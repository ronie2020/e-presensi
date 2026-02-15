<?php $__env->startSection('content'); ?>

<style>
    .glass-card {
        background: rgba(15, 23, 42, 0.7);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }
    .form-input-dark {
        @apply w-full pl-12 pr-4 py-3.5 rounded-2xl border border-white/10 bg-slate-900/50 text-white font-bold focus:bg-slate-900 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none placeholder:text-slate-600 placeholder:font-normal;
    }
    .input-icon-dark { 
        @apply absolute left-4 top-[42px] text-xl text-slate-500 transition-colors pointer-events-none; 
    }
    .form-group:focus-within .input-icon-dark { 
        @apply text-blue-400; 
    }
    
    .tab-btn-dark { 
        @apply px-6 py-4 font-bold text-sm whitespace-nowrap transition-all border-b-2 flex items-center gap-2 outline-none select-none; 
    }
    .tab-btn-active { 
        @apply text-blue-400 border-blue-500 bg-blue-500/5; 
    }
    .tab-btn-inactive { 
        @apply text-slate-500 border-transparent hover:text-slate-300 hover:bg-white/5; 
    }

    /* Custom scrollbar for tabs */
    .no-scrollbar::-webkit-scrollbar { display: none; }
    
    .animate-fade-in { animation: fadeIn 0.4s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }

    /* Loader */
    .loader {
        border: 3px solid rgba(255,255,255,0.3);
        border-radius: 50%;
        border-top: 3px solid white;
        width: 20px;
        height: 20px;
        animation: spin 1s linear infinite;
    }
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
</style>

<div class="min-h-screen w-full relative overflow-hidden bg-slate-950 font-sans pb-20">
    
    
    <div class="absolute inset-0 z-0 pointer-events-none">
        <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-blue-950 to-slate-900"></div>
        <div class="absolute top-0 left-0 w-full h-full bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
        <div class="absolute -top-40 -right-40 w-[600px] h-[600px] bg-blue-600/10 rounded-full blur-[120px]"></div>
        <div class="absolute -bottom-40 -left-40 w-[600px] h-[600px] bg-indigo-600/10 rounded-full blur-[120px]"></div>
    </div>

    <div class="relative z-10 max-w-5xl mx-auto px-4 pt-10">
        
        
        <div class="text-center mb-10" data-aos="fade-down">
            <span class="inline-flex items-center gap-2 py-1.5 px-4 rounded-full bg-blue-500/10 text-blue-400 border border-blue-500/20 text-[10px] font-black uppercase tracking-[0.2em] mb-4">
                <span class="w-2 h-2 rounded-full bg-blue-500 animate-ping"></span>
                PPDB Online <?php echo e(date('Y')); ?>

            </span>
            <h1 class="text-4xl md:text-5xl font-black text-white tracking-tight mb-4">
                Formulir <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-500">Pendaftaran</span>
            </h1>
            <p class="text-slate-400 text-sm max-w-xl mx-auto">
                Lengkapi data calon siswa dengan teliti. Gunakan data yang sesuai dengan Akta Kelahiran atau Ijazah.
            </p>
        </div>

        <!-- ERROR ALERT -->
        <?php if($errors->any()): ?>
            <div class="mb-8 p-1 rounded-2xl bg-gradient-to-r from-rose-500 to-pink-600 shadow-lg shadow-rose-900/20 animate-pulse">
                <div class="bg-slate-900 rounded-xl p-5 flex items-start gap-4">
                    <div class="p-2.5 bg-rose-500/20 rounded-full text-rose-400 shrink-0">
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

        
        <div class="glass-card rounded-[2.5rem] overflow-hidden" 
             x-data="{ 
                tab: 'jalur', 
                track: '<?php echo e(old('track', '')); ?>',
                achievement_type: '<?php echo e(old('achievement_type', '')); ?>',
                
                nextTab(target) {
                    this.tab = target;
                    window.scrollTo({top: 100, behavior: 'smooth'});
                }
             }">
            
            
            <div class="flex border-b border-white/5 bg-black/20 overflow-x-auto no-scrollbar">
                <?php
                    $tabs = [
                        'jalur' => ['icon' => 'ph-path', 'label' => 'Jalur'],
                        'pribadi' => ['icon' => 'ph-user', 'label' => 'Data Diri'],
                        'sekolah' => ['icon' => 'ph-buildings', 'label' => 'Sekolah'],
                        'orangtua' => ['icon' => 'ph-users-three', 'label' => 'Orang Tua'],
                        'upload' => ['icon' => 'ph-cloud-arrow-up', 'label' => 'Berkas'],
                    ];
                ?>

                <?php $__currentLoopData = $tabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <button type="button" @click="tab = '<?php echo e($key); ?>'" 
                        :class="tab === '<?php echo e($key); ?>' ? 'tab-btn-active' : 'tab-btn-inactive'" 
                        class="tab-btn-dark">
                        <i class="ph-bold <?php echo e($item['icon']); ?> text-lg"></i> <?php echo e($item['label']); ?>

                    </button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <form id="ppdbForm" action="<?php echo e(route('ppdb.store')); ?>" method="POST" enctype="multipart/form-data" class="p-6 md:p-10 min-h-[400px]">
                <?php echo csrf_field(); ?>

                
                <div x-show="tab === 'jalur'" class="space-y-8 animate-fade-in">
                    <div class="mb-6 border-l-4 border-blue-500 pl-4">
                        <h3 class="text-xl font-bold text-white">Pilih Jalur Pendaftaran</h3>
                        <p class="text-slate-400 text-sm">Tentukan jalur seleksi yang sesuai dengan kondisi Anda.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <?php $__currentLoopData = [
                            ['val' => 'zonasi', 'icon' => 'ph-map-pin', 'label' => 'Zonasi', 'desc' => 'Jarak Domisili'],
                            ['val' => 'prestasi', 'icon' => 'ph-trophy', 'label' => 'Prestasi', 'desc' => 'Akademik/Non'],
                            ['val' => 'afirmasi', 'icon' => 'ph-hand-heart', 'label' => 'Afirmasi', 'desc' => 'KIP/KPS/PKH'],
                            ['val' => 'pindah_tugas', 'icon' => 'ph-briefcase', 'label' => 'Pindah', 'desc' => 'Tugas Ortu'],
                        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label class="cursor-pointer group">
                            <input type="radio" name="track" value="<?php echo e($opt['val']); ?>" class="peer sr-only" x-model="track" required>
                            <div class="p-5 rounded-2xl border-2 border-white/5 bg-slate-900/30 peer-checked:border-blue-500 peer-checked:bg-blue-500/10 transition-all h-full hover:border-blue-500/30 flex flex-col items-center text-center relative overflow-hidden group-hover:bg-slate-900/50">
                                <div class="w-12 h-12 rounded-full bg-slate-800 shadow-sm flex items-center justify-center text-slate-400 peer-checked:text-white peer-checked:bg-blue-500 mb-3 transition-colors">
                                    <i class="ph-duotone <?php echo e($opt['icon']); ?> text-2xl"></i>
                                </div>
                                <span class="font-bold text-slate-400 peer-checked:text-white"><?php echo e($opt['label']); ?></span>
                                <span class="text-[10px] text-slate-500 mt-1 uppercase tracking-wide"><?php echo e($opt['desc']); ?></span>
                                <div class="absolute top-3 right-3 opacity-0 peer-checked:opacity-100 transition-opacity text-blue-500">
                                    <i class="ph-fill ph-check-circle text-xl"></i>
                                </div>
                            </div>
                        </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    
                    <div x-show="track === 'prestasi'" x-transition class="pt-6 border-t border-white/5">
                        <div class="bg-blue-900/20 p-6 rounded-2xl border border-blue-500/20">
                            <h4 class="font-bold text-blue-300 mb-4 flex items-center gap-2">
                                <i class="ph-fill ph-medal text-lg"></i> Detail Prestasi
                            </h4>
                            
                            <div class="mb-5">
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Jenis Prestasi <span class="text-rose-500">*</span></label>
                                <div class="flex flex-col sm:flex-row sm:gap-4 gap-3">
                                    <label class="flex items-center gap-3 cursor-pointer bg-slate-900/50 px-4 py-3 rounded-xl border border-white/10 hover:border-blue-500/50 transition w-full sm:w-auto">
                                        <input type="radio" name="achievement_type" value="akademik" x-model="achievement_type" class="w-4 h-4 text-blue-600 focus:ring-blue-500 bg-slate-800 border-slate-600" :required="track === 'prestasi'">
                                        <div class="leading-tight">
                                            <span class="block text-sm font-bold text-slate-200">Akademik (Rapor)</span>
                                            <span class="block text-[10px] text-slate-500 mt-0.5">Nilai rata-rata rapor</span>
                                        </div>
                                    </label>
                                    <label class="flex items-center gap-3 cursor-pointer bg-slate-900/50 px-4 py-3 rounded-xl border border-white/10 hover:border-blue-500/50 transition w-full sm:w-auto">
                                        <input type="radio" name="achievement_type" value="non_akademik" x-model="achievement_type" class="w-4 h-4 text-blue-600 focus:ring-blue-500 bg-slate-800 border-slate-600" :required="track === 'prestasi'">
                                        <div class="leading-tight">
                                            <span class="block text-sm font-bold text-slate-200">Non-Akademik</span>
                                            <span class="block text-[10px] text-slate-500 mt-0.5">Lomba/Kejuaraan</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4" x-show="achievement_type === 'non_akademik'" x-transition>
                                <div class="form-group">
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Nama Lomba <span class="text-rose-500">*</span></label>
                                    <input type="text" name="achievement_name" class="form-input-dark pl-4" placeholder="Contoh: Juara 1 OSN Matematika" :required="track === 'prestasi' && achievement_type === 'non_akademik'">
                                </div>
                                <div class="form-group relative">
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Tingkat <span class="text-rose-500">*</span></label>
                                    <select name="achievement_level" class="form-input-dark pl-4 appearance-none cursor-pointer" :required="track === 'prestasi' && achievement_type === 'non_akademik'">
                                        <option value="">-- Pilih Tingkat --</option>
                                        <option value="Kecamatan">Kecamatan</option>
                                        <option value="Kabupaten">Kabupaten/Kota</option>
                                        <option value="Provinsi">Provinsi</option>
                                        <option value="Nasional">Nasional</option>
                                        <option value="Internasional">Internasional</option>
                                    </select>
                                    <i class="ph-bold ph-caret-down absolute right-4 top-[42px] text-slate-500 pointer-events-none"></i>
                                </div>
                                <div class="md:col-span-2 form-group relative">
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Peringkat / Juara <span class="text-rose-500">*</span></label>
                                    <select name="achievement_rank" class="form-input-dark pl-4 appearance-none cursor-pointer" :required="track === 'prestasi' && achievement_type === 'non_akademik'">
                                        <option value="">-- Pilih Juara --</option>
                                        <option value="1">Juara 1 / Emas</option>
                                        <option value="2">Juara 2 / Perak</option>
                                        <option value="3">Juara 3 / Perunggu</option>
                                        <option value="Harapan 1">Harapan 1</option>
                                        <option value="Harapan 2">Harapan 2</option>
                                        <option value="Peserta">Peserta/Finalis</option>
                                    </select>
                                    <i class="ph-bold ph-caret-down absolute right-4 top-[42px] text-slate-500 pointer-events-none"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="button" @click="nextTab('pribadi')" class="px-8 py-4 bg-blue-600 text-white font-bold rounded-2xl hover:bg-blue-500 transition-all flex items-center gap-2 shadow-lg shadow-blue-600/20">
                            Lanjut: Data Pribadi <i class="ph-bold ph-arrow-right"></i>
                        </button>
                    </div>
                </div>

                
                <div x-show="tab === 'pribadi'" class="space-y-6 animate-fade-in" style="display: none;">
                    <div class="mb-6 border-l-4 border-blue-500 pl-4">
                        <h3 class="text-xl font-bold text-white">Data Calon Siswa</h3>
                        <p class="text-slate-400 text-sm">Isi identitas siswa sesuai dengan Ijazah/Akta Kelahiran.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2 form-group">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Nama Lengkap</label>
                            <input type="text" name="full_name" value="<?php echo e(old('full_name')); ?>" class="form-input-dark" placeholder="Nama Lengkap sesuai Ijazah" required>
                            <i class="ph-bold ph-user input-icon-dark"></i>
                        </div>
                        
                        <div class="form-group">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">NISN (10 Digit)</label>
                            <input type="number" name="nisn" value="<?php echo e(old('nisn')); ?>" class="form-input-dark" placeholder="0012345678" required>
                            <i class="ph-bold ph-identification-card input-icon-dark"></i>
                        </div>

                        <div class="form-group">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">NIK (No. KK)</label>
                            <input type="number" name="nik" value="<?php echo e(old('nik')); ?>" class="form-input-dark" placeholder="3207xxxxxxxxxxxx" required>
                            <i class="ph-bold ph-address-book input-icon-dark"></i>
                        </div>

                        <div class="form-group">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Tempat Lahir</label>
                            <input type="text" name="birth_place" value="<?php echo e(old('birth_place')); ?>" class="form-input-dark" placeholder="Kota/Kabupaten" required>
                            <i class="ph-bold ph-map-pin input-icon-dark"></i>
                        </div>

                        <div class="form-group">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Tanggal Lahir</label>
                            <input type="date" name="birth_date" value="<?php echo e(old('birth_date')); ?>" class="form-input-dark" required>
                            <i class="ph-bold ph-calendar-blank input-icon-dark"></i>
                        </div>

                        <div class="form-group relative">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Jenis Kelamin</label>
                            <select name="gender" class="form-input-dark appearance-none cursor-pointer" required>
                                <option value="">-- Pilih --</option>
                                <option value="L" <?php echo e(old('gender') == 'L' ? 'selected' : ''); ?>>Laki-laki</option>
                                <option value="P" <?php echo e(old('gender') == 'P' ? 'selected' : ''); ?>>Perempuan</option>
                            </select>
                            <i class="ph-bold ph-gender-intersex input-icon-dark"></i>
                            <i class="ph-bold ph-caret-down absolute right-4 top-[42px] text-slate-500 pointer-events-none"></i>
                        </div>

                        <div class="form-group relative">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Agama</label>
                            <select name="religion" class="form-input-dark appearance-none cursor-pointer" required>
                                <option value="">-- Pilih Agama --</option>
                                <option value="Islam">Islam</option>
                                <option value="Kristen">Kristen Protestan</option>
                                <option value="Katholik">Katolik</option>
                                <option value="Hindu">Hindu</option>
                                <option value="Buddha">Buddha</option>
                                <option value="Khonghucu">Khonghucu</option>
                            </select>
                            <i class="ph-bold ph-hands-praying input-icon-dark"></i>
                            <i class="ph-bold ph-caret-down absolute right-4 top-[42px] text-slate-500 pointer-events-none"></i>
                        </div>

                        <div class="form-group">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">No. WhatsApp Siswa (Opsional)</label>
                            <input type="number" name="student_phone" value="<?php echo e(old('student_phone')); ?>" class="form-input-dark" placeholder="08xxxxxxxxxx">
                            <i class="ph-bold ph-device-mobile input-icon-dark"></i>
                        </div>

                        <div class="md:col-span-2 form-group">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Alamat Lengkap</label>
                            <textarea name="address" rows="3" class="form-input-dark pl-4 h-auto leading-relaxed resize-none" placeholder="Jalan/Dusun, RT/RW, Desa/Kelurahan, Kecamatan, Kode Pos" required><?php echo e(old('address')); ?></textarea>
                            <p class="text-[10px] text-slate-500 mt-1 ml-1">* Sesuai dengan Kartu Keluarga.</p>
                        </div>
                    </div>

                    <div class="flex justify-between pt-10 border-t border-white/5">
                        <button type="button" @click="nextTab('jalur')" class="px-6 py-4 text-slate-400 font-bold hover:text-white transition-all flex items-center gap-2">
                            <i class="ph-bold ph-arrow-left"></i> Kembali
                        </button>
                        <button type="button" @click="nextTab('sekolah')" class="px-8 py-4 bg-blue-600 text-white font-bold rounded-2xl hover:bg-blue-500 transition-all flex items-center gap-2 shadow-lg shadow-blue-600/20">
                            Selanjutnya <i class="ph-bold ph-arrow-right"></i>
                        </button>
                    </div>
                </div>

                
                <div x-show="tab === 'sekolah'" class="space-y-6 animate-fade-in" style="display: none;">
                    <div class="mb-6 border-l-4 border-blue-500 pl-4">
                        <h3 class="text-xl font-bold text-white">Sekolah Asal & Nilai</h3>
                        <p class="text-slate-400 text-sm">Data pendidikan sebelumnya dan nilai rapor.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Nama Sekolah Asal</label>
                            <input type="text" name="school_origin" value="<?php echo e(old('school_origin')); ?>" class="form-input-dark" placeholder="SDN 1 Lakbok" required>
                            <i class="ph-bold ph-buildings input-icon-dark"></i>
                        </div>
                        <div class="form-group">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">NPSN Sekolah (Opsional)</label>
                            <input type="number" name="npsn_school_origin" value="<?php echo e(old('npsn_school_origin')); ?>" class="form-input-dark" placeholder="Nomor Pokok Sekolah">
                            <i class="ph-bold ph-hash input-icon-dark"></i>
                        </div>
                        <div class="md:col-span-2 form-group">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Rata-rata Nilai Rapor (Total)</label>
                            <div class="relative">
                                <input type="number" step="0.01" name="average_grade" value="<?php echo e(old('average_grade')); ?>" class="form-input-dark pl-12 text-lg font-bold text-emerald-400" placeholder="00.00" required>
                                <div class="absolute left-0 top-0 bottom-0 w-12 flex items-center justify-center text-emerald-500 text-xl border-r border-white/10">
                                    <i class="ph-bold ph-percent"></i>
                                </div>
                            </div>
                            <p class="text-[10px] text-slate-500 mt-1 ml-1">Rata-rata akumulasi nilai rapor kelas 4, 5, dan 6 (Semester 1).</p>
                        </div>

                        
                        <div class="md:col-span-2" x-show="track === 'prestasi' && achievement_type === 'akademik'" x-transition>
                            <div class="bg-emerald-900/10 p-6 rounded-2xl border border-emerald-500/20 mt-2">
                                <h4 class="font-bold text-emerald-400 mb-4 flex items-center gap-2 text-sm border-b border-emerald-500/20 pb-2">
                                    <i class="ph-bold ph-list-numbers"></i> Input Nilai Rapor (Rata-Rata Semester 7-11)
                                </h4>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                    <?php $__currentLoopData = ['PAI', 'PKn', 'B.Indo', 'MTK', 'IPA', 'IPS', 'SBdP', 'PJOK']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mapel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div>
                                        <label class="text-xs font-bold text-slate-400 mb-1 block"><?php echo e($mapel); ?></label>
                                        <input type="number" step="0.01" name="grade_<?php echo e(Str::slug($mapel)); ?>" class="w-full rounded-lg bg-slate-900 border-emerald-500/30 text-white focus:border-emerald-400 focus:ring-emerald-500/20 text-sm font-bold text-center py-2" placeholder="00" :required="track === 'prestasi' && achievement_type === 'akademik'">
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between pt-10 border-t border-white/5">
                        <button type="button" @click="nextTab('pribadi')" class="px-6 py-4 text-slate-400 font-bold hover:text-white transition-all flex items-center gap-2">
                            <i class="ph-bold ph-arrow-left"></i> Kembali
                        </button>
                        <button type="button" @click="nextTab('orangtua')" class="px-8 py-4 bg-blue-600 text-white font-bold rounded-2xl hover:bg-blue-500 transition-all flex items-center gap-2 shadow-lg shadow-blue-600/20">
                            Selanjutnya <i class="ph-bold ph-arrow-right"></i>
                        </button>
                    </div>
                </div>

                
                <div x-show="tab === 'orangtua'" class="space-y-6 animate-fade-in" style="display: none;">
                    <div class="mb-6 border-l-4 border-blue-500 pl-4">
                        <h3 class="text-xl font-bold text-white">Data Orang Tua / Wali</h3>
                        <p class="text-slate-400 text-sm">Informasi orang tua atau wali untuk keperluan komunikasi.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Nama Ayah / Wali</label>
                            <input type="text" name="father_name" value="<?php echo e(old('father_name')); ?>" class="form-input-dark" required>
                            <i class="ph-bold ph-user input-icon-dark"></i>
                        </div>
                        <div class="form-group">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Nama Ibu</label>
                            <input type="text" name="mother_name" value="<?php echo e(old('mother_name')); ?>" class="form-input-dark" required>
                            <i class="ph-bold ph-user-circle input-icon-dark"></i>
                        </div>
                        <div class="form-group">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">No. WhatsApp Orang Tua</label>
                            <input type="number" name="parent_phone" value="<?php echo e(old('parent_phone')); ?>" class="form-input-dark" placeholder="08xxxxxxxxxx" required>
                            <i class="ph-bold ph-whatsapp-logo input-icon-dark"></i>
                        </div>
                        <div class="form-group">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Pekerjaan Ayah/Wali</label>
                            <input type="text" name="parent_job" value="<?php echo e(old('parent_job')); ?>" class="form-input-dark" placeholder="Wiraswasta/PNS/Buruh">
                            <i class="ph-bold ph-briefcase input-icon-dark"></i>
                        </div>
                        
                        <div class="form-group md:col-span-2 relative">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Penghasilan Orang Tua</label>
                            <select name="parent_income" class="form-input-dark appearance-none cursor-pointer">
                                <option value="">-- Pilih Range Penghasilan --</option>
                                <option value="Kurang dari 500.000">Kurang dari Rp 500.000</option>
                                <option value="500.000 - 1.000.000">Rp 500.000 - Rp 1.000.000</option>
                                <option value="1.000.000 - 2.000.000">Rp 1.000.000 - Rp 2.000.000</option>
                                <option value="2.000.000 - 5.000.000">Rp 2.000.000 - Rp 5.000.000</option>
                                <option value="Lebih dari 5.000.000">Lebih dari Rp 5.000.000</option>
                            </select>
                            <i class="ph-bold ph-currency-dollar input-icon-dark"></i>
                            <i class="ph-bold ph-caret-down absolute right-4 top-[42px] text-slate-500 pointer-events-none"></i>
                        </div>
                    </div>

                    <div class="flex justify-between pt-10 border-t border-white/5">
                        <button type="button" @click="nextTab('sekolah')" class="px-6 py-4 text-slate-400 font-bold hover:text-white transition-all flex items-center gap-2">
                            <i class="ph-bold ph-arrow-left"></i> Kembali
                        </button>
                        <button type="button" @click="nextTab('upload')" class="px-8 py-4 bg-blue-600 text-white font-bold rounded-2xl hover:bg-blue-500 transition-all flex items-center gap-2 shadow-lg shadow-blue-600/20">
                            Selanjutnya <i class="ph-bold ph-arrow-right"></i>
                        </button>
                    </div>
                </div>

                
                <div x-show="tab === 'upload'" class="space-y-6 animate-fade-in" style="display: none;">
                    <div class="mb-6 border-l-4 border-blue-500 pl-4">
                        <h3 class="text-xl font-bold text-white">Upload Dokumen</h3>
                        <p class="text-slate-400 text-sm">Unggah berkas persyaratan dalam format JPG, PNG, atau PDF (Max 2MB).</p>
                    </div>

                    <div class="space-y-6">
                        <?php $__currentLoopData = [
                            ['name' => 'file_photo', 'label' => 'Pas Foto (3x4)', 'desc' => 'Latar belakang merah/biru', 'icon' => 'ph-user-focus', 'required' => true],
                            ['name' => 'file_kk', 'label' => 'Kartu Keluarga (KK)', 'desc' => 'Scan asli atau fotocopy legalisir', 'icon' => 'ph-users-three', 'required' => true],
                            ['name' => 'file_akta', 'label' => 'Akta Kelahiran', 'desc' => 'Scan asli dokumen akta', 'icon' => 'ph-scroll', 'required' => true],
                            ['name' => 'file_grades', 'label' => 'Scan Rapor', 'desc' => 'Bagian nilai pengetahuan (Smt 7-11)', 'icon' => 'ph-exam', 'required' => true],
                        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="form-group">
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1"><?php echo e($file['label']); ?> <span class="text-rose-500">*</span></label>
                            
                            <div class="relative group">
                                <input type="file" name="<?php echo e($file['name']); ?>" id="<?php echo e($file['name']); ?>" 
                                       class="peer absolute inset-0 w-full h-full opacity-0 z-20 cursor-pointer" 
                                       onchange="previewFile(this)" <?php echo e($file['required'] ? 'required' : ''); ?>>
                                
                                <div id="preview-<?php echo e($file['name']); ?>-default" class="file-box-default border border-white/10 rounded-2xl bg-slate-900/50 p-4 flex flex-col sm:flex-row items-center gap-4 transition-all group-hover:border-blue-500/50 group-hover:bg-slate-900 peer-focus:border-blue-500 peer-focus:ring-4 peer-focus:ring-blue-500/10">
                                    <div class="w-12 h-12 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 group-hover:text-blue-400 transition-colors shrink-0 border border-white/5">
                                        <i class="ph-duotone <?php echo e($file['icon']); ?> text-2xl"></i>
                                    </div>
                                    <div class="text-center sm:text-left flex-1">
                                        <p class="font-bold text-slate-300 text-sm mb-0.5">Klik untuk unggah file</p>
                                        <p class="text-xs text-slate-500"><?php echo e($file['desc']); ?></p>
                                    </div>
                                    <div class="bg-slate-800 border border-white/10 text-slate-400 px-4 py-2 rounded-lg text-xs font-bold shadow-sm group-hover:bg-blue-600 group-hover:text-white group-hover:border-transparent transition-colors shrink-0">
                                        Pilih
                                    </div>
                                </div>

                                <div id="preview-<?php echo e($file['name']); ?>-selected" class="hidden border border-emerald-500/50 bg-emerald-500/10 rounded-2xl p-4 flex items-center justify-between gap-4 transition-all">
                                    <div class="flex items-center gap-3 overflow-hidden">
                                        <div class="w-10 h-10 rounded-full bg-emerald-500/20 flex items-center justify-center text-emerald-400 shrink-0">
                                            <i class="ph-fill ph-check-circle text-xl"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-bold text-emerald-300 text-sm truncate file-name-text">filename.jpg</p>
                                            <p class="text-xs text-emerald-500/70 font-semibold">Siap diupload</p>
                                        </div>
                                    </div>
                                    <button type="button" class="text-xs font-bold text-rose-400 hover:text-rose-300 underline shrink-0 z-30 relative" onclick="resetFile('<?php echo e($file['name']); ?>', event)">Ganti</button>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        
                        <div x-show="track === 'prestasi' && achievement_type === 'non_akademik'" x-transition class="pt-6 border-t border-white/5">
                            <div class="form-group">
                                <label class="block text-xs font-bold text-blue-400 uppercase mb-2 ml-1">
                                    Bukti Sertifikat Kejuaraan <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative group">
                                    <input type="file" name="file_achievement" id="file_achievement" class="peer absolute inset-0 w-full h-full opacity-0 z-20 cursor-pointer" onchange="previewFile(this)" :required="track === 'prestasi' && achievement_type === 'non_akademik'">
                                    <div id="preview-file_achievement-default" class="border border-blue-500/30 rounded-2xl bg-blue-500/5 p-4 flex flex-col sm:flex-row items-center gap-4 transition-all group-hover:bg-blue-500/10">
                                        <div class="w-12 h-12 rounded-full bg-blue-900/50 flex items-center justify-center text-blue-400 shrink-0 border border-blue-500/20"><i class="ph-duotone ph-medal text-2xl"></i></div>
                                        <div class="text-center sm:text-left flex-1">
                                            <p class="font-bold text-blue-300 text-sm mb-0.5">Unggah Sertifikat Juara</p>
                                            <p class="text-xs text-blue-400/60">Dokumen asli kejuaraan tertinggi</p>
                                        </div>
                                        <div class="bg-blue-600 text-white px-4 py-2 rounded-lg text-xs font-bold shadow-lg shadow-blue-500/20 group-hover:bg-blue-500 transition-colors shrink-0">Pilih</div>
                                    </div>
                                    <div id="preview-file_achievement-selected" class="hidden border border-emerald-500/50 bg-emerald-500/10 rounded-2xl p-4 flex items-center justify-between gap-4">
                                        <div class="flex items-center gap-3 overflow-hidden">
                                            <div class="w-10 h-10 rounded-full bg-emerald-500/20 flex items-center justify-center text-emerald-400 shrink-0"><i class="ph-fill ph-check-circle text-xl"></i></div>
                                            <div class="min-w-0"><p class="font-bold text-emerald-300 text-sm truncate file-name-text">filename.jpg</p><p class="text-xs text-emerald-500/70 font-semibold">Siap diupload</p></div>
                                        </div>
                                        <button type="button" class="text-xs font-bold text-rose-400 hover:text-rose-300 underline shrink-0 z-30 relative" onclick="resetFile('file_achievement', event)">Hapus</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        <div class="pt-6 border-t border-white/5">
                            <h4 class="font-bold text-white mb-4 flex items-center gap-2 text-sm">
                                Dokumen Pendukung <span class="px-2 py-0.5 rounded-md bg-slate-800 text-slate-400 text-[10px] font-bold uppercase tracking-wider border border-white/10">Opsional</span>
                            </h4>
                            <div class="form-group">
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Kartu KIP / PKH / KPS</label>
                                <div class="relative group">
                                    <input type="file" name="file_kip" id="file_kip" class="peer absolute inset-0 w-full h-full opacity-0 z-20 cursor-pointer" onchange="previewFile(this)">
                                    <div id="preview-file_kip-default" class="border border-white/10 rounded-2xl bg-slate-900/50 p-4 flex flex-col sm:flex-row items-center gap-4 transition-all group-hover:bg-slate-900">
                                        <div class="w-12 h-12 rounded-full bg-slate-800 flex items-center justify-center text-purple-400 shrink-0 border border-white/5"><i class="ph-duotone ph-cards text-2xl"></i></div>
                                        <div class="text-center sm:text-left flex-1">
                                            <p class="font-bold text-slate-300 text-sm mb-0.5">Unggah Bukti KIP/PKH</p>
                                            <p class="text-xs text-slate-500">Jika memiliki kartu bantuan pemerintah</p>
                                        </div>
                                        <div class="bg-slate-800 border border-white/10 text-slate-400 px-4 py-2 rounded-lg text-xs font-bold shadow-sm group-hover:bg-purple-600 group-hover:text-white group-hover:border-transparent transition-colors shrink-0">Pilih</div>
                                    </div>
                                    <div id="preview-file_kip-selected" class="hidden border border-emerald-500/50 bg-emerald-500/10 rounded-2xl p-4 flex items-center justify-between gap-4">
                                        <div class="flex items-center gap-3 overflow-hidden">
                                            <div class="w-10 h-10 rounded-full bg-emerald-500/20 flex items-center justify-center text-emerald-400 shrink-0"><i class="ph-fill ph-check-circle text-xl"></i></div>
                                            <div class="min-w-0"><p class="font-bold text-emerald-300 text-sm truncate file-name-text">filename.jpg</p><p class="text-xs text-emerald-500/70 font-semibold">Siap diupload</p></div>
                                        </div>
                                        <button type="button" class="text-xs font-bold text-rose-400 hover:text-rose-300 underline shrink-0 z-30 relative" onclick="resetFile('file_kip', event)">Hapus</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="mt-8 bg-slate-900/50 rounded-2xl p-5 border border-white/10">
                        <label class="flex items-start gap-3 cursor-pointer group">
                            <div class="relative flex items-center mt-0.5">
                                <input type="checkbox" required class="peer h-5 w-5 cursor-pointer appearance-none rounded-md border-2 border-slate-600 transition-all checked:border-blue-500 checked:bg-blue-600 hover:border-blue-400">
                                <i class="ph-bold ph-check absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-white text-xs opacity-0 transition-opacity peer-checked:opacity-100 pointer-events-none"></i>
                            </div>
                            <div class="text-xs text-slate-400 leading-relaxed select-none group-hover:text-slate-300 transition-colors">
                                <span class="font-bold text-white block mb-0.5">Pernyataan Tanggung Jawab Mutlak</span>
                                Saya menyatakan bahwa data yang saya isikan adalah BENAR. Apabila dikemudian hari ditemukan ketidaksesuaian, saya bersedia menerima sanksi.
                            </div>
                        </label>
                    </div>

                    <div class="flex justify-between pt-6 border-t border-white/5 mt-6">
                        <button type="button" @click="nextTab('orangtua')" class="px-6 py-4 text-slate-400 font-bold hover:text-white transition-all flex items-center gap-2">
                            <i class="ph-bold ph-arrow-left"></i> Sebelumnya
                        </button>
                        
                        <button type="submit" id="submitBtn" class="px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold rounded-2xl shadow-lg shadow-blue-600/30 transition-all transform hover:-translate-y-1 flex items-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed">
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

        <div class="mt-8 text-center text-xs text-slate-500 font-medium tracking-wide">
            &copy; <?php echo e(date('Y')); ?> Panitia PPDB SMPN 3 Lakbok. Hak Cipta Dilindungi.
        </div>
    </div>

     
    <script>
        // 1. Validasi File Size & Preview
        function validateAndPreview(input) {
            const file = input.files[0];
            const id = input.id;
            const defaultView = document.getElementById(`preview-${id}-default`);
            const selectedView = document.getElementById(`preview-${id}-selected`);
            const errorView = document.getElementById(`preview-${id}-error`);
            const submitBtn = document.getElementById('submitBtn');

            // Reset
            defaultView.classList.remove('hidden');
            selectedView.classList.add('hidden');
            errorView.classList.add('hidden');
            errorView.classList.remove('flex');

            if (file) {
                // Cek ukuran > 2MB (2 * 1024 * 1024)
                if (file.size > 2097152) {
                    input.value = ''; // Reset input
                    errorView.classList.remove('hidden');
                    errorView.classList.add('flex');
                    alert('Maaf, ukuran file ' + file.name + ' terlalu besar. Maksimal 2MB.');
                    return;
                }

                defaultView.classList.add('hidden');
                selectedView.classList.remove('hidden');
                selectedView.classList.add('flex');
                selectedView.querySelector('.file-name-text').textContent = file.name;
                selectedView.querySelector('.file-size-text').textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
            }
        }

        // 2. LocalStorage Auto-Save Logic
        document.addEventListener('DOMContentLoaded', () => {
            const inputs = document.querySelectorAll('.save-local');
            
            // Load data
            let hasData = false;
            inputs.forEach(input => {
                const key = 'ppdb_' + input.dataset.key;
                const savedValue = localStorage.getItem(key);
                if(savedValue) {
                    input.value = savedValue;
                    hasData = true;
                }
                
                // Save on input
                input.addEventListener('input', (e) => {
                    localStorage.setItem(key, e.target.value);
                });
            });

            if(hasData) document.getElementById('draft-alert').classList.remove('hidden');

            // Clear storage on successful submit
            document.getElementById('ppdbForm').addEventListener('submit', function() {
                const btn = document.getElementById('submitBtn');
                const text = document.getElementById('btnText');
                const loading = document.getElementById('btnLoading');
                
                btn.disabled = true;
                text.classList.add('hidden');
                loading.classList.remove('hidden');

                // Kita asumsikan sukses kirim, hapus storage
                // Idealnya dihapus di halaman 'Success', tapi di sini juga oke
                inputs.forEach(input => localStorage.removeItem('ppdb_' + input.dataset.key));
                localStorage.removeItem('ppdb_track');
                localStorage.removeItem('ppdb_achievement_type');
            });
        });
    </script>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/ppdb/register.blade.php ENDPATH**/ ?>