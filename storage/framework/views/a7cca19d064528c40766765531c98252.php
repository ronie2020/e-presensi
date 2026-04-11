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
    
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php $__env->stopPush(); ?>

    

    <div class="py-4 sm:py-10 font-sans text-slate-800" 
         x-data="teachingSession({ 
            sessionId: <?php echo e($session->id); ?>, 
            stats: <?php echo e(json_encode($stats)); ?>

         })">
         
        <div class="max-w-[1600px] mx-auto px-3 sm:px-6 lg:px-8">
            
            
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4 sm:mb-6">
                <a href="<?php echo e(route('teaching.index')); ?>" class="group inline-flex items-center gap-2 text-sm text-slate-500 hover:text-blue-600 transition font-bold">
                    <div class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center shadow-sm group-hover:border-blue-200 group-hover:bg-blue-50 transition">
                        <i class="ph-bold ph-arrow-left"></i>
                    </div>
                    Kembali
                </a>

                
                <?php if(session('error')): ?>
                    <div class="bg-rose-50 text-rose-600 px-4 py-2 rounded-xl text-xs sm:text-sm font-bold flex items-center gap-2 border border-rose-100 animate-pulse">
                        <i class="ph-fill ph-warning-circle"></i> <?php echo e(session('error')); ?>

                    </div>
                <?php endif; ?>
                <?php if(session('success')): ?>
                    <div class="bg-emerald-50 text-emerald-600 px-4 py-2 rounded-xl text-xs sm:text-sm font-bold flex items-center gap-2 border border-emerald-100">
                        <i class="ph-fill ph-check-circle"></i> <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>
            </div>

            
            <div class="relative bg-gray-900 bg-gradient-to-br from-slate-900 via-blue-900 to-blue-800 rounded-[1.5rem] sm:rounded-[2.5rem] p-5 sm:p-10 text-white shadow-xl mb-6 overflow-hidden group border border-white/10">
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
                    <div class="space-y-3 w-full">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="bg-blue-500 shadow-lg text-white text-[10px] font-black px-3 py-1.5 rounded-lg uppercase tracking-wider border border-blue-400/50">
                                <?php echo e($session->schedule->schoolClass->name); ?>

                            </span>
                            <span class="bg-white/10 backdrop-blur-md border border-white/20 text-blue-100 text-[10px] font-bold px-3 py-1.5 rounded-lg flex items-center gap-1.5">
                                <i class="ph-bold ph-clock"></i> <?php echo e(\Carbon\Carbon::parse($session->started_at)->format('H:i')); ?>

                            </span>

                            <?php if(!$isOpen): ?>
                                <span class="bg-slate-700/80 backdrop-blur text-slate-300 text-[10px] font-bold px-3 py-1.5 rounded-lg uppercase border border-slate-600/50 flex items-center gap-1">
                                    <i class="ph-fill ph-lock-key"></i> Selesai
                                </span>
                            <?php else: ?>
                                <span class="bg-emerald-500/80 backdrop-blur text-white text-[10px] font-bold px-3 py-1.5 rounded-lg uppercase border border-emerald-400/50 animate-pulse flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 bg-white rounded-full"></span> Live
                                </span>
                            <?php endif; ?>
                        </div>
                        <h1 class="text-xl sm:text-3xl md:text-5xl font-black tracking-tight leading-tight text-white drop-shadow-sm break-words">
                            <?php echo e($session->schedule->subject->name); ?>

                        </h1>
                    </div>
                    
                    
                    <?php if($isOpen): ?>
                        <form id="close-session-form" action="<?php echo e(route('teaching.close', $session->id)); ?>" method="POST" class="w-full md:w-auto">
                            <?php echo csrf_field(); ?>
                            <button type="button" onclick="confirmCloseClass()" class="w-full md:w-auto group relative overflow-hidden bg-white hover:bg-rose-50 text-rose-600 pl-4 pr-5 py-3 rounded-2xl font-bold shadow-xl transition-all active:scale-95 flex items-center justify-center md:justify-start gap-3 border border-white/20">
                                <div class="bg-rose-100 p-2 rounded-xl group-hover:bg-rose-200 transition-colors">
                                    <i class="ph-bold ph-power text-lg"></i>
                                </div>
                                <div class="text-left">
                                    <div class="text-[9px] uppercase opacity-60 font-black tracking-widest text-slate-500">Selesai</div>
                                    <div class="text-sm font-black">Tutup Kelas</div>
                                </div>
                            </button>
                        </form>
                    <?php else: ?>
                        
                        <a href="<?php echo e(route('teaching.edit', $session->id)); ?>" class="w-full md:w-auto group relative overflow-hidden bg-amber-500 hover:bg-amber-400 text-white pl-4 pr-5 py-3 rounded-2xl font-bold shadow-xl transition-all active:scale-95 flex items-center justify-center md:justify-start gap-3 border border-white/20">
                            <div class="bg-white/20 p-2 rounded-xl group-hover:bg-white/30 transition-colors">
                                <i class="ph-bold ph-pencil-simple text-lg"></i>
                            </div>
                            <div class="text-left">
                                <div class="text-[9px] uppercase opacity-80 font-black tracking-widest text-amber-100">Ada Kesalahan?</div>
                                <div class="text-sm font-black">Edit Data</div>
                            </div>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 xl:gap-8">
                
                
                <div class="xl:col-span-4 space-y-6 h-fit xl:sticky xl:top-6 order-1">
                    
                    
                    <?php if($isOpen): ?>
                        <div class="bg-slate-900 rounded-[2rem] shadow-2xl p-5 text-center text-white relative overflow-hidden group border border-slate-800">
                            <div class="relative z-10">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center gap-2 text-blue-400 bg-slate-800/50 px-3 py-1 rounded-full border border-slate-700">
                                        <i class="ph-fill ph-wifi-high text-lg animate-pulse"></i>
                                        <span class="text-[10px] font-bold uppercase tracking-widest">RFID</span>
                                    </div>
                                    
                                    
                                    <button @click="toggleScanMode()" 
                                            class="text-[10px] font-bold px-2 py-1 rounded border transition-colors flex items-center gap-2"
                                            :class="isScanMode ? 'bg-blue-600 text-white border-blue-500 shadow-blue-500/50 shadow-lg' : 'bg-slate-800 text-slate-500 border-slate-700'">
                                        <span class="w-2 h-2 rounded-full" :class="isScanMode ? 'bg-white animate-pulse' : 'bg-slate-600'"></span>
                                        <span x-text="isScanMode ? 'AUTO FOCUS ON' : 'AUTO FOCUS OFF'"></span>
                                    </button>
                                </div>

                                <div class="mb-4 relative group/input">
                                    
                                    <input type="text" id="rfidInput" x-model="rfidCode" @keydown.enter.prevent="submitScan()"
                                        @blur="keepFocus($event)" 
                                        :disabled="!isScanMode && !showCamera"
                                        class="relative w-full bg-slate-800/80 border-2 border-slate-700 focus:border-blue-500 text-white rounded-2xl text-center font-mono text-lg tracking-[0.2em] py-4 transition-all focus:ring-4 focus:ring-blue-500/20 uppercase placeholder:text-slate-600 shadow-inner disabled:opacity-50 disabled:cursor-not-allowed"
                                        placeholder="TAP KARTU..." autocomplete="off">
                                </div>

                                <button @click="toggleCamera()" type="button" class="w-full py-3 bg-slate-800 hover:bg-slate-700 text-blue-300 font-bold rounded-xl border border-slate-700 hover:border-blue-500/50 transition flex items-center justify-center gap-2 text-sm shadow-lg mb-4">
                                    <i class="ph-bold ph-camera text-lg"></i>
                                    <span x-text="showCamera ? 'Tutup Kamera' : 'Scan via Kamera HP'"></span>
                                </button>

                                
                                <div x-show="showCamera" x-transition class="mt-4 bg-black rounded-2xl overflow-hidden border-2 border-slate-700 relative shadow-inner">
                                    <div id="reader" class="w-full h-64 bg-black"></div>
                                </div>

                                <p class="mt-4 text-xs font-mono text-slate-500" x-text="statusMessage">Menunggu input...</p>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="bg-white border border-slate-200 rounded-[2rem] p-6 text-center shadow-sm">
                            <h3 class="font-bold text-slate-800 text-base">Absensi Terkunci</h3>
                            <p class="text-xs text-slate-500">Sesi kelas telah berakhir. Gunakan tombol Edit di atas jika ada kesalahan.</p>
                        </div>
                    <?php endif; ?>

                    
                    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden" x-data="{ photoPreview: null }">
                         <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-lg shadow-sm">
                                <i class="ph-fill ph-notebook"></i>
                            </div>
                            <h3 class="font-bold text-slate-800 text-base">Jurnal Mengajar</h3>
                        </div>
                        <div class="p-6">
                            <fieldset <?php echo e(!$isOpen ? 'disabled' : ''); ?>>
                                <form action="<?php echo e(route('teaching.update', $session->id)); ?>" method="POST" enctype="multipart/form-data">
                                    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                                    <div class="space-y-4">
                                        
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Topik / Materi <span class="text-rose-500">*</span></label>
                                            <input type="text" name="topic" value="<?php echo e(old('topic', $session->topic)); ?>" 
                                                class="journal-input w-full rounded-2xl border-slate-200 focus:border-blue-500 font-bold text-slate-700 py-3 px-4 text-sm bg-slate-50 transition-all" 
                                                placeholder="Contoh: Aljabar Linear" required>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Catatan</label>
                                            <textarea name="activities" rows="3" 
                                                class="journal-input w-full rounded-2xl border-slate-200 focus:border-blue-500 text-sm text-slate-600 py-3 px-4 bg-slate-50 transition-all" 
                                                placeholder="Deskripsi kegiatan..."><?php echo e(old('activities', $session->activities)); ?></textarea>
                                        </div>
                                        
                                        
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Foto Dokumentasi</label>
                                            
                                            
                                            <?php if($session->photo_proof): ?>
                                                <div class="relative group h-40 rounded-2xl overflow-hidden border border-slate-200 mb-4 shadow-sm" x-show="!photoPreview">
                                                    <img src="<?php echo e(asset('storage/' . $session->photo_proof)); ?>" class="w-full h-full object-cover">
                                                    <a href="<?php echo e(asset('storage/' . $session->photo_proof)); ?>" target="_blank" class="absolute inset-0 bg-slate-900/40 backdrop-blur-[2px] flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300 text-white font-bold text-xs gap-2">
                                                        <i class="ph-bold ph-eye text-lg"></i> Lihat Foto
                                                    </a>
                                                </div>
                                            <?php endif; ?>

                                            
                                            <div class="relative h-40 rounded-2xl overflow-hidden border border-blue-200 mb-4 shadow-sm bg-blue-50" x-show="photoPreview" x-cloak>
                                                <img :src="photoPreview" class="w-full h-full object-cover">
                                                <div class="absolute bottom-0 left-0 right-0 bg-blue-600/80 text-white text-[10px] font-bold py-1 text-center backdrop-blur-sm">Foto Baru</div>
                                            </div>

                                            <?php if($isOpen): ?>
                                                <label class="flex flex-col items-center justify-center w-full h-20 border-2 border-dashed border-slate-200 rounded-2xl cursor-pointer hover:bg-blue-50 hover:border-blue-300 transition-all group/upload bg-slate-50">
                                                    <div class="flex flex-col items-center justify-center pt-2">
                                                        <i class="ph-duotone ph-image text-xl text-slate-300 group-hover/upload:text-blue-500 mb-1"></i>
                                                        <p class="text-[10px] text-slate-400 group-hover/upload:text-slate-600"><span class="font-bold">Upload Foto</span></p>
                                                    </div>
                                                    <input type="file" name="photo_proof" accept="image/*" class="hidden" 
                                                           @change="photoPreview = URL.createObjectURL($event.target.files[0])" />
                                                </label>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <?php if($isOpen): ?>
                                            <button type="submit" class="w-full bg-blue-900 text-white hover:bg-blue-800 font-bold py-3.5 rounded-2xl shadow-md transition-transform active:scale-95">Simpan Jurnal</button>
                                        <?php endif; ?>
                                    </div>
                                </form>
                            </fieldset>
                        </div>
                    </div>
                </div>

                
                <div class="xl:col-span-8 order-2" x-data="{ searchQuery: '' }">
                    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 flex flex-col h-full min-h-[600px] overflow-hidden">
                        
                        
                        <div class="p-5 sm:p-6 border-b border-slate-100 flex flex-col gap-4 bg-slate-50/30">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                <div>
                                    <h3 class="font-black text-slate-800 text-xl">Kehadiran Siswa</h3>
                                    <p class="text-xs text-slate-500 font-medium mt-1">Kelola absensi siswa secara manual atau scan.</p>
                                </div>
                            </div>
                            
                            
                            <div class="grid grid-cols-4 gap-2">
                                <div class="px-2 py-2 bg-emerald-100 text-emerald-700 rounded-xl text-center border border-emerald-200">
                                    <div class="text-[8px] font-black uppercase opacity-60">Hadir</div>
                                    <div class="text-base font-black leading-none" x-text="stats.present">0</div>
                                </div>
                                <div class="px-2 py-2 bg-blue-100 text-blue-700 rounded-xl text-center border border-blue-200">
                                    <div class="text-[8px] font-black uppercase opacity-60">Sakit</div>
                                    <div class="text-base font-black leading-none" x-text="stats.sick">0</div>
                                </div>
                                <div class="px-2 py-2 bg-amber-100 text-amber-700 rounded-xl text-center border border-amber-200">
                                    <div class="text-[8px] font-black uppercase opacity-60">Izin</div>
                                    <div class="text-base font-black leading-none" x-text="stats.permission">0</div>
                                </div>
                                <div class="px-2 py-2 bg-rose-100 text-rose-700 rounded-xl text-center border border-rose-200">
                                    <div class="text-[8px] font-black uppercase opacity-60">Alpha</div>
                                    <div class="text-base font-black leading-none" x-text="stats.alpha">0</div>
                                </div>
                            </div>

                            
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ph-bold ph-magnifying-glass text-slate-400 group-focus-within:text-blue-500"></i>
                                </div>
                                <input type="text" x-model="searchQuery" class="journal-input block w-full pl-10 pr-3 py-3 border-none rounded-xl bg-white ring-1 ring-slate-200 focus:ring-2 focus:ring-blue-500 placeholder-slate-400 text-sm font-bold shadow-sm transition-all" placeholder="Cari nama siswa...">
                            </div>
                        </div>

                        
                        <div class="flex-1 p-4 sm:p-6 bg-slate-50/30 overflow-y-auto max-h-[800px] custom-scrollbar">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <?php $__currentLoopData = $allStudents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        // LOGIKA STATUS AWAL SISWA
                                        $att = $attendances[$student->id] ?? null;
                                        $initialStatus = $att ? $att->status : null; 
                                        
                                        // HELPER INISIAL (AGAR LEBIH AMAN)
                                        $initials = Str::upper(Str::substr(trim($student->name), 0, 1));
                                    ?>

                                    
                                    <div class="relative border rounded-2xl p-3 flex items-center gap-3 transition-all duration-300 bg-white border-slate-200" 
                                         id="student-row-<?php echo e($student->id); ?>"
                                         x-data="{ 
                                            name: '<?php echo e(strtolower($student->name)); ?>', 
                                            id: '<?php echo e($student->student_id); ?>',
                                            status: '<?php echo e($initialStatus); ?>'
                                         }"
                                         
                                         @update-status-<?php echo e($student->id); ?>.window="status = $event.detail.status"
                                         x-show="name.includes(searchQuery.toLowerCase()) || id.includes(searchQuery.toLowerCase())"
                                         :class="{
                                            'bg-emerald-50/60 border-emerald-200 ring-1 ring-emerald-500/20': status === 'present',
                                            'bg-blue-50/60 border-blue-200': status === 'sick',
                                            'bg-amber-50/60 border-amber-200': status === 'permission',
                                            'bg-rose-50/60 border-rose-200': status === 'alpha',
                                            'bg-white border-slate-200': !status
                                         }">
                                        
                                        
                                        <div class="w-10 h-10 rounded-2xl flex items-center justify-center font-bold text-xs shrink-0 shadow-sm transition-all"
                                             :class="{ 'bg-white': status, 'bg-slate-100 text-slate-500': !status }">
                                             <template x-if="status === 'present'"> <i class="ph-fill ph-check-circle text-emerald-500 text-xl"></i> </template>
                                             <template x-if="status === 'sick'"> <span class="text-blue-600 font-black">S</span> </template>
                                             <template x-if="status === 'permission'"> <span class="text-amber-600 font-black">I</span> </template>
                                             <template x-if="status === 'alpha'"> <span class="text-rose-600 font-black">A</span> </template>
                                             <template x-if="!status"> <span><?php echo e($initials); ?></span> </template>
                                        </div>

                                        
                                        <div class="flex-1 min-w-0">
                                            <p class="font-bold text-slate-800 text-sm leading-tight break-words"><?php echo e($student->name); ?></p>
                                            <p class="text-[10px] text-slate-500 font-mono tracking-wide mt-0.5"><?php echo e($student->student_id); ?></p>
                                        </div>

                                        <?php if($isOpen): ?>
                                            <div class="flex items-center gap-1 shrink-0">
                                                
                                                <button @click="setManual(<?php echo e($student->id); ?>, 'present')" 
                                                        class="w-9 h-9 rounded-xl flex items-center justify-center transition-all shadow-sm active:scale-90 border"
                                                        :class="status === 'present' ? 'bg-emerald-500 text-white shadow-emerald-200 border-transparent' : 'bg-white border-slate-200 text-slate-300 hover:border-emerald-500 hover:text-emerald-500'">
                                                    <i class="ph-bold ph-check text-lg"></i>
                                                </button>
                                                
                                                
                                                <div class="relative" x-data="{ open: false }">
                                                    <button @click="open = !open" @click.outside="open = false"
                                                            class="w-9 h-9 rounded-xl flex items-center justify-center transition-all shadow-sm active:scale-90 border"
                                                            :class="['sick', 'permission', 'alpha'].includes(status) ? 'bg-slate-800 text-white border-transparent' : 'bg-white border-slate-200 text-slate-300 hover:border-slate-800 hover:text-slate-800'">
                                                        <i class="ph-bold ph-dots-three-vertical text-lg"></i>
                                                    </button>
                                                    
                                                    <div x-show="open" style="display: none;" x-transition class="absolute right-0 mt-2 w-36 bg-white rounded-xl shadow-xl border border-slate-100 z-20 py-1 overflow-hidden">
                                                        <button @click="setManual(<?php echo e($student->id); ?>, 'sick'); open=false" class="w-full text-left px-4 py-2.5 text-xs font-bold text-blue-600 hover:bg-blue-50 flex items-center gap-2"><div class="w-1.5 h-1.5 bg-blue-500 rounded-full"></div> Sakit</button>
                                                        <button @click="setManual(<?php echo e($student->id); ?>, 'permission'); open=false" class="w-full text-left px-4 py-2.5 text-xs font-bold text-amber-600 hover:bg-amber-50 flex items-center gap-2"><div class="w-1.5 h-1.5 bg-amber-500 rounded-full"></div> Izin</button>
                                                        <button @click="setManual(<?php echo e($student->id); ?>, 'alpha'); open=false" class="w-full text-left px-4 py-2.5 text-xs font-bold text-rose-600 hover:bg-rose-50 flex items-center gap-2"><div class="w-1.5 h-1.5 bg-rose-500 rounded-full"></div> Alpha</button>
                                                        <div class="border-t border-slate-100 my-1"></div>
                                                        <button @click="setManual(<?php echo e($student->id); ?>, null); open=false" class="w-full text-left px-4 py-2.5 text-xs text-slate-400 hover:bg-slate-50 font-medium">Reset</button>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
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
        // Efek Suara Beep
        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        function playBeep(type) {
            if (audioCtx.state === 'suspended') audioCtx.resume();
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.connect(gain);
            gain.connect(audioCtx.destination);
            if (type === 'success') { osc.type = 'sine'; osc.frequency.setValueAtTime(880, audioCtx.currentTime); } 
            else { osc.type = 'sawtooth'; osc.frequency.setValueAtTime(150, audioCtx.currentTime); }
            gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.3);
            osc.start(); osc.stop(audioCtx.currentTime + 0.3);
        }

        // Konfirmasi Tutup Kelas
        function confirmCloseClass() {
            Swal.fire({
                title: 'Akhiri Sesi?',
                text: "Tutup kelas sekarang?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                confirmButtonText: 'Tutup',
                cancelButtonText: 'Batal'
            }).then((result) => { if (result.isConfirmed) document.getElementById('close-session-form').submit(); })
        }

        document.addEventListener('alpine:init', () => {
            Alpine.data('teachingSession', (config) => ({
                rfidCode: '',
                sessionId: config.sessionId,
                stats: config.stats,
                statusMessage: 'Siap memindai...',
                showCamera: false,
                html5QrcodeScanner: null,
                isScanMode: true, // DEFAULT: Mode Scan Aktif

                showToast(icon, title) {
                    const Toast = Swal.mixin({
                        toast: true, position: 'top-end', showConfirmButton: false, timer: 1500, timerProgressBar: true,
                        didOpen: (toast) => { toast.addEventListener('mouseenter', Swal.stopTimer); toast.addEventListener('mouseleave', Swal.resumeTimer); }
                    })
                    Toast.fire({ icon: icon, title: title })
                },

                // Logic agar scanner tidak "rebutan" fokus saat ngetik Jurnal
                toggleScanMode() {
                    this.isScanMode = !this.isScanMode;
                    if(this.isScanMode) {
                        this.showToast('info', 'Auto Focus ON');
                        this.$nextTick(() => document.getElementById('rfidInput').focus({ preventScroll: true }));
                    } else {
                        this.showToast('info', 'Auto Focus OFF (Mode Ketik)');
                    }
                },

                keepFocus(event) {
                    // HANYA PAKSA FOKUS JIKA MODE SCAN AKTIF & KAMERA MATI
                    if (this.isScanMode && !this.showCamera) {
                        // Jika klik input jurnal, abaikan
                        if (event && event.relatedTarget && event.relatedTarget.classList.contains('journal-input')) return;
                        
                        setTimeout(() => {
                            const input = document.getElementById('rfidInput');
                            if(input) input.focus({ preventScroll: true });
                        }, 100);
                    }
                },

                // Logic Update Manual (AJAX tanpa reload)
                async setManual(studentId, status) {
                    try {
                        const response = await fetch('<?php echo e(route("teaching.manual")); ?>', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                            body: JSON.stringify({ session_id: this.sessionId, student_id: studentId, status: status })
                        });
                        const data = await response.json();
                        
                        if(data.status === 'success') {
                            // Cek jika status direset (data.data bisa null atau status null)
                            const newStatus = data.new_status || null; 
                            
                            // Kita tidak tahu status lama di sini (kecuali disimpan di variabel lokal), 
                            // tapi untuk simplifikasi visual, kita update saja UI-nya.
                            // Statistik mungkin agak tricky jika tidak reload, tapi untuk UX tombol sudah cukup.
                            
                            // Update local stats (simple logic: kurangi semua, tambah yang baru)
                            // Note: Logic akurat butuh state 'oldStatus' dari elemen anak, 
                            // tapi karena ini parent, kita biarkan Alpine anak handle visual row.
                            // Kita refresh page jika ingin statistik 100% akurat atau kirim data via event.

                            // Update UI Baris Siswa menggunakan Custom Event
                            window.dispatchEvent(new CustomEvent('update-status-' + studentId, { detail: { status: newStatus } }));
                            
                            playBeep('success');
                            
                            const statusMap = { 'present': 'HADIR', 'sick': 'SAKIT', 'permission': 'IZIN', 'alpha': 'ALPHA' };
                            const statusText = newStatus ? (statusMap[newStatus] || newStatus.toUpperCase()) : 'DIRESET';

                            this.showToast('success', 'Status: ' + statusText);
                            
                            // Opsional: Reload stats via fetch terpisah jika sangat perlu
                        }
                    } catch (e) { this.showToast('error', 'Gagal update status.'); }
                },

                updateLocalStats(oldStatus, newStatus) {
                    if(oldStatus && this.stats[oldStatus] > 0) this.stats[oldStatus]--;
                    if(newStatus) this.stats[newStatus]++;
                },

                // Logic Scan RFID / QR
                async submitScan() {
                    if(this.rfidCode.length < 3) return;
                    this.statusMessage = 'Memproses...';
                    try {
                        const response = await fetch('<?php echo e(route("teaching.scan")); ?>', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                            body: JSON.stringify({ rfid: this.rfidCode, session_id: this.sessionId })
                        });
                        const data = await response.json();
                        
                        if(data.status === 'success') {
                            this.statusMessage = 'OK: ' + data.student.name;
                            
                            // Update Status Siswa
                            window.dispatchEvent(new CustomEvent('update-status-' + data.student.id, { detail: { status: 'present' } }));
                            
                            // Auto-scroll ke siswa tersebut
                            let rowEl = document.getElementById('student-row-' + data.student.id);
                            if(rowEl) {
                                let alpineEl = Alpine.$data(rowEl);
                                // Update stats manual di client side
                                if(alpineEl) this.updateLocalStats(alpineEl.status, 'present');
                                rowEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            }

                            playBeep('success');
                            Swal.fire({
                                icon: 'success', title: 'Hadir!', text: data.student.name,
                                timer: 1000, showConfirmButton: false, backdrop: `rgba(0,0,0,0.4)`,
                                customClass: { popup: 'rounded-3xl w-[85%] max-w-sm' }
                            });
                        } else if(data.status === 'warning') {
                             this.statusMessage = 'Sudah absen: ' + data.student.name;
                             playBeep('error'); // Beep beda
                             this.showToast('warning', data.student.name + ' sudah absen.');
                        } else {
                            this.statusMessage = 'GAGAL: ' + data.message;
                            playBeep('error');
                            this.showToast('error', data.message);
                        }
                    } catch (error) { this.statusMessage = 'Error koneksi'; }
                    this.rfidCode = '';
                    
                    // Kembalikan fokus jika mode scan aktif
                    if(this.isScanMode) document.getElementById('rfidInput').focus({ preventScroll: true });
                },

                // Logic Kamera HP
                toggleCamera() {
                    this.showCamera = !this.showCamera;
                    if (this.showCamera) this.$nextTick(() => { this.startScanner(); }); else this.stopScanner();
                },

                startScanner() {
                    this.html5QrcodeScanner = new Html5Qrcode("reader");
                    const config = { fps: 10, qrbox: { width: 250, height: 250 } };
                    
                    this.html5QrcodeScanner.start({ facingMode: "environment" }, config,
                        (decodedText) => { 
                             if (!this.loading) { 
                                 this.rfidCode = decodedText; 
                                 this.submitScan();
                                 this.loading = true;
                                 setTimeout(() => { this.loading = false; }, 2000);
                             } 
                        },
                        (errorMessage) => { }
                    ).catch(err => {
                        this.statusMessage = "Error Kamera: Izin ditolak atau HTTPS diperlukan.";
                        Swal.fire('Kamera Error', 'Pastikan Anda menggunakan HTTPS dan memberikan izin kamera.', 'error');
                    });
                },

                stopScanner() {
                    if (this.html5QrcodeScanner) {
                        this.html5QrcodeScanner.stop().then(() => { this.html5QrcodeScanner.clear(); }).catch(err => {});
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\teaching\show.blade.php ENDPATH**/ ?>