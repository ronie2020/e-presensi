<x-app-layout>
    {{-- CUSTOM STYLES UNTUK ANIMASI --}}
    <style>
        /* 1. Animasi Masuk (Fade In Up) */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-enter {
            opacity: 0;
            animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        /* 2. Animasi Background Hero (Floating) */
        @keyframes float {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(-10px, -15px); }
        }
        @keyframes float-reverse {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(10px, 15px); }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }
        .animate-float-reverse { animation: float-reverse 7s ease-in-out infinite; }

        /* 3. Animasi Wiggle untuk Icon saat Hover */
        @keyframes wiggle {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(-10deg); }
            75% { transform: rotate(10deg); }
        }
        .group:hover .animate-wiggle {
            animation: wiggle 0.5s ease-in-out;
        }
        
        /* 4. Shimmer Text Effect */
        @keyframes shimmer {
            0% { background-position: -200% center; }
            100% { background-position: 200% center; }
        }
        .text-shimmer {
            background: linear-gradient(to right, #1e293b 0%, #94a3b8 20%, #1e293b 40%, #1e293b 100%);
            background-size: 200% auto;
            color: #1e293b;
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: shimmer 3s linear infinite;
        }
    </style>

    {{-- WRAPPER UTAMA (ALPINE.JS) --}}
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
        }" class="relative space-y-8 min-h-screen pb-10 font-sans text-slate-800">
        
        {{-- LOADING OVERLAY --}}
        <div x-show="loading" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center"
             style="display: none;">
            <div class="flex flex-col items-center animate-bounce">
                <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/50 mb-4">
                    <i class="ph-spinner animate-spin text-2xl text-white"></i>
                </div>
                <span class="text-sm font-bold text-white tracking-wide">Memuat data...</span>
            </div>
        </div>

        {{-- HERO SECTION --}}
        <div class="animate-enter relative rounded-[2.5rem] bg-gradient-to-r from-blue-900 via-blue-800 to-slate-900 p-8 sm:p-10 mb-8 text-white shadow-xl shadow-blue-900/20 overflow-hidden print:hidden group border border-white/10">
            
            {{-- Background Decorations --}}
            <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>

            {{-- Floating Blobs --}}
            <div class="absolute -top-24 -right-24 w-80 h-80 bg-blue-400/10 rounded-full blur-3xl pointer-events-none animate-float transition-all duration-700 group-hover:bg-blue-400/20"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-indigo-600/20 rounded-full blur-3xl -ml-10 -mb-10 pointer-events-none animate-float-reverse"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-200 text-[10px] font-bold uppercase tracking-widest mb-3 backdrop-blur-sm animate-pulse">
                        <i class="ph-fill ph-chart-line-up"></i> Real-time Monitoring
                    </div>
                    <h1 class="text-2xl md:text-4xl font-black mb-2 tracking-tight drop-shadow-md">Dashboard Monitoring 👋</h1>
                    <p class="text-blue-100/80 max-w-xl text-sm leading-relaxed font-medium">
                        Ringkasan aktivitas kehadiran siswa periode 
                        <span class="font-bold text-white bg-blue-500/30 border border-blue-400/30 px-2 py-0.5 rounded shadow-sm" x-text="period === 'today' ? 'Hari Ini' : (period === 'week' ? 'Minggu Ini' : 'Bulan Ini')"></span>.
                    </p>
                </div>
                
                {{-- Filter Controls --}}
                <div class="flex flex-col gap-3 min-w-[280px]">
                    <div class="bg-slate-900/40 backdrop-blur-md p-1.5 rounded-2xl flex border border-white/10 shadow-inner">
                        <button @click="updateFilter('today')" 
                            :class="period === 'today' ? 'bg-white text-blue-900 shadow-lg scale-[1.02]' : 'text-blue-200 hover:bg-white/10 hover:text-white'" 
                            class="flex-1 py-2.5 text-xs font-black rounded-xl transition-all duration-300">
                            Harian
                        </button>
                        <button @click="updateFilter('week')" 
                            :class="period === 'week' ? 'bg-white text-blue-900 shadow-lg scale-[1.02]' : 'text-blue-200 hover:bg-white/10 hover:text-white'" 
                            class="flex-1 py-2.5 text-xs font-black rounded-xl transition-all duration-300">
                            Mingguan
                        </button>
                        <button @click="updateFilter('month')" 
                            :class="period === 'month' ? 'bg-white text-blue-900 shadow-lg scale-[1.02]' : 'text-blue-200 hover:bg-white/10 hover:text-white'" 
                            class="flex-1 py-2.5 text-xs font-black rounded-xl transition-all duration-300">
                            Bulanan
                        </button>
                    </div>

                    <div class="flex gap-2">
                        <div class="relative flex-1 group/date">
                             <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="ph-calendar text-blue-300 group-hover/date:text-white transition-colors"></i>
                            </div>
                            <input x-show="period === 'today'" type="date" x-model="date" @change="updateFilter('today')" 
                                class="w-full bg-slate-900/40 border border-white/10 text-white placeholder-blue-300/50 text-xs font-bold rounded-xl pl-9 pr-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-400/50 cursor-pointer transition-all hover:bg-slate-900/60">
                            <input x-show="period === 'week'" type="week" 
                                class="w-full bg-slate-900/40 border border-white/10 text-white placeholder-blue-300/50 text-xs font-bold rounded-xl pl-9 pr-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-400/50 cursor-pointer transition-all hover:bg-slate-900/60">
                             <input x-show="period === 'month'" type="month" 
                                class="w-full bg-slate-900/40 border border-white/10 text-white placeholder-blue-300/50 text-xs font-bold rounded-xl pl-9 pr-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-400/50 cursor-pointer transition-all hover:bg-slate-900/60">
                        </div>
                        
                        <button @click="printDashboard()" class="bg-white text-blue-900 p-2.5 rounded-xl hover:bg-blue-50 hover:scale-105 active:scale-95 transition-all shadow-sm shrink-0 border border-white/20" title="Print Laporan">
                            <i class="ph-printer text-lg font-bold"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- 
            =========================================
            BAGIAN 1: KPI CARDS (FIXED ICONS)
            =========================================
        --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-5">
            @foreach($cards as $index => $card)
            
            {{-- LOGIC PENENTUAN ICON OTOMATIS BERDASARKAN JUDUL --}}
            @php
                $titleLower = strtolower($card['title']);
                $iconClass = $card['icon'] ?? ''; 

                // Jika icon dari database kosong atau default 'ph-hash', kita paksa pakai icon yang sesuai
                if (empty($iconClass) || $iconClass == 'ph-hash') {
                    if (str_contains($titleLower, 'hadir')) {
                        $iconClass = 'ph-check-circle';
                    } elseif (str_contains($titleLower, 'telat') || str_contains($titleLower, 'lambat')) {
                        $iconClass = 'ph-clock-countdown';
                    } elseif (str_contains($titleLower, 'izin')) {
                        $iconClass = 'ph-envelope-open';
                    } elseif (str_contains($titleLower, 'sakit')) {
                        $iconClass = 'ph-bandaids'; // atau ph-thermometer
                    } elseif (str_contains($titleLower, 'alpha') || str_contains($titleLower, 'absen')) {
                        $iconClass = 'ph-x-circle';
                    } elseif (str_contains($titleLower, 'total') || str_contains($titleLower, 'siswa')) {
                        $iconClass = 'ph-users-three';
                    } else {
                        $iconClass = 'ph-chart-bar'; // Default aman
                    }
                }
            @endphp

            <a href="{{ url('attendance') }}?status={{ $card['filter_status'] }}&period={{ request('period', 'today') }}" 
               class="animate-enter group bg-white relative overflow-hidden rounded-[1.5rem] shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] hover:shadow-xl hover:shadow-blue-500/10 border border-slate-100 hover:border-blue-200 transition-all duration-300 hover:-translate-y-1.5 h-full cursor-pointer p-6 flex flex-col justify-between"
               style="animation-delay: {{ ($index + 1) * 100 }}ms">
                
                {{-- Decorative Watermark Icon (Besar & Transparan) --}}
                <i class="{{ $iconClass }} ph-duotone absolute -right-6 -bottom-6 text-[8rem] opacity-[0.05] text-slate-900 transform -rotate-[15deg] group-hover:scale-110 group-hover:rotate-0 transition-transform duration-500"></i>
                
                <div class="flex justify-between items-start mb-4 relative z-10">
                    {{-- Icon Container (Fixed: Tambah class ph-duotone) --}}
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center {{ $card['icon_bg'] ?? 'bg-slate-50' }} {{ $card['icon_color'] ?? 'text-slate-500' }} shadow-sm group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                        <i class="{{ $iconClass }} ph-duotone text-2xl animate-wiggle"></i>
                    </div>
                    
                    @if(isset($card['percentage']))
                    <span class="text-[10px] font-black px-2 py-1 rounded-lg border {{ $card['percentage'] > 0 ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-rose-50 text-rose-600 border-rose-100' }}">
                        {{ $card['percentage'] > 0 ? '+' : '' }}{{ $card['percentage'] }}%
                    </span>
                    @endif
                </div>

                <div class="relative z-10">
                    <p class="text-[11px] font-bold text-slate-400 mb-1 uppercase tracking-widest">{{ $card['title'] }}</p>
                    {{-- Count Up Number --}}
                    <h3 class="text-3xl font-black {{ $card['text_color'] }} tracking-tight count-up" data-target="{{ $card['value'] }}">
                        0
                    </h3>
                </div>
                
                {{-- Arrow Decoration (Slide in) --}}
                <div class="absolute top-6 right-6 opacity-0 -translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300">
                    <i class="ph-bold ph-arrow-right text-slate-300 text-lg"></i>
                </div>
            </a>
            @endforeach
        </div>

        {{-- BAGIAN 2: GRAFIK & KOMPOSISI --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            {{-- Grafik Batang --}}
            <div class="animate-enter xl:col-span-2 bg-white p-6 sm:p-8 rounded-[2rem] shadow-sm border border-slate-100" style="animation-delay: 600ms">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-lg font-black text-slate-800 flex items-center gap-2">
                            <i class="ph-fill ph-chart-bar text-blue-600"></i> Analisis Tren
                        </h3>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wide mt-1">Statistik harian selama periode ini</p>
                    </div>
                    {{-- Legend Custom --}}
                    <div class="flex gap-2 hidden sm:flex bg-slate-50 p-1.5 rounded-xl border border-slate-100">
                        <div class="px-3 py-1.5 rounded-lg bg-white border border-slate-100 shadow-sm flex items-center gap-2 text-[10px] font-bold text-slate-600 uppercase tracking-wide">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Tepat
                        </div>
                        <div class="px-3 py-1.5 rounded-lg bg-white border border-slate-100 shadow-sm flex items-center gap-2 text-[10px] font-bold text-slate-600 uppercase tracking-wide">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span> Telat
                        </div>
                        <div class="px-3 py-1.5 rounded-lg bg-white border border-slate-100 shadow-sm flex items-center gap-2 text-[10px] font-bold text-slate-600 uppercase tracking-wide">
                            <span class="w-2 h-2 rounded-full bg-red-500"></span> Absen
                        </div>
                    </div>
                </div>
                <div class="relative h-80 w-full">
                    <canvas id="weeklyChart"></canvas>
                </div>
            </div>

            {{-- Donut Chart & Ringkasan --}}
            <div class="animate-enter bg-white p-6 sm:p-8 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col h-full" style="animation-delay: 700ms">
                <h3 class="text-lg font-black text-slate-800 mb-6 flex items-center gap-2">
                    <i class="ph-fill ph-chart-pie-slice text-blue-500"></i> Komposisi Data
                </h3>
                
                <div class="relative h-60 w-full flex items-center justify-center mb-8">
                    <canvas id="dailyDonutChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-4xl font-black text-slate-800 count-up" data-target="{{ $totalStudents }}">0</span>
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Total Siswa</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 mt-auto">
                    <div class="bg-emerald-50/50 p-4 rounded-2xl border border-emerald-100 hover:bg-emerald-50 transition-colors">
                        <span class="block text-[10px] font-black text-emerald-600 uppercase mb-1 tracking-wider">Hadir</span>
                        <span class="text-xl font-black text-slate-800">{{ $presentOnTimeCount }}</span>
                    </div>
                    <div class="bg-amber-50/50 p-4 rounded-2xl border border-amber-100 hover:bg-amber-50 transition-colors">
                        <span class="block text-[10px] font-black text-amber-600 uppercase mb-1 tracking-wider">Telat</span>
                        <span class="text-xl font-black text-slate-800">{{ $lateCount }}</span>
                    </div>
                    <div class="bg-red-50/50 p-4 rounded-2xl border border-red-100 hover:bg-red-50 transition-colors">
                        <span class="block text-[10px] font-black text-red-600 uppercase mb-1 tracking-wider">Absen</span>
                        <span class="text-xl font-black text-slate-800">{{ $absentCount }}</span>
                    </div>
                    <div class="bg-blue-50/50 p-4 rounded-2xl border border-blue-100 hover:bg-blue-50 transition-colors">
                        <span class="block text-[10px] font-black text-blue-600 uppercase mb-1 tracking-wider">Izin</span>
                        <span class="text-xl font-black text-slate-800">{{ $sickPermitCount }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- BAGIAN 3: TABLES & FEED --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            {{-- A. LOG AKTIVITAS --}}
            <div class="animate-enter bg-white p-6 sm:p-8 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col h-full" style="animation-delay: 800ms" x-data="{ tab: 'activity' }">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex gap-4 border-b border-slate-100 w-full">
                        <button @click="tab = 'activity'" 
                            :class="tab === 'activity' ? 'text-blue-900 border-blue-600' : 'text-slate-400 border-transparent hover:text-slate-600'"
                            class="text-sm font-black pb-3 border-b-2 transition-all px-2">
                            Aktivitas Terbaru
                        </button>
                        <button @click="tab = 'late_recap'" 
                            :class="tab === 'late_recap' ? 'text-amber-600 border-amber-500' : 'text-slate-400 border-transparent hover:text-slate-600'"
                            class="text-sm font-black pb-3 border-b-2 transition-all px-2">
                            Top Terlambat
                        </button>
                    </div>
                </div>

                {{-- Tab 1: Live Feed --}}
                <div x-show="tab === 'activity'" x-transition:enter="transition ease-out duration-200" class="flex-1 overflow-y-auto max-h-[400px] custom-scrollbar pr-2">
                    @if(count($recentActivities) > 0)
                        <div class="relative pl-6 border-l-2 border-slate-100 space-y-6 py-2 ml-2">
                            @foreach($recentActivities as $log)
                            <div class="relative group">
                                <div class="absolute -left-[31px] top-1.5 h-4 w-4 rounded-full border-[3px] border-white ring-1 ring-slate-200 {{ $log->status == 'Terlambat' ? 'bg-amber-400 shadow-[0_0_10px_rgba(251,191,36,0.5)]' : 'bg-emerald-400 shadow-[0_0_10px_rgba(52,211,153,0.5)]' }}"></div>
                                <div class="flex items-start justify-between gap-3 p-3 rounded-2xl hover:bg-slate-50 transition-colors -mt-2 -ml-2">
                                    <div class="flex items-center gap-3">
                                        {{-- Avatar Initials --}}
                                        <div class="w-10 h-10 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-700 font-black text-sm shrink-0 shadow-sm">
                                            {{ substr($log->student->name ?? '?', 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-slate-800 line-clamp-1 group-hover:text-blue-700 transition-colors">{{ $log->student->name ?? 'Siswa' }}</p>
                                            <p class="text-[11px] text-slate-500 font-bold bg-slate-100 px-1.5 py-0.5 rounded-md inline-block mt-1">{{ $log->student->schoolClass->name ?? '-' }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right shrink-0">
                                        <p class="text-xs font-bold font-mono text-slate-600 mb-1">
                                            {{ \Carbon\Carbon::parse($log->created_at)->format('H:i') }}
                                        </p>
                                        <span class="text-[10px] font-black px-2 py-1 rounded-lg {{ $log->status == 'Terlambat' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">
                                            {{ $log->status }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center h-40 text-center text-slate-400">
                            <i class="ph-duotone ph-coffee text-4xl mb-3 opacity-30 animate-bounce"></i>
                            <p class="text-xs font-bold">Belum ada aktivitas baru hari ini</p>
                        </div>
                    @endif
                </div>

                {{-- Tab 2: Top Late Students --}}
                <div x-show="tab === 'late_recap'" x-transition:enter="transition ease-out duration-200" style="display: none;" class="flex-1 overflow-y-auto max-h-[400px] custom-scrollbar">
                    @if(count($topLateStudents) > 0)
                        <div class="space-y-2">
                            @foreach($topLateStudents as $index => $student)
                            <div class="flex items-center justify-between p-3 rounded-2xl border border-slate-50 hover:bg-red-50/30 hover:border-red-100 transition-all group">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 font-bold text-xs flex items-center justify-center border border-slate-200">
                                        #{{ $index + 1 }}
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-slate-800">{{ $student->student->name ?? 'Siswa' }}</div>
                                        <div class="text-[10px] text-slate-400">{{ $student->student->schoolClass->name ?? '-' }}</div>
                                    </div>
                                </div>
                                <span class="text-xs font-black text-red-600 bg-red-50 px-3 py-1.5 rounded-xl border border-red-100 group-hover:scale-110 transition-transform">
                                    {{ $student->total_late }}x
                                </span>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-10 text-slate-400 text-xs font-bold">
                            <i class="ph-duotone ph-smiley text-2xl mb-2"></i><br>
                            Tidak ada data keterlambatan signifikan
                        </div>
                    @endif
                </div>
            </div>

            {{-- B. PERFORMA KELAS (RANKING) --}}
            <div class="animate-enter bg-white p-6 sm:p-8 rounded-[2rem] shadow-sm border border-slate-100 h-full" style="animation-delay: 900ms">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-black text-slate-800 flex items-center gap-2">
                        <i class="ph-fill ph-trophy text-yellow-400 text-xl drop-shadow-sm"></i> Top Kelas
                    </h3>
                    <span class="text-[10px] bg-indigo-50 text-indigo-600 border border-indigo-100 px-2 py-1 rounded-lg font-bold uppercase tracking-wide">Terajin</span>
                </div>

                @if(count($classRanks) > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <tbody class="divide-y divide-slate-50">
                            @foreach($classRanks as $index => $rank)
                            <tr class="group hover:bg-slate-50 transition-colors">
                                <td class="py-4 pl-2 w-10">
                                    @if($index == 0)
                                        <i class="ph-fill ph-medal text-yellow-400 text-2xl drop-shadow-sm"></i>
                                    @elseif($index == 1)
                                        <i class="ph-fill ph-medal text-slate-300 text-xl"></i>
                                    @elseif($index == 2)
                                        <i class="ph-fill ph-medal text-amber-600 text-xl"></i>
                                    @else
                                        <span class="font-bold text-slate-300 text-sm ml-1.5">#{{ $index + 1 }}</span>
                                    @endif
                                </td>
                                <td class="py-4">
                                    <div class="font-black text-slate-700 text-sm mb-1">{{ $rank->class_name }}</div>
                                    <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden max-w-[120px]">
                                        @php $percent = min(100, ($rank->present_count / 40) * 100); @endphp
                                        <div class="h-1.5 rounded-full {{ $index == 0 ? 'bg-yellow-400' : 'bg-emerald-500' }}" style="width: {{ $percent }}%"></div>
                                    </div>
                                </td>
                                <td class="py-4 text-right pr-2">
                                    <div class="text-sm font-black text-slate-800">{{ number_format($percent, 0) }}%</div>
                                    <div class="text-[10px] text-slate-400 font-bold">{{ $rank->present_count }} Hadir</div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                 <div class="flex flex-col items-center justify-center h-40 text-center text-slate-400">
                    <p class="text-xs font-bold">Data peringkat kelas belum tersedia</p>
                </div>
                @endif
            </div>

        </div>

    </div>

    {{-- SCRIPT CHART.JS & ANIMASI ANGKA --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // 1. COUNT UP ANIMATION LOGIC
            const counters = document.querySelectorAll('.count-up');
            const animateCounter = (counter) => {
                const target = +counter.getAttribute('data-target');
                const duration = 2000; 
                const frameRate = 16;
                const totalFrames = duration / frameRate;
                const increment = target / totalFrames;
                
                let current = 0;
                const updateCount = () => {
                    current += increment;
                    if (current < target) {
                        counter.innerText = Math.ceil(current).toLocaleString('id-ID');
                        requestAnimationFrame(updateCount);
                    } else {
                        counter.innerText = target.toLocaleString('id-ID');
                    }
                };
                updateCount();
            };
            counters.forEach(counter => animateCounter(counter));

            // Default Config Charts
            Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
            Chart.defaults.color = '#94a3b8';
            Chart.defaults.scale.grid.color = '#f1f5f9';

            // Data from Controller
            const rawPresentData = @json($weeklyPresentData ?? []);
            const rawLateData    = @json($weeklyLateData ?? []);
            const rawAbsentData  = @json($weeklyAbsentData ?? []);
            const chartLabels    = @json($chartLabels ?? []);

            const d_presentOnTime = @json($presentOnTimeCount ?? 0);
            const d_late          = @json($lateCount ?? 0);
            const d_absent        = @json($absentCount ?? 0); 
            const d_excused       = @json($sickPermitCount ?? 0);

            // 2. CHART MINGGUAN (BAR)
            const ctxWeekly = document.getElementById('weeklyChart').getContext('2d');
            new Chart(ctxWeekly, {
                type: 'bar',
                data: {
                    labels: chartLabels,
                    datasets: [
                        { label: 'Hadir', data: rawPresentData, backgroundColor: '#10b981', hoverBackgroundColor: '#059669', borderRadius: 8, barPercentage: 0.5, stack: 'main' },
                        { label: 'Telat', data: rawLateData, backgroundColor: '#f59e0b', hoverBackgroundColor: '#d97706', borderRadius: 8, barPercentage: 0.5, stack: 'main' },
                        { label: 'Absen', data: rawAbsentData, backgroundColor: '#ef4444', hoverBackgroundColor: '#dc2626', borderRadius: 8, barPercentage: 0.5, stack: 'main' }
                    ]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    animation: {
                        duration: 2000,
                        easing: 'easeOutQuart'
                    },
                    plugins: { 
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e293b', padding: 12, titleFont: { size: 13, weight: 'bold' }, bodyFont: { size: 12 }, cornerRadius: 12, displayColors: true,
                            callbacks: { label: function(context) { return ' ' + context.dataset.label + ': ' + context.parsed.y + ' Siswa'; } }
                        }
                    }, 
                    scales: { 
                        y: { beginAtZero: true, stacked: true, border: { display: false }, grid: { borderDash: [4, 4], drawBorder: false }, ticks: { padding: 10, font: {weight: 'bold'} } }, 
                        x: { stacked: true, grid: { display: false, drawBorder: false }, ticks: { font: {weight: 'bold'} } } 
                    }
                }
            });

            // 3. CHART HARIAN (DONUT)
            const ctxDonut = document.getElementById('dailyDonutChart').getContext('2d');
            new Chart(ctxDonut, {
                type: 'doughnut',
                data: {
                    labels: ['Hadir', 'Telat', 'Absen', 'Izin'],
                    datasets: [{
                        data: [d_presentOnTime, d_late, d_absent, d_excused],
                        backgroundColor: ['#10b981', '#f59e0b', '#ef4444', '#3b82f6'], 
                        borderWidth: 0, hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false, cutout: '85%', 
                    animation: {
                        animateScale: true,
                        animateRotate: true,
                        duration: 2000,
                        easing: 'easeOutBounce'
                    },
                    plugins: { 
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e293b', bodyFont: { size: 12, weight: 'bold' }, padding: 12, cornerRadius: 12,
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
        });
    </script>
</x-app-layout>