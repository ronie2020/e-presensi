<div class="bg-elevate-dark rounded-[2.5rem] shadow-xl shadow-elevate-dark/10 overflow-hidden mb-8 border border-elevate-primary/30 relative group">
    
    <!-- TOMBOL SAKLAR DARK MODE -->
    <div class="absolute top-4 right-4 sm:top-6 sm:right-6 z-50">
        <button @click="toggleTheme()" 
                class="w-10 h-10 rounded-full bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-white hover:bg-white/20 hover:scale-110 transition-all shadow-lg focus:outline-none"
                :title="isDark ? 'Beralih ke Mode Terang' : 'Beralih ke Mode Gelap'">
            <i class="transition-all duration-300" 
               :class="isDark ? 'ph-fill ph-sun text-elevate-peach text-xl' : 'ph-fill ph-moon text-elevate-soft text-lg'"></i>
        </button>
    </div>

    <!-- Background Banner (Elevate Style) -->
    <div class="absolute inset-0 z-0 overflow-hidden">
        <!-- Abstract Shapes Overlay -->
        <div class="absolute -top-20 -right-10 w-[300px] sm:w-[400px] h-[300px] sm:h-[400px] bg-elevate-primary rounded-[4rem] rotate-12 opacity-40 mix-blend-screen pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-[200px] sm:w-[300px] h-[200px] sm:h-[300px] <?php echo e($isAlumni ? 'bg-elevate-peach-dark' : 'bg-elevate-accent'); ?> rounded-full filter blur-[80px] opacity-20 animate-blob"></div>
        
        <!-- Texture -->
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] mix-blend-overlay"></div>
    </div>
    
    <!-- Content Container -->
    <div class="relative z-10 px-6 sm:px-10 pt-14 sm:pt-16 pb-8 flex flex-col md:flex-row items-center md:items-end text-center md:text-left gap-4 sm:gap-6">
        
        <!-- Foto Profil -->
        <div class="relative group shrink-0 mx-auto md:mx-0 -mb-2">
            <div class="w-28 h-28 sm:w-36 sm:h-36 rounded-full bg-white p-1.5 shadow-2xl relative z-10 transform group-hover:scale-105 transition-transform duration-300 ring-4 ring-elevate-accent/20">
                <div class="w-full h-full rounded-full bg-elevate-soft flex items-center justify-center overflow-hidden border-2 border-white relative">
                    <?php if($student->photo_path): ?>
                        <img src="<?php echo e(asset('storage/' . $student->photo_path)); ?>" alt="<?php echo e($student->name); ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <div class="w-full h-full bg-elevate-gradient-card flex items-center justify-center text-4xl sm:text-5xl font-black text-elevate-primary select-none">
                            <?php echo e(substr(trim($student->name), 0, 1)); ?>

                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            
            <?php if(!$isAlumni): ?>
                <?php
                    $points = $total_merit_points ?? 0;
                    $level = floor($points / 50) + 1;
                    $nextLevelPoints = $level * 50;
                    $currentLevelProgress = $points % 50;
                    $progressPercent = ($currentLevelProgress / 50) * 100;
                ?>
                <div class="absolute -bottom-3 left-1/2 transform -translate-x-1/2 z-30 whitespace-nowrap">
                    <div class="bg-white text-elevate-dark text-[10px] font-black px-4 py-1.5 rounded-full shadow-lg border border-slate-100 flex items-center gap-1.5">
                        <i class="ph-fill ph-crown text-elevate-peach"></i> LVL <?php echo e($level); ?>

                    </div>
                </div>
            <?php else: ?>
                 <div class="absolute bottom-1 right-1 z-20 bg-elevate-peach text-white text-[10px] font-black px-3 py-1 rounded-full border-2 border-white shadow-sm flex items-center gap-1.5">
                    <i class="ph-fill ph-graduation-cap"></i> ALUMNI
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Detail Siswa -->
        <div class="flex-1 min-w-0 w-full md:pb-3">
            
            <!-- BARIS 1: Nama (Full Width) -->
            <h1 class="text-2xl sm:text-4xl font-black text-white tracking-tight leading-tight mb-2 break-words capitalize text-center md:text-left">
                <?php echo e(strtolower($student->name)); ?>

            </h1>
            
            <!-- BARIS 2: Kumpulan Badge Informasi (Akan selalu sejajar) -->
            <div class="flex flex-wrap justify-center md:justify-start gap-2 text-xs sm:text-sm font-medium mb-4">
                <?php if(!$isAlumni): ?>
                <span class="inline-flex items-center bg-elevate-primary/50 backdrop-blur-md px-3 sm:px-4 py-1.5 rounded-full text-white border border-elevate-accent/30 shadow-sm">
                    <i class="ph-fill ph-chalkboard-teacher mr-2 text-base sm:text-lg text-elevate-accent"></i>
                    <span>Kelas <strong class="font-bold text-white"><?php echo e($student->schoolClass->name ?? 'Unassigned'); ?></strong></span>
                </span>
                <?php endif; ?>
                
                <span x-data="{ copied: false }" 
                      @click="navigator.clipboard.writeText('<?php echo e($student->student_id); ?>'); copied = true; setTimeout(() => copied = false, 2000)" 
                      class="inline-flex items-center bg-white/10 backdrop-blur-md px-3 sm:px-4 py-1.5 rounded-full text-white border border-white/20 font-mono hover:bg-white/20 cursor-pointer select-none transition-all shadow-sm" 
                      title="Klik untuk salin">
                    <i class="ph-fill mr-2 text-base sm:text-lg text-elevate-soft" :class="copied ? 'ph-check text-green-400' : 'ph-identification-card'"></i>
                    <span x-text="copied ? 'Tersalin!' : '<?php echo e($student->student_id); ?>'"></span>
                </span>

                
                <?php if($student->is_validated): ?>
                    <span class="inline-flex items-center bg-emerald-500/20 backdrop-blur-md px-3 sm:px-4 py-1.5 rounded-full text-emerald-100 border border-emerald-400/30 font-medium shadow-sm" title="Data Telah Tervalidasi">
                        <i class="ph-fill ph-seal-check mr-1.5 text-base text-emerald-400"></i> Terverifikasi
                    </span>
                <?php else: ?>
                    <a href="<?php echo e(route('students.verify')); ?>" class="inline-flex items-center bg-rose-500/40 backdrop-blur-md px-3 sm:px-4 py-1.5 rounded-full text-white border border-rose-400/50 font-bold hover:bg-rose-500/60 transition-all shadow-sm animate-pulse cursor-pointer" title="Klik untuk memvalidasi data">
                        <i class="ph-fill ph-warning-circle mr-1.5 text-base text-rose-300"></i> Belum Validasi
                    </a>
                <?php endif; ?>
            </div>

            <!-- BARIS 3: XP Bar (Kiri) & Tombol Aksi (Kanan) -->
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4 mt-2 border-t border-white/10 pt-4">
                
                <!-- BAGIAN KIRI: XP Bar -->
                <div class="w-full lg:w-5/12 max-w-md mx-auto md:mx-0">
                    <?php if(!$isAlumni): ?>
                    <div class="bg-white/5 backdrop-blur-sm p-3 rounded-2xl border border-white/10">
                        <div class="flex items-center justify-between text-[10px] font-bold text-elevate-soft mb-1.5 px-1">
                            <span><?php echo e($points); ?> XP</span>
                            <span><?php echo e($nextLevelPoints); ?> XP (Next Lvl)</span>
                        </div>
                        <div class="h-2.5 w-full bg-elevate-dark/50 rounded-full overflow-hidden shadow-inner">
                            <div class="h-full bg-elevate-accent rounded-full transition-all duration-1000 ease-out shadow-[0_0_10px_rgba(86,187,241,0.5)]" style="width: <?php echo e($progressPercent); ?>%"></div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- BAGIAN KANAN: Action Buttons -->
                <div class="w-full lg:w-7/12 flex flex-wrap justify-center lg:justify-end gap-2 mt-4 lg:mt-0">
                    <?php if(!$isAlumni): ?>                    
                    <a href="<?php echo e(route('student.habits.dashboard')); ?>" class="flex-1 sm:flex-none justify-center inline-flex items-center px-4 py-2.5 bg-elevate-peach-dark hover:bg-elevate-peach text-white rounded-xl text-xs sm:text-sm font-bold transition-all shadow-lg active:scale-95 group border border-white/20">
                        <i class="ph-bold ph-star mr-1.5 group-hover:rotate-180 transition-transform duration-500"></i> Poin Karakter
                    </a>
                    <a href="<?php echo e(route('portal.card', $student->id)); ?>" target="_blank" class="flex-1 sm:flex-none justify-center inline-flex items-center px-4 py-2.5 bg-white text-elevate-dark rounded-xl text-xs sm:text-sm font-bold hover:bg-elevate-soft transition-all shadow-lg active:scale-95 group">
                        <i class="ph-bold ph-identification-card mr-1.5 text-elevate-primary group-hover:animate-bounce"></i> Kartu OSIS
                    </a>
                    <?php endif; ?>
                    
                    <a href="<?php echo e(route('portal.biodata', $student->id)); ?>" class="flex-1 sm:flex-none justify-center inline-flex items-center px-4 py-2.5 bg-elevate-primary/30 backdrop-blur-md border border-elevate-accent/30 rounded-xl text-xs sm:text-sm font-bold text-white hover:bg-elevate-primary transition-all shadow-lg">
                        <i class="ph-bold ph-file-text mr-1.5"></i> Biodata
                    </a>
                    
                    <a href="<?php echo e(route('portal.index')); ?>" class="flex-1 sm:flex-none justify-center inline-flex items-center px-4 py-2.5 bg-elevate-primary/30 backdrop-blur-md border border-elevate-accent/30 rounded-xl text-xs sm:text-sm font-bold text-white hover:bg-elevate-primary transition-all shadow-lg">
                        <i class="ph-bold ph-magnifying-glass mr-1.5"></i> Cari Lain
                    </a>
                </div>
                
            </div>
            
        </div>
    </div>
</div><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/students/portal/partials/header.blade.php ENDPATH**/ ?>