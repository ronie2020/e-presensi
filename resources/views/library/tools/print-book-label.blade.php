<!DOCTYPE html>
<html lang="id">
<head>
    <title>Cetak Label Buku</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- FONT PENTING: Mengubah text menjadi Barcode --}}
    <link href="https://fonts.googleapis.com/css2?family=Libre+Barcode+39+Text&family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: #e2e8f0; 
            -webkit-print-color-adjust: exact; 
        }

        /* FONT BARCODE */
        .font-barcode {
            font-family: 'Libre Barcode 39 Text', cursive;
            font-size: 42px; /* Ukuran barcode */
            line-height: 1;
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
            grid-template-columns: repeat(3, 1fr); /* 3 Kolom Stiker */
            grid-auto-rows: min-content;
            gap: 4mm; /* Jarak antar stiker */
            align-content: start;
        }

        /* DESAIN SATU STIKER */
        .sticker {
            border: 1px dashed #cbd5e1; /* Garis potong */
            height: 38mm; /* Tinggi standar label sticker */
            display: flex;
            overflow: hidden;
            position: relative;
            background: white;
        }

        .spine-label {
            width: 35%;
            background: #f1f5f9;
            border-right: 1px dashed #94a3b8;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 4px;
        }

        .cover-label {
            width: 65%;
            padding: 6px 10px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        @media print {
            body { background: none; margin: 0; }
            .page { 
                width: 100%; 
                margin: 0; 
                box-shadow: none; 
                padding: 5mm; 
                page-break-after: always;
            }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

    <!-- Tombol Navigasi (Hilang saat print) -->
    <div class="no-print fixed top-0 left-0 w-full bg-white/80 backdrop-blur-md border-b border-slate-200 p-4 flex justify-between items-center z-50">
        <div>
            <h1 class="font-bold text-slate-800">Preview Cetak Label Buku</h1>
            <p class="text-xs text-slate-500">Total: {{ $books->count() }} Label</p>
        </div>
        <div class="flex gap-3">
            <button onclick="window.print()" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-xl font-bold shadow-lg transition flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 256 256"><path d="M216,40H40A16,16,0,0,0,24,56V160a16,16,0,0,0,16,16H56v48a16,16,0,0,0,16,16H184a16,16,0,0,0,16-16V176h32a16,16,0,0,0,16-16V56A16,16,0,0,0,216,40ZM184,224H72V152H184Zm32-64H200V144a8,8,0,0,0-8-8H64a8,8,0,0,0-8,8v16H40V56H216Zm-32-80a12,12,0,1,1,12,12A12,12,0,0,1,184,80Z"></path></svg>
                Cetak
            </button>
            <button onclick="window.close()" class="bg-slate-100 text-slate-600 px-4 py-2 rounded-xl font-bold hover:bg-slate-200 transition">
                Tutup
            </button>
        </div>
    </div>

    <!-- AREA KERTAS -->
    <div class="page mt-20">
        @foreach($books as $book)
        <div class="sticker">
            {{-- BAGIAN KIRI: LABEL PUNGGUNG (Untuk Rak) --}}
            <div class="spine-label">
                <span class="text-[8px] font-bold text-slate-500 uppercase tracking-tighter mb-0.5">PERPUS</span>
                
                {{-- Klasifikasi DDC (Contoh: 813) --}}
                <h3 class="text-lg font-black text-slate-900 leading-none">
                    {{ substr($book->category->code ?? '000', 0, 3) }}
                </h3>
                
                {{-- 3 Huruf Pengarang (Contoh: ROW) --}}
                <h3 class="text-xs font-bold text-slate-700 leading-none mt-1 uppercase">
                    {{ substr($book->author ?? 'XXX', 0, 3) }}
                </h3>
                
                {{-- Huruf Awal Judul (Contoh: h) --}}
                <h3 class="text-xs font-bold text-slate-500 leading-none mt-1 lowercase">
                    {{ substr($book->title ?? 'x', 0, 1) }}
                </h3>
            </div>

            {{-- BAGIAN KANAN: BARCODE & JUDUL --}}
            <div class="cover-label">
                <p class="text-[9px] font-bold text-slate-600 truncate w-full mb-1 text-center">
                    {{ \Illuminate\Support\Str::limit($book->title, 20) }}
                </p>
                
                {{-- BARCODE GENERATOR (Font Based) --}}
                <div class="flex-1 flex items-center justify-center w-full overflow-hidden">
                    <span class="font-barcode text-slate-900 text-4xl">
                        {{-- Tambahkan * di awal & akhir agar discan valid Code39 --}}
                        *{{ $book->book_code }}*
                    </span>
                </div>
                <p class="text-[8px] font-mono text-slate-400 mt-1">{{ $book->book_code }}</p>
            </div>
        </div>
        @endforeach
    </div>

</body>
</html>