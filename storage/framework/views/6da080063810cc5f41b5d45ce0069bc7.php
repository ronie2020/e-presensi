<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Karakter - <?php echo e(\Carbon\Carbon::parse($date)->format('d-m-Y')); ?></title>
    <style>
        @page { size: A4 landscape; margin: 1.5cm; }
        
        body { font-family: 'Times New Roman', serif; color: #000; line-height: 1.3; font-size: 11pt; }
        
        .no-print { display: block; }
        @media print { 
            .no-print { display: none !important; } 
            body { -webkit-print-color-adjust: exact; }
            .page-break { page-break-before: always; }
        }
        
        /* Header Kop Surat */
        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px double #000; padding-bottom: 10px; }
        .header h1 { font-size: 18pt; margin: 0; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        .header h2 { font-size: 14pt; margin: 5px 0 0; font-weight: bold; text-transform: uppercase; }
        .header p { font-size: 10pt; margin: 2px 0; font-style: italic; }

        /* Meta Info */
        .meta-table { width: 100%; margin-bottom: 15px; font-size: 11pt; border: none; }
        .meta-table td { padding: 2px 0; vertical-align: top; }
        .meta-title { font-weight: bold; width: 130px; }

        /* Summary Box (Diadaptasi dari Absensi) */
        .summary-box { border: 1px solid #000; padding: 15px; margin-bottom: 25px; display: flex; justify-content: space-around; background: #fafafa; }
        .summary-item { text-align: center; }
        .summary-val { font-weight: bold; font-size: 1.6em; display: block; margin-bottom: 3px; }
        .summary-label { font-size: 0.85em; text-transform: uppercase; color: #333; font-weight: bold; }

        /* Tables & Sections */
        .section-title { font-weight: bold; font-size: 12pt; margin-top: 25px; margin-bottom: 10px; text-transform: uppercase; border-left: 4px solid #000; padding-left: 8px; }
        table.data { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 10pt; }
        table.data th, table.data td { border: 1px solid #000; padding: 6px 4px; }
        table.data th { background-color: #f3f3f3; text-align: center; font-weight: bold; vertical-align: middle; height: 30px; }
        table.data td { vertical-align: middle; }
        table.data .center { text-align: center; }
        table.data .left { text-align: left; padding-left: 8px; }
        
        /* Kolom Kebiasaan */
        .col-habit { width: 8%; font-size: 9pt; }
        .check { font-family: DejaVu Sans, sans-serif; font-size: 14pt; font-weight: bold; }
        .check-yes { color: #000; } 
        .check-no { color: #ccc; font-size: 10pt; }
        .text-danger { color: #d32f2f; font-weight: bold; }

        /* Footer */
        .footer { margin-top: 30px; width: 100%; page-break-inside: avoid; }
        .signature-box { float: right; width: 250px; text-align: center; }
        .signature-box p { margin-bottom: 70px; }
        
        /* Legend */
        .legend { font-size: 9pt; margin-top: 10px; border: 1px solid #000; padding: 5px; width: fit-content; }
        
        /* Tombol Cetak */
        .btn-print {
            position: fixed; bottom: 30px; right: 30px;
            background: #2563eb; color: white; border: none;
            padding: 12px 24px; border-radius: 50px; cursor: pointer;
            font-weight: bold; box-shadow: 0 4px 15px rgba(37, 99, 235, 0.4);
            font-family: sans-serif; display: flex; align-items: center; gap: 8px; z-index: 1000;
        }
        .btn-print:hover { background: #1d4ed8; }
    </style>
</head>
<body>

    <button onclick="window.print()" class="btn-print no-print">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
        Cetak Laporan
    </button>

    <div class="header">
        <h1>LAPORAN MONITORING KEBIASAAN SISWA (7 HABITS)</h1>
        <h2>SMP NEGERI 3 LAKBOK</h2>
        <p>Jl. Mekarjaya No.199 Sidaharja Kec. Lakbok, Ciamis, Jawa Barat</p>
    </div>

    <table class="meta-table">
        <tr>
            <td class="meta-title">Periode Analisis</td>
            <td width="10">:</td>
            <td width="300">
                <?php if($periodType == 'weekly'): ?>
                    Minggu ke-<?php echo e(substr($date, 6)); ?>, Tahun <?php echo e(substr($date, 0, 4)); ?>

                <?php elseif($periodType == 'monthly'): ?>
                    Bulan <?php echo e(\Carbon\Carbon::parse($date . '-01')->translatedFormat('F Y')); ?>

                <?php else: ?>
                    <?php echo e(\Carbon\Carbon::parse($date)->translatedFormat('l, d F Y')); ?>

                <?php endif; ?>
            </td>
            
            <td class="meta-title">Kelas</td>
            <td width="10">:</td>
            <td><?php echo e($class->name ?? 'Semua Kelas (Global)'); ?></td>
        </tr>
        <tr>
            <td class="meta-title">Dicetak Oleh</td>
            <td>:</td>
            <td><?php echo e(auth()->user()->name ?? 'Administrator'); ?></td>

            <td class="meta-title">Total Siswa</td>
            <td>:</td>
            <td><?php echo e($students->count()); ?> Siswa</td>
        </tr>
    </table>

    
    <?php
        $totalStudents = $students->count();
        $submittedStudents = $students->where('habit_status', 'submitted');
        $missingStudents = $students->where('habit_status', 'missing');
        
        $submittedCount = $submittedStudents->count();
        $missingCount = $missingStudents->count();
        $percentage = $totalStudents > 0 ? round(($submittedCount / $totalStudents) * 100) : 0;
    ?>

    <div class="summary-box">
        <div class="summary-item">
            <span class="summary-val"><?php echo e($totalStudents); ?></span>
            <span class="summary-label">Total Siswa</span>
        </div>
        <div class="summary-item" style="color: #059669;">
            <span class="summary-val"><?php echo e($submittedCount); ?></span>
            <span class="summary-label">Sudah Lapor</span>
        </div>
        <div class="summary-item" style="color: #e11d48;">
            <span class="summary-val"><?php echo e($missingCount); ?></span>
            <span class="summary-label">Belum Lapor</span>
        </div>
        <div class="summary-item" style="color: #2563eb;">
            <span class="summary-val"><?php echo e($percentage); ?>%</span>
            <span class="summary-label">Partisipasi</span>
        </div>
    </div>

    
    <?php if($submittedCount > 0): ?>
        <div class="section-title">A. Daftar Siswa Sudah Melapor</div>
        <table class="data">
            <thead>
                <tr>
                    <th width="3%" rowspan="2">No</th>
                    <th width="9%" rowspan="2">Tgl Lapor</th> <!-- TAMBAHAN KOLOM TANGGAL -->
                    <th width="18%" rowspan="2">Nama Siswa</th>
                    <th width="10%" rowspan="2">Kelas</th>
                    <th colspan="7">Capaian Kebiasaan Baik</th>
                    <th width="6%" rowspan="2">Skor</th>
                </tr>
                <tr>
                    <!-- Label Header Sesuai Dashboard -->
                    <th class="col-habit">1. Bangun &<br>Mandi</th>
                    <th class="col-habit">2. Shalat /<br>Ibadah</th>
                    <th class="col-habit">3. Olahraga</th>
                    <th class="col-habit">4. Makan<br>Sehat</th>
                    <th class="col-habit">5. Belajar<br>Mandiri</th>
                    <th class="col-habit">6. Bantu<br>Ortu</th>
                    <th class="col-habit">7. Tidur<br>Tepat</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $submittedStudents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php 
                        $h = $student->habit_data; 
                        
                        // --- LOGIKA CEK STATUS ---
                        $checks = [];
                        $checks[1] = $h->habit_1 && $h->habit_2;
                        $checks[2] = $h->prayer_subuh || $h->prayer_dzuhur || $h->prayer_ashar || 
                                     $h->prayer_maghrib || $h->prayer_isya || $h->prayer_dhuha;
                        $checks[3] = $h->habit_3;
                        $checks[4] = $h->habit_5;
                        $checks[5] = $h->habit_4;
                        $checks[6] = $h->habit_6;
                        $checks[7] = $h->habit_7;

                        $totalScore = count(array_filter($checks));
                        $tglLapor = \Carbon\Carbon::parse($h->report_date)->format('d/m/Y');
                    ?>
                <tr>
                    <td class="center"><?php echo e($loop->iteration); ?></td>
                    <td class="center"><?php echo e($tglLapor); ?></td> <!-- CETAK TANGGAL LAPOR -->
                    <td class="left" style="text-transform: uppercase; font-weight: bold;"><?php echo e($student->name); ?></td>
                    <td class="center"><?php echo e($student->schoolClass->name ?? '-'); ?></td>
                    
                    
                    <?php for($i = 1; $i <= 7; $i++): ?>
                        <td class="center">
                            <span class="check <?php echo e($checks[$i] ? 'check-yes' : 'check-no'); ?>">
                                <?php echo $checks[$i] ? '&#10003;' : '-'; ?>

                            </span>
                        </td>
                    <?php endfor; ?>

                    
                    <td class="center" style="font-weight: bold;">
                        <?php echo e($totalScore); ?>/7
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    <?php endif; ?>

    
    <?php if($missingCount > 0): ?>
        <div class="section-title text-danger">B. Daftar Siswa Belum Melapor</div>
        <table class="data">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="45%">Nama Siswa</th>
                    <th width="25%">Kelas</th>
                    <th width="25%">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $missingStudents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td class="center"><?php echo e($loop->iteration); ?></td>
                    <td class="left" style="text-transform: uppercase; font-weight: bold;"><?php echo e($student->name); ?></td>
                    <td class="center"><?php echo e($student->schoolClass->name ?? '-'); ?></td>
                    <td class="center text-danger" style="font-style: italic;">Alfa / Belum Mengisi Jurnal</td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    <?php endif; ?>

    <?php if($totalStudents == 0): ?>
        <div style="text-align: center; padding: 50px; border: 1px dashed #ccc; margin-top: 20px;">
            Data siswa tidak ditemukan untuk periode/kelas ini.
        </div>
    <?php else: ?>
        <div class="legend" style="page-break-inside: avoid;">
            <strong>Keterangan Tabel Capaian:</strong><br>
            (&#10003;) : Melaksanakan Kebiasaan Baik<br>
            (-) : Tidak Melaksanakan Kebiasaan
        </div>
    <?php endif; ?>

    <div class="footer">
        <div class="signature-box">
            <p>
                Lakbok, <?php echo e(\Carbon\Carbon::now()->isoFormat('D MMMM Y')); ?><br>
                Mengetahui,<br>
                <?php echo e($class ? 'Wali Kelas / Guru BK' : 'Kepala Sekolah'); ?>

            </p>
            <div style="font-weight: bold; text-decoration: underline; margin-top: 20px;">
                ( ........................................... )
            </div>
            <div style="margin-top: 5px;">NIP. .....................................</div>
        </div>
    </div>

</body>
</html><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/habits/print.blade.php ENDPATH**/ ?>