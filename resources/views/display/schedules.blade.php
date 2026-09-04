<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Jadwal & Bel Sekolah</title>

    <!-- TailwindCSS & Alpine.js -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Phosphor Icons (dipakai juga di halaman schedules/index) -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <!-- Font Figtree, sama seperti fontFamily.sans di tailwind.config.js utama -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <script>
        // Disalin persis dari tailwind.config.js aplikasi Anda, supaya halaman berdiri sendiri ini
        // (di luar build Vite utama) tetap konsisten 1:1 dengan halaman schedules/index.
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Figtree', 'ui-sans-serif', 'system-ui', '-apple-system', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'sans-serif'],
                    },
                    colors: {
                        elevate: {
                            dark: '#2c3f61',      // Biru Navy (Teks utama, Tombol Utama)
                            primary: '#0d52a1',   // Biru Pekat (Icon hover, aksen teks)
                            accent: '#56bbf1',    // Biru Muda/Cyan (Highlight, Shadow)
                            surface: '#ffffff',   // Background dasar card
                            soft: '#e5eff5',      // Background icon/badge
                            peach: {
                                light: '#f4d1c0',
                                DEFAULT: '#f9a282', // Icon edit
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
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        [x-cloak] { display: none !important; }

        @keyframes enter {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-enter { animation: enter .4s ease-out; }
    </style>
</head>
<body class="bg-slate-50 text-elevate-dark font-sans antialiased h-screen overflow-hidden flex flex-col selection:bg-elevate-soft">

    <div x-data="scheduleDisplay()" x-init="initDisplay()" x-cloak class="w-full h-full flex flex-col p-6 lg:p-10 max-w-screen-2xl mx-auto relative overflow-hidden">
        <!-- Pesan Izin Audio (Muncul jika diblokir browser) -->
        <div x-show="audioBlocked" x-cloak 
             class="absolute top-8 left-1/2 transform -translate-x-1/2 z-50 bg-rose-500 text-white px-6 py-3 rounded-full shadow-lg font-bold flex items-center gap-3 cursor-pointer hover:scale-105 transition-transform" 
             @click="initAudio()">
            <i class="ph-bold ph-speaker-slash text-xl animate-pulse"></i>
            Suara diblokir browser. Klik di sini untuk mengizinkan bel berbunyi.
        </div>

        <!-- Efek Latar Belakang Halus -->
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-40 pointer-events-none -z-10 blur-3xl"></div>

        <!-- HERO / HEADER -->
        <div class="relative rounded-[2.5rem] bg-elevate-gradient-main p-6 lg:p-8 mb-6 border border-white/60 shadow-xl shadow-elevate-accent/10 overflow-hidden shrink-0">
            <div class="absolute -top-24 -right-24 w-72 h-72 bg-white/50 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-elevate-dark flex items-center justify-center shadow-lg shadow-elevate-dark/30 shrink-0">
                        <i class="ph-fill ph-bell-ringing text-2xl text-white"></i>
                    </div>
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/70 border border-elevate-accent/30 text-elevate-primary text-[10px] font-bold uppercase tracking-widest mb-1.5">
                            <i class="ph-fill ph-broadcast"></i> Layar Informasi Sekolah
                        </div>
                        <h1 class="text-2xl lg:text-3xl font-black tracking-tight text-elevate-dark">Jadwal Pembelajaran</h1>
                    </div>
                </div>

                <div class="flex items-center gap-4 w-full lg:w-auto justify-between lg:justify-end">
                    <div class="text-right">
                        <div class="text-4xl lg:text-5xl font-black text-elevate-dark font-mono tracking-tighter" x-text="currentTimeFormatted">00:00:00</div>
                        <div class="text-xs lg:text-sm font-bold text-elevate-primary/80 mt-0.5" x-text="currentDateFormatted">Memuat Tanggal...</div>
                    </div>

                    <!-- Tombol Edit -> mengarah ke halaman admin schedules/index (perlu login di sana). Warna peach dipakai
                         karena di tailwind.config.js Anda, elevate.peach memang ditandai khusus untuk "Icon edit". -->
                    <a href="{{ route('schedules.index') }}"
                       class="inline-flex items-center gap-2 bg-elevate-peach-dark hover:bg-elevate-peach text-white font-bold text-sm px-5 py-3 rounded-2xl shadow-lg shadow-elevate-peach-dark/30 transition-all active:scale-95 shrink-0">
                        <i class="ph-bold ph-pencil-simple text-lg"></i>
                        <span class="hidden sm:inline">Edit Jadwal</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Konten Utama -->
        <div class="flex-1 grid grid-cols-1 lg:grid-cols-12 gap-6 min-h-0">

            <!-- Panel Kiri: Status Saat Ini -->
            <div class="lg:col-span-5 flex flex-col">
                <div class="rounded-[2.5rem] p-8 text-white shadow-xl shadow-elevate-primary/20 flex-1 flex flex-col justify-center relative overflow-hidden bg-elevate-dark">
                    <!-- Foto sekolah sebagai latar. Taruh file foto Anda di public/images/sekolah-hero.jpg,
                         atau ganti path di bawah kalau nama/lokasinya beda. Kalau file belum ada,
                         onerror menyembunyikannya supaya tetap tampil rapi (gradien polos). -->
                    <img src="{{ asset('images/netila.jpg') }}" alt=""
                         class="absolute inset-0 w-full h-full object-cover"
                         onerror="this.style.display='none'">

                    <!-- Overlay gradien elevate di atas foto supaya teks tetap terbaca.
                         Turunkan angka opacity (mis. /90 -> /70) kalau ingin fotonya lebih terlihat. -->
                    <div class="absolute inset-0 bg-gradient-to-br from-elevate-primary/90 via-elevate-primary/80 to-elevate-dark/95"></div>

                    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white opacity-10 rounded-full blur-2xl"></div>

                    <template x-if="isHoliday">
                        <div class="text-center relative z-10 animate-enter">
                            <span class="inline-flex items-center gap-1.5 bg-white text-elevate-primary px-4 py-1.5 rounded-full text-xs font-bold tracking-widest uppercase shadow-sm">
                                <i class="ph-fill ph-sun"></i> Status Hari Ini
                            </span>
                            <h2 class="text-4xl font-black mt-6 mb-2">Hari Libur</h2>
                            <p class="text-lg text-white/80 font-semibold" x-text="holidayReason"></p>
                        </div>
                    </template>

                    <template x-if="!isHoliday">
                        <div class="relative z-10 animate-enter">
                            <div class="inline-flex items-center gap-2 bg-white/15 border border-white/30 backdrop-blur-sm px-4 py-1.5 rounded-full text-xs font-bold tracking-widest uppercase mb-6">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                Kegiatan Saat Ini / Berikutnya
                            </div>
                            <h2 class="text-4xl lg:text-5xl font-black leading-tight tracking-tight mb-4" x-text="nextActivity.name">Memuat...</h2>
                            <div class="flex items-center gap-3">
                                <i class="ph-fill ph-clock text-3xl text-white/70"></i>
                                <span class="text-4xl font-mono font-bold text-white" x-text="nextActivity.time">--:--</span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Panel Kanan: Daftar Jadwal Lengkap -->
            <div class="lg:col-span-7 bg-white shadow-sm border border-slate-100 rounded-[2.5rem] p-6 lg:p-8 flex flex-col h-full relative overflow-hidden">
                <div class="absolute top-0 right-0 w-72 h-72 bg-elevate-primary/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>

                <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100 relative z-10">
                    <h3 class="text-lg lg:text-xl font-black text-elevate-dark flex items-center gap-2">
                        <i class="ph-fill ph-calendar-blank text-elevate-primary"></i>
                        Jadwal Hari <span x-text="dayName"></span>
                    </h3>
                    <span class="inline-flex items-center gap-1.5 bg-elevate-soft text-elevate-primary px-3 py-1.5 rounded-xl text-xs font-bold border border-elevate-accent/30">
                        <span x-text="schedules.length">0</span> Sesi
                    </span>
                </div>

                <!-- List Jadwal (Scrollable) -->
                <div class="flex-1 overflow-y-auto pr-2 space-y-3 relative z-10">
                    <template x-if="schedules.length === 0 && !isHoliday">
                        <div class="flex flex-col items-center justify-center h-full text-slate-400">
                            <i class="ph-duotone ph-calendar-x text-6xl mb-4 text-slate-300"></i>
                            <p class="text-lg font-semibold">Tidak ada jadwal bel untuk hari ini.</p>
                        </div>
                    </template>

                    <template x-for="(schedule, index) in schedules" :key="index">
                        <div class="flex items-center justify-between p-4 rounded-2xl border relative overflow-hidden transition-all duration-300"
                             :class="{
                                 'bg-slate-50 border-slate-200 opacity-60': isPast(schedule.trigger_time),
                                 'bg-white border-elevate-accent/40 shadow-md shadow-elevate-primary/10 scale-[1.02]': isCurrent(schedule.trigger_time, index),
                                 'bg-white border-slate-100 hover:border-slate-200': !isPast(schedule.trigger_time) && !isCurrent(schedule.trigger_time, index)
                             }">

                            <div class="absolute top-0 left-0 w-1 h-full"
                                 :class="isCurrent(schedule.trigger_time, index) ? 'bg-elevate-primary' : 'bg-transparent'"></div>

                            <div class="flex items-center space-x-4 pl-2">
                                <div class="flex flex-col items-center justify-center w-16 h-14 rounded-xl font-mono font-black text-lg shrink-0"
                                     :class="isCurrent(schedule.trigger_time, index) ? 'bg-elevate-primary text-white shadow-inner' : 'bg-slate-100 text-slate-600'">
                                    <span x-text="formatTime(schedule.trigger_time)"></span>
                                </div>
                                <div>
                                    <h4 class="text-base lg:text-lg font-black"
                                        :class="isCurrent(schedule.trigger_time, index) ? 'text-elevate-primary' : 'text-slate-700'"
                                        x-text="schedule.activity_name"></h4>
                                    <p class="text-xs lg:text-sm font-bold mt-0.5"
                                       :class="isPast(schedule.trigger_time) ? 'text-slate-400' : (isCurrent(schedule.trigger_time, index) ? 'text-elevate-primary/70' : 'text-slate-400')"
                                       x-text="getStatusText(schedule.trigger_time, index)"></p>
                                </div>
                            </div>

                            <!-- Icon Bel (Muncul jika ada audio) -->
                            <template x-if="schedule.audio_file">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0"
                                     :class="isCurrent(schedule.trigger_time, index) ? 'bg-elevate-soft text-elevate-primary animate-pulse' : 'bg-slate-100 text-slate-400'">
                                    <i class="ph-fill ph-speaker-high text-lg"></i>
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
                            // Proteksi jika data bukan array
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
                    
                    // Proteksi jika jadwal benar-benar kosong
                    if (!this.schedules || this.schedules.length === 0) {
                        this.nextActivity = { name: 'Tidak ada jadwal', time: '--:--' };
                        return;
                    }

                    let nowStr = this.getNowStr();
                    let current = null;
                    let upcoming = null;

                    // Mengganti .slice() dengan for-loop murni agar bebas crash
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
    </script>
</body>
</html>