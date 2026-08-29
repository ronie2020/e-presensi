<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-elevate-dark leading-tight">
            {{ __('Buat Tugas Baru') }}
        </h2>
    </x-slot>

    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>

    {{-- CSS QUILL --}}
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        .ql-toolbar.ql-snow { border: none !important; border-bottom: 1px solid #e2e8f0 !important; background-color: #f8fafc; font-family: inherit; border-top-left-radius: 1rem; border-top-right-radius: 1rem; }
        .ql-container.ql-snow { border: none !important; font-family: inherit; border-bottom-left-radius: 1rem; border-bottom-right-radius: 1rem;}
        .ql-editor { font-size: 0.875rem; line-height: 1.6; padding: 1.25rem; min-height: 150px; }
        .ql-editor.ql-blank::before { font-style: normal; color: #94a3b8; }
    </style>

    <div class="py-8 sm:py-10 font-sans text-elevate-dark bg-elevate-surface min-h-screen relative overflow-hidden pb-20">
        
        {{-- Efek Latar Belakang Halus --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- HERO HEADER ELEVATE --}}
            <div class="relative rounded-[2rem] bg-gradient-to-r from-elevate-accent via-elevate-peach-light to-elevate-peach p-8 md:p-10 mb-8 text-elevate-dark shadow-xl shadow-elevate-accent/20 overflow-hidden border border-white/60 group">
                <div class="absolute -top-10 -left-10 w-56 h-56 bg-elevate-primary/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-elevate-peach/40 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-xl"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <h1 class="text-3xl md:text-4xl font-black mb-2 tracking-tight">Setting Penugasan</h1>
                        <p class="text-elevate-dark/80 text-sm font-semibold">Atur detail tugas, kuis, atau instruksi untuk siswa.</p>
                    </div>
                    <a href="{{ route('lms.assignments.index') }}" class="w-full md:w-auto inline-flex justify-center items-center gap-2 px-6 py-3.5 bg-white/60 hover:bg-white rounded-xl text-sm font-bold backdrop-blur-md transition-colors text-elevate-dark border border-white/60 shadow-sm active:scale-95 btn-cancel-confirm shrink-0">
                        <i class="ph-bold ph-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>

            {{-- ERROR BLOCK --}}
            @if ($errors->any())
                <div class="animate-enter mb-8 bg-[#FDE7E9] border border-[#F4C3C9] p-5 rounded-[1.5rem] flex items-start gap-4 shadow-sm">
                    <div class="w-10 h-10 bg-white text-[#D13438] rounded-xl shrink-0 shadow-sm border border-[#F4C3C9] flex items-center justify-center">
                        <i class="ph-bold ph-warning-octagon text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xs font-black text-[#D13438] uppercase tracking-wider mb-1 mt-1">Gagal Menyimpan</h3>
                        <ul class="list-disc list-inside text-sm text-[#D13438] space-y-1 font-bold">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- INFO ALUR BELAJAR (Diperbarui untuk Basis Modul) --}}
            <div class="animate-enter mb-8 bg-blue-50 border border-blue-200 p-5 rounded-[2rem] flex flex-col md:flex-row items-start md:items-center gap-4 shadow-sm">
                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-2xl shrink-0 flex items-center justify-center text-2xl">
                    <i class="ph-duotone ph-book-open"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-black text-blue-900 mb-1">Struktur Bab Pembelajaran</h3>
                    <p class="text-xs font-medium text-blue-700 leading-relaxed">
                        Tugas ini akan diletakkan di dalam <b>Bab/Pokok Bahasan</b> yang Anda pilih. Siswa hanya akan melihat tugas ini ketika mereka sedang mempelajari Bab tersebut di Learning Player.
                    </p>
                </div>
            </div>

            {{-- FORM CARD --}}
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden">
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
                              // Mengambil data Bab berdasarkan mapel
                              fetch('/lms/api/subjects/' + this.selectedSubject + '/topics')
                                  .then(response => response.json())
                                  .then(data => {
                                      this.topics = data;
                                      // Cek jika topic lama masih ada di list
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
                                <div class="w-12 h-12 rounded-xl bg-elevate-peach-light/40 text-elevate-peach-dark flex items-center justify-center text-2xl border border-elevate-peach/30 shadow-sm"><i class="ph-bold ph-info"></i></div>
                                <h3 class="text-xl font-black text-elevate-dark">Informasi Dasar</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="col-span-2">
                                    <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">Judul Tugas <span class="text-[#D13438]">*</span></label>
                                    <input type="text" name="title" value="{{ old('title') }}" required class="w-full rounded-2xl border-slate-200 bg-elevate-soft font-black text-elevate-dark focus:bg-white focus:ring-elevate-accent/30 focus:border-elevate-accent h-14 px-5 placeholder:font-bold placeholder:text-slate-400 transition-colors shadow-sm" placeholder="Contoh: Ulangan Harian Bab 1">
                                </div>

                                <!-- MAPEL -->
                                <div>
                                    <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">Mata Pelajaran <span class="text-[#D13438]">*</span></label>
                                    <div class="relative group">
                                        <select name="subject_id" x-model="selectedSubject" @change="fetchTopics()" required class="w-full rounded-2xl border-slate-200 bg-elevate-soft font-bold text-elevate-dark focus:bg-white focus:ring-elevate-accent/30 focus:border-elevate-accent h-14 px-5 appearance-none transition-colors cursor-pointer shadow-sm">
                                            <option value="">-- Pilih Mapel --</option>
                                            @foreach($subjects as $subject)
                                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-slate-500 group-focus-within:text-elevate-primary"><i class="ph-bold ph-caret-down text-lg"></i></div>
                                    </div>
                                </div>

                                <!-- POKOK BAHASAN (BAB) -->
                                <div>
                                    <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1 flex items-center gap-1.5">
                                        Pokok Bahasan / Bab <span class="text-[#D13438]">*</span>
                                        <span x-show="selectedSubject && topics.length === 0" class="text-xs normal-case text-orange-500"><i class="ph-fill ph-warning-circle"></i> Belum ada bab</span>
                                    </label>
                                    <div class="relative group">
                                        <select name="topic_id" x-model="selectedTopic" :disabled="topics.length === 0" required class="w-full rounded-2xl border-slate-200 bg-elevate-soft font-bold text-elevate-dark focus:bg-white focus:ring-elevate-accent/30 focus:border-elevate-accent h-14 px-5 appearance-none transition-colors cursor-pointer shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                                            <option value="">-- Pilih Bab Terlebih Dahulu --</option>
                                            <template x-for="topic in topics" :key="topic.id">
                                                <option :value="topic.id" x-text="topic.title"></option>
                                            </template>
                                        </select>
                                        <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-slate-500 group-focus-within:text-elevate-primary"><i class="ph-bold ph-caret-down text-lg"></i></div>
                                    </div>
                                    <!-- Jika belum memilih mapel, berikan hint -->
                                    <p class="text-[10px] font-bold text-slate-400 mt-2 ml-1" x-show="!selectedSubject">Silakan pilih Mata Pelajaran terlebih dahulu.</p>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">Deadline <span class="text-[#D13438]">*</span></label>
                                    <input type="datetime-local" name="deadline" value="{{ old('deadline') }}" required class="w-full rounded-2xl border-slate-200 bg-elevate-soft font-bold text-elevate-dark focus:bg-white focus:ring-elevate-accent/30 focus:border-elevate-accent h-14 px-5 transition-colors shadow-sm">
                                </div>

                                <div class="col-span-2 md:col-span-1 flex items-center mt-4">
                                    <label class="inline-flex items-center cursor-pointer group">
                                        <div class="relative">
                                            <input type="checkbox" name="allow_late_submission" class="sr-only peer" {{ old('allow_late_submission') ? 'checked' : '' }}>
                                            <div class="w-12 h-7 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-elevate-primary shadow-inner"></div>
                                        </div>
                                        <span class="ml-3 text-sm font-bold text-slate-500 group-hover:text-elevate-primary transition-colors">Izinkan pengumpulan terlambat</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="h-px bg-slate-100"></div>

                        <!-- 2. PILIHAN TIPE TUGAS -->
                        <div>
                            <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-4 ml-1">Jenis Penugasan <span class="text-[#D13438]">*</span></label>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                                {{-- Card 1: Upload File --}}
                                <label class="cursor-pointer group relative">
                                    <input type="radio" name="assignment_type" value="file_upload" x-model="assignmentType" class="peer sr-only">
                                    <div class="p-6 rounded-2xl border-2 border-slate-100 bg-white hover:border-elevate-accent/50 hover:bg-elevate-soft/50 transition-all peer-checked:border-elevate-primary peer-checked:bg-elevate-soft/30 peer-checked:shadow-md flex flex-col items-center justify-center text-center h-full gap-4">
                                        <div class="w-14 h-14 rounded-2xl bg-slate-100 border border-slate-200 text-slate-500 flex items-center justify-center text-3xl peer-checked:bg-elevate-primary peer-checked:border-elevate-primary peer-checked:text-white transition-colors shadow-sm">
                                            <i class="ph-duotone ph-upload-simple"></i>
                                        </div>
                                        <div><span class="block font-black text-elevate-dark peer-checked:text-elevate-primary text-sm sm:text-base">Upload File</span></div>
                                        <div class="absolute top-4 right-4 text-elevate-primary opacity-0 peer-checked:opacity-100 transition-opacity"><i class="ph-fill ph-check-circle text-xl"></i></div>
                                    </div>
                                </label>

                                {{-- Card 2: Kuis Online --}}
                                <label class="cursor-pointer group relative">
                                    <input type="radio" name="assignment_type" value="quiz" x-model="assignmentType" class="peer sr-only">
                                    <div class="p-6 rounded-2xl border-2 border-slate-100 bg-white hover:border-purple-300 hover:bg-purple-50/50 transition-all peer-checked:border-purple-500 peer-checked:bg-purple-50/50 peer-checked:shadow-md flex flex-col items-center justify-center text-center h-full gap-4">
                                        <div class="w-14 h-14 rounded-2xl bg-slate-100 border border-slate-200 text-slate-500 flex items-center justify-center text-3xl peer-checked:bg-purple-600 peer-checked:border-purple-600 peer-checked:text-white transition-colors shadow-sm">
                                            <i class="ph-duotone ph-brain"></i>
                                        </div>
                                        <div><span class="block font-black text-elevate-dark peer-checked:text-purple-700 text-sm sm:text-base">Kuis Online</span></div>
                                        <div class="absolute top-4 right-4 text-purple-600 opacity-0 peer-checked:opacity-100 transition-opacity"><i class="ph-fill ph-check-circle text-xl"></i></div>
                                    </div>
                                </label>

                                {{-- Card 3: Link Eksternal --}}
                                <label class="cursor-pointer group relative">
                                    <input type="radio" name="assignment_type" value="link" x-model="assignmentType" class="peer sr-only">
                                    <div class="p-6 rounded-2xl border-2 border-slate-100 bg-white hover:border-[#FFD8A8] hover:bg-[#FFEFD6]/50 transition-all peer-checked:border-[#D83B01] peer-checked:bg-[#FFEFD6]/30 peer-checked:shadow-md flex flex-col items-center justify-center text-center h-full gap-4">
                                        <div class="w-14 h-14 rounded-2xl bg-slate-100 border border-slate-200 text-slate-500 flex items-center justify-center text-3xl peer-checked:bg-[#D83B01] peer-checked:border-[#D83B01] peer-checked:text-white transition-colors shadow-sm">
                                            <i class="ph-duotone ph-link"></i>
                                        </div>
                                        <div><span class="block font-black text-elevate-dark peer-checked:text-[#D83B01] text-sm sm:text-base">Link Luar</span></div>
                                        <div class="absolute top-4 right-4 text-[#D83B01] opacity-0 peer-checked:opacity-100 transition-opacity"><i class="ph-fill ph-check-circle text-xl"></i></div>
                                    </div>
                                </label>

                                {{-- Card 4: Video Interaktif --}}
                                <label class="cursor-pointer group relative">
                                    <input type="radio" name="assignment_type" value="interactive_video" x-model="assignmentType" class="peer sr-only">
                                    <div class="p-6 rounded-2xl border-2 border-slate-100 bg-white hover:border-red-300 hover:bg-red-50/50 transition-all peer-checked:border-red-600 peer-checked:bg-red-50/50 peer-checked:shadow-md flex flex-col items-center justify-center text-center h-full gap-4">
                                        <div class="w-14 h-14 rounded-2xl bg-slate-100 border border-slate-200 text-slate-500 flex items-center justify-center text-3xl peer-checked:bg-red-600 peer-checked:border-red-600 peer-checked:text-white transition-colors shadow-sm">
                                            <i class="ph-duotone ph-youtube-logo"></i>
                                        </div>
                                        <div><span class="block font-black text-elevate-dark peer-checked:text-red-700 text-sm sm:text-base">Video Interaktif</span></div>
                                        <div class="absolute top-4 right-4 text-red-600 opacity-0 peer-checked:opacity-100 transition-opacity"><i class="ph-fill ph-check-circle text-xl"></i></div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- 3. KONTEN DINAMIS -->
                        <div class="bg-elevate-soft/30 rounded-[2rem] p-6 md:p-8 border border-slate-100">
                                                    
                           <!-- A. JIKA UPLOAD FILE -->
                            <div x-show="assignmentType === 'file_upload'">
                                <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">Instruksi / Soal <span class="text-[#D13438]">*</span></label>
                                <input type="hidden" name="description_file" id="desc-file-input" value="{{ old('description_file') }}">
                                <div class="rounded-2xl border border-slate-200 overflow-hidden shadow-sm bg-white">
                                    <div id="quill-file" class="text-elevate-dark font-medium">{!! old('description_file') !!}</div>
                                </div>
                            </div>

                            <!-- B. JIKA LINK EKSTERNAL -->
                            <div x-show="assignmentType === 'link'" style="display: none;">
                                <div class="mb-5">
                                    <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">Instruksi Tambahan <span class="text-[#D13438]">*</span></label>
                                        <input type="hidden" name="description_link" id="desc-link-input" value="{{ old('description_link') }}">
                                        <div class="rounded-2xl border border-slate-200 overflow-hidden shadow-sm bg-white">
                                            <div id="quill-link" class="text-elevate-dark font-medium">{!! old('description_link') !!}</div>
                                        </div>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-elevate-primary"><i class="ph-bold ph-link text-lg"></i></div>
                                        <input type="url" name="link_url" value="{{ old('link_url') }}" 
                                               :required="assignmentType === 'link'" 
                                               :disabled="assignmentType !== 'link'"
                                               class="w-full rounded-2xl border-slate-200 bg-white pl-12 font-bold text-elevate-primary focus:ring-elevate-accent/30 focus:border-elevate-accent h-14 transition-colors shadow-sm" 
                                               placeholder="https://...">
                                    </div>
                                </div>
                                <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">Instruksi Tambahan <span class="text-[#D13438]">*</span></label>
                                <textarea name="description_link" rows="4" 
                                          :required="assignmentType === 'link'" 
                                          :disabled="assignmentType !== 'link'"
                                          class="w-full rounded-2xl border-slate-200 bg-white focus:ring-[#D83B01]/30 focus:border-[#D83B01] p-5 font-medium transition-colors shadow-sm" 
                                          placeholder="Silakan kerjakan link di atas...">{{ old('description_link') }}</textarea>
                            </div>

                            <!-- C. JIKA KUIS ONLINE -->
                            <div x-show="assignmentType === 'quiz'" style="display: none;">
                                <div class="mb-8 flex flex-col md:flex-row gap-5">
                                    <div class="flex-1">
                                        <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">Instruksi Kuis <span class="text-[#D13438]">*</span></label>
                                        <textarea name="description_quiz" rows="2" :required="assignmentType === 'quiz'" :disabled="assignmentType !== 'quiz'" class="w-full rounded-2xl border-slate-200 bg-white focus:ring-purple-500/30 focus:border-purple-500 p-4 transition-colors shadow-sm">{{ old('description_quiz') }}</textarea>
                                    </div>
                                    <div class="w-full md:w-1/3">
                                        <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">Durasi (Menit) <span class="text-[#D13438]">*</span></label>
                                        <input type="number" name="duration_minutes" value="{{ old('duration_minutes', 60) }}" :required="assignmentType === 'quiz'" :disabled="assignmentType !== 'quiz'" class="w-full rounded-2xl border-slate-200 bg-white font-black text-elevate-dark focus:ring-purple-500/30 focus:border-purple-500 h-14 pl-5 transition-colors shadow-sm">
                                    </div>
                                </div>

                                <div class="space-y-5 mb-6">
                                    <template x-for="(q, index) in questions" :key="index">
                                        <div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm relative group">
                                            <button type="button" @click="questions = questions.filter((_, i) => i !== index)" class="absolute top-5 right-5 w-10 h-10 rounded-xl bg-[#FDE7E9] text-[#D13438] border border-[#F4C3C9] flex items-center justify-center shadow-sm">
                                                <i class="ph-bold ph-trash text-lg"></i>
                                            </button>
                                            <div class="flex flex-col sm:flex-row gap-5">
                                                <span class="bg-purple-100 text-purple-700 w-12 h-12 flex items-center justify-center rounded-2xl font-black text-lg shrink-0 shadow-sm" x-text="index + 1"></span>
                                                <div class="flex-1 w-full pr-12 sm:pr-0">
                                                    <textarea :name="'questions['+index+'][text]'" x-model="q.text" required rows="2" class="w-full rounded-2xl border-slate-200 text-base mb-3 focus:ring-purple-500/30 p-4 bg-slate-50 focus:bg-white" placeholder="Tuliskan pertanyaan..."></textarea>
                                                    <div class="space-y-3">
                                                        <template x-for="opt in ['A', 'B', 'C', 'D']">
                                                            <div class="flex items-center gap-3">
                                                                <input type="radio" :name="'questions['+index+'][correct]'" :value="opt" class="w-5 h-5 text-[#107C10] focus:ring-[#107C10] border-slate-300">
                                                                <div class="flex-1 relative group/opt">
                                                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 font-black" x-text="opt"></div>
                                                                    <input type="text" :name="'questions['+index+'][options]['+opt+']'" class="w-full rounded-xl border-slate-200 bg-elevate-soft text-sm py-3.5 pl-10 focus:bg-white focus:ring-purple-500/30 text-elevate-dark font-semibold" :placeholder="'Pilihan Jawaban ' + opt">
                                                                </div>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                                <button type="button" @click="questions.push({type: 'multiple_choice', text: '', points: 10})" class="w-full py-4 border-2 border-dashed border-purple-300 bg-purple-50 text-purple-600 rounded-[1.5rem] font-bold text-sm hover:bg-purple-100 transition-all flex items-center justify-center gap-2">
                                    <i class="ph-bold ph-plus text-lg"></i> Tambah Pertanyaan Kuis
                                </button>
                            </div>

                            <!-- D. JIKA VIDEO INTERAKTIF -->
                            <div x-show="assignmentType === 'interactive_video'" style="display: none;">
                                <div class="mb-8">
                                    <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">URL YouTube Video <span class="text-[#D13438]">*</span></label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-red-500"><i class="ph-bold ph-youtube-logo text-xl"></i></div>
                                        <input type="url" name="youtube_url" 
                                               :required="assignmentType === 'interactive_video'" 
                                               :disabled="assignmentType !== 'interactive_video'"
                                               class="w-full rounded-2xl border-slate-200 bg-white pl-12 font-bold text-elevate-dark focus:ring-red-500/30 focus:border-red-500 h-14 transition-colors shadow-sm" 
                                               placeholder="Contoh: https://www.youtube.com/watch?v=xxxxxxx">
                                    </div>
                                    <p class="text-xs font-semibold text-slate-500 mt-2 ml-1">Pastikan video YouTube bersifat Publik atau Unlisted.</p>
                                </div>

                                <h4 class="font-black text-elevate-dark text-lg mb-4 flex items-center gap-2"><i class="ph-fill ph-target text-red-500"></i> Titik Kuis (Pemberhentian Video)</h4>

                                <div class="space-y-6 mb-6">
                                    <template x-for="(iq, index) in interactiveQuestions" :key="index">
                                        <div class="bg-white p-6 rounded-[2rem] border-2 border-slate-100 hover:border-red-200 transition-all shadow-sm relative">
                                            
                                            <button type="button" @click="interactiveQuestions = interactiveQuestions.filter((_, i) => i !== index)" class="absolute top-5 right-5 w-10 h-10 rounded-xl bg-slate-100 text-slate-500 hover:bg-[#FDE7E9] hover:text-[#D13438] flex items-center justify-center transition-colors shadow-sm">
                                                <i class="ph-bold ph-trash text-lg"></i>
                                            </button>

                                            <div class="mb-5 flex flex-wrap items-end gap-3">
                                                <div class="w-full sm:w-auto">
                                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Muncul di Menit ke</label>
                                                    <input type="number" :name="'interactive_questions['+index+'][minute]'" x-model="iq.minute" min="0" required class="w-24 text-center rounded-xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-red-500 focus:ring-red-500/30 font-black text-lg text-elevate-dark shadow-sm">
                                                </div>
                                                <div class="pb-3 text-xl font-black text-slate-300">:</div>
                                                <div class="w-full sm:w-auto">
                                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Detik ke</label>
                                                    <input type="number" :name="'interactive_questions['+index+'][second]'" x-model="iq.second" min="0" max="59" required class="w-24 text-center rounded-xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-red-500 focus:ring-red-500/30 font-black text-lg text-elevate-dark shadow-sm">
                                                </div>
                                            </div>

                                            <div class="h-px bg-slate-100 w-full mb-5"></div>

                                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Pertanyaan</label>
                                            <textarea :name="'interactive_questions['+index+'][text]'" x-model="iq.text" required rows="2" class="w-full rounded-2xl border-slate-200 text-base mb-4 focus:ring-red-500/30 focus:border-red-500 font-medium shadow-sm p-4 bg-slate-50 focus:bg-white transition-colors" placeholder="Tuliskan pertanyaan kuis di sini..."></textarea>
                                            
                                            <div class="space-y-3">
                                                <template x-for="opt in ['A', 'B', 'C', 'D']">
                                                    <div class="flex items-center gap-3">
                                                        <input type="radio" :name="'interactive_questions['+index+'][correct]'" :value="opt" required class="w-5 h-5 text-[#107C10] focus:ring-[#107C10] border-slate-300 cursor-pointer">
                                                        <div class="flex-1 relative">
                                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 text-sm font-black" x-text="opt"></div>
                                                            <input type="text" :name="'interactive_questions['+index+'][options]['+opt+']'" required class="w-full rounded-xl border-slate-200 bg-elevate-soft text-sm py-3.5 pl-10 focus:bg-white focus:ring-red-500/30 focus:border-red-500 transition-colors font-semibold text-elevate-dark" :placeholder="'Pilihan ' + opt">
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                            <p class="text-[10px] font-bold text-slate-400 mt-3"><i class="ph-fill ph-info text-elevate-primary"></i> Klik bulatan di sebelah kiri untuk menentukan kunci jawaban benar.</p>
                                        </div>
                                    </template>
                                </div>

                                <button type="button" @click="interactiveQuestions.push({ minute: 0, second: 0, text: '', correct: 'A' })" 
                                        class="w-full py-4 border-2 border-dashed border-red-300 bg-red-50/50 text-red-600 rounded-[1.5rem] font-bold text-sm hover:bg-red-100 hover:border-red-400 transition-all flex items-center justify-center gap-2 active:scale-95">
                                    <i class="ph-bold ph-plus text-lg"></i> Tambah Titik Pemberhentian Kuis
                                </button>
                            </div>

                        </div>

                        <!-- 4. TARGET PENERIMA -->
                        <div class="bg-elevate-soft/50 p-6 md:p-8 rounded-[2rem] border border-slate-100">
                            <label class="block text-xs font-black text-elevate-primary uppercase tracking-widest mb-4 flex items-center gap-2">
                                <i class="ph-fill ph-users-three text-lg"></i> Target Penerima <span class="text-[#D13438]">*</span>
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="flex flex-col sm:flex-row gap-4">
                                    <label class="flex-1 inline-flex items-center cursor-pointer group bg-white px-4 py-3.5 border border-slate-200 rounded-xl hover:border-elevate-accent shadow-sm transition-all">
                                        <div class="relative flex items-center">
                                            <input type="radio" name="target_type" value="class" x-model="targetType" class="peer sr-only">
                                            <div class="w-5 h-5 border-2 border-slate-300 rounded-full peer-checked:border-elevate-primary peer-checked:bg-elevate-primary transition-colors"></div>
                                        </div>
                                        <span class="ml-3 text-sm font-black text-elevate-dark group-hover:text-elevate-primary transition-colors">Satu Kelas</span>
                                    </label>
                                    <label class="flex-1 inline-flex items-center cursor-pointer group bg-white px-4 py-3.5 border border-slate-200 rounded-xl hover:border-elevate-accent shadow-sm transition-all">
                                        <div class="relative flex items-center">
                                            <input type="radio" name="target_type" value="grade" x-model="targetType" class="peer sr-only">
                                            <div class="w-5 h-5 border-2 border-slate-300 rounded-full peer-checked:border-elevate-primary peer-checked:bg-elevate-primary transition-colors"></div>
                                        </div>
                                        <span class="ml-3 text-sm font-black text-elevate-dark group-hover:text-elevate-primary transition-colors">Satu Jenjang</span>
                                    </label>
                                </div>

                                <div>
                                    <div x-show="targetType === 'class'">
                                        <select name="class_id" :required="targetType === 'class'" :disabled="targetType !== 'class'" class="w-full text-sm font-bold rounded-xl border-slate-200 bg-white focus:ring-elevate-accent/30 focus:border-elevate-accent h-14 px-5 shadow-sm text-elevate-dark transition-colors">
                                            <option value="">-- Pilih Kelas --</option>
                                            @foreach($classes as $class)
                                                <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div x-show="targetType === 'grade'" style="display: none;">
                                        <select name="target_grade" :required="targetType === 'grade'" :disabled="targetType !== 'grade'" class="w-full text-sm font-bold rounded-xl border-slate-200 bg-white focus:ring-elevate-accent/30 focus:border-elevate-accent h-14 px-5 shadow-sm text-elevate-dark transition-colors">
                                            <option value="7">Kelas 7</option>
                                            <option value="8">Kelas 8</option>
                                            <option value="9">Kelas 9</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- FOOTER ACTIONS -->
                    <div class="bg-elevate-soft/30 px-6 py-6 md:px-10 md:py-8 flex flex-col sm:flex-row justify-end gap-4 border-t border-slate-100">
                        <a href="{{ route('lms.assignments.index') }}" class="w-full sm:w-auto px-8 py-4 bg-white border-2 border-slate-200 text-elevate-dark font-bold rounded-2xl hover:bg-elevate-soft transition-colors text-center text-sm shadow-sm btn-cancel-confirm active:scale-95">Batal</a>
                        <button type="submit" class="w-full sm:w-auto px-8 py-4 bg-elevate-dark text-white font-bold rounded-2xl shadow-lg shadow-elevate-dark/30 hover:bg-elevate-primary transition-all flex items-center justify-center gap-2 text-sm border border-transparent active:scale-95">
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
                        didOpen: () => { Swal.showLoading(); },
                        customClass: { popup: 'rounded-[2rem] font-sans border-0 shadow-2xl', title: 'text-xl font-black text-elevate-dark' }
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
            // Fungsi pembuat instance editor Quill
            function initQuill(selector, placeholderText) {
                if(document.querySelector(selector)) {
                    return new Quill(selector, {
                        theme: 'snow',
                        placeholder: placeholderText,
                        modules: {
                            toolbar: [
                                ['bold', 'italic', 'underline'],
                                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                                ['link', 'image'], // Tombol gambar ditambahkan
                                ['clean']
                            ]
                        }
                    });
                }
                return null;
            }

            // Inisialisasi Editor (Sesuaikan dengan ID yang Anda gunakan)
            var quillFile = initQuill('#quill-file', 'Tuliskan instruksi tugas secara rinci...');
            var quillLink = initQuill('#quill-link', 'Tuliskan instruksi tambahan di sini...');
            var quillEdit = initQuill('#quill-edit', 'Edit instruksi tugas...');

            // Tangkap event submit pada form
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
                    // Sync data berdasarkan tipe tugas yang dipilih
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