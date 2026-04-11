<!DOCTYPE html>
<html>
<head>
    <title>Surat Keterangan Lulus - <?php echo e($student->name); ?></title>
    <style>
        /* PENTING: Mengatur margin halaman agar muat 1 lembar */
        @page { margin: 2cm 2.5cm 1.5cm 2.5cm; }

        body { 
            font-family: 'Times New Roman', Times, serif; 
            font-size: 11pt; 
            line-height: 1.3; 
            color: #000; 
        }

        /* HEADER KOP SURAT */
        .kop-surat {
            width: 100%;
            border-bottom: 3px double #000;
            padding-bottom: 8px;
            margin-bottom: 15px;
            border-collapse: collapse;
        }
        .kop-surat td { vertical-align: middle; }
        .kop-text { text-align: center; }
        .kop-text h3 { margin: 0; font-size: 14pt; font-weight: normal; }
        .kop-text h2 { margin: 0; font-size: 16pt; font-weight: normal; }
        .kop-text h1 { margin: 0; font-size: 18pt; font-weight: bold; }
        .kop-text p { margin: 0; font-size: 9pt; }

        /* JUDUL SURAT */
        .title { 
            font-size: 14pt; 
            font-weight: bold; 
            text-decoration: underline; 
            text-align: center; 
            margin-bottom: 2px; 
            margin-top: 0; 
        }
        .subtitle { 
            text-align: center; 
            margin-top: 0; 
            margin-bottom: 20px; 
            font-size: 11pt; 
        }

        /* KONTEN */
        .content { margin: 0 10px; } 
        
        .table-data { width: 100%; margin-top: 5px; margin-bottom: 10px; }
        .table-data td { padding: 3px 5px; vertical-align: top; }
        .label { width: 170px; }
        .colon { width: 10px; text-align: center; }

        /* KOTAK LULUS */
        .status-box {
            border: 2px solid #000;
            padding: 10px;
            margin: 15px 0; 
            text-align: center;
        }

        /* AREA TANDA TANGAN & QR CODE */
        .ttd-area {
            width: 100%;
            margin-top: 30px;
            page-break-inside: avoid; /* Mencegah terpotong halaman baru */
            border-collapse: collapse;
        }
        .ttd-area td {
            vertical-align: bottom; /* Sejajarkan bagian bawah elemen */
        }

        /* FOOTER KECIL */
        .footer { 
            position: fixed; 
            bottom: 0; 
            width: 100%; 
            text-align: center; 
            font-size: 8pt; 
            font-style: italic; 
            color: #888; 
        }
    </style>
</head>
<body>
    
    <!-- KOP SURAT -->
    <table class="kop-surat">
        <tr>
            <td width="15%" style="text-align: left;">
                <img src="<?php echo e(public_path('img/logo_ciamis.png')); ?>" style="width: 75px; height: auto;" alt="Logo Ciamis">
            </td>
            <td width="70%" class="kop-text">
                <h3>PEMERINTAH KABUPATEN CIAMIS</h3>
                <h2>DINAS PENDIDIKAN</h2>
                <h1>SMP NEGERI 3 LAKBOK</h1>
                <p>Jalan Mekarjaya No. 199 Sidaharja Kecamatan Lakbok Kabupaten Ciamis</p>
                <p>Laman: www.smpn3lakbok.sch.id &nbsp; E-mail: smpn3lakbok@gmail.com</p>
            </td>
            <td width="15%" style="text-align: right;">
                <img src="<?php echo e(public_path('img/logo_sekolah.png')); ?>" style="width: 80px; height: auto;" alt="Logo Sekolah">
            </td>
        </tr>
    </table>

    <h3 class="title">SURAT KETERANGAN KELULUSAN</h3>
    <p class="subtitle">Nomor: <?php echo e($student->graduation->skl_number ?? ($settings['letter_number'] ?? '421.3/     /SMP.03/' . date('Y'))); ?></p>

    <div class="content">
        <p>Yang bertanda tangan di bawah ini, Kepala SMP Negeri 3 Lakbok, Kabupaten Ciamis, menerangkan bahwa:</p>

        <table class="table-data">
            <tr>
                <td class="label">Nama Peserta Didik</td>
                <td class="colon">:</td>
                <td style="font-weight: bold; text-transform: uppercase;"><?php echo e($student->name); ?></td>
            </tr>
            <tr>
                <td class="label">Tempat, Tanggal Lahir</td>
                <td class="colon">:</td>
                <td><?php echo e($student->pob); ?>, <?php echo e(\Carbon\Carbon::parse($student->dob)->isoFormat('D MMMM Y')); ?></td>
            </tr>
            <tr>
                <td class="label">Nomor Induk Siswa</td>
                <td class="colon">:</td>
                <td><?php echo e($student->nis ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="label">NISN</td>
                <td class="colon">:</td>
                <td><?php echo e($student->student_id); ?></td>
            </tr>
            <tr>
                <td class="label">Asal Sekolah</td>
                <td class="colon">:</td>
                <td>SMP NEGERI 3 LAKBOK</td>
            </tr>
        </table>

        <p style="text-align: justify;">
            Berdasarkan hasil Rapat Pleno Dewan Guru tentang Kelulusan Peserta Didik Tahun Pelajaran <?php echo e($student->graduation->academic_year ?? date('Y').'/'.(date('Y')+1)); ?> yang dilaksanakan pada tanggal <?php echo e(\Carbon\Carbon::parse($student->graduation->announcement_date ?? now())->isoFormat('D MMMM Y')); ?>, maka peserta didik tersebut dinyatakan:
        </p>

        <div class="status-box">
            <h1 style="margin: 0; font-size: 22pt; font-weight: bold; letter-spacing: 3px;">L U L U S</h1>
        </div>

        <p style="text-align: justify;">
            Surat Keterangan ini bersifat sementara sampai diterbitkannya Ijazah asli. Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.
        </p>

        <!-- AREA TANDA TANGAN & QR CODE -->
        <table class="ttd-area">
            <tr>
                <!-- KIRI: QR Code Verifikasi -->
                <td style="width: 50%; text-align: left; padding-left: 20px;">
                    
                    <?php
                        /* =========================================================
                           SOLUSI JITU UNTUK DOMPDF (Ubah gambar luar jadi Base64)
                           ========================================================= */
                        $portalUrl = route('portal.show', $student->id);
                        $apiUrl = "http://api.qrserver.com/v1/create-qr-code/?size=90x90&data=" . urlencode($portalUrl) . "&margin=0";
                        
                        // Konteks untuk mematikan verifikasi SSL lokal (Aman untuk XAMPP/Laragon)
                        $context = stream_context_create([
                            'ssl' => ['verify_peer' => false, 'verify_peer_name' => false],
                            'http' => ['ignore_errors' => true]
                        ]);
                        
                        $qrImageBase64 = '';
                        try {
                            // PHP mendownload gambar langsung secara gaib
                            $imageData = file_get_contents($apiUrl, false, $context);
                            if ($imageData) {
                                $qrImageBase64 = 'data:image/png;base64,' . base64_encode($imageData);
                            }
                        } catch (\Exception $e) {
                            $qrImageBase64 = ''; // Kosongkan jika gagal
                        }
                    ?>

                    
                    <?php if($qrImageBase64): ?>
                        <img src="<?php echo e($qrImageBase64); ?>" style="width: 85px; height: 85px;" alt="QR Code Verifikasi">
                    <?php else: ?>
                        <!-- Tampil kotak kosong jika saat load tidak ada koneksi internet -->
                        <div style="width: 85px; height: 85px; border: 1px dashed #999; text-align: center; line-height: 85px; font-size: 10px; color: #999;">[QR CODE]</div>
                    <?php endif; ?>
                    
                    <div style="margin-top: 5px; font-size: 8pt; color: #444; line-height: 1.2;">
                        <i>* Pindai QR Code untuk memverifikasi<br>keaslian dokumen pada sistem sekolah.</i>
                    </div>
                </td>
                
                <!-- KANAN: Tanda Tangan Kepala Sekolah -->
                <td style="width: 50%; text-align: center;">
                    <p style="margin: 0;">Lakbok, <?php echo e(\Carbon\Carbon::now()->isoFormat('D MMMM Y')); ?></p>
                    <p style="margin: 0;">Kepala Sekolah,</p>
                    <!-- Spasi Tanda Tangan -->
                    <br><br><br><br>
                    <p style="margin: 0; font-weight: bold; text-decoration: underline;"><?php echo e(isset($settings['principal_name']) ? strtoupper($settings['principal_name']) : 'TANTAN SUTANDI NUGRAHA, S.Si, M.Pd.'); ?></p>
                    <p style="margin: 0;">NIP. <?php echo e($settings['principal_nip'] ?? '197xxxxxx...'); ?></p>
                </td>
            </tr>
        </table>
    </div>

    <!-- Footer kecil di bawah -->
    <div class="footer">
        Dicetak melalui Sistem Informasi Sekolah SMPN 3 Lakbok
    </div>
</body>
</html><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\graduation\pdf_skl.blade.php ENDPATH**/ ?>