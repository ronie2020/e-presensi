<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Jadwal & Bel Sekolah</title>

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
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        [x-cloak] { display: none !important; }

        @keyframes enter {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-enter { animation: enter .5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        
        /* Efek melayang perlahan untuk ikon start */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .animate-float { animation: float 3s ease-in-out infinite; }
    </style>
</head>
<body class="bg-slate-50 text-elevate-dark font-sans antialiased h-screen overflow-hidden flex flex-col selection:bg-elevate-soft">

    <!-- LAYER START DISPLAY -->
    <div id="start-overlay" class="fixed inset-0 z-[100] bg-slate-900 flex flex-col items-center justify-center cursor-pointer transition-all duration-700 backdrop-blur-sm" onclick="startDisplay()">
        <div class="relative mb-8 group animate-float">
            <div class="absolute inset-0 bg-elevate-primary/40 blur-[60px] rounded-full animate-pulse transition-all duration-500 group-hover:bg-elevate-accent/50 group-hover:scale-125"></div>
            <div class="w-36 h-36 bg-white/10 backdrop-blur-xl rounded-full border border-elevate-accent/40 flex items-center justify-center relative z-10 shadow-[0_0_40px_rgba(13,82,161,0.4)] group-hover:scale-110 transition-transform duration-500">
                <i class="ph-bold ph-monitor-play text-7xl text-elevate-accent drop-shadow-[0_0_15px_rgba(86,187,241,0.8)] group-hover:text-white transition-colors"></i>
            </div>
        </div>
        <h1 class="text-4xl md:text-6xl font-black text-white tracking-tight mb-5 uppercase drop-shadow-2xl">DISPLAY JADWAL</h1>
        <p class="text-elevate-dark font-bold uppercase tracking-widest text-xs bg-elevate-accent px-8 py-3.5 rounded-full shadow-[0_0_20px_rgba(86,187,241,0.5)] animate-pulse hover:scale-105 transition-transform cursor-pointer">Ketuk Layar Untuk Memulai</p>
    </div>

    <div x-data="scheduleDisplay()" x-init="initDisplay()" x-cloak class="w-full h-full flex flex-col p-6 lg:p-10 max-w-screen-2xl mx-auto relative overflow-hidden">
       
        <!-- Efek Latar Belakang Halus -->
        <div class="absolute top-0 left-0 w-full h-[500px] bg-elevate-gradient-main opacity-30 pointer-events-none -z-10 blur-3xl transition-opacity duration-1000"></div>

        <!-- HERO / HEADER -->
        <div class="relative rounded-[2.5rem] bg-elevate-gradient-main p-6 lg:p-8 mb-6 border border-white/80 shadow-2xl shadow-elevate-accent/15 overflow-hidden shrink-0 animate-enter">
            <div class="absolute -top-24 -right-24 w-80 h-80 bg-white/60 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                <div class="flex items-center gap-5">
                    <div class="w-16 h-16 rounded-2xl bg-elevate-dark flex items-center justify-center shadow-xl shadow-elevate-dark/40 shrink-0">
                        <i class="ph-fill ph-bell-ringing text-3xl text-white"></i>
                    </div>
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/80 border border-elevate-accent/30 text-elevate-primary text-[10px] font-black uppercase tracking-widest mb-1.5 shadow-sm backdrop-blur-md">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Layar Informasi Sekolah
                        </div>
                        <h1 class="text-2xl lg:text-3xl font-black tracking-tight text-elevate-dark">Jadwal Pembelajaran</h1>
                    </div>
                </div>

                <div class="flex items-center gap-5 w-full lg:w-auto justify-between lg:justify-end">
                    <div class="text-right">
                        <div class="text-5xl lg:text-6xl font-black text-elevate-dark font-mono tracking-tighter drop-shadow-sm" x-text="currentTimeFormatted">00:00:00</div>
                        <div class="text-xs lg:text-sm font-bold text-elevate-primary mt-1 opacity-80" x-text="currentDateFormatted">Memuat Tanggal...</div>
                    </div>

                    <a href="{{ route('schedules.index') }}"
                       class="inline-flex items-center gap-2 bg-gradient-to-r from-elevate-peach-dark to-elevate-peach hover:to-elevate-peach-light text-white font-bold text-sm px-6 py-4 rounded-2xl shadow-lg shadow-elevate-peach-dark/30 hover:shadow-xl hover:-translate-y-0.5 transition-all active:scale-95 shrink-0 group border border-white/20">
                        <i class="ph-bold ph-pencil-simple text-xl group-hover:rotate-12 transition-transform"></i>
                        <span class="hidden sm:inline">Edit Jadwal</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Konten Utama -->
        <div class="flex-1 grid grid-cols-1 lg:grid-cols-12 gap-6 min-h-0">

            <!-- Panel Kiri: Status Saat Ini -->
            <div class="lg:col-span-5 flex flex-col animate-enter" style="animation-delay: 100ms">
                <div class="rounded-[2.5rem] p-8 lg:p-10 text-white shadow-2xl shadow-elevate-dark/20 flex-1 flex flex-col justify-center relative overflow-hidden bg-elevate-dark border border-slate-700/50 group">
                    <img src="{{ asset('images/netila.jpg') }}" alt=""
                         class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-1000"
                         onerror="this.style.display='none'">

                    <div class="absolute inset-0 bg-gradient-to-br from-elevate-primary/95 via-elevate-primary/80 to-elevate-dark/95 mix-blend-multiply"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 to-transparent"></div>

                    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-48 h-48 bg-white opacity-10 rounded-full blur-3xl pointer-events-none"></div>

                    <template x-if="isHoliday">
                        <div class="text-center relative z-10 animate-enter">
                            <span class="inline-flex items-center gap-2 bg-white text-elevate-primary px-5 py-2 rounded-full text-xs font-black tracking-widest uppercase shadow-lg shadow-black/20">
                                <i class="ph-fill ph-sun text-lg"></i> Status Hari Ini
                            </span>
                            <h2 class="text-4xl lg:text-5xl font-black mt-8 mb-3 drop-shadow-md">Hari Libur</h2>
                            <p class="text-lg lg:text-xl text-white/90 font-semibold bg-black/20 inline-block px-4 py-2 rounded-xl backdrop-blur-sm border border-white/10" x-text="holidayReason"></p>
                        </div>
                    </template>

                    <template x-if="!isHoliday">
                        <div class="relative z-10 animate-enter flex flex-col justify-end h-full">
                            <div>
                                <div class="inline-flex items-center gap-2 bg-white/20 border border-white/30 backdrop-blur-md px-4 py-2 rounded-full text-[10px] lg:text-xs font-black tracking-widest uppercase mb-6 shadow-sm">
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse shadow-[0_0_10px_#34d399]"></span>
                                    Kegiatan Saat Ini
                                </div>
                                <h2 class="text-4xl lg:text-6xl font-black leading-tight tracking-tight mb-6 drop-shadow-lg" x-text="nextActivity.name">Memuat...</h2>
                            </div>
                            
                            <div class="flex items-center gap-4 bg-black/20 p-4 rounded-2xl backdrop-blur-md border border-white/10 mt-auto shadow-inner">
                                <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center shrink-0">
                                    <i class="ph-fill ph-clock text-2xl text-white/90"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-white/60 uppercase tracking-widest mb-0.5">Waktu Eksekusi</p>
                                    <span class="text-3xl font-mono font-black text-white" x-text="nextActivity.time">--:--</span>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Panel Kanan: Daftar Jadwal Lengkap -->
            <div class="lg:col-span-7 bg-white/90 backdrop-blur-xl shadow-2xl shadow-slate-200/50 border border-white rounded-[2.5rem] p-6 lg:p-8 flex flex-col h-full relative overflow-hidden animate-enter" style="animation-delay: 200ms">
                <div class="absolute top-0 right-0 w-80 h-80 bg-elevate-accent/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3 pointer-events-none"></div>

                <div class="flex justify-between items-center mb-6 pb-5 border-b border-slate-100 relative z-10">
                    <h3 class="text-lg lg:text-xl font-black text-elevate-dark flex items-center gap-3">
                        <div class="p-2 bg-elevate-soft rounded-xl text-elevate-primary border border-elevate-accent/20">
                            <i class="ph-bold ph-calendar-blank text-xl"></i>
                        </div>
                        Jadwal Hari <span x-text="dayName" class="text-elevate-primary ml-1"></span>
                    </h3>
                    <span class="inline-flex items-center gap-2 bg-slate-50 text-slate-600 px-4 py-2 rounded-xl text-xs font-black border border-slate-200 shadow-sm">
                        <span class="bg-elevate-primary text-white px-2 py-0.5 rounded-md" x-text="schedules.length">0</span> Sesi
                    </span>
                </div>

                <!-- List Jadwal (Scrollable) -->
                <div class="flex-1 overflow-y-auto pr-3 space-y-3 relative z-10 custom-scrollbar">
                    <template x-if="schedules.length === 0 && !isHoliday">
                        <div class="flex flex-col items-center justify-center h-full text-slate-400">
                            <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-slate-100 shadow-inner">
                                <i class="ph-duotone ph-calendar-x text-5xl text-slate-300"></i>
                            </div>
                            <p class="text-lg font-bold text-slate-500">Tidak ada jadwal bel untuk hari ini.</p>
                        </div>
                    </template>

                    <template x-for="(schedule, index) in schedules" :key="index">
                        <div class="group flex items-center justify-between p-4 rounded-2xl border relative overflow-hidden transition-all duration-300"
                             :class="{
                                 'bg-slate-50 border-slate-200 opacity-60 hover:opacity-80': isPast(schedule.trigger_time),
                                 'bg-white border-elevate-accent/60 shadow-lg shadow-elevate-primary/10 scale-[1.02] ring-2 ring-elevate-accent/20': isCurrent(schedule.trigger_time, index),
                                 'bg-white border-slate-100 hover:border-slate-300 hover:shadow-md hover:-translate-y-0.5': !isPast(schedule.trigger_time) && !isCurrent(schedule.trigger_time, index)
                             }">

                            <div class="absolute top-0 left-0 w-1.5 h-full transition-colors duration-300"
                                 :class="isCurrent(schedule.trigger_time, index) ? 'bg-elevate-primary' : 'bg-transparent group-hover:bg-slate-300'"></div>

                            <div class="flex items-center space-x-4 pl-3">
                                <div class="flex flex-col items-center justify-center w-16 h-14 rounded-xl font-mono font-black text-lg shrink-0 transition-colors duration-300"
                                     :class="isCurrent(schedule.trigger_time, index) ? 'bg-elevate-primary text-white shadow-inner shadow-black/20' : 'bg-slate-100 text-slate-500 group-hover:bg-slate-200 group-hover:text-slate-700'">
                                    <span x-text="formatTime(schedule.trigger_time)"></span>
                                </div>
                                <div>
                                    <h4 class="text-base lg:text-lg font-black transition-colors duration-300"
                                        :class="isCurrent(schedule.trigger_time, index) ? 'text-elevate-primary' : 'text-elevate-dark'"
                                        x-text="schedule.activity_name"></h4>
                                    <p class="text-[10px] lg:text-xs font-bold mt-1 uppercase tracking-widest transition-colors duration-300"
                                       :class="isPast(schedule.trigger_time) ? 'text-slate-400' : (isCurrent(schedule.trigger_time, index) ? 'text-elevate-primary' : 'text-slate-400')"
                                       x-text="getStatusText(schedule.trigger_time, index)"></p>
                                </div>
                            </div>

                            <!-- Icon Bel -->
                            <template x-if="schedule.audio_file">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 transition-colors duration-300"
                                     :class="isCurrent(schedule.trigger_time, index) ? 'bg-elevate-soft text-elevate-primary shadow-sm' : 'bg-slate-50 text-slate-300 group-hover:bg-slate-100 group-hover:text-slate-400'">
                                    <i class="ph-bold ph-speaker-high text-lg" :class="{'animate-pulse': isCurrent(schedule.trigger_time, index)}"></i>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Audio Element untuk Bel -->
        <audio x-ref="bellAudio"></audio>
    </div>

    <!-- LOGIKA ALPINE.JS (TETAP SAMA 100%) -->
    <script>
        function scheduleDisplay() {
            return {
                schedules: [],
                dayName: '...',
                isHoliday: false,
                holidayReason: '',
                currentTime: new Date(),
                nextActivity: { name: 'Memuat data...', time: '' },
                
                lastPlayedTime: null,
                audioBlocked: false,

                initDisplay() {
                    this.fetchSchedules();

                    setInterval(() => {
                        this.currentTime = new Date();
                        this.checkBell();
                    }, 1000);

                    setInterval(() => {
                        this.fetchSchedules();
                    }, 1800000);
                },

                getNowStr() {
                    let h = String(this.currentTime.getHours()).padStart(2, '0');
                    let m = String(this.currentTime.getMinutes()).padStart(2, '0');
                    let s = String(this.currentTime.getSeconds()).padStart(2, '0');
                    return `${h}:${m}:${s}`;
                },

                initAudio() {
                    this.audioBlocked = false;
                    if(this.$refs.bellAudio) {
                        this.$refs.bellAudio.src = 'data:audio/wav;base64,UklGRigAAABXQVZFZm10IBIAAAABAAEARKwAAIhYAQACABAAAABkYXRhAgAAAAEA';
                        this.$refs.bellAudio.play().catch(e => console.log("Abaikan", e));
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
                            this.updateNextActivity();
                        })
                        .catch(err => {
                            console.error("Gagal mengambil data jadwal dari server", err);
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
                        if (this.lastPlayedTime !== currentMinute) {
                            this.lastPlayedTime = currentMinute; 
                            
                            if (this.$refs.bellAudio) {
                                this.$refs.bellAudio.src = '/storage/' + currentSchedule.audio_file;
                                let playPromise = this.$refs.bellAudio.play();
                                
                                if (playPromise !== undefined) {
                                    playPromise.then(_ => {
                                        this.audioBlocked = false;
                                    }).catch(error => {
                                        console.warn('Autoplay diblokir browser', error);
                                        this.audioBlocked = true;
                                    });
                                }
                            }
                        }
                    }
                }
            }
        }

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
    </script>
</body>
</html>