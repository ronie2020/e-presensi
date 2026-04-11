       
    <div id="alumni" class="py-24 bg-gradient-to-br from-indigo-900 to-slate-900 relative overflow-hidden border-t border-slate-800">
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
        <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-indigo-500 to-transparent opacity-50"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="px-3 py-1 bg-indigo-500/20 text-indigo-300 rounded-full text-xs font-bold uppercase tracking-widest border border-indigo-500/30">
                    Tracer Study
                </span>
                <h2 class="text-3xl font-extrabold text-white sm:text-4xl mt-4">Jejak Langkah Alumni</h2>
                <p class="mt-4 text-lg text-indigo-200 max-w-2xl mx-auto">
                    Melihat sebaran dan kisah sukses para alumni SMPN 3 Lakbok yang telah melanjutkan ke jenjang lebih tinggi.
                </p>
            </div>

            <!-- STATISTIK ALUMNI -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-20">
                <div class="bg-indigo-950/50 backdrop-blur-sm border border-indigo-800/50 rounded-2xl p-6 text-center hover:bg-indigo-900/50 transition-all duration-300 group" data-aos="fade-up" data-aos-delay="0">
                    <p class="text-4xl font-black text-white mb-2 group-hover:scale-110 transition-transform"><?php echo e($alumniStats['total'] ?? 0); ?></p>
                    <p class="text-[10px] font-bold text-indigo-300 uppercase tracking-wider">Total Alumni</p>
                </div>
                <div class="bg-indigo-950/50 backdrop-blur-sm border border-indigo-800/50 rounded-2xl p-6 text-center hover:bg-indigo-900/50 transition-all duration-300 group" data-aos="fade-up" data-aos-delay="100">
                    <p class="text-4xl font-black text-blue-400 mb-2 group-hover:scale-110 transition-transform"><?php echo e($alumniStats['sma'] ?? 0); ?></p>
                    <p class="text-[10px] font-bold text-indigo-300 uppercase tracking-wider">Lanjut SMA</p>
                </div>
                <div class="bg-indigo-950/50 backdrop-blur-sm border border-indigo-800/50 rounded-2xl p-6 text-center hover:bg-indigo-900/50 transition-all duration-300 group" data-aos="fade-up" data-aos-delay="200">
                    <p class="text-4xl font-black text-orange-400 mb-2 group-hover:scale-110 transition-transform"><?php echo e($alumniStats['smk'] ?? 0); ?></p>
                    <p class="text-[10px] font-bold text-indigo-300 uppercase tracking-wider">Lanjut SMK</p>
                </div>
                <div class="bg-indigo-950/50 backdrop-blur-sm border border-indigo-800/50 rounded-2xl p-6 text-center hover:bg-indigo-900/50 transition-all duration-300 group" data-aos="fade-up" data-aos-delay="300">
                    <p class="text-4xl font-black text-emerald-400 mb-2 group-hover:scale-110 transition-transform"><?php echo e($alumniStats['pesantren'] ?? 0); ?></p>
                    <p class="text-[10px] font-bold text-indigo-300 uppercase tracking-wider">Pesantren</p>
                </div>
                <div class="bg-indigo-950/50 backdrop-blur-sm border border-indigo-800/50 rounded-2xl p-6 text-center hover:bg-indigo-900/50 transition-all duration-300 group" data-aos="fade-up" data-aos-delay="400">
                    <p class="text-4xl font-black text-slate-300 mb-2 group-hover:scale-110 transition-transform"><?php echo e($alumniStats['bekerja'] ?? 0); ?></p>
                    <p class="text-[10px] font-bold text-indigo-300 uppercase tracking-wider">Bekerja</p>
                </div>
            </div>

            <!-- SLIDER TESTIMONI ALUMNI -->
            <?php if(isset($alumniTestimonials) && count($alumniTestimonials) > 0): ?>
                <div class="flex overflow-x-auto gap-6 pb-8 custom-scrollbar hide-scroll snap-x snap-mandatory">
                    <?php $__currentLoopData = $alumniTestimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $testi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="min-w-[300px] md:min-w-[400px] bg-white rounded-3xl p-8 shadow-xl relative snap-center group hover:-translate-y-2 transition-transform duration-300">
                            <i class="ph-fill ph-quotes text-5xl text-indigo-100 absolute top-6 right-6 group-hover:text-indigo-200 transition-colors"></i>
                            
                            <div class="relative z-10 h-full flex flex-col">
                                <div class="flex items-center gap-4 mb-6">
                                    <div class="w-14 h-14 rounded-full bg-slate-100 border-2 border-indigo-100 overflow-hidden shrink-0">
                                        <?php if($testi->student && $testi->student->photo_path): ?>
                                            <img src="<?php echo e(asset('storage/' . $testi->student->photo_path)); ?>" class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <div class="w-full h-full flex items-center justify-center bg-indigo-500 text-white font-bold text-xl"><?php echo e(substr($testi->student->name ?? 'A', 0, 1)); ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-slate-900 text-base line-clamp-1"><?php echo e($testi->student->name ?? 'Alumni'); ?></h4>
                                        <p class="text-xs text-indigo-600 font-bold uppercase mt-0.5">
                                            <?php echo e($testi->activity_status); ?> 
                                            <?php if($testi->campus_name || $testi->company_name): ?>
                                                @ <?php echo e(Str::limit($testi->campus_name ?? $testi->company_name, 20)); ?>

                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="flex-1">
                                    <p class="text-slate-600 text-sm italic leading-relaxed line-clamp-4">
                                        "<?php echo e($testi->testimony); ?>"
                                    </p>
                                </div>

                                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center gap-1 text-yellow-400 text-sm">
                                    <?php for($i=0; $i < ($testi->rating ?? 5); $i++): ?> <i class="ph-fill ph-star"></i> <?php endfor; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                
                <div class="flex justify-center gap-2 mt-4 md:hidden">
                    <i class="ph-bold ph-arrow-left text-indigo-400 animate-pulse"></i>
                    <span class="text-xs text-indigo-300 font-medium">Geser untuk melihat testimoni</span>
                    <i class="ph-bold ph-arrow-right text-indigo-400 animate-pulse"></i>
                </div>

                 
                <div class="mt-12 text-center" data-aos="fade-up">
                    <a href="<?php echo e(route('public.testimonials')); ?>" class="inline-flex items-center justify-center px-8 py-3 text-sm font-bold text-indigo-100 bg-indigo-800/50 border border-indigo-500/50 rounded-full hover:bg-indigo-700 hover:text-white transition-all shadow-lg hover:shadow-indigo-500/30 group">
                        Lihat Semua Testimoni 
                        <i class="ph-bold ph-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            <?php else: ?>
                <div class="text-center py-12 border border-dashed border-indigo-800/30 rounded-3xl bg-indigo-900/20">
                    <p class="text-indigo-300 italic">Belum ada testimoni alumni yang ditampilkan.</p>
                </div>
            <?php endif; ?>
        </div>
    </div><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\landing\alumni.blade.php ENDPATH**/ ?>