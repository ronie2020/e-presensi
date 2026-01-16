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
    
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-6 sm:py-8">
        
        
        <div class="mb-10 px-4 sm:px-0 print:hidden">
            <div class="relative rounded-[2.5rem] bg-gray-900 bg-gradient-to-br from-blue-900 via-blue-800 to-slate-900 p-8 sm:p-10 text-white shadow-2xl shadow-blue-900/40 overflow-hidden border border-white/10 group">
                
                
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-80 h-80 bg-blue-500/20 rounded-full blur-3xl pointer-events-none group-hover:bg-blue-500/30 transition-all duration-700"></div>
                <div class="absolute bottom-0 right-20 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
                
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                    
                    
                    <div class="max-w-2xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-200 text-[10px] font-bold uppercase tracking-widest mb-4 backdrop-blur-sm">
                            <i class="ph-fill ph-users-three"></i> Modul Kesiswaan
                        </div>
                        <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-3 flex items-center gap-3 text-white leading-tight">
                            Peserta Ekstrakurikuler
                        </h1>
                        <p class="text-blue-100/80 text-sm md:text-base font-medium leading-relaxed max-w-lg">
                            Kelola data keanggotaan siswa. Tambahkan anggota baru, pantau partisipasi, dan cetak daftar hadir per kegiatan.
                        </p>

                        
                        <?php if($selectedEkskulId): ?>
                            <div class="mt-8">
                                <button onclick="window.print()" class="px-6 py-3 bg-white text-blue-900 font-bold rounded-xl shadow-lg hover:bg-blue-50 hover:scale-105 transition-all flex items-center gap-2 transform active:scale-95">
                                    <div class="bg-blue-100 p-1 rounded-md">
                                        <i class="ph-bold ph-printer"></i>
                                    </div>
                                    <span>Cetak Absensi</span>
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    
                    <div class="flex flex-row md:flex-col lg:flex-row gap-4 w-full md:w-auto">
                        <?php if($selectedEkskulId): ?>
                            
                            <div class="bg-white/10 backdrop-blur-md px-6 py-5 rounded-2xl border border-white/10 flex-1 md:flex-none min-w-[160px] text-center md:text-left hover:bg-white/15 transition-colors">
                                <div class="flex items-center justify-center md:justify-start gap-2 mb-1 text-emerald-300">
                                    <i class="ph-duotone ph-user-check text-lg"></i>
                                    <span class="text-[10px] font-bold uppercase tracking-wider">Anggota Aktif</span>
                                </div>
                                <span class="block text-4xl font-black text-white tracking-tight"><?php echo e($members->total()); ?></span>
                                <span class="text-[10px] text-blue-200 block mt-1 truncate max-w-[140px]"><?php echo e($extracurriculars->find($selectedEkskulId)->name); ?></span>
                            </div>
                        <?php else: ?>
                            
                            <div class="bg-white/10 backdrop-blur-md px-6 py-5 rounded-2xl border border-white/10 flex-1 md:flex-none min-w-[160px] text-center md:text-left hover:bg-white/15 transition-colors">
                                <div class="flex items-center justify-center md:justify-start gap-2 mb-1 text-blue-300">
                                    <i class="ph-duotone ph-users text-lg"></i>
                                    <span class="text-[10px] font-bold uppercase tracking-wider">Total Partisipan</span>
                                </div>
                                <?php
                                    $totalAll = $extracurriculars->sum('members_count');
                                ?>
                                <span class="block text-4xl font-black text-white tracking-tight"><?php echo e($totalAll); ?></span>
                                <span class="text-[10px] text-blue-200 block mt-1">Semua Kegiatan</span>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 px-4 sm:px-0 items-start">
            
            
            <div class="lg:col-span-1 space-y-6 print:hidden">
                <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden sticky top-24">
                    <div class="p-6 bg-slate-50/50 border-b border-slate-100">
                        <h3 class="font-black text-slate-800 text-lg">Pilih Kegiatan</h3>
                        <p class="text-xs text-slate-500 font-medium mt-1">Pilih ekskul untuk melihat anggota.</p>
                    </div>
                    <div class="p-4 max-h-[70vh] overflow-y-auto custom-scrollbar">
                        <form method="GET" action="<?php echo e(route('extracurriculars.members')); ?>">
                            <div class="space-y-2">
                                <?php $__currentLoopData = $extracurriculars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ekskul): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <button type="submit" name="ekskul_id" value="<?php echo e($ekskul->id); ?>" 
                                        class="w-full flex items-center justify-between p-3 rounded-2xl transition-all group <?php echo e($selectedEkskulId == $ekskul->id ? 'bg-blue-900 text-white shadow-lg shadow-blue-900/30' : 'bg-white hover:bg-blue-50 text-slate-600 border border-slate-100'); ?>">
                                        <div class="flex items-center gap-3 text-left overflow-hidden">
                                            <div class="w-10 h-10 shrink-0 rounded-xl flex items-center justify-center text-lg <?php echo e($selectedEkskulId == $ekskul->id ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-400 group-hover:text-blue-600 group-hover:bg-blue-100'); ?>">
                                                <i class="<?php echo e($ekskul->icon && !Str::startsWith($ekskul->icon, 'storage') ? $ekskul->icon : 'ph-fill ph-star'); ?>"></i>
                                            </div>
                                            <span class="font-bold text-sm truncate pr-2"><?php echo e($ekskul->name); ?></span>
                                        </div>
                                        <span class="text-[10px] font-black px-2.5 py-1 rounded-lg shrink-0 <?php echo e($selectedEkskulId == $ekskul->id ? 'bg-white text-blue-900' : 'bg-slate-100 text-slate-500 group-hover:bg-blue-200 group-hover:text-blue-800'); ?>">
                                            <?php echo e($ekskul->members_count); ?>

                                        </span>
                                    </button>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            
            <div class="lg:col-span-2 space-y-6">
                <?php if($selectedEkskulId): ?>
                    <!-- Form Tambah Anggota (FILTER KELAS -> SISWA) -->
                    <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 p-8 print:hidden relative overflow-hidden">
                        
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-600 to-blue-400"></div>
                        
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-2xl shadow-sm border border-blue-100">
                                <i class="ph-duotone ph-user-plus"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-800 leading-none">Tambah Anggota</h3>
                                <p class="text-xs text-slate-400 font-bold mt-1 uppercase tracking-wider">Input Siswa Baru</p>
                            </div>
                        </div>
                        
                        <form action="<?php echo e(route('extracurriculars.members.store')); ?>" method="POST" class="flex flex-col gap-5">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="extracurricular_id" value="<?php echo e($selectedEkskulId); ?>">
                            
                            
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Langkah 1: Pilih Kelas</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                        <i class="ph-bold ph-chalkboard-teacher"></i>
                                    </div>
                                    <select id="filter-class" class="w-full pl-11 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm font-bold text-slate-700 py-3 transition-all appearance-none">
                                        <option value="">-- Pilih Kelas --</option>
                                        <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($c->id); ?>"><?php echo e($c->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                                </div>
                            </div>

                            
                            <div class="relative w-full">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Langkah 2: Pilih Siswa</label>
                                <select id="select-students" name="student_ids[]" multiple placeholder="Pilih kelas terlebih dahulu..." autocomplete="off" disabled class="rounded-2xl">
                                    
                                </select>
                                <p class="text-[10px] text-slate-400 mt-2 font-medium flex items-center gap-1">
                                    <i class="ph-fill ph-info"></i> Hanya siswa yang BELUM masuk ekskul ini yang akan muncul.
                                </p>
                            </div>
                            
                            <div class="flex justify-end pt-2">
                                <button type="submit" class="w-full sm:w-auto px-8 py-3.5 bg-blue-900 text-white font-bold rounded-2xl hover:bg-blue-800 transition-all shadow-lg shadow-blue-900/20 text-sm flex items-center justify-center gap-2 transform active:scale-95">
                                    <i class="ph-bold ph-plus-circle text-lg"></i> Simpan Anggota
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Tabel Anggota -->
                    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden print:shadow-none print:border-none print:rounded-none">
                        <div class="p-6 border-b border-slate-50 bg-slate-50/30 flex justify-between items-center print:border-b-2 print:border-black print:bg-white">
                            <div>
                                <h3 class="font-black text-slate-800 text-lg flex items-center gap-2">
                                    <i class="ph-fill ph-users text-blue-900 print:hidden"></i> Daftar Anggota Aktif
                                </h3>
                                <p class="hidden print:block text-sm font-bold text-slate-700 mt-1 uppercase tracking-wide">
                                    Kegiatan: <?php echo e($extracurriculars->find($selectedEkskulId)->name); ?>

                                </p>
                                <?php if($members->total() > 0): ?>
                                    <p class="text-xs text-slate-400 font-bold mt-1 print:hidden">
                                        Menampilkan <?php echo e($members->firstItem()); ?>-<?php echo e($members->lastItem()); ?> dari <?php echo e($members->total()); ?> siswa
                                    </p>
                                <?php endif; ?>
                            </div>
                            <span class="bg-white border border-slate-200 text-xs font-black px-3 py-1.5 rounded-xl text-slate-600 print:hidden shadow-sm">
                                Total: <?php echo e($members->total()); ?>

                            </span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead class="bg-slate-50/50 text-xs font-bold text-slate-500 uppercase tracking-wider print:bg-white print:text-black print:border-b-2 print:border-black">
                                    <tr>
                                        <th class="px-6 py-4 print:py-2 w-10">No</th>
                                        <th class="px-6 py-4 print:py-2">Identitas Siswa</th>
                                        <th class="px-6 py-4 print:py-2 text-center">Kelas</th>
                                        <th class="hidden print:table-cell px-6 py-4 border-l border-black text-center w-40">Paraf</th>
                                        <th class="px-6 py-4 text-right print:hidden">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50 print:divide-slate-300">
                                    <?php $__empty_1 = true; $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr class="hover:bg-blue-50/30 transition-colors group">
                                            <td class="px-6 py-4 print:py-2 text-xs font-bold text-slate-500">
                                                <?php echo e($members->firstItem() + $index); ?>

                                            </td>
                                            <td class="px-6 py-4 print:py-2">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-blue-600 font-black text-sm shadow-sm print:hidden group-hover:border-blue-200 transition-colors">
                                                        <?php echo e(substr($member->student->name, 0, 2)); ?>

                                                    </div>
                                                    <div>
                                                        <span class="font-bold text-slate-800 text-sm block group-hover:text-blue-700 transition-colors"><?php echo e($member->student->name); ?></span>
                                                        <span class="text-[10px] text-slate-400 font-mono font-bold bg-slate-100 px-1.5 py-0.5 rounded border border-slate-200 print:hidden"><?php echo e($member->student->nis); ?></span>
                                                        <span class="hidden print:inline text-xs">(<?php echo e($member->student->nis); ?>)</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 print:py-2 text-center">
                                                <span class="inline-flex px-3 py-1 bg-slate-50 border border-slate-100 rounded-lg text-xs font-bold text-slate-600 print:border-none print:bg-transparent print:p-0">
                                                    <?php echo e($member->student->schoolClass->name ?? '-'); ?>

                                                </span>
                                            </td>
                                            <td class="hidden print:table-cell border-l border-slate-300"></td>
                                            <td class="px-6 py-4 text-right print:hidden">
                                                <form action="<?php echo e(route('extracurriculars.members.destroy', $member->id)); ?>" method="POST" class="delete-form inline-block">
                                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                    <button type="button" class="btn-delete w-9 h-9 rounded-xl flex items-center justify-center text-slate-400 hover:text-rose-600 hover:bg-rose-50 border border-transparent hover:border-rose-100 transition-all" title="Keluarkan">
                                                        <i class="ph-bold ph-sign-out text-lg"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="5" class="px-6 py-16 text-center">
                                                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300 border border-slate-100">
                                                    <i class="ph-duotone ph-users-three text-4xl"></i>
                                                </div>
                                                <p class="text-sm font-bold text-slate-600">Belum ada anggota terdaftar.</p>
                                                <p class="text-xs text-slate-400 mt-1">Gunakan formulir di atas untuk menambahkan siswa.</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="p-6 border-t border-slate-50 print:hidden">
                            <?php echo e($members->links()); ?> 
                        </div>
                    </div>
                <?php else: ?>
                    
                    <div class="flex flex-col items-center justify-center h-80 bg-slate-50/50 rounded-[2.5rem] border-2 border-dashed border-slate-200 text-center px-4 group hover:border-blue-200 hover:bg-blue-50/30 transition-all">
                        <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mb-6 text-slate-300 shadow-sm border border-slate-100 group-hover:scale-110 transition-transform duration-300">
                            <i class="ph-duotone ph-hand-pointing text-5xl"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-700 mb-2">Pilih Kegiatan Dahulu</h3>
                        <p class="text-sm text-slate-400 font-medium max-w-xs mx-auto leading-relaxed">Silakan pilih salah satu ekstrakurikuler di menu sebelah kiri untuk mulai mengelola anggotanya.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // --- 1. SETUP DATA SISWA (Dari Laravel ke JS) ---
            <?php
                $studentsData = $students->map(function($s) {
                    return [
                        'id' => $s->id,
                        'name' => $s->name,
                        'nis' => $s->nis,
                        'class_id' => $s->class_id, 
                        'class_name' => optional($s->schoolClass)->name ?? '-'
                    ];
                })->values();
            ?>

            const allStudents = <?php echo json_encode($studentsData, 15, 512) ?>;

            // --- 2. SETUP TOM SELECT ---
            let studentSelect;
            if(document.getElementById('select-students')) {
                studentSelect = new TomSelect('#select-students', {
                    create: false,
                    sortField: { field: "text", direction: "asc" },
                    placeholder: "Pilih kelas terlebih dahulu...",
                    plugins: ['dropdown_input', 'remove_button'],
                    maxOptions: null,
                    valueField: 'id',
                    labelField: 'name',
                    searchField: ['name', 'nis'],
                    render: {
                        option: function(data, escape) {
                            return '<div class="py-1">' +
                                '<span class="font-bold text-slate-700 block text-sm">' + escape(data.name) + '</span>' +
                                '<span class="text-xs text-slate-400 font-mono">NIS: ' + escape(data.nis || '-') + '</span>' +
                            '</div>';
                        },
                        item: function(data, escape) {
                            return '<div title="' + escape(data.name) + '">' + escape(data.name) + '</div>';
                        }
                    }
                });
            }

            // --- 3. LOGIKA FILTER KELAS ---
            const classFilter = document.getElementById('filter-class');
            if(classFilter && studentSelect) {
                classFilter.addEventListener('change', function() {
                    const selectedClassId = this.value;
                    
                    studentSelect.clear();
                    studentSelect.clearOptions();

                    if(selectedClassId) {
                        const filteredStudents = allStudents.filter(s => s.class_id == selectedClassId);
                        
                        filteredStudents.forEach(s => {
                            studentSelect.addOption(s);
                        });

                        studentSelect.settings.placeholder = "Pilih siswa (Total: " + filteredStudents.length + ")";
                        studentSelect.enable();
                        studentSelect.refreshOptions(false); 
                    } else {
                        studentSelect.settings.placeholder = "Pilih kelas terlebih dahulu...";
                        studentSelect.disable();
                    }
                    studentSelect.sync();
                });
            }

            // --- 4. SWEETALERT ---
            <?php if(session('success')): ?>
                Swal.fire({
                    icon: 'success', title: 'Berhasil!', text: "<?php echo e(session('success')); ?>",
                    timer: 3000, showConfirmButton: false, toast: true, position: 'top-end',
                    customClass: { popup: 'rounded-xl' }
                });
            <?php endif; ?>
            <?php if(session('error')): ?>
                Swal.fire({ icon: 'error', title: 'Gagal!', text: "<?php echo e(session('error')); ?>" });
            <?php endif; ?>

            // --- 5. DELETE CONFIRM ---
            document.querySelectorAll('.btn-delete').forEach(button => {
                button.addEventListener('click', function(e) {
                    const form = this.closest('.delete-form');
                    Swal.fire({
                        title: 'Keluarkan Siswa?', text: "Siswa akan dihapus dari daftar anggota ekskul ini.",
                        icon: 'warning', showCancelButton: true,
                        confirmButtonColor: '#e11d48', cancelButtonColor: '#64748b',
                        confirmButtonText: 'Ya, Keluarkan!', cancelButtonText: 'Batal',
                        borderRadius: '1.5rem',
                        customClass: {
                            popup: 'rounded-[2rem]',
                            confirmButton: 'rounded-xl px-6 py-2.5 font-bold',
                            cancelButton: 'rounded-xl px-6 py-2.5 font-bold'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) form.submit();
                    });
                });
            });
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
<?php endif; ?><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/extracurriculars/members.blade.php ENDPATH**/ ?>