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
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.tailwindcss.com"></script> 

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-800">
    
    <!-- NAVBAR (DARK BLUE THEME) -->
    <nav class="bg-gray-900 bg-gradient-to-r from-slate-900 to-blue-900 border-b border-white/10 fixed w-full z-50 top-0 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <!-- Logo & Judul -->
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-white/10 backdrop-blur-md rounded-xl flex items-center justify-center text-white border border-white/10 shadow-inner">
                        <i class="ph-bold ph-student text-2xl"></i>
                    </div>
                    <div class="leading-tight">
                        <h1 class="font-extrabold text-white text-lg tracking-tight">Portal Ujian</h1>
                        <p class="text-[10px] font-bold text-blue-200 uppercase tracking-widest">CBT System</p>
                    </div>
                </div>

                <!-- Menu Kanan (User Info & Logout) -->
                <div class="flex items-center gap-4">
                    <div class="hidden md:block text-right mr-2">
                        @if(Auth::guard('student')->check())
                            <p class="text-sm font-bold text-white">{{ Auth::guard('student')->user()->name }}</p>
                            <p class="text-xs text-blue-300 font-mono">{{ Auth::guard('student')->user()->student_id }}</p>
                        @endif
                    </div>

                    <!-- Tombol Logout -->
                    <form method="POST" action="{{ route('student.logout') }}">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-sm font-bold transition flex items-center gap-2 shadow-lg shadow-rose-900/20">
                            <i class="ph-bold ph-sign-out text-lg"></i>
                            <span class="hidden sm:inline">Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- CONTENT WRAPPER -->
    <div class="pt-28 pb-12 min-h-screen relative overflow-hidden">
        {{-- Background Decoration --}}
        <div class="absolute inset-0 z-0 opacity-30 pointer-events-none">
            <div class="absolute top-0 left-0 w-full h-[500px] bg-gradient-to-b from-blue-100/50 to-transparent"></div>
        </div>

        @if(isset($header))
            <header class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 relative z-10">
                <div class="bg-white rounded-[2rem] p-6 shadow-xl shadow-slate-200/50 border border-slate-100">
                    {{ $header }}
                </div>
            </header>
        @endif

        <main class="relative z-10">
            {{ $slot }}
        </main>
    </div>

</body>
</html>