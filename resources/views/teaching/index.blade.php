<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Jadwal Mengajar Saya') }}
        </h2>
    </x-slot>

    <div class="py-6">
        
        <!-- ===> TAMBAHAN: BLOK NOTIFIKASI ERROR/SUKSES <=== -->
        @if(session('success'))
            <div class="mb-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-green-50 border border-green-200 text-green-700 p-4 rounded-xl flex items-center gap-3 shadow-sm">
                    <i class="ph-fill ph-check-circle text-xl"></i>
                    <span class="font-bold text-sm">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-xl flex items-center gap-3 shadow-sm">
                    <i class="ph-fill ph-warning-circle text-xl"></i>
                    <div>
                        <span class="font-bold text-sm block">Terjadi Kesalahan:</span>
                        <span class="text-sm">{{ session('error') }}</span>
                    </div>
                </div>
            </div>
        @endif
        <!-- ===> AKHIR TAMBAHAN <=== -->

        <!-- HEADER STATS -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 px-4 sm:px-0">
            <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
                <div class="absolute right-0 top-0 opacity-10 transform translate-x-4 -translate-y-4">
                    <i class="ph-fill ph-chalkboard-teacher text-9xl"></i>
                </div>
                <p class="text-blue-100 text-sm font-medium mb-1">Hari Ini</p>
                <h3 class="text-3xl font-bold">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</h3>
                <p class="mt-4 text-sm bg-white/20 inline-block px-3 py-1 rounded-lg">
                    {{ count($schedules) }} Sesi Pelajaran
                </p>
            </div>
            
            <div class="md:col-span-2 bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-gray-800 text-lg">Selamat Datang, {{ Auth::user()->name }}!</h3>
                    <p class="text-gray-500 text-sm mt-1">Siap mengajar hari ini? Silakan pilih jadwal kelas di bawah untuk memulai sesi.</p>
                </div>
                <div class="hidden md:block">
                    <img src="https://cdn-icons-png.flaticon.com/512/3426/3426653.png" class="w-24 h-24 opacity-80" alt="Teaching">
                </div>
            </div>
        </div>

        <!-- DAFTAR JADWAL -->
        <h3 class="font-bold text-gray-800 text-lg mb-4 flex items-center gap-2 px-4 sm:px-0">
            <i class="ph-fill ph-list-dashes text-blue-600"></i> Daftar Kelas Hari Ini
        </h3>

        @if($schedules->count() > 0)
            <div class="grid grid-cols-1 gap-4 px-4 sm:px-0">
                @foreach($schedules as $schedule)
                    @php
                        // Cek apakah sesi sudah dibuat hari ini
                        $session = \App\Models\TeachingSession::where('schedule_id', $schedule->id)
                                    ->whereDate('date', \Carbon\Carbon::today())
                                    ->first();
                        
                        // Status warna
                        $statusColor = $session ? ($session->status == 'open' ? 'border-l-4 border-green-500' : 'border-l-4 border-gray-400') : 'border-l-4 border-blue-500';
                    @endphp

                    <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all p-5 border border-gray-100 {{ $statusColor }} flex flex-col md:flex-row justify-between items-center gap-4">
                        
                        <!-- Info Jadwal -->
                        <div class="flex items-center gap-4 w-full md:w-auto">
                            <div class="w-14 h-14 rounded-xl flex items-center justify-center text-2xl font-bold shrink-0
                                {{ $session ? ($session->status == 'open' ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-500') : 'bg-blue-50 text-blue-600' }}">
                                {{ substr($schedule->schoolClass->name, 0, 2) }}
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 text-lg">{{ $schedule->subject->name }}</h4>
                                <div class="flex flex-wrap gap-3 text-sm text-gray-500 mt-1">
                                    <span class="flex items-center gap-1 bg-gray-50 px-2 py-0.5 rounded">
                                        <i class="ph-bold ph-chalkboard"></i> Kelas {{ $schedule->schoolClass->name }}
                                    </span>
                                    <span class="flex items-center gap-1 bg-gray-50 px-2 py-0.5 rounded">
                                        <i class="ph-bold ph-clock"></i> {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-3 w-full md:w-auto">
                            @if(!$session)
                                <!-- Belum Mulai -->
                                <form action="{{ route('teaching.start', $schedule->id) }}" method="POST" class="w-full md:w-auto">
                                    @csrf
                                    <button type="submit" class="w-full md:w-auto px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-lg shadow-blue-200 transition flex items-center justify-center gap-2">
                                        <i class="ph-bold ph-play-circle"></i> Mulai Kelas
                                    </button>
                                </form>
                            @elseif($session->status == 'open')
                                <!-- Sedang Berlangsung -->
                                <div class="flex items-center gap-4">
                                    <span class="flex items-center gap-1.5 text-green-600 font-bold text-sm bg-green-50 px-3 py-1.5 rounded-full animate-pulse">
                                        <span class="w-2 h-2 rounded-full bg-green-500"></span> Sedang Berlangsung
                                    </span>
                                    <a href="{{ route('teaching.show', $session->id) }}" class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg shadow-lg shadow-green-200 transition flex items-center gap-2">
                                        Masuk Kelas <i class="ph-bold ph-arrow-right"></i>
                                    </a>
                                </div>
                            @else
                                <!-- Sudah Selesai -->
                                <span class="px-4 py-2 bg-gray-100 text-gray-500 font-bold rounded-lg flex items-center gap-2 cursor-not-allowed">
                                    <i class="ph-fill ph-check-circle"></i> Selesai
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16 bg-white rounded-2xl border-2 border-dashed border-gray-200 mx-4 sm:mx-0">
                <i class="ph-duotone ph-coffee text-5xl text-gray-300 mb-3"></i>
                <h3 class="text-gray-500 font-bold text-lg">Tidak ada jadwal mengajar hari ini.</h3>
                <p class="text-gray-400 text-sm">Nikmati waktu istirahat Anda!</p>
            </div>
        @endif
    </div>
</x-app-layout>