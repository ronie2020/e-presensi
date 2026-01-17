<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <?php echo e(__('Proses Konseling')); ?> #<?php echo e($session->id); ?>

            </h2>
            <a href="<?php echo e(route('admin.bk.index')); ?>" class="text-sm text-gray-500 hover:text-gray-700">Kembali</a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Notifikasi -->
            <?php if(session('success')): ?>
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline"><?php echo e(session('success')); ?></span>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- KOLOM KIRI: INFO SISWA & MASALAH -->
                <div class="md:col-span-1 space-y-6">
                    <!-- Info Siswa -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Data Siswa</h3>
                        <div class="flex items-center mb-4">
                            <?php if($session->student->photo_path): ?>
                                <img class="h-12 w-12 rounded-full object-cover mr-4" src="<?php echo e(asset('storage/' . $session->student->photo_path)); ?>" alt="">
                            <?php else: ?>
                                <div class="h-12 w-12 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 font-bold text-lg mr-4">
                                    <?php echo e(substr($session->student->name, 0, 1)); ?>

                                </div>
                            <?php endif; ?>
                            <div>
                                <div class="font-bold text-gray-900"><?php echo e($session->student->name); ?></div>
                                
                                <div class="text-sm text-gray-500"><?php echo e($session->student->schoolClass->name ?? 'Tanpa Kelas'); ?></div>
                            </div>
                        </div>
                        <div class="border-t border-gray-100 pt-4 space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">NIS/NISN:</span>
                                <span class="font-medium"><?php echo e($session->student->nis); ?>/<?php echo e($session->student->nisn); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">No. WA Ortu:</span>
                                <span class="font-medium text-green-600"><?php echo e($session->student->parent_wa_number ?? '-'); ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Pesan Awal -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Detail Pengajuan</h3>
                        <div class="mb-4">
                            <span class="px-2 py-1 text-xs rounded bg-slate-100 font-bold text-slate-600"><?php echo e($session->category->name); ?></span>
                            <span class="px-2 py-1 text-xs rounded bg-slate-100 font-bold text-slate-600 ml-2"><?php echo e(ucfirst($session->method)); ?></span>
                        </div>
                        <div class="bg-yellow-50 border border-yellow-100 p-4 rounded-lg text-sm text-slate-700 italic">
                            "<?php echo e($session->initial_message); ?>"
                        </div>
                        <div class="mt-2 text-xs text-gray-400 text-right">
                            Diajukan: <?php echo e($session->created_at->format('d M Y H:i')); ?>

                        </div>
                    </div>
                </div>

                <!-- KOLOM KANAN: AKSI & JURNAL -->
                <div class="md:col-span-2 space-y-6">
                    
                    <!-- 1. FORM APPROVAL (Hanya jika status PENDING) -->
                    <?php if($session->status == 'pending'): ?>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-amber-500">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Tindakan Awal</h3>
                        <form action="<?php echo e(route('admin.bk.update_status', $session->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Keputusan</label>
                                    <select name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" x-data="{}" @change="$el.value == 'approved' ? document.getElementById('schedule_field').style.display = 'block' : document.getElementById('schedule_field').style.display = 'none'">
                                        <option value="approved">Setujui & Jadwalkan</option>
                                        <option value="rejected">Tolak</option>
                                    </select>
                                </div>
                                <div id="schedule_field">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Jadwal Pertemuan</label>
                                    <input type="datetime-local" name="scheduled_at" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pesan Balasan (Untuk Siswa)</label>
                                <textarea name="response_message" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Contoh: Silakan datang ke ruang BK, Ibu tunggu ya." required></textarea>
                                <p class="text-xs text-gray-500 mt-1">*Pesan ini akan dikirim via WA ke Orang Tua/Siswa jika nomor tersedia.</p>
                            </div>

                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 font-bold text-sm">
                                Simpan & Kirim Notifikasi
                            </button>
                        </form>
                    </div>
                    <?php endif; ?>

                    <!-- INFO JADWAL (Jika sudah Approved) -->
                    <?php if($session->status == 'approved' || $session->status == 'finished'): ?>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Sesi Terjadwal</h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    <i class="ph-bold ph-calendar-check mr-1"></i> <?php echo e($session->scheduled_at ? $session->scheduled_at->translatedFormat('l, d F Y H:i') : '-'); ?>

                                </p>
                                <p class="text-sm text-gray-600 mt-1">
                                    <i class="ph-bold ph-chat-centered-text mr-1"></i> Respon: "<?php echo e($session->response_message); ?>"
                                </p>
                            </div>
                            <?php if($session->status == 'approved'): ?>
                            <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2 py-1 rounded">Sedang Berlangsung</span>
                            <?php else: ?>
                            <span class="bg-green-100 text-green-800 text-xs font-bold px-2 py-1 rounded">Selesai</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- 2. FORM JURNAL HASIL (Untuk Menyelesaikan Sesi) -->
                    <?php if($session->status == 'approved' || $session->status == 'finished'): ?>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="flex justify-between items-center mb-4 border-b pb-2">
                            <h3 class="text-lg font-bold text-gray-900">Jurnal Konseling (Rekam Medis)</h3>
                            <?php if($session->status == 'finished'): ?>
                                <span class="text-xs bg-slate-100 text-slate-500 px-2 py-1 rounded"><i class="ph-fill ph-lock-key"></i> Read Only</span>
                            <?php endif; ?>
                        </div>

                        <?php if($session->status == 'approved'): ?>
                        <form action="<?php echo e(route('admin.bk.store_record', $session->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Analisis Masalah</label>
                                    <textarea name="problem_analysis" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Analisis akar masalah siswa..." required></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Solusi / Tindakan</label>
                                    <textarea name="solution" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Nasihat atau tindakan yang diberikan..." required></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Hasil Akhir</label>
                                    <textarea name="result" rows="2" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Kesepakatan atau hasil konseling..."></textarea>
                                </div>
                                
                                <div class="flex items-center gap-2 bg-yellow-50 p-3 rounded-md border border-yellow-200">
                                    <input type="checkbox" name="is_confidential" value="1" id="is_confidential" checked class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <label for="is_confidential" class="text-sm text-slate-700 font-medium">Bersifat Rahasia (Hanya Guru BK & Kepsek yang bisa lihat)</label>
                                </div>

                                <div class="pt-4">
                                    <button type="submit" class="w-full bg-green-600 text-white px-4 py-3 rounded-md hover:bg-green-700 font-bold shadow-lg shadow-green-500/30 flex justify-center items-center gap-2">
                                        <i class="ph-bold ph-check-circle"></i> Simpan & Selesaikan Sesi
                                    </button>
                                </div>
                            </div>
                        </form>
                        <?php else: ?>
                            <!-- TAMPILAN READ ONLY JIKA SUDAH SELESAI -->
                            <div class="space-y-4 text-sm">
                                <div>
                                    <h4 class="font-bold text-slate-700">Analisis Masalah</h4>
                                    <p class="text-slate-600 mt-1 p-3 bg-slate-50 rounded border border-slate-100"><?php echo e($session->record->problem_analysis ?? '-'); ?></p>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-700">Solusi / Tindakan</h4>
                                    <p class="text-slate-600 mt-1 p-3 bg-slate-50 rounded border border-slate-100"><?php echo e($session->record->solution ?? '-'); ?></p>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-700">Hasil Akhir</h4>
                                    <p class="text-slate-600 mt-1 p-3 bg-slate-50 rounded border border-slate-100"><?php echo e($session->record->result ?? '-'); ?></p>
                                </div>
                                <?php if($session->record->is_confidential): ?>
                                    <div class="inline-flex items-center gap-1 px-3 py-1 bg-red-100 text-red-700 rounded-full font-bold text-xs">
                                        <i class="ph-fill ph-lock-key"></i> Dokumen Rahasia
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/admin/bk/show.blade.php ENDPATH**/ ?>