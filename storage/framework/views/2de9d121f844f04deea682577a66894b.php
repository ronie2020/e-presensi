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
    <?php $__env->startPush('styles'); ?>
    <style>
        .watermark {
            background-image: url('https://upload.wikimedia.org/wikipedia/commons/9/9c/Logo_Tut_Wuri_Handayani.png');
            background-repeat: no-repeat;
            background-position: center;
            background-size: 300px;
            opacity: 0.05; 
            pointer-events: none;
        }

        @media screen {
            .print-area { display: none !important; }
        }

        @media print {
            .screen-area { display: none !important; }
            header, nav, aside, footer { display: none !important; }
            
            body, html, #app, main, .min-h-screen {
                height: auto !important;
                min-height: auto !important;
                overflow: visible !important;
                background-color: white !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            
            .print-area { 
                display: block !important; 
                width: 100% !important; 
            }
            
            @page { size: A4; margin: 10mm; }
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
            
            .page-break { 
                page-break-after: always !important; 
                break-after: page !important; 
            }
        }
    </style>
    <?php $__env->stopPush(); ?>

    
    <div class="screen-area py-6 bg-slate-200 min-h-screen font-sans text-slate-800 flex flex-col items-center">
        <div class="fixed top-6 left-1/2 -translate-x-1/2 z-50 w-[90%] max-w-4xl">
            <div class="bg-slate-900/90 backdrop-blur-xl text-white p-4 rounded-2xl shadow-2xl flex flex-col md:flex-row justify-between items-center gap-4 border border-white/10 ring-1 ring-black/5">
                
                <div class="flex items-center gap-4 w-full md:w-auto">
                    <button onclick="window.close()" 
                       class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center hover:bg-white/20 transition text-white shrink-0"
                       title="Tutup Tab">
                        <i class="ph-bold ph-x text-lg"></i>
                    </button>
                    <div class="min-w-0">
                        <h2 class="font-bold text-sm md:text-base leading-tight truncate">Cetak Semua Rapor</h2>
                        <p class="text-[10px] md:text-xs text-blue-200 font-mono">Kelas <?php echo e($class->name); ?> | <?php echo e(count($reportData)); ?> Siswa</p>
                    </div>
                </div>

                <button onclick="window.print()" class="w-full md:w-auto px-6 py-2.5 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl shadow-lg shadow-blue-600/30 transition flex items-center justify-center gap-2 text-sm">
                    <i class="ph-bold ph-printer text-lg"></i>
                    <span>Cetak Sekarang</span>
                </button>
            </div>
        </div>

        <div class="mt-28 text-center max-w-lg">
            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4 text-blue-500 shadow-lg shadow-blue-500/10">
                <i class="ph-duotone ph-files text-4xl"></i>
            </div>
            <h3 class="text-xl font-black text-slate-800 mb-2">Mode Cetak Massal Aktif</h3>
            <p class="text-sm text-slate-500 font-medium mb-4">
                Memuat data <?php echo e(count($reportData)); ?> siswa. Sistem akan memisahkan rapor masing-masing siswa ke halaman baru.
            </p>
            
            <div class="inline-flex items-center gap-2 text-xs font-bold text-amber-600 bg-amber-50 px-4 py-2 rounded-lg border border-amber-200">
                <i class="ph-bold ph-warning-circle"></i> Pastikan pengaturan margin printer diset ke "Default" atau "None".
            </div>
        </div>
    </div>

    
    <div class="print-area">
        <?php $__currentLoopData = $reportData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div style="position: relative; width: 100%; min-height: 297mm; break-inside: avoid;">
                <div class="watermark" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; z-index: 0;"></div>
                
                <div style="position: relative; z-index: 10;">
                    <?php if (isset($component)) { $__componentOriginaldbe060b656b39bfa57a3f8ec45e341f1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldbe060b656b39bfa57a3f8ec45e341f1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.report-content','data' => ['student' => $data['student'],'semester' => $semester,'year' => $academic_year,'subjects' => $data['subjects'],'record' => $data['record']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('report-content'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['student' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($data['student']),'semester' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($semester),'year' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($academic_year),'subjects' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($data['subjects']),'record' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($data['record'])]); ?>
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
                </div>
            </div>
            
            <?php if(!$loop->last): ?>
                <div class="page-break"></div>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <?php $__env->startPush('scripts'); ?>
    <script>
        window.addEventListener('load', function() {
            // Memberikan jeda sedikit untuk memastikan font/gambar termuat
            setTimeout(() => {
                window.print();
            }, 1000);
        });
    </script>
    <?php $__env->stopPush(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\grades\print-all.blade.php ENDPATH**/ ?>