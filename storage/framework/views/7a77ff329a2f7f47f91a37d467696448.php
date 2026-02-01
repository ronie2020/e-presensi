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
    
    
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-8 sm:py-10 font-sans text-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            
            <div class="relative rounded-[2.5rem] bg-gray-900 bg-gradient-to-br from-blue-900 via-blue-800 to-slate-900 p-8 sm:p-10 text-white shadow-2xl shadow-blue-900/40 overflow-hidden border border-white/10 group">
                
                
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl pointer-events-none group-hover:bg-purple-500/30 transition-all duration-700"></div>
                <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-full h-1/2 bg-gradient-to-t from-blue-900/50 to-transparent pointer-events-none"></div>

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-500/20 border border-purple-400/30 text-purple-200 text-[10px] font-bold uppercase tracking-widest mb-4 backdrop-blur-sm">
                            <i class="ph-fill ph-printer"></i> Pusat Data
                        </div>
                        <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-3 flex items-center gap-3 text-white leading-tight">
                            Laporan & Unduhan
                        </h1>
                        <p class="text-blue-100/80 text-sm md:text-base font-medium leading-relaxed max-w-lg">
                            Pusat rekapitulasi data, statistik visual real-time, dan cetak dokumen massal untuk keperluan arsip sekolah.
                        </p>
                    </div>
                </div>
            </div>

            <!-- 1. STATISTIK VISUAL (REAL TIME) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <!-- Grafik Jalur Pendaftaran -->
                <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 flex flex-col h-full relative overflow-hidden group hover:shadow-xl transition-shadow duration-300">
                    <div class="absolute top-0 right-0 p-6 opacity-5 pointer-events-none">
                        <i class="ph-fill ph-chart-pie-slice text-9xl text-blue-900"></i>
                    </div>

                    <div class="flex items-center gap-3 mb-8 relative z-10">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl border border-blue-100 shadow-sm">
                            <i class="ph-duotone ph-chart-pie-slice"></i>
                        </div>
                        <h3 class="font-black text-slate-800 text-lg">Sebaran Pendaftar</h3>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row items-center gap-8 flex-1 relative z-10">
                        <!-- Area Chart -->
                        <div class="relative w-48 h-48 flex-shrink-0">
                            <canvas id="trackChart"></canvas>
                            <!-- Center Text -->
                            <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                                <span class="text-3xl font-black text-slate-800"><?php echo e($totalRegistrants); ?></span>
                                <span class="text-[10px] uppercase font-bold text-slate-400">Total</span>
                            </div>
                        </div>
                        
                        <!-- Legend Manual -->
                        <div class="flex-1 w-full space-y-3">
                            <?php $__currentLoopData = [
                                ['label' => 'Zonasi', 'val' => $trackStats['zonasi'], 'color' => 'bg-blue-500', 'text' => 'text-blue-600'],
                                ['label' => 'Prestasi', 'val' => $trackStats['prestasi'], 'color' => 'bg-purple-500', 'text' => 'text-purple-600'],
                                ['label' => 'Afirmasi', 'val' => $trackStats['afirmasi'], 'color' => 'bg-orange-500', 'text' => 'text-orange-600'],
                                ['label' => 'Pindah Tugas', 'val' => $trackStats['pindah_tugas'], 'color' => 'bg-slate-500', 'text' => 'text-slate-600']
                            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex justify-between items-center p-2 rounded-lg hover:bg-slate-50 transition-colors">
                                <div class="flex items-center gap-3">
                                    <span class="w-3 h-3 rounded-full <?php echo e($stat['color']); ?> shadow-sm"></span>
                                    <span class="text-sm font-bold text-slate-600"><?php echo e($stat['label']); ?></span>
                                </div>
                                <span class="font-black <?php echo e($stat['text']); ?>"><?php echo e($stat['val']); ?></span>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>

                <!-- Ringkasan Gender & Status -->
                <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 flex flex-col h-full relative overflow-hidden group hover:shadow-xl transition-shadow duration-300">
                    <div class="absolute top-0 right-0 p-6 opacity-5 pointer-events-none">
                        <i class="ph-fill ph-users-three text-9xl text-emerald-900"></i>
                    </div>

                    <div class="flex items-center gap-3 mb-8 relative z-10">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl border border-emerald-100 shadow-sm">
                            <i class="ph-duotone ph-users-three"></i>
                        </div>
                        <h3 class="font-black text-slate-800 text-lg">Demografi Peserta</h3>
                    </div>

                    <div class="flex-1 grid grid-cols-2 gap-4 relative z-10">
                        <!-- Laki-laki -->
                        <div class="bg-blue-50/50 rounded-2xl p-6 flex flex-col justify-center items-center text-center border border-blue-100 hover:bg-blue-50 transition duration-300">
                            <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-2xl mb-3 shadow-sm">
                                <i class="ph-fill ph-gender-male"></i>
                            </div>
                            <span class="text-3xl font-black text-slate-800 mb-1"><?php echo e($genderStats['L']); ?></span>
                            <span class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Laki-laki</span>
                        </div>
                        
                        <!-- Perempuan -->
                        <div class="bg-pink-50/50 rounded-2xl p-6 flex flex-col justify-center items-center text-center border border-pink-100 hover:bg-pink-50 transition duration-300">
                            <div class="w-12 h-12 rounded-full bg-pink-100 text-pink-600 flex items-center justify-center text-2xl mb-3 shadow-sm">
                                <i class="ph-fill ph-gender-female"></i>
                            </div>
                            <span class="text-3xl font-black text-slate-800 mb-1"><?php echo e($genderStats['P']); ?></span>
                            <span class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Perempuan</span>
                        </div>
                        
                        <!-- Total Diterima -->
                        <div class="col-span-2 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-2xl p-6 flex items-center justify-between shadow-lg shadow-emerald-500/20 text-white relative overflow-hidden group-inner">
                            <div class="absolute right-0 top-0 h-full w-full bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 pointer-events-none"></div>
                            
                            <div class="relative z-10">
                                <span class="block text-xs text-emerald-100 font-bold uppercase tracking-wider mb-1">Status Akhir</span>
                                <span class="block text-xl font-black">Siswa Diterima</span>
                            </div>
                            <div class="relative z-10 flex items-center gap-3">
                                <span class="text-5xl font-black tracking-tighter"><?php echo e($totalAccepted); ?></span>
                                <i class="ph-duotone ph-check-circle text-4xl text-emerald-200"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. MENU CETAK & EXPORT -->
            <div class="pt-4">
                <div class="flex items-center gap-3 mb-6">
                    <div class="h-px flex-1 bg-slate-200"></div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Download Center</span>
                    <div class="h-px flex-1 bg-slate-200"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    <!-- Export Excel -->
                    <div class="group bg-white rounded-[2rem] p-1 shadow-sm border border-slate-100 hover:shadow-xl hover:shadow-green-900/5 transition-all duration-300">
                        <div class="bg-green-50/50 rounded-[1.8rem] p-6 h-full flex flex-col relative overflow-hidden group-hover:bg-green-50 transition-colors">
                            <div class="absolute -right-6 -top-6 text-green-200 opacity-50 group-hover:scale-110 transition-transform duration-500">
                                <i class="ph-fill ph-microsoft-excel-logo text-9xl"></i>
                            </div>
                            
                            <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-green-600 shadow-sm mb-4 relative z-10 group-hover:scale-110 transition-transform duration-300">
                                <i class="ph-duotone ph-microsoft-excel-logo text-3xl"></i>
                            </div>
                            
                            <h4 class="font-black text-slate-800 text-lg relative z-10 mb-2">Export CSV</h4>
                            <p class="text-sm text-slate-500 font-medium leading-relaxed relative z-10 mb-6 flex-1">
                                Unduh database lengkap pendaftar dalam format Excel (CSV) untuk analisis lanjutan.
                            </p>
                            
                            <a href="<?php echo e(route('admin.ppdb.export.excel')); ?>" class="relative z-10 w-full py-3.5 bg-green-600 text-white font-bold rounded-xl shadow-lg shadow-green-600/20 hover:bg-green-700 hover:shadow-green-600/30 transition-all flex items-center justify-center gap-2 transform active:scale-[0.98]">
                                <i class="ph-bold ph-download-simple"></i> Download File
                            </a>
                        </div>
                    </div>

                    <!-- Laporan PDF -->
                    <div class="group bg-white rounded-[2rem] p-1 shadow-sm border border-slate-100 hover:shadow-xl hover:shadow-rose-900/5 transition-all duration-300">
                        <div class="bg-rose-50/50 rounded-[1.8rem] p-6 h-full flex flex-col relative overflow-hidden group-hover:bg-rose-50 transition-colors">
                            <div class="absolute -right-6 -top-6 text-rose-200 opacity-50 group-hover:scale-110 transition-transform duration-500">
                                <i class="ph-fill ph-file-pdf text-9xl"></i>
                            </div>
                            
                            <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-rose-600 shadow-sm mb-4 relative z-10 group-hover:scale-110 transition-transform duration-300">
                                <i class="ph-duotone ph-printer text-3xl"></i>
                            </div>
                            
                            <h4 class="font-black text-slate-800 text-lg relative z-10 mb-2">Laporan Rekap</h4>
                            <p class="text-sm text-slate-500 font-medium leading-relaxed relative z-10 mb-6 flex-1">
                                Cetak dokumen resmi rekapitulasi penerimaan siswa baru (Kop Dinas) untuk arsip fisik.
                            </p>
                            
                            <a href="<?php echo e(route('admin.ppdb.print.recap')); ?>" target="_blank" class="relative z-10 w-full py-3.5 bg-rose-600 text-white font-bold rounded-xl shadow-lg shadow-rose-600/20 hover:bg-rose-700 hover:shadow-rose-600/30 transition-all flex items-center justify-center gap-2 transform active:scale-[0.98]">
                                <i class="ph-bold ph-printer"></i> Preview PDF
                            </a>
                        </div>
                    </div>

                    <!-- Cetak Massal Surat -->
                    <div class="group bg-white rounded-[2rem] p-1 shadow-sm border border-slate-100 hover:shadow-xl hover:shadow-blue-900/5 transition-all duration-300">
                        <div class="bg-blue-50/50 rounded-[1.8rem] p-6 h-full flex flex-col relative overflow-hidden group-hover:bg-blue-50 transition-colors">
                            <div class="absolute -right-6 -top-6 text-blue-200 opacity-50 group-hover:scale-110 transition-transform duration-500">
                                <i class="ph-fill ph-files text-9xl"></i>
                            </div>
                            
                            <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-blue-600 shadow-sm mb-4 relative z-10 group-hover:scale-110 transition-transform duration-300">
                                <i class="ph-duotone ph-envelope-open text-3xl"></i>
                            </div>
                            
                            <h4 class="font-black text-slate-800 text-lg relative z-10 mb-2">Surat Kelulusan</h4>
                            <p class="text-sm text-slate-500 font-medium leading-relaxed relative z-10 mb-6 flex-1">
                                Generator otomatis SKL (Surat Keterangan Lulus) untuk seluruh siswa yang berstatus 'Diterima'.
                            </p>
                            
                            <a href="<?php echo e(route('admin.ppdb.print.mass_letters')); ?>" target="_blank" class="relative z-10 w-full py-3.5 bg-blue-600 text-white font-bold rounded-xl shadow-lg shadow-blue-600/20 hover:bg-blue-700 hover:shadow-blue-600/30 transition-all flex items-center justify-center gap-2 transform active:scale-[0.98]">
                                <i class="ph-bold ph-files"></i> Cetak Massal
                            </a>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- Script Chart.js -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('trackChart').getContext('2d');
            
            // Data dari Controller
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
                        backgroundColor: [
                            '#3b82f6', // Blue 500
                            '#a855f7', // Purple 500
                            '#f97316', // Orange 500
                            '#64748b'  // Slate 500
                        ],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    cutout: '75%',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            padding: 12,
                            titleFont: { family: 'ui-sans-serif', size: 13 },
                            bodyFont: { family: 'ui-sans-serif', size: 13, weight: 'bold' },
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    if (label) { label += ': '; }
                                    let value = context.raw;
                                    let total = context.chart._metasets[context.datasetIndex].total;
                                    let percentage = Math.round((value / total) * 100) + '%';
                                    return label + value + ' (' + percentage + ')';
                                }
                            }
                        }
                    }
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