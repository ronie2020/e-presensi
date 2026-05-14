
<div class="mb-8">
    <h3 class="text-lg font-black text-elevate-dark mb-4 flex items-center gap-2">
        <i class="ph-fill ph-book-open text-elevate-primary"></i> Nilai Semester Aktif
    </h3>

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
            <p class="text-sm text-slate-400 mt-1 max-w-xs mx-auto">Data akademik semester ini akan muncul setelah guru mempublikasikan nilai.</p>
        </div>
    <?php endif; ?>
</div>


<div class="mt-12">
    <h3 class="text-lg font-black text-elevate-dark mb-4 flex items-center gap-2">
        <i class="ph-fill ph-clock-counter-clockwise text-elevate-primary"></i> Riwayat Nilai Raport (Buku Induk)
    </h3>
    
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto w-full custom-scrollbar p-6">
            <table class="w-full text-center text-xs md:text-sm border-collapse min-w-[800px] border border-slate-200 rounded-xl overflow-hidden">
                <thead class="bg-slate-50 font-bold text-elevate-dark">
                    <tr>
                        <th rowspan="2" class="border border-slate-200 p-3 w-12">No</th>
                        <th rowspan="2" class="border border-slate-200 p-3 text-left">Mata Pelajaran</th>
                        <th colspan="2" class="border border-slate-200 p-3 bg-blue-50/50">Kelas VII</th>
                        <th colspan="2" class="border border-slate-200 p-3 bg-emerald-50/50">Kelas VIII</th>
                        <th colspan="2" class="border border-slate-200 p-3 bg-amber-50/50">Kelas IX</th>
                    </tr>
                    <tr class="text-[10px] md:text-xs uppercase tracking-wider text-slate-500">
                        <th class="border border-slate-200 p-2 bg-blue-50/30">Smt 1</th>
                        <th class="border border-slate-200 p-2 bg-blue-50/30">Smt 2</th>
                        <th class="border border-slate-200 p-2 bg-emerald-50/30">Smt 1</th>
                        <th class="border border-slate-200 p-2 bg-emerald-50/30">Smt 2</th>
                        <th class="border border-slate-200 p-2 bg-amber-50/30">Smt 1</th>
                        <th class="border border-slate-200 p-2 bg-amber-50/30">Smt 2</th>
                    </tr>
                </thead>
                <tbody class="text-slate-600">
                    <?php
                        // Memastikan variabel $student ada (di portal biasanya dikirim dengan nama $student atau Auth::user()->student)
                        $targetStudent = isset($student) ? $student : auth()->user()->student;
                        $mapelInduk = \App\Models\Subject::orderBy('order')->get();
                        $no = 1;
                    ?>

                    <?php if($targetStudent): ?>
                        <?php $__currentLoopData = $mapelInduk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mapel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="border border-slate-200 p-2"><?php echo e($no++); ?></td>
                            <td class="border border-slate-200 p-2 text-left font-semibold text-elevate-dark"><?php echo e($mapel->name); ?></td>
                            
                            <td class="border border-slate-200 p-2 font-mono"><?php echo e($targetStudent->getScore($mapel->name, 7, 1) ?: '-'); ?></td>
                            <td class="border border-slate-200 p-2 font-mono"><?php echo e($targetStudent->getScore($mapel->name, 7, 2) ?: '-'); ?></td>
                            
                            <td class="border border-slate-200 p-2 font-mono"><?php echo e($targetStudent->getScore($mapel->name, 8, 1) ?: '-'); ?></td>
                            <td class="border border-slate-200 p-2 font-mono"><?php echo e($targetStudent->getScore($mapel->name, 8, 2) ?: '-'); ?></td>
                            
                            <td class="border border-slate-200 p-2 font-mono"><?php echo e($targetStudent->getScore($mapel->name, 9, 1) ?: '-'); ?></td>
                            <td class="border border-slate-200 p-2 font-mono"><?php echo e($targetStudent->getScore($mapel->name, 9, 2) ?: '-'); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="border border-slate-200 p-4 text-slate-400 italic">Data profil siswa tidak ditemukan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/students/portal/partials/tab-akademik.blade.php ENDPATH**/ ?>