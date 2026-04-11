<?php $__env->startSection('content'); ?>

<style>
    /* Animations */
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    
    /* Input Fields Modern (Light) */
    .form-input {
        @apply w-full pl-12 pr-4 py-4 rounded-xl border border-slate-200 bg-slate-50 text-slate-800 font-bold focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none placeholder:text-slate-400 placeholder:font-normal;
    }
    
    .input-icon { 
        @apply absolute left-4 top-[18px] text-xl text-slate-400 transition-colors pointer-events-none; 
    }
    
    /* Focus State for Icons */
    .form-group:focus-within .input-icon { 
        @apply text-blue-600; 
    }
    
    /* Custom Scrollbar */
    .no-scrollbar::-webkit-scrollbar { display: none; }

    /* Selection Card Hover */
    .selection-card:hover {
        box-shadow: 0 10px 40px -10px rgba(0,0,0,0.1);
        transform: translateY(-4px);
    }

    /* Loader */
    .loader {
        border: 3px solid rgba(255,255,255,0.3);
        border-radius: 50%;
        border-top: 3px solid #ffffff;
        width: 20px;
        height: 20px;
        animation: spin 0.8s linear infinite;
    }
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
</style>

<div class="min-h-screen w-full relative overflow-x-hidden bg-slate-50 font-sans pb-20 selection:bg-blue-200 selection:text-blue-900">
    
    
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="absolute top-[-10%] right-[-5%] w-[600px] h-[600px] bg-blue-100/50 rounded-full blur-[100px] opacity-60 mix-blend-multiply"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[500px] h-[500px] bg-purple-100/50 rounded-full blur-[100px] opacity-60 mix-blend-multiply"></div>
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-30"></div>
    </div>

    <div class="relative z-10 max-w-5xl mx-auto px-4 pt-8 md:pt-12">
        
        
        <div class="text-center mb-12 animate-enter">
            <div class="inline-flex items-center gap-2 py-1.5 px-4 rounded-full bg-white border border-blue-100 mb-6 shadow-sm">
                <span class="relative flex h-2.5 w-2.5">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-blue-600"></span>
                </span>
                <span class="text-xs font-black text-slate-600 tracking-wider uppercase">PPDB Online <?php echo e(date('Y')); ?></span>
            </div>
            
            <h1 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight mb-4">
                Formulir <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Pendaftaran</span>
            </h1>
            <p class="text-slate-500 text-sm md:text-base font-medium max-w-2xl mx-auto leading-relaxed">
                Bergabunglah dengan kami untuk masa depan yang lebih cerah. Lengkapi data diri Anda dengan teliti sesuai dokumen resmi.
            </p>
        </div>

        
        <div id="draft-alert" class="hidden mb-6 mx-auto max-w-3xl animate-enter">
            <div class="bg-blue-50 border border-blue-100 rounded-[1.5rem] p-4 flex items-center gap-3 shadow-sm">
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 shrink-0">
                    <i class="ph-fill ph-floppy-disk text-xl"></i>
                </div>
                <div>
                    <h4 class="font-bold text-slate-800 text-sm">Draft Tersimpan</h4>
                    <p class="text-xs text-slate-500 font-medium">Data Anda tersimpan otomatis di perangkat ini.</p>
                </div>
            </div>
        </div>

        
        <?php if($errors->any()): ?>
            <div class="mb-8 mx-auto max-w-3xl animate-enter">
                <div class="bg-rose-50 border border-rose-100 rounded-[1.5rem] p-4 flex items-start gap-4 shadow-sm">
                    <div class="p-2 bg-rose-100 rounded-xl text-rose-600 shrink-0">
                        <i class="ph-fill ph-warning-circle text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-rose-700 font-bold text-sm mb-1">Perhatian</h3>
                        <ul class="list-disc list-inside text-xs text-rose-600 font-medium space-y-1">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        
        <div class="bg-white rounded-[2.5rem] overflow-hidden shadow-xl shadow-slate-200/60 border border-slate-100 animate-enter" 
             style="animation-delay: 100ms"
             x-data="{ 
                tab: 'jalur', 
                track: '<?php echo e(old('track', '')); ?>',
                achievement_type: '<?php echo e(old('achievement_type', '')); ?>',
                
                nextTab(target) {
                    this.tab = target;
                    // Smooth scroll to form top
                    document.getElementById('ppdbForm').scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
             }">
            
            
            <div class="border-b border-slate-100 bg-slate-50/50 overflow-x-auto no-scrollbar py-3">
                <div class="flex items-center justify-between min-w-max px-6 gap-2">
                    <?php
                        $tabs = [
                            'jalur' => ['icon' => 'ph-path', 'label' => 'Jalur', 'step' => '01'],
                            'pribadi' => ['icon' => 'ph-user', 'label' => 'Data Diri', 'step' => '02'],
                            'sekolah' => ['icon' => 'ph-graduation-cap', 'label' => 'Sekolah', 'step' => '03'],
                            'orangtua' => ['icon' => 'ph-users-three', 'label' => 'Orang Tua', 'step' => '04'],
                            'upload' => ['icon' => 'ph-cloud-arrow-up', 'label' => 'Berkas', 'step' => '05'],
                        ];
                    ?>

                    <?php $__currentLoopData = $tabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <button type="button" @click="tab = '<?php echo e($key); ?>'" 
                            class="group relative flex items-center gap-3 px-5 py-3 rounded-2xl transition-all duration-300 outline-none"
                            :class="tab === '<?php echo e($key); ?>' ? 'bg-white text-blue-600 shadow-sm ring-1 ring-slate-200' : 'text-slate-400 hover:bg-white hover:text-slate-600'">
                            
                            <div class="flex flex-col items-start text-left">
                                <span class="text-[10px] font-extrabold uppercase tracking-wider mb-0.5" 
                                      :class="tab === '<?php echo e($key); ?>' ? 'text-blue-600' : 'text-slate-400'">Step <?php echo e($item['step']); ?></span>
                                <span class="text-sm font-bold flex items-center gap-2">
                                    <i class="ph-bold <?php echo e($item['icon']); ?>"></i> <?php echo e($item['label']); ?>

                                </span>
                            </div>

                            
                            <div x-show="tab === '<?php echo e($key); ?>'" class="absolute bottom-0 left-6 right-6 h-[3px] bg-blue-600 rounded-t-full"></div>
                        </button>
                        
                        
                        <?php if(!$loop->last): ?>
                            <div class="h-8 w-[1px] bg-slate-200 hidden md:block"></div>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <form id="ppdbForm" action="<?php echo e(route('ppdb.store')); ?>" method="POST" enctype="multipart/form-data" class="p-6 md:p-12 min-h-[500px]">
                <?php echo csrf_field(); ?>

                
                <div x-show="tab === 'jalur'" class="space-y-8 animate-enter">
                    <div class="text-center max-w-2xl mx-auto mb-10">
                        <h3 class="text-2xl font-black text-slate-800 mb-2">Pilih Jalur Pendaftaran</h3>
                        <p class="text-slate-500 font-medium text-sm">Silakan pilih jalur seleksi yang paling sesuai dengan kualifikasi dan kondisi Anda saat ini.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                        <?php $__currentLoopData = [
                            ['val' => 'zonasi', 'icon' => 'ph-map-pin', 'label' => 'Zonasi', 'desc' => 'Berdasarkan Jarak Domisili', 'color' => 'text-blue-600', 'bg' => 'peer-checked:bg-blue-50', 'border' => 'peer-checked:border-blue-500', 'ring' => 'peer-checked:ring-blue-200'],
                            ['val' => 'prestasi', 'icon' => 'ph-trophy', 'label' => 'Prestasi', 'desc' => 'Nilai Rapor & Kejuaraan', 'color' => 'text-purple-600', 'bg' => 'peer-checked:bg-purple-50', 'border' => 'peer-checked:border-purple-500', 'ring' => 'peer-checked:ring-purple-200'],
                            ['val' => 'afirmasi', 'icon' => 'ph-hand-heart', 'label' => 'Afirmasi', 'desc' => 'KIP / KPS / PKH', 'color' => 'text-orange-600', 'bg' => 'peer-checked:bg-orange-50', 'border' => 'peer-checked:border-orange-500', 'ring' => 'peer-checked:ring-orange-200'],
                            ['val' => 'pindah_tugas', 'icon' => 'ph-briefcase', 'label' => 'Pindah', 'desc' => 'Perpindahan Tugas Ortu', 'color' => 'text-slate-600', 'bg' => 'peer-checked:bg-slate-100', 'border' => 'peer-checked:border-slate-500', 'ring' => 'peer-checked:ring-slate-200'],
                        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label class="cursor-pointer group relative">
                            <input type="radio" name="track" value="<?php echo e($opt['val']); ?>" class="peer sr-only save-local" data-key="track" x-model="track" required>
                            
                            
                            <div class="selection-card h-full p-6 rounded-[2rem] border border-slate-200 bg-white <?php echo e($opt['border']); ?> <?php echo e($opt['bg']); ?> peer-checked:ring-4 <?php echo e($opt['ring']); ?> transition-all duration-300 flex flex-col items-center justify-center text-center relative overflow-hidden">
                                <div class="w-16 h-16 rounded-2xl bg-slate-50 shadow-sm flex items-center justify-center mb-4 border border-slate-100 transition-transform group-hover:scale-110 duration-300 group-hover:bg-white">
                                    <i class="ph-duotone <?php echo e($opt['icon']); ?> text-3xl <?php echo e($opt['color']); ?>"></i>
                                </div>
                                <span class="font-extrabold text-lg text-slate-800 mb-1"><?php echo e($opt['label']); ?></span>
                                <span class="text-xs text-slate-500 font-bold leading-tight"><?php echo e($opt['desc']); ?></span>
                                <div class="absolute top-4 right-4 opacity-0 peer-checked:opacity-100 transition-all duration-300 transform scale-50 peer-checked:scale-100">
                                    <div class="<?php echo e(str_replace('text-', 'bg-', $opt['color'])); ?> text-white rounded-full p-1 shadow-md">
                                        <i class="ph-bold ph-check text-xs"></i>
                                    </div>
                                </div>
                            </div>
                        </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    
                    <div x-show="track === 'prestasi'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="pt-6 border-t border-slate-100">
                        <div class="bg-purple-50 p-6 md:p-8 rounded-[2rem] border border-purple-100 relative overflow-hidden">
                            <div class="absolute top-0 right-0 p-3 opacity-5"><i class="ph-duotone ph-medal text-9xl text-purple-900"></i></div>
                            
                            <h4 class="font-black text-purple-700 mb-6 flex items-center gap-2 relative z-10 text-lg">
                                <i class="ph-fill ph-star"></i> Detail Prestasi
                            </h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 relative z-10">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-3 tracking-wide">Jenis Prestasi <span class="text-rose-500">*</span></label>
                                    <div class="space-y-3">
                                        <label class="flex items-center gap-4 cursor-pointer bg-white p-4 rounded-2xl border border-purple-100 hover:border-purple-300 hover:shadow-md transition group">
                                            <input type="radio" name="achievement_type" value="akademik" x-model="achievement_type" class="w-5 h-5 text-purple-600 focus:ring-purple-500 bg-slate-100 border-slate-300 save-local" data-key="achievement_type" :required="track === 'prestasi'">
                                            <div class="flex-1">
                                                <span class="block text-sm font-bold text-slate-800 group-hover:text-purple-700 transition">Prestasi Akademik</span>
                                                <span class="block text-xs text-slate-500">Berdasarkan nilai rata-rata rapor</span>
                                            </div>
                                        </label>
                                        <label class="flex items-center gap-4 cursor-pointer bg-white p-4 rounded-2xl border border-purple-100 hover:border-purple-300 hover:shadow-md transition group">
                                            <input type="radio" name="achievement_type" value="non_akademik" x-model="achievement_type" class="w-5 h-5 text-purple-600 focus:ring-purple-500 bg-slate-100 border-slate-300 save-local" data-key="achievement_type" :required="track === 'prestasi'">
                                            <div class="flex-1">
                                                <span class="block text-sm font-bold text-slate-800 group-hover:text-purple-700 transition">Prestasi Non-Akademik</span>
                                                <span class="block text-xs text-slate-500">Kejuaraan Lomba / Olahraga / Seni</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                
                                <div x-show="achievement_type === 'non_akademik'" x-transition class="space-y-4">
                                    <div class="form-group relative">
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Nama Lomba / Kejuaraan <span class="text-rose-500">*</span></label>
                                        <input type="text" name="achievement_name" class="form-input pl-4 save-local" data-key="achievement_name" placeholder="Contoh: Juara 1 OSN Matematika" :required="track === 'prestasi' && achievement_type === 'non_akademik'">
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="form-group relative">
                                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Tingkat <span class="text-rose-500">*</span></label>
                                            <select name="achievement_level" class="form-input pl-4 appearance-none cursor-pointer save-local" data-key="achievement_level" :required="track === 'prestasi' && achievement_type === 'non_akademik'">
                                                <option value="">-- Pilih --</option>
                                                <option value="Kecamatan">Kecamatan</option>
                                                <option value="Kabupaten">Kabupaten/Kota</option>
                                                <option value="Provinsi">Provinsi</option>
                                                <option value="Nasional">Nasional</option>
                                                <option value="Internasional">Internasional</option>
                                            </select>
                                            <i class="ph-bold ph-caret-down absolute right-4 top-[45px] text-slate-400 pointer-events-none"></i>
                                        </div>
                                        <div class="form-group relative">
                                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Juara <span class="text-rose-500">*</span></label>
                                            <select name="achievement_rank" class="form-input pl-4 appearance-none cursor-pointer save-local" data-key="achievement_rank" :required="track === 'prestasi' && achievement_type === 'non_akademik'">
                                                <option value="">-- Pilih --</option>
                                                <option value="1">Juara 1 / Emas</option>
                                                <option value="2">Juara 2 / Perak</option>
                                                <option value="3">Juara 3 / Perunggu</option>
                                                <option value="Harapan 1">Harapan 1</option>
                                                <option value="Harapan 2">Harapan 2</option>
                                                <option value="Peserta">Peserta / Finalis</option>
                                            </select>
                                            <i class="ph-bold ph-caret-down absolute right-4 top-[45px] text-slate-400 pointer-events-none"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-6">
                        <button type="button" @click="nextTab('pribadi')" class="px-8 py-4 bg-slate-900 text-white font-bold rounded-xl hover:bg-blue-600 shadow-lg shadow-slate-900/20 hover:shadow-blue-600/30 transition-all hover:-translate-y-1 flex items-center gap-3">
                            Lanjut Isi Data Diri <i class="ph-bold ph-arrow-right"></i>
                        </button>
                    </div>
                </div>

                
                <div x-show="tab === 'pribadi'" class="space-y-6 animate-enter" style="display: none;">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 border border-blue-100">
                            <i class="ph-duotone ph-user text-3xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-slate-800">Identitas Calon Siswa</h3>
                            <p class="text-slate-500 text-sm font-medium">Pastikan data sesuai dengan Ijazah & KK.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2 form-group relative group">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1 group-focus-within:text-blue-600 transition-colors">Nama Lengkap</label>
                            <input type="text" name="full_name" value="<?php echo e(old('full_name')); ?>" class="form-input save-local" data-key="full_name" placeholder="Nama sesuai Ijazah" required>
                            <i class="ph-bold ph-text-t input-icon"></i>
                        </div>
                        
                        <div class="form-group relative group">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1 group-focus-within:text-blue-600 transition-colors">NISN (10 Digit)</label>
                            <input type="number" name="nisn" value="<?php echo e(old('nisn')); ?>" class="form-input save-local" data-key="nisn" placeholder="0012345678" required>
                            <i class="ph-bold ph-identification-card input-icon"></i>
                        </div>

                        <div class="form-group relative group">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1 group-focus-within:text-blue-600 transition-colors">NIK (16 Digit)</label>
                            <input type="number" name="nik" value="<?php echo e(old('nik')); ?>" class="form-input save-local" data-key="nik" placeholder="3207xxxxxxxxxxxx" required>
                            <i class="ph-bold ph-fingerprint input-icon"></i>
                        </div>

                        <div class="form-group relative group">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1 group-focus-within:text-blue-600 transition-colors">Tempat Lahir</label>
                            <input type="text" name="birth_place" value="<?php echo e(old('birth_place')); ?>" class="form-input save-local" data-key="birth_place" placeholder="Kota Kelahiran" required>
                            <i class="ph-bold ph-map-pin input-icon"></i>
                        </div>

                        <div class="form-group relative group">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1 group-focus-within:text-blue-600 transition-colors">Tanggal Lahir</label>
                            <input type="date" name="birth_date" value="<?php echo e(old('birth_date')); ?>" class="form-input save-local" data-key="birth_date" required>
                            <i class="ph-bold ph-calendar-blank input-icon"></i>
                        </div>

                        <div class="form-group relative group">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1 group-focus-within:text-blue-600 transition-colors">Jenis Kelamin</label>
                            <select name="gender" class="form-input appearance-none cursor-pointer save-local" data-key="gender" required>
                                <option value="">-- Pilih --</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                            <i class="ph-bold ph-gender-intersex input-icon"></i>
                            <i class="ph-bold ph-caret-down absolute right-4 top-[50px] text-slate-400 pointer-events-none"></i>
                        </div>

                        <div class="form-group relative group">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1 group-focus-within:text-blue-600 transition-colors">Agama</label>
                            <select name="religion" class="form-input appearance-none cursor-pointer save-local" data-key="religion" required>
                                <option value="">-- Pilih Agama --</option>
                                <option value="Islam">Islam</option>
                                <option value="Kristen">Kristen Protestan</option>
                                <option value="Katholik">Katolik</option>
                                <option value="Hindu">Hindu</option>
                                <option value="Buddha">Buddha</option>
                                <option value="Khonghucu">Khonghucu</option>
                            </select>
                            <i class="ph-bold ph-hands-praying input-icon"></i>
                            <i class="ph-bold ph-caret-down absolute right-4 top-[50px] text-slate-400 pointer-events-none"></i>
                        </div>

                        <div class="form-group relative group">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1 group-focus-within:text-blue-600 transition-colors">No. WA Siswa (Opsional)</label>
                            <input type="number" name="student_phone" value="<?php echo e(old('student_phone')); ?>" class="form-input save-local" data-key="student_phone" placeholder="08xxxxxxxxxx">
                            <i class="ph-bold ph-device-mobile input-icon"></i>
                        </div>

                        <div class="md:col-span-2 form-group relative group">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1 group-focus-within:text-blue-600 transition-colors">Alamat Lengkap</label>
                            <textarea name="address" rows="3" class="form-input pl-12 h-auto leading-relaxed resize-none pt-4 save-local" data-key="address" placeholder="Jalan, RT/RW, Dusun, Desa, Kecamatan, Kode Pos" required><?php echo e(old('address')); ?></textarea>
                            <i class="ph-bold ph-house-line input-icon top-[20px]"></i>
                            <p class="text-[10px] text-slate-400 font-bold mt-1 ml-1">* Sesuai dengan Kartu Keluarga.</p>
                        </div>
                    </div>

                    <div class="flex justify-between pt-10 border-t border-slate-100">
                        <button type="button" @click="nextTab('jalur')" class="px-6 py-4 text-slate-500 font-bold hover:text-blue-600 transition-all flex items-center gap-2 rounded-xl hover:bg-slate-50">
                            <i class="ph-bold ph-arrow-left"></i> Kembali
                        </button>
                        <button type="button" @click="nextTab('sekolah')" class="px-8 py-4 bg-slate-900 text-white font-bold rounded-xl hover:bg-blue-600 shadow-lg shadow-slate-900/20 hover:shadow-blue-600/30 transition-all hover:-translate-y-1 flex items-center gap-3">
                            Lanjut: Sekolah Asal <i class="ph-bold ph-arrow-right"></i>
                        </button>
                    </div>
                </div>

                
                <div x-show="tab === 'sekolah'" class="space-y-6 animate-enter" style="display: none;">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-100">
                            <i class="ph-duotone ph-graduation-cap text-3xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-slate-800">Sekolah Asal & Nilai</h3>
                            <p class="text-slate-500 text-sm font-medium">Informasi pendidikan dasar sebelumnya.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group relative group">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1 group-focus-within:text-emerald-600 transition-colors">Nama Sekolah Asal</label>
                            <input type="text" name="school_origin" class="form-input focus:border-emerald-500 focus:ring-emerald-500/20 save-local" data-key="school_origin" placeholder="Contoh: SDN 1 Lakbok" required>
                            <i class="ph-bold ph-buildings input-icon group-focus-within:text-emerald-600"></i>
                        </div>
                        
                        <div class="form-group relative group">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1 group-focus-within:text-emerald-600 transition-colors">NPSN (Opsional)</label>
                            <input type="number" name="npsn" class="form-input focus:border-emerald-500 focus:ring-emerald-500/20 save-local" data-key="npsn" placeholder="Nomor Pokok Sekolah">
                            <i class="ph-bold ph-hash input-icon group-focus-within:text-emerald-600"></i>
                        </div>

                        <div class="md:col-span-2 form-group relative group">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1 group-focus-within:text-emerald-600 transition-colors">Rata-rata Nilai Rapor (Total)</label>
                            <input type="number" step="0.01" name="average_grade" class="form-input focus:border-emerald-500 focus:ring-emerald-500/20 text-emerald-600 font-extrabold text-lg save-local" data-key="average_grade" placeholder="00.00" required>
                            <i class="ph-bold ph-percent input-icon group-focus-within:text-emerald-600"></i>
                            <p class="text-[10px] text-slate-400 font-bold mt-1 ml-1">Rata-rata akumulasi nilai rapor kelas 4, 5, dan 6 (Semester 1).</p>
                        </div>

                        
                        <div class="md:col-span-2" x-show="track === 'prestasi' && achievement_type === 'akademik'" x-transition>
                            <div class="bg-emerald-50 p-6 rounded-[2rem] border border-emerald-100 mt-4">
                                <h4 class="font-black text-emerald-700 mb-4 flex items-center gap-2 text-sm border-b border-emerald-200 pb-3">
                                    <i class="ph-bold ph-list-numbers"></i> Input Nilai Rapor (Rata-Rata Semester 7-11)
                                </h4>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                    <?php $__currentLoopData = ['PAI', 'PKn', 'B.Indo', 'MTK', 'IPA', 'IPS', 'SBdP', 'PJOK']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mapel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div>
                                        <label class="text-xs font-bold text-slate-500 mb-1.5 block text-center"><?php echo e($mapel); ?></label>
                                        <input type="number" step="0.01" name="grade_<?php echo e(Str::slug($mapel)); ?>" class="w-full rounded-xl bg-white border border-emerald-100 text-slate-800 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 text-sm font-bold text-center py-3 outline-none transition-all save-local" data-key="grade_<?php echo e(Str::slug($mapel)); ?>" placeholder="00" :required="track === 'prestasi' && achievement_type === 'akademik'">
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between pt-10 border-t border-slate-100">
                        <button type="button" @click="nextTab('pribadi')" class="px-6 py-4 text-slate-500 font-bold hover:text-blue-600 transition-all flex items-center gap-2 rounded-xl hover:bg-slate-50">
                            <i class="ph-bold ph-arrow-left"></i> Kembali
                        </button>
                        <button type="button" @click="nextTab('orangtua')" class="px-8 py-4 bg-slate-900 text-white font-bold rounded-xl hover:bg-blue-600 shadow-lg shadow-slate-900/20 hover:shadow-blue-600/30 transition-all hover:-translate-y-1 flex items-center gap-3">
                            Lanjut: Data Ortu <i class="ph-bold ph-arrow-right"></i>
                        </button>
                    </div>
                </div>

                
                <div x-show="tab === 'orangtua'" class="space-y-6 animate-enter" style="display: none;">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-14 h-14 rounded-2xl bg-purple-50 flex items-center justify-center text-purple-600 border border-purple-100">
                            <i class="ph-duotone ph-users-three text-3xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-slate-800">Data Orang Tua / Wali</h3>
                            <p class="text-slate-500 text-sm font-medium">Informasi kontak orang tua untuk komunikasi.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group relative group">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1 group-focus-within:text-purple-600">Nama Ayah / Wali</label>
                            <input type="text" name="father_name" class="form-input focus:border-purple-500 focus:ring-purple-500/20 save-local" data-key="father_name" required>
                            <i class="ph-bold ph-user input-icon group-focus-within:text-purple-600"></i>
                        </div>
                        <div class="form-group relative group">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1 group-focus-within:text-purple-600">Nama Ibu</label>
                            <input type="text" name="mother_name" class="form-input focus:border-purple-500 focus:ring-purple-500/20 save-local" data-key="mother_name" required>
                            <i class="ph-bold ph-user-circle input-icon group-focus-within:text-purple-600"></i>
                        </div>
                        <div class="form-group relative group">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1 group-focus-within:text-purple-600">No. WhatsApp Orang Tua</label>
                            <input type="number" name="parent_phone" class="form-input focus:border-purple-500 focus:ring-purple-500/20 save-local" data-key="parent_phone" required>
                            <i class="ph-bold ph-whatsapp-logo input-icon group-focus-within:text-purple-600"></i>
                        </div>
                        <div class="form-group relative group">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1 group-focus-within:text-purple-600">Pekerjaan Ayah/Wali</label>
                            <input type="text" name="parent_job" class="form-input focus:border-purple-500 focus:ring-purple-500/20 save-local" data-key="parent_job">
                            <i class="ph-bold ph-briefcase input-icon group-focus-within:text-purple-600"></i>
                        </div>

                        
                        <div class="form-group md:col-span-2 relative group">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1 group-focus-within:text-purple-600">Penghasilan Orang Tua</label>
                            <select name="parent_income" class="form-input appearance-none cursor-pointer save-local" data-key="parent_income">
                                <option value="">-- Pilih Range Penghasilan --</option>
                                <option value="Kurang dari 500.000">Kurang dari Rp 500.000</option>
                                <option value="500.000 - 1.000.000">Rp 500.000 - Rp 1.000.000</option>
                                <option value="1.000.000 - 2.000.000">Rp 1.000.000 - Rp 2.000.000</option>
                                <option value="2.000.000 - 5.000.000">Rp 2.000.000 - Rp 5.000.000</option>
                                <option value="Lebih dari 5.000.000">Lebih dari Rp 5.000.000</option>
                            </select>
                            <i class="ph-bold ph-currency-dollar input-icon group-focus-within:text-purple-600"></i>
                            <i class="ph-bold ph-caret-down absolute right-4 top-[50px] text-slate-400 pointer-events-none"></i>
                        </div>
                    </div>

                    <div class="flex justify-between pt-10 border-t border-slate-100">
                        <button type="button" @click="nextTab('sekolah')" class="px-6 py-4 text-slate-500 font-bold hover:text-blue-600 transition-all flex items-center gap-2 rounded-xl hover:bg-slate-50">
                            <i class="ph-bold ph-arrow-left"></i> Kembali
                        </button>
                        <button type="button" @click="nextTab('upload')" class="px-8 py-4 bg-slate-900 text-white font-bold rounded-xl hover:bg-blue-600 shadow-lg shadow-slate-900/20 hover:shadow-blue-600/30 transition-all hover:-translate-y-1 flex items-center gap-3">
                            Lanjut: Upload Berkas <i class="ph-bold ph-arrow-right"></i>
                        </button>
                    </div>
                </div>

                
                <div x-show="tab === 'upload'" class="space-y-6 animate-enter" style="display: none;">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-14 h-14 rounded-2xl bg-cyan-50 flex items-center justify-center text-cyan-600 border border-cyan-100">
                            <i class="ph-duotone ph-cloud-arrow-up text-3xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-slate-800">Unggah Dokumen</h3>
                            <p class="text-slate-500 text-sm font-medium">Pastikan dokumen terbaca jelas (Max 2MB/file).</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php $__currentLoopData = [
                            ['name' => 'file_photo', 'label' => 'Pas Foto 3x4', 'desc' => 'Latar merah/biru', 'icon' => 'ph-user-focus', 'required' => true],
                            ['name' => 'file_kk', 'label' => 'Kartu Keluarga', 'desc' => 'Scan asli/legalisir', 'icon' => 'ph-users-three', 'required' => true],
                            ['name' => 'file_akta', 'label' => 'Akta Kelahiran', 'desc' => 'Scan asli', 'icon' => 'ph-scroll', 'required' => true],
                            ['name' => 'file_grades', 'label' => 'Scan Rapor', 'desc' => 'Semester 7-11', 'icon' => 'ph-exam', 'required' => true],
                        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="form-group col-span-1">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1"><?php echo e($file['label']); ?> <span class="text-rose-500">*</span></label>
                            
                            <div class="relative group">
                                <input type="file" name="<?php echo e($file['name']); ?>" id="<?php echo e($file['name']); ?>" 
                                       class="peer absolute inset-0 w-full h-full opacity-0 z-20 cursor-pointer" 
                                       onchange="previewFile(this)" <?php echo e($file['required'] ? 'required' : ''); ?>>
                                
                                
                                <div id="preview-<?php echo e($file['name']); ?>-default" class="h-28 border border-dashed border-slate-300 rounded-2xl bg-slate-50 hover:bg-cyan-50 hover:border-cyan-300 transition-all flex items-center justify-center gap-4 group-hover:shadow-sm">
                                    <div class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center group-hover:border-cyan-200 group-hover:text-cyan-600 transition-colors text-slate-400">
                                        <i class="ph-duotone <?php echo e($file['icon']); ?> text-xl"></i>
                                    </div>
                                    <div class="text-left">
                                        <p class="font-bold text-slate-700 text-sm group-hover:text-cyan-700 transition-colors">Pilih File...</p>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase"><?php echo e($file['desc']); ?></p>
                                    </div>
                                </div>

                                
                                <div id="preview-<?php echo e($file['name']); ?>-selected" class="hidden h-28 border border-solid border-cyan-200 bg-cyan-50 rounded-2xl px-6 flex items-center justify-between">
                                    <div class="flex items-center gap-3 overflow-hidden">
                                        <div class="w-10 h-10 rounded-full bg-cyan-500 flex items-center justify-center text-white shrink-0 shadow-sm">
                                            <i class="ph-bold ph-check text-sm"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-bold text-cyan-800 text-sm truncate file-name-text">filename.jpg</p>
                                            <p class="text-[10px] text-cyan-600 font-bold uppercase">Siap diupload</p>
                                        </div>
                                    </div>
                                    <span class="text-xs text-rose-500 hover:text-rose-600 cursor-pointer font-extrabold relative z-30 bg-white px-3 py-1.5 rounded-lg shadow-sm border border-rose-100">Ganti</span>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    
                    <div x-show="track === 'prestasi' && achievement_type === 'non_akademik'" x-transition class="pt-6 border-t border-slate-100 mt-6">
                         <div class="form-group">
                            <label class="block text-xs font-bold text-amber-500 uppercase mb-2 ml-1">
                                Bukti Sertifikat Kejuaraan <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative group">
                                <input type="file" name="file_achievement" id="file_achievement" class="peer absolute inset-0 w-full h-full opacity-0 z-20 cursor-pointer" onchange="previewFile(this)" :required="track === 'prestasi' && achievement_type === 'non_akademik'">
                                
                                <div id="preview-file_achievement-default" class="h-28 border border-dashed border-amber-200 rounded-2xl bg-amber-50 hover:bg-amber-100/50 transition-all flex items-center justify-center gap-4">
                                    <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center text-amber-500 border border-amber-100 shadow-sm">
                                        <i class="ph-duotone ph-medal text-2xl"></i>
                                    </div>
                                    <div class="text-left">
                                        <p class="font-bold text-amber-700 text-sm">Unggah Sertifikat Juara</p>
                                        <p class="text-xs text-amber-600/70 font-bold">Dokumen asli kejuaraan tertinggi</p>
                                    </div>
                                </div>

                                <div id="preview-file_achievement-selected" class="hidden h-28 border border-solid border-amber-200 bg-amber-50 rounded-2xl px-6 flex items-center justify-between">
                                    <div class="flex items-center gap-3 overflow-hidden">
                                        <div class="w-10 h-10 rounded-full bg-amber-500 flex items-center justify-center text-white shrink-0"><i class="ph-fill ph-check text-lg"></i></div>
                                        <div class="min-w-0"><p class="font-bold text-amber-800 text-sm truncate file-name-text">filename.jpg</p><p class="text-xs text-amber-600 font-bold">Siap diupload</p></div>
                                    </div>
                                    <span class="text-xs text-rose-500 hover:text-rose-600 cursor-pointer font-extrabold relative z-30 bg-white px-3 py-1.5 rounded-lg shadow-sm border border-rose-100">Ganti</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="pt-6 border-t border-slate-100 mt-6">
                        <h4 class="font-bold text-slate-800 mb-4 flex items-center gap-2 text-sm">
                            Dokumen Pendukung <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-500 text-[10px] font-bold uppercase tracking-wider border border-slate-200">Opsional</span>
                        </h4>
                        <div class="form-group">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Kartu KIP / PKH / KPS</label>
                            <div class="relative group">
                                <input type="file" name="file_kip" id="file_kip" class="peer absolute inset-0 w-full h-full opacity-0 z-20 cursor-pointer" onchange="previewFile(this)">
                                
                                <div id="preview-file_kip-default" class="h-28 border border-dashed border-slate-300 rounded-2xl bg-slate-50 hover:bg-purple-50 hover:border-purple-300 transition-all flex items-center justify-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center group-hover:border-purple-200 group-hover:text-purple-600 transition-colors text-slate-400">
                                        <i class="ph-duotone ph-cards text-xl"></i>
                                    </div>
                                    <div class="text-left">
                                        <p class="font-bold text-slate-700 text-sm group-hover:text-purple-700 transition-colors">Upload Kartu Bantuan</p>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase">Jika memiliki kartu pemerintah</p>
                                    </div>
                                </div>

                                <div id="preview-file_kip-selected" class="hidden h-28 border border-solid border-purple-200 bg-purple-50 rounded-2xl px-6 flex items-center justify-between">
                                    <div class="flex items-center gap-3 overflow-hidden">
                                        <div class="w-8 h-8 rounded-full bg-purple-600 flex items-center justify-center text-white shrink-0"><i class="ph-bold ph-check text-sm"></i></div>
                                        <div class="min-w-0"><p class="font-bold text-purple-800 text-sm truncate file-name-text">filename.jpg</p><p class="text-[10px] text-purple-600 font-bold">Siap diupload</p></div>
                                    </div>
                                    <span class="text-xs text-rose-500 hover:text-rose-600 cursor-pointer font-extrabold relative z-30 bg-white px-3 py-1.5 rounded-lg shadow-sm border border-rose-100">Ganti</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="mt-8 bg-blue-50/50 rounded-[2rem] p-6 border border-blue-100">
                        <label class="flex items-start gap-4 cursor-pointer group">
                            <div class="relative flex items-center mt-1">
                                <input type="checkbox" required class="peer h-6 w-6 cursor-pointer appearance-none rounded-lg border-2 border-slate-300 transition-all checked:border-blue-600 checked:bg-blue-600 hover:border-blue-400 bg-white">
                                <i class="ph-bold ph-check absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-white text-sm opacity-0 transition-opacity peer-checked:opacity-100 pointer-events-none"></i>
                            </div>
                            <div class="text-sm text-slate-500 leading-relaxed select-none transition-colors">
                                <span class="font-black text-slate-800 block mb-1">Pernyataan Tanggung Jawab Mutlak</span>
                                Saya menyatakan bahwa seluruh data yang saya isikan adalah benar dan sah. Apabila dikemudian hari ditemukan pemalsuan data, saya bersedia menerima sanksi sesuai ketentuan hukum yang berlaku.
                            </div>
                        </label>
                    </div>

                    <div class="flex justify-between pt-8 border-t border-slate-100 mt-8">
                        <button type="button" @click="nextTab('orangtua')" class="px-6 py-4 text-slate-500 font-bold hover:text-blue-600 transition-all flex items-center gap-2 rounded-xl hover:bg-slate-50">
                            <i class="ph-bold ph-arrow-left"></i> Sebelumnya
                        </button>
                        
                        <button type="submit" id="submitBtn" class="px-10 py-4 bg-slate-900 hover:bg-blue-600 text-white font-bold rounded-xl shadow-lg shadow-slate-900/20 hover:shadow-blue-600/30 transition-all transform hover:-translate-y-1 flex items-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed">
                            <span id="btnText" class="flex items-center gap-2">
                                <i class="ph-bold ph-paper-plane-right text-lg"></i>
                                <span>Kirim Pendaftaran</span>
                            </span>
                            <div id="btnLoading" class="hidden"><div class="loader"></div></div>
                        </button>
                    </div>
                </div>

            </form>
        </div>

        <div class="mt-12 text-center pb-8">
            <p class="text-xs text-slate-400 font-bold tracking-widest uppercase">
                &copy; <?php echo e(date('Y')); ?> Panitia PPDB SMPN 3 Lakbok. <br class="md:hidden"> Hak Cipta Dilindungi Undang-Undang.
            </p>
        </div>
    </div>

    
    <script>
        // 1. File Preview Logic
        function previewFile(input) {
            const file = input.files[0];
            const id = input.id;
            const defaultView = document.getElementById(`preview-${id}-default`);
            const selectedView = document.getElementById(`preview-${id}-selected`);

            if (file) {
                if (file.size > 2097152) { // 2MB Check
                    alert('Maaf, ukuran file ' + file.name + ' terlalu besar. Maksimal 2MB.');
                    input.value = '';
                    return;
                }
                defaultView.classList.add('hidden');
                selectedView.classList.remove('hidden');
                selectedView.classList.add('flex');
                selectedView.querySelector('.file-name-text').textContent = file.name;
            } else {
                defaultView.classList.remove('hidden');
                selectedView.classList.add('hidden');
            }
        }

        // 2. LocalStorage Logic
        document.addEventListener('DOMContentLoaded', () => {
            const inputs = document.querySelectorAll('.save-local');
            let hasData = false;

            // Load saved data
            inputs.forEach(input => {
                const key = 'ppdb_' + input.dataset.key;
                const savedValue = localStorage.getItem(key);
                if(savedValue) {
                    input.value = savedValue;
                    hasData = true;
                    // Trigger dispatch event agar AlpineJS x-model terupdate
                    input.dispatchEvent(new Event('input'));
                }
                
                // Save on input
                input.addEventListener('input', (e) => {
                    localStorage.setItem(key, e.target.value);
                });
            });

            if(hasData) document.getElementById('draft-alert').classList.remove('hidden');

            // Handle Submit Loading & Clear Storage
            document.getElementById('ppdbForm').addEventListener('submit', function() {
                const btn = document.getElementById('submitBtn');
                const text = document.getElementById('btnText');
                const loading = document.getElementById('btnLoading');
                
                btn.disabled = true;
                text.classList.add('hidden');
                loading.classList.remove('hidden');

                // Clear storage on submit
                inputs.forEach(input => localStorage.removeItem('ppdb_' + input.dataset.key));
            });
        });
    </script>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\ppdb\register.blade.php ENDPATH**/ ?>