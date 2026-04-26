<x-app-layout>
    {{-- LOAD TRIX EDITOR RESOURCES --}}
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
    
    <style>
        trix-toolbar .trix-button-group--file-tools { display: none; }
        trix-editor { min-height: 150px; background-color: #f8fafc; border-radius: 1rem; border-color: #e2e8f0; }
        .trix-content ul { list-style-type: disc; padding-left: 1.5rem; }
        .trix-content ol { list-style-type: decimal; padding-left: 1.5rem; }       
        .trix-button-group { background-color: white; }
        /* FIX: Tambahan Z-Index agar toolbar Trix tidak tertutup elemen lain */
        trix-toolbar { position: relative; z-index: 40; } 
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
                customClass: { popup: 'rounded-[2rem]' }
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
                customClass: { popup: 'rounded-[2rem]' }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('bulkDeleteForm').submit();
                }
            });
        },
        // --- END BULK ACTION ---

        // State untuk Input Soal Baru
        createType: '{{ old('question_type', 'choice') }}',
        createQuestionText: {{ json_encode(old('question_text', '')) }},
        matchPairs: {{ json_encode(old('matches', [['left' => '', 'right' => '']])) }}, 
        createTags: {{ old('tags') ? json_encode(array_map('trim', explode(',', old('tags')))) : '[]' }},
        newTag: '',

        // State Edit
        editState: {
            id: null,
            url: '', 
            question_type: 'choice', 
            question_text: '', 
            question_image: '', 
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
            
            this.editState = { 
                ...this.editState, 
                ...data, 
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
            this.deleteImage = true; this.newImagePreview = null; this.$refs.editFileInput.value = '';
        }
    }">
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-[#2c3f61] leading-tight">{{ __('Kelola Bank Soal') }}</h2>
        </x-slot>

        <div class="py-8 sm:py-10 font-sans text-[#2c3f61]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
                
                {{-- HERO INFO MICROSOFT ELEVATE THEME --}}
                <div class="relative rounded-[2rem] bg-gradient-to-r from-[#56bbf1] via-[#e5eff5] to-[#f4d1c0] p-8 text-[#2c3f61] shadow-xl shadow-[#56bbf1]/10 overflow-hidden border border-white/60">
                    <div class="absolute -top-10 -left-10 w-48 h-48 bg-[#0d52a1]/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-3xl"></div>
                    <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-[#f9a282]/20 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-2xl"></div>
                    <div class="absolute top-10 right-32 w-24 h-24 bg-white/40 rounded-2xl rotate-45 pointer-events-none shadow-sm"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-2 mb-2">
                                <a href="{{ route('bank.index') }}" class="text-xs font-bold text-[#0d52a1] hover:text-[#2c3f61] transition flex items-center gap-1 bg-white/60 px-3 py-1 rounded-full border border-white backdrop-blur-sm shadow-sm"><i class="ph-bold ph-arrow-left"></i> Kembali</a>
                                <span class="text-[#2c3f61]/30 text-xs">•</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wide bg-white/60 text-[#2c3f61] border border-white shadow-sm">{{ $bank->subject_name }}</span>
                            </div>
                        <h1 class="text-4xl font-extrabold tracking-tight leading-none text-[#2c3f61] mb-2">{{ $bank->title }}</h1>
                        <p class="text-[#2c3f61]/80 text-sm font-medium">Total: {{ $bank->questions->count() }} Soal • Kode: {{ $bank->code }}</p>
                    </div>
                </div>

                <div class="flex flex-col lg:flex-row gap-8 items-start">
                    
                    {{-- FORM INPUT SOAL (CREATE) --}}
                    <div class="w-full lg:w-2/5 order-2 lg:order-1">
                        <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 lg:sticky lg:top-8">
                            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-50">
                                <div class="w-10 h-10 rounded-2xl bg-[#56bbf1]/10 text-[#0d52a1] flex items-center justify-center text-lg shadow-sm border border-[#56bbf1]/20"><i class="ph-fill ph-plus-circle"></i></div>
                                <div>
                                    <h3 class="font-black text-[#2c3f61] text-lg leading-none">Tambah Soal</h3>
                                    <p class="text-xs text-slate-400 font-bold mt-1 uppercase tracking-wider">Ke Bank Soal</p>
                                </div>
                            </div>

                            <form action="{{ route('bank.questions.store', $bank->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5" id="createQuestionForm">
                                @csrf
                                
                                {{-- Pilih Tipe (Create) --}}
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Tipe Soal</label>
                                    <div class="relative">
                                        <select name="question_type" x-model="createType" class="w-full rounded-xl border-slate-200 bg-slate-50 text-sm font-bold text-[#2c3f61] py-3 px-4 focus:ring-[#56bbf1] focus:border-[#56bbf1] cursor-pointer">
                                            <option value="choice">Pilihan Ganda</option>
                                            <option value="true_false">Benar / Salah</option>
                                            <option value="matching">Menjodohkan (Matching)</option>
                                            <option value="essay">Isian Singkat / Essai</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-500"><i class="ph-bold ph-caret-down"></i></div>
                                    </div>
                                </div>

                                {{-- NEW: INPUT TAGS / KD --}}
                                <div class="bg-[#e5eff5]/50 p-4 rounded-2xl border border-[#56bbf1]/20">
                                    <label class="block text-xs font-bold text-[#0d52a1] uppercase mb-2 ml-1"><i class="ph-fill ph-tag"></i> Materi / KD (Opsional)</label>
                                    <div class="flex flex-wrap gap-2 mb-2">
                                        <template x-for="(tag, index) in createTags" :key="index">
                                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-[#56bbf1]/20 text-[#0d52a1] rounded-lg text-xs font-bold border border-[#56bbf1]/30">
                                                <span x-text="tag"></span>
                                                <button type="button" @click="createTags.splice(index, 1)" class="hover:text-rose-500"><i class="ph-bold ph-x"></i></button>
                                            </span>
                                        </template>
                                    </div>
                                    <div class="flex gap-2">
                                        <input type="text" x-model="newTag" @keydown.enter.prevent="addTag(newTag, 'create')" placeholder="Ketik lalu Enter..." class="w-full rounded-xl border-slate-200 text-sm py-2 px-3 focus:ring-[#56bbf1] focus:border-[#56bbf1]">
                                        <button type="button" @click="addTag(newTag, 'create')" class="px-4 bg-[#2c3f61] text-white rounded-xl hover:bg-[#1c2940] font-bold text-xs shadow-sm">Tambah</button>
                                    </div>
                                    <input type="hidden" name="tags" :value="createTags.join(',')">
                                </div>

                                {{-- Editor --}}
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Pertanyaan / Instruksi</label>
                                    <input id="q_input_create" type="hidden" name="question_text" value="{{ old('question_text') }}">
                                    <trix-editor id="create-trix-editor" input="q_input_create" @trix-change="createQuestionText = $event.target.value" placeholder="Tulis soal di sini..." class="prose prose-sm max-w-none focus:ring-[#56bbf1] focus:border-[#56bbf1] text-[#2c3f61]"></trix-editor>
                                </div>

                                {{-- Upload Gambar --}}
                                <div x-data="{ createPreviewImage: null }">
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Gambar (Opsional)</label>
                                    <template x-if="createPreviewImage">
                                        <div class="mb-3 relative w-full group">
                                            <img :src="createPreviewImage" class="w-full h-40 object-cover rounded-2xl border border-slate-200">
                                            <button type="button" @click="createPreviewImage = null; $refs.fileInput.value = ''" class="absolute top-2 right-2 bg-rose-500 text-white p-1.5 rounded-xl shadow-lg hover:bg-rose-600 transition"><i class="ph-bold ph-trash"></i></button>
                                        </div>
                                    </template>
                                    <label class="flex flex-col items-center justify-center w-full h-16 border-2 border-dashed border-slate-200 rounded-2xl cursor-pointer hover:bg-[#e5eff5] hover:border-[#56bbf1] transition-all bg-slate-50">
                                        <div class="flex items-center gap-2"><i class="ph-duotone ph-image text-xl text-slate-400"></i><span class="text-xs font-bold text-slate-500">Upload Gambar</span></div>
                                        <input type="file" x-ref="fileInput" name="question_image" @change="createPreviewImage = URL.createObjectURL($event.target.files[0])" accept="image/*" class="hidden">
                                    </label>
                                </div>

                                {{-- Input Jawaban Dinamis (Create) --}}
                                <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                                    
                                    {{-- Tipe 1: Pilihan Ganda --}}
                                    <template x-if="createType === 'choice'">
                                        <div class="space-y-4">
                                            <label class="block text-xs font-bold text-slate-400 uppercase ml-1">Opsi Jawaban & Gambar</label>
                                            @foreach(['A','B','C','D'] as $opt)
                                            <div class="flex gap-3 items-start group">
                                                <span class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 flex items-center justify-center font-black text-xs shrink-0 mt-1">{{ $opt }}</span>
                                                <div class="flex-1 space-y-2" x-data="{ optPreview: null }">
                                                    <div class="flex gap-2">
                                                        <input type="text" name="option_{{ $opt }}" value="{{ old('option_'.$opt) }}" class="flex-1 rounded-xl border-slate-200 bg-white text-sm py-2 px-3 focus:ring-[#56bbf1] focus:border-[#56bbf1]" placeholder="Teks Jawaban {{ $opt }}">
                                                        <label class="w-10 h-10 shrink-0 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-400 hover:text-[#0d52a1] hover:bg-[#e5eff5] cursor-pointer transition" title="Upload Gambar Opsi {{ $opt }}">
                                                            <i class="ph-bold ph-image text-lg"></i>
                                                            <input type="file" name="image_{{ $opt }}" accept="image/*" class="hidden" @change="optPreview = URL.createObjectURL($event.target.files[0])">
                                                        </label>
                                                    </div>
                                                    <template x-if="optPreview">
                                                        <div class="relative w-24 mt-2">
                                                            <img :src="optPreview" class="h-24 w-24 object-cover rounded-xl border border-slate-200 shadow-sm">
                                                            <button type="button" @click="optPreview = null; $event.target.closest('.flex-1').querySelector('input[type=file]').value = ''" class="absolute -top-2 -right-2 bg-rose-500 text-white rounded-lg p-1 shadow-md hover:bg-rose-600"><i class="ph-bold ph-x text-xs"></i></button>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                            @endforeach
                                            <div class="mt-2">
                                                <label class="text-xs font-bold text-slate-500">Kunci Jawaban:</label>
                                                <select name="correct_answer" class="ml-2 rounded-lg border-slate-200 text-xs font-bold py-1 px-3 bg-white focus:ring-[#56bbf1] focus:border-[#56bbf1]">
                                                    <option value="A" {{ old('correct_answer') == 'A' ? 'selected' : '' }}>A</option>
                                                    <option value="B" {{ old('correct_answer') == 'B' ? 'selected' : '' }}>B</option>
                                                    <option value="C" {{ old('correct_answer') == 'C' ? 'selected' : '' }}>C</option>
                                                    <option value="D" {{ old('correct_answer') == 'D' ? 'selected' : '' }}>D</option>
                                                </select>
                                            </div>
                                        </div>
                                    </template>

                                    {{-- Tipe 2: Benar Salah --}}
                                    <template x-if="createType === 'true_false'">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-400 uppercase mb-3 ml-1">Kunci Jawaban</label>
                                            <div class="flex gap-4">
                                                <label class="flex-1 cursor-pointer">
                                                    <input type="radio" name="correct_answer" value="A" {{ old('correct_answer') == 'A' ? 'checked' : '' }} class="peer sr-only">
                                                    <div class="p-3 rounded-xl bg-white border border-slate-200 text-center peer-checked:bg-emerald-50 peer-checked:border-emerald-500 peer-checked:text-emerald-700 transition"><span class="font-bold text-sm">BENAR</span></div>
                                                </label>
                                                <label class="flex-1 cursor-pointer">
                                                    <input type="radio" name="correct_answer" value="B" {{ old('correct_answer') == 'B' ? 'checked' : '' }} class="peer sr-only">
                                                    <div class="p-3 rounded-xl bg-white border border-slate-200 text-center peer-checked:bg-rose-50 peer-checked:border-rose-500 peer-checked:text-rose-700 transition"><span class="font-bold text-sm">SALAH</span></div>
                                                </label>
                                            </div>
                                        </div>
                                    </template>

                                    {{-- Tipe 3: Menjodohkan --}}
                                    <template x-if="createType === 'matching'">
                                        <div class="space-y-3">
                                            <label class="block text-xs font-bold text-slate-400 uppercase ml-1">Pasangan (Teks & Gambar)</label>
                                            <template x-for="(pair, index) in matchPairs" :key="index">
                                                <div class="bg-white p-3 rounded-xl border border-slate-200 shadow-sm space-y-3" x-data="{ leftPreview: null, rightPreview: null }">
                                                    
                                                    <!-- BARIS 1: INPUT TEKS DAN IKON -->
                                                    <div class="flex items-center gap-2">
                                                        <!-- Sisi Kiri -->
                                                        <div class="flex-1 flex items-center gap-2 min-w-0">
                                                            <input type="text" :name="'matches['+index+'][left]'" x-model="pair.left" class="w-full min-w-0 rounded-lg border-slate-200 text-xs px-3 h-9 focus:ring-[#56bbf1] focus:border-[#56bbf1]" placeholder="Teks Kiri">
                                                            <label class="shrink-0 w-9 h-9 bg-slate-50 border border-slate-200 rounded-lg flex items-center justify-center text-slate-400 hover:text-[#0d52a1] hover:bg-[#e5eff5] cursor-pointer transition" title="Gambar Kiri">
                                                                <i class="ph-bold ph-image text-sm"></i>
                                                                <input type="file" :name="'matches['+index+'][left_image]'" accept="image/*" class="left-file-input hidden" @change="leftPreview = URL.createObjectURL($event.target.files[0])">
                                                            </label>
                                                        </div>
                                                        
                                                        <!-- Panah Tengah -->
                                                        <div class="shrink-0 w-4 flex items-center justify-center text-slate-300">
                                                            <i class="ph-bold ph-arrow-right"></i>
                                                        </div>

                                                        <!-- Sisi Kanan -->
                                                        <div class="flex-1 flex items-center gap-2 min-w-0">
                                                            <input type="text" :name="'matches['+index+'][right]'" x-model="pair.right" class="w-full min-w-0 rounded-lg border-slate-200 text-xs px-3 h-9 focus:ring-[#56bbf1] focus:border-[#56bbf1]" placeholder="Teks Kanan">
                                                            <label class="shrink-0 w-9 h-9 bg-slate-50 border border-slate-200 rounded-lg flex items-center justify-center text-slate-400 hover:text-[#0d52a1] hover:bg-[#e5eff5] cursor-pointer transition" title="Gambar Kanan">
                                                                <i class="ph-bold ph-image text-sm"></i>
                                                                <input type="file" :name="'matches['+index+'][right_image]'" accept="image/*" class="right-file-input hidden" @change="rightPreview = URL.createObjectURL($event.target.files[0])">
                                                            </label>
                                                        </div>
                                                        
                                                        <!-- Tombol Hapus -->
                                                        <button type="button" @click="removePair(index)" class="shrink-0 w-8 h-8 flex items-center justify-center text-rose-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus Pasangan">
                                                            <i class="ph-bold ph-trash text-base"></i>
                                                        </button>
                                                    </div>

                                                    <!-- BARIS 2: PREVIEW GAMBAR -->
                                                    <template x-if="leftPreview || rightPreview">
                                                        <div class="flex items-start gap-2">
                                                            <!-- Preview Kiri -->
                                                            <div class="flex-1 min-w-0">
                                                                <template x-if="leftPreview">
                                                                    <div class="relative w-fit">
                                                                        <img :src="leftPreview" class="h-16 w-16 object-cover rounded-lg border border-slate-200 shadow-sm">
                                                                        <button type="button" @click="leftPreview = null; $event.target.closest('.bg-white').querySelector('.left-file-input').value = ''" class="absolute -top-1.5 -right-1.5 bg-rose-500 text-white rounded-md p-0.5 shadow hover:bg-rose-600"><i class="ph-bold ph-x text-[10px]"></i></button>
                                                                    </div>
                                                                </template>
                                                            </div>

                                                            <!-- Spacer Panah -->
                                                            <div class="shrink-0 w-4"></div>

                                                            <!-- Preview Kanan -->
                                                            <div class="flex-1 min-w-0">
                                                                <template x-if="rightPreview">
                                                                    <div class="relative w-fit">
                                                                        <img :src="rightPreview" class="h-16 w-16 object-cover rounded-lg border border-slate-200 shadow-sm">
                                                                        <button type="button" @click="rightPreview = null; $event.target.closest('.bg-white').querySelector('.right-file-input').value = ''" class="absolute -top-1.5 -right-1.5 bg-rose-500 text-white rounded-md p-0.5 shadow hover:bg-rose-600"><i class="ph-bold ph-x text-[10px]"></i></button>
                                                                    </div>
                                                                </template>
                                                            </div>

                                                            <!-- Spacer Hapus -->
                                                            <div class="shrink-0 w-8"></div>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>
                                            <button type="button" @click="addPair()" class="text-xs font-bold text-[#0d52a1] hover:text-[#2c3f61] flex items-center gap-1 mt-2"><i class="ph-bold ph-plus"></i> Tambah Pasangan</button>
                                        </div>
                                    </template>

                                    {{-- Tipe 4: Essai --}}
                                    <template x-if="createType === 'essay'">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Kunci (Opsional)</label>
                                            <input type="text" name="correct_answer" value="{{ old('correct_answer') }}" class="w-full rounded-xl border-slate-200 text-sm py-2 px-3 focus:ring-[#56bbf1] focus:border-[#56bbf1]" placeholder="Jawaban singkat (Auto-grade)">
                                        </div>
                                    </template>
                                </div>

                                {{-- Bobot --}}
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Bobot Nilai</label>
                                    <input type="number" name="score_weight" value="{{ old('score_weight', 2) }}" required class="w-full rounded-xl border-slate-200 bg-slate-50 text-sm font-bold text-center h-11 focus:ring-[#56bbf1] focus:border-[#56bbf1]">
                                </div>

                                <button type="submit" class="w-full py-3.5 bg-[#2c3f61] text-white font-bold rounded-xl hover:bg-[#1c2940] transition shadow-lg shadow-[#2c3f61]/20 flex items-center justify-center gap-2 transform active:scale-95">
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
                                <h3 class="font-black text-[#2c3f61] text-lg flex items-center gap-2">
                                    <i class="ph-fill ph-list-dashes text-[#0d52a1]"></i> Isi Bank Soal
                                </h3>
                                
                                {{-- TOMBOL IMPORT --}}
                                <button @click="showImportModal = true" class="px-3 py-1.5 bg-[#2c3f61] text-white rounded-lg text-xs font-bold hover:bg-[#1c2940] transition shadow-sm shadow-[#2c3f61]/20 flex items-center gap-1.5 ml-2">
                                    <i class="ph-bold ph-file-arrow-up text-base"></i> Import
                                </button>                                  

                                @if($bank->questions->count() > 0)
                                    {{-- TOMBOL EXPORT EXCEL --}}
                                    <a href="{{ route('bank.questions.export', $bank->id) }}" class="px-3 py-1.5 bg-emerald-600 text-white rounded-lg text-xs font-bold hover:bg-emerald-700 transition shadow-sm flex items-center gap-1.5">
                                        <i class="ph-bold ph-file-xls text-base"></i> Excel
                                    </a>                                        

                                    {{-- TOMBOL EXPORT WORD --}}
                                    <a href="{{ route('bank.export_word', $bank->id) }}" class="px-3 py-1.5 bg-[#0d52a1] text-white rounded-lg text-xs font-bold hover:bg-blue-800 transition shadow-sm flex items-center gap-1.5">
                                        <i class="ph-bold ph-file-doc text-base"></i> Word
                                    </a>                                        

                                    {{-- TOMBOL PREVIEW --}}
                                    <a href="{{ route('bank.preview', $bank->id) }}" target="_blank" class="px-3 py-1.5 bg-slate-800 text-white rounded-lg text-xs font-bold hover:bg-slate-900 transition shadow-sm flex items-center gap-1.5">
                                        <i class="ph-bold ph-desktop text-base"></i> Pratinjau
                                    </a>

                                    {{-- TOMBOL CETAK PDF --}}
                                    <a href="{{ route('bank.questions.print', $bank->id) }}" target="_blank" class="px-3 py-1.5 bg-white text-slate-600 border border-slate-200 rounded-lg text-xs font-bold hover:text-[#0d52a1] hover:bg-[#e5eff5] hover:border-[#56bbf1]/50 transition shadow-sm flex items-center gap-1.5">
                                        <i class="ph-bold ph-printer text-base"></i> PDF
                                    </a>
                                @endif
                            </div>                            
                        </div>
                        <div class="flex flex-col sm:flex-row justify-between items-center px-2 gap-4">

                            {{-- JUMLAH SOAL --}}
                            <span class="text-xs font-bold text-[#0d52a1] bg-[#56bbf1]/10 border border-[#56bbf1]/20 px-3 py-1.5 rounded-lg shadow-sm flex items-center gap-1.5" title="Total Nilai">
                                <i class=" ph-fill ph-book-open-text text-base"></i> Soal {{ $bank->questions->count() }}
                            </span>

                            {{-- TOTAL POIN --}}
                            <span class="text-xs font-bold text-[#c86845] bg-[#f9a282]/10 border border-[#f9a282]/20 px-3 py-1.5 rounded-lg shadow-sm flex items-center gap-1.5" title="Akumulasi Bobot Nilai">
                                <i class="ph-fill ph-chart-bar text-base"></i> Poin {{ $totalPoints ?? 0 }}
                            </span>

                            {{-- CHECKBOX PILIH SEMUA --}}
                            @if($bank->questions->count() > 0)
                                <label class="flex items-center gap-2 cursor-pointer bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-100 transition">
                                    <input type="checkbox" @change="toggleSelectAll($event)" class="w-4 h-4 rounded border-slate-300 text-[#0d52a1] focus:ring-[#56bbf1]">
                                    <span class="text-xs font-bold text-slate-600">Pilih Semua</span>
                                </label>
                            @endif
                            {{-- KOLOM PENCARIAN --}}
                            <div class="relative w-full md:w-80 shrink-0">
                                <i class="ph-bold ph-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input type="text" x-model="questionSearch" placeholder="Cari pertanyaan atau tag..." class="w-full pl-10 pr-4 py-2 text-sm font-bold border-slate-200 rounded-xl focus:ring-[#56bbf1] focus:border-[#56bbf1] bg-white shadow-sm transition">
                            </div>
                        </div>                      
                        
                        {{-- ACTION BAR MUNCUL SAAT ADA YANG DIPILIH --}}
                        <div x-show="selectedQuestions.length > 0" x-transition class="bg-[#e5eff5] border border-[#56bbf1]/30 rounded-[1.5rem] p-4 flex flex-col sm:flex-row justify-between items-center gap-4 shadow-sm" style="display: none;">
                            <div class="text-sm font-bold text-[#0d52a1] flex items-center gap-2">
                                <i class="ph-fill ph-check-circle text-[#56bbf1] text-lg"></i>
                                <span x-text="selectedQuestions.length"></span> Soal Terpilih
                            </div>
                            <div class="flex gap-2 w-full sm:w-auto">
                                <button type="button" @click="promptBulkWeight()" class="flex-1 sm:flex-none px-4 py-2 bg-white text-[#0d52a1] border border-[#56bbf1]/50 rounded-xl text-xs font-bold hover:bg-[#56bbf1] hover:text-white transition shadow-sm">Ubah Bobot</button>
                                <button type="button" @click="confirmBulkDelete()" class="flex-1 sm:flex-none px-4 py-2 bg-white text-rose-600 border border-rose-200 rounded-xl text-xs font-bold hover:bg-rose-600 hover:text-white transition shadow-sm">Hapus</button>
                            </div>
                        </div>

                        @forelse($bank->questions as $index => $q)
                            @php 
                                $qType = $q->question_type ?? 'choice'; 
                                $searchableText = strtolower(strip_tags($q->question_text) . ' ' . ($q->option_A ?? '') . ' ' . ($q->option_B ?? '') . ' ' . ($q->option_C ?? '') . ' ' . ($q->option_D ?? '') . ' ' . ($q->tags ?? ''));
                            @endphp

                            <div data-search="{{ $searchableText }}"
                                 x-show="questionSearch === '' || $el.dataset.search.includes(questionSearch.toLowerCase())"
                                 class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm relative group hover:border-[#56bbf1]/40 hover:shadow-lg transition-all duration-300">
                            
                            <div class="absolute top-6 left-6 w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center font-black text-slate-500 text-sm group-hover:bg-[#2c3f61] group-hover:text-white transition-colors shadow-inner">{{ $index + 1 }}</div>
                            
                            {{-- CHECKBOX ITEM --}}
                            <div class="absolute top-8 left-[4.5rem] z-10">
                                <input type="checkbox" value="{{ $q->id }}" x-model="selectedQuestions" class="question-checkbox w-5 h-5 rounded border-slate-300 text-[#0d52a1] focus:ring-[#56bbf1] cursor-pointer shadow-sm">
                            </div>

                            <div class="pl-20 sm:pl-24">
                                {{-- Badge Tipe Soal --}}
                                    <div class="mb-2">
                                        @if($qType == 'choice') <span class="text-[10px] font-bold bg-[#56bbf1]/10 text-[#0d52a1] px-2 py-0.5 rounded border border-[#56bbf1]/20">PILIHAN GANDA</span>
                                        @elseif($qType == 'true_false') <span class="text-[10px] font-bold bg-[#f9a282]/10 text-[#c86845] px-2 py-0.5 rounded border border-[#f9a282]/20">BENAR / SALAH</span>
                                        @elseif($qType == 'matching') <span class="text-[10px] font-bold bg-[#e5eff5] text-[#2c3f61] px-2 py-0.5 rounded border border-slate-200">MENJODOHKAN</span>
                                        @elseif($qType == 'essay') <span class="text-[10px] font-bold bg-slate-100 text-slate-600 px-2 py-0.5 rounded border border-slate-200">ESSAI</span>
                                        @endif
                                    </div>

                                    {{-- Tampilkan TAGS --}}
                                    @if(!empty($q->tags))
                                        <div class="mb-3 flex flex-wrap gap-1">
                                            @foreach(explode(',', $q->tags) as $tag)
                                                <span class="text-[10px] font-bold bg-[#56bbf1]/10 text-[#0d52a1] px-2 py-0.5 rounded border border-[#56bbf1]/20 flex items-center gap-1">
                                                    <i class="ph-fill ph-tag"></i> {{ trim($tag) }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif

                                    @if($q->question_image)
                                        <div class="mb-4 group/img relative w-fit">
                                            <img src="{{ asset('storage/' . $q->question_image) }}" class="max-h-48 rounded-2xl border border-slate-100 shadow-sm object-cover cursor-zoom-in hover:opacity-90 transition" onclick="viewImage('{{ asset('storage/' . $q->question_image) }}')">
                                        </div>
                                    @endif
                                    
                                    <div class="text-[#2c3f61] font-medium text-lg mb-5 leading-relaxed trix-content prose prose-sm max-w-none">{!! $q->question_text !!}</div>
                                    
                                    {{-- Preview Jawaban (Read Only) --}}
                                    @if($qType == 'choice')
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                                            @foreach(['A','B','C','D'] as $opt)
                                                @php 
                                                    $val = isset($q->{'option_'.$opt}) ? $q->{'option_'.$opt} : ($q->options[$opt] ?? '-'); 
                                                    $imgVal = isset($q->{'image_'.$opt}) ? $q->{'image_'.$opt} : ($q->options['image_'.$opt] ?? null);
                                                @endphp
                                                <div class="flex items-start gap-3 p-2.5 rounded-xl border transition-colors {{ $opt == $q->correct_answer ? 'bg-emerald-50 border-emerald-200' : 'bg-slate-50/50 border-transparent' }}">
                                                    <span class="w-6 h-6 flex items-center justify-center rounded-lg border {{ $opt == $q->correct_answer ? 'border-emerald-500 bg-emerald-500 text-white' : 'border-slate-300 text-slate-400 bg-white' }} text-[10px] font-black shrink-0 mt-0.5">{{ $opt }}</span>
                                                    <div class="flex-1 overflow-hidden">
                                                        @if($val && $val !== '-')
                                                            <span class="leading-relaxed block {{ $opt == $q->correct_answer ? 'text-emerald-800 font-bold' : 'text-slate-600 font-medium' }}">{{ $val }}</span>
                                                        @endif
                                                        @if($imgVal)
                                                            <img src="{{ asset('storage/' . $imgVal) }}" class="mt-2 max-h-24 rounded-lg border border-slate-200 object-cover cursor-zoom-in hover:opacity-90 transition" onclick="viewImage('{{ asset('storage/' . $imgVal) }}')">
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @elseif($qType == 'matching')
                                        <div class="text-xs text-slate-500 bg-slate-50 p-3 rounded-xl border border-slate-200">
                                            <p class="font-bold mb-2">Pasangan:</p>
                                            @php 
                                                $pairs = is_string($q->options) ? json_decode($q->options, true)['pairs'] ?? [] : $q->options['pairs'] ?? [];
                                            @endphp
                                            <div class="space-y-2">
                                                @foreach($pairs as $p)
                                                    <div class="flex items-center gap-3 bg-white p-2 rounded-lg border border-slate-100 shadow-sm">
                                                        <div class="flex-1 flex flex-col sm:flex-row items-center sm:items-start gap-2 text-center sm:text-left">
                                                            @if(isset($p['left_image']) && $p['left_image'])
                                                                <img src="{{ asset('storage/' . $p['left_image']) }}" class="w-10 h-10 rounded border border-slate-200 object-cover cursor-zoom-in" onclick="viewImage('{{ asset('storage/' . $p['left_image']) }}')">
                                                            @endif
                                                            @if(isset($p['left']) && $p['left'] !== '')
                                                                <span class="font-medium text-[#2c3f61] mt-1 sm:mt-0">{{ $p['left'] }}</span>
                                                            @endif
                                                        </div>
                                                        <i class="ph-bold ph-arrows-left-right text-[#56bbf1]"></i>
                                                        <div class="flex-1 flex flex-col sm:flex-row items-center sm:items-start gap-2 text-center sm:text-left">
                                                            @if(isset($p['right_image']) && $p['right_image'])
                                                                <img src="{{ asset('storage/' . $p['right_image']) }}" class="w-10 h-10 rounded border border-slate-200 object-cover cursor-zoom-in" onclick="viewImage('{{ asset('storage/' . $p['right_image']) }}')">
                                                            @endif
                                                            @if(isset($p['right']) && $p['right'] !== '')
                                                                <span class="font-medium text-[#2c3f61] mt-1 sm:mt-0">{{ $p['right'] }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @elseif($qType == 'essay')
                                        <div class="text-xs text-slate-500 bg-slate-50 p-3 rounded-xl border border-slate-200">
                                            <span class="font-bold">Kunci:</span> <span class="text-[#2c3f61] font-medium">{{ $q->correct_answer ?: '(Koreksi Manual)' }}</span>
                                        </div>
                                    @elseif($qType == 'true_false')
                                        <div class="flex gap-2">
                                            <span class="px-3 py-1 rounded-lg border text-xs font-bold {{ $q->correct_answer == 'A' ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 'bg-slate-50 text-slate-400' }}">BENAR</span>
                                            <span class="px-3 py-1 rounded-lg border text-xs font-bold {{ $q->correct_answer == 'B' ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 'bg-slate-50 text-slate-400' }}">SALAH</span>
                                        </div>
                                    @endif
                                    
                                    <div class="mt-5 pt-3 border-t border-slate-50 flex items-center gap-3">
                                        <span class="text-[10px] font-bold text-[#0d52a1] uppercase bg-[#56bbf1]/10 px-2 py-1 rounded-md border border-[#56bbf1]/20">Bobot: {{ $q->score_weight }} Poin</span>
                                    </div>
                                </div>

                                {{-- ACTION BUTTONS --}}
                                <div class="absolute top-6 right-6 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    {{-- Button Edit --}}
                                    <button type="button" @click="openEdit({
                                            question_type: '{{ $qType }}',
                                            question_text: {{ json_encode($q->question_text) }},
                                            question_image: {{ json_encode($q->question_image) }}, 
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
                                            tags: '{{ addslashes($q->tags ?? '') }}'
                                        }, '{{ route('bank.questions.update', $q->id) }}')" 
                                        class="w-9 h-9 rounded-xl bg-white border border-[#f9a282]/50 text-[#c86845] hover:bg-[#f9a282]/10 shadow-sm flex items-center justify-center transition-all hover:scale-105">
                                        <i class="ph-bold ph-pencil-simple text-lg"></i>
                                    </button>
                                    
                                    {{-- Button Delete --}}
                                    <form action="{{ route('bank.questions.destroy', $q->id) }}" method="POST" class="delete-form">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-9 h-9 rounded-xl bg-white border border-rose-200 text-rose-400 hover:text-rose-600 hover:border-rose-300 hover:bg-rose-50 shadow-sm flex items-center justify-center transition-all hover:scale-105 btn-delete">
                                            <i class="ph-bold ph-trash text-lg"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-16 bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200">
                                <div class="w-20 h-20 bg-[#56bbf1]/10 rounded-full flex items-center justify-center mx-auto mb-4 text-[#0d52a1] animate-pulse"><i class="ph-duotone ph-folder-dashed text-4xl"></i></div>
                                <h3 class="text-[#2c3f61] font-black text-xl mb-1">Bank Soal Kosong</h3>
                                <p class="text-slate-500 text-sm">Isi bank soal ini untuk digunakan di ujian nanti.</p>
                            </div>
                        @endforelse
                        
                        <div x-show="questionSearch !== '' && document.querySelectorAll(`[data-search]:not([style*='display: none'])`).length === 0" class="text-center py-10" style="display: none;">
                            <p class="text-slate-400 font-bold">Tidak ada soal yang cocok dengan kata kunci tersebut.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL EDIT SOAL --}}
        <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
             <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="showEditModal = false"></div>
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="relative transform overflow-hidden rounded-[2.5rem] bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-2xl border border-slate-100">
                    <form :action="editState.url" method="POST" enctype="multipart/form-data" id="editQuestionForm">
                        @csrf @method('PUT')
                        <input type="hidden" name="delete_image" x-model="deleteImage">

                        <div class="bg-white px-8 py-6 border-b border-slate-100 flex justify-between items-center">
                            <h3 class="text-xl font-black text-[#2c3f61] flex items-center gap-2"><i class="ph-fill ph-pencil-simple text-[#0d52a1]"></i> Edit Soal</h3>
                            <button type="button" @click="showEditModal = false" class="text-slate-400 hover:text-rose-500 transition bg-slate-50 w-10 h-10 rounded-xl flex items-center justify-center"><i class="ph-bold ph-x text-lg"></i></button>
                        </div>

                        <div class="p-8 space-y-6 max-h-[70vh] overflow-y-auto custom-scrollbar bg-slate-50/30">
                            
                            {{-- GANTI TIPE SOAL (EDIT) --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Tipe Soal</label>
                                <div class="relative">
                                    <select name="question_type" x-model="editState.question_type" class="w-full rounded-xl border-slate-200 bg-white text-sm font-bold text-[#2c3f61] py-3 px-4 focus:ring-[#56bbf1] focus:border-[#56bbf1] cursor-pointer">
                                        <option value="choice">Pilihan Ganda</option>
                                        <option value="true_false">Benar / Salah</option>
                                        <option value="matching">Menjodohkan</option>
                                        <option value="essay">Essai / Isian</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-500"><i class="ph-bold ph-caret-down"></i></div>
                                </div>
                            </div>

                            {{-- EDIT TAGS / KD --}}
                            <div class="bg-[#e5eff5]/50 p-4 rounded-2xl border border-[#56bbf1]/20">
                                <label class="block text-xs font-bold text-[#0d52a1] uppercase mb-2 ml-1"><i class="ph-fill ph-tag"></i> Materi / KD (Opsional)</label>
                                <div class="flex flex-wrap gap-2 mb-2">
                                    <template x-for="(tag, index) in editTags" :key="index">
                                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-[#56bbf1]/20 text-[#0d52a1] rounded-lg text-xs font-bold border border-[#56bbf1]/30">
                                            <span x-text="tag"></span>
                                            <button type="button" @click="editTags.splice(index, 1)" class="hover:text-rose-500"><i class="ph-bold ph-x"></i></button>
                                        </span>
                                    </template>
                                </div>
                                <div class="flex gap-2">
                                    <input type="text" x-model="editNewTag" @keydown.enter.prevent="addTag(editNewTag, 'edit')" placeholder="Ketik lalu Enter..." class="w-full rounded-xl border-slate-200 text-sm py-2 px-3 focus:ring-[#56bbf1] focus:border-[#56bbf1]">
                                    <button type="button" @click="addTag(editNewTag, 'edit')" class="px-4 bg-[#2c3f61] text-white rounded-xl hover:bg-[#1c2940] font-bold text-xs">Tambah</button>
                                </div>
                                <input type="hidden" name="tags" :value="editTags.join(',')">
                            </div>

                            {{-- Editor (ID Unik untuk Edit) --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Pertanyaan</label>
                                <input id="q_input_edit" type="hidden" name="question_text" x-model="editState.question_text">
                                <trix-editor id="edit-trix-editor" input="q_input_edit" @trix-change="editState.question_text = $event.target.value" class="prose prose-sm max-w-none bg-white text-[#2c3f61] focus:ring-[#56bbf1] focus:border-[#56bbf1]"></trix-editor>
                            </div>

                            {{-- Gambar --}}
                            <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100">
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-3 ml-1">Gambar Soal</label>
                                <div class="flex flex-col gap-4">
                                    <template x-if="newImagePreview">
                                        <div class="relative w-fit">
                                            <p class="text-[10px] font-bold text-emerald-600 mb-2 flex items-center gap-1 bg-emerald-50 px-2 py-1 rounded-md w-fit"><i class="ph-bold ph-check"></i> Akan Diganti:</p>
                                            <img :src="newImagePreview" class="h-32 rounded-xl border-2 border-emerald-500 shadow-sm object-cover">
                                            <button type="button" @click="newImagePreview = null; $refs.editFileInput.value = ''" class="absolute -top-2 -right-2 bg-rose-500 text-white rounded-xl p-1.5 shadow-lg hover:bg-rose-600 transition"><i class="ph-bold ph-x text-sm"></i></button>
                                        </div>
                                    </template>
                                    <template x-if="!newImagePreview && editState.question_image && !deleteImage">
                                        <div class="relative w-fit group">
                                            <p class="text-[10px] font-bold text-slate-400 mb-2">Gambar Saat Ini:</p>
                                            <img :src="'/storage/' + editState.question_image" class="h-24 rounded-xl border border-slate-200 shadow-sm object-cover opacity-90">
                                            <button type="button" @click="removeCurrentImage()" class="absolute inset-0 bg-rose-900/80 backdrop-blur-[1px] text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition rounded-xl font-bold text-xs gap-2"><i class="ph-bold ph-trash"></i> Hapus</button>
                                        </div>
                                    </template>
                                    <div class="flex-1">
                                        <input type="file" x-ref="editFileInput" name="question_image" @change="handleEditImage" accept="image/*" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-white file:text-[#0d52a1] hover:file:bg-[#e5eff5] cursor-pointer border border-slate-200 rounded-xl bg-slate-50">
                                    </div>
                                </div>
                            </div>

                            {{-- JAWABAN DINAMIS (EDIT) --}}
                            <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                                
                                {{-- Edit: Pilihan Ganda --}}
                                <template x-if="editState.question_type === 'choice'">
                                    <div class="space-y-4">
                                        <label class="block text-xs font-bold text-slate-400 uppercase ml-1">Edit Pilihan & Gambar</label>
                                        @foreach(['A','B','C','D'] as $opt)
                                        <div class="flex gap-3 items-start">
                                            <span class="w-10 h-10 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center font-black text-sm shrink-0 mt-1">{{ $opt }}</span>
                                            <div class="flex-1 space-y-2" x-data="{ optPreviewEdit: null, deleteOptImage: false }">
                                                <div class="flex gap-2">
                                                    <input type="text" name="option_{{ $opt }}" x-model="editState.option_{{ $opt }}" class="flex-1 rounded-xl border-slate-200 text-sm py-2.5 px-4 focus:ring-[#56bbf1] focus:border-[#56bbf1] font-medium" placeholder="Teks opsi {{ $opt }}">
                                                    <label class="w-11 h-11 shrink-0 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-400 hover:text-[#0d52a1] hover:bg-[#e5eff5] cursor-pointer transition" title="Ganti Gambar Opsi {{ $opt }}">
                                                        <i class="ph-bold ph-image text-lg"></i>
                                                        <input type="file" name="image_{{ $opt }}" accept="image/*" class="hidden" @change="optPreviewEdit = URL.createObjectURL($event.target.files[0]); deleteOptImage = false">
                                                    </label>
                                                </div>
                                                
                                                <input type="hidden" name="delete_image_{{ $opt }}" x-model="deleteOptImage">

                                                <template x-if="optPreviewEdit">
                                                    <div class="relative w-24 mt-2">
                                                        <p class="text-[10px] font-bold text-emerald-600 mb-1">Baru:</p>
                                                        <img :src="optPreviewEdit" class="h-24 w-24 object-cover rounded-xl border-2 border-emerald-500 shadow-sm">
                                                        <button type="button" @click="optPreviewEdit = null; $event.target.closest('.flex-1').querySelector('input[type=file]').value = ''" class="absolute top-4 -right-2 bg-rose-500 text-white rounded-lg p-1 shadow-md hover:bg-rose-600"><i class="ph-bold ph-x text-xs"></i></button>
                                                    </div>
                                                </template>
                                                
                                                <template x-if="!optPreviewEdit && editState.image_{{ $opt }} && !deleteOptImage">
                                                    <div class="relative w-24 group/optimg mt-2">
                                                        <p class="text-[10px] font-bold text-slate-400 mb-1">Saat ini:</p>
                                                        <img :src="'/storage/' + editState.image_{{ $opt }}" class="h-24 w-24 object-cover rounded-xl border border-slate-200 shadow-sm">
                                                        <button type="button" @click="deleteOptImage = true" class="absolute inset-y-0 bottom-0 mt-5 inset-x-0 bg-rose-900/80 text-white flex items-center justify-center opacity-0 group-hover/optimg:opacity-100 transition rounded-xl font-bold text-xs gap-1"><i class="ph-bold ph-trash"></i> Hapus</button>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                        @endforeach
                                        <div class="mt-2">
                                            <label class="text-xs font-bold text-slate-500">Kunci:</label>
                                            <select name="correct_answer" x-model="editState.correct_answer" class="ml-2 rounded-lg border-slate-200 text-xs font-bold py-1 px-3 bg-white focus:ring-[#56bbf1] focus:border-[#56bbf1]">
                                                <option value="A">A</option><option value="B">B</option><option value="C">C</option><option value="D">D</option>
                                            </select>
                                        </div>
                                    </div>
                                </template>

                                {{-- Edit: Benar Salah --}}
                                <template x-if="editState.question_type === 'true_false'">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase mb-3 ml-1">Kunci Jawaban</label>
                                        <div class="flex gap-4">
                                            <label class="flex-1 cursor-pointer">
                                                <input type="radio" name="correct_answer" value="A" x-model="editState.correct_answer" class="peer sr-only">
                                                <div class="p-3 rounded-xl bg-white border border-slate-200 text-center peer-checked:bg-emerald-50 peer-checked:border-emerald-500 peer-checked:text-emerald-700 transition">Benar</div>
                                            </label>
                                            <label class="flex-1 cursor-pointer">
                                                <input type="radio" name="correct_answer" value="B" x-model="editState.correct_answer" class="peer sr-only">
                                                <div class="p-3 rounded-xl bg-white border border-slate-200 text-center peer-checked:bg-rose-50 peer-checked:border-rose-500 peer-checked:text-rose-700 transition">Salah</div>
                                            </label>
                                        </div>
                                    </div>
                                </template>

                                {{-- Edit: Menjodohkan --}}
                                <template x-if="editState.question_type === 'matching'">
                                    <div class="space-y-3">
                                        <label class="block text-xs font-bold text-slate-400 uppercase ml-1">Pasangan (Teks & Gambar)</label>
                                        <template x-for="(pair, index) in editMatchPairs" :key="index">
                                            <div class="bg-white p-3 rounded-xl border border-slate-200 shadow-sm space-y-3" x-data="{ leftPreviewEdit: null, rightPreviewEdit: null, delLeftImg: false, delRightImg: false }">
                                                
                                                <!-- BARIS 1: INPUT TEKS DAN IKON -->
                                                <div class="flex items-center gap-2">
                                                    <!-- Edit Sisi Kiri -->
                                                    <div class="flex-1 flex items-center gap-2 min-w-0">
                                                        <input type="text" :name="'matches['+index+'][left]'" x-model="pair.left" class="w-full min-w-0 rounded-lg border-slate-200 text-xs px-3 h-9 focus:ring-[#56bbf1] focus:border-[#56bbf1]">
                                                        <label class="shrink-0 w-9 h-9 bg-slate-50 border border-slate-200 rounded-lg flex items-center justify-center text-slate-400 cursor-pointer hover:bg-[#e5eff5] hover:text-[#0d52a1] transition" title="Ganti Gambar Kiri">
                                                            <i class="ph-bold ph-image text-sm"></i>
                                                            <input type="file" :name="'matches['+index+'][left_image]'" accept="image/*" class="left-file-edit hidden" @change="leftPreviewEdit = URL.createObjectURL($event.target.files[0]); delLeftImg = false">
                                                        </label>
                                                    </div>
                                                    <input type="hidden" :name="'matches['+index+'][delete_left_image]'" x-model="delLeftImg">
                                                    
                                                    <!-- Panah Tengah -->
                                                    <div class="shrink-0 w-4 flex items-center justify-center text-slate-300">
                                                        <i class="ph-bold ph-arrow-right"></i>
                                                    </div>
                                                    
                                                    <!-- Edit Sisi Kanan -->
                                                    <div class="flex-1 flex items-center gap-2 min-w-0">
                                                        <input type="text" :name="'matches['+index+'][right]'" x-model="pair.right" class="w-full min-w-0 rounded-lg border-slate-200 text-xs px-3 h-9 focus:ring-[#56bbf1] focus:border-[#56bbf1]">
                                                        <label class="shrink-0 w-9 h-9 bg-slate-50 border border-slate-200 rounded-lg flex items-center justify-center text-slate-400 cursor-pointer hover:bg-[#e5eff5] hover:text-[#0d52a1] transition" title="Ganti Gambar Kanan">
                                                            <i class="ph-bold ph-image text-sm"></i>
                                                            <input type="file" :name="'matches['+index+'][right_image]'" accept="image/*" class="right-file-edit hidden" @change="rightPreviewEdit = URL.createObjectURL($event.target.files[0]); delRightImg = false">
                                                        </label>
                                                    </div>
                                                    <input type="hidden" :name="'matches['+index+'][delete_right_image]'" x-model="delRightImg">
                                                    
                                                    <!-- Tombol Hapus -->
                                                    <button type="button" @click="removeEditPair(index)" class="shrink-0 w-8 h-8 flex items-center justify-center text-rose-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus Pasangan">
                                                        <i class="ph-bold ph-trash text-base"></i>
                                                    </button>
                                                </div>

                                                <!-- BARIS 2: PREVIEW GAMBAR -->
                                                <template x-if="leftPreviewEdit || (pair.left_image && !delLeftImg) || rightPreviewEdit || (pair.right_image && !delRightImg)">
                                                    <div class="flex items-start gap-2">
                                                        <!-- Preview Kiri -->
                                                        <div class="flex-1 min-w-0">
                                                            <template x-if="leftPreviewEdit">
                                                                <div class="relative w-fit mt-2">
                                                                    <p class="text-[9px] font-bold text-emerald-600 mb-1">Baru:</p>
                                                                    <img :src="leftPreviewEdit" class="h-16 w-16 object-cover rounded-lg border-2 border-emerald-500 shadow-sm">
                                                                    <button type="button" @click="leftPreviewEdit = null; $event.target.closest('.bg-white').querySelector('.left-file-edit').value = ''" class="absolute top-4 -right-1.5 bg-rose-500 text-white rounded p-0.5"><i class="ph-bold ph-x text-[10px]"></i></button>
                                                                </div>
                                                            </template>
                                                            <template x-if="!leftPreviewEdit && pair.left_image && !delLeftImg">
                                                                <div class="relative w-fit group/img mt-2">
                                                                    <p class="text-[9px] font-bold text-slate-400 mb-1">Saat ini:</p>
                                                                    <img :src="'/storage/' + pair.left_image" class="h-16 w-16 object-cover rounded-lg border border-slate-200">
                                                                    <button type="button" @click="delLeftImg = true" class="absolute inset-y-0 bottom-0 mt-4 inset-x-0 bg-rose-900/80 text-white flex items-center justify-center opacity-0 group-hover/img:opacity-100 transition rounded-lg text-[10px] font-bold">Hapus</button>
                                                                </div>
                                                            </template>
                                                        </div>

                                                        <!-- Spacer Panah -->
                                                        <div class="shrink-0 w-4"></div>

                                                        <!-- Preview Kanan -->
                                                        <div class="flex-1 min-w-0">
                                                            <template x-if="rightPreviewEdit">
                                                                <div class="relative w-fit mt-2">
                                                                    <p class="text-[9px] font-bold text-emerald-600 mb-1">Baru:</p>
                                                                    <img :src="rightPreviewEdit" class="h-16 w-16 object-cover rounded-lg border-2 border-emerald-500 shadow-sm">
                                                                    <button type="button" @click="rightPreviewEdit = null; $event.target.closest('.bg-white').querySelector('.right-file-edit').value = ''" class="absolute top-4 -right-1.5 bg-rose-500 text-white rounded p-0.5"><i class="ph-bold ph-x text-[10px]"></i></button>
                                                                </div>
                                                            </template>
                                                            <template x-if="!rightPreviewEdit && pair.right_image && !delRightImg">
                                                                <div class="relative w-fit group/img mt-2">
                                                                    <p class="text-[9px] font-bold text-slate-400 mb-1">Saat ini:</p>
                                                                    <img :src="'/storage/' + pair.right_image" class="h-16 w-16 object-cover rounded-lg border border-slate-200">
                                                                    <button type="button" @click="delRightImg = true" class="absolute inset-y-0 bottom-0 mt-4 inset-x-0 bg-rose-900/80 text-white flex items-center justify-center opacity-0 group-hover/img:opacity-100 transition rounded-lg text-[10px] font-bold">Hapus</button>
                                                                </div>
                                                            </template>
                                                        </div>

                                                        <!-- Spacer Hapus -->
                                                        <div class="shrink-0 w-8"></div>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>
                                        <button type="button" @click="addEditPair()" class="text-xs font-bold text-[#0d52a1] hover:text-[#2c3f61] flex items-center gap-1 mt-2"><i class="ph-bold ph-plus"></i> Tambah Pasangan</button>
                                    </div>
                                </template>

                                {{-- Edit: Essai --}}
                                <template x-if="editState.question_type === 'essay'">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Kunci (Opsional)</label>
                                        <input type="text" name="correct_answer" x-model="editState.correct_answer" class="w-full rounded-xl border-slate-200 text-sm py-2 px-3 focus:ring-[#56bbf1] focus:border-[#56bbf1]">
                                    </div>
                                </template>
                            </div>

                            {{-- Bobot Nilai (EDIT) --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Bobot Nilai</label>
                                <input type="number" name="score_weight" x-model="editState.score_weight" required class="w-full rounded-xl border-slate-200 bg-white text-sm font-bold text-center h-11 focus:ring-[#56bbf1] focus:border-[#56bbf1]">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                {{-- Tombol Simpan --}}
                                <div class="col-span-2 flex justify-end gap-3 mt-4">
                                    <button type="button" @click="showEditModal = false" class="px-6 py-3 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition text-sm">Batal</button>
                                    <button type="submit" class="px-6 py-3 bg-[#2c3f61] text-white font-bold rounded-xl hover:bg-[#1c2940] transition shadow-lg shadow-[#2c3f61]/30 text-sm flex items-center gap-2"><i class="ph-bold ph-floppy-disk text-lg"></i> Simpan</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- MODAL IMPORT SOAL --}}
        <div x-show="showImportModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showImportModal = false"></div>
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="relative transform overflow-hidden rounded-[2.5rem] bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-md border border-slate-100">
                    <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center">
                        <h3 class="text-xl font-black text-[#2c3f61] flex items-center gap-2"><i class="ph-fill ph-file-arrow-up text-[#0d52a1]"></i> Import dari Excel</h3>
                        <button @click="showImportModal = false" class="text-slate-400 hover:text-rose-500 transition"><i class="ph-bold ph-x text-lg"></i></button>
                    </div>
                    
                    <form action="{{ route('bank.questions.import', $bank->id) }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                        @csrf
                        <div class="bg-[#e5eff5]/50 p-4 rounded-2xl border border-[#56bbf1]/20 mb-4">
                            <p class="text-xs text-[#0d52a1] font-bold leading-relaxed">
                                <i class="ph-bold ph-info"></i> Pastikan format file sesuai dengan template. Kolom 'soal' dan 'kunci' wajib diisi.
                            </p>
                            <a href="{{ route('bank.questions.template') }}" class="inline-flex items-center gap-1 mt-2 text-xs font-black text-[#2c3f61] hover:text-[#0d52a1] hover:underline transition">
                                <i class="ph-bold ph-download-simple"></i> Download Template Excel
                            </a>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-400 uppercase ml-1">Pilih File (.xlsx / .xls)</label>
                            <input type="file" name="file" required class="block w-full text-xs text-slate-500 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#2c3f61] file:text-white hover:file:bg-[#1c2940] cursor-pointer border border-slate-200 rounded-xl bg-slate-50">
                        </div>

                        <div class="flex gap-3 pt-4">
                            <button type="button" @click="showImportModal = false" class="flex-1 py-3 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition text-sm">Batal</button>
                            <button type="submit" class="flex-1 py-3 bg-[#2c3f61] text-white font-bold rounded-xl hover:bg-[#1c2940] transition shadow-lg shadow-[#2c3f61]/30 text-sm">Mulai Import</button>
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
                        confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal', customClass: { popup: 'rounded-[2rem]' }
                    }).then((result) => { if (result.isConfirmed) form.submit(); });
                });
            });
            @if(session('success'))
                Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, customClass: { popup: 'rounded-xl' } });
            @endif

            {{-- TAMBAHAN: Tampilkan Pesan Error Validasi Backend --}}
            @if($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Menyimpan!',
                    html: `
                        <div class="text-sm text-rose-500 font-medium text-left mt-2">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    `,
                    customClass: { popup: 'rounded-[2rem]' }
                });
            @endif
        });
        function viewImage(url) {
            Swal.fire({ imageUrl: url, imageAlt: 'Gambar Soal', showConfirmButton: false, showCloseButton: true, customClass: { popup: 'rounded-[2rem]', image: 'rounded-2xl' }, width: 'auto' });
        }
    </script>
</x-app-layout>