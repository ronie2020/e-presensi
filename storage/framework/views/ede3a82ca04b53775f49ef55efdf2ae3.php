<?php $__env->startSection('title', 'Direktori Pengajar - ' . config('app.name', 'SMP Negeri 3 Lakbok')); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        /* Mencegah elemen berkedip saat AlpineJS belum siap */
        [x-cloak] { display: none !important; }

        /* Animasi Custom Khusus Halaman Ini */
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
        
        .animate-blob { animation: blob 7s infinite; }
        @keyframes blob { 
            0% { transform: translate(0px, 0px) scale(1); } 
            33% { transform: translate(30px, -50px) scale(1.1); } 
            66% { transform: translate(-20px, 20px) scale(0.9); } 
            100% { transform: translate(0px, 0px) scale(1); } 
        }
        
        /* Custom Scrollbar untuk Modal */
        .modal-scroll::-webkit-scrollbar { width: 6px; }
        .modal-scroll::-webkit-scrollbar-track { background: #f1f5f9; }
        .modal-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    
    <div x-data="{ 
          modalOpen: false, 
          teacher: {},
          linkCopied: false,
          isSearching: false, // <-- IDE PENGEMBANGAN 4: State untuk Skeleton Loading
          
          openModal(data) {
              this.teacher = data;
              this.modalOpen = true;
              this.linkCopied = false;
              document.body.style.overflow = 'hidden'; // Kunci scroll body saat modal terbuka
          },
          closeModal() {
              this.modalOpen = false;
              setTimeout(() => { this.teacher = {} }, 300);
              document.body.style.overflow = 'auto'; 
          },
          formatWA(phone) {
              if(!phone) return '';
              let number = phone.replace(/[^0-9]/g, '');
              if(number.startsWith('0')) number = '62' + number.substr(1);
              return 'https://wa.me/' + number;
          },
          // Format Pintar Sosial Media
          formatSocialUrl(platform, value) {
              if (!value) return '';
              value = value.trim();
              if (value.startsWith('http')) return value;
              // Hilangkan karakter @ jika user cuma ketik username
              if (value.startsWith('@')) value = value.substring(1);
              
              if (platform === 'ig') return 'https://instagram.com/' + value;
              if (platform === 'fb') return 'https://facebook.com/' + value;
              if (platform === 'tiktok') return 'https://tiktok.com/@' + value;
              return value;
          },
          // Fungsi Salin Tautan (Copy to Clipboard)
           copyProfileLink() {
              if(!this.teacher.profile_url) return;
              
              const el = document.createElement('textarea');
              el.value = this.teacher.profile_url;
              document.body.appendChild(el);
              el.select();
              document.execCommand('copy');
              document.body.removeChild(el);
              
              this.linkCopied = true;
              setTimeout(() => { this.linkCopied = false; }, 3000);
          }
      }">

        <!-- HEADER SECTION (Tema Disamakan dengan Hero: Gradien Cyan-Blue) -->
        <div class="pt-32 pb-32 relative overflow-hidden -mt-24 bg-gradient-to-br from-cyan-500 via-blue-600 to-blue-900">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-[0.05] mix-blend-overlay"></div>

            <!-- Animated Blobs (Glow Putih/Cyan & Deep Blue) -->
            <div class="absolute top-0 left-0 w-full md:w-[60%] h-full bg-cyan-300/20 rounded-full blur-[100px] -translate-x-1/4 -translate-y-1/4 pointer-events-none animate-blob"></div>
            <div class="absolute bottom-0 right-0 w-full md:w-[50%] h-[80%] bg-indigo-900/30 rounded-full blur-[120px] translate-x-1/4 translate-y-1/4 pointer-events-none animate-blob" style="animation-delay: 2s;"></div>
            
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center animate-enter">
                
                
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 border border-white/20 text-cyan-100 text-xs font-bold uppercase tracking-wider mb-6 backdrop-blur-md shadow-sm">
                    <span class="relative flex h-2 w-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-300 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-cyan-400"></span>
                    </span>
                    SDM Berkualitas
                </span>
                
                <h1 class="text-4xl md:text-6xl font-black text-white mb-6 tracking-tight">Direktori Tenaga Pendidik</h1>
                <p class="text-blue-50 text-lg max-w-2xl mx-auto mb-12 leading-relaxed font-medium opacity-90">
                    Profil profesional guru dan staf pengajar SMP Negeri 3 Lakbok.
                </p>

                <!-- FORM PENCARIAN & FILTER -->
                <form action="<?php echo e(route('teachers.index')); ?>" method="GET" class="max-w-2xl mx-auto relative group" @submit="isSearching = true">
                    <div class="absolute -inset-1 bg-gradient-to-r from-cyan-400 to-blue-500 rounded-full blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200"></div>
                    
                    <div class="relative flex flex-col sm:flex-row bg-white/95 backdrop-blur-xl rounded-[2rem] sm:rounded-full shadow-2xl transition-transform focus-within:scale-[1.02] border border-white/20">
                        
                        <!-- Dropdown Kategori -->
                        <div class="relative w-full sm:w-2/5 border-b sm:border-b-0 sm:border-r border-slate-200">
                            <div class="absolute left-6 top-1/2 -translate-y-1/2 text-slate-400">
                                <i class="ph-bold ph-funnel text-xl"></i>
                            </div>
                            <select name="kategori" class="w-full pl-14 pr-10 py-4 bg-transparent border-0 focus:ring-0 text-sm font-bold text-slate-600 cursor-pointer appearance-none rounded-t-[2rem] sm:rounded-l-full sm:rounded-tr-none">
                                <option value="">Semua Peran</option>
                                <option value="guru" <?php echo e(request('kategori') == 'guru' ? 'selected' : ''); ?>>Guru / Pendidik</option>
                                <option value="staf" <?php echo e(request('kategori') == 'staf' ? 'selected' : ''); ?>>Staf Tata Usaha</option>
                            </select>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                                <i class="ph-bold ph-caret-down text-lg"></i>
                            </div>
                        </div>
                        
                        <!-- Input Pencarian -->
                        <div class="relative w-full sm:w-3/5">
                            <input type="text" name="q" value="<?php echo e(request('q')); ?>" placeholder="Ketik nama atau mapel..." class="w-full pl-6 pr-24 py-4 bg-transparent border-0 focus:ring-0 text-sm font-bold placeholder-slate-400 text-slate-800 rounded-b-[2rem] sm:rounded-r-full sm:rounded-bl-none">
                            
                            <!-- Tombol Reset & Submit -->
                            <div class="absolute right-3 top-1/2 -translate-y-1/2 flex items-center gap-1.5">
                                <?php if(request('q') || request('kategori')): ?>
                                    <a href="<?php echo e(route('teachers.index')); ?>" class="w-9 h-9 flex items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-red-100 hover:text-red-600 transition-colors" title="Reset Filter"><i class="ph-bold ph-x"></i></a>
                                <?php endif; ?>
                                <button type="submit" class="w-10 h-10 flex items-center justify-center bg-cyan-500 rounded-full text-white hover:bg-cyan-600 transition shadow-lg shadow-cyan-500/30 hover:scale-110 active:scale-95">
                                    <i class="ph-bold ph-magnifying-glass text-lg"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- MAIN CONTENT -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-20 relative z-20 pb-20">
            
            <!-- IDE PENGEMBANGAN 4: SKELETON LOADING -->
            <div x-show="isSearching" x-cloak class="grid grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
                <?php for($i = 1; $i <= 8; $i++): ?>
                    <div class="bg-white rounded-[2rem] overflow-hidden shadow-sm border border-slate-100 flex flex-col h-full relative animate-pulse">
                        <div class="aspect-[4/5] sm:aspect-square bg-slate-200/60 relative overflow-hidden"></div>
                        <div class="p-5 text-center flex-1 flex flex-col relative bg-white">
                            <div class="absolute -top-4 left-0 right-0 flex justify-center px-4">
                                <div class="w-20 h-6 bg-slate-200 rounded-full shadow-sm border-2 border-white"></div>
                            </div>
                            <div class="mt-4 mb-2 flex flex-col items-center gap-2">
                                <div class="w-3/4 h-5 bg-slate-200 rounded-md"></div>
                                <div class="w-1/2 h-3 bg-slate-100 rounded-md mt-1"></div>
                            </div>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>

            <!-- KONTEN ASLI (Akan disembunyikan saat sedang loading/submit form) -->
            <div x-show="!isSearching" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="grid grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
                <?php $__empty_1 = true; $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        // Logika untuk mendecode Role yang berbentuk JSON string ["Guru", "Admin"]
                        $displayRole = $teacher->position;
                        if (empty($displayRole)) {
                            $decodedRoles = is_string($teacher->role) ? json_decode($teacher->role, true) : $teacher->role;
                            $displayRole = is_array($decodedRoles) ? implode(', ', $decodedRoles) : $teacher->role;
                        }
                    ?>
                    <div @click="openModal({
                            name: '<?php echo e(addslashes($teacher->name)); ?>',
                            nip: '<?php echo e($teacher->nip ?? '-'); ?>',
                            pangkat: '<?php echo e($teacher->pangkat ?? '-'); ?>',
                            position: '<?php echo e(addslashes($displayRole)); ?>',
                            bio: '<?php echo e(addslashes($teacher->bio ?? 'Belum ada pesan & kesan.')); ?>',
                            keahlian: '<?php echo e(addslashes($teacher->keahlian ?? '')); ?>',
                            hobi: '<?php echo e(addslashes($teacher->hobi ?? '')); ?>',
                            phone: '<?php echo e($teacher->phone); ?>',
                            instagram: '<?php echo e($teacher->instagram); ?>',
                            tiktok: '<?php echo e($teacher->tiktok); ?>',
                            facebook: '<?php echo e($teacher->facebook); ?>',
                            photo_url: '<?php echo e($teacher->photo_path ? asset('storage/' . $teacher->photo_path) : ''); ?>',
                            profile_url: '<?php echo e(route('teachers.show', $teacher->id)); ?>',
                            cv_url: '<?php echo e(route('teachers.cv', $teacher->id)); ?>'
                         })"
                         class="animate-enter group bg-white rounded-[2rem] overflow-hidden shadow-sm hover:shadow-2xl hover:shadow-blue-900/10 hover:-translate-y-2 transition-all duration-500 border border-slate-100 flex flex-col h-full relative cursor-pointer"
                         style="animation-delay: <?php echo e(($index % 4) * 100); ?>ms">
                        
                        <!-- Foto Guru -->
                        <div class="aspect-[4/5] sm:aspect-square bg-slate-200 relative overflow-hidden">
                            <?php if($teacher->photo_path): ?>
                                <img src="<?php echo e(asset('storage/' . $teacher->photo_path)); ?>" alt="<?php echo e($teacher->name); ?>" loading="lazy" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 filter grayscale group-hover:grayscale-0" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="hidden w-full h-full flex-col items-center justify-center bg-gradient-to-br from-cyan-50 to-blue-100 text-slate-400">
                                    <span class="text-4xl font-bold opacity-30"><?php echo e(substr($teacher->name, 0, 2)); ?></span>
                                </div>
                            <?php else: ?>
                                <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-cyan-50 to-blue-100 text-cyan-500">
                                    <span class="text-6xl sm:text-7xl font-black opacity-30 select-none uppercase group-hover:scale-110 transition-transform duration-500"><?php echo e(substr($teacher->name, 0, 1)); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Overlay Text (Call to Action) -->
                            <div class="absolute inset-0 bg-cyan-900/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                <span class="bg-white/95 backdrop-blur text-cyan-700 text-xs font-bold px-4 py-2 rounded-full shadow-lg transform translate-y-4 group-hover:translate-y-0 transition-transform">Lihat Profil Lengkap</span>
                            </div>
                        </div>

                        <!-- Info Singkat -->
                        <div class="p-5 text-center flex-1 flex flex-col relative bg-white">
                            <div class="absolute -top-4 left-0 right-0 flex justify-center px-4">
                                <span class="inline-block px-4 py-1.5 bg-gradient-to-r from-cyan-500 to-blue-600 text-white text-[10px] font-black uppercase tracking-wider rounded-full shadow-lg border-2 border-white transform group-hover:scale-105 transition-transform truncate max-w-full" title="<?php echo e($displayRole); ?>">
                                    <?php echo e($displayRole); ?>

                                </span>
                            </div>
                            <div class="mt-4 mb-2">
                                <h3 class="text-base sm:text-lg font-bold text-slate-800 leading-tight group-hover:text-cyan-600 transition-colors line-clamp-1"><?php echo e($teacher->name); ?></h3>
                                <?php if($teacher->nip): ?>
                                    <p class="text-[10px] sm:text-xs text-slate-400 font-mono mt-1 font-medium bg-slate-50 inline-block px-2 py-0.5 rounded"><?php echo e($teacher->nip); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                 <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-span-2 lg:col-span-4 py-24 text-center animate-enter">
                        <div class="inline-flex bg-slate-100 p-6 rounded-full mb-6 text-slate-300 ring-8 ring-slate-50"><i class="ph-duotone ph-magnifying-glass text-5xl"></i></div>
                        <h3 class="text-xl font-bold text-slate-800 mb-2">Data Tidak Ditemukan</h3>
                        <p class="text-slate-500 text-sm max-w-md mx-auto mb-6">Maaf, kami tidak dapat menemukan data guru dengan kata kunci atau filter tersebut.</p>
                        <?php if(request('q') || request('kategori')): ?>
                            <a href="<?php echo e(route('teachers.index')); ?>" class="inline-flex items-center justify-center px-6 py-2.5 bg-cyan-500 text-white text-sm font-bold rounded-full hover:bg-cyan-600 transition shadow-lg shadow-cyan-500/30 gap-2"><i class="ph-bold ph-arrow-counter-clockwise"></i> Reset Pencarian & Filter</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div x-show="!isSearching" class="mt-16 px-4 animate-enter"><?php echo e($teachers->withQueryString()->links()); ?></div>
        </div>

        <!-- MODAL DETAIL GURU (POPUP) -->
        <div x-show="modalOpen" x-cloak style="display: none;" class="fixed inset-0 z-[99999] flex items-start justify-center p-4 pt-24 sm:p-6 sm:pt-28 pb-4 sm:pb-8" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <!-- Backdrop -->
            <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 bg-slate-900/70 backdrop-blur-sm transition-opacity" @click="closeModal()"></div>

            <!-- Wrapper Modal -->
            <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative flex flex-col w-full max-w-4xl max-h-[calc(100vh-7rem)] sm:max-h-[calc(100vh-9rem)] bg-white rounded-[2.5rem] text-left shadow-2xl border border-slate-200 overflow-hidden transform transition-all" @click.away="closeModal()">
                
                <button @click="closeModal()" class="absolute top-4 right-4 z-20 bg-white/90 backdrop-blur p-2.5 rounded-full text-slate-400 hover:text-red-500 hover:bg-red-50 transition-all shadow-sm border border-slate-100"><i class="ph-bold ph-x text-xl"></i></button>

                <!-- Area Konten yang Bisa di-Scroll secara internal -->
                <div class="flex-1 overflow-y-auto modal-scroll w-full bg-white">
                    <div class="flex flex-col md:flex-row min-h-full">
                        
                        <!-- KIRI: FOTO & IDENTITAS UTAMA -->
                        <div class="md:w-5/12 bg-slate-50/80 p-6 md:p-10 flex flex-col items-center justify-start text-center relative border-b md:border-b-0 md:border-r border-slate-100 shrink-0">
                            <!-- Foto Profil Besar -->
                            <div class="w-48 h-48 rounded-full p-1.5 bg-white border-2 border-dashed border-blue-200 shadow-xl mb-6 relative group">
                               <div class="w-full h-full rounded-full overflow-hidden relative">
                                    <template x-if="teacher.photo_url">
                                        <img :src="teacher.photo_url" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700" alt="Foto Guru">
                                    </template>
                                    <template x-if="!teacher.photo_url">
                                        <div class="w-full h-full bg-gradient-to-br from-cyan-100 via-blue-50 to-indigo-100 flex items-center justify-center text-cyan-600 text-6xl font-black uppercase select-none shadow-inner">
                                            <span x-text="teacher.name ? teacher.name.substring(0,2) : 'GU'"></span>
                                        </div>
                                    </template>
                                </div>
                                <div class="absolute bottom-3 right-3 bg-emerald-500 border-4 border-white w-7 h-7 rounded-full shadow-md" title="Status Aktif"></div>
                            </div>

                            <!-- Nama & Jabatan -->
                            <h2 class="text-2xl font-black text-slate-800 leading-tight mb-2" x-text="teacher.name"></h2>
                            <div class="mb-6">
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-cyan-50 text-cyan-700 text-xs font-bold uppercase tracking-wider border border-cyan-200">
                                    <i class="ph-fill ph-chalkboard-teacher mr-1.5"></i>
                                    <span x-text="teacher.position || 'Tenaga Pendidik'"></span>
                                </span>
                            </div>
                            
                             <!-- Tombol Portofolio -->
                            <a :href="teacher.profile_url" class="w-full mb-3 py-3.5 bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-600 hover:to-blue-700 text-white rounded-2xl font-bold shadow-lg shadow-cyan-500/20 transition-all flex items-center justify-center gap-2 group hover:-translate-y-1">
                                <i class="ph-bold ph-user-circle text-xl group-hover:scale-110 transition-transform"></i>
                                Lihat Portofolio & Karya
                            </a>

                            <!-- Tombol Kontak WA -->
                            <template x-if="teacher.phone">
                                <a :href="formatWA(teacher.phone)" target="_blank" class="w-full py-3.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-2xl font-bold shadow-lg shadow-emerald-500/20 transition-all flex items-center justify-center gap-2 group hover:-translate-y-1">
                                    <i class="ph-fill ph-whatsapp-logo text-xl group-hover:scale-110 transition-transform"></i>
                                    Hubungi via WhatsApp
                                </a>
                            </template>
                            <template x-if="!teacher.phone">
                                <button disabled class="w-full py-3.5 bg-slate-200 text-slate-400 rounded-2xl font-bold cursor-not-allowed flex items-center justify-center gap-2">
                                    <i class="ph-slash ph-phone-slash text-xl"></i> Kontak Tidak Tersedia
                                </button>
                            </template>
                        </div>

                        <!-- KANAN: DETAIL INFO -->
                        <div class="md:w-7/12 p-6 md:p-10 bg-white">
                            
                             <!-- Info Akademik -->
                            <div class="mb-8">
                                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                    <i class="ph-bold ph-info text-cyan-500"></i> Informasi Akademik
                                </h3>
                                <div class="grid grid-cols-1 gap-4">
                                    <div class="bg-cyan-50/50 p-4 rounded-2xl border border-cyan-100 flex items-start gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-cyan-100 text-cyan-600 flex items-center justify-center shrink-0">
                                            <i class="ph-duotone ph-briefcase text-xl"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-slate-500 font-bold uppercase mb-0.5">Jabatan / Mapel</p>
                                            <p class="font-bold text-slate-800 text-lg" x-text="teacher.position || 'Tenaga Pendidik'"></p>
                                        </div>
                                    </div>
                                    <div class="bg-slate-50/50 p-4 rounded-2xl border border-slate-100 flex items-start gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-slate-200 text-slate-600 flex items-center justify-center shrink-0">
                                            <i class="ph-duotone ph-identification-card text-xl"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-slate-500 font-bold uppercase mb-0.5">NIP & Pangkat</p>
                                            
                                            <template x-if="teacher.nip && teacher.nip !== '-'">
                                                <p class="font-mono font-bold text-slate-800 text-sm" x-text="teacher.nip"></p>
                                            </template>
                                            <template x-if="!teacher.nip || teacher.nip === '-'">
                                                <p class="text-slate-400 text-sm italic">Belum ada NIP</p>
                                            </template>
                                            
                                            <template x-if="teacher.pangkat && teacher.pangkat !== '-'">
                                                <span class="inline-block mt-1 px-2.5 py-0.5 bg-cyan-100 border border-cyan-200 text-cyan-700 text-[10px] font-bold rounded-lg" x-text="teacher.pangkat"></span>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pesan & Kesan (Bio) -->
                            <div class="mb-8">
                                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                    <i class="ph-bold ph-quotes text-blue-500"></i> Pesan & Kesan
                                </h3>
                                <div class="relative bg-gradient-to-br from-blue-50 to-white p-6 rounded-[1.5rem] border border-blue-100 shadow-sm">
                                    <i class="ph-fill ph-quotes text-4xl text-blue-200/50 absolute top-4 right-4"></i>
                                    <p class="text-slate-600 text-sm italic leading-relaxed relative z-10" x-text="teacher.bio || 'Belum ada pesan dan kesan yang ditambahkan.'"></p>
                                </div>
                            </div>

                            <!-- Keahlian & Hobi -->
                            <div class="mb-8 grid grid-cols-1 sm:grid-cols-2 gap-6" x-show="teacher.keahlian || teacher.hobi">
                                <div x-show="teacher.keahlian">
                                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                                        <i class="ph-bold ph-star text-amber-500"></i> Keahlian
                                    </h3>
                                    <div class="flex flex-wrap gap-2">
                                        <template x-for="item in (teacher.keahlian || '').split(',')" :key="item">
                                            <span class="px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200 rounded-lg text-[10px] font-bold uppercase tracking-wider" x-text="item.trim()" x-show="item.trim() !== ''"></span>
                                        </template>
                                    </div>
                                </div>
                                <div x-show="teacher.hobi">
                                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                                        <i class="ph-bold ph-heart text-rose-500"></i> Hobi
                                    </h3>
                                    <div class="flex flex-wrap gap-2">
                                        <template x-for="item in (teacher.hobi || '').split(',')" :key="item">
                                            <span class="px-2.5 py-1 bg-rose-50 text-rose-700 border border-rose-200 rounded-lg text-[10px] font-bold uppercase tracking-wider" x-text="item.trim()" x-show="item.trim() !== ''"></span>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-- Sosial Media, Unduh CV, & Copy Link -->
                            <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4 mt-8 pt-6 border-t border-slate-100">
                                
                                <div class="flex items-center gap-2 flex-wrap">
                                    <!-- Sosial Media (Menggunakan formatSocialUrl) -->
                                    <template x-if="teacher.instagram">
                                        <a :href="formatSocialUrl('ig', teacher.instagram)" target="_blank" class="w-10 h-10 rounded-full bg-slate-100 hover:bg-pink-500 hover:text-white flex items-center justify-center text-slate-500 transition-all hover:-translate-y-1 hover:shadow-md" title="Instagram"><i class="ph-fill ph-instagram-logo text-xl"></i></a>
                                    </template>
                                    <template x-if="teacher.facebook">
                                        <a :href="formatSocialUrl('fb', teacher.facebook)" target="_blank" class="w-10 h-10 rounded-full bg-slate-100 hover:bg-blue-600 hover:text-white flex items-center justify-center text-slate-500 transition-all hover:-translate-y-1 hover:shadow-md" title="Facebook"><i class="ph-fill ph-facebook-logo text-xl"></i></a>
                                    </template>
                                    <template x-if="teacher.tiktok">
                                        <a :href="formatSocialUrl('tiktok', teacher.tiktok)" target="_blank" class="w-10 h-10 rounded-full bg-slate-100 hover:bg-slate-800 hover:text-white flex items-center justify-center text-slate-500 transition-all hover:-translate-y-1 hover:shadow-md" title="TikTok"><i class="ph-fill ph-tiktok-logo text-xl"></i></a>
                                    </template>

                                    <!-- Tombol Bagikan Link Profil -->
                                    <button @click="copyProfileLink()" class="ml-2 px-3 py-2 rounded-xl border border-slate-200 text-slate-500 font-bold text-xs hover:bg-slate-50 transition-colors flex items-center gap-1.5 focus:outline-none" :class="linkCopied ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : ''">
                                        <i class="ph-bold text-base" :class="linkCopied ? 'ph-check-circle' : 'ph-link'"></i>
                                        <span x-text="linkCopied ? 'Disalin!' : 'Bagikan'"></span>
                                    </button>
                                </div>

                                <a :href="teacher.cv_url" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-slate-100 hover:bg-blue-50 hover:text-blue-600 text-slate-600 font-bold rounded-full transition-all">
                                    <i class="ph-bold ph-download-simple text-lg"></i> Unduh CV (PDF)
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/teachers.blade.php ENDPATH**/ ?>