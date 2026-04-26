<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Riwayat Mengajar') }}
        </h2>
    </x-slot>

    @php
        // Pastikan format tanggal menggunakan Bahasa Indonesia
        \Carbon\Carbon::setLocale('id');
    @endphp

    @push('styles')
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        
        /* FLUENT UI SHADOWS */
        .fluent-card { box-shadow: 0 1.6px 3.6px 0 rgba(0, 0, 0, 0.132), 0 0.3px 0.9px 0 rgba(0, 0, 0, 0.108); border: 1px solid rgba(0, 0, 0, 0.05); transition: all 0.3s ease; }
        .fluent-card:hover { box-shadow: 0 6.4px 14.4px 0 rgba(0, 0, 0, 0.132), 0 1.2px 3.6px 0 rgba(0, 0, 0, 0.108); transform: translateY(-2px); }
    </style>
    @endpush

    <div class="py-8 sm:py-10 font-sans text-slate-800 pb-20">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HERO SECTION ELEVATE & FILTER --}}
            <div class="animate-enter relative rounded-xl bg-gradient-to-br from-[#25D0FF] via-[#5295FF] to-[#FFC9B9] p-8 md:p-10 mb-10 text-[#2A3B52] shadow-[0_10px_40px_-10px_rgba(37,208,255,0.4)] overflow-hidden border border-white/40 group">
                
                {{-- Dekorasi Latar --}}
                <div class="absolute inset-0 opacity-[0.03] bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none mix-blend-overlay"></div>
                <div class="absolute -top-24 -right-24 w-80 h-80 bg-white/30 rounded-full blur-[80px] pointer-events-none group-hover:bg-white/40 transition-all duration-700"></div>
                <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-white/20 rounded-full blur-[80px] pointer-events-none group-hover:bg-white/30 transition-all duration-700"></div>

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <a href="{{ route('dashboard') }}" class="group/btn bg-white/40 hover:bg-white/60 text-[#2A3B52] px-4 py-2 rounded-xl font-bold text-xs backdrop-blur-sm border border-white/50 transition-all flex items-center gap-2 shadow-sm w-fit mb-4 active:scale-95">
                            <i class="ph-bold ph-arrow-left group-hover/btn:-translate-x-1 transition-transform"></i>
                            <span>Dashboard</span>
                        </a>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/40 border border-white/50 text-[#2A3B52] text-[10px] font-black uppercase tracking-widest mb-3 backdrop-blur-sm shadow-sm">
                            <i class="ph-bold ph-archive"></i> Arsip Jurnal
                        </div>
                        <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-2 text-[#2A3B52]">
                            Riwayat Mengajar
                        </h2>
                        <p class="text-[#2A3B52]/80 text-sm md:text-base max-w-lg font-medium leading-relaxed">
                            Lihat kembali log aktivitas mengajar, materi yang disampaikan, dan catatan absensi per sesi.
                        </p>
                    </div>

                    {{-- FILTER BULAN --}}
                    <div class="bg-white/40 backdrop-blur-md p-5 rounded-xl border border-white/50 shadow-sm w-full md:w-auto shrink-0 mt-4 md:mt-0">
                        <form action="{{ route('teaching.history') }}" method="GET" class="flex flex-col gap-2">
                            <label for="month" class="text-xs font-bold text-[#2A3B52] uppercase tracking-wider">Filter Bulan</label>
                            <div class="flex items-center gap-2">
                                <div class="relative">
                                    <input type="month" name="month" id="month" value="{{ $month }}" 
                                        class="bg-white/70 hover:bg-white focus:bg-white border border-white/60 focus:border-[#5295FF] focus:ring-[#5295FF] text-[#2A3B52] text-sm font-bold rounded-lg px-4 py-2.5 transition-all shadow-sm w-full sm:w-48 cursor-pointer">
                                </div>
                                <button type="submit" class="bg-[#2A3B52] hover:bg-[#182436] text-white px-4 py-2.5 rounded-lg shadow-sm transition-all active:scale-95 flex items-center justify-center border border-transparent" title="Terapkan Filter">
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
                    @php
                        $startDate = \Carbon\Carbon::parse($history->date);
                        $startTime = \Carbon\Carbon::parse($history->started_at);
                        $endTime   = \Carbon\Carbon::parse($history->ended_at);
                    @endphp

                    <div class="animate-enter bg-white rounded-xl fluent-card overflow-hidden flex flex-col md:flex-row group hover:border-[#5295FF]" style="animation-delay: {{ ($index + 1) * 100 }}ms">
                        
                        {{-- KIRI: Tanggal & Waktu --}}
                        <div class="bg-slate-50/50 border-r border-slate-100 p-6 md:w-48 flex flex-row md:flex-col items-center justify-between md:justify-center gap-4 shrink-0 transition-colors group-hover:bg-[#F3F9FD]/30">
                            <div class="text-center">
                                <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ $startDate->translatedFormat('l') }}</div>
                                <div class="text-4xl font-black text-[#2A3B52] leading-none">{{ $startDate->format('d') }}</div>
                                <div class="text-xs font-bold text-[#5295FF] mt-1">{{ $startDate->translatedFormat('M Y') }}</div>
                            </div>
                            
                            <div class="hidden md:block w-8 h-px bg-slate-200 my-2"></div>
                            
                            <div class="text-center flex flex-col items-center">
                                <div class="bg-white border border-slate-200 rounded-lg px-3 py-1.5 shadow-sm">
                                    <div class="text-xs font-bold text-slate-600 font-mono">{{ $startTime->format('H:i') }}</div>
                                    <div class="text-[9px] text-slate-400 font-bold uppercase tracking-wider text-center mt-0.5">S/D</div>
                                    <div class="text-xs font-bold text-slate-600 font-mono">{{ $history->ended_at ? $endTime->format('H:i') : '...' }}</div>
                                </div>
                            </div>
                        </div>

                        {{-- KANAN: Detail Mengajar --}}
                        <div class="p-6 flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex flex-wrap items-center gap-2 mb-3">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200">
                                        <i class="ph-bold ph-book-open"></i> {{ $history->subject->name ?? 'Mata Pelajaran' }}
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider bg-[#F3F9FD] text-[#5295FF] border border-[#D0E7F8]">
                                        <i class="ph-bold ph-users-three"></i> Kelas {{ $history->schoolClass->name ?? '-' }}
                                    </span>
                                </div>
                                
                                <h3 class="text-lg sm:text-xl font-bold text-[#2A3B52] leading-tight mb-2 group-hover:text-[#5295FF] transition-colors">
                                    {{ $history->topic ?? 'Tidak Ada Topik' }}
                                </h3>
                                
                                <p class="text-sm text-slate-500 mb-6 leading-relaxed line-clamp-2 md:line-clamp-3">
                                    {{ $history->activities ?? 'Tidak ada catatan aktivitas untuk sesi ini.' }}
                                </p>
                            </div>

                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mt-auto pt-4 border-t border-slate-100">
                                
                                {{-- Info Kehadiran Semantik Elevate --}}
                                <div class="flex items-center gap-2 flex-wrap">
                                    <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-[#DFF6DD] border border-[#B7DFB9] shadow-sm" title="Siswa Hadir">
                                        <i class="ph-fill ph-check-circle text-[#107C10] text-sm"></i>
                                        <span class="text-xs font-bold text-[#107C10]">{{ $history->present_count ?? 0 }}</span>
                                    </div>
                                    @if(($history->late_count ?? 0) > 0)
                                        <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-[#FFEFD6] border border-[#FFD8A8] shadow-sm" title="Siswa Terlambat">
                                            <i class="ph-fill ph-clock-warning text-[#D83B01] text-sm"></i>
                                            <span class="text-xs font-bold text-[#D83B01]">{{ $history->late_count }}</span>
                                        </div>
                                    @endif
                                    @if(($history->alpha_count ?? 0) > 0)
                                        <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-[#FDE7E9] border border-[#F4C3C9] shadow-sm" title="Siswa Alpha">
                                            <i class="ph-fill ph-x-circle text-[#D13438] text-sm"></i>
                                            <span class="text-xs font-bold text-[#D13438]">{{ $history->alpha_count }}</span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Aksi --}}
                                <a href="{{ route('teaching.show', $history->id) }}" class="group/link w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-[#2A3B52] hover:bg-[#182436] text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-md transition-all active:scale-95 border border-transparent">
                                    <span>Detail Sesi</span> 
                                    <i class="ph-bold ph-arrow-right group-hover/link:translate-x-1 transition-transform"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="animate-enter relative flex flex-col items-center justify-center py-20 text-center z-10 bg-white rounded-xl fluent-card border-2 border-dashed border-slate-200">
                        <div class="w-20 h-20 sm:w-24 sm:h-24 bg-[#F3F9FD] border border-[#D0E7F8] rounded-full flex items-center justify-center shadow-sm mb-6">
                            <i class="ph-duotone ph-notebook text-4xl sm:text-5xl text-[#5295FF]"></i>
                        </div>
                        <h3 class="text-[#2A3B52] font-black text-lg sm:text-xl">Belum Ada Riwayat</h3>
                        <p class="text-slate-500 text-sm mt-2 max-w-sm mx-auto leading-relaxed">
                            Aktivitas mengajar Anda di bulan <span class="font-bold text-slate-700">{{ \Carbon\Carbon::parse($month)->translatedFormat('F Y') }}</span> belum terekam.
                        </p>
                        <a href="{{ route('teaching.index') }}" class="mt-6 px-6 py-3 bg-[#2A3B52] hover:bg-[#182436] text-white font-bold rounded-xl shadow-md transition-all flex items-center gap-2 active:scale-95 border border-transparent">
                            <i class="ph-bold ph-calendar-check"></i> Cek Jadwal Hari Ini
                        </a>
                    </div>
                @endforelse

            </div>

            {{-- Pagination --}}
            <div class="mt-10 flex justify-center animate-enter" style="animation-delay: 300ms">
                {{ $histories->appends(request()->query())->links() }}
            </div>

        </div>
    </div>
</x-app-layout>