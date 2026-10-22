<x-app-layout>
    {{-- LIBRARIES --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    @push('styles')
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        
        /* =========================================================
           PERBAIKAN KAMERA RESPONSIVE & ANTI GEPENG (MOBILE FIX)
           ========================================================= */
        #reader { 
            width: 100% !important; 
            min-height: 320px !important; 
            border: none !important; 
            border-radius: 1.25rem;
            overflow: hidden; 
            position: relative; 
            background: #021124; 
        }
        
        #reader__scan_region { 
            width: 100% !important; 
            height: 100% !important;
            min-height: 320px !important;
            background: transparent !important; 
        }

        #reader video { 
            width: 100% !important; 
            height: 100% !important; 
            min-height: 320px !important;
            object-fit: cover !important;
            display: block !important;
            border-radius: 1.25rem;
        }
        
        #reader canvas { 
            display: none !important;
        }
        
        #reader__dashboard_section_csr span, 
        #reader__dashboard_section_swaplink,
        #reader__status_span,
        #reader__header_message { 
            display: none !important; 
        }
        
        .digital-clock { font-feature-settings: "tnum"; font-variant-numeric: tabular-nums; }
    </style>
    @endpush

    <div class="py-8 sm:py-10 relative min-h-screen font-sans text-white bg-elevate-surface pb-32 overflow-hidden">
        
        {{-- Efek Latar Belakang Halus --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        {{-- Indikator Offline --}}
        <div id="offlineIndicator" class="fixed bottom-6 right-6 z-50 hidden animate-bounce">
            <div class="bg-rose-600 text-white px-5 py-4 rounded-2xl shadow-2xl flex items-center gap-3 border border-rose-200">
                <i class="ph-bold ph-wifi-slash text-2xl"></i>
                <div>
                    <div class="font-bold text-sm">Koneksi Terputus</div>
                    <div class="text-[10px] opacity-90 uppercase tracking-widest font-bold mt-0.5">Menunggu sambungan...</div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 md:space-y-8 relative z-10">
            
            {{-- HERO SECTION (UNIFIED ELEVATE DARK GLASS - AQUALIFE & E-LEARNING) --}}
            <div class="animate-enter relative rounded-[2.5rem] bg-gradient-to-br from-[#0d52a1]/85 via-[#031d3d]/90 to-[#021124]/95 p-8 md:p-10 mb-8 text-white shadow-2xl shadow-[#0d52a1]/25 border border-white/20 backdrop-blur-2xl overflow-hidden group">
                
                {{-- Specular Top Rim Light (Ref 2) --}}
                <div class="absolute inset-0 rounded-[2.5rem] pointer-events-none border-t border-l border-white/30"></div>
                <div class="absolute inset-0 opacity-[0.03] bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none mix-blend-overlay"></div>

                {{-- Ambient Radiant Glow Orbs (Ref 1 & 2) --}}
                <div class="absolute -top-16 -right-16 w-80 h-80 bg-[#56bbf1]/20 rounded-full blur-[100px] pointer-events-none"></div>
                <div class="absolute -bottom-16 -left-16 w-72 h-72 bg-[#0d52a1]/30 rounded-full blur-[90px] pointer-events-none"></div>

                <div class="relative z-10 flex flex-col lg:flex-row gap-8 items-start lg:items-center justify-between">
                    
                    {{-- KIRI: Judul, Intro, Chips & Navigasi --}}
                    <div class="space-y-4 max-w-2xl">
                        <div class="flex flex-wrap items-center gap-2">
                            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 border border-white/20 text-sky-300 text-xs font-bold uppercase tracking-wider backdrop-blur-md shadow-sm">
                                <span class="relative flex h-2 w-2">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                </span>
                                Pos Piket Digital
                            </div>
                            <nav class="flex flex-wrap items-center gap-1.5 bg-white/10 backdrop-blur-md p-1 rounded-full border border-white/15">
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-sky-500/30 text-sky-200 border border-sky-400/40 shadow-sm flex items-center gap-1.5">
                                    <i class="ph-bold ph-shield-check"></i> Pos Piket
                                </span>
                                <a href="{{ route('permit.history') }}" class="px-3 py-1 rounded-full text-xs font-bold text-slate-300 hover:text-white hover:bg-white/15 transition-all flex items-center gap-1.5">
                                    <i class="ph-bold ph-clock-counter-clockwise text-sky-400"></i> Riwayat
                                </a>
                                <a href="{{ route('permit.analytics') }}" class="px-3 py-1 rounded-full text-xs font-bold text-slate-300 hover:text-white hover:bg-white/15 transition-all flex items-center gap-1.5">
                                    <i class="ph-bold ph-chart-polar text-amber-400"></i> Analitik
                                </a>
                                <a href="{{ route('permit.schedules') }}" class="px-3 py-1 rounded-full text-xs font-bold text-emerald-300 hover:text-white hover:bg-emerald-500/20 border border-emerald-400/30 transition-all flex items-center gap-1.5">
                                    <i class="ph-bold ph-calendar-check text-emerald-400"></i> Jadwal Piket
                                </a>
                                <a href="{{ route('permit.journal.print', ['date' => $todayDate]) }}" target="_blank" class="px-3 py-1 rounded-full text-xs font-bold text-amber-300 hover:text-white hover:bg-amber-500/20 border border-amber-400/30 transition-all flex items-center gap-1.5">
                                    <i class="ph-bold ph-printer text-amber-400"></i> Cetak Jurnal
                                </a>
                            </nav>
                        </div>
                        
                        <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-white tracking-tight leading-snug sm:leading-snug md:leading-normal">
                            <span class="block text-slate-100">Pelayanan Izin &</span>
                            <span class="block mt-1 sm:mt-1.5 text-transparent bg-clip-text bg-gradient-to-r from-[#56bbf1] via-sky-200 to-[#38bdf8]">Pos Guru Piket</span>
                        </h1>
                        <p class="text-slate-300 text-sm md:text-base font-medium leading-relaxed">
                            Kelola izin keluar-masuk siswa dengan cepat dan akurat. Terverifikasi barcode scanner kamera, RFID fisik, dan otomatis tercatat ke riwayat.
                        </p>

                        {{-- Feature Highlight Chips (Ref 1 & 2) --}}
                        <div class="flex flex-wrap items-center gap-2.5 pt-1">
                            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-white/5 border border-white/10 text-slate-200 text-xs font-semibold backdrop-blur-sm shadow-sm">
                                <i class="ph-bold ph-check text-sky-400"></i> Scanner Kamera & RFID
                            </div>
                            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-white/5 border border-white/10 text-slate-200 text-xs font-semibold backdrop-blur-sm shadow-sm">
                                <i class="ph-bold ph-check text-sky-400"></i> Timer Durasi Otomatis
                            </div>
                            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-white/5 border border-white/10 text-slate-200 text-xs font-semibold backdrop-blur-sm shadow-sm">
                                <i class="ph-bold ph-check text-sky-400"></i> Monitoring Realtime
                            </div>
                        </div>
                    </div>

                    {{-- KANAN: GLOWING FOCAL SHOWCASE + WIDGET JAM (Ref 1 Glowing Ring & Floating Bubbles) --}}
                    <div class="relative flex items-center justify-center shrink-0 w-full lg:w-auto mt-4 lg:mt-0">
                        {{-- Multi-Layer Luminous Outer Ring --}}
                        <div class="absolute w-64 h-64 rounded-full border border-sky-400/20 bg-sky-500/5 blur-sm pointer-events-none"></div>
                        <div class="absolute w-56 h-56 rounded-full border border-sky-300/30 shadow-[0_0_40px_rgba(86,187,241,0.2)] pointer-events-none"></div>

                        {{-- Floating Micro Bubbles (Ref 1) --}}
                        <div class="absolute -top-3 -right-2 z-20 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-sky-300 text-[10px] font-bold shadow-lg flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            Live Scanner
                        </div>
                        <div class="absolute -bottom-2 -left-3 z-20 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-slate-300 text-[10px] font-bold shadow-lg flex items-center gap-1.5">
                            <i class="ph-bold ph-barcode text-sky-400"></i>
                            RFID Ready
                        </div>

                        {{-- Core Clock Card --}}
                        <div class="relative z-10 bg-[#031d3d]/90 backdrop-blur-xl border border-white/20 p-6 sm:p-7 rounded-[2.5rem] shadow-2xl flex items-center justify-between gap-6 min-w-[280px] group-hover:border-sky-400/40 transition-all">
                            <div>
                                <h3 class="text-xs font-bold text-slate-300 uppercase tracking-widest mb-1 flex items-center gap-2">
                                    <i class="ph-bold ph-calendar-blank text-sky-400"></i> Waktu Sekarang
                                </h3>
                                <div id="clockDate" class="text-white text-sm font-bold">...</div>
                            </div>

                            <div class="text-right bg-[#021124]/90 px-5 py-3.5 rounded-2xl border border-white/15 shrink-0 shadow-sm">
                                <div id="clockTime" class="text-3xl sm:text-4xl font-black text-white digital-clock tracking-tight leading-none">00:00:00</div>
                                <div class="text-[10px] font-black text-sky-400 mt-1.5 uppercase tracking-widest text-right">WIB / GMT+7</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB & DIALOG STATE (ALPINE.JS) --}}
            <div x-data="{ 
                activePiketTab: 'permits', 
                modalInvalOpen: false,
                modalEditInvalOpen: false,
                editInval: {
                    id: '',
                    absent_teacher_id: '',
                    reason: 'Sakit',
                    class_id: '',
                    subject_id: '',
                    timeslot_info: '',
                    inval_teacher_id: '',
                    assignment_notes: '',
                    status: 'Menunggu Pengganti'
                },
                openEditInval(inval) {
                    this.editInval = {
                        id: inval.id,
                        absent_teacher_id: inval.absent_teacher_id || '',
                        reason: inval.reason || 'Sakit',
                        class_id: inval.class_id || '',
                        subject_id: inval.subject_id || '',
                        timeslot_info: inval.timeslot_info || '',
                        inval_teacher_id: inval.inval_teacher_id || '',
                        assignment_notes: inval.assignment_notes || '',
                        status: inval.status || 'Menunggu Pengganti'
                    };
                    this.modalEditInvalOpen = true;
                }
            }" class="space-y-6 md:space-y-8">
                
                {{-- FLASH NOTIFICATIONS --}}
                @if(session('success'))
                <div class="p-4 rounded-2xl bg-emerald-500/20 border border-emerald-400/40 text-emerald-200 text-sm font-bold flex items-center gap-3 backdrop-blur-md animate-enter">
                    <i class="ph-bold ph-check-circle text-2xl text-emerald-400 shrink-0"></i>
                    <span>{{ session('success') }}</span>
                </div>
                @endif
                @if(session('error'))
                <div class="p-4 rounded-2xl bg-rose-500/20 border border-rose-400/40 text-rose-200 text-sm font-bold flex items-center gap-3 backdrop-blur-md animate-enter">
                    <i class="ph-bold ph-warning-circle text-2xl text-rose-400 shrink-0"></i>
                    <span>{{ session('error') }}</span>
                </div>
                @endif

                {{-- BANNER PETUGAS PIKET HARI INI --}}
                <div class="bg-[#031d3d]/80 backdrop-blur-xl border border-white/15 rounded-[2rem] p-5 sm:p-6 shadow-xl flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-sky-500/20 border border-sky-400/30 flex items-center justify-center text-sky-400 shrink-0">
                            <i class="ph-bold ph-users-three text-2xl"></i>
                        </div>
                        <div>
                            <span class="text-[10px] text-sky-300 font-bold uppercase tracking-widest block">Petugas Piket Hari Ini</span>
                            <h3 class="text-base sm:text-lg font-black text-white">
                                {{ $todayName }}, {{ \Carbon\Carbon::parse($todayDate)->translatedFormat('d F Y') }}
                            </h3>
                        </div>
                    </div>

                    {{-- Daftar Petugas --}}
                    <div class="flex flex-wrap items-center gap-2 w-full md:w-auto">
                        @forelse($todayPiketStaff as $staff)
                        <div class="flex items-center gap-2.5 px-3.5 py-2 rounded-xl bg-[#021124]/80 border border-white/10 shadow-sm group">
                            <div class="w-7 h-7 rounded-full bg-slate-800 border border-white/20 overflow-hidden shrink-0 flex items-center justify-center text-[10px] font-bold text-sky-300">
                                @if($staff->teacher->photo_path)
                                    <img src="{{ asset('storage/' . $staff->teacher->photo_path) }}" alt="" class="w-full h-full object-cover">
                                @else
                                    {{ substr($staff->teacher->name, 0, 2) }}
                                @endif
                            </div>
                            <div class="text-left">
                                <div class="text-xs font-bold text-white leading-tight flex items-center gap-1">
                                    {{ $staff->teacher->name }}
                                    @if($staff->is_coordinator)
                                        <span class="px-1 py-0.2 rounded text-[8px] bg-amber-500/20 text-amber-300 border border-amber-400/30 font-black">KOOR</span>
                                    @endif
                                </div>
                                <span class="text-[9px] font-bold text-sky-400/80">{{ $staff->post_location }}</span>
                            </div>
                        </div>
                        @empty
                        <div class="flex items-center gap-3 text-slate-400 text-xs font-semibold">
                            <span>Belum ada jadwal piket untuk hari {{ $todayName }}.</span>
                            <a href="{{ route('permit.schedules') }}" class="px-3 py-1.5 rounded-lg bg-sky-500/20 hover:bg-sky-500/30 border border-sky-400/30 text-sky-300 text-xs font-bold transition-all">
                                + Atur Jadwal
                            </a>
                        </div>
                        @endforelse
                    </div>
                </div>

                {{-- TAB SWITCHER MENU (MODERN PILL TABS) --}}
                <div class="flex flex-wrap items-center gap-2 p-1.5 bg-[#031d3d]/90 backdrop-blur-xl rounded-2xl border border-white/15 shadow-lg">
                    <button type="button" @click="activePiketTab = 'permits'"
                        :class="activePiketTab === 'permits' ? 'bg-gradient-to-r from-sky-500 to-blue-600 text-white shadow-lg shadow-sky-500/20 font-black' : 'text-slate-300 hover:text-white hover:bg-white/5 font-bold'"
                        class="px-4 py-2.5 rounded-xl text-xs sm:text-sm transition-all flex items-center gap-2">
                        <i class="ph-bold ph-shield-check text-lg"></i>
                        <span>Izin Siswa (Live)</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-white/20 text-white">{{ $activePermits->count() }}</span>
                    </button>

                    <button type="button" @click="activePiketTab = 'inval'"
                        :class="activePiketTab === 'inval' ? 'bg-gradient-to-r from-sky-500 to-blue-600 text-white shadow-lg shadow-sky-500/20 font-black' : 'text-slate-300 hover:text-white hover:bg-white/5 font-bold'"
                        class="px-4 py-2.5 rounded-xl text-xs sm:text-sm transition-all flex items-center gap-2">
                        <i class="ph-bold ph-chalkboard-teacher text-lg"></i>
                        <span>Guru Absen & Inval</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-white/20 text-white">{{ $todayJournal->invals->count() }}</span>
                    </button>

                    <button type="button" @click="activePiketTab = 'guests'"
                        :class="activePiketTab === 'guests' ? 'bg-gradient-to-r from-sky-500 to-blue-600 text-white shadow-lg shadow-sky-500/20 font-black' : 'text-slate-300 hover:text-white hover:bg-white/5 font-bold'"
                        class="px-4 py-2.5 rounded-xl text-xs sm:text-sm transition-all flex items-center gap-2">
                        <i class="ph-bold ph-book-open-user text-lg"></i>
                        <span>Buku Tamu Piket</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-white/20 text-white">{{ $todayJournal->guests->count() }}</span>
                    </button>

                    <button type="button" @click="activePiketTab = 'journal'"
                        :class="activePiketTab === 'journal' ? 'bg-gradient-to-r from-sky-500 to-blue-600 text-white shadow-lg shadow-sky-500/20 font-black' : 'text-slate-300 hover:text-white hover:bg-white/5 font-bold'"
                        class="px-4 py-2.5 rounded-xl text-xs sm:text-sm transition-all flex items-center gap-2">
                        <i class="ph-bold ph-note-pencil text-lg"></i>
                        <span>Catatan Jurnal & Kejadian</span>
                    </button>
                </div>

                {{-- ======================================================== --}}
                {{-- TAB 1: IZIN SISWA (SCANNER & LIVE MONITORING ASLI)      --}}
                {{-- ======================================================== --}}
                <div x-show="activePiketTab === 'permits'" x-transition class="grid grid-cols-1 lg:grid-cols-12 gap-6 md:gap-8 items-start">
                
                {{-- KOLOM KIRI: SCANNER & INPUT (Sticky) --}}
                <div class="lg:col-span-5 space-y-6 md:space-y-8 lg:sticky lg:top-6">
                    
                    {{-- SCANNER CARD --}}
                    <div class="bg-[#031d3d]/90 backdrop-blur-md p-6 md:p-8 rounded-[2.5rem] shadow-xl border border-white/10 relative overflow-hidden animate-enter delay-100 group">
                        
                        <div class="flex justify-between items-center mb-8">
                            <h3 class="font-black text-white flex items-center gap-3 text-lg">
                                <div class="w-10 h-10 rounded-xl bg-white/10 text-sky-400 border border-white/15 flex items-center justify-center">
                                    <i class="ph-bold ph-qr-code text-xl"></i>
                                </div>
                                Scan / Input
                            </h3>
                            
                            {{-- SWITCH MODE --}}
                            <div class="flex items-center gap-2 bg-white/5 px-4 py-2 rounded-xl border border-white/10 shadow-inner" title="Auto Focus RFID">
                                <label class="flex items-center cursor-pointer relative">
                                    <input type="checkbox" id="kioskModeToggle" class="sr-only peer" checked>
                                    <div class="w-8 h-4.5 bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-500 after:border after:rounded-full after:h-3.5 after:w-3.5 after:transition-all peer-checked:bg-sky-500 shadow-inner"></div>
                                    <span class="ml-3 text-[10px] font-black text-slate-300 uppercase tracking-widest">RFID Mode</span>
                                </label>
                            </div>
                        </div>

                        {{-- SCANNER AREA --}}
                        <div class="space-y-5">
                            <div id="cameraContainer" class="hidden mb-4 relative bg-slate-900 rounded-2xl overflow-hidden shadow-inner border-4 border-slate-900 ring-1 ring-white/20">
                                <div id="reader" class="w-full bg-black"></div>
                                <div class="absolute bottom-4 left-0 right-0 text-center pointer-events-none z-10">
                                    <span class="bg-[#021124]/90 text-white text-xs px-4 py-2 rounded-xl backdrop-blur-md border border-white/10 font-bold shadow-sm inline-block">
                                        Arahkan QR Code ke Kamera
                                    </span>
                                </div>
                                <!-- Scan line animation -->
                                <div class="absolute top-0 left-0 w-full h-1 bg-elevate-accent shadow-[0_0_20px_rgba(86,187,241,0.8)] animate-[scan_2s_infinite] z-0 opacity-80"></div>
                            </div>

                            {{-- INPUT FIELD --}}
                            <div class="relative group/input">
                                <input type="text" id="scannerInput" 
                                    class="w-full pl-14 pr-16 py-4 md:py-5 rounded-2xl border-2 border-white/15 bg-[#021124]/90 focus:bg-[#021124] focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 font-mono text-lg md:text-xl font-bold text-white transition-all placeholder:text-slate-500 placeholder:font-sans placeholder:font-medium shadow-sm group-hover/input:border-elevate-accent/50 outline-none" 
                                    placeholder="Tempel Kartu / NIS..." autofocus autocomplete="off">
                                
                                <div class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within/input:text-sky-400 transition-colors">
                                    <i class="ph-duotone ph-barcode text-2xl"></i>
                                </div>
                                
                                <div id="inputSpinner" class="hidden absolute right-5 top-1/2 -translate-y-1/2 text-sky-400">
                                    <i class="ph-bold ph-spinner animate-spin text-2xl"></i>
                                </div>
                                
                                <button id="btnSearch" class="absolute right-3 top-1/2 -translate-y-1/2 bg-sky-600 shadow-sm border border-sky-500 text-white p-2 md:p-3 rounded-xl hover:bg-sky-500 transition cursor-pointer active:scale-95">
                                    <i class="ph-bold ph-arrow-right text-lg"></i>
                                </button>
                            </div>

                            {{-- ACTION BUTTONS --}}
                            <div class="grid grid-cols-2 gap-3 mt-4">
                                <button onclick="PiketApp.toggleCamera()" id="btnCamera" class="col-span-1 text-xs font-bold px-4 py-4 bg-white/10 hover:bg-white/20 text-white hover:text-sky-300 hover:border-sky-400/50 rounded-2xl transition-all flex items-center justify-center gap-2 border border-white/15 shadow-sm active:scale-95">
                                    <i class="ph-bold ph-camera text-xl"></i> <span id="cameraText">Buka Kamera</span>
                                </button>
                                <button onclick="PiketApp.switchCamera()" id="btnSwitchCamera" class="hidden col-span-1 text-xs font-bold px-4 py-4 bg-white/10 hover:bg-white/20 text-white hover:text-sky-300 hover:border-sky-400/50 rounded-2xl transition-all flex items-center justify-center gap-2 border border-white/15 shadow-sm active:scale-95">
                                    <i class="ph-bold ph-camera-rotate text-xl"></i> <span>Putar Kamera</span>
                                </button>
                                <button onclick="PiketApp.openModalManual()" class="col-span-1 text-xs font-bold px-4 py-4 bg-sky-600 hover:bg-sky-500 text-white border border-sky-400/30 rounded-2xl transition-all flex items-center justify-center gap-2 shadow-sm active:scale-95">
                                    <i class="ph-bold ph-keyboard text-xl"></i> Input Manual
                                </button>
                            </div>
                        </div>

                        {{-- FEEDBACK & STATUS --}}
                        <div id="scanFeedback" class="hidden mt-6 p-4 rounded-xl text-center text-sm font-bold animate-pulse transition-all shadow-sm"></div>
                        
                        <div class="mt-6 pt-4 flex justify-between items-center border-t border-slate-100">
                             <span id="focusStatus" class="text-[10px] font-black text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1 rounded-xl hidden uppercase tracking-widest items-center gap-2 shadow-sm">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse inline-block"></span> Ready
                            </span>
                            <div class="text-[10px] text-elevate-dark/50 font-bold uppercase tracking-widest ml-auto flex items-center gap-2">
                                Petugas: <span class="text-elevate-dark font-black px-2 py-1 bg-slate-50 rounded-md border border-slate-200">{{ Auth::user()->name ?? 'Admin' }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- RECENT HISTORY (Compact List) --}}
                    <div class="bg-white p-6 md:p-8 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 animate-enter delay-200">
                        <h3 class="font-black text-elevate-dark mb-6 flex items-center gap-2 text-base uppercase tracking-wider">
                            <i class="ph-duotone ph-clock-counter-clockwise text-elevate-primary text-xl"></i> Baru Saja Kembali
                        </h3>
                        
                        <div id="historyContainer" class="space-y-3 max-h-[300px] overflow-y-auto custom-scrollbar pr-2">
                            @forelse($todayHistory ?? [] as $history)
                            <div class="flex items-center justify-between p-4 rounded-2xl border border-slate-100 hover:border-emerald-200 hover:bg-emerald-50/50 transition-all duration-300 group shadow-sm hover:shadow-md">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-slate-50 flex items-center justify-center font-black text-elevate-dark text-sm shadow-sm border border-slate-200 group-hover:bg-emerald-100 group-hover:text-emerald-700 group-hover:border-emerald-200 transition-colors shrink-0">
                                        {{ substr($history->student->name, 0, 1) }}
                                    </div>
                                    <div class="min-w-0 pr-2">
                                        <div class="text-sm font-bold text-elevate-dark line-clamp-1 group-hover:text-emerald-700 transition-colors">{{ $history->student->name }}</div>
                                        <div class="text-[10px] text-elevate-dark/60 font-bold uppercase tracking-wider mt-1 truncate">
                                            {{ $history->reason_category }} <span class="mx-1 text-slate-300">•</span> {{ $history->duration_minutes }} mnt
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-600 text-[10px] font-black border border-emerald-100 shadow-sm uppercase tracking-wider">
                                        <i class="ph-bold ph-check"></i> {{ $history->time_in->format('H:i') }}
                                    </span>
                                </div>
                            </div>
                            @empty
                            <div class="flex flex-col items-center justify-center py-10 text-elevate-dark/40 border border-dashed border-slate-200 rounded-[2rem] bg-slate-50/50">
                                <i class="ph-duotone ph-coffee text-4xl mb-3 opacity-50 text-elevate-primary"></i>
                                <span class="text-xs font-bold uppercase tracking-widest">Belum ada riwayat.</span>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: LIVE MONITORING (Cards) --}}
                <div class="lg:col-span-7 animate-enter delay-200 h-full flex flex-col">
                    <div class="bg-[#031d3d]/90 backdrop-blur-md rounded-[2.5rem] shadow-xl border border-white/10 flex flex-col min-h-[500px] lg:h-full relative overflow-hidden">
                        
                        {{-- HEADER MONITORING --}}
                        <div class="p-6 md:p-8 border-b border-white/10 bg-[#031d3d]/95 backdrop-blur-md sticky top-0 z-20 flex justify-between items-center">
                            <div>
                                <h3 class="font-black text-white text-lg sm:text-xl flex items-center gap-3">
                                    <span class="relative flex h-3 w-3">
                                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-500 opacity-75"></span>
                                      <span class="relative inline-flex rounded-full h-3 w-3 bg-rose-500"></span>
                                    </span>
                                    Sedang Di Luar
                                </h3>
                                <p class="text-xs text-slate-400 font-medium mt-1.5 ml-6">Siswa yang belum kembali ke kelas.</p>
                            </div>
                            
                            <div id="activeCountBadge" class="bg-sky-600 text-white px-5 sm:px-6 py-3 rounded-2xl shadow-lg text-center min-w-[80px] sm:min-w-[90px] shrink-0 border border-sky-400/30">
                                <span class="block text-2xl sm:text-3xl font-black leading-none">{{ collect($activePermits ?? [])->count() }}</span>
                                <span class="text-[9px] font-black uppercase tracking-widest text-sky-200 mt-1 block">Siswa</span>
                            </div>
                        </div>
                        
                        {{-- GRID CARD --}}
                        <div id="activePermitsContainer" class="flex-1 overflow-y-auto custom-scrollbar p-6 md:p-8 bg-transparent">
                            @if(collect($activePermits ?? [])->count() > 0)
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    @foreach($activePermits as $permit)
                                    <div class="permit-card group relative bg-white p-6 rounded-2xl border transition-all duration-300 flex flex-col justify-between shadow-sm hover:-translate-y-1
                                        {{ $permit->is_overdue 
                                            ? 'border-rose-200 hover:border-rose-500 hover:shadow-xl hover:shadow-rose-500/10' 
                                            : 'border-slate-200 hover:border-elevate-accent hover:shadow-xl hover:shadow-elevate-accent/10' 
                                        }}">
                                        
                                        @if($permit->is_overdue)
                                            <div class="absolute -top-3 -right-3 bg-rose-600 text-white text-[10px] font-black uppercase tracking-wider px-3 py-1.5 rounded-xl shadow-md animate-pulse z-10 flex items-center gap-1.5 border border-white">
                                                <i class="ph-bold ph-warning"></i> TELAT
                                            </div>
                                        @endif

                                        <div class="flex items-start gap-4 mb-5">
                                            <div class="w-14 h-14 rounded-2xl flex-shrink-0 flex items-center justify-center text-xl font-black shadow-sm transition-colors border
                                                {{ $permit->is_overdue ? 'bg-rose-50 text-rose-600 border-rose-200' : 'bg-elevate-soft text-elevate-primary border-elevate-accent/20 group-hover:bg-elevate-primary group-hover:text-white' }}">
                                                {{ substr($permit->student->name, 0, 1) }}
                                            </div>
                                            <div class="min-w-0 pr-2 pt-1">
                                                <h4 class="font-black text-elevate-dark leading-snug truncate text-sm md:text-base">{{ $permit->student->name }}</h4>
                                                <p class="text-xs text-elevate-dark/60 font-bold mt-1 flex items-center gap-1.5">
                                                    <i class="ph-bold ph-student"></i> <span class="truncate">{{ $permit->student->schoolClass->name ?? 'Kelas -' }}</span>
                                                    <span class="text-slate-300 mx-0.5">•</span>
                                                    <span class="font-mono">{{ $permit->student->student_id }}</span>
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <div class="space-y-4">
                                            {{-- Reason Box --}}
                                            <div class="bg-slate-50 rounded-xl p-3 border border-slate-100 group-hover:bg-white transition-colors">
                                                <div class="flex justify-between items-center mb-1.5">
                                                    <span class="text-[9px] text-elevate-dark/50 font-black uppercase tracking-widest">Keperluan</span>
                                                    <span class="text-[10px] font-bold text-elevate-dark px-2.5 py-1 bg-white rounded-lg border border-slate-200 shadow-sm">{{ $permit->reason_category }}</span>
                                                </div>
                                                @if($permit->notes)
                                                <p class="text-xs text-elevate-dark/70 italic truncate mt-2 font-medium">"{{ $permit->notes }}"</p>
                                                @endif
                                            </div>

                                            <div class="flex items-end justify-between pt-3 border-t border-slate-100">
                                                <div class="text-[10px] font-bold text-elevate-dark/50 uppercase tracking-widest">
                                                    Keluar <span class="text-elevate-dark font-mono font-black text-sm ml-1 bg-slate-50 px-2 py-1 rounded border border-slate-200">{{ $permit->time_out->format('H:i') }}</span>
                                                </div>
                                                <div class="live-timer text-right" data-start="{{ $permit->time_out }}">
                                                    <span class="text-3xl font-black font-mono leading-none tracking-tight {{ $permit->is_overdue ? 'text-rose-600' : 'text-elevate-dark' }}">
                                                        <span class="timer-number">{{ $permit->minutes_elapsed }}</span><span class="text-sm font-bold opacity-50 ml-1 font-sans">mnt</span>
                                                    </span>
                                                </div>
                                            </div>

                                            {{-- TOMBOL AKSI 1-KLIK (KEMBALI & CETAK SLIP) --}}
                                            <div class="flex items-center gap-2 pt-3 border-t border-slate-100">
                                                <button type="button" 
                                                        onclick="PiketApp.returnPermit({{ $permit->id }}, '{{ addslashes($permit->student->name) }}')" 
                                                        class="flex-1 py-2 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-sm active:scale-95" 
                                                        title="Tandai Siswa Sudah Kembali ke Kelas">
                                                    <i class="ph-bold ph-arrow-u-down-left text-sm"></i>
                                                    <span>Kembali</span>
                                                </button>
                                                <button type="button" 
                                                        onclick="PiketApp.openSlip({{ $permit->id }})" 
                                                        class="p-2 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 text-xs font-bold transition flex items-center justify-center shadow-sm active:scale-95" 
                                                        title="Cetak Slip Izin Mini (Untuk Satpam Gerbang)">
                                                    <i class="ph-bold ph-printer text-base text-sky-600"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="flex flex-col items-center justify-center h-full text-elevate-dark/40 py-16 lg:py-24">
                                    <div class="w-32 h-32 md:w-40 md:h-40 bg-white rounded-full flex items-center justify-center mb-8 shadow-sm border border-slate-200 group">
                                        <i class="ph-duotone ph-student text-6xl md:text-7xl text-elevate-primary opacity-50 group-hover:scale-110 group-hover:opacity-100 transition-all duration-500"></i>
                                    </div>
                                    <h4 class="text-xl font-black text-elevate-dark">Kelas Kondusif</h4>
                                    <p class="text-sm max-w-xs text-center mt-2 font-medium text-elevate-dark/60">Semua siswa berada di dalam kelas saat ini. Data siswa yang izin akan muncul di sini.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                </div>

                {{-- ======================================================== --}}
                {{-- TAB 2: GURU ABSEN & PENUGASAN INVAL (PENGGANTI)          --}}
                {{-- ======================================================== --}}
                <div x-show="activePiketTab === 'inval'" x-transition class="space-y-6">
                    <div class="bg-[#031d3d]/90 backdrop-blur-xl border border-white/15 rounded-[2.5rem] p-6 sm:p-8 shadow-xl">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 pb-4 border-b border-white/10">
                            <div>
                                <h3 class="text-xl font-black text-white flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-sky-500/20 text-sky-400 border border-sky-400/30 flex items-center justify-center">
                                        <i class="ph-bold ph-chalkboard-teacher text-xl"></i>
                                    </div>
                                    Catatan Guru Tidak Hadir & Inval Hari Ini
                                </h3>
                                <p class="text-xs text-slate-300 mt-1">Catat guru yang berhalangan hadir dan tentukan guru pengganti (inval) agar kelas tidak kosong.</p>
                            </div>

                            <button type="button" @click="modalInvalOpen = true" class="px-5 py-3 rounded-2xl bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white font-bold text-sm shadow-lg shadow-sky-500/25 transition-all flex items-center gap-2 active:scale-95">
                                <i class="ph-bold ph-plus text-lg"></i> + Catat Guru Absen / Inval
                            </button>
                        </div>

                        {{-- TABEL INVAL --}}
                        <div class="overflow-x-auto custom-scrollbar">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-white/10 text-[11px] font-black uppercase tracking-wider text-slate-400">
                                        <th class="py-3 px-4">Guru Tidak Hadir</th>
                                        <th class="py-3 px-4">Alasan</th>
                                        <th class="py-3 px-4">Kelas & Jam</th>
                                        <th class="py-3 px-4">Mapel</th>
                                        <th class="py-3 px-4">Guru Inval (Pengganti)</th>
                                        <th class="py-3 px-4">Status</th>
                                        <th class="py-3 px-4 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5 text-sm">
                                    @forelse($todayJournal->invals as $inval)
                                    <tr class="hover:bg-white/[0.03] transition-colors">
                                        <td class="py-3.5 px-4 font-bold text-white">
                                            {{ $inval->absentTeacher->name ?? '-' }}
                                        </td>
                                        <td class="py-3.5 px-4">
                                            <span class="px-2.5 py-1 rounded-lg text-xs font-bold 
                                                {{ $inval->reason === 'Sakit' ? 'bg-amber-500/20 text-amber-300 border border-amber-400/30' : 
                                                   ($inval->reason === 'Dinas Luar' ? 'bg-sky-500/20 text-sky-300 border border-sky-400/30' : 'bg-slate-500/20 text-slate-300 border border-slate-400/30') }}">
                                                {{ $inval->reason }}
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-4 text-slate-300 font-semibold">
                                            <span class="font-bold text-white">{{ $inval->schoolClass->name ?? '-' }}</span>
                                            <span class="text-xs text-sky-400 block">{{ $inval->timeslot_info ?: 'Jam KBM' }}</span>
                                        </td>
                                        <td class="py-3.5 px-4 text-slate-300">
                                            {{ $inval->subject->name ?? '-' }}
                                        </td>
                                        <td class="py-3.5 px-4">
                                            @if($inval->invalTeacher)
                                                <div class="font-bold text-emerald-300 flex items-center gap-1.5">
                                                    <i class="ph-bold ph-check-circle"></i>
                                                    {{ $inval->invalTeacher->name }}
                                                </div>
                                            @else
                                                <span class="text-xs italic text-rose-300 font-bold bg-rose-500/20 px-2.5 py-1 rounded-lg border border-rose-400/30">
                                                    Belum Ada Pengganti
                                                </span>
                                            @endif
                                            @if($inval->assignment_notes)
                                                <p class="text-[11px] text-slate-400 italic mt-1 line-clamp-1">Tugas: "{{ $inval->assignment_notes }}"</p>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-4">
                                            <span class="px-2.5 py-1 rounded-lg text-xs font-bold 
                                                {{ $inval->status === 'Terisi' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-400/30' : 
                                                   ($inval->status === 'Selesai' ? 'bg-blue-500/20 text-blue-300 border border-blue-400/30' : 'bg-rose-500/20 text-rose-300 border border-rose-400/30') }}">
                                                {{ $inval->status }}
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-4 text-center">
                                            <div class="flex items-center justify-center gap-1">
                                                <button type="button" 
                                                    @click="openEditInval({{ json_encode([
                                                        'id' => $inval->id,
                                                        'absent_teacher_id' => $inval->absent_teacher_id,
                                                        'reason' => $inval->reason,
                                                        'class_id' => $inval->class_id,
                                                        'subject_id' => $inval->subject_id,
                                                        'timeslot_info' => $inval->timeslot_info,
                                                        'inval_teacher_id' => $inval->inval_teacher_id,
                                                        'assignment_notes' => $inval->assignment_notes,
                                                        'status' => $inval->status,
                                                    ]) }})"
                                                    class="p-2 rounded-xl text-slate-400 hover:text-sky-300 hover:bg-sky-500/10 transition-colors cursor-pointer" 
                                                    title="Edit Data Inval">
                                                    <i class="ph-bold ph-pencil-simple text-lg"></i>
                                                </button>
                                                <form action="{{ route('permit.inval.destroy', $inval->id) }}" method="POST" onsubmit="return confirm('Hapus catatan inval ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 rounded-xl text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 transition-colors cursor-pointer" title="Hapus">
                                                        <i class="ph-bold ph-trash text-lg"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="py-12 text-center text-slate-400">
                                            <div class="w-16 h-16 rounded-full bg-white/5 flex items-center justify-center mx-auto mb-3 border border-white/10">
                                                <i class="ph-bold ph-check text-2xl text-emerald-400"></i>
                                            </div>
                                            <p class="font-bold text-sm text-white">Seluruh Guru Hadir</p>
                                            <p class="text-xs text-slate-400 mt-1">Belum ada catatan guru yang berhalangan hadir pada hari ini.</p>
                                            <button type="button" @click="modalInvalOpen = true" class="mt-4 inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-sky-500/20 hover:bg-sky-500/30 text-sky-300 border border-sky-400/30 font-bold text-xs transition-all cursor-pointer active:scale-95">
                                                <i class="ph-bold ph-plus-circle text-base"></i> + Catat Guru Tidak Hadir & Inval Sekarang
                                            </button>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- ======================================================== --}}
                {{-- TAB 3: BUKU TAMU MEJA PIKET                              --}}
                {{-- ======================================================== --}}
                <div x-show="activePiketTab === 'guests'" x-transition class="space-y-6">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                        
                        {{-- Form Input Tamu (Kiri) --}}
                        <div class="lg:col-span-4 bg-[#031d3d]/90 backdrop-blur-xl border border-white/15 rounded-[2.5rem] p-6 sm:p-8 shadow-xl">
                            <h3 class="font-black text-lg text-white mb-6 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-sky-500/20 text-sky-400 border border-sky-400/30 flex items-center justify-center">
                                    <i class="ph-bold ph-user-plus text-xl"></i>
                                </div>
                                Form Tamu Datang
                            </h3>

                            <form action="{{ route('permit.guest.store') }}" method="POST" class="space-y-4">
                                @csrf
                                <input type="hidden" name="piket_journal_id" value="{{ $todayJournal->id }}">

                                <div>
                                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">Nama Tamu</label>
                                    <input type="text" name="guest_name" required placeholder="Nama Lengkap Tamu" class="w-full px-4 py-3 rounded-xl border border-white/15 bg-[#021124] text-white font-bold text-sm focus:border-sky-400 focus:ring-2 focus:ring-sky-400/30 outline-none">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">Asal Instansi / Lembaga</label>
                                    <input type="text" name="institution" placeholder="Contoh: Dinas Pendidikan, Orang Tua Siswa" class="w-full px-4 py-3 rounded-xl border border-white/15 bg-[#021124] text-white font-bold text-sm focus:border-sky-400 focus:ring-2 focus:ring-sky-400/30 outline-none">
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">No. HP / WA</label>
                                        <input type="text" name="phone" placeholder="08..." class="w-full px-4 py-3 rounded-xl border border-white/15 bg-[#021124] text-white font-bold text-sm focus:border-sky-400 focus:ring-2 focus:ring-sky-400/30 outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">Jam Datang</label>
                                        <input type="time" name="time_in" value="{{ \Carbon\Carbon::now(config('app.timezone', 'Asia/Jakarta'))->format('H:i') }}" class="w-full px-4 py-3 rounded-xl border border-white/15 bg-[#021124] text-white font-bold text-sm focus:border-sky-400 focus:ring-2 focus:ring-sky-400/30 outline-none">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">Bertemu Dengan</label>
                                    <input type="text" name="meet_with" placeholder="Contoh: Kepala Sekolah, Bpk. Guru X" class="w-full px-4 py-3 rounded-xl border border-white/15 bg-[#021124] text-white font-bold text-sm focus:border-sky-400 focus:ring-2 focus:ring-sky-400/30 outline-none">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">Keperluan / Agenda</label>
                                    <textarea name="purpose" rows="2" required placeholder="Maksud kunjungan..." class="w-full px-4 py-3 rounded-xl border border-white/15 bg-[#021124] text-white font-bold text-sm focus:border-sky-400 focus:ring-2 focus:ring-sky-400/30 outline-none"></textarea>
                                </div>

                                <div class="flex items-center gap-2 pt-1">
                                    <input type="checkbox" name="is_vip" value="1" id="is_vip" class="rounded border-slate-700 text-sky-500 focus:ring-sky-400 bg-slate-900">
                                    <label for="is_vip" class="text-xs font-bold text-slate-300 cursor-pointer">Tamu VIP (Tampilkan di Layar Lobi)</label>
                                </div>

                                <button type="submit" class="w-full py-3.5 px-6 rounded-xl bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white font-bold text-sm shadow-lg shadow-sky-500/25 transition-all active:scale-95 flex items-center justify-center gap-2">
                                    <i class="ph-bold ph-floppy-disk text-lg"></i> Simpan Buku Tamu
                                </button>
                            </form>
                        </div>

                        {{-- Tabel Daftar Tamu Hari Ini (Kanan) --}}
                        <div class="lg:col-span-8 bg-[#031d3d]/90 backdrop-blur-xl border border-white/15 rounded-[2.5rem] p-6 sm:p-8 shadow-xl">
                            <h3 class="font-black text-lg text-white mb-6 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white/10 text-sky-400 border border-white/15 flex items-center justify-center">
                                    <i class="ph-bold ph-address-book text-xl"></i>
                                </div>
                                Tamu Berkunjung Hari Ini ({{ $todayJournal->guests->count() }})
                            </h3>

                            <div class="overflow-x-auto custom-scrollbar">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="border-b border-white/10 text-[11px] font-black uppercase tracking-wider text-slate-400">
                                            <th class="py-3 px-3">Tamu</th>
                                            <th class="py-3 px-3">Instansi</th>
                                            <th class="py-3 px-3">Bertemu</th>
                                            <th class="py-3 px-3">Keperluan</th>
                                            <th class="py-3 px-3">Waktu</th>
                                            <th class="py-3 px-3 text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-white/5 text-sm">
                                        @forelse($todayJournal->guests as $guest)
                                        <tr class="hover:bg-white/[0.03] transition-colors">
                                            <td class="py-3.5 px-3">
                                                <div class="font-bold text-white flex items-center gap-1.5">
                                                    {{ $guest->guest_name }}
                                                    @if($guest->is_vip)
                                                        <span class="px-1.5 py-0.5 rounded text-[8px] bg-amber-500/20 text-amber-300 border border-amber-400/40 font-black">VIP</span>
                                                    @endif
                                                </div>
                                                @if($guest->phone)
                                                    <span class="text-xs text-slate-400 block">{{ $guest->phone }}</span>
                                                @endif
                                            </td>
                                            <td class="py-3.5 px-3 text-slate-300 font-semibold">{{ $guest->institution ?: '-' }}</td>
                                            <td class="py-3.5 px-3 text-sky-300 font-bold">{{ $guest->meet_with ?: '-' }}</td>
                                            <td class="py-3.5 px-3 text-slate-300 max-w-xs truncate">{{ $guest->purpose }}</td>
                                            <td class="py-3.5 px-3">
                                                <span class="font-mono text-xs font-bold text-white block">
                                                    {{ substr($guest->time_in, 0, 5) }} 
                                                    @if($guest->time_out)
                                                        - {{ substr($guest->time_out, 0, 5) }}
                                                    @endif
                                                </span>
                                                @if(!$guest->time_out)
                                                    <span class="text-[9px] font-bold text-emerald-400 uppercase tracking-widest">Sedang Berkunjung</span>
                                                @else
                                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Selesai</span>
                                                @endif
                                            </td>
                                            <td class="py-3.5 px-3 text-center">
                                                <div class="flex items-center justify-center gap-1">
                                                    @if(!$guest->time_out)
                                                    <form action="{{ route('permit.guest.checkout', $guest->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="px-2.5 py-1.5 rounded-lg bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300 text-xs font-bold border border-emerald-400/30 transition-all" title="Check-out">
                                                            Check-out
                                                        </button>
                                                    </form>
                                                    @endif

                                                    <form action="{{ route('permit.guest.destroy', $guest->id) }}" method="POST" onsubmit="return confirm('Hapus data tamu ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-400 transition-colors" title="Hapus">
                                                            <i class="ph-bold ph-trash text-base"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="py-10 text-center text-slate-400">
                                                <i class="ph-duotone ph-users text-4xl mb-2 opacity-50 text-sky-400"></i>
                                                <p class="font-bold text-sm text-white">Belum Ada Tamu Hari Ini</p>
                                                <p class="text-xs text-slate-400 mt-1">Input data tamu melalui formulir di sebelah kiri.</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ======================================================== --}}
                {{-- TAB 4: CATATAN KHUSUS & JURNAL PIKET                     --}}
                {{-- ======================================================== --}}
                <div x-show="activePiketTab === 'journal'" x-transition class="space-y-6">
                    <div class="bg-[#031d3d]/90 backdrop-blur-xl border border-white/15 rounded-[2.5rem] p-6 sm:p-8 shadow-xl max-w-4xl mx-auto">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 pb-4 border-b border-white/10">
                            <div>
                                <h3 class="text-xl font-black text-white flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-sky-500/20 text-sky-400 border border-sky-400/30 flex items-center justify-center">
                                        <i class="ph-bold ph-note-pencil text-xl"></i>
                                    </div>
                                    Buku Jurnal & Catatan Khusus Piket
                                </h3>
                                <p class="text-xs text-slate-300 mt-1">Catat kejadian penting, insiden ketertiban, dan kondisi umum jalannya KBM.</p>
                            </div>

                            <a href="{{ route('permit.journal.print', ['date' => $todayDate]) }}" target="_blank" class="px-5 py-3 rounded-2xl bg-amber-500/20 hover:bg-amber-500/30 text-amber-300 border border-amber-400/40 font-bold text-sm shadow-sm transition-all flex items-center gap-2">
                                <i class="ph-bold ph-printer text-lg"></i> Cetak Dokumen PDF
                            </a>
                        </div>

                        <form action="{{ route('permit.journal.save') }}" method="POST" class="space-y-6">
                            @csrf
                            <input type="hidden" name="date" value="{{ $todayDate }}">

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                {{-- Cuaca --}}
                                <div>
                                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Kondisi Cuaca</label>
                                    <select name="weather" class="w-full px-4 py-3 rounded-xl border border-white/15 bg-[#021124] text-white font-bold text-sm focus:border-sky-400 outline-none">
                                        <option value="Cerah" {{ $todayJournal->weather === 'Cerah' ? 'selected' : '' }}>☀️ Cerah</option>
                                        <option value="Berawan" {{ $todayJournal->weather === 'Berawan' ? 'selected' : '' }}>⛅ Berawan</option>
                                        <option value="Hujan Ringan" {{ $todayJournal->weather === 'Hujan Ringan' ? 'selected' : '' }}>🌦️ Hujan Ringan</option>
                                        <option value="Hujan Lebat" {{ $todayJournal->weather === 'Hujan Lebat' ? 'selected' : '' }}>⛈️ Hujan Lebat</option>
                                    </select>
                                </div>

                                {{-- Koordinator Piket --}}
                                <div>
                                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Koordinator Piket Hari Ini</label>
                                    <select name="coordinator_id" class="w-full px-4 py-3 rounded-xl border border-white/15 bg-[#021124] text-white font-bold text-sm focus:border-sky-400 outline-none">
                                        <option value="">-- Pilih Koordinator --</option>
                                        @foreach($allTeachers as $teacher)
                                            <option value="{{ $teacher->id }}" {{ $todayJournal->coordinator_id == $teacher->id ? 'selected' : '' }}>
                                                {{ $teacher->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Catatan Umum --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Catatan Khusus / Insiden / Ketertiban</label>
                                <textarea name="general_notes" rows="6" placeholder="Tuliskan catatan kejadian penting hari ini, seperti keterlambatan massal, masalah sarana kelas, atau pembinaan siswa..." class="w-full px-4 py-3.5 rounded-2xl border border-white/15 bg-[#021124] text-white font-medium text-sm focus:border-sky-400 outline-none leading-relaxed">{{ $todayJournal->general_notes }}</textarea>
                            </div>

                            <div class="flex justify-end gap-3 pt-2">
                                <button type="submit" class="py-3.5 px-8 rounded-xl bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white font-bold text-sm shadow-lg shadow-sky-500/25 transition-all active:scale-95 flex items-center gap-2">
                                    <i class="ph-bold ph-floppy-disk text-lg"></i> Simpan Catatan Jurnal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- MODAL TAMBAH INVAL (MULTI-KELAS) --}}
                <div x-show="modalInvalOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                    <div class="fixed inset-0 bg-[#021124]/85 backdrop-blur-md" @click="modalInvalOpen = false"></div>
                    
                    <div x-data="{
                        slots: [
                            { class_id: '', subject_id: '', timeslot_info: '', inval_teacher_id: '', assignment_notes: '' }
                        ],
                        addSlot() {
                            this.slots.push({ class_id: '', subject_id: '', timeslot_info: '', inval_teacher_id: '', assignment_notes: '' });
                        },
                        removeSlot(idx) {
                            if (this.slots.length > 1) {
                                this.slots.splice(idx, 1);
                            }
                        }
                    }" class="bg-[#031d3d] border border-white/20 w-full max-w-3xl rounded-[2.5rem] shadow-2xl p-6 sm:p-8 relative z-10 animate-enter max-h-[92vh] overflow-y-auto custom-scrollbar text-white">
                        
                        {{-- Modal Header --}}
                        <div class="flex justify-between items-start mb-6 pb-4 border-b border-white/10">
                            <div>
                                <h3 class="font-black text-xl text-white flex items-center gap-2.5">
                                    <div class="w-9 h-9 rounded-xl bg-sky-500/20 text-sky-400 border border-sky-400/30 flex items-center justify-center">
                                        <i class="ph-bold ph-chalkboard-teacher text-lg"></i>
                                    </div>
                                    Catat Guru Absen & Penugasan Inval
                                </h3>
                                <p class="text-xs text-slate-300 mt-1">Dapat menambahkan beberapa jadwal kelas sekaligus yang ditinggalkan guru bersangkutan hari ini.</p>
                            </div>
                            <button type="button" @click="modalInvalOpen = false" class="p-2 rounded-full bg-white/10 text-slate-400 hover:text-white hover:bg-white/20 transition cursor-pointer">
                                <i class="ph-bold ph-x text-lg"></i>
                            </button>
                        </div>

                        <form action="{{ route('permit.inval.store') }}" method="POST" class="space-y-6">
                            @csrf
                            <input type="hidden" name="piket_journal_id" value="{{ $todayJournal->id }}">

                            {{-- SECTION 1: IDENTITAS GURU & ALASAN --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-5 rounded-2xl bg-[#021124]/70 border border-white/10">
                                <div>
                                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                                        <i class="ph-bold ph-user text-sky-400"></i> Guru yang Tidak Hadir <span class="text-rose-400">*</span>
                                    </label>
                                    <select name="absent_teacher_id" required class="w-full px-4 py-3 rounded-xl border border-white/15 bg-[#031d3d] text-white font-bold text-sm focus:border-sky-400 outline-none">
                                        <option value="">-- Pilih Guru --</option>
                                        @foreach($allTeachers as $teacher)
                                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                                        <i class="ph-bold ph-info text-amber-400"></i> Alasan Berhalangan <span class="text-rose-400">*</span>
                                    </label>
                                    <select name="reason" required class="w-full px-4 py-3 rounded-xl border border-white/15 bg-[#031d3d] text-white font-bold text-sm focus:border-sky-400 outline-none">
                                        <option value="Sakit">🤒 Sakit</option>
                                        <option value="Izin">✉️ Izin Kepentingan Pribadi</option>
                                        <option value="Dinas Luar">🏛️ Dinas Luar / SPPD / Pelatihan</option>
                                        <option value="Tanpa Keterangan">❓ Tanpa Keterangan</option>
                                    </select>
                                </div>
                            </div>

                            {{-- SECTION 2: DAFTAR KELAS & JAM MENGAJAR (DYNAMIC REPEATER) --}}
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <h4 class="text-xs font-black text-sky-300 uppercase tracking-wider flex items-center gap-2">
                                        <i class="ph-bold ph-list-numbers text-base"></i> Daftar Kelas & Jam Mengajar yang Ditinggalkan
                                    </h4>
                                    <button type="button" @click="addSlot()" class="px-3.5 py-1.5 rounded-xl bg-sky-500/20 hover:bg-sky-500/30 text-sky-300 border border-sky-400/40 text-xs font-bold flex items-center gap-1.5 transition-all cursor-pointer active:scale-95">
                                        <i class="ph-bold ph-plus-circle text-sm"></i> + Tambah Kelas
                                    </button>
                                </div>

                                {{-- SLOT CARDS LOOP --}}
                                <div class="space-y-4">
                                    <template x-for="(slot, index) in slots" :key="index">
                                        <div class="p-5 rounded-2xl bg-[#021124]/90 border border-white/15 shadow-md relative group space-y-3">
                                            {{-- Slot Badge & Delete --}}
                                            <div class="flex justify-between items-center pb-2 border-b border-white/10">
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-sky-500/20 text-sky-300 border border-sky-400/30 text-[11px] font-black uppercase tracking-wider">
                                                    <i class="ph-bold ph-chalkboard"></i>
                                                    <span x-text="'Kelas Mengajar #' + (index + 1)"></span>
                                                </span>

                                                <button type="button" x-show="slots.length > 1" @click="removeSlot(index)" class="text-rose-400 hover:text-rose-300 text-xs font-bold flex items-center gap-1 transition-colors px-2 py-1 rounded-lg hover:bg-rose-500/10 cursor-pointer">
                                                    <i class="ph-bold ph-trash text-sm"></i> Hapus Kelas Ini
                                                </button>
                                            </div>

                                            {{-- Grid: Kelas & Mapel --}}
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                <div>
                                                    <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-1">Kelas <span class="text-rose-400">*</span></label>
                                                    <select :name="'slots[' + index + '][class_id]'" x-model="slot.class_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-white/15 bg-[#031d3d] text-white font-bold text-xs focus:border-sky-400 outline-none">
                                                        <option value="">-- Pilih Kelas --</option>
                                                        @foreach($allClasses as $class)
                                                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div>
                                                    <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-1">Mata Pelajaran <span class="text-rose-400">*</span></label>
                                                    <select :name="'slots[' + index + '][subject_id]'" x-model="slot.subject_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-white/15 bg-[#031d3d] text-white font-bold text-xs focus:border-sky-400 outline-none">
                                                        <option value="">-- Pilih Mapel --</option>
                                                        @foreach($allSubjects as $subj)
                                                            <option value="{{ $subj->id }}">{{ $subj->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            {{-- Grid: Jam Ke & Guru Pengganti --}}
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                <div>
                                                    <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-1">Jam Pelajaran Ke-</label>
                                                    <input type="text" :name="'slots[' + index + '][timeslot_info]'" x-model="slot.timeslot_info" placeholder="Contoh: Jam 1-2 atau Jam 3-4" class="w-full px-3.5 py-2.5 rounded-xl border border-white/15 bg-[#031d3d] text-white font-bold text-xs focus:border-sky-400 outline-none">
                                                </div>

                                                <div>
                                                    <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-1">Guru Pengganti (Inval)</label>
                                                    <select :name="'slots[' + index + '][inval_teacher_id]'" x-model="slot.inval_teacher_id" class="w-full px-3.5 py-2.5 rounded-xl border border-white/15 bg-[#031d3d] text-white font-bold text-xs focus:border-sky-400 outline-none">
                                                        <option value="">-- Belum Ada / Tugas Mandiri --</option>
                                                        @foreach($allTeachers as $teacher)
                                                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            {{-- Tugas Mandiri untuk Kelas Ini --}}
                                            <div>
                                                <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-1">Tugas / Instruksi untuk Kelas Ini</label>
                                                <input type="text" :name="'slots[' + index + '][assignment_notes]'" x-model="slot.assignment_notes" placeholder="Tugas yang ditinggalkan, misal: Mengerjakan LKS hal 24-25..." class="w-full px-3.5 py-2.5 rounded-xl border border-white/15 bg-[#031d3d] text-white font-medium text-xs focus:border-sky-400 outline-none">
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                {{-- Tombol Tambah Baris Kelas Baru --}}
                                <button type="button" @click="addSlot()" class="w-full py-3 rounded-2xl border-2 border-dashed border-sky-400/30 bg-sky-500/5 hover:bg-sky-500/15 text-sky-300 font-bold text-xs flex items-center justify-center gap-2 transition-all cursor-pointer active:scale-98">
                                    <i class="ph-bold ph-plus-circle text-base"></i> + Tambah Kelas & Jam Mengajar Lainnya
                                </button>
                            </div>

                            {{-- FOOTER MODAL --}}
                            <div class="flex justify-end gap-3 pt-3 border-t border-white/10">
                                <button type="button" @click="modalInvalOpen = false" class="px-5 py-3 rounded-xl bg-white/10 text-slate-300 hover:text-white font-bold text-xs sm:text-sm transition-colors cursor-pointer">Batal</button>
                                <button type="submit" class="px-6 py-3 rounded-xl bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white font-bold text-xs sm:text-sm shadow-lg shadow-sky-500/25 transition-all flex items-center gap-2 active:scale-95 cursor-pointer">
                                    <i class="ph-bold ph-floppy-disk text-base"></i> Simpan Semua Jadwal Inval
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- MODAL EDIT INVAL --}}
                <div x-show="modalEditInvalOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                    <div class="fixed inset-0 bg-[#021124]/85 backdrop-blur-md" @click="modalEditInvalOpen = false"></div>
                    
                    <div class="bg-[#031d3d] border border-white/20 w-full max-w-2xl rounded-[2.5rem] shadow-2xl p-6 sm:p-8 relative z-10 animate-enter max-h-[92vh] overflow-y-auto custom-scrollbar text-white">
                        
                        {{-- Modal Header --}}
                        <div class="flex justify-between items-start mb-6 pb-4 border-b border-white/10">
                            <div>
                                <h3 class="font-black text-xl text-white flex items-center gap-2.5">
                                    <div class="w-9 h-9 rounded-xl bg-amber-500/20 text-amber-400 border border-amber-400/30 flex items-center justify-center">
                                        <i class="ph-bold ph-pencil-simple text-lg"></i>
                                    </div>
                                    Edit Catatan Inval & Guru Absen
                                </h3>
                                <p class="text-xs text-slate-300 mt-1">Perbarui rincian ketidakhadiran, kelas, jam mengajar, penugasan, atau guru pengganti (inval).</p>
                            </div>
                            <button type="button" @click="modalEditInvalOpen = false" class="p-2 rounded-full bg-white/10 text-slate-400 hover:text-white hover:bg-white/20 transition cursor-pointer">
                                <i class="ph-bold ph-x text-lg"></i>
                            </button>
                        </div>

                        <form :action="'{{ url('/permit/inval') }}/' + editInval.id" method="POST" class="space-y-6">
                            @csrf
                            @method('PUT')

                            {{-- SECTION 1: IDENTITAS GURU & ALASAN --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-5 rounded-2xl bg-[#021124]/70 border border-white/10">
                                <div>
                                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                                        <i class="ph-bold ph-user text-sky-400"></i> Guru yang Tidak Hadir <span class="text-rose-400">*</span>
                                    </label>
                                    <select name="absent_teacher_id" x-model="editInval.absent_teacher_id" required class="w-full px-4 py-3 rounded-xl border border-white/15 bg-[#031d3d] text-white font-bold text-sm focus:border-sky-400 outline-none">
                                        <option value="">-- Pilih Guru --</option>
                                        @foreach($allTeachers as $teacher)
                                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                                        <i class="ph-bold ph-info text-amber-400"></i> Alasan Berhalangan <span class="text-rose-400">*</span>
                                    </label>
                                    <select name="reason" x-model="editInval.reason" required class="w-full px-4 py-3 rounded-xl border border-white/15 bg-[#031d3d] text-white font-bold text-sm focus:border-sky-400 outline-none">
                                        <option value="Sakit">🤒 Sakit</option>
                                        <option value="Izin">✉️ Izin Kepentingan Pribadi</option>
                                        <option value="Dinas Luar">🏛️ Dinas Luar / SPPD / Pelatihan</option>
                                        <option value="Tanpa Keterangan">❓ Tanpa Keterangan</option>
                                    </select>
                                </div>
                            </div>

                            {{-- SECTION 2: KELAS, MAPEL & JAM PELAJARAN --}}
                            <div class="p-5 rounded-2xl bg-[#021124]/70 border border-white/10 space-y-4">
                                <h4 class="text-xs font-black text-sky-300 uppercase tracking-wider flex items-center gap-2">
                                    <i class="ph-bold ph-chalkboard text-base"></i> Kelas & Jadwal Pelajaran
                                </h4>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-1">Kelas <span class="text-rose-400">*</span></label>
                                        <select name="class_id" x-model="editInval.class_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-white/15 bg-[#031d3d] text-white font-bold text-xs sm:text-sm focus:border-sky-400 outline-none">
                                            <option value="">-- Pilih Kelas --</option>
                                            @foreach($allClasses as $class)
                                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-1">Mata Pelajaran <span class="text-rose-400">*</span></label>
                                        <select name="subject_id" x-model="editInval.subject_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-white/15 bg-[#031d3d] text-white font-bold text-xs sm:text-sm focus:border-sky-400 outline-none">
                                            <option value="">-- Pilih Mapel --</option>
                                            @foreach($allSubjects as $subj)
                                                <option value="{{ $subj->id }}">{{ $subj->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-1">Jam Pelajaran Ke-</label>
                                    <input type="text" name="timeslot_info" x-model="editInval.timeslot_info" placeholder="Contoh: Jam 1-2 atau Jam 3-4" class="w-full px-3.5 py-2.5 rounded-xl border border-white/15 bg-[#031d3d] text-white font-bold text-xs sm:text-sm focus:border-sky-400 outline-none">
                                </div>
                            </div>

                            {{-- SECTION 3: GURU PENGGANTI (INVAL), TUGAS & STATUS --}}
                            <div class="p-5 rounded-2xl bg-[#021124]/70 border border-white/10 space-y-4">
                                <h4 class="text-xs font-black text-emerald-300 uppercase tracking-wider flex items-center gap-2">
                                    <i class="ph-bold ph-user-switch text-base"></i> Penugasan Guru Inval & Catatan
                                </h4>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-1">Guru Pengganti (Inval)</label>
                                        <select name="inval_teacher_id" x-model="editInval.inval_teacher_id" @change="if(editInval.inval_teacher_id && editInval.status === 'Menunggu Pengganti') editInval.status = 'Terisi'; if(!editInval.inval_teacher_id && editInval.status === 'Terisi') editInval.status = 'Menunggu Pengganti';" class="w-full px-3.5 py-2.5 rounded-xl border border-white/15 bg-[#031d3d] text-white font-bold text-xs sm:text-sm focus:border-sky-400 outline-none">
                                            <option value="">-- Belum Ada / Tugas Mandiri --</option>
                                            @foreach($allTeachers as $teacher)
                                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-1">Status Penugasan <span class="text-rose-400">*</span></label>
                                        <select name="status" x-model="editInval.status" required class="w-full px-3.5 py-2.5 rounded-xl border border-white/15 bg-[#031d3d] text-white font-bold text-xs sm:text-sm focus:border-sky-400 outline-none">
                                            <option value="Menunggu Pengganti">⏳ Menunggu Pengganti</option>
                                            <option value="Terisi">✅ Terisi</option>
                                            <option value="Selesai">🎉 Selesai</option>
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-1">Tugas / Catatan untuk Kelas</label>
                                    <textarea name="assignment_notes" x-model="editInval.assignment_notes" rows="2" placeholder="Catatan tugas yang ditinggalkan oleh guru..." class="w-full px-3.5 py-2.5 rounded-xl border border-white/15 bg-[#031d3d] text-white font-medium text-xs sm:text-sm focus:border-sky-400 outline-none resize-none"></textarea>
                                </div>
                            </div>

                            {{-- FOOTER MODAL --}}
                            <div class="flex justify-end gap-3 pt-3 border-t border-white/10">
                                <button type="button" @click="modalEditInvalOpen = false" class="px-5 py-3 rounded-xl bg-white/10 text-slate-300 hover:text-white font-bold text-xs sm:text-sm transition-colors cursor-pointer">Batal</button>
                                <button type="submit" class="px-6 py-3 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-white font-bold text-xs sm:text-sm shadow-lg shadow-amber-500/25 transition-all flex items-center gap-2 active:scale-95 cursor-pointer">
                                    <i class="ph-bold ph-check text-base"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

    {{-- MODAL IZIN KELUAR --}}
    <div id="permitModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 sm:p-6">
        <div class="fixed inset-0 bg-elevate-dark/80 backdrop-blur-sm transition-opacity" onclick="PiketApp.closeModal()"></div>
        <div class="bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl relative border border-slate-100 flex flex-col max-h-[90vh] overflow-hidden z-10 animate-enter">
            
            <button type="button" onclick="PiketApp.closeModal()" class="absolute top-6 right-6 text-slate-400 hover:text-rose-600 transition cursor-pointer z-20 bg-white shadow-sm border border-slate-200 hover:bg-rose-50 hover:border-rose-200 p-2.5 rounded-full flex items-center justify-center">
                <i class="ph-bold ph-x text-xl"></i>
            </button>
            
            <div class="overflow-y-auto custom-scrollbar p-8 md:p-10 flex-1">
                <div class="text-center mb-8 mt-2">
                    <div class="w-20 h-20 bg-elevate-soft border border-elevate-accent/20 text-elevate-primary rounded-2xl rotate-3 flex items-center justify-center mx-auto mb-6 text-4xl shadow-sm">
                        <i class="ph-duotone ph-door-open"></i>
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-black text-elevate-dark tracking-tight">Izin Keluar Kelas</h3>
                    <div class="mt-6 bg-slate-50 rounded-2xl p-5 border border-slate-200 inline-block w-full shadow-inner">
                        <p id="modalStudentName" class="text-elevate-primary font-black text-xl sm:text-2xl leading-tight truncate">Nama Siswa</p>
                        <p id="modalStudentClass" class="text-xs text-elevate-dark/60 font-mono mt-1.5 font-bold uppercase tracking-widest">Kelas Siswa</p>
                    </div>
                </div>

                <form id="permitForm" onsubmit="event.preventDefault(); PiketApp.submitPermitManual();">
                    <input type="hidden" id="modalStudentId" name="student_id">
                    
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        @foreach(['Toilet', 'UKS', 'Keluar Gerbang', 'Pulang Cepat', 'Dispensasi', 'Panggilan Guru', 'Barang Tertinggal', 'Lainnya'] as $reason)
                        <label class="cursor-pointer relative group">
                            <input type="radio" name="reason_category" value="{{ $reason }}" class="peer sr-only">
                            <div class="p-4 rounded-2xl border border-slate-200 text-center text-xs font-bold text-elevate-dark/70 
                                        group-hover:bg-slate-50 group-hover:border-slate-300
                                        peer-checked:border-elevate-accent peer-checked:bg-elevate-soft peer-checked:text-elevate-primary 
                                        transition-all duration-200 shadow-sm flex items-center justify-center h-full">
                                {{ $reason }}
                            </div>
                            <div class="absolute -top-2 -right-2 bg-elevate-dark text-white rounded-full p-1.5 opacity-0 peer-checked:opacity-100 transition-all scale-0 peer-checked:scale-100 transform duration-200 shadow-md ring-2 ring-white">
                                <i class="ph-bold ph-check text-xs"></i>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    
                    <div class="mb-8">
                        <label class="block text-xs font-black text-elevate-primary uppercase tracking-widest mb-3 ml-1">Catatan Tambahan</label>
                        <input type="text" name="notes" class="w-full rounded-2xl border-slate-200 focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 text-sm py-4 px-5 bg-elevate-soft focus:bg-white transition-all placeholder:text-slate-400 font-bold text-elevate-dark shadow-sm outline-none" placeholder="Contoh: Sakit perut, dipanggil Bu Ani...">
                    </div>
                    
                    <button type="submit" id="btnSubmitPermit" class="w-full py-4.5 rounded-2xl bg-elevate-dark text-white font-bold text-lg hover:bg-elevate-primary active:scale-95 transition-all shadow-xl shadow-elevate-dark/30 flex items-center justify-center gap-3 disabled:opacity-50 disabled:cursor-not-allowed border border-transparent">
                        <span>Berikan Izin</span>
                        <i class="ph-bold ph-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL PENCARIAN MANUAL --}}
    <div id="manualSearchModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 sm:p-6">
        <div class="fixed inset-0 bg-elevate-dark/80 backdrop-blur-sm transition-opacity" onclick="document.getElementById('manualSearchModal').classList.add('hidden')"></div>
        <div class="bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl p-8 relative z-10 animate-enter border border-slate-100">
            <h3 class="font-black text-2xl mb-2 text-elevate-dark">Input Manual</h3>
            <p class="text-sm text-elevate-dark/60 mb-8 font-medium leading-relaxed">Masukkan NIS atau Nama siswa jika kartu tertinggal atau rusak.</p>
            
            <div class="relative mb-8">
                <input type="text" id="manualInputBox" class="w-full pl-14 pr-4 py-4 rounded-2xl border-slate-200 focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 font-bold text-elevate-dark bg-elevate-soft focus:bg-white transition-all shadow-sm outline-none placeholder:text-slate-400" placeholder="Ketik Nama / NIS...">
                <i class="ph-bold ph-keyboard absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 text-2xl"></i>
            </div>
            
            <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 mt-4 border-t border-slate-100 pt-6">
                <button onclick="document.getElementById('manualSearchModal').classList.add('hidden')" class="w-full sm:w-auto px-6 py-4 sm:py-3.5 rounded-2xl text-elevate-dark/60 font-bold hover:bg-slate-100 transition-colors border border-transparent">Batal</button>
                <button onclick="PiketApp.submitManualSearch()" class="w-full sm:w-auto px-8 py-4 sm:py-3.5 rounded-2xl bg-elevate-dark text-white font-bold hover:bg-elevate-primary shadow-lg shadow-elevate-dark/30 transition-all active:scale-95 text-center border border-transparent">Cari Data</button>
            </div>
        </div>
    </div>

    {{-- JAVASCRIPT LOGIC --}}
    <script>
        const PiketApp = {
            csrfToken: '{{ csrf_token() }}',
            isProcessing: false,
            isCameraRunning: false,
            scanCooldown: false,
            currentFacingMode: 'environment',
            html5QrCode: null,
            audioCtx: new (window.AudioContext || window.webkitAudioContext)(),
            
            elements: {
                scannerInput: document.getElementById('scannerInput'),
                scanFeedback: document.getElementById('scanFeedback'),
                kioskModeToggle: document.getElementById('kioskModeToggle'),
                focusStatus: document.getElementById('focusStatus'),
                modal: document.getElementById('permitModal'),
            },

            init() {
                this.startClock();
                this.setupOfflineListener();
                this.setupEventListeners();
                
                setInterval(() => this.updateRealtimeTimers(), 30000); 
                setInterval(() => this.refreshDashboardData(), 60000); 
                
                document.addEventListener('click', () => {
                    if (this.audioCtx.state === 'suspended') this.audioCtx.resume();
                }, { once: true });
            },

            playTone(freq, type, duration) {
                const osc = this.audioCtx.createOscillator();
                const gainNode = this.audioCtx.createGain();
                osc.connect(gainNode);
                gainNode.connect(this.audioCtx.destination);
                osc.type = type;
                osc.frequency.value = freq;
                gainNode.gain.setValueAtTime(0.1, this.audioCtx.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.0001, this.audioCtx.currentTime + duration);
                osc.start();
                osc.stop(this.audioCtx.currentTime + duration);
            },

            playAudio(type) {
                if (type === 'success') { this.playTone(800, 'sine', 0.1); setTimeout(() => this.playTone(1200, 'sine', 0.3), 100); }
                else if (type === 'error') { this.playTone(150, 'sawtooth', 0.3); }
                else if (type === 'notification') { this.playTone(500, 'triangle', 0.1); }
            },

            startClock() {
                const update = () => {
                    const now = new Date();
                    document.getElementById('clockTime').innerText = now.toLocaleTimeString('id-ID', { hour12: false });
                    document.getElementById('clockDate').innerText = now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
                };
                setInterval(update, 1000);
                update();
            },

            setupOfflineListener() {
                window.addEventListener('offline', () => document.getElementById('offlineIndicator').classList.remove('hidden'));
                window.addEventListener('online', () => {
                    document.getElementById('offlineIndicator').classList.add('hidden');
                    this.playAudio('notification');
                    Swal.fire({ icon: 'success', title: 'Terhubung Kembali', toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, customClass: { popup: 'rounded-2xl border border-emerald-200 shadow-lg bg-emerald-50 text-emerald-700 font-sans' } });
                });
            },

            showFeedback(msg, type) {
                const el = this.elements.scanFeedback;
                el.className = 'mt-6 p-4 rounded-2xl text-center text-sm font-bold animate-pulse transition-all shadow-sm ' + 
                    (type === 'success' ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 
                    (type === 'error' ? 'bg-rose-50 text-rose-600 border border-rose-200' : 'bg-elevate-soft text-elevate-primary border border-elevate-accent/30'));
                el.innerHTML = msg; el.classList.remove('hidden');
                setTimeout(() => el.classList.add('hidden'), 3000);
            },

            setProcessingState(loading) {
                this.isProcessing = loading;
                const spinner = document.getElementById('inputSpinner');
                if(loading) { 
                    this.elements.scannerInput.disabled = true; 
                    spinner.classList.remove('hidden'); 
                } else { 
                    this.elements.scannerInput.disabled = false; 
                    this.elements.scannerInput.focus(); 
                    spinner.classList.add('hidden'); 
                }
            },

            toggleCamera() {
                if (this.isCameraRunning) {
                    this.stopCamera();
                } else {
                    this.startCamera();
                }
            },

            startCamera() {
                const container = document.getElementById('cameraContainer');
                const btnText = document.getElementById('cameraText');
                const btnSwitch = document.getElementById('btnSwitchCamera');

                if (container) container.classList.remove('hidden');
                if (btnText) btnText.textContent = "Tutup Kamera";
                if (btnSwitch) btnSwitch.classList.remove('hidden');

                if (!this.html5QrCode) {
                    this.html5QrCode = new Html5Qrcode("reader");
                }

                const config = { 
                    fps: 15, 
                    qrbox: function(viewfinderWidth, viewfinderHeight) {
                        let minEdgeSize = Math.min(viewfinderWidth, viewfinderHeight);
                        let qrboxSize = Math.floor(minEdgeSize * 0.7);
                        return { width: qrboxSize, height: qrboxSize };
                    },
                    aspectRatio: 1.0,
                    experimentalFeatures: {
                        useBarCodeDetectorIfSupported: true
                    }
                };

                const cameraConstraints = { facingMode: this.currentFacingMode };

                this.html5QrCode.start(
                    cameraConstraints, 
                    config, 
                    (decodedText) => {
                        if (this.isProcessing || this.scanCooldown) return;
                        this.scanCooldown = true;
                        this.handleScan(decodedText).finally(() => {
                            setTimeout(() => { this.scanCooldown = false; }, 2500);
                        });
                    }
                ).then(() => { 
                    this.isCameraRunning = true; 
                }).catch(err => { 
                    console.warn("Camera start error:", err);
                    Swal.fire({
                        title: "Error Kamera", 
                        text: "Izin kamera diperlukan atau kamera tidak dapat diakses.", 
                        icon: "error", 
                        customClass: { popup: 'rounded-[2rem]' }
                    }); 
                    this.stopCamera();
                });
            },

            stopCamera() {
                const container = document.getElementById('cameraContainer');
                const btnText = document.getElementById('cameraText');
                const btnSwitch = document.getElementById('btnSwitchCamera');

                const cleanup = () => {
                    if (container) container.classList.add('hidden');
                    if (btnText) btnText.textContent = "Buka Kamera";
                    if (btnSwitch) btnSwitch.classList.add('hidden');
                    this.isCameraRunning = false;
                };

                if (this.html5QrCode && this.isCameraRunning) {
                    this.html5QrCode.stop().then(cleanup).catch(cleanup);
                } else {
                    cleanup();
                }
            },

            switchCamera() {
                if (!this.isCameraRunning) return;
                this.currentFacingMode = this.currentFacingMode === "environment" ? "user" : "environment";
                if (this.html5QrCode) {
                    this.html5QrCode.stop().then(() => {
                        this.isCameraRunning = false;
                        this.startCamera();
                    }).catch(() => {
                        this.isCameraRunning = false;
                        this.startCamera();
                    });
                }
            },

            async handleScan(code) {
                if(!code || this.isProcessing) return;
                this.setProcessingState(true);
                this.showFeedback('Memproses data...', 'info');

                try {
                    const res = await fetch('{{ route("permit.scan") }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.csrfToken },
                        body: JSON.stringify({ identifier: code })
                    });
                    const data = await res.json();
                    
                    if(!res.ok) throw new Error(data.message || 'Data tidak ditemukan');

                    if(data.mode === 'CHECK_IN') {
                        this.playAudio('success');
                        this.showFeedback(`✅ ${data.data.student.name} KEMBALI`, 'success');
                        await this.refreshDashboardData(); 
                        Swal.fire({ icon: 'success', title: 'Selamat Datang Kembali', text: `${data.data.student.name} (${data.data.duration} menit)`, timer: 2000, showConfirmButton: false, backdrop: `rgba(0,0,0,0.4)`, customClass: { popup: 'rounded-[2.5rem]' } });
                        this.elements.scannerInput.value = '';
                    } else {
                        this.playAudio('notification');
                        
                        const limitIzinHarian = 3; 
                        const countHariIni = data.data.student.today_permit_count || 0;

                        if (countHariIni >= limitIzinHarian) {
                            this.playAudio('error'); 
                            
                            Swal.fire({
                                icon: 'warning',
                                title: '⚠️ Red Zone Peringatan!',
                                html: `<div class="mt-4 text-sm text-elevate-dark font-medium">
                                        Siswa <b class="font-black">${data.data.student.name}</b> sudah izin keluar kelas sebanyak 
                                        <span class="text-rose-600 font-black text-xl mx-1">${countHariIni} KALI</span> hari ini.
                                       </div>
                                       <div class="mt-4 text-xs text-elevate-dark/60 font-bold">Apakah Anda yakin tetap ingin memberikan izin?</div>`,
                                showCancelButton: true,
                                confirmButtonColor: '#e11d48', 
                                cancelButtonColor: '#94a3b8', 
                                confirmButtonText: '<i class="ph-bold ph-warning"></i> Tetap Izinkan',
                                cancelButtonText: 'Batalkan',
                                reverseButtons: true,
                                customClass: {
                                    popup: 'rounded-[2.5rem] shadow-2xl',
                                    confirmButton: 'rounded-2xl font-bold px-8 py-3.5 flex items-center gap-2 border border-transparent shadow-lg shadow-rose-600/30 active:scale-95 transition-all',
                                    cancelButton: 'rounded-2xl font-bold px-8 py-3.5 border border-transparent'
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    this.showFeedback('Silakan pilih alasan...', 'info');
                                    this.openModal(data.data.student);
                                } else {
                                    this.showFeedback('Izin Dibatalkan.', 'error');
                                    this.elements.scannerInput.value = '';
                                    setTimeout(() => this.elements.scannerInput.focus(), 100);
                                }
                            });

                        } else {
                            this.showFeedback('Silakan pilih alasan...', 'info');
                            this.openModal(data.data.student);
                        }
                    }
                } catch (err) {
                    this.playAudio('error');
                    this.showFeedback(err.message, 'error');
                    this.elements.scannerInput.value = ''; 
                    this.elements.scannerInput.focus();
                } finally {
                    this.setProcessingState(false);
                }
            },

            openModalManual() {
                document.getElementById('manualSearchModal').classList.remove('hidden');
                document.getElementById('manualInputBox').focus();
            },

            submitManualSearch() {
                const val = document.getElementById('manualInputBox').value;
                if(val) {
                    this.handleScan(val);
                    document.getElementById('manualSearchModal').classList.add('hidden');
                    document.getElementById('manualInputBox').value = '';
                }
            },

            openModal(student) {
                document.getElementById('modalStudentName').textContent = student.name;
                document.getElementById('modalStudentClass').textContent = student.school_class?.name || 'Kelas Tidak Diketahui';
                document.getElementById('modalStudentId').value = student.id;
                document.querySelectorAll('input[name="reason_category"]').forEach(el => el.checked = false);
                document.querySelector('input[name="notes"]').value = '';
                this.elements.modal.classList.remove('hidden');
            },

            closeModal() {
                this.elements.modal.classList.add('hidden');
                document.getElementById('permitForm').reset();
                this.elements.scannerInput.focus();
            },

            async submitPermitManual() {
                const form = document.getElementById('permitForm');
                const formData = new FormData(form);
                const reason = formData.get('reason_category');
                if (!reason) { Swal.fire({ icon: 'warning', title: 'Pilih Alasan!', timer: 2000, customClass: { popup: 'rounded-[2rem]' } }); return; }

                const btn = document.getElementById('btnSubmitPermit');
                btn.disabled = true; btn.innerHTML = '<i class="ph-bold ph-spinner animate-spin text-xl"></i> Menyimpan...';

                try {
                    const payload = Object.fromEntries(formData.entries());
                    const res = await fetch('{{ route("permit.store") }}', {
                        method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.csrfToken }, body: JSON.stringify(payload)
                    });
                    const data = await res.json();
                    if(!res.ok) throw new Error(data.message);

                    this.closeModal(); 
                    this.playAudio('success'); 
                    this.elements.scannerInput.value = '';
                    await this.refreshDashboardData();

                    if (data.data.reason_category === 'Keluar Gerbang' && data.data.id) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Izin Keluar Gerbang Tercatat',
                            text: `${data.data.student.name} - Berikan slip ini ke satpam gerbang.`,
                            showCancelButton: true,
                            confirmButtonText: '<i class="ph-bold ph-printer"></i> Cetak Slip Sekarang',
                            cancelButtonText: 'Selesai',
                            confirmButtonColor: '#0284c7',
                            cancelButtonColor: '#94a3b8',
                            customClass: { popup: 'rounded-[2.5rem]' }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                this.openSlip(data.data.id);
                            }
                        });
                    } else {
                        Swal.fire({ icon: 'success', title: 'Izin Tercatat', text: `${data.data.student.name} - ${data.data.reason}`, timer: 2000, showConfirmButton: false, customClass: { popup: 'rounded-[2.5rem]' } });
                    }
                } catch (err) {
                    this.playAudio('error'); Swal.fire({ icon: 'error', title: 'Gagal', text: err.message, customClass: { popup: 'rounded-[2.5rem]' } });
                } finally {
                    btn.disabled = false; btn.innerHTML = '<span>Berikan Izin</span> <i class="ph-bold ph-arrow-right"></i>';
                    setTimeout(() => this.elements.scannerInput.focus(), 100);
                }
            },

            async returnPermit(permitId, studentName) {
                const confirmed = await Swal.fire({
                    title: 'Konfirmasi Siswa Kembali',
                    html: `Tandai siswa <b>${studentName}</b> telah kembali ke kelas?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Sudah Kembali',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#94a3b8',
                    customClass: { popup: 'rounded-[2rem]' }
                });

                if (!confirmed.isConfirmed) return;

                try {
                    const res = await fetch(`/permit/${permitId}/return`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken,
                            'Accept': 'application/json'
                        }
                    });
                    const data = await res.json();
                    if (!res.ok) throw new Error(data.message || 'Gagal memproses pengembalian');

                    this.playAudio('success');
                    await this.refreshDashboardData();
                    Swal.fire({
                        icon: 'success',
                        title: 'Siswa Kembali!',
                        text: `${studentName} telah kembali (Durasi: ${data.data?.duration_minutes ?? '-'} mnt)`,
                        timer: 2500,
                        showConfirmButton: false,
                        customClass: { popup: 'rounded-[2rem]' }
                    });
                } catch (err) {
                    this.playAudio('error');
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: err.message,
                        customClass: { popup: 'rounded-[2rem]' }
                    });
                }
            },

            openSlip(permitId) {
                const url = `/permit/${permitId}/slip`;
                window.open(url, '_blank', 'width=460,height=650,resizable=yes,scrollbars=yes');
            },

            async refreshDashboardData() {
                if(navigator.onLine === false) return; 

                const container1 = document.getElementById('activePermitsContainer');
                const container2 = document.getElementById('historyContainer');
                const badge = document.getElementById('activeCountBadge');

                try {
                    const response = await fetch(window.location.href);
                    const text = await response.text();
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(text, 'text/html');
                    if(container1) container1.innerHTML = doc.getElementById('activePermitsContainer').innerHTML;
                    if(container2) container2.innerHTML = doc.getElementById('historyContainer').innerHTML;
                    if(badge) badge.innerHTML = doc.getElementById('activeCountBadge').innerHTML;
                } catch (error) { 
                    console.warn('Silent Refresh failed', error); 
                }
            },

            updateRealtimeTimers() {
                document.querySelectorAll('.live-timer').forEach(el => {
                    const diffMins = Math.floor((new Date().getTime() - new Date(el.dataset.start).getTime()) / 60000);
                    const numberDisplay = el.querySelector('.timer-number');
                    if(numberDisplay) numberDisplay.textContent = diffMins;
                    if(diffMins > 15) { 
                        const card = el.closest('.permit-card');
                        if(card) {
                            card.classList.add('border-rose-200', 'shadow-[0_4px_20px_-4px_rgba(225,29,72,0.15)]');
                            card.classList.remove('border-slate-200', 'shadow-sm');
                        }
                        const timerText = numberDisplay.closest('span');
                        if(timerText) {
                            timerText.classList.remove('text-elevate-dark');
                            timerText.classList.add('text-rose-600');
                        }
                    }
                });
            },
            
            setupEventListeners() {
                const { scannerInput, kioskModeToggle, focusStatus, modal } = this.elements;
                
                scannerInput.addEventListener('focus', () => { 
                    focusStatus.classList.remove('hidden');
                    focusStatus.classList.add('flex');
                });
                
                scannerInput.addEventListener('blur', () => {
                    focusStatus.classList.add('hidden');
                    focusStatus.classList.remove('flex');
                    if (kioskModeToggle.checked && modal.classList.contains('hidden') && document.getElementById('manualSearchModal').classList.contains('hidden')) {
                        setTimeout(() => { 
                            if(document.activeElement.tagName !== "INPUT" && document.activeElement.tagName !== "TEXTAREA") scannerInput.focus(); 
                        }, 200); 
                    }
                });

                document.addEventListener('click', (e) => {
                    if (kioskModeToggle.checked) {
                        const isInteractive = e.target.closest('input, button, a, #permitModal, #manualSearchModal, label');
                        if (!isInteractive && modal.classList.contains('hidden') && document.getElementById('manualSearchModal').classList.contains('hidden')) {
                            scannerInput.focus();
                        }
                    }
                });

                scannerInput.addEventListener('keypress', (e) => { 
                    if (e.key === 'Enter') { e.preventDefault(); this.handleScan(scannerInput.value); } 
                });

                document.getElementById('btnSearch').addEventListener('click', () => this.handleScan(scannerInput.value));
            }
        };

        document.addEventListener('DOMContentLoaded', () => PiketApp.init());
    </script>
</x-app-layout>