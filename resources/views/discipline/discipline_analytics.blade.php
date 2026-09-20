<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-8 font-sans text-elevate-text bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HERO SECTION --}}
            <div class="mb-8 relative z-10">
                <x-hero-section
                    badge="ANALITIK PERILAKU & BK"
                    badgeIcon="ph-fill ph-chart-pie-slice"
                    showcaseIcon="ph-duotone ph-chart-polar"
                    showcaseTitle="Tren Kedisiplinan"
                    showcaseSubtitle="Evaluasi Bimbingan Konseling">
                    <x-slot:title>
                        <span class="block text-slate-100">Analisis & Statistik</span>
                        <span class="block mt-1 sm:mt-1.5 text-transparent bg-clip-text bg-gradient-to-r from-[#56bbf1] via-sky-200 to-[#38bdf8]">
                            Kedisiplinan & BK
                        </span>
                    </x-slot:title>
                    <x-slot:description>
                        Visualisasi komprehensif tren perilaku siswa, poin pelanggaran vs prestasi, dan status konseling untuk mendukung kebijakan sekolah.
                    </x-slot:description>
                    <x-slot:chips>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                            <i class="ph-bold ph-warning-circle text-rose-400"></i> Pelanggaran & Poin
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                            <i class="ph-bold ph-medal text-amber-400"></i> Prestasi (Merit)
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                            <i class="ph-bold ph-heart text-emerald-400"></i> Sesi Bimbingan BK
                        </span>
                    </x-slot:chips>
                    <x-slot:cta>
                        <a href="{{ route('discipline.index') }}"
                           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-slate-800/80 hover:bg-slate-700 text-slate-300 hover:text-white font-bold text-xs border border-slate-700 transition-all duration-300">
                            <i class="ph-bold ph-arrow-left"></i>
                            <span>Kembali ke Log</span>
                        </a>
                        <a href="{{ route('recovery.index') }}"
                           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white font-bold text-xs shadow-lg shadow-sky-500/25 transition-all duration-300">
                            <i class="ph-bold ph-sparkle"></i>
                            <span>Halaman Recovery</span>
                        </a>
                    </x-slot:cta>
                    <x-slot:showcaseStats>
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-2xl bg-slate-900/80 border border-amber-500/30 backdrop-blur-md">
                            <i class="ph-fill ph-chats text-amber-400 text-sm"></i>
                            <span class="text-xs font-bold text-slate-300">BK Pending:</span>
                            <span class="text-sm font-black text-amber-400 font-mono">{{ \App\Models\BkSession::where('status', 'pending')->count() }}</span>
                        </div>
                    </x-slot:showcaseStats>
                </x-hero-section>
            </div>

            {{-- 1. STATS OVERVIEW --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                {{-- Total Pelanggaran (Poin) - Tetap Rose --}}
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
                    <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center text-2xl mb-4">
                        <i class="ph-duotone ph-warning-circle"></i>
                    </div>
                    <div class="text-xs font-black text-slate-400 uppercase tracking-widest">Akumulasi Pelanggaran</div>
                    <div class="text-3xl font-black text-elevate-dark mt-1">{{ number_format($classSummaries->sum('total_violation')) }} <span class="text-xs text-slate-400">Poin</span></div>
                </div>

                {{-- Total Prestasi (Poin) - Tetap Emerald --}}
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-2xl mb-4">
                        <i class="ph-duotone ph-medal"></i>
                    </div>
                    <div class="text-xs font-black text-slate-400 uppercase tracking-widest">Akumulasi Prestasi</div>
                    <div class="text-3xl font-black text-elevate-dark mt-1">{{ number_format($classSummaries->sum('total_merit')) }} <span class="text-xs text-slate-400">Poin</span></div>
                </div>

                {{-- Tiket BK Aktif - Tetap Amber --}}
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
                    <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center text-2xl mb-4">
                        <i class="ph-duotone ph-chats"></i>
                    </div>
                    <div class="text-xs font-black text-slate-400 uppercase tracking-widest">Tiket BK Pending</div>
                    <div class="text-3xl font-black text-elevate-dark mt-1">{{ \App\Models\BkSession::where('status', 'pending')->count() }}</div>
                </div>

                {{-- Partisipasi - Diubah ke Tema Elevate Dark --}}
                <div class="bg-elevate-dark p-6 rounded-[2rem] shadow-xl shadow-elevate-dark/10">
                    <div class="w-12 h-12 bg-elevate-accent/20 text-elevate-accent rounded-2xl flex items-center justify-center text-2xl mb-4">
                        <i class="ph-duotone ph-users-four"></i>
                    </div>
                    <div class="text-xs font-black text-elevate-accent/60 uppercase tracking-widest">Siswa Terlibat</div>
                    <div class="text-3xl font-black text-white mt-1">{{ $students->where('total_violation', '>', 0)->count() + $students->where('total_merit', '>', 0)->count() }}</div>
                </div>
            </div>

            {{-- 2. CHARTS SECTION --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
                {{-- Tren Bulanan (REAL DATA) --}}
                <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
                    <h3 class="font-black text-elevate-dark mb-6 flex items-center gap-2">
                        <i class="ph-bold ph-trend-up text-elevate-primary"></i> Tren Disiplin (Tahun {{ date('Y') }})
                    </h3>
                    <div class="h-[300px]">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>

                {{-- Distribusi Kelas (REAL DATA) --}}
                <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
                    <h3 class="font-black text-elevate-dark mb-6 flex items-center gap-2">
                        <i class="ph-bold ph-chart-bar text-elevate-accent"></i> Akumulasi Poin Per Kelas
                    </h3>
                    <div class="h-[300px]">
                        <canvas id="classChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- 3. CRITICAL STUDENTS TABLE --}}
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden mb-10">
                <div class="px-8 py-6 border-b border-slate-50 flex justify-between items-center">
                    <h3 class="font-black text-elevate-dark flex items-center gap-2">
                        <i class="ph-bold ph-warning-octagon text-rose-500"></i> 
                        Siswa Perlu Perhatian Khusus (Poin Tertinggi)
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50/50">
                            <tr>
                                <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Siswa</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Minus (-)</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Plus (+)</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status BK</th>
                                <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($topViolators->take(5) as $sv)
                            @php
                                $sObj = $students->where('name', $sv->name)->first();
                                $hasTicket = \App\Models\BkSession::where('student_id', $sObj->id)->whereIn('status', ['pending', 'approved', 'ongoing'])->exists();
                            @endphp
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-8 py-4">
                                    <div class="font-black text-elevate-dark uppercase tracking-tight">{{ $sv->name }}</div>
                                    <div class="text-[10px] font-bold text-slate-400 uppercase">{{ $sv->schoolClass->name ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="bg-rose-50 text-rose-600 px-3 py-1 rounded-lg font-black text-sm border border-rose-100">-{{ $sv->total_violation }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="bg-emerald-50 text-emerald-600 px-3 py-1 rounded-lg font-black text-sm border border-emerald-100">+{{ $sv->total_merit ?? 0 }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($hasTicket)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-elevate-accent/10 text-elevate-primary text-[9px] font-black uppercase tracking-wider">
                                            <i class="ph-fill ph-check-circle"></i> Dalam BK
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-slate-400 text-[9px] font-black uppercase tracking-wider">
                                            <i class="ph-bold ph-minus"></i> Belum Diproses
                                        </span>
                                    @endif
                                </td>
                                <td class="px-8 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('discipline.sp_print', $sObj->id) }}" target="_blank" class="p-2 bg-slate-100 hover:bg-elevate-dark hover:text-white text-slate-500 rounded-xl transition-all shadow-sm" title="Cetak SP">
                                            <i class="ph-bold ph-printer text-lg"></i>
                                        </a>
                                        <a href="{{ route('admin.bk.index') }}?search={{ urlencode($sv->name) }}" class="p-2 bg-elevate-accent/10 hover:bg-elevate-primary hover:text-white text-elevate-primary rounded-xl transition-all shadow-sm" title="Lihat Riwayat BK">
                                            <i class="ph-bold ph-chat-centered-text text-lg"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // --- 1. DATA TREN BULANAN (REAL) ---
        const ctxTrend = document.getElementById('trendChart').getContext('2d');
        new Chart(ctxTrend, {
            type: 'line',
            data: {
                labels: @json($trendLabels),
                datasets: [
                    {
                        label: 'Poin Pelanggaran',
                        data: @json($trendViolations),
                        borderColor: '#f43f5e',
                        backgroundColor: 'rgba(244, 63, 94, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 4,
                        pointBackgroundColor: '#f43f5e'
                    },
                    {
                        label: 'Poin Prestasi',
                        data: @json($trendMerits),
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 4,
                        pointBackgroundColor: '#10b981'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { 
                        display: true,
                        position: 'top',
                        labels: { font: { weight: 'bold', family: 'Plus Jakarta Sans' } }
                    } 
                },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [5, 5] } },
                    x: { grid: { display: false } }
                }
            }
        });

        // --- 2. DATA BAR CHART KELAS (REAL) ---
        const ctxClass = document.getElementById('classChart').getContext('2d');
        const classLabels = @json($classSummaries->pluck('class_name'));
        const classViolations = @json($classSummaries->pluck('total_violation'));
        const classMerits = @json($classSummaries->pluck('total_merit'));

        new Chart(ctxClass, {
            type: 'bar',
            data: {
                labels: classLabels,
                datasets: [
                    {
                        label: 'Total Minus (-)',
                        data: classViolations,
                        backgroundColor: '#f43f5e',
                        borderRadius: 8
                    },
                    {
                        label: 'Total Plus (+)',
                        data: classMerits,
                        backgroundColor: '#10b981',
                        borderRadius: 8
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { 
                        display: true, 
                        position: 'top',
                        labels: { font: { weight: 'bold', family: 'Plus Jakarta Sans' } }
                    } 
                },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [5, 5] } },
                    x: { grid: { display: false } }
                }
            }
        });
    </script>
</x-app-layout>