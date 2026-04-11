<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analisis Soal - <?php echo e($exam->title); ?></title>
    <style>
        /* Menggunakan ukuran A4 Landscape agar tabel muat banyak kolom */
        @page { size: A4 landscape; margin: 1.5cm; }
        body { font-family: 'Times New Roman', serif; color: #000; line-height: 1.3; font-size: 11pt; }
        
        .no-print { display: block; margin-bottom: 20px; text-align: right; }
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
        .report-info { margin-top: 10px; font-size: 10pt; width: 60%; margin-left: auto; margin-right: auto; }
        .report-info td { padding: 2px; vertical-align: top; }

        /* Tabel Data */
        table.data { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 10pt; }
        table.data th, table.data td { border: 1px solid #000; padding: 8px; vertical-align: middle; }
        table.data th { background-color: #e0e0e0; text-align: center; font-weight: bold; }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        
        /* Distribusi List */
        .dist-list { margin: 0; padding-left: 15px; list-style-type: square; font-size: 9.5pt; }
        .key-correct { font-weight: bold; color: #166534; } /* Warna hijau tua untuk kunci saat dicetak berwarna */

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

    
    <table class="header-table">
        <tr>
            <td width="12%" align="center" style="padding-bottom: 10px;">
                <div style="width: 70px; height: 70px; background: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; border: 2px solid #000;">LOGO</div>
            </td>
            <td align="center" style="padding-bottom: 10px;">
                <h2 class="header-title">PEMERINTAH KABUPATEN CIAMIS</h2>
                <h2 class="header-subtitle">DINAS PENDIDIKAN</h2>
                <h1 class="header-title" style="font-size: 16pt; margin-top: 5px;">SMP NEGERI 3 LAKBOK</h1>
                <p class="header-address">Jl. Raya Lakbok No. 123, Cintaratu, Lakbok, Ciamis - 46385</p>
            </td>
            <td width="12%"></td>
        </tr>
    </table>

    <div class="report-title">
        <h3>LAPORAN ANALISIS BUTIR SOAL</h3>
        
        <table class="report-info" align="center">
            <tr>
                <td width="30%"><strong>Mata Pelajaran</strong></td>
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
                <td><strong>Sampel Data</strong></td>
                <td>:</td>
                <td><?php echo e($totalStudents); ?> Siswa</td>
            </tr>
        </table>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="35%">Cuplikan Soal</th>
                <th width="8%">Tipe</th>
                <th width="8%">Kunci</th>
                <th width="15%">Tingkat Kesukaran</th>
                <th width="30%">Distribusi Jawaban Siswa</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $analysis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td class="text-center"><?php echo e($index + 1); ?></td>
                <td style="text-align: justify;">
                    
                    <?php echo e(Str::limit(strip_tags($item->text), 150)); ?>

                </td>
                <td class="text-center">
                    <?php if(in_array($item->type, ['choice', 'true_false'])): ?> PG 
                    <?php elseif($item->type == 'essay'): ?> ESSAI
                    <?php elseif($item->type == 'matching'): ?> MATCHING
                    <?php endif; ?>
                </td>
                <td class="text-center font-bold">
                    <?php echo e(in_array($item->type, ['choice', 'true_false']) ? $item->correct_key : '-'); ?>

                </td>
                <td class="text-center">
                    <strong><?php echo e($item->difficulty_index); ?>%</strong> Benar<br>
                    <span style="font-size: 9pt;">(<?php echo e($item->difficulty_label); ?>)</span>
                </td>
                <td>
                    <?php if(in_array($item->type, ['choice', 'true_false'])): ?>
                        <ul class="dist-list">
                            <?php $__currentLoopData = ['A','B','C','D','E']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(isset($item->options[$opt]) || $opt != 'E'): ?>
                                    <?php 
                                        $count = $item->options[$opt] ?? 0;
                                        $percent = $totalStudents > 0 ? round(($count / $totalStudents) * 100) : 0;
                                        $isKey = $opt == $item->correct_key;
                                    ?>
                                    <li class="<?php echo e($isKey ? 'key-correct' : ''); ?>">
                                        Opsi <b><?php echo e($opt); ?></b> : <?php echo e($count); ?> Siswa (<?php echo e($percent); ?>%) <?php echo $isKey ? '&#10003;' : ''; ?>

                                    </li>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    <?php elseif($item->type == 'essay'): ?>
                        <div class="text-center" style="color: gray; font-style: italic; padding-top: 10px;">
                            Dikoreksi Manual
                        </div>
                    <?php else: ?>
                        <div class="text-center">-</div>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="6" class="text-center" style="padding: 20px;">Belum ada data analisis.</td>
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
                    Guru Mata Pelajaran
                    <br><br><br><br><br>
                    <strong><?php echo e(Auth::user()->name); ?></strong><br>
                    NIP. .........................
                </td>
            </tr>
        </table>
    </div>

</body>
</html><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\cbt\analysis_pdf.blade.php ENDPATH**/ ?>