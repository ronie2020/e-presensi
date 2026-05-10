<x-student-learning-layout>
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        [x-cloak] { display: none !important; }
        .embed-container { position: relative; }
        .embed-loading { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; background-color: #f8fafc; z-index: 0; }
        .embed-iframe { position: relative; z-index: 10; }
        
        /* Smooth scrolling on iOS */
        .smooth-scroll { -webkit-overflow-scrolling: touch; }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div x-data="learningPlayer()" x-init="initPlayer()" class="h-screen flex flex-col bg-elevate-surface font-sans overflow-hidden">
        
        {{-- HEADER ELEVATE THEME --}}
        <header class="h-16 bg-white border-b border-elevate-soft flex items-center justify-between px-4 sm:px-6 shrink-0 z-20 shadow-sm relative">
            {{-- Aksen gradient tipis di atas header --}}
            <div class="absolute top-0 left-0 w-full h-1 bg-elevate-gradient-main opacity-80"></div>
            
            <div class="flex items-center gap-3 md:gap-4 flex-1 min-w-0 mt-1">
                <a href="{{ isset($isPreview) && $isPreview ? route('lms.assignments.index') : route('students.learning.index') }}" class="w-10 h-10 rounded-xl bg-elevate-soft text-elevate-primary hover:bg-elevate-primary hover:text-white flex items-center justify-center transition-colors border border-transparent shrink-0 active:scale-95">
                    <i class="ph-bold ph-arrow-left text-lg"></i>
                </a>
                <div class="flex-1 min-w-0">
                    <h1 class="text-sm font-black text-elevate-dark leading-tight truncate">{{ $subject->name ?? 'Mata Pelajaran' }}</h1>
                    <p class="text-[10px] font-bold text-elevate-primary uppercase tracking-widest truncate">Alur Belajar Terstruktur</p>
                </div>
            </div>

            <div class="hidden md:flex items-center gap-4 ml-4 mt-1">
                <div class="text-right">
                    <p class="text-xs font-black text-elevate-dark"><span x-text="completedCount"></span> dari <span x-text="syllabus.length"></span> Selesai</p>
                </div>
                <div class="w-32 h-2.5 bg-elevate-soft rounded-full overflow-hidden border border-elevate-soft shadow-inner">
                    <div class="h-full bg-elevate-accent transition-all duration-500 ease-out" :style="`width: ${progressPercentage}%`"></div>
                </div>
            </div>

            <button @click="sidebarOpen = !sidebarOpen" class="md:hidden w-10 h-10 rounded-xl bg-elevate-soft text-elevate-primary flex items-center justify-center ml-2 shrink-0 active:scale-95 mt-1 transition-colors hover:bg-elevate-primary hover:text-white">
                <i class="ph-bold ph-list text-lg"></i>
            </button>
        </header>

        {{-- MAIN CONTENT --}}
        <div class="flex-1 flex overflow-hidden relative">
            
            {{-- MOBILE BACKDROP OVERLAY --}}
            <div x-show="sidebarOpen" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="sidebarOpen = false"
                 class="absolute inset-0 bg-elevate-dark/60 backdrop-blur-sm z-30 md:hidden" x-cloak>
            </div>

            {{-- SIDEBAR SILABUS HIERARKIS (GROUPED) --}}
            <aside class="absolute md:static inset-y-0 left-0 w-[85%] max-w-[320px] md:w-80 bg-white border-r border-elevate-soft z-40 md:z-10 transform transition-transform duration-300 md:transform-none flex flex-col shadow-2xl md:shadow-none"
                   :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
                
                <div class="p-5 border-b border-elevate-soft bg-elevate-soft/30 flex items-center justify-between">
                    <h2 class="text-xs font-black text-elevate-primary uppercase tracking-widest flex items-center gap-2">
                        <i class="ph-fill ph-list-numbers text-elevate-accent"></i> Daftar Isi
                    </h2>
                    <button @click="sidebarOpen = false" class="md:hidden w-8 h-8 rounded-lg bg-white border border-elevate-soft text-elevate-primary flex items-center justify-center active:scale-95">
                        <i class="ph-bold ph-x"></i>
                    </button>
                </div>
                
                <div class="flex-1 overflow-y-auto no-scrollbar smooth-scroll p-3 space-y-5 bg-white">
                    <template x-for="(group, gIndex) in groupedSyllabus" :key="'group-'+gIndex">
                        <div>
                            {{-- Nama Induk Materi / Tugas --}}
                            <div class="px-3 mb-2 flex items-center gap-2">
                                <div class="w-1.5 h-1.5 rounded-full bg-elevate-primary/30 shrink-0"></div>
                                <h3 class="text-[10px] font-black text-elevate-dark/50 uppercase tracking-widest line-clamp-1" x-text="group.title"></h3>
                            </div>
                            
                            {{-- Sub Item (Pengantar, Video, PDF) --}}
                            <div class="space-y-1">
                                <template x-for="(item, index) in group.items" :key="item.id">
                                    <button @click="selectItem(item.id)" :disabled="item.locked" class="w-full text-left p-3 rounded-2xl transition-all duration-200 flex gap-3 relative group"
                                            :class="{'bg-elevate-primary text-white shadow-lg shadow-elevate-primary/20': activeItem && activeItem.id === item.id, 'hover:bg-elevate-soft text-elevate-dark': activeItem && activeItem.id !== item.id && !item.locked, 'opacity-50 cursor-not-allowed bg-elevate-soft/50 text-elevate-dark/40': item.locked}">
                                        
                                        <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 border transition-colors"
                                             :class="{'bg-white/20 border-white/20 text-white': activeItem && activeItem.id === item.id, 'bg-[#DFF6DD] border-[#B7DFB9] text-[#107C10]': item.completed && (activeItem && activeItem.id !== item.id), 'bg-white border-elevate-soft text-elevate-primary': !item.completed && !item.locked && (activeItem && activeItem.id !== item.id), 'bg-elevate-soft border-transparent text-elevate-dark/30': item.locked}">
                                            
                                            <i x-show="item.completed && (activeItem && activeItem.id !== item.id)" class="ph-bold ph-check"></i>
                                            <i x-show="item.locked" class="ph-bold ph-lock-key"></i>
                                            <template x-if="!item.locked && (!item.completed || (activeItem && activeItem.id === item.id))">
                                                <span>
                                                    <i x-show="item.type === 'video'" class="ph-fill ph-play-circle text-lg"></i>
                                                    <i x-show="item.type === 'file' || item.type === 'pdf'" class="ph-fill ph-file-pdf text-lg"></i>
                                                    <i x-show="item.type === 'text'" class="ph-fill ph-text-t text-lg"></i>
                                                    <i x-show="item.type === 'link'" class="ph-fill ph-link text-lg"></i>
                                                    <i x-show="item.type === 'assignment'" class="ph-fill ph-clipboard-text text-lg"></i>
                                                </span>
                                            </template>
                                        </div>

                                        <div class="flex-1 min-w-0 flex flex-col justify-center">
                                            <span class="text-[10px] font-black uppercase tracking-wider mb-0.5" :class="(activeItem && activeItem.id === item.id) ? 'text-white/70' : 'text-elevate-dark/40'"
                                                  x-text="item.type === 'assignment' ? 'Tugas' : (item.type === 'video' ? 'Video Belajar' : (item.type === 'file' ? 'Dokumen PDF' : 'Materi Pembuka'))"></span>
                                            <h4 class="text-sm font-bold truncate leading-tight" :class="(activeItem && activeItem.id === item.id) ? 'text-white' : 'text-elevate-dark'" x-text="item.title"></h4>
                                        </div>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </aside>

            {{-- KONTEN TENGAH DINAMIS --}}
            <main class="flex-1 bg-elevate-soft/20 flex flex-col relative overflow-hidden smooth-scroll">
                <div class="flex-1 overflow-y-auto p-4 sm:p-8 flex justify-center w-full relative">
                    {{-- Dekorasi background tengah --}}
                    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-elevate-gradient-main opacity-5 blur-[100px] pointer-events-none rounded-full"></div>

                    <div class="w-full max-w-5xl relative min-h-full z-10" x-show="activeItem" x-cloak>
                        <div x-show="!isTransitioning" x-transition.opacity.duration.300ms class="pb-24">

                            {{-- 1. RENDER TEKS PENGANTAR --}}
                            <template x-if="activeItem.type === 'text'">
                                <div class="bg-white rounded-[2rem] shadow-sm border border-elevate-soft overflow-hidden flex flex-col p-6 md:p-10">
                                    <h2 class="text-xl md:text-2xl font-black text-elevate-dark mb-6 leading-tight" x-text="activeItem.group_title"></h2>
                                    <div class="prose max-w-none text-elevate-dark/80 bg-elevate-soft/30 p-6 md:p-8 rounded-2xl border border-elevate-soft" x-html="activeItem.content"></div>
                                </div>
                            </template>

                            {{-- 2. RENDER VIDEO YOUTUBE --}}
                            <template x-if="activeItem.type === 'video'">
                                <div class="bg-white rounded-[2rem] shadow-sm border border-elevate-soft overflow-hidden flex flex-col p-4 md:p-8">
                                    <h2 class="text-lg md:text-xl font-black text-elevate-dark mb-4 ml-1 md:ml-2 leading-tight" x-text="activeItem.title"></h2>
                                    <div class="w-full aspect-video rounded-xl overflow-hidden bg-elevate-dark shadow-inner border border-elevate-dark embed-container">
                                        <div class="embed-loading bg-elevate-soft/50"><div class="animate-pulse flex flex-col items-center gap-3"><i class="ph-duotone ph-spinner-gap animate-spin text-4xl text-elevate-primary"></i><span class="text-xs font-bold text-elevate-primary">Memuat Video...</span></div></div>
                                        <iframe :src="getEmbedUrl(activeItem.file_url)" class="w-full h-full embed-iframe border-0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>
                                </div>
                            </template>

                            {{-- 3. RENDER PDF / DOKUMEN --}}
                            <template x-if="activeItem.type === 'file'">
                                <div class="bg-white rounded-[2rem] shadow-sm border border-elevate-soft overflow-hidden flex flex-col p-4 md:p-8">
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4 ml-1 md:ml-2">
                                        <h2 class="text-lg md:text-xl font-black text-elevate-dark leading-tight" x-text="activeItem.title"></h2>
                                        <a :href="activeItem.file_url" target="_blank" class="w-full sm:w-auto px-5 py-3 sm:py-2.5 bg-elevate-soft text-elevate-primary rounded-xl text-sm font-bold hover:bg-elevate-primary hover:text-white transition-colors text-center flex items-center justify-center gap-2 active:scale-95">
                                            <i class="ph-bold ph-download-simple"></i> Download / Buka
                                        </a>
                                    </div>
                                    <div class="w-full h-[50vh] md:h-[65vh] rounded-xl overflow-hidden bg-elevate-soft/50 shadow-inner border border-elevate-soft embed-container">
                                        <div class="embed-loading bg-elevate-soft/50"><div class="animate-pulse flex flex-col items-center gap-3"><i class="ph-duotone ph-spinner-gap animate-spin text-4xl text-elevate-primary"></i><span class="text-xs font-bold text-elevate-primary">Memuat Dokumen...</span></div></div>
                                        <iframe :src="activeItem.file_url + '#toolbar=0'" class="w-full h-full embed-iframe border-0" type="application/pdf"></iframe>
                                    </div>
                                </div>
                            </template>

                            {{-- 4. RENDER TAUTAN LINK LUAR --}}
                            <template x-if="activeItem.type === 'link'">
                                <div class="bg-white rounded-[2rem] shadow-sm border border-elevate-soft overflow-hidden flex flex-col p-6 md:p-10">
                                    <h2 class="text-lg md:text-xl font-black text-elevate-dark mb-6 leading-tight" x-text="activeItem.title"></h2>
                                    <div class="p-6 bg-elevate-soft/50 border border-elevate-soft rounded-2xl flex flex-col sm:flex-row items-center justify-between gap-4">
                                        <span class="text-sm font-medium text-elevate-primary truncate flex-1 w-full" x-text="activeItem.file_url"></span>
                                        <a :href="activeItem.file_url" target="_blank" class="w-full sm:w-auto px-6 py-3.5 bg-elevate-primary text-white rounded-xl text-sm font-bold hover:bg-elevate-dark shadow-lg shadow-elevate-primary/20 text-center flex items-center justify-center gap-2 transition-colors active:scale-95"><i class="ph-bold ph-arrow-square-out"></i> Buka Tautan</a>
                                    </div>
                                </div>
                            </template>

                            {{-- 5. RENDER TUGAS / KUIS --}}
                            <template x-if="activeItem.type === 'assignment'">
                                <div class="bg-white rounded-[2.5rem] shadow-sm border border-elevate-soft overflow-hidden">
                                    <div class="bg-elevate-dark text-white p-6 md:p-8 relative overflow-hidden">
                                        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                                        <div class="absolute -top-10 -right-10 w-40 h-40 bg-elevate-primary/30 blur-2xl rounded-full pointer-events-none"></div>
                                        <div class="relative z-10">
                                            <span class="px-3 py-1 bg-elevate-peach/20 text-elevate-peach-light text-[10px] font-black uppercase tracking-widest rounded-full border border-elevate-peach/20 mb-3 inline-block shadow-sm" x-text="activeItem.assignment_type.toUpperCase()"></span>
                                            <h2 class="text-xl md:text-2xl font-black text-white leading-tight" x-text="activeItem.group_title"></h2>
                                        </div>
                                    </div>
                                    <div class="p-6 md:p-8">
                                        <div class="prose max-w-none text-elevate-dark/80 mb-8 bg-elevate-soft/30 p-5 md:p-6 rounded-2xl border border-elevate-soft text-sm md:text-base" x-text="activeItem.content"></div>
                                        
                                        <template x-if="activeItem.completed">
                                            {{-- SUCCESS CARD MENGGUNAKAN WARNA #107C10 DARI DAILY.BLADE.PHP --}}
                                            <div class="bg-[#DFF6DD] text-[#107C10] p-5 md:p-6 rounded-2xl border border-[#B7DFB9] flex flex-col sm:flex-row items-start sm:items-center gap-4 shadow-sm">
                                                <i class="ph-fill ph-check-circle text-4xl hidden sm:block"></i>
                                                <div class="flex items-center gap-3 sm:hidden mb-2">
                                                     <i class="ph-fill ph-check-circle text-3xl"></i>
                                                     <p class="font-black text-lg">Tugas Selesai!</p>
                                                </div>
                                                <div class="w-full">
                                                    <p class="font-black text-lg hidden sm:block">Tugas Selesai!</p>
                                                    <p class="text-sm font-medium opacity-90">Kamu sudah mengerjakan tugas ini.</p>
                                                    <template x-if="activeItem.grade !== null">
                                                        <p class="text-xs font-bold mt-2 bg-[#107C10] text-white px-3 py-1.5 rounded-lg w-fit shadow-sm border border-transparent">Nilai: <span x-text="activeItem.grade"></span></p>
                                                    </template>
                                                </div>
                                            </div>
                                        </template>

                                        <template x-if="!activeItem.completed">
                                            <div>
                                                <template x-if="activeItem.assignment_type === 'quiz'">
                                                    <a :href="'/students/learning/assignment/' + activeItem.db_id + '/quiz'" class="block w-full py-4 bg-elevate-primary text-white text-center font-bold rounded-2xl shadow-lg shadow-elevate-primary/20 hover:bg-elevate-dark transition-colors active:scale-95">
                                                        <i class="ph-bold ph-play-circle mr-2"></i> Buka Halaman Kuis (<span x-text="activeItem.duration"></span> Menit)
                                                    </a>
                                                </template>
                                                <template x-if="activeItem.assignment_type !== 'quiz'">
                                                    <form :action="'/students/learning/assignment/' + activeItem.db_id + '/submit'" method="POST" enctype="multipart/form-data" class="space-y-5 p-5 md:p-6 border-2 border-dashed border-elevate-primary/30 rounded-2xl bg-elevate-soft/30">
                                                        @csrf
                                                        <input type="hidden" name="submission_type" :value="activeItem.assignment_type === 'link' ? 'link' : 'file'">
                                                        <template x-if="activeItem.assignment_type === 'link'">
                                                            <div>
                                                                <a :href="activeItem.link_url" target="_blank" class="text-elevate-primary font-bold mb-4 inline-flex items-center gap-2 bg-elevate-soft px-4 py-2 rounded-lg hover:bg-elevate-primary hover:text-white transition-colors"><i class="ph-link"></i> Buka Link Soal/Tugas</a>
                                                                <input type="url" name="link_url" placeholder="Paste link jawaban kamu di sini..." class="w-full p-4 border border-elevate-soft rounded-xl focus:ring-2 focus:ring-elevate-accent/20 focus:border-elevate-accent bg-white text-elevate-dark outline-none transition-all text-sm shadow-sm">
                                                            </div>
                                                        </template>
                                                        <template x-if="activeItem.assignment_type === 'file_upload'">
                                                            <input type="file" name="file" required class="block w-full text-sm text-elevate-dark/60 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-elevate-primary file:text-white hover:file:bg-elevate-dark transition-colors border border-elevate-soft bg-white rounded-xl shadow-sm cursor-pointer">
                                                        </template>
                                                        <textarea name="student_note" rows="3" placeholder="Tambahkan catatan opsional..." class="w-full rounded-xl border-elevate-soft p-4 focus:ring-2 focus:ring-elevate-accent/20 focus:border-elevate-accent text-elevate-dark bg-white outline-none transition-all text-sm resize-none shadow-sm"></textarea>
                                                        <button type="submit" class="w-full py-4 bg-elevate-primary text-white font-bold rounded-xl shadow-lg shadow-elevate-primary/20 hover:bg-elevate-dark transition-colors active:scale-95 flex items-center justify-center gap-2">
                                                            <i class="ph-bold ph-paper-plane-right text-lg"></i> Kumpulkan Tugas
                                                        </button>
                                                    </form>
                                                </template>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- TOMBOL KONTROL BAWAH --}}
                <div class="bg-white border-t border-elevate-soft p-4 shadow-[0_-10px_40px_rgba(44,63,97,0.05)] z-20">
                    <div class="max-w-4xl mx-auto flex flex-row items-center justify-between gap-3 md:gap-4">
                        
                        <button @click="prevItem()" :disabled="currentIndex === 0" class="px-5 md:px-6 py-3.5 rounded-2xl font-bold flex items-center gap-2 transition-all shrink-0 active:scale-95" :class="currentIndex === 0 ? 'bg-elevate-soft/50 text-elevate-dark/30 border-transparent cursor-not-allowed' : 'bg-white border border-elevate-soft text-elevate-dark hover:bg-elevate-soft shadow-sm'">
                            <i class="ph-bold ph-arrow-left text-lg"></i> <span class="hidden sm:inline">Kembali</span>
                        </button>

                        <template x-if="activeItem && (activeItem.type !== 'assignment' || activeItem.completed)">
                            <button @click="handleNextClick()" class="flex-1 w-full px-4 md:px-8 py-3.5 bg-elevate-primary text-white font-bold rounded-2xl shadow-lg shadow-elevate-primary/20 hover:bg-elevate-dark transition-all flex items-center justify-center gap-2 active:scale-95">
                                <span class="truncate text-sm md:text-base" x-text="activeItem.completed ? 'Lanjut' : 'Tandai Selesai & Lanjut'"></span>
                                <i class="ph-bold ph-arrow-right shrink-0 text-lg"></i>
                            </button>
                        </template>
                        
                        {{-- WARNING ALERT MENGGUNAKAN WARNA #D13438 DARI DAILY.BLADE.PHP --}}
                        <template x-if="activeItem && activeItem.type === 'assignment' && !activeItem.completed">
                            <div class="flex-1 w-full px-4 md:px-8 py-3.5 bg-[#FDE7E9] text-[#D13438] font-bold rounded-2xl border border-[#F4C3C9] flex items-center justify-center gap-2 text-xs md:text-sm shadow-sm">
                                <i class="ph-warning-circle shrink-0 text-lg"></i> <span class="truncate">Selesaikan tugas untuk lanjut.</span>
                            </div>
                        </template>

                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('learningPlayer', () => ({
                sidebarOpen: false,
                isTransitioning: false,
                syllabus: {!! $syllabusJson ?? '[]' !!}, 
                activeItem: null,

                get groupedSyllabus() {
                    let groups = [];
                    let currentGroup = null;
                    
                    this.syllabus.forEach(item => {
                        if (!currentGroup || currentGroup.title !== item.group_title) {
                            currentGroup = { title: item.group_title, items: [] };
                            groups.push(currentGroup);
                        }
                        currentGroup.items.push(item);
                    });
                    
                    return groups;
                },

                getEmbedUrl(url) {
                    if (!url) return '';
                    const ytMatch = url.match(/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})/);
                    if (ytMatch && ytMatch[1]) {
                        return `https://www.youtube.com/embed/${ytMatch[1]}?rel=0`;
                    }
                    if (url.includes('drive.google.com/file/d/')) {
                        return url.replace(/\/view.*$/, '/preview');
                    }
                    return url;
                },
                
                initPlayer() {
                    if(this.syllabus.length === 0) return;
                    let target = this.syllabus.find(item => !item.completed && !item.locked);
                    this.activeItem = target || this.syllabus[0];
                },

                get currentIndex() { return this.syllabus.findIndex(item => item.id === this.activeItem.id); },
                get completedCount() { return this.syllabus.filter(item => item.completed).length; },
                get progressPercentage() { return this.syllabus.length ? (this.completedCount / this.syllabus.length) * 100 : 0; },

                selectItem(id) {
                    const item = this.syllabus.find(i => i.id === id);
                    if (!item || item.locked) return;
                    this.changeContent(item);
                    
                    // AUTO-CLOSE SIDEBAR DI HP SETELAH MEMILIH MATERI
                    if (window.innerWidth < 768) {
                        this.sidebarOpen = false;
                    }
                },

                prevItem() {
                    if (this.currentIndex > 0) this.changeContent(this.syllabus[this.currentIndex - 1]);
                },

                handleNextClick() {
                    if (!this.activeItem.completed) {
                        this.markCurrentAsCompleted();
                    } else {
                        this.goToNext();
                    }
                },

                markCurrentAsCompleted() {
                    @if(!isset($isPreview))
                        if (this.activeItem.type !== 'assignment') {
                            fetch('{{ route("students.learning.mark-material") }}', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                body: JSON.stringify({ material_id: this.activeItem.db_id })
                            });
                        }
                    @endif

                    this.syllabus[this.currentIndex].completed = true;
                    if (this.currentIndex + 1 < this.syllabus.length) {
                        this.syllabus[this.currentIndex + 1].locked = false; 
                    }
                    this.goToNext();
                },

                goToNext() {
                    if (this.currentIndex + 1 < this.syllabus.length) {
                        this.changeContent(this.syllabus[this.currentIndex + 1]);
                    } else {
                        Swal.fire({
                            icon: 'success', 
                            title: 'Selesai!',
                            text: 'Selamat! Kamu telah menyelesaikan bab ini.',
                            confirmButtonColor: '#0d52a1', // elevate-primary
                            customClass: { popup: 'rounded-[2rem] font-sans' }
                        }).then(() => {
                            window.location.href = "{{ isset($isPreview) && $isPreview ? route('lms.assignments.index') : route('students.learning.index') }}";
                        });
                    }
                },

                changeContent(newItem) {
                    this.isTransitioning = true;
                    setTimeout(() => {
                        this.activeItem = newItem;
                        this.isTransitioning = false;
                    }, 300);
                }
            }));
        });
    </script>
</x-student-learning-layout>