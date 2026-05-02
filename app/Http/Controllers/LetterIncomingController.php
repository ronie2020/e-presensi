<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LetterIncoming;
use Illuminate\Support\Facades\Storage;

class LetterIncomingController extends Controller
{
    // MENAMPILKAN DATA DARI DATABASE (DENGAN PENCARIAN)
    public function index(Request $request)
    {
        $query = LetterIncoming::query();

        // Logika Pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%")
                  ->orWhere('asal_surat', 'like', "%{$search}%");
            });
        }
        
        $letters = $query->latest()->paginate(10);

        return view('letters.incoming.index', compact('letters'));
    }

     public function create()
    {
        // Logika Nomor Agenda Otomatis
        $lastLetter = LetterIncoming::latest('id')->first();
        
        if (!$lastLetter) {
            // Jika belum ada data sama sekali, mulai dari 0001
            $nextAgenda = '0001';
        } else {
            // Ambil angka dari agenda terakhir, jadikan integer, tambah 1
            $lastAgendaNumber = intval($lastLetter->nomor_agenda);
            // Format kembali menjadi 4 digit (contoh: 0002, 0003, dst)
            $nextAgenda = str_pad($lastAgendaNumber + 1, 4, '0', STR_PAD_LEFT);
        }

        // Kirim variabel $nextAgenda ke halaman view
        return view('letters.incoming.create', compact('nextAgenda'));
    }
    
    // MENYIMPAN DATA BARU
    public function store(Request $request)
    {
        $request->validate([
            'nomor_agenda' => 'required|string',
            'nomor_surat'  => 'required|string|max:255',
            'sifat_surat'  => 'required|string',
            'asal_surat'   => 'required|string|max:255',
            'tgl_surat'    => 'required|date',
            'tgl_diterima' => 'required|date',
            'perihal'      => 'required|string',
            'file_surat'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $data = $request->except(['file_surat']);

        // Handle Upload File jika ada
        if ($request->hasFile('file_surat')) {
            $path = $request->file('file_surat')->store('surat-masuk', 'public');
            $data['file_path'] = $path;
        }

        LetterIncoming::create($data);

        return redirect()->route('letters.incoming.index')
            ->with('success', 'Surat Masuk berhasil disimpan!');
    }

    // MENAMPILKAN FORM EDIT
    public function edit($id)
    {
        $letter = LetterIncoming::findOrFail($id);
        return view('letters.incoming.edit', compact('letter'));
    }

    // MEMPROSES UPDATE DATA
    public function update(Request $request, $id)
    {
        $letter = LetterIncoming::findOrFail($id);

        $request->validate([
            'nomor_surat'  => 'required|string|max:255',
            'asal_surat'   => 'required|string|max:255', // Tadinya 'pengirim'
            'tgl_surat'    => 'required|date',
            'tgl_diterima' => 'required|date',           // Tadinya 'tgl_terima'
            'perihal'      => 'required|string',
            'file_surat'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $data = $request->except(['file_surat', '_token', '_method']);

        // Handle File Upload saat Update
        if ($request->hasFile('file_surat')) {
            // 1. Hapus file lama jika ada
            if ($letter->file_path && Storage::disk('public')->exists($letter->file_path)) {
                Storage::disk('public')->delete($letter->file_path);
            }

            // 2. Upload file baru
            $path = $request->file('file_surat')->store('surat-masuk', 'public');
            $data['file_path'] = $path;
        }

        $letter->update($data);

        return redirect()->route('letters.incoming.index')
            ->with('success', 'Data Surat berhasil diperbarui!');
    }

    // MENGHAPUS DATA
    public function destroy($id)
    {
        $letter = LetterIncoming::findOrFail($id);

        // Hapus file fisik jika ada
        if ($letter->file_path && Storage::disk('public')->exists($letter->file_path)) {
            Storage::disk('public')->delete($letter->file_path);
        }

        $letter->delete();

        return redirect()->route('letters.incoming.index')
            ->with('success', 'Surat berhasil dihapus!');
    }
}