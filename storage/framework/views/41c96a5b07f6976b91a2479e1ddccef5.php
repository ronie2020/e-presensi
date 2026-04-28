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
        .animate-enter { opacity: 0; animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>

    <div class="font-sans p-4 md:p-8 space-y-8 min-h-screen bg-slate-50 text-elevate-text">
        
        
        <div class="animate-enter relative rounded-[3rem] bg-elevate-gradient-main p-8 md:p-12 text-elevate-dark shadow-xl shadow-elevate-accent/10 overflow-hidden group border border-white/60">
            <div class="absolute -top-10 -left-10 w-48 h-48 bg-elevate-primary/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-3xl"></div>
            <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-elevate-peach/20 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-2xl"></div>
            <div class="absolute top-10 right-32 w-24 h-24 bg-white/40 rounded-2xl rotate-45 pointer-events-none shadow-sm"></div>
            
            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-10">
                <div class="flex-1">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/60 border border-white text-elevate-dark text-[10px] font-black uppercase tracking-[0.2em] mb-6 backdrop-blur-md shadow-sm">
                        <i class="ph-fill ph-shield-check text-elevate-primary text-sm"></i> Panel Manajemen Keamanan
                    </div>
                    <h1 class="text-4xl md:text-6xl font-black text-elevate-dark tracking-tighter mb-4 leading-none">
                        Tindak Lanjut <span class="text-transparent bg-clip-text bg-gradient-to-r from-elevate-primary to-elevate-accent">Laporan</span>
                    </h1>
                    <p class="text-elevate-dark/80 text-sm md:text-lg max-w-xl leading-relaxed font-medium">
                        Dengarkan suara siswa dan berikan solusi terbaik untuk lingkungan sekolah yang aman.
                    </p>
                </div>

                <div class="flex gap-4 shrink-0">
                    <div class="bg-white/60 backdrop-blur-md p-6 rounded-[2.5rem] border border-white text-center min-w-[140px] shadow-sm">
                        <p class="text-[10px] font-black text-amber-500 uppercase tracking-widest mb-1">Menunggu</p>
                        <p class="text-4xl font-black text-elevate-dark tracking-tight"><?php echo e($complaints->where('status', 'pending')->count()); ?></p>
                    </div>
                    <div class="bg-white/60 backdrop-blur-md p-6 rounded-[2.5rem] border border-white text-center min-w-[140px] shadow-sm">
                        <p class="text-[10px] font-black text-emerald-500 uppercase tracking-widest mb-1">Selesai</p>
                        <p class="text-4xl font-black text-elevate-dark tracking-tight"><?php echo e($complaints->where('status', 'resolved')->count()); ?></p>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="animate-enter" style="animation-delay: 100ms">
            <form action="<?php echo e(route('complaints.index')); ?>" method="GET" class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2 block">Cari Kata Kunci</label>
                    <div class="relative">
                        <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari nama siswa atau isi laporan..." class="w-full pl-11 pr-4 py-3 bg-slate-50 border-transparent rounded-2xl text-sm font-bold focus:ring-elevate-primary focus:bg-white transition-all text-elevate-dark">
                    </div>
                </div>
                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2 block">Tanggal Kejadian</label>
                    <div class="relative">
                        <i class="ph-bold ph-calendar absolute left-4 top-1/2 -translate-y-1/2 text-elevate-primary"></i>
                        <input type="date" name="date" value="<?php echo e($date ?? request('date')); ?>" class="w-full pl-11 pr-4 py-3 bg-slate-50 border-transparent rounded-2xl text-sm font-bold focus:ring-elevate-primary focus:bg-white transition-all text-elevate-dark" onchange="this.form.submit()">
                    </div>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 bg-elevate-dark text-white py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-elevate-primary shadow-lg shadow-elevate-dark/20 transition-all active:scale-95">Filter</button>
                    <a href="<?php echo e(route('complaints.index')); ?>" class="px-4 py-3 bg-slate-100 text-slate-400 rounded-2xl hover:bg-rose-50 hover:text-rose-500 transition-all"><i class="ph-bold ph-arrow-counter-clockwise"></i></a>
                </div>
            </form>
        </div>

        
        <div class="animate-enter bg-white rounded-[3rem] shadow-sm border border-slate-100 overflow-hidden mb-12" style="animation-delay: 200ms">
            <div class="divide-y divide-slate-50">
                <?php $__empty_1 = true; $__currentLoopData = $complaints; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $complaint): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="p-8 md:p-10 hover:bg-slate-50/50 transition-all group relative overflow-hidden">
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 <?php echo e($complaint->status == 'resolved' ? 'bg-emerald-500' : 'bg-amber-500'); ?>"></div>

                    <div class="flex flex-col lg:flex-row gap-10">
                        <div class="flex-1 space-y-6">
                            <div class="flex flex-wrap items-center gap-3">
                                <span class="px-4 py-1 rounded-xl text-[10px] font-black border uppercase bg-elevate-accent/10 text-elevate-primary border-elevate-accent/20">
                                    <?php echo e($complaint->category); ?>

                                </span>
                                <span class="text-[10px] text-slate-400 font-black uppercase tracking-widest">
                                    <?php echo e($complaint->created_at->translatedFormat('d M Y, H:i')); ?>

                                </span>
                            </div>

                            <div class="flex items-start gap-5">
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center shrink-0 border border-slate-200 shadow-sm bg-white
                                    <?php echo e($complaint->is_anonymous ? 'text-slate-400' : 'text-elevate-primary'); ?>">
                                    <i class="ph-fill <?php echo e($complaint->is_anonymous ? 'ph-mask-spy' : 'ph-user-focus'); ?> text-3xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-xl font-black text-elevate-dark tracking-tight leading-tight">
                                        <?php echo e($complaint->is_anonymous ? 'Siswa Anonim' : ($complaint->student->name ?? 'Siswa Tidak Ditemukan')); ?>

                                    </h4>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">
                                        NISN: <?php echo e($complaint->student->student_id ?? '-'); ?> &bull; Kelas: <?php echo e($complaint->student->schoolClass->name ?? '-'); ?>

                                    </p>
                                </div>
                            </div>
                            
                            <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm relative">
                                <div class="flex items-center gap-2 mb-3 text-rose-500">
                                    <i class="ph-fill ph-map-pin text-lg"></i>
                                    <span class="text-xs font-black uppercase tracking-widest"><?php echo e($complaint->location); ?></span>
                                </div>
                                <p class="text-slate-600 text-sm leading-relaxed font-medium italic">"<?php echo e($complaint->description); ?>"</p>
                                
                                <?php if($complaint->evidence_path): ?>
                                    <div class="mt-4 pt-4 border-t border-slate-50">
                                        <a href="<?php echo e(asset('storage/' . $complaint->evidence_path)); ?>" target="_blank" class="inline-flex items-center gap-2 text-xs font-bold text-elevate-primary hover:text-elevate-dark transition-colors">
                                            <i class="ph-bold ph-image text-lg"></i> Lihat Bukti Lampiran
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="w-full lg:w-72 shrink-0 flex flex-col justify-center gap-4 bg-slate-50/50 p-6 rounded-[2.5rem] border border-slate-100">
                            <?php if($complaint->status == 'resolved'): ?>
                                <div class="text-center space-y-3">
                                    <div class="w-16 h-16 bg-emerald-50 border border-emerald-100 text-emerald-500 rounded-[1.5rem] flex items-center justify-center mx-auto shadow-sm">
                                        <i class="ph-fill ph-check-circle text-3xl"></i>
                                    </div>
                                    <h5 class="text-sm font-black text-emerald-700 uppercase tracking-tight">Selesai</h5>
                                </div>
                            <?php else: ?>
                                <button onclick="confirmResolve('<?php echo e($complaint->id); ?>')" 
                                        class="w-full py-4 bg-elevate-dark hover:bg-emerald-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.15em] shadow-lg shadow-elevate-dark/20 transition-all transform active:scale-95 flex items-center justify-center gap-3 group/btn">
                                    Tandai Selesai
                                    <i class="ph-bold ph-check-square-offset text-xl group-hover/btn:scale-110 transition-transform"></i>
                                </button>

                                <form id="resolve-form-<?php echo e($complaint->id); ?>" action="<?php echo e(route('complaints.resolve', $complaint->id)); ?>" method="POST" class="hidden">
                                    <?php echo csrf_field(); ?>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="py-32 text-center flex flex-col items-center justify-center">
                    <div class="w-24 h-24 bg-slate-50 rounded-[2rem] flex items-center justify-center mb-8 border border-slate-100 shadow-sm group transition-all">
                        <i class="ph-duotone ph-tray text-6xl text-slate-300"></i>
                    </div>
                    <h3 class="text-2xl font-black text-elevate-dark tracking-tight">Kotak Laporan Kosong</h3>
                    <p class="text-slate-500 font-medium mt-2">Tidak ada laporan masuk yang perlu ditindaklanjuti.</p>
                </div>
                <?php endif; ?>
            </div>
            
            <?php if($complaints->hasPages()): ?>
            <div class="p-8 border-t border-slate-50">
                <?php echo e($complaints->links()); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmResolve(id) {
            Swal.fire({
                title: 'Tandai Selesai?',
                text: "Masalah ini telah terselesaikan dan laporan akan ditutup statusnya.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981', // emerald-500
                cancelButtonColor: '#94a3b8', // slate-400
                confirmButtonText: 'Ya, Selesaikan!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-[2.5rem] font-sans border-0 shadow-2xl',
                    confirmButton: 'bg-emerald-500 text-white px-6 py-3 rounded-xl font-bold hover:bg-emerald-600 transition-colors mx-2 shadow-lg shadow-emerald-500/20',
                    cancelButton: 'bg-slate-100 text-slate-600 px-6 py-3 rounded-xl font-bold hover:bg-slate-200 transition-colors mx-2'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('resolve-form-' + id).submit();
                }
            })
        }

        // Cek Session Success
        document.addEventListener('DOMContentLoaded', function() {
            <?php if(session('success')): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "<?php echo e(session('success')); ?>",
                    confirmButtonColor: '#3b5889', // elevate-primary
                    customClass: { popup: 'rounded-[2rem] shadow-xl border border-slate-100' }
                });
            <?php endif; ?>
        });
    </script>
    <?php $__env->stopPush(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/admin/complaints/index.blade.php ENDPATH**/ ?>