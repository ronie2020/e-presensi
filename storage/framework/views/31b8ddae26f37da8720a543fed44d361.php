<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($title ?? 'Rekapitulasi Absensi Kelas'); ?></title>
    <style>
        @page { size: A4; margin: 1.5cm; }
        body { font-family: 'Times New Roman', serif; color: #000; line-height: 1.4; font-size: 11pt; }
        
        .no-print { display: block; margin-bottom: 20px; }
        @media print { 
            .no-print { display: none; } 
            body { -webkit-print-color-adjust: exact; }
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
        .text-right { text-align: right; }
        
        /* Footer Tanda Tangan */
        .footer { margin-top: 40px; width: 100%; page-break-inside: avoid; }
        .signature-table { width: 100%; }
        .signature-table td { text-align: center; vertical-align: top; width: 33%; }
        
        /* Tombol Cetak */
        .btn-print {
            position: fixed; top: 20px; right: 20px;
            background: #1e3a8a; color: white; border: none;
            padding: 10px 20px; border-radius: 8px; cursor: pointer;
            font-weight: bold; font-family: sans-serif; box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        .btn-print:hover { background: #1e40af; }
    </style>
</head>
<body>

    <button onclick="window.print()" class="btn-print no-print">Cetak / Simpan PDF</button>

    
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
        <h3>REKAPITULASI ABSENSI KELAS</h3>
        <p>
            <strong>Periode:</strong> <?php echo e(isset($startDate) ? \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') : '-'); ?> 
            s/d <?php echo e(isset($endDate) ? \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') : '-'); ?>

        </p>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th rowspan="2" width="5%">No</th>
                <th rowspan="2">Nama Kelas</th>
                <th rowspan="2" width="10%">Jml Siswa</th>
                <th colspan="4">Rincian Kehadiran</th>
                <th rowspan="2" width="10%">Persentase</th>
            </tr>
            <tr>
                <th width="10%">Hadir</th>
                <th width="10%">Telat</th>
                <th width="10%">Izin/Sakit</th>
                <th width="10%">Alpha</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $reportData ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                // Mencegah error "Undefined property" dengan menggunakan data_get
                // data_get aman karena mengecek property pada object, maupun key pada array.
                $hadir = data_get($data, 'hadir', 0);
                $telat = data_get($data, 'telat', 0);
                $izin_sakit = data_get($data, 'izin_sakit', 0);
                $alpha = data_get($data, 'alpha', 0);
                
                // Kalkulasi manual untuk Rate/Persentase jika propertinya tidak ada
                $total_logs = $hadir + $telat + $izin_sakit + $alpha;
                $calculated_rate = $total_logs > 0 ? round(($hadir / $total_logs) * 100, 1) : 0;
            ?>
            <tr>
                <td class="text-center"><?php echo e($loop->iteration); ?></td>
                <td><?php echo e(data_get($data, 'name', 'Kelas Tidak Diketahui')); ?></td>
                <td class="text-center"><?php echo e(data_get($data, 'total_students', 0)); ?></td>
                <td class="text-center"><?php echo e($hadir); ?></td>
                <td class="text-center"><?php echo e($telat); ?></td>
                <td class="text-center"><?php echo e($izin_sakit); ?></td>
                <td class="text-center"><?php echo e($alpha); ?></td>
                <td class="text-center font-bold"><?php echo e(data_get($data, 'rate', $calculated_rate)); ?>%</td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="8" class="text-center" style="padding: 20px;">Data tidak ditemukan pada periode ini.</td>
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
                    Waka Kesiswaan / Petugas
                    <br><br><br><br><br>
                    <strong><?php echo e(Auth::user()->name ?? 'Admin'); ?></strong><br>
                    NIP. .........................
                </td>
            </tr>
        </table>
    </div>

</body>
</html><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\reports\pdf_class_recap.blade.php ENDPATH**/ ?>