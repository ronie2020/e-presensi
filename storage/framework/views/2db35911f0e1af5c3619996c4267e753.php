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
            <?php echo e(__('Tugas & PR')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>

    <div class="py-6 md:py-10 font-sans text-slate-800 pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <div class="animate-enter relative rounded-[2.5rem] bg-gray-900 bg-gradient-to-br from-slate-900 via-blue-900 to-blue-800 p-8 md:p-10 mb-10 text-white shadow-2xl shadow-blue-900/20 overflow-hidden border border-white/10 group">
                
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl pointer-events-none group-hover:bg-blue-500/20 transition-all duration-700"></div>
                <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none group-hover:bg-indigo-500/20 transition-all duration-700"></div>

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-8">
                    <div class="text-center md:text-left">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 border border-blue-400/20 text-blue-300 text-[10px] font-black uppercase tracking-widest mb-3 backdrop-blur-md">
                            <i class="ph-fill ph-chalkboard-teacher"></i> Area Guru
                        </div>
                        <h2 class="text-3xl md:text-4xl font-black tracking-tight mb-2 flex items-center justify-center md:justify-start gap-3">
                            Manajemen Tugas
                        </h2>
                        <p class="text-slate-400 text-sm md:text-base font-medium max-w-lg leading-relaxed">
                            Buat tugas, kuis online, atau ulangan harian dan pantau hasil pengerjaan siswa secara real-time.
                        </p>
                    </div>
                    
                    
                    <a href="<?php echo e(route('lms.assignments.create')); ?>" class="w-full md:w-auto group bg-white text-blue-900 px-7 py-4 rounded-2xl font-bold text-sm shadow-xl shadow-blue-900/10 hover:bg-blue-50 hover:shadow-blue-900/20 transition-all duration-300 flex items-center justify-center gap-3 active:scale-95 border border-white/10">
                        <div class="bg-blue-100 text-blue-600 w-8 h-8 rounded-full flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <i class="ph-bold ph-plus"></i>
                        </div>
                        <span>Buat Tugas Baru</span>
                    </a>
                </div>
            </div>

            
            <?php if($assignments->count() > 0): ?>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <?php $__currentLoopData = $assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            // Tentukan Icon & Warna Dasar Berdasarkan Tipe
                            $iconType = 'ph-file-text';
                            $labelType = 'Tugas File';
                            if($task->assignment_type == 'quiz') { $iconType = 'ph-brain'; $labelType = 'Kuis Online'; }
                            if($task->assignment_type == 'link') { $iconType = 'ph-link'; $labelType = 'Tugas Link'; }
                            
                            // Cek Status Deadline
                            $isExpired = now() > $task->deadline;

                            // LOGIKA TEMA WARNA BERDASARKAN MAPEL
                            $subjectName = strtolower($task->subject->name ?? 'umum');
                            $theme = ['bg' => 'bg-slate-50', 'text' => 'text-slate-600', 'border' => 'border-slate-200', 'light' => 'bg-slate-100', 'ring' => 'ring-slate-100'];

                            if (str_contains($subjectName, 'matematika') || str_contains($subjectName, 'fisika')) {
                                $theme = ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200', 'light' => 'bg-blue-100', 'ring' => 'ring-blue-100'];
                            } elseif (str_contains($subjectName, 'indonesia') || str_contains($subjectName, 'inggris')) {
                                $theme = ['bg' => 'bg-rose-50', 'text' => 'text-rose-700', 'border' => 'border-rose-200', 'light' => 'bg-rose-100', 'ring' => 'ring-rose-100'];
                            } elseif (str_contains($subjectName, 'ipa') || str_contains($subjectName, 'biologi')) {
                                $theme = ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200', 'light' => 'bg-emerald-100', 'ring' => 'ring-emerald-100'];
                            } elseif (str_contains($subjectName, 'ips') || str_contains($subjectName, 'sejarah')) {
                                $theme = ['bg' => 'bg-orange-50', 'text' => 'text-orange-700', 'border' => 'border-orange-200', 'light' => 'bg-orange-100', 'ring' => 'ring-orange-100'];
                            } elseif (str_contains($subjectName, 'agama') || str_contains($subjectName, 'pai')) {
                                $theme = ['bg' => 'bg-teal-50', 'text' => 'text-teal-700', 'border' => 'border-teal-200', 'light' => 'bg-teal-100', 'ring' => 'ring-teal-100'];
                            } elseif (str_contains($subjectName, 'seni') || str_contains($subjectName, 'budaya')) {
                                $theme = ['bg' => 'bg-purple-50', 'text' => 'text-purple-700', 'border' => 'border-purple-200', 'light' => 'bg-purple-100', 'ring' => 'ring-purple-100'];
                            } elseif (str_contains($subjectName, 'informatika') || str_contains($subjectName, 'tik')) {
                                $theme = ['bg' => 'bg-cyan-50', 'text' => 'text-cyan-700', 'border' => 'border-cyan-200', 'light' => 'bg-cyan-100', 'ring' => 'ring-cyan-100'];
                            }
                        ?>

                        <div class="animate-enter group relative bg-white border border-slate-100 rounded-[2rem] p-1 shadow-sm hover:shadow-2xl hover:shadow-slate-200/50 transition-all duration-300 flex flex-col h-full hover:-translate-y-1 hover:border-transparent hover:ring-2 <?php echo e($theme['ring']); ?>" style="animation-delay: <?php echo e(($index + 1) * 100); ?>ms">
                            
                            
                            <div class="bg-white rounded-[1.8rem] p-6 h-full flex flex-col relative overflow-hidden">
                                
                                
                                <div class="absolute -right-10 -top-10 w-32 h-32 rounded-full <?php echo e($theme['bg']); ?> opacity-50 group-hover:scale-150 transition-transform duration-500 pointer-events-none"></div>

                                
                                <div class="flex justify-between items-start mb-4 relative z-10">
                                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-3xl shadow-sm border <?php echo e($theme['border']); ?> <?php echo e($theme['bg']); ?> <?php echo e($theme['text']); ?> group-hover:scale-110 transition-transform duration-300">
                                        <i class="ph-duotone <?php echo e($iconType); ?>"></i>
                                    </div>
                                    
                                    <div class="flex flex-col items-end gap-1">
                                        <?php if($isExpired): ?>
                                            <span class="bg-slate-100 text-slate-500 border border-slate-200 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider flex items-center gap-1">
                                                <i class="ph-bold ph-lock-key"></i> Ditutup
                                            </span>
                                        <?php else: ?>
                                            <span class="bg-rose-50 text-rose-600 border border-rose-100 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider flex items-center gap-1 animate-pulse">
                                                <i class="ph-bold ph-clock"></i> Aktif
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                
                                <div class="mb-4 relative z-10">
                                    <h3 class="font-bold text-lg text-slate-800 group-hover:text-blue-700 transition-colors line-clamp-1" title="<?php echo e($task->title); ?>">
                                        <?php echo e($task->title); ?>

                                    </h3>
                                    <p class="text-sm font-bold text-slate-400 mt-0.5"><?php echo e($task->subject->name); ?></p>
                                </div>

                                
                                <div class="grid grid-cols-2 gap-3 mb-6 relative z-10">
                                    
                                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Target</p>
                                        <div class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                                            <i class="ph-fill ph-users text-blue-500"></i>
                                            <?php if($task->is_bulk): ?>
                                                Semua Kelas <?php echo e($task->target_grade ?? ''); ?> (<?php echo e($task->total_classes); ?>)
                                            <?php else: ?>
                                                <?php echo e($task->schoolClass->name ?? 'Semua'); ?>

                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    
                                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Deadline</p>
                                        <div class="text-xs font-bold <?php echo e($isExpired ? 'text-rose-600' : 'text-slate-700'); ?> flex items-center gap-1.5">
                                            <i class="ph-fill ph-calendar-blank <?php echo e($isExpired ? 'text-rose-500' : 'text-slate-400'); ?>"></i>
                                            <?php echo e($task->deadline->format('d M, H:i')); ?>

                                        </div>
                                    </div>
                                </div>

                                
                                <div class="mb-6 relative z-10">
                                    <div class="flex justify-between text-xs font-bold text-slate-500 mb-2">
                                        <span>Total Pengumpulan</span>
                                        <span class="text-blue-600"><?php echo e($task->is_bulk ? $task->global_submissions_count : $task->submissions_count); ?> Siswa</span>
                                    </div>
                                    <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                        
                                        <div class="bg-blue-500 h-2 rounded-full" style="width: 5%"></div>
                                    </div>
                                </div>

                                
                                <div class="pt-4 border-t border-slate-100 mt-auto flex items-center justify-between gap-2 relative z-10">
                                    
                                    
                                    <a href="<?php echo e(route('lms.assignments.submissions', $task->id)); ?>" class="flex-1 bg-slate-800 hover:bg-slate-900 text-white px-4 py-2.5 rounded-xl font-bold text-sm transition-all shadow-md flex items-center justify-center gap-2 group/btn">
                                        <i class="ph-bold ph-list-checks text-lg text-yellow-400"></i>
                                        <span>Periksa</span>
                                    </a>

                                    
                                    <a href="<?php echo e(route('lms.assignments.edit', $task->id)); ?>" class="w-11 h-11 rounded-xl bg-white border border-amber-100 text-amber-500 hover:bg-amber-500 hover:text-white hover:shadow-lg hover:shadow-amber-200 transition-all flex items-center justify-center shadow-sm" title="Edit Tugas">
                                        <i class="ph-bold ph-pencil-simple text-lg"></i>
                                    </a>

                                    
                                    <form action="<?php echo e(route('lms.assignments.destroy', $task->id)); ?>" method="POST" class="form-delete-task">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button type="button" class="btn-delete w-11 h-11 rounded-xl bg-white border border-rose-100 text-rose-500 hover:bg-rose-500 hover:text-white hover:shadow-lg hover:shadow-rose-200 transition-all flex items-center justify-center shadow-sm" title="Hapus Tugas">
                                            <i class="ph-bold ph-trash text-lg"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                
                <div class="mt-10 animate-enter px-4" style="animation-delay: 500ms">
                    <?php echo e($assignments->links()); ?>

                </div>
            <?php else: ?>
                
                <div class="animate-enter bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200 p-12 flex flex-col items-center justify-center text-center group hover:border-blue-300 transition-colors" style="animation-delay: 200ms">
                    <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mb-6 group-hover:bg-blue-50 group-hover:text-blue-500 transition-all duration-500">
                        <i class="ph-duotone ph-clipboard-text text-5xl"></i>
                    </div>
                    <h3 class="font-black text-slate-800 text-xl mb-2">Belum Ada Tugas</h3>
                    <p class="text-slate-500 text-sm max-w-md mx-auto leading-relaxed mb-8">
                        Anda belum membuat tugas apapun. Mulailah dengan membuat tugas baru untuk dikerjakan siswa.
                    </p>
                    <a href="<?php echo e(route('lms.assignments.create')); ?>" class="px-8 py-3.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-600/30 hover:-translate-y-1 transform flex items-center gap-2 active:scale-95">
                        <i class="ph-bold ph-plus"></i> Buat Tugas Pertama
                    </a>
                </div>
            <?php endif; ?>

        </div>
    </div>

    
    <?php $__env->startPush('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // 1. Tombol Hapus dengan Konfirmasi
            const deleteButtons = document.querySelectorAll('.btn-delete');
            deleteButtons.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('.form-delete-task');
                    
                    Swal.fire({
                        title: 'Hapus Tugas Ini?',
                        text: "Data nilai dan pengumpulan siswa akan ikut terhapus permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e11d48', // Rose-600
                        cancelButtonColor: '#64748b', // Slate-500
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        customClass: {
                            popup: 'rounded-[2rem] font-sans',
                            title: 'text-xl font-bold text-slate-800',
                            htmlContainer: 'text-slate-500',
                            confirmButton: 'px-6 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-rose-200',
                            cancelButton: 'px-6 py-2.5 rounded-xl text-sm font-bold hover:bg-slate-100 text-slate-600'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            // 2. Notifikasi Toast Sukses
            <?php if(session('success')): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "<?php echo e(session('success')); ?>",
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    customClass: {
                        popup: 'rounded-xl border border-emerald-100 bg-white shadow-lg font-sans'
                    }
                });
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
<?php endif; ?><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/lms/assignments/index.blade.php ENDPATH**/ ?>