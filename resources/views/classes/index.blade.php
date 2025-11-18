{{-- Halaman ini adalah tampilan untuk resources/views/classes/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Data Kelas') }}
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
                    
                    {{-- Tampilkan pesan error (untuk foreign key) --}}
                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- Kontainer Grid: 1/3 untuk Form, 2/3 untuk Tabel --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        {{-- Kolom 1: Form Tambah Kelas Baru --}}
                        <div class="md:col-span-1">
                            <h3 class="text-lg font-medium mb-4">Tambah Kelas Baru</h3>
                            <form action="{{ route('classes.store') }}" method="POST">
                                @csrf
                                
                                {{-- Nama Kelas --}}
                                <div class="mb-4">
                                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Kelas (Contoh: 7A, 8F, 9E)</label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" required 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('name') border-red-500 @enderror">
                                    @error('name')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                {{-- 1. TAMBAHKAN DROPDOWN WALI KELAS --}}
                                <div class="mb-4">
                                    <label for="homeroom_teacher_id" class="block text-sm font-medium text-gray-700">Wali Kelas (Opsional)</label>
                                    <select name="homeroom_teacher_id" id="homeroom_teacher_id" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <option value="">-- Pilih Wali Kelas --</option>
                                        @foreach ($teachers as $teacher)
                                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <button type="submit" 
                                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Tambah Kelas
                                </button>
                            </form>
                        </div>

                            {{-- Kolom 2: Daftar Kelas --}}
                        <div class="md:col-span-2">
                            <h3 class="text-lg font-medium mb-4">Daftar Kelas</h3>
                            <div class="overflow-x-auto border rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kelas</th>
                                            {{-- 2. TAMBAHKAN KOLOM WALI KELAS --}}
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Wali Kelas</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse ($classes as $class)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $class->name }}
                                                </td>
                                                {{-- 3. TAMPILKAN NAMA WALI KELAS --}}
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $class->homeroomTeacher->name ?? '-' }}
                                                </td>
                                               <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    {{-- 1. UBAH BARIS INI (uncomment dan beri route) --}}
                                                    <a href="{{ route('classes.edit', $class->id) }}" class="text-yellow-600 hover:text-yellow-900 mr-4">Edit</a>
                                                    
                                                    <form action="{{ route('classes.destroy', $class->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus kelas ini? Menghapus kelas mungkin gagal jika masih ada siswa di dalamnya.');">                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                                                    Belum ada data kelas.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>