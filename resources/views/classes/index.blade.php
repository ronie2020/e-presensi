<x-app-layout>
    {{-- 
        X-DATA CONTEXT:
        State 'search' untuk fitur pencarian real-time client-side.
    --}}
    <div x-data="{ search: '' }" class="py-8 sm:py-10 font-sans text-slate-100 bg-[#020b18] min-h-screen relative overflow-hidden">
        
        {{-- Efek Latar Belakang Halus --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-gradient-to-b from-[#0d52a1]/20 via-[#031d3d]/10 to-transparent opacity-30 pointer-events-none -z-10 blur-3xl"></div>

        {{-- HERO SECTION (UNIFIED ELEVATE DARK GLASS - AQUALIFE & E-LEARNING) --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10 relative z-10">
            <div class="animate-enter relative rounded-[2.5rem] bg-gradient-to-br from-[#0d52a1]/85 via-[#031d3d]/90 to-[#021124]/95 p-8 sm:p-10 text-white shadow-2xl shadow-[#0d52a1]/25 border border-white/20 backdrop-blur-2xl overflow-hidden group">
                {{-- Specular Top Rim Light --}}
                <div class="absolute inset-0 rounded-[2.5rem] pointer-events-none border-t border-l border-white/30"></div>
                <div class="absolute inset-0 opacity-[0.03] bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none mix-blend-overlay"></div>

                {{-- Ambient Radiant Glow Orbs --}}
                <div class="absolute -top-16 -right-16 w-80 h-80 bg-[#56bbf1]/20 rounded-full blur-[100px] pointer-events-none"></div>
                <div class="absolute -bottom-16 -left-16 w-72 h-72 bg-[#0d52a1]/30 rounded-full blur-[90px] pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                    
                    {{-- KIRI: Judul, Deskripsi, Chips --}}
                    <div class="space-y-4 max-w-2xl">
                        <div class="flex flex-wrap items-center gap-2">
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white border border-white/15 text-xs font-bold transition-all shadow-sm">
                                <i class="ph-bold ph-arrow-left text-sky-400"></i>
                                <span>Dashboard</span>
                            </a>
                            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 border border-white/20 text-sky-300 text-xs font-bold uppercase tracking-wider backdrop-blur-md shadow-sm">
                                <i class="ph-fill ph-chalkboard-teacher text-sky-400"></i> Data Akademik & Rombel
                            </div>
                        </div>

                        <h1 class="text-3xl sm:text-4xl md:text-5xl font-black tracking-tight leading-snug sm:leading-snug md:leading-normal text-white">
                            <span class="block text-slate-100">Struktur Rombel &</span>
                            <span class="block mt-1 sm:mt-1.5 text-transparent bg-clip-text bg-gradient-to-r from-[#56bbf1] via-sky-200 to-[#38bdf8]">Manajemen Kelas</span>
                        </h1>
                        <p class="text-slate-300 text-sm sm:text-base font-medium leading-relaxed max-w-lg">
                            Kelola daftar rombongan belajar (rombel), penugasan wali kelas, serta distribusi kapasitas siswa per tingkat kelas secara terpadu.
                        </p>

                        {{-- Feature Highlight Chips --}}
                        <div class="flex flex-wrap items-center gap-2.5 pt-1">
                            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-white/5 border border-white/10 text-slate-200 text-xs font-semibold backdrop-blur-sm shadow-sm">
                                <i class="ph-bold ph-check text-sky-400"></i> Wali Kelas Terverifikasi
                            </div>
                            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-white/5 border border-white/10 text-slate-200 text-xs font-semibold backdrop-blur-sm shadow-sm">
                                <i class="ph-bold ph-check text-sky-400"></i> Manajemen Kuota Siswa
                            </div>
                            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-white/5 border border-white/10 text-slate-200 text-xs font-semibold backdrop-blur-sm shadow-sm">
                                <i class="ph-bold ph-check text-sky-400"></i> Terhubung Presensi
                            </div>
                        </div>
                    </div>
                    
                    {{-- KANAN: Luminous Circular Showcase --}}
                    <div class="relative flex items-center justify-center shrink-0 w-full md:w-auto mt-4 md:mt-0">
                        <div class="absolute w-52 h-52 rounded-full border border-[#56bbf1]/30 animate-pulse pointer-events-none"></div>
                        <div class="absolute w-60 h-60 rounded-full border border-sky-400/15 pointer-events-none"></div>

                        {{-- Core Glowing Card --}}
                        <div class="relative z-10 w-44 h-44 rounded-full bg-gradient-to-br from-[#0d52a1]/80 via-[#031d3d]/90 to-[#021124] p-1 border-2 border-[#56bbf1]/50 shadow-2xl shadow-sky-500/30 backdrop-blur-xl flex flex-col items-center justify-center text-center">
                            <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-sky-400 to-[#0d52a1] flex items-center justify-center text-white shadow-lg shadow-sky-400/40 mb-1 border border-white/20">
                                <i class="ph-bold ph-chalkboard text-xl"></i>
                            </div>
                            <span class="text-3xl font-black tracking-tight text-white leading-none">
                                {{ $classes->count() }}
                            </span>
                            <span class="text-[10px] font-bold text-sky-300 uppercase tracking-widest mt-1">Total Rombel</span>
                            <div class="mt-1.5 inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 text-[10px] font-bold">
                                <i class="ph-fill ph-check-circle"></i> Aktif
                            </div>
                        </div>

                        {{-- Floating Status Bubbles --}}
                        <div class="absolute -top-2 -right-2 z-20 flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#031d3d]/90 border border-white/20 text-white text-[11px] font-bold shadow-xl backdrop-blur-md">
                            <i class="ph-bold ph-users-three text-sky-400"></i>
                            <span>Semua Tingkat</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            {{-- Pesan Flash --}}
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition class="mb-8 p-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 rounded-[2rem] flex items-center justify-between shadow-xl backdrop-blur-xl">
                    <div class="flex items-center gap-3 px-2">
                        <div class="p-2 bg-emerald-500/20 rounded-full text-emerald-400 border border-emerald-500/30">
                            <i class="ph-bold ph-check-circle text-xl"></i>
                        </div>
                        <span class="font-bold text-sm text-slate-100">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-400 hover:text-emerald-300 p-2 rounded-xl hover:bg-emerald-500/20 transition"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif

            @if (session('error'))
                <div x-data="{ show: true }" x-show="show" x-transition class="mb-8 p-4 bg-rose-500/10 border border-rose-500/30 text-rose-300 rounded-[2rem] flex items-center justify-between shadow-xl backdrop-blur-xl">
                    <div class="flex items-center gap-3 px-2">
                        <div class="p-2 bg-rose-500/20 rounded-full text-rose-400 border border-rose-500/30">
                            <i class="ph-bold ph-warning-circle text-xl"></i>
                        </div>
                        <span class="font-bold text-sm text-slate-100">{{ session('error') }}</span>
                    </div>
                    <button @click="show = false" class="text-rose-400 hover:text-rose-300 p-2 rounded-xl hover:bg-rose-500/20 transition"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

                {{-- KOLOM 1: FORM TAMBAH KELAS --}}
                <div class="lg:col-span-1">
                    <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] p-8 rounded-[2.5rem] shadow-2xl border border-white/10 backdrop-blur-xl relative overflow-hidden sticky top-24 group/form transition-all duration-300">
                        {{-- Aksen Header --}}
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-[#0d52a1] to-[#56bbf1]"></div>
                        
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-12 h-12 bg-sky-500/20 text-sky-400 rounded-2xl flex items-center justify-center text-2xl shadow-sm border border-sky-400/30 group-hover/form:scale-110 transition-transform">
                                <i class="ph-duotone ph-plus-square"></i>
                            </div>
                            <h2 class="text-xl font-black text-white">Tambah Kelas</h2>
                        </div>

                        <form action="{{ route('classes.store') }}" method="POST" class="space-y-5">
                            @csrf
                            
                            {{-- Input Nama Kelas --}}
                            <div>
                                <label class="block text-xs font-bold text-sky-400 uppercase tracking-wider mb-2 ml-1">Nama Kelas</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-sky-400 transition-colors">
                                        <i class="ph-bold ph-chalkboard text-lg"></i>
                                    </div>
                                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: 7A" 
                                           class="w-full pl-11 pr-4 py-3.5 rounded-2xl border border-white/10 bg-slate-900/80 focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 font-bold text-white transition-all shadow-sm placeholder:font-medium placeholder:text-slate-500 [color-scheme:dark] @error('name') border-rose-500 bg-rose-500/10 @enderror">
                                </div>
                                @error('name')
                                    <p class="mt-1.5 ml-1 text-xs text-rose-400 font-bold flex items-center gap-1"><i class="ph-bold ph-warning"></i> {{ $message }}</p>
                                @enderror
                            </div>
                            
                            {{-- Dropdown Wali Kelas --}}
                            <div>
                                <label class="block text-xs font-bold text-sky-400 uppercase tracking-wider mb-2 ml-1">Wali Kelas (Opsional)</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-sky-400 transition-colors">
                                        <i class="ph-bold ph-user-circle text-lg"></i>
                                    </div>
                                    <select name="homeroom_teacher_id" class="w-full pl-11 pr-10 py-3.5 rounded-2xl border border-white/10 bg-slate-900/80 focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 text-sm font-bold text-white transition-all shadow-sm appearance-none cursor-pointer [color-scheme:dark]">
                                        <option value="" class="bg-slate-900 text-white">-- Pilih Guru --</option>
                                        @foreach ($teachers as $teacher)
                                            <option value="{{ $teacher->id }}" class="bg-slate-900 text-white">{{ $teacher->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                        <i class="ph-bold ph-caret-down"></i>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="w-full mt-6 py-4 px-6 bg-gradient-to-r from-[#0d52a1] to-[#56bbf1] hover:from-sky-600 hover:to-sky-400 text-white font-bold rounded-2xl transition-all shadow-lg shadow-sky-600/25 flex items-center justify-center gap-2 transform active:scale-95 border border-white/20">
                                <i class="ph-bold ph-floppy-disk text-lg"></i>
                                Simpan Kelas
                            </button>
                        </form>
                    </div>
                </div>

                {{-- KOLOM 2: DAFTAR KELAS --}}
                <div class="lg:col-span-2">
                    <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] shadow-2xl border border-white/10 backdrop-blur-xl overflow-hidden min-h-[500px] flex flex-col relative">
                        {{-- Aksen Header --}}
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-[#0d52a1] to-[#56bbf1]"></div>

                        <div class="p-8 border-b border-white/10 bg-white/5 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-6">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-sky-500/20 text-sky-400 rounded-2xl flex items-center justify-center text-2xl shadow-sm border border-sky-400/30">
                                    <i class="ph-duotone ph-list-numbers"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-black text-white">Daftar Rombel</h3>
                                    <p class="text-xs text-sky-300 font-bold uppercase tracking-wider mt-1">Total {{ $classes->count() }} Kelas</p>
                                </div>
                            </div>
                            
                            {{-- Search Box --}}
                            <div class="relative w-full sm:w-64 group">
                                <input x-model="search" type="text" placeholder="Cari kelas..." class="w-full pl-11 pr-4 py-3 rounded-2xl border border-white/10 bg-slate-900/80 focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 text-sm font-bold transition-all shadow-sm text-white outline-none placeholder:font-medium placeholder:text-slate-500 [color-scheme:dark]">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-sky-400 transition-colors">
                                    <i class="ph-bold ph-magnifying-glass"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="overflow-x-auto flex-1 custom-scrollbar">
                            <table class="min-w-full text-left text-sm text-slate-200">
                                <thead class="bg-slate-900/80 text-xs font-bold text-sky-400 uppercase tracking-wider sticky top-0 z-10 border-b border-white/10">
                                    <tr>
                                        <th class="px-8 py-5">Identitas Kelas</th>
                                        <th class="px-6 py-5">Wali Kelas</th>
                                        <th class="px-8 py-5 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5">
                                    @forelse ($classes as $class)
                                        <tr class="hover:bg-white/5 transition-colors group"
                                            x-show="search === '' || '{{ strtolower($class->name) }}'.includes(search.toLowerCase()) || '{{ strtolower($class->homeroomTeacher->name ?? '') }}'.includes(search.toLowerCase())"
                                            x-transition.opacity>
                                            
                                            <td class="px-8 py-5 whitespace-nowrap">
                                                <div class="flex items-center gap-4">
                                                    <div class="w-12 h-12 rounded-2xl bg-sky-500/10 text-sky-400 font-black flex items-center justify-center text-sm shadow-sm border border-sky-400/20 group-hover:bg-sky-500 group-hover:text-white transition-all">
                                                        {{ substr($class->name, 0, 3) }}
                                                    </div>
                                                    <div>
                                                        <span class="block font-black text-white text-base">{{ $class->name }}</span>
                                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">ID: {{ $class->id }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                @if($class->homeroomTeacher)
                                                    <div class="flex items-center gap-2">
                                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 shadow-sm">
                                                            <i class="ph-bold ph-user-circle text-sm"></i>
                                                            {{ $class->homeroomTeacher->name }}
                                                        </span>
                                                    </div>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30 shadow-sm">
                                                        <i class="ph-bold ph-warning-circle text-sm"></i> Belum diatur
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-8 py-5 whitespace-nowrap text-right">
                                                <div class="flex items-center justify-end gap-2 opacity-80 group-hover:opacity-100 transition-opacity transform translate-x-2 group-hover:translate-x-0 duration-200">
                                                    {{-- Tombol Edit --}}
                                                    <a href="{{ route('classes.edit', $class->id) }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 border border-white/10 text-slate-300 hover:text-white hover:border-sky-400/40 hover:bg-sky-500/20 transition-all shadow-sm" title="Edit Kelas">
                                                        <i class="ph-bold ph-pencil-simple text-lg"></i>
                                                    </a>
                                                    
                                                    {{-- Tombol Hapus --}}
                                                    <form action="{{ route('classes.destroy', $class->id) }}" 
                                                          method="POST" 
                                                          id="delete-form-{{ $class->id }}" class="shrink-0 block">
                                                        @csrf 
                                                        @method('DELETE')
                                                        
                                                        <button type="button" 
                                                                onclick="confirmDelete('{{ $class->id }}', '{{ $class->name }}')"
                                                                class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 border border-white/10 text-slate-300 hover:text-white hover:border-rose-500/40 hover:bg-rose-500/20 transition-all shadow-sm" 
                                                                title="Hapus Kelas">
                                                            <i class="ph-bold ph-trash text-lg"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-8 py-20 text-center">
                                                <div class="flex flex-col items-center justify-center">
                                                    <div class="w-20 h-20 bg-sky-500/10 rounded-full flex items-center justify-center mb-4 text-sky-400 shadow-inner border border-sky-400/20">
                                                        <i class="ph-duotone ph-chalkboard text-4xl"></i>
                                                    </div>
                                                    <p class="text-base font-black text-white mb-1">Belum ada data kelas.</p>
                                                    <p class="text-sm font-medium text-slate-400 mt-1">Silakan tambahkan kelas baru di formulir sebelah kiri.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse

                                    {{-- State Pencarian Kosong --}}
                                    <tr x-show="search !== '' && $el.parentNode.querySelectorAll('tr[x-show]:not([style*=\'display: none\'])').length === 0" style="display: none;">
                                        <td colspan="3" class="px-8 py-12 text-center text-slate-400">
                                            <div class="inline-block px-4 py-2 bg-white/5 rounded-xl border border-white/10 shadow-sm font-medium text-sm">
                                                Tidak ditemukan kelas dengan kata kunci "<span x-text="search" class="font-black text-white"></span>"
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
                background: '#021124',
                color: '#fff',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#1e293b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-[2rem] font-sans border border-white/10 bg-[#021124] text-white shadow-2xl',
                    confirmButton: 'bg-rose-600 text-white px-6 py-3.5 rounded-xl font-bold hover:bg-rose-700 transition-colors mx-2 shadow-lg shadow-rose-600/30',
                    cancelButton: 'bg-slate-800 text-slate-300 px-6 py-3.5 rounded-xl font-bold hover:bg-slate-700 transition-colors mx-2 border border-white/10'
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