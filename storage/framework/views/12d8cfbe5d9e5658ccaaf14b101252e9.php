<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($title); ?></title>
    <style>
        @page { size: A4 portrait; margin: 1.5cm; }
        body { font-family: 'Times New Roman', serif; color: #000; line-height: 1.4; font-size: 11pt; }
        
        .no-print { display: block; margin-bottom: 20px; }
        @media print { 
            .no-print { display: none !important; } 
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            @page { margin: 1.5cm; }
        }
        
        /* Kop Surat */
        .header-table { width: 100%; border-bottom: 3px double #000; margin-bottom: 15px; }
        .header-table td { vertical-align: middle; }
        .header-title { font-size: 14pt; font-weight: bold; margin: 0; text-transform: uppercase; }
        .header-subtitle { font-size: 12pt; font-weight: bold; margin: 2px 0 0; }
        .header-address { font-size: 9pt; font-style: italic; margin: 2px 0; }

        .report-title { text-align: center; margin-bottom: 20px; }
        .report-title h3 { margin: 0; text-decoration: underline; text-transform: uppercase; font-size: 12pt; }
        .report-title p { margin: 5px 0 0; font-size: 10pt; }

        /* Tabel Data */
        table.data { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 10pt; }
        table.data th, table.data td { border: 1px solid #000; padding: 6px; }
        table.data th { background-color: #e0e0e0; text-align: center; font-weight: bold; vertical-align: middle; }
        table.data td { vertical-align: middle; }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        
        /* Footer Tanda Tangan */
        .footer { margin-top: 40px; width: 100%; page-break-inside: avoid; }
        .signature-table { width: 100%; }
        .signature-table td { text-align: center; vertical-align: top; width: 33%; }
        
        /* Tombol Cetak */
        .btn-print {
            position: fixed; top: 20px; right: 20px;
            background: #10b981; color: white; border: none;
            padding: 10px 20px; border-radius: 8px; cursor: pointer;
            font-weight: bold; font-family: sans-serif; box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }
        .btn-print:hover { background: #059669; transform: translateY(-2px); }
    </style>
</head>
<body>

    <button onclick="window.print()" class="btn-print no-print">🖨️ Cetak / Simpan PDF</button>

    
    <table class="header-table">
        <tr>
            <td width="15%" align="center" style="padding-bottom: 10px;">
                <!-- Ganti dengan path logo sekolah Anda -->
                <div style="width: 70px; height: 70px; background: #ddd; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; border: 1px solid #999;">LOGO</div>
            </td>
            <td align="center" style="padding-bottom: 10px;">
                <h2 class="header-title">PEMERINTAH KABUPATEN CIAMIS</h2>
                <h2 class="header-subtitle">DINAS PENDIDIKAN</h2>
                <h1 class="header-title" style="font-size: 16pt; margin-top: 5px;">SMP NEGERI 3 LAKBOK</h1>
                <p class="header-address">Jl. Raya Lakbok No. 123, Cintaratu, Lakbok, Ciamis - 46385</p>
            </td>
            <td width="15%"></td>
        </tr>
    </table>

    <div class="report-title">
        <h3>REKAPITULASI MUTABAAH RAMADHAN</h3>
        <p>
            <strong>Kelas:</strong> <?php echo e($class->name); ?> &nbsp;|&nbsp;
            <strong>Periode:</strong> <?php echo e(\Carbon\Carbon::parse($startDate)->translatedFormat('d F Y')); ?> 
            s/d <?php echo e(\Carbon\Carbon::parse($endDate)->translatedFormat('d F Y')); ?>

        </p>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">NIS</th>
                <th class="text-left">Nama Siswa</th>
                <th width="12%">Total<br>Pengisian</th>
                <th width="12%">Total<br>Puasa</th>
                <th width="15%">Total Shalat<br>(Waktu)</th>
                <th width="12%">Rata-rata<br>Nilai</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $reportData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td class="text-center"><?php echo e($loop->iteration); ?></td>
                <td class="text-center"><?php echo e($data->nis); ?></td>
                <td><?php echo e($data->name); ?></td>
                <td class="text-center"><?php echo e($data->total_log); ?> Hari</td>
                <td class="text-center"><?php echo e($data->total_puasa); ?> Hari</td>
                <td class="text-center"><?php echo e($data->total_shalat); ?> Waktu</td>
                <td class="text-center font-bold"><?php echo e($data->rata_nilai); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="7" class="text-center" style="padding: 20px;">Data aktivitas Ramadhan tidak ditemukan pada periode ini.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        <table class="signature-table">
            <tr>
                <td>
                    Mengetahui,<br>
                    Kepala Sekolah
                    <br><br><br><br><br>
                    <strong>TANTAN SUTANDI N., S.Si, M.Pd.</strong><br>
                    NIP. 19820928 201101 1 002
                </td>
                <td></td>
                <td>
                    Lakbok, <?php echo e(\Carbon\Carbon::now()->translatedFormat('d F Y')); ?><br>
                    Guru PAI / Wali Kelas
                    <br><br><br><br><br>
                    <strong><?php echo e(Auth::user()->name ?? '.........................'); ?></strong><br>
                    NIP. .........................
                </td>
            </tr>
        </table>
    </div>

</body>
</html><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/ramadan/export-pdf.blade.php ENDPATH**/ ?>