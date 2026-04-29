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
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-8 sm:py-10 font-sans text-elevate-dark min-h-screen relative overflow-hidden" 
         x-data="{ 
            // 1. TAB PERSISTENCE: Mengambil tab terakhir dari SessionStorage, default ke 'pendidikan'
            activeTab: sessionStorage.getItem('portfolioActiveTab') || 'pendidikan',
            
            editModalOpen: false,
            editType: '', 
            editData: {},
            editFormAction: '',
            
            init() {
                // Pantau perubahan activeTab, lalu simpan ke SessionStorage
                this.$watch('activeTab', value => {
                    sessionStorage.setItem('portfolioActiveTab', value);
                });
            },

            openEditModal(type, item, url) {
                this.editType = type;
                this.editData = JSON.parse(JSON.stringify(item)); 
                
                if (type === 'art' && this.editData.published_at) {
                    this.editData.published_at = this.editData.published_at.substring(0, 10);
                }
                
                this.editFormAction = url;
                this.editModalOpen = true;
                document.body.style.overflow = 'hidden';
            },
            
            closeEditModal() {
                this.editModalOpen = false;
                document.body.style.overflow = 'auto';
                setTimeout(() => { 
                    this.editData = {}; 
                    this.editType = ''; 
                    this.editFormAction = '';
                }, 300);
            },

            confirmDelete(event, message) {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e11d48', // Tailwind rose-600
                    cancelButtonColor: '#94a3b8', 
                    confirmButtonText: '<i class=\'ph-bold ph-trash\'></i> Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true, 
                    customClass: {
                        popup: 'rounded-[2rem] shadow-2xl',
                        confirmButton: 'px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 border border-transparent transition-all',
                        cancelButton: 'px-5 py-2.5 rounded-xl font-bold border border-transparent transition-all'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.target.submit(); 
                    }
                })
            }
         }">
         
        
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
           
            <div class="animate-enter relative rounded-[2rem] bg-elevate-gradient-main p-6 md:p-10 text-elevate-dark shadow-xl shadow-elevate-accent/20 overflow-hidden group border border-white/60 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                
                
                <div class="absolute inset-0 opacity-[0.03] bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none mix-blend-overlay"></div>
                <div class="absolute top-0 right-0 w-80 h-80 bg-white/40 rounded-full blur-[80px] translate-x-1/2 -translate-y-1/2 pointer-events-none group-hover:bg-white/60 transition-all duration-700"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-elevate-peach/30 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-xl"></div>

                <div class="relative z-10 flex items-center gap-5">
                    <div class="w-16 h-16 rounded-2xl bg-white/50 backdrop-blur-md flex items-center justify-center border border-white/60 shadow-sm shrink-0 text-elevate-primary">
                        <i class="ph-duotone ph-chart-polar text-4xl"></i>
                    </div>
                    <div>
                        
                        <?php
                            $managedUser = $targetUser ?? auth()->user();
                            
                            $userRoles = is_string($managedUser->role) ? json_decode($managedUser->role, true) : $managedUser->role;
                            if (!is_array($userRoles)) {
                                $userRoles = is_string($managedUser->role) ? explode(',', $managedUser->role) : [$managedUser->role];
                            }
                            $userRoles = array_filter(array_map('trim', $userRoles));
                        ?>

                        <div class="flex flex-wrap gap-2 mb-2">
                            <?php $__currentLoopData = $userRoles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-white/50 border border-white/60 text-elevate-dark text-[10px] font-bold uppercase tracking-wider backdrop-blur-sm shadow-sm">
                                    <i class="ph-bold ph-check-circle text-elevate-primary"></i> <?php echo e($role); ?>

                                </span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            
                            <?php if(!empty($managedUser->position) && $managedUser->position !== '-'): ?>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-elevate-soft/80 border border-elevate-accent/30 text-elevate-primary text-[10px] font-bold uppercase tracking-wider backdrop-blur-sm shadow-sm">
                                    <i class="ph-bold ph-briefcase"></i> <?php echo e($managedUser->position); ?>

                                </span>
                            <?php endif; ?>
                        </div>

                        <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight leading-tight text-elevate-dark">
                            Kelola Portofolio <?php echo e(isset($targetUser) && $targetUser->id !== auth()->id() ? '- ' . $targetUser->name : ''); ?>

                        </h2>
                        <p class="text-elevate-dark/80 text-sm mt-1 font-semibold">Tambahkan karya, materi, dan pengalaman untuk ditampilkan di direktori publik.</p>
                    </div>
                </div>
                <div class="relative z-10 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 w-full md:w-auto">
                    <a href="<?php echo e(route('teachers.show', request('user_id') ?? auth()->id())); ?>" target="_blank" class="w-full md:w-auto justify-center px-6 py-3.5 bg-elevate-dark border border-transparent text-white font-bold rounded-xl hover:bg-elevate-primary transition-all flex items-center gap-2 shadow-lg shadow-elevate-dark/30 active:scale-95">
                        <i class="ph-bold ph-eye text-lg"></i> Lihat Profil Publik
                    </a>
                </div>
            </div>

            
            <?php if(session('success')): ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.mixin({
                            toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true,
                            customClass: { popup: 'rounded-2xl border border-elevate-accent/30 shadow-lg bg-elevate-soft text-elevate-primary' },
                            didOpen: (toast) => { toast.addEventListener('mouseenter', Swal.stopTimer); toast.addEventListener('mouseleave', Swal.resumeTimer); }
                        }).fire({ icon: 'success', title: '<?php echo e(session('success')); ?>', iconColor: '#0d52a1' });
                    });
                </script>
            <?php endif; ?>

            
            <?php if($errors->any()): ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error', title: 'Gagal Menyimpan!',
                            html: `
                                <div class="text-left text-sm mt-2">
                                    <p class="text-slate-600 mb-2">Mohon perbaiki kesalahan berikut:</p>
                                    <ul class="list-disc pl-5 space-y-1">
                                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li class="text-rose-500 font-bold"><?php echo e($error); ?></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                            `,
                            confirmButtonColor: '#2c3f61', confirmButtonText: 'Tutup & Perbaiki', customClass: { popup: 'rounded-[2rem] shadow-2xl' }
                        });
                    });
                </script>
            <?php endif; ?>

            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm flex flex-col md:flex-row min-h-[600px] relative mt-8 overflow-hidden">
                
                
                <div class="md:w-64 bg-slate-50/50 border-r border-slate-100 p-6 shrink-0 z-10">
                    <nav class="flex md:flex-col gap-2 overflow-x-auto custom-scrollbar pb-2 md:pb-0 hide-scroll-mobile">
                        <button @click="activeTab = 'pendidikan'" :class="activeTab === 'pendidikan' ? 'bg-elevate-primary text-white shadow-md shadow-elevate-primary/20' : 'text-slate-500 hover:bg-white hover:text-elevate-dark border border-transparent hover:border-slate-100'" class="w-full text-left px-4 py-3.5 rounded-xl font-bold text-sm transition-all flex items-center gap-3 whitespace-nowrap">
                            <i class="ph-bold ph-graduation-cap text-lg"></i> Pendidikan
                        </button>
                        <button @click="activeTab = 'pengalaman'" :class="activeTab === 'pengalaman' ? 'bg-elevate-primary text-white shadow-md shadow-elevate-primary/20' : 'text-slate-500 hover:bg-white hover:text-elevate-dark border border-transparent hover:border-slate-100'" class="w-full text-left px-4 py-3.5 rounded-xl font-bold text-sm transition-all flex items-center gap-3 whitespace-nowrap">
                            <i class="ph-bold ph-student text-lg"></i> Pengalaman
                        </button>
                        <button @click="activeTab = 'materi'" :class="activeTab === 'materi' ? 'bg-elevate-primary text-white shadow-md shadow-elevate-primary/20' : 'text-slate-500 hover:bg-white hover:text-elevate-dark border border-transparent hover:border-slate-100'" class="w-full text-left px-4 py-3.5 rounded-xl font-bold text-sm transition-all flex items-center gap-3 whitespace-nowrap">
                            <i class="ph-bold ph-presentation-chart text-lg"></i> Materi & Media
                        </button>
                        <button @click="activeTab = 'portofolio'" :class="activeTab === 'portofolio' ? 'bg-elevate-primary text-white shadow-md shadow-elevate-primary/20' : 'text-slate-500 hover:bg-white hover:text-elevate-dark border border-transparent hover:border-slate-100'" class="w-full text-left px-4 py-3.5 rounded-xl font-bold text-sm transition-all flex items-center gap-3 whitespace-nowrap">
                            <i class="ph-bold ph-trophy text-lg"></i> Prestasi / Galeri
                        </button>
                        <button @click="activeTab = 'artikel'" :class="activeTab === 'artikel' ? 'bg-elevate-primary text-white shadow-md shadow-elevate-primary/20' : 'text-slate-500 hover:bg-white hover:text-elevate-dark border border-transparent hover:border-slate-100'" class="w-full text-left px-4 py-3.5 rounded-xl font-bold text-sm transition-all flex items-center gap-3 whitespace-nowrap">
                            <i class="ph-bold ph-article text-lg"></i> Artikel Tulisan
                        </button>
                    </nav>
                </div>

                
                <div class="p-6 md:p-8 flex-1 overflow-hidden bg-white">

                    
                    <div x-show="activeTab === 'pengalaman'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                        <div class="flex items-center gap-3 mb-6 pb-2 border-b border-slate-100">
                            <div class="w-10 h-10 rounded-xl bg-elevate-peach-light/50 text-elevate-peach-dark border border-elevate-peach/30 flex items-center justify-center"><i class="ph-bold ph-student text-xl"></i></div>
                            <h3 class="text-xl font-black text-elevate-dark">Riwayat Pelatihan & Sertifikasi</h3>
                        </div>
                        
                    <form action="<?php echo e(route('portfolio.exp.store')); ?>" method="POST" enctype="multipart/form-data" x-data="{ isSubmitting: false }" @submit="isSubmitting = true" class="bg-elevate-surface p-6 rounded-2xl border border-slate-100 shadow-sm mb-8 grid grid-cols-1 md:grid-cols-4 gap-4">
                        <?php echo csrf_field(); ?>
                        <?php if(request('user_id')): ?> <input type="hidden" name="user_id" value="<?php echo e(request('user_id')); ?>"> <?php endif; ?>
                        <div>
                            <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Tahun</label>
                            <input type="number" name="year" value="<?php echo e(old('year')); ?>" placeholder="2023" class="w-full rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3 px-4 transition-all" required>
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Nama Pelatihan / Sertifikasi</label>
                            <input type="text" name="title" value="<?php echo e(old('title')); ?>" placeholder="Cth: Diklat Guru Penggerak..." class="w-full rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3 px-4 transition-all" required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Penyelenggara</label>
                            <input type="text" name="organizer" value="<?php echo e(old('organizer')); ?>" placeholder="Cth: Kemdikbud..." class="w-full rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3 px-4 transition-all">
                        </div>
                        
                        
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Upload Sertifikat (PDF/Gambar)</label>
                            <input type="file" name="certificate" accept=".pdf,image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:font-bold file:bg-elevate-soft file:text-elevate-primary hover:file:bg-elevate-primary/10 transition-all bg-white rounded-2xl border border-slate-200">
                        </div>

                        <div class="md:col-span-4 flex justify-end mt-2">
                            <button type="submit" :disabled="isSubmitting" :class="{'opacity-70 cursor-wait': isSubmitting}" class="w-full md:w-auto px-8 py-3.5 bg-elevate-dark text-white font-bold rounded-xl hover:bg-elevate-primary shadow-lg shadow-elevate-dark/30 transition-all flex justify-center items-center gap-2 active:scale-95 border border-transparent">
                                <span x-show="!isSubmitting"><i class="ph-bold ph-plus text-lg"></i> Tambah</span>
                                <span x-show="isSubmitting" x-cloak><i class="ph-bold ph-spinner animate-spin text-lg"></i> Loading...</span>
                            </button>
                        </div>
                    </form>

                        <div class="space-y-4">
                            <?php $__empty_1 = true; $__currentLoopData = $experiences ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between p-5 bg-white border border-slate-200 rounded-2xl hover:shadow-xl hover:shadow-elevate-accent/10 hover:border-elevate-accent/50 transition-all duration-300 group gap-3 sm:gap-0">
                                    <div class="flex items-start gap-4 pr-4">
                                        <div class="px-3 py-1.5 bg-elevate-soft text-elevate-primary border border-elevate-accent/20 rounded-lg text-xs font-black mt-1"><?php echo e($exp->year); ?></div>
                                        <div>
                                            <h4 class="font-bold text-elevate-dark text-lg group-hover:text-elevate-primary transition-colors"><?php echo e($exp->title); ?></h4>
                                            <p class="text-sm font-medium text-slate-500 mt-0.5"><?php echo e($exp->organizer); ?></p>
                                            
                                            
                                            <?php if($exp->certificate_path): ?>
                                                <a href="<?php echo e(asset('storage/' . $exp->certificate_path)); ?>" target="_blank" class="inline-flex items-center gap-1.5 mt-3 text-[10px] uppercase tracking-wider font-black text-elevate-primary bg-elevate-soft border border-elevate-accent/20 px-2.5 py-1.5 rounded-lg hover:bg-elevate-primary hover:text-white transition-colors">
                                                    <i class="ph-bold ph-certificate"></i> Lihat Sertifikat
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-opacity justify-end border-t border-slate-100 sm:border-0 pt-3 sm:pt-0">
                                        <button type="button" @click="openEditModal('exp', <?php echo \Illuminate\Support\Js::from($exp)->toHtml() ?>, '<?php echo e(route('portfolio.exp.update', ['id' => $exp->id, 'user_id' => request('user_id')])); ?>')" class="w-10 h-10 flex items-center justify-center text-elevate-peach bg-elevate-peach-light/30 border border-elevate-peach/30 sm:text-slate-400 sm:bg-white sm:border-slate-200 sm:hover:text-white sm:hover:bg-elevate-peach sm:hover:border-elevate-peach rounded-xl transition-all" title="Edit">
                                            <i class="ph-bold ph-pencil-simple text-lg"></i>
                                        </button>
                                        <form action="<?php echo e(route('portfolio.exp.destroy', ['id' => $exp->id, 'user_id' => request('user_id')])); ?>" method="POST" @submit.prevent="confirmDelete($event, 'Menghapus riwayat pelatihan tidak dapat dibatalkan.')">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="w-10 h-10 flex items-center justify-center text-rose-500 bg-rose-50 border border-rose-200 sm:text-slate-400 sm:bg-white sm:border-slate-200 sm:hover:text-white sm:hover:bg-rose-500 sm:hover:border-rose-500 rounded-xl transition-all" title="Hapus">
                                                <i class="ph-bold ph-trash text-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="text-center py-12 bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200">
                                    <div class="w-20 h-20 bg-elevate-peach-light/50 rounded-full flex items-center justify-center mx-auto mb-4 text-elevate-peach-dark">
                                        <i class="ph-duotone ph-folder-dashed text-4xl"></i>
                                    </div>
                                    <p class="text-elevate-dark/70 text-sm font-medium">Belum ada data pengalaman ditambahkan.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    
                    <div x-show="activeTab === 'materi'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                        <div class="flex items-center gap-3 mb-6 pb-2 border-b border-slate-100">
                            <div class="w-10 h-10 rounded-xl bg-elevate-peach-light/50 text-elevate-peach-dark border border-elevate-peach/30 flex items-center justify-center"><i class="ph-bold ph-presentation-chart text-xl"></i></div>
                            <h3 class="text-xl font-black text-elevate-dark">Materi & Media Pembelajaran</h3>
                        </div>
                        
                        <form action="<?php echo e(route('portfolio.mat.store')); ?>" method="POST" enctype="multipart/form-data" x-data="{ isSubmitting: false, fileName: '' }" @submit="isSubmitting = true" class="bg-elevate-surface p-6 rounded-2xl border border-slate-100 shadow-sm mb-8 grid grid-cols-1 md:grid-cols-2 gap-5">
                            <?php echo csrf_field(); ?>
                            <?php if(request('user_id')): ?> <input type="hidden" name="user_id" value="<?php echo e(request('user_id')); ?>"> <?php endif; ?>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Judul Materi</label>
                                <input type="text" name="title" value="<?php echo e(old('title')); ?>" class="w-full rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3 px-4 transition-all" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Tipe (Misal: Modul PDF, Slide PPT)</label>
                                <input type="text" name="type" value="<?php echo e(old('type')); ?>" class="w-full rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3 px-4 transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Link URL (Jika File di GDrive/Youtube)</label>
                                <input type="url" name="file_url" value="<?php echo e(old('file_url')); ?>" placeholder="https://..." class="w-full rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3 px-4 transition-all">
                            </div>
                            <div class="md:col-span-2 p-4 bg-white border-2 border-dashed border-slate-200 rounded-2xl hover:border-elevate-accent hover:bg-elevate-soft transition-colors">
                                <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2">Atau Upload File Langsung (Opsional)</label>
                                <div class="flex items-center gap-3">
                                    <input type="file" name="file" @change="fileName = $event.target.files[0].name" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-elevate-soft file:text-elevate-primary hover:file:bg-elevate-primary/10 cursor-pointer transition-all">
                                </div>
                            </div>
                            <div class="md:col-span-2 flex justify-end mt-2">
                                <button type="submit" :disabled="isSubmitting" :class="{'opacity-70 cursor-wait': isSubmitting}" class="w-full md:w-auto px-8 py-3.5 bg-elevate-dark text-white font-bold rounded-xl hover:bg-elevate-primary shadow-lg shadow-elevate-dark/30 transition-all flex justify-center items-center gap-2 active:scale-95 border border-transparent">
                                    <span x-show="!isSubmitting"><i class="ph-bold ph-upload-simple text-lg"></i> Simpan Materi</span>
                                    <span x-show="isSubmitting" x-cloak><i class="ph-bold ph-spinner animate-spin text-lg"></i> Mengunggah...</span>
                                </button>
                            </div>
                        </form>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <?php $__empty_1 = true; $__currentLoopData = $materials ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="flex flex-col sm:flex-row items-start gap-4 p-5 bg-elevate-gradient-card border border-slate-200 rounded-2xl hover:shadow-xl hover:shadow-elevate-accent/10 hover:border-elevate-accent/50 transition-all relative group">
                                    <div class="flex gap-4 w-full">
                                        <div class="w-14 h-14 rounded-2xl bg-white flex items-center justify-center shrink-0 border border-slate-100 shadow-sm text-elevate-primary">
                                            <i class="<?php echo e($mat->icon ?? 'ph-duotone ph-file-text'); ?> text-3xl"></i>
                                        </div>
                                        <div class="flex-1 sm:pr-14">
                                            <h4 class="font-bold text-base text-elevate-dark line-clamp-2 leading-tight group-hover:text-elevate-primary transition-colors"><?php echo e($mat->title); ?></h4>
                                            <p class="text-xs font-semibold text-slate-500 mt-1 mb-3"><?php echo e($mat->type); ?></p>
                                            <?php if($mat->file_url): ?> 
                                                <a href="<?php echo e($mat->file_url); ?>" target="_blank" class="inline-flex items-center gap-1.5 text-[10px] uppercase tracking-wider font-black text-elevate-primary bg-white border border-elevate-accent/20 shadow-sm px-3 py-1.5 rounded-lg hover:bg-elevate-primary hover:text-white transition-all"><i class="ph-bold ph-link"></i> Buka Link</a>
                                            <?php elseif($mat->file_path): ?>
                                                <a href="<?php echo e(asset('storage/'.$mat->file_path)); ?>" target="_blank" class="inline-flex items-center gap-1.5 text-[10px] uppercase tracking-wider font-black text-elevate-primary bg-white border border-elevate-accent/20 shadow-sm px-3 py-1.5 rounded-lg hover:bg-elevate-primary hover:text-white transition-all"><i class="ph-bold ph-download-simple"></i> Download</a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="flex flex-row sm:flex-col gap-2 w-full sm:w-auto justify-end sm:absolute sm:top-5 sm:right-5 border-t sm:border-t-0 border-slate-100 pt-3 sm:pt-0 mt-2 sm:mt-0 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                                        <button type="button" @click="openEditModal('mat', <?php echo \Illuminate\Support\Js::from($mat)->toHtml() ?>, '<?php echo e(route('portfolio.mat.update', ['id' => $mat->id, 'user_id' => request('user_id')])); ?>')" class="flex-1 sm:flex-none h-10 sm:w-10 flex items-center justify-center text-elevate-peach bg-elevate-peach-light/30 border border-elevate-peach/30 sm:text-slate-400 sm:bg-white sm:border-slate-200 sm:hover:text-white sm:hover:bg-elevate-peach sm:hover:border-elevate-peach rounded-xl transition-all"><i class="ph-bold ph-pencil-simple text-lg"></i></button>
                                        <form action="<?php echo e(route('portfolio.mat.destroy', ['id' => $mat->id, 'user_id' => request('user_id')])); ?>" method="POST" @submit.prevent="confirmDelete($event, 'File materi yang dihapus tidak dapat dikembalikan.')" class="flex-1 sm:flex-none">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <button class="w-full h-10 sm:w-10 flex items-center justify-center text-rose-500 bg-rose-50 border border-rose-200 sm:text-slate-400 sm:bg-white sm:border-slate-200 sm:hover:text-white sm:hover:bg-rose-500 sm:hover:border-rose-500 rounded-xl transition-all"><i class="ph-bold ph-trash text-lg"></i></button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="sm:col-span-2 text-center py-12 bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200">
                                    <div class="w-20 h-20 bg-elevate-peach-light/50 rounded-full flex items-center justify-center mx-auto mb-4 text-elevate-peach-dark">
                                        <i class="ph-duotone ph-folder-dashed text-4xl"></i>
                                    </div>
                                    <p class="text-elevate-dark/70 text-sm font-medium">Belum ada materi/media dibagikan.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    
                    <div x-show="activeTab === 'portofolio'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                        <div class="flex items-center gap-3 mb-6 pb-2 border-b border-slate-100">
                            <div class="w-10 h-10 rounded-xl bg-elevate-peach-light/50 text-elevate-peach-dark border border-elevate-peach/30 flex items-center justify-center"><i class="ph-bold ph-trophy text-xl"></i></div>
                            <h3 class="text-xl font-black text-elevate-dark">Galeri Portofolio & Pencapaian</h3>
                        </div>
                        
                        <form action="<?php echo e(route('portfolio.port.store')); ?>" method="POST" enctype="multipart/form-data" 
                              x-data="{ isSubmitting: false, imagePreviews: [] }" 
                              @submit.prevent="
                                  let fileInput = $el.querySelector('input[type=file]');
                                  let files = fileInput.files;
                                  let overSize = false;
                                  let totalSize = 0;
                                  for(let i=0; i<files.length; i++) {
                                      if(files[i].size > 2048 * 1024) overSize = true; // max 2MB/file
                                      totalSize += files[i].size;
                                  }
                                  
                                  if(overSize) {
                                      Swal.fire({ icon: 'warning', title: 'Foto Terlalu Besar', text: 'Ukuran maksimal 1 foto adalah 2MB. Silakan kompres foto Anda terlebih dahulu.', confirmButtonColor: '#2c3f61', customClass: { popup: 'rounded-[2rem] shadow-2xl' } });
                                      return;
                                  }
                                  if(totalSize > 8388608) { // 8MB
                                      Swal.fire({ icon: 'warning', title: 'Kapasitas Penuh', text: 'Total keseluruhan foto melebihi kapasitas memori server (8MB). Silakan kurangi jumlah foto yang diupload bersamaan.', confirmButtonColor: '#2c3f61', customClass: { popup: 'rounded-[2rem] shadow-2xl' } });
                                      return;
                                  }
                                  
                                  isSubmitting = true; 
                                  $el.submit();
                              " 
                              class="bg-elevate-surface p-6 rounded-2xl border border-slate-100 shadow-sm mb-8">
                            <?php echo csrf_field(); ?>
                            <?php if(request('user_id')): ?> <input type="hidden" name="user_id" value="<?php echo e(request('user_id')); ?>"> <?php endif; ?>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 items-end">
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Judul Kegiatan / Prestasi</label>
                                    <input type="text" name="title" value="<?php echo e(old('title')); ?>" class="w-full rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3 px-4 transition-all" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Tahun</label>
                                    <input type="text" name="year" value="<?php echo e(old('year')); ?>" class="w-full rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3 px-4 transition-all">
                                </div>
                                <div class="md:col-span-3 p-5 bg-white border-2 border-dashed border-slate-200 rounded-2xl flex flex-col md:flex-row gap-5 items-center justify-between hover:border-elevate-accent hover:bg-elevate-soft transition-colors">
                                    <div class="flex-1 w-full">
                                        <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2">Upload Foto Dokumentasi (Bisa Pilih Lebih Dari Satu)</label>
                                        <input type="file" name="images[]" accept="image/*" multiple @change="imagePreviews = Array.from($event.target.files).map(file => URL.createObjectURL(file))" class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:font-bold file:bg-elevate-soft file:text-elevate-primary cursor-pointer transition-all" required>
                                    </div>
                                    
                                    
                                    <template x-if="imagePreviews.length > 0">
                                        <div class="flex gap-2 overflow-x-auto max-w-[200px] custom-scrollbar">
                                            <template x-for="img in imagePreviews">
                                                <div class="w-16 h-16 rounded-xl overflow-hidden border border-slate-200 shrink-0 shadow-sm">
                                                    <img :src="img" class="w-full h-full object-cover">
                                                </div>
                                            </template>
                                        </div>
                                    </template>

                                    <button type="submit" :disabled="isSubmitting" :class="{'opacity-70 cursor-wait': isSubmitting}" class="px-8 py-3.5 bg-elevate-dark text-white font-bold rounded-xl hover:bg-elevate-primary shadow-lg shadow-elevate-dark/30 transition-all shrink-0 w-full md:w-auto flex justify-center items-center gap-2 active:scale-95 border border-transparent">
                                        <span x-show="!isSubmitting"><i class="ph-bold ph-upload-simple text-lg"></i> Simpan</span>
                                        <span x-show="isSubmitting" x-cloak><i class="ph-bold ph-spinner animate-spin text-lg"></i> Uploading...</span>
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="grid grid-cols-2 md:grid-cols-3 gap-5">
                            <?php $__empty_1 = true; $__currentLoopData = $portfolios ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $port): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php
                                    $images = json_decode($port->image_path, true);
                                    if (!is_array($images)) {
                                        $images = $port->image_path ? [$port->image_path] : [];
                                    }
                                    $port->images_array = $images;
                                ?>
                                <div class="relative group rounded-2xl overflow-hidden border border-slate-200 shadow-sm hover:shadow-xl hover:shadow-elevate-accent/20 hover:border-elevate-accent/50 transition-all duration-300 bg-white">
                                    <div class="aspect-square bg-slate-100 relative overflow-hidden">
                                        <?php if(count($images) > 0): ?>
                                            <img src="<?php echo e(asset('storage/' . $images[0])); ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                            <?php if(count($images) > 1): ?>
                                                <div class="absolute top-3 left-3 bg-elevate-dark/80 backdrop-blur-md text-white text-[10px] font-black px-2.5 py-1 rounded-lg flex items-center gap-1.5 shadow-sm">
                                                    <i class="ph-bold ph-images"></i> +<?php echo e(count($images) - 1); ?> Foto
                                                </div>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <div class="w-full h-full flex items-center justify-center text-slate-300">
                                                <i class="ph-duotone ph-image text-5xl"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="absolute inset-0 bg-gradient-to-t from-elevate-dark/90 via-elevate-dark/40 to-transparent opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-between p-5">
                                        <div class="flex justify-end gap-2">
                                            <button type="button" @click="openEditModal('port', <?php echo \Illuminate\Support\Js::from($port)->toHtml() ?>, '<?php echo e(route('portfolio.port.update', ['id' => $port->id, 'user_id' => request('user_id')])); ?>')" class="w-10 h-10 bg-white/20 backdrop-blur-md border border-white/30 text-white rounded-xl hover:bg-elevate-peach hover:border-elevate-peach transition-all flex items-center justify-center shadow-sm"><i class="ph-bold ph-pencil-simple text-lg"></i></button>
                                            <form action="<?php echo e(route('portfolio.port.destroy', ['id' => $port->id, 'user_id' => request('user_id')])); ?>" method="POST" @submit.prevent="confirmDelete($event, 'Foto galeri yang dihapus tidak dapat dikembalikan.')">
                                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                <button class="w-10 h-10 bg-white/20 backdrop-blur-md border border-white/30 text-white rounded-xl hover:bg-rose-500 hover:border-rose-500 transition-all flex items-center justify-center shadow-sm"><i class="ph-bold ph-trash text-lg"></i></button>
                                            </form>
                                        </div>
                                        <div>
                                            <span class="inline-block px-2.5 py-1 bg-elevate-primary text-white text-[10px] font-black rounded-lg mb-2 shadow-sm"><?php echo e($port->year); ?></span>
                                            <h4 class="text-white font-bold text-base leading-tight line-clamp-2"><?php echo e($port->title); ?></h4>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="col-span-2 md:col-span-3 text-center py-12 bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200">
                                    <div class="w-20 h-20 bg-elevate-peach-light/50 rounded-full flex items-center justify-center mx-auto mb-4 text-elevate-peach-dark">
                                        <i class="ph-duotone ph-folder-dashed text-4xl"></i>
                                    </div>
                                    <p class="text-elevate-dark/70 text-sm font-medium">Belum ada foto galeri.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    
                    <div x-show="activeTab === 'artikel'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                        <div class="flex items-center gap-3 mb-6 pb-2 border-b border-slate-100">
                            <div class="w-10 h-10 rounded-xl bg-elevate-peach-light/50 text-elevate-peach-dark border border-elevate-peach/30 flex items-center justify-center"><i class="ph-bold ph-article text-xl"></i></div>
                            <h3 class="text-xl font-black text-elevate-dark">Artikel & Tulisan Terpublikasi</h3>
                        </div>
                        
                        <form action="<?php echo e(route('portfolio.art.store')); ?>" method="POST" enctype="multipart/form-data" x-data="{ isSubmitting: false, imagePreview: null }" @submit="isSubmitting = true" class="bg-elevate-surface p-6 rounded-2xl border border-slate-100 shadow-sm mb-8 grid grid-cols-1 md:grid-cols-2 gap-5">
                            <?php echo csrf_field(); ?>
                            <?php if(request('user_id')): ?> <input type="hidden" name="user_id" value="<?php echo e(request('user_id')); ?>"> <?php endif; ?>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Judul Artikel / Opini</label>
                                <input type="text" name="title" value="<?php echo e(old('title')); ?>" class="w-full rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3 px-4 transition-all" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Kategori Topik</label>
                                <input type="text" name="category" value="<?php echo e(old('category')); ?>" placeholder="Pendidikan, Opini..." class="w-full rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3 px-4 transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Tanggal Publikasi</label>
                                <input type="date" name="published_at" value="<?php echo e(old('published_at')); ?>" class="w-full rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3 px-4 transition-all text-sm">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Ringkasan / Excerpt</label>
                                <textarea name="excerpt" rows="2" class="w-full rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-medium text-elevate-dark py-3 px-4 transition-all"><?php echo e(old('excerpt')); ?></textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Link URL Artikel Asli (Opsional)</label>
                                <input type="url" name="url" value="<?php echo e(old('url')); ?>" placeholder="https://..." class="w-full rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3 px-4 transition-all">
                            </div>
                            
                            <div class="flex items-center gap-4">
                                <div class="flex-1">
                                    <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Thumbnail Cover (Opsional)</label>
                                    <input type="file" name="image" accept="image/*" @change="imagePreview = URL.createObjectURL($event.target.files[0])" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:font-bold file:bg-elevate-soft file:text-elevate-primary hover:file:bg-elevate-primary/10 transition-all bg-white rounded-2xl border border-slate-200 overflow-hidden">
                                </div>
                                <template x-if="imagePreview">
                                    <img :src="imagePreview" class="w-16 h-16 rounded-xl object-cover border border-slate-200 mt-6 shadow-sm">
                                </template>
                            </div>

                            <div class="md:col-span-2 flex justify-end mt-4">
                                <button type="submit" :disabled="isSubmitting" :class="{'opacity-70 cursor-wait': isSubmitting}" class="w-full md:w-auto px-8 py-3.5 bg-elevate-dark text-white font-bold rounded-xl hover:bg-elevate-primary shadow-lg shadow-elevate-dark/30 transition-all flex justify-center items-center gap-2 active:scale-95 border border-transparent">
                                    <span x-show="!isSubmitting"><i class="ph-bold ph-floppy-disk text-lg"></i> Simpan Artikel</span>
                                    <span x-show="isSubmitting" x-cloak><i class="ph-bold ph-spinner animate-spin text-lg"></i> Menyimpan...</span>
                                </button>
                            </div>
                        </form>

                        <div class="space-y-5">
                            <?php $__empty_1 = true; $__currentLoopData = $articles ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $art): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="flex flex-col sm:flex-row items-stretch gap-5 p-5 bg-elevate-gradient-card border border-slate-200 rounded-2xl relative group hover:shadow-xl hover:shadow-elevate-accent/10 hover:border-elevate-accent/50 transition-all">
                                    <div class="flex gap-5 w-full">
                                        <?php if($art->image_path): ?>
                                            <div class="w-28 h-28 rounded-2xl overflow-hidden shrink-0 bg-white border border-slate-100 shadow-sm">
                                                <img src="<?php echo e(asset('storage/' . $art->image_path)); ?>" class="w-full h-full object-cover">
                                            </div>
                                        <?php endif; ?>
                                        <div class="flex-1 py-1 sm:pr-16">
                                            <span class="inline-block px-2.5 py-1 bg-elevate-soft border border-elevate-accent/20 text-elevate-primary text-[10px] font-black rounded-lg uppercase tracking-wider mb-2 shadow-sm"><?php echo e($art->category ?? 'Umum'); ?></span>
                                            <h4 class="font-bold text-elevate-dark text-lg leading-tight group-hover:text-elevate-primary transition-colors"><?php echo e($art->title); ?></h4>
                                            <p class="text-sm font-medium text-slate-500 mt-2 line-clamp-2"><?php echo e($art->excerpt); ?></p>
                                            <?php if($art->url): ?> 
                                                <a href="<?php echo e($art->url); ?>" target="_blank" class="inline-flex items-center gap-1.5 text-[10px] uppercase tracking-wider font-black text-elevate-primary hover:text-elevate-dark mt-3 transition-colors"><i class="ph-bold ph-link"></i> Baca di Web Asli</a> 
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="flex flex-row sm:flex-col gap-2 justify-end sm:absolute sm:top-5 sm:right-5 border-t sm:border-t-0 border-slate-100 pt-3 sm:pt-0 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                                        <button type="button" @click="openEditModal('art', <?php echo \Illuminate\Support\Js::from($art)->toHtml() ?>, '<?php echo e(route('portfolio.art.update', ['id' => $art->id, 'user_id' => request('user_id')])); ?>')" class="flex-1 sm:flex-none h-10 sm:w-10 flex items-center justify-center text-elevate-peach bg-elevate-peach-light/30 border border-elevate-peach/30 sm:text-slate-400 sm:bg-white sm:border-slate-200 sm:hover:text-white sm:hover:bg-elevate-peach sm:hover:border-elevate-peach rounded-xl transition-all"><i class="ph-bold ph-pencil-simple text-lg"></i></button>
                                        <form action="<?php echo e(route('portfolio.art.destroy', ['id' => $art->id, 'user_id' => request('user_id')])); ?>" method="POST" @submit.prevent="confirmDelete($event, 'Menghapus data artikel tidak dapat dibatalkan.')" class="flex-1 sm:flex-none">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <button class="w-full h-10 sm:w-10 flex items-center justify-center text-rose-500 bg-rose-50 border border-rose-200 sm:text-slate-400 sm:bg-white sm:border-slate-200 sm:hover:text-white sm:hover:bg-rose-500 sm:hover:border-rose-500 rounded-xl transition-all"><i class="ph-bold ph-trash text-lg"></i></button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="text-center py-12 bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200">
                                    <div class="w-20 h-20 bg-elevate-peach-light/50 rounded-full flex items-center justify-center mx-auto mb-4 text-elevate-peach-dark">
                                        <i class="ph-duotone ph-folder-dashed text-4xl"></i>
                                    </div>
                                    <p class="text-elevate-dark/70 text-sm font-medium">Belum ada tulisan artikel.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    
                    <div x-show="activeTab === 'pendidikan'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                        <div class="flex items-center gap-3 mb-6 pb-2 border-b border-slate-100">
                            <div class="w-10 h-10 rounded-xl bg-elevate-peach-light/50 text-elevate-peach-dark border border-elevate-peach/30 flex items-center justify-center"><i class="ph-bold ph-graduation-cap text-xl"></i></div>
                            <h3 class="text-xl font-black text-elevate-dark">Riwayat Pendidikan Formal</h3>
                        </div>
                        
                        <form action="<?php echo e(route('portfolio.edu.store')); ?>" method="POST" x-data="{ isSubmitting: false }" @submit="isSubmitting = true" class="bg-elevate-surface p-6 rounded-2xl border border-slate-100 shadow-sm mb-8 grid grid-cols-1 md:grid-cols-4 gap-4">
                            <?php echo csrf_field(); ?>
                            <?php if(request('user_id')): ?> <input type="hidden" name="user_id" value="<?php echo e(request('user_id')); ?>"> <?php endif; ?>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Nama Institusi / Universitas</label>
                                <input type="text" name="institution" value="<?php echo e(old('institution')); ?>" placeholder="Cth: Universitas Pendidikan..." class="w-full rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3 px-4 transition-all" required>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Gelar / Jurusan</label>
                                <input type="text" name="degree" value="<?php echo e(old('degree')); ?>" placeholder="Cth: S1 Pendidikan Matematika" class="w-full rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3 px-4 transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Tahun Masuk</label>
                                <input type="number" name="start_year" value="<?php echo e(old('start_year')); ?>" placeholder="Cth: 2010" class="w-full rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3 px-4 transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Tahun Lulus</label>
                                <input type="number" name="end_year" value="<?php echo e(old('end_year')); ?>" placeholder="Cth: 2014" class="w-full rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3 px-4 transition-all">
                            </div>
                            <div class="md:col-span-2 flex items-end justify-end mt-2">
                                <button type="submit" :disabled="isSubmitting" :class="{'opacity-70 cursor-wait': isSubmitting}" class="w-full md:w-auto px-8 py-3.5 bg-elevate-dark text-white font-bold rounded-xl hover:bg-elevate-primary shadow-lg shadow-elevate-dark/30 transition-all flex justify-center items-center gap-2 active:scale-95 border border-transparent">
                                    <span x-show="!isSubmitting"><i class="ph-bold ph-plus text-lg"></i> Tambah Riwayat</span>
                                    <span x-show="isSubmitting" x-cloak><i class="ph-bold ph-spinner animate-spin text-lg"></i> Menyimpan...</span>
                                </button>
                            </div>
                        </form>

                        <div class="space-y-4">
                            <?php $__empty_1 = true; $__currentLoopData = $educations ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $edu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between p-5 bg-white border border-slate-200 rounded-2xl hover:shadow-xl hover:shadow-elevate-accent/10 hover:border-elevate-accent/50 transition-all duration-300 group gap-3 sm:gap-0">
                                    <div class="flex items-start gap-4 pr-4">
                                        <div class="px-3 py-1.5 bg-elevate-soft text-elevate-primary border border-elevate-accent/20 rounded-lg text-xs font-black mt-1 shadow-sm"><?php echo e($edu->start_year ?? '-'); ?> - <?php echo e($edu->end_year ?? 'Skrg'); ?></div>
                                        <div>
                                            <h4 class="font-bold text-lg text-elevate-dark group-hover:text-elevate-primary transition-colors"><?php echo e($edu->institution); ?></h4>
                                            <p class="text-sm font-semibold text-slate-500 mt-0.5"><?php echo e($edu->degree); ?></p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-opacity justify-end border-t border-slate-100 sm:border-0 pt-3 sm:pt-0">
                                        <button type="button" @click="openEditModal('edu', <?php echo \Illuminate\Support\Js::from($edu)->toHtml() ?>, '<?php echo e(route('portfolio.edu.update', ['id' => $edu->id, 'user_id' => request('user_id')])); ?>')" class="w-10 h-10 flex items-center justify-center text-elevate-peach bg-elevate-peach-light/30 border border-elevate-peach/30 sm:text-slate-400 sm:bg-white sm:border-slate-200 sm:hover:text-white sm:hover:bg-elevate-peach sm:hover:border-elevate-peach rounded-xl transition-all"><i class="ph-bold ph-pencil-simple text-lg"></i></button>
                                        <form action="<?php echo e(route('portfolio.edu.destroy', ['id' => $edu->id, 'user_id' => request('user_id')])); ?>" method="POST" @submit.prevent="confirmDelete($event, 'Apakah Anda ingin menghapus riwayat pendidikan ini?')">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="w-10 h-10 flex items-center justify-center text-rose-500 bg-rose-50 border border-rose-200 sm:text-slate-400 sm:bg-white sm:border-slate-200 sm:hover:text-white sm:hover:bg-rose-500 sm:hover:border-rose-500 rounded-xl transition-all"><i class="ph-bold ph-trash text-lg"></i></button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="text-center py-12 bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200">
                                    <div class="w-20 h-20 bg-elevate-peach-light/50 rounded-full flex items-center justify-center mx-auto mb-4 text-elevate-peach-dark">
                                        <i class="ph-duotone ph-folder-dashed text-4xl"></i>
                                    </div>
                                    <p class="text-elevate-dark/70 text-sm font-medium">Belum ada riwayat pendidikan ditambahkan.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            </div>
            
            
            
            
            <div x-show="editModalOpen" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center p-4 sm:p-6">
                <!-- Backdrop -->
                <div x-show="editModalOpen" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-elevate-dark/60 backdrop-blur-sm" @click="closeEditModal()"></div>

                <!-- Modal Panel -->
                <div x-show="editModalOpen" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative bg-white rounded-[2rem] shadow-2xl w-full max-w-2xl border border-slate-100 flex flex-col max-h-[90vh] overflow-hidden" 
                     @click.away="closeEditModal()">
                     
                    <!-- Modal Header -->
                    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-elevate-soft/50 shrink-0">
                        <h3 class="text-xl font-black text-elevate-dark flex items-center gap-3">
                            <div class="flex items-center justify-center w-10 h-10 bg-elevate-peach/20 rounded-xl text-elevate-peach border border-elevate-peach/50">
                                <i class="ph-bold ph-pencil-simple text-xl"></i>
                            </div> 
                            Edit Data
                        </h3>
                        <button @click="closeEditModal()" type="button" class="text-elevate-dark/60 hover:text-elevate-dark bg-elevate-soft hover:bg-elevate-peach-light p-2 rounded-full transition-colors">
                            <i class="ph-bold ph-x text-lg"></i>
                        </button>
                    </div>

                     <!-- Modal Body / Form -->
                    <form :action="editFormAction" method="POST" enctype="multipart/form-data" class="p-6 md:p-8 overflow-y-auto custom-scrollbar flex-1" 
                          x-data="{ isModalSubmitting: false, editImagePreview: null, editImagePreviews: [] }" 
                          @submit.prevent="
                              let fileInputs = $el.querySelectorAll('input[type=file]');
                              let overSize = false;
                              let totalSize = 0;
                              fileInputs.forEach(input => {
                                  let files = input.files;
                                  for(let i=0; i<files.length; i++) {
                                      if(files[i].size > 2048 * 1024) overSize = true; // max 2MB
                                      totalSize += files[i].size;
                                  }
                              });
                              if(overSize) {
                                  Swal.fire({ icon: 'warning', title: 'File Terlalu Besar', text: 'Ukuran maksimal per file adalah 2MB.', confirmButtonColor: '#2c3f61', customClass: { popup: 'rounded-[2rem] shadow-2xl' } });
                                  return;
                              }
                              if(totalSize > 8388608) { // 8MB
                                  Swal.fire({ icon: 'warning', title: 'Kapasitas Penuh', text: 'Total file melampaui batas server (8MB).', confirmButtonColor: '#2c3f61', customClass: { popup: 'rounded-[2rem] shadow-2xl' } });
                                  return;
                              }
                              isModalSubmitting = true; 
                              $el.submit();
                          ">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <?php if(request('user_id')): ?> <input type="hidden" name="user_id" value="<?php echo e(request('user_id')); ?>"> <?php endif; ?>

                        <!-- Form Edit: Pengalaman (exp) -->
                        <template x-if="editType === 'exp'">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2">Tahun</label>
                                    <input type="number" name="year" x-model="editData.year" class="w-full rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3.5 px-5 transition-all" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2">Nama Pelatihan / Sertifikasi</label>
                                    <input type="text" name="title" x-model="editData.title" class="w-full rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3.5 px-5 transition-all" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2">Penyelenggara</label>
                                    <input type="text" name="organizer" x-model="editData.organizer" class="w-full rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3.5 px-5 transition-all">
                                </div>
                                
                                
                                <div class="p-4 bg-elevate-soft/50 border border-slate-200 rounded-2xl">
                                    <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-1">Ganti Sertifikat (Opsional)</label>
                                    <p class="text-xs text-slate-500 mb-3">Biarkan kosong jika tidak ingin mengubah sertifikat.</p>
                                    <input type="file" name="certificate" accept=".pdf,image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:font-bold file:bg-elevate-soft file:text-elevate-primary hover:file:bg-elevate-primary/10 transition-all bg-white rounded-xl border border-slate-200">
                                    
                                    <template x-if="editData.certificate_path">
                                        <a :href="'<?php echo e(asset('storage')); ?>/' + editData.certificate_path" target="_blank" class="inline-flex items-center gap-1.5 mt-3 text-xs font-bold text-elevate-primary hover:text-elevate-dark transition-colors">
                                            <i class="ph-bold ph-link text-base"></i> Lihat Sertifikat Saat Ini
                                        </a>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <!-- Form Edit: Materi (mat) -->
                        <template x-if="editType === 'mat'">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2">Judul Materi</label>
                                    <input type="text" name="title" x-model="editData.title" class="w-full rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3.5 px-5 transition-all" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2">Tipe</label>
                                    <input type="text" name="type" x-model="editData.type" class="w-full rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3.5 px-5 transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2">Link URL</label>
                                    <input type="url" name="file_url" x-model="editData.file_url" class="w-full rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3.5 px-5 transition-all">
                                </div>
                                <div class="p-4 bg-elevate-soft/50 border border-slate-200 rounded-2xl">
                                    <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-1">Ganti File (Opsional)</label>
                                    <p class="text-xs text-slate-500 mb-3">Biarkan kosong jika tidak ingin mengubah file.</p>
                                    <input type="file" name="file" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:font-bold file:bg-elevate-soft file:text-elevate-primary hover:file:bg-elevate-primary/10 transition-all bg-white rounded-xl border border-slate-200">
                                </div>
                            </div>
                        </template>

                       <!-- Form Edit: Portofolio/Galeri (port) -->
                        <template x-if="editType === 'port'">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2">Judul Kegiatan</label>
                                    <input type="text" name="title" x-model="editData.title" class="w-full rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3.5 px-5 transition-all" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2">Tahun</label>
                                    <input type="text" name="year" x-model="editData.year" class="w-full rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3.5 px-5 transition-all">
                                </div>
                                <div class="p-4 bg-elevate-soft/50 border border-slate-200 rounded-2xl flex flex-col gap-3">
                                    <div class="flex-1">
                                        <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-1">Ganti Foto (Opsional, Bisa Lebih Dari Satu)</label>
                                        <p class="text-xs text-slate-500 mb-3">Pilih beberapa foto sekaligus. Biarkan kosong jika tidak ingin mengubah foto lama.</p>
                                        <input type="file" name="images[]" multiple accept="image/*" @change="editImagePreviews = Array.from($event.target.files).map(file => URL.createObjectURL(file))" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:font-bold file:bg-elevate-soft file:text-elevate-primary hover:file:bg-elevate-primary/10 transition-all bg-white rounded-xl border border-slate-200">
                                    </div>
                                    
                                    <!-- Preview Foto Baru -->
                                    <template x-if="editImagePreviews.length > 0">
                                        <div class="flex gap-3 overflow-x-auto custom-scrollbar pb-2 mt-2">
                                            <template x-for="img in editImagePreviews">
                                                <img :src="img" class="w-20 h-20 rounded-xl object-cover border border-slate-200 shrink-0 shadow-sm">
                                            </template>
                                        </div>
                                    </template>

                                    <!-- Preview Foto Lama -->
                                    <template x-if="editImagePreviews.length === 0 && editData.images_array && editData.images_array.length > 0">
                                        <div class="flex gap-3 overflow-x-auto custom-scrollbar pb-2 mt-2">
                                            <template x-for="img in editData.images_array">
                                                <img :src="'<?php echo e(asset('storage')); ?>/' + img" class="w-20 h-20 rounded-xl object-cover border border-slate-200 shrink-0 shadow-sm">
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                        
                        <!-- Form Edit: Artikel (art) -->
                        <template x-if="editType === 'art'">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2">Judul Artikel</label>
                                    <input type="text" name="title" x-model="editData.title" class="w-full rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3.5 px-5 transition-all" required>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2">Kategori</label>
                                        <input type="text" name="category" x-model="editData.category" class="w-full rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3.5 px-5 transition-all">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2">Tanggal Publikasi</label>
                                        <input type="date" name="published_at" x-model="editData.published_at" class="w-full rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3.5 px-5 transition-all text-sm">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2">Ringkasan</label>
                                    <textarea name="excerpt" x-model="editData.excerpt" rows="2" class="w-full rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-medium text-elevate-dark py-3.5 px-5 transition-all"></textarea>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2">Link URL Artikel</label>
                                    <input type="url" name="url" x-model="editData.url" class="w-full rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3.5 px-5 transition-all">
                                </div>
                                <div class="p-4 bg-elevate-soft/50 border border-slate-200 rounded-2xl flex flex-col sm:flex-row items-center gap-4">
                                    <div class="flex-1 w-full">
                                        <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-1">Ganti Thumbnail (Opsional)</label>
                                        <p class="text-xs text-slate-500 mb-3">Biarkan kosong jika tidak ingin mengubah thumbnail.</p>
                                        <input type="file" name="image" accept="image/*" @change="editImagePreview = URL.createObjectURL($event.target.files[0])" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:font-bold file:bg-elevate-soft file:text-elevate-primary hover:file:bg-elevate-primary/10 transition-all bg-white rounded-xl border border-slate-200">
                                    </div>
                                    <template x-if="editImagePreview">
                                        <img :src="editImagePreview" class="w-20 h-20 rounded-xl object-cover border border-slate-200 shadow-sm shrink-0">
                                    </template>
                                </div>
                            </div>
                        </template>

                        <!-- Form Edit: Pendidikan (edu) -->
                        <template x-if="editType === 'edu'">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2">Nama Institusi</label>
                                    <input type="text" name="institution" x-model="editData.institution" class="w-full rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3.5 px-5 transition-all" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2">Gelar / Jurusan</label>
                                    <input type="text" name="degree" x-model="editData.degree" class="w-full rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3.5 px-5 transition-all">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2">Tahun Masuk</label>
                                        <input type="number" name="start_year" x-model="editData.start_year" class="w-full rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3.5 px-5 transition-all">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2">Tahun Lulus</label>
                                        <input type="number" name="end_year" x-model="editData.end_year" class="w-full rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3.5 px-5 transition-all">
                                    </div>
                                </div>
                            </div>
                        </template>

                        <!-- Submit Button -->
                        <div class="mt-8 pt-6 border-t border-slate-100 flex justify-end gap-3 shrink-0">
                            <button type="button" @click="closeEditModal()" class="px-6 py-3.5 rounded-xl font-bold text-elevate-dark bg-white hover:bg-elevate-soft border-2 border-slate-100 transition-colors">Batal</button>
                            <button type="submit" :disabled="isModalSubmitting" :class="{'opacity-70 cursor-wait': isModalSubmitting}" class="px-8 py-3.5 rounded-xl font-bold text-white bg-elevate-dark hover:bg-elevate-primary shadow-lg shadow-elevate-dark/30 transition-all flex items-center gap-2 border border-transparent active:scale-95">
                                <span x-show="!isModalSubmitting"><i class="ph-bold ph-check text-lg"></i> Simpan Perubahan</span>
                                <span x-show="isModalSubmitting" x-cloak><i class="ph-bold ph-spinner animate-spin text-lg"></i> Menyimpan...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
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
<?php endif; ?><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/portfolio/index.blade.php ENDPATH**/ ?>