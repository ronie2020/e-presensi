<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\TeachingLoad;
use App\Models\Timeslot;
use App\Models\Timetable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ClassTimetableExport;
use App\Exports\TeacherTimetableExport;

class TimetableController extends Controller
{
    /**
     * Menampilkan halaman utama (Wizard) Timetable
     */
     public function index()
    {
        $classes = SchoolClass::with(['teachingLoads.teacher', 'teachingLoads.subject'])->get();
        $timeslots = Timeslot::orderBy('order_sequence')->get();
        $teachers = User::role(['Guru', 'Guru Mata Pelajaran', 'Wali Kelas'])->orderBy('name')->get();
        $totalTeachingLoads = TeachingLoad::sum('hours_per_week');
        $hasGenerated = Timetable::exists();

        $timetables = [];
        $teacherTimetables = [];
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $unassignedLoadsList = [];

        // HILANGKAN if ($hasGenerated) { ... }
        // Biarkan data ditarik setiap saat agar bisa manual dari nol

        $allSchedules = Timetable::with(['timeslot', 'teacher', 'subject', 'studentClass'])->get();
        foreach ($allSchedules as $schedule) {
            $timetables[$schedule->class_id][$schedule->day_of_week][$schedule->timeslot_id] = $schedule;
            $teacherTimetables[$schedule->teacher_id][$schedule->day_of_week][$schedule->timeslot_id] = $schedule;
        }

        $allLoads = TeachingLoad::with(['teacher', 'subject', 'studentClass'])->get();
        foreach ($allLoads as $load) {
            $assignedCount = Timetable::where('class_id', $load->class_id)
                                      ->where('teacher_id', $load->teacher_id)
                                      ->where('subject_id', $load->subject_id)
                                      ->count();
            $sisa = $load->hours_per_week - $assignedCount;
            if ($sisa > 0) {
                $unassignedLoadsList[] = [
                    'load' => $load,
                    'sisa' => $sisa
                ];
            }
        }

        return view('timetable.index', compact('classes', 'timeslots', 'teachers', 'totalTeachingLoads', 'hasGenerated', 'timetables', 'teacherTimetables', 'days', 'unassignedLoadsList'));
    }

    /**
     * FITUR DRAG & DROP: Menghapus jadwal dari tabel (Kembalikan ke Bank)
     */
    public function removeSchedule(Request $request)
    {
        $request->validate([
            'timetable_id' => 'required|exists:timetables,id',
        ]);

        // Hapus dari tabel jadwal
        Timetable::destroy($request->timetable_id);

        return response()->json(['success' => true, 'message' => 'Jadwal berhasil dikembalikan ke Bank Sisa.']);
    }

    /**
     * Algoritma Auto-Generate Jadwal Pelajaran (Versi 2.1 - Distribusi Merata)
     */
    public function generate(Request $request)
    {
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $timeslots = Timeslot::orderBy('order_sequence')->get();
        
        // Ambil beban mengajar
        $teachingLoads = TeachingLoad::orderBy('hours_per_week', 'desc')->get();

        if ($timeslots->isEmpty() || $teachingLoads->isEmpty()) {
            return redirect()->back()->with('error', 'Gagal generate. Pastikan Slot Waktu dan Beban Mengajar sudah diisi.');
        }

        Timetable::truncate();
        DB::beginTransaction();
        
        try {
            $unassignedLoads = []; 
            $allBlocks = [];
            
            // 1. MEMBUAT BLOK JADWAL (CHUNKING)
            foreach ($teachingLoads as $load) {
                $hpw = $load->hours_per_week;
                $chunks = [];
                
                if ($hpw <= 3) {
                    $chunks[] = $hpw;
                } elseif ($hpw == 4) {
                    $chunks = [2, 2];
                } elseif ($hpw == 5) {
                    $chunks = [3, 2];
                } else {
                    while ($hpw > 0) {
                        $chunk = min(3, $hpw);
                        $chunks[] = $chunk;
                        $hpw -= $chunk;
                    }
                }
                
                foreach ($chunks as $chunk) {
                    $allBlocks[] = [
                        'load' => $load,
                        'size' => $chunk,
                    ];
                }
            }

            // 2. MENGURUTKAN BLOK (PRIORITAS BLOK BESAR DULUAN - BIN PACKING)
            // Blok 3 JP dan 2 JP diplot lebih dulu. 1 JP akan mengisi lubang yang tersisa.
            usort($allBlocks, function($a, $b) {
                if ($a['size'] != $b['size']) {
                    return $b['size'] <=> $a['size']; // Urutkan dari size terbesar
                }
                return $b['load']->hours_per_week <=> $a['load']->hours_per_week;
            });

            // 3. IN-MEMORY CACHE UNTUK PENGECEKAN BENTROK (SUPER CEPAT)
            $scheduleMap = [
                'class' => [],
                'teacher' => []
            ];
            
            $timetablesToInsert = [];
            $usedDaysByLoad = [];

            // 4. PROSES PENEMPATAN
            for ($i = 0; $i < count($allBlocks); $i++) {
                $block = $allBlocks[$i];
                $load = $block['load'];
                $size = $block['size'];
                $loadId = $load->id;
                
                if (!isset($usedDaysByLoad[$loadId])) $usedDaysByLoad[$loadId] = [];
                
                $shuffledDays = $days;
                shuffle($shuffledDays); // Acak hari biar merata
                
                $placed = false;

                // STRATEGI 1: Cari jam berurutan di hari yang BELUM dipakai oleh beban mengajar ini
                foreach ($shuffledDays as $day) {
                    if (in_array($day, $usedDaysByLoad[$loadId])) continue;
                    
                    $slotsToBook = $this->findConsecutiveSlots($day, $timeslots, $size, $load, $scheduleMap);
                    
                    if ($slotsToBook) {
                        foreach ($slotsToBook as $slotId) {
                            $scheduleMap['class'][$day][$slotId][$load->class_id] = true;
                            $scheduleMap['teacher'][$day][$slotId][$load->teacher_id] = true;
                            $timetablesToInsert[] = [
                                'day_of_week' => $day, 'timeslot_id' => $slotId,
                                'class_id' => $load->class_id, 'teacher_id' => $load->teacher_id,
                                'subject_id' => $load->subject_id, 'status' => 'published',
                                'created_at' => now(), 'updated_at' => now(),
                            ];
                        }
                        $usedDaysByLoad[$loadId][] = $day;
                        $placed = true;
                        break;
                    }
                }

                // STRATEGI 2: Fallback Hari - Cari jam berurutan di hari BEBAS (Boleh hari yang sama jika mentok)
                if (!$placed) {
                    foreach ($shuffledDays as $day) {
                        $slotsToBook = $this->findConsecutiveSlots($day, $timeslots, $size, $load, $scheduleMap);
                        if ($slotsToBook) {
                            foreach ($slotsToBook as $slotId) {
                                $scheduleMap['class'][$day][$slotId][$load->class_id] = true;
                                $scheduleMap['teacher'][$day][$slotId][$load->teacher_id] = true;
                                $timetablesToInsert[] = [
                                    'day_of_week' => $day, 'timeslot_id' => $slotId,
                                    'class_id' => $load->class_id, 'teacher_id' => $load->teacher_id,
                                    'subject_id' => $load->subject_id, 'status' => 'published',
                                    'created_at' => now(), 'updated_at' => now(),
                                ];
                            }
                            $usedDaysByLoad[$loadId][] = $day;
                            $placed = true;
                            break;
                        }
                    }
                }

                // STRATEGI 3: DYNAMIC SPLITTING (PEMECAHAN BLOK OTOMATIS)               
                if (!$placed) {
                    // PERBAIKAN: Jika ukuran > 2, baru boleh dipecah. 
                    // Jika ukuran 2, JANGAN DIPECAH (masukkan ke unassigned saja).
                    if ($size > 2) {
                        $allBlocks[] = ['load' => $load, 'size' => $size - 1]; // Kembalikan potongan besar
                        $allBlocks[] = ['load' => $load, 'size' => 1];         // Kembalikan potongan kecil (1 JP)
                    } else {      
                        $teacherName = User::find($load->teacher_id)->name ?? 'ID '.$load->teacher_id;
                        $className = SchoolClass::find($load->class_id)->name ?? 'ID '.$load->class_id;
                        $unassignedLoads[] = "Gagal menaruh 2 JP berurutan: Guru {$teacherName} di Kelas {$className}";
                    }
                }
            }

            // Insert massal agar proses generate super cepat (Batch Insert)
            $chunks = array_chunk($timetablesToInsert, 100);
            foreach ($chunks as $chunk) {
                Timetable::insert($chunk);
            }

            DB::commit();

            if (count($unassignedLoads) > 0) {
                return redirect()->route('timetable.index')->with('warning', 'Jadwal terbuat, tapi ada ' . count($unassignedLoads) . ' jam pelajaran yang tidak kebagian tempat akibat jadwal mentok 100%. Detail: ' . implode(', ', array_slice($unassignedLoads, 0, 3)) . (count($unassignedLoads) > 3 ? '...' : ''));
            }

            return redirect()->route('timetable.index')->with('success', 'Jadwal pelajaran berhasil di-generate! Pemisahan mapel telah dicegah dan sisa slot dioptimalkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Fungsi Bantuan untuk mencari deretan Jam Pelajaran Kosong (Strict Consecutive)
     */
    private function findConsecutiveSlots($day, $timeslots, $size, $load, $scheduleMap)
    {
        $slotsToBook = [];
        $consecutiveCount = 0;
        
        foreach ($timeslots as $slot) {
            $slotDays = array_map('trim', explode(',', $slot->day_of_week));
            $isValidDay = in_array($day, $slotDays) || $slot->day_of_week === 'Semua Hari' || ($slot->day_of_week === 'Selain Senin' && $day !== 'Senin') || ($slot->day_of_week === 'Selain Jumat' && $day !== 'Jumat');
            
            if (!$isValidDay) continue;

            if ($slot->is_break) {
                continue; // Jam istirahat dilewati tanpa memutus urutan
            }

            // Cek Bentrok di Memori 
            $classBusy = isset($scheduleMap['class'][$day][$slot->id][$load->class_id]);
            $teacherBusy = isset($scheduleMap['teacher'][$day][$slot->id][$load->teacher_id]);

            if (!$classBusy && !$teacherBusy) {
                $slotsToBook[] = $slot->id;
                $consecutiveCount++;

                if ($consecutiveCount == $size) {
                    return $slotsToBook; // Ketemu deretan yang pas, kembalikan slotnya!
                }
            } else {
                // Terpotong oleh mapel lain! Reset hitungan dari 0.
                $slotsToBook = [];
                $consecutiveCount = 0;
            }
        }
        
        return false;
    }

    /**
     * Hapus semua jadwal (Reset)
     */
    public function reset()
    {
        Timetable::truncate();
        return redirect()->back()->with('success', 'Semua jadwal berhasil dikosongkan.');
    }

    /**
     * Ekspor Jadwal per Kelas 
     */
     public function exportClass($class_id)
    {
        $class = SchoolClass::findOrFail($class_id);
        return Excel::download(new ClassTimetableExport($class_id), 'Jadwal-Kelas-'.$class->name.'.xlsx');
    }

    /**
     * Ekspor Jadwal per Guru
     */
     public function exportTeacher($teacher_id)
    {
        $teacher = User::findOrFail($teacher_id);
        return Excel::download(new TeacherTimetableExport($teacher_id), 'Jadwal-Guru-'.$teacher->name.'.xlsx');
    }

    /**
     * FITUR DRAG & DROP: Memindahkan jadwal yang sudah ada
     */
    public function moveSchedule(Request $request)
    {
        $request->validate([
            'timetable_id' => 'required|exists:timetables,id',
            'target_day' => 'required|string',
            'target_timeslot_id' => 'required|exists:timeslots,id',
        ]);

        $timetable = Timetable::findOrFail($request->timetable_id);
        
        // 1. Cek Bentrok Kelas (MENGGUNAKAN $timetable BUKAN $load)
        $classBusy = Timetable::where('day_of_week', $request->target_day)
        ->where('timeslot_id', $request->target_timeslot_id)
        ->where('class_id', $timetable->class_id) // PERBAIKAN DI SINI
        ->when(isset($request->timetable_id), function($query) use ($request) {
            return $query->where('id', '!=', $request->timetable_id);
        })
        ->with(['subject', 'studentClass'])
        ->first();

        if ($classBusy) {
            return response()->json([
                'success' => false, 
                'message' => "Slot ini sudah diisi mapel " . ($classBusy->subject->name ?? 'lain') . " di kelas ini."
            ]);
        }

        // 2. Cek Bentrok Guru (MENGGUNAKAN $timetable BUKAN $load)
        $teacherBusy = Timetable::where('day_of_week', $request->target_day)
            ->where('timeslot_id', $request->target_timeslot_id)
            ->where('teacher_id', $timetable->teacher_id) // PERBAIKAN DI SINI
            ->when(isset($request->timetable_id), function($query) use ($request) {
                return $query->where('id', '!=', $request->timetable_id);
            })
            ->with(['subject', 'studentClass'])
            ->first();
                
        if ($teacherBusy) {
            $className = $teacherBusy->studentClass ? $teacherBusy->studentClass->name : 'Kelas Tidak Diketahui';
            $subjectName = $teacherBusy->subject ? $teacherBusy->subject->name : 'Mapel Lain';
            
            return response()->json([
                'success' => false, 
                'message' => "Guru bentrok! Saat ini sedang mengajar mapel **{$subjectName}** di **{$className}** pada jam tersebut."
            ]);
        }

        // Simpan perpindahan
        $timetable->update([
            'day_of_week' => $request->target_day,
            'timeslot_id' => $request->target_timeslot_id
        ]);

        return response()->json(['success' => true, 'message' => 'Jadwal berhasil dipindah.']);
    }

    /**
     * FITUR DRAG & DROP: Menempatkan JP yang tersisa ke jadwal kosong
     */
    public function placeUnassigned(Request $request)
    {
        $request->validate([
            'teaching_load_id' => 'required|exists:teaching_loads,id',
            'target_day' => 'required|string',
            'target_timeslot_id' => 'required|exists:timeslots,id',
        ]);

        $load = TeachingLoad::findOrFail($request->teaching_load_id);

        // 1. Cek Bentrok Kelas (Slot tersebut sudah diisi mapel lain untuk kelas ini?)
        $classBusy = Timetable::where('day_of_week', $request->target_day)
            ->where('timeslot_id', $request->target_timeslot_id)
            ->where('class_id', $load->class_id) // Di sini menggunakan $load, benar.
            ->when(isset($request->timetable_id), function($query) use ($request) {
                return $query->where('id', '!=', $request->timetable_id);
            })
            ->with(['subject', 'studentClass'])
            ->first();

        if ($classBusy) {
            return response()->json([
                'success' => false, 
                'message' => "Slot ini sudah diisi mapel " . ($classBusy->subject->name ?? 'lain') . " di kelas ini."
            ]);
        }

        // 2. Cek Bentrok Guru (Guru ini sedang mengajar di kelas lain?)
        $teacherBusy = Timetable::where('day_of_week', $request->target_day)
            ->where('timeslot_id', $request->target_timeslot_id)
            ->where('teacher_id', $load->teacher_id) // Di sini menggunakan $load, benar.
            ->when(isset($request->timetable_id), function($query) use ($request) {
                return $query->where('id', '!=', $request->timetable_id);
            })
            ->with(['subject', 'studentClass']) // WAJIB ADA agar relasi terbaca
            ->first();
                    
        if ($teacherBusy) {
            // Pastikan 'name' adalah field yang benar di tabel 'classes' (Sesuai SchoolClass.php)
            $className = $teacherBusy->studentClass ? $teacherBusy->studentClass->name : 'Kelas Tidak Diketahui';
            $subjectName = $teacherBusy->subject ? $teacherBusy->subject->name : 'Mapel Lain';
            
            return response()->json([
                'success' => false, 
                'message' => "Guru bentrok! Saat ini sedang mengajar mapel **{$subjectName}** di **{$className}** pada jam tersebut."
            ]);
        }
        
        Timetable::create([
            'day_of_week' => $request->target_day,
            'timeslot_id' => $request->target_timeslot_id,
            'class_id' => $load->class_id,
            'teacher_id' => $load->teacher_id,
            'subject_id' => $load->subject_id,
            'status' => 'published',
        ]);

        return response()->json(['success' => true, 'message' => 'Sisa jam pelajaran berhasil ditempatkan!']);
    }
}