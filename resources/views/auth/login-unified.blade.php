<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Masuk - {{ config('app.name', 'SMP Negeri 3 Lakbok') }}</title>

        <!-- PWA META TAGS -->
        <link rel="manifest" href="{{ asset('manifest-guru.json') }}">
        <meta name="theme-color" content="#032b5b">
        <link rel="apple-touch-icon" href="{{ asset('icons/icon-guru-192x192.png') }}">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="SIMADU Lakbok">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://unpkg.com/@phosphor-icons/web"></script>

        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            [x-cloak] { display: none !important; }

            /* Efek Grid Halus untuk Background */
            .bg-grid-pattern {
                background-image: linear-gradient(to right, rgba(13, 82, 161, 0.03) 1px, transparent 1px),
                                  linear-gradient(to bottom, rgba(13, 82, 161, 0.03) 1px, transparent 1px);
                background-size: 32px 32px;
            }

            /* Animasi Masuk Halus */
            @keyframes fadeInUp {
                from { opacity: 0; transform: translateY(24px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-fade-in-up {
                animation: fadeInUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
                opacity: 0;
            }
            .delay-100 { animation-delay: 100ms; }
            .delay-200 { animation-delay: 200ms; }
            .delay-300 { animation-delay: 300ms; }

            /* Shimmer button effect */
            @keyframes shimmer {
                0% { transform: translateX(-100%); }
                100% { transform: translateX(100%); }
            }

            /* Tab indicator sliding animation */
            .tab-slider {
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            /* Form slide animation */
            @keyframes slideIn {
                from { opacity: 0; transform: translateX(12px); }
                to { opacity: 1; transform: translateX(0); }
            }
            .form-animate {
                animation: slideIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            }
        </style>
    </head>

    <body class="text-elevate-dark antialiased min-h-screen bg-slate-50 bg-grid-pattern relative overflow-x-hidden flex flex-col md:flex-row selection:bg-elevate-primary selection:text-white">

        {{-- ===== TENTUKAN TAB AKTIF AWAL ===== --}}
        @php
            // Prioritas: error validasi > variable dari controller > default 'guru'
            if ($errors->has('student_id')) {
                $initTab = 'siswa';
            } elseif ($errors->has('email') || $errors->has('password')) {
                $initTab = 'guru';
            } else {
                $initTab = $activeTab ?? 'guru';
            }
        @endphp

        <!-- DEKORASI BACKGROUND -->
        <div class="hidden md:block absolute -top-32 -left-32 w-[500px] h-[500px] bg-elevate-accent/10 rounded-full blur-[100px] pointer-events-none z-0"></div>
        <div class="hidden md:block absolute bottom-0 right-0 w-[600px] h-[600px] bg-elevate-primary/5 rounded-tl-[100%] pointer-events-none z-0"></div>

        <!-- BACKGROUND MOBILE -->
        <div class="md:hidden absolute inset-0 z-0 overflow-hidden">
            <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('images/netila.jpg') }}');"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-elevate-dark/90 via-elevate-dark/80 to-slate-900/95 backdrop-blur-sm"></div>
        </div>

        <main class="flex w-full min-h-screen relative z-10 flex-col md:flex-row"
              x-data="{ tab: '{{ $initTab }}' }">

            {{-- ========================= PANEL KIRI (DESKTOP) ========================= --}}
            <section class="hidden md:flex md:w-1/2 lg:w-3/5 p-12 lg:p-16 text-white flex-col justify-between relative shadow-[20px_0_40px_rgba(0,0,0,0.15)] z-20 rounded-br-[4rem] min-h-screen overflow-hidden group">

                <!-- Background Image -->
                <div class="absolute inset-0 bg-cover bg-center transition-transform duration-1000 group-hover:scale-105"
                     style="background-image: url('{{ asset('images/netila.jpg') }}');"></div>

                <!-- Overlay Gradien -->
                <div class="absolute inset-0 bg-gradient-to-r from-[#032b5b]/95 via-[#032b5b]/80 to-[#032b5b]/30 z-0"></div>

                <!-- Konten Teks -->
                <div class="relative z-20 animate-fade-in-up delay-100">
                    <div class="flex items-center gap-4 mb-16">
                        <div class="p-1.5 bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 shadow-lg shrink-0">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-12 h-12 object-contain">
                        </div>
                        <div>
                            <h1 class="font-black text-2xl tracking-tight leading-none drop-shadow-md">SMP NEGERI 3 LAKBOK</h1>
                            <p class="text-[11px] text-elevate-accent font-black uppercase tracking-widest drop-shadow-md mt-1">Sistem Informasi Terpadu</p>
                        </div>
                    </div>

                    <div class="max-w-xl">
                        <h2 class="text-4xl lg:text-6xl font-black mb-6 leading-tight tracking-tight">
                            SIMADU <br>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-elevate-accent via-[#85d1f8] to-white">TERINTEGRASI</span> <br>
                            Untuk Siswa & Guru
                        </h2>
                        <p class="text-slate-200 text-lg lg:text-xl font-medium leading-relaxed max-w-md">
                            Satu pintu masuk untuk absensi, manajemen jadwal pelajaran, bank soal, dan administrasi sekolah.
                        </p>
                    </div>
                </div>

                <!-- Lencana Keamanan -->
                <div class="relative z-20 mt-12 flex items-center gap-3 text-xs font-bold text-elevate-accent uppercase tracking-widest animate-fade-in-up delay-200 bg-white/5 w-max px-5 py-3 rounded-2xl backdrop-blur-md border border-white/10 shadow-lg">
                    <i class="ph-duotone ph-shield-check text-xl"></i> Akses Terlindungi Sistem
                </div>
            </section>

            {{-- ========================= PANEL KANAN (FORM) ========================= --}}
            <section class="w-full md:w-1/2 lg:w-2/5 flex flex-col justify-center items-center p-6 sm:p-12 relative z-20 min-h-screen">

                <!-- Glowing effect desktop -->
                <div class="hidden md:block absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-3/4 h-3/4 bg-white/50 rounded-full blur-[100px] pointer-events-none -z-10"></div>

                <!-- Mobile Logo -->
                <div class="md:hidden text-center mb-10 pt-8 animate-fade-in-up">
                    <div class="w-24 h-24 mx-auto mb-5 p-2 bg-white/10 backdrop-blur-md rounded-[1.5rem] border border-white/20 shadow-xl">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo Sekolah" class="w-full h-full object-contain">
                    </div>
                    <h1 class="font-black text-3xl text-white tracking-tight drop-shadow-md">SIMADU LAKBOK</h1>
                    <p class="text-[11px] text-elevate-accent font-black uppercase tracking-widest mt-2 drop-shadow-md bg-white/10 w-fit mx-auto px-4 py-1.5 rounded-full border border-white/10">Portal Siswa & Guru</p>
                </div>

                <!-- Form Box -->
                <div class="w-full max-w-[420px] animate-fade-in-up delay-200 relative">

                    <!-- Kotak Glassmorphism -->
                    <div class="bg-white/90 md:bg-white/80 backdrop-blur-xl rounded-[2.5rem] shadow-[0_20px_50px_rgba(13,82,161,0.1)] border border-white p-8 sm:p-10 relative overflow-hidden">

                        <!-- Decorative gradient -->
                        <div class="absolute -top-16 -right-16 w-32 h-32 bg-gradient-to-br from-elevate-accent/20 to-transparent rounded-full blur-xl pointer-events-none"></div>

                        {{-- ===== TAB SWITCHER ===== --}}
                        <div class="relative mb-8 bg-slate-100 p-1.5 rounded-2xl flex gap-1">

                            {{-- Tab Guru --}}
                            <button type="button"
                                    @click="tab = 'guru'"
                                    :class="tab === 'guru'
                                        ? 'bg-white text-elevate-dark shadow-md shadow-elevate-primary/10 ring-1 ring-slate-200/60'
                                        : 'text-slate-400 hover:text-slate-600'"
                                    class="relative flex-1 flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl text-sm font-bold transition-all duration-300 focus:outline-none">
                                <i class="ph-bold ph-chalkboard-teacher text-base"
                                   :class="tab === 'guru' ? 'text-elevate-primary' : 'text-slate-400'"></i>
                                <span>Guru / Admin</span>
                            </button>

                            {{-- Tab Siswa --}}
                            <button type="button"
                                    @click="tab = 'siswa'"
                                    :class="tab === 'siswa'
                                        ? 'bg-white text-elevate-dark shadow-md shadow-elevate-primary/10 ring-1 ring-slate-200/60'
                                        : 'text-slate-400 hover:text-slate-600'"
                                    class="relative flex-1 flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl text-sm font-bold transition-all duration-300 focus:outline-none">
                                <i class="ph-bold ph-student text-base"
                                   :class="tab === 'siswa' ? 'text-elevate-primary' : 'text-slate-400'"></i>
                                <span>Siswa</span>
                            </button>
                        </div>

                        {{-- ===== FORM GURU ===== --}}
                        <div x-show="tab === 'guru'" x-cloak class="form-animate">
                            <div class="mb-6 text-center md:text-left">
                                <h3 class="text-2xl font-black text-elevate-dark mb-1">Masuk sebagai Guru</h3>
                                <p class="text-sm font-semibold text-slate-400">Masukkan email & password Anda.</p>
                            </div>

                            <!-- Session Status -->
                            @if (session('status'))
                                <div class="mb-4 bg-emerald-50 text-emerald-600 px-4 py-3 rounded-xl text-sm font-medium border border-emerald-100 flex items-center gap-2">
                                    <i class="ph-bold ph-check-circle text-lg"></i>
                                    {{ session('status') }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('login') }}" class="space-y-5"
                                  x-data="{ isLoggingIn: false }" @submit="isLoggingIn = true">
                                @csrf

                                <!-- Email -->
                                <div class="space-y-1">
                                    <label for="email" class="text-sm font-bold text-slate-700 ml-1">Email / NIP</label>
                                    <div class="relative group">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-blue-600 transition-colors">
                                            <i class="ph-duotone ph-envelope-simple text-xl"></i>
                                        </div>
                                        <x-text-input id="email"
                                            class="block w-full rounded-xl border-slate-200 bg-slate-50/50 py-3 pl-11 pr-4 text-sm focus:border-blue-500 focus:bg-white focus:ring-blue-500 transition-all shadow-sm"
                                            type="email" name="email" :value="old('email')"
                                            required autofocus autocomplete="username"
                                            placeholder="nama@sekolah.sch.id" />
                                    </div>
                                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-rose-500 font-medium ml-1" />
                                </div>

                                <!-- Password -->
                                <div class="space-y-1">
                                    <div class="flex justify-between items-center">
                                        <label for="password" class="text-sm font-bold text-slate-700 ml-1">Password</label>
                                        @if (Route::has('password.request'))
                                            <a href="{{ route('password.request') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition-colors hover:underline">
                                                Lupa Password?
                                            </a>
                                        @endif
                                    </div>
                                    <div class="relative group" x-data="{ show: false }">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-blue-600 transition-colors">
                                            <i class="ph-duotone ph-lock-key text-xl"></i>
                                        </div>
                                        <x-text-input id="password"
                                            class="block w-full rounded-xl border-slate-200 bg-slate-50/50 py-3 pl-11 pr-12 text-sm focus:border-blue-500 focus:bg-white focus:ring-blue-500 transition-all shadow-sm"
                                            ::type="show ? 'text' : 'password'"
                                            name="password" required autocomplete="current-password"
                                            placeholder="••••••••" />
                                        <button type="button" @click="show = !show"
                                                class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 hover:text-slate-600 cursor-pointer focus:outline-none transition-colors"
                                                tabindex="-1">
                                            <i class="ph-bold" :class="show ? 'ph-eye' : 'ph-eye-slash'"></i>
                                        </button>
                                    </div>
                                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-rose-500 font-medium ml-1" />
                                </div>

                                <!-- Remember Me -->
                                <div class="flex items-center pt-1">
                                    <label for="remember_me" class="inline-flex items-center cursor-pointer group select-none">
                                        <input id="remember_me" type="checkbox"
                                               class="rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500 cursor-pointer"
                                               name="remember">
                                        <span class="ml-2 text-xs font-bold text-slate-500 group-hover:text-blue-600 transition-colors">Ingat Saya</span>
                                    </label>
                                </div>

                                <!-- Tombol Submit Guru -->
                                <button type="submit"
                                        :disabled="isLoggingIn"
                                        :class="{ 'opacity-75 cursor-wait': isLoggingIn, 'hover:bg-blue-600 hover:shadow-lg hover:shadow-blue-900/30 active:scale-[0.98]': !isLoggingIn }"
                                        class="group relative flex w-full justify-center rounded-xl bg-slate-900 py-3.5 px-4 text-sm font-bold text-white transition-all focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 overflow-hidden transform">

                                    <span x-show="!isLoggingIn" class="relative z-10 flex items-center gap-2">
                                        Masuk sebagai Guru <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
                                    </span>
                                    <span x-show="isLoggingIn" class="relative z-10 flex items-center gap-2" style="display:none;">
                                        <i class="ph-bold ph-spinner animate-spin text-lg"></i> Memverifikasi...
                                    </span>
                                    <div x-show="!isLoggingIn" class="absolute inset-0 -translate-x-full group-hover:animate-[shimmer_1.5s_infinite] bg-gradient-to-r from-transparent via-white/10 to-transparent z-0"></div>
                                </button>
                            </form>
                        </div>

                        {{-- ===== FORM SISWA ===== --}}
                        <div x-show="tab === 'siswa'" x-cloak class="form-animate">
                            <div class="mb-6 text-center md:text-left">
                                <h3 class="text-2xl font-black text-elevate-dark mb-1">Masuk sebagai Siswa</h3>
                                <p class="text-sm font-semibold text-slate-400">Masukkan NISN / NIS kamu.</p>
                            </div>

                            <!-- Error Siswa (NISN tidak ditemukan) -->
                            @if (session('error'))
                                <div class="mb-4 bg-rose-50 text-rose-600 px-4 py-3 rounded-xl text-sm font-medium border border-rose-100 flex items-center gap-2">
                                    <i class="ph-bold ph-warning-circle text-lg"></i>
                                    {{ session('error') }}
                                </div>
                            @endif

                            <!-- Error Throttle (terlalu banyak percobaan) -->
                            @error('throttle')
                                <div class="mb-4 bg-amber-50 text-amber-700 px-4 py-3 rounded-xl text-sm font-medium border border-amber-200 flex items-start gap-2">
                                    <i class="ph-bold ph-clock-countdown text-lg shrink-0 mt-0.5"></i>
                                    <span>Terlalu banyak percobaan. Silakan tunggu sebentar sebelum mencoba lagi.</span>
                                </div>
                            @enderror

                            <form method="POST" action="{{ route('student.login.post') }}" class="space-y-5"
                                  x-data="{ isLoggingIn: false }" @submit="isLoggingIn = true">
                                @csrf

                                <!-- Input NISN -->
                                <div class="space-y-1">
                                    <label for="student_id" class="text-sm font-bold text-slate-700 ml-1">NISN / NIS</label>
                                    <div class="relative group">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-blue-600 transition-colors">
                                            <i class="ph-duotone ph-identification-card text-xl"></i>
                                        </div>
                                        <input id="student_id" name="student_id" type="text"
                                               autocomplete="off" required
                                               :autofocus="tab === 'siswa'"
                                               value="{{ old('student_id') }}"
                                               class="block w-full rounded-xl border border-slate-200 bg-slate-50/50 py-3 pl-11 pr-4 text-sm text-slate-900 placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-1 focus:ring-blue-500 transition-all shadow-sm outline-none"
                                               placeholder="Contoh: 0056789012">
                                    </div>
                                    <x-input-error :messages="$errors->get('student_id')" class="mt-1 text-xs text-rose-500 font-medium ml-1" />
                                </div>

                                {{-- intended_app default kosong = portal siswa --}}
                                <input type="hidden" name="intended_app" value="">

                                <!-- Tombol Submit Siswa -->
                                <button type="submit"
                                        :disabled="isLoggingIn"
                                        :class="{ 'opacity-75 cursor-wait': isLoggingIn, 'hover:from-blue-500 hover:to-cyan-500 hover:shadow-lg active:scale-[0.98]': !isLoggingIn }"
                                        class="group relative flex w-full justify-center rounded-xl bg-gradient-to-r from-blue-600 to-cyan-600 py-3.5 px-4 text-sm font-bold text-white transition-all focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 overflow-hidden transform shadow-md shadow-blue-600/20">

                                    <span x-show="!isLoggingIn" class="relative z-10 flex items-center gap-2">
                                        Masuk Portal Siswa <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
                                    </span>
                                    <span x-show="isLoggingIn" class="relative z-10 flex items-center gap-2" style="display:none;">
                                        <i class="ph-bold ph-spinner animate-spin text-lg"></i> Mencari data...
                                    </span>
                                    <div x-show="!isLoggingIn" class="absolute inset-0 -translate-x-full group-hover:animate-[shimmer_1.5s_infinite] bg-gradient-to-r from-transparent via-white/20 to-transparent z-0"></div>
                                </button>
                            </form>

                            <!-- PINTASAN LANGSUNG LAYANAN KHUSUS SISWA -->
                            <div class="mt-6 pt-5 border-t border-slate-100">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-3 text-center">
                                    Atau Masuk Langsung ke Layanan Khusus
                                </p>
                                <div class="grid grid-cols-2 gap-2.5">
                                    <a href="{{ route('student.login.learning') }}" class="p-3 rounded-xl bg-blue-50/70 hover:bg-blue-100/70 border border-blue-100 transition-all flex flex-col items-center text-center group">
                                        <div class="w-8 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center mb-1.5 shadow-sm group-hover:scale-110 transition-transform">
                                            <i class="ph-bold ph-books text-base"></i>
                                        </div>
                                        <span class="text-xs font-black text-blue-900 leading-tight">Ruang Belajar</span>
                                        <span class="text-[10px] text-blue-600 font-semibold mt-0.5">E-Learning & Tugas</span>
                                    </a>

                                    <a href="{{ route('student.login.cbt') }}" class="p-3 rounded-xl bg-rose-50/70 hover:bg-rose-100/70 border border-rose-100 transition-all flex flex-col items-center text-center group">
                                        <div class="w-8 h-8 rounded-lg bg-rose-600 text-white flex items-center justify-center mb-1.5 shadow-sm group-hover:scale-110 transition-transform">
                                            <i class="ph-bold ph-monitor-play text-base"></i>
                                        </div>
                                        <span class="text-xs font-black text-rose-900 leading-tight">Ruang Ujian</span>
                                        <span class="text-[10px] text-rose-600 font-semibold mt-0.5">Ujian CBT Online</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>{{-- END: Form Box --}}

                    <!-- Footer / Back Link -->
                    <div class="mt-8 text-center pb-6 animate-fade-in-up delay-300">
                        <div class="flex flex-col items-center gap-4">
                            <a href="{{ url('/') }}"
                               class="inline-flex items-center gap-2 text-sm font-bold text-white/80 md:text-slate-500 hover:text-white md:hover:text-elevate-primary transition-all group px-5 py-2.5 rounded-full md:hover:bg-slate-200/50 hover:bg-white/10 md:bg-transparent bg-white/5 border border-transparent md:hover:border-slate-200 hover:border-white/10">
                                <div class="w-6 h-6 rounded-full bg-white/10 md:bg-slate-100 flex items-center justify-center group-hover:bg-white md:group-hover:bg-elevate-soft md:group-hover:text-elevate-primary transition-colors text-current">
                                    <i class="ph-bold ph-arrow-left"></i>
                                </div>
                                Kembali ke Beranda
                            </a>

                            <p class="md:hidden text-[11px] text-white/50 font-semibold mt-2 tracking-wide">
                                &copy; {{ date('Y') }} SMP Negeri 3 Lakbok.
                            </p>
                        </div>
                    </div>

                </div>{{-- END: max-w --}}
            </section>
        </main>

        <!-- PWA SERVICE WORKER -->
        <script>
            if ('serviceWorker' in navigator && window.isSecureContext) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register('/sw-guru.js')
                        .catch(error => {
                            console.warn('PWA Service Worker gagal didaftarkan:', error);
                        });
                });
            }
        </script>
    </body>
</html>
