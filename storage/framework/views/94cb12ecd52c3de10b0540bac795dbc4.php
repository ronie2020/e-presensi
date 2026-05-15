<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Absensi Harian - <?php echo e($range['label']); ?></title>
    <style>
        @page { size: A4; margin: 2cm; }
        body { font-family: 'Bookman Old Style', Bookman, Georgia, serif; color: #000; line-height: 1.5; font-size: 11pt; }
        
        .no-print { display: block; }
        @media print { 
            .no-print { display: none !important; } 
            body { -webkit-print-color-adjust: exact; }
            .page-break { page-break-before: always; }
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
        .doc-title { text-align: center; margin-bottom: 20px; }
        .doc-title h3 { margin: 0; font-size: 14pt; text-decoration: underline; font-weight: bold; text-transform: uppercase; }
        .doc-title p { margin: 5px 0 0 0; font-size: 11pt; }

        /* Meta Info */
        .meta-table { width: 100%; margin-bottom: 20px; font-size: 11pt; border: none; }
        .meta-table td { padding: 2px 0; vertical-align: top; }
        .meta-title { font-weight: bold; width: 150px; }

        /* Tables */
        .section-title { font-weight: bold; font-size: 12pt; margin-top: 20px; margin-bottom: 8px; text-decoration: underline; }
        table.data { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 10pt; }
        table.data th, table.data td { border: 1px solid #000; padding: 6px 8px; }
        table.data th { background-color: #f3f3f3; text-align: center; font-weight: bold; text-transform: uppercase; }
        table.data td { vertical-align: middle; }
        table.data .center { text-align: center; }
        table.data .right { text-align: right; }
        
        /* Helpers */
        .text-danger { color: #d32f2f; font-weight: bold; }
        .text-muted { color: #666; font-style: italic; }
        
        /* Footer */
        .footer { margin-top: 40px; width: 100%; page-break-inside: avoid; }
        .signature-box { float: right; width: 250px; text-align: center; }
        .signature-box p { margin-bottom: 70px; }
        
        /* Tombol Cetak */
        .btn-print {
            position: fixed; top: 20px; right: 20px;
            background: #2563eb; color: white; border: none;
            padding: 10px 20px; border-radius: 8px; cursor: pointer;
            font-weight: bold; box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            font-family: sans-serif; display: flex; align-items: center; gap: 8px; z-index: 1000;
        }
        .btn-print:hover { background: #1d4ed8; }
        .summary-box { border: 1px solid #000; padding: 10px; margin-bottom: 20px; display: flex; justify-content: space-around; background: #fafafa; }
        .summary-item { text-align: center; }
        .summary-val { font-weight: bold; font-size: 1.2em; display: block; }
        .summary-label { font-size: 0.9em; text-transform: uppercase; color: #555; }
    </style>
</head>
<body>

    <button onclick="window.print()" class="btn-print no-print">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
        Cetak Laporan
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
        <h3>LAPORAN ABSENSI SISWA</h3>
    </div>

    <table class="meta-table">
        <tr>
            <td class="meta-title">Periode</td>
            <td width="10">:</td>
            <td><?php echo e($range['label']); ?></td>
        </tr>
        <tr>
            <td class="meta-title">Dicetak Oleh</td>
            <td>:</td>
            <td><?php echo e(auth()->user()->name ?? 'Administrator'); ?></td>
        </tr>
    </table>

    <div class="summary-box">
        <div class="summary-item">
            <span class="summary-val"><?php echo e($stats['hadir'] + $stats['terlambat']); ?></span>
            <span class="summary-label">Hadir</span>
        </div>
        <div class="summary-item">
            <span class="summary-val"><?php echo e($stats['sakit']); ?></span>
            <span class="summary-label">Sakit</span>
        </div>
        <div class="summary-item">
            <span class="summary-val"><?php echo e($stats['izin']); ?></span>
            <span class="summary-label">Izin</span>
        </div>
        <div class="summary-item">
            <span class="summary-val"><?php echo e($stats['alfa']); ?></span>
            <span class="summary-label">Alfa</span>
        </div>
        <div class="summary-item">
            <span class="summary-val"><?php echo e($stats['belum']); ?></span>
            <span class="summary-label">Belum Absen</span>
        </div>
    </div>

    
    <?php if($attendancesHadir->count() > 0): ?>
        <div class="section-title">A. Daftar Siswa Hadir</div>
        <table class="data">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    
                    <?php if($range['type'] != 'daily'): ?> 
                        <th width="15%">Tanggal</th> 
                    <?php endif; ?>
                    <th width="25%">Nama Siswa</th>
                    <th width="15%">Kelas</th>
                    <th width="15%">Jam Masuk</th>
                    <th width="15%">Jam Pulang</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $attendancesHadir; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $att): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td class="center"><?php echo e($index + 1); ?></td>
                    
                    <?php if($range['type'] != 'daily'): ?> 
                        <td class="center"><?php echo e(\Carbon\Carbon::parse($att->attendance_date)->format('d/m/Y')); ?></td> 
                    <?php endif; ?>
                    <td><?php echo e($att->student->name); ?></td>
                    <td class="center"><?php echo e($att->student->schoolClass->name ?? '-'); ?></td>
                    <td class="center"><?php echo e($att->time_in ? \Carbon\Carbon::parse($att->time_in)->format('H:i') : '-'); ?></td>
                    <td class="center"><?php echo e($att->time_out ? \Carbon\Carbon::parse($att->time_out)->format('H:i') : '-'); ?></td>
                    <td>
                        <?php echo e($att->status); ?>

                        <?php if($att->status == 'Terlambat'): ?> <small class="text-danger">(- Poin)</small> <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    <?php endif; ?>

    
    <?php if($attendancesLain->count() > 0): ?>
        <div class="section-title">B. Daftar Ketidakhadiran (Izin / Sakit / Alfa)</div>
        <table class="data">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    
                    <?php if($range['type'] != 'daily'): ?> 
                        <th width="15%">Tanggal</th> 
                    <?php endif; ?>
                    <th width="30%">Nama Siswa</th>
                    <th width="15%">Kelas</th>
                    <th width="15%">Status</th>
                    <th>Catatan / Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $attendancesLain; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $att): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td class="center"><?php echo e($index + 1); ?></td>
                    
                    <?php if($range['type'] != 'daily'): ?> 
                        <td class="center"><?php echo e(\Carbon\Carbon::parse($att->attendance_date)->format('d/m/Y')); ?></td> 
                    <?php endif; ?>
                    <td><?php echo e($att->student->name); ?></td>
                    <td class="center"><?php echo e($att->student->schoolClass->name ?? '-'); ?></td>
                    <td class="center" style="font-weight: bold;">
                        <?php echo e($att->status); ?>

                        
                        <?php if(in_array($att->status, ['Alfa', 'Alpa'])): ?>
                            <br><small class="text-danger">(-10 Poin)</small>
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($att->notes ?? '-'); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    <?php endif; ?>

    
    <?php if($belumAbsenList->count() > 0): ?>
        <div class="section-title text-danger">C. Daftar Siswa Belum Absen</div>
        <table class="data">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="40%">Nama Siswa</th>
                    <th width="20%">Kelas</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $belumAbsenList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td class="center"><?php echo e($index + 1); ?></td>
                    <td><?php echo e($student->name); ?></td>
                    <td class="center"><?php echo e($student->schoolClass->name ?? '-'); ?></td>
                    <td class="center text-muted">Belum ada keterangan</td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div class="footer">
        <div class="signature-box">
            <p>
                Lakbok, <?php echo e(\Carbon\Carbon::now()->isoFormat('D MMMM Y')); ?><br>
                Mengetahui,<br>
                Kepala Sekolah / Guru Piket
            </p>
            <div style="font-weight: bold; text-decoration: underline; margin-top: 20px;">
                ( ........................................... )
            </div>
            <div style="margin-top: 5px;">NIP. .....................................</div>
        </div>
    </div>

</body>
</html><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/reports/print_daily.blade.php ENDPATH**/ ?>