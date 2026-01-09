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
    
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-6 sm:py-8" x-data="{ addModalOpen: false, editModalOpen: false, editData: {} }">
        
        
        <div class="mb-10 px-4 sm:px-0">
            <div class="relative rounded-[2.5rem] bg-gray-900 bg-gradient-to-br from-blue-900 via-blue-800 to-slate-900 p-8 sm:p-10 text-white shadow-2xl shadow-blue-900/40 overflow-hidden border border-white/10 group">
                
                
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-80 h-80 bg-blue-500/20 rounded-full blur-3xl pointer-events-none group-hover:bg-blue-500/30 transition-all duration-700"></div>
                <div class="absolute bottom-0 right-20 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
                
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                    
                    
                    <div class="max-w-2xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-200 text-[10px] font-bold uppercase tracking-widest mb-4 backdrop-blur-sm">
                            <i class="ph-fill ph-star"></i> Modul Kesiswaan
                        </div>
                        <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-3 flex items-center gap-3 text-white leading-tight">
                            Manajemen Ekstrakurikuler
                        </h1>
                        <p class="text-blue-100/80 text-sm md:text-base font-medium leading-relaxed max-w-lg">
                            Wadahi bakat dan minat siswa. Kelola jadwal latihan, pantau keanggotaan, dan rekap kehadiran kegiatan dalam satu panel.
                        </p>

                        
                        <div class="mt-8 flex flex-wrap gap-3">
                            <button @click="addModalOpen = true" class="px-6 py-3 bg-white text-blue-900 font-bold rounded-xl shadow-lg hover:bg-blue-50 hover:scale-105 transition-all flex items-center gap-2 transform active:scale-95">
                                <div class="bg-blue-100 p-1 rounded-md">
                                    <i class="ph-bold ph-plus"></i>
                                </div>
                                <span>Tambah Ekskul</span>
                            </button>
                            <a href="<?php echo e(route('extracurriculars.reports')); ?>" class="px-6 py-3 bg-blue-800/50 border border-blue-400/30 text-white font-bold rounded-xl hover:bg-blue-800 hover:border-blue-400 transition-all flex items-center gap-2 backdrop-blur-sm">
                                <i class="ph-bold ph-files"></i> Laporan Absensi
                            </a>
                        </div>
                    </div>
                    
                    
                    <div class="w-full md:w-auto mt-2 md:mt-0">
                        <div class="grid grid-cols-2 md:grid-cols-1 lg:grid-cols-2 gap-4">
                            
                            <div class="bg-white/10 backdrop-blur-md px-5 py-5 rounded-2xl border border-white/10 text-center md:text-left hover:bg-white/15 transition-colors">
                                <div class="flex flex-col md:flex-row lg:flex-col items-center justify-center md:justify-start gap-2 mb-1 text-blue-300">
                                    <i class="ph-duotone ph-trophy text-2xl md:text-xl lg:text-2xl shrink-0"></i>
                                    <span class="text-[10px] font-bold uppercase tracking-wider">Total Kegiatan</span>
                                </div>
                                <span class="block text-3xl font-black text-white tracking-tight mt-1"><?php echo e($extracurriculars->count()); ?></span>
                            </div>

                            
                            <?php
                                $totalMembers = $extracurriculars->sum('members_count');
                            ?>
                            <div class="bg-white/10 backdrop-blur-md px-5 py-5 rounded-2xl border border-white/10 text-center md:text-left hover:bg-white/15 transition-colors">
                                <div class="flex flex-col md:flex-row lg:flex-col items-center justify-center md:justify-start gap-2 mb-1 text-emerald-300">
                                    <i class="ph-duotone ph-users-three text-2xl md:text-xl lg:text-2xl shrink-0"></i>
                                    <span class="text-[10px] font-bold uppercase tracking-wider">Siswa Aktif</span>
                                </div>
                                <span class="block text-3xl font-black text-white tracking-tight mt-1"><?php echo e($totalMembers); ?></span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        
        <?php if(session('success')): ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: "<?php echo e(session('success')); ?>",
                        timer: 3000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                });
            </script>
        <?php endif; ?>

        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 px-4 sm:px-0">
            <?php $__empty_1 = true; $__currentLoopData = $extracurriculars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ekskul): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="bg-white rounded-[2rem] border border-slate-100 p-6 hover:shadow-2xl hover:shadow-blue-900/10 hover:border-blue-200 transition-all duration-300 group flex flex-col h-full relative overflow-hidden">
                    
                    
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-gradient-to-br from-blue-50 to-slate-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="absolute top-6 right-6 opacity-0 group-hover:opacity-10 transition-opacity duration-300 transform translate-x-4 group-hover:translate-x-0">
                        <i class="ph-fill ph-basketball text-6xl text-blue-900"></i>
                    </div>

                    
                    <div class="flex items-start justify-between mb-6 relative z-10">
                        
                        <div class="w-16 h-16 shrink-0 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-900 text-3xl shadow-sm group-hover:shadow-md group-hover:bg-blue-900 group-hover:text-white transition-all duration-300">
                            <?php if(Str::startsWith($ekskul->icon, 'storage/')): ?>
                                <img src="<?php echo e(asset($ekskul->icon)); ?>" class="w-full h-full object-cover rounded-2xl">
                            <?php elseif(Str::startsWith($ekskul->icon, 'http')): ?>
                                <img src="<?php echo e($ekskul->icon); ?>" class="w-full h-full object-cover rounded-2xl">
                            <?php else: ?>
                                <i class="<?php echo e($ekskul->icon ?? 'ph-fill ph-star'); ?>"></i>
                            <?php endif; ?>
                        </div>
                        
                        
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false" class="w-10 h-10 flex items-center justify-center rounded-xl text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors">
                                <i class="ph-bold ph-dots-three-vertical text-xl"></i>
                            </button>
                            <div x-show="open" class="absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-xl border border-slate-100 z-20 py-2 ring-1 ring-black/5" style="display: none;" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100">
                                
                                
                                <button @click="
                                    editModalOpen = true; 
                                    editData = JSON.parse('<?php echo e(json_encode($ekskul, JSON_HEX_APOS | JSON_HEX_QUOT)); ?>');
                                    open = false;
                                    setTimeout(() => setupEditForm(editData), 50);
                                " class="w-full text-left px-4 py-2.5 text-xs font-bold text-slate-600 hover:bg-slate-50 hover:text-blue-600 flex items-center gap-2 transition-colors">
                                    <i class="ph-bold ph-pencil-simple text-base"></i> Edit Data
                                </button>
                                
                                <div class="border-t border-slate-100 my-1"></div>

                                
                                <form action="<?php echo e(route('extracurriculars.destroy', $ekskul->id)); ?>" method="POST" class="delete-form">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="button" class="btn-delete w-full text-left px-4 py-2.5 text-xs font-bold text-rose-500 hover:bg-rose-50 flex items-center gap-2 transition-colors">
                                        <i class="ph-bold ph-trash text-base"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    
                    <div class="flex-1 relative z-10">
                        <h3 class="text-xl font-black text-slate-800 mb-1 group-hover:text-blue-900 transition-colors line-clamp-2"><?php echo e($ekskul->name); ?></h3>
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-400 mb-6 uppercase tracking-wide">
                            <i class="ph-bold ph-user-circle text-blue-500 text-lg"></i>
                            <span class="truncate"><?php echo e($ekskul->coach_name ?? 'Belum ada pembina'); ?></span>
                        </div>

                        <div class="space-y-3">
                            
                            <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100">
                                <span class="text-[10px] font-bold text-slate-400 uppercase">Jadwal</span>
                                <span class="text-xs font-bold text-slate-700 flex items-center gap-1.5 text-right">
                                    <div class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></div>
                                    <span class="truncate max-w-[120px]"><?php echo e($ekskul->schedule ?? '-'); ?></span>
                                </span>
                            </div>
                            
                            
                            <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100">
                                <span class="text-[10px] font-bold text-slate-400 uppercase">Partisipan</span>
                                <div class="flex items-center -space-x-2">
                                    
                                    <div class="w-6 h-6 rounded-full border-2 border-white bg-blue-200"></div>
                                    <div class="w-6 h-6 rounded-full border-2 border-white bg-pink-200"></div>
                                    <div class="w-6 h-6 rounded-full border-2 border-white bg-slate-200 flex items-center justify-center text-[8px] font-bold text-slate-600">
                                        +<?php echo e($ekskul->members_count); ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="mt-6 pt-4 relative z-10">
                        <a href="<?php echo e(route('extracurriculars.members', ['ekskul_id' => $ekskul->id])); ?>" class="w-full py-3 rounded-xl bg-blue-50 text-blue-900 font-bold text-sm flex items-center justify-center gap-2 group-hover:bg-blue-900 group-hover:text-white transition-all shadow-sm group-hover:shadow-lg group-hover:shadow-blue-900/30">
                            <span>Kelola Anggota</span>
                            <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-full py-20 text-center bg-white rounded-[2.5rem] border border-slate-100 shadow-sm">
                    <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                        <i class="ph-duotone ph-puzzle-piece text-5xl text-slate-300"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-700 mb-2">Belum ada Ekstrakurikuler</h3>
                    <p class="text-slate-400 text-sm max-w-xs mx-auto">Tambahkan kegiatan baru untuk memulai manajemen bakat siswa.</p>
                    <button @click="addModalOpen = true" class="mt-6 px-6 py-2.5 bg-blue-900 text-white rounded-xl font-bold text-sm hover:bg-blue-800 transition shadow-lg shadow-blue-900/20">
                        + Tambah Data
                    </button>
                </div>
            <?php endif; ?>
        </div>

        
        <div x-show="addModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-blue-950/60 backdrop-blur-sm transition-opacity" @click="addModalOpen = false"></div>
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-lg overflow-hidden transform transition-all border border-white/20">
                    <div class="bg-gradient-to-r from-blue-900 to-blue-800 p-6 flex justify-between items-center">
                        <h3 class="text-lg font-black text-white flex items-center gap-2">
                            <i class="ph-bold ph-plus-circle text-blue-300"></i> Tambah Ekskul Baru
                        </h3>
                        <button @click="addModalOpen = false" class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-white/20 transition-colors"><i class="ph-bold ph-x"></i></button>
                    </div>
                    <form action="<?php echo e(route('extracurriculars.store')); ?>" method="POST" enctype="multipart/form-data" class="p-8 space-y-5">
                        <?php echo csrf_field(); ?>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Nama Ekskul <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" required placeholder="Contoh: Basket Putra" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm py-3 px-4 font-bold text-slate-700 transition-all">
                        </div>
                        <div class="grid grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Nama Pembina</label>
                                <input type="text" name="coach_name" placeholder="Bpk/Ibu..." class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm py-3 px-4 font-bold text-slate-700 transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Jadwal Latihan</label>
                                <input type="text" name="schedule" placeholder="Senin, 15:00" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm py-3 px-4 font-bold text-slate-700 transition-all">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Ikon / Logo</label>
                            <div x-data="{ type: 'upload' }" class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                                <div class="flex gap-4 mb-4">
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <div class="w-4 h-4 rounded-full border-2 border-slate-300 flex items-center justify-center group-hover:border-blue-500 transition-colors">
                                            <div class="w-2 h-2 rounded-full bg-blue-600 opacity-0" :class="{'opacity-100': type === 'upload'}"></div>
                                        </div>
                                        <input type="radio" x-model="type" value="upload" class="hidden">
                                        <span class="text-xs font-bold text-slate-600 group-hover:text-blue-600 transition-colors">Upload Gambar</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <div class="w-4 h-4 rounded-full border-2 border-slate-300 flex items-center justify-center group-hover:border-blue-500 transition-colors">
                                            <div class="w-2 h-2 rounded-full bg-blue-600 opacity-0" :class="{'opacity-100': type === 'icon'}"></div>
                                        </div>
                                        <input type="radio" x-model="type" value="icon" class="hidden">
                                        <span class="text-xs font-bold text-slate-600 group-hover:text-blue-600 transition-colors">Phosphor Icon</span>
                                    </label>
                                </div>
                                <div x-show="type === 'upload'">
                                    <input type="file" name="image_file" accept="image/*" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 transition-colors cursor-pointer"/>
                                </div>
                                <div x-show="type === 'icon'" style="display: none;">
                                    <div class="relative">
                                        <i class="ph-bold ph-code absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="text" name="icon_text" placeholder="Contoh: ph-fill ph-basketball" class="w-full rounded-xl border-slate-300 pl-9 text-sm py-2.5 bg-white focus:border-blue-600 focus:ring-blue-600 font-mono text-slate-600">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pt-2 flex gap-3">
                            <button type="button" @click="addModalOpen = false" class="flex-1 py-3.5 rounded-xl bg-slate-100 text-slate-600 font-bold text-sm hover:bg-slate-200 transition-colors">Batal</button>
                            <button type="submit" class="flex-1 py-3.5 rounded-xl bg-blue-900 text-white text-sm font-bold hover:bg-blue-800 shadow-lg shadow-blue-900/20 transition-all transform active:scale-95">Simpan Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        
        <div x-show="editModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-blue-950/60 backdrop-blur-sm transition-opacity" @click="editModalOpen = false"></div>
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-lg overflow-hidden transform transition-all border border-white/20">
                    <div class="bg-blue-900 p-6 flex justify-between items-center">
                        <h3 class="text-lg font-black text-white flex items-center gap-2">
                            <i class="ph-bold ph-pencil-simple text-blue-300"></i> Edit Ekstrakurikuler
                        </h3>
                        <button @click="editModalOpen = false" class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-white/20 transition-colors"><i class="ph-bold ph-x"></i></button>
                    </div>
                    <form id="editForm" method="POST" enctype="multipart/form-data" class="p-8 space-y-5">
                        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Nama Ekskul</label>
                            <input type="text" name="name" id="edit_name" required class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm py-3 px-4 font-bold text-slate-700 transition-all">
                        </div>
                        <div class="grid grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Nama Pembina</label>
                                <input type="text" name="coach_name" id="edit_coach" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm py-3 px-4 font-bold text-slate-700 transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Jadwal</label>
                                <input type="text" name="schedule" id="edit_schedule" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm py-3 px-4 font-bold text-slate-700 transition-all">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Update Tampilan</label>
                            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                                <div class="mb-4">
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Ganti Gambar (Opsional)</label>
                                    <input type="file" name="image_file" accept="image/*" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 transition-colors cursor-pointer"/>
                                </div>
                                <div class="border-t border-slate-200 my-4 relative">
                                    <span class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 bg-slate-50 px-2 text-[10px] font-bold text-slate-400">ATAU</span>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Ganti Kode Ikon</label>
                                    <div class="relative">
                                        <i class="ph-bold ph-code absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="text" name="icon_text" id="edit_icon_text" placeholder="Contoh: ph-fill ph-trophy" class="w-full rounded-xl border-slate-300 pl-9 text-sm py-2.5 bg-white focus:border-blue-600 focus:ring-blue-600 font-mono text-slate-600">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pt-2 flex gap-3">
                            <button type="button" @click="editModalOpen = false" class="flex-1 py-3.5 rounded-xl bg-slate-100 text-slate-600 font-bold text-sm hover:bg-slate-200 transition-colors">Batal</button>
                            <button type="submit" class="flex-1 py-3.5 rounded-xl bg-blue-900 text-white text-sm font-bold hover:bg-blue-800 shadow-lg shadow-blue-900/20 transition-all transform active:scale-95">Update Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <script>
        function setupEditForm(ekskul) {
            if(!ekskul) return; // Guard clause

            document.getElementById('edit_name').value = ekskul.name;
            document.getElementById('edit_coach').value = ekskul.coach_name;
            document.getElementById('edit_schedule').value = ekskul.schedule;
            
            if (ekskul.icon && !ekskul.icon.startsWith('storage/')) {
                document.getElementById('edit_icon_text').value = ekskul.icon;
            } else {
                document.getElementById('edit_icon_text').value = '';
            }

            // Replace URL ID dummy '0'
            let url = "<?php echo e(route('extracurriculars.update', 0)); ?>";
            let form = document.getElementById('editForm');
            form.action = url.replace('/0', '/' + ekskul.id);
        }

        // Handle Delete with SweetAlert
        document.addEventListener('DOMContentLoaded', function() {
            // Gunakan event delegation untuk element yang mungkin dinamis, atau init ulang
            const deleteButtons = document.querySelectorAll('.btn-delete');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    const form = this.closest('.delete-form');
                    Swal.fire({
                        title: 'Hapus Ekskul?',
                        text: "Data ekskul beserta riwayat absensinya akan dihapus permanen.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e11d48', // Rose-600
                        cancelButtonColor: '#64748b', // Slate-500
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        borderRadius: '1.5rem',
                        customClass: {
                            popup: 'rounded-[2rem]',
                            confirmButton: 'rounded-xl px-6 py-2.5 font-bold',
                            cancelButton: 'rounded-xl px-6 py-2.5 font-bold'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
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
<?php endif; ?><?php /**PATH D:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/extracurriculars/index.blade.php ENDPATH**/ ?>