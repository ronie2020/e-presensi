<div class="rounded-[2.5rem] shadow-2xl overflow-hidden mb-10 border border-white/10 relative min-h-[500px] md:min-h-[600px] flex items-center justify-center text-center group transition-all duration-700"
     :class="{
        'bg-slate-900 shadow-blue-900/20': mode === 'portal',
        'bg-blue-950 shadow-blue-900/30': mode === 'lms',
        'bg-rose-950 shadow-rose-900/30': mode === 'cbt'
     }">
    
    <!-- Background Decoration -->
    <div class="absolute inset-0 z-0 transition-opacity duration-700">
        <!-- Background Image (Gunakan asset lokal jika memungkinkan) -->
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
        
        <!-- Animated Blobs -->
        <div class="absolute top-1/4 left-1/4 w-64 h-64 md:w-96 md:h-96 rounded-full mix-blend-screen filter blur-[80px] md:blur-[100px] opacity-30 animate-blob transition-colors duration-700"
             :class="mode === 'cbt' ? 'bg-rose-600' : 'bg-cyan-500'"></div>
        <div class="absolute bottom-1/4 right-1/4 w-64 h-64 md:w-96 md:h-96 rounded-full mix-blend-screen filter blur-[80px] md:blur-[100px] opacity-30 animate-blob animation-delay-2000 transition-colors duration-700"
             :class="mode === 'cbt' ? 'bg-orange-600' : 'bg-blue-600'"></div>
    </div>

    <!-- Konten Utama -->
    <div class="relative z-10 w-full max-w-3xl px-6 py-12 flex flex-col items-center">
        
        <!-- Logo Sekolah / Icon -->
        <div class="mb-8 w-20 h-20 md:w-24 md:h-24 rounded-3xl flex items-center justify-center text-white shadow-2xl border-2 border-white/20 backdrop-blur-md transition-all duration-500"
             :class="{
                'bg-gradient-to-br from-slate-700 to-slate-900 shadow-slate-500/20': mode === 'portal',
                'bg-gradient-to-br from-blue-500 to-indigo-700 shadow-blue-500/30': mode === 'lms',
                'bg-gradient-to-br from-rose-500 to-red-700 shadow-rose-500/30': mode === 'cbt'
             }" data-aos="fade-down">
            
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
            <?php echo $__env->make('students.portal.partials.home-switcher', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <!-- FORM CONTAINER -->
            <div class="relative bg-white rounded-[1.5rem] md:rounded-[2rem] p-4 transition-colors duration-300 ring-1 ring-slate-100 shadow-inner">
                
                <?php if(Auth::guard('student')->check()): ?>
                    
                    <?php echo $__env->make('students.portal.partials.home-auth-menu', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php else: ?>
                    
                    <?php echo $__env->make('students.portal.partials.home-guest-forms', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
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
</div><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/students/portal/partials/home-hero.blade.php ENDPATH**/ ?>