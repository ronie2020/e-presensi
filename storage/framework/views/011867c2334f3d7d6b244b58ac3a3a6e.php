<div class="rounded-[2.5rem] shadow-2xl overflow-hidden mb-10 border border-white/60 relative min-h-[500px] md:min-h-[600px] flex items-center justify-center text-center group transition-all duration-700 bg-elevate-gradient-main"
     :class="{
        'shadow-elevate-primary/20': mode === 'portal',
        'shadow-elevate-accent/20': mode === 'lms',
        'shadow-elevate-peach/20': mode === 'cbt'
     }">
    
    <!-- Abstract Shapes Ornaments (Elevate Style) -->
    <div class="absolute inset-0 z-0 transition-opacity duration-700 overflow-hidden pointer-events-none">
        <div class="absolute -top-20 -left-10 w-64 h-64 bg-white/40 rounded-[3rem] rotate-12 backdrop-blur-3xl shadow-sm transition-all duration-700"
             :class="mode === 'cbt' ? 'bg-elevate-peach/30' : 'bg-white/40'"></div>
             
        <div class="absolute top-1/4 right-10 w-32 h-32 bg-elevate-accent/20 rounded-3xl -rotate-12 backdrop-blur-xl transition-all duration-700"
             :class="mode === 'lms' ? 'bg-elevate-primary/20 scale-125' : 'bg-elevate-accent/20'"></div>
             
        <div class="absolute -bottom-32 left-1/4 w-80 h-80 bg-elevate-peach/20 rounded-[4rem] rotate-45 backdrop-blur-2xl transition-all duration-700"
             :class="mode === 'cbt' ? 'bg-rose-400/20 scale-110' : 'bg-elevate-peach/20'"></div>
             
        <!-- Pattern Overlay -->
        <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(#2c3f61 1px, transparent 1px); background-size: 40px 40px;"></div>
    </div>

    <!-- Konten Utama -->
    <div class="relative z-10 w-full max-w-3xl px-6 py-12 flex flex-col items-center">
        
        <!-- LOGO SEKOLAH (Menggunakan Gambar Elevate Star) -->
        <div class="mb-8 w-24 h-24 md:w-28 md:h-28 rounded-3xl flex items-center justify-center shadow-xl border-2 border-white backdrop-blur-md transition-all duration-500 overflow-hidden"
             :class="{
                'bg-white shadow-elevate-dark/10': mode === 'portal',
                'bg-elevate-soft shadow-elevate-primary/30': mode === 'lms',
                'bg-elevate-peach-light/30 shadow-elevate-peach/30': mode === 'cbt'
             }" data-aos="fade-down">
            
             <!-- Tag Image untuk memanggil gambar yang Anda upload -->
             <img src="<?php echo e(asset('images/logo.png')); ?>" 
                  alt="Logo Sekolah" 
                  class="w-full h-full object-contain p-3 transition-transform duration-500"
                  :class="{
                      'scale-100': mode === 'portal',
                      'scale-110': mode === 'lms' || mode === 'cbt'
                  }">
        </div>

        <!-- Judul & Deskripsi -->
        <div class="mb-10 transition-all duration-500" data-aos="fade-down" data-aos-delay="100">
            <!-- Label Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full border backdrop-blur-md text-[10px] md:text-xs font-bold uppercase tracking-widest mb-6 shadow-sm transition-all duration-300"
                 :class="{
                    'bg-white/60 border-white text-elevate-dark': mode === 'portal',
                    'bg-elevate-soft border-elevate-accent/30 text-elevate-primary': mode === 'lms',
                    'bg-elevate-peach-light/50 border-elevate-peach/30 text-elevate-peach-dark': mode === 'cbt'
                 }">
                <span x-text="mode === 'portal' ? 'Portal Publik' : (mode === 'lms' ? 'Area Siswa' : 'Area Ujian')"></span>
            </div>

            <!-- Main Title -->
            <h1 class="text-3xl sm:text-4xl md:text-6xl font-black text-elevate-dark tracking-tight leading-tight mb-4 min-h-[4rem] md:min-h-[5rem]">
                <span x-show="mode === 'portal'" x-transition:enter.duration.500ms>
                    Pusat Informasi <br> <span class="text-elevate-primary">Data Akademik</span>
                </span>
                <span x-show="mode === 'lms'" x-cloak x-transition:enter.duration.500ms>
                    Ruang Belajar <br> <span class="text-elevate-primary">Digital Siswa</span>
                </span>
                <span x-show="mode === 'cbt'" x-cloak x-transition:enter.duration.500ms>
                    Sistem Ujian <br> <span class="text-elevate-peach-dark">Berbasis Komputer</span>
                </span>
            </h1>
            
            <!-- Subtitle -->
            <p class="text-sm md:text-lg text-elevate-dark/70 font-medium leading-relaxed max-w-xl mx-auto min-h-[3rem] md:min-h-[3.5rem] transition-all duration-300 px-2">
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
        <div class="bg-white/40 backdrop-blur-xl border border-white/80 p-3 rounded-[2rem] md:rounded-[2.5rem] shadow-2xl relative w-full max-w-2xl mx-auto transition-all duration-500 transform hover:scale-[1.01]" 
             :class="{
                'shadow-elevate-dark/10': mode === 'portal',
                'shadow-elevate-primary/20': mode === 'lms',
                'shadow-elevate-peach/20': mode === 'cbt'
             }"
             data-aos="fade-up" data-aos-delay="200">
            
            <!-- 3 Tab Buttons -->
            <?php echo $__env->make('students.portal.partials.home-switcher', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <!-- FORM CONTAINER -->
            <div class="relative bg-white rounded-[1.5rem] md:rounded-[2rem] p-4 transition-colors duration-300 ring-1 ring-slate-100 shadow-sm">
                
                <?php if(Auth::guard('student')->check()): ?>
                    
                    <?php echo $__env->make('students.portal.partials.home-auth-menu', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php else: ?>
                    
                    <?php echo $__env->make('students.portal.partials.home-guest-forms', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endif; ?>

            </div>
        </div>

        <!-- Error Message -->
        <?php if(session('error') || $errors->any()): ?>
            <div class="mt-6 p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-600 flex items-center justify-center gap-3 animate-pulse shadow-md max-w-lg mx-auto" role="alert">
                <div class="bg-rose-100 rounded-full p-1.5"><i class="ph-bold ph-warning text-rose-600"></i></div>
                <span class="font-bold text-sm"><?php echo e(session('error') ?? $errors->first()); ?></span>
            </div>
        <?php endif; ?>

    </div>
    
    <!-- Copyright -->
    <div class="absolute bottom-0 w-full text-center pb-6 text-elevate-dark/40 text-xs font-medium z-10 pointer-events-none">
        &copy; <?php echo e(date('Y')); ?> Sistem Informasi Sekolah Terpadu.
    </div>
</div><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/students/portal/partials/home-hero.blade.php ENDPATH**/ ?>