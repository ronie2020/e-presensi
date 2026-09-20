<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>CV - {{ $teacher->name }}</title>
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
        
        /* Kolom Kiri (Sidebar Gelap - Elevate Dark & Cyan) */
        .sidebar {
            width: 35%;
            background-color: #021124; /* Elevate Dark */
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
            border: 4px solid #56bbf1; /* Elevate Accent */
        }
        .name {
            color: #56bbf1; /* Elevate Cyan */
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
            color: #ffffff;
        }
        .bio {
            text-align: center;
            font-size: 11px;
            line-height: 1.5;
            margin-bottom: 30px;
            color: #cbd5e1; /* slate-300 */
        }
        
        .sidebar-title {
            color: #ffffff;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 2px solid #56bbf1; /* Garis bawah cyan */
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
            color: #f1f5f9;
        }
        .sidebar-list li:before {
            content: "•";
            position: absolute;
            left: 0;
            color: #56bbf1; /* Bullet cyan */
        }
        .contact-info {
            font-size: 11px;
            line-height: 1.8;
            margin-bottom: 20px;
            color: #f1f5f9;
        }
        
        .personal-data table {
            width: 100%;
            font-size: 11px;
            line-height: 1.6;
        }
        .personal-data td {
            vertical-align: top;
            padding-bottom: 4px;
            color: #f1f5f9;
        }
        .pd-label {
            width: 40%;
            color: #94a3b8; /* slate-400 */
        }

        /* --- STYLING KONTEN (KANAN) --- */
        .content-title {
            color: #021124; /* Elevate Dark */
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 2px solid #56bbf1; /* Garis bawah cyan */
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
            color: #0d52a1; /* Elevate Primary */
            font-size: 12px;
        }
        .desc-col {
            width: 80%;
        }
        .desc-col h4 {
            margin: 0 0 3px 0;
            font-size: 14px;
            color: #021124; /* Elevate Dark */
        }
        .desc-col p {
            margin: 0;
            font-size: 12px;
            color: #475569; /* slate-600 */
            line-height: 1.5;
        }
    </style>
</head>
<body>

    @php
        // LOGIKA GAMBAR UNTUK DOMPDF (Ubah gambar ke Base64 agar pasti terbaca oleh PDF)
        $photoData = null;
        if($teacher->photo_path && file_exists(public_path('storage/' . $teacher->photo_path))) {
            $type = pathinfo(public_path('storage/' . $teacher->photo_path), PATHINFO_EXTENSION);
            $data = file_get_contents(public_path('storage/' . $teacher->photo_path));
            $photoData = 'data:image/' . $type . ';base64,' . base64_encode($data);
        } else {
            // Gambar default jika tidak ada foto (Elevate Dark background, teks cyan)
            $photoData = 'https://ui-avatars.com/api/?name='.urlencode($teacher->name).'&background=021124&color=56bbf1&size=300';
        }

        // Decode Role
        $displayRole = $teacher->position;
        if (empty($displayRole)) {
            $decodedRoles = is_string($teacher->role) ? json_decode($teacher->role, true) : $teacher->role;
            $displayRole = is_array($decodedRoles) ? implode(', ', $decodedRoles) : $teacher->role;
        }
    @endphp

    <table class="main-layout">
        <tr>
            <!-- ================= KOLOM KIRI (SIDEBAR) ================= -->
            <td class="sidebar">
                
                <div class="profile-img-container">
                    <img src="{{ $photoData }}" class="profile-img" alt="Foto Profil">
                </div>

                <div class="name">{{ $teacher->name }}</div>
                <div class="job-title">{{ $displayRole ?? 'Guru' }}</div>
                
                <div class="bio">
                    "{{ $teacher->bio ?? 'Terus belajar dan menginspirasi generasi bangsa.' }}"
                </div>

                <div class="sidebar-title">Keahlian</div>
                <ul class="sidebar-list">
                    @if(!empty($teacher->keahlian))
                        @foreach(array_map('trim', explode(',', $teacher->keahlian)) as $keahlian)
                            @if(!empty($keahlian))
                                <li>{{ $keahlian }}</li>
                            @endif
                        @endforeach
                    @else
                        <li style="color:#94a3b8; font-style:italic;">Belum ada data keahlian.</li>
                    @endif
                </ul>

                <div class="sidebar-title">Kontak</div>
                <div class="contact-info">
                    @if($teacher->phone)
                        <strong>Tlp/WA:</strong> <br>
                        {{ $teacher->phone }}<br><br>
                    @endif
                    <strong>Email:</strong> <br>
                    {{ $teacher->email }}<br>
                </div>

                <div class="sidebar-title">Hobi</div>
                <ul class="sidebar-list">
                    @if(!empty($teacher->hobi))
                        @foreach(array_map('trim', explode(',', $teacher->hobi)) as $hobi)
                            @if(!empty($hobi))
                                <li>{{ $hobi }}</li>
                            @endif
                        @endforeach
                    @else
                        <li style="color:#94a3b8; font-style:italic;">Belum ada data hobi.</li>
                    @endif
                </ul>

                <div class="sidebar-title">Data Pribadi</div>
                <div class="personal-data">
                    <table>
                        <tr>
                            <td class="pd-label">NIP</td>
                            <td>: {{ $teacher->nip ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="pd-label">Pangkat</td>
                            <td>: {{ $teacher->pangkat ?? '-' }}</td>
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
                @if($teacher->educations && $teacher->educations->count() > 0)
                    <table class="timeline-table">
                        @foreach($teacher->educations as $edu)
                        <tr>
                            <td class="time-col">{{ $edu->start_year ?? '-' }} - {{ $edu->end_year ?? 'Sekarang' }}</td>
                            <td class="desc-col">
                                <h4>{{ $edu->institution }}</h4>
                                <p>{{ $edu->degree }}</p>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                @else
                    <p style="color:#94a3b8; font-style:italic;">Belum ada riwayat pendidikan ditambahkan.</p>
                @endif

                <br>
                 <!-- PENGALAMAN & PELATIHAN -->
                <div class="content-title">Pengalaman & Pelatihan</div>
                @if($teacher->experiences->count() > 0)
                    <table class="timeline-table">
                        @foreach($teacher->experiences as $exp)
                        <tr>
                            <td class="time-col">{{ $exp->year ?? '-' }}</td>
                            <td class="desc-col">
                                <h4>{{ $exp->title }}</h4>
                                <p>
                                    {{ $exp->organizer ?? 'Instansi/Penyelenggara' }}
                                    {{-- TAMBAHAN: Indikator Sertifikat di PDF (Warna dirubah ke Cyan) --}}
                                    @if(!empty($exp->certificate_path))
                                        <span style="color:#06b6d4; font-size:10px; font-weight:bold; margin-left: 5px;">[Tersedia Sertifikat]</span>
                                    @endif
                                </p>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                @else
                    <p style="color:#94a3b8; font-style:italic;">Belum ada riwayat pengalaman ditambahkan.</p>
                @endif

                <br>

                <!-- KARYA & ARTIKEL (Sebagai Tambahan Portofolio) -->
                <div class="content-title">Karya Tulis & Artikel</div>
                @if($teacher->articles->count() > 0)
                    <table class="timeline-table">
                        @foreach($teacher->articles as $art)
                        <tr>
                            <td class="time-col">{{ \Carbon\Carbon::parse($art->published_at)->format('Y') }}</td>
                            <td class="desc-col">
                                <h4>{{ $art->title }}</h4>
                                <p><strong>{{ $art->category ?? 'Umum' }}</strong> - {{ $art->excerpt }}</p>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                @else
                    <p style="color:#94a3b8; font-style:italic;">Belum ada karya tulis dipublikasikan.</p>
                @endif

                <br>

                <!-- PRESTASI / PENGHARGAAN -->
                @if($teacher->portfolios->count() > 0)
                <div class="content-title">Prestasi & Penghargaan</div>
                    <table class="timeline-table">
                        @foreach($teacher->portfolios as $port)
                        <tr>
                            <td class="time-col">{{ $port->year ?? '-' }}</td>
                            <td class="desc-col">
                                <h4>{{ $port->title }}</h4>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                @endif

            </td>
        </tr>
    </table>

</body>
</html>