<!DOCTYPE html>
<html>
<head>
    <title>Rekap Nilai - <?php echo e($selectedClass->name); ?> - <?php echo e($selectedSubject->name); ?></title>
    <style>
        /* Mengatur halaman Landscape agar tabel nilai muat */
        @page { 
            size: A4 landscape; 
            margin: 1cm 1.5cm 1cm 1.5cm; 
        }

        body { 
            font-family: 'Times New Roman', Times, serif; 
            font-size: 11pt; 
            line-height: 1.3; 
            color: #000; 
        }

        /* HEADER / KOP SURAT */
        .header { 
            text-align: center; 
            border-bottom: 3px double #000; 
            padding-bottom: 8px; 
            margin-bottom: 20px; 
            position: relative;
        }
        
        /* Logo diposisikan absolute agar tidak merusak centering teks */
        .logo { 
            width: 75px; 
            height: auto; 
            position: absolute; 
            left: 20px; 
            top: 5px; 
        }
        
        .header h3 { margin: 0; font-size: 14pt; font-weight: normal; }
        .header h2 { margin: 0; font-size: 16pt; font-weight: bold; }
        .header h1 { margin: 0; font-size: 18pt; font-weight: bold; }
        .header p { margin: 0; font-size: 9pt; font-style: italic; }

        /* JUDUL LAPORAN */
        .title { 
            font-size: 14pt; 
            font-weight: bold; 
            text-decoration: underline; 
            text-align: center; 
            margin-bottom: 20px; 
            text-transform: uppercase;
        }

        /* INFO KELAS */
        .info-table { width: 100%; margin-bottom: 15px; font-size: 11pt; }
        .info-table td { padding: 2px 0; vertical-align: top; }
        .label { width: 150px; font-weight: bold; }
        .colon { width: 15px; text-align: center; }

        /* TABEL NILAI */
        .grade-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 20px; 
            font-size: 10pt; 
        }
        .grade-table th, .grade-table td { 
            border: 1px solid #000; 
            padding: 6px 4px; 
            text-align: center; 
        }
        .grade-table th { 
            background-color: #f0f0f0; 
            font-weight: bold; 
            vertical-align: middle;
        }
        .grade-table td.left { text-align: left; padding-left: 8px; }
        .grade-table td.name-col { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px; }

        /* TANDA TANGAN */
        .signature-section { 
            width: 100%; 
            margin-top: 30px; 
            display: table; /* Pengganti Flexbox untuk PDF support yang lebih baik */
        }
        .sign-box { 
            display: table-cell; 
            width: 33%; 
            text-align: center; 
            vertical-align: top;
        }
        .sign-space { height: 70px; }
        .sign-name { font-weight: bold; text-decoration: underline; }
        
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 8pt;
            font-style: italic;
            color: #555;
        }
    </style>
</head>
<body>
    <!-- KOP SURAT -->
    <div class="header">
        <!-- Pastikan path logo benar -->
        <img src="<?php echo e(public_path('images/logo.png')); ?>" class="logo" alt="Logo">
        
        <h3>PEMERINTAH KABUPATEN CIAMIS</h3>
        <h2>DINAS PENDIDIKAN</h2>
        <h1>SMP NEGERI 3 LAKBOK</h1>
        <p>Alamat: Jl. Mekarjaya No. 199, Desa Sidaharja, Kec. Lakbok, Kab. Ciamis 46385</p>
    </div>

    <h3 class="title">REKAPITULASI NILAI SISWA</h3>

    <!-- INFO KELAS -->
    <table class="info-table">
        <tr>
            <td class="label">Mata Pelajaran</td><td class="colon">:</td><td width="40%"><?php echo e($selectedSubject->name); ?></td>
            <td class="label">Kelas</td><td class="colon">:</td><td><?php echo e($selectedClass->name); ?></td>
        </tr>
        <tr>
            <td class="label">Guru Pengampu</td><td class="colon">:</td><td><?php echo e($teacher->name); ?></td>
            <td class="label">Tahun Ajaran</td><td class="colon">:</td><td><?php echo e(date('Y')); ?>/<?php echo e(date('Y')+1); ?></td>
        </tr>
    </table>

    <!-- TABEL NILAI -->
    <table class="grade-table">
        <thead>
            <tr>
                <th width="30">No</th>
                <th>Nama Siswa</th>
                <th width="80">NISN</th>
                
                
                <?php $__currentLoopData = $assignments->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <th style="font-size: 8pt;">
                        <?php echo e(\Illuminate\Support\Str::limit($task->title, 15)); ?>

                        <br>
                        <span style="font-weight: normal; font-size: 7pt;">(<?php echo e($task->created_at->format('d/m')); ?>)</span>
                    </th>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <th width="50">Rata2</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $total = 0; 
                    $count = 0;
                ?>
                <tr>
                    <td><?php echo e($index + 1); ?></td>
                    <td class="left name-col"><?php echo e($student->name); ?></td>
                    <td><?php echo e($student->student_id); ?></td>
                    
                    <?php $__currentLoopData = $assignments->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $score = $gradeBook[$student->id][$task->id] ?? null;
                            if($score !== null) { $total += $score; $count++; }
                        ?>
                        <td><?php echo e($score ?? '-'); ?></td>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <td style="font-weight: bold; background-color: #f9f9f9;">
                        <?php echo e($count > 0 ? round($total / $count, 0) : 0); ?>

                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <!-- TANDA TANGAN -->
    <div class="signature-section">
        <!-- Kiri: Mengetahui -->
        <div class="sign-box">
            <p>Mengetahui,<br>Kepala Sekolah</p>
            <div class="sign-space"></div>
            <p class="sign-name"><?php echo e($headmaster ?? 'TANTAN SUTANDI NUGRAHA, S.Si, M.Pd.'); ?></p>
            <p>NIP. <?php echo e($headmaster_nip ?? '-'); ?></p>
        </div>

        <!-- Tengah: Kosong (Spasi) -->
        <div class="sign-box"></div>

        <!-- Kanan: Guru -->
        <div class="sign-box">
            <p>Lakbok, <?php echo e(\Carbon\Carbon::now()->isoFormat('D MMMM Y')); ?><br>Guru Mata Pelajaran</p>
            <div class="sign-space"></div>
            <p class="sign-name"><?php echo e($teacher->name); ?></p>
            <p>NIP. <?php echo e($teacher->nip ?? '-'); ?></p>
        </div>
    </div>

    <div class="footer">
        Dicetak melalui Sistem Informasi Sekolah SMPN 3 Lakbok pada <?php echo e(date('d/m/Y H:i')); ?>

    </div>
</body>
</html><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/lms/grades/pdf.blade.php ENDPATH**/ ?>