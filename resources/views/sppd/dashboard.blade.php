<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>

    <div class="py-8 sm:py-10 font-sans text-elevate-text bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="flex items-center justify-between mb-8">
                <div>
                    <a href="{{ route('sppd.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-elevate-primary mb-3 transition-colors group">
                        <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali ke Daftar
                    </a>
                    <h1 class="text-3xl font-extrabold text-elevate-dark flex items-center gap-3">
                        <span class="w-10 h-10 rounded-xl bg-elevate-accent/20 text-elevate-primary flex items-center justify-center">
                            <i class="ph-bold ph-chart-bar text-xl"></i>
                        </span>
                        Dashboard Perjalanan Dinas
                    </h1>
                    <p class="text-slate-500 text-sm mt-1 ml-13">Ringkasan & statistik SPPD tahun {{ date('Y') }}</p>
                </div>
            </div>

            {{-- Stat Cards --}}
            <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
                @php
                    $cards = [
                        ['label'=>'Total SPPD',      'value'=>$stats['total_sppd'],   'icon'=>'ph-car-profile',     'color'=>'elevate-primary', 'bg'=>'bg-elevate-accent/10'],
                        ['label'=>'Bulan Ini',        'value'=>$stats['bulan_ini'],    'icon'=>'ph-calendar-check',  'color'=>'sky-600',         'bg'=>'bg-sky-50'],
                        ['label'=>'Total Hari Dinas', 'value'=>$stats['total_hari'],   'icon'=>'ph-clock-countdown', 'color'=>'violet-600',      'bg'=>'bg-violet-50'],
                        ['label'=>'Belum Selesai',    'value'=>$stats['belum_selesai'],'icon'=>'ph-hourglass',       'color'=>'amber-600',       'bg'=>'bg-amber-50'],
                        ['label'=>'Total Anggaran',   'value'=>'Rp '.number_format($stats['total_biaya'],0,',','.'), 'icon'=>'ph-money','color'=>'emerald-600','bg'=>'bg-emerald-50', 'small'=>true],
                    ];
                @endphp
                @foreach($cards as $card)
                <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-10 h-10 rounded-xl {{ $card['bg'] }} text-{{ $card['color'] }} flex items-center justify-center mb-4">
                        <i class="ph-bold {{ $card['icon'] }} text-xl"></i>
                    </div>
                    <div class="text-{{ isset($card['small']) ? '2xl' : '3xl' }} font-black text-elevate-dark">{{ $card['value'] }}</div>
                    <div class="text-xs text-slate-500 font-bold uppercase tracking-wider mt-1">{{ $card['label'] }}</div>
                </div>
                @endforeach
            </div>

            {{-- Charts Row --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

                {{-- Chart SPPD per Bulan (2/3 width) --}}
                <div class="lg:col-span-2 bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm">
                    <h3 class="font-black text-elevate-dark text-base mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-trend-up text-elevate-primary"></i> SPPD Per Bulan (12 Bulan Terakhir)
                    </h3>
                    <canvas id="chartBulan" height="80"></canvas>
                </div>

                {{-- Chart Distribusi Status (1/3 width) --}}
                <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm">
                    <h3 class="font-black text-elevate-dark text-base mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-chart-donut text-elevate-primary"></i> Status SPPD
                    </h3>
                    <canvas id="chartStatus" height="160"></canvas>
                    <div class="mt-4 space-y-2">
                        @foreach($chartStatus as $item)
                        <div class="flex items-center justify-between text-xs font-bold">
                            <span class="text-slate-600">{{ $item['label'] }}</span>
                            <span class="text-elevate-dark">{{ $item['value'] }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Bottom Row --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Top Pegawai --}}
                <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm">
                    <h3 class="font-black text-elevate-dark text-base mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-medal text-amber-500"></i> Top 5 Pegawai Terbanyak Dinas
                    </h3>
                    @if($topPegawai->count())
                    <div class="space-y-3">
                        @foreach($topPegawai as $i => $pegawai)
                        <div class="flex items-center gap-4 p-3 rounded-2xl bg-slate-50 border border-slate-100">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center font-black text-sm shrink-0
                                {{ $i === 0 ? 'bg-amber-100 text-amber-600' : ($i === 1 ? 'bg-slate-200 text-slate-600' : ($i === 2 ? 'bg-orange-100 text-orange-600' : 'bg-slate-100 text-slate-500')) }}">
                                {{ $i + 1 }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-bold text-elevate-dark text-sm truncate">{{ $pegawai['nama'] }}</div>
                                <div class="text-[10px] text-slate-400 font-medium">{{ $pegawai['total_hari'] }} hari dinas</div>
                            </div>
                            <div class="text-right shrink-0">
                                <div class="font-black text-elevate-primary text-lg">{{ $pegawai['total_dinas'] }}</div>
                                <div class="text-[10px] text-slate-400">SPPD</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-12 text-slate-400"><i class="ph-duotone ph-users text-4xl block mb-2"></i><p class="text-sm italic">Belum ada data</p></div>
                    @endif
                </div>

                {{-- Rekap Bulan Ini --}}
                <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm">
                    <h3 class="font-black text-elevate-dark text-base mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-list-dashes text-elevate-primary"></i> SPPD Bulan Ini ({{ \Carbon\Carbon::now()->translatedFormat('F Y') }})
                    </h3>
                    @if($rekapBulanIni->count())
                    <div class="space-y-2 max-h-80 overflow-y-auto custom-scrollbar pr-1">
                        @foreach($rekapBulanIni as $sppd)
                        @php
                            $colors = ['draft'=>'bg-slate-100 text-slate-500','submitted'=>'bg-amber-50 text-amber-600','approved'=>'bg-sky-50 text-sky-600','selesai'=>'bg-emerald-50 text-emerald-600'];
                        @endphp
                        <div class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 hover:border-slate-200 transition-colors">
                            <div class="flex-1 min-w-0">
                                <div class="font-bold text-elevate-dark text-xs truncate">{{ $sppd->user?->name ?? '-' }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">{{ $sppd->nomor_sppd }}</div>
                                <div class="text-[10px] text-slate-500 flex items-center gap-1 mt-0.5"><i class="ph-fill ph-map-pin text-rose-400"></i> {{ $sppd->tempat_tujuan }}</div>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="px-2 py-1 rounded-lg text-[10px] font-bold {{ $colors[$sppd->status] ?? 'bg-slate-100' }}">{{ $sppd->status_label }}</span>
                                @if($sppd->total_biaya > 0)
                                <div class="text-[10px] font-bold text-emerald-600 mt-1">Rp {{ number_format($sppd->total_biaya,0,',','.') }}</div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-12 text-slate-400"><i class="ph-duotone ph-car-profile text-4xl block mb-2"></i><p class="text-sm italic">Belum ada SPPD bulan ini</p></div>
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
                    backgroundColor: 'rgba(59, 88, 137, 0.15)',
                    borderColor: '#3b5889',
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: 'rgba(0,0,0,0.04)' } },
                    x: { grid: { display: false } }
                }
            }
        });

        // ===== Chart Status Donut =====
        const chartStatusData = @json($chartStatus);
        const statusColors = { slate: '#94a3b8', amber: '#f59e0b', sky: '#0ea5e9', emerald: '#10b981' };
        new Chart(document.getElementById('chartStatus'), {
            type: 'doughnut',
            data: {
                labels: chartStatusData.map(d => d.label),
                datasets: [{
                    data: chartStatusData.map(d => d.value),
                    backgroundColor: chartStatusData.map(d => statusColors[d.color] || '#94a3b8'),
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
