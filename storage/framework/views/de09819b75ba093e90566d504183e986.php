<div class="space-y-8 animate-in fade-in duration-500 font-sans">
    
    
    <div class="bg-gradient-to-br from-emerald-900 via-teal-800 to-emerald-900 rounded-[2.5rem] p-8 md:p-10 text-white shadow-2xl shadow-emerald-900/30 relative overflow-hidden flex flex-col items-center text-center">
        <!-- Dekorasi Background -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full overflow-hidden pointer-events-none">
            <div class="absolute top-[-50%] left-[-20%] w-[140%] h-[100%] bg-gradient-to-b from-emerald-500/20 to-transparent rounded-[100%] blur-3xl"></div>
            <i class="ph-fill ph-trophy text-[250px] text-white/5 absolute top-10 left-1/2 -translate-x-1/2"></i>
        </div>

        <!-- Konten -->
        <div class="relative z-10 max-w-lg mx-auto">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-800/50 border border-emerald-500/30 backdrop-blur-md mb-4 shadow-sm">
                <i class="ph-fill ph-sparkle text-amber-400 animate-pulse"></i>
                <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-emerald-100">Fastabiqul Khairat</span>
            </div>
            <h3 class="text-3xl md:text-4xl font-black tracking-tight mb-2 text-transparent bg-clip-text bg-gradient-to-r from-amber-200 via-white to-amber-200 drop-shadow-sm">
                Papan Juara Kebaikan
            </h3>
            <p class="text-emerald-100/80 text-sm leading-relaxed">
                "Dan bagi tiap-tiap umat ada kiblatnya sendiri yang ia menghadap kepadanya. Maka berlomba-lombalah kamu dalam kebaikan." (QS. Al-Baqarah: 148)
            </p>
        </div>
    </div>

    
    <?php if($topRamadanStudents->isNotEmpty()): ?>
    <div class="relative pt-10 pb-6 px-4">
        <div class="grid grid-cols-3 gap-3 items-end max-w-xl mx-auto">
            
            
            <div class="flex flex-col items-center <?php echo e(!isset($topRamadanStudents[1]) ? 'invisible' : ''); ?>">
                <?php if(isset($topRamadanStudents[1])): ?>
                <div class="relative group">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full border-4 border-slate-300 p-1 bg-white shadow-xl relative z-10 group-hover:scale-105 transition-transform duration-300">
                        <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($topRamadanStudents[1]->name)); ?>&background=cbd5e1&color=64748b&bold=true" class="w-full h-full object-cover rounded-full">
                        <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 bg-slate-700 text-white text-[10px] font-black px-2 py-0.5 rounded-md shadow-md border border-slate-600">
                            #2
                        </div>
                    </div>
                </div>
                <div class="bg-white/80 backdrop-blur-sm p-3 rounded-2xl shadow-sm border border-slate-200 mt-4 w-full text-center relative">
                    <div class="w-full h-8 bg-gradient-to-t from-slate-200/50 to-transparent absolute -top-8 left-0 rounded-t-xl -z-10"></div>
                    <p class="text-[10px] font-black text-slate-800 line-clamp-1 capitalize"><?php echo e(strtolower(strtok($topRamadanStudents[1]->name, ' '))); ?></p>
                    <p class="text-[9px] font-bold text-slate-400"><?php echo e(number_format($topRamadanStudents[1]->ramadan_points)); ?> Pts</p>
                </div>
                <?php endif; ?>
            </div>

            
            <div class="flex flex-col items-center -mt-8 relative z-20">
                <?php if(isset($topRamadanStudents[0])): ?>
                <div class="relative group">
                    <i class="ph-fill ph-crown text-amber-400 text-4xl absolute -top-8 left-1/2 -translate-x-1/2 drop-shadow-md animate-bounce-subtle"></i>
                    <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full border-4 border-amber-400 p-1 bg-gradient-to-b from-amber-100 to-white shadow-2xl relative z-10 ring-4 ring-amber-400/20 group-hover:scale-105 transition-transform duration-300">
                        <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($topRamadanStudents[0]->name)); ?>&background=fbbf24&color=78350f&bold=true" class="w-full h-full object-cover rounded-full">
                        <div class="absolute -bottom-3 left-1/2 -translate-x-1/2 bg-amber-500 text-white text-xs font-black px-3 py-0.5 rounded-lg shadow-lg border border-amber-400 flex items-center gap-1">
                            <i class="ph-fill ph-star"></i> #1
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-b from-white to-amber-50 p-4 rounded-3xl shadow-lg border border-amber-200 mt-5 w-full text-center relative min-w-[120px]">
                    <p class="text-xs font-black text-slate-900 line-clamp-1 capitalize mb-0.5"><?php echo e(strtolower(strtok($topRamadanStudents[0]->name, ' '))); ?></p>
                    <div class="inline-block bg-emerald-600 text-white text-[9px] font-bold px-2 py-0.5 rounded-full shadow-sm">
                        <?php echo e(number_format($topRamadanStudents[0]->ramadan_points)); ?> Pts
                    </div>
                </div>
                <?php endif; ?>
            </div>

            
            <div class="flex flex-col items-center <?php echo e(!isset($topRamadanStudents[2]) ? 'invisible' : ''); ?>">
                <?php if(isset($topRamadanStudents[2])): ?>
                <div class="relative group">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full border-4 border-orange-700/40 p-1 bg-white shadow-xl relative z-10 group-hover:scale-105 transition-transform duration-300">
                        <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($topRamadanStudents[2]->name)); ?>&background=d97706&color=ffffff&bold=true" class="w-full h-full object-cover rounded-full">
                        <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 bg-orange-800 text-white text-[10px] font-black px-2 py-0.5 rounded-md shadow-md border border-orange-700">
                            #3
                        </div>
                    </div>
                </div>
                <div class="bg-white/80 backdrop-blur-sm p-3 rounded-2xl shadow-sm border border-slate-200 mt-4 w-full text-center relative">
                    <div class="w-full h-6 bg-gradient-to-t from-slate-200/50 to-transparent absolute -top-6 left-0 rounded-t-xl -z-10"></div>
                    <p class="text-[10px] font-black text-slate-800 line-clamp-1 capitalize"><?php echo e(strtolower(strtok($topRamadanStudents[2]->name, ' '))); ?></p>
                    <p class="text-[9px] font-bold text-slate-400"><?php echo e(number_format($topRamadanStudents[2]->ramadan_points)); ?> Pts</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php else: ?>
        
        <div class="text-center py-16 bg-white rounded-[3rem] border border-dashed border-slate-200 mx-4">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 animate-bounce-subtle">
                <i class="ph-duotone ph-trophy text-4xl text-slate-300"></i>
            </div>
            <h4 class="text-lg font-black text-slate-700">Peringkat Belum Tersedia</h4>
            <p class="text-sm text-slate-400 mt-1 max-w-xs mx-auto">Jadilah yang pertama mengisi jurnal hari ini!</p>
        </div>
    <?php endif; ?>

    
    <?php if($topRamadanStudents->count() > 3): ?>
    <div class="px-2">
        <div class="flex items-center gap-3 mb-4 px-2">
            <div class="h-8 w-1 bg-emerald-500 rounded-full"></div>
            <h3 class="font-bold text-slate-700 text-lg">Top 10 Pejuang</h3>
        </div>

        <div class="space-y-3">
            <?php $__currentLoopData = $topRamadanStudents->slice(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $isMe = Auth::guard('student')->id() == $s->id; ?>
            <div class="flex items-center gap-4 p-4 rounded-2xl border transition-all duration-300
                <?php echo e($isMe 
                    ? 'bg-emerald-900 text-white shadow-lg shadow-emerald-900/20 scale-[1.02] border-emerald-800' 
                    : 'bg-white text-slate-800 hover:bg-slate-50 hover:shadow-md border-slate-100'); ?>">
                
                
                <div class="flex-shrink-0 w-8 text-center">
                    <span class="text-sm font-black <?php echo e($isMe ? 'text-emerald-400' : 'text-slate-300'); ?>">#<?php echo e($index + 4); ?></span>
                </div>

                
                <div class="relative">
                    <div class="w-12 h-12 rounded-full overflow-hidden border-2 <?php echo e($isMe ? 'border-emerald-500' : 'border-slate-100'); ?>">
                        <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($s->name)); ?>&size=100&background=<?php echo e($isMe ? '064e3b' : 'random'); ?>&color=<?php echo e($isMe ? 'fff' : 'fff'); ?>" class="w-full h-full object-cover">
                    </div>
                </div>

                
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <p class="text-sm font-bold truncate capitalize"><?php echo e(strtolower($s->name)); ?></p>
                        <?php if($isMe): ?> 
                            <span class="px-1.5 py-0.5 rounded text-[9px] font-black bg-emerald-500 text-white uppercase tracking-wider">You</span> 
                        <?php endif; ?>
                    </div>
                    <p class="text-[10px] font-bold uppercase tracking-wide <?php echo e($isMe ? 'text-emerald-300' : 'text-slate-400'); ?>">
                        <?php echo e($s->schoolClass->name ?? 'Siswa'); ?>

                    </p>
                </div>

                
                <div class="text-right">
                    <div class="text-base font-black <?php echo e($isMe ? 'text-emerald-400' : 'text-emerald-600'); ?>">
                        <?php echo e(number_format($s->ramadan_points, 0, ',', '.')); ?>

                    </div>
                    <div class="text-[9px] font-bold uppercase <?php echo e($isMe ? 'text-emerald-600' : 'text-slate-400'); ?>">Poin</div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>
</div><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/students/portal/partials/tab-ramadan-leaderboard.blade.php ENDPATH**/ ?>