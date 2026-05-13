<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SKL - <?php echo e($student->name); ?></title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- INJEKSI TEMA MICROSOFT ELEVATE -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { elevate: { dark: '#032b5b', primary: '#3b5889', accent: '#38bdf8', text: '#1e293b' } }
                }
            }
        }
    </script>

    
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        /* PENGATURAN KERTAS A4 UNTUK SKL */
        @page { 
            size: 21cm 29.7cm; 
            margin: 0; 
        }
        
        body {
            font-family: 'Times New Roman', serif;
            background-color: #f8fafc;
            -webkit-print-color-adjust: exact;
        }

        /* TAMPILAN KERTAS DI LAYAR & FONT BOOKMAN OLD STYLE */
        .sheet {
            font-family: 'Bookman Old Style', Bookman, Georgia, serif; 
            background: white;
            width: 21cm;
            min-height: 29.7cm;
            margin: 30px auto;
            padding: 2cm 2.5cm; /* Padding standar dokumen formal */
            box-sizing: border-box;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            position: relative;
            page-break-after: always; 
            page-break-inside: avoid;
            color: #000;
        }

        /* MODIFIKASI GARIS KOP SURAT */
        .garis-kop {
            border-bottom: 3px solid black;
            margin-bottom: 2px;
        }
        .garis-kop-bawah {
            border-bottom: 1px solid black;
            margin-bottom: 24px;
        }
        
        /* MODE PRINT */
        @media print {
            body { background: none; margin: 0; }
            .sheet { 
                width: 21cm; 
                margin: 0; 
                padding: 2cm 2.5cm;
                box-sizing: border-box;
                box-shadow: none; 
                border: none; 
                page-break-after: always; 
                page-break-inside: avoid;
            }
            .sheet:last-child { page-break-after: auto; }
            .no-print { display: none !important; }
        }

        /* TYPOGRAPHY SURAT */
        .judul-surat { text-align: center; font-weight: bold; margin-bottom: 20px; }
        .judul-surat h2 { margin: 0; font-size: 14pt; text-decoration: underline; text-transform: uppercase; }
        .judul-surat p { margin: 0; font-size: 11pt; font-weight: normal; text-transform: none; }

        /* TABEL DATA UTAMA */
        table.data { width: 100%; border-collapse: collapse; margin-top: 5px; margin-bottom: 15px; font-size: 11pt; }
        table.data td { vertical-align: top; padding: 4px 5px; }
        table.data tr td:first-child { width: 180px; }
        table.data tr td:nth-child(2) { width: 15px; text-align: center; }

        .status-box { border: 2px solid #000; padding: 10px; margin: 15px 0; text-align: center; }
        .status-box h1 { margin: 0; font-size: 22pt; font-weight: bold; letter-spacing: 5px; }

        .clear { clear: both; }
        .footer { position: absolute; bottom: 1.5cm; left: 0; width: 100%; text-align: center; font-size: 8pt; font-style: italic; color: #888; }
    </style>
</head>
<body class="relative">

    <!-- DEKORASI BACKGROUND (Hanya tampil di layar) -->
    <div class="fixed top-0 left-0 w-full h-64 bg-gradient-to-b from-elevate-primary/10 to-transparent pointer-events-none no-print -z-10"></div>

    <!-- TOOLBAR AKSI (Tidak tercetak) -->
    <div class="w-[21cm] mx-auto mt-6 mb-6 flex flex-col sm:flex-row justify-between items-center gap-4 no-print bg-white/80 backdrop-blur-md p-4 rounded-2xl shadow-lg shadow-elevate-dark/5 border border-white/60 sticky top-4 z-50">
        <div>
            <h2 class="font-black text-elevate-dark font-sans flex items-center gap-2">
                <i class="ph-bold ph-printer text-elevate-primary text-xl"></i> Pratinjau SKL
            </h2>
            <p class="text-xs text-slate-500 font-bold ml-7 font-sans">Siswa: <?php echo e($student->name); ?> | Kertas: A4</p>
        </div>

        <div class="flex flex-wrap gap-3 items-center font-sans">
            <button onclick="window.close()" class="px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 hover:text-elevate-primary transition-colors shadow-sm flex items-center gap-2 group">
                <i class="ph-bold ph-x group-hover:scale-110 transition-transform"></i> Tutup
            </button>
            
            <button onclick="window.print()" class="px-5 py-2.5 bg-elevate-primary text-white font-bold rounded-xl hover:bg-elevate-dark shadow-lg shadow-elevate-primary/30 transition-transform active:scale-95 flex items-center gap-2 text-xs group">
                <i class="ph-bold ph-printer text-sm group-hover:scale-110 transition-transform"></i> Cetak Dokumen
            </button>
        </div>
    </div>

    <!-- HALAMAN CETAK -->
    <div class="sheet">
        
        <!-- KOP SURAT (Disesuaikan persis dengan SPPD) -->
        <div class="kop-surat garis-kop pb-2 pt-2 flex justify-between items-center px-1">
            <!-- Logo Kiri -->
            <img src="<?php echo e(asset('img/logo_ciamis.png')); ?>" alt="Logo Ciamis" class="w-[70px] h-auto object-contain" onerror="this.style.display='none'"> 
            
            <!-- Teks Tengah -->
            <div class="text-center flex-1 px-4 leading-tight">
                <div class="text-[14pt] tracking-wide mb-1">PEMERINTAH KABUPATEN CIAMIS</div>
                <div class="font-bold text-[22pt] tracking-wider mb-1">SMP NEGERI 3 LAKBOK</div>
                <div class="text-[12pt]">Jalan Mekarjaya No.199, Sidaharja</div>
                <div class="text-[12pt]">Kecamatan Lakbok, Kabupaten Ciamis 46385</div>
                <div class="text-[11pt] mt-1">
                    Laman: <a href="http://www.smpn3lakbok.sch.id" class="text-blue-700 underline">www.smpn3lakbok.sch.id</a> 
                    <span class="mx-3"></span> 
                    E-mail: netila.smp@gmail.com
                </div>
            </div>

            <!-- Logo Kanan -->
            <img src="<?php echo e(asset('img/logo_sekolah.png')); ?>" alt="Logo Sekolah" class="w-[70px] h-auto object-contain" onerror="this.style.display='none'">
        </div>
        <div class="garis-kop-bawah"></div>

        <div class="judul-surat">
            <h2>SURAT KETERANGAN KELULUSAN</h2>
            <p>Nomor: <?php echo e($student->graduation->skl_number ?? ($settings['letter_number'] ?? '421.3/     /SMP.03/' . date('Y'))); ?></p>
        </div>

        <div style="line-height: 1.3;">
            <p>Yang bertanda tangan di bawah ini, Kepala SMP Negeri 3 Lakbok, Kabupaten Ciamis, menerangkan bahwa:</p>

            <table class="data">
                <tr>
                    <td>Nama Peserta Didik</td>
                    <td>:</td>
                    <td style="font-weight: bold; text-transform: uppercase;"><?php echo e($student->name); ?></td>
                </tr>
                <tr>
                    <td>Tempat, Tanggal Lahir</td>
                    <td>:</td>
                    <td><?php echo e($student->pob); ?>, <?php echo e(\Carbon\Carbon::parse($student->dob)->locale('id')->isoFormat('D MMMM Y')); ?></td>
                </tr>
                <tr>
                    <td>Nomor Induk Siswa</td>
                    <td>:</td>
                    <td><?php echo e($student->nis ?? '-'); ?></td>
                </tr>
                <tr>
                    <td>NISN</td>
                    <td>:</td>
                    <td><?php echo e($student->student_id); ?></td>
                </tr>
                <tr>
                    <td>Asal Sekolah</td>
                    <td>:</td>
                    <td>SMP NEGERI 3 LAKBOK</td>
                </tr>
            </table>

            <p style="text-align: justify;">
                Berdasarkan hasil Rapat Pleno Dewan Guru tentang Kelulusan Peserta Didik Tahun Pelajaran <?php echo e($settings['academic_year'] ?? ($student->graduation->academic_year ?? '')); ?> yang dilaksanakan pada tanggal <?php echo e(\Carbon\Carbon::parse($settings['announcement_date'] ?? ($student->graduation->announcement_date ?? now()))->locale('id')->isoFormat('D MMMM Y')); ?>, maka peserta didik tersebut dinyatakan:
            </p>

            <div class="status-box">
                <h1>L U L U S</h1>
            </div>

            <p style="text-align: justify;">
                Surat Keterangan ini bersifat sementara sampai diterbitkannya Ijazah asli. Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.
            </p>

            <!-- AREA TANDA TANGAN & QR CODE MENGGUNAKAN FLOAT SEPERTI SPPD -->
            <div style="margin-top: 30px;">
                <!-- KIRI: QR Code Verifikasi -->
                <div style="float: left; width: 48%; text-align: left; padding-left: 20px;">
                    <?php
                        $portalUrl = route('portal.show', $student->id);
                        // Langsung panggil URL (Browser sangat cerdas meload ini)
                        $qrUrl = "http://api.qrserver.com/v1/create-qr-code/?size=90x90&data=" . urlencode($portalUrl) . "&margin=0";
                    ?>

                    <img src="<?php echo e($qrUrl); ?>" style="width: 85px; height: 85px;" alt="QR Code Verifikasi">
                    
                    <div style="margin-top: 5px; font-size: 8pt; color: #444; line-height: 1.2;">
                        <i>* Pindai QR Code untuk memverifikasi<br>keaslian dokumen pada SIMADU.</i>
                    </div>
                </div>
                
                <!-- KANAN: Tanda Tangan Kepala Sekolah -->
                <div style="float: right; width: 48%; text-align: center;">
                    <p style="margin: 0;">Lakbok, <?php echo e(\Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y')); ?></p>
                    <p style="margin: 0;">Kepala Sekolah,</p>
                    
                    <div style="height: 60px;"></div>
                    
                    <p style="margin: 0; font-weight: bold; text-decoration: underline; white-space: nowrap;"><?php echo e(isset($settings['principal_name']) ? strtoupper($settings['principal_name']) : 'TANTAN SUTANDI NUGRAHA, S.Si, M.Pd.'); ?></p>
                    <p style="margin: 0;">NIP. <?php echo e($settings['principal_nip'] ?? '197xxxxxx...'); ?></p>
                </div>
                <div class="clear"></div>
            </div>
        </div>

        <div class="footer">
            Dicetak melalui Simadu (Sistem Informasi Terpadu) SMPN 3 Lakbok
        </div>
    </div>
</body>
</html><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/graduation/pdf_skl.blade.php ENDPATH**/ ?>