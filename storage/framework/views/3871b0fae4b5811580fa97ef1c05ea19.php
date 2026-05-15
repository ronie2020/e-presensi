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
        <h2 class="font-semibold text-xl text-elevate-dark leading-tight">
            <?php echo e(__('Rekap Nilai Siswa')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .custom-scrollbar::-webkit-scrollbar { width: 8px; height: 8px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    </style>

    <div class="py-8 sm:py-10 font-sans text-elevate-dark bg-elevate-surface min-h-screen relative overflow-hidden pb-20">
        
        
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            
            <div class="animate-enter relative rounded-[2rem] bg-gradient-to-r from-elevate-accent via-elevate-peach-light to-elevate-peach p-8 md:p-10 mb-8 text-elevate-dark shadow-xl shadow-elevate-accent/20 overflow-hidden border border-white/60 group">
                
                <div class="absolute -top-10 -left-10 w-56 h-56 bg-elevate-primary/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-elevate-peach/40 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-xl"></div>

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="text-center md:text-left">
                        <a href="<?php echo e(route('dashboard')); ?>" class="group bg-white/60 hover:bg-white text-elevate-dark px-5 py-3 rounded-xl font-bold text-sm backdrop-blur-md border border-white/60 transition-all flex items-center gap-2 shadow-sm w-fit mb-4 mx-auto xl:mx-0 active:scale-95">
                            <i class="ph-bold ph-arrow-left text-lg group-hover:-translate-x-1 transition-transform"></i>
                            <span>Kembali ke Dashboard</span>
                        </a>
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/60 border border-white/50 text-elevate-dark text-[10px] font-black uppercase tracking-widest mb-3 backdrop-blur-md shadow-sm">
                            <i class="ph-fill ph-chalkboard-teacher"></i> Area Guru
                        </div>
                        <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-3 flex items-center justify-center md:justify-start gap-3">
                            <i class="ph-fill ph-chart-bar text-elevate-primary drop-shadow-sm"></i> Rekap Nilai
                        </h2>
                        <p class="text-elevate-dark/80 text-sm md:text-base font-semibold max-w-lg leading-relaxed">
                            Pantau perkembangan nilai tugas, kuis, dan ulangan harian siswa dalam satu tampilan matriks terpadu.
                        </p>
                    </div>

                    
                    <?php if(($selectedClassId ?? false) && ($selectedSubjectId ?? false) && isset($assignments) && $assignments->isNotEmpty()): ?>
                        <div class="flex flex-col items-center md:items-end gap-2 w-full md:w-auto shrink-0">
                            <div class="flex flex-col sm:flex-row w-full justify-center gap-3">
                                <a href="<?php echo e(route('lms.grades.export', ['class_id' => $selectedClassId, 'subject_id' => $selectedSubjectId])); ?>" class="btn-export px-6 py-3.5 bg-[#107C10] hover:bg-[#0c5c0c] text-white text-sm font-bold rounded-2xl shadow-lg shadow-[#107C10]/30 transition-all flex items-center justify-center gap-2 group border border-transparent active:scale-95">
                                    <i class="ph-bold ph-microsoft-excel-logo text-xl"></i>
                                    <span>Export Excel</span>
                                </a>
                                <a href="<?php echo e(route('lms.grades.print', ['class_id' => $selectedClassId, 'subject_id' => $selectedSubjectId])); ?>" target="_blank" class="btn-print px-6 py-3.5 bg-white hover:bg-elevate-soft text-elevate-dark text-sm font-bold rounded-2xl shadow-sm border border-slate-200 transition-all flex items-center justify-center gap-2 active:scale-95">
                                    <i class="ph-bold ph-printer text-xl"></i>
                                    <span>Cetak PDF</span>
                                </a>
                            </div>
                            
                            <?php if($assignments->count() > 10): ?>
                                <span class="text-[10px] text-[#D83B01] mt-2 flex items-center justify-center md:justify-start gap-1.5 font-bold bg-[#FFEFD6] px-3 py-1.5 rounded-lg border border-[#FFD8A8]">
                                    <i class="ph-bold ph-info"></i> PDF dibatasi 10 tugas pertama
                                </span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="animate-enter bg-white p-6 md:p-8 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/40 mb-8 relative overflow-hidden" style="animation-delay: 100ms">
                <form action="<?php echo e(route('lms.grades.index')); ?>" method="GET" class="relative z-10" id="filterForm">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-5 items-end">
                        
                        <div class="w-full">
                            <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">Pilih Kelas</label>
                            <div class="relative group">
                                <select name="class_id" class="w-full rounded-2xl border-slate-200 bg-elevate-soft font-bold text-elevate-dark focus:bg-white focus:ring-elevate-accent/30 focus:border-elevate-accent h-14 px-5 appearance-none cursor-pointer transition-colors shadow-sm" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    <?php $__currentLoopData = $classes ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($c->id); ?>" <?php echo e(($selectedClassId ?? '') == $c->id ? 'selected' : ''); ?>>
                                            <?php echo e($c->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-slate-500 group-focus-within:text-elevate-primary"><i class="ph-bold ph-caret-down text-lg"></i></div>
                            </div>
                        </div>

                        <div class="w-full">
                            <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">Pilih Mata Pelajaran</label>
                            <div class="relative group">
                                <select name="subject_id" class="w-full rounded-2xl border-slate-200 bg-elevate-soft font-bold text-elevate-dark focus:bg-white focus:ring-elevate-accent/30 focus:border-elevate-accent h-14 px-5 appearance-none cursor-pointer transition-colors shadow-sm" required>
                                    <option value="">-- Pilih Mapel --</option>
                                    <?php $__currentLoopData = $subjects ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($s->id); ?>" <?php echo e(($selectedSubjectId ?? '') == $s->id ? 'selected' : ''); ?>>
                                            <?php echo e($s->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-slate-500 group-focus-within:text-elevate-primary"><i class="ph-bold ph-caret-down text-lg"></i></div>
                            </div>
                        </div>

                        
                        <div class="w-full">
                            <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">Periode Waktu</label>
                            <div class="relative group">
                                <select name="period" class="w-full rounded-2xl border-slate-200 bg-elevate-soft font-bold text-elevate-dark focus:bg-white focus:ring-elevate-accent/30 focus:border-elevate-accent h-14 px-5 appearance-none cursor-pointer transition-colors shadow-sm" required>
                                    <option value="semester" <?php echo e(($selectedPeriod ?? 'semester') == 'semester' ? 'selected' : ''); ?>>Rekap Semester (Rapor)</option>
                                    <option value="monthly" <?php echo e(($selectedPeriod ?? '') == 'monthly' ? 'selected' : ''); ?>>Bulanan (Bulan Ini)</option>
                                    <option value="weekly" <?php echo e(($selectedPeriod ?? '') == 'weekly' ? 'selected' : ''); ?>>Mingguan (Minggu Ini)</option>
                                    <option value="daily" <?php echo e(($selectedPeriod ?? '') == 'daily' ? 'selected' : ''); ?>>Harian (Hari Ini)</option>
                                </select>
                                <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-slate-500 group-focus-within:text-elevate-primary"><i class="ph-bold ph-caret-down text-lg"></i></div>
                            </div>
                        </div>

                        <div class="w-full">
                            <button type="submit" class="w-full px-8 py-3 bg-elevate-dark text-white font-bold rounded-2xl hover:bg-elevate-primary transition-all shadow-lg shadow-elevate-dark/30 flex items-center justify-center gap-2 h-14 border border-transparent active:scale-95">
                                <i class="ph-bold ph-magnifying-glass text-lg"></i>
                                <span>Tampilkan</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            
            <?php if(($selectedClassId ?? false) && ($selectedSubjectId ?? false)): ?>
                <div class="animate-enter bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/40 overflow-hidden" style="animation-delay: 200ms">
                    <?php if(($assignments ?? collect())->isEmpty()): ?>
                        
                        <div class="p-16 text-center flex flex-col items-center">
                            <div class="w-24 h-24 bg-elevate-soft border border-slate-100 rounded-full flex items-center justify-center mb-6 animate-pulse shadow-sm">
                                <i class="ph-duotone ph-clipboard-text text-5xl text-elevate-primary"></i>
                            </div>
                            <h3 class="text-2xl font-black text-elevate-dark mb-2">Belum Ada Tugas</h3>
                            <p class="text-slate-500 text-sm max-w-md mx-auto leading-relaxed font-medium">
                                Belum ada tugas atau kuis yang dibuat untuk kelas dan mata pelajaran ini. Silakan buat tugas terlebih dahulu.
                            </p>
                            <a href="<?php echo e(route('lms.assignments.create')); ?>" class="mt-8 px-8 py-4 bg-elevate-dark text-white font-bold rounded-2xl hover:bg-elevate-primary shadow-lg shadow-elevate-dark/30 transition-colors flex items-center gap-2 active:scale-95">
                                <i class="ph-bold ph-plus text-lg"></i> Buat Tugas Baru
                            </a>
                        </div>
                    <?php else: ?>
                        
                        <div class="overflow-x-auto custom-scrollbar pb-2">
                            <table class="w-full text-sm text-left border-collapse min-w-[800px]">
                                <thead class="bg-elevate-soft/50 text-elevate-primary uppercase font-black text-[10px] tracking-wider border-b border-slate-100">
                                    <tr>
                                        <th class="px-5 py-5 sticky left-0 bg-elevate-soft/90 backdrop-blur-sm z-20 min-w-[4rem] max-w-[4rem] text-center border-r border-slate-100">No</th>
                                        <th class="px-5 py-5 sticky left-[4rem] bg-elevate-soft/90 backdrop-blur-sm z-20 min-w-[220px] border-r border-slate-100 shadow-[4px_0_12px_-4px_rgba(0,0,0,0.05)]">Nama Siswa</th>
                                        
                                        <!-- Loop Judul Tugas (Kolom) -->
                                        <?php $__currentLoopData = $assignments ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <th class="px-5 py-5 text-center min-w-[140px] group relative border-r border-slate-100 hover:bg-white transition-colors">
                                                <div class="flex flex-col items-center gap-1.5">
                                                    <span class="block truncate w-32 cursor-help font-bold text-elevate-dark" title="<?php echo e($task->title); ?>">
                                                        <?php echo e(Str::limit($task->title, 15)); ?>

                                                    </span>
                                                    <div class="flex items-center gap-1.5">
                                                        <span class="text-[9px] text-slate-500 font-bold bg-white border border-slate-100 px-2 py-0.5 rounded shadow-sm"><?php echo e($task->created_at->format('d/m')); ?></span>
                                                        <span class="text-[9px] px-2 py-0.5 rounded font-black uppercase tracking-widest shadow-sm <?php echo e($task->assignment_type == 'quiz' ? 'bg-purple-100 text-purple-700 border border-purple-200' : 'bg-elevate-primary/10 text-elevate-primary border border-elevate-primary/20'); ?>">
                                                            <?php echo e($task->assignment_type == 'quiz' ? 'Kuis' : 'Tugas'); ?>

                                                        </span>
                                                    </div>
                                                </div>
                                            </th>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                        <th class="px-5 py-5 text-center bg-elevate-soft text-elevate-primary min-w-[100px] border-r border-slate-100">Total</th>
                                        <th class="px-5 py-5 text-center <?php echo e(($selectedPeriod ?? 'semester') == 'semester' ? 'bg-elevate-primary text-white border-elevate-primary' : 'bg-slate-100 text-elevate-dark border-slate-200'); ?> min-w-[110px]">
                                            <?php echo e(($selectedPeriod ?? 'semester') == 'semester' ? 'Nilai Rapor' : 'Rata-rata'); ?>

                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50 bg-white">
                                    <?php $__currentLoopData = $students ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="hover:bg-elevate-soft/30 transition-colors group">
                                            
                                            <td class="px-5 py-4 border-r border-slate-50 sticky left-0 min-w-[4rem] max-w-[4rem] bg-white group-hover:bg-elevate-soft/10 transition-colors text-center text-slate-400 font-bold z-10">
                                                <?php echo e($index + 1); ?>

                                            </td>
                                            
                                            
                                            <td class="px-5 py-4 border-r border-slate-100 sticky left-[4rem] bg-white group-hover:bg-elevate-soft/10 transition-colors z-10 shadow-[4px_0_12px_-4px_rgba(0,0,0,0.05)]">
                                                <div class="flex flex-col">
                                                    <span class="font-black text-elevate-dark text-sm group-hover:text-elevate-primary transition-colors"><?php echo e($student->name); ?></span>
                                                    <span class="text-[10px] font-bold text-slate-400 font-mono tracking-wider mt-0.5"><?php echo e($student->nisn ?? $student->student_id ?? '-'); ?></span>
                                                </div>
                                            </td>

                                            <!-- Loop Nilai per Tugas -->
                                            <?php $__currentLoopData = $assignments ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php $score = $gradeBook[$student->id][$task->id] ?? null; ?>
                                                
                                                <td class="px-5 py-4 text-center border-r border-slate-50">
                                                    <?php if($score !== null): ?>
                                                        <span class="inline-flex w-12 h-9 items-center justify-center rounded-xl text-sm font-black border shadow-sm
                                                            <?php echo e($score < 70 ? 'bg-[#FDE7E9] text-[#D13438] border-[#F4C3C9]' : 'bg-[#DFF6DD] text-[#107C10] border-[#B7DFB9]'); ?>">
                                                            <?php echo e($score); ?>

                                                        </span>
                                                    <?php else: ?>
                                                        <span class="text-slate-300 text-lg font-bold" title="Belum dinilai">-</span>
                                                    <?php endif; ?>
                                                </td>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                            
                                            <td class="px-5 py-4 text-center border-r border-slate-100 bg-elevate-soft/30 font-black text-elevate-primary text-base">
                                                <?php echo e($student->total_score ?? 0); ?>

                                            </td>
                                            
                                            
                                            <?php
                                                $average = $student->average_score ?? 0;
                                                $isSemester = ($selectedPeriod ?? 'semester') == 'semester';
                                                $avgColor = $average > 0 && $average < 70 ? 'text-[#D13438] bg-[#FDE7E9]' : 'text-[#107C10] bg-[#DFF6DD]';
                                                $semesterColor = $average > 0 && $average < 70 ? 'text-white bg-[#D13438]' : 'text-white bg-elevate-primary';
                                            ?>
                                            <td class="px-5 py-4 text-center text-base font-black <?php echo e($isSemester ? $semesterColor : $avgColor); ?>">
                                                <?php echo e($average); ?>

                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                
                <div class="animate-enter bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/40 overflow-hidden relative" style="animation-delay: 200ms">
                    <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:16px_16px] opacity-40 pointer-events-none"></div>
                    
                    <div class="p-12 md:p-20 text-center relative z-10 flex flex-col items-center">
                        <div class="w-24 h-24 bg-elevate-peach-light/30 text-elevate-peach-dark rounded-[2rem] flex items-center justify-center mb-6 border border-elevate-peach/30 shadow-sm rotate-3 hover:rotate-6 transition-transform duration-300">
                            <i class="ph-duotone ph-list-magnifying-glass text-5xl"></i>
                        </div>
                        
                        <h3 class="text-3xl font-black text-elevate-dark mb-4">Mulai Pantau Nilai Siswa</h3>
                        <p class="text-slate-500 max-w-lg mx-auto mb-12 leading-relaxed text-sm font-semibold">
                            Silakan tentukan <b>Kelas</b> dan <b>Mata Pelajaran</b> pada panel filter di atas untuk melihat matriks rekapitulasi nilai secara lengkap.
                        </p>

                        <!-- Quick Guide Steps -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto w-full text-left">
                            <!-- Step 1 -->
                            <div class="bg-white rounded-[1.5rem] p-6 border border-slate-100 shadow-sm relative overflow-hidden group hover:border-elevate-accent hover:shadow-md transition-all">
                                <div class="absolute -top-4 -right-4 w-20 h-20 bg-elevate-soft rounded-full opacity-80 group-hover:scale-150 transition-transform duration-500 pointer-events-none"></div>
                                <div class="w-12 h-12 bg-elevate-soft rounded-xl flex items-center justify-center text-elevate-primary font-black mb-5 border border-slate-200 group-hover:bg-elevate-primary group-hover:text-white group-hover:border-elevate-primary transition-colors z-10 relative text-lg shadow-sm">1</div>
                                <h4 class="font-black text-elevate-dark mb-2 relative z-10 text-lg">Pilih Filter</h4>
                                <p class="text-xs text-slate-500 font-medium leading-relaxed relative z-10">Pilih kelas dan mata pelajaran yang Anda ampu dari menu dropdown di atas.</p>
                            </div>
                            <!-- Step 2 -->
                            <div class="bg-white rounded-[1.5rem] p-6 border border-slate-100 shadow-sm relative overflow-hidden group hover:border-[#B7DFB9] hover:shadow-md transition-all">
                                <div class="absolute -top-4 -right-4 w-20 h-20 bg-[#DFF6DD] rounded-full opacity-80 group-hover:scale-150 transition-transform duration-500 pointer-events-none"></div>
                                <div class="w-12 h-12 bg-[#DFF6DD] rounded-xl flex items-center justify-center text-[#107C10] font-black mb-5 border border-[#B7DFB9] group-hover:bg-[#107C10] group-hover:text-white group-hover:border-[#107C10] transition-colors z-10 relative text-lg shadow-sm">2</div>
                                <h4 class="font-black text-elevate-dark mb-2 relative z-10 text-lg">Tinjau Matriks</h4>
                                <p class="text-xs text-slate-500 font-medium leading-relaxed relative z-10">Pantau nilai tugas, kuis, beserta kalkulasi total dan rata-rata secara otomatis.</p>
                            </div>
                            <!-- Step 3 -->
                            <div class="bg-white rounded-[1.5rem] p-6 border border-slate-100 shadow-sm relative overflow-hidden group hover:border-elevate-peach hover:shadow-md transition-all">
                                <div class="absolute -top-4 -right-4 w-20 h-20 bg-elevate-peach-light/40 rounded-full opacity-80 group-hover:scale-150 transition-transform duration-500 pointer-events-none"></div>
                                <div class="w-12 h-12 bg-elevate-peach-light/40 rounded-xl flex items-center justify-center text-elevate-peach-dark font-black mb-5 border border-elevate-peach/50 group-hover:bg-elevate-peach-dark group-hover:text-white group-hover:border-elevate-peach-dark transition-colors z-10 relative text-lg shadow-sm">3</div>
                                <h4 class="font-black text-elevate-dark mb-2 relative z-10 text-lg">Cetak & Export</h4>
                                <p class="text-xs text-slate-500 font-medium leading-relaxed relative z-10">Unduh data dalam format Microsoft Excel atau cetak langsung menjadi dokumen PDF.</p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>

    
    <?php $__env->startPush('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Loading saat Filter Form dikirim
            const filterForm = document.getElementById('filterForm');
            if(filterForm) {
                filterForm.addEventListener('submit', function(e) {
                    const selects = this.querySelectorAll('select');
                    let isValid = true;
                    selects.forEach(s => { if(s.value === '') isValid = false; });

                    if(isValid) {
                        Swal.fire({
                            title: 'Sedang Memuat Data...',
                            text: 'Mohon tunggu sebentar.',
                            allowOutsideClick: false,
                            didOpen: () => { Swal.showLoading(); },
                            customClass: { popup: 'rounded-[2rem] font-sans border-0 shadow-2xl' }
                        });
                    }
                });
            }

            // 2. Notifikasi Toast Export
            const btnExport = document.querySelector('.btn-export');
            if(btnExport) {
                btnExport.addEventListener('click', function() {
                    Swal.fire({
                        icon: 'success', 
                        title: 'Menyiapkan Excel...',
                        text: 'File akan segera diunduh.',
                        toast: true, 
                        position: 'top-end',
                        showConfirmButton: false, 
                        timer: 4000, 
                        timerProgressBar: true,
                        customClass: { popup: 'rounded-2xl border border-[#B7DFB9] shadow-lg bg-[#DFF6DD] text-[#107C10] font-sans' }
                    });
                });
            }

            // 3. Notifikasi Toast Print
            const btnPrint = document.querySelector('.btn-print');
            if(btnPrint) {
                btnPrint.addEventListener('click', function() {
                    Swal.fire({
                        icon: 'info', 
                        title: 'Membuka PDF...',
                        text: 'Membuka dokumen di tab baru.',
                        toast: true, 
                        position: 'top-end',
                        showConfirmButton: false, 
                        timer: 3000, 
                        timerProgressBar: true,
                        customClass: { popup: 'rounded-2xl border border-slate-200 shadow-lg bg-white text-elevate-dark font-sans' }
                    });
                });
            }

            // 4. Session Messages
            <?php if(session('success')): ?>
                Swal.fire({ icon: 'success', title: 'Berhasil', text: "<?php echo e(session('success')); ?>", customClass: { popup: 'rounded-[2rem] font-sans border-0 shadow-2xl' } });
            <?php endif; ?>
            <?php if(session('error')): ?>
                Swal.fire({ icon: 'error', title: 'Gagal', text: "<?php echo e(session('error')); ?>", customClass: { popup: 'rounded-[2rem] font-sans border-0 shadow-2xl' } });
            <?php endif; ?>
        });
    </script>
    <?php $__env->stopPush(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/lms/grades/index.blade.php ENDPATH**/ ?>