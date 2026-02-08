<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak SPT - <?php echo e($spt->nomor_spt); ?></title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        /* PENGATURAN KERTAS F4 (Folio) */
        @page { 
            size: 21.5cm 33cm; 
            margin: 0; 
        }
        
        body {
            font-family: 'Times New Roman', serif;
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
        }

        /* MODIFIKASI SAAT DICETAK (PRINT MODE) */
        @media print {
            body { background: none; margin: 0; }
            .sheet { width: 100%; margin: 0; padding: 1cm 2cm; box-shadow: none; border: none; }
            .no-print { display: none !important; }
        }

        /* TYPOGRAPHY SURAT */
        .header-text h3 { margin: 0; font-size: 14pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        .header-text h4 { margin: 0; font-size: 12pt; font-weight: bold; text-transform: uppercase; }
        .header-text p { margin: 0; font-size: 10pt; }
        
        .double-line { 
            border-top: 4px double #000; 
            margin-top: 8px; 
            margin-bottom: 24px; 
        }

        .judul-surat { text-align: center; margin-bottom: 24px; }
        .judul-surat h2 { margin: 0; font-size: 13pt; font-weight: bold; text-decoration: underline; text-transform: uppercase; }
        .judul-surat p { font-size: 11pt; margin-top: 4px; }

        .content-table { width: 100%; border-collapse: collapse; font-size: 11pt; }
        .content-table td { vertical-align: top; padding: 4px 0; }
        .col-label { width: 100px; font-weight: normal; }
        .col-separator { width: 20px; text-align: center; }

        .table-pegawai { width: 100%; border-collapse: collapse; margin: 10px 0 20px 0; font-size: 11pt; }
        .table-pegawai th, .table-pegawai td { border: 1px solid #000; padding: 6px 8px; }
        .table-pegawai th { text-align: center; background-color: #e5e7eb; }
        @media print { .table-pegawai th { background-color: #ddd !important; -webkit-print-color-adjust: exact; } }

        .ttd-box { float: right; width: 40%; margin-top: 40px; text-align: left; font-size: 11pt; }
    </style>
</head>
<body>

    <!-- TOOLBAR (Hanya Tampil di Layar) -->
    <!-- UPDATED: Theme Blue-900 -->
    <div class="no-print fixed top-0 left-0 right-0 bg-white/90 backdrop-blur-md border-b border-slate-200 shadow-sm p-4 flex justify-between items-center z-50">
        <div class="flex items-center gap-4">
            <div class="bg-blue-900 p-2.5 rounded-xl text-white shadow-lg shadow-blue-900/20">
                <i class="ph-bold ph-printer text-xl"></i>
            </div>
            <div>
                <h1 class="font-black text-slate-800 text-sm md:text-base font-sans">Pratinjau Cetak SPT</h1>
                <p class="text-xs text-slate-500 font-sans font-bold">No: <?php echo e($spt->nomor_spt); ?></p>
            </div>
        </div>
        <div class="flex gap-3">
            <a href="<?php echo e(route('letters.spt.index')); ?>" class="px-5 py-2.5 text-xs font-bold text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 hover:text-slate-800 transition shadow-sm font-sans flex items-center gap-2">
                <i class="ph-bold ph-arrow-left"></i> Kembali
            </a>
            <button onclick="window.print()" class="px-5 py-2.5 text-xs font-bold text-white bg-blue-900 rounded-xl hover:bg-blue-800 transition shadow-lg shadow-blue-900/30 font-sans flex items-center gap-2">
                <i class="ph-bold ph-printer"></i> Cetak Sekarang
            </button>
        </div>
    </div>

    <div class="no-print h-24"></div>

    <!-- HALAMAN KERTAS (KONTEN TETAP ORIGINAL / FORMAL) -->
    <div class="sheet">
        
        <!-- KOP SURAT -->
        <div class="relative py-2">
            <img src="<?php echo e(asset('img/logo_ciamis.png')); ?>" alt="Logo Ciamis" 
                 class="absolute left-0 top-1 w-20 h-auto object-contain"
                 onerror="this.style.display='none'"> 
            
            <div class="text-center header-text mx-auto w-3/4">
                <h3>PEMERINTAH KABUPATEN CIAMIS</h3>
                <h3>DINAS PENDIDIKAN</h3>
                <h4>SMP NEGERI 3 LAKBOK</h4>
                <p>Jalan Mekarjaya No. 199 Sidaharja Kecamatan Lakbok Kabupaten Ciamis</p>
                <p>Laman: www.smpn3lakbok.sch.id   E-mail: smpn3lakbok@gmail.com</p>
            </div>

            <img src="<?php echo e(asset('img/logo_sekolah.png')); ?>" alt="Logo Sekolah" 
                 class="absolute right-0 top-1 w-20 h-auto object-contain"
                 onerror="this.style.display='none'">
        </div>
        
        <div class="double-line"></div>

        <!-- JUDUL -->
        <div class="judul-surat">
            <h2>SURAT PERINTAH TUGAS</h2>
            <p>Nomor: <?php echo e($spt->nomor_spt); ?></p>
        </div>

        <!-- ISI -->
        <table class="content-table">
            <tr>
                <td class="col-label">Dasar</td>
                <td class="col-separator">:</td>
                <td class="text-justify">
                    <?php if($spt->letterIncoming): ?>
                        Menindaklanjuti Surat dari <strong><?php echo e($spt->letterIncoming->pengirim); ?></strong> 
                        Nomor <?php echo e($spt->letterIncoming->nomor_surat); ?> 
                        tanggal <?php echo e(\Carbon\Carbon::parse($spt->letterIncoming->tgl_surat)->isoFormat('D MMMM Y')); ?> 
                        perihal "<?php echo e($spt->letterIncoming->perihal); ?>".
                    <?php else: ?>
                        Kepentingan Dinas Sekolah SMP Negeri 3 Lakbok dalam rangka peningkatan mutu pendidikan dan pelayanan sekolah.
                    <?php endif; ?>
                </td>
            </tr>
        </table>

        <div class="text-center font-bold my-4">MEMBERI TUGAS:</div>

        <table class="content-table">
            <tr>
                <td class="col-label">Kepada</td>
                <td class="col-separator">:</td>
                <td></td>
            </tr>
        </table>

        <!-- LIST PEGAWAI -->
        <table class="table-pegawai">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="40%">Nama / NIP</th>
                    <th width="20%">Pangkat/Gol.</th>
                    <th width="35%">Jabatan</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $spt->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td align="center" valign="top"><?php echo e($index + 1); ?></td>
                    <td valign="top">
                        <div style="font-weight: bold;"><?php echo e($user->name); ?></div>
                        <div style="font-size: 10pt; color: #333;">NIP. <?php echo e($user->nip ?? '-'); ?></div>
                    </td>
                    <td align="center" valign="top"><?php echo e($user->pangkat ?? '-'); ?></td>
                    <td align="center" valign="top"><?php echo e($user->position ?? 'Guru / Staf'); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

        <table class="content-table">
            <tr>
                <td class="col-label">Untuk</td>
                <td class="col-separator">:</td>
                <td class="text-justify"><?php echo e($spt->untuk); ?></td>
            </tr>
            <tr>
                <td class="col-label">Tempat</td>
                <td class="col-separator">:</td>
                <td><strong><?php echo e($spt->tempat_tujuan); ?></strong></td>
            </tr>
            <tr>
                <td class="col-label">Waktu</td>
                <td class="col-separator">:</td>
                <td>
                    <?php echo e(\Carbon\Carbon::parse($spt->tgl_berangkat)->isoFormat('dddd, D MMMM Y')); ?>

                    <?php if($spt->lama_hari > 1): ?>
                        s.d. <?php echo e(\Carbon\Carbon::parse($spt->tgl_kembali)->isoFormat('dddd, D MMMM Y')); ?>

                    <?php endif; ?>
                </td>
            </tr>
        </table>

        <p class="mt-4 text-justify" style="text-indent: 3rem;">
            Demikian Surat Perintah Tugas ini dibuat untuk dilaksanakan dengan penuh tanggung jawab dan melaporkan hasilnya setelah kegiatan selesai.
        </p>

        <!-- TANDA TANGAN -->
        <div class="ttd-box">
            <p>Ditetapkan di: Lakbok</p>
            <p class="mb-6">Pada tanggal: <?php echo e(\Carbon\Carbon::parse($spt->created_at)->isoFormat('D MMMM Y')); ?></p>
            
            <p class="font-bold">Kepala Sekolah,</p>
            
            <div style="height: 70px;"></div>
            
            <p style="font-weight: bold; text-decoration: underline;"><?php echo e($spt->pejabat_nama ?? '(Nama Kepala Sekolah)'); ?></p>
            <p>NIP. <?php echo e($spt->pejabat_nip ?? '....................'); ?></p>
        </div>

        <div style="clear: both;"></div>
    </div>

</body>
</html><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\letters\spt\print.blade.php ENDPATH**/ ?>