<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Ujian Online') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    
    {{-- FIX SEB: Async scripts agar tidak memblokir render --}}
    <script src="https://unpkg.com/@phosphor-icons/web" async></script>
    
    {{-- SweetAlert2 untuk Alert Fungsional --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-[#f8fafc] text-[#2c3f61]">
    
    <!-- NAVBAR (MICROSOFT ELEVATE THEME - GLASSMORPHISM) -->
    <nav class="bg-white/80 backdrop-blur-lg border-b border-slate-200/60 fixed w-full z-50 top-0 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <!-- Logo & Judul -->
                <div class="flex items-center gap-4">
                    <div class="w-11 h-11 bg-gradient-to-br from-[#56bbf1] to-[#0d52a1] rounded-2xl flex items-center justify-center text-white shadow-md shadow-[#56bbf1]/30">
                        <i class="ph-bold ph-student text-2xl"></i>
                    </div>
                    <div class="leading-tight">
                        <h1 class="font-black text-[#2c3f61] text-lg tracking-tight">Portal Ujian</h1>
                        <p class="text-[10px] font-bold text-[#0d52a1] uppercase tracking-widest">Siswa</p>
                    </div>
                </div>

                <!-- Menu Kanan (User Info & Logout) -->
                <div class="flex items-center gap-4">
                    <div class="hidden md:block text-right mr-2">
                        @if(Auth::guard('student')->check())
                            <p class="text-sm font-bold text-[#2c3f61]">{{ Auth::guard('student')->user()->name }}</p>
                            <p class="text-xs text-slate-400 font-mono font-bold">{{ Auth::guard('student')->user()->student_id }}</p>
                        @endif
                    </div>

                    <!-- Tombol Logout -->
                    <form id="logoutForm" method="POST" action="{{ route('student.logout') }}">
                        @csrf
                        <button type="button" onclick="confirmLogout()" class="px-4 py-2.5 bg-white hover:bg-rose-50 border border-rose-100 hover:border-rose-200 text-rose-500 rounded-xl text-sm font-bold transition-all flex items-center gap-2 shadow-sm active:scale-95">
                            <i class="ph-bold ph-power text-lg"></i>
                            <span class="hidden sm:inline">Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- CONTENT WRAPPER -->
    <div class="pt-28 pb-12 min-h-screen relative overflow-hidden">
        {{-- Background Decoration (Elevate Soft Cyan) --}}
        <div class="absolute inset-0 z-0 pointer-events-none">
            <div class="absolute top-0 left-0 w-full h-[500px] bg-gradient-to-b from-[#e5eff5]/80 to-transparent"></div>
            <div class="absolute -top-32 -right-32 w-96 h-96 bg-[#56bbf1]/10 rounded-full blur-3xl"></div>
            <div class="absolute top-40 -left-32 w-80 h-80 bg-[#f4d1c0]/20 rounded-full blur-3xl"></div>
        </div>

        @if(isset($header))
            <header class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 relative z-10">
                <div class="bg-white rounded-[2rem] p-6 shadow-xl shadow-[#56bbf1]/5 border border-slate-100">
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
                    popup: 'rounded-2xl shadow-xl border border-slate-100 mt-20'
                },
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            @if(session('success')) Toast.fire({ icon: 'success', title: '{!! session('success') !!}' }); @endif
            @if(session('error')) Toast.fire({ icon: 'error', title: '{!! session('error') !!}' }); @endif
            @if(session('warning')) Toast.fire({ icon: 'warning', title: '{!! session('warning') !!}' }); @endif
            @if(session('info')) Toast.fire({ icon: 'info', title: '{!! session('info') !!}' }); @endif
        });

        function confirmLogout() {
            Swal.fire({
                title: 'Akhiri Sesi?',
                text: "Anda akan keluar dari portal ujian. Pastikan semua ujian Anda telah selesai dikumpulkan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Keluar',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-[2rem]',
                    confirmButton: 'rounded-xl font-bold px-6 py-3',
                    cancelButton: 'rounded-xl font-bold px-6 py-3'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Sedang mengeluarkan akun Anda',
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading() },
                        customClass: { popup: 'rounded-[2rem]' }
                    });
                    document.getElementById('logoutForm').submit();
                }
            });
        }
    </script>
</body>
</html>