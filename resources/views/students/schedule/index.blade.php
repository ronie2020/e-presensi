@extends('layouts.public')

@section('content')
    @php
        \Carbon\Carbon::setLocale('id');
        $days = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
        ];
        $todayName = $days[date('l')];
        $defaultTab = ($todayName == 'Minggu') ? 'Senin' : $todayName;
    @endphp

    <div class="max-w-6xl mx-auto px-4 sm:px-6 pb-20 pt-24">
        
        {{-- HEADER SECTION --}}
        <div class="animate-enter relative rounded-[2.5rem] bg-gradient-to-r from-blue-900 via-indigo-800 to-blue-900 p-8 md:p-10 mb-8 text-white shadow-2xl shadow-blue-900/20 overflow-hidden border border-white/10">
            <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-blue-500 rounded-full mix-blend-overlay filter blur-[120px] opacity-20"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div>
                    <a href="{{ route('student.habits.dashboard') }}" class="inline-flex items-center gap-2 text-blue-200 hover:text-white transition-colors mb-4 text-xs font-bold uppercase tracking-widest">
                        <i class="ph-bold ph-arrow-left"></i> Kembali ke Dashboard
                    </a>
                    <h1 class="text-3xl font-extrabold tracking-tight mb-2">Jadwal Pelajaran</h1>
                    <p class="text-blue-100/90 text-sm max-w-xl leading-relaxed">
                        Lihat jadwal belajarmu di kelas <span class="font-bold text-white">{{ Auth::guard('student')->user()->schoolClass->name ?? '-' }}</span>.
                    </p>
                </div>
                
                <div class="bg-white/10 backdrop-blur-md px-5 py-3 rounded-2xl border border-white/20 flex items-center gap-3 shrink-0">
                    <div class="text-right">
                        <p class="text-xs text-blue-200 font-bold uppercase">Hari Ini</p>
                        <p class="text-xl font-black">{{ \Carbon\Carbon::now()->translatedFormat('l, d M Y') }}</p>
                    </div>
                    <div class="w-12 h-12 bg-white text-blue-600 rounded-xl flex items-center justify-center text-2xl font-bold shadow-lg">
                        <i class="ph-fill ph-calendar"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- MAIN CONTENT DENGAN ALPINE JS --}}
        <div x-data="{ activeDay: '{{ $defaultTab }}' }" class="space-y-6">

            {{-- NAVIGASI HARI (Tabs) --}}
            <div class="flex overflow-x-auto pb-4 gap-3 no-scrollbar snap-x">
                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $day)
                    <button @click="activeDay = '{{ $day }}'" 
                        class="snap-start shrink-0 px-8 py-3.5 rounded-2xl font-bold text-sm transition-all duration-300 border relative overflow-hidden group"
                        :class="activeDay === '{{ $day }}' 
                            ? 'bg-blue-600 text-white border-blue-600 shadow-lg shadow-blue-600/30 scale-105' 
                            : 'bg-white text-slate-500 border-slate-200 hover:border-blue-300 hover:text-blue-600'">
                        
                        @if($day == $todayName)
                            <span class="absolute top-1 right-1 flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                            </span>
                        @endif
                        
                        <span class="relative z-10">{{ $day }}</span>
                    </button>
                @endforeach
            </div>

            {{-- AREA JADWAL --}}
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 p-6 sm:p-10 min-h-[450px] relative overflow-hidden">
                <div class="absolute top-0 right-0 w-80 h-80 bg-blue-50 rounded-full blur-[100px] -translate-y-1/2 translate-x-1/2 pointer-events-none opacity-50"></div>

                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $day)
                    <div x-show="activeDay === '{{ $day }}'" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         style="display: none;">
                        
                        <div class="flex items-center justify-between mb-10 relative z-10">
                            <div>
                                <h2 class="text-3xl font-black text-slate-800 tracking-tight">{{ $day }}</h2>
                                <p class="text-slate-400 text-sm font-medium mt-1">Mata pelajaran yang harus kamu ikuti.</p>
                            </div>
                            @if($day == $todayName)
                                <span class="px-4 py-1.5 bg-amber-100 text-amber-700 text-xs font-black rounded-full border border-amber-200 uppercase tracking-widest shadow-sm">
                                    Hari Ini
                                </span>
                            @endif
                        </div>

                        {{-- TIMELINE JADWAL MENGGUNAKAN TIMESLOT BARU --}}
                        <div class="relative z-10 space-y-8">
                            @php 
                                $daySchedules = $schedules->where('day_of_week', $day)->keyBy('timeslot_id'); 
                                $hasAnySession = false;
                            @endphp

                            @foreach($timeslots as $slot)
                                @php
                                    $slotDays = array_map('trim', explode(',', $slot->day_of_week ?? 'Semua Hari'));
                                    $isValidDay = in_array($day, $slotDays) || $slot->day_of_week === 'Semua Hari' || ($slot->day_of_week === 'Selain Senin' && $day !== 'Senin') || ($slot->day_of_week === 'Selain Jumat' && $day !== 'Jumat');
                                @endphp

                                @if($isValidDay)
                                    @php $hasAnySession = true; @endphp

                                    @if($slot->is_break)
                                        {{-- UI ISTIRAHAT --}}
                                        <div class="flex group items-center">
                                            <div class="flex flex-col items-center mr-6 md:mr-10 shrink-0">
                                                <div class="w-20 py-2.5 rounded-2xl bg-amber-50 text-amber-600 font-black text-[10px] text-center border border-amber-200 shadow-sm">
                                                    {{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }}<br>
                                                    <span class="text-amber-300">|</span><br>
                                                    {{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }}
                                                </div>
                                            </div>
                                            <div class="flex-1">
                                                <div class="bg-amber-50/50 p-4 sm:p-5 rounded-3xl border border-amber-200 border-dashed flex items-center gap-4 hover:bg-amber-50 transition-colors">
                                                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-amber-500 shadow-sm border border-amber-100 shrink-0">
                                                        <i class="ph-fill {{ (str_contains(strtolower($slot->name), 'sholat') || str_contains(strtolower($slot->name), 'dhuha')) ? 'ph-mosque' : (str_contains(strtolower($slot->name), 'upacara') ? 'ph-flag' : 'ph-coffee') }} text-2xl"></i>
                                                    </div>
                                                    <div>
                                                        <h4 class="font-black text-amber-700 text-sm uppercase tracking-wider mb-0.5">{{ $slot->name }}</h4>
                                                        <p class="text-[10px] font-bold text-amber-600/70">Waktunya rehat sejenak.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        {{-- UI MATA PELAJARAN --}}
                                        @php $sched = $daySchedules->get($slot->id); @endphp
                                        
                                        @if($sched)
                                            <div class="flex group">
                                                {{-- Waktu --}}
                                                <div class="flex flex-col items-center mr-6 md:mr-10 shrink-0">
                                                    <div class="w-20 py-3 rounded-2xl bg-slate-100 text-slate-600 font-black text-xs text-center border border-slate-200 group-hover:bg-blue-600 group-hover:text-white group-hover:border-blue-600 transition-all duration-300 shadow-sm">
                                                        {{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }}
                                                        <div class="w-10 h-[2px] bg-slate-300 mx-auto my-1.5 opacity-30 group-hover:bg-white"></div>
                                                        {{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }}
                                                    </div>
                                                    @if(!$loop->last)
                                                        <div class="w-1 h-full bg-slate-100 my-3 rounded-full group-hover:bg-blue-50 transition-colors"></div>
                                                    @endif
                                                </div>

                                                {{-- Card Mata Pelajaran --}}
                                                <div class="flex-1 pb-6 sm:pb-8">
                                                    <div class="bg-white p-5 sm:p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:border-blue-200 transition-all duration-300 group-hover:-translate-y-1 relative overflow-hidden">
                                                        <div class="absolute top-0 right-0 w-32 h-32 bg-slate-50 rounded-full -mr-16 -mt-16 group-hover:bg-blue-50 transition-colors pointer-events-none"></div>
                                                        
                                                        <div class="relative z-10">
                                                            <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Sesi Ke-{{ $slot->order_sequence }}</div>
                                                            <h3 class="font-black text-lg sm:text-xl text-slate-800 mb-3 group-hover:text-blue-600 transition-colors">
                                                                {{ $sched->subject->name ?? 'Mata Pelajaran' }}
                                                            </h3>
                                                            
                                                            <div class="flex flex-wrap items-center gap-3 mt-4">
                                                                <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-100">
                                                                    <div class="w-6 h-6 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-[10px] font-black shrink-0">
                                                                        {{ substr($sched->teacher->name ?? 'G', 0, 1) }}
                                                                    </div>
                                                                    <span class="text-xs font-bold text-slate-600">{{ $sched->teacher->name ?? 'Guru Pengampu' }}</span>
                                                                </div>
                                                                
                                                                <div class="flex items-center gap-1.5 text-slate-500 text-xs font-bold px-3 py-2 bg-slate-50 rounded-xl border border-slate-100">
                                                                    <i class="ph-bold ph-door-open text-base text-slate-400"></i>
                                                                    Kelas {{ $sched->studentClass->name ?? '-' }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                @endif
                            @endforeach

                            @if(!$hasAnySession)
                                <div class="text-center py-20">
                                    <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300 border border-dashed border-slate-200">
                                        <i class="ph-duotone ph-coffee text-5xl"></i>
                                    </div>
                                    <h3 class="text-xl font-black text-slate-700">Tidak ada pelajaran</h3>
                                    <p class="text-slate-400 text-sm mt-2">Waktunya istirahat atau belajar mandiri!</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- FOOTER INFO --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-slate-900 rounded-[2rem] p-8 text-white relative overflow-hidden shadow-xl shadow-slate-900/20">
                     <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
                     <div class="flex items-center gap-4 mb-4">
                         <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center text-2xl text-blue-400">
                             <i class="ph-fill ph-clock-countdown"></i>
                         </div>
                         <h4 class="font-black text-lg">Jam Masuk</h4>
                     </div>
                     <p class="text-3xl font-black text-blue-400">07:00 <span class="text-sm text-slate-500 font-bold">WIB</span></p>
                     <p class="text-xs text-slate-400 mt-3 font-medium italic">"Disiplin adalah kunci kesuksesan seorang juara."</p>
                </div>
                
                <div class="bg-blue-600 rounded-[2rem] p-8 text-white relative overflow-hidden shadow-xl shadow-blue-600/20">
                     <div class="absolute top-0 right-0 w-32 h-32 bg-white/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
                     <div class="flex items-center gap-4 mb-4">
                         <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center text-2xl text-white">
                             <i class="ph-fill ph-info"></i>
                         </div>
                         <h4 class="font-black text-lg">Informasi Jadwal</h4>
                     </div>
                     <p class="text-sm font-medium leading-relaxed opacity-90">
                        Jadwal ini dapat berubah sewaktu-waktu sesuai kebijakan sekolah. Pastikan cek agenda kegiatan secara berkala.
                     </p>
                </div>
            </div>

        </div>
    </div>

    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>
@endsection