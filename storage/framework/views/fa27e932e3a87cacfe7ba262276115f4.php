<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bank Soal - <?php echo e($bank->title); ?></title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
        }
        h1, h2, h3 { text-align: center; margin: 5px 0; }
        .header-box { border-bottom: 2px solid #000; padding-bottom: 15px; margin-bottom: 20px; }
        table.meta { width: 100%; margin-bottom: 20px; font-weight: bold; }
        .question-container { margin-bottom: 20px; page-break-inside: avoid; }
        table.options { width: 100%; border-collapse: collapse; margin-top: 5px; }
        table.options td { padding: 4px 0; vertical-align: top; }
        .opt-label { width: 25px; font-weight: bold; }
        .img-box { max-width: 300px; max-height: 300px; margin: 10px 0; display: block; }
        .answer-key { margin-top: 50px; border-top: 1px dashed #000; padding-top: 20px; }
        table.key-table { width: 100%; border-collapse: collapse; }
        table.key-table th, table.key-table td { border: 1px solid #000; padding: 5px; text-align: center; }
    </style>
</head>
<body>

    <div class="header-box">
        <h2>BANK SOAL SEKOLAH</h2>
        <h3><?php echo e(strtoupper($bank->title)); ?></h3>
        <table class="meta">
            <tr>
                <td width="15%">Mata Pelajaran</td><td width="2%">:</td><td><?php echo e($bank->subject_name); ?></td>
                <td width="15%">Kelas</td><td width="2%">:</td><td><?php echo e($bank->class_level); ?></td>
            </tr>
            <tr>
                <td>Kode Bank</td><td>:</td><td><?php echo e($bank->code); ?></td>
                <td>Total Soal</td><td>:</td><td><?php echo e($bank->questions->count()); ?></td>
            </tr>
        </table>
    </div>

    <?php $__currentLoopData = $bank->questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="question-container">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="25" valign="top"><b><?php echo e($index + 1); ?>.</b></td>
                    <td valign="top">
                        <?php if($q->question_image): ?>
                            <img src="<?php echo e(url('storage/' . $q->question_image)); ?>" class="img-box"><br>
                        <?php endif; ?>
                        
                        <?php echo $q->question_text; ?>


                        <?php if($q->question_type == 'choice'): ?>
                            <table class="options">
                                <?php $__currentLoopData = ['A','B','C','D']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php 
                                        $val = isset($q->{'option_'.$opt}) ? $q->{'option_'.$opt} : ($q->options[$opt] ?? ''); 
                                        $imgVal = isset($q->{'image_'.$opt}) ? $q->{'image_'.$opt} : ($q->options['image_'.$opt] ?? null);
                                    ?>
                                    <?php if($val !== '' || $imgVal): ?>
                                        <tr>
                                            <td class="opt-label"><?php echo e($opt); ?>.</td>
                                            <td>
                                                <?php echo $val; ?>

                                                <?php if($imgVal): ?>
                                                    <br><img src="<?php echo e(url('storage/' . $imgVal)); ?>" class="img-box">
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </table>
                        <?php elseif($q->question_type == 'true_false'): ?>
                            <p><i>Pilihan: (Benar / Salah)</i></p>
                        <?php elseif($q->question_type == 'essay'): ?>
                            <br><br><br>
                        <?php elseif($q->question_type == 'matching'): ?>
                            <p><b>Pasangkan:</b></p>
                            <?php 
                                $pairs = is_string($q->options) ? json_decode($q->options, true)['pairs'] ?? [] : $q->options['pairs'] ?? [];
                            ?>
                            <table border="1" cellpadding="5" cellspacing="0" width="80%">
                                <?php $__currentLoopData = $pairs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td width="45%">
                                            <?php if(isset($p['left_image']) && $p['left_image']): ?>
                                                <img src="<?php echo e(url('storage/' . $p['left_image'])); ?>" height="50"><br>
                                            <?php endif; ?>
                                            <?php echo e($p['left']); ?>

                                        </td>
                                        <td width="10%" align="center"> ---> </td>
                                        <td width="45%">
                                            <?php if(isset($p['right_image']) && $p['right_image']): ?>
                                                <img src="<?php echo e(url('storage/' . $p['right_image'])); ?>" height="50"><br>
                                            <?php endif; ?>
                                            <?php echo e($p['right']); ?>

                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </table>
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    
    <br clear="all" style="page-break-before:always" />
    <div class="answer-key">
        <h3>KUNCI JAWABAN & PEMBOBOTAN</h3>
        <table class="key-table">
            <tr>
                <th width="10%">No</th>
                <th width="20%">Tipe Soal</th>
                <th width="50%">Kunci / Jawaban Benar</th>
                <th width="20%">Bobot Poin</th>
            </tr>
            <?php $__currentLoopData = $bank->questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($index + 1); ?></td>
                    <td>
                        <?php if($q->question_type == 'choice'): ?> PG 
                        <?php elseif($q->question_type == 'true_false'): ?> B/S 
                        <?php elseif($q->question_type == 'essay'): ?> Essai 
                        <?php else: ?> Menjodohkan <?php endif; ?>
                    </td>
                    <td>
                        <?php if($q->question_type == 'matching'): ?>
                            <i>(Terlampir pada tabel soal)</i>
                        <?php else: ?>
                            <b><?php echo e($q->correct_answer ?: '-'); ?></b>
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($q->score_weight); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </table>
    </div>

</body>
</html><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\cbt\bank\export_word.blade.php ENDPATH**/ ?>