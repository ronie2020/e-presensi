<x-app-layout>
    {{-- LIBRARIES --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @push('styles')
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }
    </style>
    @endpush

    <div class="py-8 sm:py-10 font-sans text-white bg-elevate-surface relative overflow-hidden min-h-screen">
        {{-- Efek Latar Belakang Halus --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 md:space-y-8 relative z-10">

            {{-- HERO SECTION (UNIFIED ELEVATE DARK GLASS - AQUALIFE & E-LEARNING) --}}
            <div class="animate-enter relative rounded-[2.5rem] bg-gradient-to-br from-[#0d52a1]/85 via-[#031d3d]/90 to-[#021124]/95 p-8 md:p-10 mb-8 text-white shadow-2xl shadow-[#0d52a1]/25 border border-white/20 backdrop-blur-2xl overflow-hidden group">
                
                {{-- Specular Top Rim Light (Ref 2) --}}
                <div class="absolute inset-0 rounded-[2.5rem] pointer-events-none border-t border-l border-white/30"></div>
                <div class="absolute inset-0 opacity-[0.03] bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none mix-blend-overlay no-print"></div>

                {{-- Ambient Radiant Glow Orbs (Ref 1 & 2) --}}
                <div class="absolute -top-16 -right-16 w-80 h-80 bg-[#56bbf1]/20 rounded-full blur-[100px] pointer-events-none"></div>
                <div class="absolute -bottom-16 -left-16 w-72 h-72 bg-[#0d52a1]/30 rounded-full blur-[90px] pointer-events-none"></div>
            
                <div class="relative z-10 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-8">
                    
                    {{-- KIRI: Judul, Intro, Chips & Navigasi --}}
                    <div class="space-y-4 max-w-2xl">
                        <div class="flex flex-wrap items-center gap-2">
                            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 border border-white/20 text-sky-300 text-xs font-bold uppercase tracking-wider backdrop-blur-md shadow-sm">
                                <i class="ph-bold ph-trend-up"></i> Evaluasi Kedisiplinan
                            </div>
                            <nav class="flex items-center gap-1.5 bg-white/10 backdrop-blur-md p-1 rounded-full border border-white/15">
                                <a href="{{ route('permit.index') }}" class="px-3 py-1 rounded-full text-xs font-bold text-slate-300 hover:text-white hover:bg-white/15 transition-all flex items-center gap-1.5">
                                    <i class="ph-bold ph-shield-check text-sky-400"></i> Pos Piket
                                </a>
                                <a href="{{ route('permit.history') }}" class="px-3 py-1 rounded-full text-xs font-bold text-slate-300 hover:text-white hover:bg-white/15 transition-all flex items-center gap-1.5">
                                    <i class="ph-bold ph-clock-counter-clockwise text-sky-400"></i> Riwayat
                                </a>
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-sky-500/30 text-sky-200 border border-sky-400/40 shadow-sm flex items-center gap-1.5">
                                    <i class="ph-bold ph-chart-polar text-amber-400"></i> Analitik
                                </span>
                            </nav>
                        </div>
                        
                        <h1 class="text-3xl sm:text-4xl md:text-5xl font-black tracking-tight leading-snug sm:leading-snug md:leading-normal text-white">
                            <span class="block text-slate-100">Statistik Tren &</span>
                            <span class="block mt-1 sm:mt-1.5 text-transparent bg-clip-text bg-gradient-to-r from-[#56bbf1] via-sky-200 to-[#38bdf8]">Analitik Perizinan</span>
                        </h1>
                        <p class="text-slate-300 text-sm md:text-base font-medium leading-relaxed">
                            Analisis jam sibuk, proporsi alasan, dan tingkat kepatuhan perizinan per kelas untuk evaluasi pembiasaan dan kedisiplinan siswa.
                        </p>

                        {{-- Feature Highlight Chips (Ref 1 & 2) --}}
                        <div class="flex flex-wrap items-center gap-2.5 pt-1">
                            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-white/5 border border-white/10 text-slate-200 text-xs font-semibold backdrop-blur-sm shadow-sm">
                                <i class="ph-bold ph-check text-sky-400"></i> Distribusi Jam Puncak
                            </div>
                            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-white/5 border border-white/10 text-slate-200 text-xs font-semibold backdrop-blur-sm shadow-sm">
                                <i class="ph-bold ph-check text-sky-400"></i> Proporsi Kategori Alasan
                            </div>
                            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-white/5 border border-white/10 text-slate-200 text-xs font-semibold backdrop-blur-sm shadow-sm">
                                <i class="ph-bold ph-check text-sky-400"></i> Top 5 Siswa Sering Izin
                            </div>
                        </div>
                    </div>

                    {{-- KANAN: GLOWING FOCAL SHOWCASE & MONTH FILTER (Ref 1 Glowing Ring & Floating Bubbles) --}}
                    <div class="relative flex items-center justify-center shrink-0 w-full lg:w-auto mt-4 lg:mt-0">
                        {{-- Multi-Layer Luminous Outer Ring --}}
                        <div class="absolute w-56 h-56 rounded-full border border-sky-400/20 bg-sky-500/5 blur-sm pointer-events-none"></div>
                        <div class="absolute w-48 h-48 rounded-full border border-sky-300/30 shadow-[0_0_40px_rgba(86,187,241,0.2)] pointer-events-none"></div>

                        {{-- Floating Micro Bubbles (Ref 1) --}}
                        <div class="absolute -top-2 -right-1 z-20 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-sky-300 text-[10px] font-bold shadow-lg flex items-center gap-1.5">
                            <i class="ph-bold ph-calendar text-sky-400"></i>
                            Periode Bulanan
                        </div>
                        <div class="absolute -bottom-2 -left-2 z-20 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-amber-300 text-[10px] font-bold shadow-lg flex items-center gap-1.5">
                            <i class="ph-bold ph-lightning text-amber-400"></i>
                            Auto Charts
                        </div>

                        {{-- Core Filter Card --}}
                        <div class="relative z-10 bg-[#031d3d]/90 backdrop-blur-xl border border-white/20 p-6 sm:p-7 rounded-[2.5rem] shadow-2xl flex flex-col items-center justify-center text-center min-w-[260px] group-hover:border-sky-400/40 transition-all">
                            <div class="w-14 h-14 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center text-3xl text-sky-400 shadow-inner mb-3">
                                <i class="ph-duotone ph-chart-polar"></i>
                            </div>
                            <form action="{{ route('permit.analytics') }}" method="GET" id="monthFilterForm" class="w-full">
                                <label for="monthFilter" class="block text-slate-300 text-[10px] font-bold uppercase tracking-widest mb-2">Pilih Periode Bulan</label>
                                <div class="relative w-full group">
                                    <input type="month" name="month" id="monthFilter" value="{{ $selectedMonth }}" 
                                        onchange="document.getElementById('monthFilterForm').submit()"
                                        class="w-full bg-[#021124]/90 hover:bg-[#021124] border border-white/15 text-white text-xs rounded-xl px-4 py-3 focus:ring-4 focus:ring-elevate-accent/20 focus:border-elevate-accent cursor-pointer transition-all shadow-sm font-bold text-center outline-none">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KPI CARDS --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 animate-enter delay-100">
                <div class="bg-[#031d3d]/80 backdrop-blur-md p-6 rounded-[2rem] shadow-sm border border-white/10 hover:shadow-xl hover:border-sky-400/30 transition-all duration-300 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-white/10 text-sky-400 border border-white/15 flex items-center justify-center text-2xl shrink-0"><i class="ph-bold ph-door-open"></i></div>
                    <div>
                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Total Izin</div>
                        <div class="text-2xl font-black text-white">{{ $kpiTotalMonth ?? 0 }}</div>
                    </div>
                </div>
                <div class="bg-[#031d3d]/80 backdrop-blur-md p-6 rounded-[2rem] shadow-sm border border-white/10 hover:shadow-xl hover:border-amber-400/30 transition-all duration-300 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-400 border border-amber-500/20 flex items-center justify-center text-2xl shrink-0"><i class="ph-bold ph-timer"></i></div>
                    <div>
                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Rata Durasi</div>
                        <div class="text-2xl font-black text-white">{{ $kpiAvgDuration ?? 0 }}<span class="text-xs text-slate-400 font-medium ml-1 font-sans">mnt</span></div>
                    </div>
                </div>
                <div class="bg-[#031d3d]/80 backdrop-blur-md p-6 rounded-[2rem] shadow-sm border border-white/10 hover:shadow-xl hover:border-rose-400/30 transition-all duration-300 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-rose-500/10 text-rose-400 border border-rose-500/20 flex items-center justify-center text-2xl shrink-0"><i class="ph-bold ph-warning"></i></div>
                    <div>
                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Siswa Telat</div>
                        <div class="text-2xl font-black text-rose-400">{{ $kpiOverdue ?? 0 }}</div>
                    </div>
                </div>
                <div class="bg-[#031d3d]/80 backdrop-blur-md p-6 rounded-[2rem] shadow-sm border border-white/10 hover:shadow-xl hover:border-emerald-400/30 transition-all duration-300 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 flex items-center justify-center text-2xl shrink-0"><i class="ph-bold ph-check-circle"></i></div>
                    <div>
                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Penyelesaian</div>
                        <div class="text-2xl font-black text-emerald-400">{{ $kpiCompletionRate ?? 100 }}<span class="text-xs text-slate-400 font-medium ml-1 font-sans">%</span></div>
                    </div>
                </div>
            </div>

            {{-- CHART GRID --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 md:gap-8">
                
                {{-- CHART 1: Jam Paling Sibuk --}}
                <div class="lg:col-span-8 bg-[#031d3d]/90 backdrop-blur-md p-6 md:p-8 rounded-[2.5rem] shadow-xl border border-white/10 animate-enter delay-200">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="font-extrabold text-white text-lg flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white/10 text-sky-400 border border-white/15 flex items-center justify-center"><i class="ph-bold ph-clock text-xl"></i></div>
                                Jam Keluar Paling Sibuk
                            </h3>
                            <p class="text-xs text-slate-300 font-medium mt-1 ml-12">Distribusi frekuensi izin siswa (Akumulasi Bulan {{ $parsedDate->translatedFormat('F Y') }}).</p>
                        </div>
                    </div>
                    <div class="relative h-[300px] w-full">
                        <canvas id="timeChart"></canvas>
                    </div>
                </div>

                {{-- TOP 5 SISWA SERING IZIN --}}
                <div class="lg:col-span-4 bg-[#031d3d]/90 backdrop-blur-md p-6 md:p-8 rounded-[2.5rem] shadow-xl border border-white/10 animate-enter delay-200 flex flex-col">
                    <div class="mb-4">
                        <h3 class="font-extrabold text-white text-lg flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-rose-500/10 text-rose-400 border border-rose-500/20 flex items-center justify-center"><i class="ph-bold ph-siren text-xl"></i></div>
                            Top 5 Sering Izin
                        </h3>
                        <p class="text-[10px] text-slate-400 uppercase tracking-widest mt-1 ml-12 font-bold">Bulan {{ $parsedDate->translatedFormat('F Y') }}</p>
                    </div>
                    
                    <div class="flex-1 overflow-y-auto pr-2 space-y-3 custom-scrollbar mt-2">
                        @forelse($topStudents ?? [] as $index => $student)
                            <div class="flex items-center justify-between p-4 rounded-2xl bg-[#021124]/60 border border-white/10 hover:border-sky-400/40 hover:bg-[#021124]/90 transition-all shadow-sm">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-xl flex items-center justify-center text-xs font-black text-white shadow-sm
                                        {{ $index == 0 ? 'bg-rose-600' : ($index == 1 ? 'bg-amber-600' : 'bg-slate-700 border border-white/10') }}">
                                        {{ $index + 1 }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-white truncate max-w-[120px]" title="{{ $student->name }}">{{ $student->name }}</div>
                                        <div class="text-[10px] text-slate-400 font-bold uppercase">{{ $student->class_name }}</div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <span class="block text-xl font-black text-rose-400 leading-none">{{ $student->total_izin }}</span>
                                    <span class="text-[9px] font-bold text-slate-400 uppercase">Kali</span>
                                </div>
                            </div>
                        @empty
                            <div class="h-full flex flex-col items-center justify-center text-slate-400 opacity-70 py-8">
                                <i class="ph-duotone ph-shield-check text-4xl mb-2 text-sky-400"></i>
                                <p class="text-xs text-center font-medium">Belum ada data menonjol bulan ini.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- CHART 2: Alasan (Doughnut Chart) --}}
                <div class="lg:col-span-4 bg-[#031d3d]/90 backdrop-blur-md p-6 md:p-8 rounded-[2.5rem] shadow-xl border border-white/10 animate-enter delay-300">
                    <div class="mb-6">
                        <h3 class="font-extrabold text-white text-lg flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-400 border border-amber-500/20 flex items-center justify-center"><i class="ph-bold ph-question text-xl"></i></div>
                            Proporsi Alasan
                        </h3>
                    </div>
                    <div class="relative h-[250px] w-full flex justify-center">
                        <canvas id="reasonChart"></canvas>
                    </div>
                </div>

                {{-- CHART 3: Kelas Terbanyak (Bar Chart) --}}
                <div class="lg:col-span-8 bg-[#031d3d]/90 backdrop-blur-md p-6 md:p-8 rounded-[2.5rem] shadow-xl border border-white/10 animate-enter delay-300">
                    <div class="mb-6">
                        <h3 class="font-extrabold text-white text-lg flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 flex items-center justify-center"><i class="ph-bold ph-users-three text-xl"></i></div>
                            Tingkat Izin Berdasarkan Kelas
                        </h3>
                        <p class="text-xs text-slate-300 font-medium mt-1 ml-12">Membantu mengevaluasi kedisiplinan masing-masing kelas.</p>
                    </div>
                    <div class="relative h-[250px] w-full">
                        <canvas id="classChart"></canvas>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Chart.defaults.font.family = "'Figtree', sans-serif";
            Chart.defaults.color = '#94a3b8'; 
            
            const timeLabels = {!! json_encode($timeLabels ?? []) !!};
            const timeData = {!! json_encode($timeData ?? []) !!};
            const reasonLabels = {!! json_encode($reasonLabels ?? []) !!};
            const reasonData = {!! json_encode($reasonData ?? []) !!};
            const classLabels = {!! json_encode($classLabels ?? []) !!};
            const classData = {!! json_encode($classData ?? []) !!};

            // Tema Warna Elevate Dark
            const colorPrimary = '#56bbf1';
            const colorAccent = '#0d52a1';
            const colorPeach = '#fbbf24';
            const colorRose = '#f43f5e';
            const colorEmerald = '#10b981';
            const colorDark = '#38bdf8';

            // 1. CHART JAM SIBUK
            if(document.getElementById('timeChart')) {
                new Chart(document.getElementById('timeChart'), {
                    type: 'line',
                    data: {
                        labels: timeLabels,
                        datasets: [{
                            label: 'Jumlah Izin',
                            data: timeData,
                            borderColor: colorPrimary, 
                            backgroundColor: 'rgba(86, 187, 241, 0.15)', 
                            borderWidth: 3,
                            tension: 0.4, 
                            fill: true,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: colorPrimary,
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { 
                                beginAtZero: true, 
                                grid: { borderDash: [5, 5], color: 'rgba(255, 255, 255, 0.08)' }, 
                                ticks: { precision: 0, color: '#94a3b8' } 
                            },
                            x: { 
                                grid: { display: false },
                                ticks: { color: '#94a3b8' }
                            }
                        }
                    }
                });
            }

            // 2. CHART ALASAN
            if(document.getElementById('reasonChart')) {
                new Chart(document.getElementById('reasonChart'), {
                    type: 'doughnut',
                    data: {
                        labels: reasonLabels,
                        datasets: [{
                            data: reasonData,
                            backgroundColor: [colorPrimary, colorEmerald, colorPeach, colorAccent, colorRose, colorDark], 
                            borderWidth: 2,
                            borderColor: '#031d3d',
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        cutout: '70%',
                        plugins: {
                            legend: { 
                                position: 'bottom', 
                                labels: { 
                                    padding: 20, 
                                    usePointStyle: true, 
                                    pointStyle: 'circle',
                                    color: '#cbd5e1'
                                } 
                            }
                        }
                    }
                });
            }

            // 3. CHART KELAS
            if(document.getElementById('classChart')) {
                new Chart(document.getElementById('classChart'), {
                    type: 'bar',
                    data: {
                        labels: classLabels,
                        datasets: [{
                            label: 'Total Izin',
                            data: classData,
                            // Warna merah jika lebih dari 20 (terlalu banyak)
                            backgroundColor: classData.map(val => val > 20 ? colorRose : colorPrimary), 
                            borderRadius: 8,
                            barThickness: 30
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { 
                                beginAtZero: true, 
                                grid: { borderDash: [5, 5], color: 'rgba(255, 255, 255, 0.08)' }, 
                                ticks: { precision: 0, color: '#94a3b8' } 
                            },
                            x: { 
                                grid: { display: false },
                                ticks: { color: '#94a3b8' }
                            }
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>