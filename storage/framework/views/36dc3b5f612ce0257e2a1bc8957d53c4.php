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
            <h2 class="font-semibold text-xl text-[#2c3f61] leading-tight">
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

    <div class="py-8 sm:py-10 font-sans text-[#2c3f61]" x-data="{ search: '' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            
            <div class="relative rounded-[2rem] bg-gradient-to-r from-[#56bbf1] via-[#e5eff5] to-[#f4d1c0] p-8 text-[#2c3f61] shadow-xl shadow-[#56bbf1]/10 overflow-hidden border border-white/60 print:hidden">
                <div class="absolute -top-10 -left-10 w-48 h-48 bg-[#0d52a1]/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-3xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-[#f9a282]/20 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-2xl"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <a href="<?php echo e(route('cbt.index')); ?>" class="text-xs font-bold text-[#0d52a1] hover:text-[#2c3f61] transition flex items-center gap-1 bg-white/60 px-3 py-1 rounded-full border border-white backdrop-blur-sm shadow-sm">
                                <i class="ph-bold ph-arrow-left"></i> Dashboard
                            </a>
                            <span class="text-[#2c3f61]/30 text-xs">•</span>
                            <span class="text-[10px] font-bold text-[#2c3f61]/70 uppercase tracking-wider">Laporan Hasil</span>
                        </div>
                        <h1 class="text-3xl font-extrabold tracking-tight leading-none text-[#2c3f61] mb-1"><?php echo e($exam->title); ?></h1>
                        <p class="text-[#2c3f61]/80 text-sm font-medium">Mapel: <?php echo e($exam->subject_name); ?> • Kelas <?php echo e($exam->class_level); ?></p>
                    </div>

                    
                    <?php if(!isset($exam->exam_type) || $exam->exam_type !== 'google_form'): ?>
                    <div class="flex flex-wrap gap-3">
                        
                        
                        <a href="<?php echo e(route('cbt.analysis', $exam->id)); ?>" class="group px-5 py-3 bg-white text-[#0d52a1] font-bold rounded-2xl hover:bg-slate-50 transition flex items-center gap-2 shadow-lg shadow-[#0d52a1]/10 border border-slate-100">
                            <i class="ph-duotone ph-chart-pie-slice text-xl text-[#56bbf1]"></i>
                            <span class="hidden sm:inline">Analisis Soal</span>
                        </a>

                        
                        <a href="<?php echo e(route('cbt.export', ['id' => $exam->id, 'type' => 'excel'])); ?>" target="_blank" class="group px-5 py-3 bg-emerald-500 text-white font-bold rounded-2xl hover:bg-emerald-600 transition flex items-center gap-2 shadow-lg shadow-emerald-500/20">
                            <i class="ph-duotone ph-microsoft-excel-logo text-xl group-hover:scale-110 transition-transform"></i> 
                            <span class="hidden sm:inline">Excel</span>
                        </a>
                        
                        
                        <a href="<?php echo e(route('cbt.export', ['id' => $exam->id, 'type' => 'pdf'])); ?>" target="_blank" class="group px-5 py-3 bg-rose-500 text-white font-bold rounded-2xl hover:bg-rose-600 transition flex items-center gap-2 shadow-lg shadow-rose-500/20">
                            <i class="ph-duotone ph-file-pdf text-xl group-hover:scale-110 transition-transform"></i> 
                            <span class="hidden sm:inline">PDF</span>
                        </a>

                        
                        <button type="button" onclick="confirmSync()" class="group px-5 py-3 bg-[#2c3f61] text-white font-bold rounded-2xl hover:bg-[#1c2940] transition flex items-center gap-2 shadow-lg shadow-[#2c3f61]/20">
                            <i class="ph-bold ph-book-bookmark text-xl group-hover:scale-110 transition-transform text-[#56bbf1]"></i>
                            <span class="hidden sm:inline">Post Nilai</span>
                        </button>
                        
                        
                        <form id="syncForm" action="<?php echo e(route('cbt.sync_grades', $exam->id)); ?>" method="POST" class="hidden">
                            <?php echo csrf_field(); ?>
                        </form>

                        
                        <button onclick="window.print()" class="group px-5 py-3 bg-white/40 backdrop-blur-md text-[#2c3f61] font-bold rounded-2xl hover:bg-white/60 transition flex items-center gap-2 border border-white/60 shadow-sm" title="Cetak Halaman">
                            <i class="ph-bold ph-printer text-xl"></i>
                        </button>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            
            <?php if(isset($exam->exam_type) && $exam->exam_type == 'google_form'): ?>
                <div class="bg-white rounded-[2.5rem] p-12 text-center border border-emerald-200 shadow-xl shadow-[#56bbf1]/10 relative overflow-hidden print:hidden mt-8">
                    <div class="absolute -top-24 -right-24 w-64 h-64 bg-emerald-50 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="w-24 h-24 bg-emerald-100 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-emerald-500 shadow-inner relative z-10">
                        <i class="ph-duotone ph-google-logo text-5xl"></i>
                    </div>
                    <h3 class="text-2xl font-black text-[#2c3f61] mb-2 relative z-10">Rekapitulasi Nilai Google Form</h3>
                    <p class="text-slate-500 font-medium max-w-lg mx-auto mb-8 relative z-10">Ujian ini diselenggarakan melalui tautan Google Formulir. Seluruh data jawaban, analisis butir soal, dan rekapitulasi nilai dapat Anda kelola secara langsung melalui dashboard Google Workspace (Google Drive/Classroom) Anda.</p>
                    
                    <a href="<?php echo e($exam->google_form_url); ?>" target="_blank" class="inline-flex items-center justify-center px-8 py-4 bg-emerald-500 text-white font-bold rounded-xl hover:bg-emerald-600 transition shadow-lg shadow-emerald-500/30 gap-2 relative z-10 active:scale-95">
                        <i class="ph-bold ph-arrow-square-out text-xl"></i> Buka Hasil di Google Form
                    </a>
                </div>
            <?php else: ?>
                

                
                <div class="hidden print:block text-center mb-6">
                    <h2 class="text-2xl font-bold uppercase">Laporan Hasil Ujian</h2>
                    <h3 class="text-xl"><?php echo e($exam->title); ?> - <?php echo e($exam->subject_name); ?></h3>
                    <p>Kelas: <?php echo e($exam->class_level); ?> | Tanggal Cetak: <?php echo e(date('d-m-Y')); ?></p>
                </div>

                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 print:grid-cols-4 print:gap-2">
                    
                    <div class="bg-white p-5 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-[#56bbf1]/5 print:border-black print:rounded-none print:shadow-none">
                        <div class="flex items-center gap-3 mb-2 print:hidden">
                            <div class="w-10 h-10 rounded-xl bg-[#56bbf1]/20 text-[#0d52a1] flex items-center justify-center"><i class="ph-bold ph-chart-line-up text-lg"></i></div>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Rata-rata</span>
                        </div>
                        <div class="hidden print:block text-xs font-bold uppercase mb-1">Rata-rata</div>
                        <p class="text-3xl font-black text-[#2c3f61]"><?php echo e(number_format($stats['average'], 1)); ?></p>
                    </div>

                    
                    <div class="bg-white p-5 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-[#56bbf1]/5 print:border-black print:rounded-none print:shadow-none">
                        <div class="flex items-center gap-3 mb-2 print:hidden">
                            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center"><i class="ph-bold ph-crown text-lg"></i></div>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Tertinggi</span>
                        </div>
                        <div class="hidden print:block text-xs font-bold uppercase mb-1">Tertinggi</div>
                        <p class="text-3xl font-black text-[#2c3f61]"><?php echo e($stats['max_score']); ?></p>
                    </div>

                    
                    <div class="bg-white p-5 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-[#56bbf1]/5 print:border-black print:rounded-none print:shadow-none">
                        <div class="flex items-center gap-3 mb-2 print:hidden">
                            <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center"><i class="ph-bold ph-trend-down text-lg"></i></div>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Terendah</span>
                        </div>
                        <div class="hidden print:block text-xs font-bold uppercase mb-1">Terendah</div>
                        <p class="text-3xl font-black text-[#2c3f61]"><?php echo e($stats['min_score']); ?></p>
                    </div>

                    
                    <div class="bg-white p-5 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-[#56bbf1]/5 print:border-black print:rounded-none print:shadow-none">
                        <div class="flex items-center gap-3 mb-2 print:hidden">
                            <div class="w-10 h-10 rounded-xl bg-[#e5eff5] text-[#0d52a1] border border-[#56bbf1]/30 flex items-center justify-center"><i class="ph-bold ph-users text-lg"></i></div>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Peserta</span>
                        </div>
                        <div class="hidden print:block text-xs font-bold uppercase mb-1">Total Peserta</div>
                        <div class="flex items-end justify-between mt-1">
                            <p class="text-3xl font-black text-[#2c3f61]"><?php echo e($results->count()); ?> <span class="text-sm text-slate-400 font-bold print:hidden">Siswa</span></p>
                            
                            
                            <?php
                                $lulusCount = $results->where('total_score', '>=', $exam->passing_grade)->count();
                                $passRate = $results->count() > 0 ? round(($lulusCount / $results->count()) * 100) : 0;
                            ?>
                            <div class="text-right print:hidden">
                                <span class="text-[10px] font-bold text-emerald-500"><?php echo e($passRate); ?>% Lulus</span>
                                <div class="w-16 h-1.5 bg-slate-100 rounded-full mt-1 overflow-hidden" title="<?php echo e($lulusCount); ?> Siswa Lulus">
                                    <div class="h-full bg-emerald-400" style="width: <?php echo e($passRate); ?>%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-[#56bbf1]/10 overflow-hidden print:shadow-none print:border-none print:rounded-none print-area">
                    <div class="p-6 border-b border-slate-100 bg-[#e5eff5]/30 flex flex-col md:flex-row justify-between items-center gap-4 print:hidden">
                        <h4 class="font-bold text-[#2c3f61] flex items-center gap-2 text-lg">
                            <i class="ph-fill ph-trophy text-[#f9a282]"></i> Peringkat Hasil
                        </h4>
                        <div class="relative w-full md:w-72">
                            <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="text" x-model="search" placeholder="Cari nama siswa..." class="w-full pl-10 pr-4 py-2.5 text-sm font-bold border-slate-200 rounded-xl focus:ring-[#56bbf1] focus:border-[#56bbf1] bg-white shadow-sm transition-shadow text-[#2c3f61]">
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-[#2c3f61]/80">
                            <thead class="bg-[#e5eff5]/50 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100 sticky top-0 z-10 print:static print:bg-white print:text-black">
                                <tr>
                                    <th class="px-6 py-4 text-center w-16">Rank</th>
                                    <th class="px-6 py-4">Nama Siswa</th>
                                    <th class="px-6 py-4 text-center">Percobaan</th>
                                    <th class="px-6 py-4 text-center">Benar / Salah</th>
                                    <th class="px-6 py-4 text-center">Nilai Akhir</th>
                                    <th class="px-6 py-4 text-center">Status</th>
                                    <th class="px-6 py-4 text-right print:hidden">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 print:divide-gray-300">
                                <?php $__empty_1 = true; $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $res): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr x-show="search === '' || '<?php echo e(strtolower($res->student_name)); ?>'.includes(search.toLowerCase())" 
                                        class="hover:bg-[#56bbf1]/5 transition group print:hover:bg-transparent">
                                        
                                        
                                        <td class="px-6 py-4 text-center">
                                            <?php if($index == 0): ?>
                                                <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center mx-auto shadow-sm print:shadow-none print:bg-transparent print:text-black"><i class="ph-fill ph-crown"></i> 1</div>
                                            <?php elseif($index == 1): ?>
                                                <div class="w-8 h-8 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center mx-auto shadow-sm font-bold print:shadow-none print:bg-transparent print:text-black">2</div>
                                            <?php elseif($index == 2): ?>
                                                <div class="w-8 h-8 rounded-full bg-[#f9a282]/20 text-[#c86845] flex items-center justify-center mx-auto shadow-sm font-bold print:shadow-none print:bg-transparent print:text-black">3</div>
                                            <?php else: ?>
                                                <span class="font-black text-slate-400 print:text-black"><?php echo e($index + 1); ?></span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="px-6 py-4">
                                            <p class="font-black text-[#2c3f61] text-base print:text-black"><?php echo e($res->student_name); ?></p>
                                            <p class="text-xs text-slate-400 font-mono mt-0.5 print:text-black"><?php echo e($res->student_nisn ?? 'NISN -'); ?></p>
                                        </td>

                                        
                                        <td class="px-6 py-4 text-center">
                                            <span class="text-xs font-bold text-slate-500 bg-slate-50 px-2.5 py-1 rounded-md border border-slate-100 print:border-none print:bg-transparent print:p-0 print:text-black">
                                                <?php echo e($res->attempt_count ?? 1); ?>x
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 text-center">
                                            <div class="inline-flex items-center gap-2 bg-slate-50 rounded-xl p-1.5 border border-slate-100 print:bg-transparent print:p-0 print:border-none">
                                                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded text-xs font-bold print:bg-transparent print:text-black print:border print:border-black" title="Benar"><?php echo e($res->correct_answers ?? 0); ?></span>
                                                <span class="text-slate-300 print:text-black">/</span>
                                                <span class="px-2 py-0.5 bg-rose-100 text-rose-700 rounded text-xs font-bold print:bg-transparent print:text-black print:border print:border-black" title="Salah"><?php echo e($res->wrong_answers ?? 0); ?></span>
                                            </div>
                                        </td>

                                        <td class="px-6 py-4 text-center">
                                            <span class="text-xl font-black <?php echo e($res->total_score >= $exam->passing_grade ? 'text-emerald-500' : 'text-rose-500'); ?> print:text-black">
                                                <?php echo e($res->total_score ?? 0); ?>

                                            </span>
                                        </td>

                                        <td class="px-6 py-4 text-center">
                                            <?php if(($res->total_score ?? 0) >= $exam->passing_grade): ?>
                                                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-full text-[10px] font-black uppercase tracking-wider print:bg-transparent print:text-black print:border-black">
                                                    Lulus
                                                </span>
                                            <?php else: ?>
                                                <span class="px-3 py-1 bg-rose-100 text-rose-600 border border-rose-200 rounded-full text-[10px] font-black uppercase tracking-wider print:bg-transparent print:text-black print:border-black">
                                                    Remedial
                                                </span>
                                            <?php endif; ?>
                                        </td>

                                        
                                        <td class="px-6 py-4 text-right print:hidden">
                                            <div class="flex items-center justify-end gap-2">
                                                
                                                <a href="<?php echo e(route('cbt.result.detail', ['exam' => $exam->id, 'student' => $res->student_id])); ?>" 
                                                   class="w-8 h-8 rounded-xl bg-white border border-[#56bbf1]/50 text-[#0d52a1] hover:bg-[#e5eff5] hover:border-[#56bbf1] transition inline-flex items-center justify-center shadow-sm" 
                                                   title="Lihat Detail Jawaban">
                                                    <i class="ph-bold ph-eye"></i>
                                                </a>
                                                
                                                
                                                <button type="button" 
                                                    onclick="confirmRetake('<?php echo e(route('cbt.student.retake', ['exam' => $exam->id, 'student' => $res->student_id])); ?>', '<?php echo e(addslashes($res->student_name)); ?>', <?php echo e($res->attempt_count ?? 1); ?>)" 
                                                    class="w-8 h-8 rounded-xl bg-white border border-[#f9a282]/50 text-[#c86845] hover:bg-[#f9a282]/20 hover:border-[#f9a282] transition inline-flex items-center justify-center shadow-sm" 
                                                    title="Izinkan Kerjakan Ulang (Reset Ujian)">
                                                    <i class="ph-bold ph-arrow-counter-clockwise"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="7" class="px-6 py-12 text-center">
                                            <div class="w-16 h-16 bg-[#e5eff5] rounded-full flex items-center justify-center mx-auto mb-3 text-[#0d52a1]/50">
                                                <i class="ph-duotone ph-file-x text-3xl"></i>
                                            </div>
                                            <p class="text-[#2c3f61]/60 font-bold">Belum ada data nilai masuk.</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                
                                
                                <tr x-show="search !== '' && $el.parentElement.querySelectorAll('tr[x-show]').length > 0 && Array.from($el.parentElement.querySelectorAll('tr')).filter(r => r.style.display !== 'none' && !r.hasAttribute('x-show-empty')).length === 0" 
                                    x-show-empty 
                                    style="display: none;">
                                    <td colspan="7" class="px-6 py-8 text-center text-slate-500">
                                        <p class="font-medium">Tidak ditemukan siswa dengan nama "<span x-text="search" class="font-bold text-[#2c3f61]"></span>"</p>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    
    <?php $__env->startPush('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // FUNGSI KONFIRMASI KERJAKAN ULANG
        function confirmRetake(url, studentName, currentAttempt) {
            const nextAttempt = currentAttempt + 1;
            
            let attemptText = `percobaan ke-<b>${nextAttempt}</b>`;
            if (nextAttempt === 2) {
                attemptText = `mengerjakan soal untuk yang <b>kedua kalinya</b>`;
            }
            
            Swal.fire({
                title: 'Izinkan Ujian Ulang?',
                html: `Siswa <b>${studentName}</b> sudah menyelesaikan ujian ini.<br><br>Jika Anda mengizinkan, siswa akan ${attemptText}. Data jawaban sebelumnya akan di-reset. Apakah Anda yakin?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#c86845',
                cancelButtonColor: '#64748b',
                confirmButtonText: '<i class="ph-bold ph-arrow-counter-clockwise"></i> Ya, Izinkan!',
                cancelButtonText: 'Batal',
                customClass: { 
                    popup: 'rounded-[2rem]',
                    confirmButton: 'rounded-xl px-4 py-2 font-bold',
                    cancelButton: 'rounded-xl px-4 py-2 font-bold'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;
                    
                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = '<?php echo e(csrf_token()); ?>';
                    
                    form.appendChild(csrf);
                    document.body.appendChild(form);
                    
                    Swal.fire({ 
                        title: 'Memproses Izin...', 
                        allowOutsideClick: false, 
                        didOpen: () => Swal.showLoading(), 
                        customClass: { popup: 'rounded-[2rem]' } 
                    });
                    
                    form.submit();
                }
            });
        }

        // FUNGSI KONFIRMASI POSTING NILAI
        function confirmSync() {
            Swal.fire({
                title: 'Posting Nilai?',
                text: "Nilai ujian ini akan disinkronkan ke Buku Nilai (Gradebook/LMS). Nilai lama (jika ada) akan ditimpa.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0d52a1',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Posting!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-[2rem]',
                    confirmButton: 'rounded-xl px-4 py-2 font-bold',
                    cancelButton: 'rounded-xl px-4 py-2 font-bold'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memproses...',
                        html: 'Sedang memposting nilai ke Gradebook.',
                        timerProgressBar: true,
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                        customClass: {
                            popup: 'rounded-[2rem]'
                        }
                    });
                    document.getElementById('syncForm').submit();
                }
            })
        }

        <?php if(session('success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "<?php echo e(session('success')); ?>",
                timer: 3000,
                showConfirmButton: false,
                customClass: { popup: 'rounded-[2rem]' }
            });
        <?php endif; ?>
        
        <?php if(session('error')): ?>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "<?php echo e(session('error')); ?>",
                customClass: { popup: 'rounded-[2rem]' }
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
<?php endif; ?><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/cbt/recap.blade.php ENDPATH**/ ?>