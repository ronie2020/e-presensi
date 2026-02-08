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
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .table-fixed-column { position: sticky; left: 0; z-index: 10; background-color: #fff; box-shadow: 2px 0 5px rgba(0,0,0,0.05); }
        .table-header-rotate th span { writing-mode: vertical-rl; transform: rotate(180deg); padding-top: 5px; }
        
        @media print {
            .no-print { display: none !important; }
            body { background-color: white; zoom: 70%; }
            .table-fixed-column { position: static; box-shadow: none; }
            .overflow-x-auto { overflow: visible !important; }
        }
    </style>

    <div class="py-6 md:py-8 font-sans text-slate-800 pb-32" x-data="{ loading: false }">
        
        
        <div x-show="loading" class="fixed inset-0 z-[100] bg-slate-900/40 backdrop-blur-[2px] flex items-center justify-center" style="display: none;">
            <div class="bg-white p-6 rounded-2xl shadow-2xl flex flex-col items-center">
                <div class="w-10 h-10 border-4 border-blue-600 border-t-transparent rounded-full animate-spin mb-3"></div>
                <span class="text-xs font-bold text-slate-700 tracking-wider">Memproses Data...</span>
            </div>
        </div>

        <div class="max-w-[95%] mx-auto px-2 sm:px-6 lg:px-8">
            
            
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-6 no-print">
                <div class="animate-enter lg:col-span-1 bg-gray-900 bg-gradient-to-br from-indigo-900 via-blue-900 to-slate-900 rounded-[2rem] p-6 text-white shadow-xl shadow-blue-900/30 relative overflow-hidden flex flex-col justify-center min-h-[160px]">
                     <div class="absolute -right-10 -top-10 w-40 h-40 bg-indigo-500/20 rounded-full blur-2xl"></div>
                     <div class="relative z-10">
                         
                         <a href="<?php echo e(route('reports.class')); ?>" class="group bg-white/10 hover:bg-white/20 text-white px-5 py-3 rounded-2xl font-bold text-sm backdrop-blur-sm border border-white/10 transition-all flex items-center gap-2 shadow-sm w-fit mb-4 mx-auto xl:mx-0">
                            <i class="ph-bold ph-arrow-left text-lg group-hover:-translate-x-1 transition-transform"></i>
                            <span>Kembali ke Rekap</span>
                        </a>
                        <h1 class="text-xl lg:text-2xl font-extrabold mb-1 tracking-tight text-white flex items-center gap-2">
                            Laporan Harian
                        </h1>
                        <p class="text-indigo-200 text-sm font-medium">Detail absensi per tanggal (Matrix).</p>
                    </div>
                </div>

                <div class="animate-enter lg:col-span-3 bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm relative overflow-hidden flex items-center" style="animation-delay: 100ms">
                     
                     <form action="<?php echo e(route('reports.class.detail')); ?>" method="GET" class="w-full flex flex-col md:flex-row gap-4 items-end md:items-center" @submit="loading = true">
                        
                        <div class="flex-1 w-full">
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-1 ml-1">Pilih Kelas</label>
                            <div class="relative">
                                <i class="ph-bold ph-chalkboard-teacher absolute left-4 top-3.5 text-slate-400 text-lg"></i>
                                <select name="class_id" class="w-full pl-11 rounded-xl border-slate-200 bg-slate-50 font-bold h-12 text-sm focus:ring-indigo-900 focus:border-indigo-900 shadow-sm">
                                    <option value="" disabled <?php echo e(!$classId ? 'selected' : ''); ?>>-- Pilih Kelas --</option>
                                    <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($c->id); ?>" <?php echo e($classId == $c->id ? 'selected' : ''); ?>><?php echo e($c->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>

                        <div class="w-full md:w-64">
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-1 ml-1">Bulan</label>
                            <input type="month" name="month" value="<?php echo e($monthStr); ?>" 
                                   class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold h-12 text-sm px-4 focus:ring-indigo-900 focus:border-indigo-900 shadow-sm">
                        </div>

                        <button type="submit" class="w-full md:w-auto bg-indigo-900 hover:bg-slate-900 text-white px-8 rounded-xl h-12 font-bold text-sm shadow-lg flex items-center justify-center gap-2 transition-all active:scale-95">
                            <i class="ph-bold ph-magnifying-glass"></i> Tampilkan
                        </button>
                    </form>
                </div>
            </div>

            <?php if($classId && $students->count() > 0): ?>
                <div class="animate-enter bg-white rounded-[1.5rem] shadow-sm border border-slate-200 overflow-hidden" style="animation-delay: 200ms">
                    
                   <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                        <div>
                            <h3 class="font-bold text-slate-800 text-lg"><?php echo e($classes->where('id', $classId)->first()->name ?? 'Kelas'); ?></h3>
                            <p class="text-xs text-slate-500 font-medium"><?php echo e($startDate->translatedFormat('F Y')); ?></p>
                        </div>
                        <div class="flex gap-2">
                             <div class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-wider bg-white border border-slate-200 px-3 py-1.5 rounded-lg shadow-sm">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span> H: Hadir
                                <span class="w-2 h-2 rounded-full bg-amber-500 ml-2"></span> T: Terlambat
                                <span class="w-2 h-2 rounded-full bg-blue-500 ml-2"></span> S: Sakit
                                <span class="w-2 h-2 rounded-full bg-indigo-500 ml-2"></span> I: Izin
                                <span class="w-2 h-2 rounded-full bg-rose-500 ml-2"></span> A: Alfa
                            </div>
                            
                            
                            <a href="<?php echo e(route('reports.printClassReport', request()->all())); ?>" target="_blank" class="bg-white border border-slate-200 text-slate-600 hover:text-indigo-900 hover:border-indigo-900 w-9 h-9 flex items-center justify-center rounded-lg transition-colors no-print" title="Cetak Laporan">
                                <i class="ph-bold ph-printer text-lg"></i>
                            </a>

                        </div>
                    </div>

                    <div class="overflow-x-auto custom-scrollbar pb-2">
                        <table class="w-full border-collapse text-sm text-left">
                            <thead>
                                <tr class="bg-slate-100 border-b border-slate-200 text-slate-600">
                                    <th class="p-3 font-bold uppercase text-[10px] tracking-wider text-center w-10 sticky left-0 z-20 bg-slate-100 shadow-[2px_0_5px_rgba(0,0,0,0.05)]">No</th>
                                    <th class="p-3 font-bold uppercase text-[10px] tracking-wider min-w-[200px] sticky left-10 z-20 bg-slate-100 shadow-[2px_0_5px_rgba(0,0,0,0.05)] border-r border-slate-200">Nama Siswa</th>
                                    
                                    
                                    <?php $__currentLoopData = $dates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <th class="p-1 font-bold text-[10px] text-center w-8 min-w-[32px] border-r border-slate-200 <?php echo e($date->isSunday() ? 'bg-rose-50/50 text-rose-400' : ''); ?>">
                                            <div class="flex flex-col items-center">
                                                <span><?php echo e($date->format('d')); ?></span>
                                            </div>
                                        </th>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    
                                    <th class="p-2 font-bold text-emerald-600 bg-emerald-50/50 text-center w-10 border-l border-slate-200">H</th>
                                    <th class="p-2 font-bold text-blue-600 bg-blue-50/50 text-center w-10">S</th>
                                    <th class="p-2 font-bold text-indigo-600 bg-indigo-50/50 text-center w-10">I</th>
                                    <th class="p-2 font-bold text-rose-600 bg-rose-50/50 text-center w-10">A</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="hover:bg-slate-50 transition-colors group">
                                        <td class="p-3 text-center text-xs font-bold text-slate-400 sticky left-0 bg-white group-hover:bg-slate-50 z-10 shadow-[2px_0_5px_rgba(0,0,0,0.05)]">
                                            <?php echo e($index + 1); ?>

                                        </td>
                                        <td class="p-3 font-bold text-slate-700 whitespace-nowrap sticky left-10 bg-white group-hover:bg-slate-50 z-10 shadow-[2px_0_5px_rgba(0,0,0,0.05)] border-r border-slate-100">
                                            <?php echo e($student->name); ?>

                                        </td>

                                        
                                        <?php $__currentLoopData = $dates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php 
                                                $dateStr = $date->format('Y-m-d');
                                                $data = $student->attendance_map[$dateStr] ?? ['code' => '-', 'class' => 'text-slate-300'];
                                            ?>
                                            <td class="p-1 text-center border-r border-slate-50 text-[10px] <?php echo e($data['class']); ?>">
                                                <?php echo e($data['code']); ?>

                                            </td>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                        
                                        <td class="p-2 text-center font-bold text-emerald-600 bg-emerald-50/30 text-xs border-l border-slate-100"><?php echo e($student->summary['H']); ?></td>
                                        <td class="p-2 text-center font-bold text-blue-600 bg-blue-50/30 text-xs"><?php echo e($student->summary['S']); ?></td>
                                        <td class="p-2 text-center font-bold text-indigo-600 bg-indigo-50/30 text-xs"><?php echo e($student->summary['I']); ?></td>
                                        <td class="p-2 text-center font-bold text-rose-600 bg-rose-50/30 text-xs"><?php echo e($student->summary['A']); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if(count($students) > 15): ?>
                        <div class="p-4 bg-slate-50 border-t border-slate-200 text-center text-xs text-slate-500">
                            Menampilkan <?php echo e(count($students)); ?> Siswa
                        </div>
                    <?php endif; ?>
                </div>
            <?php elseif($classId): ?>
                <div class="animate-enter text-center py-20 bg-white rounded-[2rem] border border-slate-100 shadow-sm" style="animation-delay: 200ms">
                     <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300"><i class="ph-duotone ph-student text-4xl"></i></div>
                    <h3 class="text-lg font-bold text-slate-700">Data Siswa Kosong</h3>
                    <p class="text-slate-400">Tidak ada siswa aktif ditemukan di kelas ini.</p>
                </div>
            <?php else: ?>
                <div class="animate-enter text-center py-20 bg-white rounded-[2rem] border border-slate-100 shadow-sm" style="animation-delay: 200ms">
                    <div class="w-20 h-20 bg-indigo-50 rounded-full flex items-center justify-center mx-auto mb-4 text-indigo-300"><i class="ph-duotone ph-chalkboard-teacher text-4xl"></i></div>
                    <h3 class="text-lg font-bold text-slate-700">Pilih Kelas Terlebih Dahulu</h3>
                    <p class="text-slate-400">Silakan pilih kelas dan bulan untuk melihat rekapitulasi.</p>
                </div>
            <?php endif; ?>

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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\reports\class_report.blade.php ENDPATH**/ ?>