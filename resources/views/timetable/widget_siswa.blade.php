@php
    $hariIni = \Carbon\Carbon::now()->locale('id')->isoFormat('dddd');
    $tanggal = \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y');
    $currentTime = \Carbon\Carbon::now()->format('H:i:s');
@endphp

<div class="bg-white p-6 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden">
    <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-500 to-indigo-600"></div>
    
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-lg font-black text-elevate-dark flex items-center gap-2">
                <i class="ph-fill ph-books text-blue-500"></i> Pelajaran Hari Ini
            </h3>
            <p class="text-xs text-slate-500 font-medium mt-1">{{ $hariIni }}, {{ $tanggal }}</p>
        </div>
        <div class="w-10 h-10 bg-slate-50 rounded-full flex items-center justify-center border border-slate-100 shadow-sm shrink-0">
            <i class="ph-duotone ph-student text-xl text-blue-500"></i>
        </div>
    </div>

    @if(isset($jadwalPelajaranHariIni) && $jadwalPelajaranHariIni->isNotEmpty())
        <div class="relative border-l-2 border-slate-100 ml-2 md:ml-3 space-y-5 mt-2">
            @foreach($jadwalPelajaranHariIni as $jadwal)
                @php
                    $isNow = ($currentTime >= $jadwal->timeslot->start_time && $currentTime <= $jadwal->timeslot->end_time);
                    $isPast = ($currentTime > $jadwal->timeslot->end_time);
                @endphp
                
                <div class="relative pl-6">
                    <div class="absolute -left-[11px] top-1.5 w-5 h-5 rounded-full border-4 border-white {{ $isNow ? 'bg-blue-500 shadow-blue-500/40' : ($isPast ? 'bg-slate-300' : 'bg-indigo-500 shadow-indigo-500/40') }} shadow-sm z-10 flex items-center justify-center">
                        @if($isNow)
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                        @endif
                    </div>
                    
                    <div class="p-4 rounded-2xl border {{ $isNow ? 'bg-blue-50 border-blue-200 shadow-md ring-2 ring-blue-500/20' : 'bg-slate-50 border-slate-100 shadow-sm hover:bg-white transition-colors' }}">
                        <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                            <span class="text-[10px] font-black {{ $isNow ? 'text-blue-700' : 'text-slate-500' }} uppercase tracking-wider bg-white/60 px-2 py-0.5 rounded-md border {{ $isNow ? 'border-blue-200' : 'border-slate-200' }}">
                                {{ $jadwal->timeslot->name }}
                            </span>
                            <span class="text-[10px] font-mono font-bold {{ $isNow ? 'bg-blue-200 text-blue-800' : 'bg-slate-200 text-slate-600' }} px-2 py-0.5 rounded-md">
                                <i class="ph-bold ph-clock"></i> {{ \Carbon\Carbon::parse($jadwal->timeslot->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->timeslot->end_time)->format('H:i') }}
                            </span>
                        </div>
                        <h4 class="text-sm font-black text-elevate-dark mb-1">{{ $jadwal->subject->name }}</h4>
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-600">
                            <i class="ph-fill ph-chalkboard-teacher text-slate-400"></i> Guru: {{ $jadwal->teacher->name }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-10 bg-slate-50 rounded-2xl border border-slate-100 border-dashed">
            <div class="w-16 h-16 bg-white border border-slate-100 rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm">
                <i class="ph-duotone ph-coffee text-3xl text-slate-300"></i>
            </div>
            <h4 class="text-sm font-bold text-slate-600">Tidak Ada Jadwal</h4>
            <p class="text-xs font-medium text-slate-400 mt-1 max-w-[200px] mx-auto">Asyik, hari ini tidak ada pelajaran masuk.</p>
        </div>
    @endif
</div>