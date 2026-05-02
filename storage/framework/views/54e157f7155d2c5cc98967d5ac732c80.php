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

    <div class="py-8 sm:py-10 font-sans text-elevate-text bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <div class="relative rounded-[2rem] bg-elevate-gradient-main p-8 mb-8 text-elevate-dark shadow-xl shadow-elevate-accent/10 overflow-hidden border border-white/60">
                <div class="absolute -top-10 -left-10 w-48 h-48 bg-elevate-primary/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-3xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-elevate-peach/20 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-2xl"></div>
                <div class="absolute top-10 right-32 w-24 h-24 bg-white/40 rounded-2xl rotate-45 pointer-events-none shadow-sm"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="text-center md:text-left">
                        <div class="flex items-center justify-center md:justify-start gap-2 mb-2">
                            <span class="text-[10px] font-bold text-elevate-dark/70 uppercase tracking-wider bg-white/50 px-3 py-1 rounded-full border border-white/60 backdrop-blur-sm shadow-sm">Administrasi Sekolah</span>
                        </div>
                        <h1 class="text-3xl font-extrabold tracking-tight mb-2 flex items-center justify-center md:justify-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-elevate-accent/20 text-elevate-primary flex items-center justify-center shrink-0">
                                <i class="ph-bold ph-envelope-simple-open text-xl"></i>
                            </div>
                            Arsip Surat Masuk
                        </h1>
                        <p class="text-elevate-dark/80 text-sm font-medium leading-relaxed max-w-lg ml-0 md:ml-12">
                            Digitalisasi dokumen persuratan masuk. Pantau, catat, dan arsipkan surat resmi untuk kemudahan pencarian.
                        </p>
                        
                        <div class="mt-6 flex flex-wrap justify-center md:justify-start gap-3 ml-0 md:ml-12">
                            <a href="<?php echo e(route('letters.incoming.create')); ?>" class="group bg-white text-elevate-dark px-5 py-3 rounded-2xl font-bold text-sm transition-all hover:bg-slate-50 flex items-center gap-2 shadow-lg shadow-elevate-dark/5 border border-white active:scale-95">
                                <div class="w-7 h-7 rounded-full bg-elevate-accent/20 text-elevate-primary flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <i class="ph-bold ph-plus text-sm"></i>
                                </div>
                                <span>Input Surat Baru</span>
                            </a>
                        </div>
                    </div>
                    
                    
                    <div class="flex gap-3">
                        <div class="bg-white/60 backdrop-blur-md px-6 py-5 rounded-[2rem] border border-white shadow-sm text-center min-w-[140px]">
                            <span class="block text-4xl font-black text-elevate-dark mb-1"><?php echo e($letters->total()); ?></span>
                            <span class="text-[10px] uppercase font-bold text-elevate-primary tracking-wider">Total Surat</span>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                
                
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row gap-4 justify-between items-center">
                    <h3 class="font-black text-elevate-dark text-lg flex items-center gap-2">
                        <i class="ph-fill ph-list-dashes text-elevate-primary"></i> Data Surat Masuk
                    </h3>

                    <form action="<?php echo e(route('letters.incoming.index')); ?>" method="GET" class="relative w-full sm:w-80 group">
                        <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-elevate-primary transition-colors"></i>
                        <input type="text" name="search" 
                               value="<?php echo e(request('search')); ?>" 
                               placeholder="Cari No Surat / Pengirim / Perihal..." 
                               class="w-full pl-11 pr-4 py-3 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary focus:ring-elevate-primary text-sm font-bold text-elevate-dark transition-all">
                    </form>
                </div>

                
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50/80 text-slate-400 text-xs font-bold uppercase tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-5 w-48">Agenda & Diterima</th>
                                <th class="px-6 py-5">Identitas Surat</th>
                                <th class="px-6 py-5 w-1/3">Asal & Perihal</th>
                                <th class="px-6 py-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php $__empty_1 = true; $__currentLoopData = $letters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $letter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <td class="px-6 py-5 align-top">
                                    <div class="font-mono font-black text-elevate-primary bg-elevate-accent/10 px-3 py-1.5 rounded-lg border border-elevate-accent/20 inline-block text-sm mb-2 shadow-sm">
                                        #<?php echo e($letter->nomor_agenda); ?>

                                    </div>
                                    <div class="text-xs text-slate-500 font-medium flex items-center gap-1.5 mt-1">
                                        <i class="ph-bold ph-calendar-blank"></i> <?php echo e(\Carbon\Carbon::parse($letter->tgl_diterima)->format('d M Y')); ?>

                                    </div>
                                </td>
                                <td class="px-6 py-5 align-top">
                                    <div class="font-bold text-elevate-dark text-sm mb-1 leading-snug"><?php echo e($letter->nomor_surat); ?></div>
                                    <div class="text-xs text-slate-500 font-medium flex items-center gap-1.5 mb-2">
                                        <i class="ph-bold ph-calendar-blank text-slate-400"></i> Tgl Surat: <?php echo e(\Carbon\Carbon::parse($letter->tgl_surat)->format('d M Y')); ?>

                                    </div>
                                    <span class="inline-flex px-2 py-1 rounded border border-slate-200 bg-slate-50 text-[10px] font-bold text-slate-600 tracking-wider">
                                        <?php echo e($letter->sifat_surat); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-5 align-top">
                                    <div class="flex items-start gap-2 mb-2">
                                        <i class="ph-fill ph-buildings text-elevate-primary mt-0.5"></i>
                                        <span class="font-bold text-elevate-dark text-sm"><?php echo e($letter->asal_surat); ?></span>
                                    </div>
                                    <p class="text-sm text-slate-600 leading-relaxed line-clamp-2 font-medium">
                                        <?php echo e($letter->perihal); ?>

                                    </p>
                                </td>
                                <td class="px-6 py-5 align-top text-right">
                                    <div class="flex flex-col items-end gap-2">
                                        <?php if($letter->file_path): ?>
                                            <a href="<?php echo e(asset('storage/' . $letter->file_path)); ?>" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-elevate-accent/10 border border-elevate-accent/20 text-elevate-primary hover:bg-elevate-primary hover:text-white rounded-xl text-xs font-bold transition-all shadow-sm">
                                                <i class="ph-bold ph-download-simple text-base"></i> Lampiran
                                            </a>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-50 text-slate-400 rounded-lg text-xs font-medium border border-slate-100">
                                                <i class="ph-bold ph-file-dashed"></i> No File
                                            </span>
                                        <?php endif; ?>
                                        
                                        <div class="flex items-center gap-2 mt-1">
                                            <a href="<?php echo e(route('letters.incoming.edit', $letter->id)); ?>" class="w-9 h-9 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-amber-600 hover:border-amber-200 hover:bg-amber-50 hover:shadow-sm transition-all" title="Edit">
                                                <i class="ph-bold ph-pencil-simple text-lg"></i>
                                            </a>
                                            <button type="button" onclick="confirmDelete('<?php echo e($letter->id); ?>')" class="w-9 h-9 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-rose-600 hover:border-rose-200 hover:bg-rose-50 hover:shadow-sm transition-all" title="Hapus">
                                                <i class="ph-bold ph-trash text-lg"></i>
                                            </button>
                                        </div>

                                        <form id="delete-form-<?php echo e($letter->id); ?>" action="<?php echo e(route('letters.incoming.destroy', $letter->id)); ?>" method="POST" class="hidden">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="4" class="px-6 py-20 text-center">
                                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                                        <i class="ph-duotone ph-envelope-open text-4xl"></i>
                                    </div>
                                    <h3 class="text-elevate-dark font-bold text-lg">Belum ada Arsip Surat</h3>
                                    <p class="text-slate-500 text-sm mt-1">Silakan input surat masuk baru melalui tombol di atas.</p>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                
                <div class="p-6 border-t border-slate-100 bg-slate-50/50">
                    <?php echo e($letters->withQueryString()->links()); ?>

                </div>
            </div>
        </div>
    </div>

    <script>
        // Notifikasi Sukses
        <?php if(session('success')): ?>
            const Toast = Swal.mixin({
                toast: true, position: 'top-end', showConfirmButton: false, timer: 3000,
                timerProgressBar: true, customClass: { popup: 'rounded-[1.5rem]' }
            });
            Toast.fire({ icon: 'success', title: '<?php echo e(session('success')); ?>' });
        <?php endif; ?>

        // Konfirmasi Hapus
        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Surat?', 
                text: "Data surat dan file lampiran akan dihapus permanen.",
                icon: 'warning', 
                showCancelButton: true,
                confirmButtonColor: '#e11d48', 
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Hapus!', 
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-[2.5rem] font-sans border-0 shadow-2xl',
                    confirmButton: 'bg-rose-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-rose-700 transition-colors mx-2 shadow-lg shadow-rose-900/20',
                    cancelButton: 'bg-slate-100 text-slate-600 px-6 py-3 rounded-xl font-bold hover:bg-slate-200 transition-colors mx-2'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) document.getElementById('delete-form-' + id).submit();
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
<?php endif; ?><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/letters/incoming/index.blade.php ENDPATH**/ ?>