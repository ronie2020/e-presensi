<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak SPT - <?php echo e($spt->nomor_spt); ?></title>
    
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
            /* Font default untuk elemen web / non-cetak */
            font-family: 'Times New Roman', serif;
            background-color: #f8fafc; /* Slate-50 */
            -webkit-print-color-adjust: exact;
        }

        /* FONT KHUSUS KOP & ISI SURAT (BOOKMAN OLD STYLE) */
        .area-surat {
            font-family: 'Bookman Old Style', Bookman, Georgia, serif;
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

        /* MODIFIKASI GARIS KOP SURAT (Garis Tebal & Tipis) */
        .garis-kop {
            border-bottom: 3px solid black;
            margin-bottom: 2px;
        }
        .garis-kop-bawah {
            border-bottom: 1px solid black;
            margin-bottom: 24px; /* Jarak dari garis ke judul surat */
        }

        /* MODIFIKASI TABEL */
        .spt-table { width: 100%; border-collapse: collapse; margin-top: 15px; margin-bottom: 15px; }
        .spt-table td { padding: 4px 8px; vertical-align: top; }
        .col-label { width: 25%; font-weight: normal; }
        .col-separator { width: 3%; text-align: center; }
        
        .pengikut-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .pengikut-table th, .pengikut-table td { 
            border: 1px solid black; 
            padding: 6px; 
            text-align: left; 
        }
        .pengikut-table th { background-color: #f3f4f6; }

        .ttd-box { width: 300px; float: right; margin-top: 30px; text-align: left; }

        /* KHUSUS CETAK */
        @media print {
            body { background-color: white !important; margin: 0; padding: 0; }
            .no-print { display: none !important; }
            .sheet { margin: 0 !important; box-shadow: none !important; border: none !important; padding: 1.5cm 2cm; }
            .print-break { page-break-inside: avoid; }
        }
    </style>
</head>
<body class="min-h-screen relative">

    <!-- DEKORASI BACKGROUND (Hanya tampil di layar) -->
    <div class="fixed top-0 left-0 w-full h-64 bg-gradient-to-b from-elevate-primary/10 to-transparent pointer-events-none no-print -z-10"></div>

    <!-- TOOLBAR AKSI -->
    <div class="w-[21.5cm] mx-auto mt-6 mb-6 flex flex-col sm:flex-row justify-between items-center gap-4 no-print bg-white/80 backdrop-blur-md p-4 rounded-2xl shadow-lg shadow-elevate-dark/5 border border-white/60 sticky top-4 z-50">
        <div>
            <h2 class="font-black text-elevate-dark font-sans flex items-center gap-2">
                <i class="ph-bold ph-printer text-elevate-primary text-xl"></i> Pratinjau Cetak SPT
            </h2>
            <p class="text-xs text-slate-500 font-bold ml-7 font-sans">Kertas: F4 (Folio) | Skala: 100%</p>
        </div>

        <div class="flex flex-wrap gap-3 items-center font-sans">
            <a href="<?php echo e(route('letters.spt.index')); ?>" class="px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 hover:text-elevate-primary transition-colors shadow-sm flex items-center gap-2 group">
                <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali
            </a>
            
            <a href="<?php echo e(route('letters.spt.edit', $spt->id)); ?>" class="px-4 py-2.5 bg-elevate-accent/10 border border-elevate-accent/20 text-elevate-primary rounded-xl shadow-sm text-xs font-bold hover:bg-elevate-accent/20 hover:text-elevate-dark transition-colors flex items-center gap-2">
                <i class="ph-bold ph-pencil-simple text-sm"></i> Edit SPT
            </a>

            <button onclick="window.print()" class="px-5 py-2.5 bg-elevate-primary text-white font-bold rounded-xl hover:bg-elevate-dark shadow-lg shadow-elevate-primary/30 transition-transform active:scale-95 flex items-center gap-2 text-xs group">
                <i class="ph-bold ph-printer text-sm group-hover:scale-110 transition-transform"></i> Print / PDF
            </button>
        </div>
    </div>

    <!-- AREA KERTAS -->
    <div class="sheet area-surat text-[12pt]">
        
        <!-- KOP SURAT (FLEXBOX BIKIN LEBIH RAPI) -->
        <div class="kop-surat garis-kop pb-2 pt-2 flex justify-between items-center px-1">
            <!-- Logo Kiri -->
            <img src="<?php echo e(asset('img/logo_ciamis.png')); ?>" alt="Logo Ciamis" class="w-[85px] h-auto object-contain" onerror="this.style.display='none'"> 
            
            <!-- Teks Tengah -->
            <div class="text-center flex-1 px-4 leading-tight">
                <div class="text-[14pt] tracking-wide mb-1">PEMERINTAH KABUPATEN CIAMIS</div>
                <div class="font-bold text-[22pt] tracking-wider mb-1">SMP NEGERI 3 LAKBOK</div>
                <div class="text-[12pt]">Jalan Mekarjaya No.199, Sidaharja</div>
                <div class="text-[12pt]">Kecamatan Lakbok, Kabupaten Ciamis 46385</div>
                <div class="text-[10pt] mt-1">
                    Laman: <a href="http://www.smpn3lakbok.sch.id" class="text-blue-700 underline">www.smpn3lakbok.sch.id</a> 
                    <span class="mx-3"></span> 
                    E-mail: netila.smp@gmail.com
                </div>
            </div>

            <!-- Logo Kanan -->
            <img src="<?php echo e(asset('img/logo_sekolah.png')); ?>" alt="Logo Sekolah" class="w-[85px] h-auto object-contain" onerror="this.style.display='none'">
        </div>
        <!-- Garis tipis pelengkap batas kop -->
        <div class="garis-kop-bawah"></div>

        <!-- ISI SURAT -->
        <div class="text-center mb-6">
            <h2 class="text-lg font-bold uppercase underline underline-offset-4 mb-1">SURAT PERINTAH TUGAS</h2>
            <p>Nomor : <?php echo e($spt->nomor_spt); ?></p>
        </div>

        <table class="spt-table">
            <tr>
                <td class="col-label">Dasar</td>
                <td class="col-separator">:</td>
                <td class="text-justify"><?php echo e($spt->dasar ?? 'Peraturan / Undangan Terkait.'); ?></td>
            </tr>            
        </table>
        
        <p class="mt-4 text-justify" style="text-indent: 3rem;">
            Kepala SMP Negeri 3 Lakbok, Kabupaten Ciamis, memberikan Tugas Kepada : 
        </p>

        <table class="spt-table">
            <tr>
                <td class="col-label">Kepada</td>
                <td class="col-separator">:</td>
                <td>
                    <table class="w-full">
                        <?php $__empty_1 = true; $__currentLoopData = $spt->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="<?php echo e($index > 0 ? 'mt-2' : ''); ?>">
                            <td style="width: 20px; vertical-align: top;"><?php echo e($index + 1); ?>.</td>
                            <td style="width: 80px; vertical-align: top;">Nama</td>
                            <td style="vertical-align: top;">: <strong><?php echo e($user->name); ?></strong></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="vertical-align: top;">NIP</td>
                            <td style="vertical-align: top;">: <?php echo e($user->nip ?? '-'); ?></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="vertical-align: top;">Jabatan</td>
                            <td style="vertical-align: top;">: <?php echo e($user->jabatan ?? 'Guru / Staf'); ?></td>
                        </tr>
                        
                        <?php if(!$loop->last): ?>
                            <tr><td colspan="3" style="height: 10px;"></td></tr>
                        <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="3" class="text-rose-500 italic font-sans">Data pegawai belum dipilih.</td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </td>
            </tr>
            
            <?php if(count($spt->pengikut ?? []) > 0): ?>
            <tr>
                <td class="col-label">Pengikut</td>
                <td class="col-separator">:</td>
                <td>
                    <table class="pengikut-table">
                        <thead>
                            <tr>
                                <th style="width: 30px; text-align: center;">No</th>
                                <th>Nama Lengkap</th>
                                <th>NIP/NIK</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $spt->pengikut; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $pengikut): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td style="text-align: center;"><?php echo e($idx + 1); ?></td>
                                <td><?php echo e($pengikut['nama']); ?></td>
                                <td><?php echo e($pengikut['nip'] ?? '-'); ?></td>
                                <td><?php echo e($pengikut['keterangan'] ?? '-'); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </td>
            </tr>
            <?php endif; ?>

            <tr>
                <td class="col-label" style="padding-top: 15px;">Untuk</td>
                <td class="col-separator" style="padding-top: 15px;">:</td>
                <td class="text-justify" style="padding-top: 15px;"><?php echo e($spt->untuk); ?></td>
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
                    (<?php echo e($spt->lama_hari); ?> hari)
                </td>
            </tr>
        </table>

        <p class="mt-6 text-justify" style="text-indent: 3rem;">
            Demikian Surat Perintah Tugas ini dibuat untuk dilaksanakan dengan penuh tanggung jawab dan melaporkan hasilnya setelah kegiatan selesai.
        </p>

        <!-- TANDA TANGAN -->
        <div class="ttd-box print-break">
            <p>Ditetapkan di : Lakbok</p>
            <p class="mb-6">Pada tanggal &nbsp;: <?php echo e(\Carbon\Carbon::parse($spt->created_at)->isoFormat('D MMMM Y')); ?></p>
            
            <p class="font-bold">Kepala Sekolah,</p>
            
            <div style="height: 70px;"></div>
            
            <p class="font-bold underline whitespace-nowrap"><?php echo e($spt->pejabat_nama); ?></p>
            <p>NIP. <?php echo e($spt->pejabat_nip); ?></p>
        </div>

    </div> <!-- End Kertas -->

</body>
</html><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/letters/spt/print.blade.php ENDPATH**/ ?>