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
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        @keyframes wiggle { 0%, 100% { transform: rotate(0deg); } 25% { transform: rotate(-10deg); } 75% { transform: rotate(10deg); } }
        .group:hover .animate-wiggle { animation: wiggle 0.5s ease-in-out; }
        
        .fluent-card {
            box-shadow: 0 1.6px 3.6px 0 rgba(0, 0, 0, 0.132), 0 0.3px 0.9px 0 rgba(0, 0, 0, 0.108);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        .fluent-card:hover {
            box-shadow: 0 6.4px 14.4px 0 rgba(0, 0, 0, 0.132), 0 1.2px 3.6px 0 rgba(0, 0, 0, 0.108);
            transform: translateY(-2px);
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="relative space-y-6 md:space-y-8 min-h-screen pb-10 font-sans text-elevate-dark bg-elevate-surface">
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 py-6 md:py-8">

            
            <div class="animate-enter relative rounded-[2rem] bg-elevate-gradient-main overflow-hidden p-6 md:p-10 text-elevate-dark shadow-xl shadow-elevate-accent/20 group border border-white/60">
                
                
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-[0.03] pointer-events-none mix-blend-overlay"></div>
                <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-white/30 rounded-full blur-[100px] pointer-events-none group-hover:opacity-70 transition-opacity"></div>
                
                <div class="relative z-10">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/40 border border-white/50 text-elevate-dark text-xs font-bold uppercase tracking-wider mb-4 backdrop-blur-sm shadow-sm">
                        <i class="ph-fill ph-chart-bar text-elevate-primary"></i> Pusat Data & Laporan
                    </div>
                    <h1 class="text-3xl md:text-5xl font-extrabold text-elevate-dark tracking-tight mb-3">
                        Laporan & Unduhan
                    </h1>
                    <p class="text-elevate-dark/80 text-sm md:text-base font-medium leading-relaxed max-w-xl">
                        Pantau statistik pendaftar secara real-time dan unduh rekapitulasi data untuk keperluan arsip sekolah.
                    </p>
                </div>
            </div>

            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                
                <div class="animate-enter delay-100 bg-white rounded-2xl p-6 md:p-8 fluent-card relative overflow-hidden group">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 rounded-xl bg-elevate-soft text-elevate-primary border border-elevate-accent/30 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform duration-300 shadow-sm">
                            <i class="ph-duotone ph-chart-pie-slice animate-wiggle"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-elevate-dark text-lg">Sebaran Pendaftar</h3>
                            <p class="text-xs text-slate-400 font-bold uppercase tracking-wide">Berdasarkan jalur masuk</p>
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row items-center gap-8">
                        <div class="relative w-40 h-40 md:w-48 md:h-48 flex-shrink-0">
                            <canvas id="trackChart"></canvas>
                            <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                                <span class="text-3xl font-black text-elevate-dark"><?php echo e($totalRegistrants); ?></span>
                                <span class="text-[10px] uppercase font-bold text-slate-400">Siswa</span>
                            </div>
                        </div>
                        
                        <div class="flex-1 w-full space-y-3">
                            <?php $__currentLoopData = [
                                ['label' => 'Zonasi', 'val' => $trackStats['zonasi'], 'color' => 'bg-elevate-primary', 'bg' => 'bg-elevate-soft', 'text' => 'text-elevate-primary', 'border' => 'border-elevate-accent/30'],
                                ['label' => 'Prestasi', 'val' => $trackStats['prestasi'], 'color' => 'bg-emerald-600', 'bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200'],
                                ['label' => 'Afirmasi', 'val' => $trackStats['afirmasi'], 'color' => 'bg-amber-500', 'bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' => 'border-amber-200'],
                                ['label' => 'Pindah', 'val' => $trackStats['pindah_tugas'], 'color' => 'bg-elevate-dark', 'bg' => 'bg-slate-50', 'text' => 'text-elevate-dark', 'border' => 'border-slate-200']
                            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex justify-between items-center p-3 rounded-xl border <?php echo e($stat['bg']); ?> <?php echo e($stat['border']); ?> hover:shadow-sm transition-shadow">
                                <div class="flex items-center gap-3">
                                    <span class="w-3 h-3 rounded-full <?php echo e($stat['color']); ?> shadow-sm border border-white"></span>
                                    <span class="text-xs font-bold <?php echo e($stat['text']); ?>"><?php echo e($stat['label']); ?></span>
                                </div>
                                <span class="font-black text-elevate-dark text-sm"><?php echo e($stat['val']); ?></span>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>

                
                <div class="animate-enter delay-100 bg-white rounded-2xl p-6 md:p-8 fluent-card relative overflow-hidden flex flex-col group">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 rounded-xl bg-elevate-peach-light/50 text-elevate-peach-dark border border-elevate-peach flex items-center justify-center text-2xl group-hover:scale-110 transition-transform duration-300 shadow-sm">
                            <i class="ph-duotone ph-users-three animate-wiggle"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-elevate-dark text-lg">Ringkasan Data</h3>
                            <p class="text-xs text-slate-400 font-bold uppercase tracking-wide">Gender & Kelulusan</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 flex-1">
                        
                        <div class="bg-elevate-soft border border-elevate-accent/30 rounded-xl p-5 flex flex-col justify-center hover:bg-elevate-accent/10 transition-colors shadow-sm">
                            <div class="flex items-center justify-between mb-2">
                                <div class="w-10 h-10 rounded-lg bg-white text-elevate-primary border border-elevate-accent/30 flex items-center justify-center shadow-sm">
                                    <i class="ph-fill ph-gender-male text-xl"></i>
                                </div>
                            </div>
                            <span class="text-3xl font-extrabold text-elevate-dark"><?php echo e($genderStats['L']); ?></span>
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mt-1">Laki-laki</span>
                        </div>

                        
                        <div class="bg-rose-50 border border-rose-200 rounded-xl p-5 flex flex-col justify-center hover:bg-rose-100 transition-colors shadow-sm">
                            <div class="flex items-center justify-between mb-2">
                                <div class="w-10 h-10 rounded-lg bg-white text-rose-500 border border-rose-200 flex items-center justify-center shadow-sm">
                                    <i class="ph-fill ph-gender-female text-xl"></i>
                                </div>
                            </div>
                            <span class="text-3xl font-extrabold text-elevate-dark"><?php echo e($genderStats['P']); ?></span>
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mt-1">Perempuan</span>
                        </div>
                        
                        
                        <div class="col-span-2 bg-emerald-50 border border-emerald-200 rounded-xl p-6 text-emerald-700 relative overflow-hidden flex items-center justify-between fluent-card">
                            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
                            <div class="absolute -right-10 -top-10 w-32 h-32 bg-white/40 rounded-full blur-xl"></div>
                            <div class="relative z-10">
                                <p class="text-xs font-bold text-emerald-700 uppercase mb-1 tracking-wider">Total Diterima</p>
                                <p class="text-4xl font-black text-emerald-700 tracking-tight"><?php echo e($totalAccepted); ?> <span class="text-sm font-bold text-emerald-700/80">Siswa</span></p>
                            </div>
                            <i class="ph-duotone ph-check-circle text-6xl text-emerald-600/20 relative z-10"></i>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="animate-enter delay-200 border-t border-slate-200 pt-8">
                <h3 class="text-sm font-bold text-elevate-dark mb-5 flex items-center gap-2">
                    <i class="ph-bold ph-download-simple"></i> Area Unduhan Dokumen
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    
                    
                    <div class="bg-white rounded-2xl p-6 fluent-card group flex flex-col h-full hover:border-emerald-600">
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-all duration-300 shadow-sm">
                            <i class="ph-duotone ph-microsoft-excel-logo"></i>
                        </div>
                        <h4 class="font-bold text-elevate-dark text-lg mb-2">Export Data (CSV)</h4>
                        <p class="text-xs text-slate-500 mb-6 flex-1 font-medium leading-relaxed">Download data lengkap seluruh pendaftar untuk diolah di Excel.</p>
                        <a href="<?php echo e(route('admin.ppdb.export.excel')); ?>" class="flex items-center justify-center gap-2 w-full py-2.5 bg-emerald-600 text-white font-bold rounded-xl text-sm hover:bg-emerald-700 transition-all shadow-sm">
                            <i class="ph-bold ph-download-simple"></i> Download CSV
                        </a>
                    </div>

                    
                    <div class="bg-white rounded-2xl p-6 fluent-card group flex flex-col h-full hover:border-rose-600">
                        <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 border border-rose-200 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-all duration-300 shadow-sm">
                            <i class="ph-duotone ph-file-pdf"></i>
                        </div>
                        <h4 class="font-bold text-elevate-dark text-lg mb-2">Laporan Rekap</h4>
                        <p class="text-xs text-slate-500 mb-6 flex-1 font-medium leading-relaxed">Cetak rekapitulasi penerimaan siswa dengan Kop Resmi Dinas.</p>
                        <a href="<?php echo e(route('admin.ppdb.print.recap')); ?>" target="_blank" class="flex items-center justify-center gap-2 w-full py-2.5 bg-rose-600 text-white font-bold rounded-xl text-sm hover:bg-rose-700 transition-all shadow-sm">
                            <i class="ph-bold ph-printer"></i> Preview Laporan
                        </a>
                    </div>

                    
                    <div class="bg-white rounded-2xl p-6 fluent-card group flex flex-col h-full hover:border-elevate-primary">
                        <div class="w-12 h-12 rounded-xl bg-elevate-soft text-elevate-primary border border-elevate-accent/30 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-all duration-300 shadow-sm">
                            <i class="ph-duotone ph-envelope-open"></i>
                        </div>
                        <h4 class="font-bold text-elevate-dark text-lg mb-2">Cetak SKL Massal</h4>
                        <p class="text-xs text-slate-500 mb-6 flex-1 font-medium leading-relaxed">Cetak Surat Keterangan Lulus untuk semua siswa yang Diterima.</p>
                        <a href="<?php echo e(route('admin.ppdb.print.mass_letters')); ?>" target="_blank" class="flex items-center justify-center gap-2 w-full py-2.5 bg-elevate-primary text-white font-bold rounded-xl text-sm hover:bg-elevate-dark transition-all shadow-sm">
                            <i class="ph-bold ph-files"></i> Cetak Massal
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('trackChart').getContext('2d');
            const trackData = {
                zonasi: <?php echo e($trackStats['zonasi']); ?>,
                prestasi: <?php echo e($trackStats['prestasi']); ?>,
                afirmasi: <?php echo e($trackStats['afirmasi']); ?>,
                pindah: <?php echo e($trackStats['pindah_tugas']); ?>

            };

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Zonasi', 'Prestasi', 'Afirmasi', 'Pindah Tugas'],
                    datasets: [{
                        data: [trackData.zonasi, trackData.prestasi, trackData.afirmasi, trackData.pindah],
                        // Diselaraskan dengan hex dari tema Elevate & Semantic
                        backgroundColor: ['#0d52a1', '#10b981', '#f59e0b', '#2c3f61'], 
                        borderWidth: 0, hoverOffset: 10
                    }]
                },
                options: {
                    cutout: '75%',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } }
                }
            });
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/admin/ppdb/reports.blade.php ENDPATH**/ ?>