<?php

namespace App\Http\Controllers;

use App\Models\Timeslot;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TimeslotImport;
use Illuminate\Support\Facades\DB;

class TimeslotController extends Controller
{
    public function index()
    {
        // PERBAIKAN: Gunakan paginate(15) alih-alih get() agar tabel dipecah per halaman
        $timeslots = Timeslot::orderBy('order_sequence')->paginate(15);
            
        return view('timeslots.index', compact('timeslots'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'day_of_week' => 'required|array|min:1',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'order_sequence' => 'required|integer|min:1',
        ], [
            'day_of_week.required' => 'Minimal pilih 1 hari berlakunya sesi ini.',
            'end_time.after' => 'Jam Selesai harus lebih besar dari Jam Mulai.'
        ]);

       Timeslot::create([
            'name' => $request->name,
            'day_of_week' => implode(',', $request->day_of_week),
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'is_break' => $request->boolean('is_break') ? 1 : 0,
            'order_sequence' => $request->order_sequence,
        ]);

        return redirect()->back()->with('success', 'Slot waktu berhasil ditambahkan.');
    }

     public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'day_of_week' => 'required|array|min:1',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'order_sequence' => 'required|integer|min:1',
        ], [
            'day_of_week.required' => 'Minimal pilih 1 hari berlakunya sesi ini.',
            'end_time.after' => 'Jam Selesai harus lebih besar dari Jam Mulai.'
        ]);

        $timeslot = Timeslot::findOrFail($id);
        
        $timeslot->update([
            'name' => $request->name,
            'day_of_week' => implode(',', $request->day_of_week),
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'order_sequence' => $request->order_sequence,
            'is_break' => $request->boolean('is_break') ? 1 : 0
        ]);

        return redirect()->back()->with('success', 'Slot waktu berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $timeslot = Timeslot::findOrFail($id);
        $timeslot->delete();

        return redirect()->back()->with('success', 'Slot waktu berhasil dihapus.');
    }

    public function template()
    {
        $headers = [
            'Content-type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename=Template_Slot_Waktu.csv',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0'
        ];

        $columns = ['nama_sesi', 'hari', 'jam_mulai', 'jam_selesai', 'urutan', 'istirahat'];

        $callback = function() use($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            fputcsv($file, ['Jam ke-1', 'Senin,Selasa,Rabu,Kamis', '07:00', '07:45', '1', 'tidak']);
            fputcsv($file, ['Istirahat Pagi', 'Semua Hari', '09:15', '09:45', '4', 'ya']);
            fputcsv($file, ['Upacara Bendera', 'Senin', '07:00', '07:45', '1', 'ya']);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:5120'
        ], [
            'file.mimes' => 'Format file ditolak! Pastikan Anda mengupload file dengan format Excel (.xlsx, .xls) atau CSV (.csv).',
            'file.max' => 'Ukuran file maksimal adalah 5MB.'
        ]);

        try {
            Excel::import(new TimeslotImport, $request->file('file'));
            return redirect()->back()->with('success', 'Data slot waktu dari Excel berhasil diproses!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal meng-import data. Detail Error: ' . $e->getMessage());
        }
    }

    public function reset()
    {
        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            \App\Models\Timetable::truncate();
            Timeslot::truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            return redirect()->back()->with('success', 'Seluruh slot waktu (beserta jadwal terkait) berhasil dikosongkan secara paksa!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengosongkan data: ' . $e->getMessage());
        }
    }
}