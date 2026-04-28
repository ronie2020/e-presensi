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
        <h2 class="font-semibold text-xl text-[#2c3f61] leading-tight">
            <?php echo e(__('Cetak Kartu Peserta')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-8 sm:py-12 font-sans text-[#2c3f61]">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-[#56bbf1]/10 border border-slate-100 overflow-hidden">
                
                
                <div class="p-8 md:p-10 text-center relative overflow-hidden bg-gradient-to-r from-[#56bbf1] via-[#e5eff5] to-[#f4d1c0] border-b border-white/60">
                    
                    <div class="absolute -top-10 -left-10 w-48 h-48 bg-[#0d52a1]/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-3xl"></div>
                    <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-[#f9a282]/20 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-2xl"></div>
                    
                    <div class="relative z-10">
                        <div class="w-20 h-20 bg-white/60 backdrop-blur-md rounded-[1.5rem] flex items-center justify-center mx-auto mb-5 border border-white text-[#0d52a1] text-4xl shadow-xl shadow-[#56bbf1]/20">
                            
                            <i class="ph-duotone ph-address-book"></i>
                        </div>
                        <h3 class="text-2xl md:text-3xl font-black text-[#2c3f61] mb-2 tracking-tight">Cetak Kartu Ujian</h3>
                        <p class="text-[#2c3f61]/70 text-sm max-w-md mx-auto font-medium">Pilih tingkat angkatan atau kelas spesifik untuk mencetak kartu login peserta ujian yang berisi QR Code.</p>
                    </div>
                </div>

                <div class="p-8 md:p-10 bg-white" x-data="{ mode: 'level' }">
                    <form action="<?php echo e(route('cbt.cards.print')); ?>" method="GET" target="_blank">
                        <div class="mb-8">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 ml-1">Pilih Mode Cetak</label>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                
                                <div class="group cursor-pointer transition-all duration-300 rounded-[1.5rem] border-2 relative overflow-hidden"
                                     :class="mode === 'level' ? 'border-[#56bbf1] bg-[#e5eff5]/50' : 'border-slate-200 bg-white hover:border-[#56bbf1]/30 hover:bg-slate-50'"
                                     @click="mode = 'level'">
                                    
                                    
                                    <input type="radio" name="mode" value="level" x-model="mode" class="hidden">
                                    
                                    <div class="p-5">
                                        <div class="flex items-center gap-4 mb-2">
                                            <div class="w-12 h-12 rounded-[1rem] flex items-center justify-center text-2xl shrink-0 transition-colors shadow-sm"
                                                 :class="mode === 'level' ? 'bg-[#56bbf1] text-white border border-[#56bbf1]' : 'bg-slate-50 border border-slate-200 text-slate-400'">
                                                
                                                <i class="ph-fill ph-stack"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-[#2c3f61] text-sm mb-0.5">Cetak Per Tingkat</h4>
                                                <p class="text-xs text-slate-500 font-medium">Pilih angkatan kelas.</p>
                                            </div>
                                            
                                            
                                            <div class="absolute top-5 right-5 w-5 h-5 rounded-full border-2 flex items-center justify-center transition-all"
                                                 :class="mode === 'level' ? 'border-[#0d52a1] bg-[#0d52a1] text-white' : 'border-slate-300 text-transparent'">
                                                <i class="ph-bold ph-check text-xs"></i>
                                            </div>
                                        </div>
                                    </div>

                                    
                                    <div class="px-5 pb-5" x-show="mode === 'level'" x-transition @click.stop>
                                        <div class="relative mt-2">
                                            <select name="level" class="w-full rounded-xl border-slate-200 bg-white font-bold text-[#2c3f61] py-3.5 pl-4 pr-10 appearance-none cursor-pointer focus:ring-[#56bbf1] focus:border-[#56bbf1] transition-shadow shadow-sm" :disabled="mode !== 'level'">
                                                <option value="all">Semua Tingkat (Seluruh Siswa)</option>
                                                <option value="7">Kelas 7</option>
                                                <option value="8">Kelas 8</option>
                                                <option value="9">Kelas 9</option>
                                            </select>
                                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400">
                                                <i class="ph-bold ph-caret-down text-lg"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                
                                <div class="group cursor-pointer transition-all duration-300 rounded-[1.5rem] border-2 relative overflow-hidden"
                                     :class="mode === 'class' ? 'border-[#56bbf1] bg-[#e5eff5]/50' : 'border-slate-200 bg-white hover:border-[#56bbf1]/30 hover:bg-slate-50'"
                                     @click="mode = 'class'">
                                    
                                    
                                    <input type="radio" name="mode" value="class" x-model="mode" class="hidden">
                                    
                                    <div class="p-5">
                                        <div class="flex items-center gap-4 mb-2">
                                            <div class="w-12 h-12 rounded-[1rem] flex items-center justify-center text-2xl shrink-0 transition-colors shadow-sm"
                                                 :class="mode === 'class' ? 'bg-[#56bbf1] text-white border border-[#56bbf1]' : 'bg-slate-50 border border-slate-200 text-slate-400'">
                                                
                                                <i class="ph-fill ph-users-three"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-[#2c3f61] text-sm mb-0.5">Cetak Per Kelas</h4>
                                                <p class="text-xs text-slate-500 font-medium">Pilih kelas spesifik.</p>
                                            </div>
                                            
                                            
                                            <div class="absolute top-5 right-5 w-5 h-5 rounded-full border-2 flex items-center justify-center transition-all"
                                                 :class="mode === 'class' ? 'border-[#0d52a1] bg-[#0d52a1] text-white' : 'border-slate-300 text-transparent'">
                                                <i class="ph-bold ph-check text-xs"></i>
                                            </div>
                                        </div>
                                    </div>

                                    
                                    <div class="px-5 pb-5" x-show="mode === 'class'" x-transition @click.stop>
                                        <div class="relative mt-2">
                                            <select name="class_id" class="w-full rounded-xl border-slate-200 bg-white font-bold text-[#2c3f61] py-3.5 pl-4 pr-10 appearance-none cursor-pointer focus:ring-[#56bbf1] focus:border-[#56bbf1] transition-shadow shadow-sm" :disabled="mode !== 'class'">
                                                <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($c->id); ?>"><?php echo e($c->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400">
                                                <i class="ph-bold ph-caret-down text-lg"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>

                        
                        <div class="pt-6 border-t border-slate-100 flex justify-end">
                            <button type="submit" class="w-full sm:w-auto px-8 py-4 bg-[#2c3f61] hover:bg-[#1c2940] text-white rounded-2xl font-bold shadow-xl shadow-[#2c3f61]/20 transition-all flex items-center justify-center gap-3 transform active:scale-95 border border-transparent">
                                <i class="ph-bold ph-printer text-xl text-[#56bbf1]"></i> 
                                <span>Generate Kartu (PDF)</span>
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/cbt/cards/index.blade.php ENDPATH**/ ?>