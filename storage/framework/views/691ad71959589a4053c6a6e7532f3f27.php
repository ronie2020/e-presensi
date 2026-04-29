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
            <?php echo e(__('Materi Pelajaran')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>

    <div class="py-8 sm:py-10 font-sans text-elevate-dark bg-elevate-surface min-h-screen relative overflow-hidden pb-20">
        
        
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            
            <div class="animate-enter relative rounded-[2rem] bg-gradient-to-r from-elevate-accent via-elevate-peach-light to-elevate-peach p-8 md:p-10 mb-8 text-elevate-dark shadow-xl shadow-elevate-accent/20 overflow-hidden border border-white/60 group">
                
                
                <div class="absolute -top-10 -left-10 w-56 h-56 bg-elevate-primary/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-elevate-peach/40 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-xl"></div>

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-8">
                    <div class="text-center md:text-left">
                        <a href="<?php echo e(route('dashboard')); ?>" class="group/btn bg-white/60 hover:bg-white text-elevate-dark px-5 py-3 rounded-xl font-bold text-sm backdrop-blur-md border border-white/60 transition-all flex items-center gap-2 shadow-sm w-fit mb-4 mx-auto xl:mx-0 active:scale-95">
                            <i class="ph-bold ph-arrow-left text-lg group-hover/btn:-translate-x-1 transition-transform"></i>
                            <span>Kembali ke Dashboard</span>
                        </a>
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/60 border border-white/50 text-elevate-dark text-[10px] font-black uppercase tracking-widest mb-3 backdrop-blur-md shadow-sm">
                            <i class="ph-fill ph-chalkboard-teacher"></i> Area Guru
                        </div>
                        <h2 class="text-3xl md:text-4xl font-black tracking-tight mb-2 flex items-center justify-center md:justify-start gap-3 text-elevate-dark">
                            Kelola Materi
                        </h2>
                        <p class="text-elevate-dark/80 text-sm md:text-base font-medium max-w-lg leading-relaxed">
                            Bagikan bahan ajar digital (Dokumen, Video, Link) kepada siswa untuk mendukung kegiatan belajar mengajar.
                        </p>
                    </div>
                    
                    
                    <a href="<?php echo e(route('lms.materials.create')); ?>" class="w-full md:w-auto group/add bg-elevate-dark text-white px-7 py-4 rounded-2xl font-bold text-sm shadow-lg shadow-elevate-dark/30 hover:bg-elevate-primary transition-all duration-300 flex items-center justify-center gap-3 active:scale-95 border border-transparent">
                        <div class="bg-white/20 text-white w-8 h-8 rounded-xl flex items-center justify-center group-hover/add:bg-white group-hover/add:text-elevate-primary transition-colors">
                            <i class="ph-bold ph-plus"></i>
                        </div>
                        <span>Upload Materi Baru</span>
                    </a>
                </div>
            </div>

            
            <div class="animate-enter mb-10 bg-white p-5 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/40 flex flex-col md:flex-row gap-4" style="animation-delay: 100ms">
                <form action="<?php echo e(route('lms.materials.index')); ?>" method="GET" class="flex-1 flex flex-col md:flex-row gap-4 w-full">
                    <div class="relative flex-1 group">
                        <div class="absolute inset-y-0 left-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-elevate-primary transition-colors"><i class="ph-bold ph-magnifying-glass text-lg"></i></div>
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari judul materi..." class="w-full pl-12 pr-5 h-14 rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:ring-elevate-accent/30 focus:border-elevate-accent text-sm font-bold text-elevate-dark transition-all shadow-sm">
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                        <div class="relative w-full sm:w-56 group">
                            <select name="subject" class="w-full h-14 rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:ring-elevate-accent/30 focus:border-elevate-accent text-sm font-bold text-elevate-dark transition-all appearance-none px-5 shadow-sm cursor-pointer">
                                <option value="">Semua Mapel</option>
                                <?php if(isset($subjects)): ?>
                                    <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($sub->id); ?>" <?php echo e(request('subject') == $sub->id ? 'selected' : ''); ?>><?php echo e($sub->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </select>
                            <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-elevate-primary transition-colors"><i class="ph-bold ph-caret-down"></i></div>
                        </div>
                        <button type="submit" class="h-14 px-8 bg-elevate-dark hover:bg-elevate-primary text-white font-bold rounded-2xl transition-all shadow-lg shadow-elevate-dark/20 text-sm active:scale-95 border border-transparent w-full sm:w-auto flex justify-center items-center gap-2">
                            <i class="ph-bold ph-funnel text-lg"></i> Filter
                        </button>
                    </div>
                </form>
            </div>

            
            <?php if($materials->count() > 0): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php $__currentLoopData = $materials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $material): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        
                        <?php
                            $realType = $material->type;
                            $realPath = $material->file_path;
                            $realLink = $material->video_link;
                            
                            // Cek lampiran jika data utama kosong
                            if (empty($realPath) && empty($realLink) && $material->attachments->isNotEmpty()) {
                                $firstAtt = $material->attachments->first();
                                $realPath = $firstAtt->file_path;
                                
                                if ($firstAtt->file_type == 'file') {
                                    $realType = 'document';
                                } elseif ($firstAtt->file_type == 'video') {
                                    $realType = 'video';
                                    $realLink = $firstAtt->file_path; 
                                } else {
                                    $realType = 'link';
                                    $realLink = $firstAtt->file_path;
                                }
                            }

                            $finalUrl = '#';
                            $targetAttr = '';
                            if ($realType == 'document' && !empty($realPath)) {
                                $cleanPath = str_replace(['public/', 'public\\'], '', $realPath);
                                $finalUrl = asset('storage/' . $cleanPath);
                                $targetAttr = '_blank';
                            } elseif (($realType == 'video' || $realType == 'link') && !empty($realLink)) {
                                $finalUrl = $realLink;
                                $targetAttr = '_blank';
                            }

                            // Konfigurasi Ikon Tipe File
                            $typeConfig = match($realType) {
                                'document' => ['icon' => 'ph-file-pdf', 'label' => 'Dokumen'],
                                'video' => ['icon' => 'ph-youtube-logo', 'label' => 'Video'],
                                default => ['icon' => 'ph-link', 'label' => 'Link']
                            };
                        ?>

                        
                        <?php
                            $subjectName = strtolower($material->subject->name ?? 'umum');
                            $theme = ['bg' => 'bg-elevate-soft/50', 'text' => 'text-elevate-primary', 'border' => 'border-slate-200', 'light' => 'bg-elevate-soft', 'ring' => 'hover:border-elevate-accent/50'];

                            if (str_contains($subjectName, 'indonesia') || str_contains($subjectName, 'inggris') || str_contains($subjectName, 'jawa') || str_contains($subjectName, 'pkn') || str_contains($subjectName, 'pancasila')) {
                                $theme = ['bg' => 'bg-[#FDE7E9]', 'text' => 'text-[#D13438]', 'border' => 'border-[#F4C3C9]', 'light' => 'bg-[#FDE7E9]', 'ring' => 'hover:border-[#F4C3C9]'];
                            } elseif (str_contains($subjectName, 'ipa') || str_contains($subjectName, 'biologi') || str_contains($subjectName, 'kimia') || str_contains($subjectName, 'alam') || str_contains($subjectName, 'pjok') || str_contains($subjectName, 'olahraga')) {
                                $theme = ['bg' => 'bg-[#DFF6DD]', 'text' => 'text-[#107C10]', 'border' => 'border-[#B7DFB9]', 'light' => 'bg-[#DFF6DD]', 'ring' => 'hover:border-[#B7DFB9]'];
                            } elseif (str_contains($subjectName, 'ips') || str_contains($subjectName, 'sejarah') || str_contains($subjectName, 'geografi') || str_contains($subjectName, 'ekonomi')) {
                                $theme = ['bg' => 'bg-[#FFEFD6]', 'text' => 'text-[#D83B01]', 'border' => 'border-[#FFD8A8]', 'light' => 'bg-[#FFEFD6]', 'ring' => 'hover:border-[#FFD8A8]'];
                            }
                        ?>

                        
                        <div class="animate-enter group relative bg-white rounded-[2rem] shadow-xl shadow-slate-200/40 flex flex-col h-full border border-slate-100 transition-all duration-300 hover:-translate-y-1 <?php echo e($theme['ring']); ?>" style="animation-delay: <?php echo e(($index + 1) * 100); ?>ms">
                            
                            
                            <div class="p-6 md:p-8 h-full flex flex-col relative overflow-hidden">
                                
                                
                                <div class="absolute -right-10 -top-10 w-40 h-40 rounded-full <?php echo e($theme['bg']); ?> opacity-50 group-hover:scale-150 transition-transform duration-700 pointer-events-none blur-xl"></div>

                                
                                <div class="flex justify-between items-start mb-5 relative z-10">
                                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-3xl shadow-sm border <?php echo e($theme['border']); ?> <?php echo e($theme['bg']); ?> <?php echo e($theme['text']); ?> group-hover:scale-110 transition-transform duration-300">
                                        <i class="ph-duotone <?php echo e($typeConfig['icon']); ?>"></i>
                                    </div>
                                    
                                    <div class="flex flex-col items-end gap-1.5">
                                        
                                        <span class="px-3 py-1.5 rounded-lg bg-white border border-slate-100 text-slate-500 text-[10px] font-black uppercase tracking-wider shadow-sm">
                                            <?php echo e($typeConfig['label']); ?>

                                        </span>
                                        <!-- Indikator Jumlah Lampiran -->
                                        <?php if($material->attachments->count() > 1): ?>
                                            <span class="text-[10px] font-bold text-slate-400 bg-white border border-slate-100 px-3 py-1 rounded-lg shadow-sm">
                                                +<?php echo e($material->attachments->count() - 1); ?> File Lain
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                
                                <div class="mb-5 relative z-10">
                                    <h3 class="font-black text-xl text-elevate-dark group-hover:text-elevate-primary transition-colors line-clamp-2 leading-snug min-h-[3.5rem]" title="<?php echo e($material->title); ?>">
                                        <?php echo e($material->title); ?>

                                    </h3>
                                    
                                    
                                    <div class="flex flex-wrap gap-2 mt-3">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold border <?php echo e($theme['bg']); ?> <?php echo e($theme['text']); ?> <?php echo e($theme['border']); ?> shadow-sm">
                                            <i class="ph-fill ph-book-bookmark"></i>
                                            <?php echo e($material->subject->name ?? 'Mapel Umum'); ?>

                                        </span>
                                        
                                        
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-white text-slate-500 border border-slate-200 shadow-sm" 
                                              title="<?php echo e($material->is_bulk ? 'Materi ini dibagikan ke beberapa kelas sekaligus' : 'Materi khusus satu kelas'); ?>">
                                            <i class="ph-fill ph-users"></i>
                                            <?php if($material->is_bulk): ?>
                                                Semua Kelas <?php echo e($material->target_grade ?? ''); ?> (<?php echo e($material->total_classes); ?>)
                                            <?php else: ?>
                                                <?php echo e($material->schoolClass->name ?? 'Semua Kelas'); ?>

                                            <?php endif; ?>
                                        </span>
                                    </div>
                                </div>

                                
                                <div class="relative z-10 bg-elevate-soft/30 rounded-xl p-4 border border-slate-100 mb-6 flex-grow">
                                    <p class="text-sm text-slate-500 line-clamp-3 leading-relaxed font-medium italic">
                                        "<?php echo e($material->resume ?? $material->description ?? 'Tidak ada deskripsi tambahan.'); ?>"
                                    </p>
                                </div>

                                
                                <div class="pt-5 border-t border-slate-100 mt-auto flex items-center justify-between relative z-10">
                                    <div class="text-xs font-bold text-slate-400 flex items-center gap-1.5">
                                        <i class="ph-fill ph-clock"></i> <?php echo e($material->created_at->diffForHumans()); ?>

                                    </div>
                                    
                                    <div class="flex items-center gap-2">
                                        
                                        <a href="<?php echo e($finalUrl); ?>" target="<?php echo e($targetAttr); ?>" class="w-12 h-12 rounded-2xl bg-elevate-dark text-white hover:bg-elevate-primary transition-all flex items-center justify-center active:scale-95 shadow-md shadow-elevate-dark/20" title="Buka / Download">
                                            <?php if($realType == 'video' || $realType == 'link'): ?>
                                                <i class="ph-bold ph-arrow-square-out text-xl"></i>
                                            <?php else: ?>
                                                <i class="ph-bold ph-download-simple text-xl"></i>
                                            <?php endif; ?>
                                        </a>

                                        
                                        <a href="<?php echo e(route('lms.materials.edit', $material->id)); ?>" class="w-12 h-12 rounded-2xl bg-white text-[#D83B01] hover:bg-[#FFEFD6] transition-all flex items-center justify-center active:scale-95 border border-[#FFD8A8] shadow-sm" title="Edit Materi">
                                            <i class="ph-bold ph-pencil-simple text-xl"></i>
                                        </a>

                                        
                                        <form action="<?php echo e(route('lms.materials.destroy', $material->id)); ?>" method="POST" class="form-delete-material">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <button type="button" class="btn-delete w-12 h-12 rounded-2xl bg-white text-[#D13438] hover:bg-[#FDE7E9] transition-all flex items-center justify-center active:scale-95 border border-[#F4C3C9] shadow-sm" title="Hapus Materi">
                                                <i class="ph-bold ph-trash text-xl"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                
                <div class="mt-10 animate-enter px-4 flex justify-center" style="animation-delay: 500ms">
                    <?php echo e($materials->links()); ?>

                </div>
            <?php else: ?>
                
                <div class="animate-enter bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200 p-16 flex flex-col items-center justify-center text-center group hover:border-elevate-primary transition-colors" style="animation-delay: 200ms">
                    <div class="w-24 h-24 bg-elevate-soft rounded-full flex items-center justify-center text-elevate-primary mb-6 group-hover:bg-elevate-primary group-hover:text-white transition-all duration-500 border border-slate-100 shadow-sm">
                        <i class="ph-duotone ph-books text-5xl"></i>
                    </div>
                    <h3 class="font-black text-elevate-dark text-2xl mb-2"><?php echo e(request('search') ? 'Materi Tidak Ditemukan' : 'Belum Ada Materi'); ?></h3>
                    <p class="text-slate-500 text-sm max-w-md mx-auto leading-relaxed font-medium mb-8">
                        <?php echo e(request('search') ? 'Coba ubah kata kunci pencarian atau filter mapel Anda.' : 'Anda belum mengunggah materi pelajaran apapun. Mulailah berbagi ilmu.'); ?>

                    </p>
                    <a href="<?php echo e(route('lms.materials.create')); ?>" class="px-8 py-4 bg-elevate-dark text-white font-bold rounded-2xl hover:bg-elevate-primary transition-colors shadow-lg shadow-elevate-dark/30 flex items-center gap-2 active:scale-95 border border-transparent text-sm">
                        <i class="ph-bold ph-plus text-lg"></i> <?php echo e(request('search') ? 'Upload Materi Baru' : 'Tambah Materi Pertama'); ?>

                    </a>
                </div>
            <?php endif; ?>

        </div>
    </div>

    
    <?php $__env->startPush('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Handler Tombol Hapus
            const deleteButtons = document.querySelectorAll('.btn-delete');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault(); 
                    const form = this.closest('.form-delete-material');

                    Swal.fire({
                        title: 'Yakin Hapus Materi?',
                        text: "Data yang dihapus tidak bisa dikembalikan.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#D13438',
                        cancelButtonColor: '#94a3b8',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                        customClass: {
                            popup: 'rounded-[2rem] font-sans border border-slate-100 shadow-2xl',
                            title: 'text-xl font-black text-elevate-dark',
                            confirmButton: 'bg-[#D13438] text-white px-6 py-3 rounded-xl text-sm font-bold shadow-sm border border-transparent',
                            cancelButton: 'bg-slate-100 text-slate-600 px-6 py-3 rounded-xl text-sm font-bold hover:bg-slate-200 border border-transparent'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            // Handler Flash Message Success
            <?php if(session('success')): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "<?php echo e(session('success')); ?>",
                    showConfirmButton: false,
                    timer: 2500,
                    customClass: {
                        popup: 'rounded-[2rem] font-sans border border-slate-100 shadow-2xl'
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
<?php endif; ?><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/lms/materials/index.blade.php ENDPATH**/ ?>