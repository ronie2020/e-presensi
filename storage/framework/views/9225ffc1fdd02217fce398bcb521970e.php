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
    
    
    <div class="py-8 sm:py-10 font-sans text-slate-800">
        
        
        <?php
            $announcementTime = isset($scheduleData['announcement_date']) ? \Carbon\Carbon::parse($scheduleData['announcement_date']) : null;
            $isSet = $announcementTime != null;
            $isPast = $isSet && \Carbon\Carbon::now()->greaterThanOrEqualTo($announcementTime);
        ?>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <div class="relative rounded-[2.5rem] bg-gray-900 bg-gradient-to-br from-blue-900 via-blue-800 to-slate-900 p-8 sm:p-10 text-white shadow-2xl shadow-blue-900/40 overflow-hidden border border-white/10 group mb-10">
                
                
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-96 h-96 bg-blue-500/20 rounded-full blur-3xl pointer-events-none group-hover:bg-blue-500/30 transition-all duration-700"></div>
                <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-full h-1/2 bg-gradient-to-t from-blue-900/50 to-transparent pointer-events-none"></div>

                <div class="relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-10 items-start">
                    
                    
                    <div>
                        <a href="<?php echo e(route('dashboard')); ?>" class="group bg-white/10 hover:bg-white/20 text-white px-5 py-3 rounded-2xl font-bold text-sm backdrop-blur-sm border border-white/10 transition-all flex items-center gap-2 shadow-sm w-fit mb-4 mx-auto xl:mx-0">
                            <i class="ph-bold ph-arrow-left text-m group-hover:-translate-x-1 transition-transform"></i>
                            <span>Kembali ke Dashboard</span>
                        </a>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-200 text-[10px] font-bold uppercase tracking-widest mb-6 backdrop-blur-sm">
                            <i class="ph-fill ph-users-three"></i> Portal Admin
                        </div>
                        <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-4 flex items-center gap-3 text-white leading-tight">
                            Data Pendaftar PPDB
                        </h1>
                        <p class="text-blue-100/80 text-sm md:text-base font-medium leading-relaxed max-w-lg mb-8">
                            Kelola data calon siswa, verifikasi berkas, dan atur jadwal pengumuman hasil seleksi dalam satu dashboard terpadu.
                        </p>

                        
                        <div class="grid grid-cols-3 gap-4">
                            <div class="bg-white/10 backdrop-blur-md px-5 py-4 rounded-2xl border border-white/10 hover:bg-white/15 transition-colors">
                                <span class="block text-[10px] font-bold uppercase tracking-wider text-blue-300 mb-1">Total Daftar</span>
                                <span class="block text-2xl font-black text-white tracking-tight"><?php echo e($stats['total']); ?></span>
                            </div>
                            <div class="bg-white/10 backdrop-blur-md px-5 py-4 rounded-2xl border border-white/10 hover:bg-white/15 transition-colors">
                                <span class="block text-[10px] font-bold uppercase tracking-wider text-emerald-300 mb-1">Diterima</span>
                                <span class="block text-2xl font-black text-white tracking-tight"><?php echo e($stats['accepted']); ?></span>
                            </div>
                            <div class="bg-white/10 backdrop-blur-md px-5 py-4 rounded-2xl border border-white/10 hover:bg-white/15 transition-colors">
                                <span class="block text-[10px] font-bold uppercase tracking-wider text-yellow-300 mb-1">Pending</span>
                                <span class="block text-2xl font-black text-white tracking-tight"><?php echo e($stats['pending']); ?></span>
                            </div>
                        </div>
                    </div>

                    
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-[2rem] p-6 lg:p-8 relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-4 opacity-5 text-white pointer-events-none">
                            <i class="ph-fill ph-calendar-check text-8xl"></i>
                        </div>

                        <div class="relative z-10">
                            <h3 class="text-lg font-black text-white mb-1 flex items-center gap-2">
                                <i class="ph-duotone ph-clock text-blue-300"></i> Jadwal Pengumuman
                            </h3>
                            <p class="text-blue-100 text-xs font-medium mb-6">Atur kapan hasil seleksi dapat dilihat siswa.</p>
                            
                            
                            <div class="mb-6 p-4 rounded-2xl bg-black/20 border border-white/10">
                                <?php if($isSet): ?>
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="relative flex h-3 w-3">
                                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full <?php echo e($isPast ? 'bg-emerald-400' : 'bg-blue-400'); ?> opacity-75"></span>
                                          <span class="relative inline-flex rounded-full h-3 w-3 <?php echo e($isPast ? 'bg-emerald-500' : 'bg-blue-500'); ?>"></span>
                                        </span>
                                        <span class="text-xs font-black uppercase tracking-wider <?php echo e($isPast ? 'text-emerald-300' : 'text-blue-300'); ?>">
                                            <?php echo e($isPast ? 'Sudah Dibuka' : 'Terjadwal (Menunggu)'); ?>

                                        </span>
                                    </div>
                                    <p class="text-white text-sm font-bold tracking-wide">
                                        <?php echo e($announcementTime->translatedFormat('l, d F Y')); ?> <span class="text-blue-300 mx-1">•</span> <?php echo e($announcementTime->format('H:i')); ?> WIB
                                    </p>
                                <?php else: ?>
                                    <div class="flex items-center gap-3 mb-1">
                                        <span class="h-3 w-3 rounded-full bg-amber-400"></span>
                                        <span class="text-xs font-black uppercase tracking-wider text-amber-300">Belum Diatur</span>
                                    </div>
                                    <p class="text-white/60 text-xs italic">Siswa belum dapat melihat hasil.</p>
                                <?php endif; ?>
                            </div>

                            
                            <form action="<?php echo e(route('admin.ppdb.set_schedule')); ?>" method="POST" class="space-y-3">
                                <?php echo csrf_field(); ?>
                                <div class="relative group">
                                    <i class="ph-bold ph-calendar-plus absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-600 transition-colors z-10"></i>
                                    <input type="datetime-local" name="announcement_date" required 
                                           value="<?php echo e($isSet ? $announcementTime->format('Y-m-d\TH:i') : ''); ?>"
                                           class="block w-full pl-11 pr-4 py-3 rounded-xl border-white/20 bg-white/90 focus:bg-white text-slate-800 text-sm font-bold shadow-lg focus:ring-blue-500 focus:border-blue-500 transition-all placeholder:text-slate-400">
                                </div>
                                <button type="submit" class="w-full py-3 bg-blue-500 hover:bg-blue-400 text-white font-bold rounded-xl shadow-lg shadow-blue-900/30 transition-all text-sm flex items-center justify-center gap-2 transform active:scale-[0.98]">
                                    <i class="ph-bold ph-floppy-disk"></i> Simpan Jadwal
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>

            
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden relative min-h-[600px] flex flex-col">
                
                
                <div class="p-8 border-b border-slate-50 bg-slate-50/30 flex flex-col xl:flex-row xl:items-center justify-between gap-6">
                    
                    
                    <div class="flex flex-wrap gap-2 items-center">
                        <?php
                            $btnBase = "px-5 py-2.5 text-xs font-bold rounded-xl border transition-all shadow-sm flex items-center gap-2";
                            $activeClass = "bg-blue-900 text-white border-blue-900 shadow-blue-900/20";
                            $inactiveClass = "bg-white text-slate-500 border-slate-200 hover:border-blue-300 hover:text-blue-700";
                        ?>

                        <a href="<?php echo e(route('admin.ppdb.index')); ?>" 
                           class="<?php echo e($btnBase); ?> <?php echo e(!request('status') ? $activeClass : $inactiveClass); ?>">
                           <i class="ph-bold ph-squares-four"></i> Semua
                        </a>
                        <a href="<?php echo e(route('admin.ppdb.index', ['status' => 'pending'])); ?>" 
                           class="<?php echo e($btnBase); ?> <?php echo e(request('status') == 'pending' ? 'bg-yellow-500 text-white border-yellow-500' : $inactiveClass); ?>">
                           <i class="ph-bold ph-clock"></i> Pending
                        </a>
                        <a href="<?php echo e(route('admin.ppdb.index', ['status' => 'verified'])); ?>" 
                           class="<?php echo e($btnBase); ?> <?php echo e(request('status') == 'verified' ? 'bg-blue-500 text-white border-blue-500' : $inactiveClass); ?>">
                           <i class="ph-bold ph-check-circle"></i> Verified
                        </a>
                        <a href="<?php echo e(route('admin.ppdb.index', ['status' => 'accepted'])); ?>" 
                           class="<?php echo e($btnBase); ?> <?php echo e(request('status') == 'accepted' ? 'bg-emerald-500 text-white border-emerald-500' : $inactiveClass); ?>">
                           <i class="ph-bold ph-medal"></i> Diterima
                        </a>

                        
                        <button type="button" onclick="submitBulk()" class="ml-2 px-5 py-2.5 bg-emerald-600 text-white rounded-xl font-bold text-xs hover:bg-emerald-700 transition flex items-center gap-2 shadow-lg shadow-emerald-500/20">
                            <i class="ph-bold ph-users-three"></i> Pindahkan Terpilih
                        </button>
                    </div>
                    
                    
                    <form method="GET" class="relative group w-full xl:w-80">
                        <input type="hidden" name="status" value="<?php echo e(request('status')); ?>">
                        <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-600 transition-colors"></i>
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari Nama / NISN..." 
                               class="w-full pl-11 pr-4 py-3 rounded-xl border-slate-200 bg-white focus:border-blue-600 focus:ring-blue-600 text-sm font-bold text-slate-700 transition-colors shadow-sm">
                    </form>
                </div>

                
                <div class="overflow-x-auto flex-1 custom-scrollbar">
                    
                    
                    <form action="<?php echo e(route('admin.ppdb.bulk_promote')); ?>" method="POST" id="bulkForm">
                        <?php echo csrf_field(); ?>
                        
                        <table class="w-full text-sm text-left text-slate-600">
                            <thead class="bg-slate-50 text-xs font-bold text-slate-400 uppercase tracking-wider sticky top-0 z-10">
                                <tr>
                                    
                                    <th class="px-6 py-5 w-10 text-center">
                                        <input type="checkbox" id="checkAll" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-4 h-4 cursor-pointer">
                                    </th>
                                    <th class="px-6 py-5 whitespace-nowrap">No. Daftar</th>
                                    <th class="px-6 py-5 whitespace-nowrap">Identitas Siswa</th>
                                    <th class="px-6 py-5 text-center whitespace-nowrap">Jalur</th>
                                    <th class="px-6 py-5 text-center whitespace-nowrap">Nilai</th>
                                    <th class="px-6 py-5 text-center whitespace-nowrap">Status</th>
                                    <th class="px-6 py-5 text-right whitespace-nowrap">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                <?php $__empty_1 = true; $__currentLoopData = $registrants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-blue-50/30 transition-colors group">
                                    
                                    <td class="px-6 py-5 text-center">
                                        <?php if($item->status == 'accepted'): ?>
                                            <input type="checkbox" name="selected_ids[]" value="<?php echo e($item->id); ?>" class="check-item rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-4 h-4 cursor-pointer">
                                        <?php else: ?>
                                            <span class="text-slate-300 cursor-not-allowed" title="Hanya siswa DITERIMA yang bisa dipindahkan">
                                                <i class="ph-bold ph-minus"></i>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-5">
                                        <span class="font-mono font-bold text-slate-500 bg-slate-100 px-2 py-1 rounded text-xs border border-slate-200">
                                            <?php echo e($item->registration_number); ?>

                                        </span>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-100 to-indigo-100 text-blue-600 flex items-center justify-center font-black text-sm border border-blue-200">
                                                <?php echo e(substr($item->full_name, 0, 1)); ?>

                                            </div>
                                            <div>
                                                <div class="font-bold text-slate-800 text-base mb-0.5"><?php echo e($item->full_name); ?></div>
                                                <div class="text-xs text-slate-400 font-bold flex items-center gap-1">
                                                    <i class="ph-bold ph-graduation-cap"></i> <?php echo e($item->school_origin); ?>

                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-center whitespace-nowrap">
                                        <?php
                                            $trackClass = match($item->track) {
                                                'prestasi' => 'text-purple-600 bg-purple-50 border-purple-200',
                                                'afirmasi' => 'text-orange-600 bg-orange-50 border-orange-200',
                                                default => 'text-blue-600 bg-blue-50 border-blue-200'
                                            };
                                        ?>
                                        <span class="px-3 py-1.5 rounded-xl text-[10px] font-black uppercase border <?php echo e($trackClass); ?>">
                                            <?php echo e($item->track); ?>

                                        </span>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <span class="font-black text-slate-700 text-base"><?php echo e($item->average_grade); ?></span>
                                    </td>
                                    <td class="px-6 py-5 text-center whitespace-nowrap">
                                        <?php if($item->status == 'pending'): ?>
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-bold bg-yellow-50 text-yellow-700 border border-yellow-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse"></span> Menunggu
                                            </span>
                                        <?php elseif($item->status == 'verified'): ?>
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                                <i class="ph-fill ph-check-circle"></i> Terverifikasi
                                            </span>
                                        <?php elseif($item->status == 'accepted'): ?>
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <i class="ph-fill ph-medal"></i> Diterima
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                                <i class="ph-fill ph-x-circle"></i> Ditolak
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-5 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity transform translate-x-2 group-hover:translate-x-0 duration-200">
                                            
                                            <a href="<?php echo e(route('admin.ppdb.show', $item->id)); ?>" 
                                            class="w-9 h-9 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 transition-all shadow-sm" title="Lihat Detail">
                                                <i class="ph-bold ph-eye text-lg"></i>
                                            </a>

                                            
                                            
                                            
                                            <button type="button" 
                                                onclick="confirmDelete('<?php echo e($item->id); ?>', '<?php echo e($item->full_name); ?>')"
                                                class="w-9 h-9 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-rose-600 hover:border-rose-200 hover:bg-rose-50 transition-all shadow-sm" title="Hapus Data">
                                                <i class="ph-bold ph-trash text-lg"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7" class="px-6 py-20 text-center">
                                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                                            <i class="ph-duotone ph-folder-open text-5xl"></i>
                                        </div>
                                        <p class="text-sm font-bold text-slate-600">Belum ada data pendaftar.</p>
                                        <p class="text-xs text-slate-400 mt-1">Data siswa yang mendaftar akan muncul di sini.</p>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </form> 

                    
                    <?php $__currentLoopData = $registrants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <form action="<?php echo e(route('admin.ppdb.destroy', $item->id)); ?>" method="POST" id="delete-form-<?php echo e($item->id); ?>" class="hidden">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                        </form>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </div>
                
                
                <?php if($registrants->hasPages()): ?>
                <div class="p-6 border-t border-slate-50 bg-slate-50/50">
                    <?php echo e($registrants->withQueryString()->links()); ?>

                </div>
                <?php endif; ?>
            </div>

        </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // FLASH MESSAGES
            <?php if(session('success')): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '<?php echo e(session('success')); ?>',
                    confirmButtonColor: '#1e3a8a',
                    customClass: {
                        popup: 'rounded-[2rem] font-sans border-0 shadow-2xl',
                        confirmButton: 'bg-blue-900 text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-800 transition-colors shadow-lg shadow-blue-900/20',
                    },
                    buttonsStyling: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            <?php endif; ?>

            <?php if(session('error')): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '<?php echo e(session('error')); ?>',
                    confirmButtonColor: '#e11d48',
                    customClass: {
                        popup: 'rounded-[2rem] font-sans border-0 shadow-2xl',
                        confirmButton: 'bg-rose-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-rose-700 transition-colors shadow-lg shadow-rose-900/20',
                    },
                    buttonsStyling: false
                });
            <?php endif; ?>

            // LOGIC CHECKBOX "SELECT ALL"
            const checkAll = document.getElementById('checkAll');
            const checkItems = document.querySelectorAll('.check-item');

            if(checkAll) {
                checkAll.addEventListener('change', function() {
                    checkItems.forEach(item => {
                        item.checked = this.checked;
                    });
                });
            }
        });

        // LOGIC SUBMIT BULK ACTION
        function submitBulk() {
            const selected = document.querySelectorAll('.check-item:checked').length;
            
            if(selected === 0) {
                Swal.fire({
                    title: 'Belum Ada Data Dipilih',
                    text: 'Silakan centang minimal satu siswa yang berstatus DITERIMA.',
                    icon: 'warning',
                    confirmButtonColor: '#f59e0b',
                    confirmButtonText: 'Oke',
                    customClass: { popup: 'rounded-[2rem] font-sans' }
                });
                return;
            }

            Swal.fire({
                title: `Pindahkan ${selected} Siswa?`,
                text: "Data siswa yang dipilih akan disalin ke Data Induk Siswa Aktif.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#059669',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Proses!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-[2rem] font-sans border-0 shadow-2xl',
                    confirmButton: 'bg-emerald-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-emerald-700 transition-colors mx-2 shadow-lg shadow-emerald-500/20',
                    cancelButton: 'bg-slate-100 text-slate-600 px-6 py-3 rounded-xl font-bold hover:bg-slate-200 transition-colors mx-2'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('bulkForm').submit();
                }
            });
        }

        // FUNGSI KONFIRMASI HAPUS
        function confirmDelete(id, name) {
            Swal.fire({
                title: 'Hapus Pendaftar?',
                html: `Data pendaftar <b>${name}</b> akan dihapus permanen.<br><span class="text-xs text-rose-500 font-bold mt-2 block">Berkas yang diupload juga akan dihapus.</span>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-[2rem] font-sans border-0 shadow-2xl',
                    confirmButton: 'bg-rose-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-rose-700 transition-colors mx-2 shadow-lg shadow-rose-900/20',
                    cancelButton: 'bg-slate-100 text-slate-600 px-6 py-3 rounded-xl font-bold hover:bg-slate-200 transition-colors mx-2'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('delete-form-' + id);
                    if (form) form.submit();
                }
            });
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/admin/ppdb/index.blade.php ENDPATH**/ ?>