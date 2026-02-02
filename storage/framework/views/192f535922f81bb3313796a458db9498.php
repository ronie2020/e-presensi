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
    
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
    </style>

    
    <?php if(session('error')): ?>
    <script>
        Swal.fire({ icon: 'error', title: 'Oops...', text: '<?php echo e(session('error')); ?>', customClass: { popup: 'rounded-[2rem]' } });
    </script>
    <?php endif; ?>

    <div class="py-8 sm:py-10 font-sans text-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <div class="animate-enter relative rounded-[2rem] bg-gray-900 bg-gradient-to-br from-blue-900 via-blue-800 to-slate-900 p-8 mb-10 text-white shadow-xl shadow-blue-900/30 overflow-hidden border border-white/10">
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
                
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <a href="<?php echo e(route('library.dashboard')); ?>" class="px-3 py-1 bg-white/10 hover:bg-white/20 rounded-lg text-xs font-bold text-blue-100 transition flex items-center gap-2">
                                <i class="ph-bold ph-arrow-left"></i> Dashboard
                            </a>
                            <span class="text-blue-300 text-xs font-bold uppercase tracking-wider">Alat Admin</span>
                        </div>
                        <h1 class="text-3xl font-black tracking-tight flex items-center gap-3">
                            <span class="text-4xl">🖨️</span> Pusat Cetak & Laporan
                        </h1>
                        <p class="text-blue-200 text-sm font-medium mt-2 max-w-lg">
                            Kelola kebutuhan administrasi fisik perpustakaan, cetak kartu anggota, label buku, dan laporan sirkulasi.
                        </p>
                    </div>
                    <div class="hidden md:block">
                        <div class="bg-white/10 backdrop-blur-md p-4 rounded-2xl border border-white/10">
                            <i class="ph-duotone ph-printer text-4xl text-white opacity-80"></i>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                
                <div class="animate-enter delay-100 bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 h-full flex flex-col relative overflow-hidden group hover:shadow-2xl hover:shadow-blue-900/10 transition-all duration-300">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-blue-50 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none group-hover:bg-blue-100 transition-colors duration-500"></div>
                    
                    <div class="p-8 pb-0 relative z-10">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-[1.2rem] flex items-center justify-center text-2xl shadow-sm border border-blue-100 group-hover:scale-110 transition-transform">
                                <i class="ph-duotone ph-identification-card"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-black text-slate-800">Kartu Anggota</h2>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mt-1">ID Card Siswa</p>
                            </div>
                        </div>
                        <p class="text-sm text-slate-500 font-medium mb-6 leading-relaxed">
                            Cetak kartu perpustakaan siswa. Bisa satuan atau per kelas (Batch Print).
                        </p>
                    </div>

                    <div class="p-8 pt-0 mt-auto relative z-10">
                        <form action="<?php echo e(route('library.tools.print-card')); ?>" method="GET" target="_blank" class="space-y-4" x-data="{ mode: 'single' }">
                            
                            
                            <div class="flex bg-slate-100 p-1 rounded-xl mb-4 border border-slate-200">
                                <button type="button" @click="mode = 'single'" :class="mode === 'single' ? 'bg-white text-blue-700 shadow-sm font-black' : 'text-slate-500 font-bold hover:text-slate-700'" class="flex-1 py-2 text-xs rounded-lg transition-all">Per Siswa</button>
                                <button type="button" @click="mode = 'class'" :class="mode === 'class' ? 'bg-white text-blue-700 shadow-sm font-black' : 'text-slate-500 font-bold hover:text-slate-700'" class="flex-1 py-2 text-xs rounded-lg transition-all">Per Kelas</button>
                            </div>

                            <input type="hidden" name="mode" x-model="mode">

                            
                            <div x-show="mode === 'single'" x-transition>
                                <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-2 ml-1">NISN / NIS Siswa</label>
                                <div class="flex items-center px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus-within:border-blue-500 focus-within:bg-white focus-within:shadow-md transition-all">
                                    <i class="ph-bold ph-user text-slate-400 mr-3"></i>
                                    <input type="text" name="nisn" class="w-full bg-transparent border-none focus:ring-0 text-slate-800 font-bold text-sm placeholder-slate-400" placeholder="Contoh: 12345678">
                                </div>
                            </div>

                            
                            <div x-show="mode === 'class'" style="display: none;" x-transition>
                                <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-2 ml-1">Pilih Kelas</label>
                                <div class="relative">
                                    <select name="class_id" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 font-bold text-slate-700 text-sm focus:ring-0 focus:border-blue-500 focus:bg-white focus:shadow-md transition-all appearance-none cursor-pointer">
                                        <option value="" disabled selected>-- Pilih Kelas --</option>
                                        <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($class->id); ?>"><?php echo e($class->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <i class="ph-bold ph-caret-down absolute right-4 top-3.5 text-slate-400 pointer-events-none"></i>
                                </div>
                            </div>

                            <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl shadow-lg shadow-blue-600/30 transition-all transform active:scale-95 flex items-center justify-center gap-2 group/btn">
                                <i class="ph-bold ph-printer text-lg"></i> 
                                <span>Cetak Sekarang</span>
                            </button>
                        </form>
                    </div>
                </div>

                
                <div class="animate-enter delay-200 bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 h-full flex flex-col relative overflow-hidden group hover:shadow-2xl hover:shadow-purple-900/10 transition-all duration-300">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-purple-50 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none group-hover:bg-purple-100 transition-colors duration-500"></div>
                    
                    <div class="p-8 pb-0 relative z-10">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-14 h-14 bg-purple-50 text-purple-600 rounded-[1.2rem] flex items-center justify-center text-2xl shadow-sm border border-purple-100 group-hover:scale-110 transition-transform">
                                <i class="ph-duotone ph-barcode"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-black text-slate-800">Label Buku</h2>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mt-1">Stiker Barcode</p>
                            </div>
                        </div>
                        <p class="text-sm text-slate-500 font-medium mb-6 leading-relaxed">
                            Cetak label punggung dan barcode untuk koleksi buku baru.
                        </p>
                    </div>

                    <div class="p-8 pt-0 mt-auto relative z-10">
                        <form action="<?php echo e(route('library.tools.print-book-label')); ?>" method="GET" target="_blank" class="space-y-4" x-data="{ mode: 'latest' }">
                            
                            
                            <div class="flex bg-slate-100 p-1 rounded-xl mb-4 border border-slate-200">
                                <button type="button" @click="mode = 'latest'" :class="mode === 'latest' ? 'bg-white text-purple-700 shadow-sm font-black' : 'text-slate-500 font-bold hover:text-slate-700'" class="flex-1 py-2 text-xs rounded-lg transition-all">Buku Terbaru</button>
                                <button type="button" @click="mode = 'manual'" :class="mode === 'manual' ? 'bg-white text-purple-700 shadow-sm font-black' : 'text-slate-500 font-bold hover:text-slate-700'" class="flex-1 py-2 text-xs rounded-lg transition-all">Pilih Manual</button>
                            </div>

                            <div x-show="mode === 'latest'" x-transition>
                                <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-2 ml-1">Jumlah Terakhir</label>
                                <div class="flex items-center px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus-within:border-purple-500 focus-within:bg-white focus-within:shadow-md transition-all">
                                    <i class="ph-bold ph-stack text-slate-400 mr-3"></i>
                                    <input type="number" name="limit" value="10" min="1" max="100" class="w-full bg-transparent border-none focus:ring-0 text-slate-800 font-bold text-sm">
                                </div>
                            </div>

                            <div x-show="mode === 'manual'" style="display: none;" x-transition>
                                <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-2 ml-1">Kode Buku (Pisahkan koma)</label>
                                <div class="flex items-center px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus-within:border-purple-500 focus-within:bg-white focus-within:shadow-md transition-all">
                                    <i class="ph-bold ph-keyboard text-slate-400 mr-3"></i>
                                    <input type="text" name="book_codes" class="w-full bg-transparent border-none focus:ring-0 text-slate-800 font-bold text-sm placeholder-slate-400" placeholder="B001, B002, B003">
                                </div>
                                <p class="text-[10px] text-slate-400 mt-1 italic ml-1">*Contoh: BK-001, BK-002</p>
                            </div>
                            
                            <button type="submit" class="w-full py-4 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-2xl shadow-lg shadow-purple-600/30 transition-all transform active:scale-95 flex items-center justify-center gap-2 group/btn">
                                <i class="ph-bold ph-printer text-lg"></i> 
                                <span>Cetak Label</span>
                            </button>
                        </form>
                    </div>
                </div>

                
                <div class="animate-enter delay-300 bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 h-full flex flex-col relative overflow-hidden group hover:shadow-2xl hover:shadow-emerald-900/10 transition-all duration-300">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-50 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none group-hover:bg-emerald-100 transition-colors duration-500"></div>

                    <div class="p-8 pb-0 relative z-10">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-[1.2rem] flex items-center justify-center text-2xl shadow-sm border border-emerald-100 group-hover:scale-110 transition-transform">
                                <i class="ph-duotone ph-file-pdf"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-black text-slate-800">Export Laporan</h2>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mt-1">Data Sirkulasi</p>
                            </div>
                        </div>
                        <p class="text-sm text-slate-500 font-medium mb-6 leading-relaxed">
                            Unduh rekapitulasi data peminjaman dan statistik bulanan.
                        </p>
                    </div>

                    <div class="p-8 pt-0 mt-auto relative z-10">
                        <form action="<?php echo e(route('library.tools.report')); ?>" method="GET" target="_blank" class="space-y-4">
                            <div>
                                <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-2 ml-1">Jenis Laporan</label>
                                <div class="relative">
                                    <select name="type" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 font-bold text-slate-700 text-sm focus:ring-0 focus:border-emerald-500 focus:bg-white focus:shadow-md transition-all appearance-none cursor-pointer">
                                        <option value="monthly">Sirkulasi Bulanan</option>
                                        
                                    </select>
                                    <i class="ph-bold ph-caret-down absolute right-4 top-3.5 text-slate-400 pointer-events-none"></i>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-2 ml-1">Bulan</label>
                                    <div class="relative">
                                        <select name="month" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-3 py-3 font-bold text-slate-700 text-sm focus:ring-0 focus:border-emerald-500 focus:bg-white focus:shadow-md transition-all appearance-none cursor-pointer">
                                            <?php for($i = 1; $i <= 12; $i++): ?>
                                                <option value="<?php echo e($i); ?>" <?php echo e(date('m') == $i ? 'selected' : ''); ?>><?php echo e(date('F', mktime(0, 0, 0, $i, 10))); ?></option>
                                            <?php endfor; ?>
                                        </select>
                                        <i class="ph-bold ph-caret-down absolute right-3 top-3.5 text-slate-400 pointer-events-none"></i>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-2 ml-1">Tahun</label>
                                    <input type="number" name="year" value="<?php echo e(date('Y')); ?>" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-3 py-3 font-bold text-slate-700 text-sm focus:ring-0 focus:border-emerald-500 focus:bg-white focus:shadow-md transition-all text-center">
                                </div>
                            </div>
                            <button type="submit" class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl shadow-lg shadow-emerald-600/30 transition-all transform active:scale-95 flex items-center justify-center gap-2 group/btn">
                                <i class="ph-bold ph-download-simple text-lg"></i> 
                                <span>Lihat Laporan</span>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/library/tools/index.blade.php ENDPATH**/ ?>