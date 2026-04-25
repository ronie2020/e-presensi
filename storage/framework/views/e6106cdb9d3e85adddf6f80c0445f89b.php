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
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            <?php echo e(__('Daftar Ujian - ' . $event->name)); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-8 sm:py-10 font-sans text-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            
            <?php if(session('success')): ?>
                <div id="flash-success" data-message="<?php echo e(session('success')); ?>"></div>
            <?php endif; ?>

            
            <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-5 rounded-[2rem] shadow-sm border border-slate-100">
                <div>
                    <div class="flex items-center gap-2 text-slate-400 text-sm font-bold mb-1">
                        <a href="<?php echo e(route('cbt.index')); ?>" class="hover:text-blue-600 transition flex items-center gap-1">
                            <i class="ph-bold ph-folders"></i> Dashboard Folder
                        </a>
                        <span>/</span>
                        <span class="text-blue-600"><?php echo e($event->name); ?></span>
                    </div>
                    <h1 class="text-2xl font-black text-slate-800 flex items-center gap-2">
                        <i class="ph-fill ph-folder-open text-blue-500"></i> <?php echo e($event->name); ?>

                    </h1>
                </div>

                <a href="<?php echo e(route('cbt.create', ['event_id' => $event->id])); ?>" class="group flex items-center gap-3 px-6 py-3.5 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 active:scale-95 transition-all shadow-lg shadow-blue-500/30">
                    <i class="ph-bold ph-plus text-lg"></i> Tambah Ujian Mapel
                </a>
            </div>

            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white rounded-[1.5rem] p-5 border border-slate-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center text-xl shrink-0"><i class="ph-bold ph-check-circle"></i></div>
                    <div><p class="text-[10px] text-slate-400 font-bold uppercase mb-0.5 tracking-wider">Ujian Aktif</p><h4 class="text-2xl font-black text-slate-800"><?php echo e($stats['active_exams']); ?></h4></div>
                </div>
                <div class="bg-white rounded-[1.5rem] p-5 border border-slate-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-full flex items-center justify-center text-xl shrink-0"><i class="ph-bold ph-list-numbers"></i></div>
                    <div><p class="text-[10px] text-slate-400 font-bold uppercase mb-0.5 tracking-wider">Total Soal</p><h4 class="text-2xl font-black text-slate-800"><?php echo e(number_format($stats['total_questions'])); ?></h4></div>
                </div>
                <div class="bg-white rounded-[1.5rem] p-5 border border-slate-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center text-xl shrink-0"><i class="ph-bold ph-users"></i></div>
                    <div><p class="text-[10px] text-slate-400 font-bold uppercase mb-0.5 tracking-wider">Siswa Ujian</p><h4 class="text-2xl font-black text-slate-800"><?php echo e(number_format($stats['students_working'])); ?></h4></div>
                </div>
                <div class="bg-white rounded-[1.5rem] p-5 border border-slate-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-full flex items-center justify-center text-xl shrink-0"><i class="ph-bold ph-chart-line-up"></i></div>
                    <div><p class="text-[10px] text-slate-400 font-bold uppercase mb-0.5 tracking-wider">Rata Nilai</p><h4 class="text-2xl font-black text-slate-800"><?php echo e(number_format($stats['avg_score'], 1)); ?></h4></div>
                </div>
            </div>

            
            <?php
                // Mengekstrak item agar bisa menggunakan method Collection dengan leluasa, kompatibel jika dipaginasi
                $examItems = method_exists($exams, 'items') ? collect($exams->items()) : collect($exams);
                
                // 1. Urutkan berdasarkan start_time. Yang belum dijadwalkan dilempar ke urutan paling bawah
                $sortedExams = $examItems->sortBy(function($exam) {
                    return $exam->start_time ? \Carbon\Carbon::parse($exam->start_time)->timestamp : 9999999999;
                });

                // 2. Kelompokkan berdasarkan format tanggal
                $groupedExams = $sortedExams->groupBy(function($exam) {
                    if (!$exam->start_time) return 'Belum Dijadwalkan';
                    return \Carbon\Carbon::parse($exam->start_time)->locale('id')->isoFormat('dddd, D MMMM Y');
                });
            ?>

            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php $__empty_1 = true; $__currentLoopData = $groupedExams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dateLabel => $dailyExams): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    
                    
                    <div class="col-span-full mb-2 mt-4 first:mt-0">
                        <div class="flex items-center gap-4">
                            <div class="bg-white border border-slate-200 rounded-[1rem] p-3 pr-5 flex items-center gap-3 shadow-sm w-max">
                                <div class="w-10 h-10 rounded-[0.75rem] flex items-center justify-center shadow-sm border <?php echo e($dateLabel === 'Belum Dijadwalkan' ? 'bg-rose-50 border-rose-100 text-rose-500' : 'bg-blue-50 border-blue-100 text-blue-600'); ?>">
                                    <?php if($dateLabel === 'Belum Dijadwalkan'): ?>
                                        <i class="ph-bold ph-calendar-slash text-xl"></i>
                                    <?php else: ?>
                                        <i class="ph-bold ph-calendar-check text-xl"></i>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <p class="text-[10px] uppercase font-black tracking-wider text-slate-400 mb-0.5">Jadwal Ujian</p>
                                    <h3 class="text-sm font-black <?php echo e($dateLabel === 'Belum Dijadwalkan' ? 'text-rose-600' : 'text-slate-700'); ?>"><?php echo e($dateLabel); ?></h3>
                                </div>
                            </div>
                            <div class="flex-1 h-px bg-gradient-to-r from-slate-200 to-transparent"></div>
                        </div>
                    </div>

                    
                    <?php $__currentLoopData = $dailyExams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-white border border-slate-100 rounded-[2rem] p-6 hover:shadow-xl hover:shadow-blue-900/5 hover:border-blue-200 transition-all duration-300 group relative flex flex-col h-full">
                            
                           
                           <div class="absolute top-6 right-6 z-10" title="Aktifkan / Nonaktifkan Ujian">
                                <label class="relative inline-flex items-center cursor-pointer group/toggle">
                                    <input type="checkbox" class="sr-only peer" 
                                           <?php echo e($exam->is_active ? 'checked' : ''); ?> 
                                           data-url="<?php echo e(route('cbt.toggle_status', $exam->id)); ?>" 
                                           data-id="<?php echo e($exam->id); ?>"
                                           onchange="toggleStatus(this)">
                                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500 shadow-inner"></div>
                                </label>
                            </div>

                            <div class="mb-4 pr-16">
                                <div class="flex flex-wrap items-center gap-2 mb-3">
                                    <span class="inline-block px-3 py-1 bg-indigo-50 text-indigo-600 border border-indigo-100 rounded-lg text-[10px] font-black uppercase tracking-wide">
                                        <?php echo e($exam->subject_name); ?>

                                    </span>
                                    <?php if(isset($exam->exam_type) && $exam->exam_type == 'google_form'): ?>
                                        <span class="inline-block px-3 py-1 bg-teal-50 text-teal-600 border border-teal-100 rounded-lg text-[10px] font-black uppercase tracking-wide">
                                            <i class="ph-bold ph-google-logo"></i> G-Form
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-block px-3 py-1 bg-blue-50 text-blue-600 border border-blue-100 rounded-lg text-[10px] font-black uppercase tracking-wide">
                                            <i class="ph-bold ph-desktop"></i> CBT
                                        </span>
                                    <?php endif; ?>

                                    
                                    <span id="status-badge-<?php echo e($exam->id); ?>" class="inline-block px-3 py-1 border rounded-lg text-[10px] font-black uppercase tracking-wide transition-colors <?php echo e($exam->is_active ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-rose-50 text-rose-600 border-rose-100'); ?>">
                                        <?php if($exam->is_active): ?>
                                            <i class="ph-bold ph-check-circle"></i> Aktif
                                        <?php else: ?>
                                            <i class="ph-bold ph-x-circle"></i> Tidak Aktif
                                        <?php endif; ?>
                                    </span>
                                </div>
                                <h4 class="font-black text-xl text-slate-800 leading-tight group-hover:text-blue-600 transition-colors line-clamp-2"><?php echo e($exam->title); ?></h4>
                            </div>

                            
                            <div class="mb-4 p-3 bg-slate-50 border border-slate-100 rounded-xl space-y-2">
                                <div class="flex items-start text-xs font-bold text-slate-600">
                                    <i class="ph-fill ph-play-circle text-emerald-500 text-base mr-2 mt-0.5 shrink-0"></i>
                                    <span class="w-12 text-slate-400 shrink-0">Mulai</span>
                                    <span class="leading-tight">: <?php echo e($exam->start_time ? \Carbon\Carbon::parse($exam->start_time)->locale('id')->isoFormat('dddd, D MMMM Y - HH:mm') : 'Belum diatur'); ?></span>
                                </div>
                                <div class="flex items-start text-xs font-bold text-slate-600">
                                    <i class="ph-fill ph-stop-circle text-rose-500 text-base mr-2 mt-0.5 shrink-0"></i>
                                    <span class="w-12 text-slate-400 shrink-0">Akhir</span>
                                    <span class="leading-tight">: <?php echo e($exam->end_time ? \Carbon\Carbon::parse($exam->end_time)->locale('id')->isoFormat('dddd, D MMMM Y - HH:mm') : 'Belum diatur'); ?></span>
                                </div>
                            </div>
                            
                            <div class="flex-1 space-y-4">
                                <div class="bg-white border border-slate-200 rounded-2xl p-4 flex items-center justify-between group/token cursor-pointer hover:bg-blue-50 hover:border-blue-200 transition shadow-sm" onclick="copyToken('<?php echo e($exam->token); ?>')">
                                    <div>
                                        <span class="text-[10px] text-slate-400 uppercase font-bold tracking-wider block mb-0.5">Token Ujian</span>
                                        <span class="font-mono font-black text-xl text-slate-700 tracking-widest group-hover/token:text-blue-600"><?php echo e($exam->token); ?></span>
                                    </div>
                                    <div class="w-8 h-8 bg-slate-50 rounded-full flex items-center justify-center text-slate-400 shadow-sm border border-slate-100 group-hover/token:text-blue-500 transition"><i class="ph-bold ph-copy"></i></div>
                                </div>
                                <div class="flex items-center gap-4 text-xs text-slate-500 font-bold">
                                    <span class="flex items-center gap-1.5"><i class="ph-bold ph-users text-purple-500"></i> Kelas <?php echo e($exam->class_level); ?></span>
                                    <span class="flex items-center gap-1.5"><i class="ph-bold ph-clock text-blue-500"></i> <?php echo e($exam->duration_minutes); ?> Menit</span>
                                </div>
                            </div>

                            <!-- Footer Actions -->
                            <div class="mt-5 pt-4 border-t border-slate-50 grid grid-cols-2 gap-2">
                                <!-- Baris 1: Soal & Monitor -->
                                <?php if(isset($exam->exam_type) && $exam->exam_type == 'google_form'): ?>
                                    <a href="<?php echo e($exam->google_form_url); ?>" target="_blank" class="flex items-center justify-center p-2.5 bg-teal-50 text-teal-600 border border-teal-100 rounded-xl text-xs font-bold hover:bg-teal-600 hover:text-white transition-all group/btn" title="Buka Link Google Form">
                                        <i class="ph-bold ph-google-logo text-lg mr-2"></i> Form
                                    </a>
                                <?php else: ?>
                                    <a href="<?php echo e(route('cbt.questions.manage', $exam->id)); ?>" class="flex items-center justify-center p-2.5 bg-slate-50 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-800 hover:text-white transition-all group/btn" title="Kelola Soal Ujian">
                                        <i class="ph-bold ph-list-numbers text-lg mr-2"></i> Soal
                                    </a>
                                <?php endif; ?>
                                <a href="<?php echo e(route('cbt.monitoring', $exam->id)); ?>" class="flex items-center justify-center p-2.5 bg-emerald-50 text-emerald-600 rounded-xl text-xs font-bold hover:bg-emerald-600 hover:text-white transition-all border border-emerald-100">
                                    <i class="ph-bold ph-desktop text-lg mr-2"></i> Monitor
                                </a>

                                <!-- Baris 2: Rekap -->
                                <a href="<?php echo e(route('cbt.recap', $exam->id)); ?>" class="col-span-2 flex items-center justify-center p-2.5 bg-indigo-50 text-indigo-600 border border-indigo-100 rounded-xl text-xs font-bold hover:bg-indigo-600 hover:text-white transition-all">
                                    <i class="ph-bold ph-chart-bar text-lg mr-2"></i> Rekapitulasi Nilai
                                </a>

                                <!-- Baris 3: SEB & Edit -->
                                <a href="<?php echo e(route('cbt.download_seb', $exam->id)); ?>" class="col-span-1 flex items-center justify-center p-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-100 hover:text-blue-600 transition-all">
                                    <i class="ph-bold ph-file-lock text-lg mr-2"></i> SEB
                                </a>
                                <a href="<?php echo e(route('cbt.edit', $exam->id)); ?>" class="col-span-1 flex items-center justify-center p-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-100 hover:text-blue-600 transition-all">
                                    <i class="ph-bold ph-pencil-simple text-lg mr-2"></i> Edit
                                </a>

                                <!-- Baris 4: Duplikat & Hapus -->
                                <form action="<?php echo e(route('cbt.clone', $exam->id)); ?>" method="POST" class="col-span-1">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="w-full flex items-center justify-center p-2.5 bg-white border border-amber-200 text-amber-600 rounded-xl text-xs font-bold hover:bg-amber-50 hover:text-amber-700 transition-all" onclick="return confirm('Menduplikasi ujian ini beserta semua soalnya?')">
                                        <i class="ph-bold ph-copy text-lg mr-2"></i> Clone
                                    </button>
                                </form>
                                <button onclick="confirmDelete('<?php echo e($exam->id); ?>')" class="col-span-1 flex items-center justify-center p-2.5 bg-white border border-rose-100 text-rose-500 rounded-xl text-xs font-bold hover:bg-rose-500 hover:text-white transition-all">
                                    <i class="ph-bold ph-trash text-lg mr-2"></i> Hapus
                                </button>

                                <form id="delete-form-<?php echo e($exam->id); ?>" action="<?php echo e(route('cbt.destroy', $exam->id)); ?>" method="POST" class="hidden">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                </form>
                                
                                <!-- Baris 5: Cetak Dokumen -->
                                <a href="<?php echo e(route('cbt.attendance', $exam->id)); ?>" target="_blank" class="col-span-1 flex items-center justify-center p-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-100 hover:text-blue-600 transition-all">
                                    <i class="ph-bold ph-users-three text-lg mr-2"></i> Absensi
                                </a>
                                <a href="<?php echo e(route('cbt.minutes', $exam->id)); ?>" target="_blank" class="col-span-1 flex items-center justify-center p-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-100 hover:text-blue-600 transition-all">
                                    <i class="ph-bold ph-file-text text-lg mr-2"></i> Berita Acara
                                </a>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    
                    <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-20 bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200 mt-4">
                        <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                            <i class="ph-duotone ph-file-dashed text-5xl"></i>
                        </div>
                        <h3 class="text-slate-800 font-bold text-xl mb-2">Folder Ini Masih Kosong</h3>
                        <p class="text-slate-500 max-w-xs mx-auto mb-8 text-sm">Silakan buat jadwal ujian baru yang akan dimasukkan ke dalam kegiatan <b><?php echo e($event->name); ?></b>.</p>
                        <a href="<?php echo e(route('cbt.create', ['event_id' => $event->id])); ?>" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-500/30 text-sm">
                            <i class="ph-bold ph-plus"></i> Tambah Ujian Mapel
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            
            
            <?php if(method_exists($exams, 'links')): ?>
                <div class="mt-8"><?php echo e($exams->links()); ?></div>
            <?php endif; ?>
        </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function toggleStatus(element) {
            const url = element.getAttribute('data-url');
            const id = element.getAttribute('data-id');
            const isChecked = element.checked;

            fetch(url, {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json', 
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                    'Accept': 'application/json'
                }
            }).then(res => res.json()).then(data => {
                if (data.success) {
                    Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: data.message, showConfirmButton: false, timer: 2000, customClass: { popup: 'rounded-xl' }});
                    
                    // DOM: Update Badge Teks secara dinamis
                    const badge = document.getElementById('status-badge-' + id);
                    if (badge) {
                        if (data.is_active) {
                            badge.className = 'inline-block px-3 py-1 border rounded-lg text-[10px] font-black uppercase tracking-wide transition-colors bg-emerald-50 text-emerald-600 border-emerald-100';
                            badge.innerHTML = '<i class="ph-bold ph-check-circle"></i> Aktif';
                        } else {
                            badge.className = 'inline-block px-3 py-1 border rounded-lg text-[10px] font-black uppercase tracking-wide transition-colors bg-rose-50 text-rose-600 border-rose-100';
                            badge.innerHTML = '<i class="ph-bold ph-x-circle"></i> Tidak Aktif';
                        }
                    }
                } else {
                    element.checked = !isChecked; // Kembalikan ke posisi awal jika gagal
                }
            }).catch(error => {
                element.checked = !isChecked; // Kembalikan ke posisi awal jika error jaringan
                console.error('Error:', error);
            });
        }
        
        function copyToken(token) {
            navigator.clipboard.writeText(token).then(() => {
                Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Token disalin!', showConfirmButton: false, timer: 2000, customClass: { popup: 'rounded-xl' }});
            });
        }
        
        function confirmDelete(id) {
            Swal.fire({ title: 'Hapus Ujian?', text: "Data ujian, jawaban, dan nilai dihapus permanen!", icon: 'warning', showCancelButton: true, confirmButtonColor: '#e11d48', cancelButtonColor: '#64748b', confirmButtonText: 'Hapus!', cancelButtonText: 'Batal', customClass: { popup: 'rounded-[2rem]' }
            }).then((result) => { if (result.isConfirmed) document.getElementById('delete-form-' + id).submit(); })
        }
        
        document.addEventListener("DOMContentLoaded", function() {
            const flash = document.getElementById('flash-success');
            if (flash) Swal.fire({ icon: 'success', title: 'Berhasil!', text: flash.getAttribute('data-message'), timer: 3000, showConfirmButton: false, customClass: { popup: 'rounded-[2rem]' }});
        });
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/cbt/show_event.blade.php ENDPATH**/ ?>