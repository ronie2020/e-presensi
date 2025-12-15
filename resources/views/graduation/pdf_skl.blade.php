<!DOCTYPE html>
<html>
<head>
    <title>Surat Keterangan Lulus - {{ $student->name }}</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; line-height: 1.5; color: #000; }
        .header { text-align: center; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px; }
        .logo { width: 80px; height: auto; position: absolute; left: 0; top: 0; }
        .title { font-size: 14pt; font-weight: bold; text-decoration: underline; text-align: center; margin-bottom: 5px; margin-top: 0; }
        .subtitle { text-align: center; margin-top: 0; margin-bottom: 30px; }
        .content { margin: 0 40px; }
        .table-data { width: 100%; margin-top: 10px; margin-bottom: 20px; }
        .table-data td { padding: 5px; vertical-align: top; }
        .label { width: 180px; }
        .colon { width: 10px; text-align: center; }
        .signature { margin-top: 50px; float: right; width: 250px; text-align: center; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 9pt; font-style: italic; color: #555; }
    </style>
</head>
<body>
    <div class="header">
        <!-- Ganti path logo sesuai kebutuhan, gunakan absolute path untuk dompdf -->
        <!-- <img src="{{ public_path('images/logo.png') }}" class="logo" alt="Logo"> -->
        <div style="margin-left: 20px;">
            <h3 style="margin: 0; font-size: 16pt;">PEMERINTAH KABUPATEN CIAMIS</h3>
            <h2 style="margin: 0; font-size: 18pt;">DINAS PENDIDIKAN</h2>
            <h1 style="margin: 0; font-size: 20pt;">SMP NEGERI 3 LAKBOK</h1>
            <p style="margin: 0; font-size: 10pt;">Alamat: Jl. Mekarjaya No. 199, Desa Sidaharja, Kec. Lakbok, Kab. Ciamis 46385</p>
        </div>
    </div>

    <h3 class="title">SURAT KETERANGAN KELULUSAN</h3>
    <p class="subtitle">Nomor: {{ $student->graduation->skl_number ?? '421.3/     /SMP.03/2024' }}</p>

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
            Berdasarkan hasil Rapat Pleno Dewan Guru tentang Kelulusan Peserta Didik Tahun Pelajaran {{ $student->graduation->academic_year ?? date('Y').'/'.(date('Y')+1) }} yang dilaksanakan pada tanggal {{ \Carbon\Carbon::parse($student->graduation->announcement_date)->isoFormat('D MMMM Y') }}, maka peserta didik tersebut dinyatakan:
        </p>
        <div style="border: 2px solid #000; padding: 15px; margin: 20px 0; text-align: center;">
            <h1 style="margin: 0; font-size: 24pt; font-weight: bold;">L U L U S</h1>
        </div>
        <p style="text-align: justify;">
            Surat Keterangan ini bersifat sementara sampai diterbitkannya Ijazah asli. Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.
        </p>
        <div class="signature">
            <p>Lakbok, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</p>
            <p>Kepala Sekolah,</p>
            <br><br><br>
            <p style="font-weight: bold; text-decoration: underline;">TANTAN SUTANDI NUGRAHA, S.Si, M.Pd.</p>
            <p>NIP. 197xxxxxx...</p>
        </div>
    </div>

    <div class="footer">
        Dicetak melalui Sistem Informasi Sekolah SMPN 3 Lakbok pada {{ now() }}
    </div>
</body>
</html>