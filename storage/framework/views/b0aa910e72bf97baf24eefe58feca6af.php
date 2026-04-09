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
    
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        /* Style untuk area kamera agar responsif */
        #reader video {
            object-fit: cover;
            width: 100% !important;
            height: 100% !important;
            border-radius: 1rem;
        }
        #reader { width: 100%; }
        
        /* Hilangkan scrollbar default pada tabel agar bersih */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    <div class="py-8 sm:py-10 font-sans text-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <div class="relative rounded-[2rem] bg-gray-900 bg-gradient-to-br from-slate-900 via-blue-900 to-blue-800 p-8 mb-8 text-white shadow-xl shadow-blue-900/30 overflow-hidden border border-white/10">
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="text-center md:text-left">
                        <h1 class="text-3xl font-extrabold tracking-tight mb-2 flex items-center justify-center md:justify-start gap-3">
                            <i class="ph-duotone ph-clipboard-text text-blue-300"></i> Catatan Kedisiplinan
                        </h1>
                        <p class="text-blue-300 text-sm font-medium leading-relaxed max-w-lg">
                            Kelola poin siswa, pantau klasemen pelanggaran, dan lihat rekapitulasi per kelas dalam satu dashboard yang terintegrasi.
                        </p>
                    </div>
                    
                    <a href="<?php echo e(route('discipline-types.index')); ?>" class="group bg-white/10 backdrop-blur-md hover:bg-white/20 text-white px-5 py-3 rounded-2xl font-bold text-sm border border-white/10 transition-all flex items-center gap-2 shadow-lg">
                        <i class="ph-bold ph-gear text-xl group-hover:rotate-90 transition-transform duration-500"></i>
                        <span>Atur Jenis Poin</span>
                    </a>
                </div>
            </div>

            
            <?php if(session('success')): ?>
                <div x-data="{ show: true }" x-show="show" class="mb-8 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-[1.5rem] flex items-center justify-between shadow-sm animate-in slide-in-from-top-2">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600"><i class="ph-bold ph-check"></i></div>
                        <span class="font-bold text-sm"><?php echo e(session('success')); ?></span>
                    </div>
                    <button @click="show = false" class="p-2 hover:bg-emerald-100 rounded-lg transition"><i class="ph-bold ph-x"></i></button>
                </div>
            <?php endif; ?>

            <!-- BAGIAN 1: FORM INPUT (GRID 2 KOLOM) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
                
                <!-- KIRI: Form Pelanggaran (Tema Merah) -->
                <div class="bg-white rounded-[2.5rem] shadow-xl shadow-rose-900/5 border border-slate-100 overflow-hidden relative group hover:border-rose-100 transition-all duration-300">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-rose-500"></div>
                    <div class="p-8 relative z-10">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center text-2xl shadow-sm border border-rose-100 group-hover:scale-110 transition-transform duration-300">
                                <i class="ph-duotone ph-warning-octagon"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-800">Input Pelanggaran</h3>
                                <p class="text-xs font-bold text-rose-400 uppercase tracking-wider">Kurangi Poin (-)</p>
                            </div>
                        </div>

                        <form action="<?php echo e(route('discipline.store')); ?>" method="POST" class="space-y-5">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="date" value="<?php echo e(\Carbon\Carbon::today()->toDateString()); ?>">
                            
                            
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Pilih Siswa</label>
                                <div class="flex gap-2">
                                    <div class="relative flex-1">
                                        <select name="student_id" id="student_select_violation" required class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-rose-500 focus:ring-rose-500 text-sm font-bold text-slate-700 py-3.5 pl-4 pr-10 appearance-none cursor-pointer">
                                            <option value="">-- Cari / Pilih Nama Siswa --</option>
                                            
                                            <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($student->id); ?>" 
                                                        data-nis="<?php echo e($student->nis ?? ''); ?>" 
                                                        data-nisn="<?php echo e($student->nisn ?? ''); ?>"
                                                        data-student-id="<?php echo e($student->student_id ?? ''); ?>"
                                                        data-rfid="<?php echo e($student->rfid_id ?? ''); ?>"
                                                        data-class="<?php echo e($student->schoolClass->name ?? ''); ?>">
                                                    <?php echo e($student->name); ?> (<?php echo e($student->schoolClass->name ?? 'N/A'); ?>)
                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                                    </div>
                                    <button type="button" onclick="startScanner('student_select_violation')" class="shrink-0 bg-slate-800 text-white w-12 rounded-2xl hover:bg-slate-700 transition-colors shadow-lg flex items-center justify-center" title="Scan QR Code">
                                        <i class="ph-bold ph-qr-code text-xl"></i>
                                    </button>
                                </div>
                            </div>
                            
                            
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Jenis Pelanggaran</label>
                                <div class="relative">
                                    <select name="discipline_type_id" required class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-rose-500 focus:ring-rose-500 text-sm font-bold text-slate-700 py-3.5 pl-4 pr-10 appearance-none cursor-pointer">
                                        <option value="">-- Pilih Kategori --</option>
                                        <?php $__currentLoopData = $violationTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($type->id); ?>"><?php echo e($type->name); ?> (-<?php echo e($type->point_value); ?> Poin)</option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                                </div>
                            </div>
                            
                            
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Kronologi / Catatan</label>
                                <textarea name="notes" rows="2" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-rose-500 focus:ring-rose-500 text-sm font-medium p-4" placeholder="Jelaskan singkat kejadiannya..."></textarea>
                            </div>

                            <button type="submit" class="w-full py-3.5 bg-rose-600 text-white font-bold rounded-2xl hover:bg-rose-700 transition-all shadow-lg shadow-rose-200 flex items-center justify-center gap-2 mt-2 transform active:scale-95">
                                <i class="ph-bold ph-warning-circle text-lg"></i>
                                Simpan Pelanggaran
                            </button>
                        </form>
                    </div>
                </div>

                <!-- KANAN: Form Kebaikan (Tema Hijau) -->
                <div class="bg-white rounded-[2.5rem] shadow-xl shadow-emerald-900/5 border border-slate-100 overflow-hidden relative group hover:border-emerald-100 transition-all duration-300">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-emerald-500"></div>
                    <div class="p-8 relative z-10">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-2xl shadow-sm border border-emerald-100 group-hover:scale-110 transition-transform duration-300">
                                <i class="ph-duotone ph-medal"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-800">Input Prestasi</h3>
                                <p class="text-xs font-bold text-emerald-500 uppercase tracking-wider">Tambah Poin (+)</p>
                            </div>
                        </div>

                        <form action="<?php echo e(route('discipline.store')); ?>" method="POST" class="space-y-5">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="date" value="<?php echo e(\Carbon\Carbon::today()->toDateString()); ?>">
                            
                            
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Pilih Siswa</label>
                                <div class="flex gap-2">
                                    <div class="relative flex-1">
                                        <select name="student_id" id="student_select_merit" required class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 text-sm font-bold text-slate-700 py-3.5 pl-4 pr-10 appearance-none cursor-pointer">
                                            <option value="">-- Cari / Pilih Nama Siswa --</option>
                                            
                                            <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($student->id); ?>" 
                                                        data-nis="<?php echo e($student->nis ?? ''); ?>" 
                                                        data-nisn="<?php echo e($student->nisn ?? ''); ?>"
                                                        data-student-id="<?php echo e($student->student_id ?? ''); ?>"
                                                        data-rfid="<?php echo e($student->rfid_id ?? ''); ?>"
                                                        data-class="<?php echo e($student->schoolClass->name ?? ''); ?>">
                                                    <?php echo e($student->name); ?> (<?php echo e($student->schoolClass->name ?? 'N/A'); ?>)
                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                                    </div>
                                    <button type="button" onclick="startScanner('student_select_merit')" class="shrink-0 bg-slate-800 text-white w-12 rounded-2xl hover:bg-slate-700 transition-colors shadow-lg flex items-center justify-center" title="Scan QR Code">
                                        <i class="ph-bold ph-qr-code text-xl"></i>
                                    </button>
                                </div>
                            </div>
                            
                            
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Jenis Kebaikan</label>
                                <div class="relative">
                                    <select name="discipline_type_id" required class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 text-sm font-bold text-slate-700 py-3.5 pl-4 pr-10 appearance-none cursor-pointer">
                                        <option value="">-- Pilih Kategori --</option>
                                        <?php $__currentLoopData = $meritTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($type->id); ?>"><?php echo e($type->name); ?> (+<?php echo e($type->point_value); ?> Poin)</option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                                </div>
                            </div>
                            
                            
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Detail Tambahan</label>
                                <textarea name="notes" rows="2" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 text-sm font-medium p-4" placeholder="Keterangan prestasi..."></textarea>
                            </div>

                            <button type="submit" class="w-full py-3.5 bg-emerald-600 text-white font-bold rounded-2xl hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-200 flex items-center justify-center gap-2 mt-2 transform active:scale-95">
                                <i class="ph-bold ph-star text-lg"></i>
                                Simpan Kebaikan
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- BAGIAN 3: RIWAYAT / LOG -->
            <?php if(isset($historyRecords)): ?>
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden mb-10">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col lg:flex-row justify-between items-center gap-4">
                    <div>
                        <h3 class="text-xl font-black text-slate-800">Log Aktivitas</h3>
                        <p class="text-sm font-medium text-slate-400 mb-2 lg:mb-0">Riwayat input poin terbaru.</p>
                    </div>
                
                    <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
                        <form action="<?php echo e(route('discipline.index')); ?>" method="GET" class="flex flex-col sm:flex-row w-full gap-2">
                            <input type="date" name="filter_date" value="<?php echo e(request('filter_date')); ?>" 
                                class="rounded-xl border-slate-200 text-sm py-2 px-3 text-slate-600 focus:ring-blue-500 focus:border-blue-500 w-full sm:w-auto">
                            
                            <div class="relative w-full sm:w-auto">
                                <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari nama siswa..." 
                                    class="rounded-xl border-slate-200 text-sm py-2 pl-9 pr-3 text-slate-700 focus:ring-blue-500 focus:border-blue-500 w-full">
                                <i class="ph-bold ph-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                            </div>
                            
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-bold shadow-sm transition-colors flex items-center justify-center gap-2">
                                Cari
                            </button>
                            
                            <?php if(request('search') || request('filter_date')): ?>
                                <a href="<?php echo e(route('discipline.index')); ?>" class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-4 py-2 rounded-xl text-sm font-bold transition-colors flex items-center justify-center">
                                    Reset
                                </a>
                            <?php endif; ?>
                        </form>

                        <div class="text-xs font-bold text-slate-500 bg-white px-3 py-2 rounded-xl border border-slate-200 shadow-sm whitespace-nowrap">
                            <?php echo e($historyRecords->total()); ?> Data
                        </div>
                    </div>
                </div>             
                <div class="overflow-x-auto w-full custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Waktu</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Siswa</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Keterangan</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Poin</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-right">Petugas</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php $__empty_1 = true; $__currentLoopData = $historyRecords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php
                                    $isViolation = optional($record->disciplineType)->type == 'Pelanggaran';
                                    $color = $isViolation ? 'rose' : 'emerald';
                                    $sign = $isViolation ? '-' : '+';
                                ?>
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-slate-700"><?php echo e($record->created_at->format('d/m H:i')); ?></div>
                                        <div class="text-[10px] font-bold text-slate-400"><?php echo e($record->created_at->diffForHumans()); ?></div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-slate-800"><?php echo e($record->student->name); ?></div>
                                        <div class="text-xs text-slate-500 font-medium"><?php echo e($record->student->schoolClass->name ?? '-'); ?></div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-slate-700"><?php echo e($record->disciplineType->name ?? '-'); ?></div>
                                        <?php if($record->notes): ?> 
                                            <div class="text-xs text-slate-500 italic mt-0.5 truncate max-w-xs">"<?php echo e($record->notes); ?>"</div> 
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-black bg-<?php echo e($color); ?>-100 text-<?php echo e($color); ?>-700 border border-<?php echo e($color); ?>-200 shadow-sm">
                                            <?php echo e($sign); ?><?php echo e($record->disciplineType->point_value ?? 0); ?>

                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="text-xs font-bold text-slate-500"><?php echo e($record->recorder->name ?? 'Sistem'); ?></span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <form action="<?php echo e(route('discipline.destroy', $record->id)); ?>" method="POST" 
                                            onsubmit="event.preventDefault(); 
                                                        const form = this;
                                                        Swal.fire({
                                                            title: 'Hapus Riwayat?',
                                                            text: 'Data poin siswa akan kembali disesuaikan. Yakin ingin menghapus?',
                                                            icon: 'warning',
                                                            showCancelButton: true,
                                                            confirmButtonColor: '#e11d48',
                                                            cancelButtonColor: '#94a3b8',
                                                            confirmButtonText: 'Ya, Hapus!',
                                                            cancelButtonText: 'Batal',
                                                            reverseButtons: true,
                                                            customClass: {
                                                                popup: 'rounded-[2rem] font-sans border-0 shadow-2xl',
                                                                confirmButton: 'bg-rose-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-rose-700 transition-colors mx-2 shadow-lg shadow-rose-900/20',
                                                                cancelButton: 'bg-slate-100 text-slate-600 px-6 py-3 rounded-xl font-bold hover:bg-slate-200 transition-colors mx-2'
                                                            },
                                                            buttonsStyling: false
                                                        }).then((result) => {
                                                            if (result.isConfirmed) form.submit();
                                                        });">
                                            <?php echo csrf_field(); ?> 
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="text-slate-300 hover:text-rose-500 transition-colors p-2 rounded-lg hover:bg-rose-50" title="Hapus Riwayat">
                                                <i class="ph-bold ph-trash text-lg"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                        <div class="flex flex-col items-center">
                                            <i class="ph-duotone ph-clipboard-text text-3xl mb-2 text-slate-300"></i>
                                            <span>Belum ada data aktivitas hari ini.</span>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="p-6 border-t border-slate-50 bg-slate-50/30">
                    <?php echo e($historyRecords->links()); ?>

                </div>
            </div>
            <?php endif; ?>

            <!-- BAGIAN 4: STATISTIK & KLASEMEN -->
            <?php if(isset($classSummaries) && isset($topViolators) && isset($topMerits)): ?>
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                
                <div class="bg-white rounded-[2.5rem] border border-slate-200 overflow-hidden shadow-sm xl:col-span-1">
                    <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                        <h3 class="text-sm font-bold text-slate-600 uppercase tracking-wider flex items-center gap-2">
                            <i class="ph-bold ph-chalkboard-teacher"></i> Rekap Per Kelas
                        </h3>
                    </div>
                    <div class="overflow-x-auto max-h-[500px] custom-scrollbar">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 sticky top-0 z-10 shadow-sm">
                                <tr>
                                    <th class="px-4 py-3 text-xs font-bold text-slate-400 uppercase">Kelas</th>
                                    <th class="px-4 py-3 text-xs font-bold text-slate-400 uppercase text-center">Minus</th>
                                    <th class="px-4 py-3 text-xs font-bold text-slate-400 uppercase text-center">Plus</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php $__currentLoopData = $classSummaries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $summary): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-4 py-3 font-bold text-slate-700 text-sm"><?php echo e($summary->class_name); ?></td>
                                        <td class="px-4 py-3 text-center">
                                            <?php if($summary->total_violation > 0): ?>
                                                <span class="text-rose-600 text-xs font-bold bg-rose-50 px-1.5 py-0.5 rounded border border-rose-100">-<?php echo e($summary->total_violation); ?></span>
                                            <?php else: ?> <span class="text-slate-300 text-xs">-</span> <?php endif; ?>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <?php if($summary->total_merit > 0): ?>
                                                <span class="text-emerald-600 text-xs font-bold bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-100">+<?php echo e($summary->total_merit); ?></span>
                                            <?php else: ?> <span class="text-slate-300 text-xs">-</span> <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                
                <div class="bg-white rounded-[2.5rem] border border-slate-200 overflow-hidden shadow-sm xl:col-span-1">
                    <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                        <h3 class="text-sm font-bold text-rose-600 uppercase tracking-wider flex items-center gap-2">
                            <i class="ph-bold ph-warning-octagon"></i> Top 10 Pelanggaran
                        </h3>
                    </div>
                    <div class="overflow-x-auto max-h-[500px] custom-scrollbar">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 sticky top-0 z-10 shadow-sm">
                                <tr>
                                    <th class="px-4 py-3 text-xs font-bold text-slate-400 uppercase w-8">#</th>
                                    <th class="px-4 py-3 text-xs font-bold text-slate-400 uppercase">Siswa</th>
                                    <th class="px-4 py-3 text-xs font-bold text-slate-400 uppercase text-center">Poin</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php $__currentLoopData = $topViolators; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $summary): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="hover:bg-rose-50/30 transition-colors">
                                        <td class="px-4 py-3 font-bold text-rose-300 text-sm"><?php echo e($loop->iteration); ?></td>
                                        <td class="px-4 py-3">
                                            <span class="font-bold text-slate-700 block text-sm"><?php echo e($summary->name); ?></span>
                                            <span class="text-[10px] text-slate-400 font-bold uppercase"><?php echo e($summary->class); ?></span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="text-rose-600 font-black text-sm">-<?php echo e($summary->total_violation); ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                
                <div class="bg-white rounded-[2.5rem] border border-slate-200 overflow-hidden shadow-sm xl:col-span-1">
                    <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                        <h3 class="text-sm font-bold text-emerald-600 uppercase tracking-wider flex items-center gap-2">
                            <i class="ph-bold ph-medal"></i> Top 10 Prestasi
                        </h3>
                    </div>
                    <div class="overflow-x-auto max-h-[500px] custom-scrollbar">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 sticky top-0 z-10 shadow-sm">
                                <tr>
                                    <th class="px-4 py-3 text-xs font-bold text-slate-400 uppercase w-8">#</th>
                                    <th class="px-4 py-3 text-xs font-bold text-slate-400 uppercase">Siswa</th>
                                    <th class="px-4 py-3 text-xs font-bold text-slate-400 uppercase text-center">Poin</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php $__currentLoopData = $topMerits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $summary): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="hover:bg-emerald-50/30 transition-colors">
                                        <td class="px-4 py-3 font-bold text-emerald-300 text-sm"><?php echo e($loop->iteration); ?></td>
                                        <td class="px-4 py-3">
                                            <span class="font-bold text-slate-700 block text-sm"><?php echo e($summary->name); ?></span>
                                            <span class="text-[10px] text-slate-400 font-bold uppercase"><?php echo e($summary->class); ?></span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="text-emerald-600 font-black text-sm">+<?php echo e($summary->total_merit); ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>

    
    <div id="qrModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4 text-center">
            <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" onclick="closeScanner()"></div>

            <div class="inline-block align-bottom bg-white rounded-[2rem] text-left overflow-hidden shadow-2xl transform transition-all sm:align-middle sm:max-w-md w-full relative">
                <div class="bg-white p-6">
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 mb-4">
                            <i class="ph-duotone ph-qr-code text-3xl text-blue-600"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-800 mb-2">Scan Kartu Siswa</h3>
                        
                        <div class="relative w-full rounded-2xl overflow-hidden aspect-square bg-black border-4 border-slate-900 shadow-inner">
                            <div id="reader" class="w-full h-full object-cover"></div>
                            <div id="scanner-status" class="absolute inset-0 flex items-center justify-center text-white text-xs font-bold z-10 pointer-events-none bg-black/50">
                                Menunggu Kamera...
                            </div>
                        </div>

                        <div id="error-message" class="text-rose-500 text-xs font-bold mt-4 hidden bg-rose-50 p-2 rounded-lg"></div>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 flex flex-row-reverse gap-2">
                    <button type="button" class="w-full inline-flex justify-center rounded-xl border border-slate-200 shadow-sm px-4 py-3 bg-white text-base font-bold text-slate-700 hover:bg-slate-100 focus:outline-none sm:w-auto sm:text-sm" onclick="closeScanner()">
                        Batal / Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    
    <script>
        let html5QrcodeScanner = null;
        let currentTargetInput = null;

        function updateStatus(message) {
            const statusEl = document.getElementById('scanner-status');
            if(statusEl) statusEl.innerText = message;
        }

        function startScanner(targetInputId) {
            if (typeof Html5Qrcode === 'undefined') {
                Swal.fire('Error', 'Library Scanner belum siap.', 'error');
                return;
            }

            currentTargetInput = targetInputId;
            const modal = document.getElementById('qrModal');
            const errorMsg = document.getElementById('error-message');
            const statusEl = document.getElementById('scanner-status');

            errorMsg.classList.add('hidden');
            modal.classList.remove('hidden');
            
            if(statusEl) {
                statusEl.style.display = 'flex';
                statusEl.innerText = "Memulai kamera...";
            }

            setTimeout(() => {
                if (html5QrcodeScanner === null) {
                    html5QrcodeScanner = new Html5Qrcode("reader");
                }

                Html5Qrcode.getCameras().then(devices => {
                    if (devices && devices.length) {
                        let cameraId = devices.length > 1 ? devices[devices.length - 1].id : devices[0].id;
                        updateStatus("Kamera aktif...");

                        html5QrcodeScanner.start(
                            cameraId, 
                            { fps: 10, qrbox: { width: 250, height: 250 } }, 
                            onScanSuccess
                        )
                        .then(() => {
                            if(statusEl) statusEl.style.display = 'none';
                        }).catch(err => {
                            showError("Gagal membuka kamera: " + err);
                        });
                    } else {
                        showError("Tidak ada kamera ditemukan.");
                    }
                }).catch(err => {
                    showError("Izin kamera ditolak atau tidak tersedia.");
                });
            }, 500);
        }

        function showError(msg) {
            const errorMsg = document.getElementById('error-message');
            if(errorMsg) {
                errorMsg.innerText = msg;
                errorMsg.classList.remove('hidden');
            }
        }

        function onScanSuccess(decodedText, decodedResult) {
            // 1. Bersihkan teks hasil scan
            const scannedText = String(decodedText).trim();
            
            console.log("-----------------------------------");
            console.log("QR Terbaca:", scannedText);
            
            let selectElement = document.getElementById(currentTargetInput);
            
            let found = false;
            let foundName = "";

            // 2. Loop mencari data di dropdown
            for (let i = 0; i < selectElement.options.length; i++) {
                const option = selectElement.options[i];
                
                // Ambil data atribut
                const optValue = String(option.value).trim(); // Student ID (PK)
                const optNis = option.getAttribute('data-nis') ? String(option.getAttribute('data-nis')).trim() : '';
                const optNisn = option.getAttribute('data-nisn') ? String(option.getAttribute('data-nisn')).trim() : '';
                // UPDATED: Ambil student_id dan rfid_id dari atribut
                const optStudentId = option.getAttribute('data-student-id') ? String(option.getAttribute('data-student-id')).trim() : '';
                const optRfid = option.getAttribute('data-rfid') ? String(option.getAttribute('data-rfid')).trim() : '';

                // --- LOGIKA 1: EXACT MATCH (String sama persis) ---
                // Cek ke semua kemungkinan field: NIS, NISN, StudentID, RFID
                if (optValue === scannedText || 
                    optNis === scannedText || 
                    optNisn === scannedText || 
                    optStudentId === scannedText || 
                    optRfid === scannedText) {
                    
                    selectElement.selectedIndex = i;
                    found = true;
                    foundName = option.text;
                    break;
                }

                // --- LOGIKA 2: NUMBER MATCH (Smart Match) ---
                // Hanya jalankan jika scannedText berupa angka saja
                if (/^\d+$/.test(scannedText)) {
                    const scanNum = parseInt(scannedText, 10);
                    
                    // Helper untuk cek number match
                    const checkNum = (val) => val && /^\d+$/.test(val) && parseInt(val, 10) === scanNum;

                    if (checkNum(optNis) || checkNum(optNisn) || checkNum(optStudentId)) {
                        selectElement.selectedIndex = i;
                        found = true;
                        foundName = option.text;
                        break;
                    }
                }
            }

            if (found) {
                playBeep();
                closeScanner();
                Swal.fire({
                    icon: 'success', 
                    title: 'Siswa Ditemukan!',
                    text: foundName, 
                    timer: 1500, 
                    showConfirmButton: false,
                    customClass: { popup: 'rounded-[2rem]' }
                });
            } else {
                if (navigator.vibrate) navigator.vibrate(200);
                
                console.warn("TIDAK DITEMUKAN. Pastikan data-nis/student-id di HTML sesuai dengan QR.");
                
                Swal.fire({
                    icon: 'error', 
                    title: 'Tidak Ditemukan',
                    text: `Kode terbaca: [${scannedText}] tidak ada di data siswa.`,
                    customClass: { popup: 'rounded-[2rem]' }
                });
            }
        }

        function closeScanner() {
            const modal = document.getElementById('qrModal');
            if (html5QrcodeScanner) {
                html5QrcodeScanner.stop().then(() => {
                    html5QrcodeScanner.clear();
                    if(modal) modal.classList.add('hidden');
                }).catch(() => {
                    if(modal) modal.classList.add('hidden');
                });
            } else {
                if(modal) modal.classList.add('hidden');
            }
        }

        function playBeep() {
            try {
                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioCtx.createOscillator();
                const gainNode = audioCtx.createGain();
                oscillator.connect(gainNode);
                gainNode.connect(audioCtx.destination);
                oscillator.frequency.value = 880; 
                gainNode.gain.value = 0.1;
                oscillator.start();
                setTimeout(() => oscillator.stop(), 100);
            } catch (e) {
                console.log("Audio play failed");
            }
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
<?php endif; ?><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/discipline/index.blade.php ENDPATH**/ ?>