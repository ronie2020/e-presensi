<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Baca: <?php echo e($book->title); ?></title>
    
    <!-- Scripts & Styles -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <style>
        /* Custom Scrollbar untuk Sidebar Gelap */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: rgba(255,255,255,0.05); }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }
        body { overflow: hidden; background-color: #0f172a; }
    </style>
</head>
<body class="font-sans antialiased text-slate-800">

    
    <div class="fixed inset-0 z-50 bg-slate-900 flex flex-col h-screen w-screen overflow-hidden">
        
        
        <div class="h-16 bg-slate-800 border-b border-slate-700 flex items-center justify-between px-4 sm:px-6 shrink-0 z-20 shadow-xl relative">
            
            
            <div class="flex items-center gap-4">
                
                <a href="<?php echo e(route('library.catalogue')); ?>" class="w-10 h-10 rounded-xl bg-slate-700 hover:bg-slate-600 text-slate-300 hover:text-white flex items-center justify-center transition-all group border border-slate-600 hover:border-slate-500" title="Kembali ke Katalog">
                    <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                </a>
                
                
                <div class="hidden sm:block border-l border-slate-600 pl-4">
                    <h1 class="text-white font-bold text-sm line-clamp-1 max-w-md tracking-wide"><?php echo e($book->title); ?></h1>
                    <p class="text-slate-400 text-[10px] uppercase font-bold tracking-wider flex items-center gap-1 mt-0.5">
                        <i class="ph-fill ph-pen-nib"></i> <?php echo e($book->author ?? 'Tanpa Pengarang'); ?>

                    </p>
                </div>
            </div>

            
            <div class="flex items-center gap-2">
                
                <button onclick="toggleSidebar()" class="md:hidden w-10 h-10 rounded-xl text-slate-400 hover:text-white hover:bg-slate-700 transition-colors">
                    <i class="ph-bold ph-info"></i>
                </button>

                
                <button onclick="toggleFullscreen()" class="w-10 h-10 rounded-xl text-slate-400 hover:text-white hover:bg-slate-700 transition-colors flex items-center justify-center" title="Layar Penuh">
                    <i class="ph-bold ph-corners-out text-xl"></i>
                </button>
            </div>
        </div>

        
        <div class="flex-1 flex relative bg-[#525659] overflow-hidden">
            
            
            <iframe 
                id="pdf-frame"
                src="<?php echo e(asset('storage/' . $book->ebook_path)); ?>#toolbar=0&view=FitH" 
                class="w-full h-full border-none"
                allowfullscreen>
            </iframe>

            
            <div id="book-sidebar" class="hidden md:flex w-80 bg-slate-900 border-l border-slate-800 flex-col absolute md:relative right-0 top-0 bottom-0 z-10 transition-transform duration-300 shadow-2xl">
                
                
                <div class="md:hidden p-4 border-b border-slate-800 flex justify-between items-center bg-slate-900">
                    <span class="text-white font-bold text-sm">Detail Buku</span>
                    <button onclick="toggleSidebar()" class="text-slate-400 hover:text-white">
                        <i class="ph-bold ph-x text-lg"></i>
                    </button>
                </div>

                
                <div class="p-6 overflow-y-auto custom-scrollbar flex-1">
                    
                    
                    <div class="aspect-[2/3] rounded-xl overflow-hidden shadow-2xl shadow-black/50 mb-6 border border-slate-700 bg-slate-800 flex items-center justify-center group relative">
                        <?php if($book->cover_path): ?>
                            <img src="<?php echo e(asset('storage/' . $book->cover_path)); ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="Cover">
                        <?php else: ?>
                            <div class="flex flex-col items-center gap-2 text-slate-600">
                                <i class="ph-duotone ph-book-open text-5xl"></i>
                                <span class="text-[10px] font-bold uppercase">No Cover</span>
                            </div>
                        <?php endif; ?>
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent pointer-events-none"></div>
                    </div>

                    
                    <div class="space-y-4">
                        
                        <div class="sm:hidden mb-4">
                            <h2 class="text-white font-bold text-lg leading-snug"><?php echo e($book->title); ?></h2>
                            <p class="text-slate-400 text-xs mt-1"><?php echo e($book->author); ?></p>
                        </div>

                        <div class="bg-slate-800/50 p-4 rounded-2xl border border-slate-700/50">
                            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider mb-1">Penerbit</p>
                            <p class="text-slate-200 font-medium text-sm flex items-center gap-2">
                                <i class="ph-fill ph-buildings text-blue-500"></i>
                                <?php echo e($book->publisher ?? '-'); ?>

                            </p>
                        </div>

                        <div class="bg-slate-800/50 p-4 rounded-2xl border border-slate-700/50">
                            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider mb-1">Tahun Terbit</p>
                            <p class="text-slate-200 font-medium text-sm flex items-center gap-2">
                                <i class="ph-fill ph-calendar-blank text-orange-500"></i>
                                <?php echo e($book->year ?? '-'); ?>

                            </p>
                        </div>
                        
                        <div class="bg-slate-800/50 p-4 rounded-2xl border border-slate-700/50">
                            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider mb-1">Sinopsis</p>
                            <div class="text-slate-300 text-xs leading-relaxed font-medium text-justify">
                                <?php echo e($book->description ?? 'Tidak ada sinopsis tersedia untuk buku ini.'); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(e => {
                    console.log(`Error attempting to enable fullscreen: ${e.message}`);
                });
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                }
            }
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('book-sidebar');
            if (sidebar.classList.contains('hidden')) {
                sidebar.classList.remove('hidden');
                sidebar.classList.add('flex', 'absolute', 'right-0', 'top-0', 'bottom-0', 'z-30', 'w-80', 'bg-slate-900', 'shadow-2xl');
            } else {
                sidebar.classList.add('hidden');
                sidebar.classList.remove('flex', 'absolute', 'right-0', 'top-0', 'bottom-0', 'z-30', 'w-80', 'bg-slate-900', 'shadow-2xl');
            }
        }
    </script>
</body>
</html><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/books/read.blade.php ENDPATH**/ ?>