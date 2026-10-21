<x-app-layout>
    {{-- LOAD TRIX EDITOR RESOURCES --}}
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
    
    <style>
        trix-toolbar .trix-button-group--file-tools { display: none; }
        trix-editor { 
            min-height: 150px; 
            background-color: rgba(15, 23, 42, 0.8) !important; 
            border-radius: 1rem !important; 
            border: 1px solid rgba(255, 255, 255, 0.15) !important; 
            color: #f1f5f9 !important; 
        }
        trix-editor:focus {
            border-color: #38bdf8 !important;
            box-shadow: 0 0 0 2px rgba(56, 189, 248, 0.2) !important;
        }
        trix-toolbar {
            background-color: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1rem;
            padding: 0.5rem;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 40;
        }
        trix-toolbar .trix-button {
            background-color: rgba(30, 41, 59, 0.8) !important;
            border-bottom-color: rgba(255, 255, 255, 0.1) !important;
            color: #cbd5e1 !important;
        }
        trix-toolbar .trix-button:hover {
            background-color: rgba(51, 65, 85, 0.9) !important;
            color: #ffffff !important;
        }
        trix-toolbar .trix-button.trix-active {
            background-color: rgba(14, 165, 233, 0.3) !important;
            color: #38bdf8 !important;
        }
        .trix-content ul { list-style-type: disc; padding-left: 1.5rem; }
        .trix-content ol { list-style-type: decimal; padding-left: 1.5rem; }       
        .trix-button-group { background-color: transparent !important; }
    </style>

    {{-- CONFIG MATHJAX (Untuk Rumus Matematika) --}}
    <script>
        window.MathJax = {
            tex: { inlineMath: [['$', '$'], ['\\(', '\\)']] },
            svg: { fontCache: 'global' },
            startup: {
                ready: () => {
                    MathJax.startup.defaultReady();
                    window.renderMath = () => { MathJax.typesetPromise(); };
                }
            }
        };
    </script>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>

    <div x-data="{ 
        showEditModal: false,
        showImportModal: false, 
        questionSearch: '', 
        
        // --- STATE BULK ACTION ---
        selectedQuestions: [],
        
        toggleSelectAll(e) {
            if(e.target.checked) {
                this.selectedQuestions = Array.from(document.querySelectorAll('.question-checkbox')).map(cb => cb.value);
            } else {
                this.selectedQuestions = [];
            }
        },
        
        promptBulkWeight() {
            Swal.fire({
                title: 'Ubah Poin Masal',
                text: 'Masukkan bobot poin baru untuk ' + this.selectedQuestions.length + ' soal terpilih:',
                input: 'number',
                inputValue: 2,
                showCancelButton: true,
                confirmButtonText: 'Simpan',
                cancelButtonText: 'Batal',
                inputValidator: (value) => {
                    if (!value || value <= 0) return 'Poin harus lebih dari 0!'
                },
                customClass: { popup: 'rounded-[2rem] bg-slate-900 text-white border border-white/10' }
            }).then((result) => {
                if(result.isConfirmed) {
                    document.getElementById('bulkScoreWeightInput').value = result.value;
                    document.getElementById('bulkWeightForm').submit();
                }
            });
        },

        confirmBulkDelete() {
            Swal.fire({
                title: 'Hapus Soal Terpilih?',
                text: `Anda akan menghapus ${this.selectedQuestions.length} soal secara permanen!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                customClass: { popup: 'rounded-[2rem] bg-slate-900 text-white border border-white/10' }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('bulkDeleteForm').submit();
                }
            });
        },
        // --- END BULK ACTION ---

        // State Create
        createType: 'choice',
        createQuestionText: '',
        matchPairs: [{left: '', right: ''}],
        createTags: [],
        newTag: '',

        // State Edit
        editState: {
            id: null,
            url: '', 
            question_type: 'choice', 
            question_text: '', 
            question_image: '', 
            audio_path: '',
            audio_play_limit: 2,
            complex_answers: [],
            option_A: '', option_B: '', option_C: '', option_D: '',
            image_A: '', image_B: '', image_C: '', image_D: '',
            correct_answer: 'A', 
            score_weight: 2
        },
        editMatchPairs: [], 
        editTags: [],
        editNewTag: '',
        newImagePreview: null,
        deleteImage: false,
        newAudioPreview: null,
        deleteAudio: false,
        
        addPair() { this.matchPairs.push({left: '', right: ''}); },
        removePair(index) { if(this.matchPairs.length > 1) this.matchPairs.splice(index, 1); },

        addTag(tag, mode) {
            let clean = tag.trim();
            if(clean === '') return;
            if(mode === 'create') {
                if(!this.createTags.includes(clean)) this.createTags.push(clean);
                this.newTag = '';
            } else {
                if(!this.editTags.includes(clean)) this.editTags.push(clean);
                this.editNewTag = '';
            }
        },

        addEditPair() { this.editMatchPairs.push({left: '', right: ''}); },
        removeEditPair(index) { if(this.editMatchPairs.length > 1) this.editMatchPairs.splice(index, 1); },

        openEdit(data, url) {
            this.newImagePreview = null;
            this.deleteImage = false;
            this.newAudioPreview = null;
            this.deleteAudio = false;

            let parsedComplex = [];
            if (data.question_type === 'complex') {
                if (Array.isArray(data.correct_answer)) {
                    parsedComplex = data.correct_answer;
                } else if (typeof data.correct_answer === 'string') {
                    try { 
                        parsedComplex = JSON.parse(data.correct_answer); 
                    } catch(e) { 
                        parsedComplex = data.correct_answer ? [data.correct_answer] : []; 
                    }
                }
            }
            
            this.editState = { 
                ...this.editState, 
                ...data, 
                audio_path: data.audio_path || '',
                audio_play_limit: data.audio_play_limit || 2,
                complex_answers: Array.isArray(parsedComplex) ? parsedComplex : [],
                url: url,
                question_type: data.question_type || 'choice'
            };

            this.editTags = data.tags ? data.tags.split(',').map(t => t.trim()).filter(t => t) : [];

            if (this.editState.question_type === 'matching') {
                let pairs = [];
                if (data.options && data.options.pairs) {
                    pairs = data.options.pairs;
                } else if (typeof data.options === 'string' && data.options.includes('pairs')) {
                    try { pairs = JSON.parse(data.options).pairs; } catch(e){}
                }
                if (!pairs || pairs.length === 0) pairs = [{left: '', right: ''}];
                this.editMatchPairs = pairs;
            } else {
                this.editMatchPairs = [{left: '', right: ''}];
            }

            this.showEditModal = true;
            
            this.$nextTick(() => {
                const trix = document.getElementById('edit-trix-editor');
                if(trix) {
                    document.getElementById('q_input_edit').value = this.editState.question_text;
                    trix.editor.loadHTML(this.editState.question_text);
                }
                if (window.renderMath) window.renderMath();
            });
        },
        
        handleEditImage(event) {
            const file = event.target.files[0];
            if (file) { this.newImagePreview = URL.createObjectURL(file); this.deleteImage = false; }
        },

        removeCurrentImage() {
            this.deleteImage = true; this.newImagePreview = null; if (this.$refs.editFileInput) this.$refs.editFileInput.value = '';
        },

        handleEditAudio(event) {
            const file = event.target.files[0];
            if (file) { this.newAudioPreview = URL.createObjectURL(file); this.deleteAudio = false; }
        },

        removeCurrentAudio() {
            this.deleteAudio = true; this.newAudioPreview = null; if (this.$refs.editAudioInput) this.$refs.editAudioInput.value = '';
        }
    }">
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-white leading-tight">{{ __('Kelola Bank Soal') }}</h2>
        </x-slot>

        <div class="py-8 sm:py-10 font-sans min-h-screen text-slate-100 bg-[#020b18] relative overflow-hidden pb-20">
            
            {{-- Ambient Glow Effects --}}
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-600/15 rounded-full blur-3xl pointer-events-none -z-10"></div>
            <div class="absolute top-1/3 right-10 w-96 h-96 bg-sky-500/10 rounded-full blur-3xl pointer-events-none -z-10"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 space-y-8">
                
                {{-- HERO INFO MICROSOFT ELEVATE DARK THEME --}}
                <div class="relative rounded-[2.5rem] bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] p-8 text-white shadow-2xl backdrop-blur-xl overflow-hidden border border-white/10">
                    <div class="absolute -top-10 -left-10 w-48 h-48 bg-sky-500/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-3xl"></div>
                    <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-blue-600/10 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-2xl"></div>
                    <div class="absolute top-10 right-32 w-24 h-24 bg-white/5 rounded-2xl rotate-45 pointer-events-none shadow-sm"></div>
                    
                    <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <a href="{{ route('bank.index') }}" class="text-xs font-bold text-sky-300 hover:text-white transition flex items-center gap-1 bg-slate-900/80 px-3 py-1 rounded-full border border-white/10 backdrop-blur-sm shadow-sm">
                                    <i class="ph-bold ph-arrow-left"></i> Kembali
                                </a>
                                <span class="text-slate-500 text-xs">•</span>
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wide bg-sky-500/20 text-sky-300 border border-sky-400/30">
                                    {{ $bank->subject_name }}
                                </span>
                            </div>
                            <h1 class="text-3xl font-extrabold tracking-tight leading-none text-white mb-2">{{ $bank->title }}</h1>
                            <p class="text-slate-300 text-sm font-medium">Total: {{ $bank->questions->count() }} Soal • Kode: <span class="font-bold text-sky-400">{{ $bank->code }}</span></p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col lg:flex-row gap-8 items-start">
                    
                    {{-- FORM INPUT SOAL (CREATE) --}}
                    <div class="w-full lg:w-2/5 order-2 lg:order-1">
                        <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] p-6 sm:p-8 rounded-[2.5rem] border border-white/10 shadow-2xl backdrop-blur-xl lg:sticky lg:top-8">
                            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-white/10">
                                <div class="w-10 h-10 rounded-2xl bg-sky-500/20 text-sky-300 flex items-center justify-center text-lg shadow-sm border border-sky-400/30">
                                    <i class="ph-fill ph-plus-circle"></i>
                                </div>
                                <div>
                                    <h3 class="font-black text-white text-lg leading-none">Tambah Soal</h3>
                                    <p class="text-[10px] text-sky-400/80 font-bold mt-1 uppercase tracking-wider">Ke Bank Soal</p>
                                </div>
                            </div>

                            <form action="{{ route('bank.questions.store', $bank->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5" id="createQuestionForm">
                                @csrf
                                
                                {{-- Pilih Tipe (Create) --}}
                                <div>
                                    <label class="block text-xs font-bold text-sky-400 uppercase mb-2 ml-1">Tipe Soal</label>
                                    <div class="relative">
                                        <select name="question_type" x-model="createType" class="w-full rounded-2xl border-white/15 bg-slate-900/80 text-sm font-bold text-white py-3 px-4 focus:bg-slate-900 focus:ring-sky-400 focus:border-sky-400 cursor-pointer appearance-none transition-colors">
                                            <option value="choice">Pilihan Ganda</option>
                                            <option value="complex">Pilihan Ganda Kompleks (Multi-Jawaban)</option>
                                            <option value="true_false">Benar / Salah</option>
                                            <option value="matching">Menjodohkan (Matching)</option>
                                            <option value="essay">Isian Singkat / Essai</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400">
                                            <i class="ph-bold ph-caret-down"></i>
                                        </div>
                                    </div>
                                </div>

                                {{-- INPUT TAGS / KD --}}
                                <div class="bg-slate-900/50 p-4 rounded-2xl border border-white/10">
                                    <label class="block text-xs font-bold text-sky-400 uppercase mb-2 ml-1">
                                        <i class="ph-fill ph-tag"></i> Materi / KD (Opsional)
                                    </label>
                                    <div class="flex flex-wrap gap-2 mb-2">
                                        <template x-for="(tag, index) in createTags" :key="index">
                                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-sky-500/20 text-sky-300 rounded-lg text-xs font-bold border border-sky-400/30">
                                                <span x-text="tag"></span>
                                                <button type="button" @click="createTags.splice(index, 1)" class="hover:text-rose-400 transition">
                                                    <i class="ph-bold ph-x"></i>
                                                </button>
                                            </span>
                                        </template>
                                    </div>
                                    <div class="flex gap-2">
                                        <input type="text" x-model="newTag" @keydown.enter.prevent="addTag(newTag, 'create')" placeholder="Ketik lalu Enter..." class="w-full rounded-xl border-white/15 bg-slate-900/80 text-white placeholder-slate-500 text-sm py-2 px-3 focus:ring-sky-400 focus:border-sky-400">
                                        <button type="button" @click="addTag(newTag, 'create')" class="px-4 bg-gradient-to-r from-sky-500 to-blue-600 text-white rounded-xl hover:from-sky-400 hover:to-blue-500 font-bold text-xs shadow-sm transition">
                                            Tambah
                                        </button>
                                    </div>
                                    <input type="hidden" name="tags" :value="createTags.join(',')">
                                </div>

                                {{-- Editor --}}
                                <div>
                                    <label class="block text-xs font-bold text-sky-400 uppercase mb-2 ml-1">Pertanyaan / Instruksi</label>
                                    <input id="q_input_create" type="hidden" name="question_text" value="{{ old('question_text') }}">
                                    <trix-editor id="create-trix-editor" input="q_input_create" @trix-change="createQuestionText = $event.target.value" placeholder="Tulis soal di sini..." class="prose prose-invert prose-sm max-w-none"></trix-editor>
                                </div>

                                {{-- Upload Gambar --}}
                                <div x-data="{ createPreviewImage: null }">
                                    <label class="block text-xs font-bold text-sky-400 uppercase mb-2 ml-1">Gambar (Opsional)</label>
                                    <template x-if="createPreviewImage">
                                        <div class="mb-3 relative w-full group">
                                            <img :src="createPreviewImage" class="w-full h-40 object-cover rounded-2xl border border-white/15 shadow-sm">
                                            <button type="button" @click="createPreviewImage = null; $refs.fileInput.value = ''" class="absolute top-2 right-2 bg-rose-500 text-white p-1.5 rounded-xl shadow-lg hover:bg-rose-600 transition">
                                                <i class="ph-bold ph-trash"></i>
                                            </button>
                                        </div>
                                    </template>
                                    <label class="flex flex-col items-center justify-center w-full h-16 border-2 border-dashed border-white/15 rounded-2xl cursor-pointer hover:bg-sky-500/10 hover:border-sky-400/50 transition-all bg-slate-900/60">
                                        <div class="flex items-center gap-2">
                                            <i class="ph-duotone ph-image text-xl text-sky-400"></i>
                                            <span class="text-xs font-bold text-slate-300">Upload Gambar</span>
                                        </div>
                                        <input type="file" x-ref="fileInput" name="question_image" @change="createPreviewImage = URL.createObjectURL($event.target.files[0])" accept="image/*" class="hidden">
                                    </label>
                                </div>

                                {{-- Upload Audio (Listening) & Limit --}}
                                <div class="bg-slate-900/50 p-4 rounded-2xl border border-white/10" x-data="{ createPreviewAudio: null }">
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="block text-xs font-bold text-amber-400 uppercase ml-1">
                                            <i class="ph-fill ph-speaker-high"></i> Audio Listening (Opsional)
                                        </label>
                                        <span class="text-[10px] text-slate-400 font-medium">MP3, WAV, M4A, OGG</span>
                                    </div>
                                    <div class="space-y-3">
                                        <template x-if="createPreviewAudio">
                                            <div class="p-3 bg-amber-500/10 rounded-xl border border-amber-500/30 flex items-center justify-between gap-3">
                                                <audio :src="createPreviewAudio" controls class="h-8 max-w-full flex-1"></audio>
                                                <button type="button" @click="createPreviewAudio = null; $refs.audioInput.value = ''" class="bg-rose-500 text-white p-1.5 rounded-lg shadow hover:bg-rose-600 transition" title="Hapus Audio">
                                                    <i class="ph-bold ph-trash text-sm"></i>
                                                </button>
                                            </div>
                                        </template>
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                            <div class="sm:col-span-2">
                                                <label class="flex items-center justify-center gap-2 w-full h-11 border-2 border-dashed border-white/15 rounded-xl cursor-pointer hover:bg-amber-500/10 hover:border-amber-400/50 transition-all bg-slate-900/60 px-3">
                                                    <i class="ph-duotone ph-music-note text-lg text-amber-400"></i>
                                                    <span class="text-xs font-bold text-slate-300">Pilih File Audio</span>
                                                    <input type="file" x-ref="audioInput" name="question_audio" @change="createPreviewAudio = URL.createObjectURL($event.target.files[0])" accept="audio/*" class="hidden">
                                                </label>
                                            </div>
                                            <div>
                                                <div class="relative">
                                                    <input type="number" name="audio_play_limit" min="1" max="10" value="2" class="w-full rounded-xl border-white/15 bg-slate-900/80 text-white text-xs font-bold py-2.5 px-3 pl-8 focus:ring-amber-400 focus:border-amber-400" title="Batas pemutaran oleh siswa">
                                                    <span class="absolute inset-y-0 left-2.5 flex items-center text-amber-400 font-bold text-xs pointer-events-none">
                                                        <i class="ph-bold ph-repeat"></i>
                                                    </span>
                                                    <span class="absolute inset-y-0 right-2.5 flex items-center text-[10px] text-slate-400 font-medium pointer-events-none">x Putar</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Input Jawaban Dinamis (Create) --}}
                                <div class="bg-slate-900/50 rounded-2xl p-4 border border-white/10">
                                    
                                    {{-- Tipe 1: Pilihan Ganda --}}
                                    <template x-if="createType === 'choice'">
                                        <div class="space-y-4">
                                            <label class="block text-xs font-bold text-sky-400 uppercase ml-1">Opsi Jawaban & Gambar</label>
                                            @foreach(['A','B','C','D'] as $opt)
                                            <div class="flex gap-3 items-start group">
                                                <span class="w-8 h-8 rounded-lg bg-slate-800 border border-white/10 text-slate-300 flex items-center justify-center font-black text-xs shrink-0 mt-1">{{ $opt }}</span>
                                                <div class="flex-1 space-y-2" x-data="{ optPreview: null }">
                                                    <div class="flex gap-2">
                                                        <input type="text" name="option_{{ $opt }}" value="{{ old('option_'.$opt) }}" class="flex-1 rounded-xl border-white/15 bg-slate-900/90 text-white placeholder-slate-500 text-sm py-2 px-3 focus:ring-sky-400 focus:border-sky-400 transition-colors" placeholder="Teks Jawaban {{ $opt }}">
                                                        <label class="w-10 h-10 shrink-0 bg-slate-800 border border-white/10 rounded-xl flex items-center justify-center text-slate-400 hover:text-sky-300 hover:bg-slate-700 cursor-pointer transition" title="Upload Gambar Opsi {{ $opt }}">
                                                            <i class="ph-bold ph-image text-lg"></i>
                                                            <input type="file" name="image_{{ $opt }}" accept="image/*" class="hidden" @change="optPreview = URL.createObjectURL($event.target.files[0])">
                                                        </label>
                                                    </div>
                                                    <template x-if="optPreview">
                                                        <div class="relative w-24 mt-2">
                                                            <img :src="optPreview" class="h-24 w-24 object-cover rounded-xl border border-white/20 shadow-sm">
                                                            <button type="button" @click="optPreview = null; $event.target.closest('.flex-1').querySelector('input[type=file]').value = ''" class="absolute -top-2 -right-2 bg-rose-500 text-white rounded-lg p-1 shadow-md hover:bg-rose-600">
                                                                <i class="ph-bold ph-x text-xs"></i>
                                                            </button>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                            @endforeach
                                            <div class="mt-2 flex items-center">
                                                <label class="text-xs font-bold text-slate-300">Kunci Jawaban:</label>
                                                <select name="correct_answer" class="ml-2 rounded-lg border-white/15 text-xs font-bold py-1.5 px-3 bg-slate-900 text-white focus:ring-sky-400">
                                                    <option value="A" {{ old('correct_answer') == 'A' ? 'selected' : '' }}>A</option>
                                                    <option value="B" {{ old('correct_answer') == 'B' ? 'selected' : '' }}>B</option>
                                                    <option value="C" {{ old('correct_answer') == 'C' ? 'selected' : '' }}>C</option>
                                                    <option value="D" {{ old('correct_answer') == 'D' ? 'selected' : '' }}>D</option>
                                                </select>
                                            </div>
                                        </div>
                                    </template>

                                    {{-- Tipe 2: Pilihan Ganda Kompleks --}}
                                    <template x-if="createType === 'complex'">
                                        <div class="space-y-4">
                                            <div class="flex items-center justify-between">
                                                <label class="block text-xs font-bold text-amber-400 uppercase ml-1">Opsi Jawaban & Centang Kunci Benar</label>
                                                <span class="text-[10px] text-amber-300 font-bold bg-amber-500/20 px-2 py-0.5 rounded-md">Multi-Jawaban</span>
                                            </div>
                                            @foreach(['A','B','C','D'] as $opt)
                                            <div class="flex gap-3 items-start group">
                                                <label class="cursor-pointer shrink-0 mt-1 flex flex-col items-center gap-1" title="Centang jika merupakan jawaban benar">
                                                    <input type="checkbox" name="complex_answers[]" value="{{ $opt }}" class="rounded bg-slate-800 border-white/20 text-amber-500 focus:ring-amber-400 h-5 w-5">
                                                    <span class="text-[10px] font-bold text-slate-300">{{ $opt }}</span>
                                                </label>
                                                <div class="flex-1 space-y-2 min-w-0" x-data="{ optPreview: null }">
                                                    <div class="flex gap-2 min-w-0">
                                                        <input type="text" name="option_{{ $opt }}" value="{{ old('option_'.$opt) }}" class="flex-1 min-w-0 rounded-xl border-white/15 bg-slate-900/90 text-white placeholder-slate-500 text-sm py-2 px-3 focus:ring-sky-400 focus:border-sky-400 transition-colors" placeholder="Teks Jawaban {{ $opt }}">
                                                        <label class="w-10 h-10 shrink-0 bg-slate-800 border border-white/10 rounded-xl flex items-center justify-center text-slate-400 hover:text-sky-300 hover:bg-slate-700 cursor-pointer transition" title="Upload Gambar Opsi {{ $opt }}">
                                                            <i class="ph-bold ph-image text-lg"></i>
                                                            <input type="file" name="image_{{ $opt }}" accept="image/*" class="hidden" @change="optPreview = URL.createObjectURL($event.target.files[0])">
                                                        </label>
                                                    </div>
                                                    <template x-if="optPreview">
                                                        <div class="relative w-24 mt-2">
                                                            <img :src="optPreview" class="h-24 w-24 object-cover rounded-xl border border-white/20 shadow-sm">
                                                            <button type="button" @click="optPreview = null; $event.target.closest('.flex-1').querySelector('input[type=file]').value = ''" class="absolute -top-2 -right-2 bg-rose-500 text-white rounded-lg p-1 shadow-md hover:bg-rose-600">
                                                                <i class="ph-bold ph-x text-xs"></i>
                                                            </button>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </template>

                                    {{-- Tipe 2: Benar Salah --}}
                                    <template x-if="createType === 'true_false'">
                                        <div>
                                            <label class="block text-xs font-bold text-sky-400 uppercase mb-3 ml-1">Kunci Jawaban</label>
                                            <div class="flex gap-4">
                                                <label class="flex-1 cursor-pointer">
                                                    <input type="radio" name="correct_answer" value="A" {{ old('correct_answer') == 'A' ? 'checked' : '' }} class="peer sr-only">
                                                    <div class="p-3 rounded-xl bg-slate-800/80 border border-white/10 text-slate-300 text-center peer-checked:bg-emerald-500/20 peer-checked:border-emerald-500 peer-checked:text-emerald-300 transition">
                                                        <span class="font-bold text-sm">BENAR</span>
                                                    </div>
                                                </label>
                                                <label class="flex-1 cursor-pointer">
                                                    <input type="radio" name="correct_answer" value="B" {{ old('correct_answer') == 'B' ? 'checked' : '' }} class="peer sr-only">
                                                    <div class="p-3 rounded-xl bg-slate-800/80 border border-white/10 text-slate-300 text-center peer-checked:bg-rose-500/20 peer-checked:border-rose-500 peer-checked:text-rose-300 transition">
                                                        <span class="font-bold text-sm">SALAH</span>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </template>

                                    {{-- Tipe 3: Menjodohkan --}}
                                    <template x-if="createType === 'matching'">
                                        <div class="space-y-3">
                                            <label class="block text-xs font-bold text-sky-400 uppercase ml-1">Pasangan (Teks & Gambar)</label>
                                            <template x-for="(pair, index) in matchPairs" :key="index">
                                                <div class="bg-slate-900/80 p-3 rounded-xl border border-white/10 shadow-sm space-y-3" x-data="{ leftPreview: null, rightPreview: null }">
                                                    
                                                    <div class="flex items-center gap-2">
                                                        <!-- Sisi Kiri -->
                                                        <div class="flex-1 flex items-center gap-2 min-w-0">
                                                            <input type="text" :name="'matches['+index+'][left]'" x-model="pair.left" class="w-full min-w-0 rounded-lg border-white/15 bg-slate-800/90 text-white text-xs px-3 h-9 focus:ring-sky-400" placeholder="Teks Kiri">
                                                            <label class="shrink-0 w-9 h-9 bg-slate-800 border border-white/10 rounded-lg flex items-center justify-center text-slate-400 hover:text-sky-300 hover:bg-slate-700 cursor-pointer transition" title="Gambar Kiri">
                                                                <i class="ph-bold ph-image text-sm"></i>
                                                                <input type="file" :name="'matches['+index+'][left_image]'" accept="image/*" class="left-file-input hidden" @change="leftPreview = URL.createObjectURL($event.target.files[0])">
                                                            </label>
                                                        </div>
                                                        
                                                        <!-- Panah Tengah -->
                                                        <div class="shrink-0 w-4 flex items-center justify-center text-slate-500">
                                                            <i class="ph-bold ph-arrow-right"></i>
                                                        </div>

                                                        <!-- Sisi Kanan -->
                                                        <div class="flex-1 flex items-center gap-2 min-w-0">
                                                            <input type="text" :name="'matches['+index+'][right]'" x-model="pair.right" class="w-full min-w-0 rounded-lg border-white/15 bg-slate-800/90 text-white text-xs px-3 h-9 focus:ring-sky-400" placeholder="Teks Kanan">
                                                            <label class="shrink-0 w-9 h-9 bg-slate-800 border border-white/10 rounded-lg flex items-center justify-center text-slate-400 hover:text-sky-300 hover:bg-slate-700 cursor-pointer transition" title="Gambar Kanan">
                                                                <i class="ph-bold ph-image text-sm"></i>
                                                                <input type="file" :name="'matches['+index+'][right_image]'" accept="image/*" class="right-file-input hidden" @change="rightPreview = URL.createObjectURL($event.target.files[0])">
                                                            </label>
                                                        </div>
                                                        
                                                        <!-- Tombol Hapus -->
                                                        <button type="button" @click="removePair(index)" class="shrink-0 w-8 h-8 flex items-center justify-center text-rose-400 hover:text-rose-300 hover:bg-rose-500/20 rounded-lg transition" title="Hapus Pasangan">
                                                            <i class="ph-bold ph-trash text-base"></i>
                                                        </button>
                                                    </div>

                                                    <template x-if="leftPreview || rightPreview">
                                                        <div class="flex items-start gap-2">
                                                            <div class="flex-1 min-w-0">
                                                                <template x-if="leftPreview">
                                                                    <div class="relative w-fit">
                                                                        <img :src="leftPreview" class="h-16 w-16 object-cover rounded-lg border border-white/20 shadow-sm">
                                                                        <button type="button" @click="leftPreview = null; $event.target.closest('.bg-slate-900').querySelector('.left-file-input').value = ''" class="absolute -top-1.5 -right-1.5 bg-rose-500 text-white rounded-md p-0.5 shadow hover:bg-rose-600"><i class="ph-bold ph-x text-[10px]"></i></button>
                                                                    </div>
                                                                </template>
                                                            </div>
                                                            <div class="shrink-0 w-4"></div>
                                                            <div class="flex-1 min-w-0">
                                                                <template x-if="rightPreview">
                                                                    <div class="relative w-fit">
                                                                        <img :src="rightPreview" class="h-16 w-16 object-cover rounded-lg border border-white/20 shadow-sm">
                                                                        <button type="button" @click="rightPreview = null; $event.target.closest('.bg-slate-900').querySelector('.right-file-input').value = ''" class="absolute -top-1.5 -right-1.5 bg-rose-500 text-white rounded-md p-0.5 shadow hover:bg-rose-600"><i class="ph-bold ph-x text-[10px]"></i></button>
                                                                    </div>
                                                                </template>
                                                            </div>
                                                            <div class="shrink-0 w-8"></div>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>
                                            <button type="button" @click="addPair()" class="text-xs font-bold text-sky-400 hover:text-sky-300 flex items-center gap-1 mt-2">
                                                <i class="ph-bold ph-plus"></i> Tambah Pasangan
                                            </button>
                                        </div>
                                    </template>

                                    {{-- Tipe 4: Essai --}}
                                    <template x-if="createType === 'essay'">
                                        <div>
                                            <label class="block text-xs font-bold text-sky-400 uppercase mb-2 ml-1">Kunci (Opsional)</label>
                                            <input type="text" name="correct_answer" value="{{ old('correct_answer') }}" class="w-full rounded-xl border-white/15 bg-slate-900/80 text-white placeholder-slate-500 text-sm py-2 px-3 focus:ring-sky-400" placeholder="Jawaban singkat (Auto-grade)">
                                        </div>
                                    </template>
                                </div>

                                {{-- Metadata Kesukaran & Taksonomi Bloom --}}
                                <div class="grid grid-cols-2 gap-3 mb-4">
                                    <div>
                                        <label class="block text-xs font-bold text-sky-400 uppercase mb-2 ml-1">Tingkat Kesukaran</label>
                                        <select name="difficulty" class="w-full rounded-xl border-white/15 bg-slate-900/80 text-white text-xs font-bold h-11 focus:ring-sky-400 focus:border-sky-400">
                                            <option value="mudah" {{ old('difficulty') == 'mudah' ? 'selected' : '' }}>🟢 Mudah</option>
                                            <option value="sedang" {{ old('difficulty', 'sedang') == 'sedang' ? 'selected' : '' }}>🟡 Sedang</option>
                                            <option value="sulit" {{ old('difficulty') == 'sulit' ? 'selected' : '' }}>🔴 Sulit</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-sky-400 uppercase mb-2 ml-1">Taksonomi Bloom</label>
                                        <select name="bloom_taxonomy" class="w-full rounded-xl border-white/15 bg-slate-900/80 text-white text-xs font-bold h-11 focus:ring-sky-400 focus:border-sky-400">
                                            <option value="C1" {{ old('bloom_taxonomy') == 'C1' ? 'selected' : '' }}>C1 - Mengingat</option>
                                            <option value="C2" {{ old('bloom_taxonomy', 'C2') == 'C2' ? 'selected' : '' }}>C2 - Memahami</option>
                                            <option value="C3" {{ old('bloom_taxonomy') == 'C3' ? 'selected' : '' }}>C3 - Menerapkan</option>
                                            <option value="C4" {{ old('bloom_taxonomy') == 'C4' ? 'selected' : '' }}>C4 - Menganalisis</option>
                                            <option value="C5" {{ old('bloom_taxonomy') == 'C5' ? 'selected' : '' }}>C5 - Evaluasi</option>
                                            <option value="C6" {{ old('bloom_taxonomy') == 'C6' ? 'selected' : '' }}>C6 - Mencipta</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Bobot --}}
                                <div>
                                    <label class="block text-xs font-bold text-sky-400 uppercase mb-2 ml-1">Bobot Nilai</label>
                                    <input type="number" name="score_weight" value="{{ old('score_weight', 2) }}" required class="w-24 rounded-xl border-white/15 bg-slate-900/90 text-white text-sm font-bold text-center h-10 focus:ring-sky-400">
                                </div>

                                <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-sky-500 to-blue-600 text-white font-bold rounded-xl hover:from-sky-400 hover:to-blue-500 transition shadow-lg shadow-sky-500/25 flex items-center justify-center gap-2 transform active:scale-95">
                                    <i class="ph-bold ph-floppy-disk text-lg"></i> Simpan ke Bank
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- LIST SOAL (KANAN) --}}
                    <div class="w-full lg:w-3/5 order-1 lg:order-2 space-y-6">
                        
                        {{-- FORM BULK ACTIONS (HIDDEN) --}}
                        <form id="bulkDeleteForm" action="{{ route('bank.questions.bulk_delete', $bank->id) }}" method="POST" class="hidden">
                            @csrf @method('DELETE')
                            <input type="hidden" name="question_ids" :value="selectedQuestions.join(',')">
                        </form>
                        <form id="bulkWeightForm" action="{{ route('bank.questions.bulk_weight', $bank->id) }}" method="POST" class="hidden">
                            @csrf @method('PUT')
                            <input type="hidden" name="question_ids" :value="selectedQuestions.join(',')">
                            <input type="hidden" name="score_weight" id="bulkScoreWeightInput">
                        </form>

                        <div class="flex flex-col sm:flex-row justify-between items-center px-2 gap-4">
                            <div class="flex items-center gap-3 flex-wrap">
                                <h3 class="font-black text-white text-lg flex items-center gap-2">
                                    <i class="ph-fill ph-list-dashes text-sky-400"></i> Isi Bank Soal
                                </h3>
                                
                                {{-- TOMBOL IMPORT --}}
                                <button @click="showImportModal = true" class="px-3.5 py-1.5 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-xl text-xs font-bold hover:from-emerald-400 hover:to-teal-500 transition shadow-md shadow-emerald-500/20 flex items-center gap-1.5 ml-2">
                                    <i class="ph-bold ph-file-arrow-up text-base"></i> Import
                                </button>                                  

                                @if($bank->questions->count() > 0)
                                    {{-- TOMBOL EXPORT EXCEL --}}
                                    <a href="{{ route('bank.questions.export', $bank->id) }}" class="px-3 py-1.5 bg-slate-900/80 text-sky-300 border border-white/10 rounded-xl text-xs font-bold hover:bg-slate-800 hover:text-white transition shadow-sm flex items-center gap-1.5">
                                        <i class="ph-bold ph-file-xls text-base text-emerald-400"></i> Excel
                                    </a>                                        

                                    {{-- TOMBOL EXPORT WORD --}}
                                    <a href="{{ route('bank.export_word', $bank->id) }}" class="px-3 py-1.5 bg-slate-900/80 text-sky-300 border border-white/10 rounded-xl text-xs font-bold hover:bg-slate-800 hover:text-white transition shadow-sm flex items-center gap-1.5">
                                        <i class="ph-bold ph-file-doc text-base text-blue-400"></i> Word
                                    </a>                                        

                                    {{-- TOMBOL PREVIEW --}}
                                    <a href="{{ route('bank.preview', $bank->id) }}" target="_blank" class="px-3.5 py-1.5 bg-gradient-to-r from-sky-500 to-blue-600 text-white rounded-xl text-xs font-bold hover:from-sky-400 hover:to-blue-500 transition shadow-sm flex items-center gap-1.5">
                                        <i class="ph-bold ph-desktop text-base"></i> Pratinjau
                                    </a>

                                    {{-- TOMBOL CETAK PDF --}}
                                    <a href="{{ route('bank.questions.print', $bank->id) }}" target="_blank" class="px-3 py-1.5 bg-slate-900/80 text-slate-300 border border-white/10 rounded-xl text-xs font-bold hover:text-white hover:bg-slate-800 transition shadow-sm flex items-center gap-1.5">
                                        <i class="ph-bold ph-printer text-base text-sky-400"></i> PDF
                                    </a>
                                @endif
                            </div>                            
                        </div>

                        <div class="flex flex-col sm:flex-row justify-between items-center px-2 gap-4">
                            <div class="flex items-center gap-2 flex-wrap">
                                {{-- JUMLAH SOAL --}}
                                <span class="text-xs font-bold text-sky-300 bg-sky-500/20 border border-sky-400/30 px-3 py-1.5 rounded-lg shadow-sm flex items-center gap-1.5" title="Total Soal">
                                    <i class="ph-fill ph-book-open-text text-base"></i> Soal {{ $bank->questions->count() }}
                                </span>

                                {{-- TOTAL POIN --}}
                                <span class="text-xs font-bold text-emerald-400 bg-emerald-500/20 border border-emerald-500/30 px-3 py-1.5 rounded-lg shadow-sm flex items-center gap-1.5" title="Akumulasi Bobot Nilai">
                                    <i class="ph-fill ph-chart-bar text-base"></i> Poin {{ $totalPoints ?? 0 }}
                                </span>

                                {{-- CHECKBOX PILIH SEMUA --}}
                                @if($bank->questions->count() > 0)
                                    <label class="flex items-center gap-2 cursor-pointer bg-slate-900/80 px-3.5 py-2 rounded-xl border border-white/10 hover:bg-slate-800 shadow-sm transition">
                                        <input type="checkbox" @change="toggleSelectAll($event)" class="w-4 h-4 rounded border-white/20 bg-slate-800 text-sky-500 focus:ring-sky-400 cursor-pointer">
                                        <span class="text-xs font-bold text-slate-300">Pilih Semua</span>
                                    </label>
                                @endif
                            </div>

                            {{-- KOLOM PENCARIAN --}}
                            <div class="relative w-full md:w-80 shrink-0">
                                <i class="ph-bold ph-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input type="text" x-model="questionSearch" placeholder="Cari pertanyaan / tag..." class="w-full pl-10 pr-4 py-2 text-sm font-bold border-white/15 rounded-xl focus:ring-sky-400 focus:border-sky-400 bg-slate-900/80 text-white placeholder-slate-500 shadow-sm transition">
                            </div>
                        </div>                      
                        
                        {{-- ACTION BAR MUNCUL SAAT ADA YANG DIPILIH --}}
                        <div x-show="selectedQuestions.length > 0" x-transition class="bg-gradient-to-r from-slate-900 via-sky-950 to-slate-900 border border-sky-500/30 rounded-[1.5rem] p-4 flex flex-col sm:flex-row justify-between items-center gap-4 shadow-xl shadow-sky-950/50" style="display: none;">
                            <div class="text-sm font-bold text-white flex items-center gap-2">
                                <i class="ph-fill ph-check-circle text-sky-400 text-lg"></i>
                                <span x-text="selectedQuestions.length"></span> Soal Terpilih
                            </div>
                            <div class="flex gap-2 w-full sm:w-auto">
                                <button type="button" @click="promptBulkWeight()" class="flex-1 sm:flex-none px-4 py-2 bg-sky-500/20 text-sky-300 border border-sky-400/30 rounded-xl text-xs font-bold hover:bg-sky-500/30 transition shadow-sm">Ubah Bobot</button>
                                <button type="button" @click="confirmBulkDelete()" class="flex-1 sm:flex-none px-4 py-2 bg-rose-500 text-white border border-transparent rounded-xl text-xs font-bold hover:bg-rose-600 transition shadow-sm">Hapus</button>
                            </div>
                        </div>

                        @forelse($bank->questions as $index => $q)
                            @php 
                                $qType = $q->question_type ?? 'choice'; 
                                $searchableText = strtolower(strip_tags($q->question_text) . ' ' . ($q->option_A ?? '') . ' ' . ($q->option_B ?? '') . ' ' . ($q->option_C ?? '') . ' ' . ($q->option_D ?? '') . ' ' . ($q->tags ?? ''));
                            @endphp

                            <div data-search="{{ $searchableText }}"
                                 x-show="questionSearch === '' || $el.dataset.search.includes(questionSearch.toLowerCase())"
                                 class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] p-6 sm:p-7 rounded-[2.5rem] border border-white/10 shadow-2xl backdrop-blur-xl relative group hover:border-sky-400/40 transition-all duration-300">
                            
                            <div class="absolute top-6 left-6 w-10 h-10 bg-sky-500/20 border border-sky-400/30 rounded-xl flex items-center justify-center font-black text-sky-300 text-sm group-hover:bg-sky-500 group-hover:text-white transition-colors shadow-inner">{{ $index + 1 }}</div>
                            
                            {{-- CHECKBOX ITEM --}}
                            <div class="absolute top-8 left-[4.5rem] z-10">
                                <input type="checkbox" value="{{ $q->id }}" x-model="selectedQuestions" class="question-checkbox w-5 h-5 rounded border-white/20 bg-slate-900 text-sky-500 focus:ring-sky-400 cursor-pointer shadow-sm">
                            </div>

                            <div class="pl-20 sm:pl-24">
                                {{-- Badge Tipe Soal & Metadata --}}
                                <div class="mb-2 flex flex-wrap items-center gap-1.5">
                                    @if($qType == 'choice') <span class="text-[10px] font-bold bg-sky-500/20 text-sky-300 px-2.5 py-0.5 rounded border border-sky-400/30">PILIHAN GANDA</span>
                                    @elseif($qType == 'complex') <span class="text-[10px] font-bold bg-amber-500/20 text-amber-300 px-2.5 py-0.5 rounded border border-amber-500/30">PILIHAN GANDA KOMPLEKS</span>
                                    @elseif($qType == 'true_false') <span class="text-[10px] font-bold bg-purple-500/20 text-purple-300 px-2.5 py-0.5 rounded border border-purple-500/30">BENAR / SALAH</span>
                                    @elseif($qType == 'matching') <span class="text-[10px] font-bold bg-indigo-500/20 text-indigo-300 px-2.5 py-0.5 rounded border border-indigo-500/30">MENJODOHKAN</span>
                                    @elseif($qType == 'essay') <span class="text-[10px] font-bold bg-pink-500/20 text-pink-300 px-2.5 py-0.5 rounded border border-pink-500/30">ESSAI</span>
                                    @endif

                                    @if($q->audio_path)
                                        <span class="text-[10px] font-bold bg-amber-500/20 text-amber-300 px-2 py-0.5 rounded border border-amber-500/30 flex items-center gap-1">
                                            <i class="ph-fill ph-speaker-high"></i> AUDIO ({{ $q->audio_play_limit ?? 2 }}x)
                                        </span>
                                    @endif

                                    @if(($q->difficulty ?? 'sedang') == 'mudah')
                                        <span class="text-[10px] font-bold bg-emerald-500/20 text-emerald-300 px-2 py-0.5 rounded border border-emerald-500/30">🟢 MUDAH</span>
                                    @elseif(($q->difficulty ?? 'sedang') == 'sulit')
                                        <span class="text-[10px] font-bold bg-rose-500/20 text-rose-300 px-2 py-0.5 rounded border border-rose-500/30">🔴 SULIT</span>
                                    @else
                                        <span class="text-[10px] font-bold bg-amber-500/20 text-amber-300 px-2 py-0.5 rounded border border-amber-500/30">🟡 SEDANG</span>
                                    @endif

                                    <span class="text-[10px] font-bold bg-indigo-500/20 text-indigo-300 px-2 py-0.5 rounded border border-indigo-500/30">🧠 {{ strtoupper($q->bloom_taxonomy ?? 'C2') }}</span>
                                </div>

                                {{-- Tampilkan TAGS --}}
                                @if(!empty($q->tags))
                                    <div class="mb-3 flex flex-wrap gap-1.5">
                                        @foreach(explode(',', $q->tags) as $tag)
                                            <span class="text-[10px] font-bold bg-slate-800/80 text-sky-300 px-2.5 py-0.5 rounded border border-white/10 flex items-center gap-1">
                                                <i class="ph-fill ph-tag"></i> {{ trim($tag) }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Audio Player Preview --}}
                                @if($q->audio_path)
                                    <div class="mb-4 p-3 rounded-2xl bg-amber-500/10 border border-amber-500/30 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-inner">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-9 h-9 rounded-xl bg-amber-500/20 text-amber-300 flex items-center justify-center shrink-0 border border-amber-500/30">
                                                <i class="ph-duotone ph-speaker-high text-xl"></i>
                                            </div>
                                            <div>
                                                <p class="text-xs font-bold text-amber-200">Audio Listening Soal</p>
                                                <p class="text-[10px] text-amber-400/80">Batas putar siswa: {{ $q->audio_play_limit ?? 2 }}x</p>
                                            </div>
                                        </div>
                                        <audio controls class="h-8 max-w-full sm:max-w-[280px]">
                                            <source src="{{ asset('storage/' . $q->audio_path) }}">
                                            Browser tidak mendukung audio.
                                        </audio>
                                    </div>
                                @endif

                                @if($q->question_image)
                                    <div class="mb-4 group/img relative w-fit">
                                        <img src="{{ asset('storage/' . $q->question_image) }}" class="max-h-48 rounded-2xl border border-white/15 shadow-sm object-cover cursor-zoom-in hover:opacity-90 transition" onclick="viewImage('{{ asset('storage/' . $q->question_image) }}')">
                                    </div>
                                @endif
                                
                                <div class="text-slate-100 font-medium text-lg mb-5 leading-relaxed trix-content prose prose-invert prose-sm max-w-none">{!! $q->question_text !!}</div>
                                
                                {{-- Preview Jawaban (Read Only) --}}
                                @if($qType == 'choice')
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                                        @foreach(['A','B','C','D'] as $opt)
                                            @php 
                                                $val = isset($q->{'option_'.$opt}) ? $q->{'option_'.$opt} : ($q->options[$opt] ?? '-'); 
                                                $imgVal = isset($q->{'image_'.$opt}) ? $q->{'image_'.$opt} : ($q->options['image_'.$opt] ?? null);
                                            @endphp
                                            <div class="flex items-start gap-3 p-3 rounded-[1.5rem] border transition-colors {{ $opt == $q->correct_answer ? 'bg-emerald-500/15 border-emerald-500/40 text-emerald-300 shadow-sm' : 'bg-slate-900/60 border-white/10 text-slate-300' }}">
                                                <span class="w-8 h-8 flex items-center justify-center rounded-xl border {{ $opt == $q->correct_answer ? 'border-emerald-500 bg-emerald-500 text-white shadow-md' : 'border-white/10 text-slate-400 bg-slate-800' }} text-[10px] font-black shrink-0">{{ $opt }}</span>
                                                <div class="flex-1 overflow-hidden mt-1">
                                                    @if($val && $val !== '-')
                                                        <span class="leading-relaxed block {{ $opt == $q->correct_answer ? 'text-emerald-200 font-bold' : 'text-slate-200 font-medium' }}">{{ $val }}</span>
                                                    @endif
                                                    @if($imgVal)
                                                        <img src="{{ asset('storage/' . $imgVal) }}" class="mt-2 max-h-24 rounded-lg border border-white/15 object-cover cursor-zoom-in hover:opacity-90 transition" onclick="viewImage('{{ asset('storage/' . $imgVal) }}')">
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @elseif($qType == 'complex')
                                    @php
                                        $correctKeys = is_string($q->correct_answer) ? (json_decode($q->correct_answer, true) ?: [$q->correct_answer]) : (is_array($q->correct_answer) ? $q->correct_answer : []);
                                    @endphp
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                                        @foreach(['A','B','C','D'] as $opt)
                                            @php 
                                                $isCorrect = in_array($opt, $correctKeys);
                                                $val = isset($q->{'option_'.$opt}) ? $q->{'option_'.$opt} : ($q->options[$opt] ?? '-'); 
                                                $imgVal = isset($q->{'image_'.$opt}) ? $q->{'image_'.$opt} : ($q->options['image_'.$opt] ?? null);
                                            @endphp
                                            <div class="flex items-start gap-3 p-3 rounded-[1.5rem] border transition-colors {{ $isCorrect ? 'bg-amber-500/15 border-amber-500/40 text-amber-300 shadow-sm' : 'bg-slate-900/60 border-white/10 text-slate-300' }}">
                                                <span class="w-8 h-8 flex items-center justify-center rounded-xl border {{ $isCorrect ? 'border-amber-500 bg-amber-500 text-slate-900 shadow-md font-black' : 'border-white/10 text-slate-400 bg-slate-800' }} text-[10px] font-black shrink-0">
                                                    @if($isCorrect)
                                                        <i class="ph-bold ph-check"></i> {{ $opt }}
                                                    @else
                                                        {{ $opt }}
                                                    @endif
                                                </span>
                                                <div class="flex-1 overflow-hidden mt-1">
                                                    @if($val && $val !== '-')
                                                        <span class="leading-relaxed block {{ $isCorrect ? 'text-amber-200 font-bold' : 'text-slate-200 font-medium' }}">{{ $val }}</span>
                                                    @endif
                                                    @if($imgVal)
                                                        <img src="{{ asset('storage/' . $imgVal) }}" class="mt-2 max-h-24 rounded-lg border border-white/15 object-cover cursor-zoom-in hover:opacity-90 transition" onclick="viewImage('{{ asset('storage/' . $imgVal) }}')">
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @elseif($qType == 'matching')
                                    <div class="text-xs text-slate-300 bg-slate-900/60 p-4 rounded-2xl border border-white/10">
                                        <p class="font-bold text-sky-400 mb-2.5">Pasangan:</p>
                                        @php 
                                            $pairs = is_string($q->options) ? json_decode($q->options, true)['pairs'] ?? [] : $q->options['pairs'] ?? [];
                                        @endphp
                                        <div class="space-y-2">
                                            @foreach($pairs as $p)
                                                <div class="flex items-center gap-3 bg-slate-800/80 p-2.5 rounded-xl border border-white/10 shadow-sm">
                                                    <div class="flex-1 flex flex-col sm:flex-row items-center sm:items-start gap-2 text-center sm:text-left">
                                                        @if(isset($p['left_image']) && $p['left_image'])
                                                            <img src="{{ asset('storage/' . $p['left_image']) }}" class="w-10 h-10 rounded border border-white/15 object-cover cursor-zoom-in" onclick="viewImage('{{ asset('storage/' . $p['left_image']) }}')">
                                                        @endif
                                                        @if(isset($p['left']) && $p['left'] !== '')
                                                            <span class="font-medium text-white mt-1 sm:mt-0">{{ $p['left'] }}</span>
                                                        @endif
                                                    </div>
                                                    <i class="ph-bold ph-arrows-left-right text-slate-500"></i>
                                                    <div class="flex-1 flex flex-col sm:flex-row items-center sm:items-start gap-2 text-center sm:text-left">
                                                        @if(isset($p['right_image']) && $p['right_image'])
                                                            <img src="{{ asset('storage/' . $p['right_image']) }}" class="w-10 h-10 rounded border border-white/15 object-cover cursor-zoom-in" onclick="viewImage('{{ asset('storage/' . $p['right_image']) }}')">
                                                        @endif
                                                        @if(isset($p['right']) && $p['right'] !== '')
                                                            <span class="font-medium text-white mt-1 sm:mt-0">{{ $p['right'] }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @elseif($qType == 'essay')
                                    <div class="text-xs text-slate-300 bg-slate-900/60 p-3 rounded-xl border border-white/10">
                                        <span class="font-bold text-sky-400">Kunci:</span> {{ $q->correct_answer ?: '(Koreksi Manual)' }}
                                    </div>
                                @elseif($qType == 'true_false')
                                    <div class="flex gap-2">
                                        <span class="px-3 py-1 rounded-lg border text-xs font-bold {{ $q->correct_answer == 'A' ? 'bg-emerald-500/20 text-emerald-300 border-emerald-500/40' : 'bg-slate-900/60 text-slate-500 border-white/10' }}">BENAR</span>
                                        <span class="px-3 py-1 rounded-lg border text-xs font-bold {{ $q->correct_answer == 'B' ? 'bg-emerald-500/20 text-emerald-300 border-emerald-500/40' : 'bg-slate-900/60 text-slate-500 border-white/10' }}">SALAH</span>
                                    </div>
                                @endif

                                <div class="mt-5 pt-3 border-t border-white/10 flex items-center gap-3">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase bg-slate-900/80 px-2.5 py-1 rounded-md border border-white/10">Bobot: {{ $q->score_weight }} Poin</span>
                                </div>
                            </div>

                            {{-- ACTION BUTTONS (Edit Amber & Delete Red) --}}
                            <div class="absolute top-6 right-6 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                {{-- Tombol Edit --}}
                                <button type="button" @click="openEdit({
                                        question_type: '{{ $qType }}',
                                        question_text: {{ json_encode($q->question_text) }},
                                        question_image: {{ json_encode($q->question_image) }}, 
                                        audio_path: {{ json_encode($q->audio_path) }},
                                        audio_play_limit: {{ $q->audio_play_limit ?? 2 }},
                                        option_A: {{ json_encode($q->option_A ?? ($q->options['A']??'')) }},
                                        option_B: {{ json_encode($q->option_B ?? ($q->options['B']??'')) }},
                                        option_C: {{ json_encode($q->option_C ?? ($q->options['C']??'')) }},
                                        option_D: {{ json_encode($q->option_D ?? ($q->options['D']??'')) }},
                                        image_A: '{{ $q->image_A ?? ($q->options['image_A'] ?? '') }}',
                                        image_B: '{{ $q->image_B ?? ($q->options['image_B'] ?? '') }}',
                                        image_C: '{{ $q->image_C ?? ($q->options['image_C'] ?? '') }}',
                                        image_D: '{{ $q->image_D ?? ($q->options['image_D'] ?? '') }}',
                                        options: {{ json_encode($q->options) }}, 
                                        correct_answer: '{{ $q->correct_answer }}',
                                        score_weight: {{ $q->score_weight }},
                                        difficulty: '{{ $q->difficulty ?? 'sedang' }}',
                                        bloom_taxonomy: '{{ $q->bloom_taxonomy ?? 'C2' }}',
                                        tags: '{{ addslashes($q->tags ?? '') }}'
                                    }, '{{ route('bank.questions.update', $q->id) }}')"
                                    class="w-10 h-10 rounded-xl bg-slate-900/90 border border-amber-400/40 text-amber-300 hover:bg-amber-500/20 hover:border-amber-400 shadow-sm flex items-center justify-center transition-all hover:scale-105 active:scale-95"
                                    title="Edit Soal">
                                    <i class="ph-bold ph-pencil-simple text-lg"></i>
                                </button>

                                {{-- Tombol Delete --}}
                                <form action="{{ route('bank.questions.destroy', $q->id) }}" method="POST" class="delete-form">
                                    @csrf @method('DELETE')
                                    <button type="button" class="w-10 h-10 rounded-xl bg-slate-900/90 border border-rose-500/40 text-rose-400 hover:bg-rose-500/20 hover:border-rose-400 shadow-sm flex items-center justify-center transition-all hover:scale-105 active:scale-95 btn-delete" title="Hapus Soal">
                                        <i class="ph-bold ph-trash text-lg"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-16 bg-gradient-to-b from-[#031d3d]/50 to-[#020b18] rounded-[2.5rem] border-2 border-dashed border-white/10">
                            <div class="w-20 h-20 bg-sky-500/10 rounded-full flex items-center justify-center mx-auto mb-4 text-sky-400">
                                <i class="ph-duotone ph-clipboard-text text-4xl"></i>
                            </div>
                            <h3 class="text-white font-black text-xl mb-1">Bank Soal Kosong</h3>
                            <p class="text-slate-400 text-sm max-w-xs mx-auto font-medium">Mulai tambahkan soal baru untuk bank soal ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- MODAL EDIT SOAL --}}
        <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
             <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-md transition-opacity" @click="showEditModal = false"></div>
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="relative transform overflow-hidden rounded-[2.5rem] bg-[#020b18] text-left shadow-2xl transition-all sm:w-full sm:max-w-2xl border border-white/15">
                    <form :action="editState.url" method="POST" enctype="multipart/form-data" id="editQuestionForm">
                        @csrf @method('PUT')
                        <input type="hidden" name="delete_image" x-model="deleteImage">
                        <input type="hidden" name="delete_audio" x-model="deleteAudio">

                        <div class="bg-slate-900/80 px-8 py-6 border-b border-white/10 flex justify-between items-center">
                            <h3 class="text-xl font-black text-white flex items-center gap-2">
                                <div class="w-10 h-10 bg-amber-500/20 text-amber-300 border border-amber-400/30 rounded-xl flex items-center justify-center shrink-0">
                                    <i class="ph-fill ph-pencil-simple text-xl"></i>
                                </div>
                                Edit Soal Bank
                            </h3>
                            <button type="button" @click="showEditModal = false" class="text-slate-400 hover:text-rose-400 transition bg-slate-800/80 w-10 h-10 rounded-xl flex items-center justify-center">
                                <i class="ph-bold ph-x text-lg"></i>
                            </button>
                        </div>

                        <div class="p-8 space-y-6 max-h-[70vh] overflow-y-auto custom-scrollbar bg-slate-950/50">
                            
                            {{-- GANTI TIPE SOAL (EDIT) --}}
                            <div>
                                <label class="block text-xs font-bold text-sky-400 uppercase mb-2 ml-1">Tipe Soal</label>
                                <div class="relative">
                                    <select name="question_type" x-model="editState.question_type" class="w-full rounded-2xl border-white/15 bg-slate-900 text-sm font-bold text-white py-3 px-4 focus:ring-sky-400 focus:border-sky-400 cursor-pointer appearance-none">
                                        <option value="choice">Pilihan Ganda</option>
                                        <option value="complex">Pilihan Ganda Kompleks (Multi-Jawaban)</option>
                                        <option value="true_false">Benar / Salah</option>
                                        <option value="matching">Menjodohkan</option>
                                        <option value="essay">Essai / Isian</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400">
                                        <i class="ph-bold ph-caret-down"></i>
                                    </div>
                                </div>
                            </div>

                            {{-- EDIT TAGS / KD --}}
                            <div class="bg-slate-900/50 p-4 rounded-2xl border border-white/10">
                                <label class="block text-xs font-bold text-sky-400 uppercase mb-2 ml-1"><i class="ph-fill ph-tag"></i> Materi / KD (Opsional)</label>
                                <div class="flex flex-wrap gap-2 mb-2">
                                    <template x-for="(tag, index) in editTags" :key="index">
                                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-sky-500/20 text-sky-300 rounded-lg text-xs font-bold border border-sky-400/30">
                                            <span x-text="tag"></span>
                                            <button type="button" @click="editTags.splice(index, 1)" class="hover:text-rose-400"><i class="ph-bold ph-x"></i></button>
                                        </span>
                                    </template>
                                </div>
                                <div class="flex gap-2">
                                    <input type="text" x-model="editNewTag" @keydown.enter.prevent="addTag(editNewTag, 'edit')" placeholder="Ketik lalu Enter..." class="w-full rounded-xl border-white/15 bg-slate-900 text-white placeholder-slate-500 text-sm py-2 px-3 focus:ring-sky-400 focus:border-sky-400">
                                    <button type="button" @click="addTag(editNewTag, 'edit')" class="px-4 bg-gradient-to-r from-sky-500 to-blue-600 text-white rounded-xl hover:from-sky-400 hover:to-blue-500 font-bold text-xs">Tambah</button>
                                </div>
                                <input type="hidden" name="tags" :value="editTags.join(',')">
                            </div>

                            {{-- Editor --}}
                            <div>
                                <label class="block text-xs font-bold text-sky-400 uppercase mb-2 ml-1">Pertanyaan</label>
                                <input id="q_input_edit" type="hidden" name="question_text" x-model="editState.question_text">
                                <trix-editor id="edit-trix-editor" input="q_input_edit" @trix-change="editState.question_text = $event.target.value" class="prose prose-invert prose-sm max-w-none"></trix-editor>
                            </div>

                            {{-- Gambar --}}
                            <div class="bg-slate-900/50 p-5 rounded-2xl border border-white/10">
                                <label class="block text-xs font-bold text-sky-400 uppercase mb-3 ml-1">Gambar Soal</label>
                                <div class="flex flex-col gap-4">
                                    <template x-if="newImagePreview">
                                        <div class="relative w-fit">
                                            <p class="text-[10px] font-bold text-emerald-400 mb-2 flex items-center gap-1 bg-emerald-500/20 px-2 py-1 rounded-md w-fit"><i class="ph-bold ph-check"></i> Akan Diganti:</p>
                                            <img :src="newImagePreview" class="h-32 rounded-xl border-2 border-emerald-500 shadow-sm object-cover">
                                            <button type="button" @click="newImagePreview = null; $refs.editFileInput.value = ''" class="absolute -top-2 -right-2 bg-rose-500 text-white rounded-xl p-1.5 shadow-lg hover:bg-rose-600 transition"><i class="ph-bold ph-x text-sm"></i></button>
                                        </div>
                                    </template>
                                    <template x-if="!newImagePreview && editState.question_image && !deleteImage">
                                        <div class="relative w-fit group">
                                            <p class="text-[10px] font-bold text-slate-400 mb-2">Gambar Saat Ini:</p>
                                            <img :src="'/storage/' + editState.question_image" class="h-24 rounded-xl border border-white/15 shadow-sm object-cover opacity-90">
                                            <button type="button" @click="removeCurrentImage()" class="absolute inset-0 bg-rose-950/80 backdrop-blur-[1px] text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition rounded-xl font-bold text-xs gap-2"><i class="ph-bold ph-trash"></i> Hapus</button>
                                        </div>
                                    </template>
                                    <div class="flex-1">
                                        <input type="file" x-ref="editFileInput" name="question_image" @change="handleEditImage" accept="image/*" class="block w-full text-xs text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-sky-500/20 file:text-sky-300 hover:file:bg-sky-500/30 cursor-pointer border border-white/15 rounded-xl bg-slate-900/80">
                                    </div>
                                </div>
                            </div>

                            {{-- Audio Listening Soal (Edit) --}}
                            <div class="bg-slate-900/50 p-5 rounded-2xl border border-white/10">
                                <div class="flex items-center justify-between mb-3">
                                    <label class="block text-xs font-bold text-amber-400 uppercase ml-1">
                                        <i class="ph-fill ph-speaker-high"></i> Audio Listening Soal
                                    </label>
                                    <span class="text-[10px] text-slate-400">MP3, WAV, M4A, OGG</span>
                                </div>
                                <div class="space-y-4">
                                    <template x-if="newAudioPreview">
                                        <div class="p-3 bg-amber-500/10 rounded-xl border border-amber-500/30 flex items-center justify-between gap-3">
                                            <div>
                                                <p class="text-[10px] font-bold text-emerald-400 mb-1 flex items-center gap-1">
                                                    <i class="ph-bold ph-check"></i> Audio Baru Terpilih:
                                                </p>
                                                <audio :src="newAudioPreview" controls class="h-8 max-w-full"></audio>
                                            </div>
                                            <button type="button" @click="newAudioPreview = null; if($refs.editAudioInput) $refs.editAudioInput.value = ''" class="bg-rose-500 text-white p-2 rounded-xl shadow hover:bg-rose-600 transition">
                                                <i class="ph-bold ph-x text-xs"></i>
                                            </button>
                                        </div>
                                    </template>
                                    <template x-if="!newAudioPreview && editState.audio_path && !deleteAudio">
                                        <div class="p-3 bg-slate-800/80 rounded-xl border border-white/10 flex items-center justify-between gap-3">
                                            <div>
                                                <p class="text-[10px] font-bold text-slate-400 mb-1">Audio Saat Ini:</p>
                                                <audio :src="'/storage/' + editState.audio_path" controls class="h-8 max-w-full"></audio>
                                            </div>
                                            <button type="button" @click="removeCurrentAudio()" class="px-3 py-1.5 bg-rose-500/20 text-rose-300 hover:bg-rose-500 hover:text-white rounded-lg text-xs font-bold transition flex items-center gap-1 border border-rose-500/30">
                                                <i class="ph-bold ph-trash"></i> Hapus
                                            </button>
                                        </div>
                                    </template>
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                        <div class="sm:col-span-2">
                                            <input type="file" x-ref="editAudioInput" name="question_audio" @change="handleEditAudio" accept="audio/*" class="block w-full text-xs text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-amber-500/20 file:text-amber-300 hover:file:bg-amber-500/30 cursor-pointer border border-white/15 rounded-xl bg-slate-900/80">
                                        </div>
                                        <div>
                                            <div class="relative">
                                                <input type="number" name="audio_play_limit" min="1" max="10" x-model="editState.audio_play_limit" class="w-full rounded-xl border-white/15 bg-slate-900/80 text-white text-xs font-bold py-2.5 px-3 pl-8 focus:ring-amber-400 focus:border-amber-400" title="Batas putar untuk siswa">
                                                <span class="absolute inset-y-0 left-2.5 flex items-center text-amber-400 font-bold text-xs pointer-events-none">
                                                    <i class="ph-bold ph-repeat"></i>
                                                </span>
                                                <span class="absolute inset-y-0 right-2.5 flex items-center text-[10px] text-slate-400 font-medium pointer-events-none">x Putar</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- JAWABAN DINAMIS (EDIT) --}}
                            <div class="bg-slate-900/50 rounded-2xl p-4 border border-white/10">
                                <template x-if="editState.question_type === 'choice'">
                                    <div class="space-y-4">
                                        <label class="block text-xs font-bold text-sky-400 uppercase ml-1">Edit Pilihan & Gambar</label>
                                        @foreach(['A','B','C','D'] as $opt)
                                        <div class="flex gap-3 items-start">
                                            <span class="w-10 h-10 rounded-xl bg-slate-800 border border-white/10 text-slate-300 flex items-center justify-center font-black text-sm shrink-0 mt-1">{{ $opt }}</span>
                                            <div class="flex-1 space-y-2 min-w-0" x-data="{ optPreviewEdit: null, deleteOptImage: false }">
                                                <div class="flex gap-2 min-w-0">
                                                    <input type="text" name="option_{{ $opt }}" x-model="editState.option_{{ $opt }}" class="flex-1 min-w-0 rounded-xl border-white/15 bg-slate-900 text-sm py-2.5 px-4 focus:ring-sky-400 focus:border-sky-400 font-medium text-white placeholder-slate-500" placeholder="Teks opsi {{ $opt }}">
                                                    <label class="w-11 h-11 shrink-0 bg-slate-800 border border-white/10 rounded-xl flex items-center justify-center text-slate-400 hover:text-sky-300 hover:bg-slate-700 cursor-pointer transition" title="Ganti Gambar Opsi {{ $opt }}">
                                                        <i class="ph-bold ph-image text-lg"></i>
                                                        <input type="file" name="image_{{ $opt }}" accept="image/*" class="hidden" @change="optPreviewEdit = URL.createObjectURL($event.target.files[0]); deleteOptImage = false">
                                                    </label>
                                                </div>
                                                
                                                <input type="hidden" name="delete_image_{{ $opt }}" x-model="deleteOptImage">

                                                <template x-if="optPreviewEdit">
                                                    <div class="relative w-24 mt-2">
                                                        <p class="text-[10px] font-bold text-emerald-400 mb-1">Baru:</p>
                                                        <img :src="optPreviewEdit" class="h-24 w-24 object-cover rounded-xl border-2 border-emerald-500 shadow-sm">
                                                        <button type="button" @click="optPreviewEdit = null; $event.target.closest('.flex-1').querySelector('input[type=file]').value = ''" class="absolute top-4 -right-2 bg-rose-500 text-white rounded-lg p-1 shadow-md hover:bg-rose-600"><i class="ph-bold ph-x text-xs"></i></button>
                                                    </div>
                                                </template>
                                                
                                                <template x-if="!optPreviewEdit && editState.image_{{ $opt }} && !deleteOptImage">
                                                    <div class="relative w-24 group/optimg mt-2">
                                                        <p class="text-[10px] font-bold text-slate-400 mb-1">Saat ini:</p>
                                                        <img :src="'/storage/' + editState.image_{{ $opt }}" class="h-24 w-24 object-cover rounded-xl border border-white/15 shadow-sm">
                                                        <button type="button" @click="deleteOptImage = true" class="absolute inset-y-0 bottom-0 mt-5 inset-x-0 bg-rose-950/80 text-white flex items-center justify-center opacity-0 group-hover/optimg:opacity-100 transition rounded-xl font-bold text-xs gap-1"><i class="ph-bold ph-trash"></i> Hapus</button>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                        @endforeach
                                        <div class="mt-2 flex items-center">
                                            <label class="text-xs font-bold text-slate-300">Kunci:</label>
                                            <select name="correct_answer" x-model="editState.correct_answer" class="ml-2 rounded-lg border-white/15 text-xs font-bold py-1.5 px-3 bg-slate-900 text-white focus:ring-sky-400">
                                                <option value="A">A</option><option value="B">B</option><option value="C">C</option><option value="D">D</option>
                                            </select>
                                        </div>
                                    </div>
                                </template>

                                {{-- Tipe: Pilihan Ganda Kompleks (Edit) --}}
                                <template x-if="editState.question_type === 'complex'">
                                    <div class="space-y-4">
                                        <div class="flex items-center justify-between">
                                            <label class="block text-xs font-bold text-amber-400 uppercase ml-1">Edit Opsi & Centang Kunci Benar</label>
                                            <span class="text-[10px] text-amber-300 font-bold bg-amber-500/20 px-2 py-0.5 rounded-md">Multi-Jawaban</span>
                                        </div>
                                        @foreach(['A','B','C','D'] as $opt)
                                        <div class="flex gap-3 items-start">
                                            <label class="cursor-pointer shrink-0 mt-2 flex flex-col items-center gap-1" title="Centang jika benar">
                                                <input type="checkbox" name="complex_answers[]" value="{{ $opt }}" :checked="editState.complex_answers && editState.complex_answers.includes('{{ $opt }}')" class="rounded bg-slate-800 border-white/20 text-amber-500 focus:ring-amber-400 h-5 w-5">
                                                <span class="text-[10px] font-bold text-slate-300">{{ $opt }}</span>
                                            </label>
                                            <div class="flex-1 space-y-2 min-w-0" x-data="{ optPreviewEdit: null, deleteOptImage: false }">
                                                <div class="flex gap-2 min-w-0">
                                                    <input type="text" name="option_{{ $opt }}" x-model="editState.option_{{ $opt }}" class="flex-1 min-w-0 rounded-xl border-white/15 bg-slate-900 text-sm py-2.5 px-4 focus:ring-sky-400 focus:border-sky-400 font-medium text-white placeholder-slate-500" placeholder="Teks opsi {{ $opt }}">
                                                    <label class="w-11 h-11 shrink-0 bg-slate-800 border border-white/10 rounded-xl flex items-center justify-center text-slate-400 hover:text-sky-300 hover:bg-slate-700 cursor-pointer transition" title="Ganti Gambar Opsi {{ $opt }}">
                                                        <i class="ph-bold ph-image text-lg"></i>
                                                        <input type="file" name="image_{{ $opt }}" accept="image/*" class="hidden" @change="optPreviewEdit = URL.createObjectURL($event.target.files[0]); deleteOptImage = false">
                                                    </label>
                                                </div>
                                                
                                                <input type="hidden" name="delete_image_{{ $opt }}" x-model="deleteOptImage">
                
                                                <template x-if="optPreviewEdit">
                                                    <div class="relative w-24 mt-2">
                                                        <p class="text-[10px] font-bold text-emerald-400 mb-1">Baru:</p>
                                                        <img :src="optPreviewEdit" class="h-24 w-24 object-cover rounded-xl border-2 border-emerald-500 shadow-sm">
                                                        <button type="button" @click="optPreviewEdit = null; $event.target.closest('.flex-1').querySelector('input[type=file]').value = ''" class="absolute top-4 -right-2 bg-rose-500 text-white rounded-lg p-1 shadow-md hover:bg-rose-600">
                                                            <i class="ph-bold ph-x text-xs"></i>
                                                        </button>
                                                    </div>
                                                </template>
                                                
                                                <template x-if="!optPreviewEdit && editState.image_{{ $opt }} && !deleteOptImage">
                                                    <div class="relative w-24 group/optimg mt-2">
                                                        <p class="text-[10px] font-bold text-slate-400 mb-1">Saat ini:</p>
                                                        <img :src="'/storage/' + editState.image_{{ $opt }}" class="h-24 w-24 object-cover rounded-xl border border-white/15 shadow-sm">
                                                        <button type="button" @click="deleteOptImage = true" class="absolute inset-y-0 bottom-0 mt-5 inset-x-0 bg-rose-950/80 text-white flex items-center justify-center opacity-0 group-hover/optimg:opacity-100 transition rounded-xl font-bold text-xs gap-1">
                                                            <i class="ph-bold ph-trash"></i> Hapus
                                                        </button>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </template>

                                <template x-if="editState.question_type === 'true_false'">
                                    <div>
                                        <label class="block text-xs font-bold text-sky-400 uppercase mb-3 ml-1">Kunci Jawaban</label>
                                        <div class="flex gap-4">
                                            <label class="flex-1 cursor-pointer">
                                                <input type="radio" name="correct_answer" value="A" x-model="editState.correct_answer" class="peer sr-only">
                                                <div class="p-3 rounded-xl bg-slate-800/80 border border-white/10 text-slate-300 text-center peer-checked:bg-emerald-500/20 peer-checked:border-emerald-500 peer-checked:text-emerald-300 transition">Benar</div>
                                            </label>
                                            <label class="flex-1 cursor-pointer">
                                                <input type="radio" name="correct_answer" value="B" x-model="editState.correct_answer" class="peer sr-only">
                                                <div class="p-3 rounded-xl bg-slate-800/80 border border-white/10 text-slate-300 text-center peer-checked:bg-rose-500/20 peer-checked:border-rose-500 peer-checked:text-rose-300 transition">Salah</div>
                                            </label>
                                        </div>
                                    </div>
                                </template>

                                <template x-if="editState.question_type === 'matching'">
                                    <div class="space-y-3">
                                        <label class="block text-xs font-bold text-sky-400 uppercase ml-1">Pasangan (Teks & Gambar)</label>
                                        <template x-for="(pair, index) in editMatchPairs" :key="index">
                                            <div class="bg-slate-900/80 p-3 rounded-xl border border-white/10 shadow-sm space-y-3" x-data="{ leftPreviewEdit: null, rightPreviewEdit: null, delLeftImg: false, delRightImg: false }">
                                                
                                                <div class="flex items-center gap-2">
                                                    <!-- Edit Sisi Kiri -->
                                                    <div class="flex-1 flex items-center gap-2 min-w-0">
                                                        <input type="text" :name="'matches['+index+'][left]'" x-model="pair.left" class="w-full min-w-0 rounded-lg border-white/15 bg-slate-800/90 text-white text-xs px-3 h-9 focus:ring-sky-400">
                                                        <label class="shrink-0 w-9 h-9 bg-slate-800 border border-white/10 rounded-lg flex items-center justify-center text-slate-400 cursor-pointer hover:bg-slate-700 hover:text-sky-300 transition" title="Ganti Gambar Kiri">
                                                            <i class="ph-bold ph-image text-sm"></i>
                                                            <input type="file" :name="'matches['+index+'][left_image]'" accept="image/*" class="left-file-edit hidden" @change="leftPreviewEdit = URL.createObjectURL($event.target.files[0]); delLeftImg = false">
                                                        </label>
                                                    </div>
                                                    <input type="hidden" :name="'matches['+index+'][delete_left_image]'" x-model="delLeftImg">
                                                    
                                                    <!-- Panah Tengah -->
                                                    <div class="shrink-0 w-4 flex items-center justify-center text-slate-500">
                                                        <i class="ph-bold ph-arrow-right"></i>
                                                    </div>
                                                    
                                                    <!-- Edit Sisi Kanan -->
                                                    <div class="flex-1 flex items-center gap-2 min-w-0">
                                                        <input type="text" :name="'matches['+index+'][right]'" x-model="pair.right" class="w-full min-w-0 rounded-lg border-white/15 bg-slate-800/90 text-white text-xs px-3 h-9 focus:ring-sky-400">
                                                        <label class="shrink-0 w-9 h-9 bg-slate-800 border border-white/10 rounded-lg flex items-center justify-center text-slate-400 cursor-pointer hover:bg-slate-700 hover:text-sky-300 transition" title="Ganti Gambar Kanan">
                                                            <i class="ph-bold ph-image text-sm"></i>
                                                            <input type="file" :name="'matches['+index+'][right_image]'" accept="image/*" class="right-file-edit hidden" @change="rightPreviewEdit = URL.createObjectURL($event.target.files[0]); delRightImg = false">
                                                        </label>
                                                    </div>
                                                    <input type="hidden" :name="'matches['+index+'][delete_right_image]'" x-model="delRightImg">
                                                    
                                                    <!-- Tombol Hapus -->
                                                    <button type="button" @click="removeEditPair(index)" class="shrink-0 w-8 h-8 flex items-center justify-center text-rose-400 hover:text-rose-300 hover:bg-rose-500/20 rounded-lg transition" title="Hapus Pasangan">
                                                        <i class="ph-bold ph-trash text-base"></i>
                                                    </button>
                                                </div>

                                                <template x-if="leftPreviewEdit || (pair.left_image && !delLeftImg) || rightPreviewEdit || (pair.right_image && !delRightImg)">
                                                    <div class="flex items-start gap-2">
                                                        <!-- Preview Kiri -->
                                                        <div class="flex-1 min-w-0">
                                                            <template x-if="leftPreviewEdit">
                                                                <div class="relative w-fit mt-2">
                                                                    <p class="text-[9px] font-bold text-emerald-400 mb-1">Baru:</p>
                                                                    <img :src="leftPreviewEdit" class="h-16 w-16 object-cover rounded-lg border-2 border-emerald-500 shadow-sm">
                                                                    <button type="button" @click="leftPreviewEdit = null; $event.target.closest('.bg-slate-900').querySelector('.left-file-edit').value = ''" class="absolute top-4 -right-1.5 bg-rose-500 text-white rounded p-0.5"><i class="ph-bold ph-x text-[10px]"></i></button>
                                                                </div>
                              
                              </template>
                                                            <template x-if="!leftPreviewEdit && pair.left_image && !delLeftImg">
                                                                <div class="relative w-fit group/img mt-2">
                                                                    <p class="text-[9px] font-bold text-slate-400 mb-1">Saat ini:</p>
                                                                    <img :src="'/storage/' + pair.left_image" class="h-16 w-16 object-cover rounded-lg border border-white/15">
                                                                    <button type="button" @click="delLeftImg = true" class="absolute inset-y-0 bottom-0 mt-4 inset-x-0 bg-rose-950/80 text-white flex items-center justify-center opacity-0 group-hover/img:opacity-100 transition rounded-lg text-[10px] font-bold">Hapus</button>
                                                                </div>
                                                            </template>
                                                        </div>

                                                        <div class="shrink-0 w-4"></div>

                                                        <!-- Preview Kanan -->
                                                        <div class="flex-1 min-w-0">
                                                            <template x-if="rightPreviewEdit">
                                                                <div class="relative w-fit mt-2">
                                                                    <p class="text-[9px] font-bold text-emerald-400 mb-1">Baru:</p>
                                                                    <img :src="rightPreviewEdit" class="h-16 w-16 object-cover rounded-lg border-2 border-emerald-500 shadow-sm">
                                                                    <button type="button" @click="rightPreviewEdit = null; $event.target.closest('.bg-slate-900').querySelector('.right-file-edit').value = ''" class="absolute top-4 -right-1.5 bg-rose-500 text-white rounded p-0.5"><i class="ph-bold ph-x text-[10px]"></i></button>
                                                                </div>
                                                            </template>
                                                            <template x-if="!rightPreviewEdit && pair.right_image && !delRightImg">
                                                                <div class="relative w-fit group/img mt-2">
                                                                    <p class="text-[9px] font-bold text-slate-400 mb-1">Saat ini:</p>
                                                                    <img :src="'/storage/' + pair.right_image" class="h-16 w-16 object-cover rounded-lg border border-white/15">
                                                                    <button type="button" @click="delRightImg = true" class="absolute inset-y-0 bottom-0 mt-4 inset-x-0 bg-rose-950/80 text-white flex items-center justify-center opacity-0 group-hover/img:opacity-100 transition rounded-lg text-[10px] font-bold">Hapus</button>
                                                                </div>
                                                            </template>
                                                        </div>

                                                        <div class="shrink-0 w-8"></div>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>
                                        <button type="button" @click="addEditPair()" class="text-xs font-bold text-sky-400 hover:text-sky-300 flex items-center gap-1 mt-2">
                                            <i class="ph-bold ph-plus"></i> Tambah Pasangan
                                        </button>
                                    </div>
                                </template>

                                <template x-if="editState.question_type === 'essay'">
                                    <div>
                                        <label class="block text-xs font-bold text-sky-400 uppercase mb-2 ml-1">Kunci (Opsional)</label>
                                        <input type="text" name="correct_answer" x-model="editState.correct_answer" class="w-full rounded-xl border-white/15 bg-slate-900 text-white placeholder-slate-500 text-sm py-2 px-3 focus:ring-sky-400">
                                    </div>
                                </template>
                            </div>

                            {{-- Metadata Kesukaran & Taksonomi Bloom (Edit) --}}
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-sky-400 uppercase mb-2 ml-1">Tingkat Kesukaran</label>
                                    <select name="difficulty" x-model="editState.difficulty" class="w-full rounded-xl border-white/15 bg-slate-900 text-white text-xs font-bold h-11 focus:ring-sky-400 focus:border-sky-400">
                                        <option value="mudah">🟢 Mudah</option>
                                        <option value="sedang">🟡 Sedang</option>
                                        <option value="sulit">🔴 Sulit</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-sky-400 uppercase mb-2 ml-1">Taksonomi Bloom</label>
                                    <select name="bloom_taxonomy" x-model="editState.bloom_taxonomy" class="w-full rounded-xl border-white/15 bg-slate-900 text-white text-xs font-bold h-11 focus:ring-sky-400 focus:border-sky-400">
                                        <option value="C1">C1 - Mengingat</option>
                                        <option value="C2">C2 - Memahami</option>
                                        <option value="C3">C3 - Menerapkan</option>
                                        <option value="C4">C4 - Menganalisis</option>
                                        <option value="C5">C5 - Evaluasi</option>
                                        <option value="C6">C6 - Mencipta</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Bobot --}}
                            <div>
                                <label class="block text-xs font-bold text-sky-400 uppercase mb-2 ml-1">Bobot Nilai</label>
                                <input type="number" name="score_weight" x-model="editState.score_weight" required class="w-full rounded-xl border-white/15 bg-slate-900 text-sm font-bold text-white text-center h-11 focus:ring-sky-400">
                            </div>
                        </div>

                        <div class="bg-slate-900/80 px-8 py-5 flex justify-end gap-3 rounded-b-[2.5rem] border-t border-white/10 relative z-10">
                            <button type="button" @click="showEditModal = false" class="px-6 py-3 bg-slate-800 border border-white/10 text-slate-300 font-bold rounded-xl hover:bg-slate-700 transition text-sm">Batal</button>
                            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-sky-500 to-blue-600 text-white font-bold rounded-xl hover:from-sky-400 hover:to-blue-500 transition shadow-lg shadow-sky-500/25 text-sm flex items-center gap-2 border border-transparent">
                                <i class="ph-bold ph-floppy-disk text-lg"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- MODAL IMPORT EXCEL --}}
        <div x-show="showImportModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-md transition-opacity" @click="showImportModal = false"></div>
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="relative bg-[#020b18] rounded-[2.5rem] shadow-2xl max-w-md w-full p-8 border border-white/15">
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-emerald-500/20 border border-emerald-500/30 rounded-2xl flex items-center justify-center mx-auto mb-4 text-emerald-400 text-3xl">
                            <i class="ph-fill ph-microsoft-excel-logo"></i>
                        </div>
                        <h3 class="text-xl font-black text-white">Import Soal ke Bank</h3>
                        <p class="text-xs text-slate-400 mt-1">Upload file excel sesuai template bank soal.</p>
                    </div>
                    
                    <form action="{{ route('bank.questions.import', $bank->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        <div class="bg-slate-900/80 p-4 rounded-2xl border border-white/10 mb-4">
                            <p class="text-xs text-slate-300 font-medium leading-relaxed">
                                <i class="ph-bold ph-info text-sky-400"></i> Pastikan format file sesuai dengan template. Kolom 'soal' dan 'kunci' wajib diisi.
                            </p>
                            <a href="{{ route('bank.questions.template') }}" class="inline-flex items-center gap-1 mt-2 text-xs font-bold text-sky-400 hover:text-sky-300 hover:underline transition">
                                <i class="ph-bold ph-download-simple"></i> Download Template Excel
                            </a>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-sky-400 uppercase ml-1">Pilih File (.xlsx / .xls)</label>
                            <input type="file" name="file" required class="block w-full text-sm text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-500/20 file:text-emerald-300 hover:file:bg-emerald-500/30 cursor-pointer border border-white/15 rounded-xl bg-slate-900/80">
                        </div>

                        <div class="flex gap-3 pt-4">
                            <button type="button" @click="showImportModal = false" class="flex-1 py-3 bg-slate-800 border border-white/10 text-slate-300 font-bold rounded-xl hover:bg-slate-700 transition text-sm">Batal</button>
                            <button type="submit" class="flex-1 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-bold rounded-xl hover:from-emerald-400 hover:to-teal-500 transition shadow-lg shadow-emerald-500/20 text-sm">Mulai Import</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.btn-delete');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('.delete-form');
                    Swal.fire({
                        title: 'Hapus Soal Ini?', text: "Soal yang dihapus dari Bank Soal tidak dapat dikembalikan!", icon: 'warning',
                        showCancelButton: true, confirmButtonColor: '#e11d48', cancelButtonColor: '#64748b',
                        confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal', 
                        customClass: { popup: 'rounded-[2rem] bg-slate-900 text-white border border-white/10' }
                    }).then((result) => { if (result.isConfirmed) form.submit(); });
                });
            });
            @if(session('success'))
                Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, customClass: { popup: 'rounded-xl bg-slate-900 text-white border border-white/10' } });
            @endif

            @if($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Menyimpan!',
                    html: `
                        <div class="text-sm text-rose-400 font-medium text-left mt-2">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    `,
                    customClass: { popup: 'rounded-[2rem] bg-slate-900 text-white border border-white/10' }
                });
            @endif
        });
        function viewImage(url) {
            Swal.fire({ 
                imageUrl: url, 
                imageAlt: 'Gambar Soal', 
                showConfirmButton: false, 
                showCloseButton: true, 
                customClass: { popup: 'rounded-[2rem] bg-slate-900 text-white border border-white/10', image: 'rounded-2xl' }, 
                width: 'auto' 
            });
        }
    </script>
</x-app-layout>