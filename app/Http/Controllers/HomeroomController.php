<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\AttendanceSiswa;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeroomController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // 1. Cek apakah ini Admin/Kepala Sekolah
        $isAdminOrKepsek = $user->hasRole(['Admin', 'Kepala Sekolah']);

        if ($isAdminOrKepsek) {
            // Jika Admin, dia bisa memilih kelas dari dropdown (opsional). 
            // Defaultnya, tampilkan kelas pertama di database.
            if ($request->has('class_id')) {
                $class = SchoolClass::find($request->class_id);
            } else {
                $class = SchoolClass::first();
            }

            // Jika database kelas benar-benar kosong
            if (!$class) {
                return redirect()->route('dashboard')->with('error', 'Tabel kelas masih kosong. Buat data kelas terlebih dahulu.');
            }
            
            // Ambil semua daftar kelas untuk keperluan navigasi Admin
            $allClasses = SchoolClass::orderBy('name', 'asc')->get();
            
        } else {
            // Jika BUKAN Admin (artinya murni Wali Kelas)
            $class = SchoolClass::where('homeroom_teacher_id', $user->id)->first();
            
            if (!$class) {
                return redirect()->route('dashboard')->with('error', 'Anda tidak terdaftar sebagai Wali Kelas untuk kelas mana pun.');
            }
            
            $allClasses = null; // Wali kelas tidak butuh navigasi kelas lain
        }

        // 2. Ambil data siswa di kelas tersebut
        $students = Student::with(['disciplineRecords.disciplineType', 'attendances', 'habits'])
            ->where('class_id', $class->id)
            ->where('status', 'active')
            ->get();

        // 3. Olah Data untuk Dashboard
        $totalStudents = $students->count();
        
        $stats = [
            'total_students' => $totalStudents,
            'total_violations' => 0,
            'total_merits' => 0,
            'alfa_count' => 0,
        ];

        $warningStudents = collect();
        $topStudents = collect();

        foreach ($students as $student) {
            $violationPoints = $student->disciplineRecords->where('disciplineType.type', 'Pelanggaran')->sum('disciplineType.point_value');
            $meritPoints = $student->disciplineRecords->where('disciplineType.type', 'Kebaikan')->sum('disciplineType.point_value');
            
            $stats['total_violations'] += $violationPoints;
            $stats['total_merits'] += $meritPoints;

            $alfaThisMonth = $student->attendances
                ->where('attendance_date', '>=', Carbon::now()->startOfMonth())
                ->whereIn('status', ['Alfa', 'Alpa', 'Tanpa Keterangan'])
                ->count();
            
            $stats['alfa_count'] += $alfaThisMonth;

            // DETEKSI DINI
            if ($violationPoints >= 50 || $alfaThisMonth >= 3) {
                $warningStudents->push((object)[
                    'id' => $student->id,
                    'name' => $student->name,
                    'photo' => $student->photo_path,
                    'violation_points' => $violationPoints,
                    'alfa_count' => $alfaThisMonth,
                    'issue' => $alfaThisMonth >= 3 ? 'Sering Alpa' : 'Pelanggaran Tinggi'
                ]);
            }

            // LEADERBOARD
            if ($meritPoints > 0) {
                $topStudents->push((object)[
                    'name' => $student->name,
                    'photo' => $student->photo_path,
                    'merit_points' => $meritPoints,
                ]);
            }
        }

        $warningStudents = $warningStudents->sortByDesc('violation_points')->take(10);
        $topStudents = $topStudents->sortByDesc('merit_points')->take(5);

        // Lempar variabel isAdminOrKepsek dan allClasses ke View
        return view('homeroom.dashboard', compact('class', 'stats', 'warningStudents', 'topStudents', 'isAdminOrKepsek', 'allClasses'));
    }
}