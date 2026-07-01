<x-app-layout>
    {{-- CUSTOM STYLES --}}
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .count-up { font-variant-numeric: tabular-nums; }
        
        /* Animasi Wiggle */
        @keyframes wiggle { 0%, 100% { transform: rotate(0deg); } 25% { transform: rotate(-10deg); } 75% { transform: rotate(10deg); } }
        .group:hover .animate-wiggle { animation: wiggle 0.5s ease-in-out; }

        /* ==========================================================
           MICROSOFT FLUENT ELEVATION SHADOWS & DESIGN
           ========================================================== */
        .fluent-card {
            box-shadow: 0 1.6px 3.6px 0 rgba(0, 0, 0, 0.05), 0 0.3px 0.9px 0 rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        .fluent-card:hover {
            box-shadow: 0 6.4px 14.4px 0 rgba(0, 0, 0, 0.08), 0 1.2px 3.6px 0 rgba(0, 0, 0, 0.08);
            transform: translateY(-2px);
        }
        .fluent-modal {
            box-shadow: 0 25.6px 57.6px 0 rgba(0, 0, 0, 0.15), 0 4.8px 14.4px 0 rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        /* Utility */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* PRINT STYLES */
        @media print {
            body { background-color: white !important; color: black !important; }
            .no-print, nav, header, .filter-group, button, .quick-actions { display: none !important; }
            .card-print { break-inside: avoid; border: 1px solid #ddd; box-shadow: none !important; background: white !important; color: black !important; transform: none !important;}
            .text-white { color: black !important; }
            .bg-gradient-to-br, .bg-gradient-to-r { background: none !important; border-bottom: 2px solid #000; color: black !important; }
            .print-header { display: block !important; margin-bottom: 20px; text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; }
            canvas { max-width: 100% !important; max-height: 300px !important; }
            .fluent-card, .shadow-2xl, .shadow-xl, .shadow-lg, .shadow-md, .shadow-sm { box-shadow: none !important; border: 1px solid #ccc !important; }
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
        }" class="relative space-y-6 md:space-y-8 min-h-screen pb-10 font-sans text-elevate-text">
        
        {{-- HEADER CETAK --}}
        <div class="print-header">
            <h1 class="text-2xl font-bold uppercase tracking-wide">Laporan Monitoring Harian</h1>
            <p class="text-sm">SMP NEGERI 3 LAKBOK</p>
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
            
            <div class="bg-white p-6 rounded-2xl fluent-modal flex flex-col items-center transform transition-all scale-100">
                <div class="relative w-12 h-12 mb-4">
                    <div class="absolute inset-0 rounded-full border-4 border-slate-100"></div>
                    <div class="absolute inset-0 rounded-full border-4 border-elevate-dark border-t-transparent animate-spin"></div>
                </div>
                <span class="text-xs font-bold text-elevate-dark tracking-wider uppercase animate-pulse">Memproses Data...</span>
            </div>
        </div>

         {{-- HERO SECTION (TEMA MICROSOFT ELEVATE) --}}
        <div class="animate-enter relative rounded-[2.5rem] bg-elevate-gradient-main p-6 md:p-10 mb-6 text-elevate-dark shadow-xl shadow-elevate-accent/10 overflow-hidden group border border-white/60 card-print">
            
            {{-- Background Pattern --}}
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 pointer-events-none no-print mix-blend-overlay"></div>
            
            {{-- Blobs Bercahaya --}}
            <div class="absolute -top-10 -left-10 w-[300px] sm:w-[400px] h-[300px] sm:h-[400px] bg-elevate-primary/10 rounded-full blur-[100px] group-hover:opacity-70 transition-opacity duration-1000 no-print animate-blob"></div>
            <div class="absolute bottom-0 right-0 w-[200px] sm:w-[300px] h-[200px] sm:h-[300px] bg-elevate-peach/20 rounded-full blur-[120px] no-print animate-blob" style="animation-delay: 2s;"></div>
            <div class="absolute top-10 right-32 w-24 h-24 bg-white/40 rounded-2xl rotate-45 pointer-events-none shadow-sm"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/50 border border-white/60 text-elevate-dark text-[10px] font-bold uppercase tracking-wider mb-4 backdrop-blur-sm shadow-sm no-print">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-500 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        System Online                        
                    </div>
                    
                    <h1 class="text-3xl md:text-5xl font-black text-elevate-dark tracking-tight mb-3">
                        Halo, <span>{{ Auth::user()->name ?? 'Administrator' }}</span> 
                    </h1>
                    <p class="text-elevate-dark/80 text-sm md:text-base max-w-xl leading-relaxed font-medium">
                        Berikut adalah ringkasan aktivitas akademik dan kehadiran siswa untuk periode 
                        <span class="text-elevate-dark font-black bg-white/70 px-2 py-0.5 rounded shadow-sm border border-white/50" x-text="period === 'today' ? 'Hari Ini' : (period === 'week' ? 'Minggu Ini' : 'Bulan Ini')"></span>.
                    </p>
                </div>
                
                {{-- FILTER CONTROLS --}}
                <div class="flex flex-col gap-3 w-full md:w-auto md:min-w-[320px] filter-group no-print">
                    <div class="flex items-center justify-between bg-white/50 backdrop-blur-md rounded-xl p-1 border border-white/60 mb-1 relative shadow-sm">
                        <div x-show="loadingTarget === 'date'" class="absolute inset-0 bg-white/50 rounded-xl flex items-center justify-center z-10">
                            <i class="ph-bold ph-spinner animate-spin text-elevate-dark"></i>
                        </div>

                        <button @click="changeDate(-1)" :disabled="loading" class="p-2 hover:bg-white/60 rounded-lg text-elevate-dark transition disabled:opacity-50" title="Sebelumnya">
                            <i class="ph-bold ph-caret-left"></i>
                        </button>
                        <div class="relative group/date flex-1 mx-2">
                             <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="ph-bold ph-calendar text-elevate-dark/70 group-hover/date:text-elevate-dark transition-colors"></i>
                            </div>
                            <input type="date" x-model="date" @change="loading = true; loadingTarget = 'date'; updateFilter(period)" 
                                class="w-full bg-transparent border-none text-elevate-dark text-xs font-bold text-center focus:ring-0 cursor-pointer placeholder-elevate-dark/70">
                        </div>
                        <button @click="changeDate(1)" :disabled="loading" class="p-2 hover:bg-white/60 rounded-lg text-elevate-dark transition disabled:opacity-50" title="Berikutnya">
                            <i class="ph-bold ph-caret-right"></i>
                        </button>
                    </div>

                    <div class="bg-white/50 backdrop-blur-md p-1.5 rounded-xl flex border border-white/60 shadow-sm overflow-x-auto">
                        <button @click="updateFilter('today')" :disabled="loading"
                            :class="period === 'today' ? 'bg-white text-elevate-primary shadow-sm' : 'text-elevate-dark hover:bg-white/60'" 
                            class="flex-1 py-2.5 px-3 md:px-4 text-[10px] md:text-xs font-bold rounded-lg transition-all duration-300 flex justify-center items-center gap-1 md:gap-2 whitespace-nowrap">
                            <i x-show="loading && loadingTarget === 'today'" class="ph-bold ph-spinner animate-spin"></i>
                            <span x-text="(loading && loadingTarget === 'today') ? '' : 'Harian'"></span>
                        </button>

                        <button @click="updateFilter('week')" :disabled="loading"
                            :class="period === 'week' ? 'bg-white text-elevate-primary shadow-sm' : 'text-elevate-dark hover:bg-white/60'" 
                            class="flex-1 py-2.5 px-3 md:px-4 text-[10px] md:text-xs font-bold rounded-lg transition-all duration-300 flex justify-center items-center gap-1 md:gap-2 whitespace-nowrap">
                            <i x-show="loading && loadingTarget === 'week'" class="ph-bold ph-spinner animate-spin"></i>
                            <span x-text="(loading && loadingTarget === 'week') ? '' : 'Mingguan'"></span>
                        </button>

                        <button @click="updateFilter('month')" :disabled="loading"
                            :class="period === 'month' ? 'bg-white text-elevate-primary shadow-sm' : 'text-elevate-dark hover:bg-white/60'" 
                            class="flex-1 py-2.5 px-3 md:px-4 text-[10px] md:text-xs font-bold rounded-lg transition-all duration-300 flex justify-center items-center gap-1 md:gap-2 whitespace-nowrap">
                            <i x-show="loading && loadingTarget === 'month'" class="ph-bold ph-spinner animate-spin"></i>
                            <span x-text="(loading && loadingTarget === 'month') ? '' : 'Bulanan'"></span>
                        </button>
                        
                        {{-- Tombol Cetak / Export PDF --}}
                        <a href="{{ route('reports.printDaily', ['date' => request('date')]) }}" target="_blank" class="ml-2 bg-elevate-dark text-white p-2.5 rounded-lg hover:bg-elevate-primary hover:scale-105 active:scale-95 transition-all shadow-sm border border-transparent shrink-0 flex items-center gap-2" title="Cetak Laporan Harian">
                            <i class="ph-bold ph-printer text-lg"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- WIDGET MONITORING SISWA KELUAR --}}
        <div class="animate-enter mb-8 no-print" style="animation-delay: 50ms">
            @if($countOut > 0)
                {{-- STATUS WARNING --}}
                <div class="bg-amber-50 rounded-[1.5rem] border border-amber-200 p-6 relative overflow-hidden fluent-card">
                    <div class="absolute top-0 right-0 p-4 opacity-10">
                        <i class="ph-duotone ph-door-open text-8xl text-amber-600"></i>
                    </div>
                    
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6 relative z-10">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <span class="relative flex h-3 w-3">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-500 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
                                </span>
                                <h3 class="text-lg font-bold text-amber-600">Peringatan: Siswa Sedang Di Luar</h3>
                            </div>
                            <p class="text-sm text-amber-700/80">Terdapat <span class="font-bold">{{ $countOut }} siswa</span> yang belum kembali ke kelas saat ini.</p>
                        </div>
                        
                        <a href="{{ route('permit.index') }}" class="px-5 py-2.5 bg-white text-amber-600 text-sm font-bold rounded-xl shadow-sm border border-amber-200 hover:bg-amber-100 transition-colors flex items-center gap-2">
                            <i class="ph-bold ph-eye"></i> Lihat Detail
                        </a>
                    </div>

                    <div class="mt-6 flex gap-3 overflow-x-auto pb-2 custom-scrollbar">
                        @foreach($studentsOut as $permit)
                            @php
                                $duration = \Carbon\Carbon::parse($permit->time_out)->diffInMinutes(now());
                                $isOverdue = $duration > 15;
                            @endphp
                            <div class="flex-shrink-0 w-64 bg-white p-3 rounded-xl border {{ $isOverdue ? 'border-rose-200 bg-rose-50' : 'border-amber-200' }} shadow-sm flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg {{ $isOverdue ? 'bg-rose-100 text-rose-600' : 'bg-amber-100 text-amber-600' }} flex items-center justify-center font-bold text-sm">
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
                {{-- STATUS AMAN --}}
                <div class="bg-emerald-50 rounded-[1.5rem] border border-emerald-200 p-6 relative overflow-hidden flex items-center justify-between fluent-card">
                    <div class="flex items-center gap-4 relative z-10">
                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-emerald-500 text-2xl shadow-sm border border-emerald-100">
                            <i class="ph-fill ph-shield-check"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-emerald-600">Status Monitoring: Aman</h3>
                            <p class="text-sm text-emerald-700/80">Semua siswa berada di dalam kelas. Tidak ada izin keluar aktif.</p>
                        </div>
                    </div>
                    <a href="{{ route('permit.index') }}" class="px-5 py-2.5 bg-white text-emerald-600 text-sm font-bold rounded-xl shadow-sm border border-emerald-200 hover:bg-emerald-100 transition-colors hidden md:flex items-center gap-2">
                        <i class="ph-bold ph-list-magnifying-glass"></i> Cek Log
                    </a>
                </div>
            @endif
        </div>

        {{-- QUICK ACTIONS --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4 mb-8 no-print animate-enter quick-actions" style="animation-delay: 100ms">
            <a href="{{ route('students.index') }}" @click.prevent="navigate('{{ route('students.index') }}')" class="group bg-white p-4 rounded-2xl fluent-card flex flex-col items-center justify-center gap-2 hover:border-elevate-primary cursor-pointer text-center">
                <div class="w-12 h-12 rounded-xl bg-elevate-primary/10 text-elevate-primary flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm border border-elevate-primary/20">
                    <i class="ph-bold ph-student text-2xl"></i>
                </div>
                <div class="text-[11px] font-bold text-slate-700 group-hover:text-elevate-primary">Data Siswa</div>
            </a>
            
             <a href="{{ route('teacher.habits.index') }}" @click.prevent="navigate('{{ route('teacher.habits.index') }}')" class="group bg-white p-4 rounded-2xl fluent-card flex flex-col items-center justify-center gap-2 hover:border-emerald-500 cursor-pointer text-center">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm border border-emerald-100">
                    <i class="ph-bold ph-calendar-check text-2xl"></i>
                </div>
                <div class="text-[11px] font-bold text-slate-700 group-hover:text-emerald-600">7 Kebiasaan</div>
            </a>
            
            <a href="{{ route('cbt.index') }}" @click.prevent="navigate('{{ route('cbt.index') }}')" class="group bg-white p-4 rounded-2xl fluent-card flex flex-col items-center justify-center gap-2 hover:border-elevate-dark cursor-pointer text-center">
                <div class="w-12 h-12 rounded-xl bg-elevate-dark/5 text-elevate-dark flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm border border-elevate-dark/10">
                    <i class="ph-bold ph-monitor-play text-2xl"></i>
                </div>
                <div class="text-[11px] font-bold text-slate-700 group-hover:text-elevate-dark">Ujian CBT</div>
            </a>

            <a href="{{ route('lms.assignments.index') }}" @click.prevent="navigate('{{ route('lms.assignments.index') }}')" class="group bg-white p-4 rounded-2xl fluent-card flex flex-col items-center justify-center gap-2 hover:border-rose-500 cursor-pointer text-center">
                <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm border border-rose-100">
                    <i class="ph-bold ph-pencil-simple text-2xl"></i>
                </div>
                <div class="text-[11px] font-bold text-slate-700 group-hover:text-rose-600">Tugas & PR</div>
            </a>

            <a href="{{ route('lms.grades.index') }}" @click.prevent="navigate('{{ route('lms.grades.index') }}')" class="group bg-white p-4 rounded-2xl fluent-card flex flex-col items-center justify-center gap-2 hover:border-emerald-500 cursor-pointer text-center">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm border border-emerald-100">
                    <i class="ph-bold ph-chart-bar text-2xl"></i>
                </div>
                <div class="text-[11px] font-bold text-slate-700 group-hover:text-emerald-600">Rekap Nilai</div>
            </a>

            <a href="{{ route('reports.class') }}" @click.prevent="navigate('{{ route('reports.class') }}')" class="group bg-white p-4 rounded-2xl fluent-card flex flex-col items-center justify-center gap-2 hover:border-amber-500 cursor-pointer text-center">
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm border border-amber-100">
                    <i class="ph-bold ph-files text-2xl"></i>
                </div>
                <div class="text-[11px] font-bold text-slate-700 group-hover:text-amber-600">Laporan Kelas</div>
            </a>

            <a href="{{ route('admin.graduation.index') }}" @click.prevent="navigate('{{ route('admin.graduation.index') }}')" class="group bg-white p-4 rounded-2xl fluent-card flex flex-col items-center justify-center gap-2 hover:border-rose-500 cursor-pointer text-center">
                <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm border border-rose-100">
                    <i class="ph-bold ph-envelope-open text-2xl"></i>
                </div>
                <div class="text-[11px] font-bold text-slate-700 group-hover:text-rose-600">Kelulusan</div>
            </a>

            <a href="{{ route('admin.ppdb.index') }}" @click.prevent="navigate('{{ route('admin.ppdb.index') }}')" class="group bg-white p-4 rounded-2xl fluent-card flex flex-col items-center justify-center gap-2 hover:border-amber-500 cursor-pointer text-center">
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm border border-amber-100">
                    <i class="ph-bold ph-users text-2xl"></i>
                </div>
                <div class="text-[11px] font-bold text-slate-700 group-hover:text-amber-600">SPMB/PPDB</div>
            </a>

            <a href="{{ route('letters.spt.index') }}" @click.prevent="navigate('{{ route('letters.spt.index') }}')" class="group bg-white p-4 rounded-2xl fluent-card flex flex-col items-center justify-center gap-2 hover:border-elevate-primary cursor-pointer text-center">
                <div class="w-12 h-12 rounded-xl bg-elevate-primary/10 text-elevate-primary flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm border border-elevate-primary/20">
                    <i class="ph-bold ph-car-profile text-2xl"></i>
                </div>
                <div class="text-[11px] font-bold text-slate-700 group-hover:text-elevate-primary">SPPD</div>
            </a>

            <a href="{{ route('library.dashboard') }}" @click.prevent="navigate('{{ route('library.dashboard') }}')" class="group bg-white p-4 rounded-2xl fluent-card flex flex-col items-center justify-center gap-2 hover:border-elevate-dark cursor-pointer text-center">
                <div class="w-12 h-12 rounded-xl bg-elevate-dark/5 text-elevate-dark flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm border border-elevate-dark/10">
                    <i class="ph-bold ph-book-open-text text-2xl"></i>
                </div>
                <div class="text-[11px] font-bold text-slate-700 group-hover:text-elevate-dark">Perpustakaan</div>
            </a>
        </div>

        {{-- KPI CARDS --}}
        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4 md:gap-5">
            @foreach($cards as $index => $card)
            @php
                $titleLower = strtolower($card['title']);
                $rawIcon = $card['icon'] ?? ''; 
                
                if (str_contains($titleLower, 'alpha') || str_contains($titleLower, 'alpa') || str_contains($titleLower, 'absen') || str_contains($titleLower, 'tidak hadir')) { 
                    $iconClass = 'ph-x-circle'; 
                    $colorKey = 'danger'; 
                } 
                elseif (str_contains($titleLower, 'telat') || str_contains($titleLower, 'lambat')) { 
                    $iconClass = 'ph-clock'; 
                    $colorKey = 'warning'; 
                } 
                elseif (str_contains($titleLower, 'izin') || str_contains($titleLower, 'sakit')) { 
                    $iconClass = 'ph-envelope-open'; 
                    $colorKey = 'info'; 
                } 
                elseif (str_contains($titleLower, 'hadir') && !str_contains($titleLower, 'belum') && !str_contains($titleLower, 'tidak')) { 
                    $iconClass = 'ph-check-circle'; 
                    $colorKey = 'success'; 
                } 
                elseif (str_contains($titleLower, 'belum')) { 
                    $iconClass = 'ph-minus-circle'; 
                    $colorKey = 'neutral'; 
                } 
                elseif (str_contains($titleLower, 'pulang')) { 
                    $iconClass = 'ph-person-simple-run'; 
                    $colorKey = 'warning'; 
                } 
                elseif (str_contains($titleLower, 'total') || str_contains($titleLower, 'siswa')) { 
                    $iconClass = 'ph-student'; 
                    $colorKey = 'primary'; 
                } 
                else { 
                    $iconClass = (!empty($rawIcon) && !str_starts_with($rawIcon, 'M') && $rawIcon !== 'ph-hash') ? $rawIcon : 'ph-chart-bar'; 
                    $colorKey = 'primary'; 
                }

                $theme = match($colorKey) {
                    'success' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'hover_bg' => 'group-hover:bg-emerald-500', 'hover_border' => 'hover:border-emerald-200', 'border' => 'border-emerald-100'],
                    'warning' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-600', 'hover_bg' => 'group-hover:bg-amber-500', 'hover_border' => 'hover:border-amber-200', 'border' => 'border-amber-100'],
                    'danger' => ['bg' => 'bg-rose-50', 'text' => 'text-rose-600', 'hover_bg' => 'group-hover:bg-rose-500', 'hover_border' => 'hover:border-rose-200', 'border' => 'border-rose-100'],
                    'info' => ['bg' => 'bg-elevate-dark/5', 'text' => 'text-elevate-dark', 'hover_bg' => 'group-hover:bg-elevate-dark', 'hover_border' => 'hover:border-elevate-dark/20', 'border' => 'border-elevate-dark/10'],
                    'neutral' => ['bg' => 'bg-slate-50', 'text' => 'text-slate-500', 'hover_bg' => 'group-hover:bg-slate-500', 'hover_border' => 'hover:border-slate-300', 'border' => 'border-slate-200'],
                    default => ['bg' => 'bg-elevate-primary/10', 'text' => 'text-elevate-primary', 'hover_bg' => 'group-hover:bg-elevate-primary', 'hover_border' => 'hover:border-elevate-primary/30', 'border' => 'border-elevate-primary/20'],
                };
            @endphp

            <div onclick="showCardInfo('{{ $card['title'] }}', '{{ $card['value'] }}', '{{ $colorKey }}')" 
               class="cursor-pointer animate-enter group bg-white rounded-2xl p-5 fluent-card {{ $theme['hover_border'] }} relative overflow-hidden flex flex-col justify-between h-full card-print"
               style="animation-delay: {{ ($index + 1) * 100 }}ms">
                
                <i class="ph-duotone {{ $iconClass }} absolute -right-4 -bottom-4 text-[5rem] opacity-[0.03] text-slate-900 group-hover:scale-110 group-hover:-rotate-12 transition-transform duration-500 no-print"></i>
                
                <div class="flex justify-between items-start mb-4 relative z-10">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-sm border {{ $theme['border'] }} transition-all duration-300 {{ $theme['bg'] }} {{ $theme['text'] }} {{ $theme['hover_bg'] }} group-hover:text-white group-hover:scale-110">
                        <i class="ph-duotone {{ $iconClass }} text-2xl animate-wiggle"></i>
                    </div>
                    
                    @if(isset($card['percentage']))
                    <span class="text-[10px] font-bold px-2 py-1 rounded-lg border {{ $card['percentage'] > 0 ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-rose-50 text-rose-600 border-rose-200' }}">
                        {{ $card['percentage'] > 0 ? '+' : '' }}{{ $card['percentage'] }}%
                    </span>
                    @endif
                    
                    @if(isset($card['trend']) && $card['trend'] !== null)
                        <div class="text-[10px] font-bold px-2 py-1 rounded-lg border flex items-center gap-1 {{ $card['trend'] >= 0 ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-rose-50 text-rose-600 border-rose-200' }}">
                            <i class="{{ $card['trend'] >= 0 ? 'ph-bold ph-trend-up' : 'ph-bold ph-trend-down' }}"></i>
                            <span>{{ abs($card['trend']) }}</span>
                        </div>
                    @endif
                </div>
                <div class="relative z-10 mt-auto">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 truncate {{ str_replace('text-', 'group-hover:text-', $theme['text']) }} transition-colors">{{ $card['title'] }}</p>
                    <h3 class="text-2xl md:text-3xl font-black text-elevate-dark tracking-tight count-up" data-target="{{ $card['value'] }}">0</h3>
                    @if(isset($card['trend']) && $card['trend'] !== null)
                        <p class="text-[9px] text-slate-400 font-bold mt-1">vs Kemarin</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        {{-- GRAFIK & KOMPOSISI --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            {{-- Grafik Batang --}}
            <div class="animate-enter xl:col-span-2 bg-white p-5 md:p-8 rounded-[2rem] fluent-card card-print" style="animation-delay: 600ms">
                <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                    <div>
                        <h3 class="text-lg font-black text-elevate-dark flex items-center gap-2">
                            <i class="ph-fill ph-chart-bar text-elevate-primary"></i> Analisis Tren Kehadiran
                        </h3>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wide mt-1">
                            Statistik <span x-text="period === 'month' ? 'Bulanan' : 'Mingguan'"></span>
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-2 no-print">
                        <div class="px-3 py-1 rounded-xl bg-elevate-primary/10 border border-elevate-primary/20 flex items-center gap-2 text-[10px] font-bold text-elevate-primary uppercase"><span class="w-2 h-2 rounded-full bg-elevate-primary"></span> Hadir</div>
                        <div class="px-3 py-1 rounded-xl bg-amber-50 border border-amber-100 flex items-center gap-2 text-[10px] font-bold text-amber-600 uppercase"><span class="w-2 h-2 rounded-full bg-amber-500"></span> Telat</div>
                        <div class="px-3 py-1 rounded-xl bg-rose-50 border border-rose-100 flex items-center gap-2 text-[10px] font-bold text-rose-600 uppercase"><span class="w-2 h-2 rounded-full bg-rose-500"></span> Absen</div>
                    </div>
                </div>
                <div class="relative h-64 md:h-72 w-full">
                    <canvas id="weeklyChart"></canvas>
                </div>
            </div>

            {{-- Donut Chart --}}
            <div class="animate-enter bg-white p-5 md:p-8 rounded-[2rem] fluent-card flex flex-col h-full card-print" style="animation-delay: 700ms">
                <h3 class="text-lg font-black text-elevate-dark mb-6 flex items-center gap-2">
                    <i class="ph-fill ph-chart-pie-slice text-elevate-dark"></i> Komposisi Hari Ini
                </h3>
                <div class="relative h-56 w-full flex items-center justify-center mb-6">
                    <canvas id="dailyDonutChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-3xl md:text-4xl font-black text-elevate-dark count-up" data-target="{{ $totalStudents ?? 0 }}">0</span>
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Total Siswa</span>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3 mt-auto">
                    <div class="bg-elevate-primary/10 p-3 rounded-xl border border-elevate-primary/20"><span class="block text-[10px] font-bold text-elevate-primary uppercase mb-1">Hadir Tepat</span><span class="text-lg font-black text-elevate-dark">{{ $presentOnTimeCount ?? 0 }}</span></div>
                    <div class="bg-amber-50 p-3 rounded-xl border border-amber-100"><span class="block text-[10px] font-bold text-amber-600 uppercase mb-1">Terlambat</span><span class="text-lg font-black text-elevate-dark">{{ $lateCount ?? 0 }}</span></div>
                    <div class="bg-rose-50 p-3 rounded-xl border border-rose-100"><span class="block text-[10px] font-bold text-rose-600 uppercase mb-1">Alfa</span><span class="text-lg font-black text-elevate-dark">{{ $absentCount ?? 0 }}</span></div>
                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-200"><span class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Belum Hadir</span><span class="text-lg font-black text-elevate-dark">{{ $notYetScannedCount ?? 0 }}</span></div>
                </div>
            </div>
        </div>
        
        {{-- WIDGET JADWAL MENGAJAR GURU --}}
        <div class="mb-6 flex items-center justify-between">
    <h3 class="text-lg font-black text-elevate-dark flex items-center gap-2">
        <i class="ph-fill ph-calendar-check text-elevate-primary text-xl"></i> Jadwal Mengajar Hari Ini
    </h3>
    <a href="{{ route('teaching.index') }}" class="text-xs font-bold text-elevate-primary hover:text-elevate-dark transition-colors flex items-center gap-1">
        Lihat Semua <i class="ph-bold ph-caret-right"></i>
    </a>
</div>

@if(isset($groupedSchedules) && count($groupedSchedules) > 0)
    <div class="grid grid-cols-1 gap-4">
        @foreach($groupedSchedules as $index => $group)
            @php
                // Ambil jadwal pertama dan terakhir dalam blok ini
                $firstSchedule = $group->first();
                $lastSchedule = $group->last();
                $totalJP = $group->count();
                
                // Cek status sesi melalui jam pertama
                $session = $firstSchedule->todaySession;
                
                $startJP = isset($firstSchedule->timeslot->start_time) ? \Carbon\Carbon::parse($firstSchedule->timeslot->start_time)->format('H:i') : '--:--';
                $endJP   = isset($lastSchedule->timeslot->end_time) ? \Carbon\Carbon::parse($lastSchedule->timeslot->end_time)->format('H:i') : '--:--';
                
                $orderFirst = $firstSchedule->timeslot->order_sequence ?? preg_replace('/[^0-9]/', '', $firstSchedule->timeslot->name ?? ($index + 1));
                $orderLast  = $lastSchedule->timeslot->order_sequence ?? preg_replace('/[^0-9]/', '', $lastSchedule->timeslot->name ?? ($index + 1));
                
                // Format angka "22" atau "22-24"
                $orderDisplay = $orderFirst == $orderLast ? $orderFirst : $orderFirst . '-' . $orderLast;

                if (!$session) {
                    $status = 'waiting'; 
                    $borderClass = 'border-l-[4px] border-l-elevate-accent';
                    $bgIcon = 'bg-elevate-soft text-elevate-primary border-slate-200';
                    $btnClass = 'bg-elevate-dark hover:bg-elevate-primary text-white'; 
                } elseif ($session->status == 'open') {
                    $status = 'ongoing';
                    $borderClass = 'border-l-[4px] border-l-[#107C10] ring-1 ring-[#107C10]/10';
                    $bgIcon = 'bg-[#DFF6DD] text-[#107C10] border-[#B7DFB9]';
                    $btnClass = 'bg-[#107C10] hover:bg-[#0c5c0c] text-white'; 
                } else {
                    $status = 'done';
                    $borderClass = 'border-l-[4px] border-l-slate-300 bg-slate-50/50';
                    $bgIcon = 'bg-white text-slate-400 border-slate-200';
                }
            @endphp

            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 md:p-5 {{ $borderClass }} flex flex-col md:flex-row justify-between items-center gap-4 group hover:shadow-md transition-shadow fluent-card">
                
                {{-- INFO KIRI --}}
                <div class="flex items-center gap-4 w-full md:w-auto">
                    <div class="flex flex-col items-center justify-center w-16 h-16 rounded-xl {{ $bgIcon }} shrink-0 shadow-sm border transition-colors relative">
                        @if($totalJP > 1)
                            <div class="absolute -top-2 -right-2 bg-elevate-peach text-elevate-peach-dark text-[9px] font-black px-2 py-0.5 rounded-full border border-white shadow-sm z-10">
                                {{ $totalJP }} JP
                            </div>
                        @endif
                        <span class="text-[8px] font-bold uppercase tracking-wider opacity-70">Sesi</span>
                        <span class="text-xl font-black leading-none mt-0.5">{{ $orderDisplay }}</span>
                    </div>
                    <div>
                        <h4 class="font-black text-elevate-dark text-lg group-hover:text-elevate-primary transition-colors line-clamp-1">{{ $firstSchedule->subject->name ?? 'Pelajaran' }}</h4>
                        <div class="flex flex-wrap gap-2 mt-1.5">
                            <span class="flex items-center gap-1 text-[10px] font-bold text-slate-500 bg-slate-100 px-2 py-1 rounded-md border border-slate-200">
                                <i class="ph-bold ph-users-three"></i> Kls {{ $firstSchedule->studentClass->name ?? $firstSchedule->schoolClass->name ?? '-' }}
                            </span>
                            <span class="flex items-center gap-1 text-[10px] font-bold text-slate-500 bg-slate-100 px-2 py-1 rounded-md border border-slate-200">
                                <i class="ph-bold ph-clock"></i> {{ $startJP }}-{{ $endJP }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- TOMBOL KANAN --}}
                <div class="w-full md:w-auto shrink-0 mt-2 md:mt-0">
                    @if($status == 'waiting')
                        <form action="{{ route('teaching.start', $firstSchedule->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full md:w-auto px-5 py-2.5 {{ $btnClass }} font-bold rounded-xl shadow-sm transition transform flex items-center justify-center gap-2 active:scale-95 text-xs border border-transparent">
                                <i class="ph-bold ph-play-circle text-base"></i> 
                                {{ $totalJP > 1 ? "Mulai ({$totalJP} JP)" : "Mulai Mengajar" }}
                            </button>
                        </form>
                    @elseif($status == 'ongoing')
                        <div class="flex flex-col md:items-end gap-2">
                            <div class="flex items-center justify-center md:justify-end gap-1.5 text-[#107C10] font-black text-[9px] uppercase tracking-widest bg-[#DFF6DD] px-2.5 py-1 rounded-full border border-[#B7DFB9]">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#107C10] animate-pulse"></span> Sedang Berlangsung
                            </div>
                            <a href="{{ route('teaching.show', $session->id) }}" class="w-full md:w-auto px-5 py-2.5 {{ $btnClass }} font-bold rounded-xl shadow-sm transition transform flex items-center justify-center gap-2 active:scale-95 text-xs border border-transparent">
                                Buka Kelas <i class="ph-bold ph-arrow-right"></i>
                            </a>
                        </div>
                    @else
                        <div class="flex items-center gap-2 justify-end w-full">
                            <span class="px-4 py-2.5 bg-slate-100 text-slate-400 font-bold text-xs rounded-xl flex items-center gap-1.5 border border-slate-200 cursor-not-allowed w-full md:w-auto justify-center">
                                <i class="ph-fill ph-check-circle"></i> Selesai
                            </span>
                            <a href="{{ route('teaching.show', $session->id) }}" class="p-2.5 bg-white border border-slate-200 rounded-xl text-slate-400 hover:text-elevate-primary hover:border-elevate-accent/50 hover:bg-elevate-soft transition-all shadow-sm active:scale-95 shrink-0" title="Lihat Detail">
                                <i class="ph-bold ph-eye text-base"></i>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="text-center py-12 bg-white rounded-2xl border border-dashed border-slate-200 fluent-card">
        <div class="w-16 h-16 bg-elevate-soft rounded-full flex items-center justify-center mx-auto mb-4 text-elevate-primary border border-slate-100">
            <i class="ph-duotone ph-coffee text-3xl"></i>
        </div>
        <h3 class="text-elevate-dark font-black text-base mb-1">Tidak Ada Jadwal Mengajar</h3>
        <p class="text-slate-500 max-w-xs mx-auto text-xs font-medium">
            Hari ini Anda tidak memiliki jadwal kelas.
        </p>
    </div>
@endif

        {{-- TABLES SECTION --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 page-break-inside-avoid">
            {{-- Activity Log --}}
            <div class="animate-enter bg-white p-6 md:p-8 rounded-[2rem] fluent-card flex flex-col h-full card-print" style="animation-delay: 800ms" x-data="{ tab: 'activity' }">
                <div class="flex items-center justify-between mb-6 no-print">
                    <div class="flex gap-6 border-b border-slate-100 w-full">
                        <button @click="tab = 'activity'" :class="tab === 'activity' ? 'text-elevate-primary border-elevate-primary' : 'text-slate-400 border-transparent hover:text-slate-600'" class="text-sm font-bold pb-3 border-b-2 transition-all px-1">Aktivitas Terbaru</button>
                        <button @click="tab = 'late_recap'" :class="tab === 'late_recap' ? 'text-amber-600 border-amber-600' : 'text-slate-400 border-transparent hover:text-slate-600'" class="text-sm font-bold pb-3 border-b-2 transition-all px-1">Top Terlambat</button>
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
                                    $theme = ['bg_icon' => 'bg-elevate-primary/10', 'border_icon' => 'border-elevate-primary/20', 'text_icon' => 'text-elevate-primary', 'dot' => 'bg-elevate-primary', 'bg_badge' => 'bg-elevate-primary/10', 'text_badge' => 'text-elevate-primary'];
                                    $icon = 'ph-check-circle';

                                    if ($type === 'Keagamaan') {
                                        $icon = 'ph-moon-stars'; $statusText = $log->activity; $subText = 'Ibadah';
                                        $theme = ['bg_icon' => 'bg-slate-100', 'border_icon' => 'border-slate-200', 'text_icon' => 'text-elevate-dark', 'dot' => 'bg-elevate-dark', 'bg_badge' => 'bg-slate-100', 'text_badge' => 'text-elevate-dark'];
                                    } elseif ($type === 'Ekstrakurikuler') {
                                        $icon = 'ph-trophy'; $statusText = $log->activity; $subText = 'Ekstrakurikuler';
                                        $theme = ['bg_icon' => 'bg-amber-50', 'border_icon' => 'border-amber-100', 'text_icon' => 'text-amber-600', 'dot' => 'bg-amber-500', 'bg_badge' => 'bg-amber-50', 'text_badge' => 'text-amber-600'];
                                    } else {
                                        if ($log->status == 'Terlambat') {
                                            $icon = 'ph-clock-warning';
                                            $theme = ['bg_icon' => 'bg-amber-50', 'border_icon' => 'border-amber-100', 'text_icon' => 'text-amber-600', 'dot' => 'bg-amber-500', 'bg_badge' => 'bg-amber-50', 'text_badge' => 'text-amber-600'];
                                        } elseif ($type == 'Pulang') {
                                            $icon = 'ph-person-simple-walk'; $statusText = 'Pulang'; $subText = 'Selesai KBM';
                                            $theme = ['bg_icon' => 'bg-elevate-primary/10', 'border_icon' => 'border-elevate-primary/20', 'text_icon' => 'text-elevate-primary', 'dot' => 'bg-elevate-primary', 'bg_badge' => 'bg-elevate-primary/10', 'text_badge' => 'text-elevate-primary'];
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
                                            <p class="text-sm font-bold text-elevate-dark line-clamp-1 group-hover:text-elevate-primary transition-colors">{{ $log->student->name ?? 'Siswa' }}</p>
                                            <p class="text-[10px] text-slate-500 font-bold px-2 py-0.5 rounded-lg inline-block mt-1 border border-slate-100 bg-white">{{ $subText }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right shrink-0">
                                        <p class="text-xs font-bold font-mono text-slate-600 mb-1">{{ $log->created_at->format('H:i') }}</p>
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
                            <div class="flex items-center justify-between p-3 rounded-2xl border border-slate-50 hover:bg-rose-50/50 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 font-bold text-xs flex items-center justify-center border border-slate-200">#{{ $index + 1 }}</div>
                                    <div>
                                        <div class="text-xs font-bold text-elevate-dark line-clamp-1">{{ $student->student->name ?? 'Siswa' }}</div>
                                        <div class="text-[10px] text-slate-400">{{ $student->student->schoolClass->name ?? '-' }}</div>
                                    </div>
                                </div>
                                <span class="text-xs font-black text-rose-600 bg-rose-50 px-3 py-1 rounded-lg border border-rose-100">{{ $student->total_late }}x</span>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-10 text-slate-400 text-xs font-bold">Tidak ada siswa terlambat signifikan.</div>
                    @endif
                </div>
            </div>

            {{-- Class Rank Table --}}
            <div class="animate-enter bg-white p-6 md:p-8 rounded-[2rem] fluent-card h-full card-print" style="animation-delay: 900ms" x-data="{ rankTab: 'best' }">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-black text-elevate-dark flex items-center gap-2">
                        <i class="ph-fill ph-trophy text-amber-500 text-xl drop-shadow-sm"></i> Peringkat Kelas
                    </h3>
                    
                    {{-- Tab Switcher --}}
                    <div class="bg-slate-100 p-1 rounded-xl flex no-print border border-slate-200">
                        <button @click="rankTab = 'best'" :class="rankTab === 'best' ? 'bg-white text-elevate-primary shadow-sm' : 'text-slate-500 hover:text-slate-700'" class="px-3 py-1.5 rounded-lg text-[10px] font-bold transition-all">Rajin</button>
                        <button @click="rankTab = 'worst'" :class="rankTab === 'worst' ? 'bg-white text-rose-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'" class="px-3 py-1.5 rounded-lg text-[10px] font-bold transition-all">Perlu Atensi</button>
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
                                        @if($index == 0) <i class="ph-fill ph-medal text-amber-500 text-2xl drop-shadow-sm"></i>
                                        @elseif($index == 1) <i class="ph-fill ph-medal text-slate-400 text-xl"></i>
                                        @elseif($index == 2) <i class="ph-fill ph-medal text-amber-700 text-xl"></i>
                                        @else <span class="font-bold text-slate-400 ml-1.5">#{{ $index + 1 }}</span> @endif
                                    </td>
                                    <td class="py-4">
                                        <div class="font-bold text-elevate-dark mb-1">{{ $rank->class_name }}</div>
                                        <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden max-w-[150px]">
                                            @php $percent = min(100, ($rank->present_count / 40) * 100); @endphp
                                            <div class="h-1.5 rounded-full {{ $index == 0 ? 'bg-amber-500' : 'bg-elevate-primary' }}" style="width: {{ $percent }}%"></div>
                                        </div>
                                    </td>
                                    <td class="py-4 text-right pr-2">
                                        <div class="font-black text-elevate-dark">{{ number_format($percent, 0) }}%</div>
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
                                        <div class="font-bold text-elevate-dark mb-1">{{ $rank->class_name }}</div>
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
                     <div class="flex flex-col items-center justify-center h-40 text-center text-emerald-600">
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

    {{-- SCRIPT INITIALIZATION & SWEETALERT CUSTOM FUNCTION --}}
    <script>
        // Fungsi Popup Info dengan warna semantik Elevate
        function showCardInfo(title, value, colorKey) {
            let colorHex = '#3b5889'; // default primary (Elevate Blue)
            if(colorKey === 'success') colorHex = '#10b981'; // Emerald
            if(colorKey === 'warning') colorHex = '#f59e0b'; // Amber
            if(colorKey === 'danger') colorHex = '#f43f5e'; // Rose
            if(colorKey === 'info') colorHex = '#032b5b'; // Elevate Dark
            if(colorKey === 'neutral') colorHex = '#64748b'; // Slate

            Swal.fire({
                title: `<span style="color: ${colorHex}; font-weight: 900;">${title}</span>`,
                html: `
                    <div class="mt-2 mb-6">
                        <span class="text-5xl font-black text-elevate-dark">${value}</span>
                        <span class="text-sm font-bold text-slate-400 ml-1">Siswa</span>
                    </div>
                    <p class="text-sm text-slate-500 leading-relaxed mb-4">
                        Untuk melihat daftar nama siswa secara spesifik, silakan buka menu <b>Data Siswa</b> atau menu <b>Laporan Kelas</b> di pintasan atas.
                    </p>
                    <div class="inline-block bg-slate-50 border border-slate-200 px-3 py-1.5 rounded-lg text-xs font-bold text-slate-400">
                        Halaman tabel detail sedang dalam tahap persiapan.
                    </div>
                `,
                icon: 'info',
                confirmButtonColor: colorHex,
                confirmButtonText: 'Tutup Info',
                customClass: {
                    popup: 'fluent-modal rounded-[2rem]',
                    confirmButton: 'rounded-xl font-bold px-6 py-3 transition-shadow fluent-card'
                }
            });
        }

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
            
            // Bar Chart (Warna Microsoft Elevate Tailwind)
            const ctxBar = document.getElementById('weeklyChart');
            if(ctxBar) {
                const hasData = rawPresent.some(x => x > 0) || rawLate.some(x => x > 0) || rawAbsent.some(x => x > 0);
                if (hasData) {
                    new Chart(ctxBar.getContext('2d'), {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [
                                { label: 'Hadir', data: rawPresent, backgroundColor: '#3b5889', borderRadius: 4, barThickness: 12 }, // elevate-primary
                                { label: 'Telat', data: rawLate, backgroundColor: '#f59e0b', borderRadius: 4, barThickness: 12 }, // amber-500
                                { label: 'Absen', data: rawAbsent, backgroundColor: '#f43f5e', borderRadius: 4, barThickness: 12 } // rose-500
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

            // Donut Chart (Warna Semantik Microsoft)
            const ctxDonut = document.getElementById('dailyDonutChart');
            if(ctxDonut) {
                new Chart(ctxDonut.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Hadir Tepat', 'Telat', 'Alfa', 'Izin/Sakit', 'Belum Hadir'],
                        datasets: [{ 
                            data: [
                                {{ $presentOnTimeCount ?? 0 }}, 
                                {{ $lateCount ?? 0 }}, 
                                {{ $absentCount ?? 0 }}, 
                                {{ $sickPermitCount ?? 0 }},
                                {{ $notYetScannedCount ?? 0 }}
                            ], 
                            backgroundColor: ['#3b5889', '#f59e0b', '#f43f5e', '#032b5b', '#cbd5e1'], // Primary, Amber, Rose, Navy, Slate
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