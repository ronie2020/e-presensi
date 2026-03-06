<x-app-layout>
    {{-- 1. CUSTOM STYLES & FONTS --}}
    <style>
        @import url('https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap');
        
        .font-jakarta { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        @keyframes fadeInUp { 
            from { opacity: 0; transform: translateY(20px); } 
            to { opacity: 1; transform: translateY(0); } 
        }
        .animate-enter { 
            opacity: 0; 
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; 
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }

        @media print {
            @page { size: landscape; margin: 10mm; }
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; background: white !important; }
            .no-print, header, nav, footer, form, .shadow-sm, .modal-backdrop, .hero-decoration { display: none !important; }
            .print-container { padding: 0 !important; margin: 0 !important; border: none !important; box-shadow: none !important; width: 100% !important; background: white !important; }
            .print-table th, .print-table td { border: 1px solid #cbd5e1 !important; font-size: 10pt !important; padding: 8px !important; }
            .print-header { display: block !important; margin-bottom: 20px; text-align: center; }
            .page-container { padding: 0 !important; background: white !important; }
        }
        .print-header { display: none; }
        [x-cloak] { display: none !important; }
    </style>

    {{-- WRAPPER DENGAN ALPINE JS --}}
    <div x-data="{
        isModalOpen: false,
        studentName: '',
        details: {
            fasting: false,
            prayers: {}, 
            sunnahs: {}, 
            khotib: '',
            summary: '',
            tadarus_surah: '',
            tadarus_ayah: '',
            murojaah: '',
            kultum_penceramah: '',
            kultum_summary: ''
        }, 
        formAction: '',
        currentScore: '',
        currentNote: '',
        
        openFeedback(id, logData, sName) {
            this.studentName = sName;
            this.currentScore = logData.teacher_score !== null ? logData.teacher_score : 100;
            this.currentNote = logData.teacher_note || '';
            
            this.details = {
                fasting: logData.is_fasting || false,
                prayers: logData.prayers || {},
                sunnahs: logData.sunnah_deeds || {},
                khotib: logData.friday_khotib || '',
                summary: logData.friday_summary || '',
                tadarus_surah: logData.tadarus_surah || '',
                tadarus_ayah: logData.tadarus_ayah || '',
                murojaah: logData.murojaah_surah || '',
                kultum_penceramah: logData.kultum_penceramah || '',
                kultum_summary: logData.kultum_summary || ''
            };

            this.formAction = '{{ route('admin.ramadan.verify', ':id') }}'.replace(':id', id); 
            this.isModalOpen = true;
            document.body.style.overflow = 'hidden';
        },

        closeModal() {
            this.isModalOpen = false;
            document.body.style.overflow = 'auto';
        },
        setScore(value) { this.currentScore = value; }
    }" class="page-container p-4 md:p-8 space-y-8 min-h-screen bg-slate-50 font-jakarta print-container">
        
       {{-- HERO SECTION --}}
        <div class="animate-enter relative rounded-[3rem] bg-gradient-to-br from-emerald-900 via-teal-900 to-slate-900 p-8 md:p-12 text-white shadow-2xl shadow-emerald-900/30 overflow-hidden group border border-white/10 no-print">
            <div class="absolute top-0 right-0 -mt-20 -mr-20 w-[500px] h-[500px] bg-emerald-500/20 rounded-full blur-[100px] group-hover:opacity-40 transition-opacity duration-1000 hero-decoration"></div>
            <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-[400px] h-[400px] bg-teal-500/10 rounded-full blur-[80px] hero-decoration"></div>
            
            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-10">
                <div class="flex-1 min-w-0">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 border border-white/10 text-emerald-300 text-[10px] font-black uppercase tracking-[0.2em] mb-6 backdrop-blur-md shadow-inner">
                        <i class="ph-fill ph-moon-stars"></i> Program Ramadhan
                    </div>
                    <h1 class="text-4xl md:text-5xl font-black text-white tracking-tighter mb-4 leading-none">
                        Rekap <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-300 to-teal-200">Mutabaah</span> Siswa
                    </h1>
                    <p class="text-emerald-100/60 text-sm md:text-lg max-w-xl leading-relaxed font-medium">
                        Monitoring ibadah harian dan kegiatan Ramadhan.
                        <br><span class="text-white font-bold">{{ \Carbon\Carbon::parse($date)->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
                    </p>
                </div>

                {{-- FILTER FORM --}}
                <div class="w-full lg:w-[450px] xl:w-[500px] shrink-0 flex flex-col gap-4">
                    <form action="{{ route('admin.ramadan.reports') }}" method="GET" class="bg-white/5 backdrop-blur-xl p-6 rounded-[2rem] border border-white/10 shadow-2xl flex flex-col gap-5">
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-emerald-200 uppercase tracking-widest ml-1 block">Tanggal</label>
                                <div class="relative group">
                                    <i class="ph-bold ph-calendar absolute left-4 top-1/2 -translate-y-1/2 text-emerald-400"></i>
                                    <input type="date" name="date" value="{{ $date }}" onchange="this.form.submit()" 
                                        class="block w-full pl-11 pr-4 py-3 bg-white/10 border-white/10 rounded-2xl text-xs font-bold text-white focus:ring-emerald-500 focus:border-emerald-500 transition-all uppercase placeholder-emerald-200/50">
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-emerald-200 uppercase tracking-widest ml-1 block">Kelas</label>
                                <div class="relative group">
                                    <i class="ph-bold ph-chalkboard absolute left-4 top-1/2 -translate-y-1/2 text-emerald-400"></i>
                                    <select name="class_id" onchange="this.form.submit()" 
                                        class="block w-full pl-11 pr-10 py-3 bg-white/10 border-white/10 rounded-2xl text-xs font-bold text-white focus:ring-emerald-500 focus:border-emerald-500 transition-all appearance-none">
                                        <option value="" class="bg-slate-900 text-white">Semua Kelas</option>
                                        @foreach($classes as $c)
                                            <option value="{{ $c->id }}" {{ $selectedClass == $c->id ? 'selected' : '' }} class="bg-slate-900 text-white">{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="space-y-2 sm:col-span-2">
                                <label class="text-[10px] font-black text-emerald-200 uppercase tracking-widest ml-1 block">Cari Siswa</label>
                                <div class="relative group flex items-center gap-2">
                                    <div class="relative w-full">
                                        <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-emerald-400"></i>
                                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama..." 
                                            class="block w-full pl-11 pr-4 py-3 bg-white/10 border-white/10 rounded-2xl text-xs font-bold text-white focus:ring-emerald-500 focus:border-emerald-500 transition-all placeholder-emerald-200/50">
                                    </div>
                                    <button type="submit" class="p-3 rounded-2xl bg-emerald-600 text-white hover:bg-emerald-500 transition"><i class="ph-bold ph-caret-right"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    {{-- Tombol Export --}}
                    @if($selectedClass)
                        <div class="flex items-center gap-2 mt-2">
                            <button onclick="window.print()" class="flex-1 py-3 bg-slate-800 hover:bg-slate-700 text-white rounded-xl font-bold shadow-lg flex flex-col items-center justify-center gap-1 transition-all active:scale-95 border border-white/10">
                                <i class="ph-bold ph-printer text-xl"></i>
                                <span class="uppercase tracking-wider text-[9px]">Cetak Harian</span>
                            </button>
                            <a href="{{ route('admin.ramadan.exportPdf', ['class_id' => $selectedClass]) }}" target="_blank" class="flex-1 py-3 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl font-bold shadow-lg flex flex-col items-center justify-center gap-1 transition-all active:scale-95 border border-white/10">
                                <i class="ph-bold ph-file-pdf text-xl"></i>
                                <span class="uppercase tracking-wider text-[9px]">Rekap Full</span>
                            </a>
                            <a href="{{ route('admin.ramadan.exportExcel', ['class_id' => $selectedClass]) }}" class="flex-1 py-3 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-bold shadow-lg flex flex-col items-center justify-center gap-1 transition-all active:scale-95 border border-white/10">
                                <i class="ph-bold ph-file-csv text-xl"></i>
                                <span class="uppercase tracking-wider text-[9px]">Excel (CSV)</span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- BENTO GRID STATS --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 animate-enter no-print" style="animation-delay: 100ms">
            <div class="glass-card p-6 rounded-[2rem] flex items-center gap-4 group">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shadow-inner group-hover:scale-110 transition-transform"><i class="ph-fill ph-student"></i></div>
                <div>
                    <div class="text-2xl font-black text-slate-800 tracking-tight">{{ $stats['total_students'] }}</div>
                    <div class="text-[9px] uppercase font-black text-slate-400 tracking-widest">
                        {{ $selectedClass ? 'Siswa Kelas' : 'Total Siswa' }}
                    </div>
                </div>
            </div>
            
            <div class="glass-card p-6 rounded-[2rem] flex items-center gap-4 group">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shadow-inner group-hover:scale-110 transition-transform shrink-0"><i class="ph-fill ph-bowl-food"></i></div>
                <div class="flex-1 w-full">
                    <div class="text-2xl font-black text-slate-800 tracking-tight">{{ $stats['fasting_count'] }} <span class="text-xs text-emerald-500 font-bold">({{ $stats['percentage_fasting'] }}%)</span></div>
                    <div class="text-[9px] uppercase font-black text-slate-400 tracking-widest mb-1">Berpuasa</div>
                    {{-- TAMBAHAN: Progress Bar Visual --}}
                    <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-emerald-500 h-1.5 rounded-full transition-all duration-1000" style="width: {{ $stats['percentage_fasting'] }}%"></div>
                    </div>
                </div>
            </div>
            
            <div class="glass-card p-6 rounded-[2rem] flex items-center gap-4 group">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shadow-inner group-hover:scale-110 transition-transform"><i class="ph-fill ph-hands-praying"></i></div>
                <div>
                    <div class="text-2xl font-black text-slate-800 tracking-tight">{{ $stats['prayer_complete_count'] }}</div>
                    <div class="text-[9px] uppercase font-black text-slate-400 tracking-widest">Shalat 5W</div>
                </div>
            </div>

            @if($isFriday)
            <div class="bg-emerald-600 p-6 rounded-[2rem] flex items-center gap-4 group text-white shadow-xl shadow-emerald-500/20 relative overflow-hidden">
                <div class="absolute -right-4 -top-4 text-emerald-500/30"><i class="ph-fill ph-mosque text-6xl"></i></div>
                <div class="w-12 h-12 rounded-2xl bg-white/20 flex items-center justify-center text-xl backdrop-blur-sm"><i class="ph-fill ph-mosque"></i></div>
                <div class="relative z-10">
                    <div class="text-2xl font-black tracking-tight">{{ $stats['friday_log_count'] }}</div>
                    <div class="text-[9px] uppercase font-black text-emerald-100 tracking-widest">Jurnal Jumat</div>
                </div>
            </div>
            @else
            <div class="bg-slate-100 p-6 rounded-[2rem] flex items-center gap-4 group border border-slate-200">
                <div class="w-12 h-12 rounded-2xl bg-white text-slate-400 flex items-center justify-center text-xl"><i class="ph-bold ph-calendar-x"></i></div>
                <div>
                    <div class="text-xs font-bold text-slate-500">Bukan Hari Jumat</div>
                    <div class="text-[9px] uppercase font-black text-slate-400 tracking-widest">Fitur Non-aktif</div>
                </div>
            </div>
            @endif
        </div>

        {{-- ======================================================= --}}
        {{-- BAGIAN BARU: LEADERBOARD SISWA PALING RAJIN --}}
        {{-- ======================================================= --}}
        @if(isset($topStudents) && $topStudents->count() > 0)
        <div class="animate-enter bg-white rounded-[3rem] shadow-sm border border-slate-100 p-6 md:p-8 no-print" style="animation-delay: 150ms">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-xl font-black text-slate-800 flex items-center gap-2">
                        <i class="ph-fill ph-crown text-amber-500 text-2xl"></i> Siswa Paling Rajin
                    </h3>
                    <p class="text-slate-500 text-xs font-medium mt-1">
                        Berdasarkan konsistensi pengisian mutabaah terbanyak selama bulan Ramadhan ini.
                    </p>
                </div>
                <div class="shrink-0 flex items-center gap-2">
                    <span class="px-4 py-1.5 rounded-xl bg-amber-50 text-amber-600 text-[10px] font-black uppercase tracking-wider border border-amber-100 hidden sm:inline-block">
                        Top {{ $topStudents->count() }} Leaderboard
                    </span>
                    {{-- TAMBAHAN: Tombol Link ke Halaman Papan Peringkat Full --}}
                    <a href="{{ route('admin.ramadan.leaderboard') }}" class="px-4 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-white text-[10px] font-black uppercase tracking-wider transition-colors shadow-md flex items-center gap-1">
                        Lihat Semua <i class="ph-bold ph-arrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($topStudents as $index => $topStudent)
                    @php
                        // Styling dinamis berdasarkan ranking (1, 2, 3)
                        $bgClass = $index == 0 ? 'bg-gradient-to-br from-amber-50 to-orange-50 border-amber-200 shadow-lg shadow-amber-500/10' : 
                                  ($index == 1 ? 'bg-gradient-to-br from-slate-50 to-gray-100 border-slate-200' : 
                                                 'bg-gradient-to-br from-orange-50 to-rose-50 border-orange-200');
                                                 
                        $avatarClass = $index == 0 ? 'border-amber-300 shadow-amber-500/30' : 
                                      ($index == 1 ? 'border-slate-300 shadow-slate-500/30' : 
                                                     'border-orange-300 shadow-orange-500/30');
                                                     
                        $badgeClass = $index == 0 ? 'bg-amber-500 text-white' : 
                                     ($index == 1 ? 'bg-slate-500 text-white' : 
                                                    'bg-orange-500 text-white');
                                                    
                        $textColor = $index == 0 ? 'text-amber-700' : ($index == 1 ? 'text-slate-700' : 'text-orange-700');
                    @endphp

                    <div class="flex items-center gap-4 p-4 rounded-[2rem] border transition-all hover:-translate-y-1 hover:shadow-xl {{ $bgClass }}">
                        
                        {{-- Avatar & Ranking Badge --}}
                        <div class="relative shrink-0">
                            {{-- PERBAIKAN: Menggunakan UI-Avatars agar lebih cantik seperti di leaderboard siswa --}}
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center font-black text-xl shadow-lg border-2 overflow-hidden bg-white {{ $avatarClass }}">
                                @php
                                    $avatarBg = $index == 0 ? 'f59e0b' : ($index == 1 ? '64748b' : 'f97316');
                                @endphp
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($topStudent->name) }}&background={{ $avatarBg }}&color=fff&bold=true" alt="Avatar" class="w-full h-full object-cover">
                            </div>
                            <div class="absolute -top-2 -right-2 w-7 h-7 rounded-full flex items-center justify-center text-[11px] font-black border-2 border-white shadow-sm {{ $badgeClass }}">
                                #{{ $index + 1 }}
                            </div>
                        </div>

                        {{-- Nama & Kelas --}}
                        <div class="flex-1 min-w-0">
                            <h4 class="font-black text-slate-800 text-sm truncate" title="{{ $topStudent->name }}">
                                {{ $topStudent->name }}
                            </h4>
                            <div class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mt-0.5 flex items-center gap-1">
                                <i class="ph-fill ph-chalkboard text-slate-400"></i> {{ $topStudent->schoolClass->name ?? 'N/A' }}
                            </div>
                        </div>

                        {{-- Total Hari --}}
                        <div class="text-right shrink-0 bg-white/50 px-3 py-2 rounded-xl backdrop-blur-sm border border-white/50">
                            <div class="text-xl font-black leading-none {{ $textColor }}">
                                {{ $topStudent->total_logs_count ?? $topStudent->total_logs ?? 0 }}
                            </div>
                            <div class="text-[8px] uppercase font-black {{ $textColor }} opacity-70 tracking-widest mt-1">Hari</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
        {{-- ======================================================= --}}


        {{-- HEADER PRINT --}}
        <div class="print-header">
            <h2 class="text-xl font-bold uppercase">Laporan Harian Mutabaah Ramadhan</h2>
            <p class="text-sm">{{ $classes->find($selectedClass)->name ?? 'Semua Kelas' }} &bull; {{ \Carbon\Carbon::parse($date)->isoFormat('dddd, D MMMM Y') }}</p>
        </div>
        
        {{-- KONTEN UTAMA --}}
        @if($selectedClass)
            {{-- === TAMPILAN TABEL KELAS (JIKA KELAS DIPILIH) === --}}
            <div class="animate-enter bg-white rounded-[3rem] shadow-sm border border-slate-100 overflow-hidden mb-12 print-container" style="animation-delay: 200ms">
                <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between no-print">
                    <h2 class="text-xl font-black text-slate-800 flex items-center gap-3">
                        <i class="ph-bold ph-list-checks text-blue-600"></i> Data Mutabaah Kelas
                    </h2>
                    <span class="px-4 py-1.5 rounded-xl bg-slate-50 text-slate-500 text-[10px] font-black uppercase tracking-wider">
                        {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
                    </span>
                </div>

                {{-- TAMBAHAN: max-h-[600px] agar tabel bisa discroll secara internal dan headernya menempel --}}
                <div class="overflow-x-auto overflow-y-auto custom-scrollbar max-h-[600px] relative">
                    <table class="w-full text-left print-table">
                        {{-- TAMBAHAN: sticky top-0 z-20 backdrop-blur-md agar header melayang --}}
                        <thead class="bg-slate-50/90 backdrop-blur-md text-slate-400 uppercase text-[9px] font-black tracking-[0.2em] border-b border-slate-200 sticky top-0 z-20 shadow-sm">
                            <tr>
                                <th class="px-8 py-6 text-center w-16">No</th>
                                <th class="px-6 py-6">Profil Siswa</th>
                                <th class="px-6 py-6 text-center">Puasa</th>
                                <th class="px-6 py-6 text-center">Shalat 5W</th>
                                <th class="px-6 py-6 text-center no-print">Detail</th>
                                
                                @if($isFriday)
                                <th class="px-6 py-6 text-center text-emerald-600 bg-emerald-50/30">Jumat</th>
                                @endif

                                <th class="px-6 py-6">Tilawah</th>
                                <th class="px-8 py-6 text-center bg-slate-50/50">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($reports as $index => $student)
                            @php 
                                $log = $student->ramadanLogs->first();
                                $prayerCount = $log ? count(array_filter($log->prayers ?? [])) : 0;
                            @endphp
                            <tr class="hover:bg-blue-50/30 transition-all group">
                                <td class="px-8 py-5 text-center text-xs font-bold text-slate-400">{{ $index + 1 }}</td>
                                <td class="px-6 py-5">
                                    <div class="font-black text-slate-800 text-sm group-hover:text-blue-600 transition-colors">{{ $student->name }}</div>
                                    <div class="text-[10px] font-bold text-slate-400 mt-0.5 tracking-wider">{{ $student->student_id }}</div>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    @if($log && $log->is_fasting)
                                        <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto shadow-sm"><i class="ph-fill ph-check"></i></div>
                                    @elseif($log)
                                        <div class="w-8 h-8 rounded-full bg-rose-100 text-rose-500 flex items-center justify-center mx-auto shadow-sm"><i class="ph-bold ph-x"></i></div>
                                    @else
                                        <span class="text-slate-300 font-bold">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-5 text-center">
                                    @if($log)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-black {{ $prayerCount == 5 ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                                            {{ $prayerCount }}/5
                                        </span>
                                    @else
                                        <span class="text-slate-300 font-bold">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-5 text-center no-print">
                                    @if($log && is_array($log->prayers))
                                        <div class="flex justify-center gap-1.5">
                                            @foreach(['subuh','dzuhur','ashar','maghrib','isya'] as $p)
                                                <div title="{{ ucfirst($p) }}" class="w-2.5 h-2.5 rounded-full {{ ($log->prayers[$p] ?? false) ? 'bg-emerald-500' : 'bg-slate-200' }}"></div>
                                            @endforeach
                                        </div>
                                    @else
                                        -
                                    @endif
                                </td>

                                @if($isFriday)
                                <td class="px-6 py-5 text-center">
                                    @if($log && $log->friday_khotib)
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-emerald-100 text-emerald-700 text-[10px] font-black border border-emerald-200 shadow-sm" title="{{ Str::limit($log->friday_summary, 50) }}">
                                            <i class="ph-bold ph-check-circle"></i> Ada
                                        </span>
                                    @else
                                        <span class="text-slate-300 text-[10px] italic font-medium">Kosong</span>
                                    @endif
                                </td>
                                @endif

                                <td class="px-6 py-5">
                                    @if($log && $log->tadarus_surah)
                                        <div class="flex items-center gap-2">
                                            <i class="ph-bold ph-book-open-text text-blue-500"></i>
                                            <span class="text-xs font-bold text-slate-700">{{ Str::limit($log->tadarus_surah, 12) }} : {{ $log->tadarus_ayah }}</span>
                                        </div>
                                    @else
                                        <span class="text-slate-300 text-[10px] font-bold">-</span>
                                    @endif
                                </td>
                                
                                <td class="px-8 py-5 text-center bg-slate-50/50">
                                    @if($log)
                                        <button type="button" 
                                            @click="openFeedback({{ $log->id }}, {{ json_encode($log) }}, {{ json_encode($student->name) }})"
                                            class="w-full inline-flex justify-center items-center gap-2 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-wider transition-all shadow-sm hover:shadow-md active:scale-95
                                            {{ $log->teacher_verified_at ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : 'bg-white border border-slate-200 text-slate-500 hover:bg-emerald-600 hover:text-white hover:border-emerald-600' }}">
                                            @if($log->teacher_verified_at)
                                                <i class="ph-bold ph-check-circle text-lg"></i> {{ $log->teacher_score }}
                                            @else
                                                <i class="ph-bold ph-pencil-simple text-lg"></i> Nilai
                                            @endif
                                        </button>
                                    @else
                                        <span class="text-slate-300 text-[10px] italic font-bold">Belum Ada</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="{{ $isFriday ? 8 : 7 }}" class="text-center py-20 text-slate-400 font-medium italic">Data siswa tidak ditemukan untuk kelas ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            {{-- === TAMPILAN FEED DASHBOARD DENGAN TAB FILTER DAN PAGINATION === --}}
            <div class="animate-enter space-y-6 no-print" style="animation-delay: 100ms">
                
                {{-- HEADER FEED & TABS --}}
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 px-2">
                    <div>
                        <h3 class="text-2xl font-black text-slate-800 tracking-tight flex items-center gap-2">
                            <i class="ph-duotone ph-lightning text-amber-500"></i>
                            Aktivitas Ramadhan Global
                        </h3>
                        <p class="text-slate-500 text-sm font-medium mt-1">Pantau seluruh aktivitas siswa pada hari ini.</p>
                    </div>

                    {{-- TABS FILTER STATUS --}}
                    <div class="flex items-center gap-2 bg-white p-1.5 rounded-2xl border border-slate-200 shadow-sm">
                        <a href="{{ request()->fullUrlWithQuery(['status' => null, 'page' => 1]) }}" 
                           class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all {{ !request('status') ? 'bg-slate-800 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50' }}">
                            <i class="ph-bold ph-list-dashes"></i> Semua
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['status' => 'pending', 'page' => 1]) }}" 
                           class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all flex items-center gap-2 {{ request('status') == 'pending' ? 'bg-amber-100 text-amber-700 shadow-sm border border-amber-200' : 'text-slate-500 hover:bg-slate-50' }}">
                            <i class="ph-bold ph-clock-countdown"></i> Antrean Penilaian
                        </a>
                    </div>
                </div>

                {{-- GRID CARD SISWA --}}
                @if(isset($latestLogs) && $latestLogs->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        @foreach($latestLogs as $log)
                        @php $prayerCount = count(array_filter($log->prayers ?? [])); @endphp
                        <div class="group bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-emerald-900/5 hover:border-emerald-200 transition-all duration-300 relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-emerald-50 to-slate-50 rounded-bl-[4rem] -mr-6 -mt-6 transition-transform group-hover:scale-110 opacity-50"></div>
                            <div class="relative z-10">
                                {{-- Profil Siswa --}}
                                <div class="flex items-center gap-4 mb-6">
                                    <div class="w-14 h-14 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-600 font-black text-lg shadow-inner group-hover:bg-emerald-600 group-hover:text-white group-hover:border-emerald-500 transition-all duration-300">
                                        {{ substr($log->student->name, 0, 1) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-black text-slate-800 text-sm truncate group-hover:text-emerald-600 transition-colors">{{ $log->student->name }}</h4>
                                        <div class="flex items-center gap-2 mt-1.5">
                                            <span class="px-2 py-0.5 rounded-md bg-slate-100 border border-slate-200 text-[9px] font-bold text-slate-500 uppercase truncate max-w-[80px]">
                                                {{ $log->student->schoolClass->name ?? 'N/A' }}
                                            </span>
                                            <span class="text-[9px] text-slate-400 font-bold flex items-center gap-1 shrink-0"><i class="ph-bold ph-clock"></i> {{ $log->updated_at->format('H:i') }}</span>
                                        </div>
                                    </div>
                                </div>
                                {{-- Quick Stats Ramadhan --}}
                                <div class="flex gap-2 mb-6">
                                    <div class="flex-1 py-2.5 px-1 rounded-2xl bg-slate-50 border border-slate-100 text-center transition-colors group-hover:bg-white group-hover:shadow-sm" title="Status Puasa">
                                        <i class="ph-fill ph-bowl-food text-xl {{ $log->is_fasting ? 'text-emerald-500' : 'text-rose-400' }} mb-1.5 block"></i>
                                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-wide">Puasa</span>
                                    </div>
                                    <div class="flex-1 py-2.5 px-1 rounded-2xl bg-slate-50 border border-slate-100 text-center transition-colors group-hover:bg-white group-hover:shadow-sm" title="Shalat Wajib">
                                        <div class="flex items-center justify-center gap-0.5 text-xl font-black {{ $prayerCount == 5 ? 'text-emerald-500' : 'text-amber-500' }} mb-1.5">
                                            {{ $prayerCount }}<span class="text-[10px] text-slate-400">/5</span>
                                        </div>
                                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-wide">Wajib</span>
                                    </div>
                                    <div class="flex-1 py-2.5 px-1 rounded-2xl bg-slate-50 border border-slate-100 text-center transition-colors group-hover:bg-white group-hover:shadow-sm" title="Tilawah">
                                        <i class="ph-fill ph-book-open-text text-xl {{ $log->tadarus_surah ? 'text-blue-500' : 'text-slate-300' }} mb-1.5 block"></i>
                                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-wide">Qur'an</span>
                                    </div>
                                </div>
                                {{-- Tombol Action --}}
                                <div class="flex items-center justify-between gap-3 pt-5 border-t border-slate-50">
                                    @if($log->teacher_verified_at)
                                        <div class="flex items-center gap-1.5 text-emerald-600 text-[10px] font-black uppercase tracking-wide bg-emerald-50 px-2 py-1 rounded-lg">
                                            <i class="ph-bold ph-check-circle"></i> Nilai: {{ $log->teacher_score }}
                                        </div>
                                    @else
                                        <div class="flex items-center gap-1.5 text-amber-500 text-[10px] font-black uppercase tracking-wide bg-amber-50 px-2 py-1 rounded-lg animate-pulse">
                                            <i class="ph-bold ph-clock"></i> Menunggu
                                        </div>
                                    @endif

                                    <button @click="openFeedback({{ $log->id }}, {{ json_encode($log) }}, {{ json_encode($log->student->name) }})" 
                                        class="pl-4 pr-3 py-2 bg-slate-900 hover:bg-emerald-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-slate-200 hover:shadow-emerald-200 transition-all active:scale-95 flex items-center gap-2 group-hover:translate-x-1">
                                        Tinjau <i class="ph-bold ph-caret-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    {{-- PAGINATION --}}
                    <div class="mt-10">
                        {{ $latestLogs->links() }}
                    </div>
                @else
                    {{-- EMPTY STATE --}}
                    <div class="text-center py-32 bg-white rounded-[2.5rem] border border-slate-100 border-dashed shadow-sm">
                        <div class="w-24 h-24 bg-slate-50 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-slate-300 rotate-3 transition-transform hover:rotate-0">
                            <i class="ph-duotone ph-moon-stars text-5xl"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-800 tracking-tight">
                            {{ request('status') == 'pending' ? 'Hore! Antrean Kosong' : 'Belum Ada Aktivitas' }}
                        </h3>
                        <p class="text-slate-500 text-sm mt-2 max-w-sm mx-auto">
                            {{ request('status') == 'pending' ? 'Semua mutabaah untuk hari ini sudah kamu nilai.' : 'Tampaknya belum ada siswa yang mengisi mutabaah sesuai pencarianmu.' }}
                        </p>
                        @if(request('status') || request('search'))
                            <a href="{{ route('admin.ramadan.reports') }}" class="inline-block mt-6 px-6 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-slate-200 transition-colors">Reset Filter</a>
                        @endif
                    </div>
                @endif
            </div>
        @endif

        {{-- MODAL FEEDBACK & MOTIVASI --}}
        <div x-cloak x-show="isModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center px-4 modal-backdrop">
            {{-- Backdrop --}}
            <div x-show="isModalOpen" 
                x-transition.opacity.duration.300ms
                @click="closeModal()"
                class="absolute inset-0 bg-slate-900/80 backdrop-blur-xl"></div>

            {{-- Modal Content --}}
            <div x-show="isModalOpen" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-8"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                class="relative bg-white rounded-[2.5rem] shadow-2xl w-full max-w-3xl overflow-hidden flex flex-col max-h-[90vh] border border-white/20">
                
                {{-- Header Modal --}}
                <div class="relative bg-gradient-to-r from-emerald-600 to-teal-500 p-8 text-white shrink-0 overflow-hidden">
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white/20 rounded-full blur-2xl"></div>
                    <div class="relative z-10">
                        <h3 class="font-black text-2xl tracking-tight">Detail Mutabaah Siswa</h3>
                        <p class="text-emerald-100 text-sm font-medium mt-1 flex items-center gap-2">
                            <i class="ph-bold ph-student"></i> <span x-text="studentName"></span>
                        </p>
                    </div>
                    <button @click="closeModal()" class="absolute top-6 right-6 p-2 bg-white/20 hover:bg-white/30 rounded-xl transition text-white backdrop-blur-md"><i class="ph-bold ph-x text-lg"></i></button>
                </div>

                {{-- Body (GRID LAYOUT) --}}
                <div class="p-8 overflow-y-auto custom-scrollbar space-y-6 bg-slate-50/50">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- KOLOM KIRI: WAJIB --}}
                        <div class="space-y-6">
                            {{-- 1. Status Puasa --}}
                            <div class="bg-white p-5 rounded-[1.5rem] border border-slate-100 shadow-sm flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg" :class="details.fasting ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-500'">
                                        <i class="ph-fill" :class="details.fasting ? 'ph-check-circle' : 'ph-x-circle'"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-slate-800 text-sm">Puasa Hari Ini</h4>
                                        <p class="text-[10px] text-slate-400 font-medium" x-text="details.fasting ? 'Melaksanakan' : 'Tidak / Berhalangan'"></p>
                                    </div>
                                </div>
                            </div>

                            {{-- 2. Shalat 5 Waktu --}}
                            <div class="bg-white p-6 rounded-[1.5rem] shadow-sm border border-slate-100">
                                <h4 class="font-bold text-slate-800 text-sm mb-4 flex items-center gap-2">
                                    <i class="ph-fill ph-clock text-emerald-500"></i> Shalat Wajib
                                </h4>
                                <div class="grid grid-cols-5 gap-2">
                                    <template x-for="p in ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya']">
                                        <div class="flex flex-col items-center gap-2">
                                            <div class="w-10 h-10 rounded-full flex items-center justify-center border-2 transition-all"
                                                :class="details.prayers[p] ? 'bg-emerald-50 border-emerald-200 text-emerald-600' : 'bg-slate-50 border-slate-100 text-slate-300'">
                                                <i class="ph-fill text-lg" :class="details.prayers[p] ? 'ph-check-circle' : 'ph-circle'"></i>
                                            </div>
                                            <span class="text-[9px] font-bold uppercase text-slate-400" x-text="p"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            {{-- 3. Quran (Tilawah) --}}
                            <div class="bg-white p-6 rounded-[1.5rem] shadow-sm border border-slate-100">
                                <h4 class="font-bold text-slate-800 text-sm mb-4 flex items-center gap-2">
                                    <i class="ph-fill ph-book-open text-blue-500"></i> Tilawah & Murojaah
                                </h4>
                                <div class="space-y-3">
                                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wide mb-1">Tadarus</p>
                                        <p class="text-sm font-bold text-slate-700">
                                            <span x-text="details.tadarus_surah || '-'"></span> 
                                            <span x-show="details.tadarus_ayah" class="text-slate-400 font-medium"> : Ayat <span x-text="details.tadarus_ayah"></span></span>
                                        </p>
                                    </div>
                                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wide mb-1">Murojaah</p>
                                        <p class="text-sm font-medium text-slate-700" x-text="details.murojaah || '-'"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- KOLOM KANAN: SUNNAH & LAINNYA --}}
                        <div class="space-y-6">
                            {{-- 4. Amalan Sunnah --}}
                            <div class="bg-white p-6 rounded-[1.5rem] shadow-sm border border-slate-100 h-full">
                                <h4 class="font-bold text-slate-800 text-sm mb-4 flex items-center gap-2">
                                    <i class="ph-fill ph-star text-amber-500"></i> Amalan Sunnah
                                </h4>
                                <div class="space-y-3">
                                    <template x-for="s in ['tarawih', 'witir', 'dhuha', 'rawatib', 'sedekah']">
                                        <div class="flex items-center justify-between p-3 rounded-xl border transition-all"
                                            :class="details.sunnahs[s] ? 'bg-emerald-50/50 border-emerald-100' : 'bg-slate-50 border-slate-50 opacity-60'">
                                            <span class="text-xs font-bold text-slate-600 capitalize" x-text="s"></span>
                                            <i class="ph-fill text-lg" :class="details.sunnahs[s] ? 'ph-check-circle text-emerald-600' : 'ph-circle text-slate-300'"></i>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 5. Laporan Jumat --}}
                    <template x-if="details.khotib">
                        <div class="bg-emerald-50/50 p-6 rounded-[1.5rem] border border-emerald-100">
                            <h4 class="font-bold text-emerald-800 text-sm mb-3 flex items-center gap-2">
                                <i class="ph-fill ph-mosque text-emerald-600"></i> Laporan Jumat
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <p class="text-[10px] text-emerald-600 font-bold uppercase tracking-wide mb-1">Khotib</p>
                                    <p class="text-sm font-bold text-slate-700 bg-white p-2 rounded-lg border border-emerald-100" x-text="details.khotib"></p>
                                </div>
                                <div class="md:col-span-2">
                                    <p class="text-[10px] text-emerald-600 font-bold uppercase tracking-wide mb-1">Ringkasan</p>
                                    <p class="text-sm text-slate-600 italic bg-white p-3 rounded-lg border border-emerald-100 leading-relaxed" x-text="details.summary"></p>
                                </div>
                            </div>
                        </div>
                    </template>

                    {{-- 6. LAPORAN KULTUM (BARU) --}}
                    <template x-if="details.kultum_summary || details.kultum_penceramah">
                        <div class="bg-purple-50/50 p-6 rounded-[1.5rem] border border-purple-100">
                            <h4 class="font-bold text-purple-800 text-sm mb-3 flex items-center gap-2">
                                <i class="ph-fill ph-microphone-stage text-purple-600"></i> Laporan Kultum
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <p class="text-[10px] text-purple-600 font-bold uppercase tracking-wide mb-1">Penceramah</p>
                                    <p class="text-sm font-bold text-slate-700 bg-white p-2 rounded-lg border border-purple-100" x-text="details.kultum_penceramah || '-'"></p>
                                </div>
                                <div class="md:col-span-2">
                                    <p class="text-[10px] text-purple-600 font-bold uppercase tracking-wide mb-1">Ringkasan Materi</p>
                                    <p class="text-sm text-slate-600 italic bg-white p-3 rounded-lg border border-purple-100 leading-relaxed" x-text="details.kultum_summary || '-'"></p>
                                </div>
                            </div>
                        </div>
                    </template>

                    {{-- FORM INPUT GURU --}}
                    <form :action="formAction" method="POST" id="gradingForm" class="space-y-5 pt-4 border-t border-slate-200 border-dashed">
                        @csrf
                        <div class="flex flex-col md:flex-row gap-5">
                            <div class="w-full md:w-1/3">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block ml-1">Nilai (0-100)</label>
                                <div class="flex items-center gap-2">
                                    <input type="number" name="teacher_score" x-model="currentScore" min="0" max="100" class="w-full pl-4 pr-2 py-3 rounded-2xl border-slate-200 font-bold focus:ring-emerald-500 focus:border-emerald-500 text-xl text-slate-800 shadow-sm text-center" placeholder="0">
                                    <div class="flex flex-col gap-1">
                                        <button type="button" @click="setScore(100)" class="px-2 py-1 rounded-lg bg-emerald-100 text-emerald-700 text-[10px] font-bold hover:bg-emerald-200">100</button>
                                        <button type="button" @click="setScore(80)" class="px-2 py-1 rounded-lg bg-slate-100 text-slate-600 text-[10px] font-bold hover:bg-slate-200">80</button>
                                    </div>
                                </div>
                                @error('teacher_score')
                                    <p class="text-rose-500 text-[10px] mt-1 font-bold">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="w-full md:w-2/3">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block ml-1">Catatan / Motivasi</label>
                                <textarea name="teacher_note" x-model="currentNote" rows="2" class="w-full p-3 rounded-2xl border-slate-200 text-sm font-medium focus:ring-emerald-500 focus:border-emerald-500 leading-relaxed shadow-sm placeholder:text-slate-300 resize-none" placeholder="Berikan semangat..."></textarea>
                                @error('teacher_note')
                                    <p class="text-rose-500 text-[10px] mt-1 font-bold">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Footer Modal --}}
                <div class="p-6 border-t border-slate-100 bg-white flex justify-end gap-3 shrink-0">
                    <button @click="closeModal()" class="px-6 py-3 rounded-xl font-bold text-slate-500 hover:bg-slate-50 transition text-xs uppercase tracking-widest">Batal</button>
                    <button type="submit" form="gradingForm" class="px-8 py-3 rounded-xl font-black text-white bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 transition text-xs uppercase tracking-widest shadow-lg shadow-emerald-500/20 flex items-center gap-2 transform active:scale-95">
                        <i class="ph-bold ph-paper-plane-right text-lg"></i> Simpan Penilaian
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>