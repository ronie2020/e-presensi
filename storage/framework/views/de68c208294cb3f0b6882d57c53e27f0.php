  <!-- ANNOUNCEMENTS (Bottom) & FOOTER SECTION -->
    <div class="bg-slate-900 text-white pt-24 pb-12 relative overflow-hidden">
        
        <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-slate-700 to-transparent"></div>
        <div class="absolute -right-20 top-20 w-96 h-96 bg-blue-600 rounded-full mix-blend-overlay filter blur-[100px] opacity-20"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- PENGUMUMAN -->
            <div class="mb-24">
                <div class="flex justify-between items-end mb-10">
                    <div>
                        <h2 class="text-2xl font-bold text-white mb-2">Papan Pengumuman</h2>
                        <p class="text-slate-400 text-sm">Informasi terbaru seputar kegiatan sekolah.</p>
                    </div>
                </div>
                
                <div class="grid gap-6 md:grid-cols-3">
                    <?php $__empty_1 = true; $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <article class="bg-slate-800/50 backdrop-blur-md rounded-2xl p-6 border border-slate-700/50 hover:border-blue-500/50 transition-all duration-300 hover:bg-slate-800 hover:-translate-y-1 group h-full flex flex-col cursor-pointer" @click="openAnnouncementByIndex(<?php echo e($index); ?>)" data-aos="fade-up" data-aos-delay="<?php echo e($loop->index * 100); ?>">
                            <div class="flex justify-between items-start mb-4">
                                <span class="px-2 py-1 rounded bg-blue-500/10 text-blue-400 text-[10px] font-bold uppercase tracking-wide border border-blue-500/20">Info</span>
                                <span class="text-xs text-slate-500 font-medium flex items-center gap-1">
                                    <i class="ph-fill ph-calendar-blank"></i> <?php echo e($item->created_at->format('d M')); ?>

                                </span>
                            </div>
                            <h3 class="text-lg font-bold text-white mb-3 line-clamp-2 group-hover:text-blue-400 transition-colors"><?php echo e($item->title); ?></h3>
                            <p class="text-slate-400 text-sm line-clamp-3 mb-4 flex-1 leading-relaxed"><?php echo e(Str::limit(strip_tags($item->content), 100)); ?></p>
                            <div class="flex items-center text-sm text-blue-400 font-semibold mt-auto gap-1 group-hover:gap-2 transition-all">
                                Baca Selengkapnya <i class="ph-bold ph-arrow-right text-xs"></i>
                            </div>
                        </article>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="col-span-3 text-center py-12 border border-dashed border-slate-700 rounded-xl bg-slate-800/30">
                            <p class="text-slate-500">Tidak ada pengumuman terbaru saat ini.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- AGENDA KEGIATAN -->
            <div class="bg-slate-800/50 rounded-3xl p-8 mb-16 border border-slate-700/50 backdrop-blur-md">
                <div class="flex items-center gap-3 mb-8">
                    <div class="p-2 bg-blue-500/10 rounded-lg text-blue-400">
                        <i class="ph-fill ph-calendar-check text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">Agenda Mendatang</h3>
                        <p class="text-slate-400 text-sm mt-0.5">Jadwal kegiatan akademik dan non-akademik.</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <?php $__empty_1 = true; $__currentLoopData = $agendas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agenda): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $colors = ['blue', 'green', 'purple', 'orange', 'pink'];
                            $color = $colors[$loop->index % count($colors)];
                        ?>
                        
                        <div class="bg-slate-700/50 p-4 rounded-xl border-l-4 border-<?php echo e($color); ?>-500 flex items-start gap-4 hover:bg-slate-700 transition cursor-default group h-full">
                            <div class="text-center bg-slate-800 p-2 rounded-lg min-w-[60px] shadow-lg group-hover:bg-slate-900 transition-colors shrink-0">
                                <span class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider"><?php echo e($agenda->event_date->format('M')); ?></span>
                                <span class="block text-xl font-bold text-white"><?php echo e($agenda->event_date->format('d')); ?></span>
                            </div>
                            <div class="flex-1 min-w-0 py-0.5">
                                <h4 class="text-white font-bold text-sm line-clamp-2 leading-snug mb-1" title="<?php echo e($agenda->title); ?>"><?php echo e($agenda->title); ?></h4>
                                <p class="text-slate-400 text-xs flex items-center gap-1.5">
                                    <i class="ph-fill ph-map-pin shrink-0 text-<?php echo e($color); ?>-400"></i> 
                                    <span class="truncate"><?php echo e($agenda->location ?? 'Sekolah'); ?></span>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="col-span-4 text-center py-6">
                            <p class="text-slate-500 italic">Belum ada agenda kegiatan mendatang.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- FOOTER WIDGETS -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16 border-t border-slate-800 pt-16">
                <div class="col-span-1 md:col-span-2 pr-0 md:pr-12">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-lg overflow-hidden bg-white flex items-center justify-center p-1">
                             <img src="<?php echo e(asset('images/logo.png')); ?>" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';" alt="Logo" class="w-full h-full object-contain">
                             <i class="ph-bold ph-graduation-cap text-xl text-blue-900" style="display: none;"></i>
                        </div>
                        <span class="text-xl font-bold text-white tracking-tight">SMPN 3 LAKBOK</span>
                    </div>
                    <p class="text-slate-400 text-sm leading-relaxed mb-8">
                        Visi sekolah adalah Terciptanya generasi pemelajar yang beriman dan bertakwa, tangguh, literat, berkecakapan global, serta berkesadaran budaya dan lingkungan.
                    </p>
                    <div class="flex space-x-4">
                        <a href="https://www.facebook.com/NetiLakbok" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 hover:text-white hover:bg-blue-600 transition-all duration-300"><i class="ph-fill ph-facebook-logo text-xl"></i></a>
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 hover:text-white hover:bg-pink-600 transition-all duration-300"><i class="ph-fill ph-instagram-logo text-xl"></i></a>
                        <a href="https://www.youtube.com/@netilachannel" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 hover:text-white hover:bg-red-600 transition-all duration-300"><i class="ph-fill ph-youtube-logo text-xl"></i></a>
                    </div>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-6 text-lg">Menu Utama</h4>
                    <ul class="space-y-3 text-sm text-slate-400">
                        <li><a href="#profil" class="hover:text-blue-400 transition-colors flex items-center gap-2"><i class="ph-bold ph-caret-right text-xs"></i> Profil Sekolah</a></li>
                        <li><a href="#guru" class="hover:text-blue-400 transition-colors flex items-center gap-2"><i class="ph-bold ph-caret-right text-xs"></i> Tenaga Pendidik</a></li>
                        <li><a href="#kegiatan" class="hover:text-blue-400 transition-colors flex items-center gap-2"><i class="ph-bold ph-caret-right text-xs"></i> Galeri Kegiatan</a></li>
                        <li><a href="<?php echo e(route('login')); ?>" class="hover:text-blue-400 transition-colors flex items-center gap-2"><i class="ph-bold ph-caret-right text-xs"></i> Login Staff</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-6 text-lg">Hubungi Kami</h4>
                    <ul class="space-y-4 text-sm text-slate-400">
                        <li class="flex items-start gap-3">
                            <i class="ph-fill ph-map-pin mt-1 text-blue-500 shrink-0"></i>
                            <span class="leading-relaxed">Jl. Mekarjaya No.199 Sidaharja Kec. Lakbok, Kab. Ciamis, Jawa Barat 46385</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="ph-fill ph-phone text-blue-500 shrink-0"></i>
                            <span>+62 85135961994</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="ph-fill ph-envelope text-blue-500 shrink-0"></i>
                            <span>admin@smpn3lakbok.sch.id</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- COPYRIGHT -->
            <div class="text-center pt-8 border-t border-slate-800">
                <p class="text-slate-500 text-sm">
                    &copy; <?php echo e(date('Y')); ?> SMP Negeri 3 Lakbok. Ri.. All rights reserved.
                </p>
            </div>
        </div>
    </div><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/landing/footer.blade.php ENDPATH**/ ?>