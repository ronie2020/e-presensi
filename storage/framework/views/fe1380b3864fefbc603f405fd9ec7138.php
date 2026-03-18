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
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-8 sm:py-10 font-sans text-slate-800" 
         x-data="{ 
            // 1. TAB PERSISTENCE: Mengambil tab terakhir dari SessionStorage, default ke 'pendidikan'
            activeTab: sessionStorage.getItem('portfolioActiveTab') || 'pendidikan',
            
            editModalOpen: false,
            editType: '', 
            editData: {},
            editFormAction: '',
            
            init() {
                // Pantau perubahan activeTab, lalu simpan ke SessionStorage
                this.$watch('activeTab', value => {
                    sessionStorage.setItem('portfolioActiveTab', value);
                });
            },

            openEditModal(type, item, url) {
                this.editType = type;
                this.editData = JSON.parse(JSON.stringify(item)); 
                
                if (type === 'art' && this.editData.published_at) {
                    this.editData.published_at = this.editData.published_at.substring(0, 10);
                }
                
                this.editFormAction = url;
                this.editModalOpen = true;
                document.body.style.overflow = 'hidden';
            },
            
            closeEditModal() {
                this.editModalOpen = false;
                document.body.style.overflow = 'auto';
                setTimeout(() => { 
                    this.editData = {}; 
                    this.editType = ''; 
                    this.editFormAction = '';
                }, 300);
            },

            confirmDelete(event, message) {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444', 
                    cancelButtonColor: '#94a3b8', 
                    confirmButtonText: '<i class=\'ph-bold ph-trash\'></i> Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true, 
                    customClass: {
                        popup: 'rounded-3xl',
                        confirmButton: 'px-5 py-2.5 rounded-xl font-bold flex items-center gap-2',
                        cancelButton: 'px-5 py-2.5 rounded-xl font-bold'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.target.submit(); 
                    }
                })
            }
         }">
         
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-3xl font-black text-gray-800 leading-tight flex items-center gap-3">
                        <i class="ph-duotone ph-medal text-blue-600"></i> 
                        Kelola Portofolio <?php echo e(isset($targetUser) && $targetUser->id !== auth()->id() ? '- ' . $targetUser->name : ''); ?>

                    </h2>
                    <p class="text-slate-500 text-sm mt-1">Tambahkan karya, materi, dan pengalaman untuk ditampilkan di direktori publik.</p>
                </div>
                <a href="<?php echo e(route('teachers.show', request('user_id') ?? auth()->id())); ?>" target="_blank" class="px-5 py-2.5 bg-blue-100 text-blue-700 font-bold rounded-2xl hover:bg-blue-200 transition-colors flex items-center gap-2 shadow-sm">
                    <i class="ph-bold ph-eye"></i> Lihat Profil Publik
                </a>
            </div>

            
            <?php if(session('success')): ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.mixin({
                            toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true,
                            customClass: { popup: 'rounded-2xl border border-emerald-100 shadow-lg' },
                            didOpen: (toast) => { toast.addEventListener('mouseenter', Swal.stopTimer); toast.addEventListener('mouseleave', Swal.resumeTimer); }
                        }).fire({ icon: 'success', title: '<?php echo e(session('success')); ?>' });
                    });
                </script>
            <?php endif; ?>

            
            <?php if($errors->any()): ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error', title: 'Gagal Menyimpan!',
                            html: `
                                <div class="text-left text-sm mt-2">
                                    <p class="text-slate-600 mb-2">Mohon perbaiki kesalahan berikut:</p>
                                    <ul class="list-disc pl-5 space-y-1">
                                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li class="text-rose-500 font-medium"><?php echo e($error); ?></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                            `,
                            confirmButtonColor: '#3b82f6', confirmButtonText: 'Tutup & Perbaiki', customClass: { popup: 'rounded-3xl' }
                        });
                    });
                </script>
            <?php endif; ?>

            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden flex flex-col md:flex-row min-h-[600px] relative">
                
                
                <div class="md:w-64 bg-slate-50 border-r border-slate-100 p-6 shrink-0">
                    <nav class="flex md:flex-col gap-2 overflow-x-auto custom-scrollbar pb-2 md:pb-0">
                        <button @click="activeTab = 'pendidikan'" :class="activeTab === 'pendidikan' ? 'bg-cyan-600 text-white shadow-lg shadow-cyan-600/20' : 'text-slate-500 hover:bg-slate-200 hover:text-slate-700'" class="w-full text-left px-4 py-3.5 rounded-2xl font-bold text-sm transition-all flex items-center gap-3 whitespace-nowrap">
                            <i class="ph-bold ph-graduation-cap text-lg"></i> Pendidikan
                        </button>
                        <button @click="activeTab = 'pengalaman'" :class="activeTab === 'pengalaman' ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-500 hover:bg-slate-200 hover:text-slate-700'" class="w-full text-left px-4 py-3.5 rounded-2xl font-bold text-sm transition-all flex items-center gap-3 whitespace-nowrap">
                            <i class="ph-bold ph-student text-lg"></i> Pengalaman
                        </button>
                        <button @click="activeTab = 'materi'" :class="activeTab === 'materi' ? 'bg-purple-600 text-white shadow-lg shadow-purple-600/20' : 'text-slate-500 hover:bg-slate-200 hover:text-slate-700'" class="w-full text-left px-4 py-3.5 rounded-2xl font-bold text-sm transition-all flex items-center gap-3 whitespace-nowrap">
                            <i class="ph-bold ph-presentation-chart text-lg"></i> Materi & Media
                        </button>
                        <button @click="activeTab = 'portofolio'" :class="activeTab === 'portofolio' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/20' : 'text-slate-500 hover:bg-slate-200 hover:text-slate-700'" class="w-full text-left px-4 py-3.5 rounded-2xl font-bold text-sm transition-all flex items-center gap-3 whitespace-nowrap">
                            <i class="ph-bold ph-trophy text-lg"></i> Prestasi / Galeri
                        </button>
                        <button @click="activeTab = 'artikel'" :class="activeTab === 'artikel' ? 'bg-orange-600 text-white shadow-lg shadow-orange-600/20' : 'text-slate-500 hover:bg-slate-200 hover:text-slate-700'" class="w-full text-left px-4 py-3.5 rounded-2xl font-bold text-sm transition-all flex items-center gap-3 whitespace-nowrap">
                            <i class="ph-bold ph-article text-lg"></i> Artikel Tulisan
                        </button>
                    </nav>
                </div>

                
                <div class="p-6 md:p-8 flex-1">

                    
                    <div x-show="activeTab === 'pengalaman'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                        <div class="flex items-center gap-3 mb-6 pb-2 border-b border-slate-100">
                            <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center"><i class="ph-bold ph-student"></i></div>
                            <h3 class="text-lg font-black text-slate-800">Riwayat Pelatihan & Sertifikasi</h3>
                        </div>
                        
                        
                        <form action="<?php echo e(route('portfolio.exp.store')); ?>" method="POST" x-data="{ isSubmitting: false }" @submit="isSubmitting = true" class="bg-slate-50/50 p-6 rounded-3xl border border-slate-100 mb-8 grid grid-cols-1 md:grid-cols-4 gap-4">
                            <?php echo csrf_field(); ?>
                            <?php if(request('user_id')): ?> <input type="hidden" name="user_id" value="<?php echo e(request('user_id')); ?>"> <?php endif; ?>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Tahun</label>
                                <input type="number" name="year" value="<?php echo e(old('year')); ?>" placeholder="2023" class="w-full rounded-2xl border-slate-200 bg-white focus:border-blue-500 focus:ring-blue-500 font-bold text-slate-700" required>
                            </div>
                            <div class="md:col-span-3">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Nama Pelatihan / Sertifikasi</label>
                                <input type="text" name="title" value="<?php echo e(old('title')); ?>" placeholder="Cth: Diklat Guru Penggerak..." class="w-full rounded-2xl border-slate-200 bg-white focus:border-blue-500 focus:ring-blue-500 font-bold text-slate-700" required>
                            </div>
                            <div class="md:col-span-3">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Penyelenggara</label>
                                <input type="text" name="organizer" value="<?php echo e(old('organizer')); ?>" placeholder="Cth: Kemdikbud..." class="w-full rounded-2xl border-slate-200 bg-white focus:border-blue-500 focus:ring-blue-500 font-bold text-slate-700">
                            </div>
                            <div class="flex items-end">
                                <button type="submit" :disabled="isSubmitting" :class="{'opacity-70 cursor-wait': isSubmitting}" class="w-full py-3 bg-blue-600 text-white font-bold rounded-2xl hover:bg-blue-700 shadow-lg shadow-blue-500/20 transition-all flex justify-center items-center gap-2">
                                    <span x-show="!isSubmitting"><i class="ph-bold ph-plus"></i> Tambah</span>
                                    <span x-show="isSubmitting" x-cloak><i class="ph-bold ph-spinner animate-spin"></i> Loading...</span>
                                </button>
                            </div>
                        </form>

                        <div class="space-y-3">
                            <?php $__empty_1 = true; $__currentLoopData = $experiences ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="flex items-center justify-between p-4 border border-slate-200 rounded-2xl hover:bg-blue-50/50 transition-colors group">
                                    <div class="flex items-start gap-4">
                                        <div class="px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-xs font-black mt-1"><?php echo e($exp->year); ?></div>
                                        <div>
                                            <h4 class="font-bold text-slate-800"><?php echo e($exp->title); ?></h4>
                                            <p class="text-sm text-slate-500 mt-0.5"><?php echo e($exp->organizer); ?></p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button type="button" @click="openEditModal('exp', <?php echo \Illuminate\Support\Js::from($exp)->toHtml() ?>, '<?php echo e(route('portfolio.exp.update', ['id' => $exp->id, 'user_id' => request('user_id')])); ?>')" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-amber-500 hover:bg-amber-50 rounded-xl transition-all" title="Edit">
                                            <i class="ph-bold ph-pencil-simple"></i>
                                        </button>
                                        <form action="<?php echo e(route('portfolio.exp.destroy', ['id' => $exp->id, 'user_id' => request('user_id')])); ?>" method="POST" @submit.prevent="confirmDelete($event, 'Menghapus riwayat pelatihan tidak dapat dibatalkan.')">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition-all" title="Hapus"><i class="ph-bold ph-trash"></i></button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="text-center py-10 bg-slate-50 rounded-3xl border border-dashed border-slate-200">
                                    <i class="ph-duotone ph-folder-open text-4xl text-slate-300 mb-2"></i>
                                    <p class="text-slate-500 text-sm font-medium">Belum ada data pengalaman ditambahkan.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    
                    <div x-show="activeTab === 'materi'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                        <div class="flex items-center gap-3 mb-6 pb-2 border-b border-slate-100">
                            <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center"><i class="ph-bold ph-presentation-chart"></i></div>
                            <h3 class="text-lg font-black text-slate-800">Materi & Media Pembelajaran</h3>
                        </div>
                        
                        <form action="<?php echo e(route('portfolio.mat.store')); ?>" method="POST" enctype="multipart/form-data" x-data="{ isSubmitting: false, fileName: '' }" @submit="isSubmitting = true" class="bg-slate-50/50 p-6 rounded-3xl border border-slate-100 mb-8 grid grid-cols-1 md:grid-cols-2 gap-5">
                            <?php echo csrf_field(); ?>
                            <?php if(request('user_id')): ?> <input type="hidden" name="user_id" value="<?php echo e(request('user_id')); ?>"> <?php endif; ?>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Judul Materi</label>
                                <input type="text" name="title" value="<?php echo e(old('title')); ?>" class="w-full rounded-2xl border-slate-200 bg-white focus:border-purple-500 focus:ring-purple-500 font-bold text-slate-700" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Tipe (Misal: Modul PDF, Slide PPT)</label>
                                <input type="text" name="type" value="<?php echo e(old('type')); ?>" class="w-full rounded-2xl border-slate-200 bg-white focus:border-purple-500 focus:ring-purple-500 font-bold text-slate-700">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Link URL (Jika File di GDrive/Youtube)</label>
                                <input type="url" name="file_url" value="<?php echo e(old('file_url')); ?>" placeholder="https://..." class="w-full rounded-2xl border-slate-200 bg-white focus:border-purple-500 focus:ring-purple-500 font-bold text-slate-700">
                            </div>
                            <div class="md:col-span-2 p-4 bg-white border-2 border-dashed border-slate-200 rounded-2xl">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Atau Upload File Langsung (Opsional)</label>
                                <div class="flex items-center gap-3">
                                    <input type="file" name="file" @change="fileName = $event.target.files[0].name" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                                </div>
                            </div>
                            <div class="md:col-span-2 flex justify-end">
                                <button type="submit" :disabled="isSubmitting" :class="{'opacity-70 cursor-wait': isSubmitting}" class="px-8 py-3 bg-purple-600 text-white font-bold rounded-2xl hover:bg-purple-700 shadow-lg shadow-purple-500/20 transition-all flex items-center gap-2">
                                    <span x-show="!isSubmitting"><i class="ph-bold ph-upload-simple"></i> Simpan Materi</span>
                                    <span x-show="isSubmitting" x-cloak><i class="ph-bold ph-spinner animate-spin"></i> Mengunggah...</span>
                                </button>
                            </div>
                        </form>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <?php $__empty_1 = true; $__currentLoopData = $materials ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="flex items-start gap-4 p-5 border border-slate-200 rounded-3xl bg-white shadow-sm hover:shadow-md transition-shadow relative group">
                                    <div class="w-12 h-12 rounded-xl bg-slate-50 flex items-center justify-center shrink-0">
                                        <i class="<?php echo e($mat->icon ?? 'ph-file-text text-slate-500'); ?> text-3xl"></i>
                                    </div>
                                    <div class="flex-1 pr-14">
                                        <h4 class="font-bold text-sm text-slate-800 line-clamp-2 leading-tight"><?php echo e($mat->title); ?></h4>
                                        <p class="text-xs font-medium text-slate-500 mt-1 mb-2"><?php echo e($mat->type); ?></p>
                                        <?php if($mat->file_url): ?> 
                                            <a href="<?php echo e($mat->file_url); ?>" target="_blank" class="inline-flex items-center gap-1 text-[10px] uppercase tracking-wider font-black text-purple-600 bg-purple-50 px-2 py-1 rounded-lg hover:bg-purple-100"><i class="ph-bold ph-link"></i> Buka Link</a>
                                        <?php elseif($mat->file_path): ?>
                                            <a href="<?php echo e(asset('storage/'.$mat->file_path)); ?>" target="_blank" class="inline-flex items-center gap-1 text-[10px] uppercase tracking-wider font-black text-blue-600 bg-blue-50 px-2 py-1 rounded-lg hover:bg-blue-100"><i class="ph-bold ph-download-simple"></i> Download</a>
                                        <?php endif; ?>
                                    </div>
                                    <div class="absolute top-4 right-4 flex flex-col gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button type="button" @click="openEditModal('mat', <?php echo \Illuminate\Support\Js::from($mat)->toHtml() ?>, '<?php echo e(route('portfolio.mat.update', ['id' => $mat->id, 'user_id' => request('user_id')])); ?>')" class="w-8 h-8 flex items-center justify-center rounded-xl text-slate-400 hover:bg-amber-50 hover:text-amber-500 transition-colors"><i class="ph-bold ph-pencil-simple"></i></button>
                                        <form action="<?php echo e(route('portfolio.mat.destroy', ['id' => $mat->id, 'user_id' => request('user_id')])); ?>" method="POST" @submit.prevent="confirmDelete($event, 'File materi yang dihapus tidak dapat dikembalikan.')">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <button class="w-8 h-8 flex items-center justify-center rounded-xl text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-colors"><i class="ph-bold ph-trash"></i></button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="sm:col-span-2 text-center py-10 bg-slate-50 rounded-3xl border border-dashed border-slate-200">
                                    <p class="text-slate-500 text-sm font-medium">Belum ada materi/media dibagikan.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    
                    <div x-show="activeTab === 'portofolio'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                        <div class="flex items-center gap-3 mb-6 pb-2 border-b border-slate-100">
                            <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center"><i class="ph-bold ph-trophy"></i></div>
                            <h3 class="text-lg font-black text-slate-800">Galeri Portofolio & Pencapaian</h3>
                        </div>
                        
                        
                        <form action="<?php echo e(route('portfolio.port.store')); ?>" method="POST" enctype="multipart/form-data" x-data="{ isSubmitting: false, imagePreview: null }" @submit="isSubmitting = true" class="bg-slate-50/50 p-6 rounded-3xl border border-slate-100 mb-8">
                            <?php echo csrf_field(); ?>
                            <?php if(request('user_id')): ?> <input type="hidden" name="user_id" value="<?php echo e(request('user_id')); ?>"> <?php endif; ?>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 items-end">
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Judul Kegiatan / Prestasi</label>
                                    <input type="text" name="title" value="<?php echo e(old('title')); ?>" class="w-full rounded-2xl border-slate-200 bg-white focus:border-emerald-500 focus:ring-emerald-500 font-bold text-slate-700" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Tahun</label>
                                    <input type="text" name="year" value="<?php echo e(old('year')); ?>" class="w-full rounded-2xl border-slate-200 bg-white focus:border-emerald-500 focus:ring-emerald-500 font-bold text-slate-700">
                                </div>
                                <div class="md:col-span-3 p-4 bg-white border-2 border-dashed border-slate-200 rounded-2xl flex flex-col md:flex-row gap-4 items-center justify-between">
                                    <div class="flex-1 w-full">
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Upload Foto Dokumentasi</label>
                                        <input type="file" name="image" accept="image/*" @change="imagePreview = URL.createObjectURL($event.target.files[0])" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:font-bold file:bg-emerald-50 file:text-emerald-700" required>
                                    </div>
                                    
                                    
                                    <template x-if="imagePreview">
                                        <div class="w-24 h-24 rounded-xl overflow-hidden border border-slate-200 shrink-0 shadow-sm">
                                            <img :src="imagePreview" class="w-full h-full object-cover">
                                        </div>
                                    </template>

                                    <button type="submit" :disabled="isSubmitting" :class="{'opacity-70 cursor-wait': isSubmitting}" class="px-8 py-3 bg-emerald-600 text-white font-bold rounded-2xl hover:bg-emerald-700 shadow-lg shadow-emerald-500/20 transition-all shrink-0 w-full md:w-auto flex justify-center items-center gap-2">
                                        <span x-show="!isSubmitting"><i class="ph-bold ph-upload-simple"></i> Simpan</span>
                                        <span x-show="isSubmitting" x-cloak><i class="ph-bold ph-spinner animate-spin"></i> Uploading...</span>
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            <?php $__empty_1 = true; $__currentLoopData = $portfolios ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $port): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="relative group rounded-3xl overflow-hidden border border-slate-200 shadow-sm">
                                    <div class="aspect-square bg-slate-100">
                                        <img src="<?php echo e(asset('storage/' . $port->image_path)); ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    </div>
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-between p-4">
                                        <div class="flex justify-end gap-2">
                                            <button type="button" @click="openEditModal('port', <?php echo \Illuminate\Support\Js::from($port)->toHtml() ?>, '<?php echo e(route('portfolio.port.update', ['id' => $port->id, 'user_id' => request('user_id')])); ?>')" class="w-8 h-8 bg-white/20 backdrop-blur text-white rounded-xl hover:bg-amber-500 transition-colors flex items-center justify-center"><i class="ph-bold ph-pencil-simple"></i></button>
                                            <form action="<?php echo e(route('portfolio.port.destroy', ['id' => $port->id, 'user_id' => request('user_id')])); ?>" method="POST" @submit.prevent="confirmDelete($event, 'Foto galeri yang dihapus tidak dapat dikembalikan.')">
                                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                <button class="w-8 h-8 bg-white/20 backdrop-blur text-white rounded-xl hover:bg-rose-500 transition-colors flex items-center justify-center"><i class="ph-bold ph-trash"></i></button>
                                            </form>
                                        </div>
                                        <div>
                                            <span class="inline-block px-2 py-1 bg-emerald-500 text-white text-[10px] font-black rounded-lg mb-1"><?php echo e($port->year); ?></span>
                                            <h4 class="text-white font-bold text-sm leading-tight line-clamp-2"><?php echo e($port->title); ?></h4>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="col-span-2 md:col-span-3 text-center py-10 bg-slate-50 rounded-3xl border border-dashed border-slate-200">
                                    <p class="text-slate-500 text-sm font-medium">Belum ada foto galeri.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    
                    <div x-show="activeTab === 'artikel'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                        <div class="flex items-center gap-3 mb-6 pb-2 border-b border-slate-100">
                            <div class="w-8 h-8 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center"><i class="ph-bold ph-article"></i></div>
                            <h3 class="text-lg font-black text-slate-800">Artikel & Tulisan Terpublikasi</h3>
                        </div>
                        
                        <form action="<?php echo e(route('portfolio.art.store')); ?>" method="POST" enctype="multipart/form-data" x-data="{ isSubmitting: false, imagePreview: null }" @submit="isSubmitting = true" class="bg-slate-50/50 p-6 rounded-3xl border border-slate-100 mb-8 grid grid-cols-1 md:grid-cols-2 gap-5">
                            <?php echo csrf_field(); ?>
                            <?php if(request('user_id')): ?> <input type="hidden" name="user_id" value="<?php echo e(request('user_id')); ?>"> <?php endif; ?>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Judul Artikel / Opini</label>
                                <input type="text" name="title" value="<?php echo e(old('title')); ?>" class="w-full rounded-2xl border-slate-200 bg-white focus:border-orange-500 focus:ring-orange-500 font-bold text-slate-700" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Kategori Topik</label>
                                <input type="text" name="category" value="<?php echo e(old('category')); ?>" placeholder="Pendidikan, Opini..." class="w-full rounded-2xl border-slate-200 bg-white focus:border-orange-500 focus:ring-orange-500 font-bold text-slate-700">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Tanggal Publikasi</label>
                                <input type="date" name="published_at" value="<?php echo e(old('published_at')); ?>" class="w-full rounded-2xl border-slate-200 bg-white focus:border-orange-500 focus:ring-orange-500 font-bold text-slate-700 text-sm">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Ringkasan / Excerpt</label>
                                <textarea name="excerpt" rows="2" class="w-full rounded-2xl border-slate-200 bg-white focus:border-orange-500 focus:ring-orange-500 font-medium text-slate-700"><?php echo e(old('excerpt')); ?></textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Link URL Artikel Asli (Opsional)</label>
                                <input type="url" name="url" value="<?php echo e(old('url')); ?>" placeholder="https://..." class="w-full rounded-2xl border-slate-200 bg-white focus:border-orange-500 focus:ring-orange-500 font-bold text-slate-700">
                            </div>
                            
                            <div class="flex items-center gap-4">
                                <div class="flex-1">
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Thumbnail Cover (Opsional)</label>
                                    <input type="file" name="image" accept="image/*" @change="imagePreview = URL.createObjectURL($event.target.files[0])" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:font-bold file:bg-orange-50 file:text-orange-600 bg-white rounded-2xl border border-slate-200 overflow-hidden">
                                </div>
                                <template x-if="imagePreview">
                                    <img :src="imagePreview" class="w-12 h-12 rounded-xl object-cover border border-slate-200 mt-5 shadow-sm">
                                </template>
                            </div>

                            <div class="md:col-span-2 flex justify-end mt-2">
                                <button type="submit" :disabled="isSubmitting" :class="{'opacity-70 cursor-wait': isSubmitting}" class="px-8 py-3 bg-orange-500 text-white font-bold rounded-2xl hover:bg-orange-600 shadow-lg shadow-orange-500/20 transition-all flex items-center gap-2">
                                    <span x-show="!isSubmitting"><i class="ph-bold ph-floppy-disk"></i> Simpan Artikel</span>
                                    <span x-show="isSubmitting" x-cloak><i class="ph-bold ph-spinner animate-spin"></i> Menyimpan...</span>
                                </button>
                            </div>
                        </form>

                        <div class="space-y-4">
                            <?php $__empty_1 = true; $__currentLoopData = $articles ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $art): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="flex items-stretch gap-4 p-4 border border-slate-200 rounded-3xl bg-white relative group hover:shadow-md transition-all">
                                    <?php if($art->image_path): ?>
                                        <div class="w-24 h-24 rounded-2xl overflow-hidden shrink-0 bg-slate-100">
                                            <img src="<?php echo e(asset('storage/' . $art->image_path)); ?>" class="w-full h-full object-cover">
                                        </div>
                                    <?php endif; ?>
                                    <div class="flex-1 py-1 pr-16">
                                        <span class="inline-block px-2 py-1 bg-orange-100 text-orange-700 text-[10px] font-black rounded-lg uppercase tracking-wider mb-1"><?php echo e($art->category ?? 'Umum'); ?></span>
                                        <h4 class="font-bold text-slate-800 text-base leading-tight"><?php echo e($art->title); ?></h4>
                                        <p class="text-xs font-medium text-slate-500 mt-1 line-clamp-2"><?php echo e($art->excerpt); ?></p>
                                        <?php if($art->url): ?> 
                                            <a href="<?php echo e($art->url); ?>" target="_blank" class="inline-flex items-center gap-1 text-[10px] uppercase tracking-wider font-black text-blue-600 hover:text-blue-800 mt-2"><i class="ph-bold ph-link"></i> Baca di Web Asli</a> 
                                        <?php endif; ?>
                                    </div>
                                    <div class="absolute top-4 right-4 flex flex-col gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button type="button" @click="openEditModal('art', <?php echo \Illuminate\Support\Js::from($art)->toHtml() ?>, '<?php echo e(route('portfolio.art.update', ['id' => $art->id, 'user_id' => request('user_id')])); ?>')" class="w-8 h-8 flex items-center justify-center rounded-xl text-slate-400 hover:bg-amber-50 hover:text-amber-500 transition-colors"><i class="ph-bold ph-pencil-simple"></i></button>
                                        <form action="<?php echo e(route('portfolio.art.destroy', ['id' => $art->id, 'user_id' => request('user_id')])); ?>" method="POST" @submit.prevent="confirmDelete($event, 'Menghapus data artikel tidak dapat dibatalkan.')">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <button class="w-8 h-8 flex items-center justify-center rounded-xl text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-colors"><i class="ph-bold ph-trash"></i></button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="text-center py-10 bg-slate-50 rounded-3xl border border-dashed border-slate-200">
                                    <p class="text-slate-500 text-sm font-medium">Belum ada tulisan artikel.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    
                    <div x-show="activeTab === 'pendidikan'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                        <div class="flex items-center gap-3 mb-6 pb-2 border-b border-slate-100">
                            <div class="w-8 h-8 rounded-lg bg-cyan-100 text-cyan-600 flex items-center justify-center"><i class="ph-bold ph-graduation-cap"></i></div>
                            <h3 class="text-lg font-black text-slate-800">Riwayat Pendidikan Formal</h3>
                        </div>
                        
                        <form action="<?php echo e(route('portfolio.edu.store')); ?>" method="POST" x-data="{ isSubmitting: false }" @submit="isSubmitting = true" class="bg-slate-50/50 p-6 rounded-3xl border border-slate-100 mb-8 grid grid-cols-1 md:grid-cols-4 gap-4">
                            <?php echo csrf_field(); ?>
                            <?php if(request('user_id')): ?> <input type="hidden" name="user_id" value="<?php echo e(request('user_id')); ?>"> <?php endif; ?>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Nama Institusi / Universitas</label>
                                <input type="text" name="institution" value="<?php echo e(old('institution')); ?>" placeholder="Cth: Universitas Pendidikan..." class="w-full rounded-2xl border-slate-200 bg-white focus:border-cyan-500 focus:ring-cyan-500 font-bold text-slate-700" required>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Gelar / Jurusan</label>
                                <input type="text" name="degree" value="<?php echo e(old('degree')); ?>" placeholder="Cth: S1 Pendidikan Matematika" class="w-full rounded-2xl border-slate-200 bg-white focus:border-cyan-500 focus:ring-cyan-500 font-bold text-slate-700">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Tahun Masuk</label>
                                <input type="number" name="start_year" value="<?php echo e(old('start_year')); ?>" placeholder="Cth: 2010" class="w-full rounded-2xl border-slate-200 bg-white focus:border-cyan-500 focus:ring-cyan-500 font-bold text-slate-700">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Tahun Lulus</label>
                                <input type="number" name="end_year" value="<?php echo e(old('end_year')); ?>" placeholder="Cth: 2014" class="w-full rounded-2xl border-slate-200 bg-white focus:border-cyan-500 focus:ring-cyan-500 font-bold text-slate-700">
                            </div>
                            <div class="md:col-span-2 flex items-end justify-end">
                                <button type="submit" :disabled="isSubmitting" :class="{'opacity-70 cursor-wait': isSubmitting}" class="px-8 py-3 bg-cyan-600 text-white font-bold rounded-2xl hover:bg-cyan-700 shadow-lg shadow-cyan-500/20 transition-all flex items-center gap-2">
                                    <span x-show="!isSubmitting"><i class="ph-bold ph-plus"></i> Tambah Riwayat</span>
                                    <span x-show="isSubmitting" x-cloak><i class="ph-bold ph-spinner animate-spin"></i> Menyimpan...</span>
                                </button>
                            </div>
                        </form>

                        <div class="space-y-3">
                            <?php $__empty_1 = true; $__currentLoopData = $educations ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $edu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="flex items-center justify-between p-4 border border-slate-200 rounded-2xl hover:bg-cyan-50/50 transition-colors group">
                                    <div class="flex items-start gap-4">
                                        <div class="px-3 py-1 bg-cyan-100 text-cyan-700 rounded-lg text-xs font-black mt-1"><?php echo e($edu->start_year ?? '-'); ?> - <?php echo e($edu->end_year ?? 'Skrg'); ?></div>
                                        <div>
                                            <h4 class="font-bold text-slate-800"><?php echo e($edu->institution); ?></h4>
                                            <p class="text-sm text-slate-500 mt-0.5"><?php echo e($edu->degree); ?></p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button type="button" @click="openEditModal('edu', <?php echo \Illuminate\Support\Js::from($edu)->toHtml() ?>, '<?php echo e(route('portfolio.edu.update', ['id' => $edu->id, 'user_id' => request('user_id')])); ?>')" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-amber-500 hover:bg-amber-50 rounded-xl transition-all"><i class="ph-bold ph-pencil-simple"></i></button>
                                        <form action="<?php echo e(route('portfolio.edu.destroy', ['id' => $edu->id, 'user_id' => request('user_id')])); ?>" method="POST" @submit.prevent="confirmDelete($event, 'Apakah Anda ingin menghapus riwayat pendidikan ini?')">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition-all"><i class="ph-bold ph-trash"></i></button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="text-center py-10 bg-slate-50 rounded-3xl border border-dashed border-slate-200">
                                    <p class="text-slate-500 text-sm font-medium">Belum ada riwayat pendidikan ditambahkan.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            </div>
            
            
            
            
            <div x-show="editModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6">
                <!-- Backdrop -->
                <div x-show="editModalOpen" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="closeEditModal()"></div>

                <!-- Modal Panel -->
                <div x-show="editModalOpen" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative bg-white rounded-[2rem] shadow-2xl w-full max-w-2xl border border-slate-100 overflow-hidden" 
                     @click.away="closeEditModal()">
                     
                    <!-- Modal Header -->
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                        <h3 class="text-lg font-black text-slate-800 flex items-center gap-2">
                            <i class="ph-bold ph-pencil-simple text-blue-600"></i> Edit Data
                        </h3>
                        <button @click="closeEditModal()" type="button" class="text-slate-400 hover:text-slate-600 bg-white hover:bg-slate-100 p-2 rounded-full transition-colors border border-slate-200">
                            <i class="ph-bold ph-x"></i>
                        </button>
                    </div>

                    <!-- Modal Body / Form -->
                    <form :action="editFormAction" method="POST" enctype="multipart/form-data" class="p-6" x-data="{ isModalSubmitting: false, editImagePreview: null }" @submit="isModalSubmitting = true">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <?php if(request('user_id')): ?> <input type="hidden" name="user_id" value="<?php echo e(request('user_id')); ?>"> <?php endif; ?>

                        <!-- Form Edit: Pengalaman (exp) -->
                        <template x-if="editType === 'exp'">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Tahun</label>
                                    <input type="number" name="year" x-model="editData.year" class="w-full rounded-2xl border-slate-200 bg-white focus:border-blue-500 focus:ring-blue-500 font-bold text-slate-700" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Nama Pelatihan / Sertifikasi</label>
                                    <input type="text" name="title" x-model="editData.title" class="w-full rounded-2xl border-slate-200 bg-white focus:border-blue-500 focus:ring-blue-500 font-bold text-slate-700" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Penyelenggara</label>
                                    <input type="text" name="organizer" x-model="editData.organizer" class="w-full rounded-2xl border-slate-200 bg-white focus:border-blue-500 focus:ring-blue-500 font-bold text-slate-700">
                                </div>
                            </div>
                        </template>

                        <!-- Form Edit: Materi (mat) -->
                        <template x-if="editType === 'mat'">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Judul Materi</label>
                                    <input type="text" name="title" x-model="editData.title" class="w-full rounded-2xl border-slate-200 bg-white focus:border-purple-500 focus:ring-purple-500 font-bold text-slate-700" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Tipe</label>
                                    <input type="text" name="type" x-model="editData.type" class="w-full rounded-2xl border-slate-200 bg-white focus:border-purple-500 focus:ring-purple-500 font-bold text-slate-700">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Link URL</label>
                                    <input type="url" name="file_url" x-model="editData.file_url" class="w-full rounded-2xl border-slate-200 bg-white focus:border-purple-500 focus:ring-purple-500 font-bold text-slate-700">
                                </div>
                                <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl">
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Ganti File (Opsional)</label>
                                    <p class="text-xs text-slate-500 mb-2">Biarkan kosong jika tidak ingin mengubah file.</p>
                                    <input type="file" name="file" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-purple-100 file:text-purple-700">
                                </div>
                            </div>
                        </template>

                        <!-- Form Edit: Portofolio/Galeri (port) -->
                        <template x-if="editType === 'port'">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Judul Kegiatan</label>
                                    <input type="text" name="title" x-model="editData.title" class="w-full rounded-2xl border-slate-200 bg-white focus:border-emerald-500 focus:ring-emerald-500 font-bold text-slate-700" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Tahun</label>
                                    <input type="text" name="year" x-model="editData.year" class="w-full rounded-2xl border-slate-200 bg-white focus:border-emerald-500 focus:ring-emerald-500 font-bold text-slate-700">
                                </div>
                                <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl flex items-center gap-4">
                                    <div class="flex-1">
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Ganti Foto (Opsional)</label>
                                        <p class="text-xs text-slate-500 mb-2">Biarkan kosong jika tidak ingin mengubah foto.</p>
                                        <input type="file" name="image" accept="image/*" @change="editImagePreview = URL.createObjectURL($event.target.files[0])" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-emerald-100 file:text-emerald-700">
                                    </div>
                                    <template x-if="editImagePreview">
                                        <img :src="editImagePreview" class="w-16 h-16 rounded-lg object-cover border border-slate-200">
                                    </template>
                                </div>
                            </div>
                        </template>

                        <!-- Form Edit: Artikel (art) -->
                        <template x-if="editType === 'art'">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Judul Artikel</label>
                                    <input type="text" name="title" x-model="editData.title" class="w-full rounded-2xl border-slate-200 bg-white focus:border-orange-500 focus:ring-orange-500 font-bold text-slate-700" required>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Kategori</label>
                                        <input type="text" name="category" x-model="editData.category" class="w-full rounded-2xl border-slate-200 bg-white focus:border-orange-500 focus:ring-orange-500 font-bold text-slate-700">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Tanggal Publikasi</label>
                                        <input type="date" name="published_at" x-model="editData.published_at" class="w-full rounded-2xl border-slate-200 bg-white focus:border-orange-500 focus:ring-orange-500 font-bold text-slate-700 text-sm">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Ringkasan</label>
                                    <textarea name="excerpt" x-model="editData.excerpt" rows="2" class="w-full rounded-2xl border-slate-200 bg-white focus:border-orange-500 focus:ring-orange-500 font-medium text-slate-700"></textarea>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Link URL Artikel</label>
                                    <input type="url" name="url" x-model="editData.url" class="w-full rounded-2xl border-slate-200 bg-white focus:border-orange-500 focus:ring-orange-500 font-bold text-slate-700">
                                </div>
                                <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl flex items-center gap-4">
                                    <div class="flex-1">
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Ganti Thumbnail (Opsional)</label>
                                        <p class="text-xs text-slate-500 mb-2">Biarkan kosong jika tidak ingin mengubah thumbnail.</p>
                                        <input type="file" name="image" accept="image/*" @change="editImagePreview = URL.createObjectURL($event.target.files[0])" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-orange-100 file:text-orange-700">
                                    </div>
                                    <template x-if="editImagePreview">
                                        <img :src="editImagePreview" class="w-16 h-16 rounded-lg object-cover border border-slate-200">
                                    </template>
                                </div>
                            </div>
                        </template>

                        <!-- Form Edit: Pendidikan (edu) -->
                        <template x-if="editType === 'edu'">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Nama Institusi</label>
                                    <input type="text" name="institution" x-model="editData.institution" class="w-full rounded-2xl border-slate-200 bg-white focus:border-cyan-500 focus:ring-cyan-500 font-bold text-slate-700" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Gelar / Jurusan</label>
                                    <input type="text" name="degree" x-model="editData.degree" class="w-full rounded-2xl border-slate-200 bg-white focus:border-cyan-500 focus:ring-cyan-500 font-bold text-slate-700">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Tahun Masuk</label>
                                        <input type="number" name="start_year" x-model="editData.start_year" class="w-full rounded-2xl border-slate-200 bg-white focus:border-cyan-500 focus:ring-cyan-500 font-bold text-slate-700">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Tahun Lulus</label>
                                        <input type="number" name="end_year" x-model="editData.end_year" class="w-full rounded-2xl border-slate-200 bg-white focus:border-cyan-500 focus:ring-cyan-500 font-bold text-slate-700">
                                    </div>
                                </div>
                            </div>
                        </template>

                        <!-- Submit Button -->
                        <div class="mt-8 pt-4 border-t border-slate-100 flex justify-end gap-3">
                            <button type="button" @click="closeEditModal()" class="px-6 py-2.5 rounded-xl font-bold text-slate-500 hover:bg-slate-100 transition-colors">Batal</button>
                            <button type="submit" :disabled="isModalSubmitting" :class="{'opacity-70 cursor-wait': isModalSubmitting}" class="px-6 py-2.5 rounded-xl font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-500/20 transition-all flex items-center gap-2">
                                <span x-show="!isModalSubmitting"><i class="ph-bold ph-check"></i> Simpan Perubahan</span>
                                <span x-show="isModalSubmitting" x-cloak><i class="ph-bold ph-spinner animate-spin"></i> Menyimpan...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/portfolio/index.blade.php ENDPATH**/ ?>