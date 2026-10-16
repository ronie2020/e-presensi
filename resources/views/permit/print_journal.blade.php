<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        @page { size: A4 portrait; margin: 1.5cm; }
        body { font-family: 'Bookman Old Style', Bookman, Georgia, serif; color: #000; line-height: 1.4; font-size: 10pt; }
        .no-print { display: block; }
        @media print { 
            .no-print { display: none !important; } 
            body { -webkit-print-color-adjust: exact; }
        }

        /* --- KOP SURAT (Sesuai Standar SPPD) --- */
        .kop-surat { width: 100%; border-collapse: collapse; margin-bottom: 2px; }
        .kop-surat td { padding: 0; vertical-align: middle; }
        .kop-dinas { font-size: 14pt; letter-spacing: 0.025em; margin-bottom: 3px; line-height: 1.1; }
        .kop-sekolah { font-size: 22pt; font-weight: bold; letter-spacing: 0.05em; margin-bottom: 3px; line-height: 1.1; }
        .kop-alamat { font-size: 11pt; font-style: normal; line-height: 1.2; }
        .kop-kontak { font-size: 10.5pt; margin-top: 3px; line-height: 1.2; }
        .garis-kop { border: none; border-top: 3.5px solid #000; border-bottom: 1.5px solid #000; height: 2px; margin-top: 6px; margin-bottom: 16px; }

        /* --- JUDUL --- */
        .doc-title { text-align: center; margin-bottom: 15px; }
        .doc-title h3 { margin: 0; font-size: 13pt; text-decoration: underline; font-weight: bold; text-transform: uppercase; }
        .doc-title p { margin: 3px 0 0 0; font-size: 10pt; font-weight: bold; }

        .meta-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; font-size: 9.5pt; }
        .meta-table td { padding: 3px 4px; vertical-align: top; }

        /* --- SECTION TITLE --- */
        .section-header { font-weight: bold; text-transform: uppercase; font-size: 10pt; margin-top: 14px; margin-bottom: 5px; border-bottom: 1px solid #000; padding-bottom: 2px; }

        /* --- TABEL DATA --- */
        table.data { width: 100%; border-collapse: collapse; margin-top: 4px; margin-bottom: 10px; font-size: 9pt; }
        table.data th, table.data td { border: 1px solid #000; padding: 4px 6px; }
        table.data th { background-color: #f3f3f3; text-align: center; font-weight: bold; text-transform: uppercase; }
        table.data td { vertical-align: middle; }

        /* --- TANDA TANGAN --- */
        .footer { margin-top: 25px; width: 100%; page-break-inside: avoid; }
        .sig-table { width: 100%; border-collapse: collapse; }
        .sig-table td { width: 50%; text-align: center; vertical-align: top; font-size: 10pt; }
        .sig-space { height: 60px; }

        /* Tombol Cetak */
        .btn-print {
            position: fixed; top: 20px; right: 20px;
            background: #1e3a8a; color: white; border: none;
            padding: 10px 20px; border-radius: 8px; cursor: pointer;
            font-weight: bold; box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            font-family: sans-serif; display: flex; align-items: center; gap: 8px; z-index: 9999;
        }
        .btn-print:hover { background: #1e40af; }
    </style>
</head>
<body>

    <button onclick="window.print()" class="btn-print no-print">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 256 256"><path d="M200,80V48a16,16,0,0,0-16-16H72A16,16,0,0,0,56,48V80H40A16,16,0,0,0,24,96v80a16,16,0,0,0,16,16H56v32a16,16,0,0,0,16,16H184a16,16,0,0,0,16-16V192h16a16,16,0,0,0,16-16V96A16,16,0,0,0,200,80ZM72,48H184V80H72ZM184,224H72V176H184Zm32-48H200V160a16,16,0,0,0-16-16H72a16,16,0,0,0-16,16v16H40V96H216v80Z"></path></svg>
        Cetak Dokumen
    </button>

    {{-- KOP SURAT RESMI (Sama dengan SPPD) --}}
    <table class="kop-surat">
        <tr>
            <td style="width: 15%; text-align: center;">
                <img src="{{ asset('img/logo_ciamis.png') }}" alt="Logo Ciamis" style="width: 80px; height: auto; object-fit: contain;" onerror="this.style.display='none'">
            </td>
            <td style="width: 70%; text-align: center;">
                <div class="kop-dinas">PEMERINTAH KABUPATEN CIAMIS</div>
                <div class="kop-sekolah">SMP NEGERI 3 LAKBOK</div>
                <div class="kop-alamat">Jalan Mekarjaya No.199, Sidaharja</div>
                <div class="kop-alamat">Kecamatan Lakbok, Kabupaten Ciamis 46385</div>
                <div class="kop-kontak">
                    Laman: <a href="http://www.smpn3lakbok.sch.id" style="color: #1d4ed8; text-decoration: underline;">www.smpn3lakbok.sch.id</a> 
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                    E-mail: netila.smp@gmail.com
                </div>
            </td>
            <td style="width: 15%; text-align: center;">
                <img src="{{ asset('img/logo_sekolah.png') }}" alt="Logo Sekolah" style="width: 85px; height: auto; object-fit: contain;" onerror="this.style.display='none'">
            </td>
        </tr>
    </table>
    <div class="garis-kop"></div>

    {{-- JUDUL LAPORAN --}}
    <div class="doc-title">
        <h3>BUKU JURNAL PIKET HARIAN SEKOLAH</h3>
        <p>Hari: {{ $dayName }}, Tanggal {{ $dateObj->translatedFormat('d F Y') }}</p>
    </div>

    {{-- INFORMASI UMUM & PETUGAS PIKET --}}
    <table class="meta-table">
        <tr>
            <td style="width: 20%; font-weight: bold;">Tahun Ajaran</td>
            <td style="width: 30%;">: {{ $journal->academicYear->name ?? '2026/2027' }}</td>
            <td style="width: 20%; font-weight: bold;">Keadaan Cuaca</td>
            <td style="width: 30%;">: {{ $journal->weather ?? 'Cerah' }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Petugas Piket</td>
            <td colspan="3">: 
                @if($dutyTeachers->count() > 0)
                    {{ $dutyTeachers->pluck('teacher.name')->implode(', ') }}
                @else
                    -
                @endif
            </td>
        </tr>
    </table>

    {{-- 1. GURU TIDAK HADIR & GURU INVAL --}}
    <div class="section-header">I. CATATAN GURU TIDAK HADIR & GURU PENGGANTI (INVAL)</div>
    <table class="data">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 25%;">Nama Guru Absen</th>
                <th style="width: 15%;">Alasan</th>
                <th style="width: 15%;">Kelas & Jam</th>
                <th style="width: 20%;">Materi / Tugas</th>
                <th style="width: 20%;">Guru Inval (Pengganti)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($journal->invals ?? [] as $index => $inval)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td><strong>{{ $inval->absentTeacher->name ?? '-' }}</strong></td>
                <td>{{ $inval->reason }}</td>
                <td>{{ $inval->schoolClass->name ?? '-' }} ({{ $inval->timeslot_info ?: 'Jam KBM' }})</td>
                <td>{{ $inval->assignment_notes ?: '-' }}</td>
                <td>{{ $inval->invalTeacher->name ?? 'Tugas Mandiri' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; font-style: italic; color: #666;">Seluruh guru hadir sesuai jadwal (Tidak ada kelas kosong / inval).</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- 2. BUKU TAMU PIKET --}}
    <div class="section-header">II. CATATAN TAMU KUNJUNGAN SEKOLAH</div>
    <table class="data">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 22%;">Nama Tamu</th>
                <th style="width: 20%;">Instansi / Asal</th>
                <th style="width: 18%;">Bertemu Dengan</th>
                <th style="width: 23%;">Keperluan</th>
                <th style="width: 12%;">Waktu</th>
            </tr>
        </thead>
        <tbody>
            @forelse($journal->guests ?? [] as $index => $guest)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td><strong>{{ $guest->guest_name }}</strong></td>
                <td>{{ $guest->institution ?: '-' }}</td>
                <td>{{ $guest->meet_with ?: 'Pihak Sekolah' }}</td>
                <td>{{ $guest->purpose }}</td>
                <td style="text-align: center;">{{ substr($guest->time_in, 0, 5) }} - {{ $guest->time_out ? substr($guest->time_out, 0, 5) : '...' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; font-style: italic; color: #666;">Tidak ada catatan kunjungan tamu pada hari ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- 3. REKAP SISWA IZIN KELUAR --}}
    <div class="section-header">III. REKAPITULASI IZIN KELUAR SISWA (DISPENSASI)</div>
    <table class="data">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 30%;">Nama Siswa</th>
                <th style="width: 15%;">Kelas</th>
                <th style="width: 25%;">Alasan / Keperluan</th>
                <th style="width: 12%;">Jam Keluar</th>
                <th style="width: 13%;">Jam Kembali</th>
            </tr>
        </thead>
        <tbody>
            @forelse($permits ?? [] as $index => $permit)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td><strong>{{ $permit->student->name ?? '-' }}</strong></td>
                <td style="text-align: center;">{{ $permit->student->schoolClass->name ?? '-' }}</td>
                <td>{{ $permit->reason_category }} {{ $permit->notes ? '('.$permit->notes.')' : '' }}</td>
                <td style="text-align: center;">{{ $permit->time_out ? $permit->time_out->format('H:i') : '-' }}</td>
                <td style="text-align: center;">{{ $permit->time_in ? $permit->time_in->format('H:i') : 'Belum Kembali' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; font-style: italic; color: #666;">Tidak ada siswa yang izin keluar kelas/gerbang pada hari ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- 4. CATATAN KHUSUS & KEJADIAN PENTING --}}
    <div class="section-header">IV. CATATAN KHUSUS / KEJADIAN PENTING / KETERTIBAN</div>
    <div style="border: 1px solid #000; padding: 10px; min-height: 50px; background-color: #fafafa; font-style: {{ empty($journal->general_notes) ? 'italic' : 'normal' }};">
        {{ $journal->general_notes ?: 'Situasi KBM dan ketertiban sekolah secara umum berlangsung aman, tertib, dan lancar.' }}
    </div>

    {{-- TANDA TANGAN RESMI --}}
    <div class="footer">
        <table class="sig-table">
            <tr>
                <td>
                    Mengetahui,<br>
                    <strong>Kepala Sekolah</strong>
                    <div class="sig-space"></div>
                    <strong>( TANTAN SUTANDI NUGRAHA, S.Pd, M.Pd )</strong><br>
                    NIP. .................................................
                </td>
                <td>
                    Lakbok, {{ $dateObj->translatedFormat('d F Y') }}<br>
                    <strong>Koordinator Guru Piket</strong>
                    <div class="sig-space"></div>
                    <strong>( {{ $journal->coordinator->name ?? '....................................................' }} )</strong><br>
                    NIP. {{ $journal->coordinator->nip ?? '.................................................' }}
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
