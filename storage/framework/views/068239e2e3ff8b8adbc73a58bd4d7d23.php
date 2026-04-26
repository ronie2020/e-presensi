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
        <h2 class="font-semibold text-xl text-[#2c3f61] leading-tight">
            <?php echo e(__('CBT Dashboard - Kategori Kegiatan')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    
    <div class="py-8 sm:py-10 font-sans text-[#2c3f61]" 
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
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            
            <?php if(session('success')): ?>
                <div id="flash-success" data-message="<?php echo e(session('success')); ?>"></div>
            <?php endif; ?>
            <?php if(session('error')): ?>
                <div id="flash-error" data-message="<?php echo e(session('error')); ?>"></div>
            <?php endif; ?>

            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                
                
                <div class="md:col-span-2 relative rounded-[2rem] bg-gradient-to-r from-[#56bbf1] via-[#e5eff5] to-[#f4d1c0] p-8 md:p-10 text-[#2c3f61] shadow-xl shadow-[#56bbf1]/10 overflow-hidden border border-white/60">
                    <div class="absolute -top-10 -left-10 w-48 h-48 bg-[#0d52a1]/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-3xl"></div>
                    <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-[#f9a282]/20 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-2xl"></div>
                    <div class="absolute top-10 right-10 w-24 h-24 bg-white/40 rounded-2xl rotate-45 pointer-events-none shadow-sm"></div>
                    
                    <div class="relative z-10 flex flex-col justify-center h-full">
                        <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-3 text-[#2c3f61]">Manajemen Ujian CBT</h1>
                        <p class="text-[#2c3f61]/80 text-sm font-medium max-w-lg leading-relaxed">
                            Buat dan kelola folder kegiatan ujian (PTS, PAS, Asesmen Harian). Folder ini digunakan untuk mengelompokkan jadwal ujian siswa.
                        </p>
                    </div>
                </div>

                
                <div class="bg-white rounded-[2rem] border border-slate-100 p-8 flex flex-col items-center justify-center text-center shadow-xl shadow-slate-200/50 relative overflow-hidden group">
                    <div class="w-16 h-16 bg-[#e5eff5] text-[#0d52a1] rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-inner">
                        <i class="ph-bold ph-folder-plus text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-black text-[#2c3f61] mb-1">Kategori Baru</h3>
                    <p class="text-xs text-slate-500 font-medium mb-5">Buat folder ujian baru.</p>
                    <button @click="openModal = true" class="w-full px-5 py-3.5 bg-[#2c3f61] text-white rounded-xl font-bold hover:bg-[#1c2940] transition shadow-lg shadow-[#2c3f61]/20 active:scale-95">
                        Buat Sekarang
                    </button>
                </div>
            </div>

            
            <form id="filterForm" action="<?php echo e(route('cbt.index')); ?>" method="GET" class="flex flex-col md:flex-row items-center justify-between mb-6 px-2 gap-4">
                <h3 class="font-bold text-[#2c3f61] text-xl flex items-center gap-2 shrink-0 w-full md:w-auto">
                    <div class="w-1.5 h-6 bg-[#56bbf1] rounded-full"></div>
                    Daftar Kegiatan CBT (Folder)
                </h3>

                <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                    
                    <div class="relative w-full sm:w-64 group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#56bbf1] transition-colors">
                            <i class="ph-bold ph-magnifying-glass"></i>
                        </div>
                        <input name="search" value="<?php echo e(request('search')); ?>" type="text" class="w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 bg-white text-sm focus:border-[#56bbf1] focus:ring-4 focus:ring-[#56bbf1]/20 transition-all font-medium placeholder-slate-400 shadow-sm" placeholder="Cari Kegiatan...">
                        <button type="submit" class="hidden"></button>
                    </div>
                </div>
            </form>

            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php $__empty_1 = true; $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="bg-white border border-slate-200 rounded-[2rem] hover:shadow-xl hover:shadow-[#56bbf1]/10 hover:border-[#56bbf1]/50 transition-all duration-300 group relative block overflow-hidden">
                        
                        <!-- Pattern Background on Hover -->
                        <div class="absolute inset-0 bg-[#e5eff5]/0 group-hover:bg-[#e5eff5]/40 transition-colors pointer-events-none z-0"></div>
                        <div class="absolute -right-4 -top-4 text-[#e5eff5] opacity-0 group-hover:opacity-100 transition-opacity transform group-hover:scale-110 pointer-events-none z-0">
                            <i class="ph-fill ph-folder text-[10rem]"></i>
                        </div>

                        <!-- TOMBOL EDIT FOLDER (Akan muncul saat di hover) -->
                        <div class="absolute top-5 right-5 z-20 opacity-0 group-hover:opacity-100 transition-opacity translate-y-1 group-hover:translate-y-0">
                            <button type="button" 
                                    @click="openEditModal($event.currentTarget)"
                                    data-name="<?php echo e($event->name); ?>"
                                    data-desc="<?php echo e($event->description); ?>"
                                    data-action="<?php echo e(route('cbt.events.update', $event->id)); ?>"
                                    title="Edit Nama/Deskripsi Folder"
                                    class="w-10 h-10 flex items-center justify-center bg-white/90 backdrop-blur-sm text-[#0d52a1] rounded-[1rem] hover:bg-[#0d52a1] hover:text-white shadow-sm border border-[#56bbf1]/30 transition-all">
                                <i class="ph-bold ph-pencil-simple text-lg"></i>
                            </button>
                        </div>

                        <!-- KONTEN UTAMA YANG BISA DI KLIK MENUJU DAFTAR SOAL -->
                        <a href="<?php echo e(route('cbt.events.show', $event->id)); ?>" class="p-6 block relative z-10 h-full">
                            <div class="flex items-start justify-between mb-4">
                                <div class="w-14 h-14 bg-[#e5eff5] text-[#0d52a1] rounded-[1.25rem] flex items-center justify-center text-3xl group-hover:scale-110 group-hover:bg-[#0d52a1] group-hover:text-white transition-all shadow-sm">
                                    <i class="ph-duotone ph-folder-open"></i>
                                </div>
                                <!-- Tempat kosong agar layout tidak nabrak tombol edit -->
                                <div class="w-10"></div>
                            </div>

                            <div class="flex justify-between items-center mb-2">
                                <h4 class="font-black text-2xl text-[#2c3f61] leading-tight group-hover:text-[#0d52a1] transition-colors">
                                    <?php echo e($event->name); ?>

                                </h4>
                            </div>
                            
                            <p class="text-slate-500 text-sm font-medium line-clamp-2 mb-4 h-10">
                                <?php echo e($event->description ?? 'Tidak ada deskripsi kegiatan.'); ?>

                            </p>

                            <div class="pt-4 border-t border-slate-100 flex items-center justify-between mt-auto">
                                <div class="flex items-center gap-2 text-[#0d52a1] font-bold text-sm bg-[#56bbf1]/10 px-3 py-1.5 rounded-lg border border-[#56bbf1]/20">
                                    <i class="ph-bold ph-files"></i> <?php echo e($event->exams_count ?? 0); ?> Jadwal Ujian
                                </div>
                                <div class="text-slate-400 group-hover:text-[#0d52a1] group-hover:translate-x-1 transition-all">
                                    <i class="ph-bold ph-arrow-right text-xl"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-20 bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200 mt-4">
                        <div class="w-24 h-24 bg-[#56bbf1]/10 rounded-full flex items-center justify-center mx-auto mb-6 text-[#0d52a1]">
                            <i class="ph-duotone ph-folder-dashed text-5xl"></i>
                        </div>
                        <h3 class="text-[#2c3f61] font-bold text-xl mb-2">Belum Ada Kegiatan CBT</h3>
                        <p class="text-slate-500 max-w-xs mx-auto mb-8 text-sm">Buat Folder/Kegiatan pertama Anda, seperti "PSAT Genap 2026".</p>
                        <button @click="openModal = true" class="inline-flex items-center gap-2 px-6 py-3 bg-[#2c3f61] text-white rounded-xl font-bold hover:bg-[#1c2940] transition shadow-lg shadow-[#2c3f61]/30 text-sm">
                            <i class="ph-bold ph-plus"></i> Buat Kegiatan Baru
                        </button>
                    </div>
                <?php endif; ?>
            </div>

            
            <?php if(method_exists($events, 'links')): ?>
                <div class="mt-8">
                    <?php echo e($events->links()); ?>

                </div>
            <?php endif; ?>
        </div>

        
        <div x-show="openModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                <div x-show="openModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" @click="openModal = false"></div>

                <div x-show="openModal" x-transition.scale.origin.bottom class="relative inline-block w-full max-w-md p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-[2rem] sm:my-8 sm:p-8 border border-slate-100">
                    <div class="absolute top-0 right-0 pt-6 pr-6">
                        <button @click="openModal = false" class="text-slate-400 hover:text-slate-500 bg-slate-50 rounded-full p-2 transition">
                            <i class="ph-bold ph-x text-lg"></i>
                        </button>
                    </div>

                    <div class="sm:flex sm:items-start mb-6">
                        <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-[#e5eff5] rounded-full sm:mx-0 sm:h-12 sm:w-12 text-[#0d52a1]">
                            <i class="ph-bold ph-folder-plus text-2xl"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-xl font-black leading-6 text-[#2c3f61]" id="modal-title">Buat Kegiatan CBT Baru</h3>
                            <div class="mt-2">
                                <p class="text-sm text-slate-500 font-medium">Contoh: Penilaian Sumatif Akhir Tahun, Try Out UNBK, dll.</p>
                            </div>
                        </div>
                    </div>

                    <form action="<?php echo e(route('cbt.events.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Nama Kegiatan <span class="text-rose-500">*</span></label>
                                <input type="text" name="name" required class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-[#56bbf1] focus:ring-[#56bbf1] font-bold text-[#2c3f61] py-3.5 px-5 transition-all" placeholder="Misal: PSAT Kelas 7, 8, 9">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Deskripsi Singkat (Opsional)</label>
                                <textarea name="description" rows="2" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-[#56bbf1] focus:ring-[#56bbf1] font-medium text-[#2c3f61] py-3.5 px-5 transition-all" placeholder="Tahun Ajaran 2025/2026..."></textarea>
                            </div>
                        </div>

                        <div class="mt-8 flex gap-3">
                            <button type="button" @click="openModal = false" class="w-full inline-flex justify-center px-4 py-3.5 border border-slate-200 shadow-sm text-sm font-bold rounded-xl text-slate-600 bg-white hover:bg-slate-50 transition-colors">Batal</button>
                            <button type="submit" class="w-full inline-flex justify-center px-4 py-3.5 border border-transparent shadow-lg shadow-[#2c3f61]/20 text-sm font-bold rounded-xl text-white bg-[#2c3f61] hover:bg-[#1c2940] transition-colors">Simpan Kegiatan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        
        <div x-show="editModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                <div x-show="editModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" @click="editModal = false"></div>

                <div x-show="editModal" x-transition.scale.origin.bottom class="relative inline-block w-full max-w-md p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-[2rem] sm:my-8 sm:p-8 border border-slate-100">
                    <div class="absolute top-0 right-0 pt-6 pr-6">
                        <button @click="editModal = false" class="text-slate-400 hover:text-slate-500 bg-slate-50 rounded-full p-2 transition">
                            <i class="ph-bold ph-x text-lg"></i>
                        </button>
                    </div>

                    <div class="sm:flex sm:items-start mb-6">
                        <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-[#f9a282]/20 rounded-full sm:mx-0 sm:h-12 sm:w-12 text-[#c86845]">
                            <i class="ph-bold ph-pencil-simple text-2xl"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-xl font-black leading-6 text-[#2c3f61]">Edit Kegiatan CBT</h3>
                            <div class="mt-2">
                                <p class="text-sm text-slate-500 font-medium">Perbarui nama atau deskripsi dari folder kegiatan ini.</p>
                            </div>
                        </div>
                    </div>

                    <form :action="editFormAction" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Nama Kegiatan <span class="text-rose-500">*</span></label>
                                <input type="text" name="name" x-model="editName" required class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-[#56bbf1] focus:ring-[#56bbf1] font-bold text-[#2c3f61] py-3.5 px-5 transition-all" placeholder="Misal: PSAT Kelas 7, 8, 9">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Deskripsi Singkat (Opsional)</label>
                                <textarea name="description" x-model="editDesc" rows="2" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-[#56bbf1] focus:ring-[#56bbf1] font-medium text-[#2c3f61] py-3.5 px-5 transition-all" placeholder="Tahun Ajaran 2025/2026..."></textarea>
                            </div>
                        </div>

                        <div class="mt-8 flex gap-3">
                            <button type="button" @click="editModal = false" class="w-full inline-flex justify-center px-4 py-3.5 border border-slate-200 shadow-sm text-sm font-bold rounded-xl text-slate-600 bg-white hover:bg-slate-50 transition-colors">Batal</button>
                            <button type="submit" class="w-full inline-flex justify-center px-4 py-3.5 border border-transparent shadow-lg shadow-[#2c3f61]/20 text-sm font-bold rounded-xl text-white bg-[#2c3f61] hover:bg-[#1c2940] transition-colors">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const flashSuccess = document.getElementById('flash-success');
            if (flashSuccess) {
                Swal.fire({
                    icon: 'success', title: 'Berhasil!',
                    text: flashSuccess.getAttribute('data-message'),
                    timer: 3000, showConfirmButton: false,
                    customClass: { popup: 'rounded-[2rem]' }
                });
            }
            const flashError = document.getElementById('flash-error');
            if (flashError) {
                Swal.fire({
                    icon: 'error', title: 'Gagal!',
                    text: flashError.getAttribute('data-message'),
                    customClass: { popup: 'rounded-[2rem]' }
                });
            }
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/cbt/index.blade.php ENDPATH**/ ?>