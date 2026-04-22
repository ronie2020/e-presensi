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
                        <!-- PERBAIKAN: Badge dan teks diubah menjadi nuansa Cyan/Blue -->
                        <span class="px-3 py-1 bg-cyan-50 dark:bg-cyan-900/30 text-cyan-600 dark:text-cyan-400 rounded-full text-xs font-bold uppercase tracking-widest border border-cyan-100 dark:border-cyan-500/20">Tentang Kami</span>
                        <h2 class="text-3xl md:text-5xl font-extrabold text-slate-900 dark:text-white leading-tight">Mewujudkan Generasi <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-blue-600 dark:from-cyan-400 dark:to-blue-400">Cerdas & Berkarakter</span></h2>
                    </div>
                    <p class="text-lg text-slate-600 dark:text-slate-400 leading-relaxed text-justify lg:text-left">
                        SMP Negeri 3 Lakbok berkomitmen untuk memberikan layanan pendidikan terbaik yang mengintegrasikan kecerdasan akademik dengan nilai-nilai karakter luhur. Kami hadir untuk mencetak pemimpin masa depan yang kompetitif dan berakhlak mulia.
                    </p>
                    
                    <!-- PERBAIKAN: Menyatukan tema kartu statistik menjadi seragam (Hover Cyan/Blue) -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        <div class="p-5 bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 text-center hover:border-cyan-300 dark:hover:border-cyan-500 hover:shadow-xl hover:shadow-cyan-500/10 hover:-translate-y-1 transition-all duration-300 group">
                            <p class="text-3xl font-black text-slate-800 dark:text-white group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors">{{ $schoolStats['siswa'] ?? '-' }}</p>
                            <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase mt-1 tracking-wider">Siswa</p>
                        </div>
                        <div class="p-5 bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 text-center hover:border-blue-300 dark:hover:border-blue-500 hover:shadow-xl hover:shadow-blue-500/10 hover:-translate-y-1 transition-all duration-300 group">
                            <p class="text-3xl font-black text-slate-800 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">{{ $schoolStats['guru'] ?? '-' }}</p>
                            <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase mt-1 tracking-wider">Guru</p>
                        </div>
                        <div class="p-5 bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 text-center hover:border-cyan-300 dark:hover:border-cyan-500 hover:shadow-xl hover:shadow-cyan-500/10 hover:-translate-y-1 transition-all duration-300 group">
                            <p class="text-3xl font-black text-slate-800 dark:text-white group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors">{{ $schoolStats['rombel'] ?? '-' }}</p>
                            <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase mt-1 tracking-wider">Rombel</p>
                        </div>
                        <div class="p-5 bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 text-center hover:border-blue-300 dark:hover:border-blue-500 hover:shadow-xl hover:shadow-blue-500/10 hover:-translate-y-1 transition-all duration-300 group">
                            <p class="text-3xl font-black text-slate-800 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">{{ $schoolStats['materi'] ?? 0 }}</p>
                            <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase mt-1 tracking-wider">Materi Digital</p>
                        </div>
                        <div class="p-5 bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 text-center hover:border-cyan-300 dark:hover:border-cyan-500 hover:shadow-xl hover:shadow-cyan-500/10 hover:-translate-y-1 transition-all duration-300 group sm:col-span-2">
                            <p class="text-3xl font-black text-slate-800 dark:text-white group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors">{{ $schoolStats['tugas'] ?? 0 }}</p>
                            <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase mt-1 tracking-wider">Tugas & Kuis Online</p>
                        </div>
                    </div>
                </div>
                
                <div class="relative group" data-aos="fade-left"
                    x-data="{ 
                        currentSlide: 0, 
                        slides: [
                            '{{ asset('images/netila.jpg') }}', 
                            '{{ asset('images/hadir.jpg') }}', 
                            '{{ asset('images/digital1.jpg') }}', 
                            '{{ asset('images/digital2.jpg') }}', 
                            '{{ asset('images/kka.png') }}', 
                            '{{ asset('images/religi.jpg') }}'
                        ],
                        init() { setInterval(() => { this.currentSlide = (this.currentSlide + 1) % this.slides.length; }, 4000); }
                    }" x-init="init()">
                    
                    <div class="absolute -inset-4 bg-gradient-to-r from-blue-600 to-cyan-500 rounded-[2.5rem] opacity-20 blur-lg group-hover:opacity-40 transition duration-500"></div>
                    <div class="bg-slate-200 rounded-[2rem] overflow-hidden shadow-2xl relative aspect-video z-10">
                        <template x-for="(slide, index) in slides" :key="index">
                            <img :src="slide" x-show="currentSlide === index" x-transition.opacity.duration.1000ms class="absolute inset-0 w-full h-full object-cover" alt="Galeri">
                        </template>
                        <div class="absolute inset-0 bg-gradient-to-tr from-blue-900/90 to-transparent flex items-center justify-center z-20 pointer-events-none"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>