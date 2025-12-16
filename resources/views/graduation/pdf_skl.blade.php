<!DOCTYPE html>
<html>
<head>
    <title>Surat Keterangan Lulus - {{ $student->name }}</title>
    <style>
        /* PENTING: Mengatur margin halaman agar muat 1 lembar */
        @page { margin: 2cm 2.5cm 1.5cm 2.5cm; }

        body { 
            font-family: 'Times New Roman', Times, serif; 
            font-size: 11pt; /* Diperkecil sedikit agar muat */
            line-height: 1.3; /* Spasi baris dirapatkan */
            color: #000; 
        }

        /* HEADER */
        .header { 
            text-align: center; 
            border-bottom: 3px double #000; 
            padding-bottom: 8px; 
            margin-bottom: 15px; /* Jarak ke judul dikurangi */
        }
        .logo { width: 75px; height: auto; position: absolute; left: 0; top: 0; }
        
        .header h1 { margin: 0; font-size: 18pt; }
        .header h2 { margin: 0; font-size: 16pt; }
        .header h3 { margin: 0; font-size: 14pt; }
        .header p { margin: 0; font-size: 9pt; }

        /* JUDUL SURAT */
        .title { 
            font-size: 14pt; 
            font-weight: bold; 
            text-decoration: underline; 
            text-align: center; 
            margin-bottom: 2px; 
            margin-top: 0; 
        }
        .subtitle { 
            text-align: center; 
            margin-top: 0; 
            margin-bottom: 20px; 
            font-size: 11pt; 
        }

        /* KONTEN */
        .content { margin: 0 10px; } /* Margin samping konten dikurangi */
        
        .table-data { width: 100%; margin-top: 5px; margin-bottom: 10px; }
        .table-data td { padding: 3px 5px; vertical-align: top; }
        .label { width: 170px; }
        .colon { width: 10px; text-align: center; }

        /* KOTAK LULUS */
        .status-box {
            border: 2px solid #000;
            padding: 10px;
            margin: 15px 0; /* Jarak atas bawah dikurangi */
            text-align: center;
        }

        /* TANDA TANGAN */
        .signature { 
            margin-top: 30px; /* Jarak dari teks penutup dikurangi */
            float: right; 
            width: 250px; 
            text-align: center; 
        }

        /* FOOTER KECIL */
        .footer { 
            position: fixed; 
            bottom: 0; 
            width: 100%; 
            text-align: center; 
            font-size: 8pt; 
            font-style: italic; 
            color: #888; 
        }
    </style>
</head>
<body>
    <div class="header">
        <!-- Logo opsional, uncomment jika diperlukan -->
        <!-- <img src="{{ public_path('images/logo.png') }}" class="logo" alt="Logo"> -->
        
        <!-- Gunakan margin-left jika ada logo, jika tidak 0 saja -->
        <div style="margin-left: 0;"> 
            <h3 style="margin: 0;">PEMERINTAH KABUPATEN CIAMIS</h3>
            <h2 style="margin: 0;">DINAS PENDIDIKAN</h2>
            <h1 style="margin: 0;">SMP NEGERI 3 LAKBOK</h1>
            <p>Alamat: Jl. Mekarjaya No. 199, Desa Sidaharja, Kec. Lakbok, Kab. Ciamis 46385</p>
        </div>
    </div>

    <h3 class="title">SURAT KETERANGAN KELULUSAN</h3>
    <!-- Nomor SKL dinamis atau default -->
    <p class="subtitle">Nomor: {{ $student->graduation->skl_number ?? '421.3/     /SMP.03/' . date('Y') }}</p>

    <div class="content">
        <p>Yang bertanda tangan di bawah ini, Kepala SMP Negeri 3 Lakbok, Kabupaten Ciamis, menerangkan bahwa:</p>

        <table class="table-data">
            <tr>
                <td class="label">Nama Peserta Didik</td>
                <td class="colon">:</td>
                <td style="font-weight: bold; text-transform: uppercase;">{{ $student->name }}</td>
            </tr>
            <tr>
                <td class="label">Tempat, Tanggal Lahir</td>
                <td class="colon">:</td>
                <td>{{ $student->pob }}, {{ \Carbon\Carbon::parse($student->dob)->isoFormat('D MMMM Y') }}</td>
            </tr>
            <tr>
                <td class="label">Nomor Induk Siswa</td>
                <td class="colon">:</td>
                <td>{{ $student->nis ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">NISN</td>
                <td class="colon">:</td>
                <td>{{ $student->student_id }}</td>
            </tr>
            <tr>
                <td class="label">Asal Sekolah</td>
                <td class="colon">:</td>
                <td>SMP NEGERI 3 LAKBOK</td>
            </tr>
        </table>

        <p style="text-align: justify;">
            Berdasarkan hasil Rapat Pleno Dewan Guru tentang Kelulusan Peserta Didik Tahun Pelajaran {{ $student->graduation->academic_year ?? date('Y').'/'.(date('Y')+1) }} yang dilaksanakan pada tanggal {{ \Carbon\Carbon::parse($student->graduation->announcement_date ?? now())->isoFormat('D MMMM Y') }}, maka peserta didik tersebut dinyatakan:
        </p>

        <div class="status-box">
            <h1 style="margin: 0; font-size: 22pt; font-weight: bold; letter-spacing: 3px;">L U L U S</h1>
        </div>

        <p style="text-align: justify;">
            Surat Keterangan ini bersifat sementara sampai diterbitkannya Ijazah asli. Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.
        </p>

        <div class="signature">
            <p>Lakbok, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</p>
            <p>Kepala Sekolah,</p>
            <!-- Jumlah <br> dikurangi agar tidak memakan tempat vertikal terlalu banyak -->
            <br><br><br>
            <p style="font-weight: bold; text-decoration: underline;">TANTAN SUTANDI NUGRAHA, S.Si, M.Pd.</p>
            <p>NIP. 197xxxxxx...</p>
        </div>
    </div>

    <!-- Footer kecil di bawah -->
    <div class="footer">
        Dicetak melalui Sistem Informasi Sekolah SMPN 3 Lakbok
    </div>
</body>
</html>