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
            <?php echo e(__('Penilaian Tugas')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-8 font-sans text-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <div class="relative rounded-[2rem] bg-gray-900 bg-gradient-to-br from-slate-900 via-blue-900 to-blue-800 p-8 mb-8 text-white shadow-xl shadow-blue-900/30 overflow-hidden border border-white/10">
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        
                        <div class="flex flex-wrap items-center gap-2 mb-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider bg-white/10 border border-white/10 text-blue-100 backdrop-blur-sm">
                                <i class="ph-bold ph-tag mr-1.5"></i>
                                <?php echo e(str_replace('_', ' ', $assignment->assignment_type)); ?>

                            </span>
                            
                            
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider bg-emerald-500/20 border border-emerald-400/30 text-emerald-100 backdrop-blur-sm">
                                <i class="ph-bold ph-users-three mr-1.5"></i>
                                <?php if($assignment->is_bulk): ?>
                                    Semua Kelas <?php echo e($assignment->target_grade); ?>

                                <?php else: ?>
                                    <?php echo e($assignment->schoolClass->name ?? 'Semua Kelas'); ?>

                                <?php endif; ?>
                            </span>
                        </div>

                        <h1 class="text-3xl font-extrabold tracking-tight mb-2 text-white leading-tight">
                            <?php echo e($assignment->title); ?>

                        </h1>
                        
                        <div class="flex flex-wrap items-center gap-4 text-blue-200 text-sm font-medium">
                            <span class="flex items-center gap-1.5 bg-white/5 px-3 py-1.5 rounded-lg border border-white/5 hover:bg-white/10 transition">
                                <i class="ph-bold ph-book-open text-blue-400"></i> <?php echo e($assignment->subject->name); ?>

                            </span>
                            <span class="flex items-center gap-1.5 bg-rose-500/10 px-3 py-1.5 rounded-lg border border-rose-500/20 text-rose-200">
                                <i class="ph-bold ph-clock text-rose-400"></i> Deadline: <?php echo e($assignment->deadline->format('d M Y, H:i')); ?>

                            </span>
                        </div>
                    </div>
                    
                    <a href="<?php echo e(route('lms.assignments.index')); ?>" class="group bg-white/10 hover:bg-white/20 text-white px-5 py-3 rounded-2xl font-bold text-sm backdrop-blur-sm border border-white/10 transition-all flex items-center gap-2 shadow-lg">
                        <i class="ph-bold ph-arrow-left text-lg group-hover:-translate-x-1 transition-transform"></i>
                        <span>Kembali</span>
                    </a>
                </div>
            </div>

            
            <?php
                $totalStudents = $allStudents->count();
                $submittedCount = $submissions->count();
                $pendingCount = $totalStudents - $submittedCount;
                $progressPercent = $totalStudents > 0 ? round(($submittedCount / $totalStudents) * 100) : 0;
            ?>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center justify-between group hover:border-blue-200 transition-colors">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Total Siswa</p>
                        <h3 class="text-3xl font-black text-slate-800"><?php echo e($totalStudents); ?></h3>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                        <i class="ph-duotone ph-student"></i>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center justify-between group hover:border-emerald-200 transition-colors">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Sudah Kumpul</p>
                        <div class="flex items-baseline gap-2">
                            <h3 class="text-3xl font-black text-emerald-600"><?php echo e($submittedCount); ?></h3>
                            <span class="text-xs font-bold text-emerald-500 bg-emerald-50 px-2 py-0.5 rounded-full"><?php echo e($progressPercent); ?>%</span>
                        </div>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                        <i class="ph-duotone ph-check-fat"></i>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center justify-between group hover:border-rose-200 transition-colors">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Belum Kumpul</p>
                        <h3 class="text-3xl font-black text-rose-500"><?php echo e($pendingCount); ?></h3>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                        <i class="ph-duotone ph-clock-countdown"></i>
                    </div>
                </div>
            </div>

            
            <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden">
                
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row justify-between items-center gap-4">
                    <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                        <i class="ph-fill ph-list-checks text-blue-600"></i> Daftar Pengumpulan
                    </h3>
                    
                    <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                        
                        <?php if($assignment->is_bulk || $allStudents->count() > 30): ?>
                            <div class="relative w-full sm:w-48">
                                <select id="classFilter" class="w-full pl-10 pr-8 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold focus:ring-blue-500 focus:border-blue-500 shadow-sm transition-all appearance-none cursor-pointer">
                                    <option value="">Semua Kelas</option>
                                    <?php $__currentLoopData = $allStudents->pluck('schoolClass.name')->unique()->sort(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $className): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($className); ?>"><?php echo e($className); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <i class="ph-bold ph-funnel absolute left-3.5 top-3 text-slate-400"></i>
                                <i class="ph-bold ph-caret-down absolute right-3 top-3 text-slate-400 pointer-events-none"></i>
                            </div>
                        <?php endif; ?>

                        
                        <div class="relative w-full sm:w-64">
                            <input type="text" id="tableSearch" placeholder="Cari nama siswa..." class="pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold focus:ring-blue-500 focus:border-blue-500 w-full shadow-sm transition-all">
                            <i class="ph-bold ph-magnifying-glass absolute left-3.5 top-3 text-slate-400"></i>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse" id="submissionsTable">
                        <thead class="bg-slate-50 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider w-1/4">Siswa</th>
                                
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Kelas</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Status</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider w-1/4">Lampiran</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Waktu</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Nilai</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider w-1/4">Feedback</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php $__currentLoopData = $allStudents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $submission = $submissions->where('student_id', $student->id)->first();
                                    $isLate = false;
                                    if($submission && $submission->submitted_at > $assignment->deadline) {
                                        $isLate = true;
                                    }
                                ?>

                                
                                <tr class="group hover:bg-slate-50/80 transition-colors student-row" data-class="<?php echo e($student->schoolClass->name ?? '-'); ?>">
                                    <!-- Kolom Siswa -->
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-100 to-indigo-100 text-blue-600 flex items-center justify-center font-bold text-xs border border-white shadow-sm shrink-0">
                                                <?php echo e(substr($student->name, 0, 2)); ?>

                                            </div>
                                            <div>
                                                <div class="font-bold text-slate-800 text-sm group-hover:text-blue-700 transition-colors"><?php echo e($student->name); ?></div>
                                                <div class="text-[10px] text-slate-400 font-mono mt-0.5"><?php echo e($student->nisn ?? 'NISN -'); ?></div>
                                            </div>
                                        </div>
                                    </td>

                                    
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                            <?php echo e($student->schoolClass->name ?? '-'); ?>

                                        </span>
                                    </td>

                                    <!-- Kolom Status -->
                                    <td class="px-6 py-4 text-center">
                                        <?php if($submission): ?>
                                            <?php if($isLate): ?>
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wide bg-amber-50 text-amber-600 border border-amber-100" title="Terlambat <?php echo e($submission->submitted_at->diffForHumans($assignment->deadline)); ?>">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Late
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wide bg-emerald-50 text-emerald-600 border border-emerald-100">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> On Time
                                                </span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wide bg-slate-100 text-slate-400 border border-slate-200">
                                                Belum
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Kolom Jawaban -->
                                    <td class="px-6 py-4">
                                        <?php if($submission): ?>
                                            <div class="flex flex-col gap-2">
                                                <?php if($submission->file_path): ?>
                                                    <a href="<?php echo e(asset('storage/'.$submission->file_path)); ?>" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 bg-white border border-slate-200 hover:border-blue-300 hover:text-blue-600 rounded-xl text-xs font-bold transition-all w-fit shadow-sm group/file">
                                                        <i class="ph-bold ph-file-text text-lg text-slate-400 group-hover/file:text-blue-500"></i>
                                                        File
                                                    </a>
                                                <?php endif; ?>

                                                <?php if($submission->student_note): ?>
                                                    <div class="bg-amber-50 p-2.5 rounded-xl border border-amber-100 text-xs text-amber-800 italic relative w-fit max-w-[200px]">
                                                        <i class="ph-fill ph-quotes text-amber-200 text-xl absolute -top-2 -left-1"></i>
                                                        <span class="relative z-10 font-medium">"<?php echo e(Str::limit($submission->student_note, 40)); ?>"</span>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if(!$submission->file_path && !$submission->student_note): ?>
                                                    <span class="text-xs text-slate-400 italic">Tanpa lampiran.</span>
                                                <?php endif; ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-slate-300 text-2xl ml-2"><i class="ph-duotone ph-minus-circle"></i></span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Kolom Waktu -->
                                    <td class="px-6 py-4">
                                        <?php if($submission): ?>
                                            <div class="flex flex-col">
                                                <span class="text-xs font-bold text-slate-700"><?php echo e($submission->submitted_at->format('d/m')); ?></span>
                                                <span class="text-[10px] font-mono text-slate-400"><?php echo e($submission->submitted_at->format('H:i')); ?></span>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-slate-300">-</span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- FORM PENILAIAN -->
                                    <?php if($submission): ?>
                                        <form action="<?php echo e(route('lms.submissions.grade', $submission->id)); ?>" method="POST" class="contents grade-form">
                                            <?php echo csrf_field(); ?>
                                            
                                            <!-- Input Nilai -->
                                            <td class="px-6 py-4 text-center">
                                                <input type="number" name="grade" min="0" max="100" 
                                                       value="<?php echo e($submission->grade); ?>" 
                                                       class="w-14 text-center rounded-xl border-slate-200 bg-white focus:border-blue-500 focus:ring-blue-500 text-sm font-black text-slate-800 h-10 shadow-sm transition-all focus:scale-110"
                                                       placeholder="-">
                                            </td>

                                            <!-- Input Feedback -->
                                            <td class="px-6 py-4">
                                                <input type="text" name="feedback" 
                                                       value="<?php echo e($submission->teacher_feedback); ?>" 
                                                       class="w-full min-w-[150px] rounded-xl border-slate-200 bg-white focus:border-blue-500 focus:ring-blue-500 text-xs h-10 px-3 placeholder:text-slate-300 transition-shadow focus:shadow-md font-medium text-slate-600"
                                                       placeholder="Feedback...">
                                            </td>

                                            <!-- Tombol Simpan -->
                                            <td class="px-6 py-4 text-right">
                                                <button type="submit" class="w-9 h-9 rounded-xl bg-blue-600 text-white hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-200 transition-all flex items-center justify-center shadow-md transform active:scale-90" title="Simpan Nilai">
                                                    <i class="ph-bold ph-floppy-disk text-lg"></i>
                                                </button>
                                            </td>
                                        </form>
                                    <?php else: ?>
                                        <td colspan="3" class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center gap-1 text-xs text-slate-400 font-medium bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100 border-dashed">
                                                <i class="ph-bold ph-hourglass"></i> Menunggu
                                            </span>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if($allStudents->count() == 0): ?>
                    <div class="text-center py-16 bg-slate-50/50">
                        <div class="w-16 h-16 bg-slate-100 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-3 text-3xl">
                            <i class="ph-duotone ph-users-three"></i>
                        </div>
                        <p class="text-slate-500 font-bold text-sm">Tidak ada siswa yang terdaftar.</p>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // 1. Logic Filter Kelas & Pencarian (Client Side)
            const classFilter = document.getElementById('classFilter');
            const searchInput = document.getElementById('tableSearch');
            const tableRows = document.querySelectorAll('.student-row');

            function filterTable() {
                const classValue = classFilter ? classFilter.value : '';
                const searchValue = searchInput ? searchInput.value.toLowerCase() : '';

                tableRows.forEach(row => {
                    const rowClass = row.getAttribute('data-class');
                    const rowText = row.textContent.toLowerCase();
                    
                    const matchesClass = classValue === '' || rowClass === classValue;
                    const matchesSearch = searchValue === '' || rowText.includes(searchValue);

                    if (matchesClass && matchesSearch) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            if (classFilter) classFilter.addEventListener('change', filterTable);
            if (searchInput) searchInput.addEventListener('keyup', filterTable);

            // 2. Loading State saat Simpan Nilai
            const gradeForms = document.querySelectorAll('.grade-form');
            gradeForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    Swal.fire({
                        title: 'Menyimpan...',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        didOpen: () => { Swal.showLoading(); },
                        customClass: { popup: 'rounded-xl border border-slate-100 bg-white shadow-lg' }
                    });
                });
            });

            // 3. Notifikasi Toast Sukses
            <?php if(session('success')): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: "<?php echo e(session('success')); ?>",
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    customClass: { popup: 'rounded-xl shadow-lg border border-emerald-100 bg-white' }
                });
            <?php endif; ?>
        });
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
<?php endif; ?><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/lms/assignments/submissions.blade.php ENDPATH**/ ?>