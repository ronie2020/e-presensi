<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScheduleRegular;
use App\Models\ScheduleSpecial;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon; // Kita akan gunakan Carbon untuk format tanggal

class ScheduleController extends Controller
{
    /**
     * Menampilkan halaman utama Manajemen Jadwal.
     * Akan memuat data jadwal reguler dan khusus.
     */
    public function index()
    {
        // Ambil data jadwal reguler
        // Kita gunakan keyBy('day_type') agar mudah diakses di view
        $regularSchedules = ScheduleRegular::all()->keyBy('day_type');

        // Ambil data jadwal khusus, urutkan dari yang terbaru (tanggalnya)
        $specialSchedules = ScheduleSpecial::orderBy('date', 'desc')->get();

        // Tampilkan view dan kirimkan datanya
        return view('schedules.index', [
            'regularSchedules' => $regularSchedules,
            'specialSchedules' => $specialSchedules,
        ]);
    }

    /**
     * Menyimpan atau memperbarui data Jadwal Reguler.
     */
    public function storeRegular(Request $request)
    {
        // Validasi input
        $request->validate([
            'day_type.*' => 'required|string|in:Biasa,Jumat',
            'start_in.*' => 'required|date_format:H:i',
            'end_in.*' => 'required|date_format:H:i|after:start_in.*',
            'start_out.*' => 'required|date_format:H:i',
            'end_out.*' => 'required|date_format:H:i|after:start_out.*',
        ]);

        // Looping data (indeks 0 untuk 'Biasa', indeks 1 untuk 'Jumat')
        foreach ($request->day_type as $index => $dayType) {
            ScheduleRegular::updateOrCreate(
                ['day_type' => $dayType], // Kunci untuk mencari
                [ // Data untuk di-update atau di-create
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
            // Jam hanya wajib jika 'is_holiday' tidak dicentang (null atau 0)
            'start_in' => 'nullable|required_if:is_holiday,null,0|date_format:H:i',
            'end_in' => 'nullable|required_if:is_holiday,null,0|date_format:H:i|after_or_equal:start_in',
            'start_out' => 'nullable|required_if:is_holiday,null,0|date_format:H:i',
            'end_out' => 'nullable|required_if:is_holiday,null,0|date_format:H:i|after_or_equal:start_out',
        ], [
            'date.unique' => 'Jadwal khusus untuk tanggal ini sudah ada.',
            'start_in.required_if' => 'Jam Masuk Mulai wajib diisi jika ini bukan hari libur.',
        ]);

        // Atur nilai 'is_holiday' (jika tidak dicentang, nilainya null)
        $validatedData['is_holiday'] = $request->has('is_holiday');

        // Buat data baru
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