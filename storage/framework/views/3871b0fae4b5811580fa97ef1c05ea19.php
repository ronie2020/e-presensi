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

    <div class="py-8 font-sans text-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <div class="relative rounded-[2rem] bg-gray-900 bg-gradient-to-br from-slate-900 via-blue-900 to-blue-800 p-8 mb-8 text-white shadow-xl shadow-blue-900/30 overflow-hidden border border-white/10">
                
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-blue-400/10 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="text-center md:text-left">
                        <a href="<?php echo e(route('dashboard')); ?>" class="group bg-white/10 hover:bg-white/20 text-white px-5 py-3 rounded-2xl font-bold text-sm backdrop-blur-sm border border-white/10 transition-all flex items-center gap-2 shadow-sm w-fit mb-4 mx-auto xl:mx-0">
                            <i class="ph-bold ph-arrow-left text-lg group-hover:-translate-x-1 transition-transform"></i>
                            <span>Kembali ke Dashboard</span>
                        </a>
                        <h2 class="text-3xl font-extrabold tracking-tight mb-2 flex items-center justify-center md:justify-start gap-3">
                            <span class="text-4xl"></span> Rekap Nilai
                        </h2>
                        <p class="text-blue-300 text-sm font-medium max-w-lg leading-relaxed">
                            Pantau perkembangan nilai tugas, kuis, dan ulangan harian siswa dalam satu tampilan matriks terpadu.
                        </p>
                    </div>

                    
                    <?php if(($selectedClassId ?? false) && ($selectedSubjectId ?? false) && isset($assignments) && $assignments->isNotEmpty()): ?>
                        <div class="flex flex-col items-center md:items-end gap-2">
                            <div class="flex flex-wrap justify-center gap-3">
                                <a href="<?php echo e(route('lms.grades.export', ['class_id' => $selectedClassId, 'subject_id' => $selectedSubjectId])); ?>" class="btn-export px-5 py-3 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold rounded-xl shadow-lg transition-all flex items-center gap-2 group">
                                    <i class="ph-bold ph-microsoft-excel-logo text-xl"></i>
                                    <span>Export Excel</span>
                                </a>
                                <a href="<?php echo e(route('lms.grades.print', ['class_id' => $selectedClassId, 'subject_id' => $selectedSubjectId])); ?>" target="_blank" class="btn-print px-5 py-3 bg-white/10 hover:bg-white/20 text-white text-sm font-bold rounded-xl shadow-lg backdrop-blur-sm border border-white/10 transition-all flex items-center gap-2">
                                    <i class="ph-bold ph-printer text-xl"></i>
                                    <span>Cetak PDF</span>
                                </a>
                            </div>
                            
                            <?php if($assignments->count() > 10): ?>
                                <span class="text-xs text-amber-200 mt-1 flex items-center gap-1 font-medium bg-amber-900/30 px-3 py-1 rounded-full border border-amber-500/30">
                                    <i class="ph-bold ph-info"></i> PDF dibatasi 10 tugas pertama
                                </span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm mb-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-slate-50 rounded-bl-full -mr-10 -mt-10 opacity-50 pointer-events-none"></div>
                
                <form action="<?php echo e(route('lms.grades.index')); ?>" method="GET" class="relative z-10" id="filterForm">
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-5 items-end">
                        
                        <div class="w-full">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Pilih Kelas</label>
                            <div class="relative">
                                <select name="class_id" class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold text-slate-700 focus:ring-blue-500 focus:border-blue-500 h-12 px-4 appearance-none" required>
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
                                <select name="subject_id" class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold text-slate-700 focus:ring-blue-500 focus:border-blue-500 h-12 px-4 appearance-none" required>
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
                                <select name="period" class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold text-slate-700 focus:ring-blue-500 focus:border-blue-500 h-12 px-4 appearance-none" required>
                                    <option value="semester" <?php echo e(($selectedPeriod ?? 'semester') == 'semester' ? 'selected' : ''); ?>>Rekap Semester (Rapor)</option>
                                    <option value="monthly" <?php echo e(($selectedPeriod ?? '') == 'monthly' ? 'selected' : ''); ?>>Bulanan (Bulan Ini)</option>
                                    <option value="weekly" <?php echo e(($selectedPeriod ?? '') == 'weekly' ? 'selected' : ''); ?>>Mingguan (Minggu Ini)</option>
                                    <option value="daily" <?php echo e(($selectedPeriod ?? '') == 'daily' ? 'selected' : ''); ?>>Harian (Hari Ini)</option>
                                </select>
                                <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-500"><i class="ph-bold ph-caret-down"></i></div>
                            </div>
                        </div>

                        <div class="w-full">
                            <button type="submit" class="w-full px-8 py-3 bg-blue-900 text-white font-bold rounded-xl hover:bg-slate-900 transition-all shadow-lg shadow-blue-900/20 flex items-center justify-center gap-2 h-12">
                                <i class="ph-bold ph-magnifying-glass text-lg"></i>
                                <span>Tampilkan</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            
            <?php if(($selectedClassId ?? false) && ($selectedSubjectId ?? false)): ?>
                <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                    <?php if(($assignments ?? collect())->isEmpty()): ?>
                        
                        <div class="p-16 text-center flex flex-col items-center">
                            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-6 animate-pulse">
                                <i class="ph-duotone ph-clipboard-text text-4xl text-slate-300"></i>
                            </div>
                            <h3 class="text-xl font-black text-slate-800 mb-2">Belum Ada Tugas</h3>
                            <p class="text-slate-500 text-sm max-w-sm mx-auto leading-relaxed">
                                Belum ada tugas atau kuis yang dibuat untuk kelas dan mata pelajaran ini. Silakan buat tugas terlebih dahulu.
                            </p>
                            <a href="<?php echo e(route('lms.assignments.create')); ?>" class="mt-6 px-6 py-2.5 bg-blue-50 text-blue-600 font-bold rounded-xl hover:bg-blue-100 transition flex items-center gap-2">
                                <i class="ph-bold ph-plus"></i> Buat Tugas
                            </a>
                        </div>
                    <?php else: ?>
                        
                        <div class="overflow-x-auto custom-scrollbar pb-2">
                            <table class="w-full text-sm text-left border-collapse">
                                
                                <thead class="bg-slate-50 text-slate-500 uppercase font-bold text-xs tracking-wider">
                                    <tr>
                                        
                                        <th class="px-4 py-4 border-b border-slate-200 sticky left-0 bg-slate-50 z-20 min-w-[3rem] max-w-[3rem] text-center border-r border-slate-100">No</th>
                                        <th class="px-4 py-4 border-b border-slate-200 sticky left-[3rem] bg-slate-50 z-20 min-w-[220px] border-r border-slate-100 shadow-[4px_0_12px_-4px_rgba(0,0,0,0.05)]">Nama Siswa</th>
                                        
                                        <!-- Loop Judul Tugas (Kolom) -->
                                        <?php $__currentLoopData = $assignments ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <th class="px-4 py-4 border-b border-slate-200 text-center min-w-[130px] group relative border-r border-slate-100 hover:bg-blue-50/50 transition-colors">
                                                <div class="flex flex-col items-center gap-1">
                                                    <span class="block truncate w-32 cursor-help font-bold text-blue-600" title="<?php echo e($task->title); ?>">
                                                        <?php echo e(Str::limit($task->title, 15)); ?>

                                                    </span>
                                                    <div class="flex items-center gap-1">
                                                        <span class="text-[9px] text-slate-400 font-medium bg-white border border-slate-100 px-1.5 rounded"><?php echo e($task->created_at->format('d/m')); ?></span>
                                                        <span class="text-[9px] px-1.5 py-0.5 rounded text-white font-bold uppercase <?php echo e($task->assignment_type == 'quiz' ? 'bg-purple-400' : 'bg-blue-400'); ?>">
                                                            <?php echo e($task->assignment_type == 'quiz' ? 'Kuis' : 'Tugas'); ?>

                                                        </span>
                                                    </div>
                                                </div>
                                            </th>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                        <th class="px-4 py-4 border-b border-slate-200 text-center bg-blue-50/50 text-blue-800 min-w-[90px] border-r border-slate-100">Total</th>
                                        
                                        <th class="px-4 py-4 border-b border-slate-200 text-center <?php echo e(($selectedPeriod ?? 'semester') == 'semester' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-emerald-50/50 text-emerald-800'); ?> min-w-[100px]">
                                            <?php echo e(($selectedPeriod ?? 'semester') == 'semester' ? 'Nilai Rapor' : 'Rata-rata'); ?>

                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50 bg-white">
                                    <?php $__currentLoopData = $students ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="hover:bg-slate-50 transition-colors group">
                                            
                                            <td class="px-4 py-3 border-r border-slate-50 sticky left-0 min-w-[3rem] max-w-[3rem] bg-white group-hover:bg-slate-50 transition-colors text-center text-slate-400 font-medium z-10">
                                                <?php echo e($index + 1); ?>

                                            </td>
                                            
                                            
                                            <td class="px-4 py-3 border-r border-slate-100 sticky left-[3rem] bg-white group-hover:bg-slate-50 transition-colors z-10 shadow-[4px_0_12px_-4px_rgba(0,0,0,0.05)]">
                                                <div class="flex flex-col">
                                                    <span class="font-bold text-slate-700 text-sm"><?php echo e($student->name); ?></span>
                                                    <span class="text-[10px] text-slate-400 font-mono"><?php echo e($student->nisn ?? $student->student_id ?? '-'); ?></span>
                                                </div>
                                            </td>

                                            <!-- Loop Nilai per Tugas -->
                                            <?php $__currentLoopData = $assignments ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                
                                                <?php $score = $gradeBook[$student->id][$task->id] ?? null; ?>
                                                
                                                <td class="px-4 py-3 text-center border-r border-slate-50">
                                                    <?php if($score !== null): ?>
                                                        <span class="inline-flex w-10 h-8 items-center justify-center rounded-lg text-xs font-bold border 
                                                            <?php echo e($score < 70 ? 'bg-rose-50 text-rose-600 border-rose-100' : 'bg-emerald-50 text-emerald-600 border-emerald-100'); ?>">
                                                            <?php echo e($score); ?>

                                                        </span>
                                                    <?php else: ?>
                                                        <span class="text-slate-300 text-lg" title="Belum dinilai">-</span>
                                                    <?php endif; ?>
                                                </td>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                            
                                            
                                            <td class="px-4 py-3 text-center border-r border-slate-100 bg-blue-50/30 font-bold text-blue-700">
                                                <?php echo e($student->total_score ?? 0); ?>

                                            </td>
                                            
                                            
                                            <?php
                                                $average = $student->average_score ?? 0;
                                                $isSemester = ($selectedPeriod ?? 'semester') == 'semester';
                                                $avgColor = $average > 0 && $average < 70 ? 'text-rose-600' : 'text-emerald-600';
                                            ?>
                                            <td class="px-4 py-3 text-center font-black <?php echo e($isSemester ? 'bg-indigo-50/50 text-indigo-700 text-base border-l-2 border-indigo-400' : 'bg-emerald-50/30 ' . $avgColor); ?>">
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
                
                <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden relative">
                    <!-- Dekorasi Background Titik-titik Samar -->
                    <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:16px_16px] opacity-40"></div>
                    
                    <div class="p-12 md:p-16 text-center relative z-10 flex flex-col items-center">
                        <div class="w-24 h-24 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mb-6 shadow-inner ring-8 ring-white">
                            <i class="ph-duotone ph-list-magnifying-glass text-5xl"></i>
                        </div>
                        
                        <h3 class="text-2xl font-extrabold text-slate-800 mb-3">Mulai Pantau Nilai Siswa</h3>
                        <p class="text-slate-500 max-w-lg mx-auto mb-10 leading-relaxed text-sm">
                            Silakan tentukan <b>Kelas</b> dan <b>Mata Pelajaran</b> pada panel filter di atas untuk melihat matriks rekapitulasi nilai secara lengkap.
                        </p>

                        <!-- Quick Guide Steps (Panduan 3 Langkah) -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto w-full text-left">
                            <!-- Step 1 -->
                            <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm relative overflow-hidden group hover:border-blue-200 hover:shadow-md transition-all">
                                <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-blue-50 to-blue-100 rounded-bl-full -mr-10 -mt-10 opacity-80 group-hover:scale-110 transition-transform duration-300"></div>
                                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 font-black mb-4 border border-blue-100 group-hover:bg-blue-600 group-hover:text-white transition-colors">1</div>
                                <h4 class="font-bold text-slate-800 mb-2">Pilih Filter</h4>
                                <p class="text-xs text-slate-500 leading-relaxed">Pilih kelas dan mata pelajaran yang Anda ampu dari menu dropdown di atas.</p>
                            </div>
                            <!-- Step 2 -->
                            <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm relative overflow-hidden group hover:border-emerald-200 hover:shadow-md transition-all">
                                <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-bl-full -mr-10 -mt-10 opacity-80 group-hover:scale-110 transition-transform duration-300"></div>
                                <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 font-black mb-4 border border-emerald-100 group-hover:bg-emerald-600 group-hover:text-white transition-colors">2</div>
                                <h4 class="font-bold text-slate-800 mb-2">Tinjau Matriks</h4>
                                <p class="text-xs text-slate-500 leading-relaxed">Pantau nilai tugas, kuis, beserta kalkulasi total dan rata-rata secara otomatis.</p>
                            </div>
                            <!-- Step 3 -->
                            <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm relative overflow-hidden group hover:border-purple-200 hover:shadow-md transition-all">
                                <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-purple-50 to-purple-100 rounded-bl-full -mr-10 -mt-10 opacity-80 group-hover:scale-110 transition-transform duration-300"></div>
                                <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600 font-black mb-4 border border-purple-100 group-hover:bg-purple-600 group-hover:text-white transition-colors">3</div>
                                <h4 class="font-bold text-slate-800 mb-2">Cetak & Export</h4>
                                <p class="text-xs text-slate-500 leading-relaxed">Unduh data dalam format Microsoft Excel atau cetak langsung menjadi dokumen PDF.</p>
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
                            allowOutsideClick: true,
                            didOpen: () => { Swal.showLoading(); },
                            // PERBAIKAN: Menggunakan class standar tailwind
                            customClass: { popup: 'rounded-3xl' }
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
                        timer: 4000, // Diperpanjang sedikit agar terbaca
                        timerProgressBar: true,
                        customClass: { popup: 'rounded-xl' }
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
                        customClass: { popup: 'rounded-xl' }
                    });
                });
            }

            // 4. Session Messages
            <?php if(session('success')): ?>
                Swal.fire({ icon: 'success', title: 'Berhasil', text: "<?php echo e(session('success')); ?>", customClass: { popup: 'rounded-3xl' } });
            <?php endif; ?>
            <?php if(session('error')): ?>
                Swal.fire({ icon: 'error', title: 'Oops...', text: "<?php echo e(session('error')); ?>", customClass: { popup: 'rounded-3xl' } });
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