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
    </style>

    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="relative space-y-8 min-h-screen pb-10 font-sans text-slate-800 bg-slate-50">
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 py-8">

            
            <div class="animate-enter relative rounded-[2.5rem] bg-gradient-to-r from-blue-900 via-slate-800 to-slate-900 overflow-hidden p-8 sm:p-12 text-white shadow-2xl shadow-blue-900/20 group border border-white/10">
                
                <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-purple-500 rounded-full mix-blend-overlay filter blur-[120px] opacity-20 group-hover:opacity-30 transition-opacity duration-1000"></div>
                <div class="absolute bottom-0 left-0 w-[300px] h-[300px] bg-blue-500 rounded-full mix-blend-overlay filter blur-[100px] opacity-20"></div>
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 pointer-events-none"></div>

                <div class="relative z-10">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/10 text-purple-200 text-xs font-bold uppercase tracking-wider mb-4 backdrop-blur-sm shadow-sm">
                        <i class="ph-fill ph-chart-bar"></i> Pusat Data & Laporan
                    </div>
                    <h1 class="text-3xl md:text-5xl font-extrabold text-white tracking-tight mb-3">
                        Laporan <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-200 to-white">& Unduhan</span>
                    </h1>
                    <p class="text-blue-100/80 text-sm md:text-base font-medium leading-relaxed max-w-xl">
                        Pantau statistik pendaftar secara real-time dan unduh rekapitulasi data untuk keperluan arsip sekolah.
                    </p>
                </div>
            </div>

            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                
                <div class="animate-enter delay-100 bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-xl hover:shadow-blue-100/50 transition-all duration-300">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl border border-blue-100 group-hover:scale-110 transition-transform duration-300">
                            <i class="ph-duotone ph-chart-pie-slice animate-wiggle"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 text-lg">Sebaran Pendaftar</h3>
                            <p class="text-xs text-slate-400 font-bold uppercase tracking-wide">Berdasarkan jalur masuk</p>
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row items-center gap-8">
                        <div class="relative w-48 h-48 flex-shrink-0">
                            <canvas id="trackChart"></canvas>
                            <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                                <span class="text-3xl font-black text-slate-800"><?php echo e($totalRegistrants); ?></span>
                                <span class="text-[10px] uppercase font-bold text-slate-400">Siswa</span>
                            </div>
                        </div>
                        
                        <div class="flex-1 w-full space-y-3">
                            <?php $__currentLoopData = [
                                ['label' => 'Zonasi', 'val' => $trackStats['zonasi'], 'color' => 'bg-blue-500', 'bg' => 'bg-blue-50', 'text' => 'text-blue-600'],
                                ['label' => 'Prestasi', 'val' => $trackStats['prestasi'], 'color' => 'bg-purple-500', 'bg' => 'bg-purple-50', 'text' => 'text-purple-600'],
                                ['label' => 'Afirmasi', 'val' => $trackStats['afirmasi'], 'color' => 'bg-orange-500', 'bg' => 'bg-orange-50', 'text' => 'text-orange-600'],
                                ['label' => 'Pindah', 'val' => $trackStats['pindah_tugas'], 'color' => 'bg-slate-500', 'bg' => 'bg-slate-50', 'text' => 'text-slate-600']
                            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex justify-between items-center p-3 rounded-2xl <?php echo e($stat['bg']); ?> border border-transparent hover:border-slate-200 transition-colors">
                                <div class="flex items-center gap-3">
                                    <span class="w-3 h-3 rounded-full <?php echo e($stat['color']); ?> shadow-sm"></span>
                                    <span class="text-xs font-bold <?php echo e($stat['text']); ?>"><?php echo e($stat['label']); ?></span>
                                </div>
                                <span class="font-black text-slate-800 text-sm"><?php echo e($stat['val']); ?></span>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>

                
                <div class="animate-enter delay-100 bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 relative overflow-hidden flex flex-col group hover:shadow-xl hover:shadow-emerald-100/50 transition-all duration-300">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl border border-emerald-100 group-hover:scale-110 transition-transform duration-300">
                            <i class="ph-duotone ph-users-three animate-wiggle"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 text-lg">Ringkasan Data</h3>
                            <p class="text-xs text-slate-400 font-bold uppercase tracking-wide">Gender & Kelulusan</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 flex-1">
                        
                        <div class="bg-blue-50/50 border border-blue-100 rounded-2xl p-5 flex flex-col justify-center hover:bg-blue-50 transition-colors">
                            <div class="flex items-center justify-between mb-2">
                                <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                                    <i class="ph-fill ph-gender-male text-xl"></i>
                                </div>
                            </div>
                            <span class="text-3xl font-extrabold text-slate-800"><?php echo e($genderStats['L']); ?></span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Laki-laki</span>
                        </div>

                        
                        <div class="bg-pink-50/50 border border-pink-100 rounded-2xl p-5 flex flex-col justify-center hover:bg-pink-50 transition-colors">
                            <div class="flex items-center justify-between mb-2">
                                <div class="w-10 h-10 rounded-xl bg-pink-100 text-pink-600 flex items-center justify-center">
                                    <i class="ph-fill ph-gender-female text-xl"></i>
                                </div>
                            </div>
                            <span class="text-3xl font-extrabold text-slate-800"><?php echo e($genderStats['P']); ?></span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Perempuan</span>
                        </div>
                        
                        
                        <div class="col-span-2 bg-gradient-to-r from-emerald-600 to-emerald-500 rounded-2xl p-6 text-white relative overflow-hidden flex items-center justify-between shadow-lg shadow-emerald-600/20">
                            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
                            <div class="relative z-10">
                                <p class="text-xs font-bold text-emerald-100 uppercase mb-1 tracking-wider">Total Diterima</p>
                                <p class="text-4xl font-black tracking-tight"><?php echo e($totalAccepted); ?> <span class="text-sm font-medium text-emerald-100">Siswa</span></p>
                            </div>
                            <i class="ph-duotone ph-check-circle text-6xl text-emerald-200/50 relative z-10"></i>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="animate-enter delay-200 border-t border-slate-200 pt-8">
                <h3 class="text-sm font-extrabold text-slate-400 uppercase tracking-widest mb-6 ml-1">Area Unduhan</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    
                    <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm hover:shadow-xl hover:shadow-green-100/50 hover:border-green-200 transition-all duration-300 group flex flex-col h-full">
                        <div class="w-14 h-14 rounded-2xl bg-green-50 text-green-600 flex items-center justify-center text-3xl mb-4 group-hover:scale-110 group-hover:bg-green-600 group-hover:text-white transition-all duration-300 shadow-sm">
                            <i class="ph-duotone ph-microsoft-excel-logo"></i>
                        </div>
                        <h4 class="font-bold text-slate-800 text-lg mb-2">Export Data (CSV)</h4>
                        <p class="text-sm text-slate-500 mb-6 flex-1">Download data lengkap seluruh pendaftar untuk diolah di Excel.</p>
                        <a href="<?php echo e(route('admin.ppdb.export.excel')); ?>" class="flex items-center justify-center gap-2 w-full py-3 bg-slate-900 text-white font-bold rounded-xl text-sm hover:bg-green-600 transition-all shadow-lg shadow-slate-900/10 hover:shadow-green-600/30">
                            <i class="ph-bold ph-download-simple"></i> Download
                        </a>
                    </div>

                    
                    <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm hover:shadow-xl hover:shadow-rose-100/50 hover:border-rose-200 transition-all duration-300 group flex flex-col h-full">
                        <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-3xl mb-4 group-hover:scale-110 group-hover:bg-rose-600 group-hover:text-white transition-all duration-300 shadow-sm">
                            <i class="ph-duotone ph-file-pdf"></i>
                        </div>
                        <h4 class="font-bold text-slate-800 text-lg mb-2">Laporan Rekap</h4>
                        <p class="text-sm text-slate-500 mb-6 flex-1">Cetak rekapitulasi penerimaan siswa dengan Kop Resmi Dinas.</p>
                        <a href="<?php echo e(route('admin.ppdb.print.recap')); ?>" target="_blank" class="flex items-center justify-center gap-2 w-full py-3 bg-slate-900 text-white font-bold rounded-xl text-sm hover:bg-rose-600 transition-all shadow-lg shadow-slate-900/10 hover:shadow-rose-600/30">
                            <i class="ph-bold ph-printer"></i> Preview PDF
                        </a>
                    </div>

                    
                    <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm hover:shadow-xl hover:shadow-blue-100/50 hover:border-blue-200 transition-all duration-300 group flex flex-col h-full">
                        <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-3xl mb-4 group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300 shadow-sm">
                            <i class="ph-duotone ph-envelope-open"></i>
                        </div>
                        <h4 class="font-bold text-slate-800 text-lg mb-2">Cetak SKL Massal</h4>
                        <p class="text-sm text-slate-500 mb-6 flex-1">Cetak Surat Keterangan Lulus untuk semua siswa 'Diterima' sekaligus.</p>
                        <a href="<?php echo e(route('admin.ppdb.print.mass_letters')); ?>" target="_blank" class="flex items-center justify-center gap-2 w-full py-3 bg-slate-900 text-white font-bold rounded-xl text-sm hover:bg-blue-600 transition-all shadow-lg shadow-slate-900/10 hover:shadow-blue-600/30">
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
                        backgroundColor: ['#3b82f6', '#a855f7', '#f97316', '#64748b'],
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
<?php endif; ?><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/admin/ppdb/reports.blade.php ENDPATH**/ ?>