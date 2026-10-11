<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Portal Keamanan Ujian - SMPN 3 Lakbok') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
    
    <script src="https://unpkg.com/@phosphor-icons/web" async></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        html { scroll-behavior: smooth; }
        [x-cloak] { display: none !important; }

        .bg-grid-dark {
            background-image: linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 32px 32px;
        }
    </style>
</head>
<body class="font-sans antialiased bg-[#021124] text-white min-h-screen relative overflow-x-hidden selection:bg-sky-500 selection:text-white">
    
    <!-- GLOBAL FIXED BACKGROUND -->
    <div class="fixed inset-0 z-[-1] pointer-events-none overflow-hidden">
        <div class="absolute inset-0 bg-[#021124]"></div>
        <div class="absolute inset-0 bg-cover bg-center opacity-10 filter blur-[14px] scale-110 contrast-125 saturate-50" 
             style="background-image: url('{{ asset('images/netila.jpg') }}');"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_rgba(13,82,161,0.2)_0%,_rgba(2,17,36,0.85)_50%,_#021124_100%)]"></div>
        <div class="absolute inset-0 bg-gradient-to-tr from-[#021124] via-[#021124]/95 to-[#0d52a1]/25"></div>
        <div class="absolute inset-0 bg-grid-dark opacity-35"></div>

        <div class="absolute -top-32 -left-32 w-[600px] h-[600px] bg-sky-500/15 rounded-full blur-[160px] pointer-events-none"></div>
        <div class="absolute top-1/3 right-1/4 w-[550px] h-[550px] bg-blue-600/20 rounded-full blur-[180px] pointer-events-none"></div>
        <div class="absolute -bottom-32 left-1/3 w-[500px] h-[500px] bg-sky-400/10 rounded-full blur-[150px] pointer-events-none"></div>
    </div>

    <!-- NAVBAR DARK GLASSMORPHISM -->
    <nav class="bg-[#031d3d]/90 backdrop-blur-2xl border-b border-white/15 sticky top-0 z-50 shadow-[0_4px_30px_rgba(0,0,0,0.5)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <!-- Logo & Judul -->
                <div class="flex items-center gap-3 group">
                    <div class="w-11 h-11 bg-gradient-to-br from-sky-400 via-blue-600 to-sky-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-sky-500/30 p-0.5">
                        <div class="w-full h-full bg-[#021124] rounded-[14px] flex items-center justify-center text-sky-300 text-xl">
                            <i class="ph-bold ph-shield-check"></i>
                        </div>
                    </div>
                    <div class="leading-tight">
                        <h1 class="font-black text-white text-base sm:text-lg tracking-tight">Portal Ujian</h1>
                        <p class="text-[10px] font-bold text-sky-400 uppercase tracking-widest">Siswa &bull; CBT Security</p>
                    </div>
                </div>

                <!-- Menu Kanan (User Info & Logout) -->
                <div class="flex items-center gap-4">
                    <div class="hidden sm:block text-right mr-2">
                        @if(Auth::guard('student')->check())
                            <p class="text-sm font-bold text-white flex items-center justify-end gap-1.5">
                                <span>{{ Auth::guard('student')->user()->name }}</span>
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            </p>
                            <p class="text-[10px] text-slate-400 font-mono">NISN: {{ Auth::guard('student')->user()->student_id }}</p>
                        @endif
                    </div>

                    <!-- Tombol Logout -->
                    <form id="logoutForm" method="POST" action="{{ route('student.logout') }}">
                        @csrf
                        <button type="button" onclick="confirmLogout()" class="px-4 py-2 bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/30 text-rose-300 rounded-xl text-xs font-bold transition-all flex items-center gap-2 shadow-sm active:scale-95">
                            <i class="ph-bold ph-power text-base"></i>
                            <span class="hidden sm:inline">Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- CONTENT WRAPPER -->
    <div class="pt-8 pb-16 min-h-[calc(100vh-80px)] relative overflow-hidden flex flex-col justify-center">
        @if(isset($header))
            <header class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 relative z-10">
                <div class="bg-gradient-to-b from-[#031d3d]/90 to-[#020b18] rounded-[2rem] p-6 shadow-2xl border border-white/10">
                    {{ $header }}
                </div>
            </header>
        @endif

        <main class="relative z-10">
            {{ $slot }}
        </main>
    </div>

    {{-- SCRIPTS UNTUK ALERT FUNGSIONAL --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                customClass: {
                    popup: 'rounded-2xl shadow-xl border border-white/10 bg-slate-900 text-white mt-20'
                },
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });

            @if(session('success'))
                Toast.fire({ icon: 'success', title: "{{ session('success') }}" });
            @endif

            @if(session('error'))
                Toast.fire({ icon: 'error', title: "{{ session('error') }}" });
            @endif
        });

        function confirmLogout() {
            Swal.fire({
                title: 'Konfirmasi Keluar?',
                text: "Anda akan keluar dari sesi ujian siswa ini.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#475569',
                confirmButtonText: 'Ya, Keluar',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-[2rem] bg-slate-900 text-white border border-white/10'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logoutForm').submit();
                }
            });
        }
    </script>
</body>
</html>