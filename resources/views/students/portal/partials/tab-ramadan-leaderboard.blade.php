<div class="space-y-8 animate-in fade-in duration-500">
    {{-- Top 3 Podium --}}
    @if($topRamadanStudents->count() >= 3)
    <div class="grid grid-cols-3 gap-2 items-end max-w-2xl mx-auto py-6">
        {{-- Peringkat 2 --}}
        <div class="text-center">
            <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-full border-4 border-slate-300 mx-auto mb-2 overflow-hidden bg-white shadow-lg">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($topRamadanStudents[1]->name) }}&background=cbd5e1&color=64748b" class="w-full h-full object-cover">
            </div>
            <div class="bg-white p-3 rounded-t-2xl shadow-md border-x border-t border-slate-100">
                <p class="text-[10px] font-black text-slate-800 line-clamp-1 capitalize">{{ strtolower($topRamadanStudents[1]->name) }}</p>
                <span class="text-[10px] font-bold text-emerald-600">2nd</span>
            </div>
        </div>
        {{-- Peringkat 1 --}}
        <div class="text-center scale-110">
            <i class="ph-fill ph-crown text-amber-400 text-2xl mb-1 drop-shadow-sm"></i>
            <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full border-4 border-amber-400 mx-auto mb-2 overflow-hidden bg-white shadow-xl ring-4 ring-amber-400/20">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($topRamadanStudents[0]->name) }}&background=fbbf24&color=78350f" class="w-full h-full object-cover">
            </div>
            <div class="bg-white p-4 rounded-t-3xl shadow-xl border-x border-t border-amber-100">
                <p class="text-[10px] font-black text-slate-900 line-clamp-1 capitalize">{{ strtolower($topRamadanStudents[0]->name) }}</p>
                <span class="text-[10px] font-black text-white bg-emerald-600 px-2 py-0.5 rounded-full">Sultan</span>
            </div>
        </div>
        {{-- Peringkat 3 --}}
        <div class="text-center">
            <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-full border-4 border-amber-700/30 mx-auto mb-2 overflow-hidden bg-white shadow-lg">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($topRamadanStudents[2]->name) }}&background=d97706&color=ffffff" class="w-full h-full object-cover">
            </div>
            <div class="bg-white p-3 rounded-t-2xl shadow-md border-x border-t border-slate-100">
                <p class="text-[10px] font-black text-slate-800 line-clamp-1 capitalize">{{ strtolower($topRamadanStudents[2]->name) }}</p>
                <span class="text-[10px] font-bold text-emerald-600">3rd</span>
            </div>
        </div>
    </div>
    @endif

    {{-- List Lainnya --}}
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-50 bg-slate-50/50">
            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest">Papan Peringkat Kebaikan (Top 10)</h3>
        </div>
        <div class="divide-y divide-slate-50">
            @foreach($topRamadanStudents as $index => $s)
            <div class="flex items-center justify-between p-4 {{ Auth::guard('student')->id() == $s->id ? 'bg-emerald-50/50' : '' }}">
                <div class="flex items-center gap-4">
                    <span class="w-6 text-xs font-black text-slate-300">#{{ $index + 1 }}</span>
                    <div class="w-10 h-10 rounded-full overflow-hidden border border-slate-100">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($s->name) }}&size=100" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-800 capitalize">{{ strtolower($s->name) }}</p>
                        <p class="text-[10px] text-slate-400 font-bold uppercase">{{ $s->schoolClass->name ?? 'Kelas' }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm font-black text-emerald-600">{{ number_format($s->ramadan_points, 0, ',', '.') }}</p>
                    <p class="text-[9px] font-bold text-slate-400 uppercase">Points</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>