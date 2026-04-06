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
    
    <?php $__env->startPush('scripts'); ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php $__env->stopPush(); ?>

     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            <?php echo e(__('Bank Soal Terpusat')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    
    <div class="py-8 sm:py-10 font-sans text-slate-800" 
         x-data="{ 
            search: '',
            createModalOpen: false,
            
            // Konfirmasi Hapus dengan SweetAlert
            confirmDelete(id) {
                Swal.fire({
                    title: 'Hapus Bank Soal?',
                    text: 'Semua soal di dalamnya akan ikut terhapus permanen!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e11d48',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    customClass: { popup: 'rounded-[2rem]' }
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-form-' + id).submit();
                    }
                });
            }
         }">
         
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            
            <div class="relative rounded-[2rem] bg-gray-900 bg-gradient-to-br from-slate-900 via-indigo-900 to-indigo-800 p-8 text-white shadow-xl shadow-indigo-900/30 overflow-hidden border border-white/10">
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <a href="<?php echo e(route('cbt.index')); ?>" class="text-xs font-bold text-indigo-300 hover:text-white transition flex items-center gap-1">
                                <i class="ph-bold ph-arrow-left"></i> Dashboard Ujian
                            </a>
                            <span class="text-white/30 text-xs">•</span>
                            <span class="text-[10px] font-bold text-indigo-200 uppercase tracking-wider">Gudang Soal</span>
                        </div>
                        <h1 class="text-3xl font-black tracking-tight leading-none text-white mb-2">Bank Soal Sekolah</h1>
                        <p class="text-indigo-200 text-sm font-medium">Kelola repositori soal untuk berbagai mata pelajaran dan ujian.</p>
                    </div>
                    
                    
                    <div>
                        <button @click="createModalOpen = true" class="group flex items-center gap-3 px-6 py-4 bg-white text-indigo-900 rounded-2xl font-bold hover:bg-indigo-50 transition shadow-lg">
                            <i class="ph-bold ph-plus-circle text-xl"></i>
                            <span>Buat Bank Soal</span>
                        </button>
                    </div>
                </div>

                
                <div class="mt-8 relative max-w-lg">
                    <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-indigo-300 text-lg"></i>
                    <input type="text" x-model="search" placeholder="Cari nama mapel, judul bank soal, atau kode..." 
                        class="w-full pl-12 pr-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-indigo-200 focus:bg-white/20 focus:ring-0 focus:border-white/40 transition font-medium backdrop-blur-sm">
                </div>
            </div>

            
            <div x-show="createModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0">
                
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="createModalOpen = false"></div>
                <div class="flex min-h-screen items-center justify-center p-4">
                    <div class="relative bg-white rounded-[2.5rem] shadow-2xl max-w-md w-full p-8 border border-slate-100"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                        
                        <h3 class="text-xl font-black text-slate-800 mb-6 flex items-center gap-2">
                            <i class="ph-fill ph-folder-plus text-indigo-600"></i> Bank Soal Baru
                        </h3>
                        <form action="<?php echo e(route('bank.store')); ?>" method="POST" class="space-y-4">
                            <?php echo csrf_field(); ?>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Judul Paket Soal</label>
                                <input type="text" name="title" required class="w-full rounded-xl border-slate-200 font-bold text-slate-700 py-3 px-4 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Contoh: PTS Matematika Ganjil">
                            </div>
                            
                            
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Mata Pelajaran</label>
                                <div class="relative">
                                    <select name="subject_name" required class="w-full rounded-xl border-slate-200 font-bold text-slate-700 py-3 px-4 focus:ring-indigo-500 focus:border-indigo-500 bg-white appearance-none cursor-pointer">
                                        <option value="" disabled selected>Pilih Mapel...</option>
                                        
                                        <?php if(isset($subjects) && $subjects->count() > 0): ?>
                                            <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                
                                                <option value="<?php echo e($subject->name); ?>"><?php echo e($subject->name); ?> (<?php echo e($subject->code ?? '-'); ?>)</option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                            <option value="" disabled>Belum ada data mapel</option>
                                        <?php endif; ?>
                                    </select>
                                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-500"><i class="ph-bold ph-caret-down"></i></div>
                                </div>
                                <?php if(!isset($subjects) || $subjects->count() == 0): ?>
                                    <p class="text-[10px] text-rose-500 mt-1 font-bold">* Tambahkan data Mata Pelajaran di menu Pengaturan terlebih dahulu.</p>
                                <?php endif; ?>
                            </div>

                            
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Tingkat Kelas</label>
                                <div class="relative">
                                    <select name="class_level" class="w-full rounded-xl border-slate-200 font-bold text-slate-700 py-3 px-4 focus:ring-indigo-500 focus:border-indigo-500 bg-white appearance-none cursor-pointer">
                                        <option value="" disabled selected>Pilih Tingkat Kelas...</option>
                                        <option value="7">Kelas 7</option>
                                        <option value="8">Kelas 8</option>
                                        <option value="9">Kelas 9</option>                                                                                                           
                                    </select>
                                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-500"><i class="ph-bold ph-caret-down"></i></div>
                                </div>
                            </div>

                            <div class="pt-4 flex gap-3">
                                <button type="button" @click="createModalOpen = false" class="flex-1 py-3 rounded-xl border border-slate-200 font-bold text-slate-500 hover:bg-slate-50">Batal</button>
                                <button type="submit" class="flex-1 py-3 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-500/30">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php $__empty_1 = true; $__currentLoopData = $banks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="bg-white border border-slate-100 rounded-[2rem] p-6 hover:shadow-xl hover:shadow-indigo-900/5 hover:border-indigo-200 transition-all duration-300 group relative flex flex-col h-full"
                         data-search="<?php echo e(strtolower($bank->title . ' ' . $bank->subject_name . ' ' . $bank->code)); ?>"
                         x-show="search === '' || $el.dataset.search.includes(search.toLowerCase())"
                         x-transition.duration.300ms>
                        
                        <div class="mb-5">
                            <div class="flex justify-between items-start mb-3">
                                <span class="inline-block px-3 py-1 bg-indigo-50 text-indigo-600 border border-indigo-100 rounded-lg text-[10px] font-black uppercase tracking-wide">
                                    <?php echo e($bank->subject_name); ?> • Kls <?php echo e($bank->class_level); ?>

                                </span>
                                <span class="text-[10px] font-mono text-slate-300 group-hover:text-slate-400 transition"><?php echo e($bank->code); ?></span>
                            </div>
                            <h4 class="font-black text-xl text-slate-800 leading-tight group-hover:text-indigo-600 transition-colors line-clamp-2">
                                <?php echo e($bank->title); ?>

                            </h4>
                        </div>
                        
                        <div class="flex-1 flex items-end">
                            <div class="flex items-center gap-2 text-slate-500 text-xs font-bold bg-slate-50 px-4 py-2 rounded-xl w-full border border-slate-100">
                                <i class="ph-fill ph-files text-indigo-400 text-lg"></i>
                                <span class="text-slate-700 text-sm"><?php echo e($bank->questions_count); ?></span> Soal
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-slate-50 flex gap-2">
                            <a href="<?php echo e(route('bank.manage', $bank->id)); ?>" class="flex-1 flex items-center justify-center p-2.5 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/20">
                                <i class="ph-bold ph-list-plus text-lg mr-2"></i> Isi Soal
                            </a>
                            
                            
                            <form id="delete-form-<?php echo e($bank->id); ?>" action="<?php echo e(route('bank.destroy', $bank->id)); ?>" method="POST">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="button" @click="confirmDelete(<?php echo e($bank->id); ?>)" class="w-10 h-10 flex items-center justify-center bg-white border border-rose-100 text-rose-500 rounded-xl hover:bg-rose-50 hover:border-rose-200 transition">
                                    <i class="ph-bold ph-trash text-lg"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-span-full text-center py-20 bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200">
                        <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                            <i class="ph-duotone ph-folder-open text-5xl"></i>
                        </div>
                        <h3 class="text-slate-800 font-bold text-xl mb-2">Belum ada Bank Soal</h3>
                        <p class="text-slate-500 text-sm">Buat bank soal pertama Anda untuk mulai menabung soal.</p>
                    </div>
                <?php endif; ?>
                
                
                <div x-show="search !== '' && $el.previousElementSibling && document.querySelectorAll('[data-search]:not([style*=\'display: none\'])').length === 0" 
                     class="col-span-full text-center py-10" style="display: none;">
                    <p class="text-slate-400 font-bold">Tidak ditemukan bank soal dengan kata kunci tersebut.</p>
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
<?php endif; ?><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/cbt/bank/index.blade.php ENDPATH**/ ?>