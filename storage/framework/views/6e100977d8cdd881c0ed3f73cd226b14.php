<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Formulir PPDB Online - SMP Negeri 3 Lakbok</title>
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <!-- Styles -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #0f172a; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #475569; }

        /* Form Input Styles */
        .form-group { @apply relative mb-5; }
        .form-label { @apply block text-xs font-bold text-slate-500 uppercase mb-1.5 ml-1; }
        .form-input { 
            @apply w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-800 font-bold focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all outline-none placeholder:text-slate-400 placeholder:font-normal; 
        }
        .input-icon { @apply absolute left-4 top-[38px] text-xl text-slate-400 pointer-events-none transition-colors; }
        .form-input:focus ~ .input-icon { @apply text-blue-600; }
        
        /* Loading Spinner Animation */
        .loader {
            border: 3px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top: 3px solid white;
            width: 20px;
            height: 20px;
            -webkit-animation: spin 1s linear infinite; /* Safari */
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Tab Active State Triangle */
        .tab-active-indicator {
            clip-path: polygon(50% 100%, 0 0, 100% 0);
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-800 antialiased min-h-screen relative selection:bg-blue-500 selection:text-white">

    <!-- BACKGROUND EFFECTS -->
    <div class="fixed inset-0 z-0 pointer-events-none">
        <div class="absolute inset-0 bg-gradient-to-b from-slate-900 via-slate-900 to-blue-950"></div>
        <div class="absolute top-0 left-0 w-full h-full bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
        <div class="absolute -top-[20%] -right-[10%] w-[600px] h-[600px] bg-blue-600/20 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute -bottom-[20%] -left-[10%] w-[600px] h-[600px] bg-indigo-600/20 rounded-full blur-[120px]"></div>
    </div>

    <!-- NAVBAR (Sticky Glass) -->
    <nav class="sticky top-0 z-50 backdrop-blur-xl bg-slate-900/80 border-b border-white/10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <a href="<?php echo e(url('/')); ?>" class="group flex items-center gap-3 text-slate-300 hover:text-white transition">
                <div class="p-2 rounded-xl bg-white/5 border border-white/10 group-hover:bg-blue-600 group-hover:border-blue-500 transition-all shadow-sm">
                    <i class="ph-bold ph-arrow-left text-lg"></i>
                </div>
                <div class="hidden sm:block leading-tight">
                    <p class="text-xs font-medium text-slate-400 group-hover:text-blue-200">Kembali ke</p>
                    <p class="text-sm font-bold">Halaman Utama</p>
                </div>
            </a>
            
            <div class="flex items-center gap-3">
                <div class="text-right">
                    <p class="text-white font-bold text-lg tracking-tight">PPDB <span class="text-blue-400">ONLINE</span></p>
                    <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest">SMPN 3 Lakbok</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-900/50 text-white">
                    <i class="ph-bold ph-student text-xl"></i>
                </div>
            </div>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="relative z-10 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <!-- HEADER TEXT -->
        <div class="text-center mb-10" data-aos="fade-down">
            <span class="inline-flex items-center gap-2 py-1.5 px-4 rounded-full bg-blue-500/10 text-blue-300 border border-blue-500/20 text-xs font-bold uppercase tracking-widest mb-4 backdrop-blur-sm">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></span>
                Tahun Ajaran <?php echo e(date('Y')); ?>/<?php echo e(date('Y')+1); ?>

            </span>
            <h1 class="text-3xl md:text-5xl font-black text-white mb-4 tracking-tight leading-tight">
                Formulir Pendaftaran
            </h1>
            <p class="text-slate-400 text-sm md:text-base max-w-2xl mx-auto leading-relaxed">
                Mohon lengkapi data di bawah ini secara bertahap. Pastikan data yang dimasukkan valid dan sesuai dengan dokumen asli.
            </p>
        </div>

        <!-- ERROR ALERT -->
        <?php if($errors->any()): ?>
            <div class="mb-8 p-1 rounded-2xl bg-gradient-to-r from-red-500 to-pink-600 shadow-lg shadow-red-900/20 animate-pulse">
                <div class="bg-slate-900 rounded-xl p-5 flex items-start gap-4">
                    <div class="p-2.5 bg-red-500/20 rounded-full text-red-400 shrink-0">
                        <i class="ph-fill ph-warning-circle text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-lg mb-1">Terjadi Kesalahan!</h3>
                        <ul class="list-disc list-inside text-sm text-slate-300 space-y-1">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        
        <div x-data="{ 
                tab: 'jalur', 
                track: '<?php echo e(old('track', '')); ?>',
                achievement_type: '<?php echo e(old('achievement_type', '')); ?>',
                
                nextTab(target) {
                    this.tab = target;
                    window.scrollTo({top: 100, behavior: 'smooth'});
                }
             }" 
             class="bg-white rounded-3xl shadow-2xl shadow-black/50 overflow-hidden border border-white/10">

            
            <div class="bg-slate-50 border-b border-slate-200 px-4 pt-4 flex gap-2 overflow-x-auto custom-scrollbar no-scrollbar">
                <?php
                    $tabs = [
                        'jalur' => ['icon' => 'ph-path', 'label' => 'Jalur & Prestasi'],
                        'pribadi' => ['icon' => 'ph-user', 'label' => 'Data Diri'],
                        'sekolah' => ['icon' => 'ph-buildings', 'label' => 'Sekolah Asal'],
                        'orangtua' => ['icon' => 'ph-users-three', 'label' => 'Orang Tua'],
                        'upload' => ['icon' => 'ph-upload-simple', 'label' => 'Upload Berkas'],
                    ];
                ?>

                <?php $__currentLoopData = $tabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <button type="button" @click="tab = '<?php echo e($key); ?>'" 
                        :class="{ 
                            'bg-white text-blue-700 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] border-slate-200 border-b-white': tab === '<?php echo e($key); ?>', 
                            'text-slate-500 hover:text-blue-600 hover:bg-slate-100 border-transparent': tab !== '<?php echo e($key); ?>' 
                        }" 
                        class="px-5 py-4 rounded-t-2xl border-t border-x font-bold text-sm whitespace-nowrap transition-all relative group flex items-center gap-2.5 min-w-fit">
                        <i class="ph-bold <?php echo e($item['icon']); ?> text-lg" :class="tab === '<?php echo e($key); ?>' ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-500'"></i>
                        <?php echo e($item['label']); ?>

                        
                        
                        <div x-show="tab === '<?php echo e($key); ?>'" class="absolute top-0 left-0 w-full h-1 bg-blue-600 rounded-t-full"></div>
                    </button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <form id="ppdbForm" action="<?php echo e(route('ppdb.store')); ?>" method="POST" enctype="multipart/form-data" class="p-6 sm:p-10 min-h-[400px]">
                <?php echo csrf_field(); ?>

                
                <div x-show="tab === 'jalur'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" class="space-y-8">
                    <div class="mb-6">
                        <h3 class="text-xl font-bold text-slate-800 mb-1 flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-sm font-bold">1</span>
                            Pilih Jalur Pendaftaran
                        </h3>
                        <p class="text-slate-500 text-sm ml-10">Tentukan jalur seleksi yang sesuai dengan kondisi Anda.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <?php $__currentLoopData = [
                            ['val' => 'zonasi', 'icon' => 'ph-map-pin', 'label' => 'Zonasi', 'desc' => 'Berdasarkan Jarak'],
                            ['val' => 'prestasi', 'icon' => 'ph-trophy', 'label' => 'Prestasi', 'desc' => 'Akademik/Non'],
                            ['val' => 'afirmasi', 'icon' => 'ph-hand-heart', 'label' => 'Afirmasi', 'desc' => 'KIP/PKH/KPS'],
                            ['val' => 'pindah_tugas', 'icon' => 'ph-briefcase', 'label' => 'Pindah Tugas', 'desc' => 'Orang Tua/Wali'],
                        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label class="cursor-pointer group">
                            <input type="radio" name="track" value="<?php echo e($opt['val']); ?>" class="peer sr-only" x-model="track" required>
                            <div class="p-5 rounded-2xl border-2 border-slate-100 bg-slate-50 peer-checked:border-blue-600 peer-checked:bg-blue-50/50 transition-all h-full hover:border-blue-300 flex flex-col items-center text-center relative overflow-hidden">
                                <div class="w-12 h-12 rounded-full bg-white shadow-sm flex items-center justify-center text-slate-400 peer-checked:text-blue-600 peer-checked:bg-blue-100 mb-3 transition-colors">
                                    <i class="ph-duotone <?php echo e($opt['icon']); ?> text-2xl"></i>
                                </div>
                                <span class="font-bold text-slate-700 peer-checked:text-blue-800"><?php echo e($opt['label']); ?></span>
                                <span class="text-xs text-slate-500 mt-1"><?php echo e($opt['desc']); ?></span>
                                <div class="absolute top-3 right-3 opacity-0 peer-checked:opacity-100 transition-opacity text-blue-600">
                                    <i class="ph-fill ph-check-circle text-xl"></i>
                                </div>
                            </div>
                        </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    
                    <div x-show="track === 'prestasi'" x-transition class="pt-6 border-t border-slate-100">
                        <div class="bg-blue-50/50 p-6 rounded-2xl border border-blue-100/60">
                            <h4 class="font-bold text-blue-900 mb-4 flex items-center gap-2">
                                <i class="ph-fill ph-medal text-lg"></i> Detail Prestasi
                            </h4>
                            
                            <div class="mb-5">
                                <label class="form-label text-blue-800">Jenis Prestasi <span class="text-red-500">*</span></label>
                                <div class="flex flex-col sm:flex-row sm:gap-4 gap-3">
                                    <label class="flex items-center gap-3 cursor-pointer bg-white px-4 py-3 rounded-xl border border-blue-100 hover:border-blue-300 transition shadow-sm w-full sm:w-auto">
                                        <input type="radio" name="achievement_type" value="akademik" x-model="achievement_type" class="w-4 h-4 text-blue-600 focus:ring-blue-500" :required="track === 'prestasi'">
                                        <div class="leading-tight">
                                            <span class="block text-sm font-bold text-slate-800">Akademik (Rapor)</span>
                                            <span class="block text-[10px] text-slate-500 mt-0.5">Nilai rata-rata rapor</span>
                                        </div>
                                    </label>
                                    <label class="flex items-center gap-3 cursor-pointer bg-white px-4 py-3 rounded-xl border border-blue-100 hover:border-blue-300 transition shadow-sm w-full sm:w-auto">
                                        <input type="radio" name="achievement_type" value="non_akademik" x-model="achievement_type" class="w-4 h-4 text-blue-600 focus:ring-blue-500" :required="track === 'prestasi'">
                                        <div class="leading-tight">
                                            <span class="block text-sm font-bold text-slate-800">Non-Akademik (Lomba)</span>
                                            <span class="block text-[10px] text-slate-500 mt-0.5">Kejuaraan/Kompetisi</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4" x-show="achievement_type === 'non_akademik'" x-transition>
                                <div>
                                    <label class="form-label text-blue-800">Nama Lomba / Kejuaraan <span class="text-red-500">*</span></label>
                                    <input type="text" name="achievement_name" class="form-input bg-white border-blue-200 focus:border-blue-500 pl-4" placeholder="Contoh: Juara 1 OSN Matematika" :required="track === 'prestasi' && achievement_type === 'non_akademik'">
                                </div>
                                <div>
                                    <label class="form-label text-blue-800">Tingkat Prestasi <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <select name="achievement_level" class="form-input bg-white border-blue-200 focus:border-blue-500 pl-4 appearance-none cursor-pointer" :required="track === 'prestasi' && achievement_type === 'non_akademik'">
                                            <option value="">-- Pilih Tingkat --</option>
                                            <option value="Kecamatan">Kecamatan</option>
                                            <option value="Kabupaten">Kabupaten/Kota</option>
                                            <option value="Provinsi">Provinsi</option>
                                            <option value="Nasional">Nasional</option>
                                            <option value="Internasional">Internasional</option>
                                        </select>
                                        <i class="ph-bold ph-caret-down absolute right-4 top-[14px] text-blue-400 pointer-events-none"></i>
                                    </div>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="form-label text-blue-800">Peringkat / Juara <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <select name="achievement_rank" class="form-input bg-white border-blue-200 focus:border-blue-500 pl-4 appearance-none cursor-pointer" :required="track === 'prestasi' && achievement_type === 'non_akademik'">
                                            <option value="">-- Pilih Juara --</option>
                                            <option value="1">Juara 1 / Emas</option>
                                            <option value="2">Juara 2 / Perak</option>
                                            <option value="3">Juara 3 / Perunggu</option>
                                            <option value="Harapan 1">Harapan 1</option>
                                            <option value="Harapan 2">Harapan 2</option>
                                            <option value="Peserta">Peserta/Finalis</option>
                                        </select>
                                        <i class="ph-bold ph-caret-down absolute right-4 top-[14px] text-blue-400 pointer-events-none"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="button" @click="nextTab('pribadi')" class="px-6 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition flex items-center gap-2 shadow-lg shadow-blue-600/30">
                            Selanjutnya <i class="ph-bold ph-arrow-right"></i>
                        </button>
                    </div>
                </div>

                
                <div x-show="tab === 'pribadi'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" class="space-y-8" style="display: none;">
                    <div class="mb-6">
                        <h3 class="text-xl font-bold text-slate-800 mb-1 flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-sm font-bold">2</span>
                            Data Calon Siswa
                        </h3>
                        <p class="text-slate-500 text-sm ml-10">Isi identitas siswa sesuai dengan Ijazah/Akta Kelahiran.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2 form-group">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="full_name" value="<?php echo e(old('full_name')); ?>" class="form-input" placeholder="Sesuai Ijazah SD/MI" required>
                            <i class="ph-bold ph-user input-icon"></i>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">NISN (10 Digit)</label>
                            <input type="number" name="nisn" value="<?php echo e(old('nisn')); ?>" class="form-input" placeholder="0012345678" required>
                            <i class="ph-bold ph-identification-card input-icon"></i>
                        </div>

                        <div class="form-group">
                            <label class="form-label">NIK (No. KK)</label>
                            <input type="number" name="nik" value="<?php echo e(old('nik')); ?>" class="form-input" placeholder="3207xxxxxxxxxxxx" required>
                            <i class="ph-bold ph-address-book input-icon"></i>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Tempat Lahir</label>
                            <input type="text" name="birth_place" value="<?php echo e(old('birth_place')); ?>" class="form-input" placeholder="Kota/Kabupaten" required>
                            <i class="ph-bold ph-map-pin input-icon"></i>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" name="birth_date" value="<?php echo e(old('birth_date')); ?>" class="form-input" required>
                            <i class="ph-bold ph-calendar-blank input-icon"></i>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Jenis Kelamin</label>
                            <div class="relative">
                                <select name="gender" class="form-input appearance-none cursor-pointer" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="L" <?php echo e(old('gender') == 'L' ? 'selected' : ''); ?>>Laki-laki</option>
                                    <option value="P" <?php echo e(old('gender') == 'P' ? 'selected' : ''); ?>>Perempuan</option>
                                </select>
                                <i class="ph-bold ph-gender-intersex input-icon"></i>
                                <i class="ph-bold ph-caret-down absolute right-4 top-[18px] text-slate-400 pointer-events-none"></i>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Agama</label>
                            <div class="relative">
                                <select name="religion" class="form-input appearance-none cursor-pointer" required>
                                    <option value="">-- Pilih Agama --</option>
                                    <option value="Islam">Islam</option>
                                    <option value="Kristen">Kristen Protestan</option>
                                    <option value="Katholik">Katolik</option>
                                    <option value="Hindu">Hindu</option>
                                    <option value="Buddha">Buddha</option>
                                    <option value="Khonghucu">Khonghucu</option>
                                </select>
                                <i class="ph-bold ph-hands-praying input-icon"></i>
                                <i class="ph-bold ph-caret-down absolute right-4 top-[18px] text-slate-400 pointer-events-none"></i>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">No. WhatsApp Siswa (Opsional)</label>
                            <input type="number" name="student_phone" value="<?php echo e(old('student_phone')); ?>" class="form-input" placeholder="08xxxxxxxxxx">
                            <i class="ph-bold ph-device-mobile input-icon"></i>
                        </div>

                        <div class="md:col-span-2 form-group">
                            <label class="form-label">Alamat Lengkap (Sesuai KK)</label>
                            <textarea name="address" rows="3" class="form-input resize-none h-auto pl-4 leading-relaxed" placeholder="Jalan/Dusun, RT/RW, Desa/Kelurahan, Kecamatan, Kode Pos" required><?php echo e(old('address')); ?></textarea>
                            <p class="text-[10px] text-slate-400 mt-1 ml-1">* Tuliskan detail RT, RW, Desa, dan Kecamatan dengan lengkap.</p>
                        </div>
                    </div>

                    <div class="flex justify-between pt-4 border-t border-slate-50">
                        <button type="button" @click="nextTab('jalur')" class="px-5 py-3 text-slate-500 font-bold hover:text-slate-800 transition flex items-center gap-2">
                            <i class="ph-bold ph-arrow-left"></i> Sebelumnya
                        </button>
                        <button type="button" @click="nextTab('sekolah')" class="px-6 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition flex items-center gap-2 shadow-lg shadow-blue-600/30">
                            Selanjutnya <i class="ph-bold ph-arrow-right"></i>
                        </button>
                    </div>
                </div>

                
                <div x-show="tab === 'sekolah'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" class="space-y-8" style="display: none;">
                    <div class="mb-6">
                        <h3 class="text-xl font-bold text-slate-800 mb-1 flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-sm font-bold">3</span>
                            Sekolah Asal & Nilai
                        </h3>
                        <p class="text-slate-500 text-sm ml-10">Data pendidikan sebelumnya dan nilai rapor.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label class="form-label">Nama Sekolah Asal</label>
                            <input type="text" name="school_origin" value="<?php echo e(old('school_origin')); ?>" class="form-input" placeholder="SDN 1 Lakbok" required>
                            <i class="ph-bold ph-buildings input-icon"></i>
                        </div>
                        <div class="form-group">
                            <label class="form-label">NPSN Sekolah (Opsional)</label>
                            <input type="number" name="npsn_school_origin" value="<?php echo e(old('npsn_school_origin')); ?>" class="form-input" placeholder="Nomor Pokok Sekolah">
                            <i class="ph-bold ph-hash input-icon"></i>
                        </div>
                        <div class="md:col-span-2 form-group">
                            <label class="form-label">Rata-rata Nilai Rapor (Total)</label>
                            <div class="relative">
                                <input type="number" step="0.01" name="average_grade" value="<?php echo e(old('average_grade')); ?>" class="form-input pl-12 text-lg font-bold text-teal-600" placeholder="00.00" required>
                                <div class="absolute left-0 top-[38px] bottom-0 w-12 flex items-center justify-center text-teal-500 text-xl border-r border-slate-200 h-[46px]">
                                    <i class="ph-bold ph-percent"></i>
                                </div>
                            </div>
                            <p class="text-[10px] text-slate-400 mt-1 ml-1">Rata-rata akumulasi nilai rapor kelas 4, 5, dan 6 (Semester 1).</p>
                        </div>

                        
                        <div class="md:col-span-2" x-show="track === 'prestasi' && achievement_type === 'akademik'" x-transition>
                            <div class="bg-teal-50/50 p-6 rounded-2xl border border-teal-100 mt-2">
                                <h4 class="font-bold text-teal-800 mb-4 flex items-center gap-2 text-sm border-b border-teal-200 pb-2">
                                    <i class="ph-bold ph-list-numbers"></i> Input Nilai Rapor (Rata-Rata Semester 7-11)
                                </h4>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                    <?php $__currentLoopData = ['PAI', 'PKn', 'B.Indo', 'MTK', 'IPA', 'IPS', 'SBdP', 'PJOK']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mapel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div>
                                        <label class="text-xs font-bold text-slate-600 mb-1 block"><?php echo e($mapel); ?></label>
                                        <input type="number" step="0.01" name="grade_<?php echo e(Str::slug($mapel)); ?>" class="w-full rounded-lg border-teal-200 focus:border-teal-500 focus:ring-teal-500 text-sm font-bold text-center py-2" placeholder="00" :required="track === 'prestasi' && achievement_type === 'akademik'">
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                <p class="text-[10px] text-teal-600 mt-3 italic">* Masukkan nilai skala 0-100. Wajib diisi untuk Jalur Akademik.</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between pt-4 border-t border-slate-50">
                        <button type="button" @click="nextTab('pribadi')" class="px-5 py-3 text-slate-500 font-bold hover:text-slate-800 transition flex items-center gap-2">
                            <i class="ph-bold ph-arrow-left"></i> Sebelumnya
                        </button>
                        <button type="button" @click="nextTab('orangtua')" class="px-6 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition flex items-center gap-2 shadow-lg shadow-blue-600/30">
                            Selanjutnya <i class="ph-bold ph-arrow-right"></i>
                        </button>
                    </div>
                </div>

                
                <div x-show="tab === 'orangtua'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" class="space-y-8" style="display: none;">
                    <div class="mb-6">
                        <h3 class="text-xl font-bold text-slate-800 mb-1 flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-sm font-bold">4</span>
                            Data Orang Tua / Wali
                        </h3>
                        <p class="text-slate-500 text-sm ml-10">Informasi orang tua atau wali untuk keperluan komunikasi.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label class="form-label">Nama Ayah / Wali</label>
                            <input type="text" name="father_name" value="<?php echo e(old('father_name')); ?>" class="form-input" required>
                            <i class="ph-bold ph-user input-icon"></i>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nama Ibu</label>
                            <input type="text" name="mother_name" value="<?php echo e(old('mother_name')); ?>" class="form-input" required>
                            <i class="ph-bold ph-user-circle input-icon"></i>
                        </div>
                        <div class="form-group">
                            <label class="form-label">No. WhatsApp Orang Tua (Aktif)</label>
                            <input type="number" name="parent_phone" value="<?php echo e(old('parent_phone')); ?>" class="form-input" placeholder="08xxxxxxxxxx" required>
                            <i class="ph-bold ph-whatsapp-logo input-icon"></i>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Pekerjaan Ayah/Wali</label>
                            <input type="text" name="parent_job" value="<?php echo e(old('parent_job')); ?>" class="form-input" placeholder="Wiraswasta/PNS/Buruh/dll">
                            <i class="ph-bold ph-briefcase input-icon"></i>
                        </div>
                        
                        <div class="form-group md:col-span-2">
                            <label class="form-label">Penghasilan Rata-rata Orang Tua (Per Bulan)</label>
                            <div class="relative">
                                <select name="parent_income" class="form-input appearance-none cursor-pointer">
                                    <option value="">-- Pilih Range Penghasilan --</option>
                                    <option value="Kurang dari 500.000">Kurang dari Rp 500.000</option>
                                    <option value="500.000 - 1.000.000">Rp 500.000 - Rp 1.000.000</option>
                                    <option value="1.000.000 - 2.000.000">Rp 1.000.000 - Rp 2.000.000</option>
                                    <option value="2.000.000 - 5.000.000">Rp 2.000.000 - Rp 5.000.000</option>
                                    <option value="Lebih dari 5.000.000">Lebih dari Rp 5.000.000</option>
                                </select>
                                <i class="ph-bold ph-currency-dollar input-icon"></i>
                                <i class="ph-bold ph-caret-down absolute right-4 top-[18px] text-slate-400 pointer-events-none"></i>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between pt-4 border-t border-slate-50">
                        <button type="button" @click="nextTab('sekolah')" class="px-5 py-3 text-slate-500 font-bold hover:text-slate-800 transition flex items-center gap-2">
                            <i class="ph-bold ph-arrow-left"></i> Sebelumnya
                        </button>
                        <button type="button" @click="nextTab('upload')" class="px-6 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition flex items-center gap-2 shadow-lg shadow-blue-600/30">
                            Selanjutnya <i class="ph-bold ph-arrow-right"></i>
                        </button>
                    </div>
                </div>

                
                <div x-show="tab === 'upload'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" class="space-y-8" style="display: none;">
                    <div class="mb-6">
                        <h3 class="text-xl font-bold text-slate-800 mb-1 flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-sm font-bold">5</span>
                            Upload Dokumen
                        </h3>
                        <p class="text-slate-500 text-sm ml-10">Unggah berkas persyaratan dalam format JPG, PNG, atau PDF (Max 2MB).</p>
                    </div>

                    <div class="bg-blue-50/50 border border-blue-100 rounded-2xl p-4 mb-8 flex gap-4 items-start">
                        <div class="bg-blue-100 text-blue-600 p-2 rounded-lg shrink-0">
                            <i class="ph-fill ph-info text-xl"></i>
                        </div>
                        <div class="text-xs sm:text-sm text-slate-600">
                            <ul class="list-disc list-inside space-y-1 opacity-90">
                                <li>Pastikan dokumen hasil scan/foto terbaca jelas (tidak buram).</li>
                                <li>Nama file tidak boleh mengandung karakter unik.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <?php $__currentLoopData = [
                            ['name' => 'file_photo', 'label' => 'Pas Foto (3x4)', 'desc' => 'Latar belakang merah/biru', 'icon' => 'ph-user-focus', 'required' => true],
                            ['name' => 'file_kk', 'label' => 'Kartu Keluarga (KK)', 'desc' => 'Scan asli atau fotocopy legalisir', 'icon' => 'ph-users-three', 'required' => true],
                            ['name' => 'file_akta', 'label' => 'Akta Kelahiran', 'desc' => 'Scan asli dokumen akta', 'icon' => 'ph-scroll', 'required' => true],
                            ['name' => 'file_grades', 'label' => 'Scan Rapor', 'desc' => 'Bagian nilai pengetahuan (Smt 7-11)', 'icon' => 'ph-exam', 'required' => true],
                        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="form-group">
                            <label class="form-label flex justify-between items-center">
                                <span><?php echo e($file['label']); ?> <span class="text-red-500">*</span></span>
                            </label>
                            
                            <div class="relative group">
                                <input type="file" name="<?php echo e($file['name']); ?>" id="<?php echo e($file['name']); ?>" 
                                       class="peer absolute inset-0 w-full h-full opacity-0 z-20 cursor-pointer" 
                                       onchange="previewFile(this)" <?php echo e($file['required'] ? 'required' : ''); ?>>
                                
                                <div id="preview-<?php echo e($file['name']); ?>-default" class="file-box-default border-2 border-dashed border-slate-300 rounded-xl bg-slate-50 p-4 flex flex-col sm:flex-row items-center gap-4 transition-all group-hover:border-blue-400 group-hover:bg-blue-50/30 peer-focus:border-blue-500 peer-focus:ring-4 peer-focus:ring-blue-500/10">
                                    <div class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center text-slate-400 group-hover:text-blue-500 transition-colors shrink-0">
                                        <i class="ph-duotone <?php echo e($file['icon']); ?> text-2xl"></i>
                                    </div>
                                    <div class="text-center sm:text-left flex-1">
                                        <p class="font-bold text-slate-700 text-sm mb-0.5">Klik untuk unggah file</p>
                                        <p class="text-xs text-slate-400"><?php echo e($file['desc']); ?></p>
                                    </div>
                                    <div class="bg-white border border-slate-200 text-slate-600 px-4 py-2 rounded-lg text-xs font-bold shadow-sm group-hover:border-blue-300 group-hover:text-blue-600 transition-colors shrink-0">
                                        Pilih
                                    </div>
                                </div>

                                <div id="preview-<?php echo e($file['name']); ?>-selected" class="hidden border-2 border-solid border-emerald-500 bg-emerald-50/30 rounded-xl p-4 flex items-center justify-between gap-4 transition-all">
                                    <div class="flex items-center gap-3 overflow-hidden">
                                        <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 shrink-0">
                                            <i class="ph-fill ph-check-circle text-xl"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-bold text-emerald-800 text-sm truncate file-name-text">filename.jpg</p>
                                            <p class="text-xs text-emerald-600 font-semibold">Siap diupload</p>
                                        </div>
                                    </div>
                                    <button type="button" class="text-xs font-bold text-red-500 hover:text-red-700 underline shrink-0 z-30 relative" onclick="resetFile('<?php echo e($file['name']); ?>', event)">Ganti</button>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        
                        <div x-show="track === 'prestasi' && achievement_type === 'non_akademik'" x-transition class="pt-6 border-t border-slate-100">
                            <div class="form-group">
                                <label class="form-label flex justify-between items-center text-blue-700">
                                    <span>Bukti Sertifikat Kejuaraan <span class="text-red-500">*</span></span>
                                    <span class="text-[10px] font-bold bg-blue-100 text-blue-600 px-2 py-0.5 rounded">Wajib Jalur Non-Akademik</span>
                                </label>
                                <div class="relative group">
                                    <input type="file" name="file_achievement" id="file_achievement" class="peer absolute inset-0 w-full h-full opacity-0 z-20 cursor-pointer" onchange="previewFile(this)" :required="track === 'prestasi' && achievement_type === 'non_akademik'">
                                    <div id="preview-file_achievement-default" class="border-2 border-dashed border-blue-300 rounded-xl bg-blue-50/50 p-4 flex flex-col sm:flex-row items-center gap-4 transition-all group-hover:bg-blue-100/50">
                                        <div class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center text-blue-500 shrink-0"><i class="ph-duotone ph-medal text-2xl"></i></div>
                                        <div class="text-center sm:text-left flex-1">
                                            <p class="font-bold text-blue-800 text-sm mb-0.5">Unggah Piagam/Sertifikat</p>
                                            <p class="text-xs text-blue-600/70">Scan sertifikat kejuaraan tertinggi (Juara 1/2/3)</p>
                                        </div>
                                        <div class="bg-blue-600 border border-transparent text-white px-4 py-2 rounded-lg text-xs font-bold shadow-lg shadow-blue-500/20 group-hover:bg-blue-700 transition-colors shrink-0">Pilih</div>
                                    </div>
                                    <div id="preview-file_achievement-selected" class="hidden border-2 border-solid border-emerald-500 bg-emerald-50/30 rounded-xl p-4 flex items-center justify-between gap-4">
                                        <div class="flex items-center gap-3 overflow-hidden">
                                            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 shrink-0"><i class="ph-fill ph-check-circle text-xl"></i></div>
                                            <div class="min-w-0"><p class="font-bold text-emerald-800 text-sm truncate file-name-text">filename.jpg</p><p class="text-xs text-emerald-600 font-semibold">Siap diupload</p></div>
                                        </div>
                                        <button type="button" class="text-xs font-bold text-red-500 hover:text-red-700 underline shrink-0 z-30 relative" onclick="resetFile('file_achievement', event)">Hapus</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        <div class="pt-6 border-t border-slate-100">
                            <h4 class="font-bold text-slate-800 mb-4 flex items-center gap-2 text-sm">
                                Dokumen Pendukung <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-500 text-[10px] font-bold uppercase tracking-wider border border-slate-200">Opsional</span>
                            </h4>
                            <div class="form-group">
                                <label class="form-label flex justify-between items-center text-slate-600">
                                    <span>Kartu KIP / PKH / KPS</span>
                                    <span x-show="track === 'afirmasi'" class="text-[10px] font-bold text-orange-500 animate-pulse">Disarankan untuk Jalur Afirmasi</span>
                                </label>
                                <div class="relative group">
                                    <input type="file" name="file_kip" id="file_kip" class="peer absolute inset-0 w-full h-full opacity-0 z-20 cursor-pointer" onchange="previewFile(this)">
                                    <div id="preview-file_kip-default" class="border-2 border-dashed border-slate-300 rounded-xl bg-slate-50/50 p-4 flex flex-col sm:flex-row items-center gap-4 transition-all group-hover:bg-blue-50/50 group-hover:border-blue-300">
                                        <div class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center text-purple-400 shrink-0"><i class="ph-duotone ph-cards text-2xl"></i></div>
                                        <div class="text-center sm:text-left flex-1">
                                            <p class="font-bold text-slate-700 text-sm mb-0.5">Unggah Bukti KIP/PKH (Jika Ada)</p>
                                            <p class="text-xs text-slate-400">Scan kartu bantuan sosial pemerintah</p>
                                        </div>
                                        <div class="bg-white border border-slate-200 text-slate-600 px-4 py-2 rounded-lg text-xs font-bold shadow-sm group-hover:text-blue-600 transition-colors shrink-0">Pilih</div>
                                    </div>
                                    <div id="preview-file_kip-selected" class="hidden border-2 border-solid border-emerald-500 bg-emerald-50/30 rounded-xl p-4 flex items-center justify-between gap-4">
                                        <div class="flex items-center gap-3 overflow-hidden">
                                            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 shrink-0"><i class="ph-fill ph-check-circle text-xl"></i></div>
                                            <div class="min-w-0"><p class="font-bold text-emerald-800 text-sm truncate file-name-text">filename.jpg</p><p class="text-xs text-emerald-600 font-semibold">Siap diupload</p></div>
                                        </div>
                                        <button type="button" class="text-xs font-bold text-red-500 hover:text-red-700 underline shrink-0 z-30 relative" onclick="resetFile('file_kip', event)">Hapus</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="mt-8 bg-slate-50 rounded-2xl p-5 border border-slate-200">
                        <label class="flex items-start gap-3 cursor-pointer group">
                            <div class="relative flex items-center mt-0.5">
                                <input type="checkbox" required class="peer h-5 w-5 cursor-pointer appearance-none rounded-md border-2 border-slate-400 transition-all checked:border-blue-600 checked:bg-blue-600 hover:border-blue-400">
                                <i class="ph-bold ph-check absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-white text-xs opacity-0 transition-opacity peer-checked:opacity-100 pointer-events-none"></i>
                            </div>
                            <div class="text-xs text-slate-600 leading-relaxed select-none group-hover:text-slate-800 transition-colors">
                                <span class="font-bold text-slate-900 block mb-0.5">Pernyataan Tanggung Jawab Mutlak</span>
                                Saya menyatakan bahwa data yang saya isikan adalah BENAR. Apabila dikemudian hari ditemukan ketidaksesuaian, saya bersedia menerima sanksi.
                            </div>
                        </label>
                    </div>

                    <div class="flex justify-between pt-6 border-t border-slate-50 mt-6">
                        <button type="button" @click="nextTab('orangtua')" class="px-5 py-3 text-slate-500 font-bold hover:text-slate-800 transition flex items-center gap-2">
                            <i class="ph-bold ph-arrow-left"></i> Sebelumnya
                        </button>
                        
                        <button type="submit" id="submitBtn" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold rounded-xl shadow-lg shadow-blue-600/30 transition-all transform hover:-translate-y-1 flex items-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed">
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

        <div class="mt-8 text-center">
            <p class="text-xs text-slate-500 font-medium">&copy; <?php echo e(date('Y')); ?> Panitia PPDB SMPN 3 Lakbok. Hak Cipta Dilindungi.</p>
        </div>
    </main>

    <script>
        function previewFile(input) {
            const file = input.files[0];
            const id = input.id;
            const defaultView = document.getElementById(`preview-${id}-default`);
            const selectedView = document.getElementById(`preview-${id}-selected`);
            const nameText = selectedView.querySelector('.file-name-text');

            if (file) {
                defaultView.classList.add('hidden');
                defaultView.classList.remove('flex');
                selectedView.classList.remove('hidden');
                selectedView.classList.add('flex');
                nameText.textContent = file.name;
                selectedView.classList.add('scale-[1.02]');
                setTimeout(() => selectedView.classList.remove('scale-[1.02]'), 200);
            }
        }

        function resetFile(id, event) {
            if(event) event.preventDefault();
            const input = document.getElementById(id);
            const defaultView = document.getElementById(`preview-${id}-default`);
            const selectedView = document.getElementById(`preview-${id}-selected`);
            input.value = ''; 
            defaultView.classList.remove('hidden');
            defaultView.classList.add('flex');
            selectedView.classList.add('hidden');
            selectedView.classList.remove('flex');
        }

        document.getElementById('ppdbForm').addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');
            const text = document.getElementById('btnText');
            const loading = document.getElementById('btnLoading');
            btn.disabled = true;
            text.classList.add('hidden');
            loading.classList.remove('hidden');
        });
    </script>
</body>
</html><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/ppdb/register.blade.php ENDPATH**/ ?>