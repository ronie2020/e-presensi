<?php

namespace App\Http\Controllers;

use App\Models\StudentPermit;
use App\Models\Student;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class StudentPermitController extends Controller
{
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

    public function scan(Request $request)
    {
        $request->validate(['identifier' => 'required']);

        $student = Student::where('student_id', $request->identifier)
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

        // LOGIKA CHECK-IN (KEMBALI)
        if ($existingPermit) {
            $checkInTime = Carbon::now();
            
            // [UPDATE] Pakai (int) agar angka bulat, tidak ada desimal panjang
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

        // LOGIKA PRE-CHECK-OUT (MAU KELUAR)
        return response()->json([
            'status' => 'success',
            'mode' => 'PRE_CHECK_OUT',
            'message' => "Silakan pilih alasan izin.",
            'data' => ['student' => $student]
        ]);
    }

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

        StudentPermit::create([
            'student_id' => $request->student_id,
            'pic_teacher_id' => Auth::id(),
            'reason_category' => $request->reason_category,
            'notes' => $request->notes,
            'time_out' => Carbon::now(),
            'status' => 'OUT'
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Izin berhasil dicatat.'
        ]);
    }
}