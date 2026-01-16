<?php

namespace App\Http\Controllers;

use App\Models\StudentPermit;
use App\Models\Student;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class StudentPermitController extends Controller
{
    /**
     * Halaman Utama (Dashboard Monitoring)
     */
    public function index()
    {
        $activePermits = StudentPermit::with(['student.schoolClass']) 
            ->where('status', 'OUT')
            ->orderBy('time_out', 'asc') 
            ->get();

        $todayHistory = StudentPermit::with(['student.schoolClass'])
            ->where('status', 'RETURNED')
            ->whereDate('created_at', Carbon::today())
            ->latest('time_in')
            ->limit(10)
            ->get();

        return view('permit.index', compact('activePermits', 'todayHistory'));
    }

    /**
     * Helper: Filter Query (Agar tidak menulis ulang logika yang sama)
     */
    private function getFilteredQuery(Request $request)
    {
        $query = StudentPermit::with(['student.schoolClass'])
            ->latest('time_out');

        // 1. Filter Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function (Builder $q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        // 2. Filter Tanggal
        if ($request->filled('date')) {
            $query->whereDate('time_out', $request->date);
        }

        // 3. Filter Status
        if ($request->filled('status')) {
            if ($request->status == 'active') {
                $query->where('status', 'OUT');
            } elseif ($request->status == 'returned') {
                $query->where('status', 'RETURNED');
            } elseif ($request->status == 'overdue') {
                $query->where('status', 'OUT')
                      ->where('time_out', '<=', Carbon::now()->subMinutes(15));
            }
        }

        return $query;
    }

    /**
     * Halaman Riwayat Lengkap & Laporan
     */
    public function history(Request $request)
    {
        $query = $this->getFilteredQuery($request);
        $permits = $query->paginate(10)->withQueryString();

        return view('permit.history', compact('permits'));
    }

    /**
     * [BARU] Fitur Print Laporan (PDF View)
     */
    public function print(Request $request)
    {
        // Ambil semua data sesuai filter (tanpa pagination)
        $permits = $this->getFilteredQuery($request)->get();
        
        // Judul Laporan dinamis
        $title = 'Laporan Izin Siswa';
        if($request->filled('date')) {
            $title .= ' - Tanggal ' . Carbon::parse($request->date)->translatedFormat('d F Y');
        }

        return view('permit.print', compact('permits', 'title'));
    }

    /**
     * [BARU] Fitur Export Excel
     */
    public function export(Request $request)
    {
        $permits = $this->getFilteredQuery($request)->get();
        $filename = 'Laporan_Izin_' . date('Y-m-d_H-i') . '.xls';

        // Header agar browser mendownload sebagai Excel
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");

        return view('permit.excel', compact('permits'));
    }

    /**
     * Handle Scan QR / Input Manual (Cek Data Siswa)
     */
    public function scan(Request $request)
    {
        $request->validate(['identifier' => 'required']);

        $student = Student::with('schoolClass')->where('student_id', $request->identifier)
            ->orWhere('nisn', $request->identifier)
            ->orWhere('rfid_id', $request->identifier)
            ->first();

        if (!$student) {
            return response()->json([
                'status' => 'error',
                'message' => 'Siswa tidak ditemukan!',
            ], 404);
        }

        $existingPermit = StudentPermit::where('student_id', $student->id)
            ->where('status', 'OUT')
            ->first();

        if ($existingPermit) {
            $checkInTime = Carbon::now();
            $duration = (int) $existingPermit->time_out->diffInMinutes($checkInTime);

            $existingPermit->update([
                'time_in' => $checkInTime,
                'status' => 'RETURNED',
                'duration_minutes' => $duration
            ]);

            return response()->json([
                'status' => 'success',
                'mode' => 'CHECK_IN',
                'message' => "Selamat Datang Kembali, {$student->name}.",
                'data' => [
                    'student' => $student,
                    'duration' => $duration
                ]
            ]);
        }

        return response()->json([
            'status' => 'success',
            'mode' => 'PRE_CHECK_OUT',
            'message' => "Silakan pilih alasan izin.",
            'data' => ['student' => $student]
        ]);
    }

    /**
     * Simpan Data Izin Baru (Check-Out)
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'reason_category' => 'required',
            'notes' => 'nullable|string'
        ]);

        if (StudentPermit::where('student_id', $request->student_id)->where('status', 'OUT')->exists()) {
            return response()->json(['status' => 'error', 'message' => 'Siswa sudah tercatat di luar!'], 422);
        }

        $permit = StudentPermit::create([
            'student_id' => $request->student_id,
            'pic_teacher_id' => Auth::id(),
            'reason_category' => $request->reason_category,
            'notes' => $request->notes,
            'time_out' => Carbon::now(),
            'status' => 'OUT'
        ]);

        $permit->load('student');

        return response()->json([
            'status' => 'success',
            'message' => 'Izin berhasil dicatat.',
            'data' => [
                'student' => $permit->student,
                'reason' => $permit->reason_category
            ]
        ]);
    }
}