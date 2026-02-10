<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keagamaan - <?php echo e($range['label'] ?? 'Laporan'); ?></title>
    <style>
        @page { size: A4; margin: 1.5cm 2cm; }
        body { font-family: 'Times New Roman', serif; color: #000; line-height: 1.4; font-size: 11pt; }
        
        .no-print { display: block; }
        @media print { 
            .no-print { display: none !important; } 
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .page-break { page-break-before: always; }
        }
        
        /* Header */
        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px double #000; padding-bottom: 10px; }
        .header h1 { font-size: 14pt; margin: 0; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        .header h2 { font-size: 12pt; margin: 5px 0 0; font-weight: normal; }
        .header p { font-size: 10pt; margin: 2px 0; font-style: italic; }

        /* Meta Info */
        .meta-table { width: 100%; margin-bottom: 15px; font-size: 10pt; border: none; }
        .meta-table td { padding: 2px 0; vertical-align: top; }
        .meta-title { font-weight: bold; width: 130px; }

        /* Tables */
        .section-title { font-weight: bold; font-size: 11pt; margin-top: 15px; margin-bottom: 5px; text-decoration: underline; }
        table.data { width: 100%; border-collapse: collapse; margin-bottom: 10px; font-size: 10pt; }
        table.data th, table.data td { border: 1px solid #000; padding: 4px 6px; }
        table.data th { background-color: #f0f0f0; text-align: center; font-weight: bold; text-transform: uppercase; font-size: 9pt; }
        table.data td { vertical-align: middle; }
        table.data .center { text-align: center; }
        table.data .right { text-align: right; }
        
        /* Helpers */
        .text-danger { color: #d32f2f; font-weight: bold; }
        .text-muted { color: #666; font-style: italic; }
        .bg-yellow-soft { background-color: #fffbeb !important; } /* Warna Dhuha */
        .bg-blue-soft { background-color: #eff6ff !important; } /* Warna Dhuhur */
        
        /* Footer */
        .footer { margin-top: 30px; width: 100%; page-break-inside: avoid; }
        .signature-box { float: right; width: 200px; text-align: center; font-size: 10pt; }
        .signature-box p { margin-bottom: 60px; }
        
        /* Tombol Cetak */
        .btn-print {
            position: fixed; top: 20px; right: 20px;
            background: #2563eb; color: white; border: none;
            padding: 10px 20px; border-radius: 8px; cursor: pointer;
            font-weight: bold; box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            font-family: sans-serif; display: flex; align-items: center; gap: 8px; z-index: 1000;
        }
        .btn-print:hover { background: #1d4ed8; }
        
        .summary-box { border: 1px solid #000; padding: 10px; margin-bottom: 15px; display: flex; justify-content: space-around; background: #fafafa; }
        .summary-item { text-align: center; }
        .summary-val { font-weight: bold; font-size: 1.1em; display: block; }
        .summary-label { font-size: 0.8em; text-transform: uppercase; color: #555; }
    </style>
</head>
<body>

    <button onclick="window.print()" class="btn-print no-print">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
        Cetak Laporan
    </button>

    <div class="header">
        <h1>Laporan Kegiatan Keagamaan</h1>
        <h2>SMP NEGERI 3 LAKBOK</h2>
        <p>Jl. Mekarjaya No.199 Sidaharja Kec. Lakbok, Ciamis, Jawa Barat</p>
    </div>

    <table class="meta-table">
        <tr>
            <td class="meta-title">Periode</td>
            <td width="10">:</td>
            <td><?php echo e($range['label'] ?? '-'); ?></td>
        </tr>
        <tr>
            <td class="meta-title">Jenis Laporan</td>
            <td>:</td>
            <td>
                <?php if(isset($viewMode) && $viewMode == 'rekap'): ?>
                    Rekapitulasi Shalat Dhuha & Dhuhur
                <?php else: ?>
                    Detail Daftar Siswa (Shalat <?php echo e($selectedActivity ?? '-'); ?>)
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td class="meta-title">Dicetak Oleh</td>
            <td>:</td>
            <td><?php echo e(auth()->user()->name ?? 'Administrator'); ?></td>
        </tr>
    </table>

    
    
    
    <?php if(isset($viewMode) && $viewMode == 'rekap'): ?>
        
        <table class="data">
            <thead>
                <tr>
                    <th rowspan="2" width="5%">No</th>
                    <th rowspan="2" width="15%">Kelas</th>
                    <th rowspan="2" width="10%">Jml Siswa</th>
                    
                    <th colspan="2" class="bg-yellow-soft" style="border-bottom: 1px solid #ccc;">SHALAT DHUHA</th>
                    
                    <th colspan="2" class="bg-blue-soft" style="border-bottom: 1px solid #ccc;">SHALAT DHUHUR</th>
                </tr>
                <tr>
                    
                    <th class="bg-yellow-soft" width="15%">Hadir</th>
                    <th class="bg-yellow-soft" width="15%">Persentase</th>
                    
                    <th class="bg-blue-soft" width="15%">Hadir</th>
                    <th class="bg-blue-soft" width="15%">Persentase</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $classRecap; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $rekap): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td class="center"><?php echo e($index + 1); ?></td>
                    <td style="font-weight: bold;"><?php echo e($rekap->className ?? $rekap['className'] ?? '-'); ?></td>
                    <td class="center"><?php echo e($rekap->total_siswa ?? 0); ?></td>
                    
                    
                    <td class="center bg-yellow-soft">
                        <?php echo e($rekap->dhuha['hadir'] ?? 0); ?>

                    </td>
                    <td class="center bg-yellow-soft">
                        <strong><?php echo e($rekap->dhuha['percent'] ?? 0); ?>%</strong>
                    </td>

                    
                    <td class="center bg-blue-soft">
                        <?php echo e($rekap->dhuhur['hadir'] ?? 0); ?>

                    </td>
                    <td class="center bg-blue-soft">
                        <strong><?php echo e($rekap->dhuhur['percent'] ?? 0); ?>%</strong>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

    
    
    
    <?php else: ?>
    
        <div class="summary-box">
            <div class="summary-item">
                <span class="summary-val"><?php echo e($hadirCount ?? 0); ?></span>
                <span class="summary-label">Hadir</span>
            </div>
            <div class="summary-item">
                <span class="summary-val"><?php echo e($izinUzurCount ?? 0); ?></span>
                <span class="summary-label">Uzur / Izin</span>
            </div>
            <div class="summary-item">
                <span class="summary-val"><?php echo e($alfaCount ?? 0); ?></span>
                <span class="summary-label">Alfa</span>
            </div>
            <div class="summary-item">
                <span class="summary-val"><?php echo e($belumAbsenCount ?? 0); ?></span>
                <span class="summary-label">Belum Absen</span>
            </div>
        </div>

        
        <?php if(isset($attendancesHadir) && $attendancesHadir->count() > 0): ?>
            <div class="section-title">A. Daftar Siswa Hadir</div>
            <table class="data">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <?php if($range['type'] != 'daily'): ?> <th width="15%">Tanggal</th> <?php endif; ?>
                        <th width="35%">Nama Siswa</th>
                        <th width="20%">Kelas</th>
                        <th width="20%">Waktu Absen</th>
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
                        <td class="center"><?php echo e($att->created_at->format('H:i')); ?></td>
                        <td><?php echo e($att->notes ?? '-'); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php endif; ?>

        
        <?php if(isset($attendancesUzur) && $attendancesUzur->count() > 0): ?>
            <div class="section-title">B. Daftar Ketidakhadiran (Uzur / Izin / Alfa)</div>
            <table class="data">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <?php if($range['type'] != 'daily'): ?> <th width="15%">Tanggal</th> <?php endif; ?>
                        <th width="35%">Nama Siswa</th>
                        <th width="20%">Kelas</th>
                        <th width="15%">Status</th>
                        <th>Catatan / Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $attendancesUzur; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $att): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                                <br><small class="text-danger">(- Poin)</small>
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($att->notes ?? '-'); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php endif; ?>

        
        <?php if(isset($belumAbsenList) && $belumAbsenList->count() > 0): ?>
            <div class="section-title text-danger">C. Daftar Siswa Belum Absen</div>
            <table class="data">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="45%">Nama Siswa</th>
                        <th width="25%">Kelas</th>
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

    <?php endif; ?> 

    <div class="footer">
        <div class="signature-box">
            <p>
                Lakbok, <?php echo e(\Carbon\Carbon::now()->isoFormat('D MMMM Y')); ?><br>
                Mengetahui,<br>
                Koordinator Keagamaan
            </p>
            <div style="font-weight: bold; text-decoration: underline; margin-top: 20px;">
                ( ........................................... )
            </div>
            <div style="margin-top: 5px;">NIP. .....................................</div>
        </div>
    </div>

</body>
</html><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/reports/print_religious.blade.php ENDPATH**/ ?>