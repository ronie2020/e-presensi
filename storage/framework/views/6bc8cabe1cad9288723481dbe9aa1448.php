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
        @media print {
            @page { size: landscape; margin: 10mm; }
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; background: white !important; }
            .no-print, header, nav, footer, form, .shadow-sm, .modal-backdrop { display: none !important; }
            .print-container { padding: 0 !important; margin: 0 !important; border: none !important; box-shadow: none !important; width: 100% !important; }
            .print-table th, .print-table td { border: 1px solid #cbd5e1 !important; font-size: 10pt !important; padding: 8px !important; }
            .print-header { display: block !important; margin-bottom: 20px; text-align: center; }
            .print-hidden { display: none !important; }
        }
        .print-header { display: none; }
        [x-cloak] { display: none !important; }
    </style>

    
    <div x-data="{
        isModalOpen: false,
        studentName: '',
        details: {}, 
        formAction: '',
        currentScore: '',
        currentNote: '',
        
        // FUNGSI BUKA MODAL
        openFeedback(id, name, score, note, fasting, prayerCount, sunnahCount, khotib, summary) {
            this.studentName = name;
            this.currentScore = score ? score : 100; // Default nilai 100 jika belum ada
            this.currentNote = note;
            
            this.details = {
                fasting: fasting,
                prayerCount: prayerCount,
                sunnahCount: sunnahCount,
                khotib: khotib,
                summary: summary
            };

            this.formAction = '<?php echo e(route('admin.ramadan.verify', ':id')); ?>'.replace(':id', id); 
            this.isModalOpen = true;
        },

        // FUNGSI QUICK SCORE
        setScore(value) {
            this.currentScore = value;
        }
    }" class="p-6 md:p-8 space-y-6 min-h-screen bg-slate-50 print-container">
        
        
        <div class="relative rounded-[2.5rem] bg-gradient-to-r from-blue-900 via-slate-800 to-slate-900 p-8 mb-8 text-white shadow-2xl shadow-blue-900/20 overflow-hidden border border-white/10 group no-print">
            
            
            <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-blue-500 rounded-full mix-blend-overlay filter blur-[120px] opacity-20 group-hover:opacity-30 transition-opacity duration-1000 pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-[300px] h-[300px] bg-indigo-500 rounded-full mix-blend-overlay filter blur-[100px] opacity-20 pointer-events-none"></div>
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-black tracking-tight flex items-center gap-3 text-white">
                        <i class="ph-fill ph-book-open text-blue-400"></i> Rekap Mutabaah
                    </h1>
                    <p class="text-sm text-blue-100 mt-1">Monitoring ibadah: <span class="font-bold text-white"><?php echo e(\Carbon\Carbon::parse($date)->isoFormat('dddd, D MMMM Y')); ?></span></p>
                </div>
                
                
                <form action="<?php echo e(route('admin.ramadan.reports')); ?>" method="GET" class="flex flex-wrap gap-3 bg-white/10 p-2 rounded-2xl shadow-sm border border-white/10 backdrop-blur-md">
                    <div class="flex items-center px-3 gap-2 border-r border-white/10">
                        <i class="ph-bold ph-chalkboard text-blue-300"></i>
                        <select name="class_id" class="border-none bg-transparent text-sm font-bold focus:ring-0 cursor-pointer min-w-[140px] text-white option:text-slate-800" onchange="this.form.submit()">
                            <option value="" class="text-slate-800">Pilih Kelas</option>
                            <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($c->id); ?>" <?php echo e($selectedClass == $c->id ? 'selected' : ''); ?> class="text-slate-800"><?php echo e($c->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="flex items-center px-3 gap-2">
                        <i class="ph-bold ph-calendar text-blue-300"></i>
                        <input type="date" name="date" value="<?php echo e($date); ?>" onchange="this.form.submit()" class="border-none bg-transparent text-sm font-bold focus:ring-0 cursor-pointer text-white placeholder-blue-200">
                    </div>
                    <div class="px-2">
                         <button type="submit" class="hidden">Filter</button>
                    </div>
                </form>
            </div>
        </div>

        
        <?php if($selectedClass && $reports->count() > 0): ?>
        <?php
            $totalStudents = $reports->count();
            $fastingCount = $reports->filter(fn($s) => $s->ramadanLogs->first()?->is_fasting)->count();
            $fullPrayerCount = $reports->filter(fn($s) => count(array_filter($s->ramadanLogs->first()?->prayers ?? [])) == 5)->count();
            $fastingPercent = $totalStudents > 0 ? round(($fastingCount / $totalStudents) * 100) : 0;
            // $isFriday dipindah ke Controller agar tidak error saat diakses global
        ?>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 no-print">
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center"><i class="ph-bold ph-student"></i></div>
                <div><div class="text-lg font-black text-slate-800"><?php echo e($totalStudents); ?></div><div class="text-[10px] uppercase font-bold text-slate-400">Total Siswa</div></div>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center"><i class="ph-bold ph-bowl-food"></i></div>
                <div><div class="text-lg font-black text-slate-800"><?php echo e($fastingCount); ?> <span class="text-xs text-slate-400 font-medium">(<?php echo e($fastingPercent); ?>%)</span></div><div class="text-[10px] uppercase font-bold text-slate-400">Berpuasa</div></div>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center"><i class="ph-bold ph-hands-praying"></i></div>
                <div><div class="text-lg font-black text-slate-800"><?php echo e($fullPrayerCount); ?></div><div class="text-[10px] uppercase font-bold text-slate-400">Shalat Lengkap</div></div>
            </div>
             <?php if($isFriday): ?>
            <div class="bg-white p-4 rounded-2xl border border-emerald-100 shadow-sm flex items-center gap-3 ring-1 ring-emerald-50">
                <div class="w-10 h-10 rounded-full bg-emerald-600 text-white flex items-center justify-center"><i class="ph-bold ph-mosque"></i></div>
                <div>
                    <div class="text-lg font-black text-slate-800">
                        <?php echo e($reports->filter(fn($s) => $s->ramadanLogs->first()?->friday_khotib)->count()); ?>

                    </div>
                    <div class="text-[10px] uppercase font-bold text-slate-400">Jurnal Jumat</div>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        
        <div class="print-header">
            <h2 class="text-xl font-bold uppercase">Laporan Harian Mutabaah Ramadhan</h2>
            <p class="text-sm"><?php echo e($classes->find($selectedClass)->name ?? 'Semua Kelas'); ?> &bull; <?php echo e(\Carbon\Carbon::parse($date)->isoFormat('dddd, D MMMM Y')); ?></p>
        </div>

        
        <?php if($selectedClass): ?>
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden print-container">
                <div class="overflow-x-auto">
                    <table class="w-full text-left print-table">
                        <thead class="bg-slate-50/50 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest w-10">No</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Siswa</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Puasa</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Shalat 5W</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center no-print">Detail</th>
                                
                                
                                <?php if($isFriday): ?>
                                <th class="px-6 py-4 text-[10px] font-black text-emerald-600 uppercase tracking-widest text-center bg-emerald-50/30">Laporan Jumat</th>
                                <?php endif; ?>

                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tilawah</th>
                                <th class="px-6 py-4 text-[10px] font-black text-emerald-600 uppercase tracking-widest text-center bg-emerald-50/50">Feedback Guru</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php $__empty_1 = true; $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php 
                                $log = $student->ramadanLogs->first();
                                $prayerCount = $log ? count(array_filter($log->prayers ?? [])) : 0;
                                $sunnahCount = $log ? count(array_filter($log->sunnah_deeds ?? [])) : 0;
                            ?>
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 text-xs font-bold text-slate-500"><?php echo e($index + 1); ?></td>
                                <td class="px-6 py-4">
                                    <div class="font-black text-slate-800 text-sm"><?php echo e($student->name); ?></div>
                                    <div class="text-[10px] font-bold text-slate-400"><?php echo e($student->student_id); ?></div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <?php if($log && $log->is_fasting): ?>
                                        <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto"><i class="ph-bold ph-check"></i></div>
                                    <?php elseif($log): ?>
                                        <div class="w-6 h-6 rounded-full bg-rose-100 text-rose-500 flex items-center justify-center mx-auto"><i class="ph-bold ph-x"></i></div>
                                    <?php else: ?>
                                        <span class="text-slate-300">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <?php if($log): ?>
                                        <span class="text-sm font-black <?php echo e($prayerCount == 5 ? 'text-emerald-600' : 'text-amber-500'); ?>"><?php echo e($prayerCount); ?>/5</span>
                                    <?php else: ?>
                                        <span class="text-slate-300">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-center no-print">
                                    <?php if($log && is_array($log->prayers)): ?>
                                        <div class="flex justify-center gap-1">
                                            <?php $__currentLoopData = ['subuh','dzuhur','ashar','maghrib','isya']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div title="<?php echo e(ucfirst($p)); ?>" class="w-2 h-2 rounded-full <?php echo e(($log->prayers[$p] ?? false) ? 'bg-emerald-500' : 'bg-slate-200'); ?>"></div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>

                                
                                <?php if($isFriday): ?>
                                <td class="px-6 py-4 text-center">
                                    <?php if($log && $log->friday_khotib): ?>
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-emerald-100 text-emerald-700 text-[10px] font-bold border border-emerald-200" title="<?php echo e(Str::limit($log->friday_summary, 50)); ?>">
                                            <i class="ph-bold ph-check-circle"></i> Ada
                                        </span>
                                    <?php else: ?>
                                        <span class="text-slate-300 text-[10px] italic">Kosong</span>
                                    <?php endif; ?>
                                </td>
                                <?php endif; ?>

                                <td class="px-6 py-4">
                                    <?php if($log && $log->tadarus_surah): ?>
                                        <div class="text-xs font-bold text-slate-700"><?php echo e(Str::limit($log->tadarus_surah, 12)); ?> : <?php echo e($log->tadarus_ayah); ?></div>
                                    <?php else: ?>
                                        <span class="text-slate-300 text-[10px]">-</span>
                                    <?php endif; ?>
                                </td>
                                
                                
                                <td class="px-6 py-4 text-center bg-slate-50/30">
                                    <?php if($log): ?>
                                        <button type="button" 
                                            @click="openFeedback(
                                                <?php echo e($log->id); ?>, 
                                                <?php echo e(json_encode($student->name)); ?>, 
                                                '<?php echo e($log->teacher_score); ?>', 
                                                <?php echo e(json_encode($log->teacher_note)); ?>,
                                                <?php echo e($log->is_fasting ? 'true' : 'false'); ?>,
                                                <?php echo e($prayerCount); ?>,
                                                <?php echo e($sunnahCount); ?>,
                                                <?php echo e(json_encode($log->friday_khotib)); ?>,
                                                <?php echo e(json_encode($log->friday_summary)); ?>

                                            )"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider transition-all shadow-sm group
                                            <?php echo e($log->teacher_verified_at ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-white border border-slate-200 text-slate-500 hover:bg-emerald-600 hover:text-white hover:border-emerald-600'); ?>">
                                            <?php if($log->teacher_verified_at): ?>
                                                <i class="ph-bold ph-check-circle"></i> Nilai: <?php echo e($log->teacher_score); ?>

                                            <?php else: ?>
                                                <i class="ph-bold ph-chat-text group-hover:scale-110 transition-transform"></i> Nilai
                                            <?php endif; ?>
                                        </button>
                                    <?php else: ?>
                                        <span class="text-slate-300 text-[10px] italic">Belum Input</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="<?php echo e($isFriday ? 8 : 7); ?>" class="text-center py-10 text-slate-400">Tidak ada data siswa.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            
            <div class="text-center py-24 bg-white rounded-[3rem] border border-dashed border-slate-200 no-print">
                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="ph-duotone ph-chalkboard-teacher text-5xl text-slate-300"></i>
                </div>
                <h3 class="text-xl font-black text-slate-600">Pilih Kelas</h3>
                <p class="text-slate-400 text-sm mt-2">Silakan pilih kelas di atas untuk melihat laporan.</p>
            </div>
        <?php endif; ?>

        
        <?php if($selectedClass): ?>
        <div class="flex justify-end gap-4 no-print">
            <button onclick="window.print()" class="bg-slate-800 text-white px-6 py-3 rounded-xl font-bold hover:bg-slate-700 transition shadow-lg flex items-center gap-2">
                <i class="ph-bold ph-printer"></i> Cetak Laporan
            </button>
        </div>
        <?php endif; ?>

        
        <div x-cloak x-show="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center px-4 modal-backdrop">
            
            <div x-show="isModalOpen" 
                x-transition.opacity
                @click="isModalOpen = false"
                class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

            
            <div x-show="isModalOpen" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                class="relative bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden flex flex-col max-h-[90vh]">
                
                
                <div class="bg-gradient-to-r from-emerald-800 to-teal-600 p-6 text-white flex justify-between items-start shrink-0">
                    <div>
                        <h3 class="font-black text-xl">Feedback & Motivasi</h3>
                        <p class="text-emerald-100 text-sm" x-text="studentName"></p>
                    </div>
                    <button @click="isModalOpen = false" class="text-white/60 hover:text-white transition"><i class="ph-bold ph-x text-2xl"></i></button>
                </div>

                
                <div class="p-6 overflow-y-auto space-y-6">
                    
                    
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 space-y-3">
                        <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-200 pb-2 mb-2">Rincian Ibadah Hari Ini</h4>
                        
                        <div class="grid grid-cols-3 gap-2 text-center">
                            <div class="bg-white p-2 rounded-xl border border-slate-100">
                                <div class="text-[10px] font-bold text-slate-400">Puasa</div>
                                <div class="font-black" :class="details.fasting ? 'text-emerald-600' : 'text-rose-500'" x-text="details.fasting ? 'YA' : 'TIDAK'"></div>
                            </div>
                            <div class="bg-white p-2 rounded-xl border border-slate-100">
                                <div class="text-[10px] font-bold text-slate-400">Wajib</div>
                                <div class="font-black text-slate-700"><span x-text="details.prayerCount"></span>/5</div>
                            </div>
                            <div class="bg-white p-2 rounded-xl border border-slate-100">
                                <div class="text-[10px] font-bold text-slate-400">Sunnah</div>
                                <div class="font-black text-slate-700" x-text="details.sunnahCount"></div>
                            </div>
                        </div>

                        
                        <template x-if="details.khotib">
                            <div class="mt-3 pt-3 border-t border-slate-200">
                                <div class="text-[10px] font-bold text-emerald-600 uppercase mb-1">Jurnal Jumat</div>
                                <div class="text-xs font-bold text-slate-800 mb-1" x-text="'Khotib: ' + details.khotib"></div>
                                <div class="text-xs text-slate-600 italic bg-white p-3 rounded-xl border border-slate-100" x-text="details.summary"></div>
                            </div>
                        </template>
                         
                         
                         <template x-if="!details.khotib && '<?php echo e($isFriday ?? false); ?>'">
                             <div class="mt-3 pt-3 border-t border-slate-200">
                                <div class="text-[10px] font-bold text-rose-500 uppercase mb-1">Jurnal Jumat: Kosong</div>
                             </div>
                        </template>
                    </div>

                    
                    <form :action="formAction" method="POST" id="gradingForm">
                        <?php echo csrf_field(); ?>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Nilai Aktivitas (0-100)</label>
                                <div class="flex items-center gap-3">
                                    <input type="number" name="teacher_score" x-model="currentScore" min="0" max="100" class="w-full rounded-xl border-slate-200 font-bold focus:ring-emerald-500 focus:border-emerald-500 text-lg" placeholder="0">
                                    
                                    
                                    <div class="flex gap-1">
                                        <button type="button" @click="setScore(100)" class="px-3 py-2 bg-emerald-50 text-emerald-700 rounded-lg text-xs font-bold hover:bg-emerald-100">100</button>
                                        <button type="button" @click="setScore(80)" class="px-3 py-2 bg-slate-50 text-slate-700 rounded-lg text-xs font-bold hover:bg-slate-100">80</button>
                                        <button type="button" @click="setScore(60)" class="px-3 py-2 bg-rose-50 text-rose-700 rounded-lg text-xs font-bold hover:bg-rose-100">60</button>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Catatan & Motivasi Guru</label>
                                <textarea name="teacher_note" x-model="currentNote" rows="3" class="w-full rounded-xl border-slate-200 text-sm focus:ring-emerald-500 focus:border-emerald-500 leading-relaxed" placeholder="Berikan kata-kata motivasi..."></textarea>
                            </div>
                        </div>
                    </form>
                </div>

                
                <div class="p-6 border-t border-slate-100 bg-slate-50 flex justify-end gap-3 shrink-0">
                    <button @click="isModalOpen = false" class="px-5 py-2.5 rounded-xl font-bold text-slate-500 hover:bg-slate-200 transition text-sm">Batal</button>
                    <button type="submit" form="gradingForm" class="px-5 py-2.5 rounded-xl font-bold text-white bg-emerald-600 hover:bg-emerald-700 transition text-sm shadow-lg shadow-emerald-200 flex items-center gap-2">
                        <i class="ph-bold ph-paper-plane-right"></i> Simpan
                    </button>
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\ramadan\admin_report.blade.php ENDPATH**/ ?>