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
            <?php echo e(__('Materi Pelajaran')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>

    <div class="py-6 md:py-10 font-sans text-slate-800 pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <div class="animate-enter relative rounded-[2.5rem] bg-gray-900 bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900 p-8 md:p-10 mb-10 text-white shadow-2xl shadow-blue-900/20 overflow-hidden border border-white/10 group">
                
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl pointer-events-none group-hover:bg-blue-500/20 transition-all duration-700"></div>
                <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none group-hover:bg-indigo-500/20 transition-all duration-700"></div>

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-8">
                    <div class="text-center md:text-left">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 border border-blue-400/20 text-blue-300 text-[10px] font-black uppercase tracking-widest mb-3 backdrop-blur-md">
                            <i class="ph-fill ph-chalkboard-teacher"></i> Area Guru
                        </div>
                        <h2 class="text-3xl md:text-4xl font-black tracking-tight mb-2 flex items-center justify-center md:justify-start gap-3">
                            Kelola Materi
                        </h2>
                        <p class="text-slate-400 text-sm md:text-base font-medium max-w-lg leading-relaxed">
                            Bagikan bahan ajar digital (Dokumen, Video, Link) kepada siswa untuk mendukung kegiatan belajar mengajar.
                        </p>
                    </div>
                    
                    
                    <a href="<?php echo e(route('lms.materials.create')); ?>" class="w-full md:w-auto group bg-white text-slate-900 px-7 py-4 rounded-2xl font-bold text-sm shadow-xl shadow-blue-900/10 hover:bg-blue-50 hover:shadow-blue-900/20 transition-all duration-300 flex items-center justify-center gap-3 active:scale-95 border border-white/10">
                        <div class="bg-blue-100 text-blue-600 w-8 h-8 rounded-full flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <i class="ph-bold ph-plus"></i>
                        </div>
                        <span>Upload Materi Baru</span>
                    </a>
                </div>
            </div>

            
            <div class="animate-enter mb-10 bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex flex-col md:flex-row gap-4" style="animation-delay: 100ms">
                <form action="<?php echo e(route('lms.materials.index')); ?>" method="GET" class="flex-1 flex flex-col md:flex-row gap-4 w-full">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-magnifying-glass text-lg"></i></div>
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari judul materi..." class="w-full pl-11 pr-4 h-12 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:ring-blue-500 focus:border-blue-500 text-sm font-medium transition-colors">
                    </div>
                    <div class="flex gap-2">
                        <select name="subject" class="h-12 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:ring-blue-500 focus:border-blue-500 text-sm font-medium transition-colors w-full md:w-48">
                            <option value="">Semua Mapel</option>
                            
                            <?php if(isset($subjects)): ?>
                                <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($sub->id); ?>" <?php echo e(request('subject') == $sub->id ? 'selected' : ''); ?>><?php echo e($sub->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </select>
                        <button type="submit" class="h-12 px-6 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-xl transition text-sm">Filter</button>
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
                            $theme = ['bg' => 'bg-slate-50', 'text' => 'text-slate-600', 'border' => 'border-slate-200', 'light' => 'bg-slate-100', 'ring' => 'ring-slate-100'];

                            if (str_contains($subjectName, 'matematika') || str_contains($subjectName, 'fisika')) {
                                $theme = ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200', 'light' => 'bg-blue-100', 'ring' => 'ring-blue-100'];
                            } elseif (str_contains($subjectName, 'indonesia') || str_contains($subjectName, 'inggris') || str_contains($subjectName, 'jawa')) {
                                $theme = ['bg' => 'bg-rose-50', 'text' => 'text-rose-700', 'border' => 'border-rose-200', 'light' => 'bg-rose-100', 'ring' => 'ring-rose-100'];
                            } elseif (str_contains($subjectName, 'ipa') || str_contains($subjectName, 'biologi') || str_contains($subjectName, 'kimia') || str_contains($subjectName, 'alam')) {
                                $theme = ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200', 'light' => 'bg-emerald-100', 'ring' => 'ring-emerald-100'];
                            } elseif (str_contains($subjectName, 'ips') || str_contains($subjectName, 'sejarah') || str_contains($subjectName, 'geografi') || str_contains($subjectName, 'ekonomi')) {
                                $theme = ['bg' => 'bg-orange-50', 'text' => 'text-orange-700', 'border' => 'border-orange-200', 'light' => 'bg-orange-100', 'ring' => 'ring-orange-100'];
                            } elseif (str_contains($subjectName, 'agama') || str_contains($subjectName, 'pai') || str_contains($subjectName, 'quran')) {
                                $theme = ['bg' => 'bg-teal-50', 'text' => 'text-teal-700', 'border' => 'border-teal-200', 'light' => 'bg-teal-100', 'ring' => 'ring-teal-100'];
                            } elseif (str_contains($subjectName, 'seni') || str_contains($subjectName, 'budaya') || str_contains($subjectName, 'prakarya')) {
                                $theme = ['bg' => 'bg-purple-50', 'text' => 'text-purple-700', 'border' => 'border-purple-200', 'light' => 'bg-purple-100', 'ring' => 'ring-purple-100'];
                            } elseif (str_contains($subjectName, 'informatika') || str_contains($subjectName, 'tik') || str_contains($subjectName, 'komputer')) {
                                $theme = ['bg' => 'bg-cyan-50', 'text' => 'text-cyan-700', 'border' => 'border-cyan-200', 'light' => 'bg-cyan-100', 'ring' => 'ring-cyan-100'];
                            } elseif (str_contains($subjectName, 'pjok') || str_contains($subjectName, 'olahraga')) {
                                $theme = ['bg' => 'bg-lime-50', 'text' => 'text-lime-700', 'border' => 'border-lime-200', 'light' => 'bg-lime-100', 'ring' => 'ring-lime-100'];
                            } elseif (str_contains($subjectName, 'pkn') || str_contains($subjectName, 'pancasila')) {
                                $theme = ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'border' => 'border-red-200', 'light' => 'bg-red-100', 'ring' => 'ring-red-100'];
                            }
                        ?>

                        
                        <div class="animate-enter group relative bg-white border border-slate-100 rounded-[2rem] p-1.5 shadow-sm hover:shadow-2xl hover:shadow-slate-200/50 transition-all duration-300 flex flex-col h-full hover:-translate-y-1 hover:border-transparent hover:ring-2 <?php echo e($theme['ring']); ?>" style="animation-delay: <?php echo e(($index + 1) * 100); ?>ms">
                            
                            
                            <div class="bg-white rounded-[1.7rem] p-6 h-full flex flex-col relative overflow-hidden">
                                
                                
                                <div class="absolute -right-10 -top-10 w-32 h-32 rounded-full <?php echo e($theme['bg']); ?> opacity-50 group-hover:scale-150 transition-transform duration-500 pointer-events-none"></div>

                                
                                <div class="flex justify-between items-start mb-4 relative z-10">
                                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-3xl shadow-sm border <?php echo e($theme['border']); ?> <?php echo e($theme['bg']); ?> <?php echo e($theme['text']); ?> group-hover:scale-110 transition-transform duration-300">
                                        <i class="ph-duotone <?php echo e($typeConfig['icon']); ?>"></i>
                                    </div>
                                    
                                    <div class="flex flex-col items-end gap-1">
                                        
                                        <span class="px-2.5 py-1 rounded-lg bg-slate-50 text-slate-500 border border-slate-100 text-[10px] font-black uppercase tracking-wider">
                                            <?php echo e($typeConfig['label']); ?>

                                        </span>
                                        <!-- Indikator Jumlah Lampiran -->
                                        <?php if($material->attachments->count() > 1): ?>
                                            <span class="text-[10px] font-bold text-slate-400 bg-slate-50 px-2 py-0.5 rounded border border-slate-100">
                                                +<?php echo e($material->attachments->count() - 1); ?> File Lain
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                
                                <div class="mb-4 relative z-10">
                                    <h3 class="font-bold text-lg text-slate-800 group-hover:text-blue-700 transition-colors line-clamp-2 leading-snug min-h-[3rem]" title="<?php echo e($material->title); ?>">
                                        <?php echo e($material->title); ?>

                                    </h3>
                                    
                                    
                                    <div class="flex flex-wrap gap-2 mt-3">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold border <?php echo e($theme['bg']); ?> <?php echo e($theme['text']); ?> <?php echo e($theme['border']); ?>">
                                            <i class="ph-fill ph-book-bookmark"></i>
                                            <?php echo e($material->subject->name ?? 'Mapel Umum'); ?>

                                        </span>
                                        
                                        
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-50 text-slate-500 border border-slate-200" 
                                              title="<?php echo e($material->is_bulk ? 'Materi ini dibagikan ke beberapa kelas sekaligus' : 'Materi khusus satu kelas'); ?>">
                                            <i class="ph-fill ph-users"></i>
                                            <?php if($material->is_bulk): ?>
                                                Semua Kelas <?php echo e($material->target_grade ?? ''); ?> (<?php echo e($material->total_classes); ?> Rombel)
                                            <?php else: ?>
                                                <?php echo e($material->schoolClass->name ?? 'Semua Kelas'); ?>

                                            <?php endif; ?>
                                        </span>
                                    </div>
                                </div>

                                
                                <div class="relative z-10 bg-slate-50/50 rounded-xl p-3 border border-slate-100 mb-6 flex-grow">
                                    <p class="text-sm text-slate-500 line-clamp-3 leading-relaxed italic">
                                        <?php echo e($material->resume ?? $material->description ?? 'Tidak ada deskripsi tambahan.'); ?>

                                    </p>
                                </div>

                                
                                <div class="pt-4 border-t border-slate-100 mt-auto flex items-center justify-between relative z-10">
                                    <div class="text-xs text-slate-400 flex items-center gap-1">
                                        <i class="ph-fill ph-clock"></i> <?php echo e($material->created_at->diffForHumans()); ?>

                                    </div>
                                    
                                    <div class="flex items-center gap-2">
                                        
                                        <a href="<?php echo e($finalUrl); ?>" target="<?php echo e($targetAttr); ?>" class="w-10 h-10 rounded-xl <?php echo e($theme['bg']); ?> <?php echo e($theme['text']); ?> hover:bg-slate-800 hover:text-white hover:shadow-lg transition-all flex items-center justify-center active:scale-95 border <?php echo e($theme['border']); ?> hover:border-slate-800" title="Buka / Download">
                                            <?php if($realType == 'video' || $realType == 'link'): ?>
                                                <i class="ph-bold ph-arrow-square-out text-lg"></i>
                                            <?php else: ?>
                                                <i class="ph-bold ph-download-simple text-lg"></i>
                                            <?php endif; ?>
                                        </a>

                                        
                                        <a href="<?php echo e(route('lms.materials.edit', $material->id)); ?>" class="w-10 h-10 rounded-xl bg-white text-amber-500 hover:bg-amber-500 hover:text-white hover:shadow-lg hover:shadow-amber-200 transition-all flex items-center justify-center active:scale-95 border border-amber-100 hover:border-amber-500" title="Edit Materi">
                                            <i class="ph-bold ph-pencil-simple text-lg"></i>
                                        </a>

                                        
                                        <form action="<?php echo e(route('lms.materials.destroy', $material->id)); ?>" method="POST" class="form-delete-material">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <button type="button" class="btn-delete w-10 h-10 rounded-xl bg-white text-rose-500 hover:bg-rose-500 hover:text-white hover:shadow-lg hover:shadow-rose-200 transition-all flex items-center justify-center active:scale-95 border border-rose-100 hover:border-rose-500" title="Hapus Materi">
                                                <i class="ph-bold ph-trash text-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                
                <div class="mt-10 animate-enter px-4" style="animation-delay: 500ms">
                    <?php echo e($materials->links()); ?>

                </div>
            <?php else: ?>
                
                <div class="animate-enter bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200 p-12 flex flex-col items-center justify-center text-center group hover:border-blue-300 transition-colors" style="animation-delay: 200ms">
                    <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mb-6 group-hover:bg-blue-50 group-hover:text-blue-500 transition-all duration-500">
                        <i class="ph-duotone ph-books text-5xl"></i>
                    </div>
                    <h3 class="font-black text-slate-800 text-xl mb-2"><?php echo e(request('search') ? 'Materi Tidak Ditemukan' : 'Belum Ada Materi'); ?></h3>
                    <p class="text-slate-500 text-sm max-w-md mx-auto leading-relaxed mb-8">
                        <?php echo e(request('search') ? 'Coba ubah kata kunci pencarian atau filter mapel Anda.' : 'Anda belum mengunggah materi pelajaran apapun. Mulailah berbagi ilmu.'); ?>

                    </p>
                    <a href="<?php echo e(route('lms.materials.create')); ?>" class="px-8 py-3.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-600/30 hover:-translate-y-1 transform flex items-center gap-2 active:scale-95">
                        <i class="ph-bold ph-plus"></i> <?php echo e(request('search') ? 'Upload Materi Baru' : 'Tambah Materi Pertama'); ?>

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
                        confirmButtonColor: '#e11d48',
                        cancelButtonColor: '#94a3b8',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
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

            // Handler Flash Message Success
            <?php if(session('success')): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "<?php echo e(session('success')); ?>",
                    showConfirmButton: false,
                    timer: 2000,
                    customClass: {
                        popup: 'rounded-[2rem] font-sans'
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/lms/materials/index.blade.php ENDPATH**/ ?>