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
    
    <div class="py-8 sm:py-10 font-sans text-[#2c3f61]" 
         x-data="gradeForm({
            kkm: 75,
            intervals: { a: 92, b: 83, c: 75 } 
         })">
         
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <div class="relative rounded-[2rem] bg-gradient-to-r from-[#56bbf1] via-[#e5eff5] to-[#f4d1c0] p-8 mb-8 text-[#2c3f61] shadow-xl shadow-[#56bbf1]/10 overflow-hidden border border-white/60">
                
                <div class="absolute -top-10 -left-10 w-48 h-48 bg-[#0d52a1]/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-3xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-[#f9a282]/20 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-2xl"></div>
                <div class="absolute top-10 right-32 w-24 h-24 bg-white/40 rounded-2xl rotate-45 pointer-events-none shadow-sm"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 text-[#2c3f61]/70 text-sm font-bold mb-2">
                            <a href="<?php echo e(route('grades.index')); ?>" class="hover:text-[#0d52a1] transition flex items-center gap-1">
                                <i class="ph-bold ph-arrow-left"></i> Kembali
                            </a>
                            <span class="opacity-50">/</span>
                            <span>Input Per Siswa</span>
                        </div>
                        <h1 class="text-4xl font-extrabold tracking-tight leading-none text-[#2c3f61] mb-2">Nilai Siswa</h1>
                        <div class="flex items-center gap-2 mt-3">
                            <span class="bg-white/60 text-[#2c3f61] px-3 py-1 rounded-lg text-xs font-bold border border-white shadow-sm uppercase tracking-wider"><?php echo e($class->name); ?></span>
                            <span class="text-[#2c3f61]/80 font-medium text-sm">Semester <?php echo e($semester); ?></span>
                        </div>
                    </div>

                    
                    <div class="w-full md:w-96 bg-white/60 backdrop-blur-md p-1.5 rounded-2xl border border-white shadow-sm flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-[#2c3f61] text-white flex items-center justify-center font-bold text-lg shadow-sm shrink-0">
                             <?php echo e(substr($student->name, 0, 2)); ?>

                        </div>
                        <div class="flex-1 min-w-0 mr-2">
                            <label class="text-[9px] uppercase font-bold text-[#2c3f61]/60 tracking-widest block mb-0.5">Sedang Menilai:</label>
                            <div class="relative">
                                <select onchange="window.location.href = this.value" 
                                        class="w-full p-0 border-none text-[#2c3f61] font-bold text-sm focus:ring-0 cursor-pointer truncate bg-transparent hover:text-[#0d52a1] transition appearance-none pr-6">
                                    <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option class="text-slate-800" value="<?php echo e(route('grades.create_by_student', ['class_id' => $class->id, 'student_id' => $s->id, 'academic_year' => $academic_year, 'semester' => $semester])); ?>" 
                                                <?php echo e($s->id == $student->id ? 'selected' : ''); ?>>
                                            <?php echo e($s->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <i class="ph-bold ph-caret-down text-[#2c3f61]/60 absolute right-0 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <form id="gradeForm" action="<?php echo e(route('grades.store_by_student')); ?>" method="POST" @submit="isDirty = false; isSubmitting = true">
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
                                    <tr class="hover:bg-[#e5eff5]/40 transition-colors group" 
                                        x-data="{ score: '<?php echo e($existingScore); ?>', predikat: '' }"
                                        x-init="predikat = calculatePredicate(score)">
                                        
                                        <td class="px-6 py-4 text-center font-bold text-slate-400 text-sm"><?php echo e($index + 1); ?></td>
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-[#2c3f61] text-sm group-hover:text-[#0d52a1] transition-colors"><?php echo e($subject->name); ?></div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-center gap-2">
                                                <input type="number" 
                                                       name="grades[<?php echo e($subject->id); ?>]" 
                                                       x-model="score"
                                                       @input="isDirty = true; predikat = calculatePredicate(score)"
                                                       @keydown="handleKeydown($event, <?php echo e($index); ?>, 'score')"
                                                       class="input-score w-20 rounded-xl border-slate-200 bg-slate-50 text-center font-black py-2 focus:ring-[#56bbf1] focus:border-[#56bbf1]"
                                                       placeholder="-">
                                                
                                                <div class="w-8 h-8 flex items-center justify-center rounded-lg font-black text-xs border border-transparent"
                                                     :class="{
                                                        'bg-emerald-100 text-emerald-700': predikat === 'A',
                                                        'bg-blue-100 text-[#0d52a1]': predikat === 'B',
                                                        'bg-[#f9a282]/20 text-[#c86845]': predikat === 'C',
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
                                            <input type="text" name="descriptions[<?php echo e($subject->id); ?>]" value="<?php echo e($existingDesc); ?>" @input="isDirty = true" class="w-full rounded-xl border-slate-200 bg-slate-50 text-sm py-2 px-3 focus:ring-[#56bbf1] focus:border-[#56bbf1]">
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="p-6 bg-white border-t border-slate-100 flex justify-between items-center sticky bottom-0 z-20">
                        <span x-show="isDirty" style="display: none;" class="text-[#f9a282] font-bold text-xs flex items-center gap-1"><i class="ph-fill ph-warning-circle"></i> Perubahan belum disimpan</span>
                        <div class="flex gap-3">
                            <a href="<?php echo e(route('grades.index')); ?>" class="px-6 py-3 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-slate-50 transition">Batal</a>
                            
                            
                            <button type="submit" :class="{'opacity-75 cursor-not-allowed': isSubmitting}" class="px-8 py-3 bg-[#2c3f61] text-white font-bold rounded-xl hover:bg-[#1c2940] shadow-lg shadow-[#2c3f61]/20 transition flex items-center gap-2">
                                <i x-show="isSubmitting" style="display: none;" class="ph-bold ph-spinner animate-spin"></i>
                                <span x-text="isSubmitting ? 'Menyimpan...' : 'Simpan Nilai'">Simpan Nilai</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('gradeForm', (config) => ({
                isDirty: false,
                isSubmitting: false,
                totalRows: <?php echo e(count($subjects)); ?>,
                kkm: config.kkm,
                intervals: config.intervals,

                init() {
                    window.addEventListener('beforeunload', (e) => {
                        if (this.isDirty && !this.isSubmitting) { e.preventDefault(); e.returnValue = ''; }
                    });

                    // NOTIFIKASI SUKSES (Muncul setelah halaman direload)
                    <?php if(session('success')): ?>
                        Swal.fire({
                            icon: 'success',
                            title: 'Tersimpan!',
                            text: '<?php echo e(session('success')); ?>',
                            timer: 3000,
                            showConfirmButton: false,
                            customClass: { popup: 'rounded-[2rem]' }
                        });
                    <?php endif; ?>
                    
                    // NOTIFIKASI ERROR
                    <?php if($errors->any()): ?>
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            html: '<?php echo implode("<br>", $errors->all()); ?>',
                            customClass: { popup: 'rounded-[2rem]' }
                        });
                    <?php endif; ?>
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/grades/create-by-student.blade.php ENDPATH**/ ?>