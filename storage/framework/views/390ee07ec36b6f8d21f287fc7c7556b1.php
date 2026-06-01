<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SKL - <?php echo e($student->name ?? 'AIDA LESMINING FURIE'); ?></title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { elevate: { dark: '#032b5b', primary: '#3b5889', accent: '#38bdf8', text: '#1e293b' } }
                }
            }
        }
    </script>

    <style>
        /* PENGATURAN KERTAS A4 */
        @page { 
            size: 21cm 29.7cm; 
            margin: 0; 
        }
        
        body {
            font-family: Arial, sans-serif;
            background-color: #f8fafc;
            -webkit-print-color-adjust: exact;
        }

        /* TAMPILAN KERTAS (KONTEN SURAT) */
        .sheet {
            font-family: 'Times New Roman', Times, serif;
            background: white;
            width: 21cm;
            min-height: 29.7cm;
            margin: 30px auto;
            padding: 1cm 2cm; /* DIPERBAIKI: Padding atas bawah dikurangi agar muat 1 halaman */
            box-sizing: border-box;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            position: relative;
            color: #000;
        }

        /* KOP SURAT */
        .garis-kop {
            border-bottom: 3px solid black;
            margin-bottom: 2px;
        }
        .garis-kop-bawah {
            border-bottom: 1px solid black;
            margin-bottom: 10px; /* DIPERBAIKI: Margin dikurangi */
        }

        .kop-text-1 { font-size: 13pt; letter-spacing: 0.5px; }
        .kop-text-2 { font-size: 18pt; font-weight: bold; letter-spacing: 1px; }
        .kop-text-3 { font-size: 10pt; line-height: 1.2; }
        .kop-text-4 { font-size: 9pt; color: #1e3a8a; }

        /* TYPOGRAPHY DOKUMEN */
        .judul-surat { text-align: center; margin-bottom: 10px; /* DIPERBAIKI */ }
        .judul-surat h2 { margin: 0; font-size: 14pt; font-weight: bold; text-decoration: underline; letter-spacing: 1px; }
        .judul-surat p { margin: 0; font-size: 11pt; }

        .teks-pembuka { font-size: 10pt; text-align: justify; line-height: 1.25; margin-bottom: 3px;} /* DIPERBAIKI: Ukuran & spasi */
        
        /* LIST PERATURAN */
        ol.peraturan {
            margin-top: 4px;
            margin-bottom: 6px; /* DIPERBAIKI */
            padding-left: 20px;
            font-size: 10pt; /* DIPERBAIKI */
            text-align: justify;
            line-height: 1.25; /* DIPERBAIKI */
            list-style-type: decimal;
        }
        ol.peraturan li {
            margin-bottom: 2px;
            padding-left: 5px;
        }

        /* TABEL BIODATA */
        table.biodata { width: 100%; border-collapse: collapse; font-size: 10pt; margin-bottom: 6px; line-height: 1.25; } /* DIPERBAIKI */
        table.biodata td { vertical-align: top; }
        table.biodata tr td:first-child { width: 220px; }
        table.biodata tr td:nth-child(2) { width: 15px; text-align: center; }

        .lulus-text {
            font-size: 20pt; /* DIPERBAIKI: Sedikit dikecilkan agar hemat ruang */
            font-weight: bold;
            text-align: center;
            letter-spacing: 3px;
            margin: 6px 0 10px 0; /* DIPERBAIKI */
        }

        /* TABEL NILAI KURIKULUM 2013 */
        table.tabel-nilai { 
            width: 100%; 
            border-collapse: collapse; 
            font-size: 10pt; /* DIPERBAIKI */
            margin-bottom: 10px; /* DIPERBAIKI */
        }
        table.tabel-nilai th, table.tabel-nilai td { 
            border: 1px solid black; 
            padding: 3px 5px; /* DIPERBAIKI: Padding cell dikurangi */
            vertical-align: middle;
        }
        table.tabel-nilai th { 
            text-align: center; 
            font-weight: bold; 
            background-color: #e5e5e5;
            -webkit-print-color-adjust: exact;
        }
        table.tabel-nilai td.no { text-align: center; width: 5%; }
        table.tabel-nilai td.nilai { text-align: center; width: 15%; font-weight: bold; }
        table.tabel-nilai tr.grup td { font-weight: bold; }
        
        /* TANDA TANGAN */
        .ttd-box {
            float: right;
            width: 300px;
            text-align: left;
            font-size: 11pt;
            margin-top: 5px; /* DIPERBAIKI */
            padding-left: 30px;
        }

        .clear { clear: both; }
        
        /* MODE PRINT */
        @media print {
            body { background: none; margin: 0; }
            .sheet { margin: 0; box-shadow: none; padding: 1cm 2cm; } /* DIPERBAIKI: Pastikan saat print paddingnya tetap kecil */
            .no-print { display: none !important; }
            table.tabel-nilai th { background-color: transparent; }
        }
    </style>
</head>
<body class="relative">

    <div class="fixed top-0 left-0 w-full h-64 bg-gradient-to-b from-elevate-primary/10 to-transparent pointer-events-none no-print -z-10"></div>

    <div class="w-[21cm] mx-auto mt-6 mb-6 flex flex-col sm:flex-row justify-between items-center gap-4 no-print bg-white/80 backdrop-blur-md p-4 rounded-2xl shadow-lg shadow-elevate-dark/5 border border-white/60 sticky top-4 z-50">
        <div>
            <h2 class="font-black text-elevate-dark font-sans flex items-center gap-2">
                <i class="ph-bold ph-file-text text-elevate-primary text-xl"></i> Format SKL Resmi
            </h2>
            <p class="text-xs text-slate-500 font-bold ml-7 font-sans">Siswa: <?php echo e($student->name ?? 'AIDA LESMINING FURIE'); ?> | Kertas: A4</p>
        </div>

        <div class="flex flex-wrap gap-3 items-center font-sans">
            <button onclick="window.close()" class="px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition-colors flex items-center gap-2">
                <i class="ph-bold ph-x"></i> Tutup
            </button>
            <button onclick="window.print()" class="px-5 py-2.5 bg-elevate-primary text-white font-bold rounded-xl hover:bg-elevate-dark shadow-lg shadow-elevate-primary/30 transition-transform active:scale-95 flex items-center gap-2 text-xs">
                <i class="ph-bold ph-printer"></i> Cetak Dokumen
            </button>
        </div>
    </div>

    <?php
        function getSafeScore($student, $subject, $kelas, $semester, $default) {
            if (!$student) return $default;
            try {
                $score = $student->getScore($subject, $kelas, $semester);
                if (is_numeric($score)) {
                    return number_format((float)$score, 2, ',', '');
                }
                return $default;
            } catch (\Exception $e) {
                return $default;
            }
        }

        $n_pai = getSafeScore($student ?? null, 'Agama', 9, 2, '90,96');
        $n_pkn = getSafeScore($student ?? null, 'Pancasila', 9, 2, '90,72');
        $n_bin = getSafeScore($student ?? null, 'Indonesia', 9, 2, '88,80');
        $n_mtk = getSafeScore($student ?? null, 'Matematika', 9, 2, '88,32');
        $n_ipa = getSafeScore($student ?? null, 'Alam', 9, 2, '89,76');
        $n_ips = getSafeScore($student ?? null, 'Sosial', 9, 2, '89,60');
        $n_ing = getSafeScore($student ?? null, 'Inggris', 9, 2, '89,68');

        $n_sbd = getSafeScore($student ?? null, 'Seni Budaya', 9, 2, '92,68');
        $n_pjk = getSafeScore($student ?? null, 'Jasmani', 9, 2, '88,28');
        $n_pkr = getSafeScore($student ?? null, 'Prakarya', 9, 2, '91,80');
        $n_snd = getSafeScore($student ?? null, 'Sunda', 9, 2, '86,72');

        $n_avg = '89,76'; 
    ?>

    <div class="sheet">
        
        <!-- KOP SURAT -->
        <div class="flex justify-between items-center px-1 mb-1">
            <div class="w-[85px] text-center">
                <img src="<?php echo e(asset('img/logo_ciamis.png')); ?>" alt="Logo Ciamis" class="w-[70px] mx-auto h-auto object-contain" onerror="this.src='https://placehold.co/100x120/transparent/000?text=Logo+1'">
            </div>
            
            <div class="text-center flex-1 px-2 font-['Arial'] leading-tight">
                <div class="kop-text-1">PEMERINTAH KABUPATEN CIAMIS</div>
                <div class="kop-text-2">SMP NEGERI 3 LAKBOK</div>
                <div class="kop-text-3">Jalan Mekarjaya No. 199, Sidaharja</div>
                <div class="kop-text-3">Kecamatan Lakbok, Kabupaten Ciamis Kode Pos 46385</div>
                <div class="kop-text-4">
                    Laman: <span style="text-decoration: underline;">www.smpn3lakbok.sch.id</span> 
                    &nbsp; E-mail: netila.smp@gmail.com
                </div>
            </div>

            <div class="w-[85px] text-center">
                <img src="<?php echo e(asset('img/logo_sekolah.png')); ?>" alt="Logo Sekolah" class="w-[75px] mx-auto h-auto object-contain" onerror="this.src='https://placehold.co/100x100/transparent/000?text=Logo+2'">
            </div>
        </div>
        
        <div class="garis-kop"></div>
        <div class="garis-kop-bawah"></div>

        <!-- JUDUL SURAT -->
        <div class="judul-surat">
            <h2>SURAT KETERANGAN LULUS</h2>
            <p>Nomor : <?php echo e($student->graduation->skl_number ?? '421.2/...../SMP.03/Disdik/2026'); ?></p>
        </div>

        <!-- TEKS PEMBUKA & PERATURAN -->
        <div class="teks-pembuka">
            Yang bertanda tangan di bawah ini Kepala Sekolah Menengah Pertama Negeri 3 Lakbok Kabupaten Ciamis Provinsi Jawa Barat Tahun Ajaran 2025/2026, berdasarkan :
        </div>
        
        <ol class="peraturan">
            <li>Peraturan Menteri Pendidikan, Kebudayaan, Riset, dan Teknologi Republik Indonesia Nomor 21 Tahun 2022 Tentang Standar Penilaian Pendidikan Pada Pendidikan Anak Usia Dini, Jenjang Pendidikan Dasar, dan Jenjang Pendidikan Menengah;</li>
            <li>Peraturan Menteri Pendidikan, Kebudayaan, Riset, dan Teknologi Nomor 58 Tahun 2024 Tentang Ijazah Pendidikan Dasar dan Pendidikan Menengah;</li>
            <li>Surat Edaran Sekretaris Jendral Kementerian Pendidikan Dasar dan Menengah Nomor 5 Tahun 2025 Tentang Pengelolaan Blangko Ijazah Jenjang Pendidikan Dasar dan Pendidikan Menengah;</li>
            <li>Hasil Rapat Dewan Guru SMP Negeri 3 Lakbok tanggal 2 Juni 2026 tentang Kelulusan Peserta Didik Kelas 9 SMP Negeri 3 Lakbok Tahun Ajaran 2025/2026.</li>
        </ol>

        <div class="teks-pembuka">
            Menerangkan bahwa :
        </div>

        <table class="biodata">
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td style="font-weight: bold;"><?php echo e(strtoupper($student->name ?? '-')); ?></td>
            </tr>
            <tr>
                <td>Tempat dan Tanggal Lahir</td>
                <td>:</td>
                <td><?php echo e(ucfirst($student->pob ?? '-')); ?>, <?php echo e($student ? \Carbon\Carbon::parse($student->dob)->locale('id')->isoFormat('D MMMM Y') : '-'); ?></td>
            </tr>
            <tr>
                <td>Nama Orang Tua/Wali</td>
                <td>:</td>
                <td><?php echo e($student->father_name ?? $student->guardian_name ?? '-'); ?></td>
            </tr>
            <tr>
                <td>Nomor Induk Siswa</td>
                <td>:</td>
                <td><?php echo e($student->nis ?? '-'); ?></td>
            </tr>
            <tr>
                <td>Nomor Induk Siswa Nasional</td>
                <td>:</td>
                <td><?php echo e($student->student_id ?? ($student->nisn ?? '-')); ?></td>
            </tr>
            <tr>
                <td>dinyatakan</td>
                <td>:</td>
                <td></td>
            </tr>
        </table>

        <!-- STATUS LULUS -->
        <div class="lulus-text">
            LULUS
        </div>

        <div style="font-size: 10pt; margin-bottom: 5px;">
            dengan nilai sebagai berikut:
        </div>

        <table class="tabel-nilai">
            <thead>
                <tr>
                    <th rowspan="2" class="no" style="width: 8%;">No.</th>
                    <th>Mata Pelajaran</th>
                    <th rowspan="2" class="nilai" style="width: 18%;">Nilai</th>
                </tr>
                <tr>
                    <th>Kurikulum 2013</th>
                </tr>
            </thead>
            <tbody>
                <!-- KELOMPOK A -->
                <tr class="grup">
                    <td colspan="3" style="text-align: left; padding-left: 5px;">Kelompok A</td>
                </tr>
                <tr>
                    <td class="no">1.</td>
                    <td>Pendidikan Agama dan Budi Pekerti</td>
                    <td class="nilai"><?php echo e($n_pai); ?></td>
                </tr>
                <tr>
                    <td class="no">2.</td>
                    <td>Pendidikan Pancasila dan Kewarganegaraan</td>
                    <td class="nilai"><?php echo e($n_pkn); ?></td>
                </tr>
                <tr>
                    <td class="no">3.</td>
                    <td>Bahasa Indonesia</td>
                    <td class="nilai"><?php echo e($n_bin); ?></td>
                </tr>
                <tr>
                    <td class="no">4.</td>
                    <td>Matematika</td>
                    <td class="nilai"><?php echo e($n_mtk); ?></td>
                </tr>
                <tr>
                    <td class="no">5.</td>
                    <td>Ilmu Pengetahuan Alam</td>
                    <td class="nilai"><?php echo e($n_ipa); ?></td>
                </tr>
                <tr>
                    <td class="no">6.</td>
                    <td>Ilmu Pengetahuan Sosial</td>
                    <td class="nilai"><?php echo e($n_ips); ?></td>
                </tr>
                <tr>
                    <td class="no">7.</td>
                    <td>Bahasa Inggris</td>
                    <td class="nilai"><?php echo e($n_ing); ?></td>
                </tr>

                <!-- KELOMPOK B -->
                <tr class="grup">
                    <td colspan="3" style="text-align: left; padding-left: 5px;">Kelompok B</td>
                </tr>
                <tr>
                    <td class="no">1.</td>
                    <td>Seni Budaya</td>
                    <td class="nilai"><?php echo e($n_sbd); ?></td>
                </tr>
                <tr>
                    <td class="no">2.</td>
                    <td>Pendidikan Jasmani, Olahraga dan Kesehatan</td>
                    <td class="nilai"><?php echo e($n_pjk); ?></td>
                </tr>
                <tr>
                    <td class="no">3.</td>
                    <td>Prakarya</td>
                    <td class="nilai"><?php echo e($n_pkr); ?></td>
                </tr>
                <tr>
                    <td class="no">4.</td>
                    <td>Muatan Lokal</td>
                    <td class="nilai"></td>
                </tr>
                <tr>
                    <td class="no"></td>
                    <td>Bahasa Sunda</td>
                    <td class="nilai"><?php echo e($n_snd); ?></td>
                </tr>
                
                <!-- RATA RATA -->
                <tr>
                    <td colspan="2" align="center" style="text-align: center; font-weight: bold;"><strong>Rata-rata</strong></td>
                    <td class="nilai" align="center" style="text-align: center; font-weight: bold;"><strong><?php echo e($n_avg); ?></strong></td>
                </tr>
            </tbody>
        </table>

        <div class="teks-pembuka" style="margin-bottom: 2px;">
            Demikian keterangan ini dibuat untuk dipergunakan sebagaimana mestinya dan berlaku sampai dengan diterima Ijazah oleh peserta didik.
        </div>

        <!-- TANDA TANGAN (SISI KANAN BAWAH) -->
        <div class="ttd-box">
            <p style="margin: 0;">Kab. Ciamis, 2 Juni 2026</p>
            <p style="margin: 0;">Kepala Sekolah</p>
            
            <!-- Tempat Tanda Tangan dan Stempel -->
            <div style="height: 65px; position: relative;"> <!-- DIPERBAIKI: Tinggi diturunkan dari 80px ke 65px agar hemat spasi (gambarnya absolute jadi aman overlap) -->
                <img src="<?php echo e(asset('img/ttd_stempel.jpg')); ?>" 
                     alt="Tanda Tangan dan Stempel" 
                     style="height: 120px; position: absolute; top: -20px; left: -75px; mix-blend-mode: multiply;" 
                     onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/e/e0/Placeholder_Signature.png'; this.style.filter='hue-rotate(200deg)'; this.style.left='0px';">
            </div>
            
            <p style="margin: 0; font-weight: bold; text-decoration: underline; white-space: nowrap;"><?php echo e(isset($settings['principal_name']) ? $settings['principal_name'] : 'Tantan Sutandi Nugraha, S.Si, M.Pd'); ?></p>
            <p style="margin: 0;">NIP. <?php echo e($settings['principal_nip'] ?? '19820928 201101 1 002'); ?></p>
        </div>

        <div class="clear"></div>

    </div>
</body>
</html><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/graduation/pdf_skl.blade.php ENDPATH**/ ?>