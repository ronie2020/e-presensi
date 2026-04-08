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
    
    <div class="py-8 sm:py-10 font-sans text-slate-800" 
         x-data="gradeForm({
            kkm: 75,
            intervals: { a: 92, b: 83, c: 75 } 
         })">
         
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <div class="relative rounded-[2rem] bg-gray-900 bg-gradient-to-br from-slate-900 via-blue-900 to-blue-800 p-8 mb-8 text-white shadow-xl shadow-blue-900/30 overflow-hidden border border-white/10">
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
                    <div>
                        <div class="flex items-center gap-2 text-blue-300 text-sm font-bold mb-2">
                            <a href="<?php echo e(route('grades.index')); ?>" class="hover:text-white transition flex items-center gap-1">
                                <i class="ph-bold ph-arrow-left"></i> Kembali
                            </a>
                            <span class="opacity-50">/</span>
                            <span>Input Nilai Mapel</span>
                        </div>
                        <h1 class="text-3xl font-extrabold tracking-tight leading-none text-white mb-2">Form Penilaian</h1>
                        <p class="text-blue-200 text-sm">Masukan nilai pengetahuan/keterampilan siswa.</p>
                    </div>

                    
                    <div class="flex gap-3">
                        <div class="bg-white/10 backdrop-blur-md px-4 py-2 rounded-2xl border border-white/10 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-500 text-white flex items-center justify-center text-lg shadow-sm">
                                <i class="ph-bold ph-chalkboard-teacher"></i>
                            </div>
                            <div>
                                <p class="text-[9px] font-bold text-blue-300 uppercase tracking-wider">Kelas</p>
                                <p class="text-sm font-bold text-white"><?php echo e($class->name); ?></p>
                            </div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-md px-4 py-2 rounded-2xl border border-white/10 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-sky-500 text-white flex items-center justify-center text-lg shadow-sm">
                                <i class="ph-bold ph-book-open"></i>
                            </div>
                            <div>
                                <p class="text-[9px] font-bold text-blue-300 uppercase tracking-wider">Mapel</p>
                                <p class="text-sm font-bold text-white"><?php echo e($subject->name); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <form id="gradeForm" action="<?php echo e(route('grades.store')); ?>" method="POST" @submit="isDirty = false">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="class_id" value="<?php echo e($class->id); ?>">
                <input type="hidden" name="subject_id" value="<?php echo e($subject->id); ?>">
                <input type="hidden" name="academic_year" value="<?php echo e($academic_year); ?>">
                <input type="hidden" name="semester" value="<?php echo e($semester); ?>">

                <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative">
                    
                    
                    <div class="bg-blue-50/50 px-8 py-3 text-xs font-bold text-blue-700 flex flex-col sm:flex-row items-center justify-between border-b border-blue-100/50 gap-2">
                        <div class="flex items-center gap-2">
                            <i class="ph-fill ph-info text-lg"></i>
                            <span>Tips: Gunakan <strong>Panah Atas/Bawah</strong> untuk pindah baris dengan cepat.</span>
                        </div>
                        <div class="flex gap-4 font-mono opacity-80 bg-white px-3 py-1 rounded-lg border border-blue-100 shadow-sm">
                            <span class="text-emerald-600">A: >92</span>
                            <span class="text-blue-600">B: >83</span>
                            <span class="text-amber-600">C: >75</span>
                        </div>
                    </div>

                    
                    <div class="overflow-x-auto max-h-[70vh] overflow-y-auto custom-scrollbar">
                        <table class="w-full text-left border-collapse relative">
                            <thead class="bg-slate-50 sticky top-0 z-10 shadow-sm text-slate-500 border-b border-slate-200">
                                <tr>
                                    <th class="px-6 py-5 text-xs font-black uppercase tracking-wider w-16 text-center text-slate-400">No</th>
                                    <th class="px-6 py-5 text-xs font-black uppercase tracking-wider min-w-[250px]">Nama Siswa</th>
                                    <th class="px-6 py-5 text-xs font-black uppercase tracking-wider w-48 text-center">Nilai (0-100)</th>
                                    <th class="px-6 py-5 text-xs font-black uppercase tracking-wider min-w-[300px]">Deskripsi (Opsional)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 bg-white">
                                <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $existingScore = $existingGrades[$student->id]->score ?? '';
                                        $existingDesc = $existingGrades[$student->id]->description ?? '';
                                    ?>
                                    <tr class="hover:bg-blue-50/20 transition-colors group focus-within:bg-blue-50/40" 
                                        data-row-index="<?php echo e($index); ?>"
                                        x-data="{ score: '<?php echo e($existingScore); ?>', predikat: '' }"
                                        x-init="predikat = calculatePredicate(score)">
                                        
                                        <td class="px-6 py-4 text-center font-bold text-slate-400 text-sm"><?php echo e($index + 1); ?></td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-4">
                                                <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center font-bold text-xs shadow-sm border border-slate-200 shrink-0">
                                                    <?php echo e(substr($student->name, 0, 2)); ?>

                                                </div>
                                                <div>
                                                    <div class="font-bold text-slate-700 text-sm group-hover:text-blue-700 transition-colors"><?php echo e($student->name); ?></div>
                                                    <div class="text-[10px] text-slate-400 font-mono font-medium tracking-wide">NIS: <?php echo e($student->student_id); ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="relative flex items-center justify-center gap-3">
                                                <input type="number" 
                                                       name="grades[<?php echo e($student->id); ?>]" 
                                                       x-model="score"
                                                       @input="isDirty = true; predikat = calculatePredicate(score)"
                                                       @keydown="handleKeydown($event, <?php echo e($index); ?>, 'score')"
                                                       min="0" max="100"
                                                       class="input-score w-20 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-center font-black text-slate-800 py-2.5 transition-all shadow-sm placeholder:font-normal placeholder:text-slate-300 text-lg"
                                                       placeholder="-">
                                                
                                                
                                                <div class="w-9 h-9 flex items-center justify-center rounded-xl font-black text-sm shadow-sm transition-all duration-300 border border-transparent"
                                                     :class="{
                                                        'bg-emerald-100 text-emerald-700 border-emerald-200': predikat === 'A',
                                                        'bg-blue-100 text-blue-700 border-blue-200': predikat === 'B',
                                                        'bg-amber-100 text-amber-700 border-amber-200': predikat === 'C',
                                                        'bg-rose-100 text-rose-700 border-rose-200': predikat === 'D' || predikat === 'E',
                                                        'bg-slate-50 text-slate-300 border-slate-100': !predikat
                                                     }">
                                                    <span x-text="predikat || '-'"></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="text" 
                                                   name="descriptions[<?php echo e($student->id); ?>]" 
                                                   value="<?php echo e($existingDesc); ?>"
                                                   @input="isDirty = true"
                                                   @keydown="handleKeydown($event, <?php echo e($index); ?>, 'desc')"
                                                   class="input-desc w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm font-medium text-slate-600 py-2.5 px-4 transition-all shadow-sm"
                                                   placeholder="Deskripsi pencapaian...">
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>

                    
                    <div class="p-6 bg-white border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between sticky bottom-0 z-20 shadow-[0_-10px_40px_rgba(0,0,0,0.05)] gap-4">
                        <div class="text-xs text-slate-400 font-medium hidden sm:block">
                            <span x-show="isDirty" class="text-amber-500 font-bold mr-2 flex items-center gap-1"><i class="ph-fill ph-warning-circle text-lg"></i> Perubahan belum disimpan</span>
                            <span>Menampilkan <span class="font-bold text-slate-700"><?php echo e(count($students)); ?></span> Siswa.</span>
                        </div>
                        <div class="flex gap-3 w-full sm:w-auto">
                            <a href="<?php echo e(route('grades.index')); ?>" class="px-6 py-3 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-slate-50 hover:text-slate-800 transition text-center w-full sm:w-auto shadow-sm">Batal</a>
                            <button type="submit" class="px-8 py-3 bg-blue-900 text-white font-bold rounded-xl hover:bg-blue-800 shadow-lg shadow-blue-900/20 transition flex items-center justify-center gap-2 w-full sm:w-auto transform active:scale-95">
                                <i class="ph-bold ph-floppy-disk text-lg"></i> Simpan Data
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('gradeForm', (config) => ({
                isDirty: false,
                totalRows: <?php echo e(count($students)); ?>,
                kkm: config.kkm,
                intervals: config.intervals,

                init() {
                    window.addEventListener('beforeunload', (e) => {
                        if (this.isDirty) { e.preventDefault(); e.returnValue = ''; }
                    });
                },

                calculatePredicate(val) {
                    let score = parseInt(val);
                    if (isNaN(score)) return '';
                    if (score >= this.intervals.a) return 'A';
                    if (score >= this.intervals.b) return 'B';
                    if (score >= this.intervals.c) return 'C';
                    return 'D';
                },

                handleKeydown(e, index, type) {
                    if (e.key === 'ArrowDown' || e.key === 'ArrowUp' || e.key === 'Enter') {
                        if(e.key === 'Enter') e.preventDefault();
                        let nextIndex = index + (e.key === 'ArrowUp' ? -1 : 1);
                        if (nextIndex >= 0 && nextIndex < this.totalRows) {
                            const selector = type === 'score' ? '.input-score' : '.input-desc';
                            const rows = document.querySelectorAll('tbody tr');
                            const target = rows[nextIndex].querySelector(selector);
                            if (target) target.focus();
                        }
                    }
                }
            }))
        })
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/grades/create.blade.php ENDPATH**/ ?>