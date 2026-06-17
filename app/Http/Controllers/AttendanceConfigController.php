<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AttendanceConfig;
use Illuminate\Http\Request;

class AttendanceConfigController extends Controller
{
    /**
     * Menampilkan Form Pengaturan
     */
    public function index()
    {
        // Ambil data baris pertama, jika kosong otomatis buat data default
        $config = AttendanceConfig::firstOrCreate(
            ['id' => 1],
            [
                'dhuha_start'  => '07:30:00',
                'dhuha_end'    => '08:00:00',
                'makan_start'  => '09:00:00',
                'makan_end'    => '10:00:00',
                'dhuhur_start' => '11:45:00',
                'dhuhur_end'   => '13:30:00',
            ]
        );

        return view('admin.config.index', compact('config'));
    }

    /**
     * Menyimpan Perubahan Jam ke Database
     */
    public function update(Request $request)
    {
        // Validasi agar jam selesai tidak lebih awal dari jam mulai
        $request->validate([
            'dhuha_start'  => 'required',
            'dhuha_end'    => 'required|after:dhuha_start',
            'makan_start'  => 'required',
            'makan_end'    => 'required|after:makan_start',
            'dhuhur_start' => 'required',
            'dhuhur_end'   => 'required|after:dhuhur_start',
        ], [
            'dhuha_end.after'   => 'Jam selesai Dhuha harus setelah jam mulai.',
            'makan_end.after'   => 'Jam selesai Makan Siang harus setelah jam mulai.',
            'dhuhur_end.after' => 'Jam selesai Dhuhur harus setelah jam mulai.',
        ]);

        $config = AttendanceConfig::find(1);
        $config->update($request->all());

        return back()->with('success', 'Konfigurasi rentang waktu absensi berhasil diperbarui!');
    }
}
