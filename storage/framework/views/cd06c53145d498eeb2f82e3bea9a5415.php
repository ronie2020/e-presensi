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
    
    <script>
        window.MathJax = {
            tex: { inlineMath: [['$', '$'], ['\\(', '\\)']] },
            svg: { fontCache: 'global' },
            startup: {
                ready: () => {
                    MathJax.startup.defaultReady();
                    window.renderMath = () => { MathJax.typesetPromise(); };
                }
            }
        };
    </script>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>

     <?php $__env->slot('header', null, []); ?> 
        <div class="flex items-center justify-between print:hidden">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                <?php echo e(__('Detail Hasil Ujian')); ?>

            </h2>
            <button onclick="window.print()" class="text-sm font-bold text-slate-500 hover:text-blue-600 flex items-center gap-2 transition bg-white px-4 py-2 rounded-xl border border-slate-200 shadow-sm">
                <i class="ph-bold ph-printer text-lg"></i> Cetak Hasil
            </button>
        </div>
     <?php $__env->endSlot(); ?>

    
    <style>
        @media print {
            body { background: white; -webkit-print-color-adjust: exact; }
            .no-print { display: none !important; }
            .print-break { page-break-inside: avoid; }
            ::-webkit-scrollbar { display: none; }
        }
    </style>

    <div class="py-8 sm:py-10 font-sans text-slate-800">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden print:shadow-none print:border-black print:rounded-none">
                <div class="relative p-8 overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-indigo-50 to-blue-50 rounded-bl-full opacity-50 pointer-events-none print:hidden"></div>
                    
                    <div class="relative z-10 flex flex-col md:flex-row gap-8 items-center md:items-start justify-between">
                        <div class="text-center md:text-left">
                            <div class="flex items-center gap-2 justify-center md:justify-start mb-2 no-print">
                                <a href="<?php echo e(route('cbt.recap', $exam->id)); ?>" class="text-xs font-bold text-slate-400 hover:text-indigo-600 transition flex items-center gap-1">
                                    <i class="ph-bold ph-arrow-left"></i> Kembali ke Rekap
                                </a>
                            </div>
                            <h1 class="text-3xl font-black text-slate-800 mb-1"><?php echo e($student->name); ?></h1>
                            <p class="text-slate-500 font-medium mb-4"><?php echo e($student->schoolClass->name ?? 'Kelas -'); ?> • NISN: <?php echo e($student->student_id ?? '-'); ?></p>
                            
                            <div class="inline-flex flex-col items-start gap-1">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Ujian</span>
                                <span class="font-bold text-slate-700"><?php echo e($exam->title); ?> (<?php echo e($exam->subject_name); ?>)</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-6">
                            
                            <div class="relative w-32 h-32 flex items-center justify-center rounded-full border-8 <?php echo e($examSession->total_score >= $exam->passing_grade ? 'border-emerald-100 bg-emerald-50' : 'border-rose-100 bg-rose-50'); ?> print:border-black print:bg-white transition-colors duration-500">
                                <div class="text-center">
                                    <span id="displayTotalScore" class="block text-4xl font-black <?php echo e($examSession->total_score >= $exam->passing_grade ? 'text-emerald-600' : 'text-rose-600'); ?> print:text-black transition-colors duration-500">
                                        <?php echo e($examSession->total_score); ?>

                                    </span>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 print:text-black">Nilai Akhir</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="space-y-6">
                <div class="flex items-center gap-2 mb-4 px-2 print:hidden">
                    <i class="ph-fill ph-list-magnifying-glass text-indigo-500 text-xl"></i>
                    <h3 class="font-bold text-slate-700 text-lg">Analisis & Koreksi Soal</h3>
                </div>

                <?php $__empty_1 = true; $__currentLoopData = $answers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        // Deteksi Tipe & Data
                        $qType = $item->question_type ?? 'choice';
                        $studentAns = trim($item->student_answer ?? '');
                        $correctAns = trim($item->correct_answer ?? '');
                        
                        $isSkipped = is_null($studentAns) || $studentAns === '';
                        $isCorrect = false;

                        // 1. Evaluasi Kebenaran Jawaban
                        if ($qType == 'choice' || $qType == 'true_false') {
                            $isCorrect = strtoupper($studentAns) === strtoupper($correctAns);
                        } elseif ($qType == 'matching') {
                            $keyMap = json_decode($correctAns, true) ?? [];
                            $studentMap = json_decode($studentAns, true) ?? [];
                            if (is_array($keyMap)) ksort($keyMap);
                            if (is_array($studentMap)) ksort($studentMap);
                            $isCorrect = (!empty($keyMap) && $keyMap == $studentMap);
                        }

                        // 2. Kalkulasi Skor Per Soal (PERBAIKAN BUG)
                        if ($qType == 'essay') {
                            // Essai: Ambil dari skor manual guru di DB
                            $currentScore = $item->score ?? 0;
                            $isCorrect = $currentScore > 0; // Tampilkan visual hijau jika dapat nilai
                        } else {
                            // Pilihan Ganda / True-False / Matching: Dapatkan skor otomatis dari bobot jika benar
                            $currentScore = $isCorrect ? ($item->score_weight ?? 0) : 0;
                        }
                    ?>

                    
                    <div class="bg-white rounded-[2rem] border <?php echo e($isCorrect ? 'border-emerald-100' : ($isSkipped ? 'border-slate-200' : ($qType == 'essay' ? 'border-indigo-100' : 'border-rose-100'))); ?> p-6 shadow-sm relative overflow-hidden print-break print:border-black print:rounded-none"
                         x-data="{ 
                            manualScore: <?php echo e($item->score ?? 0); ?>, 
                            maxScore: <?php echo e($item->score_weight); ?>,
                            isSaving: false 
                         }">
                        
                        
                        <div class="absolute left-0 top-0 bottom-0 w-1.5 <?php echo e($isCorrect ? 'bg-emerald-400' : ($isSkipped ? 'bg-slate-300' : ($qType == 'essay' ? 'bg-indigo-400' : 'bg-rose-400'))); ?> print:border-r print:border-black"></div>

                        
                        <div class="flex justify-between items-start mb-4 pl-4">
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg flex items-center justify-center font-black text-sm <?php echo e($isCorrect ? 'bg-emerald-100 text-emerald-700' : ($isSkipped ? 'bg-slate-100 text-slate-500' : ($qType == 'essay' ? 'bg-indigo-100 text-indigo-700' : 'bg-rose-100 text-rose-700'))); ?> print:border print:border-black print:bg-white print:text-black">
                                    <?php echo e($index + 1); ?>

                                </span>
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">
                                        Bobot Maks: <?php echo e($item->score_weight); ?>

                                    </span>
                                    <span class="text-[10px] font-bold text-slate-300 uppercase">
                                        <?php echo e($qType == 'choice' ? 'Pilihan Ganda' : ($qType == 'essay' ? 'Essai' : ucfirst($qType))); ?>

                                    </span>
                                </div>
                            </div>
                            
                            
                            <div class="text-right">
                                <span class="block text-2xl font-black <?php echo e($currentScore > 0 ? 'text-emerald-600' : 'text-slate-300'); ?> transition-colors">
                                    <?php echo e(floatval($currentScore)); ?>

                                </span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase">Nilai Diperoleh</span>
                            </div>
                        </div>

                        
                        <div class="pl-4 mb-6">
                            <?php if($item->question_image): ?>
                                <img src="<?php echo e(asset('storage/' . $item->question_image)); ?>" class="max-h-48 rounded-xl border border-slate-100 mb-4 object-contain print:border-black">
                            <?php endif; ?>
                            <div class="text-slate-800 font-medium text-base leading-relaxed prose prose-sm max-w-none">
                                <?php echo $item->question_text; ?>

                            </div>
                        </div>

                        
                        <div class="pl-4">
                            
                            
                            <?php if($qType == 'choice' || $qType == 'true_false'): ?>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <?php $__currentLoopData = ['A','B','C','D', 'E']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $optionText = $item->{'option_'.$opt} ?? null;
                                            if(!$optionText && $qType == 'true_false' && ($opt == 'A' || $opt == 'B')) {
                                                 $optionText = ($opt == 'A') ? 'Benar' : 'Salah';
                                            }
                                        ?>
                                        <?php if($optionText): ?>
                                            <?php
                                                $isKey = $opt == $item->correct_answer;
                                                $isStudentChoice = $opt == $item->student_answer;
                                                $bgClass = $isKey ? 'bg-emerald-50 border-emerald-200 text-emerald-800 ring-1 ring-emerald-200' 
                                                         : ($isStudentChoice ? 'bg-rose-50 border-rose-200 text-rose-800 ring-1 ring-rose-200' : 'bg-white border-slate-100 text-slate-600');
                                            ?>
                                            <div class="flex items-start gap-3 p-3 rounded-xl border text-sm transition-colors <?php echo e($bgClass); ?> print:border-black print:bg-white print:text-black">
                                                <div class="font-black text-xs pt-0.5 shrink-0 w-5"><?php echo e($opt); ?>.</div>
                                                <div class="flex-1"><?php echo e($optionText); ?></div>
                                                <?php if($isKey): ?> <i class="ph-fill ph-check-circle text-emerald-500 text-lg"></i>
                                                <?php elseif($isStudentChoice): ?> <i class="ph-fill ph-x-circle text-rose-500 text-lg"></i>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>

                            
                            <?php elseif($qType == 'essay'): ?>
                                <div class="space-y-4">
                                    
                                    <div class="p-5 rounded-2xl border bg-indigo-50/50 border-indigo-100">
                                        <p class="text-[10px] font-bold text-indigo-400 uppercase mb-2">Jawaban Siswa:</p>
                                        <p class="font-medium text-slate-800 whitespace-pre-wrap leading-relaxed text-sm"><?php echo e($item->student_answer ?: '(Tidak dijawab)'); ?></p>
                                    </div>
                                    
                                    
                                    <div class="p-4 rounded-xl border bg-amber-50 border-amber-200 border-dashed print:hidden">
                                        <p class="text-[10px] font-bold text-amber-600 uppercase mb-1 flex items-center gap-1"><i class="ph-bold ph-key"></i> Kunci Jawaban (Referensi):</p>
                                        <p class="font-medium text-slate-700 whitespace-pre-wrap text-sm"><?php echo e($item->correct_answer ?: '-'); ?></p>
                                    </div>

                                    
                                    <div class="flex items-center gap-4 bg-white p-4 rounded-xl border border-slate-200 shadow-sm print:hidden">
                                        <div class="flex-1">
                                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Berikan Nilai Manual:</label>
                                            <div class="flex items-center gap-2">
                                                <input type="number" x-model="manualScore" :max="maxScore" min="0" step="0.1"
                                                       @keydown.enter="saveEssayScore(<?php echo e($item->answer_id); ?>, manualScore)"
                                                       class="w-24 font-bold text-center rounded-lg border-slate-300 focus:ring-indigo-500 focus:border-indigo-500">
                                                <span class="text-sm font-bold text-slate-400">/ <span x-text="maxScore"></span> Poin</span>
                                            </div>
                                        </div>
                                        <button @click="saveEssayScore(<?php echo e($item->answer_id); ?>, manualScore)" 
                                                :disabled="isSaving"
                                                class="px-5 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/30 transition flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                            <i class="ph-bold" :class="isSaving ? 'ph-spinner animate-spin' : 'ph-floppy-disk'"></i>
                                            <span x-text="isSaving ? 'Menyimpan...' : 'Simpan Nilai'"></span>
                                        </button>
                                    </div>
                                </div>

                            
                            <?php elseif($qType == 'matching'): ?>
                                <?php
                                    $studentPairs = json_decode($item->student_answer, true) ?? [];
                                    $correctPairs = json_decode($item->correct_answer, true) ?? []; 
                                ?>
                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase mb-2">Pencocokan Jawaban:</p>
                                    <div class="space-y-2">
                                        <?php $__currentLoopData = $correctPairs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $left => $right): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $studentSelected = $studentPairs[$left] ?? '-';
                                                $isMatch = strtoupper($studentSelected) == strtoupper($right);
                                            ?>
                                            <div class="flex flex-col sm:flex-row gap-2 items-center bg-white p-2 rounded-lg border <?php echo e($isMatch ? 'border-emerald-200' : 'border-rose-200'); ?>">
                                                <div class="flex-1 text-sm font-bold text-slate-700 text-center sm:text-left"><?php echo e($left); ?></div>
                                                <i class="ph-bold ph-arrow-right text-slate-300"></i>
                                                <div class="flex-1 text-sm text-center sm:text-right font-bold <?php echo e($isMatch ? 'text-emerald-600' : 'text-rose-500 line-through'); ?>">
                                                    <?php echo e($studentSelected); ?>

                                                </div>
                                                <?php if(!$isMatch): ?>
                                                    <div class="text-xs bg-emerald-100 text-emerald-700 px-2 py-1 rounded"><?php echo e($right); ?></div>
                                                <?php else: ?>
                                                    <i class="ph-fill ph-check-circle text-emerald-500"></i>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-12 bg-white rounded-[2rem] border border-slate-100">
                        <p class="text-slate-500 font-bold">Data jawaban tidak ditemukan.</p>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>

    
    <?php $__env->startPush('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function saveEssayScore(answerId, score) {
            if (score < 0) return Swal.fire('Error', 'Nilai tidak boleh minus', 'error');

            fetch("<?php echo e(route('cbt.grade_essay')); ?>", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ answer_id: answerId, score: score })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Tersimpan!',
                        text: 'Nilai berhasil diperbarui.',
                        toast: true, position: 'top-end', showConfirmButton: false, timer: 2000
                    });
                    
                    // Update Total Score di Header secara Real-time
                    const displayTotal = document.getElementById('displayTotalScore');
                    if(displayTotal) {
                        displayTotal.innerText = data.new_total;
                        
                        // Update warna lingkaran jika melewati KKM secara Live
                        const kkm = <?php echo e($exam->passing_grade ?? 0); ?>;
                        const circleContainer = displayTotal.closest('.border-8');
                        if (data.new_total >= kkm) {
                            displayTotal.classList.replace('text-rose-600', 'text-emerald-600');
                            circleContainer.classList.replace('border-rose-100', 'border-emerald-100');
                            circleContainer.classList.replace('bg-rose-50', 'bg-emerald-50');
                        } else {
                            displayTotal.classList.replace('text-emerald-600', 'text-rose-600');
                            circleContainer.classList.replace('border-emerald-100', 'border-rose-100');
                            circleContainer.classList.replace('bg-emerald-50', 'bg-rose-50');
                        }
                    }
                    
                    // Note: Kami tidak me-reload seluruh halaman agar pengalaman mengoreksi essai guru lebih mulus. 
                    // Warna card 'benar/salah' pada essai juga tidak perlu berubah real-time karena guru hanya fokus pada box input nilai.
                } else {
                    Swal.fire('Gagal', data.message || 'Terjadi kesalahan.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Gagal menghubungi server.', 'error');
            });
        }
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\cbt\result_detail.blade.php ENDPATH**/ ?>