<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;        
use App\Models\ScheduleRegular; 
use App\Models\ScheduleSpecial; 
use App\Models\SchoolClass;     
use App\Models\Subject;         
use App\Models\User;            
use Carbon\Carbon; 

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        //  AMBIL DATA MASTER ---
        $classes = SchoolClass::orderBy('name', 'asc')->get(); 
        $subjects = Subject::orderBy('name', 'asc')->get();
        
        $teachers = User::whereIn('role', ['Guru', 'Wali Kelas', 'Kepala Sekolah'])
                    ->orderBy('name', 'asc')
                    ->get();
        
        if($teachers->isEmpty()) {
            $teachers = User::all();
        }

        // FILTER & QUERY ---
        $query = Schedule::with(['schoolClass', 'subject', 'teacher'])
                 ->orderByRaw("FIELD(day, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
                 ->orderBy('start_time'); 

        if ($request->has('class_id') && $request->class_id != '') {
            $query->where('school_class_id', $request->class_id);
        }
        $schedules = $query->get();

        // DATA JAM BEL ---    
        $regularSchedules = ScheduleRegular::all()->keyBy('day_name');
        
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

    public function store(Request $request)
    {
        // 1. Validasi 
        $request->validate([
            'school_class_id' => 'required|exists:classes,id', 
            'subject_id'      => 'required|exists:subjects,id',
            'teacher_id'      => 'required|exists:users,id',
            'day'             => 'required',
            'start_time'      => 'required|integer|min:1|max:15',
            'end_time'        => 'required|integer|gte:start_time|max:15', 
        ], [
            'end_time.gte' => 'Jam selesai tidak boleh lebih kecil dari jam mulai.',
        ]);

        // 2. Cek Bentrok GURU 
        $teacherClash = Schedule::where('teacher_id', $request->teacher_id)
                ->where('day', $request->day)
                ->where(function($q) use ($request) {
                    $q->where('start_time', '<=', $request->end_time)
                      ->where('end_time', '>=', $request->start_time);
                })
                ->exists();

        if ($teacherClash) {
            return back()->withInput()->withErrors(['teacher_id' => 'Gagal! Guru ini sudah memiliki jadwal mengajar di jam pelajaran tersebut.']);
        }

        // 3. Cek Bentrok KELAS         
        $classClash = Schedule::where('school_class_id', $request->school_class_id)
                ->where('day', $request->day)
                ->where(function($q) use ($request) {
                    $q->where('start_time', '<=', $request->end_time)
                      ->where('end_time', '>=', $request->start_time);
                })
                ->exists();

        if ($classClash) {
            return back()->withInput()->withErrors(['school_class_id' => 'Gagal! Kelas ini sudah ada mata pelajaran lain di jam pelajaran tersebut.']);
        }

        // 4. Simpan Data
        Schedule::create($request->all());

        return back()->with('success', 'Jadwal pelajaran berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        Schedule::findOrFail($id)->delete();
        return back()->with('success', 'Jadwal berhasil dihapus.');
    }

    // ==========================================
    // JAM REGULER & KHUSUS
    // ==========================================

    public function storeRegular(Request $request)
    {
        // Validasi input form 
        $request->validate([
            'day_type.*' => 'required|string|in:Biasa,Jumat',
            'start_in.*' => 'required', 
            'end_in.*'   => 'required|after:start_in.*', 
            'start_out.*'=> 'required',
            'end_out.*'  => 'required|after:start_out.*', 
        ]);

        if ($request->has('day_type')) {
            foreach ($request->day_type as $index => $dayType) {
                if (isset($request->start_in[$index])) {   
                    ScheduleRegular::updateOrCreate(
                        ['day_name' => $dayType], 
                        [                             
                            'start_in'  => $request->start_in[$index],
                            'end_in'    => $request->end_in[$index],
                            'start_out' => $request->start_out[$index],
                            'end_out'   => $request->end_out[$index],
                        ]
                    );
                }
            }
        }

        return back()->with('success', 'Jam Sekolah Reguler berhasil diperbarui.');
    }

    public function storeSpecial(Request $request)
    {
        $request->merge(['is_holiday' => $request->has('is_holiday') ? 1 : 0]);

        $validatedData = $request->validate([
            'date'        => 'required|date|unique:schedules_special,date',
            'description' => 'nullable|string|max:255',
            'is_holiday'  => 'boolean',
            'start_in'    => 'nullable|required_if:is_holiday,0',
            'end_in'      => 'nullable|required_if:is_holiday,0|after_or_equal:start_in',
            'start_out'   => 'nullable|required_if:is_holiday,0',
            'end_out'     => 'nullable|required_if:is_holiday,0|after_or_equal:start_out',
        ]);

        ScheduleSpecial::create($validatedData);

        return back()->with('success', 'Jadwal Khusus berhasil ditambahkan.');
    }

    public function destroySpecial($id)
    {
        $schedule = ScheduleSpecial::find($id);
        if($schedule) {
            $schedule->delete();
            return back()->with('success', 'Jadwal Khusus berhasil dihapus.');
        }
        return back()->with('error', 'Data tidak ditemukan.');
    }
}