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
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            <?php echo e(__('Rekap Nilai Siswa')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        
        .fluent-card { box-shadow: 0 1.6px 3.6px 0 rgba(0, 0, 0, 0.132), 0 0.3px 0.9px 0 rgba(0, 0, 0, 0.108); border: 1px solid rgba(0, 0, 0, 0.05); transition: all 0.3s ease; }
        .fluent-card:hover { box-shadow: 0 6.4px 14.4px 0 rgba(0, 0, 0, 0.132), 0 1.2px 3.6px 0 rgba(0, 0, 0, 0.108); transform: translateY(-2px); }
        .fluent-modal { box-shadow: 0 25.6px 57.6px 0 rgba(0, 0, 0, 0.22), 0 4.8px 14.4px 0 rgba(0, 0, 0, 0.18); border: 1px solid rgba(0, 0, 0, 0.05); }
    </style>

    <div class="py-6 md:py-10 font-sans text-slate-800 pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <div class="animate-enter relative rounded-xl bg-gradient-to-br from-[#25D0FF] via-[#5295FF] to-[#FFC9B9] p-8 md:p-10 mb-8 text-[#2A3B52] shadow-[0_10px_40px_-10px_rgba(37,208,255,0.4)] overflow-hidden border border-white/40 group">
                
                <div class="absolute inset-0 opacity-[0.03] bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none mix-blend-overlay"></div>
                <div class="absolute -top-24 -right-24 w-80 h-80 bg-white/30 rounded-full blur-[80px] pointer-events-none group-hover:bg-white/40 transition-all duration-700"></div>
                <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-white/20 rounded-full blur-[80px] pointer-events-none group-hover:bg-white/30 transition-all duration-700"></div>

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="text-center md:text-left">
                        <a href="<?php echo e(route('dashboard')); ?>" class="group bg-white/40 hover:bg-white/60 text-[#2A3B52] px-5 py-3 rounded-xl font-bold text-sm backdrop-blur-sm border border-white/50 transition-all flex items-center gap-2 shadow-sm w-fit mb-4 mx-auto xl:mx-0 active:scale-95">
                            <i class="ph-bold ph-arrow-left text-lg group-hover:-translate-x-1 transition-transform"></i>
                            <span>Kembali ke Dashboard</span>
                        </a>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/40 border border-white/50 text-[#2A3B52] text-[10px] font-black uppercase tracking-widest mb-3 backdrop-blur-md shadow-sm">
                            <i class="ph-fill ph-chalkboard-teacher"></i> Area Guru
                        </div>
                        <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-2 flex items-center justify-center md:justify-start gap-3">
                            <i class="ph-fill ph-chart-bar text-[#5295FF] drop-shadow-sm"></i> Rekap Nilai
                        </h2>
                        <p class="text-[#2A3B52]/80 text-sm md:text-base font-medium max-w-lg leading-relaxed">
                            Pantau perkembangan nilai tugas, kuis, dan ulangan harian siswa dalam satu tampilan matriks terpadu.
                        </p>
                    </div>

                    
                    <?php if(($selectedClassId ?? false) && ($selectedSubjectId ?? false) && isset($assignments) && $assignments->isNotEmpty()): ?>
                        <div class="flex flex-col items-center md:items-end gap-2 w-full md:w-auto">
                            <div class="flex flex-col sm:flex-row w-full justify-center gap-3">
                                <a href="<?php echo e(route('lms.grades.export', ['class_id' => $selectedClassId, 'subject_id' => $selectedSubjectId])); ?>" class="btn-export px-6 py-3.5 bg-[#107C10] hover:bg-[#0c5c0c] text-white text-sm font-bold rounded-xl shadow-md transition-all flex items-center justify-center gap-2 group border border-transparent active:scale-95">
                                    <i class="ph-bold ph-microsoft-excel-logo text-xl"></i>
                                    <span>Export Excel</span>
                                </a>
                                <a href="<?php echo e(route('lms.grades.print', ['class_id' => $selectedClassId, 'subject_id' => $selectedSubjectId])); ?>" target="_blank" class="btn-print px-6 py-3.5 bg-white hover:bg-slate-50 text-[#2A3B52] text-sm font-bold rounded-xl shadow-sm border border-slate-200 transition-all flex items-center justify-center gap-2 active:scale-95">
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

            
            <div class="animate-enter bg-white p-6 rounded-xl border border-slate-100 shadow-sm mb-8 relative overflow-hidden fluent-card" style="animation-delay: 100ms">
                <form action="<?php echo e(route('lms.grades.index')); ?>" method="GET" class="relative z-10" id="filterForm">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-5 items-end">
                        
                        <div class="w-full">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Pilih Kelas</label>
                            <div class="relative">
                                <select name="class_id" class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold text-[#2A3B52] focus:ring-[#5295FF] focus:border-[#5295FF] h-12 px-4 appearance-none cursor-pointer transition-colors" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    <?php $__currentLoopData = $classes ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($c->id); ?>" <?php echo e(($selectedClassId ?? '') == $c->id ? 'selected' : ''); ?>>
                                            <?php echo e($c->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-500"><i class="ph-bold ph-caret-down"></i></div>
                            </div>
                        </div>

                        <div class="w-full">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Pilih Mata Pelajaran</label>
                            <div class="relative">
                                <select name="subject_id" class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold text-[#2A3B52] focus:ring-[#5295FF] focus:border-[#5295FF] h-12 px-4 appearance-none cursor-pointer transition-colors" required>
                                    <option value="">-- Pilih Mapel --</option>
                                    <?php $__currentLoopData = $subjects ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($s->id); ?>" <?php echo e(($selectedSubjectId ?? '') == $s->id ? 'selected' : ''); ?>>
                                            <?php echo e($s->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-500"><i class="ph-bold ph-caret-down"></i></div>
                            </div>
                        </div>

                        
                        <div class="w-full">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Periode Waktu</label>
                            <div class="relative">
                                <select name="period" class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold text-[#2A3B52] focus:ring-[#5295FF] focus:border-[#5295FF] h-12 px-4 appearance-none cursor-pointer transition-colors" required>
                                    <option value="semester" <?php echo e(($selectedPeriod ?? 'semester') == 'semester' ? 'selected' : ''); ?>>Rekap Semester (Rapor)</option>
                                    <option value="monthly" <?php echo e(($selectedPeriod ?? '') == 'monthly' ? 'selected' : ''); ?>>Bulanan (Bulan Ini)</option>
                                    <option value="weekly" <?php echo e(($selectedPeriod ?? '') == 'weekly' ? 'selected' : ''); ?>>Mingguan (Minggu Ini)</option>
                                    <option value="daily" <?php echo e(($selectedPeriod ?? '') == 'daily' ? 'selected' : ''); ?>>Harian (Hari Ini)</option>
                                </select>
                                <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-500"><i class="ph-bold ph-caret-down"></i></div>
                            </div>
                        </div>

                        <div class="w-full">
                            <button type="submit" class="w-full px-8 py-3 bg-[#2A3B52] text-white font-bold rounded-xl hover:bg-[#182436] transition-all shadow-md flex items-center justify-center gap-2 h-12 border border-transparent active:scale-95">
                                <i class="ph-bold ph-magnifying-glass text-lg"></i>
                                <span>Tampilkan</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            
            <?php if(($selectedClassId ?? false) && ($selectedSubjectId ?? false)): ?>
                <div class="animate-enter bg-white rounded-xl fluent-card overflow-hidden" style="animation-delay: 200ms">
                    <?php if(($assignments ?? collect())->isEmpty()): ?>
                        
                        <div class="p-16 text-center flex flex-col items-center">
                            <div class="w-20 h-20 bg-[#F3F9FD] border border-[#D0E7F8] rounded-xl flex items-center justify-center mb-6 animate-pulse">
                                <i class="ph-duotone ph-clipboard-text text-4xl text-[#5295FF]"></i>
                            </div>
                            <h3 class="text-xl font-black text-[#2A3B52] mb-2">Belum Ada Tugas</h3>
                            <p class="text-slate-500 text-sm max-w-sm mx-auto leading-relaxed">
                                Belum ada tugas atau kuis yang dibuat untuk kelas dan mata pelajaran ini. Silakan buat tugas terlebih dahulu.
                            </p>
                            <a href="<?php echo e(route('lms.assignments.create')); ?>" class="mt-6 px-8 py-3.5 bg-[#2A3B52] text-white font-bold rounded-xl hover:bg-[#182436] shadow-md transition flex items-center gap-2 border border-transparent active:scale-95">
                                <i class="ph-bold ph-plus"></i> Buat Tugas Baru
                            </a>
                        </div>
                    <?php else: ?>
                        
                        <div class="overflow-x-auto custom-scrollbar pb-2">
                            <table class="w-full text-sm text-left border-collapse">
                                <thead class="bg-slate-50 text-slate-500 uppercase font-bold text-xs tracking-wider border-b border-slate-100">
                                    <tr>
                                        <th class="px-4 py-4 sticky left-0 bg-slate-50 z-20 min-w-[3rem] max-w-[3rem] text-center border-r border-slate-100">No</th>
                                        <th class="px-4 py-4 sticky left-[3rem] bg-slate-50 z-20 min-w-[220px] border-r border-slate-100 shadow-[4px_0_12px_-4px_rgba(0,0,0,0.05)]">Nama Siswa</th>
                                        
                                        <!-- Loop Judul Tugas (Kolom) -->
                                        <?php $__currentLoopData = $assignments ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <th class="px-4 py-4 text-center min-w-[130px] group relative border-r border-slate-100 hover:bg-[#F3F9FD] transition-colors">
                                                <div class="flex flex-col items-center gap-1">
                                                    <span class="block truncate w-32 cursor-help font-bold text-[#5295FF]" title="<?php echo e($task->title); ?>">
                                                        <?php echo e(Str::limit($task->title, 15)); ?>

                                                    </span>
                                                    <div class="flex items-center gap-1">
                                                        <span class="text-[9px] text-slate-400 font-medium bg-white border border-slate-100 px-1.5 rounded"><?php echo e($task->created_at->format('d/m')); ?></span>
                                                        <span class="text-[9px] px-1.5 py-0.5 rounded font-bold uppercase border <?php echo e($task->assignment_type == 'quiz' ? 'bg-purple-50 text-purple-600 border-purple-200' : 'bg-[#F3F9FD] text-[#5295FF] border-[#D0E7F8]'); ?>">
                                                            <?php echo e($task->assignment_type == 'quiz' ? 'Kuis' : 'Tugas'); ?>

                                                        </span>
                                                    </div>
                                                </div>
                                            </th>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                        <th class="px-4 py-4 text-center bg-[#F3F9FD]/50 text-[#5295FF] min-w-[90px] border-r border-slate-100">Total</th>
                                        <th class="px-4 py-4 text-center <?php echo e(($selectedPeriod ?? 'semester') == 'semester' ? 'bg-[#E0F0FC] text-[#005A9E] border-[#D0E7F8]' : 'bg-slate-100 text-[#2A3B52]'); ?> min-w-[100px]">
                                            <?php echo e(($selectedPeriod ?? 'semester') == 'semester' ? 'Nilai Rapor' : 'Rata-rata'); ?>

                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50 bg-white">
                                    <?php $__currentLoopData = $students ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="hover:bg-slate-50/80 transition-colors group">
                                            
                                            <td class="px-4 py-3 border-r border-slate-50 sticky left-0 min-w-[3rem] max-w-[3rem] bg-white group-hover:bg-slate-50 transition-colors text-center text-slate-400 font-medium z-10">
                                                <?php echo e($index + 1); ?>

                                            </td>
                                            
                                            
                                            <td class="px-4 py-3 border-r border-slate-100 sticky left-[3rem] bg-white group-hover:bg-slate-50 transition-colors z-10 shadow-[4px_0_12px_-4px_rgba(0,0,0,0.05)]">
                                                <div class="flex flex-col">
                                                    <span class="font-bold text-[#2A3B52] text-sm group-hover:text-[#5295FF] transition-colors"><?php echo e($student->name); ?></span>
                                                    <span class="text-[10px] text-slate-400 font-mono"><?php echo e($student->nisn ?? $student->student_id ?? '-'); ?></span>
                                                </div>
                                            </td>

                                            <!-- Loop Nilai per Tugas -->
                                            <?php $__currentLoopData = $assignments ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php $score = $gradeBook[$student->id][$task->id] ?? null; ?>
                                                
                                                <td class="px-4 py-3 text-center border-r border-slate-50">
                                                    <?php if($score !== null): ?>
                                                        <span class="inline-flex w-10 h-8 items-center justify-center rounded-lg text-xs font-bold border 
                                                            <?php echo e($score < 70 ? 'bg-[#FDE7E9] text-[#D13438] border-[#F4C3C9]' : 'bg-[#DFF6DD] text-[#107C10] border-[#B7DFB9]'); ?>">
                                                            <?php echo e($score); ?>

                                                        </span>
                                                    <?php else: ?>
                                                        <span class="text-slate-300 text-lg" title="Belum dinilai">-</span>
                                                    <?php endif; ?>
                                                </td>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                            
                                            <td class="px-4 py-3 text-center border-r border-slate-100 bg-[#F3F9FD]/30 font-bold text-[#5295FF]">
                                                <?php echo e($student->total_score ?? 0); ?>

                                            </td>
                                            
                                            
                                            <?php
                                                $average = $student->average_score ?? 0;
                                                $isSemester = ($selectedPeriod ?? 'semester') == 'semester';
                                                $avgColor = $average > 0 && $average < 70 ? 'text-[#D13438] bg-[#FDE7E9]' : 'text-[#107C10] bg-[#DFF6DD]';
                                                $semesterColor = $average > 0 && $average < 70 ? 'text-[#D13438] bg-[#FDE7E9]/50 border-l-2 border-[#F4C3C9]' : 'text-[#005A9E] bg-[#E0F0FC]/50 border-l-2 border-[#D0E7F8]';
                                            ?>
                                            <td class="px-4 py-3 text-center font-black <?php echo e($isSemester ? $semesterColor : $avgColor); ?>">
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
                
                <div class="animate-enter bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden relative fluent-card" style="animation-delay: 200ms">
                    <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:16px_16px] opacity-40"></div>
                    
                    <div class="p-12 md:p-16 text-center relative z-10 flex flex-col items-center">
                        <div class="w-20 h-20 bg-[#F3F9FD] text-[#5295FF] rounded-xl flex items-center justify-center mb-6 border border-[#D0E7F8] shadow-sm">
                            <i class="ph-duotone ph-list-magnifying-glass text-5xl"></i>
                        </div>
                        
                        <h3 class="text-2xl font-extrabold text-[#2A3B52] mb-3">Mulai Pantau Nilai Siswa</h3>
                        <p class="text-slate-500 max-w-lg mx-auto mb-10 leading-relaxed text-sm">
                            Silakan tentukan <b>Kelas</b> dan <b>Mata Pelajaran</b> pada panel filter di atas untuk melihat matriks rekapitulasi nilai secara lengkap.
                        </p>

                        <!-- Quick Guide Steps -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto w-full text-left">
                            <!-- Step 1 -->
                            <div class="bg-white rounded-xl p-6 border border-slate-100 shadow-sm relative overflow-hidden group hover:border-[#D0E7F8] hover:shadow-md transition-all">
                                <div class="absolute top-0 right-0 w-16 h-16 bg-[#F3F9FD] rounded-bl-full -mr-6 -mt-6 opacity-80 group-hover:scale-150 transition-transform duration-500"></div>
                                <div class="w-10 h-10 bg-[#F3F9FD] rounded-lg flex items-center justify-center text-[#5295FF] font-black mb-4 border border-[#D0E7F8] group-hover:bg-[#5295FF] group-hover:text-white transition-colors z-10 relative">1</div>
                                <h4 class="font-bold text-[#2A3B52] mb-2 relative z-10">Pilih Filter</h4>
                                <p class="text-xs text-slate-500 leading-relaxed relative z-10">Pilih kelas dan mata pelajaran yang Anda ampu dari menu dropdown di atas.</p>
                            </div>
                            <!-- Step 2 -->
                            <div class="bg-white rounded-xl p-6 border border-slate-100 shadow-sm relative overflow-hidden group hover:border-[#B7DFB9] hover:shadow-md transition-all">
                                <div class="absolute top-0 right-0 w-16 h-16 bg-[#DFF6DD] rounded-bl-full -mr-6 -mt-6 opacity-80 group-hover:scale-150 transition-transform duration-500"></div>
                                <div class="w-10 h-10 bg-[#DFF6DD] rounded-lg flex items-center justify-center text-[#107C10] font-black mb-4 border border-[#B7DFB9] group-hover:bg-[#107C10] group-hover:text-white transition-colors z-10 relative">2</div>
                                <h4 class="font-bold text-[#2A3B52] mb-2 relative z-10">Tinjau Matriks</h4>
                                <p class="text-xs text-slate-500 leading-relaxed relative z-10">Pantau nilai tugas, kuis, beserta kalkulasi total dan rata-rata secara otomatis.</p>
                            </div>
                            <!-- Step 3 -->
                            <div class="bg-white rounded-xl p-6 border border-slate-100 shadow-sm relative overflow-hidden group hover:border-[#FFD8A8] hover:shadow-md transition-all">
                                <div class="absolute top-0 right-0 w-16 h-16 bg-[#FFEFD6] rounded-bl-full -mr-6 -mt-6 opacity-80 group-hover:scale-150 transition-transform duration-500"></div>
                                <div class="w-10 h-10 bg-[#FFEFD6] rounded-lg flex items-center justify-center text-[#D83B01] font-black mb-4 border border-[#FFD8A8] group-hover:bg-[#D83B01] group-hover:text-white transition-colors z-10 relative">3</div>
                                <h4 class="font-bold text-[#2A3B52] mb-2 relative z-10">Cetak & Export</h4>
                                <p class="text-xs text-slate-500 leading-relaxed relative z-10">Unduh data dalam format Microsoft Excel atau cetak langsung menjadi dokumen PDF.</p>
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
                            customClass: { popup: 'rounded-xl fluent-modal border border-slate-100 font-sans' }
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
                        customClass: { popup: 'rounded-xl border border-[#B7DFB9] shadow-md bg-[#DFF6DD] text-[#107C10] font-sans' }
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
                        customClass: { popup: 'rounded-xl border border-[#D0E7F8] shadow-md bg-[#F3F9FD] text-[#5295FF] font-sans' }
                    });
                });
            }

            // 4. Session Messages
            <?php if(session('success')): ?>
                Swal.fire({ icon: 'success', title: 'Berhasil', text: "<?php echo e(session('success')); ?>", customClass: { popup: 'rounded-xl fluent-modal font-sans border-0' } });
            <?php endif; ?>
            <?php if(session('error')): ?>
                Swal.fire({ icon: 'error', title: 'Gagal', text: "<?php echo e(session('error')); ?>", customClass: { popup: 'rounded-xl fluent-modal font-sans border-0' } });
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
<?php endif; ?><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/lms/grades/index.blade.php ENDPATH**/ ?>