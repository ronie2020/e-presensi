<!DOCTYPE html>
<html>
<head>
    <title>Laporan Data Prestasi</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 11px;
            color: #1e293b; /* Slate-800 */
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 3px double #0f172a; /* Slate-900 */
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0 0 5px 0;
            font-size: 16px;
            text-transform: uppercase;
            font-weight: 900;
            color: #0f172a;
        }
        .header p {
            margin: 0;
            font-size: 11px;
            color: #475569; /* Slate-600 */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #cbd5e0; /* Slate-300 */
            padding: 8px 10px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #1e3a8a; /* Blue-900 */
            color: #ffffff;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
        }
        tr:nth-child(even) {
            background-color: #f8fafc; /* Slate-50 */
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        
        .footer {
            margin-top: 40px;
            text-align: right;
            font-size: 11px;
        }
        .small-text {
            font-size: 10px;
            color: #64748b;
        }
        .badge {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
            padding: 2px 4px;
            border-radius: 4px;
            background-color: #e2e8f0;
            color: #1e293b;
        }
        .cert-badge {
            color: #2563eb; /* Blue-600 */
            font-size: 9px;
            font-weight: bold;
            margin-top: 4px;
            display: inline-block;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Laporan Prestasi & Penghargaan</h1>
        <p>SMP NEGERI 3 LAKBOK</p>
        <p class="small-text">Jalan Raya Lakbok, Kabupaten Ciamis, Jawa Barat</p>
    </div>

    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
        <span class="small-text">Dicetak pada: <?php echo e(date('d F Y, H:i')); ?></span>
        <?php if(request('search')): ?>
            <span class="small-text" style="float: right;">Filter: "<strong><?php echo e(request('search')); ?></strong>"</span>
        <?php endif; ?>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%" class="text-center">No</th>
                <th style="width: 25%">Nama Juara</th>
                <th style="width: 10%" class="text-center">Tipe</th>
                <th style="width: 30%">Judul Prestasi</th>
                <th style="width: 15%" class="text-center">Tingkat</th>
                <th style="width: 15%" class="text-center">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $achievements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="text-center"><?php echo e($index + 1); ?></td>
                    <td>
                        
                        <?php if($item->type === 'Siswa' && $item->student): ?>
                            <strong><?php echo e($item->student->name); ?></strong><br>
                            <span class="small-text"><?php echo e($item->student->schoolClass->name ?? '-'); ?></span>
                        <?php else: ?>
                            <strong><?php echo e($item->achiever_name ?? $item->name_manual); ?></strong>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <span class="badge"><?php echo e($item->type); ?></span>
                    </td>
                    <td>
                        <?php echo e($item->title); ?>

                        
                        <?php if(!empty($item->certificate_path)): ?>
                            <br><span class="cert-badge">[Ada Sertifikat]</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center"><?php echo e($item->level); ?></td>
                    <td class="text-center"><?php echo e(\Carbon\Carbon::parse($item->date)->format('d/m/Y')); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="text-center" style="padding: 20px; font-style: italic; color: #64748b;">
                        Tidak ada data prestasi yang ditemukan.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        <p>Mengetahui,</p>
        <p>Kepala Sekolah / Waka Kesiswaan</p>
        <br><br><br>
        <p style="font-weight: bold; text-decoration: underline;">( .......................................... )</p>
        <p>NIP. ..............................</p>
    </div>

</body>
</html><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\achievements\pdf_export.blade.php ENDPATH**/ ?>