<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Jadwal Mengajar') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- NOTIFIKASI --}}
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <i class="ph-fill ph-check-circle text-xl"></i>
                        <span class="font-bold">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="hover:bg-emerald-100 p-1 rounded-lg"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif

            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <i class="ph-fill ph-warning-circle text-xl"></i>
                        <span class="font-bold">{{ session('error') }}</span>
                    </div>
                    <button @click="show = false" class="hover:bg-rose-100 p-1 rounded-lg"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif

            {{-- HEADER STATS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                {{-- Kartu Hari Ini --}}
                <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-[2rem] p-8 text-white shadow-xl shadow-blue-500/20 relative overflow-hidden group">
                    <div class="absolute right-0 top-0 opacity-10 transform translate-x-8 -translate-y-8 group-hover:scale-110 transition-transform duration-500">
                        <i class="ph-fill ph-calendar-check text-[10rem]"></i>
                    </div>
                    <div class="relative z-10">
                        <p class="text-blue-100 font-medium mb-1 flex items-center gap-2"><i class="ph-bold ph-calendar-blank"></i> Hari Ini</p>
                        <h3 class="text-3xl font-black tracking-tight">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</h3>
                        <div class="mt-6 flex items-center gap-3">
                            <span class="bg-white/20 backdrop-blur-md px-4 py-2 rounded-xl text-sm font-bold border border-white/10 shadow-sm">
                                {{ count($schedules) }} Sesi Pelajaran
                            </span>
                        </div>
                    </div>
                </div>
                
                {{-- Kartu Welcome --}}
                <div class="md:col-span-2 bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 flex items-center justify-between relative overflow-hidden">
                    <div class="absolute inset-0 bg-slate-50/50 opacity-0 md:opacity-100 bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:16px_16px]"></div>
                    <div class="relative z-10 max-w-lg">
                        <h3 class="font-bold text-slate-800 text-2xl mb-2">Halo, {{ Auth::user()->name }}! 👋</h3>
                        <p class="text-slate-500 leading-relaxed">
                            Sudah siap mengajar hari ini? Pastikan jurnal terisi dan absensi siswa tercatat dengan baik. Semangat mencerdaskan bangsa!
                        </p>
                    </div>
                    <div class="hidden lg:block relative z-10">
                        <div class="w-24 h-24 bg-blue-50 rounded-full flex items-center justify-center text-blue-600 animate-bounce-slow">
                            <i class="ph-duotone ph-chalkboard-teacher text-5xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- LIST JADWAL --}}
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-black text-slate-800 text-xl flex items-center gap-2">
                    <span class="bg-blue-100 text-blue-600 w-8 h-8 rounded-lg flex items-center justify-center text-lg"><i class="ph-bold ph-list-dashes"></i></span>
                    Agenda Hari Ini
                </h3>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider bg-slate-100 px-3 py-1 rounded-full">
                    {{ \Carbon\Carbon::now()->format('d M Y') }}
                </span>
            </div>

            @if($schedules->count() > 0)
                <div class="grid grid-cols-1 gap-5">
                    @foreach($schedules as $schedule)
                        @php
                            $session = \App\Models\TeachingSession::where('schedule_id', $schedule->id)
                                        ->whereDate('date', \Carbon\Carbon::today())
                                        ->first();
                            
                            // Logika Status & Warna
                            if (!$session) {
                                $status = 'waiting'; // Belum Mulai
                                $borderClass = 'border-l-4 border-l-blue-500';
                                $bgIcon = 'bg-blue-50 text-blue-600';
                            } elseif ($session->status == 'open') {
                                $status = 'ongoing'; // Sedang Jalan
                                $borderClass = 'border-l-4 border-l-emerald-500 ring-2 ring-emerald-500/20';
                                $bgIcon = 'bg-emerald-50 text-emerald-600';
                            } else {
                                $status = 'done'; // Selesai
                                $borderClass = 'border-l-4 border-l-slate-300 opacity-75 grayscale-[0.5] hover:grayscale-0 transition-all';
                                $bgIcon = 'bg-slate-100 text-slate-500';
                            }
                        @endphp

                        <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all p-6 border border-slate-100 {{ $borderClass }} flex flex-col md:flex-row justify-between items-center gap-6 group">
                            
                            <!-- Kiri: Info Waktu & Kelas -->
                            <div class="flex items-center gap-5 w-full md:w-auto">
                                <div class="flex flex-col items-center justify-center w-20 h-20 rounded-2xl {{ $bgIcon }} shrink-0 shadow-sm">
                                    <span class="text-xs font-bold uppercase tracking-wider opacity-60">Jam Ke</span>
                                    <span class="text-2xl font-black">{{ $loop->iteration }}</span>
                                </div>
                                <div>
                                    <h4 class="font-black text-slate-800 text-xl group-hover:text-blue-600 transition-colors">{{ $schedule->subject->name }}</h4>
                                    <div class="flex flex-wrap gap-3 mt-2">
                                        <span class="flex items-center gap-1.5 text-sm font-bold text-slate-500 bg-slate-50 px-3 py-1 rounded-lg border border-slate-100">
                                            <i class="ph-bold ph-users-three"></i> Kelas {{ $schedule->schoolClass->name }}
                                        </span>
                                        <span class="flex items-center gap-1.5 text-sm font-bold text-slate-500 bg-slate-50 px-3 py-1 rounded-lg border border-slate-100">
                                            <i class="ph-bold ph-clock"></i> {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Kanan: Tombol Aksi -->
                            <div class="w-full md:w-auto">
                                @if($status == 'waiting')
                                    <form action="{{ route('teaching.start', $schedule->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full md:w-auto px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transition transform hover:-translate-y-0.5 active:scale-95 flex items-center justify-center gap-2">
                                            <i class="ph-bold ph-play-circle text-xl"></i> Mulai Mengajar
                                        </button>
                                    </form>
                                @elseif($status == 'ongoing')
                                    <div class="flex flex-col md:items-end gap-2">
                                        <div class="flex items-center gap-2 text-emerald-600 font-bold text-xs uppercase tracking-wide animate-pulse">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Sedang Berlangsung
                                        </div>
                                        <a href="{{ route('teaching.show', $session->id) }}" class="w-full md:w-auto px-8 py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/30 transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                            Lanjutkan Kelas <i class="ph-bold ph-arrow-right"></i>
                                        </a>
                                    </div>
                                @else
                                    <div class="flex items-center gap-3">
                                        <span class="px-5 py-2 bg-slate-100 text-slate-500 font-bold rounded-xl flex items-center gap-2 border border-slate-200 cursor-not-allowed">
                                            <i class="ph-fill ph-check-circle"></i> Selesai
                                        </span>
                                        <a href="{{ route('teaching.show', $session->id) }}" class="p-2 text-slate-400 hover:text-blue-600 transition" title="Lihat Detail">
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
                    <p class="text-slate-500 max-w-xs mx-auto">Hari ini Anda tidak memiliki jadwal kelas. Nikmati waktu istirahat atau persiapkan materi untuk esok!</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>