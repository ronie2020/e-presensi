<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Untuk enkripsi password
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth; // Untuk cek user yang login

class UserController extends Controller
{
    /**
     * Menampilkan halaman manajemen pengguna.
     */
    public function index()
    {
        // Ambil semua user, paginasi 10 per halaman
        $users = User::latest()->paginate(10);

        return view('users.index', [
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('users.index');
    }

    /**
     * Menyimpan pengguna (Guru/Staf) baru.
     */
    public function store(Request $request)
    {
        // 1. Validasi data
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:Admin,Kepala Sekolah,Wali Kelas,Guru Piket,Guru'],
        ]);

        // 2. Buat user baru
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // 3. Redirect kembali
        return redirect()->route('users.index')->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // Tidak kita gunakan
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // (Akan kita kembangkan nanti untuk fitur Edit)
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // (Akan kita kembangkan nanti untuk fitur Edit)
    }

    /**
     * Menghapus pengguna.
     */
    public function destroy(User $user)
    {
        // Keamanan: Pastikan user tidak menghapus akunnya sendiri
        if (Auth::id() == $user->id) {
            return redirect()->route('users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}