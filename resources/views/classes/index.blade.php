{{-- Halaman ini adalah tampilan untuk resources/views/classes/index.blade.php --}}
<x-app-layout>
    {{-- 
        X-DATA CONTEXT:
        Menambahkan state 'search' untuk fitur pencarian real-time client-side.
    --}}
    <div x-data="{ search: '' }" class="py-6 sm:py-8">
        
        {{-- Header Page --}}
        <div class="mb-8 px-4 sm:px-0 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight leading-tight flex items-center gap-3">
                    <i class="ph-duotone ph-chalkboard-teacher text-blue-600"></i> Manajemen Kelas
                </h1>
                <p class="text-slate-500 mt-2 text-lg">
                    Atur daftar kelas dan tetapkan wali kelas.
                </p>
            </div>
        </div>

        {{-- Pesan Sukses --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 mx-4 sm:mx-0 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-emerald-100 rounded-full text-emerald-600">
                        <i class="ph-bold ph-check"></i>
                    </div>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="hover:bg-emerald-100 p-1 rounded-lg transition"><i class="ph-bold ph-x"></i></button>
            </div>
        @endif

        {{-- Pesan Error --}}
        @if (session('error'))
            <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 mx-4 sm:mx-0 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-rose-100 rounded-full text-rose-600">
                        <i class="ph-bold ph-warning"></i>
                    </div>
                    <span class="font-bold">{{ session('error') }}</span>
                </div>
                <button @click="show = false" class="hover:bg-rose-100 p-1 rounded-lg transition"><i class="ph-bold ph-x"></i></button>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 px-4 sm:px-0 items-start">

            {{-- KOLOM 1: FORM TAMBAH KELAS --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden relative sticky top-24">
                    {{-- Aksen Biru di Atas --}}
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-blue-500"></div>
                    
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl">
                                <i class="ph-duotone ph-plus-square"></i>
                            </div>
                            <h2 class="text-lg font-bold text-slate-800">Tambah Kelas</h2>
                        </div>

                        <form action="{{ route('classes.store') }}" method="POST" class="space-y-5">
                            @csrf
                            
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Nama Kelas</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                        <i class="ph-bold ph-chalkboard"></i>
                                    </div>
                                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: 7A, 8F" 
                                           class="w-full pl-10 pr-4 py-3 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm font-bold text-slate-700 transition-colors placeholder:font-normal @error('name') border-rose-300 bg-rose-50 @enderror">
                                </div>
                                @error('name')
                                    <p class="mt-1 text-xs text-rose-500 font-bold">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Wali Kelas (Opsional)</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                        <i class="ph-bold ph-user-circle"></i>
                                    </div>
                                    <select name="homeroom_teacher_id" class="w-full pl-10 pr-10 py-3 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm font-medium text-slate-700 transition-colors appearance-none cursor-pointer">
                                        <option value="">-- Pilih Guru --</option>
                                        @foreach ($teachers as $teacher)
                                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
                                        <i class="ph-bold ph-caret-down"></i>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="w-full py-3 px-6 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/30 flex items-center justify-center gap-2 transform active:scale-[0.98]">
                                <i class="ph-bold ph-floppy-disk"></i>
                                Simpan Kelas
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- KOLOM 2: DAFTAR KELAS --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden min-h-[500px] flex flex-col">
                    <div class="p-6 border-b border-slate-50 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                        <div class="flex items-center gap-3">
                            <h3 class="text-lg font-bold text-slate-800">Daftar Kelas</h3>
                            <span class="bg-slate-100 text-xs font-bold px-3 py-1 rounded-full text-slate-500 border border-slate-200">{{ $classes->count() }} Rombel</span>
                        </div>
                        
                        {{-- Search Box (Fitur Baru) --}}
                        <div class="relative w-full sm:w-64">
                            <input x-model="search" type="text" placeholder="Cari kelas atau wali..." class="w-full pl-10 pr-4 py-2 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm font-medium transition-colors">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <i class="ph-bold ph-magnifying-glass"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto flex-1">
                        <table class="min-w-full text-left border-collapse">
                            <thead class="bg-slate-50 border-b border-slate-100">
                                <tr>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Nama Kelas</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Wali Kelas</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse ($classes as $class)
                                    <tr class="hover:bg-slate-50/50 transition-colors group"
                                        x-show="search === '' || '{{ strtolower($class->name) }}'.includes(search.toLowerCase()) || '{{ strtolower($class->homeroomTeacher->name ?? '') }}'.includes(search.toLowerCase())"
                                        x-transition.opacity>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-500 font-black flex items-center justify-center text-xs group-hover:bg-blue-50 group-hover:text-blue-600 transition-colors">
                                                    {{ substr($class->name, 0, 3) }}
                                                </div>
                                                <span class="font-bold text-slate-700 text-sm">{{ $class->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($class->homeroomTeacher)
                                                <div class="flex items-center gap-2">
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                        <i class="ph-bold ph-user"></i>
                                                        {{ $class->homeroomTeacher->name }}
                                                    </span>
                                                </div>
                                            @else
                                                <span class="text-xs text-slate-400 italic flex items-center gap-1">
                                                    <i class="ph-fill ph-warning-circle"></i> Belum diatur
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="flex items-center justify-end gap-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                                                {{-- Tombol Edit --}}
                                                <a href="{{ route('classes.edit', $class->id) }}" class="p-2 text-slate-300 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit Kelas">
                                                    <i class="ph-bold ph-pencil-simple text-lg"></i>
                                                </a>
                                                
                                                {{-- Tombol Hapus --}}
                                                <form action="{{ route('classes.destroy', $class->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kelas {{ $class->name }}? Data siswa di dalamnya mungkin akan terpengaruh.');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="p-2 text-slate-300 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus Kelas">
                                                        <i class="ph-bold ph-trash text-lg"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-16 text-center text-slate-400">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3 text-slate-300">
                                                    <i class="ph-duotone ph-chalkboard text-4xl"></i>
                                                </div>
                                                <p class="text-sm font-bold text-slate-500">Belum ada data kelas.</p>
                                                <p class="text-xs text-slate-400">Silakan tambahkan kelas baru di formulir sebelah kiri.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse

                                {{-- State Pencarian Kosong --}}
                                <tr x-show="search !== '' && $el.parentNode.querySelectorAll('tr[x-show]:not([style*=\'display: none\'])').length === 0" style="display: none;">
                                    <td colspan="3" class="px-6 py-8 text-center text-slate-400">
                                        Tidak ditemukan kelas dengan kata kunci "<span x-text="search" class="font-bold text-slate-600"></span>"
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>