<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GuestBook; // Pastikan Anda membuat Model ini nanti

class GuestBookController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi Input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'institution' => 'required|string|max:255',
            'purpose' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // 2. Simpan ke Database
        // Pastikan Anda sudah membuat Model GuestBook dan Migration-nya
        if (class_exists(GuestBook::class)) {
            GuestBook::create($validated);
        }

        // 3. Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Terima kasih! Data buku tamu berhasil disimpan.');
    }
}