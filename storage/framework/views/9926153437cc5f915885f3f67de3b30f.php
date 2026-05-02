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
            <?php echo e(__('Bank Soal - Kategori Folder')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-8 sm:py-10 font-sans text-elevate-dark relative overflow-hidden min-h-screen" 
         x-data="{ 
            openModal: false, 
            editModal: false,
            editFormAction: '',
            editName: '',
            editDesc: '',
            openEditModal(btn) {
                this.editName = btn.dataset.name;
                this.editDesc = btn.dataset.desc;
                this.editFormAction = btn.dataset.action;
                this.editModal = true;
            }
         }">

        
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            
            <?php if(session('success')): ?>
                <div id="flash-success" data-message="<?php echo e(session('success')); ?>"></div>
            <?php endif; ?>

            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                
                
                <div class="md:col-span-2 relative rounded-[2rem] bg-gradient-to-r from-elevate-accent via-elevate-peach-light to-elevate-peach p-8 md:p-10 text-elevate-dark shadow-xl shadow-elevate-accent/20 overflow-hidden border border-white/60">
                    
                    
                    <div class="absolute -top-10 -left-10 w-56 h-56 bg-elevate-primary/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-xl"></div>
                    <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-elevate-peach/40 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-xl"></div>
                    <div class="absolute top-10 right-10 w-28 h-28 bg-white/40 rounded-[2rem] rotate-45 pointer-events-none shadow-sm backdrop-blur-md border border-white/50"></div>
                    
                    <div class="relative z-10 flex flex-col justify-center h-full">
                        <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-3 text-elevate-dark">Gudang Bank Soal</h1>
                        <p class="text-elevate-dark/80 text-sm font-semibold max-w-lg leading-relaxed">
                            Kelola arsip soal berdasarkan folder tahun ajaran atau jenis asesmen agar tertata rapi.
                        </p>
                    </div>
                </div>

                
                <div class="bg-white rounded-[2rem] border border-slate-100 p-8 flex flex-col items-center justify-center text-center shadow-xl shadow-slate-200/50 relative overflow-hidden group">
                    <div class="w-16 h-16 bg-elevate-peach-light text-elevate-primary rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-sm border border-elevate-peach">
                        <i class="ph-bold ph-folder-plus text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-black text-elevate-dark mb-1">Folder Baru</h3>
                    <p class="text-xs text-elevate-dark/70 font-medium mb-5">Buat kategori penyimpanan soal.</p>
                    
                    <button @click="openModal = true" class="w-full px-5 py-3.5 bg-elevate-dark text-white rounded-xl font-bold hover:bg-elevate-primary transition-all shadow-lg shadow-elevate-dark/30 active:scale-95">
                        Buat Folder
                    </button>
                </div>
            </div>

            
            <h3 class="font-bold text-elevate-dark text-xl flex items-center gap-3 mb-6">
                <div class="w-2 h-6 bg-elevate-accent rounded-full"></div>
                Daftar Folder Tersedia
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php $__empty_1 = true; $__currentLoopData = $bankFolders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $folder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="bg-elevate-surface border border-slate-200 rounded-[2rem] hover:shadow-xl hover:border-elevate-accent/50 transition-all duration-300 group relative block overflow-hidden">
                        
                        <div class="absolute inset-0 bg-elevate-soft/0 group-hover:bg-elevate-soft/30 transition-colors pointer-events-none z-0"></div>

                        <div class="absolute top-5 right-5 z-20 opacity-0 group-hover:opacity-100 transition-opacity translate-y-1 group-hover:translate-y-0">
                            <button type="button" @click="openEditModal($event.currentTarget)"
                                    data-name="<?php echo e($folder->name); ?>" data-desc="<?php echo e($folder->description); ?>" data-action="<?php echo e(route('bank.folder.update', $folder->id)); ?>"
                                    class="w-10 h-10 flex items-center justify-center bg-white/90 backdrop-blur-sm text-slate-600 rounded-xl hover:bg-elevate-peach hover:text-white shadow-sm border border-slate-200 transition-all">
                                <i class="ph-bold ph-pencil-simple text-lg"></i>
                            </button>
                        </div>

                        <a href="<?php echo e(route('bank.show', $folder->id)); ?>" class="p-6 block relative z-10 h-full">
                            <div class="w-14 h-14 bg-elevate-soft text-elevate-primary rounded-2xl flex items-center justify-center text-3xl mb-4 group-hover:bg-elevate-primary group-hover:text-white transition-all border border-elevate-primary/10">
                                <i class="ph-duotone ph-folder-open"></i>
                            </div>
                            <h4 class="font-black text-2xl text-elevate-dark mb-2 group-hover:text-elevate-primary transition-colors"><?php echo e($folder->name); ?></h4>
                            <p class="text-slate-500 text-sm font-medium line-clamp-2 mb-4 h-10"><?php echo e($folder->description ?? 'Tidak ada deskripsi.'); ?></p>
                            
                            <div class="pt-4 border-t border-slate-100 flex items-center justify-between mt-auto">
                                <div class="flex items-center gap-2 text-elevate-dark font-bold text-xs bg-elevate-soft px-3 py-1.5 rounded-full border border-elevate-primary/10">
                                    <i class="ph-bold ph-books text-elevate-primary"></i> <?php echo e($folder->banks_count ?? 0); ?> Mapel
                                </div>
                                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-elevate-primary group-hover:text-white transition-all"><i class="ph-bold ph-arrow-right"></i></div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-span-full text-center py-20 bg-elevate-surface rounded-[2.5rem] border-2 border-dashed border-slate-200">
                        <div class="w-24 h-24 bg-elevate-peach-light rounded-full flex items-center justify-center mx-auto mb-6 text-elevate-primary">
                            <i class="ph-duotone ph-folder-dashed text-5xl"></i>
                        </div>
                        <h3 class="text-elevate-dark font-bold text-xl mb-2">Belum Ada Folder</h3>
                        <p class="text-elevate-dark/70 max-w-xs mx-auto mb-8 text-sm">Buat Folder/Kegiatan pertama Anda, seperti "Bank Soal 2026".</p>
                        <button @click="openModal = true" class="px-6 py-3 bg-elevate-dark text-white rounded-full font-bold hover:bg-elevate-primary text-sm mt-4 transition-colors">
                            Buat Folder Pertama
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        
        <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4" style="display: none;">
            <div @click.away="openModal = false" class="bg-elevate-surface rounded-[2rem] w-full max-w-md p-6 shadow-2xl border border-slate-100">
                <h3 class="text-xl font-black text-elevate-dark mb-4">Buat Folder Bank Soal</h3>
                <form action="<?php echo e(route('bank.folder.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Nama Folder</label>
                            <input type="text" name="name" required class="w-full rounded-2xl border-slate-200 bg-slate-50 py-3.5 px-4 focus:bg-white focus:ring-4 focus:ring-elevate-accent/20 focus:border-elevate-accent transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Deskripsi</label>
                            <textarea name="description" rows="2" class="w-full rounded-2xl border-slate-200 bg-slate-50 py-3.5 px-4 focus:bg-white focus:ring-4 focus:ring-elevate-accent/20 focus:border-elevate-accent transition-all"></textarea>
                        </div>
                    </div>
                    <div class="mt-6 flex gap-3">
                        <button type="button" @click="openModal = false" class="flex-1 py-3.5 border-2 border-slate-100 text-slate-600 rounded-xl font-bold hover:bg-slate-50 transition-colors">Batal</button>
                        <button type="submit" class="flex-1 py-3.5 bg-elevate-dark text-white rounded-xl font-bold hover:bg-elevate-primary shadow-lg shadow-elevate-dark/20 transition-all">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        
        <div x-show="editModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4" style="display: none;">
            <div @click.away="editModal = false" class="bg-elevate-surface rounded-[2rem] w-full max-w-md p-6 shadow-2xl border border-slate-100">
                <h3 class="text-xl font-black text-elevate-dark mb-4">Edit Folder</h3>
                <form :action="editFormAction" method="POST">
                    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Nama Folder</label>
                            <input type="text" name="name" x-model="editName" required class="w-full rounded-2xl border-slate-200 bg-slate-50 py-3.5 px-4 focus:bg-white focus:ring-4 focus:ring-elevate-accent/20 focus:border-elevate-accent transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Deskripsi</label>
                            <textarea name="description" x-model="editDesc" rows="2" class="w-full rounded-2xl border-slate-200 bg-slate-50 py-3.5 px-4 focus:bg-white focus:ring-4 focus:ring-elevate-accent/20 focus:border-elevate-accent transition-all"></textarea>
                        </div>
                    </div>
                    <div class="mt-6 flex gap-3 flex-wrap">
                        <button type="button" @click="editModal = false" class="flex-1 py-3.5 border-2 border-slate-100 text-slate-600 rounded-xl font-bold hover:bg-slate-50 transition-colors">Batal</button>
                        <button type="submit" class="flex-1 py-3.5 bg-elevate-dark text-white rounded-xl font-bold hover:bg-elevate-primary shadow-lg shadow-elevate-dark/20 transition-all">Update</button>
                    </div>
                    <div class="mt-4 border-t border-slate-100 pt-4">
                        <button type="button" onclick="confirmDelete(this.dataset.deleteUrl)" :data-delete-url="editFormAction" class="w-full py-3.5 bg-rose-50 text-rose-600 rounded-xl font-bold hover:bg-rose-100 flex items-center justify-center gap-2 transition-colors">
                            <i class="ph-bold ph-trash"></i> Hapus Folder
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <form id="delete-folder-form" action="" method="POST" class="hidden"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?></form>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(url) {
            Swal.fire({ title: 'Yakin Hapus?', text: "Semua Mapel & Soal di folder ini akan ikut terhapus permanen!", icon: 'warning', showCancelButton: true, confirmButtonColor: '#e11d48', confirmButtonText: 'Ya, Hapus!', customClass: { popup: 'rounded-[2rem]' }
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = document.getElementById('delete-folder-form');
                    form.action = url; form.submit();
                }
            })
        }
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/cbt/bank/index.blade.php ENDPATH**/ ?>