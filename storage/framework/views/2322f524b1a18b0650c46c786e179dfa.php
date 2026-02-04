
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
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/airbnb.css">

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <div class="mb-6 flex justify-between items-end">
                <div>
                    <h1 class="text-3xl font-black text-blue-900 tracking-tight">Edit Buku Induk Siswa</h1>
                    <p class="text-slate-500 text-sm mt-1">Lengkapi data detail siswa: <span class="font-bold text-blue-900 bg-blue-50 px-2 py-0.5 rounded"><?php echo e($student->name); ?></span></p>
                </div>
                
                <a href="<?php echo e(route('students.index')); ?>" class="btn-back-confirm px-4 py-2 bg-white border border-slate-300 rounded-xl text-sm font-bold text-slate-600 hover:bg-blue-50 hover:text-blue-900 hover:border-blue-200 transition-all shadow-sm flex items-center gap-2 group">
                    <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali
                </a>
            </div>

            
            <?php if($errors->any()): ?>
                <div class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-xl text-sm flex items-start gap-3 shadow-sm">
                    <i class="ph-fill ph-warning-circle text-lg shrink-0 mt-0.5"></i>
                    <div>
                        <span class="font-bold block mb-1">Gagal Menyimpan Perubahan:</span>
                        <ul class="list-disc list-inside">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden" x-data="{ tab: 'pribadi' }">
                
                
                <div class="bg-blue-50/30 border-b border-slate-200 px-6 pt-4 flex gap-2 overflow-x-auto custom-scrollbar">
                    <?php $__currentLoopData = ['pribadi' => 'A. Pribadi', 'tempat_tinggal' => 'B. Alamat', 'kesehatan' => 'C. Kesehatan', 'pendidikan' => 'D. Pendidikan', 'orangtua' => 'E. Ortu & Wali', 'tamat' => 'F. Mutasi & Tamat']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <button type="button" @click="tab = '<?php echo e($key); ?>'" 
                            :class="{ 
                                'bg-white text-blue-900 shadow-sm border-slate-200 border-b-white ring-t-2 ring-blue-900': tab === '<?php echo e($key); ?>', 
                                'text-slate-500 hover:text-blue-700 hover:bg-blue-50/50 border-transparent': tab !== '<?php echo e($key); ?>' 
                            }" 
                            class="px-5 py-3 rounded-t-xl border-t border-x font-bold text-sm whitespace-nowrap transition-all relative z-10 flex items-center gap-2">
                            <span x-show="tab === '<?php echo e($key); ?>'" class="w-1.5 h-1.5 rounded-full bg-blue-900"></span>
                            <?php echo e($label); ?>

                        </button>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                
                <form id="edit-student-form" action="<?php echo e(route('students.update', $student->id)); ?>" method="POST" enctype="multipart/form-data" class="p-8">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    
                    
                    <div x-show="tab === 'pribadi'" class="space-y-8">
                        
                        
                        <div class="flex flex-col md:flex-row gap-8 items-start border-b border-slate-100 pb-8">
                            <div class="w-full md:w-1/4 flex flex-col items-center gap-4">
                                <div class="relative group">
                                    <div class="w-40 h-48 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-300 group-hover:border-blue-500 flex items-center justify-center overflow-hidden shadow-sm transition-colors">
                                        <?php if($student->photo_path): ?>
                                            <img id="photo-preview" src="<?php echo e(asset('storage/' . $student->photo_path)); ?>" alt="Foto Siswa" class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <img id="photo-preview" src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($student->name)); ?>&background=random&size=200" alt="Placeholder" class="w-full h-full object-cover opacity-50">
                                        <?php endif; ?>
                                    </div>
                                    <label for="photo-input" class="absolute inset-0 flex items-center justify-center bg-blue-900/60 backdrop-blur-[2px] text-white text-xs font-bold opacity-0 group-hover:opacity-100 transition-all cursor-pointer rounded-2xl">
                                        <i class="ph-bold ph-camera mr-2"></i> Ganti Foto
                                    </label>
                                </div>
                                <div class="text-center">
                                    <input type="file" name="photo" id="photo-input" accept="image/*" class="hidden"/>
                                    <p class="text-[10px] text-slate-400 mt-1 font-medium">Format: JPG/PNG, Max: 2MB</p>
                                </div>
                            </div>

                            <div class="w-full md:w-3/4 grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Nama Lengkap *</label>
                                    <input type="text" name="name" value="<?php echo e(old('name', $student->name)); ?>" required class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 font-bold text-slate-800">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Nama Panggilan</label>
                                    <input type="text" name="nickname" value="<?php echo e(old('nickname', $student->nickname)); ?>" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900">
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">NIS (Sekolah)</label>
                                    <input type="text" name="nis" value="<?php echo e(old('nis', $student->nis)); ?>" placeholder="Nomor Induk Sekolah" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 font-mono">
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">NISN (Nasional) *</label>
                                    <input type="text" name="student_id" value="<?php echo e(old('student_id', $student->student_id)); ?>" required class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 font-mono bg-blue-50/30 text-blue-900 font-bold">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Tempat, Tanggal Lahir</label>
                                    <div class="flex gap-2">
                                        <input type="text" name="pob" value="<?php echo e(old('pob', $student->pob)); ?>" placeholder="Kota" class="w-1/2 rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900">
                                        
                                        <div class="relative w-1/2">
                                            <input type="text" name="dob" value="<?php echo e(old('dob', $student->dob)); ?>" placeholder="dd/mm/yyyy" class="datepicker w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 bg-white">
                                            <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-calendar-blank"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Jenis Kelamin</label>
                                    <select name="gender" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900">
                                        <option value="L" <?php echo e(old('gender', $student->gender) == 'L' ? 'selected' : ''); ?>>Laki-laki</option>
                                        <option value="P" <?php echo e(old('gender', $student->gender) == 'P' ? 'selected' : ''); ?>>Perempuan</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Agama</label>
                                    <select name="religion" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900">
                                        <option value="Islam" <?php echo e(old('religion', $student->religion) == 'Islam' ? 'selected' : ''); ?>>Islam</option>
                                        <option value="Kristen" <?php echo e(old('religion', $student->religion) == 'Kristen' ? 'selected' : ''); ?>>Kristen</option>
                                        <option value="Katolik" <?php echo e(old('religion', $student->religion) == 'Katolik' ? 'selected' : ''); ?>>Katolik</option>
                                        <option value="Hindu" <?php echo e(old('religion', $student->religion) == 'Hindu' ? 'selected' : ''); ?>>Hindu</option>
                                        <option value="Buddha" <?php echo e(old('religion', $student->religion) == 'Buddha' ? 'selected' : ''); ?>>Buddha</option>
                                        <option value="Konghucu" <?php echo e(old('religion', $student->religion) == 'Konghucu' ? 'selected' : ''); ?>>Konghucu</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Kewarganegaraan</label>
                                    <input type="text" name="citizenship" value="<?php echo e(old('citizenship', $student->citizenship ?? 'WNI')); ?>" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Anak ke-</label>
                                    <input type="number" name="birth_order" value="<?php echo e(old('birth_order', $student->birth_order)); ?>" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Status Yatim</label>
                                    <select name="orphan_status" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900">
                                        <option value="Lengkap" <?php echo e(old('orphan_status', $student->orphan_status) == 'Lengkap' ? 'selected' : ''); ?>>Lengkap</option>
                                        <option value="Yatim" <?php echo e(old('orphan_status', $student->orphan_status) == 'Yatim' ? 'selected' : ''); ?>>Yatim</option>
                                        <option value="Piatu" <?php echo e(old('orphan_status', $student->orphan_status) == 'Piatu' ? 'selected' : ''); ?>>Piatu</option>
                                        <option value="Yatim Piatu" <?php echo e(old('orphan_status', $student->orphan_status) == 'Yatim Piatu' ? 'selected' : ''); ?>>Yatim Piatu</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Jumlah Saudara</label>
                                    <div class="flex gap-2 text-sm items-center">
                                        <input type="number" name="siblings_count" value="<?php echo e(old('siblings_count', $student->siblings_count)); ?>" placeholder="Kandung" class="w-1/3 rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 text-center" title="Kandung">
                                        <input type="number" name="step_siblings_count" value="<?php echo e(old('step_siblings_count', $student->step_siblings_count)); ?>" placeholder="Tiri" class="w-1/3 rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 text-center" title="Tiri">
                                        <input type="number" name="adoptive_siblings_count" value="<?php echo e(old('adoptive_siblings_count', $student->adoptive_siblings_count)); ?>" placeholder="Angkat" class="w-1/3 rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 text-center" title="Angkat">
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-1 ml-1">Urutan: Kandung / Tiri / Angkat</p>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Bahasa Sehari-hari</label>
                                    <input type="text" name="daily_language" value="<?php echo e(old('daily_language', $student->daily_language)); ?>" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Kelas Saat Ini *</label>
                                    <select name="class_id" required class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900">
                                        <option value="">-- Pilih Kelas --</option>
                                        <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($c->id); ?>" <?php echo e(old('class_id', $student->class_id) == $c->id ? 'selected' : ''); ?>><?php echo e($c->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">RFID ID (Kartu)</label>
                                    <input type="text" name="rfid_id" value="<?php echo e(old('rfid_id', $student->rfid_id)); ?>" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 font-mono bg-slate-50">
                                </div>
                            </div>
                        </div>
                    </div>


                    
                    <div x-show="tab === 'tempat_tinggal'" class="space-y-6" style="display: none;">
                        <h3 class="text-lg font-bold text-blue-900 border-b border-slate-100 pb-2 mb-4">Keterangan Tempat Tinggal</h3>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Alamat Lengkap</label>
                            <textarea name="address" rows="3" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900"><?php echo e(old('address', $student->address)); ?></textarea>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Nomor Telepon/HP (Siswa)</label>
                                <input type="text" name="phone" value="<?php echo e(old('phone', $student->phone)); ?>" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Tinggal Bersama</label>
                                <select name="living_with" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900">
                                    <option value="Orang Tua" <?php echo e(old('living_with', $student->living_with) == 'Orang Tua' ? 'selected' : ''); ?>>Orang Tua</option>
                                    <option value="Wali" <?php echo e(old('living_with', $student->living_with) == 'Wali' ? 'selected' : ''); ?>>Wali</option>
                                    <option value="Asrama" <?php echo e(old('living_with', $student->living_with) == 'Asrama' ? 'selected' : ''); ?>>Asrama</option>
                                    <option value="Kost" <?php echo e(old('living_with', $student->living_with) == 'Kost' ? 'selected' : ''); ?>>Kost</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Jarak ke Sekolah</label>
                                <input type="text" name="distance_to_school" value="<?php echo e(old('distance_to_school', $student->distance_to_school)); ?>" placeholder="Contoh: 1 km" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Transportasi</label>
                                <input type="text" name="transport_mode" value="<?php echo e(old('transport_mode', $student->transport_mode)); ?>" placeholder="Jalan Kaki/Motor/Angkot" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900">
                            </div>
                        </div>
                    </div>

                    
                    <div x-show="tab === 'kesehatan'" class="space-y-6" style="display: none;">
                        <h3 class="text-lg font-bold text-blue-900 border-b border-slate-100 pb-2 mb-4">Keterangan Kesehatan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Golongan Darah</label>
                                <select name="blood_type" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900">
                                    <?php $__currentLoopData = ['-', 'A', 'B', 'AB', 'O']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($b); ?>" <?php echo e(old('blood_type', $student->blood_type) == $b ? 'selected' : ''); ?>><?php echo e($b); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Berat Badan (kg)</label>
                                <input type="number" name="weight" value="<?php echo e(old('weight', $student->weight)); ?>" step="0.1" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Tinggi Badan (cm)</label>
                                <input type="number" name="height" value="<?php echo e(old('height', $student->height)); ?>" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Riwayat Penyakit</label>
                            <input type="text" name="history_disease" value="<?php echo e(old('history_disease', $student->history_disease)); ?>" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Kelainan Jasmani</label>
                            <input type="text" name="physical_abnormalities" value="<?php echo e(old('physical_abnormalities', $student->physical_abnormalities)); ?>" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900">
                        </div>
                    </div>

                    
                    <div x-show="tab === 'pendidikan'" class="space-y-6" style="display: none;">
                        <h3 class="text-lg font-bold text-blue-900 border-b border-slate-100 pb-2 mb-4">Pendidikan Sebelumnya</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Asal Sekolah Dasar (SD)</label>
                                <input type="text" name="school_origin" value="<?php echo e(old('school_origin', $student->school_origin)); ?>" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">No. Ijazah</label>
                                <input type="text" name="prev_diploma_no" value="<?php echo e(old('prev_diploma_no', $student->prev_diploma_no)); ?>" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Tanggal Ijazah</label>
                                <div class="relative">
                                    <input type="text" name="prev_exam_date" value="<?php echo e(old('prev_exam_date', $student->prev_exam_date)); ?>" class="datepicker w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900" placeholder="dd/mm/yyyy">
                                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-calendar-blank"></i></div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Tanggal Diterima di Sekolah Ini</label>
                                <div class="relative">
                                    <input type="text" name="accepted_date" value="<?php echo e(old('accepted_date', $student->accepted_date)); ?>" class="datepicker w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900" placeholder="dd/mm/yyyy">
                                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-calendar-blank"></i></div>
                                </div>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Pindahan Dari Sekolah (Jika Pindahan)</label>
                                <input type="text" name="transfer_from_school" value="<?php echo e(old('transfer_from_school', $student->transfer_from_school)); ?>" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900">
                            </div>
                        </div>
                    </div>

                    
                    <div x-show="tab === 'orangtua'" class="space-y-6" style="display: none;">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-4">
                                <h4 class="font-bold text-blue-900 border-b border-blue-100 pb-2">Data Ayah Kandung</h4>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Nama Ayah</label>
                                    <input type="text" name="father_name" value="<?php echo e(old('father_name', $student->father_name)); ?>" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Tempat, Tanggal Lahir</label>
                                    <div class="flex gap-2">
                                        <input type="text" name="father_pob" value="<?php echo e(old('father_pob', $student->father_pob)); ?>" placeholder="Kota" class="w-1/2 rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900">
                                        
                                        <div class="relative w-1/2">
                                            <input type="text" name="father_birth_year" value="<?php echo e(old('father_birth_year', $student->father_birth_year)); ?>" class="datepicker w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900" placeholder="dd/mm/yyyy">
                                            <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-calendar-blank"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Pekerjaan</label>
                                    <input type="text" name="father_job" value="<?php echo e(old('father_job', $student->father_job)); ?>" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Penghasilan / Bulan</label>
                                    <input type="text" name="father_income" value="<?php echo e(old('father_income', $student->father_income)); ?>" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900">
                                </div>
                            </div>
                            <div class="space-y-4">
                                <h4 class="font-bold text-pink-600 border-b border-pink-100 pb-2">Data Ibu Kandung</h4>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Nama Ibu</label>
                                    <input type="text" name="mother_name" value="<?php echo e(old('mother_name', $student->mother_name)); ?>" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Tempat, Tanggal Lahir</label>
                                    <div class="flex gap-2">
                                        <input type="text" name="mother_pob" value="<?php echo e(old('mother_pob', $student->mother_pob)); ?>" placeholder="Kota" class="w-1/2 rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900">
                                        
                                        <div class="relative w-1/2">
                                            <input type="text" name="mother_birth_year" value="<?php echo e(old('mother_birth_year', $student->mother_birth_year)); ?>" class="datepicker w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900" placeholder="dd/mm/yyyy">
                                            <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-calendar-blank"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Pekerjaan</label>
                                    <input type="text" name="mother_job" value="<?php echo e(old('mother_job', $student->mother_job)); ?>" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Penghasilan / Bulan</label>
                                    <input type="text" name="mother_income" value="<?php echo e(old('mother_income', $student->mother_income)); ?>" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900">
                                </div>
                            </div>
                        </div>
                        
                        <div class="border-t border-slate-100 pt-6">
                            <h4 class="font-bold text-slate-700 border-b border-slate-100 pb-2 mb-4">Data Wali (Jika Ada)</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Nama Wali</label>
                                    <input type="text" name="guardian_name" value="<?php echo e(old('guardian_name', $student->guardian_name)); ?>" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Tempat, Tanggal Lahir</label>
                                    <div class="flex gap-2">
                                        <input type="text" name="guardian_pob" value="<?php echo e(old('guardian_pob', $student->guardian_pob)); ?>" placeholder="Kota" class="w-1/2 rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900">
                                        
                                        <div class="relative w-1/2">
                                            <input type="text" name="guardian_dob" value="<?php echo e(old('guardian_dob', $student->guardian_dob)); ?>" class="datepicker w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900" placeholder="dd/mm/yyyy">
                                            <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-calendar-blank"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Kewarganegaraan</label>
                                    <input type="text" name="guardian_citizenship" value="<?php echo e(old('guardian_citizenship', $student->guardian_citizenship)); ?>" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900" placeholder="WNI">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Pekerjaan</label>
                                    <input type="text" name="guardian_job" value="<?php echo e(old('guardian_job', $student->guardian_job)); ?>" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Penghasilan / Bulan</label>
                                    <input type="text" name="guardian_income" value="<?php echo e(old('guardian_income', $student->guardian_income)); ?>" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Alamat Wali</label>
                                    <input type="text" name="guardian_address" value="<?php echo e(old('guardian_address', $student->guardian_address)); ?>" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Hubungan Keluarga</label>
                                    <input type="text" name="guardian_relationship" value="<?php echo e(old('guardian_relationship', $student->guardian_relationship)); ?>" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900" placeholder="Paman / Kakek">
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div x-show="tab === 'tamat'" class="space-y-8" style="display: none;">
                        
                        
                        <div class="bg-emerald-50 p-6 rounded-2xl border border-emerald-100">
                            <h3 class="font-bold text-emerald-800 mb-4">I. Tamat Belajar (Lulus)</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-emerald-600 uppercase mb-1 ml-1">Tanggal Tamat</label>
                                    <div class="relative">
                                        <input type="text" name="graduated_date" value="<?php echo e(old('graduated_date', $student->graduated_date)); ?>" class="datepicker w-full rounded-xl border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500 bg-white" placeholder="dd/mm/yyyy">
                                        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-emerald-400"><i class="ph-bold ph-calendar-blank"></i></div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-emerald-600 uppercase mb-1 ml-1">No. Ijazah</label>
                                    <input type="text" name="graduated_diploma_no" value="<?php echo e(old('graduated_diploma_no', $student->graduated_diploma_no)); ?>" class="w-full rounded-xl border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-emerald-600 uppercase mb-1 ml-1">Melanjutkan Ke</label>
                                    <input type="text" name="continuing_to_school" value="<?php echo e(old('continuing_to_school', $student->continuing_to_school)); ?>" placeholder="Nama SMA/SMK" class="w-full rounded-xl border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-emerald-600 uppercase mb-1 ml-1">Alamat Sekolah Lanjutan</label>
                                    <input type="text" name="continuing_school_address" value="<?php echo e(old('continuing_school_address', $student->continuing_school_address)); ?>" class="w-full rounded-xl border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500">
                                </div>
                            </div>
                        </div>

                        
                        <div class="bg-amber-50 p-6 rounded-2xl border border-amber-100">
                            <h3 class="font-bold text-amber-800 mb-4">II. Pindah Sekolah (Mutasi Keluar)</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-amber-600 uppercase mb-1 ml-1">Tanggal Pindah</label>
                                    <div class="relative">
                                        <input type="text" name="leaving_date" value="<?php echo e(old('leaving_date', $student->leaving_date)); ?>" class="datepicker w-full rounded-xl border-amber-200 focus:border-amber-500 focus:ring-amber-500 bg-white" placeholder="dd/mm/yyyy">
                                        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-amber-400"><i class="ph-bold ph-calendar-blank"></i></div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-amber-600 uppercase mb-1 ml-1">Dari Kelas</label>
                                    <input type="text" name="leaving_class" value="<?php echo e(old('leaving_class', $student->leaving_class)); ?>" class="w-full rounded-xl border-amber-200 focus:border-amber-500 focus:ring-amber-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-amber-600 uppercase mb-1 ml-1">Pindah Ke Sekolah</label>
                                    <input type="text" name="leaving_to_school" value="<?php echo e(old('leaving_to_school', $student->leaving_to_school)); ?>" class="w-full rounded-xl border-amber-200 focus:border-amber-500 focus:ring-amber-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-amber-600 uppercase mb-1 ml-1">Alasan Pindah</label>
                                    <input type="text" name="leaving_reason" value="<?php echo e(old('leaving_reason', $student->leaving_reason)); ?>" class="w-full rounded-xl border-amber-200 focus:border-amber-500 focus:ring-amber-500">
                                </div>
                            </div>
                        </div>
                        
                         <div class="bg-rose-50 p-6 rounded-2xl border border-rose-100">
                            <h3 class="font-bold text-rose-800 mb-4">Putus Sekolah</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-rose-600 uppercase mb-1 ml-1">Tanggal Putus</label>
                                    <div class="relative">
                                        <input type="text" name="dropout_date" value="<?php echo e(old('dropout_date', $student->dropout_date)); ?>" class="datepicker w-full rounded-xl border-rose-200 focus:border-rose-500 focus:ring-rose-500 bg-white" placeholder="dd/mm/yyyy">
                                        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-rose-400"><i class="ph-bold ph-calendar-blank"></i></div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-rose-600 uppercase mb-1 ml-1">Alasan</label>
                                    <input type="text" name="dropout_reason" value="<?php echo e(old('dropout_reason', $student->dropout_reason)); ?>" class="w-full rounded-xl border-rose-200 focus:border-rose-500 focus:ring-rose-500">
                                </div>
                            </div>
                        </div>

                        <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200">
                            <h3 class="font-bold text-slate-800 mb-4">Lain-Lain</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1 ml-1">Beasiswa (Tahun / Kelas / Sumber)</label>
                                    <textarea name="scholarship_info" rows="2" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:border-blue-900 focus:ring-blue-900"><?php echo e(old('scholarship_info', $student->scholarship_info)); ?></textarea>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1 ml-1">Catatan Penting Selama Siswa Belajar</label>
                                    <textarea name="general_notes" rows="3" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:border-blue-900 focus:ring-blue-900"><?php echo e(old('general_notes', $student->general_notes)); ?></textarea>
                                </div>
                            </div>
                        </div>

                    </div>


                    
                    <div class="mt-8 pt-6 border-t border-slate-100 flex justify-between items-center">
                        <p class="text-xs text-slate-400 italic">* Pastikan data sudah benar sebelum disimpan.</p>
                        <button type="submit" class="px-8 py-3 bg-blue-900 text-white font-bold rounded-xl hover:bg-blue-800 shadow-lg shadow-blue-900/30 transition-all transform active:scale-95 flex items-center gap-2">
                            <i class="ph-bold ph-floppy-disk"></i>
                            Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. INISIALISASI DATEPICKER (Flatpickr)
            flatpickr(".datepicker", {
                altInput: true,
                altFormat: "d/m/Y", // Tampilan di Form: 31/12/2025
                dateFormat: "Y-m-d", // Data dikirim ke Server: 2025-12-31
                locale: "id", // Bahasa Indonesia
                disableMobile: "true" // Memaksa pakai tema Flatpickr di HP juga (supaya format tetap konsisten)
            });

            // 2. PREVIEW FOTO
            const photoInput = document.getElementById('photo-input');
            if(photoInput) {
                photoInput.addEventListener('change', function(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            document.getElementById('photo-preview').src = e.target.result;
                        }
                        reader.readAsDataURL(file);
                    }
                });
            }

            // 3. KONFIRMASI KEMBALI JIKA BELUM SIMPAN
            // Cek jika form berubah (opsional, sederhana saja dulu: konfirmasi langsung saat klik kembali)
            const btnBack = document.querySelector('.btn-back-confirm');
            if(btnBack) {
                btnBack.addEventListener('click', function(e) {
                    // Hanya contoh proteksi sederhana
                    // e.preventDefault();
                    // Swal.fire(...) 
                    // (Aktifkan jika ingin memproteksi tombol kembali)
                });
            }

            // 4. LOADING SAAT SIMPAN
            const form = document.getElementById('edit-student-form');
            if(form) {
                form.addEventListener('submit', function() {
                    Swal.fire({
                        title: 'Menyimpan Data...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                        customClass: {
                            popup: 'rounded-[2rem]'
                        }
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
<?php endif; ?><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/students/edit.blade.php ENDPATH**/ ?>