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
        @media print {
            @page { size: A4; margin: 10mm; }
            body > *:not(.print-wrapper) { display: none !important; }
            .print-wrapper { display: block !important; background: white; width: 100%; height: 100%; }
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
            tr, td, th, .avoid-break { page-break-inside: avoid !important; }
            .no-print { display: none !important; }
        }
    </style>
    <?php $__env->stopPush(); ?>

    
    <div class="py-6 bg-slate-200 min-h-screen no-print font-sans text-slate-800 overflow-hidden"
         x-data="{ 
            scale: 1,
            zoomIn() { if(this.scale < 1.5) this.scale += 0.1 },
            zoomOut() { if(this.scale > 0.5) this.scale -= 0.1 },
            resetZoom() { this.scale = 1 }
         }">
        
        
        <div class="fixed top-6 left-1/2 -translate-x-1/2 z-50 w-[90%] max-w-4xl">
            <div class="bg-slate-900/90 backdrop-blur-xl text-white p-3 rounded-2xl shadow-2xl flex flex-col md:flex-row justify-between items-center gap-4 border border-white/10 ring-1 ring-black/5">
                
                
                <div class="flex items-center gap-4 w-full md:w-auto">
                    <a href="<?php echo e(route('grades.list', ['class_id' => $student->class_id, 'academic_year' => $year, 'semester' => $semester])); ?>" 
                       class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center hover:bg-white/20 transition text-white shrink-0"
                       title="Kembali ke Daftar">
                        <i class="ph-bold ph-arrow-left text-lg"></i>
                    </a>
                    <div class="min-w-0">
                        <h2 class="font-bold text-sm md:text-base leading-tight truncate"><?php echo e($student->name); ?></h2>
                        <p class="text-[10px] md:text-xs text-blue-200 font-mono"><?php echo e($student->student_id); ?> | Kelas <?php echo e($student->schoolClass->name ?? '-'); ?></p>
                    </div>
                </div>

                
                <div class="flex items-center gap-2 bg-black/20 p-1 rounded-xl">
                    
                    
                    <div class="flex items-center mr-2 border-r border-white/10 pr-2 gap-1">
                        
                        <?php if(isset($prevStudentId) && $prevStudentId): ?>
                            <a href="<?php echo e(route('grades.report', ['student_id' => $prevStudentId, 'year' => $year, 'semester' => $semester])); ?>" 
                               class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-white/10 text-white transition"
                               title="Siswa Sebelumnya">
                                <i class="ph-bold ph-caret-left"></i>
                            </a>
                        <?php else: ?>
                            <button disabled class="w-8 h-8 flex items-center justify-center rounded-lg text-white/30 cursor-not-allowed">
                                <i class="ph-bold ph-caret-left"></i>
                            </button>
                        <?php endif; ?>

                        <span class="text-xs font-bold text-slate-400 select-none">Navigasi</span>

                        
                        <?php if(isset($nextStudentId) && $nextStudentId): ?>
                            <a href="<?php echo e(route('grades.report', ['student_id' => $nextStudentId, 'year' => $year, 'semester' => $semester])); ?>" 
                               class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-white/10 text-white transition"
                               title="Siswa Selanjutnya">
                                <i class="ph-bold ph-caret-right"></i>
                            </a>
                        <?php else: ?>
                            <button disabled class="w-8 h-8 flex items-center justify-center rounded-lg text-white/30 cursor-not-allowed">
                                <i class="ph-bold ph-caret-right"></i>
                            </button>
                        <?php endif; ?>
                    </div>

                    
                    <button @click="zoomOut()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-white/10 text-white transition"><i class="ph-bold ph-minus"></i></button>
                    <span class="text-xs font-mono w-12 text-center select-none" x-text="Math.round(scale * 100) + '%'"></span>
                    <button @click="zoomIn()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-white/10 text-white transition"><i class="ph-bold ph-plus"></i></button>
                </div>
                
                
                <button onclick="window.print()" class="w-full md:w-auto px-5 py-2 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl shadow-lg shadow-blue-600/30 transition flex items-center justify-center gap-2 text-sm">
                    <i class="ph-bold ph-printer text-lg"></i>
                    <span class="hidden sm:inline">Cetak / PDF</span>
                </button>
            </div>
        </div>

        
        <div class="mt-24 pb-20 overflow-auto flex justify-center custom-scrollbar h-[calc(100vh-100px)]">
            <div class="transition-transform duration-200 origin-top" :style="`transform: scale(${scale})`">
                
                
                <div class="bg-white w-[210mm] min-h-[297mm] p-[15mm] md:p-[20mm] shadow-2xl relative text-slate-900 font-serif mx-auto">
                    
                    
                    <div class="absolute inset-0 watermark z-0"></div>

                    
                    <div class="relative z-10">
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

                        
                        <div class="mt-12 pt-4 border-t border-slate-200 flex justify-between items-end text-[10px] text-slate-400 no-print">
                            <div>
                                <p>Dicetak pada: <?php echo e(now()->format('d/m/Y H:i')); ?></p>
                                <p>Oleh: <?php echo e(Auth::user()->name); ?></p>
                            </div>
                            <div class="text-right flex flex-col items-center gap-1">
                                <div class="w-12 h-12 bg-slate-100 border border-slate-200 flex items-center justify-center rounded">
                                    <i class="ph-duotone ph-qr-code text-2xl"></i>
                                </div>
                                <span>Dokumen Valid</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    
    <div class="print-wrapper hidden">
        <div class="watermark fixed inset-0 z-0"></div>
        <div class="relative z-10">
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
<?php endif; ?><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/grades/report.blade.php ENDPATH**/ ?>