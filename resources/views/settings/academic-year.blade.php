<x-app-layout>
    <div class="py-8 sm:py-10 font-sans min-h-screen text-slate-100 bg-[#020b18] relative overflow-hidden">
        {{-- Efek Latar Belakang Halus --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-gradient-to-b from-sky-600/10 via-blue-600/5 to-transparent pointer-events-none -z-10 blur-3xl"></div>

        {{-- HERO SECTION --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 sm:mb-10 relative z-10">
            <x-hero-section
                badge="KONFIGURASI SISTEM"
                badgeIcon="ph-fill ph-gear"
                showcaseIcon="ph-duotone ph-calendar"
                showcaseTitle="Tahun Ajaran"
                showcaseSubtitle="Kalender Akademik">
                <x-slot:title>
                    <span class="block text-slate-100">Pengaturan Periode</span>
                    <span class="block mt-1 sm:mt-1.5 text-transparent bg-clip-text bg-gradient-to-r from-[#56bbf1] via-sky-200 to-[#38bdf8]">
                        Tahun Ajaran & Semester
                    </span>
                </x-slot:title>
                <x-slot:description>
                    Kelola periode akademik sekolah. Aktifkan semester berjalan untuk memulai kegiatan belajar mengajar dan pelaporan nilai.
                </x-slot:description>
                <x-slot:chips>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-900/80 border border-white/10 text-slate-300 text-xs font-semibold">
                        <i class="ph-bold ph-calendar text-sky-400"></i> Ganjil & Genap
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-900/80 border border-white/10 text-slate-300 text-xs font-semibold">
                        <i class="ph-bold ph-toggle-right text-emerald-400"></i> Status Aktif
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-900/80 border border-white/10 text-slate-300 text-xs font-semibold">
                        <i class="ph-bold ph-lock-key text-cyan-400"></i> Kunci Riwayat Nilai
                    </span>
                </x-slot:chips>
                <x-slot:showcaseStats>
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-2xl bg-slate-900/80 border border-white/10 backdrop-blur-md">
                        <i class="ph-fill ph-calendar-blank text-sky-400 text-sm"></i>
                        <span class="text-xs font-bold text-slate-300">Total:</span>
                        <span class="text-sm font-black text-white font-mono">{{ $years->count() }}</span>
                        <span class="text-xs text-slate-400">Periode</span>
                    </div>
                </x-slot:showcaseStats>
            </x-hero-section>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- Alert Sukses --}}
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition class="mb-8 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 rounded-2xl flex items-center justify-between shadow-sm backdrop-blur-md">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-emerald-500/20 rounded-full text-emerald-400">
                            <i class="ph-bold ph-check-circle text-xl"></i>
                        </div>
                        <span class="font-bold text-sm">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-400 hover:text-emerald-200 p-1 rounded-md transition"><i class="ph-bold ph-x text-lg"></i></button>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                {{-- KOLOM KIRI: FORM TAMBAH --}}
                <div class="lg:col-span-1">
                    <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] shadow-2xl border border-white/10 overflow-hidden sticky top-24 relative group backdrop-blur-xl">
                        
                        {{-- Card Header --}}
                        <div class="p-8 relative overflow-hidden border-b border-white/10 bg-slate-900/40">
                            <div class="absolute -right-6 -top-6 text-sky-400/5 text-9xl pointer-events-none">
                                <i class="ph-fill ph-plus-circle"></i>
                            </div>
                            <h3 class="text-xl font-black text-white relative z-10 flex items-center gap-2">
                                <i class="ph-bold ph-plus-circle text-sky-400"></i> Periode Baru
                            </h3>
                            <p class="text-slate-400 text-sm font-medium relative z-10 mt-1">Tambahkan tahun ajaran.</p>
                        </div>

                        <div class="p-8 relative z-10">
                            <form action="{{ route('settings.academic.store') }}" method="POST" class="space-y-6">
                                @csrf
                                <div>
                                    <label class="block text-xs font-bold text-sky-400 uppercase tracking-wider mb-2 ml-1">Tahun Ajaran</label>
                                    <div class="relative group">
                                        <i class="ph-bold ph-calendar absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-sky-400 transition-colors"></i>
                                        <input type="text" name="name" placeholder="Contoh: 2025/2026" required 
                                               class="w-full pl-11 pr-4 py-3.5 rounded-2xl border border-white/10 bg-slate-900/80 text-white placeholder-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 text-sm font-bold transition-all shadow-sm">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-sky-400 uppercase tracking-wider mb-2 ml-1">Semester</label>
                                    <div class="relative group">
                                        <i class="ph-bold ph-clock absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-sky-400 transition-colors"></i>
                                        <select name="semester" class="w-full pl-11 pr-10 py-3.5 rounded-2xl border border-white/10 bg-slate-900/80 text-white focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 text-sm font-bold transition-all shadow-sm appearance-none cursor-pointer [color-scheme:dark]">
                                            <option value="Ganjil" class="bg-slate-900 text-white">Ganjil (1)</option>
                                            <option value="Genap" class="bg-slate-900 text-white">Genap (2)</option>
                                        </select>
                                        <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 pointer-events-none"></i>
                                    </div>
                                </div>
                                <button type="submit" class="w-full py-3.5 px-6 bg-gradient-to-r from-sky-500 to-blue-600 text-white font-bold rounded-2xl hover:from-sky-400 hover:to-blue-500 transition-all shadow-lg shadow-sky-500/25 flex items-center justify-center gap-2 transform active:scale-95 border border-transparent">
                                    <i class="ph-bold ph-floppy-disk text-lg"></i>
                                    Simpan Data
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: DAFTAR TAHUN AJARAN --}}
                <div class="lg:col-span-2">
                    <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] shadow-2xl border border-white/10 overflow-hidden flex flex-col h-full min-h-[500px] backdrop-blur-xl">
                        <div class="p-8 border-b border-white/10 bg-slate-900/60 flex justify-between items-center">
                            <h2 class="text-lg font-black text-white flex items-center gap-2">
                                <i class="ph-fill ph-list-dashes text-sky-400"></i> Riwayat Periode
                            </h2>
                            <span class="bg-sky-500/10 border border-sky-500/20 text-xs font-black px-3 py-1.5 rounded-xl text-sky-300 shadow-sm">
                                {{ $years->count() }} Data
                            </span>
                        </div>
                        <div class="overflow-x-auto flex-1 custom-scrollbar">
                            <table class="w-full text-left text-sm text-slate-200">
                                <thead class="bg-slate-900/80 text-xs font-bold text-sky-400 uppercase tracking-wider border-b border-white/10">
                                    <tr>
                                        <th class="px-8 py-5">Tahun & Semester</th>
                                        <th class="px-6 py-5">Status</th>
                                        <th class="px-8 py-5 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5">
                                    @foreach($years as $year)
                                        <tr class="group hover:bg-white/[0.02] transition-colors {{ $year->is_active ? 'bg-sky-500/10' : '' }}">
                                            <td class="px-8 py-5">
                                                <div class="flex items-center gap-4">
                                                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-xl shadow-sm {{ $year->is_active ? 'bg-sky-500/20 text-sky-300 border border-sky-500/30' : 'bg-slate-900/80 text-slate-400 border border-white/10' }}">
                                                        <i class="ph-duotone ph-calendar-blank"></i>
                                                    </div>
                                                    <div>
                                                        <p class="font-black text-white text-base mb-0.5">{{ $year->name }}</p>
                                                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Semester {{ $year->semester }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-5">
                                                @if($year->is_active)
                                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-emerald-500/10 text-emerald-400 text-xs font-black border border-emerald-500/20 shadow-sm">
                                                        <span class="relative flex h-2 w-2">
                                                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                                          <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                                        </span>
                                                        Sedang Aktif
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-900/80 text-slate-400 text-xs font-bold border border-white/10">
                                                        <i class="ph-bold ph-prohibit"></i> Tidak Aktif
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-8 py-5 text-right">
                                                <div class="flex justify-end gap-2">
                                                    @if(!$year->is_active)
                                                        <form action="{{ route('settings.academic.activate', $year->id) }}" method="POST">
                                                            @csrf @method('PATCH')
                                                            <button type="submit" class="px-4 py-2 bg-sky-500/10 border border-sky-500/30 text-sky-400 rounded-xl text-xs font-bold hover:bg-sky-500 hover:text-white transition-all shadow-sm flex items-center gap-2 group/btn">
                                                                <i class="ph-bold ph-power"></i> Aktifkan
                                                            </button>
                                                        </form>
                                                        
                                                        <form action="{{ route('settings.academic.destroy', $year->id) }}" 
                                                              method="POST" 
                                                              id="delete-form-{{ $year->id }}">
                                                            @csrf @method('DELETE')
                                                            
                                                            <button type="button" 
                                                                    onclick="confirmDelete('{{ $year->id }}', '{{ $year->name }} (Sem. {{ $year->semester }})')"
                                                                    class="w-9 h-9 flex items-center justify-center bg-rose-500/10 border border-rose-500/20 text-rose-400 hover:text-white hover:bg-rose-500 rounded-xl transition-all shadow-sm" title="Hapus">
                                                                <i class="ph-bold ph-trash text-lg"></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <span class="text-xs font-bold text-sky-400/60 italic flex items-center justify-end gap-1.5 px-3 py-2">
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
                background: '#021124',
                color: '#fff',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#334155',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-[2rem] font-sans border border-white/10 bg-[#021124] text-white shadow-2xl',
                    confirmButton: 'bg-rose-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-rose-700 transition-colors mx-2 shadow-lg shadow-rose-900/40',
                    cancelButton: 'bg-slate-800 text-slate-300 px-6 py-3 rounded-xl font-bold hover:bg-slate-700 transition-colors mx-2'
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