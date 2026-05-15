<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Daftar Hadir - <?php echo e($exam->title); ?></title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        /* PENGATURAN KERTAS F4 (Folio) */
        @page { 
            size: 21.5cm 33cm; 
            /* Pindahkan margin ke @page agar browser tahu batas aman untuk memotong baris */
            margin: 1.5cm 2cm; 
        }
        
        body {
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
            padding: 1.5cm 2cm; /* Padding untuk tampilan di layar (akan dinonaktifkan saat print) */
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            position: relative;
        }
        
        /* MODE PRINT */
        @media print {
            body { background: none; margin: 0; }
            .sheet { 
                width: auto; 
                min-height: auto;
                margin: 0; 
                padding: 0; /* Dinonaktifkan karena margin sudah di-handle oleh @page */
                box-shadow: none; 
                border: none; 
            }
            .no-print { display: none !important; }

            /* MENCEGAH TABEL TERPOTONG DI TENGAH BARIS */
            table.data { page-break-inside: auto; }
            table.data tr { page-break-inside: avoid; page-break-after: auto; }
            table.data thead { display: table-header-group; } /* Mengulang header tabel di halaman baru */

            /* MENCEGAH FOOTER TANDA TANGAN TERBELAH */
            .footer-section { page-break-inside: avoid; }
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

        /* TABEL DATA */
        table.info-ujian { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        table.info-ujian td { padding: 4px 5px; vertical-align: top; }

        table.data { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 11pt; }
        table.data th { background-color: #f3f4f6 !important; text-align: center; font-weight: bold; padding: 8px; }
        table.data td { border: 1px solid black; padding: 6px; }
        table.data th { border: 1px solid black; }
    </style>
</head>
<body>

    <!-- TOOLBAR (Floating) -->
    <div class="no-print fixed top-0 left-0 right-0 bg-white/90 backdrop-blur-md border-b border-slate-200 shadow-sm p-4 flex justify-between items-center z-50">
        <div class="flex items-center gap-4">
            <div class="bg-blue-900 p-2.5 rounded-xl text-white shadow-lg shadow-blue-900/20">
                <i class="ph-bold ph-users-three text-xl"></i>
            </div>
            <div>
                <h1 class="font-black text-slate-800 text-sm md:text-base font-sans">Pratinjau Daftar Hadir</h1>
                <p class="text-xs text-slate-500 font-sans font-bold"><?php echo e($exam->title); ?></p>
            </div>
        </div>
        <div class="flex gap-3">
            <a href="<?php echo e(route('cbt.events.show', $exam->cbt_event_id ?? 0)); ?>" class="px-5 py-2.5 text-xs font-bold text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 hover:text-slate-800 transition shadow-sm font-sans flex items-center gap-2">
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
            <h2>DAFTAR HADIR</h2>
            <p style="font-weight: bold; margin-top: 5px; font-size: 12pt;"><?php echo e(strtoupper($exam->title)); ?></p>
            <?php
                // Mengambil tahun ajaran yang sedang aktif dari database
                $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
                $tahunPelajaran = $activeYear ? $activeYear->name : date('Y') . '/' . date('Y', strtotime('+1 year'));
            ?>
            <p>Tahun Pelajaran <?php echo e($tahunPelajaran); ?></p>
        </div>

        <!-- Info Ujian -->
        <table class="info-ujian">
            <tr>
                <td style="width: 15%;">Mata Pelajaran</td><td style="width: 2%;">:</td><td style="width: 43%; font-weight: bold;"><?php echo e($exam->subject_name); ?></td>
                <td style="width: 12%;">Tanggal</td><td style="width: 2%;">:</td><td style="width: 26%;"><?php echo e($exam->start_time ? \Carbon\Carbon::parse($exam->start_time)->locale('id')->isoFormat('dddd, D MMMM Y') : '-'); ?></td>
            </tr>
            <tr>
                <td>Kelas Target</td><td>:</td><td style="font-weight: bold;"><?php echo e($exam->class_level); ?></td>
                <td>Waktu</td><td>:</td><td><?php echo e($exam->start_time ? \Carbon\Carbon::parse($exam->start_time)->format('H:i') : '-'); ?> - <?php echo e($exam->end_time ? \Carbon\Carbon::parse($exam->end_time)->format('H:i') : '-'); ?> WIB</td>
            </tr>
            <tr>
                <td>Sistem Ujian</td><td>:</td><td style="font-weight: bold; text-transform: uppercase;"><?php echo e(str_replace('_', ' ', $exam->exam_type)); ?></td>
                <td colspan="3"></td> <!-- Dikosongkan karena info peserta pindah ke bawah -->
            </tr>
        </table>

        <!-- Tabel Peserta -->
        <table class="data">
            <thead>
                <tr>
                    <th style="width: 40px;">No</th>
                    <th style="width: 130px;">NISN/NIS</th>
                    <th>Nama Peserta</th>
                    <th style="width: 80px;">Kelas</th>
                    <th colspan="2" style="width: 160px;">Tanda Tangan</th>
                    <th style="width: 80px;">Ket</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td style="text-align: center;"><?php echo e($index + 1); ?></td>
                    <td style="text-align: center;"><?php echo e($student->student_id); ?></td>
                    <td style="font-weight: bold;"><?php echo e($student->name); ?></td>
                    <td style="text-align: center;"><?php echo e($student->schoolClass->name ?? '-'); ?></td>
                    
                   <!-- Kolom Tanda Tangan Zig-Zag -->
                    <td colspan="2" style="position: relative; height: 35px; width: 160px; padding: 0;">
                        <?php if($index % 2 == 0): ?>
                            <!-- Ganjil: Di kiri -->
                            <span style="position: absolute; top: 4px; left: 8px; font-size: 10px; color: #555;"><?php echo e($index + 1); ?>.</span>
                        <?php else: ?>
                            <!-- Genap: Di tengah (50% dari lebar kolom) -->
                            <span style="position: absolute; top: 4px; left: 50%; font-size: 10px; color: #555;"><?php echo e($index + 1); ?>.</span>
                        <?php endif; ?>
                    </td>
                    
                    <!-- Keterangan Hadir -->
                    <td style="text-align: center; font-size: 14pt; font-weight: bold; color: #16a34a;">
                        <?php if(in_array($student->id, $sessions)): ?>
                            &#10003;
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px; color: #6b7280;">Tidak ada data siswa ditemukan untuk kelas ini.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- FOOTER: Rekapitulasi & Tanda Tangan (Ditambahkan kelas footer-section) -->
        <div class="footer-section" style="margin-top: 30px;">
            
            <!-- Blok Rekapitulasi Kehadiran (Kiri) -->
            <div style="float: left; width: 350px; margin-top: 10px;">
                <p style="font-weight: bold; font-size: 9.5pt; text-decoration: underline; margin-bottom: 5px;">Rekapitulasi Kehadiran:</p>
                <table style="width: 100%; border-collapse: collapse; font-size: 9.5pt;">
                    <tr>
                        <td style="padding: 3px 0; width: 60%;">Jumlah Peserta Seluruhnya</td>
                        <td style="padding: 3px 5px; width: 5%;">:</td>
                        <td style="padding: 3px 0; font-weight: bold;"><?php echo e(count($students)); ?> Siswa</td>
                    </tr>
                    <tr>
                        <td style="padding: 3px 0;">Siswa Mengerjakan (Hadir)</td>
                        <td style="padding: 3px 5px;">:</td>
                        <td style="padding: 3px 0; font-weight: bold;"><?php echo e(count($sessions)); ?> Siswa</td>
                    </tr>
                    <tr>
                        <td style="padding: 3px 0;">Belum Mengerjakan (Absen)</td>
                        <td style="padding: 3px 5px;">:</td>
                        <td style="padding: 3px 0; font-weight: bold;"><?php echo e(count($students) - count($sessions)); ?> Siswa</td>
                    </tr>
                </table>
            </div>

            <!-- Tanda Tangan Pengawas (Kanan) -->
            <div style="float: right; width: 250px; text-align: center;">
                <p>Pengawas Ujian,</p>
                <div style="height: 70px;"></div>
                <div style="display: inline-block; text-align: left;">
                    <p style="font-weight: bold; text-decoration: underline;">(..............................................)</p>
                    <p>NIP. </p>
                </div>
            </div>

            <div style="clear: both;"></div>
        </div>

    </div>

</body>
</html><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/cbt/daftar_hadir.blade.php ENDPATH**/ ?>