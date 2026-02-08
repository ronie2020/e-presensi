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
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            <?php echo e(__('Monitoring Live')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    
    <div class="py-8 sm:py-10 font-sans text-slate-800" 
         x-data="{ 
            // LOGIKA LAMA (Refresh Halaman)
            search: '', 
            count: 30,
            isPaused: false,
            
            // LOGIKA BARU (Token Otomatis)
            currentToken: '<?php echo e($exam->token); ?>',
            autoRotate: false,
            intervalMinutes: 15,
            isLoading: false,
            tokenTimeLeft: 0,
            tokenProgress: 100,
            tokenTimer: null,
            
            init() {
                // 1. Jalankan Timer Refresh Halaman (Logika Lama)
                setInterval(() => {
                    if (this.search === '') {
                        if (this.count > 0) this.count--; else location.reload();
                        this.isPaused = false;
                    } else {
                        this.isPaused = true; // Pause refresh jika sedang mengetik search
                        this.count = 30;
                    }
                }, 1000);

                // 2. Jalankan Timer Token (Logika Baru)
                // Cek LocalStorage agar timer tidak reset saat halaman reload
                this.loadTokenState();
                
                this.$watch('autoRotate', value => {
                    if (value) this.startTokenTimer();
                    else this.stopTokenTimer();
                    this.saveTokenState();
                });
                
                this.$watch('intervalMinutes', () => {
                    if (this.autoRotate) this.startTokenTimer(); 
                    this.saveTokenState();
                });
            },

            // --- FUNGSI TOKEN ---
            loadTokenState() {
                const savedState = JSON.parse(localStorage.getItem('token_monitor_<?php echo e($exam->id); ?>'));
                if (savedState) {
                    this.autoRotate = savedState.autoRotate;
                    this.intervalMinutes = savedState.intervalMinutes;
                    
                    if (this.autoRotate) {
                        // Hitung sisa waktu berdasarkan timestamp target
                        const now = Math.floor(Date.now() / 1000);
                        if (savedState.targetTime > now) {
                            this.tokenTimeLeft = savedState.targetTime - now;
                            this.startTokenTimer(false); // Resume, jangan reset
                        } else {
                            this.startTokenTimer(); // Reset baru
                        }
                    }
                }
            },

            saveTokenState() {
                const now = Math.floor(Date.now() / 1000);
                const targetTime = now + this.tokenTimeLeft;
                localStorage.setItem('token_monitor_<?php echo e($exam->id); ?>', JSON.stringify({
                    autoRotate: this.autoRotate,
                    intervalMinutes: this.intervalMinutes,
                    targetTime: targetTime
                }));
            },

            startTokenTimer(reset = true) {
                this.stopTokenTimer();
                let totalSeconds = this.intervalMinutes * 60;
                
                if (reset) {
                    this.tokenTimeLeft = totalSeconds;
                }
                
                this.tokenTimer = setInterval(() => {
                    this.tokenTimeLeft--;
                    this.tokenProgress = (this.tokenTimeLeft / totalSeconds) * 100;
                    this.saveTokenState(); // Simpan state tiap detik agar sinkron saat reload

                    if (this.tokenTimeLeft <= 0) {
                        this.rotateTokenNow();
                        this.tokenTimeLeft = totalSeconds; 
                    }
                }, 1000);
            },

            stopTokenTimer() {
                if (this.tokenTimer) clearInterval(this.tokenTimer);
                this.tokenProgress = 100;
                localStorage.removeItem('token_monitor_<?php echo e($exam->id); ?>');
            },

            rotateTokenNow() {
                this.isLoading = true;
                fetch('<?php echo e(route("cbt.auto_token", $exam->id)); ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' }
                })
                .then(r => r.json())
                .then(d => {
                    if (d.status === 'success') this.currentToken = d.token;
                })
                .finally(() => this.isLoading = false);
            }
         }">
         
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                
                <div class="md:col-span-2 relative rounded-[2rem] bg-gray-900 bg-gradient-to-br from-slate-900 via-blue-900 to-blue-800 p-8 text-white shadow-xl shadow-blue-900/30 overflow-hidden border border-white/10">
                    <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                    <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
                    
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-2">
                            <?php if($exam->is_active): ?>
                                <span class="bg-emerald-500/20 border border-emerald-500/50 text-emerald-300 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider animate-pulse flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></span> Live Active
                                </span>
                            <?php else: ?>
                                <span class="bg-white/10 border border-white/10 text-slate-300 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider">Non-Aktif</span>
                            <?php endif; ?>
                            <span class="text-blue-300 text-xs font-bold uppercase tracking-wider">Kelas <?php echo e($exam->class_level); ?></span>
                        </div>
                        <h1 class="text-3xl font-black tracking-tight leading-none mb-4"><?php echo e($exam->title); ?></h1>
                        
                        
                        <div class="flex gap-3">
                            <div class="bg-white/10 backdrop-blur-md px-5 py-3 rounded-2xl border border-white/10 text-center min-w-[90px]">
                                <h4 class="text-2xl font-black text-white leading-none"><?php echo e($stats['working']); ?></h4>
                                <p class="text-[9px] uppercase font-bold text-blue-300 mt-1">Proses</p>
                            </div>
                            <div class="bg-emerald-500/20 backdrop-blur-md px-5 py-3 rounded-2xl border border-emerald-500/30 text-center min-w-[90px]">
                                <h4 class="text-2xl font-black text-emerald-300 leading-none"><?php echo e($stats['finished']); ?></h4>
                                <p class="text-[9px] uppercase font-bold text-emerald-200 mt-1">Selesai</p>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="md:col-span-1 bg-white p-6 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 flex flex-col justify-between relative overflow-hidden">
                    <div>
                        <div class="flex justify-between items-start mb-2">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Token Akses</p>
                            
                            <div class="flex items-center gap-2">
                                <span x-show="autoRotate" class="animate-pulse w-2 h-2 bg-emerald-500 rounded-full"></span>
                                <span x-text="autoRotate ? 'Auto: ' + intervalMinutes + 'm' : 'Manual'" class="text-[10px] font-bold text-slate-500"></span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 mb-4">
                            <h2 class="text-5xl font-mono font-black tracking-widest text-slate-800" x-text="currentToken">
                                <?php echo e($exam->token ?? '-----'); ?>

                            </h2>
                            <button @click="rotateTokenNow()" :disabled="isLoading" class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 transition disabled:opacity-50">
                                <i class="ph-bold ph-arrows-clockwise text-xl" :class="isLoading ? 'animate-spin' : ''"></i>
                            </button>
                        </div>
                        
                        
                        <div x-show="autoRotate" class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden mb-4">
                            <div class="bg-emerald-500 h-full transition-all duration-1000 ease-linear" :style="'width: ' + tokenProgress + '%'"></div>
                        </div>
                    </div>

                    
                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" x-model="autoRotate" class="rounded text-blue-600 focus:ring-blue-500 border-gray-300">
                            <span class="text-xs font-bold text-slate-600">Auto Ganti</span>
                        </label>
                        <select x-model="intervalMinutes" :disabled="!autoRotate" class="text-xs font-bold text-slate-700 bg-transparent border-none focus:ring-0 p-0 cursor-pointer text-right disabled:text-slate-400">
                            <option value="5">5 Menit</option>
                            <option value="10">10 Menit</option>
                            <option value="15">15 Menit</option>
                        </select>
                    </div>
                </div>
            </div>

            
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <a href="<?php echo e(route('cbt.index')); ?>" class="group inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-blue-600 transition">
                    <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali ke List
                </a>

                <div class="text-[10px] font-bold px-3 py-1.5 rounded-full border shadow-sm flex items-center gap-2 transition-colors duration-300"
                     :class="isPaused ? 'bg-amber-50 text-amber-600 border-amber-200' : 'bg-white text-slate-400 border-slate-200'">
                    <template x-if="!isPaused">
                        <div class="flex items-center gap-1.5">
                            <i class="ph-bold ph-arrows-clockwise animate-spin text-blue-500"></i>
                            <span>Update Status Peserta: <span x-text="count" class="font-mono text-slate-700"></span>s</span>
                        </div>
                    </template>
                    <template x-if="isPaused">
                        <div class="flex items-center gap-1.5">
                            <i class="ph-fill ph-pause-circle text-amber-500"></i>
                            <span>Paused (Sedang mencari...)</span>
                        </div>
                    </template>
                </div>
            </div>

            <!-- TABEL PESERTA (LOGIKA LAMA - TIDAK BERUBAH) -->
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden min-h-[500px]">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row justify-between items-center gap-4">
                    <h4 class="font-bold text-slate-700 flex items-center gap-2 text-lg">
                        <i class="ph-fill ph-users-three text-blue-500"></i> Peserta Ujian <span class="bg-slate-200 text-slate-600 text-xs px-2 py-0.5 rounded-full"><?php echo e($stats['total_students']); ?></span>
                    </h4>
                    <div class="relative w-full md:w-72">
                        <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" x-model="search" placeholder="Cari siswa..." class="w-full pl-10 pr-4 py-2.5 text-sm font-bold border-slate-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 bg-white shadow-sm">
                    </div>
                </div>
                
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4 w-16 text-center">No</th>
                                <th class="px-6 py-4">Nama Siswa</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-center">Mulai</th>
                                <th class="px-6 py-4 text-center">Skor</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php $__empty_1 = true; $__currentLoopData = $monitoringData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr x-show="search === '' || '<?php echo e(strtolower($student->name)); ?>'.includes(search.toLowerCase())" 
                                class="hover:bg-blue-50/20 transition group <?php echo e($student->is_active ? 'bg-blue-50/10' : ''); ?>">
                                <td class="px-6 py-4 text-center font-bold text-slate-400"><?php echo e($index + 1); ?></td>
                                <td class="px-6 py-4 font-bold text-slate-800"><?php echo e($student->name); ?></td>
                                <td class="px-6 py-4 text-center">
                                    <?php if($student->status == 'Sedang Mengerjakan'): ?>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[10px] font-black bg-blue-100 text-blue-700 border border-blue-200 uppercase tracking-wide">
                                            <span class="block w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></span> Proses
                                        </span>
                                    <?php elseif($student->status == 'Selesai'): ?>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[10px] font-black bg-emerald-100 text-emerald-700 border border-emerald-200 uppercase tracking-wide">
                                            <i class="ph-bold ph-check"></i> Selesai
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-[10px] font-black bg-slate-100 text-slate-400 border border-slate-200 uppercase tracking-wide">
                                            Belum
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-center font-mono text-xs font-medium"><?php echo e($student->start_time ?? '-'); ?></td>
                                <td class="px-6 py-4 text-center">
                                    <span class="font-black text-slate-800 text-lg <?php echo e($student->score > 0 ? 'text-blue-600' : 'text-slate-300'); ?>">
                                        <?php echo e($student->score > 0 ? $student->score : '0'); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <?php if($student->is_active): ?>
                                        <form action="<?php echo e(route('cbt.reset', ['exam' => $exam->id, 'student' => $student->id])); ?>" method="POST" onsubmit="return confirm('Peringatan: Reset akan memaksa siswa logout. Lanjutkan?')">
                                            <?php echo csrf_field(); ?>
                                            <button class="text-xs font-bold text-rose-500 hover:text-white hover:bg-rose-500 border border-rose-200 bg-rose-50 px-3 py-1.5 rounded-lg transition flex items-center gap-1 ml-auto" title="Reset Login">
                                                <i class="ph-bold ph-power"></i> Reset
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="6" class="px-6 py-12 text-center text-slate-400">Belum ada siswa yang login.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- MOBILE LIST -->
                <div class="md:hidden p-4 space-y-3 bg-slate-50/50">
                    <?php $__empty_1 = true; $__currentLoopData = $monitoringData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div x-show="search === '' || '<?php echo e(strtolower($student->name)); ?>'.includes(search.toLowerCase())" 
                         class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden">
                        
                        <div class="absolute left-0 top-0 bottom-0 w-1.5 <?php echo e($student->status == 'Sedang Mengerjakan' ? 'bg-blue-500' : ($student->status == 'Selesai' ? 'bg-emerald-500' : 'bg-slate-200')); ?>"></div>
                        
                        <div class="pl-3 flex justify-between items-start">
                            <div>
                                <h5 class="font-bold text-slate-800 text-sm mb-1"><?php echo e($student->name); ?></h5>
                                <div class="flex items-center gap-2">
                                    <?php if($student->status == 'Sedang Mengerjakan'): ?>
                                        <span class="text-[10px] font-bold bg-blue-50 text-blue-700 px-2 py-0.5 rounded border border-blue-100">Sedang Ujian</span>
                                    <?php elseif($student->status == 'Selesai'): ?>
                                        <span class="text-[10px] font-bold bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded border border-emerald-100">Selesai</span>
                                    <?php else: ?>
                                        <span class="text-[10px] font-bold text-slate-400">Belum Login</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="text-right">
                                <span class="block text-[10px] text-slate-400 font-bold uppercase mb-0.5">Skor</span>
                                <span class="block text-xl font-black text-slate-800 leading-none"><?php echo e($student->score > 0 ? $student->score : '0'); ?></span>
                            </div>
                        </div>
                        
                        <?php if($student->is_active): ?>
                        <div class="mt-3 pt-3 border-t border-slate-50 text-right">
                             <form action="<?php echo e(route('cbt.reset', ['exam' => $exam->id, 'student' => $student->id])); ?>" method="POST" onsubmit="return confirm('Reset login siswa ini?')">
                                <?php echo csrf_field(); ?>
                                <button class="text-xs font-bold text-rose-600 flex items-center gap-1 ml-auto">
                                    <i class="ph-bold ph-power"></i> Reset Login
                                </button>
                            </form>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center py-8 text-slate-400 italic text-sm">Belum ada peserta.</div>
                    <?php endif; ?>
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
<?php endif; ?><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/cbt/monitoring.blade.php ENDPATH**/ ?>