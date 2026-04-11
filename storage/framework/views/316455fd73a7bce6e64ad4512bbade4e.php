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
                    <div class="flex-1">
                        <div class="flex items-center gap-2 text-blue-300 text-sm font-bold mb-2">
                            <a href="<?php echo e(route('grades.index')); ?>" class="hover:text-white transition flex items-center gap-1">
                                <i class="ph-bold ph-arrow-left"></i> Kembali
                            </a>
                            <span class="opacity-50">/</span>
                            <span>Input Per Siswa</span>
                        </div>
                        <h1 class="text-3xl font-extrabold tracking-tight leading-none text-white mb-2">Nilai Siswa</h1>
                        <div class="flex items-center gap-2">
                            <span class="bg-white/10 text-white px-3 py-1 rounded-lg text-xs font-bold border border-white/10 uppercase tracking-wider"><?php echo e($class->name); ?></span>
                            <span class="text-blue-200 text-sm">Semester <?php echo e($semester); ?></span>
                        </div>
                    </div>

                    
                    <div class="w-full md:w-96 bg-white/10 backdrop-blur-md p-1.5 rounded-2xl border border-white/20 flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-white text-blue-900 flex items-center justify-center font-bold text-lg shadow-sm shrink-0">
                             <?php echo e(substr($student->name, 0, 2)); ?>

                        </div>
                        <div class="flex-1 min-w-0 mr-2">
                            <label class="text-[9px] uppercase font-bold text-blue-300 tracking-widest block mb-0.5">Sedang Menilai:</label>
                            <div class="relative">
                                <select onchange="window.location.href = this.value" 
                                        class="w-full p-0 border-none text-white font-bold text-sm focus:ring-0 cursor-pointer truncate bg-transparent hover:text-blue-200 transition appearance-none pr-6">
                                    <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option class="text-slate-800" value="<?php echo e(route('grades.create_by_student', ['class_id' => $class->id, 'student_id' => $s->id, 'academic_year' => $academic_year, 'semester' => $semester])); ?>" 
                                                <?php echo e($s->id == $student->id ? 'selected' : ''); ?>>
                                            <?php echo e($s->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <i class="ph-bold ph-caret-down text-blue-300 absolute right-0 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <form id="gradeForm" action="<?php echo e(route('grades.store_by_student')); ?>" method="POST" @submit="isDirty = false">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="class_id" value="<?php echo e($class->id); ?>">
                <input type="hidden" name="student_id" value="<?php echo e($student->id); ?>">
                <input type="hidden" name="academic_year" value="<?php echo e($academic_year); ?>">
                <input type="hidden" name="semester" value="<?php echo e($semester); ?>">

                <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                    <div class="overflow-x-auto max-h-[70vh] overflow-y-auto custom-scrollbar">
                        <table class="w-full text-left border-collapse relative">
                            <thead class="bg-slate-50 sticky top-0 z-10 shadow-sm text-slate-500 border-b border-slate-200">
                                <tr>
                                    <th class="px-6 py-5 text-xs font-black uppercase tracking-wider w-16 text-center text-slate-400">No</th>
                                    <th class="px-6 py-5 text-xs font-black uppercase tracking-wider min-w-[250px]">Mata Pelajaran</th>
                                    <th class="px-6 py-5 text-xs font-black uppercase tracking-wider w-64 text-center">Nilai (0-100)</th>
                                    <th class="px-6 py-5 text-xs font-black uppercase tracking-wider min-w-[300px]">Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 bg-white">
                                <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $existingScore = $existingGrades[$subject->id]->score ?? '';
                                        $existingDesc = $existingGrades[$subject->id]->description ?? '';
                                    ?>
                                    <tr class="hover:bg-fuchsia-50/20 transition-colors group" 
                                        x-data="{ score: '<?php echo e($existingScore); ?>', predikat: '' }"
                                        x-init="predikat = calculatePredicate(score)">
                                        
                                        <td class="px-6 py-4 text-center font-bold text-slate-400 text-sm"><?php echo e($index + 1); ?></td>
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-slate-700 text-sm group-hover:text-fuchsia-700 transition-colors"><?php echo e($subject->name); ?></div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-center gap-2">
                                                <input type="number" 
                                                       name="grades[<?php echo e($subject->id); ?>]" 
                                                       x-model="score"
                                                       @input="isDirty = true; predikat = calculatePredicate(score)"
                                                       @keydown="handleKeydown($event, <?php echo e($index); ?>, 'score')"
                                                       class="input-score w-20 rounded-xl border-slate-200 bg-slate-50 text-center font-black py-2"
                                                       placeholder="-">
                                                
                                                <div class="w-8 h-8 flex items-center justify-center rounded-lg font-black text-xs border border-transparent"
                                                     :class="{
                                                        'bg-emerald-100 text-emerald-700': predikat === 'A',
                                                        'bg-blue-100 text-blue-700': predikat === 'B',
                                                        'bg-amber-100 text-amber-700': predikat === 'C',
                                                        'bg-rose-100 text-rose-700': predikat === 'D',
                                                        'bg-slate-50 text-slate-300': !predikat
                                                     }" x-text="predikat || '-'"></div>

                                                
                                                <button type="button" 
                                                        @click="if(confirm('Hapus nilai mapel <?php echo e(addslashes($subject->name)); ?>?')) { score = ''; predikat = ''; isDirty = true; }"
                                                        class="p-2 text-slate-300 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition"
                                                        title="Hapus Nilai">
                                                    <i class="ph-bold ph-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="text" name="descriptions[<?php echo e($subject->id); ?>]" value="<?php echo e($existingDesc); ?>" @input="isDirty = true" class="w-full rounded-xl border-slate-200 bg-slate-50 text-sm py-2 px-3">
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="p-6 bg-white border-t border-slate-100 flex justify-between items-center sticky bottom-0 z-20">
                        <span x-show="isDirty" class="text-amber-500 font-bold text-xs flex items-center gap-1"><i class="ph-fill ph-warning-circle"></i> Perubahan belum disimpan</span>
                        <div class="flex gap-3">
                            <a href="<?php echo e(route('grades.index')); ?>" class="px-6 py-3 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm">Batal</a>
                            <button type="submit" class="px-8 py-3 bg-fuchsia-600 text-white font-bold rounded-xl hover:bg-fuchsia-700 shadow-lg transition">Simpan Nilai</button>
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
                totalRows: <?php echo e(count($subjects)); ?>,
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\grades\create-by-student.blade.php ENDPATH**/ ?>