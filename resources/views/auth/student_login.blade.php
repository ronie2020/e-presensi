<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login CBT Siswa - SMPN 3 Lakbok</title>
    
    <!-- Menggunakan Tailwind CSS dari CDN untuk memastikan style berjalan tanpa build process yang rumit di tahap ini -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    animation: {
                        'fade-in-up': 'fadeInUp 0.5s ease-out',
                        'fade-in': 'fadeIn 0.7s ease-out',
                    },
                    keyframes: {
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                    }
                }
            }
        }
    </script>
    <!-- Google Font: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        /* Custom Style untuk Background Pattern */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc; /* slate-50 */
            background-image: 
                radial-gradient(at 40% 20%, rgba(59, 130, 246, 0.10) 0px, transparent 50%),
                radial-gradient(at 80% 0%, rgba(236, 72, 153, 0.05) 0px, transparent 50%),
                radial-gradient(at 0% 50%, rgba(99, 102, 241, 0.05) 0px, transparent 50%);
            background-attachment: fixed;
        }
        .pattern-grid {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2364748b' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="h-full antialiased pattern-grid">
    <div class="min-h-[100dvh] flex flex-col justify-center py-12 sm:px-6 lg:px-8 relative overflow-hidden">
        
        <!-- Ilustrasi Dekoratif (Opsional - Bisa dihapus jika terlalu ramai) -->
        <div class="absolute top-10 left-10 opacity-20 hidden lg:block animate-fade-in text-blue-500">
             <i class="ph-duotone ph-exam text-9xl"></i>
        </div>
        <div class="absolute bottom-10 right-10 opacity-20 hidden lg:block animate-fade-in text-indigo-500">
             <i class="ph-duotone ph-student text-9xl"></i>
        </div>

        <!-- Header Logo & Judul -->
        <div class="sm:mx-auto sm:w-full sm:max-w-md text-center mb-8 animate-fade-in-up">
            <div class="inline-flex p-3 bg-white rounded-2xl shadow-sm border border-slate-100 mb-4">
                <img src="{{ asset('images/logo.png') }}" class="h-16 w-auto" alt="Logo Sekolah" onerror="this.src='https://via.placeholder.com/150?text=LOGO'; this.onerror=null;">
            </div>
            <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">
                CBT System
            </h2>
            <p class="mt-2 text-sm font-medium text-slate-500">
                SMP Negeri 3 Lakbok
            </p>
        </div>

        <!-- Kartu Login -->
        <div class="sm:mx-auto sm:w-full sm:max-w-[480px] animate-fade-in-up delay-100">
            <div class="bg-white py-10 px-6 shadow-[0_20px_50px_rgba(8,_112,_184,_0.07)] sm:rounded-[2rem] sm:px-12 border border-slate-100 relative overflow-hidden group">
                
                <!-- Dekorasi Header Kartu -->
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-500 via-indigo-500 to-blue-500"></div>
                <div class="absolute -top-24 -right-24 w-48 h-48 bg-blue-50 rounded-full opacity-50 blur-3xl pointer-events-none group-hover:bg-blue-100 transition-colors duration-500"></div>
                
                <div class="relative z-10">
                    <div class="mb-8 text-center">
                         <h3 class="text-xl font-bold text-slate-800">Selamat Datang, Siswa!</h3>
                         <p class="text-sm text-slate-500 mt-1">Silakan masukkan NISN untuk memulai ujian.</p>
                    </div>

                    <!-- Form Login -->
                    <form class="space-y-6" action="{{ route('student.login.post') }}" method="POST">
                        @csrf
                        <div>
                            <label for="student_id" class="block text-sm font-bold text-slate-700 mb-2">
                                NISN / ID Siswa
                            </label>
                            <div class="relative mt-1 rounded-xl shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                    <i class="ph-bold ph-identification-badge text-slate-400 text-xl"></i>
                                </div>
                                <input id="student_id" name="student_id" type="text" autocomplete="username" required autofocus 
                                    class="block w-full rounded-xl border-0 py-3.5 pl-12 pr-4 text-slate-900 font-medium ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-shadow bg-slate-50/50 focus:bg-white"
                                    placeholder="Contoh: 1234567890">
                            </div>
                            @error('student_id')
                                <p class="mt-2 text-sm text-rose-600 flex items-center font-medium animate-fade-in">
                                    <i class="ph-bold ph-warning-circle mr-1.5"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <button type="submit" class="flex w-full justify-center items-center rounded-xl bg-blue-600 px-4 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-200 hover:bg-blue-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0 group">
                                <span class="mr-2">Masuk ke Ruang Ujian</span>
                                <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-8 text-center animate-fade-in delay-200">
                <p class="text-xs font-medium text-slate-400">
                    &copy; {{ date('Y') }} SMP Negeri 3 Lakbok. <br class="sm:hidden">All rights reserved.
                </p>
                <a href="/" class="inline-flex items-center mt-4 text-sm font-semibold text-blue-600 hover:text-blue-800 transition-colors">
                    <i class="ph-bold ph-house-line mr-1.5"></i> Kembali ke Beranda Utama
                </a>
            </div>
        </div>
    </div>
</body>
</html>