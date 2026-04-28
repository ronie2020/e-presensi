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
    
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    
    <div class="py-6 sm:py-8 font-sans text-slate-800"
         x-data="{ 
            editModalOpen: false, 
            editData: { id: '', title: '', description: '', video_url: '' },
            editFormAction: '',
            openEditModal(data, url) {
                this.editData = JSON.parse(JSON.stringify(data));
                this.editFormAction = url;
                this.editModalOpen = true;
                document.body.style.overflow = 'hidden';
            },
            closeEditModal() {
                this.editModalOpen = false;
                document.body.style.overflow = 'auto';
            }
         }">
        
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 sm:mb-10">
            <div class="relative rounded-[2.5rem] bg-gray-900 bg-gradient-to-br from-blue-900 via-blue-800 to-slate-900 p-8 sm:p-10 text-white shadow-2xl shadow-blue-900/40 overflow-hidden border border-white/10 group">
                
                
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-80 h-80 bg-blue-500/20 rounded-full blur-3xl pointer-events-none group-hover:bg-blue-500/30 transition-all duration-700"></div>
                <div class="absolute bottom-0 right-20 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                    
                    
                    <div class="max-w-2xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-200 text-[10px] font-bold uppercase tracking-widest mb-4 backdrop-blur-sm">
                            <i class="ph-fill ph-image"></i> Dokumentasi Sekolah
                        </div>
                        <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-3 flex items-center gap-3 text-white leading-tight">
                            Galeri Kegiatan
                        </h1>
                        <p class="text-blue-100/80 text-sm md:text-base font-medium leading-relaxed max-w-lg">
                            Abadikan dan publikasikan momen terbaik sekolah. Kelola foto dan video kegiatan untuk ditampilkan di halaman depan.
                        </p>
                    </div>
                    
                    
                    <div class="w-full md:w-auto mt-4 md:mt-0">
                        <div class="grid grid-cols-2 md:grid-cols-1 lg:grid-cols-2 gap-4">
                            <div class="bg-white/10 backdrop-blur-md px-5 py-5 rounded-2xl border border-white/10 text-center md:text-left hover:bg-white/15 transition-colors group/stat">
                                <div class="flex flex-col md:flex-row lg:flex-col items-center justify-center md:justify-start gap-2 mb-1 text-blue-300">
                                    <i class="ph-duotone ph-images-square text-2xl md:text-xl lg:text-2xl group-hover/stat:scale-110 transition-transform"></i>
                                    <span class="text-[10px] font-bold uppercase tracking-wider">Total Album</span>
                                </div>
                                <span class="block text-3xl font-black text-white tracking-tight mt-1"><?php echo e($activities->total() ?? 0); ?></span>
                            </div>

                            <a href="<?php echo e(url('/activities')); ?>" target="_blank" class="bg-indigo-500/20 backdrop-blur-md px-5 py-5 rounded-2xl border border-indigo-400/20 text-center md:text-left hover:bg-indigo-500/30 transition-colors group/link cursor-pointer">
                                <div class="flex flex-col md:flex-row lg:flex-col items-center justify-center md:justify-start gap-2 mb-1 text-indigo-300">
                                    <i class="ph-duotone ph-eye text-2xl md:text-xl lg:text-2xl"></i>
                                    <span class="text-[10px] font-bold uppercase tracking-wider">Preview</span>
                                </div>
                                <div class="flex items-center justify-center md:justify-start gap-1 text-white font-bold text-sm mt-2 group-hover/link:translate-x-1 transition-transform">
                                    <span>Lihat Web</span>
                                    <i class="ph-bold ph-arrow-right"></i>
                                </div>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <?php if(session('success')): ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: '<?php echo e(str_contains(session("success"), "Gagal") || str_contains(session("success"), "Error") ? "error" : "success"); ?>',
                            title: '<?php echo e(str_contains(session("success"), "Gagal") || str_contains(session("success"), "Error") ? "Oops..." : "Berhasil"); ?>',
                            text: "<?php echo e(session('success')); ?>",
                            timer: 4000,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end',
                            background: '#ffffff',
                            iconColor: '<?php echo e(str_contains(session("success"), "Gagal") || str_contains(session("success"), "Error") ? "#ef4444" : "#10b981"); ?>',
                            customClass: {
                                popup: 'shadow-lg border border-slate-100 rounded-2xl'
                            }
                        });
                    });
                </script>
            <?php endif; ?>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden lg:sticky lg:top-24 relative group hover:shadow-2xl hover:shadow-blue-900/10 transition-all duration-300">
                        <div class="bg-gradient-to-r from-blue-900 to-blue-800 p-8 text-white relative overflow-hidden">
                            <div class="absolute -right-6 -bottom-6 text-white/5 text-9xl pointer-events-none transform rotate-12">
                                <i class="ph-fill ph-aperture"></i>
                            </div>
                            <h3 class="text-xl font-black relative z-10">Upload Kegiatan</h3>
                            <p class="text-blue-200 text-sm font-medium relative z-10 mt-1">Bagikan momen terbaik sekolah.</p>
                        </div>

                        <div class="p-8 relative z-10">
                            <form action="<?php echo e(route('school-activities.store')); ?>" method="POST" enctype="multipart/form-data" class="space-y-6" x-data="{ imgPreviews: [] }">
                                <?php echo csrf_field(); ?>
                                
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Judul Kegiatan</label>
                                    <div class="relative">
                                        <i class="ph-bold ph-text-t absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="text" name="title" value="<?php echo e(old('title')); ?>" required placeholder="Contoh: Perkemahan Sabtu Minggu" class="w-full pl-11 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 font-bold text-slate-800 py-3 transition-colors placeholder:font-normal">
                                    </div>
                                    <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-rose-500 text-xs mt-1 block font-bold ml-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Deskripsi Singkat</label>
                                    <textarea name="description" required rows="3" placeholder="Ceritakan sedikit tentang kegiatan ini..." class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm text-slate-700 placeholder:font-normal p-4 font-medium"><?php echo e(old('description')); ?></textarea>
                                    <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-rose-500 text-xs mt-1 block font-bold ml-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Foto Dokumentasi</label>
                                    <div class="relative group">
                                        <input type="file" name="photos[]" accept="image/*" multiple
                                            @change="imgPreviews = []; Array.from($event.target.files).forEach(file => imgPreviews.push(URL.createObjectURL(file)))"
                                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" required>
                                        
                                        <div class="border-2 border-dashed border-slate-300 rounded-2xl p-6 text-center transition-all group-hover:border-blue-500 group-hover:bg-blue-50"
                                             :class="{'border-blue-500 bg-blue-50': imgPreviews.length > 0}">
                                            
                                            <div x-show="imgPreviews.length === 0" class="space-y-3 py-2">
                                                <div class="mx-auto w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-white group-hover:text-blue-600 transition-colors shadow-sm">
                                                    <i class="ph-fill ph-images text-2xl"></i>
                                                </div>
                                                <div>
                                                    <p class="text-xs font-bold text-slate-600"><span class="text-blue-600 underline">Pilih banyak foto</span> sekaligus</p>
                                                    <p class="text-[10px] text-slate-400 mt-1">Bisa blok lebih dari 1 file (Maks 10MB/foto)</p>
                                                </div>
                                            </div>

                                            <div x-show="imgPreviews.length > 0" class="w-full text-left" style="display: none;">
                                                <p class="text-xs font-bold text-blue-600 mb-2">Terpilih: <span x-text="imgPreviews.length"></span> Foto</p>
                                                <div class="grid grid-cols-3 gap-2">
                                                    <template x-for="(src, index) in imgPreviews" :key="index">
                                                        <div class="relative h-16 sm:h-20 rounded-lg overflow-hidden shadow-sm border border-slate-200">
                                                            <img :src="src" class="h-full w-full object-cover">
                                                        </div>
                                                    </template>
                                                </div>
                                                <p class="text-[10px] text-slate-400 mt-3 text-center">Klik atau Drop area ini untuk mengganti foto</p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $__errorArgs = ['photos'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-rose-500 text-xs mt-1 block font-bold ml-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Link Video (Opsional)</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-red-500">
                                            <i class="ph-fill ph-youtube-logo text-lg"></i>
                                        </div>
                                        <input type="url" name="video_url" value="<?php echo e(old('video_url')); ?>" placeholder="https://youtube.com/..." class="w-full pl-11 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm text-slate-700 font-bold placeholder:font-normal py-3 transition-colors">
                                    </div>
                                </div>

                                <button type="submit" class="w-full py-3.5 px-4 bg-blue-900 text-white font-bold rounded-xl hover:bg-blue-800 transition-all shadow-lg shadow-blue-900/30 flex items-center justify-center gap-2 transform active:scale-[0.98]">
                                    <i class="ph-bold ph-paper-plane-right text-lg"></i>
                                    <span>Publikasikan Album</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                        
                        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row gap-4 justify-between items-center">
                            <h3 class="font-black text-slate-800 text-lg flex items-center gap-2">
                                <i class="ph-fill ph-list-dashes text-blue-900"></i> Daftar Kegiatan
                            </h3>
                            <span class="bg-white border border-slate-200 text-[10px] font-black px-3 py-1.5 rounded-xl text-slate-500 shadow-sm">
                                Total: <?php echo e($activities->total() ?? 0); ?> Post
                            </span>
                        </div>

                        <div class="p-6 bg-slate-50/30 min-h-[500px]">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <?php $__empty_1 = true; $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <div class="group bg-white rounded-[1.5rem] overflow-hidden border border-slate-100 hover:border-blue-200 hover:shadow-xl hover:shadow-blue-900/5 transition-all duration-300 flex flex-col relative h-full">
                                        
                                        <div class="relative h-56 w-full bg-slate-100 overflow-hidden">
                                            <?php
                                                $rawImage = $activity->image_path;
                                                $images = [];

                                                if (is_array($rawImage)) {
                                                    $images = $rawImage;
                                                } elseif (is_string($rawImage)) {
                                                    $decoded = json_decode($rawImage, true);
                                                    $images = (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) ? $decoded : [$rawImage];
                                                }
                                                
                                                $images = array_filter($images);
                                                $coverImage = !empty($images) ? array_values($images)[0] : null;
                                                $totalImages = count($images);
                                            ?>

                                            <?php if($coverImage): ?>
                                                <img src="<?php echo e(asset('storage/' . $coverImage)); ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                            <?php else: ?>
                                                <div class="w-full h-full flex flex-col items-center justify-center text-slate-300 bg-slate-50">
                                                    <i class="ph-duotone ph-image text-5xl mb-2 opacity-50"></i>
                                                    <span class="text-xs font-bold uppercase tracking-wide">Tidak ada foto</span>
                                                </div>
                                            <?php endif; ?>

                                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent opacity-80"></div>

                                            <?php if($totalImages > 1): ?>
                                                <div class="absolute top-4 right-4 z-20">
                                                    <span class="bg-white/90 backdrop-blur text-slate-800 text-[10px] font-bold px-2 py-1 rounded-md shadow-sm flex items-center gap-1 border border-white/50">
                                                        <i class="ph-fill ph-images"></i> +<?php echo e($totalImages - 1); ?> Foto
                                                    </span>
                                                </div>
                                            <?php endif; ?>

                                            <?php if($activity->video_url): ?>
                                                <div class="absolute top-4 left-4 z-20">
                                                    <a href="<?php echo e($activity->video_url); ?>" target="_blank" class="px-3 py-1.5 bg-red-600/90 backdrop-blur text-white text-[10px] font-bold uppercase rounded-lg shadow-lg flex items-center gap-1.5 hover:bg-red-500 transition-colors">
                                                        <i class="ph-fill ph-play-circle text-sm"></i> Tonton
                                                    </a>
                                                </div>
                                            <?php endif; ?>

                                            <!-- TAMBAHAN: Tombol Aksi (Edit & Hapus) Stack -->
                                            <div class="absolute top-4 right-4 z-30 flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200 transform translate-x-2 group-hover:translate-x-0 <?php echo e($totalImages > 1 ? 'mt-8' : ''); ?>">
                                                
                                                <!-- Tombol Edit -->
                                                <button type="button" @click="openEditModal(<?php echo \Illuminate\Support\Js::from($activity)->toHtml() ?>, '<?php echo e(route('school-activities.update', $activity->id)); ?>')" class="bg-white/90 backdrop-blur text-amber-500 p-2.5 rounded-xl shadow-lg hover:bg-amber-500 hover:text-white transition-all" title="Edit Kegiatan">
                                                    <i class="ph-bold ph-pencil-simple text-lg"></i>
                                                </button>

                                                <!-- Tombol Hapus -->
                                                <form action="<?php echo e(route('school-activities.destroy', $activity->id)); ?>" method="POST" class="delete-form">
                                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                    <button type="button" class="btn-delete bg-white/90 backdrop-blur text-rose-500 p-2.5 rounded-xl shadow-lg hover:bg-rose-500 hover:text-white transition-all" title="Hapus Kegiatan">
                                                        <i class="ph-bold ph-trash text-lg"></i>
                                                    </button>
                                                </form>
                                            </div>

                                            <div class="absolute bottom-4 left-4 z-20">
                                                <span class="text-[10px] font-black text-white uppercase tracking-widest flex items-center gap-1.5 bg-black/30 backdrop-blur-md px-3 py-1 rounded-lg border border-white/10">
                                                    <i class="ph-bold ph-calendar-blank"></i>
                                                    <?php echo e($activity->created_at->format('d M Y')); ?>

                                                </span>
                                            </div>
                                        </div>

                                        <div class="p-6 flex-1 flex flex-col">
                                            <div class="mb-4">
                                                <h4 class="text-lg font-black text-slate-800 mb-2 line-clamp-2 leading-tight group-hover:text-blue-700 transition-colors">
                                                    <?php echo e($activity->title); ?>

                                                </h4>
                                                <p class="text-xs text-slate-500 leading-relaxed line-clamp-3 font-medium">
                                                    <?php echo e($activity->description); ?>

                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <div class="col-span-1 md:col-span-2 py-20 text-center bg-white rounded-[2rem] border-2 border-dashed border-slate-200">
                                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300 shadow-sm">
                                            <i class="ph-duotone ph-image-broken text-4xl"></i>
                                        </div>
                                        <h4 class="text-slate-700 font-bold text-lg">Belum ada kegiatan</h4>
                                        <p class="text-sm text-slate-400 mt-1 max-w-xs mx-auto">Mulai dengan mengupload foto dokumentasi kegiatan sekolah di formulir samping.</p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="mt-8">
                                <?php echo e($activities->links() ?? ''); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div x-show="editModalOpen" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center p-4 sm:p-6">
                <!-- Backdrop -->
                <div x-show="editModalOpen" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" 
                     @click="closeEditModal()"></div>

                <!-- Modal Panel -->
                <div x-show="editModalOpen" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative bg-white rounded-[2rem] shadow-2xl w-full max-w-2xl border border-slate-100 flex flex-col max-h-[90vh] overflow-hidden" 
                     @click.away="closeEditModal()">
                     
                    <!-- Modal Header -->
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50 shrink-0">
                        <h3 class="text-lg font-black text-slate-800 flex items-center gap-2">
                            <i class="ph-bold ph-pencil-simple text-blue-600"></i> Edit Album Kegiatan
                        </h3>
                        <button @click="closeEditModal()" type="button" class="text-slate-400 hover:text-slate-600 bg-white hover:bg-slate-100 p-2 rounded-full transition-colors border border-slate-200">
                            <i class="ph-bold ph-x"></i>
                        </button>
                    </div>

                    <!-- Modal Form -->
                    <form :action="editFormAction" method="POST" enctype="multipart/form-data" class="p-6 overflow-y-auto custom-scrollbar flex-1" x-data="{ editImgPreviews: [], isSubmitting: false }" @submit="isSubmitting = true">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Judul Kegiatan</label>
                                <input type="text" name="title" x-model="editData.title" required class="w-full rounded-2xl border-slate-200 bg-white focus:border-blue-600 focus:ring-blue-600 font-bold text-slate-800 py-3">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Deskripsi Singkat</label>
                                <textarea name="description" x-model="editData.description" required rows="4" class="w-full rounded-2xl border-slate-200 bg-white focus:border-blue-600 focus:ring-blue-600 text-sm text-slate-700 font-medium"></textarea>
                            </div>

                            <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Ganti Foto / Tambah Foto Baru (Opsional)</label>
                                <p class="text-[10px] text-slate-500 mb-3">Biarkan kosong jika tidak ingin mengubah foto. Perhatian: Mengupload foto baru akan <b>menghapus seluruh foto lama</b> pada album ini.</p>
                                
                                <input type="file" name="photos[]" accept="image/*" multiple
                                    @change="editImgPreviews = []; Array.from($event.target.files).forEach(file => editImgPreviews.push(URL.createObjectURL(file)))"
                                    class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 mb-3 bg-white">
                                
                                <!-- Preview Foto Baru -->
                                <div x-show="editImgPreviews.length > 0" class="grid grid-cols-4 gap-2 mt-2" style="display: none;">
                                    <template x-for="(src, index) in editImgPreviews" :key="index">
                                        <div class="h-16 sm:h-20 rounded-lg overflow-hidden border border-slate-200 shadow-sm">
                                            <img :src="src" class="w-full h-full object-cover">
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Link Video (Opsional)</label>
                                <input type="url" name="video_url" x-model="editData.video_url" placeholder="https://..." class="w-full rounded-2xl border-slate-200 bg-white focus:border-blue-600 focus:ring-blue-600 text-sm text-slate-700 font-bold py-3">
                            </div>
                        </div>

                        <!-- Footer Modal -->
                        <div class="mt-8 pt-4 border-t border-slate-100 flex justify-end gap-3 shrink-0">
                            <button type="button" @click="closeEditModal()" class="px-6 py-2.5 rounded-xl font-bold text-slate-500 hover:bg-slate-100 transition-colors">Batal</button>
                            <button type="submit" :disabled="isSubmitting" :class="{'opacity-70 cursor-wait': isSubmitting}" class="px-6 py-2.5 rounded-xl font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-500/20 transition-all flex items-center gap-2">
                                <span x-show="!isSubmitting"><i class="ph-bold ph-check"></i> Simpan Perubahan</span>
                                <span x-show="isSubmitting" x-cloak><i class="ph-bold ph-spinner animate-spin"></i> Menyimpan...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.btn-delete');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    const form = this.closest('.delete-form');
                    Swal.fire({
                        title: 'Hapus Album?',
                        text: "Semua foto dalam album kegiatan ini akan terhapus.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e11d48',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        borderRadius: '1.5rem',
                        customClass: {
                            popup: 'rounded-[2rem]',
                            confirmButton: 'rounded-xl px-6 py-2.5 font-bold',
                            cancelButton: 'rounded-xl px-6 py-2.5 font-bold'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/school-activities/index.blade.php ENDPATH**/ ?>