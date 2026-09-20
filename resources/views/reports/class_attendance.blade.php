<x-app-layout>
    <div class="py-8 sm:py-10 font-sans text-elevate-dark bg-elevate-surface min-h-screen relative overflow-hidden">
        
        {{-- Efek Latar Belakang Halus --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        {{-- HEADER & FILTER --}}
       <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 relative z-10">
            <x-hero-section
                badge="Rekapitulasi Presensi"
                badgeIcon="ph-chart-bar"
                title="Rekapitulasi Kehadiran"
                titleHighlight="Per Rombel Kelas"
                description="Analisis komposisi kehadiran (Hadir, Telat, Sakit, Izin, Alpha) dan monitoring harian seluruh rombel kelas."
                :chips="[
                    ['icon' => 'ph-calendar', 'label' => \Carbon\Carbon::parse($startDate)->format('d M') . ' - ' . \Carbon\Carbon::parse($endDate)->format('d M Y')],
                    ['icon' => 'ph-chalkboard-teacher', 'label' => count($classes ?? []) . ' Rombel Aktif'],
                    ['icon' => 'ph-file-xls', 'label' => 'Excel & Print Support']
                ]"
                heroIcon="ph-chart-bar"
                :showcaseNumber="count($classes ?? [])"
                showcaseLabel="Total Rombel"
                statusOrb="Data Sinkron"
                statusColor="emerald"
                ctaSecondaryText="Dashboard Utama"
                ctaSecondaryHref="{{ route('dashboard') }}"
                ctaSecondaryIcon="ph-arrow-left"
            >
                <x-slot:cta>
                    <div class="flex flex-wrap items-center gap-3">
                        <form action="{{ route('reports.class') }}" method="GET" class="flex flex-wrap items-center gap-2 bg-white/10 backdrop-blur-md p-2 rounded-xl border border-white/15">
                            <div class="flex items-center gap-2 px-3 py-1.5 bg-white/10 rounded-lg border border-white/15">
                                <span class="text-[10px] font-bold text-sky-200 uppercase">Dari</span>
                                <input type="date" name="start_date" value="{{ $startDate }}" class="border-none p-0 text-xs font-bold text-white focus:ring-0 cursor-pointer bg-transparent">
                            </div>
                            <div class="flex items-center gap-2 px-3 py-1.5 bg-white/10 rounded-lg border border-white/15">
                                <span class="text-[10px] font-bold text-sky-200 uppercase">Sampai</span>
                                <input type="date" name="end_date" value="{{ $endDate }}" class="border-none p-0 text-xs font-bold text-white focus:ring-0 cursor-pointer bg-transparent">
                            </div>
                            <button type="submit" class="bg-gradient-to-r from-[#56bbf1] to-[#0d52a1] text-white px-3.5 py-2 rounded-lg font-bold text-xs transition shadow-md flex items-center justify-center gap-1 active:scale-95 border border-white/20">
                                <i class="ph-bold ph-funnel"></i>
                            </button>
                        </form>

                        <div class="flex gap-2">
                            <a href="{{ route('reports.class.excel', ['start_date' => $startDate, 'end_date' => $endDate]) }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-bold text-xs uppercase tracking-wider shadow-lg shadow-emerald-500/30 flex items-center gap-1.5 border border-white/20 active:scale-95">
                                <i class="ph-bold ph-microsoft-excel-logo text-base"></i> <span>Excel</span>
                            </a>
                            <a href="{{ route('reports.class.print', request()->all()) }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs uppercase tracking-wider backdrop-blur-md border border-white/20 flex items-center gap-1.5 active:scale-95">
                                <i class="ph-bold ph-printer text-base"></i> <span>Print</span>
                            </a>
                        </div>
                    </div>
                </x-slot:cta>
            </x-hero-section>
        </div>

        {{-- MAIN CONTENT --}}
       <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="bg-elevate-gradient-card border border-slate-100 rounded-[2.5rem] overflow-hidden shadow-xl shadow-slate-200/40">
                <div class="p-6 md:p-8 border-b border-slate-100 bg-white/50 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <h3 class="font-black text-xl text-elevate-dark">Komposisi Kehadiran</h3>
                    <div class="flex flex-wrap gap-4 justify-center bg-white px-4 py-2.5 rounded-2xl border border-slate-100 shadow-sm">
                        <div class="flex items-center gap-1.5 text-xs font-bold uppercase text-slate-500"><span class="w-3 h-3 rounded-full bg-[#107C10]"></span> Hadir</div>
                        <div class="flex items-center gap-1.5 text-xs font-bold uppercase text-slate-500"><span class="w-3 h-3 rounded-full bg-[#D83B01]"></span> Telat</div>
                        <div class="flex items-center gap-1.5 text-xs font-bold uppercase text-slate-500"><span class="w-3 h-3 rounded-full bg-elevate-primary"></span> Izin/Skt</div>
                        <div class="flex items-center gap-1.5 text-xs font-bold uppercase text-slate-500"><span class="w-3 h-3 rounded-full bg-[#D13438]"></span> Alpha</div>
                        <div class="flex items-center gap-1.5 text-xs font-bold uppercase text-slate-500"><span class="w-3 h-3 rounded-full bg-slate-300"></span> Tdk Absen</div>
                    </div>
                </div>

              <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse bg-white">
                        <thead class="bg-elevate-soft/50 text-xs font-bold text-elevate-primary uppercase tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="px-8 py-5 w-48 rounded-tl-3xl">Nama Kelas</th>
                                <th class="px-6 py-5 w-1/4">Grafik Komposisi</th>
                                <th class="px-2 py-5 text-center text-[#107C10]">Hadir</th>
                                <th class="px-2 py-5 text-center text-[#D83B01]">Telat</th>
                                <th class="px-2 py-5 text-center text-elevate-primary">Izin</th>
                                <th class="px-2 py-5 text-center text-[#D13438]">Alpha</th>
                                <th class="px-2 py-5 text-center text-slate-400">N/A</th>
                                <th class="px-8 py-5 text-right rounded-tr-3xl">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($reportData as $data)
                                @php
                                    $hadir = data_get($data, 'hadir', 0);
                                    $telat = data_get($data, 'telat', 0);
                                    $izin  = data_get($data, 'izin_sakit', 0);
                                    $alpha = data_get($data, 'alpha', 0);
                                    
                                    $logsCount = $hadir + $telat + $izin + $alpha;
                                    $divider = $logsCount > 0 ? $logsCount : 1; 

                                    $pctHadir = round(($hadir / $divider) * 100, 1);
                                    $pctTelat = round(($telat / $divider) * 100, 1);
                                    $pctIzin  = round(($izin / $divider) * 100, 1);
                                    $pctAlpha = round(($alpha / $divider) * 100, 1);
                                    
                                    $pctNA = 0; 
                                @endphp

                                <tr class="group hover:bg-elevate-soft/30 transition-colors">
                                    {{-- Nama Kelas --}}
                                    <td class="px-8 py-5">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 rounded-2xl bg-elevate-peach-light/50 text-elevate-peach-dark font-black text-sm flex items-center justify-center border border-elevate-peach group-hover:bg-elevate-primary group-hover:text-white group-hover:border-elevate-primary transition-colors shadow-sm">
                                                {{ substr(data_get($data, 'name', '??'), 0, 2) }}
                                            </div>
                                           <div>
                                                <div class="font-black text-elevate-dark text-lg">{{ data_get($data, 'name', 'Kelas ?') }}</div>
                                                <div class="text-[10px] text-elevate-primary font-bold uppercase tracking-wide">{{ data_get($data, 'total_students', 0) }} Siswa</div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Stacked Progress Bar --}}
                                    <td class="px-6 py-5 align-middle">
                                       <div class="flex w-full h-3 bg-elevate-soft rounded-full overflow-hidden shadow-inner">
                                            @if($pctHadir > 0)<div style="width: {{ $pctHadir }}%" class="bg-[#107C10] hover:opacity-80 transition-all" title="Hadir: {{ $pctHadir }}%"></div>@endif
                                            @if($pctTelat > 0)<div style="width: {{ $pctTelat }}%" class="bg-[#D83B01] hover:opacity-80 transition-all" title="Telat: {{ $pctTelat }}%"></div>@endif
                                            @if($pctIzin > 0)<div style="width: {{ $pctIzin }}%" class="bg-elevate-primary hover:opacity-80 transition-all" title="Izin/Sakit: {{ $pctIzin }}%"></div>@endif
                                            @if($pctAlpha > 0)<div style="width: {{ $pctAlpha }}%" class="bg-[#D13438] hover:opacity-80 transition-all" title="Alpha: {{ $pctAlpha }}%"></div>@endif
                                        </div>
                                        <div class="flex justify-between mt-2 text-[10px] font-bold text-slate-400">
                                            <span>0%</span>
                                            <span>50%</span>
                                            <span>100%</span>
                                        </div>
                                    </td>

                                    {{-- Persentase Detail --}}
                                    <td class="px-2 py-5 text-center"><div class="flex flex-col"><span class="font-black text-lg text-[#107C10]">{{ $pctHadir }}%</span><span class="text-[10px] font-bold text-slate-400">{{ $hadir }}</span></div></td>
                                    <td class="px-2 py-5 text-center"><div class="flex flex-col"><span class="font-black text-lg text-[#D83B01]">{{ $pctTelat }}%</span><span class="text-[10px] font-bold text-slate-400">{{ $telat }}</span></div></td>
                                    <td class="px-2 py-5 text-center"><div class="flex flex-col"><span class="font-black text-lg text-elevate-primary">{{ $pctIzin }}%</span><span class="text-[10px] font-bold text-slate-400">{{ $izin }}</span></div></td>
                                    <td class="px-2 py-5 text-center"><div class="flex flex-col"><span class="font-black text-lg text-[#D13438]">{{ $pctAlpha }}%</span><span class="text-[10px] font-bold text-slate-400">{{ $alpha }}</span></div></td>
                                    <td class="px-2 py-5 text-center border-l border-slate-100 border-dashed"><div class="flex flex-col"><span class="font-black text-lg text-slate-400">{{ $pctNA > 0 ? $pctNA.'%' : '-' }}</span><span class="text-[10px] font-bold text-slate-300">N/A</span></div></td>

                                    {{-- Action Button --}}
                                    <td class="px-8 py-5 text-right">
                                        <a href="{{ route('reports.class.detail', ['class_id' => data_get($data, 'id'), 'month' => \Carbon\Carbon::parse($startDate)->format('Y-m')]) }}" 
                                           class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-white border border-slate-200 rounded-xl text-xs font-bold text-elevate-dark hover:text-white hover:border-elevate-primary hover:bg-elevate-primary transition-all shadow-sm group-hover:shadow-md">
                                            <span>Lihat Harian</span><i class="ph-bold ph-caret-right"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan=\"8\" class=\"px-6 py-16 text-center text-slate-400\">
                                        <div class=\"flex flex-col items-center justify-center\">
                                            <div class="w-20 h-20 bg-elevate-soft rounded-full flex items-center justify-center mx-auto mb-4 text-elevate-primary"><i class=\"ph-duotone ph-chalkboard-teacher text-4xl\"></i></div>
                                            <p class=\"font-bold\">Belum ada data kelas atau absensi pada periode ini.</p>\n                                        </div>\n                                    </td>\n                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{-- Footer Info --}}
                <div class="bg-white/50 p-6 border-t border-slate-100 text-center rounded-b-[2.5rem]">
                    <p class="text-sm font-semibold text-slate-400">
                        <i class="ph-bold ph-info"></i> Data ditampilkan berdasarkan rekapitulasi kehadiran siswa.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>