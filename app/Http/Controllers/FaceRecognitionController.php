<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Carbon\Carbon;

class FaceRecognitionController extends Controller
{
    /**
     * Halaman Utama Registrasi / Enrolment Wajah Siswa
     */
    public function index(Request $request)
    {
        $classes = SchoolClass::orderBy('name', 'asc')->get();
        $selectedClassId = $request->get('class_id');
        $search = $request->get('search');
        $statusFilter = $request->get('status'); // 'registered', 'unregistered'

        $query = Student::with('schoolClass')
            ->where('status', 'active');

        if ($selectedClassId) {
            $query->where('class_id', $selectedClassId);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        if ($statusFilter === 'registered') {
            $query->whereNotNull('face_descriptor');
        } elseif ($statusFilter === 'unregistered') {
            $query->whereNull('face_descriptor');
        }

        $students = $query->orderBy('name', 'asc')->paginate(20)->withQueryString();

        // Statistik
        $totalActive = Student::where('status', 'active')->count();
        $totalRegistered = Student::where('status', 'active')->whereNotNull('face_descriptor')->count();
        $totalUnregistered = $totalActive - $totalRegistered;
        
        // Kandidat siap diekstrak massal (punya pas foto resmi tapi belum ada biometrik)
        $totalCandidates = Student::where('status', 'active')
            ->whereNotNull('photo_path')
            ->where('photo_path', '!=', '')
            ->whereNull('face_descriptor')
            ->count();

        return view('students.face_enrollment', compact(
            'classes',
            'students',
            'selectedClassId',
            'search',
            'statusFilter',
            'totalActive',
            'totalRegistered',
            'totalUnregistered',
            'totalCandidates'
        ));
    }

    /**
     * Endpoint API: Ambil daftar siswa yang memiliki foto namun belum terdaftar biometriknya untuk ekstraksi massal.
     */
    public function batchCandidates(Request $request)
    {
        $classId = $request->get('class_id');

        $query = Student::where('status', 'active')
            ->whereNotNull('photo_path')
            ->where('photo_path', '!=', '')
            ->whereNull('face_descriptor');

        if ($classId) {
            $query->where('class_id', $classId);
        }

        $students = $query->with('schoolClass:id,name')
            ->orderBy('name', 'asc')
            ->get()
            ->map(function ($s) {
                return [
                    'id' => $s->id,
                    'student_id' => $s->student_id,
                    'nisn' => $s->nisn,
                    'name' => $s->name,
                    'class_name' => $s->schoolClass ? $s->schoolClass->name : '-',
                    'photo_url' => $s->photo_path ? (str_starts_with($s->photo_path, 'http') ? $s->photo_path : asset('storage/' . $s->photo_path)) : null,
                ];
            });

        return response()->json([
            'status' => 'success',
            'count' => $students->count(),
            'students' => $students
        ]);
    }

    /**
     * Endpoint API: Ambil semua descriptor wajah siswa aktif untuk Kiosk & Mobile Scanner
     */
    /**
     * Endpoint API: Ambil semua descriptor wajah siswa aktif untuk Kiosk & Mobile Scanner
     * Dioptimalkan dengan Cache::remember (1 jam) untuk mencegah lonjakan query database di jam 07:00 pagi
     */
    public function getDescriptors()
    {
        try {
            $students = Cache::remember('kiosk_face_descriptors', 3600, function () {
                return Student::with('schoolClass:id,name')
                    ->where('status', 'active')
                    ->whereNotNull('face_descriptor')
                    ->select('id', 'student_id', 'nisn', 'name', 'class_id', 'photo_path', 'face_descriptor')
                    ->get()
                    ->map(function ($s) {
                        $photoUrl = null;
                        if ($s->photo_path) {
                            $photoUrl = str_starts_with($s->photo_path, 'http')
                                ? $s->photo_path
                                : asset('storage/' . $s->photo_path);
                        }

                        return [
                            'id' => $s->id,
                            'student_id' => $s->student_id,
                            'nisn' => $s->nisn,
                            'name' => $s->name,
                            'class_name' => $s->schoolClass->name ?? '-',
                            'photo_url' => $photoUrl,
                            'descriptor' => $s->face_descriptor,
                        ];
                    })->values()->all();
            });

            return response()->json([
                'status' => 'success',
                'count' => count($students),
                'data' => $students
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error fetching face descriptors: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data descriptor wajah: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Simpan Vektor Descriptor Wajah Siswa
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id'     => 'required',
            'descriptor'     => 'required|array|min:128|max:128',
            'image'          => 'nullable|string', // Base64 dataURL opsional untuk foto terbaru
            'set_as_profile' => 'nullable|boolean', // Opsi izin operator untuk menyimpan foto profil
        ]);

        $student = Student::where('id', $request->student_id)
            ->orWhere('student_id', $request->student_id)
            ->first();

        if (!$student) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data siswa tidak ditemukan.'
            ], 404);
        }

        try {
            $photoUpdated = false;

            // Hanya perbarui foto profil siswa jika operator secara eksplisit mengizinkan
            if ($request->boolean('set_as_profile') && $request->filled('image') && str_starts_with($request->image, 'data:image')) {
                $imageParts = explode(';base64,', $request->image);
                if (count($imageParts) === 2) {
                    $imageBase64 = base64_decode($imageParts[1]);
                    $fileName = 'students/faces/' . $student->id . '_' . time() . '.jpg';
                    Storage::disk('public')->put($fileName, $imageBase64);
                    $student->photo_path = $fileName;
                    $photoUpdated = true;
                }
            }

            // Simpan Vektor Wajah
            $student->face_descriptor = $request->descriptor;
            $student->face_registered_at = Carbon::now();
            $student->save();

            // Invalidate cache descriptor agar Kiosk & Scanner segera mendapatkan data teranyar
            Cache::forget('kiosk_face_descriptors');

            $message = $photoUpdated 
                ? "Wajah dan foto profil {$student->name} berhasil diperbarui!"
                : "Wajah biometrik {$student->name} berhasil didaftarkan! (Foto resmi tetap terlindungi)";

            return response()->json([
                'status' => 'success',
                'message' => $message,
                'student' => [
                    'id' => $student->id,
                    'name' => $student->name,
                    'student_id' => $student->student_id,
                    'photo_path' => $student->photo_path ? asset('storage/' . $student->photo_path) : null,
                    'face_registered_at' => $student->face_registered_at->translatedFormat('d M Y H:i'),
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error("Face Store Error: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem saat menyimpan data wajah: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus Data Biometrik Wajah Siswa
     */
    public function destroy($id)
    {
        $student = Student::findOrFail($id);

        try {
            $student->face_descriptor = null;
            $student->face_registered_at = null;
            $student->save();

            // Invalidate cache descriptor
            Cache::forget('kiosk_face_descriptors');

            return response()->json([
                'status' => 'success',
                'message' => "Data biometrik wajah {$student->name} berhasil dihapus."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus data wajah: ' . $e->getMessage()
            ], 500);
        }
    }

    // =========================================================================
    //  TAMBAHAN LOKAL: REKAM MANDIRI PORTAL SISWA & VERIFIKASI WALI KELAS/ADMIN
    // =========================================================================

    /**
     * Pastikan kolom face recognition tambahan tersedia di tabel students (auto-guard non-destruktif).
     */
    public static function ensureFaceColumnsExist(): void
    {
        try {
            if (!Schema::hasColumn('students', 'face_status')) {
                Schema::table('students', function (Blueprint $table) {
                    if (!Schema::hasColumn('students', 'face_descriptor')) {
                        $table->longText('face_descriptor')->nullable()->after('photo_path');
                    }
                    if (!Schema::hasColumn('students', 'face_photo_path')) {
                        $table->string('face_photo_path')->nullable()->after('face_descriptor');
                    }
                    if (!Schema::hasColumn('students', 'face_status')) {
                        $table->string('face_status', 30)->default('unregistered')->after('face_photo_path');
                    }
                    if (!Schema::hasColumn('students', 'face_registered_at')) {
                        $table->timestamp('face_registered_at')->nullable()->after('face_status');
                    }
                    if (!Schema::hasColumn('students', 'face_verified_at')) {
                        $table->timestamp('face_verified_at')->nullable()->after('face_registered_at');
                    }
                    if (!Schema::hasColumn('students', 'face_verified_by')) {
                        $table->unsignedBigInteger('face_verified_by')->nullable()->after('face_verified_at');
                    }
                    if (!Schema::hasColumn('students', 'face_rejection_note')) {
                        $table->string('face_rejection_note')->nullable()->after('face_verified_by');
                    }
                });
            }
        } catch (\Exception $e) {
            // Abaikan jika gagal cek schema
        }
    }

    /**
     * Halaman Rekam Mandiri Face Recognition di Portal Siswa
     */
    public function studentIndex()
    {
        self::ensureFaceColumnsExist();

        $student = Auth::guard('student')->user();
        if (!$student) {
            return redirect()->route('portal.index')->with('error', 'Silakan masuk ke Portal Siswa terlebih dahulu.');
        }

        $student->load('schoolClass');

        return view('students.portal.face_enrollment', compact('student'));
    }

    /**
     * Proses Simpan Rekam Mandiri Face Recognition oleh Siswa
     * Status menjadi 'pending' (Menunggu Verifikasi Wali Kelas/Admin),
     * namun langsung aktif & bisa digunakan di Kiosk dan Scan.
     */
    public function studentStore(Request $request)
    {
        self::ensureFaceColumnsExist();

        $student = Auth::guard('student')->user();
        if (!$student) {
            return response()->json(['status' => 'error', 'message' => 'Sesi login berakhir. Silakan muat ulang halaman.'], 401);
        }

        // Cek apakah sudah terdaftar dan terkunci (pending atau verified)
        if (!empty($student->face_descriptor) && in_array($student->face_status, ['pending', 'verified'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data Face ID Anda sudah terdaftar dan terkunci. Hubungi Wali Kelas atau Admin jika ingin mereset data wajah.'
            ], 403);
        }

        $request->validate([
            'descriptor'    => 'required|string',
            'photo'         => 'required|string',
            'security_code' => 'required|string',
        ]);

        // Validasi Keamanan Lapis Kedua (NIK atau NISN/NIS Siswa)
        $inputCode = trim($request->security_code);
        $validCodes = array_filter([
            $student->nik ? trim($student->nik) : null,
            $student->student_id ? trim($student->student_id) : null,
            $student->nis ? trim($student->nis) : null,
            $student->nisn ? trim($student->nisn) : null,
        ]);

        if (!in_array($inputCode, $validCodes)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kode Konfirmasi (NISN / NIK) tidak cocok dengan identitas akun Anda!'
            ], 422);
        }

        // Validasi format descriptor (128 float array)
        $descriptorArray = json_decode($request->descriptor, true);
        if (!is_array($descriptorArray) || count($descriptorArray) !== 128) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data vektor wajah tidak valid (harus 128 titik fitur wajah). Silakan ulangi pemindaian.'
            ], 422);
        }

        // Normalisasi ke float
        $descriptorArray = array_map('floatval', array_values($descriptorArray));

        // Cek Duplikasi Wajah Antar-Siswa (Anti-Joki: 1 Wajah = 1 Akun Siswa)
        $otherStudents = Student::where('id', '!=', $student->id)
            ->where('status', 'active')
            ->whereNotNull('face_descriptor')
            ->select('id', 'name', 'student_id', 'face_descriptor')
            ->get();

        foreach ($otherStudents as $other) {
            $rawOther = $other->face_descriptor;
            $otherDesc = is_array($rawOther) ? $rawOther : json_decode((string) $rawOther, true);
            if (is_array($otherDesc) && count($otherDesc) === 128) {
                $distance = $this->euclideanDistance($descriptorArray, $otherDesc);
                if ($distance < 0.38) {
                    return response()->json([
                        'status' => 'error',
                        'message' => "Wajah ini terdeteksi sangat mirip dengan akun siswa lain yang sudah terdaftar. Satu wajah hanya dapat didaftarkan untuk satu akun siswa."
                    ], 422);
                }
            }
        }

        // Simpan foto hasil tangkapan kamera
        $savedPhotoPath = $this->saveBase64Photo($request->photo, $student->student_id ?? $student->id);
        if (!$savedPhotoPath) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan file foto wajah. Pastikan kamera aktif dengan benar.'
            ], 422);
        }

        // Hapus foto face lama jika ada
        if ($student->face_photo_path && $student->face_photo_path !== $student->photo_path && Storage::disk('public')->exists($student->face_photo_path)) {
            Storage::disk('public')->delete($student->face_photo_path);
        }

        $updateData = [
            'face_descriptor'     => $descriptorArray,
            'face_photo_path'     => $savedPhotoPath,
            'face_status'         => 'pending',
            'face_registered_at'  => now(),
            'face_verified_at'    => null,
            'face_verified_by'    => null,
            'face_rejection_note' => null,
        ];

        // Jika siswa belum punya foto profil utama atau foto sebelumnya sudah dihapus, gunakan foto wajah ini sebagai foto profil juga
        if (empty($student->photo_path) || !Storage::disk('public')->exists($student->photo_path)) {
            $updateData['photo_path'] = $savedPhotoPath;
        }

        $student->update($updateData);

        Cache::forget('kiosk_face_descriptors');

        return response()->json([
            'status'       => 'success',
            'message'      => 'Perekaman Face ID berhasil! Wajah Anda kini sudah AKTIF untuk absensi di Mesin Kiosk & Scan, serta sedang dalam antrean verifikasi Wali Kelas/Admin.',
            'redirect_url' => route('portal.show', $student->id)
        ]);
    }

    /**
     * Perekaman / Update Face ID langsung oleh Admin / Guru / Wali Kelas (Langsung Terverifikasi)
     */
    public function adminStore(Request $request, Student $student)
    {
        self::ensureFaceColumnsExist();

        $request->validate([
            'descriptor'           => 'required|string',
            'photo'                => 'required|string',
            'update_profile_photo' => 'nullable|boolean',
        ]);

        $descriptorArray = json_decode($request->descriptor, true);
        if (!is_array($descriptorArray) || count($descriptorArray) !== 128) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data vektor wajah tidak valid (harus 128 titik fitur wajah).'
            ], 422);
        }

        $descriptorArray = array_map('floatval', array_values($descriptorArray));

        $savedPhotoPath = $this->saveBase64Photo($request->photo, $student->student_id ?? $student->id);
        if (!$savedPhotoPath) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menyimpan foto wajah.'
            ], 422);
        }

        if ($student->face_photo_path && $student->face_photo_path !== $student->photo_path && Storage::disk('public')->exists($student->face_photo_path)) {
            Storage::disk('public')->delete($student->face_photo_path);
        }

        $updateData = [
            'face_descriptor'     => $descriptorArray,
            'face_photo_path'     => $savedPhotoPath,
            'face_status'         => 'verified',
            'face_registered_at'  => now(),
            'face_verified_at'    => now(),
            'face_verified_by'    => Auth::id(),
            'face_rejection_note' => null,
        ];

        if ($request->boolean('update_profile_photo', true) || empty($student->photo_path)) {
            if ($student->photo_path && $student->photo_path !== $savedPhotoPath && Storage::disk('public')->exists($student->photo_path)) {
                Storage::disk('public')->delete($student->photo_path);
            }
            $updateData['photo_path'] = $savedPhotoPath;
        }

        $student->update($updateData);

        Cache::forget('kiosk_face_descriptors');

        if ($request->expectsJson()) {
            return response()->json([
                'status'     => 'success',
                'message'    => "Data Face ID & Foto untuk {$student->name} berhasil disimpan dan diverifikasi.",
                'photo_url'  => asset('storage/' . ($updateData['photo_path'] ?? $savedPhotoPath)),
                'face_status'=> 'verified'
            ]);
        }

        return back()->with('success', "Data Face ID untuk {$student->name} berhasil direkam dan diverifikasi.");
    }

    /**
     * Verifikasi / Setujui Rekaman Wajah Mandiri Siswa oleh Admin / Wali Kelas / Guru
     */
    public function verify(Request $request, Student $student)
    {
        self::ensureFaceColumnsExist();

        if (empty($student->face_descriptor)) {
            if ($request->expectsJson()) {
                return response()->json(['status' => 'error', 'message' => 'Siswa ini belum memiliki rekaman Face ID.'], 422);
            }
            return back()->with('error', 'Siswa ini belum memiliki rekaman Face ID.');
        }

        $updateData = [
            'face_status'         => 'verified',
            'face_verified_at'    => now(),
            'face_verified_by'    => Auth::id(),
            'face_rejection_note' => null,
        ];

        if ($student->face_photo_path && (empty($student->photo_path) || $request->boolean('sync_profile_photo', false))) {
            $updateData['photo_path'] = $student->face_photo_path;
        }

        $student->update($updateData);

        Cache::forget('kiosk_face_descriptors');

        if ($request->expectsJson()) {
            return response()->json([
                'status'  => 'success',
                'message' => "Rekaman Face ID {$student->name} berhasil diverifikasi!"
            ]);
        }

        return back()->with('success', "Rekaman Face ID {$student->name} berhasil diverifikasi.");
    }

    /**
     * Verifikasi Massal (Bulk Verify) semua siswa berstatus 'pending' di kelas / daftar terpilih
     */
    public function bulkVerify(Request $request)
    {
        self::ensureFaceColumnsExist();

        $query = Student::where('face_status', 'pending')->whereNotNull('face_descriptor');

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        } elseif ($request->filled('ids')) {
            $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);
            $query->whereIn('id', $ids);
        } elseif (Auth::user()->role === 'Wali Kelas' && Auth::user()->homeroomClass) {
            $query->where('class_id', Auth::user()->homeroomClass->id);
        }

        $count = $query->count();
        if ($count === 0) {
            return back()->with('error', 'Tidak ada antrean Face ID berstatus menunggu verifikasi.');
        }

        $query->update([
            'face_status'         => 'verified',
            'face_verified_at'    => now(),
            'face_verified_by'    => Auth::id(),
            'face_rejection_note' => null,
        ]);

        Cache::forget('kiosk_face_descriptors');

        return back()->with('success', "{$count} data Face ID siswa berhasil diverifikasi sekaligus.");
    }

    /**
     * Hapus Foto Siswa yang Salah / Tidak Sesuai & Reset Face Recognition
     * Membuka kembali kunci rekam mandiri di Portal Siswa agar siswa bisa merekam ulang.
     */
    public function resetOrDeletePhoto(Request $request, Student $student)
    {
        self::ensureFaceColumnsExist();

        $deleteMode = $request->input('delete_mode', $request->input('reset_type', 'both')); // 'both' | 'face_only' | 'photo_only'
        $reason = $request->input('rejection_note', 'Foto atau data wajah tidak sesuai ketentuan. Silakan lakukan perekaman ulang dengan wajah terlihat jelas dan berseragam rapi.');

        $oldPhotoPath = $student->photo_path;
        $oldFacePhotoPath = $student->face_photo_path;

        $updateData = [];

        if (in_array($deleteMode, ['both', 'face_only'])) {
            if ($oldFacePhotoPath && Storage::disk('public')->exists($oldFacePhotoPath)) {
                if ($deleteMode === 'both' || $oldFacePhotoPath !== $oldPhotoPath) {
                    Storage::disk('public')->delete($oldFacePhotoPath);
                }
            }
            $updateData['face_descriptor']     = null;
            $updateData['face_photo_path']     = null;
            $updateData['face_status']         = 'rejected';
            $updateData['face_registered_at']  = null;
            $updateData['face_verified_at']    = null;
            $updateData['face_verified_by']    = null;
            $updateData['face_rejection_note'] = $reason;
        }

        if (in_array($deleteMode, ['both', 'photo_only'])) {
            if ($oldPhotoPath && Storage::disk('public')->exists($oldPhotoPath)) {
                Storage::disk('public')->delete($oldPhotoPath);
            }
            $updateData['photo_path'] = null;
        }

        $student->update($updateData);

        Cache::forget('kiosk_face_descriptors');

        $msg = match ($deleteMode) {
            'photo_only' => "Foto profil {$student->name} berhasil dihapus.",
            'face_only'  => "Data Face ID {$student->name} berhasil direset. Siswa dapat merekam ulang di Portal Siswa.",
            default      => "Foto & Data Face ID {$student->name} yang tidak sesuai berhasil dihapus. Siswa kini dapat merekam ulang di Portal Siswa.",
        };

        if ($request->expectsJson()) {
            return response()->json([
                'status'  => 'success',
                'message' => $msg
            ]);
        }

        return back()->with('success', $msg);
    }

    /**
     * Helper: Hitung Euclidean Distance antara 2 vektor 128-float
     */
    private function euclideanDistance(array $a, array $b): float
    {
        $sum = 0.0;
        for ($i = 0; $i < 128; $i++) {
            $diff = ($a[$i] ?? 0.0) - ($b[$i] ?? 0.0);
            $sum += $diff * $diff;
        }
        return sqrt($sum);
    }

    /**
     * Helper: Simpan Base64 Image ke Storage Public
     */
    private function saveBase64Photo(string $base64Data, string $identifier): ?string
    {
        try {
            if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $type)) {
                $data = substr($base64Data, strpos($base64Data, ',') + 1);
                $ext = strtolower($type[1]);
                if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                    $ext = 'jpg';
                }
                $decoded = base64_decode($data);
                if ($decoded === false) {
                    return null;
                }
                $cleanId = preg_replace('/[^A-Za-z0-9_\-]/', '', $identifier);
                $fileName = 'students/faces/face_' . $cleanId . '_' . time() . '.' . $ext;
                Storage::disk('public')->put($fileName, $decoded);
                return $fileName;
            }
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }
}
