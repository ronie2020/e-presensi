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
            // PERBAIKAN: Gunakan integer dan gt (greater than) untuk angka jam pelajaran
            'start_time'      => 'required|integer|min:1|max:15',
            'end_time'        => 'required|integer|gt:start_time|max:15', 
        ], [
            'end_time.gt' => 'Jam selesai harus lebih besar dari jam mulai.', // Pesan error custom
        ]);

        // Cek Tabrakan Jadwal (Bentrok Guru)
        // Logika disesuaikan untuk angka (Range overlapping)
        $clash = Schedule::where('teacher_id', $request->teacher_id)
                ->where('day', $request->day)
                ->where(function($q) use ($request) {
                    // Cek jika range waktu yang baru beririsan dengan yang sudah ada
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
        // Validasi Jam Bel tetap menggunakan format Waktu (H:i) karena ini jam dinding asli
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