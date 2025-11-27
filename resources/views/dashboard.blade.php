<x-app-layout>
    {{-- Menggunakan AlpineJS untuk manajemen state filter sederhana --}}
    <div x-data="{ period: 'today' }">
        
        {{-- Header Section --}}
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-800 tracking-tight">
                    Dashboard Monitoring
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Selamat Datang, <span class="text-blue-600 font-bold">{{ Auth::user()->name }}</span>!
                    <span x-show="period === 'today'">Berikut ringkasan <span class="font-semibold text-gray-700">Hari Ini</span>.</span>
                    <span x-show="period === 'week'" style="display: none;">Berikut ringkasan <span class="font-semibold text-gray-700">Minggu Ini</span>.</span>
                </p>
            </div>
            
            {{-- Filter Cepat dengan Interaksi AlpineJS --}}
            <div class="flex items-center gap-2 bg-white p-1.5 rounded-xl shadow-sm border border-gray-100">
                <button 
                    @click="period = 'today'" 
                    :class="period === 'today' ? 'bg-blue-50 text-blue-700 shadow-sm' : 'text-gray-500 hover:bg-gray-50'"
                    class="px-4 py-2 text-xs font-bold rounded-lg transition-all duration-200">
                    Hari Ini
                </button>
                <button 
                    @click="period = 'week'" 
                    :class="period === 'week' ? 'bg-blue-50 text-blue-700 shadow-sm' : 'text-gray-500 hover:bg-gray-50'"
                    class="px-4 py-2 text-xs font-bold rounded-lg transition-all duration-200">
                    Minggu Ini
                </button>
            </div>
        </div>

        {{-- 
            =========================================
            BAGIAN 1: KARTU STATISTIK (STATS CARDS)
            =========================================
        --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            @php
                $cards = [
                    [
                        'title' => 'Total Siswa',
                        'value' => $totalStudents,
                        'icon_bg' => 'bg-blue-100',
                        'icon_text' => 'text-blue-600',
                        'hover_text' => 'group-hover:text-blue-600',
                        'bg_decor' => 'bg-blue-50',
                        'icon_path' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
                        'footer_text' => 'Terdaftar di sistem',
                        'footer_color' => 'text-blue-600'
                    ],
                    [
                        'title' => 'Hadir',
                        'value' => $presentCount,
                        'icon_bg' => 'bg-emerald-100',
                        'icon_text' => 'text-emerald-600',
                        'hover_text' => 'group-hover:text-emerald-600',
                        'bg_decor' => 'bg-emerald-50',
                        'icon_path' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                        'footer_text' => $presentPercentage . '% dari total siswa',
                        'footer_color' => 'text-emerald-600'
                    ],
                    [
                        'title' => 'Terlambat',
                        'value' => $lateCount,
                        'icon_bg' => 'bg-amber-100',
                        'icon_text' => 'text-amber-600',
                        'hover_text' => 'group-hover:text-amber-500',
                        'bg_decor' => 'bg-amber-50',
                        'icon_path' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                        'footer_text' => 'Perlu Perhatian',
                        'footer_color' => 'text-amber-600'
                    ],
                    [
                        'title' => 'Belum Hadir',
                        'value' => $absentCount,
                        'icon_bg' => 'bg-red-100',
                        'icon_text' => 'text-red-500',
                        'hover_text' => 'group-hover:text-red-500',
                        'bg_decor' => 'bg-red-50',
                        'icon_path' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                        'footer_text' => 'Sakit: '.$sickCount.' | Izin: '.$permitCount,
                        'footer_color' => 'text-red-500'
                    ]
                ];
            @endphp

            @foreach($cards as $card)
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 group relative overflow-hidden">
                <div class="absolute right-0 top-0 h-24 w-24 {{ $card['bg_decor'] }} rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                <div class="relative flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ $card['title'] }}</p>
                        <h3 class="text-3xl font-extrabold text-gray-800 mt-1 {{ $card['hover_text'] }} transition-colors">
                            {{ $card['value'] }}
                        </h3>
                    </div>
                    <div class="h-12 w-12 {{ $card['icon_bg'] }} {{ $card['icon_text'] }} rounded-xl flex items-center justify-center text-xl shadow-sm group-hover:rotate-12 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon_path'] }}"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs text-gray-400">
                    <span class="{{ $card['footer_color'] }} font-bold mr-1 flex items-center">
                        <span class="w-2 h-2 rounded-full bg-current mr-1.5 opacity-70"></span>
                        {{ $card['footer_text'] }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>

        {{-- 
            =========================================
            BAGIAN 2: GRAFIK & CHART
            =========================================
        --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            
            <!-- Grafik Batang -->
            <div class="lg:col-span-2 bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Analisis Kehadiran</h3>
                        <p class="text-xs text-gray-400">Tren kehadiran siswa dalam satu minggu</p>
                    </div>
                    <button class="inline-flex items-center gap-2 text-xs font-semibold text-gray-600 bg-gray-50 hover:bg-gray-100 px-3 py-1.5 rounded-lg transition-colors border border-gray-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Export PDF
                    </button>
                </div>
                <div class="relative h-72 w-full">
                    <canvas id="weeklyChart"></canvas>
                </div>
            </div>

            <!-- Grafik Donat -->
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col">
                <h3 class="text-lg font-bold text-gray-800 mb-2">Komposisi Real-time</h3>
                <p class="text-xs text-gray-400 mb-6">Persentase status siswa hari ini</p>
                
                <div class="relative h-64 w-full flex-1 flex items-center justify-center">
                    <canvas id="dailyDonutChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-3xl font-extrabold text-gray-800">{{ $totalStudents }}</span>
                        <span class="text-xs text-gray-400 font-medium">Total Siswa</span>
                    </div>
                </div>
                
                <div class="mt-6 grid grid-cols-2 gap-3 text-xs text-gray-600">
                    <div class="flex items-center p-2 rounded-lg bg-emerald-50/50 border border-emerald-100">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 mr-2"></span>Hadir Tepat
                    </div>
                    <div class="flex items-center p-2 rounded-lg bg-amber-50/50 border border-amber-100">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500 mr-2"></span>Terlambat
                    </div>
                    <div class="flex items-center p-2 rounded-lg bg-red-50/50 border border-red-100">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-500 mr-2"></span>Belum Hadir
                    </div>
                    <div class="flex items-center p-2 rounded-lg bg-blue-50/50 border border-blue-100">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-500 mr-2"></span>Pulang Awal
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
        Chart.defaults.color = '#94a3b8';

        // --- 1. GRAFIK BATANG MINGGUAN (STACKED SEPERTI LANDING PAGE) ---
        const ctxWeekly = document.getElementById('weeklyChart').getContext('2d');
        
        // Data dari Controller
        const rawPresentData = @json($weeklyPresentData); // Ini masih TOTAL (Hadir + Telat)
        const rawLateData = @json($weeklyLateData);
        const rawAbsentData = @json($weeklyAbsentData);

        // KITA HITUNG ULANG DI JS AGAR STACKINGNYA BENAR
        // Hadir Tepat Waktu = Total Hadir - Terlambat
        const onTimeData = rawPresentData.map((total, index) => {
            return Math.max(0, total - rawLateData[index]);
        });

        new Chart(ctxWeekly, {
            type: 'bar',
            data: {
                labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                datasets: [
                    { 
                        label: 'Hadir Tepat Waktu', 
                        data: onTimeData, // Pakai data hasil hitungan
                        backgroundColor: '#10b981', // Emerald-500 (Sama dgn Landing)
                        hoverBackgroundColor: '#059669',
                        borderRadius: 4, 
                        barThickness: 25, // Batang Tebal Solid
                        stack: 'mainStack' // Kunci agar bertumpuk
                    },
                    { 
                        label: 'Terlambat', 
                        data: rawLateData, 
                        backgroundColor: '#f59e0b', // Amber-500 (Sama dgn Landing)
                        hoverBackgroundColor: '#d97706',
                        borderRadius: 4, 
                        barThickness: 25,
                        stack: 'mainStack'
                    },
                    { 
                        label: 'Tidak Hadir', 
                        data: rawAbsentData, 
                        backgroundColor: '#ef4444', // Red-500 (Sama dgn Landing)
                        hoverBackgroundColor: '#dc2626',
                        borderRadius: 4, 
                        barThickness: 25,
                        stack: 'mainStack'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { 
                        position: 'top', 
                        align: 'end', 
                        labels: { usePointStyle: true, boxWidth: 8, padding: 20 } 
                    },
                    tooltip: { 
                        backgroundColor: 'rgba(15, 23, 42, 0.9)', 
                        padding: 12, 
                        cornerRadius: 8,
                        callbacks: {
                            // Custom tooltip agar totalnya benar
                            footer: function(tooltipItems) {
                                let total = 0;
                                tooltipItems.forEach(function(tooltipItem) {
                                    total += tooltipItem.raw;
                                });
                                return 'Total: ' + total + ' Siswa';
                            }
                        }
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        stacked: true, // WAJIB: Aktifkan mode tumpuk Y
                        grid: { borderDash: [4, 4], color: '#f1f5f9', drawBorder: false },
                        border: { display: false }
                    },
                    x: { 
                        stacked: true, // WAJIB: Aktifkan mode tumpuk X
                        grid: { display: false, drawBorder: false } 
                    }
                }
            }
        });

        // --- 2. GRAFIK DONAT HARIAN ---
        const ctxDonut = document.getElementById('dailyDonutChart').getContext('2d');
        new Chart(ctxDonut, {
            type: 'doughnut',
            data: {
                labels: ['Hadir Tepat', 'Terlambat', 'Belum Hadir', 'Pulang Awal', 'Lainnya'],
                datasets: [{
                    data: [
                        {{ $presentOnTimeCount }}, {{ $lateCount }}, {{ $absentCount }}, {{ $earlyLeaveCount }},
                        {{ $sickCount + $permitCount + $alphaCount }}
                    ],
                    backgroundColor: ['#10b981', '#f59e0b', '#ef4444', '#3b82f6', '#8b5cf6'],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '80%', 
                plugins: { legend: { display: false } }
            }
        });
    </script>
</x-app-layout>