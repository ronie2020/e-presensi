<div class="mb-8 sticky top-4 z-40 transition-all duration-300" id="sticky-nav">
    <div class="bg-white/90 backdrop-blur-xl p-1.5 rounded-2xl shadow-lg border border-gray-100/50 relative group">
        <div class="absolute right-0 top-0 bottom-0 w-8 bg-gradient-to-l from-white to-transparent pointer-events-none md:hidden z-10 rounded-r-2xl"></div>
        
        <div class="overflow-x-auto custom-scrollbar w-full pb-0.5 md:pb-0 scroll-smooth px-1 md:overflow-visible">
            <div class="flex items-center gap-1 w-max md:w-full md:flex-wrap md:justify-center"> 
                <?php
                    $tabs = [
                        'ringkasan' => ['icon' => 'squares-four', 'label' => 'Ringkasan'],
                    ];

                    if ($isAlumni) {
                        $tabs['prestasi'] = ['icon' => 'trophy', 'label' => 'Riwayat Prestasi'];
                        $tabs['perpustakaan'] = ['icon' => 'books', 'label' => 'Riwayat Pustaka'];
                    } else {
                        $tabs['kebiasaan'] = ['icon' => 'sun-horizon', 'label' => '7 Kebiasaan'];
                        $tabs['penghubung'] = ['icon' => 'notebook', 'label' => 'Buku Penghubung'];
                        $tabs['pengaduan'] = ['icon' => 'megaphone', 'label' => 'Pengaduan'];
                        $tabs['jadwal'] = ['icon' => 'calendar-blank', 'label' => 'Jadwal']; 
                        $tabs['lms'] = ['icon' => 'clipboard-text', 'label' => 'Tugas & Kuis'];
                        $tabs['kbm'] = ['icon' => 'chalkboard-teacher', 'label' => 'Jurnal KBM'];
                        $tabs['akademik'] = ['icon' => 'exam', 'label' => 'Nilai Rapor'];
                        $tabs['kehadiran'] = ['icon' => 'calendar-check', 'label' => 'Kehadiran'];
                        $tabs['disiplin'] = ['icon' => 'warning-circle', 'label' => 'Disiplin'];
                        $tabs['prestasi'] = ['icon' => 'trophy', 'label' => 'Prestasi'];
                        $tabs['perpustakaan'] = ['icon' => 'books', 'label' => 'Pustaka'];
                        // Opsional jika ingin menampilkan Keagamaan di tab terpisah
                         $tabs['keagamaan'] = ['icon' => 'hands-praying', 'label' => 'Keagamaan'];
                    }
                ?>

                <?php $__currentLoopData = $tabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $tab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <button @click="updateTab('<?php echo e($key); ?>')" 
                        :class="activeTab === '<?php echo e($key); ?>' ? 'bg-slate-900 text-white shadow-lg shadow-slate-300 transform scale-100' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700'"
                        class="px-4 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 flex items-center gap-2 whitespace-nowrap flex-shrink-0 outline-none focus:ring-2 focus:ring-slate-200 mb-1">
                        <i class="ph-bold ph-<?php echo e($tab['icon']); ?> text-lg"></i> <?php echo e($tab['label']); ?>

                    </button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</div><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/students/portal/partials/tabs-nav.blade.php ENDPATH**/ ?>