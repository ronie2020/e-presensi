<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GuestBook; 

class GuestBookController extends Controller
{
    public function store(Request $request)
    {
        // 0. Proteksi Honeypot Anti-Spam: Jika field website_hp terisi, ini adalah bot
        if ($request->filled('website_hp')) {
            return redirect()->back()->with('success', 'Terima kasih! Data buku tamu berhasil disimpan.');
        }

        // 1. Validasi Input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'institution' => 'required|string|max:255',
            'purpose' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ]);

        // 2. Simpan ke Database & Clear Cache Landing Page
        if (class_exists(GuestBook::class)) {
            GuestBook::create($validated);
            \Illuminate\Support\Facades\Cache::forget('landing_general_data');
        }

        // 3. Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Terima kasih! Data buku tamu berhasil disimpan.');
    }
}