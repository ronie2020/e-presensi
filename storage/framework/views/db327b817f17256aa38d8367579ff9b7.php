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
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    
    <?php $__env->startPush('styles'); ?>
    <style>
        /* Animasi Scanner */
        .scanner-overlay {
            position: absolute; top: 0; left: 0; right: 0; bottom: 0;
            border: 2px solid rgba(59, 130, 246, 0.5); border-radius: 1.5rem; 
            pointer-events: none; overflow: hidden; transition: box-shadow 0.3s ease;
        }
        .scan-success-effect { box-shadow: inset 0 0 50px rgba(34, 197, 94, 0.8); border-color: #22c55e; }
        .scan-warning-effect { box-shadow: inset 0 0 50px rgba(245, 158, 11, 0.8); border-color: #f59e0b; }
        .scan-error-effect { box-shadow: inset 0 0 50px rgba(239, 68, 68, 0.8); border-color: #ef4444; }
        /* Efek khusus makan siang */
        .scan-makan-effect { box-shadow: inset 0 0 50px rgba(249, 115, 22, 0.8); border-color: #f97316; }

        .scanner-line {
            position: absolute; width: 100%; height: 4px;
            background: linear-gradient(to right, transparent, #3b82f6, transparent);
            top: 0; animation: scanMove 2s infinite linear;
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.8);
        }
        @keyframes scanMove { 0% { top: 0; opacity: 0; } 50% { opacity: 1; } 100% { top: 100%; opacity: 0; } }
        
        /* Utility */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; } 
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        
        .hidden-col { display: none !important; }
        .hidden-row { display: none !important; }
        @keyframes highlightRow { 0% { background-color: #ffedd5; } 100% { background-color: transparent; } } /* Orange highlight */
        .new-row-entry { animation: highlightRow 2s ease-out; }
        .holiday-overlay { position: absolute; inset: 0; z-index: 50; background: rgba(255, 255, 255, 0.95); display: flex; flex-direction: column; align-items: center; justify-content: center; border-radius: 1.5rem; }
    </style>
    <?php $__env->stopPush(); ?>

    <?php
        // Ambil data dari Controller
        $safeSchedule = isset($scheduleConfig) ? $scheduleConfig : [];
        $scheduleJson = json_encode($safeSchedule);
        
        $totalTarget = $statsConfig['total_target'] ?? 0;
        $currentTaken = $statsConfig['current_taken'] ?? 0;
    ?>

    
    <div class="py-4 md:py-8 font-sans text-slate-800" onclick="initAudio()"> 
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <div class="relative rounded-[1.5rem] md:rounded-[2rem] bg-gray-900 bg-gradient-to-br from-blue-900 via-blue-800 to-slate-900 p-5 md:p-8 mb-6 md:mb-8 text-white shadow-xl shadow-blue-900/30 overflow-hidden border border-white/10">
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-4 md:gap-6">
                    <div class="text-center md:text-left">
                        <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight mb-2 flex items-center justify-center md:justify-start gap-2">
                            Scan QR Aktifitas <span class="animate-pulse"></span>
                        </h2>
                        <div class="flex flex-wrap justify-center md:justify-start gap-2 items-center text-blue-300 text-xs md:text-sm font-medium">
                            <?php if(isset($scheduleConfig) && ($scheduleConfig['is_holiday'] ?? false)): ?>
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-rose-500/20 text-rose-200 border border-rose-500/30">Libur: <?php echo e($scheduleConfig['description']); ?></span>
                            <?php else: ?>
                                <span class="opacity-90">Sistem pencatatan kehadiran, ibadah & gizi siswa.</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="bg-slate-900/40 backdrop-blur-md border border-white/10 px-4 py-3 md:px-6 md:py-4 rounded-2xl flex items-center gap-3 shadow-lg">
                        <div class="p-2 md:p-3 bg-blue-600 rounded-xl text-white shadow-lg"><i class="ph-bold ph-clock text-lg md:text-2xl"></i></div>
                        <div>
                            <p class="text-[9px] md:text-[10px] font-bold text-blue-300 uppercase tracking-widest">Waktu Server</p>
                            <div id="clock" class="text-2xl md:text-3xl font-black text-white font-mono tracking-widest leading-none mt-1">00:00:00</div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div id="makan-stats-panel" class="hidden grid-cols-1 md:grid-cols-3 gap-4 mb-8 animate-enter">
                <div class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-4 relative overflow-hidden group">
                    <div class="w-14 h-14 rounded-2xl bg-slate-50 text-slate-500 flex items-center justify-center text-3xl shrink-0 group-hover:scale-110 transition-transform">
                        <i class="ph-duotone ph-users-three"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Sasaran</p>
                        <h3 class="text-3xl font-black text-slate-800 tracking-tight" id="stat-total"><?php echo e($totalTarget); ?></h3>
                    </div>
                </div>

                <div class="bg-orange-500 p-5 rounded-[2rem] border border-orange-400 shadow-lg shadow-orange-500/20 flex items-center gap-4 relative overflow-hidden text-white group">
                    <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:scale-110 transition-transform"><i class="ph-fill ph-check-circle text-6xl"></i></div>
                    <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center text-3xl shrink-0 border border-white/20">
                        <i class="ph-fill ph-hand-grabbing"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-orange-100 uppercase tracking-widest mb-1">Sudah Mengambil</p>
                        <h3 class="text-3xl font-black text-white tracking-tight" id="stat-taken"><?php echo e($currentTaken); ?></h3>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-4 relative overflow-hidden group">
                    <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center text-3xl shrink-0 group-hover:scale-110 transition-transform">
                        <i class="ph-duotone ph-cookie"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Sisa Belum Ambil</p>
                        <h3 class="text-3xl font-black text-slate-800 tracking-tight" id="stat-remaining"><?php echo e($totalTarget - $currentTaken); ?></h3>
                    </div>
                </div>
            </div>

            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 md:gap-4 mb-6 md:mb-8">
                <?php $__currentLoopData = [
                    ['id'=>'harian', 'label'=>'Absen Harian', 'sub'=>'Masuk & Pulang', 'icon'=>'calendar-check', 'color'=>'blue', 'type'=>'Harian'],
                    ['id'=>'makan', 'label'=>'Makan Bergizi', 'sub'=>'Jam Makan Siang', 'icon'=>'bowl-food', 'color'=>'orange', 'type'=>'Makan'],
                    ['id'=>'dhuha', 'label'=>'Sholat Dhuha', 'sub'=>'Ibadah Pagi', 'icon'=>'sun-horizon', 'color'=>'emerald', 'type'=>'Dhuha'],
                    ['id'=>'dhuhur', 'label'=>'Sholat Dhuhur', 'sub'=>'Ibadah Siang', 'icon'=>'moon-stars', 'color'=>'amber', 'type'=>'Dhuhur'],
                    ['id'=>'ekskul', 'label'=>'Ekskul', 'sub'=>'Kegiatan Sore', 'icon'=>'basketball', 'color'=>'purple', 'type'=>'Ekstrakurikuler']
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mode): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <button id="btn-<?php echo e($mode['id']); ?>" data-type="<?php echo e($mode['type']); ?>" class="scan-type-btn group relative bg-white p-3 md:p-4 rounded-2xl md:rounded-[2rem] shadow-sm border border-slate-100 hover:shadow-md transition-all text-left overflow-hidden ring-2 ring-transparent">
                    <div class="flex items-center justify-between mb-2 md:mb-3">
                        <div class="p-2 md:p-3 rounded-xl md:rounded-2xl bg-<?php echo e($mode['color']); ?>-50 text-<?php echo e($mode['color']); ?>-600 group-hover:bg-<?php echo e($mode['color']); ?>-600 group-hover:text-white transition-colors">
                            <i class="ph-bold ph-<?php echo e($mode['icon']); ?> text-xl md:text-2xl"></i>
                        </div>
                        <div class="w-2.5 h-2.5 md:w-3 md:h-3 rounded-full border-2 border-slate-200 indicator-dot transition-all"></div>
                    </div>
                    <h3 class="font-bold text-slate-700 text-xs md:text-sm group-hover:text-<?php echo e($mode['color']); ?>-700 transition-colors"><?php echo e($mode['label']); ?></h3>
                    <p class="text-[9px] md:text-[10px] text-slate-400 mt-0.5 font-medium truncate"><?php echo e($mode['sub']); ?></p>
                    <div class="absolute inset-0 border-2 border-<?php echo e($mode['color']); ?>-500 rounded-2xl md:rounded-[2rem] opacity-0 scale-95 transition-all active-border"></div>
                </button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            
            <div id="extra-selector-container" class="hidden mb-8 animate-fade-in-down">
                <div class="bg-white p-4 rounded-[1.5rem] border border-slate-200 shadow-sm flex flex-col md:flex-row items-center gap-4">
                    <div class="p-2 bg-purple-100 text-purple-600 rounded-xl"><i class="ph-fill ph-trophy text-2xl"></i></div>
                    <div class="flex-1 w-full">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wide block mb-1">Pilih Jenis Kegiatan</label>
                        <select id="extra-activity-select" class="w-full rounded-xl border-slate-300 focus:border-purple-500 font-bold text-slate-700 h-10 md:h-12 text-sm">
                            <option value="">-- Pilih Ekstrakurikuler --</option>
                            <?php if(isset($extracurriculars)): ?>
                                <?php $__currentLoopData = $extracurriculars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ekskul): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($ekskul->name); ?>"><?php echo e($ekskul->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
            </div>

            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 lg:gap-8">
                
                
                <div class="lg:col-span-5 flex flex-col order-1 lg:order-1">
                    <div class="bg-white rounded-[1.5rem] md:rounded-[2rem] p-3 md:p-4 shadow-xl shadow-slate-200/60 border border-slate-100 h-fit">
                        <div class="flex justify-between items-center mb-3 md:mb-4 px-2">
                            <h3 class="font-bold text-slate-800 flex items-center gap-2 text-sm md:text-base">
                                <i class="ph-fill ph-camera text-blue-600 text-lg md:text-xl"></i> Kamera
                            </h3>
                            <div id="mode-badge" class="px-2 py-1 md:px-3 rounded-full text-[10px] md:text-xs font-black uppercase tracking-wider bg-slate-100 text-slate-500 border border-slate-200 flex items-center gap-1.5 md:gap-2">
                                <span class="w-1.5 h-1.5 md:w-2 md:h-2 rounded-full bg-slate-400"></span>
                                <span id="mode-text">Standby</span>
                            </div>
                        </div>

                        
                        <div class="relative bg-slate-900 rounded-[1rem] md:rounded-[1.5rem] overflow-hidden aspect-auto min-h-[300px] border-[3px] md:border-[4px] border-slate-900 shadow-inner group">
                            <?php if(isset($scheduleConfig) && ($scheduleConfig['is_holiday'] ?? false)): ?>
                            <div class="holiday-overlay text-center p-6">
                                <div class="w-16 h-16 bg-red-50 text-red-500 rounded-full flex items-center justify-center mb-4"><i class="ph-duotone ph-prohibit text-3xl"></i></div>
                                <h3 class="text-xl font-black text-slate-800 mb-2">Scanner Nonaktif</h3>
                            </div>
                            <?php endif; ?>
                            
                            <div id="qr-reader" class="w-full h-full object-cover"></div>
                            <div id="scanner-overlay-el" class="scanner-overlay z-20"><div class="scanner-line"></div></div>
                            <div class="absolute bottom-6 left-0 right-0 flex justify-center z-30 px-6">
                                <div id="scan-status" class="bg-white/90 backdrop-blur-md text-slate-800 text-[10px] md:text-xs py-2 px-4 rounded-full font-bold border border-white/20 shadow-lg flex items-center gap-2">
                                    <i class="ph-bold ph-circle-notch animate-spin text-blue-600"></i> Menunggu...
                                </div>
                            </div>
                        </div>

                        <div id="scan-result" class="mt-4 p-3 rounded-xl font-bold text-xs md:text-sm text-center hidden transition-all duration-500 shadow-sm border border-transparent"></div>
                        
                        <button id="btn-reset-auto" class="hidden w-full mt-3 py-2.5 rounded-xl border-2 border-dashed border-blue-200 text-blue-600 font-bold text-xs md:text-sm hover:bg-blue-50 transition-colors flex items-center justify-center gap-2" onclick="resetAutoMode()">
                            <i class="ph-bold ph-arrows-clockwise"></i> Kembali ke Otomatis
                        </button>
                    </div>
                </div>

                
                <div class="lg:col-span-7 flex flex-col order-2 lg:order-2">
                    <div class="bg-white rounded-[1.5rem] md:rounded-[2rem] shadow-xl shadow-slate-200/60 border border-slate-100 flex flex-col h-full min-h-[400px] md:min-h-[500px] relative overflow-hidden">
                        
                        <div class="p-4 md:p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                            <div>
                                <h3 class="font-bold text-slate-800 text-sm md:text-lg flex items-center gap-2">
                                    <i class="ph-duotone ph-list-dashes text-blue-600 text-lg md:text-xl"></i> Riwayat
                                </h3>
                                <p class="text-[10px] md:text-xs text-slate-400 font-medium mt-0.5">Real-time update.</p>
                            </div>
                            <div class="flex items-center gap-1.5 md:gap-2 px-2 py-1 md:px-3 md:py-1.5 bg-emerald-50 border border-emerald-100 rounded-lg">
                                <span class="relative flex h-2 w-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span></span>
                                <span class="text-[9px] md:text-[10px] font-black uppercase tracking-wider text-emerald-600">Online</span>
                            </div>
                        </div>
                        
                        <div class="flex-1 overflow-hidden relative">
                            <div class="absolute inset-0 overflow-auto custom-scrollbar">
                                <table class="w-full text-left border-collapse">
                                    <thead class="bg-white sticky top-0 z-10 shadow-sm text-[10px] md:text-xs font-bold text-slate-400 uppercase tracking-wider">
                                        <tr>
                                            <th class="px-3 py-3 md:px-6 md:py-4 rounded-tl-2xl">Siswa</th>
                                            <th class="col-harian px-2 py-3 md:px-4 md:py-4 text-center">Masuk</th>
                                            <th class="col-harian px-2 py-3 md:px-4 md:py-4 text-center">Pulang</th>
                                            <th class="col-waktu hidden-col px-2 py-3 md:px-4 md:py-4 text-center">Waktu</th>
                                            <th class="col-kegiatan hidden-col px-2 py-3 md:px-4 md:py-4 text-center">Kegiatan</th>
                                            <th class="px-3 py-3 md:px-6 md:py-4 text-right rounded-tr-2xl">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="scan-log" class="text-xs md:text-sm divide-y divide-slate-50">
                                        <?php if(isset($recentScans)): ?>
                                            <?php $__currentLoopData = $recentScans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $scan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr class="log-entry group hover:bg-slate-50/80 transition-colors" 
                                                    data-harian="<?php echo e(($scan['data_harian'] ?? false) ? 'true' : 'false'); ?>"
                                                    data-makan="<?php echo e(($scan['data_makan'] ?? false) ? 'true' : 'false'); ?>"
                                                    data-dhuha="<?php echo e(($scan['data_dhuha'] ?? false) ? 'true' : 'false'); ?>"
                                                    data-dhuhur="<?php echo e(($scan['data_dhuhur'] ?? false) ? 'true' : 'false'); ?>"
                                                    data-ekskul="<?php echo e(($scan['data_ekskul'] ?? false) ? 'true' : 'false'); ?>">
                                                    
                                                    <td class="px-3 py-3 md:px-6 md:py-4">
                                                        <div class="font-bold text-slate-800 line-clamp-1 max-w-[100px] md:max-w-none"><?php echo e($scan['student_name']); ?></div>
                                                        <div class="text-[9px] md:text-[10px] text-slate-400 font-mono mt-0.5"><?php echo e($scan['student_id']); ?></div>
                                                    </td>
                                                    <td class="col-harian px-2 py-3 md:px-4 md:py-4 text-center">
                                                        <?php if($scan['time_in'] ?? false): ?> <span class="font-mono font-bold text-slate-600 bg-slate-100 px-1.5 py-0.5 md:px-2 md:py-1 rounded text-[10px] md:text-xs"><?php echo e(\Carbon\Carbon::parse($scan['time_in'])->format('H:i')); ?></span>
                                                        <?php else: ?> <span class="text-slate-300">-</span> <?php endif; ?>
                                                    </td>
                                                    <td class="col-harian px-2 py-3 md:px-4 md:py-4 text-center">
                                                        <?php if($scan['time_out'] ?? false): ?> <span class="font-mono font-bold text-slate-600 bg-slate-100 px-1.5 py-0.5 md:px-2 md:py-1 rounded text-[10px] md:text-xs"><?php echo e(\Carbon\Carbon::parse($scan['time_out'])->format('H:i')); ?></span>
                                                        <?php else: ?> <span class="text-slate-300">-</span> <?php endif; ?>
                                                    </td>
                                                    <td class="col-waktu hidden-col px-2 py-3 md:px-4 md:py-4 text-center">
                                                        <?php $timeFormat = function($t) { return $t ? \Carbon\Carbon::parse($t)->format('H:i') : '-'; }; ?>
                                                        <span class="time-makan font-mono font-bold text-orange-600 bg-orange-50 px-1.5 py-0.5 md:px-2 md:py-1 rounded text-[10px] md:text-xs <?php echo e(($scan['makan_time'] ?? false) ? '' : 'hidden'); ?>"><?php echo e($timeFormat($scan['makan_time'] ?? false)); ?></span>
                                                        <span class="time-dhuha font-mono font-bold text-slate-600 bg-slate-100 px-1.5 py-0.5 md:px-2 md:py-1 rounded text-[10px] md:text-xs <?php echo e(($scan['dhuha_time'] ?? false) ? '' : 'hidden'); ?>"><?php echo e($timeFormat($scan['dhuha_time'] ?? false)); ?></span>
                                                        <span class="time-dhuhur font-mono font-bold text-slate-600 bg-slate-100 px-1.5 py-0.5 md:px-2 md:py-1 rounded text-[10px] md:text-xs <?php echo e(($scan['dhuhur_time'] ?? false) ? '' : 'hidden'); ?>"><?php echo e($timeFormat($scan['dhuhur_time'] ?? false)); ?></span>
                                                        <span class="time-ekskul font-mono font-bold text-slate-600 bg-slate-100 px-1.5 py-0.5 md:px-2 md:py-1 rounded text-[10px] md:text-xs <?php echo e(($scan['ekskul_time'] ?? false) ? '' : 'hidden'); ?>"><?php echo e($timeFormat($scan['ekskul_time'] ?? false)); ?></span>
                                                    </td>
                                                    <td class="col-kegiatan hidden-col px-2 py-3 md:px-4 md:py-4 text-center text-slate-600 font-medium text-[10px] md:text-xs line-clamp-1">
                                                        <?php echo e($scan['ekskul_name'] ?? '-'); ?>

                                                    </td>
                                                    <td class="log-status px-3 py-3 md:px-6 md:py-4 text-right">
                                                        <span class="badge-harian inline-flex items-center px-1.5 py-0.5 md:px-2.5 md:py-1 rounded-lg text-[9px] md:text-[10px] font-black uppercase tracking-wide <?php echo e(($scan['status'] ?? '') == 'Masuk' ? 'bg-emerald-100 text-emerald-700' : (($scan['status'] ?? '') == 'Terlambat' ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-500')); ?>">
                                                            <?php echo e($scan['status'] ?? '-'); ?>

                                                        </span>
                                                        <span class="badge-makan hidden inline-flex items-center gap-1 px-1.5 py-0.5 md:px-2.5 md:py-1 rounded-lg text-[9px] md:text-[10px] font-black uppercase tracking-wide bg-orange-100 text-orange-700"><i class="ph-fill ph-check"></i> Diambil</span>
                                                        <span class="badge-dhuha hidden inline-flex items-center px-1.5 py-0.5 md:px-2.5 md:py-1 rounded-lg text-[9px] md:text-[10px] font-black uppercase tracking-wide bg-emerald-100 text-emerald-700">Selesai</span>
                                                        <span class="badge-dhuhur hidden inline-flex items-center px-1.5 py-0.5 md:px-2.5 md:py-1 rounded-lg text-[9px] md:text-[10px] font-black uppercase tracking-wide bg-amber-100 text-amber-700">Selesai</span>
                                                        <span class="badge-ekskul hidden inline-flex items-center px-1.5 py-0.5 md:px-2.5 md:py-1 rounded-lg text-[9px] md:text-[10px] font-black uppercase tracking-wide bg-purple-100 text-purple-700">Hadir</span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                                
                                <div id="no-log-entry" class="<?php echo e((isset($recentScans) && count($recentScans) > 0) ? 'hidden' : ''); ?> flex flex-col items-center justify-center py-10 md:py-20 text-center">
                                    <div class="w-12 h-12 md:w-16 md:h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3 md:mb-4"><i class="ph-duotone ph-qr-code text-2xl md:text-3xl text-slate-300"></i></div>
                                    <p class="text-slate-400 font-medium text-xs md:text-sm">Belum ada data scan.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        const SCHEDULE_DATA = <?php echo $scheduleJson; ?>;
        
        let statsData = {
            total: <?php echo e($totalTarget); ?>,
            taken: <?php echo e($currentTaken); ?>

        };

        // AUDIO CONTEXT (Improvement: Check state before creating new one)
        let audioCtx;
        function initAudio() { 
            if (!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)(); 
            if (audioCtx.state === 'suspended') audioCtx.resume(); 
        }

        function escapeHtml(text) { if (!text) return text; return String(text).replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;"); }

        document.addEventListener('DOMContentLoaded', (event) => {
            if(SCHEDULE_DATA.is_holiday) return;
            
            const toMinutes = (timeStr) => { 
                if(!timeStr) return 0; 
                const [h, m] = timeStr.split(':').map(Number); 
                return h * 60 + m; 
            };
            
            // --- 1. KONFIGURASI DINAMIS DARI DATABASE ---
            const MODE_TIMES = {
                MAKAN_START: toMinutes(SCHEDULE_DATA.makan_start || '09:00'), 
                MAKAN_END: toMinutes(SCHEDULE_DATA.makan_end || '10:00'),
                DHUHA_START: toMinutes(SCHEDULE_DATA.dhuha_start || '07:30'), 
                DHUHA_END: toMinutes(SCHEDULE_DATA.dhuha_end || '09:00'),
                DHUHUR_START: toMinutes(SCHEDULE_DATA.dhuhur_start || '11:45'), 
                DHUHUR_END: toMinutes(SCHEDULE_DATA.dhuhur_end || '12:30')
            };

            let currentScanMode = 'Harian'; 
            let selectedExtra = ''; 
            let manualOverride = false; 
            let isProcessing = false;
            
            // --- 2. DEBOUNCE SET (Mencegah Double Scan) ---
            const processedSet = new Set(); 

            const csrfToken = '<?php echo e(csrf_token()); ?>'; 
            const scanProcessUrl = '<?php echo e(route('scan.process')); ?>'; 

            // DOM Elements
            const logTableBody = document.getElementById('scan-log');
            const scanStatus = document.getElementById('scan-status');
            const scanResult = document.getElementById('scan-result');
            const modeBadgeText = document.getElementById('mode-text');
            const modeBadge = document.getElementById('mode-badge');
            const extraContainer = document.getElementById('extra-selector-container');
            const extraSelect = document.getElementById('extra-activity-select');
            const btnResetAuto = document.getElementById('btn-reset-auto');
            const scannerOverlay = document.getElementById('scanner-overlay-el');
            
            // Stats Panel Elements
            const makanStatsPanel = document.getElementById('makan-stats-panel');
            const elStatTaken = document.getElementById('stat-taken');
            const elStatRemaining = document.getElementById('stat-remaining');

            function playBeep(type = 'success') {
                try { 
                    initAudio(); 
                    const osc = audioCtx.createOscillator(); 
                    const gain = audioCtx.createGain(); 
                    osc.connect(gain); 
                    gain.connect(audioCtx.destination); 
                    osc.type = 'sine';
                    
                    let freq = 880; 
                    if(type === 'warning') freq = 440; 
                    else if(type === 'error') freq = 200;
                    else if(type === 'makan') freq = 600; 

                    osc.frequency.setValueAtTime(freq, audioCtx.currentTime); 
                    gain.gain.setValueAtTime(0.1, audioCtx.currentTime); 
                    gain.gain.exponentialRampToValueAtTime(0.0001, audioCtx.currentTime + 0.5); 
                    osc.start(audioCtx.currentTime); 
                    osc.stop(audioCtx.currentTime + 0.5);
                } catch (e) { console.log("Audio err", e); }
            }

            const visualConfig = { 
                'Harian': {color:'blue',label:'Absen Harian'}, 
                'Makan': {color:'orange',label:'Makan Bergizi'},
                'Dhuha': {color:'emerald',label:'Sholat Dhuha'}, 
                'Dhuhur': {color:'amber',label:'Sholat Dhuhur'}, 
                'Ekstrakurikuler': {color:'purple',label:'Ekstrakurikuler'} 
            };
            
            // Clock Logic
            const clockElement = document.getElementById('clock');
            if(clockElement) { setInterval(() => { clockElement.textContent = new Date().toLocaleTimeString('id-ID', { hour12: false }); }, 1000); }

            // Auto Mode Logic
            function autoSelectMode() {
                if (manualOverride) return;
                const now = new Date(); 
                const currentMinutes = now.getHours() * 60 + now.getMinutes();
                let newMode = 'Harian';
                
                if (currentMinutes >= MODE_TIMES.MAKAN_START && currentMinutes < MODE_TIMES.MAKAN_END) newMode = 'Makan';
                else if (currentMinutes >= MODE_TIMES.DHUHA_START && currentMinutes < MODE_TIMES.DHUHA_END) newMode = 'Dhuha';
                else if (currentMinutes >= MODE_TIMES.DHUHUR_START && currentMinutes < MODE_TIMES.DHUHUR_END) newMode = 'Dhuhur';
                
                if (currentScanMode !== newMode && currentScanMode !== 'Ekstrakurikuler') selectScanMode(newMode, true);
            }

            window.resetAutoMode = function() { manualOverride = false; btnResetAuto.classList.add('hidden'); autoSelectMode(); }

            function selectScanMode(type, isAuto = false) {
                if (!isAuto) { manualOverride = true; btnResetAuto.classList.remove('hidden'); initAudio(); } else { btnResetAuto.classList.add('hidden'); }
                currentScanMode = type; 
                const config = visualConfig[type];
                
                // Update UI Buttons
                document.querySelectorAll('.scan-type-btn').forEach(btn => {
                    const btnType = btn.getAttribute('data-type'); 
                    const activeBorder = btn.querySelector('.active-border'); 
                    const indicator = btn.querySelector('.indicator-dot');
                    
                    if (btnType === type) { 
                        activeBorder.classList.remove('opacity-0', 'scale-95'); 
                        indicator.className = `w-2.5 h-2.5 md:w-3 md:h-3 rounded-full indicator-dot bg-${config.color}-500 shadow-[0_0_8px_rgba(0,0,0,0.2)]`; 
                    } else { 
                        activeBorder.classList.add('opacity-0', 'scale-95'); 
                        indicator.className = `w-2.5 h-2.5 md:w-3 md:h-3 rounded-full border-2 border-slate-200 indicator-dot`; 
                    }
                });
                
                // Update Badge
                modeBadgeText.innerText = config.label;
                modeBadge.className = `px-2 py-1 md:px-3 rounded-full text-[10px] md:text-xs font-black uppercase tracking-wider border flex items-center gap-1.5 md:gap-2 bg-${config.color}-50 text-${config.color}-600 border-${config.color}-100`;
                modeBadge.querySelector('span').className = `w-1.5 h-1.5 md:w-2 md:h-2 rounded-full bg-${config.color}-500 animate-pulse`;

                // Update Visible Panel
                extraContainer.classList.add('hidden');
                
                if (type === 'Makan') {
                    makanStatsPanel.classList.remove('hidden');
                    makanStatsPanel.classList.add('grid');
                    scanStatus.innerHTML = `<i class="ph-bold ph-bowl-food text-orange-500"></i> Scan Ambil Makan`;
                } else {
                    makanStatsPanel.classList.add('hidden');
                    makanStatsPanel.classList.remove('grid');
                    
                    if (type === 'Ekstrakurikuler') {
                        extraContainer.classList.remove('hidden');
                        scanStatus.innerHTML = selectedExtra ? `<i class="ph-bold ph-check text-purple-500"></i> Siap: ${selectedExtra}` : `<i class="ph-bold ph-warning text-amber-500"></i> Pilih Kegiatan!`;
                    } else {
                        scanStatus.innerHTML = `<i class="ph-bold ph-qr-code text-slate-500"></i> Scan QR ${type}`;
                    }
                }
                
                updateTableLayout(type); 
                filterLogs(type);
            }

            // Event Listeners
            document.querySelectorAll('.scan-type-btn').forEach(btn => { btn.addEventListener('click', () => selectScanMode(btn.getAttribute('data-type'))); });
            extraSelect.addEventListener('change', (e) => {
                selectedExtra = e.target.value; 
                if (selectedExtra) scanStatus.innerHTML = `<i class="ph-bold ph-check text-purple-500"></i> Siap: ${selectedExtra}`;
                else scanStatus.innerHTML = `<i class="ph-bold ph-warning text-amber-500"></i> Pilih Kegiatan!`;
            });
            
            // Run Init
            autoSelectMode(); 
            setInterval(autoSelectMode, 60000); 

            // Table Helpers
            function updateTableLayout(type) {
                const harianCols = document.querySelectorAll('.col-harian'); 
                const waktuCols = document.querySelectorAll('.col-waktu'); 
                const kegiatanCols = document.querySelectorAll('.col-kegiatan');
                
                harianCols.forEach(el => el.classList.add('hidden-col')); 
                waktuCols.forEach(el => el.classList.add('hidden-col')); 
                kegiatanCols.forEach(el => el.classList.add('hidden-col'));
                
                if (type === 'Harian') harianCols.forEach(el => el.classList.remove('hidden-col'));
                else if (type === 'Ekstrakurikuler') { waktuCols.forEach(el => el.classList.remove('hidden-col')); kegiatanCols.forEach(el => el.classList.remove('hidden-col')); }
                else waktuCols.forEach(el => el.classList.remove('hidden-col')); 
            }

            function filterLogs(type) {
                const rows = logTableBody.querySelectorAll('.log-entry'); 
                let visibleCount = 0;
                rows.forEach(row => {
                    let shouldShow = false;
                    if (type === 'Harian') shouldShow = row.getAttribute('data-harian') === 'true';
                    else if (type === 'Makan') shouldShow = row.getAttribute('data-makan') === 'true';
                    else if (type === 'Dhuha') shouldShow = row.getAttribute('data-dhuha') === 'true';
                    else if (type === 'Dhuhur') shouldShow = row.getAttribute('data-dhuhur') === 'true';
                    else if (type === 'Ekstrakurikuler') shouldShow = row.getAttribute('data-ekskul') === 'true';
                    
                    if (shouldShow) { 
                        row.classList.remove('hidden-row'); 
                        toggleCells(row, type.toLowerCase()); 
                        visibleCount++; 
                    } else {
                        row.classList.add('hidden-row');
                    }
                });
                
                const noLogEntry = document.getElementById('no-log-entry');
                if (visibleCount === 0) noLogEntry.classList.remove('hidden'); else noLogEntry.classList.add('hidden');
            }

            function toggleCells(row, activeType) {
                row.querySelectorAll('.badge-harian, .badge-makan, .badge-dhuha, .badge-dhuhur, .badge-ekskul').forEach(el => el.classList.add('hidden'));
                row.querySelectorAll('.col-waktu span').forEach(el => el.classList.add('hidden'));
                
                let badgeClass = `.badge-${activeType}`; 
                if(row.querySelector(badgeClass)) row.querySelector(badgeClass).classList.remove('hidden');
                
                if (activeType !== 'harian') { 
                    let timeClass = `.time-${activeType}`; 
                    if(row.querySelector(timeClass)) row.querySelector(timeClass).classList.remove('hidden'); 
                }
            }

            function updateMakanStats(serverTotal) {
                // Update dari server (lebih akurat) atau increment lokal
                if(serverTotal) statsData.taken = serverTotal;
                else statsData.taken++; 
                
                elStatTaken.textContent = statsData.taken;
                elStatRemaining.textContent = statsData.total - statsData.taken;
                
                elStatTaken.parentElement.classList.add('scale-105');
                setTimeout(() => elStatTaken.parentElement.classList.remove('scale-105'), 200);
            }

            function addNewRowToTable(scanData, scanType) {
                const now = new Date(); 
                const timeString = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                const name = scanData.student_name || 'Siswa'; 
                const id = scanData.student_id || 'ID'; 
                const status = scanData.status || 'Hadir';
                
                // Hapus baris 'no data' jika ada
                document.getElementById('no-log-entry').classList.add('hidden');

                const row = document.createElement('tr');
                row.className = 'log-entry group hover:bg-slate-50/80 transition-colors new-row-entry';
                
                // Set Attributes
                row.setAttribute('data-harian', scanType === 'Harian'); 
                row.setAttribute('data-makan', scanType === 'Makan');
                row.setAttribute('data-dhuha', scanType === 'Dhuha'); 
                row.setAttribute('data-dhuhur', scanType === 'Dhuhur'); 
                row.setAttribute('data-ekskul', scanType === 'Ekstrakurikuler');
                
                let statusClass = 'bg-slate-100 text-slate-500';
                if(status === 'Masuk' || status === 'Hadir') statusClass = 'bg-emerald-100 text-emerald-700';
                else if(String(status).includes('Terlambat')) statusClass = 'bg-amber-100 text-amber-700';

                // Kolom badge
                let statusBadgeHtml = '';
                if(scanType === 'Makan') statusBadgeHtml = `<span class="badge-makan inline-flex items-center gap-1 px-1.5 py-0.5 md:px-2.5 md:py-1 rounded-lg text-[9px] md:text-[10px] font-black uppercase tracking-wide bg-orange-100 text-orange-700"><i class="ph-fill ph-check"></i> Diambil</span>`;
                else if (scanType === 'Harian') statusBadgeHtml = `<span class="badge-harian inline-flex items-center px-1.5 py-0.5 md:px-2.5 md:py-1 rounded-lg text-[9px] md:text-[10px] font-black uppercase tracking-wide ${statusClass}">${escapeHtml(status)}</span>`;
                else statusBadgeHtml = `<span class="inline-flex items-center px-1.5 py-0.5 md:px-2.5 md:py-1 rounded-lg text-[9px] md:text-[10px] font-black uppercase tracking-wide bg-emerald-100 text-emerald-700">Selesai</span>`;

                row.innerHTML = `
                    <td class="px-3 py-3 md:px-6 md:py-4">
                        <div class="font-bold text-slate-800 line-clamp-1 max-w-[100px] md:max-w-none">${escapeHtml(name)}</div>
                        <div class="text-[9px] md:text-[10px] text-slate-400 font-mono mt-0.5">${escapeHtml(id)}</div>
                    </td>
                    <td class="col-harian px-2 py-3 md:px-4 md:py-4 text-center ${scanType !== 'Harian' ? 'hidden-col' : ''}">
                        <span class="font-mono font-bold text-slate-600 bg-slate-100 px-1.5 py-0.5 md:px-2 md:py-1 rounded text-[10px] md:text-xs">${scanType === 'Harian' ? timeString : '-'}</span>
                    </td>
                    <td class="col-harian px-2 py-3 md:px-4 md:py-4 text-center ${scanType !== 'Harian' ? 'hidden-col' : ''}">-</td>
                    <td class="col-waktu px-2 py-3 md:px-4 md:py-4 text-center ${scanType === 'Harian' ? 'hidden-col' : ''}">
                        <span class="font-mono font-bold ${scanType === 'Makan' ? 'text-orange-600 bg-orange-50' : 'text-slate-600 bg-slate-100'} px-1.5 py-0.5 md:px-2 md:py-1 rounded text-[10px] md:text-xs">${scanType !== 'Harian' ? timeString : '-'}</span>
                    </td>
                    <td class="col-kegiatan px-2 py-3 md:px-4 md:py-4 text-center text-slate-600 font-medium text-[10px] md:text-xs line-clamp-1 ${scanType === 'Harian' ? 'hidden-col' : ''}">
                        ${scanType === 'Ekstrakurikuler' ? escapeHtml(selectedExtra) : '-'}
                    </td>
                    <td class="px-3 py-3 md:px-6 md:py-4 text-right">
                        ${statusBadgeHtml}
                    </td>
                `;
                logTableBody.insertBefore(row, logTableBody.firstChild); 
                filterLogs(currentScanMode);
            }

            // QR HANDLER
            async function processScan(studentId, type, activity = null) {
                try {
                    const body = { student_id: studentId, type: type }; 
                    if(activity) body.activity = activity;
                    
                    const response = await fetch(scanProcessUrl, { 
                        method: 'POST', 
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }, 
                        body: JSON.stringify(body) 
                    });
                    
                    const result = await response.json();
                    
                    if (response.ok) {
                        const isLate = String(result.message).toUpperCase().includes('TERLAMBAT');
                        
                        if (type === 'Makan') {
                             triggerScanEffect('success'); 
                             playBeep('makan');
                             // Gunakan data stats dari server jika ada
                             updateMakanStats(result.stats ? result.stats.taken : null); 
                        } else {
                             triggerScanEffect(isLate ? 'warning' : 'success'); 
                             playBeep(isLate ? 'warning' : 'success'); 
                        }
                        
                        showScanResult(isLate ? 'warning' : 'success', result.message);
                        if(result.scan) addNewRowToTable(result.scan, type);
                    } else { 
                        triggerScanEffect('error'); 
                        showScanResult(response.status === 409 ? 'warning' : 'error', result.message || 'Error Server'); 
                        playBeep('error'); 
                    }
                } catch (error) { 
                    triggerScanEffect('error'); 
                    showScanResult('error', 'Gagal koneksi server.'); 
                    playBeep('error'); 
                } finally { 
                    resumeScanner(); 
                }
            }

            const onScanSuccess = (decodedText, decodedResult) => {
                if (isProcessing) return;

                // --- 2. DEBOUNCE CHECK ---
                // Jika ID ini baru saja diproses dalam 5 detik terakhir, abaikan
                if (processedSet.has(decodedText)) {
                    console.log(`Scan diabaikan: ${decodedText} (Cooldown)`);
                    return; 
                }

                if (currentScanMode === 'Ekstrakurikuler' && !selectedExtra) {
                    Swal.fire({ icon: 'warning', title: 'Pilih Kegiatan!', text: 'Silakan pilih jenis ekstrakurikuler.', timer: 2000, showConfirmButton: false }); return;
                }

                isProcessing = true; 
                html5QrCode.pause(); 
                
                // Tambahkan ke Set Cooldown
                processedSet.add(decodedText);
                setTimeout(() => processedSet.delete(decodedText), 5000); // Hapus setelah 5 detik

                scanStatus.innerHTML = `<i class="ph-bold ph-spinner animate-spin text-blue-600"></i> Memproses...`;
                
                if (decodedText.length < 3 || decodedText.length > 50) { 
                    showScanResult('error', 'QR Invalid'); 
                    triggerScanEffect('error'); 
                    playBeep('error'); 
                    resumeScanner(); 
                    return; 
                }
                
                let handler;
                if (currentScanMode === 'Harian') handler = () => processScan(decodedText, 'Harian');
                else if (currentScanMode === 'Makan') handler = () => processScan(decodedText, 'Makan', null);
                else handler = () => processScan(decodedText, currentScanMode, selectedExtra);
                
                handler();
            };

            // Setup HTML5 Qr Code
            const html5QrCode = new Html5Qrcode("qr-reader");
            const config = { fps: 10, qrbox: { width: 250, height: 250 }, aspectRatio: 1.0 };
            
            function startCamera() {
                Html5Qrcode.getCameras().then(cameras => {
                    if (cameras && cameras.length) {
                        const rearCamera = cameras.find(c => c.label.toLowerCase().includes('back')) || cameras[0];
                        html5QrCode.start(rearCamera.id, config, onScanSuccess)
                            .catch(err => {
                                console.error(err);
                                // Fallback jika kamera belakang gagal
                                html5QrCode.start(cameras[0].id, config, onScanSuccess);
                            });
                    } else { 
                        scanStatus.textContent = "Kamera tidak ditemukan"; 
                        Swal.fire('Error', 'Kamera tidak ditemukan.', 'error'); 
                    }
                }).catch(err => { 
                    scanStatus.textContent = "Izin kamera ditolak"; 
                    Swal.fire('Error', 'Izin kamera ditolak.', 'error'); 
                });
            }
            startCamera();

            function triggerScanEffect(type) {
                scannerOverlay.classList.remove('scan-success-effect', 'scan-error-effect', 'scan-warning-effect', 'scan-makan-effect'); 
                if (currentScanMode === 'Makan' && type === 'success') scannerOverlay.classList.add('scan-makan-effect');
                else scannerOverlay.classList.add(`scan-${type}-effect`);
                setTimeout(() => scannerOverlay.classList.remove(`scan-${type}-effect`, 'scan-makan-effect'), 500);
            }

            function resumeScanner() {
                setTimeout(() => { 
                    html5QrCode.resume(); 
                    let statusText = currentScanMode;
                    if (currentScanMode === 'Ekstrakurikuler') statusText = (selectedExtra || 'Pilih Kegiatan');
                    if (currentScanMode === 'Makan') statusText = 'Ambil Makan';
                    
                    scanStatus.innerHTML = `<i class="ph-bold ph-qr-code text-slate-500"></i> Scan QR ${statusText}`; 
                    isProcessing = false; 
                }, 1500); 
            }

            let resultTimeout;
            function showScanResult(type, message) {
                if (resultTimeout) clearTimeout(resultTimeout);
                scanResult.className = 'mt-3 md:mt-4 p-3 md:p-4 rounded-xl font-bold text-xs md:text-sm text-center transition-all duration-500 shadow-sm transform scale-100 opacity-100 border';
                const colors = { success: { bg: 'bg-emerald-50', text: 'text-emerald-700', border: 'border-emerald-200' }, warning: { bg: 'bg-amber-50', text: 'text-amber-700', border: 'border-amber-200' }, error: { bg: 'bg-rose-50', text: 'text-rose-700', border: 'border-rose-200' } };
                const c = colors[type] || colors.error;
                scanResult.classList.add(c.bg, c.text, c.border); 
                scanResult.innerHTML = `<span>${escapeHtml(message)}</span>`; 
                scanResult.classList.remove('hidden');
                resultTimeout = setTimeout(() => { scanResult.classList.add('opacity-0', 'scale-95'); setTimeout(() => scanResult.classList.add('hidden'), 300); }, 3000); 
            }
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
<?php endif; ?><?php /**PATH D:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/scan/index.blade.php ENDPATH**/ ?>