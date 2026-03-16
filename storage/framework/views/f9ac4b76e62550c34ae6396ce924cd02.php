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
            
            <th rowspan="2" align="center" valign="center" style="font-weight: bold; border: 1px solid #000;">Jml</th>
            <th rowspan="2" align="center" valign="center" style="font-weight: bold; border: 1px solid #000;">Rank</th>
            <th colspan="3" align="center" style="font-weight: bold; border: 1px solid #000; background-color: #8ea9db;">Ketidakhadiran</th>
        </tr>
        
        <!-- BARIS 2: Singkatan Mapel & Kehadiran -->
        <tr>
            <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <!-- Gunakan Kode Mapel jika ada, jika kosong gunakan 3 huruf pertama -->
                <th align="center" style="font-weight: bold; border: 1px solid #000; background-color: #b4c6e7;">
                    <?php echo e($subject->code ? strtoupper($subject->code) : strtoupper(substr($subject->name, 0, 3))); ?>

                </th>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <th align="center" style="font-weight: bold; border: 1px solid #000; background-color: #b4c6e7;">Sakit</th>
            <th align="center" style="font-weight: bold; border: 1px solid #000; background-color: #b4c6e7;">Izin</th>
            <th align="center" style="font-weight: bold; border: 1px solid #000; background-color: #b4c6e7;">Alpa</th>
        </tr>
    </thead>
    <tbody>
        <!-- Loop Data Siswa -->
        <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td align="center" style="border: 1px solid #000;"><?php echo e($index + 1); ?></td>
                <td style="border: 1px solid #000;"><?php echo e($student->name); ?></td>
                <td align="center" style="border: 1px solid #000;"><?php echo e($student->student_id); ?></td> <!-- NISN -->
                <td align="center" style="border: 1px solid #000;"><?php echo e($student->nis ?? ''); ?></td> <!-- Jika ada field NIS terpisah -->
                
                <!-- Ruang kosong untuk diisi nilai Mapel oleh guru -->
                <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <td align="center" style="border: 1px solid #000;"></td>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                
                <!-- Ruang kosong untuk Jml, Rank, Kehadiran -->
                <td align="center" style="border: 1px solid #000;"></td>
                <td align="center" style="border: 1px solid #000;"></td>
                <td align="center" style="border: 1px solid #000;"></td>
                <td align="center" style="border: 1px solid #000;"></td>
                <td align="center" style="border: 1px solid #000;"></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/grades/exports/leger.blade.php ENDPATH**/ ?>