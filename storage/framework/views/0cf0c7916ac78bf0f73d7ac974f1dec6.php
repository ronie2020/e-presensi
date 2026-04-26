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
    
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        
        /* Microsoft Fluent Elevation Shadows */
        .fluent-card {
            box-shadow: 0 1.6px 3.6px 0 rgba(0, 0, 0, 0.132), 0 0.3px 0.9px 0 rgba(0, 0, 0, 0.108);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        .fluent-card:hover {
            box-shadow: 0 6.4px 14.4px 0 rgba(0, 0, 0, 0.132), 0 1.2px 3.6px 0 rgba(0, 0, 0, 0.108);
            transform: translateY(-2px);
        }
        .fluent-modal {
            box-shadow: 0 25.6px 57.6px 0 rgba(0, 0, 0, 0.22), 0 4.8px 14.4px 0 rgba(0, 0, 0, 0.18);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        dialog::backdrop {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
        }
    </style>

    <?php $__env->startPush('scripts'); ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php $__env->stopPush(); ?>

    <div class="py-8 sm:py-10 font-sans text-[#2A3B52] bg-[#f8fafc] min-h-screen">
        
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
            <div class="animate-enter relative rounded-[2rem] md:rounded-[3rem] bg-gradient-to-br from-[#25D0FF] via-[#5295FF] to-[#FFC9B9] p-8 sm:p-10 text-[#2A3B52] shadow-[0_10px_40px_-10px_rgba(37,208,255,0.4)] overflow-hidden border border-white/40 group">
                
                
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-[0.03] pointer-events-none mix-blend-overlay"></div>
                <div class="absolute -top-24 -right-24 w-96 h-96 bg-white/30 rounded-full blur-[100px] pointer-events-none group-hover:opacity-70 transition-opacity duration-1000"></div>
                <div class="absolute bottom-0 left-20 w-64 h-64 bg-white/20 rounded-full blur-[80px] pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col xl:flex-row justify-between items-start xl:items-center gap-8">
                    <div class="space-y-3">
                        <a href="<?php echo e(route('dashboard')); ?>" class="group bg-white/40 hover:bg-white/60 text-[#2A3B52] px-5 py-2.5 rounded-xl font-bold text-sm backdrop-blur-sm border border-white/50 transition-all flex items-center gap-2 shadow-sm w-fit mb-4 mx-auto xl:mx-0">
                            <i class="ph-bold ph-arrow-left text-m group-hover:-translate-x-1 transition-transform"></i>
                            <span>Kembali ke Dashboard</span>
                        </a>
                        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/40 border border-white/50 text-[#2A3B52] text-xs font-bold uppercase tracking-widest backdrop-blur-sm shadow-sm">
                            <i class="ph-fill ph-graduation-cap text-[#5295FF]"></i> Manajemen Akademik
                        </div>
                        <h1 class="text-3xl sm:text-5xl font-black tracking-tight leading-tight text-[#2A3B52]">
                            Kelulusan & <span class="text-[#2A3B52]">SKL Digital</span>
                        </h1>
                        <p class="text-[#2A3B52]/80 text-sm sm:text-base max-w-xl font-medium leading-relaxed">
                            Kelola status kelulusan siswa tingkat akhir, generate Surat Keterangan Lulus (SKL), dan publikasi pengumuman secara terpusat.
                        </p>
                    </div>

                    
                    <div class="flex flex-wrap gap-3 w-full xl:w-auto">
                        <!-- Tombol Set Tanggal -->
                        <button onclick="document.getElementById('modalGlobalDate').showModal()" class="flex-1 xl:flex-none bg-white/40 hover:bg-white/60 border border-white/50 text-[#2A3B52] backdrop-blur-md px-5 py-3 rounded-xl font-bold text-sm transition-all flex items-center justify-center gap-2 shadow-sm">
                            <i class="ph-bold ph-calendar-check text-lg text-[#107C10]"></i>
                            <span>Set Tanggal</span>
                        </button>
                        
                        <!-- Tombol Import -->
                        <button onclick="document.getElementById('modalImport').showModal()" class="flex-1 xl:flex-none bg-white/40 hover:bg-white/60 border border-white/50 text-[#2A3B52] backdrop-blur-md px-5 py-3 rounded-xl font-bold text-sm transition-all flex items-center justify-center gap-2 shadow-sm">
                            <i class="ph-bold ph-file-csv text-lg text-[#D83B01]"></i>
                            <span>Import CSV</span>
                        </button>

                        <!-- Tombol Generate Nomor SKL Massal -->
                        <button onclick="document.getElementById('modalSkl').showModal()" class="flex-1 xl:flex-none bg-[#5295FF] hover:bg-[#3b7ee6] text-white shadow-md px-5 py-3 rounded-xl font-bold text-sm transition-all flex items-center justify-center gap-2">
                            <i class="ph-bold ph-list-numbers text-lg"></i>
                            <span>Set No. SKL (Auto)</span>
                        </button>

                        <!-- Tombol Pengaturan SKL -->
                        <button onclick="document.getElementById('modalSettings').showModal()" class="flex-1 xl:flex-none bg-white/40 hover:bg-white/60 border border-white/50 text-[#2A3B52] backdrop-blur-md px-5 py-3 rounded-xl font-bold text-sm transition-all flex items-center justify-center gap-2 shadow-sm">
                            <i class="ph-bold ph-gear text-lg text-[#2A3B52]"></i>
                            <span>Pengaturan SKL</span>
                        </button>

                        <!-- Tombol Pindahkan ke Alumni -->
                        <form action="<?php echo e(route('admin.graduation.process_alumni')); ?>" method="POST" id="formProcessAlumni" class="w-full xl:w-auto">
                            <?php echo csrf_field(); ?>
                            <button type="button" onclick="confirmAlumniProcess()" class="w-full bg-[#107C10] hover:bg-[#0c5e0c] text-white shadow-md px-6 py-3 rounded-xl font-bold text-sm transition-all flex items-center justify-center gap-2">
                                <i class="ph-bold ph-users-three text-lg"></i>
                                <span>Pindahkan ke Alumni</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6 animate-enter" style="animation-delay: 100ms;">
            <div class="bg-white p-4 rounded-2xl fluent-card flex flex-col sm:flex-row gap-4 justify-between items-center">
                
                
                <form method="GET" class="w-full sm:w-auto flex items-center gap-2">
                    <div class="relative w-full sm:w-64 group">
                        <i class="ph-bold ph-chalkboard-teacher absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-[#5295FF] transition-colors"></i>
                        <select name="class_id" onchange="this.form.submit()" class="w-full pl-11 pr-10 py-2.5 rounded-xl border-slate-200 bg-slate-50 text-sm font-bold text-[#2A3B52] focus:ring-[#5295FF] focus:border-[#5295FF] appearance-none cursor-pointer transition-all shadow-sm">
                            <option value="">Semua Kelas 9</option>
                            <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($class->id); ?>" <?php echo e(request('class_id') == $class->id ? 'selected' : ''); ?>><?php echo e($class->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                    </div>
                </form>

                
                <form method="GET" class="w-full sm:w-auto relative group">
                    <?php if(request('class_id')): ?> 
                        <input type="hidden" name="class_id" value="<?php echo e(request('class_id')); ?>"> 
                    <?php endif; ?>
                    <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-[#5295FF] transition-colors"></i>
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari Siswa / NISN..." 
                           class="w-full sm:w-72 pl-11 pr-4 py-2.5 rounded-xl border-slate-200 bg-slate-50 text-sm font-bold text-[#2A3B52] focus:ring-[#5295FF] focus:border-[#5295FF] transition-all shadow-sm placeholder:font-medium placeholder:text-slate-400">
                </form>
            </div>
        </div>

        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 animate-enter" style="animation-delay: 200ms;">
            <form action="<?php echo e(route('admin.graduation.bulk_update')); ?>" method="POST" id="bulkForm">
                <?php echo csrf_field(); ?>
                <div class="bg-white rounded-[2rem] fluent-card overflow-hidden">
                    
                    
                    <div class="px-6 py-5 border-b border-slate-100 bg-white flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div class="flex items-center gap-2">
                            <span class="bg-[#F3F9FD] border border-[#D0E7F8] text-[#5295FF] px-4 py-1.5 rounded-lg text-xs font-bold shadow-sm">Total: <?php echo e($students->total()); ?> Siswa</span>
                        </div>
                        <button type="submit" class="bg-[#2A3B52] hover:bg-[#182436] text-white px-5 py-2.5 rounded-lg text-xs font-bold shadow-sm transition-all flex items-center gap-2">
                            <i class="ph-bold ph-floppy-disk"></i> Simpan Perubahan Masal
                        </button>
                    </div>

                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-slate-50 text-xs uppercase font-bold text-slate-500 tracking-wider">
                                <tr>
                                    <th class="px-6 py-4">Identitas Siswa</th>
                                    <th class="px-6 py-4">Status Kelulusan</th>
                                    <th class="px-6 py-4 text-center">Nilai Rata-rata</th>
                                    <th class="px-6 py-4">No. SKL</th>
                                    <th class="px-6 py-4 text-center border-l border-slate-100 bg-white">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm">
                                <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-slate-50/50 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-500 font-bold text-xs shrink-0 overflow-hidden shadow-sm group-hover:border-[#5295FF] transition-colors">
                                                <?php if($student->photo_path): ?>
                                                    <img src="<?php echo e(asset('storage/'.$student->photo_path)); ?>" class="w-full h-full object-cover">
                                                <?php else: ?>
                                                    <?php echo e(substr($student->name, 0, 2)); ?>

                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <div class="font-bold text-[#2A3B52] group-hover:text-[#5295FF] transition-colors"><?php echo e($student->name); ?></div>
                                                <div class="text-[11px] font-mono text-slate-400 font-medium mt-0.5 flex items-center gap-1">
                                                    <i class="ph-bold ph-identification-card"></i> <?php echo e($student->student_id); ?> <span class="mx-1">•</span> <i class="ph-fill ph-chalkboard text-slate-300"></i> <?php echo e($student->schoolClass->name ?? '-'); ?>

                                                </div>
                                            </div>
                                        </div>
                                        <?php if($student->status == 'graduated'): ?>
                                            <span class="mt-2 inline-flex items-center gap-1 px-2.5 py-1 bg-[#DFF6DD] border border-[#B7DFB9] text-[#107C10] text-[10px] font-bold rounded-md">
                                                <i class="ph-fill ph-check-circle"></i> Sudah Alumni
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <select name="students[<?php echo e($student->id); ?>][status]" class="w-36 py-2 px-3 rounded-lg text-xs font-bold border-slate-200 focus:ring-[#5295FF] focus:border-[#5295FF] cursor-pointer shadow-sm
                                            <?php echo e($student->graduation?->status == 'LULUS' ? 'bg-[#DFF6DD] text-[#107C10] border-[#B7DFB9]' : ''); ?>

                                            <?php echo e($student->graduation?->status == 'TIDAK LULUS' ? 'bg-[#FDE7E9] text-[#D13438] border-[#F4C3C9]' : ''); ?>">
                                            <option value="DITUNDA" <?php echo e(($student->graduation?->status ?? 'DITUNDA') == 'DITUNDA' ? 'selected' : ''); ?>>⏳ Ditunda</option>
                                            <option value="LULUS" <?php echo e(($student->graduation?->status ?? '') == 'LULUS' ? 'selected' : ''); ?>>✅ Lulus</option>
                                            <option value="TIDAK LULUS" <?php echo e(($student->graduation?->status ?? '') == 'TIDAK LULUS' ? 'selected' : ''); ?>>❌ Tidak Lulus</option>
                                        </select>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <input type="number" step="0.01" name="students[<?php echo e($student->id); ?>][average_score]" value="<?php echo e($student->graduation?->average_score); ?>" 
                                            class="w-24 text-center py-2 rounded-lg border-slate-200 text-xs font-bold focus:ring-[#5295FF] focus:border-[#5295FF] bg-slate-50 focus:bg-white transition-colors shadow-sm text-[#2A3B52]" placeholder="0.00">
                                    </td>
                                    <td class="px-6 py-4">
                                        <input type="text" name="students[<?php echo e($student->id); ?>][skl_number]" value="<?php echo e($student->graduation?->skl_number); ?>" 
                                            class="w-48 py-2 rounded-lg border-slate-200 text-xs font-medium focus:ring-[#5295FF] focus:border-[#5295FF] bg-slate-50 focus:bg-white transition-colors shadow-sm text-[#2A3B52]" placeholder="Kosong = Format Default">
                                        
                                        
                                        <input type="hidden" name="students[<?php echo e($student->id); ?>][announcement_date]" value="<?php echo e($student->graduation?->announcement_date); ?>">
                                    </td>
                                    <td class="px-6 py-4 text-center bg-white border-l border-slate-100">
                                        <div class="flex items-center justify-center gap-2">
                                            <button type="button" onclick="saveSingle('<?php echo e($student->id); ?>')" class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:bg-[#F3F9FD] hover:text-[#5295FF] border border-transparent hover:border-[#D0E7F8] transition-all" title="Simpan Baris Ini">
                                                <i class="ph-bold ph-check text-lg"></i>
                                            </button>
                                            
                                            <?php if($student->graduation?->status == 'LULUS'): ?>
                                                <a href="<?php echo e(route('graduation.print', $student->id)); ?>" target="_blank" class="w-8 h-8 flex items-center justify-center rounded-lg text-[#107C10] hover:bg-[#DFF6DD] border border-transparent hover:border-[#B7DFB9] transition-all" title="Cetak SKL">
                                                    <i class="ph-bold ph-printer text-lg"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                        <span id="msg_<?php echo e($student->id); ?>" class="text-[10px] text-[#107C10] font-bold hidden animate-pulse mt-1">Tersimpan!</span>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center text-slate-400">
                                        <div class="flex flex-col items-center justify-center gap-3">
                                            <div class="w-16 h-16 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-center shadow-sm">
                                                <i class="ph-duotone ph-student text-3xl"></i>
                                            </div>
                                            <span class="font-bold text-sm text-[#2A3B52]">Tidak ada data siswa kelas 9 ditemukan.</span>
                                            <span class="text-xs">Silakan sesuaikan filter pencarian.</span>
                                        </div>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    
                    <div class="px-6 py-4 border-t border-slate-100 bg-white">
                        <?php echo e($students->links()); ?>

                    </div>
                </div>
            </form>
        </div>
    </div>

    
    
    

    
    <dialog id="modalSkl" class="rounded-[2rem] p-0 fluent-modal w-full max-w-md bg-transparent">
        <form action="<?php echo e(route('admin.graduation.bulk_skl')); ?>" method="POST" class="bg-white p-8 rounded-[2rem]">
            <?php echo csrf_field(); ?>
            <div class="flex items-center gap-3 mb-6 border-b border-slate-100 pb-4">
                <div class="w-10 h-10 rounded-lg bg-[#F3F9FD] text-[#5295FF] border border-[#D0E7F8] flex items-center justify-center text-lg shadow-sm">
                    <i class="ph-bold ph-list-numbers"></i>
                </div>
                <h3 class="text-lg font-black text-[#2A3B52]">Generate SKL Massal</h3>
            </div>
            
            <div class="p-4 mb-6 rounded-xl bg-[#F3F9FD] border border-[#D0E7F8] flex gap-3 items-start shadow-sm">
                <i class="ph-fill ph-info text-xl text-[#5295FF] mt-0.5"></i>
                <div class="text-xs text-[#2A3B52] leading-relaxed font-medium">
                    Gunakan tag <b class="text-[#5295FF] font-mono px-1.5 py-0.5 bg-white border border-[#D0E7F8] rounded shadow-sm">{urut}</b> agar sistem membuat urutan otomatis (001, 002, dst) ke database.<br><br>
                    Contoh: <br><b class="font-mono text-[#2A3B52]">421.3/{urut}/SMP.03/2026</b>
                </div>
            </div>

            <?php if(request('class_id')): ?> <input type="hidden" name="class_filter" value="<?php echo e(request('class_id')); ?>"> <?php endif; ?>
            
            <div class="mb-5">
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 ml-1">Format Nomor Surat</label>
                <input type="text" name="skl_format" required class="w-full py-3 px-4 rounded-xl border-slate-200 font-bold text-[#2A3B52] focus:border-[#5295FF] focus:ring-[#5295FF] bg-slate-50 focus:bg-white transition-colors shadow-sm" placeholder="Cth: 421.3/{urut}/SMP.03/2026">
            </div>

            <div class="mb-8">
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 ml-1">Mulai Dari Urutan Ke</label>
                <input type="number" name="start_number" value="1" min="1" class="w-full py-3 px-4 rounded-xl border-slate-200 font-bold text-[#2A3B52] focus:border-[#5295FF] focus:ring-[#5295FF] bg-slate-50 focus:bg-white transition-colors shadow-sm">
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="this.closest('dialog').close()" class="px-5 py-2.5 rounded-lg text-xs font-bold text-slate-500 bg-slate-100 hover:bg-slate-200 transition-colors">Batal</button>
                <button type="submit" class="px-6 py-2.5 rounded-lg bg-[#5295FF] text-white text-xs font-bold hover:bg-[#3b7ee6] shadow-sm transition-all flex items-center gap-2">
                    <i class="ph-bold ph-database"></i> Generate ke Database
                </button>
            </div>
        </form>
    </dialog>

    
    <dialog id="modalGlobalDate" class="rounded-[2rem] p-0 fluent-modal w-full max-w-md bg-transparent">
        <form action="<?php echo e(route('admin.graduation.set_date')); ?>" method="POST" class="bg-white p-8 rounded-[2rem]">
            <?php echo csrf_field(); ?>
            <div class="flex items-center gap-3 mb-6 border-b border-slate-100 pb-4">
                <div class="w-10 h-10 rounded-lg bg-[#DFF6DD] text-[#107C10] border border-[#B7DFB9] flex items-center justify-center text-lg shadow-sm">
                    <i class="ph-bold ph-calendar-check"></i>
                </div>
                <h3 class="text-lg font-black text-[#2A3B52]">Set Tanggal Pengumuman</h3>
            </div>
            
            <div class="p-4 mb-6 rounded-xl bg-[#DFF6DD] border border-[#B7DFB9] flex gap-3 items-start shadow-sm">
                <i class="ph-fill ph-info text-xl text-[#107C10] mt-0.5"></i>
                <div class="text-xs text-[#2A3B52] leading-relaxed font-medium">
                    Mengatur jadwal di sini akan otomatis membuat seluruh siswa di tabel menjadi berstatus <span class="bg-white text-[#107C10] px-1.5 py-0.5 rounded border border-[#B7DFB9] font-bold text-[10px]">LULUS</span>. Jika ada yang tidak lulus, Anda dapat mengubahnya secara manual setelah ini.
                </div>
            </div>

            <?php if(request('class_id')): ?> <input type="hidden" name="class_filter" value="<?php echo e(request('class_id')); ?>"> <?php endif; ?>
            <div class="mb-8">
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 ml-1">Tanggal & Jam Publikasi</label>
                <input type="datetime-local" name="global_date" required class="w-full py-3 px-4 rounded-xl border-slate-200 font-bold text-[#2A3B52] focus:border-[#5295FF] focus:ring-[#5295FF] bg-slate-50 focus:bg-white transition-colors shadow-sm cursor-pointer">
            </div>
            
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="this.closest('dialog').close()" class="px-5 py-2.5 rounded-lg text-xs font-bold text-slate-500 bg-slate-100 hover:bg-slate-200 transition-colors">Batal</button>
                <button type="submit" class="px-6 py-2.5 rounded-lg bg-[#107C10] text-white text-xs font-bold hover:bg-[#0c5e0c] shadow-sm transition-all flex items-center gap-2">
                    <i class="ph-bold ph-floppy-disk"></i> Simpan Jadwal
                </button>
            </div>
        </form>
    </dialog>

    
    <dialog id="modalImport" class="rounded-[2rem] p-0 fluent-modal w-full max-w-md bg-transparent">
        <form action="<?php echo e(route('admin.graduation.import')); ?>" method="POST" enctype="multipart/form-data" class="bg-white p-8 rounded-[2rem]">
            <?php echo csrf_field(); ?>
            <div class="flex items-center gap-3 mb-4 border-b border-slate-100 pb-4">
                <div class="w-10 h-10 rounded-lg bg-[#FFEFD6] text-[#D83B01] border border-[#FFD8A8] flex items-center justify-center text-lg shadow-sm">
                    <i class="ph-bold ph-file-csv"></i>
                </div>
                <h3 class="text-lg font-black text-[#2A3B52]">Import Data CSV</h3>
            </div>
            
            <p class="text-xs text-slate-500 mb-6 font-medium bg-slate-50 p-3 rounded-lg border border-slate-100">Format Kolom: <b class="text-[#2A3B52]">NISN</b>, <b class="text-[#2A3B52]">STATUS</b> (LULUS/TIDAK LULUS), <b class="text-[#2A3B52]">NILAI</b></p>
            
            <div class="mb-8">
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 ml-1">Pilih File CSV</label>
                <input type="file" name="file" accept=".csv" required class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#F3F9FD] file:text-[#5295FF] hover:file:bg-[#D0E7F8] file:transition-colors file:cursor-pointer border border-slate-200 rounded-xl bg-slate-50 p-1 shadow-sm">
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="this.closest('dialog').close()" class="px-5 py-2.5 rounded-lg text-xs font-bold text-slate-500 bg-slate-100 hover:bg-slate-200 transition-colors">Batal</button>
                <button type="submit" class="px-6 py-2.5 rounded-lg bg-[#D83B01] text-white text-xs font-bold hover:bg-[#b53201] shadow-sm transition-all flex items-center gap-2">
                    <i class="ph-bold ph-upload-simple"></i> Upload Data
                </button>
            </div>
        </form>
    </dialog>

    
    <dialog id="modalSettings" class="rounded-[2rem] p-0 fluent-modal w-full max-w-md bg-transparent">
        <form action="<?php echo e(route('admin.graduation.save_settings')); ?>" method="POST" class="bg-white p-8 rounded-[2rem]">
            <?php echo csrf_field(); ?>
            <div class="flex items-center gap-3 mb-6 border-b border-slate-100 pb-4">
                <div class="w-10 h-10 rounded-lg bg-slate-100 text-[#2A3B52] border border-slate-200 flex items-center justify-center text-lg shadow-sm">
                    <i class="ph-bold ph-gear"></i>
                </div>
                <h3 class="text-lg font-black text-[#2A3B52]">Pengaturan Cetak SKL</h3>
            </div>
            
            <div class="space-y-5 mb-8">
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 ml-1">Format Default Nomor Surat</label>
                    <input type="text" name="letter_number" value="<?php echo e($settings['letter_number'] ?? ''); ?>" required class="w-full py-3 px-4 rounded-xl border-slate-200 text-sm font-bold text-[#2A3B52] focus:border-[#5295FF] focus:ring-[#5295FF] shadow-sm" placeholder="Contoh: 421.3/ ... /SMP.03/2026">
                    <p class="text-[10px] text-[#D83B01] font-medium mt-2 bg-[#FFEFD6] p-2 rounded-lg border border-[#FFD8A8]">
                        *Hanya dipakai sebagai fallback statis jika No. SKL siswa kosong. Untuk auto-increment, gunakan fitur <b>Set No. SKL (Auto)</b>.
                    </p>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 ml-1">Nama Kepala Sekolah</label>
                    <input type="text" name="principal_name" value="<?php echo e($settings['principal_name'] ?? ''); ?>" required class="w-full py-3 px-4 rounded-xl border-slate-200 text-sm font-bold text-[#2A3B52] focus:border-[#5295FF] focus:ring-[#5295FF] shadow-sm" placeholder="Nama beserta gelar">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 ml-1">NIP Kepala Sekolah</label>
                    <input type="text" name="principal_nip" value="<?php echo e($settings['principal_nip'] ?? ''); ?>" required class="w-full py-3 px-4 rounded-xl border-slate-200 text-sm font-bold text-[#2A3B52] focus:border-[#5295FF] focus:ring-[#5295FF] shadow-sm" placeholder="NIP Kepala Sekolah">
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="this.closest('dialog').close()" class="px-5 py-2.5 rounded-lg text-xs font-bold text-slate-500 bg-slate-100 hover:bg-slate-200 transition-colors">Batal</button>
                <button type="submit" class="px-6 py-2.5 rounded-lg bg-[#2A3B52] text-white text-xs font-bold hover:bg-[#182436] shadow-sm transition-all flex items-center gap-2">
                    <i class="ph-bold ph-floppy-disk"></i> Simpan Pengaturan
                </button>
            </div>
        </form>
    </dialog>

    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if(session('success')): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "<?php echo e(session('success')); ?>",
                    confirmButtonColor: '#107C10',
                    customClass: { popup: 'fluent-modal rounded-xl border border-slate-100', confirmButton: 'rounded-lg font-bold px-6 py-2.5' }
                });
            <?php endif; ?>

            <?php if(session('error')): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "<?php echo e(session('error')); ?>",
                    confirmButtonColor: '#D13438',
                    customClass: { popup: 'fluent-modal rounded-xl border border-slate-100', confirmButton: 'rounded-lg font-bold px-6 py-2.5' }
                });
            <?php endif; ?>
        });

        // SweetAlert untuk konfirmasi pindah Alumni
        function confirmAlumniProcess() {
            Swal.fire({
                title: 'Pindahkan ke Alumni?',
                html: 'Siswa dengan status <b style="color:#107C10">LULUS</b> akan dipindahkan menjadi <b>ALUMNI</b>.<br><br><ul class="text-left text-xs text-slate-500 list-disc pl-5 mt-2 font-medium"><li>Akun dikeluarkan dari kelas aktif.</li><li>Login diarahkan ke Dashboard Alumni.</li></ul>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#107C10',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Pindahkan!',
                cancelButtonText: 'Batal',
                customClass: { 
                    popup: 'fluent-modal rounded-[2rem]',
                    confirmButton: 'rounded-lg font-bold px-5 py-2.5',
                    cancelButton: 'rounded-lg font-bold px-5 py-2.5'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memproses Data...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading(); },
                        customClass: { popup: 'fluent-modal rounded-[2rem]' }
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
            btn.innerHTML = '<i class="ph-bold ph-spinner animate-spin text-lg"></i>'; 
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
                btn.innerHTML = '<i class="ph-bold ph-warning text-[#D13438] text-lg"></i>';
                btn.disabled = false;
                
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Menyimpan',
                    text: 'Terjadi kesalahan saat menghubungi server.',
                    confirmButtonColor: '#D13438',
                    customClass: { popup: 'fluent-modal rounded-[2rem]', confirmButton: 'rounded-lg font-bold px-6 py-2.5' }
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