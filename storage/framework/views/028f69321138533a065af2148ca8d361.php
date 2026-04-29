<?php $__env->startSection('title', 'Apa Kata Alumni - ' . config('app.name', 'SMP Negeri 3 Lakbok')); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        /* Animasi Custom */
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
        
        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
        .animate-float { animation: float 6s ease-in-out infinite; }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    
    <div class="relative pt-32 pb-24 rounded-b-[3rem] shadow-sm border-b border-slate-200/50 overflow-hidden -mt-24 mb-12 bg-white">
        
        
        <div class="absolute inset-0 z-0">
            <img src="<?php echo e(asset('images/netila.jpg')); ?>" 
                 alt="Background Sekolah" 
                 class="w-full h-full object-cover opacity-80 transform hover:scale-105 transition-transform duration-[70s]"
                 onerror="this.style.opacity='0'">
            
            
            <div class="absolute inset-0 bg-white/85 backdrop-blur-[3px]"></div>
            <div class="absolute inset-0 bg-elevate-gradient-main mix-blend-multiply opacity-40"></div>
            
            
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-5 mix-blend-overlay"></div>
        </div>

        
        <div class="absolute top-0 right-0 w-96 h-96 bg-elevate-accent/20 rounded-full blur-[80px] pointer-events-none translate-x-1/2 -translate-y-1/2 animate-float"></div>
        <div class="absolute bottom-0 left-0 w-80 h-80 bg-elevate-peach/20 rounded-full blur-[80px] pointer-events-none -translate-x-1/2 translate-y-1/2 animate-float" style="animation-delay: 2s"></div>
        
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center animate-enter">
            <span class="px-4 py-1.5 bg-white/60 text-elevate-primary rounded-full text-xs font-bold uppercase tracking-widest border border-white mb-6 inline-flex items-center backdrop-blur-md shadow-sm">
                <i class="ph-fill ph-chats-circle mr-1.5 text-elevate-accent"></i> Kata Alumni
            </span>
            <h1 class="text-4xl md:text-6xl font-black text-elevate-dark mb-6 tracking-tight drop-shadow-sm leading-tight">
                Jejak Langkah <span class="text-transparent bg-clip-text bg-gradient-to-r from-elevate-primary to-elevate-accent">Alumni</span>
            </h1>
            <p class="text-elevate-dark/80 text-lg max-w-2xl mx-auto font-medium leading-relaxed">
                Kumpulan kisah sukses, kenangan manis, dan inspirasi dari para alumni selama menempuh pendidikan di SMP Negeri 3 Lakbok.
            </p>
        </div>
    </div>

    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
            <?php $__empty_1 = true; $__currentLoopData = $testimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $testi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="animate-enter bg-elevate-surface rounded-[2rem] p-8 shadow-sm border border-slate-100 hover:shadow-xl hover:shadow-elevate-accent/10 hover:border-elevate-accent/40 hover:-translate-y-2 transition-all duration-500 flex flex-col h-full relative group overflow-hidden"
                     style="animation-delay: <?php echo e($index * 100); ?>ms">
                    
                    
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-slate-50 to-elevate-soft/50 rounded-bl-[100%] -mr-8 -mt-8 transition-colors group-hover:from-elevate-soft group-hover:to-elevate-primary/10"></div>
                    <i class="ph-fill ph-quotes text-6xl text-elevate-soft absolute top-4 right-4 group-hover:text-elevate-primary/10 transition-colors duration-500 transform group-hover:rotate-12"></i>

                    
                    <div class="flex items-center gap-4 mb-6 relative z-10">
                        <div class="w-14 h-14 rounded-full bg-elevate-soft border-2 border-white shadow-md overflow-hidden shrink-0 group-hover:scale-110 transition-transform duration-500">
                            <?php if($testi->student && $testi->student->photo_path): ?>
                                <img src="<?php echo e(asset('storage/' . $testi->student->photo_path)); ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center bg-elevate-soft text-elevate-primary font-black text-xl">
                                    <?php echo e(substr($testi->student->name ?? 'A', 0, 1)); ?>

                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="min-w-0">
                            <h4 class="font-bold text-elevate-dark text-base truncate group-hover:text-elevate-primary transition-colors" title="<?php echo e($testi->student->name ?? 'Alumni'); ?>">
                                <?php echo e($testi->student->name ?? 'Alumni'); ?>

                            </h4>
                            <div class="flex flex-col">
                                <span class="text-[10px] font-bold text-elevate-dark/50 uppercase tracking-wide">
                                    Lulusan <?php echo e($testi->student->graduation_year ?? '-'); ?>

                                </span>
                                <span class="text-xs text-elevate-primary font-bold truncate max-w-[150px] bg-elevate-accent/10 border border-elevate-accent/20 px-2 py-0.5 rounded-md mt-1 w-fit">
                                    <?php echo e($testi->activity_status); ?> 
                                    <?php if($testi->campus_name || $testi->company_name): ?>
                                        @ <?php echo e($testi->campus_name ?? $testi->company_name); ?>

                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    
                    <div class="flex-1 relative z-10">
                        <p class="text-elevate-dark/80 text-sm italic leading-relaxed relative">
                            "<?php echo e($testi->testimony); ?>"
                        </p>
                    </div>

                    
                    <div class="mt-8 pt-4 border-t border-slate-50 flex items-center justify-between text-xs text-elevate-dark/40">
                        <span class="flex items-center gap-1 font-medium"><i class="ph-bold ph-calendar"></i> <?php echo e($testi->updated_at->format('d M Y')); ?></span>
                        <div class="flex items-center gap-0.5 text-elevate-peach-dark text-sm">
                            <?php for($i=0; $i < ($testi->rating ?? 5); $i++): ?> <i class="ph-fill ph-star"></i> <?php endfor; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-full py-24 text-center animate-enter bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-elevate-soft text-elevate-primary mb-6 ring-8 ring-elevate-soft/50">
                        <i class="ph-duotone ph-chat-teardrop-slash text-5xl"></i>
                    </div>
                    <h3 class="text-2xl font-black text-elevate-dark mb-2">Belum Ada Testimoni</h3>
                    <p class="text-elevate-dark/60 font-medium max-w-md mx-auto">Jadilah alumni pertama yang membagikan kisah suksesmu di sini.</p>
                </div>
            <?php endif; ?>
        </div>

        
        <div class="mb-16 flex justify-center animate-enter">
            <?php echo e($testimonials->onEachSide(1)->links()); ?>

        </div>

        
        <div class="animate-enter relative bg-elevate-gradient-main rounded-[2.5rem] p-8 md:p-16 text-center shadow-lg overflow-hidden group border border-white/60">
            <!-- Decorative Background -->
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-[0.03] mix-blend-overlay"></div>
            
            <!-- Blob Decor Elevate -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-elevate-primary/10 rounded-full blur-[60px] translate-x-1/2 -translate-y-1/2 pointer-events-none group-hover:scale-110 transition-transform duration-700"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-elevate-peach/20 rounded-full blur-[60px] -translate-x-1/2 translate-y-1/2 pointer-events-none"></div>

            <div class="relative z-10 max-w-2xl mx-auto">
                <h3 class="text-3xl font-black text-elevate-dark mb-4 tracking-tight">Kamu Alumni Sekolah Ini?</h3>
                <p class="text-elevate-dark/80 mb-8 font-medium leading-relaxed">
                    Mari berbagi pengalaman dan inspirasi untuk adik-adik kelasmu. Partisipasi Anda sangat berarti untuk kemajuan sekolah dan update data tracer study.
                </p>
                
                <?php if(auth()->guard('student')->check()): ?>
                    <a href="<?php echo e(route('alumni.tracer')); ?>" class="inline-flex items-center justify-center px-8 py-4 text-sm font-bold text-white bg-elevate-dark rounded-full hover:bg-elevate-dark/90 hover:-translate-y-1 transition-all shadow-lg shadow-elevate-dark/20 group">
                        <i class="ph-bold ph-pencil-simple mr-2 text-lg"></i> Tulis Testimoni
                    </a>
                <?php else: ?>
                    <a href="<?php echo e(route('student.login')); ?>" class="inline-flex items-center justify-center px-8 py-4 text-sm font-bold text-white bg-elevate-dark rounded-full hover:bg-elevate-dark/90 hover:-translate-y-1 transition-all shadow-lg shadow-elevate-dark/20 group">
                        <i class="ph-bold ph-sign-in mr-2 text-lg"></i> Login Alumni
                    </a>
                <?php endif; ?>
            </div>
        </div>

    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/testimonials.blade.php ENDPATH**/ ?>