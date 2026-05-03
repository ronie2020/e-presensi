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

    <div class="py-8 sm:py-10 font-sans text-elevate-text bg-slate-50 min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <a href="<?php echo e(route('sppd.index')); ?>" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-elevate-primary mb-6 transition-colors group">
                <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali ke Daftar
            </a>

            
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative"
                 x-data="{ 
                    mode: 'spt', 
                    // Mengambil nilai 'old' jika validasi gagal
                    selectedSptId: <?php echo e(Js::from(old('spt_id', ''))); ?>,
                    pegawaiId: <?php echo e(Js::from(old('pegawai_id', ''))); ?>,
                    maksud: <?php echo e(Js::from(old('maksud', ''))); ?>,
                    tujuan: <?php echo e(Js::from(old('tujuan', ''))); ?>,
                    tanggal_berangkat: <?php echo e(Js::from(old('tgl_berangkat', ''))); ?>,
                    tanggal_kembali: <?php echo e(Js::from(old('tgl_kembali', ''))); ?>,
                    followers: <?php echo e(Js::from(old('followers', []))); ?>, 

                    // Data Dinamis
                    sptList: <?php echo e(Js::from($spt_json ?? [])); ?>, 
                    availableUsers: [],
                    allUsers: <?php echo e(Js::from($users->map(fn($u) => ['id'=>$u->id, 'name'=>$u->name, 'nip'=>$u->nip]))); ?>,

                    init() {
                        // Menentukan mode saat pertama kali load berdasarkan data 'old'
                        if(this.selectedSptId) {
                            this.mode = 'spt';
                            const data = this.sptList.find(item => item.id == this.selectedSptId);
                            if(data) this.availableUsers = data.pegawai;
                        } else if (this.pegawaiId) {
                            this.mode = 'manual';
                        } else {
                            if(this.sptList.length > 0) this.mode = 'spt';
                        }
                    },

                    selectSpt() {
                        const data = this.sptList.find(item => item.id == this.selectedSptId);
                        if (data) {
                            this.maksud = data.perihal;
                            this.tujuan = data.tujuan;
                            this.tanggal_berangkat = data.tgl_mulai;
                            this.tanggal_kembali = data.tgl_selesai;
                            this.availableUsers = data.pegawai;
                            
                            // Otomatis pilih pegawai jika hanya ada satu di SPT tersebut
                            if(this.availableUsers.length === 1) {
                                this.pegawaiId = this.availableUsers[0].id;
                            } else {
                                this.pegawaiId = '';
                            }
                        } else {
                            this.resetForm();
                        }
                    },

                    resetForm() {
                        this.maksud = ''; 
                        this.tujuan = ''; 
                        this.tanggal_berangkat = ''; 
                        this.tanggal_kembali = ''; 
                        this.selectedSptId = ''; 
                        this.pegawaiId = '';
                        this.availableUsers = []; 
                    },

                    addFollower() {
                        this.followers.push({ nama: '', nip: '', keterangan: '' });
                    },
                    removeFollower(index) {
                        this.followers.splice(index, 1);
                    },
                    fillFollowerName(index, event) {
                        const selectedId = event.target.value;
                        const user = this.allUsers.find(u => u.id == selectedId);
                        if (user) {
                            this.followers[index].nama = user.name;
                            this.followers[index].nip = user.nip || '-'; 
                        }
                    }
                 }">
                
                
                <div class="bg-gradient-to-r from-elevate-dark to-elevate-primary p-8 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-elevate-accent/20 rounded-full blur-3xl pointer-events-none -translate-y-1/2 translate-x-1/2"></div>
                    <div class="absolute -right-6 -top-6 text-white/5 text-9xl pointer-events-none">
                        <i class="ph-fill ph-car-profile"></i>
                    </div>
                    <h2 class="text-2xl font-black relative z-10 flex items-center gap-3">
                        <i class="ph-duotone ph-file-text text-elevate-accent"></i> Formulir Perjalanan Dinas
                    </h2>
                    <p class="text-elevate-accent text-sm font-medium relative z-10 mt-1">Lengkapi data SPPD berdasarkan Surat Perintah Tugas (SPT).</p>
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

                    
                    <form id="form-create-sppd" action="<?php echo e(route('sppd.store')); ?>" method="POST" class="space-y-8">
                        <?php echo csrf_field(); ?>

                        <!-- SECTION 1: DASAR & MODE INPUT -->
                        <div class="p-6 bg-elevate-accent/5 rounded-[2rem] border border-elevate-accent/20 relative group hover:border-elevate-accent/40 transition-colors">
                            <h3 class="text-sm font-black text-elevate-dark uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="bg-elevate-accent/20 text-elevate-primary rounded-full w-7 h-7 flex items-center justify-center text-xs">1</span>
                                Dasar Pelaksanaan
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pl-9">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Metode Input Data</label>
                                    <div class="flex p-1 bg-white rounded-2xl border border-slate-200 shadow-sm">
                                        <button type="button" @click="mode = 'spt'; resetForm()" 
                                            :class="mode === 'spt' ? 'bg-elevate-dark text-white shadow-md' : 'text-slate-500 hover:text-elevate-dark'" 
                                            class="px-4 py-2.5 text-xs font-black rounded-xl flex-1 transition-all uppercase tracking-wider">
                                            Berdasarkan SPT
                                        </button>
                                        <button type="button" @click="mode = 'manual'; resetForm()" 
                                            :class="mode === 'manual' ? 'bg-elevate-dark text-white shadow-md' : 'text-slate-500 hover:text-elevate-dark'" 
                                            class="px-4 py-2.5 text-xs font-black rounded-xl flex-1 transition-all uppercase tracking-wider">
                                            Input Manual
                                        </button>
                                    </div>
                                </div>
                                <div x-show="mode === 'spt'" x-transition class="animate-in fade-in slide-in-from-left-2">
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1 text-elevate-primary">Pilih Nomor Surat Tugas (SPT)</label>
                                    <div class="relative">
                                        <select name="spt_id" x-model="selectedSptId" @change="selectSpt()" class="w-full pl-4 pr-10 py-3 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary focus:ring-elevate-primary text-sm font-bold text-elevate-dark appearance-none transition-all cursor-pointer">
                                            <option value="">-- Cari Nomor SPT --</option>
                                            <template x-for="spt in sptList" :key="spt.id">
                                                <option :value="spt.id" x-text="spt.nomor + ' (' + spt.tujuan + ')'"></option>
                                            </template>
                                        </select>
                                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 2: DETAIL SPPD -->
                        <div class="p-6 bg-slate-50/50 rounded-[2rem] border border-slate-100 relative group hover:border-slate-200 transition-colors">
                            <h3 class="text-sm font-black text-elevate-dark uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="bg-elevate-accent/20 text-elevate-primary rounded-full w-7 h-7 flex items-center justify-center text-xs">2</span>
                                Detail Perjalanan & Pelaksana
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pl-9">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Nomor SPPD (Otomatis)</label>
                                    <input type="text" name="nomor_sppd" value="<?php echo e(old('nomor_sppd', $nomor_otomatis ?? '')); ?>" class="w-full px-4 rounded-2xl border-slate-200 bg-slate-100 text-slate-500 font-mono font-bold text-sm py-3 shadow-inner" readonly>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Pegawai Pelaksana <span class="text-rose-500">*</span></label>
                                    <div class="relative">
                                        <select name="pegawai_id" x-model="pegawaiId" required class="w-full pl-4 pr-10 py-3 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary focus:ring-elevate-primary text-sm font-bold text-elevate-dark appearance-none transition-all cursor-pointer">
                                            <option value="">-- Pilih Pegawai --</option>
                                            <template x-for="user in (mode === 'manual' ? allUsers : availableUsers)" :key="user.id">
                                                <option :value="user.id" x-text="user.name + (mode === 'manual' && user.nip && user.nip !== '-' ? ' (' + user.nip + ')' : '')"></option>
                                            </template>
                                        </select>
                                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-2 ml-1 italic" x-show="mode === 'spt' && availableUsers.length > 0">
                                        *Hanya menampilkan pegawai yang terdaftar di SPT terpilih.
                                    </p>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Maksud Perjalanan Dinas</label>
                                    <textarea name="maksud" rows="2" x-model="maksud" required class="w-full px-4 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary focus:ring-elevate-primary text-sm py-3 font-medium text-elevate-dark transition-all" placeholder="Tujuan atau perihal keberangkatan..."></textarea>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Tempat Tujuan</label>
                                    <input type="text" name="tujuan" x-model="tujuan" required class="w-full px-4 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary focus:ring-elevate-primary text-sm py-3 font-bold text-elevate-dark transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Alat Angkutan</label>
                                    <input type="text" name="transportasi" value="<?php echo e(old('transportasi')); ?>" placeholder="Contoh: Kendaraan Dinas / Mobil Pribadi" class="w-full px-4 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary focus:ring-elevate-primary text-sm py-3 font-bold text-elevate-dark transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Tgl Berangkat</label>
                                    <input type="date" name="tgl_berangkat" x-model="tanggal_berangkat" required class="w-full px-4 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary focus:ring-elevate-primary text-sm py-3 font-bold text-elevate-dark transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Tgl Kembali</label>
                                    <input type="date" name="tgl_kembali" x-model="tanggal_kembali" required class="w-full px-4 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary focus:ring-elevate-primary text-sm py-3 font-bold text-elevate-dark transition-all">
                                </div>
                            </div>
                        </div>
                        
                        <!-- SECTION 3: PENGIKUT -->
                        <div class="p-6 bg-slate-50/50 rounded-[2rem] border border-slate-100 relative group hover:border-slate-200 transition-colors">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-sm font-black text-elevate-dark uppercase tracking-wider flex items-center gap-2">
                                    <span class="bg-elevate-accent/20 text-elevate-primary rounded-full w-7 h-7 flex items-center justify-center text-xs">3</span>
                                    Pengikut / Peserta Tambahan
                                </h3>
                                <button type="button" @click="addFollower()" class="px-4 py-2 bg-emerald-50 text-emerald-600 border border-emerald-200 rounded-xl text-xs font-bold hover:bg-emerald-100 transition-colors flex items-center gap-2 shadow-sm">
                                    <i class="ph-bold ph-plus"></i> Tambah Pengikut
                                </button>
                            </div>

                            <div class="space-y-3 pl-9">
                                <template x-for="(follower, index) in followers" :key="index">
                                    <div class="flex flex-col md:flex-row gap-4 items-end bg-white p-5 rounded-[1.5rem] border border-slate-200 relative group/follower transition-all hover:border-elevate-accent/50 shadow-sm animate-in zoom-in-95 duration-200">
                                        <div class="flex-1 w-full">
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1 tracking-wider">Nama (Pilih/Ketik)</label>
                                            <select class="block w-full text-xs border-slate-200 rounded-xl mb-2 focus:border-elevate-primary focus:ring-elevate-primary bg-slate-50 cursor-pointer" @change="fillFollowerName(index, $event)">
                                                <option value="">-- Pilih Cepat dari Database --</option>
                                                <template x-for="u in allUsers" :key="u.id">
                                                    <option :value="u.id" x-text="u.name"></option>
                                                </template>
                                            </select>
                                            <input type="text" :name="'followers['+index+'][nama]'" x-model="follower.nama" placeholder="Nama Lengkap" class="block w-full text-sm font-bold border-slate-200 rounded-xl focus:border-elevate-primary focus:ring-elevate-primary text-elevate-dark" required>
                                        </div>
                                        <div class="w-full md:w-1/4">
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1 tracking-wider">NIP / NIK</label>
                                            <input type="text" :name="'followers['+index+'][nip]'" x-model="follower.nip" class="block w-full text-sm font-mono border-slate-200 rounded-xl focus:border-elevate-primary focus:ring-elevate-primary text-slate-600" placeholder="-">
                                        </div>
                                        <div class="w-full md:w-1/3">
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1 tracking-wider">Keterangan</label>
                                            <input type="text" :name="'followers['+index+'][keterangan]'" x-model="follower.keterangan" placeholder="Contoh: Pendamping" class="block w-full text-sm border-slate-200 rounded-xl focus:border-elevate-primary focus:ring-elevate-primary text-slate-600">
                                        </div>
                                        <div class="absolute top-2 right-2 md:static">
                                            <button type="button" @click="removeFollower(index)" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-50 border border-slate-200 text-slate-400 hover:text-rose-500 hover:border-rose-200 hover:bg-rose-50 hover:shadow-sm transition-all shadow-sm">
                                                <i class="ph-bold ph-trash text-lg"></i>
                                            </button>
                                        </div>
                                    </div>
                                </template>
                                
                                <div x-show="followers.length === 0" class="text-center py-10 border-2 border-dashed border-slate-100 rounded-[2rem] text-slate-300 italic text-sm bg-slate-50/30">
                                    <i class="ph-bold ph-users-three text-3xl block mb-2 opacity-50"></i>
                                    Belum ada pengikut tambahan yang ditambahkan.
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 4: ANGGARAN -->
                        <div class="p-6 bg-slate-50/50 rounded-[2rem] border border-slate-100 relative group hover:border-slate-200 transition-colors">
                            <h3 class="text-sm font-black text-elevate-dark uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="bg-elevate-accent/20 text-elevate-primary rounded-full w-7 h-7 flex items-center justify-center text-xs">4</span>
                                Pembebanan Anggaran
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pl-9">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Instansi Penanggung Biaya</label>
                                    <input type="text" name="instansi_biaya" value="<?php echo e(old('instansi_biaya', 'SMP Negeri 3 Lakbok')); ?>" class="w-full px-4 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary focus:ring-elevate-primary text-sm py-3 font-bold text-elevate-dark transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Mata Anggaran / Kode Rekening</label>
                                    <input type="text" name="kode_rekening" value="<?php echo e(old('kode_rekening')); ?>" placeholder="Misal: 5.2.2.15.01 (Dana BOS)" class="w-full px-4 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary focus:ring-elevate-primary text-sm py-3 font-mono font-bold text-elevate-dark transition-all">
                                </div>
                            </div>
                        </div>

                        
                        <div class="flex items-center justify-end gap-4 pt-8 mt-4 border-t border-slate-100">
                            <a href="<?php echo e(route('sppd.index')); ?>" class="px-8 py-4 rounded-2xl text-slate-500 font-bold text-sm hover:bg-slate-100 hover:text-slate-700 transition-colors">Batalkan</a>
                            
                            <button type="button" @click="confirmSubmit($event)" class="px-10 py-4 bg-elevate-dark text-white font-black text-xs uppercase tracking-widest rounded-2xl hover:bg-elevate-primary shadow-xl shadow-elevate-dark/20 transition-all transform active:scale-95 flex items-center gap-3 group">
                                <i class="ph-bold ph-floppy-disk text-lg group-hover:scale-110 transition-transform"></i> 
                                <span>Simpan & Terbitkan</span>
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
            
            // PERBAIKAN: Mengambil form berdasarkan ID, bukan elemen <form> pertama yang ditemukan
            const form = document.getElementById('form-create-sppd');
            
            const pegawai = document.getElementsByName('pegawai_id')[0].value;
            if(!pegawai) {
                 Swal.fire({
                    icon: 'warning',
                    title: 'Data Belum Lengkap',
                    text: 'Mohon pilih pegawai pelaksana perjalanan dinas.',
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
                title: 'Simpan Data SPPD?', 
                text: 'Pastikan rincian tugas dan anggaran sudah sesuai.', 
                icon: 'question',
                showCancelButton: true, 
                confirmButtonText: 'Ya, Simpan!', 
                cancelButtonText: 'Periksa Lagi',
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
                        title: 'Menyimpan Data...', 
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/sppd/create.blade.php ENDPATH**/ ?>