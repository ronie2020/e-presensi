<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Induk - <?php echo e($student->name); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            @page {
                size: A4;
                margin: 10mm; 
            }
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
                background-color: white;
            }
            .no-print {
                display: none !important;
            }
            .print-break {
                page-break-inside: avoid;
            }
            .shadow-lg {
                box-shadow: none !important;
            }
            .border {
                border: 1px solid #000 !important;
            }
        }
        
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
        .label-col {
            width: 35%;
            font-weight: bold;
        }
        .separator {
            width: 2%;
        }
        .value-col {
            width: 63%;
            border-bottom: 1px dotted #ccc;
        }
        .header-section {
            background-color: #f3f4f6;
            padding: 4px 8px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            margin-top: 10px;
            margin-bottom: 5px;
        }
        .sub-header {
            font-weight: bold;
            font-style: italic;
            color: #4b5563;
            padding-top: 6px;
            padding-bottom: 2px;
            text-decoration: underline;
        }
    </style>
</head>

<body class="bg-slate-100 min-h-screen p-8 font-serif text-gray-900">

    <!-- TOMBOL AKSI (Tidak tercetak) -->
    <div class="max-w-[210mm] mx-auto mb-6 flex justify-between items-center no-print">
        <a href="<?php echo e(route('students.index')); ?>" class="px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-sm font-bold text-blue-900 hover:bg-gray-50 transition">
            &larr; Kembali
        </a>
        <div class="flex gap-3">
            <a href="<?php echo e(route('students.edit', $student->id)); ?>" class="px-4 py-2 bg-yellow-50 border border-yellow-200 text-yellow-700 rounded-lg shadow-sm text-sm font-bold hover:bg-yellow-100 transition">
                Edit Data
            </a>
            
            <button onclick="window.print()" class="px-4 py-2 bg-blue-900 text-white rounded-lg shadow-sm text-sm font-bold hover:bg-blue-800 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak / PDF
            </button>
        </div>
    </div>

    <!-- KERTAS A4 -->
    <div class="max-w-[210mm] mx-auto bg-white shadow-xl p-[15mm] min-h-[297mm] relative">
        
        <!-- KOP -->
        <div class="text-center border-b-2 border-black pb-4 mb-4">
            <h1 class="text-xl font-bold uppercase tracking-wide">LEMBAR BUKU INDUK PESERTA DIDIK</h1>
            <h2 class="text-lg font-bold uppercase">SMP NEGERI 3 LAKBOK</h2>
            <p class="text-xs mt-1">Jalan Raya Lakbok, Kabupaten Ciamis, Jawa Barat</p>
        </div>

        <!-- INFO SISWA -->
        <div class="flex justify-between items-start mb-4">
            <div class="w-2/3">
                <table class="w-full text-sm">
                    <tr>
                        <td class="w-32 font-bold">Nomor Induk / NIS</td>
                        <td class="w-4">:</td>
                        <td><?php echo e($student->nis ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="font-bold">NISN</td>
                        <td>:</td>
                        <td><?php echo e($student->student_id); ?></td>
                    </tr>
                    <tr>
                        <td class="font-bold">Nama Peserta Didik</td>
                        <td>:</td>
                        <td class="uppercase font-bold"><?php echo e($student->name); ?></td>
                    </tr>
                </table>
            </div>
            <div class="w-28 h-36 border border-gray-300 flex items-center justify-center bg-gray-50">
                <?php if($student->photo_path): ?>
                    <img src="<?php echo e(asset('storage/' . $student->photo_path)); ?>" class="w-full h-full object-cover" alt="Foto">
                <?php else: ?>
                    <span class="text-[10px] text-gray-400 text-center">Pas Foto<br>3 x 4</span>
                <?php endif; ?>
            </div>
        </div>

        <!-- A. PRIBADI -->
        <div class="header-section">A. KETERANGAN PRIBADI SISWA</div>
        <table class="table-induk">
            <tr>
                <td class="label-col">1. Nama Lengkap</td>
                <td class="separator">:</td>
                <td class="value-col uppercase font-bold"><?php echo e($student->name); ?></td>
            </tr>
            <tr>
                <td class="label-col">2. Nama Panggilan</td>
                <td class="separator">:</td>
                <td class="value-col"><?php echo e($student->nickname ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="label-col">3. Jenis Kelamin</td>
                <td class="separator">:</td>
                <td class="value-col"><?php echo e($student->gender == 'L' ? 'Laki-laki' : 'Perempuan'); ?></td>
            </tr>
            <tr>
                <td class="label-col">4. Tempat dan Tanggal Lahir</td>
                <td class="separator">:</td>
                <td class="value-col">
                    <?php echo e($student->pob ?? '.......'); ?>, 
                    <?php echo e($student->dob ? $student->dob->translatedFormat('d F Y') : '.......'); ?>

                </td>
            </tr>
            <tr>
                <td class="label-col">5. Agama</td>
                <td class="separator">:</td>
                <td class="value-col"><?php echo e($student->religion ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="label-col">6. Kewarganegaraan</td>
                <td class="separator">:</td>
                <td class="value-col"><?php echo e($student->citizenship ?? 'Indonesia'); ?></td>
            </tr>
            <tr>
                <td class="label-col">7. Anak ke</td>
                <td class="separator">:</td>
                <td class="value-col"><?php echo e($student->birth_order ?? '...'); ?></td>
            </tr>
            <tr>
                <td class="label-col">8. Jumlah Saudara (Kandung/Tiri/Angkat)</td>
                <td class="separator">:</td>
                <td class="value-col">
                    <?php echo e($student->siblings_count ?? '-'); ?> / 
                    <?php echo e($student->step_siblings_count ?? '-'); ?> / 
                    <?php echo e($student->adoptive_siblings_count ?? '-'); ?>

                </td>
            </tr>
            <tr>
                <td class="label-col">9. Status Yatim/Piatu</td>
                <td class="separator">:</td>
                <td class="value-col"><?php echo e($student->orphan_status ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="label-col">10. Bahasa Sehari-hari</td>
                <td class="separator">:</td>
                <td class="value-col"><?php echo e($student->daily_language ?? '-'); ?></td>
            </tr>
        </table>

        <!-- B. TEMPAT TINGGAL -->
        <div class="header-section">B. KETERANGAN TEMPAT TINGGAL</div>
        <table class="table-induk">
            <tr>
                <td class="label-col">11. Alamat Peserta Didik</td>
                <td class="separator">:</td>
                <td class="value-col"><?php echo e($student->address ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="label-col">12. Nomor Telepon / HP</td>
                <td class="separator">:</td>
                <td class="value-col"><?php echo e($student->phone ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="label-col">13. Tinggal Dengan</td>
                <td class="separator">:</td>
                <td class="value-col"><?php echo e($student->living_with ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="label-col">14. Jarak ke Sekolah</td>
                <td class="separator">:</td>
                <td class="value-col"><?php echo e($student->distance_to_school ?? '-'); ?></td>
            </tr>
            
            <tr>
                <td class="label-col">15. Transportasi ke Sekolah</td>
                <td class="separator">:</td>
                <td class="value-col"><?php echo e($student->transport_mode ?? '-'); ?></td>
            </tr>
        </table>

        <!-- C. KESEHATAN -->
        <div class="header-section">C. KETERANGAN KESEHATAN</div>
        <table class="table-induk">
            
            <tr>
                <td class="label-col">16. Golongan Darah</td>
                <td class="separator">:</td>
                <td class="value-col"><?php echo e($student->blood_type ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="label-col">17. Penyakit Pernah Diderita</td>
                <td class="separator">:</td>
                <td class="value-col"><?php echo e($student->history_disease ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="label-col">18. Kelainan Jasmani</td>
                <td class="separator">:</td>
                <td class="value-col"><?php echo e($student->physical_abnormalities ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="label-col">19. Tinggi / Berat Badan</td>
                <td class="separator">:</td>
                <td class="value-col"><?php echo e($student->height ?? '...'); ?> cm / <?php echo e($student->weight ?? '...'); ?> kg</td>
            </tr>
        </table>

        <!-- D. PENDIDIKAN -->
        <div class="header-section">D. KETERANGAN PENDIDIKAN SEBELUMNYA</div>
        <table class="table-induk">
            <tr>
                <td class="label-col">20. Asal Sekolah (SD/MI)</td>
                <td class="separator">:</td>
                <td class="value-col"><?php echo e($student->prev_school_name ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="label-col">21. No. Ijazah / Tanggal</td>
                <td class="separator">:</td>
                <td class="value-col">
                    <?php echo e($student->prev_diploma_no ?? '-'); ?> 
                    <?php if($student->prev_exam_date): ?> 
                        / <?php echo e($student->prev_exam_date->format('d-m-Y')); ?>

                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td class="label-col">22. Diterima di Sekolah ini Tgl</td>
                <td class="separator">:</td>
                <td class="value-col">
                    <?php echo e($student->accepted_date ? $student->accepted_date->translatedFormat('d F Y') : '-'); ?>

                </td>
            </tr>
            <tr>
                <td class="label-col">23. Pindahan Dari Sekolah</td>
                <td class="separator">:</td>
                <td class="value-col"><?php echo e($student->transfer_from_school ?? '-'); ?></td>
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
                <td class="value-col"><?php echo e($student->father_name ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="label-col">25. Tempat, Tanggal Lahir</td>
                <td class="separator">:</td>
                <td class="value-col"><?php echo e($student->father_pob ?? ''); ?>, <?php echo e($student->father_birth_year ? \Carbon\Carbon::parse($student->father_birth_year)->format('Y') : ''); ?></td>
            </tr>
            <tr>
                <td class="label-col">26. Pekerjaan</td>
                <td class="separator">:</td>
                <td class="value-col"><?php echo e($student->father_job ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="label-col">27. Penghasilan</td>
                <td class="separator">:</td>
                <td class="value-col"><?php echo e($student->father_income ?? '-'); ?></td>
            </tr>

            <!-- IBU -->
            <tr><td colspan="3" class="sub-header">Data Ibu Kandung</td></tr>
            <tr>
                <td class="label-col">28. Nama Ibu</td>
                <td class="separator">:</td>
                <td class="value-col"><?php echo e($student->mother_name ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="label-col">29. Tempat, Tanggal Lahir</td>
                <td class="separator">:</td>
                <td class="value-col"><?php echo e($student->mother_pob ?? ''); ?>, <?php echo e($student->mother_birth_year ? \Carbon\Carbon::parse($student->mother_birth_year)->format('Y') : ''); ?></td>
            </tr>
            <tr>
                <td class="label-col">30. Pekerjaan</td>
                <td class="separator">:</td>
                <td class="value-col"><?php echo e($student->mother_job ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="label-col">31. Penghasilan</td>
                <td class="separator">:</td>
                <td class="value-col"><?php echo e($student->mother_income ?? '-'); ?></td>
            </tr>

            <!-- WALI -->
            <tr><td colspan="3" class="sub-header">Data Wali (Jika Ada)</td></tr>
            <tr>
                <td class="label-col">32. Nama Wali</td>
                <td class="separator">:</td>
                <td class="value-col"><?php echo e($student->guardian_name ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="label-col">33. Tempat, Tanggal Lahir</td>
                <td class="separator">:</td>
                <td class="value-col">
                    <?php echo e($student->guardian_pob ?? ''); ?>

                    <?php if($student->guardian_dob): ?>, <?php echo e(\Carbon\Carbon::parse($student->guardian_dob)->translatedFormat('d F Y')); ?> <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td class="label-col">34. Kewarganegaraan</td>
                <td class="separator">:</td>
                <td class="value-col"><?php echo e($student->guardian_citizenship ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="label-col">35. Hubungan Keluarga</td>
                <td class="separator">:</td>
                <td class="value-col"><?php echo e($student->guardian_relationship ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="label-col">36. Pekerjaan</td>
                <td class="separator">:</td>
                <td class="value-col"><?php echo e($student->guardian_job ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="label-col">37. Alamat</td>
                <td class="separator">:</td>
                <td class="value-col"><?php echo e($student->guardian_address ?? '-'); ?></td>
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
                <td class="value-col"><?php echo e($student->graduated_date ? $student->graduated_date->translatedFormat('d F Y') : '-'); ?></td>
            </tr>
            <tr>
                <td class="label-col">39. Nomor Ijazah</td>
                <td class="separator">:</td>
                <td class="value-col"><?php echo e($student->graduated_diploma_no ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="label-col">40. Melanjutkan Ke</td>
                <td class="separator">:</td>
                <td class="value-col"><?php echo e($student->continuing_to_school ?? '-'); ?></td>
            </tr>

            <!-- PINDAH -->
            <tr><td colspan="3" class="sub-header">B. Pindah Sekolah (Mutasi)</td></tr>
            <tr>
                <td class="label-col">41. Tanggal Pindah</td>
                <td class="separator">:</td>
                <td class="value-col"><?php echo e($student->leaving_date ? $student->leaving_date->translatedFormat('d F Y') : '-'); ?></td>
            </tr>
            <tr>
                <td class="label-col">42. Dari Kelas</td>
                <td class="separator">:</td>
                <td class="value-col"><?php echo e($student->leaving_class ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="label-col">43. Ke Sekolah</td>
                <td class="separator">:</td>
                <td class="value-col"><?php echo e($student->leaving_to_school ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="label-col">44. Alasan</td>
                <td class="separator">:</td>
                <td class="value-col"><?php echo e($student->leaving_reason ?? '-'); ?></td>
            </tr>

            <!-- PUTUS -->
            <tr><td colspan="3" class="sub-header">C. Putus Sekolah</td></tr>
            <tr>
                <td class="label-col">45. Tanggal / Alasan</td>
                <td class="separator">:</td>
                <td class="value-col">
                    <?php echo e($student->dropout_date ? $student->dropout_date->translatedFormat('d F Y') : '-'); ?> 
                    / <?php echo e($student->dropout_reason ?? '-'); ?>

                </td>
            </tr>
        </table>

        <!-- G. LAIN-LAIN -->
        <div class="header-section">G. LAIN-LAIN</div>
        <table class="table-induk">
            <tr>
                <td class="label-col">46. Beasiswa</td>
                <td class="separator">:</td>
                <td class="value-col"><?php echo e($student->scholarship_info ?? '-'); ?></td>
            </tr>
            <tr>
                <td class="label-col">47. Catatan Penting</td>
                <td class="separator">:</td>
                <td class="value-col"><?php echo e($student->general_notes ?? '-'); ?></td>
            </tr>
        </table>

        <!-- TTD -->
        <div class="mt-12 flex justify-end text-sm print-break">
            <div class="text-center w-48">
                <p>Lakbok, <?php echo e(now()->translatedFormat('d F Y')); ?></p>
                <p class="mb-16">Kepala Sekolah,</p>
                <p class="font-bold underline">( .................................... )</p>
                <p>NIP. ..............................</p>
            </div>
        </div>

    </div>

</body>
</html><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/students/show.blade.php ENDPATH**/ ?>