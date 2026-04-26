<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Rapor - {{ $student->name }}</title>

    {{-- Load Tailwind CSS & Alpine JS bawaan Laravel --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Phosphor Icons --}}
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        /* PENGATURAN KERTAS A4 */
        @page { 
            size: A4 portrait; 
            margin: 15mm 20mm; 
        }
        
        body {
            background-color: #f1f5f9; /* Warna latar luar (Slate 100) */
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* TAMPILAN KERTAS DI LAYAR (Preview Mode) */
        .sheet {
            background: white;
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 15mm 20mm;
            box-sizing: border-box;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            position: relative;
        }

        /* Watermark Transparan di Tengah Kertas */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 400px;
            height: 400px;
            background-image: url('https://upload.wikimedia.org/wikipedia/commons/9/9c/Logo_Tut_Wuri_Handayani.png');
            background-repeat: no-repeat;
            background-position: center;
            background-size: contain;
            opacity: 0.05;
            z-index: 0;
            pointer-events: none;
        }

        /* MODIFIKASI SAAT DICETAK (PRINT MODE) */
        @media print {
            body { background: none; margin: 0; padding: 0; }
            .no-print { display: none !important; }
            
            .sheet { 
                width: 100% !important; 
                max-width: 100% !important;
                margin: 0 !important; 
                padding: 0 !important; 
                box-shadow: none !important; 
                border: none !important;
                min-height: auto !important;
                box-sizing: border-box !important;
            }

            .watermark { position: fixed !important; }

            .zoom-wrapper {
                transform: none !important;
                width: 100% !important;
                margin: 0 !important;
            }

            table { page-break-inside: auto !important; width: 100% !important; }
            tr, td, th { page-break-inside: avoid !important; break-inside: avoid !important; }
            thead { display: table-header-group !important; }
            tfoot { display: table-footer-group !important; }

            div[class*="border"], div[class*="flex"] {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }
            
            .h-full, .flex-grow {
                height: auto !important;
                flex-grow: 0 !important;
            }
        }
    </style>
</head>

<body class="font-sans text-slate-800" 
      x-data="{ 
          scale: 1, 
          zoomIn() { if(this.scale < 1.5) this.scale += 0.1 }, 
          zoomOut() { if(this.scale > 0.5) this.scale -= 0.1 } 
      }">

    <!-- TOOLBAR ATAS (Hanya Tampil di Layar) -->
    <div class="no-print fixed top-0 left-0 right-0 bg-white/90 backdrop-blur-md border-b border-slate-200 shadow-sm p-4 flex flex-col md:flex-row justify-between items-center gap-4 z-50">
        
        <!-- Kiri: Info Siswa -->
        <div class="flex items-center gap-4 w-full md:w-auto">
            <div class="bg-[#0d52a1] p-2.5 rounded-xl text-white shadow-lg shadow-[#0d52a1]/20">
                <i class="ph-bold ph-student text-xl"></i>
            </div>
            <div>
                <h1 class="font-black text-[#2c3f61] text-sm md:text-base font-sans">Pratinjau E-Rapor</h1>
                <p class="text-xs text-[#2c3f61]/70 font-sans font-bold">{{ $student->name }} | NISN: {{ $student->student_id }}</p>
            </div>
        </div>

        <!-- Tengah: Navigasi Prev/Next & Zoom -->
        <div class="flex items-center gap-2 bg-[#e5eff5]/50 p-1.5 rounded-xl border border-slate-200 w-full md:w-auto justify-center shadow-inner">
            <!-- Navigasi Siswa -->
            <div class="flex items-center mr-2 border-r border-slate-300 pr-2 gap-1">
                @if(isset($prevStudentId) && $prevStudentId)
                    <a href="{{ route('grades.report', ['student_id' => $prevStudentId, 'year' => $year, 'semester' => $semester]) }}" 
                       class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-white text-slate-600 hover:text-[#0d52a1] transition shadow-sm border border-transparent hover:border-slate-200" title="Siswa Sebelumnya">
                        <i class="ph-bold ph-caret-left"></i>
                    </a>
                @else
                    <button disabled class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 cursor-not-allowed">
                        <i class="ph-bold ph-caret-left"></i>
                    </button>
                @endif
                
                <span class="text-[10px] font-bold text-[#2c3f61]/60 uppercase tracking-wider mx-1">Navigasi</span>
                
                @if(isset($nextStudentId) && $nextStudentId)
                    <a href="{{ route('grades.report', ['student_id' => $nextStudentId, 'year' => $year, 'semester' => $semester]) }}" 
                       class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-white text-slate-600 hover:text-[#0d52a1] transition shadow-sm border border-transparent hover:border-slate-200" title="Siswa Selanjutnya">
                        <i class="ph-bold ph-caret-right"></i>
                    </a>
                @else
                    <button disabled class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 cursor-not-allowed">
                        <i class="ph-bold ph-caret-right"></i>
                    </button>
                @endif
            </div>

            <!-- Kontrol Zoom -->
            <button @click="zoomOut()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-white text-slate-600 transition shadow-sm border border-transparent hover:border-slate-200">
                <i class="ph-bold ph-minus"></i>
            </button>
            <span class="text-xs font-mono font-bold text-[#2c3f61] w-12 text-center select-none" x-text="Math.round(scale * 100) + '%'"></span>
            <button @click="zoomIn()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-white text-slate-600 transition shadow-sm border border-transparent hover:border-slate-200">
                <i class="ph-bold ph-plus"></i>
            </button>
        </div>

        <!-- Kanan: Tombol Aksi -->
        <div class="flex gap-3 w-full md:w-auto justify-end">
            <a href="{{ route('grades.list', ['class_id' => $student->class_id, 'academic_year' => $year, 'semester' => $semester]) }}" class="px-5 py-2.5 text-xs font-bold text-[#2c3f61] bg-white border border-[#2c3f61] rounded-xl hover:bg-slate-50 transition shadow-sm font-sans flex items-center gap-2">
                <i class="ph-bold ph-arrow-left"></i> Kembali
            </a>
            <button onclick="window.print()" class="px-5 py-2.5 text-xs font-bold text-white bg-[#2c3f61] rounded-xl hover:bg-[#1c2940] transition shadow-lg shadow-[#2c3f61]/20 font-sans flex items-center gap-2">
                <i class="ph-bold ph-printer text-lg"></i> Cetak / PDF
            </button>
        </div>
    </div>

    <!-- Spacer -->
    <div class="no-print h-32 md:h-24"></div>

    <!-- AREA DOKUMEN / KERTAS -->
    <div class="flex justify-center w-full pb-16">
        
        <div class="zoom-wrapper origin-top transition-transform duration-200" :style="`transform: scale(${scale})`">
            
            <div class="sheet font-serif text-slate-900">
                
                {{-- Watermark --}}
                <div class="watermark"></div>

                {{-- Konten Rapor (Diimpor dari komponen) --}}
                <div class="relative z-10 w-full">
                    <x-report-content 
                        :student="$student" 
                        :semester="$semester" 
                        :year="$year" 
                        :subjects="$subjects" 
                        :record="$record" 
                    />

                    {{-- Footer Validasi QR Code --}}
                    <div class="mt-12 pt-4 border-t border-slate-400 flex justify-between items-end text-[10px] text-slate-600 font-sans">
                        <div>
                            <p>Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
                            <p>Oleh: {{ Auth::user()->name ?? 'Sistem' }}</p>
                        </div>
                        <div class="text-right flex flex-col items-center gap-1">
                            <div class="w-12 h-12 border border-slate-400 flex items-center justify-center rounded">
                                <i class="ph-bold ph-qr-code text-2xl"></i>
                            </div>
                            <span>Dokumen Valid</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>