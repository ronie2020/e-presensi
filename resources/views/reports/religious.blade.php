<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js" defer></script>
    {{-- Menggunakan ApexCharts untuk grafik yang modern dan responsif --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    {{-- CUSTOM STYLES --}}
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        @keyframes wiggle { 0%, 100% { transform: rotate(0deg); } 25% { transform: rotate(-10deg); } 75% { transform: rotate(10deg); } }
        .group:hover .animate-wiggle { animation: wiggle 0.5s ease-in-out; }
        @media print {
            .no-print { display: none !important; }
            body { background-color: white; }
            [x-show] { display: block !important; }
            .print-table { display: table !important; width: 100%; border-collapse: collapse; }
            .print-table th, .print-table td { border: 1px solid #000; padding: 5px; font-size: 10pt; }
            /* Sembunyikan view list saat print jika sedang mode rekap, dan sebaliknya */
            .view-list { display: none; } 
        }
    </style>

    {{-- Wrapper Utama --}}
    <div class="py-6 md:py-8 font-sans text-slate-800 pb-32" x-data="{ 
        activeTab: '{{ request('activeTab', 'hadir') }}',
        reportType: '{{ request('report_type', 'daily') }}',
        viewMode: 'list', 
        loading: false, 
        
        navigate(url) {
            this.loading = true;
            setTimeout(() => { window.location.href = url; }, 200);
        },
        submitFilter() {
            this.loading = true;
            setTimeout(() => { this.$el.closest('form').submit(); }, 200);
        }
    }">

        {{-- LOADING OVERLAY --}}
        <div x-show="loading" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             style="display: none;" 
             class="fixed inset-0 z-[100] bg-slate-900/40 backdrop-blur-[2px] flex items-center justify-center">
            
            <div class="bg-white p-6 rounded-2xl shadow-2xl flex flex-col items-center transform transition-all scale-100">
                <div class="relative w-12 h-12 mb-4">
                    <div class="absolute inset-0 rounded-full border-4 border-slate-100"></div>
                    <div class="absolute inset-0 rounded-full border-4 border-blue-600 border-t-transparent animate-spin"></div>
                </div>
                <span class="text-xs font-bold text-slate-700 tracking-wider uppercase animate-pulse">Memuat Data...</span>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HERO SECTION --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8 no-print">
                <div class="animate-enter bg-gray-900 bg-gradient-to-br from-blue-900 via-blue-800 to-slate-900 rounded-[2rem] p-6 lg:p-8 text-white shadow-xl shadow-blue-900/30 relative overflow-hidden flex flex-col justify-between min-h-[180px] lg:min-h-[200px] border border-white/10 group">
                    <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                    <div class="relative z-10">
                        <h1 class="text-xl lg:text-2xl font-extrabold mb-1 tracking-tight text-white flex items-center gap-2">
                            Rekap Keagamaan
                        </h1>
                        <p class="text-blue-300 text-sm font-medium tracking-wide">Laporan ibadah siswa.</p>
                    </div>

                    <div class="relative z-10 mt-6 bg-slate-900/50 p-1.5 rounded-2xl flex border border-white/10 backdrop-blur-sm">
                        <button @click="navigate('{{ route('reports.religious', array_merge(request()->all(), ['activity' => 'Dhuha'])) }}')" 
                           class="flex-1 text-center py-2.5 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2 {{ $selectedActivity == 'Dhuha' ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'text-blue-300 hover:bg-white/5 hover:text-white' }}">
                            <i class="ph-bold ph-sun text-lg {{ $selectedActivity == 'Dhuha' ? 'text-yellow-300' : '' }}"></i> Dhuha
                        </button>
                        <button @click="navigate('{{ route('reports.religious', array_merge(request()->all(), ['activity' => 'Dhuhur'])) }}')" 
                           class="flex-1 text-center py-2.5 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2 {{ $selectedActivity == 'Dhuhur' ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'text-blue-300 hover:bg-white/5 hover:text-white' }}">
                            <i class="ph-fill ph-moon-stars text-lg {{ $selectedActivity == 'Dhuhur' ? 'text-white' : '' }}"></i> Dhuhur
                        </button>
                    </div>
                </div>

                <div class="animate-enter lg:col-span-2 bg-white rounded-[2rem] p-6 lg:p-8 border border-slate-100 shadow-sm relative overflow-hidden" style="animation-delay: 100ms">
                    <div class="absolute inset-0 opacity-40 bg-[radial-gradient(#cbd5e1_1px,transparent_1px)] [background-size:20px_20px]"></div>
                    <div class="relative z-10">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-4">
                            <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                                <span class="w-1.5 h-6 bg-blue-900 rounded-full"></span>
                                Filter Data
                            </h2>
                            <div class="bg-slate-100 p-1 rounded-xl flex w-full md:w-auto">
                                <button @click="reportType = 'daily'" :class="reportType === 'daily' ? 'bg-white text-slate-900 shadow-sm ring-1 ring-slate-200' : 'text-slate-500 hover:text-slate-700'" class="flex-1 px-4 py-2 rounded-lg text-xs font-bold transition-all text-center">Harian</button>
                                <button @click="reportType = 'weekly'" :class="reportType === 'weekly' ? 'bg-white text-slate-900 shadow-sm ring-1 ring-slate-200' : 'text-slate-500 hover:text-slate-700'" class="flex-1 px-4 py-2 rounded-lg text-xs font-bold transition-all text-center">Mingguan</button>
                                <button @click="reportType = 'monthly'" :class="reportType === 'monthly' ? 'bg-white text-slate-900 shadow-sm ring-1 ring-slate-200' : 'text-slate-500 hover:text-slate-700'" class="flex-1 px-4 py-2 rounded-lg text-xs font-bold transition-all text-center">Bulanan</button>
                            </div>
                        </div>

                        <form action="{{ route('reports.religious') }}" method="GET" class="flex flex-col md:flex-row gap-3 w-full" @submit.prevent="submitFilter">
                            <input type="hidden" name="activity" value="{{ $selectedActivity }}">
                            <input type="hidden" name="activeTab" x-model="activeTab">
                            <input type="hidden" name="report_type" x-model="reportType">
                            
                            <div class="flex-1 w-full">
                                <div x-show="reportType === 'daily'">
                                    <input type="date" name="date" value="{{ request('date', $selectedDate_db->format('Y-m-d')) }}" 
                                           class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold h-11 text-sm px-4 focus:ring-blue-900 focus:border-blue-900 shadow-sm">
                                </div>
                                <div x-show="reportType === 'weekly'" style="display: none;">
                                    <input type="week" name="week" value="{{ request('week', date('Y-\WW')) }}" 
                                           class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold h-11 text-sm px-4 focus:ring-blue-900 focus:border-blue-900 shadow-sm">
                                </div>
                                <div x-show="reportType === 'monthly'" style="display: none;">
                                    <input type="month" name="month" value="{{ request('month', date('Y-m')) }}" 
                                           class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold h-11 text-sm px-4 focus:ring-blue-900 focus:border-blue-900 shadow-sm">
                                </div>
                            </div>

                            <div class="flex gap-2 w-full md:w-auto">
                                <button type="submit" class="flex-1 md:flex-none bg-blue-900 hover:bg-slate-900 text-white px-5 rounded-xl h-11 font-bold text-sm shadow-lg flex items-center justify-center gap-2 transition-all active:scale-95">
                                    <i class="ph-bold ph-magnifying-glass"></i> <span class="md:hidden">Cari</span>
                                </button>
                                <div class="w-px h-11 bg-slate-200 hidden md:block"></div>
                                {{-- TOMBOL CETAK UTAMA (Default Mode: List) --}}
                                <a href="{{ route('reports.printReligious', request()->all()) }}" target="_blank" class="flex-1 md:flex-none bg-white border border-slate-200 text-slate-600 hover:text-blue-900 hover:border-blue-900 px-5 rounded-xl h-11 font-bold text-sm flex items-center justify-center gap-2 transition-colors active:scale-95">
                                    <i class="ph-bold ph-printer text-lg"></i> <span class="md:hidden">Cetak</span>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- 
                ============================================
                DASHBOARD VISUAL (NEW FEATURE)
                ============================================
            --}}
            <div class="mb-8 no-print">
                {{-- Row 1: Statistik Card --}}
                <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 md:gap-4 mb-6">
                    <!-- Total Siswa -->
                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100">
                        <p class="text-[10px] md:text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Total Siswa</p>
                        <div class="flex items-center gap-3">
                            <h3 class="text-2xl md:text-3xl font-black text-slate-800">{{ $hadirCount + $izinUzurCount + $alfaCount + $belumAbsenCount }}</h3>
                            <div class="p-1.5 bg-slate-100 rounded-lg text-slate-500"><i class="ph-bold ph-users"></i></div>
                        </div>
                    </div>
                    
                    <!-- Total Hadir -->
                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100">
                        <p class="text-[10px] md:text-xs font-bold text-emerald-600/70 uppercase tracking-wider mb-2">Total Hadir</p>
                        <div class="flex items-center gap-3">
                            <h3 class="text-2xl md:text-3xl font-black text-slate-800">{{ $hadirCount }}</h3>
                            <div class="p-1.5 bg-emerald-50 rounded-lg text-emerald-600"><i class="ph-fill ph-check-circle"></i></div>
                        </div>
                    </div>

                    <!-- Belum Hadir (Belum Absen + Alfa) -->
                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100">
                        <p class="text-[10px] md:text-xs font-bold text-rose-600/70 uppercase tracking-wider mb-2">Belum Hadir</p>
                        <div class="flex items-center gap-3">
                            <h3 class="text-2xl md:text-3xl font-black text-slate-800">{{ $belumAbsenCount + $alfaCount }}</h3>
                            <div class="p-1.5 bg-rose-50 rounded-lg text-rose-600"><i class="ph-fill ph-x-circle"></i></div>
                        </div>
                    </div>
                    
                    <!-- Sakit / Izin -->
                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100">
                        <p class="text-[10px] md:text-xs font-bold text-blue-600/70 uppercase tracking-wider mb-2">Sakit / Izin</p>
                        <div class="flex items-center gap-3">
                            <h3 class="text-2xl md:text-3xl font-black text-slate-800">{{ $izinUzurCount }}</h3>
                            <div class="p-1.5 bg-blue-50 rounded-lg text-blue-600"><i class="ph-fill ph-info"></i></div>
                        </div>
                    </div>

                     <!-- Persentase -->
                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 col-span-2 lg:col-span-1 flex items-center justify-between">
                         <div>
                            <p class="text-[10px] md:text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Kehadiran</p>
                             @php
                                $totalAll = $hadirCount + $izinUzurCount + $alfaCount + $belumAbsenCount;
                                $percentage = $totalAll > 0 ? round(($hadirCount / $totalAll) * 100) : 0;
                            @endphp
                            <h3 class="text-2xl md:text-3xl font-black text-slate-800">{{ $percentage }}%</h3>
                        </div>
                        <div class="w-12 h-12 relative flex items-center justify-center">
                            {{-- Simple SVG Donut --}}
                            <svg viewBox="0 0 36 36" class="w-full h-full text-blue-600 transform -rotate-90">
                                <path class="text-slate-100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="4" />
                                <path class="text-current" stroke-dasharray="{{ $percentage }}, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="4" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Row 2: Charts Area --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {{-- Chart 1: Tren Kehadiran --}}
                    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 lg:col-span-2">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                                    <i class="ph-fill ph-chart-bar text-blue-600"></i> Analisis Tren Kehadiran
                                </h3>
                                <p class="text-xs text-slate-500 font-medium">{{ $chartData['trendLabel'] ?? 'Statistik' }}</p>
                            </div>
                            <div class="flex gap-2">
                                <span class="px-2 py-1 bg-emerald-50 text-emerald-700 rounded text-[10px] font-bold uppercase">Hadir</span>
                                <span class="px-2 py-1 bg-rose-50 text-rose-700 rounded text-[10px] font-bold uppercase">Absen</span>
                            </div>
                        </div>
                        <div id="chartTrend" class="w-full min-h-[300px]"></div>
                    </div>

                    {{-- Chart 2: Komposisi --}}
                    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100">
                        <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2 mb-6">
                            <i class="ph-fill ph-pie-chart text-purple-600"></i> Komposisi Hari Ini
                        </h3>
                        <div class="relative flex items-center justify-center mb-6">
                            <div id="chartDonut" class="w-full"></div>
                            {{-- Center Text Absolute --}}
                            <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none mt-2">
                                <span class="text-3xl font-black text-slate-800">{{ $hadirCount }}</span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase">Hadir</span>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                <p class="text-[10px] font-bold text-slate-400 uppercase">Hadir Tepat</p>
                                <p class="text-xl font-black text-emerald-600">{{ $hadirCount }}</p>
                            </div>
                            <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                <p class="text-[10px] font-bold text-slate-400 uppercase">Tidak Hadir</p>
                                <p class="text-xl font-black text-rose-600">{{ $alfaCount + $izinUzurCount }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- VIEW MODE TOGGLE --}}
            <div class="flex justify-center mb-6 no-print">
                <div class="bg-slate-200 p-1 rounded-xl inline-flex shadow-inner">
                    <button @click="viewMode = 'list'" 
                        :class="viewMode === 'list' ? 'bg-white text-blue-900 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                        class="px-6 py-2 rounded-lg text-sm font-bold transition-all flex items-center gap-2">
                        <i class="ph-bold ph-list-dashes"></i> Detail Siswa
                    </button>
                    <button @click="viewMode = 'rekap'" 
                        :class="viewMode === 'rekap' ? 'bg-white text-blue-900 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                        class="px-6 py-2 rounded-lg text-sm font-bold transition-all flex items-center gap-2">
                        <i class="ph-bold ph-chart-bar"></i> Rekap Per Kelas
                    </button>
                </div>
            </div>

            {{-- MAIN CONTENT AREA (LIST VIEW) --}}
            <div x-show="viewMode === 'list'" class="animate-enter bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden min-h-[500px]" style="animation-delay: 200ms">
                
                {{-- Tabs Header --}}
                <div class="flex flex-wrap md:flex-nowrap border-b border-slate-100 bg-slate-50/50 p-2 gap-2 sticky top-0 z-20 no-print">
                    <button @click="activeTab = 'hadir'" :class="activeTab === 'hadir' ? 'bg-white text-blue-900 shadow-sm ring-1 ring-slate-200' : 'text-slate-500 hover:bg-white/60'" class="flex-1 md:flex-none py-2.5 px-4 md:px-6 rounded-xl text-xs md:text-sm font-bold transition-all whitespace-nowrap">Sudah Absen</button>
                    <button @click="activeTab = 'belum'" :class="activeTab === 'belum' ? 'bg-white text-blue-900 shadow-sm ring-1 ring-slate-200' : 'text-slate-500 hover:bg-white/60'" class="flex-1 md:flex-none py-2.5 px-4 md:px-6 rounded-xl text-xs md:text-sm font-bold transition-all whitespace-nowrap">
                        Belum <span class="hidden sm:inline">Absen</span> <span class="ml-1 px-1.5 py-0.5 bg-rose-100 text-rose-600 rounded-md text-[10px]">{{ $belumAbsenCount }}</span>
                    </button>
                    <button @click="activeTab = 'uzur'" :class="activeTab === 'uzur' ? 'bg-white text-blue-900 shadow-sm ring-1 ring-slate-200' : 'text-slate-500 hover:bg-white/60'" class="flex-1 md:flex-none py-2.5 px-4 md:px-6 rounded-xl text-xs md:text-sm font-bold transition-all whitespace-nowrap">Ket. Lain</button>
                </div>

                <div class="p-0">
                    
                    {{-- TAB HADIR --}}
                    <div x-show="activeTab === 'hadir'" class="w-full">
                        <div class="p-4 bg-slate-50 border-b border-slate-100 no-print flex justify-end">
                            <form id="reset-data-form" method="POST" action="{{ route('reports.destroyReligious') }}">
                                @csrf @method('DELETE')
                                <input type="hidden" name="date" value="{{ $selectedDate_db->format('Y-m-d') }}">
                                <input type="hidden" name="activity" value="{{ $selectedActivity }}">
                                <button type="button" onclick="confirmResetData()" class="text-xs font-bold text-rose-600 hover:text-rose-800 bg-rose-50 hover:bg-rose-100 px-4 py-2.5 rounded-xl transition-colors flex items-center gap-2 border border-rose-100 active:scale-95">
                                    <i class="ph-bold ph-trash"></i> Reset Data
                                </button>
                            </form>
                        </div>

                        <div class="grid grid-cols-1 gap-0">
                            @forelse ($attendancesHadir as $attendance)
                                <div class="relative p-4 md:p-5 border-b border-slate-50 hover:bg-slate-50 transition-colors group flex items-center justify-between">
                                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-emerald-500 hidden group-hover:block"></div>
                                    <div class="flex items-center gap-3 md:gap-4 overflow-hidden w-full">
                                        <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-xs shrink-0">
                                            {{ $loop->iteration }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex justify-between items-center pr-2">
                                                <button type="button" onclick="openStudentHistory({{ $attendance->student->id }}, '{{ addslashes($attendance->student->name) }}')" 
                                                    class="font-bold text-slate-800 group-hover:text-blue-600 transition-colors truncate text-left hover:underline decoration-blue-300 underline-offset-2">
                                                    {{ $attendance->student->name }}
                                                </button>

                                                @if(isset($range) && $range['type'] != 'daily')
                                                    <span class="text-[10px] font-bold bg-slate-100 text-slate-500 px-2 py-1 rounded-md border border-slate-200">
                                                        {{ \Carbon\Carbon::parse($attendance->attendance_date)->format('d M') }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="text-xs font-medium text-slate-400 bg-slate-100 px-2 py-0.5 rounded">{{ $attendance->student->schoolClass->name }}</span>
                                                <span class="text-xs font-bold text-emerald-600 flex items-center gap-1"><i class="ph-bold ph-clock"></i> {{ $attendance->created_at->format('H:i') }}</span>
                                                <span class="text-[10px] font-bold bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded uppercase border border-emerald-100">{{ $attendance->status }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <button onclick="openEditModalReligious({{ $attendance->id }}, '{{ addslashes($attendance->student->name) }}', '{{ $attendance->status }}', `{{ addslashes($attendance->notes ?? '') }}`, '{{ $attendance->activity }}')" 
                                        class="p-2 ml-2 md:ml-4 text-slate-300 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all no-print shrink-0 active:scale-95">
                                        <i class="ph-bold ph-pencil-simple text-xl"></i>
                                    </button>
                                </div>
                            @empty
                                <div class="text-center py-20">
                                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300"><i class="ph-duotone ph-coffee text-4xl"></i></div>
                                    <p class="text-slate-400 font-bold">Belum ada data hadir.</p>
                                </div> 
                            @endforelse
                        </div>
                        <div class="p-4 border-t border-slate-100">
                            {{ $attendancesHadir->appends(request()->query() + ['activeTab' => 'hadir'])->links() }}
                        </div>
                    </div>

                    {{-- TAB BELUM ABSEN & UZUR --}}
                    {{-- (Isi tab lain tetap sama, tidak perlu diubah) --}}
                    <div x-show="activeTab === 'belum'" style="display: none;" class="w-full">
                         @if($belumAbsenList->count() > 0)
                            <div class="p-5 bg-rose-50 border-b border-rose-100 flex flex-col md:flex-row items-center justify-between gap-4 no-print">
                                <div class="flex items-center gap-3 text-rose-700">
                                    <div class="p-2 bg-white rounded-lg shadow-sm shrink-0"><i class="ph-fill ph-warning-octagon text-xl"></i></div>
                                    <div>
                                        <h4 class="font-bold text-sm">Aksi Massal Diperlukan</h4>
                                        <p class="text-xs opacity-80">{{ $belumAbsenList->count() }} siswa belum tercatat.</p>
                                    </div>
                                </div>
                                <form id="bulk-alpha-religious-form" action="{{ route('reports.bulkAlpha') }}" method="POST" class="w-full md:w-auto">
                                    @csrf
                                    <input type="hidden" name="date" value="{{ $selectedDate_db->format('Y-m-d') }}">
                                    <input type="hidden" name="type" value="Keagamaan">
                                    <input type="hidden" name="activity" value="{{ $selectedActivity }}">
                                    <button type="button" onclick="confirmBulkAlphaReligious('{{ $belumAbsenList->count() }}')" 
                                        class="w-full bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold px-5 py-3 rounded-xl shadow-lg shadow-rose-200 transition-all flex items-center justify-center gap-2 active:scale-95">
                                        <i class="ph-bold ph-check-circle"></i> Tandai Semua Alfa
                                    </button>
                                </form>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 gap-0">
                            @forelse ($belumAbsenList as $student)
                                <div class="relative p-4 md:p-5 border-b border-slate-50 hover:bg-slate-50 transition-colors group flex items-center justify-between">
                                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-rose-500 hidden group-hover:block"></div>
                                    <div class="flex items-center gap-4 overflow-hidden">
                                        <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center font-bold text-xs shrink-0">!</div>
                                        <div class="min-w-0">
                                            <button type="button" onclick="openStudentHistory({{ $student->id }}, '{{ addslashes($student->name) }}')" 
                                                class="font-bold text-slate-800 group-hover:text-blue-600 transition-colors truncate text-left hover:underline decoration-blue-300 underline-offset-2">
                                                {{ $student->name }}
                                            </button>
                                            <p class="text-xs text-slate-500">{{ $student->schoolClass->name ?? '-' }}</p>
                                        </div>
                                    </div>
                                    <button onclick="openManualModalForStudent({{ $student->id }}, '{{ addslashes($student->name) }}')" 
                                        class="inline-flex items-center gap-2 bg-white border border-slate-200 text-slate-600 px-4 py-2 rounded-xl text-xs font-bold hover:bg-blue-50 hover:text-blue-600 hover:border-blue-100 transition-all shadow-sm no-print shrink-0 active:scale-95">
                                        Input <span class="hidden md:inline">Manual</span>
                                    </button>
                                </div>
                            @empty
                                <div class="text-center py-20 text-emerald-600 font-bold">Semua Aman! Tidak ada siswa yang tertinggal.</div>
                            @endforelse
                        </div>
                    </div>

                    <div x-show="activeTab === 'uzur'" style="display: none;" class="w-full">
                         <div class="grid grid-cols-1 gap-0">
                            @forelse ($attendancesUzur as $attendance)
                                <div class="relative p-4 md:p-5 border-b border-slate-50 hover:bg-slate-50 transition-colors group flex items-center justify-between">
                                    <div class="absolute left-0 top-0 bottom-0 w-1 {{ $attendance->status == 'Alfa' ? 'bg-rose-500' : 'bg-blue-500' }} hidden group-hover:block"></div>
                                    <div class="flex items-center gap-4 overflow-hidden w-full">
                                        <div class="w-10 h-10 rounded-xl {{ $attendance->status == 'Alfa' ? 'bg-rose-100 text-rose-600' : 'bg-blue-100 text-blue-600' }} flex items-center justify-center font-bold text-xs shrink-0">
                                            {{ substr($attendance->status, 0, 1) }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex justify-between items-center pr-2">
                                                <button type="button" onclick="openStudentHistory({{ $attendance->student->id }}, '{{ addslashes($attendance->student->name) }}')" 
                                                    class="font-bold text-slate-800 group-hover:text-blue-600 transition-colors truncate text-left hover:underline decoration-blue-300 underline-offset-2">
                                                    {{ $attendance->student->name }}
                                                </button>
                                                
                                                @if(isset($range) && $range['type'] != 'daily')
                                                    <span class="text-[10px] font-bold bg-slate-100 text-slate-500 px-2 py-1 rounded-md border border-slate-200">
                                                        {{ \Carbon\Carbon::parse($attendance->attendance_date)->format('d M') }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $attendance->status == 'Alfa' ? 'bg-rose-50 text-rose-700' : 'bg-blue-50 text-blue-700' }} uppercase">{{ $attendance->status }}</span>
                                                @if($attendance->notes)
                                                    <span class="text-xs text-slate-400 italic max-w-[100px] md:max-w-none truncate">"{{ $attendance->notes }}"</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <button onclick="openEditModalReligious({{ $attendance->id }}, '{{ addslashes($attendance->student->name) }}', '{{ $attendance->status }}', `{{ addslashes($attendance->notes ?? '') }}`, '{{ $attendance->activity }}')" 
                                        class="p-2 ml-2 md:ml-4 text-slate-300 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all no-print shrink-0 active:scale-95">
                                        <i class="ph-bold ph-pencil-simple text-xl"></i>
                                    </button>
                                </div>
                            @empty
                                <div class="text-center py-20 text-slate-400 italic">Tidak ada data izin/uzur.</div> 
                            @endforelse
                        </div>
                        <div class="p-4 border-t border-slate-100">
                            {{ $attendancesUzur->appends(request()->query() + ['activeTab' => 'uzur'])->links() }}
                        </div>
                    </div>

                </div>
            </div>

            {{-- 
                ==========================================================
                MAIN CONTENT AREA (REKAP VIEW - SPLIT COLUMNS)
                ==========================================================
            --}}
            <div x-show="viewMode === 'rekap'" style="display: none;" class="animate-enter bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <div>
                        <h3 class="font-bold text-lg text-slate-800">Rekapitulasi Dhuha & Dhuhur</h3>
                        <p class="text-xs text-slate-500">Performa kehadiran ibadah per kelas.</p>
                    </div>
                    {{-- TOMBOL CETAK KHUSUS MODE REKAP --}}
                    <a href="{{ route('reports.printReligious', array_merge(request()->all(), ['view_mode' => 'rekap'])) }}" target="_blank" 
                       class="text-slate-400 hover:text-blue-900 transition-colors p-2 bg-white rounded-xl shadow-sm border border-slate-200">
                       <i class="ph-bold ph-printer text-xl"></i>
                    </a>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider font-bold border-b border-slate-200">
                                <th class="p-4 pl-6">Kelas</th>
                                <th class="p-4 text-center border-l border-slate-200">Total Siswa</th>
                                
                                {{-- HEADER DHUHA --}}
                                <th class="p-4 text-center bg-yellow-50/50 border-l border-yellow-100 text-yellow-700 w-[30%]">
                                    <div class="flex items-center justify-center gap-2">
                                        <i class="ph-fill ph-sun"></i> Dhuha
                                    </div>
                                </th>

                                {{-- HEADER DHUHUR --}}
                                <th class="p-4 text-center bg-blue-50/50 border-l border-blue-100 text-blue-700 w-[30%]">
                                    <div class="flex items-center justify-center gap-2">
                                        <i class="ph-fill ph-moon-stars"></i> Dhuhur
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="text-sm font-bold text-slate-700 divide-y divide-slate-100">
                            @foreach($classRecap as $rekap)
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <td class="p-4 pl-6">
                                    <span class="bg-slate-100 text-slate-600 px-3 py-1.5 rounded-lg group-hover:bg-blue-100 group-hover:text-blue-900 transition-colors border border-slate-200 group-hover:border-blue-200">{{ $rekap->className }}</span>
                                </td>
                                <td class="p-4 text-center text-slate-400 border-l border-slate-100">{{ $rekap->total_siswa }}</td>
                                
                                {{-- KOLOM DHUHA --}}
                                <td class="p-4 border-l border-slate-100 bg-yellow-50/10">
                                    <div class="flex items-center justify-between mb-1.5">
                                        <span class="text-xs text-slate-400">Hadir</span>
                                        <span class="text-emerald-600">{{ $rekap->dhuha['hadir'] }}</span>
                                    </div>
                                    <div class="w-full bg-slate-100 rounded-full h-2 mb-1 overflow-hidden">
                                        <div class="bg-yellow-400 h-2 rounded-full" style="width: {{ $rekap->dhuha['percent'] }}%"></div>
                                    </div>
                                    <div class="flex justify-between text-[10px] font-medium opacity-70">
                                        <span class="text-rose-500">Alfa: {{ $rekap->dhuha['alfa'] }}</span>
                                        <span>{{ $rekap->dhuha['percent'] }}%</span>
                                    </div>
                                </td>

                                {{-- KOLOM DHUHUR --}}
                                <td class="p-4 border-l border-slate-100 bg-blue-50/10">
                                     <div class="flex items-center justify-between mb-1.5">
                                        <span class="text-xs text-slate-400">Hadir</span>
                                        <span class="text-emerald-600">{{ $rekap->dhuhur['hadir'] }}</span>
                                    </div>
                                    <div class="w-full bg-slate-100 rounded-full h-2 mb-1 overflow-hidden">
                                        <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $rekap->dhuhur['percent'] }}%"></div>
                                    </div>
                                    <div class="flex justify-between text-[10px] font-medium opacity-70">
                                        <span class="text-rose-500">Alfa: {{ $rekap->dhuhur['alfa'] }}</span>
                                        <span>{{ $rekap->dhuhur['percent'] }}%</span>
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

    {{-- MODALS (Manual, Edit, History) --}}
    <div id="manualInputModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4 transition-opacity no-print">
        <div class="bg-white rounded-[2rem] w-full max-w-md shadow-2xl overflow-hidden border border-slate-100 animate-enter">
            <div class="bg-blue-900 px-6 py-4 flex justify-between items-center">
                <h3 class="font-bold text-white flex items-center gap-2"><i class="ph-bold ph-pencil-line"></i> Input Manual</h3>
                <button onclick="closeManualModal()" class="text-white/70 hover:text-white transition"><i class="ph-bold ph-x text-xl"></i></button>
            </div>
            <form action="{{ route('reports.storeManual') }}" method="POST" class="p-6 space-y-4" onsubmit="this.querySelector('button[type=submit]').disabled=true; this.querySelector('button[type=submit]').innerText='Menyimpan...';">
                @csrf
                <input type="hidden" name="attendance_type" value="Keagamaan">
                <input type="hidden" name="activity" value="{{ $selectedActivity }}">
                <input type="hidden" name="date" value="{{ $selectedDate_db->format('Y-m-d') }}">
                
                <div class="bg-blue-50 p-4 rounded-2xl border border-blue-100 text-center">
                    <span class="block text-xs font-bold text-blue-900 uppercase tracking-widest mb-1">Siswa</span>
                    <input type="text" id="manual-student-name-display" class="w-full bg-transparent border-none text-center text-xl font-black text-blue-900 focus:ring-0 p-0" readonly>
                    <input type="hidden" name="student_id" id="manual-student-id">
                </div>

                <div x-data="{ status: 'Hadir' }">
                    <label class="text-xs font-bold text-slate-400 uppercase mb-2 block ml-1">Status Kehadiran</label>
                    <select name="status" x-model="status" class="w-full border-slate-200 bg-slate-50 rounded-xl focus:ring-blue-900 font-bold text-slate-700 h-12">
                        <option value="Hadir">Hadir</option>
                        <option value="Sakit">Sakit</option>
                        <option value="Izin">Izin</option>
                        <option value="Uzur Syar'i">Uzur Syar'i</option>
                        <option value="Alfa">Alfa</option> 
                    </select>

                    <div x-show="status === 'Alfa'" class="mt-2 text-xs font-bold text-rose-600 bg-rose-50 p-3 rounded-xl border border-rose-100 flex items-start gap-2">
                        <i class="ph-fill ph-warning-circle text-lg mt-0.5"></i> 
                        <div>
                            <span>Hati-hati!</span>
                            <span class="block font-medium opacity-80 mt-0.5">Siswa akan otomatis mendapatkan Poin Pelanggaran.</span>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-400 uppercase mb-2 block ml-1">Catatan</label>
                    <input type="text" name="notes" class="w-full border-slate-200 bg-slate-50 rounded-xl focus:ring-blue-900 h-12" placeholder="Contoh: Sakit">
                </div>
                <button type="submit" class="w-full bg-blue-900 hover:bg-slate-900 text-white font-bold h-12 rounded-xl transition-colors shadow-lg shadow-blue-200 mt-2 active:scale-95">Simpan Data</button>
            </form>
        </div>
    </div>

    <div id="editReligiousModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4 transition-opacity no-print">
        <div class="bg-white rounded-[2rem] w-full max-w-md shadow-2xl overflow-hidden border border-slate-100 animate-enter">
            <div class="bg-slate-800 px-6 py-4 flex justify-between items-center">
                <h3 class="font-bold text-white flex items-center gap-2"><i class="ph-bold ph-pencil-simple"></i> Edit Data</h3>
                <button onclick="closeEditModalReligious()" class="text-white/70 hover:text-white transition"><i class="ph-bold ph-x text-xl"></i></button>
            </div>
            <form id="editReligiousForm" method="POST" class="p-6 space-y-4" onsubmit="this.querySelector('button[type=submit]').disabled=true; this.querySelector('button[type=submit]').innerText='Menyimpan...';">
                @csrf @method('PUT')
                <div class="text-center mb-4">
                    <p class="text-xs font-bold text-slate-400 uppercase mb-1">Mengedit Siswa</p>
                    <p id="modal-religious-student-name" class="text-xl font-black text-slate-800 truncate px-4"></p>
                </div>
                <input type="hidden" name="activity" id="modal-religious-activity">
                
                <div x-data="{ status: '' }" x-init="$watch('status', value => {})">
                    <label class="text-xs font-bold text-slate-400 uppercase mb-2 block ml-1">Status</label>
                    <select name="status" id="modal-religious-status" onchange="checkEditReligiousStatus(this.value)" class="w-full border-slate-200 bg-slate-50 rounded-xl font-bold text-slate-700 h-12">
                        <option value="Hadir">Hadir</option>
                        <option value="Sakit">Sakit</option>
                        <option value="Izin">Izin</option>
                        <option value="Uzur Syar'i">Uzur Syar'i</option>
                        <option value="Alfa">Alfa</option>
                    </select>

                     <div id="edit-religious-alert" class="hidden mt-2 text-xs font-bold text-rose-600 bg-rose-50 p-3 rounded-xl border border-rose-100 flex items-start gap-2">
                        <i class="ph-fill ph-warning-circle text-lg mt-0.5"></i> 
                        <div>
                            <span>Hati-hati!</span>
                            <span class="block font-medium opacity-80 mt-0.5">Mengubah menjadi Alfa akan menambah Poin Pelanggaran.</span>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-400 uppercase mb-2 block ml-1">Catatan</label>
                    <textarea name="notes" id="modal-religious-notes" class="w-full border-slate-200 bg-slate-50 rounded-xl" rows="2"></textarea>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeEditModalReligious()" class="flex-1 h-12 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200">Batal</button>
                    <button type="submit" class="flex-1 h-12 bg-blue-900 text-white font-bold rounded-xl hover:bg-slate-900 shadow-md active:scale-95">Update</button>
                </div>
            </form>
        </div>
    </div>

    <div id="historyModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4 transition-opacity no-print">
        <div class="bg-white rounded-[2rem] w-full max-w-lg shadow-2xl overflow-hidden border border-slate-100 animate-enter flex flex-col max-h-[80vh]">
            <div class="bg-white border-b border-slate-100 px-6 py-4 flex justify-between items-center shrink-0">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase">Riwayat Keagamaan</p>
                    <h3 id="history-student-name" class="font-bold text-xl text-slate-800">Nama Siswa</h3>
                </div>
                <button onclick="document.getElementById('historyModal').classList.add('hidden')" class="w-8 h-8 rounded-full bg-slate-50 hover:bg-slate-100 flex items-center justify-center text-slate-400 transition"><i class="ph-bold ph-x"></i></button>
            </div>
            
            <div id="history-content" class="p-0 overflow-y-auto grow">
                <div class="flex flex-col items-center justify-center h-40">
                    <div class="w-8 h-8 border-4 border-blue-600 border-t-transparent rounded-full animate-spin mb-2"></div>
                    <span class="text-xs font-bold text-slate-400">Memuat riwayat...</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if(isset($chartData))
                const trendOptions = {
                    series: @json($chartData['series']),
                    chart: { type: 'bar', height: 300, toolbar: { show: false }, fontFamily: 'Plus Jakarta Sans, sans-serif' },
                    colors: ['#10b981', '#cbd5e1'],
                    plotOptions: { bar: { horizontal: false, columnWidth: '50%', borderRadius: 6, borderRadiusApplication: 'end' } },
                    dataLabels: { enabled: false },
                    stroke: { show: true, width: 2, colors: ['transparent'] },
                    xaxis: { categories: @json($chartData['labels']), axisBorder: { show: false }, axisTicks: { show: false }, labels: { style: { colors: '#94a3b8', fontSize: '11px', fontWeight: 600 } } },
                    yaxis: { labels: { style: { colors: '#94a3b8', fontSize: '11px', fontWeight: 600 } } },
                    fill: { opacity: 1 },
                    tooltip: { y: { formatter: function (val) { return val + " Siswa" } } },
                    grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
                    legend: { show: false }
                };
                new ApexCharts(document.querySelector("#chartTrend"), trendOptions).render();

                const donutOptions = {
                    series: [{{ $chartData['composition']['hadir'] }}, {{ $chartData['composition']['uzur'] }}, {{ $chartData['composition']['alfa'] + $chartData['composition']['belum'] }}],
                    labels: ['Hadir', 'Sakit/Izin', 'Tidak Hadir'],
                    chart: { type: 'donut', height: 250, fontFamily: 'Plus Jakarta Sans, sans-serif' },
                    colors: ['#10b981', '#3b82f6', '#f43f5e'],
                    plotOptions: { pie: { donut: { size: '75%', labels: { show: false } } } },
                    dataLabels: { enabled: false },
                    legend: { show: false },
                    tooltip: { enabled: true, y: { formatter: function(val) { return val + " Siswa" } } }
                };
                new ApexCharts(document.querySelector("#chartDonut"), donutOptions).render();
            @endif
        });

        function openStudentHistory(studentId, studentName) {
            document.getElementById('history-student-name').innerText = studentName;
            document.getElementById('historyModal').classList.remove('hidden');
            const contentDiv = document.getElementById('history-content');
            contentDiv.innerHTML = `<div class="flex flex-col items-center justify-center h-40"><div class="w-8 h-8 border-4 border-blue-600 border-t-transparent rounded-full animate-spin mb-2"></div><span class="text-xs font-bold text-slate-400">Memuat riwayat...</span></div>`;
            fetch(`{{ url('reports/religious/history') }}?student_id=${studentId}&activity={{ $selectedActivity }}`).then(r=>r.text()).then(h=>{contentDiv.innerHTML=h;}).catch(e=>{console.error(e);contentDiv.innerHTML=`<div class="p-6 text-center text-rose-500 font-bold text-sm">Gagal memuat data.</div>`;});
        }
        function openManualModalForStudent(id, name) {
            document.getElementById('manual-student-id').value = id;
            document.getElementById('manual-student-name-display').value = name;
            document.getElementById('manualInputModal').classList.remove('hidden');
        }
        function closeManualModal() { document.getElementById('manualInputModal').classList.add('hidden'); }
        function confirmResetData() {
            Swal.fire({ title: 'Reset Data Hari Ini?', text: "Semua data kehadiran {{ $selectedActivity }} akan dihapus!", icon: 'warning', showCancelButton: true, confirmButtonColor: '#e11d48', cancelButtonColor: '#64748b', confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal', customClass: { popup: 'rounded-[2rem]' } }).then((result) => { if (result.isConfirmed) document.getElementById('reset-data-form').submit(); })
        }
        function confirmBulkAlphaReligious(count) {
            Swal.fire({ title: 'Tandai ' + count + ' Siswa Alfa?', html: "Siswa akan ditandai <b>Alfa</b> untuk Shalat {{ $selectedActivity }}.<br><div class='mt-3 text-rose-600 font-bold bg-rose-50 p-2 rounded-lg border border-rose-100 text-sm'>Poin Pelanggaran akan ditambahkan otomatis!</div>", icon: 'warning', showCancelButton: true, confirmButtonColor: '#e11d48', cancelButtonColor: '#64748b', confirmButtonText: 'Ya, Proses!', cancelButtonText: 'Batal', customClass: { popup: 'rounded-[2rem]' } }).then((result) => { if (result.isConfirmed) document.getElementById('bulk-alpha-religious-form').submit(); })
        }
        const religiousModal = document.getElementById('editReligiousModal');
        const religiousForm = document.getElementById('editReligiousForm');
        const religiousStudentNameDisplay = document.getElementById('modal-religious-student-name');
        const religiousActivitySelect = document.getElementById('modal-religious-activity');
        const religiousStatusSelect = document.getElementById('modal-religious-status');
        const religiousNotesInput = document.getElementById('modal-religious-notes');
        function checkEditReligiousStatus(val) { const alertBox = document.getElementById('edit-religious-alert'); if(val === 'Alfa') { alertBox.classList.remove('hidden'); } else { alertBox.classList.add('hidden'); } }
        function openEditModalReligious(id, name, status, notes, activity) {
            const submitBtn = religiousForm.querySelector('button[type=submit]');
            submitBtn.disabled = false; submitBtn.innerText = 'Update';
            const updateRoute = '{{ route('reports.update', ['attendance' => '__ID__']) }}'.replace('__ID__', id);
            religiousForm.action = updateRoute;
            religiousStudentNameDisplay.textContent = name; religiousActivitySelect.value = activity;
            religiousStatusSelect.value = status; religiousNotesInput.value = notes;
            checkEditReligiousStatus(status); religiousModal.classList.remove('hidden');
        }
        function closeEditModalReligious() { religiousModal.classList.add('hidden'); }
    </script>
</x-app-layout>