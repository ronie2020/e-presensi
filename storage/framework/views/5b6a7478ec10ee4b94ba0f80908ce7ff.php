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
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <div class="py-8 sm:py-10 font-sans text-elevate-text bg-slate-50 min-h-screen" 
         x-data="{ 
            incomingLetters: <?php echo e($incoming_letters->toJson()); ?>,
            selectedLetterId: '<?php echo e($selected_letter_id ?? ''); ?>',
            perihal: '<?php echo e(old('untuk')); ?>',
            tempat: '<?php echo e(old('tempat')); ?>',
            followers: <?php echo e(old('pengikut') ? Js::from(old('pengikut')) : '[]'); ?>,
            users: <?php echo e(Js::from($users->map(fn($u) => ['id' => $u->id, 'name' => $u->name, 'nip' => $u->nip]))); ?>,

            init() {
                if(this.selectedLetterId) {
                    this.updateFromLetter();
                }
            },

            updateFromLetter() {
                const letter = this.incomingLetters.find(l => l.id == this.selectedLetterId);
                if(letter) {
                    this.perihal = letter.perihal;
                    this.tempat = letter.asal_surat;
                }
            },

            addFollower() {
                this.followers.push({ nama: '', nip: '', keterangan: '' });
            },

            removeFollower(index) {
                this.followers.splice(index, 1);
            },

            fillFollowerData(index, event) {
                const selectedId = event.target.value;
                const user = this.users.find(u => u.id == selectedId);
                if (user) {
                    this.followers[index].nama = user.name;
                    this.followers[index].nip = user.nip || '-';
                }
            }
         }">
        
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <a href="<?php echo e(route('letters.spt.index')); ?>" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-elevate-primary mb-6 transition-colors group">
                <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali ke Daftar
            </a>

            
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative">
                
                
                <div class="bg-gradient-to-r from-elevate-dark to-elevate-primary p-8 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-elevate-accent/20 rounded-full blur-3xl pointer-events-none -translate-y-1/2 translate-x-1/2"></div>
                    <div class="absolute -right-6 -top-6 text-white/5 text-9xl pointer-events-none">
                        <i class="ph-fill ph-paper-plane-tilt"></i>
                    </div>
                    <h2 class="text-2xl font-black relative z-10 flex items-center gap-3">
                        <i class="ph-duotone ph-paper-plane-tilt text-elevate-accent"></i> Buat Surat Perintah Tugas
                    </h2>
                    <p class="text-elevate-accent text-sm font-medium relative z-10 mt-1">Lengkapi rincian penugasan dinas pegawai.</p>
                </div>

                
                <div class="p-8">
                    <?php if($errors->any()): ?>
                        <div class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl text-sm flex items-start gap-3 shadow-sm">
                            <i class="ph-fill ph-warning-circle text-xl shrink-0 mt-0.5"></i>
                            <div>
                                <strong class="font-bold block mb-1">Periksa kembali inputan Anda!</strong>
                                <ul class="list-disc list-inside font-medium">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>

                    <form id="form-create-spt" action="<?php echo e(route('letters.spt.store')); ?>" method="POST" class="space-y-8">
                        <?php echo csrf_field(); ?>

                        <!-- SECTION 1: DASAR & IDENTITAS -->
                        <div class="p-6 bg-elevate-accent/5 rounded-[2rem] border border-elevate-accent/20 relative group hover:border-elevate-accent/40 transition-colors">
                            <h3 class="text-sm font-black text-elevate-dark uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="bg-elevate-accent/20 text-elevate-primary rounded-full w-7 h-7 flex items-center justify-center text-xs">1</span>
                                Identitas & Dasar SPT
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pl-9">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Nomor SPT (Otomatis)</label>
                                    <input type="text" name="nomor_spt" value="<?php echo e(old('nomor_spt', $nomor_otomatis)); ?>" required class="w-full px-4 rounded-2xl border-slate-200 bg-slate-50 font-mono font-bold text-elevate-primary shadow-inner text-sm py-3 transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Berdasarkan Surat Masuk (Opsional)</label>
                                    <div class="relative">
                                        <select name="letter_incoming_id" x-model="selectedLetterId" @change="updateFromLetter()" class="w-full pl-4 pr-10 py-3 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary focus:ring-elevate-primary text-sm font-bold text-elevate-dark appearance-none transition-all cursor-pointer">
                                            <option value="">-- Pilih Dasar Surat --</option>
                                            <?php $__currentLoopData = $incoming_letters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $letter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($letter->id); ?>"><?php echo e($letter->nomor_surat); ?> - <?php echo e($letter->perihal); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 2: PEGAWAI UTAMA -->
                        <div class="p-6 bg-slate-50/50 rounded-[2rem] border border-slate-100 relative group hover:border-slate-200 transition-colors">
                            <h3 class="text-sm font-black text-elevate-dark uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="bg-elevate-accent/20 text-elevate-primary rounded-full w-7 h-7 flex items-center justify-center text-xs">2</span>
                                Pegawai yang Ditugaskan <span class="text-rose-500">*</span>
                            </h3>
                            <div class="pl-9">
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 max-h-60 overflow-y-auto p-4 bg-white rounded-3xl border border-slate-200 custom-scrollbar shadow-inner">
                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <label class="flex items-center gap-3 p-3 rounded-xl border border-transparent hover:border-elevate-accent/30 hover:bg-elevate-accent/5 transition-all cursor-pointer group/item">
                                            <input type="checkbox" name="pegawai_ids[]" value="<?php echo e($user->id); ?>" 
                                                class="rounded-md border-slate-300 text-elevate-primary focus:ring-elevate-primary w-5 h-5 transition-all">
                                            <div class="flex flex-col">
                                                <span class="text-sm font-bold text-elevate-dark group-hover/item:text-elevate-primary"><?php echo e($user->name); ?></span>
                                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">NIP. <?php echo e($user->nip ?? '-'); ?></span>
                                            </div>
                                        </label>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                <p class="text-[10px] text-slate-400 mt-3 ml-1 italic font-medium">
                                    <i class="ph-fill ph-info text-elevate-primary"></i> Pilih satu atau lebih personil inti penugasan.
                                </p>
                            </div>
                        </div>

                        <!-- SECTION 3: DETAIL TUGAS -->
                        <div class="p-6 bg-slate-50/50 rounded-[2rem] border border-slate-100 relative group hover:border-slate-200 transition-colors">
                            <h3 class="text-sm font-black text-elevate-dark uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="bg-elevate-accent/20 text-elevate-primary rounded-full w-7 h-7 flex items-center justify-center text-xs">3</span>
                                Lokasi & Maksud Kegiatan
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pl-9">
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Maksud Perjalanan / Perihal <span class="text-rose-500">*</span></label>
                                    <textarea name="untuk" x-model="perihal" rows="2" required class="w-full px-4 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary focus:ring-elevate-primary text-sm py-3 font-medium text-elevate-dark transition-all" placeholder="Contoh: Menghadiri undangan rapat koordinasi..."></textarea>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Tempat Tujuan <span class="text-rose-500">*</span></label>
                                    <input type="text" name="tempat" x-model="tempat" required class="w-full px-4 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary focus:ring-elevate-primary text-sm py-3 font-bold text-elevate-dark transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Tgl Berangkat <span class="text-rose-500">*</span></label>
                                    <input type="date" name="tgl_berangkat" value="<?php echo e(old('tgl_berangkat')); ?>" required class="w-full px-4 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary focus:ring-elevate-primary text-sm py-3 font-bold text-elevate-dark transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Tgl Kembali <span class="text-rose-500">*</span></label>
                                    <input type="date" name="tgl_kembali" value="<?php echo e(old('tgl_kembali')); ?>" required class="w-full px-4 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary focus:ring-elevate-primary text-sm py-3 font-bold text-elevate-dark transition-all">
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 4: PENGIKUT -->
                        <div class="p-6 bg-slate-50/50 rounded-[2rem] border border-slate-100 relative group hover:border-slate-200 transition-colors">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-sm font-black text-elevate-dark uppercase tracking-wider flex items-center gap-2">
                                    <span class="bg-elevate-accent/20 text-elevate-primary rounded-full w-7 h-7 flex items-center justify-center text-xs">4</span>
                                    Peserta Tambahan (Pengikut)
                                </h3>
                                <button type="button" @click="addFollower()" class="px-4 py-2 bg-elevate-soft text-elevate-primary border border-elevate-accent/30 rounded-xl text-xs font-bold hover:bg-elevate-accent/20 transition-colors flex items-center gap-2 shadow-sm">
                                    <i class="ph-bold ph-plus"></i> Tambah Pengikut
                                </button>
                            </div>

                            <div class="space-y-3 pl-9">
                                <template x-for="(follower, index) in followers" :key="index">
                                    <div class="flex flex-col md:flex-row gap-4 items-end bg-white p-5 rounded-2xl border border-slate-200 relative group/follower transition-all hover:border-elevate-accent/50 shadow-sm">
                                        <div class="flex-1 w-full">
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Pilih Pegawai (Cepat)</label>
                                            <select class="block w-full text-xs border-slate-200 rounded-xl mb-2 focus:border-elevate-primary focus:ring-elevate-primary bg-slate-50 transition-all" @change="fillFollowerData(index, $event)">
                                                <option value="">-- Pilih untuk Auto-fill --</option>
                                                <template x-for="u in users" :key="u.id">
                                                    <option :value="u.id" x-text="u.name"></option>
                                                </template>
                                            </select>
                                            <input type="text" :name="'pengikut['+index+'][nama]'" x-model="follower.nama" placeholder="Nama Lengkap" class="block w-full text-sm font-bold border-slate-200 rounded-xl focus:border-elevate-primary focus:ring-elevate-primary text-elevate-dark" required>
                                        </div>
                                        <div class="w-full md:w-1/4">
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">NIP / NIK</label>
                                            <input type="text" :name="'pengikut['+index+'][nip]'" x-model="follower.nip" class="block w-full text-sm font-mono border-slate-200 rounded-xl focus:border-elevate-primary focus:ring-elevate-primary text-slate-600" placeholder="-">
                                        </div>
                                        <div class="w-full md:w-1/3">
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Keterangan</label>
                                            <input type="text" :name="'pengikut['+index+'][keterangan]'" x-model="follower.keterangan" placeholder="Contoh: Anggota" class="block w-full text-sm border-slate-200 rounded-xl focus:border-elevate-primary focus:ring-elevate-primary text-slate-600">
                                        </div>
                                        <div class="absolute top-2 right-2 md:static">
                                            <button type="button" @click="removeFollower(index)" class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-50 border border-slate-200 text-slate-400 hover:text-rose-500 hover:border-rose-200 hover:bg-rose-50 transition-all shadow-sm">
                                                <i class="ph-bold ph-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </template>
                                
                                <div x-show="followers.length === 0" class="text-center py-10 border-2 border-dashed border-slate-100 rounded-2xl text-slate-300 italic text-sm bg-slate-50/30">
                                    <i class="ph-bold ph-users-three text-2xl block mb-2 opacity-50"></i>
                                    Tidak ada peserta tambahan.
                                </div>
                            </div>
                        </div>

                        
                        <div class="pt-8 mt-4 border-t border-slate-100 flex items-center justify-end gap-4">
                            <a href="<?php echo e(route('letters.spt.index')); ?>" class="px-6 py-3.5 rounded-2xl text-slate-500 font-bold text-sm hover:bg-slate-100 transition-colors">
                                Batalkan
                            </a>
                            <button type="button" onclick="confirmSubmit(event)" class="px-10 py-3.5 bg-elevate-dark text-white font-bold rounded-2xl hover:bg-elevate-primary shadow-lg shadow-elevate-dark/20 transition-all transform active:scale-95 flex items-center gap-2 group">
                                <i class="ph-bold ph-check-circle text-lg group-hover:scale-110 transition-transform"></i> 
                                <span>Terbitkan SPT</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmSubmit(e) {
            e.preventDefault();
            const form = document.getElementById('form-create-spt');
            
            // Validasi Checkbox Pegawai Utama
            const checkedCount = document.querySelectorAll('input[name="pegawai_ids[]"]:checked').length;
            if (checkedCount === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pegawai Kosong',
                    text: 'Silakan pilih minimal satu pegawai yang ditugaskan.',
                    confirmButtonColor: '#032b5b',
                    customClass: { popup: 'rounded-[2rem]' }
                });
                return;
            }

            if (!form.checkValidity()) { 
                form.reportValidity(); 
                return; 
            }

            Swal.fire({
                title: 'Simpan & Terbitkan?', 
                text: 'SPT akan diterbitkan dan draft SPPD otomatis akan dibuat.', 
                icon: 'question',
                showCancelButton: true, 
                confirmButtonText: 'Ya, Terbitkan!', 
                cancelButtonText: 'Cek Lagi',
                reverseButtons: true, 
                customClass: {
                    popup: 'rounded-[2.5rem] font-sans border-0 shadow-2xl',
                    confirmButton: 'bg-elevate-dark text-white px-8 py-3.5 rounded-2xl font-bold hover:bg-elevate-primary mx-2 shadow-lg shadow-elevate-dark/20',
                    cancelButton: 'bg-slate-100 text-slate-600 px-8 py-3.5 rounded-2xl font-bold hover:bg-slate-200 mx-2'
                }, 
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ 
                        title: 'Memproses Data...', 
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        showConfirmButton: false, 
                        didOpen: () => Swal.showLoading() 
                    });
                    form.submit();
                }
            });
        }
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/letters/spt/create.blade.php ENDPATH**/ ?>