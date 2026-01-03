<x-app-layout>
    {{-- 
        LOGIC PENGGUNAAN:
        1. Pastikan Controller mengirimkan data: $schedules (dikelompokkan per hari), $classes, dan $today (hari ini dalam Bhs Indo).
        2. Gunakan AlpineJS untuk interactivity tanpa reload page.
    --}}

    <div class="py-12 font-sans text-slate-800 bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- HEADER SECTION --}}
            <div class="text-center mb-10">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold uppercase tracking-widest mb-4">
                    <i class="ph-fill ph-student"></i> Portal Siswa
                </div>
                <h1 class="text-3xl md:text-4xl font-black text-slate-900 mb-2">Jadwal Pelajaran</h1>
                <p class="text-slate-500 text-lg">Cek mata pelajaran dan gurumu hari ini.</p>
            </div>

            {{-- SEARCH / FILTER KELAS (Jika Siswa belum login atau untuk ortu) --}}
            {{-- Jika sudah login dan ada session kelas, bagian ini bisa di-hide atau disabled --}}
            <div class="max-w-sm mx-auto mb-10">
                <div class="relative group">
                    <div class="absolute inset-0 bg-indigo-200 rounded-2xl blur-lg opacity-50 group-hover:opacity-75 transition-opacity"></div>
                    <form action="" method="GET" class="relative bg-white rounded-2xl p-2 shadow-sm border border-slate-200 flex items-center">
                        <div class="pl-4 text-slate-400">
                            <i class="ph-bold ph-chalkboard-teacher text-xl"></i>
                        </div>
                        <select name="class_id" onchange="this.form.submit()" class="w-full border-none focus:ring-0 text-sm font-bold text-slate-700 bg-transparent cursor-pointer py-3 pl-3 pr-10">
                            <option value="">-- Pilih Kelas Kamu --</option>
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>Kelas {{ $c->name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute right-4 pointer-events-none text-slate-400">
                            <i class="ph-bold ph-caret-down"></i>
                        </div>
                    </form>
                </div>
            </div>

            {{-- MAIN CONTENT --}}
            {{-- Menggunakan x-data untuk Tab Hari --}}
            {{-- Default active tab diset ke hari ini (via PHP) atau default 'Senin' --}}
            @php
                // Logika sederhana untuk menentukan hari ini dalam Bahasa Indonesia
                $days = [
                    'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 
                    'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
                ];
                $todayName = $days[date('l')];
                // Jika hari minggu, default ke senin
                $defaultTab = ($todayName == 'Minggu') ? 'Senin' : $todayName;
            @endphp

            <div x-data="{ activeDay: '{{ $defaultTab }}' }" class="space-y-6">

                {{-- NAVIGASI HARI (Tabs) --}}
                <div class="flex overflow-x-auto pb-4 gap-3 no-scrollbar snap-x">
                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $day)
                        <button @click="activeDay = '{{ $day }}'" 
                            class="snap-start shrink-0 px-6 py-3 rounded-2xl font-bold text-sm transition-all duration-300 border relative overflow-hidden group"
                            :class="activeDay === '{{ $day }}' 
                                ? 'bg-indigo-600 text-white border-indigo-600 shadow-lg shadow-indigo-600/30' 
                                : 'bg-white text-slate-500 border-slate-200 hover:border-indigo-300 hover:text-indigo-600'">
                            
                            {{-- Indikator Hari Ini --}}
                            @if($day == $todayName)
                                <span class="absolute top-1 right-1 flex h-2.5 w-2.5">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-amber-500"></span>
                                </span>
                            @endif
                            
                            <span class="relative z-10">{{ $day }}</span>
                        </button>
                    @endforeach
                </div>

                {{-- CONTENT AREA --}}
                <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 p-6 sm:p-8 min-h-[400px] relative overflow-hidden">
                    
                    {{-- Decoration --}}
                    <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-50 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>

                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $day)
                        <div x-show="activeDay === '{{ $day }}'" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 translate-y-4"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             style="display: none;">
                            
                            <div class="flex items-center justify-between mb-8 relative z-10">
                                <h2 class="text-2xl font-black text-slate-800">{{ $day }}</h2>
                                @if($day == $todayName)
                                    <span class="px-3 py-1 bg-amber-100 text-amber-700 text-xs font-bold rounded-full border border-amber-200">
                                        Hari Ini
                                    </span>
                                @endif
                            </div>

                            {{-- LIST JADWAL (Timeline Style) --}}
                            <div class="relative z-10 space-y-6">
                                {{-- Filter data jadwal berdasarkan hari di sisi Server atau Client --}}
                                {{-- Di sini kita asumsikan $schedules adalah collection yang berisi semua jadwal --}}
                                @php
                                    $daySchedules = $schedules->where('day', $day)->sortBy('start_time');
                                @endphp

                                @forelse($daySchedules as $sched)
                                    <div class="flex group">
                                        {{-- Time Column --}}
                                        <div class="flex flex-col items-center mr-6">
                                            <div class="w-16 py-2 rounded-xl bg-slate-100 text-slate-600 font-black text-xs text-center border border-slate-200 group-hover:bg-indigo-600 group-hover:text-white group-hover:border-indigo-600 transition-colors">
                                                {{ \Carbon\Carbon::parse($sched->start_time)->format('H:i') }}
                                                <div class="w-full h-[1px] bg-slate-300 my-1 opacity-50"></div>
                                                {{ \Carbon\Carbon::parse($sched->end_time)->format('H:i') }}
                                            </div>
                                            {{-- Vertical Line --}}
                                            @if(!$loop->last)
                                                <div class="w-0.5 h-full bg-slate-100 my-2 group-hover:bg-indigo-50 transition-colors"></div>
                                            @endif
                                        </div>

                                        {{-- Detail Card --}}
                                        <div class="flex-1 pb-6">
                                            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:border-indigo-100 transition-all cursor-default group-hover:-translate-y-1 duration-300">
                                                <h3 class="font-bold text-lg text-slate-800 mb-1 group-hover:text-indigo-600 transition-colors">
                                                    {{ $sched->subject->name }}
                                                </h3>
                                                
                                                <div class="flex items-center gap-3 mt-3">
                                                    <div class="flex items-center gap-2 px-3 py-1 rounded-lg bg-slate-50 border border-slate-100">
                                                        <div class="w-5 h-5 rounded-full bg-slate-200 flex items-center justify-center text-[10px] font-bold text-slate-500">
                                                            {{ substr($sched->teacher->name, 0, 1) }}
                                                        </div>
                                                        <span class="text-xs font-bold text-slate-500">{{ $sched->teacher->name }}</span>
                                                    </div>

                                                    {{-- Tampilkan Kelas jika filter "Semua Kelas" aktif --}}
                                                    @if(!request('class_id'))
                                                        <span class="px-2 py-1 rounded-lg bg-indigo-50 text-indigo-600 text-[10px] font-bold border border-indigo-100">
                                                            Kelas {{ $sched->schoolClass->name }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    {{-- Empty State --}}
                                    <div class="text-center py-12">
                                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                                            <i class="ph-duotone ph-coffee text-4xl"></i>
                                        </div>
                                        <h3 class="text-lg font-bold text-slate-700">Tidak ada pelajaran</h3>
                                        <p class="text-slate-400 text-sm">Nikmati waktu istirahatmu!</p>
                                    </div>
                                @endforelse
                            </div>

                        </div>
                    @endforeach
                </div>
                
                {{-- INFO TAMBAHAN (Waktu Sholat / Istirahat - Opsional) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-indigo-900 rounded-3xl p-6 text-white relative overflow-hidden">
                         <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl -translate-y-1/2 translate-x-1/2"></div>
                         <h4 class="font-bold text-indigo-200 mb-1 flex items-center gap-2"><i class="ph-fill ph-clock"></i> Jam Masuk</h4>
                         <p class="text-2xl font-black">07:00 WIB</p>
                         <p class="text-xs text-indigo-300 mt-2 opacity-80">Pastikan hadir 10 menit sebelum bel.</p>
                    </div>
                    <div class="bg-amber-500 rounded-3xl p-6 text-white relative overflow-hidden">
                         <div class="absolute top-0 right-0 w-32 h-32 bg-white/20 rounded-full blur-2xl -translate-y-1/2 translate-x-1/2"></div>
                         <h4 class="font-bold text-amber-100 mb-1 flex items-center gap-2"><i class="ph-fill ph-bell-ringing"></i> Info Libur</h4>
                         <p class="text-sm font-medium leading-relaxed">
                            Cek selalu jadwal khusus untuk mengetahui tanggal merah atau acara sekolah.
                         </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>