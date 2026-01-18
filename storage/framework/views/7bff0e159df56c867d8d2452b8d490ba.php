<table border="1">
    <thead>
        <tr>
            <th colspan="8" style="font-size: 14pt; font-weight: bold; text-align: center;">LAPORAN IZIN KELUAR SISWA</th>
        </tr>
        <tr>
            <th>No</th>
            <th>NIS</th>
            <th>Nama Siswa</th>
            <th>Kelas</th>
            <th>Alasan</th>
            <th>Catatan</th>
            <th>Waktu Keluar</th>
            <th>Waktu Kembali</th>
            <th>Durasi (Menit)</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $permits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $permit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($index + 1); ?></td>
            <td><?php echo e($permit->student->student_id); ?></td>
            <td><?php echo e($permit->student->name); ?></td>
            <td><?php echo e($permit->student->schoolClass->name ?? '-'); ?></td>
            <td><?php echo e($permit->reason_category); ?></td>
            <td><?php echo e($permit->notes); ?></td>
            <td><?php echo e($permit->time_out); ?></td>
            <td><?php echo e($permit->time_in); ?></td>
            <td><?php echo e($permit->duration_minutes); ?></td>
            <td><?php echo e($permit->status); ?></td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\permit\excel.blade.php ENDPATH**/ ?>