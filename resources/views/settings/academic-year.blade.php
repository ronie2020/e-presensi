<x-app-layout>
    <div class="py-8 sm:py-10 font-sans text-elevate-dark relative overflow-hidden">
        {{-- Efek Latar Belakang Halus --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        {{-- HERO SECTION --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10 relative z-10">
            <div class="relative rounded-[2.5rem] bg-elevate-gradient-main p-8 sm:p-10 text-elevate-dark shadow-xl shadow-elevate-accent/20 overflow-hidden border border-white/60 group">
                
                {{-- Background Decorations --}}
                <div class="absolute inset-0 opacity-[0.03] bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none mix-blend-overlay"></div>
                <div class="absolute -top-24 -right-24 w-80 h-80 bg-white/40 rounded-full blur-3xl pointer-events-none group-hover:bg-white/60 transition-all duration-700"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                    
                    {{-- Text Content --}}
                    <div class="max-w-2xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-elevate-soft border border-elevate-accent/30 text-elevate-primary text-[10px] font-bold uppercase tracking-widest mb-4 backdrop-blur-sm shadow-sm">
                            <i class="ph-fill ph-gear"></i> Konfigurasi Sistem
                        </div>
                        <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-3 flex items-center gap-3 text-elevate-dark leading-tight">
                            Tahun Ajaran
                        </h1>
                        <p class="text-elevate-dark/80 text-sm md:text-base font-semibold leading-relaxed max-w-lg">
                            Kelola periode akademik sekolah. Aktifkan semester berjalan untuk memulai kegiatan belajar mengajar dan pelaporan nilai.
                        </p>
                    </div>
                    
                    {{-- Stats Cards --}}
                    <div class="flex gap-4">
                        <div class="bg-white/60 backdrop-blur-md px-6 py-5 rounded-2xl border border-white/80 min-w-[140px] text-center md:text-left hover:bg-white transition-colors shadow-sm">
                            <div class="flex items-center justify-center md:justify-start gap-2 mb-1 text-elevate-primary">
                                <i class="ph-duotone ph-calendar-check text-lg"></i>
                                <span class="text-[10px] font-bold uppercase tracking-wider">Total Periode</span>
                            </div>
                            <span class="block text-3xl font-black text-elevate-dark tracking-tight">{{ $years->count() }}</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- Alert Sukses --}}
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition class="mb-8 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-emerald-100 rounded-full text-emerald-600">
                            <i class="ph-bold ph-check-circle text-xl"></i>
                        </div>
                        <span class="font-bold text-sm">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 p-1 rounded-md hover:bg-emerald-100 transition"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                {{-- KOLOM KIRI: FORM TAMBAH --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden sticky top-24 relative group hover:shadow-2xl hover:shadow-elevate-accent/10 transition-all duration-300">
                        
                        {{-- Card Header --}}
                        <div class="bg-elevate-gradient-card p-8 text-elevate-dark relative overflow-hidden border-b border-slate-100">
                            <div class="absolute -right-6 -top-6 text-elevate-primary/5 text-9xl pointer-events-none">
                                <i class="ph-fill ph-plus-circle"></i>
                            </div>
                            <h3 class="text-xl font-black relative z-10">Periode Baru</h3>
                            <p class="text-elevate-dark/60 text-sm font-medium relative z-10 mt-1">Tambahkan tahun ajaran.</p>
                        </div>

                        <div class="p-8 relative z-10">
                            <form action="{{ route('settings.academic.store') }}" method="POST" class="space-y-6">
                                @csrf
                                <div>
                                    <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Tahun Ajaran</label>
                                    <div class="relative group">
                                        <i class="ph-bold ph-calendar absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-elevate-primary transition-colors"></i>
                                        <input type="text" name="name" placeholder="Contoh: 2025/2026" required 
                                               class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 text-sm font-bold text-elevate-dark transition-all shadow-sm placeholder:font-medium placeholder:text-slate-400">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Semester</label>
                                    <div class="relative group">
                                        <i class="ph-bold ph-clock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-elevate-primary transition-colors"></i>
                                        <select name="semester" class="w-full pl-11 pr-10 py-3.5 rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 text-sm font-bold text-elevate-dark transition-all shadow-sm appearance-none cursor-pointer">
                                            <option value="Ganjil">Ganjil (1)</option>
                                            <option value="Genap">Genap (2)</option>
                                        </select>
                                        <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                    </div>
                                </div>
                                <button type="submit" class="w-full py-3.5 px-6 bg-elevate-dark text-white font-bold rounded-2xl hover:bg-elevate-primary transition-all shadow-lg shadow-elevate-dark/30 flex items-center justify-center gap-2 transform active:scale-95 border border-transparent">
                                    <i class="ph-bold ph-floppy-disk text-lg"></i>
                                    Simpan Data
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: DAFTAR TAHUN AJARAN --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden flex flex-col h-full min-h-[500px]">
                        <div class="p-8 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                            <h2 class="text-lg font-black text-elevate-dark flex items-center gap-2">
                                <i class="ph-fill ph-list-dashes text-elevate-primary"></i> Riwayat Periode
                            </h2>
                            <span class="bg-white border border-slate-200 text-[10px] font-black px-3 py-1.5 rounded-xl text-elevate-primary shadow-sm">
                                {{ $years->count() }} Data
                            </span>
                        </div>
                        <div class="overflow-x-auto flex-1 custom-scrollbar">
                            <table class="w-full text-left text-sm text-elevate-dark">
                                <thead class="bg-elevate-soft/50 text-xs font-bold text-elevate-primary uppercase tracking-wider border-b border-slate-100">
                                    <tr>
                                        <th class="px-8 py-5">Tahun & Semester</th>
                                        <th class="px-6 py-5">Status</th>
                                        <th class="px-8 py-5 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @foreach($years as $year)
                                        <tr class="group hover:bg-elevate-soft/30 transition-colors {{ $year->is_active ? 'bg-elevate-soft/50' : '' }}">
                                            <td class="px-8 py-5">
                                                <div class="flex items-center gap-4">
                                                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-xl shadow-sm {{ $year->is_active ? 'bg-white text-elevate-primary border border-elevate-accent/30' : 'bg-slate-50 text-slate-400 border border-slate-200' }}">
                                                        <i class="ph-duotone ph-calendar-blank"></i>
                                                    </div>
                                                    <div>
                                                        <p class="font-black text-elevate-dark text-base mb-0.5">{{ $year->name }}</p>
                                                        <p class="text-xs font-bold text-elevate-dark/50 uppercase tracking-wide">Semester {{ $year->semester }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-5">
                                                @if($year->is_active)
                                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-600 text-xs font-black border border-emerald-200 shadow-sm">
                                                        <span class="relative flex h-2 w-2">
                                                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                                          <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                                        </span>
                                                        Sedang Aktif
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-50 text-slate-500 text-xs font-bold border border-slate-200">
                                                        <i class="ph-bold ph-prohibit"></i> Tidak Aktif
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-8 py-5 text-right">
                                                <div class="flex justify-end gap-2">
                                                    @if(!$year->is_active)
                                                        <form action="{{ route('settings.academic.activate', $year->id) }}" method="POST">
                                                            @csrf @method('PATCH')
                                                            <button type="submit" class="px-4 py-2 bg-white border border-elevate-accent/40 text-elevate-primary rounded-xl text-xs font-bold hover:bg-elevate-primary hover:text-white transition-all shadow-sm flex items-center gap-2 group/btn">
                                                                <i class="ph-bold ph-power"></i> Aktifkan
                                                            </button>
                                                        </form>
                                                        
                                                        <form action="{{ route('settings.academic.destroy', $year->id) }}" 
                                                              method="POST" 
                                                              id="delete-form-{{ $year->id }}">
                                                            @csrf @method('DELETE')
                                                            
                                                            <button type="button" 
                                                                    onclick="confirmDelete('{{ $year->id }}', '{{ $year->name }} (Sem. {{ $year->semester }})')"
                                                                    class="w-9 h-9 flex items-center justify-center bg-white border border-slate-200 text-slate-400 hover:text-rose-500 hover:border-rose-200 hover:bg-rose-50 rounded-xl transition-all shadow-sm" title="Hapus">
                                                                <i class="ph-bold ph-trash text-lg"></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <span class="text-xs font-bold text-elevate-primary/50 italic flex items-center justify-end gap-1.5 px-3 py-2">
                                                            <i class="ph-fill ph-lock-key"></i> Terkunci
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
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
                title: 'Hapus Periode?',
                text: `Yakin ingin menghapus Tahun Ajaran ${name}? Data kelas dan nilai terkait mungkin akan terpengaruh.`,
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
                    const form = document.getElementById('delete-form-' + id);
                    if (form) form.submit();
                }
            });
        }
    </script>
</x-app-layout>