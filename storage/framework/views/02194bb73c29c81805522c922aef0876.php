<?php if($academic_record): ?>
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-6 mb-6 relative overflow-hidden group hover:border-elevate-accent/30 transition-colors">
        <div class="absolute top-0 right-0 p-4 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity pointer-events-none">
            <i class="ph-fill ph-chart-line-up text-9xl text-elevate-primary"></i>
        </div>
        <div class="h-72 w-full relative z-10">
            <canvas id="academicChart"></canvas>
        </div>
    </div>
    
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto w-full custom-scrollbar">
            <table class="w-full text-left border-collapse min-w-[600px]">
                <thead class="bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-5 rounded-tl-[2.5rem]">Mata Pelajaran</th>
                        <th class="px-6 py-5 text-center">Nilai</th>
                        <th class="px-6 py-5 text-center">Predikat</th>
                        <th class="px-6 py-5 hidden md:table-cell rounded-tr-[2.5rem]">Deskripsi Capaian</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-sm">
                    <?php $__currentLoopData = $academic_record->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-elevate-soft/30 transition-colors group">
                            <td class="px-6 py-5 font-bold text-elevate-dark group-hover:text-elevate-primary transition-colors">
                                <?php echo e($item->subject->name ?? 'Mapel Dihapus'); ?>

                            </td>
                            <td class="px-6 py-5 text-center">
                                <span class="inline-block font-black text-elevate-dark text-lg"><?php echo e($item->score); ?></span>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <?php 
                                    $gradeColor = match($item->predicate) { 
                                        'A' => 'bg-emerald-50 text-emerald-600 border-emerald-200', 
                                        'B' => 'bg-elevate-soft text-elevate-primary border-elevate-accent/30', 
                                        'C' => 'bg-elevate-peach-light/20 text-elevate-peach-dark border-elevate-peach/30', 
                                        default => 'bg-rose-50 text-rose-600 border-rose-200' 
                                    }; 
                                ?>
                                <span class="px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-wider border <?php echo e($gradeColor); ?> shadow-sm">
                                    <?php echo e($item->predicate); ?>

                                </span>
                            </td>
                            <td class="px-6 py-5 text-slate-500 hidden md:table-cell max-w-sm leading-relaxed text-xs font-medium">
                                <?php echo e(Str::limit($item->description, 100) ?? '-'); ?>

                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
<?php else: ?>
    <div class="bg-white rounded-[3rem] border-2 border-dashed border-slate-200 p-16 text-center group hover:border-elevate-accent transition-colors flex flex-col items-center">
        <div class="w-20 h-20 bg-elevate-soft rounded-full flex items-center justify-center mb-4 text-elevate-primary group-hover:scale-110 transition-transform">
            <i class="ph-duotone ph-exam text-4xl"></i>
        </div>
        <h3 class="font-black text-elevate-dark text-lg">Belum Ada Data Nilai</h3>
        <p class="text-sm text-slate-400 mt-1 max-w-xs mx-auto">Data akademik akan muncul setelah guru mempublikasikan nilai ujian/rapor.</p>
    </div>
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/students/portal/partials/tab-akademik.blade.php ENDPATH**/ ?>