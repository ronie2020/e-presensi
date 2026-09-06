<!-- JADWAL PELAJARAN HARI INI -->
<section id="jadwal" class="py-20 bg-slate-50 dark:bg-slate-900 relative overflow-hidden" 
    x-data="{ 
        activeTab: 'Senin',
        selectedClass: '{{ $scheduleClasses[0] ?? '' }}',
        days: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'],
        classes: {{ Js::from($scheduleClasses ?? []) }},
        schedules: {{ Js::from($publicSchedules ?? []) }},
        get currentItems() {
            return (this.schedules[this.activeTab] && this.schedules[this.activeTab][this.selectedClass])
                ? this.schedules[this.activeTab][this.selectedClass]
                : [];
        }
    }"
    x-init="
        const today = new Date().getDay();
        const daysMap = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        if (today > 0 && today < 6) { // Senin sampai Jumat
            activeTab = daysMap[today];
        } else { // Sabtu dan Minggu default ke Senin
            activeTab = 'Senin';
        }
    "
>
    <div class="container mx-auto px-4 sm:px-6 relative z-10 max-w-7xl">
        
        <!-- Header -->
        <div class="text-center max-w-2xl mx-auto mb-12" x-data="{ shown: false }" x-intersect="shown = true">
            <span class="inline-flex items-center rounded-full bg-elevate-accent/10 px-3 py-1.5 text-xs font-black uppercase tracking-widest text-elevate-primary ring-1 ring-inset ring-elevate-accent/30 mb-4 transition-all duration-700 transform" :class="shown ? 'translate-y-0 opacity-100' : 'translate-y-4 opacity-0'">
                <i class="ph-bold ph-calendar-check mr-2"></i> Kegiatan Harian
            </span>
            <h2 class="text-3xl md:text-4xl font-black text-elevate-dark dark:text-slate-100 tracking-tight mb-4 transition-all duration-700 delay-100 transform" :class="shown ? 'translate-y-0 opacity-100' : 'translate-y-4 opacity-0'">
                Jadwal <span class="text-transparent bg-clip-text bg-gradient-to-r from-elevate-primary to-elevate-accent">Pelajaran & Kegiatan</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 font-medium transition-all duration-700 delay-200 transform" :class="shown ? 'translate-y-0 opacity-100' : 'translate-y-4 opacity-0'">
                Informasi jadwal pembelajaran dan kegiatan ekstrakurikuler siswa secara umum.
            </p>
        </div>

        <!-- Tab Navigasi Hari + Filter Kelas -->
        <div class="flex flex-col md:flex-row items-center justify-center gap-4 mb-8" x-data="{ shown: false }" x-intersect="shown = true">
            <div class="flex flex-wrap justify-center gap-2">
                <template x-for="day in days" :key="day">
                    <button 
                        @click="activeTab = day" 
                        class="px-5 py-2.5 rounded-xl font-bold text-sm transition-all duration-300 transform hover:-translate-y-0.5 focus:outline-none"
                        :class="activeTab === day 
                            ? 'bg-elevate-primary text-white shadow-lg shadow-elevate-primary/30 ring-1 ring-elevate-primary' 
                            : 'bg-white dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-elevate-dark dark:hover:text-slate-200 ring-1 ring-slate-200 dark:ring-slate-700 shadow-sm'"
                    >
                        <span x-text="day"></span>
                    </button>
                </template>
            </div>

            <!-- Pemilih Kelas: wajib supaya tidak menampilkan 18 kelas x 41 JP sekaligus -->
            <div class="relative" x-show="classes.length > 0">
                <select 
                    x-model="selectedClass"
                    class="appearance-none pl-4 pr-10 py-2.5 rounded-xl font-bold text-sm bg-white dark:bg-slate-800 text-elevate-dark dark:text-slate-200 ring-1 ring-slate-200 dark:ring-slate-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-elevate-primary cursor-pointer"
                >
                    <template x-for="cls in classes" :key="cls">
                        <option :value="cls" x-text="'Kelas ' + cls"></option>
                    </template>
                </select>
                <i class="ph-bold ph-caret-down absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400 text-sm"></i>
            </div>
        </div>

        <!-- Konten Jadwal -->
        <div class="bg-white dark:bg-slate-800 rounded-[2rem] shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-700 p-6 md:p-8 min-h-[400px]">
            <div class="flex items-center justify-between mb-6 border-b border-slate-100 dark:border-slate-700 pb-4">
                <h3 class="text-xl font-black text-elevate-dark dark:text-slate-100 flex items-center gap-2">
                    <i class="ph-fill ph-calendar-blank text-elevate-primary"></i> 
                    Hari <span x-text="activeTab" class="text-elevate-primary"></span>
                    <span class="text-slate-300 dark:text-slate-600 font-medium" x-show="selectedClass">&middot;</span>
                    <span class="text-slate-400 dark:text-slate-500 text-base" x-show="selectedClass" x-text="'Kelas ' + selectedClass"></span>
                </h3>
                <span class="text-sm font-bold text-slate-400" x-text="currentItems.length ? currentItems.length + ' Kegiatan' : 'Libur'"></span>
            </div>

            <!-- List Jadwal -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <template x-for="(item, index) in currentItems" :key="index">
                    <div 
                        class="p-4 rounded-2xl border transition-all duration-300 hover:shadow-lg group flex items-start gap-4"
                        :class="item.type === 'kegiatan' ? 'bg-elevate-accent/5 border-elevate-accent/20 hover:border-elevate-accent/40 dark:bg-elevate-accent/10 dark:border-elevate-accent/30' : 'bg-white dark:bg-slate-800 border-slate-100 dark:border-slate-700 hover:border-elevate-primary/30 dark:hover:border-elevate-primary/50'"
                    >
                        <!-- Ikon Jam -->
                        <div class="shrink-0 flex flex-col items-center justify-center w-14 h-14 rounded-xl"
                            :class="item.type === 'kegiatan' ? 'bg-elevate-accent/20 text-elevate-primary dark:bg-elevate-accent/30 dark:text-elevate-accent' : 'bg-slate-50 dark:bg-slate-700 text-slate-400 dark:text-slate-300 group-hover:bg-elevate-primary/10 dark:group-hover:bg-elevate-primary/20 group-hover:text-elevate-primary dark:group-hover:text-elevate-accent'"
                        >
                            <i class="ph-bold ph-clock text-2xl mb-0.5"></i>
                        </div>
                        
                        <!-- Detail Info -->
                        <div class="flex-1">
                            <span class="inline-block px-2.5 py-1 rounded-md text-[10px] font-black tracking-widest uppercase mb-2"
                                :class="item.type === 'kegiatan' ? 'bg-elevate-primary text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-300'"
                                x-text="item.time"
                            ></span>
                            <h4 class="text-lg font-black text-elevate-dark dark:text-slate-100 mb-1 leading-tight group-hover:text-elevate-primary dark:group-hover:text-elevate-accent transition-colors" x-text="item.subject"></h4>
                            
                            <div class="flex items-center gap-3 text-sm font-medium text-slate-500 dark:text-slate-400 mt-2">
                                <div class="flex items-center gap-1.5">
                                    <i class="ph-fill ph-chalkboard-teacher text-slate-400 dark:text-slate-500"></i>
                                    <span x-text="item.teacher"></span>
                                </div>
                                <div class="w-1 h-1 rounded-full bg-slate-300 dark:bg-slate-600"></div>
                                <div class="flex items-center gap-1.5">
                                    <i class="ph-fill ph-users-three text-slate-400 dark:text-slate-500"></i>
                                    <span x-text="item.class"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
                
                <!-- State Kosong -->
                <div x-show="!currentItems.length" class="col-span-1 md:col-span-2 py-12 text-center flex flex-col items-center justify-center" style="display: none;">
                    <div class="w-20 h-20 rounded-full bg-slate-50 dark:bg-slate-700/50 flex items-center justify-center mb-4">
                        <i class="ph-fill ph-coffee text-4xl text-slate-300 dark:text-slate-500"></i>
                    </div>
                    <h4 class="text-lg font-black text-elevate-dark dark:text-slate-200 mb-1">Hari Libur Sekolah</h4>
                    <p class="text-slate-500 dark:text-slate-400 font-medium">Tidak ada kegiatan pembelajaran pada hari ini.</p>
                </div>
            </div>
        </div>

    </div>
</section>