<!-- DEFINISI FUNGSI TERBILANG -->
<?php
    if (!function_exists('Terbilang')) {
        function Terbilang($x) {
            $angka = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];
            if ($x < 12) return $angka[$x];
            elseif ($x < 20) return $angka[$x - 10] . " Belas";
            elseif ($x < 100) return $angka[$x / 10] . " Puluh " . $angka[$x % 10];
            return $x;
        }
    }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak SPPD - <?php echo e($sppd->nomor_sppd); ?></title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- INJEKSI TEMA MICROSOFT ELEVATE UNTUK HALAMAN STANDALONE -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        elevate: {
                            dark: '#032b5b',
                            primary: '#3b5889',
                            accent: '#38bdf8',
                            text: '#1e293b',
                        }
                    }
                }
            }
        }
    </script>

    
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        /* PENGATURAN KERTAS F4 (Folio) */
        @page { 
            size: 21.5cm 33cm; 
            margin: 0; 
        }
        
        body {
            font-family: 'Times New Roman', serif;
            font-size: 11pt;
            background-color: #f8fafc; /* Slate-50 */
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

        /* TYPOGRAPHY SURAT */
        .header-text h3 { margin: 0; font-size: 14pt; font-weight: bold; text-transform: uppercase; }
        .header-text h4 { margin: 0; font-size: 12pt; font-weight: bold; text-transform: uppercase; }
        .header-text p { margin: 0; font-size: 10pt; }
        
        .double-line { border-top: 4px double #000; margin-top: 8px; margin-bottom: 20px; }
        
        .judul-surat { text-align: center; font-weight: bold; text-transform: uppercase; margin-bottom: 10px; }
        .judul-surat h2 { margin: 0; text-decoration: underline; font-size: 12pt; }
        .judul-surat p { margin: 0; font-size: 11pt; font-weight: normal; text-transform: none; }

        /* TABEL DATA UTAMA */
        table.data { width: 100%; border-collapse: collapse; margin-top: 5px; font-size: 11pt; }
        table.data td { vertical-align: top; padding: 5px; border: 1px solid black; }
        table.data tr td:first-child { width: 30px; text-align: center; }
        table.data tr td:nth-child(2) { width: 35%; }

        /* TABEL VISUM */
        table.visum { width: 100%; border-collapse: collapse; border: 1px solid black; margin-top: 10px; }
        table.visum td { border: 1px solid black; padding: 8px; vertical-align: top; width: 50%; height: 140px; }

        .ttd-box { float: right; width: 350px; text-align: left; margin-top: 20px; }
        .clear { clear: both; }
        .indent { margin-left: 30px; text-align: justify; display: block; }
        .label-section { font-weight: bold; width: 200px; display: inline-block; }
    </style>
</head>
<body class="relative">

    <!-- DEKORASI BACKGROUND (Hanya tampil di layar) -->
    <div class="fixed top-0 left-0 w-full h-64 bg-gradient-to-b from-elevate-primary/10 to-transparent pointer-events-none no-print -z-10"></div>

    <!-- TOOLBAR AKSI (Tidak tercetak) - Tema Microsoft Elevate -->
    <div class="w-[21.5cm] mx-auto mt-6 mb-6 flex flex-col sm:flex-row justify-between items-center gap-4 no-print bg-white/80 backdrop-blur-md p-4 rounded-2xl shadow-lg shadow-elevate-dark/5 border border-white/60 sticky top-4 z-50">
        <div>
            <h2 class="font-black text-elevate-dark font-sans flex items-center gap-2">
                <i class="ph-bold ph-printer text-elevate-primary text-xl"></i> Pratinjau Cetak SPPD
            </h2>
            <p class="text-xs text-slate-500 font-bold ml-7 font-sans">No: <?php echo e($sppd->nomor_sppd); ?> | Kertas: F4 (Folio)</p>
        </div>

        <div class="flex flex-wrap gap-3 items-center font-sans">
            <a href="<?php echo e(route('sppd.index')); ?>" class="px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 hover:text-elevate-primary transition-colors shadow-sm flex items-center gap-2 group">
                <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali
            </a>
            
            <button onclick="window.print()" class="px-5 py-2.5 bg-elevate-primary text-white font-bold rounded-xl hover:bg-elevate-dark shadow-lg shadow-elevate-primary/30 transition-transform active:scale-95 flex items-center gap-2 text-xs group">
                <i class="ph-bold ph-printer text-sm group-hover:scale-110 transition-transform"></i> Cetak / PDF
            </button>
        </div>
    </div>

    <!-- HALAMAN 1: SPPD DEPAN -->
    <div class="sheet">
         <!-- KOP SURAT -->
        <div class="relative py-2">
            <img src="<?php echo e(asset('img/logo_ciamis.png')); ?>" alt="Logo Ciamis" class="absolute left-0 top-1 w-16 h-auto object-contain" onerror="this.style.display='none'"> 
            <div class="text-center header-text mx-auto w-3/4">
                <h3>PEMERINTAH KABUPATEN CIAMIS</h3>
                <h3>DINAS PENDIDIKAN</h3>
                <h4>SMP NEGERI 3 LAKBOK</h4>
                <p>Jalan Mekarjaya No. 199 Sidaharja Kecamatan Lakbok Kabupaten Ciamis</p>
            </div>
            <img src="<?php echo e(asset('img/logo_sekolah.png')); ?>" alt="Logo Sekolah" class="absolute right-0 top-1 w-20 h-auto object-contain" onerror="this.style.display='none'">
        </div>
        <div class="double-line"></div>

        <div class="judul-surat">
            <h2>SURAT PERINTAH PERJALANAN DINAS</h2>
            <p>Nomor: <?php echo e($sppd->nomor_sppd); ?></p>
        </div>

        <table class="data">
            <tr><td>1</td><td>Pejabat berwenang yang memberi perintah</td><td colspan="2"><?php echo e($sppd->pejabat_jabatan); ?></td></tr>
            <tr><td>2</td><td>Nama / NIP Pegawai yang diperintah</td><td colspan="2"><strong><?php echo e($sppd->user->name); ?></strong><br>NIP. <?php echo e($sppd->user->nip ?? '-'); ?></td></tr>
            <tr><td>3</td><td>a. Pangkat dan Golongan<br>b. Jabatan / Instansi<br>c. Tingkat Biaya</td><td colspan="2">a. <?php echo e($sppd->user->pangkat ?? '-'); ?><br>b. <?php echo e($sppd->user->position ?? 'Guru'); ?><br>c. -</td></tr>
            <tr><td>4</td><td>Maksud Perjalanan Dinas</td><td colspan="2"><?php echo e($sppd->maksud_perjalanan); ?></td></tr>
            <tr><td>5</td><td>Alat Angkutan</td><td colspan="2"><?php echo e($sppd->alat_angkut ?? 'Kendaraan Umum'); ?></td></tr>
            <tr><td>6</td><td>a. Tempat Berangkat<br>b. Tempat Tujuan</td><td colspan="2">a. <?php echo e($sppd->tempat_berangkat); ?><br>b. <?php echo e($sppd->tempat_tujuan); ?></td></tr>
            <tr><td>7</td><td>a. Lamanya Perjalanan<br>b. Tanggal Berangkat<br>c. Tanggal Kembali</td><td colspan="2">a. <?php echo e($sppd->lama_hari); ?> (<?php echo e(Terbilang($sppd->lama_hari)); ?>) hari<br>b. <?php echo e(\Carbon\Carbon::parse($sppd->tgl_berangkat)->isoFormat('D MMMM Y')); ?><br>c. <?php echo e(\Carbon\Carbon::parse($sppd->tgl_kembali)->isoFormat('D MMMM Y')); ?></td></tr>
            
            <!-- PENGIKUT -->
            <tr>
                <td>8</td>
                <td>Pengikut: Nama</td>
                <td style="width: 35%; text-align: center;">NIP / NIK</td>
                <td style="width: 20%; text-align: center;">Keterangan</td>
            </tr>
            <?php if($sppd->followers->count() > 0): ?>
                <?php $__currentLoopData = $sppd->followers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $follower): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td></td>
                    <td><?php echo e($index + 1); ?>. <?php echo e($follower->nama); ?></td>
                    <td style="text-align: center;"><?php echo e($follower->nip ?? '-'); ?></td>
                    <td style="text-align: center;"><?php echo e($follower->keterangan); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <tr><td></td><td>1. -</td><td></td><td></td></tr>
            <?php endif; ?>

            <tr><td>9</td><td>Pembebanan Anggaran<br>a. Instansi<br>b. Mata Anggaran</td><td colspan="2"><br>a. <?php echo e($sppd->instansi_pembayar); ?><br>b. <?php echo e($sppd->mata_anggaran ?? '-'); ?></td></tr>
            <tr><td>10</td><td>Keterangan Lain</td><td colspan="2"><?php echo e($sppd->keterangan_lain ?? '-'); ?></td></tr>
        </table>

        <!-- TTD HALAMAN 1 -->
        <div class="ttd-box">
            <p>Dikeluarkan di: Lakbok</p>
            <p class="mb-6">Pada tanggal: <?php echo e(\Carbon\Carbon::now()->isoFormat('D MMMM Y')); ?></p>
            <p style="font-weight: bold;"><?php echo e($sppd->pejabat_jabatan); ?></p>
            <div style="height: 60px;"></div>
            <p class="whitespace-nowrap" style="font-weight: bold; text-decoration: underline;"><?php echo e($sppd->pejabat_nama); ?></p>
            <p>NIP. <?php echo e($sppd->pejabat_nip); ?></p>
        </div>
        <div class="clear"></div>
    </div>

    <!-- HALAMAN 2: VISUM -->
    <div class="sheet">
        <table class="visum">
            <tr>
                <td></td>
                <td>
                    <p style="margin:0;">I. Berangkat dari : <?php echo e($sppd->tempat_berangkat); ?></p>
                    <p style="margin:0; text-indent: 14px;">(Tempat Kedudukan)</p>
                    <p style="margin:0; text-indent: 14px;">Ke : <?php echo e($sppd->tempat_tujuan); ?></p>
                    <p style="margin:0; text-indent: 14px;">Pada Tanggal : <?php echo e(\Carbon\Carbon::parse($sppd->tgl_berangkat)->isoFormat('D MMMM Y')); ?></p>
                    <br><p style="margin:0; text-align:center; font-weight:bold;">Kepala SMP Negeri 3 Lakbok</p><br><br><br>
                    <p class="whitespace-nowrap" style="margin:0; text-align:center; font-weight:bold; text-decoration:underline;"><?php echo e($sppd->pejabat_nama); ?></p>
                    <p style="margin:0; text-align:center;">NIP. <?php echo e($sppd->pejabat_nip); ?></p>
                </td>
            </tr>
            <tr>
                 <td>
                    <p style="margin:0;">II. Tiba di : <?php echo e($sppd->tempat_tujuan); ?></p>
                    <p style="margin:0; text-indent: 18px;">Pada Tanggal : <?php echo e(\Carbon\Carbon::parse($sppd->tgl_berangkat)->isoFormat('D MMMM Y')); ?></p>
                    
                    <div style="text-align: center; margin-top: 25px;">
                        <p style="margin:0;">Kepala / Pejabat Setempat</p>
                        <div style="height: 60px;"></div>
                        <div style="display: inline-block; text-align: left;">
                            <p style="margin:0;">( .............................................. )</p>
                            <p style="margin:0;">NIP.</p>
                        </div>
                    </div>
                </td>
                <td>
                    <p style="margin:0; text-indent: 14px;">Berangkat dari : <?php echo e($sppd->tempat_tujuan); ?></p>
                    <p style="margin:0; text-indent: 14px;">Ke : <?php echo e($sppd->tempat_berangkat); ?></p>
                    <p style="margin:0; text-indent: 14px;">Pada Tanggal : <?php echo e(\Carbon\Carbon::parse($sppd->tgl_kembali)->isoFormat('D MMMM Y')); ?></p>
                    
                    <div style="text-align: center; margin-top: 15px;">
                        <p style="margin:0;">Kepala / Pejabat Setempat</p>
                        <div style="height: 60px;"></div>
                        <div style="display: inline-block; text-align: left;">
                            <p style="margin:0;">( .............................................. )</p>
                            <p style="margin:0;">NIP.</p>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td><p>III. Tiba di:</p><br><br><br><br><br><p style="text-align:center;">(..............................................)</p></td>
                <td><p>Berangkat dari:</p><br><br><br><br><br><p style="text-align:center;">(..............................................)</p></td>
            </tr>
            <tr>
                <td>
                    <p style="margin:0;">IV. Tiba di: <?php echo e($sppd->tempat_berangkat); ?></p>
                    <p style="margin:0; text-indent: 18px;">(Tempat Kedudukan)</p>
                    <p style="margin:0; text-indent: 18px;">Pada Tanggal: <?php echo e(\Carbon\Carbon::parse($sppd->tgl_kembali)->isoFormat('D MMMM Y')); ?></p>
                    <br><p style="margin:0; text-align:center; font-weight:bold;">Kepala SMP Negeri 3 Lakbok</p><br><br><br>
                    <p class="whitespace-nowrap" style="margin:0; text-align:center; font-weight:bold; text-decoration:underline;"><?php echo e($sppd->pejabat_nama); ?></p>
                    <p style="margin:0; text-align:center;">NIP. <?php echo e($sppd->pejabat_nip); ?></p>
                </td>
                <td style="text-align: justify; padding: 10px;">Telah diperiksa dengan keterangan bahwa perjalanan tersebut atas perintahnya dan semata-mata untuk kepentingan jabatan.</td>
            </tr>
            <tr><td colspan="2"><p>V. Catatan Lain-lain:</p></td></tr>
            <tr><td colspan="2"><p style="font-weight:bold;">VI. PERHATIAN:</p><p style="text-align: justify; font-size: 9pt;">Pejabat yang berwenang dan pejabat/pegawai yang melakukan perjalanan dinas bertanggung jawab sepenuhnya atas kerugian yang diderita oleh negara.</p></td></tr>
        </table>
    </div>

    <!-- HALAMAN 3: LAPORAN -->
    <div class="sheet">
        <!-- KOP SURAT -->
        <div class="relative py-2">
            <img src="<?php echo e(asset('img/logo_ciamis.png')); ?>" alt="Logo Ciamis" class="absolute left-0 top-1 w-16 h-auto object-contain" onerror="this.style.display='none'"> 
            <div class="text-center header-text mx-auto w-3/4">
                <h3>PEMERINTAH KABUPATEN CIAMIS</h3>
                <h3>DINAS PENDIDIKAN</h3>
                <h4>SMP NEGERI 3 LAKBOK</h4>
                <p>Jalan Mekarjaya No. 199 Sidaharja Kecamatan Lakbok Kabupaten Ciamis</p>
            </div>
            <img src="<?php echo e(asset('img/logo_sekolah.png')); ?>" alt="Logo Sekolah" class="absolute right-0 top-1 w-20 h-auto object-contain" onerror="this.style.display='none'">
        </div>
        <div class="double-line"></div>
        
        <div class="judul-surat" style="margin-bottom: 30px;"><h2>LAPORAN PERJALANAN DINAS</h2></div>
        
        <div class="content" style="line-height: 1.6;">
            <p><span class="label-section">I. DASAR</span></p>
            <span class="indent">Surat Perintah Tugas Kepala SMP Negeri 3 Lakbok Nomor: <?php echo e(str_replace('090', '094', $sppd->nomor_sppd)); ?> Tanggal <?php echo e(\Carbon\Carbon::parse($sppd->tgl_berangkat)->isoFormat('D MMMM Y')); ?>.</span>
            
            <p class="mt-4"><span class="label-section">II. MAKSUD DAN TUJUAN</span></p>
            <span class="indent"><?php echo e($sppd->maksud_perjalanan); ?></span>
            
            <p class="mt-4"><span class="label-section">III. WAKTU PELAKSANAAN</span></p>
            <span class="indent">Kegiatan dilaksanakan pada hari <?php echo e(\Carbon\Carbon::parse($sppd->tgl_berangkat)->isoFormat('dddd')); ?> tanggal <?php echo e(\Carbon\Carbon::parse($sppd->tgl_berangkat)->isoFormat('D MMMM Y')); ?> bertempat di <?php echo e($sppd->tempat_tujuan); ?>.</span>
            
             <p class="mt-4"><span class="label-section">IV. HASIL KEGIATAN</span></p>
            <div class="indent">
                <div style="border-bottom: 1px dotted #666; margin-top: 30px;"></div>
                <div style="border-bottom: 1px dotted #666; margin-top: 30px;"></div>
                <div style="border-bottom: 1px dotted #666; margin-top: 30px;"></div>
                <div style="border-bottom: 1px dotted #666; margin-top: 30px;"></div>
                <div style="border-bottom: 1px dotted #666; margin-top: 30px;"></div>
                <div style="border-bottom: 1px dotted #666; margin-top: 30px;"></div>
                <div style="border-bottom: 1px dotted #666; margin-top: 30px;"></div>
            </div>
            
            <p class="mt-4"><span class="label-section">V. KESIMPULAN / SARAN</span></p>
            <div class="indent">
                <div style="border-bottom: 1px dotted #666; margin-top: 30px;"></div>
                <div style="border-bottom: 1px dotted #666; margin-top: 30px;"></div>
                <div style="border-bottom: 1px dotted #666; margin-top: 30px;"></div>
            </div>
        </div>

        <div style="margin-top: 50px;">
            <div style="float: left; width: 48%; text-align: center;">
                <p>Mengetahui,<br>Kepala Sekolah</p>
                <div style="height: 60px;"></div>
                <p style="font-weight: bold; text-decoration: underline; white-space: nowrap;"><?php echo e($sppd->pejabat_nama); ?></p>
                <p>NIP. <?php echo e($sppd->pejabat_nip); ?></p>
            </div>
            <div style="float: right; width: 48%; text-align: center;">
                <p>Lakbok, <?php echo e(\Carbon\Carbon::parse($sppd->tgl_kembali)->isoFormat('D MMMM Y')); ?><br>Pelapor,</p>
                <div style="height: 60px;"></div>
                <p style="font-weight: bold; text-decoration: underline; white-space: nowrap;"><?php echo e($sppd->user->name); ?></p>
                <p>NIP. <?php echo e($sppd->user->nip ?? '-'); ?></p>
            </div>
            <div class="clear"></div>
        </div>
    </div>

</body>
</html><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/sppd/print.blade.php ENDPATH**/ ?>