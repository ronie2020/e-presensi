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
    <div class="py-8 sm:py-10 font-sans text-elevate-dark relative overflow-hidden min-h-screen bg-slate-50">
        
        
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-black text-elevate-dark flex items-center gap-3">
                        <i class="ph-duotone ph-archive text-elevate-primary"></i>
                        Tutup Buku Poin
                    </h1>
                    <p class="text-slate-500 font-medium mt-1">Arsipkan poin kedisiplinan tahun ini dan reset poin siswa ke 0 untuk tahun ajaran baru.</p>
                </div>
                
                <div>
                    <a href="<?php echo e(route('discipline.index')); ?>" class="px-5 py-2.5 bg-white border border-slate-200 rounded-2xl text-slate-600 font-bold hover:bg-slate-50 transition-all flex items-center gap-2 shadow-sm active:scale-95">
                        <i class="ph-bold ph-arrow-left text-xl"></i> Kembali ke Disiplin
                    </a>
                </div>
            </div>

            
            <?php if(session('success')): ?>
                <div x-data="{ show: true }" x-show="show" x-transition class="mb-8 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3 px-2">
                        <div class="p-2 bg-emerald-100 rounded-full text-emerald-600">
                            <i class="ph-bold ph-check-circle text-xl"></i>
                        </div>
                        <span class="font-bold text-sm"><?php echo e(session('success')); ?></span>
                    </div>
                    <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 p-2 rounded-xl"><i class="ph-bold ph-x"></i></button>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="mb-8 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl flex items-center gap-3 shadow-sm">
                    <div class="p-2 bg-rose-100 rounded-full text-rose-600">
                        <i class="ph-bold ph-warning-circle text-xl"></i>
                    </div>
                    <span class="font-bold text-sm"><?php echo e(session('error')); ?></span>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                
                <div class="lg:col-span-1">
                    <div class="bg-white p-8 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-rose-500 to-rose-700"></div>
                        
                        <div class="w-14 h-14 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center text-3xl mb-6 border border-rose-100">
                            <i class="ph-duotone ph-warning-octagon"></i>
                        </div>
                        
                        <h3 class="text-lg font-black text-elevate-dark mb-2">Eksekusi Reset Poin</h3>
                        <p class="text-xs text-slate-500 font-medium mb-6 leading-relaxed">
                            Proses ini akan menyimpan saldo poin terakhir dari <strong class="text-elevate-dark"><?php echo e($activeStudentsCount); ?> siswa aktif</strong> ke tabel riwayat, lalu mereset saldo poin aktif mereka kembali menjadi 0.
                        </p>

                        <form action="<?php echo e(route('admin.points_reset.submit')); ?>" method="POST" id="form-reset-poin">
                            <?php echo csrf_field(); ?>
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Tahun Ajaran yang Ditutup</label>
                                    <div class="relative group">
                                        <i class="ph-bold ph-calendar-blank absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="text" name="academic_year" placeholder="Contoh: 2023/2024" required 
                                               class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-rose-500 focus:ring-4 focus:ring-rose-500/20 font-bold text-elevate-dark transition-all shadow-sm outline-none">
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-2 ml-1">Ketik tahun ajaran untuk arsip (misal: 2023/2024).</p>
                                </div>
                                
                                <button type="button" onclick="confirmReset()" class="w-full py-4 px-6 bg-rose-600 text-white font-black rounded-2xl hover:bg-rose-700 transition-all shadow-lg shadow-rose-600/30 flex items-center justify-center gap-2 transform active:scale-95 border border-transparent">
                                    <i class="ph-bold ph-power"></i> Proses Tutup Buku
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden h-full flex flex-col relative">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-elevate-primary to-elevate-accent"></div>

                        <div class="p-6 md:p-8 border-b border-slate-100 bg-slate-50/50">
                            <h3 class="text-lg font-black text-elevate-dark flex items-center gap-2">
                                <i class="ph-fill ph-folder-open text-elevate-primary"></i> Riwayat Arsip Tahunan
                            </h3>
                        </div>
                        
                        <div class="overflow-x-auto flex-1 custom-scrollbar">
                            <table class="w-full text-left text-sm text-elevate-dark">
                                <thead class="bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                    <tr>
                                        <th class="px-8 py-5">Tahun Ajaran</th>
                                        <th class="px-6 py-5 text-center">Siswa Diarsipkan</th>
                                        <th class="px-8 py-5 text-center">Rata-rata Poin</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    <?php $__empty_1 = true; $__currentLoopData = $histories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr class="hover:bg-slate-50/50 transition-colors">
                                            <td class="px-8 py-5 font-black text-elevate-dark text-base">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-elevate-primary shadow-sm">
                                                        <i class="ph-duotone ph-calendar"></i>
                                                    </div>
                                                    <?php echo e($history->academic_year); ?>

                                                </div>
                                            </td>
                                            <td class="px-6 py-5 text-center font-bold text-slate-600">
                                                <?php echo e(number_format($history->total_students)); ?> Siswa
                                            </td>
                                            <td class="px-8 py-5 text-center">
                                                <span class="inline-flex items-center justify-center px-3 py-1.5 bg-elevate-soft text-elevate-primary font-black rounded-xl border border-elevate-accent/20 text-xs shadow-sm">
                                                    <?php echo e(number_format($history->average_score, 1)); ?>

                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="3" class="px-8 py-20 text-center">
                                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300 shadow-inner border border-slate-100">
                                                    <i class="ph-duotone ph-folder-dashed text-4xl"></i>
                                                </div>
                                                <p class="text-sm font-bold text-slate-500">Belum ada riwayat arsip.</p>
                                                <p class="text-xs text-slate-400 mt-1">Lakukan tutup buku untuk melihat data arsip poin di sini.</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function confirmReset() {
            const inputYear = document.querySelector('input[name="academic_year"]').value;
            if(!inputYear) {
                Swal.fire({
                    icon: 'error', title: 'Input Kosong', text: 'Silakan ketik tahun ajaran terlebih dahulu!',
                    customClass: { popup: 'rounded-2xl border border-slate-100 shadow-lg font-sans' }
                });
                return;
            }

            Swal.fire({
                title: 'PERINGATAN KERAS!',
                text: "Anda akan mereset SEMUA poin siswa aktif menjadi 0. Poin saat ini akan diarsipkan untuk tahun " + inputYear + ". Lanjutkan?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Reset Poin ke 0!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-[2rem] font-sans border-0 shadow-2xl',
                    confirmButton: 'bg-rose-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-rose-700 transition-colors mx-2 shadow-lg shadow-rose-900/20',
                    cancelButton: 'bg-slate-100 text-slate-600 px-6 py-3 rounded-xl font-bold hover:bg-slate-200 transition-colors mx-2'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Tunggu sebentar, sedang mengarsipkan data.',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => { Swal.showLoading() }
                    });
                    document.getElementById('form-reset-poin').submit();
                }
            });
        }
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/admin/bk/student_point_reset.blade.php ENDPATH**/ ?>