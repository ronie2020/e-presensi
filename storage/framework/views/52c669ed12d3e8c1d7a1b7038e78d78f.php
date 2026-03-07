<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Kartu Massal - <?php echo e($className ?? 'Semua Kelas'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /* Pengaturan Kertas A4 Landscape */
        @page {
            size: A4 landscape;
            margin: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #cbd5e1;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px 0;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Container Halaman A4 */
        .a4-page {
            width: 297mm;
            height: 210mm;
            background: white;
            margin-bottom: 20px;
            /* Padding diset agar tepat berada di tengah A4 */
            padding: 19.4mm 13.5mm; 
            box-sizing: border-box;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            display: grid;
            grid-template-columns: repeat(5, 54mm);
            grid-template-rows: repeat(2, 85.6mm);
            page-break-after: always;
            position: relative;
        }

        /* Base Card ID */
        .id-card {
            width: 54mm;
            height: 85.6mm;
            background-color: #fcfcfc;
            background-image: url("https://www.transparenttextures.com/patterns/cream-paper.png");
            position: relative;
            overflow: hidden;
            border: 0.5px dashed #cbd5e1; /* Garis potong (Cutting Guide) */
            display: flex;
            flex-direction: column;
        }

        /* CSS KHUSUS ELEMEN KARTU (Diadaptasi dari osis_card.blade.php) */
        .header-wave { position: absolute; top: -25px; left: -10%; width: 120%; height: 75px; background-color: #22c55e; border-radius: 0 0 50% 50%; z-index: 1; }
        .header-wave-accent { position: absolute; top: -28px; left: -10%; width: 120%; height: 80px; background-color: #86efac; border-radius: 0 0 50% 50%; z-index: 0; }
        .logo-left-group { position: absolute; top: 6px; left: 6px; z-index: 20; display: flex; gap: 4px; }
        .school-logo-center { position: absolute; top: 22px; left: 50%; transform: translateX(-50%); z-index: 10; background: white; padding: 2px; border-radius: 50%; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .card-header-text { margin-top: 70px; text-align: center; z-index: 10; padding: 0 4px; margin-bottom: 8px; }
        .student-photo-frame { width: 80px; height: 80px; border-radius: 50%; border: 3px solid #22c55e; overflow: hidden; margin: 0 auto; background: #f3f4f6; flex-shrink: 0; }
        .student-info-container { flex: 1; display: flex; flex-direction: column; justify-content: flex-start; padding-top: 6px; z-index: 10; }
        .data-table { width: 100%; font-size: 8px; font-weight: 600; color: #1f2937; margin-top: 6px; line-height: 1.4; }
        .data-table td { padding: 0 2px; vertical-align: top; }
        .data-label { width: 32px; }
        .data-separator { width: 6px; text-align: center; }
        .footer-bar { width: 100%; height: 28px; background-color: #15803d; display: flex; align-items: center; justify-content: space-evenly; padding: 0 5px; z-index: 20; margin-top: auto; }
        .social-item { display: flex; align-items: center; gap: 3px; color: white; font-size: 5px; font-weight: 600; }
        .social-icon { width: 9px; height: 9px; background-color: white; border-radius: 2px; padding: 1px; display: flex; align-items: center; justify-content: center; }
        .wave-decoration-back { position: absolute; z-index: 0; }

        @media print {
            body { background-color: white; padding: 0; }
            .a4-page { margin: 0; box-shadow: none; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

    <!-- TOOLBAR (Tidak ikut tercetak) -->
    <div class="no-print sticky top-0 z-50 w-full bg-white border-b border-slate-200 px-6 py-4 shadow-sm flex justify-between items-center mb-6">
        <div>
            <h1 class="font-black text-slate-800 text-lg">Cetak Kartu Massal</h1>
            <p class="text-xs text-slate-500 font-medium">Dicetak berdasarkan: <span class="text-blue-600 font-bold"><?php echo e($className ?? 'Semua Kelas'); ?></span> (<?php echo e($students->count()); ?> Siswa)</p>
        </div>
        <div class="flex gap-3 items-center">
            <p class="text-[10px] font-bold text-amber-600 bg-amber-50 border border-amber-200 px-3 py-1.5 rounded-lg">
                <i class="ph-bold ph-warning-circle"></i> Info Printer: Gunakan mode "Landscape" & Flip on Short Edge (Bolak-Balik)
            </p>
            <button onclick="window.close()" class="px-5 py-2.5 bg-slate-100 text-slate-600 font-bold rounded-xl text-sm hover:bg-slate-200 transition-colors">Tutup</button>
            <button onclick="window.print()" class="px-5 py-2.5 bg-blue-600 text-white font-bold rounded-xl text-sm hover:bg-blue-700 shadow-lg shadow-blue-500/30 flex items-center gap-2 transition-transform active:scale-95">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Mulai Cetak
            </button>
        </div>
    </div>

    <!-- RENDERING KARTU -->
    <?php $__currentLoopData = $students->chunk(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chunkIndex => $chunk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        
        
        <div class="a4-page">
            <?php $__currentLoopData = $chunk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="id-card">
                    <!-- Gelombang Header -->
                    <div class="header-wave-accent"></div>
                    <div class="header-wave"></div>

                    <!-- Logo Kiri Atas -->
                    <div class="logo-left-group">
                         <img src="<?php echo e(asset('images/tut_wuri.png')); ?>" onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/b/b3/Logo_Tut_Wuri_Handayani.svg'" class="h-6 w-auto drop-shadow-sm filter brightness-110">
                         <img src="<?php echo e(asset('images/ciamis.png')); ?>" onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/thumb/a/a6/Coat_of_arms_of_Ciamis_Regency.svg/1200px-Coat_of_arms_of_Ciamis_Regency.svg.png'" class="h-6 w-auto drop-shadow-sm">
                    </div>

                    <!-- Logo Sekolah Tengah -->
                    <div class="school-logo-center">
                        <img src="<?php echo e(asset('images/logo.png')); ?>" onerror="this.src='https://via.placeholder.com/100?text=LOGO'" class="h-9 w-9 object-contain">
                    </div>

                    <!-- Judul Kartu -->
                    <div class="card-header-text">
                        <h1 class="text-[13px] font-black text-slate-900 tracking-wide uppercase leading-tight">KARTU PELAJAR</h1>
                        <h2 class="text-[8px] font-bold text-slate-600 uppercase tracking-wider">SMP NEGERI 3 LAKBOK</h2>
                    </div>

                    <!-- Foto Siswa -->
                    <div class="w-full flex justify-center mb-1">
                        <div class="student-photo-frame">
                            <?php if($student->photo_path): ?>
                                <img src="<?php echo e(asset('storage/' . $student->photo_path)); ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($student->name)); ?>&background=10b981&color=fff&size=200" class="w-full h-full object-cover">
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Info Siswa -->
                    <div class="student-info-container items-center px-4">
                        <div class="text-center w-full mb-1">
                            <div class="inline-block border-b-2 border-slate-900 pb-0.5">
                                <h3 class="text-[10px] font-black text-slate-900 uppercase leading-none">
                                    <?php echo e(\Illuminate\Support\Str::limit($student->name, 22)); ?>

                                </h3>
                            </div>
                            <p class="text-[8px] font-bold text-slate-600 uppercase tracking-widest mt-0.5">SISWA</p>
                        </div>

                        <div class="w-full px-4">
                            <table class="data-table">
                                <tr>
                                    <td class="data-label">NISN</td>
                                    <td class="data-separator">:</td>
                                    <td class="font-bold font-mono"><?php echo e($student->student_id); ?></td>
                                </tr>
                                <tr>
                                    <td class="data-label">TTL</td>
                                    <td class="data-separator">:</td>
                                    <td class="font-bold leading-tight">
                                        <?php echo e($student->pob); ?>, <?php echo e($student->dob ? $student->dob->translatedFormat('d M Y') : '-'); ?>

                                    </td>
                                </tr>                               
                            </table>
                        </div>
                    </div>

                    <!-- Footer Bar -->
                    <div class="footer-bar">
                        <div class="social-item"><div class="social-icon"><svg class="w-2 h-2 text-green-700" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg></div><span>Netila Channel</span></div>
                        <div class="social-item"><div class="social-icon"><svg class="w-2 h-2 text-green-700" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm1 16.057v-3.057h2.994c-.059 1.143-.212 2.183-.442 3.057h-2.552zm-6.123-3.057h2.123v3.057c-1.223-.604-2.075-1.74-2.123-3.057zm-1.877 0c.05 2.169 1.488 4.008 3.447 4.789-1.096-1.328-1.996-2.951-2.434-4.789h-1.013zm8.987-13.943c2.17.05 4.009 1.488 4.789 3.447-1.328-1.096-2.951-1.996-4.789-2.434v-1.013zm-3.987-1.013v1.013c-1.838.438-3.461 1.338-4.789 2.434.78-1.959 2.619-3.397 4.789-3.447zm-1 4.071c1.143-.059 2.183-.212 3.057-.442v2.552h-3.057v-2.11zm0 3.11h3.057v2.886h-3.057v-2.886zm-2-2.11v2.11h-3.057v-2.552c.874.23 1.914.383 3.057.442zm-3.057 5.996h3.057v2.886h-3.057v-2.886zm4.057 2.886v-2.886h3.057v2.886h-3.057zm-5.434-1.886h1.013c.438-1.838 1.338-3.461 2.434-4.789-1.959.781-3.397 2.62-3.447 4.789zm1.421-6.886h1.013c-.438 1.838-1.338 3.461-2.434 4.789 1.959-.78-3.397-2.619-3.447-4.789zm9.013-1.013c-.048-1.317-.9-2.453-2.123-3.057v3.057h2.123zm2.123-3.057c-1.223.604-2.075 1.74-2.123 3.057h2.123v-3.057zm1.89 3.057h1.013c-.438-1.838-1.338-3.461-2.434-4.789 1.959.78 3.397 2.619 3.447 4.789zm-1.013 7.886c.438-1.838 1.338-3.461 2.434-4.789-1.959.781-3.397 2.62-3.447 4.789h1.013zm-2.987 5.071c1.838-.438 3.461-1.338 4.789-2.434-.78 1.959-2.619 3.397-4.789 3.447v-1.013z"/></svg></div><span>smpn3.lakbok.sch.id</span></div>
                        <div class="social-item"><div class="social-icon"><svg class="w-2 h-2 text-green-700" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></div><span>smpnegeri3lakbok</span></div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        
        <?php
            $backs = collect();
            $chunkArray = $chunk->values()->all(); // Reset index jadi 0-9
            
            // Pencerminan Baris 1 (Indeks 0-4 dicerminkan jadi 4-0)
            for($i=4; $i>=0; $i--) {
                $backs->push(isset($chunkArray[$i]) ? $chunkArray[$i] : null);
            }
            // Pencerminan Baris 2 (Indeks 5-9 dicerminkan jadi 9-5)
            for($i=9; $i>=5; $i--) {
                $backs->push(isset($chunkArray[$i]) ? $chunkArray[$i] : null);
            }
        ?>

        <div class="a4-page">
            <?php $__currentLoopData = $backs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($student): ?>
                    <div class="id-card items-center justify-center">
                        <!-- Gelombang Belakang -->
                        <div class="wave-decoration-back absolute top-[-20px] left-[-20px] w-[100px] h-[60px] bg-green-400 rounded-br-[100%] transform -rotate-12 z-0"></div>
                        <div class="wave-decoration-back absolute top-[-25px] left-[-25px] w-[110px] h-[70px] bg-green-600 rounded-br-[100%] transform -rotate-12 -z-10"></div>
                        <div class="wave-decoration-back absolute bottom-[-20px] right-[-20px] w-[120px] h-[60px] bg-green-700 rounded-tl-[100%] z-0"></div>

                        <div class="z-10 flex flex-col items-center w-full px-6 flex-1 justify-center mt-6">
                            <!-- QR Code Box -->
                            <div class="bg-white p-1 rounded-lg border border-slate-200 mb-2 shadow-sm">
                                <?php echo QrCode::size(100)->margin(0)->generate($student->student_id); ?>

                            </div>
                            
                            <!-- Nama & NISN (Mencegah Kartu Tertukar) -->
                            <div class="text-center mb-3 w-full px-1">
                                <p class="text-[8px] font-black text-slate-900 uppercase leading-tight"><?php echo e(\Illuminate\Support\Str::limit($student->name, 22)); ?></p>
                                <p class="text-[6px] font-bold text-slate-600 font-mono tracking-wide mt-0.5"><?php echo e($student->student_id); ?></p>
                            </div>

                            <!-- Tata Tertib -->
                            <div class="w-full">
                                <ul class="text-[6px] text-slate-800 font-bold list-disc pl-4 space-y-1.5 text-justify leading-relaxed">
                                    <li>Kartu ini wajib dibawa selama berada di lingkungan sekolah.</li>
                                    <li>Laporkan segera jika kartu hilang atau rusak.</li>
                                    <li>Kartu ini bukan alat tukar.</li>
                                    <li>Dilarang menyalahgunakan kartu ini.</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Footer Bar Back -->
                         <div class="footer-bar !justify-center">
                            <span class="text-[7px] font-bold text-white opacity-90 tracking-wider">MENCERDASKAN KEHIDUPAN BANGSA</span>
                        </div>
                    </div>
                <?php else: ?>
                    
                    <div class="id-card" style="visibility: hidden;"></div>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</body>
</html><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/students/osis_card_batch.blade.php ENDPATH**/ ?>