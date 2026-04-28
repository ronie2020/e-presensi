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

    
    <style>
        .fluent-card { box-shadow: 0 1.6px 3.6px 0 rgba(0, 0, 0, 0.05), 0 0.3px 0.9px 0 rgba(0, 0, 0, 0.03); border: 1px solid rgba(0, 0, 0, 0.05); transition: all 0.3s ease; }
        .fluent-card:hover { box-shadow: 0 6.4px 14.4px 0 rgba(0, 0, 0, 0.08), 0 1.2px 3.6px 0 rgba(0, 0, 0, 0.05); transform: translateY(-2px); }
        .fluent-modal { box-shadow: 0 25.6px 57.6px 0 rgba(0, 0, 0, 0.15), 0 4.8px 14.4px 0 rgba(0, 0, 0, 0.1); border: 1px solid rgba(0, 0, 0, 0.05); }
    </style>

    
    <div class="py-6 sm:py-8 font-sans text-elevate-text bg-elevate-surface min-h-screen relative overflow-hidden"
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
        
        
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-10 pointer-events-none -z-10 blur-3xl"></div>

        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 sm:mb-10 relative z-10">
            <div class="relative rounded-[2.5rem] bg-gradient-to-r from-elevate-accent via-elevate-peach-light to-elevate-peach p-8 sm:p-10 text-elevate-dark shadow-xl shadow-elevate-accent/20 overflow-hidden border border-white/60 group fluent-card">
                
                
                <div class="absolute inset-0 opacity-[0.03] bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none mix-blend-overlay"></div>
                <div class="absolute -top-10 -left-10 w-56 h-56 bg-elevate-primary/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-elevate-peach/40 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-xl group-hover:scale-105 transition-transform duration-700"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                    
                    
                    <div class="max-w-2xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/40 border border-white/50 text-elevate-dark text-[10px] font-black uppercase tracking-widest mb-4 backdrop-blur-sm shadow-sm">
                            <i class="ph-fill ph-image text-elevate-primary"></i> Dokumentasi Sekolah
                        </div>
                        <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-3 flex items-center gap-3 text-elevate-dark leading-tight">
                            Galeri Kegiatan
                        </h1>
                        <p class="text-elevate-dark/80 text-sm md:text-base font-bold leading-relaxed max-w-lg">
                            Abadikan dan publikasikan momen terbaik sekolah. Kelola foto dan video kegiatan untuk ditampilkan di halaman depan.
                        </p>
                    </div>
                    
                    
                    <div class="w-full md:w-auto mt-4 md:mt-0 flex gap-4">
                        <div class="bg-white/40 backdrop-blur-md px-6 py-5 rounded-[1.5rem] border border-white/50 flex-1 md:flex-none min-w-[140px] text-center md:text-left hover:bg-white/60 transition-colors shadow-sm group/stat">
                            <div class="flex items-center justify-center md:justify-start gap-2 mb-1 text-elevate-primary">
                                <i class="ph-duotone ph-images-square text-2xl group-hover/stat:scale-110 transition-transform"></i>
                                <span class="text-[10px] font-black uppercase tracking-wider text-elevate-dark/70">Total Album</span>
                            </div>
                            <span class="block text-3xl md:text-4xl font-black text-elevate-dark tracking-tight mt-1"><?php echo e($activities->total() ?? 0); ?></span>
                        </div>

                        <a href="<?php echo e(url('/activities')); ?>" target="_blank" class="bg-elevate-dark px-6 py-5 rounded-[1.5rem] border border-elevate-dark text-center md:text-left hover:bg-elevate-primary transition-all duration-300 group/link cursor-pointer shadow-xl shadow-elevate-dark/30 flex flex-col justify-center hover:-translate-y-1">
                            <div class="flex items-center justify-center md:justify-start gap-2 mb-1 text-elevate-accent">
                                <i class="ph-duotone ph-eye text-2xl"></i>
                                <span class="text-[10px] font-black uppercase tracking-wider hidden sm:inline">Preview</span>
                            </div>
                            <div class="flex items-center justify-center md:justify-start gap-1.5 text-white font-black text-sm mt-1">
                                <span>Lihat Web</span>
                                <i class="ph-bold ph-arrow-right group-hover/link:translate-x-1 transition-transform"></i>
                            </div>
                        </a>
                    </div>

                </div>
            </div>
        </div>

        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            
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
                            iconColor: '<?php echo e(str_contains(session("success"), "Gagal") || str_contains(session("success"), "Error") ? "#e11d48" : "#10b981"); ?>',
                            customClass: {
                                popup: 'fluent-modal rounded-[1.5rem] font-sans'
                            }
                        });
                    });
                </script>
            <?php endif; ?>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white rounded-[2rem] border border-slate-100 overflow-hidden lg:sticky lg:top-24 relative fluent-card">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-elevate-primary to-elevate-accent"></div>
                        
                        <div class="p-6 md:p-8 relative z-10">
                            <div class="flex items-center gap-4 mb-6 pb-4 border-b border-slate-50">
                                <div class="w-12 h-12 bg-elevate-peach-light text-elevate-primary border border-elevate-peach/50 rounded-xl flex items-center justify-center text-2xl shadow-sm shrink-0">
                                    <i class="ph-duotone ph-aperture"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-black text-elevate-dark">Upload Kegiatan</h3>
                                    <p class="text-xs font-bold text-elevate-text/60 uppercase tracking-wide mt-1">Publikasi Galeri</p>
                                </div>
                            </div>

                            <form action="<?php echo e(route('school-activities.store')); ?>" method="POST" enctype="multipart/form-data" class="space-y-6" x-data="{ imgPreviews: [] }">
                                <?php echo csrf_field(); ?>
                                
                                <div>
                                    <label class="block text-[10px] font-black text-elevate-primary uppercase tracking-widest mb-2 ml-1">Judul Kegiatan</label>
                                    <div class="relative">
                                        <i class="ph-bold ph-text-t absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="text" name="title" value="<?php echo e(old('title')); ?>" required placeholder="Contoh: Perkemahan Sabtu Minggu" class="w-full pl-11 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3.5 transition-colors placeholder:font-medium">
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
                                    <label class="block text-[10px] font-black text-elevate-primary uppercase tracking-widest mb-2 ml-1">Deskripsi Singkat</label>
                                    <textarea name="description" required rows="3" placeholder="Ceritakan sedikit tentang kegiatan ini..." class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 text-sm text-elevate-dark placeholder:font-medium p-4 font-medium transition-colors"><?php echo e(old('description')); ?></textarea>
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
                                    <label class="block text-[10px] font-black text-elevate-primary uppercase tracking-widest mb-2 ml-1">Foto Dokumentasi</label>
                                    <div class="relative group/upload">
                                        <input type="file" name="photos[]" accept="image/*" multiple
                                            @change="imgPreviews = []; Array.from($event.target.files).forEach(file => imgPreviews.push(URL.createObjectURL(file)))"
                                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" required>
                                        
                                        <div class="border-2 border-dashed border-slate-200 rounded-2xl p-6 text-center transition-all group-hover/upload:border-elevate-primary group-hover/upload:bg-elevate-primary/5"
                                             :class="{'border-elevate-primary bg-elevate-primary/5': imgPreviews.length > 0}">
                                            
                                            <div x-show="imgPreviews.length === 0" class="space-y-3 py-2">
                                                <div class="mx-auto w-12 h-12 rounded-[1rem] bg-elevate-peach-light flex items-center justify-center text-elevate-primary border border-elevate-peach/30 transition-colors shadow-sm">
                                                    <i class="ph-fill ph-images text-2xl"></i>
                                                </div>
                                                <div>
                                                    <p class="text-xs font-bold text-elevate-dark"><span class="text-elevate-primary underline">Pilih banyak foto</span> sekaligus</p>
                                                    <p class="text-[10px] text-slate-400 mt-1 font-bold">Bisa blok lebih dari 1 file (Maks 10MB/foto)</p>
                                                </div>
                                            </div>

                                            <div x-show="imgPreviews.length > 0" class="w-full text-left" style="display: none;">
                                                <p class="text-[10px] font-black text-elevate-primary uppercase tracking-widest mb-2">Terpilih: <span x-text="imgPreviews.length"></span> Foto</p>
                                                <div class="grid grid-cols-3 gap-2">
                                                    <template x-for="(src, index) in imgPreviews" :key="index">
                                                        <div class="relative h-16 sm:h-20 rounded-lg overflow-hidden shadow-sm border border-slate-200">
                                                            <img :src="src" class="h-full w-full object-cover">
                                                        </div>
                                                    </template>
                                                </div>
                                                <p class="text-[10px] text-slate-400 mt-3 text-center font-bold">Klik atau Drop area ini untuk mengganti foto</p>
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
                                    <label class="block text-[10px] font-black text-elevate-primary uppercase tracking-widest mb-2 ml-1">Link Video (Opsional)</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-rose-500">
                                            <i class="ph-fill ph-youtube-logo text-lg"></i>
                                        </div>
                                        <input type="url" name="video_url" value="<?php echo e(old('video_url')); ?>" placeholder="https://youtube.com/..." class="w-full pl-11 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 text-sm text-elevate-dark font-bold placeholder:font-medium py-3.5 transition-colors">
                                    </div>
                                </div>

                                <div class="pt-2">
                                    <button type="submit" class="w-full py-3.5 px-4 bg-elevate-dark text-white font-bold rounded-xl hover:bg-elevate-primary transition-all shadow-lg shadow-elevate-dark/20 flex items-center justify-center gap-2 transform active:scale-95 border border-transparent">
                                        <i class="ph-bold ph-paper-plane-right text-lg"></i>
                                        <span>Publikasikan Album</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-[2rem] border border-slate-100 overflow-hidden flex flex-col h-full min-h-[600px] fluent-card">
                        
                        <div class="p-6 md:p-8 border-b border-slate-50 bg-elevate-peach-light/30 flex flex-col sm:flex-row gap-4 justify-between items-center">
                            <h3 class="text-xl font-black text-elevate-dark flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white text-elevate-primary flex items-center justify-center border border-elevate-peach/50 shadow-sm">
                                    <i class="ph-bold ph-list-dashes text-xl"></i>
                                </div>
                                Daftar Kegiatan
                            </h3>
                            <span class="bg-white border border-slate-200 text-[10px] font-black px-3 py-1.5 rounded-full text-elevate-primary shadow-sm tracking-wider uppercase">
                                Total: <?php echo e($activities->total() ?? 0); ?> Post
                            </span>
                        </div>

                        <div class="p-6 md:p-8 bg-white flex-1">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <?php $__empty_1 = true; $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <div class="group bg-white rounded-[1.5rem] overflow-hidden border border-slate-200 hover:border-elevate-accent/50 transition-all duration-300 flex flex-col relative h-full fluent-card">
                                        
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
                                                <div class="w-full h-full flex flex-col items-center justify-center text-elevate-primary bg-elevate-peach-light/50">
                                                    <i class="ph-duotone ph-image text-5xl mb-2 opacity-50"></i>
                                                    <span class="text-[10px] font-black uppercase tracking-widest">Tidak ada foto</span>
                                                </div>
                                            <?php endif; ?>

                                            <div class="absolute inset-0 bg-gradient-to-t from-elevate-dark/90 via-transparent to-transparent opacity-80"></div>

                                            <?php if($totalImages > 1): ?>
                                                <div class="absolute top-4 right-4 z-20">
                                                    <span class="bg-white/90 backdrop-blur-md text-elevate-primary text-[10px] font-black px-2.5 py-1.5 rounded-lg shadow-sm flex items-center gap-1.5 border border-white/50">
                                                        <i class="ph-fill ph-images"></i> +<?php echo e($totalImages - 1); ?> Foto
                                                    </span>
                                                </div>
                                            <?php endif; ?>

                                            <?php if($activity->video_url): ?>
                                                <div class="absolute top-4 left-4 z-20">
                                                    <a href="<?php echo e($activity->video_url); ?>" target="_blank" class="px-3 py-1.5 bg-rose-600/90 backdrop-blur-md text-white text-[10px] font-black uppercase rounded-lg shadow-lg flex items-center gap-1.5 hover:bg-rose-500 transition-colors border border-rose-500/50">
                                                        <i class="ph-fill ph-play-circle text-sm"></i> Tonton
                                                    </a>
                                                </div>
                                            <?php endif; ?>

                                            <!-- Tombol Aksi (Edit & Hapus) Stack -->
                                            <div class="absolute top-4 right-4 z-30 flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200 transform translate-x-2 group-hover:translate-x-0 <?php echo e($totalImages > 1 ? 'mt-10' : ''); ?>">
                                                
                                                <!-- Tombol Edit -->
                                                <button type="button" @click="openEditModal(<?php echo \Illuminate\Support\Js::from($activity)->toHtml() ?>, '<?php echo e(route('school-activities.update', $activity->id)); ?>')" class="bg-white/90 backdrop-blur-md text-amber-500 p-2.5 rounded-xl shadow-sm border border-white/50 hover:bg-amber-500 hover:text-white transition-all" title="Edit Kegiatan">
                                                    <i class="ph-bold ph-pencil-simple text-lg"></i>
                                                </button>

                                                <!-- Tombol Hapus -->
                                                <form action="<?php echo e(route('school-activities.destroy', $activity->id)); ?>" method="POST" class="delete-form">
                                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                    <button type="button" class="btn-delete bg-white/90 backdrop-blur-md text-rose-500 p-2.5 rounded-xl shadow-sm border border-white/50 hover:bg-rose-500 hover:text-white transition-all" title="Hapus Kegiatan">
                                                        <i class="ph-bold ph-trash text-lg"></i>
                                                    </button>
                                                </form>
                                            </div>

                                            <div class="absolute bottom-4 left-4 z-20">
                                                <span class="text-[10px] font-black text-white uppercase tracking-widest flex items-center gap-1.5 bg-black/40 backdrop-blur-md px-3 py-1.5 rounded-lg border border-white/20 shadow-sm">
                                                    <i class="ph-bold ph-calendar-blank text-elevate-accent"></i>
                                                    <?php echo e($activity->created_at->format('d M Y')); ?>

                                                </span>
                                            </div>
                                        </div>

                                        <div class="p-6 flex-1 flex flex-col border-t border-slate-100">
                                            <div class="mb-2">
                                                <h4 class="text-lg font-black text-elevate-dark mb-2 line-clamp-2 leading-tight group-hover:text-elevate-primary transition-colors">
                                                    <?php echo e($activity->title); ?>

                                                </h4>
                                                <p class="text-xs text-elevate-text/70 leading-relaxed line-clamp-3 font-medium">
                                                    <?php echo e($activity->description); ?>

                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <div class="col-span-1 md:col-span-2 py-24 text-center bg-slate-50/50 rounded-[2rem] border-2 border-dashed border-slate-200">
                                        <div class="w-20 h-20 bg-elevate-peach-light rounded-[1.5rem] flex items-center justify-center mx-auto mb-4 text-elevate-primary border border-elevate-peach/30 shadow-sm">
                                            <i class="ph-duotone ph-image-broken text-4xl"></i>
                                        </div>
                                        <h4 class="text-elevate-dark font-black text-lg">Belum ada kegiatan</h4>
                                        <p class="text-sm text-slate-500 font-medium mt-1 max-w-xs mx-auto">Mulai dengan mengupload foto dokumentasi kegiatan sekolah di formulir samping.</p>
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

            
            <div x-show="editModalOpen" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6">
                <!-- Backdrop -->
                <div x-show="editModalOpen" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 backdrop-blur-none" x-transition:enter-end="opacity-100 backdrop-blur-sm"
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 backdrop-blur-sm" x-transition:leave-end="opacity-0 backdrop-blur-none"
                     class="fixed inset-0 bg-elevate-dark/70 backdrop-blur-sm" 
                     @click="closeEditModal()"></div>

                <!-- Modal Panel -->
                <div x-show="editModalOpen" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-8 sm:scale-95"
                     class="relative bg-white rounded-[2rem] fluent-modal w-full max-w-2xl flex flex-col max-h-[90vh] overflow-hidden" 
                     @click.away="closeEditModal()">
                     
                    <!-- Modal Header -->
                    <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between bg-elevate-peach-light/30 shrink-0">
                        <h3 class="text-xl font-black text-elevate-dark flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white text-elevate-primary flex items-center justify-center border border-elevate-peach/50 shadow-sm">
                                <i class="ph-bold ph-pencil-simple text-xl"></i>
                            </div>
                            Edit Album Kegiatan
                        </h3>
                        <button @click="closeEditModal()" type="button" class="w-10 h-10 flex items-center justify-center text-slate-400 hover:text-rose-600 bg-slate-50 hover:bg-rose-50 rounded-full transition-colors border border-transparent">
                            <i class="ph-bold ph-x text-lg"></i>
                        </button>
                    </div>

                    <!-- Modal Form -->
                    <form :action="editFormAction" method="POST" enctype="multipart/form-data" class="p-8 overflow-y-auto custom-scrollbar flex-1" x-data="{ editImgPreviews: [], isSubmitting: false }" @submit="isSubmitting = true">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        
                        <div class="space-y-6">
                            <div>
                                <label class="block text-[10px] font-black text-elevate-primary uppercase tracking-widest mb-2 ml-1">Judul Kegiatan</label>
                                <input type="text" name="title" x-model="editData.title" required class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3.5 px-4 transition-colors">
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-elevate-primary uppercase tracking-widest mb-2 ml-1">Deskripsi Singkat</label>
                                <textarea name="description" x-model="editData.description" required rows="4" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 text-sm text-elevate-dark font-medium p-4 transition-colors"></textarea>
                            </div>

                            <div class="p-6 bg-slate-50/80 border border-slate-200 rounded-2xl">
                                <label class="block text-[10px] font-black text-elevate-primary uppercase tracking-widest mb-2">Ganti Foto / Tambah Foto Baru (Opsional)</label>
                                <p class="text-[10px] text-slate-500 mb-4 font-bold">Biarkan kosong jika tidak ingin mengubah foto. <span class="text-rose-500">Perhatian: Mengupload foto baru akan menghapus seluruh foto lama pada album ini.</span></p>
                                
                                <input type="file" name="photos[]" accept="image/*" multiple
                                    @change="editImgPreviews = []; Array.from($event.target.files).forEach(file => editImgPreviews.push(URL.createObjectURL(file)))"
                                    class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-elevate-peach-light file:text-elevate-primary hover:file:bg-elevate-primary hover:file:text-white transition-all bg-white border border-slate-200 rounded-xl p-1.5 mb-3 shadow-sm">
                                
                                <!-- Preview Foto Baru -->
                                <div x-show="editImgPreviews.length > 0" class="grid grid-cols-4 sm:grid-cols-5 gap-2 mt-4" style="display: none;">
                                    <template x-for="(src, index) in editImgPreviews" :key="index">
                                        <div class="h-16 rounded-xl overflow-hidden border border-slate-200 shadow-sm">
                                            <img :src="src" class="w-full h-full object-cover">
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-elevate-primary uppercase tracking-widest mb-2 ml-1">Link Video (Opsional)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-rose-500">
                                        <i class="ph-fill ph-youtube-logo text-lg"></i>
                                    </div>
                                    <input type="url" name="video_url" x-model="editData.video_url" placeholder="https://..." class="w-full pl-11 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 text-sm text-elevate-dark font-bold py-3.5 transition-colors">
                                </div>
                            </div>
                        </div>

                        <!-- Footer Modal -->
                        <div class="mt-8 pt-6 border-t border-slate-100 flex justify-end gap-3 shrink-0">
                            <button type="button" @click="closeEditModal()" class="px-6 py-3.5 rounded-xl font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors text-sm border border-transparent">Batal</button>
                            <button type="submit" :disabled="isSubmitting" :class="{'opacity-70 cursor-wait': isSubmitting}" class="px-8 py-3.5 rounded-xl font-bold text-white bg-elevate-dark hover:bg-elevate-primary shadow-lg shadow-elevate-dark/20 transition-all flex items-center gap-2 text-sm border border-transparent">
                                <span x-show="!isSubmitting" class="flex items-center gap-2"><i class="ph-bold ph-floppy-disk text-lg"></i> Simpan Perubahan</span>
                                <span x-show="isSubmitting" x-cloak class="flex items-center gap-2"><i class="ph-bold ph-spinner animate-spin text-lg"></i> Menyimpan...</span>
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
                        cancelButtonColor: '#94a3b8',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        borderRadius: '1.5rem',
                        customClass: {
                            popup: 'fluent-modal rounded-[2rem] font-sans',
                            confirmButton: 'bg-rose-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-rose-700 transition-colors mx-2 shadow-lg border border-transparent',
                            cancelButton: 'bg-slate-100 text-slate-600 px-6 py-3 rounded-xl font-bold hover:bg-slate-200 transition-colors mx-2 border border-transparent'
                        },
                        buttonsStyling: false
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