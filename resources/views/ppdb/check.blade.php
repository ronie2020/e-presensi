<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cek Status PPDB - SMP Negeri 3 Lakbok</title>
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-900 text-slate-800 antialiased min-h-screen flex flex-col items-center justify-center relative overflow-hidden">

    <!-- Background Decoration -->
    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 pointer-events-none"></div>
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-blue-600 rounded-full mix-blend-multiply filter blur-[100px] opacity-20 animate-pulse"></div>
    <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-purple-600 rounded-full mix-blend-multiply filter blur-[100px] opacity-20 animate-pulse"></div>

    <div class="w-full max-w-md px-4 relative z-10">
        <div class="text-center mb-8">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-slate-400 hover:text-white transition mb-6 text-sm font-bold">
                <i class="ph-bold ph-arrow-left"></i> Kembali ke Beranda
            </a>
            <h1 class="text-3xl font-extrabold text-white mb-2">Pantau Status Seleksi</h1>
            <p class="text-slate-400 text-sm">Cek status pendaftaran & pengumuman kelulusan PPDB secara real-time.</p>
        </div>

        <div class="bg-white rounded-3xl p-8 shadow-2xl shadow-blue-900/50">
            @if (session('error'))
                <div class="mb-6 bg-red-50 text-red-600 px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-2 border border-red-100">
                    <i class="ph-fill ph-warning-circle text-lg"></i>
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('ppdb.search') }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nomor Pendaftaran / NISN</label>
                    <div class="relative">
                        <input type="text" name="search" class="w-full pl-11 pr-4 py-3.5 rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 font-bold text-slate-800 placeholder:font-normal placeholder:text-slate-400" placeholder="Contoh: REG-2025-0001" required>
                        <i class="ph-bold ph-magnifying-glass absolute left-4 top-3.5 text-xl text-slate-400"></i>
                    </div>
                </div>
                <button type="submit" class="w-full py-3.5 rounded-xl bg-blue-600 text-white font-bold text-sm shadow-lg shadow-blue-600/30 hover:bg-blue-700 hover:-translate-y-1 transition-all flex items-center justify-center gap-2">
                    Cek Status Sekarang <i class="ph-bold ph-arrow-right"></i>
                </button>
            </form>
        </div>

        <p class="text-center text-slate-500 text-xs mt-8">
            &copy; {{ date('Y') }} SMP Negeri 3 Lakbok.
        </p>
    </div>
</body>
</html>