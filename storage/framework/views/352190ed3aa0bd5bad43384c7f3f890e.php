<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($title); ?></title>
    <style>
        @page { size: A4; margin: 2cm; }
        
        /* Font disesuaikan menjadi Bookman Old Style */
        body { font-family: 'Bookman Old Style', Bookman, Georgia, serif; color: #000; line-height: 1.5; }
        
        .no-print { display: block; }
        @media print { 
            .no-print { display: none !important; } 
            body { -webkit-print-color-adjust: exact; }
        }
        
        /* --- KOP SURAT STYLE (Sama dengan halaman SPPD) --- */
        .kop-surat { width: 100%; border-collapse: collapse; margin-bottom: 2px; }
        .kop-surat td { padding: 0; vertical-align: middle; }
        .kop-dinas { font-size: 14pt; letter-spacing: 0.025em; margin-bottom: 4px; line-height: 1.1; }
        .kop-sekolah { font-size: 22pt; font-weight: bold; letter-spacing: 0.05em; margin-bottom: 4px; line-height: 1.1; }
        .kop-alamat { font-size: 12pt; font-style: normal; line-height: 1.2; }
        .kop-kontak { font-size: 11pt; margin-top: 4px; }
        .garis-kop { border: none; border-top: 4px solid #000; border-bottom: 1.5px solid #000; height: 2px; margin-bottom: 15px; }

        /* --- JUDUL DOKUMEN --- */
        .doc-title { text-align: center; margin-bottom: 20px; }
        .doc-title h3 { margin: 0; font-size: 14pt; text-decoration: underline; font-weight: bold; text-transform: uppercase; }
        .doc-title p { margin: 5px 0 0 0; font-size: 11pt; }

        /* --- TABEL DATA --- */
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

    
    <div class="doc-title">
        <h3>LAPORAN MONITORING IZIN SISWA</h3>
        <p>
            <strong>Periode:</strong> <?php echo e(request('date') ? \Carbon\Carbon::parse(request('date'))->translatedFormat('d F Y') : 'Semua Waktu'); ?> 
            | <strong>Status:</strong> <?php echo e(request('status') ? ucfirst(request('status')) : 'Semua'); ?>

        </p>
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
                    <?php echo e($permit->time_out->format('H:i')); ?><br>
                    <small><?php echo e($permit->time_out->format('d/m/y')); ?></small>
                </td>
                <td style="text-align: center;">
                    <?php if($permit->time_in): ?>
                        <?php echo e($permit->time_in->format('H:i')); ?>

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
</html><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/permit/print.blade.php ENDPATH**/ ?>