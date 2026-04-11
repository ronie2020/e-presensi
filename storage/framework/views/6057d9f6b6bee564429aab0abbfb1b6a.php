<!DOCTYPE html>
<html>
<head>
    <title><?php echo e($title); ?></title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .header p { margin: 2px 0; }
        
        /* Tabel Utama */
        .table-data { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table-data th, .table-data td { border: 1px solid #333; padding: 6px 8px; text-align: left; }
        .table-data th { background-color: #f0f0f0; font-weight: bold; }
        
        .badge { padding: 2px 5px; border-radius: 4px; font-size: 10px; color: white; display: inline-block; }
        .bg-green { background-color: #16a34a; } /* Emerald-600 */
        .bg-red { background-color: #dc2626; } /* Red-600 */
        .bg-blue { background-color: #2563eb; } /* Blue-600 */
        
        /* Tabel Khusus Tanda Tangan */
        .table-signature {
            width: 100%;
            border: none;
            margin-top: 50px;
        }
        .table-signature td {
            border: none;
            text-align: center;
            width: 50%;
            padding: 0;
            vertical-align: top;
        }

        .footer { margin-top: 30px; text-align: right; font-size: 10px; color: #666; font-style: italic; }
    </style>
</head>
<body>

    <div class="header">
        <h1>SMP NEGERI 3 LAKBOK</h1>
        <p>Laporan Perpustakaan Digital</p>
        <p><strong><?php echo e($title); ?></strong></p>
    </div>

    <?php if($type == 'monthly'): ?>
        
        <table class="table-data">
            <thead>
                <tr>
                    <th style="width: 5%; text-align: center;">No</th>
                    <th style="width: 15%">Tanggal</th>
                    <th style="width: 25%">Nama Siswa</th>
                    <th style="width: 30%">Judul Buku</th>
                    <th style="width: 15%; text-align: center;">Status</th>
                    <th style="width: 10%; text-align: right;">Denda</th>
                </tr>
            </thead>
            <tbody>
                <?php $totalDenda = 0; ?>
                <?php $__empty_1 = true; $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $loan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php $totalDenda += $loan->fine; ?>
                    <tr>
                        <td style="text-align: center;"><?php echo e($index + 1); ?></td>
                        <td><?php echo e(\Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y')); ?></td>
                        <td><?php echo e($loan->student->name ?? '-'); ?></td>
                        <td><?php echo e($loan->book->title ?? '-'); ?></td>
                        <td style="text-align: center;">
                            <?php if($loan->return_date): ?>
                                <span class="badge bg-green">Kembali</span>
                            <?php else: ?>
                                <span class="badge bg-blue">Dipinjam</span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align: right;">
                            <?php if($loan->fine > 0): ?>
                                Rp <?php echo e(number_format($loan->fine, 0, ',', '.')); ?>

                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 20px;">Tidak ada data peminjaman pada periode ini.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
            
            <?php if($totalDenda > 0): ?>
            <tfoot>
                <tr>
                    <th colspan="5" style="text-align: right; text-transform: uppercase;">Total Denda Terkumpul:</th>
                    <th style="text-align: right; font-weight: bold;">Rp <?php echo e(number_format($totalDenda, 0, ',', '.')); ?></th>
                </tr>
            </tfoot>
            <?php endif; ?>
        </table>

    <?php elseif($type == 'top_books'): ?>
        
        <table class="table-data">
            <thead>
                <tr>
                    <th style="width: 5%; text-align: center;">No</th>
                    <th style="width: 15%">Kode Buku</th>
                    <th style="width: 40%">Judul Buku</th>
                    <th style="width: 25%">Penulis</th>
                    <th style="width: 15%; text-align: center;">Dipinjam</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td style="text-align: center; font-weight: bold;"><?php echo e($index + 1); ?></td>
                        <td><?php echo e($book->book_code); ?></td>
                        <td><?php echo e($book->title); ?></td>
                        <td><?php echo e($book->author); ?></td>
                        <td style="text-align: center;"><?php echo e($book->loans_count); ?>x</td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">Belum ada data peminjaman.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    <?php endif; ?>

    
    <table class="table-signature">
        <tr>
            <td>
                <p>Mengetahui,</p>
                <p><strong>Kepala SMP Negeri 3 Lakbok</strong></p>
                <br><br><br><br>
                <p style="font-weight: bold; text-decoration: underline;">Nama Kepala Sekolah, S.Pd., M.Pd.</p>
                <p>NIP. 1980xxxx xxxx x xxxx</p>
            </td>
            <td>
                <p>Lakbok, <?php echo e(date('d F Y')); ?></p>
                <p><strong>Kepala Perpustakaan</strong></p>
                <br><br><br><br>
                <p style="font-weight: bold; text-decoration: underline;">Nama Pustakawan, S.I.P.</p>
                <p>NIP. 1990xxxx xxxx x xxxx</p>
            </td>
        </tr>
    </table>

    <div class="footer">
        Dicetak dari Sistem Perpustakaan Digital pada <?php echo e(date('d/m/Y H:i:s')); ?>

    </div>

</body>
</html><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\library\reports\pdf-template.blade.php ENDPATH**/ ?>