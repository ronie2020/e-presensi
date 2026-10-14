<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
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
    public function getDescriptors()
    {
        try {
            $students = Student::with('schoolClass:id,name')
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
                });

            return response()->json([
                'status' => 'success',
                'count' => $students->count(),
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
}
