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
    <div class="py-8 sm:py-10 font-sans text-elevate-text bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-2xl font-black text-elevate-dark tracking-tight">Edit Data Alumni</h1>
                    <p class="text-sm text-slate-500 font-medium mt-1">Input manual tracer study (Sekolah Lanjutan / Karir).</p>
                </div>
                <a href="<?php echo e(route('admin.alumni.index')); ?>" class="px-5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-600 hover:bg-slate-50 hover:text-elevate-primary hover:border-elevate-primary/30 transition-all flex items-center gap-2 shadow-sm group">
                    <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali
                </a>
            </div>

            
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden relative group hover:border-slate-200 transition-colors">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-elevate-accent to-elevate-primary"></div>
                
                
                <?php
                    $currentStatus = $student->alumniProfile->activity_status ?? 'SMA';
                    if ($currentStatus == 'Tidak Lanjut') {
                        $currentStatus = 'Lainnya';
                    }
                ?>

                <form action="<?php echo e(route('admin.alumni.update', $student->id)); ?>" method="POST" x-data="{ status: '<?php echo e($currentStatus); ?>' }">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div class="p-8 space-y-8 mt-2">
                        
                        
                        <div class="bg-slate-50/50 p-6 rounded-2xl flex flex-col md:flex-row md:items-center gap-4 border border-slate-200">
                            <div class="w-16 h-16 rounded-[1rem] bg-white border border-slate-200 flex items-center justify-center text-xl font-black text-elevate-primary shrink-0 shadow-sm overflow-hidden">
                                <?php if($student->photo_path): ?>
                                    <img src="<?php echo e(asset('storage/' . $student->photo_path)); ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <?php echo e(substr($student->name, 0, 2)); ?>

                                <?php endif; ?>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-black text-elevate-dark leading-tight"><?php echo e($student->name); ?></h3>
                                <p class="text-sm font-medium text-slate-500 mt-1"><?php echo e($student->nisn ?? $student->student_id); ?> | <span class="font-bold">Lulusan <?php echo e($student->graduation_year ?? ($student->graduated_date ? \Carbon\Carbon::parse($student->graduated_date)->year : '-')); ?></span></p>
                            </div>
                            <div class="shrink-0 text-right md:block hidden">
                                <span class="px-4 py-2 bg-elevate-primary/10 text-elevate-primary text-xs font-bold rounded-xl border border-elevate-primary/20">Status: Alumni</span>
                            </div>
                        </div>

                        
                        <div>
                            <h4 class="font-black text-elevate-dark mb-4 flex items-center gap-2 text-sm uppercase tracking-wider">
                                <i class="ph-fill ph-address-book text-elevate-primary"></i> Kontak Terkini
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">No. HP / WA</label>
                                    <input type="text" name="phone_number" value="<?php echo e(old('phone_number', $student->alumniProfile->phone_number ?? $student->phone)); ?>" 
                                           class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white font-bold text-elevate-dark focus:border-elevate-primary focus:ring-elevate-primary transition-all py-3 px-4 text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Email</label>
                                    <input type="email" name="email" value="<?php echo e(old('email', $student->alumniProfile->email ?? '')); ?>" 
                                           class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white font-bold text-elevate-dark focus:border-elevate-primary focus:ring-elevate-primary transition-all py-3 px-4 text-sm">
                                </div>
                            </div>
                        </div>

                        
                        <div>
                            <h4 class="font-black text-elevate-dark mb-4 flex items-center gap-2 text-sm uppercase tracking-wider">
                                <i class="ph-fill ph-briefcase text-elevate-primary"></i> Aktivitas Saat Ini
                            </h4>
                            
                            
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 mb-6 bg-slate-50 p-2 rounded-2xl border border-slate-100">
                                <?php $__currentLoopData = ['SMA', 'SMK', 'MA', 'Pesantren', 'Bekerja', 'Lainnya']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <label class="cursor-pointer h-full">
                                    <input type="radio" name="activity_status" value="<?php echo e($opt); ?>" x-model="status" class="peer sr-only">
                                    <div class="px-2 py-3 rounded-xl border border-transparent text-center font-bold text-slate-500 peer-checked:bg-white peer-checked:text-elevate-primary peer-checked:border-slate-200 peer-checked:shadow-sm transition-all text-xs hover:text-elevate-dark h-full flex items-center justify-center">
                                        <?php echo e($opt); ?>

                                    </div>
                                </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                            
                            <div x-show="['SMA', 'SMK', 'MA', 'Pesantren'].includes(status)" 
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 -translate-y-2"
                                 class="bg-elevate-accent/5 p-6 rounded-[2rem] border border-elevate-accent/20" x-cloak>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div class="col-span-2">
                                        <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Nama Sekolah / Pesantren</label>
                                        <input type="text" name="campus_name" value="<?php echo e(old('campus_name', $student->alumniProfile->campus_name ?? '')); ?>" 
                                               class="w-full rounded-2xl border-slate-200 bg-white font-bold text-elevate-dark focus:border-elevate-primary focus:ring-elevate-primary transition-all py-3 px-4 text-sm shadow-sm" placeholder="Nama Sekolah Tujuan">
                                    </div>
                                    <div x-show="status !== 'Pesantren'">
                                        <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Jurusan / Peminatan</label>
                                        <input type="text" name="campus_major" value="<?php echo e(old('campus_major', $student->alumniProfile->campus_major ?? '')); ?>" 
                                               class="w-full rounded-2xl border-slate-200 bg-white font-bold text-elevate-dark focus:border-elevate-primary focus:ring-elevate-primary transition-all py-3 px-4 text-sm shadow-sm" placeholder="IPA/IPS/TKJ/dll">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Tahun Masuk</label>
                                        <input type="number" name="campus_entry_year" value="<?php echo e(old('campus_entry_year', $student->alumniProfile->campus_entry_year ?? date('Y'))); ?>" 
                                               class="w-full rounded-2xl border-slate-200 bg-white font-bold text-elevate-dark focus:border-elevate-primary focus:ring-elevate-primary transition-all py-3 px-4 text-sm shadow-sm">
                                    </div>
                                </div>
                            </div>

                            
                            <div x-show="['Bekerja', 'Lainnya'].includes(status)" 
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 -translate-y-2"
                                 class="bg-slate-50/50 p-6 rounded-[2rem] border border-slate-200" x-cloak>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <template x-if="status === 'Bekerja'">
                                        <div class="col-span-2 md:col-span-1">
                                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Nama Tempat Kerja</label>
                                            <input type="text" name="company_name" value="<?php echo e(old('company_name', $student->alumniProfile->company_name ?? '')); ?>" 
                                                   class="w-full rounded-2xl border-slate-200 bg-white font-bold text-elevate-dark focus:border-elevate-primary focus:ring-elevate-primary transition-all py-3 px-4 text-sm shadow-sm" placeholder="PT... / CV... / Toko...">
                                        </div>
                                    </template>

                                    <div class="col-span-2" :class="status === 'Bekerja' ? 'md:col-span-1' : ''">
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">
                                            <span x-text="status === 'Bekerja' ? 'Posisi / Jabatan' : 'Keterangan Kegiatan / Kesibukan'"></span>
                                        </label>
                                        <input type="text" name="position" value="<?php echo e(old('position', $student->alumniProfile->position ?? '')); ?>" 
                                               class="w-full rounded-2xl border-slate-200 bg-white font-bold text-elevate-dark focus:border-elevate-primary focus:ring-elevate-primary transition-all py-3 px-4 text-sm shadow-sm" 
                                               x-bind:placeholder="status === 'Bekerja' ? 'Staff / Admin / Kasir' : 'Gap Year / Membantu Orang Tua'">
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        <div>
                            <h4 class="font-black text-elevate-dark mb-4 flex items-center gap-2 text-sm uppercase tracking-wider">
                                <i class="ph-fill ph-chat-centered-text text-elevate-primary"></i> Catatan Testimoni
                            </h4>
                            <textarea name="testimony" rows="3" placeholder="Pesan dan kesan dari alumni..."
                                      class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white font-medium text-elevate-dark focus:border-elevate-primary focus:ring-elevate-primary transition-all p-4 text-sm"><?php echo e(old('testimony', $student->alumniProfile->testimony ?? '')); ?></textarea>
                            
                            
                            <input type="hidden" name="rating" value="<?php echo e(old('rating', $student->alumniProfile->rating ?? 5)); ?>">
                        </div>

                    </div>

                    <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100 flex justify-end gap-3">
                        <button type="submit" class="px-8 py-3.5 bg-elevate-dark text-white font-bold rounded-2xl shadow-lg shadow-elevate-dark/20 hover:bg-elevate-primary transition-all transform active:scale-95 flex items-center gap-2 group/btn">
                            <i class="ph-bold ph-floppy-disk text-lg group-hover/btn:scale-110 transition-transform"></i>
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>
                </form>
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
<?php endif; ?><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/admin/alumni/edit.blade.php ENDPATH**/ ?>