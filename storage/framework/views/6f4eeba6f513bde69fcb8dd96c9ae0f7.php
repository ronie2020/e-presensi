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
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-[#2c3f61] leading-tight">
            <?php echo e(__('Monitoring Live')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('monitoringData', () => ({
                students: <?php echo json_encode($monitoringData, 15, 512) ?>,
                stats: <?php echo json_encode($stats, 15, 512) ?>,
                search: '', 
                timer: 10,
                isUpdating: false,
                
                // PERBAIKAN: Tambahkan variabel examType ke Alpine state
                examType: '<?php echo e($exam->exam_type ?? "cbt"); ?>',
                
                currentToken: '<?php echo e($exam->token); ?>',
                autoRotate: false,
                intervalMinutes: 15,
                tokenIsLoading: false,
                tokenTimeLeft: 0,
                tokenProgress: 100,
                tokenTimer: null,

                showPhotoModal: false,
                activeStudentName: '',
                studentPhotos: [],
                loadingPhotos: false,
                
                urls: {
                    monitoring: '<?php echo e(route('cbt.monitoring_data', $exam->id)); ?>',
                    autoToken: '<?php echo e(route('cbt.auto_token', $exam->id)); ?>',
                    resetBase: '<?php echo e(route('cbt.reset', ['exam' => $exam->id, 'student' => 'ID_PLACEHOLDER'])); ?>',
                    photosBase: '<?php echo e(route("cbt.monitoring.photos", ["exam" => $exam->id, "student" => "ID_PLACEHOLDER"])); ?>'
                },

                init() {
                    setInterval(() => {
                        if (this.timer > 0) {
                            this.timer--;
                        } else {
                            this.fetchStudentData();
                            this.timer = 10;
                        }
                    }, 1000);

                    this.loadTokenState();
                    this.$watch('autoRotate', val => { 
                        if(val) this.startTokenTimer(); else this.stopTokenTimer();
                        this.saveTokenState(); 
                    });
                    this.$watch('intervalMinutes', () => { 
                        if(this.autoRotate) this.startTokenTimer();
                        this.saveTokenState(); 
                    });
                },

                async fetchStudentData() {
                    this.isUpdating = true;
                    try {
                        const noCacheUrl = this.urls.monitoring + '?t=' + new Date().getTime();
                        const response = await fetch(noCacheUrl);
                        if (response.ok) {
                            this.students = await response.json();
                            this.updateLocalStats();
                        }
                    } catch (error) {
                        console.error('Gagal update data:', error);
                    } finally {
                        this.isUpdating = false;
                    }
                },

                updateLocalStats() {
                    this.stats.working = this.students.filter(s => s.status === 'Sedang Mengerjakan').length;
                    this.stats.finished = this.students.filter(s => s.status === 'Selesai').length;
                },

                getResetUrl(studentId) {
                    return this.urls.resetBase.replace('ID_PLACEHOLDER', studentId);
                },

                get filteredStudents() {
                    if (this.search === '') return this.students;
                    return this.students.filter(s => s.name.toLowerCase().includes(this.search.toLowerCase()));
                },

                loadTokenState() {
                    const saved = JSON.parse(localStorage.getItem('token_monitor_<?php echo e($exam->id); ?>'));
                    if (saved) {
                        this.autoRotate = saved.autoRotate;
                        this.intervalMinutes = saved.intervalMinutes;
                        const now = Math.floor(Date.now() / 1000);
                        if (this.autoRotate && saved.targetTime > now) {
                            this.tokenTimeLeft = saved.targetTime - now;
                            this.startTokenTimer(false);
                        } else if (this.autoRotate) {
                            this.startTokenTimer();
                        }
                    }
                },

                saveTokenState() {
                    const now = Math.floor(Date.now() / 1000);
                    localStorage.setItem('token_monitor_<?php echo e($exam->id); ?>', JSON.stringify({
                        autoRotate: this.autoRotate, 
                        intervalMinutes: this.intervalMinutes, 
                        targetTime: now + this.tokenTimeLeft
                    }));
                },

                startTokenTimer(reset = true) {
                    this.stopTokenTimer();
                    let total = this.intervalMinutes * 60;
                    if (reset) this.tokenTimeLeft = total;
                    
                    this.tokenTimer = setInterval(() => {
                        this.tokenTimeLeft--;
                        this.tokenProgress = (this.tokenTimeLeft / total) * 100;
                        this.saveTokenState();
                        if (this.tokenTimeLeft <= 0) { 
                            this.rotateTokenNow(); 
                            this.tokenTimeLeft = total; 
                        }
                    }, 1000);
                },

                stopTokenTimer() { 
                    clearInterval(this.tokenTimer); 
                    this.tokenProgress = 100; 
                    localStorage.removeItem('token_monitor_<?php echo e($exam->id); ?>'); 
                },

                rotateTokenNow() {
                    this.tokenIsLoading = true;
                    fetch(this.urls.autoToken, {
                        method: 'POST', 
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' }
                    })
                    .then(r => r.json())
                    .then(d => { 
                        if (d.status === 'success') {
                            this.currentToken = d.token;
                            Swal.fire({
                                toast: true, position: 'top-end', icon: 'success', 
                                title: 'Token Diperbarui!', text: d.token,
                                showConfirmButton: false, timer: 3000,
                                customClass: { popup: 'rounded-2xl shadow-xl border border-slate-100' }
                            });
                        }
                    })
                    .finally(() => this.tokenIsLoading = false);
                },

                openPhotoModal(studentId, studentName) {
                    this.activeStudentName = studentName;
                    this.showPhotoModal = true;
                    this.loadingPhotos = true;
                    this.studentPhotos = [];

                    const url = this.urls.photosBase.replace('ID_PLACEHOLDER', studentId);

                    fetch(url)
                        .then(res => {
                            if (!res.ok) throw new Error('Gagal memuat foto');
                            return res.json();
                        })
                        .then(data => { this.studentPhotos = data; })
                        .catch(err => { console.error(err); this.studentPhotos = []; })
                        .finally(() => { this.loadingPhotos = false; });
                }
            }));
        });

        function confirmResetLogin(url, studentName) {
            Swal.fire({
                title: 'Keluarkan Siswa?',
                html: `Siswa <b>${studentName}</b> akan dipaksa keluar (Logout) dan harus memasukkan token baru jika ingin masuk lagi.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Keluarkan!',
                cancelButtonText: 'Batal',
                customClass: { popup: 'rounded-[2rem]' }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;
                    
                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = '<?php echo e(csrf_token()); ?>';
                    
                    form.appendChild(csrf);
                    document.body.appendChild(form);
                    
                    Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => Swal.showLoading(), customClass: { popup: 'rounded-[2rem]' } });
                    form.submit();
                }
            });
        }
    </script>

    <div class="py-8 sm:py-10 font-sans text-[#2c3f61]" x-data="monitoringData">
         
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="md:col-span-2 relative rounded-[2rem] bg-gradient-to-r from-[#56bbf1] via-[#e5eff5] to-[#f4d1c0] p-8 text-[#2c3f61] shadow-xl shadow-[#56bbf1]/10 overflow-hidden border border-white/60">
                    <div class="absolute -top-10 -left-10 w-48 h-48 bg-[#0d52a1]/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-3xl"></div>
                    <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-[#f9a282]/20 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-2xl"></div>
                    <div class="absolute top-10 right-32 w-24 h-24 bg-white/40 rounded-2xl rotate-45 pointer-events-none shadow-sm"></div>

                    <div class="relative z-10">
                        <div class="flex flex-wrap items-center gap-3 mb-2">
                            <?php if($exam->is_active): ?>
                                <span class="bg-emerald-400/20 border border-emerald-400/50 text-emerald-700 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider animate-pulse flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Live Active
                                </span>
                            <?php else: ?>
                                <span class="bg-white/40 border border-white/60 text-[#2c3f61]/60 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider">Non-Aktif</span>
                            <?php endif; ?>
                            <span class="text-[#0d52a1] text-xs font-black uppercase tracking-wider bg-white/40 px-2 py-0.5 rounded border border-white/60">Kelas <?php echo e($exam->class_level); ?></span>
                            
                            
                            <?php if(isset($exam->exam_type) && $exam->exam_type == 'google_form'): ?>
                                <span class="bg-emerald-400/20 border border-emerald-400/50 text-emerald-700 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider flex items-center gap-1.5 shadow-sm" title="Ujian Terintegrasi Google Form">
                                    <i class="ph-bold ph-google-logo"></i> G-Form
                                </span>
                            <?php endif; ?>
                        </div>
                        <h1 class="text-3xl font-black tracking-tight leading-none mb-4 text-[#2c3f61]"><?php echo e($exam->title); ?></h1>
                        
                        <div class="flex gap-3">
                            <div class="bg-white/60 backdrop-blur-md px-5 py-3 rounded-2xl border border-white/60 text-center min-w-[90px] shadow-sm">
                                <h4 class="text-2xl font-black text-[#0d52a1] leading-none" x-text="stats.working"><?php echo e($stats['working']); ?></h4>
                                <p class="text-[9px] uppercase font-black text-[#2c3f61]/60 mt-1">Proses</p>
                            </div>
                            <div class="bg-emerald-400/20 backdrop-blur-md px-5 py-3 rounded-2xl border border-emerald-400/30 text-center min-w-[90px] shadow-sm">
                                <h4 class="text-2xl font-black text-emerald-700 leading-none" x-text="stats.finished"><?php echo e($stats['finished']); ?></h4>
                                <p class="text-[9px] uppercase font-black text-emerald-800/70 mt-1">Selesai</p>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="md:col-span-1 bg-white p-6 rounded-[2.5rem] shadow-xl shadow-[#56bbf1]/10 border border-slate-100 flex flex-col justify-between relative overflow-hidden">
                    <div>
                        <div class="flex justify-between items-start mb-2">
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-1.5"><i class="ph-fill ph-key"></i> Token Akses</p>
                            <div class="flex items-center gap-2">
                                <span x-show="autoRotate" class="animate-pulse w-2 h-2 bg-emerald-400 rounded-full"></span>
                                <span x-text="autoRotate ? 'Auto: ' + intervalMinutes + 'm' : 'Manual'" class="text-[10px] font-bold text-slate-500"></span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between gap-3 mb-4">
                            <h2 class="text-5xl font-mono font-black tracking-widest text-[#0d52a1]" x-text="currentToken"><?php echo e($exam->token ?? '-----'); ?></h2>
                            <button @click="rotateTokenNow()" :disabled="tokenIsLoading" class="p-3 rounded-xl bg-[#e5eff5] hover:bg-[#56bbf1]/30 text-[#0d52a1] transition disabled:opacity-50 border border-[#56bbf1]/20">
                                <i class="ph-bold ph-arrows-clockwise text-xl" :class="tokenIsLoading ? 'animate-spin' : ''"></i>
                            </button>
                        </div>
                        <div x-show="autoRotate" class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden mb-4">
                            <div class="bg-[#56bbf1] h-full transition-all duration-1000 ease-linear" :style="'width: ' + tokenProgress + '%'"></div>
                        </div>
                    </div>
                    <div class="bg-[#e5eff5]/50 p-3.5 rounded-2xl border border-[#56bbf1]/20 flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" x-model="autoRotate" class="rounded border-slate-300 text-[#0d52a1] focus:ring-[#56bbf1]">
                            <span class="text-xs font-bold text-[#2c3f61]">Auto Ganti</span>
                        </label>
                        <select x-model="intervalMinutes" :disabled="!autoRotate" class="text-xs font-bold text-[#2c3f61] bg-transparent border-none focus:ring-0 p-0 cursor-pointer text-right disabled:text-slate-400 appearance-none">
                            <option value="5">5 Menit</option>
                            <option value="10">10 Menit</option>
                            <option value="15">15 Menit</option>
                        </select>
                    </div>
                </div>
            </div>

            
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <a href="<?php echo e(route('cbt.index')); ?>" class="group inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-[#0d52a1] transition">
                    <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali ke List
                </a>

                <div class="text-[10px] font-bold px-3 py-1.5 rounded-full border shadow-sm flex items-center gap-2 transition-colors duration-300"
                     :class="isUpdating ? 'bg-[#e5eff5] text-[#0d52a1] border-[#56bbf1]/30' : 'bg-white text-slate-400 border-slate-200'">
                    <i class="ph-bold ph-arrows-clockwise text-[#56bbf1]" :class="isUpdating ? 'animate-spin' : ''"></i>
                    <span x-text="isUpdating ? 'Mengambil Data...' : 'Auto Update: ' + timer + 's'"></span>
                </div>
            </div>

            <!-- TABEL PESERTA -->
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-[#56bbf1]/5 overflow-hidden min-h-[500px]">
                <div class="p-6 border-b border-slate-100 bg-[#e5eff5]/30 flex flex-col md:flex-row justify-between items-center gap-4">
                    <h4 class="font-bold text-[#2c3f61] flex items-center gap-2 text-lg">
                        <i class="ph-fill ph-users-three text-[#56bbf1]"></i> Peserta Ujian 
                        <span class="bg-white text-[#0d52a1] shadow-sm text-xs px-2.5 py-0.5 rounded-full border border-slate-100" x-text="students.length"></span>
                    </h4>
                    <div class="relative w-full md:w-72">
                        <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" x-model="search" placeholder="Cari siswa..." class="w-full pl-10 pr-4 py-2.5 text-sm font-bold border-slate-200 rounded-xl focus:ring-[#56bbf1] focus:border-[#56bbf1] bg-white shadow-sm transition text-[#2c3f61]">
                    </div>
                </div>
                
                
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left text-sm text-[#2c3f61]/80">
                        <thead class="bg-white text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4 w-16 text-center">No</th>
                                <th class="px-6 py-4">Nama Siswa</th>
                                <th class="px-6 py-4 text-center">Keamanan & Perangkat</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-center">Mulai</th>
                                <th class="px-6 py-4 text-center">Skor</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <template x-for="(student, index) in filteredStudents" :key="student.id">
                                <tr class="hover:bg-[#e5eff5]/30 transition group" :class="student.is_active ? 'bg-[#56bbf1]/5' : ''">
                                    <td class="px-6 py-4 text-center font-bold text-slate-400" x-text="index + 1"></td>
                                    <td class="px-6 py-4 font-black text-[#2c3f61]" x-text="student.name"></td>
                                    
                                    
                                    <td class="px-6 py-4 text-center">
                                        <template x-if="student.status !== 'Belum Mengerjakan'">
                                            <div class="flex items-center justify-center gap-2">
                                                <template x-if="student.is_seb">
                                                    <span title="Menggunakan SEB (Aman)" class="flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600">
                                                        <i class="ph-fill ph-shield-check text-lg"></i>
                                                    </span>
                                                </template>
                                                <template x-if="!student.is_seb">
                                                    <span title="Browser Biasa (Mode Darurat)" class="flex items-center justify-center w-8 h-8 rounded-lg bg-[#f9a282]/20 text-[#c86845] animate-pulse border border-[#f9a282]/30">
                                                        <i class="ph-fill ph-warning-circle text-lg"></i>
                                                    </span>
                                                </template>
                                                <template x-if="student.device === 'Mobile'">
                                                    <span title="Menggunakan HP" class="flex items-center justify-center w-8 h-8 rounded-lg bg-slate-50 text-slate-400 border border-slate-100">
                                                        <i class="ph-bold ph-device-mobile text-lg"></i>
                                                    </span>
                                                </template>
                                                <template x-if="student.device === 'Desktop'">
                                                    <span title="Menggunakan Laptop/PC" class="flex items-center justify-center w-8 h-8 rounded-lg bg-slate-50 text-slate-400 border border-slate-100">
                                                        <i class="ph-bold ph-laptop text-lg"></i>
                                                    </span>
                                                </template>
                                            </div>
                                        </template>
                                        <template x-if="student.status === 'Belum Mengerjakan'">
                                            <span class="text-slate-300 text-xs">-</span>
                                        </template>
                                    </td>

                                    
                                    <td class="px-6 py-4 text-center">
                                        <template x-if="student.status === 'Sedang Mengerjakan'">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[10px] font-black bg-[#e5eff5] text-[#0d52a1] border border-[#56bbf1]/30 uppercase tracking-wide">
                                                <span class="block w-1.5 h-1.5 rounded-full bg-[#56bbf1] animate-pulse"></span> Proses
                                            </span>
                                        </template>
                                        <template x-if="student.status === 'Selesai'">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[10px] font-black bg-emerald-100 text-emerald-700 border border-emerald-200 uppercase tracking-wide">
                                                <i class="ph-bold ph-check"></i> Selesai
                                            </span>
                                        </template>
                                        <template x-if="student.status !== 'Sedang Mengerjakan' && student.status !== 'Selesai'">
                                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-[10px] font-black bg-slate-100 text-slate-400 border border-slate-200 uppercase tracking-wide">
                                                Belum
                                            </span>
                                        </template>
                                    </td>
                                    
                                    <td class="px-6 py-4 text-center font-mono text-xs font-medium" x-text="student.start_time"></td>
                                    
                                    
                                    <td class="px-6 py-4 text-center">
                                        <template x-if="examType === 'google_form'">
                                            <span class="text-[10px] font-bold text-slate-400 bg-slate-50 border border-slate-100 px-2 py-1 rounded-lg" title="Nilai ada di platform Google">Cek G-Form</span>
                                        </template>
                                        <template x-if="examType !== 'google_form'">
                                            <span class="font-black text-lg" 
                                                :class="student.status === 'Selesai' ? (student.score > 0 ? 'text-[#0d52a1]' : 'text-[#2c3f61]') : 'text-slate-300'" 
                                                x-text="student.score"></span>
                                        </template>
                                    </td>
                                    
                                    
                                    <td class="px-6 py-4 text-right flex items-center justify-end gap-2">
                                        <template x-if="student.is_active || student.status === 'Selesai'">
                                            <button @click="openPhotoModal(student.id, student.name)" class="w-8 h-8 rounded-xl bg-white border border-[#56bbf1]/30 text-[#0d52a1] hover:bg-[#56bbf1]/10 flex items-center justify-center transition shadow-sm" title="Lihat Foto Pengawasan">
                                                <i class="ph-bold ph-camera"></i>
                                            </button>
                                        </template>
                                        <template x-if="student.is_active">
                                            <button @click="confirmResetLogin(getResetUrl(student.id), student.name)" class="w-8 h-8 rounded-xl bg-white border border-rose-200 text-rose-500 hover:bg-rose-50 flex items-center justify-center transition shadow-sm" title="Keluarkan Siswa (Reset)">
                                                <i class="ph-bold ph-power"></i>
                                            </button>
                                        </template>
                                    </td>
                                </tr>
                            </template>
                            
                            <tr x-show="filteredStudents.length === 0">
                                <td colspan="7" class="px-6 py-12 text-center text-slate-400">Tidak ada siswa yang ditemukan.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                
                <div class="md:hidden p-4 space-y-3 bg-[#e5eff5]/10">
                    <template x-for="student in filteredStudents" :key="'m-' + student.id">
                        <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden">
                            <div class="absolute left-0 top-0 bottom-0 w-1.5" 
                                 :class="student.status == 'Sedang Mengerjakan' ? 'bg-[#56bbf1]' : (student.status == 'Selesai' ? 'bg-emerald-400' : 'bg-slate-200')"></div>
                            
                            <div class="pl-3 flex justify-between items-start">
                                <div>
                                    <h5 class="font-bold text-[#2c3f61] text-sm mb-1" x-text="student.name"></h5>
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded border"
                                              :class="student.status == 'Sedang Mengerjakan' ? 'bg-[#e5eff5] text-[#0d52a1] border-[#56bbf1]/30' : (student.status == 'Selesai' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'text-slate-400 border-transparent')"
                                              x-text="student.status == 'Sedang Mengerjakan' ? 'Sedang Ujian' : (student.status == 'Selesai' ? 'Selesai' : 'Belum Login')">
                                        </span>
                                    </div>
                                    <template x-if="student.status !== 'Belum Mengerjakan'">
                                        <div class="flex gap-2">
                                            <span x-show="student.is_seb" class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded flex items-center gap-1"><i class="ph-fill ph-shield-check"></i> SEB</span>
                                            <span x-show="!student.is_seb" class="text-[10px] font-bold text-[#c86845] bg-[#f9a282]/20 border border-[#f9a282]/30 px-2 py-0.5 rounded flex items-center gap-1"><i class="ph-fill ph-warning-circle"></i> Browser</span>
                                        </div>
                                    </template>
                                </div>
                                <div class="text-right">
                                    <span class="block text-[10px] text-slate-400 font-bold uppercase mb-0.5">Skor</span>
                                    
                                    <template x-if="examType === 'google_form'">
                                        <span class="block text-[10px] font-bold text-slate-500 bg-slate-50 border border-slate-100 px-2 py-1 rounded mt-1">Cek G-Form</span>
                                    </template>
                                    <template x-if="examType !== 'google_form'">
                                        <span class="block text-xl font-black text-[#2c3f61] leading-none" x-text="student.score"></span>
                                    </template>
                                </div>
                            </div>
                            
                            <template x-if="student.is_active">
                                <div class="mt-3 pt-3 border-t border-slate-50 flex justify-end gap-2">
                                    <button @click="openPhotoModal(student.id, student.name)" class="text-xs font-bold text-[#0d52a1] bg-[#e5eff5] px-3 py-1.5 rounded-lg">
                                        <i class="ph-bold ph-camera"></i> Foto
                                    </button>
                                    <button @click="confirmResetLogin(getResetUrl(student.id), student.name)" class="text-xs font-bold text-rose-500 bg-rose-50 px-3 py-1.5 rounded-lg">
                                        <i class="ph-bold ph-power"></i> Reset
                                    </button>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        
        <template x-teleport="body">
            <div x-show="showPhotoModal" style="display: none;" class="fixed inset-0 z-[999] overflow-y-auto" @keydown.escape.window="showPhotoModal = false">
                <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" @click="showPhotoModal = false"></div>
                <div class="flex min-h-screen items-center justify-center p-4">
                    <div class="relative bg-white rounded-[2.5rem] shadow-2xl max-w-3xl w-full p-8 overflow-hidden transform transition-all border border-[#56bbf1]/20">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h3 class="text-xl font-black text-[#2c3f61] flex items-center gap-2">
                                    <i class="ph-fill ph-camera text-[#56bbf1]"></i> Log Foto Pengawasan
                                </h3>
                                <p class="text-sm text-slate-500 font-bold" x-text="activeStudentName"></p>
                            </div>
                            <button @click="showPhotoModal = false" class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 hover:bg-[#e5eff5] hover:text-[#0d52a1] flex items-center justify-center transition">
                                <i class="ph-bold ph-x text-lg"></i>
                            </button>
                        </div>

                        <div class="bg-[#e5eff5]/30 rounded-2xl p-6 min-h-[300px] max-h-[60vh] overflow-y-auto custom-scroll border border-slate-100">
                            
                            <div x-show="loadingPhotos" class="flex flex-col items-center justify-center h-48 text-[#56bbf1]">
                                <i class="ph-bold ph-spinner animate-spin text-3xl mb-2"></i>
                                <span class="text-xs font-black uppercase tracking-wider text-[#0d52a1]">Memuat Foto...</span>
                            </div>

                            
                            <div x-show="!loadingPhotos && studentPhotos.length === 0" class="flex flex-col items-center justify-center h-48 text-slate-400">
                                <i class="ph-duotone ph-image-broken text-4xl mb-2 text-[#56bbf1]/50"></i>
                                <span class="text-xs font-bold">Tidak ada foto terekam.</span>
                            </div>

                            
                            <div x-show="!loadingPhotos && studentPhotos.length > 0" class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <template x-for="photo in studentPhotos">
                                    <div class="bg-white p-2 rounded-xl shadow-sm border border-slate-200 group hover:border-[#56bbf1]/50 transition">
                                        <div class="relative overflow-hidden rounded-lg aspect-video bg-slate-100">
                                            <img :src="photo.url" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500">
                                        </div>
                                        <div class="mt-2 flex justify-between items-center px-1">
                                            <span class="text-[10px] font-black text-[#0d52a1] bg-[#e5eff5] px-2 py-0.5 rounded" x-text="photo.time"></span>
                                            <span class="text-[10px] text-slate-400 font-bold" x-text="photo.ago"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>

    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/cbt/monitoring.blade.php ENDPATH**/ ?>