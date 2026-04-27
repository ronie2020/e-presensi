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
    <div class="py-8 font-sans text-elevate-text bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
                <div>
                    <h1 class="text-3xl font-black text-elevate-dark flex items-center gap-3">
                        <i class="ph-duotone ph-leaf text-elevate-primary"></i>
                        Program Pemulihan Siswa
                    </h1>
                    <p class="text-slate-500 font-medium">Log amnesti poin dan monitoring penyusutan poin otomatis (Point Decay).</p>
                </div>
                
               <div class="flex gap-2">
                    
                    <form action="<?php echo e(route('recovery.trigger_decay')); ?>" method="POST" 
                          onsubmit="event.preventDefault(); 
                                    const form = this;
                                    Swal.fire({
                                        title: 'Jalankan Point Decay?',
                                        text: 'Sistem akan mengecek dan memulihkan poin siswa yang berhak secara otomatis. Lanjutkan?',
                                        icon: 'question',
                                        showCancelButton: true,
                                        confirmButtonColor: '#3b5889', /* elevate-primary */
                                        cancelButtonColor: '#94a3b8',
                                        confirmButtonText: 'Ya, Jalankan!',
                                        cancelButtonText: 'Batal',
                                        reverseButtons: true,
                                        customClass: {
                                            popup: 'rounded-[2.5rem] font-sans border-0 shadow-2xl',
                                            confirmButton: 'bg-elevate-primary text-white px-6 py-3 rounded-xl font-bold hover:bg-elevate-dark transition-colors mx-2 shadow-lg shadow-elevate-primary/20',
                                            cancelButton: 'bg-slate-100 text-slate-600 px-6 py-3 rounded-xl font-bold hover:bg-slate-200 transition-colors mx-2'
                                        },
                                        buttonsStyling: false
                                    }).then((result) => {
                                        if (result.isConfirmed) form.submit();
                                    });">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="px-5 py-2.5 bg-elevate-dark rounded-2xl text-white font-bold hover:bg-elevate-primary transition-all flex items-center gap-2 shadow-lg shadow-elevate-dark/20 active:scale-95">
                            <i class="ph-bold ph-robot text-xl"></i> Cek Poin Kebaikan 
                        </button>
                    </form>

                    
                    <button onclick="document.getElementById('modalAmnesti').classList.remove('hidden')" class="px-5 py-2.5 bg-emerald-600 rounded-2xl text-white font-bold hover:bg-emerald-700 transition-all flex items-center gap-2 shadow-lg shadow-emerald-200 active:scale-95">
                        <i class="ph-bold ph-plus-circle text-xl"></i> Input Manual Tugas Positif
                    </button>
                    
                    <a href="<?php echo e(route('discipline.index')); ?>" class="px-5 py-2.5 bg-white border border-slate-200 rounded-2xl text-slate-600 font-bold hover:bg-slate-50 transition-all flex items-center gap-2 shadow-sm active:scale-95">
                        <i class="ph-bold ph-arrow-left text-xl"></i> Catatan Disiplin
                    </a>
                </div>
            </div>

            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-[2rem] border border-emerald-100 shadow-sm">
                    <div class="text-[10px] font-black text-emerald-500 uppercase tracking-widest mb-1">Total Poin Terpulihkan</div>
                    <div class="text-3xl font-black text-elevate-dark"><?php echo e(number_format($totalRecovered)); ?> <span class="text-sm text-slate-400">Poin</span></div>
                </div>
                <div class="bg-white p-6 rounded-[2rem] border border-elevate-accent/30 shadow-sm">
                    <div class="text-[10px] font-black text-elevate-primary uppercase tracking-widest mb-1">Siswa Aktif Berusaha</div>
                    <div class="text-3xl font-black text-elevate-dark"><?php echo e($activeCount); ?> <span class="text-sm text-slate-400">Siswa</span></div>
                </div>
                <div class="bg-elevate-dark p-6 rounded-[2rem] text-white shadow-xl shadow-elevate-dark/10">
                    <div class="text-[10px] font-black text-elevate-accent/60 uppercase tracking-widest mb-1">Sistem Point Decay</div>
                    <div class="text-3xl font-black">Aktif <span class="text-sm font-medium text-elevate-accent">Bulanan</span></div>
                </div>
            </div>

            
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/30">
                    <h3 class="font-black text-elevate-dark flex items-center gap-2">
                        <i class="ph-bold ph-list-star text-elevate-primary"></i>
                        Riwayat Pemutihan & Bonus Decay
                    </h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                            <tr>
                                <th class="px-8 py-5">Tanggal</th>
                                <th class="px-6 py-5">Siswa</th>
                                <th class="px-6 py-5">Jenis Pemulihan</th>
                                <th class="px-6 py-5 text-center">Saldo (+)</th>
                                <th class="px-6 py-5">Keterangan</th>
                                <th class="px-8 py-5 text-right">Oleh</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php $__empty_1 = true; $__currentLoopData = $recoveryRecords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-emerald-50/30 transition-colors">
                                <td class="px-8 py-5">
                                    <div class="text-xs font-bold text-slate-500"><?php echo e(\Carbon\Carbon::parse($record->date)->translatedFormat('d M Y')); ?></div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="font-black text-elevate-dark text-sm uppercase tracking-tight"><?php echo e($record->student->name); ?></div>
                                    <div class="text-[10px] font-bold text-slate-400 uppercase"><?php echo e($record->student->schoolClass->name ?? '-'); ?></div>
                                </td>
                                <td class="px-6 py-5">
                                    <?php if(str_contains(strtolower($record->disciplineType->name), 'decay')): ?>
                                        <span class="px-3 py-1 rounded-lg bg-elevate-accent/10 text-elevate-primary text-[9px] font-black uppercase border border-elevate-accent/20">Point Decay</span>
                                    <?php else: ?>
                                        <span class="px-3 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-[9px] font-black uppercase border border-emerald-100">Amnesti Manual</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <div class="text-emerald-600 font-black">+<?php echo e($record->disciplineType->point_value); ?></div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="text-xs text-slate-600 italic leading-relaxed">"<?php echo e($record->notes); ?>"</div>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <div class="text-xs font-bold text-slate-500"><?php echo e($record->recorder->name ?? 'Sistem'); ?></div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="px-8 py-20 text-center text-slate-400 italic">Belum ada data pemulihan poin.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="px-8 py-4 bg-slate-50">
                    <?php echo e($recoveryRecords->links()); ?>

                </div>
            </div>
        </div>
    </div>

    
    <div id="modalAmnesti" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-[2.5rem] w-full max-w-lg p-8 shadow-2xl">
            <h3 class="text-xl font-black text-elevate-dark mb-6 flex items-center gap-2">
                <i class="ph-fill ph-leaf text-emerald-500"></i> Validasi Tugas Pemulihan
            </h3>
            <form action="<?php echo e(route('recovery.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Pilih Siswa</label>
                        <select name="student_id" class="w-full rounded-xl border-slate-200 bg-slate-50 text-sm font-bold focus:ring-emerald-500" required>
                            <option value="">-- Pilih Siswa --</option>
                            <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <option value="<?php echo e($s->id); ?>"><?php echo e($s->name); ?> (<?php echo e($s->schoolClass->name ?? '-'); ?>)</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <option value="" disabled>-- Tidak ada siswa aktif --</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Jenis Amnesti</label>
                        <select name="discipline_type_id" class="w-full rounded-xl border-slate-200 bg-slate-50 text-sm font-bold" required>
                            <option value="">-- Pilih Jenis Amnesti --</option>
                            <?php $__empty_1 = true; $__currentLoopData = $recoveryTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <option value="<?php echo e($type->id); ?>"><?php echo e($type->name); ?> (+<?php echo e($type->point_value); ?> Poin)</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <option value="" disabled>⚠️ Belum Ada Master Data Amnesti!</option>
                            <?php endif; ?>
                        </select>
                        <?php if($recoveryTypes->isEmpty()): ?>
                            <p class="text-xs text-rose-500 mt-2 font-medium">
                                Tambahkan jenis kabaikan yang namanya mengandung kata <b>"Amnesti"</b> atau <b>"Pemutihan"</b> di menu Jenis Pelanggaran terlebih dahulu.
                            </p>
                        <?php endif; ?>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Keterangan Tugas</label>
                        <textarea name="notes" placeholder="Tulis tugas yang diselesaikan siswa..." class="w-full rounded-xl border-slate-200 bg-slate-50 text-sm" rows="3" required></textarea>
                    </div>
                </div>
                <div class="flex gap-3 mt-8">
                    <button type="submit" class="flex-1 py-3 bg-emerald-600 text-white font-black rounded-xl hover:bg-emerald-700 transition-all" <?php echo e($recoveryTypes->isEmpty() ? 'disabled' : ''); ?>>Simpan & Kurangi Poin</button>
                    <button type="button" onclick="document.getElementById('modalAmnesti').classList.add('hidden')" class="flex-1 py-3 bg-slate-100 text-slate-500 font-black rounded-xl">Batal</button>
                </div>
            </form>
        </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/discipline/recovery_monitoring.blade.php ENDPATH**/ ?>