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
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex items-center justify-between print:hidden">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                <?php echo e(__('Rekapitulasi Nilai')); ?>

            </h2>
        </div>
     <?php $__env->endSlot(); ?>

    
    <style>
        @media print {
            body { background: white; }
            .no-print { display: none !important; }
            .print-area { box-shadow: none !important; border: none !important; }
            table { width: 100%; font-size: 12px; color: black; }
            th, td { border: 1px solid #ddd !important; padding: 8px !important; }
        }
    </style>

    <div class="py-8 sm:py-10 font-sans text-slate-800" x-data="{ search: '' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            
            <div class="relative rounded-[2rem] bg-gray-900 bg-gradient-to-br from-slate-900 via-indigo-900 to-purple-900 p-8 text-white shadow-xl shadow-indigo-900/30 overflow-hidden border border-white/10 print:hidden">
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <a href="<?php echo e(route('cbt.index')); ?>" class="text-xs font-bold text-indigo-300 hover:text-white transition flex items-center gap-1">
                                <i class="ph-bold ph-arrow-left"></i> Dashboard
                            </a>
                            <span class="text-white/30 text-xs">•</span>
                            <span class="text-[10px] font-bold text-indigo-200 uppercase tracking-wider">Laporan Hasil</span>
                        </div>
                        <h1 class="text-3xl font-black tracking-tight leading-none text-white mb-1"><?php echo e($exam->title); ?></h1>
                        <p class="text-indigo-200 text-sm font-medium">Mapel: <?php echo e($exam->subject_name); ?> • Kelas <?php echo e($exam->class_level); ?></p>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        
                        
                        <a href="<?php echo e(route('cbt.analysis', $exam->id)); ?>" class="group px-5 py-3 bg-white text-indigo-900 font-bold rounded-2xl hover:bg-indigo-50 transition flex items-center gap-2 shadow-lg shadow-black/10">
                            <i class="ph-duotone ph-chart-pie-slice text-xl"></i>
                            <span class="hidden sm:inline">Analisis Soal</span>
                        </a>

                        
                        <a href="<?php echo e(route('cbt.export', ['id' => $exam->id, 'type' => 'excel'])); ?>" target="_blank" class="group px-5 py-3 bg-emerald-600 text-white font-bold rounded-2xl hover:bg-emerald-500 transition flex items-center gap-2 shadow-lg shadow-emerald-900/20">
                            <i class="ph-duotone ph-microsoft-excel-logo text-xl group-hover:scale-110 transition-transform"></i> 
                            <span class="hidden sm:inline">Excel</span>
                        </a>
                        
                        
                        <a href="<?php echo e(route('cbt.export', ['id' => $exam->id, 'type' => 'pdf'])); ?>" target="_blank" class="group px-5 py-3 bg-rose-600 text-white font-bold rounded-2xl hover:bg-rose-500 transition flex items-center gap-2 shadow-lg shadow-rose-900/20">
                            <i class="ph-duotone ph-file-pdf text-xl group-hover:scale-110 transition-transform"></i> 
                            <span class="hidden sm:inline">PDF</span>
                        </a>

                        
                        <button type="button" onclick="confirmSync()" class="group px-5 py-3 bg-amber-500 text-white font-bold rounded-2xl hover:bg-amber-400 transition flex items-center gap-2 shadow-lg shadow-amber-900/20 border border-amber-400">
                            <i class="ph-bold ph-book-bookmark text-xl group-hover:scale-110 transition-transform"></i>
                            <span class="hidden sm:inline">Post Nilai</span>
                        </button>
                        
                        
                        <form id="syncForm" action="<?php echo e(route('cbt.sync_grades', $exam->id)); ?>" method="POST" class="hidden">
                            <?php echo csrf_field(); ?>
                        </form>

                        
                        <button onclick="window.print()" class="group px-5 py-3 bg-white/10 backdrop-blur-md text-white font-bold rounded-2xl hover:bg-white/20 transition flex items-center gap-2 border border-white/10" title="Cetak Halaman">
                            <i class="ph-bold ph-printer text-xl"></i>
                        </button>
                    </div>
                </div>
            </div>

            
            <div class="hidden print:block text-center mb-6">
                <h2 class="text-2xl font-bold uppercase">Laporan Hasil Ujian</h2>
                <h3 class="text-xl"><?php echo e($exam->title); ?> - <?php echo e($exam->subject_name); ?></h3>
                <p>Kelas: <?php echo e($exam->class_level); ?> | Tanggal Cetak: <?php echo e(date('d-m-Y')); ?></p>
            </div>

            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 print:grid-cols-4 print:gap-2">
                
                <div class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm print:border-black print:rounded-none">
                    <div class="flex items-center gap-3 mb-2 print:hidden">
                        <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center"><i class="ph-bold ph-chart-line-up"></i></div>
                        <span class="text-xs font-bold text-slate-400 uppercase">Rata-rata</span>
                    </div>
                    <div class="hidden print:block text-xs font-bold uppercase mb-1">Rata-rata</div>
                    <p class="text-2xl font-black text-slate-800"><?php echo e(number_format($stats['average'], 1)); ?></p>
                </div>

                
                <div class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm print:border-black print:rounded-none">
                    <div class="flex items-center gap-3 mb-2 print:hidden">
                        <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center"><i class="ph-bold ph-crown"></i></div>
                        <span class="text-xs font-bold text-slate-400 uppercase">Tertinggi</span>
                    </div>
                    <div class="hidden print:block text-xs font-bold uppercase mb-1">Tertinggi</div>
                    <p class="text-2xl font-black text-slate-800"><?php echo e($stats['max_score']); ?></p>
                </div>

                
                <div class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm print:border-black print:rounded-none">
                    <div class="flex items-center gap-3 mb-2 print:hidden">
                        <div class="w-8 h-8 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center"><i class="ph-bold ph-trend-down"></i></div>
                        <span class="text-xs font-bold text-slate-400 uppercase">Terendah</span>
                    </div>
                    <div class="hidden print:block text-xs font-bold uppercase mb-1">Terendah</div>
                    <p class="text-2xl font-black text-slate-800"><?php echo e($stats['min_score']); ?></p>
                </div>

                
                <div class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm print:border-black print:rounded-none">
                    <div class="flex items-center gap-3 mb-2 print:hidden">
                        <div class="w-8 h-8 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center"><i class="ph-bold ph-users"></i></div>
                        <span class="text-xs font-bold text-slate-400 uppercase">Peserta</span>
                    </div>
                    <div class="hidden print:block text-xs font-bold uppercase mb-1">Total Peserta</div>
                    <p class="text-2xl font-black text-slate-800"><?php echo e($results->count()); ?> <span class="text-sm text-slate-400 font-bold print:hidden">Siswa</span></p>
                </div>
            </div>

            
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden print:shadow-none print:border-none print:rounded-none print-area">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row justify-between items-center gap-4 print:hidden">
                    <h4 class="font-bold text-slate-700 flex items-center gap-2 text-lg">
                        <i class="ph-fill ph-trophy text-amber-500"></i> Peringkat Hasil
                    </h4>
                    <div class="relative w-full md:w-72">
                        <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" x-model="search" placeholder="Cari nama siswa..." class="w-full pl-10 pr-4 py-2.5 text-sm font-bold border-slate-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 bg-white shadow-sm transition-shadow">
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100 sticky top-0 z-10 print:static print:bg-white print:text-black">
                            <tr>
                                <th class="px-6 py-4 text-center w-16">Rank</th>
                                <th class="px-6 py-4">Nama Siswa</th>
                                <th class="px-6 py-4 text-center">Benar / Salah</th>
                                <th class="px-6 py-4 text-center">Nilai Akhir</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-right print:hidden">Detail</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 print:divide-gray-300">
                            <?php $__empty_1 = true; $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $res): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr x-show="search === '' || '<?php echo e(strtolower($res->student_name)); ?>'.includes(search.toLowerCase())" 
                                    class="hover:bg-indigo-50/30 transition group print:hover:bg-transparent">
                                    
                                    
                                    <td class="px-6 py-4 text-center">
                                        <?php if($index == 0): ?>
                                            <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center mx-auto shadow-sm print:shadow-none print:bg-transparent print:text-black"><i class="ph-fill ph-crown"></i> 1</div>
                                        <?php elseif($index == 1): ?>
                                            <div class="w-8 h-8 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center mx-auto shadow-sm font-bold print:shadow-none print:bg-transparent print:text-black">2</div>
                                        <?php elseif($index == 2): ?>
                                            <div class="w-8 h-8 rounded-full bg-orange-100 text-orange-700 flex items-center justify-center mx-auto shadow-sm font-bold print:shadow-none print:bg-transparent print:text-black">3</div>
                                        <?php else: ?>
                                            <span class="font-bold text-slate-400 print:text-black"><?php echo e($index + 1); ?></span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="px-6 py-4">
                                        <p class="font-bold text-slate-800 text-base print:text-black"><?php echo e($res->student_name); ?></p>
                                        <p class="text-xs text-slate-400 font-mono mt-0.5 print:text-black"><?php echo e($res->student_nisn ?? 'NISN -'); ?></p>
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <div class="inline-flex items-center gap-2 bg-slate-100 rounded-lg p-1.5 print:bg-transparent print:p-0">
                                            <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded text-xs font-bold print:bg-transparent print:text-black print:border print:border-black" title="Benar"><?php echo e($res->correct_answers ?? 0); ?></span>
                                            <span class="text-slate-300 print:text-black">/</span>
                                            <span class="px-2 py-0.5 bg-rose-100 text-rose-700 rounded text-xs font-bold print:bg-transparent print:text-black print:border print:border-black" title="Salah"><?php echo e($res->wrong_answers ?? 0); ?></span>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <span class="text-xl font-black <?php echo e($res->total_score >= $exam->passing_grade ? 'text-emerald-600' : 'text-rose-500'); ?> print:text-black">
                                            <?php echo e($res->total_score ?? 0); ?>

                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <?php if(($res->total_score ?? 0) >= $exam->passing_grade): ?>
                                            <span class="px-3 py-1 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-full text-[10px] font-black uppercase tracking-wider print:bg-transparent print:text-black print:border-black">
                                                Lulus
                                            </span>
                                        <?php else: ?>
                                            <span class="px-3 py-1 bg-rose-50 text-rose-600 border border-rose-100 rounded-full text-[10px] font-black uppercase tracking-wider print:bg-transparent print:text-black print:border-black">
                                                Remedial
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    
                                    <td class="px-6 py-4 text-right print:hidden">
                                        <a href="<?php echo e(route('cbt.result.detail', ['exam' => $exam->id, 'student' => $res->student_id])); ?>" 
                                           class="w-8 h-8 rounded-xl bg-white border border-slate-200 text-slate-400 hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition inline-flex items-center justify-center shadow-sm" 
                                           title="Lihat Detail Jawaban">
                                            <i class="ph-bold ph-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-300">
                                            <i class="ph-duotone ph-file-x text-3xl"></i>
                                        </div>
                                        <p class="text-slate-500 font-bold">Belum ada data nilai masuk.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            
                            
                            <tr x-show="search !== '' && $el.parentElement.querySelectorAll('tr[x-show]').length > 0 && Array.from($el.parentElement.querySelectorAll('tr')).filter(r => r.style.display !== 'none' && !r.hasAttribute('x-show-empty')).length === 0" 
                                x-show-empty 
                                style="display: none;">
                                <td colspan="6" class="px-6 py-8 text-center text-slate-500">
                                    <p class="font-medium">Tidak ditemukan siswa dengan nama "<span x-text="search" class="font-bold text-slate-800"></span>"</p>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    
    <?php $__env->startPush('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmSync() {
            Swal.fire({
                title: 'Posting Nilai?',
                text: "Nilai ujian ini akan disinkronkan ke Buku Nilai (Gradebook/LMS). Nilai lama (jika ada) akan ditimpa.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#f59e0b', // Amber sesuai tombol
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Posting!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-[1.5rem]',
                    confirmButton: 'rounded-xl px-4 py-2 font-bold',
                    cancelButton: 'rounded-xl px-4 py-2 font-bold'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tampilkan Loading
                    Swal.fire({
                        title: 'Memproses...',
                        html: 'Sedang memposting nilai ke Gradebook.',
                        timerProgressBar: true,
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                        customClass: {
                            popup: 'rounded-[1.5rem]'
                        }
                    });
                    
                    // Submit Form Asli
                    document.getElementById('syncForm').submit();
                }
            })
        }

        // Tampilkan Flash Message dari Session (Success/Error)
        <?php if(session('success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "<?php echo e(session('success')); ?>",
                timer: 3000,
                showConfirmButton: false,
                customClass: { popup: 'rounded-[1.5rem]' }
            });
        <?php endif; ?>
        
        <?php if(session('error')): ?>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "<?php echo e(session('error')); ?>",
                customClass: { popup: 'rounded-[1.5rem]' }
            });
        <?php endif; ?>
    </script>
    <?php $__env->stopPush(); ?>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/cbt/recap.blade.php ENDPATH**/ ?>