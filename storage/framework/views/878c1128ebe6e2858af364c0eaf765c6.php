<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-in fade-in duration-500" x-data="{ showAddModal: false, isSubmitting: false, imgPreview: null }">
    
    
    <div class="lg:col-span-1">
        <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-emerald-100 sticky top-24">
            
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-500 to-teal-700 p-6 text-white shadow-lg shadow-emerald-500/20">
                <div class="absolute top-0 right-0 p-4 opacity-10">
                    <i class="ph-fill ph-trophy text-8xl"></i>
                </div>
                
                <div class="relative z-10">
                    <p class="text-xs font-bold uppercase tracking-widest text-emerald-100 mb-1">Total Poin Kebaikan</p>
                    <h2 class="text-5xl font-black tracking-tight">+<?php echo e($total_merit_points ?? 0); ?></h2>
                    <div class="mt-4 flex items-center gap-2 text-xs font-medium bg-white/20 w-fit px-3 py-1.5 rounded-lg backdrop-blur-sm">
                        <i class="ph-bold ph-trend-up"></i> Terus Meningkat
                    </div>
                </div>
            </div>

            
            <div class="mt-6 bg-slate-50 p-6 rounded-3xl border border-slate-100 relative">
                <i class="ph-fill ph-quotes text-slate-200 text-4xl absolute top-4 left-4"></i>
                <p class="text-sm text-slate-600 italic text-center relative z-10 leading-relaxed pt-2">
                    "Prestasi bukanlah kebetulan, melainkan hasil dari kerja keras, ketekunan, dan doa yang konsisten."
                </p>
                <div class="mt-4 flex justify-center gap-1">
                    <div class="w-1 h-1 rounded-full bg-slate-300"></div>
                    <div class="w-8 h-1 rounded-full bg-emerald-400"></div>
                    <div class="w-1 h-1 rounded-full bg-slate-300"></div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="lg:col-span-2 space-y-6">
        
        
        <div class="flex flex-col sm:flex-row items-center justify-between bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm gap-4">
            <div class="w-full sm:w-auto text-center sm:text-left">
                <h3 class="text-xl font-black text-slate-800 flex items-center justify-center sm:justify-start gap-2">
                    <i class="ph-fill ph-star text-yellow-400"></i> Jejak Prestasi
                </h3>
                <p class="text-slate-400 text-sm mt-1">Riwayat pencapaian dan perilaku positifmu.</p>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto">
                
                <button @click="showAddModal = true" class="flex-1 sm:flex-none justify-center inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-xl text-sm font-bold hover:from-emerald-600 hover:to-teal-700 transition-all shadow-lg shadow-emerald-500/20 active:scale-95">
                    <i class="ph-bold ph-plus-circle text-lg"></i> Lapor Prestasi
                </button>
                
                <div class="hidden sm:block">
                    <span class="px-4 py-2.5 bg-slate-50 rounded-xl text-xs font-bold text-slate-500 border border-slate-200">
                        Total: <?php echo e(isset($achievements) ? count($achievements) : 0); ?> Catatan
                    </span>
                </div>
            </div>
        </div>

        
        <?php if(isset($achievements) && count($achievements) > 0): ?>
            <div class="space-y-4">
                <?php $__currentLoopData = $achievements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        // Cek Tipe: Apakah Prestasi Besar (Lomba) atau Poin Harian
                        $isMajorAchievement = isset($record->type) && $record->type === 'achievement_record';
                        
                        // Fallback Status jika kolom status belum ada di database, default ke approved agar data lama aman
                        $status = $record->status ?? 'approved'; 
                    ?>

                    <?php if($isMajorAchievement): ?>
                        
                        <div class="relative group">
                            
                            <div class="absolute inset-0 bg-gradient-to-r from-yellow-100 to-amber-50 rounded-3xl transform translate-y-2 translate-x-2 transition-transform group-hover:translate-x-3 group-hover:translate-y-3 <?php echo e($status === 'rejected' ? 'opacity-50' : ''); ?>"></div>
                            
                            <div class="relative bg-white p-6 rounded-3xl border <?php echo e($status === 'rejected' ? 'border-rose-100 bg-rose-50/30' : 'border-amber-100'); ?> shadow-sm flex flex-col sm:flex-row gap-5 overflow-hidden">
                                
                                <div class="shrink-0">
                                    <div class="w-16 h-16 rounded-2xl <?php echo e($status === 'rejected' ? 'bg-slate-300' : 'bg-gradient-to-br from-yellow-400 to-amber-600 shadow-lg shadow-amber-200'); ?> flex items-center justify-center text-white">
                                        <i class="ph-duotone ph-trophy text-3xl"></i>
                                    </div>
                                </div>
                                
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-wrap items-start justify-between gap-2">
                                        <div>
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="px-2 py-0.5 rounded-md bg-amber-100 text-amber-700 text-[10px] font-black uppercase tracking-wider border border-amber-200">
                                                    <?php echo e($record->level ?? 'PENGHARGAAN'); ?>

                                                </span>
                                                <span class="text-xs text-slate-400 font-medium">
                                                    <?php echo e(\Carbon\Carbon::parse($record->date)->translatedFormat('d F Y')); ?>

                                                </span>
                                            </div>
                                            <h4 class="text-lg font-black <?php echo e($status === 'rejected' ? 'text-slate-500 line-through' : 'text-slate-800 group-hover:text-amber-600'); ?> leading-snug transition-colors">
                                                <?php echo e($record->title); ?>

                                            </h4>
                                        </div>
                                    </div>
                                    
                                    <?php if($record->notes): ?>
                                        <p class="text-sm text-slate-600 mt-2 line-clamp-2"><?php echo e($record->notes); ?></p>
                                    <?php endif; ?>

                                    
                                    <div class="mt-4 flex flex-wrap items-center gap-2">
                                        <?php if(!empty($record->photo)): ?>
                                            <a href="<?php echo e(asset('storage/' . $record->photo)); ?>" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 text-amber-700 hover:bg-amber-100 hover:text-amber-800 text-[10px] font-bold rounded-lg transition-colors border border-amber-200">
                                                <i class="ph-bold ph-image text-sm"></i> Foto Dokumentasi
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php if(!empty($record->certificate_path)): ?>
                                            <a href="<?php echo e(asset('storage/' . $record->certificate_path)); ?>" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-100 hover:text-blue-800 text-[10px] font-bold rounded-lg transition-colors border border-blue-200">
                                                <i class="ph-bold ph-certificate text-sm"></i> Sertifikat Asli
                                            </a>
                                        <?php endif; ?>

                                        
                                        <?php if($status === 'approved'): ?>
                                            <div class="flex items-center gap-1.5 text-[10px] font-bold text-emerald-600 bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-lg ml-auto shadow-sm">
                                                <i class="ph-fill ph-check-circle"></i> Divalidasi
                                            </div>
                                        <?php elseif($status === 'pending'): ?>
                                            <div class="flex items-center gap-1.5 text-[10px] font-bold text-amber-600 bg-amber-50 border border-amber-200 px-3 py-1.5 rounded-lg ml-auto shadow-sm animate-pulse">
                                                <i class="ph-fill ph-clock"></i> Menunggu Verifikasi
                                            </div>
                                        <?php elseif($status === 'rejected'): ?>
                                            <div class="flex items-center gap-1.5 text-[10px] font-bold text-rose-600 bg-rose-50 border border-rose-200 px-3 py-1.5 rounded-lg ml-auto shadow-sm">
                                                <i class="ph-fill ph-x-circle"></i> Laporan Ditolak
                                            </div>
                                        <?php endif; ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        
                        <div class="flex gap-4 group">
                            
                            <div class="flex flex-col items-center">
                                <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100 shrink-0 group-hover:bg-emerald-500 group-hover:text-white transition-colors">
                                    <i class="ph-bold ph-plus"></i>
                                </div>
                                <div class="w-0.5 h-full bg-slate-100 my-2 group-last:hidden"></div>
                            </div>

                            <div class="flex-1 pb-6">
                                <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm group-hover:border-emerald-200 transition-colors relative">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h5 class="font-bold text-slate-800 text-sm">
                                                <?php echo e($record->disciplineType->name ?? 'Kebaikan Harian'); ?>

                                            </h5>
                                            <p class="text-xs text-slate-400 mt-0.5">
                                                <?php echo e(\Carbon\Carbon::parse($record->date)->translatedFormat('l, d F Y')); ?>

                                            </p>
                                        </div>
                                        <?php if(isset($record->disciplineType->point_value) && $record->disciplineType->point_value > 0): ?>
                                            <span class="text-emerald-600 font-black text-sm bg-emerald-50 px-2 py-1 rounded-lg border border-emerald-100">
                                                +<?php echo e($record->disciplineType->point_value); ?>

                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <?php if($record->notes): ?>
                                        <div class="mt-2 text-xs text-slate-600 bg-slate-50 p-2 rounded-lg italic">
                                            "<?php echo e($record->notes); ?>"
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            
            <div class="bg-white rounded-[2.5rem] p-12 text-center border-2 border-dashed border-slate-200">
                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 animate-bounce-subtle">
                    <i class="ph-duotone ph-medal text-5xl text-slate-300"></i>
                </div>
                <h3 class="text-xl font-black text-slate-800">Belum Ada Catatan</h3>
                <p class="text-slate-500 text-sm mt-2 max-w-sm mx-auto">
                    Setiap langkah kecil menuju kebaikan adalah prestasi. Ayo mulai kumpulkan poin kebaikanmu!
                </p>
            </div>
        <?php endif; ?>
    </div>

    
    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">
        
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" 
             x-show="showAddModal" x-transition.opacity @click="showAddModal = false"></div>
        
        <!-- Wrapper untuk memposisikan modal (Pakai items-start & pt-28 agar turun tidak tertutup navbar) -->
        <div class="flex min-h-full items-start justify-center p-4 pt-28 sm:p-6 sm:pt-32 text-center">
            
            <!-- Panel Modal -->
            <div class="relative bg-white rounded-[2rem] w-full max-w-2xl shadow-2xl flex flex-col text-left transform transition-all max-h-[calc(100vh-8rem)]" 
                 x-show="showAddModal" x-transition>
                
                <!-- Header Modal (Sticky/Tetap di atas) -->
                <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 shrink-0 rounded-t-[2rem]">
                    <h3 class="font-black text-slate-800 text-lg flex items-center gap-2">
                        <span class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center"><i class="ph-bold ph-paper-plane-tilt text-lg"></i></span> 
                        Lapor Prestasi Mandiri
                    </h3>
                    <button @click="showAddModal = false" type="button" class="w-8 h-8 flex items-center justify-center rounded-full bg-white text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-colors shadow-sm border border-slate-200">
                        <i class="ph-bold ph-x"></i>
                    </button>
                </div>
                
                <!-- Form Container -->
                <form action="<?php echo e(route('student.achievements.store') ?? '#'); ?>" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden" @submit="isSubmitting = true">
                    <?php echo csrf_field(); ?>
                    
                    
                    <input type="hidden" name="student_id" value="<?php echo e($student->id ?? ''); ?>">
                    
                    <!-- Body Modal (Area yang bisa di-scroll) -->
                    <div class="flex-1 overflow-y-auto p-6 space-y-5 custom-scrollbar">
                        
                        <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 flex gap-3 text-blue-800 mb-2">
                            <i class="ph-fill ph-info text-xl shrink-0"></i>
                            <p class="text-xs font-medium leading-relaxed">Formulir ini digunakan untuk melaporkan kejuaraan, lomba, atau penghargaan yang kamu ikuti. Data akan diverifikasi terlebih dahulu oleh admin/guru sebelum divalidasi dan poin ditambahkan.</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Judul Kejuaraan/Prestasi <span class="text-rose-500">*</span></label>
                            <input type="text" name="title" required placeholder="Contoh: Juara 1 Lomba Pidato Bahasa Inggris..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:bg-white focus:ring-2 focus:ring-emerald-500 outline-none transition-all">
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Tingkat <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <select name="level" required class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-4 pr-10 py-3 text-sm font-bold text-slate-800 focus:bg-white focus:ring-2 focus:ring-emerald-500 outline-none transition-all appearance-none cursor-pointer">
                                        <option value="Sekolah">Tingkat Sekolah</option>
                                        <option value="Kecamatan">Tingkat Kecamatan</option>
                                        <option value="Kabupaten">Tingkat Kabupaten</option>
                                        <option value="Provinsi">Tingkat Provinsi</option>
                                        <option value="Nasional">Tingkat Nasional</option>
                                        <option value="Internasional">Tingkat Internasional</option>
                                    </select>
                                    <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Tanggal Pelaksanaan <span class="text-rose-500">*</span></label>
                                <input type="date" name="date" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:bg-white focus:ring-2 focus:ring-emerald-500 outline-none transition-all">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Deskripsi / Penyelenggara</label>
                            <textarea name="description" rows="2" placeholder="Contoh: Diselenggarakan oleh Universitas XYZ..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-emerald-500 outline-none transition-all custom-scrollbar"></textarea>
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Sertifikat / Piagam <span class="text-emerald-600">(Wajib)</span></label>
                                <input type="file" name="certificate" required accept=".pdf,image/*" class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:font-bold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 bg-white border border-slate-200 rounded-xl cursor-pointer p-1">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Foto Dokumentasi Lomba</label>
                                <input type="file" name="photo" accept="image/*" @change="imgPreview = URL.createObjectURL($event.target.files[0])" class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:font-bold file:bg-amber-100 file:text-amber-700 hover:file:bg-amber-200 bg-white border border-slate-200 rounded-xl cursor-pointer p-1">
                                
                                <!-- Preview Gambar -->
                                <div x-show="imgPreview" class="mt-3 h-24 w-full rounded-xl overflow-hidden border border-slate-200 shadow-sm" style="display: none;">
                                    <img :src="imgPreview" class="w-full h-full object-cover">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Footer Modal (Sticky/Tetap di bawah) -->
                    <div class="p-6 border-t border-slate-100 flex justify-end gap-3 shrink-0 bg-white rounded-b-[2rem]">
                        <button type="button" @click="showAddModal = false" class="px-5 py-2.5 rounded-xl text-sm font-bold text-slate-500 hover:bg-slate-100 transition-colors">Batal</button>
                        <button type="submit" :disabled="isSubmitting" :class="{'opacity-70 cursor-wait': isSubmitting}" class="px-6 py-2.5 rounded-xl text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-lg shadow-emerald-500/30 transition-all flex items-center gap-2">
                            <span x-show="!isSubmitting"><i class="ph-bold ph-paper-plane-right"></i> Kirim Laporan</span>
                            <span x-show="isSubmitting" x-cloak><i class="ph-bold ph-spinner animate-spin"></i> Memproses...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\students\portal\partials\tab-prestasi.blade.php ENDPATH**/ ?>