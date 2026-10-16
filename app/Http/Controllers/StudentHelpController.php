<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\CbtExam;

class StudentHelpController extends Controller
{
    /**
     * Cari NISN Mandiri untuk Siswa yang Lupa Akun/Nomor Induk
     */
    public function searchNisn(Request $request)
    {
        $query = trim($request->input('query', ''));
        $classId = $request->input('class_id');

        if (strlen($query) < 2 && empty($classId)) {
            return response()->json([
                'success' => false,
                'message' => 'Ketik minimal 2 karakter nama untuk mencari data.',
                'data' => []
            ]);
        }

        $studentsQuery = Student::with('schoolClass')
            ->select('id', 'name', 'student_id', 'nis', 'nisn', 'class_id', 'status')
            ->where(function($q) {
                $q->whereNull('status')
                  ->orWhere('status', '!=', 'graduated');
            });

        if (!empty($query)) {
            $studentsQuery->where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('nickname', 'LIKE', "%{$query}%");
            });
        }

        if (!empty($classId)) {
            $studentsQuery->where('class_id', $classId);
        }

        $students = $studentsQuery->orderBy('name', 'asc')
            ->limit(15)
            ->get()
            ->map(function($student) {
                return [
                    'id'         => $student->id,
                    'name'       => $student->name,
                    'nisn'       => $student->student_id ?: ($student->nisn ?: $student->nis),
                    'nis'        => $student->nis,
                    'class_name' => $student->schoolClass->name ?? 'Belum Ada Kelas',
                ];
            });

        return response()->json([
            'success' => true,
            'count'   => $students->count(),
            'data'    => $students
        ]);
    }

    /**
     * Ambil data kontak bantuan & jadwal ujian aktif (CBT)
     */
    public function getHelpData()
    {
        $classes = SchoolClass::orderBy('name', 'asc')
            ->select('id', 'name')
            ->get();

        $todayExams = CbtExam::where('is_active', 1)
            ->whereDate('start_time', '<=', now()->toDateString())
            ->whereDate('end_time', '>=', now()->toDateString())
            ->select('id', 'title', 'subject_name', 'start_time', 'end_time')
            ->orderBy('start_time', 'asc')
            ->limit(3)
            ->get();

        return response()->json([
            'classes'     => $classes,
            'today_exams' => $todayExams,
            'contacts'    => [
                'helpdesk_phone'    => '085135961994',
                'helpdesk_wa'       => '6285135961994',
                'helpdesk_name'     => 'Operator SIMADU SMPN 3 Lakbok',
                'helpdesk_hours'    => 'Senin - Sabtu: 07.00 - 15.30 WIB',
                'bk_counselor_name' => 'Guru Bimbingan Konseling (BK)',
                'proktor_name'      => 'Tim Proktor & Teknisi CBT',
            ]
        ]);
    }
}
