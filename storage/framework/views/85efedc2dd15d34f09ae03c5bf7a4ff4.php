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
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    
    <style>
        /* 1. Animasi Masuk (Staggered Fade In Up) */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-enter {
            opacity: 0;
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        /* Delay bertingkat */
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        .delay-400 { animation-delay: 0.4s; }

        /* 2. Animasi Background Hero (Floating Blobs) */
        @keyframes float {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(-10px, -15px); }
        }
        @keyframes float-reverse {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(10px, 15px); }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }
        .animate-float-reverse { animation: float-reverse 7s ease-in-out infinite; }

        /* 3. Animasi Berkedip (Twinkle) untuk Partikel */
        @keyframes twinkle {
            0%, 100% { opacity: 0.3; transform: scale(0.8); }
            50% { opacity: 1; transform: scale(1.2); box-shadow: 0 0 10px rgba(255,255,255,0.8); }
        }
        .animate-twinkle { animation: twinkle 3s ease-in-out infinite; }
        .delay-slow { animation-delay: 1.5s; }

        /* 4. Animasi Teks Mengkilap (Shimmering Gradient) */
        @keyframes shimmer {
            0% { background-position: -200% center; }
            100% { background-position: 200% center; }
        }
        .text-shimmer {
            background: linear-gradient(to right, #ffffff 0%, #bfdbfe 20%, #ffffff 40%, #ffffff 100%);
            background-size: 200% auto;
            color: #fff;
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: shimmer 3s linear infinite;
        }

        /* 5. Animasi Icon Goyang (Wiggle) saat Hover */
        @keyframes wiggle {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(-10deg); }
            75% { transform: rotate(10deg); }
        }
        .group:hover .animate-wiggle {
            animation: wiggle 0.5s ease-in-out;
        }
    </style>

    <div class="py-6 sm:py-8 font-sans text-slate-800">
        
        
        <div class="animate-enter max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 sm:mb-10">
            <div class="relative rounded-[2.5rem] bg-gray-900 bg-gradient-to-br from-blue-900 via-blue-800 to-slate-900 p-8 sm:p-10 text-white shadow-2xl shadow-blue-900/40 overflow-hidden border border-white/10 group">
                
                
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                
                
                <div class="absolute -top-24 -right-24 w-80 h-80 bg-blue-500/20 rounded-full blur-3xl pointer-events-none group-hover:bg-blue-500/30 transition-all duration-700 animate-float"></div>
                <div class="absolute bottom-0 right-20 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none animate-float-reverse"></div>
                
                
                <div class="absolute top-10 left-20 w-1.5 h-1.5 bg-blue-300 rounded-full animate-twinkle"></div>
                <div class="absolute top-1/2 right-1/3 w-1 h-1 bg-white rounded-full animate-twinkle delay-200"></div>
                <div class="absolute bottom-20 left-1/3 w-2 h-2 bg-indigo-300 rounded-full animate-twinkle delay-slow blur-[1px]"></div>
                <div class="absolute top-20 right-20 w-1.5 h-1.5 bg-blue-100 rounded-full animate-twinkle delay-300"></div>

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                    
                    
                    <div class="max-w-2xl">
                        
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-200 text-[10px] font-bold uppercase tracking-widest mb-4 backdrop-blur-sm shadow-[0_0_15px_rgba(59,130,246,0.3)] animate-pulse">
                            <i class="ph-fill ph-books"></i> Sistem Perpustakaan Digital
                        </div>
                        <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-3 flex items-center gap-3 text-white leading-tight drop-shadow-lg">
                            Dashboard Pustaka
                        </h1>
                        <p class="text-blue-100/80 text-sm md:text-base font-medium leading-relaxed max-w-lg">
                            Pusat kontrol manajemen buku, sirkulasi peminjaman, dan statistik kunjungan siswa secara real-time.
                        </p>
                    </div>
                    
                    
                    <div class="w-full md:w-auto mt-4 md:mt-0">
                        <div class="grid grid-cols-2 md:grid-cols-1 lg:grid-cols-2 gap-4">
                            
                            <div class="bg-white/10 backdrop-blur-md px-5 py-5 rounded-2xl border border-white/20 text-center md:text-left hover:bg-white/15 transition-all hover:scale-105 duration-300 group/card shadow-lg">
                                <div class="flex flex-col md:flex-row lg:flex-col items-center justify-center md:justify-start gap-2 mb-1 text-blue-300">
                                    <i class="ph-duotone ph-book-bookmark text-2xl md:text-xl lg:text-2xl group-hover/card:text-white transition-colors"></i>
                                    <span class="text-[10px] font-bold uppercase tracking-wider">Koleksi Buku</span>
                                </div>
                                
                                <span class="block text-3xl font-black tracking-tight count-up text-shimmer mt-1" data-target="<?php echo e($totalBooks); ?>">0</span>
                            </div>

                            
                            <div class="bg-orange-500/20 backdrop-blur-md px-5 py-5 rounded-2xl border border-orange-400/30 text-center md:text-left hover:bg-orange-500/30 transition-all hover:scale-105 duration-300 group/card shadow-lg">
                                <div class="flex flex-col md:flex-row lg:flex-col items-center justify-center md:justify-start gap-2 mb-1 text-orange-300">
                                    <i class="ph-duotone ph-users-three text-2xl md:text-xl lg:text-2xl group-hover/card:text-white transition-colors"></i>
                                    <span class="text-[10px] font-bold uppercase tracking-wider">Pengunjung</span>
                                </div>
                                
                                <span class="block text-3xl font-black tracking-tight count-up text-shimmer mt-1" data-target="<?php echo e($todayVisits); ?>">0</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- KOLOM UTAMA (KIRI - 2/3) -->
                <div class="lg:col-span-2 space-y-8">

                    <!-- 1. Menu Akses Cepat -->
                    <div class="animate-enter delay-100 bg-white p-6 sm:p-8 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden group/panel">
                        
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-500 via-indigo-500 to-blue-500 bg-[length:200%_100%] animate-[shimmer_3s_linear_infinite]"></div>
                        
                        <h2 class="text-lg font-black text-slate-800 mb-6 flex items-center gap-2">
                            <i class="ph-fill ph-lightning text-yellow-500 text-xl drop-shadow-md"></i> Akses Cepat
                        </h2>
                        
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            
                            <a href="<?php echo e(route('library.circulation.index')); ?>" class="group flex flex-col items-center justify-center p-5 bg-blue-50/50 hover:bg-blue-600 border border-blue-100 hover:border-blue-500 rounded-[2rem] transition-all duration-300 hover:shadow-lg hover:shadow-blue-500/30 hover:-translate-y-2 transform">
                                <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center shadow-sm mb-3 text-blue-600 group-hover:text-blue-600 group-hover:bg-white transition-colors">
                                    <i class="ph-duotone ph-arrows-left-right text-3xl animate-wiggle"></i>
                                </div>
                                <span class="font-bold text-slate-600 text-sm group-hover:text-white transition-colors text-center">Sirkulasi</span>
                            </a>
                            
                            
                            <button onclick="searchMemberPopup()" class="group flex flex-col items-center justify-center p-5 bg-purple-50/50 hover:bg-purple-600 border border-purple-100 hover:border-purple-500 rounded-[2rem] transition-all duration-300 hover:shadow-lg hover:shadow-purple-500/30 hover:-translate-y-2 transform">
                                <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center shadow-sm mb-3 text-purple-600 group-hover:text-purple-600 group-hover:bg-white transition-colors">
                                    <i class="ph-duotone ph-user-focus text-3xl animate-wiggle"></i>
                                </div>
                                <span class="font-bold text-slate-600 text-sm group-hover:text-white transition-colors text-center">Cari Siswa</span>
                            </button>
                            
                            
                            <a href="<?php echo e(route('library.books.create')); ?>" class="group flex flex-col items-center justify-center p-5 bg-emerald-50/50 hover:bg-emerald-600 border border-emerald-100 hover:border-emerald-500 rounded-[2rem] transition-all duration-300 hover:shadow-lg hover:shadow-emerald-500/30 hover:-translate-y-2 transform">
                                <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center shadow-sm mb-3 text-emerald-600 group-hover:text-emerald-600 group-hover:bg-white transition-colors">
                                    <i class="ph-duotone ph-plus-circle text-3xl animate-wiggle"></i>
                                </div>
                                <span class="font-bold text-slate-600 text-sm group-hover:text-white transition-colors text-center">Input Buku</span>
                            </a>
                            
                            
                            <a href="<?php echo e(route('library.kiosk.index')); ?>" target="_blank" class="group flex flex-col items-center justify-center p-5 bg-orange-50/50 hover:bg-orange-600 border border-orange-100 hover:border-orange-500 rounded-[2rem] transition-all duration-300 hover:shadow-lg hover:shadow-orange-500/30 hover:-translate-y-2 transform">
                                <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center shadow-sm mb-3 text-orange-600 group-hover:text-orange-600 group-hover:bg-white transition-colors">
                                    <i class="ph-duotone ph-desktop text-3xl animate-wiggle"></i>
                                </div>
                                <span class="font-bold text-slate-600 text-sm group-hover:text-white transition-colors text-center">Mode Kiosk</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- 2. Grafik Statistik -->
                    <div class="animate-enter delay-200 bg-white p-6 sm:p-8 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 hover:shadow-2xl hover:shadow-blue-900/5 transition-all duration-500">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
                            <div>
                                <h2 class="text-lg font-black text-slate-800 flex items-center gap-2">
                                    <i class="ph-fill ph-chart-bar text-blue-900"></i> Statistik Bulanan
                                </h2>
                                <p class="text-xs text-slate-400 font-bold mt-1 uppercase tracking-wide">Analisa Peminjaman & Kunjungan</p>
                            </div>
                            <!-- Toggle Chart -->
                            <div class="flex bg-slate-100 p-1.5 rounded-xl border border-slate-200 w-full sm:w-auto">
                                <button onclick="toggleChart('loans')" id="btn-loans" class="flex-1 sm:flex-none px-4 py-2 rounded-lg text-xs font-black bg-white shadow-sm text-blue-900 transition-all hover:scale-105 active:scale-95 text-center">Peminjaman</button>
                                <button onclick="toggleChart('visits')" id="btn-visits" class="flex-1 sm:flex-none px-4 py-2 rounded-lg text-xs font-bold text-slate-500 hover:text-slate-800 transition-all hover:scale-105 active:scale-95 text-center">Kunjungan</button>
                            </div>
                        </div>
                        <div class="h-64 sm:h-72 w-full relative">
                             <canvas id="mainChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- SIDEBAR KANAN (1/3) -->
                <div class="lg:col-span-1 space-y-8">
                     
                     <!-- 3. Status Sirkulasi -->
                     <div class="animate-enter delay-300 bg-white p-6 sm:p-8 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="font-black text-slate-800 text-lg">Status Sirkulasi</h3>
                            <div class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center animate-pulse">
                                
                                <i class="ph-duotone ph-arrows-left-right"></i>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="p-4 sm:p-5 bg-indigo-50 rounded-3xl text-center border border-indigo-100 hover:bg-indigo-100 transition-colors group cursor-default">
                                <div class="w-10 h-10 mx-auto bg-white text-indigo-600 rounded-full flex items-center justify-center shadow-sm mb-2 group-hover:scale-110 transition-transform">
                                    
                                    <i class="ph-duotone ph-book-open-text text-lg"></i>
                                </div>
                                <p class="text-2xl sm:text-3xl font-black text-slate-800 count-up" data-target="<?php echo e($borrowedBooks); ?>">0</p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Dipinjam</p>
                            </div>
                            <div class="p-4 sm:p-5 bg-rose-50 rounded-3xl text-center border border-rose-100 hover:bg-rose-100 transition-colors group cursor-default">
                                <div class="w-10 h-10 mx-auto bg-white text-rose-600 rounded-full flex items-center justify-center shadow-sm mb-2 group-hover:scale-110 transition-transform">
                                    
                                    <i class="ph-duotone ph-warning-circle text-lg"></i>
                                </div>
                                <p class="text-2xl sm:text-3xl font-black text-slate-800 count-up" data-target="<?php echo e($overdueBooks); ?>">0</p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Terlambat</p>
                            </div>
                        </div>

                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                            <div class="flex justify-between items-center text-xs mb-2">
                                <span class="font-bold text-slate-500">Anggota Meminjam</span>
                                <span class="font-black text-blue-900 bg-blue-100 px-2 py-0.5 rounded"><?php echo e($membersBorrowingCount); ?> Siswa</span>
                            </div>
                            <div class="w-full bg-slate-200 rounded-full h-2.5 overflow-hidden">
                                <div class="bg-blue-600 h-2.5 rounded-full animate-pulse" style="width: <?php echo e($activeMembers > 0 ? ($membersBorrowingCount / $activeMembers) * 100 : 0); ?>%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- 4. Log Aktivitas -->
                    <div class="animate-enter delay-400 bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden flex flex-col h-[500px]">
                        <div class="p-6 border-b border-slate-50 flex items-center justify-between bg-white z-10">
                            <h3 class="font-black text-slate-800 text-lg flex items-center gap-2">
                                <i class="ph-fill ph-clock-counter-clockwise text-blue-900"></i> Log Aktivitas
                            </h3>
                        </div>
                        
                        <div class="p-0 overflow-y-auto flex-1 custom-scrollbar">
                            <div class="divide-y divide-slate-50">
                                <?php $__empty_1 = true; $__currentLoopData = $recentActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <div class="p-5 hover:bg-blue-50/50 transition-colors flex gap-4 items-start group cursor-pointer">
                                        <div class="shrink-0 mt-1 transition-transform group-hover:scale-110 duration-200">
                                            <?php if($activity->type == 'visit'): ?>
                                                <div class="w-10 h-10 rounded-2xl bg-orange-50 text-orange-500 border border-orange-100 flex items-center justify-center text-lg shadow-sm">
                                                    
                                                    <i class="ph-duotone ph-door-open"></i>
                                                </div>
                                            <?php else: ?>
                                                <div class="w-10 h-10 rounded-2xl flex items-center justify-center text-lg shadow-sm border <?php echo e($activity->status == 'returned' ? 'bg-emerald-50 text-emerald-500 border-emerald-100' : 'bg-blue-50 text-blue-500 border-blue-100'); ?>">
                                                    
                                                    <i class="<?php echo e($activity->status == 'returned' ? 'ph-duotone ph-arrow-u-down-left' : 'ph-duotone ph-arrow-u-right-up'); ?>"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-bold text-slate-800 truncate group-hover:text-blue-700 transition-colors"><?php echo e($activity->student->name); ?></p>
                                            
                                            <?php if($activity->type == 'visit'): ?>
                                                <p class="text-xs text-slate-500 font-medium flex items-center gap-1 mt-0.5">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span> Absensi Masuk
                                                </p>
                                            <?php else: ?>
                                                <p class="text-xs text-slate-500 font-medium truncate mt-0.5"><?php echo e($activity->book->title); ?></p>
                                            <?php endif; ?>
                                            
                                            <p class="text-[10px] font-bold text-slate-400 mt-2 bg-slate-100 px-2 py-0.5 rounded-md inline-block group-hover:bg-white group-hover:shadow-sm transition-all">
                                                <?php echo e($activity->sort_time->diffForHumans(null, true)); ?> yang lalu
                                            </p>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <div class="text-center py-12 text-slate-400">
                                        <i class="ph-duotone ph-coffee text-4xl mb-2 animate-bounce"></i>
                                        <p class="text-xs font-bold">Belum ada aktivitas hari ini.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // --- 1. COUNT UP ANIMATION LOGIC ---
            const counters = document.querySelectorAll('.count-up');
            
            const animateCounter = (counter) => {
                const target = +counter.getAttribute('data-target');
                const duration = 2000; 
                const frameRate = 16;
                const totalFrames = duration / frameRate;
                const increment = target / totalFrames;
                
                let current = 0;

                const updateCount = () => {
                    current += increment;
                    if (current < target) {
                        counter.innerText = Math.ceil(current).toLocaleString('id-ID');
                        requestAnimationFrame(updateCount);
                    } else {
                        counter.innerText = target.toLocaleString('id-ID');
                    }
                };
                updateCount();
            };
            counters.forEach(counter => animateCounter(counter));


            // --- 2. CHART JS LOGIC ---
            const loanLabels = <?php echo json_encode($chartLabels, 15, 512) ?>;
            const loanData = <?php echo json_encode($chartData, 15, 512) ?>;
            const visitLabels = <?php echo json_encode($visitChartLabels, 15, 512) ?>;
            const visitData = <?php echo json_encode($visitChartData, 15, 512) ?>;

            const canvasElement = document.getElementById('mainChart');
            const ctx = canvasElement.getContext('2d');
            let mainChart;

            function renderChart(type) {
                // FIX: Menghindari error "Canvas is already in use"
                const existingChart = Chart.getChart(canvasElement);
                if (existingChart) {
                    existingChart.destroy();
                }
                
                // Fallback variabel lokal
                if (mainChart) {
                    mainChart.destroy();
                    mainChart = null;
                }
                
                const isVisit = type === 'visits';
                const labels = isVisit ? visitLabels : loanLabels;
                const data = isVisit ? visitData : loanData;
                const label = isVisit ? 'Jumlah Kunjungan' : 'Jumlah Peminjaman';
                
                const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                if (isVisit) {
                    gradient.addColorStop(0, 'rgba(249, 115, 22, 0.5)'); 
                    gradient.addColorStop(1, 'rgba(249, 115, 22, 0.0)');
                } else {
                    gradient.addColorStop(0, 'rgba(30, 64, 175, 0.5)'); 
                    gradient.addColorStop(1, 'rgba(30, 64, 175, 0.0)');
                }

                const borderColor = isVisit ? '#f97316' : '#1e3a8a';

                mainChart = new Chart(ctx, {
                    type: 'line', 
                    data: {
                        labels: labels,
                        datasets: [{
                            label: label,
                            data: data,
                            backgroundColor: gradient,
                            borderColor: borderColor,
                            borderWidth: 3,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: borderColor,
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            fill: true,
                            tension: 0.4 
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: {
                            duration: 2000,
                            easing: 'easeOutQuart'
                        },
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { 
                                beginAtZero: true, 
                                grid: { borderDash: [2, 4], color: '#f1f5f9' }, 
                                ticks: { font: { size: 10, weight: 'bold', family: 'sans-serif' }, color: '#94a3b8' }
                            },
                            x: { 
                                grid: { display: false },
                                ticks: { font: { size: 10, weight: 'bold', family: 'sans-serif' }, color: '#64748b' }
                            }
                        }
                    }
                });
            }

            renderChart('loans');

            window.toggleChart = function(type) {
                const btnLoans = document.getElementById('btn-loans');
                const btnVisits = document.getElementById('btn-visits');
                
                if(type === 'loans') {
                    btnLoans.className = "flex-1 sm:flex-none px-4 py-2 rounded-lg text-xs font-black bg-white shadow-sm text-blue-900 transition-all hover:scale-105 active:scale-95 text-center";
                    btnVisits.className = "flex-1 sm:flex-none px-4 py-2 rounded-lg text-xs font-bold text-slate-500 hover:text-slate-800 transition-all hover:scale-105 active:scale-95 text-center";
                    renderChart('loans');
                } else {
                    btnVisits.className = "flex-1 sm:flex-none px-4 py-2 rounded-lg text-xs font-black bg-white shadow-sm text-slate-800 transition-all hover:scale-105 active:scale-95 text-center";
                    btnLoans.className = "flex-1 sm:flex-none px-4 py-2 rounded-lg text-xs font-bold text-slate-500 hover:text-slate-800 transition-all hover:scale-105 active:scale-95 text-center";
                    renderChart('visits');
                }
            };
        });

        // --- 3. SEARCH POPUP LOGIC ---
        async function searchMemberPopup() {
            const { value: query } = await Swal.fire({
                title: 'Cari Data Siswa',
                input: 'text',
                inputPlaceholder: 'Ketik Nama atau NISN...',
                confirmButtonColor: '#1e3a8a', 
                confirmButtonText: 'Cari',
                showCancelButton: true,
                cancelButtonText: 'Batal',
                background: '#fff',
                customClass: {
                    popup: 'rounded-[2rem]',
                    confirmButton: 'rounded-xl px-6 py-2.5 font-bold',
                    cancelButton: 'rounded-xl px-6 py-2.5 font-bold',
                    input: 'rounded-xl border-slate-200 bg-slate-50 focus:ring-blue-900 focus:border-blue-900'
                }
            });

            if (query) {
                try {
                    Swal.showLoading();
                    const res = await fetch('<?php echo e(route("library.circulation.searchStudent")); ?>', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' },
                        body: JSON.stringify({ q: query })
                    });
                    const data = await res.json();

                    if(data.success) {
                        const student = data.student;
                        Swal.fire({
                            html: `
                                <div class="text-center">
                                    <div class="w-24 h-24 bg-gradient-to-br from-blue-900 to-blue-700 text-white rounded-[2rem] flex items-center justify-center text-4xl font-black mx-auto mb-6 shadow-xl shadow-blue-900/30">
                                        ${student.name.charAt(0)}
                                    </div>
                                    <h3 class="text-2xl font-black text-slate-800 mb-1">${student.name}</h3>
                                    <p class="text-slate-400 font-mono text-sm font-bold mb-6 bg-slate-50 inline-block px-3 py-1 rounded-lg border border-slate-100">${student.student_id}</p>
                                    
                                    <div class="grid grid-cols-2 gap-4 text-left">
                                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1">Kelas</p>
                                            <p class="font-bold text-slate-700 text-lg">${student.school_class ? student.school_class.name : '-'}</p>
                                        </div>
                                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1">Status</p>
                                            ${data.has_overdue ? '<span class="text-rose-600 font-black text-lg flex items-center gap-1"><i class="ph-fill ph-warning-circle"></i> Terblokir</span>' : '<span class="text-emerald-600 font-black text-lg flex items-center gap-1"><i class="ph-fill ph-check-circle"></i> Aktif</span>'}
                                        </div>
                                    </div>
                                    <div class="mt-4 bg-blue-50 p-4 rounded-2xl border border-blue-100">
                                        <div class="flex justify-between items-center">
                                            <p class="text-xs font-bold text-blue-400 uppercase">Buku Dipinjam</p>
                                            <p class="font-black text-blue-900 text-xl">${data.active_loans} Buku</p>
                                        </div>
                                    </div>
                                </div>
                            `,
                            showConfirmButton: false,
                            showCloseButton: true,
                            customClass: { popup: 'rounded-[2.5rem] p-0 overflow-hidden' }
                        });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Tidak Ditemukan', text: 'Data siswa tidak ada dalam database.', customClass: { popup: 'rounded-[2rem]' } });
                    }
                } catch (err) {
                    Swal.fire('Error', 'Terjadi kesalahan sistem.', 'error');
                }
            }
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/library/dashboard.blade.php ENDPATH**/ ?>