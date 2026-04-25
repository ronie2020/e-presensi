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
            <?php echo e(__('CBT Dashboard - Kategori Kegiatan')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    
    <div class="py-8 sm:py-10 font-sans text-slate-800" 
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

            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                
                <div class="bg-gradient-to-br from-cyan-500 via-blue-600 to-blue-900 rounded-[2rem] p-8 text-white shadow-xl shadow-cyan-900/30 relative overflow-hidden group border border-white/10">
                    <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                    <div class="absolute right-0 top-0 opacity-10 transform translate-x-8 -translate-y-8 group-hover:scale-110 transition-transform duration-500">
                        <i class="ph-fill ph-folders text-[10rem]"></i>
                    </div>
                    
                    <div class="relative z-10 h-full flex flex-col justify-between">
                        <div>
                            <a href="<?php echo e(route('dashboard')); ?>" class="group bg-white/10 hover:bg-white/20 text-white px-5 py-3 rounded-2xl font-bold text-sm backdrop-blur-sm border border-white/10 transition-all flex items-center gap-2 shadow-sm w-fit mb-4 mx-auto xl:mx-0">
                                <i class="ph-bold ph-arrow-left text-lg group-hover:-translate-x-1 transition-transform"></i>
                                <span>Kembali ke Dashboard</span>
                            </a>
                            <p class="text-cyan-200 font-bold text-sm mb-1 flex items-center gap-2"><i class="ph-bold ph-calendar-blank"></i> Hari Ini</p>
                            <h3 class="text-3xl font-black tracking-tight leading-tight"><?php echo e(\Carbon\Carbon::now()->translatedFormat('l, d F Y')); ?></h3>
                        </div>
                        <div class="mt-6">
                            <span class="bg-white/10 backdrop-blur-md px-4 py-2.5 rounded-xl text-sm font-bold border border-white/20 shadow-sm inline-flex items-center gap-2">
                                <span class="bg-emerald-400 w-2 h-2 rounded-full animate-pulse"></span>
                                <?php echo e(method_exists($events, 'total') ? $events->total() : $events->count()); ?> Kegiatan/Folder Ujian
                            </span>
                        </div>
                    </div>
                </div>
                
                
                <div class="md:col-span-2 bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 flex flex-col md:flex-row items-center justify-between relative overflow-hidden">
                    <div class="absolute inset-0 bg-slate-50/50 opacity-0 md:opacity-100 bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:20px_20px]"></div>
                    
                    <div class="relative z-10 max-w-lg mb-6 md:mb-0 text-center md:text-left">
                        <h3 class="font-black text-slate-800 text-2xl mb-2">Halo, <?php echo e(Auth::user()->name); ?>! </h3>
                        <p class="text-slate-500 leading-relaxed font-medium text-sm">
                            Kelola jadwal CBT berdasarkan kelompok kegiatan besar seperti PSAT, Ulangan Harian, atau Penilaian Akhir Jenjang agar lebih terstruktur.
                        </p>
                    </div>

                    <div class="relative z-10 flex flex-col gap-3">
                        <button @click="openModal = true" class="group flex items-center gap-3 px-6 py-4 bg-cyan-600 text-white rounded-2xl font-bold hover:bg-cyan-500 active:scale-95 transition-all shadow-lg shadow-cyan-500/30 w-full md:w-auto justify-center">
                            <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center group-hover:scale-110 transition">
                                <i class="ph-bold ph-folder-plus text-white"></i>
                            </div>
                            <span>Buat Kegiatan Baru</span>
                        </button>
                    </div>
                </div>
            </div>

            
            <form id="filterForm" action="<?php echo e(route('cbt.index')); ?>" method="GET" class="flex flex-col md:flex-row items-center justify-between mb-6 px-2 gap-4">
                <h3 class="font-bold text-slate-800 text-xl flex items-center gap-2 shrink-0 w-full md:w-auto">
                    <div class="w-1.5 h-6 bg-cyan-500 rounded-full"></div>
                    Daftar Kegiatan CBT (Folder)
                </h3>

                <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                    
                    <div class="relative w-full sm:w-64 group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-cyan-500 transition-colors">
                            <i class="ph-bold ph-magnifying-glass"></i>
                        </div>
                        <input name="search" value="<?php echo e(request('search')); ?>" type="text" class="w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 bg-white text-sm focus:border-cyan-500 focus:ring-4 focus:ring-cyan-50 transition-all font-medium placeholder-slate-400 shadow-sm" placeholder="Cari Kegiatan...">
                        <button type="submit" class="hidden"></button>
                    </div>
                </div>
            </form>

            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php $__empty_1 = true; $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="bg-white border border-slate-200 rounded-[2rem] hover:shadow-xl hover:shadow-cyan-900/5 hover:border-cyan-300 transition-all duration-300 group relative block overflow-hidden">
                        
                        <!-- Pattern Background on Hover -->
                        <div class="absolute inset-0 bg-cyan-50/0 group-hover:bg-cyan-50/50 transition-colors pointer-events-none z-0"></div>
                        <div class="absolute -right-4 -top-4 text-cyan-50 opacity-0 group-hover:opacity-100 transition-opacity transform group-hover:scale-110 pointer-events-none z-0">
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
                                    class="w-10 h-10 flex items-center justify-center bg-white/90 backdrop-blur-sm text-cyan-600 rounded-[1rem] hover:bg-cyan-600 hover:text-white shadow-sm border border-cyan-100 transition-all">
                                <i class="ph-bold ph-pencil-simple text-lg"></i>
                            </button>
                        </div>

                        <!-- KONTEN UTAMA YANG BISA DI KLIK MENUJU DAFTAR SOAL -->
                        <a href="<?php echo e(route('cbt.events.show', $event->id)); ?>" class="p-6 block relative z-10 h-full">
                            <div class="flex items-start justify-between mb-4">
                                <div class="w-14 h-14 bg-cyan-50 text-cyan-600 rounded-[1.25rem] flex items-center justify-center text-3xl group-hover:scale-110 group-hover:bg-cyan-600 group-hover:text-white transition-all shadow-sm">
                                    <i class="ph-duotone ph-folder-open"></i>
                                </div>
                                <!-- Tempat kosong agar layout tidak nabrak tombol edit -->
                                <div class="w-10"></div>
                            </div>

                            <div class="flex justify-between items-center mb-2">
                                <h4 class="font-black text-2xl text-slate-800 leading-tight group-hover:text-cyan-700 transition-colors">
                                    <?php echo e($event->name); ?>

                                </h4>
                            </div>
                            
                            <p class="text-slate-500 text-sm font-medium line-clamp-2 mb-4 h-10">
                                <?php echo e($event->description ?? 'Tidak ada deskripsi kegiatan.'); ?>

                            </p>

                            <div class="pt-4 border-t border-slate-100 flex items-center justify-between mt-auto">
                                <div class="flex items-center gap-2 text-cyan-600 font-bold text-sm bg-cyan-50 px-3 py-1.5 rounded-lg">
                                    <i class="ph-bold ph-files"></i> <?php echo e($event->exams_count ?? 0); ?> Jadwal Ujian
                                </div>
                                <div class="text-slate-400 group-hover:text-cyan-600 group-hover:translate-x-1 transition-all">
                                    <i class="ph-bold ph-arrow-right text-xl"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-20 bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200 mt-4">
                        <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                            <i class="ph-duotone ph-folder-dashed text-5xl"></i>
                        </div>
                        <h3 class="text-slate-800 font-bold text-xl mb-2">Belum Ada Kegiatan CBT</h3>
                        <p class="text-slate-500 max-w-xs mx-auto mb-8 text-sm">Buat Folder/Kegiatan pertama Anda, seperti "PSAT Genap 2026".</p>
                        <button @click="openModal = true" class="inline-flex items-center gap-2 px-6 py-3 bg-cyan-600 text-white rounded-xl font-bold hover:bg-cyan-500 transition shadow-lg shadow-cyan-500/30 text-sm">
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
                        <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-cyan-50 rounded-full sm:mx-0 sm:h-12 sm:w-12 text-cyan-600">
                            <i class="ph-bold ph-folder-plus text-2xl"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-xl font-black leading-6 text-slate-800" id="modal-title">Buat Kegiatan CBT Baru</h3>
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
                                <input type="text" name="name" required class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-cyan-500 focus:ring-cyan-500 font-bold text-slate-700 py-3.5 px-5 transition-all" placeholder="Misal: PSAT Kelas 7, 8, 9">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Deskripsi Singkat (Opsional)</label>
                                <textarea name="description" rows="2" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-cyan-500 focus:ring-cyan-500 font-medium text-slate-700 py-3.5 px-5 transition-all" placeholder="Tahun Ajaran 2025/2026..."></textarea>
                            </div>
                        </div>

                        <div class="mt-8 flex gap-3">
                            <button type="button" @click="openModal = false" class="w-full inline-flex justify-center px-4 py-3.5 border border-slate-200 shadow-sm text-sm font-bold rounded-xl text-slate-700 bg-white hover:bg-slate-50">Batal</button>
                            <button type="submit" class="w-full inline-flex justify-center px-4 py-3.5 border border-transparent shadow-lg shadow-cyan-500/30 text-sm font-bold rounded-xl text-white bg-cyan-600 hover:bg-cyan-700">Simpan Kegiatan</button>
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
                        <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-cyan-50 rounded-full sm:mx-0 sm:h-12 sm:w-12 text-cyan-600">
                            <i class="ph-bold ph-pencil-simple text-2xl"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-xl font-black leading-6 text-slate-800">Edit Kegiatan CBT</h3>
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
                                <input type="text" name="name" x-model="editName" required class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-cyan-500 focus:ring-cyan-500 font-bold text-slate-700 py-3.5 px-5 transition-all" placeholder="Misal: PSAT Kelas 7, 8, 9">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Deskripsi Singkat (Opsional)</label>
                                <textarea name="description" x-model="editDesc" rows="2" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-cyan-500 focus:ring-cyan-500 font-medium text-slate-700 py-3.5 px-5 transition-all" placeholder="Tahun Ajaran 2025/2026..."></textarea>
                            </div>
                        </div>

                        <div class="mt-8 flex gap-3">
                            <button type="button" @click="editModal = false" class="w-full inline-flex justify-center px-4 py-3.5 border border-slate-200 shadow-sm text-sm font-bold rounded-xl text-slate-700 bg-white hover:bg-slate-50">Batal</button>
                            <button type="submit" class="w-full inline-flex justify-center px-4 py-3.5 border border-transparent shadow-lg shadow-cyan-500/30 text-sm font-bold rounded-xl text-white bg-cyan-600 hover:bg-cyan-700">Simpan Perubahan</button>
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