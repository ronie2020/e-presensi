<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Jadwal Mengajar') }}
        </h2>
    </x-slot>

    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        @keyframes wave { 0% { transform: rotate(0deg); } 10% { transform: rotate(14deg); } 20% { transform: rotate(-8deg); } 30% { transform: rotate(14deg); } 40% { transform: rotate(-4deg); } 50% { transform: rotate(10deg); } 60% { transform: rotate(0deg); } 100% { transform: rotate(0deg); } }
        .animate-wave { animation: wave 2.5s infinite; transform-origin: 70% 70%; }
    </style>

    <div class="py-8 sm:py-10 font-sans text-slate-100 bg-[#020b18] min-h-screen relative overflow-hidden pb-20">
        
        {{-- Efek Latar Belakang Halus --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-gradient-to-r from-sky-600/20 via-blue-600/10 to-transparent pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            {{-- NOTIFIKASI --}}
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 p-5 bg-emerald-950/60 border border-emerald-500/30 text-emerald-300 rounded-[1.5rem] flex items-center justify-between shadow-lg backdrop-blur-md">
                    <div class="flex items-center gap-3">
                        <i class="ph-fill ph-check-circle text-xl text-emerald-400"></i>
                        <span class="font-bold text-sm">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="hover:bg-emerald-500/20 p-2 rounded-full transition-colors text-emerald-300"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif

            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 p-5 bg-rose-950/60 border border-rose-500/30 text-rose-300 rounded-[1.5rem] flex items-center justify-between shadow-lg backdrop-blur-md">
                    <div class="flex items-center gap-3">
                        <i class="ph-fill ph-warning-circle text-xl text-rose-400"></i>
                        <span class="font-bold text-sm">{{ session('error') }}</span>
                    </div>
                    <button @click="show = false" class="hover:bg-rose-500/20 p-2 rounded-full transition-colors text-rose-300"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif

            {{-- HERO SECTION --}}
            <x-hero-section
                badge="Jurnal Mengajar Guru"
                badgeIcon="ph-chalkboard-teacher"
                title="Agenda Kelas"
                titleHighlight="Hari Ini"
                description="Halo, {{ Auth::user()->name }}! Siap mengajar hari ini? Pastikan jurnal terisi dan absensi siswa tercatat dengan akurat."
                :chips="[
                    ['icon' => 'ph-calendar-blank', 'label' => \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y')],
                    ['icon' => 'ph-chalkboard-teacher', 'label' => count($groupedSchedules ?? []) . ' Pertemuan Kelas'],
                    ['icon' => 'ph-check-circle', 'label' => 'Jurnal & Presensi']
                ]"
                heroIcon="ph-chalkboard-teacher"
                :showcaseNumber="count($groupedSchedules ?? [])"
                showcaseLabel="Pertemuan Hari Ini"
                statusOrb="Aktif Mengajar"
                statusColor="emerald"
                ctaPrimaryText="Riwayat Mengajar"
                ctaPrimaryHref="{{ route('teaching.history') }}"
                ctaPrimaryIcon="ph-clock-counter-clockwise"
                ctaSecondaryText="Dashboard Utama"
                ctaSecondaryHref="{{ route('dashboard') }}"
                ctaSecondaryIcon="ph-arrow-left"
            />

            {{-- LIST JADWAL --}}
            <div class="flex items-center justify-between mb-6 animate-enter" style="animation-delay: 200ms">
                <h3 class="font-black text-white text-xl flex items-center gap-3">
                    <div class="w-2 h-6 bg-[#56bbf1] rounded-full"></div>
                    Agenda {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l') }}
                </h3>
            </div>

            @if(isset($groupedSchedules) && count($groupedSchedules) > 0)
                <div class="grid grid-cols-1 gap-5">
                    @foreach($groupedSchedules as $index => $group)
                        @php
                            // PERUBAHAN: Sekarang kita mengambil data dari Grup, bukan satuan
                            $firstSchedule = $group->first();
                            $lastSchedule = $group->last();
                            $totalJP = $group->count();
                            
                            $session = $firstSchedule->todaySession;
                            
                            $startJP = isset($firstSchedule->timeslot->start_time) ? \Carbon\Carbon::parse($firstSchedule->timeslot->start_time)->format('H:i') : '--:--';
                            $endJP   = isset($lastSchedule->timeslot->end_time) ? \Carbon\Carbon::parse($lastSchedule->timeslot->end_time)->format('H:i') : '--:--';
                            
                            $firstSlotName = $firstSchedule->timeslot->name ?? ('Sesi ' . ($firstSchedule->timeslot->order_sequence ?? ($index + 1)));
                            $lastSlotName  = $lastSchedule->timeslot->name ?? ('Sesi ' . ($lastSchedule->timeslot->order_sequence ?? ($index + 1)));
                            $sessionDisplay = $firstSlotName === $lastSlotName ? $firstSlotName : $firstSlotName . ' - ' . $lastSlotName;

                            if (!$session) {
                                $status = 'waiting'; 
                                $borderClass = 'border-l-[6px] border-l-[#56bbf1]';
                                $bgIcon = 'bg-sky-500/20 text-sky-300 border-sky-500/30';
                                $btnClass = 'bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white shadow-sky-500/20'; 
                            } elseif ($session->status == 'open') {
                                $status = 'ongoing';
                                $borderClass = 'border-l-[6px] border-l-emerald-500 ring-2 ring-emerald-500/20';
                                $bgIcon = 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30';
                                $btnClass = 'bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-white shadow-emerald-500/20'; 
                            } else {
                                $status = 'done';
                                $borderClass = 'border-l-[6px] border-l-slate-700 bg-slate-900/40';
                                $bgIcon = 'bg-slate-800/80 text-slate-400 border-white/10';
                            }
                        @endphp

                        <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2rem] border border-white/10 shadow-2xl p-6 {{ $borderClass }} flex flex-col md:flex-row justify-between items-center gap-6 group relative overflow-hidden animate-enter backdrop-blur-xl" style="animation-delay: {{ ($index + 3) * 100 }}ms">
                            
                            <div class="flex items-center gap-5 w-full md:w-auto z-10">
                                <div class="flex flex-col items-center justify-center min-w-[6rem] px-2 py-2 h-24 rounded-2xl {{ $bgIcon }} shrink-0 shadow-sm border transition-colors relative text-center">
                                    {{-- Lencana Jumlah JP (Hanya muncul jika > 1) --}}
                                    @if($totalJP > 1)
                                        <div class="absolute -top-2 -right-2 bg-amber-500/30 text-amber-300 text-[10px] font-black px-2.5 py-0.5 rounded-full border border-amber-500/40 shadow-sm z-20 backdrop-blur-md">
                                            {{ $totalJP }} JP
                                        </div>
                                    @endif
                                    <span class="text-[9px] font-bold uppercase tracking-wider opacity-70">Sesi</span>
                                    <span class="{{ strlen($sessionDisplay) > 7 ? 'text-xs md:text-sm' : 'text-2xl md:text-3xl' }} font-black leading-tight mt-1 text-white">{{ $sessionDisplay }}</span>
                                </div>
                                <div>
                                    <h4 class="font-black text-white text-xl md:text-2xl group-hover:text-sky-400 transition-colors">{{ $firstSchedule->subject->name ?? 'Pelajaran Terhapus' }}</h4>
                                    <div class="flex flex-wrap gap-2 mt-2">
                                        <span class="flex items-center gap-1.5 text-xs font-bold text-slate-300 bg-slate-800/80 px-3 py-1.5 rounded-lg border border-white/10">
                                            <i class="ph-bold ph-users-three text-sky-400"></i> Kelas {{ $firstSchedule->studentClass->name ?? $firstSchedule->schoolClass->name ?? 'Tidak Diketahui' }}
                                        </span>
                                        <span class="flex items-center gap-1.5 text-xs font-bold text-slate-300 bg-slate-800/80 px-3 py-1.5 rounded-lg border border-white/10">
                                            <i class="ph-bold ph-clock text-emerald-400"></i> {{ $startJP }} - {{ $endJP }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="w-full md:w-auto z-10">
                                @if($status == 'waiting')
                                    <form action="{{ route('teaching.start', $firstSchedule->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full md:w-auto px-8 py-3.5 {{ $btnClass }} font-bold rounded-2xl shadow-lg transition transform flex items-center justify-center gap-2 active:scale-95 text-sm border border-white/10">
                                            <i class="ph-bold ph-play-circle text-xl"></i> 
                                            {{ $totalJP > 1 ? "Mulai Kelas ({$totalJP} Jam)" : "Mulai Mengajar" }}
                                        </button>
                                    </form>
                                @elseif($status == 'ongoing')
                                    <div class="flex flex-col md:items-end gap-3">
                                        <div class="flex items-center justify-center md:justify-end gap-2 text-emerald-300 font-black text-[10px] uppercase tracking-widest bg-emerald-950/60 px-3 py-1.5 rounded-full border border-emerald-500/30 shadow-sm">
                                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Sedang Berlangsung
                                        </div>
                                        <a href="{{ route('teaching.show', $session->id) }}" class="w-full md:w-auto px-8 py-3.5 {{ $btnClass }} font-bold rounded-2xl shadow-lg transition transform flex items-center justify-center gap-2 active:scale-95 text-sm border border-white/10">
                                            Buka Kelas <i class="ph-bold ph-arrow-right"></i>
                                        </a>
                                    </div>
                                @else
                                    <div class="flex items-center gap-3">
                                        <span class="px-5 py-3.5 bg-slate-800/60 text-slate-400 font-bold text-sm rounded-2xl flex items-center gap-2 border border-white/10 cursor-not-allowed">
                                            <i class="ph-fill ph-check-circle text-emerald-400"></i> Selesai
                                        </span>
                                        <a href="{{ route('teaching.show', $session->id) }}" class="p-3 bg-slate-800/80 border border-white/10 rounded-2xl text-slate-300 hover:text-sky-400 hover:border-sky-500/40 hover:bg-slate-700 transition-all shadow-sm active:scale-95" title="Lihat Detail">
                                            <i class="ph-bold ph-eye text-xl"></i>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-24 bg-slate-900/60 rounded-[2.5rem] border-2 border-dashed border-white/10 animate-enter delay-200">
                    <div class="w-24 h-24 bg-slate-800/80 rounded-full flex items-center justify-center mx-auto mb-6 text-sky-400 border border-white/10">
                        <i class="ph-duotone ph-coffee text-5xl"></i>
                    </div>
                    <h3 class="text-white font-black text-xl mb-2">Tidak Ada Jadwal Hari Ini</h3>
                    <p class="text-slate-400 max-w-xs mx-auto text-sm font-medium leading-relaxed">
                        Hari ini ({{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l') }}) Anda tidak memiliki jadwal kelas.
                    </p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>