<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Soal - <?php echo e($title); ?></title>
    
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    
    <script>
        window.MathJax = { tex: { inlineMath: [['$', '$'], ['\\(', '\\)']] }, svg: { fontCache: 'global' } };
    </script>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>

    <style>
        /* PENGATURAN KERTAS F4 (Folio) */
        @page { 
            size: 21.5cm 33cm; 
            margin: 0; 
        }
        
        body {
            font-family: 'Times New Roman', serif;
            background-color: #f1f5f9; /* Slate-100 */
            -webkit-print-color-adjust: exact;
            color: #000;
        }

        /* TAMPILAN KERTAS DI LAYAR */
        .sheet {
            background: white;
            width: 21.5cm;
            min-height: 33cm;
            margin: 30px auto;
            padding: 1.5cm 2cm;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            position: relative;
        }

        /* MODIFIKASI SAAT DICETAK (PRINT MODE) */
        @media print {
            body { background: none; margin: 0; }
            .sheet { width: 100%; margin: 0; padding: 1.5cm 2cm; box-shadow: none; border: none; }
            .no-print { display: none !important; }
        }

        /* TYPOGRAPHY KOP SURAT */
        .header-text h3 { margin: 0; font-size: 14pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; line-height: 1.2; }
        .header-text h4 { margin: 0; font-size: 12pt; font-weight: bold; text-transform: uppercase; line-height: 1.2; }
        .header-text p { margin: 0; font-size: 10pt; line-height: 1.3; }
        
        .double-line { 
            border-top: 4px double #000; 
            margin-top: 8px; 
            margin-bottom: 24px; 
        }

        /* KUSTOMISASI LAYOUT SOAL */
        .question-container { page-break-inside: avoid; margin-bottom: 25px; }
        
        .question-meta { 
            font-size: 10pt; font-family: sans-serif;
            border-bottom: 1px dashed #cbd5e1; 
            padding-bottom: 6px; margin-bottom: 12px; 
            display: flex; justify-content: space-between; align-items: center;
        }
        .meta-badge { background: #f8fafc; padding: 2px 8px; border-radius: 4px; border: 1px solid #e2e8f0; font-size: 9pt; font-weight: bold; color: #475569; }

        .question-body { display: table; width: 100%; font-size: 12pt; }
        .question-number { display: table-cell; font-weight: bold; width: 35px; vertical-align: top; }
        .question-content { display: table-cell; vertical-align: top; }
        
        .question-content img { max-width: 300px; height: auto; max-height: 250px; display: block; margin: 10px 0; border: 1px solid #e2e8f0; border-radius: 8px; }
        .question-text p { margin-top: 0; line-height: 1.6; }
        
        /* Opsi Jawaban (Pilihan Ganda) */
        .options { margin-top: 12px; list-style-type: none; padding-left: 0; }
        .options li { margin-bottom: 10px; display: table; width: 100%; }
        .opt-label { display: table-cell; font-weight: bold; width: 30px; vertical-align: top; }
        .opt-content { display: table-cell; vertical-align: top; line-height: 1.5; }
        
        /* Indikator Kunci Jawaban */
        .correct-answer { font-weight: bold; color: #065f46; }
        .key-indicator { 
            color: #059669; font-family: sans-serif; font-weight: bold; font-size: 9pt; 
            background: #ecfdf5; padding: 2px 8px; border: 1px solid #a7f3d0; 
            border-radius: 6px; margin-left: 8px; display: inline-flex; items-center; gap: 4px; 
        }

        /* Tabel Menjodohkan */
        .matching-table { width: 100%; border-collapse: collapse; margin-top: 12px; font-size: 11pt; }
        .matching-table th, .matching-table td { border: 1px solid #94a3b8; padding: 8px; text-align: left; vertical-align: top; }
        .matching-table th { background-color: #f1f5f9; text-align: center; font-weight: bold; }

        /* Panduan Essai */
        .essay-key { margin-top: 12px; padding: 12px; background: #f8fafc; border-left: 4px solid #64748b; font-size: 11pt; border-radius: 0 8px 8px 0; }
    </style>
</head>
<body>

    <!-- TOOLBAR (Hanya Tampil di Layar) -->
    <div class="no-print fixed top-0 left-0 right-0 bg-white/90 backdrop-blur-md border-b border-slate-200 shadow-sm p-4 flex justify-between items-center z-50">
        <div class="flex items-center gap-4">
            <div class="bg-blue-900 p-2.5 rounded-xl text-white shadow-lg shadow-blue-900/20">
                <i class="ph-bold ph-files text-xl"></i>
            </div>
            <div>
                <h1 class="font-black text-slate-800 text-sm md:text-base font-sans">Pratinjau Laporan Butir Soal</h1>
                <p class="text-xs text-slate-500 font-sans font-bold"><?php echo e($title); ?></p>
            </div>
        </div>
        <div class="flex gap-3">
            <button onclick="window.close()" class="px-5 py-2.5 text-xs font-bold text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 hover:text-slate-800 transition shadow-sm font-sans flex items-center gap-2">
                <i class="ph-bold ph-x"></i> Tutup
            </button>
            <button onclick="window.print()" class="px-5 py-2.5 text-xs font-bold text-white bg-blue-900 rounded-xl hover:bg-blue-800 transition shadow-lg shadow-blue-900/30 font-sans flex items-center gap-2">
                <i class="ph-bold ph-printer"></i> Cetak Sekarang
            </button>
        </div>
    </div>

    <!-- Spacer untuk Toolbar -->
    <div class="no-print h-24"></div>

    <!-- HALAMAN KERTAS (KONTEN CETAK) -->
    <div class="sheet">
        
        <!-- KOP SURAT SEKOLAH (MENGGUNAKAN FLEXBOX AGAR LURUS) -->
        <div class="flex justify-between items-center pb-2">
            
            <div class="w-24 shrink-0 flex justify-start">
                <img src="<?php echo e(asset('img/logo_ciamis.png')); ?>" alt="Logo Daerah" 
                     class="w-20 h-auto object-contain"
                     onerror="this.style.display='none'"> 
            </div>
            
            
            <div class="text-center header-text flex-1 px-4">
                <h3>PEMERINTAH KABUPATEN CIAMIS</h3>
                <h3>DINAS PENDIDIKAN</h3>
                <h4>SMP NEGERI 3 LAKBOK</h4>
                <p>Jalan Mekarjaya No. 199 Sidaharja Kecamatan Lakbok Kabupaten Ciamis</p>
                <p>Laman: www.smpn3lakbok.sch.id &nbsp;&nbsp; E-mail: smpn3lakbok@gmail.com</p>
            </div>

            
            <div class="w-24 shrink-0 flex justify-end">
                <img src="<?php echo e(asset('img/logo_sekolah.png')); ?>" alt="Logo Sekolah" 
                     class="w-20 h-auto object-contain"
                     onerror="this.style.display='none'">
            </div>
        </div>
        
        <div class="double-line"></div>

        <!-- JUDUL LAPORAN -->
        <div class="text-center mb-6">
            <h2 class="m-0 text-[14pt] font-bold uppercase underline">Laporan Butir Soal (<?php echo e($type); ?>)</h2>
            <p class="text-[12pt] mt-1"><?php echo e($title); ?></p>
        </div>

        <!-- TABEL INFORMASI -->
        <table class="w-full mb-6 text-[11pt] border-collapse">
            <tr>
                <td class="w-[18%] py-1 font-bold">Mata Pelajaran</td>
                <td class="w-[2%] py-1">:</td>
                <td class="w-[40%] py-1"><?php echo e($subject); ?></td>
                
                <td class="w-[18%] py-1 font-bold">Jumlah Soal</td>
                <td class="w-[2%] py-1">:</td>
                <td class="w-[20%] py-1"><?php echo e($questions->count()); ?> Butir</td>
            </tr>
            <tr>
                <td class="py-1 font-bold">Keterangan</td>
                <td class="py-1">:</td>
                <td class="py-1"><?php echo e($info); ?></td>
                
                <td class="py-1 font-bold">Waktu Cetak</td>
                <td class="py-1">:</td>
                <td class="py-1"><?php echo e(date('d/m/Y H:i')); ?></td>
            </tr>
        </table>

        <div class="border-b-[3px] border-black mb-8"></div>

        <!-- DAFTAR SOAL -->
        <?php $__empty_1 = true; $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $qType = $q->question_type ?? 'choice';
                $opts = is_string($q->options) ? json_decode($q->options, true) : ($q->options ?? []);
                
                $typeLabel = 'Pilihan Ganda';
                if($qType == 'true_false') $typeLabel = 'Benar / Salah';
                elseif($qType == 'matching') $typeLabel = 'Menjodohkan';
                elseif($qType == 'essay') $typeLabel = 'Isian / Essai';
            ?>
            
            <div class="question-container">
                
                <div class="question-meta">
                    <div class="flex gap-2 items-center">
                        <span class="meta-badge">Tipe: <?php echo e($typeLabel); ?></span>
                        <span class="meta-badge bg-indigo-50 border-indigo-100 text-indigo-700">Materi/KD: <?php echo e(!empty($q->tags) ? $q->tags : '-'); ?></span>
                    </div>
                    <div>
                        <span class="meta-badge bg-amber-50 border-amber-100 text-amber-700">Bobot: <?php echo e($q->score_weight); ?> Poin</span>
                    </div>
                </div>

                <div class="question-body">
                    <div class="question-number"><?php echo e($index + 1); ?>.</div>
                    <div class="question-content">
                        
                        
                        <?php if($q->question_image): ?>
                            <img src="<?php echo e(asset('storage/' . $q->question_image)); ?>" alt="Gambar Soal">
                        <?php endif; ?>
                        
                        
                        <div class="question-text"><?php echo $q->question_text; ?></div>
                        
                        
                        <?php if($qType == 'choice'): ?>
                            <ul class="options">
                                <?php $__currentLoopData = ['A','B','C','D','E']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if(isset($opts[$opt]) || isset($opts['image_'.$opt])): ?>
                                        <li class="<?php echo e($q->correct_answer == $opt ? 'correct-answer' : ''); ?>">
                                            <div class="opt-label"><?php echo e($opt); ?>.</div> 
                                            <div class="opt-content">
                                                <?php if(isset($opts[$opt])): ?> <span><?php echo e($opts[$opt]); ?></span> <?php endif; ?>
                                                
                                                <?php if(isset($opts['image_'.$opt])): ?>
                                                    <img src="<?php echo e(asset('storage/' . $opts['image_'.$opt])); ?>">
                                                <?php endif; ?>
                                                
                                                <?php if($q->correct_answer == $opt): ?> 
                                                    <span class="key-indicator">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 256 256"><path d="M229.66,77.66l-128,128a8,8,0,0,1-11.32,0l-56-56a8,8,0,0,1,11.32-11.32L96,188.69,218.34,66.34a8,8,0,0,1,11.32,11.32Z"></path></svg>
                                                        Kunci Jawaban
                                                    </span> 
                                                <?php endif; ?>
                                            </div>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        
                        
                        <?php elseif($qType == 'true_false'): ?>
                            <ul class="options">
                                <li class="<?php echo e($q->correct_answer == 'A' ? 'correct-answer' : ''); ?>">
                                    <div class="opt-label">A.</div>
                                    <div class="opt-content">Benar 
                                        <?php if($q->correct_answer == 'A'): ?> <span class="key-indicator"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 256 256"><path d="M229.66,77.66l-128,128a8,8,0,0,1-11.32,0l-56-56a8,8,0,0,1,11.32-11.32L96,188.69,218.34,66.34a8,8,0,0,1,11.32,11.32Z"></path></svg> Kunci</span> <?php endif; ?>
                                    </div>
                                </li>
                                <li class="<?php echo e($q->correct_answer == 'B' ? 'correct-answer' : ''); ?>">
                                    <div class="opt-label">B.</div>
                                    <div class="opt-content">Salah 
                                        <?php if($q->correct_answer == 'B'): ?> <span class="key-indicator"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 256 256"><path d="M229.66,77.66l-128,128a8,8,0,0,1-11.32,0l-56-56a8,8,0,0,1,11.32-11.32L96,188.69,218.34,66.34a8,8,0,0,1,11.32,11.32Z"></path></svg> Kunci</span> <?php endif; ?>
                                    </div>
                                </li>
                            </ul>
                        
                        
                        <?php elseif($qType == 'matching'): ?>
                            <table class="matching-table">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="45%">Pernyataan (Kiri)</th>
                                        <th width="50%">Kunci Pasangan (Kanan)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(isset($opts['pairs'])): ?>
                                        <?php $__currentLoopData = $opts['pairs']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $pair): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td align="center"><?php echo e($idx + 1); ?></td>
                                                <td>
                                                    <?php if(isset($pair['left_image'])): ?> <img src="<?php echo e(asset('storage/' . $pair['left_image'])); ?>" style="max-width: 150px; max-height: 100px;"> <?php endif; ?>
                                                    <?php echo e($pair['left'] ?? ''); ?>

                                                </td>
                                                <td>
                                                    <?php if(isset($pair['right_image'])): ?> <img src="<?php echo e(asset('storage/' . $pair['right_image'])); ?>" style="max-width: 150px; max-height: 100px;"> <?php endif; ?>
                                                    <?php echo e($pair['right'] ?? ''); ?>

                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        
                        
                        <?php elseif($qType == 'essay'): ?>
                            <div class="essay-key">
                                <b class="text-slate-800">Kunci Jawaban / Panduan Koreksi:</b><br>
                                <span class="text-slate-600"><?php echo e($q->correct_answer ?: '(Koreksi secara manual / kebijaksanaan guru)'); ?></span>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="text-center p-10 text-slate-500">
                <h3>Paket Soal Masih Kosong</h3>
            </div>
        <?php endif; ?>

    </div>
</body>
</html><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/cbt/print_questions.blade.php ENDPATH**/ ?>