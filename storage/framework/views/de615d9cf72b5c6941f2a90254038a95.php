<?php $__env->startSection('content'); ?>
    <style>
        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { height: 0px; background: transparent; }
        .custom-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    
    <div class="min-h-screen bg-slate-50 font-sans text-slate-800 pb-20"
         x-data="{ activeTab: 'ringkasan' }">
        
        
        <div class="relative bg-slate-900 pb-20 pt-24 lg:pt-32 overflow-hidden rounded-b-[3rem] shadow-xl mb-8">
            
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
            <div class="absolute top-0 right-0 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl pointer-events-none translate-x-1/2 -translate-y-1/2"></div>
            
            <div class="max-w-6xl mx-auto px-6 relative z-10">
                <div class="flex flex-col md:flex-row items-center gap-8">
                    
                    <div class="relative shrink-0">
                        <div class="w-24 h-24 md:w-32 md:h-32 rounded-full border-4 border-amber-500/30 p-1 bg-slate-800 shadow-2xl">
                            <?php if($student->photo_path): ?>
                                <img src="<?php echo e(asset('storage/' . $student->photo_path)); ?>" class="w-full h-full object-cover rounded-full bg-slate-700" alt="<?php echo e($student->name); ?>">
                            <?php else: ?>
                                <div class="w-full h-full rounded-full bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center text-3xl font-black text-white">
                                    <?php echo e(substr($student->name, 0, 2)); ?>

                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="absolute -bottom-2 -right-2 bg-amber-500 text-slate-900 text-[10px] font-black px-3 py-1 rounded-full shadow-lg border border-slate-900 flex items-center gap-1">
                            <i class="ph-fill ph-graduation-cap"></i> ALUMNI <?php echo e($student->graduation_year ?? date('Y')); ?>

                        </div>
                    </div>

                    
                    <div class="text-center md:text-left flex-1">
                        <h1 class="text-3xl md:text-4xl font-black text-white mb-2 tracking-tight">
                            Halo, <?php echo e($student->nickname ?? explode(' ', $student->name)[0]); ?>!
                        </h1>
                        <p class="text-slate-400 text-sm md:text-base max-w-2xl">
                            Selamat datang di Dashboard Alumni. Ini adalah pusat data kelulusan dan arsip sekolahmu.
                        </p>
                    </div>

                    
                    <form method="POST" action="<?php echo e(route('student.logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="px-5 py-2.5 bg-white/10 hover:bg-white/20 text-white rounded-xl text-xs font-bold backdrop-blur-md transition border border-white/10 flex items-center gap-2">
                            <i class="ph-bold ph-sign-out"></i> Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>

        
        <div class="max-w-6xl mx-auto px-4 sm:px-6 -mt-12 relative z-20 mb-6">
            <div class="bg-white/90 backdrop-blur-xl p-1.5 rounded-2xl shadow-lg border border-slate-200/60 overflow-x-auto custom-scrollbar flex justify-center">
                <div class="flex items-center gap-1 w-max">
                    <button @click="activeTab = 'ringkasan'" 
                        :class="activeTab === 'ringkasan' ? 'bg-slate-900 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700'"
                        class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 flex items-center gap-2 whitespace-nowrap">
                        <i class="ph-bold ph-squares-four text-lg"></i> Ringkasan & Karir
                    </button>
                    <button @click="activeTab = 'prestasi'" 
                        :class="activeTab === 'prestasi' ? 'bg-emerald-600 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50 hover:text-emerald-700'"
                        class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 flex items-center gap-2 whitespace-nowrap">
                        <i class="ph-bold ph-trophy text-lg"></i> Riwayat Prestasi
                    </button>
                    <button @click="activeTab = 'perpustakaan'" 
                        :class="activeTab === 'perpustakaan' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50 hover:text-indigo-700'"
                        class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 flex items-center gap-2 whitespace-nowrap">
                        <i class="ph-bold ph-books text-lg"></i> Riwayat Pustaka
                    </button>
                </div>
            </div>
        </div>

        
        <div class="max-w-6xl mx-auto px-4 sm:px-6 relative z-10">
            
            
            <div x-show="activeTab === 'ringkasan'" x-transition.duration.300ms>
                
                
                <?php if(!$isTracerFilled): ?>
                <div class="bg-amber-50 border border-amber-200 rounded-[2rem] p-6 md:p-8 shadow-lg shadow-amber-900/5 mb-8 flex flex-col md:flex-row items-center gap-6 animate-pulse">
                    <div class="w-16 h-16 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center text-3xl shrink-0">
                        <i class="ph-duotone ph-warning-circle"></i>
                    </div>
                    <div class="flex-1 text-center md:text-left">
                        <h3 class="text-lg font-bold text-amber-900 mb-1">Data Alumni Belum Lengkap!</h3>
                        <p class="text-sm text-amber-700/80">
                            Mohon luangkan waktu untuk mengisi data Sekolah Lanjutan atau Pekerjaan Anda saat ini demi kelengkapan database alumni.
                        </p>
                    </div>
                    <a href="<?php echo e(route('alumni.tracer')); ?>" class="px-8 py-3 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl shadow-lg shadow-amber-500/30 transition-all transform hover:-translate-y-1 whitespace-nowrap">
                        <i class="ph-bold ph-pencil-simple mr-1"></i> Isi Tracer Study
                    </a>
                </div>
                <?php else: ?>
                <div class="bg-emerald-50 border border-emerald-100 rounded-[2rem] p-6 mb-8 flex flex-col sm:flex-row items-center gap-4 shadow-sm">
                    <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center text-2xl shrink-0">
                        <i class="ph-fill ph-check-circle"></i>
                    </div>
                    <div class="flex-1 text-center sm:text-left">
                        <h3 class="text-base font-bold text-emerald-800">Data Alumni Terverifikasi</h3>
                        <p class="text-sm text-emerald-600">Terima kasih telah berkontribusi. Data Anda aman tersimpan.</p>
                    </div>
                    <a href="<?php echo e(route('alumni.tracer')); ?>" class="text-sm font-bold text-emerald-700 underline hover:text-emerald-900">
                        Edit Data
                    </a>
                </div>
                <?php endif; ?>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    
                    <div class="md:col-span-2 bg-white rounded-[2.5rem] p-8 shadow-xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden group">
                        <div class="absolute top-0 right-0 w-40 h-40 bg-slate-50 rounded-full mix-blend-multiply filter blur-3xl opacity-50 -mr-10 -mt-10 group-hover:bg-blue-50 transition-colors"></div>
                        
                        <div class="flex items-center gap-3 mb-6 relative z-10">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl"><i class="ph-bold ph-briefcase"></i></div>
                            <h3 class="text-lg font-black text-slate-800">Aktivitas Saat Ini</h3>
                        </div>

                        <?php if($profile): ?>
                            <div class="flex flex-col md:flex-row gap-6 items-center md:items-start bg-slate-50 p-6 rounded-3xl border border-slate-100">
                                <div class="w-20 h-20 rounded-2xl flex items-center justify-center text-4xl shadow-sm bg-white text-slate-700">
                                    
                                    <?php if(in_array($profile->activity_status, ['SMA', 'SMK', 'MA'])): ?>
                                        <i class="ph-duotone ph-student text-blue-500"></i>
                                    <?php elseif($profile->activity_status == 'Pesantren'): ?>
                                        <i class="ph-duotone ph-mosque text-emerald-500"></i>
                                    <?php elseif($profile->activity_status == 'Bekerja'): ?>
                                        <i class="ph-duotone ph-briefcase text-amber-500"></i>
                                    <?php else: ?>
                                        <i class="ph-duotone ph-user text-slate-500"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="text-center md:text-left">
                                    <span class="inline-block px-3 py-1 bg-white border border-slate-200 rounded-lg text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-2">
                                        <?php echo e($profile->activity_status); ?>

                                    </span>
                                    <h4 class="text-xl font-bold text-slate-900 mb-1">
                                        <?php echo e($profile->campus_name ?? $profile->company_name ?? 'Data Belum Lengkap'); ?>

                                    </h4>
                                    <p class="text-sm text-slate-500">
                                        <?php echo e($profile->campus_major ?? $profile->position ?? '-'); ?> 
                                        <?php if($profile->campus_entry_year): ?> • Angkatan <?php echo e($profile->campus_entry_year); ?> <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                            
                            <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="flex items-center gap-3 p-3 rounded-2xl border border-slate-100 hover:bg-slate-50 transition">
                                    <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500"><i class="ph-fill ph-whatsapp-logo"></i></div>
                                    <span class="text-sm font-bold text-slate-600"><?php echo e($profile->phone_number ?? '-'); ?></span>
                                </div>
                                <div class="flex items-center gap-3 p-3 rounded-2xl border border-slate-100 hover:bg-slate-50 transition">
                                    <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500"><i class="ph-fill ph-envelope-simple"></i></div>
                                    <span class="text-sm font-bold text-slate-600 truncate"><?php echo e($profile->email ?? '-'); ?></span>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-10 bg-slate-50 rounded-3xl border border-dashed border-slate-200">
                                <i class="ph-duotone ph-folder-dashed text-4xl text-slate-300 mb-2"></i>
                                <p class="text-sm text-slate-400 font-medium">Data belum tersedia</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    
                    <div class="space-y-6">
                        <div class="bg-white rounded-[2.5rem] p-6 shadow-xl shadow-slate-200/50 border border-slate-100">
                            <h3 class="text-base font-bold text-slate-800 mb-4 px-2">Menu Lainnya</h3>
                            <div class="space-y-3">
                                <a href="<?php echo e(route('portal.show', $student->id)); ?>" class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 hover:bg-blue-50 hover:text-blue-700 transition group">
                                    <div class="w-10 h-10 rounded-xl bg-white text-slate-400 shadow-sm flex items-center justify-center group-hover:text-blue-600 transition">
                                        <i class="ph-bold ph-globe"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-bold text-sm">Lihat Web Publik</p>
                                        <p class="text-[10px] text-slate-400 group-hover:text-blue-400">Tampilan profil publik</p>
                                    </div>
                                    <i class="ph-bold ph-arrow-right text-sm opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                </a>

                                <button onclick="window.print()" class="w-full flex items-center gap-4 p-4 rounded-2xl bg-slate-50 hover:bg-indigo-50 hover:text-indigo-700 transition group text-left">
                                    <div class="w-10 h-10 rounded-xl bg-white text-slate-400 shadow-sm flex items-center justify-center group-hover:text-indigo-600 transition">
                                        <i class="ph-bold ph-printer"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-bold text-sm">Cetak Biodata</p>
                                        <p class="text-[10px] text-slate-400 group-hover:text-indigo-400">Arsip data diri</p>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div x-show="activeTab === 'prestasi'" x-cloak x-transition.duration.300ms>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    <div class="lg:col-span-1">
                        <div class="bg-white p-6 rounded-3xl shadow-lg shadow-emerald-100/50 border border-emerald-100 sticky top-4">
                            <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-2xl mb-4">
                                <i class="ph-duotone ph-trophy"></i>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800 mb-1">Total Poin Kebaikan</h3>
                            <p class="text-sm text-slate-500 mb-6">Akumulasi prestasi selama bersekolah.</p>
                            
                            <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl p-6 text-center text-white shadow-lg shadow-emerald-500/30">
                                <p class="text-4xl font-black mb-1">+<?php echo e($total_merit_points ?? 0); ?></p>
                                <p class="text-xs font-medium opacity-80 uppercase tracking-widest">POIN TERKUMPUL</p>
                            </div>
                        </div>
                    </div>

                    
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden min-h-[400px]">
                            <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                                <h3 class="font-bold text-slate-800">Jejak Histori Prestasi</h3>
                            </div>
                            <div class="divide-y divide-slate-50">
                                <?php if(isset($achievements) && count($achievements) > 0): ?>
                                    <?php $__currentLoopData = $achievements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="p-6 hover:bg-emerald-50/30 transition-colors flex gap-4 items-start">
                                        <div class="flex-shrink-0 w-14 text-center">
                                            <div class="text-2xl font-black text-slate-300">
                                                <?php echo e(\Carbon\Carbon::parse($record->date)->format('d')); ?>

                                            </div>
                                            <div class="text-[10px] font-bold text-slate-400 uppercase">
                                                <?php echo e(\Carbon\Carbon::parse($record->date)->translatedFormat('M Y')); ?>

                                            </div>
                                        </div>
                                        <div class="flex-grow">
                                            <div class="flex justify-between items-start mb-1">
                                                <h4 class="font-bold text-slate-800 text-lg"><?php echo e($record->disciplineType->name ?? 'Prestasi'); ?></h4>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-bold bg-emerald-100 text-emerald-700">
                                                    +<?php echo e($record->disciplineType->point_value ?? 0); ?>

                                                </span>
                                            </div>
                                            <p class="text-sm text-slate-500"><?php echo e($record->notes ?? 'Tanpa keterangan'); ?></p>
                                        </div>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <div class="p-16 text-center flex flex-col items-center justify-center">
                                        <i class="ph-duotone ph-star text-4xl text-slate-200 mb-3"></i>
                                        <p class="text-slate-400 text-sm">Belum ada data prestasi tercatat.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div x-show="activeTab === 'perpustakaan'" x-cloak x-transition.duration.300ms>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    <div class="lg:col-span-1">
                        <div class="bg-white p-6 rounded-3xl shadow-lg shadow-indigo-100/50 border border-indigo-100 sticky top-4">
                            <div class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center text-2xl mb-4">
                                <i class="ph-duotone ph-books"></i>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800 mb-1">Literasi</h3>
                            <p class="text-sm text-slate-500 mb-6">Ringkasan aktivitas perpustakaan.</p>
                            
                            <div class="space-y-3">
                                <div class="flex justify-between items-center p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                    <span class="text-xs font-bold text-slate-500 uppercase">Total Kunjungan</span>
                                    <span class="text-xl font-black text-slate-800"><?php echo e($library_visits ?? 0); ?></span>
                                </div>
                                <div class="flex justify-between items-center p-4 bg-indigo-50 rounded-2xl border border-indigo-100">
                                    <span class="text-xs font-bold text-indigo-600 uppercase">Total Buku Dipinjam</span>
                                    <span class="text-xl font-black text-indigo-700"><?php echo e(isset($library_history) ? count($library_history) : 0); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden min-h-[400px]">
                            <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                                <h3 class="font-bold text-slate-800">Riwayat Peminjaman Buku</h3>
                            </div>
                            <div class="divide-y divide-slate-50">
                                <?php if(isset($library_history) && count($library_history) > 0): ?>
                                    <?php $__currentLoopData = $library_history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="p-5 hover:bg-indigo-50/30 transition-colors flex items-center gap-5">
                                        <div class="w-12 h-16 bg-slate-200 rounded-md flex-shrink-0 flex items-center justify-center text-slate-400 shadow-sm border border-slate-300/50">
                                            <i class="ph-fill ph-book-open text-2xl"></i>
                                        </div>
                                        <div class="flex-grow min-w-0">
                                            <h4 class="font-bold text-slate-800 truncate mb-1" title="<?php echo e($book->title); ?>"><?php echo e($book->title); ?></h4>
                                            <div class="flex flex-wrap items-center gap-3 text-xs text-slate-500">
                                                <span class="flex items-center gap-1">
                                                    <i class="ph-bold ph-calendar-blank"></i> 
                                                    <?php echo e(\Carbon\Carbon::parse($book->borrow_date)->translatedFormat('d M Y')); ?>

                                                </span>
                                            </div>
                                        </div>
                                        <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-[10px] font-bold uppercase tracking-wide border border-slate-200">
                                            Kembali
                                        </span>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <div class="p-16 text-center flex flex-col items-center justify-center">
                                        <i class="ph-duotone ph-book-bookmark text-4xl text-slate-200 mb-3"></i>
                                        <p class="text-slate-400 text-sm">Tidak ada riwayat peminjaman buku.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\alumni\dashboard.blade.php ENDPATH**/ ?>