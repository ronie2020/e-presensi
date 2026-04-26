<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pratinjau - <?php echo e($bank->title); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    
    <script>
        window.MathJax = {
            tex: { inlineMath: [['$', '$'], ['\\(', '\\)']] },
            svg: { fontCache: 'global' }
        };
    </script>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>

    <style>
        .trix-content ul { list-style-type: disc; padding-left: 1.5rem; }
        .trix-content ol { list-style-type: decimal; padding-left: 1.5rem; }
    </style>
</head>
<body class="bg-[#e5eff5]/30 font-sans text-[#2c3f61] selection:bg-[#56bbf1]/30 selection:text-[#0d52a1]">

    
    <div class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-4xl mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-[#56bbf1] to-[#0d52a1] rounded-xl flex items-center justify-center text-white shadow-md shadow-[#56bbf1]/20">
                    <i class="ph-bold ph-desktop text-xl"></i>
                </div>
                <div>
                    <h1 class="font-black text-lg text-[#2c3f61] leading-tight"><?php echo e($bank->title); ?></h1>
                    <p class="text-[10px] font-bold text-[#0d52a1] uppercase tracking-wide">Mode Pratinjau Siswa • <?php echo e($bank->subject_name); ?></p>
                </div>
            </div>
            <button onclick="window.close()" class="px-5 py-2.5 bg-slate-100 text-slate-600 rounded-xl font-bold text-sm hover:bg-rose-50 hover:text-rose-600 transition-colors border border-transparent hover:border-rose-200">
                Tutup
            </button>
        </div>
    </div>

    
    <div class="max-w-4xl mx-auto px-4 py-8 space-y-6">
        <?php $__currentLoopData = $bank->questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $qType = $q->question_type ?? 'choice'; ?>
            
            <div class="bg-white rounded-[2rem] p-6 md:p-8 shadow-[0_2px_10px_-3px_rgba(86,187,241,0.1)] border border-slate-100 flex flex-col md:flex-row gap-4 md:gap-6 transition-all hover:border-[#56bbf1]/40 hover:shadow-[#56bbf1]/10">
                
                
                <div class="w-12 h-12 shrink-0 bg-[#56bbf1]/10 rounded-2xl flex items-center justify-center font-black text-[#0d52a1] border border-[#56bbf1]/20 shadow-sm">
                    <?php echo e($index + 1); ?>

                </div>

                
                <div class="flex-1 overflow-hidden">
                    
                    
                    <?php if($q->question_image): ?>
                        <div class="mb-5">
                            <img src="<?php echo e(asset('storage/' . $q->question_image)); ?>" class="max-h-64 rounded-2xl border border-slate-200 shadow-sm object-contain bg-slate-50">
                        </div>
                    <?php endif; ?>

                    
                    <div class="prose prose-slate max-w-none mb-6 text-base leading-relaxed trix-content text-[#2c3f61]">
                        <?php echo $q->question_text; ?>

                    </div>

                    
                    <?php if($qType == 'choice'): ?>
                        <div class="space-y-3">
                            <?php $__currentLoopData = ['A','B','C','D', 'E']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php 
                                    $val = isset($q->{'option_'.$opt}) ? $q->{'option_'.$opt} : ($q->options[$opt] ?? ''); 
                                    $imgVal = isset($q->{'image_'.$opt}) ? $q->{'image_'.$opt} : ($q->options['image_'.$opt] ?? null);
                                ?>
                                <?php if($val !== '' || $imgVal): ?>
                                    <label class="flex items-start gap-4 p-4 rounded-2xl border-2 border-slate-100 hover:border-[#56bbf1] hover:bg-[#e5eff5]/50 cursor-pointer transition-all group">
                                        <input type="radio" name="preview_ans_<?php echo e($q->id); ?>" class="mt-1 w-5 h-5 border-slate-300 text-[#0d52a1] focus:ring-[#56bbf1]">
                                        <div class="flex-1">
                                            <span class="font-black text-slate-400 group-hover:text-[#0d52a1] transition-colors mr-2"><?php echo e($opt); ?>.</span>
                                            <span class="font-medium text-[#2c3f61]"><?php echo $val; ?></span>
                                            <?php if($imgVal): ?>
                                                <img src="<?php echo e(asset('storage/' . $imgVal)); ?>" class="mt-3 max-h-32 rounded-xl border border-slate-200 object-contain bg-white shadow-sm">
                                            <?php endif; ?>
                                        </div>
                                    </label>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                    <?php elseif($qType == 'true_false'): ?>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <label class="flex-1 flex items-center justify-center p-4 rounded-2xl border-2 border-slate-100 hover:border-emerald-300 hover:bg-emerald-50 cursor-pointer transition-all text-emerald-700 font-bold group shadow-sm">
                                <input type="radio" name="preview_ans_<?php echo e($q->id); ?>" class="mr-3 w-5 h-5 text-emerald-500 focus:ring-emerald-500"> BENAR
                            </label>
                            <label class="flex-1 flex items-center justify-center p-4 rounded-2xl border-2 border-slate-100 hover:border-rose-300 hover:bg-rose-50 cursor-pointer transition-all text-rose-700 font-bold group shadow-sm">
                                <input type="radio" name="preview_ans_<?php echo e($q->id); ?>" class="mr-3 w-5 h-5 text-rose-500 focus:ring-rose-500"> SALAH
                            </label>
                        </div>

                    <?php elseif($qType == 'matching'): ?>
                        <div class="bg-[#e5eff5]/40 p-6 rounded-[1.5rem] border border-[#56bbf1]/20 shadow-inner">
                            <p class="text-xs font-bold text-[#2c3f61]/60 mb-5 uppercase tracking-wide flex items-center gap-2"><i class="ph-bold ph-arrows-left-right text-[#0d52a1]"></i> Pasangkan Jawaban Berikut:</p>
                            <?php 
                                $pairs = is_string($q->options) ? json_decode($q->options, true)['pairs'] ?? [] : $q->options['pairs'] ?? [];
                            ?>
                            <div class="space-y-4">
                                <?php $__currentLoopData = $pairs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="flex flex-col sm:flex-row items-center gap-4">
                                        
                                        <div class="flex-1 w-full bg-white p-4 rounded-2xl border border-slate-200 text-center font-medium shadow-sm text-[#2c3f61]">
                                            <?php if(isset($p['left_image']) && $p['left_image']): ?>
                                                <img src="<?php echo e(asset('storage/' . $p['left_image'])); ?>" class="h-20 mx-auto mb-3 rounded-lg object-contain border border-slate-100">
                                            <?php endif; ?>
                                            <?php echo e($p['left']); ?>

                                        </div>
                                        <div class="shrink-0 text-[#56bbf1] rotate-90 sm:rotate-0"><i class="ph-bold ph-arrow-right text-2xl"></i></div>
                                        
                                        <div class="flex-1 w-full relative group">
                                            <select class="w-full bg-white p-4 rounded-2xl border-2 border-dashed border-slate-300 text-center font-bold text-[#2c3f61] appearance-none cursor-pointer hover:border-[#56bbf1] hover:text-[#0d52a1] transition-colors shadow-sm outline-none">
                                                <option>-- Pilih Pasangan --</option>
                                                <option><?php echo e($p['right']); ?></option>
                                            </select>
                                            <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none group-hover:text-[#0d52a1] transition-colors"></i>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                    <?php elseif($qType == 'essay'): ?>
                        <textarea rows="5" class="w-full p-5 rounded-[1.5rem] border-2 border-slate-200 bg-slate-50 focus:bg-white focus:border-[#56bbf1] focus:ring-4 focus:ring-[#56bbf1]/10 text-[#2c3f61] transition-all font-medium placeholder-slate-400 shadow-inner" placeholder="Ketik jawaban urain di sini..."></textarea>
                    <?php endif; ?>

                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <div class="text-center py-12">
            <div class="w-16 h-1 bg-slate-200 rounded-full mx-auto mb-4"></div>
            <p class="text-slate-400 font-bold text-sm uppercase tracking-widest">Akhir dari Pratinjau Soal</p>
        </div>
    </div>

</body>
</html><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/cbt/bank/preview.blade.php ENDPATH**/ ?>