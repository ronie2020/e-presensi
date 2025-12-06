<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Ujian Online') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-50">
    
    <!-- NAVBAR SEDERHANA -->
    <nav class="bg-white border-b border-slate-200 fixed w-full z-30 top-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo & Judul -->
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center text-white">
                        <img src="{{ asset('images/logo.png') }}" class="w-10 h-10 object-contain" alt="Logo">
                    </div>
                    <div class="leading-tight">
                        <h1 class="font-bold text-slate-800 text-lg">Portal Siswa</h1>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Ujian Online</p>
                    </div>
                </div>

                <!-- Menu Kanan (User Info & Logout) -->
                <div class="flex items-center gap-4">
                    <div class="hidden md:block text-right mr-2">
                        <!-- Pastikan Auth guard sesuai -->
                        <p class="text-sm font-bold text-slate-700">{{ Auth::guard('student')->user()->name ?? 'Siswa' }}</p>
                        <p class="text-xs text-slate-500">{{ Auth::guard('student')->user()->student_id ?? '-' }}</p>
                    </div>

                    <!-- Tombol Logout -->
                    <form method="POST" action="{{ route('student.logout') }}">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-red-50 text-red-600 border border-red-100 rounded-lg text-sm font-bold hover:bg-red-600 hover:text-white transition flex items-center gap-2">
                            <i class="ph-bold ph-sign-out"></i>
                            <span class="hidden sm:inline">Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- CONTENT WRAPPER -->
    <div class="pt-20 pb-12 min-h-screen">
        @if(isset($header))
            <header class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                    {{ $header }}
                </div>
            </header>
        @endif

        <main>
            {{ $slot }}
        </main>
    </div>

</body>
</html>
