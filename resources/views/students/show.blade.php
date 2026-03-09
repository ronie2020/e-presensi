<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Induk - {{ $student->name }}</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Phosphor Icons & Fonts -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8fafc; /* slate-50 */
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .ui-sans { font-family: 'Poppins', sans-serif; }
        
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

        /* KHUSUS PENGATURAN CETAK (PRINT) */
        @media print {
            @page {
                size: A4;
                margin: 10mm; 
            }
            body {
                background-color: white !important;
                margin: 0;
                padding: 0;
            }
            .no-print { display: none !important; }
            .print-break { page-break-inside: avoid; }
            .a4-paper-container {
                box-shadow: none !important;
                margin: 0 !important;
                padding: 0 !important;
                max-width: 100% !important;
            }
        }
    </style>
</head>
<body class="min-h-screen py-8 text-gray-900 ui-sans">

    <!-- TOOLBAR AKSI (Tidak tercetak) -->
    <div class="max-w-[210mm] mx-auto mb-6 flex flex-col sm:flex-row justify-between items-center gap-4 no-print bg-white p-4 rounded-2xl shadow-sm border border-slate-200 sticky top-4 z-50">
        <div>
            <h2 class="font-black text-slate-800 flex items-center gap-2">
                <i class="ph-bold ph-file-text text-blue-600 text-xl"></i> Lembar Buku Induk
            </h2>
            <p class="text-xs text-slate-500 font-medium ml-7">{{ $student->name }}</p>
        </div>

        <div class="flex flex-wrap gap-3 items-center">
            {{-- Tombol Kembali membawa parameter pencarian --}}
            <a href="{{ route('students.index', request()->query()) }}" class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition-colors shadow-sm flex items-center gap-2 group">
                <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali
            </a>
            
            {{-- Tombol Edit membawa parameter pencarian --}}
            <a href="{{ route('students.edit', array_merge(['student' => $student->id], request()->query())) }}" class="px-4 py-2.5 bg-amber-50 border border-amber-200 text-amber-700 rounded-xl shadow-sm text-xs font-bold hover:bg-amber-100 transition-colors flex items-center gap-2">
                <i class="ph-bold ph-pencil-simple text-sm"></i> Edit Data
            </a>
            
            {{-- Tombol Cetak Biru Tua (Blue-900) ala Index --}}
            <button onclick="window.print()" class="px-5 py-2.5 bg-blue-900 text-white font-bold rounded-xl hover:bg-blue-800 shadow-lg shadow-blue-900/30 transition-transform active:scale-95 flex items-center gap-2 text-xs">
                <i class="ph-bold ph-printer text-sm"></i> Cetak / PDF
            </button>
        </div>
    </div>

    <!-- KERTAS A4 (Area yang akan dicetak) -->
    <div class="a4-paper-container max-w-[210mm] mx-auto bg-white shadow-2xl shadow-slate-300/50 rounded-lg p-[15mm] min-h-[297mm] relative border border-slate-200 text-black">
        
        <div class="font-serif"> <!-- Memaksa font serif untuk bagian dalam dokumen cetak -->
            
            <!-- KOP -->
            <div class="text-center border-b-2 border-black pb-4 mb-4">
                <h1 class="text-xl font-bold uppercase tracking-wide">LEMBAR BUKU INDUK PESERTA DIDIK</h1>
                <h2 class="text-lg font-bold uppercase mt-1">SMP NEGERI 3 LAKBOK</h2>
                <p class="text-xs mt-1">Jalan Raya Lakbok, Kabupaten Ciamis, Jawa Barat</p>
            </div>

            <!-- INFO SISWA -->
            <div class="flex justify-between items-start mb-6">
                <div class="w-2/3">
                    <table class="w-full text-sm font-serif">
                        <tr>
                            <td class="w-36 font-bold py-1">Nomor Induk / NIS</td>
                            <td class="w-4 py-1">:</td>
                            <td class="py-1">{{ $student->nis ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="font-bold py-1">NISN</td>
                            <td class="py-1">:</td>
                            <td class="py-1">{{ $student->student_id }}</td>
                        </tr>
                        <tr>
                            <td class="font-bold py-1">Nama Peserta Didik</td>
                            <td class="py-1">:</td>
                            <td class="uppercase font-bold text-base py-1">{{ $student->name }}</td>
                        </tr>
                    </table>
                </div>
                <!-- Frame Foto Resmi -->
                <div class="w-[3cm] h-[4cm] border-2 border-gray-400 flex items-center justify-center bg-gray-50 p-1">
                    @if($student->photo_path)
                        <img src="{{ asset('storage/' . $student->photo_path) }}" class="w-full h-full object-cover grayscale" alt="Foto Resmi">
                    @else
                        <span class="text-[10px] text-gray-400 text-center font-sans font-bold uppercase">Pas Foto<br>3 x 4</span>
                    @endif
                </div>
            </div>

            <!-- A. PRIBADI -->
            <div class="header-section">A. KETERANGAN PRIBADI SISWA</div>
            <table class="table-induk">
                <tr>
                    <td class="label-col">1. Nama Lengkap</td>
                    <td class="separator">:</td>
                    <td class="value-col uppercase font-bold">{{ $student->name }}</td>
                </tr>
                <tr>
                    <td class="label-col">2. Nama Panggilan</td>
                    <td class="separator">:</td>
                    <td class="value-col">{{ $student->nickname ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label-col">3. Jenis Kelamin</td>
                    <td class="separator">:</td>
                    <td class="value-col">{{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                </tr>
                <tr>
                    <td class="label-col">4. Tempat dan Tanggal Lahir</td>
                    <td class="separator">:</td>
                    <td class="value-col">
                        {{ $student->pob ?? '.......' }}, 
                        {{ $student->dob ? \Carbon\Carbon::parse($student->dob)->translatedFormat('d F Y') : '.......' }}
                    </td>
                </tr>
                <tr>
                    <td class="label-col">5. Agama</td>
                    <td class="separator">:</td>
                    <td class="value-col">{{ $student->religion ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label-col">6. Kewarganegaraan</td>
                    <td class="separator">:</td>
                    <td class="value-col">{{ $student->citizenship ?? 'Indonesia' }}</td>
                </tr>
                <tr>
                    <td class="label-col">7. Anak ke</td>
                    <td class="separator">:</td>
                    <td class="value-col">{{ $student->birth_order ?? '...' }}</td>
                </tr>
                <tr>
                    <td class="label-col">8. Jumlah Saudara (Kandung/Tiri/Angkat)</td>
                    <td class="separator">:</td>
                    <td class="value-col">
                        {{ $student->siblings_count ?? '-' }} / 
                        {{ $student->step_siblings_count ?? '-' }} / 
                        {{ $student->adoptive_siblings_count ?? '-' }}
                    </td>
                </tr>
                <tr>
                    <td class="label-col">9. Status Yatim/Piatu</td>
                    <td class="separator">:</td>
                    <td class="value-col">{{ $student->orphan_status ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label-col">10. Bahasa Sehari-hari</td>
                    <td class="separator">:</td>
                    <td class="value-col">{{ $student->daily_language ?? '-' }}</td>
                </tr>
            </table>

            <!-- B. TEMPAT TINGGAL -->
            <div class="header-section">B. KETERANGAN TEMPAT TINGGAL</div>
            <table class="table-induk">
                <tr>
                    <td class="label-col">11. Alamat Peserta Didik</td>
                    <td class="separator">:</td>
                    <td class="value-col">{{ $student->address ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label-col">12. Nomor Telepon / HP</td>
                    <td class="separator">:</td>
                    <td class="value-col">{{ $student->phone ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label-col">13. Tinggal Dengan</td>
                    <td class="separator">:</td>
                    <td class="value-col">{{ $student->living_with ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label-col">14. Jarak ke Sekolah</td>
                    <td class="separator">:</td>
                    <td class="value-col">{{ $student->distance_to_school ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label-col">15. Transportasi ke Sekolah</td>
                    <td class="separator">:</td>
                    <td class="value-col">{{ $student->transport_mode ?? '-' }}</td>
                </tr>
            </table>

            <!-- C. KESEHATAN -->
            <div class="header-section">C. KETERANGAN KESEHATAN</div>
            <table class="table-induk">
                <tr>
                    <td class="label-col">16. Golongan Darah</td>
                    <td class="separator">:</td>
                    <td class="value-col">{{ $student->blood_type ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label-col">17. Penyakit Pernah Diderita</td>
                    <td class="separator">:</td>
                    <td class="value-col">{{ $student->history_disease ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label-col">18. Kelainan Jasmani</td>
                    <td class="separator">:</td>
                    <td class="value-col">{{ $student->physical_abnormalities ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label-col">19. Tinggi / Berat Badan</td>
                    <td class="separator">:</td>
                    <td class="value-col">{{ $student->height ?? '...' }} cm / {{ $student->weight ?? '...' }} kg</td>
                </tr>
            </table>

            <!-- D. PENDIDIKAN -->
            <div class="header-section">D. KETERANGAN PENDIDIKAN SEBELUMNYA</div>
            <table class="table-induk">
                <tr>
                    <td class="label-col">20. Asal Sekolah (SD/MI)</td>
                    <td class="separator">:</td>
                    <td class="value-col">{{ $student->school_origin ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label-col">21. No. Ijazah / Tanggal</td>
                    <td class="separator">:</td>
                    <td class="value-col">
                        {{ $student->prev_diploma_no ?? '-' }} 
                        @if($student->prev_exam_date) 
                            / {{ \Carbon\Carbon::parse($student->prev_exam_date)->format('d-m-Y') }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="label-col">22. Diterima di Sekolah ini Tgl</td>
                    <td class="separator">:</td>
                    <td class="value-col">
                        {{ $student->accepted_date ? \Carbon\Carbon::parse($student->accepted_date)->translatedFormat('d F Y') : '-' }}
                    </td>
                </tr>
                <tr>
                    <td class="label-col">23. Pindahan Dari Sekolah</td>
                    <td class="separator">:</td>
                    <td class="value-col">{{ $student->transfer_from_school ?? '-' }}</td>
                </tr>
            </table>

            <!-- E. ORANG TUA & WALI -->
            <div class="header-section print-break">E. KETERANGAN ORANG TUA & WALI</div>
            <table class="table-induk">
                <!-- AYAH -->
                <tr><td colspan="3" class="sub-header">Data Ayah Kandung</td></tr>
                <tr>
                    <td class="label-col">24. Nama Ayah</td>
                    <td class="separator">:</td>
                    <td class="value-col">{{ $student->father_name ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label-col">25. Tempat, Tanggal Lahir</td>
                    <td class="separator">:</td>
                    <td class="value-col">{{ $student->father_pob ?? '' }}, {{ $student->father_birth_year ? \Carbon\Carbon::parse($student->father_birth_year)->format('Y') : '' }}</td>
                </tr>
                <tr>
                    <td class="label-col">26. Pekerjaan</td>
                    <td class="separator">:</td>
                    <td class="value-col">{{ $student->father_job ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label-col">27. Penghasilan</td>
                    <td class="separator">:</td>
                    <td class="value-col">{{ $student->father_income ?? '-' }}</td>
                </tr>

                <!-- IBU -->
                <tr><td colspan="3" class="sub-header">Data Ibu Kandung</td></tr>
                <tr>
                    <td class="label-col">28. Nama Ibu</td>
                    <td class="separator">:</td>
                    <td class="value-col">{{ $student->mother_name ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label-col">29. Tempat, Tanggal Lahir</td>
                    <td class="separator">:</td>
                    <td class="value-col">{{ $student->mother_pob ?? '' }}, {{ $student->mother_birth_year ? \Carbon\Carbon::parse($student->mother_birth_year)->format('Y') : '' }}</td>
                </tr>
                <tr>
                    <td class="label-col">30. Pekerjaan</td>
                    <td class="separator">:</td>
                    <td class="value-col">{{ $student->mother_job ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label-col">31. Penghasilan</td>
                    <td class="separator">:</td>
                    <td class="value-col">{{ $student->mother_income ?? '-' }}</td>
                </tr>

                <!-- WALI -->
                <tr><td colspan="3" class="sub-header">Data Wali (Jika Ada)</td></tr>
                <tr>
                    <td class="label-col">32. Nama Wali</td>
                    <td class="separator">:</td>
                    <td class="value-col">{{ $student->guardian_name ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label-col">33. Tempat, Tanggal Lahir</td>
                    <td class="separator">:</td>
                    <td class="value-col">
                        {{ $student->guardian_pob ?? '' }}
                        @if($student->guardian_dob), {{ \Carbon\Carbon::parse($student->guardian_dob)->translatedFormat('d F Y') }} @endif
                    </td>
                </tr>
                <tr>
                    <td class="label-col">34. Kewarganegaraan</td>
                    <td class="separator">:</td>
                    <td class="value-col">{{ $student->guardian_citizenship ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label-col">35. Hubungan Keluarga</td>
                    <td class="separator">:</td>
                    <td class="value-col">{{ $student->guardian_relationship ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label-col">36. Pekerjaan</td>
                    <td class="separator">:</td>
                    <td class="value-col">{{ $student->guardian_job ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label-col">37. Alamat</td>
                    <td class="separator">:</td>
                    <td class="value-col">{{ $student->guardian_address ?? '-' }}</td>
                </tr>
            </table>

            <!-- F. MENINGGALKAN SEKOLAH -->
            <div class="header-section print-break">F. KETERANGAN MENINGGALKAN SEKOLAH</div>
            <table class="table-induk">
                <!-- TAMAT -->
                <tr><td colspan="3" class="sub-header">A. Tamat Belajar</td></tr>
                <tr>
                    <td class="label-col">38. Tanggal Tamat</td>
                    <td class="separator">:</td>
                    <td class="value-col">{{ $student->graduated_date ? \Carbon\Carbon::parse($student->graduated_date)->translatedFormat('d F Y') : '-' }}</td>
                </tr>
                <tr>
                    <td class="label-col">39. Nomor Ijazah</td>
                    <td class="separator">:</td>
                    <td class="value-col">{{ $student->graduated_diploma_no ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label-col">40. Melanjutkan Ke</td>
                    <td class="separator">:</td>
                    <td class="value-col">{{ $student->continuing_to_school ?? '-' }}</td>
                </tr>

                <!-- PINDAH -->
                <tr><td colspan="3" class="sub-header">B. Pindah Sekolah (Mutasi)</td></tr>
                <tr>
                    <td class="label-col">41. Tanggal Pindah</td>
                    <td class="separator">:</td>
                    <td class="value-col">{{ $student->leaving_date ? \Carbon\Carbon::parse($student->leaving_date)->translatedFormat('d F Y') : '-' }}</td>
                </tr>
                <tr>
                    <td class="label-col">42. Dari Kelas</td>
                    <td class="separator">:</td>
                    <td class="value-col">{{ $student->leaving_class ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label-col">43. Ke Sekolah</td>
                    <td class="separator">:</td>
                    <td class="value-col">{{ $student->leaving_to_school ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label-col">44. Alasan</td>
                    <td class="separator">:</td>
                    <td class="value-col">{{ $student->leaving_reason ?? '-' }}</td>
                </tr>

                <!-- PUTUS -->
                <tr><td colspan="3" class="sub-header">C. Putus Sekolah</td></tr>
                <tr>
                    <td class="label-col">45. Tanggal / Alasan</td>
                    <td class="separator">:</td>
                    <td class="value-col">
                        {{ $student->dropout_date ? \Carbon\Carbon::parse($student->dropout_date)->translatedFormat('d F Y') : '-' }} 
                        / {{ $student->dropout_reason ?? '-' }}
                    </td>
                </tr>
            </table>

            <!-- G. LAIN-LAIN -->
            <div class="header-section print-break">G. LAIN-LAIN</div>
            <table class="table-induk">
                <tr>
                    <td class="label-col">46. Beasiswa</td>
                    <td class="separator">:</td>
                    <td class="value-col">{{ $student->scholarship_info ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label-col">47. Catatan Penting</td>
                    <td class="separator">:</td>
                    <td class="value-col">{{ $student->general_notes ?? '-' }}</td>
                </tr>
            </table>

            <!-- TTD -->
            <div class="mt-12 flex justify-end text-sm print-break">
                <div class="text-center w-56 font-serif">
                    <p>Lakbok, {{ now()->translatedFormat('d F Y') }}</p>
                    <p class="mb-20">Kepala Sekolah,</p>
                    <p class="font-bold underline">( ........................................ )</p>
                    <p>NIP. ....................................</p>
                </div>
            </div>

        </div> <!-- End Font Serif Wrapper -->
    </div> <!-- End Kertas A4 -->

</body>
</html>