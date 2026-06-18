<table style="border-collapse: collapse; font-family: Arial, sans-serif;">
    {{-- KOP SURAT (Judul) --}}
    <tr>
        <td colspan="3" style="text-align: center; font-weight: bold; font-size: 14px; height: 30px; vertical-align: middle;">DAFTAR NAMA SISWA</td>
    </tr>
    <tr>
        <td colspan="3"></td> {{-- Spacer / Jarak --}}
    </tr>

    {{-- INFO KELAS & TAHUN PELAJARAN --}}
    <tr>
        <td style="font-weight: bold; font-size: 11px;">TAHUN PELAJARAN</td>
        <td colspan="2" style="font-weight: bold; font-size: 11px;">: {{ $tahunPelajaran ?? '2024/2025' }}</td>
    </tr>
    <tr>
        <td style="font-weight: bold; font-size: 11px;">KELAS</td>
        <td colspan="2" style="font-weight: bold; font-size: 11px;">: {{ $class->name }}</td>
    </tr>
    <tr>
        <td style="font-weight: bold; font-size: 11px;">WALI KELAS</td>
        <td colspan="2" style="font-weight: bold; font-size: 11px;">: .......................................</td>
    </tr>
    <tr>
        <td colspan="3"></td> {{-- Spacer / Jarak --}}
    </tr>

    {{-- HEADER TABEL (Sesuai Gambar Terbaru) --}}
    <tr>
        <th style="text-align: center; font-weight: bold; vertical-align: middle; border: 1px solid black; width: 5px; background-color: #f3f4f6; height: 25px;">No.</th>
        <th style="text-align: center; font-weight: bold; vertical-align: middle; border: 1px solid black; width: 20px; background-color: #f3f4f6;">NIS / NISN</th>
        <th style="text-align: center; font-weight: bold; vertical-align: middle; border: 1px solid black; width: 45px; background-color: #f3f4f6;">NAMA SISWA</th>
    </tr>

    {{-- ISI DATA SISWA --}}
    @php
        $laki = 0;
        $perempuan = 0;
    @endphp

    @foreach($students as $index => $s)
        @php
            if($s->gender == 'L') $laki++;
            if($s->gender == 'P') $perempuan++;
        @endphp
        <tr>
            <td style="text-align: center; border: 1px solid black; height: 22px;">{{ $index + 1 }}</td>
            <td style="text-align: center; border: 1px solid black;">{{ $s->student_id }}</td>
            <td style="border: 1px solid black; text-transform: uppercase;">{{ $s->name }}</td>
        </tr>
    @endforeach

    {{-- FOOTER REKAPITULASI & TTD WALI KELAS --}}
    <tr>
        <td colspan="3"></td>
    </tr>
    <tr>
        <td colspan="2" style="font-weight: bold; font-size: 11px;">Laki - Laki</td>
        <td style="font-weight: bold; font-size: 11px;">: {{ $laki }} Orang</td>
    </tr>
    <tr>
        <td colspan="2" style="font-weight: bold; font-size: 11px;">Perempuan</td>
        <td style="font-weight: bold; font-size: 11px;">: {{ $perempuan }} Orang</td>
    </tr>
    <tr>
        <td colspan="2" style="font-weight: bold; font-size: 11px;">Jumlah</td>
        <td style="font-weight: bold; font-size: 11px;">: {{ $laki + $perempuan }} Orang</td>
    </tr>
    
    {{-- Ruang Tanda Tangan --}}
    <tr>
        <td colspan="3"></td>
    </tr>
    <tr>
        <td colspan="2"></td>
        <td style="text-align: center; font-size: 11px;">Mengetahui,</td>
    </tr>
    <tr>
        <td colspan="2"></td>
        <td style="text-align: center; font-size: 11px;">Wali Kelas</td>
    </tr>
    <tr>
        <td colspan="3" style="height: 50px;"></td> {{-- Jarak untuk TTD --}}
    </tr>
    <tr>
        <td colspan="2"></td>
        <td style="text-align: center; font-weight: bold; font-size: 11px; text-decoration: underline;">
            ( .......................................... )
        </td>
    </tr>
</table>