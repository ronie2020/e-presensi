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
        
        /* Microsoft Fluent Elevation Shadows */
        .fluent-card {
            box-shadow: 0 1.6px 3.6px 0 rgba(0, 0, 0, 0.132), 0 0.3px 0.9px 0 rgba(0, 0, 0, 0.108);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        .fluent-card:hover {
            box-shadow: 0 6.4px 14.4px 0 rgba(0, 0, 0, 0.132), 0 1.2px 3.6px 0 rgba(0, 0, 0, 0.108);
            transform: translateY(-2px);
        }
        .fluent-modal {
            box-shadow: 0 25.6px 57.6px 0 rgba(0, 0, 0, 0.22), 0 4.8px 14.4px 0 rgba(0, 0, 0, 0.18);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
    </style>

    
    <?php
        $announcementTime = isset($scheduleData['announcement_date']) ? \Carbon\Carbon::parse($scheduleData['announcement_date']) : null;
        $isSet = $announcementTime != null;
        $isPast = $isSet && \Carbon\Carbon::now()->greaterThanOrEqualTo($announcementTime);
    ?>

    <div class="relative space-y-6 md:space-y-8 min-h-screen pb-10 font-sans text-elevate-dark bg-elevate-surface">
        
        
        <div class="animate-enter relative rounded-[2rem] bg-elevate-gradient-main p-6 md:p-10 text-elevate-dark shadow-xl shadow-elevate-accent/20 overflow-hidden group border border-white/60">
            
            
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-[0.03] pointer-events-none mix-blend-overlay"></div>
            <div class="absolute top-0 left-0 w-[400px] h-[400px] bg-white/30 rounded-full blur-[100px] group-hover:opacity-70 transition-opacity duration-1000 pointer-events-none -ml-20 -mt-20"></div>
            <div class="absolute bottom-0 right-0 w-[300px] h-[300px] bg-white/20 rounded-full blur-[120px] pointer-events-none"></div>

            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
                
                
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/40 border border-white/50 text-elevate-dark text-xs font-bold uppercase tracking-wider mb-4 backdrop-blur-sm shadow-sm">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-elevate-peach-dark opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-elevate-peach-dark"></span>
                        </span>
                        Portal Penerimaan Siswa Baru
                    </div>
                    
                    <h1 class="text-3xl md:text-5xl font-extrabold text-elevate-dark tracking-tight mb-4 leading-tight">
                        Manajemen <br>
                        <span class="text-elevate-dark">PPDB Online</span> 
                    </h1>
                    <p class="text-elevate-dark/80 text-sm md:text-base max-w-xl leading-relaxed mb-8 font-medium">
                        Kelola data calon siswa, verifikasi berkas, dan atur jadwal pengumuman hasil seleksi secara terpusat.
                    </p>

                    <div class="flex flex-wrap gap-3">
                        <a href="<?php echo e(route('dashboard')); ?>" class="px-6 py-3 bg-elevate-dark hover:bg-elevate-primary text-white text-sm font-bold rounded-xl transition-all shadow-md shadow-elevate-dark/20 flex items-center gap-2">
                            <i class="ph-bold ph-squares-four"></i> Dashboard Utama
                        </a>
                    </div>
                </div>

                
                <div class="bg-white/40 backdrop-blur-md p-6 sm:p-8 rounded-[1.5rem] border border-white/50 shadow-sm relative overflow-hidden">
                    <div class="absolute -top-10 -right-10 p-4 opacity-10 text-elevate-primary pointer-events-none transform rotate-12">
                        <i class="ph-fill ph-calendar-check text-[10rem]"></i>
                    </div>

                    <h3 class="text-xl font-black text-elevate-dark mb-2 flex items-center gap-2 relative z-10">
                        <i class="ph-duotone ph-clock text-elevate-primary"></i> Jadwal Pengumuman
                    </h3>
                    <p class="text-elevate-dark/80 text-sm font-medium mb-6 relative z-10">Atur kapan hasil seleksi dapat dilihat publik.</p>
                    
                    
                    <div class="mb-6 p-4 rounded-xl bg-white/60 border border-white/60 relative z-10 shadow-sm">
                        <?php if($isSet): ?>
                            <div class="flex items-center gap-3 mb-2">
                                <span class="text-[10px] font-black uppercase tracking-wider px-2.5 py-1 rounded-md border <?php echo e($isPast ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 'bg-elevate-soft text-elevate-primary border-elevate-accent/30'); ?>">
                                    <?php echo e($isPast ? '● Sudah Dibuka' : '● Terjadwal'); ?>

                                </span>
                            </div>
                            <p class="text-elevate-dark text-lg font-bold tracking-wide font-mono">
                                <?php echo e($announcementTime->translatedFormat('d M Y, H:i')); ?> WIB
                            </p>
                        <?php else: ?>
                            <div class="flex items-center gap-3 mb-2">
                                <span class="text-[10px] font-black uppercase tracking-wider px-2.5 py-1 rounded-md border bg-amber-100 text-amber-700 border-amber-200">● Belum Diatur</span>
                            </div>
                            <p class="text-elevate-dark/70 text-sm">Siswa belum dapat melihat hasil.</p>
                        <?php endif; ?>
                    </div>

                    
                    <form action="<?php echo e(route('admin.ppdb.set_schedule')); ?>" method="POST" class="space-y-2 relative z-10">
                        <?php echo csrf_field(); ?>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <input type="datetime-local" name="announcement_date" required 
                                   value="<?php echo e($isSet ? $announcementTime->format('Y-m-d\TH:i') : ''); ?>"
                                   class="block w-full px-4 py-3 rounded-xl border-white/60 bg-white/70 focus:bg-white text-elevate-dark text-sm font-bold shadow-sm focus:ring-elevate-accent/30 focus:border-elevate-accent transition-all cursor-pointer">
                            <button type="submit" class="px-6 py-3 bg-elevate-primary hover:bg-elevate-dark text-white font-bold rounded-xl shadow-md transition-all flex items-center justify-center gap-2 whitespace-nowrap" title="Simpan">
                                <i class="ph-bold ph-floppy-disk text-lg"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 animate-enter" style="animation-delay: 100ms">
            <!-- Total Pendaftar -->
            <div class="group bg-white rounded-2xl p-5 fluent-card relative overflow-hidden flex items-center gap-5 hover:border-elevate-primary">
                <div class="w-14 h-14 rounded-xl flex items-center justify-center shadow-sm border transition-all duration-300 bg-elevate-soft text-elevate-primary border-elevate-accent/20 group-hover:bg-elevate-primary group-hover:text-white group-hover:scale-110">
                    <i class="ph-duotone ph-users-three text-3xl animate-wiggle"></i>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1 group-hover:text-elevate-primary transition-colors">Total Pendaftar</p>
                    <h3 class="text-3xl font-black text-elevate-dark tracking-tight count-up" data-target="<?php echo e($stats['total']); ?>">0</h3>
                </div>
            </div>

            <!-- Diterima -->
            <div class="group bg-white rounded-2xl p-5 fluent-card relative overflow-hidden flex items-center gap-5 hover:border-emerald-600">
                <div class="w-14 h-14 rounded-xl flex items-center justify-center shadow-sm border transition-all duration-300 bg-emerald-50 text-emerald-600 border-emerald-200 group-hover:bg-emerald-600 group-hover:text-white group-hover:scale-110">
                    <i class="ph-duotone ph-medal text-3xl animate-wiggle"></i>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1 group-hover:text-emerald-600 transition-colors">Lulus Seleksi</p>
                    <h3 class="text-3xl font-black text-elevate-dark tracking-tight count-up" data-target="<?php echo e($stats['accepted']); ?>">0</h3>
                </div>
            </div>

            <!-- Pending -->
            <div class="group bg-white rounded-2xl p-5 fluent-card relative overflow-hidden flex items-center gap-5 hover:border-amber-500">
                <div class="w-14 h-14 rounded-xl flex items-center justify-center shadow-sm border transition-all duration-300 bg-amber-50 text-amber-600 border-amber-200 group-hover:bg-amber-500 group-hover:text-white group-hover:scale-110">
                    <i class="ph-duotone ph-clock-countdown text-3xl animate-wiggle"></i>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1 group-hover:text-amber-600 transition-colors">Perlu Verifikasi</p>
                    <h3 class="text-3xl font-black text-elevate-dark tracking-tight count-up" data-target="<?php echo e($stats['pending']); ?>">0</h3>
                </div>
            </div>
        </div>

        
        <div class="animate-enter bg-white rounded-2xl fluent-card overflow-hidden flex flex-col min-h-[600px]" style="animation-delay: 200ms">
            
            
            <div class="p-5 md:p-6 border-b border-slate-100 flex flex-col xl:flex-row xl:items-center justify-between gap-5">
                
                
                <div class="bg-slate-50 p-1.5 rounded-xl flex flex-wrap gap-1 w-full md:w-auto overflow-x-auto border border-slate-200 no-scrollbar">
                    <?php
                        $tabClass = "px-4 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-2 whitespace-nowrap";
                        $activeClass = "bg-white text-elevate-primary shadow-sm border border-slate-200";
                        $inactiveClass = "text-slate-500 hover:text-elevate-dark hover:bg-slate-100";
                    ?>

                    <a href="<?php echo e(route('admin.ppdb.index')); ?>" class="<?php echo e($tabClass); ?> <?php echo e(!request('status') ? $activeClass : $inactiveClass); ?>">
                       <i class="ph-bold ph-squares-four"></i> Semua
                    </a>
                    <a href="<?php echo e(route('admin.ppdb.index', ['status' => 'pending'])); ?>" class="<?php echo e($tabClass); ?> <?php echo e(request('status') == 'pending' ? 'bg-amber-500 text-white shadow-sm' : $inactiveClass); ?>">
                       <i class="ph-bold ph-clock"></i> Pending
                    </a>
                    <a href="<?php echo e(route('admin.ppdb.index', ['status' => 'verified'])); ?>" class="<?php echo e($tabClass); ?> <?php echo e(request('status') == 'verified' ? 'bg-elevate-primary text-white shadow-sm' : $inactiveClass); ?>">
                       <i class="ph-bold ph-check-circle"></i> Verified
                    </a>
                    <a href="<?php echo e(route('admin.ppdb.index', ['status' => 'accepted'])); ?>" class="<?php echo e($tabClass); ?> <?php echo e(request('status') == 'accepted' ? 'bg-emerald-600 text-white shadow-sm' : $inactiveClass); ?>">
                       <i class="ph-bold ph-medal"></i> Diterima
                    </a>
                </div>
                
                
                <div class="flex flex-col md:flex-row gap-3 w-full xl:w-auto">
                    <button type="button" onclick="submitBulk()" class="px-5 py-2.5 bg-elevate-dark text-white rounded-xl font-bold text-xs hover:bg-elevate-primary transition flex items-center justify-center gap-2 shadow-sm">
                        <i class="ph-bold ph-user-plus"></i> Promote Terpilih
                    </button>

                    <form method="GET" class="relative group w-full md:w-64">
                        <input type="hidden" name="status" value="<?php echo e(request('status')); ?>">
                        <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-elevate-primary transition-colors"></i>
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari Siswa..." 
                               class="w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 text-sm font-bold text-elevate-dark transition-all shadow-sm placeholder:font-medium">
                    </form>
                </div>
            </div>

            
            <div class="overflow-x-auto flex-1">
                <form action="<?php echo e(route('admin.ppdb.bulk_promote')); ?>" method="POST" id="bulkForm">
                    <?php echo csrf_field(); ?>
                    <table class="w-full text-sm text-left text-elevate-dark">
                        <thead class="bg-slate-50 text-xs font-bold text-slate-500 uppercase tracking-wider sticky top-0 z-10 border-b border-slate-100">
                            <tr>
                                <th class="px-5 py-4 w-10 text-center">
                                    <input type="checkbox" id="checkAll" class="rounded border-slate-300 text-elevate-primary focus:ring-elevate-primary w-4 h-4 cursor-pointer bg-white">
                                </th>
                                <th class="px-5 py-4">Data Siswa</th>
                                <th class="px-5 py-4 text-center">Jalur & Nilai</th>
                                <th class="px-5 py-4 text-center">Status</th>
                                <th class="px-5 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php $__empty_1 = true; $__currentLoopData = $registrants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-5 py-4 text-center">
                                    <?php if($item->status == 'accepted'): ?>
                                        <input type="checkbox" name="selected_ids[]" value="<?php echo e($item->id); ?>" class="check-item rounded border-slate-300 text-elevate-primary focus:ring-elevate-primary w-4 h-4 cursor-pointer">
                                    <?php else: ?>
                                        <i class="ph-bold ph-minus text-slate-300"></i>
                                    <?php endif; ?>
                                </td>
                                
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-500 font-black text-sm group-hover:scale-110 group-hover:border-elevate-primary group-hover:text-elevate-primary transition-all shadow-sm">
                                            <?php echo e(substr($item->full_name, 0, 1)); ?>

                                        </div>
                                        <div>
                                            <div class="font-bold text-elevate-dark mb-0.5 group-hover:text-elevate-primary transition-colors"><?php echo e($item->full_name); ?></div>
                                            <div class="text-[11px] text-slate-400 font-mono flex items-center gap-1.5">
                                                <i class="ph-bold ph-identification-card"></i> <?php echo e($item->registration_number); ?>

                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4 text-center">
                                    <div class="flex flex-col items-center gap-1.5">
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider border 
                                            <?php echo e($item->track == 'prestasi' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-elevate-soft text-elevate-primary border-elevate-accent/30'); ?>">
                                            <?php echo e($item->track); ?>

                                        </span>
                                        <span class="text-xs font-bold text-slate-500 bg-white border border-slate-200 px-2 py-0.5 rounded shadow-sm"><?php echo e($item->average_grade); ?></span>
                                    </div>
                                </td>

                                <td class="px-5 py-4 text-center">
                                    <?php if($item->status == 'pending'): ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold bg-amber-50 text-amber-600 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Pending
                                        </span>
                                    <?php elseif($item->status == 'accepted'): ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">
                                            <i class="ph-fill ph-check-circle"></i> Diterima
                                        </span>
                                    <?php elseif($item->status == 'verified'): ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold bg-elevate-soft text-elevate-primary border border-elevate-accent/30">
                                            <i class="ph-fill ph-shield-check"></i> Terverifikasi
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold bg-rose-50 text-rose-600 border border-rose-200">
                                            <i class="ph-fill ph-x-circle"></i> Ditolak
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                        <a href="<?php echo e(route('admin.ppdb.show', $item->id)); ?>" class="p-2 rounded-lg text-slate-400 hover:text-elevate-primary hover:bg-elevate-soft hover:border-elevate-accent/30 border border-transparent transition-all" title="Detail">
                                            <i class="ph-bold ph-eye text-lg"></i>
                                        </a>
                                        <button type="button" onclick="confirmDelete('<?php echo e($item->id); ?>', '<?php echo e($item->full_name); ?>')" class="p-2 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 hover:border-rose-200 border border-transparent transition-all" title="Hapus">
                                            <i class="ph-bold ph-trash text-lg"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="py-16 text-center">
                                    <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-slate-100 text-slate-300">
                                        <i class="ph-duotone ph-folder-notch-open text-3xl"></i>
                                    </div>
                                    <p class="text-sm font-bold text-elevate-dark">Belum ada data pendaftar</p>
                                    <p class="text-xs text-slate-400 mt-1">Silakan sesuaikan filter pencarian.</p>
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
            <div class="p-5 border-t border-slate-100 bg-white">
                <?php echo e($registrants->withQueryString()->links()); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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

            <?php if(session('success')): ?>
                Swal.fire({
                    icon: 'success', title: 'Berhasil!', text: '<?php echo e(session('success')); ?>',
                    confirmButtonColor: '#10b981', // Tailwind Emerald 500
                    customClass: { popup: 'rounded-[2rem]', confirmButton: 'px-6 py-2 rounded-xl font-bold' }
                });
            <?php endif; ?>
            <?php if(session('error')): ?>
                Swal.fire({ icon: 'error', title: 'Error', text: '<?php echo e(session('error')); ?>', customClass: { popup: 'rounded-[2rem]'} });
            <?php endif; ?>

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
                Swal.fire({ icon: 'warning', title: 'Pilih Data', text: 'Centang minimal satu siswa.', customClass: { popup: 'rounded-[2rem]' }, confirmButtonColor: '#2c3f61' });
                return;
            }
            Swal.fire({
                title: 'Promote Siswa?',
                text: `Pindahkan ${selected} siswa ke Data Induk Siswa Aktif?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Proses',
                confirmButtonColor: '#0d52a1', // Elevate Primary
                cancelButtonColor: '#64748b',
                customClass: { popup: 'rounded-[2rem]', confirmButton: 'rounded-xl font-bold', cancelButton: 'rounded-xl font-bold' }
            }).then((res) => {
                if(res.isConfirmed) document.getElementById('bulkForm').submit();
            });
        }

        function confirmDelete(id, name) {
            Swal.fire({
                title: 'Hapus Data?',
                html: `Data <b>${name}</b> akan dihapus permanen.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Hapus',
                confirmButtonColor: '#e11d48', // Tailwind Rose 600
                customClass: { popup: 'rounded-[2rem]', confirmButton: 'rounded-xl font-bold', cancelButton: 'rounded-xl font-bold' }
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