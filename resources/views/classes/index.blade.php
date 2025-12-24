<x-app-layout>
    {{-- 
        X-DATA CONTEXT:
        State 'search' untuk fitur pencarian real-time client-side.
    --}}
    <div x-data="{ search: '' }" class="py-8 sm:py-10 font-sans text-slate-800">
        
        {{-- HERO SECTION (Style Dashboard) --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10">
            <div class="relative rounded-[2.5rem] bg-gray-900 bg-gradient-to-br from-blue-900 via-blue-800 to-slate-900 p-8 sm:p-10 text-white shadow-2xl shadow-blue-900/40 overflow-hidden border border-white/10 group">
                
                {{-- Background Decorations --}}
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-80 h-80 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none group-hover:bg-indigo-500/30 transition-all duration-700"></div>
                <div class="absolute bottom-0 right-20 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                    
                    {{-- Text Content --}}
                    <div class="max-w-2xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 border border-indigo-400/30 text-indigo-200 text-[10px] font-bold uppercase tracking-widest mb-4 backdrop-blur-sm">
                            <i class="ph-fill ph-chalkboard-teacher"></i> Data Akademik
                        </div>
                        <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-3 flex items-center gap-3 text-white leading-tight">
                            Manajemen Kelas
                        </h1>
                        <p class="text-indigo-100/80 text-sm md:text-base font-medium leading-relaxed max-w-lg">
                            Kelola daftar rombongan belajar (rombel) dan penugasan wali kelas.
                        </p>
                    </div>
                    
                    {{-- Stats Cards --}}
                    <div class="flex flex-row md:flex-col lg:flex-row gap-4 w-full md:w-auto">
                        <div class="bg-white/10 backdrop-blur-md px-6 py-5 rounded-2xl border border-white/10 flex-1 md:flex-none min-w-[140px] text-center md:text-left hover:bg-white/15 transition-colors">
                            <div class="flex items-center justify-center md:justify-start gap-2 mb-1 text-indigo-300">
                                <i class="ph-duotone ph-student text-lg"></i>
                                <span class="text-[10px] font-bold uppercase tracking-wider">Total Rombel</span>
                            </div>
                            <span class="block text-3xl font-black text-white tracking-tight">{{ $classes->count() }}</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Pesan Flash (Style Modern) --}}
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition class="mb-8 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-[2rem] flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3 px-2">
                        <div class="p-2 bg-emerald-100 rounded-full text-emerald-600">
                            <i class="ph-bold ph-check-circle text-xl"></i>
                        </div>
                        <span class="font-bold text-sm">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 p-2 rounded-xl hover:bg-emerald-100 transition"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif

            @if (session('error'))
                <div x-data="{ show: true }" x-show="show" x-transition class="mb-8 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-[2rem] flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3 px-2">
                        <div class="p-2 bg-rose-100 rounded-full text-rose-600">
                            <i class="ph-bold ph-warning-circle text-xl"></i>
                        </div>
                        <span class="font-bold text-sm">{{ session('error') }}</span>
                    </div>
                    <button @click="show = false" class="text-rose-400 hover:text-rose-600 p-2 rounded-xl hover:bg-rose-100 transition"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

                {{-- KOLOM 1: FORM TAMBAH KELAS --}}
                <div class="lg:col-span-1">
                    <div class="bg-white p-8 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden sticky top-24">
                        {{-- Aksen Header --}}
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-500 to-indigo-600"></div>
                        
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl shadow-sm border border-blue-100">
                                <i class="ph-duotone ph-plus-square"></i>
                            </div>
                            <h2 class="text-lg font-black text-slate-800">Tambah Kelas</h2>
                        </div>

                        <form action="{{ route('classes.store') }}" method="POST" class="space-y-5">
                            @csrf
                            
                            {{-- Input Nama Kelas --}}
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Nama Kelas</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition-colors">
                                        <i class="ph-bold ph-chalkboard text-lg"></i>
                                    </div>
                                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: 7A" 
                                           class="w-full pl-11 pr-4 py-3 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 font-bold text-slate-700 transition-all shadow-sm placeholder:font-normal placeholder:text-slate-400 @error('name') border-rose-300 bg-rose-50 @enderror">
                                </div>
                                @error('name')
                                    <p class="mt-1.5 ml-1 text-xs text-rose-500 font-bold flex items-center gap-1"><i class="ph-bold ph-warning"></i> {{ $message }}</p>
                                @enderror
                            </div>
                            
                            {{-- Dropdown Wali Kelas --}}
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Wali Kelas (Opsional)</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition-colors">
                                        <i class="ph-bold ph-user-circle text-lg"></i>
                                    </div>
                                    <select name="homeroom_teacher_id" class="w-full pl-11 pr-10 py-3 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm font-bold text-slate-700 transition-all shadow-sm appearance-none cursor-pointer">
                                        <option value="">-- Pilih Guru --</option>
                                        @foreach ($teachers as $teacher)
                                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                        <i class="ph-bold ph-caret-down"></i>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="w-full mt-4 py-3.5 px-6 bg-blue-900 text-white font-bold rounded-2xl hover:bg-blue-800 transition-all shadow-lg shadow-blue-900/30 flex items-center justify-center gap-2 transform active:scale-[0.98]">
                                <i class="ph-bold ph-floppy-disk text-lg"></i>
                                Simpan Kelas
                            </button>
                        </form>
                    </div>
                </div>

                {{-- KOLOM 2: DAFTAR KELAS --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden min-h-[500px] flex flex-col relative">
                        {{-- Aksen Header --}}
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-500 to-indigo-600"></div>

                        <div class="p-8 border-b border-slate-50 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-xl shadow-sm border border-indigo-100">
                                    <i class="ph-duotone ph-list-numbers"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-black text-slate-800">Daftar Rombel</h3>
                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Total {{ $classes->count() }} Kelas</p>
                                </div>
                            </div>
                            
                            {{-- Search Box --}}
                            <div class="relative w-full sm:w-64 group">
                                <input x-model="search" type="text" placeholder="Cari kelas..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm font-bold transition-all shadow-sm group-hover:border-blue-200">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-500 transition-colors">
                                    <i class="ph-bold ph-magnifying-glass"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="overflow-x-auto flex-1 custom-scrollbar">
                            <table class="min-w-full text-left text-sm text-slate-600">
                                <thead class="bg-slate-50 text-xs font-bold text-slate-400 uppercase tracking-wider sticky top-0 z-10">
                                    <tr>
                                        <th class="px-6 py-5">Identitas Kelas</th>
                                        <th class="px-6 py-5">Wali Kelas</th>
                                        <th class="px-6 py-5 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @forelse ($classes as $class)
                                        <tr class="hover:bg-slate-50 transition-colors group"
                                            x-show="search === '' || '{{ strtolower($class->name) }}'.includes(search.toLowerCase()) || '{{ strtolower($class->homeroomTeacher->name ?? '') }}'.includes(search.toLowerCase())"
                                            x-transition.opacity>
                                            
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <div class="flex items-center gap-4">
                                                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200 text-slate-600 font-black flex items-center justify-center text-sm shadow-sm border border-slate-200 group-hover:from-blue-100 group-hover:to-blue-200 group-hover:text-blue-700 transition-all">
                                                        {{ substr($class->name, 0, 3) }}
                                                    </div>
                                                    <div>
                                                        <span class="block font-black text-slate-700 text-sm">{{ $class->name }}</span>
                                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">ID: {{ $class->id }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                @if($class->homeroomTeacher)
                                                    <div class="flex items-center gap-2">
                                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100 shadow-sm">
                                                            <i class="ph-bold ph-user-circle"></i>
                                                            {{ $class->homeroomTeacher->name }}
                                                        </span>
                                                    </div>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-amber-50 text-amber-600 border border-amber-100 shadow-sm">
                                                        <i class="ph-bold ph-warning-circle"></i> Belum diatur
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    {{-- Tombol Edit --}}
                                                    <a href="{{ route('classes.edit', $class->id) }}" class="w-9 h-9 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-300 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 transition-all shadow-sm" title="Edit Kelas">
                                                        <i class="ph-bold ph-pencil-simple text-lg"></i>
                                                    </a>
                                                    
                                                    {{-- Tombol Hapus (Fix: Menggunakan type="button" dan onclick) --}}
                                                    <form action="{{ route('classes.destroy', $class->id) }}" 
                                                          method="POST" 
                                                          id="delete-form-{{ $class->id }}">
                                                        @csrf 
                                                        @method('DELETE')
                                                        
                                                        <button type="button" 
                                                                onclick="confirmDelete('{{ $class->id }}', '{{ $class->name }}')"
                                                                class="w-9 h-9 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-300 hover:text-rose-600 hover:border-rose-200 hover:bg-rose-50 transition-all shadow-sm" 
                                                                title="Hapus Kelas">
                                                            <i class="ph-bold ph-trash text-lg"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-20 text-center text-slate-400">
                                                <div class="flex flex-col items-center justify-center">
                                                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 text-slate-300 shadow-inner">
                                                        <i class="ph-duotone ph-chalkboard text-4xl"></i>
                                                    </div>
                                                    <p class="text-sm font-bold text-slate-500">Belum ada data kelas.</p>
                                                    <p class="text-xs text-slate-400 mt-1">Silakan tambahkan kelas baru di formulir sebelah kiri.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse

                                    {{-- State Pencarian Kosong --}}
                                    <tr x-show="search !== '' && $el.parentNode.querySelectorAll('tr[x-show]:not([style*=\'display: none\'])').length === 0" style="display: none;">
                                        <td colspan="3" class="px-6 py-12 text-center text-slate-400">
                                            <div class="inline-block px-4 py-2 bg-slate-50 rounded-xl border border-slate-100">
                                                Tidak ditemukan kelas dengan kata kunci "<span x-text="search" class="font-black text-slate-600"></span>"
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- SweetAlert2 Library --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        function confirmDelete(id, name) {
            Swal.fire({
                title: 'Hapus Kelas?',
                text: `Yakin ingin menghapus kelas ${name}? Data siswa di dalamnya mungkin akan terpengaruh.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-[2rem] font-sans border-0 shadow-2xl',
                    confirmButton: 'bg-rose-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-rose-700 transition-colors mx-2 shadow-lg shadow-rose-900/20',
                    cancelButton: 'bg-slate-100 text-slate-600 px-6 py-3 rounded-xl font-bold hover:bg-slate-200 transition-colors mx-2'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    // Cari form berdasarkan ID unik dan submit
                    const form = document.getElementById('delete-form-' + id);
                    if (form) {
                        form.submit();
                    } else {
                        console.error('Form not found for ID:', id);
                    }
                }
            });
        }
    </script>
</x-app-layout>