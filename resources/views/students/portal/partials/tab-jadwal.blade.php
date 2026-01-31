{{-- 
    TAB JADWAL PELAJARAN (REDESIGNED)
    Konsep: Vertical Timeline dengan Color Coding Dinamis
--}}

@php
    \Carbon\Carbon::setLocale('id');
    $schedules = $student->schoolClass->schedules ?? collect([]);
    $todayName = \Carbon\Carbon::now()->translatedFormat('l');
    
    // Default tab: Jika Minggu, ke Senin. Jika tidak, ke Hari Ini.
    $defaultDay = ($todayName == 'Minggu') ? 'Senin' : $todayName;
@endphp

<div x-data="{ activeDay: '{{ $defaultDay }}' }" class="space-y-8 animate-in fade-in duration-500 font-sans">

    {{-- 1. NAVIGASI HARI (Sticky pada Mobile) --}}
    <div class="sticky top-20 z-30 bg-slate-50/90 backdrop-blur-md py-2 -mx-4 px-4 sm:mx-0 sm:px-0 sm:bg-transparent sm:backdrop-filter-none transition-all">
        <div class="flex overflow-x-auto gap-3 no-scrollbar snap-x py-2">
            @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $day)
                <button @click="activeDay = '{{ $day }}'" 
                    class="snap-start shrink-0 relative px-6 py-3 rounded-2xl font-bold text-sm transition-all duration-300 border overflow-hidden group"
                    :class="activeDay === '{{ $day }}' 
                        ? 'bg-blue-600 text-white border-blue-600 shadow-lg shadow-blue-600/30 scale-105' 
                        : 'bg-white text-slate-500 border-slate-200 hover:border-blue-300 hover:text-blue-600'">
                    
                    {{-- Indikator Hari Ini (Ping Animation) --}}
                    @if($day == $todayName)
                        <span class="absolute top-2 right-2 flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                        </span>
                    @endif
                    
                    <span class="relative z-10 flex items-center gap-2">
                        {{ $day }}
                    </span>
                </button>
            @endforeach
        </div>
    </div>

    {{-- 2. KONTAINER JADWAL --}}
    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 min-h-[500px] relative overflow-hidden">
        
        {{-- Background Decor --}}
        <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>

        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $day)
            <div x-show="activeDay === '{{ $day }}'" 
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 translate-x-8"
                 x-transition:enter-end="opacity-100 translate-x-0"
                 class="p-6 sm:p-10 relative z-10"
                 style="display: none;">
                
                {{-- Header Hari --}}
                <div class="flex items-end justify-between mb-10 border-b border-slate-50 pb-4">
                    <div>
                        <h2 class="text-4xl font-black text-slate-800 tracking-tighter mb-1">{{ $day }}</h2>
                        <div class="flex items-center gap-2 text-slate-500 text-sm font-medium">
                            <i class="ph-bold ph-chalkboard-teacher text-blue-500"></i>
                            <span>Kelas {{ $student->schoolClass->name ?? '-' }}</span>
                        </div>
                    </div>
                    @if($day == $todayName)
                        <div class="hidden sm:flex flex-col items-end">
                            <span class="text-[10px] font-black uppercase tracking-widest text-amber-500 mb-1">Status</span>
                            <span class="px-4 py-1.5 bg-amber-100 text-amber-700 text-xs font-black rounded-full border border-amber-200 shadow-sm flex items-center gap-1">
                                <i class="ph-fill ph-sun"></i> Hari Ini
                            </span>
                        </div>
                    @endif
                </div>

                <div class="relative space-y-0">
                    {{-- Garis Timeline Vertikal --}}
                    <div class="absolute left-[2.25rem] top-4 bottom-4 w-0.5 bg-gradient-to-b from-slate-200 via-slate-100 to-transparent"></div>

                    @php
                        $daySchedules = $schedules->where('day', $day)->sortBy('start_time');
                    @endphp

                    @forelse($daySchedules as $sched)
                        @php
                            // Parsing Waktu (Format JP atau Jam)
                            $startJP = str_contains($sched->start_time, ':') ? \Carbon\Carbon::parse($sched->start_time)->second : intval($sched->start_time);
                            $endJP = str_contains($sched->end_time, ':') ? \Carbon\Carbon::parse($sched->end_time)->second : intval($sched->end_time);
                            if($startJP == 0) $startJP = intval($sched->start_time);
                            if($endJP == 0) $endJP = intval($sched->end_time);
                            
                            // Color Coding Dinamis (Berdasarkan Hash Nama Mapel)
                            $colors = ['blue', 'indigo', 'violet', 'emerald', 'teal', 'rose', 'orange'];
                            $colorIndex = crc32($sched->subject->name ?? 'X') % count($colors);
                            $color = $colors[$colorIndex];
                        @endphp

                        <div class="relative pl-24 py-4 group">
                            {{-- Bubble Waktu (Kiri) --}}
                            <div class="absolute left-2 top-4 w-16 h-16 rounded-2xl bg-white border-2 border-slate-100 shadow-sm flex flex-col items-center justify-center z-10 group-hover:border-{{ $color }}-200 group-hover:scale-110 transition-all duration-300">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">Jam Ke</span>
                                <div class="flex items-center text-lg font-black text-slate-700">
                                    {{ $startJP }}<span class="text-slate-300 mx-0.5 text-xs">-</span>{{ $endJP }}
                                </div>
                            </div>

                            {{-- Kartu Mapel --}}
                            <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-{{ $color }}-500/10 hover:border-{{ $color }}-200 transition-all duration-300 relative overflow-hidden group-hover:-translate-y-1">
                                {{-- Aksen Warna Kiri --}}
                                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-{{ $color }}-500"></div>
                                
                                <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                                    <div>
                                        <h3 class="text-lg font-black text-slate-800 mb-2 group-hover:text-{{ $color }}-600 transition-colors line-clamp-1">
                                            {{ $sched->subject->name ?? 'Mata Pelajaran' }}
                                        </h3>
                                        <div class="flex flex-wrap items-center gap-3">
                                            <div class="flex items-center gap-1.5 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100">
                                                <i class="ph-fill ph-user-circle text-slate-400"></i>
                                                <span class="text-xs font-bold text-slate-600">{{ $sched->teacher->name ?? 'Guru' }}</span>
                                            </div>
                                            <div class="flex items-center gap-1.5 text-xs font-bold text-slate-400">
                                                <i class="ph-bold ph-door-open"></i>
                                                {{ $student->schoolClass->name ?? '-' }}
                                            </div>
                                        </div>
                                    </div>
                                    
                                    {{-- Ikon Kanan --}}
                                    <div class="w-12 h-12 rounded-full bg-{{ $color }}-50 text-{{ $color }}-500 flex items-center justify-center text-2xl group-hover:rotate-12 transition-transform shrink-0">
                                        <i class="ph-duotone ph-book-bookmark"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- CARD ISTIRAHAT (Otomatis muncul setelah Jam ke-3 & ke-6) --}}
                        @if($endJP == 3 || $endJP == 6)
                            <div class="relative pl-24 py-6">
                                <div class="absolute left-[2.25rem] top-1/2 -translate-y-1/2 w-4 h-4 bg-amber-400 rounded-full border-4 border-white shadow z-10"></div>
                                
                                <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-2xl p-4 border border-amber-100/50 border-dashed flex items-center gap-4 opacity-80 hover:opacity-100 transition-opacity">
                                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-amber-500 shadow-sm shrink-0">
                                        <i class="ph-fill {{ $endJP == 6 ? 'ph-mosque' : 'ph-coffee' }} text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-black text-amber-900 text-sm">{{ $endJP == 6 ? 'Istirahat Sholat & Makan' : 'Istirahat Pertama' }}</h4>
                                        <p class="text-xs text-amber-700 font-medium">Rehat sejenak untuk menyegarkan pikiran.</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                    @empty
                        <div class="text-center py-24">
                            <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 border border-dashed border-slate-200 animate-bounce-subtle">
                                <i class="ph-duotone ph-coffee text-5xl text-slate-300"></i>
                            </div>
                            <h3 class="text-xl font-black text-slate-700">Libur / Bebas Pelajaran</h3>
                            <p class="text-slate-400 text-sm mt-2 max-w-xs mx-auto">Tidak ada jadwal pelajaran yang tercatat untuk hari ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>

    {{-- FOOTER INFO --}}
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-[2rem] p-8 text-white relative overflow-hidden shadow-xl shadow-blue-500/20">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
        <div class="relative z-10 flex flex-col sm:flex-row items-center gap-6 text-center sm:text-left">
            <div class="w-14 h-14 rounded-2xl bg-white/20 flex items-center justify-center text-2xl backdrop-blur-md shadow-inner border border-white/20">
                <i class="ph-fill ph-info"></i>
            </div>
            <div>
                <h4 class="font-black text-lg mb-1">Catatan Jadwal</h4>
                <p class="text-blue-100 text-sm leading-relaxed max-w-xl">
                    Jadwal dapat berubah sewaktu-waktu sesuai kebijakan sekolah. Istirahat mengikuti bel sekolah. Pastikan hadir tepat waktu.
                </p>
            </div>
        </div>
    </div>
</div>