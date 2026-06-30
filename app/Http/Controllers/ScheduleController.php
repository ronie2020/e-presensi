<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScheduleRegular; 
use App\Models\ScheduleSpecial; 
use Carbon\Carbon; 

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

        return view('admin.schedules.index', [
            'regularSchedules' => $regularSchedules,
            'specialSchedules' => $specialSchedules,
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
}