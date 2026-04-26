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

    <?php $__env->startPush('styles'); ?>
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }
        
        .fluent-card { box-shadow: 0 1.6px 3.6px 0 rgba(0, 0, 0, 0.132), 0 0.3px 0.9px 0 rgba(0, 0, 0, 0.108); border: 1px solid rgba(0, 0, 0, 0.05); transition: all 0.3s ease; }
    </style>
    <?php $__env->stopPush(); ?>

    <div class="py-6 font-sans text-slate-800 bg-slate-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            
            <div class="animate-enter relative rounded-xl bg-gradient-to-br from-[#25D0FF] via-[#5295FF] to-[#FFC9B9] p-6 md:p-10 text-[#2A3B52] shadow-[0_10px_40px_-10px_rgba(37,208,255,0.4)] overflow-hidden group border border-white/40 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                
                <div class="absolute inset-0 opacity-[0.03] bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none mix-blend-overlay no-print"></div>
            
                <div class="relative z-10 flex items-center gap-5">
                    <div class="w-16 h-16 rounded-xl bg-white/40 backdrop-blur-md flex items-center justify-center border border-white/50 shadow-sm shrink-0">
                        <i class="ph-duotone ph-chart-polar text-4xl text-[#2A3B52]"></i>
                    </div>
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/40 border border-white/50 text-[#2A3B52] text-[10px] font-bold uppercase tracking-wider mb-2 backdrop-blur-sm shadow-sm">
                            <i class="ph-bold ph-trend-up"></i> Evaluasi Kedisiplinan
                        </div>
                        <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight leading-tight">
                            Statistik & Analitik
                        </h2>
                    </div>
                </div>

                
                <div class="relative z-10 w-full md:w-auto mt-4 md:mt-0">
                    <form action="<?php echo e(route('permit.analytics')); ?>" method="GET" id="monthFilterForm" class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                        <label for="monthFilter" class="text-[#2A3B52] text-sm font-bold opacity-90 hidden sm:block">Pilih Bulan:</label>
                        <div class="relative w-full sm:w-auto">
                            <input type="month" name="month" id="monthFilter" value="<?php echo e($selectedMonth); ?>" 
                                onchange="document.getElementById('monthFilterForm').submit()"
                                class="w-full bg-white/50 hover:bg-white border border-[#2A3B52]/20 text-[#2A3B52] text-sm rounded-lg px-4 py-2.5 focus:ring-0 focus:border-[#5295FF] backdrop-blur-md cursor-pointer transition-all shadow-sm font-bold">
                        </div>
                    </form>
                </div>
            </div>

            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 animate-enter delay-100">
                <div class="bg-white p-5 rounded-xl fluent-card hover:border-[#D0E7F8] hover:-translate-y-1 transition-all duration-300 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-[#F3F9FD] text-[#5295FF] border border-[#D0E7F8] flex items-center justify-center text-xl shrink-0"><i class="ph-bold ph-door-open"></i></div>
                    <div>
                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Total Izin</div>
                        <div class="text-2xl font-black text-[#2A3B52]"><?php echo e($kpiTotalMonth ?? 0); ?></div>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-xl fluent-card hover:border-[#FFD8A8] hover:-translate-y-1 transition-all duration-300 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-[#FFEFD6] text-[#D83B01] border border-[#FFD8A8] flex items-center justify-center text-xl shrink-0"><i class="ph-bold ph-timer"></i></div>
                    <div>
                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Rata-rata Durasi</div>
                        <div class="text-2xl font-black text-[#2A3B52]"><?php echo e($kpiAvgDuration ?? 0); ?><span class="text-sm text-slate-400 font-medium ml-1">mnt</span></div>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-xl fluent-card hover:border-[#F4C3C9] hover:-translate-y-1 transition-all duration-300 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-[#FDE7E9] text-[#D13438] border border-[#F4C3C9] flex items-center justify-center text-xl shrink-0"><i class="ph-bold ph-warning"></i></div>
                    <div>
                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Siswa Telat</div>
                        <div class="text-2xl font-black text-[#2A3B52]"><?php echo e($kpiOverdue ?? 0); ?></div>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-xl fluent-card hover:border-[#B7DFB9] hover:-translate-y-1 transition-all duration-300 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-[#DFF6DD] text-[#107C10] border border-[#B7DFB9] flex items-center justify-center text-xl shrink-0"><i class="ph-bold ph-check-circle"></i></div>
                    <div>
                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Penyelesaian</div>
                        <div class="text-2xl font-black text-[#2A3B52]"><?php echo e($kpiCompletionRate ?? 100); ?><span class="text-sm text-slate-400 font-medium ml-1">%</span></div>
                    </div>
                </div>
            </div>

            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                
                
                <div class="lg:col-span-8 bg-white p-6 md:p-8 rounded-xl fluent-card animate-enter delay-200">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="font-extrabold text-[#2A3B52] text-lg flex items-center gap-2">
                                <div class="w-8 h-8 rounded-md bg-[#F3F9FD] text-[#5295FF] border border-[#D0E7F8] flex items-center justify-center"><i class="ph-bold ph-clock"></i></div>
                                Jam Keluar Paling Sibuk
                            </h3>
                            <p class="text-xs text-slate-500 mt-1 ml-10">Distribusi frekuensi izin siswa (Akumulasi Bulan <?php echo e($parsedDate->translatedFormat('F Y')); ?>).</p>
                        </div>
                    </div>
                    <div class="relative h-[300px] w-full">
                        <canvas id="timeChart"></canvas>
                    </div>
                </div>

                
                <div class="lg:col-span-4 bg-white p-6 md:p-8 rounded-xl fluent-card animate-enter delay-200 flex flex-col">
                    <div class="mb-4">
                        <h3 class="font-extrabold text-[#2A3B52] text-lg flex items-center gap-2">
                            <div class="w-8 h-8 rounded-md bg-[#FDE7E9] text-[#D13438] border border-[#F4C3C9] flex items-center justify-center"><i class="ph-bold ph-siren"></i></div>
                            Top 5 Sering Izin
                        </h3>
                        <p class="text-[10px] text-slate-400 uppercase tracking-widest mt-1 ml-10 font-bold">Bulan <?php echo e($parsedDate->translatedFormat('F Y')); ?></p>
                    </div>
                    
                    <div class="flex-1 overflow-y-auto pr-2 space-y-3">
                        <?php $__empty_1 = true; $__currentLoopData = $topStudents ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="flex items-center justify-between p-3 rounded-lg bg-slate-50 border border-slate-100 hover:border-[#F4C3C9] transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-md flex items-center justify-center text-xs font-bold text-white shadow-sm
                                        <?php echo e($index == 0 ? 'bg-[#D13438]' : ($index == 1 ? 'bg-[#D83B01]' : 'bg-slate-400')); ?>">
                                        <?php echo e($index + 1); ?>

                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-[#2A3B52] truncate max-w-[120px]" title="<?php echo e($student->name); ?>"><?php echo e($student->name); ?></div>
                                        <div class="text-[10px] text-slate-500 font-bold uppercase"><?php echo e($student->class_name); ?></div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <span class="block text-lg font-black text-[#D13438] leading-none"><?php echo e($student->total_izin); ?></span>
                                    <span class="text-[9px] font-bold text-slate-400 uppercase">Kali</span>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="h-full flex flex-col items-center justify-center text-slate-400 opacity-70">
                                <i class="ph-duotone ph-shield-check text-4xl mb-2"></i>
                                <p class="text-xs text-center font-medium">Belum ada data menonjol bulan ini.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="lg:col-span-4 bg-white p-6 md:p-8 rounded-xl fluent-card animate-enter delay-300">
                    <div class="mb-6">
                        <h3 class="font-extrabold text-[#2A3B52] text-lg flex items-center gap-2">
                            <div class="w-8 h-8 rounded-md bg-[#FFEFD6] text-[#D83B01] border border-[#FFD8A8] flex items-center justify-center"><i class="ph-bold ph-question"></i></div>
                            Proporsi Alasan
                        </h3>
                    </div>
                    <div class="relative h-[250px] w-full flex justify-center">
                        <canvas id="reasonChart"></canvas>
                    </div>
                </div>

                
                <div class="lg:col-span-8 bg-white p-6 md:p-8 rounded-xl fluent-card animate-enter delay-300">
                    <div class="mb-6">
                        <h3 class="font-extrabold text-[#2A3B52] text-lg flex items-center gap-2">
                            <div class="w-8 h-8 rounded-md bg-[#DFF6DD] text-[#107C10] border border-[#B7DFB9] flex items-center justify-center"><i class="ph-bold ph-users-three"></i></div>
                            Tingkat Izin Berdasarkan Kelas
                        </h3>
                        <p class="text-xs text-slate-500 mt-1 ml-10">Membantu mengevaluasi kedisiplinan masing-masing kelas.</p>
                    </div>
                    <div class="relative h-[250px] w-full">
                        <canvas id="classChart"></canvas>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
            Chart.defaults.color = '#64748b'; 
            
            const timeLabels = <?php echo json_encode($timeLabels ?? []); ?>;
            const timeData = <?php echo json_encode($timeData ?? []); ?>;
            const reasonLabels = <?php echo json_encode($reasonLabels ?? []); ?>;
            const reasonData = <?php echo json_encode($reasonData ?? []); ?>;
            const classLabels = <?php echo json_encode($classLabels ?? []); ?>;
            const classData = <?php echo json_encode($classData ?? []); ?>;

            // 1. CHART JAM SIBUK
            if(document.getElementById('timeChart')) {
                new Chart(document.getElementById('timeChart'), {
                    type: 'line',
                    data: {
                        labels: timeLabels,
                        datasets: [{
                            label: 'Jumlah Izin',
                            data: timeData,
                            borderColor: '#5295FF', // Elevate Blue
                            backgroundColor: 'rgba(82, 149, 255, 0.1)', 
                            borderWidth: 3,
                            tension: 0.4, 
                            fill: true,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#5295FF',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, grid: { borderDash: [5, 5], color: '#f1f5f9' }, ticks: { precision: 0 } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }

            // 2. CHART ALASAN
            if(document.getElementById('reasonChart')) {
                new Chart(document.getElementById('reasonChart'), {
                    type: 'doughnut',
                    data: {
                        labels: reasonLabels,
                        datasets: [{
                            data: reasonData,
                            backgroundColor: ['#5295FF', '#107C10', '#D83B01', '#2A3B52', '#D13438', '#8b5cf6'], 
                            borderWidth: 0,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        cutout: '70%',
                        plugins: {
                            legend: { position: 'bottom', labels: { padding: 20, usePointStyle: true, pointStyle: 'circle' } }
                        }
                    }
                });
            }

            // 3. CHART KELAS
            if(document.getElementById('classChart')) {
                new Chart(document.getElementById('classChart'), {
                    type: 'bar',
                    data: {
                        labels: classLabels,
                        datasets: [{
                            label: 'Total Izin',
                            data: classData,
                            // Warna merah jika lebih dari 20 (terlalu banyak)
                            backgroundColor: classData.map(val => val > 20 ? '#D13438' : '#5295FF'), 
                            borderRadius: 6,
                            barThickness: 30
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, grid: { borderDash: [5, 5], color: '#f1f5f9' }, ticks: { precision: 0 } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/permit/analytics.blade.php ENDPATH**/ ?>