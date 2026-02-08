<table border="1">
    <thead>
        <tr>
            <th colspan="8" style="font-size: 14pt; font-weight: bold; text-align: center;">REKAPITULASI ABSENSI PER KELAS</th>
        </tr>
        <tr>
            <th colspan="8" style="text-align: center;">
                Periode: <?php echo e($startDate); ?> s/d <?php echo e($endDate); ?>

            </th>
        </tr>
        <tr>
            <th>No</th>
            <th>Nama Kelas</th>
            <th>Jumlah Siswa</th>
            <th>Hadir</th>
            <th>Terlambat</th>
            <th>Izin / Sakit</th>
            <th>Alpha</th>
            <th>Persentase Kehadiran (%)</th>
        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $reportData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td style="text-align: center;"><?php echo e($loop->iteration); ?></td>
            <td><?php echo e($data->name); ?></td>
            <td style="text-align: center;"><?php echo e($data->total_students); ?></td>
            <td style="text-align: center;"><?php echo e($data->hadir); ?></td>
            <td style="text-align: center;"><?php echo e($data->telat); ?></td>
            <td style="text-align: center;"><?php echo e($data->izin_sakit); ?></td>
            <td style="text-align: center;"><?php echo e($data->alpha); ?></td>
            <td style="text-align: center;"><?php echo e($data->rate); ?></td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\reports\excel_class_recap.blade.php ENDPATH**/ ?>