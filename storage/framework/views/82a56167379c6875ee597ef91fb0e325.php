<div class="grid grid-cols-1 gap-6">
    <?php if(isset($teaching_journals) && count($teaching_journals) > 0): ?>
        <?php $__currentLoopData = $teaching_journals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $journal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        
        
        <?php
            // Cek apakah siswa ini punya record absen di jurnal ini
            $attendance = $journal->attendances->where('student_id', $student->id)->first();
            $status = $attendance ? $attendance->status : null;

            // Setup Tampilan Badge
            $badgeColor = 'bg-slate-100 text-slate-500 border-slate-200';
            $statusLabel = 'Belum Absen';

            if ($status == 'present') {
                $badgeColor = 'bg-emerald-50 text-emerald-600 border-emerald-100';
                $statusLabel = 'Hadir';
            } elseif ($status == 'sick') {
                $badgeColor = 'bg-blue-50 text-blue-600 border-blue-100';
                $statusLabel = 'Sakit';
            } elseif ($status == 'permission') {
                $badgeColor = 'bg-amber-50 text-amber-600 border-amber-100';
                $statusLabel = 'Izin';
            } elseif ($status == 'alpha') {
                $badgeColor = 'bg-rose-50 text-rose-600 border-rose-100';
                $statusLabel = 'Alpha';
            } elseif ($journal->status == 'closed' && !$status) {
                // Jika kelas sudah tutup tapi siswa tidak ada data, otomatis Alpha
                $badgeColor = 'bg-rose-50 text-rose-600 border-rose-100';
                $statusLabel = 'Alpha (Otomatis)';
            }
        ?>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow relative">
            
            
            <div class="absolute top-0 right-0 p-4 z-10">
                 <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border <?php echo e($badgeColor); ?>">
                    <?php echo e($statusLabel); ?>

                </span>
            </div>

            <div class="p-6">
                <div class="flex flex-col md:flex-row justify-between items-start gap-4 mb-4 pr-16">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 text-xs font-bold uppercase tracking-wide border border-blue-100">
                                <?php echo e($journal->schedule?->subject?->name ?? 'Mapel'); ?>

                            </span>
                            <span class="text-xs text-slate-400 font-bold flex items-center gap-1">
                                <i class="ph-fill ph-clock"></i>
                                <?php echo e(\Carbon\Carbon::parse($journal->started_at)->format('H:i')); ?>

                            </span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mt-2"><?php echo e($journal->topic ?? 'Tanpa Topik'); ?></h3>
                        <p class="text-sm text-slate-500 font-medium">Pengajar: <span class="text-slate-700"><?php echo e($journal->schedule?->teacher?->name ?? 'Guru'); ?></span></p>
                    </div>
                    
                    
                    <div class="flex items-center gap-3 md:block md:text-right mt-2 md:mt-0">
                        <div class="md:hidden w-10 h-10 bg-slate-50 rounded-lg flex items-center justify-center border border-slate-100">
                             <i class="ph-duotone ph-calendar text-xl text-slate-400"></i>
                        </div>
                        <div>
                            <p class="text-sm md:text-2xl font-black text-slate-600 md:text-slate-200"><?php echo e(\Carbon\Carbon::parse($journal->date)->format('d')); ?></p>
                            <p class="text-xs font-bold text-slate-400 uppercase"><?php echo e(\Carbon\Carbon::parse($journal->date)->translatedFormat('M Y')); ?></p>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-50 rounded-xl p-4 mb-2 border border-slate-100">
                    <p class="text-[10px] font-bold text-slate-400 uppercase mb-2 flex items-center gap-1">
                        <i class="ph-bold ph-notebook"></i> Aktivitas / Tugas
                    </p>
                    <p class="text-slate-700 text-sm leading-relaxed whitespace-pre-line"><?php echo e($journal->activities ?? 'Tidak ada catatan aktivitas.'); ?></p>
                </div>

                <?php if($journal->photo_proof): ?>
                    <div class="mt-4">
                        <p class="text-[10px] font-bold text-slate-400 uppercase mb-2">Dokumentasi Kelas</p>
                        <div class="h-32 w-48 rounded-lg overflow-hidden border border-slate-200 relative group">
                            <img src="<?php echo e(asset('storage/' . $journal->photo_proof)); ?>" class="w-full h-full object-cover transition-transform group-hover:scale-105">
                            <a href="<?php echo e(asset('storage/' . $journal->photo_proof)); ?>" target="_blank" class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <i class="ph-bold ph-eye text-white text-2xl"></i>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php else: ?>
        <div class="bg-white rounded-3xl border-2 border-dashed border-slate-200 p-16 text-center group hover:border-blue-300 transition-colors">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-blue-50 transition-colors">
                <i class="ph-duotone ph-notebook text-4xl text-slate-300 group-hover:text-blue-400 transition-colors"></i>
            </div>
            <h3 class="font-bold text-slate-800 text-lg">Belum Ada Riwayat KBM</h3>
            <p class="text-sm text-slate-400 mt-2">Jurnal kegiatan belajar mengajar akan muncul di sini.</p>
        </div>
    <?php endif; ?>
</div><?php /**PATH C:\Users\ronie\Documents\aplikasi\E-Presensi Netila\resources\views/students/portal/partials/tab-kbm.blade.php ENDPATH**/ ?>