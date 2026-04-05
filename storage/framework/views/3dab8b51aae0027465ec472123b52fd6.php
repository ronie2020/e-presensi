

<?php $__env->startSection('title', 'Portofolio ' . ($teacher->name ?? 'Guru') . ' - ' . config('app.name', 'SMP Negeri 3 Lakbok')); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        /* Mencegah tab berkedip saat halaman baru dimuat (Wajib untuk AlpineJS) */
        [x-cloak] { display: none !important; }
        
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
                    <img src="<?php echo e(!empty($teacher->photo_path) ? asset('storage/' . $teacher->photo_path) : 'https://ui-avatars.com/api/?name='.urlencode($teacher->name ?? 'Guru').'&background=random'); ?>" 
                         alt="<?php echo e($teacher->name ?? 'Foto Guru'); ?>" 
                         class="w-full h-full rounded-full object-cover bg-slate-800">
                </div>

                <!-- Info Utama -->
                <div class="text-center md:text-left flex-1 mt-4 md:mt-0">
                    <?php
                        // Logika decode role (Sama seperti di teachers.blade.php)
                        $displayRole = $teacher->position ?? null;
                        if (empty($displayRole) && isset($teacher->role)) {
                            $decodedRoles = is_string($teacher->role) ? json_decode($teacher->role, true) : $teacher->role;
                            $displayRole = is_array($decodedRoles) ? implode(', ', $decodedRoles) : $teacher->role;
                        }
                    ?>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 border border-blue-500/30 text-blue-300 text-xs font-bold uppercase tracking-wider mb-4">
                        <i class="ph-fill ph-briefcase"></i> <?php echo e($displayRole ?: 'Tenaga Pendidik'); ?>

                    </div>
                    <h1 class="text-3xl md:text-5xl font-black text-white mb-2 tracking-tight"><?php echo e($teacher->name ?? 'Nama Tidak Diketahui'); ?></h1>
                    <p class="text-slate-400 font-mono text-sm mb-6"><?php echo e(!empty($teacher->nip) ? 'NIP. ' . $teacher->nip : 'Non-NIP'); ?></p>
                    
                    <p class="text-slate-300 max-w-2xl leading-relaxed text-sm md:text-base italic mb-6">
                        "<?php echo e($teacher->bio ?? 'Terus belajar dan menginspirasi generasi bangsa.'); ?>"
                    </p>

                   <!-- Sosial Media (Opsi 2: Kotak Glow) -->
                    <div class="flex items-center justify-center md:justify-start gap-4">
                        <?php if(!empty($teacher->instagram)): ?>
                        <a href="<?php echo e($teacher->instagram); ?>" target="_blank" class="w-11 h-11 rounded-xl bg-pink-500/10 border border-pink-500/20 text-pink-400 hover:bg-gradient-to-tr hover:from-pink-500 hover:to-purple-500 hover:text-white flex items-center justify-center transition-all duration-300 hover:shadow-[0_0_15px_rgba(236,72,153,0.4)] hover:-translate-y-1">
                            <i class="ph ph-instagram-logo text-2xl"></i>
                        </a>
                        <?php endif; ?>

                        <?php if(!empty($teacher->facebook)): ?>
                        <a href="<?php echo e($teacher->facebook); ?>" target="_blank" class="w-11 h-11 rounded-xl bg-blue-500/10 border border-blue-500/20 text-blue-400 hover:bg-blue-600 hover:text-white flex items-center justify-center transition-all duration-300 hover:shadow-[0_0_15px_rgba(37,99,235,0.4)] hover:-translate-y-1">
                            <i class="ph ph-facebook-logo text-2xl"></i>
                        </a>
                        <?php endif; ?>

                        <?php if(!empty($teacher->tiktok)): ?>
                        <a href="<?php echo e($teacher->tiktok); ?>" target="_blank" class="w-11 h-11 rounded-xl bg-slate-700/30 border border-slate-600 text-slate-300 hover:bg-slate-900 hover:text-white flex items-center justify-center transition-all duration-300 hover:shadow-[0_0_15px_rgba(15,23,42,0.4)] hover:-translate-y-1">
                            <i class="ph ph-tiktok-logo text-2xl"></i>
                        </a>
                        <?php endif; ?>

                        <?php if(!empty($teacher->phone)): ?>
                        <a href="https://wa.me/<?php echo e(preg_replace('/[^0-9]/', '', $teacher->phone)); ?>" target="_blank" class="w-11 h-11 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 hover:bg-emerald-500 hover:text-white flex items-center justify-center transition-all duration-300 hover:shadow-[0_0_15px_rgba(16,185,129,0.4)] hover:-translate-y-1">
                            <i class="ph ph-whatsapp-logo text-2xl"></i>
                        </a>
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
                                    
                                    <?php $__currentLoopData = array_map('trim', explode(',', $teacher->keahlian ?? '')); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keahlian): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                                    
                                    <?php $__currentLoopData = array_map('trim', explode(',', $teacher->hobi ?? '')); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hobi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                                <h3 class="text-lg font-bold text-slate-800"><?php echo e($edu->institution ?? 'Institusi Tidak Diketahui'); ?></h3>
                                <p class="text-slate-500 text-sm mt-1"><?php echo e($edu->degree ?? '-'); ?></p>
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
               <div x-cloak x-show="activeTab === 'pengalaman'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100 min-h-[400px]">
                    <h2 class="text-2xl font-black text-slate-800 mb-6 flex items-center gap-3">
                        <span class="p-2 bg-blue-100 text-blue-600 rounded-lg"><i class="ph-bold ph-student"></i></span> Riwayat Pelatihan & Sertifikasi
                    </h2>
                    
                    <div class="relative border-l-2 border-slate-100 ml-4 space-y-8 pb-4">
                        
                        <?php $__empty_1 = true; $__currentLoopData = $experiences ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="relative pl-6">
                                <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-blue-500 border-4 border-white shadow"></div>
                                <span class="text-xs font-bold text-blue-500 mb-1 block">Tahun <?php echo e($exp->year ?? 'N/A'); ?></span>
                                <h3 class="text-lg font-bold text-slate-800"><?php echo e($exp->title ?? 'Tanpa Judul'); ?></h3>
                                <p class="text-slate-500 text-sm mt-1 mb-2"><?php echo e($exp->organizer ?? '-'); ?></p>
                                
                                
                                <?php if(!empty($exp->certificate_path)): ?>
                                    <a href="<?php echo e(asset('storage/' . $exp->certificate_path)); ?>" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-lg text-xs font-bold transition-colors">
                                        <i class="ph-bold ph-certificate"></i> Lihat Sertifikat
                                    </a>
                                <?php endif; ?>
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
                <div x-cloak x-show="activeTab === 'materi'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100 min-h-[400px]">
                    <h2 class="text-2xl font-black text-slate-800 mb-6 flex items-center gap-3">
                        <span class="p-2 bg-purple-100 text-purple-600 rounded-lg"><i class="ph-bold ph-presentation-chart"></i></span> Materi & Media Pembelajaran
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        
                        <?php $__empty_1 = true; $__currentLoopData = $materials ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $material): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <a href="<?php echo e(!empty($material->file_url) ? $material->file_url : '#'); ?>" target="_blank" class="group block p-4 border border-slate-200 rounded-2xl hover:border-purple-300 hover:shadow-lg hover:shadow-purple-100 transition-all bg-slate-50/50">
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 shrink-0 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                                        <i class="ph-fill <?php echo e($material->icon ?? 'ph-file-text'); ?>"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-slate-800 group-hover:text-purple-600 transition-colors line-clamp-2"><?php echo e($material->title ?? 'Dokumen'); ?></h4>
                                        <p class="text-xs text-slate-500 mt-1"><?php echo e($material->type ?? 'Materi'); ?></p>
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
                <div x-cloak x-show="activeTab === 'portofolio'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100 min-h-[400px]">
                    <h2 class="text-2xl font-black text-slate-800 mb-6 flex items-center gap-3">
                        <span class="p-2 bg-emerald-100 text-emerald-600 rounded-lg"><i class="ph-bold ph-medal"></i></span> Portofolio & Pencapaian
                    </h2>

                    
                    <div x-data="{ galleryOpen: false, galleryImages: [], currentIdx: 0, galleryTitle: '' }">
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            <?php $__empty_1 = true; $__currentLoopData = $portfolios ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $portfolio): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php
                                    // Parsing Array Foto
                                    $images = is_string($portfolio->image_path) ? json_decode($portfolio->image_path, true) : $portfolio->image_path;
                                    if (!is_array($images)) {
                                        $images = $portfolio->image_path ? [$portfolio->image_path] : [];
                                    }

                                    // Buat array Full URL khusus untuk dikirim ke Javascript
                                    $imageUrls = [];
                                    foreach($images as $img) {
                                        $imageUrls[] = asset('storage/' . $img);
                                    }
                                ?>
                                
                                
                                <div @click="if(<?php echo e(count($imageUrls)); ?> > 0) { galleryOpen = true; galleryImages = <?php echo \Illuminate\Support\Js::from($imageUrls)->toHtml() ?>; currentIdx = 0; galleryTitle = '<?php echo e(addslashes($portfolio->title ?? 'Portofolio')); ?>' }" 
                                     class="group rounded-2xl overflow-hidden border border-slate-200 relative cursor-pointer hover:shadow-xl hover:-translate-y-1 hover:border-emerald-300 transition-all duration-300 bg-white">
                                    
                                    <div class="aspect-video bg-slate-100 flex items-center justify-center relative">
                                        <?php if(count($images) > 0): ?>
                                            <img src="<?php echo e(asset('storage/' . $images[0])); ?>" alt="Portofolio" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                            
                                            <?php if(count($images) > 1): ?>
                                                <div class="absolute top-3 right-3 bg-black/60 backdrop-blur-md text-white text-[10px] font-black px-2.5 py-1 rounded-lg flex items-center gap-1 shadow-sm">
                                                    <i class="ph-bold ph-images"></i> +<?php echo e(count($images) - 1); ?> Foto
                                                </div>
                                            <?php endif; ?>

                                            
                                            <div class="absolute inset-0 bg-emerald-900/30 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center backdrop-blur-[2px]">
                                                <span class="bg-white/95 text-emerald-800 px-4 py-2 rounded-xl text-sm font-black flex items-center gap-2 shadow-lg transform translate-y-4 group-hover:translate-y-0 transition-all duration-300">
                                                    <i class="ph-bold ph-magnifying-glass-plus text-lg"></i> Lihat Galeri
                                                </span>
                                            </div>
                                        <?php else: ?>
                                            <i class="ph-duotone ph-image text-slate-300 text-3xl"></i>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="p-4 relative z-10 border-t border-slate-100">
                                        <h4 class="font-bold text-slate-800 mb-1 group-hover:text-emerald-600 transition-colors line-clamp-1"><?php echo e($portfolio->title ?? 'Portofolio'); ?></h4>
                                        <p class="text-xs font-medium text-slate-500">Tahun <?php echo e($portfolio->year ?? '-'); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="col-span-1 sm:col-span-2 lg:col-span-3 py-10 text-center text-slate-400 border border-dashed border-slate-200 rounded-3xl bg-slate-50">
                                    <i class="ph-duotone ph-folder-open text-5xl mb-3 opacity-50"></i>
                                    <p>Belum ada portofolio / galeri yang ditambahkan.</p>
                                </div>
                            <?php endif; ?>
                        </div>

                        
                        <div x-show="galleryOpen" x-cloak 
                             class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6 bg-slate-900/95 backdrop-blur-md"
                             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                            
                            
                            <button @click="galleryOpen = false" class="absolute top-4 right-4 text-white hover:text-rose-400 bg-white/10 hover:bg-white/20 p-3 rounded-full transition-colors z-[110]">
                                <i class="ph-bold ph-x text-xl"></i>
                            </button>

                            <div @click.away="galleryOpen = false" class="relative w-full max-w-5xl flex flex-col items-center justify-center h-full">
                                <!-- Judul Galeri -->
                                <div class="text-center mb-6 mt-12 md:mt-0 px-4">
                                    <h3 class="text-xl md:text-2xl font-black text-white drop-shadow-md" x-text="galleryTitle"></h3>
                                    <p class="text-xs font-bold text-emerald-300 mt-2 bg-emerald-500/20 border border-emerald-500/30 px-3 py-1 rounded-full inline-block">
                                        Foto <span x-text="currentIdx + 1"></span> dari <span x-text="galleryImages.length"></span>
                                    </p>
                                </div>

                                <!-- Area Foto Utama -->
                                <div class="relative w-full h-[50vh] md:h-[65vh] flex items-center justify-center mb-6 rounded-3xl overflow-hidden shadow-2xl bg-black/50 border border-white/10 group">
                                    <template x-if="galleryImages.length > 0">
                                        <img :src="galleryImages[currentIdx]" class="max-w-full max-h-full object-contain transition-all duration-300" 
                                             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                                    </template>
                                    
                                    <!-- Navigasi -->
                                    <button x-show="galleryImages.length > 1" @click.stop="currentIdx = currentIdx === 0 ? galleryImages.length - 1 : currentIdx - 1" class="absolute left-2 md:left-6 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/10 border border-white/20 hover:bg-emerald-600 text-white rounded-full flex items-center justify-center transition-all shadow-lg backdrop-blur-md opacity-80 hover:opacity-100 md:opacity-0 md:group-hover:opacity-100"><i class="ph-bold ph-caret-left text-2xl"></i></button>
                                    <button x-show="galleryImages.length > 1" @click.stop="currentIdx = currentIdx === galleryImages.length - 1 ? 0 : currentIdx + 1" class="absolute right-2 md:right-6 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/10 border border-white/20 hover:bg-emerald-600 text-white rounded-full flex items-center justify-center transition-all shadow-lg backdrop-blur-md opacity-80 hover:opacity-100 md:opacity-0 md:group-hover:opacity-100"><i class="ph-bold ph-caret-right text-2xl"></i></button>
                                </div>

                                <!-- Thumbnail -->
                                <div class="flex gap-3 overflow-x-auto max-w-full custom-scroll py-2 px-2" x-show="galleryImages.length > 1">
                                    <template x-for="(img, index) in galleryImages">
                                        <button @click.stop="currentIdx = index" class="shrink-0 w-16 h-16 md:w-20 md:h-20 rounded-xl overflow-hidden border-2 transition-all duration-300 ease-out" :class="currentIdx === index ? 'border-amber-400 opacity-100 scale-110 shadow-lg shadow-amber-500/40' : 'border-white/10 opacity-40 hover:opacity-100 hover:border-white/40'">
                                            <img :src="img" class="w-full h-full object-cover">
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>

                <!-- TAB 4: ARTIKEL -->
                <div x-cloak x-show="activeTab === 'artikel'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100 min-h-[400px]">
                    <h2 class="text-2xl font-black text-slate-800 mb-6 flex items-center gap-3">
                        <span class="p-2 bg-orange-100 text-orange-600 rounded-lg"><i class="ph-bold ph-article"></i></span> Artikel & Opini
                    </h2>

                    <div class="space-y-4">
                        
                        <?php $__empty_1 = true; $__currentLoopData = $articles ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <a href="<?php echo e(!empty($article->url) ? $article->url : '#'); ?>" class="flex flex-col sm:flex-row gap-4 p-4 rounded-2xl border border-slate-100 hover:bg-slate-50 hover:border-slate-200 transition-all group">
                                <?php if(!empty($article->image_path)): ?>
                                <div class="sm:w-32 aspect-video sm:aspect-square rounded-xl bg-slate-200 overflow-hidden shrink-0">
                                    <img src="<?php echo e(asset('storage/' . $article->image_path)); ?>" alt="Thumbnail" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                </div>
                                <?php endif; ?>
                                <div class="flex flex-col justify-center">
                                    <span class="text-xs font-bold text-orange-500 mb-1"><?php echo e($article->category ?? 'Pendidikan'); ?></span>
                                    <h4 class="text-lg font-bold text-slate-800 group-hover:text-blue-600 transition-colors"><?php echo e($article->title ?? 'Tanpa Judul'); ?></h4>
                                    <p class="text-sm text-slate-500 mt-2 line-clamp-2"><?php echo e($article->excerpt ?? ''); ?></p>
                                    <span class="text-xs text-slate-400 mt-3">
                                        <i class="ph-regular ph-calendar-blank"></i> 
                                        
                                        <?php echo e(!empty($article->published_at) ? \Carbon\Carbon::parse($article->published_at)->translatedFormat('d F Y') : 'Belum dipublikasi'); ?>

                                    </span>
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