<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Surat Tugas - {{ $spt->nomor_spt }}</title>
    <style>
        /* Pengaturan Kertas F4 (21.5cm x 33cm) */
        @page { size: 21.5cm 33cm; margin: 1cm 2cm; }
        
        body { 
            font-family: 'Times New Roman', serif; 
            font-size: 11pt; 
            line-height: 1.15; 
            color: #000; 
            margin: 0; padding: 0;
        }

        /* Tombol Print (Sembunyi saat dicetak) */
        @media print { .no-print { display: none; } }

        /* KOP SURAT */
        .header { text-align: center; margin-bottom: 5px; margin-top: 10px; }
        .header h3 { margin: 0; font-size: 14pt; font-weight: bold; text-transform: uppercase; }
        .header h4 { margin: 0; font-size: 12pt; font-weight: bold; text-transform: uppercase; }
        .header p { margin: 0; font-size: 10pt; }
        .line { border-bottom: 3px double black; margin-top: 5px; margin-bottom: 20px; }

        /* Judul Dokumen */
        .judul { text-align: center; margin-bottom: 20px; }
        .judul h2 { margin: 0; font-weight: bold; text-decoration: underline; font-size: 12pt; text-transform: uppercase; }
        .judul p { margin: 0; font-size: 11pt; }

        /* Tabel Info Utama (Dasar, Kepada, Untuk) */
        table.info { width: 100%; border-collapse: collapse; }
        table.info td { vertical-align: top; padding: 2px 0; }
        .label { width: 90px; }
        .titik-dua { width: 20px; text-align: center; }

        /* Tabel Daftar Pegawai */
        table.pegawai { width: 100%; border-collapse: collapse; margin: 10px 0; border: 1px solid black; }
        table.pegawai th, table.pegawai td { border: 1px solid black; padding: 5px; font-size: 10pt; }
        table.pegawai th { text-align: center; background-color: #f0f0f0; }
        
        /* Area Tanda Tangan */
        .ttd-area { float: right; width: 45%; text-align: left; margin-top: 30px; }
        .clear { clear: both; }

        .text-bold { font-weight: bold; }
        .text-justify { text-align: justify; }
    </style>
</head>
<body>

    <!-- Tombol Navigasi -->
    <div class="no-print" style="margin-bottom: 20px; text-align: right; background: #eee; padding: 10px; border-bottom: 1px solid #ccc;">
        <button onclick="window.print()" style="padding: 8px 20px; background: #1e40af; color: white; border: none; cursor: pointer; font-weight: bold; border-radius: 4px;">🖨️ Cetak SPT</button>
        <a href="{{ route('letters.spt.index') }}" style="margin-left: 10px; color: #333; text-decoration: none;">&larr; Kembali</a>
    </div>

    <!-- KOP SURAT -->
    <div class="header">
        <h3>PEMERINTAH KABUPATEN CIAMIS</h3>
        <h3>DINAS PENDIDIKAN</h3>
        <h4>SMP NEGERI 3 LAKBOK</h4>
        <p>Jalan Mekarjaya No. 199 Sidaharja Kecamatan Lakbok Kabupaten Ciamis</p>
        <p>Laman: www.smpn3lakbok.sch.id   E-mail: smpn3lakbok@gmail.com</p>
    </div>
    <div class="line"></div>

    <!-- JUDUL -->
    <div class="judul">
        <h2>SURAT PERINTAH TUGAS</h2>
        <p>Nomor: {{ $spt->nomor_spt }}</p>
    </div>

    <!-- ISI SURAT -->
    <div class="content">
        <!-- BAGIAN DASAR -->
        <table class="info">
            <tr>
                <td class="label">Dasar</td>
                <td class="titik-dua">:</td>
                <td class="text-justify">
                    @if($spt->letterIncoming)
                        Surat dari {{ $spt->letterIncoming->pengirim }} 
                        Nomor {{ $spt->letterIncoming->nomor_surat }} 
                        tanggal {{ $spt->letterIncoming->tgl_surat->isoFormat('D MMMM Y') }}
                        tentang "{{ $spt->letterIncoming->perihal }}".
                    @else
                        Kepentingan Dinas Sekolah SMP Negeri 3 Lakbok.
                    @endif
                </td>
            </tr>
        </table>

        <div style="text-align: center; margin: 15px 0; font-weight: bold;">
            MEMBERI TUGAS:
        </div>

        <!-- BAGIAN KEPADA (DAFTAR PEGAWAI) -->
        <table class="info">
            <tr>
                <td class="label">Kepada</td>
                <td class="titik-dua">:</td>
                <td></td>
            </tr>
        </table>
        
        <!-- Tabel List Pegawai -->
        <table class="pegawai">
            <thead>
                <tr>
                    <th style="width: 30px;">No</th>
                    <th>Nama / NIP</th>
                    <th>Pangkat / Gol.</th>
                    <th>Jabatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($spt->users as $index => $user)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>
                        <span class="text-bold">{{ $user->name }}</span><br>
                        NIP. {{ $user->nip ?? '-' }}
                    </td>
                    <td>{{ $user->pangkat ?? '-' }}</td>
                    <td>{{ $user->position ?? 'Guru' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- BAGIAN UNTUK -->
        <table class="info" style="margin-top: 10px;">
            <tr>
                <td class="label">Untuk</td>
                <td class="titik-dua">:</td>
                <td class="text-justify">
                    {{ $spt->untuk }}
                </td>
            </tr>
            <tr>
                <td class="label">Tempat</td>
                <td class="titik-dua">:</td>
                <td>{{ $spt->tempat_tujuan }}</td>
            </tr>
            <tr>
                <td class="label">Waktu</td>
                <td class="titik-dua">:</td>
                <td>
                    {{ $spt->tgl_berangkat->isoFormat('dddd, D MMMM Y') }}
                    @if($spt->lama_hari > 1)
                        s.d. {{ $spt->tgl_kembali->isoFormat('dddd, D MMMM Y') }}
                    @endif
                </td>
            </tr>
        </table>
        
        <p style="margin-top: 15px; text-indent: 30px; text-align: justify;">
            Demikian Surat Perintah Tugas ini dibuat untuk dilaksanakan dengan penuh tanggung jawab.
        </p>
    </div>

    <!-- TANDA TANGAN -->
    <div class="ttd-area">
        <p>Ditetapkan di: Lakbok</p>
        <p>Pada tanggal: {{ $spt->created_at->isoFormat('D MMMM Y') }}</p>
        <br>
        <p class="text-bold">Kepala Sekolah,</p>
        <br><br><br><br>
        <p style="font-weight: bold; text-decoration: underline;">{{ $spt->pejabat_nama }}</p>
        <p>NIP. {{ $spt->pejabat_nip }}</p>
    </div>
    <div class="clear"></div>

</body>
</html>