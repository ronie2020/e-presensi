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

        /* Main Table */
        table.data { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 10pt; }
        table.data th, table.data td { border: 1px solid #000; padding: 6px 4px; }
        table.data th { background-color: #f3f3f3; text-align: center; font-weight: bold; vertical-align: middle; height: 30px; }
        table.data td { vertical-align: middle; }
        table.data .center { text-align: center; }
        table.data .left { text-align: left; padding-left: 8px; }
        
        /* Kolom Kebiasaan */
        .col-habit { width: 9%; font-size: 9pt; }
        .check { font-family: DejaVu Sans, sans-serif; font-size: 14pt; font-weight: bold; }
        .check-yes { color: #000; } 
        .check-no { color: #ccc; font-size: 10pt; }

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
            <td class="meta-title">Hari / Tanggal</td>
            <td width="10">:</td>
            <td width="300"><?php echo e(\Carbon\Carbon::parse($date)->translatedFormat('l, d F Y')); ?></td>
            
            <td class="meta-title">Kelas</td>
            <td width="10">:</td>
            <td><?php echo e($class->name ?? 'Semua Kelas'); ?></td>
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

    <table class="data">
        <thead>
            <tr>
                <th width="5%" rowspan="2">No</th>
                <th width="20%" rowspan="2">Nama Siswa</th>
                <th colspan="7">Capaian Kebiasaan Baik</th>
                <th width="8%" rowspan="2">Skor</th>
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
            <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php 
                    $h = $student->habit_data; 
                    
                    // --- LOGIKA CEK STATUS (SAMA DENGAN DASHBOARD) ---
                    $checks = [];
                    
                    if($h) {
                        // 1. Bangun & Mandi (Harus Keduanya)
                        $checks[1] = $h->habit_1 && $h->habit_2;
                        
                        // 2. Shalat (Salah satu terisi)
                        $checks[2] = $h->prayer_subuh || $h->prayer_dzuhur || $h->prayer_ashar || 
                                     $h->prayer_maghrib || $h->prayer_isya || $h->prayer_dhuha;
                        
                        // 3. Olahraga
                        $checks[3] = $h->habit_3;
                        
                        // 4. Makan Sehat (habit_5 di DB)
                        $checks[4] = $h->habit_5;
                        
                        // 5. Belajar (habit_4 di DB)
                        $checks[5] = $h->habit_4;
                        
                        // 6. Bantu Ortu
                        $checks[6] = $h->habit_6;
                        
                        // 7. Tidur
                        $checks[7] = $h->habit_7;
                    } else {
                        // Jika belum ada data
                        $checks = array_fill(1, 7, false);
                    }

                    // Hitung Total Skor
                    $totalScore = count(array_filter($checks));
                ?>
            <tr>
                <td class="center"><?php echo e($index + 1); ?></td>
                <td class="left" style="text-transform: uppercase;"><?php echo e($student->name); ?></td>
                
                
                <?php for($i = 1; $i <= 7; $i++): ?>
                    <td class="center">
                        <span class="check <?php echo e($checks[$i] ? 'check-yes' : 'check-no'); ?>">
                            <?php echo $checks[$i] ? '&#10003;' : '-'; ?>

                        </span>
                    </td>
                <?php endfor; ?>

                
                <td class="center" style="font-weight: bold;">
                    <?php if($h): ?>
                        <?php echo e($totalScore); ?>/7
                    <?php else: ?>
                        <span style="color: #d32f2f; font-size: 8pt; font-style: italic;">Alfa</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="10" class="center" style="padding: 20px;">Data siswa tidak ditemukan untuk kelas ini.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="legend">
        <strong>Keterangan:</strong><br>
        (&#10003;) : Melaksanakan Kebiasaan<br>
        (-) : Tidak Melaksanakan / Belum Lapor
    </div>

    <div class="footer">
        <div class="signature-box">
            <p>
                Lakbok, <?php echo e(\Carbon\Carbon::now()->isoFormat('D MMMM Y')); ?><br>
                Wali Kelas / Guru BK
            </p>
            <div style="font-weight: bold; text-decoration: underline; margin-top: 20px;">
                ( ........................................... )
            </div>
            <div style="margin-top: 5px;">NIP. .....................................</div>
        </div>
    </div>

</body>
</html><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\habits\print.blade.php ENDPATH**/ ?>