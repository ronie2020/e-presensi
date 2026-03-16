<table>
    <thead>
        <!-- BARIS 1: Header Utama dengan Colspan & Rowspan -->
        <tr>
            <th rowspan="2" align="center" valign="center" style="font-weight: bold; border: 1px solid #000;">NO</th>
            <th rowspan="2" align="center" valign="center" style="font-weight: bold; border: 1px solid #000; width: 30px;">NAMA SISWA</th>
            <th rowspan="2" align="center" valign="center" style="font-weight: bold; border: 1px solid #000; width: 15px;">NISN</th>
            <th rowspan="2" align="center" valign="center" style="font-weight: bold; border: 1px solid #000; width: 15px;">NIS</th>
            
            <!-- Colspan dinamis sebanyak jumlah mapel -->
            <th colspan="<?php echo e(count($subjects)); ?>" align="center" style="font-weight: bold; border: 1px solid #000; background-color: #8ea9db;">MATA PELAJARAN</th>
            
            <th rowspan="2" align="center" valign="center" style="font-weight: bold; border: 1px solid #000; background-color: #ffd966;">Jml</th>
            <th rowspan="2" align="center" valign="center" style="font-weight: bold; border: 1px solid #000; background-color: #ffd966;">Rank</th>
            <th colspan="3" align="center" style="font-weight: bold; border: 1px solid #000; background-color: #8ea9db;">Ketidakhadiran</th>
        </tr>
        
        <!-- BARIS 2: Singkatan Mapel & Kehadiran -->
        <tr>
            <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <!-- Gunakan Kode Mapel jika sudah diisi di menu Mata Pelajaran, jika kosong gunakan 3 huruf pertama -->
                <th align="center" style="font-weight: bold; border: 1px solid #000; background-color: #b4c6e7;">
                    <?php echo e(!empty($subject->code) ? strtoupper($subject->code) : strtoupper(substr($subject->name, 0, 3))); ?>

                </th>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <th align="center" style="font-weight: bold; border: 1px solid #000; background-color: #e2efda;">Sakit</th>
            <th align="center" style="font-weight: bold; border: 1px solid #000; background-color: #e2efda;">Izin</th>
            <th align="center" style="font-weight: bold; border: 1px solid #000; background-color: #e2efda;">Alpa</th>
        </tr>
    </thead>
    <tbody>
        <?php
            // HELPER: Fungsi untuk mengubah angka menjadi huruf Kolom Excel (Contoh: 1=>A, 5=>E)
            $getExcelColumn = function($num) {
                $letter = '';
                while ($num > 0) {
                    $modulo = ($num - 1) % 26;
                    $letter = chr(65 + $modulo) . $letter;
                    $num = intval(($num - $modulo) / 26);
                }
                return $letter;
            };

            $totalMapel = count($subjects);
            $totalSiswa = count($students);

            // Kolom Mapel dimulai dari E (Index 5)
            $lastMapelColNum = 4 + $totalMapel;
            $lastMapelCol = $getExcelColumn($lastMapelColNum);

            // Kolom Jumlah (Jml) ada tepat setelah Mapel terakhir
            $jmlColNum = $lastMapelColNum + 1;
            $jmlCol = $getExcelColumn($jmlColNum);

            // Baris terakhir data siswa (Baris 1 & 2 adalah Header)
            $lastRow = 2 + $totalSiswa;
        ?>

        <!-- Loop Data Siswa -->
        <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $rowNum = $index + 3; // Karena data siswa dimulai di baris ke-3 Excel
                $bgColor = $index % 2 == 0 ? '#ffffff' : '#f8fafc'; // Efek warna selang-seling (Zebra)
            ?>
            
            <tr style="background-color: <?php echo e($bgColor); ?>;">
                <td align="center" style="border: 1px solid #000;"><?php echo e($index + 1); ?></td>
                <td style="border: 1px solid #000;"><?php echo e($student->name); ?></td>
                <td align="center" style="border: 1px solid #000;"><?php echo e($student->student_id); ?></td> <!-- NISN -->
                <td align="center" style="border: 1px solid #000;"><?php echo e($student->nis ?? ''); ?></td> <!-- NIS -->
                
                <!-- Ruang kosong untuk diisi nilai Mapel oleh guru -->
                <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <td align="center" style="border: 1px solid #000;"></td>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                
                <!-- RUMUS OTOMATIS: Jml (SUM) dan Rank (RANK) -->
                <td align="center" style="border: 1px solid #000; font-weight: bold; color: #b45f06; background-color: #fff2cc;">
                    =SUM(E<?php echo e($rowNum); ?>:<?php echo e($lastMapelCol); ?><?php echo e($rowNum); ?>)
                </td>
                <td align="center" style="border: 1px solid #000; font-weight: bold; color: #b45f06; background-color: #fff2cc;">
                    =RANK(<?php echo e($jmlCol); ?><?php echo e($rowNum); ?>, <?php echo e($jmlCol); ?>$3:<?php echo e($jmlCol); ?>$<?php echo e($lastRow); ?>, 0)
                </td>
                
                <!-- Kehadiran -->
                <td align="center" style="border: 1px solid #000;"></td>
                <td align="center" style="border: 1px solid #000;"></td>
                <td align="center" style="border: 1px solid #000;"></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/grades/exports/leger.blade.php ENDPATH**/ ?>