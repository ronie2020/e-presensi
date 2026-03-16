

<?php $__env->startSection('title', 'Portofolio ' . ($teacher->name ?? 'Guru') . ' - ' . config('app.name', 'SMP Negeri 3 Lakbok')); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
        
        /* Custom Scrollbar */
        .custom-scroll::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 10px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <!-- HEADER PROFIL (Hero Section) -->
    <div class="bg-slate-900 pt-32 pb-24 relative overflow-hidden -mt-24">
        <div class="absolute inset-0 bg-blue-600/10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-20"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-slate-900 via-slate-900/95 to-slate-900"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 animate-enter">
            <div class="flex flex-col md:flex-row items-center md:items-start gap-8">
                <!-- Foto -->
                <div class="w-40 h-40 md:w-48 md:h-48 rounded-full p-2 bg-white/10 backdrop-blur border border-white/20 shadow-2xl shrink-0">
                    <img src="<?php echo e($teacher->photo_path ? asset('storage/' . $teacher->photo_path) : 'https://ui-avatars.com/api/?name='.urlencode($teacher->name).'&background=random'); ?>" 
                         alt="<?php echo e($teacher->name); ?>" 
                         class="w-full h-full rounded-full object-cover">
                </div>

                <!-- Info Utama -->
                <div class="text-center md:text-left flex-1 mt-4 md:mt-0">
                    <?php
                        // Logika decode role (Sama seperti di teachers.blade.php)
                        $displayRole = $teacher->position;
                        if (empty($displayRole)) {
                            $decodedRoles = is_string($teacher->role) ? json_decode($teacher->role, true) : $teacher->role;
                            $displayRole = is_array($decodedRoles) ? implode(', ', $decodedRoles) : $teacher->role;
                        }
                    ?>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 border border-blue-500/30 text-blue-300 text-xs font-bold uppercase tracking-wider mb-4">
                        <i class="ph-fill ph-briefcase"></i> <?php echo e($displayRole ?? 'Tenaga Pendidik'); ?>

                    </div>
                    <h1 class="text-3xl md:text-5xl font-black text-white mb-2 tracking-tight"><?php echo e($teacher->name); ?></h1>
                    <p class="text-slate-400 font-mono text-sm mb-6"><?php echo e($teacher->nip ? 'NIP. ' . $teacher->nip : 'Non-NIP'); ?></p>
                    
                    <p class="text-slate-300 max-w-2xl leading-relaxed text-sm md:text-base italic mb-6">
                        "<?php echo e($teacher->bio ?? 'Terus belajar dan menginspirasi generasi bangsa.'); ?>"
                    </p>

                    <!-- Sosial Media -->
                    <div class="flex items-center justify-center md:justify-start gap-3">
                        <?php if($teacher->instagram): ?>
                        <a href="<?php echo e($teacher->instagram); ?>" target="_blank" class="w-10 h-10 rounded-full bg-white/5 hover:bg-pink-500 hover:text-white flex items-center justify-center text-slate-300 transition-all"><i class="ph-logo ph-instagram-logo text-xl"></i></a>
                        <?php endif; ?>
                        <?php if($teacher->facebook): ?>
                        <a href="<?php echo e($teacher->facebook); ?>" target="_blank" class="w-10 h-10 rounded-full bg-white/5 hover:bg-blue-600 hover:text-white flex items-center justify-center text-slate-300 transition-all"><i class="ph-logo ph-facebook-logo text-xl"></i></a>
                        <?php endif; ?>
                        <?php if($teacher->tiktok): ?>
                        <a href="<?php echo e($teacher->tiktok); ?>" target="_blank" class="w-10 h-10 rounded-full bg-white/5 hover:bg-slate-800 hover:text-white flex items-center justify-center text-slate-300 transition-all"><i class="ph-logo ph-tiktok-logo text-xl"></i></a>
                        <?php endif; ?>
                        <?php if($teacher->phone): ?>
                        <a href="https://wa.me/<?php echo e(preg_replace('/[^0-9]/', '', $teacher->phone)); ?>" target="_blank" class="w-10 h-10 rounded-full bg-white/5 hover:bg-emerald-500 hover:text-white flex items-center justify-center text-slate-300 transition-all"><i class="ph-logo ph-whatsapp-logo text-xl"></i></a>
                        <?php endif; ?>
                    </div>

                    <!-- Keahlian & Hobi (BARU) -->
                    <?php if(!empty($teacher->keahlian) || !empty($teacher->hobi)): ?>
                        <div class="mt-8 flex flex-col md:flex-row gap-6 border-t border-white/10 pt-6 text-left">
                            <?php if(!empty($teacher->keahlian)): ?>
                            <div class="flex-1">
                                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 flex items-center justify-center md:justify-start gap-2">
                                    <i class="ph-bold ph-star text-amber-400"></i> Keahlian Utama
                                </h3>
                                <div class="flex flex-wrap justify-center md:justify-start gap-2">
                                    <?php $__currentLoopData = array_map('trim', explode(',', $teacher->keahlian)); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keahlian): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if(!empty($keahlian)): ?>
                                        <span class="px-3 py-1 bg-white/5 text-slate-300 border border-white/10 rounded-lg text-xs font-medium"><?php echo e($keahlian); ?></span>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if(!empty($teacher->hobi)): ?>
                            <div class="flex-1">
                                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 flex items-center justify-center md:justify-start gap-2">
                                    <i class="ph-bold ph-heart text-rose-400"></i> Minat & Hobi
                                </h3>
                                <div class="flex flex-wrap justify-center md:justify-start gap-2">
                                    <?php $__currentLoopData = array_map('trim', explode(',', $teacher->hobi)); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hobi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if(!empty($hobi)): ?>
                                        <span class="px-3 py-1 bg-white/5 text-slate-300 border border-white/10 rounded-lg text-xs font-medium"><?php echo e($hobi); ?></span>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT DENGAN TABS (ALPINE.JS) -->
    <div x-data="{ activeTab: 'pendidikan' }" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-20 pb-20">
        
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- SIDEBAR NAVIGASI TABS -->
            <div class="lg:w-1/4">
                <div class="bg-white rounded-3xl p-4 shadow-xl shadow-slate-200/50 sticky top-24 border border-slate-100 animate-enter" style="animation-delay: 100ms;">
                    <nav class="flex flex-row lg:flex-col gap-2 overflow-x-auto custom-scroll pb-2 lg:pb-0">
                        <button @click="activeTab = 'pendidikan'" :class="activeTab === 'pendidikan' ? 'bg-cyan-50 text-cyan-600 font-bold border-cyan-200' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800 border-transparent'" class="w-full flex items-center gap-3 px-4 py-3.5 rounded-2xl border transition-all text-sm whitespace-nowrap lg:whitespace-normal text-left">
                            <i class="ph-duotone ph-graduation-cap text-xl" :class="activeTab === 'pendidikan' ? 'text-cyan-500' : ''"></i> Pendidikan Formal
                        </button>
                        <button @click="activeTab = 'pengalaman'" :class="activeTab === 'pengalaman' ? 'bg-blue-50 text-blue-600 font-bold border-blue-200' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800 border-transparent'" class="w-full flex items-center gap-3 px-4 py-3.5 rounded-2xl border transition-all text-sm whitespace-nowrap lg:whitespace-normal text-left">
                            <i class="ph-duotone ph-student text-xl" :class="activeTab === 'pengalaman' ? 'text-blue-500' : ''"></i> Pengalaman & Pelatihan
                        </button>
                        <button @click="activeTab = 'materi'" :class="activeTab === 'materi' ? 'bg-purple-50 text-purple-600 font-bold border-purple-200' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800 border-transparent'" class="w-full flex items-center gap-3 px-4 py-3.5 rounded-2xl border transition-all text-sm whitespace-nowrap lg:whitespace-normal text-left">
                            <i class="ph-duotone ph-book-open-text text-xl" :class="activeTab === 'materi' ? 'text-purple-500' : ''"></i> Materi & Media
                        </button>
                        <button @click="activeTab = 'portofolio'" :class="activeTab === 'portofolio' ? 'bg-emerald-50 text-emerald-600 font-bold border-emerald-200' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800 border-transparent'" class="w-full flex items-center gap-3 px-4 py-3.5 rounded-2xl border transition-all text-sm whitespace-nowrap lg:whitespace-normal text-left">
                            <i class="ph-duotone ph-medal text-xl" :class="activeTab === 'portofolio' ? 'text-emerald-500' : ''"></i> Portofolio Guru
                        </button>
                        <button @click="activeTab = 'artikel'" :class="activeTab === 'artikel' ? 'bg-orange-50 text-orange-600 font-bold border-orange-200' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800 border-transparent'" class="w-full flex items-center gap-3 px-4 py-3.5 rounded-2xl border transition-all text-sm whitespace-nowrap lg:whitespace-normal text-left">
                            <i class="ph-duotone ph-article text-xl" :class="activeTab === 'artikel' ? 'text-orange-500' : ''"></i> Artikel Terpublikasi
                        </button>
                    </nav>

                    <hr class="my-4 border-slate-100 hidden lg:block">
                    
                    <a href="<?php echo e(route('teachers.index')); ?>" class="hidden lg:flex items-center justify-center gap-2 w-full py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-sm transition-colors">
                        <i class="ph-bold ph-arrow-left"></i> Kembali ke Direktori
                    </a>
                </div>
            </div>

            <!-- AREA KONTEN -->
            <div class="lg:w-3/4 animate-enter" style="animation-delay: 200ms;">
                
                <!-- TAB PENDIDIKAN -->
                <div x-show="activeTab === 'pendidikan'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100 min-h-[400px]">
                    <h2 class="text-2xl font-black text-slate-800 mb-6 flex items-center gap-3">
                        <span class="p-2 bg-cyan-100 text-cyan-600 rounded-lg"><i class="ph-bold ph-graduation-cap"></i></span> Riwayat Pendidikan Formal
                    </h2>
                    
                    <div class="relative border-l-2 border-slate-100 ml-4 space-y-8 pb-4">
                        <?php $__empty_1 = true; $__currentLoopData = $teacher->educations ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $edu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="relative pl-6">
                                <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-cyan-500 border-4 border-white shadow"></div>
                                <span class="text-xs font-bold text-cyan-500 mb-1 block"><?php echo e($edu->start_year ?? '-'); ?> - <?php echo e($edu->end_year ?? 'Sekarang'); ?></span>
                                <h3 class="text-lg font-bold text-slate-800"><?php echo e($edu->institution); ?></h3>
                                <p class="text-slate-500 text-sm mt-1"><?php echo e($edu->degree); ?></p>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="py-10 text-center text-slate-400">
                                <i class="ph-duotone ph-graduation-cap text-5xl mb-3 opacity-50"></i>
                                <p>Belum ada data pendidikan yang ditambahkan.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- TAB 1: PENGALAMAN & PELATIHAN -->
                <div x-show="activeTab === 'pengalaman'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100 min-h-[400px]">
                    <h2 class="text-2xl font-black text-slate-800 mb-6 flex items-center gap-3">
                        <span class="p-2 bg-blue-100 text-blue-600 rounded-lg"><i class="ph-bold ph-student"></i></span> Riwayat Pelatihan & Sertifikasi
                    </h2>
                    
                    <div class="relative border-l-2 border-slate-100 ml-4 space-y-8 pb-4">
                        <?php $__empty_1 = true; $__currentLoopData = $experiences; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="relative pl-6">
                                <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-blue-500 border-4 border-white shadow"></div>
                                <span class="text-xs font-bold text-blue-500 mb-1 block">Tahun <?php echo e($exp->year ?? 'N/A'); ?></span>
                                <h3 class="text-lg font-bold text-slate-800"><?php echo e($exp->title); ?></h3>
                                <p class="text-slate-500 text-sm mt-1"><?php echo e($exp->organizer); ?></p>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="py-10 text-center text-slate-400">
                                <i class="ph-duotone ph-folder-open text-5xl mb-3 opacity-50"></i>
                                <p>Belum ada data pelatihan yang ditambahkan.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- TAB 2: MATERI & MEDIA -->
                <div x-show="activeTab === 'materi'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100 min-h-[400px]">
                    <h2 class="text-2xl font-black text-slate-800 mb-6 flex items-center gap-3">
                        <span class="p-2 bg-purple-100 text-purple-600 rounded-lg"><i class="ph-bold ph-presentation-chart"></i></span> Materi & Media Pembelajaran
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php $__empty_1 = true; $__currentLoopData = $materials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $material): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <a href="<?php echo e($material->file_url ?? '#'); ?>" target="_blank" class="group block p-4 border border-slate-200 rounded-2xl hover:border-purple-300 hover:shadow-lg hover:shadow-purple-100 transition-all bg-slate-50/50">
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 shrink-0 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                                        <i class="ph-fill <?php echo e($material->icon ?? 'ph-file-text'); ?>"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-slate-800 group-hover:text-purple-600 transition-colors line-clamp-2"><?php echo e($material->title); ?></h4>
                                        <p class="text-xs text-slate-500 mt-1"><?php echo e($material->type ?? 'Dokumen'); ?></p>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="col-span-1 md:col-span-2 py-10 text-center text-slate-400">
                                <i class="ph-duotone ph-folder-open text-5xl mb-3 opacity-50"></i>
                                <p>Belum ada data materi yang dibagikan.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- TAB 3: PORTOFOLIO -->
                <div x-show="activeTab === 'portofolio'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100 min-h-[400px]">
                    <h2 class="text-2xl font-black text-slate-800 mb-6 flex items-center gap-3">
                        <span class="p-2 bg-emerald-100 text-emerald-600 rounded-lg"><i class="ph-bold ph-medal"></i></span> Portofolio & Pencapaian
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <?php $__empty_1 = true; $__currentLoopData = $portfolios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $portfolio): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="group rounded-2xl overflow-hidden border border-slate-200 relative">
                                <div class="aspect-video bg-slate-200">
                                    <img src="<?php echo e(asset('storage/' . $portfolio->image_path)); ?>" alt="Portofolio" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                </div>
                                <div class="p-4 bg-white">
                                    <h4 class="font-bold text-slate-800 mb-1"><?php echo e($portfolio->title); ?></h4>
                                    <p class="text-xs text-slate-500">Tahun <?php echo e($portfolio->year); ?></p>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="col-span-1 sm:col-span-2 py-10 text-center text-slate-400">
                                <i class="ph-duotone ph-folder-open text-5xl mb-3 opacity-50"></i>
                                <p>Belum ada portofolio yang ditambahkan.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- TAB 4: ARTIKEL -->
                <div x-show="activeTab === 'artikel'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100 min-h-[400px]">
                    <h2 class="text-2xl font-black text-slate-800 mb-6 flex items-center gap-3">
                        <span class="p-2 bg-orange-100 text-orange-600 rounded-lg"><i class="ph-bold ph-article"></i></span> Artikel & Opini
                    </h2>

                    <div class="space-y-4">
                        <?php $__empty_1 = true; $__currentLoopData = $articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <a href="<?php echo e($article->url ?? '#'); ?>" class="flex flex-col sm:flex-row gap-4 p-4 rounded-2xl border border-slate-100 hover:bg-slate-50 hover:border-slate-200 transition-all group">
                                <?php if(isset($article->image_path) && $article->image_path): ?>
                                <div class="sm:w-32 aspect-video sm:aspect-square rounded-xl bg-slate-200 overflow-hidden shrink-0">
                                    <img src="<?php echo e(asset('storage/' . $article->image_path)); ?>" alt="Thumbnail" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                </div>
                                <?php endif; ?>
                                <div class="flex flex-col justify-center">
                                    <span class="text-xs font-bold text-orange-500 mb-1"><?php echo e($article->category ?? 'Pendidikan'); ?></span>
                                    <h4 class="text-lg font-bold text-slate-800 group-hover:text-blue-600 transition-colors"><?php echo e($article->title); ?></h4>
                                    <p class="text-sm text-slate-500 mt-2 line-clamp-2"><?php echo e($article->excerpt); ?></p>
                                    <span class="text-xs text-slate-400 mt-3"><i class="ph-regular ph-calendar-blank"></i> <?php echo e(\Carbon\Carbon::parse($article->published_at)->format('d F Y')); ?></span>
                                </div>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="py-10 text-center text-slate-400">
                                <i class="ph-duotone ph-folder-open text-5xl mb-3 opacity-50"></i>
                                <p>Belum ada artikel yang dipublikasikan.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/teacher-detail.blade.php ENDPATH**/ ?>