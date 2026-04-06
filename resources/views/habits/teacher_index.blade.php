<x-app-layout>
    {{-- IMPORT FONT & CUSTOM STYLES --}}
    <style>
        @import url('https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap');

        .font-jakarta {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

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

        .page-container {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }
    </style>

    <div class="page-container p-4 md:p-8 space-y-8 min-h-screen bg-slate-50 font-jakarta">
        
        {{-- HERO SECTION --}}
        <div class="animate-enter relative rounded-[3rem] bg-gradient-to-br from-blue-900 via-blue-800 to-slate-900 p-8 md:p-12 text-white shadow-2xl shadow-blue-900/30 overflow-hidden group border border-white/10">
            {{-- Decorative Background Elements --}}
            <div class="absolute top-0 right-0 -mt-20 -mr-20 w-[500px] h-[500px] bg-blue-500/20 rounded-full blur-[100px] group-hover:opacity-40 transition-opacity duration-1000"></div>
            <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-[400px] h-[400px] bg-indigo-500/10 rounded-full blur-[80px]"></div>
            
            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-10">
                <div class="flex-1">
                    <div class="flex flex-wrap gap-3 mb-6 mx-auto xl:mx-0">
                        <a href="{{ route('dashboard') }}" class="group bg-white/10 hover:bg-white/20 text-white px-5 py-3 rounded-2xl font-bold text-sm backdrop-blur-sm border border-white/10 transition-all flex items-center gap-2 shadow-sm w-fit">
                            <i class="ph-bold ph-arrow-left text-lg group-hover:-translate-x-1 transition-transform"></i>
                            <span>Kembali ke Dashboard</span>
                        </a>
                        {{-- TOMBOL MENUJU LEADERBOARD --}}
                        <a href="{{ route('teacher.habits.leaderboard') }}" class="group bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 text-white px-5 py-3 rounded-2xl font-bold text-sm backdrop-blur-sm border border-white/10 transition-all flex items-center gap-2 shadow-sm w-fit">
                            <i class="ph-fill ph-trophy text-lg group-hover:scale-110 transition-transform"></i>
                            <span>Siswa Terajin</span>
                        </a>
                    </div>
                    
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 border border-white/10 text-blue-200 text-[10px] font-black uppercase tracking-[0.2em] mb-6 backdrop-blur-md shadow-inner">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-400"></span>
                        </span>
                        Sistem Monitoring Karakter
                    </div>
                    <h1 class="text-4xl md:text-6xl font-black text-white tracking-tighter mb-4 leading-none">
                        Pantau <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-300 to-cyan-200">Kebiasaan</span> Siswa
                    </h1>
                    <p class="text-blue-100/60 text-sm md:text-lg max-w-xl leading-relaxed font-medium">
                        Kelola dan tinjau perkembangan karakter siswa secara real-time. Berikan apresiasi terbaik untuk setiap langkah kecil mereka.
                    </p>
                </div>

                {{-- FILTER FORM & PRINT BUTTON --}}
                <div class="w-full lg:w-auto shrink-0 flex flex-col gap-4">
                    
                    <form id="filterForm" action="{{ route('teacher.habits.index') }}" method="GET" class="bg-white/5 backdrop-blur-xl p-6 rounded-[2rem] border border-white/10 shadow-2xl flex flex-col gap-5 relative">
                        <div id="formLoading" class="hidden absolute inset-0 bg-slate-900/40 backdrop-blur-[4px] z-10 rounded-[2rem] flex items-center justify-center">
                            <i class="ph-bold ph-circle-notch animate-spin text-blue-400 text-3xl"></i>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-blue-200 uppercase tracking-widest ml-1 block">Periode</label>
                                <div class="relative group">
                                    <i class="ph-bold ph-calendar absolute left-4 top-1/2 -translate-y-1/2 text-blue-400"></i>
                                    <input type="date" id="filterDate" name="date" value="{{ $date }}" 
                                        class="block w-full pl-11 pr-4 py-3 bg-white/10 border-white/10 rounded-2xl text-xs font-bold text-white focus:ring-blue-500 focus:border-blue-500 transition-all uppercase" 
                                        onchange="submitFilter()">
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-blue-200 uppercase tracking-widest ml-1 block">Kelas</label>
                                <div class="relative group">
                                    <i class="ph-bold ph-users-three absolute left-4 top-1/2 -translate-y-1/2 text-blue-400"></i>
                                    <select id="filterClass" name="class_id" 
                                        class="block w-full pl-11 pr-10 py-3 bg-white/10 border-white/10 rounded-2xl text-xs font-bold text-white focus:ring-blue-500 focus:border-blue-500 transition-all appearance-none" 
                                        onchange="submitFilter()">
                                        <option value="" class="bg-slate-900 text-white">Pilih Kelas</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}" {{ $classId == $class->id ? 'selected' : '' }} class="bg-slate-900 text-white">{{ $class->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>

                    {{-- TOMBOL ACTION GRID --}}
                    @if($classId)
                        <div class="grid grid-cols-2 gap-3">
                            {{-- Tombol Rekap --}}
                            <button onclick="openRecap()" 
                                class="group w-full py-4 bg-white hover:bg-indigo-50 text-slate-600 hover:text-indigo-600 rounded-[1.5rem] font-bold shadow-lg shadow-slate-200/50 flex flex-col items-center justify-center gap-2 transition-all active:scale-95 border border-white/50">
                                <i class="ph-duotone ph-list-numbers text-2xl mb-1 group-hover:scale-110 transition-transform"></i>
                                <span class="uppercase tracking-wider text-[9px] font-black">Lihat Rekap</span>
                            </button>

                            {{-- Tombol Cetak --}}
                            <button onclick="printReport()" 
                                class="group w-full py-4 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-white rounded-[1.5rem] font-bold shadow-lg shadow-emerald-500/20 flex flex-col items-center justify-center gap-2 transition-all active:scale-95 border border-white/10">
                                <i class="ph-bold ph-printer text-2xl mb-1 group-hover:rotate-12 transition-transform"></i>
                                <span class="uppercase tracking-wider text-[9px] font-black">Cetak PDF</span>
                            </button>
                        </div>
                    @endif
                </div>    
            </div>
        </div>

        {{-- === BAGIAN 1: STATISTIK CARD (SELALU MUNCUL) === --}}
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 animate-enter" style="animation-delay: 100ms">
            {{-- Sudah Lapor Card --}}
            <div class="glass-card p-8 rounded-[2.5rem] shadow-sm flex items-center gap-6 group hover:border-emerald-200 transition-all duration-300">
                <div class="w-16 h-16 rounded-[1.5rem] bg-emerald-50 text-emerald-600 flex items-center justify-center text-3xl group-hover:scale-110 transition-transform shadow-inner">
                    <i class="ph-fill ph-shield-check"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Sudah Melapor</p>
                    <p class="text-4xl font-black text-slate-800 tracking-tighter">
                        {{ $stats['submitted'] ?? 0 }} <span class="text-sm font-bold text-slate-400">SISWA</span>
                    </p>
                    @if(!$classId)
                        <span class="text-[9px] text-emerald-600 font-bold bg-emerald-50 px-2 py-0.5 rounded-md">Total Sekolah</span>
                    @endif
                </div>
            </div>

            {{-- Belum Lapor Card --}}
            <div class="glass-card p-8 rounded-[2.5rem] shadow-sm flex items-center gap-6 group hover:border-rose-200 transition-all duration-300">
                <div class="w-16 h-16 rounded-[1.5rem] bg-rose-50 text-rose-500 flex items-center justify-center text-3xl group-hover:scale-110 transition-transform shadow-inner">
                    <i class="ph-fill ph-clock-countdown"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Belum Melapor</p>
                    <p class="text-4xl font-black text-slate-800 tracking-tighter">
                        {{ $stats['missing'] ?? 0 }} <span class="text-sm font-bold text-slate-400">SISWA</span>
                    </p>
                </div>
            </div>

            {{-- Partisipasi Card --}}
            <div class="bg-slate-900 p-8 rounded-[2.5rem] shadow-2xl shadow-blue-900/20 flex items-center gap-6 group hover:bg-slate-800 transition-all duration-300 border border-white/5 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-blue-500/10 rounded-full blur-3xl"></div>
                <div class="w-16 h-16 rounded-[1.5rem] bg-white/10 text-blue-400 flex items-center justify-center text-3xl group-hover:rotate-12 transition-transform shadow-inner">
                    <i class="ph-fill ph-chart-line-up"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-blue-300/60 uppercase tracking-widest mb-1">Tingkat Partisipasi</p>
                    <p class="text-4xl font-black text-white tracking-tighter">{{ $stats['percentage'] ?? 0 }}%</p>
                </div>
            </div>
        </div>


        {{-- === BAGIAN 2: KONTEN UTAMA === --}}
        
        @if($classId)
            {{-- === JIKA KELAS DIPILIH: TAMPILKAN TABEL DAFTAR SISWA === --}}
            <div class="animate-enter bg-white rounded-[3rem] shadow-sm border border-slate-100 overflow-hidden mb-12" style="animation-delay: 200ms">
                <div class="px-8 py-6 border-b border-slate-50 flex flex-col xl:flex-row xl:items-center justify-between gap-4">
                    <h2 class="text-xl font-black text-slate-800 flex items-center gap-3">
                        <i class="ph-bold ph-list-checks text-blue-600"></i> 
                        Status Monitoring Harian
                    </h2>
                    
                    <div class="flex flex-col md:flex-row items-center gap-3 w-full xl:w-auto">
                        
                        {{-- FILTER STATUS BARU --}}
                        <div class="relative w-full md:w-48 shrink-0">
                            <select id="statusFilter" onchange="searchTable()" class="w-full pl-4 pr-10 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 focus:ring-blue-500 focus:border-blue-500 transition-all appearance-none cursor-pointer">
                                <option value="all">Semua Status</option>
                                <option value="waiting">⏳ Menunggu Dinilai</option>
                                <option value="graded">✅ Sudah Dinilai</option>
                                <option value="missing">❌ Belum Lapor</option>
                            </select>
                            <i class="ph-bold ph-caret-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                        </div>

                        {{-- Kolom Pencarian Cepat --}}
                        <div class="relative w-full md:w-64">
                            <i class="ph-bold ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Cari nama siswa..." 
                                class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 focus:ring-blue-500 focus:border-blue-500 transition-all placeholder:text-slate-400">
                        </div>

                        <div class="flex gap-2 w-full md:w-auto shrink-0">
                            <span class="px-4 py-2 rounded-xl bg-blue-50 text-blue-600 text-[10px] font-black uppercase tracking-wider flex-1 text-center whitespace-nowrap">
                                Kelas: {{ $classes->find($classId)->name ?? '-' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left" id="studentsTable">
                        <thead class="bg-slate-50/50 text-slate-400 uppercase text-[9px] font-black tracking-[0.2em] border-b border-slate-100">
                            <tr>
                                <th class="px-10 py-6">Profil Siswa</th>
                                <th class="px-6 py-6 text-center">Status Jurnal</th>
                                <th class="px-6 py-6 text-center">Waktu Masuk</th>
                                <th class="px-6 py-6 text-center">Makan (MBG)</th>
                                <th class="px-10 py-6 text-right">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($students as $student)
                                @php
                                    // Menentukan Status Row untuk fitur Filter JavaScript
                                    $rowStatus = 'missing';
                                    if ($student->habit_status == 'submitted') {
                                        $rowStatus = ($student->habit_data && $student->habit_data->teacher_feedback) ? 'graded' : 'waiting';
                                    }
                                @endphp

                                <tr class="hover:bg-blue-50/30 transition-all group student-row" data-status="{{ $rowStatus }}">
                                    <td class="px-10 py-5">
                                        <div class="flex items-center gap-5">
                                            <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-600 font-black text-sm border border-slate-200 group-hover:bg-blue-600 group-hover:text-white group-hover:border-blue-500 transition-all duration-300">
                                                {{ substr($student->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="student-name font-black text-slate-800 group-hover:text-blue-600 transition-colors uppercase tracking-tight text-sm">{{ $student->name }}</div>
                                                <div class="text-[9px] text-slate-400 font-bold tracking-widest uppercase mt-0.5">{{ $student->student_id ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        @if($student->habit_status == 'submitted')
                                            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-50 text-emerald-600 text-[9px] font-black uppercase tracking-widest border border-emerald-100">
                                                <i class="ph-fill ph-check-circle text-xs"></i> Sudah Lapor
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-slate-100 text-slate-400 text-[9px] font-black uppercase tracking-widest border border-slate-200">
                                                <i class="ph-bold ph-warning-circle text-xs"></i> Belum Ada
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        @if($student->habit_data)
                                            <div class="flex items-center justify-center gap-2 text-slate-600 font-black text-xs">
                                                <i class="ph-bold ph-timer text-blue-500 text-sm"></i>
                                                {{ $student->habit_data->created_at->format('H:i') }}
                                            </div>
                                        @else
                                            <span class="text-slate-300 text-xs font-bold italic tracking-tighter">MENUNGGU...</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        @if($student->habit_data && $student->habit_data->habit_5) 
                                            <span class="inline-flex w-8 h-8 items-center justify-center rounded-full bg-orange-100 text-orange-600 shadow-sm" title="Sudah Mengambil Makan">
                                                <i class="ph-fill ph-check font-bold"></i>
                                            </span>
                                        @else
                                            <span class="inline-flex w-8 h-8 items-center justify-center rounded-full bg-slate-50 text-slate-300 border border-slate-100" title="Belum Mengambil">
                                                <i class="ph-bold ph-minus"></i>
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-10 py-5 text-right">
                                        @if($student->habit_data)
                                            <div class="flex items-center justify-end gap-2">
                                                <div id="badge-feedback-{{ $student->habit_data->id }}" class="mr-2 hidden md:block">
                                                    @if($student->habit_data->teacher_feedback)
                                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-emerald-50 border border-emerald-100 text-emerald-600 text-[10px] font-black uppercase tracking-wider">
                                                            <i class="ph-fill ph-check-circle"></i> Dinilai
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-amber-50 border border-amber-100 text-amber-600 text-[10px] font-black uppercase tracking-wider animate-pulse">
                                                            <i class="ph-bold ph-warning-circle"></i> Menunggu
                                                        </span>
                                                    @endif
                                                </div>

                                                <button onclick="openDetail({{ $student->habit_data->id }})" 
                                                    class="inline-flex items-center gap-2 text-blue-600 hover:text-white font-black text-[9px] uppercase tracking-[0.1em] bg-blue-50 hover:bg-blue-600 px-6 py-3 rounded-2xl transition-all active:scale-90 border border-blue-100 shadow-sm">
                                                    <i class="ph-bold ph-notebook text-sm"></i> Tinjau Laporan
                                                </button>
                                            </div>
                                        @else
                                            <span class="text-slate-300 text-[9px] font-black uppercase tracking-widest italic opacity-50">Laporan Kosong</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-24 text-center">
                                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                                            <i class="ph-duotone ph-users-three text-3xl"></i>
                                        </div>
                                        <p class="text-slate-400 font-black uppercase tracking-widest text-[10px] italic">Tidak ada data siswa yang ditemukan</p>
                                    </td>
                                </tr>
                            @endforelse
                            <tr id="noResultsRow" class="hidden">
                                <td colspan="5" class="px-8 py-16 text-center text-slate-400 text-sm italic">
                                    Siswa dengan status/nama tersebut tidak ditemukan.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        @else
            {{-- === JIKA BELUM PILIH KELAS: TAMPILKAN FEED AKTIVITAS === --}}
            <div class="animate-enter space-y-6" style="animation-delay: 100ms">
                
                {{-- Header Feed --}}
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-2">
                    <div>
                        <h3 class="text-2xl font-black text-slate-800 tracking-tight flex items-center gap-2">
                            <i class="ph-duotone ph-lightning text-yellow-500"></i>
                            Aktivitas Masuk Terbaru
                        </h3>
                        <p class="text-slate-500 text-sm font-medium mt-1">Daftar siswa yang baru saja mengirimkan laporan kebiasaan hari ini.</p>
                    </div>
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-black text-slate-600 uppercase tracking-widest shadow-sm">
                        <i class="ph-bold ph-calendar-check text-blue-500"></i>
                        {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}
                    </div>
                </div>

                {{-- Grid Card Siswa --}}
                @if(isset($latestSubmissions) && $latestSubmissions->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        @foreach($latestSubmissions as $submission)
                        <div class="group bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-blue-900/5 hover:border-blue-200 transition-all duration-300 relative overflow-hidden">
                            
                            {{-- Dekorasi Background --}}
                            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-50 to-slate-50 rounded-bl-[4rem] -mr-6 -mt-6 transition-transform group-hover:scale-110 opacity-50"></div>

                            <div class="relative z-10">
                                {{-- Profil Siswa --}}
                                <div class="flex items-center gap-4 mb-6">
                                    <div class="w-14 h-14 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-600 font-black text-lg shadow-inner group-hover:bg-blue-600 group-hover:text-white group-hover:border-blue-500 transition-all duration-300">
                                        {{ substr($submission->student->name, 0, 1) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-black text-slate-800 text-sm truncate group-hover:text-blue-600 transition-colors">
                                            {{ $submission->student->name }}
                                        </h4>
                                        <div class="flex items-center gap-2 mt-1.5">
                                            <span class="px-2 py-0.5 rounded-md bg-slate-100 border border-slate-200 text-[9px] font-bold text-slate-500 uppercase truncate max-w-[80px]">
                                                {{ $submission->student->schoolClass->name ?? 'N/A' }}
                                            </span>
                                            <span class="text-[9px] text-slate-400 font-bold flex items-center gap-1 shrink-0">
                                                <i class="ph-bold ph-clock"></i> {{ $submission->updated_at->format('H:i') }} WIB
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Quick Stats --}}
                                <div class="flex gap-2 mb-6">
                                    <div class="flex-1 py-2.5 px-1 rounded-2xl bg-slate-50 border border-slate-100 text-center transition-colors group-hover:bg-white group-hover:shadow-sm" title="Status Shalat">
                                        @php $isPrayerDone = $submission->prayer_subuh || $submission->prayer_dzuhur || $submission->prayer_ashar || $submission->prayer_maghrib || $submission->prayer_isya || $submission->is_udzur_syar_i; @endphp
                                        <i class="ph-fill ph-mosque text-xl {{ $isPrayerDone ? 'text-emerald-500' : 'text-slate-300' }} mb-1.5 block"></i>
                                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-wide">Ibadah</span>
                                    </div>
                                    <div class="flex-1 py-2.5 px-1 rounded-2xl bg-slate-50 border border-slate-100 text-center transition-colors group-hover:bg-white group-hover:shadow-sm" title="One Day One Ayat">
                                        <i class="ph-fill ph-microphone-stage text-xl {{ $submission->odoa_audio_path ? 'text-blue-500' : 'text-slate-300' }} mb-1.5 block"></i>
                                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-wide">Odoa</span>
                                    </div>
                                    <div class="flex-1 py-2.5 px-1 rounded-2xl bg-slate-50 border border-slate-100 text-center transition-colors group-hover:bg-white group-hover:shadow-sm" title="Makan Bergizi">
                                        <i class="ph-fill ph-carrot text-xl {{ $submission->habit_5 ? 'text-orange-500' : 'text-slate-300' }} mb-1.5 block"></i>
                                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-wide">Gizi</span>
                                    </div>
                                </div>

                                {{-- Tombol Action --}}
                                <div class="flex items-center justify-between gap-3 pt-5 border-t border-slate-50">
                                    
                                    <!-- WRAPPER BADGE FEEDBACK -->
                                    <div id="badge-feedback-{{ $submission->id }}">
                                        @if($submission->teacher_feedback)
                                            <div class="flex items-center gap-1.5 text-emerald-600 text-[10px] font-black uppercase tracking-wide bg-emerald-50 px-2 py-1 rounded-lg">
                                                <i class="ph-bold ph-check-circle"></i> Dinilai
                                            </div>
                                        @else
                                            <div class="flex items-center gap-1.5 text-amber-500 text-[10px] font-black uppercase tracking-wide bg-amber-50 px-2 py-1 rounded-lg animate-pulse">
                                                <i class="ph-bold ph-clock"></i> Menunggu
                                            </div>
                                        @endif
                                    </div>

                                    <button onclick="openDetail({{ $submission->id }})" 
                                        class="pl-4 pr-3 py-2 bg-slate-900 hover:bg-blue-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-slate-200 hover:shadow-blue-200 transition-all active:scale-95 flex items-center gap-2 group-hover:translate-x-1">
                                        Tinjau <i class="ph-bold ph-caret-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    {{-- Empty State jika Feed Kosong --}}
                    <div class="text-center py-32 bg-white rounded-[2.5rem] border border-slate-100 border-dashed shadow-sm">
                        <div class="w-24 h-24 bg-slate-50 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-slate-300 rotate-3 transition-transform hover:rotate-0">
                            <i class="ph-duotone ph-coffee text-5xl"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-800 tracking-tight">Belum Ada Aktivitas Hari Ini</h3>
                        <p class="text-slate-500 text-sm mt-2 max-w-sm mx-auto">Tampaknya belum ada siswa yang mengisi jurnal monitoring pada tanggal ini.</p>
                    </div>
                @endif
            </div>
        @endif

    </div>

    {{-- MODAL DETAIL --}}
    <div id="detailModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-xl transition-opacity duration-500" onclick="closeDetail()"></div>
        <div class="flex items-center justify-center min-h-screen p-4 sm:p-6">
            <div class="bg-white rounded-[3.5rem] w-full max-w-2xl shadow-2xl relative transform transition-all overflow-hidden border border-white/20">
                <div class="h-2 bg-gradient-to-r from-blue-600 via-cyan-400 to-indigo-600"></div>
                <button onclick="closeDetail()" class="absolute top-8 right-8 z-10 text-slate-400 hover:text-rose-500 p-3 rounded-2xl hover:bg-rose-50 transition-all active:scale-90"><i class="ph-bold ph-x text-2xl"></i></button>
                <div id="modalContent" class="p-10 md:p-14 font-jakarta"></div>
            </div>
        </div>
    </div>
    
    {{-- MODAL REKAP --}}
    <div id="recapModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-xl transition-opacity duration-500" onclick="closeRecap()"></div>
        <div class="flex items-center justify-center min-h-screen p-4 sm:p-6">
            <div class="bg-white rounded-[3rem] w-full max-w-4xl shadow-2xl relative transform transition-all overflow-hidden border border-white/20 flex flex-col max-h-[90vh]">
                <div class="p-8 border-b border-slate-100 flex items-center justify-between bg-white z-10">
                    <div>
                        <h3 class="text-2xl font-black text-slate-800 flex items-center gap-2"><i class="ph-duotone ph-list-numbers text-indigo-600"></i> Rekapitulasi Kelas</h3>
                        <p class="text-sm text-slate-500 font-medium">Data rekap siswa per {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</p>
                    </div>
                    <div class="flex gap-2">
                         <button onclick="copyRecap()" class="px-6 py-3 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl font-bold text-xs uppercase tracking-widest shadow-lg shadow-emerald-500/20 flex items-center gap-2 transition-all active:scale-95"><i class="ph-bold ph-whatsapp-logo text-lg"></i> Salin WhatsApp</button>
                        <button onclick="closeRecap()" class="p-3 bg-slate-100 hover:bg-slate-200 text-slate-500 rounded-xl transition-colors"><i class="ph-bold ph-x text-lg"></i></button>
                    </div>
                </div>

                <div class="p-8 overflow-y-auto custom-scrollbar bg-slate-50">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="bg-white p-6 rounded-[2rem] border border-emerald-100 shadow-sm">
                            <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-50">
                                <h4 class="font-black text-emerald-600 uppercase tracking-widest text-xs flex items-center gap-2"><i class="ph-fill ph-check-circle text-lg"></i> Sudah Lapor</h4>
                                <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-lg text-xs font-bold">{{ $stats['submitted'] ?? 0 }} Siswa</span>
                            </div>
                            <ol class="list-decimal list-inside space-y-2 text-sm font-medium text-slate-600 marker:font-bold marker:text-emerald-300">
                                @forelse($students->where('habit_status', 'submitted') as $s) <li class="pl-2">{{ $s->name }}</li>
                                @empty <p class="text-slate-400 italic text-center py-4 text-xs">Belum ada siswa yang melapor.</p> @endforelse
                            </ol>
                        </div>
                        <div class="bg-white p-6 rounded-[2rem] border border-rose-100 shadow-sm">
                            <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-50">
                                <h4 class="font-black text-rose-500 uppercase tracking-widest text-xs flex items-center gap-2"><i class="ph-fill ph-x-circle text-lg"></i> Belum Lapor</h4>
                                <span class="bg-rose-100 text-rose-700 px-3 py-1 rounded-lg text-xs font-bold">{{ $stats['missing'] ?? 0 }} Siswa</span>
                            </div>
                            <ol class="list-decimal list-inside space-y-2 text-sm font-medium text-slate-600 marker:font-bold marker:text-rose-300">
                                @forelse($students->where('habit_status', 'missing') as $s) <li class="pl-2">{{ $s->name }}</li>
                                @empty <p class="text-slate-400 italic text-center py-4 text-xs">Semua siswa sudah melapor!</p> @endforelse
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($classId)
    <textarea id="recapText" class="hidden">
*LAPORAN MONITORING KEBIASAAN BAIK*
Kelas: {{ $classes->find($classId)->name ?? '-' }}
Tanggal: {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}

✅ *SUDAH LAPOR ({{ $stats['submitted'] ?? 0 }}):*
@foreach($students->where('habit_status', 'submitted') as $index => $s)
{{ $loop->iteration }}. {{ $s->name }}
@endforeach

❌ *BELUM LAPOR ({{ $stats['missing'] ?? 0 }}):*
@foreach($students->where('habit_status', 'missing') as $index => $s)
{{ $loop->iteration }}. {{ $s->name }}
@endforeach

_Mohon segera mengisi jurnal kebiasaan baik._
Terima kasih. 🙏
    </textarea>
    @endif

    {{-- SWEETALERT --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // 1. FUNGSI FILTER & MODAL DASAR
        function submitFilter() {
            document.getElementById('formLoading').classList.remove('hidden');
            document.getElementById('filterForm').submit();
        }

        function openDetail(id) {
            const modal = document.getElementById('detailModal');
            const content = document.getElementById('modalContent');
            
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            content.innerHTML = `<div class="flex flex-col items-center justify-center py-32"><div class="relative"><div class="w-20 h-20 border-4 border-blue-100 rounded-full"></div><div class="w-20 h-20 border-4 border-blue-600 border-t-transparent rounded-full animate-spin absolute top-0 left-0"></div></div><p class="mt-8 text-slate-400 text-[10px] font-black uppercase tracking-[0.4em]">Mengambil Jurnal...</p></div>`;
            
            fetch(`{{ url('/teacher/habits/detail') }}/${id}`)
                .then(response => { if (!response.ok) throw new Error('Network error'); return response.text(); })
                .then(html => { content.innerHTML = html; })
                .catch(err => { content.innerHTML = `<div class="text-center py-24"><div class="w-20 h-20 bg-rose-50 text-rose-500 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-inner"><i class="ph-bold ph-warning-circle text-4xl"></i></div><h3 class="text-xl font-black text-slate-800">Gagal Memuat Jurnal</h3><p class="text-slate-500 text-sm mb-10 font-medium">Koneksi bermasalah.</p><button onclick="openDetail(${id})" class="bg-blue-600 text-white px-10 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest active:scale-95">Muat Ulang</button></div>`; });
        }

        function closeDetail() { 
            document.getElementById('detailModal').classList.add('hidden'); 
            document.body.style.overflow = 'auto'; 
            
            // Hentikan pemutaran audio jika modal ditutup (Mencegah bug suara)
            const content = document.getElementById('modalContent');
            if(content) {
                const audios = content.getElementsByTagName('audio');
                for(let i=0; i<audios.length; i++) {
                    audios[i].pause();
                }
            }
        }

        function printReport() {
            const date = document.getElementById('filterDate').value;
            const classId = document.getElementById('filterClass').value;
            if (!classId) { alert('Silakan pilih kelas terlebih dahulu.'); return; }
            window.open(`{{ route('teacher.habits.print') }}?date=${date}&class_id=${classId}`, '_blank');
        }

        function openRecap() { document.getElementById('recapModal').classList.remove('hidden'); document.body.style.overflow = 'hidden'; }
        function closeRecap() { document.getElementById('recapModal').classList.add('hidden'); document.body.style.overflow = 'auto'; }
        
        function copyRecap() {
            const text = document.getElementById('recapText').value;
            navigator.clipboard.writeText(text).then(() => { alert('Rekap berhasil disalin ke Clipboard!'); }).catch(err => { console.error(err); alert('Gagal menyalin.'); });
        }

        // 2. FITUR DIPERBARUI: PENCARIAN & FILTER STATUS DI TABEL (JS MURNI)
        function searchTable() {
            const input = document.getElementById("searchInput").value.toLowerCase();
            const statusFilter = document.getElementById("statusFilter").value; // Ambil nilai dropdown status
            
            const rows = document.querySelectorAll(".student-row");
            let hasResults = false;

            rows.forEach(row => {
                const nameText = row.querySelector(".student-name").innerText.toLowerCase();
                const rowStatus = row.getAttribute("data-status"); // Ambil status dari atribut baris

                // Cek apakah teks cocok
                const matchName = nameText.includes(input);
                
                // Cek apakah status cocok ('all' berarti tampilkan semua)
                const matchStatus = (statusFilter === 'all' || rowStatus === statusFilter);

                if (matchName && matchStatus) {
                    row.style.display = "";
                    hasResults = true;
                } else {
                    row.style.display = "none";
                }
            });

            // Tampilkan pesan "Tidak Ditemukan" jika pencarian/filter kosong
            const noResultsRow = document.getElementById("noResultsRow");
            if(noResultsRow) {
                noResultsRow.style.display = hasResults ? "none" : "";
            }
        }

        // 3. FITUR AJAX SUBMIT FEEDBACK (DIPERBARUI)
        function submitFeedbackAjax(event, formElement) {
            event.preventDefault(); // Mencegah reload halaman
            
            const url = formElement.action;
            const formData = new FormData(formElement);
            const btnSubmit = formElement.querySelector('#btn-submit-feedback');
            const originalText = btnSubmit.innerHTML;
            
            // Ekstrak ID habit dari URL action form
            const urlParts = url.split('/');
            const habitId = urlParts[urlParts.length - 2]; 

            // Ubah status tombol loading
            btnSubmit.disabled = true;
            btnSubmit.innerHTML = '<i class="ph-bold ph-spinner animate-spin"></i> Menyimpan...';
            btnSubmit.classList.add('opacity-70');

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json(); 
            })
            .then(data => {
                Swal.fire({
                    icon: 'success', 
                    title: 'Berhasil!', 
                    text: 'Feedback/Apresiasi berhasil tersimpan.',
                    toast: true, 
                    position: 'top-end', 
                    showConfirmButton: false, 
                    timer: 3000,
                    customClass: { popup: 'rounded-xl shadow-lg border border-emerald-100 bg-white' }
                });

                btnSubmit.innerHTML = '<i class="ph-bold ph-check"></i> Perbarui Feedback';

                // ==========================================
                // UPDATE UI BADGE & DATA-STATUS (REAL-TIME)
                // ==========================================
                const badgeElement = document.getElementById('badge-feedback-' + habitId);
                if (badgeElement) {
                    // Update tampilan badge
                    badgeElement.innerHTML = `
                        <div class="flex items-center gap-1.5 text-emerald-600 text-[10px] font-black uppercase tracking-wide bg-emerald-50 px-2 py-1 rounded-lg">
                            <i class="ph-bold ph-check-circle"></i> Dinilai
                        </div>
                    `;
                    
                    // UPDATE JUGA ATRIBUT PADA BARIS TABEL AGAR FILTER TETAP BEKERJA
                    const tableRow = badgeElement.closest('tr.student-row');
                    if (tableRow) {
                        tableRow.setAttribute('data-status', 'graded');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error', 
                    title: 'Oops...', 
                    text: 'Terjadi kesalahan saat menyimpan feedback.',
                });
                btnSubmit.innerHTML = originalText;
            })
            .finally(() => {
                btnSubmit.disabled = false;
                btnSubmit.classList.remove('opacity-70');
            });
        }
    </script>
</x-app-layout>