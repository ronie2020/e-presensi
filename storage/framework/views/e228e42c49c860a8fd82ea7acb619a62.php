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
            <?php echo e(__('Isi Folder - ' . $folder->name)); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-8 sm:py-10 font-sans text-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            
            <?php if(session('success')): ?>
                <div id="flash-success" data-message="<?php echo e(session('success')); ?>"></div>
            <?php endif; ?>

            
            <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-5 rounded-[2rem] shadow-sm border border-slate-100">
                <div>
                    <div class="flex items-center gap-2 text-slate-400 text-sm font-bold mb-1">
                        <a href="<?php echo e(route('bank.index')); ?>" class="hover:text-elevate-primary transition flex items-center gap-1">
                            <i class="ph-bold ph-folders"></i> Dashboard Folder
                        </a>
                        <span>/</span>
                        <span class="text-elevate-primary"><?php echo e($folder->name); ?></span>
                    </div>
                    <h1 class="text-2xl font-black text-elevate-dark tracking-tight">Daftar Mata Pelajaran</h1>
                </div>
                <div class="flex gap-2 w-full md:w-auto">
                <a href="<?php echo e(route('bank.create', ['folder_id' => $folder->id])); ?>" class="w-full md:w-auto px-6 py-3 bg-elevate-dark text-white font-bold rounded-xl hover:bg-elevate-dark/90 transition shadow-lg shadow-elevate-dark/20 flex items-center justify-center gap-2">
                    <i class="ph-bold ph-plus-circle text-lg"></i> Buat Bank Soal Mapel
                </a>
            </div>
        </div>

        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="col-span-1 md:col-span-2 bg-white rounded-[2rem] p-5 border border-slate-100 shadow-xl shadow-elevate-accent/5 flex items-center gap-4 hover:-translate-y-1 transition-transform">
                <div class="w-12 h-12 bg-elevate-accent/20 text-elevate-primary rounded-[1rem] flex items-center justify-center text-xl shrink-0 border border-elevate-accent/30"><i class="ph-bold ph-books"></i></div>
                <div><p class="text-[10px] text-slate-400 font-bold uppercase mb-0.5 tracking-wider">Total Mapel</p><h4 class="text-2xl font-black text-elevate-dark"><?php echo e($banks->count()); ?></h4></div>
            </div>
            <div class="col-span-1 md:col-span-2 bg-white rounded-[2rem] p-5 border border-slate-100 shadow-xl shadow-elevate-accent/5 flex items-center gap-4 hover:-translate-y-1 transition-transform">
                <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-[1rem] flex items-center justify-center text-xl shrink-0"><i class="ph-bold ph-list-numbers"></i></div>
                <div><p class="text-[10px] text-slate-400 font-bold uppercase mb-0.5 tracking-wider">Total Butir Soal</p><h4 class="text-2xl font-black text-elevate-dark"><?php echo e(number_format($totalQuestions ?? 0)); ?></h4></div>
            </div>
        </div>

        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php $__empty_1 = true; $__currentLoopData = $banks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="bg-white border border-slate-100 rounded-[2.5rem] p-6 hover:shadow-xl hover:shadow-elevate-accent/10 hover:border-elevate-accent/50 transition-all duration-300 group relative flex flex-col h-full">
                        
                        <div class="mb-4 pr-4">
                            <div class="flex flex-wrap items-center gap-2 mb-3">
                                <span class="inline-block px-3 py-1 bg-elevate-accent/10 text-elevate-primary border border-elevate-accent/20 rounded-lg text-[10px] font-black uppercase tracking-wide">
                                    Kelas <?php echo e($bank->class_level); ?>

                                </span>
                            </div>
                            <h4 class="font-black text-xl text-elevate-dark leading-tight group-hover:text-elevate-primary transition-colors line-clamp-2"><?php echo e($bank->title); ?></h4>
                            <p class="text-slate-500 text-sm font-bold mt-1"><?php echo e($bank->subject_name); ?></p>
                        </div>
                        
                        <div class="flex-1 space-y-4 mt-auto">
                            <div class="bg-white border border-slate-200 rounded-2xl p-4 flex items-center justify-between group/token hover:bg-elevate-soft/50 hover:border-elevate-accent/50 transition shadow-sm">
                                <div>
                                    <span class="text-[10px] text-slate-400 uppercase font-bold tracking-wider block mb-0.5">Jumlah Soal</span>
                                    <span class="font-mono font-black text-xl text-elevate-dark tracking-widest group-hover/token:text-elevate-primary"><?php echo e($bank->questions_count ?? 0); ?> Butir</span>
                                </div>
                                <div class="w-8 h-8 bg-slate-50 rounded-lg flex items-center justify-center text-slate-400 shadow-sm border border-slate-100 group-hover/token:text-elevate-primary transition"><i class="ph-bold ph-files"></i></div>
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div class="mt-5 pt-4 border-t border-slate-100 grid grid-cols-2 gap-2">
                            <!-- Baris 1: Kelola Soal (Utama) -->
                            <a href="<?php echo e(route('bank.manage', $bank->id)); ?>" class="col-span-2 flex items-center justify-center p-2.5 bg-elevate-soft text-elevate-primary border border-elevate-accent/30 rounded-xl text-xs font-bold hover:bg-elevate-primary hover:text-white transition-all active:scale-95">
                                <i class="ph-bold ph-list-numbers text-lg mr-2"></i> Kelola Soal
                            </a>

                            <!-- Baris 2: Edit & Hapus -->
                            <button onclick="editMapel(<?php echo e($bank->id); ?>, '<?php echo e(addslashes($bank->title)); ?>', '<?php echo e(addslashes($bank->subject_name)); ?>', '<?php echo e($bank->class_level); ?>', '<?php echo e($bank->cbt_bank_folder_id); ?>')" class="col-span-1 flex items-center justify-center p-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-50 hover:text-elevate-primary hover:border-elevate-accent/50 transition-all">
                                <i class="ph-bold ph-pencil-simple text-lg mr-2"></i> Edit
                            </button>

                            <button onclick="confirmDelete('<?php echo e($bank->id); ?>')" class="col-span-1 flex items-center justify-center p-2.5 bg-white border border-rose-200 text-rose-500 rounded-xl text-xs font-bold hover:bg-rose-50 hover:text-rose-600 transition-all active:scale-95">
                                <i class="ph-bold ph-trash text-lg mr-2"></i> Hapus
                            </button>

                            <form id="delete-form-<?php echo e($bank->id); ?>" action="<?php echo e(route('bank.destroy', $bank->id)); ?>" method="POST" class="hidden">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            </form>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    
                    <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-20 bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200 mt-4">
                        <div class="w-24 h-24 bg-elevate-soft rounded-full flex items-center justify-center mx-auto mb-6 text-elevate-primary/50">
                            <i class="ph-duotone ph-file-dashed text-5xl"></i>
                        </div>
                        <h3 class="text-elevate-dark font-bold text-xl mb-2">Folder Ini Masih Kosong</h3>
                        <p class="text-slate-500 max-w-xs mx-auto mb-8 text-sm">Silakan buat bank soal mata pelajaran baru yang akan dimasukkan ke dalam folder <b><?php echo e($folder->name); ?></b>.</p>
                        <a href="<?php echo e(route('bank.create', ['folder_id' => $folder->id])); ?>" class="inline-flex items-center gap-2 px-6 py-3.5 bg-elevate-dark text-white rounded-xl font-bold hover:bg-elevate-dark/90 transition shadow-lg shadow-elevate-dark/30 text-sm">
                            <i class="ph-bold ph-plus"></i> Tambah Bank Soal
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <?php
        $allFolders = \App\Models\CbtBankFolder::orderBy('name', 'asc')->get();
    ?>

    
    <div id="editMapelModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm p-4 flex items-center justify-center">
        <div class="bg-white rounded-[2rem] w-full max-w-md p-6 sm:p-8 shadow-2xl relative border border-slate-100">
            <div class="flex items-center justify-center w-12 h-12 mb-4 bg-elevate-peach/20 rounded-2xl text-elevate-peach border border-elevate-peach/50">
                <i class="ph-bold ph-pencil-simple text-2xl"></i>
            </div>
            <h3 class="text-xl font-black text-elevate-dark mb-4">Edit Info Mapel</h3>
            
            <form id="editMapelForm" action="" method="POST">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-elevate-primary uppercase mb-2 ml-1">Nama Bank Soal</label>
                        <input type="text" name="title" id="edit_title" required class="w-full rounded-2xl border-slate-200 bg-elevate-soft py-3.5 px-4 focus:bg-white focus:ring-4 focus:ring-elevate-accent/20 focus:border-elevate-accent transition-all font-bold text-elevate-dark">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-elevate-primary uppercase mb-2 ml-1">Mata Pelajaran</label>
                        <input type="text" name="subject_name" id="edit_subject" required class="w-full rounded-2xl border-slate-200 bg-elevate-soft py-3.5 px-4 focus:bg-white focus:ring-4 focus:ring-elevate-accent/20 focus:border-elevate-accent transition-all text-elevate-dark">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-elevate-primary uppercase mb-2 ml-1">Kelas</label>
                            <select name="class_level" id="edit_class" class="w-full rounded-2xl border-slate-200 bg-elevate-soft py-3.5 px-4 focus:bg-white focus:ring-4 focus:ring-elevate-accent/20 focus:border-elevate-accent transition-all text-elevate-dark">
                                <option value="7">Kelas 7</option><option value="8">Kelas 8</option><option value="9">Kelas 9</option><option value="Umum">Umum</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-elevate-primary uppercase mb-2 ml-1">Pindah Folder</label>
                            <select name="cbt_bank_folder_id" id="edit_folder" class="w-full rounded-2xl border-slate-200 bg-elevate-soft py-3.5 px-4 focus:bg-white focus:ring-4 focus:ring-elevate-accent/20 focus:border-elevate-accent transition-all text-elevate-dark font-medium">
                                <?php $__currentLoopData = $allFolders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($f->id); ?>"><?php echo e($f->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="mt-8 flex gap-3">
                    <button type="button" onclick="document.getElementById('editMapelModal').classList.add('hidden')" class="w-full py-3.5 border-2 border-slate-100 text-slate-600 rounded-full font-bold hover:bg-slate-50 transition-colors">Batal</button>
                    <button type="submit" class="w-full py-3.5 bg-elevate-dark text-white rounded-full font-bold hover:bg-elevate-primary shadow-lg shadow-elevate-dark/20 transition-all">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Ditambahkan parameter folderId
        function editMapel(id, title, subject, level, folderId) {
            document.getElementById('edit_title').value = title;
            document.getElementById('edit_subject').value = subject;
            document.getElementById('edit_class').value = level;
            
            // Set value dropdown ke folder saat ini
            if(folderId) {
                document.getElementById('edit_folder').value = folderId;
            }

            document.getElementById('editMapelForm').action = '/bank-soal/mapel/' + id;
            document.getElementById('editMapelModal').classList.remove('hidden');
        }
        
        function confirmDelete(id) {
            Swal.fire({ 
                title: 'Hapus Mapel?', 
                text: "Mapel beserta seluruh butir soal di dalamnya akan terhapus permanen!", 
                icon: 'warning', 
                showCancelButton: true, 
                confirmButtonColor: '#e11d48', 
                cancelButtonColor: '#64748b', 
                confirmButtonText: 'Hapus!', 
                cancelButtonText: 'Batal', 
                customClass: { popup: 'rounded-[2rem]' }
            }).then((result) => { 
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit(); 
                }
            })
        }
        
        document.addEventListener("DOMContentLoaded", function() {
            const flash = document.getElementById('flash-success');
            if (flash) Swal.fire({ icon: 'success', title: 'Berhasil!', text: flash.getAttribute('data-message'), timer: 3000, showConfirmButton: false, customClass: { popup: 'rounded-[2rem]' }});
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/cbt/bank/show.blade.php ENDPATH**/ ?>