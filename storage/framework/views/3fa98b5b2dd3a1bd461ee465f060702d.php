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
        <div class="flex items-center justify-between print:hidden">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                <?php echo e(__('Analisis Butir Soal')); ?>

            </h2>
            
            
            <a href="<?php echo e(route('cbt.analysis.print', $exam->id)); ?>" target="_blank" class="text-sm font-bold text-slate-500 hover:text-indigo-600 flex items-center gap-2 transition bg-white px-4 py-2 rounded-xl border border-slate-200 shadow-sm">
                <i class="ph-bold ph-printer text-lg"></i> Cetak Laporan Formal
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    
    <style>
        @media print {
            body { background: white; }
            .print\:hidden { display: none !important; }
            .print-area { box-shadow: none !important; border: none !important; }
            table { width: 100%; font-size: 11px; color: black; border-collapse: collapse; }
            th, td { border: 1px solid #cbd5e1 !important; padding: 8px !important; }
            
            /* Memaksa browser mencetak warna background (Grafik Batang) */
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
            .bg-emerald-400 { background-color: #34d399 !important; }
            .bg-blue-400 { background-color: #60a5fa !important; }
            .bg-rose-400 { background-color: #fb7185 !important; }
            .bg-amber-400 { background-color: #fbbf24 !important; }
            .bg-slate-300 { background-color: #cbd5e1 !important; }
        }
    </style>

    <div class="py-8 sm:py-10 font-sans text-slate-800" x-data="{ search: '' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            
            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4 print-area print:mb-6 print:p-0">
                <div>
                    <div class="flex items-center gap-2 mb-1 print:hidden">
                        <a href="<?php echo e(route('cbt.recap', $exam->id)); ?>" class="text-xs font-bold text-slate-400 hover:text-indigo-600 transition flex items-center gap-1">
                            <i class="ph-bold ph-arrow-left"></i> Kembali ke Rekap
                        </a>
                    </div>
                    <h3 class="text-2xl font-black text-slate-800"><?php echo e($exam->title); ?></h3>
                    <p class="text-slate-500 text-sm font-medium">Analisis Kualitas Soal • Mapel: <?php echo e($exam->subject_name); ?> • Sampel: <b><?php echo e($totalStudents); ?> Siswa</b></p>
                </div>
                
                
                <div class="flex gap-3 text-[10px] uppercase font-bold text-slate-500 bg-slate-50 p-3 rounded-xl border border-slate-200 print:bg-transparent print:border-none print:p-0">
                    <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-emerald-400"></span> Mudah (>75%)</div>
                    <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-blue-400"></span> Sedang</div>
                    <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-rose-400"></span> Sukar (<30%)</div>
                </div>
            </div>

            
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden print-area print:rounded-none">
                
                
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row justify-between items-center gap-4 print:hidden">
                    <h4 class="font-bold text-slate-700 flex items-center gap-2 text-lg">
                        <i class="ph-fill ph-chart-pie-slice text-indigo-500"></i> Detail Analisis per Butir
                    </h4>
                    <div class="relative w-full md:w-72">
                        <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" x-model="search" placeholder="Cari potongan soal..." class="w-full pl-10 pr-4 py-2.5 text-sm font-bold border-slate-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 bg-white shadow-sm transition-shadow">
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100 print:bg-white print:text-black">
                            <tr>
                                <th class="px-6 py-4 w-12 text-center">No</th>
                                <th class="px-6 py-4 w-1/3">Cuplikan Soal</th>
                                <th class="px-6 py-4 text-center">Tipe & Kunci</th>
                                <th class="px-6 py-4 text-center">Tingkat Kesukaran</th>
                                <th class="px-6 py-4 w-1/3">Distribusi Jawaban Siswa</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 print:divide-gray-300">
                            <?php $__empty_1 = true; $__currentLoopData = $analysis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr x-show="search === '' || '<?php echo e(strtolower(addslashes(strip_tags($item->text)))); ?>'.includes(search.toLowerCase())" 
                                    class="hover:bg-indigo-50/20 transition group print:hover:bg-transparent">
                                    
                                    <td class="px-6 py-4 text-center font-black text-slate-400 print:text-black"><?php echo e($index + 1); ?></td>
                                    
                                    <td class="px-6 py-4">
                                        <p class="font-medium text-slate-700 line-clamp-3 print:line-clamp-none" title="<?php echo e($item->text); ?>"><?php echo e($item->text); ?></p>
                                    </td>
                                    
                                    
                                    <td class="px-6 py-4 text-center">
                                        <?php if(in_array($item->type, ['choice', 'true_false'])): ?>
                                            <span class="inline-block mb-1 text-[9px] font-bold text-slate-400 uppercase">PG / B-S</span><br>
                                            <span class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 font-black flex items-center justify-center mx-auto border border-slate-200 print:bg-transparent print:border-black">
                                                <?php echo e($item->correct_key); ?>

                                            </span>
                                        <?php elseif($item->type == 'essay'): ?>
                                            <span class="inline-block mb-1 text-[9px] font-bold text-indigo-400 uppercase">ESSAI</span><br>
                                            <button onclick="Swal.fire({title: 'Kunci Jawaban', text: '<?php echo e(addslashes($item->correct_key)); ?>', confirmButtonColor: '#4f46e5'})" 
                                                    class="text-xs font-bold text-indigo-600 hover:underline cursor-pointer print:hidden">
                                                Lihat Kunci
                                            </button>
                                            <span class="hidden print:block text-xs text-slate-700"><?php echo e($item->correct_key ?: '-'); ?></span>
                                        <?php elseif($item->type == 'matching'): ?>
                                            <span class="inline-block mb-1 text-[9px] font-bold text-orange-400 uppercase">MATCHING</span><br>
                                            <span class="text-xs text-slate-400">-</span>
                                        <?php endif; ?>
                                    </td>

                                    
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-block px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider mb-2 <?php echo e($item->difficulty_badge); ?> print:border print:border-black print:text-black print:bg-transparent">
                                            <?php echo e($item->difficulty_label); ?>

                                        </span>
                                        
                                        
                                        <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden flex print:h-2 print:border print:border-black">
                                            <div class="h-full <?php echo e(str_contains($item->difficulty_badge, 'emerald') ? 'bg-emerald-400' : (str_contains($item->difficulty_badge, 'rose') ? 'bg-rose-400' : 'bg-blue-400')); ?>" 
                                                 style="width: <?php echo e($item->difficulty_index); ?>%"></div>
                                        </div>
                                        <p class="text-[10px] font-bold text-slate-400 mt-1 print:text-black"><?php echo e($item->difficulty_index); ?>% Siswa Benar</p>
                                    </td>

                                    
                                    <td class="px-6 py-4">
                                        
                                        <?php if(in_array($item->type, ['choice', 'true_false'])): ?>
                                            <div class="flex items-end gap-2 h-16 w-full pb-1 border-b border-slate-200">
                                                <?php $__currentLoopData = ['A','B','C','D', 'E']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if(isset($item->options[$opt]) || $opt != 'E'): ?> 
                                                        <?php 
                                                            $count = $item->options[$opt] ?? 0;
                                                            $percent = $totalStudents > 0 ? ($count / $totalStudents) * 100 : 0;
                                                            $isKey = $opt == $item->correct_key;
                                                            $color = $isKey ? 'bg-emerald-400' : 'bg-slate-300';
                                                            if(!$isKey && $percent > 20) $color = 'bg-amber-400'; // Distractor yang kuat (Kuning)
                                                        ?>
                                                        <div class="flex-1 flex flex-col justify-end items-center group relative">
                                                            
                                                            <div class="absolute bottom-full mb-1 opacity-0 group-hover:opacity-100 transition text-[10px] font-bold bg-slate-800 text-white px-2 py-1 rounded whitespace-nowrap z-10 print:hidden pointer-events-none">
                                                                <?php echo e($count); ?> Siswa (<?php echo e(round($percent)); ?>%)
                                                            </div>
                                                            
                                                            
                                                            <div class="w-full rounded-t-sm transition-all duration-500 <?php echo e($color); ?>" 
                                                                 style="height: <?php echo e($percent > 0 ? $percent : 2); ?>%"></div>
                                                            
                                                            
                                                            <span class="text-[10px] font-bold <?php echo e($isKey ? 'text-emerald-600' : 'text-slate-400'); ?> mt-1 print:text-black"><?php echo e($opt); ?></span>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        
                                        
                                        <?php elseif($item->type == 'essay'): ?>
                                            <div class="h-16 w-full flex items-center justify-center bg-slate-50 rounded-lg border border-dashed border-slate-200 text-center px-4 print:bg-transparent print:border-solid">
                                                <p class="text-[10px] font-bold text-slate-400 uppercase">
                                                    Dikoreksi Manual
                                                </p>
                                            </div>

                                        
                                        <?php else: ?>
                                            <div class="h-16 w-full flex items-center justify-center">
                                                <p class="text-xs text-slate-400">-</p>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <i class="ph-duotone ph-chart-bar text-3xl"></i>
                                        </div>
                                        Belum ada data analisis. Pastikan ujian sudah dikerjakan siswa.
                                    </td>
                                </tr>
                            <?php endif; ?>

                            
                            <tr x-show="search !== '' && document.querySelectorAll('tbody tr[x-show]:not([style*=\'display: none\'])').length === 0" style="display: none;">
                                <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                                    <p class="font-medium">Tidak ada cuplikan soal yang cocok dengan "<span x-text="search" class="font-bold text-slate-800"></span>"</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/cbt/analysis.blade.php ENDPATH**/ ?>