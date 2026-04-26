<x-app-layout>
    {{-- LIBRARIES --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    @push('styles')
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        
        /* --- FLUENT UI SHADOWS --- */
        .fluent-card { box-shadow: 0 1.6px 3.6px 0 rgba(0, 0, 0, 0.132), 0 0.3px 0.9px 0 rgba(0, 0, 0, 0.108); border: 1px solid rgba(0, 0, 0, 0.05); }
        .fluent-modal { box-shadow: 0 25.6px 57.6px 0 rgba(0, 0, 0, 0.22), 0 4.8px 14.4px 0 rgba(0, 0, 0, 0.18); border: 1px solid rgba(0, 0, 0, 0.05); }
        
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        
        /* =========================================================
           PERBAIKAN KAMERA RESPONSIVE & ANTI GEPENG (MOBILE FIX)
           ========================================================= */
        #reader { 
            width: 100% !important; 
            min-height: 350px !important; 
            border: none !important; 
            border-radius: 0.75rem; 
            overflow: hidden; 
            position: relative; 
            background: #0f172a; 
        }
        
        #reader__scan_region { 
            width: 100% !important; 
            min-height: 350px !important;
            background: transparent !important; 
        }

        #reader video, 
        #reader canvas { 
            width: 100% !important; 
            height: 100% !important; 
            min-height: 350px !important;
            object-fit: cover !important; /* Memaksa kamera memenuhi container tanpa gepeng */
            display: block !important;
            border-radius: 0.75rem;
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
        }
        
        #reader__dashboard_section_csr span, #reader__dashboard_section_swaplink { display: none !important; }
        
        .input-glow:focus { box-shadow: 0 0 0 4px rgba(82, 149, 255, 0.2); }
        .digital-clock { font-feature-settings: "tnum"; font-variant-numeric: tabular-nums; }
    </style>
    @endpush

    <div class="py-8 sm:py-10 relative min-h-screen font-sans text-slate-800 pb-32">
        
        {{-- Indikator Offline --}}
        <div id="offlineIndicator" class="fixed bottom-6 right-6 z-50 hidden animate-bounce">
            <div class="bg-[#D13438] text-white px-4 py-3 rounded-xl shadow-2xl flex items-center gap-3 border border-[#F4C3C9] fluent-card">
                <i class="ph-bold ph-wifi-slash text-xl"></i>
                <div>
                    <div class="font-bold text-sm">Koneksi Terputus</div>
                    <div class="text-[10px] opacity-90">Menunggu sambungan...</div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 md:space-y-8">
            
            {{-- HERO SECTION (MICROSOFT ELEVATE THEME) --}}
            <div class="animate-enter relative rounded-xl bg-gradient-to-br from-[#25D0FF] via-[#5295FF] to-[#FFC9B9] p-6 md:p-10 text-[#2A3B52] shadow-[0_10px_40px_-10px_rgba(37,208,255,0.4)] overflow-hidden group border border-white/40">
                
                <div class="absolute inset-0 opacity-[0.03] bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none mix-blend-overlay"></div>

                <div class="relative z-10 flex flex-col lg:flex-row gap-8 items-start lg:items-center justify-between">
                    
                    {{-- KIRI: Judul & Intro --}}
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5 w-full lg:w-auto">
                        <div class="w-16 h-16 rounded-xl bg-white/40 backdrop-blur-md flex items-center justify-center border border-white/50 shadow-sm shrink-0">
                            <i class="ph-duotone ph-shield-check text-4xl text-[#2A3B52]"></i>
                        </div>
                        <div>
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/40 border border-white/50 text-[#2A3B52] text-[10px] font-bold uppercase tracking-wider mb-2 backdrop-blur-sm shadow-sm">
                                <span class="relative flex h-2 w-2">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#107C10] opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-2 w-2 bg-[#107C10]"></span>
                                </span>
                                Sistem Monitoring Realtime
                            </div>
                            <h1 class="text-3xl md:text-4xl font-extrabold text-[#2A3B52] tracking-tight leading-none">
                                Pos Guru Piket
                            </h1>
                            <p class="text-[#2A3B52]/80 text-sm mt-2 max-w-md font-medium">
                                Kelola izin keluar masuk siswa dengan cepat dan akurat.
                            </p>
                        </div>
                    </div>

                    {{-- KANAN: WIDGET JAM --}}
                    <div class="bg-white/40 backdrop-blur-md border border-white/50 p-5 rounded-xl relative overflow-hidden flex items-center justify-between gap-6 w-full lg:w-auto shrink-0 mt-4 lg:mt-0 shadow-sm">
                        <div class="absolute top-0 right-0 p-4 opacity-5 text-[#2A3B52] pointer-events-none">
                            <i class="ph-fill ph-clock text-7xl"></i>
                        </div>

                        <div>
                            <h3 class="text-xs font-bold text-[#2A3B52] uppercase tracking-widest mb-1 flex items-center gap-2 relative z-10">
                                <i class="ph-bold ph-calendar-blank"></i> Waktu Sekarang
                            </h3>
                            <div id="clockDate" class="text-[#2A3B52] text-sm font-medium relative z-10 opacity-90">...</div>
                        </div>

                        <div class="text-right relative z-10 bg-white/50 px-4 py-2 rounded-lg border border-white/40 shrink-0 shadow-sm">
                            <div id="clockTime" class="text-3xl sm:text-4xl font-black text-[#2A3B52] digital-clock tracking-tight leading-none">00:00:00</div>
                            <div class="text-[10px] font-bold text-[#107C10] mt-1 uppercase tracking-wider text-right">WIB / GMT+7</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- MAIN CONTENT GRID --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 md:gap-8 items-start">
                
                {{-- KOLOM KIRI: SCANNER & INPUT (Sticky) --}}
                <div class="lg:col-span-5 space-y-6 md:space-y-8 lg:sticky lg:top-6">
                    
                    {{-- SCANNER CARD --}}
                    <div class="bg-white p-6 md:p-8 rounded-xl fluent-card relative overflow-hidden animate-enter delay-100 group">
                        
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="font-bold text-[#2A3B52] flex items-center gap-3 text-lg">
                                <div class="w-8 h-8 rounded-lg bg-[#F3F9FD] text-[#5295FF] border border-[#D0E7F8] flex items-center justify-center">
                                    <i class="ph-bold ph-qr-code"></i>
                                </div>
                                Scan / Input
                            </h3>
                            
                            {{-- SWITCH MODE --}}
                            <div class="flex items-center gap-2 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100 shadow-inner" title="Auto Focus RFID">
                                <label class="flex items-center cursor-pointer relative">
                                    <input type="checkbox" id="kioskModeToggle" class="sr-only peer" checked>
                                    <div class="w-7 h-4 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:bg-[#5295FF]"></div>
                                    <span class="ml-2 text-[10px] font-black text-slate-500 uppercase tracking-wide">RFID Mode</span>
                                </label>
                            </div>
                        </div>

                        {{-- SCANNER AREA --}}
                        <div class="space-y-4">
                            <div id="cameraContainer" class="hidden mb-4 relative bg-slate-900 rounded-xl overflow-hidden shadow-inner border-4 border-slate-900 ring-1 ring-white/20">
                                <div id="reader" class="w-full bg-black"></div>
                                <div class="absolute bottom-4 left-0 right-0 text-center pointer-events-none z-10">
                                    <span class="bg-[#2A3B52]/80 text-white text-[10px] px-3 py-1.5 rounded-lg backdrop-blur-md border border-[#2A3B52] font-bold shadow-sm">
                                        Arahkan QR Code ke Kamera
                                    </span>
                                </div>
                                <!-- Scan line animation -->
                                <div class="absolute top-0 left-0 w-full h-1 bg-[#5295FF] shadow-[0_0_20px_rgba(82,149,255,0.8)] animate-[scan_2s_infinite] z-0 opacity-80"></div>
                            </div>

                            {{-- INPUT FIELD --}}
                            <div class="relative group/input">
                                <input type="text" id="scannerInput" 
                                    class="w-full pl-14 pr-12 py-4 md:py-5 rounded-xl border-2 border-slate-100 bg-slate-50 focus:bg-white focus:border-[#5295FF] focus:ring-0 font-mono text-lg md:text-xl font-bold text-slate-800 transition-all placeholder:text-slate-400 placeholder:font-sans placeholder:font-medium input-glow shadow-sm group-hover/input:border-slate-200" 
                                    placeholder="Tempel Kartu / NIS..." autofocus autocomplete="off">
                                
                                <div class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within/input:text-[#5295FF] transition-colors">
                                    <i class="ph-duotone ph-barcode text-2xl"></i>
                                </div>
                                
                                <div id="inputSpinner" class="hidden absolute right-5 top-1/2 -translate-y-1/2 text-[#5295FF]">
                                    <i class="ph-bold ph-spinner animate-spin text-xl"></i>
                                </div>
                                
                                <button id="btnSearch" class="absolute right-3 top-1/2 -translate-y-1/2 bg-white shadow-sm border border-slate-200 text-[#5295FF] p-2 md:p-2.5 rounded-lg hover:bg-[#F3F9FD] hover:border-[#D0E7F8] transition cursor-pointer active:scale-95">
                                    <i class="ph-bold ph-arrow-right"></i>
                                </button>
                            </div>

                            {{-- ACTION BUTTONS --}}
                            <div class="grid grid-cols-2 gap-3">
                                <button onclick="PiketApp.toggleCamera()" id="btnCamera" class="col-span-1 text-xs font-bold px-4 py-3.5 bg-white hover:bg-[#F3F9FD] text-slate-600 hover:text-[#5295FF] hover:border-[#D0E7F8] rounded-xl transition flex items-center justify-center gap-2 border border-slate-200 shadow-sm active:scale-95">
                                    <i class="ph-bold ph-camera text-lg"></i> <span id="cameraText">Buka Kamera</span>
                                </button>
                                <button onclick="PiketApp.openModalManual()" class="col-span-1 text-xs font-bold px-4 py-3.5 bg-[#F3F9FD] hover:bg-[#E0F0FC] text-[#5295FF] border border-[#D0E7F8] rounded-xl transition flex items-center justify-center gap-2 shadow-sm active:scale-95">
                                    <i class="ph-bold ph-keyboard text-lg"></i> Input Manual
                                </button>
                            </div>
                        </div>

                        {{-- FEEDBACK & STATUS --}}
                        <div id="scanFeedback" class="hidden mt-4 p-4 rounded-xl text-center text-sm font-bold animate-pulse transition-all shadow-sm"></div>
                        
                        <div class="mt-4 flex justify-between items-center px-1">
                             <span id="focusStatus" class="text-[9px] font-black text-[#107C10] bg-[#DFF6DD] border border-[#B7DFB9] px-2 py-1 rounded-lg hidden uppercase tracking-wider items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#107C10] animate-pulse inline-block"></span> Ready
                            </span>
                            <div class="text-[10px] text-slate-400 font-medium ml-auto">
                                Petugas: <span class="text-[#2A3B52] font-bold">{{ Auth::user()->name ?? 'Admin' }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- RECENT HISTORY (Compact List) --}}
                    <div class="bg-white p-6 md:p-8 rounded-xl fluent-card animate-enter delay-200">
                        <h3 class="font-bold text-[#2A3B52] mb-4 flex items-center gap-2 text-sm uppercase tracking-wider">
                            <i class="ph-duotone ph-clock-counter-clockwise text-[#5295FF] text-lg"></i> Baru Saja Kembali
                        </h3>
                        
                        <div id="historyContainer" class="space-y-3 max-h-[250px] overflow-y-auto custom-scrollbar pr-2">
                            @forelse($todayHistory ?? [] as $history)
                            <div class="flex items-center justify-between p-3 rounded-xl border border-slate-100 hover:border-[#B7DFB9] hover:bg-[#DFF6DD]/30 transition-all duration-300 group">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center font-black text-[#2A3B52] text-xs shadow-sm border border-slate-200 group-hover:bg-[#5295FF] group-hover:text-white group-hover:border-[#5295FF] transition-colors shrink-0">
                                        {{ substr($history->student->name, 0, 1) }}
                                    </div>
                                    <div class="min-w-0 pr-2">
                                        <div class="text-sm font-bold text-[#2A3B52] line-clamp-1 group-hover:text-[#5295FF] transition-colors">{{ $history->student->name }}</div>
                                        <div class="text-[10px] text-slate-500 font-medium truncate">
                                            {{ $history->reason_category }} <span class="mx-1 text-slate-300">•</span> {{ $history->duration_minutes }} m
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-[#DFF6DD] text-[#107C10] text-[10px] font-bold border border-[#B7DFB9]">
                                        <i class="ph-bold ph-check"></i> {{ $history->time_in->format('H:i') }}
                                    </span>
                                </div>
                            </div>
                            @empty
                            <div class="flex flex-col items-center justify-center py-8 text-slate-400 border border-dashed border-slate-200 rounded-xl">
                                <i class="ph-duotone ph-coffee text-2xl mb-1 opacity-50"></i>
                                <span class="text-xs font-medium">Belum ada riwayat.</span>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: LIVE MONITORING (Cards) --}}
                <div class="lg:col-span-7 animate-enter delay-200 h-full flex flex-col">
                    <div class="bg-white rounded-xl fluent-card flex flex-col min-h-[500px] lg:h-full relative overflow-hidden">
                        
                        {{-- HEADER MONITORING --}}
                        <div class="p-6 md:p-8 border-b border-slate-100 bg-white/80 backdrop-blur-md sticky top-0 z-20 flex justify-between items-center">
                            <div>
                                <h3 class="font-extrabold text-[#2A3B52] text-lg sm:text-xl flex items-center gap-2">
                                    <span class="relative flex h-3 w-3">
                                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#D83B01] opacity-75"></span>
                                      <span class="relative inline-flex rounded-full h-3 w-3 bg-[#D83B01]"></span>
                                    </span>
                                    Sedang Di Luar
                                </h3>
                                <p class="text-xs text-slate-500 font-medium mt-1 ml-5">Siswa yang belum kembali ke kelas.</p>
                            </div>
                            
                            <div id="activeCountBadge" class="bg-[#2A3B52] text-white px-4 sm:px-5 py-2 rounded-xl shadow-md text-center min-w-[70px] sm:min-w-[80px] shrink-0 border border-transparent">
                                <span class="block text-xl sm:text-2xl font-black leading-none">{{ collect($activePermits ?? [])->count() }}</span>
                                <span class="text-[9px] font-bold uppercase tracking-wider text-[#5295FF]">Siswa</span>
                            </div>
                        </div>
                        
                        {{-- GRID CARD --}}
                        <div id="activePermitsContainer" class="flex-1 overflow-y-auto custom-scrollbar p-6 bg-slate-50/50">
                            @if(collect($activePermits ?? [])->count() > 0)
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    @foreach($activePermits as $permit)
                                    <div class="permit-card group relative bg-white p-5 rounded-xl border transition-all duration-300 flex flex-col justify-between shadow-sm hover:-translate-y-1
                                        {{ $permit->is_overdue 
                                            ? 'border-[#F4C3C9] hover:border-[#D13438] hover:shadow-md' 
                                            : 'border-slate-200 hover:border-[#5295FF] hover:shadow-md' 
                                        }}">
                                        
                                        @if($permit->is_overdue)
                                            <div class="absolute -top-2 -right-2 bg-[#D13438] text-white text-[10px] font-bold px-3 py-1 rounded-lg shadow-sm animate-pulse z-10 flex items-center gap-1 border border-white">
                                                <i class="ph-bold ph-warning"></i> TELAT
                                            </div>
                                        @endif

                                        <div class="flex items-start gap-4 mb-4">
                                            <div class="w-12 h-12 rounded-lg flex-shrink-0 flex items-center justify-center text-lg font-bold shadow-sm transition-colors border
                                                {{ $permit->is_overdue ? 'bg-[#FDE7E9] text-[#D13438] border-[#F4C3C9]' : 'bg-[#F3F9FD] text-[#5295FF] border-[#D0E7F8] group-hover:bg-[#5295FF] group-hover:text-white' }}">
                                                {{ substr($permit->student->name, 0, 1) }}
                                            </div>
                                            <div class="min-w-0 pr-2">
                                                <h4 class="font-bold text-[#2A3B52] leading-snug truncate text-sm md:text-base">{{ $permit->student->name }}</h4>
                                                <p class="text-xs text-slate-500 font-medium mt-0.5 flex items-center gap-1">
                                                    <i class="ph-bold ph-student"></i> <span class="truncate">{{ $permit->student->schoolClass->name ?? 'Kelas -' }}</span>
                                                    <span class="text-slate-300 mx-0.5">|</span>
                                                    <span class="font-mono">{{ $permit->student->student_id }}</span>
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <div class="space-y-3">
                                            {{-- Reason Box --}}
                                            <div class="bg-slate-50 rounded-lg p-3 border border-slate-100 group-hover:bg-white transition-colors">
                                                <div class="flex justify-between items-center mb-1">
                                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Keperluan</span>
                                                    <span class="text-[10px] font-bold text-[#2A3B52] px-2 py-0.5 bg-white rounded-md border border-slate-200 shadow-sm">{{ $permit->reason_category }}</span>
                                                </div>
                                                @if($permit->notes)
                                                <p class="text-xs text-slate-500 italic truncate mt-1">"{{ $permit->notes }}"</p>
                                                @endif
                                            </div>

                                            <div class="flex items-end justify-between pt-2 border-t border-slate-50">
                                                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">
                                                    Keluar <span class="text-[#2A3B52] font-mono text-xs ml-1">{{ $permit->time_out->format('H:i') }}</span>
                                                </div>
                                                <div class="live-timer text-right" data-start="{{ $permit->time_out }}">
                                                    <span class="text-2xl font-black font-mono leading-none tracking-tight {{ $permit->is_overdue ? 'text-[#D13438]' : 'text-[#2A3B52]' }}">
                                                        <span class="timer-number">{{ $permit->minutes_elapsed }}</span><span class="text-sm font-bold opacity-50 ml-0.5">m</span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="flex flex-col items-center justify-center h-full text-slate-400 py-16 lg:py-24">
                                    <div class="w-24 h-24 md:w-32 md:h-32 bg-white rounded-full flex items-center justify-center mb-6 shadow-sm border border-slate-100 group">
                                        <i class="ph-duotone ph-student text-5xl md:text-6xl text-slate-300 group-hover:scale-110 transition-transform duration-500"></i>
                                    </div>
                                    <h4 class="text-lg font-bold text-[#2A3B52]">Kelas Kondusif</h4>
                                    <p class="text-sm max-w-xs text-center mt-2 opacity-70">Semua siswa berada di dalam kelas saat ini.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL IZIN KELUAR --}}
    <div id="permitModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 sm:p-6">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="PiketApp.closeModal()"></div>
        <div class="bg-white w-full max-w-lg rounded-xl fluent-modal relative border border-slate-100 flex flex-col max-h-[90vh] overflow-hidden z-10 animate-enter">
            
            <button type="button" onclick="PiketApp.closeModal()" class="absolute top-4 right-4 sm:top-6 sm:right-6 text-slate-400 hover:text-[#D13438] transition cursor-pointer z-20 bg-white shadow-sm border border-slate-100 hover:bg-[#FDE7E9] p-2 rounded-lg">
                <i class="ph-bold ph-x text-xl"></i>
            </button>
            
            <div class="overflow-y-auto custom-scrollbar p-6 sm:p-8 flex-1">
                <div class="text-center mb-6 sm:mb-8 mt-2">
                    <div class="w-16 h-16 bg-[#F3F9FD] border border-[#D0E7F8] text-[#5295FF] rounded-xl rotate-3 flex items-center justify-center mx-auto mb-4 text-3xl shadow-sm">
                        <i class="ph-duotone ph-door-open"></i>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-black text-[#2A3B52] tracking-tight">Izin Keluar Kelas</h3>
                    <div class="mt-4 bg-slate-50 rounded-xl p-4 border border-slate-100 inline-block w-full">
                        <p id="modalStudentName" class="text-[#5295FF] font-black text-lg sm:text-xl leading-tight">Nama Siswa</p>
                        <p id="modalStudentClass" class="text-xs text-slate-500 font-mono mt-1 font-bold uppercase tracking-wider">Kelas Siswa</p>
                    </div>
                </div>

                <form id="permitForm" onsubmit="event.preventDefault(); PiketApp.submitPermitManual();">
                    <input type="hidden" id="modalStudentId" name="student_id">
                    
                    <div class="grid grid-cols-2 gap-3 mb-6">
                        @foreach(['Toilet', 'UKS', 'Barang Tertinggal', 'Panggilan Guru', 'Dispensasi', 'Lainnya'] as $reason)
                        <label class="cursor-pointer relative group">
                            <input type="radio" name="reason_category" value="{{ $reason }}" class="peer sr-only">
                            <div class="p-3.5 rounded-xl border border-slate-200 text-center text-xs font-bold text-slate-600 
                                        group-hover:bg-slate-50 group-hover:border-slate-300
                                        peer-checked:border-[#5295FF] peer-checked:bg-[#F3F9FD] peer-checked:text-[#5295FF] 
                                        transition-all duration-200 shadow-sm flex items-center justify-center h-full">
                                {{ $reason }}
                            </div>
                            <div class="absolute -top-2 -right-2 bg-[#2A3B52] text-white rounded-full p-1 opacity-0 peer-checked:opacity-100 transition-all scale-0 peer-checked:scale-100 transform duration-200 shadow-sm ring-2 ring-white">
                                <i class="ph-bold ph-check text-xs"></i>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-2 ml-1">Catatan Tambahan</label>
                        <input type="text" name="notes" class="w-full rounded-xl border-slate-200 focus:border-[#5295FF] focus:ring-0 text-sm py-3 px-4 bg-slate-50 focus:bg-white transition-colors placeholder:text-slate-300 font-medium" placeholder="Contoh: Sakit perut, dipanggil Bu Ani...">
                    </div>
                    
                    <button type="submit" id="btnSubmitPermit" class="w-full py-4 rounded-xl bg-[#2A3B52] text-white font-bold text-lg hover:bg-[#182436] active:scale-95 transition-all shadow-md flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed border border-transparent">
                        <span>Berikan Izin</span>
                        <i class="ph-bold ph-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL PENCARIAN MANUAL --}}
    <div id="manualSearchModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 sm:p-6">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="document.getElementById('manualSearchModal').classList.add('hidden')"></div>
        <div class="bg-white w-full max-w-md rounded-xl fluent-modal p-6 sm:p-8 relative z-10 animate-enter border border-slate-100">
            <h3 class="font-extrabold text-xl mb-2 text-[#2A3B52]">Input Manual</h3>
            <p class="text-sm text-slate-500 mb-6 leading-relaxed">Masukkan NIS atau Nama siswa jika kartu tertinggal atau rusak.</p>
            
            <div class="relative mb-6">
                <input type="text" id="manualInputBox" class="w-full pl-12 pr-4 py-4 rounded-xl border border-slate-200 focus:border-[#5295FF] focus:ring-0 font-bold text-slate-700 bg-slate-50 focus:bg-white transition-colors" placeholder="Ketik Nama / NIS...">
                <i class="ph-bold ph-keyboard absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl"></i>
            </div>
            
            <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 mt-4">
                <button onclick="document.getElementById('manualSearchModal').classList.add('hidden')" class="w-full sm:w-auto px-5 py-3 sm:py-2.5 rounded-xl text-slate-500 font-bold hover:bg-slate-100 transition-colors border border-transparent">Batal</button>
                <button onclick="PiketApp.submitManualSearch()" class="w-full sm:w-auto px-6 py-3 sm:py-2.5 rounded-xl bg-[#2A3B52] text-white font-bold hover:bg-[#182436] shadow-md transition-all active:scale-95 text-center border border-transparent">Cari Data</button>
            </div>
        </div>
    </div>

    {{-- JAVASCRIPT LOGIC --}}
    <script>
        const PiketApp = {
            csrfToken: '{{ csrf_token() }}',
            isProcessing: false,
            isCameraRunning: false,
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
                    Swal.fire({ icon: 'success', title: 'Terhubung Kembali', toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, customClass: { popup: 'rounded-xl border border-[#B7DFB9] shadow-sm bg-[#DFF6DD] text-[#107C10]' } });
                });
            },

            showFeedback(msg, type) {
                const el = this.elements.scanFeedback;
                el.className = 'mt-4 p-4 rounded-xl text-center text-sm font-bold animate-pulse transition-all shadow-sm ' + 
                    (type === 'success' ? 'bg-[#DFF6DD] text-[#107C10] border border-[#B7DFB9]' : 
                    (type === 'error' ? 'bg-[#FDE7E9] text-[#D13438] border border-[#F4C3C9]' : 'bg-[#F3F9FD] text-[#5295FF] border border-[#D0E7F8]'));
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
                const container = document.getElementById('cameraContainer');
                const btnText = document.getElementById('cameraText');
                
                if (this.isCameraRunning) {
                    this.html5QrCode.stop().then(() => {
                        container.classList.add('hidden');
                        btnText.textContent = "Buka Kamera";
                        this.isCameraRunning = false;
                        this.html5QrCode = null;
                    });
                } else {
                    container.classList.remove('hidden');
                    btnText.textContent = "Tutup Kamera";
                    this.html5QrCode = new Html5Qrcode("reader");
                    this.html5QrCode.start(
                        { facingMode: "environment" }, 
                        { fps: 10, qrbox: { width: 250, height: 250 } }, 
                        (decodedText) => {
                            if(this.isProcessing) return;
                            this.html5QrCode.pause(); 
                            this.handleScan(decodedText).then(() => { 
                                setTimeout(() => { if(this.isCameraRunning) this.html5QrCode.resume(); }, 2000); 
                            });
                        }
                    ).then(() => { this.isCameraRunning = true; })
                     .catch(err => { Swal.fire({title: "Error Kamera", text: "Izin kamera diperlukan.", icon: "error", customClass: {popup: 'fluent-modal rounded-xl'}}); container.classList.add('hidden'); });
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
                        Swal.fire({ icon: 'success', title: 'Selamat Datang Kembali', text: `${data.data.student.name} (${data.data.duration} menit)`, timer: 2000, showConfirmButton: false, backdrop: `rgba(0,0,0,0.4)`, customClass: { popup: 'rounded-xl fluent-modal' } });
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
                                html: `<div class="mt-2 text-sm text-slate-600">
                                        Siswa <b>${data.data.student.name}</b> sudah izin keluar kelas sebanyak 
                                        <span class="text-[#D13438] font-black text-lg mx-1">${countHariIni} KALI</span> hari ini.
                                       </div>
                                       <div class="mt-3 text-xs text-slate-400">Apakah Anda yakin tetap ingin memberikan izin?</div>`,
                                showCancelButton: true,
                                confirmButtonColor: '#D13438', 
                                cancelButtonColor: '#94a3b8', 
                                confirmButtonText: '<i class="ph-bold ph-warning"></i> Tetap Izinkan',
                                cancelButtonText: 'Batalkan',
                                reverseButtons: true,
                                customClass: {
                                    popup: 'rounded-xl fluent-modal',
                                    confirmButton: 'rounded-xl font-bold px-6 py-3 flex items-center gap-2 border border-transparent',
                                    cancelButton: 'rounded-xl font-bold px-6 py-3 border border-transparent'
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
                if (!reason) { Swal.fire({ icon: 'warning', title: 'Pilih Alasan!', timer: 2000, customClass: { popup: 'rounded-xl fluent-modal' } }); return; }

                const btn = document.getElementById('btnSubmitPermit');
                btn.disabled = true; btn.innerHTML = '<i class="ph-bold ph-spinner animate-spin"></i> Menyimpan...';

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
                    Swal.fire({ icon: 'success', title: 'Izin Tercatat', text: `${data.data.student.name} - ${data.data.reason}`, timer: 1500, showConfirmButton: false, customClass: { popup: 'rounded-xl fluent-modal' } });
                } catch (err) {
                    this.playAudio('error'); Swal.fire({ icon: 'error', title: 'Gagal', text: err.message, customClass: { popup: 'rounded-xl fluent-modal' } });
                } finally {
                    btn.disabled = false; btn.innerHTML = '<span>Berikan Izin</span> <i class="ph-bold ph-arrow-right"></i>';
                    setTimeout(() => this.elements.scannerInput.focus(), 100);
                }
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
                            card.classList.add('border-[#F4C3C9]', 'shadow-[0_4px_20px_-4px_rgba(209,52,56,0.15)]');
                            card.classList.remove('border-slate-100', 'shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)]');
                        }
                        const timerText = numberDisplay.closest('span');
                        if(timerText) {
                            timerText.classList.remove('text-[#2A3B52]');
                            timerText.classList.add('text-[#D13438]');
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