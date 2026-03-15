<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Curriculum Vitae - <?php echo e($teacher->name); ?></title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #334155;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            font-size: 14px;
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 2px solid #3b82f6;
            margin-bottom: 25px;
        }
        .header h1 {
            margin: 0 0 5px 0;
            color: #1e40af;
            font-size: 28px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header p {
            margin: 2px 0;
            color: #64748b;
            font-size: 14px;
        }
        .bio-quotes {
            font-style: italic;
            color: #475569;
            background-color: #f8fafc;
            padding: 12px 15px;
            border-left: 4px solid #3b82f6;
            margin-bottom: 25px;
            border-radius: 4px;
        }
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        .section-title {
            font-size: 16px;
            color: #1e40af;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 5px;
            margin-bottom: 15px;
            text-transform: uppercase;
            font-weight: bold;
        }
        .item {
            margin-bottom: 12px;
        }
        .item-title {
            font-weight: bold;
            color: #1e293b;
            font-size: 15px;
        }
        .item-meta {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 3px;
        }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 5px 0; vertical-align: top; }
        .col-left { width: 25%; font-weight: bold; color: #475569; }
        .col-right { width: 75%; color: #0f172a; }
        .footer {
            margin-top: 50px;
            text-align: right;
            color: #94a3b8;
            font-size: 10px;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <div class="header">
        <?php
            $displayRole = $teacher->position;
            if (empty($displayRole)) {
                $decodedRoles = is_string($teacher->role) ? json_decode($teacher->role, true) : $teacher->role;
                $displayRole = is_array($decodedRoles) ? implode(', ', $decodedRoles) : $teacher->role;
            }
        ?>
        <h1><?php echo e($teacher->name); ?></h1>
        <p><strong><?php echo e($displayRole ?? 'Tenaga Pendidik'); ?></strong> | NIP: <?php echo e($teacher->nip ?? '-'); ?></p>
        <p><?php echo e($teacher->email); ?> <?php if($teacher->phone): ?> | <?php echo e($teacher->phone); ?> <?php endif; ?></p>
    </div>

    <?php if($teacher->bio): ?>
    <div class="bio-quotes">
        "<?php echo e($teacher->bio); ?>"
    </div>
    <?php endif; ?>

    <div class="section">
        <div class="section-title">Informasi Kepegawaian</div>
        <table>
            <tr>
                <td class="col-left">Nama Lengkap</td>
                <td class="col-right">: <?php echo e($teacher->name); ?></td>
            </tr>
            <tr>
                <td class="col-left">NIP</td>
                <td class="col-right">: <?php echo e($teacher->nip ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="col-left">Pangkat/Golongan</td>
                <td class="col-right">: <?php echo e($teacher->pangkat ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="col-left">Posisi/Jabatan</td>
                <td class="col-right">: <?php echo e($displayRole ?? '-'); ?></td>
            </tr>
        </table>
    </div>

    <?php if($teacher->experiences->count() > 0): ?>
    <div class="section">
        <div class="section-title">Pengalaman & Pelatihan</div>
        <?php $__currentLoopData = $teacher->experiences; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="item">
            <div class="item-title"><?php echo e($exp->title); ?></div>
            <div class="item-meta">Tahun <?php echo e($exp->year); ?> | Penyelenggara: <?php echo e($exp->organizer ?? '-'); ?></div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php endif; ?>

    <?php if($teacher->portfolios->count() > 0): ?>
    <div class="section">
        <div class="section-title">Prestasi & Pencapaian</div>
        <?php $__currentLoopData = $teacher->portfolios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $port): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="item">
            <div class="item-title"><?php echo e($port->title); ?></div>
            <div class="item-meta">Tahun <?php echo e($port->year); ?></div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php endif; ?>

    <?php if($teacher->articles->count() > 0): ?>
    <div class="section">
        <div class="section-title">Karya Tulis & Artikel</div>
        <?php $__currentLoopData = $teacher->articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $art): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="item">
            <div class="item-title"><?php echo e($art->title); ?></div>
            <div class="item-meta"><?php echo e($art->category ?? 'Umum'); ?> | Dipublikasikan: <?php echo e(\Carbon\Carbon::parse($art->published_at)->format('d F Y')); ?></div>
            <p style="font-size: 13px; margin: 3px 0 0 0; color: #475569;"><?php echo e($art->excerpt); ?></p>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php endif; ?>

    <div class="footer">
        Dicetak secara otomatis dari Sistem Portofolio SMP Negeri 3 Lakbok<br>
        Pada tanggal: <?php echo e(\Carbon\Carbon::now()->format('d F Y, H:i')); ?> WIB
    </div>

</body>
</html><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/teacher-cv.blade.php ENDPATH**/ ?>