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
        /* Animasi standar tetap dipertahankan */
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .delay-100 { animation-delay: 0.1s; } .delay-200 { animation-delay: 0.2s; } .delay-300 { animation-delay: 0.3s; }
        @keyframes float { 0%, 100% { transform: translate(0, 0); } 50% { transform: translate(-10px, -15px); } }
        .animate-float { animation: float 6s ease-in-out infinite; }
        
        /* Shimmer Effect */
        @keyframes shimmer { 0% { background-position: -200% center; } 100% { background-position: 200% center; } }
        .text-shimmer {
            background: linear-gradient(to right, #ffffff 0%, #bfdbfe 20%, #ffffff 40%, #ffffff 100%);
            background-size: 200% auto;
            color: #fff;
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: shimmer 3s linear infinite;
        }
    </style>

    <div class="py-6 sm:py-8 font-sans text-slate-800">
        
        
         <div class="animate-enter max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
            <div class="relative rounded-[2.5rem] bg-gray-900 bg-gradient-to-br from-blue-900 via-blue-800 to-slate-900 p-8 sm:p-10 text-white shadow-2xl shadow-blue-900/40 overflow-hidden border border-white/10 group">
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-80 h-80 bg-blue-500/20 rounded-full blur-3xl pointer-events-none group-hover:bg-blue-500/30 transition-all duration-700 animate-float"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                    <div class="max-w-2xl">
                        <a href="<?php echo e(route('dashboard')); ?>" class="group bg-white/10 hover:bg-white/20 text-white px-5 py-3 rounded-2xl font-bold text-sm backdrop-blur-sm border border-white/10 transition-all flex items-center gap-2 shadow-sm w-fit mb-4 mx-auto xl:mx-0">
                            <i class="ph-bold ph-arrow-left text-sm group-hover:-translate-x-1 transition-transform"></i>
                            <span>Kembali ke Dashboard</span>
                        </a>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-200 text-[10px] font-bold uppercase tracking-widest mb-4 backdrop-blur-sm animate-pulse">
                            <i class="ph-fill ph-books"></i> Sistem Perpustakaan Digital
                        </div>
                        <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-3 text-white drop-shadow-lg">Dashboard Pustaka</h1>
                        <p class="text-blue-100/80 text-sm md:text-base font-medium leading-relaxed max-w-lg">
                            Pusat kontrol manajemen buku, sirkulasi peminjaman, dan statistik kunjungan siswa secara real-time.
                        </p>
                    </div>
                    
                    <div class="w-full md:w-auto grid grid-cols-2 gap-4">
                        <div class="bg-white/10 backdrop-blur-md px-5 py-5 rounded-2xl border border-white/20 hover:bg-white/15 transition-all hover:scale-105 duration-300">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-blue-300">Koleksi Buku</span>
                            <span class="block text-3xl font-black text-shimmer count-up" data-target="<?php echo e($totalBooks); ?>">0</span>
                        </div>
                        <div class="bg-orange-500/20 backdrop-blur-md px-5 py-5 rounded-2xl border border-orange-400/30 hover:bg-orange-500/30 transition-all hover:scale-105 duration-300">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-orange-300">Pengunjung</span>
                            <span class="block text-3xl font-black text-shimmer count-up" data-target="<?php echo e($todayVisits); ?>">0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- KOLOM UTAMA (KIRI - 2/3) -->
                <div class="lg:col-span-2 space-y-8">

                    <!-- Menu Akses Cepat (EXISTING) -->
                    <div class="animate-enter delay-100 bg-white p-6 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden">
                        <h2 class="text-lg font-black text-slate-800 mb-6 flex items-center gap-2">
                            <i class="ph-fill ph-lightning text-yellow-500 text-xl"></i> Akses Cepat
                        </h2>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <a href="<?php echo e(route('library.circulation.index')); ?>" class="flex flex-col items-center justify-center p-4 bg-blue-50/50 hover:bg-blue-600 border border-blue-100 hover:border-blue-500 rounded-[2rem] transition-all duration-300 hover:shadow-lg group">
                                <i class="ph-duotone ph-arrows-left-right text-3xl text-blue-600 group-hover:text-white mb-2 transition-colors"></i>
                                <span class="font-bold text-slate-600 text-xs group-hover:text-white transition-colors">Sirkulasi</span>
                            </a>
                            <button onclick="searchMemberPopup()" class="flex flex-col items-center justify-center p-4 bg-purple-50/50 hover:bg-purple-600 border border-purple-100 hover:border-purple-500 rounded-[2rem] transition-all duration-300 hover:shadow-lg group">
                                <i class="ph-duotone ph-user-focus text-3xl text-purple-600 group-hover:text-white mb-2 transition-colors"></i>
                                <span class="font-bold text-slate-600 text-xs group-hover:text-white transition-colors">Cari Siswa</span>
                            </button>
                            <a href="<?php echo e(route('library.books.create')); ?>" class="flex flex-col items-center justify-center p-4 bg-emerald-50/50 hover:bg-emerald-600 border border-emerald-100 hover:border-emerald-500 rounded-[2rem] transition-all duration-300 hover:shadow-lg group">
                                <i class="ph-duotone ph-plus-circle text-3xl text-emerald-600 group-hover:text-white mb-2 transition-colors"></i>
                                <span class="font-bold text-slate-600 text-xs group-hover:text-white transition-colors">Input Buku</span>
                            </a>
                            <a href="<?php echo e(route('library.kiosk.index')); ?>" target="_blank" class="flex flex-col items-center justify-center p-4 bg-orange-50/50 hover:bg-orange-600 border border-orange-100 hover:border-orange-500 rounded-[2rem] transition-all duration-300 hover:shadow-lg group">
                                <i class="ph-duotone ph-desktop text-3xl text-orange-600 group-hover:text-white mb-2 transition-colors"></i>
                                <span class="font-bold text-slate-600 text-xs group-hover:text-white transition-colors">Mode Kiosk</span>
                            </a>
                        </div>   
                    </div>     

                    <!-- E-Book Stats (EXISTING) -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-enter delay-100">
                        <div class="bg-gradient-to-br from-indigo-600 to-violet-600 rounded-[2rem] p-6 text-white shadow-xl shadow-indigo-600/20 relative overflow-hidden">
                            <div class="relative z-10">
                                <div class="flex items-center gap-2 mb-2 opacity-80">
                                    <i class="ph-fill ph-read-cv-logo"></i>
                                    <span class="text-[10px] font-bold uppercase tracking-wider">Literasi Digital</span>
                                </div>
                                <h2 class="text-4xl font-black mb-1 count-up" data-target="<?php echo e($ebookReadsThisMonth ?? 0); ?>">0</h2>
                                <p class="text-xs text-indigo-200">Total baca E-Book bulan ini</p>
                            </div>
                        </div>

                        <div class="lg:col-span-2 bg-white rounded-[2rem] border border-slate-100 shadow-sm p-6">
                            <h3 class="font-bold text-slate-800 text-sm mb-3 flex items-center gap-2">
                                <i class="ph-fill ph-crown text-amber-500"></i> Top E-Book
                            </h3>
                            <?php if(isset($popularEbooks) && count($popularEbooks) > 0): ?>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    <?php $__currentLoopData = $popularEbooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="flex items-center gap-3 p-2 rounded-xl hover:bg-slate-50 transition-colors cursor-default">
                                            <div class="w-5 h-5 flex items-center justify-center text-xs font-black bg-slate-100 rounded text-slate-500"><?php echo e($index + 1); ?></div>
                                            <div class="flex-1 min-w-0">
                                                <h4 class="font-bold text-slate-700 text-xs truncate"><?php echo e($book->title); ?></h4>
                                                <span class="text-[10px] text-slate-400"><?php echo e($book->ebook_reads_count); ?>x Baca</span>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php else: ?>
                                <p class="text-xs text-slate-400 text-center py-4">Belum ada data bacaan digital.</p>
                            <?php endif; ?>
                        </div>
                    </div>  

                    <!-- Grafik Utama & Grafik Jam Sibuk -->
                    <div class="grid grid-cols-1 gap-6 animate-enter delay-200">
                        
                        <!-- Grafik Tren Aktivitas (EXISTING) -->
                        <div class="bg-white p-6 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100">
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <h2 class="text-lg font-black text-slate-800 flex items-center gap-2">
                                        <i class="ph-fill ph-chart-line-up text-blue-600"></i> Tren Aktivitas
                                    </h2>
                                    <p class="text-xs text-slate-400 font-bold mt-1 uppercase">7 Hari Terakhir</p>
                                </div>
                                <div class="flex gap-2">
                                    <button onclick="downloadChart('mainChart')" class="p-2 text-slate-400 hover:text-blue-600 transition" title="Download Gambar"><i class="ph-bold ph-download-simple"></i></button>
                                </div>
                            </div>
                            <div class="h-64 w-full relative">
                                <canvas id="mainChart"></canvas>
                            </div>
                        </div>

                        <!-- [BARU] Grafik Jam Sibuk (ADDED FEATURE) -->
                        <div class="bg-white p-6 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100">
                            <h2 class="text-lg font-black text-slate-800 flex items-center gap-2 mb-4">
                                <i class="ph-fill ph-clock text-orange-500"></i> Analitik Jam Sibuk
                            </h2>
                            <div class="h-48 w-full relative">
                                <canvas id="busyHoursChart"></canvas>
                            </div>
                        </div>

                    </div>            

                </div> 

                <!-- SIDEBAR KANAN -->
                <div class="lg:col-span-1 space-y-8">
                     
                     <!-- Status Sirkulasi (EXISTING) -->
                     <div class="animate-enter delay-300 bg-white p-6 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100">
                        <h3 class="font-black text-slate-800 text-lg mb-4">Status Sirkulasi</h3>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="p-4 bg-indigo-50 rounded-2xl text-center border border-indigo-100">
                                <i class="ph-duotone ph-book-open-text text-2xl text-indigo-600 mb-1 block"></i>
                                <p class="text-2xl font-black text-slate-800 count-up" data-target="<?php echo e($borrowedBooks); ?>">0</p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase">Dipinjam</p>
                            </div>
                            <div class="p-4 bg-rose-50 rounded-2xl text-center border border-rose-100">
                                <i class="ph-duotone ph-warning-circle text-2xl text-rose-600 mb-1 block"></i>
                                <p class="text-2xl font-black text-slate-800 count-up" data-target="<?php echo e($overdueBooks); ?>">0</p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase">Terlambat</p>
                            </div>
                        </div>
                    </div>

                    <!-- [BARU] Widget Perlu Perhatian (Stok Habis) -->
                    <div class="animate-enter delay-300 bg-white p-6 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100">
                        <h3 class="font-black text-slate-800 text-lg mb-4 flex items-center gap-2">
                            <i class="ph-fill ph-warning text-amber-500"></i> Stok Menipis
                        </h3>
                        <?php if(isset($attentionBooks) && $attentionBooks->count() > 0): ?>
                            <div class="space-y-3">
                                <?php $__currentLoopData = $attentionBooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center gap-3 p-3 bg-amber-50 rounded-xl border border-amber-100">
                                    <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center text-amber-500 shadow-sm">
                                        <i class="ph-bold ph-book"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-bold text-slate-800 truncate"><?php echo e($book->title); ?></p>
                                        <p class="text-[10px] text-amber-600 font-bold">Stok: <?php echo e($book->stock); ?></p>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4 text-slate-400 text-xs">Semua stok buku aman.</div>
                        <?php endif; ?>
                    </div>

                    <!-- Log Aktivitas (EXISTING) -->
                    <div class="animate-enter delay-400 bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 flex flex-col h-[500px]">
                        <div class="p-6 border-b border-slate-50 flex justify-between items-center">
                            <h3 class="font-black text-slate-800 text-lg flex items-center gap-2">
                                <i class="ph-fill ph-clock-counter-clockwise text-blue-900"></i> Log Aktivitas
                            </h3>
                            <span class="text-[10px] bg-green-100 text-green-600 px-2 py-0.5 rounded-full font-bold animate-pulse">Live</span>
                        </div>
                        <div class="overflow-y-auto flex-1 p-0 custom-scrollbar">
                            <div class="divide-y divide-slate-50">
                                <?php $__empty_1 = true; $__currentLoopData = $recentActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <div class="p-4 hover:bg-slate-50 transition-colors flex gap-3 items-start">
                                        <div class="shrink-0 mt-1">
                                            <?php if($activity->type == 'visit'): ?>
                                                <div class="w-8 h-8 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center text-sm">
                                                    <i class="ph-duotone ph-door-open"></i>
                                                </div>
                                            <?php else: ?>
                                                <div class="w-8 h-8 rounded-xl flex items-center justify-center text-sm <?php echo e($activity->status == 'returned' ? 'bg-emerald-100 text-emerald-600' : 'bg-blue-100 text-blue-600'); ?>">
                                                    <i class="<?php echo e($activity->status == 'returned' ? 'ph-duotone ph-arrow-u-down-left' : 'ph-duotone ph-arrow-u-right-up'); ?>"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-bold text-slate-800 truncate"><?php echo e($activity->student->name ?? 'Siswa'); ?></p>
                                            <p class="text-[10px] text-slate-500 truncate"><?php echo e($activity->type == 'visit' ? 'Absensi Masuk' : ($activity->book->title ?? '-')); ?></p>
                                            <p class="text-[9px] font-bold text-slate-400 mt-1"><?php echo e($activity->updated_at->diffForHumans()); ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <div class="text-center py-10 text-slate-400">
                                        <p class="text-xs">Belum ada aktivitas.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div> 

        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 space-y-8 animate-enter delay-300">
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
                    <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-chart-pie-slice text-purple-500"></i> Distribusi Kelas
                    </h3>
                    <div class="h-64 w-full">
                        <canvas id="classChart"></canvas>
                    </div>
                </div>

                
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
                    <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-trophy text-yellow-500"></i> Buku Fisik Terpopuler
                    </h3>
                    <div class="space-y-3">
                        <?php $__empty_1 = true; $__currentLoopData = $popularBooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="flex items-center gap-4 p-2 hover:bg-slate-50 rounded-xl transition-colors">
                                <span class="font-black text-slate-300 text-lg w-6 text-center"><?php echo e($index + 1); ?></span>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-bold text-slate-800 truncate" title="<?php echo e($book->title); ?>"><?php echo e($book->title); ?></h4>
                                    <p class="text-xs text-slate-500"><?php echo e($book->borrowings_count); ?>x Dipinjam</p>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="flex flex-col items-center justify-center h-40 text-slate-400">
                                <i class="ph-duotone ph-books text-3xl mb-2 opacity-50"></i>
                                <p class="text-xs font-bold">Belum ada data peminjaman.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>

            
            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-8 text-center flex flex-col justify-center">
                <i class="ph-duotone ph-barcode text-5xl text-blue-200 mb-4 block mx-auto"></i>
                <h3 class="text-xl font-black text-slate-800 mb-2">Cek Status Siswa</h3>
                <p class="text-slate-500 mb-6 text-sm max-w-xs mx-auto">Scan kartu atau masukkan NISN untuk cek peminjaman aktif.</p>
                
                <div class="w-full relative group max-w-xl mx-auto">
                    <input type="text" id="studentSearchInput" placeholder="Scan Barcode / NISN..." 
                        class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-100 focus:border-blue-500 font-mono font-bold text-slate-800 transition-all text-center">
                    <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                    <div id="loadingIndicator" class="absolute right-4 top-1/2 -translate-y-1/2 hidden">
                        <i class="ph-bold ph-spinner animate-spin text-blue-500"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // 1. COUNT UP ANIMATION
            const counters = document.querySelectorAll('.count-up');
            counters.forEach(counter => {
                const target = +counter.getAttribute('data-target');
                let current = 0;
                const increment = target / 50;
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
            });

            // 2. MAIN CHART LOGIC (Time Series)
            const ctx = document.getElementById('mainChart').getContext('2d');
            const bgGradient = ctx.createLinearGradient(0, 0, 0, 300);
            bgGradient.addColorStop(0, 'rgba(37, 99, 235, 0.2)');
            bgGradient.addColorStop(1, 'rgba(255, 255, 255, 0)');

            new Chart(ctx, {
                type: 'line', 
                data: {
                    labels: <?php echo json_encode($visitChartLabels ?? [], 15, 512) ?>,
                    datasets: [
                        {
                            label: 'Kunjungan',
                            data: <?php echo json_encode($visitChartData ?? [], 15, 512) ?>,
                            backgroundColor: bgGradient,
                            borderColor: '#2563eb',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 0,
                            pointHoverRadius: 6
                        },
                        {
                            label: 'Peminjaman',
                            data: <?php echo json_encode($loanChartData ?? [], 15, 512) ?>,
                            borderColor: '#8b5cf6',
                            borderWidth: 2,
                            borderDash: [5, 5],
                            fill: false,
                            tension: 0.4,
                            pointRadius: 0
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: true, position: 'bottom' } },
                    scales: {
                        y: { beginAtZero: true, grid: { borderDash: [2, 4] } },
                        x: { grid: { display: false } }
                    }
                }
            });

            // 3. [EXISTING] CLASS CHART LOGIC
            const classLabels = <?php echo json_encode($classChartLabels ?? [], 15, 512) ?>;
            const classData = <?php echo json_encode($classChartData ?? [], 15, 512) ?>;
            
            if(document.getElementById('classChart')) {
                const ctxClass = document.getElementById('classChart').getContext('2d');
                new Chart(ctxClass, {
                    type: 'bar',
                    data: {
                        labels: classLabels,
                        datasets: [{
                            label: 'Peminjaman',
                            data: classData,
                            backgroundColor: '#8b5cf6',
                            borderRadius: 6,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, grid: { display: false } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }

            // 4. [BARU] BUSY HOURS CHART
            const busyHoursLabels = <?php echo json_encode($busyHoursLabels ?? [], 15, 512) ?>;
            const busyHoursData = <?php echo json_encode($busyHoursData ?? [], 15, 512) ?>;

            if(document.getElementById('busyHoursChart')) {
                const ctxBusy = document.getElementById('busyHoursChart').getContext('2d');
                new Chart(ctxBusy, {
                    type: 'bar',
                    data: {
                        labels: busyHoursLabels,
                        datasets: [{
                            label: 'Kunjungan',
                            data: busyHoursData,
                            backgroundColor: '#f97316',
                            borderRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { display: false },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }

            // 5. Download Chart Function (ADDED)
            window.downloadChart = function(id) {
                const link = document.createElement('a');
                link.download = 'chart-report.png';
                link.href = document.getElementById(id).toDataURL();
                link.click();
            }

            // 6. KIOSK SEARCH LOGIC (EXISTING)
            const searchInput = document.getElementById('studentSearchInput');
            const loadingIndicator = document.getElementById('loadingIndicator');
            let timeout = null;

            async function performSearch(query) {
                if(!query || query.length < 3) return;
                loadingIndicator.classList.remove('hidden');
                try {
                    const res = await fetch('<?php echo e(route("library.dashboard.checkStudent")); ?>', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' },
                        body: JSON.stringify({ q: query })
                    });
                    const data = await res.json();
                    loadingIndicator.classList.add('hidden');
                    if(data.success) {
                        const s = data.student;
                        Swal.fire({
                            title: s.name,
                            text: `${s.student_id} - ${s.school_class ? s.school_class.name : ''}`,
                            icon: data.has_overdue ? 'warning' : 'success',
                            html: `
                                <div class="mt-2 p-3 bg-slate-50 rounded-lg text-left text-sm">
                                    <div class="flex justify-between mb-1">
                                        <span>Status:</span>
                                        <span class="font-bold ${data.has_overdue ? 'text-red-500' : 'text-green-500'}">
                                            ${data.has_overdue ? 'Terblokir (Denda)' : 'Aktif'}
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Buku Dipinjam:</span>
                                        <span class="font-bold">${data.active_loans} Buku</span>
                                    </div>
                                </div>
                            `,
                            confirmButtonColor: '#1e3a8a',
                            timer: 5000
                        });
                        searchInput.value = '';
                    } else {
                        searchInput.classList.add('ring-2', 'ring-red-200', 'border-red-500');
                        setTimeout(() => searchInput.classList.remove('ring-2', 'ring-red-200', 'border-red-500'), 1000);
                    }
                } catch (err) {
                    console.error(err);
                    loadingIndicator.classList.add('hidden');
                }
            }

            if(searchInput) {
                searchInput.addEventListener('input', function (e) {
                    clearTimeout(timeout);
                    const query = e.target.value;
                    if(!query) return; // PERBAIKAN: Menghapus syarat panjang minimal 3 karakter
                    timeout = setTimeout(() => { performSearch(query); }, 800);
                });
                searchInput.addEventListener('keypress', function (e) {
                    if (e.key === 'Enter') {
                        e.preventDefault(); clearTimeout(timeout); performSearch(e.target.value);
                    }
                });
            }
        });

        // Global function untuk Popup Search (Header)
        window.searchMemberPopup = async function() {
            const { value: query } = await Swal.fire({
                title: 'Cari Siswa',
                input: 'text',
                inputPlaceholder: 'Nama atau NISN...',
                confirmButtonColor: '#1e3a8a',
                showCancelButton: true
            });
            if (query) {
                const searchInput = document.getElementById('studentSearchInput');
                if(searchInput) {
                    searchInput.value = query;
                    searchInput.dispatchEvent(new Event('input'));
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\library\dashboard.blade.php ENDPATH**/ ?>