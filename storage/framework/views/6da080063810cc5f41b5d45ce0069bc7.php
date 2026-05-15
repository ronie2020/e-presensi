<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Karakter - <?php echo e(\Carbon\Carbon::parse($date)->format('d-m-Y')); ?></title>
    <style>
        @page { size: A4 landscape; margin: 1.5cm; }
        
        body { font-family: 'Bookman Old Style', Bookman, Georgia, serif; color: #000; line-height: 1.3; font-size: 11pt; }
        
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

        /* --- JUDUL & INFO (GAYA SPPD) --- */
        .doc-title { text-align: center; margin-bottom: 20px; }
        .doc-title h3 { margin: 0; font-size: 14pt; text-decoration: underline; font-weight: bold; text-transform: uppercase; }
        .doc-title p { margin: 2px 0 0 0; font-size: 11pt; }

        .sppd-table { width: 100%; margin-bottom: 20px; font-size: 11pt; border-collapse: collapse; }
        .sppd-table td { padding: 3px 5px; vertical-align: top; }
        .sppd-label { width: 18%; font-weight: bold; }
        .sppd-colon { width: 2%; text-align: center; }

        /* Summary Box */
        .summary-box { border: 1px solid #000; padding: 10px 15px; margin-bottom: 25px; display: flex; justify-content: space-around; background: #fafafa; }
        .summary-item { text-align: center; }
        .summary-val { font-weight: bold; font-size: 1.5em; display: block; margin-bottom: 2px; }
        .summary-label { font-size: 0.85em; text-transform: uppercase; color: #333; font-weight: bold; }

        /* Tables & Sections */
        .section-title { font-weight: bold; font-size: 11pt; margin-top: 15px; margin-bottom: 8px; text-transform: uppercase; }
        table.data { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 10pt; }
        table.data th, table.data td { border: 1px solid #000; padding: 5px 4px; }
        table.data th { background-color: #f3f3f3; text-align: center; font-weight: bold; vertical-align: middle; height: 25px; }
        table.data td { vertical-align: middle; }
        table.data .center { text-align: center; }
        table.data .left { text-align: left; padding-left: 8px; }
        
        /* Kolom Kebiasaan */
        .col-habit { width: 8%; font-size: 9pt; }
        .check { font-family: DejaVu Sans, sans-serif; font-size: 12pt; font-weight: bold; }
        .check-yes { color: #000; } 
        .check-no { color: #ccc; font-size: 10pt; }
        .text-danger { color: #d32f2f; font-weight: bold; }

        /* Footer */
        .footer { margin-top: 30px; width: 100%; page-break-inside: avoid; }
        .signature-box { float: right; width: 280px; text-align: center; }
        .signature-box p { margin-bottom: 65px; }
        
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
                <!-- Logo Kanan (Berdasarkan gambar sebelumnya) -->
                <img src="<?php echo e(asset('img/logo_sekolah.png')); ?>" alt="Logo SMP" style="width: 90px; height: auto; object-fit: contain;" onerror="this.style.display='none'">
            </td>
        </tr>
    </table>
    <hr class="garis-kop">

    
    <?php
        $periodText = '';
        if($periodType == 'weekly') {
            $periodText = 'Minggu ke-' . substr($date, 6) . ', Tahun ' . substr($date, 0, 4);
        } elseif($periodType == 'monthly') {
            $periodText = 'Bulan ' . \Carbon\Carbon::parse($date . '-01')->translatedFormat('F Y');
        } else {
            $periodText = \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y');
        }
    ?>

    
    <div class="doc-title">
        <h3>LAPORAN MONITORING KEBIASAAN SISWA</h3>
        <p>Program Pembiasaan 7 Habits Siswa</p>
    </div>

    
    <table class="sppd-table">
        <tr>
            <td class="sppd-label">1. Periode Analisis</td>
            <td class="sppd-colon">:</td>
            <td><?php echo e($periodText); ?></td>
        </tr>
        <tr>
            <td class="sppd-label">2. Kelas / Rombel</td>
            <td class="sppd-colon">:</td>
            <td><strong><?php echo e($class->name ?? 'Semua Kelas (Global)'); ?></strong></td>
        </tr>
        <tr>
            <td class="sppd-label">3. Total Data Siswa</td>
            <td class="sppd-colon">:</td>
            <td><?php echo e($students->count()); ?> Siswa</td>
        </tr>
        <tr>
            <td class="sppd-label">4. Dicetak Oleh</td>
            <td class="sppd-colon">:</td>
            <td><?php echo e(auth()->user()->name ?? 'Administrator'); ?></td>
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
            <span class="summary-label">Partisipasi Capaian</span>
        </div>
    </div>

    
    <?php if($submittedCount > 0): ?>
        <div class="section-title">A. Rincian Capaian Siswa (Sudah Melapor)</div>
        <table class="data">
            <thead>
                <tr>
                    <th width="3%" rowspan="2">No</th>
                    <th width="9%" rowspan="2">Tgl Lapor</th>
                    <th width="18%" rowspan="2">Nama Siswa</th>
                    <th width="10%" rowspan="2">Kelas</th>
                    <th colspan="7">Capaian Kebiasaan Baik</th>
                    <th width="6%" rowspan="2">Skor</th>
                </tr>
                <tr>
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
                    <td class="center"><?php echo e($tglLapor); ?></td>
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