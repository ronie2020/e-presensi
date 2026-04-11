<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['student', 'semester', 'year', 'subjects', 'record']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['student', 'semester', 'year', 'subjects', 'record']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div class="relative w-full h-full font-serif text-slate-900">
    
    
    <div class="watermark absolute inset-0 pointer-events-none z-0" style="background-image: url('https://upload.wikimedia.org/wikipedia/commons/9/9c/Logo_Tut_Wuri_Handayani.png'); background-repeat: no-repeat; background-position: center; background-size: 400px; opacity: 0.03;"></div>
    
    <div class="relative z-10">
        
        <div class="text-center mb-6 border-b-4 border-double border-slate-900 pb-4">
            <div class="flex items-center justify-center gap-4 mb-2">
                
                <img src="<?php echo e(asset('images/logo.png')); ?>" class="h-20 w-auto object-contain" alt="Logo" onerror="this.style.display='none'">
            </div>
            <h2 class="text-xl font-bold uppercase tracking-wide font-sans text-slate-700">Pemerintah Kabupaten Ciamis</h2>
            <h1 class="text-2xl font-black uppercase tracking-wider font-sans mb-1">SMP Negeri 3 Lakbok</h1>
            <p class="text-xs text-slate-500 font-sans">Jl. Raya Lakbok, Kecamatan Lakbok, Kabupaten Ciamis - Jawa Barat</p>
        </div>

        <div class="text-center mb-6">
            <h3 class="text-lg font-bold uppercase underline decoration-2 underline-offset-4">Laporan Hasil Belajar</h3>
        </div>

        
        <div class="grid grid-cols-2 gap-x-12 text-sm mb-8 font-sans">
            <table class="w-full">
                <tr><td class="py-1 w-32 font-bold text-slate-600 align-top">Nama Siswa</td><td class="py-1 uppercase font-bold text-slate-900 align-top">: <?php echo e($student->name); ?></td></tr>
                <tr><td class="py-1 font-bold text-slate-600 align-top">NISN</td><td class="py-1 font-mono align-top">: <?php echo e($student->student_id); ?></td></tr>
            </table>
            <table class="w-full">
                <tr><td class="py-1 w-32 font-bold text-slate-600 align-top">Kelas</td><td class="py-1 align-top">: <?php echo e($student->schoolClass->name ?? '-'); ?></td></tr>
                <tr><td class="py-1 font-bold text-slate-600 align-top">Semester</td><td class="py-1 align-top">: <?php echo e($semester); ?> / <?php echo e($year); ?></td></tr>
            </table>
        </div>

        
        <div class="mb-8">
            <h3 class="font-bold mb-2 text-sm uppercase border-b border-slate-400 inline-block pb-1 font-sans">A. Nilai Akademik</h3>
            <table class="w-full border-collapse border border-slate-900 text-sm">
                <thead>
                    <tr class="bg-slate-100 print:bg-slate-200">
                        <th class="border border-slate-600 px-2 py-3 w-10 text-center">No</th>
                        <th class="border border-slate-600 px-3 py-3 text-left">Mata Pelajaran</th>
                        <th class="border border-slate-600 px-2 py-3 w-16 text-center">Nilai</th>
                        <th class="border border-slate-600 px-2 py-3 w-16 text-center">Predikat</th>
                        <th class="border border-slate-600 px-3 py-3 text-left">Capaian Kompetensi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php 
                            $grade = $subject->grade; 
                            // Zebra striping tipis untuk kemudahan baca
                            $rowClass = $index % 2 == 0 ? 'bg-white' : 'bg-slate-50/50 print:bg-white';
                        ?>
                        <tr class="<?php echo e($rowClass); ?> avoid-break">
                            <td class="border border-slate-600 px-2 py-2 text-center align-top"><?php echo e($index + 1); ?></td>
                            <td class="border border-slate-600 px-3 py-2 font-medium align-top"><?php echo e($subject->name); ?></td>
                            <td class="border border-slate-600 px-2 py-2 text-center font-bold text-slate-900 align-top"><?php echo e($grade ? $grade->score : '-'); ?></td>
                            <td class="border border-slate-600 px-2 py-2 text-center font-bold align-top"><?php echo e($grade ? $grade->predicate : '-'); ?></td>
                            <td class="border border-slate-600 px-3 py-2 text-xs leading-relaxed text-justify align-top text-slate-700 italic">
                                <?php echo e($grade && $grade->description ? $grade->description : '-'); ?>

                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8 avoid-break"> 
            
            
            <div>
                <h3 class="font-bold mb-2 text-sm uppercase border-b border-slate-400 inline-block pb-1 font-sans">B. Ekstrakurikuler</h3>
                <table class="w-full border-collapse border border-slate-900 text-sm">
                    <thead>
                        <tr class="bg-slate-100 print:bg-slate-200">
                            <th class="border border-slate-600 px-3 py-2 text-left">Kegiatan</th>
                            <th class="border border-slate-600 px-3 py-2 w-20 text-center">Predikat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $record->extracurriculars ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ex): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="border border-slate-600 px-3 py-2 align-top"><?php echo e($ex->activity_name); ?></td>
                                <td class="border border-slate-600 px-3 py-2 text-center font-bold align-top"><?php echo e($ex->score); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td class="border border-slate-600 px-3 py-2 align-top text-slate-400 italic">Tidak mengikuti</td>
                                <td class="border border-slate-600 px-3 py-2 text-center align-top">-</td>
                            </tr>
                            
                            <tr><td class="border border-slate-600 px-3 py-2">&nbsp;</td><td class="border border-slate-600 px-3 py-2"></td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            
            <div>
                <h3 class="font-bold mb-2 text-sm uppercase border-b border-slate-400 inline-block pb-1 font-sans">C. Ketidakhadiran</h3>
                <table class="w-full border-collapse border border-slate-900 text-sm">
                    <tr>
                        <td class="border border-slate-600 px-3 py-2">Sakit</td>
                        <td class="border border-slate-600 px-3 py-2 text-center font-bold w-24"><?php echo e($record->absent_s ?? '-'); ?> Hari</td>
                    </tr>
                    <tr>
                        <td class="border border-slate-600 px-3 py-2">Izin</td>
                        <td class="border border-slate-600 px-3 py-2 text-center font-bold"><?php echo e($record->absent_i ?? '-'); ?> Hari</td>
                    </tr>
                    <tr>
                        <td class="border border-slate-600 px-3 py-2">Tanpa Keterangan</td>
                        <td class="border border-slate-600 px-3 py-2 text-center font-bold"><?php echo e($record->absent_a ?? '-'); ?> Hari</td>
                    </tr>
                </table>
            </div>
        </div>

        
        <div class="mb-8 avoid-break">
            <h3 class="font-bold mb-2 text-sm uppercase border-b border-slate-400 inline-block pb-1 font-sans">D. Catatan Wali Kelas</h3>
            <div class="border border-slate-900 p-4 min-h-[80px] rounded-sm bg-white relative">
                <i class="ph-duotone ph-quotes text-3xl text-slate-100 absolute top-2 left-2"></i>
                <p class="text-sm italic text-slate-800 leading-relaxed relative z-10">
                    "<?php echo e($record->notes ?? 'Tetap semangat dalam belajar dan tingkatkan terus prestasimu.'); ?>"
                </p>
            </div>
        </div>

        
        <?php if(($semester == '2' || strtolower($semester) == 'genap') && isset($student->schoolClass->name)): ?>
            <div class="mb-10 border-2 border-double border-slate-900 p-4 text-center bg-slate-50 print:bg-white avoid-break">
                <p class="font-bold text-xs text-slate-500 mb-1 font-sans uppercase tracking-widest">Keputusan Rapat Dewan Guru</p>
                <p class="font-black text-lg uppercase tracking-wider font-sans text-slate-900">
                    NAIK KE KELAS <?php echo e(intval(preg_replace('/[^0-9]/', '', $student->schoolClass->name)) + 1); ?>

                </p>
            </div>
        <?php endif; ?>

        
        <div class="flex justify-between items-start text-sm mt-8 px-2 font-sans avoid-break">
            
            <div class="text-center w-64">
                <p class="mb-24 text-slate-600">Mengetahui,<br>Orang Tua/Wali</p>
                <div class="border-b border-slate-900 w-full mx-auto"></div>
            </div>
            
            
            <div class="text-center w-64">
                <p class="mb-2 text-slate-600">Lakbok, <?php echo e(now()->translatedFormat('d F Y')); ?></p>
                <p class="mb-24 text-slate-600">Wali Kelas</p>
                <p class="font-bold underline uppercase mb-1">Nama Wali Kelas</p>
                <p class="text-xs">NIP. ...........................</p>
            </div>
        </div>
        
        
        <div class="flex items-end justify-center mt-6 gap-8 font-sans avoid-break">
            
            <div class="mb-4 text-center hidden print:block">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=Validasi:<?php echo e($student->student_id); ?>-<?php echo e($semester); ?>-<?php echo e($year); ?>" 
                     alt="QR Validasi" 
                     class="w-20 h-20 opacity-90 mx-auto border border-slate-200 p-1 bg-white">
                <p class="text-[9px] text-slate-400 mt-1 uppercase tracking-wider">Dokumen Sah</p>
            </div>

            <div class="text-center w-64">
                <p class="mb-24 text-slate-600">Mengetahui,<br>Kepala Sekolah</p>
                <p class="font-bold underline uppercase mb-1">TANTAN SUTANDI NUGRAHA, S.Si, M.Pd.</p>
                <p class="text-xs">NIP. 19800101 200501 1 001</p>
            </div>
        </div>
    </div>
</div><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\components\report-content.blade.php ENDPATH**/ ?>