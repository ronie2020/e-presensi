<?php $__env->startSection('content'); ?>
<style>
    [x-cloak] { display: none !important; }
    .glass-effect {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
    }
    @keyframes blob {
        0% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
        100% { transform: translate(0px, 0px) scale(1); }
    }
    .animate-blob { animation: blob 7s infinite; }
    .animation-delay-2000 { animation-delay: 2s; }
    .animation-delay-4000 { animation-delay: 4s; }
</style>


<div class="w-full max-w-6xl mx-auto min-h-[85vh] flex flex-col justify-center px-4" 
     x-data="{ mode: 'portal', isLoading: false }">

    <!-- 1. HERO SECTION (DYNAMIC THEME) -->
    <div class="rounded-[2.5rem] shadow-2xl overflow-hidden mb-10 border border-white/10 relative min-h-[500px] md:min-h-[600px] flex items-center justify-center text-center group transition-all duration-700"
         :class="{
            'bg-slate-900 shadow-blue-900/20': mode === 'portal',
            'bg-blue-950 shadow-blue-900/30': mode === 'lms',
            'bg-rose-950 shadow-rose-900/30': mode === 'cbt'
         }">
        
        <!-- Background Decoration -->
        <div class="absolute inset-0 z-0 transition-opacity duration-700">
            <!-- Background Image -->
            <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80')] bg-cover bg-center opacity-10 mix-blend-overlay"></div>
            
            <!-- Gradient Overlay per Mode -->
            <div class="absolute inset-0 bg-gradient-to-br transition-colors duration-700"
                 :class="{
                    'from-slate-900 via-slate-800 to-slate-900': mode === 'portal',
                    'from-blue-900 via-indigo-900 to-blue-950': mode === 'lms',
                    'from-rose-900 via-red-950 to-rose-950': mode === 'cbt'
                 }"></div>
            
            <!-- Pattern Overlay -->
            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 30px 30px;"></div>
            
            <!-- Animated Blobs (Colors change with mode) -->
            <div class="absolute top-1/4 left-1/4 w-64 h-64 md:w-96 md:h-96 rounded-full mix-blend-screen filter blur-[80px] md:blur-[100px] opacity-30 animate-blob transition-colors duration-700"
                 :class="mode === 'cbt' ? 'bg-rose-600' : 'bg-blue-500'"></div>
            <div class="absolute bottom-1/4 right-1/4 w-64 h-64 md:w-96 md:h-96 rounded-full mix-blend-screen filter blur-[80px] md:blur-[100px] opacity-30 animate-blob animation-delay-2000 transition-colors duration-700"
                 :class="mode === 'cbt' ? 'bg-orange-600' : 'bg-indigo-500'"></div>
        </div>

        <!-- Konten Utama -->
        <div class="relative z-10 w-full max-w-3xl px-6 py-12 flex flex-col items-center">
            
            <!-- Logo Sekolah -->
            <div class="mb-8 w-20 h-20 md:w-24 md:h-24 rounded-3xl flex items-center justify-center text-white shadow-2xl border-2 border-white/20 backdrop-blur-md transition-all duration-500"
                 :class="{
                    'bg-gradient-to-br from-slate-700 to-slate-900 shadow-slate-500/20': mode === 'portal',
                    'bg-gradient-to-br from-blue-500 to-indigo-700 shadow-blue-500/30': mode === 'lms',
                    'bg-gradient-to-br from-rose-500 to-red-700 shadow-rose-500/30': mode === 'cbt'
                 }" data-aos="fade-down">
                
                 <!-- Icon Ganti-ganti sesuai mode -->
                 <i class="ph-fill text-4xl md:text-5xl transition-all duration-300 transform"
                    :class="{
                        'ph-buildings': mode === 'portal',
                        'ph-books': mode === 'lms',
                        'ph-desktop': mode === 'cbt'
                    }"></i>
            </div>

            <!-- Judul & Deskripsi -->
            <div class="mb-10 transition-all duration-500" data-aos="fade-down" data-aos-delay="100">
                <!-- Label Badge -->
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full border backdrop-blur-md text-[10px] md:text-xs font-bold uppercase tracking-widest mb-6 shadow-lg transition-all duration-300"
                     :class="{
                        'bg-slate-500/20 border-slate-400/30 text-slate-200 ring-1 ring-slate-500/30': mode === 'portal',
                        'bg-blue-500/20 border-blue-400/30 text-blue-200 ring-1 ring-blue-500/30': mode === 'lms',
                        'bg-rose-500/20 border-rose-400/30 text-rose-200 ring-1 ring-rose-500/30': mode === 'cbt'
                     }">
                    <span x-text="mode === 'portal' ? 'Portal Publik' : (mode === 'lms' ? 'Area Siswa' : 'Area Ujian')"></span>
                </div>

                <!-- Main Title -->
                <h1 class="text-3xl sm:text-4xl md:text-6xl font-black text-white tracking-tight leading-tight mb-4 drop-shadow-xl min-h-[4rem] md:min-h-[5rem]">
                    <span x-show="mode === 'portal'" x-transition:enter.duration.500ms>
                        Pusat Informasi <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-slate-200 to-gray-400">Data Akademik</span>
                    </span>
                    <span x-show="mode === 'lms'" x-cloak x-transition:enter.duration.500ms>
                        Ruang Belajar <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-300 to-cyan-300">Digital Siswa</span>
                    </span>
                    <span x-show="mode === 'cbt'" x-cloak x-transition:enter.duration.500ms>
                        Sistem Ujian <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-300 to-orange-300">Berbasis Komputer</span>
                    </span>
                </h1>
                
                <!-- Subtitle -->
                <p class="text-sm md:text-lg text-slate-300 font-medium leading-relaxed max-w-xl mx-auto min-h-[3rem] md:min-h-[3.5rem] transition-all duration-300 px-2">
                    <span x-show="mode === 'portal'" x-transition.opacity>
                        Cek data kehadiran, pelanggaran, nilai, dan informasi siswa lainnya secara publik.
                    </span>
                    <span x-show="mode === 'lms'" x-cloak x-transition.opacity>
                        Login untuk mengakses materi pelajaran, mengumpulkan tugas, dan berdiskusi dengan guru.
                    </span>
                    <span x-show="mode === 'cbt'" x-cloak x-transition.opacity>
                        Login khusus untuk mengikuti Penilaian Tengah Semester (PTS), PAS, dan Ujian Sekolah.
                    </span>
                </p>
            </div>

            <!-- TAB SWITCHER & FORM CARD -->
            <div class="glass-effect border border-white/60 p-3 rounded-[2rem] md:rounded-[2.5rem] shadow-2xl relative w-full max-w-2xl mx-auto transition-all duration-500 transform hover:scale-[1.01]" 
                 :class="{
                    'shadow-slate-900/50': mode === 'portal',
                    'shadow-blue-900/50': mode === 'lms',
                    'shadow-rose-900/50': mode === 'cbt'
                 }"
                 data-aos="fade-up" data-aos-delay="200">
                
                <!-- 3 Tab Buttons -->
                <div class="grid grid-cols-3 gap-2 mb-3 p-1.5 bg-slate-100/80 rounded-[1.5rem] md:rounded-[2rem] border border-slate-200">
                    <button @click="mode = 'portal'" class="py-2 md:py-3 rounded-[1.2rem] md:rounded-[1.5rem] text-[10px] md:text-sm font-bold transition-all duration-300 flex flex-col sm:flex-row items-center justify-center gap-1 md:gap-2" :class="mode === 'portal' ? 'bg-white text-slate-800 shadow-md scale-100 ring-1 ring-black/5' : 'text-slate-500 hover:text-slate-700 hover:bg-white/50'">
                        <i class="ph-bold ph-magnifying-glass text-lg md:text-xl"></i> <span>Cek Data</span>
                    </button>
                    <button @click="mode = 'lms'" class="py-2 md:py-3 rounded-[1.2rem] md:rounded-[1.5rem] text-[10px] md:text-sm font-bold transition-all duration-300 flex flex-col sm:flex-row items-center justify-center gap-1 md:gap-2" :class="mode === 'lms' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/30 scale-100' : 'text-slate-500 hover:text-blue-600 hover:bg-white/50'">
                        <i class="ph-bold ph-books text-lg md:text-xl"></i> <span>Masuk Kelas</span>
                    </button>
                    <button @click="mode = 'cbt'" class="py-2 md:py-3 rounded-[1.2rem] md:rounded-[1.5rem] text-[10px] md:text-sm font-bold transition-all duration-300 flex flex-col sm:flex-row items-center justify-center gap-1 md:gap-2" :class="mode === 'cbt' ? 'bg-rose-600 text-white shadow-md shadow-rose-500/30 scale-100' : 'text-slate-500 hover:text-rose-600 hover:bg-white/50'">
                        <i class="ph-bold ph-desktop text-lg md:text-xl"></i> <span>Masuk Ujian</span>
                    </button>
                </div>

                <!-- FORM CONTAINER -->
                <div class="relative bg-white rounded-[1.5rem] md:rounded-[2rem] p-4 transition-colors duration-300 ring-1 ring-slate-100 shadow-inner">
                    
                    <?php if(Auth::guard('student')->check()): ?>
                        
                        
                        
                        <div class="text-center py-6">
                            <div class="inline-block p-2 px-4 rounded-full bg-emerald-100 text-emerald-700 font-bold text-xs uppercase mb-4 animate-pulse">
                                <i class="ph-fill ph-check-circle"></i> Anda Sedang Login
                            </div>
                            <h3 class="text-xl font-bold text-slate-800 mb-1">Halo, <?php echo e(Auth::guard('student')->user()->name); ?></h3>
                            <p class="text-slate-400 text-sm mb-6">Silakan pilih layanan untuk melanjutkan:</p>

                            <!-- Tombol Dinamis Sesuai Mode -->
                            <div x-show="mode === 'portal'" class="space-y-3">
                                
                                <a href="<?php echo e(route('portal.show', Auth::guard('student')->id())); ?>" class="w-full block py-4 bg-slate-800 hover:bg-slate-900 text-white rounded-xl font-bold shadow-lg transition-all flex items-center justify-center gap-2">
                                    <i class="ph-bold ph-user-circle text-xl"></i> Buka Profil Saya
                                </a>

                                
                                <a href="<?php echo e(route('student.schedule.index')); ?>" class="w-full block py-4 bg-white border-2 border-slate-100 hover:border-blue-200 text-slate-700 hover:text-blue-600 rounded-xl font-bold transition-all flex items-center justify-center gap-2">
                                    <i class="ph-bold ph-calendar-blank text-xl"></i> Lihat Jadwal Pelajaran
                                </a>

                                
                                <a href="<?php echo e(route('student.liaison.index')); ?>" class="w-full block py-4 bg-white border-2 border-indigo-100 hover:border-indigo-300 text-indigo-700 hover:text-indigo-800 rounded-xl font-bold transition-all flex items-center justify-center gap-2 shadow-sm hover:shadow-md">
                                    <i class="ph-bold ph-chat-circle-text text-xl"></i> Buku Penghubung & Chat
                                </a>

                                
                                <a href="<?php echo e(route('student.complaints.index')); ?>" class="w-full block py-4 bg-white border-2 border-rose-100 hover:border-rose-300 text-rose-700 hover:text-rose-800 rounded-xl font-bold transition-all flex items-center justify-center gap-2 shadow-sm hover:shadow-md">
                                    <i class="ph-bold ph-warning-circle text-xl"></i> Layanan Pengaduan
                                </a>

                                
                                <a href="<?php echo e(route('student.habits.dashboard')); ?>" class="w-full block py-4 bg-white border-2 border-emerald-100 hover:border-emerald-300 text-emerald-700 hover:text-emerald-800 rounded-xl font-bold transition-all flex items-center justify-center gap-2 shadow-sm hover:shadow-md">
                                    <i class="ph-bold ph-check-square-offset text-xl"></i> Jurnal Kebiasaan
                                </a>
                            </div>

                            <div x-show="mode === 'lms'" x-cloak>
                                <a href="<?php echo e(route('students.learning.index')); ?>" class="w-full block py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-lg shadow-blue-600/20 transition-all flex items-center justify-center gap-2">
                                    <i class="ph-bold ph-books text-xl"></i> Masuk Ruang Belajar
                                </a>
                            </div>

                            <div x-show="mode === 'cbt'" x-cloak>
                                <a href="<?php echo e(route('student.exam.index')); ?>" class="w-full block py-4 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold shadow-lg shadow-rose-600/20 transition-all flex items-center justify-center gap-2">
                                    <i class="ph-bold ph-desktop text-xl"></i> Masuk Ruang Ujian
                                </a>
                            </div>

                            <div class="mt-4 pt-4 border-t border-slate-100">
                                <form method="POST" action="<?php echo e(route('student.logout')); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="text-xs font-bold text-rose-500 hover:text-rose-700 hover:underline">
                                        Bukan Anda? Keluar Akun
                                    </button>
                                </form>
                            </div>
                        </div>

                    <?php else: ?>
                        
                        
                        
                        
                        <!-- 1. FORM PORTAL (Search) -->
                        <form x-show="mode === 'portal'" @submit="isLoading = true" action="<?php echo e(route('portal.search')); ?>" method="POST" class="relative">
                            <?php echo csrf_field(); ?>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 md:pl-6 flex items-center pointer-events-none">
                                    <i class="ph-bold ph-identification-card text-xl md:text-2xl text-slate-400 group-focus-within:text-slate-700 transition-colors"></i>
                                </div>
                                
                                <input type="text" name="student_id" class="block w-full pl-12 md:pl-16 pr-14 md:pr-32 py-4 md:py-5 bg-slate-50 text-slate-800 text-base md:text-lg font-bold rounded-2xl focus:ring-4 focus:ring-slate-200 focus:bg-white transition-all placeholder:text-slate-400 border-none outline-none" placeholder="Masukkan NISN Siswa" required autocomplete="off">
                                
                                <button type="submit" :disabled="isLoading" class="absolute right-2 top-2 bottom-2 bg-slate-800 hover:bg-slate-900 disabled:bg-slate-400 text-white w-11 md:w-auto px-0 md:px-8 rounded-xl font-bold transition-all shadow-lg shadow-slate-800/20 flex items-center justify-center gap-2 group/btn">
                                    <span x-show="!isLoading" class="hidden md:inline">Cari</span>
                                    <i x-show="!isLoading" class="ph-bold ph-arrow-right text-lg md:text-base group-hover/btn:translate-x-1 transition-transform"></i>
                                    <svg x-show="isLoading" x-cloak class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                </button>
                            </div>
                            <p class="text-xs text-slate-400 mt-3 px-4 text-center font-medium">Fitur ini untuk Orang Tua mengecek data siswa tanpa perlu login.</p>
                        </form>

                        <!-- 2. FORM LOGIN LMS (Biru) -->
                        <form x-show="mode === 'lms'" action="<?php echo e(route('student.login.post')); ?>" method="POST" class="relative" x-cloak>
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="intended_app" value="lms">
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 md:pl-6 flex items-center pointer-events-none">
                                    <i class="ph-bold ph-student text-xl md:text-2xl text-blue-400 group-focus-within:text-blue-600 transition-colors"></i>
                                </div>
                                
                                <input type="text" name="student_id" class="block w-full pl-12 md:pl-16 pr-14 md:pr-40 py-4 md:py-5 bg-blue-50/50 text-slate-800 text-base md:text-lg font-bold rounded-2xl focus:ring-4 focus:ring-blue-100 focus:bg-white transition-all placeholder:text-blue-300 border-none outline-none" placeholder="NISN Siswa" required autocomplete="off">
                                
                                <button type="submit" class="absolute right-2 top-2 bottom-2 bg-blue-600 hover:bg-blue-700 text-white w-11 md:w-auto px-0 md:px-6 rounded-xl font-bold transition-all shadow-lg shadow-blue-600/20 flex items-center justify-center gap-2 group/btn">
                                    <span class="hidden md:inline">Masuk Kelas</span>
                                    <i class="ph-bold ph-sign-in text-lg md:text-base group-hover/btn:translate-x-1 transition-transform"></i>
                                </button>
                            </div>
                            <p class="text-xs text-blue-400/80 mt-3 px-4 text-center font-medium">Masuk untuk mengakses materi pelajaran dan tugas.</p>
                        </form>

                        <!-- 3. FORM LOGIN CBT (Merah) -->
                        <form x-show="mode === 'cbt'" action="<?php echo e(route('student.login.post')); ?>" method="POST" class="relative" x-cloak>
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="intended_app" value="cbt">
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 md:pl-6 flex items-center pointer-events-none">
                                    <i class="ph-bold ph-lock-key text-xl md:text-2xl text-rose-400 group-focus-within:text-rose-600 transition-colors"></i>
                                </div>
                                
                                <input type="text" name="student_id" class="block w-full pl-12 md:pl-16 pr-14 md:pr-40 py-4 md:py-5 bg-rose-50/50 text-slate-800 text-base md:text-lg font-bold rounded-2xl focus:ring-4 focus:ring-rose-100 focus:bg-white transition-all placeholder:text-rose-300 border-none outline-none" placeholder="NISN Siswa" required autocomplete="off">
                                
                                <button type="submit" class="absolute right-2 top-2 bottom-2 bg-rose-600 hover:bg-rose-700 text-white w-11 md:w-auto px-0 md:px-6 rounded-xl font-bold transition-all shadow-lg shadow-rose-600/20 flex items-center justify-center gap-2 group/btn">
                                    <span class="hidden md:inline">Mulai Ujian</span>
                                    <i class="ph-bold ph-arrow-right text-lg md:text-base group-hover/btn:translate-x-1 transition-transform"></i>
                                </button>
                            </div>
                            <p class="text-xs text-rose-400/80 mt-3 px-4 text-center font-bold"><i class="ph-fill ph-warning-circle"></i> Pastikan Anda berada di ruangan ujian yang benar.</p>
                        </form>
                    <?php endif; ?>

                </div>
            </div>

            <!-- Error Message -->
            <?php if(session('error') || $errors->any()): ?>
                <div class="mt-6 p-4 bg-rose-500/90 backdrop-blur-md border border-rose-400 rounded-2xl text-white flex items-center justify-center gap-3 animate-pulse shadow-xl max-w-lg mx-auto" role="alert">
                    <div class="bg-white/20 rounded-full p-1.5"><i class="ph-bold ph-warning text-white"></i></div>
                    <span class="font-bold text-sm"><?php echo e(session('error') ?? $errors->first()); ?></span>
                </div>
            <?php endif; ?>

        </div>
        
        <!-- Copyright -->
        <div class="absolute bottom-0 w-full text-center pb-6 text-white/30 text-xs font-medium z-10 pointer-events-none">
            &copy; <?php echo e(date('Y')); ?> Sistem Informasi Sekolah Terpadu.
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ronie\Documents\aplikasi\E-Presensi Netila\resources\views/students/portal/index.blade.php ENDPATH**/ ?>