<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    
    <?php
        $schoolName = "SMP NEGERI 3 LAKBOK";
        $schoolAddress = "Jl. Raya Lakbok, Kecamatan Lakbok, Kabupaten Ciamis - Jawa Barat";
        $principalName = "TANTAN SUTANDI NUGRAHA, S.Si, M.Pd.";
        $principalNIP  = "19800101 200501 1 001";
        $printDate     = \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y');
    ?>

    <?php $__env->startPush('styles'); ?>
    <style>
        /* --- LOGIKA PRINT OPTIMIZED --- */
        @media print {
            /* Sembunyikan semua elemen body */
            body * { visibility: hidden; }
            
            /* Tampilkan hanya container print & isinya */
            .print-container, .print-container * { 
                visibility: visible; 
            }
            
            /* Reset posisi container agar pas di kertas A4 */
            .print-container {
                position: absolute !important; 
                left: 0 !important; 
                top: 0 !important;
                width: 100% !important; 
                margin: 0 !important; 
                padding: 20px !important; /* Tambah padding kertas */
                background: white !important; 
                z-index: 99999;
                box-shadow: none !important; 
                border: none !important; 
                border-radius: 0 !important;
            }

            /* Styling Tabel Cetak */
            table {
                width: 100% !important; 
                border-collapse: collapse !important;
                font-family: 'Times New Roman', Times, serif !important; 
                font-size: 11px !important;
            }
            thead th {
                background-color: #f3f4f6 !important; /* gray-100 */
                color: #000 !important;
                font-weight: bold !important; 
                border: 1px solid #000 !important;
                padding: 8px !important;
                -webkit-print-color-adjust: exact; /* Paksa cetak background */
                print-color-adjust: exact;
            }
            td {
                border: 1px solid #000 !important; 
                padding: 6px 8px !important;
                color: #000 !important; 
                vertical-align: top !important;
            }

            /* Utilities Cetak */
            .print-header, .print-footer { display: block !important; width: 100%; }
            .no-print, .pagination-container, a[href] { display: none !important; }
            a { text-decoration: none !important; color: #000 !important; pointer-events: none; }
            
            /* Hapus background warna-warni saat print agar hemat tinta & bersih */
            .bg-slate-100, .bg-blue-50, .bg-emerald-50, .bg-rose-50 {
                background-color: transparent !important;
                border: none !important;
            }
            .text-emerald-600, .text-rose-600, .text-blue-600 {
                color: #000 !important; /* Paksa hitam saat print */
                font-weight: normal !important;
            }
            
            /* Page Break handling */
            tr { page-break-inside: avoid; }
            .avoid-break { page-break-inside: avoid; }
        }

        /* Tampilan Layar (Screen) */
        .print-header, .print-footer { display: none; }
    </style>
    <?php $__env->stopPush(); ?>

    <div class="py-8 sm:py-10 font-sans text-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <div class="relative rounded-[2rem] bg-gray-900 bg-gradient-to-br from-slate-900 via-blue-900 to-blue-800 p-8 mb-8 text-white shadow-xl shadow-blue-900/30 overflow-hidden border border-white/10 no-print">
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="text-center md:text-left">
                        <h1 class="text-3xl font-extrabold tracking-tight mb-2 flex items-center justify-center md:justify-start gap-3">
                            <span class="text-4xl">📊</span> Monitoring Jurnal
                        </h1>
                        <p class="text-blue-300 text-sm font-medium leading-relaxed max-w-lg">
                            Rekapitulasi aktivitas belajar mengajar (KBM) guru beserta kehadiran siswa secara terperinci.
                        </p>
                    </div>
                    
                    <button onclick="window.print()" class="group bg-white text-slate-900 px-6 py-3.5 rounded-2xl font-bold text-sm shadow-lg hover:bg-blue-50 hover:text-blue-700 transition-all duration-300 flex items-center gap-2 transform active:scale-95">
                        <i class="ph-bold ph-printer text-xl group-hover:scale-110 transition-transform"></i>
                        <span>Cetak Laporan</span>
                    </button>
                </div>
            </div>

            
            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 mb-8 no-print relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-slate-50 rounded-bl-full -mr-8 -mt-8 pointer-events-none"></div>
                
                <div class="relative z-10">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2 mb-5">
                        <span class="bg-blue-100 text-blue-600 w-8 h-8 rounded-lg flex items-center justify-center"><i class="ph-bold ph-faders"></i></span>
                        Filter Data
                    </h3>

                    <form method="GET" action="<?php echo e(route('reports.teaching_journal')); ?>" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-5 items-end">
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Dari Tanggal</label>
                            <input type="date" name="start_date" value="<?php echo e($startDate); ?>" class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold text-slate-700 focus:ring-blue-500 focus:border-blue-500 h-11 px-4 shadow-sm transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Sampai Tanggal</label>
                            <input type="date" name="end_date" value="<?php echo e($endDate); ?>" class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold text-slate-700 focus:ring-blue-500 focus:border-blue-500 h-11 px-4 shadow-sm transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Guru</label>
                            <div class="relative">
                                <select name="teacher_id" class="w-full rounded-xl border-slate-200 bg-slate-50 text-sm font-bold text-slate-700 focus:ring-blue-500 focus:border-blue-500 h-11 px-4 appearance-none shadow-sm transition-all">
                                    <option value="">Semua Guru</option>
                                    <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <option value="<?php echo e($t->id); ?>" <?php echo e($teacherId == $t->id ? 'selected' : ''); ?>><?php echo e($t->name); ?></option> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-500"><i class="ph-bold ph-caret-down"></i></div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Kelas</label>
                            <div class="relative">
                                <select name="class_id" class="w-full rounded-xl border-slate-200 bg-slate-50 text-sm font-bold text-slate-700 focus:ring-blue-500 focus:border-blue-500 h-11 px-4 appearance-none shadow-sm transition-all">
                                    <option value="">Semua Kelas</option>
                                    <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <option value="<?php echo e($c->id); ?>" <?php echo e($classId == $c->id ? 'selected' : ''); ?>><?php echo e($c->name); ?></option> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-500"><i class="ph-bold ph-caret-down"></i></div>
                            </div>
                        </div>
                        <button type="submit" class="w-full h-11 bg-blue-900 hover:bg-slate-900 text-white font-bold rounded-xl transition-all shadow-lg flex items-center justify-center gap-2 group">
                            <i class="ph-bold ph-magnifying-glass text-lg group-hover:scale-110 transition-transform"></i> Terapkan
                        </button>
                    </form>
                </div>
            </div>

            
            <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden print-container p-0 md:p-0">
                
                
                <div class="print-header px-8 pt-6 mb-4 text-center">
                    <div style="border-bottom: 2px solid black; padding-bottom: 10px;">
                        <h2 class="text-xl font-bold uppercase" style="margin:0;">PEMERINTAH KABUPATEN CIAMIS</h2>
                        <h2 class="text-2xl font-black uppercase" style="margin:5px 0;"><?php echo e($schoolName); ?></h2>
                        <p class="text-sm italic" style="margin:0;">Alamat: <?php echo e($schoolAddress); ?></p>
                    </div>
                    <div class="mt-4 text-center">
                        <h3 class="text-lg font-bold uppercase underline">LAPORAN JURNAL MENGAJAR</h3>
                        <p class="text-sm">Periode: <?php echo e(\Carbon\Carbon::parse($startDate)->locale('id')->translatedFormat('d F Y')); ?> s.d. <?php echo e(\Carbon\Carbon::parse($endDate)->locale('id')->translatedFormat('d F Y')); ?></p>
                    </div>
                </div>

                
                <div class="overflow-x-auto px-0 md:px-0">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 border-b border-slate-100 text-slate-500">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center w-24">Tanggal</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Guru & Mapel</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center w-24">Kelas</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider w-1/3">Materi & Aktivitas</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center">Kehadiran</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center no-print w-20">Bukti</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php $__empty_1 = true; $__currentLoopData = $sessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php
                                    // Logika perhitungan hadir dipindah ke variabel agar bersih
                                    // Pastikan di Controller sudah di-load withCount('attendances as ...')
                                    $hadirTotal = ($session->hadir_count ?? 0) + ($session->late_count ?? 0);
                                    $alphaTotal = $session->alpha_count ?? 0;
                                ?>
                                <tr class="hover:bg-blue-50/20 transition-colors group">
                                    
                                    <td class="px-6 py-4 text-center border-b border-slate-100 align-top">
                                        <div class="font-bold text-slate-700 bg-slate-100 rounded-lg py-1 px-2 inline-block print:bg-transparent print:border-none">
                                            <?php echo e(\Carbon\Carbon::parse($session->date)->format('d/m')); ?>

                                        </div>
                                        <div class="text-[10px] text-slate-400 font-mono mt-1 font-bold">
                                            <?php echo e($session->started_at ? \Carbon\Carbon::parse($session->started_at)->format('H:i') : '-'); ?>

                                        </div>
                                    </td>
                                    
                                    
                                    <td class="px-6 py-4 border-b border-slate-100 align-top">
                                        <div class="font-bold text-slate-800 text-sm"><?php echo e($session->teacher->name ?? '-'); ?></div>
                                        <div class="text-xs font-bold text-blue-600 mt-0.5 flex items-center gap-1">
                                            <i class="ph-bold ph-book-open"></i> <?php echo e($session->schedule->subject->name ?? '-'); ?>

                                        </div>
                                    </td>

                                    
                                    <td class="px-6 py-4 text-center border-b border-slate-100 align-top">
                                        <span class="inline-block px-2.5 py-1 rounded-lg border border-slate-200 font-bold text-xs bg-white text-slate-600 shadow-sm print:shadow-none print:border-none">
                                            <?php echo e($session->schedule->schoolClass->name ?? '-'); ?>

                                        </span>
                                    </td>

                                    
                                    <td class="px-6 py-4 border-b border-slate-100 align-top">
                                        <p class="font-bold text-slate-800 text-sm mb-1">
                                            <?php echo e($session->topic ?? 'Tanpa Topik'); ?>

                                        </p>
                                        <p class="text-xs text-slate-500 text-justify leading-relaxed">
                                            <?php echo e($session->activities ?? '-'); ?>

                                        </p>
                                    </td>

                                    
                                    <td class="px-6 py-4 text-center border-b border-slate-100 align-top">
                                        <div class="flex flex-col items-center gap-1.5">
                                            <span class="inline-flex items-center gap-1 text-[10px] font-black uppercase tracking-wide bg-emerald-50 text-emerald-600 px-2 py-1 rounded border border-emerald-100">
                                                <?php echo e($hadirTotal); ?> Hadir
                                            </span>
                                            <?php if($alphaTotal > 0): ?>
                                                <span class="inline-flex items-center gap-1 text-[10px] font-black uppercase tracking-wide bg-rose-50 text-rose-600 px-2 py-1 rounded border border-rose-100">
                                                    <?php echo e($alphaTotal); ?> Alpha
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </td>

                                    
                                    <td class="px-6 py-4 text-center border-b border-slate-100 align-top no-print">
                                        <div class="flex gap-2 justify-center">
                                            <?php if($session->photo_proof): ?>
                                                <a href="<?php echo e(asset('storage/' . $session->photo_proof)); ?>" target="_blank" class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center hover:bg-purple-600 hover:text-white transition-all shadow-sm border border-purple-100" title="Lihat Foto">
                                                    <i class="ph-bold ph-image text-lg"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if($session->reference_link || $session->video_link): ?>
                                                <a href="<?php echo e($session->reference_link ?? $session->video_link); ?>" target="_blank" class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all shadow-sm border border-blue-100" title="Buka Link">
                                                    <i class="ph-bold ph-link text-lg"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-20 text-center text-slate-400">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4 no-print">
                                                <i class="ph-duotone ph-folder-dashed text-4xl text-slate-300"></i>
                                            </div>
                                            <p class="font-bold text-slate-600">Tidak ada data jurnal ditemukan.</p>
                                            <p class="text-xs mt-1 no-print">Coba ubah filter tanggal atau guru.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                
                <div class="print-footer mt-8 px-8 pb-8 avoid-break">
                    <table style="width: 100%; border: none !important;">
                        <tr style="border: none !important;">
                            <td style="width: 70%; border: none !important;"></td>
                            <td style="width: 30%; text-align: center; border: none !important; vertical-align: top;">
                                <p class="text-sm">Lakbok, <?php echo e($printDate); ?></p>
                                <p class="text-sm font-bold mt-1">Kepala Sekolah</p>
                                <br><br><br><br>
                                <p class="font-bold underline text-sm"><?php echo e($principalName); ?></p>
                                <p class="text-xs">NIP. <?php echo e($principalNIP); ?></p>
                            </td>
                        </tr>
                    </table>
                </div>

                
                <div class="p-6 border-t border-slate-50 no-print">
                    <?php echo e($sessions->links()); ?>

                </div>
            </div>

        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\reports\teaching_journal.blade.php ENDPATH**/ ?>