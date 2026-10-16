<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Signage & Display Informasi Sekolah</title>

    <!-- TailwindCSS & Alpine.js -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <!-- Font Figtree -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Figtree', 'ui-sans-serif', 'system-ui', '-apple-system', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'sans-serif'],
                    },
                    colors: {
                        elevate: {
                            dark: '#2c3f61',      
                            primary: '#0d52a1',   
                            accent: '#56bbf1',    
                            surface: '#ffffff',   
                            soft: '#e5eff5',      
                            peach: {
                                light: '#f4d1c0',
                                DEFAULT: '#f9a282', 
                                dark: '#c86845',
                            },
                        },
                    },
                    backgroundImage: {
                        'elevate-gradient-main': 'linear-gradient(120deg, #1cb5e0 0%, #e0f2fe 40%, #fff0e8 70%, #ffbca5 100%)',
                        'elevate-gradient-card': 'linear-gradient(135deg, #fffcf9 0%, #fff0e8 100%)',
                    },
                },
            },
        }
    </script>

    <style>
        /* Smooth Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: rgba(255, 255, 255, 0.05); }
        ::-webkit-scrollbar-thumb { background: rgba(86, 187, 241, 0.35); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #56bbf1; }
        [x-cloak] { display: none !important; }

        @keyframes enter {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-enter { animation: enter .5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .animate-float { animation: float 3s ease-in-out infinite; }

        /* Running Text Ticker Animation */
        @keyframes marquee {
            0% { transform: translateX(0%); }
            100% { transform: translateX(-50%); }
        }
        .animate-marquee {
            display: inline-flex;
            white-space: nowrap;
            animation: marquee 45s linear infinite;
        }
        .animate-marquee:hover {
            animation-play-state: paused;
        }
    </style>
</head>
<body class="bg-[#021124] text-white font-sans antialiased h-screen overflow-hidden flex flex-col selection:bg-elevate-accent selection:text-[#021124] relative">

    <!-- GLOBAL FIXED BACKGROUND -->
    <div class="fixed inset-0 z-0 pointer-events-none bg-[#021124] overflow-hidden">
        {{-- Background Image Sekolah --}}
        <div class="absolute inset-0 bg-cover bg-center opacity-70 filter brightness-85 contrast-110" style="background-image: url('{{ asset('images/netila.jpg') }}');"></div>
        
        {{-- Soft Navy Gradient & Vignette Overlay --}}
        <div class="absolute inset-0 bg-gradient-to-b from-[#021124]/75 via-[#021124]/50 to-[#021124]/80"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_rgba(13,82,161,0.2)_0%,_rgba(2,17,36,0.55)_60%,_#021124_100%)]"></div>

        {{-- Glowing Ambient Orbs --}}
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-elevate-accent/10 rounded-full blur-[160px] pointer-events-none animate-pulse"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-elevate-primary/15 rounded-full blur-[160px] pointer-events-none animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    <!-- LAYER START DISPLAY (KETUK UNTUK AUDIO & FULLSCREEN) -->
    <div id="start-overlay" class="fixed inset-0 z-[100] bg-[#021124] flex flex-col items-center justify-center cursor-pointer transition-all duration-700 backdrop-blur-sm overflow-hidden" onclick="startDisplay()">
        <div class="absolute inset-0 bg-cover bg-center opacity-65 filter brightness-80 contrast-110 pointer-events-none" style="background-image: url('{{ asset('images/netila.jpg') }}');"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-[#021124]/80 via-[#021124]/55 to-[#021124]/85 pointer-events-none"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_rgba(13,82,161,0.25)_0%,_transparent_70%)] pointer-events-none"></div>
        
        <div class="relative mb-8 group animate-float z-10">
            <div class="absolute inset-0 bg-elevate-primary/40 blur-[60px] rounded-full animate-pulse transition-all duration-500 group-hover:bg-elevate-accent/50 group-hover:scale-125"></div>
            <div class="w-36 h-36 bg-white/10 backdrop-blur-xl rounded-full border border-elevate-accent/40 flex items-center justify-center relative z-10 shadow-[0_0_40px_rgba(13,82,161,0.4)] group-hover:scale-110 transition-transform duration-500">
                <i class="ph-bold ph-monitor-play text-7xl text-elevate-accent drop-shadow-[0_0_15px_rgba(86,187,241,0.8)] group-hover:text-white transition-colors"></i>
            </div>
        </div>
        <h1 class="text-4xl md:text-6xl font-black text-white tracking-tight mb-4 uppercase drop-shadow-2xl relative z-10">DIGITAL SIGNAGE</h1>
        <p class="text-slate-300 font-medium text-sm md:text-base tracking-wide mb-6 relative z-10 text-center max-w-md">Layar Informasi Lobi Terpadu, Jadwal Bel & Guru Piket Sekolah</p>
        <p class="text-elevate-dark font-bold uppercase tracking-widest text-xs bg-elevate-accent px-8 py-3.5 rounded-full shadow-[0_0_20px_rgba(86,187,241,0.5)] animate-pulse hover:scale-105 transition-transform cursor-pointer relative z-10">Ketuk Layar Untuk Memulai</p>
    </div>

    <!-- MAIN DISPLAY APP -->
    <div x-data="scheduleDisplay()" x-init="initDisplay()" x-cloak class="w-full h-full flex flex-col p-4 lg:p-8 max-w-screen-2xl mx-auto relative z-10 overflow-hidden justify-between">
       
        <!-- HERO / HEADER -->
        <div class="relative rounded-[2rem] bg-white/10 backdrop-blur-2xl p-4 lg:p-6 mb-4 border border-white/15 shadow-[0_8px_32px_rgba(0,0,0,0.4)] overflow-hidden shrink-0 animate-enter">
            <div class="absolute -top-24 -right-24 w-80 h-80 bg-elevate-accent/15 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                <!-- Identitas Header -->
                <div class="flex items-center gap-4 w-full lg:w-auto">
                    <div class="w-12 h-12 lg:w-14 lg:h-14 rounded-2xl bg-elevate-accent/20 border border-elevate-accent/30 flex items-center justify-center shadow-lg shadow-elevate-accent/10 shrink-0">
                        <i class="ph-fill ph-bell-ringing text-2xl lg:text-3xl text-elevate-accent"></i>
                    </div>
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/20 text-elevate-accent text-[9px] lg:text-[10px] font-black uppercase tracking-widest mb-1 shadow-sm backdrop-blur-md">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse shadow-[0_0_8px_#34d399]"></span> Smart School Display
                        </div>
                        <h1 class="text-xl lg:text-2xl font-black tracking-tight text-white">Layar Informasi Lobi Sekolah</h1>
                    </div>
                </div>

                <!-- Jam, Tanggal & Tombol Aksi -->
                <div class="flex items-center gap-4 w-full lg:w-auto justify-between lg:justify-end">
                    <div class="text-left lg:text-right mr-auto lg:mr-0">
                        <div class="text-3xl sm:text-4xl lg:text-5xl font-black text-white font-mono tracking-tighter drop-shadow-[0_0_20px_rgba(86,187,241,0.35)] leading-none" x-text="currentTimeFormatted">00:00:00</div>
                        <div class="text-[10px] sm:text-xs font-bold text-slate-300 mt-1" x-text="currentDateFormatted">Memuat Tanggal...</div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-2 shrink-0">
                        <!-- Shortcut ke Pos Piket -->
                        <a href="{{ route('permit.index') }}"
                           class="inline-flex items-center justify-center gap-2 bg-white/10 hover:bg-sky-600/80 text-white font-bold text-xs p-3 sm:px-4 sm:py-3 rounded-xl shadow-sm border border-white/20 transition-all hover:scale-105" title="Buka Pos Piket">
                            <i class="ph-bold ph-shield-check text-base"></i>
                            <span class="hidden sm:inline">Pos Piket</span>
                        </a>

                        <!-- Tombol Keluar -->
                        <button onclick="exitDisplay()"
                           class="inline-flex items-center justify-center gap-2 bg-white/10 hover:bg-rose-500/80 text-slate-200 hover:text-white backdrop-blur-md border border-white/20 hover:border-rose-500 font-bold text-xs p-3 sm:px-4 sm:py-3 rounded-xl shadow-sm transition-all hover:scale-105" title="Keluar Display">
                            <i class="ph-bold ph-sign-out text-base"></i>
                            <span class="hidden sm:inline">Keluar</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- KONTEN UTAMA DUA KOLOM -->
        <div class="flex-1 grid grid-cols-1 lg:grid-cols-12 gap-5 min-h-0 overflow-hidden">

            <!-- PANEL KIRI: STATUS KEGIATAN SAAT INI (KBM / LIBUR) -->
            <div class="lg:col-span-5 flex flex-col animate-enter h-full">
                <div class="rounded-[2.5rem] p-6 lg:p-8 text-white shadow-2xl shadow-black/40 flex-1 flex flex-col justify-between relative overflow-hidden bg-slate-900/60 backdrop-blur-2xl border border-white/15 group">
                    <img src="{{ asset('images/netila.jpg') }}" alt=""
                         class="absolute inset-0 w-full h-full object-cover opacity-60 filter brightness-80 contrast-115 group-hover:scale-105 transition-transform duration-1000"
                         onerror="this.style.display='none'">

                    <div class="absolute inset-0 bg-gradient-to-br from-[#021124]/85 via-[#0d52a1]/65 to-[#021124]/85"></div>
                    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_rgba(13,82,161,0.2)_0%,_rgba(2,17,36,0.65)_60%,_#021124_100%)]"></div>

                    <div class="relative z-10 flex items-center justify-between">
                        <div class="inline-flex items-center gap-2 bg-white/10 border border-white/20 backdrop-blur-md px-4 py-1.5 rounded-full text-[10px] lg:text-xs font-black tracking-widest uppercase text-slate-200">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse shadow-[0_0_10px_#34d399]"></span>
                            Kegiatan Hari Ini
                        </div>
                        <span class="text-xs font-bold text-sky-300" x-text="dayName"></span>
                    </div>

                    <template x-if="isHoliday">
                        <div class="text-center relative z-10 my-auto py-8">
                            <span class="inline-flex items-center gap-2 bg-white/10 text-elevate-accent border border-elevate-accent/30 px-5 py-2 rounded-full text-xs font-black tracking-widest uppercase shadow-lg backdrop-blur-md">
                                <i class="ph-fill ph-sun text-lg text-amber-400"></i> Status Hari Ini
                            </span>
                            <h2 class="text-4xl lg:text-5xl font-black mt-6 mb-3 drop-shadow-md text-white">Hari Libur</h2>
                            <p class="text-base lg:text-lg text-slate-200 font-semibold bg-black/40 inline-block px-4 py-2 rounded-xl backdrop-blur-md border border-white/10" x-text="holidayReason"></p>
                        </div>
                    </template>

                    <template x-if="!isHoliday">
                        <div class="relative z-10 my-auto py-4">
                            <p class="text-xs font-bold text-sky-400 uppercase tracking-widest mb-1">Status Sesi KBM</p>
                            <h2 class="text-3xl lg:text-5xl font-black leading-tight tracking-tight mb-4 drop-shadow-lg text-white" x-text="nextActivity.name">Memuat...</h2>
                            
                            <div class="flex items-center gap-4 bg-black/40 p-4 rounded-2xl backdrop-blur-md border border-white/10 shadow-inner">
                                <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center shrink-0 border border-white/15">
                                    <i class="ph-fill ph-clock text-2xl text-elevate-accent"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Waktu Bel / Eksekusi</p>
                                    <span class="text-2xl lg:text-3xl font-mono font-black text-white" x-text="nextActivity.time">--:--</span>
                                </div>
                            </div>
                        </div>
                    </template>

                    {{-- Mini Widget Presensi Snapshot di bawah kiri --}}
                    <div class="relative z-10 bg-black/40 backdrop-blur-md p-3.5 rounded-2xl border border-white/10 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-lg">
                                <i class="ph-bold ph-chart-line-up"></i>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Tingkat Hadir Hari Ini</span>
                                <span class="text-sm font-black text-white" x-text="attendanceStats.rate + '% Kehadiran'">0%</span>
                            </div>
                        </div>
                        <span class="text-xs font-mono font-bold text-slate-300" x-text="attendanceStats.hadir + ' / ' + attendanceStats.total + ' Siswa'"></span>
                    </div>
                </div>
            </div>

            <!-- PANEL KANAN: AUTO-SLIDESHOW CAROUSEL (JADWAL BEL <-> GURU PIKET <-> STATISTIK <-> AGENDA) -->
            <div class="lg:col-span-7 bg-slate-900/60 backdrop-blur-2xl shadow-2xl shadow-black/40 border border-white/15 rounded-[2.5rem] p-5 lg:p-7 flex flex-col h-full relative overflow-hidden animate-enter">
                
                {{-- HEADER SLIDESHOW DENGAN NAVIGASI PILL --}}
                <div class="flex flex-wrap justify-between items-center mb-4 pb-3 border-b border-white/10 relative z-10 gap-2">
                    <div class="flex items-center gap-1.5 p-1 bg-white/5 rounded-xl border border-white/10 backdrop-blur-md">
                        <button type="button" @click="currentSlide = 1"
                            :class="currentSlide === 1 ? 'bg-elevate-accent text-elevate-dark font-black shadow-sm' : 'text-slate-400 hover:text-white font-bold'"
                            class="px-3 py-1.5 rounded-lg text-xs transition-all flex items-center gap-1.5">
                            <i class="ph-bold ph-bell"></i>
                            <span>Jadwal Bel</span>
                        </button>
                        <button type="button" @click="currentSlide = 2"
                            :class="currentSlide === 2 ? 'bg-elevate-accent text-elevate-dark font-black shadow-sm' : 'text-slate-400 hover:text-white font-bold'"
                            class="px-3 py-1.5 rounded-lg text-xs transition-all flex items-center gap-1.5">
                            <i class="ph-bold ph-users-three"></i>
                            <span>Guru Piket</span>
                        </button>
                        <button type="button" @click="currentSlide = 3"
                            :class="currentSlide === 3 ? 'bg-elevate-accent text-elevate-dark font-black shadow-sm' : 'text-slate-400 hover:text-white font-bold'"
                            class="px-3 py-1.5 rounded-lg text-xs transition-all flex items-center gap-1.5">
                            <i class="ph-bold ph-star"></i>
                            <span>Presensi & Teladan</span>
                        </button>
                        <button type="button" @click="currentSlide = 4"
                            :class="currentSlide === 4 ? 'bg-elevate-accent text-elevate-dark font-black shadow-sm' : 'text-slate-400 hover:text-white font-bold'"
                            class="px-3 py-1.5 rounded-lg text-xs transition-all flex items-center gap-1.5">
                            <i class="ph-bold ph-calendar"></i>
                            <span>Agenda</span>
                        </button>
                    </div>

                    <div class="flex items-center gap-2 text-xs font-bold text-slate-400">
                        <span class="w-2 h-2 rounded-full bg-sky-400 animate-ping"></span>
                        Slide <span x-text="currentSlide" class="text-white font-bold">1</span> dari 4
                    </div>
                </div>

                {{-- SLIDE 1: DAFTAR JADWAL BEL PEMBELAJARAN --}}
                <div x-show="currentSlide === 1" x-transition.opacity class="flex-1 flex flex-col min-h-0 relative z-10">
                    <div class="flex justify-between items-center mb-3">
                        <h4 class="text-sm font-bold uppercase tracking-wider text-slate-300">Daftar Sesi Pembelajaran & Bel Hari Ini</h4>
                        <span class="text-xs text-sky-400 font-bold" x-text="schedules.length + ' Sesi'"></span>
                    </div>

                    <div class="flex-1 overflow-y-auto pr-2 space-y-2.5 custom-scrollbar">
                        <template x-if="schedules.length === 0 && !isHoliday">
                            <div class="flex flex-col items-center justify-center h-full text-slate-400 py-12">
                                <i class="ph-duotone ph-calendar-x text-5xl mb-3 text-slate-500"></i>
                                <p class="text-sm font-bold">Tidak ada jadwal bel untuk hari ini.</p>
                            </div>
                        </template>

                        <template x-for="(schedule, index) in schedules" :key="index">
                            <div class="group flex items-center justify-between p-3.5 rounded-2xl border relative overflow-hidden transition-all duration-300 backdrop-blur-md"
                                 :class="{
                                     'bg-white/[0.03] border-white/5 opacity-50': isPast(schedule.trigger_time),
                                     'bg-gradient-to-r from-elevate-primary/40 to-elevate-accent/20 border-elevate-accent shadow-lg shadow-elevate-accent/20 scale-[1.01] ring-2 ring-elevate-accent/30': isCurrent(schedule.trigger_time, index),
                                     'bg-white/5 border-white/10': !isPast(schedule.trigger_time) && !isCurrent(schedule.trigger_time, index)
                                 }">

                                <div class="flex items-center space-x-3.5">
                                    <div class="flex flex-col items-center justify-center w-14 h-12 rounded-xl font-mono font-black text-base shrink-0"
                                         :class="isCurrent(schedule.trigger_time, index) ? 'bg-elevate-accent text-elevate-dark shadow-md' : 'bg-white/10 text-slate-300 border border-white/10'">
                                        <span x-text="formatTime(schedule.trigger_time)"></span>
                                    </div>
                                    <div>
                                        <h4 class="text-sm lg:text-base font-black text-white" x-text="schedule.activity_name"></h4>
                                        <p class="text-[9px] lg:text-[10px] font-bold uppercase tracking-widest mt-0.5"
                                           :class="isPast(schedule.trigger_time) ? 'text-slate-500' : (isCurrent(schedule.trigger_time, index) ? 'text-elevate-accent' : 'text-slate-400')"
                                           x-text="getStatusText(schedule.trigger_time, index)"></p>
                                    </div>
                                </div>

                                <template x-if="schedule.audio_file">
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center shrink-0"
                                         :class="isCurrent(schedule.trigger_time, index) ? 'bg-elevate-accent/20 text-elevate-accent border border-elevate-accent/40 shadow-sm' : 'bg-white/5 text-slate-400 border border-white/10'">
                                        <i class="ph-bold ph-speaker-high text-base"></i>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- SLIDE 2: GURU PIKET HARI INI --}}
                <div x-show="currentSlide === 2" x-transition.opacity class="flex-1 flex flex-col min-h-0 relative z-10">
                    <div class="flex justify-between items-center mb-3">
                        <h4 class="text-sm font-bold uppercase tracking-wider text-slate-300">Daftar Guru / Petugas Piket Sekolah Hari Ini</h4>
                        <span class="text-xs text-sky-400 font-bold" x-text="dutyTeachers.length + ' Petugas'"></span>
                    </div>

                    <div class="flex-1 overflow-y-auto pr-2 grid grid-cols-1 sm:grid-cols-2 gap-3.5 custom-scrollbar items-start">
                        <template x-for="(duty, idx) in dutyTeachers" :key="idx">
                            <div class="p-4 rounded-2xl bg-white/5 border border-white/10 hover:border-sky-400/40 backdrop-blur-md flex items-center gap-3.5 transition-all">
                                <div class="w-14 h-14 rounded-2xl bg-slate-800 border border-white/20 overflow-hidden shrink-0 flex items-center justify-center font-black text-base text-sky-300 shadow-sm">
                                    <template x-if="duty.photo_url">
                                        <img :src="duty.photo_url" alt="" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!duty.photo_url">
                                        <span x-text="duty.name.substring(0, 2)"></span>
                                    </template>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="text-sm font-black text-white truncate flex items-center gap-1.5">
                                        <span x-text="duty.name"></span>
                                        <template x-if="duty.is_coordinator">
                                            <span class="px-1.5 py-0.5 rounded text-[8px] bg-amber-500/20 text-amber-300 border border-amber-400/30 font-black">KOOR</span>
                                        </template>
                                    </div>
                                    <p class="text-xs text-slate-300 font-medium truncate" x-text="duty.nip ? 'NIP. ' + duty.nip : 'Tenaga Pendidik'"></p>
                                    <span class="inline-block mt-1 px-2.5 py-0.5 rounded-lg text-[10px] font-bold bg-sky-500/20 text-sky-300 border border-sky-400/30" x-text="duty.post_location"></span>
                                </div>
                            </div>
                        </template>

                        <template x-if="dutyTeachers.length === 0">
                            <div class="col-span-2 flex flex-col items-center justify-center py-16 text-slate-400">
                                <i class="ph-duotone ph-users-three text-5xl mb-3 text-slate-500"></i>
                                <p class="text-sm font-bold text-white">Belum Ada Jadwal Piket Hari Ini</p>
                                <p class="text-xs text-slate-400 mt-1">Jadwal dapat diatur dari menu Pos Piket > Jadwal Piket.</p>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- SLIDE 3: LIVE PRESENSI & SISWA TELADAN --}}
                <div x-show="currentSlide === 3" x-transition.opacity class="flex-1 flex flex-col min-h-0 relative z-10 space-y-4">
                    {{-- Statistik Cards --}}
                    <div class="grid grid-cols-4 gap-2.5">
                        <div class="p-3 rounded-2xl bg-white/5 border border-white/10 text-center">
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Total</span>
                            <span class="text-xl font-black text-white" x-text="attendanceStats.total">0</span>
                        </div>
                        <div class="p-3 rounded-2xl bg-emerald-500/10 border border-emerald-400/30 text-center">
                            <span class="text-[10px] text-emerald-400 font-bold uppercase tracking-wider block">Hadir</span>
                            <span class="text-xl font-black text-emerald-300" x-text="attendanceStats.hadir">0</span>
                        </div>
                        <div class="p-3 rounded-2xl bg-amber-500/10 border border-amber-400/30 text-center">
                            <span class="text-[10px] text-amber-400 font-bold uppercase tracking-wider block">Terlambat</span>
                            <span class="text-xl font-black text-amber-300" x-text="attendanceStats.terlambat">0</span>
                        </div>
                        <div class="p-3 rounded-2xl bg-rose-500/10 border border-rose-400/30 text-center">
                            <span class="text-[10px] text-rose-400 font-bold uppercase tracking-wider block">Sakit/Izin</span>
                            <span class="text-xl font-black text-rose-300" x-text="attendanceStats.sakit + attendanceStats.izin">0</span>
                        </div>
                    </div>

                    {{-- Top Siswa Teladan / Karakter --}}
                    <div class="flex-1 flex flex-col min-h-0">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-sky-300 mb-2 flex items-center gap-1.5">
                            <i class="ph-fill ph-trophy text-amber-400"></i> Siswa Teladan & Poin Karakter Teratas
                        </h4>
                        
                        <div class="flex-1 overflow-y-auto pr-1 grid grid-cols-2 gap-3 custom-scrollbar">
                            <template x-for="(std, idx) in topStudents" :key="idx">
                                <div class="p-3 rounded-2xl bg-white/5 border border-white/10 flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-xl bg-slate-800 border border-white/20 overflow-hidden shrink-0 flex items-center justify-center font-black text-sm text-sky-300 shadow-sm">
                                        <template x-if="std.photo_url">
                                            <img :src="std.photo_url" alt="" class="w-full h-full object-cover">
                                        </template>
                                        <template x-if="!std.photo_url">
                                            <span x-text="std.name.substring(0, 2)"></span>
                                        </template>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h5 class="text-xs font-black text-white truncate" x-text="std.name"></h5>
                                        <p class="text-[10px] text-slate-400 font-semibold" x-text="'Kelas ' + std.class"></p>
                                        <span class="text-[10px] font-black text-amber-300" x-text="std.score + ' Poin'"></span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- SLIDE 4: AGENDA & KEGIATAN SEKOLAH --}}
                <div x-show="currentSlide === 4" x-transition.opacity class="flex-1 flex flex-col min-h-0 relative z-10">
                    <div class="flex justify-between items-center mb-3">
                        <h4 class="text-sm font-bold uppercase tracking-wider text-slate-300">Agenda & Kalender Kegiatan Terdekat</h4>
                        <span class="text-xs text-sky-400 font-bold" x-text="upcomingAgendas.length + ' Agenda'"></span>
                    </div>

                    <div class="flex-1 overflow-y-auto pr-2 space-y-3 custom-scrollbar">
                        <template x-for="(agenda, idx) in upcomingAgendas" :key="idx">
                            <div class="p-4 rounded-2xl bg-white/5 border border-white/10 hover:border-sky-400/40 backdrop-blur-md flex items-center gap-4 transition-all">
                                <div class="w-12 h-12 rounded-xl bg-sky-500/20 text-sky-400 border border-sky-400/30 flex items-center justify-center text-xl shrink-0">
                                    <i class="ph-bold ph-calendar-star"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h4 class="text-sm font-black text-white truncate" x-text="agenda.title"></h4>
                                    <p class="text-xs text-slate-300 font-medium mt-0.5" x-text="'Lokasi: ' + agenda.location"></p>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="px-3 py-1.5 rounded-xl bg-white/10 text-xs font-mono font-bold text-sky-300 border border-white/15" x-text="agenda.date"></span>
                                </div>
                            </div>
                        </template>

                        <template x-if="upcomingAgendas.length === 0">
                            <div class="flex flex-col items-center justify-center py-16 text-slate-400">
                                <i class="ph-duotone ph-calendar-blank text-5xl mb-3 text-slate-500"></i>
                                <p class="text-sm font-bold text-white">Tidak Ada Agenda Terdekat</p>
                                <p class="text-xs text-slate-400 mt-1">Kegiatan mendatang akan ditampilkan di sini.</p>
                            </div>
                        </template>
                    </div>
                </div>

            </div>
        </div>

        <!-- FOOTER: RUNNING TEXT (TICKER PENGUMUMAN & MOTIVASI) -->
        <div class="mt-4 bg-slate-900/80 backdrop-blur-2xl rounded-2xl border border-white/15 px-4 py-2.5 flex items-center gap-3 overflow-hidden shrink-0 shadow-xl relative z-20">
            <div class="flex items-center gap-2 bg-gradient-to-r from-elevate-accent to-elevate-primary px-3 py-1 rounded-xl text-white font-black text-xs uppercase tracking-wider shrink-0 shadow-sm">
                <i class="ph-fill ph-broadcast animate-pulse text-base"></i>
                <span class="hidden sm:inline">PENGUMUMAN</span>
            </div>
            
            <div class="flex-1 overflow-hidden whitespace-nowrap">
                <div class="animate-marquee font-bold text-xs sm:text-sm text-slate-200">
                    <template x-for="(text, idx) in runningTexts" :key="idx">
                        <span class="inline-flex items-center mx-6">
                            <span class="w-2 h-2 rounded-full bg-elevate-accent mr-3 inline-block"></span>
                            <span x-text="text"></span>
                        </span>
                    </template>
                    <template x-for="(text, idx) in runningTexts" :key="'dup-'+idx">
                        <span class="inline-flex items-center mx-6">
                            <span class="w-2 h-2 rounded-full bg-elevate-accent mr-3 inline-block"></span>
                            <span x-text="text"></span>
                        </span>
                    </template>
                </div>
            </div>
        </div>

        <!-- Audio Element untuk Bel -->
        <audio x-ref="bellAudio"></audio>

        <!-- Banner jika browser memblokir audio bel -->
        <div x-show="audioBlocked" x-transition
             class="fixed bottom-16 right-6 z-[110] flex items-center gap-3 bg-rose-600 text-white px-5 py-4 rounded-2xl shadow-2xl shadow-rose-900/40 cursor-pointer hover:bg-rose-500 transition-colors animate-pulse"
             @click="initAudio()" title="Ketuk untuk mengaktifkan suara bel">
            <i class="ph-bold ph-speaker-x text-2xl shrink-0"></i>
            <div class="text-xs sm:text-sm font-bold leading-snug">
                Suara bel diblokir browser.<br>
                <span class="underline">Ketuk di sini untuk mengaktifkan</span>
            </div>
        </div>
    </div>

    <!-- LOGIKA JAVASCRIPT & ALPINE.JS -->
    <script>
        function scheduleDisplay() {
            return {
                schedules: [],
                dayName: '...',
                isHoliday: false,
                holidayReason: '',
                currentTime: new Date(),
                nextActivity: { name: 'Memuat data...', time: '' },

                dutyTeachers: [],
                attendanceStats: { total: 0, hadir: 0, terlambat: 0, sakit: 0, izin: 0, rate: 0 },
                upcomingAgendas: [],
                topStudents: [],
                runningTexts: [],

                currentSlide: 1,
                totalSlides: 4,
                slideInterval: null,
                
                lastPlayedTime: null,
                audioBlocked: false,

                initDisplay() {
                    this.fetchSchedules();

                    // Update Jam & Cek Bel setiap 1 detik
                    setInterval(() => {
                        this.currentTime = new Date();
                        this.checkBell();
                    }, 1000);

                    // Sinkronisasi data API setiap 3 menit
                    setInterval(() => {
                        this.fetchSchedules();
                    }, 180000);

                    // Rotasi Slideshow otomatis setiap 15 detik
                    this.startSlideRotation();
                },

                startSlideRotation() {
                    if (this.slideInterval) clearInterval(this.slideInterval);
                    this.slideInterval = setInterval(() => {
                        this.currentSlide = (this.currentSlide % this.totalSlides) + 1;
                    }, 15000);
                },

                getNowStr() {
                    let h = String(this.currentTime.getHours()).padStart(2, '0');
                    let m = String(this.currentTime.getMinutes()).padStart(2, '0');
                    let s = String(this.currentTime.getSeconds()).padStart(2, '0');
                    return `${h}:${m}:${s}`;
                },

                getDateKey() {
                    let d = this.currentTime;
                    return `${d.getFullYear()}-${d.getMonth() + 1}-${d.getDate()}`;
                },

                initAudio() {
                    if(this.$refs.bellAudio) {
                        this.$refs.bellAudio.src = 'data:audio/wav;base64,UklGRigAAABXQVZFZm10IBIAAAABAAEARKwAAIhYAQACABAAAABkYXRhAgAAAAEA';
                        this.$refs.bellAudio.play().then(() => {
                            this.audioBlocked = false;
                        }).catch(e => {
                            console.log("Unlock audio gagal", e);
                            this.audioBlocked = true;
                        });
                    }
                },

                fetchSchedules() {
                    fetch('{{ route('api.display.schedules') }}')
                        .then(res => {
                            if (!res.ok) throw new Error('API Error');
                            return res.json();
                        })
                        .then(data => {
                            this.schedules = Array.isArray(data.schedules) ? data.schedules : Object.values(data.schedules || {});
                            this.dayName = data.day_name || 'Tidak Diketahui';
                            this.isHoliday = data.is_holiday || false;
                            this.holidayReason = data.holiday_reason || '';
                            
                            this.dutyTeachers = data.duty_teachers || [];
                            this.attendanceStats = data.attendance_stats || { total: 0, hadir: 0, terlambat: 0, sakit: 0, izin: 0, rate: 0 };
                            this.upcomingAgendas = data.upcoming_agendas || [];
                            this.topStudents = data.top_students || [];
                            this.runningTexts = data.running_texts || [];

                            this.updateNextActivity();
                        })
                        .catch(err => {
                            console.error("Gagal mengambil data display dari server", err);
                            this.nextActivity = { name: 'Koneksi API Gagal', time: 'Error' };
                        });
                },

                get currentTimeFormatted() {
                    return this.getNowStr();
                },

                get currentDateFormatted() {
                    return this.currentTime.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                },

                formatTime(timeString) {
                    if (!timeString) return '';
                    return timeString.substring(0, 5);
                },

                isPast(triggerTime) {
                    return this.getNowStr() > triggerTime;
                },

                isCurrent(triggerTime, index) {
                    let nowStr = this.getNowStr();
                    if (nowStr < triggerTime) return false;

                    let nextSchedule = this.schedules[index + 1];
                    if (nextSchedule && nowStr >= nextSchedule.trigger_time) {
                        return false;
                    }
                    return true;
                },

                getStatusText(triggerTime, index) {
                    if (this.isCurrent(triggerTime, index)) return 'Sedang Berlangsung';
                    if (this.isPast(triggerTime)) return 'Selesai';
                    return 'Akan Datang';
                },

                updateNextActivity() {
                    if (this.isHoliday) return;
                    
                    if (!this.schedules || this.schedules.length === 0) {
                        this.nextActivity = { name: 'Tidak ada jadwal', time: '--:--' };
                        return;
                    }

                    let nowStr = this.getNowStr();
                    let current = null;
                    let upcoming = null;

                    for (let i = 0; i < this.schedules.length; i++) {
                        if (this.schedules[i].trigger_time <= nowStr) {
                            current = this.schedules[i];
                        } else if (!upcoming) {
                            upcoming = this.schedules[i];
                        }
                    }

                    if (upcoming) {
                        this.nextActivity = { name: upcoming.activity_name, time: this.formatTime(upcoming.trigger_time) };
                    } else if (current) {
                        this.nextActivity = { name: current.activity_name, time: 'Berjalan' };
                    } else {
                        this.nextActivity = { name: 'Semua Selesai', time: '--:--' };
                    }
                },

                checkBell() {
                    this.updateNextActivity();
                    if (!this.schedules || this.schedules.length === 0) return;

                    let nowStr = this.getNowStr();
                    let currentMinute = nowStr.substring(0, 5);
                    let currentSchedule = this.schedules.find(s => this.formatTime(s.trigger_time) === currentMinute);

                    if (currentSchedule && currentSchedule.audio_file) {
                        let playKey = `${this.getDateKey()}_${currentMinute}`;

                        if (this.lastPlayedTime !== playKey) {
                            this.lastPlayedTime = playKey;
                            const repeatCount = Math.min(5, Math.max(1, parseInt(currentSchedule.repeat_count) || 1));
                            this.playBellWithRepeat('/storage/' + currentSchedule.audio_file, repeatCount);
                        }
                    }
                },

                playBellWithRepeat(src, totalRepeats) {
                    if (!this.$refs.bellAudio) return;
                    const audio = this.$refs.bellAudio;
                    let played = 0;

                    audio.onended = () => {
                        played++;
                        if (played < totalRepeats) {
                            setTimeout(() => {
                                audio.currentTime = 0;
                                audio.play().catch(err => console.warn('Repeat play error', err));
                            }, 500);
                        } else {
                            audio.onended = null;
                        }
                    };

                    audio.src = src;
                    audio.load();
                    audio.play().then(() => {
                        this.audioBlocked = false;
                    }).catch(err => {
                        console.warn('Autoplay diblokir browser', err);
                        this.audioBlocked = true;
                    });
                }
            }
        }

        // Fungsi Memulai Layar (Masuk Fullscreen)
        function startDisplay() {
            const overlay = document.getElementById('start-overlay');
            overlay.style.opacity = '0';
            setTimeout(() => overlay.style.display = 'none', 700);

            if (document.documentElement.requestFullscreen) {
                document.documentElement.requestFullscreen().catch(err => console.log(err));
            }

            const audioEl = document.querySelector('audio');
            if(audioEl) {
                audioEl.src = 'data:audio/wav;base64,UklGRigAAABXQVZFZm10IBIAAAABAAEARKwAAIhYAQACABAAAABkYXRhAgAAAAEA';
                audioEl.play().catch(e => console.log("Abaikan ini", e));
            }
        }

        // Fungsi Keluar Layar (Keluar Fullscreen dan Kembali)
        function exitDisplay() {
            if (document.fullscreenElement) {
                document.exitFullscreen().then(() => {
                    setTimeout(() => {
                        window.history.back();
                    }, 200);
                }).catch(err => {
                    console.error("Gagal keluar dari fullscreen:", err);
                    window.history.back(); 
                });
            } else {
                window.history.back();
            }
        }
    </script>
</body>
</html>
