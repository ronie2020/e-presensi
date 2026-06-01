<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SKL - {{ $student->name ?? 'AIDA LESMINING FURIE' }}</title>
    
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
        /* PENGATURAN KERTAS F4 (Folio) */
        @page { 
            size: 21.5cm 33cm; 
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
            width: 21.5cm; /* Lebar F4 */
            margin: 30px auto;
            padding: 0.5cm 1.5cm; 
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
            margin-bottom: 5px;
        }

        /* PENYESUAIAN FONT KOP SURAT */
        .kop-text-1 { font-size: 12pt; letter-spacing: 0.5px; } /* Dari 13pt -> 12pt */
        .kop-text-2 { font-size: 16pt; font-weight: bold; letter-spacing: 1px; } /* Dari 18pt -> 16pt */
        .kop-text-3 { font-size: 9pt; line-height: 1.15; } /* Dari 10pt -> 9pt */
        .kop-text-4 { font-size: 8.5pt; color: #1e3a8a; line-height: 1.15; } /* Dari 9pt -> 8.5pt */

        /* TYPOGRAPHY DOKUMEN */
        .judul-surat { text-align: center; margin-bottom: 5px; }
        .judul-surat h2 { margin: 0; font-size: 13pt; font-weight: bold; text-decoration: underline; letter-spacing: 1px; } /* Dari 14pt -> 13pt */
        .judul-surat p { margin: 0; font-size: 10pt; } /* Dari 11pt -> 10pt */

        /* PENYESUAIAN FONT TEKS */
        .teks-pembuka { font-size: 9.5pt; text-align: justify; line-height: 1.2; margin-bottom: 3px;} /* Dari 10pt -> 9.5pt */
        
        /* LIST PERATURAN */
        ol.peraturan {
            margin-top: 3px;
            margin-bottom: 5px; 
            padding-left: 25px; 
            font-size: 9.5pt; /* Dari 10pt -> 9.5pt */
            text-align: justify;
            line-height: 1.2; 
            list-style-type: decimal;
        }
        ol.peraturan li {
            margin-bottom: 2px;
            padding-left: 5px;
        }

        /* TABEL BIODATA */
        table.biodata { width: 100%; border-collapse: collapse; font-size: 9.5pt; margin-bottom: 5px; line-height: 1.2; } /* Dari 10pt -> 9.5pt */
        table.biodata td { vertical-align: top; padding-bottom: 2px; }
        table.biodata tr td:first-child { width: 220px; }
        table.biodata tr td:nth-child(2) { width: 15px; text-align: center; }

        .lulus-text {
            font-size: 16pt; /* Dari 20pt/18pt -> 16pt */
            font-weight: bold;
            text-align: center;
            letter-spacing: 4px;
            margin: 5px 0 5px 0;
        }

        /* TABEL NILAI KURIKULUM 2013 */
        table.tabel-nilai { 
            width: 100%; 
            border-collapse: collapse; 
            font-size: 9.5pt; /* Dari 10pt -> 9.5pt */
            margin-bottom: 5px; 
        }
        table.tabel-nilai th, table.tabel-nilai td { 
            border: 1px solid black; 
            padding: 3px 5px; /* Padding sedikit dirapatkan */
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
            font-size: 10pt; /* Dari 11pt -> 10pt */
            margin-top: 5px; 
            padding-left: 30px;
        }

        .clear { clear: both; }
        
        /* MODE PRINT */
        @media print {
            body { background: none; margin: 0; }
            .sheet { 
                width: 21.5cm; 
                margin: 0; 
                box-shadow: none; 
                padding: 0.5cm 1.5cm; 
                page-break-after: avoid; 
            } 
            .no-print { display: none !important; }
            table.tabel-nilai th { background-color: transparent; }
        }
    </style>
</head>
<body class="relative">

    <div class="fixed top-0 left-0 w-full h-64 bg-gradient-to-b from-elevate-primary/10 to-transparent pointer-events-none no-print -z-10"></div>

    <div class="w-[21.5cm] mx-auto mt-6 mb-6 flex flex-col sm:flex-row justify-between items-center gap-4 no-print bg-white/80 backdrop-blur-md p-4 rounded-2xl shadow-lg shadow-elevate-dark/5 border border-white/60 sticky top-4 z-50">
        <div>
            <h2 class="font-black text-elevate-dark font-sans flex items-center gap-2">
                <i class="ph-bold ph-file-text text-elevate-primary text-xl"></i> Format SKL Resmi
            </h2>
            <p class="text-xs text-slate-500 font-bold ml-7 font-sans">Siswa: {{ $student->name ?? 'AIDA LESMINING FURIE' }} | Kertas: F4 (Folio)</p>
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

    @php
        // Mengambil nilai dalam format angka (float) agar bisa dihitung rata-ratanya
        function getRawScore($student, $subject, $kelas, $semester, $default) {
            if (!$student) return (float)$default;
            try {
                $score = $student->getScore($subject, $kelas, $semester);
                if (is_numeric($score)) {
                    return (float)$score;
                }
                return (float)$default;
            } catch (\Exception $e) {
                return (float)$default;
            }
        }

        // Ambil nilai angka mentah (raw) dari database
        $r_pai = getRawScore($student ?? null, 'Agama', 9, 2, 90.96);
        $r_pkn = getRawScore($student ?? null, 'Pancasila', 9, 2, 90.72);
        $r_bin = getRawScore($student ?? null, 'Indonesia', 9, 2, 88.80);
        $r_mtk = getRawScore($student ?? null, 'Matematika', 9, 2, 88.32);
        $r_ipa = getRawScore($student ?? null, 'Alam', 9, 2, 89.76);
        $r_ips = getRawScore($student ?? null, 'Sosial', 9, 2, 89.60);
        $r_ing = getRawScore($student ?? null, 'Inggris', 9, 2, 89.68);
        $r_sbd = getRawScore($student ?? null, 'Seni Budaya', 9, 2, 92.68);
        $r_pjk = getRawScore($student ?? null, 'Jasmani', 9, 2, 88.28);
        $r_pkr = getRawScore($student ?? null, 'Prakarya', 9, 2, 91.80);
        $r_snd = getRawScore($student ?? null, 'Sunda', 9, 2, 86.72);

        // Hitung total dan rata-rata
        $total_score = $r_pai + $r_pkn + $r_bin + $r_mtk + $r_ipa + $r_ips + $r_ing + $r_sbd + $r_pjk + $r_pkr + $r_snd;
        $avg_score = $total_score / 11; // 11 adalah jumlah mata pelajaran

        // Format angka ke format Indonesia (koma) untuk ditampilkan di tabel
        $n_pai = number_format($r_pai, 2, ',', '');
        $n_pkn = number_format($r_pkn, 2, ',', '');
        $n_bin = number_format($r_bin, 2, ',', '');
        $n_mtk = number_format($r_mtk, 2, ',', '');
        $n_ipa = number_format($r_ipa, 2, ',', '');
        $n_ips = number_format($r_ips, 2, ',', '');
        $n_ing = number_format($r_ing, 2, ',', '');
        $n_sbd = number_format($r_sbd, 2, ',', '');
        $n_pjk = number_format($r_pjk, 2, ',', '');
        $n_pkr = number_format($r_pkr, 2, ',', '');
        $n_snd = number_format($r_snd, 2, ',', '');
        
        $n_avg = number_format($avg_score, 2, ',', '');
    @endphp

    <div class="sheet">
        
        <!-- KOP SURAT -->
        <div class="flex justify-between items-center px-1 mb-1">
            <div class="w-[85px] text-center">
                <img src="{{ asset('img/logo_ciamis.png') }}" alt="Logo Ciamis" class="w-[65px] mx-auto h-auto object-contain" onerror="this.src='https://placehold.co/100x120/transparent/000?text=Logo+1'">
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
                <img src="{{ asset('img/logo_sekolah.png') }}" alt="Logo Sekolah" class="w-[70px] mx-auto h-auto object-contain" onerror="this.src='https://placehold.co/100x100/transparent/000?text=Logo+2'">
            </div>
        </div>
        
        <div class="garis-kop"></div>
        <div class="garis-kop-bawah"></div>

        <!-- JUDUL SURAT -->
        <div class="judul-surat">
            <h2>SURAT KETERANGAN LULUS</h2>
            <p>Nomor : {{ $student->graduation->skl_number ?? '421.2/...../SMP.03/Disdik/2026' }}</p>
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
                <td style="font-weight: bold;">{{ strtoupper($student->name ?? 'AIDA LESMINING FURIE') }}</td>
            </tr>
            <tr>
                <td>Tempat dan Tanggal Lahir</td>
                <td>:</td>
                <td>{{ ucfirst($student->pob ?? 'Ciamis') }}, {{ $student ? \Carbon\Carbon::parse($student->dob)->locale('id')->isoFormat('D MMMM Y') : '23 December 2010' }}</td>
            </tr>
            <tr>
                <td>Nama Orang Tua/Wali</td>
                <td>:</td>
                <td>{{ $student->father_name ?? $student->guardian_name ?? 'Puji Lukman' }}</td>
            </tr>
            <tr>
                <td>Nomor Induk Siswa</td>
                <td>:</td>
                <td>{{ $student->nis ?? '23247002' }}</td>
            </tr>
            <tr>
                <td>Nomor Induk Siswa Nasional</td>
                <td>:</td>
                <td>{{ $student->student_id ?? ($student->nisn ?? '0103163305') }}</td>
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

        <div style="font-size: 9pt; margin-bottom: 3px;">
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
                    <td class="nilai">{{ $n_pai }}</td>
                </tr>
                <tr>
                    <td class="no">2.</td>
                    <td>Pendidikan Pancasila dan Kewarganegaraan</td>
                    <td class="nilai">{{ $n_pkn }}</td>
                </tr>
                <tr>
                    <td class="no">3.</td>
                    <td>Bahasa Indonesia</td>
                    <td class="nilai">{{ $n_bin }}</td>
                </tr>
                <tr>
                    <td class="no">4.</td>
                    <td>Matematika</td>
                    <td class="nilai">{{ $n_mtk }}</td>
                </tr>
                <tr>
                    <td class="no">5.</td>
                    <td>Ilmu Pengetahuan Alam</td>
                    <td class="nilai">{{ $n_ipa }}</td>
                </tr>
                <tr>
                    <td class="no">6.</td>
                    <td>Ilmu Pengetahuan Sosial</td>
                    <td class="nilai">{{ $n_ips }}</td>
                </tr>
                <tr>
                    <td class="no">7.</td>
                    <td>Bahasa Inggris</td>
                    <td class="nilai">{{ $n_ing }}</td>
                </tr>

                <!-- KELOMPOK B -->
                <tr class="grup">
                    <td colspan="3" style="text-align: left; padding-left: 5px;">Kelompok B</td>
                </tr>
                <tr>
                    <td class="no">1.</td>
                    <td>Seni Budaya</td>
                    <td class="nilai">{{ $n_sbd }}</td>
                </tr>
                <tr>
                    <td class="no">2.</td>
                    <td>Pendidikan Jasmani, Olahraga dan Kesehatan</td>
                    <td class="nilai">{{ $n_pjk }}</td>
                </tr>
                <tr>
                    <td class="no">3.</td>
                    <td>Prakarya</td>
                    <td class="nilai">{{ $n_pkr }}</td>
                </tr>
                <tr>
                    <td class="no">4.</td>
                    <td>Muatan Lokal</td>
                    <td class="nilai"></td>
                </tr>
                <tr>
                    <td class="no"></td>
                    <td>Bahasa Sunda</td>
                    <td class="nilai">{{ $n_snd }}</td>
                </tr>
                
                <!-- RATA RATA -->
                <tr>
                    <td colspan="2" align="center" style="text-align: center; font-weight: bold;"><strong>Rata-rata</strong></td>
                    <td class="nilai" align="center" style="text-align: center; font-weight: bold;"><strong>{{ $n_avg }}</strong></td>
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
            <!-- Ruang tanda tangan diregangkan sedikit lagi karena kita sudah hemat ruang berkat font kecil -->
            <div style="height: 55px; position: relative;"> 
                <img src="{{ asset('img/ttd_stempel.jpg') }}" 
                     alt="Tanda Tangan dan Stempel" 
                     style="height: 120px; position: absolute; top: -20px; left: -75px; mix-blend-mode: multiply;" 
                     onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/e/e0/Placeholder_Signature.png'; this.style.filter='hue-rotate(200deg)'; this.style.left='0px';">
            </div>
            
            <p style="margin: 0; font-weight: bold; text-decoration: underline; white-space: nowrap;">{{ isset($settings['principal_name']) ? $settings['principal_name'] : 'Tantan Sutandi Nugraha, S.Si, M.Pd' }}</p>
            <p style="margin: 0;">NIP. {{ $settings['principal_nip'] ?? '19820928 201101 1 002' }}</p>
        </div>

        <div class="clear"></div>

    </div>
</body>
</html>