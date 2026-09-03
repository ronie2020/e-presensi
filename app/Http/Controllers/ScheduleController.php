<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScheduleRegular; 
use App\Models\ScheduleSpecial; 
use Carbon\Carbon; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache; 
use Illuminate\Support\Facades\Storage; 

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        // Data Jam Reguler
        $regularSchedules = ScheduleRegular::all()->keyBy('day_name');
        
        // Data Jadwal Khusus
        $specialSchedules = ScheduleSpecial::orderBy('date', 'desc')->get();
        
        // Data Jadwal Bel (Diurutkan berdasarkan Hari lalu Jam)
        $learningSchedules = DB::table('learning_schedules')
            ->orderByRaw("FIELD(day_type, 'Senin', 'Selasa-Kamis', 'Jumat')")
            ->orderBy('trigger_time', 'asc')
            ->get();

        // Mengambil status toggle Master Bel dari Cache (Default: true)
        $bellSettings = (object) ['is_active' => Cache::get('is_bell_active', true)];

        return view('admin.schedules.index', [
            'regularSchedules' => $regularSchedules,
            'specialSchedules' => $specialSchedules,
            'learningSchedules' => $learningSchedules,
            'bellSettings' => $bellSettings,
        ]);
    }

    // ==========================================
    // JAM REGULER & KHUSUS
    // ==========================================

    public function storeRegular(Request $request)
    {
        // Validasi input form - Disesuaikan dengan pilihan hari yang baru
        $request->validate([
            'day_type.*' => 'required|string|in:Senin,Selasa-Kamis,Jumat', 
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

    // ==========================================
    // JAM PEMBELAJARAN (BEL)
    // ==========================================

    public function storeLearning(Request $request)
    {
        $request->validate([
            'day_type' => 'required|string|in:Senin,Selasa-Kamis,Jumat',
            'activity_name' => 'required|string|max:255',
            'trigger_time' => 'required',
            'audio_file' => 'nullable|mimes:mp3,wav|max:5120',
        ]);

        $audioPath = null;
        if ($request->hasFile('audio_file')) {
            $audioPath = $request->file('audio_file')->store('bells', 'public');
        }

        DB::table('learning_schedules')->insert([
            'day_type' => $request->day_type,
            'activity_name' => $request->activity_name,
            'trigger_time' => $request->trigger_time,
            'audio_file' => $audioPath,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Jadwal bel berhasil ditambahkan!');
    }

    public function updateLearning(Request $request, $id)
    {
        $request->validate([
            'day_type' => 'required|string|in:Senin,Selasa-Kamis,Jumat',
            'activity_name' => 'required|string|max:255',
            'trigger_time' => 'required',
            'audio_file' => 'nullable|mimes:mp3,wav|max:5120',
        ]);

        $schedule = DB::table('learning_schedules')->where('id', $id)->first();
        
        $updateData = [
            'day_type' => $request->day_type,
            'activity_name' => $request->activity_name,
            'trigger_time' => $request->trigger_time,
            'updated_at' => now(),
        ];

        // Jika upload audio baru
        if ($request->hasFile('audio_file')) {
            if ($schedule && $schedule->audio_file) {
                Storage::disk('public')->delete($schedule->audio_file);
            }
            $updateData['audio_file'] = $request->file('audio_file')->store('bells', 'public');
        }

        DB::table('learning_schedules')->where('id', $id)->update($updateData);

        return redirect()->back()->with('success', 'Jadwal bel berhasil diperbarui!');
    }

    public function updateSettings(Request $request)
    {
        $isActive = $request->has('is_bell_active');
        
        // Simpan state secara global menggunakan Cache Laravel
        Cache::forever('is_bell_active', $isActive);

        return redirect()->back()->with('success', 'Pengaturan Master Bel berhasil diperbarui.');
    }

    public function destroyLearning($id)
    {
        $schedule = DB::table('learning_schedules')->where('id', $id)->first();
        
        if ($schedule && $schedule->audio_file) {
            Storage::disk('public')->delete($schedule->audio_file);
        }

        DB::table('learning_schedules')->where('id', $id)->delete();

        return redirect()->back()->with('success', 'Jadwal bel berhasil dihapus!');
    }
}