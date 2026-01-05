<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Jadwal Mengajar') }}
        </h2>
    </x-slot>

    <div class="py-8 font-sans text-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- NOTIFIKASI --}}
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <i class="ph-fill ph-check-circle text-xl"></i>
                        <span class="font-bold">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="hover:bg-emerald-100 p-1 rounded-lg"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif

            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <i class="ph-fill ph-warning-circle text-xl"></i>
                        <span class="font-bold">{{ session('error') }}</span>
                    </div>
                    <button @click="show = false" class="hover:bg-rose-100 p-1 rounded-lg"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif

            {{-- HEADER DASHBOARD --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">
                
                {{-- Kartu Hari Ini --}}
                <div class="bg-gray-900 bg-gradient-to-br from-slate-900 via-blue-900 to-blue-800 rounded-[2rem] p-8 text-white shadow-xl shadow-blue-900/30 relative overflow-hidden group border border-white/10">
                    <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                    <div class="absolute right-0 top-0 opacity-10 transform translate-x-8 -translate-y-8 group-hover:scale-110 transition-transform duration-500">
                        <i class="ph-fill ph-calendar-check text-[10rem]"></i>
                    </div>
                    
                    <div class="relative z-10 h-full flex flex-col justify-between">
                        <div>
                            <p class="text-blue-300 font-bold text-sm mb-1 flex items-center gap-2"><i class="ph-bold ph-calendar-blank"></i> Hari Ini</p>
                            <h3 class="text-3xl font-black tracking-tight leading-tight">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</h3>
                        </div>
                        <div class="mt-6">
                            <span class="bg-white/10 backdrop-blur-md px-4 py-2.5 rounded-xl text-sm font-bold border border-white/10 shadow-sm inline-flex items-center gap-2">
                                <span class="bg-emerald-400 w-2 h-2 rounded-full animate-pulse"></span>
                                {{ count($schedules) }} Sesi Pelajaran
                            </span>
                        </div>
                    </div>
                </div>
                
                {{-- Kartu Welcome --}}
                <div class="lg:col-span-2 bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 flex items-center justify-between relative overflow-hidden">
                    <div class="absolute inset-0 bg-slate-50/50 opacity-0 md:opacity-100 bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:20px_20px]"></div>
                    
                    <div class="relative z-10 max-w-lg">
                        <h3 class="font-black text-slate-800 text-2xl mb-2 flex items-center gap-2">
                            Halo, {{ Auth::user()->name }}! <span class="animate-wave origin-bottom-right inline-block">👋</span>
                        </h3>
                        <p class="text-slate-500 leading-relaxed font-medium text-sm">
                            Sudah siap mengajar hari ini? Pastikan jurnal terisi dan absensi siswa tercatat dengan baik.
                        </p>
                    </div>
                    
                    <div class="hidden md:block relative z-10">
                        <div class="w-24 h-24 bg-blue-50 rounded-[1.5rem] flex items-center justify-center text-blue-600 shadow-inner rotate-3 hover:rotate-6 transition-transform">
                            <i class="ph-duotone ph-chalkboard-teacher text-5xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- LIST JADWAL --}}
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-bold text-slate-800 text-xl flex items-center gap-2">
                    <div class="w-1.5 h-6 bg-blue-600 rounded-full"></div>
                    Agenda Hari Ini
                </h3>
            </div>

            @if($schedules->count() > 0)
                <div class="grid grid-cols-1 gap-5">
                    @foreach($schedules as $schedule)
                        @php
                            $session = \App\Models\TeachingSession::where('schedule_id', $schedule->id)
                                        ->whereDate('date', \Carbon\Carbon::today())
                                        ->first();
                            
                            // --- LOGIKA PEMBERSIHAN FORMAT JAM ---
                            // Jika formatnya "00:00:01" (karena tipe kolom TIME), kita ambil detiknya saja sebagai angka jam ke-
                            // Jika formatnya sudah angka "1", gunakan langsung.
                            $startJP = str_contains($schedule->start_time, ':') 
                                        ? \Carbon\Carbon::parse($schedule->start_time)->second // Ambil detik (krn input 1 jadi 00:00:01)
                                        : $schedule->start_time;
                                        
                            $endJP = str_contains($schedule->end_time, ':') 
                                        ? \Carbon\Carbon::parse($schedule->end_time)->second 
                                        : $schedule->end_time;

                            // Fallback jika hasilnya 0 (berarti parsing gagal atau jam ke-0)
                            if($startJP == 0) $startJP = intval($schedule->start_time);
                            if($endJP == 0) $endJP = intval($schedule->end_time);

                            // Logika Status
                            if (!$session) {
                                $status = 'waiting'; 
                                $borderClass = 'border-l-4 border-l-blue-500';
                                $bgIcon = 'bg-blue-50 text-blue-600';
                                $btnClass = 'bg-blue-600 hover:bg-blue-700 shadow-blue-500/30';
                            } elseif ($session->status == 'open') {
                                $status = 'ongoing';
                                $borderClass = 'border-l-4 border-l-emerald-500 ring-2 ring-emerald-500/20';
                                $bgIcon = 'bg-emerald-50 text-emerald-600';
                                $btnClass = 'bg-emerald-500 hover:bg-emerald-600 shadow-emerald-500/30';
                            } else {
                                $status = 'done';
                                $borderClass = 'border-l-4 border-l-slate-300 bg-slate-50/50';
                                $bgIcon = 'bg-slate-100 text-slate-500';
                            }
                        @endphp

                        <div class="bg-white rounded-[2rem] shadow-sm hover:shadow-lg transition-all p-6 border border-slate-100 {{ $borderClass }} flex flex-col md:flex-row justify-between items-center gap-6 group relative overflow-hidden">
                            
                            <!-- Kiri: Info Waktu & Kelas -->
                            <div class="flex items-center gap-5 w-full md:w-auto z-10">
                                <div class="flex flex-col items-center justify-center w-20 h-20 rounded-2xl {{ $bgIcon }} shrink-0 shadow-sm border border-white/50">
                                    <span class="text-[10px] font-bold uppercase tracking-wider opacity-60">Jam Ke</span>
                                    {{-- TAMPILKAN ANGKA BERSIH --}}
                                    <span class="text-3xl font-black leading-none">{{ $startJP }}</span>
                                    @if($startJP != $endJP)
                                        <span class="text-xs font-bold -mt-1 opacity-60">- {{ $endJP }}</span>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="font-black text-slate-800 text-xl group-hover:text-blue-600 transition-colors">{{ $schedule->subject->name }}</h4>
                                    <div class="flex flex-wrap gap-2 mt-2">
                                        <span class="flex items-center gap-1.5 text-xs font-bold text-slate-500 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100">
                                            <i class="ph-bold ph-users-three"></i> Kelas {{ $schedule->schoolClass->name }}
                                        </span>
                                        
                                        {{-- LABEL JAM --}}
                                        <span class="flex items-center gap-1.5 text-xs font-bold text-slate-500 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100">
                                            <i class="ph-bold ph-clock"></i> 
                                            JP {{ $startJP }} - {{ $endJP }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Kanan: Tombol Aksi -->
                            <div class="w-full md:w-auto z-10">
                                @if($status == 'waiting')
                                    <form action="{{ route('teaching.start', $schedule->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full md:w-auto px-8 py-3.5 {{ $btnClass }} text-white font-bold rounded-xl shadow-lg transition transform hover:-translate-y-0.5 active:scale-95 flex items-center justify-center gap-2">
                                            <i class="ph-bold ph-play-circle text-xl"></i> Mulai Mengajar
                                        </button>
                                    </form>
                                @elseif($status == 'ongoing')
                                    <div class="flex flex-col md:items-end gap-2">
                                        <div class="flex items-center gap-2 text-emerald-600 font-bold text-xs uppercase tracking-wide animate-pulse">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Sedang Berlangsung
                                        </div>
                                        <a href="{{ route('teaching.show', $session->id) }}" class="w-full md:w-auto px-8 py-3.5 {{ $btnClass }} text-white font-bold rounded-xl shadow-lg transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                            Lanjutkan Kelas <i class="ph-bold ph-arrow-right"></i>
                                        </a>
                                    </div>
                                @else
                                    <div class="flex items-center gap-3">
                                        <span class="px-5 py-2.5 bg-slate-100 text-slate-500 font-bold text-sm rounded-xl flex items-center gap-2 border border-slate-200 cursor-not-allowed">
                                            <i class="ph-fill ph-check-circle"></i> Selesai
                                        </span>
                                        <a href="{{ route('teaching.show', $session->id) }}" class="p-2.5 bg-white border border-slate-200 rounded-xl text-slate-400 hover:text-blue-600 hover:border-blue-200 transition shadow-sm" title="Lihat Detail">
                                            <i class="ph-bold ph-eye text-xl"></i>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-20 bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200">
                    <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                        <i class="ph-duotone ph-coffee text-5xl"></i>
                    </div>
                    <h3 class="text-slate-800 font-bold text-xl mb-2">Tidak Ada Jadwal Mengajar</h3>
                    <p class="text-slate-500 max-w-xs mx-auto text-sm leading-relaxed">
                        Hari ini Anda tidak memiliki jadwal kelas. Nikmati waktu istirahat atau persiapkan materi untuk esok!
                    </p>
                </div>
            @endif
        </div>
    </div>
    
    <style>
        @keyframes wave {
            0% { transform: rotate(0deg); }
            10% { transform: rotate(14deg); }
            20% { transform: rotate(-8deg); }
            30% { transform: rotate(14deg); }
            40% { transform: rotate(-4deg); }
            50% { transform: rotate(10deg); }
            60% { transform: rotate(0deg); }
            100% { transform: rotate(0deg); }
        }
        .animate-wave {
            animation: wave 2.5s infinite;
            transform-origin: 70% 70%;
        }
    </style>
</x-app-layout>