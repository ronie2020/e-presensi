<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @if($isAlumni)
        <div class="md:col-span-3 bg-amber-50 border border-amber-200 rounded-3xl p-6 flex flex-col md:flex-row items-center gap-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-amber-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 -mr-16 -mt-16"></div>
            <div class="w-20 h-20 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center text-4xl shrink-0 z-10 shadow-inner">
                <i class="ph-duotone ph-graduation-cap"></i>
            </div>
            <div class="flex-1 text-center md:text-left z-10 w-full">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-xl font-black text-amber-900 mb-1">Status Alumni</h3>
                        <p class="text-amber-800/80 text-sm">
                            Siswa ini dinyatakan <strong>LULUS</strong> pada tahun {{ $student->graduation_year ?? \Carbon\Carbon::parse($student->graduated_date)->year }}.
                        </p>
                    </div>
                    <div class="flex flex-wrap justify-center gap-3">
                        @if($student->alumniProfile)
                            <div class="inline-flex items-center gap-2 px-4 py-2.5 bg-white rounded-xl border border-amber-200 shadow-sm">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wide">Saat ini:</span>
                                <span class="font-bold text-amber-600">{{ $student->alumniProfile->activity_status }}</span>
                            </div>
                        @else
                            <a href="{{ route('alumni.tracer') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-amber-600 hover:bg-amber-700 text-white rounded-xl font-bold shadow-xl shadow-amber-600/30 transition-all animate-bounce hover:animate-none">
                                <i class="ph-bold ph-clipboard-text"></i> Isi Tracer Study
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-lg transition-all duration-300">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition transform group-hover:scale-110"><i class="ph-fill ph-chart-pie-slice text-9xl text-blue-500"></i></div>
            <div class="relative z-10">
                <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-2">Persentase Kehadiran</h3>
                <div class="flex items-baseline gap-2 mb-4">
                    @php 
                        $total_hari = ($hadir ?? 0) + ($sakit ?? 0) + ($izin ?? 0) + ($alpa ?? 0); 
                        $persen = $total_hari > 0 ? round(($hadir/$total_hari)*100) : 0; 
                    @endphp
                    <span class="text-5xl font-black text-slate-800">{{ $persen }}<span class="text-2xl text-slate-400">%</span></span>
                </div>
                <div class="flex gap-2">
                    <span class="px-3 py-1.5 bg-green-50 text-green-700 border border-green-100 rounded-lg text-xs font-bold flex items-center gap-1"><div class="w-1.5 h-1.5 rounded-full bg-green-500"></div> Hadir: {{ $hadir ?? 0 }}</span>
                    <span class="px-3 py-1.5 bg-rose-50 text-rose-700 border border-rose-100 rounded-lg text-xs font-bold flex items-center gap-1"><div class="w-1.5 h-1.5 rounded-full bg-rose-500"></div> Alpa: {{ $alpa ?? 0 }}</span>
                </div>
            </div>
        </div>
    @endif
    
    <!-- Card Poin -->
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-lg transition-all duration-300">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition transform group-hover:scale-110"><i class="ph-fill ph-star text-9xl text-yellow-500"></i></div>
        <div class="relative z-10">
            <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-4">Poin Karakter {{ $isAlumni ? '(Akhir)' : '' }}</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-green-50/50 p-3 rounded-xl border border-green-100/50">
                    <p class="text-[10px] text-green-600 font-bold mb-1 uppercase">Kebaikan</p>
                    <p class="text-3xl font-black text-green-600">+{{ $total_merit_points ?? 0 }}</p>
                </div>
                <div class="bg-rose-50/50 p-3 rounded-xl border border-rose-100/50">
                    <p class="text-[10px] text-rose-600 font-bold mb-1 uppercase">Pelanggaran</p>
                    <p class="text-3xl font-black text-rose-600">-{{ $total_violation_points ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Literasi -->
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-lg transition-all duration-300">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition transform group-hover:scale-110"><i class="ph-fill ph-books text-9xl text-purple-500"></i></div>
        <div class="relative z-10">
            <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-2">Literasi</h3>
            <div class="flex items-baseline gap-2">
                <span class="text-5xl font-black text-slate-800">{{ $library_visits ?? 0 }}</span>
                <span class="text-sm text-slate-400 font-bold bg-slate-100 px-2 py-1 rounded-md">Kunjungan</span>
            </div>
            <p class="mt-4 text-sm text-slate-500 font-medium">"Buku adalah jendela dunia."</p>
        </div>
    </div>
</div>