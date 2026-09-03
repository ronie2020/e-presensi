<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScheduleRegular; 
use App\Models\ScheduleSpecial; 
use Carbon\Carbon; 
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    /**
     * Menampilkan Halaman Pengaturan Jam Operasional (Mesin Absen & Libur)
     */
    public function index(Request $request)
    {
        // DATA JAM BEL REGULER & KHUSUS HARI LIBUR
        $regularSchedules = ScheduleRegular::all()->keyBy('day_name');
        $specialSchedules = ScheduleSpecial::orderBy('date', 'desc')->get();

         //  data jam pembelajaran (bel)
        $learningSchedules = DB::table('learning_schedules')->orderBy('trigger_time', 'asc')->get();


        return view('admin.schedules.index', [
            'regularSchedules' => $regularSchedules,
            'specialSchedules' => $specialSchedules,
            'learningSchedules' => $learningSchedules,
        ]);
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

     // ==========================================
    // JAM PEMBELAJARAN (BEL)
    // ==========================================

    public function storeLearning(Request $request)
    {
        $request->validate([
            'activity_name' => 'required|string|max:255',
            'trigger_time' => 'required',
            'audio_file' => 'nullable|mimes:mp3,wav|max:5120', // Maksimal 5MB, format mp3/wav
        ]);

        $audioPath = null;
        if ($request->hasFile('audio_file')) {
            // Simpan file ke folder storage/app/public/bells
            $audioPath = $request->file('audio_file')->store('bells', 'public');
        }

        \Illuminate\Support\Facades\DB::table('learning_schedules')->insert([
            'activity_name' => $request->activity_name,
            'trigger_time' => $request->trigger_time,
            'audio_file' => $audioPath,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Jadwal bel berhasil ditambahkan!');
    }

    public function destroyLearning($id)
    {
        // Ambil data untuk menghapus file audio fisik jika ada
        $schedule = \Illuminate\Support\Facades\DB::table('learning_schedules')->where('id', $id)->first();
        
        if ($schedule && $schedule->audio_file) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($schedule->audio_file);
        }

        \Illuminate\Support\Facades\DB::table('learning_schedules')->where('id', $id)->delete();

        return redirect()->back()->with('success', 'Jadwal bel berhasil dihapus!');
    }
}