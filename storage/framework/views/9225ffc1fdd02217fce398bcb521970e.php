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
    
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .count-up { font-variant-numeric: tabular-nums; }
        @keyframes wiggle { 0%, 100% { transform: rotate(0deg); } 25% { transform: rotate(-10deg); } 75% { transform: rotate(10deg); } }
        .group:hover .animate-wiggle { animation: wiggle 0.5s ease-in-out; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .glass-panel { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1); }
    </style>

    
    <?php
        $announcementTime = isset($scheduleData['announcement_date']) ? \Carbon\Carbon::parse($scheduleData['announcement_date']) : null;
        $isSet = $announcementTime != null;
        $isPast = $isSet && \Carbon\Carbon::now()->greaterThanOrEqualTo($announcementTime);
    ?>

    <div class="relative space-y-8 min-h-screen pb-10 font-sans text-slate-800">
        
        
        <div class="animate-enter relative rounded-[2.5rem] bg-gradient-to-r from-blue-900 via-slate-800 to-slate-900 p-6 md:p-10 text-white shadow-2xl shadow-blue-900/20 overflow-hidden group border border-white/10">
            
            
            <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-blue-500 rounded-full mix-blend-overlay filter blur-[120px] opacity-20 group-hover:opacity-30 transition-opacity duration-1000"></div>
            <div class="absolute bottom-0 left-0 w-[300px] h-[300px] bg-indigo-500 rounded-full mix-blend-overlay filter blur-[100px] opacity-20"></div>
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 pointer-events-none"></div>

            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
                
                
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/10 text-blue-200 text-xs font-bold uppercase tracking-wider mb-4 backdrop-blur-sm shadow-sm">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span>
                        </span>
                        Portal Penerimaan Siswa Baru
                    </div>
                    
                    <h1 class="text-3xl md:text-5xl font-extrabold text-white tracking-tight mb-3">
                        Manajemen <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-white">PPDB Online</span> 
                    </h1>
                    <p class="text-blue-100/80 text-sm md:text-base max-w-xl leading-relaxed mb-6">
                        Kelola data calon siswa, verifikasi berkas, dan atur jadwal pengumuman hasil seleksi dalam satu dashboard terpadu.
                    </p>

                    <div class="flex gap-3">
                        <a href="<?php echo e(route('dashboard')); ?>" class="px-5 py-2.5 bg-white/10 hover:bg-white/20 text-white text-sm font-bold rounded-xl transition border border-white/10 flex items-center gap-2">
                            <i class="ph-bold ph-arrow-left"></i> Kembali ke Dashboard
                        </a>
                    </div>
                </div>

                
                <div class="glass-panel p-6 rounded-[2rem] relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-4 opacity-10 text-white pointer-events-none">
                        <i class="ph-fill ph-calendar-check text-8xl"></i>
                    </div>

                    <h3 class="text-lg font-black text-white mb-1 flex items-center gap-2 relative z-10">
                        <i class="ph-duotone ph-clock text-blue-300"></i> Jadwal Pengumuman
                    </h3>
                    <p class="text-blue-100 text-xs font-medium mb-4 relative z-10">Atur kapan hasil seleksi dapat dilihat siswa.</p>
                    
                    
                    <div class="mb-4 p-3 rounded-xl bg-black/20 border border-white/10 relative z-10">
                        <?php if($isSet): ?>
                            <div class="flex items-center gap-3 mb-1">
                                <span class="text-[10px] font-black uppercase tracking-wider <?php echo e($isPast ? 'text-emerald-300' : 'text-blue-300'); ?>">
                                    <?php echo e($isPast ? '● Sudah Dibuka' : '● Terjadwal'); ?>

                                </span>
                            </div>
                            <p class="text-white text-sm font-bold tracking-wide font-mono">
                                <?php echo e($announcementTime->translatedFormat('d M Y, H:i')); ?> WIB
                            </p>
                        <?php else: ?>
                            <div class="flex items-center gap-3 mb-1">
                                <span class="text-[10px] font-black uppercase tracking-wider text-amber-300">● Belum Diatur</span>
                            </div>
                            <p class="text-white/60 text-xs italic">Siswa belum dapat melihat hasil.</p>
                        <?php endif; ?>
                    </div>

                    
                    <form action="<?php echo e(route('admin.ppdb.set_schedule')); ?>" method="POST" class="space-y-2 relative z-10">
                        <?php echo csrf_field(); ?>
                        <div class="flex gap-2">
                            <input type="datetime-local" name="announcement_date" required 
                                   value="<?php echo e($isSet ? $announcementTime->format('Y-m-d\TH:i') : ''); ?>"
                                   class="block w-full px-4 py-2.5 rounded-xl border-white/20 bg-white/10 focus:bg-white text-white focus:text-slate-900 text-xs font-bold shadow-lg focus:ring-blue-500 focus:border-blue-500 transition-all cursor-pointer">
                            <button type="submit" class="p-2.5 bg-blue-500 hover:bg-blue-400 text-white font-bold rounded-xl shadow-lg transition-all flex items-center justify-center" title="Simpan">
                                <i class="ph-bold ph-floppy-disk text-lg"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 animate-enter" style="animation-delay: 100ms">
            <!-- Total Pendaftar -->
            <div class="group bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.05)] hover:shadow-xl hover:shadow-blue-100/50 hover:border-blue-200 transition-all duration-300 relative overflow-hidden flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl flex items-center justify-center shadow-sm transition-all duration-300 bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white group-hover:scale-110">
                    <i class="ph-duotone ph-users-three text-3xl animate-wiggle"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 group-hover:text-blue-600 transition-colors">Total Pendaftar</p>
                    <h3 class="text-3xl font-extrabold text-slate-800 tracking-tight count-up" data-target="<?php echo e($stats['total']); ?>">0</h3>
                </div>
            </div>

            <!-- Diterima -->
            <div class="group bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.05)] hover:shadow-xl hover:shadow-emerald-100/50 hover:border-emerald-200 transition-all duration-300 relative overflow-hidden flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl flex items-center justify-center shadow-sm transition-all duration-300 bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white group-hover:scale-110">
                    <i class="ph-duotone ph-medal text-3xl animate-wiggle"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 group-hover:text-emerald-600 transition-colors">Lulus Seleksi</p>
                    <h3 class="text-3xl font-extrabold text-slate-800 tracking-tight count-up" data-target="<?php echo e($stats['accepted']); ?>">0</h3>
                </div>
            </div>

            <!-- Pending -->
            <div class="group bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.05)] hover:shadow-xl hover:shadow-amber-100/50 hover:border-amber-200 transition-all duration-300 relative overflow-hidden flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl flex items-center justify-center shadow-sm transition-all duration-300 bg-amber-50 text-amber-600 group-hover:bg-amber-600 group-hover:text-white group-hover:scale-110">
                    <i class="ph-duotone ph-clock-countdown text-3xl animate-wiggle"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 group-hover:text-amber-600 transition-colors">Perlu Cek</p>
                    <h3 class="text-3xl font-extrabold text-slate-800 tracking-tight count-up" data-target="<?php echo e($stats['pending']); ?>">0</h3>
                </div>
            </div>
        </div>

        
        <div class="animate-enter bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden flex flex-col min-h-[600px]" style="animation-delay: 200ms">
            
            
            <div class="p-6 md:p-8 border-b border-slate-50 bg-slate-50/30 flex flex-col xl:flex-row xl:items-center justify-between gap-6">
                
                
                <div class="bg-slate-100 p-1.5 rounded-xl flex flex-wrap gap-1 w-full md:w-auto overflow-x-auto no-scrollbar">
                    <?php
                        $tabClass = "px-4 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-2 whitespace-nowrap";
                        $activeClass = "bg-white text-slate-800 shadow-sm";
                        $inactiveClass = "text-slate-500 hover:text-slate-700 hover:bg-slate-200/50";
                    ?>

                    <a href="<?php echo e(route('admin.ppdb.index')); ?>" class="<?php echo e($tabClass); ?> <?php echo e(!request('status') ? $activeClass : $inactiveClass); ?>">
                       <i class="ph-bold ph-squares-four"></i> Semua
                    </a>
                    <a href="<?php echo e(route('admin.ppdb.index', ['status' => 'pending'])); ?>" class="<?php echo e($tabClass); ?> <?php echo e(request('status') == 'pending' ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/30' : $inactiveClass); ?>">
                       <i class="ph-bold ph-clock"></i> Pending
                    </a>
                    <a href="<?php echo e(route('admin.ppdb.index', ['status' => 'verified'])); ?>" class="<?php echo e($tabClass); ?> <?php echo e(request('status') == 'verified' ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30' : $inactiveClass); ?>">
                       <i class="ph-bold ph-check-circle"></i> Verified
                    </a>
                    <a href="<?php echo e(route('admin.ppdb.index', ['status' => 'accepted'])); ?>" class="<?php echo e($tabClass); ?> <?php echo e(request('status') == 'accepted' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/30' : $inactiveClass); ?>">
                       <i class="ph-bold ph-medal"></i> Diterima
                    </a>
                </div>
                
                
                <div class="flex flex-col md:flex-row gap-3 w-full xl:w-auto">
                    
                    <button type="button" onclick="submitBulk()" class="px-5 py-2.5 bg-slate-900 text-white rounded-xl font-bold text-xs hover:bg-slate-800 transition flex items-center justify-center gap-2 shadow-lg shadow-slate-900/20">
                        <i class="ph-bold ph-user-plus"></i> Promote Terpilih
                    </button>

                    
                    <form method="GET" class="relative group w-full md:w-64">
                        <input type="hidden" name="status" value="<?php echo e(request('status')); ?>">
                        <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-600 transition-colors"></i>
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari Siswa..." 
                               class="w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 bg-white focus:border-blue-500 focus:ring-blue-500 text-sm font-bold text-slate-700 transition-all shadow-sm placeholder:font-normal">
                    </form>
                </div>
            </div>

            
            <div class="overflow-x-auto flex-1">
                <form action="<?php echo e(route('admin.ppdb.bulk_promote')); ?>" method="POST" id="bulkForm">
                    <?php echo csrf_field(); ?>
                    <table class="w-full text-sm text-left text-slate-600">
                        <thead class="bg-slate-50/80 text-xs font-extrabold text-slate-400 uppercase tracking-wider sticky top-0 z-10 backdrop-blur-sm">
                            <tr>
                                <th class="px-6 py-4 w-10 text-center">
                                    <input type="checkbox" id="checkAll" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 w-4 h-4 cursor-pointer bg-slate-100">
                                </th>
                                <th class="px-6 py-4">Data Siswa</th>
                                <th class="px-6 py-4 text-center">Jalur & Nilai</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php $__empty_1 = true; $__currentLoopData = $registrants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-blue-50/30 transition-colors group">
                                
                                <td class="px-6 py-4 text-center">
                                    <?php if($item->status == 'accepted'): ?>
                                        <input type="checkbox" name="selected_ids[]" value="<?php echo e($item->id); ?>" class="check-item rounded border-slate-300 text-blue-600 focus:ring-blue-500 w-4 h-4 cursor-pointer">
                                    <?php else: ?>
                                        <i class="ph-bold ph-minus text-slate-200"></i>
                                    <?php endif; ?>
                                </td>
                                
                                
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-slate-100 to-slate-200 border border-slate-300 flex items-center justify-center text-slate-500 font-black text-sm shadow-sm group-hover:scale-110 transition-transform">
                                            <?php echo e(substr($item->full_name, 0, 1)); ?>

                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-800 text-base"><?php echo e($item->full_name); ?></div>
                                            <div class="text-xs text-slate-400 font-mono mt-0.5 flex items-center gap-1">
                                                <i class="ph-bold ph-barcode"></i> <?php echo e($item->registration_number); ?>

                                            </div>
                                        </div>
                                    </div>
                                </td>

                                
                                <td class="px-6 py-4 text-center">
                                    <div class="flex flex-col items-center gap-1">
                                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wide border 
                                            <?php echo e($item->track == 'prestasi' ? 'bg-purple-50 text-purple-600 border-purple-100' : 'bg-blue-50 text-blue-600 border-blue-100'); ?>">
                                            <?php echo e($item->track); ?>

                                        </span>
                                        <span class="text-xs font-bold text-slate-500"><?php echo e($item->average_grade); ?></span>
                                    </div>
                                </td>

                                
                                <td class="px-6 py-4 text-center">
                                    <?php if($item->status == 'pending'): ?>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-600 border border-amber-200 shadow-sm">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Pending
                                        </span>
                                    <?php elseif($item->status == 'accepted'): ?>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-200 shadow-sm">
                                            <i class="ph-fill ph-check-circle"></i> Diterima
                                        </span>
                                    <?php elseif($item->status == 'verified'): ?>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-600 border border-blue-200 shadow-sm">
                                            <i class="ph-fill ph-check"></i> Terverifikasi
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-600 border border-rose-200 shadow-sm">
                                            <i class="ph-fill ph-x-circle"></i> Ditolak
                                        </span>
                                    <?php endif; ?>
                                </td>

                                
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2 opacity-60 group-hover:opacity-100 transition-opacity translate-x-2 group-hover:translate-x-0 duration-200">
                                        <a href="<?php echo e(route('admin.ppdb.show', $item->id)); ?>" class="p-2 rounded-xl text-slate-400 hover:text-blue-600 hover:bg-blue-50 border border-transparent hover:border-blue-100 transition-all" title="Detail">
                                            <i class="ph-bold ph-eye text-lg"></i>
                                        </a>
                                        <button type="button" onclick="confirmDelete('<?php echo e($item->id); ?>', '<?php echo e($item->full_name); ?>')" class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 border border-transparent hover:border-rose-100 transition-all" title="Hapus">
                                            <i class="ph-bold ph-trash text-lg"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="py-20 text-center">
                                    <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                                        <i class="ph-duotone ph-folder-notch-open text-5xl"></i>
                                    </div>
                                    <p class="text-sm font-bold text-slate-600">Belum ada data pendaftar</p>
                                    <p class="text-xs text-slate-400 mt-1">Silakan cek filter atau data masuk.</p>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </form>

                
                <?php $__currentLoopData = $registrants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <form action="<?php echo e(route('admin.ppdb.destroy', $item->id)); ?>" method="POST" id="delete-form-<?php echo e($item->id); ?>" class="hidden">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
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

    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animasi Angka (Count Up)
            const counters = document.querySelectorAll('.count-up');
            counters.forEach(counter => {
                const target = +counter.getAttribute('data-target');
                let count = 0; const inc = Math.max(1, target / 30);
                const updateCount = () => {
                    count += inc;
                    if (count < target) { counter.innerText = Math.ceil(count); requestAnimationFrame(updateCount); } 
                    else { counter.innerText = target; }
                };
                updateCount();
            });

            // SweetAlert Session
            <?php if(session('success')): ?>
                Swal.fire({
                    icon: 'success', title: 'Berhasil!', text: '<?php echo e(session('success')); ?>',
                    confirmButtonColor: '#1e3a8a',
                    customClass: { popup: 'rounded-[2rem] font-sans', confirmButton: 'px-6 py-3 rounded-xl' }
                });
            <?php endif; ?>
            <?php if(session('error')): ?>
                Swal.fire({ icon: 'error', title: 'Error', text: '<?php echo e(session('error')); ?>' });
            <?php endif; ?>

            // Checkbox All Logic
            const checkAll = document.getElementById('checkAll');
            const checkItems = document.querySelectorAll('.check-item');
            if(checkAll) {
                checkAll.addEventListener('change', function() {
                    checkItems.forEach(item => item.checked = this.checked);
                });
            }
        });

        function submitBulk() {
            const selected = document.querySelectorAll('.check-item:checked').length;
            if(selected === 0) {
                Swal.fire({ icon: 'warning', title: 'Pilih Data', text: 'Centang minimal satu siswa (status Diterima).', customClass: { popup: 'rounded-[2rem]' } });
                return;
            }
            Swal.fire({
                title: 'Promote Siswa?',
                text: `Pindahkan ${selected} siswa ke Data Induk Siswa Aktif?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Proses',
                confirmButtonColor: '#059669',
                cancelButtonColor: '#64748b',
                customClass: { popup: 'rounded-[2rem] font-sans' }
            }).then((res) => {
                if(res.isConfirmed) document.getElementById('bulkForm').submit();
            });
        }

        function confirmDelete(id, name) {
            Swal.fire({
                title: 'Hapus Data?',
                html: `Data <b>${name}</b> akan dihapus permanen beserta berkasnya.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Hapus',
                confirmButtonColor: '#e11d48',
                customClass: { popup: 'rounded-[2rem] font-sans' }
            }).then((res) => {
                if(res.isConfirmed) document.getElementById('delete-form-'+id).submit();
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