<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

// Import library Excel & Class Export/Import
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use App\Imports\UsersImport;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('users.index', ['users' => $users]);
    }

    public function create()
    {
        return redirect()->route('users.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:Admin,Kepala Sekolah,Wali Kelas,Guru Piket,Guru'],
            
            // Validasi Data Profil & Kontak
            'position' => ['nullable', 'string', 'max:50'],
            'pangkat' => ['nullable', 'string', 'max:50'], // [PENTING] Validasi Pangkat
            'bio' => ['nullable', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'nip' => ['nullable', 'string', 'max:20'],
            'phone' => ['nullable', 'string', 'max:20'], 
            'instagram' => ['nullable', 'string', 'max:50'],
            'tiktok' => ['nullable', 'string', 'max:50'],
            'facebook' => ['nullable', 'string', 'max:50'],
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('teachers', 'public');
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'position' => $request->position,
            'pangkat' => $request->pangkat, // [PENTING] Simpan Pangkat
            'bio' => $request->bio,
            'photo_path' => $photoPath,
            'nip' => $request->nip,
            // Simpan Kontak
            'phone' => $request->phone,
            'instagram' => $request->instagram,
            'tiktok' => $request->tiktok,
            'facebook' => $request->facebook,
        ]);

        return redirect()->route('users.index')->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', 'string', 'in:Admin,Kepala Sekolah,Wali Kelas,Guru Piket,Guru'],
            'position' => ['nullable', 'string', 'max:50'],
            'pangkat' => ['nullable', 'string', 'max:50'], // [PENTING] Validasi Pangkat
            'bio' => ['nullable', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'nip' => ['nullable', 'string', 'max:20'],
            // Validasi Kontak Baru
            'phone' => ['nullable', 'string', 'max:20'],
            'instagram' => ['nullable', 'string', 'max:50'],
            'tiktok' => ['nullable', 'string', 'max:50'],
            'facebook' => ['nullable', 'string', 'max:50'],
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'position' => $request->position,
            'pangkat' => $request->pangkat, // [PENTING] Update Pangkat
            'bio' => $request->bio,
            'nip' => $request->nip,
            // Update Data Kontak
            'phone' => $request->phone,
            'instagram' => $request->instagram,
            'tiktok' => $request->tiktok,
            'facebook' => $request->facebook,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('photo')) {
            if ($user->photo_path) {
                Storage::disk('public')->delete($user->photo_path);
            }
            $data['photo_path'] = $request->file('photo')->store('teachers', 'public');
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if (Auth::id() == $user->id) {
            return redirect()->route('users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        if ($user->photo_path) {
            Storage::disk('public')->delete($user->photo_path);
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    // ===========================================================
    // FITUR EXPORT & IMPORT EXCEL
    // ===========================================================

    public function export()
    {
        return Excel::download(new UsersExport, 'data-pengguna.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:5120'
        ]);

        try {
            Excel::import(new UsersImport, $request->file('file'));
            return redirect()->back()->with('success', 'Data pengguna berhasil di-import dari Excel!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
             $failures = $e->failures();
             $errorMsg = "Gagal Import. Baris ke-" . $failures[0]->row() . ": " . implode(', ', $failures[0]->errors());
             return redirect()->back()->withErrors(['file' => $errorMsg]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['file' => 'Terjadi kesalahan saat import: ' . $e->getMessage()]);
        }
    }
}