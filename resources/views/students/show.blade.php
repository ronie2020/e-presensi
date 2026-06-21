<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Induk - {{ $student->name }}</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- INJEKSI TEMA MICROSOFT ELEVATE UNTUK HALAMAN STANDALONE -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        elevate: {
                            dark: '#032b5b',
                            primary: '#3b5889',
                            accent: '#38bdf8',
                            text: '#1e293b',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Phosphor Icons & Fonts -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <style>
        body {
            background-color: #f8fafc; /* slate-50 */
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* PENGATURAN TABEL BUKU INDUK (Formal Standar Diknas) */
        .table-induk {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            font-family: 'Times New Roman', Times, serif;
        }
        .table-induk td {
            padding: 3px 6px;
            vertical-align: top;
        }
        .label-col { width: 35%; font-weight: bold; }
        .separator { width: 2%; }
        .value-col { width: 63%; border-bottom: 1px dotted #cbd5e1; }
        
        .header-section {
            background-color: #f1f5f9; /* slate-100 */
            padding: 4px 8px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
            border-top: 1.5px solid #000;
            border-bottom: 1.5px solid #000;
            margin-top: 12px;
            margin-bottom: 6px;
            font-family: 'Times New Roman', Times, serif;
        }
        
        .sub-header {
            font-weight: bold;
            font-style: italic;
            color: #334155;
            padding-top: 6px;
            padding-bottom: 2px;
            text-decoration: underline;
        }

        /* KHUSUS PENGATURAN CETAK (PRINT) KERTAS A4 / F4 */
        @media print {
            @page {
                size: A4 portrait; /* Bisa diganti Folio/F4 jika diperlukan */
                margin: 10mm; 
            }
            body {
                background-color: white !important;
                margin: 0;
                padding: 0;
            }
            .no-print { display: none !important; }
            /* Mencegah elemen terpotong di tengah kertas */
            .print-break-inside-avoid { page-break-inside: avoid; }
            .print-break-before { page-break-before: always; }
            
            .a4-paper-container {
                box-shadow: none !important;
                margin: 0 !important;
                padding: 0 !important;
                max-width: 100% !important;
                border: none !important;
            }
        }
    </style>
</head>
<body class="min-h-screen py-8 text-elevate-text font-sans relative">

   <!-- DEKORASI BACKGROUND HANYA DI WEB -->
    <div class="fixed top-0 left-0 w-full h-64 bg-gradient-to-b from-elevate-primary/10 to-transparent pointer-events-none no-print -z-10"></div>

    <!-- TOOLBAR AKSI (TIDAK TERCETAK) -->
    <div class="max-w-[210mm] mx-auto mb-6 flex flex-col sm:flex-row justify-between items-center gap-4 no-print bg-white/80 backdrop-blur-md p-4 rounded-2xl shadow-lg border border-white/60 sticky top-4 z-50">
        <div>
            <h2 class="font-black text-elevate-dark flex items-center gap-2">
                <i class="ph-bold ph-archive text-elevate-primary text-xl"></i> Lembar Buku Induk
            </h2>
            <p class="text-xs text-slate-500 font-bold ml-7">{{ $student->name }} ({{ $student->nis }})</p>
        </div>

        <div class="flex flex-wrap gap-3 items-center">
            <button onclick="window.close()" class="px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 flex items-center gap-2 group">
                <i class="ph-bold ph-x group-hover:-rotate-90 transition-transform"></i> Tutup
            </button>
            
            <a href="{{ route('students.edit', $student->id) }}" class="px-4 py-2.5 bg-elevate-accent/10 border border-elevate-accent/20 text-elevate-primary rounded-xl text-xs font-bold hover:bg-elevate-accent/20 flex items-center gap-2">
                <i class="ph-bold ph-pencil-simple text-sm"></i> Edit Data
            </a>
            
            <button onclick="window.print()" class="px-5 py-2.5 bg-elevate-primary text-white font-bold rounded-xl hover:bg-elevate-dark shadow-lg shadow-elevate-primary/30 transition-transform active:scale-95 flex items-center gap-2 text-xs group">
                <i class="ph-bold ph-printer text-sm group-hover:scale-110 transition-transform"></i> Cetak Dokumen
            </button>
        </div>
    </div>

    <!-- AREA KERTAS CETAK (A4) -->
    <div class="a4-paper-container max-w-[210mm] mx-auto bg-white shadow-2xl rounded-lg p-[15mm] min-h-[297mm] relative border border-slate-200 text-black">
        
        <div class="font-serif"> 
            
            <!-- KOP SEKOLAH BUKU INDUK -->
            <div class="text-center border-b-[3px] border-black pb-4 mb-5 relative">
                <h1 class="text-xl font-bold uppercase tracking-wider">LEMBAR BUKU INDUK PESERTA DIDIK</h1>
                <h2 class="text-lg font-bold uppercase mt-1">SMP NEGERI 3 LAKBOK</h2>
                <p class="text-xs mt-1">Jalan Raya Lakbok, Kabupaten Ciamis, Jawa Barat</p>
                <div class="absolute bottom-1 left-0 w-full border-b border-black"></div>
            </div>

            <!-- BLOK IDENTITAS, FOTO, DAN CAP 3 JARI -->
            <div class="flex justify-between items-start mb-6">
                <div class="w-2/3">
                    <table class="w-full text-sm font-serif">
                        <tr>
                            <td class="w-40 font-bold py-1">Nomor Induk / NIS</td>
                            <td class="w-4 py-1">:</td>
                            <td class="py-1">{{ $student->nis ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="font-bold py-1">NISN / NIK</td>
                            <td class="py-1">:</td>
                            <td class="py-1">{{ $student->student_id }} / {{ $student->nik ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="font-bold py-1">Nama Peserta Didik</td>
                            <td class="py-1">:</td>
                            <td class="uppercase font-bold text-[15px] py-1">{{ $student->name }}</td>
                        </tr>
                    </table>
                </div>
                
                <!-- Area Pas Foto dan Cap 3 Jari Standar Diknas -->
                <div class="flex gap-2">
                    <div class="w-[3cm] h-[4cm] border-[1.5px] border-black flex items-center justify-center bg-gray-50 p-[2px]">
                        @if($student->photo_path)
                            <img src="{{ asset('storage/' . $student->photo_path) }}" class="w-full h-full object-cover grayscale" alt="Foto Resmi">
                        @else
                            <span class="text-[10px] text-gray-400 text-center font-sans font-bold uppercase">Pas Foto<br>3 x 4</span>
                        @endif
                    </div>                    
                </div>
            </div>

            <!-- A. PRIBADI -->
            <div class="header-section">A. KETERANGAN PRIBADI SISWA</div>
            <table class="table-induk">
                <tr><td class="label-col">1. Nama Lengkap</td><td class="separator">:</td><td class="value-col uppercase font-bold">{{ $student->name }}</td></tr>
                <tr><td class="label-col">2. Nama Panggilan</td><td class="separator">:</td><td class="value-col">{{ $student->nickname ?? '-' }}</td></tr>
                <tr><td class="label-col">3. Jenis Kelamin</td><td class="separator">:</td><td class="value-col">{{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</td></tr>
                <tr><td class="label-col">4. Tempat dan Tanggal Lahir</td><td class="separator">:</td><td class="value-col">{{ $student->pob ?? '.......' }}, {{ $student->dob ? \Carbon\Carbon::parse($student->dob)->translatedFormat('d F Y') : '.......' }}</td></tr>
                <tr><td class="label-col">5. Agama</td><td class="separator">:</td><td class="value-col">{{ $student->religion ?? '-' }}</td></tr>
                <tr><td class="label-col">6. Kewarganegaraan</td><td class="separator">:</td><td class="value-col">{{ $student->citizenship ?? 'Indonesia' }}</td></tr>
                <tr><td class="label-col">7. Anak ke</td><td class="separator">:</td><td class="value-col">{{ $student->birth_order ?? '...' }}</td></tr>
                <tr><td class="label-col">8. Jumlah Saudara (Kandung/Tiri/Angkat)</td><td class="separator">:</td><td class="value-col">{{ $student->siblings_count ?? '0' }} / {{ $student->step_siblings_count ?? '0' }} / {{ $student->adoptive_siblings_count ?? '0' }}</td></tr>
                <tr><td class="label-col">9. Status Yatim/Piatu</td><td class="separator">:</td><td class="value-col">{{ $student->orphan_status ?? '-' }}</td></tr>
                <tr><td class="label-col">10. Bahasa Sehari-hari</td><td class="separator">:</td><td class="value-col">{{ $student->daily_language ?? '-' }}</td></tr>
            </table>

            <!-- B. TEMPAT TINGGAL -->
            <div class="header-section">B. KETERANGAN TEMPAT TINGGAL</div>
            <table class="table-induk">
                <tr><td class="label-col">11. Alamat Peserta Didik</td><td class="separator">:</td><td class="value-col">{{ $student->address ?? '-' }}</td></tr>
                <tr><td class="label-col">12. Nomor Telepon / HP</td><td class="separator">:</td><td class="value-col">{{ $student->phone ?? '-' }}</td></tr>
                <tr><td class="label-col">13. Tinggal Dengan</td><td class="separator">:</td><td class="value-col">{{ $student->living_with ?? '-' }}</td></tr>
                <tr><td class="label-col">14. Jarak ke Sekolah</td><td class="separator">:</td><td class="value-col">{{ $student->distance_to_school ?? '-' }}</td></tr>
                <tr><td class="label-col">15. Transportasi ke Sekolah</td><td class="separator">:</td><td class="value-col">{{ $student->transport_mode ?? '-' }}</td></tr>
            </table>

            <!-- C. KESEHATAN -->
            <div class="header-section">C. KETERANGAN KESEHATAN</div>
            <table class="table-induk">
                <tr><td class="label-col">16. Golongan Darah</td><td class="separator">:</td><td class="value-col">{{ $student->blood_type ?? '-' }}</td></tr>
                <tr><td class="label-col">17. Penyakit Pernah Diderita</td><td class="separator">:</td><td class="value-col">{{ $student->history_disease ?? '-' }}</td></tr>
                <tr><td class="label-col">18. Kelainan Jasmani</td><td class="separator">:</td><td class="value-col">{{ $student->physical_abnormalities ?? '-' }}</td></tr>
                <tr><td class="label-col">19. Tinggi / Berat Badan</td><td class="separator">:</td><td class="value-col">{{ $student->height ?? '...' }} cm / {{ $student->weight ?? '...' }} kg</td></tr>
            </table>

            <!-- D. PENDIDIKAN -->
            <div class="header-section print-break-inside-avoid">D. KETERANGAN PENDIDIKAN SEBELUMNYA</div>
            <table class="table-induk print-break-inside-avoid">
                <tr><td class="label-col">20. Asal Sekolah Dasar (SD/MI)</td><td class="separator">:</td><td class="value-col">{{ $student->school_origin ?? '-' }}</td></tr>
                <tr><td class="label-col">21. No. Ijazah / Tanggal</td><td class="separator">:</td><td class="value-col">{{ $student->prev_diploma_no ?? '-' }} @if($student->prev_exam_date) / {{ \Carbon\Carbon::parse($student->prev_exam_date)->format('d-m-Y') }} @endif</td></tr>
                <tr><td class="label-col">22. Diterima di Sekolah ini Tgl</td><td class="separator">:</td><td class="value-col">{{ $student->accepted_date ? \Carbon\Carbon::parse($student->accepted_date)->translatedFormat('d F Y') : '-' }}</td></tr>
                <tr><td class="label-col">23. Pindahan Dari Sekolah</td><td class="separator">:</td><td class="value-col">{{ $student->transfer_from_school ?? '-' }}</td></tr>
            </table>           

            <!-- =============== HALAMAN 2: DATA ORTU & PERKEMBANGAN =============== -->
            
            <!-- E. ORANG TUA & WALI -->
            <div class="header-section print-break-before">E. KETERANGAN ORANG TUA & WALI</div>
            <table class="table-induk">
                <!-- AYAH -->
                <tr><td colspan="3" class="sub-header">Data Ayah Kandung</td></tr>
                <tr><td class="label-col">24. Nama Ayah</td><td class="separator">:</td><td class="value-col">{{ $student->father_name ?? '-' }}</td></tr>
                <tr><td class="label-col">25. Tempat, Tanggal Lahir</td><td class="separator">:</td><td class="value-col">{{ $student->father_pob ?? '' }}, {{ $student->father_birth_year ? \Carbon\Carbon::parse($student->father_birth_year)->format('Y') : '' }}</td></tr>
                <tr><td class="label-col">26. Pendidikan Tertinggi</td><td class="separator">:</td><td class="value-col">{{ $student->father_education ?? '-' }}</td></tr>
                <tr><td class="label-col">27. Pekerjaan</td><td class="separator">:</td><td class="value-col">{{ $student->father_job ?? '-' }}</td></tr>
                <tr><td class="label-col">28. Penghasilan</td><td class="separator">:</td><td class="value-col">{{ $student->father_income ?? '-' }}</td></tr>

                <!-- IBU -->
                <tr><td colspan="3" class="sub-header">Data Ibu Kandung</td></tr>
                <tr><td class="label-col">29. Nama Ibu</td><td class="separator">:</td><td class="value-col">{{ $student->mother_name ?? '-' }}</td></tr>
                <tr><td class="label-col">30. Tempat, Tanggal Lahir</td><td class="separator">:</td><td class="value-col">{{ $student->mother_pob ?? '' }}, {{ $student->mother_birth_year ? \Carbon\Carbon::parse($student->mother_birth_year)->format('Y') : '' }}</td></tr>
                <tr><td class="label-col">31. Pendidikan Tertinggi</td><td class="separator">:</td><td class="value-col">{{ $student->mother_education ?? '-' }}</td></tr>
                <tr><td class="label-col">32. Pekerjaan</td><td class="separator">:</td><td class="value-col">{{ $student->mother_job ?? '-' }}</td></tr>
                <tr><td class="label-col">33. Penghasilan</td><td class="separator">:</td><td class="value-col">{{ $student->mother_income ?? '-' }}</td></tr>
                <tr><td class="label-col">34. No. Telepon / WA Ortu</td><td class="separator">:</td><td class="value-col">{{ $student->parent_wa_number ?? '-' }} {{ $student->parent_phone ? ' / ' . $student->parent_phone : '' }}</td></tr>

                <!-- WALI -->
                <tr><td colspan="3" class="sub-header">Data Wali (Jika Ada)</td></tr>
                <tr><td class="label-col">35. Nama Wali</td><td class="separator">:</td><td class="value-col">{{ $student->guardian_name ?? '-' }}</td></tr>
                <tr><td class="label-col">36. Tempat, Tanggal Lahir</td><td class="separator">:</td><td class="value-col">{{ $student->guardian_pob ?? '' }} @if($student->guardian_dob), {{ \Carbon\Carbon::parse($student->guardian_dob)->translatedFormat('d F Y') }} @endif</td></tr>
                <tr><td class="label-col">37. Kewarganegaraan</td><td class="separator">:</td><td class="value-col">{{ $student->guardian_citizenship ?? '-' }}</td></tr>
                <tr><td class="label-col">38. Hubungan Keluarga</td><td class="separator">:</td><td class="value-col">{{ $student->guardian_relationship ?? '-' }}</td></tr>
                <tr><td class="label-col">39. Pekerjaan</td><td class="separator">:</td><td class="value-col">{{ $student->guardian_job ?? '-' }}</td></tr>
                <tr><td class="label-col">40. Penghasilan</td><td class="separator">:</td><td class="value-col">{{ $student->guardian_income ?? '-' }}</td></tr>
                <tr><td class="label-col">41. No. Telepon / HP Wali</td><td class="separator">:</td><td class="value-col">{{ $student->guardian_phone ?? '-' }}</td></tr>
                <tr><td class="label-col">42. Alamat Wali</td><td class="separator">:</td><td class="value-col">{{ $student->guardian_address ?? '-' }}</td></tr>
            </table>           

            <!-- F. MENINGGALKAN SEKOLAH -->
            <div class="header-section print-break-inside-avoid">F. KETERANGAN MENINGGALKAN SEKOLAH</div>
            <table class="table-induk print-break-inside-avoid">
                <!-- TAMAT -->
                <tr><td colspan="3" class="sub-header">A. Tamat Belajar</td></tr>
                <tr><td class="label-col">43. Tanggal Tamat</td><td class="separator">:</td><td class="value-col">{{ $student->graduated_date ? \Carbon\Carbon::parse($student->graduated_date)->translatedFormat('d F Y') : '-' }}</td></tr>
                <tr><td class="label-col">44. Nomor Ijazah</td><td class="separator">:</td><td class="value-col">{{ $student->graduated_diploma_no ?? '-' }}</td></tr>
                <tr><td class="label-col">45. Melanjutkan Ke</td><td class="separator">:</td><td class="value-col">{{ $student->continuing_to_school ?? '-' }}</td></tr>
                <tr><td class="label-col">46. Alamat Sekolah Lanjutan</td><td class="separator">:</td><td class="value-col">{{ $student->continuing_school_address ?? '-' }}</td></tr>

                <!-- PINDAH -->
                <tr><td colspan="3" class="sub-header">B. Pindah Sekolah (Mutasi)</td></tr>
                <tr><td class="label-col">47. Tanggal Pindah</td><td class="separator">:</td><td class="value-col">{{ $student->leaving_date ? \Carbon\Carbon::parse($student->leaving_date)->translatedFormat('d F Y') : '-' }}</td></tr>
                <tr><td class="label-col">48. Dari Kelas</td><td class="separator">:</td><td class="value-col">{{ $student->leaving_class ?? '-' }}</td></tr>
                <tr><td class="label-col">49. Ke Sekolah</td><td class="separator">:</td><td class="value-col">{{ $student->leaving_to_school ?? '-' }}</td></tr>
                <tr><td class="label-col">50. Alasan Pindah</td><td class="separator">:</td><td class="value-col">{{ $student->leaving_reason ?? '-' }}</td></tr>

                <!-- PUTUS -->
                <tr><td colspan="3" class="sub-header">C. Putus Sekolah</td></tr>
                <tr><td class="label-col">51. Tanggal / Alasan Putus</td><td class="separator">:</td><td class="value-col">{{ $student->dropout_date ? \Carbon\Carbon::parse($student->dropout_date)->translatedFormat('d F Y') : '-' }} / {{ $student->dropout_reason ?? '-' }}</td></tr>
            </table>

            <!-- G. LAIN-LAIN -->
            <div class="header-section print-break-inside-avoid">G. LAIN-LAIN (PRESTASI & BEASISWA)</div>
            <table class="table-induk print-break-inside-avoid">
                <tr><td class="label-col">52. Prestasi Siswa</td><td class="separator">:</td><td class="value-col">{{ $student->achievements ?? '-' }}</td></tr>
                <tr><td class="label-col">53. Beasiswa</td><td class="separator">:</td><td class="value-col">{{ $student->scholarship_info ?? '-' }}</td></tr>
                <tr><td class="label-col">54. Catatan Penting</td><td class="separator">:</td><td class="value-col">{{ $student->general_notes ?? '-' }}</td></tr>
            </table>

            <!-- H. PRESTASI BELAJAR (RAPORT) & I. ABSENSI (DIGABUNGKAN AGAR RAPI) -->
            <div class="header-section print-break-before" style="margin-top: 20px;">H. PERKEMBANGAN SISWA (RAPORT & KEHADIRAN)</div>
            <table class="w-full text-center text-[10px] font-serif mb-6" style="border-collapse: collapse; border: 1.5px solid #000;">
                <thead>
                    <tr>
                        <th rowspan="2" style="border: 1px solid #000; padding: 4px; width: 5%;">No</th>
                        <th rowspan="2" style="border: 1px solid #000; padding: 4px; width: 35%; text-align: left;">Mata Pelajaran</th>
                        <th colspan="2" style="border: 1px solid #000; padding: 4px;">Kelas VII</th>
                        <th colspan="2" style="border: 1px solid #000; padding: 4px;">Kelas VIII</th>
                        <th colspan="2" style="border: 1px solid #000; padding: 4px;">Kelas IX</th>
                    </tr>
                    <tr>
                        <th style="border: 1px solid #000; padding: 4px; width: 10%;">Smt 1</th>
                        <th style="border: 1px solid #000; padding: 4px; width: 10%;">Smt 2</th>
                        <th style="border: 1px solid #000; padding: 4px; width: 10%;">Smt 1</th>
                        <th style="border: 1px solid #000; padding: 4px; width: 10%;">Smt 2</th>
                        <th style="border: 1px solid #000; padding: 4px; width: 10%;">Smt 1</th>
                        <th style="border: 1px solid #000; padding: 4px; width: 10%;">Smt 2</th>
                    </tr>
                </thead>
               <tbody>
                    @if(isset($raportRows) && count($raportRows) > 0)
                        @foreach($raportRows as $index => $row)
                        <tr>
                            <td style="border: 1px solid #000; padding: 4px;">{{ $index + 1 }}</td>
                            <td style="border: 1px solid #000; padding: 4px; text-align: left;">{{ $row['name'] }}</td>
                            <td style="border: 1px solid #000; padding: 4px;">{{ $row['71'] }}</td>
                            <td style="border: 1px solid #000; padding: 4px;">{{ $row['72'] }}</td>
                            <td style="border: 1px solid #000; padding: 4px;">{{ $row['81'] }}</td>
                            <td style="border: 1px solid #000; padding: 4px;">{{ $row['82'] }}</td>
                            <td style="border: 1px solid #000; padding: 4px;">{{ $row['91'] }}</td>
                            <td style="border: 1px solid #000; padding: 4px;">{{ $row['92'] }}</td>
                        </tr>
                        @endforeach
                        
                        <!-- BARIS RATA-RATA NILAI -->
                        <tr>
                            <td colspan="2" style="border: 1px solid #000; padding: 4px; font-weight: bold; text-align: right; background-color: #f8fafc;">Rata-rata Nilai Raport</td>
                            <td style="border: 1px solid #000; padding: 4px; font-weight: bold; background-color: #f8fafc;">{{ $averages['71'] }}</td>
                            <td style="border: 1px solid #000; padding: 4px; font-weight: bold; background-color: #f8fafc;">{{ $averages['72'] }}</td>
                            <td style="border: 1px solid #000; padding: 4px; font-weight: bold; background-color: #f8fafc;">{{ $averages['81'] }}</td>
                            <td style="border: 1px solid #000; padding: 4px; font-weight: bold; background-color: #f8fafc;">{{ $averages['82'] }}</td>
                            <td style="border: 1px solid #000; padding: 4px; font-weight: bold; background-color: #f8fafc;">{{ $averages['91'] }}</td>
                            <td style="border: 1px solid #000; padding: 4px; font-weight: bold; background-color: #f8fafc;">{{ $averages['92'] }}</td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="8" style="border: 1px solid #000; padding: 15px; font-style: italic;">Data raport belum tersedia atau fitur tidak aktif.</td>
                        </tr>
                    @endif

                    <!-- BARIS REKAP ABSENSI TOTAL DI BAWAH RAPORT -->
                    <tr>
                        <td colspan="8" style="border-left: 1px solid #000; border-right: 1px solid #000; border-top: 2px solid #000; padding: 4px; font-weight: bold; text-align: left; background-color: #e2e8f0;">
                            REKAPITULASI KETIDAKHADIRAN (TOTAL SELAMA BERSEKOLAH)
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="border: 1px solid #000; padding: 4px; text-align: right;">Sakit</td>
                        <td colspan="6" style="border: 1px solid #000; padding: 4px; text-align: left; font-weight: bold;">{{ $attendanceStats['sakit'] ?? 0 }} Hari</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="border: 1px solid #000; padding: 4px; text-align: right;">Izin</td>
                        <td colspan="6" style="border: 1px solid #000; padding: 4px; text-align: left; font-weight: bold;">{{ $attendanceStats['izin'] ?? 0 }} Hari</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="border: 1px solid #000; padding: 4px; text-align: right;">Tanpa Keterangan (Alpa)</td>
                        <td colspan="6" style="border: 1px solid #000; padding: 4px; text-align: left; font-weight: bold;">{{ $attendanceStats['alfa'] ?? 0 }} Hari</td>
                    </tr>
                </tbody>
            </table>

           <!-- J. RIWAYAT KELAS (JEJAK AKADEMIK) -->
            <div class="header-section print-break-inside-avoid">I. RIWAYAT KELAS (JEJAK AKADEMIK)</div>
            <table class="table-induk print-break-inside-avoid" style="width: 100%; border: 1.5px solid #000; margin-bottom: 20px; text-align: center;">
                <thead style="background-color: #f8fafc;">
                    <tr>
                        <th style="border: 1px solid #000; padding: 6px; width: 5%;">No</th>
                        <th style="border: 1px solid #000; padding: 6px; width: 30%;">Tahun Ajaran</th>
                        <th style="border: 1px solid #000; padding: 6px; width: 35%;">Duduk di Kelas</th>
                        <th style="border: 1px solid #000; padding: 6px; width: 30%;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($student->classHistories->sortBy('academic_year') as $index => $history)
                    <tr>
                        <td style="border: 1px solid #000; padding: 4px;">{{ $index + 1 }}</td>
                        <td style="border: 1px solid #000; padding: 4px; font-weight: bold;">{{ $history->academic_year }}</td>
                        <td style="border: 1px solid #000; padding: 4px; font-weight: bold;">{{ $history->schoolClass->name ?? 'Kelas Dihapus' }}</td>
                        <td style="border: 1px solid #000; padding: 4px;">{{ $history->status ?? 'Naik Kelas' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="border: 1px solid #000; padding: 10px; font-style: italic; color: #64748b;">Belum ada catatan riwayat kelas untuk siswa ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

             <!-- TANDA TANGAN VALIDASI AWAL SISWA & ORTU -->
            <div class="print-break-inside-avoid" style="margin-top: 30px; display: flex; justify-content: space-between; align-items: flex-end; width: 100%;">
                <div style="width: 33%; text-align: center;">
                    <p style="margin: 0; font-size: 11px;">Mengetahui,</p>
                    <p style="margin: 0; font-size: 11px;">Orang Tua / Wali Siswa</p>
                    <div style="height: 60px;"></div>
                    <p style="margin: 0; font-weight: bold; text-decoration: underline; font-size: 11px;">( ......................................... )</p>
                </div>
                <div style="width: 33%; text-align: center;">
                    <p style="margin: 0; font-size: 11px;">Lakbok, ............................ 20...</p>
                    <p style="margin: 0; font-size: 11px;">Siswa yang Bersangkutan</p>
                    <div style="height: 60px;"></div>
                    <p style="margin: 0; font-weight: bold; text-decoration: underline; font-size: 11px;">{{ $student->name }}</p>
                </div>
            </div>
           
           <!-- AREA TANDA TANGAN FINAL (KEPALA SEKOLAH) -->
            <div class="print-break-inside-avoid" style="margin-top: 40px; display: flex; justify-content: space-between; align-items: flex-start; width: 100%;">
                
                <!-- KIRI: QR Code -->
                <div style="width: 50%; padding-left: 10px;">
                    @php
                        $portalUrl = route('portal.show', $student->id);
                        $qrUrl = "http://api.qrserver.com/v1/create-qr-code/?size=85x85&data=" . urlencode($portalUrl) . "&margin=0";
                    @endphp
                    <img src="{{ $qrUrl }}" style="width: 80px; height: 80px;" alt="QR Code">
                    <div style="margin-top: 8px; font-size: 7.5pt; color: #444; line-height: 1.2;">
                        <i>* Pindai QR Code untuk memverifikasi keaslian dokumen.</i>
                    </div>
                </div>
                
                <!-- KANAN: Tanda Tangan Kepsek -->
                <div style="width: 50%; text-align: center;">
                    <p style="margin: 0; font-size: 11px; font-weight: bold;">Mengetahui dan Memvalidasi Data:</p>
                    <p style="margin: 0; font-size: 11px;">Lakbok, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y') }}</p>
                    <p style="margin: 0; font-size: 11px;">Kepala Sekolah,</p> <br>
                    
                    <!-- Stempel TTE (Tanda Tangan Elektronik) -->
                    <div style="margin: 10px 0; display: flex; justify-content: center;">
                        <table style="border: 1.5px solid #1e293b; padding: 3px; border-radius: 4px; background-color: #f8fafc;">
                            <tr>
                                <td style="padding: 2px 6px; border-right: 1px solid #1e293b; vertical-align: middle;">
                                    <span style="font-family: Arial, sans-serif; font-size: 10pt; font-weight: 900; color: #1e293b;">TTE</span>
                                </td>
                                <td style="padding: 2px 8px; text-align: left; vertical-align: middle; line-height: 1.1;">
                                    <span style="font-family: Arial, sans-serif; font-size: 6.5pt; font-weight: bold; color: #1e293b; display: block;">Ditandatangani secara elektronik</span>
                                    <span style="font-family: Arial, sans-serif; font-size: 5.5pt; color: #475569;">Validasi Sistem Informasi (SIMADU)</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    
                    <p style="margin: 0; font-weight: bold; text-decoration: underline; font-size: 11px;">{{ isset($settings['principal_name']) ? strtoupper($settings['principal_name']) : 'TANTAN SUTANDI NUGRAHA, S.Si, M.Pd.' }}</p>
                    <p style="margin: 0; font-size: 11px;">NIP. {{ $settings['principal_nip'] ?? '198028032008011003' }}</p>
                </div>
            </div>
            
            <div class="clear"></div>
        </div> 
    </div>

</body>
</html>