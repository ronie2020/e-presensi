<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>

    <div class="py-8 sm:py-10 font-sans bg-[#020b18] text-slate-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- HERO SECTION --}}
            <div class="mb-8 relative z-10">
                <x-hero-section
                    badge="ANALITIK PERJALANAN DINAS"
                    badgeIcon="ph-fill ph-chart-bar"
                    showcaseIcon="ph-duotone ph-airplane-takeoff"
                    showcaseTitle="Dashboard SPPD"
                    showcaseSubtitle="Statistik & Anggaran {{ date('Y') }}">
                    <x-slot:title>
                        <span class="block text-slate-100">Dashboard & Statistik</span>
                        <span class="block mt-1 sm:mt-1.5 text-transparent bg-clip-text bg-gradient-to-r from-[#56bbf1] via-sky-200 to-[#38bdf8]">
                            Perjalanan Dinas
                        </span>
                    </x-slot:title>
                    <x-slot:description>
                        Ringkasan komprehensif statistik penugasan dinas, frekuensi bulanan, dan total realisasi anggaran sekolah.
                    </x-slot:description>
                    <x-slot:chips>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                            <i class="ph-bold ph-chart-line-up text-sky-400"></i> Tren 12 Bulan
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                            <i class="ph-bold ph-wallet text-emerald-400"></i> Realisasi Biaya
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                            <i class="ph-bold ph-clock text-cyan-400"></i> Hari Dinas
                        </span>
                    </x-slot:chips>
                    <x-slot:cta>
                        <a href="{{ route('sppd.index') }}"
                           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-slate-800/80 hover:bg-slate-700 text-slate-300 hover:text-white font-bold text-xs border border-slate-700 transition-all duration-300">
                            <i class="ph-bold ph-arrow-left"></i>
                            <span>Kembali ke Daftar</span>
                        </a>
                    </x-slot:cta>
                    <x-slot:showcaseStats>
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-2xl bg-slate-900/80 border border-slate-700/80 backdrop-blur-md">
                            <span class="text-xs font-bold text-slate-300">Total:</span>
                            <span class="text-sm font-black text-white font-mono">{{ $stats['total_sppd'] }}</span>
                        </div>
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-2xl bg-slate-900/80 border border-emerald-500/30 backdrop-blur-md">
                            <span class="text-xs font-bold text-slate-300">Biaya:</span>
                            <span class="text-sm font-black text-emerald-400 font-mono">Rp {{ number_format($stats['total_biaya'],0,',','.') }}</span>
                        </div>
                    </x-slot:showcaseStats>
                </x-hero-section>
            </div>

            {{-- Stat Cards --}}
            <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
                @php
                    $cards = [
                        ['label'=>'Total SPPD',      'value'=>$stats['total_sppd'],   'icon'=>'ph-car-profile',     'color'=>'text-[#56bbf1]',  'bg'=>'bg-[#56bbf1]/10 border border-[#56bbf1]/20'],
                        ['label'=>'Bulan Ini',        'value'=>$stats['bulan_ini'],    'icon'=>'ph-calendar-check',  'color'=>'text-sky-400',    'bg'=>'bg-sky-500/10 border border-sky-500/20'],
                        ['label'=>'Total Hari Dinas', 'value'=>$stats['total_hari'],   'icon'=>'ph-clock-countdown', 'color'=>'text-violet-400', 'bg'=>'bg-violet-500/10 border border-violet-500/20'],
                        ['label'=>'Belum Selesai',    'value'=>$stats['belum_selesai'],'icon'=>'ph-hourglass',       'color'=>'text-amber-400',  'bg'=>'bg-amber-500/10 border border-amber-500/20'],
                        ['label'=>'Total Anggaran',   'value'=>'Rp '.number_format($stats['total_biaya'],0,',','.'), 'icon'=>'ph-money','color'=>'text-emerald-400','bg'=>'bg-emerald-500/10 border border-emerald-500/20', 'small'=>true],
                    ];
                @endphp
                @foreach($cards as $card)
                <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2rem] p-6 border border-white/10 shadow-2xl backdrop-blur-xl">
                    <div class="w-10 h-10 rounded-xl {{ $card['bg'] }} {{ $card['color'] }} flex items-center justify-center mb-4">
                        <i class="ph-bold {{ $card['icon'] }} text-xl"></i>
                    </div>
                    <div class="text-{{ isset($card['small']) ? '2xl' : '3xl' }} font-black text-white">{{ $card['value'] }}</div>
                    <div class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-1">{{ $card['label'] }}</div>
                </div>
                @endforeach
            </div>

            {{-- Charts Row --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

                {{-- Chart SPPD per Bulan (2/3 width) --}}
                <div class="lg:col-span-2 bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2rem] p-6 border border-white/10 shadow-2xl backdrop-blur-xl">
                    <h3 class="font-black text-white text-base mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-trend-up text-[#56bbf1]"></i> SPPD Per Bulan (12 Bulan Terakhir)
                    </h3>
                    <canvas id="chartBulan" height="80"></canvas>
                </div>

                {{-- Chart Distribusi Status (1/3 width) --}}
                <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2rem] p-6 border border-white/10 shadow-2xl backdrop-blur-xl">
                    <h3 class="font-black text-white text-base mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-chart-donut text-[#56bbf1]"></i> Status SPPD
                    </h3>
                    <canvas id="chartStatus" height="160"></canvas>
                    <div class="mt-4 space-y-2">
                        @foreach($chartStatus as $item)
                        <div class="flex items-center justify-between text-xs font-bold">
                            <span class="text-slate-400">{{ $item['label'] }}</span>
                            <span class="text-white">{{ $item['value'] }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Bottom Row --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Top Pegawai --}}
                <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2rem] p-6 border border-white/10 shadow-2xl backdrop-blur-xl">
                    <h3 class="font-black text-white text-base mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-medal text-amber-400"></i> Top 5 Pegawai Terbanyak Dinas
                    </h3>
                    @if($topPegawai->count())
                    <div class="space-y-3">
                        @foreach($topPegawai as $i => $pegawai)
                        <div class="flex items-center gap-4 p-3 rounded-2xl bg-slate-900/60 border border-white/10">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center font-black text-sm shrink-0
                                {{ $i === 0 ? 'bg-amber-500/20 text-amber-400 border border-amber-500/30' : ($i === 1 ? 'bg-slate-700 text-slate-300 border border-slate-600' : ($i === 2 ? 'bg-orange-500/20 text-orange-400 border border-orange-500/30' : 'bg-slate-800 text-slate-400 border border-slate-700')) }}">
                                {{ $i + 1 }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-bold text-white text-sm truncate">{{ $pegawai['nama'] }}</div>
                                <div class="text-[10px] text-slate-400 font-medium">{{ $pegawai['total_hari'] }} hari dinas</div>
                            </div>
                            <div class="text-right shrink-0">
                                <div class="font-black text-[#56bbf1] text-lg">{{ $pegawai['total_dinas'] }}</div>
                                <div class="text-[10px] text-slate-400">SPPD</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-12 text-slate-500"><i class="ph-duotone ph-users text-4xl block mb-2 opacity-50"></i><p class="text-sm italic">Belum ada data</p></div>
                    @endif
                </div>

                {{-- Rekap Bulan Ini --}}
                <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2rem] p-6 border border-white/10 shadow-2xl backdrop-blur-xl">
                    <h3 class="font-black text-white text-base mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-list-dashes text-[#56bbf1]"></i> SPPD Bulan Ini ({{ \Carbon\Carbon::now()->translatedFormat('F Y') }})
                    </h3>
                    @if($rekapBulanIni->count())
                    <div class="space-y-2 max-h-80 overflow-y-auto custom-scrollbar pr-1">
                        @foreach($rekapBulanIni as $sppd)
                        @php
                            $colors = ['draft'=>'bg-slate-800 text-slate-300 border border-slate-700','submitted'=>'bg-amber-500/10 text-amber-400 border border-amber-500/20','approved'=>'bg-sky-500/10 text-[#56bbf1] border border-sky-500/20','selesai'=>'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20'];
                        @endphp
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-900/60 border border-white/10 hover:border-white/20 transition-colors">
                            <div class="flex-1 min-w-0">
                                <div class="font-bold text-white text-xs truncate">{{ $sppd->user?->name ?? '-' }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">{{ $sppd->nomor_sppd }}</div>
                                <div class="text-[10px] text-slate-400 flex items-center gap-1 mt-0.5"><i class="ph-fill ph-map-pin text-rose-400"></i> {{ $sppd->tempat_tujuan }}</div>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="px-2 py-1 rounded-lg text-[10px] font-bold {{ $colors[$sppd->status] ?? 'bg-slate-800 text-slate-400' }}">{{ $sppd->status_label }}</span>
                                @if($sppd->total_biaya > 0)
                                <div class="text-[10px] font-bold text-emerald-400 mt-1">Rp {{ number_format($sppd->total_biaya,0,',','.') }}</div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-12 text-slate-500"><i class="ph-duotone ph-car-profile text-4xl block mb-2 opacity-50"></i><p class="text-sm italic">Belum ada SPPD bulan ini</p></div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        // ===== Chart SPPD Per Bulan =====
        const chartBulanData = @json($chartBulan);
        new Chart(document.getElementById('chartBulan'), {
            type: 'bar',
            data: {
                labels: chartBulanData.map(d => d.label),
                datasets: [{
                    label: 'Jumlah SPPD',
                    data: chartBulanData.map(d => d.value),
                    backgroundColor: 'rgba(86, 187, 241, 0.25)',
                    borderColor: '#56bbf1',
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        ticks: { stepSize: 1, color: 'rgba(255, 255, 255, 0.6)' }, 
                        grid: { color: 'rgba(255, 255, 255, 0.08)' } 
                    },
                    x: { 
                        ticks: { color: 'rgba(255, 255, 255, 0.6)' },
                        grid: { display: false } 
                    }
                }
            }
        });

        // ===== Chart Status Donut =====
        const chartStatusData = @json($chartStatus);
        const statusColors = { slate: '#64748b', amber: '#f59e0b', sky: '#56bbf1', emerald: '#10b981' };
        new Chart(document.getElementById('chartStatus'), {
            type: 'doughnut',
            data: {
                labels: chartStatusData.map(d => d.label),
                datasets: [{
                    data: chartStatusData.map(d => d.value),
                    backgroundColor: chartStatusData.map(d => statusColors[d.color] || '#64748b'),
                    borderWidth: 0,
                    hoverOffset: 4,
                }]
            },
            options: {
                responsive: true,
                cutout: '70%',
                plugins: { legend: { display: false } }
            }
        });
    </script>
</x-app-layout>
