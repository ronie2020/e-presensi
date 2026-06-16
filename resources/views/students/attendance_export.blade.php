<table>
    {{-- KOP SURAT --}}
    <tr>
        <td colspan="36" style="text-align: center; font-weight: bold; font-size: 14px;">DAFTAR HADIR SISWA</td>
    </tr>
    <tr>
        <td colspan="36" style="text-align: center; font-weight: bold; font-size: 14px;">NAMA SEKOLAH ANDA DISINI</td>
    </tr>
    <tr>
        <td colspan="36" style="text-align: center; font-weight: bold; font-size: 12px;">TAHUN PELAJARAN {{ $tahunPelajaran }}</td>
    </tr>
    <tr>
        <td colspan="36"></td> {{-- Spacer --}}
    </tr>

    {{-- INFO KELAS --}}
    <tr>
        <td colspan="36" style="font-size: 11px;">
            Kelas: <b>{{ $class->name }}</b> | Wali Kelas: ....................................... | Bulan: ..............................
        </td>
    </tr>

    {{-- HEADER TABEL --}}
    <tr>
        <th rowspan="2" style="text-align: center; font-weight: bold; background-color: #f3f4f6;">NO. URUT</th>
        <th rowspan="2" style="text-align: center; font-weight: bold; background-color: #f3f4f6;">NISN / NIS</th>
        <th rowspan="2" style="text-align: center; font-weight: bold; background-color: #f3f4f6;">NAMA SISWA</th>
        <th rowspan="2" style="text-align: center; font-weight: bold; background-color: #f3f4f6;">L/P</th>
        <th colspan="31" style="text-align: center; font-weight: bold; background-color: #f3f4f6;">TANGGAL</th>
        <th rowspan="2" style="text-align: center; font-weight: bold; background-color: #f3f4f6;">KET</th>
    </tr>
    <tr>
        @for ($i = 1; $i <= 31; $i++)
            <th style="text-align: center; font-weight: bold; background-color: #f3f4f6;">{{ $i }}</th>
        @endfor
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
            <td style="text-align: center;">{{ $index + 1 }}</td>
            <td style="text-align: center; text-transform: uppercase;">{{ $s->student_id }} / {{ $s->nis }}</td>
            <td style="text-transform: uppercase;">{{ $s->name }}</td>
            <td style="text-align: center;">{{ $s->gender }}</td>
            {{-- Kotak Kosong untuk Absensi --}}
            @for ($i = 1; $i <= 31; $i++)
                <td></td>
            @endfor
            <td></td> {{-- Keterangan --}}
        </tr>
    @endforeach

    {{-- FOOTER REKAPITULASI --}}
    <tr><td colspan="36"></td></tr>
    <tr>
        <td colspan="3"><b>Rekapitulasi:</b></td>
        <td colspan="33"></td>
    </tr>
    <tr>
        <td colspan="2">Laki - Laki</td>
        <td>: {{ $laki }} Orang</td>
        <td colspan="33"></td>
    </tr>
    <tr>
        <td colspan="2">Perempuan</td>
        <td>: {{ $perempuan }} Orang</td>
        <td colspan="33"></td>
    </tr>
    <tr>
        <td colspan="2"><b>Jumlah Total</b></td>
        <td><b>: {{ $laki + $perempuan }} Orang</b></td>
        <td colspan="33"></td>
    </tr>
</table>