<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Ujian (CBT)</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        body { background-color: #f1f5f9; font-family: sans-serif; }
        /* Background Pattern Sederhana & Ringan */
        .bg-pattern {
            background-color: #0f172a;
            background-image: radial-gradient(#1e293b 1px, transparent 1px);
            background-size: 20px 20px;
        }
    </style>
</head>
<body class="bg-pattern min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        
        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-2xl shadow-lg mb-4 p-2">
                <!-- Pastikan path logo sesuai -->
                <img src="{{ asset('images/logo.png') }}" class="w-full h-full object-contain" alt="Logo Sekolah">
            </div>
            <h1 class="text-2xl font-black text-white tracking-tight">UJIAN ONLINE (CBT)</h1>
            <p class="text-slate-400 text-sm mt-1">Silakan login untuk memulai sesi ujian.</p>
        </div>

        <!-- Kartu Login -->
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-white/10 relative">
            
            <!-- Indikator SEB -->
            <div class="bg-emerald-50 border-b border-emerald-100 px-6 py-3 flex items-center justify-center gap-2">
                <i class="ph-fill ph-shield-check text-emerald-600 text-lg"></i>
                <span class="text-xs font-bold text-emerald-800 uppercase tracking-wider">Safe Exam Browser Mode</span>
            </div>

            <div class="p-8 pt-6">
                <!-- Alert Error -->
                @if(session('error') || $errors->any())
                    <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-600 px-4 py-3 rounded-xl flex items-center gap-3 text-sm font-bold shadow-sm">
                        <i class="ph-fill ph-warning-circle text-xl"></i>
                        <span>{{ session('error') ?? $errors->first() }}</span>
                    </div>
                @endif

                <form action="{{ route('student.login.post') }}" method="POST">
                    @csrf
                    
                    <div class="space-y-5">
                        <!-- Input NISN -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Nomor Induk Siswa (NISN)</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="ph-bold ph-user text-xl text-slate-400 group-focus-within:text-blue-600 transition-colors"></i>
                                </div>
                                <input type="text" name="student_id" required autofocus autocomplete="off"
                                    class="block w-full pl-12 pr-4 py-4 bg-slate-50 text-slate-800 font-bold rounded-xl border-2 border-slate-100 focus:border-blue-500 focus:ring-0 transition-all placeholder:text-slate-300 outline-none text-lg" 
                                    placeholder="Contoh: 00123456">
                            </div>
                        </div>

                        <!-- Tombol Login -->
                        <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-600/30 transition-all transform active:scale-95 flex items-center justify-center gap-2">
                            <span>MASUK UJIAN</span>
                            <i class="ph-bold ph-sign-in text-lg"></i>
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Footer Kecil -->
            <div class="bg-slate-50 px-6 py-4 text-center border-t border-slate-100">
                <p class="text-[10px] text-slate-400 font-medium">
                    &copy; {{ date('Y') }} Sistem Ujian Sekolah. <br>
                    Pastikan koneksi internet stabil.
                </p>
            </div>
        </div>

        <!-- Tombol Bantuan (Opsional) -->
        <div class="text-center mt-8">
            <button onclick="window.location.reload()" class="text-slate-500 hover:text-white text-sm font-medium flex items-center justify-center gap-2 mx-auto transition-colors">
                <i class="ph-bold ph-arrows-clockwise"></i> Refresh Halaman
            </button>
        </div>

    </div>

</body>
</html>