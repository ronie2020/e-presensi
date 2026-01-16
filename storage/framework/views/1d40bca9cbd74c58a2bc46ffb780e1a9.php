<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($title); ?></title>
    <style>
        @page { size: A4; margin: 2cm; }
        body { font-family: 'Times New Roman', serif; color: #000; line-height: 1.5; }
        .no-print { display: block; }
        @media print { 
            .no-print { display: none; } 
            body { -webkit-print-color-adjust: exact; }
        }
        
        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px double #000; padding-bottom: 10px; }
        .header h1 { font-size: 14pt; margin: 0; font-weight: bold; text-transform: uppercase; }
        .header h2 { font-size: 12pt; margin: 5px 0 0; font-weight: bold; }
        .header p { font-size: 10pt; margin: 2px 0; }
        
        table.data { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 10pt; }
        table.data th, table.data td { border: 1px solid #000; padding: 6px; }
        table.data th { background-color: #f3f3f3; text-align: center; font-weight: bold; text-transform: uppercase; }
        table.data td { vertical-align: top; }
        
        .footer { margin-top: 50px; width: 100%; page-break-inside: avoid; }
        .signature { float: right; width: 250px; text-align: center; font-size: 11pt; }
        .signature p { margin-bottom: 70px; }
        
        /* Tombol Cetak */
        .btn-print {
            position: fixed; top: 20px; right: 20px;
            background: #1e3a8a; color: white; border: none;
            padding: 10px 20px; border-radius: 8px; cursor: pointer;
            font-weight: bold; box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            font-family: sans-serif; display: flex; align-items: center; gap: 8px; z-index: 9999;
        }
        .btn-print:hover { background: #1e40af; }
        
        .badge { padding: 2px 6px; border-radius: 4px; font-size: 8pt; font-weight: bold; border: 1px solid #000; display: inline-block; }
        .badge-out { background: #ffedd5; }
        .badge-in { background: #d1fae5; }
    </style>
</head>
<body>

    <button onclick="window.print()" class="btn-print no-print">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
        Cetak / Simpan PDF
    </button>

    <div class="header">
        <h1>LAPORAN IZIN KELUAR MASUK SISWA</h1>
        <h2>SMP NEGERI 3 LAKBOK</h2>
        <p>Jl. Raya Lakbok No. 123, Ciamis, Jawa Barat</p>
    </div>

    <div style="margin-bottom: 10px;">
        <strong>Periode Laporan:</strong> <?php echo e(request('date') ? \Carbon\Carbon::parse(request('date'))->translatedFormat('d F Y') : 'Semua Waktu'); ?> <br>
        <strong>Status:</strong> <?php echo e(request('status') ? ucfirst(request('status')) : 'Semua'); ?>

    </div>

    <table class="data">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Nama Siswa</th>
                <th width="10%">Kelas</th>
                <th width="15%">Keperluan</th>
                <th width="10%">Keluar</th>
                <th width="10%">Kembali</th>
                <th width="10%">Durasi</th>
                <th width="15%">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $permits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $permit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td style="text-align: center;"><?php echo e($index + 1); ?></td>
                <td>
                    <strong><?php echo e($permit->student->name); ?></strong><br>
                    <small><?php echo e($permit->student->student_id); ?></small>
                </td>
                <td style="text-align: center;"><?php echo e($permit->student->schoolClass->name ?? '-'); ?></td>
                <td>
                    <?php echo e($permit->reason_category); ?>

                    <?php if($permit->notes): ?> <br><i style="font-size:9pt">"<?php echo e($permit->notes); ?>"</i> <?php endif; ?>
                </td>
                <td style="text-align: center;">
                    <?php echo e(\Carbon\Carbon::parse($permit->time_out)->format('H:i')); ?><br>
                    <small><?php echo e(\Carbon\Carbon::parse($permit->time_out)->format('d/m/y')); ?></small>
                </td>
                <td style="text-align: center;">
                    <?php if($permit->time_in): ?>
                        <?php echo e(\Carbon\Carbon::parse($permit->time_in)->format('H:i')); ?>

                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
                <td style="text-align: center;">
                    <?php if($permit->duration_minutes): ?>
                        <?php echo e($permit->duration_minutes); ?> m
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
                <td style="text-align: center;">
                    <?php if($permit->status == 'OUT'): ?>
                        <span class="badge badge-out">SEDANG KELUAR</span>
                    <?php else: ?>
                        <span class="badge badge-in">KEMBALI</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="8" style="text-align: center; padding: 20px;">Data tidak ditemukan.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        <div class="signature">
            <p>
                Lakbok, <?php echo e(\Carbon\Carbon::now()->translatedFormat('d F Y')); ?><br>
                Petugas Piket,
            </p>
            <div style="font-weight: bold; text-decoration: underline;"><?php echo e(Auth::user()->name ?? '.........................'); ?></div>
            <div>NIP. .........................</div>
        </div>
    </div>

</body>
</html><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/permit/print.blade.php ENDPATH**/ ?>