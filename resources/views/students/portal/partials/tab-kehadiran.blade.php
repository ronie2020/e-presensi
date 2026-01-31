<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-in fade-in duration-500">
    
    {{-- KOLOM KIRI: STATUS UTAMA (Sticky) --}}
    <div class="lg:col-span-1">
        <div class="bg-white p-6 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 sticky top-24 relative overflow-hidden group text-center">
            
            {{-- Background Decor --}}
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-400 via-indigo-500 to-purple-500"></div>
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-blue-50 rounded-full blur-3xl opacity-50 pointer-events-none"></div>

            <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-6">Persentase Kehadiran</h3>

            {{-- Circular Indicator Wrapper --}}
            <div class="relative w-56 h-56 mx-auto mb-6">
                {{-- Kita pertahankan Canvas untuk Chart.js agar logika JS yang ada tidak rusak --}}
                {{-- Namun kita bungkus agar rapi --}}
                <div class="absolute inset-0 flex items-center justify-center z-10 pointer-events-none">
                    <div class="text-center">
                        <span class="text-5xl font-black text-slate-800 tracking-tighter block">{{ $attendancePercentage }}<span class="text-2xl text-slate-400">%</span></span>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mt-1 block">Semester Ini</span>
                    </div>
                </div>
                {{-- Canvas Chart (Pastikan script JS chart di layout induk tetap jalan) --}}
                <canvas id="attendanceChart" class="relative z-0 opacity-90"></canvas>
            </div>

            {{-- Progress Bar Kelayakan --}}
            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 relative z-10">
                <div class="flex justify-between items-end mb-2">
                    <span class="text-[10px] font-bold text-slate-400 uppercase">Target Sekolah</span>
                    <span class="text-xs font-black text-slate-700">Min. 80%</span>
                </div>
                
                <div class="w-full bg-slate-200 rounded-full h-2.5 overflow-hidden mb-2">
                    <div class="h-full rounded-full transition-all duration-1000 shadow-[0_0_10px_currentColor] 
                        {{ $attendancePercentage >= 90 ? 'bg-emerald-500 text-emerald-500' : ($attendancePercentage >= 80 ? 'bg-amber-500 text-amber-500' : 'bg-rose-500 text-rose-500') }}" 
                        style="width: {{ $attendancePercentage }}%">
                    </div>
                </div>

                <p class="text-[10px] font-medium leading-relaxed">
                    @if($attendancePercentage >= 90)
                        <span class="text-emerald-600 flex items-center justify-center gap-1"><i class="ph-fill ph-check-circle"></i> Luar biasa! Pertahankan kehadiranmu.</span>
                    @elseif($attendancePercentage >= 80)
                        <span class="text-amber-600 flex items-center justify-center gap-1"><i class="ph-fill ph-warning"></i> Hati-hati, jangan bolos lagi ya.</span>
                    @else
                        <span class="text-rose-600 flex items-center justify-center gap-1"><i class="ph-fill ph-warning-octagon"></i> Bahaya! Segera temui Guru BK/Wali Kelas.</span>
                    @endif
                </p>
            </div>
        </div>
    </div>

    {{-- KOLOM KANAN: STATISTIK DETAIL & TIMELINE --}}
    <div class="lg:col-span-2 space-y-6">
        
        {{-- 1. GRID STATISTIK DETAIL --}}
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            
            {{-- Hadir --}}
            <div class="bg-white p-4 rounded-[2rem] border border-emerald-100 shadow-sm flex flex-col items-center text-center group hover:bg-emerald-50/50 transition-colors relative overflow-hidden">
                <div class="absolute top-0 right-0 p-3 opacity-10 group-hover:scale-110 transition-transform"><i class="ph-fill ph-check-circle text-4xl text-emerald-500"></i></div>
                <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mb-2 shadow-sm">
                    <i class="ph-bold ph-user-check text-xl"></i>
                </div>
                <span class="text-2xl font-black text-slate-800">{{ $hadir - $terlambat }}</span>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tepat Waktu</span>
            </div>

            {{-- Terlambat --}}
            <div class="bg-white p-4 rounded-[2rem] border border-amber-100 shadow-sm flex flex-col items-center text-center group hover:bg-amber-50/50 transition-colors relative overflow-hidden">
                <div class="absolute top-0 right-0 p-3 opacity-10 group-hover:scale-110 transition-transform"><i class="ph-fill ph-clock-countdown text-4xl text-amber-500"></i></div>
                <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center mb-2 shadow-sm">
                    <i class="ph-bold ph-clock-afternoon text-xl"></i>
                </div>
                <span class="text-2xl font-black text-slate-800">{{ $terlambat }}</span>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Terlambat</span>
            </div>

            {{-- Sakit --}}
            <div class="bg-white p-4 rounded-[2rem] border border-blue-100 shadow-sm flex flex-col items-center text-center group hover:bg-blue-50/50 transition-colors relative overflow-hidden">
                <div class="absolute top-0 right-0 p-3 opacity-10 group-hover:scale-110 transition-transform"><i class="ph-fill ph-thermometer text-4xl text-blue-500"></i></div>
                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mb-2 shadow-sm">
                    <i class="ph-bold ph-bandaids text-xl"></i>
                </div>
                <span class="text-2xl font-black text-slate-800">{{ $sakit }}</span>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Sakit</span>
            </div>

            {{-- Izin --}}
            <div class="bg-white p-4 rounded-[2rem] border border-purple-100 shadow-sm flex flex-col items-center text-center group hover:bg-purple-50/50 transition-colors relative overflow-hidden">
                <div class="absolute top-0 right-0 p-3 opacity-10 group-hover:scale-110 transition-transform"><i class="ph-fill ph-envelope-open text-4xl text-purple-500"></i></div>
                <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center mb-2 shadow-sm">
                    <i class="ph-bold ph-paper-plane-tilt text-xl"></i>
                </div>
                <span class="text-2xl font-black text-slate-800">{{ $izin }}</span>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Izin</span>
            </div>

            {{-- Alpa (Lebar di Mobile) --}}
            <div class="col-span-2 md:col-span-2 bg-white p-4 rounded-[2rem] border border-rose-100 shadow-sm flex flex-row items-center justify-between px-6 group hover:bg-rose-50/50 transition-colors relative overflow-hidden">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center shadow-sm shrink-0">
                        <i class="ph-bold ph-x-circle text-2xl"></i>
                    </div>
                    <div class="text-left">
                        <span class="text-3xl font-black text-rose-600 block leading-none">{{ $alpa }}</span>
                        <span class="text-[10px] font-bold text-rose-400 uppercase tracking-wider">Tanpa Keterangan</span>
                    </div>
                </div>
                
                @if($alpa > 0)
                    <div class="text-right">
                        <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-rose-100 text-rose-700 text-[10px] font-black uppercase rounded-lg border border-rose-200">
                            <i class="ph-bold ph-warning"></i> -{{ $alpa * 10 }} Poin
                        </span>
                    </div>
                @else
                    <div class="text-right hidden sm:block">
                        <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase rounded-lg border border-emerald-200">
                            <i class="ph-bold ph-thumbs-up"></i> Disiplin
                        </span>
                    </div>
                @endif
            </div>
        </div>

        {{-- 2. TIMELINE RIWAYAT --}}
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-6 md:p-8">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-50">
                <h4 class="font-black text-slate-800 flex items-center gap-2 text-lg">
                    <i class="ph-duotone ph-clock-counter-clockwise text-blue-600 text-xl"></i> 
                    Log Kehadiran
                </h4>
                <span class="text-[10px] font-bold bg-slate-50 border border-slate-200 px-3 py-1 rounded-full text-slate-500">
                    Terakhir
                </span>
            </div>

            <div class="relative pl-4 space-y-6">
                {{-- Garis Timeline Vertical --}}
                <div class="absolute left-4 top-2 bottom-4 w-0.5 bg-slate-100 -ml-[0.5px]"></div>

                @forelse($attendance_history as $index => $log)
                    <div class="relative pl-8 group">
                        {{-- Dot Timeline --}}
                        <div class="absolute left-[11px] top-1.5 w-3 h-3 rounded-full border-2 border-white shadow-sm z-10
                            {{ ($log->status == 'Hadir') ? 'bg-emerald-500 ring-4 ring-emerald-50' : 
                               (($log->status == 'Terlambat') ? 'bg-amber-500 ring-4 ring-amber-50' : 
                               (($log->status == 'Sakit') ? 'bg-blue-500 ring-4 ring-blue-50' : 
                               (($log->status == 'Izin') ? 'bg-purple-500 ring-4 ring-purple-50' : 'bg-rose-500 ring-4 ring-rose-50'))) }}">
                        </div>

                        <div class="bg-white rounded-2xl p-4 border border-slate-100 hover:shadow-md hover:border-slate-200 transition-all duration-300">
                            
                            {{-- Header Card --}}
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <p class="font-bold text-slate-800 text-sm">
                                        {{ \Carbon\Carbon::parse($log->attendance_date)->translatedFormat('l, d F Y') }}
                                    </p>
                                    {{-- Badge Status --}}
                                    <span class="inline-block mt-1 px-2.5 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wide border
                                        {{ ($log->status == 'Hadir') ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 
                                           (($log->status == 'Terlambat') ? 'bg-amber-50 text-amber-600 border-amber-100' : 
                                           (($log->status == 'Sakit') ? 'bg-blue-50 text-blue-600 border-blue-100' : 
                                           (($log->status == 'Izin') ? 'bg-purple-50 text-purple-600 border-purple-100' : 'bg-rose-50 text-rose-600 border-rose-100'))) }}">
                                        {{ $log->status }}
                                    </span>
                                </div>
                                
                                {{-- Icon Besar Pudar --}}
                                <i class="ph-duotone text-3xl opacity-20
                                    {{ ($log->status == 'Hadir') ? 'ph-check-circle text-emerald-600' : 
                                       (($log->status == 'Terlambat') ? 'ph-clock-countdown text-amber-600' : 
                                       (($log->status == 'Sakit') ? 'ph-thermometer text-blue-600' : 
                                       (($log->status == 'Izin') ? 'ph-envelope-open text-purple-600' : 'ph-x-circle text-rose-600'))) }}">
                                </i>
                            </div>

                            {{-- Detail Waktu (Hanya untuk Hadir/Terlambat) --}}
                            @if($log->status == 'Hadir' || $log->status == 'Terlambat')
                                <div class="flex items-center gap-2 bg-slate-50 rounded-xl p-2 border border-slate-100">
                                    <div class="flex-1 text-center border-r border-slate-200 pr-2">
                                        <span class="block text-[9px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">Datang</span>
                                        <span class="text-sm font-black {{ $log->status == 'Terlambat' ? 'text-amber-600' : 'text-slate-700' }}">
                                            {{ $log->time_in ? \Carbon\Carbon::parse($log->time_in)->format('H:i') : '--:--' }}
                                        </span>
                                    </div>
                                    <div class="flex-1 text-center pl-2">
                                        <span class="block text-[9px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">Pulang</span>
                                        <span class="text-sm font-black text-slate-700">
                                            {{ $log->time_out ? \Carbon\Carbon::parse($log->time_out)->format('H:i') : '--:--' }}
                                        </span>
                                    </div>
                                </div>
                            @endif

                            {{-- Catatan Khusus --}}
                            @if($log->notes)
                                <div class="mt-3 flex items-start gap-2">
                                    <i class="ph-fill ph-info text-slate-300 mt-0.5"></i>
                                    <p class="text-xs text-slate-500 italic leading-snug">"{{ Str::limit($log->notes, 80) }}"</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="ph-duotone ph-calendar-slash text-3xl text-slate-300"></i>
                        </div>
                        <p class="text-sm font-bold text-slate-400">Belum ada riwayat kehadiran.</p>
                    </div>
                @endforelse
            </div>
            
            @if($attendance_history->count() >= 5)
                <div class="mt-6 text-center">
                    <button class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-slate-50 text-slate-600 text-xs font-bold hover:bg-slate-100 transition-colors border border-slate-200">
                        Lihat Lebih Banyak <i class="ph-bold ph-caret-down"></i>
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>