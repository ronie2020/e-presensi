<x-app-layout>
    {{-- 
        X-DATA CONTEXT:
        State 'search' untuk fitur pencarian real-time client-side.
    --}}
    <div x-data="{ search: '' }" class="py-8 sm:py-10 font-sans text-elevate-dark relative overflow-hidden">
        
        {{-- Efek Latar Belakang Halus --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        {{-- HERO SECTION (ELEVATED THEME) --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10 relative z-10">
            <div class="relative rounded-[2.5rem] bg-elevate-gradient-main p-8 sm:p-10 text-elevate-dark shadow-xl shadow-elevate-accent/20 overflow-hidden border border-white/60 group">
                
                {{-- Background Decorations --}}
                <div class="absolute inset-0 opacity-[0.03] bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none mix-blend-overlay"></div>
                <div class="absolute -top-24 -right-24 w-80 h-80 bg-white/40 rounded-full blur-3xl pointer-events-none group-hover:bg-white/60 transition-all duration-700"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                    
                    {{-- Text Content --}}
                    <div class="max-w-2xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-elevate-soft border border-elevate-accent/30 text-elevate-primary text-[10px] font-bold uppercase tracking-widest mb-4 backdrop-blur-sm shadow-sm">
                            <i class="ph-fill ph-chalkboard-teacher"></i> Data Akademik
                        </div>
                        <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-3 flex items-center gap-3 text-elevate-dark leading-tight">
                            Manajemen Kelas
                        </h1>
                        <p class="text-elevate-dark/80 text-sm md:text-base font-semibold leading-relaxed max-w-lg">
                            Kelola daftar rombongan belajar (rombel) dan penugasan wali kelas.
                        </p>
                    </div>
                    
                    {{-- Stats Cards --}}
                    <div class="flex flex-row md:flex-col lg:flex-row gap-4 w-full md:w-auto">
                        <div class="bg-white/60 backdrop-blur-md px-6 py-5 rounded-2xl border border-white/80 flex-1 md:flex-none min-w-[140px] text-center md:text-left hover:bg-white transition-colors shadow-sm">
                            <div class="flex items-center justify-center md:justify-start gap-2 mb-1 text-elevate-primary">
                                <i class="ph-duotone ph-student text-lg"></i>
                                <span class="text-[10px] font-bold uppercase tracking-wider">Total Rombel</span>
                            </div>
                            <span class="block text-3xl font-black text-elevate-dark tracking-tight">{{ $classes->count() }}</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

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
                    <div class="bg-white p-8 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden sticky top-24 group/form hover:shadow-2xl hover:shadow-elevate-accent/10 transition-all duration-300">
                        {{-- Aksen Header --}}
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-elevate-primary to-elevate-accent"></div>
                        
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-12 h-12 bg-elevate-soft text-elevate-primary rounded-2xl flex items-center justify-center text-2xl shadow-sm border border-elevate-accent/20 group-hover/form:scale-110 transition-transform">
                                <i class="ph-duotone ph-plus-square"></i>
                            </div>
                            <h2 class="text-xl font-black text-elevate-dark">Tambah Kelas</h2>
                        </div>

                        <form action="{{ route('classes.store') }}" method="POST" class="space-y-5">
                            @csrf
                            
                            {{-- Input Nama Kelas --}}
                            <div>
                                <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Nama Kelas</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-elevate-primary transition-colors">
                                        <i class="ph-bold ph-chalkboard text-lg"></i>
                                    </div>
                                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: 7A" 
                                           class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 font-bold text-elevate-dark transition-all shadow-sm placeholder:font-medium placeholder:text-slate-400 @error('name') border-rose-300 bg-rose-50 @enderror">
                                </div>
                                @error('name')
                                    <p class="mt-1.5 ml-1 text-xs text-rose-500 font-bold flex items-center gap-1"><i class="ph-bold ph-warning"></i> {{ $message }}</p>
                                @enderror
                            </div>
                            
                            {{-- Dropdown Wali Kelas --}}
                            <div>
                                <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Wali Kelas (Opsional)</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-elevate-primary transition-colors">
                                        <i class="ph-bold ph-user-circle text-lg"></i>
                                    </div>
                                    <select name="homeroom_teacher_id" class="w-full pl-11 pr-10 py-3.5 rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 text-sm font-bold text-elevate-dark transition-all shadow-sm appearance-none cursor-pointer">
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

                            <button type="submit" class="w-full mt-6 py-4 px-6 bg-elevate-dark text-white font-bold rounded-2xl hover:bg-elevate-primary transition-all shadow-lg shadow-elevate-dark/30 flex items-center justify-center gap-2 transform active:scale-95 border border-transparent">
                                <i class="ph-bold ph-floppy-disk text-lg"></i>
                                Simpan Kelas
                            </button>
                        </form>
                    </div>
                </div>

                {{-- KOLOM 2: DAFTAR KELAS --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden min-h-[500px] flex flex-col relative">
                        {{-- Aksen Header --}}
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-elevate-primary to-elevate-accent"></div>

                        <div class="p-8 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-6">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-elevate-soft text-elevate-primary rounded-2xl flex items-center justify-center text-2xl shadow-sm border border-elevate-accent/20">
                                    <i class="ph-duotone ph-list-numbers"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-black text-elevate-dark">Daftar Rombel</h3>
                                    <p class="text-xs text-elevate-dark/60 font-bold uppercase tracking-wider mt-1">Total {{ $classes->count() }} Kelas</p>
                                </div>
                            </div>
                            
                            {{-- Search Box --}}
                            <div class="relative w-full sm:w-64 group">
                                <input x-model="search" type="text" placeholder="Cari kelas..." class="w-full pl-11 pr-4 py-3 rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 text-sm font-bold transition-all shadow-sm text-elevate-dark outline-none placeholder:font-medium">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-elevate-primary transition-colors">
                                    <i class="ph-bold ph-magnifying-glass"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="overflow-x-auto flex-1 custom-scrollbar">
                            <table class="min-w-full text-left text-sm text-elevate-dark">
                                <thead class="bg-elevate-soft/50 text-xs font-bold text-elevate-primary uppercase tracking-wider sticky top-0 z-10 border-b border-slate-100">
                                    <tr>
                                        <th class="px-8 py-5">Identitas Kelas</th>
                                        <th class="px-6 py-5">Wali Kelas</th>
                                        <th class="px-8 py-5 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @forelse ($classes as $class)
                                        <tr class="hover:bg-elevate-soft/30 transition-colors group"
                                            x-show="search === '' || '{{ strtolower($class->name) }}'.includes(search.toLowerCase()) || '{{ strtolower($class->homeroomTeacher->name ?? '') }}'.includes(search.toLowerCase())"
                                            x-transition.opacity>
                                            
                                            <td class="px-8 py-5 whitespace-nowrap">
                                                <div class="flex items-center gap-4">
                                                    <div class="w-12 h-12 rounded-2xl bg-elevate-soft text-elevate-primary font-black flex items-center justify-center text-sm shadow-sm border border-elevate-accent/30 group-hover:bg-elevate-primary group-hover:text-white transition-all">
                                                        {{ substr($class->name, 0, 3) }}
                                                    </div>
                                                    <div>
                                                        <span class="block font-black text-elevate-dark text-base">{{ $class->name }}</span>
                                                        <span class="text-[10px] font-bold text-elevate-dark/50 uppercase tracking-wide">ID: {{ $class->id }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                @if($class->homeroomTeacher)
                                                    <div class="flex items-center gap-2">
                                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100 shadow-sm">
                                                            <i class="ph-bold ph-user-circle text-sm"></i>
                                                            {{ $class->homeroomTeacher->name }}
                                                        </span>
                                                    </div>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-amber-50 text-amber-600 border border-amber-100 shadow-sm">
                                                        <i class="ph-bold ph-warning-circle text-sm"></i> Belum diatur
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-8 py-5 whitespace-nowrap text-right">
                                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity transform translate-x-2 group-hover:translate-x-0 duration-200">
                                                    {{-- Tombol Edit --}}
                                                    <a href="{{ route('classes.edit', $class->id) }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-elevate-primary hover:border-elevate-accent hover:bg-elevate-soft transition-all shadow-sm" title="Edit Kelas">
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
                                                                class="w-10 h-10 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-rose-600 hover:border-rose-200 hover:bg-rose-50 transition-all shadow-sm" 
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
                                                    <div class="w-20 h-20 bg-elevate-soft rounded-full flex items-center justify-center mb-4 text-elevate-primary shadow-inner border border-elevate-accent/20">
                                                        <i class="ph-duotone ph-chalkboard text-4xl"></i>
                                                    </div>
                                                    <p class="text-base font-black text-elevate-dark mb-1">Belum ada data kelas.</p>
                                                    <p class="text-sm font-medium text-elevate-dark/60 mt-1">Silakan tambahkan kelas baru di formulir sebelah kiri.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse

                                    {{-- State Pencarian Kosong --}}
                                    <tr x-show="search !== '' && $el.parentNode.querySelectorAll('tr[x-show]:not([style*=\'display: none\'])').length === 0" style="display: none;">
                                        <td colspan="3" class="px-8 py-12 text-center text-elevate-dark/50">
                                            <div class="inline-block px-4 py-2 bg-elevate-soft rounded-xl border border-slate-200 shadow-sm font-medium text-sm">
                                                Tidak ditemukan kelas dengan kata kunci "<span x-text="search" class="font-black text-elevate-dark"></span>"
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
                    confirmButton: 'bg-rose-600 text-white px-6 py-3.5 rounded-xl font-bold hover:bg-rose-700 transition-colors mx-2 shadow-lg shadow-rose-600/30',
                    cancelButton: 'bg-slate-100 text-slate-600 px-6 py-3.5 rounded-xl font-bold hover:bg-slate-200 transition-colors mx-2'
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