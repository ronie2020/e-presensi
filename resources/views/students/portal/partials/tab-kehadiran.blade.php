<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 lg:col-span-1 flex flex-col justify-center items-center relative">
        <div class="h-56 w-full relative mt-2">
            <canvas id="attendanceChart"></canvas>
            <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none pt-4">
                <span class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">TOTAL HARI</span>
                <span class="text-4xl font-black text-slate-800">{{ ($attendanceChart['hadir'] ?? 0) + ($attendanceChart['sakit'] ?? 0) + ($attendanceChart['izin'] ?? 0) + ($attendanceChart['alpa'] ?? 0) }}</span>
            </div>
        </div>
    </div>
    <div class="lg:col-span-2 grid grid-cols-2 gap-4">
        <div class="bg-gradient-to-br from-emerald-50 to-white p-5 rounded-2xl border border-emerald-100 flex flex-col justify-center text-center h-full hover:shadow-md transition-shadow">
            <div class="text-4xl font-black text-emerald-600 mb-1">{{ $hadir ?? 0 }}</div>
            <div class="text-xs font-bold text-emerald-600/70 uppercase tracking-widest">Hadir</div>
        </div>
        <div class="bg-gradient-to-br from-blue-50 to-white p-5 rounded-2xl border border-blue-100 flex flex-col justify-center text-center h-full hover:shadow-md transition-shadow">
            <div class="text-4xl font-black text-blue-600 mb-1">{{ $sakit ?? 0 }}</div>
            <div class="text-xs font-bold text-blue-600/70 uppercase tracking-widest">Sakit</div>
        </div>
        <div class="bg-gradient-to-br from-amber-50 to-white p-5 rounded-2xl border border-amber-100 flex flex-col justify-center text-center h-full hover:shadow-md transition-shadow">
            <div class="text-4xl font-black text-amber-600 mb-1">{{ $izin ?? 0 }}</div>
            <div class="text-xs font-bold text-amber-600/70 uppercase tracking-widest">Izin</div>
        </div>
        <div class="bg-gradient-to-br from-rose-50 to-white p-5 rounded-2xl border border-rose-100 flex flex-col justify-center text-center h-full hover:shadow-md transition-shadow">
            <div class="text-4xl font-black text-rose-600 mb-1">{{ $alpa ?? 0 }}</div>
            <div class="text-xs font-bold text-rose-600/70 uppercase tracking-widest">Alpa</div>
        </div>
    </div>
</div>
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="divide-y divide-gray-100">
        @forelse($attendance_history as $log)
        <div class="px-6 py-4 flex flex-col sm:flex-row items-start sm:items-center justify-between hover:bg-slate-50 transition gap-3">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-full flex items-center justify-center text-lg
                    {{ ($log->status == 'Hadir') ? 'bg-emerald-100 text-emerald-600' : 
                       (($log->status == 'Sakit') ? 'bg-blue-100 text-blue-600' : 
                       (($log->status == 'Izin') ? 'bg-amber-100 text-amber-600' : 'bg-rose-100 text-rose-600')) }}">
                    <i class="ph-fill 
                        {{ ($log->status == 'Hadir') ? 'ph-check' : 
                           (($log->status == 'Sakit') ? 'ph-thermometer' : 
                           (($log->status == 'Izin') ? 'ph-file-text' : 'ph-x')) }}"></i>
                </div>
                <div>
                    <p class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($log->attendance_date)->translatedFormat('l, d F Y') }}</p>
                    <p class="text-xs text-slate-500 font-mono">
                        IN: <span class="font-bold text-slate-700">{{ $log->time_in ? \Carbon\Carbon::parse($log->time_in)->format('H:i') : '--:--' }}</span>
                        | OUT: <span class="font-bold text-slate-700">{{ $log->time_out ? \Carbon\Carbon::parse($log->time_out)->format('H:i') : '--:--' }}</span>
                    </p>
                </div>
            </div>
        </div>
        @empty
        <div class="p-12 text-center text-slate-400">Belum ada data kehadiran bulan ini.</div>
        @endforelse
    </div>
</div>