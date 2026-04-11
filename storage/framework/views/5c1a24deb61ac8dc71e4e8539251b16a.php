<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>CV - <?php echo e($teacher->name); ?></title>
    <style>
        /* Reset Margin Page agar background full ke ujung kertas */
        @page {
            margin: 0px;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            font-size: 12px;
        }
        
        /* Layout Utama 2 Kolom menggunakan Table */
        .main-layout {
            width: 100%;
            height: 100%;
            border-collapse: collapse;
        }
        
        /* Kolom Kiri (Sidebar Gelap) */
        .sidebar {
            width: 35%;
            background-color: #2b3c4d; /* Warna biru gelap */
            color: #ffffff;
            vertical-align: top;
            padding: 40px 25px;
        }
        
        /* Kolom Kanan (Konten Putih) */
        .content {
            width: 65%;
            background-color: #ffffff;
            vertical-align: top;
            padding: 40px 30px;
        }

        /* --- STYLING SIDEBAR (KIRI) --- */
        .profile-img-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .profile-img {
            width: 150px;
            height: 150px;
            border-radius: 50%; /* Lingkaran */
            object-fit: cover;
            border: 4px solid #4a5c6e;
        }
        .name {
            color: #f39c12; /* Warna kuning/gold untuk nama */
            font-size: 22px;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            margin: 0 0 5px 0;
            line-height: 1.2;
        }
        .job-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin: 0 0 15px 0;
            letter-spacing: 1px;
        }
        .bio {
            text-align: center;
            font-size: 11px;
            line-height: 1.5;
            margin-bottom: 30px;
            color: #bdc3c7;
        }
        
        .sidebar-title {
            color: #ffffff;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 1px solid #4a5c6e;
            padding-bottom: 5px;
            margin-top: 25px;
            margin-bottom: 15px;
            letter-spacing: 1px;
        }
        .sidebar-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
            font-size: 11px;
            line-height: 1.6;
        }
        .sidebar-list li {
            margin-bottom: 8px;
            padding-left: 12px;
            position: relative;
        }
        .sidebar-list li:before {
            content: "•";
            position: absolute;
            left: 0;
            color: #f39c12;
        }
        .contact-info {
            font-size: 11px;
            line-height: 1.8;
            margin-bottom: 20px;
        }
        
        .personal-data table {
            width: 100%;
            font-size: 11px;
            line-height: 1.6;
        }
        .personal-data td {
            vertical-align: top;
            padding-bottom: 4px;
        }
        .pd-label {
            width: 40%;
            color: #bdc3c7;
        }

        /* --- STYLING KONTEN (KANAN) --- */
        .content-title {
            color: #2b3c4d;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 2px solid #f39c12;
            padding-bottom: 5px;
            margin-top: 0;
            margin-bottom: 15px;
        }
        
        /* Timeline Table untuk Pengalaman/Pendidikan */
        .timeline-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .timeline-table td {
            padding-bottom: 15px;
            vertical-align: top;
        }
        .time-col {
            width: 20%;
            font-weight: bold;
            color: #2b3c4d;
            font-size: 12px;
        }
        .desc-col {
            width: 80%;
        }
        .desc-col h4 {
            margin: 0 0 3px 0;
            font-size: 14px;
            color: #2b3c4d;
        }
        .desc-col p {
            margin: 0;
            font-size: 12px;
            color: #555;
            line-height: 1.5;
        }
    </style>
</head>
<body>

    <?php
        // LOGIKA GAMBAR UNTUK DOMPDF (Ubah gambar ke Base64 agar pasti terbaca oleh PDF)
        $photoData = null;
        if($teacher->photo_path && file_exists(public_path('storage/' . $teacher->photo_path))) {
            $type = pathinfo(public_path('storage/' . $teacher->photo_path), PATHINFO_EXTENSION);
            $data = file_get_contents(public_path('storage/' . $teacher->photo_path));
            $photoData = 'data:image/' . $type . ';base64,' . base64_encode($data);
        } else {
            // Gambar default jika tidak ada foto
            $photoData = 'https://ui-avatars.com/api/?name='.urlencode($teacher->name).'&background=2b3c4d&color=fff&size=300';
        }

        // Decode Role
        $displayRole = $teacher->position;
        if (empty($displayRole)) {
            $decodedRoles = is_string($teacher->role) ? json_decode($teacher->role, true) : $teacher->role;
            $displayRole = is_array($decodedRoles) ? implode(', ', $decodedRoles) : $teacher->role;
        }
    ?>

    <table class="main-layout">
        <tr>
            <!-- ================= KOLOM KIRI (SIDEBAR) ================= -->
            <td class="sidebar">
                
                <div class="profile-img-container">
                    <img src="<?php echo e($photoData); ?>" class="profile-img" alt="Foto Profil">
                </div>

                <div class="name"><?php echo e($teacher->name); ?></div>
                <div class="job-title"><?php echo e($displayRole ?? 'Guru'); ?></div>
                
                <div class="bio">
                    "<?php echo e($teacher->bio ?? 'Terus belajar dan menginspirasi generasi bangsa.'); ?>"
                </div>

                <div class="sidebar-title">Keahlian</div>
                <ul class="sidebar-list">
                    <?php if(!empty($teacher->keahlian)): ?>
                        <?php $__currentLoopData = array_map('trim', explode(',', $teacher->keahlian)); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keahlian): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if(!empty($keahlian)): ?>
                                <li><?php echo e($keahlian); ?></li>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <li style="color:#7f8c8d; font-style:italic;">Belum ada data keahlian.</li>
                    <?php endif; ?>
                </ul>

                <div class="sidebar-title">Kontak</div>
                <div class="contact-info">
                    <?php if($teacher->phone): ?>
                        <strong>Tlp/WA:</strong> <br>
                        <?php echo e($teacher->phone); ?><br><br>
                    <?php endif; ?>
                    <strong>Email:</strong> <br>
                    <?php echo e($teacher->email); ?><br>
                </div>

                <div class="sidebar-title">Hobi</div>
                <ul class="sidebar-list">
                    <?php if(!empty($teacher->hobi)): ?>
                        <?php $__currentLoopData = array_map('trim', explode(',', $teacher->hobi)); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hobi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if(!empty($hobi)): ?>
                                <li><?php echo e($hobi); ?></li>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <li style="color:#7f8c8d; font-style:italic;">Belum ada data hobi.</li>
                    <?php endif; ?>
                </ul>

                <div class="sidebar-title">Data Pribadi</div>
                <div class="personal-data">
                    <table>
                        <tr>
                            <td class="pd-label">NIP</td>
                            <td>: <?php echo e($teacher->nip ?? '-'); ?></td>
                        </tr>
                        <tr>
                            <td class="pd-label">Pangkat</td>
                            <td>: <?php echo e($teacher->pangkat ?? '-'); ?></td>
                        </tr>
                        <tr>
                            <td class="pd-label">Status</td>
                            <td>: Aktif / Pegawai</td>
                        </tr>
                    </table>
                </div>

            </td>

            <!-- ================= KOLOM KANAN (KONTEN) ================= -->
            <td class="content">

                <!-- PENDIDIKAN -->
                <div class="content-title">Pendidikan</div>
                <?php if($teacher->educations && $teacher->educations->count() > 0): ?>
                    <table class="timeline-table">
                        <?php $__currentLoopData = $teacher->educations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $edu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="time-col"><?php echo e($edu->start_year ?? '-'); ?> - <?php echo e($edu->end_year ?? 'Sekarang'); ?></td>
                            <td class="desc-col">
                                <h4><?php echo e($edu->institution); ?></h4>
                                <p><?php echo e($edu->degree); ?></p>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </table>
                <?php else: ?>
                    <p style="color:#7f8c8d; font-style:italic;">Belum ada riwayat pendidikan ditambahkan.</p>
                <?php endif; ?>

                <br>
                 <!-- PENGALAMAN & PELATIHAN -->
                <div class="content-title">Pengalaman & Pelatihan</div>
                <?php if($teacher->experiences->count() > 0): ?>
                    <table class="timeline-table">
                        <?php $__currentLoopData = $teacher->experiences; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="time-col"><?php echo e($exp->year ?? '-'); ?></td>
                            <td class="desc-col">
                                <h4><?php echo e($exp->title); ?></h4>
                                <p>
                                    <?php echo e($exp->organizer ?? 'Instansi/Penyelenggara'); ?>

                                    
                                    <?php if(!empty($exp->certificate_path)): ?>
                                        <span style="color:#3498db; font-size:10px; font-weight:bold; margin-left: 5px;">[Tersedia Sertifikat]</span>
                                    <?php endif; ?>
                                </p>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </table>
                <?php else: ?>
                    <p style="color:#7f8c8d; font-style:italic;">Belum ada riwayat pengalaman ditambahkan.</p>
                <?php endif; ?>

                <br>

                <!-- KARYA & ARTIKEL (Sebagai Tambahan Portofolio) -->
                <div class="content-title">Karya Tulis & Artikel</div>
                <?php if($teacher->articles->count() > 0): ?>
                    <table class="timeline-table">
                        <?php $__currentLoopData = $teacher->articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $art): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="time-col"><?php echo e(\Carbon\Carbon::parse($art->published_at)->format('Y')); ?></td>
                            <td class="desc-col">
                                <h4><?php echo e($art->title); ?></h4>
                                <p><strong><?php echo e($art->category ?? 'Umum'); ?></strong> - <?php echo e($art->excerpt); ?></p>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </table>
                <?php else: ?>
                    <p style="color:#7f8c8d; font-style:italic;">Belum ada karya tulis dipublikasikan.</p>
                <?php endif; ?>

                <br>

                <!-- PRESTASI / PENGHARGAAN -->
                <?php if($teacher->portfolios->count() > 0): ?>
                <div class="content-title">Prestasi & Penghargaan</div>
                    <table class="timeline-table">
                        <?php $__currentLoopData = $teacher->portfolios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $port): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="time-col"><?php echo e($port->year ?? '-'); ?></td>
                            <td class="desc-col">
                                <h4><?php echo e($port->title); ?></h4>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </table>
                <?php endif; ?>

            </td>
        </tr>
    </table>

</body>
</html><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\teacher-cv.blade.php ENDPATH**/ ?>