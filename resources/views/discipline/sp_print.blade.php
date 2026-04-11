<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Peringatan - {{ $student->name }}</title>
    <style>
        /* PENGATURAN KERTAS A4 PORTRAIT */
        @page { 
            size: A4 portrait; 
            margin: 0; 
        }
        
        body { 
            font-family: 'Times New Roman', serif; 
            background-color: #f1f5f9; 
            margin: 0; 
            padding: 0;
            display: flex;
            justify-content: center;
        }

        .no-print { 
            position: fixed; 
            top: 20px; 
            right: 20px; 
            z-index: 1000; 
        }

        @media print { 
            .no-print { display: none !important; } 
            body { background: none; }
            .page-container { margin: 0; border: none; box-shadow: none; }
        }

        /* CONTAINER UTAMA DENGAN BORDER DEKORATIF */
        .page-container {
            background: white;
            width: 21cm;
            min-height: 29.7cm;
            margin: 20px auto;
            padding: 1cm;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            position: relative;
            box-sizing: border-box;
        }

        /* BINGKAI LUAR (FRAME) */
        .outer-border {
            border: 4px double #000;
            height: 27.5cm;
            padding: 0.5cm;
            position: relative;
        }

        /* KOP SURAT */
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            min-height: 80px;
        }
        
        /* PENYESUAIAN UKURAN LOGO */
        .logo-left { 
            position: absolute; 
            left: 10px; 
            top: 5px; 
            width: 55px; /* Diperkecil dari 70px */
            height: auto;
        }
        .logo-right { 
            position: absolute; 
            right: 10px; 
            top: 5px; 
            width: 65px; /* Diperkecil dari 85px */
            height: auto;
        }

        .header-text h3 { margin: 0; font-size: 13pt; font-weight: bold; text-transform: uppercase; line-height: 1.2; }
        .header-text h2 { margin: 2px 0; font-size: 15pt; font-weight: bold; text-transform: uppercase; line-height: 1.2; }
        .header-text p { margin: 0; font-size: 9pt; font-style: italic; line-height: 1.3; }

        /* JUDUL SURAT */
        .judul-box {
            text-align: center;
            margin: 25px 0;
        }
        .judul-box h1 {
            margin: 0;
            font-size: 16pt;
            text-decoration: underline;
            font-weight: bold;
            text-transform: uppercase;
        }
        .judul-box p {
            margin: 5px 0;
            font-size: 11pt;
            font-weight: bold;
        }

        /* ISI SURAT */
        .content {
            padding: 0 20px;
            text-align: justify;
            font-size: 12pt;
            line-height: 1.5;
        }

        .student-info {
            margin: 15px auto;
            width: 90%;
            border-collapse: collapse;
        }
        .student-info td {
            padding: 4px;
            vertical-align: top;
        }

        .warning-text {
            background-color: #fffafb;
            border: 1px dashed #ef4444;
            padding: 15px;
            margin: 20px 0;
            border-radius: 8px;
        }

        /* TANDA TANGAN */
        .footer-section {
            margin-top: 40px;
            width: 100%;
        }
        
        .ttd-grid {
            display: table;
            width: 100%;
            margin-top: 20px;
        }
        
        .ttd-col {
            display: table-cell;
            width: 33%;
            text-align: center;
            vertical-align: top;
        }

        .ttd-col p { margin: 0; line-height: 1.3; font-size: 11pt; }

        .ttd-space { height: 65px; }
        
        .footer-note {
            position: absolute;
            bottom: 15px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8pt;
            color: #64748b;
        }

        button {
            padding: 10px 20px;
            background: #1e293b;
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        button:hover { background: #0f172a; }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()">
            Cetak Dokumen SP
        </button>
    </div>

    <div class="page-container">
        <div class="outer-border">
            
            <!-- HEADER / KOP -->
            <div class="header">
                <img src="{{ asset('img/logo_ciamis.png') }}" class="logo-left" onerror="this.style.display='none'">
                <div class="header-text">
                    <h3>PEMERINTAH KABUPATEN CIAMIS</h3>
                    <h3>DINAS PENDIDIKAN</h3>
                    <h2>SMP NEGERI 3 LAKBOK</h2>
                    <p>Jalan Mekarjaya No. 199 Sidaharja Kecamatan Lakbok Kabupaten Ciamis</p>
                </div>
                <img src="{{ asset('img/logo_sekolah.png') }}" class="logo-right" onerror="this.style.display='none'">
            </div>

            @php
                // Logika Penentuan SP berdasarkan total_violation
                $totalMinus = $student->total_violation ?? 0;
                $spLevel = "I (SATU)";
                if($totalMinus >= 300) $spLevel = "III (TIGA)";
                elseif($totalMinus >= 250) $spLevel = "II (DUA)";
            @endphp

            <!-- JUDUL -->
            <div class="judul-box">
                <h1>SURAT PERINGATAN {{ $spLevel }}</h1>
                <p>Nomor: 421.3 / BK / {{ date('Y') }} / {{ str_pad($student->id, 3, '0', STR_PAD_LEFT) }}</p>
            </div>

            <!-- KONTEN -->
            <div class="content">
                <p>Yang bertanda tangan di bawah ini, Bimbingan Konseling (BK) SMP Negeri 3 Lakbok, memberikan Surat Peringatan kepada:</p>
                
                <table class="student-info">
                    <tr><td width="160">Nama Lengkap</td><td width="10">:</td><td style="font-weight: bold; text-transform: uppercase;">{{ $student->name }}</td></tr>
                    <tr><td>NIS / NISN</td><td>:</td><td>{{ $student->nis }} / {{ $student->student_id }}</td></tr>
                    <tr><td>Kelas</td><td>:</td><td>{{ $student->schoolClass->name ?? '-' }}</td></tr>
                    <tr><td>Total Poin Pelanggaran</td><td>:</td><td style="color: #dc2626; font-weight: bold;">{{ $totalMinus }} Poin</td></tr>
                </table>

                <p>Surat ini dikeluarkan berdasarkan hasil rekapitulasi kedisiplinan pada sistem digital sekolah, di mana yang bersangkutan telah melakukan pelanggaran tata tertib yang melampaui batas toleransi yang ditetapkan.</p>
                
                <div class="warning-text">
                    <strong>Pernyataan:</strong><br>
                    Siswa tersebut di atas diperingatkan agar segera memperbaiki perilaku dan menaati aturan sekolah. Kelalaian atau pengulangan tindakan melanggar hukum/tata tertib akan berakibat pada pemberian sanksi yang lebih berat hingga pengembalian kepada Orang Tua/Wali.
                </div>

                <p>Demikian surat peringatan ini dibuat untuk diketahui dan menjadi perhatian bagi semua pihak yang berkepentingan.</p>
            </div>

            <!-- TANDA TANGAN -->
            <div class="footer-section">
                <div class="ttd-grid">
                    <div class="ttd-col">
                        <p>Mengetahui,</p>
                        <p>Orang Tua / Wali Siswa</p>
                        <div class="ttd-space"></div>
                        <p style="font-weight: bold; text-decoration: underline;">( ................................... )</p>
                    </div>
                    
                    <div class="ttd-col">
                        <p>Menyetujui,</p>
                        <p>Kepala Sekolah</p>
                        <div class="ttd-space"></div>
                        <p style="font-weight: bold; text-decoration: underline;">TANTAN SUTANDI N., S.Pd., M.Pd</p>
                        <p>NIP. 19820928 201101 1 002</p>
                    </div>

                    <div class="ttd-col">
                        <p>Lakbok, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                        <p>Guru Pembimbing / BK</p>
                        <div class="ttd-space"></div>
                        <p style="font-weight: bold; text-decoration: underline;">{{ auth()->user()->name ?? 'GURU BK' }}</p>
                        <p>NIP. ...................................</p>
                    </div>
                </div>
            </div>

            <div class="footer-note">
                Dokumen ini sah dan dihasilkan secara otomatis melalui Sistem Informasi SMPN 3 Lakbok.
            </div>
        </div>
    </div>

</body>
</html>