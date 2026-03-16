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

    /**
     * Helper: Cek apakah user yang login memiliki role Admin
     */
    private function checkIsAdmin()
    {
        $currentRoles = Auth::user()->role;
        // Decode JSON jika string, atau wrap array jika single string
        if (is_string($currentRoles)) {
            $decoded = json_decode($currentRoles, true);
            // Jika gagal decode (berarti string biasa "Admin"), jadikan array
            $currentRoles = (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) 
                            ? $decoded 
                            : [$currentRoles];
        } elseif (!is_array($currentRoles)) {
            $currentRoles = [$currentRoles];
        }

        return in_array('Admin', $currentRoles);
    }

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
            'role' => ['required', 'array'], 
            'role.*' => ['string', 'in:' . implode(',', $this->availableRoles)],
            
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

        // KEAMANAN: Hanya Admin yang boleh membuat user dengan role Admin
        if (in_array('Admin', $request->role) && !$this->checkIsAdmin()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki hak akses untuk membuat user Admin.');
        }

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('teachers', 'public');
        }
       
        $rolesJson = json_encode($request->role);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $rolesJson,
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
            'role' => ['required', 'array'],
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
            // [BARU] Validasi Data CV
            'tempat_lahir' => ['nullable', 'string', 'max:100'],
            'tanggal_lahir' => ['nullable', 'date'],
            'jenis_kelamin' => ['nullable', 'string', 'in:Laki-laki,Perempuan'],
            'agama' => ['nullable', 'string', 'max:50'],
            'status_pernikahan' => ['nullable', 'string', 'max:50'],
            'alamat' => ['nullable', 'string'],
            'keahlian' => ['nullable', 'string'],
            'hobi' => ['nullable', 'string'],
        ]);
        
        $imAdmin = $this->checkIsAdmin();

        // Jika SAYA bukan admin, DAN saya mencoba menambahkan role 'Admin' ke target -> TOLAK
        if (!$imAdmin && in_array('Admin', $request->role)) {
             return redirect()->back()->with('error', 'Anda tidak memiliki hak akses untuk menjadikan user sebagai Admin.');
        }

        // Opsional: Cegah Admin menghapus role Admin-nya sendiri (Anti Lockout)
        if (Auth::id() == $user->id && !in_array('Admin', $request->role)) {
             return redirect()->back()->with('error', 'Anda tidak boleh menghapus role Admin dari akun Anda sendiri.');
        }

        $rolesJson = json_encode($request->role);

         $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $rolesJson, 
            'position' => $request->position,
            'pangkat' => $request->pangkat,
            'bio' => $request->bio,
            'nip' => $request->nip,
            'phone' => $request->phone,
            'instagram' => $request->instagram,
            'tiktok' => $request->tiktok,
            'facebook' => $request->facebook,
            // [BARU] Simpan Data CV
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'agama' => $request->agama,
            'status_pernikahan' => $request->status_pernikahan,
            'alamat' => $request->alamat,
            'keahlian' => $request->keahlian,
            'hobi' => $request->hobi,
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

        // Logic Penghapusan perlu disesuaikan sedikit karena Role sekarang array/json
        $targetUserRoles = is_string($user->role) ? json_decode($user->role, true) : $user->role;
        // Handle jika decode gagal atau format lama
        if (!is_array($targetUserRoles)) {
            $targetUserRoles = is_string($user->role) ? [$user->role] : [];
        }

        // Gunakan helper checkIsAdmin
        $amIAdmin = $this->checkIsAdmin();

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