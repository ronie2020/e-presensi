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
    
    <?php $__env->startPush('scripts'); ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php $__env->stopPush(); ?>

    <div class="py-8 sm:py-10 font-sans text-slate-800">
        
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10">
            <div class="relative rounded-[2.5rem] bg-gray-900 bg-gradient-to-br from-blue-950 via-blue-900 to-slate-900 p-8 sm:p-10 text-white shadow-2xl shadow-blue-900/40 overflow-hidden border border-white/10 group">
                
                
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-96 h-96 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none group-hover:bg-emerald-500/30 transition-all duration-700"></div>
                <div class="absolute bottom-0 right-20 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col xl:flex-row justify-between items-start xl:items-center gap-8">
                    <div class="space-y-2">
                        <a href="<?php echo e(route('dashboard')); ?>" class="group bg-white/10 hover:bg-white/20 text-white px-5 py-3 rounded-2xl font-bold text-sm backdrop-blur-sm border border-white/10 transition-all flex items-center gap-2 shadow-sm w-fit mb-4 mx-auto xl:mx-0">
                            <i class="ph-bold ph-arrow-left text-m group-hover:-translate-x-1 transition-transform"></i>
                            <span>Kembali ke Dashboard</span>
                        </a>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-300 text-xs font-bold uppercase tracking-widest backdrop-blur-sm">
                            <i class="ph-fill ph-graduation-cap"></i> Manajemen Akademik
                        </div>
                        <h1 class="text-3xl sm:text-4xl font-black tracking-tight leading-tight">
                            Kelulusan & <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-emerald-200">SKL Digital</span>
                        </h1>
                        <p class="text-blue-200/80 text-sm sm:text-base max-w-xl font-medium">
                            Kelola status kelulusan siswa tingkat akhir, generate SKL, dan publikasi pengumuman secara terpusat.
                        </p>
                    </div>

                    
                    <div class="flex flex-wrap gap-3 w-full xl:w-auto">
                        <!-- Tombol Set Tanggal -->
                        <button onclick="document.getElementById('modalGlobalDate').showModal()" class="flex-1 xl:flex-none btn-secondary bg-white/10 hover:bg-white/20 border-white/10 text-white backdrop-blur-md px-5 py-3 rounded-xl font-bold text-sm transition-all flex items-center justify-center gap-2">
                            <i class="ph-bold ph-calendar-check text-lg"></i>
                            <span>Set Tanggal</span>
                        </button>
                        
                        <!-- Tombol Import -->
                        <button onclick="document.getElementById('modalImport').showModal()" class="flex-1 xl:flex-none btn-secondary bg-white/10 hover:bg-white/20 border-white/10 text-white backdrop-blur-md px-5 py-3 rounded-xl font-bold text-sm transition-all flex items-center justify-center gap-2">
                            <i class="ph-bold ph-file-csv text-lg"></i>
                            <span>Import CSV</span>
                        </button>

                        <!-- [BARU] Tombol Generate Nomor SKL Massal -->
                        <button onclick="document.getElementById('modalSkl').showModal()" class="flex-1 xl:flex-none btn-primary bg-blue-600 hover:bg-blue-500 text-white shadow-lg shadow-blue-900/20 px-5 py-3 rounded-xl font-bold text-sm transition-all flex items-center justify-center gap-2 border border-blue-400/50">
                            <i class="ph-bold ph-list-numbers text-lg"></i>
                            <span>Set No. SKL (Auto)</span>
                        </button>

                        <!-- Tombol Pengaturan SKL -->
                        <button onclick="document.getElementById('modalSettings').showModal()" class="flex-1 xl:flex-none btn-secondary bg-white/10 hover:bg-white/20 border-white/10 text-white backdrop-blur-md px-5 py-3 rounded-xl font-bold text-sm transition-all flex items-center justify-center gap-2">
                            <i class="ph-bold ph-gear text-lg"></i>
                            <span>Pengaturan SKL</span>
                        </button>

                        <!-- Tombol Pindahkan ke Alumni -->
                        <form action="<?php echo e(route('admin.graduation.process_alumni')); ?>" method="POST" id="formProcessAlumni" class="w-full xl:w-auto">
                            <?php echo csrf_field(); ?>
                            <button type="button" onclick="confirmAlumniProcess()" class="w-full btn-primary bg-emerald-500 hover:bg-emerald-400 text-white shadow-lg shadow-emerald-900/20 px-6 py-3 rounded-xl font-bold text-sm transition-all flex items-center justify-center gap-2 border border-emerald-400/50">
                                <i class="ph-bold ph-users-three text-lg"></i>
                                <span>Pindahkan ke Alumni</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 flex flex-col sm:flex-row gap-4 justify-between items-center">
                
                
                <form method="GET" class="w-full sm:w-auto flex items-center gap-2">
                    <div class="relative w-full sm:w-64">
                        <i class="ph-bold ph-chalkboard-teacher absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <select name="class_id" onchange="this.form.submit()" class="w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 bg-slate-50 text-sm font-bold text-slate-700 focus:ring-blue-500 focus:border-blue-500 appearance-none cursor-pointer">
                            <option value="">Semua Kelas 9</option>
                            <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($class->id); ?>" <?php echo e(request('class_id') == $class->id ? 'selected' : ''); ?>><?php echo e($class->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <i class="ph-bold ph-caret-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                    </div>
                </form>

                
                <form method="GET" class="w-full sm:w-auto relative">
                    <?php if(request('class_id')): ?> 
                        <input type="hidden" name="class_id" value="<?php echo e(request('class_id')); ?>"> 
                    <?php endif; ?>
                    <i class="ph-bold ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari Siswa / NISN..." 
                           class="w-full sm:w-72 pl-10 pr-4 py-2.5 rounded-xl border-slate-200 bg-slate-50 text-sm font-bold focus:ring-blue-500 focus:border-blue-500 transition-all">
                </form>
            </div>
        </div>

        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form action="<?php echo e(route('admin.graduation.bulk_update')); ?>" method="POST" id="bulkForm">
                <?php echo csrf_field(); ?>
                <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden">
                    
                    
                    <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div class="flex items-center gap-2">
                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-lg text-xs font-bold">Total: <?php echo e($students->total()); ?> Siswa</span>
                        </div>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl text-xs font-bold shadow-lg shadow-blue-500/30 transition flex items-center gap-2">
                            <i class="ph-bold ph-floppy-disk"></i> Simpan Perubahan Masal
                        </button>
                    </div>

                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-slate-50 text-xs uppercase font-bold text-slate-400 tracking-wider">
                                <tr>
                                    <th class="px-6 py-4 rounded-tl-2xl">Identitas Siswa</th>
                                    <th class="px-6 py-4">Status Kelulusan</th>
                                    <th class="px-6 py-4 text-center">Nilai Rata-rata</th>
                                    <th class="px-6 py-4">No. SKL</th>
                                    <th class="px-6 py-4 rounded-tr-2xl text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm">
                                <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-blue-50/30 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 font-bold text-xs shrink-0 overflow-hidden">
                                                <?php if($student->photo_path): ?>
                                                    <img src="<?php echo e(asset('storage/'.$student->photo_path)); ?>" class="w-full h-full object-cover">
                                                <?php else: ?>
                                                    <?php echo e(substr($student->name, 0, 2)); ?>

                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <div class="font-bold text-slate-800 group-hover:text-blue-600 transition"><?php echo e($student->name); ?></div>
                                                <div class="text-xs text-slate-400 font-medium"><?php echo e($student->student_id); ?> &bull; <?php echo e($student->schoolClass->name ?? '-'); ?></div>
                                            </div>
                                        </div>
                                        <?php if($student->status == 'graduated'): ?>
                                            <span class="mt-1 inline-block px-2 py-0.5 bg-emerald-100 text-emerald-700 text-[10px] font-bold rounded">Sudah Alumni</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <select name="students[<?php echo e($student->id); ?>][status]" class="w-32 py-1.5 px-3 rounded-lg text-xs font-bold border-slate-200 focus:ring-blue-500 focus:border-blue-500 cursor-pointer
                                            <?php echo e($student->graduation?->status == 'LULUS' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : ''); ?>

                                            <?php echo e($student->graduation?->status == 'TIDAK LULUS' ? 'bg-rose-50 text-rose-700 border-rose-200' : ''); ?>">
                                            <option value="DITUNDA" <?php echo e(($student->graduation?->status ?? 'DITUNDA') == 'DITUNDA' ? 'selected' : ''); ?>>Ditunda</option>
                                            <option value="LULUS" <?php echo e(($student->graduation?->status ?? '') == 'LULUS' ? 'selected' : ''); ?>>Lulus</option>
                                            <option value="TIDAK LULUS" <?php echo e(($student->graduation?->status ?? '') == 'TIDAK LULUS' ? 'selected' : ''); ?>>Tidak Lulus</option>
                                        </select>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <input type="number" step="0.01" name="students[<?php echo e($student->id); ?>][average_score]" value="<?php echo e($student->graduation?->average_score); ?>" 
                                            class="w-20 text-center py-1.5 rounded-lg border-slate-200 text-xs font-bold focus:ring-blue-500 focus:border-blue-500 bg-slate-50 focus:bg-white transition" placeholder="0.00">
                                    </td>
                                    <td class="px-6 py-4">
                                        <input type="text" name="students[<?php echo e($student->id); ?>][skl_number]" value="<?php echo e($student->graduation?->skl_number); ?>" 
                                            class="w-40 py-1.5 rounded-lg border-slate-200 text-xs font-medium focus:ring-blue-500 focus:border-blue-500 bg-slate-50 focus:bg-white transition" placeholder="Kosong = Default">
                                        
                                        
                                        <input type="hidden" name="students[<?php echo e($student->id); ?>][announcement_date]" value="<?php echo e($student->graduation?->announcement_date); ?>">
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <button type="button" onclick="saveSingle('<?php echo e($student->id); ?>')" class="p-2 rounded-lg text-slate-400 hover:bg-blue-50 hover:text-blue-600 transition" title="Simpan Baris Ini">
                                                <i class="ph-bold ph-check"></i>
                                            </button>
                                            
                                            <?php if($student->graduation?->status == 'LULUS'): ?>
                                                <a href="<?php echo e(route('graduation.print', $student->id)); ?>" target="_blank" class="p-2 rounded-lg text-emerald-500 hover:bg-emerald-50 transition" title="Cetak SKL">
                                                    <i class="ph-bold ph-printer"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                        <span id="msg_<?php echo e($student->id); ?>" class="text-[10px] text-emerald-600 font-bold hidden animate-pulse">Tersimpan!</span>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                        <div class="flex flex-col items-center justify-center gap-2">
                                            <i class="ph-duotone ph-student text-3xl"></i>
                                            <span class="font-medium">Tidak ada data siswa kelas 9 ditemukan.</span>
                                        </div>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    
                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
                        <?php echo e($students->links()); ?>

                    </div>
                </div>
            </form>
        </div>
    </div>

    
    
    

    
    <dialog id="modalSkl" class="modal rounded-3xl p-0 backdrop:bg-slate-900/50">
        <form action="<?php echo e(route('admin.graduation.bulk_skl')); ?>" method="POST" class="bg-white p-8 w-full max-w-md rounded-3xl shadow-2xl">
            <?php echo csrf_field(); ?>
            <h3 class="text-lg font-bold mb-2">Generate Nomor SKL Massal</h3>
            
            <div class="p-4 mb-5 rounded-2xl bg-blue-50 border border-blue-100 flex gap-3 items-start">
                <i class="ph-fill ph-info text-xl text-blue-500 mt-0.5"></i>
                <div class="text-sm text-blue-800 leading-relaxed">
                    Gunakan tag <b class="text-blue-700 font-mono px-1 bg-blue-200/50 rounded">{urut}</b> agar sistem membuat urutan otomatis (001, 002, dst) ke database.<br><br>
                    Contoh: <br><b>421.3/{urut}/SMP.03/2026</b>
                </div>
            </div>

            <?php if(request('class_id')): ?> <input type="hidden" name="class_filter" value="<?php echo e(request('class_id')); ?>"> <?php endif; ?>
            
            <div class="mb-4">
                <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Format Nomor Surat</label>
                <input type="text" name="skl_format" required class="w-full rounded-xl border-slate-200 font-bold focus:border-blue-500 focus:ring-blue-500" placeholder="Cth: 421.3/{urut}/SMP.03/2026">
            </div>

            <div class="mb-6">
                <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Mulai Dari Urutan Ke</label>
                <input type="number" name="start_number" value="1" min="1" class="w-full rounded-xl border-slate-200 font-bold focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="this.closest('dialog').close()" class="px-4 py-2 rounded-xl text-sm font-bold text-slate-500 hover:bg-slate-50">Batal</button>
                <button type="submit" class="px-6 py-2 rounded-xl bg-blue-600 text-white text-sm font-bold hover:bg-blue-700 shadow-lg shadow-blue-500/30">Generate ke Database</button>
            </div>
        </form>
    </dialog>

    
    <dialog id="modalGlobalDate" class="modal rounded-3xl p-0 backdrop:bg-slate-900/50">
        <form action="<?php echo e(route('admin.graduation.set_date')); ?>" method="POST" class="bg-white p-8 w-full max-w-md rounded-3xl shadow-2xl">
            <?php echo csrf_field(); ?>
            <h3 class="text-lg font-bold mb-2">Set Tanggal Pengumuman</h3>
            
            <div class="p-4 mb-5 rounded-2xl bg-emerald-50 border border-emerald-100 flex gap-3 items-start">
                <i class="ph-fill ph-info text-xl text-emerald-500 mt-0.5"></i>
                <div class="text-sm text-emerald-800 leading-relaxed">
                    Mengatur jadwal di sini akan otomatis membuat seluruh siswa berstatus <b class="text-emerald-700">LULUS</b>. Jika ada yang tidak lulus/bermasalah, Anda dapat mengubahnya secara manual di tabel.
                </div>
            </div>

            <?php if(request('class_id')): ?> <input type="hidden" name="class_filter" value="<?php echo e(request('class_id')); ?>"> <?php endif; ?>
            <div class="mb-6">
                <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Tanggal & Jam Buka</label>
                <input type="datetime-local" name="global_date" required class="w-full rounded-xl border-slate-200 font-bold">
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="this.closest('dialog').close()" class="px-4 py-2 rounded-xl text-sm font-bold text-slate-500 hover:bg-slate-50">Batal</button>
                <button type="submit" class="px-6 py-2 rounded-xl bg-blue-600 text-white text-sm font-bold hover:bg-blue-700 shadow-lg shadow-blue-500/30">Simpan Jadwal</button>
            </div>
        </form>
    </dialog>

    
    <dialog id="modalImport" class="modal rounded-3xl p-0 backdrop:bg-slate-900/50">
        <form action="<?php echo e(route('admin.graduation.import')); ?>" method="POST" enctype="multipart/form-data" class="bg-white p-8 w-full max-w-md rounded-3xl shadow-2xl">
            <?php echo csrf_field(); ?>
            <h3 class="text-lg font-bold mb-2">Import Data CSV</h3>
            <p class="text-xs text-slate-500 mb-6">Format: NISN, STATUS (LULUS/TIDAK), NILAI</p>
            <input type="file" name="file" accept=".csv" required class="w-full mb-6 text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            <div class="flex justify-end gap-2">
                <button type="button" onclick="this.closest('dialog').close()" class="px-4 py-2 rounded-xl text-sm font-bold text-slate-500 hover:bg-slate-50">Batal</button>
                <button type="submit" class="px-6 py-2 rounded-xl bg-blue-600 text-white text-sm font-bold hover:bg-blue-700">Upload</button>
            </div>
        </form>
    </dialog>

    
    <dialog id="modalSettings" class="modal rounded-3xl p-0 backdrop:bg-slate-900/50">
        <form action="<?php echo e(route('admin.graduation.save_settings')); ?>" method="POST" class="bg-white p-8 w-full max-w-md rounded-3xl shadow-2xl">
            <?php echo csrf_field(); ?>
            <h3 class="text-lg font-bold mb-6">Pengaturan Dokumen SKL</h3>
            
            <div class="space-y-4 mb-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Format Default Nomor Surat</label>
                    <input type="text" name="letter_number" value="<?php echo e($settings['letter_number'] ?? ''); ?>" required class="w-full rounded-xl border-slate-200 text-sm font-medium focus:border-blue-500 focus:ring-blue-500" placeholder="Contoh: 421.3/ ... /SMP.03/2026">
                    <p class="text-[10px] text-rose-500 font-medium mt-1">
                        *Hanya digunakan sebagai teks statis/fallback jika tabel "No. SKL" siswa kosong. Untuk generate otomatis yang berurutan (001, 002, dst), gunakan tombol <b>"Set No. SKL (Auto)"</b> di menu atas.
                    </p>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nama Kepala Sekolah</label>
                    <input type="text" name="principal_name" value="<?php echo e($settings['principal_name'] ?? ''); ?>" required class="w-full rounded-xl border-slate-200 text-sm font-medium focus:border-blue-500 focus:ring-blue-500" placeholder="Nama beserta gelar">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">NIP Kepala Sekolah</label>
                    <input type="text" name="principal_nip" value="<?php echo e($settings['principal_nip'] ?? ''); ?>" required class="w-full rounded-xl border-slate-200 text-sm font-medium focus:border-blue-500 focus:ring-blue-500" placeholder="NIP Kepala Sekolah">
                </div>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="this.closest('dialog').close()" class="px-4 py-2 rounded-xl text-sm font-bold text-slate-500 hover:bg-slate-50">Batal</button>
                <button type="submit" class="px-6 py-2 rounded-xl bg-blue-600 text-white text-sm font-bold hover:bg-blue-700 shadow-lg shadow-blue-500/30">Simpan Pengaturan</button>
            </div>
        </form>
    </dialog>

    
    <script>
        // Alert Global (Flash Message) dari Session Laravel
        document.addEventListener('DOMContentLoaded', function() {
            <?php if(session('success')): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "<?php echo e(session('success')); ?>",
                    confirmButtonColor: '#10b981',
                    customClass: { popup: 'rounded-3xl border border-emerald-100' }
                });
            <?php endif; ?>

            <?php if(session('error')): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "<?php echo e(session('error')); ?>",
                    confirmButtonColor: '#f43f5e',
                    customClass: { popup: 'rounded-3xl border border-rose-100' }
                });
            <?php endif; ?>
        });

        // SweetAlert untuk konfirmasi pindah Alumni
        function confirmAlumniProcess() {
            Swal.fire({
                title: 'Pindahkan ke Alumni?',
                html: 'Siswa dengan status <b>LULUS</b> akan dipindahkan menjadi <b>ALUMNI</b>.<br><br><ul class="text-left text-sm text-slate-500 list-disc pl-5"><li>Akun dikeluarkan dari kelas aktif.</li><li>Login diarahkan ke Dashboard Alumni.</li></ul>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Pindahkan!',
                cancelButtonText: 'Batal',
                customClass: { popup: 'rounded-3xl' }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memproses Data...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading(); },
                        customClass: { popup: 'rounded-3xl' }
                    });
                    document.getElementById('formProcessAlumni').submit();
                }
            });
        }

        // Fungsi Simpan Satuan (AJAX Fetch)
        function saveSingle(studentId) {
            const row = document.querySelector(`input[name="students[${studentId}][average_score]"]`).closest('tr');
            const status = row.querySelector(`select[name="students[${studentId}][status]"]`).value;
            const score = row.querySelector(`input[name="students[${studentId}][average_score]"]`).value;
            const skl = row.querySelector(`input[name="students[${studentId}][skl_number]"]`).value;
            const date = row.querySelector(`input[name="students[${studentId}][announcement_date]"]`).value;

            const btn = event.currentTarget;
            const originalIcon = btn.innerHTML;
            btn.innerHTML = '<i class="ph-bold ph-spinner animate-spin"></i>'; 
            btn.disabled = true;

            fetch("<?php echo e(route('admin.graduation.store')); ?>", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    student_id: studentId,
                    status: status,
                    average_score: score,
                    skl_number: skl,
                    announcement_date: date
                })
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                btn.innerHTML = originalIcon;
                btn.disabled = false;
                const msg = document.getElementById('msg_' + studentId);
                msg.classList.remove('hidden');
                setTimeout(() => msg.classList.add('hidden'), 2000);
            })
            .catch(error => {
                console.error('Error:', error);
                btn.innerHTML = '<i class="ph-bold ph-warning text-rose-500"></i>';
                btn.disabled = false;
                
                // Ganti alert vanilla dengan SweetAlert
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Menyimpan',
                    text: 'Terjadi kesalahan saat menghubungi server.',
                    confirmButtonColor: '#f43f5e',
                    customClass: { popup: 'rounded-3xl' }
                });
            });
        }
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/admin/graduation/index.blade.php ENDPATH**/ ?>