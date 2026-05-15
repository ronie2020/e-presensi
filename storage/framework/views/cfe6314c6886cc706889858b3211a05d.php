<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biodata Lengkap - <?php echo e($student->name); ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- INJEKSI TEMA MICROSOFT ELEVATE -->
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8fafc;
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
            background-color: #f1f5f9;
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

        .clear { clear: both; }

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
            .print-break-avoid { page-break-inside: avoid; }
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
<body class="min-h-screen py-8 text-elevate-text ui-sans relative">

    <!-- DEKORASI BACKGROUND -->
    <div class="fixed top-0 left-0 w-full h-64 bg-gradient-to-b from-elevate-primary/10 to-transparent pointer-events-none no-print -z-10"></div>

    <!-- TOOLBAR AKSI (KHUSUS PORTAL SISWA) -->
    <div class="max-w-[210mm] mx-auto mb-6 flex flex-col sm:flex-row justify-between items-center gap-4 no-print bg-white/80 backdrop-blur-md p-4 rounded-2xl shadow-lg border border-white/60 sticky top-4 z-50">
        <div>
            <h2 class="font-black text-elevate-dark flex items-center gap-2">
                <i class="ph-bold ph-identification-badge text-elevate-primary text-xl"></i> Biodata Lengkap
            </h2>
            <p class="text-xs text-slate-500 font-bold ml-7"><?php echo e($student->name); ?></p>
        </div>

        <div class="flex flex-wrap gap-3 items-center">
            <!-- LINK KEMBALI DIARAHKAN KE PORTAL SHOW, BUKAN ADMIN INDEX -->
            <a href="<?php echo e(route('portal.show', $student->id)); ?>" class="px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 flex items-center gap-2 group">
                <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali ke Profil
            </a>
            
            <!-- TOMBOL EDIT DATA DIHAPUS DEMI KEAMANAN -->
            
            <button onclick="window.print()" class="px-5 py-2.5 bg-elevate-primary text-white font-bold rounded-xl hover:bg-elevate-dark shadow-lg shadow-elevate-primary/30 transition-transform active:scale-95 flex items-center gap-2 text-xs group">
                <i class="ph-bold ph-printer text-sm group-hover:scale-110 transition-transform"></i> Cetak / Simpan PDF
            </button>
        </div>
    </div>

    <!-- KERTAS A4 (SAMA PERSIS DENGAN BUKU INDUK) -->
    <div class="a4-paper-container max-w-[210mm] mx-auto bg-white shadow-2xl rounded-lg p-[15mm] min-h-[297mm] relative border border-slate-200 text-black">
        
        <div class="font-serif"> 
            
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
                            <td class="py-1"><?php echo e($student->nis ?? '-'); ?></td>
                        </tr>
                        <tr>
                            <td class="font-bold py-1">NISN / NIK</td>
                            <td class="py-1">:</td>
                            <td class="py-1"><?php echo e($student->student_id); ?> / <?php echo e($student->nik ?? '-'); ?></td>
                        </tr>
                        <tr>
                            <td class="font-bold py-1">Nama Peserta Didik</td>
                            <td class="py-1">:</td>
                            <td class="uppercase font-bold text-base py-1"><?php echo e($student->name); ?></td>
                        </tr>
                    </table>
                </div>
                <div class="w-[3cm] h-[4cm] border-2 border-gray-400 flex items-center justify-center bg-gray-50 p-1">
                    <?php if($student->photo_path): ?>
                        <img src="<?php echo e(asset('storage/' . $student->photo_path)); ?>" class="w-full h-full object-cover grayscale" alt="Foto Resmi">
                    <?php else: ?>
                        <span class="text-[10px] text-gray-400 text-center font-sans font-bold uppercase">Pas Foto<br>3 x 4</span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- DATA PRIBADI - KESEHATAN -->
            <div class="header-section">A. KETERANGAN PRIBADI SISWA</div>
            <table class="table-induk">
                <tr><td class="label-col">1. Nama Lengkap</td><td class="separator">:</td><td class="value-col uppercase font-bold"><?php echo e($student->name); ?></td></tr>
                <tr><td class="label-col">2. Nama Panggilan</td><td class="separator">:</td><td class="value-col"><?php echo e($student->nickname ?? '-'); ?></td></tr>
                <tr><td class="label-col">3. Jenis Kelamin</td><td class="separator">:</td><td class="value-col"><?php echo e($student->gender == 'L' ? 'Laki-laki' : 'Perempuan'); ?></td></tr>
                <tr><td class="label-col">4. Tempat dan Tanggal Lahir</td><td class="separator">:</td><td class="value-col"><?php echo e($student->pob ?? '.......'); ?>, <?php echo e($student->dob ? \Carbon\Carbon::parse($student->dob)->translatedFormat('d F Y') : '.......'); ?></td></tr>
                <tr><td class="label-col">5. Agama</td><td class="separator">:</td><td class="value-col"><?php echo e($student->religion ?? '-'); ?></td></tr>
                <tr><td class="label-col">6. Kewarganegaraan</td><td class="separator">:</td><td class="value-col"><?php echo e($student->citizenship ?? 'Indonesia'); ?></td></tr>
                <tr><td class="label-col">7. Anak ke</td><td class="separator">:</td><td class="value-col"><?php echo e($student->birth_order ?? '...'); ?></td></tr>
                <tr><td class="label-col">8. Jumlah Saudara</td><td class="separator">:</td><td class="value-col"><?php echo e($student->siblings_count ?? '0'); ?> Kandung / <?php echo e($student->step_siblings_count ?? '0'); ?> Tiri / <?php echo e($student->adoptive_siblings_count ?? '0'); ?> Angkat</td></tr>
                <tr><td class="label-col">9. Status Yatim/Piatu</td><td class="separator">:</td><td class="value-col"><?php echo e($student->orphan_status ?? '-'); ?></td></tr>
                <tr><td class="label-col">10. Bahasa Sehari-hari</td><td class="separator">:</td><td class="value-col"><?php echo e($student->daily_language ?? '-'); ?></td></tr>
            </table>

            <div class="header-section">B. KETERANGAN TEMPAT TINGGAL</div>
            <table class="table-induk">
                <tr><td class="label-col">11. Alamat Peserta Didik</td><td class="separator">:</td><td class="value-col"><?php echo e($student->address ?? '-'); ?></td></tr>
                <tr><td class="label-col">12. Nomor Telepon / HP</td><td class="separator">:</td><td class="value-col"><?php echo e($student->phone ?? '-'); ?></td></tr>
                <tr><td class="label-col">13. Tinggal Dengan</td><td class="separator">:</td><td class="value-col"><?php echo e($student->living_with ?? '-'); ?></td></tr>
                <tr><td class="label-col">14. Jarak ke Sekolah</td><td class="separator">:</td><td class="value-col"><?php echo e($student->distance_to_school ?? '-'); ?></td></tr>
                <tr><td class="label-col">15. Transportasi ke Sekolah</td><td class="separator">:</td><td class="value-col"><?php echo e($student->transport_mode ?? '-'); ?></td></tr>
            </table>

            <div class="header-section">C. KETERANGAN KESEHATAN</div>
            <table class="table-induk">
                <tr><td class="label-col">16. Golongan Darah</td><td class="separator">:</td><td class="value-col"><?php echo e($student->blood_type ?? '-'); ?></td></tr>
                <tr><td class="label-col">17. Penyakit Pernah Diderita</td><td class="separator">:</td><td class="value-col"><?php echo e($student->history_disease ?? '-'); ?></td></tr>
                <tr><td class="label-col">18. Kelainan Jasmani</td><td class="separator">:</td><td class="value-col"><?php echo e($student->physical_abnormalities ?? '-'); ?></td></tr>
                <tr><td class="label-col">19. Tinggi / Berat Badan</td><td class="separator">:</td><td class="value-col"><?php echo e($student->height ?? '...'); ?> cm / <?php echo e($student->weight ?? '...'); ?> kg</td></tr>
            </table>

            <div class="header-section">D. KETERANGAN PENDIDIKAN SEBELUMNYA</div>
            <table class="table-induk">
                <tr><td class="label-col">20. Asal Sekolah Dasar (SD/MI)</td><td class="separator">:</td><td class="value-col"><?php echo e($student->school_origin ?? '-'); ?></td></tr>
                <tr><td class="label-col">21. No. Ijazah / Tanggal</td><td class="separator">:</td><td class="value-col"><?php echo e($student->prev_diploma_no ?? '-'); ?> <?php if($student->prev_exam_date): ?> / <?php echo e(\Carbon\Carbon::parse($student->prev_exam_date)->format('d-m-Y')); ?> <?php endif; ?></td></tr>
                <tr><td class="label-col">22. Diterima di Sekolah ini Tgl</td><td class="separator">:</td><td class="value-col"><?php echo e($student->accepted_date ? \Carbon\Carbon::parse($student->accepted_date)->translatedFormat('d F Y') : '-'); ?></td></tr>
                <tr><td class="label-col">23. Pindahan Dari Sekolah</td><td class="separator">:</td><td class="value-col"><?php echo e($student->transfer_from_school ?? '-'); ?></td></tr>
            </table>

            <!-- E. ORANG TUA -->
            <div class="header-section">E. KETERANGAN ORANG TUA & WALI</div>
            <table class="table-induk">
                <tr><td colspan="3" class="sub-header">Data Ayah Kandung</td></tr>
                <tr><td class="label-col">24. Nama Ayah</td><td class="separator">:</td><td class="value-col"><?php echo e($student->father_name ?? '-'); ?></td></tr>
                <tr><td class="label-col">25. Tempat, Tanggal Lahir</td><td class="separator">:</td><td class="value-col"><?php echo e($student->father_pob ?? ''); ?>, <?php echo e($student->father_birth_year ? $student->father_birth_year : ''); ?></td></tr>
                <tr><td class="label-col">26. Pendidikan Tertinggi</td><td class="separator">:</td><td class="value-col"><?php echo e($student->father_education ?? '-'); ?></td></tr>
                <tr><td class="label-col">27. Pekerjaan</td><td class="separator">:</td><td class="value-col"><?php echo e($student->father_job ?? '-'); ?></td></tr>
                <tr><td class="label-col">28. Penghasilan</td><td class="separator">:</td><td class="value-col"><?php echo e($student->father_income ?? '-'); ?></td></tr>

                <tr><td colspan="3" class="sub-header">Data Ibu Kandung</td></tr>
                <tr><td class="label-col">29. Nama Ibu</td><td class="separator">:</td><td class="value-col"><?php echo e($student->mother_name ?? '-'); ?></td></tr>
                <tr><td class="label-col">30. Tempat, Tanggal Lahir</td><td class="separator">:</td><td class="value-col"><?php echo e($student->mother_pob ?? ''); ?>, <?php echo e($student->mother_birth_year ? $student->mother_birth_year : ''); ?></td></tr>
                <tr><td class="label-col">31. Pendidikan Tertinggi</td><td class="separator">:</td><td class="value-col"><?php echo e($student->mother_education ?? '-'); ?></td></tr>
                <tr><td class="label-col">32. Pekerjaan</td><td class="separator">:</td><td class="value-col"><?php echo e($student->mother_job ?? '-'); ?></td></tr>
                <tr><td class="label-col">33. Penghasilan</td><td class="separator">:</td><td class="value-col"><?php echo e($student->mother_income ?? '-'); ?></td></tr>
                <tr><td class="label-col">34. No. Telepon / WA Ortu</td><td class="separator">:</td><td class="value-col"><?php echo e($student->parent_wa_number ?? '-'); ?></td></tr>

                <tr><td colspan="3" class="sub-header">Data Wali (Jika Ada)</td></tr>
                <tr><td class="label-col">35. Nama Wali</td><td class="separator">:</td><td class="value-col"><?php echo e($student->guardian_name ?? '-'); ?></td></tr>
                <tr><td class="label-col">38. Hubungan Keluarga</td><td class="separator">:</td><td class="value-col"><?php echo e($student->guardian_relationship ?? '-'); ?></td></tr>
                <tr><td class="label-col">41. No. Telepon / HP Wali</td><td class="separator">:</td><td class="value-col"><?php echo e($student->guardian_phone ?? '-'); ?></td></tr>
            </table>

            <!-- F, G, H (Satu kali saja) -->
            <div class="header-section print-break-avoid">F. KETERANGAN MENINGGALKAN SEKOLAH</div>
            <table class="table-induk">
                <tr><td colspan="3" class="sub-header">A. Tamat Belajar</td></tr>
                <tr><td class="label-col">42. Tanggal Tamat</td><td class="separator">:</td><td class="value-col"><?php echo e($student->graduated_date ? \Carbon\Carbon::parse($student->graduated_date)->translatedFormat('d F Y') : '-'); ?></td></tr>
                <tr><td class="label-col">43. Nomor Ijazah</td><td class="separator">:</td><td class="value-col"><?php echo e($student->graduated_diploma_no ?? '-'); ?></td></tr>

                <tr><td colspan="3" class="sub-header">B. Pindah Sekolah (Mutasi)</td></tr>
                <tr><td class="label-col">46. Tanggal Pindah</td><td class="separator">:</td><td class="value-col"><?php echo e($student->leaving_date ? \Carbon\Carbon::parse($student->leaving_date)->translatedFormat('d F Y') : '-'); ?></td></tr>
                <tr><td class="label-col">48. Ke Sekolah</td><td class="separator">:</td><td class="value-col"><?php echo e($student->leaving_to_school ?? '-'); ?></td></tr>
            </table>

            <div class="header-section print-break-avoid">G. LAIN-LAIN</div>
            <table class="table-induk">
                <tr><td class="label-col">51. Prestasi Siswa</td><td class="separator">:</td><td class="value-col"><?php echo e($student->achievements ?? '-'); ?></td></tr>
                <tr><td class="label-col">52. Beasiswa</td><td class="separator">:</td><td class="value-col"><?php echo e($student->scholarship_info ?? '-'); ?></td></tr>
                <tr><td class="label-col">53. Catatan Penting</td><td class="separator">:</td><td class="value-col"><?php echo e($student->general_notes ?? '-'); ?></td></tr>
            </table>

            <!-- H. RAPORT -->
            <div class="header-section" style="page-break-before: always; margin-top: 20px;">H. PRESTASI BELAJAR (NILAI RAPORT)</div>
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
                    <?php
                        $mapelInduk = \App\Models\Subject::orderBy('order')->get();
                        $no = 1;

                        // Siapkan array untuk menampung total nilai & jumlah mapel per semester
                        $totals = ['71' => 0, '72' => 0, '81' => 0, '82' => 0, '91' => 0, '92' => 0];
                        $counts = ['71' => 0, '72' => 0, '81' => 0, '82' => 0, '91' => 0, '92' => 0];
                    ?>

                    <?php $__currentLoopData = $mapelInduk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mapel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        // Tarik data nilai aslinya
                        $v71 = $student->getScore($mapel->name, 7, 1);
                        $v72 = $student->getScore($mapel->name, 7, 2);
                        $v81 = $student->getScore($mapel->name, 8, 1);
                        $v82 = $student->getScore($mapel->name, 8, 2);
                        $v91 = $student->getScore($mapel->name, 9, 1);
                        $v92 = $student->getScore($mapel->name, 9, 2);

                        // Cek jika nilainya angka (bukan '-' atau kosong), masukkan ke perhitungan total
                        if(is_numeric($v71)) { $totals['71'] += (float)$v71; $counts['71']++; }
                        if(is_numeric($v72)) { $totals['72'] += (float)$v72; $counts['72']++; }
                        if(is_numeric($v81)) { $totals['81'] += (float)$v81; $counts['81']++; }
                        if(is_numeric($v82)) { $totals['82'] += (float)$v82; $counts['82']++; }
                        if(is_numeric($v91)) { $totals['91'] += (float)$v91; $counts['91']++; }
                        if(is_numeric($v92)) { $totals['92'] += (float)$v92; $counts['92']++; }
                    ?>
                    <tr>
                        <td style="border: 1px solid #000; padding: 4px;"><?php echo e($no++); ?></td>
                        <td style="border: 1px solid #000; padding: 4px; text-align: left;"><?php echo e($mapel->name); ?></td>
                        <td style="border: 1px solid #000; padding: 4px;"><?php echo e($v71); ?></td>
                        <td style="border: 1px solid #000; padding: 4px;"><?php echo e($v72); ?></td>
                        <td style="border: 1px solid #000; padding: 4px;"><?php echo e($v81); ?></td>
                        <td style="border: 1px solid #000; padding: 4px;"><?php echo e($v82); ?></td>
                        <td style="border: 1px solid #000; padding: 4px;"><?php echo e($v91); ?></td>
                        <td style="border: 1px solid #000; padding: 4px;"><?php echo e($v92); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    
                    <!-- BARIS RATA-RATA NILAI -->
                    <tr>
                        <td colspan="2" style="border: 1px solid #000; padding: 4px; font-weight: bold; text-align: right; background-color: #f8fafc;">Rata-rata Nilai</td>
                        
                        <td style="border: 1px solid #000; padding: 4px; font-weight: bold; background-color: #f8fafc;">
                            <?php echo e($counts['71'] > 0 ? round($totals['71'] / $counts['71'], 1) : '-'); ?>

                        </td>
                        <td style="border: 1px solid #000; padding: 4px; font-weight: bold; background-color: #f8fafc;">
                            <?php echo e($counts['72'] > 0 ? round($totals['72'] / $counts['72'], 1) : '-'); ?>

                        </td>
                        
                        <td style="border: 1px solid #000; padding: 4px; font-weight: bold; background-color: #f8fafc;">
                            <?php echo e($counts['81'] > 0 ? round($totals['81'] / $counts['81'], 1) : '-'); ?>

                        </td>
                        <td style="border: 1px solid #000; padding: 4px; font-weight: bold; background-color: #f8fafc;">
                            <?php echo e($counts['82'] > 0 ? round($totals['82'] / $counts['82'], 1) : '-'); ?>

                        </td>
                        
                        <td style="border: 1px solid #000; padding: 4px; font-weight: bold; background-color: #f8fafc;">
                            <?php echo e($counts['91'] > 0 ? round($totals['91'] / $counts['91'], 1) : '-'); ?>

                        </td>
                        <td style="border: 1px solid #000; padding: 4px; font-weight: bold; background-color: #f8fafc;">
                            <?php echo e($counts['92'] > 0 ? round($totals['92'] / $counts['92'], 1) : '-'); ?>

                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- I. REKAP ABSENSI REAL-TIME (DARI SCANNER) -->
            <div class="header-section" style="margin-top: 20px;">I. AKUMULASI KEHADIRAN (SISTEM ABSENSI HARIAN)</div>
            <table class="table-induk" style="width: 50%; border: 1.5px solid #000; margin-bottom: 20px;">
                <tr>
                    <td class="label-col" style="border: 1px solid #000; padding: 4px;">Sakit</td>
                    <td style="border: 1px solid #000; padding: 4px; font-weight: bold; text-align: center;"><?php echo e($attendanceStats['sakit']); ?> Hari</td>
                </tr>
                <tr>
                    <td class="label-col" style="border: 1px solid #000; padding: 4px;">Izin</td>
                    <td style="border: 1px solid #000; padding: 4px; font-weight: bold; text-align: center;"><?php echo e($attendanceStats['izin']); ?> Hari</td>
                </tr>
                <tr>
                    <td class="label-col" style="border: 1px solid #000; padding: 4px;">Tanpa Keterangan (Alfa)</td>
                    <td style="border: 1px solid #000; padding: 4px; font-weight: bold; text-align: center;"><?php echo e($attendanceStats['alfa']); ?> Hari</td>
                </tr>
            </table>

            <!-- AREA TANDA TANGAN (MENGGUNAKAN FLEXBOX) -->
            <div class="print-break-avoid" style="margin-top: 40px; display: flex; justify-content: space-between; align-items: flex-start; width: 100%;">
                
                <!-- KIRI: QR Code -->
                <div style="width: 45%; padding-left: 10px;">
                    <?php
                        $portalUrl = route('portal.show', $student->id);
                        $qrUrl = "http://api.qrserver.com/v1/create-qr-code/?size=85x85&data=" . urlencode($portalUrl) . "&margin=0";
                    ?>
                    <img src="<?php echo e($qrUrl); ?>" style="width: 80px; height: 80px;" alt="QR Code">
                    <div style="margin-top: 8px; font-size: 7.5pt; color: #444; line-height: 1.2;">
                        <i>* Pindai QR Code untuk memverifikasi keaslian dokumen pada sistem SIMADU.</i>
                    </div>
                </div>
                
                <!-- KANAN: Tanda Tangan -->
                <div style="width: 45%; text-align: center;">
                    <p style="margin: 0; font-size: 11px;">Lakbok, <?php echo e(\Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y')); ?></p>
                    <p style="margin: 0; font-size: 11px;">Kepala Sekolah,</p>
                    
                    <!-- Stempel TTE -->
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
                    
                    <p style="margin: 0; font-weight: bold; text-decoration: underline; font-size: 11px;"><?php echo e(isset($settings['principal_name']) ? strtoupper($settings['principal_name']) : 'TANTAN SUTANDI NUGRAHA, S.Si, M.Pd.'); ?></p>
                    <p style="margin: 0; font-size: 11px;">NIP. <?php echo e($settings['principal_nip'] ?? '198028032008011003'); ?></p>
                </div>
            </div>
            <div class="clear"></div>
        </div> 
    </div>

</body>
</html><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/students/portal/biodata.blade.php ENDPATH**/ ?>