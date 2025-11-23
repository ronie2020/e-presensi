<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\AttendanceSiswa;
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

        // 1. Ambil Data Mentah (Urutan waktu scan tetap diambil untuk history, tapi nanti disort ulang)
        $rawAttendances = AttendanceSiswa::with('student.schoolClass')
            ->whereDate('attendance_date', $selectedDate)
            ->whereIn('type', ['Harian', 'Masuk', 'Pulang'])
            ->get(); // Hapus orderBy created_at di sini agar kita sort manual nanti

        // 2. Grouping Data & Menentukan Status Final
        $processedAttendances = $rawAttendances->groupBy('student_id')->map(function ($logs) {
            // Ambil log pertama (biasanya masuk) atau log terakhir tergantung kebutuhan
            // Kita ambil log dengan created_at paling awal sebagai referensi data siswa
            $firstLog = $logs->sortBy('created_at')->first();
            
            $timeIn = $logs->whereNotNull('time_in')->pluck('time_in')->sort()->first(); 
            
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
            
            $finalStatus = $firstLog->status; 
            if (in_array('Hadir', $statuses)) $finalStatus = 'Hadir';
            elseif (in_array('Sakit', $statuses)) $finalStatus = 'Sakit';
            elseif (in_array('Izin', $statuses)) $finalStatus = 'Izin';
            elseif (in_array('Alfa', $statuses)) $finalStatus = 'Alfa';

            $firstLog->time_in_final = $timeIn;
            $firstLog->time_out_final = $timeOut;
            $firstLog->status_final = $finalStatus;
            $firstLog->notes_final = $allNotes;
            
            return $firstLog;
        });

        // --- [BARU] SORTING LOGIC: KELAS (ASC) -> NAMA (ASC) ---
        $processedAttendances = $processedAttendances->sort(function ($a, $b) {
            // Ambil Nama Kelas, jika kosong kasih 'ZZZ' biar di bawah
            $classA = $a->student->schoolClass->name ?? 'ZZZ';
            $classB = $b->student->schoolClass->name ?? 'ZZZ';
            
            // Bandingkan Kelas (Natural Sort agar 9A tidak dianggap lebih besar dari 10A secara string biasa)
            $classComparison = strnatcmp($classA, $classB);
            
            if ($classComparison === 0) {
                // Jika kelas sama, urutkan berdasarkan Nama Siswa
                return strcasecmp($a->student->name, $b->student->name);
            }
            
            return $classComparison;
        });
        // -------------------------------------------------------

        // 3. Hitung Statistik
        $hadirCount = $processedAttendances->where('status_final', 'Hadir')->count();
        $sakitCount = $processedAttendances->where('status_final', 'Sakit')->count();
        $izinCount = $processedAttendances->where('status_final', 'Izin')->count();
        $alfaCount = $processedAttendances->where('status_final', 'Alfa')->count();

        // 4. Ambil Siswa (Query ini sudah sorted by Class & Name dari database)
        $allStudents = Student::with('schoolClass')
            ->select('students.*')
            ->join('classes', 'students.class_id', '=', 'classes.id')
            ->orderBy('classes.name', 'asc')
            ->orderBy('students.name', 'asc')
            ->get();

        $attendedIds = $processedAttendances->pluck('student_id');
        $belumAbsenList = $allStudents->whereNotIn('id', $attendedIds); 
        // Note: $belumAbsenList otomatis sudah terurut karena $allStudents sudah terurut

        // 5. PAGINATION MANUAL
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
            ->get(); // Hapus default sorting DB, kita sort manual
            
        $processedAttendances = $rawAttendances->groupBy('student_id')->map(function ($logs) {
            $firstLog = $logs->sortBy('created_at')->first(); // Ambil log pertama
            $allNotes = $logs->pluck('notes')->filter()->unique()->implode(' | ');
            $statuses = $logs->pluck('status')->toArray();
            
            $finalStatus = $firstLog->status;
            // Prioritas status
            if (in_array('Hadir', $statuses)) {
                $finalStatus = 'Hadir';
            } elseif (in_array("Uzur Syar'i", $statuses)) {
                $finalStatus = "Uzur Syar'i";
            }
            
            $firstLog->status_final = $finalStatus;
            $firstLog->notes_final = $allNotes;
            return $firstLog;
        });

        // --- [BARU] SORTING LOGIC: KELAS (ASC) -> NAMA (ASC) ---
        $processedAttendances = $processedAttendances->sort(function ($a, $b) {
            $classA = $a->student->schoolClass->name ?? 'ZZZ';
            $classB = $b->student->schoolClass->name ?? 'ZZZ';
            
            $classComparison = strnatcmp($classA, $classB);
            
            if ($classComparison === 0) {
                return strcasecmp($a->student->name, $b->student->name);
            }
            
            return $classComparison;
        });
        // -------------------------------------------------------

        $hadirCount = $processedAttendances->where('status_final', 'Hadir')->count();
        $izinUzurCount = $processedAttendances->where('status_final', "Uzur Syar'i")->count();
        
        // Ambil Siswa (Query sudah sorted)
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

        // PAGINATION MANUAL
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
            'belumAbsenCount' => $belumAbsenList->count(),
            'kehadiranPercentage' => $kehadiranPercentage
        ]);
    }

    // ... (Sisa method tidak perlu diubah) ...
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
        
        // SORTING UNTUK EXPORT JUGA (Biar rapi di Excel)
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
            ->get(); // Ambil semua data

        // Sorting Manual untuk Export
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
            $matchConditions = [
                'student_id' => $request->student_id,
                'attendance_date' => $date,
                'type' => $request->attendance_type,
            ];
            if ($request->attendance_type === 'Keagamaan') {
                $matchConditions['activity'] = $request->activity;
            }

            $attendance = AttendanceSiswa::updateOrCreate(
                $matchConditions,
                [
                    'status' => $request->status,
                    'notes' => $request->notes ?? 'Manual Entry',
                    'time_in' => DB::raw('COALESCE(time_in, "'.$timeNow.'")') 
                ]
            );
            $attendance->status = $request->status;
            $attendance->save();

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

    public function processAlpha(Request $request)
    {
        $request->validate(['date' => 'required|date']);
        $date = Carbon::parse($request->date)->toDateString();
        $allStudents = Student::all();
        $presentStudentIds = AttendanceSiswa::whereDate('attendance_date', $date)
            ->whereIn('type', ['Harian', 'Masuk', 'Pulang'])
            ->pluck('student_id')
            ->toArray();
        $count = 0;
        foreach ($allStudents as $student) {
            if (!in_array($student->id, $presentStudentIds)) {
                AttendanceSiswa::create([
                    'student_id' => $student->id,
                    'attendance_date' => $date,
                    'type' => 'Harian', 
                    'status' => 'Alfa',
                    'notes' => 'Tanpa Keterangan (Otomatis)',
                    'time_in' => null
                ]);
                $count++;
            }
        }
        return back()->with('success', "Berhasil memproses $count siswa menjadi Alfa.");
    }
}