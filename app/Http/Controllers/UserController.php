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
    /**
     * Daftar Role yang tersedia dalam sistem sesuai fungsi.
     */
    protected $availableRoles = [
        'Admin',                
        'Kepala Sekolah',       
        'TU',                   
        'Wali Kelas',           
        'Guru Mata Pelajaran',  
        'Guru Piket',           
        'Guru'                  
    ];

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
        // Ubah validasi role menjadi array
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'array'], // Input harus Array
            'role.*' => ['string', 'in:' . implode(',', $this->availableRoles)], // Tiap item harus valid
            
            'position' => ['nullable', 'string', 'max:50'],
            'pangkat' => ['nullable', 'string', 'max:50'],
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
       
        $rolesJson = json_encode($request->role);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $rolesJson, // Simpan JSON
            'position' => $request->position,
            'pangkat' => $request->pangkat,
            'bio' => $request->bio,
            'photo_path' => $photoPath,
            'nip' => $request->nip,
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
            'role' => ['required', 'array'], // Input harus Array
            'role.*' => ['string', 'in:' . implode(',', $this->availableRoles)],
            'position' => ['nullable', 'string', 'max:50'],
            'pangkat' => ['nullable', 'string', 'max:50'],
            'bio' => ['nullable', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'nip' => ['nullable', 'string', 'max:20'],
            'phone' => ['nullable', 'string', 'max:20'],
            'instagram' => ['nullable', 'string', 'max:50'],
            'tiktok' => ['nullable', 'string', 'max:50'],
            'facebook' => ['nullable', 'string', 'max:50'],
        ]);
        
        if (Auth::user()->role !== 'Admin' && in_array('Admin', $request->role)) {
             return redirect()->back()->with('error', 'Anda tidak memiliki hak akses untuk menjadikan user sebagai Admin.');
        }

        $rolesJson = json_encode($request->role);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $rolesJson, // Simpan JSON
            'position' => $request->position,
            'pangkat' => $request->pangkat,
            'bio' => $request->bio,
            'nip' => $request->nip,
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
     
        $targetUserRoles = is_string($user->role) ? json_decode($user->role, true) : $user->role;
        if (!is_array($targetUserRoles)) $targetUserRoles = [$user->role];

        $currentUserRoles = Auth::user()->role;
        if (is_string($currentUserRoles)) $currentUserRoles = json_decode($currentUserRoles, true);
        
        $amIAdmin = in_array('Admin', is_array($currentUserRoles) ? $currentUserRoles : []);

        // Jika bukan admin
        if (!$amIAdmin) {
            // Cek apakah target punya role Admin
            if (in_array('Admin', $targetUserRoles)) {
                return redirect()->route('users.index')->with('error', 'Anda tidak memiliki wewenang menghapus Administrator.');
            }
        }

        if ($user->photo_path) {
            Storage::disk('public')->delete($user->photo_path);
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    // Export Import tetap sama...
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