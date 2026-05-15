<?php if (isset($component)) { $__componentOriginal5acda7f50fc1fb55f4bf1672ea512a11 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5acda7f50fc1fb55f4bf1672ea512a11 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.student-learning-layout','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('student-learning-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        
        /* Shimmer Effect untuk Prioritas */
        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }
        .animate-shimmer {
            animation: shimmer 2s infinite linear;
            background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0.4) 50%, rgba(255,255,255,0) 100%);
            background-size: 1000px 100%;
        }
    </style>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        
        
        <div class="animate-enter bg-elevate-dark rounded-[2.5rem] p-8 md:p-12 mb-12 relative overflow-hidden shadow-2xl shadow-elevate-dark/20 border border-elevate-primary/20 group">
            
            <div class="absolute inset-0 bg-gradient-to-br from-elevate-dark via-elevate-dark to-elevate-primary/40"></div>
            <div class="absolute top-0 right-0 w-80 h-80 bg-elevate-accent/20 rounded-full blur-[100px] -mr-20 -mt-20 pointer-events-none group-hover:bg-elevate-accent/30 transition-all duration-1000"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-elevate-peach/20 rounded-full blur-[80px] -ml-10 -mb-10 pointer-events-none group-hover:bg-elevate-peach/30 transition-all duration-1000"></div>
            <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-10">
                <div class="text-center md:text-left flex-1">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/5 border border-white/10 backdrop-blur-md mb-6 shadow-sm">
                        <span class="relative flex h-2.5 w-2.5">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-elevate-accent opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-elevate-accent"></span>
                        </span>
                        <span class="text-[11px] font-bold text-elevate-soft uppercase tracking-widest">Tahun Ajaran Aktif</span>
                    </div>
                    
                    <h1 class="text-4xl md:text-6xl font-black text-white mb-4 tracking-tight leading-tight">
                        Halo, <span class="text-transparent bg-clip-text bg-gradient-to-r from-elevate-accent via-white to-elevate-peach-light animate-gradient"><?php echo e(explode(' ', Auth::guard('student')->user()->name)[0]); ?>!</span>
                    </h1>
                    
                    <p class="text-elevate-soft font-medium text-base md:text-lg max-w-xl mb-8 leading-relaxed opacity-90">
                        Selamat datang kembali di ruang belajar digitalmu. <br class="hidden md:block"> Jangan lupa cek tugas prioritas sebelum memulai materi baru hari ini.
                    </p>

                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 text-white text-sm font-bold">
                        <div class="flex items-center gap-2 bg-white/10 px-4 py-2 rounded-xl border border-white/10 hover:bg-white/20 transition-colors">
                            <i class="ph-fill ph-student text-elevate-peach-light text-lg"></i>
                            <span>Kelas <?php echo e(Auth::guard('student')->user()->schoolClass->name ?? 'Umum'); ?></span>
                        </div>
                        <div class="flex items-center gap-2 bg-white/10 px-4 py-2 rounded-xl border border-white/10 hover:bg-white/20 transition-colors">
                            <i class="ph-fill ph-calendar text-elevate-accent text-lg"></i>
                            <span><?php echo e(now()->translatedFormat('l, d F Y')); ?></span>
                        </div>
                    </div>
                </div>
                
                
                <div class="hidden md:block relative transform hover:scale-105 transition-transform duration-700">
                    <div class="relative w-40 h-40">
                        <div class="absolute inset-0 bg-gradient-to-tr from-elevate-primary to-elevate-accent rounded-[2rem] rotate-6 opacity-60 blur-lg"></div>
                        <div class="absolute inset-0 bg-gradient-to-tr from-elevate-dark to-elevate-primary rounded-[2rem] shadow-2xl border border-white/10 flex items-center justify-center z-10">
                            <i class="ph-duotone ph-rocket-launch text-7xl text-white drop-shadow-[0_0_15px_rgba(86,187,241,0.5)]"></i>
                        </div>
                        <div class="absolute -top-4 -right-4 bg-white text-elevate-dark p-3 rounded-xl shadow-lg font-black text-xs z-20 animate-bounce">
                            <i class="ph-fill ph-star text-elevate-peach"></i> Semangat!
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <?php if(isset($prioritySubjects) && $prioritySubjects->count() > 0): ?>
            <div class="animate-enter mb-16" style="animation-delay: 100ms">
                <div class="flex items-end justify-between mb-8 px-2">
                    <div>
                        <h2 class="text-2xl font-black text-elevate-dark flex items-center gap-3">
                            <span class="w-10 h-10 rounded-xl bg-elevate-peach/20 text-elevate-peach-dark flex items-center justify-center text-xl shadow-sm rotate-3">
                                <i class="ph-fill ph-fire"></i>
                            </span>
                            Prioritas Belajar
                        </h2>
                        <p class="text-sm font-bold text-elevate-dark/50 mt-2 ml-14">Selesaikan tugas ini segera.</p>
                    </div>
                </div>

                 <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php $__currentLoopData = $prioritySubjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('students.learning.play', $subject->id)); ?>" class="group relative bg-elevate-surface rounded-[2rem] p-1 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1 <?php echo e($subject->active_tasks_count > 0 ? 'hover:shadow-elevate-peach-dark/10' : 'hover:shadow-elevate-primary/10'); ?>">
                            
                            <div class="absolute inset-0 bg-gradient-to-br <?php echo e($subject->active_tasks_count > 0 ? 'from-elevate-peach-light/50 to-white' : 'from-elevate-soft to-white'); ?> rounded-[2rem] opacity-50 group-hover:opacity-100 transition-opacity"></div>
                            
                            <div class="relative bg-elevate-surface rounded-[1.8rem] p-6 h-full border <?php echo e($subject->active_tasks_count > 0 ? 'border-elevate-peach/30' : 'border-elevate-soft'); ?> flex flex-col">
                                
                                <div class="flex justify-between items-start mb-4">
                                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br <?php echo e($subject->active_tasks_count > 0 ? 'from-elevate-peach-light/40 to-white text-elevate-peach-dark border-elevate-peach/30' : 'from-elevate-soft to-white text-elevate-primary border-elevate-soft'); ?> border flex items-center justify-center text-2xl shadow-inner group-hover:scale-110 transition-transform">
                                        <i class="ph-duotone <?php echo e($subject->active_tasks_count > 0 ? 'ph-warning-circle' : 'ph-book-open'); ?>"></i>
                                    </div>
                                    <?php if($subject->active_tasks_count > 0): ?>
                                        <span class="relative overflow-hidden bg-elevate-peach-dark text-white text-[10px] font-black px-3 py-1.5 rounded-lg shadow-md shadow-elevate-peach/30 uppercase tracking-wider">
                                            <span class="relative z-10"><?php echo e($subject->active_tasks_count); ?> Tugas</span>
                                            <div class="absolute inset-0 -translate-x-full animate-shimmer z-0"></div>
                                        </span>
                                    <?php elseif($subject->new_materials_count > 0): ?>
                                        <span class="relative overflow-hidden bg-elevate-primary text-white text-[10px] font-black px-3 py-1.5 rounded-lg shadow-md shadow-elevate-primary/30 uppercase tracking-wider">
                                            <span class="relative z-10">Materi Baru</span>
                                            <div class="absolute inset-0 -translate-x-full animate-shimmer z-0"></div>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                                <h3 class="font-bold text-lg text-elevate-dark mb-1 group-hover:<?php echo e($subject->active_tasks_count > 0 ? 'text-elevate-peach-dark' : 'text-elevate-primary'); ?> transition-colors line-clamp-1"><?php echo e($subject->name); ?></h3>
                                <p class="text-xs text-elevate-dark/50 font-bold mb-4">
                                    <?php echo e($subject->active_tasks_count > 0 ? 'Deadline semakin dekat!' : 'Ada materi baru di kelas ini.'); ?>

                                </p>
                                
                                
                                <div class="mt-auto pt-4 border-t border-elevate-soft/50 flex items-center justify-between">
                                    <span class="text-xs font-bold <?php echo e($subject->active_tasks_count > 0 ? 'text-elevate-peach-dark' : 'text-elevate-primary'); ?> group-hover:underline">
                                        <?php echo e($subject->active_tasks_count > 0 ? 'Kerjakan Sekarang' : 'Pelajari Sekarang'); ?>

                                    </span>
                                    <i class="ph-bold ph-arrow-right <?php echo e($subject->active_tasks_count > 0 ? 'text-elevate-peach' : 'text-elevate-accent'); ?> group-hover:translate-x-1 transition-transform"></i>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php endif; ?>

        
        <div class="animate-enter" style="animation-delay: 200ms">
            <div class="flex items-end justify-between mb-8 px-2">
                <div>
                    <h2 class="text-2xl font-black text-elevate-dark flex items-center gap-3">
                        <span class="w-10 h-10 rounded-xl bg-elevate-soft text-elevate-primary flex items-center justify-center text-xl shadow-sm -rotate-3">
                            <i class="ph-fill ph-books"></i>
                        </span>
                        Ruang Kelas
                    </h2>
                    <p class="text-sm font-bold text-elevate-dark/50 mt-2 ml-14">Daftar semua mata pelajaranmu.</p>
                </div>
            </div>
            
            <?php if(!isset($allSubjects) || $allSubjects->isEmpty()): ?>
                <div class="bg-elevate-surface rounded-[2.5rem] p-12 text-center border-2 border-dashed border-elevate-soft">
                    <div class="w-24 h-24 bg-elevate-soft/50 rounded-full flex items-center justify-center mx-auto mb-6 text-elevate-dark/30">
                        <i class="ph-duotone ph-books text-5xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-elevate-dark mb-2">Belum Ada Mata Pelajaran</h3>
                    <p class="text-elevate-dark/60 font-medium max-w-md mx-auto">Data mata pelajaran belum ditambahkan oleh admin atau kamu belum terdaftar di kelas manapun.</p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <?php $__currentLoopData = $allSubjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            // Palet disesuaikan agar cocok dengan tema Elevate
                            $palettes = [
                                'blue'    => ['bg' => 'from-elevate-primary to-blue-500', 'light' => 'bg-elevate-soft', 'text' => 'text-elevate-primary', 'border' => 'border-elevate-soft', 'shadow' => 'shadow-elevate-primary/20'],
                                'cyan'    => ['bg' => 'from-elevate-accent to-cyan-500', 'light' => 'bg-cyan-50', 'text' => 'text-cyan-700', 'border' => 'border-cyan-100', 'shadow' => 'shadow-elevate-accent/20'],
                                'peach'   => ['bg' => 'from-elevate-peach-dark to-elevate-peach', 'light' => 'bg-elevate-peach-light/30', 'text' => 'text-elevate-peach-dark', 'border' => 'border-elevate-peach/30', 'shadow' => 'shadow-elevate-peach/20'],
                                'navy'    => ['bg' => 'from-elevate-dark to-slate-700', 'light' => 'bg-slate-100', 'text' => 'text-elevate-dark', 'border' => 'border-slate-200', 'shadow' => 'shadow-elevate-dark/20'],
                                'emerald' => ['bg' => 'from-emerald-500 to-teal-500', 'light' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-100', 'shadow' => 'shadow-emerald-200'],
                            ];

                            $name = strtolower($subject->name);
                            $selectedIcon = 'ph-book-bookmark'; 
                            $selectedTheme = 'blue'; 

                            if (str_contains($name, 'informatika') || str_contains($name, 'tik') || str_contains($name, 'komputer')) {
                                $selectedIcon = 'ph-desktop'; $selectedTheme = 'cyan';
                            } elseif (str_contains($name, 'seni') || str_contains($name, 'budaya')) {
                                $selectedIcon = 'ph-palette'; $selectedTheme = 'peach';
                            } elseif (str_contains($name, 'matematika')) {
                                $selectedIcon = 'ph-calculator'; $selectedTheme = 'navy';
                            } elseif (str_contains($name, 'ipa') || str_contains($name, 'fisika') || str_contains($name, 'kimia')) {
                                $selectedIcon = 'ph-flask'; $selectedTheme = 'emerald';
                            } elseif (str_contains($name, 'ips') || str_contains($name, 'sejarah')) {
                                $selectedIcon = 'ph-globe-hemisphere-west'; $selectedTheme = 'blue';
                            } elseif (str_contains($name, 'bahasa')) {
                                $selectedIcon = 'ph-translate'; $selectedTheme = 'peach';
                            } elseif (str_contains($name, 'agama')) {
                                $selectedIcon = 'ph-hands-praying'; $selectedTheme = 'emerald';
                            } elseif (str_contains($name, 'pjok') || str_contains($name, 'olahraga')) {
                                $selectedIcon = 'ph-soccer-ball'; $selectedTheme = 'cyan';
                            } else {
                                $keys = array_keys($palettes);
                                $selectedTheme = $keys[$index % count($keys)];
                            }

                            $t = $palettes[$selectedTheme];
                        ?>

                         <a href="<?php echo e(route('students.learning.play', $subject->id)); ?>" class="group relative flex flex-col bg-elevate-surface rounded-[2rem] p-1.5 shadow-sm hover:shadow-2xl hover:shadow-elevate-dark/10 transition-all duration-300 hover:-translate-y-1.5 h-full border border-elevate-soft/50">
                            
                            <div class="flex-1 bg-elevate-surface rounded-[1.7rem] p-5 border <?php echo e($t['border']); ?> relative overflow-hidden">
                                
                                <div class="absolute -right-10 -top-10 w-32 h-32 rounded-full bg-gradient-to-br <?php echo e($t['bg']); ?> opacity-0 group-hover:opacity-10 blur-2xl transition-opacity duration-500"></div>

                                <div class="flex justify-between items-start mb-5 relative z-10">
                                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br <?php echo e($t['bg']); ?> text-white flex items-center justify-center text-2xl shadow-lg <?php echo e($t['shadow']); ?> group-hover:scale-110 transition-transform duration-300">
                                        <i class="ph-duotone <?php echo e($selectedIcon); ?>"></i>
                                    </div>
                                    
                                    <?php if($subject->new_materials_count > 0): ?>
                                        <span class="inline-flex items-center gap-1 bg-elevate-accent/10 text-elevate-primary border border-elevate-accent/20 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider">
                                            <span class="w-1.5 h-1.5 rounded-full bg-elevate-accent animate-pulse"></span>
                                            <?php echo e($subject->new_materials_count); ?> Materi
                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                                <h4 class="font-bold text-lg text-elevate-dark leading-snug mb-1 group-hover:<?php echo e($t['text']); ?> transition-colors line-clamp-2">
                                    <?php echo e($subject->name); ?>

                                </h4>
                                <p class="text-xs font-bold text-elevate-dark/40">Guru Pengampu</p>
                                
                                <div class="mt-4 flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-elevate-soft flex items-center justify-center text-elevate-primary border border-white shadow-sm">
                                        <i class="ph-bold ph-user"></i>
                                    </div>
                                    <span class="text-xs font-bold text-elevate-dark/60 truncate">Tim Pengajar</span>
                                </div>
                            </div>

                            <div class="px-5 py-3 flex items-center justify-between">
                                <span class="text-[10px] font-black text-elevate-dark/30 uppercase tracking-widest group-hover:<?php echo e($t['text']); ?> transition-colors">Masuk Kelas</span>
                                <div class="w-8 h-8 rounded-full <?php echo e($t['light']); ?> <?php echo e($t['text']); ?> flex items-center justify-center opacity-0 group-hover:opacity-100 transform translate-x-2 group-hover:translate-x-0 transition-all duration-300">
                                    <i class="ph-bold ph-arrow-right"></i>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if(session('success')): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: "<?php echo e(session('success')); ?>",
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    customClass: {
                        popup: 'rounded-xl shadow-lg border border-emerald-100 bg-white'
                    }
                });
            <?php endif; ?>

            <?php if(session('error')): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "<?php echo e(session('error')); ?>",
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true,
                    customClass: {
                        popup: 'rounded-xl shadow-lg border border-rose-100 bg-white'
                    }
                });
            <?php endif; ?>
        });
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5acda7f50fc1fb55f4bf1672ea512a11)): ?>
<?php $attributes = $__attributesOriginal5acda7f50fc1fb55f4bf1672ea512a11; ?>
<?php unset($__attributesOriginal5acda7f50fc1fb55f4bf1672ea512a11); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5acda7f50fc1fb55f4bf1672ea512a11)): ?>
<?php $component = $__componentOriginal5acda7f50fc1fb55f4bf1672ea512a11; ?>
<?php unset($__componentOriginal5acda7f50fc1fb55f4bf1672ea512a11); ?>
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/students/lms/index.blade.php ENDPATH**/ ?>