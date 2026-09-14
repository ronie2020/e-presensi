<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login Ujian Berbasis Komputer (CBT) - {{ config('app.name', 'SMP Negeri 3 Lakbok') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts & Icons -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }

        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(-30px, 30px) scale(1.08); }
            66% { transform: translate(25px, -20px) scale(0.95); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob { animation: blob 8s infinite ease-in-out; }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }

        .glass-card {
            background: rgba(255, 255, 255, 0.90);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.7);
        }
    </style>
</head>
<body class="min-h-screen bg-slate-900 text-slate-800 relative overflow-x-hidden flex flex-col justify-between selection:bg-rose-600 selection:text-white">

    <!-- AMBIENT BACKGROUND WITH CBT THEME (ROSE / CRIMSON / AMBER) -->
    <div class="fixed inset-0 pointer-events-none -z-10 overflow-hidden bg-slate-950">
        <!-- Background Grid Pattern -->
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#1e293b15_1px,transparent_1px),linear-gradient(to_bottom,#1e293b15_1px,transparent_1px)] bg-[size:3rem_3rem] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_70%,transparent_100%)]"></div>

        <!-- Dynamic Vibrant Glowing Blobs (Rose / Crimson / Amber) -->
        <div class="absolute -top-32 -right-20 w-[550px] h-[550px] bg-rose-600/25 rounded-full blur-[130px] animate-blob"></div>
        <div class="absolute top-1/3 -left-32 w-[500px] h-[500px] bg-amber-500/20 rounded-full blur-[130px] animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-32 right-1/3 w-[600px] h-[600px] bg-red-700/20 rounded-full blur-[150px] animate-blob animation-delay-4000"></div>

        <!-- Pattern Overlay -->
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-[0.03] mix-blend-overlay"></div>
    </div>

    <!-- TOP NAVIGATION BAR -->
    <header class="w-full max-w-6xl mx-auto px-4 sm:px-6 py-6 flex items-center justify-between relative z-20">
        <a href="{{ url('/') }}" class="inline-flex items-center gap-3 group text-slate-300 hover:text-white transition-colors">
            <div class="w-10 h-10 rounded-xl bg-white/10 group-hover:bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/15 transition-all">
                <i class="ph-bold ph-arrow-left text-lg"></i>
            </div>
            <span class="text-sm font-semibold hidden sm:inline">Kembali ke Beranda</span>
        </a>

        <div class="flex items-center gap-2 sm:gap-3">
            <a href="{{ route('portal.index') }}" class="px-3.5 py-2 rounded-xl bg-white/10 hover:bg-white/15 backdrop-blur-md text-xs font-bold text-slate-200 hover:text-white border border-white/15 transition-all flex items-center gap-2">
                <i class="ph-bold ph-identification-card text-base text-cyan-400"></i>
                <span class="hidden md:inline">Portal Publik</span>
            </a>
            <a href="{{ route('student.login.learning') }}" class="px-3.5 py-2 rounded-xl bg-blue-500/20 hover:bg-blue-500/30 backdrop-blur-md text-xs font-bold text-blue-200 hover:text-white border border-blue-500/30 transition-all flex items-center gap-2">
                <i class="ph-bold ph-books text-base text-blue-400"></i>
                <span>Ruang Belajar (LMS)</span>
            </a>
        </div>
    </header>

    <!-- MAIN CONTENT CONTAINER -->
    <main class="flex-1 flex items-center justify-center px-4 sm:px-6 py-8 relative z-20">
        <div class="w-full max-w-md">

            <!-- BRANDING & ICON -->
            <div class="text-center mb-8">
                <div class="relative inline-block mb-4">
                    <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-3xl bg-gradient-to-tr from-rose-600 via-red-500 to-amber-500 p-[2px] shadow-2xl shadow-rose-600/30">
                        <div class="w-full h-full bg-slate-900 rounded-[22px] flex items-center justify-center p-3">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-full h-full object-contain" onerror="this.src='https://ui-avatars.com/api/?name=SMP3&background=e11d48&color=fff&size=128'; this.onerror=null;">
                        </div>
                    </div>
                    <span class="absolute -bottom-2 -right-2 w-8 h-8 rounded-xl bg-rose-600 text-white flex items-center justify-center text-base shadow-lg shadow-rose-600/40 border-2 border-slate-900">
                        <i class="ph-bold ph-monitor-play"></i>
                    </span>
                </div>

                <!-- BADGE -->
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-rose-500/15 border border-rose-400/30 text-rose-300 text-xs font-bold uppercase tracking-wider mb-3">
                    <span class="w-2 h-2 rounded-full bg-rose-400 animate-ping"></span>
                    <span>Sistem Ujian Online (CBT)</span>
                </div>

                <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                    Ujian Berbasis Komputer
                </h1>
                <p class="text-sm text-slate-400 mt-2 max-w-sm mx-auto font-medium leading-relaxed">
                    Masukkan NISN untuk memasuki ruang ujian online, PTS, PAS, atau Asesmen Sekolah.
                </p>
            </div>

            <!-- LOGIN CARD -->
            <div class="glass-card rounded-[2rem] p-7 sm:p-9 shadow-2xl shadow-black/40 relative">

                <!-- Alert Error / Notification -->
                @if (session('error'))
                    <div class="mb-5 bg-rose-50 text-rose-600 px-4 py-3.5 rounded-2xl text-sm font-medium border border-rose-200 flex items-start gap-3 shadow-sm animate-shake">
                        <i class="ph-fill ph-warning-circle text-xl text-rose-500 shrink-0 mt-0.5"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @error('student_id')
                    <div class="mb-5 bg-amber-50 text-amber-800 px-4 py-3.5 rounded-2xl text-xs font-semibold border border-amber-200 flex items-start gap-3 shadow-sm">
                        <i class="ph-fill ph-clock-countdown text-xl text-amber-600 shrink-0 mt-0.5"></i>
                        <span>Terlalu banyak percobaan login. Silakan tunggu 1 menit sebelum mencoba lagi.</span>
                    </div>
                @enderror

                <!-- LOGIN FORM -->
                <form method="POST" action="{{ route('student.login.post') }}" class="space-y-5"
                      x-data="{ isLoggingIn: false }" @submit="isLoggingIn = true">
                    @csrf

                    <!-- Flag Asal: CBT (Ruang Ujian) -->
                    <input type="hidden" name="intended_app" value="cbt">

                    <!-- Input NISN -->
                    <div class="space-y-1.5">
                        <label for="student_id" class="block text-xs font-bold uppercase tracking-wider text-slate-600 ml-1">
                            NISN Peserta Ujian
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-rose-600 transition-colors">
                                <i class="ph-bold ph-identification-card text-xl"></i>
                            </div>
                            <input type="text"
                                   id="student_id"
                                   name="student_id"
                                   value="{{ old('student_id') }}"
                                   required
                                   autofocus
                                   autocomplete="off"
                                   placeholder="Ketik 10 digit NISN Anda"
                                   class="w-full pl-12 pr-4 py-4 rounded-2xl bg-slate-50 border border-slate-200 focus:border-rose-500 focus:bg-white focus:ring-4 focus:ring-rose-100 text-slate-900 font-bold placeholder-slate-400 text-base transition-all outline-none">
                        </div>
                        <p class="text-[11px] text-slate-500 ml-1 font-medium">
                            <i class="ph-bold ph-shield-check text-rose-500"></i> Pastikan Anda berada di ruang ujian atau memiliki sesi aktif.
                        </p>
                    </div>

                    <!-- SUBMIT BUTTON -->
                    <button type="submit"
                            :disabled="isLoggingIn"
                            class="w-full py-4 px-6 rounded-2xl bg-gradient-to-r from-rose-600 via-rose-700 to-red-600 hover:from-rose-500 hover:via-rose-600 hover:to-red-500 text-white font-black text-sm sm:text-base tracking-wide shadow-xl shadow-rose-600/30 hover:shadow-rose-600/50 hover:-translate-y-0.5 active:translate-y-0 disabled:opacity-75 disabled:cursor-not-allowed transition-all flex items-center justify-center gap-2 group">
                        <template x-if="!isLoggingIn">
                            <span class="flex items-center gap-2">
                                <span>Masuk Ruang Ujian</span>
                                <i class="ph-bold ph-arrow-right text-lg group-hover:translate-x-1 transition-transform"></i>
                            </span>
                        </template>
                        <template x-if="isLoggingIn">
                            <span class="flex items-center gap-2 text-white">
                                <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Menyiapkan Lembar Ujian...</span>
                            </span>
                        </template>
                    </button>
                </form>

                <!-- CARD FOOTER HELPER -->
                <div class="mt-6 pt-5 border-t border-slate-200/80 text-center">
                    <p class="text-xs text-slate-500 font-medium">
                        Kendala soal atau token ujian? 
                        <span class="font-bold text-rose-700">Hubungi Proktor / Pengawas</span>
                    </p>
                </div>
            </div>

            <!-- SEB INFO / COMPATIBILITY HINT -->
            <div class="mt-6 p-4 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md flex items-center justify-between text-xs">
                <div class="flex items-center gap-2 text-slate-300">
                    <i class="ph-bold ph-shield-star text-emerald-400 text-lg"></i>
                    <span>Ujian menggunakan Safe Exam Browser?</span>
                </div>
                <a href="{{ route('seb.login') }}" class="font-bold text-emerald-400 hover:text-emerald-300 flex items-center gap-1 transition-colors">
                    <span>SEB Login</span>
                    <i class="ph-bold ph-arrow-right"></i>
                </a>
            </div>

            <!-- ACTIVE EXAM SCHEDULE WIDGET -->
            <div class="mt-8 glass-card rounded-[2rem] p-6 sm:p-7 shadow-xl">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl bg-amber-500/20 flex items-center justify-center border border-amber-500/30">
                        <i class="ph-fill ph-calendar-check text-xl text-amber-500"></i>
                    </div>
                    <div>
                        <h2 class="text-slate-800 font-bold text-base">Jadwal Ujian Aktif</h2>
                        <p class="text-xs text-slate-500 font-medium">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</p>
                    </div>
                </div>

                @if(isset($activeExams) && $activeExams->count() > 0)
                    <div class="space-y-3">
                        @foreach($activeExams as $exam)
                            <div class="p-4 rounded-2xl bg-white/50 border border-white flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-sm hover:shadow-md transition-shadow">
                                <div>
                                    <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                                        <i class="ph-bold ph-file-text text-rose-500"></i>
                                        {{ $exam->name }}
                                    </h3>
                                    <div class="flex items-center gap-3 mt-1 text-[11px] text-slate-500 font-medium">
                                        <span class="flex items-center gap-1">
                                            <i class="ph-bold ph-clock"></i>
                                            {{ \Carbon\Carbon::parse($exam->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($exam->end_time)->format('H:i') }}
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <i class="ph-bold ph-users"></i>
                                            {{ $exam->event ? $exam->event->name : 'Semua Peserta' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="shrink-0">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-200 text-[10px] font-bold uppercase tracking-wider">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Berlangsung
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6 px-4 bg-white/40 rounded-2xl border border-white border-dashed">
                        <i class="ph-duotone ph-coffee text-4xl text-slate-400 mb-2"></i>
                        <h3 class="text-sm font-bold text-slate-700">Tidak ada jadwal hari ini</h3>
                        <p class="text-xs text-slate-500 font-medium mt-1">Silakan istirahat atau pelajari materi selanjutnya.</p>
                    </div>
                @endif
            </div>

        </div>
    </main>

    <!-- FOOTER -->
    <footer class="w-full py-5 text-center text-xs text-slate-500 relative z-20 font-medium">
        &copy; {{ date('Y') }} {{ config('app.name', 'SMP Negeri 3 Lakbok') }} &bull; Computer-Based Testing System
    </footer>

</body>
</html>
