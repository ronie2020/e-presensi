<?php $__env->startSection('content'); ?>
    
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        
        /* Modifikasi warna radio card ke Elevate Theme */
        .radio-card:checked + div { border-color: #3b5889; background-color: #f8fafc; color: #032b5b; transform: scale(0.98); }
        .radio-card:checked + div .check-icon { opacity: 1; transform: scale(1); }
    </style>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 pb-20 pt-24 relative z-10" 
         x-data="{ 
            isAnonymous: false,
            category: '',
            previewUrl: null,
            fileChosen(event) {
                const file = event.target.files[0];
                if (file) { this.previewUrl = URL.createObjectURL(file); }
            }
         }">

        
        <div class="animate-enter relative rounded-[3rem] bg-elevate-gradient-main p-8 md:p-12 mb-10 text-elevate-dark shadow-xl shadow-elevate-accent/10 overflow-hidden border border-white/60">
            <div class="absolute top-0 right-0 -mt-20 -mr-20 w-[400px] h-[400px] bg-white/40 rounded-full blur-[80px] opacity-60"></div>
            <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-[300px] h-[300px] bg-elevate-peach/30 rounded-full blur-[80px]"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                <div>
                    <a href="<?php echo e(route('student.complaints.index')); ?>" class="inline-flex items-center gap-2 text-slate-500 hover:text-elevate-primary transition-colors mb-6 text-[10px] font-black uppercase tracking-[0.2em]">
                        <i class="ph-bold ph-arrow-left"></i> Kembali ke Riwayat
                    </a>
                    <h1 class="text-3xl md:text-5xl font-black tracking-tighter mb-4 leading-tight text-elevate-dark">
                        Buat Laporan <br class="md:hidden"><span class="text-transparent bg-clip-text bg-gradient-to-r from-elevate-primary to-elevate-accent">Keamanan</span>
                    </h1>
                    <p class="text-elevate-dark/80 text-sm md:text-base max-w-xl leading-relaxed font-medium">
                        Identitasmu adalah prioritas kami. Gunakan fitur <span class="text-elevate-dark font-black bg-white/60 px-3 py-1 rounded-xl border border-white mx-1 shadow-sm">Anonim</span> jika kamu merasa tidak nyaman menampilkan nama.
                    </p>
                </div>
                
                <div class="hidden md:flex w-24 h-24 bg-white/50 backdrop-blur-xl rounded-[2rem] items-center justify-center border border-white shadow-lg shadow-elevate-accent/10 group transition-transform hover:scale-110">
                    <i class="ph-duotone ph-shield-check text-5xl text-elevate-primary"></i>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 animate-enter" style="animation-delay: 100ms">
            
            
            <div class="lg:col-span-8">
                <form action="<?php echo e(route('student.complaints.store')); ?>" method="POST" enctype="multipart/form-data" class="bg-white p-8 md:p-12 rounded-[3rem] shadow-sm border border-slate-100 relative overflow-hidden group hover:border-elevate-accent/30 transition-colors">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-elevate-accent to-elevate-primary"></div>
                    <?php echo csrf_field(); ?>
                    
                    <?php if($errors->any()): ?>
                        <div class="mb-8 bg-rose-50 border border-rose-100 rounded-[1.8rem] p-6 flex gap-4 items-start animate-pulse">
                            <i class="ph-fill ph-warning-circle text-rose-500 text-2xl shrink-0 mt-0.5"></i>
                            <div>
                                <h4 class="text-sm font-black text-rose-800 uppercase tracking-tight">Ada Kendala Pengisian</h4>
                                <ul class="text-xs text-rose-600 mt-2 list-disc list-inside font-medium leading-relaxed">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>

                    
                    <div class="mb-12 mt-4">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6 ml-1">Kategori Masalah <span class="text-rose-500">*</span></label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <?php
                                $categories = [
                                    ['val' => 'Bullying', 'label' => 'Perundungan', 'icon' => 'ph-mask-sad', 'color' => 'rose'],
                                    ['val' => 'Kehilangan', 'label' => 'Kehilangan', 'icon' => 'ph-magnifying-glass', 'color' => 'amber'],
                                    ['val' => 'Fasilitas', 'label' => 'Fasilitas', 'icon' => 'ph-wrench', 'color' => 'emerald'],
                                    ['val' => 'Lainnya', 'label' => 'Lainnya', 'icon' => 'ph-dots-three-circle', 'color' => 'slate'],
                                ];
                            ?>
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="cursor-pointer group/cat">
                                <input type="radio" name="category" value="<?php echo e($cat['val']); ?>" class="radio-card hidden" x-model="category" required>
                                <div class="border-2 border-slate-50 rounded-[1.8rem] p-6 flex flex-col items-center justify-center gap-3 hover:border-<?php echo e($cat['color']); ?>-200 hover:bg-<?php echo e($cat['color']); ?>-50 transition-all h-full relative overflow-hidden shadow-sm">
                                    <i class="ph-duotone <?php echo e($cat['icon']); ?> text-4xl text-<?php echo e($cat['color']); ?>-500 group-hover/cat:scale-110 transition-transform"></i>
                                    <span class="text-[10px] font-black text-slate-600 uppercase tracking-widest group-hover/cat:text-<?php echo e($cat['color']); ?>-700"><?php echo e($cat['label']); ?></span>
                                    <div class="check-icon absolute top-3 right-3 opacity-0 transition-all duration-300 transform scale-50">
                                        <i class="ph-fill ph-check-circle text-elevate-primary text-xl"></i>
                                    </div>
                                </div>
                            </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <div class="space-y-3">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Waktu Kejadian</label>
                            <input type="date" name="incident_date" required max="<?php echo e(date('Y-m-d')); ?>" class="w-full bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:ring-4 focus:ring-elevate-accent/20 focus:border-elevate-primary py-4 px-5 font-bold text-elevate-dark transition-all">
                        </div>
                        <div class="space-y-3">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Titik Lokasi</label>
                            <div class="relative">
                                <i class="ph-bold ph-map-pin absolute left-5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input type="text" name="location" placeholder="Cth: Belakang Kantin" required class="w-full pl-12 pr-5 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:ring-4 focus:ring-elevate-accent/20 focus:border-elevate-primary font-bold text-elevate-dark transition-all">
                            </div>
                        </div>
                    </div>

                    <div class="mb-8 space-y-3">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Ceritakan Secara Detail</label>
                        <textarea name="description" rows="6" required placeholder="Tuliskan kronologi kejadian sejujur-jujurnya..." class="w-full bg-slate-50 border-transparent rounded-[2rem] focus:bg-white focus:ring-4 focus:ring-elevate-accent/20 focus:border-elevate-primary p-6 text-sm font-medium text-elevate-dark leading-relaxed transition-all placeholder:text-slate-400"></textarea>
                    </div>

                    
                    <div class="mb-10">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-4">Bukti Pendukung (Opsional)</label>
                        <div class="flex items-center justify-center w-full">
                            <label for="evidence" class="flex flex-col items-center justify-center w-full h-48 border-2 border-slate-200 border-dashed rounded-[2.5rem] cursor-pointer bg-slate-50/50 hover:bg-elevate-accent/5 hover:border-elevate-primary transition-all group/upload overflow-hidden relative shadow-inner">
                                <img x-show="previewUrl" :src="previewUrl" class="absolute inset-0 w-full h-full object-cover z-10 transition-transform group-hover/upload:scale-105">
                                <div class="flex flex-col items-center justify-center relative z-20" :class="previewUrl ? 'bg-white/80 p-6 rounded-3xl backdrop-blur-md shadow-xl' : ''">
                                    <i class="ph-duotone ph-camera-plus text-4xl text-slate-300 group-hover/upload:text-elevate-primary mb-3 transition-colors"></i>
                                    <p class="text-[11px] text-slate-500 font-black uppercase tracking-widest"><span class="text-elevate-primary">Pilih Foto</span> Bukti</p>
                                </div>
                                <input id="evidence" name="evidence" type="file" class="hidden" accept="image/*" @change="fileChosen">
                            </label>
                        </div>
                    </div>

                    <div class="pt-10 border-t border-slate-50 flex flex-col md:flex-row items-center justify-between gap-6">
                        <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest italic leading-relaxed text-center md:text-left">
                            <i class="ph-fill ph-warning-circle text-amber-500 text-sm"></i> Laporan palsu dapat merugikan dirimu sendiri.
                        </p>
                        <button type="submit" class="w-full md:w-auto bg-elevate-dark hover:bg-elevate-primary text-white px-12 py-5 rounded-[1.8rem] font-black shadow-lg shadow-elevate-dark/20 transition-all hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-3 uppercase tracking-[0.15em] text-[10px]">
                            Kirim Laporan
                            <i class="ph-bold ph-paper-plane-right text-lg"></i>
                        </button>
                    </div>
                </form>
            </div>

            
            <div class="lg:col-span-4 space-y-6">
                
                
                <div class="bg-white p-8 rounded-[3rem] shadow-sm border border-slate-100 transition-all duration-500 relative overflow-hidden">
                    
                    <div class="absolute inset-0 bg-elevate-dark transition-transform duration-700 ease-in-out z-0 origin-bottom"
                         :class="isAnonymous ? 'scale-y-100' : 'scale-y-0'"></div>
                    
                    <div class="relative z-10">
                        <h3 class="font-black mb-8 flex items-center gap-3 text-sm uppercase tracking-widest" :class="isAnonymous ? 'text-elevate-accent' : 'text-elevate-dark'">
                            <i class="ph-fill ph-mask-spy text-2xl"></i> Identitas Pelapor
                        </h3>

                        <div class="flex items-center gap-5 mb-10">
                            <div class="w-16 h-16 rounded-[1.5rem] border-2 flex items-center justify-center shrink-0 transition-all duration-500 shadow-inner"
                                 :class="isAnonymous ? 'bg-white/10 border-white/10 text-slate-300' : 'bg-elevate-accent/10 border-elevate-accent/20 text-elevate-primary'">
                                <i class="ph-duotone text-4xl" :class="isAnonymous ? 'ph-spy' : 'ph-user-focus'"></i>
                            </div>
                            <div>
                                <p class="text-[9px] font-black uppercase tracking-widest mb-1" :class="isAnonymous ? 'text-slate-400' : 'text-slate-400'">Profil Kamu</p>
                                <p class="text-xl font-black transition-all duration-500 tracking-tight" :class="isAnonymous ? 'text-white italic' : 'text-elevate-dark'">
                                    <span x-text="isAnonymous ? 'RAHASIA (Anonim)' : '<?php echo e(Auth::user()->name); ?>'"></span>
                                </p>
                            </div>
                        </div>

                        <div class="bg-slate-50/5 p-6 rounded-[2rem] border border-white/5 backdrop-blur-md" :class="!isAnonymous ? 'bg-slate-50 border-slate-100' : ''">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-xs font-black uppercase tracking-widest" :class="isAnonymous ? 'text-white' : 'text-elevate-dark'">Opsi Anonim</span>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_anonymous" class="sr-only peer" x-model="isAnonymous">
                                    <div class="w-14 h-7 bg-slate-200 rounded-full peer peer-checked:bg-elevate-primary after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:after:translate-x-full shadow-inner border border-transparent"></div>
                                </label>
                            </div>
                            <p class="text-[10px] font-medium leading-relaxed transition-colors" :class="isAnonymous ? 'text-slate-300' : 'text-slate-500'">
                                Nama kamu akan disembunyikan dari guru BK dan dashboard monitoring. Laporan hanya akan dilabeli sebagai <strong class="text-rose-500">Siswa Anonim</strong>.
                            </p>
                        </div>
                    </div>
                </div>

                
                <div class="bg-gradient-to-br from-elevate-primary to-elevate-dark p-8 rounded-[3rem] text-white shadow-xl shadow-elevate-dark/20 relative overflow-hidden group">
                    <div class="absolute -right-10 -bottom-10 opacity-10 group-hover:scale-110 transition-transform"><i class="ph-fill ph-info text-9xl"></i></div>
                    <h3 class="font-black text-elevate-accent text-[10px] uppercase tracking-[0.2em] mb-8">Panduan Laporan</h3>
                    <div class="space-y-6 relative">
                        <div class="absolute left-[11px] top-1 bottom-1 w-[1px] bg-white/20"></div>
                        <div class="relative pl-8">
                            <div class="absolute left-0 top-1.5 w-6 h-[1px] bg-white/40"></div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-elevate-accent">Langkah 1</p>
                            <p class="text-[11px] font-medium mt-1">Kirim pengaduan dengan detail kronologi dan bukti.</p>
                        </div>
                        <div class="relative pl-8">
                            <div class="absolute left-0 top-1.5 w-6 h-[1px] bg-white/40"></div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-elevate-accent">Langkah 2</p>
                            <p class="text-[11px] font-medium mt-1">Guru BK menerima notifikasi dan melakukan verifikasi.</p>
                        </div>
                        <div class="relative pl-8">
                            <div class="absolute left-0 top-1.5 w-6 h-[1px] bg-white/40"></div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-elevate-accent">Selesai</p>
                            <p class="text-[11px] font-medium mt-1">Solusi diberikan dan masalah dinyatakan tuntas.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/complaints/create.blade.php ENDPATH**/ ?>