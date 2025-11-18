{{-- Halaman ini adalah tampilan untuk resources/views/users/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Pengguna (Guru & Staf)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- Tampilkan pesan sukses jika ada --}}
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Tampilkan error validasi jika ada --}}
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Kontainer Grid: 1/3 untuk Form, 2/3 untuk Tabel --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        {{-- Kolom 1: Form Tambah Pengguna Baru --}}
                        <div class="md:col-span-1">
                            <h3 class="text-lg font-medium mb-4">Tambah Pengguna Baru</h3>
                            <form action="{{ route('users.store') }}" method="POST">
                                @csrf
                                
                                {{-- Nama Lengkap --}}
                                <div class="mb-4">
                                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" required 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>

                                {{-- Email (untuk login) --}}
                                <div class="mb-4">
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email (untuk login)</label>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}" required 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>

                                {{-- Peran (Role) --}}
                                <div class="mb-4">
                                    <label for="role" class="block text-sm font-medium text-gray-700">Peran (Role)</label>
                                    <select name="role" id="role" required 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <option value="Admin" {{ old('role') == 'Admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="Kepala Sekolah" {{ old('role') == 'Kepala Sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                                        <option value="Wali Kelas" {{ old('role') == 'Wali Kelas' ? 'selected' : '' }}>Wali Kelas</option>
                                        <option value="Guru Piket" {{ old('role') == 'Guru Piket' ? 'selected' : '' }}>Guru Piket</option>
                                        <option value="Guru" {{ old('role', 'Guru') == 'Guru' ? 'selected' : '' }}>Guru</option>
                                    </select>
                                </div>

                                {{-- Password --}}
                                <div class="mb-4">
                                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                                    <input type="password" name="password" id="password" required 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>
                                
                                {{-- Konfirmasi Password --}}
                                <div class="mb-4">
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" required 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>

                                <button type="submit" 
                                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    Tambah Pengguna
                                </button>
                            </form>
                        </div>

                        {{-- Kolom 2: Daftar Pengguna Aktif --}}
                        <div class="md:col-span-2">
                            <h3 class="text-lg font-medium mb-4">Daftar Pengguna Aktif</h3>
                            
                            <div class="overflow-x-auto border rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peran</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse ($users as $user)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $user->name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $user->email }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $user->role }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    {{-- <a href="#" class="text-yellow-600 hover:text-yellow-900">Edit</a> --}}
                                                    
                                                    {{-- Jangan biarkan user menghapus dirinya sendiri --}}
                                                    @if(Auth::id() != $user->id)
                                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-900 ml-4">Hapus</button>
                                                        </form>
                                                    @else
                                                        <span class="text-gray-400 ml-4">(Anda)</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                                    Belum ada data pengguna lain.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            
                            {{-- Pagination Links --}}
                            <div class="mt-4">
                                {{ $users->links() }}
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>