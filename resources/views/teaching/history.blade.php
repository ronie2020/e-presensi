<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-elevate-dark leading-tight">
            {{ __('Riwayat Mengajar') }}
        </h2>
    </x-slot>

    @push('styles')
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>
    @endpush

    <div class="py-8 sm:py-10 font-sans text-elevate-dark bg-elevate-surface min-h-screen relative overflow-hidden pb-20">
        
        {{-- Efek Latar Belakang Halus --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- HERO SECTION ELEVATE & FILTER --}}
            <div class="animate-enter relative rounded-[2rem] bg-gradient-to-r from-elevate-accent via-elevate-peach-light to-elevate-peach p-8 md:p-10 mb-10 text-elevate-dark shadow-xl shadow-elevate-accent/20 overflow-hidden border border-white/60 group">
                
                {{-- Dekorasi Latar --}}
                <div class="absolute -top-10 -left-10 w-56 h-56 bg-elevate-primary/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-elevate-peach/40 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-xl"></div>

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <a href="{{ route('dashboard') }}" class="group/btn bg-white/60 hover:bg-white text-elevate-dark px-5 py-2.5 rounded-xl font-bold text-xs backdrop-blur-md border border-white/60 transition-all flex items-center gap-2 shadow-sm w-fit mb-5 active:scale-95">
                            <i class="ph-bold ph-arrow-left group-hover/btn:-translate-x-1 transition-transform"></i>
                            <span>Kembali ke Dashboard</span>
                        </a>
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-elevate-dark/10 border border-elevate-dark/20 text-elevate-dark text-[10px] font-black uppercase tracking-widest mb-3 shadow-sm">
                            <i class="ph-bold ph-archive"></i> Arsip Jurnal
                        </div>
                        <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-3 text-elevate-dark">
                            Riwayat Mengajar
                        </h2>
                        <p class="text-elevate-dark/80 text-sm font-semibold max-w-lg leading-relaxed">
                            Lihat kembali log aktivitas mengajar, materi yang disampaikan, dan catatan absensi per sesi.
                        </p>
                    </div>

                    {{-- FILTER WAKTU (Harian, Mingguan, Bulanan) --}}
                    <div class="bg-white/60 backdrop-blur-md p-5 rounded-[1.5rem] border border-white/60 shadow-lg w-full lg:w-auto shrink-0 mt-4 md:mt-0" x-data="{ filterType: '{{ $filterType ?? 'monthly' }}' }">
                        <form action="{{ route('teaching.history') }}" method="GET" class="flex flex-col gap-3">
                            <label class="text-xs font-black text-elevate-dark uppercase tracking-wider">Filter Waktu</label>
                            <div class="flex flex-col sm:flex-row items-center gap-2">
                                
                                {{-- Dropdown Pilih Tipe Waktu --}}
                                <div class="relative w-full sm:w-auto">
                                    <select name="filter_type" x-model="filterType" class="bg-white hover:bg-elevate-soft border border-slate-200 text-sm font-bold text-elevate-dark rounded-xl px-4 py-3.5 focus:border-elevate-accent focus:ring-elevate-accent/30 w-full sm:w-[130px] cursor-pointer shadow-sm appearance-none transition-all">
                                        <option value="daily">Harian</option>
                                        <option value="weekly">Mingguan</option>
                                        <option value="monthly">Bulanan</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-elevate-dark">
                                        <i class="ph-bold ph-caret-down text-xs"></i>
                                    </div>
                                </div>

                                {{-- Input Nilai Waktu (Berubah Otomatis Sesuai Dropdown) --}}
                                <div class="relative w-full sm:w-[190px]">
                                    <input style="display: none;" x-show="filterType === 'daily'" type="date" name="filter_value_daily" value="{{ $filterType == 'daily' ? $filterValue : \Carbon\Carbon::now()->format('Y-m-d') }}" :disabled="filterType !== 'daily'" class="w-full bg-white hover:bg-elevate-soft focus:bg-white border border-slate-200 focus:border-elevate-accent focus:ring-elevate-accent/30 text-elevate-dark text-sm font-bold rounded-xl px-4 py-3.5 transition-all shadow-sm cursor-pointer">
                                    
                                    <input style="display: none;" x-show="filterType === 'weekly'" type="week" name="filter_value_weekly" value="{{ $filterType == 'weekly' ? $filterValue : \Carbon\Carbon::now()->format('Y-\WW') }}" :disabled="filterType !== 'weekly'" class="w-full bg-white hover:bg-elevate-soft focus:bg-white border border-slate-200 focus:border-elevate-accent focus:ring-elevate-accent/30 text-elevate-dark text-sm font-bold rounded-xl px-4 py-3.5 transition-all shadow-sm cursor-pointer">
                                    
                                    <input style="display: none;" x-show="filterType === 'monthly'" type="month" name="filter_value_monthly" value="{{ $filterType == 'monthly' ? $filterValue : \Carbon\Carbon::now()->format('Y-m') }}" :disabled="filterType !== 'monthly'" class="w-full bg-white hover:bg-elevate-soft focus:bg-white border border-slate-200 focus:border-elevate-accent focus:ring-elevate-accent/30 text-elevate-dark text-sm font-bold rounded-xl px-4 py-3.5 transition-all shadow-sm cursor-pointer">
                                </div>

                                <button type="submit" class="w-full sm:w-auto bg-elevate-dark hover:bg-elevate-primary text-white px-5 py-3.5 rounded-xl shadow-md transition-all active:scale-95 flex items-center justify-center border border-transparent" title="Terapkan Filter">
                                    <i class="ph-bold ph-magnifying-glass text-lg"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- LIST RIWAYAT --}}
            <div class="space-y-6">

                @forelse($histories as $index => $history)
                    <div class="animate-enter bg-white rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/40 overflow-hidden flex flex-col md:flex-row group hover:border-elevate-accent/50 transition-colors" style="animation-delay: {{ ($index + 1) * 100 }}ms">
                        
                        {{-- KIRI: Tanggal & Waktu --}}
                        <div class="bg-elevate-soft/50 border-r border-slate-100 p-6 md:w-56 flex flex-row md:flex-col items-center justify-between md:justify-center gap-4 shrink-0 transition-colors group-hover:bg-elevate-peach-light/30">
                            <div class="text-center">
                                <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ $history->date->translatedFormat('l') }}</div>
                                <div class="text-5xl font-black text-elevate-dark leading-none">{{ $history->date->format('d') }}</div>
                                <div class="text-sm font-bold text-elevate-primary mt-2">{{ $history->date->translatedFormat('M Y') }}</div>
                            </div>
                            
                            <div class="hidden md:block w-10 h-1 rounded-full bg-slate-200 my-4"></div>
                            
                            <div class="text-center flex flex-col items-center">
                                <div class="bg-white border border-slate-200 rounded-xl px-4 py-2 shadow-sm">
                                    <div class="text-sm font-black text-elevate-dark">{{ $history->started_at->format('H:i') }}</div>
                                    <div class="text-[9px] text-slate-400 font-bold uppercase tracking-widest text-center mt-0.5">S/D</div>
                                    <div class="text-sm font-black text-elevate-dark">{{ $history->ended_at ? $history->ended_at->format('H:i') : '...' }}</div>
                                </div>
                            </div>
                        </div>

                        {{-- KANAN: Detail Mengajar --}}
                        <div class="p-6 md:p-8 flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex flex-wrap items-center gap-2 mb-4">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200">
                                        <i class="ph-bold ph-book-open"></i> {{ $history->subject->name ?? 'Mata Pelajaran' }}
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wider bg-elevate-soft text-elevate-primary border border-slate-200">
                                        <i class="ph-bold ph-users-three"></i> Kelas {{ $history->schoolClass->name ?? '-' }}
                                    </span>
                                </div>
                                
                                <h3 class="text-xl sm:text-2xl font-black text-elevate-dark leading-tight mb-2 group-hover:text-elevate-primary transition-colors">
                                    {{ $history->topic ?? 'Tidak Ada Topik' }}
                                </h3>
                                
                                <p class="text-sm text-slate-500 mb-6 font-medium leading-relaxed line-clamp-2 md:line-clamp-3">
                                    {{ $history->activities ?? 'Tidak ada catatan aktivitas untuk sesi ini.' }}
                                </p>
                            </div>

                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5 mt-auto pt-5 border-t border-slate-100">
                                
                                {{-- Info Kehadiran Semantik Elevate --}}
                                <div class="flex items-center gap-3 flex-wrap">
                                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-[#DFF6DD] border border-[#B7DFB9] shadow-sm" title="Siswa Hadir">
                                        <i class="ph-fill ph-check-circle text-[#107C10] text-base"></i>
                                        <span class="text-sm font-black text-[#107C10]">{{ $history->total_hadir }}</span>
                                    </div>
                                    @if($history->jml_telat > 0)
                                        <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-[#FFEFD6] border border-[#FFD8A8] shadow-sm" title="Siswa Terlambat">
                                            <i class="ph-fill ph-clock-warning text-[#D83B01] text-base"></i>
                                            <span class="text-sm font-black text-[#D83B01]">{{ $history->jml_telat }}</span>
                                        </div>
                                    @endif
                                    @if($history->jml_alpha > 0)
                                        <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-[#FDE7E9] border border-[#F4C3C9] shadow-sm" title="Siswa Alpha">
                                            <i class="ph-fill ph-x-circle text-[#D13438] text-base"></i>
                                            <span class="text-sm font-black text-[#D13438]">{{ $history->jml_alpha }}</span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Aksi --}}
                                <a href="{{ route('teaching.show', $history->id) }}" class="group/link w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-elevate-dark hover:bg-elevate-primary text-white px-6 py-3.5 rounded-xl text-sm font-bold shadow-lg shadow-elevate-dark/30 transition-all active:scale-95 border border-transparent">
                                    <span>Detail Sesi</span> 
                                    <i class="ph-bold ph-arrow-right group-hover/link:translate-x-1 transition-transform"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="animate-enter relative flex flex-col items-center justify-center py-24 text-center z-10 bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200 shadow-sm">
                        <div class="w-24 h-24 sm:w-28 sm:h-28 bg-elevate-soft border border-slate-200 rounded-full flex items-center justify-center shadow-sm mb-6">
                            <i class="ph-duotone ph-notebook text-5xl sm:text-6xl text-elevate-primary"></i>
                        </div>
                        <h3 class="text-elevate-dark font-black text-xl sm:text-2xl">Belum Ada Riwayat</h3>
                        <p class="text-slate-500 text-sm mt-3 max-w-sm mx-auto font-medium leading-relaxed">
                            Aktivitas mengajar Anda pada <span class="font-bold text-slate-700">{{ $filterLabel }}</span> belum terekam.
                        </p>
                        <a href="{{ route('teaching.index') }}" class="mt-8 px-8 py-3.5 bg-elevate-dark hover:bg-elevate-primary text-white font-bold rounded-2xl shadow-lg transition-all flex items-center gap-2 active:scale-95 border border-transparent text-sm">
                            <i class="ph-bold ph-calendar-check"></i> Cek Jadwal Hari Ini
                        </a>
                    </div>
                @endforelse

            </div>

            {{-- Pagination --}}
            <div class="mt-12 flex justify-center animate-enter" style="animation-delay: 300ms">
                {{ $histories->appends(request()->query())->links() }}
            </div>

        </div>
    </div>
</x-app-layout>