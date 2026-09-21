<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Buat Tugas Baru') }}
        </h2>
    </x-slot>

    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>

    {{-- CSS QUILL DARK THEME --}}
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        .ql-toolbar.ql-snow { border: 1px solid rgba(255, 255, 255, 0.1) !important; background-color: rgba(15, 23, 42, 0.9); border-top-left-radius: 1rem; border-top-right-radius: 1rem; }
        .ql-container.ql-snow { border: 1px solid rgba(255, 255, 255, 0.1) !important; border-top: none !important; background-color: rgba(15, 23, 42, 0.6); border-bottom-left-radius: 1rem; border-bottom-right-radius: 1rem; color: #f8fafc; }
        .ql-editor { font-size: 0.875rem; line-height: 1.6; padding: 1.25rem; min-height: 150px; }
        .ql-editor.ql-blank::before { font-style: normal; color: #64748b; }
        .ql-snow .ql-stroke { stroke: #94a3b8 !important; }
        .ql-snow .ql-fill { fill: #94a3b8 !important; }
        .ql-snow .ql-picker { color: #94a3b8 !important; }
        .ql-snow .ql-picker-options { background-color: #0f172a !important; border-color: rgba(255, 255, 255, 0.1) !important; }
    </style>

    <div class="py-8 sm:py-10 font-sans text-slate-100 bg-[#020b18] min-h-screen relative overflow-hidden pb-20">
        
        {{-- Efek Latar Belakang Halus --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-gradient-to-b from-[#0d52a1]/20 via-[#031d3d]/10 to-transparent opacity-30 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- HERO HEADER --}}
            <x-hero-section
                badge="LMS Tugas & Penilaian"
                badgeIcon="ph-clipboard-text"
                title="Setting Penugasan"
                titleHighlight="Tugas Baru Siswa"
                description="Atur detail penugasan, instruksi kerja, batas waktu pengumpulan, dan bobot nilai untuk siswa."
                :chips="[
                    ['icon' => 'ph-calendar-check', 'label' => 'Batas Deadline'],
                    ['icon' => 'ph-users-three', 'label' => 'Target Kelas'],
                    ['icon' => 'ph-file-arrow-up', 'label' => 'Format Pengumpulan']
                ]"
                heroIcon="ph-clipboard-text"
                showcaseValue="Baru"
                showcaseLabel="Tugas Baru"
                showcaseSubtitle="Kuis & Asesmen"
                statusOrb="Form Input"
                statusColor="sky"
            >
                <x-slot:cta>
                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ route('lms.assignments.index') }}" class="px-5 py-2.5 bg-white/10 hover:bg-white/20 text-slate-200 hover:text-white font-bold text-xs rounded-xl border border-white/15 backdrop-blur-md hover:scale-[1.02] transition-all flex items-center gap-2">
                            <i class="ph-bold ph-arrow-left text-base text-sky-400"></i> Kembali ke Tugas
                        </a>
                        <a href="{{ route('dashboard') }}" class="px-4 py-2.5 bg-white/5 hover:bg-white/10 text-slate-300 hover:text-white font-bold text-xs rounded-xl border border-white/10 transition-all flex items-center gap-1.5">
                            <i class="ph-bold ph-squares-four text-sm text-sky-400"></i> Dashboard
                        </a>
                    </div>
                </x-slot:cta>
            </x-hero-section>

            {{-- ERROR BLOCK --}}
            @if ($errors->any())
                <div class="animate-enter mb-8 bg-rose-500/10 border border-rose-500/30 p-5 rounded-[1.5rem] flex items-start gap-4 shadow-sm backdrop-blur-md">
                    <div class="w-10 h-10 bg-rose-500/20 text-rose-400 rounded-xl shrink-0 border border-rose-500/30 flex items-center justify-center">
                        <i class="ph-bold ph-warning-octagon text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xs font-black text-rose-400 uppercase tracking-wider mb-1 mt-1">Gagal Menyimpan</h3>
                        <ul class="list-disc list-inside text-sm text-rose-300 space-y-1 font-bold">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- INFO ALUR BELAJAR --}}
            <div class="animate-enter mb-8 bg-sky-500/10 border border-sky-400/20 p-5 rounded-[2rem] flex flex-col md:flex-row items-start md:items-center gap-4 shadow-sm backdrop-blur-md">
                <div class="w-12 h-12 bg-sky-500/20 text-sky-300 rounded-2xl shrink-0 flex items-center justify-center text-2xl border border-sky-400/30">
                    <i class="ph-duotone ph-book-open"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-black text-sky-200 mb-1">Struktur Bab Pembelajaran</h3>
                    <p class="text-xs font-medium text-slate-300 leading-relaxed">
                        Tugas ini akan diletakkan di dalam <b class="text-sky-300">Bab/Pokok Bahasan</b> yang Anda pilih. Siswa hanya akan melihat tugas ini ketika mereka sedang mempelajari Bab tersebut di Learning Player.
                    </p>
                </div>
            </div>

            {{-- FORM CARD --}}
            <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] shadow-2xl border border-white/10 overflow-hidden text-slate-100 backdrop-blur-xl">
                <form action="{{ route('lms.assignments.store') }}" method="POST" id="createAssignmentForm" 
                      x-data="{ 
                          targetType: '{{ old('target_type', 'class') }}', 
                          assignmentType: '{{ old('assignment_type', 'file_upload') }}', 
                          questions: [],
                          interactiveQuestions: [],
                          selectedSubject: '{{ old('subject_id') }}',
                          topics: [],
                          selectedTopic: '{{ old('topic_id') }}',
                          
                          fetchTopics() {
                              if(!this.selectedSubject) {
                                  this.topics = [];
                                  this.selectedTopic = '';
                                  return;
                              }
                              fetch('/lms/api/subjects/' + this.selectedSubject + '/topics')
                                  .then(response => response.json())
                                  .then(data => {
                                      this.topics = data;
                                      if(!this.topics.find(t => t.id == this.selectedTopic)) {
                                          this.selectedTopic = '';
                                      }
                                  })
                                  .catch(error => console.error('Error fetching topics:', error));
                          }
                      }"
                      x-init="if(selectedSubject) fetchTopics()">
                    @csrf

                    <div class="p-6 md:p-10 space-y-10">
                        
                        <!-- 1. IDENTITAS TUGAS -->
                        <div>
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-12 h-12 rounded-xl bg-sky-500/20 text-sky-400 flex items-center justify-center text-2xl border border-sky-400/30 shadow-sm"><i class="ph-bold ph-info"></i></div>
                                <h3 class="text-xl font-black text-white">Informasi Dasar</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="col-span-2">
                                    <label class="block text-[10px] font-bold text-sky-400 uppercase tracking-widest mb-2 ml-1">Judul Tugas <span class="text-rose-400">*</span></label>
                                    <input type="text" name="title" value="{{ old('title') }}" required class="w-full rounded-2xl border-white/10 bg-slate-900/80 font-black text-white focus:bg-slate-900 focus:ring-sky-400/20 focus:border-sky-400 h-14 px-5 placeholder:font-bold placeholder:text-slate-500 transition-colors shadow-sm" placeholder="Contoh: Ulangan Harian Bab 1">
                                </div>

                                <!-- MAPEL -->
                                <div>
                                    <label class="block text-[10px] font-bold text-sky-400 uppercase tracking-widest mb-2 ml-1">Mata Pelajaran <span class="text-rose-400">*</span></label>
                                    <div class="relative group">
                                        <select name="subject_id" x-model="selectedSubject" @change="fetchTopics()" required class="w-full rounded-2xl border-white/10 bg-slate-900/80 font-bold text-white focus:bg-slate-900 focus:ring-sky-400/20 focus:border-sky-400 h-14 px-5 appearance-none transition-colors cursor-pointer shadow-sm [color-scheme:dark]">
                                            <option value="" class="bg-slate-900 text-white">-- Pilih Mapel --</option>
                                            @foreach($subjects as $subject)
                                                <option value="{{ $subject->id }}" class="bg-slate-900 text-white">{{ $subject->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-sky-400"><i class="ph-bold ph-caret-down text-lg"></i></div>
                                    </div>
                                </div>

                                <!-- POKOK BAHASAN (BAB) -->
                                <div>
                                    <label class="block text-[10px] font-bold text-sky-400 uppercase tracking-widest mb-2 ml-1 flex items-center gap-1.5">
                                        Pokok Bahasan / Bab <span class="text-rose-400">*</span>
                                        <span x-show="selectedSubject && topics.length === 0" class="text-xs normal-case text-amber-400"><i class="ph-fill ph-warning-circle"></i> Belum ada bab</span>
                                    </label>
                                    <div class="relative group">
                                        <select name="topic_id" x-model="selectedTopic" :disabled="topics.length === 0" required class="w-full rounded-2xl border-white/10 bg-slate-900/80 font-bold text-white focus:bg-slate-900 focus:ring-sky-400/20 focus:border-sky-400 h-14 px-5 appearance-none transition-colors cursor-pointer shadow-sm disabled:opacity-50 disabled:cursor-not-allowed [color-scheme:dark]">
                                            <option value="" class="bg-slate-900 text-white">-- Pilih Bab Terlebih Dahulu --</option>
                                            <template x-for="topic in topics" :key="topic.id">
                                                <option :value="topic.id" x-text="topic.title" class="bg-slate-900 text-white"></option>
                                            </template>
                                        </select>
                                        <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-sky-400"><i class="ph-bold ph-caret-down text-lg"></i></div>
                                    </div>
                                    <p class="text-[10px] font-bold text-slate-400 mt-2 ml-1" x-show="!selectedSubject">Silakan pilih Mata Pelajaran terlebih dahulu.</p>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-sky-400 uppercase tracking-widest mb-2 ml-1">Deadline <span class="text-rose-400">*</span></label>
                                    <input type="datetime-local" name="deadline" value="{{ old('deadline') }}" required class="w-full rounded-2xl border-white/10 bg-slate-900/80 font-bold text-white focus:bg-slate-900 focus:ring-sky-400/20 focus:border-sky-400 h-14 px-5 transition-colors shadow-sm [color-scheme:dark]">
                                </div>

                                <div class="col-span-2 md:col-span-1 flex items-center mt-4">
                                    <label class="inline-flex items-center cursor-pointer group">
                                        <div class="relative">
                                            <input type="checkbox" name="allow_late_submission" class="sr-only peer" {{ old('allow_late_submission') ? 'checked' : '' }}>
                                            <div class="w-12 h-7 bg-slate-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-700 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-sky-500 shadow-inner"></div>
                                        </div>
                                        <span class="ml-3 text-sm font-bold text-slate-400 group-hover:text-sky-400 transition-colors">Izinkan pengumpulan terlambat</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="h-px bg-white/10"></div>

                        <!-- 2. PILIHAN TIPE TUGAS -->
                        <div>
                            <label class="block text-[10px] font-bold text-sky-400 uppercase tracking-widest mb-4 ml-1">Jenis Penugasan <span class="text-rose-400">*</span></label>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                                {{-- Card 1: Upload File --}}
                                <label class="cursor-pointer group relative">
                                    <input type="radio" name="assignment_type" value="file_upload" x-model="assignmentType" class="peer sr-only">
                                    <div class="p-6 rounded-2xl border-2 border-white/10 bg-slate-900/60 hover:border-sky-400/50 transition-all peer-checked:border-sky-400 peer-checked:bg-sky-500/10 peer-checked:shadow-md flex flex-col items-center justify-center text-center h-full gap-4">
                                        <div class="w-14 h-14 rounded-2xl bg-slate-800 border border-white/10 text-slate-400 flex items-center justify-center text-3xl peer-checked:bg-sky-500 peer-checked:border-sky-400 peer-checked:text-slate-950 transition-colors shadow-sm">
                                            <i class="ph-duotone ph-upload-simple"></i>
                                        </div>
                                        <div><span class="block font-black text-white peer-checked:text-sky-300 text-sm sm:text-base">Upload File</span></div>
                                        <div class="absolute top-4 right-4 text-sky-400 opacity-0 peer-checked:opacity-100 transition-opacity"><i class="ph-fill ph-check-circle text-xl"></i></div>
                                    </div>
                                </label>

                                {{-- Card 2: Kuis Online --}}
                                <label class="cursor-pointer group relative">
                                    <input type="radio" name="assignment_type" value="quiz" x-model="assignmentType" class="peer sr-only">
                                    <div class="p-6 rounded-2xl border-2 border-white/10 bg-slate-900/60 hover:border-purple-400/50 transition-all peer-checked:border-purple-400 peer-checked:bg-purple-500/10 peer-checked:shadow-md flex flex-col items-center justify-center text-center h-full gap-4">
                                        <div class="w-14 h-14 rounded-2xl bg-slate-800 border border-white/10 text-slate-400 flex items-center justify-center text-3xl peer-checked:bg-purple-500 peer-checked:border-purple-400 peer-checked:text-white transition-colors shadow-sm">
                                            <i class="ph-duotone ph-brain"></i>
                                        </div>
                                        <div><span class="block font-black text-white peer-checked:text-purple-300 text-sm sm:text-base">Kuis Online</span></div>
                                        <div class="absolute top-4 right-4 text-purple-400 opacity-0 peer-checked:opacity-100 transition-opacity"><i class="ph-fill ph-check-circle text-xl"></i></div>
                                    </div>
                                </label>

                                {{-- Card 3: Link Eksternal --}}
                                <label class="cursor-pointer group relative">
                                    <input type="radio" name="assignment_type" value="link" x-model="assignmentType" class="peer sr-only">
                                    <div class="p-6 rounded-2xl border-2 border-white/10 bg-slate-900/60 hover:border-amber-400/50 transition-all peer-checked:border-amber-400 peer-checked:bg-amber-500/10 peer-checked:shadow-md flex flex-col items-center justify-center text-center h-full gap-4">
                                        <div class="w-14 h-14 rounded-2xl bg-slate-800 border border-white/10 text-slate-400 flex items-center justify-center text-3xl peer-checked:bg-amber-500 peer-checked:border-amber-400 peer-checked:text-slate-950 transition-colors shadow-sm">
                                            <i class="ph-duotone ph-link"></i>
                                        </div>
                                        <div><span class="block font-black text-white peer-checked:text-amber-300 text-sm sm:text-base">Link Luar</span></div>
                                        <div class="absolute top-4 right-4 text-amber-400 opacity-0 peer-checked:opacity-100 transition-opacity"><i class="ph-fill ph-check-circle text-xl"></i></div>
                                    </div>
                                </label>

                                {{-- Card 4: Video Interaktif --}}
                                <label class="cursor-pointer group relative">
                                    <input type="radio" name="assignment_type" value="interactive_video" x-model="assignmentType" class="peer sr-only">
                                    <div class="p-6 rounded-2xl border-2 border-white/10 bg-slate-900/60 hover:border-rose-400/50 transition-all peer-checked:border-rose-400 peer-checked:bg-rose-500/10 peer-checked:shadow-md flex flex-col items-center justify-center text-center h-full gap-4">
                                        <div class="w-14 h-14 rounded-2xl bg-slate-800 border border-white/10 text-slate-400 flex items-center justify-center text-3xl peer-checked:bg-rose-500 peer-checked:border-rose-400 peer-checked:text-white transition-colors shadow-sm">
                                            <i class="ph-duotone ph-youtube-logo"></i>
                                        </div>
                                        <div><span class="block font-black text-white peer-checked:text-rose-300 text-sm sm:text-base">Video Interaktif</span></div>
                                        <div class="absolute top-4 right-4 text-rose-400 opacity-0 peer-checked:opacity-100 transition-opacity"><i class="ph-fill ph-check-circle text-xl"></i></div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- 3. KONTEN DINAMIS -->
                        <div class="bg-slate-900/60 rounded-[2rem] p-6 md:p-8 border border-white/10">
                                                    
                           <!-- A. JIKA UPLOAD FILE -->
                            <div x-show="assignmentType === 'file_upload'">
                                <label class="block text-[10px] font-bold text-sky-400 uppercase tracking-widest mb-2 ml-1">Instruksi / Soal <span class="text-rose-400">*</span></label>
                                <input type="hidden" name="description_file" id="desc-file-input" value="{{ old('description_file') }}">
                                <div class="rounded-2xl border border-white/10 overflow-hidden shadow-sm bg-slate-900/80">
                                    <div id="quill-file" class="text-slate-100 font-medium">{!! old('description_file') !!}</div>
                                </div>
                            </div>

                            <!-- B. JIKA LINK EKSTERNAL -->
                            <div x-show="assignmentType === 'link'" style="display: none;">
                                <div class="mb-5">
                                    <label class="block text-[10px] font-bold text-sky-400 uppercase tracking-widest mb-2 ml-1">Instruksi Tambahan <span class="text-rose-400">*</span></label>
                                    <input type="hidden" name="description_link" id="desc-link-input" value="{{ old('description_link') }}">
                                    <div class="rounded-2xl border border-white/10 overflow-hidden shadow-sm bg-slate-900/80 mb-4">
                                        <div id="quill-link" class="text-slate-100 font-medium">{!! old('description_link') !!}</div>
                                    </div>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-500 group-focus-within:text-amber-400"><i class="ph-bold ph-link text-lg"></i></div>
                                        <input type="url" name="link_url" value="{{ old('link_url') }}" 
                                               :required="assignmentType === 'link'" 
                                               :disabled="assignmentType !== 'link'"
                                               class="w-full rounded-2xl border-white/10 bg-slate-900/80 pl-12 font-bold text-amber-300 focus:ring-amber-400/20 focus:border-amber-400 h-14 transition-colors shadow-sm placeholder:text-slate-600" 
                                               placeholder="https://...">
                                    </div>
                                </div>
                            </div>

                            <!-- C. JIKA KUIS ONLINE -->
                            <div x-show="assignmentType === 'quiz'" style="display: none;">
                                <div class="mb-8 flex flex-col md:flex-row gap-5">
                                    <div class="flex-1">
                                        <label class="block text-[10px] font-bold text-purple-400 uppercase tracking-widest mb-2 ml-1">Instruksi Kuis <span class="text-rose-400">*</span></label>
                                        <textarea name="description_quiz" rows="2" :required="assignmentType === 'quiz'" :disabled="assignmentType !== 'quiz'" class="w-full rounded-2xl border-white/10 bg-slate-900/80 text-white focus:ring-purple-400/20 focus:border-purple-400 p-4 transition-colors shadow-sm placeholder:text-slate-500">{{ old('description_quiz') }}</textarea>
                                    </div>
                                    <div class="w-full md:w-1/3">
                                        <label class="block text-[10px] font-bold text-purple-400 uppercase tracking-widest mb-2 ml-1">Durasi (Menit) <span class="text-rose-400">*</span></label>
                                        <input type="number" name="duration_minutes" value="{{ old('duration_minutes', 60) }}" :required="assignmentType === 'quiz'" :disabled="assignmentType !== 'quiz'" class="w-full rounded-2xl border-white/10 bg-slate-900/80 font-black text-white focus:ring-purple-400/20 focus:border-purple-400 h-14 pl-5 transition-colors shadow-sm">
                                    </div>
                                </div>

                                <div class="space-y-5 mb-6">
                                    <template x-for="(q, index) in questions" :key="index">
                                        <div class="bg-slate-900/90 p-6 rounded-[2rem] border border-white/10 shadow-sm relative group">
                                            <button type="button" @click="questions = questions.filter((_, i) => i !== index)" class="absolute top-5 right-5 w-10 h-10 rounded-xl bg-rose-500/20 text-rose-300 border border-rose-500/30 flex items-center justify-center shadow-sm hover:bg-rose-500/30">
                                                <i class="ph-bold ph-trash text-lg"></i>
                                            </button>
                                            <div class="flex flex-col sm:flex-row gap-5">
                                                <span class="bg-purple-500/20 text-purple-300 border border-purple-500/30 w-12 h-12 flex items-center justify-center rounded-2xl font-black text-lg shrink-0 shadow-sm" x-text="index + 1"></span>
                                                <div class="flex-1 w-full pr-12 sm:pr-0">
                                                    <textarea :name="'questions['+index+'][text]'" x-model="q.text" required rows="2" class="w-full rounded-2xl border-white/10 text-base mb-3 focus:ring-purple-400/20 p-4 bg-slate-800 text-white focus:bg-slate-900" placeholder="Tuliskan pertanyaan..."></textarea>
                                                    <div class="space-y-3">
                                                        <template x-for="opt in ['A', 'B', 'C', 'D']">
                                                            <div class="flex items-center gap-3">
                                                                <input type="radio" :name="'questions['+index+'][correct]'" :value="opt" class="w-5 h-5 text-emerald-400 focus:ring-emerald-400 border-white/20 bg-slate-800">
                                                                <div class="flex-1 relative group/opt">
                                                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500 font-black" x-text="opt"></div>
                                                                    <input type="text" :name="'questions['+index+'][options]['+opt+']'" class="w-full rounded-xl border-white/10 bg-slate-800 text-sm py-3.5 pl-10 focus:bg-slate-900 focus:ring-purple-400/20 text-white font-semibold" :placeholder="'Pilihan Jawaban ' + opt">
                                                                </div>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                                <button type="button" @click="questions.push({type: 'multiple_choice', text: '', points: 10})" class="w-full py-4 border-2 border-dashed border-purple-500/30 bg-purple-500/10 text-purple-300 rounded-[1.5rem] font-bold text-sm hover:bg-purple-500/20 transition-all flex items-center justify-center gap-2">
                                    <i class="ph-bold ph-plus text-lg"></i> Tambah Pertanyaan Kuis
                                </button>
                            </div>

                            <!-- D. JIKA VIDEO INTERAKTIF -->
                            <div x-show="assignmentType === 'interactive_video'" style="display: none;">
                                <div class="mb-8">
                                    <label class="block text-[10px] font-bold text-rose-400 uppercase tracking-widest mb-2 ml-1">URL YouTube Video <span class="text-rose-400">*</span></label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-500 group-focus-within:text-rose-400"><i class="ph-bold ph-youtube-logo text-xl"></i></div>
                                        <input type="url" name="youtube_url" 
                                               :required="assignmentType === 'interactive_video'" 
                                               :disabled="assignmentType !== 'interactive_video'"
                                               class="w-full rounded-2xl border-white/10 bg-slate-900/80 pl-12 font-bold text-white focus:ring-rose-400/20 focus:border-rose-400 h-14 transition-colors shadow-sm placeholder:text-slate-500" 
                                               placeholder="Contoh: https://www.youtube.com/watch?v=xxxxxxx">
                                    </div>
                                    <p class="text-xs font-semibold text-slate-400 mt-2 ml-1">Pastikan video YouTube bersifat Publik atau Unlisted.</p>
                                </div>

                                <h4 class="font-black text-white text-lg mb-4 flex items-center gap-2"><i class="ph-fill ph-target text-rose-400"></i> Titik Kuis (Pemberhentian Video)</h4>

                                <div class="space-y-6 mb-6">
                                    <template x-for="(iq, index) in interactiveQuestions" :key="index">
                                        <div class="bg-slate-900/90 p-6 rounded-[2rem] border border-white/10 hover:border-rose-500/40 transition-all shadow-sm relative">
                                            
                                            <button type="button" @click="interactiveQuestions = interactiveQuestions.filter((_, i) => i !== index)" class="absolute top-5 right-5 w-10 h-10 rounded-xl bg-slate-800 text-slate-400 hover:bg-rose-500/20 hover:text-rose-300 border border-white/10 flex items-center justify-center transition-colors shadow-sm">
                                                <i class="ph-bold ph-trash text-lg"></i>
                                            </button>

                                            <div class="mb-5 flex flex-wrap items-end gap-3">
                                                <div class="w-full sm:w-auto">
                                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Muncul di Menit ke</label>
                                                    <input type="number" :name="'interactive_questions['+index+'][minute]'" x-model="iq.minute" min="0" required class="w-24 text-center rounded-xl border-white/10 bg-slate-800 focus:bg-slate-900 focus:border-rose-400 focus:ring-rose-400/20 font-black text-lg text-white shadow-sm">
                                                </div>
                                                <div class="pb-3 text-xl font-black text-slate-500">:</div>
                                                <div class="w-full sm:w-auto">
                                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Detik ke</label>
                                                    <input type="number" :name="'interactive_questions['+index+'][second]'" x-model="iq.second" min="0" max="59" required class="w-24 text-center rounded-xl border-white/10 bg-slate-800 focus:bg-slate-900 focus:border-rose-400 focus:ring-rose-400/20 font-black text-lg text-white shadow-sm">
                                                </div>
                                            </div>

                                            <div class="h-px bg-white/10 w-full mb-5"></div>

                                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Pertanyaan</label>
                                            <textarea :name="'interactive_questions['+index+'][text]'" x-model="iq.text" required rows="2" class="w-full rounded-2xl border-white/10 text-base mb-4 focus:ring-rose-400/20 focus:border-rose-400 font-medium shadow-sm p-4 bg-slate-800 text-white focus:bg-slate-900 transition-colors" placeholder="Tuliskan pertanyaan kuis di sini..."></textarea>
                                            
                                            <div class="space-y-3">
                                                <template x-for="opt in ['A', 'B', 'C', 'D']">
                                                    <div class="flex items-center gap-3">
                                                        <input type="radio" :name="'interactive_questions['+index+'][correct]'" :value="opt" required class="w-5 h-5 text-emerald-400 focus:ring-emerald-400 border-white/20 bg-slate-800 cursor-pointer">
                                                        <div class="flex-1 relative">
                                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500 text-sm font-black" x-text="opt"></div>
                                                            <input type="text" :name="'interactive_questions['+index+'][options]['+opt+']'" required class="w-full rounded-xl border-white/10 bg-slate-800 text-sm py-3.5 pl-10 focus:bg-slate-900 focus:ring-rose-400/20 focus:border-rose-400 transition-colors font-semibold text-white" :placeholder="'Pilihan ' + opt">
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                            <p class="text-[10px] font-bold text-slate-400 mt-3"><i class="ph-fill ph-info text-sky-400"></i> Klik bulatan di sebelah kiri untuk menentukan kunci jawaban benar.</p>
                                        </div>
                                    </template>
                                </div>

                                <button type="button" @click="interactiveQuestions.push({ minute: 0, second: 0, text: '', correct: 'A' })" 
                                        class="w-full py-4 border-2 border-dashed border-rose-500/30 bg-rose-500/10 text-rose-300 rounded-[1.5rem] font-bold text-sm hover:bg-rose-500/20 transition-all flex items-center justify-center gap-2 active:scale-95">
                                    <i class="ph-bold ph-plus text-lg"></i> Tambah Titik Pemberhentian Kuis
                                </button>
                            </div>

                        </div>

                        <!-- 4. TARGET PENERIMA -->
                        <div class="bg-slate-900/60 p-6 md:p-8 rounded-[2rem] border border-white/10">
                            <label class="block text-xs font-black text-sky-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                <i class="ph-fill ph-users-three text-lg"></i> Target Penerima <span class="text-rose-400">*</span>
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="flex flex-col sm:flex-row gap-4">
                                    <label class="flex-1 inline-flex items-center cursor-pointer group bg-slate-900/80 px-4 py-3.5 border border-white/10 rounded-xl hover:border-sky-400/50 shadow-sm transition-all">
                                        <div class="relative flex items-center">
                                            <input type="radio" name="target_type" value="class" x-model="targetType" class="peer sr-only">
                                            <div class="w-5 h-5 border-2 border-slate-600 rounded-full peer-checked:border-sky-400 peer-checked:bg-sky-400 transition-colors"></div>
                                        </div>
                                        <span class="ml-3 text-sm font-black text-slate-200 group-hover:text-sky-300 transition-colors">Satu Kelas</span>
                                    </label>
                                    <label class="flex-1 inline-flex items-center cursor-pointer group bg-slate-900/80 px-4 py-3.5 border border-white/10 rounded-xl hover:border-sky-400/50 shadow-sm transition-all">
                                        <div class="relative flex items-center">
                                            <input type="radio" name="target_type" value="grade" x-model="targetType" class="peer sr-only">
                                            <div class="w-5 h-5 border-2 border-slate-600 rounded-full peer-checked:border-sky-400 peer-checked:bg-sky-400 transition-colors"></div>
                                        </div>
                                        <span class="ml-3 text-sm font-black text-slate-200 group-hover:text-sky-300 transition-colors">Satu Jenjang</span>
                                    </label>
                                </div>

                                <div>
                                    <div x-show="targetType === 'class'">
                                        <select name="class_id" :required="targetType === 'class'" :disabled="targetType !== 'class'" class="w-full text-sm font-bold rounded-xl border-white/10 bg-slate-900/80 focus:ring-sky-400/20 focus:border-sky-400 h-14 px-5 shadow-sm text-white transition-colors [color-scheme:dark]">
                                            <option value="" class="bg-slate-900 text-white">-- Pilih Kelas --</option>
                                            @foreach($classes as $class)
                                                <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }} class="bg-slate-900 text-white">{{ $class->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div x-show="targetType === 'grade'" style="display: none;">
                                        <select name="target_grade" :required="targetType === 'grade'" :disabled="targetType !== 'grade'" class="w-full text-sm font-bold rounded-xl border-white/10 bg-slate-900/80 focus:ring-sky-400/20 focus:border-sky-400 h-14 px-5 shadow-sm text-white transition-colors [color-scheme:dark]">
                                            <option value="7" class="bg-slate-900 text-white">Kelas 7</option>
                                            <option value="8" class="bg-slate-900 text-white">Kelas 8</option>
                                            <option value="9" class="bg-slate-900 text-white">Kelas 9</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- FOOTER ACTIONS -->
                    <div class="bg-slate-900/80 px-6 py-6 md:px-10 md:py-8 flex flex-col sm:flex-row justify-end gap-4 border-t border-white/10">
                        <a href="{{ route('lms.assignments.index') }}" class="w-full sm:w-auto px-8 py-4 bg-slate-800 border border-white/10 text-slate-300 font-bold rounded-2xl hover:bg-slate-700 hover:text-white transition-colors text-center text-sm shadow-sm active:scale-95">Batal</a>
                        <button type="submit" class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-sky-400 to-blue-600 text-slate-950 font-bold rounded-2xl shadow-lg shadow-sky-500/20 hover:from-sky-300 hover:to-blue-500 transition-all flex items-center justify-center gap-2 text-sm active:scale-95">
                            <i class="ph-bold ph-paper-plane-tilt text-lg"></i> <span>Terbitkan Tugas</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- SCRIPT SWEETALERT2 --}}
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('createAssignmentForm');
            if(form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    if (!this.checkValidity()) { 
                        this.reportValidity(); 
                        return; 
                    }
                    Swal.fire({
                        title: 'Sedang Menerbitkan...',
                        text: 'Mohon tunggu sebentar.',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        background: '#021124',
                        color: '#fff',
                        didOpen: () => { Swal.showLoading(); },
                        customClass: { popup: 'rounded-[2rem] font-sans border border-white/10 bg-[#021124] text-white shadow-2xl', title: 'text-xl font-black text-white' }
                    });
                    setTimeout(() => { this.submit(); }, 500);
                });
            }
        });
    </script>

    <!-- Script Quill JS -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function initQuill(selector, placeholderText) {
                if(document.querySelector(selector)) {
                    return new Quill(selector, {
                        theme: 'snow',
                        placeholder: placeholderText,
                        modules: {
                            toolbar: [
                                ['bold', 'italic', 'underline'],
                                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                                ['link', 'image'],
                                ['clean']
                            ]
                        }
                    });
                }
                return null;
            }

            var quillFile = initQuill('#quill-file', 'Tuliskan instruksi tugas secara rinci...');
            var quillLink = initQuill('#quill-link', 'Tuliskan instruksi tambahan di sini...');
            var quillEdit = initQuill('#quill-edit', 'Edit instruksi tugas...');

            var formCreate = document.getElementById('createAssignmentForm');
            var formEdit = document.getElementById('editAssignmentForm');

            function syncQuillData(quillInstance, inputId) {
                if(quillInstance && document.getElementById(inputId)) {
                    var html = quillInstance.root.innerHTML;
                    document.getElementById(inputId).value = html === '<p><br></p>' ? '' : html;
                }
            }

            if(formCreate) {
                formCreate.addEventListener('submit', function() {
                    var type = document.querySelector('input[name="assignment_type"]:checked').value;
                    if(type === 'file_upload') syncQuillData(quillFile, 'desc-file-input');
                    if(type === 'link') syncQuillData(quillLink, 'desc-link-input');
                });
            }

            if(formEdit) {
                formEdit.addEventListener('submit', function() {
                     syncQuillData(quillEdit, 'desc-edit-input');
                });
            }
        });
    </script>
    @endpush
</x-app-layout>