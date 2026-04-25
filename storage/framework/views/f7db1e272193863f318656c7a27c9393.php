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
    <div x-data="{ 
        showEditModal: false, 
        editForm: { id: '', title: '', type: '', start_date: '', end_date: '', is_all_day: false, description: '' },
        openEdit(event) {
            this.editForm.id = event.id;
            this.editForm.title = event.title;
            this.editForm.type = event.type;
            this.editForm.start_date = event.start_date;
            this.editForm.end_date = event.end_date;
            this.editForm.is_all_day = event.is_all_day;
            this.editForm.description = event.description;
            this.showEditModal = true;
        }
    }" class="p-6 max-w-7xl mx-auto space-y-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-black text-slate-800">Kalender Pendidikan</h1>
                <p class="text-slate-500">Kelola agenda, ujian, dan hari libur sekolah.</p>
            </div>
        </div>

        <?php if(session('success')): ?>
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl mb-4 flex items-center gap-3">
            <i class="ph-fill ph-check-circle text-xl"></i>
            <span class="font-bold"><?php echo e(session('success')); ?></span>
        </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 sticky top-6">
                    <h3 class="font-bold text-slate-800 text-lg mb-4 flex items-center gap-2">
                        <i class="ph-duotone ph-plus-circle text-blue-500 text-xl"></i> Tambah Agenda
                    </h3>
                    
                    <form action="<?php echo e(route('admin.academic-calendar.store')); ?>" method="POST" class="space-y-4">
                        <?php echo csrf_field(); ?>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1">Nama Agenda</label>
                            <input type="text" name="title" required placeholder="Contoh: Libur Semester Ganjil" 
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all text-sm outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1">Jenis Agenda</label>
                            <select name="type" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all text-sm outline-none bg-white">
                                <option value="kegiatan">Kegiatan Sekolah (Biru)</option>
                                <option value="ujian">Ujian / Assesmen (Kuning)</option>
                                <option value="libur">Libur Sekolah (Merah)</option>
                                <option value="nasional">Libur Nasional (Merah)</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1">Tgl Mulai</label>
                                <input type="date" name="start_date" required 
                                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1">Tgl Selesai (Opsional)</label>
                                <input type="date" name="end_date" 
                                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm outline-none">
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100">
                            <input type="checkbox" name="is_all_day" id="is_all_day" value="1" checked 
                                   class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500 border-gray-300">
                            <label for="is_all_day" class="text-sm font-bold text-slate-700 cursor-pointer">
                                Berlangsung Seharian Penuh
                            </label>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1">Keterangan Tambahan</label>
                            <textarea name="description" rows="3" placeholder="Opsional..." 
                                      class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm outline-none"></textarea>
                        </div>

                        <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold transition-colors shadow-lg shadow-blue-600/30 flex justify-center items-center gap-2">
                            <i class="ph-bold ph-floppy-disk"></i> Simpan Agenda
                        </button>
                    </form>
                </div>
            </div>

            
            <div class="lg:col-span-2 space-y-4">
                <?php $__empty_1 = true; $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $isPast = \Carbon\Carbon::parse($event->start_date)->isPast();
                        // Setup Warna Badge
                        $badgeColor = 'bg-blue-100 text-blue-700 border-blue-200';
                        $icon = 'ph-calendar-check';
                        
                        if($event->type == 'ujian') {
                            $badgeColor = 'bg-amber-100 text-amber-700 border-amber-200';
                            $icon = 'ph-pencil-simple';
                        } elseif(in_array($event->type, ['libur', 'nasional'])) {
                            $badgeColor = 'bg-rose-100 text-rose-700 border-rose-200';
                            $icon = 'ph-tent';
                        }
                    ?>
                    <div class="bg-white p-5 rounded-[2rem] shadow-sm border <?php echo e($isPast ? 'border-slate-100 opacity-60' : 'border-slate-200'); ?> flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between group hover:border-blue-300 transition-colors">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-2xl shrink-0 <?php echo e($badgeColor); ?> border">
                                <i class="ph-duotone <?php echo e($icon); ?>"></i>
                            </div>
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded border <?php echo e($badgeColor); ?>">
                                        <?php echo e($event->type); ?>

                                    </span>
                                    <?php if($isPast): ?>
                                        <span class="text-[10px] font-bold text-slate-400">Sudah Berlalu</span>
                                    <?php endif; ?>
                                </div>
                                <h4 class="font-black text-slate-800 text-lg leading-tight mb-1"><?php echo e($event->title); ?></h4>
                                <p class="text-sm font-bold text-slate-500 flex items-center gap-1.5">
                                    <i class="ph-bold ph-clock"></i>
                                    <?php echo e(\Carbon\Carbon::parse($event->start_date)->translatedFormat('d F Y')); ?>

                                    <?php if($event->end_date): ?>
                                        - <?php echo e(\Carbon\Carbon::parse($event->end_date)->translatedFormat('d F Y')); ?>

                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            
                            <button type="button" @click="openEdit({
                                id: '<?php echo e($event->id); ?>',
                                title: '<?php echo e(addslashes($event->title)); ?>',
                                type: '<?php echo e($event->type); ?>',
                                start_date: '<?php echo e(\Carbon\Carbon::parse($event->start_date)->format('Y-m-d')); ?>',
                                end_date: '<?php echo e($event->end_date ? \Carbon\Carbon::parse($event->end_date)->format('Y-m-d') : ''); ?>',
                                is_all_day: <?php echo e($event->is_all_day ? 'true' : 'false'); ?>,
                                description: '<?php echo e(addslashes($event->description ?? '')); ?>'
                            })" class="w-10 h-10 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center hover:bg-amber-500 hover:text-white transition-colors">
                                <i class="ph-bold ph-pencil-simple"></i>
                            </button>

                            
                            <form action="<?php echo e(route('admin.academic-calendar.destroy', $event->id)); ?>" method="POST" onsubmit="return confirm('Hapus agenda ini?');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="w-10 h-10 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center hover:bg-rose-500 hover:text-white transition-colors">
                                    <i class="ph-bold ph-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-20 bg-slate-50 rounded-[2rem] border border-dashed border-slate-200">
                        <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300 shadow-sm">
                            <i class="ph-duotone ph-calendar-blank text-4xl"></i>
                        </div>
                        <h3 class="text-lg font-black text-slate-600 mb-1">Belum Ada Agenda</h3>
                        <p class="text-slate-400 text-sm">Silakan tambahkan agenda pertama Anda melalui form di samping.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        
        <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showEditModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" aria-hidden="true" @click="showEditModal = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showEditModal" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-[2rem] shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6 border border-slate-100">
                    
                    <div class="flex justify-between items-center mb-5">
                        <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                            <i class="ph-duotone ph-pencil-circle text-amber-500 text-xl"></i> Edit Agenda
                        </h3>
                        <button type="button" @click="showEditModal = false" class="text-slate-400 hover:text-rose-500 transition-colors">
                            <i class="ph-bold ph-x text-lg"></i>
                        </button>
                    </div>

                    <form :action="`/admin/academic-calendar/${editForm.id}`" method="POST" class="space-y-4">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1">Nama Agenda</label>
                            <input type="text" name="title" x-model="editForm.title" required 
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all text-sm outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1">Jenis Agenda</label>
                            <select name="type" x-model="editForm.type" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all text-sm outline-none bg-white">
                                <option value="kegiatan">Kegiatan Sekolah (Biru)</option>
                                <option value="ujian">Ujian / Assesmen (Kuning)</option>
                                <option value="libur">Libur Sekolah (Merah)</option>
                                <option value="nasional">Libur Nasional (Merah)</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1">Tgl Mulai</label>
                                <input type="date" name="start_date" x-model="editForm.start_date" required 
                                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1">Tgl Selesai (Opsional)</label>
                                <input type="date" name="end_date" x-model="editForm.end_date" 
                                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm outline-none">
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100">
                            <input type="checkbox" name="is_all_day" id="edit_is_all_day" value="1" x-model="editForm.is_all_day"
                                   class="w-5 h-5 text-amber-600 rounded focus:ring-amber-500 border-gray-300">
                            <label for="edit_is_all_day" class="text-sm font-bold text-slate-700 cursor-pointer">
                                Berlangsung Seharian Penuh
                            </label>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1">Keterangan Tambahan</label>
                            <textarea name="description" x-model="editForm.description" rows="3" 
                                      class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm outline-none"></textarea>
                        </div>

                        <div class="pt-4 flex gap-3">
                            <button type="button" @click="showEditModal = false" class="flex-1 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold transition-colors">
                                Batal
                            </button>
                            <button type="submit" class="flex-1 py-3 bg-amber-500 hover:bg-amber-600 text-white rounded-xl font-bold transition-colors shadow-lg shadow-amber-500/30 flex justify-center items-center gap-2">
                                <i class="ph-bold ph-check"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/admin/academic-calendar/index.blade.php ENDPATH**/ ?>