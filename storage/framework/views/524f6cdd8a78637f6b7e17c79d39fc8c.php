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
    
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

    <div class="py-8 sm:py-10 font-sans text-elevate-text bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <div class="relative rounded-[2rem] bg-elevate-gradient-main p-8 mb-10 text-elevate-dark shadow-xl shadow-elevate-accent/10 overflow-hidden border border-white/60">
                <div class="absolute -top-10 -left-10 w-48 h-48 bg-elevate-primary/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-3xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-elevate-peach/20 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-2xl"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                    <div class="max-w-2xl">
                        <div class="flex items-center gap-2 mb-2">
                             <a href="<?php echo e(route('extracurriculars.index')); ?>" class="text-xs font-bold text-elevate-primary hover:text-elevate-dark transition flex items-center gap-1 bg-white/60 px-3 py-1 rounded-full border border-white backdrop-blur-sm shadow-sm">
                                <i class="ph-bold ph-arrow-left"></i> Kembali
                            </a>
                            <span class="text-[10px] font-bold text-elevate-dark/70 uppercase tracking-wider bg-white/50 px-3 py-1 rounded-full border border-white/60 backdrop-blur-sm shadow-sm">Laporan</span>
                        </div>
                        <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-3 flex items-center gap-3 text-elevate-dark leading-tight">
                            Rekap Absensi Ekskul
                        </h1>
                        <p class="text-elevate-dark/80 text-sm md:text-base font-medium leading-relaxed max-w-lg">
                            Pantau riwayat partisipasi siswa. Gunakan filter untuk melihat performa kehadiran per kegiatan atau periode tertentu.
                        </p>
                    </div>

                    
                    <div class="flex gap-3">
                        <div class="bg-white/60 backdrop-blur-md px-6 py-5 rounded-[2rem] border border-white shadow-sm text-center min-w-[150px]">
                            <span class="block text-4xl font-black text-elevate-dark mb-1">
                                <?php echo e($attendances instanceof \Illuminate\Pagination\LengthAwarePaginator ? $attendances->total() : $attendances->count()); ?>

                            </span>
                            <span class="text-[10px] uppercase font-bold text-elevate-primary tracking-wider">Total Entri Data</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                
                
                <div class="p-8 border-b border-slate-50 bg-slate-50/50">
                    <form method="GET" action="<?php echo e(route('extracurriculars.reports')); ?>" class="grid grid-cols-1 md:grid-cols-12 gap-6 items-end">
                        
                        <div class="md:col-span-5">
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Pilih Kegiatan</label>
                            <select id="filter-ekskul" name="ekskul_id" class="w-full">
                                <option value="">-- Tampilkan Semua Kegiatan --</option>
                                <?php $__currentLoopData = $extracurriculars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ekskul): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($ekskul->id); ?>" <?php echo e($selectedEkskulId == $ekskul->id ? 'selected' : ''); ?>><?php echo e($ekskul->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="md:col-span-3">
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Dari Tanggal</label>
                            <input type="date" name="start_date" value="<?php echo e($startDate); ?>" class="w-full rounded-2xl border-slate-200 bg-white focus:border-elevate-primary focus:ring-elevate-primary text-sm py-3.5 px-4 font-bold text-elevate-dark transition-all shadow-sm">
                        </div>

                        <div class="md:col-span-3">
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Sampai Tanggal</label>
                            <input type="date" name="end_date" value="<?php echo e($endDate); ?>" class="w-full rounded-2xl border-slate-200 bg-white focus:border-elevate-primary focus:ring-elevate-primary text-sm py-3.5 px-4 font-bold text-elevate-dark transition-all shadow-sm">
                        </div>

                        <div class="md:col-span-1 flex gap-2">
                            <button type="submit" class="w-full h-[52px] bg-elevate-dark text-white rounded-2xl hover:bg-elevate-primary transition-all shadow-lg shadow-elevate-dark/20 flex items-center justify-center group active:scale-95" title="Terapkan Filter">
                                <i class="ph-bold ph-magnifying-glass text-xl group-hover:scale-110 transition-transform"></i>
                            </button>
                        </div>
                    </form>

                    
                    <?php if(!$selectedEkskulId): ?>
                        <div class="mt-6 flex items-center gap-3 p-4 bg-amber-50 rounded-2xl border border-amber-100 text-xs font-bold text-amber-600 tracking-wide">
                            <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center shrink-0"><i class="ph-fill ph-info text-lg"></i></div>
                            Pilih satu kegiatan ekskul di filter atas untuk mengaktifkan fitur cetak laporan PDF.
                        </div>
                    <?php else: ?>
                        <div class="mt-6 flex justify-end">
                            <a href="<?php echo e(route('extracurriculars.reports.export', request()->query())); ?>" target="_blank" class="inline-flex items-center gap-2 px-6 py-3.5 bg-emerald-50 text-emerald-600 border border-emerald-200 rounded-2xl font-bold text-sm hover:bg-emerald-600 hover:text-white transition-all shadow-sm active:scale-95">
                                <i class="ph-bold ph-printer text-lg"></i>
                                <span>Ekspor Laporan PDF</span>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead class="bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">
                            <tr>
                                <th class="px-8 py-5">Identitas Siswa</th>
                                <th class="px-8 py-5">Kegiatan & Waktu</th>
                                <th class="px-8 py-5 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php $__empty_1 = true; $__currentLoopData = $attendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-slate-50/80 transition-colors group">
                                    <td class="px-8 py-5">
                                        <div class="flex items-center gap-4">
                                            <div class="w-11 h-11 rounded-2xl bg-white border border-slate-100 flex items-center justify-center text-elevate-primary font-black text-sm shadow-sm group-hover:border-elevate-primary/30 transition-colors uppercase shrink-0">
                                                <?php echo e(substr($log->student->name, 0, 2)); ?>

                                            </div>
                                            <div>
                                                <div class="font-black text-elevate-dark text-sm group-hover:text-elevate-primary transition-colors"><?php echo e($log->student->name); ?></div>
                                                <div class="text-[10px] font-bold text-slate-400 mt-0.5 uppercase tracking-wider"><?php echo e($log->student->schoolClass->name ?? 'Tanpa Kelas'); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5">
                                        <div class="flex flex-col gap-2">
                                            <div class="flex items-center gap-2">
                                                <span class="px-2.5 py-1 rounded-lg bg-elevate-accent/10 text-elevate-primary text-[10px] font-black uppercase tracking-wide border border-elevate-accent/20">
                                                    <?php echo e($log->extracurricular->name); ?>

                                                </span>
                                            </div>
                                            <div class="flex items-center gap-3 text-xs font-bold text-slate-500">
                                                <span class="flex items-center gap-1.5"><i class="ph-bold ph-calendar-blank"></i> <?php echo e(\Carbon\Carbon::parse($log->date)->isoFormat('D MMM Y')); ?></span>
                                                <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                                <span class="flex items-center gap-1.5 text-elevate-primary"><i class="ph-bold ph-clock"></i> <?php echo e($log->time_in); ?> WIB</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 text-center">
                                        <div class="inline-flex items-center justify-center w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-500 border border-emerald-100 shadow-sm">
                                            <i class="ph-bold ph-check-circle text-xl"></i>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="3" class="px-8 py-24 text-center border border-dashed border-slate-200 rounded-b-[2.5rem] bg-white">
                                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm border border-slate-100">
                                            <i class="ph-duotone ph-files text-4xl text-slate-300"></i>
                                        </div>
                                        <h3 class="text-lg font-black text-elevate-dark mb-1">Data Tidak Ditemukan</h3>
                                        <p class="text-sm text-slate-500 font-medium max-w-sm mx-auto">Silakan ubah filter pencarian untuk melihat riwayat kehadiran lainnya.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if($attendances instanceof \Illuminate\Pagination\LengthAwarePaginator): ?>
                    <div class="p-8 border-t border-slate-50 bg-slate-50/30">
                        <?php echo e($attendances->withQueryString()->links()); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <style>
        /* Custom Styling untuk TomSelect agar senada dengan Elevate Theme */
        .ts-control {
            border-radius: 1rem !important;
            padding: 0.875rem 1rem !important;
            font-weight: 700 !important;
            font-size: 0.875rem !important;
            background-color: #fff !important;
            border-color: #e2e8f0 !important;
            color: #1e293b !important;
        }
        .ts-wrapper.focus .ts-control {
            border-color: #3b5889 !important;
            box-shadow: 0 0 0 1px #3b5889 !important;
        }
        .ts-dropdown {
            border-radius: 1rem !important;
            border-color: #e2e8f0 !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new TomSelect('#filter-ekskul', {
                create: false,
                sortField: { field: "text", direction: "asc" },
                placeholder: "Pilih atau cari kegiatan...",
                plugins: ['dropdown_input'],
                render: {
                    option: function(data, escape) {
                        return '<div class="py-2 px-3 hover:bg-slate-50 transition-colors">' +
                                '<span class="font-bold text-elevate-dark block text-sm">' + escape(data.text) + '</span>' +
                            '</div>';
                    },
                    item: function(data, escape) {
                        return '<div class="font-bold text-sm">' + escape(data.text) + '</div>';
                    }
                }
            });
        });
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
<?php endif; ?><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/extracurriculars/reports.blade.php ENDPATH**/ ?>