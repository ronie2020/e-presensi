<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Nilai</title>
</head>
<body>
    <table>
        <thead>
            
            <tr>
                <th colspan="<?php echo e($assignments->count() + 5); ?>" style="font-size: 16px; font-weight: bold; text-align: center; height: 30px;">
                    REKAPITULASI NILAI SISWA
                </th>
            </tr>
            <tr>
                <th colspan="<?php echo e($assignments->count() + 5); ?>" style="font-size: 12px; font-weight: bold; text-align: center;">
                    Kelas: <?php echo e($selectedClass->name ?? '-'); ?> | Mata Pelajaran: <?php echo e($selectedSubject->name ?? '-'); ?>

                </th>
            </tr>
            <tr>
                <th colspan="<?php echo e($assignments->count() + 5); ?>" style="font-size: 11px; font-style: italic; text-align: center;">
                    Dicetak pada: <?php echo e(date('d F Y')); ?> oleh <?php echo e(Auth::user()->name); ?>

                </th>
            </tr>
            
            <tr></tr> 

            
            <tr>
                <th style="width: 5px;">No</th>
                <th style="width: 35px;">Nama Siswa</th>
                <th style="width: 15px;">NISN</th>
                
                <?php $__currentLoopData = $assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    
                    <th style="width: 15px;"><?php echo e(\Illuminate\Support\Str::limit($task->title, 15)); ?></th>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <th style="width: 10px;">Total</th>
                <th style="width: 10px;">Rata2</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $total = 0; 
                    $count = 0;
                ?>
                <tr>
                    <td><?php echo e($index + 1); ?></td>
                    <td><?php echo e($student->name); ?></td>
                    <td><?php echo e($student->nisn ?? $student->student_id); ?></td>
                    
                    <?php $__currentLoopData = $assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $score = $gradeBook[$student->id][$task->id] ?? null;
                            if($score !== null) { 
                                $total += $score; 
                                $count++; 
                            }
                        ?>
                        <td><?php echo e($score ?? '-'); ?></td>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <td style="font-weight: bold;"><?php echo e($total); ?></td>
                    <td style="font-weight: bold;"><?php echo e($count > 0 ? round($total / $count, 1) : 0); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>

        
        <tfoot>
            <tr></tr>
            <tr></tr>
            <tr>
                <td></td> 
                <td style="text-align: center;">Mengetahui,</td>
                
                <td colspan="<?php echo e($assignments->count() + 1); ?>"></td> 
                <td colspan="2" style="text-align: center;">
                    
                    Lakbok, <?php echo e(\Carbon\Carbon::now()->translatedFormat('d F Y')); ?>

                </td>
            </tr>
            <tr>
                <td></td>
                <td style="text-align: center;">Kepala Sekolah</td>
                <td colspan="<?php echo e($assignments->count() + 1); ?>"></td>
                <td colspan="2" style="text-align: center;">Guru Mata Pelajaran</td>
            </tr>
            
            
            <tr style="height: 60px;"></tr> 

            <tr>
                <td></td>
                <td style="text-align: center; font-weight: bold; text-decoration: underline;">
                    
                    TANTAN SUTANDI NUGRAHA, S.Si, M.Pd.
                </td>
                <td colspan="<?php echo e($assignments->count() + 1); ?>"></td>
                <td colspan="2" style="text-align: center; font-weight: bold; text-decoration: underline;">
                    <?php echo e(Auth::user()->name); ?>

                </td>
            </tr>
            <tr>
                <td></td>
                <td style="text-align: center;">NIP. 197xxxxxxxxxxxxx</td>
                <td colspan="<?php echo e($assignments->count() + 1); ?>"></td>
                <td colspan="2" style="text-align: center;">NIP. <?php echo e(Auth::user()->nip ?? '-'); ?></td>
            </tr>
        </tfoot>
    </table>
</body>
</html><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\lms\grades\export_excel.blade.php ENDPATH**/ ?>