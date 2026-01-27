<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kehadiran - <?php echo e($ekskul->name ?? 'Umum'); ?></title>
    <style>
        @page { size: A4; margin: 2cm; }
        body { font-family: 'Times New Roman', serif; color: #000; line-height: 1.5; }
        .no-print { display: block; }
        @media print { 
            .no-print { display: none; } 
            body { -webkit-print-color-adjust: exact; }
        }
        
        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px double #000; padding-bottom: 15px; }
        .header h1 { font-size: 16pt; margin: 0; font-weight: bold; text-transform: uppercase; }
        .header h2 { font-size: 12pt; margin: 5px 0 0; font-weight: normal; }
        
        .meta-table { width: 100%; margin-bottom: 20px; font-size: 11pt; }
        .meta-table td { padding: 4px 0; vertical-align: top; }
        
        table.data { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 10pt; }
        table.data th, table.data td { border: 1px solid #000; padding: 8px; }
        table.data th { background-color: #f3f3f3; text-align: center; font-weight: bold; text-transform: uppercase; }
        table.data td { vertical-align: middle; }
        
        .footer { margin-top: 50px; width: 100%; page-break-inside: avoid; }
        .signature { float: right; width: 250px; text-align: center; }
        .signature p { margin-bottom: 70px; }
        
        /* Tombol Cetak Cantik */
        .btn-print {
            position: fixed; top: 20px; right: 20px;
            /* UPDATED: Blue-900 */
            background: #1e3a8a; color: white; border: none;
            padding: 10px 20px; border-radius: 8px; cursor: pointer;
            font-weight: bold; box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            font-family: sans-serif; display: flex; align-items: center; gap: 8px;
        }
        .btn-print:hover { background: #1e40af; }
    </style>
</head>
<body>

    <button onclick="window.print()" class="btn-print no-print">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
        Cetak Dokumen
    </button>

    <div class="header">
        <h1>Laporan Kehadiran Ekstrakurikuler</h1>
        <h2>SMP NEGERI 3 LAKBOK</h2>
        <p style="font-size: 10pt; margin: 0;">Jl. Raya Lakbok No. 123, Ciamis, Jawa Barat</p>
    </div>

    <?php if($ekskul): ?>
        <table class="meta-table">
            <tr>
                <td width="150"><strong>Nama Kegiatan</strong></td>
                <td width="20">:</td>
                <td><?php echo e($ekskul->name); ?></td>
            </tr>
            <tr>
                <td><strong>Periode</strong></td>
                <td>:</td>
                <td>
                    <?php echo e(\Carbon\Carbon::parse($startDate)->isoFormat('D MMMM Y')); ?> 
                    s/d 
                    <?php echo e(\Carbon\Carbon::parse($endDate)->isoFormat('D MMMM Y')); ?>

                </td>
            </tr>
            <tr>
                <td><strong>Pembina</strong></td>
                <td>:</td>
                <td><?php echo e($ekskul->coach_name ?? '-'); ?></td>
            </tr>
            <tr>
                <td><strong>Total Kehadiran</strong></td>
                <td>:</td>
                <td><?php echo e($attendances->count()); ?> Data</td>
            </tr>
        </table>

        <table class="data">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="20%">Tanggal</th>
                    <th width="15%">Waktu</th>
                    <th width="15%">Kelas</th>
                    <th>Nama Siswa</th>
                    <th width="10%">Ket</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $attendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td style="text-align: center;"><?php echo e($index + 1); ?></td>
                    <td style="text-align: center;"><?php echo e(\Carbon\Carbon::parse($log->date)->format('d/m/Y')); ?></td>
                    <td style="text-align: center;"><?php echo e($log->time_in); ?></td>
                    <td style="text-align: center;"><?php echo e($log->student->schoolClass->name ?? '-'); ?></td>
                    <td><?php echo e($log->student->name); ?></td>
                    <td style="text-align: center;">&#10003;</td> <!-- Centang -->
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px; font-style: italic;">
                        Tidak ada data kehadiran pada periode ini.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="footer">
            <div class="signature">
                <p>
                    Lakbok, <?php echo e(\Carbon\Carbon::now()->isoFormat('D MMMM Y')); ?><br>
                    Pembina Ekstrakurikuler,
                </p>
                <div style="font-weight: bold; text-decoration: underline;"><?php echo e($ekskul->coach_name ?? '.........................'); ?></div>
                <div>NIP. .........................</div>
            </div>
        </div>

    <?php else: ?>
        <div style="text-align: center; margin-top: 50px; color: #666;">
            <h3>Data tidak ditemukan</h3>
            <p>Silakan pilih filter kegiatan terlebih dahulu.</p>
        </div>
    <?php endif; ?>

</body>
</html><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/extracurriculars/print_reports.blade.php ENDPATH**/ ?>