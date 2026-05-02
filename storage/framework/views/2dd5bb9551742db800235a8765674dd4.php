<!-- PROFIL SEKOLAH -->
    <div id="profil" class="py-24 bg-white dark:bg-slate-900 relative overflow-hidden border-y border-slate-100 dark:border-slate-800 transition-colors duration-300">
        <div class="absolute right-0 top-0 opacity-5 dark:opacity-10 pointer-events-none">
            <svg width="400" height="400" fill="none" viewBox="0 0 200 200">
                <defs><pattern id="dots" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse"><circle cx="2" cy="2" r="2" class="text-slate-900 dark:text-white" fill="currentColor"></circle></pattern></defs>
                <rect width="200" height="200" fill="url(#dots)"></rect>
            </svg>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div class="space-y-8" data-aos="fade-right">
                    <div class="space-y-2">
                        <span class="px-3 py-1 bg-elevate-accent/10 dark:bg-elevate-accent/20 text-elevate-primary dark:text-elevate-accent rounded-full text-xs font-bold uppercase tracking-widest border border-elevate-accent/20 dark:border-elevate-accent/30">Tentang Kami</span>
                        <h2 class="text-3xl md:text-5xl font-extrabold text-slate-900 dark:text-white leading-tight">Mewujudkan Generasi <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-elevate-accent to-elevate-primary dark:from-elevate-accent dark:to-elevate-primary">Cerdas & Berkarakter</span></h2>
                    </div>
                    <p class="text-lg text-slate-600 dark:text-slate-400 leading-relaxed text-justify lg:text-left">
                        SMP Negeri 3 Lakbok berkomitmen untuk memberikan layanan pendidikan terbaik yang mengintegrasikan kecerdasan akademik dengan nilai-nilai karakter luhur. Kami hadir untuk mencetak pemimpin masa depan yang kompetitif dan berakhlak mulia.
                    </p>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        <div class="p-5 bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 text-center hover:border-elevate-accent/50 dark:hover:border-elevate-accent/50 hover:shadow-xl hover:shadow-elevate-accent/10 hover:-translate-y-1 transition-all duration-300 group">
                            <p class="text-3xl font-black text-slate-800 dark:text-white group-hover:text-elevate-primary dark:group-hover:text-elevate-accent transition-colors"><?php echo e($schoolStats['siswa'] ?? '-'); ?></p>
                            <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase mt-1 tracking-wider">Siswa</p>
                        </div>
                        <div class="p-5 bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 text-center hover:border-elevate-primary/50 dark:hover:border-elevate-primary/50 hover:shadow-xl hover:shadow-elevate-primary/10 hover:-translate-y-1 transition-all duration-300 group">
                            <p class="text-3xl font-black text-slate-800 dark:text-white group-hover:text-elevate-primary dark:group-hover:text-elevate-accent transition-colors"><?php echo e($schoolStats['guru'] ?? '-'); ?></p>
                            <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase mt-1 tracking-wider">Guru</p>
                        </div>
                        <div class="p-5 bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 text-center hover:border-elevate-accent/50 dark:hover:border-elevate-accent/50 hover:shadow-xl hover:shadow-elevate-accent/10 hover:-translate-y-1 transition-all duration-300 group">
                            <p class="text-3xl font-black text-slate-800 dark:text-white group-hover:text-elevate-primary dark:group-hover:text-elevate-accent transition-colors"><?php echo e($schoolStats['rombel'] ?? '-'); ?></p>
                            <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase mt-1 tracking-wider">Rombel</p>
                        </div>
                        <div class="p-5 bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 text-center hover:border-elevate-primary/50 dark:hover:border-elevate-primary/50 hover:shadow-xl hover:shadow-elevate-primary/10 hover:-translate-y-1 transition-all duration-300 group">
                            <p class="text-3xl font-black text-slate-800 dark:text-white group-hover:text-elevate-primary dark:group-hover:text-elevate-accent transition-colors"><?php echo e($schoolStats['materi'] ?? 0); ?></p>
                            <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase mt-1 tracking-wider">Materi Digital</p>
                        </div>
                        <div class="p-5 bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 text-center hover:border-elevate-accent/50 dark:hover:border-elevate-accent/50 hover:shadow-xl hover:shadow-elevate-accent/10 hover:-translate-y-1 transition-all duration-300 group sm:col-span-2">
                            <p class="text-3xl font-black text-slate-800 dark:text-white group-hover:text-elevate-primary dark:group-hover:text-elevate-accent transition-colors"><?php echo e($schoolStats['tugas'] ?? 0); ?></p>
                            <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase mt-1 tracking-wider">Tugas & Kuis Online</p>
                        </div>
                    </div>
                </div>
                
                <div class="relative group" data-aos="fade-left"
                    x-data="{ 
                        currentSlide: 0, 
                        slides: [
                            '<?php echo e(asset('images/netila.jpg')); ?>', 
                            '<?php echo e(asset('images/hadir.jpg')); ?>', 
                            '<?php echo e(asset('images/digital1.jpg')); ?>', 
                            '<?php echo e(asset('images/digital2.jpg')); ?>', 
                            '<?php echo e(asset('images/kka.png')); ?>', 
                            '<?php echo e(asset('images/religi.jpg')); ?>'
                        ],
                        init() { setInterval(() => { this.currentSlide = (this.currentSlide + 1) % this.slides.length; }, 4000); }
                    }" x-init="init()">
                    
                    <div class="absolute -inset-4 bg-gradient-to-r from-elevate-primary to-elevate-accent rounded-[2.5rem] opacity-20 blur-lg group-hover:opacity-40 transition duration-500"></div>
                    <div class="bg-slate-200 rounded-[2rem] overflow-hidden shadow-2xl relative aspect-video z-10">
                        <template x-for="(slide, index) in slides" :key="index">
                            <img :src="slide" x-show="currentSlide === index" x-transition.opacity.duration.1000ms class="absolute inset-0 w-full h-full object-cover" alt="Galeri">
                        </template>
                        <div class="absolute inset-0 bg-gradient-to-tr from-elevate-dark/90 to-transparent flex items-center justify-center z-20 pointer-events-none"></div>
                    </div>
                </div>
            </div>
        </div>
    </div><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/landing/profile.blade.php ENDPATH**/ ?>