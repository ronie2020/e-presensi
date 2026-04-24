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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <div class="py-6 sm:py-8 font-sans text-slate-800">
        
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 sm:mb-10">
            <div class="relative rounded-[2.5rem] bg-gray-900 bg-gradient-to-br from-blue-900 via-blue-800 to-slate-900 p-8 sm:p-10 text-white shadow-2xl shadow-blue-900/40 overflow-hidden border border-white/10 group">
                
                
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-80 h-80 bg-blue-500/20 rounded-full blur-3xl pointer-events-none group-hover:bg-blue-500/30 transition-all duration-700"></div>
                <div class="absolute bottom-0 right-20 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                    
                    
                    <div class="max-w-2xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-200 text-[10px] font-bold uppercase tracking-widest mb-4 backdrop-blur-sm">
                            <i class="ph-fill ph-broadcast"></i> Portal Komunikasi
                        </div>
                        <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-3 flex items-center gap-3 text-white leading-tight">
                            Pusat Informasi Sekolah
                        </h1>
                        <p class="text-blue-100/80 text-sm md:text-base font-medium leading-relaxed max-w-lg">
                            Kelola pengumuman website, jadwalkan agenda kegiatan, dan kirim notifikasi WhatsApp massal dalam satu dashboard terintegrasi.
                        </p>
                    </div>
                    
                    
                    <div class="w-full md:w-auto mt-4 md:mt-0">
                        <div class="grid grid-cols-2 md:grid-cols-1 lg:grid-cols-2 gap-4">
                            
                            <div class="bg-white/10 backdrop-blur-md px-5 py-5 rounded-2xl border border-white/10 text-center md:text-left hover:bg-white/15 transition-colors group/card">
                                <div class="flex flex-col md:flex-row lg:flex-col items-center justify-center md:justify-start gap-2 mb-1 text-blue-300">
                                    <i class="ph-duotone ph-newspaper-clipping text-2xl md:text-xl lg:text-2xl group-hover/card:scale-110 transition-transform"></i>
                                    <span class="text-[10px] font-bold uppercase tracking-wider">Postingan</span>
                                </div>
                                <span class="block text-3xl font-black text-white tracking-tight mt-1"><?php echo e($announcements->count()); ?></span>
                            </div>

                            
                            <div class="bg-emerald-500/20 backdrop-blur-md px-5 py-5 rounded-2xl border border-emerald-400/20 text-center md:text-left hover:bg-emerald-500/30 transition-colors">
                                <div class="flex flex-col md:flex-row lg:flex-col items-center justify-center md:justify-start gap-2 mb-1 text-emerald-300">
                                    <i class="ph-duotone ph-whatsapp-logo text-2xl md:text-xl lg:text-2xl"></i>
                                    <span class="text-[10px] font-bold uppercase tracking-wider">Gateway</span>
                                </div>
                                <div class="flex items-center justify-center md:justify-start gap-2 mt-2 md:mt-1 lg:mt-2">
                                    <span class="relative flex h-3 w-3">
                                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                      <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                                    </span>
                                    <span class="text-sm font-black text-white tracking-tight">Online</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
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
                            position: 'top-end',
                            background: '#ecfdf5',
                            color: '#064e3b',
                            iconColor: '#10b981'
                        });
                    });
                </script>
            <?php endif; ?>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- CARD 1: PENGUMUMAN WEBSITE -->
                    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative group hover:shadow-2xl hover:shadow-blue-900/10 transition-all duration-300">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-600 to-blue-400"></div>
                        
                        <div class="p-6 sm:p-8 relative z-10">
                             <div class="flex items-center gap-4 mb-6 pb-4 border-b border-slate-50">
                                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-2xl sm:text-3xl shadow-sm border border-blue-100 shrink-0">
                                    <i class="ph-duotone ph-megaphone"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg sm:text-xl font-black text-slate-800">Pengumuman Website</h3>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mt-1">Publikasi Informasi Sekolah</p>
                                </div>
                            </div>

                            <!-- PERBAIKAN: Tambahkan enctype="multipart/form-data" untuk mengizinkan upload gambar -->
                            <form action="<?php echo e(route('announcements.store')); ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
                                <?php echo csrf_field(); ?>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Judul Pengumuman</label>
                                    <div class="relative">
                                        <i class="ph-bold ph-text-t absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="text" name="title" class="w-full pl-11 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 font-bold text-slate-800 py-3 transition-colors placeholder:font-normal" placeholder="Contoh: Jadwal Libur Awal Ramadhan" required>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Isi Konten</label>
                                    <textarea name="content" rows="4" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm p-4 text-slate-700 font-medium" placeholder="Tulis detail pengumuman di sini..." required></textarea>
                                </div>

                                <!-- TAMBAHAN: Input Upload Gambar untuk Pop-up -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Gambar Banner / Pop-up (Opsional)</label>
                                    <input type="file" name="image" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all border border-slate-200 rounded-2xl bg-slate-50 focus:bg-white p-2">
                                    <p class="text-[10px] text-slate-400 mt-1 ml-1">Format: JPG/PNG. Jika diisi, gambar ini akan tampil sebagai header Pop-up Halaman Utama.</p>
                                </div>

                                <div class="flex justify-end pt-2">
                                    <button type="submit" class="w-full sm:w-auto py-3 px-8 bg-blue-900 text-white font-bold rounded-xl hover:bg-blue-800 transition-all shadow-lg shadow-blue-900/20 text-sm flex items-center justify-center gap-2 transform active:scale-95">
                                        <i class="ph-bold ph-paper-plane-right text-lg"></i>
                                        Publikasikan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- CARD 2: AGENDA KEGIATAN -->
                    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative group hover:shadow-2xl hover:shadow-purple-900/10 transition-all duration-300">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-purple-600 to-purple-400"></div>
                        
                        <div class="p-6 sm:p-8 relative z-10">
                            <div class="flex items-center gap-4 mb-6 pb-4 border-b border-slate-50">
                                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center text-2xl sm:text-3xl shadow-sm border border-purple-100 shrink-0">
                                    <i class="ph-duotone ph-calendar-plus"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg sm:text-xl font-black text-slate-800">Agenda Kegiatan</h3>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mt-1">Jadwal Kalender Akademik</p>
                                </div>
                            </div>

                            <form action="<?php echo e(route('agendas.store')); ?>" method="POST" class="grid grid-cols-1 md:grid-cols-12 gap-5">
                                <?php echo csrf_field(); ?>
                                <div class="md:col-span-5">
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Nama Kegiatan</label>
                                    <input type="text" name="title" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-purple-600 focus:ring-purple-600 font-bold text-slate-800 py-3 placeholder:font-normal" placeholder="Contoh: UTS Semester Ganjil" required>
                                </div>
                                <div class="md:col-span-3">
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Tanggal</label>
                                    <input type="date" name="event_date" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-purple-600 focus:ring-purple-600 font-bold text-slate-800 py-3" required>
                                </div>
                                <div class="md:col-span-4">
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Lokasi</label>
                                    <div class="flex gap-2">
                                        <input type="text" name="location" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-purple-600 focus:ring-purple-600 text-sm py-3 font-bold text-slate-700" placeholder="Aula Sekolah">
                                        <button type="submit" class="p-3 bg-purple-600 text-white rounded-2xl hover:bg-purple-700 transition shadow-lg shadow-purple-500/20 active:scale-95 flex-shrink-0 w-12 flex items-center justify-center">
                                            <i class="ph-bold ph-plus text-xl"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <!-- LIST AGENDA AKTIF -->
                            <div class="mt-8 pt-6 border-t border-dashed border-slate-200">
                                <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                    <i class="ph-fill ph-list-dashes"></i> Agenda Mendatang
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <?php $__empty_1 = true; $__currentLoopData = $agendas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agenda): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <div class="flex items-center justify-between p-4 rounded-2xl border border-slate-100 bg-white hover:border-purple-200 hover:shadow-lg hover:shadow-purple-500/5 transition-all group relative overflow-hidden">
                                            <div class="flex items-center gap-4 relative z-10">
                                                <div class="text-center bg-purple-50 p-2.5 rounded-xl border border-purple-100 min-w-[60px]">
                                                    <span class="block text-[10px] text-purple-400 font-black uppercase"><?php echo e($agenda->event_date->format('M')); ?></span>
                                                    <span class="block text-2xl font-black text-purple-700 leading-none mt-0.5"><?php echo e($agenda->event_date->format('d')); ?></span>
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="text-sm font-bold text-slate-700 line-clamp-1 group-hover:text-purple-700 transition-colors" title="<?php echo e($agenda->title); ?>"><?php echo e($agenda->title); ?></p>
                                                    <div class="flex items-center gap-1.5 text-[11px] text-slate-500 mt-1 font-medium">
                                                        <i class="ph-fill ph-map-pin text-purple-400"></i>
                                                        <span class="truncate"><?php echo e($agenda->location ?? 'Sekolah'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <form action="<?php echo e(route('agendas.destroy', $agenda->id)); ?>" method="POST" class="delete-agenda-form relative z-10">
                                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                <button type="button" class="btn-delete-agenda w-8 h-8 rounded-xl flex items-center justify-center text-slate-300 hover:text-white hover:bg-rose-500 transition-all" title="Hapus Agenda">
                                                    <i class="ph-bold ph-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <div class="col-span-full text-center py-8 border-2 border-dashed border-slate-100 rounded-2xl bg-slate-50/50">
                                            <p class="text-xs text-slate-400 font-bold">Belum ada agenda mendatang.</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CARD 3: BROADCAST WA -->
                    <div class="rounded-[2.5rem] bg-gradient-to-br from-emerald-600 to-green-700 shadow-xl shadow-emerald-500/30 text-white overflow-hidden relative group border border-emerald-500/20">
                        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                        <div class="absolute -right-20 -top-20 w-64 h-64 bg-emerald-400/20 rounded-full blur-3xl pointer-events-none"></div>

                        <div class="p-6 sm:p-8 relative z-10" x-data="{ target: 'class', message: '' }">
                            <div class="flex items-center gap-4 mb-8 border-b border-emerald-500/30 pb-6">
                                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-white/10 backdrop-blur-md text-white rounded-2xl flex items-center justify-center text-2xl sm:text-3xl shadow-inner border border-white/20 shrink-0">
                                    <i class="ph-fill ph-whatsapp-logo"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg sm:text-xl font-black text-white">Broadcast WhatsApp</h3>
                                    <p class="text-xs font-bold text-emerald-200 uppercase tracking-wide mt-1">Kirim Pesan Massal</p>
                                </div>
                            </div>

                            <form action="<?php echo e(route('announcements.send')); ?>" method="POST" class="space-y-6" id="broadcastForm">
                                <?php echo csrf_field(); ?>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-xs font-bold text-emerald-200 uppercase tracking-wider mb-2 ml-1">Target Penerima</label>
                                        <div class="relative">
                                            <select name="target_type" x-model="target" class="w-full rounded-2xl border-emerald-500/50 bg-emerald-800/50 focus:bg-emerald-900 focus:border-emerald-400 focus:ring-emerald-400 text-sm py-3 px-4 font-bold text-white transition-colors cursor-pointer appearance-none">
                                                <option value="class">Per Kelas (Spesifik)</option>
                                                <option value="student">Per Siswa (Personal)</option>
                                                <option value="all">Semua Siswa (Broadcast Akbar)</option>
                                            </select>
                                            <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-emerald-300 pointer-events-none"></i>
                                        </div>
                                    </div>
                                    
                                    <div x-show="target === 'class'" x-transition>
                                        <label class="block text-xs font-bold text-emerald-200 uppercase tracking-wider mb-2 ml-1">Pilih Kelas</label>
                                        <div class="relative">
                                            <select name="class_id" class="w-full rounded-2xl border-emerald-500/50 bg-white text-slate-800 focus:bg-white focus:border-emerald-400 focus:ring-emerald-400 text-sm py-3 px-4 font-bold transition-colors appearance-none">
                                                <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($class->id); ?>"><?php echo e($class->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                            <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 pointer-events-none"></i>
                                        </div>
                                    </div>

                                    <div x-show="target === 'student'" style="display: none;" x-transition>
                                        <label class="block text-xs font-bold text-emerald-200 uppercase tracking-wider mb-2 ml-1">Pilih Siswa</label>
                                        <div class="relative">
                                            <select name="student_id" class="w-full rounded-2xl border-emerald-500/50 bg-white text-slate-800 focus:bg-white focus:border-emerald-400 focus:ring-emerald-400 text-sm py-3 px-4 font-bold transition-colors appearance-none">
                                                <option value="">-- Cari Nama Siswa --</option>
                                                <?php if(isset($students)): ?>
                                                    <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($student->id); ?>"><?php echo e($student->name); ?> (<?php echo e($student->schoolClass->name ?? '-'); ?>)</option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>
                                            </select>
                                            <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 pointer-events-none"></i>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-emerald-200 uppercase tracking-wider mb-2 ml-1">Template Cepat</label>
                                    <div class="relative">
                                        <select @change="message = $event.target.value" class="w-full rounded-2xl border-emerald-500/50 bg-emerald-800/50 focus:bg-emerald-900 focus:border-emerald-400 focus:ring-emerald-400 text-sm py-3 px-4 font-bold text-emerald-100 transition-colors cursor-pointer appearance-none">
                                            <option value="">-- Pilih Template Pesan --</option>
                                            <?php $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($val); ?>"><?php echo e($key); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-emerald-300 pointer-events-none"></i>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-emerald-200 uppercase tracking-wider mb-2 ml-1">Isi Pesan</label>
                                    <textarea name="message" x-model="message" rows="4" class="w-full rounded-2xl border-emerald-500/50 bg-black/20 focus:bg-black/30 focus:border-emerald-400 focus:ring-emerald-400 text-sm p-4 text-white font-medium placeholder-emerald-200/50 backdrop-blur-sm" placeholder="Halo Bapak/Ibu, kami menginformasikan bahwa..." required></textarea>
                                </div>

                                <div class="flex justify-end pt-2">
                                    <button type="button" id="btn-broadcast" class="w-full sm:w-auto py-3 px-8 bg-white text-emerald-700 font-black rounded-xl hover:bg-emerald-50 transition-all shadow-lg text-sm flex items-center justify-center gap-2 transform active:scale-95">
                                        <i class="ph-bold ph-paper-plane-tilt text-lg"></i>
                                        Kirim Pesan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>

                
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden flex flex-col lg:sticky lg:top-24 h-auto lg:h-[calc(100vh-6rem)]">
                        <div class="p-6 border-b border-slate-50 flex items-center justify-between bg-white z-10">
                            <h3 class="text-lg font-black text-slate-800 flex items-center gap-2">
                                <i class="ph-duotone ph-newspaper-clipping text-blue-900"></i> Feed Publik
                            </h3>
                            <span class="bg-blue-50 text-[10px] font-black px-2.5 py-1 rounded-full text-blue-600 border border-blue-100 uppercase tracking-wide"><?php echo e($announcements->count()); ?> Post</span>
                        </div>
                        
                        <div class="p-0 overflow-y-auto flex-1 custom-scrollbar">
                            <div class="divide-y divide-slate-50">
                                <?php $__empty_1 = true; $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $announce): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <div class="p-6 hover:bg-slate-50/80 transition-all group relative">

                                        <!-- Header Post -->
                                        <div class="flex items-center justify-between mb-3">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-900 to-slate-800 flex items-center justify-center text-[10px] font-bold text-white shadow-sm border border-white">
                                                    <?php echo e(substr($announce->author->name ?? 'A', 0, 1)); ?>

                                                </div>
                                                <div>
                                                    <span class="block text-[11px] font-black text-slate-800 uppercase tracking-wide"><?php echo e($announce->author->name ?? 'Admin'); ?></span>
                                                    <span class="block text-[10px] text-slate-400 font-medium"><?php echo e($announce->created_at->diffForHumans()); ?></span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Content -->
                                        <div class="pl-11">
                                             <h4 class="font-bold text-slate-800 mb-2 text-sm leading-snug group-hover:text-blue-900 transition-colors">
                                                <?php echo e($announce->title); ?>

                                                <!-- TAMBAHAN: Indikator jika postingan memiliki gambar -->
                                                <?php if(!empty($announce->image)): ?>
                                                    <span class="inline-block ml-1 align-middle text-[9px] bg-blue-100 text-blue-600 px-1.5 py-0.5 rounded uppercase font-black"><i class="ph-bold ph-image"></i> Berfoto</span>
                                                <?php endif; ?>
                                            </h4>
                                            <p class="text-xs text-slate-500 line-clamp-3 leading-relaxed mb-4 font-medium">
                                                <?php echo e(Str::limit(strip_tags($announce->content), 120)); ?>

                                            </p>
                                            
                                            <!-- Actions -->
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-2">
                                                    <span class="text-[10px] font-bold text-slate-400 flex items-center gap-1 bg-slate-50 px-2 py-1 rounded-lg">
                                                        <i class="ph-bold ph-calendar-blank"></i> <?php echo e($announce->created_at->format('d M Y')); ?>

                                                    </span>
                                                </div>
                                                
                                                <form action="<?php echo e(route('announcements.destroy', $announce->id)); ?>" method="POST" class="delete-announce-form">
                                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                    <button type="button" class="btn-delete-announce w-8 h-8 flex items-center justify-center rounded-xl text-slate-300 hover:text-white hover:bg-rose-500 transition-all opacity-100 lg:opacity-0 group-hover:opacity-100" title="Hapus Post">
                                                        <i class="ph-bold ph-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <div class="text-center py-20 px-6">
                                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300 border border-slate-100">
                                            <i class="ph-duotone ph-article text-4xl"></i>
                                        </div>
                                        <p class="text-sm font-bold text-slate-600">Belum ada pengumuman.</p>
                                        <p class="text-xs text-slate-400 mt-1 font-medium">Buat pengumuman pertama Anda di form sebelah kiri.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // 1. Handle Delete Agenda
            const deleteAgendaButtons = document.querySelectorAll('.btn-delete-agenda');
            deleteAgendaButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    const form = this.closest('.delete-agenda-form');
                    Swal.fire({
                        title: 'Hapus Agenda?',
                        text: "Jadwal kegiatan ini akan dihapus permanen.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#9333ea', // Purple-600
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
                        if (result.isConfirmed) form.submit();
                    });
                });
            });

            // 2. Handle Delete Pengumuman
            const deleteAnnounceButtons = document.querySelectorAll('.btn-delete-announce');
            deleteAnnounceButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    const form = this.closest('.delete-announce-form');
                    Swal.fire({
                        title: 'Hapus Postingan?',
                        text: "Pengumuman ini akan dihapus dari feed publik.",
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
                        if (result.isConfirmed) form.submit();
                    });
                });
            });

            // 3. Handle Broadcast Confirm
            const btnBroadcast = document.getElementById('btn-broadcast');
            if(btnBroadcast){
                btnBroadcast.addEventListener('click', function() {
                    const form = document.getElementById('broadcastForm');
                    Swal.fire({
                        title: 'Kirim Broadcast?',
                        text: "Pesan WhatsApp akan dikirim ke antrian gateway.",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#059669', // Emerald-600
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Kirim Sekarang',
                        cancelButtonText: 'Batal',
                        borderRadius: '1.5rem',
                        customClass: {
                            popup: 'rounded-[2rem]',
                            confirmButton: 'rounded-xl px-6 py-2.5 font-bold',
                            cancelButton: 'rounded-xl px-6 py-2.5 font-bold'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) form.submit();
                    });
                });
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/announcements/index.blade.php ENDPATH**/ ?>