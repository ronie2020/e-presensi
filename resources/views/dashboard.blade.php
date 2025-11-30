<x-app-layout>
    {{-- 
        =========================================
        WRAPPER UTAMA (ALPINE.JS)
        =========================================
        Mengatur state untuk filter periode dan tanggal
    --}}
    <div x-data="{ 
            period: new URLSearchParams(window.location.search).get('period') || 'today',
            date: new URLSearchParams(window.location.search).get('date') || new Date().toISOString().split('T')[0],
            
            updateFilter(newPeriod) {
                this.period = newPeriod;
                // Logika reload halaman saat filter berubah (Opsional, aktifkan jika Controller sudah siap)
                // window.location.search = '?period=' + this.period + '&date=' + this.date;
            }
        }" class="space-y-8">
        
        {{-- HEADER SECTION --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                    Dashboard Monitoring
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Ringkasan data kehadiran siswa <span class="font-bold text-indigo-600" x-text="period === 'today' ? 'Hari Ini' : (period === 'week' ? 'Minggu Ini' : 'Bulan Ini')"></span>.
                </p>
            </div>

            {{-- FILTER SECTION (DIADOPSI DARI HTML) --}}
            <div class="bg-white p-1.5 rounded-xl shadow-sm border border-gray-200 flex flex-wrap gap-2">
                {{-- Tombol Periode --}}
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

                {{-- Input Tanggal Dinamis --}}
                <div class="flex items-center">
                    <input x-show="period === 'today'" type="date" x-model="date" class="text-xs border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    <input x-show="period === 'week'" type="week" class="text-xs border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    <input x-show="period === 'month'" type="month" class="text-xs border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>
        </div>

        {{-- 
            =========================================
            BAGIAN 1: KARTU STATISTIK (KPI CARDS)
            =========================================
            Gaya visual diadopsi dari file dashboard.html (Border Left Color)
        --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
            @php
                // PENCEGAHAN ERROR VARIABLE
                $totalStudents = $totalStudents ?? 0;
                $presentCount = $presentCount ?? 0;
                $lateCount = $lateCount ?? 0;
                $absentCount = $absentCount ?? 0;
                $earlyLeaveCount = $earlyLeaveCount ?? 0;
                // Hitung total izin/sakit/alpa untuk kartu terakhir
                $excusedCount = ($sickCount ?? 0) + ($permitCount ?? 0) + ($alphaCount ?? 0);

                $cards = [
                    [
                        'title' => 'Total Siswa',
                        'value' => $totalStudents,
                        'border' => 'border-indigo-500',
                        'text_color' => 'text-gray-800',
                        'icon_color' => 'text-indigo-500',
                        'icon' => 'ph-student'
                    ],
                    [
                        'title' => 'Total Hadir',
                        'value' => $presentCount,
                        'border' => 'border-emerald-500',
                        'text_color' => 'text-gray-800',
                        'icon_color' => 'text-emerald-500',
                        'icon' => 'ph-check-circle'
                    ],
                    [
                        'title' => 'Belum Hadir',
                        'value' => $absentCount,
                        'border' => 'border-slate-500',
                        'text_color' => 'text-gray-800',
                        'icon_color' => 'text-slate-500',
                        'icon' => 'ph-minus-circle'
                    ],
                    [
                        'title' => 'Terlambat',
                        'value' => $lateCount,
                        'border' => 'border-orange-500',
                        'text_color' => 'text-gray-800',
                        'icon_color' => 'text-orange-500',
                        'icon' => 'ph-clock-warning'
                    ],
                    [
                        'title' => 'Pulang Awal',
                        'value' => $earlyLeaveCount,
                        'border' => 'border-yellow-500',
                        'text_color' => 'text-gray-800',
                        'icon_color' => 'text-yellow-500',
                        'icon' => 'ph-person-simple-run'
                    ],
                    [
                        'title' => 'Sakit / Izin',
                        'value' => $excusedCount,
                        'border' => 'border-red-500',
                        'text_color' => 'text-gray-800',
                        'icon_color' => 'text-red-500',
                        'icon' => 'ph-first-aid'
                    ]
                ];
            @endphp

            @foreach($cards as $card)
            <div class="bg-white p-5 rounded-xl shadow-sm border-l-4 {{ $card['border'] }} hover:shadow-md transition-all duration-300">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ $card['title'] }}</p>
                        <h3 class="text-2xl font-black {{ $card['text_color'] }} mt-1">
                            {{ $card['value'] }}
                        </h3>
                    </div>
                    <div class="p-2 bg-gray-50 rounded-lg {{ $card['icon_color'] }}">
                        <i class="{{ $card['icon'] ?? 'ph-hash' }} text-xl"></i>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- 
            =========================================
            BAGIAN 2: GRAFIK & CHART
            =========================================
        --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- A. Grafik Batang (Trend Kehadiran) -->
            <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                            <i class="ph-chart-bar text-indigo-500"></i> Analisis Kehadiran
                        </h3>
                        <p class="text-xs text-gray-400">Tren kehadiran siswa berdasarkan periode terpilih.</p>
                    </div>
                    <button class="text-xs flex items-center gap-1 text-gray-500 hover:text-indigo-600 transition-colors">
                        <i class="ph-download-simple"></i> Export
                    </button>
                </div>
                <div class="relative h-80 w-full">
                    <canvas id="weeklyChart"></canvas>
                </div>
            </div>

            <!-- B. Grafik Donat (Komposisi) -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col">
                <h3 class="text-lg font-bold text-gray-800 mb-2 flex items-center gap-2">
                    <i class="ph-chart-pie-slice text-purple-500"></i> Komposisi
                </h3>
                <p class="text-xs text-gray-400 mb-6">Persentase status siswa saat ini.</p>
                
                <div class="relative h-64 w-full flex-1 flex items-center justify-center">
                    <canvas id="dailyDonutChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-3xl font-extrabold text-gray-800">{{ $totalStudents }}</span>
                        <span class="text-xs text-gray-400 font-medium">Siswa</span>
                    </div>
                </div>
                
                {{-- Legenda Custom --}}
                <div class="mt-6 grid grid-cols-2 gap-3 text-xs text-gray-600">
                    <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Hadir Tepat</div>
                    <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-amber-500"></span> Terlambat</div>
                    <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-red-500"></span> Belum Hadir</div>
                    <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-blue-500"></span> Pulang Awal</div>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT CHART.JS (PHP Native Echo - Bulletproof) --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script>
        Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
        Chart.defaults.color = '#94a3b8';

        // --- 1. DATA DARI PHP (Safe Encode) ---
        const rawPresentData = <?php echo json_encode($weeklyPresentData ?? []); ?>;
        const rawLateData = <?php echo json_encode($weeklyLateData ?? []); ?>;
        const rawAbsentData = <?php echo json_encode($weeklyAbsentData ?? []); ?>;

        const d_presentOnTime = <?php echo json_encode($presentOnTimeCount ?? 0); ?>;
        const d_late = <?php echo json_encode($lateCount ?? 0); ?>;
        const d_absent = <?php echo json_encode($absentCount ?? 0); ?>;
        const d_earlyLeave = <?php echo json_encode($earlyLeaveCount ?? 0); ?>;
        const d_others = <?php echo json_encode(($sickCount ?? 0) + ($permitCount ?? 0) + ($alphaCount ?? 0)); ?>;

        // --- 2. CHART BATANG (TREND) ---
        const ctxWeekly = document.getElementById('weeklyChart').getContext('2d');
        const onTimeData = rawPresentData.map((total, index) => {
            const late = rawLateData[index] || 0;
            return Math.max(0, total - late);
        });

        new Chart(ctxWeekly, {
            type: 'bar',
            data: {
                labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                datasets: [
                    { label: 'Hadir Tepat', data: onTimeData, backgroundColor: '#10b981', borderRadius: 4, stack: 'main' },
                    { label: 'Terlambat', data: rawLateData, backgroundColor: '#f59e0b', borderRadius: 4, stack: 'main' },
                    { label: 'Tidak Hadir', data: rawAbsentData, backgroundColor: '#ef4444', borderRadius: 4, stack: 'main' }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { position: 'top', align: 'end' } },
                scales: { 
                    y: { beginAtZero: true, stacked: true, grid: { borderDash: [4, 4] }, border: { display: false } }, 
                    x: { stacked: true, grid: { display: false } } 
                }
            }
        });

        // --- 3. CHART DONAT (KOMPOSISI) ---
        const ctxDonut = document.getElementById('dailyDonutChart').getContext('2d');
        new Chart(ctxDonut, {
            type: 'doughnut',
            data: {
                labels: ['Hadir Tepat', 'Terlambat', 'Belum Hadir', 'Pulang Awal', 'Lainnya'],
                datasets: [{
                    data: [d_presentOnTime, d_late, d_absent, d_earlyLeave, d_others],
                    backgroundColor: ['#10b981', '#f59e0b', '#ef4444', '#3b82f6', '#8b5cf6'],
                    borderWidth: 0, hoverOffset: 6
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '75%', 
                plugins: { legend: { display: false } }
            }
        });
    </script>
</x-app-layout>