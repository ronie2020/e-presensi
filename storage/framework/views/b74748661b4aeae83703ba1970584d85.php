<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Rapor - <?php echo e($student->name); ?></title>

    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        /* PENGATURAN KERTAS A4 */
        @page { 
            size: A4 portrait; 
            /* [PERBAIKAN] Margin diset di sini agar BERULANG di setiap lembar halaman (Atas/Bawah 15mm, Kiri/Kanan 20mm) */
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

        /* ========================================================= */
        /* MODIFIKASI SAAT DICETAK (PRINT MODE)                      */
        /* ========================================================= */
        @media print {
            body { background: none; margin: 0; padding: 0; }
            .no-print { display: none !important; }
            
            /* Lepaskan format kertas agar browser leluasa memotong halaman */
            .sheet { 
                width: 100% !important; 
                max-width: 100% !important;
                margin: 0 !important; 
                /* [KUNCI] Padding dinolkan saat print karena margin @page sudah mengambil alih tugasnya */
                padding: 0 !important; 
                box-shadow: none !important; 
                border: none !important;
                min-height: auto !important;
                box-sizing: border-box !important;
            }

            /* Watermark dibuat berulang di setiap halaman */
            .watermark {
                position: fixed !important;
            }

            /* Matikan efek zoom saat print agar dokumen tidak menjadi gambar beku */
            .zoom-wrapper {
                transform: none !important;
                width: 100% !important;
                margin: 0 !important;
            }

            /* PERBAIKAN FRAGMENTASI TABEL & KOTAK */
            table { page-break-inside: auto !important; width: 100% !important; }
            tr, td, th { page-break-inside: avoid !important; break-inside: avoid !important; }
            thead { display: table-header-group !important; }
            tfoot { display: table-footer-group !important; }

            /* Mencegah Kotak Catatan / Area TTD terpotong pisah halaman */
            div[class*="border"], div[class*="flex"] {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }
            
            /* Mematikan sifat stretch flexbox yang merusak spasi TTD */
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

    <!-- TOOLBAR ATAS (Hanya Tampil di Layar, Mirip desain print.blade.php) -->
    <div class="no-print fixed top-0 left-0 right-0 bg-white/90 backdrop-blur-md border-b border-slate-200 shadow-sm p-4 flex flex-col md:flex-row justify-between items-center gap-4 z-50">
        
        <!-- Kiri: Info Siswa -->
        <div class="flex items-center gap-4 w-full md:w-auto">
            <div class="bg-blue-600 p-2.5 rounded-xl text-white shadow-lg shadow-blue-600/20">
                <i class="ph-bold ph-student text-xl"></i>
            </div>
            <div>
                <h1 class="font-black text-slate-800 text-sm md:text-base font-sans">Pratinjau E-Rapor</h1>
                <p class="text-xs text-slate-500 font-sans font-bold"><?php echo e($student->name); ?> | NISN: <?php echo e($student->student_id); ?></p>
            </div>
        </div>

        <!-- Tengah: Navigasi Prev/Next & Zoom -->
        <div class="flex items-center gap-2 bg-slate-100 p-1.5 rounded-xl border border-slate-200 w-full md:w-auto justify-center shadow-inner">
            <!-- Navigasi Siswa -->
            <div class="flex items-center mr-2 border-r border-slate-300 pr-2 gap-1">
                <?php if(isset($prevStudentId) && $prevStudentId): ?>
                    <a href="<?php echo e(route('grades.report', ['student_id' => $prevStudentId, 'year' => $year, 'semester' => $semester])); ?>" 
                       class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-white text-slate-600 hover:text-blue-600 transition shadow-sm border border-transparent hover:border-slate-200" title="Siswa Sebelumnya">
                        <i class="ph-bold ph-caret-left"></i>
                    </a>
                <?php else: ?>
                    <button disabled class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 cursor-not-allowed">
                        <i class="ph-bold ph-caret-left"></i>
                    </button>
                <?php endif; ?>
                
                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mx-1">Navigasi</span>
                
                <?php if(isset($nextStudentId) && $nextStudentId): ?>
                    <a href="<?php echo e(route('grades.report', ['student_id' => $nextStudentId, 'year' => $year, 'semester' => $semester])); ?>" 
                       class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-white text-slate-600 hover:text-blue-600 transition shadow-sm border border-transparent hover:border-slate-200" title="Siswa Selanjutnya">
                        <i class="ph-bold ph-caret-right"></i>
                    </a>
                <?php else: ?>
                    <button disabled class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 cursor-not-allowed">
                        <i class="ph-bold ph-caret-right"></i>
                    </button>
                <?php endif; ?>
            </div>

            <!-- Kontrol Zoom -->
            <button @click="zoomOut()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-white text-slate-600 transition shadow-sm border border-transparent hover:border-slate-200">
                <i class="ph-bold ph-minus"></i>
            </button>
            <span class="text-xs font-mono font-bold text-slate-600 w-12 text-center select-none" x-text="Math.round(scale * 100) + '%'"></span>
            <button @click="zoomIn()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-white text-slate-600 transition shadow-sm border border-transparent hover:border-slate-200">
                <i class="ph-bold ph-plus"></i>
            </button>
        </div>

        <!-- Kanan: Tombol Aksi -->
        <div class="flex gap-3 w-full md:w-auto justify-end">
            <a href="<?php echo e(route('grades.list', ['class_id' => $student->class_id, 'academic_year' => $year, 'semester' => $semester])); ?>" class="px-5 py-2.5 text-xs font-bold text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 hover:text-slate-800 transition shadow-sm font-sans flex items-center gap-2">
                <i class="ph-bold ph-arrow-left"></i> Kembali
            </a>
            <button onclick="window.print()" class="px-5 py-2.5 text-xs font-bold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-600/30 font-sans flex items-center gap-2">
                <i class="ph-bold ph-printer text-lg"></i> Cetak / PDF
            </button>
        </div>
    </div>

    <!-- Spacer untuk memberikan jarak karena toolbar berposisi fixed -->
    <div class="no-print h-32 md:h-24"></div>

    <!-- AREA DOKUMEN / KERTAS -->
    <div class="flex justify-center w-full pb-16">
        
        <!-- Wrapper AlpineJS untuk memproses Zoom Layar -->
        <div class="zoom-wrapper origin-top transition-transform duration-200" :style="`transform: scale(${scale})`">
            
            <!-- Elemen Utama Kertas -->
            <div class="sheet font-serif text-slate-900">
                
                
                <div class="watermark"></div>

                
                <div class="relative z-10 w-full">
                    <?php if (isset($component)) { $__componentOriginaldbe060b656b39bfa57a3f8ec45e341f1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldbe060b656b39bfa57a3f8ec45e341f1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.report-content','data' => ['student' => $student,'semester' => $semester,'year' => $year,'subjects' => $subjects,'record' => $record]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('report-content'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['student' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($student),'semester' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($semester),'year' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($year),'subjects' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($subjects),'record' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($record)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldbe060b656b39bfa57a3f8ec45e341f1)): ?>
<?php $attributes = $__attributesOriginaldbe060b656b39bfa57a3f8ec45e341f1; ?>
<?php unset($__attributesOriginaldbe060b656b39bfa57a3f8ec45e341f1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldbe060b656b39bfa57a3f8ec45e341f1)): ?>
<?php $component = $__componentOriginaldbe060b656b39bfa57a3f8ec45e341f1; ?>
<?php unset($__componentOriginaldbe060b656b39bfa57a3f8ec45e341f1); ?>
<?php endif; ?>

                    
                    <div class="mt-12 pt-4 border-t border-slate-400 flex justify-between items-end text-[10px] text-slate-600 font-sans">
                        <div>
                            <p>Dicetak pada: <?php echo e(now()->format('d/m/Y H:i')); ?></p>
                            <p>Oleh: <?php echo e(Auth::user()->name ?? 'Sistem'); ?></p>
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
</html><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/grades/report.blade.php ENDPATH**/ ?>