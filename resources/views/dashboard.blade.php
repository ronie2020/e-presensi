<x-app-layout>
    {{-- Header Khusus Dashboard dengan Sapaan --}}
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-800 tracking-tight">
                Dashboard Monitoring
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Selamat Datang, <span class="text-blue-600 font-bold">{{ Auth::user()->name }}</span>! Berikut ringkasan hari ini.
            </p>
        </div>
        
        {{-- Filter Cepat (Opsional - visual saja untuk saat ini) --}}
        <div class="flex items-center gap-2 bg-white p-1.5 rounded-xl shadow-sm border border-gray-100">
            <button class="px-4 py-2 text-xs font-bold text-blue-700 bg-blue-50 rounded-lg transition">
                Hari Ini
            </button>
            <button class="px-4 py-2 text-xs font-bold text-gray-500 hover:bg-gray-50 rounded-lg transition">
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
        
        <!-- Kartu 1: Total Siswa -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 group relative overflow-hidden">
            <div class="absolute right-0 top-0 h-24 w-24 bg-blue-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
            
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-wider">Total Siswa</p>
                    <h3 class="text-3xl font-extrabold text-gray-800 mt-1 group-hover:text-blue-600 transition-colors">
                        {{ $totalStudents }}
                    </h3>
                </div>
                <div class="h-12 w-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center text-xl shadow-sm group-hover:rotate-12 transition-transform">
                    <i class="fas fa-users"></i> <!-- Pastikan FontAwesome aktif, atau ganti SVG -->
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-gray-400">
                <span class="text-green-500 font-bold flex items-center mr-1">
                    <svg class="w-3 h-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                    Aktif
                </span>
                <span class="truncate">Terdaftar di sistem</span>
            </div>
        </div>

        <!-- Kartu 2: Hadir Hari Ini -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 group relative overflow-hidden">
            <div class="absolute right-0 top-0 h-24 w-24 bg-emerald-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
            
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-wider">Hadir</p>
                    <h3 class="text-3xl font-extrabold text-gray-800 mt-1 group-hover:text-emerald-600 transition-colors">
                        {{ $presentCount }}
                    </h3>
                </div>
                <div class="h-12 w-12 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center text-xl shadow-sm group-hover:rotate-12 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-gray-400">
                <span class="text-emerald-600 font-bold mr-1">{{ $presentPercentage }}%</span>
                <span>dari total siswa</span>
            </div>
        </div>

        <!-- Kartu 3: Terlambat -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 group relative overflow-hidden">
            <div class="absolute right-0 top-0 h-24 w-24 bg-amber-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
            
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-wider">Terlambat</p>
                    <h3 class="text-3xl font-extrabold text-gray-800 mt-1 group-hover:text-amber-500 transition-colors">
                        {{ $lateCount }}
                    </h3>
                </div>
                <div class="h-12 w-12 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center text-xl shadow-sm group-hover:rotate-12 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
             <div class="mt-4 flex items-center text-xs text-gray-400">
                <span class="text-amber-600 font-bold mr-1">Perlu Perhatian</span>
            </div>
        </div>

        <!-- Kartu 4: Belum Hadir / Alpha -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 group relative overflow-hidden">
            <div class="absolute right-0 top-0 h-24 w-24 bg-red-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
            
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-wider">Belum Hadir</p>
                    <h3 class="text-3xl font-extrabold text-gray-800 mt-1 group-hover:text-red-500 transition-colors">
                        {{ $absentCount }}
                    </h3>
                </div>
                <div class="h-12 w-12 bg-red-100 text-red-500 rounded-xl flex items-center justify-center text-xl shadow-sm group-hover:rotate-12 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
             <div class="mt-4 flex items-center text-xs text-gray-400">
                <span class="text-red-500 font-bold mr-1">Sakit: {{ $sickCount }} | Izin: {{ $permitCount }}</span>
            </div>
        </div>

    </div>

    {{-- 
        =========================================
        BAGIAN 2: GRAFIK & CHART (GRID 2 KOLOM)
        =========================================
    --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        
        <!-- Grafik Batang (Weekly Progress) - Mengambil 2 Kolom -->
        <div class="lg:col-span-2 bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-800">Progres Mingguan</h3>
                <button class="text-gray-400 hover:text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path></svg>
                </button>
            </div>
            <!-- Container Grafik -->
            <div class="relative h-72 w-full">
                <canvas id="weeklyChart"></canvas>
            </div>
        </div>

        <!-- Grafik Donat (Daily Status) - Mengambil 1 Kolom -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col">
            <h3 class="text-lg font-bold text-gray-800 mb-6">Komposisi Hari Ini</h3>
            <div class="relative h-64 w-full flex-1 flex items-center justify-center">
                <canvas id="dailyDonutChart"></canvas>
            </div>
            <!-- Legend Custom (Opsional, jika ingin lebih rapi) -->
            <div class="mt-6 grid grid-cols-2 gap-2 text-xs text-gray-600">
                <div class="flex items-center"><span class="w-3 h-3 rounded-full bg-emerald-500 mr-2"></span>Hadir Tepat</div>
                <div class="flex items-center"><span class="w-3 h-3 rounded-full bg-amber-500 mr-2"></span>Terlambat</div>
                <div class="flex items-center"><span class="w-3 h-3 rounded-full bg-red-500 mr-2"></span>Belum Hadir</div>
                <div class="flex items-center"><span class="w-3 h-3 rounded-full bg-blue-500 mr-2"></span>Pulang Awal</div>
            </div>
        </div>
    </div>

    {{-- Script Chart.js (Pastikan Anda sudah menginstall atau CDN chart.js di layout utama, atau panggil disini) --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Konfigurasi Umum agar Chart terlihat modern
        Chart.defaults.font.family = "'Figtree', sans-serif";
        Chart.defaults.color = '#64748b';
        
        // 1. Grafik Batang Mingguan
        const ctxWeekly = document.getElementById('weeklyChart').getContext('2d');
        new Chart(ctxWeekly, {
            type: 'bar',
            data: {
                labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                datasets: [
                    {
                        label: 'Hadir',
                        data: @json($weeklyPresentData), // Data dari Controller
                        backgroundColor: '#10b981', // Emerald 500
                        borderRadius: 6,
                        barThickness: 20,
                    },
                    {
                        label: 'Terlambat',
                        data: @json($weeklyLateData), // Data dari Controller
                        backgroundColor: '#f59e0b', // Amber 500
                        borderRadius: 6,
                        barThickness: 20,
                    },
                    {
                        label: 'Tidak Hadir',
                        data: @json($weeklyAbsentData), // Data dari Controller
                        backgroundColor: '#ef4444', // Red 500
                        borderRadius: 6,
                        barThickness: 20,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } }
                },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        grid: { borderDash: [2, 4], color: '#f1f5f9', drawBorder: false } 
                    },
                    x: { 
                        grid: { display: false, drawBorder: false } 
                    }
                }
            }
        });

        // 2. Grafik Donat Harian
        const ctxDonut = document.getElementById('dailyDonutChart').getContext('2d');
        new Chart(ctxDonut, {
            type: 'doughnut',
            data: {
                labels: ['Hadir Tepat', 'Terlambat', 'Belum Hadir', 'Pulang Awal', 'Sakit/Izin'],
                datasets: [{
                    data: [
                        {{ $presentOnTimeCount }}, 
                        {{ $lateCount }}, 
                        {{ $absentCount }}, 
                        {{ $earlyLeaveCount }},
                        {{ $sickCount + $permitCount + $alphaCount }}
                    ],
                    backgroundColor: [
                        '#10b981', // Emerald
                        '#f59e0b', // Amber
                        '#ef4444', // Red
                        '#3b82f6', // Blue
                        '#8b5cf6'  // Violet
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%', // Membuat donat lebih tipis
                plugins: {
                    legend: { display: false } // Kita pakai legend custom HTML di bawahnya
                }
            }
        });
    </script>
</x-app-layout>