<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Kartu Peserta Ujian</title>
    <style>
        /* Import font modern untuk ID Card (Jika internet tersedia saat print) */
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');

        @page { 
            size: A4; 
            margin: 1cm; 
        }
        body { 
            font-family: 'Plus Jakarta Sans', 'Arial', sans-serif; 
            -webkit-print-color-adjust: exact !important; 
            print-color-adjust: exact !important;
            margin: 0;
            padding: 0;
            color: #000;
            background-color: #fff;
        }
        
        .container {
            display: grid;
            grid-template-columns: repeat(2, 1fr); /* 2 Kartu per baris */
            gap: 20px; /* Jarak antar kartu */
            padding: 10px;
        }

        .card {
            border: 1px solid #64748b; /* Warna border yang lebih halus dari hitam pekat */
            border-radius: 16px; /* Sisi melengkung modern Elevate */
            position: relative;
            height: 320px; 
            page-break-inside: avoid;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            overflow: hidden; /* Agar sudut background terpotong melengkung */
        }

        /* --- HEADER --- */
        .header {
            padding: 8px 12px;
            background-color: #f8fafc; /* Latar abu-abu sangat muda */
            border-bottom: 2px solid #2c3f61; /* Aksen garis Navy Elevate */
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

        .kop-text {
            flex: 1;
            text-align: center;
            padding: 0 10px;
        }
        .kop-title {
            font-size: 13px;
            font-weight: 800;
            margin: 0 0 2px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .kop-sub {
            font-size: 9px;
            margin: 0;
            font-weight: 600;
        }
        .kop-address {
            font-size: 8px;
            margin: 0;
            color: #475569;
        }

        .photo-box {
            width: 45px;
            height: 55px;
            border: 1px solid #94a3b8;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 9px;
            color: #94a3b8;
            background-color: #fff;
            text-align: center;
        }

        /* --- BODY KARTU --- */
        .body-card {
            flex: 1;
            padding: 12px 15px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .card-title {
            text-align: center;
            font-size: 14px;
            font-weight: 800;
            margin-bottom: 12px;
            text-transform: uppercase;
            color: #0f172a;
        }

        .data-row {
            display: flex;
            font-size: 11px;
            margin-bottom: 6px;
            line-height: 1.4;
        }
        .data-label {
            width: 85px;
            font-weight: 600;
            color: #475569;
        }
        .data-colon {
            width: 10px;
            font-weight: 600;
        }
        .data-value {
            flex: 1;
            font-weight: 800;
            color: #0f172a;
        }

        .credential-box {
            margin-top: 5px;
            background-color: #f1f5f9;
            border: 1px dashed #cbd5e1;
            border-radius: 8px;
            padding: 6px 10px;
            display: flex;
            justify-content: space-between;
        }

        /* --- FOOTER (QR & TTD) --- */
        .footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            padding: 0 15px 10px 15px;
            height: 80px;
        }

        .qr-area {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .qr-code {
            width: 60px;
            height: 60px;
            padding: 4px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            background: #fff;
        }
        .qr-text {
            font-size: 7px;
            margin-top: 3px;
            font-weight: 700;
            color: #64748b;
        }

        .signature {
            text-align: center;
            width: 130px;
        }
        .date {
            font-size: 9px;
            margin-bottom: 2px;
        }
        .role {
            font-size: 9px;
            font-weight: 700;
        }
        .space {
            height: 35px; /* Ruang untuk TTD atau Stempel */
        }
        .name {
            font-size: 9px;
            font-weight: 800;
            text-decoration: underline;
        }
        .nip {
            font-size: 8px;
        }
    </style>
    
    <!-- QRCode Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</head>
<body>

    <div class="container">
        <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="card">
                
                
                <div class="header">
                    <div class="logo-box">
                        
                        <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Logo" onerror="this.style.display='none'">
                    </div>
                    <div class="kop-text">
                        <div class="kop-title">SMP NEGERI 3 LAKBOK</div>
                        <div class="kop-sub">KARTU PESERTA UJIAN (CBT)</div>
                        <div class="kop-address">Tahun Ajaran <?php echo e(date('Y')); ?>/<?php echo e(date('Y', strtotime('+1 year'))); ?></div>
                    </div>
                    <div class="photo-box">
                        3x4
                    </div>
                </div>

                
                <div class="body-card">
                    <div class="data-row">
                        <div class="data-label">Nama Lengkap</div>
                        <div class="data-colon">:</div>
                        <div class="data-value"><?php echo e($student->name); ?></div>
                    </div>
                    <div class="data-row">
                        <div class="data-label">Kelas</div>
                        <div class="data-colon">:</div>
                        <div class="data-value"><?php echo e($student->schoolClass->name ?? '-'); ?></div>
                    </div>
                    <div class="data-row">
                        <div class="data-label">NIS / NISN</div>
                        <div class="data-colon">:</div>
                        <div class="data-value"><?php echo e($student->nis ?? '-'); ?> / <?php echo e($student->nisn ?? '-'); ?></div>
                    </div>

                    
                    <div class="credential-box">
                        <div class="data-row" style="margin-bottom: 0;">
                            <div class="data-label" style="width: 50px;">Username</div>
                            <div class="data-colon">:</div>
                            <div class="data-value" style="font-family: monospace; font-size: 12px;"><?php echo e($student->username ?? $student->student_id); ?></div>
                        </div>
                        <div class="data-row" style="margin-bottom: 0;">
                            <div class="data-label" style="width: 50px;">Password</div>
                            <div class="data-colon">:</div>
                            <div class="data-value" style="font-family: monospace; font-size: 12px;"><?php echo e($student->plain_password ?? '********'); ?></div>
                        </div>
                    </div>
                </div>

                
                <div class="footer">
                    
                    <div class="qr-area">
                        <div id="qr-<?php echo e($index); ?>" class="qr-code"></div>
                        <div class="qr-text">Scan for Login</div>
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
                        width: 52, /* Sedikit diperkecil agar pas dengan box radius */
                        height: 52,
                        colorDark : "#0f172a", /* Warna QR sedikit lebih lembut dari hitam murni */
                        colorLight : "#ffffff",
                        correctLevel : QRCode.CorrectLevel.L
                    });
                </script>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

</body>
</html><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/cbt/cards/print.blade.php ENDPATH**/ ?>