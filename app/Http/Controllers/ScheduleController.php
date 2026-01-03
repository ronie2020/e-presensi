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
     * Mengirimkan data: Jadwal Mapel, Daftar Kelas, Daftar Mapel, Daftar Guru, & Jam Bel.
     */
    public function index(Request $request)
    {
        // --- A. AMBIL DATA MASTER (UNTUK DROPDOWN) ---
        
        // 1. Ambil Data KELAS (Ini yang Anda tanyakan)
        // Diurutkan berdasarkan nama agar rapi di dropdown
        $classes = SchoolClass::orderBy('name', 'asc')->get(); 

        // 2. Ambil Data MATA PELAJARAN
        $subjects = Subject::orderBy('name', 'asc')->get();
        
        // 3. Ambil Data GURU
        // Filter user yang punya role 'Guru', 'Wali Kelas', atau 'Kepala Sekolah'
        $teachers = User::whereIn('role', ['Guru', 'Wali Kelas', 'Kepala Sekolah'])
                    ->orderBy('name', 'asc')
                    ->get();
        
        // Fallback: Jika belum ada user dengan role guru, ambil semua user (opsional)
        if($teachers->isEmpty()) {
            $teachers = User::all();
        }

        // --- B. AMBIL DATA JADWAL PELAJARAN (UNTUK TABEL) ---
        $query = Schedule::with(['schoolClass', 'subject', 'teacher'])
                 ->orderByRaw("FIELD(day, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
                 ->orderBy('start_time');

        // Fitur Filter per Kelas (jika user memilih filter di halaman)
        if ($request->has('class_id') && $request->class_id != '') {
            $query->where('school_class_id', $request->class_id);
        }
        $schedules = $query->get();


        // --- C. AMBIL DATA JAM BEL (FITUR LAMA) ---
        $regularSchedules = ScheduleRegular::all()->keyBy('day_type');
        $specialSchedules = ScheduleSpecial::orderBy('date', 'desc')->get();


        // --- D. KIRIM SEMUA VARIABEL KE VIEW ---
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
     * Menyimpan Jadwal Pelajaran Baru.
     */
    public function store(Request $request)
    {
        // Validasi Input
        $request->validate([
            'school_class_id' => 'required|exists:classes,id',
            'subject_id'      => 'required|exists:subjects,id',
            'teacher_id'      => 'required|exists:users,id',
            'day'             => 'required',
            'start_time'      => 'required',
            'end_time'        => 'required|after:start_time',
        ]);

        // Cek Tabrakan Jadwal (Guru tidak boleh mengajar di 2 tempat sekaligus)
        $clash = Schedule::where('teacher_id', $request->teacher_id)
                ->where('day', $request->day)
                ->where(function($q) use ($request) {
                    $q->whereBetween('start_time', [$request->start_time, $request->end_time])
                      ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                      ->orWhere(function($sub) use ($request) {
                          $sub->where('start_time', '<=', $request->start_time)
                              ->where('end_time', '>=', $request->end_time);
                      });
                })
                ->exists();

        if ($clash) {
            return back()->with('error', 'Gagal! Guru tersebut sudah memiliki jadwal mengajar di jam yang sama.');
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
        $request->validate([
            'day_type.*' => 'required|string|in:Biasa,Jumat',
            'start_in.*' => 'required', 
            'end_in.*'   => 'required|after:start_in.*',
            'start_out.*'=> 'required',
            'end_out.*'  => 'required|after:start_out.*',
        ]);

        foreach ($request->day_type as $index => $dayType) {
            ScheduleRegular::updateOrCreate(
                ['day_type' => $dayType], 
                [ 
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