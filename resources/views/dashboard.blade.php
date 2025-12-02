<x-app-layout>
    {{-- 
        =========================================
        WRAPPER UTAMA (ALPINE.JS) - FIXED
        =========================================
        Logika filter sekarang aktif. Halaman akan reload otomatis saat filter diubah.
    --}}
    <div x-data="{ 
            period: new URLSearchParams(window.location.search).get('period') || 'today',
            date: new URLSearchParams(window.location.search).get('date') || new Date().toISOString().split('T')[0],
            
            updateFilter(newPeriod) {
                this.period = newPeriod;
                this.refreshData();
            },
            
            refreshData() {
                // Reload halaman dengan parameter query string baru
                const params = new URLSearchParams();
                params.set('period', this.period);
                params.set('date', this.date);
                window.location.search = params.toString();
            }
        }" class="space-y-8">
        
        {{-- HEADER SECTION --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight leading-tight flex items-center gap-3">
                    <i class="ph-duotone ph-squares-four text-blue-600"></i> Dashboard
                </h1>
                <p class="text-slate-500 mt-2 text-lg">
                    Ringkasan data kehadiran siswa <span class="font-bold text-blue-600" x-text="period === 'today' ? 'Hari Ini' : (period === 'week' ? 'Minggu Ini' : 'Bulan Ini')"></span>.
                </p>
            </div>

            {{-- FILTER SECTION --}}
            <div class="bg-white p-1.5 rounded-2xl shadow-sm border border-slate-200 flex flex-wrap gap-2">
                {{-- Tombol Periode --}}
                <div class="flex bg-slate-100 rounded-xl p-1">
                    <button @click="updateFilter('today')" 
                        :class="period === 'today' ? 'bg-white text-blue-600 shadow-sm font-bold' : 'text-slate-500 hover:text-slate-700 font-medium'"
                        class="px-4 py-2 text-xs rounded-lg transition-all duration-200">
                        Harian
                    </button>
                    <button @click="updateFilter('week')" 
                        :class="period === 'week' ? 'bg-white text-blue-600 shadow-sm font-bold' : 'text-slate-500 hover:text-slate-700 font-medium'"
                        class="px-4 py-2 text-xs rounded-lg transition-all duration-200">
                        Mingguan
                    </button>
                    <button @click="updateFilter('month')" 
                        :class="period === 'month' ? 'bg-white text-blue-600 shadow-sm font-bold' : 'text-slate-500 hover:text-slate-700 font-medium'"
                        class="px-4 py-2 text-xs rounded-lg transition-all duration-200">
                        Bulanan
                    </button>
                </div>

                {{-- Input Tanggal Dinamis (Auto Reload saat diganti) --}}
                <div class="flex items-center">
                    <input x-show="period === 'today'" type="date" x-model="date" @change="refreshData()" 
                           class="text-xs font-bold text-slate-600 border-slate-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 bg-slate-50">
                    
                    <input x-show="period === 'week'" type="week" x-model="date" @change="refreshData()"
                           class="text-xs font-bold text-slate-600 border-slate-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 bg-slate-50">
                    
                    <input x-show="period === 'month'" type="month" x-model="date" @change="refreshData()"
                           class="text-xs font-bold text-slate-600 border-slate-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 bg-slate-50">
                </div>
            </div>
        </div>

        {{-- 
            =========================================
            BAGIAN 1: KARTU STATISTIK (KPI CARDS)
            =========================================
        --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
            @php
                // PENCEGAHAN ERROR VARIABLE & LOGIKA WARNA
                $totalStudents = $totalStudents ?? 0;
                $presentCount = $presentCount ?? 0;
                $lateCount = $lateCount ?? 0;
                $absentCount = $absentCount ?? 0;
                $earlyLeaveCount = $earlyLeaveCount ?? 0;
                $excusedCount = ($sickCount ?? 0) + ($permitCount ?? 0) + ($alphaCount ?? 0);

                $cards = [
                    [ 'title' => 'Total Siswa', 'value' => $totalStudents, 'color' => 'blue', 'icon' => 'ph-student' ],
                    [ 'title' => 'Total Hadir', 'value' => $presentCount, 'color' => 'emerald', 'icon' => 'ph-check-circle' ],
                    [ 'title' => 'Belum Hadir', 'value' => $absentCount, 'color' => 'slate', 'icon' => 'ph-minus-circle' ],
                    [ 'title' => 'Terlambat', 'value' => $lateCount, 'color' => 'orange', 'icon' => 'ph-clock-warning' ],
                    [ 'title' => 'Pulang Awal', 'value' => $earlyLeaveCount, 'color' => 'yellow', 'icon' => 'ph-person-simple-run' ],
                    [ 'title' => 'Sakit / Izin', 'value' => $excusedCount, 'color' => 'rose', 'icon' => 'ph-first-aid' ]
                ];
            @endphp

            @foreach($cards as $card)
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md hover:border-{{ $card['color'] }}-200 transition-all duration-300 group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">{{ $card['title'] }}</p>
                        <h3 class="text-2xl font-black text-slate-800 group-hover:text-{{ $card['color'] }}-600 transition-colors">
                            {{ $card['value'] }}
                        </h3>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-{{ $card['color'] }}-50 text-{{ $card['color'] }}-600 flex items-center justify-center text-xl shadow-sm border border-{{ $card['color'] }}-100">
                        <i class="{{ $card['icon'] }} ph-duotone"></i>
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
            <div class="lg:col-span-2 bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-black text-slate-800 flex items-center gap-2">
                            <i class="ph-duotone ph-chart-bar text-blue-600"></i> Analisis Kehadiran
                        </h3>
                        <p class="text-xs text-slate-500 font-medium">Tren data siswa berdasarkan periode terpilih.</p>
                    </div>
                    <div class="flex items-center gap-1 bg-green-50 text-green-700 px-3 py-1 rounded-full border border-green-100">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                        </span>
                        <span class="text-[10px] font-bold uppercase tracking-wider">Live Data</span>
                    </div>
                </div>
                <div class="relative h-80 w-full">
                    <canvas id="weeklyChart"></canvas>
                </div>
            </div>

            <!-- B. Grafik Donat (Komposisi) -->
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col">
                <h3 class="text-lg font-black text-slate-800 mb-2 flex items-center gap-2">
                    <i class="ph-duotone ph-chart-pie-slice text-purple-600"></i> Komposisi
                </h3>
                <p class="text-xs text-slate-500 font-medium mb-6">Persentase status kehadiran.</p>
                
                <div class="relative h-64 w-full flex-1 flex items-center justify-center">
                    <canvas id="dailyDonutChart"></canvas>
                    {{-- Center Text Donut --}}
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-4xl font-black text-slate-800">{{ $totalStudents }}</span>
                        <span class="text-[10px] text-slate-400 uppercase font-bold tracking-widest">Siswa</span>
                    </div>
                </div>
                
                {{-- Legenda Custom --}}
                <div class="mt-6 grid grid-cols-2 gap-3 text-xs font-bold text-slate-600">
                    <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Hadir Tepat</div>
                    <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> Terlambat</div>
                    <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span> Belum Hadir</div>
                    <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span> Pulang Awal</div>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT CHART.JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
            Chart.defaults.color = '#64748b';

            // --- 1. DATA PROCESSING (ROBUST) ---
            // Menggunakan Object.values() untuk memastikan data dikonversi jadi Array murni
            // walau dari backend dikirim sebagai Object (Associative Array).
            
            const rawPresent = @json($weeklyPresentData ?? []);
            const rawLate = @json($weeklyLateData ?? []);
            const rawAbsent = @json($weeklyAbsentData ?? []);

            // Konversi ke Array dan pastikan minimal ada data default [0] jika kosong
            const arrPresent = Array.isArray(rawPresent) ? rawPresent : Object.values(rawPresent);
            const arrLate = Array.isArray(rawLate) ? rawLate : Object.values(rawLate);
            const arrAbsent = Array.isArray(rawAbsent) ? rawAbsent : Object.values(rawAbsent);

            // Hitung Hadir Tepat Waktu (Total Hadir - Terlambat)
            // Lakukan mapping manual agar aman
            const arrOnTime = arrPresent.map((val, idx) => {
                const lateVal = arrLate[idx] || 0;
                return Math.max(0, val - lateVal);
            });

            // Data untuk Donut Chart
            const d_presentOnTime = @json($presentOnTimeCount ?? 0);
            const d_late = @json($lateCount ?? 0);
            const d_absent = @json($absentCount ?? 0);
            const d_earlyLeave = @json($earlyLeaveCount ?? 0);
            const d_others = @json(($sickCount ?? 0) + ($permitCount ?? 0) + ($alphaCount ?? 0));

            // --- 2. CHART BATANG (TREND) ---
            const ctxWeekly = document.getElementById('weeklyChart').getContext('2d');
            
            // Labels Statis (Bisa diganti dinamis jika dikirim dari controller)
            const labels = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']; 

            new Chart(ctxWeekly, {
                type: 'bar',
                data: {
                    labels: labels, 
                    datasets: [
                        { label: 'Hadir Tepat', data: arrOnTime, backgroundColor: '#10b981', borderRadius: 6, barThickness: 12, stack: 'main' },
                        { label: 'Terlambat', data: arrLate, backgroundColor: '#f59e0b', borderRadius: 6, barThickness: 12, stack: 'main' },
                        { label: 'Tidak Hadir', data: arrAbsent, backgroundColor: '#ef4444', borderRadius: 6, barThickness: 12, stack: 'main' }
                    ]
                },
                options: {
                    responsive: true, 
                    maintainAspectRatio: false,
                    plugins: { 
                        legend: { position: 'top', align: 'end', labels: { usePointStyle: true, font: { weight: 'bold' } } },
                        tooltip: { backgroundColor: '#1e293b', padding: 12, cornerRadius: 8 }
                    },
                    scales: { 
                        y: { beginAtZero: true, stacked: true, grid: { borderDash: [4, 4], color: '#f1f5f9' }, border: { display: false } }, 
                        x: { stacked: true, grid: { display: false }, ticks: { font: { weight: 'bold' } } } 
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
                        borderWidth: 0, 
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true, 
                    maintainAspectRatio: false, 
                    cutout: '75%', 
                    plugins: { legend: { display: false } }
                }
            });
        });
    </script>
</x-app-layout>