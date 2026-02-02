<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title>Pendaftaran Kolektif - PPDB <?php echo e(config('app.name', 'Sekolah')); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    
    
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="font-sans antialiased text-slate-800 bg-slate-50">

    
    <div class="min-h-screen flex items-center justify-center p-4 lg:p-8 relative overflow-hidden">
        
        
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
            <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] rounded-full bg-blue-200/20 blur-[100px]"></div>
            <div class="absolute top-[40%] -right-[10%] w-[40%] h-[40%] rounded-full bg-indigo-200/20 blur-[100px]"></div>
        </div>

        
        <div class="w-full max-w-md md:max-w-5xl bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 border border-white flex flex-col md:flex-row relative z-10 overflow-hidden">
            
            
            <div class="w-full md:w-5/12 bg-gradient-to-br from-blue-900 to-slate-900 p-8 sm:p-10 text-white relative overflow-hidden flex flex-col justify-between">
                
                
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
                
                <div class="relative z-10">
                    
                    <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center text-white mb-8 border border-white/10 backdrop-blur-sm shadow-inner">
                        <i class="ph-duotone ph-microsoft-excel-logo text-3xl"></i>
                    </div>

                    <h1 class="text-3xl font-black mb-4 leading-tight tracking-tight">
                        Pendaftaran <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-cyan-200">Kolektif SD/MI</span>
                    </h1>
                    
                    <p class="text-blue-100/70 text-sm leading-relaxed mb-8 font-medium">
                        Fitur khusus Guru untuk mendaftarkan siswa secara massal. Hemat waktu tanpa input satu per satu.
                    </p>

                    
                    <div class="bg-blue-800/40 rounded-2xl p-6 border border-blue-700/50 backdrop-blur-sm relative group hover:bg-blue-800/60 transition-colors">
                        <div class="absolute -left-1 top-6 w-1 h-8 bg-yellow-400 rounded-r-lg"></div>
                        <div class="flex items-center gap-3 mb-2">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-yellow-400 text-yellow-900 font-bold text-xs">1</span>
                            <h3 class="font-bold text-white text-sm">Unduh Template</h3>
                        </div>
                        <p class="text-xs text-blue-200 mb-5 pl-9">Gunakan format Excel (.xlsx) resmi sekolah.</p>
                        
                        <a href="<?php echo e(route('ppdb.download_template')); ?>" class="w-full py-3 bg-white text-blue-900 font-bold rounded-xl text-xs flex items-center justify-center gap-2 hover:bg-blue-50 transition shadow-lg transform active:scale-[0.98]">
                            <i class="ph-bold ph-download-simple text-lg"></i> Download Template
                        </a>
                    </div>
                </div>

                
                <div class="mt-8 md:mt-0 relative z-10 flex items-center gap-2 opacity-50">
                    <i class="ph-fill ph-info text-lg"></i>
                    <p class="text-[10px] font-medium">Pastikan NISN siswa unik & valid.</p>
                </div>
            </div>

            
            <div class="w-full md:w-7/12 p-8 sm:p-12 bg-white flex flex-col justify-center">
                
                
                <?php if(session('success')): ?>
                    <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-700 text-xs font-bold flex items-start gap-3">
                        <i class="ph-fill ph-check-circle text-lg shrink-0 mt-0.5"></i>
                        <div><?php echo e(session('success')); ?></div>
                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-100 text-rose-700 text-xs font-bold flex items-start gap-3">
                        <i class="ph-fill ph-warning-circle text-lg shrink-0 mt-0.5"></i>
                        <div><?php echo session('error'); ?></div>
                    </div>
                <?php endif; ?>

                <form action="<?php echo e(route('ppdb.import')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    
                    <div class="mb-8">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-50 text-blue-600 font-bold text-sm border border-blue-100">2</span>
                            <div>
                                <h3 class="font-bold text-slate-800 text-base">Upload Data Siswa</h3>
                                <p class="text-xs text-slate-400">Pilih file Excel yang sudah diisi.</p>
                            </div>
                        </div>

                        
                        <div class="relative group">
                            <input type="file" name="file_excel" accept=".xlsx, .xls" required
                                   class="block w-full text-xs text-slate-500
                                          file:mr-4 file:py-3 file:px-6
                                          file:rounded-l-xl file:border-0
                                          file:text-xs file:font-bold
                                          file:bg-blue-50 file:text-blue-700
                                          hover:file:bg-blue-100
                                          file:cursor-pointer file:transition-colors
                                          border border-slate-200 rounded-xl cursor-pointer
                                          bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500
                                          h-12 leading-[3rem] shadow-sm
                                   "/>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-4 bg-blue-900 text-white font-bold rounded-2xl shadow-xl shadow-blue-900/20 hover:bg-blue-800 hover:shadow-2xl hover:shadow-blue-900/30 hover:-translate-y-1 active:translate-y-0 transition-all duration-300 flex items-center justify-center gap-3 group">
                        <span class="text-sm">Proses Pendaftaran</span>
                        <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </form>

                <div class="mt-8 pt-6 border-t border-slate-50 text-center md:text-left">
                    <a href="<?php echo e(route('ppdb.create')); ?>" class="inline-flex items-center gap-2 text-xs font-bold text-slate-400 hover:text-blue-600 transition-colors">
                        <i class="ph-bold ph-caret-left"></i> Kembali ke Pendaftaran Reguler
                    </a>
                </div>
            </div>

        </div>
    </div>

</body>
</html><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/ppdb/collective.blade.php ENDPATH**/ ?>