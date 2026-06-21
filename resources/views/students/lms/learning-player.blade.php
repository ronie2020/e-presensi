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
        
        /* Animasi Muncul */
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { animation: fadeInUp 0.5s ease-out forwards; }
    </style>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div x-data="learningPlayer()" x-init="initPlayer()" class="h-screen flex flex-col bg-elevate-surface font-sans overflow-hidden">
        
        {{-- HEADER ELEVATE THEME --}}
        <header class="h-16 bg-white border-b border-elevate-soft flex items-center justify-between px-4 sm:px-6 shrink-0 z-20 shadow-sm relative">
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
                            
                            {{-- Sub Item --}}
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
                                                    <template x-if="item.type === 'assignment'">
                                                        <i :class="item.assignment_type === 'interactive_video' ? 'ph-fill ph-youtube-logo text-lg' : 'ph-fill ph-clipboard-text text-lg'"></i>
                                                    </template>
                                                </span>
                                            </template>
                                        </div>

                                        <div class="flex-1 min-w-0 flex flex-col justify-center">
                                            <span class="text-[10px] font-black uppercase tracking-wider mb-0.5" :class="(activeItem && activeItem.id === item.id) ? 'text-white/70' : 'text-elevate-dark/40'"
                                                  x-text="item.type === 'assignment' ? (item.assignment_type === 'interactive_video' ? 'Video Interaktif' : 'Tugas') : (item.type === 'video' ? 'Video Belajar' : (item.type === 'file' ? 'Dokumen PDF' : (item.type === 'link' ? 'Tautan Eksternal' : 'Materi Pembuka')))"></span>
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

                            {{-- 2. RENDER VIDEO YOUTUBE BIASA --}}
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

                            {{-- 4. RENDER TAUTAN LINK LUAR (EMBEDDED) --}}
                            <template x-if="activeItem.type === 'link'">
                                <div class="bg-white rounded-[2rem] shadow-sm border border-elevate-soft overflow-hidden flex flex-col p-4 md:p-8">
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4 ml-1 md:ml-2">
                                        <div class="flex-1">
                                            <h2 class="text-lg md:text-xl font-black text-elevate-dark leading-tight mb-1" x-text="activeItem.title"></h2>
                                            <p class="text-xs font-medium text-elevate-primary truncate" x-text="activeItem.file_url"></p>
                                        </div>
                                        <a :href="activeItem.file_url" target="_blank" class="w-full sm:w-auto px-5 py-3 sm:py-2.5 bg-elevate-primary text-white rounded-xl text-sm font-bold hover:bg-elevate-dark shadow-lg shadow-elevate-primary/20 transition-colors text-center flex items-center justify-center gap-2 active:scale-95 shrink-0">
                                            <i class="ph-bold ph-arrow-square-out"></i> Buka di Tab Baru
                                        </a>
                                    </div>

                                    <div class="mb-5 p-3.5 bg-elevate-soft/30 border border-elevate-soft rounded-xl flex items-start gap-3">
                                        <i class="ph-fill ph-info text-elevate-primary text-xl shrink-0 mt-0.5"></i>
                                        <p class="text-xs sm:text-sm text-elevate-dark/70 font-medium">Beberapa website mungkin menolak untuk ditampilkan di dalam halaman ini demi keamanan privasi. Jika kotak di bawah berwarna putih/kosong, silakan gunakan tombol <b>Buka di Tab Baru</b>.</p>
                                    </div>

                                    <div class="w-full h-[50vh] md:h-[60vh] rounded-xl overflow-hidden bg-elevate-soft/50 shadow-inner border border-elevate-soft embed-container">
                                        <div class="embed-loading bg-elevate-soft/50"><div class="animate-pulse flex flex-col items-center gap-3"><i class="ph-duotone ph-spinner-gap animate-spin text-4xl text-elevate-primary"></i><span class="text-xs font-bold text-elevate-primary">Memuat Halaman...</span></div></div>
                                        <iframe :src="activeItem.file_url" class="w-full h-full embed-iframe border-0 bg-white" sandbox="allow-scripts allow-same-origin allow-forms allow-popups" allowfullscreen></iframe>
                                    </div>
                                </div>
                            </template>

                            {{-- 5. RENDER TUGAS / KUIS / VIDEO INTERAKTIF --}}
                            <template x-if="activeItem.type === 'assignment'">
                                <div class="bg-white rounded-[2.5rem] shadow-sm border border-elevate-soft overflow-hidden">
                                    <div class="bg-elevate-dark text-white p-6 md:p-8 relative overflow-hidden">
                                        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                                        <div class="absolute -top-10 -right-10 w-40 h-40 bg-elevate-primary/30 blur-2xl rounded-full pointer-events-none"></div>
                                        <div class="relative z-10">
                                            <span class="px-3 py-1 bg-elevate-peach/20 text-elevate-peach-light text-[10px] font-black uppercase tracking-widest rounded-full border border-elevate-peach/20 mb-3 inline-block shadow-sm" x-text="activeItem.assignment_type.replace('_', ' ').toUpperCase()"></span>
                                            <h2 class="text-xl md:text-2xl font-black text-white leading-tight" x-text="activeItem.group_title"></h2>
                                        </div>
                                    </div>
                                    <div class="p-6 md:p-8">
                                        <div class="prose max-w-none text-elevate-dark/80 mb-8 bg-elevate-soft/30 p-5 md:p-6 rounded-2xl border border-elevate-soft text-sm md:text-base" x-html="activeItem.content"></div>
                                        
                                        <template x-if="activeItem.completed">
                                            <div class="bg-[#DFF6DD] text-[#107C10] p-5 md:p-6 rounded-2xl border border-[#B7DFB9] flex flex-col sm:flex-row items-start sm:items-center gap-4 shadow-sm">
                                                <i class="ph-fill ph-check-circle text-4xl hidden sm:block"></i>
                                                <div class="flex items-center gap-3 sm:hidden mb-2">
                                                     <i class="ph-fill ph-check-circle text-3xl"></i>
                                                     <p class="font-black text-lg">Tugas Selesai!</p>
                                                </div>
                                                <div class="w-full">
                                                    <p class="font-black text-lg hidden sm:block">Tugas Selesai!</p>
                                                    <p class="text-sm font-medium opacity-90">Kamu sudah menyelesaikan/mengumpulkan tugas ini.</p>
                                                    <template x-if="activeItem.grade !== null">
                                                        <p class="text-xs font-bold mt-2 bg-[#107C10] text-white px-3 py-1.5 rounded-lg w-fit shadow-sm border border-transparent">Nilai: <span x-text="activeItem.grade"></span></p>
                                                    </template>
                                                </div>
                                            </div>
                                        </template>

                                        <template x-if="!activeItem.completed">
                                            <div>
                                                {{-- OPSI 1: KUIS ONLINE --}}
                                                <template x-if="activeItem.assignment_type === 'quiz'">
                                                    <a :href="'/students/learning/assignment/' + activeItem.db_id + '/quiz'" class="block w-full py-4 bg-elevate-primary text-white text-center font-bold rounded-2xl shadow-lg shadow-elevate-primary/20 hover:bg-elevate-dark transition-colors active:scale-95">
                                                        <i class="ph-bold ph-play-circle mr-2"></i> Buka Halaman Kuis (<span x-text="activeItem.duration"></span> Menit)
                                                    </a>
                                                </template>

                                                {{-- OPSI 2: TUGAS UPLOAD & LINK --}}
                                                <template x-if="activeItem.assignment_type === 'file_upload' || activeItem.assignment_type === 'link'">
                                                    <form x-data="{ submitting: false }" @submit="submitting = true" :action="'/students/learning/assignment/' + activeItem.db_id + '/submit'" method="POST" enctype="multipart/form-data" class="space-y-5 p-5 md:p-6 border-2 border-dashed border-elevate-primary/30 rounded-2xl bg-elevate-soft/30">
                                                        @csrf
                                                        <input type="hidden" name="submission_type" :value="activeItem.assignment_type === 'link' ? 'link' : 'file'">
                                                        <template x-if="activeItem.assignment_type === 'link'">
                                                            <div>
                                                                <a :href="activeItem.link_url" target="_blank" class="text-elevate-primary font-bold mb-4 inline-flex items-center gap-2 bg-elevate-soft px-4 py-2 rounded-lg hover:bg-elevate-primary hover:text-white transition-colors"><i class="ph-link"></i> Buka Link Soal/Tugas</a>
                                                                <input type="url" name="link_url" placeholder="Paste link jawaban kamu di sini..." required class="w-full p-4 border border-elevate-soft rounded-xl focus:ring-2 focus:ring-elevate-accent/20 focus:border-elevate-accent bg-white text-elevate-dark outline-none transition-all text-sm shadow-sm">
                                                            </div>
                                                        </template>
                                                        <template x-if="activeItem.assignment_type === 'file_upload'">
                                                            <input type="file" name="file" required class="block w-full text-sm text-elevate-dark/60 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-elevate-primary file:text-white hover:file:bg-elevate-dark transition-colors border border-elevate-soft bg-white rounded-xl shadow-sm cursor-pointer">
                                                        </template>
                                                        <textarea name="student_note" rows="3" placeholder="Tambahkan catatan opsional..." class="w-full rounded-xl border-elevate-soft p-4 focus:ring-2 focus:ring-elevate-accent/20 focus:border-elevate-accent text-elevate-dark bg-white outline-none transition-all text-sm resize-none shadow-sm"></textarea>
                                                        
                                                        <button type="submit" :disabled="submitting" class="w-full py-4 bg-elevate-primary text-white font-bold rounded-xl shadow-lg shadow-elevate-primary/20 hover:bg-elevate-dark transition-colors flex items-center justify-center gap-2" :class="submitting ? 'opacity-70 cursor-not-allowed' : 'active:scale-95'">
                                                            <i class="ph-bold ph-spinner-gap animate-spin text-lg" x-show="submitting" x-cloak></i>
                                                            <i class="ph-bold ph-paper-plane-right text-lg" x-show="!submitting"></i> 
                                                            <span x-text="submitting ? 'Mengunggah Tugas...' : 'Kumpulkan Tugas'"></span>
                                                        </button>
                                                    </form>
                                                </template>

                                                {{-- OPSI 3: VIDEO INTERAKTIF --}}
                                                <template x-if="activeItem.assignment_type === 'interactive_video'">
                                                    <div class="space-y-6">
                                                        {{-- Indikator Warning jika soal belum termuat --}}
                                                        <template x-if="!interactiveQuestionsState || interactiveQuestionsState.length === 0">
                                                            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-xl text-yellow-700 text-sm font-bold flex items-center gap-3">
                                                                <i class="ph-bold ph-warning-circle text-xl shrink-0"></i>
                                                                Terdapat kendala: Data kuis interaktif kosong. Lapor ke guru atau coba refresh halaman.
                                                            </div>
                                                        </template>

                                                        {{-- Render Video Youtube Player --}}
                                                        <div class="w-full aspect-video rounded-2xl overflow-hidden bg-black shadow-inner border border-elevate-dark relative">
                                                            {{-- Container Asli Iframe YouTube API --}}
                                                            <div id="interactive-youtube-container" class="w-full h-full"></div>
                                                            
                                                            {{-- Overlay Saat Jeda Kuis DIPERBARUI --}}
                                                            <div x-show="activeInteractiveQuiz" x-transition.opacity style="display: none;" class="fixed inset-0 z-[100] bg-elevate-dark/90 backdrop-blur-md flex items-center justify-center p-4 md:p-8">
                                                                <div class="bg-white rounded-[2rem] p-6 md:p-8 max-w-lg w-full shadow-2xl flex flex-col max-h-[90vh]">
                                                                    <div class="flex items-center justify-between mb-4 shrink-0">
                                                                        <span class="px-3 py-1 bg-red-100 text-red-600 rounded-lg text-[10px] font-black uppercase tracking-widest border border-red-200"><i class="ph-fill ph-youtube-logo"></i> Kuis Video</span>
                                                                        <span class="text-[10px] font-bold text-slate-400">Jawab untuk lanjut</span>
                                                                    </div>
                                                                    <h3 class="text-base md:text-lg font-black text-elevate-dark mb-5 leading-tight shrink-0" x-text="activeInteractiveQuiz ? activeInteractiveQuiz.text : ''"></h3>
                                                                    
                                                                    <div class="space-y-3 overflow-y-auto flex-1 no-scrollbar">
                                                                        <template x-if="activeInteractiveQuiz">
                                                                            <template x-for="optKey in ['A', 'B', 'C', 'D']" :key="optKey">
                                                                                <template x-if="activeInteractiveQuiz.options && activeInteractiveQuiz.options[optKey]">
                                                                                    <label class="flex items-center gap-4 p-3 md:p-4 rounded-xl border-2 cursor-pointer transition-all"
                                                                                           :class="selectedInteractiveAnswer === optKey ? 'border-elevate-primary bg-elevate-soft/50' : 'border-slate-100 bg-white hover:border-elevate-primary/30'">
                                                                                        <div class="relative flex items-center justify-center shrink-0">
                                                                                            <input type="radio" :value="optKey" x-model="selectedInteractiveAnswer" class="peer sr-only">
                                                                                            <div class="w-6 h-6 rounded-full border-2 border-slate-300 peer-checked:border-elevate-primary peer-checked:bg-elevate-primary flex items-center justify-center text-white text-xs font-bold transition-all">
                                                                                                <i class="ph-bold ph-check opacity-0 peer-checked:opacity-100"></i>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="flex-1">
                                                                                            <span class="text-[10px] font-black text-slate-400 block mb-0.5 uppercase tracking-wider" x-text="'Pilihan ' + optKey"></span>
                                                                                            <span class="font-bold text-elevate-dark text-sm leading-snug" x-text="activeInteractiveQuiz.options[optKey]"></span>
                                                                                        </div>
                                                                                    </label>
                                                                                </template>
                                                                            </template>
                                                                        </template>
                                                                    </div>
                                                                    
                                                                    <button @click="submitInteractiveAnswer()" :disabled="!selectedInteractiveAnswer" class="w-full mt-5 py-3.5 bg-elevate-primary text-white font-bold rounded-xl active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2 transition-all shrink-0">
                                                                        <span>Kirim Jawaban</span> <i class="ph-bold ph-paper-plane-right"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        {{-- Form Submit Otomatis Tampil Setelah Semua Soal Terjawab --}}
                                                        <template x-if="interactiveQuestionsState.length > 0 && interactiveQuestionsState.every(q => q.is_solved)">
                                                            <form :action="'/students/learning/assignment/' + activeItem.db_id + '/submit'" method="POST" class="animate-enter space-y-4" x-data="{ submitting: false }" @submit="submitting = true">
                                                                @csrf
                                                                <input type="hidden" name="submission_type" value="interactive_video">
                                                                <div class="bg-[#DFF6DD] text-[#107C10] p-5 rounded-2xl border border-[#B7DFB9] flex flex-col sm:flex-row items-center gap-4 shadow-sm text-center sm:text-left">
                                                                    <i class="ph-fill ph-check-circle text-4xl shrink-0"></i>
                                                                    <div>
                                                                        <p class="font-black text-lg">Luar Biasa!</p>
                                                                        <p class="text-sm font-medium">Kamu telah menonton dan menyelesaikan semua interaksi kuis di video ini.</p>
                                                                    </div>
                                                                </div>
                                                                <button type="submit" :disabled="submitting" class="w-full py-4 bg-elevate-primary text-white font-bold rounded-xl shadow-lg shadow-elevate-primary/20 hover:bg-elevate-dark transition-colors flex items-center justify-center gap-2" :class="submitting ? 'opacity-70 cursor-not-allowed' : 'active:scale-95'">
                                                                    <i class="ph-bold ph-spinner-gap animate-spin text-lg" x-show="submitting" x-cloak></i>
                                                                    <i class="ph-bold ph-flag-checkered text-lg" x-show="!submitting"></i> 
                                                                    <span x-text="submitting ? 'Mengumpulkan...' : 'Selesaikan Tugas Video'"></span>
                                                                </button>
                                                            </form>
                                                        </template>
                                                    </div>
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
                        
                        {{-- WARNING ALERT --}}
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

                // State Video Interaktif
                youtubePlayer: null,
                videoWatcher: null,
                interactiveQuestionsState: [],
                activeInteractiveQuiz: null,
                selectedInteractiveAnswer: null,

                //  State Pelacakan Waktu
                timeSpent: 0,
                lastLogTime: Date.now(),

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

                extractYoutubeId(url) {
                    const ytMatch = url.match(/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})/);
                    return ytMatch ? ytMatch[1] : null;
                },
                
                 initPlayer() {
                    if(this.syllabus.length === 0) return;
                    let target = this.syllabus.find(item => !item.completed && !item.locked);
                    this.activeItem = target || this.syllabus[0];

                    if (this.activeItem.type === 'assignment' && this.activeItem.assignment_type === 'interactive_video') {
                        setTimeout(() => this.initInteractiveVideo(this.activeItem), 500);
                    }

                    // TAMBAHAN: Mulai pelacakan waktu
                    this.lastLogTime = Date.now();
                    
                    // Event ketika browser ditutup / refresh agar waktu terakhir tetap terkirim
                    window.addEventListener('beforeunload', () => {
                        this.sendLogTime(true);
                    });
                },

                get currentIndex() { return this.syllabus.findIndex(item => item.id === this.activeItem.id); },
                get completedCount() { return this.syllabus.filter(item => item.completed).length; },
                get progressPercentage() { return this.syllabus.length ? (this.completedCount / this.syllabus.length) * 100 : 0; },

                selectItem(id) {
                    const item = this.syllabus.find(i => i.id === id);
                    if (!item || item.locked) return;
                    this.changeContent(item);
                    if (window.innerWidth < 768) this.sidebarOpen = false;
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
                            }).then(response => {
                                if (!response.ok) console.error('Gagal menyimpan progres di server.');
                            }).catch(error => console.error('Koneksi terputus', error));
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
                        // Jika selesai, kirim durasi materi terakhir dulu
                        this.sendLogTime();

                        Swal.fire({
                            icon: 'success', 
                            title: 'Selesai!',
                            text: 'Selamat! Kamu telah menyelesaikan bab ini.',
                            confirmButtonColor: '#0d52a1',
                            customClass: { popup: 'rounded-[2rem] font-sans' }
                        }).then(() => {
                            window.location.href = "{{ isset($isPreview) && $isPreview ? route('lms.assignments.index') : route('students.learning.index') }}";
                        });
                    }
                },

                changeContent(newItem) {
                    // Kirim rekapan waktu item yang lama ke server sebelum pindah
                    if (this.activeItem && this.activeItem.id !== newItem.id) {
                        this.sendLogTime();
                    }
                    this.isTransitioning = true;
                    
                    // Cleanup Video Player
                    if(this.youtubePlayer && typeof this.youtubePlayer.destroy === 'function') {
                        this.youtubePlayer.destroy();
                        this.youtubePlayer = null;
                    }
                    this.stopVideoWatcher();
                    this.activeInteractiveQuiz = null;

                    setTimeout(() => {
                        this.activeItem = newItem;
                        this.isTransitioning = false;
                        
                        const mainContainer = document.querySelector('main > div.overflow-y-auto');
                        if(mainContainer) mainContainer.scrollTop = 0;

                        if (newItem.type === 'assignment' && newItem.assignment_type === 'interactive_video') {
                            this.initInteractiveVideo(newItem);
                        }
                    }, 300);
                },

                 // Fungsi Mengirim Waktu ke Server
                sendLogTime(isUnloading = false) {
                    @if(!isset($isPreview))
                    if (!this.activeItem || this.activeItem.type === 'assignment') return;

                    // Hitung durasi (detik) sejak log terakhir
                    this.timeSpent = Math.floor((Date.now() - this.lastLogTime) / 1000);
                    
                    // Jangan kirim jika terlalu cepat (misal kurang dari 3 detik)
                    if (this.timeSpent < 3) {
                        this.lastLogTime = Date.now();
                        return;
                    }

                    const materialId = this.activeItem.db_id;
                    const timeSpent = this.timeSpent;

                    if (isUnloading && navigator.sendBeacon) {
                        // Gunakan sendBeacon karena ini dipicu saat tab browser ditutup
                        let formData = new FormData();
                        formData.append('material_id', materialId);
                        formData.append('time_spent', timeSpent);
                        formData.append('_token', '{{ csrf_token() }}');
                        navigator.sendBeacon('{{ route("students.learning.log-time") }}', formData);
                    } else {
                        // Gunakan fetch biasa jika sekadar pindah materi
                        fetch('{{ route("students.learning.log-time") }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ material_id: materialId, time_spent: timeSpent })
                        }).catch(err => console.error('Gagal log waktu:', err));
                    }

                    // Reset Timer untuk materi selanjutnya
                    this.lastLogTime = Date.now();
                    this.timeSpent = 0;
                    @endif
                },

                // -------------------------------------------------------------
                // FUNGSI KHUSUS VIDEO INTERAKTIF (YOUTUBE API)
                // -------------------------------------------------------------

                initInteractiveVideo(item) {
                    console.log("Inisialisasi Video Interaktif:", item.title);
                    
                    // 1. Parsing Data Soal dengan Aman
                    let parsedQuestions = [];
                    // Kadang dari backend namanya 'questions', kadang 'interactive_questions'
                    let rawQuestions = item.questions || item.interactive_questions || [];
                    
                    if (Array.isArray(rawQuestions) && rawQuestions.length > 0) {
                        parsedQuestions = rawQuestions.map(q => {
                            let optionsObj = q.options;
                            if (typeof optionsObj === 'string') {
                                try { optionsObj = JSON.parse(optionsObj); } catch(e) { optionsObj = {}; }
                            }
                            return {
                                id: q.id,
                                text: q.question_text || q.text,
                                options: optionsObj || {},
                                correct: q.correct_answer || q.correct,
                                time_trigger: (optionsObj && optionsObj.time_trigger) ? parseInt(optionsObj.time_trigger) : 0,
                                is_solved: false
                            };
                        });
                        console.log("Berhasil memuat", parsedQuestions.length, "soal interaktif.");
                    } else {
                        console.warn("AWAS: Tidak ada data kuis interaktif yang dimuat! (Array kosong)");
                    }
                    
                    this.interactiveQuestionsState = parsedQuestions.sort((a, b) => a.time_trigger - b.time_trigger);

                    // 2. Ekstrak Video ID
                    const videoId = this.extractYoutubeId(item.link_url || item.file_url || '');
                    if (!videoId) {
                        console.error("Gagal mendapatkan ID YouTube dari URL:", item.link_url);
                        return;
                    }

                    // 3. Menunggu DOM Siap (Karena terbungkus <template x-if>)
                    let checkDomAttempts = 0;
                    let checkDom = setInterval(() => {
                        checkDomAttempts++;
                        let container = document.getElementById('interactive-youtube-container');
                        
                        if (container) {
                            clearInterval(checkDom);
                            this.loadYoutubeScriptAndStart(videoId);
                        } else if (checkDomAttempts > 30) { // Timeout setelah 3 detik
                            clearInterval(checkDom);
                            console.error("Error: Container YouTube API lambat dirender oleh AlpineJS.");
                        }
                    }, 100);
                },

                loadYoutubeScriptAndStart(videoId) {
                    const startPlayer = () => {
                        // Pastikan bersih sebelum merender ulang iframe
                        let container = document.getElementById('interactive-youtube-container');
                        if (container) container.innerHTML = '';
                        
                        this.youtubePlayer = new YT.Player('interactive-youtube-container', {
                            videoId: videoId,
                            playerVars: { 
                                'controls': 0, 'disablekb': 1, 'rel': 0, 'modestbranding': 1, 'playsinline': 1 
                            },
                            events: {
                                'onReady': () => console.log("YouTube Player API Siap!"),
                                'onStateChange': this.onPlayerStateChange.bind(this)
                            }
                        });
                    };

                    // Cek apakah script YT sudah ter-load di browser
                    if (window.YT && window.YT.Player) {
                        startPlayer();
                    } else {
                        if (!document.getElementById('yt-api-script')) {
                            let tag = document.createElement('script');
                            tag.id = 'yt-api-script';
                            tag.src = "https://www.youtube.com/iframe_api";
                            document.head.appendChild(tag);
                        }
                        
                        let checkYt = setInterval(() => {
                            if (window.YT && window.YT.Player) {
                                clearInterval(checkYt);
                                startPlayer();
                            }
                        }, 500);
                    }
                },

                onPlayerStateChange(event) {
                    if (event.data == YT.PlayerState.PLAYING) {
                        this.startVideoWatcher();
                    } else {
                        this.stopVideoWatcher();
                        
                        // FALLBACK: Jika video sudah tamat (ENDED) tapi ada soal yang tidak ter-trigger
                        // (Misal guru salah memasukkan menit/detik melebihi durasi video asli)
                        if (event.data == YT.PlayerState.ENDED) {
                            if (this.interactiveQuestionsState.length > 0) {
                                this.interactiveQuestionsState.forEach(q => q.is_solved = true);
                                this.interactiveQuestionsState = [...this.interactiveQuestionsState];
                            }
                        }
                    }
                },

                startVideoWatcher() {
                    this.stopVideoWatcher(); // Hapus interval lama jika ada
                    
                    this.videoWatcher = setInterval(() => {
                        if(!this.youtubePlayer || typeof this.youtubePlayer.getCurrentTime !== 'function') return;
                        
                        let currentTime = this.youtubePlayer.getCurrentTime();
                        
                        let pendingQ = this.interactiveQuestionsState.find(q => !q.is_solved && currentTime >= q.time_trigger);
                        
                        if (pendingQ) {
                            // Cek jika siswa mencoba lompat waktu melewati batas kuis
                            if (currentTime > pendingQ.time_trigger + 1) {
                                this.youtubePlayer.seekTo(pendingQ.time_trigger);
                            }
                            
                            this.youtubePlayer.pauseVideo();
                            
                            // Mencegah flickering UI
                            if (this.activeInteractiveQuiz !== pendingQ) {
                                this.activeInteractiveQuiz = pendingQ;
                                this.selectedInteractiveAnswer = null;
                            }
                        }
                    }, 500); 
                },

                stopVideoWatcher() {
                    if (this.videoWatcher) clearInterval(this.videoWatcher);
                },

                submitInteractiveAnswer() {
                    if (!this.selectedInteractiveAnswer || !this.activeInteractiveQuiz) return;
                    
                    if (this.selectedInteractiveAnswer === this.activeInteractiveQuiz.correct) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Benar!',
                            text: 'Kerja bagus, silakan lanjut menonton.',
                            timer: 1500,
                            showConfirmButton: false,
                            customClass: { popup: 'rounded-[2rem] font-sans' }
                        });
                        
                        this.activeInteractiveQuiz.is_solved = true;
                        
                        // FIX: Paksa pembaruan state Alpine agar form "Selesaikan Tugas" langsung muncul
                        this.interactiveQuestionsState = [...this.interactiveQuestionsState];

                        this.activeInteractiveQuiz = null;
                        this.youtubePlayer.playVideo();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Jawaban Kurang Tepat',
                            text: 'Coba pahami kembali materinya.',
                            confirmButtonColor: '#D13438',
                            customClass: { popup: 'rounded-[2rem] font-sans' }
                        });
                        // Hukuman jika salah (Opsional): Mundur 5 Detik
                        let punishTime = Math.max(0, this.activeInteractiveQuiz.time_trigger - 5);
                        this.youtubePlayer.seekTo(punishTime);
                    }
                }
            }));
        });
    </script>
</x-student-learning-layout>