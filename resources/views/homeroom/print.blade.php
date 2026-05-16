<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Evaluasi Wali Kelas - Kelas {{ $class->name }}</title>
    <style>
        @page { size: A4; margin: 2cm; }
        body { font-family: 'Bookman Old Style', Bookman, Georgia, serif; color: #000; line-height: 1.5; font-size: 11pt; }
        
        .no-print { display: block; margin-bottom: 20px; text-align: center; }
        .no-print button { padding: 10px 20px; font-size: 14px; background: #032b5b; color: white; border: none; border-radius: 5px; cursor: pointer; }
        
        @media print { 
            .no-print { display: none !important; } 
            body { -webkit-print-color-adjust: exact; }
            .page-break { page-break-before: always; }
        }
        
        /* --- KOP SURAT STYLE --- */
        .kop-surat { width: 100%; border-collapse: collapse; margin-bottom: 2px; }
        .kop-surat td { padding: 0; vertical-align: middle; }
        .kop-dinas { font-size: 14pt; letter-spacing: 0.025em; margin-bottom: 4px; line-height: 1.1; }
        .kop-sekolah { font-size: 22pt; font-weight: bold; letter-spacing: 0.05em; margin-bottom: 4px; line-height: 1.1; }
        .kop-alamat { font-size: 10pt; line-height: 1.2; margin-bottom: 2px; }
        .garis-kop { border-top: 3px solid #000; border-bottom: 1px solid #000; height: 2px; width: 100%; margin-top: 5px; margin-bottom: 20px; }
        
        /* --- TYPOGRAPHY --- */
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .text-danger { color: #dc2626; }
        .text-success { color: #16a34a; }
        .mt-4 { margin-top: 20px; }
        
        /* --- TABLES --- */
        table.data { width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 20px; font-size: 10pt; }
        table.data th { background-color: #f1f5f9; text-align: center; border: 1px solid #000; padding: 8px; }
        table.data td { border: 1px solid #000; padding: 6px 8px; }
        .center { text-align: center; }

        /* --- INFO KELAS --- */
        .info-kelas { margin-bottom: 20px; font-weight: bold; width: 100%; }
        .info-kelas td { padding: 3px 0; }
        
        /* --- FOOTER / TTD --- */
        .footer { width: 100%; margin-top: 40px; display: table; }
        .signature-box { display: table-cell; width: 50%; text-align: center; vertical-align: bottom; }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()">🖨️ Cetak Laporan Sekarang</button>
        <button onclick="window.close()" style="background: #dc2626; margin-left: 10px;">Tutup</button>
    </div>

    <!-- KOP SURAT -->
    <table class="kop-surat text-center">
        <tr>
            <!-- Logo Kiri -->
            <td width="15%">
                <img src="{{ asset('img/logo_ciamis.png') }}" alt="Logo" style="width: 80px; height: auto;" onerror="this.style.display='none'">
            </td>
            <td width="70%">
                <div class="kop-dinas">{{ config('school.kop_dinas', 'DINAS PENDIDIKAN') }}</div>
                <div class="kop-sekolah">{{ config('school.name', 'SMP NEGERI 3 LAKBOK') }}</div>
                <div class="kop-alamat">{{ config('school.address', 'Jalan Mekarjaya No.199, Sidaharja Kecamatan Lakbok, Kabupaten Ciamis 46385') }}</div>
                <div class="kop-alamat">Website: {{ config('school.website', 'www.smpn3lakbok.sch.id') }} | Email: {{ config('school.email', 'netila.smp@gmail.com') }}</div>
            </td>
            <!-- Logo Kanan -->
            <td width="15%">
                <img src="{{ asset('img/logo_sekolah.png') }}" alt="Logo" style="width: 85px; height: auto;" onerror="this.style.display='none'">
            </td>
        </tr>
    </table>
    <div class="garis-kop"></div>

    <!-- JUDUL LAPORAN -->
    <h3 class="text-center text-bold" style="text-transform: uppercase; margin-bottom: 20px;">
        LAPORAN EVALUASI WALI KELAS<br>
        SEMESTER {{ now()->month >= 7 ? 'GANJIL' : 'GENAP' }} - {{ now()->year }}
    </h3>

    <!-- INFO KELAS -->
    <table class="info-kelas">
        <tr>
            <td width="20%">Kelas</td>
            <td width="2%">:</td>
            <td width="78%">{{ $class->name }}</td>
        </tr>
        <tr>
            <td>Wali Kelas</td>
            <td>:</td>
            <td>{{ $teacherName }}</td>
        </tr>
        <tr>
            <td>Total Siswa</td>
            <td>:</td>
            <td>{{ $stats['total_students'] }} Orang</td>
        </tr>
    </table>

    <hr style="border: 0.5px solid #ccc; margin-bottom: 20px;">

    <!-- BAGIAN A: NOMINASI SISWA TELADAN -->
    <h4 class="text-bold mt-4">A. Nominasi Siswa Teladan (Berdasarkan Sistem Skoring Terpadu)</h4>
    <p style="font-size: 9.5pt; margin-top: -10px; margin-bottom: 10px; color: #555;">*Dihitung berdasarkan Rata-rata Akademik, Absensi, Kedisiplinan, dan Keaktifan Tugas.</p>
    
    @if($awardNominees->count() > 0)
        <table class="data">
            <thead>
                <tr>
                    <th width="5%">Rnk</th>
                    <th width="35%">Nama Siswa</th>
                    <th width="15%">NISN</th>
                    <th width="10%">Alpa</th>
                    <th width="15%">Pelanggaran</th>
                    <th width="10%">Akademik</th>
                    <th width="10%">Total Skor</th>
                </tr>
            </thead>
            <tbody>
                @foreach($awardNominees as $index => $nominee)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td>{{ $nominee->name }}</td>
                    <td class="center">{{ $nominee->nisn }}</td>
                    <td class="center">{{ $nominee->alfa_count == 0 ? 'Bersih' : $nominee->alfa_count }}</td>
                    <td class="center">{{ $nominee->violation_points == 0 ? 'Bersih' : $nominee->violation_points }}</td>
                    <td class="center">{{ $nominee->academic_score }}</td>
                    <td class="center text-bold">{{ number_format($nominee->total_score, 0) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="font-style: italic; color: #666; padding-left: 15px;">Belum ada siswa yang memenuhi kriteria rekomendasi teladan di semester ini.</p>
    @endif

    <!-- BAGIAN B: SISWA PERLU PERHATIAN KHUSUS -->
    <h4 class="text-bold mt-4">B. Daftar Siswa Perlu Bimbingan Khusus (Early Warning)</h4>
    @if($warningStudents->count() > 0)
        <table class="data">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="35%">Nama Siswa</th>
                    <th width="15%">NISN</th>
                    <th width="15%" class="text-danger">Total Alpa</th>
                    <th width="15%" class="text-danger">Poin Minus</th>
                    <th>Catatan Sistem</th>
                </tr>
            </thead>
            <tbody>
                @foreach($warningStudents as $index => $ws)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td>{{ $ws->name }}</td>
                    <td class="center">{{ $ws->nisn }}</td>
                    <td class="center text-danger">{{ $ws->alfa_count }} Hari</td>
                    <td class="center text-danger">-{{ $ws->violation_points }}</td>
                    <td class="center">{{ $ws->issue }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="font-style: italic; color: #666; padding-left: 15px;">Aman terkendali. Tidak ada siswa dengan pelanggaran/absensi ekstrem.</p>
    @endif

    <!-- TANDA TANGAN -->
    <div class="footer">
        <div class="signature-box">
            <p>
                Mengetahui,<br>
                Kepala Sekolah
            </p>
            <div style="font-weight: bold; text-decoration: underline; margin-top: 60px;">
                {{ config('school.headmaster_name', '...........................................') }}
            </div>
            <div style="margin-top: 5px;">NIP. {{ config('school.headmaster_nip', '.....................................') }}</div>
        </div>
        <div class="signature-box">
            <p>
                {{ config('school.city', 'Ciamis') }}, {{ now()->isoFormat('D MMMM Y') }}<br>
                Wali Kelas {{ $class->name }}
            </p>
            <div style="font-weight: bold; text-decoration: underline; margin-top: 60px;">
                {{ $teacherName }}
            </div>
            <div style="margin-top: 5px;">NIP. {{ $teacherNip }}</div>
        </div>
    </div>

</body>
</html>