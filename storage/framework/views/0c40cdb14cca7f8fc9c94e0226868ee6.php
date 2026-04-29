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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    
    <style>
        /* Animasi standar tetap dipertahankan */
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .delay-100 { animation-delay: 0.1s; } .delay-200 { animation-delay: 0.2s; } .delay-300 { animation-delay: 0.3s; }
        @keyframes float { 0%, 100% { transform: translate(0, 0); } 50% { transform: translate(-10px, -15px); } }
        .animate-float { animation: float 6s ease-in-out infinite; }
        
        /* Shimmer Effect Dark (Diadaptasi untuk latar terang Elevate) */
        @keyframes shimmer { 0% { background-position: -200% center; } 100% { background-position: 200% center; } }
        .text-shimmer {
            background: linear-gradient(to right, #2c3f61 0%, #0d52a1 20%, #2c3f61 40%, #2c3f61 100%);
            background-size: 200% auto;
            color: #2c3f61;
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: shimmer 3s linear infinite;
        }
    </style>

    <div class="py-6 sm:py-8 font-sans text-elevate-dark relative overflow-hidden">
        
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>
        
        
         <div class="animate-enter max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 relative z-10">
            <div class="relative rounded-[2.5rem] bg-elevate-gradient-main p-8 sm:p-10 text-elevate-dark shadow-2xl shadow-elevate-accent/20 overflow-hidden border border-white/60 group">
                <div class="absolute inset-0 opacity-[0.03] bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none mix-blend-overlay"></div>
                <div class="absolute -top-24 -right-24 w-80 h-80 bg-white/40 rounded-full blur-3xl pointer-events-none group-hover:bg-white/60 transition-all duration-700 animate-float"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                    <div class="max-w-2xl">
                        <a href="<?php echo e(route('dashboard')); ?>" class="group bg-white/50 hover:bg-white/80 text-elevate-primary px-5 py-3 rounded-2xl font-bold text-sm backdrop-blur-sm border border-white/60 transition-all flex items-center gap-2 shadow-sm w-fit mb-4 mx-auto xl:mx-0">
                            <i class="ph-bold ph-arrow-left text-sm group-hover:-translate-x-1 transition-transform"></i>
                            <span>Kembali ke Dashboard Utama</span>
                        </a>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-elevate-soft/80 border border-elevate-accent/30 text-elevate-primary text-[10px] font-bold uppercase tracking-widest mb-4 backdrop-blur-sm shadow-sm animate-pulse">
                            <i class="ph-fill ph-books"></i> Sistem Perpustakaan Digital
                        </div>
                        <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-3 text-elevate-dark">Dashboard Pustaka</h1>
                        <p class="text-elevate-dark/80 text-sm md:text-base font-medium leading-relaxed max-w-lg">
                            Pusat kontrol manajemen buku, sirkulasi peminjaman, dan statistik kunjungan siswa secara real-time.
                        </p>
                    </div>
                    
                    <div class="w-full md:w-auto grid grid-cols-2 gap-4">
                        <div class="bg-white/60 backdrop-blur-md px-5 py-5 rounded-2xl border border-white/80 shadow-sm hover:bg-white transition-all hover:scale-105 duration-300">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-elevate-primary">Koleksi Buku</span>
                            <span class="block text-3xl font-black text-shimmer count-up" data-target="<?php echo e($totalBooks); ?>">0</span>
                        </div>
                        <div class="bg-elevate-peach/20 backdrop-blur-md px-5 py-5 rounded-2xl border border-elevate-peach/30 shadow-sm hover:bg-elevate-peach/30 transition-all hover:scale-105 duration-300">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-elevate-peach-dark">Pengunjung</span>
                            <span class="block text-3xl font-black text-elevate-dark count-up" data-target="<?php echo e($todayVisits); ?>">0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- KOLOM UTAMA (KIRI - 2/3) -->
                <div class="lg:col-span-2 space-y-8">

                    <!-- Menu Akses Cepat -->
                    <div class="animate-enter delay-100 bg-white p-6 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden">
                        <h2 class="text-lg font-black text-elevate-dark mb-6 flex items-center gap-2">
                            <i class="ph-fill ph-lightning text-amber-500 text-xl"></i> Akses Cepat
                        </h2>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <a href="<?php echo e(route('library.circulation.index')); ?>" class="flex flex-col items-center justify-center p-4 bg-elevate-soft hover:bg-elevate-primary border border-slate-100 hover:border-elevate-primary rounded-[2rem] transition-all duration-300 hover:shadow-lg group">
                                <i class="ph-duotone ph-arrows-left-right text-3xl text-elevate-primary group-hover:text-white mb-2 transition-colors"></i>
                                <span class="font-bold text-elevate-dark/70 text-xs group-hover:text-white transition-colors">Sirkulasi</span>
                            </a>
                            <button onclick="searchMemberPopup()" class="flex flex-col items-center justify-center p-4 bg-elevate-soft hover:bg-elevate-primary border border-slate-100 hover:border-elevate-primary rounded-[2rem] transition-all duration-300 hover:shadow-lg group">
                                <i class="ph-duotone ph-user-focus text-3xl text-elevate-primary group-hover:text-white mb-2 transition-colors"></i>
                                <span class="font-bold text-elevate-dark/70 text-xs group-hover:text-white transition-colors">Cari Siswa</span>
                            </button>
                            <a href="<?php echo e(route('library.books.create')); ?>" class="flex flex-col items-center justify-center p-4 bg-elevate-soft hover:bg-elevate-primary border border-slate-100 hover:border-elevate-primary rounded-[2rem] transition-all duration-300 hover:shadow-lg group">
                                <i class="ph-duotone ph-plus-circle text-3xl text-elevate-primary group-hover:text-white mb-2 transition-colors"></i>
                                <span class="font-bold text-elevate-dark/70 text-xs group-hover:text-white transition-colors">Input Buku</span>
                            </a>
                            <a href="<?php echo e(route('library.kiosk.index')); ?>" target="_blank" class="flex flex-col items-center justify-center p-4 bg-elevate-peach-light/40 hover:bg-elevate-peach border border-slate-100 hover:border-elevate-peach rounded-[2rem] transition-all duration-300 hover:shadow-lg group">
                                <i class="ph-duotone ph-desktop text-3xl text-elevate-peach-dark group-hover:text-white mb-2 transition-colors"></i>
                                <span class="font-bold text-elevate-dark/70 text-xs group-hover:text-white transition-colors">Mode Kiosk</span>
                            </a>
                        </div>   
                    </div>     

                    <!-- E-Book Stats -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-enter delay-100">
                        <div class="bg-elevate-dark rounded-[2rem] p-6 text-white shadow-xl shadow-elevate-dark/20 relative overflow-hidden">
                            <div class="absolute -right-4 -bottom-4 text-white/5 text-[8rem] pointer-events-none">
                                <i class="ph-fill ph-book-bookmark"></i>
                            </div>
                            <div class="relative z-10">
                                <div class="flex items-center gap-2 mb-2 text-elevate-accent">
                                    <i class="ph-fill ph-read-cv-logo"></i>
                                    <span class="text-[10px] font-bold uppercase tracking-wider">Literasi Digital</span>
                                </div>
                                <h2 class="text-4xl font-black mb-1 count-up" data-target="<?php echo e($ebookReadsThisMonth ?? 0); ?>">0</h2>
                                <p class="text-xs text-white/70">Total baca E-Book bulan ini</p>
                            </div>
                        </div>

                        <div class="lg:col-span-2 bg-elevate-gradient-card rounded-[2rem] border border-slate-200 shadow-sm p-6">
                            <h3 class="font-bold text-elevate-dark text-sm mb-3 flex items-center gap-2">
                                <i class="ph-fill ph-crown text-amber-500"></i> Top E-Book
                            </h3>
                            <?php if(isset($popularEbooks) && count($popularEbooks) > 0): ?>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    <?php $__currentLoopData = $popularEbooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="flex items-center gap-3 p-2 rounded-xl hover:bg-white transition-colors cursor-default border border-transparent hover:border-slate-100">
                                            <div class="w-5 h-5 flex items-center justify-center text-xs font-black bg-elevate-soft rounded text-elevate-primary"><?php echo e($index + 1); ?></div>
                                            <div class="flex-1 min-w-0">
                                                <h4 class="font-bold text-elevate-dark text-xs truncate"><?php echo e($book->title); ?></h4>
                                                <span class="text-[10px] text-elevate-dark/60"><?php echo e($book->ebook_reads_count); ?>x Baca</span>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php else: ?>
                                <p class="text-xs text-elevate-dark/50 text-center py-4">Belum ada data bacaan digital.</p>
                            <?php endif; ?>
                        </div>
                    </div>  

                    <!-- Grafik Utama & Grafik Jam Sibuk -->
                    <div class="grid grid-cols-1 gap-6 animate-enter delay-200">
                        
                        <!-- Grafik Tren Aktivitas -->
                        <div class="bg-white p-6 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100">
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <h2 class="text-lg font-black text-elevate-dark flex items-center gap-2">
                                        <i class="ph-fill ph-chart-line-up text-elevate-primary"></i> Tren Aktivitas
                                    </h2>
                                    <p class="text-xs text-elevate-dark/50 font-bold mt-1 uppercase">7 Hari Terakhir</p>
                                </div>
                                <div class="flex gap-2">
                                    <button onclick="downloadChart('mainChart')" class="p-2 text-slate-400 hover:text-elevate-primary transition" title="Download Gambar"><i class="ph-bold ph-download-simple"></i></button>
                                </div>
                            </div>
                            <div class="h-64 w-full relative">
                                <canvas id="mainChart"></canvas>
                            </div>
                        </div>

                        <!-- Grafik Jam Sibuk -->
                        <div class="bg-white p-6 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100">
                            <h2 class="text-lg font-black text-elevate-dark flex items-center gap-2 mb-4">
                                <i class="ph-fill ph-clock text-elevate-peach-dark"></i> Analitik Jam Sibuk
                            </h2>
                            <div class="h-48 w-full relative">
                                <canvas id="busyHoursChart"></canvas>
                            </div>
                        </div>

                    </div>            

                </div> 

                <!-- SIDEBAR KANAN -->
                <div class="lg:col-span-1 space-y-8">
                     
                     <!-- Status Sirkulasi -->
                     <div class="animate-enter delay-300 bg-white p-6 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100">
                        <h3 class="font-black text-elevate-dark text-lg mb-4">Status Sirkulasi</h3>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="p-4 bg-elevate-soft rounded-2xl text-center border border-slate-200">
                                <i class="ph-duotone ph-book-open-text text-2xl text-elevate-primary mb-1 block"></i>
                                <p class="text-2xl font-black text-elevate-dark count-up" data-target="<?php echo e($borrowedBooks); ?>">0</p>
                                <p class="text-[10px] font-bold text-elevate-dark/60 uppercase">Dipinjam</p>
                            </div>
                            <div class="p-4 bg-rose-50 rounded-2xl text-center border border-rose-100">
                                <i class="ph-duotone ph-warning-circle text-2xl text-rose-500 mb-1 block"></i>
                                <p class="text-2xl font-black text-elevate-dark count-up" data-target="<?php echo e($overdueBooks); ?>">0</p>
                                <p class="text-[10px] font-bold text-elevate-dark/60 uppercase">Terlambat</p>
                            </div>
                        </div>
                    </div>

                    <!-- Widget Perlu Perhatian (Stok Habis) -->
                    <div class="animate-enter delay-300 bg-white p-6 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100">
                        <h3 class="font-black text-elevate-dark text-lg mb-4 flex items-center gap-2">
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
                                        <p class="text-xs font-bold text-elevate-dark truncate"><?php echo e($book->title); ?></p>
                                        <p class="text-[10px] text-amber-600 font-bold">Stok: <?php echo e($book->stock); ?></p>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4 text-elevate-dark/50 text-xs">Semua stok buku aman.</div>
                        <?php endif; ?>
                    </div>

                    <!-- Log Aktivitas -->
                    <div class="animate-enter delay-400 bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 flex flex-col h-[500px]">
                        <div class="p-6 border-b border-slate-50 flex justify-between items-center">
                            <h3 class="font-black text-elevate-dark text-lg flex items-center gap-2">
                                <i class="ph-fill ph-clock-counter-clockwise text-elevate-primary"></i> Log Aktivitas
                            </h3>
                            <span class="text-[10px] bg-emerald-100 text-emerald-600 px-2 py-0.5 rounded-full font-bold animate-pulse border border-emerald-200">Live</span>
                        </div>
                        <div class="overflow-y-auto flex-1 p-0 custom-scrollbar">
                            <div class="divide-y divide-slate-50">
                                <?php $__empty_1 = true; $__currentLoopData = $recentActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <div class="p-4 hover:bg-elevate-soft/50 transition-colors flex gap-3 items-start">
                                        <div class="shrink-0 mt-1">
                                            <?php if($activity->type == 'visit'): ?>
                                                <div class="w-8 h-8 rounded-xl bg-elevate-peach/20 text-elevate-peach-dark flex items-center justify-center text-sm border border-elevate-peach/30">
                                                    <i class="ph-duotone ph-door-open"></i>
                                                </div>
                                            <?php else: ?>
                                                <div class="w-8 h-8 rounded-xl flex items-center justify-center text-sm border <?php echo e($activity->status == 'returned' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-elevate-soft text-elevate-primary border-slate-200'); ?>">
                                                    <i class="<?php echo e($activity->status == 'returned' ? 'ph-duotone ph-arrow-u-down-left' : 'ph-duotone ph-arrow-u-right-up'); ?>"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-bold text-elevate-dark truncate"><?php echo e($activity->student->name ?? 'Siswa'); ?></p>
                                            <p class="text-[10px] text-elevate-dark/60 truncate"><?php echo e($activity->type == 'visit' ? 'Absensi Masuk' : ($activity->book->title ?? '-')); ?></p>
                                            <p class="text-[9px] font-bold text-elevate-dark/40 mt-1"><?php echo e($activity->updated_at->diffForHumans()); ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <div class="text-center py-10 text-elevate-dark/50">
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
                    <h3 class="font-bold text-elevate-dark mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-chart-pie-slice text-elevate-accent"></i> Distribusi Kelas
                    </h3>
                    <div class="h-64 w-full">
                        <canvas id="classChart"></canvas>
                    </div>
                </div>

                
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
                    <h3 class="font-bold text-elevate-dark mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-trophy text-amber-500"></i> Buku Fisik Terpopuler
                    </h3>
                    <div class="space-y-3">
                        <?php $__empty_1 = true; $__currentLoopData = $popularBooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="flex items-center gap-4 p-2 hover:bg-elevate-soft rounded-xl transition-colors">
                                <span class="font-black text-slate-300 text-lg w-6 text-center"><?php echo e($index + 1); ?></span>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-bold text-elevate-dark truncate" title="<?php echo e($book->title); ?>"><?php echo e($book->title); ?></h4>
                                    <p class="text-xs text-elevate-dark/60"><?php echo e($book->borrowings_count); ?>x Dipinjam</p>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="flex flex-col items-center justify-center h-40 text-elevate-dark/40">
                                <i class="ph-duotone ph-books text-3xl mb-2 opacity-50"></i>
                                <p class="text-xs font-bold">Belum ada data peminjaman.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>

            
            <div class="bg-elevate-gradient-card rounded-[2rem] shadow-sm border border-slate-200 p-8 text-center flex flex-col justify-center relative overflow-hidden">
                <div class="absolute inset-0 opacity-[0.02] bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none mix-blend-overlay"></div>
                <i class="ph-duotone ph-barcode text-5xl text-elevate-primary mb-4 block mx-auto relative z-10"></i>
                <h3 class="text-xl font-black text-elevate-dark mb-2 relative z-10">Cek Status Siswa</h3>
                <p class="text-elevate-dark/60 mb-6 text-sm max-w-xs mx-auto relative z-10">Scan kartu atau masukkan NISN untuk cek peminjaman aktif.</p>
                
                <div class="w-full relative group max-w-xl mx-auto z-10">
                    <input type="text" id="studentSearchInput" placeholder="Scan Barcode / NISN..." 
                        class="w-full pl-12 pr-4 py-3 bg-white border border-slate-200 rounded-2xl focus:ring-2 focus:ring-elevate-accent/30 focus:border-elevate-accent font-mono font-bold text-elevate-dark transition-all text-center shadow-sm">
                    <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-elevate-primary transition-colors"></i>
                    <div id="loadingIndicator" class="absolute right-4 top-1/2 -translate-y-1/2 hidden">
                        <i class="ph-bold ph-spinner animate-spin text-elevate-primary"></i>
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
                if (target > 0) updateCount(); // Prevent zero looping
            });

            // 2. MAIN CHART LOGIC (Time Series)
            const ctx = document.getElementById('mainChart').getContext('2d');
            const bgGradient = ctx.createLinearGradient(0, 0, 0, 300);
            bgGradient.addColorStop(0, 'rgba(13, 82, 161, 0.2)'); // elevate-primary Opacity
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
                            borderColor: '#0d52a1', // elevate-primary
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 0,
                            pointHoverRadius: 6
                        },
                        {
                            label: 'Peminjaman',
                            data: <?php echo json_encode($loanChartData ?? [], 15, 512) ?>,
                            borderColor: '#56bbf1', // elevate-accent
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

            // 3. CLASS CHART LOGIC
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
                            backgroundColor: '#2c3f61', // elevate-dark
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

            // 4. BUSY HOURS CHART
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
                            backgroundColor: '#f9a282', // elevate-peach
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

            // 5. Download Chart Function
            window.downloadChart = function(id) {
                const link = document.createElement('a');
                link.download = 'chart-report.png';
                link.href = document.getElementById(id).toDataURL();
                link.click();
            }

            // 6. KIOSK SEARCH LOGIC
            const searchInput = document.getElementById('studentSearchInput');
            const loadingIndicator = document.getElementById('loadingIndicator');
            let timeout = null;

            async function performSearch(query) {
                if(!query) return;
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
                                <div class="mt-2 p-3 bg-slate-50 rounded-lg text-left text-sm border border-slate-200">
                                    <div class="flex justify-between mb-1">
                                        <span class="text-elevate-dark">Status:</span>
                                        <span class="font-bold ${data.has_overdue ? 'text-rose-500' : 'text-emerald-500'}">
                                            ${data.has_overdue ? 'Terblokir (Denda)' : 'Aktif'}
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-elevate-dark">Buku Dipinjam:</span>
                                        <span class="font-bold text-elevate-primary">${data.active_loans} Buku</span>
                                    </div>
                                </div>
                            `,
                            confirmButtonColor: '#2c3f61', // elevate-dark
                            timer: 5000,
                            customClass: { popup: 'rounded-[2rem]' }
                        });
                        searchInput.value = '';
                    } else {
                        searchInput.classList.add('ring-2', 'ring-rose-200', 'border-rose-500');
                        setTimeout(() => searchInput.classList.remove('ring-2', 'ring-rose-200', 'border-rose-500'), 1000);
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
                    if(!query) return;
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
                confirmButtonColor: '#2c3f61', // elevate-dark
                showCancelButton: true,
                customClass: { popup: 'rounded-[2rem]' }
            });
            if (query) {
                const searchInput = document.getElementById('studentSearchInput');
                if(searchInput) {
                    searchInput.value = query;
                    searchInput.dispatchEvent(new Event('input'));
                    // Otomatis scroll ke bawah
                    searchInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
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
<?php endif; ?><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/library/dashboard.blade.php ENDPATH**/ ?>