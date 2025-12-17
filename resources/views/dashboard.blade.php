<x-app-layout>
    {{-- 
        =========================================
        WRAPPER UTAMA (ALPINE.JS)
        =========================================
    --}}
    <div x-data="{ 
            period: new URLSearchParams(window.location.search).get('period') || 'today',
            date: new URLSearchParams(window.location.search).get('date') || new Date().toISOString().split('T')[0],
            loading: false,
            
            updateFilter(newPeriod) {
                this.loading = true;
                this.period = newPeriod;
                window.location.href = '?period=' + this.period + '&date=' + this.date;
            },
            
            printDashboard() {
                window.print();
            }
        }" class="relative space-y-8 min-h-screen pb-10">
        
        {{-- LOADING OVERLAY --}}
        <div x-show="loading" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             class="fixed inset-0 z-50 bg-white/60 backdrop-blur-[2px] flex items-center justify-center"
             style="display: none;">
            <div class="flex flex-col items-center">
                <i class="ph-spinner animate-spin text-4xl text-indigo-600 mb-2"></i>
                <span class="text-sm font-semibold text-gray-600">Memuat data...</span>
            </div>
        </div>

        {{-- HEADER SECTION --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 print:hidden">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                    Dashboard Monitoring
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Ringkasan data kehadiran siswa <span class="font-bold text-indigo-600" x-text="period === 'today' ? 'Hari Ini' : (period === 'week' ? 'Minggu Ini' : 'Bulan Ini')"></span>.
                </p>
            </div>

            {{-- FILTER & ACTION SECTION --}}
            <div class="flex flex-wrap items-center gap-3">
                 <button @click="printDashboard()" class="p-2 bg-white border border-gray-200 rounded-xl text-gray-500 hover:text-indigo-600 hover:border-indigo-200 transition-colors shadow-sm" title="Print Laporan">
                    <i class="ph-printer text-lg"></i>
                </button>

                <div class="bg-white p-1.5 rounded-xl shadow-sm border border-gray-200 flex flex-wrap gap-2">
                    <div class="flex bg-gray-100 rounded-lg p-1">
                        <button @click="updateFilter('today')" 
                            :class="period === 'today' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                            class="px-4 py-2 text-xs font-bold rounded-md transition-all duration-200">
                            Harian
                        </button>
                        <button @click="updateFilter('week')" 
                            :class="period === 'week' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                            class="px-4 py-2 text-xs font-bold rounded-md transition-all duration-200">
                            Mingguan
                        </button>
                        <button @click="updateFilter('month')" 
                            :class="period === 'month' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                            class="px-4 py-2 text-xs font-bold rounded-md transition-all duration-200">
                            Bulanan
                        </button>
                    </div>

                    <div class="flex items-center">
                        <input x-show="period === 'today'" type="date" x-model="date" @change="updateFilter('today')" class="text-xs border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer">
                        <input x-show="period === 'week'" type="week" class="text-xs border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer">
                        <input x-show="period === 'month'" type="month" class="text-xs border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer">
                    </div>
                </div>
            </div>
        </div>

        {{-- BAGIAN 1: KARTU STATISTIK (KPI) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
            @foreach($cards as $card)
            <a href="{{ url('attendance') }}?status={{ $card['filter_status'] }}&period={{ request('period', 'today') }}" 
               class="group relative bg-white p-5 rounded-xl shadow-sm border-l-4 {{ $card['border'] }} hover:shadow-lg hover:-translate-y-1 transition-all duration-300 cursor-pointer">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ $card['title'] }}</p>
                        <h3 class="text-2xl font-black {{ $card['text_color'] }} mt-1 group-hover:scale-105 transition-transform origin-left">
                            {{ $card['value'] }}
                        </h3>
                    </div>
                    <div class="p-2 bg-gray-50 rounded-lg {{ $card['icon_color'] }} group-hover:bg-indigo-50 transition-colors">
                        <i class="{{ $card['icon'] ?? 'ph-hash' }} text-xl"></i>
                    </div>
                </div>
                <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <i class="ph-arrow-up-right text-gray-300 text-xs"></i>
                </div>
            </a>
            @endforeach
        </div>

        {{-- BAGIAN 2: GRAFIK UTAMA --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <div class="xl:col-span-2 space-y-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                                <i class="ph-chart-bar text-indigo-500"></i> Analisis Tren
                            </h3>
                            <p class="text-xs text-gray-400">Statistik harian selama periode ini.</p>
                        </div>
                        <div class="flex gap-4 text-[10px] font-bold uppercase tracking-wide text-gray-500">
                            <div class="flex items-center gap-1"><span class="w-2 h-2 bg-emerald-500 rounded-full"></span> Tepat</div>
                            <div class="flex items-center gap-1"><span class="w-2 h-2 bg-amber-500 rounded-full"></span> Telat</div>
                            <div class="flex items-center gap-1"><span class="w-2 h-2 bg-red-500 rounded-full"></span> Absen</div>
                        </div>
                    </div>
                    <div class="relative h-80 w-full">
                        <canvas id="weeklyChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col h-fit">
                <h3 class="text-lg font-bold text-gray-800 mb-2 flex items-center gap-2">
                    <i class="ph-chart-pie-slice text-purple-500"></i> Komposisi Data
                </h3>
                <p class="text-xs text-gray-400 mb-6">Proporsi status kehadiran saat ini.</p>
                <div class="relative h-64 w-full flex-1 flex items-center justify-center">
                    <canvas id="dailyDonutChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-3xl font-extrabold text-gray-800">{{ $totalStudents }}</span>
                        <span class="text-xs text-gray-400 font-medium">Total Siswa</span>
                    </div>
                </div>
                 <div class="mt-8 grid grid-cols-2 gap-y-3 gap-x-2">
                    <div class="flex items-center justify-between text-xs px-2 py-1 bg-emerald-50 rounded-md border border-emerald-100">
                        <span class="flex items-center gap-1 text-emerald-700 font-semibold"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Hadir</span>
                        <span class="font-bold text-emerald-800">{{ $presentOnTimeCount }}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs px-2 py-1 bg-amber-50 rounded-md border border-amber-100">
                        <span class="flex items-center gap-1 text-amber-700 font-semibold"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Telat</span>
                        <span class="font-bold text-amber-800">{{ $lateCount }}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs px-2 py-1 bg-red-50 rounded-md border border-red-100">
                        <span class="flex items-center gap-1 text-red-700 font-semibold"><span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Absen</span>
                        <span class="font-bold text-red-800">{{ $absentCount }}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs px-2 py-1 bg-purple-50 rounded-md border border-purple-100">
                        <span class="flex items-center gap-1 text-purple-700 font-semibold"><span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span> Sakit</span>
                        <span class="font-bold text-purple-800">{{ $sickPermitCount }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- BAGIAN 3: DETAIL LANJUTAN --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            
            <!-- A. LOG AKTIVITAS TERBARU (LIVE FEED) -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 h-full">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                        <i class="ph-clock-countdown text-blue-500"></i> Aktivitas Terbaru
                    </h3>
                    <span class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">Realtime</span>
                </div>

                @if(count($recentActivities) > 0)
                <div class="relative pl-4 border-l-2 border-gray-100 space-y-6">
                    @foreach($recentActivities as $log)
                    <div class="relative group">
                        <div class="absolute -left-[21px] top-1 h-3 w-3 rounded-full border-2 border-white ring-1 ring-gray-200 {{ $log->status == 'Terlambat' ? 'bg-amber-400' : 'bg-blue-400' }}"></div>
                        
                        <div class="flex items-start justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-gray-100 overflow-hidden flex-shrink-0 border border-gray-200">
                                    <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs font-bold">
                                        {{ substr($log->student->name ?? '?', 0, 1) }}
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800 line-clamp-1">{{ $log->student->name ?? 'Siswa' }}</p>
                                    <p class="text-xs text-gray-500">{{ $log->student->schoolClass->name ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-bold font-mono text-gray-700">
                                    {{ \Carbon\Carbon::parse($log->created_at)->format('H:i') }}
                                </p>
                                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded {{ $log->status == 'Terlambat' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">
                                    {{ $log->status }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="flex flex-col items-center justify-center h-40 text-center text-gray-400">
                    <i class="ph-coffee text-3xl mb-2"></i>
                    <p class="text-xs">Belum ada aktivitas baru</p>
                </div>
                @endif
            </div>

            <!-- B. PERFORMA KELAS (RANKING) -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 h-full">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                        <i class="ph-ranking text-emerald-500"></i> Kelas Terajin
                    </h3>
                    <span class="text-[10px] bg-gray-100 text-gray-500 px-2 py-1 rounded">Top 5</span>
                </div>

                @if(count($classRanks) > 0)
                <div class="space-y-4">
                    @foreach($classRanks as $index => $rank)
                    <div>
                        <div class="flex justify-between items-end mb-1">
                            <span class="text-xs font-bold text-gray-700">
                                <span class="text-gray-400 mr-1">#{{ $index + 1 }}</span> {{ $rank->class_name }}
                            </span>
                            <span class="text-xs font-bold text-emerald-600">{{ $rank->present_count }} Siswa</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            @php $percent = min(100, ($rank->present_count / 40) * 100); @endphp
                            <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                 <div class="flex flex-col items-center justify-center h-40 text-center text-gray-400">
                    <p class="text-xs">Data kelas belum tersedia</p>
                </div>
                @endif
            </div>

            <!-- C. SISWA SERING TERLAMBAT & TERRAJIN (TABS) -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 h-full" x-data="{ tab: 'late' }">
                <!-- Tab Header -->
                 <div class="flex items-center justify-between mb-4 border-b border-gray-100 pb-2">
                    <div class="flex gap-4">
                        <button @click="tab = 'late'" 
                            :class="tab === 'late' ? 'text-amber-600 border-b-2 border-amber-500' : 'text-gray-400 hover:text-gray-600'"
                            class="text-sm font-bold pb-2 transition-all flex items-center gap-2">
                            <i class="ph-warning"></i> Terlambat
                        </button>
                        <button @click="tab = 'diligent'" 
                            :class="tab === 'diligent' ? 'text-blue-600 border-b-2 border-blue-500' : 'text-gray-400 hover:text-gray-600'"
                            class="text-sm font-bold pb-2 transition-all flex items-center gap-2">
                            <i class="ph-medal"></i> Terrajin
                        </button>
                    </div>
                </div>
                
                <!-- CONTENT 1: TERLAMBAT -->
                <div x-show="tab === 'late'" class="overflow-hidden">
                    @if(count($topLateStudents) > 0)
                        <table class="w-full text-sm text-left">
                            <tbody class="divide-y divide-gray-50">
                                @foreach($topLateStudents as $student)
                                <tr class="group hover:bg-gray-50 transition-colors">
                                    <td class="py-3 font-medium text-gray-900 flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-amber-50 flex items-center justify-center text-amber-600 font-bold text-xs group-hover:bg-amber-100 transition-colors">
                                            {{ substr($student->student->name ?? '?', 0, 1) }}
                                        </div>
                                        <div class="truncate max-w-[120px]">
                                            <div class="text-xs font-bold">{{ $student->student->name ?? 'Siswa' }}</div>
                                            <div class="text-[10px] text-gray-400">{{ $student->student->schoolClass->name ?? '-' }}</div>
                                        </div>
                                    </td>
                                    <td class="py-3 text-right">
                                        <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2 py-1 rounded border border-amber-100">
                                            {{ $student->total_late }}x
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center py-6 text-gray-400 text-xs">Tidak ada data terlambat</div>
                    @endif
                </div>

                <!-- CONTENT 2: TERRAJIN -->
                <div x-show="tab === 'diligent'" class="overflow-hidden" style="display: none;">
                    @if(count($topPunctualStudents) > 0)
                        <table class="w-full text-sm text-left">
                            <tbody class="divide-y divide-gray-50">
                                @foreach($topPunctualStudents as $student)
                                <tr class="group hover:bg-gray-50 transition-colors">
                                    <td class="py-3 font-medium text-gray-900 flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 font-bold text-xs group-hover:bg-blue-100 transition-colors">
                                            {{ substr($student->student->name ?? '?', 0, 1) }}
                                        </div>
                                        <div class="truncate max-w-[120px]">
                                            <div class="text-xs font-bold">{{ $student->student->name ?? 'Siswa' }}</div>
                                            <div class="text-[10px] text-gray-400">{{ $student->student->schoolClass->name ?? '-' }}</div>
                                        </div>
                                    </td>
                                    <td class="py-3 text-right">
                                        <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded border border-blue-100">
                                            {{ $student->total_present }}x
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center py-6 text-gray-400 text-xs">Belum ada data rajin</div>
                    @endif
                </div>
            </div>

        </div>

    </div>

    {{-- SCRIPT CHART.JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script>
        Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
        Chart.defaults.color = '#64748b';
        Chart.defaults.scale.grid.color = '#f1f5f9';

        const rawPresentData = @json($weeklyPresentData ?? []);
        const rawLateData    = @json($weeklyLateData ?? []);
        const rawAbsentData  = @json($weeklyAbsentData ?? []);
        const chartLabels    = @json($chartLabels ?? []);

        const d_presentOnTime = @json($presentOnTimeCount ?? 0);
        const d_late          = @json($lateCount ?? 0);
        const d_absent        = @json($absentCount ?? 0); 
        const d_excused       = @json($sickPermitCount ?? 0);
        const d_alpha         = @json($alphaCount ?? 0);

        const ctxWeekly = document.getElementById('weeklyChart').getContext('2d');
        new Chart(ctxWeekly, {
            type: 'bar',
            data: {
                labels: chartLabels,
                datasets: [
                    { label: 'Hadir Tepat', data: rawPresentData, backgroundColor: '#10b981', hoverBackgroundColor: '#059669', borderRadius: 4, barPercentage: 0.6, stack: 'main' },
                    { label: 'Terlambat', data: rawLateData, backgroundColor: '#f59e0b', hoverBackgroundColor: '#d97706', borderRadius: 4, barPercentage: 0.6, stack: 'main' },
                    { label: 'Tidak Hadir', data: rawAbsentData, backgroundColor: '#ef4444', hoverBackgroundColor: '#dc2626', borderRadius: 4, barPercentage: 0.6, stack: 'main' }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b', padding: 12, titleFont: { size: 13, weight: 'bold' }, bodyFont: { size: 12 }, cornerRadius: 8, displayColors: true,
                        callbacks: { label: function(context) { return ' ' + context.dataset.label + ': ' + context.parsed.y + ' Siswa'; } }
                    }
                }, 
                scales: { 
                    y: { beginAtZero: true, stacked: true, border: { display: false }, grid: { borderDash: [4, 4], drawBorder: false } }, 
                    x: { stacked: true, grid: { display: false, drawBorder: false }, ticks: { font: { size: 11 } } } 
                }
            }
        });

        const ctxDonut = document.getElementById('dailyDonutChart').getContext('2d');
        new Chart(ctxDonut, {
            type: 'doughnut',
            data: {
                labels: ['Hadir Tepat', 'Terlambat', 'Belum Hadir', 'Sakit/Izin', 'Alpa'],
                datasets: [{
                    data: [d_presentOnTime, d_late, d_absent, d_excused, d_alpha],
                    backgroundColor: ['#10b981', '#f59e0b', '#ef4444', '#a855f7', '#64748b'],
                    borderWidth: 2, borderColor: '#ffffff', hoverOffset: 5
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '75%', 
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b', bodyFont: { size: 12 },
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.parsed;
                                let total = context.chart._metasets[context.datasetIndex].total;
                                let percentage = ((value / total) * 100).toFixed(1) + "%";
                                return label + ': ' + value + ' (' + percentage + ')';
                            }
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>