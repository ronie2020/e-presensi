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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
    </style>

    
    <?php if(session('error')): ?>
    <script>
        Swal.fire({ 
            icon: 'error', 
            title: 'Oops...', 
            text: <?php echo json_encode(session('error')); ?>, 
            confirmButtonColor: '#2c3f61',
            customClass: { popup: 'rounded-[2rem]' } 
        });
    </script>
    <?php endif; ?>

    <div class="py-8 sm:py-10 font-sans text-elevate-dark relative overflow-hidden">
        
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            
            <div class="animate-enter relative rounded-[2rem] bg-elevate-gradient-main p-8 mb-10 text-elevate-dark shadow-xl shadow-elevate-accent/20 overflow-hidden border border-white/60">
                <div class="absolute inset-0 opacity-[0.03] bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none mix-blend-overlay"></div>
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-white/40 rounded-full blur-3xl pointer-events-none"></div>
                
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <a href="<?php echo e(route('library.dashboard')); ?>" class="px-3 py-1 bg-white/50 hover:bg-white/80 rounded-lg text-xs font-bold text-elevate-primary transition flex items-center gap-2 border border-white/60 backdrop-blur-sm shadow-sm">
                                <i class="ph-bold ph-arrow-left"></i> Dashboard
                            </a>
                            <span class="text-elevate-dark/50 text-xs font-bold uppercase tracking-wider">Alat Admin</span>
                        </div>
                        <h1 class="text-3xl font-black tracking-tight flex items-center gap-3 mt-2">
                            <span class="text-4xl">🖨️</span> Pusat Cetak & Laporan
                        </h1>
                        <p class="text-elevate-dark/80 text-sm font-semibold mt-2 max-w-lg leading-relaxed">
                            Kelola kebutuhan administrasi fisik perpustakaan, cetak kartu anggota, label buku, dan laporan sirkulasi.
                        </p>
                    </div>
                    <div class="hidden md:block">
                        <div class="w-16 h-16 rounded-2xl bg-white/50 backdrop-blur-md flex items-center justify-center border border-white/60 shadow-sm shrink-0 text-elevate-primary">
                            <i class="ph-duotone ph-printer text-4xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                
                <div class="animate-enter delay-100 bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 h-full flex flex-col relative overflow-hidden group hover:shadow-2xl hover:shadow-elevate-accent/10 transition-all duration-300">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-elevate-soft rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none group-hover:bg-elevate-peach-light/40 transition-colors duration-500"></div>
                    
                    <div class="p-8 pb-0 relative z-10">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-14 h-14 bg-elevate-soft text-elevate-primary rounded-[1.2rem] flex items-center justify-center text-2xl shadow-sm border border-slate-200 group-hover:scale-110 transition-transform">
                                <i class="ph-duotone ph-identification-card"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-black text-elevate-dark">Kartu Anggota</h2>
                                <p class="text-xs font-bold text-elevate-dark/60 uppercase tracking-wide mt-1">ID Card Siswa</p>
                            </div>
                        </div>
                        <p class="text-sm text-elevate-dark/70 font-medium mb-6 leading-relaxed">
                            Cetak kartu perpustakaan siswa. Bisa satuan atau per kelas (Batch Print).
                        </p>
                    </div>

                    <div class="p-8 pt-0 mt-auto relative z-10">
                        <form action="<?php echo e(route('library.tools.print-card')); ?>" method="GET" target="_blank" class="space-y-4" x-data="{ mode: 'single' }">
                            
                            
                            <div class="flex bg-elevate-soft p-1 rounded-xl mb-4 border border-slate-200">
                                <button type="button" @click="mode = 'single'" :class="mode === 'single' ? 'bg-white text-elevate-primary shadow-sm font-black' : 'text-elevate-dark/60 font-bold hover:text-elevate-dark'" class="flex-1 py-2 text-xs rounded-lg transition-all border border-transparent">Per Siswa</button>
                                <button type="button" @click="mode = 'class'" :class="mode === 'class' ? 'bg-white text-elevate-primary shadow-sm font-black' : 'text-elevate-dark/60 font-bold hover:text-elevate-dark'" class="flex-1 py-2 text-xs rounded-lg transition-all border border-transparent">Per Kelas</button>
                            </div>

                            <input type="hidden" name="mode" x-model="mode">

                            
                            <div x-show="mode === 'single'" x-transition>
                                <label class="block text-xs font-black text-elevate-primary uppercase tracking-wider mb-2 ml-1">NISN / NIS Siswa</label>
                                <div class="flex items-center px-4 py-3 bg-white border border-slate-200 rounded-2xl focus-within:border-elevate-accent focus-within:ring-4 focus-within:ring-elevate-accent/20 transition-all shadow-sm">
                                    <i class="ph-bold ph-user text-slate-400 mr-3"></i>
                                    <input type="text" name="nisn" class="w-full bg-transparent border-none focus:ring-0 text-elevate-dark font-bold text-sm placeholder-slate-400" placeholder="Contoh: 12345678">
                                </div>
                            </div>

                            
                            <div x-show="mode === 'class'" style="display: none;" x-transition>
                                <label class="block text-xs font-black text-elevate-primary uppercase tracking-wider mb-2 ml-1">Pilih Kelas</label>
                                <div class="relative">
                                    <select name="class_id" class="w-full bg-white border border-slate-200 rounded-2xl px-4 py-3 font-bold text-elevate-dark text-sm focus:ring-4 focus:ring-elevate-accent/20 focus:border-elevate-accent transition-all appearance-none cursor-pointer shadow-sm">
                                        <option value="" disabled selected>-- Pilih Kelas --</option>
                                        <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($class->id); ?>"><?php echo e($class->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <i class="ph-bold ph-caret-down absolute right-4 top-3.5 text-slate-400 pointer-events-none"></i>
                                </div>
                            </div>

                            <button type="submit" class="w-full py-4 bg-elevate-dark hover:bg-elevate-primary text-white font-bold rounded-2xl shadow-lg shadow-elevate-dark/30 transition-all transform active:scale-95 flex items-center justify-center gap-2 group/btn border border-transparent">
                                <i class="ph-bold ph-printer text-lg"></i> 
                                <span>Cetak Sekarang</span>
                            </button>
                        </form>
                    </div>
                </div>

                
                <div class="animate-enter delay-200 bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 h-full flex flex-col relative overflow-hidden group hover:shadow-2xl hover:shadow-elevate-accent/10 transition-all duration-300">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-elevate-soft rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none group-hover:bg-elevate-peach-light/40 transition-colors duration-500"></div>
                    
                    <div class="p-8 pb-0 relative z-10">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-14 h-14 bg-elevate-soft text-elevate-primary rounded-[1.2rem] flex items-center justify-center text-2xl shadow-sm border border-slate-200 group-hover:scale-110 transition-transform">
                                <i class="ph-duotone ph-barcode"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-black text-elevate-dark">Label Buku</h2>
                                <p class="text-xs font-bold text-elevate-dark/60 uppercase tracking-wide mt-1">Stiker Barcode</p>
                            </div>
                        </div>
                        <p class="text-sm text-elevate-dark/70 font-medium mb-6 leading-relaxed">
                            Cetak label punggung dan barcode untuk koleksi buku baru.
                        </p>
                    </div>

                    <div class="p-8 pt-0 mt-auto relative z-10">
                        <form action="<?php echo e(route('library.tools.print-book-label')); ?>" method="GET" target="_blank" class="space-y-4" x-data="{ mode: 'by_book' }">
                            
                            
                            <div class="flex bg-elevate-soft p-1 rounded-xl mb-4 border border-slate-200">
                                <button type="button" @click="mode = 'by_book'" :class="mode === 'by_book' ? 'bg-white text-elevate-primary shadow-sm font-black' : 'text-elevate-dark/60 font-bold hover:text-elevate-dark'" class="flex-1 py-2 text-[10px] sm:text-xs rounded-lg transition-all border border-transparent">Per Buku</button>
                                <button type="button" @click="mode = 'latest'" :class="mode === 'latest' ? 'bg-white text-elevate-primary shadow-sm font-black' : 'text-elevate-dark/60 font-bold hover:text-elevate-dark'" class="flex-1 py-2 text-[10px] sm:text-xs rounded-lg transition-all border border-transparent">Terbaru</button>
                                <button type="button" @click="mode = 'manual'" :class="mode === 'manual' ? 'bg-white text-elevate-primary shadow-sm font-black' : 'text-elevate-dark/60 font-bold hover:text-elevate-dark'" class="flex-1 py-2 text-[10px] sm:text-xs rounded-lg transition-all border border-transparent">Manual</button>
                            </div>

                            
                            <div x-show="mode === 'by_book'" x-transition>
                                <label class="block text-xs font-black text-elevate-primary uppercase tracking-wider mb-2 ml-1">Pilih Judul Buku</label>
                                <div class="relative">
                                    <select name="book_id" :disabled="mode !== 'by_book'" class="w-full bg-white border border-slate-200 rounded-2xl px-4 py-3 font-bold text-elevate-dark text-sm focus:ring-4 focus:ring-elevate-accent/20 focus:border-elevate-accent transition-all appearance-none cursor-pointer shadow-sm">
                                        <option value="" disabled selected>-- Pilih Buku --</option>
                                        <?php $__currentLoopData = $books; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($book->id); ?>"><?php echo e($book->title); ?> (<?php echo e($book->stock); ?> Eksemplar)</option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <i class="ph-bold ph-caret-down absolute right-4 top-3.5 text-slate-400 pointer-events-none"></i>
                                </div>
                                <p class="text-[10px] text-slate-400 mt-1 italic ml-1">*Akan mencetak seluruh eksemplar dari buku ini.</p>
                            </div>

                            
                            <div x-show="mode === 'latest'" style="display: none;" x-transition>
                                <label class="block text-xs font-black text-elevate-primary uppercase tracking-wider mb-2 ml-1">Jumlah Terakhir Ditambahkan</label>
                                <div class="flex items-center px-4 py-3 bg-white border border-slate-200 rounded-2xl focus-within:border-elevate-accent focus-within:ring-4 focus-within:ring-elevate-accent/20 transition-all shadow-sm">
                                    <i class="ph-bold ph-stack text-slate-400 mr-3"></i>
                                    <input type="number" name="limit" :disabled="mode !== 'latest'" value="10" min="1" max="100" class="w-full bg-transparent border-none focus:ring-0 text-elevate-dark font-bold text-sm">
                                </div>
                            </div>

                            
                            <div x-show="mode === 'manual'" style="display: none;" x-transition>
                                <label class="block text-xs font-black text-elevate-primary uppercase tracking-wider mb-2 ml-1">Kode Fisik (Pisahkan koma)</label>
                                <div class="flex items-center px-4 py-3 bg-white border border-slate-200 rounded-2xl focus-within:border-elevate-accent focus-within:ring-4 focus-within:ring-elevate-accent/20 transition-all shadow-sm">
                                    <i class="ph-bold ph-keyboard text-slate-400 mr-3"></i>
                                    <input type="text" name="book_codes" :disabled="mode !== 'manual'" class="w-full bg-transparent border-none focus:ring-0 text-elevate-dark font-bold text-sm placeholder-slate-400" placeholder="BK-01, BK-02">
                                </div>
                            </div>
                            
                            <button type="submit" class="w-full py-4 bg-elevate-dark hover:bg-elevate-primary text-white font-bold rounded-2xl shadow-lg shadow-elevate-dark/30 transition-all transform active:scale-95 flex items-center justify-center gap-2 group/btn border border-transparent">
                                <i class="ph-bold ph-printer text-lg"></i> 
                                <span>Cetak Label</span>
                            </button>
                        </form>
                    </div>
                </div>

                
                <div class="animate-enter delay-300 bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 h-full flex flex-col relative overflow-hidden group hover:shadow-2xl hover:shadow-elevate-accent/10 transition-all duration-300">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-elevate-soft rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none group-hover:bg-elevate-peach-light/40 transition-colors duration-500"></div>

                    <div class="p-8 pb-0 relative z-10">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-14 h-14 bg-elevate-soft text-elevate-primary rounded-[1.2rem] flex items-center justify-center text-2xl shadow-sm border border-slate-200 group-hover:scale-110 transition-transform">
                                <i class="ph-duotone ph-file-pdf"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-black text-elevate-dark">Export Laporan</h2>
                                <p class="text-xs font-bold text-elevate-dark/60 uppercase tracking-wide mt-1">Data Sirkulasi</p>
                            </div>
                        </div>
                        <p class="text-sm text-elevate-dark/70 font-medium mb-6 leading-relaxed">
                            Unduh rekapitulasi data peminjaman dan statistik bulanan.
                        </p>
                    </div>

                    <div class="p-8 pt-0 mt-auto relative z-10">
                        <form action="<?php echo e(route('library.tools.report')); ?>" method="GET" target="_blank" class="space-y-4">
                            <div>
                                <label class="block text-xs font-black text-elevate-primary uppercase tracking-wider mb-2 ml-1">Jenis Laporan</label>
                                <div class="relative">
                                    <select name="type" class="w-full bg-white border border-slate-200 rounded-2xl px-4 py-3 font-bold text-elevate-dark text-sm focus:ring-4 focus:ring-elevate-accent/20 focus:border-elevate-accent transition-all appearance-none cursor-pointer shadow-sm">
                                        <option value="monthly">Sirkulasi Bulanan</option>
                                        <option value="top_books">Buku Terpopuler</option>
                                    </select>
                                    <i class="ph-bold ph-caret-down absolute right-4 top-3.5 text-slate-400 pointer-events-none"></i>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-black text-elevate-primary uppercase tracking-wider mb-2 ml-1">Bulan</label>
                                    <div class="relative">
                                        <select name="month" class="w-full bg-white border border-slate-200 rounded-2xl px-3 py-3 font-bold text-elevate-dark text-sm focus:ring-4 focus:ring-elevate-accent/20 focus:border-elevate-accent transition-all appearance-none cursor-pointer shadow-sm">
                                            <?php for($i = 1; $i <= 12; $i++): ?>
                                                <option value="<?php echo e($i); ?>" <?php echo e(date('m') == $i ? 'selected' : ''); ?>><?php echo e(date('F', mktime(0, 0, 0, $i, 10))); ?></option>
                                            <?php endfor; ?>
                                        </select>
                                        <i class="ph-bold ph-caret-down absolute right-3 top-3.5 text-slate-400 pointer-events-none"></i>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-elevate-primary uppercase tracking-wider mb-2 ml-1">Tahun</label>
                                    <input type="number" name="year" value="<?php echo e(date('Y')); ?>" class="w-full bg-white border border-slate-200 rounded-2xl px-3 py-3 font-bold text-elevate-dark text-sm focus:ring-4 focus:ring-elevate-accent/20 focus:border-elevate-accent transition-all text-center shadow-sm">
                                </div>
                            </div>
                            <button type="submit" class="w-full py-4 bg-elevate-dark hover:bg-elevate-primary text-white font-bold rounded-2xl shadow-lg shadow-elevate-dark/30 transition-all transform active:scale-95 flex items-center justify-center gap-2 group/btn border border-transparent">
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
<?php endif; ?><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/library/tools/index.blade.php ENDPATH**/ ?>