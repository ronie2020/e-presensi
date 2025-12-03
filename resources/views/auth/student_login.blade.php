<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login CBT Siswa - SMPN 3 Lakbok</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body class="bg-slate-50 font-sans text-slate-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-slate-50">
        <!-- Logo -->
        <div class="mb-6 text-center">
            <img src="{{ asset('images/logo.png') }}" class="h-20 w-auto mx-auto mb-4" alt="Logo" onerror="this.style.display='none'">
            <h2 class="text-2xl font-bold text-slate-800">CBT System</h2>
            <p class="text-slate-500">Login Peserta Ujian</p>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-xl border border-slate-100 overflow-hidden sm:rounded-2xl">
            <!-- Form Login -->
            <form method="POST" action="{{ route('student.login.post') }}">
                @csrf

                <!-- Input NISN -->
                <div>
                    <label class="block font-medium text-sm text-slate-700 mb-1">NISN / ID Siswa</label>
                    <div class="relative">
                        <i class="ph-bold ph-identification-card absolute left-3 top-3 text-slate-400 text-lg"></i>
                        <input type="text" name="student_id" required autofocus 
                            class="pl-10 w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                            placeholder="Masukkan NISN Anda">
                    </div>
                    @error('student_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between mt-6">
                    <a href="/" class="text-sm text-slate-500 hover:text-slate-800">
                        &larr; Kembali ke Beranda
                    </a>
                    
                    <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-blue-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg shadow-blue-500/30">
                        Masuk Ujian <i class="ph-bold ph-sign-in ml-2"></i>
                    </button>
                </div>
            </form>
        </div>
        
        <p class="mt-8 text-center text-xs text-slate-400">
            &copy; {{ date('Y') }} SMP Negeri 3 Lakbok. <br>System CBT Terintegrasi.
        </p>
    </div>
</body>
</html>