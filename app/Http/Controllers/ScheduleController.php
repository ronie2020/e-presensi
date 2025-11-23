<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScheduleRegular;
use App\Models\ScheduleSpecial;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon; 

class ScheduleController extends Controller
{
    /**
     * Menampilkan halaman utama Manajemen Jadwal.
     */
    public function index()
    {
        $regularSchedules = ScheduleRegular::all()->keyBy('day_type');
        $specialSchedules = ScheduleSpecial::orderBy('date', 'desc')->get();

        return view('schedules.index', [
            'regularSchedules' => $regularSchedules,
            'specialSchedules' => $specialSchedules,
        ]);
    }

    /**
     * Menyimpan atau memperbarui data Jadwal Reguler.
     * PERBAIKAN: Menghapus validasi ketat 'date_format:H:i' yang menyebabkan error jika ada detik.
     */
    public function storeRegular(Request $request)
    {
        // Validasi input
        $request->validate([
            'day_type.*' => 'required|string|in:Biasa,Jumat',
            // Cukup 'required' saja, hilangkan 'date_format:H:i' agar lebih fleksibel
            'start_in.*' => 'required', 
            'end_in.*' => 'required|after:start_in.*',
            'start_out.*' => 'required',
            'end_out.*' => 'required|after:start_out.*',
        ]);

        // Looping data
        foreach ($request->day_type as $index => $dayType) {
            ScheduleRegular::updateOrCreate(
                ['day_type' => $dayType], 
                [ 
                    'start_in' => $request->start_in[$index],
                    'end_in' => $request->end_in[$index],
                    'start_out' => $request->start_out[$index],
                    'end_out' => $request->end_out[$index],
                ]
            );
        }

        return redirect()->route('schedules.index')->with('success', 'Jadwal Reguler berhasil diperbarui.');
    }

    /**
     * Menyimpan data Jadwal Khusus baru.
     */
    public function storeSpecial(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'date' => 'required|date|unique:schedules_special,date',
            'description' => 'nullable|string|max:255',
            'is_holiday' => 'nullable|boolean',
            // Untuk jadwal khusus, kita juga bisa melonggarkan date_format jika perlu, 
            // tapi biasanya form create baru aman karena belum ada data detiknya.
            'start_in' => 'nullable|required_if:is_holiday,null,0',
            'end_in' => 'nullable|required_if:is_holiday,null,0|after_or_equal:start_in',
            'start_out' => 'nullable|required_if:is_holiday,null,0',
            'end_out' => 'nullable|required_if:is_holiday,null,0|after_or_equal:start_out',
        ], [
            'date.unique' => 'Jadwal khusus untuk tanggal ini sudah ada.',
            'start_in.required_if' => 'Jam Masuk Mulai wajib diisi jika ini bukan hari libur.',
        ]);

        $validatedData['is_holiday'] = $request->has('is_holiday');

        ScheduleSpecial::create($validatedData);

        return redirect()->route('schedules.index')->with('success', 'Jadwal Khusus berhasil ditambahkan.');
    }

    /**
     * Menghapus data Jadwal Khusus.
     */
    public function destroySpecial(ScheduleSpecial $schedule)
    {
        $schedule->delete();
        return redirect()->route('schedules.index')->with('success', 'Jadwal Khusus berhasil dihapus.');
    }
}