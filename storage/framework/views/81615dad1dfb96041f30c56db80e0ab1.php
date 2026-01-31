<div x-data="{ activeTab: 'assignments' }" class="space-y-8 animate-in fade-in duration-500 font-sans">

    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        
        <?php
            $totalAssign = 0;
            $submittedCount = count($lms_grades ?? []);
            foreach($lms_assignments_grouped as $group) { $totalAssign += $group->count(); }
            
            $pendingCount = $totalAssign - $submittedCount;
            $avgScore = count($lms_grades) > 0 ? round(array_sum($lms_grades) / count($lms_grades)) : 0;
        ?>

        
        <div class="bg-gradient-to-br from-indigo-600 to-blue-700 rounded-[2rem] p-6 text-white shadow-xl shadow-blue-500/20 relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
                <i class="ph-fill ph-clipboard-text text-8xl"></i>
            </div>
            <div class="relative z-10">
                <p class="text-xs font-bold text-blue-200 uppercase tracking-wider mb-1">Tugas Pending</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-4xl font-black"><?php echo e($pendingCount); ?></h3>
                    <span class="text-sm font-medium opacity-80">Belum Selesai</span>
                </div>
                <?php if($pendingCount > 0): ?>
                    <div class="mt-4 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white/20 backdrop-blur-sm text-xs font-bold border border-white/10">
                        <i class="ph-bold ph-clock-countdown text-yellow-300"></i> Segera Kerjakan
                    </div>
                <?php else: ?>
                    <div class="mt-4 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-500/20 backdrop-blur-sm text-xs font-bold border border-emerald-400/30 text-emerald-100">
                        <i class="ph-bold ph-check-circle"></i> Semua Beres!
                    </div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm relative overflow-hidden group hover:border-emerald-200 transition-colors">
            <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <i class="ph-duotone ph-exam text-8xl text-emerald-600"></i>
            </div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Rata-rata Nilai</p>
            <div class="flex items-baseline gap-2">
                <h3 class="text-4xl font-black text-slate-800"><?php echo e($avgScore); ?></h3>
                <span class="text-xs font-bold px-2 py-1 rounded-md <?php echo e($avgScore >= 80 ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700'); ?>">
                    <?php echo e($avgScore >= 90 ? 'A' : ($avgScore >= 80 ? 'B' : 'C')); ?>

                </span>
            </div>
            <p class="text-xs text-slate-400 mt-3 font-medium">Dari <?php echo e($submittedCount); ?> tugas yang dinilai.</p>
        </div>

        
        <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm relative overflow-hidden group hover:border-purple-200 transition-colors">
            <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <i class="ph-duotone ph-books text-8xl text-purple-600"></i>
            </div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Total Materi</p>
            <?php $totalMateri = 0; foreach($lms_materials_grouped as $g) { $totalMateri += $g->count(); } ?>
            <div class="flex items-baseline gap-2">
                <h3 class="text-4xl font-black text-slate-800"><?php echo e($totalMateri); ?></h3>
                <span class="text-sm font-medium text-slate-400">Modul</span>
            </div>
            <p class="text-xs text-slate-400 mt-3 font-medium">Siap untuk dipelajari.</p>
        </div>
    </div>

    
    <div class="flex justify-center">
        <div class="bg-slate-100 p-1.5 rounded-2xl inline-flex relative">
            
            <button @click="activeTab = 'assignments'" 
                class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 flex items-center gap-2 relative z-10"
                :class="activeTab === 'assignments' ? 'bg-white text-blue-600 shadow-md scale-105' : 'text-slate-500 hover:text-slate-700'">
                <i class="ph-bold ph-pencil-circle"></i> Tugas Sekolah
            </button>
            <button @click="activeTab = 'materials'" 
                class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 flex items-center gap-2 relative z-10"
                :class="activeTab === 'materials' ? 'bg-white text-purple-600 shadow-md scale-105' : 'text-slate-500 hover:text-slate-700'">
                <i class="ph-bold ph-book-bookmark"></i> Materi Belajar
            </button>
        </div>
    </div>

    
    
    
    <div x-show="activeTab === 'assignments'" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0">
        
        <?php if(count($lms_assignments_grouped) > 0): ?>
            <div class="space-y-8">
                <?php $__currentLoopData = $lms_assignments_grouped; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subjectName => $assignments): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
                        
                        <div class="bg-slate-50/50 px-8 py-4 border-b border-slate-100 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-blue-600 shadow-sm">
                                <i class="ph-duotone ph-book-open-text text-xl"></i>
                            </div>
                            <h3 class="text-lg font-black text-slate-800"><?php echo e($subjectName); ?></h3>
                        </div>

                        <div class="divide-y divide-slate-50">
                            <?php $__currentLoopData = $assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $score = $lms_grades[$assignment->id] ?? null;
                                    $isGraded = !is_null($score);
                                    // Dummy status check (logic sesuaikan dengan sistem submission Anda)
                                    $isSubmitted = $isGraded; 
                                ?>

                                <div class="p-6 sm:p-8 hover:bg-slate-50 transition-colors group relative">
                                    <?php if(!$isSubmitted): ?>
                                        <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-amber-400 group-hover:w-2 transition-all"></div>
                                    <?php else: ?>
                                        <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-emerald-500 group-hover:w-2 transition-all"></div>
                                    <?php endif; ?>

                                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pl-4">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-2">
                                                <?php if($isGraded): ?>
                                                    <span class="px-2.5 py-1 rounded-lg bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase tracking-wide border border-emerald-200 flex items-center gap-1">
                                                        <i class="ph-bold ph-check-circle"></i> Dinilai
                                                    </span>
                                                <?php else: ?>
                                                    <span class="px-2.5 py-1 rounded-lg bg-amber-100 text-amber-700 text-[10px] font-black uppercase tracking-wide border border-amber-200 flex items-center gap-1 animate-pulse">
                                                        <i class="ph-bold ph-clock"></i> Pending
                                                    </span>
                                                <?php endif; ?>
                                                <span class="text-xs font-bold text-slate-400 flex items-center gap-1">
                                                    <i class="ph-bold ph-calendar-blank"></i> <?php echo e(\Carbon\Carbon::parse($assignment->due_date)->format('d M Y')); ?>

                                                </span>
                                            </div>
                                            <h4 class="text-lg font-black text-slate-800 group-hover:text-blue-600 transition-colors mb-2">
                                                <?php echo e($assignment->title); ?>

                                            </h4>
                                            <p class="text-sm text-slate-500 line-clamp-2 leading-relaxed max-w-2xl">
                                                <?php echo e($assignment->description ?? 'Tidak ada deskripsi tambahan.'); ?>

                                            </p>
                                        </div>

                                        <div class="flex items-center gap-4 shrink-0">
                                            <?php if($isGraded): ?>
                                                <div class="text-center bg-white p-3 rounded-2xl border border-slate-200 shadow-sm min-w-[80px]">
                                                    <span class="block text-[10px] font-bold text-slate-400 uppercase">Nilai</span>
                                                    <span class="text-3xl font-black text-emerald-600 tracking-tight"><?php echo e($score); ?></span>
                                                </div>
                                            <?php else: ?>
                                                <a href="#" class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-lg shadow-blue-600/20 transition-all flex items-center gap-2">
                                                    Kerjakan <i class="ph-bold ph-arrow-right"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            
            <div class="text-center py-20 bg-white rounded-[3rem] border-2 border-dashed border-slate-200">
                <div class="w-24 h-24 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="ph-duotone ph-confetti text-5xl text-emerald-400"></i>
                </div>
                <h3 class="text-xl font-black text-slate-800">Tidak Ada Tugas Aktif</h3>
                <p class="text-slate-500 text-sm mt-2 max-w-sm mx-auto">
                    Hebat! Kamu sudah menyelesaikan semua tugas. Gunakan waktu luang untuk membaca materi.
                </p>
            </div>
        <?php endif; ?>
    </div>

    
    <div x-show="activeTab === 'materials'" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         style="display: none;">
        
        <?php if(count($lms_materials_grouped) > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php $__currentLoopData = $lms_materials_grouped; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subjectName => $materials): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden h-full">
                        <div class="bg-slate-50/50 px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                            <h3 class="font-black text-slate-800 text-sm"><?php echo e($subjectName); ?></h3>
                            <span class="text-[10px] font-bold bg-white border border-slate-200 px-2 py-1 rounded-lg text-slate-500">
                                <?php echo e(count($materials)); ?> File
                            </span>
                        </div>
                        
                        <div class="p-4 space-y-3">
                            <?php $__currentLoopData = $materials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $material): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    // Deteksi Icon berdasarkan tipe file (dummy logic)
                                    $fileType = 'file';
                                    $icon = 'ph-file-text';
                                    $color = 'slate';
                                    
                                    $titleLower = strtolower($material->title);
                                    if(str_contains($titleLower, 'video') || str_contains($titleLower, 'mp4')) {
                                        $icon = 'ph-play-circle'; $color = 'rose';
                                    } elseif(str_contains($titleLower, 'ppt') || str_contains($titleLower, 'presentasi')) {
                                        $icon = 'ph-presentation-chart'; $color = 'orange';
                                    } elseif(str_contains($titleLower, 'pdf')) {
                                        $icon = 'ph-file-pdf'; $color = 'rose';
                                    }
                                ?>

                                <div class="flex items-start gap-3 p-3 rounded-2xl border border-slate-100 hover:border-purple-200 hover:bg-purple-50/30 transition-all group cursor-pointer">
                                    <div class="w-10 h-10 rounded-xl bg-<?php echo e($color); ?>-50 text-<?php echo e($color); ?>-500 flex items-center justify-center shrink-0 border border-<?php echo e($color); ?>-100 group-hover:scale-110 transition-transform">
                                        <i class="ph-duotone <?php echo e($icon); ?> text-xl"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-bold text-slate-700 truncate group-hover:text-purple-700 transition-colors">
                                            <?php echo e($material->title); ?>

                                        </h4>
                                        <p class="text-[10px] text-slate-400 mt-0.5">
                                            Diposting: <?php echo e(\Carbon\Carbon::parse($material->created_at)->format('d M')); ?>

                                        </p>
                                    </div>
                                    <button class="w-8 h-8 rounded-full bg-white border border-slate-200 text-slate-400 flex items-center justify-center hover:bg-purple-600 hover:text-white hover:border-purple-600 transition-all shadow-sm">
                                        <i class="ph-bold ph-download-simple"></i>
                                    </button>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            
            <div class="text-center py-20 bg-white rounded-[3rem] border-2 border-dashed border-slate-200">
                <div class="w-24 h-24 bg-purple-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="ph-duotone ph-files text-5xl text-purple-400"></i>
                </div>
                <h3 class="text-xl font-black text-slate-800">Belum Ada Materi</h3>
                <p class="text-slate-500 text-sm mt-2 max-w-sm mx-auto">
                    Guru belum mengunggah materi pelajaran baru.
                </p>
            </div>
        <?php endif; ?>
    </div>

</div><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/students/portal/partials/tab-lms.blade.php ENDPATH**/ ?>