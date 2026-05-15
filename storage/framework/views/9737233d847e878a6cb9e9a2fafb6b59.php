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
            search: '', 
            editModalOpen: false,
            editData: {
                id: null,
                name: '',
                code: '',
                order: '',
                group: 'A',
                actionUrl: ''
            },
            openEdit(subject) {
                this.editData = {
                    id: subject.id,
                    name: subject.name,
                    code: subject.code,
                    order: subject.order,
                    group: subject.group,
                    actionUrl: '<?php echo e(route('subjects.update', ':id')); ?>'.replace(':id', subject.id)
                };
                this.editModalOpen = true;
                this.$nextTick(() => { document.getElementById('edit_name').focus(); });
            }
        }" 
        class="py-8 sm:py-10 font-sans text-elevate-dark relative overflow-hidden">
        
        
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10 relative z-10">
            <div class="relative rounded-[2.5rem] bg-elevate-gradient-main p-8 sm:p-10 text-elevate-dark shadow-xl shadow-elevate-accent/20 overflow-hidden border border-white/60 group">
                
                
                <div class="absolute inset-0 opacity-[0.03] bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none mix-blend-overlay"></div>
                <div class="absolute -top-24 -right-24 w-80 h-80 bg-white/40 rounded-full blur-3xl pointer-events-none group-hover:bg-white/60 transition-all duration-700"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                    
                    
                    <div class="max-w-2xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-elevate-soft border border-elevate-accent/30 text-elevate-primary text-[10px] font-bold uppercase tracking-widest mb-4 backdrop-blur-sm shadow-sm">
                            <i class="ph-fill ph-books"></i> Kurikulum Merdeka
                        </div>
                        <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-3 flex items-center gap-3 text-elevate-dark leading-tight">
                            Mata Pelajaran
                        </h1>
                        <p class="text-elevate-dark/80 text-sm md:text-base font-semibold leading-relaxed max-w-lg">
                            Kelola daftar mata pelajaran, kodefikasi, dan pengelompokan (A, B, C, P5) untuk keperluan rapor dan jadwal.
                        </p>
                    </div>
                    
                    
                    <div class="flex gap-4">
                        <div class="bg-white/60 backdrop-blur-md px-6 py-5 rounded-2xl border border-white/80 min-w-[140px] text-center md:text-left hover:bg-white transition-colors shadow-sm">
                            <div class="flex items-center justify-center md:justify-start gap-2 mb-1 text-elevate-primary">
                                <i class="ph-duotone ph-book-open-text text-lg"></i>
                                <span class="text-[10px] font-bold uppercase tracking-wider">Total Mapel</span>
                            </div>
                            <span class="block text-3xl font-black text-elevate-dark tracking-tight"><?php echo e($subjects->count()); ?></span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            
            <?php if(session('success')): ?>
                <div x-data="{ show: true }" x-show="show" x-transition class="mb-8 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-emerald-100 rounded-full text-emerald-600">
                            <i class="ph-bold ph-check-circle text-xl"></i>
                        </div>
                        <span class="font-bold text-sm"><?php echo e(session('success')); ?></span>
                    </div>
                    <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 p-1 rounded-md hover:bg-emerald-100 transition"><i class="ph-bold ph-x"></i></button>
                </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div x-data="{ show: true }" x-show="show" class="mb-8 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl flex items-start gap-3 shadow-sm">
                    <div class="p-2 bg-rose-100 rounded-full text-rose-600 shrink-0">
                        <i class="ph-bold ph-warning-circle text-xl"></i>
                    </div>
                    <div>
                        <p class="font-bold text-sm mb-1">Terdapat kesalahan input:</p>
                        <ul class="list-disc list-inside text-xs font-medium">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden sticky top-24 relative group hover:shadow-2xl hover:shadow-elevate-accent/10 transition-all duration-300">
                        
                        
                        <div class="bg-elevate-gradient-card p-8 text-elevate-dark relative overflow-hidden border-b border-slate-100">
                            <div class="absolute -right-6 -top-6 text-elevate-primary/5 text-9xl pointer-events-none">
                                <i class="ph-fill ph-plus-circle"></i>
                            </div>
                            <h3 class="text-xl font-black relative z-10">Tambah Mapel</h3>
                            <p class="text-elevate-dark/60 text-sm font-medium relative z-10 mt-1">Input data pelajaran baru.</p>
                        </div>

                        <div class="p-8 relative z-10">
                            <form action="<?php echo e(route('subjects.store')); ?>" method="POST" class="space-y-6">
                                <?php echo csrf_field(); ?>
                                <div>
                                    <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Nama Mapel</label>
                                    <div class="relative group">
                                        <i class="ph-bold ph-text-t absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-elevate-primary transition-colors"></i>
                                        <input type="text" name="name" value="<?php echo e(old('name')); ?>" placeholder="Contoh: Matematika" required 
                                               class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 text-sm font-bold text-elevate-dark transition-all placeholder:font-medium shadow-sm">
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Kode</label>
                                        <div class="relative group">
                                            <i class="ph-bold ph-tag absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-elevate-primary transition-colors"></i>
                                            <input type="text" name="code" value="<?php echo e(old('code')); ?>" placeholder="MTK" 
                                                   class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 text-sm font-bold text-elevate-dark uppercase transition-all placeholder:font-medium shadow-sm">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">No. Urut</label>
                                        <div class="relative group">
                                            <i class="ph-bold ph-sort-ascending absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-elevate-primary transition-colors"></i>
                                            <input type="number" name="order" value="<?php echo e(old('order', $subjects->count() + 1)); ?>" required 
                                                   class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 text-sm font-bold text-elevate-dark transition-all shadow-sm">
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Kelompok</label>
                                    <div class="relative group">
                                        <select name="group" class="w-full pl-4 pr-10 py-3.5 rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 text-sm font-bold text-elevate-dark transition-all appearance-none cursor-pointer shadow-sm">
                                            <option value="A" <?php echo e(old('group') == 'A' ? 'selected' : ''); ?>>Kelompok A (Umum)</option>
                                            <option value="B" <?php echo e(old('group') == 'B' ? 'selected' : ''); ?>>Kelompok B (Muatan Lokal)</option>
                                            <option value="C" <?php echo e(old('group') == 'C' ? 'selected' : ''); ?>>Kelompok C (Peminatan)</option>
                                            <option value="P5" <?php echo e(old('group') == 'P5' ? 'selected' : ''); ?>>Projek (P5)</option>
                                        </select>
                                        <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                    </div>
                                </div>

                                <button type="submit" class="w-full py-3.5 px-6 bg-elevate-dark text-white font-bold rounded-2xl hover:bg-elevate-primary transition-all shadow-lg shadow-elevate-dark/30 flex items-center justify-center gap-2 transform active:scale-95 border border-transparent">
                                    <i class="ph-bold ph-floppy-disk text-lg"></i>
                                    Simpan Mapel
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden flex flex-col h-full min-h-[600px]">
                        <div class="p-8 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                            <div class="flex items-center gap-3">
                                <h2 class="text-lg font-black text-elevate-dark flex items-center gap-2">
                                    <i class="ph-fill ph-list-dashes text-elevate-primary"></i> Daftar Mapel
                                </h2>
                                <span class="bg-white border border-slate-200 text-[10px] font-black px-3 py-1.5 rounded-xl text-elevate-primary shadow-sm">
                                    <?php echo e($subjects->count()); ?> Data
                                </span>
                            </div>
                            
                            <div class="relative w-full sm:w-64 group">
                                <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-elevate-primary transition-colors"></i>
                                <input x-model="search" type="text" placeholder="Cari mapel..." class="w-full pl-11 pr-4 py-2.5 rounded-xl border-slate-200 bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 text-sm font-bold transition-all shadow-sm text-elevate-dark">
                            </div>
                        </div>
                        
                        <div class="overflow-x-auto flex-1 custom-scrollbar">
                            <table class="w-full text-left text-sm text-elevate-dark">
                                <thead class="bg-elevate-soft/50 text-xs font-bold text-elevate-primary uppercase tracking-wider border-b border-slate-100">
                                    <tr>
                                        <th class="px-6 py-5 text-center w-20">Urut</th>
                                        <th class="px-6 py-5">Mata Pelajaran</th>
                                        <th class="px-6 py-5 whitespace-nowrap">Kelompok</th>
                                        <th class="px-6 py-5 text-right whitespace-nowrap">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    <?php $__empty_1 = true; $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr class="hover:bg-elevate-soft/30 transition-colors group"
                                            x-show="search === '' || '<?php echo e(strtolower($subject->name)); ?>'.includes(search.toLowerCase()) || '<?php echo e(strtolower($subject->code)); ?>'.includes(search.toLowerCase())"
                                            x-transition.opacity>
                                            
                                            <td class="px-6 py-5 text-center">
                                                <div class="w-8 h-8 rounded-lg bg-slate-50 border border-slate-200 text-slate-500 font-black flex items-center justify-center text-xs mx-auto">
                                                    <?php echo e($subject->order); ?>

                                                </div>
                                            </td>
                                            <td class="px-6 py-5">
                                                <div class="font-bold text-elevate-dark text-base mb-0.5"><?php echo e($subject->name); ?></div>
                                                <?php if($subject->code): ?>
                                                    <span class="text-[10px] font-mono font-bold text-elevate-dark/60 bg-elevate-soft px-1.5 py-0.5 rounded border border-slate-200 shadow-sm"><?php echo e($subject->code); ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <?php
                                                    $badgeClass = match($subject->group) {
                                                        'A' => 'bg-elevate-soft text-elevate-primary border-elevate-accent/30',
                                                        'B' => 'bg-elevate-peach-light/40 text-elevate-peach-dark border-elevate-peach/30',
                                                        'C' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                        'P5' => 'bg-slate-50 text-slate-600 border-slate-200',
                                                        default => 'bg-slate-50 text-slate-600 border-slate-200'
                                                    };
                                                ?>
                                                <span class="px-3 py-1.5 rounded-xl text-xs font-black border <?php echo e($badgeClass); ?>">
                                                    Kelompok <?php echo e($subject->group); ?>

                                                </span>
                                            </td>
                                            <td class="px-6 py-5 text-right whitespace-nowrap">
                                                <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity transform translate-x-2 group-hover:translate-x-0 duration-200">
                                                    
                                                    <button @click="openEdit(<?php echo e($subject); ?>)"
                                                        class="w-9 h-9 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-elevate-primary hover:border-elevate-accent/50 hover:bg-elevate-soft transition-all shadow-sm" title="Edit Mapel">
                                                        <i class="ph-bold ph-pencil-simple text-lg"></i>
                                                    </button>

                                                    <form action="<?php echo e(route('subjects.destroy', $subject->id)); ?>" 
                                                          method="POST" 
                                                          id="delete-subject-<?php echo e($subject->id); ?>">
                                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                        
                                                        <button type="button" 
                                                                onclick="confirmDelete('<?php echo e($subject->id); ?>', '<?php echo e($subject->name); ?>')"
                                                                class="w-9 h-9 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-rose-600 hover:border-rose-200 hover:bg-rose-50 transition-all shadow-sm" title="Hapus Mapel">
                                                            <i class="ph-bold ph-trash text-lg"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="4" class="px-6 py-20 text-center">
                                                <div class="w-16 h-16 bg-elevate-soft rounded-full flex items-center justify-center mx-auto mb-4 text-elevate-primary shadow-inner">
                                                    <i class="ph-duotone ph-books text-4xl"></i>
                                                </div>
                                                <p class="text-sm font-bold text-elevate-dark/60">Belum ada mata pelajaran.</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div x-show="editModalOpen" style="display: none;" 
            class="fixed inset-0 z-50 flex items-center justify-center p-4">
            
            <div @click="editModalOpen = false" 
                x-show="editModalOpen"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="absolute inset-0 bg-elevate-dark/60 backdrop-blur-sm transition-opacity"></div>

            <div x-show="editModalOpen"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                class="bg-white rounded-[2rem] shadow-2xl w-full max-w-md overflow-hidden relative z-10 border border-slate-100">
                
                <div class="bg-elevate-soft p-6 flex justify-between items-center border-b border-slate-200 text-elevate-dark">
                    <h3 class="text-lg font-black flex items-center gap-2">
                        <i class="ph-bold ph-pencil-simple text-elevate-primary"></i> Edit Mata Pelajaran
                    </h3>
                    <button @click="editModalOpen = false" class="w-8 h-8 flex items-center justify-center rounded-full bg-white text-slate-400 hover:text-elevate-dark shadow-sm transition-colors border border-slate-200">
                        <i class="ph-bold ph-x"></i>
                    </button>
                </div>
                
                <form :action="editData.actionUrl" method="POST" class="p-8 space-y-5">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    
                    <div>
                        <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Nama Mapel</label>
                        <input type="text" name="name" id="edit_name" x-model="editData.name" required 
                               class="w-full px-4 py-3.5 rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 text-sm font-bold text-elevate-dark transition-all shadow-sm">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Kode</label>
                            <input type="text" name="code" x-model="editData.code" 
                                   class="w-full px-4 py-3.5 rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 text-sm font-bold text-elevate-dark uppercase transition-all shadow-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">No. Urut</label>
                            <input type="number" name="order" x-model="editData.order" required 
                                   class="w-full px-4 py-3.5 rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 text-sm font-bold text-elevate-dark transition-all shadow-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Kelompok</label>
                        <div class="relative">
                            <select name="group" x-model="editData.group" class="w-full px-4 py-3.5 rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 text-sm font-bold text-elevate-dark appearance-none cursor-pointer transition-all shadow-sm">
                                <option value="A">Kelompok A (Umum)</option>
                                <option value="B">Kelompok B (Muatan Lokal)</option>
                                <option value="C">Kelompok C (Peminatan)</option>
                                <option value="P5">Projek (P5)</option>
                            </select>
                            <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                        </div>
                    </div>

                    <div class="pt-4 flex gap-3">
                        <button type="button" @click="editModalOpen = false" class="flex-1 py-3.5 bg-slate-100 text-elevate-dark/60 font-bold rounded-2xl hover:bg-slate-200 transition-colors text-sm border border-transparent">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 py-3.5 bg-elevate-dark text-white font-bold rounded-2xl hover:bg-elevate-primary transition-all shadow-lg shadow-elevate-dark/20 text-sm transform active:scale-95 border border-transparent">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function confirmDelete(id, name) {
            Swal.fire({
                title: 'Hapus Mapel?',
                text: `Yakin ingin menghapus ${name}? Data nilai siswa pada mapel ini mungkin akan hilang.`,
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
                if (result.isConfirmed) {
                    const form = document.getElementById('delete-subject-' + id);
                    if (form) form.submit();
                }
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/settings/subjects.blade.php ENDPATH**/ ?>