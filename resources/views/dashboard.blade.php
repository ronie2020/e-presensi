<x-app-layout>
    {{-- CUSTOM STYLES --}}
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .count-up { font-variant-numeric: tabular-nums; }
        
        /* Animasi Wiggle */
        @keyframes wiggle { 0%, 100% { transform: rotate(0deg); } 25% { transform: rotate(-10deg); } 75% { transform: rotate(10deg); } }
        .group:hover .animate-wiggle { animation: wiggle 0.5s ease-in-out; }

        /* Utility */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* PRINT STYLES */
        @media print {
            body { background-color: white !important; color: black !important; }
            .no-print, nav, header, .filter-group, button, .quick-actions { display: none !important; }
            .card-print { break-inside: avoid; border: 1px solid #ddd; box-shadow: none !important; background: white !important; color: black !important; }
            .text-white { color: black !important; }
            .bg-gradient-to-r { background: none !important; border-bottom: 2px solid #000; color: black !important; }
            .print-header { display: block !important; margin-bottom: 20px; text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; }
            canvas { max-width: 100% !important; max-height: 300px !important; }
            .shadow-2xl, .shadow-xl, .shadow-lg, .shadow-md, .shadow-sm { box-shadow: none !important; }
        }
        .print-header { display: none; }
    </style>

    {{-- WRAPPER UTAMA --}}
    <div x-data="{ 
            period: new URLSearchParams(window.location.search).get('period') || 'today',
            date: new URLSearchParams(window.location.search).get('date') || new Date().toISOString().split('T')[0],
            loading: false,
            loadingTarget: '',
            
            updateFilter(newPeriod) {
                this.loading = true;
                this.loadingTarget = newPeriod;
                this.period = newPeriod;
                setTimeout(() => {
                    window.location.href = '?period=' + this.period + '&date=' + this.date;
                }, 300); 
            },
            changeDate(days) {
                this.loading = true;
                this.loadingTarget = 'date';
                let d = new Date(this.date);
                d.setDate(d.getDate() + days);
                this.date = d.toISOString().split('T')[0];
                window.location.href = '?period=' + this.period + '&date=' + this.date;
            },
            printDashboard() { window.print(); },
            navigate(url) {
                this.loading = true;
                this.loadingTarget = 'page';
                window.location.href = url;
            }
        }" class="relative space-y-6 md:space-y-8 min-h-screen pb-10 font-sans text-slate-800">
        
        {{-- HEADER CETAK --}}
        <div class="print-header">
            <h1 class="text-2xl font-bold uppercase tracking-wide">Laporan Monitoring Harian</h1>
            <p class="text-sm">SMK DIGITAL INDONESIA</p>
            <p class="text-xs mt-2">Dicetak pada: {{ now()->format('d F Y H:i') }} oleh {{ Auth::user()->name }}</p>
        </div>

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
                <span class="text-xs font-bold text-slate-700 tracking-wider uppercase animate-pulse">Memproses Data...</span>
            </div>
        </div>

        {{-- HERO SECTION --}}
        <div class="animate-enter relative rounded-[2.5rem] bg-gradient-to-r from-blue-900 via-slate-800 to-slate-900 p-6 md:p-10 mb-6 text-white shadow-2xl shadow-blue-900/20 overflow-hidden group border border-white/10 card-print">
            
            <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-blue-500 rounded-full mix-blend-overlay filter blur-[120px] opacity-20 group-hover:opacity-30 transition-opacity duration-1000 no-print"></div>
            <div class="absolute bottom-0 left-0 w-[300px] h-[300px] bg-indigo-500 rounded-full mix-blend-overlay filter blur-[100px] opacity-20 no-print"></div>
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 pointer-events-none no-print"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/10 text-blue-200 text-xs font-bold uppercase tracking-wider mb-4 backdrop-blur-sm shadow-sm no-print">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span>
                        </span>
                        System Online
                    </div>
                    <h1 class="text-2xl md:text-5xl font-extrabold text-white tracking-tight mb-3">
                        Halo, <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-white">{{ Auth::user()->name ?? 'Administrator' }}</span> 
                    </h1>
                    <p class="text-blue-100/80 text-sm md:text-base max-w-xl leading-relaxed">
                        Berikut adalah ringkasan aktivitas akademik dan kehadiran siswa untuk periode 
                        <span class="text-white font-bold bg-white/10 px-2 py-0.5 rounded shadow-sm border border-white/10" x-text="period === 'today' ? 'Hari Ini' : (period === 'week' ? 'Minggu Ini' : 'Bulan Ini')"></span>.
                    </p>
                </div>
                
                {{-- FILTER CONTROLS --}}
                <div class="flex flex-col gap-3 w-full md:w-auto md:min-w-[320px] filter-group no-print">
                    <div class="flex items-center justify-between bg-white/10 backdrop-blur-md rounded-xl p-1 border border-white/10 mb-1 relative">
                        <div x-show="loadingTarget === 'date'" class="absolute inset-0 bg-slate-900/50 rounded-lg flex items-center justify-center z-10">
                            <i class="ph-bold ph-spinner animate-spin text-white"></i>
                        </div>

                        <button @click="changeDate(-1)" :disabled="loading" class="p-2 hover:bg-white/20 rounded-lg text-white transition disabled:opacity-50" title="Sebelumnya">
                            <i class="ph-bold ph-caret-left"></i>
                        </button>
                        <div class="relative group/date flex-1 mx-2">
                             <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="ph-bold ph-calendar text-blue-300 group-hover/date:text-white transition-colors"></i>
                            </div>
                            <input type="date" x-model="date" @change="loading = true; loadingTarget = 'date'; updateFilter(period)" 
                                class="w-full bg-transparent border-none text-white text-xs font-bold text-center focus:ring-0 cursor-pointer placeholder-blue-200">
                        </div>
                        <button @click="changeDate(1)" :disabled="loading" class="p-2 hover:bg-white/20 rounded-lg text-white transition disabled:opacity-50" title="Berikutnya">
                            <i class="ph-bold ph-caret-right"></i>
                        </button>
                    </div>

                    <div class="bg-slate-900/50 backdrop-blur-md p-1.5 rounded-xl flex border border-white/10 shadow-lg overflow-x-auto">
                        <button @click="updateFilter('today')" :disabled="loading"
                            :class="period === 'today' ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'text-blue-200 hover:text-white hover:bg-white/5'" 
                            class="flex-1 py-2.5 px-3 md:px-4 text-[10px] md:text-xs font-bold rounded-lg transition-all duration-300 flex justify-center items-center gap-1 md:gap-2 whitespace-nowrap">
                            <i x-show="loading && loadingTarget === 'today'" class="ph-bold ph-spinner animate-spin"></i>
                            <span x-text="(loading && loadingTarget === 'today') ? '' : 'Harian'"></span>
                        </button>

                        <button @click="updateFilter('week')" :disabled="loading"
                            :class="period === 'week' ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'text-blue-200 hover:text-white hover:bg-white/5'" 
                            class="flex-1 py-2.5 px-3 md:px-4 text-[10px] md:text-xs font-bold rounded-lg transition-all duration-300 flex justify-center items-center gap-1 md:gap-2 whitespace-nowrap">
                            <i x-show="loading && loadingTarget === 'week'" class="ph-bold ph-spinner animate-spin"></i>
                            <span x-text="(loading && loadingTarget === 'week') ? '' : 'Mingguan'"></span>
                        </button>

                        <button @click="updateFilter('month')" :disabled="loading"
                            :class="period === 'month' ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'text-blue-200 hover:text-white hover:bg-white/5'" 
                            class="flex-1 py-2.5 px-3 md:px-4 text-[10px] md:text-xs font-bold rounded-lg transition-all duration-300 flex justify-center items-center gap-1 md:gap-2 whitespace-nowrap">
                            <i x-show="loading && loadingTarget === 'month'" class="ph-bold ph-spinner animate-spin"></i>
                            <span x-text="(loading && loadingTarget === 'month') ? '' : 'Bulanan'"></span>
                        </button>
                        
                        {{-- Tombol Cetak / Export PDF --}}
                        <a href="{{ route('reports.printDaily', ['date' => request('date')]) }}" target="_blank" class="ml-2 bg-white/10 text-white p-2.5 rounded-lg hover:bg-white/20 hover:scale-105 active:scale-95 transition-all shadow-sm border border-white/10 shrink-0 flex items-center gap-2" title="Cetak Laporan Harian">
                            <i class="ph-bold ph-printer text-lg"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- [WIDGET FIX] MONITORING SISWA KELUAR --}}
        @php
            $studentsOut = \App\Models\StudentPermit::with('student.schoolClass')
                            ->where('status', 'OUT')
                            ->orderBy('time_out', 'desc')
                            ->get();
            $countOut = $studentsOut->count();
        @endphp

        <!-- Widget ini sekarang akan SELALU TAMPIL -->
        <div class="animate-enter mb-8 no-print" style="animation-delay: 50ms">
            @if($countOut > 0)
                {{-- STATUS WARNING: ADA SISWA KELUAR --}}
                <div class="bg-orange-50 rounded-[2rem] border border-orange-100 p-6 relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-4 opacity-10">
                        <i class="ph-duotone ph-door-open text-8xl text-orange-500"></i>
                    </div>
                    
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6 relative z-10">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <span class="relative flex h-3 w-3">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-3 w-3 bg-orange-500"></span>
                                </span>
                                <h3 class="text-lg font-bold text-orange-800">Peringatan: Siswa Sedang Di Luar</h3>
                            </div>
                            <p class="text-sm text-orange-600/80">Terdapat <span class="font-bold">{{ $countOut }} siswa</span> yang belum kembali ke kelas saat ini.</p>
                        </div>
                        
                        <a href="{{ route('permit.index') }}" class="px-5 py-2.5 bg-white text-orange-600 text-sm font-bold rounded-xl shadow-sm border border-orange-100 hover:bg-orange-100 transition-colors flex items-center gap-2">
                            <i class="ph-bold ph-eye"></i> Lihat Detail Monitoring
                        </a>
                    </div>

                    <div class="mt-6 flex gap-3 overflow-x-auto pb-2 custom-scrollbar">
                        @foreach($studentsOut as $permit)
                            @php
                                $duration = \Carbon\Carbon::parse($permit->time_out)->diffInMinutes(now());
                                $isOverdue = $duration > 15;
                            @endphp
                            <div class="flex-shrink-0 w-64 bg-white p-3 rounded-xl border {{ $isOverdue ? 'border-rose-200 bg-rose-50/50' : 'border-orange-100' }} shadow-sm flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full {{ $isOverdue ? 'bg-rose-100 text-rose-600' : 'bg-orange-100 text-orange-600' }} flex items-center justify-center font-bold text-sm">
                                    {{ substr($permit->student->name, 0, 1) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold text-slate-700 truncate">{{ $permit->student->name }}</p>
                                    <p class="text-[10px] text-slate-500 truncate">{{ $permit->reason_category }} • <span class="{{ $isOverdue ? 'text-rose-600 font-bold animate-pulse' : 'text-slate-500' }}">{{ $duration }} m</span></p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                {{-- STATUS AMAN: TIDAK ADA SISWA KELUAR --}}
                <div class="bg-emerald-50 rounded-[2rem] border border-emerald-100 p-6 relative overflow-hidden flex items-center justify-between">
                    <div class="flex items-center gap-4 relative z-10">
                        <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 text-2xl shadow-sm">
                            <i class="ph-fill ph-shield-check"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-emerald-800">Status Monitoring: Aman</h3>
                            <p class="text-sm text-emerald-600/80">Semua siswa berada di dalam kelas. Tidak ada izin keluar aktif.</p>
                        </div>
                    </div>
                    <a href="{{ route('permit.index') }}" class="px-5 py-2.5 bg-white text-emerald-600 text-sm font-bold rounded-xl shadow-sm border border-emerald-100 hover:bg-emerald-100 transition-colors hidden md:flex items-center gap-2">
                        <i class="ph-bold ph-list-magnifying-glass"></i> Cek Log
                    </a>
                </div>
            @endif
        </div>

        {{-- QUICK ACTIONS --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8 no-print animate-enter quick-actions" style="animation-delay: 100ms">
            <a href="{{ route('students.index') }}" @click.prevent="navigate('{{ route('students.index') }}')" class="group bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-3 hover:shadow-md transition-all hover:border-blue-200 cursor-pointer">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm">
                    <i class="ph-bold ph-student text-xl"></i>
                </div>
                <div class="text-sm font-bold text-slate-700 group-hover:text-blue-700">Data Siswa</div>
            </a>
            
            <a href="{{ route('cbt.index') }}" @click.prevent="navigate('{{ route('cbt.index') }}')" class="group bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-3 hover:shadow-md transition-all hover:border-purple-200 cursor-pointer">
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm">
                    <i class="ph-bold ph-monitor-play text-xl"></i>
                </div>
                <div class="text-sm font-bold text-slate-700 group-hover:text-purple-700">Ujian CBT</div>
            </a>

            <a href="{{ route('lms.assignments.index') }}" @click.prevent="navigate('{{ route('lms.assignments.index') }}')" class="group bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-3 hover:shadow-md transition-all hover:border-rose-200 cursor-pointer">
                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm">
                    <i class="ph-bold ph-pencil-simple text-xl"></i>
                </div>
                <div class="text-sm font-bold text-slate-700 group-hover:text-rose-700">Tugas & PR</div>
            </a>

            <a href="{{ route('lms.grades.index') }}" @click.prevent="navigate('{{ route('lms.grades.index') }}')" class="group bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-3 hover:shadow-md transition-all hover:border-emerald-200 cursor-pointer">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm">
                    <i class="ph-bold ph-chart-bar text-xl"></i>
                </div>
                <div class="text-sm font-bold text-slate-700 group-hover:text-emerald-700">Rekap Nilai</div>
            </a>
        </div>

        {{-- KPI CARDS --}}
        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4 md:gap-5">
            @foreach($cards as $index => $card)
            @php
                $titleLower = strtolower($card['title']);
                $rawIcon = $card['icon'] ?? ''; 
                
                // --- LOGIKA WARNA ---
                if (str_contains($titleLower, 'alpha') || str_contains($titleLower, 'alpa') || str_contains($titleLower, 'absen') || str_contains($titleLower, 'tidak hadir')) { 
                    $iconClass = 'ph-x-circle'; 
                    $colorKey = 'rose'; 
                } 
                elseif (str_contains($titleLower, 'telat') || str_contains($titleLower, 'lambat')) { 
                    $iconClass = 'ph-clock'; 
                    $colorKey = 'amber'; 
                } 
                elseif (str_contains($titleLower, 'izin') || str_contains($titleLower, 'sakit')) { 
                    $iconClass = 'ph-envelope-open'; 
                    $colorKey = 'purple'; 
                } 
                elseif (str_contains($titleLower, 'hadir') && !str_contains($titleLower, 'belum') && !str_contains($titleLower, 'tidak')) { 
                    $iconClass = 'ph-check-circle'; 
                    $colorKey = 'emerald'; 
                } 
                elseif (str_contains($titleLower, 'belum')) { 
                    $iconClass = 'ph-minus-circle'; 
                    $colorKey = 'slate'; 
                } 
                elseif (str_contains($titleLower, 'pulang')) { 
                    $iconClass = 'ph-person-simple-run'; 
                    $colorKey = 'yellow'; 
                } 
                elseif (str_contains($titleLower, 'total') || str_contains($titleLower, 'siswa')) { 
                    $iconClass = 'ph-student'; 
                    $colorKey = 'blue'; 
                } 
                else { 
                    $iconClass = (!empty($rawIcon) && !str_starts_with($rawIcon, 'M') && $rawIcon !== 'ph-hash') ? $rawIcon : 'ph-chart-bar'; 
                    $colorKey = 'blue'; 
                }

                $theme = match($colorKey) {
                    'emerald' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'hover_bg' => 'group-hover:bg-emerald-600', 'hover_border' => 'hover:border-emerald-200'],
                    'amber' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-600', 'hover_bg' => 'group-hover:bg-amber-600', 'hover_border' => 'hover:border-amber-200'],
                    'rose' => ['bg' => 'bg-rose-50', 'text' => 'text-rose-600', 'hover_bg' => 'group-hover:bg-rose-600', 'hover_border' => 'hover:border-rose-200'],
                    'purple' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-600', 'hover_bg' => 'group-hover:bg-purple-600', 'hover_border' => 'hover:border-purple-200'],
                    'yellow' => ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-600', 'hover_bg' => 'group-hover:bg-yellow-600', 'hover_border' => 'hover:border-yellow-200'],
                    'slate' => ['bg' => 'bg-slate-100', 'text' => 'text-slate-600', 'hover_bg' => 'group-hover:bg-slate-600', 'hover_border' => 'hover:border-slate-300'],
                    default => ['bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'hover_bg' => 'group-hover:bg-blue-600', 'hover_border' => 'hover:border-blue-200'],
                };
            @endphp

            <a href="{{ url('attendance') }}?status={{ $card['filter_status'] ?? '' }}&period={{ request('period', 'today') }}" 
               class="animate-enter group bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.05)] hover:shadow-xl hover:shadow-slate-200 {{ $theme['hover_border'] }} transition-all duration-300 hover:-translate-y-1 relative overflow-hidden flex flex-col justify-between h-full card-print"
               style="animation-delay: {{ ($index + 1) * 100 }}ms">
                <i class="ph-duotone {{ $iconClass }} absolute -right-4 -bottom-4 text-[5rem] opacity-[0.03] text-slate-900 group-hover:scale-110 group-hover:-rotate-12 transition-transform duration-500 no-print"></i>
                <div class="flex justify-between items-start mb-4 relative z-10">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-sm transition-all duration-300 {{ $theme['bg'] }} {{ $theme['text'] }} {{ $theme['hover_bg'] }} group-hover:text-white group-hover:scale-110">
                        <i class="ph-duotone {{ $iconClass }} text-2xl animate-wiggle"></i>
                    </div>
                    
                    {{-- Badge Persentase (Existing) --}}
                    @if(isset($card['percentage']))
                    <span class="text-[10px] font-bold px-2 py-1 rounded-lg border {{ $card['percentage'] > 0 ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-rose-50 text-rose-600 border-rose-100' }}">
                        {{ $card['percentage'] > 0 ? '+' : '' }}{{ $card['percentage'] }}%
                    </span>
                    @endif
                    
                    {{-- Badge Tren (New) --}}
                    @if(isset($card['trend']) && $card['trend'] !== null)
                        <div class="text-[10px] font-bold px-2 py-1 rounded-lg border flex items-center gap-1 {{ $card['trend'] >= 0 ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-rose-50 text-rose-600 border-rose-100' }}">
                            <i class="{{ $card['trend'] >= 0 ? 'ph-bold ph-trend-up' : 'ph-bold ph-trend-down' }}"></i>
                            <span>{{ abs($card['trend']) }}</span>
                        </div>
                    @endif
                </div>
                <div class="relative z-10 mt-auto">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 truncate {{ str_replace('text-', 'group-hover:text-', $theme['text']) }} transition-colors">{{ $card['title'] }}</p>
                    <h3 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight count-up" data-target="{{ $card['value'] }}">0</h3>
                    @if(isset($card['trend']) && $card['trend'] !== null)
                        <p class="text-[9px] text-slate-400 font-bold mt-1">vs Kemarin</p>
                    @endif
                </div>
            </a>
            @endforeach
        </div>

        {{-- GRAFIK & KOMPOSISI --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            {{-- Grafik Batang --}}
            <div class="animate-enter xl:col-span-2 bg-white p-5 md:p-8 rounded-[2rem] shadow-sm border border-slate-100 card-print" style="animation-delay: 600ms">
                <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                            <i class="ph-fill ph-chart-bar text-blue-600"></i> Analisis Tren Kehadiran
                        </h3>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wide mt-1">
                            Statistik <span x-text="period === 'month' ? 'Bulanan' : 'Mingguan'"></span>
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-2 no-print">
                        <div class="px-3 py-1 rounded-lg bg-emerald-50 border border-emerald-100 flex items-center gap-2 text-[10px] font-bold text-emerald-700 uppercase"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Hadir</div>
                        <div class="px-3 py-1 rounded-lg bg-amber-50 border border-amber-100 flex items-center gap-2 text-[10px] font-bold text-amber-700 uppercase"><span class="w-2 h-2 rounded-full bg-amber-500"></span> Telat</div>
                        <div class="px-3 py-1 rounded-lg bg-rose-50 border border-rose-100 flex items-center gap-2 text-[10px] font-bold text-rose-700 uppercase"><span class="w-2 h-2 rounded-full bg-rose-500"></span> Absen</div>
                    </div>
                </div>
                <div class="relative h-64 md:h-72 w-full">
                    <canvas id="weeklyChart"></canvas>
                </div>
            </div>

            {{-- Donut Chart --}}
            <div class="animate-enter bg-white p-5 md:p-8 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col h-full card-print" style="animation-delay: 700ms">
                <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                    <i class="ph-fill ph-chart-pie-slice text-purple-500"></i> Komposisi Hari Ini
                </h3>
                <div class="relative h-56 w-full flex items-center justify-center mb-6">
                    <canvas id="dailyDonutChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-3xl md:text-4xl font-black text-slate-800 count-up" data-target="{{ $totalStudents ?? 0 }}">0</span>
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Total Siswa</span>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3 mt-auto">
                    <div class="bg-emerald-50/50 p-3 rounded-xl border border-emerald-100"><span class="block text-[10px] font-bold text-emerald-600 uppercase mb-1">Hadir Tepat</span><span class="text-lg font-black text-slate-800">{{ $presentOnTimeCount ?? 0 }}</span></div>
                    <div class="bg-amber-50/50 p-3 rounded-xl border border-amber-100"><span class="block text-[10px] font-bold text-amber-600 uppercase mb-1">Terlambat</span><span class="text-lg font-black text-slate-800">{{ $lateCount ?? 0 }}</span></div>
                    <div class="bg-rose-50/50 p-3 rounded-xl border border-rose-100"><span class="block text-[10px] font-bold text-rose-600 uppercase mb-1">Alfa/Belum</span><span class="text-lg font-black text-slate-800">{{ $absentCount ?? 0 }}</span></div>
                     <div class="bg-blue-50/50 p-3 rounded-xl border border-blue-100"><span class="block text-[10px] font-bold text-blue-600 uppercase mb-1">Izin/Sakit</span><span class="text-lg font-black text-slate-800">{{ $sickPermitCount ?? 0 }}</span></div>
                </div>
            </div>
        </div>

        {{-- TABLES SECTION --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 page-break-inside-avoid">
            {{-- Activity Log --}}
            <div class="animate-enter bg-white p-6 md:p-8 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col h-full card-print" style="animation-delay: 800ms" x-data="{ tab: 'activity' }">
                <div class="flex items-center justify-between mb-6 no-print">
                    <div class="flex gap-6 border-b border-slate-100 w-full">
                        <button @click="tab = 'activity'" :class="tab === 'activity' ? 'text-blue-900 border-blue-600' : 'text-slate-400 border-transparent hover:text-slate-600'" class="text-sm font-bold pb-3 border-b-2 transition-all px-1">Aktivitas Terbaru</button>
                        <button @click="tab = 'late_recap'" :class="tab === 'late_recap' ? 'text-amber-600 border-amber-500' : 'text-slate-400 border-transparent hover:text-slate-600'" class="text-sm font-bold pb-3 border-b-2 transition-all px-1">Top Terlambat</button>
                    </div>
                </div>

                <div x-show="tab === 'activity'" class="flex-1 overflow-y-auto max-h-[400px] custom-scrollbar pr-2">
                    @if(isset($recentActivities) && count($recentActivities) > 0)
                        <div class="relative pl-6 border-l-2 border-slate-100 space-y-6 py-2 ml-2">
                            @foreach($recentActivities as $log)
                                @php
                                    $type = $log->type;
                                    $statusText = $log->status;
                                    $subText = 'Absensi Sekolah';
                                    $theme = ['bg_icon' => 'bg-emerald-50', 'border_icon' => 'border-emerald-100', 'text_icon' => 'text-emerald-600', 'dot' => 'bg-emerald-400', 'bg_badge' => 'bg-emerald-100', 'text_badge' => 'text-emerald-700'];
                                    $icon = 'ph-check-circle';

                                    if ($type === 'Keagamaan') {
                                        $icon = 'ph-moon-stars'; $statusText = $log->activity; $subText = 'Ibadah';
                                        $theme = ['bg_icon' => 'bg-purple-50', 'border_icon' => 'border-purple-100', 'text_icon' => 'text-purple-600', 'dot' => 'bg-purple-400', 'bg_badge' => 'bg-purple-100', 'text_badge' => 'text-purple-700'];
                                    } elseif ($type === 'Ekstrakurikuler') {
                                        $icon = 'ph-trophy'; $statusText = $log->activity; $subText = 'Ekstrakurikuler';
                                        $theme = ['bg_icon' => 'bg-orange-50', 'border_icon' => 'border-orange-100', 'text_icon' => 'text-orange-600', 'dot' => 'bg-orange-400', 'bg_badge' => 'bg-orange-100', 'text_badge' => 'text-orange-700'];
                                    } else {
                                        if ($log->status == 'Terlambat') {
                                            $icon = 'ph-clock-warning';
                                            $theme = ['bg_icon' => 'bg-amber-50', 'border_icon' => 'border-amber-100', 'text_icon' => 'text-amber-600', 'dot' => 'bg-amber-400', 'bg_badge' => 'bg-amber-100', 'text_badge' => 'text-amber-700'];
                                        } elseif ($type == 'Pulang') {
                                            $icon = 'ph-person-simple-walk'; $statusText = 'Pulang'; $subText = 'Selesai KBM';
                                            $theme = ['bg_icon' => 'bg-blue-50', 'border_icon' => 'border-blue-100', 'text_icon' => 'text-blue-600', 'dot' => 'bg-blue-400', 'bg_badge' => 'bg-blue-100', 'text_badge' => 'text-blue-700'];
                                        } else {
                                            $subText = $log->student->schoolClass->name ?? '-';
                                        }
                                    }
                                @endphp
                            <div class="relative group">
                                <div class="absolute -left-[31px] top-1.5 h-4 w-4 rounded-full border-[3px] border-white ring-1 ring-slate-200 {{ $theme['dot'] }}"></div>
                                <div class="flex items-start justify-between gap-3 p-3 rounded-2xl hover:bg-slate-50 transition-colors -mt-2 -ml-2">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl {{ $theme['bg_icon'] }} border {{ $theme['border_icon'] }} flex items-center justify-center {{ $theme['text_icon'] }} font-bold text-xs shrink-0">
                                            <i class="ph-bold {{ $icon }} text-lg"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-slate-800 line-clamp-1 group-hover:text-blue-700 transition-colors">{{ $log->student->name ?? 'Siswa' }}</p>
                                            <p class="text-[10px] text-slate-500 font-bold px-2 py-0.5 rounded-md inline-block mt-1 border border-slate-100 bg-white">{{ $subText }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right shrink-0">
                                        <p class="text-xs font-bold font-mono text-slate-600 mb-1">{{ \Carbon\Carbon::parse($log->created_at)->format('H:i') }}</p>
                                        <span class="text-[10px] font-bold px-2 py-1 rounded-lg {{ $theme['bg_badge'] }} {{ $theme['text_badge'] }}">{{ $statusText }}</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center h-40 text-center text-slate-400">
                            <i class="ph-duotone ph-coffee text-4xl mb-3 opacity-30"></i>
                            <p class="text-xs font-bold">Belum ada aktivitas hari ini.</p>
                        </div>
                    @endif
                </div> 

                <div x-show="tab === 'late_recap'" style="display: none;" class="flex-1 overflow-y-auto max-h-[400px] custom-scrollbar">
                    @if(isset($topLateStudents) && count($topLateStudents) > 0)
                        <div class="space-y-3">
                            @foreach($topLateStudents as $index => $student)
                            <div class="flex items-center justify-between p-3 rounded-2xl border border-slate-50 hover:bg-red-50/30 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 font-bold text-xs flex items-center justify-center border border-slate-200">#{{ $index + 1 }}</div>
                                    <div>
                                        <div class="text-xs font-bold text-slate-800 line-clamp-1">{{ $student->student->name ?? 'Siswa' }}</div>
                                        <div class="text-[10px] text-slate-400">{{ $student->student->schoolClass->name ?? '-' }}</div>
                                    </div>
                                </div>
                                <span class="text-xs font-black text-red-600 bg-red-50 px-3 py-1 rounded-xl border border-red-100">{{ $student->total_late }}x</span>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-10 text-slate-400 text-xs font-bold">Tidak ada siswa terlambat signifikan.</div>
                    @endif
                </div>
            </div>

            {{-- Class Rank Table (WITH TABS FOR BEST/WORST) --}}
            <div class="animate-enter bg-white p-6 md:p-8 rounded-[2rem] shadow-sm border border-slate-100 h-full card-print" style="animation-delay: 900ms" x-data="{ rankTab: 'best' }">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        <i class="ph-fill ph-trophy text-yellow-400 text-xl drop-shadow-sm"></i> Peringkat Kelas
                    </h3>
                    
                    {{-- Tab Switcher --}}
                    <div class="bg-slate-100 p-1 rounded-lg flex no-print">
                        <button @click="rankTab = 'best'" :class="rankTab === 'best' ? 'bg-white text-emerald-700 shadow-sm' : 'text-slate-500 hover:text-slate-700'" class="px-2 py-1 rounded-md text-[10px] font-bold transition-all">Rajin</button>
                        <button @click="rankTab = 'worst'" :class="rankTab === 'worst' ? 'bg-white text-rose-700 shadow-sm' : 'text-slate-500 hover:text-slate-700'" class="px-2 py-1 rounded-md text-[10px] font-bold transition-all">Perlu Atensi</button>
                    </div>
                </div>

                {{-- TAB: KELAS TERAJIN --}}
                <div x-show="rankTab === 'best'">
                    @if(isset($classRanks) && count($classRanks) > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <tbody class="divide-y divide-slate-50">
                                @foreach($classRanks as $index => $rank)
                                <tr class="group hover:bg-slate-50 transition-colors">
                                    <td class="py-4 pl-1 w-10">
                                        @if($index == 0) <i class="ph-fill ph-medal text-yellow-400 text-2xl drop-shadow-sm"></i>
                                        @elseif($index == 1) <i class="ph-fill ph-medal text-slate-300 text-xl"></i>
                                        @elseif($index == 2) <i class="ph-fill ph-medal text-amber-600 text-xl"></i>
                                        @else <span class="font-bold text-slate-300 ml-1.5">#{{ $index + 1 }}</span> @endif
                                    </td>
                                    <td class="py-4">
                                        <div class="font-bold text-slate-700 mb-1">{{ $rank->class_name }}</div>
                                        <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden max-w-[150px]">
                                            @php $percent = min(100, ($rank->present_count / 40) * 100); @endphp
                                            <div class="h-1.5 rounded-full {{ $index == 0 ? 'bg-yellow-400' : 'bg-emerald-500' }}" style="width: {{ $percent }}%"></div>
                                        </div>
                                    </td>
                                    <td class="py-4 text-right pr-2">
                                        <div class="font-black text-slate-800">{{ number_format($percent, 0) }}%</div>
                                        <div class="text-[10px] text-slate-400 font-bold whitespace-nowrap">{{ $rank->present_count }} Hadir</div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                     <div class="flex flex-col items-center justify-center h-40 text-center text-slate-400"><p class="text-xs font-bold">Belum ada data peringkat.</p></div>
                    @endif
                </div>

                {{-- TAB: KELAS PERLU PERHATIAN (WORST) --}}
                <div x-show="rankTab === 'worst'" style="display: none;">
                    @if(isset($lowestClassRanks) && count($lowestClassRanks) > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <tbody class="divide-y divide-slate-50">
                                @foreach($lowestClassRanks as $index => $rank)
                                <tr class="group hover:bg-rose-50/50 transition-colors">
                                    <td class="py-4 pl-1 w-10 text-center font-bold text-slate-400">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="py-4">
                                        <div class="font-bold text-slate-700 mb-1">{{ $rank->class_name }}</div>
                                        <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden max-w-[150px]">
                                            @php $percent = min(100, ($rank->absent_count / 40) * 100); @endphp
                                            <div class="h-1.5 rounded-full bg-rose-500" style="width: {{ $percent }}%"></div>
                                        </div>
                                    </td>
                                    <td class="py-4 text-right pr-2">
                                        <div class="font-black text-rose-600">{{ $rank->absent_count }}</div>
                                        <div class="text-[10px] text-slate-400 font-bold whitespace-nowrap">Tidak Masuk</div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                     <div class="flex flex-col items-center justify-center h-40 text-center text-emerald-500">
                        <i class="ph-duotone ph-check-circle text-4xl mb-2 opacity-50"></i>
                        <p class="text-xs font-bold">Semua kelas hadir lengkap!</p>
                     </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    {{-- SCRIPT CHART.JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- SCRIPT INITIALIZATION --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animasi Angka
            const counters = document.querySelectorAll('.count-up');
            counters.forEach(counter => {
                const target = +counter.getAttribute('data-target');
                let count = 0; const inc = Math.max(1, target / 50);
                const updateCount = () => {
                    count += inc;
                    if (count < target) { counter.innerText = Math.ceil(count).toLocaleString('id-ID'); requestAnimationFrame(updateCount); } 
                    else { counter.innerText = target.toLocaleString('id-ID'); }
                };
                updateCount();
            });

            Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
            Chart.defaults.color = '#94a3b8';

            // Data
            const rawPresent = @json($weeklyPresentData ?? []);
            const rawLate = @json($weeklyLateData ?? []);
            const rawAbsent = @json($weeklyAbsentData ?? []);
            const labels = @json($chartLabels ?? []);
            
            // Bar Chart
            const ctxBar = document.getElementById('weeklyChart');
            if(ctxBar) {
                const hasData = rawPresent.some(x => x > 0) || rawLate.some(x => x > 0) || rawAbsent.some(x => x > 0);
                if (hasData) {
                    new Chart(ctxBar.getContext('2d'), {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [
                                { label: 'Hadir', data: rawPresent, backgroundColor: '#10b981', borderRadius: 4, barThickness: 12 },
                                { label: 'Telat', data: rawLate, backgroundColor: '#f59e0b', borderRadius: 4, barThickness: 12 },
                                { label: 'Absen', data: rawAbsent, backgroundColor: '#f43f5e', borderRadius: 4, barThickness: 12 }
                            ]
                        },
                        options: {
                            responsive: true, maintainAspectRatio: false,
                            plugins: { 
                                legend: { display: false },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            let label = context.dataset.label || '';
                                            if (label) label += ': ';
                                            if (context.parsed.y !== null) label += context.parsed.y + ' Siswa';
                                            return label;
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: { grid: { color: '#f1f5f9', borderDash: [4, 4] }, border: { display: false } },
                                x: { grid: { display: false } }
                            }
                        }
                    });
                } else {
                    ctxBar.parentElement.innerHTML = `<div class="flex flex-col items-center justify-center h-full text-slate-300"><i class="ph-duotone ph-chart-bar text-5xl mb-2"></i><p class="text-xs font-bold">Belum ada data grafik minggu ini.</p></div>`;
                }
            }

            // Donut Chart
            const ctxDonut = document.getElementById('dailyDonutChart');
            if(ctxDonut) {
                new Chart(ctxDonut.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Hadir', 'Telat', 'Absen', 'Izin'],
                        datasets: [{ 
                            data: [{{ $presentOnTimeCount ?? 0 }}, {{ $lateCount ?? 0 }}, {{ $absentCount ?? 0 }}, {{ $sickPermitCount ?? 0 }}], 
                            backgroundColor: ['#10b981', '#f59e0b', '#f43f5e', '#3b82f6'], 
                            borderWidth: 0 
                        }]
                    },
                    options: { 
                        responsive: true, maintainAspectRatio: false, cutout: '85%', 
                        plugins: { legend: { display: false } } 
                    }
                });
            }
        });
    </script>
</x-app-layout>