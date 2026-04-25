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
    
    <div class="relative pt-32 pb-24 rounded-b-[3rem] shadow-2xl overflow-hidden -mt-24 mb-12">
        
        
        <div class="absolute inset-0 z-0">
            <img src="<?php echo e(asset('images/netila.jpg')); ?>" 
                 alt="Background Sekolah" 
                 class="w-full h-full object-cover opacity-90 transform hover:scale-105 transition-transform duration-[70s]"
                 onerror="this.style.opacity='0'">
            
            
            <div class="absolute inset-0 bg-gradient-to-br from-cyan-600/95 via-blue-700/90 to-blue-900/95"></div>
            
            
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 mix-blend-overlay"></div>
        </div>

        
        <div class="absolute top-0 right-0 w-96 h-96 bg-cyan-300/30 rounded-full blur-[80px] pointer-events-none translate-x-1/2 -translate-y-1/2 mix-blend-screen animate-float"></div>
        <div class="absolute bottom-0 left-0 w-80 h-80 bg-blue-400/20 rounded-full blur-[80px] pointer-events-none -translate-x-1/2 translate-y-1/2 mix-blend-screen animate-float" style="animation-delay: 2s"></div>
        
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center animate-enter">
            <span class="px-4 py-1.5 bg-white/10 text-cyan-100 rounded-full text-xs font-bold uppercase tracking-widest border border-white/20 mb-6 inline-block backdrop-blur-md shadow-sm">
                <i class="ph-fill ph-chats-circle mr-1"></i> Kata Alumni
            </span>
            <h1 class="text-4xl md:text-6xl font-black text-white mb-6 tracking-tight drop-shadow-lg leading-tight">
                Jejak Langkah <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-200 to-white">Alumni</span>
            </h1>
            <p class="text-blue-50 text-lg max-w-2xl mx-auto font-medium leading-relaxed opacity-90">
                Kumpulan kisah sukses, kenangan manis, dan inspirasi dari para alumni selama menempuh pendidikan di SMP Negeri 3 Lakbok.
            </p>
        </div>
    </div>

    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
            <?php $__empty_1 = true; $__currentLoopData = $testimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $testi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="animate-enter bg-white rounded-[2rem] p-8 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07)] border border-slate-100 hover:shadow-2xl hover:shadow-cyan-900/10 hover:border-cyan-200 hover:-translate-y-2 transition-all duration-500 flex flex-col h-full relative group overflow-hidden"
                     style="animation-delay: <?php echo e($index * 100); ?>ms">
                    
                    
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-slate-50 to-cyan-50/50 rounded-bl-[100%] -mr-8 -mt-8 transition-colors group-hover:from-cyan-50 group-hover:to-blue-50"></div>
                    <i class="ph-fill ph-quotes text-6xl text-slate-100 absolute top-4 right-4 group-hover:text-cyan-100 transition-colors duration-500 transform group-hover:rotate-12"></i>

                    
                    <div class="flex items-center gap-4 mb-6 relative z-10">
                        <div class="w-14 h-14 rounded-full bg-slate-100 border-2 border-white shadow-lg overflow-hidden shrink-0 group-hover:scale-110 transition-transform duration-500">
                            <?php if($testi->student && $testi->student->photo_path): ?>
                                <img src="<?php echo e(asset('storage/' . $testi->student->photo_path)); ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-cyan-500 to-blue-600 text-white font-bold text-xl">
                                    <?php echo e(substr($testi->student->name ?? 'A', 0, 1)); ?>

                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="min-w-0">
                            <h4 class="font-bold text-slate-900 text-base truncate group-hover:text-cyan-600 transition-colors" title="<?php echo e($testi->student->name ?? 'Alumni'); ?>">
                                <?php echo e($testi->student->name ?? 'Alumni'); ?>

                            </h4>
                            <div class="flex flex-col">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">
                                    Lulusan <?php echo e($testi->student->graduation_year ?? '-'); ?>

                                </span>
                                <span class="text-xs text-cyan-700 font-bold truncate max-w-[150px] bg-cyan-50 border border-cyan-100 px-2 py-0.5 rounded-md mt-1 w-fit">
                                    <?php echo e($testi->activity_status); ?> 
                                    <?php if($testi->campus_name || $testi->company_name): ?>
                                        @ <?php echo e($testi->campus_name ?? $testi->company_name); ?>

                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    
                    <div class="flex-1 relative z-10">
                        <p class="text-slate-600 text-sm italic leading-relaxed relative">
                            "<?php echo e($testi->testimony); ?>"
                        </p>
                    </div>

                    
                    <div class="mt-8 pt-4 border-t border-slate-50 flex items-center justify-between text-xs text-slate-400">
                        <span class="flex items-center gap-1"><i class="ph-bold ph-calendar"></i> <?php echo e($testi->updated_at->format('d M Y')); ?></span>
                        <div class="flex items-center gap-0.5 text-amber-400 text-sm">
                            <?php for($i=0; $i < ($testi->rating ?? 5); $i++): ?> <i class="ph-fill ph-star"></i> <?php endfor; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-full py-24 text-center animate-enter">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-slate-100 text-slate-300 mb-6 ring-8 ring-slate-50">
                        <i class="ph-duotone ph-chat-teardrop-slash text-5xl"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-800 mb-2">Belum Ada Testimoni</h3>
                    <p class="text-slate-500 font-medium max-w-md mx-auto">Jadilah alumni pertama yang membagikan kisah suksesmu di sini.</p>
                </div>
            <?php endif; ?>
        </div>

        
        <div class="mb-16 flex justify-center animate-enter">
            <?php echo e($testimonials->onEachSide(1)->links()); ?>

        </div>

        
        <div class="animate-enter relative bg-gradient-to-br from-cyan-600 via-blue-600 to-blue-900 rounded-[2.5rem] p-8 md:p-16 text-center shadow-2xl overflow-hidden group border border-white/10">
            <!-- Decorative Background -->
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 mix-blend-overlay"></div>
            
            <!-- Blob Decor -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-cyan-300/30 rounded-full blur-[60px] translate-x-1/2 -translate-y-1/2 pointer-events-none group-hover:scale-110 transition-transform duration-700"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-indigo-900/30 rounded-full blur-[60px] -translate-x-1/2 translate-y-1/2 pointer-events-none"></div>

            <div class="relative z-10 max-w-2xl mx-auto">
                <h3 class="text-3xl font-black text-white mb-4 tracking-tight">Kamu Alumni Sekolah Ini?</h3>
                <p class="text-blue-50 mb-8 font-medium leading-relaxed opacity-90">
                    Mari berbagi pengalaman dan inspirasi untuk adik-adik kelasmu. Partisipasi Anda sangat berarti untuk kemajuan sekolah dan update data tracer study.
                </p>
                
                <?php if(auth()->guard('student')->check()): ?>
                    <a href="<?php echo e(route('alumni.tracer')); ?>" class="inline-flex items-center justify-center px-8 py-4 text-sm font-bold text-blue-700 bg-white rounded-full hover:bg-slate-50 hover:-translate-y-1 transition-all shadow-lg shadow-black/20 group">
                        <i class="ph-bold ph-pencil-simple mr-2 text-lg"></i> Tulis Testimoni
                    </a>
                <?php else: ?>
                    <a href="<?php echo e(route('student.login')); ?>" class="inline-flex items-center justify-center px-8 py-4 text-sm font-bold text-blue-700 bg-white rounded-full hover:bg-slate-50 hover:-translate-y-1 transition-all shadow-lg shadow-black/20">
                        <i class="ph-bold ph-sign-in mr-2 text-lg"></i> Login Alumni
                    </a>
                <?php endif; ?>
            </div>
        </div>

    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/testimonials.blade.php ENDPATH**/ ?>