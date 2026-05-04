<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Nilai - <?php echo e($exam->title); ?></title>
    <style>
        @page { size: A4; margin: 1.5cm; }
        
        body { 
            /* Ubah font utama menjadi Bookman Old Style */
            font-family: 'Bookman Old Style', Bookman, Georgia, serif; 
            color: #000; 
            line-height: 1.3; 
            font-size: 11pt; 
        }
        
        .no-print { display: block; margin-bottom: 20px; text-align: right; }
        @media print { 
            .no-print { display: none; } 
            body { -webkit-print-color-adjust: exact; }
        }
        
        /* Kop Surat (Format Tabel untuk Kompatibilitas Ekspor PDF) */
        .kop-table { width: 100%; border-collapse: collapse; }
        .kop-table td { vertical-align: middle; }
        .kop-teks { text-align: center; line-height: 1.1; }
        
        /* Garis Ganda Kop Surat */
        .garis-kop { border-bottom: 3px solid black; margin-top: 8px; margin-bottom: 2px; }
        .garis-kop-bawah { border-bottom: 1px solid black; margin-bottom: 20px; }

        /* Judul Laporan */
        .report-title { text-align: center; margin-bottom: 20px; }
        .report-title h3 { margin: 0; text-decoration: underline; text-transform: uppercase; font-size: 12pt; }
        .report-info { margin-top: 10px; font-size: 10pt; width: 100%; }
        .report-info td { padding: 4px 2px; vertical-align: top; }

        /* Tabel Data */
        table.data { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 10pt; }
        table.data th, table.data td { border: 1px solid #000; padding: 6px; }
        table.data th { background-color: #e0e0e0; text-align: center; font-weight: bold; vertical-align: middle; }
        table.data td { vertical-align: middle; }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        
        /* Footer Tanda Tangan */
        .footer { margin-top: 40px; width: 100%; page-break-inside: avoid; }
        .signature-table { width: 100%; }
        .signature-table td { text-align: center; vertical-align: top; width: 35%; }
        
        /* Tombol Cetak */
        .btn-print {
            background: #1e3a8a; color: white; border: none;
            padding: 10px 20px; border-radius: 6px; cursor: pointer;
            font-weight: bold; font-family: sans-serif; box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            text-decoration: none; display: inline-block;
        }
        .btn-print:hover { background: #1e40af; }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()" class="btn-print">
            🖨️ Cetak / Simpan PDF
        </button>
    </div>

    
    <table class="kop-table">
        <tr>
            <td width="15%" align="left">
                <img src="<?php echo e(asset('img/logo_ciamis.png')); ?>" alt="Logo Ciamis" style="width: 85px; height: auto;" onerror="this.style.display='none'">
            </td>
            <td class="kop-teks">
                <div style="font-size: 14pt; margin-bottom: 4px;">PEMERINTAH KABUPATEN CIAMIS</div>
                <div style="font-size: 22pt; font-weight: bold; letter-spacing: 1px; margin-bottom: 4px;">SMP NEGERI 3 LAKBOK</div>
                <div style="font-size: 12pt;">Jalan Mekarjaya No.199, Sidaharja</div>
                <div style="font-size: 12pt;">Kecamatan Lakbok, Kabupaten Ciamis 46385</div>
                <div style="font-size: 11pt; margin-top: 4px;">
                    Laman: <a href="http://www.smpn3lakbok.sch.id" style="color: blue; text-decoration: underline;">www.smpn3lakbok.sch.id</a> 
                    &nbsp;&nbsp;&nbsp; 
                    E-mail: netila.smp@gmail.com
                </div>
            </td>
            <td width="15%" align="right">
                <img src="<?php echo e(asset('img/logo_sekolah.png')); ?>" alt="Logo Sekolah" style="width: 85px; height: auto;" onerror="this.style.display='none'">
            </td>
        </tr>
    </table>
    <!-- Garis Ganda Kop Surat -->
    <div class="garis-kop"></div>
    <div class="garis-kop-bawah"></div>


    <div class="report-title">
        <h3>REKAPITULASI HASIL UJIAN</h3>
        
        <table class="report-info" align="center" style="width: 65%; margin: 15px auto;">
            <tr>
                <td width="35%"><strong>Mata Pelajaran</strong></td>
                <td width="5%">:</td>
                <td><?php echo e($exam->subject_name); ?></td>
            </tr>
            <tr>
                <td><strong>Judul Ujian</strong></td>
                <td>:</td>
                <td><?php echo e($exam->title); ?></td>
            </tr>
            <tr>
                <td><strong>Kelas / Tingkat</strong></td>
                <td>:</td>
                <td><?php echo e($exam->class_level); ?></td>
            </tr>
            <tr>
                <td><strong>KKM / Passing Grade</strong></td>
                <td>:</td>
                <td><?php echo e($exam->passing_grade); ?></td>
            </tr>
            <tr>
                <td><strong>Tanggal Cetak</strong></td>
                <td>:</td>
                <td><?php echo e(\Carbon\Carbon::now()->translatedFormat('d F Y')); ?></td>
            </tr>
        </table>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nama Siswa</th>
                <th width="15%">NISN</th>
                <th width="15%">Kelas</th>
                <th width="10%">Benar</th>
                <th width="10%">Salah</th>
                <th width="10%">Nilai</th>
                <th width="15%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td class="text-center"><?php echo e($index + 1); ?></td>
                <td>
                    <strong><?php echo e($row->student_name); ?></strong>
                </td>
                <td class="text-center"><?php echo e($row->student_nisn ?? '-'); ?></td>
                <td class="text-center"><?php echo e($row->class_name ?? '-'); ?></td>
                <td class="text-center" style="color: green;"><?php echo e($row->correct_answers); ?></td>
                <td class="text-center" style="color: red;"><?php echo e($row->wrong_answers); ?></td>
                <td class="text-center font-bold" style="font-size: 11pt;"><?php echo e($row->total_score); ?></td>
                <td class="text-center">
                    <?php if($row->total_score >= $exam->passing_grade): ?>
                        <span style="font-weight: bold;">LULUS</span>
                    <?php else: ?>
                        <span>REMEDIAL</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="8" class="text-center" style="padding: 20px;">Belum ada data nilai masuk.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    
    <div style="margin-top: 15px; font-size: 10pt; width: 40%;">
        <table class="data">
            <tr>
                <td style="background: #f0f0f0;"><strong>Rata-rata Nilai</strong></td>
                <td class="text-center"><strong><?php echo e(number_format($stats['average'], 1)); ?></strong></td>
            </tr>
            <tr>
                <td style="background: #f0f0f0;"><strong>Nilai Tertinggi</strong></td>
                <td class="text-center"><?php echo e($stats['max_score']); ?></td>
            </tr>
            <tr>
                <td style="background: #f0f0f0;"><strong>Nilai Terendah</strong></td>
                <td class="text-center"><?php echo e($stats['min_score']); ?></td>
            </tr>
        </table>
    </div>

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
                    Guru Mata Pelajaran
                    <br><br><br><br><br>
                    <strong><?php echo e(Auth::user()->name); ?></strong><br>
                    NIP. .........................
                </td>
            </tr>
        </table>
    </div>

</body>
</html><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/cbt/pdf_export.blade.php ENDPATH**/ ?>