<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portal Siswa</title>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-6">

    <div class="max-w-4xl w-full">
        <!-- Header -->
        <div class="text-center mb-10">
            <h1 class="text-3xl font-extrabold text-slate-800 mb-2">Selamat Datang, {{ Auth::guard('student')->user()->name }} 👋</h1>
            <p class="text-slate-500">Silakan pilih aktivitas yang ingin Anda lakukan hari ini.</p>
        </div>

        <!-- Pilihan Jalur -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <!-- KARTU 1: MODE BELAJAR -->
            <a href="{{ route('student.learning.index') }}" class="group relative bg-white rounded-3xl p-8 shadow-xl shadow-slate-200 border border-slate-100 hover:-translate-y-2 hover:shadow-2xl transition-all duration-300 overflow-hidden">
                <div class="absolute top-0 right-0 w-40 h-40 bg-emerald-100 rounded-full blur-3xl -mr-10 -mt-10 opacity-50 group-hover:scale-150 transition-transform"></div>
                
                <div class="relative z-10 flex flex-col items-center text-center">
                    <div class="w-24 h-24 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-emerald-500 group-hover:text-white transition-colors duration-300 shadow-sm">
                        <i class="ph-duotone ph-books text-5xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-slate-800 mb-2">Ruang Belajar</h2>
                    <p class="text-slate-500 text-sm mb-6">Akses materi pelajaran, video pembelajaran, dan tugas harian Anda di sini.</p>
                    <span class="inline-flex items-center gap-2 text-emerald-600 font-bold group-hover:gap-4 transition-all">
                        Masuk Belajar <i class="ph-bold ph-arrow-right"></i>
                    </span>
                </div>
            </a>

            <!-- KARTU 2: MODE UJIAN -->
            <a href="{{ route('student.exam.index') }}" class="group relative bg-slate-900 rounded-3xl p-8 shadow-xl shadow-slate-900/20 border border-slate-800 hover:-translate-y-2 hover:shadow-2xl hover:shadow-rose-900/20 transition-all duration-300 overflow-hidden">
                <div class="absolute top-0 right-0 w-40 h-40 bg-rose-500 rounded-full blur-3xl -mr-10 -mt-10 opacity-20 group-hover:scale-150 transition-transform"></div>
                
                <div class="relative z-10 flex flex-col items-center text-center">
                    <div class="w-24 h-24 bg-white/10 text-rose-400 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-rose-600 group-hover:text-white transition-colors duration-300 border border-white/5">
                        <i class="ph-duotone ph-exam text-5xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-2">Ujian Online</h2>
                    <p class="text-slate-400 text-sm mb-6">Masuk ke ruang ujian CBT, kerjakan soal ujian, dan lihat hasil nilai Anda.</p>
                    <span class="inline-flex items-center gap-2 text-rose-400 font-bold group-hover:gap-4 transition-all">
                        Masuk Ujian <i class="ph-bold ph-arrow-right"></i>
                    </span>
                </div>
            </a>

        </div>

        <!-- Footer -->
        <div class="mt-12 text-center">
            <form method="POST" action="{{ route('student.logout') }}">
                @csrf
                <button type="submit" class="text-slate-400 hover:text-red-500 text-sm font-bold flex items-center gap-2 mx-auto transition-colors">
                    <i class="ph-bold ph-sign-out"></i> Keluar Aplikasi
                </button>
            </form>
        </div>
    </div>

</body>
</html>