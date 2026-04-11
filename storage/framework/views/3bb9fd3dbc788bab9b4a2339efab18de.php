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

    <div class="py-10 font-sans text-slate-800" 
         x-data="teachingEdit({ 
            sessionId: <?php echo e($session->id); ?>

         })">
         
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <div class="flex items-center justify-between mb-8">
                <div>
                    <a href="<?php echo e(route('teaching.show', $session->id)); ?>" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-amber-600 transition mb-2">
                        <i class="ph-bold ph-arrow-left"></i> Batal & Kembali
                    </a>
                    <h1 class="text-3xl font-black text-slate-800">Edit Sesi Kelas</h1>
                    <p class="text-slate-500 font-medium">Revisi jurnal dan absensi siswa untuk sesi yang sudah ditutup.</p>
                </div>
                <div class="px-4 py-2 bg-amber-100 text-amber-700 rounded-xl border border-amber-200 font-bold text-sm flex items-center gap-2 animate-pulse">
                    <i class="ph-fill ph-pencil-simple"></i> Mode Edit Admin
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 p-6">
                        <h3 class="font-bold text-slate-800 text-lg mb-4 flex items-center gap-2">
                            <i class="ph-fill ph-notebook text-amber-500"></i> Jurnal Mengajar
                        </h3>
                        
                        
                        <form action="<?php echo e(route('teaching.update', $session->id)); ?>" method="POST" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                            <input type="hidden" name="redirect_to" value="edit"> 
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Topik / Materi</label>
                                    <input type="text" name="topic" value="<?php echo e(old('topic', $session->topic)); ?>" 
                                        class="w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500 font-bold text-slate-700 bg-slate-50" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Catatan Kegiatan</label>
                                    <textarea name="activities" rows="4" 
                                        class="w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500 text-sm text-slate-600 bg-slate-50"><?php echo e(old('activities', $session->activities)); ?></textarea>
                                </div>
                                
                                
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Update Foto (Opsional)</label>
                                    <?php if($session->photo_proof): ?>
                                        <div class="mb-2">
                                            <img src="<?php echo e(asset('storage/' . $session->photo_proof)); ?>" class="h-20 rounded-lg object-cover border border-slate-200">
                                            <p class="text-[10px] text-slate-400 mt-1">Foto saat ini</p>
                                        </div>
                                    <?php endif; ?>
                                    <input type="file" name="photo_proof" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                                </div>

                                <button type="submit" class="w-full bg-slate-800 text-white hover:bg-slate-700 font-bold py-3 rounded-xl shadow-lg transition flex items-center justify-center gap-2">
                                    <i class="ph-bold ph-floppy-disk"></i> Simpan Perubahan Jurnal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 flex flex-col h-full overflow-hidden">
                        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                            <div>
                                <h3 class="font-bold text-slate-800 text-lg">Koreksi Absensi</h3>
                                <p class="text-xs text-slate-500">Klik tombol status untuk mengubah kehadiran.</p>
                            </div>
                            
                            
                            <div class="flex gap-2">
                                <span class="px-3 py-1 rounded-lg bg-rose-50 text-rose-600 text-xs font-bold border border-rose-100">
                                    Alpha: <?php echo e(collect($attendances)->where('status', 'alpha')->count()); ?>

                                </span>
                            </div>
                        </div>

                        <div class="flex-1 p-6 overflow-y-auto max-h-[800px] custom-scrollbar">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <?php $__currentLoopData = $allStudents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $att = $attendances[$student->id] ?? null;
                                        $status = $att ? $att->status : null; 
                                    ?>

                                    <div class="flex items-center justify-between p-3 rounded-xl border border-slate-100 hover:border-amber-200 transition-colors bg-white group"
                                         id="row-<?php echo e($student->id); ?>"
                                         x-data="{ currentStatus: '<?php echo e($status); ?>' }">
                                        
                                        <div class="flex items-center gap-3 overflow-hidden">
                                            <div class="w-10 h-10 rounded-lg flex items-center justify-center font-bold text-sm shrink-0 transition-colors"
                                                 :class="{
                                                    'bg-emerald-100 text-emerald-600': currentStatus === 'present',
                                                    'bg-rose-100 text-rose-600': currentStatus === 'alpha',
                                                    'bg-blue-100 text-blue-600': currentStatus === 'sick',
                                                    'bg-amber-100 text-amber-600': currentStatus === 'permission',
                                                    'bg-slate-100 text-slate-400': !currentStatus
                                                 }">
                                                <i class="ph-fill" :class="{
                                                    'ph-check-circle': currentStatus === 'present',
                                                    'ph-x-circle': currentStatus === 'alpha',
                                                    'ph-thermometer': currentStatus === 'sick',
                                                    'ph-hand-waving': currentStatus === 'permission',
                                                    'ph-question': !currentStatus
                                                }"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="font-bold text-slate-700 text-sm truncate"><?php echo e($student->name); ?></p>
                                                <p class="text-[10px] text-slate-400"><?php echo e($student->student_id); ?></p>
                                            </div>
                                        </div>

                                        
                                        <div class="relative shrink-0" x-data="{ open: false }">
                                            <button @click="open = !open" @click.outside="open = false" 
                                                class="px-3 py-1.5 rounded-lg text-xs font-bold border flex items-center gap-2 transition-all shadow-sm"
                                                :class="{
                                                    'bg-white border-slate-200 text-slate-600 hover:bg-slate-50': true,
                                                    'ring-2 ring-rose-100 border-rose-300 text-rose-600': currentStatus === 'alpha'
                                                }">
                                                <span x-text="currentStatus ? currentStatus.toUpperCase() : 'PILIH'"></span>
                                                <i class="ph-bold ph-caret-down"></i>
                                            </button>

                                            <div x-show="open" x-transition class="absolute right-0 mt-1 w-40 bg-white rounded-xl shadow-xl border border-slate-100 z-50 py-1">
                                                <button @click="updateStatus(<?php echo e($student->id); ?>, 'present'); currentStatus='present'; open=false" class="w-full text-left px-4 py-2 text-xs font-bold text-emerald-600 hover:bg-emerald-50">Hadir</button>
                                                <button @click="updateStatus(<?php echo e($student->id); ?>, 'sick'); currentStatus='sick'; open=false" class="w-full text-left px-4 py-2 text-xs font-bold text-blue-600 hover:bg-blue-50">Sakit</button>
                                                <button @click="updateStatus(<?php echo e($student->id); ?>, 'permission'); currentStatus='permission'; open=false" class="w-full text-left px-4 py-2 text-xs font-bold text-amber-600 hover:bg-amber-50">Izin</button>
                                                <button @click="updateStatus(<?php echo e($student->id); ?>, 'alpha'); currentStatus='alpha'; open=false" class="w-full text-left px-4 py-2 text-xs font-bold text-rose-600 hover:bg-rose-50">Alpha</button>
                                                <div class="border-t border-slate-100 my-1"></div>
                                                <button @click="updateStatus(<?php echo e($student->id); ?>, null); currentStatus=null; open=false" class="w-full text-left px-4 py-2 text-xs text-slate-400 hover:bg-slate-50">Reset</button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('teachingEdit', (config) => ({
                sessionId: config.sessionId,

                async updateStatus(studentId, status) {
                    try {
                        const response = await fetch('<?php echo e(route("teaching.manual")); ?>', {
                            method: 'POST',
                            headers: { 
                                'Content-Type': 'application/json', 
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') 
                            },
                            body: JSON.stringify({ session_id: this.sessionId, student_id: studentId, status: status })
                        });
                        const data = await response.json();
                        
                        if(data.status === 'success') {
                            const Toast = Swal.mixin({
                                toast: true, position: 'top-end', showConfirmButton: false, timer: 1000,
                                didOpen: (toast) => { toast.addEventListener('mouseenter', Swal.stopTimer); toast.addEventListener('mouseleave', Swal.resumeTimer); }
                            })
                            Toast.fire({ icon: 'success', title: 'Status diperbarui' });
                        }
                    } catch (e) { 
                        Swal.fire('Error', 'Gagal update status', 'error'); 
                    }
                }
            }));
        });
    </script>
    <?php $__env->stopPush(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\teaching\edit.blade.php ENDPATH**/ ?>