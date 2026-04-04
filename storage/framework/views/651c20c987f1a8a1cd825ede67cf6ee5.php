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
            <?php echo e(__('CBT Dashboard')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-8 sm:py-10 font-sans text-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            
            <?php if(session('success')): ?>
                <div id="flash-success" data-message="<?php echo e(session('success')); ?>"></div>
            <?php endif; ?>
            <?php if(session('error')): ?>
                <div id="flash-error" data-message="<?php echo e(session('error')); ?>"></div>
            <?php endif; ?>

            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                
                <div class="bg-gray-900 bg-gradient-to-br from-slate-900 via-blue-900 to-blue-800 rounded-[2rem] p-8 text-white shadow-xl shadow-blue-900/30 relative overflow-hidden group border border-white/10">
                    <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                    <div class="absolute right-0 top-0 opacity-10 transform translate-x-8 -translate-y-8 group-hover:scale-110 transition-transform duration-500">
                        <i class="ph-fill ph-monitor-play text-[10rem]"></i>
                    </div>
                    
                    <div class="relative z-10 h-full flex flex-col justify-between">
                        <div>
                            <a href="<?php echo e(route('dashboard')); ?>" class="group bg-white/10 hover:bg-white/20 text-white px-5 py-3 rounded-2xl font-bold text-sm backdrop-blur-sm border border-white/10 transition-all flex items-center gap-2 shadow-sm w-fit mb-4 mx-auto xl:mx-0">
                                <i class="ph-bold ph-arrow-left text-lg group-hover:-translate-x-1 transition-transform"></i>
                                <span>Kembali ke Dashboard</span>
                            </a>
                            <p class="text-blue-300 font-bold text-sm mb-1 flex items-center gap-2"><i class="ph-bold ph-calendar-blank"></i> Hari Ini</p>
                            <h3 class="text-3xl font-black tracking-tight leading-tight"><?php echo e(\Carbon\Carbon::now()->translatedFormat('l, d F Y')); ?></h3>
                        </div>
                        <div class="mt-6">
                            <span class="bg-white/10 backdrop-blur-md px-4 py-2.5 rounded-xl text-sm font-bold border border-white/10 shadow-sm inline-flex items-center gap-2">
                                <span class="bg-emerald-400 w-2 h-2 rounded-full animate-pulse"></span>
                                <?php echo e(method_exists($exams, 'total') ? $exams->total() : $exams->count()); ?> Ujian Terdaftar
                            </span>
                        </div>
                    </div>
                </div>
                
                
                <div class="md:col-span-2 bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 flex flex-col md:flex-row items-center justify-between relative overflow-hidden">
                    <div class="absolute inset-0 bg-slate-50/50 opacity-0 md:opacity-100 bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:20px_20px]"></div>
                    
                    <div class="relative z-10 max-w-lg mb-6 md:mb-0 text-center md:text-left">
                        <h3 class="font-black text-slate-800 text-2xl mb-2">Halo, <?php echo e(Auth::user()->name); ?>! </h3>
                        <p class="text-slate-500 leading-relaxed font-medium text-sm">
                            Kelola ujian berbasis komputer dengan mudah. Pantau nilai siswa dan aktivasi token ujian di sini.
                        </p>
                    </div>

                    <div class="relative z-10">
                        <a href="<?php echo e(route('cbt.create')); ?>" class="group flex items-center gap-3 px-6 py-4 bg-blue-600 text-white rounded-2xl font-bold hover:bg-blue-700 active:scale-95 transition-all shadow-lg shadow-blue-500/30">
                            <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center group-hover:scale-110 transition">
                                <i class="ph-bold ph-plus text-white"></i>
                            </div>
                            <span>Buat Jadwal Baru</span>
                        </a>
                    </div>
                </div>
            </div>

            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
                <div class="bg-white rounded-[1.5rem] p-5 border border-slate-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center text-xl shrink-0"><i class="ph-bold ph-check-circle"></i></div>
                    <div><p class="text-[10px] text-slate-400 font-bold uppercase mb-0.5 tracking-wider">Ujian Aktif</p><h4 class="text-2xl font-black text-slate-800"><?php echo e($stats['active_exams']); ?></h4></div>
                </div>
                <div class="bg-white rounded-[1.5rem] p-5 border border-slate-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-full flex items-center justify-center text-xl shrink-0"><i class="ph-bold ph-list-numbers"></i></div>
                    <div><p class="text-[10px] text-slate-400 font-bold uppercase mb-0.5 tracking-wider">Total Bank Soal</p><h4 class="text-2xl font-black text-slate-800"><?php echo e(number_format($stats['total_questions'])); ?></h4></div>
                </div>
                <div class="bg-white rounded-[1.5rem] p-5 border border-slate-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center text-xl shrink-0"><i class="ph-bold ph-users"></i></div>
                    <div><p class="text-[10px] text-slate-400 font-bold uppercase mb-0.5 tracking-wider">Siswa Online</p><h4 class="text-2xl font-black text-slate-800"><?php echo e(number_format($stats['students_working'])); ?></h4></div>
                </div>
                <div class="bg-white rounded-[1.5rem] p-5 border border-slate-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-full flex items-center justify-center text-xl shrink-0"><i class="ph-bold ph-chart-line-up"></i></div>
                    <div><p class="text-[10px] text-slate-400 font-bold uppercase mb-0.5 tracking-wider">Rata-rata Nilai</p><h4 class="text-2xl font-black text-slate-800"><?php echo e(number_format($stats['avg_score'], 1)); ?></h4></div>
                </div>
            </div>

            
            <form id="filterForm" action="<?php echo e(route('cbt.index')); ?>" method="GET" class="flex flex-col md:flex-row items-center justify-between mb-6 px-2 gap-4">
                <h3 class="font-bold text-slate-800 text-xl flex items-center gap-2 shrink-0 w-full md:w-auto">
                    <div class="w-1.5 h-6 bg-blue-600 rounded-full"></div>
                    Daftar Ujian CBT
                </h3>

                <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                    
                    <input type="hidden" name="filter" id="filterInput" value="<?php echo e(request('filter', 'all')); ?>">
                    <div class="flex p-1 bg-white border border-slate-200 rounded-xl gap-1 shadow-sm overflow-x-auto">
                        <button type="button" onclick="document.getElementById('filterInput').value='all'; document.getElementById('filterForm').submit();" class="<?php echo e(request('filter', 'all') == 'all' ? 'bg-slate-100 text-blue-600' : 'text-slate-500 hover:text-slate-700'); ?> px-4 py-2 rounded-lg text-xs font-bold transition-all whitespace-nowrap">Semua</button>
                        <button type="button" onclick="document.getElementById('filterInput').value='active'; document.getElementById('filterForm').submit();" class="<?php echo e(request('filter') == 'active' ? 'bg-emerald-50 text-emerald-600' : 'text-slate-500 hover:text-slate-700'); ?> px-4 py-2 rounded-lg text-xs font-bold transition-all whitespace-nowrap">Aktif</button>
                        <button type="button" onclick="document.getElementById('filterInput').value='inactive'; document.getElementById('filterForm').submit();" class="<?php echo e(request('filter') == 'inactive' ? 'bg-slate-100 text-slate-600' : 'text-slate-500 hover:text-slate-700'); ?> px-4 py-2 rounded-lg text-xs font-bold transition-all whitespace-nowrap">Non-Aktif</button>
                    </div>

                    
                    <div class="relative w-full sm:w-64 group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-500 transition-colors">
                            <i class="ph-bold ph-magnifying-glass"></i>
                        </div>
                        <input name="search" value="<?php echo e(request('search')); ?>" type="text" class="w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 bg-white text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-50 transition-all font-medium placeholder-slate-400 shadow-sm" placeholder="Cari jadwal ujian...">
                        <button type="submit" class="hidden"></button>
                    </div>
                </div>
            </form>

            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php $__empty_1 = true; $__currentLoopData = $exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="bg-white border border-slate-100 rounded-[2rem] p-6 hover:shadow-xl hover:shadow-blue-900/5 hover:border-blue-200 transition-all duration-300 group relative flex flex-col h-full">
                        
                       
                        <div class="absolute top-6 right-6 z-10">
                            <label class="relative inline-flex items-center cursor-pointer group/toggle" title="Klik untuk On/Off Ujian">
                                <input type="checkbox" class="sr-only peer" <?php echo e($exam->is_active ? 'checked' : ''); ?> onchange="toggleStatus('<?php echo e($exam->id); ?>', this)">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500 shadow-inner"></div>
                            </label>
                        </div>

                       <!-- Header Card -->
                        <div class="mb-4 pr-16">
                            <div class="flex flex-wrap gap-2 mb-3">
                                <span class="inline-block px-3 py-1 bg-indigo-50 text-indigo-600 border border-indigo-100 rounded-lg text-[10px] font-black uppercase tracking-wide">
                                    <?php echo e($exam->subject_name); ?>

                                </span>
                                
                                
                                <?php if(isset($exam->exam_type) && $exam->exam_type == 'google_form'): ?>
                                    <span class="inline-block px-3 py-1 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-lg text-[10px] font-black uppercase tracking-wide" title="Menggunakan Google Form">
                                        <i class="ph-bold ph-google-logo"></i> G-Form
                                    </span>
                                <?php else: ?>
                                    <span class="inline-block px-3 py-1 bg-blue-50 text-blue-600 border border-blue-100 rounded-lg text-[10px] font-black uppercase tracking-wide" title="CBT Internal Sistem">
                                        <i class="ph-bold ph-desktop"></i> CBT
                                    </span>
                                <?php endif; ?>
                            </div>

                            <h4 class="font-black text-xl text-slate-800 leading-tight group-hover:text-blue-600 transition-colors line-clamp-2" title="<?php echo e($exam->title); ?>">
                                <?php echo e($exam->title); ?>

                            </h4>
                        </div>

                        
                        <div class="mb-4 p-3 bg-slate-50 border border-slate-100 rounded-xl space-y-2">
                            <div class="flex items-center text-xs font-bold text-slate-600">
                                <i class="ph-fill ph-play-circle text-emerald-500 text-base mr-2"></i>
                                <span class="w-12 text-slate-400">Mulai</span>
                                <span>: <?php echo e($exam->start_time ? \Carbon\Carbon::parse($exam->start_time)->format('d M Y, H:i') : 'Belum diatur'); ?></span>
                            </div>
                            <div class="flex items-center text-xs font-bold text-slate-600">
                                <i class="ph-fill ph-stop-circle text-rose-500 text-base mr-2"></i>
                                <span class="w-12 text-slate-400">Akhir</span>
                                <span>: <?php echo e($exam->end_time ? \Carbon\Carbon::parse($exam->end_time)->format('d M Y, H:i') : 'Belum diatur'); ?></span>
                            </div>
                        </div>
                        
                        <!-- Content: Token & Meta -->
                        <div class="flex-1 space-y-4">
                            <div class="bg-white border border-slate-200 rounded-2xl p-4 flex items-center justify-between group/token cursor-pointer hover:bg-blue-50 hover:border-blue-200 transition shadow-sm" onclick="copyToken('<?php echo e($exam->token); ?>')">
                                <div>
                                    <span class="text-[10px] text-slate-400 uppercase font-bold tracking-wider block mb-0.5">Token Ujian</span>
                                    <span class="font-mono font-black text-xl text-slate-700 tracking-widest group-hover/token:text-blue-600"><?php echo e($exam->token); ?></span>
                                </div>
                                <div class="w-8 h-8 bg-slate-50 rounded-full flex items-center justify-center text-slate-400 shadow-sm border border-slate-100 group-hover/token:text-blue-500 group-hover/token:scale-110 transition">
                                    <i class="ph-bold ph-copy"></i>
                                </div>
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
                                <a href="<?php echo e($exam->google_form_url); ?>" target="_blank" class="flex items-center justify-center p-2.5 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-xl text-xs font-bold hover:bg-emerald-600 hover:text-white transition-all group/btn" title="Buka Link Google Form">
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
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-20 bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200 mt-4">
                        <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                            <i class="ph-duotone ph-magnifying-glass text-5xl"></i>
                        </div>
                        <h3 class="text-slate-800 font-bold text-xl mb-2">Tidak Ada Data Ditemukan</h3>
                        <p class="text-slate-500 max-w-xs mx-auto mb-8 text-sm">Coba gunakan kata kunci lain atau buat jadwal ujian baru.</p>
                        <a href="<?php echo e(route('cbt.create')); ?>" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-500/30 text-sm">
                            <i class="ph-bold ph-plus"></i> Buat Jadwal Baru
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            
            <?php if(method_exists($exams, 'links')): ?>
                <div class="mt-8">
                    <?php echo e($exams->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Fungsi JS untuk Quick Toggle On/Off via AJAX
        function toggleStatus(id, element) {
            fetch(`/cbt/exams/${id}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        toast: true, position: 'top-end', icon: 'success', 
                        title: data.message, showConfirmButton: false, timer: 2000,
                        customClass: { popup: 'rounded-xl' }
                    });
                } else {
                    element.checked = !element.checked; // Kembalikan posisi jika gagal
                }
            })
            .catch(error => {
                element.checked = !element.checked;
                console.error('Error:', error);
            });
        }

        // Salin Token
        function copyToken(token) {
            navigator.clipboard.writeText(token).then(() => {
                Swal.fire({
                    toast: true, position: 'top-end', icon: 'success', 
                    title: 'Token disalin!', showConfirmButton: false, timer: 2000,
                    customClass: { popup: 'rounded-xl' }
                })
            });
        }

        // Konfirmasi Hapus
        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Ujian?',
                text: "Data ujian, jawaban siswa, dan nilai akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                customClass: { popup: 'rounded-[2rem]' }
            }).then((result) => {
                if (result.isConfirmed) document.getElementById('delete-form-' + id).submit();
            })
        }

        // Tampilkan Notifikasi Flash Session
        document.addEventListener("DOMContentLoaded", function() {
            const flashSuccess = document.getElementById('flash-success');
            const flashError = document.getElementById('flash-error');
            
            if (flashSuccess) {
                Swal.fire({
                    icon: 'success', title: 'Berhasil!',
                    text: flashSuccess.getAttribute('data-message'),
                    timer: 3000, showConfirmButton: false,
                    customClass: { popup: 'rounded-[2rem]' }
                });
            }
            if (flashError) {
                Swal.fire({
                    icon: 'error', title: 'Gagal!',
                    text: flashError.getAttribute('data-message'),
                    timer: 4000, showConfirmButton: true,
                    customClass: { popup: 'rounded-[2rem]' }
                });
            }
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
<?php endif; ?><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/cbt/index.blade.php ENDPATH**/ ?>