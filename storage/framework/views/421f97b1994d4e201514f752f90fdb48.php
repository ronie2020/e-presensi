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

    <div class="py-6 sm:py-8 font-sans text-elevate-text relative overflow-hidden min-h-screen">
        
        
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-10 pointer-events-none -z-10 blur-3xl"></div>

        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 sm:mb-10 relative z-10">
            <div class="relative rounded-[2.5rem] bg-gradient-to-r from-elevate-accent via-elevate-peach-light to-elevate-peach p-8 md:p-10 text-elevate-dark shadow-xl shadow-elevate-accent/20 overflow-hidden border border-white/60">
                
                
                <div class="absolute -top-10 -left-10 w-56 h-56 bg-elevate-primary/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-elevate-peach/40 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-xl"></div>
                <div class="absolute top-10 right-32 w-28 h-28 bg-white/40 rounded-[2rem] rotate-45 pointer-events-none shadow-sm backdrop-blur-md border border-white/50"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                    
                    
                    <div class="max-w-2xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/40 border border-white/60 text-elevate-dark text-[10px] font-black uppercase tracking-widest mb-4 backdrop-blur-sm shadow-sm">
                            <i class="ph-fill ph-broadcast text-elevate-primary"></i> Portal Komunikasi
                        </div>
                        <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-3 flex items-center gap-3 text-elevate-dark leading-tight">
                            Pusat Informasi Sekolah
                        </h1>
                        <p class="text-elevate-dark/80 text-sm md:text-base font-bold leading-relaxed max-w-lg">
                            Kelola pengumuman website, jadwalkan agenda kegiatan, dan kirim notifikasi WhatsApp massal dalam satu dashboard terintegrasi.
                        </p>
                    </div>
                    
                    
                    <div class="w-full md:w-auto mt-4 md:mt-0">
                        <div class="grid grid-cols-2 md:grid-cols-1 lg:grid-cols-2 gap-4">
                            
                            <div class="bg-white/40 backdrop-blur-md px-5 py-5 rounded-2xl border border-white/60 text-center md:text-left shadow-sm hover:bg-white/60 transition-colors group/card">
                                <div class="flex flex-col md:flex-row lg:flex-col items-center justify-center md:justify-start gap-2 mb-1 text-elevate-primary">
                                    <i class="ph-duotone ph-newspaper-clipping text-2xl md:text-xl lg:text-2xl group-hover/card:scale-110 transition-transform"></i>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-elevate-dark/70">Postingan</span>
                                </div>
                                <span class="block text-3xl font-black text-elevate-dark tracking-tight mt-1"><?php echo e($announcements->count()); ?></span>
                            </div>

                            
                            <div class="bg-emerald-50/60 backdrop-blur-md px-5 py-5 rounded-2xl border border-emerald-200/60 text-center md:text-left shadow-sm hover:bg-emerald-100/60 transition-colors">
                                <div class="flex flex-col md:flex-row lg:flex-col items-center justify-center md:justify-start gap-2 mb-1 text-emerald-600">
                                    <i class="ph-duotone ph-whatsapp-logo text-2xl md:text-xl lg:text-2xl"></i>
                                    <span class="text-[10px] font-bold uppercase tracking-wider">Gateway</span>
                                </div>
                                <div class="flex items-center justify-center md:justify-start gap-2 mt-2 md:mt-1 lg:mt-2">
                                    <span class="relative flex h-3 w-3">
                                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                      <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                                    </span>
                                    <span class="text-sm font-black text-emerald-700 tracking-tight">Online</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            
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
                            background: '#f8fafc',
                            color: '#1e293b',
                            iconColor: '#56bbf1',
                            customClass: { popup: 'rounded-2xl border border-slate-100 shadow-xl' }
                        });
                    });
                </script>
            <?php endif; ?>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- CARD 1: PENGUMUMAN WEBSITE (Theme: Elevate Primary) -->
                    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative group hover:shadow-2xl hover:border-elevate-primary/30 transition-all duration-300">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-elevate-primary to-elevate-accent"></div>
                        
                        <div class="p-6 sm:p-8 relative z-10">
                             <div class="flex items-center gap-4 mb-6 pb-4 border-b border-slate-50">
                                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-elevate-peach-light/50 text-elevate-primary rounded-2xl flex items-center justify-center text-2xl sm:text-3xl shadow-sm border border-elevate-peach/30 shrink-0">
                                    <i class="ph-duotone ph-megaphone"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg sm:text-xl font-black text-elevate-dark">Pengumuman Website</h3>
                                    <p class="text-xs font-bold text-elevate-text/60 uppercase tracking-wide mt-1">Publikasi Informasi Sekolah</p>
                                </div>
                            </div>

                            <form action="<?php echo e(route('announcements.store')); ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
                                <?php echo csrf_field(); ?>
                                <div>
                                    <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Judul Pengumuman</label>
                                    <div class="relative">
                                        <i class="ph-bold ph-text-t absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="text" name="title" class="w-full pl-11 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3.5 transition-colors placeholder:font-medium" placeholder="Contoh: Jadwal Libur Awal Ramadhan" required>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Isi Konten</label>
                                    <textarea name="content" rows="4" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 text-sm p-4 text-elevate-dark font-medium transition-all" placeholder="Tulis detail pengumuman di sini..." required></textarea>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Gambar Banner / Pop-up (Opsional)</label>
                                    <input type="file" name="image" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-3 file:px-5 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-elevate-peach-light file:text-elevate-primary hover:file:bg-elevate-peach/30 transition-all border border-slate-200 rounded-2xl bg-slate-50 focus:bg-white p-2">
                                    <p class="text-[10px] text-elevate-text/60 font-bold mt-2 ml-1">Format: JPG/PNG. Jika diisi, gambar ini akan tampil sebagai header Pop-up Halaman Utama.</p>
                                </div>

                                <div class="flex justify-end pt-2">
                                    <button type="submit" class="w-full sm:w-auto py-3.5 px-8 bg-elevate-dark text-white font-bold rounded-xl hover:bg-elevate-primary transition-all shadow-lg shadow-elevate-dark/20 text-sm flex items-center justify-center gap-2 transform active:scale-95">
                                        <i class="ph-bold ph-paper-plane-right text-lg"></i>
                                        Publikasikan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- CARD 2: AGENDA KEGIATAN (Theme: Elevate Peach) -->
                    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative group hover:shadow-2xl hover:border-elevate-peach/50 transition-all duration-300">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-elevate-peach-dark to-elevate-peach"></div>
                        
                        <div class="p-6 sm:p-8 relative z-10">
                            <div class="flex items-center gap-4 mb-6 pb-4 border-b border-slate-50">
                                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-elevate-peach-light/50 text-elevate-peach-dark rounded-2xl flex items-center justify-center text-2xl sm:text-3xl shadow-sm border border-elevate-peach/30 shrink-0">
                                    <i class="ph-duotone ph-calendar-plus"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg sm:text-xl font-black text-elevate-dark">Agenda Kegiatan</h3>
                                    <p class="text-xs font-bold text-elevate-text/60 uppercase tracking-wide mt-1">Jadwal Kalender Akademik</p>
                                </div>
                            </div>

                            <form action="<?php echo e(route('agendas.store')); ?>" method="POST" class="grid grid-cols-1 md:grid-cols-12 gap-5">
                                <?php echo csrf_field(); ?>
                                <div class="md:col-span-5">
                                    <label class="block text-xs font-bold text-elevate-peach-dark uppercase tracking-wider mb-2 ml-1">Nama Kegiatan</label>
                                    <input type="text" name="title" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-elevate-peach focus:ring-elevate-peach/30 font-bold text-elevate-dark py-3.5 placeholder:font-medium transition-all" placeholder="Contoh: UTS Semester Ganjil" required>
                                </div>
                                <div class="md:col-span-3">
                                    <label class="block text-xs font-bold text-elevate-peach-dark uppercase tracking-wider mb-2 ml-1">Tanggal</label>
                                    <input type="date" name="event_date" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-elevate-peach focus:ring-elevate-peach/30 font-bold text-elevate-dark py-3.5 transition-all" required>
                                </div>
                                <div class="md:col-span-4">
                                    <label class="block text-xs font-bold text-elevate-peach-dark uppercase tracking-wider mb-2 ml-1">Lokasi</label>
                                    <div class="flex gap-2">
                                        <input type="text" name="location" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-elevate-peach focus:ring-elevate-peach/30 text-sm py-3.5 font-bold text-elevate-dark transition-all" placeholder="Aula Sekolah">
                                        <button type="submit" class="p-3.5 bg-elevate-dark text-white rounded-2xl hover:bg-elevate-primary transition shadow-lg shadow-elevate-dark/20 active:scale-95 flex-shrink-0 w-14 flex items-center justify-center">
                                            <i class="ph-bold ph-plus text-xl"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <!-- LIST AGENDA AKTIF -->
                            <div class="mt-8 pt-6 border-t border-dashed border-slate-200">
                                <h4 class="text-xs font-black text-elevate-text/50 uppercase tracking-widest mb-4 flex items-center gap-2">
                                    <i class="ph-fill ph-list-dashes"></i> Agenda Mendatang
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <?php $__empty_1 = true; $__currentLoopData = $agendas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agenda): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <div class="flex items-center justify-between p-4 rounded-2xl border border-slate-100 bg-white hover:border-elevate-peach/40 hover:shadow-lg hover:shadow-elevate-peach/10 transition-all group relative overflow-hidden">
                                            <div class="flex items-center gap-4 relative z-10">
                                                <div class="text-center bg-elevate-peach-light/50 p-2.5 rounded-xl border border-elevate-peach/20 min-w-[60px] group-hover:bg-elevate-peach group-hover:border-elevate-peach transition-colors">
                                                    <span class="block text-[10px] text-elevate-peach-dark font-black uppercase group-hover:text-white"><?php echo e($agenda->event_date->format('M')); ?></span>
                                                    <span class="block text-2xl font-black text-elevate-dark leading-none mt-0.5 group-hover:text-white"><?php echo e($agenda->event_date->format('d')); ?></span>
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="text-sm font-bold text-elevate-dark line-clamp-1 group-hover:text-elevate-primary transition-colors" title="<?php echo e($agenda->title); ?>"><?php echo e($agenda->title); ?></p>
                                                    <div class="flex items-center gap-1.5 text-[11px] text-elevate-text/70 mt-1 font-medium">
                                                        <i class="ph-fill ph-map-pin text-elevate-peach-dark"></i>
                                                        <span class="truncate"><?php echo e($agenda->location ?? 'Sekolah'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <form action="<?php echo e(route('agendas.destroy', $agenda->id)); ?>" method="POST" class="delete-agenda-form relative z-10">
                                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                <button type="button" class="btn-delete-agenda w-9 h-9 rounded-xl flex items-center justify-center text-slate-300 hover:text-white hover:bg-rose-500 transition-all bg-slate-50 hover:shadow-md" title="Hapus Agenda">
                                                    <i class="ph-bold ph-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <div class="col-span-full text-center py-8 border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50/50">
                                            <p class="text-xs text-slate-400 font-bold">Belum ada agenda mendatang.</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CARD 3: BROADCAST WA (Theme: Emerald Elevate Style) -->
                    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative group hover:shadow-2xl hover:border-emerald-300 transition-all duration-300">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-emerald-500 to-emerald-400"></div>

                        <div class="p-6 sm:p-8 relative z-10" x-data="{ target: 'class', message: '' }">
                            <div class="flex items-center gap-4 mb-6 pb-4 border-b border-slate-50">
                                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-2xl sm:text-3xl shadow-sm border border-emerald-100 shrink-0">
                                    <i class="ph-duotone ph-whatsapp-logo"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg sm:text-xl font-black text-elevate-dark">Broadcast WhatsApp</h3>
                                    <p class="text-xs font-bold text-emerald-600 uppercase tracking-wide mt-1">Kirim Pesan Massal</p>
                                </div>
                            </div>

                            <form action="<?php echo e(route('announcements.send')); ?>" method="POST" class="space-y-6" id="broadcastForm">
                                <?php echo csrf_field(); ?>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-xs font-bold text-emerald-600 uppercase tracking-wider mb-2 ml-1">Target Penerima</label>
                                        <div class="relative">
                                            <select name="target_type" x-model="target" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-500 focus:ring-emerald-500/30 text-sm py-3.5 px-4 font-bold text-elevate-dark transition-colors cursor-pointer appearance-none">
                                                <option value="class">Per Kelas (Spesifik)</option>
                                                <option value="student">Per Siswa (Personal)</option>
                                                <option value="all">Semua Siswa (Broadcast Akbar)</option>
                                            </select>
                                            <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                        </div>
                                    </div>
                                    
                                    <div x-show="target === 'class'" x-transition>
                                        <label class="block text-xs font-bold text-emerald-600 uppercase tracking-wider mb-2 ml-1">Pilih Kelas</label>
                                        <div class="relative">
                                            <select name="class_id" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-500 focus:ring-emerald-500/30 text-sm py-3.5 px-4 font-bold text-elevate-dark transition-colors appearance-none">
                                                <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($class->id); ?>"><?php echo e($class->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                            <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                        </div>
                                    </div>

                                    <div x-show="target === 'student'" style="display: none;" x-transition>
                                        <label class="block text-xs font-bold text-emerald-600 uppercase tracking-wider mb-2 ml-1">Pilih Siswa</label>
                                        <div class="relative">
                                            <select name="student_id" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-500 focus:ring-emerald-500/30 text-sm py-3.5 px-4 font-bold text-elevate-dark transition-colors appearance-none">
                                                <option value="">-- Cari Nama Siswa --</option>
                                                <?php if(isset($students)): ?>
                                                    <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($student->id); ?>"><?php echo e($student->name); ?> (<?php echo e($student->schoolClass->name ?? '-'); ?>)</option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>
                                            </select>
                                            <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-emerald-600 uppercase tracking-wider mb-2 ml-1">Template Cepat</label>
                                    <div class="relative">
                                        <select @change="message = $event.target.value" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-500 focus:ring-emerald-500/30 text-sm py-3.5 px-4 font-bold text-elevate-dark transition-colors cursor-pointer appearance-none">
                                            <option value="">-- Pilih Template Pesan --</option>
                                            <?php $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($val); ?>"><?php echo e($key); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-emerald-600 uppercase tracking-wider mb-2 ml-1">Isi Pesan</label>
                                    <textarea name="message" x-model="message" rows="4" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-500 focus:ring-emerald-500/30 text-sm p-4 text-elevate-dark font-medium transition-all" placeholder="Halo Bapak/Ibu, kami menginformasikan bahwa..." required></textarea>
                                </div>

                                <div class="flex justify-end pt-2">
                                    <button type="button" id="btn-broadcast" class="w-full sm:w-auto py-3.5 px-8 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-600/20 text-sm flex items-center justify-center gap-2 transform active:scale-95">
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
                        <div class="p-6 border-b border-slate-50 flex items-center justify-between bg-elevate-peach-light/30 z-10">
                            <h3 class="text-lg font-black text-elevate-dark flex items-center gap-2">
                                <i class="ph-duotone ph-newspaper-clipping text-elevate-primary"></i> Feed Publik
                            </h3>
                            <span class="bg-white text-[10px] font-black px-3 py-1.5 rounded-full text-elevate-primary border border-slate-200 uppercase tracking-wide shadow-sm"><?php echo e($announcements->count()); ?> Post</span>
                        </div>
                        
                        <div class="p-0 overflow-y-auto flex-1 custom-scrollbar">
                            <div class="divide-y divide-slate-100">
                                <?php $__empty_1 = true; $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $announce): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <div class="p-6 hover:bg-slate-50/80 transition-all group relative">

                                        <!-- Header Post -->
                                        <div class="flex items-center justify-between mb-3">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-elevate-primary to-elevate-dark flex items-center justify-center text-[11px] font-bold text-white shadow-sm border-2 border-white">
                                                    <?php echo e(substr($announce->author->name ?? 'A', 0, 1)); ?>

                                                </div>
                                                <div>
                                                    <span class="block text-[11px] font-black text-elevate-dark uppercase tracking-wide"><?php echo e($announce->author->name ?? 'Admin'); ?></span>
                                                    <span class="block text-[10px] text-elevate-text/60 font-medium"><?php echo e($announce->created_at->diffForHumans()); ?></span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Content -->
                                        <div class="pl-12">
                                             <h4 class="font-bold text-elevate-dark mb-2 text-sm leading-snug group-hover:text-elevate-primary transition-colors">
                                                <?php echo e($announce->title); ?>

                                                <?php if(!empty($announce->image)): ?>
                                                    <span class="inline-block ml-1 align-middle text-[9px] bg-elevate-peach-light text-elevate-primary px-1.5 py-0.5 rounded uppercase font-black border border-elevate-peach/30"><i class="ph-bold ph-image"></i> Berfoto</span>
                                                <?php endif; ?>
                                            </h4>
                                            <p class="text-xs text-elevate-text/80 line-clamp-3 leading-relaxed mb-4 font-medium">
                                                <?php echo e(Str::limit(strip_tags($announce->content), 120)); ?>

                                            </p>
                                            
                                            <!-- Actions -->
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-2">
                                                    <span class="text-[10px] font-bold text-elevate-primary flex items-center gap-1 bg-elevate-peach-light px-2.5 py-1 rounded-lg border border-elevate-peach/20">
                                                        <i class="ph-bold ph-calendar-blank"></i> <?php echo e($announce->created_at->format('d M Y')); ?>

                                                    </span>
                                                </div>
                                                
                                                <form action="<?php echo e(route('announcements.destroy', $announce->id)); ?>" method="POST" class="delete-announce-form">
                                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                    <button type="button" class="btn-delete-announce w-8 h-8 flex items-center justify-center rounded-xl text-slate-300 hover:text-white hover:bg-rose-500 transition-all opacity-100 lg:opacity-0 group-hover:opacity-100 bg-slate-50 hover:shadow-md" title="Hapus Post">
                                                        <i class="ph-bold ph-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <div class="text-center py-20 px-6">
                                        <div class="w-20 h-20 bg-elevate-peach-light rounded-2xl flex items-center justify-center mx-auto mb-4 text-elevate-primary border border-elevate-peach/30">
                                            <i class="ph-duotone ph-article text-4xl"></i>
                                        </div>
                                        <p class="text-sm font-bold text-elevate-dark">Belum ada pengumuman.</p>
                                        <p class="text-xs text-elevate-text/60 mt-1 font-medium">Buat pengumuman pertama Anda di form sebelah kiri.</p>
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
                        confirmButtonColor: '#e11d48', // Rose-600 untuk delete
                        cancelButtonColor: '#94a3b8', // Slate-400
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        borderRadius: '1.5rem',
                        customClass: {
                            popup: 'rounded-[2rem] border border-slate-100 shadow-2xl',
                            confirmButton: 'rounded-xl px-6 py-2.5 font-bold shadow-lg shadow-rose-500/20',
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
                        cancelButtonColor: '#94a3b8', // Slate-400
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        borderRadius: '1.5rem',
                        customClass: {
                            popup: 'rounded-[2rem] border border-slate-100 shadow-2xl',
                            confirmButton: 'rounded-xl px-6 py-2.5 font-bold shadow-lg shadow-rose-500/20',
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
                        cancelButtonColor: '#94a3b8',
                        confirmButtonText: 'Kirim Sekarang',
                        cancelButtonText: 'Batal',
                        borderRadius: '1.5rem',
                        customClass: {
                            popup: 'rounded-[2rem] border border-slate-100 shadow-2xl',
                            confirmButton: 'rounded-xl px-6 py-2.5 font-bold shadow-lg shadow-emerald-500/20',
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