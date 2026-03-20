<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Kartu Peserta Ujian</title>
    <style>
        @page { 
            size: A4; 
            margin: 1cm; 
        }
        body { 
            font-family: 'Arial', sans-serif; 
            -webkit-print-color-adjust: exact; 
            margin: 0;
            padding: 0;
        }
        
        .container {
            display: grid;
            grid-template-columns: repeat(2, 1fr); /* 2 Kartu per baris */
            gap: 20px; /* Jarak antar kartu */
            padding: 10px;
        }

        .card {
            border: 1px solid #000;
            position: relative;
            /* DIPERBAIKI: Tinggi dinaikkan jadi 320px agar muat */
            height: 320px; 
            page-break-inside: avoid;
            background: #fff;
            display: flex;
            flex-direction: column;
        }

        /* --- HEADER --- */
        .header {
            padding: 5px 10px; /* Diperkecil sedikit */
            border-bottom: 3px double #000;
            display: flex;
            align-items: center;
            justify-content: space-between; 
            height: 55px;
        }

        .logo-box {
            width: 45px;
            height: 45px;
            display: flex; 
            align-items: center; 
            justify-content: center;
        }
        .logo-box img { 
            max-width: 100%; 
            max-height: 100%; 
            object-fit: contain; 
        }

        .school-info { 
            flex: 1; 
            text-align: center; 
            line-height: 1.2;
        }
        .school-info h2 { 
            margin: 0; 
            font-size: 12pt; 
            font-weight: 800; 
            text-transform: uppercase; 
            color: #000;
        }
        .school-info p { 
            margin: 0; 
            font-size: 8pt; 
            font-weight: bold;
            color: #000;
        }
        .school-info .small {
            font-size: 7pt;
            font-weight: normal;
        }

        /* --- BODY (Foto & Biodata) --- */
        .body { 
            padding: 10px 15px; 
            display: flex; 
            gap: 15px; 
            flex: 1; /* Mengisi ruang sisa */
            align-items: flex-start;
        }
        
        /* Area Foto */
        .photo-area {
            width: 75px; 
            height: 100px; /* Rasio 3x4 */
            border: 1px solid #999;
            background: #f0f0f0;
            overflow: hidden; 
            position: relative;
            flex-shrink: 0;
        }
        
        .photo-area img {
            width: 100%;
            height: 100%;
            object-fit: cover; 
            display: block;
            position: relative; 
            z-index: 2; 
        }

        .photo-placeholder {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            color: #aaa;
            z-index: 1;
        }

        /* Tabel Biodata */
        .student-info { 
            flex: 1; 
            font-size: 9pt; 
            color: #000;
        }
        .student-info table { 
            width: 100%; 
            border-collapse: collapse; 
        }
        .student-info td { 
            padding: 3px 0; /* Sedikit jarak antar baris */
            vertical-align: top; 
        }
        .label { 
            font-weight: bold; 
            width: 80px; 
        }
        .sep {
            width: 10px;
            text-align: center;
        }

        /* --- SEPARATOR & FOOTER --- */
        .separator-line {
            border-top: 1px dashed #000;
            margin: 0 10px;
            opacity: 0.5;
        }

        .footer {
            padding: 8px 15px 12px 15px; /* Padding bawah agak besar agar tidak mepet garis */
            display: flex; 
            justify-content: space-between; /* QR Kiri, TTD Kanan */
            align-items: flex-end; 
            /* DIPERBAIKI: Hapus height fix, gunakan margin-top auto agar nempel bawah */
            margin-top: auto; 
        }

        /* QR Code di Kiri */
        .qr-area {
            width: 60px;
            text-align: center;
        }
        .qr-code {
            padding: 2px;
            background: #fff;
            border: 1px solid #ddd;
            display: inline-block;
        }

        /* Tanda Tangan di Kanan */
        .signature {
            font-size: 8pt;
            text-align: center; 
            line-height: 1.3;
            min-width: 140px; 
        }
        .signature .date { margin-bottom: 2px; }
        
        /* Jarak untuk tanda tangan manual */
        .signature .space { 
            height: 40px; 
        } 
        
        .signature .name { 
            font-weight: bold; 
            text-decoration: underline; 
        }
        
        .signature .nip {
            font-size: 7pt;
        }

        @media print {
            .no-print { display: none; }
            button { display: none; }
            body { margin: 0; }
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</head>
<body>

    <div class="no-print" style="margin-bottom: 20px; text-align: center; padding: 20px; background: #f1f5f9; border-bottom: 1px solid #e2e8f0;">
        <h3 style="margin-top: 0; font-family: sans-serif;">Preview Kartu Ujian</h3>
        <p>Pastikan settingan printer: Paper A4, Margin Default, Scale 100%</p>
        <button onclick="window.print()" style="padding: 12px 25px; font-weight: bold; background: #2563eb; color: white; border: none; cursor: pointer; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);">
            🖨️ Cetak Sekarang
        </button>
    </div>

    <div class="container">
        <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="card">
                
                <div class="header">
                    <div class="logo-box">
                        <img src="<?php echo e(asset('img/logo_ciamis.png')); ?>" alt="Logo Dinas" onerror="this.style.opacity=0">
                    </div> 
                    
                    <div class="school-info">
                        <h2>KARTU PESERTA UJIAN</h2>
                        <p>SMP NEGERI 3 LAKBOK</p>
                        <p class="small">TAHUN AJARAN <?php echo e(date('Y')); ?>/<?php echo e(date('Y')+1); ?></p>
                    </div>

                    <div class="logo-box">
                        <img src="<?php echo e(asset('img/logo_sekolah.png')); ?>" alt="Logo Sekolah" onerror="this.style.opacity=0">
                    </div>
                </div>

                
                <div class="body">
                    
                    <div class="photo-area">
                        <?php
                            $photoPath = null;
                            if (!empty($student->photo_path)) $photoPath = $student->photo_path; 
                            elseif (!empty($student->image)) $photoPath = $student->image;
                            elseif (!empty($student->photo)) $photoPath = $student->photo;
                        ?>

                        <div class="photo-placeholder">
                            <div style="font-size: 20px;">👤</div>
                            <div style="font-size: 8px;">FOTO</div>
                        </div>

                        <?php if($photoPath): ?>
                            <img src="<?php echo e(asset('storage/' . $photoPath)); ?>" alt="Foto" onerror="this.style.display='none'">
                        <?php endif; ?>
                    </div>
                    
                    
                    <div class="student-info">
                        <table>
                            <tr>
                                <td class="label">Nama</td>
                                <td class="sep">:</td>
                                <td><b><?php echo e(strtoupper($student->name)); ?></b></td>
                            </tr>
                            <tr>
                                <td class="label">NISN</td>
                                <td class="sep">:</td>
                                <td><?php echo e($student->nisn ?? $student->nis ?? '-'); ?></td>
                            </tr>
                            <tr>
                                <td class="label">Kelas</td>
                                <td class="sep">:</td>
                                <td><?php echo e($student->schoolClass->name ?? '-'); ?></td>
                            </tr>
                            <tr>
                                <td class="label">Username</td>
                                <td class="sep">:</td>
                                <td><b><?php echo e($student->student_id); ?></b></td>
                            </tr>
                            <tr>
                                <td class="label">Password</td>
                                <td class="sep">:</td>
                                <td><b><?php echo e($student->student_id); ?></b></td>
                            </tr>
                        </table>
                    </div>
                </div>

                
                <div class="separator-line"></div>

                
                <div class="footer">
                    
                    <div class="qr-area">
                        <div id="qr-<?php echo e($index); ?>" class="qr-code"></div>
                    </div>

                    
                    <div class="signature">
                        <div class="date">Lakbok, <?php echo e(\Carbon\Carbon::now()->translatedFormat('d F Y')); ?></div>
                        <div class="role">Kepala Sekolah</div>
                            
                        
                        <div class="space"></div>

                        
                        <div class="name">Tantan Sutandi N.,S.Pd.,M.Pd</div>
                        <div class="nip">NIP. 19820000 000000 1 000</div>
                    </div>
                </div>

                
                <script>
                    new QRCode(document.getElementById("qr-<?php echo e($index); ?>"), {
                        text: "<?php echo e($student->login_url ?? 'NoData'); ?>",
                        width: 55,
                        height: 55,
                        colorDark : "#000000",
                        colorLight : "#ffffff",
                        correctLevel : QRCode.CorrectLevel.L
                    });
                </script>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

</body>
</html><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/cbt/cards/print.blade.php ENDPATH**/ ?>