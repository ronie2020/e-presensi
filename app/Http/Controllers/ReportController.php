<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\AttendanceSiswa;
// Import Model Disiplin & Tipe Disiplin
use App\Models\DisciplineRecord;
use App\Models\DisciplineType; 
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Pagination\LengthAwarePaginator; 
use Illuminate\Pagination\Paginator; 

class ReportController extends Controller
{
    /**
     * =========================================================================
     * BAGIAN 1: LAPORAN HARIAN
     * =========================================================================
     */
    public function dailyReport(Request $request)
    {
        $request->validate(['date' => 'nullable|date']);
        $selectedDate = $request->has('date') ? Carbon::parse($request->date)->startOfDay() : Carbon::today()->startOfDay();

        // 1. Ambil Data Mentah
        $rawAttendances = AttendanceSiswa::with('student.schoolClass')
            ->whereDate('attendance_date', $selectedDate)
            ->whereIn('type', ['Harian', 'Masuk', 'Pulang'])
            ->get();

        // 2. Grouping Data & Menentukan Status Final
        $processedAttendances = $rawAttendances->groupBy('student_id')->map(function ($logs) {
            $firstLog = $logs->sortBy('created_at')->first();
            
            $timeIn = $logs->whereNotNull('time_in')->pluck('time_in')->sort()->first(); 
            
            // Logic ambil jam pulang
            $scanPulang = $logs->where('type', 'Pulang')->first();
            $timeOut = null;
            if ($scanPulang) {
                $timeOut = $scanPulang->time_in; 
            } elseif ($firstLog->time_out) {
                $timeOut = $firstLog->time_out;
            } else {
                $timeOut = $logs->whereNotNull('time_out')->pluck('time_out')->sortDesc()->first();
            }

            $allNotes = $logs->pluck('notes')->filter()->unique()->implode(' | ');
            $statuses = $logs->pluck('status')->toArray();
            
            // Logic Penentuan Status Final
            $finalStatus = $firstLog->status; 
            
            if (in_array('Hadir', $statuses)) $finalStatus = 'Hadir';
            elseif (in_array('Terlambat', $statuses)) $finalStatus = 'Terlambat';
            elseif (in_array('Sakit', $statuses)) $finalStatus = 'Sakit';
            elseif (in_array('Izin', $statuses)) $finalStatus = 'Izin';
            elseif (in_array('Alfa', $statuses)) $finalStatus = 'Alfa';

            $firstLog->time_in_final = $timeIn;
            $firstLog->time_out_final = $timeOut;
            $firstLog->status_final = $finalStatus;
            $firstLog->notes_final = $allNotes;
            
            return $firstLog;
        });

        // Sorting: Kelas (ASC) -> Nama (ASC)
        $processedAttendances = $processedAttendances->sort(function ($a, $b) {
            $classA = $a->student->schoolClass->name ?? 'ZZZ';
            $classB = $b->student->schoolClass->name ?? 'ZZZ';
            $classComparison = strnatcmp($classA, $classB);
            if ($classComparison === 0) {
                return strcasecmp($a->student->name, $b->student->name);
            }
            return $classComparison;
        });

        // 3. HITUNG STATISTIK
        $hadirCount = $processedAttendances->whereIn('status_final', ['Hadir', 'Terlambat'])->count();
        $terlambatCount = $processedAttendances->where('status_final', 'Terlambat')->count();
        $sakitCount = $processedAttendances->where('status_final', 'Sakit')->count();
        $izinCount = $processedAttendances->where('status_final', 'Izin')->count();
        $alfaCount = $processedAttendances->where('status_final', 'Alfa')->count();

        // 4. Ambil Siswa Belum Absen
        $allStudents = Student::with('schoolClass')
            ->join('classes', 'students.class_id', '=', 'classes.id')
            ->orderBy('classes.name', 'asc')
            ->orderBy('students.name', 'asc')
            ->select('students.*')
            ->get();

        $attendedIds = $processedAttendances->pluck('student_id');
        $belumAbsenList = $allStudents->whereNotIn('id', $attendedIds); 

        // 5. Pagination
        $perPage = 20;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $processedAttendances->slice(($currentPage - 1) * $perPage, $perPage)->all();
        
        $paginatedAttendances = new LengthAwarePaginator(
            $currentItems,
            $processedAttendances->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('reports.daily', [
            'selectedDate_db' => $selectedDate,
            'todayAttendances' => $paginatedAttendances,
            'allStudents' => $allStudents,
            'hadirCount' => $hadirCount,
            'terlambatCount' => $terlambatCount,
            'sakitCount' => $sakitCount,
            'izinCount' => $izinCount,
            'alfaCount' => $alfaCount,
            'belumAbsenList' => $belumAbsenList,
        ]);
    }

    /**
     * =========================================================================
     * BAGIAN 2: LAPORAN KEAGAMAAN
     * =========================================================================
     */
    public function religiousReport(Request $request)
    {
        $request->validate([
            'date' => 'nullable|date',
            'activity' => 'nullable|string|in:Dhuha,Dhuhur' 
        ]);

        $selectedDate = $request->has('date') ? Carbon::parse($request->date)->startOfDay() : Carbon::today()->startOfDay();
        $selectedActivity = $request->input('activity', 'Dhuha');

        $rawAttendances = AttendanceSiswa::with('student.schoolClass')
            ->whereDate('attendance_date', $selectedDate)
            ->where('type', 'Keagamaan')
            ->where('activity', $selectedActivity)
            ->get();
            
        $processedAttendances = $rawAttendances->groupBy('student_id')->map(function ($logs) {
            $firstLog = $logs->sortBy('created_at')->first();
            $allNotes = $logs->pluck('notes')->filter()->unique()->implode(' | ');
            $statuses = $logs->pluck('status')->toArray();
            
            $finalStatus = $firstLog->status;
            if (in_array('Hadir', $statuses)) {
                $finalStatus = 'Hadir';
            } elseif (in_array("Uzur Syar'i", $statuses)) {
                $finalStatus = "Uzur Syar'i";
            } elseif (in_array("Alfa", $statuses)) {
                $finalStatus = "Alfa";
            }
            
            $firstLog->status_final = $finalStatus;
            $firstLog->notes_final = $allNotes;
            return $firstLog;
        });

        $processedAttendances = $processedAttendances->sort(function ($a, $b) {
            $classA = $a->student->schoolClass->name ?? 'ZZZ';
            $classB = $b->student->schoolClass->name ?? 'ZZZ';
            $classComparison = strnatcmp($classA, $classB);
            if ($classComparison === 0) {
                return strcasecmp($a->student->name, $b->student->name);
            }
            return $classComparison;
        });

        $hadirCount = $processedAttendances->where('status_final', 'Hadir')->count();
        $izinUzurCount = $processedAttendances->where('status_final', "Uzur Syar'i")->count();
        $alfaCount = $processedAttendances->where('status_final', 'Alfa')->count();
        
        $allStudents = Student::with('schoolClass')
            ->join('classes', 'students.class_id', '=', 'classes.id')
            ->orderBy('classes.name', 'asc')
            ->orderBy('students.name', 'asc')
            ->select('students.*')
            ->get();

        $attendedIds = $processedAttendances->pluck('student_id');
        $belumAbsenList = $allStudents->whereNotIn('id', $attendedIds);

        $totalStudents = $allStudents->count();
        $kehadiranPercentage = $totalStudents > 0 ? round((($hadirCount + $izinUzurCount) / $totalStudents) * 100, 1) : 0;

        $perPage = 20;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $processedAttendances->slice(($currentPage - 1) * $perPage, $perPage)->all();
        
        $paginatedAttendances = new LengthAwarePaginator(
            $currentItems,
            $processedAttendances->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('reports.religious', [
            'todayAttendances' => $paginatedAttendances,
            'belumAbsenList' => $belumAbsenList,
            'allStudents' => $allStudents,
            'selectedDate_db' => $selectedDate,
            'selectedActivity' => $selectedActivity,
            'hadirCount' => $hadirCount,
            'izinUzurCount' => $izinUzurCount,
            'alfaCount' => $alfaCount,
            'belumAbsenCount' => $belumAbsenList->count(),
            'kehadiranPercentage' => $kehadiranPercentage
        ]);
    }

    /**
     * =========================================================================
     * BAGIAN 3: EXPORT & DELETE & MANUAL
     * =========================================================================
     */

    public function destroyDaily(Request $request)
    {
        $request->validate(['date' => 'required|date']);
        $date = Carbon::parse($request->date);
        AttendanceSiswa::whereDate('attendance_date', $date)->whereIn('type', ['Harian', 'Masuk', 'Pulang'])->delete();
        return redirect()->route('reports.daily', ['date' => $request->date])->with('success', "Data harian berhasil direset.");
    }

    public function exportDaily(Request $request)
    {
        $request->validate(['date' => 'nullable|date']);
        $date = $request->date ? Carbon::parse($request->date) : Carbon::today();

        $rawAttendances = AttendanceSiswa::with('student.schoolClass')
            ->whereDate('attendance_date', $date)
            ->whereIn('type', ['Harian', 'Masuk', 'Pulang'])
            ->get();

        $processedAttendances = $rawAttendances->groupBy('student_id')->map(function ($logs) {
            $firstLog = $logs->first();
            $timeIn = $logs->whereNotNull('time_in')->pluck('time_in')->sort()->first();
            $scanPulang = $logs->where('type', 'Pulang')->first();
            $timeOut = $firstLog->time_out ? $firstLog->time_out : ($scanPulang ? $scanPulang->time_in : null);
            $allNotes = $logs->pluck('notes')->filter()->unique()->implode(' | ');
            $statuses = $logs->pluck('status')->toArray();
            
            $finalStatus = $firstLog->status;
            if (in_array('Hadir', $statuses)) $finalStatus = 'Hadir';
            elseif (in_array('Terlambat', $statuses)) $finalStatus = 'Terlambat';
            elseif (in_array('Sakit', $statuses)) $finalStatus = 'Sakit';
            elseif (in_array('Izin', $statuses)) $finalStatus = 'Izin';
            elseif (in_array('Alfa', $statuses)) $finalStatus = 'Alfa';

            return [
                'date' => $firstLog->attendance_date,
                'name' => $firstLog->student->name ?? 'Siswa Dihapus',
                'class' => $firstLog->student->schoolClass->name ?? '-',
                'time_in' => $timeIn,
                'time_out' => $timeOut,
                'status' => $finalStatus,
                'notes' => $allNotes
            ];
        });
        
        $processedAttendances = $processedAttendances->sort(function ($a, $b) {
            $classA = $a['class'] ?? 'ZZZ';
            $classB = $b['class'] ?? 'ZZZ';
            $classComparison = strnatcmp($classA, $classB);
            if ($classComparison === 0) {
                return strcasecmp($a['name'], $b['name']);
            }
            return $classComparison;
        });

        $fileName = 'rekap_harian_' . $date->format('Ymd') . '.csv';
        $headers = ["Content-type" => "text/csv", "Content-Disposition" => "attachment; filename=$fileName", "Pragma" => "no-cache", "Cache-Control" => "must-revalidate", "post-check=0, pre-check=0", "Expires" => "0"];
        $columns = ['Tanggal', 'Nama Siswa', 'Kelas', 'Jam Masuk', 'Jam Pulang', 'Status', 'Keterangan'];

        $callback = function() use ($processedAttendances, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($processedAttendances as $item) {
                fputcsv($file, array_values($item));
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function destroyReligious(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'activity' => 'required|string|in:Dhuha,Dhuhur'
        ]);
        $date = Carbon::parse($request->date);
        AttendanceSiswa::whereDate('attendance_date', $date)
            ->where('type', 'Keagamaan')
            ->where('activity', $request->activity)
            ->delete();

        return redirect()->route('reports.religious', ['date' => $request->date, 'activity' => $request->activity])
            ->with('success', "Berhasil menghapus data {$request->activity}.");
    }

    public function exportReligious(Request $request)
    {
        $request->validate(['date' => 'nullable|date', 'activity' => 'nullable|string']);
        $date = $request->date ? Carbon::parse($request->date) : Carbon::today();
        $activity = $request->input('activity', 'Dhuha');

        $attendances = AttendanceSiswa::with('student.schoolClass')
            ->whereDate('attendance_date', $date)
            ->where('type', 'Keagamaan')->where('activity', $activity)
            ->get(); 

        $attendances = $attendances->sort(function($a, $b) {
            $classA = $a->student->schoolClass->name ?? 'ZZZ';
            $classB = $b->student->schoolClass->name ?? 'ZZZ';
            $cmp = strnatcmp($classA, $classB);
            if ($cmp === 0) {
                return strcasecmp($a->student->name ?? '', $b->student->name ?? '');
            }
            return $cmp;
        });

        $fileName = 'rekap_' . $activity . '_' . $date->format('Ymd') . '.csv';
        $headers = ["Content-type" => "text/csv", "Content-Disposition" => "attachment; filename=$fileName", "Pragma" => "no-cache", "Cache-Control" => "must-revalidate", "post-check=0, pre-check=0", "Expires" => "0"];
        $columns = ['Tanggal', 'Kegiatan', 'Nama', 'Kelas', 'Status', 'Keterangan', 'Waktu'];

        $callback = function() use ($attendances, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($attendances as $item) {
                fputcsv($file, [
                    $item->attendance_date, $item->activity, $item->student->name ?? '-', $item->student->schoolClass->name ?? '-', $item->status, $item->notes, $item->created_at->format('H:i')
                ]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function storeManualEntry(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'status' => 'required|string', 
            'attendance_type' => 'required|in:Harian,Keagamaan',
            'activity' => 'nullable|required_if:attendance_type,Keagamaan|in:Dhuha,Dhuhur',
            'notes' => 'nullable|string',
            'date' => 'nullable|date',
        ]);

        $date = $request->date ? Carbon::parse($request->date)->toDateString() : Carbon::today()->toDateString();
        $timeNow = Carbon::now()->toTimeString();

        try {
            DB::transaction(function () use ($request, $date, $timeNow) {
                // 1. Simpan Data Absensi (Harian & Keagamaan TETAP DICATAT)
                $matchConditions = [
                    'student_id' => $request->student_id,
                    'attendance_date' => $date,
                    'type' => $request->attendance_type,
                ];
                if ($request->attendance_type === 'Keagamaan') {
                    $matchConditions['activity'] = $request->activity;
                }

                AttendanceSiswa::updateOrCreate(
                    $matchConditions,
                    [
                        'status' => $request->status,
                        'notes' => $request->notes ?? 'Manual Entry',
                        'time_in' => DB::raw('COALESCE(time_in, "'.$timeNow.'")') 
                    ]
                );
                
                // 2. Logic Poin Pelanggaran: HANYA JIKA HARIAN
                if ($request->status === 'Alfa' && $request->attendance_type === 'Harian') {
                    
                    // Logic Auto-Find or Auto-Create Discipline Type KHUSUS HARIAN
                    $disciplineType = DisciplineType::firstOrCreate(
                        ['name' => 'Alpa Harian'], 
                        [ 
                            'type' => 'Pelanggaran',
                            'point_value' => 10,
                            'description' => 'Dibuat otomatis oleh sistem saat Absen Manual Alfa'
                        ]
                    );

                    // Buat Record Pelanggaran
                    DisciplineRecord::create([
                        'student_id' => $request->student_id,
                        'discipline_type_id' => $disciplineType->id,
                        'recorded_by_user_id' => Auth::id() ?? 1, 
                        'notes' => "Alpa Manual - Absensi Harian",
                        'date' => $date,
                    ]);
                }
                // Jika Keagamaan, TIDAK ADA DisciplineRecord yang dibuat.
            });

            $savedDate = $date;
            if ($request->attendance_type === 'Keagamaan') {
                return redirect()->route('reports.religious', ['date' => $savedDate, 'activity' => $request->activity])
                    ->with('success', "Data berhasil disimpan.");
            } else {
                return redirect()->route('reports.daily', ['date' => $savedDate])->with('success', "Data berhasil disimpan.");
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id) {
        $attendance = AttendanceSiswa::findOrFail($id);
        $attendance->update($request->all());
        return back()->with('success', 'Data berhasil diupdate.');
    }

    public function destroy($id) {
        AttendanceSiswa::findOrFail($id)->delete();
        return back()->with('success', 'Data berhasil dihapus.');
    }

    /**
     * =========================================================================
     * BAGIAN 4: FITUR BULK ALPHA (NO VIOLATION FOR RELIGIOUS)
     * =========================================================================
     */
    public function bulkAlpha(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'date' => 'required|date',
            'type' => 'required|in:Harian,Keagamaan',
            'activity' => 'nullable|string'
        ]);

        $date = Carbon::parse($request->date)->toDateString();
        $type = $request->type;
        $activity = $request->activity; // Bisa null jika Harian

        // 2. Cari Siswa yang SUDAH absen hari ini (Hadir/Sakit/Izin/dll)
        $query = AttendanceSiswa::whereDate('attendance_date', $date)
            ->where('type', $type);

        if ($type === 'Keagamaan') {
            $query->where('activity', $activity);
        } else {
            // Untuk Harian, anggap tipe 'Harian', 'Masuk', 'Pulang' sebagai satu kesatuan kehadiran
            $query->whereIn('type', ['Harian', 'Masuk', 'Pulang']);
        }

        $presentStudentIds = $query->pluck('student_id')->toArray();

        // 3. Ambil Siswa yang BELUM absen (Target Alpa)
        $studentsToAlpha = Student::whereNotIn('id', $presentStudentIds)->get();

        if ($studentsToAlpha->isEmpty()) {
            return back()->with('success', "Semua siswa sudah diabsen, tidak ada yang diproses.");
        }

        // 4. Proses Insert Massal dalam Database Transaction
        DB::transaction(function () use ($studentsToAlpha, $date, $type, $activity) {
            
            // A. PREPARE DISCIPLINE TYPE (HANYA UNTUK HARIAN)
            $disciplineType = null;

            if ($type === 'Harian') {
                // Cari atau Buat Baru Tipe Disiplin agar tidak error
                $disciplineType = DisciplineType::firstOrCreate(
                    ['name' => 'Alpa Harian'],
                    [
                        'type' => 'Pelanggaran',
                        'point_value' => 10,
                        'description' => 'Dibuat otomatis oleh sistem saat Bulk Alpha Harian'
                    ]
                );
            }
            // Jika Keagamaan, $disciplineType tetap null (sehingga tidak ada pelanggaran dicatat)

            foreach ($studentsToAlpha as $student) {
                // B. Buat Record Absensi 'Alfa' (SELALU DIBUAT UNTUK LAPORAN)
                AttendanceSiswa::create([
                    'student_id' => $student->id,
                    'attendance_date' => $date,
                    'type' => $type,
                    'activity' => $activity, // Null jika Harian
                    'status' => 'Alfa',
                    'notes' => 'Tanpa Keterangan (Otomatis)',
                    'time_in' => null
                ]);

                // C. CATAT PELANGGARAN DI DISCIPLINE RECORD (HANYA JIKA ADA DISCIPLINE TYPE / HARIAN)
                if ($disciplineType) {
                    DisciplineRecord::create([
                        'student_id' => $student->id,
                        'discipline_type_id' => $disciplineType->id,
                        'recorded_by_user_id' => Auth::id() ?? 1,   // User yang sedang login
                        'notes' => "Alpa Otomatis - Absensi Harian",
                        'date' => $date,
                    ]);
                }
            }
        });

        $message = "Berhasil memproses " . $studentsToAlpha->count() . " siswa menjadi Alfa.";
        if ($type === 'Harian') {
            $message .= " Poin Pelanggaran telah ditambahkan.";
        } else {
            $message .= " (Hanya status absensi, tanpa poin pelanggaran).";
        }

        return back()->with('success', $message);
    }
}