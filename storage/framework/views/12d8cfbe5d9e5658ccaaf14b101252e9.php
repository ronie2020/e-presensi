<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($title); ?></title>
    <style>
        @page { size: A4 portrait; margin: 1.5cm; }
        
        /* Font disesuaikan menjadi Bookman Old Style */
        body { font-family: 'Bookman Old Style', Bookman, Georgia, serif; color: #000; line-height: 1.4; font-size: 11pt; }
        
        .no-print { display: block; margin-bottom: 20px; }
        @media print { 
            .no-print { display: none !important; } 
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            @page { margin: 1.5cm; }
        }
        
        /* --- KOP SURAT STYLE --- */
        .kop-surat { width: 100%; border-collapse: collapse; margin-bottom: 2px; }
        .kop-surat td { padding: 0; vertical-align: middle; }
        .kop-dinas { font-size: 14pt; letter-spacing: 0.025em; margin-bottom: 4px; line-height: 1.1; }
        .kop-sekolah { font-size: 22pt; font-weight: bold; letter-spacing: 0.05em; margin-bottom: 4px; line-height: 1.1; }
        .kop-alamat { font-size: 12pt; font-style: normal; line-height: 1.2; }
        .kop-kontak { font-size: 11pt; margin-top: 4px; }
        .garis-kop { border: none; border-top: 4px solid #000; border-bottom: 1.5px solid #000; height: 2px; margin-bottom: 15px; }

        /* --- JUDUL DOKUMEN --- */
        .report-title { text-align: center; margin-bottom: 20px; }
        .report-title h3 { margin: 0; text-decoration: underline; text-transform: uppercase; font-size: 14pt; font-weight: bold; }
        .report-title p { margin: 5px 0 0; font-size: 11pt; }

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
        
        /* Tombol Cetak (Diseragamkan) */
        .btn-print {
            position: fixed; top: 20px; right: 20px;
            background: #1e3a8a; color: white; border: none;
            padding: 10px 20px; border-radius: 8px; cursor: pointer;
            font-weight: bold; box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            font-family: sans-serif; display: flex; align-items: center; gap: 8px; z-index: 9999;
        }
        .btn-print:hover { background: #1e40af; }
    </style>
</head>
<body>

    <button onclick="window.print()" class="btn-print no-print">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
        Cetak / Simpan PDF
    </button>

    
    <table class="kop-surat">
        <tr>
            <td width="15%" style="text-align: center;">
                <img src="<?php echo e(asset('img/logo_ciamis.png')); ?>" alt="Logo Ciamis" style="width: 85px; height: auto; object-fit: contain;" onerror="this.style.display='none'">
            </td>
            <td width="70%" style="text-align: center;">
                <div class="kop-dinas">PEMERINTAH KABUPATEN CIAMIS</div>
                <div class="kop-sekolah">SMP NEGERI 3 LAKBOK</div>
                <div class="kop-alamat">Jalan Mekarjaya No.199, Sidaharja</div>
                <div class="kop-alamat">Kecamatan Lakbok, Kabupaten Ciamis 46385</div>
                <div class="kop-kontak">
                    Laman: <a href="http://www.smpn3lakbok.sch.id" style="color: #1d4ed8; text-decoration: underline;">www.smpn3lakbok.sch.id</a> 
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                    E-mail: netila.smp@gmail.com
                </div>
            </td>
            <td width="15%" style="text-align: center;">
                <img src="<?php echo e(asset('img/logo_sekolah.png')); ?>" alt="Logo SMP" style="width: 90px; height: auto; object-fit: contain;" onerror="this.style.display='none'">
            </td>
        </tr>
    </table>
    <hr class="garis-kop">

    
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