<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Berita Acara - <?php echo e($exam->title); ?></title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        /* PENGATURAN KERTAS F4 (Folio) */
        @page { 
            size: 21.5cm 33cm; 
            margin: 0; 
        }
        
        body {
            /* Font disesuaikan menjadi Bookman Old Style */
            font-family: 'Bookman Old Style', Bookman, Georgia, serif;
            font-size: 11pt;
            background-color: #f1f5f9; /* Slate-100 */
            -webkit-print-color-adjust: exact;
        }

        /* TAMPILAN KERTAS DI LAYAR */
        .sheet {
            background: white;
            width: 21.5cm;
            min-height: 33cm;
            margin: 30px auto;
            padding: 1.5cm 2cm;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            position: relative;
            page-break-after: always; 
        }
        
        /* MODE PRINT */
        @media print {
            body { background: none; margin: 0; }
            .sheet { width: 100%; margin: 0; padding: 1cm 2cm; box-shadow: none; border: none; page-break-after: always; }
            .sheet:last-child { page-break-after: auto; }
            .no-print { display: none !important; }
        }

        /* --- KOP SURAT STYLE STANDAR --- */
        .kop-surat { width: 100%; border-collapse: collapse; margin-bottom: 2px; }
        .kop-surat td { padding: 0; vertical-align: middle; }
        .kop-dinas { font-size: 14pt; letter-spacing: 0.025em; margin-bottom: 4px; line-height: 1.1; font-family: 'Bookman Old Style', serif; }
        .kop-sekolah { font-size: 22pt; font-weight: bold; letter-spacing: 0.05em; margin-bottom: 4px; line-height: 1.1; font-family: 'Bookman Old Style', serif; }
        .kop-alamat { font-size: 12pt; font-style: normal; line-height: 1.2; font-family: 'Bookman Old Style', serif; }
        .kop-kontak { font-size: 11pt; margin-top: 4px; font-family: 'Bookman Old Style', serif; }
        .garis-kop { border: none; border-top: 4px solid #000; border-bottom: 1.5px solid #000; height: 2px; margin-bottom: 20px; }
        
        /* TYPOGRAPHY SURAT */
        .judul-surat { text-align: center; font-weight: bold; text-transform: uppercase; margin-bottom: 25px; }
        .judul-surat h2 { margin: 0; text-decoration: underline; font-size: 12pt; }
        .judul-surat p { margin: 0; font-size: 11pt; font-weight: normal; text-transform: none; }

        /* KONTEN BERITA ACARA */
        .content-text { text-align: justify; line-height: 1.6; }
        table.detail { width: 100%; border-collapse: collapse; margin-bottom: 15px; margin-left: 10px;}
        table.detail td { vertical-align: top; padding: 4px 5px; }
        
        .ttd-box { width: 40%; text-align: center; }
        .clear { clear: both; }
    </style>
</head>
<body>

    <!-- TOOLBAR (Floating) -->
    <div class="no-print fixed top-0 left-0 right-0 bg-white/90 backdrop-blur-md border-b border-slate-200 shadow-sm p-4 flex justify-between items-center z-50">
        <div class="flex items-center gap-4">
            <div class="bg-blue-900 p-2.5 rounded-xl text-white shadow-lg shadow-blue-900/20">
                <i class="ph-bold ph-file-text text-xl"></i>
            </div>
            <div>
                <h1 class="font-black text-slate-800 text-sm md:text-base font-sans">Pratinjau Berita Acara</h1>
                <p class="text-xs text-slate-500 font-sans font-bold"><?php echo e($exam->title); ?></p>
            </div>
        </div>
        <div class="flex gap-3">
            <a href="<?php echo e(route('cbt.index')); ?>" class="px-5 py-2.5 text-xs font-bold text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 hover:text-slate-800 transition shadow-sm font-sans flex items-center gap-2">
                <i class="ph-bold ph-arrow-left"></i> Kembali
            </a>
            <button onclick="window.print()" class="px-5 py-2.5 text-xs font-bold text-white bg-blue-900 rounded-xl hover:bg-blue-800 transition shadow-lg shadow-blue-900/30 font-sans flex items-center gap-2">
                <i class="ph-bold ph-printer"></i> Cetak Dokumen
            </button>
        </div>
    </div>
    <div class="no-print h-24"></div>

    <div class="sheet">
        
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
                    <img src="<?php echo e(asset('img/logo_sekolah.png')); ?>" alt="Logo SMP" style="width: 90px; height: auto; object-fit: contain;" onerror="this.style.display='none'">
                </td>
            </tr>
        </table>
        <hr class="garis-kop">

        <div class="judul-surat">
            <h2>BERITA ACARA PELAKSANAAN UJIAN</h2>
            <?php
                // Mengambil tahun ajaran yang sedang aktif dari database
                $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
                $tahunPelajaran = $activeYear ? $activeYear->name : date('Y') . '/' . date('Y', strtotime('+1 year'));
            ?>
            <p>Tahun Pelajaran <?php echo e($tahunPelajaran); ?></p>
        </div>

        <div class="content-text">
            <p class="mb-5">
                Pada hari ini <span class="font-bold"><?php echo e($exam->start_time ? \Carbon\Carbon::parse($exam->start_time)->locale('id')->isoFormat('dddd') : '.......'); ?></span> 
                tanggal <span class="font-bold"><?php echo e($exam->start_time ? \Carbon\Carbon::parse($exam->start_time)->format('d') : '.......'); ?></span> 
                bulan <span class="font-bold"><?php echo e($exam->start_time ? \Carbon\Carbon::parse($exam->start_time)->locale('id')->isoFormat('MMMM') : '.......'); ?></span> 
                tahun <span class="font-bold"><?php echo e($exam->start_time ? \Carbon\Carbon::parse($exam->start_time)->format('Y') : '.......'); ?></span>,
                telah diselenggarakan <span class="font-bold"><?php echo e($exam->title); ?></span> dengan rincian sebagai berikut:
            </p>

            <table class="detail">
                <tr><td style="width: 30px;">1.</td><td style="width: 250px;">Mata Pelajaran</td><td style="width: 15px;">:</td><td class="font-bold"><?php echo e($exam->subject_name); ?></td></tr>
                <tr><td>2.</td><td>Tingkat / Kelas</td><td>:</td><td class="font-bold"><?php echo e($exam->class_level); ?></td></tr>
                <tr><td>3.</td><td>Waktu Ujian</td><td>:</td><td><?php echo e($exam->start_time ? \Carbon\Carbon::parse($exam->start_time)->format('H:i') : '-'); ?> s.d <?php echo e($exam->end_time ? \Carbon\Carbon::parse($exam->end_time)->format('H:i') : '-'); ?> WIB</td></tr>
            </table>

            <table class="detail mt-4">
                <tr><td style="width: 30px;">4.</td><td style="width: 250px;">Jumlah Peserta Seharusnya</td><td style="width: 15px;">:</td><td class="font-bold"><?php echo e($totalStudents); ?> Orang</td></tr>
                <tr><td>5.</td><td>Jumlah Peserta Hadir</td><td>:</td><td class="font-bold"><?php echo e($presentStudents); ?> Orang</td></tr>
                <tr><td>6.</td><td>Jumlah Peserta Tidak Hadir</td><td>:</td><td class="font-bold"><?php echo e($absentStudents); ?> Orang</td></tr>
                <tr>
                    <td>7.</td>
                    <td>Nomor/Nama Peserta Absen</td>
                    <td>:</td>
                    <td>
                        <div style="border-bottom: 1px dotted #000; height: 20px; width: 100%; margin-bottom: 10px;"></div>
                        <div style="border-bottom: 1px dotted #000; height: 20px; width: 100%;"></div>
                    </td>
                </tr>
            </table>

            <div class="mt-8 mb-12">
                <p class="mb-4">Catatan penting selama pelaksanaan ujian berlangsung:</p>
                <div style="border-bottom: 1px dotted #000; height: 26px; width: 100%; margin-bottom: 15px;"></div>
                <div style="border-bottom: 1px dotted #000; height: 26px; width: 100%; margin-bottom: 15px;"></div>
                <div style="border-bottom: 1px dotted #000; height: 26px; width: 100%; margin-bottom: 15px;"></div>
            </div>

            <p class="mb-12">Demikian Berita Acara ini dibuat dengan sesungguhnya untuk dapat dipergunakan sebagaimana mestinya.</p>
        </div>

        <!-- Tanda Tangan -->
        <div style="margin-top: 20px;">
            <div class="ttd-box" style="float: left;">
                <p>Proktor / Teknisi,</p>
                <div style="height: 70px;"></div>
                <div style="display: inline-block; text-align: left;">
                    <p style="font-weight: bold; text-decoration: underline;">(..............................................)</p>
                    <p>NIP. </p>
                </div>
            </div>
            <div class="ttd-box" style="float: right;">
                <p>Pengawas Ujian,</p>
                <div style="height: 70px;"></div>
                <div style="display: inline-block; text-align: left;">
                    <p style="font-weight: bold; text-decoration: underline;">(..............................................)</p>
                    <p>NIP. </p>
                </div>
            </div>
            <div class="clear"></div>
        </div>

    </div>

</body>
</html><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/cbt/berita_acara.blade.php ENDPATH**/ ?>