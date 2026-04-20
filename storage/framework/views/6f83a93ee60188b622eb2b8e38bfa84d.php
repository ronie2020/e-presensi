<!DOCTYPE html>
<html lang="id">
<head>
    <title>Cetak Label Buku (Eksemplar)</title>
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

        /* FONT BARCODE (Diperkecil menjadi 24px agar ISBN panjang tidak terpotong ke kanan) */
        .font-barcode {
            font-family: 'Libre Barcode 39 Text', cursive;
            font-size: 24px; /* Sebelumnya 38px, diubah agar aman untuk >16 karakter */
            line-height: 1;
            white-space: nowrap;
            display: inline-block;
        }

        /* LAYOUT KERTAS A4 */
        .page {
            width: 210mm;
            min-height: 297mm;
            padding: 10mm;
            margin: 10mm auto;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            display: flex;
            flex-wrap: wrap;
            gap: 4mm; /* Jarak antar stiker */
            align-content: flex-start;
        }

        @media print {
            body { background: white; margin: 0; padding: 0; }
            .page { margin: 0; box-shadow: none; padding: 10mm; }
            .no-print { display: none !important; }
            .show-border { border: 1px dashed #cbd5e1 !important; }
            .no-border { border: none !important; }
        }

        /* STIKER CONTAINER */
        .sticker {
            width: 90mm;  /* Lebar total stiker gabungan */
            height: 40mm; /* Tinggi stiker */
            display: flex;
            background: white;
            box-sizing: border-box;
            border: 1px dashed #cbd5e1; /* Garis potong bawaan */
        }

        /* BAGIAN PUNGGUNG BUKU (Spine) */
        .spine-label {
            width: 30mm;
            height: 100%;
            border-right: 1px dashed #cbd5e1; /* Pemisah punggung dan barcode */
            padding: 4px;
            display: flex;
            flex-col;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        /* BAGIAN COVER BUKU (Barcode) */
        .cover-label {
            width: 60mm;
            height: 100%;
            padding: 4px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            overflow: hidden; /* Mencegah pelebaran jika ada elemen yang tembus */
        }
    </style>
</head>
<body x-data="{ showBorder: true }">

    <!-- TOOLBAR (Tidak ikut terprint) -->
    <div class="no-print fixed top-0 left-0 right-0 bg-slate-900/90 backdrop-blur-md p-4 flex justify-between items-center z-50 shadow-lg border-b border-slate-700">
        <div class="text-white flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-600/30">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
            </div>
            <div>
                <h1 class="font-bold text-sm">Pratinjau Label Buku</h1>
                <p class="text-[10px] text-slate-400">Gunakan kertas Stiker A4. Sesuaikan printer ke ukuran "Actual Size".</p>
            </div>
        </div>
        
        <div class="flex items-center gap-4">
            <label class="flex items-center gap-2 cursor-pointer text-sm text-slate-300 hover:text-white transition">
                <input type="checkbox" x-model="showBorder" class="rounded bg-slate-700 border-slate-600 text-blue-500 focus:ring-blue-500 w-4 h-4 cursor-pointer">
                <span>Cetak Garis Potong</span>
            </label>
            
            <div class="w-px h-6 bg-slate-700 mx-2"></div>
            
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
        
        <?php $__currentLoopData = $copies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $copy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        
        <?php
            $ddc = substr($copy->book->category->code ?? '000', 0, 3);
            $colorClass = 'border-black'; 
        ?>

        <div class="sticker" :class="showBorder ? 'show-border' : 'no-border'">
            
            
            <div class="spine-label">
                <div class="text-[6px] font-black uppercase tracking-tighter mb-1 text-slate-400">
                    PERPUS
                </div>
                
                
                <h3 class="text-lg font-black text-slate-900 leading-none">
                    <?php echo e($ddc); ?>

                </h3>
                
                
                <h3 class="text-xs font-bold text-slate-900 leading-none mt-1 uppercase font-mono">
                    <?php echo e(substr($copy->book->author ?? 'XXX', 0, 3)); ?>

                </h3>
                
                
                <h3 class="text-xs font-bold text-slate-900 leading-none mt-0.5 lowercase font-mono">
                    <?php echo e(substr($copy->book->title ?? 'x', 0, 1)); ?>

                </h3>
            </div>

            
            <div class="cover-label">
                
                <p class="text-[9px] font-bold text-slate-700 text-center leading-tight mb-1 w-full line-clamp-2 h-6 flex items-center justify-center px-1">
                    <?php echo e($copy->book->title); ?>

                </p>
                
                
                <div class="flex-1 flex items-center justify-center w-full">
                    <span class="font-barcode text-black">
                        *<?php echo e($copy->copy_code); ?>*
                    </span>
                </div>
                
                
                <p class="text-[8.5px] font-mono font-bold text-slate-500 mt-0.5 truncate w-full text-center px-1">
                    <?php echo e($copy->copy_code); ?>

                </p>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

</body>
</html><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/library/tools/print-book-label.blade.php ENDPATH**/ ?>