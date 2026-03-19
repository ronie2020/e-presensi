<!DOCTYPE html>
<html lang="id">
<head>
    <title>Cetak Label Buku</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    
    <link href="https://fonts.googleapis.com/css2?family=Libre+Barcode+39+Text&family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: #cbd5e1; 
            -webkit-print-color-adjust: exact; 
            print-color-adjust: exact;
        }

        /* FONT BARCODE (Diperbesar sedikit spacingnya agar mudah discan) */
        .font-barcode {
            font-family: 'Libre Barcode 39 Text', cursive;
            font-size: 38px; 
            line-height: 1;
            white-space: nowrap;
        }

        /* LAYOUT KERTAS A4 */
        .page {
            width: 210mm;
            min-height: 297mm;
            padding: 10mm;
            margin: 10mm auto;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            display: grid;
            grid-template-columns: repeat(3, 1fr); /* 3 Kolom */
            grid-auto-rows: min-content;
            gap: 5mm; 
            align-content: start;
        }

        /* STICKER CONTAINER */
        .sticker {
            height: 35mm; /* Tinggi ideal label buku */
            display: flex;
            overflow: hidden;
            position: relative;
            background: white;
            page-break-inside: avoid;
        }

        /* OPSI GARIS POTONG (Dinyalakan via Alpine) */
        .sticker.show-border {
            border: 1px dashed #94a3b8;
        }
        .sticker.no-border {
            border: 1px solid transparent; /* Tetap ada border transparan agar layout tidak geser */
        }

        /* LABEL PUNGGUNG (SPINE) */
        .spine-label {
            width: 32%;
            background: #fff;
            border-right: 1px solid #000; /* Garis lipat tegas */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 2px;
        }

        /* LABEL COVER (BARCODE) */
        .cover-label {
            width: 68%;
            padding: 4px 8px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        @media print {
            body { background: white; margin: 0; padding: 0; }
            .page { 
                width: 100%; margin: 0; box-shadow: none; padding: 5mm; 
                page-break-after: always;
            }
            .no-print { display: none !important; }
            
            /* Sembunyikan scrollbar saat print */
            ::-webkit-scrollbar { display: none; }
        }
    </style>
</head>
<body x-data="{ showBorder: true }">

    <!-- TOOLBAR NAVIGASI (NO PRINT) -->
    <div class="no-print fixed top-0 left-0 w-full bg-slate-900 text-white p-3 shadow-lg z-50 flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="flex items-center gap-4">
            <div class="bg-white/10 p-2 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
            </div>
            <div>
                <h1 class="font-bold text-sm md:text-base">Preview Label Buku</h1>
                <p class="text-xs text-slate-400">Total: <?php echo e($books->count()); ?> Label</p>
            </div>
        </div>

        
        <div class="flex items-center gap-3 bg-white/5 px-4 py-2 rounded-xl border border-white/10">
            <label class="text-xs font-bold text-slate-300 uppercase tracking-wider cursor-pointer select-none flex items-center gap-2">
                <input type="checkbox" x-model="showBorder" class="w-4 h-4 rounded text-blue-500 bg-slate-700 border-slate-600 focus:ring-offset-slate-900">
                Tampilkan Garis Potong
            </label>
        </div>

        <div class="flex gap-2">
            <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-500 text-white px-5 py-2 rounded-xl font-bold shadow-lg transition flex items-center gap-2 text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd" /></svg>
                Cetak Label
            </button>
            <button onclick="window.close()" class="bg-slate-700 text-slate-300 px-4 py-2 rounded-xl font-bold hover:bg-slate-600 transition text-sm">
                Tutup
            </button>
        </div>
    </div>

    <!-- AREA KERTAS -->
    <div class="page mt-24">
        <?php $__currentLoopData = $books; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        
        <?php
            $ddc = substr($book->category->code ?? '000', 0, 3);
            $colorClass = 'border-black'; 
            // Logika warna bisa ditambahkan disini jika perlu (misal 800 sastra = kuning, dsb)
        ?>

        <div class="sticker" :class="showBorder ? 'show-border' : 'no-border'">
            
            
            <div class="spine-label">
                
                <div class="text-[6px] font-black uppercase tracking-tighter mb-1 rotate-0 text-slate-400">
                    PERPUS
                </div>
                
                
                <h3 class="text-lg font-black text-slate-900 leading-none">
                    <?php echo e($ddc); ?>

                </h3>
                
                
                <h3 class="text-xs font-bold text-slate-900 leading-none mt-1 uppercase font-mono">
                    <?php echo e(substr($book->author ?? 'XXX', 0, 3)); ?>

                </h3>
                
                
                <h3 class="text-xs font-bold text-slate-900 leading-none mt-0.5 lowercase font-mono">
                    <?php echo e(substr($book->title ?? 'x', 0, 1)); ?>

                </h3>
            </div>

            
            <div class="cover-label">
                
                <p class="text-[9px] font-bold text-slate-700 text-center leading-tight mb-1 w-full line-clamp-2 h-6 flex items-center justify-center">
                    <?php echo e($book->title); ?>

                </p>
                
                
                <div class="flex-1 flex items-center justify-center w-full overflow-hidden">
                    <span class="font-barcode text-black">
                        *<?php echo e($book->book_code); ?>*
                    </span>
                </div>
                
                
                <p class="text-[9px] font-mono font-bold text-slate-500 tracking-wider mt-0.5">
                    <?php echo e($book->book_code); ?>

                </p>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

</body>
</html><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/library/tools/print-book-label.blade.php ENDPATH**/ ?>