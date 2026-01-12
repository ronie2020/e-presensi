<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// --- 1. IMPORT MODEL YANG DIBUTUHKAN ---
use App\Models\Schedule;        
use App\Models\ScheduleRegular; 
use App\Models\ScheduleSpecial; 
use App\Models\SchoolClass;     
use App\Models\Subject;         
use App\Models\User;            
use Carbon\Carbon;

class ScheduleController extends Controller
{
    /**
     * Menampilkan halaman Manajemen Jadwal.
     */
    public function index(Request $request)
    {
        // --- A. AMBIL DATA MASTER (UNTUK DROPDOWN) ---
        $classes = SchoolClass::orderBy('name', 'asc')->get(); 
        $subjects = Subject::orderBy('name', 'asc')->get();
        
        $teachers = User::whereIn('role', ['Guru', 'Wali Kelas', 'Kepala Sekolah'])
                    ->orderBy('name', 'asc')
                    ->get();
        
        if($teachers->isEmpty()) {
            $teachers = User::all();
        }

        // --- B. AMBIL DATA JADWAL PELAJARAN ---
        $query = Schedule::with(['schoolClass', 'subject', 'teacher'])
                 ->orderByRaw("FIELD(day, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
                 ->orderBy('start_time'); // Urutkan berdasarkan jam ke-

        if ($request->has('class_id') && $request->class_id != '') {
            $query->where('school_class_id', $request->class_id);
        }
        $schedules = $query->get();

        // --- C. AMBIL DATA JAM BEL ---
        // Pastikan tabel schedules_regular sudah memiliki kolom 'day_type'
        $regularSchedules = ScheduleRegular::all()->keyBy('day_type');
        $specialSchedules = ScheduleSpecial::orderBy('date', 'desc')->get();

        return view('admin.schedules.index', [
            'classes'   => $classes,   
            'subjects'  => $subjects,  
            'teachers'  => $teachers,  
            'schedules' => $schedules,
            'regularSchedules' => $regularSchedules,
            'specialSchedules' => $specialSchedules,
        ]);
    }

    /**
     * Menyimpan Jadwal Pelajaran Baru (DENGAN PERBAIKAN VALIDASI).
     */
    public function store(Request $request)
    {
        // Validasi Input
        $request->validate([
            'school_class_id' => 'required|exists:classes,id',
            'subject_id'      => 'required|exists:subjects,id',
            'teacher_id'      => 'required|exists:users,id',
            'day'             => 'required',
            // PERBAIKAN: Gunakan 'gte' agar mapel 1 JP (misal jam 1 s.d jam 1) bisa masuk
            'start_time'      => 'required|integer|min:1|max:15',
            'end_time'        => 'required|integer|gte:start_time|max:15', 
        ], [
            'end_time.gte' => 'Jam selesai tidak boleh lebih kecil dari jam mulai.',
        ]);

        // Cek Tabrakan Jadwal (Bentrok Guru)
        // Logika overlap: (StartA <= EndB) and (EndA >= StartB)
        $clash = Schedule::where('teacher_id', $request->teacher_id)
                ->where('day', $request->day)
                ->where(function($q) use ($request) {
                    $q->where(function($sub) use ($request) {
                        $sub->where('start_time', '<=', $request->end_time)
                            ->where('end_time', '>=', $request->start_time);
                    });
                })
                ->exists();

        if ($clash) {
            return back()->with('error', 'Gagal! Guru tersebut sudah memiliki jadwal mengajar di jam pelajaran tersebut.');
        }

        // Simpan Data
        Schedule::create($request->all());

        return back()->with('success', 'Jadwal pelajaran berhasil ditambahkan.');
    }

    /**
     * Menghapus Jadwal Pelajaran.
     */
    public function destroy($id)
    {
        Schedule::findOrFail($id)->delete();
        return back()->with('success', 'Jadwal berhasil dihapus.');
    }

    // ==========================================
    // FUNGSI UNTUK JAM BEL (REGULAR & SPECIAL)
    // ==========================================

    public function storeRegular(Request $request)
    {
        // Validasi Jam Bel
        $request->validate([
            'day_type.*' => 'required|string|in:Biasa,Jumat',
            'start_in.*' => 'required', 
            'end_in.*'   => 'required|after:start_in.*',
            'start_out.*'=> 'required',
            'end_out.*'  => 'required|after:start_out.*',
        ]);

        // Looping berdasarkan array day_type yang dikirim dari form
        foreach ($request->day_type as $index => $dayType) {
            // NOTE: Kode di bawah ini butuh kolom 'day_type' di tabel 'schedules_regular'
            ScheduleRegular::updateOrCreate(
                ['day_type' => $dayType], 
                [ 
                    // FIX: Isi day_name dengan day_type agar tidak error 1364 (Field 'day_name' doesn't have a default value)
                    'day_name'  => $dayType, 
                    'start_in'  => $request->start_in[$index],
                    'end_in'    => $request->end_in[$index],
                    'start_out' => $request->start_out[$index],
                    'end_out'   => $request->end_out[$index],
                ]
            );
        }

        return back()->with('success', 'Jam Sekolah Reguler berhasil diperbarui.');
    }

    public function storeSpecial(Request $request)
    {
        $validatedData = $request->validate([
            'date'        => 'required|date|unique:schedules_special,date',
            'description' => 'nullable|string|max:255',
            'is_holiday'  => 'nullable|boolean',
            'start_in'    => 'nullable|required_if:is_holiday,null,0',
            'end_in'      => 'nullable|required_if:is_holiday,null,0|after_or_equal:start_in',
            'start_out'   => 'nullable|required_if:is_holiday,null,0',
            'end_out'     => 'nullable|required_if:is_holiday,null,0|after_or_equal:start_out',
        ]);

        $validatedData['is_holiday'] = $request->has('is_holiday');

        ScheduleSpecial::create($validatedData);

        return back()->with('success', 'Jadwal Khusus berhasil ditambahkan.');
    }

    public function destroySpecial(ScheduleSpecial $schedule)
    {
        $schedule->delete();
        return back()->with('success', 'Jadwal Khusus berhasil dihapus.');
    }
}