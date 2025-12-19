<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LetterIncoming; // Pastikan Model diimport
use Illuminate\Support\Facades\Storage;

class LetterIncomingController extends Controller
{
    // MENAMPILKAN DATA DARI DATABASE
    public function index()
    {
        // Ambil data dari database, urutkan dari yang terbaru
        $letters = LetterIncoming::latest()->paginate(10);

        return view('letters.incoming.index', compact('letters'));
    }

    public function create()
    {
        return view('letters.incoming.create');
    }

    // MENYIMPAN DATA KE DATABASE
    public function store(Request $request)
    {
        $request->validate([
            'nomor_surat' => 'required|string|max:255',
            'pengirim' => 'required|string|max:255',
            'tgl_surat' => 'required|date',
            'tgl_terima' => 'required|date',
            'perihal' => 'required|string',
            'file_surat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048', // Max 2MB
        ]);

        $data = $request->except(['file_surat']);

        // Handle Upload File jika ada
        if ($request->hasFile('file_surat')) {
            // Simpan ke folder 'storage/app/public/surat-masuk'
            $path = $request->file('file_surat')->store('surat-masuk', 'public');
            $data['file_path'] = $path;
        }

        LetterIncoming::create($data);

        return redirect()->route('letters.incoming.index')
            ->with('success', 'Surat Masuk berhasil disimpan ke Database!');
    }
}