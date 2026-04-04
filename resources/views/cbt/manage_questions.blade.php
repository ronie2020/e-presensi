<x-app-layout>
    {{-- LOAD TRIX EDITOR RESOURCES --}}
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
    
    <style>
        trix-toolbar .trix-button-group--file-tools { display: none; }
        trix-editor { min-height: 150px; background-color: #f8fafc; border-radius: 1rem; border-color: #e2e8f0; }
        .trix-content ul { list-style-type: disc; padding-left: 1.5rem; }
        .trix-content ol { list-style-type: decimal; padding-left: 1.5rem; }
    </style>

    <div x-data="{ 
        showImportModal: false, 
        showBankModal: false, 
        showExportBankModal: false,
        showEditModal: false,
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
        exportMode: 'new',
        
        // Helper Matching (Create)
        addPair() { this.matchPairs.push({left: '', right: ''}); },
        removePair(index) { if(this.matchPairs.length > 1) this.matchPairs.splice(index, 1); },

        // Helper Tags
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

        // Helper Matching (Edit)
        addEditPair() { this.editMatchPairs.push({left: '', right: ''}); },
        removeEditPair(index) { if(this.editMatchPairs.length > 1) this.editMatchPairs.splice(index, 1); },

        // Fungsi Buka Modal Edit
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
                if(trix) trix.editor.loadHTML(this.editState.question_text);
                if (window.MathJax) MathJax.typesetPromise();
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
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">{{ __('Kelola Soal Ujian') }}</h2>
        </x-slot>

        <div class="py-8 sm:py-10 font-sans text-slate-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
                
                {{-- HEADER INFO --}}
                <div class="relative rounded-[2rem] bg-gray-900 bg-gradient-to-br from-slate-900 via-blue-900 to-blue-800 p-8 text-white shadow-xl shadow-blue-900/30 overflow-hidden border border-white/10">
                    <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <a href="{{ route('cbt.index') }}" class="text-xs font-bold text-blue-300 hover:text-white transition flex items-center gap-1"><i class="ph-bold ph-arrow-left"></i> Dashboard</a>
                                <span class="text-white/30 text-xs">•</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wide bg-white/10 text-white border border-white/10">{{ $exam->subject_name }}</span>
                            </div>
                            <h1 class="text-3xl font-black tracking-tight leading-none text-white mb-1">{{ $exam->title }}</h1>
                        </div>
                        
                        {{-- Sembunyikan tombol aksi jika ini adalah Google Form --}}
                        @if(!isset($exam->exam_type) || $exam->exam_type !== 'google_form')
                        <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                            @if($exam->questions->count() > 0)
                                 {{-- TOMBOL DOWNLOAD SOAL EXCEL (BARU) --}}
                                <a href="{{ route('cbt.questions.export_excel', $exam->id) }}" class="group px-4 py-2.5 bg-emerald-100 text-emerald-700 font-bold rounded-xl hover:bg-emerald-200 transition flex items-center justify-center gap-2 border border-emerald-200 text-xs">
                                    <i class="ph-bold ph-file-xls text-lg"></i> <span>Download Excel</span>
                                </a>
                                <button @click="showExportBankModal = true" class="group px-4 py-2.5 bg-white/10 text-white font-bold rounded-xl hover:bg-white/20 transition flex items-center justify-center gap-2 border border-white/10 text-xs">
                                    <i class="ph-bold ph-upload-simple text-lg"></i> <span>Simpan ke Bank</span>
                                </button>
                            @endif
                            <button @click="showBankModal = true" class="group px-4 py-2.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-500 transition flex items-center justify-center gap-2 shadow-lg active:scale-95 border border-indigo-400 text-xs">
                                <i class="ph-bold ph-download-simple text-lg"></i> <span>Ambil Bank</span>
                            </button>
                            <button @click="showImportModal = true" class="group px-4 py-2.5 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-500 transition flex items-center justify-center gap-2 shadow-lg active:scale-95 border border-emerald-400 text-xs">
                                <i class="ph-bold ph-microsoft-excel-logo text-lg"></i> <span>Import Excel</span>
                            </button>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- PERBAIKAN: BLOKIR HALAMAN JIKA TIPE UJIAN ADALAH GOOGLE FORM --}}
                @if(isset($exam->exam_type) && $exam->exam_type == 'google_form')
                    <div class="bg-white rounded-[2.5rem] p-12 text-center border border-emerald-200 shadow-xl shadow-emerald-100/50 relative overflow-hidden">
                        <div class="absolute -top-24 -right-24 w-64 h-64 bg-emerald-50 rounded-full blur-3xl pointer-events-none"></div>
                        <div class="w-24 h-24 bg-emerald-100 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-emerald-500 shadow-inner relative z-10">
                            <i class="ph-duotone ph-google-logo text-5xl"></i>
                        </div>
                        <h3 class="text-2xl font-black text-slate-800 mb-2 relative z-10">Ini adalah Ujian Google Form</h3>
                        <p class="text-slate-500 font-medium max-w-lg mx-auto mb-8 relative z-10">Ujian ini menggunakan tautan eksternal Google Formulir. Anda tidak perlu mengelola atau menginput soal di dalam sistem sekolah ini. Pembuatan soal, penilaian, dan rekapitulasi dilakukan langsung di platform Google Workspace Anda.</p>
                        
                        <a href="{{ $exam->google_form_url }}" target="_blank" class="inline-flex items-center justify-center px-8 py-4 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition shadow-lg shadow-emerald-600/30 gap-2 relative z-10 active:scale-95">
                            <i class="ph-bold ph-arrow-square-out text-xl"></i> Buka Google Form Anda
                        </a>
                    </div>
                @else
                    {{-- JIKA BUKAN GOOGLE FORM, TAMPILKAN EDITOR CBT SEPERTI BIASA --}}
                    <div class="flex flex-col lg:flex-row gap-8 items-start">
                        
                        {{-- FORM INPUT SOAL (CREATE) --}}
                        <div class="w-full lg:w-2/5 order-2 lg:order-1">
                            <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 lg:sticky lg:top-8">
                                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-50">
                                    <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg shadow-sm border border-blue-100"><i class="ph-fill ph-plus-circle"></i></div>
                                    <div>
                                        <h3 class="font-black text-slate-800 text-lg leading-none">Buat Soal Baru</h3>
                                        <p class="text-xs text-slate-400 font-bold mt-1 uppercase tracking-wider">Editor Visual</p>
                                    </div>
                                </div>

                                <form action="{{ route('cbt.questions.store', $exam->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5" id="createQuestionForm">
                                    @csrf
                                    
                                    {{-- Pilih Tipe (Create) --}}
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Tipe Soal</label>
                                        <div class="relative">
                                            <select name="question_type" x-model="createType" class="w-full rounded-xl border-slate-200 bg-slate-50 text-sm font-bold text-slate-700 py-3 px-4 focus:ring-blue-500 cursor-pointer">
                                                <option value="choice">Pilihan Ganda</option>
                                                <option value="true_false">Benar / Salah</option>
                                                <option value="matching">Menjodohkan</option>
                                                <option value="essay">Isian Singkat / Essai</option>
                                            </select>
                                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-500"><i class="ph-bold ph-caret-down"></i></div>
                                        </div>
                                    </div>

                                    {{-- NEW: INPUT TAGS / KD --}}
                                    <div class="bg-indigo-50/50 p-4 rounded-2xl border border-indigo-100">
                                        <label class="block text-xs font-bold text-indigo-400 uppercase mb-2 ml-1"><i class="ph-fill ph-tag"></i> Materi / KD (Opsional)</label>
                                        <div class="flex flex-wrap gap-2 mb-2">
                                            <template x-for="(tag, index) in createTags" :key="index">
                                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-indigo-100 text-indigo-700 rounded-lg text-xs font-bold border border-indigo-200">
                                                    <span x-text="tag"></span>
                                                    <button type="button" @click="createTags.splice(index, 1)" class="hover:text-rose-500"><i class="ph-bold ph-x"></i></button>
                                                </span>
                                            </template>
                                        </div>
                                        <div class="flex gap-2">
                                            <input type="text" x-model="newTag" @keydown.enter.prevent="addTag(newTag, 'create')" placeholder="Ketik lalu Enter..." class="w-full rounded-xl border-slate-200 text-sm py-2 px-3 focus:ring-indigo-500 focus:border-indigo-500">
                                            <button type="button" @click="addTag(newTag, 'create')" class="px-4 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 font-bold text-xs">Tambah</button>
                                        </div>
                                        <input type="hidden" name="tags" :value="createTags.join(',')">
                                    </div>

                                    {{-- Editor --}}
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Pertanyaan / Instruksi</label>
                                        <input id="q_input_create" type="hidden" name="question_text">
                                        <trix-editor input="q_input_create" @trix-change="createQuestionText = $event.target.value" placeholder="Tulis soal di sini..." class="prose prose-sm max-w-none"></trix-editor>
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
                                        <label class="flex flex-col items-center justify-center w-full h-16 border-2 border-dashed border-slate-200 rounded-2xl cursor-pointer hover:bg-blue-50 hover:border-blue-300 transition-all bg-slate-50">
                                            <div class="flex items-center gap-2"><i class="ph-duotone ph-image text-xl text-slate-400"></i><span class="text-xs font-bold text-slate-500">Upload Gambar</span></div>
                                            <input type="file" x-ref="fileInput" name="question_image" @change="createPreviewImage = URL.createObjectURL($event.target.files[0])" accept="image/*" class="hidden">
                                        </label>
                                    </div>

                                    {{-- Input Jawaban Dinamis (Create) --}}
                                    <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                                        
                                        <template x-if="createType === 'choice'">
                                            <div class="space-y-4">
                                                <label class="block text-xs font-bold text-slate-400 uppercase ml-1">Opsi Jawaban & Gambar</label>
                                                @foreach(['A','B','C','D'] as $opt)
                                                <div class="flex gap-3 items-start group">
                                                    <span class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 flex items-center justify-center font-black text-xs shrink-0 mt-1">{{ $opt }}</span>
                                                    <div class="flex-1 space-y-2 min-w-0" x-data="{ optPreview: null }">
                                                        <div class="flex gap-2 min-w-0">
                                                            <input type="text" name="option_{{ $opt }}" class="flex-1 min-w-0 rounded-xl border-slate-200 bg-white text-sm py-2 px-3 focus:ring-blue-500" placeholder="Teks Jawaban {{ $opt }}">
                                                            <label class="w-10 h-10 shrink-0 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-400 hover:text-blue-500 hover:bg-blue-50 cursor-pointer transition" title="Upload Gambar Opsi {{ $opt }}">
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
                                                    <select name="correct_answer" class="ml-2 rounded-lg border-slate-200 text-xs font-bold py-1 px-3 bg-white">
                                                        <option value="A">A</option><option value="B">B</option><option value="C">C</option><option value="D">D</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </template>

                                        <template x-if="createType === 'true_false'">
                                            <div>
                                                <label class="block text-xs font-bold text-slate-400 uppercase mb-3 ml-1">Kunci Jawaban</label>
                                                <div class="flex gap-4">
                                                    <label class="flex-1 cursor-pointer">
                                                        <input type="radio" name="correct_answer" value="A" class="peer sr-only">
                                                        <div class="p-3 rounded-xl bg-white border border-slate-200 text-center peer-checked:bg-emerald-50 peer-checked:border-emerald-500 peer-checked:text-emerald-700 transition"><span class="font-bold text-sm">BENAR</span></div>
                                                    </label>
                                                    <label class="flex-1 cursor-pointer">
                                                        <input type="radio" name="correct_answer" value="B" class="peer sr-only">
                                                        <div class="p-3 rounded-xl bg-white border border-slate-200 text-center peer-checked:bg-rose-50 peer-checked:border-rose-500 peer-checked:text-rose-700 transition"><span class="font-bold text-sm">SALAH</span></div>
                                                    </label>
                                                </div>
                                            </div>
                                        </template>

                                        <template x-if="createType === 'matching'">
                                            <div class="space-y-3">
                                                <label class="block text-xs font-bold text-slate-400 uppercase ml-1">Pasangan (Teks & Gambar)</label>
                                                <template x-for="(pair, index) in matchPairs" :key="index">
                                                    <div class="bg-white p-3 rounded-xl border border-slate-200 shadow-sm space-y-3" x-data="{ leftPreview: null, rightPreview: null }">
                                                        
                                                        <div class="flex items-center gap-2">
                                                            <!-- Sisi Kiri -->
                                                            <div class="flex-1 flex items-center gap-2 min-w-0">
                                                                <input type="text" :name="'matches['+index+'][left]'" class="w-full min-w-0 rounded-lg border-slate-200 text-xs px-3 h-9 focus:ring-blue-500" placeholder="Teks Kiri">
                                                                <label class="shrink-0 w-9 h-9 bg-slate-50 border border-slate-200 rounded-lg flex items-center justify-center text-slate-400 hover:text-blue-500 hover:bg-blue-50 cursor-pointer transition" title="Gambar Kiri">
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
                                                                <input type="text" :name="'matches['+index+'][right]'" class="w-full min-w-0 rounded-lg border-slate-200 text-xs px-3 h-9 focus:ring-blue-500" placeholder="Teks Kanan">
                                                                <label class="shrink-0 w-9 h-9 bg-slate-50 border border-slate-200 rounded-lg flex items-center justify-center text-slate-400 hover:text-blue-500 hover:bg-blue-50 cursor-pointer transition" title="Gambar Kanan">
                                                                    <i class="ph-bold ph-image text-sm"></i>
                                                                    <input type="file" :name="'matches['+index+'][right_image]'" accept="image/*" class="right-file-input hidden" @change="rightPreview = URL.createObjectURL($event.target.files[0])">
                                                                </label>
                                                            </div>
                                                            
                                                            <!-- Tombol Hapus -->
                                                            <button type="button" @click="removePair(index)" class="shrink-0 w-8 h-8 flex items-center justify-center text-rose-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus Pasangan">
                                                                <i class="ph-bold ph-trash text-base"></i>
                                                            </button>
                                                        </div>

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

                                                                <div class="shrink-0 w-8"></div>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </template>
                                                <button type="button" @click="addPair()" class="text-xs font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1 mt-2"><i class="ph-bold ph-plus"></i> Tambah Pasangan</button>
                                            </div>
                                        </template>

                                        <template x-if="createType === 'essay'">
                                            <div>
                                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Kunci (Opsional)</label>
                                                <input type="text" name="correct_answer" class="w-full rounded-xl border-slate-200 text-sm py-2 px-3" placeholder="Jawaban singkat (Auto-grade)">
                                            </div>
                                        </template>
                                    </div>

                                    {{-- Bobot --}}
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Bobot Nilai</label>
                                        <input type="number" name="score_weight" value="2" required class="w-20 rounded-xl border-slate-200 bg-slate-50 text-sm font-bold text-center h-10 focus:ring-blue-500">
                                    </div>

                                    <button type="submit" class="w-full py-3.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-500/20 flex items-center justify-center gap-2 transform active:scale-95">
                                        <i class="ph-bold ph-floppy-disk text-lg"></i> Simpan Soal
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- LIST SOAL (KANAN) --}}
                        <div class="w-full lg:w-3/5 order-1 lg:order-2 space-y-6">
                            
                            {{-- FORM BULK ACTIONS (HIDDEN) --}}
                            <form id="bulkDeleteForm" action="{{ route('cbt.questions.bulk_delete', $exam->id) }}" method="POST" class="hidden">
                                @csrf @method('DELETE')
                                <input type="hidden" name="question_ids" :value="selectedQuestions.join(',')">
                            </form>
                            <form id="bulkWeightForm" action="{{ route('cbt.questions.bulk_weight', $exam->id) }}" method="POST" class="hidden">
                                @csrf @method('PUT')
                                <input type="hidden" name="question_ids" :value="selectedQuestions.join(',')">
                                <input type="hidden" name="score_weight" id="bulkScoreWeightInput">
                            </form>

                            <div class="flex flex-col sm:flex-row justify-between items-center px-2 gap-4">
                                <div class="flex items-center gap-3">
                                    <h3 class="font-black text-slate-800 text-lg flex items-center gap-2">
                                        <i class="ph-fill ph-list-dashes text-blue-500"></i> Daftar Soal

                                      {{-- JUMLAH SOAL --}}
                                        <span class="text-xs font-bold text-blue-600 bg-blue-50 border border-blue-100 px-3 py-1.5 rounded-lg shadow-sm flex items-center gap-1.5" title="Total Nilai">
                                           <i class=" ph-fill ph-book-open-text text-base"></i>  Soal {{ $exam->questions->count() }}  
                                        </span>

                                        {{-- PERBAIKAN: TOTAL POIN SEKARANG AKAN TERLIHAT --}}
                                        <span class="text-xs font-bold text-emerald-600 bg-emerald-50 border border-emerald-100 px-3 py-1.5 rounded-lg shadow-sm flex items-center gap-1.5" title="Akumulasi Bobot Nilai">
                                            <i class="ph-fill ph-chart-bar text-base"></i> Poin {{ $totalPoints ?? 0 }}
                                        </span>    
                                    </h3>                                    
                                   
                                </div>                                
                            </div>
                            <div class="flex flex-col sm:flex-row justify-between items-center px-2 gap-4">
                                 {{-- CHECKBOX PILIH SEMUA --}}
                                @if($exam->questions->count() > 0)
                                    <label class="flex items-center gap-2 cursor-pointer bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-100 transition">
                                        <input type="checkbox" @change="toggleSelectAll($event)" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                        <span class="text-xs font-bold text-slate-600">Pilih Semua</span>
                                    </label>
                                @endif
                                {{-- TOMBOL CETAK LAPORAN --}}
                                @if($exam->questions->count() > 0)
                                    <a href="{{ route('cbt.questions.print', $exam->id) }}" target="_blank" class="ml-2 px-3 py-1.5 bg-white text-slate-600 border border-slate-200 rounded-lg text-xs font-bold hover:text-blue-600 hover:bg-blue-50 hover:border-blue-200 transition shadow-sm flex items-center gap-1.5">
                                        <i class="ph-bold ph-printer text-base"></i> Cetak PDF
                                    </a>
                                @endif
                                {{-- Pencarian --}}
                                <div class="relative w-full sm:w-64">
                                    <i class="ph-bold ph-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input type="text" x-model="questionSearch" placeholder="Cari isi pertanyaan atau tag..." class="w-full pl-10 pr-4 py-2 text-sm font-bold border-slate-200 rounded-xl focus:ring-blue-500 bg-white shadow-sm transition">
                                </div>
                            </div>
                                
                            
                            {{-- ACTION BAR MUNCUL SAAT ADA YANG DIPILIH --}}
                            <div x-show="selectedQuestions.length > 0" x-transition class="bg-blue-50 border border-blue-200 rounded-[1.5rem] p-4 flex flex-col sm:flex-row justify-between items-center gap-4 shadow-sm" style="display: none;">
                                <div class="text-sm font-bold text-blue-800 flex items-center gap-2">
                                    <i class="ph-fill ph-check-circle text-blue-600 text-lg"></i>
                                    <span x-text="selectedQuestions.length"></span> Soal Terpilih
                                </div>
                                <div class="flex gap-2 w-full sm:w-auto">
                                    <button type="button" @click="promptBulkWeight()" class="flex-1 sm:flex-none px-4 py-2 bg-white text-blue-600 border border-blue-200 rounded-xl text-xs font-bold hover:bg-blue-600 hover:text-white transition shadow-sm">Ubah Bobot</button>
                                    <button type="button" @click="confirmBulkDelete()" class="flex-1 sm:flex-none px-4 py-2 bg-white text-rose-600 border border-rose-200 rounded-xl text-xs font-bold hover:bg-rose-600 hover:text-white transition shadow-sm">Hapus</button>
                                </div>
                            </div>

                            @forelse($exam->questions as $index => $q)
                                @php $qType = $q->question_type ?? 'choice'; @endphp
                                <div x-show="questionSearch === '' || '{{ strtolower(addslashes(strip_tags($q->question_text . ' ' . ($q->tags ?? '')))) }}'.includes(questionSearch.toLowerCase())"
                                     class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm relative group hover:border-blue-200 hover:shadow-lg transition-all duration-300">
                                    
                                    <div class="absolute top-6 left-6 w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center font-black text-slate-500 text-sm group-hover:bg-blue-600 group-hover:text-white transition-colors shadow-inner">{{ $index + 1 }}</div>
                                    
                                    {{-- CHECKBOX ITEM --}}
                                    <div class="absolute top-8 left-[4.5rem] z-10">
                                        <input type="checkbox" value="{{ $q->id }}" x-model="selectedQuestions" class="question-checkbox w-5 h-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer shadow-sm">
                                    </div>

                                    <div class="pl-20 sm:pl-24">
                                        {{-- Badge Tipe Soal --}}
                                        <div class="mb-2">
                                            @if($qType == 'choice') <span class="text-[10px] font-bold bg-blue-50 text-blue-600 px-2 py-0.5 rounded border border-blue-100">PILIHAN GANDA</span>
                                            @elseif($qType == 'true_false') <span class="text-[10px] font-bold bg-purple-50 text-purple-600 px-2 py-0.5 rounded border border-purple-100">BENAR / SALAH</span>
                                            @elseif($qType == 'matching') <span class="text-[10px] font-bold bg-orange-50 text-orange-600 px-2 py-0.5 rounded border border-orange-100">MENJODOHKAN</span>
                                            @elseif($qType == 'essay') <span class="text-[10px] font-bold bg-pink-50 text-pink-600 px-2 py-0.5 rounded border border-pink-100">ESSAI</span>
                                            @endif
                                        </div>
                                        
                                        {{-- Tampilkan TAGS --}}
                                        @if(!empty($q->tags))
                                            <div class="mb-3 flex flex-wrap gap-1">
                                                @foreach(explode(',', $q->tags) as $tag)
                                                    <span class="text-[10px] font-bold bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded border border-indigo-100 flex items-center gap-1">
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
                                        <div class="text-slate-800 font-medium text-lg mb-5 leading-relaxed trix-content prose prose-sm max-w-none">{!! $q->question_text !!}</div>
                                        
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
                                                                    <span class="font-medium text-slate-700 mt-1 sm:mt-0">{{ $p['left'] }}</span>
                                                                @endif
                                                            </div>
                                                            <i class="ph-bold ph-arrows-left-right text-slate-300"></i>
                                                            <div class="flex-1 flex flex-col sm:flex-row items-center sm:items-start gap-2 text-center sm:text-left">
                                                                @if(isset($p['right_image']) && $p['right_image'])
                                                                    <img src="{{ asset('storage/' . $p['right_image']) }}" class="w-10 h-10 rounded border border-slate-200 object-cover cursor-zoom-in" onclick="viewImage('{{ asset('storage/' . $p['right_image']) }}')">
                                                                @endif
                                                                @if(isset($p['right']) && $p['right'] !== '')
                                                                    <span class="font-medium text-slate-700 mt-1 sm:mt-0">{{ $p['right'] }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @elseif($qType == 'essay')
                                            <div class="text-xs text-slate-500 bg-slate-50 p-3 rounded-xl border border-slate-200">
                                                <span class="font-bold">Kunci:</span> {{ $q->correct_answer ?: '(Koreksi Manual)' }}
                                            </div>
                                        @elseif($qType == 'true_false')
                                            <div class="flex gap-2">
                                                <span class="px-3 py-1 rounded-lg border text-xs font-bold {{ $q->correct_answer == 'A' ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 'bg-slate-50 text-slate-400' }}">BENAR</span>
                                                <span class="px-3 py-1 rounded-lg border text-xs font-bold {{ $q->correct_answer == 'B' ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 'bg-slate-50 text-slate-400' }}">SALAH</span>
                                            </div>
                                        @endif

                                        <div class="mt-5 pt-3 border-t border-slate-50 flex items-center gap-3">
                                            <span class="text-[10px] font-bold text-slate-400 uppercase bg-slate-50 px-2 py-1 rounded-md border border-slate-100">Bobot: {{ $q->score_weight }} Poin</span>
                                        </div>
                                    </div>

                                    {{-- ACTION BUTTONS --}}
                                    <div class="absolute top-6 right-6 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
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
                                            }, '{{ route('cbt.questions.update', $q->id) }}')"
                                            class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-indigo-600 hover:border-indigo-200 shadow-sm flex items-center justify-center transition-all hover:scale-105">
                                            <i class="ph-bold ph-pencil-simple text-lg"></i>
                                        </button>

                                        <form action="{{ route('cbt.questions.destroy', $q->id) }}" method="POST" class="delete-form">
                                            @csrf @method('DELETE')
                                            <button type="button" class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-rose-600 hover:border-rose-200 shadow-sm flex items-center justify-center transition-all hover:scale-105 btn-delete"><i class="ph-bold ph-trash text-lg"></i></button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-16 bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200">
                                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300 animate-pulse"><i class="ph-duotone ph-clipboard-text text-4xl"></i></div>
                                    <h3 class="text-slate-800 font-black text-xl mb-1">Bank Soal Kosong</h3>
                                    <p class="text-slate-500 text-sm max-w-xs mx-auto font-medium">Mulai tambahkan soal secara manual atau import dari file Excel.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endif
            </div>

            {{-- === MODAL SECTION (Tetap ada, disembunyikan secara bawaan oleh x-show) === --}}

            {{-- 1. MODAL EDIT SOAL --}}
            <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
                 <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="showEditModal = false"></div>
                <div class="flex min-h-screen items-center justify-center p-4">
                    <div class="relative transform overflow-hidden rounded-[2.5rem] bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-2xl border border-slate-100">
                        <form :action="editState.url" method="POST" enctype="multipart/form-data" id="editQuestionForm">
                            @csrf @method('PUT')
                            <input type="hidden" name="delete_image" x-model="deleteImage">

                            <div class="bg-white px-8 py-6 border-b border-slate-100 flex justify-between items-center">
                                <h3 class="text-xl font-black text-slate-800 flex items-center gap-2"><i class="ph-fill ph-pencil-simple text-indigo-600"></i> Edit Soal</h3>
                                <button type="button" @click="showEditModal = false" class="text-slate-400 hover:text-rose-500 transition bg-slate-50 w-10 h-10 rounded-xl flex items-center justify-center"><i class="ph-bold ph-x text-lg"></i></button>
                            </div>

                            <div class="p-8 space-y-6 max-h-[70vh] overflow-y-auto custom-scrollbar bg-slate-50/30">
                                
                                {{-- GANTI TIPE SOAL (EDIT) --}}
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Tipe Soal</label>
                                    <div class="relative">
                                        <select name="question_type" x-model="editState.question_type" class="w-full rounded-xl border-slate-200 bg-white text-sm font-bold text-slate-700 py-3 px-4 focus:ring-blue-500 cursor-pointer">
                                            <option value="choice">Pilihan Ganda</option>
                                            <option value="true_false">Benar / Salah</option>
                                            <option value="matching">Menjodohkan</option>
                                            <option value="essay">Essai / Isian</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- EDIT TAGS / KD --}}
                                <div class="bg-indigo-50/50 p-4 rounded-2xl border border-indigo-100">
                                    <label class="block text-xs font-bold text-indigo-400 uppercase mb-2 ml-1"><i class="ph-fill ph-tag"></i> Materi / KD (Opsional)</label>
                                    <div class="flex flex-wrap gap-2 mb-2">
                                        <template x-for="(tag, index) in editTags" :key="index">
                                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-indigo-100 text-indigo-700 rounded-lg text-xs font-bold border border-indigo-200">
                                                <span x-text="tag"></span>
                                                <button type="button" @click="editTags.splice(index, 1)" class="hover:text-rose-500"><i class="ph-bold ph-x"></i></button>
                                            </span>
                                        </template>
                                    </div>
                                    <div class="flex gap-2">
                                        <input type="text" x-model="editNewTag" @keydown.enter.prevent="addTag(editNewTag, 'edit')" placeholder="Ketik lalu Enter..." class="w-full rounded-xl border-slate-200 text-sm py-2 px-3 focus:ring-indigo-500 focus:border-indigo-500">
                                        <button type="button" @click="addTag(editNewTag, 'edit')" class="px-4 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 font-bold text-xs">Tambah</button>
                                    </div>
                                    <input type="hidden" name="tags" :value="editTags.join(',')">
                                </div>

                                {{-- Editor --}}
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Pertanyaan</label>
                                    <input id="q_input_edit" type="hidden" name="question_text" x-model="editState.question_text">
                                    <trix-editor id="edit-trix-editor" input="q_input_edit" @trix-change="editState.question_text = $event.target.value" class="prose prose-sm max-w-none bg-white"></trix-editor>
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
                                            <input type="file" x-ref="editFileInput" name="question_image" @change="handleEditImage" accept="image/*" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-white file:text-blue-600 hover:file:bg-blue-50 cursor-pointer border border-slate-200 rounded-xl bg-slate-50">
                                        </div>
                                    </div>
                                </div>

                                {{-- JAWABAN DINAMIS (EDIT) --}}
                                <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                                    <template x-if="editState.question_type === 'choice'">
                                        <div class="space-y-4">
                                            <label class="block text-xs font-bold text-slate-400 uppercase ml-1">Edit Pilihan & Gambar</label>
                                            @foreach(['A','B','C','D'] as $opt)
                                            <div class="flex gap-3 items-start">
                                                <span class="w-10 h-10 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center font-black text-sm shrink-0 mt-1">{{ $opt }}</span>
                                                <div class="flex-1 space-y-2 min-w-0" x-data="{ optPreviewEdit: null, deleteOptImage: false }">
                                                    <div class="flex gap-2 min-w-0">
                                                        <input type="text" name="option_{{ $opt }}" x-model="editState.option_{{ $opt }}" class="flex-1 min-w-0 rounded-xl border-slate-200 text-sm py-2.5 px-4 focus:ring-blue-500 font-medium" placeholder="Teks opsi {{ $opt }}">
                                                        <label class="w-11 h-11 shrink-0 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-400 hover:text-blue-500 hover:bg-blue-50 cursor-pointer transition" title="Ganti Gambar Opsi {{ $opt }}">
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
                                                <select name="correct_answer" x-model="editState.correct_answer" class="ml-2 rounded-lg border-slate-200 text-xs font-bold py-1 px-3 bg-white">
                                                    <option value="A">A</option><option value="B">B</option><option value="C">C</option><option value="D">D</option>
                                                </select>
                                            </div>
                                        </div>
                                    </template>

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

                                    <template x-if="editState.question_type === 'matching'">
                                        <div class="space-y-3">
                                            <label class="block text-xs font-bold text-slate-400 uppercase ml-1">Pasangan (Teks & Gambar)</label>
                                            <template x-for="(pair, index) in editMatchPairs" :key="index">
                                                <div class="bg-white p-3 rounded-xl border border-slate-200 shadow-sm space-y-3" x-data="{ leftPreviewEdit: null, rightPreviewEdit: null, delLeftImg: false, delRightImg: false }">
                                                    
                                                    <div class="flex items-center gap-2">
                                                        <!-- Edit Sisi Kiri -->
                                                        <div class="flex-1 flex items-center gap-2 min-w-0">
                                                            <input type="text" :name="'matches['+index+'][left]'" x-model="pair.left" class="w-full min-w-0 rounded-lg border-slate-200 text-xs px-3 h-9 focus:ring-blue-500">
                                                            <label class="shrink-0 w-9 h-9 bg-slate-50 border border-slate-200 rounded-lg flex items-center justify-center text-slate-400 cursor-pointer hover:bg-blue-50 hover:text-blue-500 transition" title="Ganti Gambar Kiri">
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
                                                            <input type="text" :name="'matches['+index+'][right]'" x-model="pair.right" class="w-full min-w-0 rounded-lg border-slate-200 text-xs px-3 h-9 focus:ring-blue-500">
                                                            <label class="shrink-0 w-9 h-9 bg-slate-50 border border-slate-200 rounded-lg flex items-center justify-center text-slate-400 cursor-pointer hover:bg-blue-50 hover:text-blue-500 transition" title="Ganti Gambar Kanan">
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

                                                            <div class="shrink-0 w-8"></div>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>
                                            <button type="button" @click="addEditPair()" class="text-xs font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1 mt-2"><i class="ph-bold ph-plus"></i> Tambah Pasangan</button>
                                        </div>
                                    </template>

                                    <template x-if="editState.question_type === 'essay'">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Kunci (Opsional)</label>
                                            <input type="text" name="correct_answer" x-model="editState.correct_answer" class="w-full rounded-xl border-slate-200 text-sm py-2 px-3">
                                        </div>
                                    </template>
                                </div>

                                {{-- Bobot --}}
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Bobot</label>
                                    <input type="number" name="score_weight" x-model="editState.score_weight" required class="w-full rounded-xl border-slate-200 text-sm font-bold text-center h-11">
                                </div>
                            </div>

                            <div class="bg-white px-8 py-5 flex justify-end gap-3 rounded-b-[2.5rem] border-t border-slate-100 shadow-[0_-10px_40px_rgba(0,0,0,0.05)] relative z-10">
                                <button type="button" @click="showEditModal = false" class="px-6 py-3 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition text-sm">Batal</button>
                                <button type="submit" class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-500/30 text-sm flex items-center gap-2"><i class="ph-bold ph-floppy-disk text-lg"></i> Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- 2. MODAL IMPORT EXCEL --}}
            <div x-show="showImportModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="showImportModal = false"></div>
                <div class="flex min-h-screen items-center justify-center p-4">
                    <div class="relative bg-white rounded-[2.5rem] shadow-2xl max-w-md w-full p-8 border border-slate-100">
                        <div class="text-center mb-6">
                            <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4 text-emerald-600 text-3xl"><i class="ph-fill ph-microsoft-excel-logo"></i></div>
                            <h3 class="text-xl font-black text-slate-800">Import Soal Excel</h3>
                            <p class="text-sm text-slate-500 mt-1">Upload file .xlsx sesuai template.</p>
                        </div>
                        <form action="{{ route('cbt.questions.import', $exam->id) }}" method="POST" enctype="multipart/form-data" id="importQuestionForm" onsubmit="event.preventDefault(); confirmSubmit(this, 'Import File Excel?', 'Soal dari excel akan ditambahkan ke ujian ini.', '#059669', 'Memproses Data...');">
                            @csrf
                            <input type="file" name="file" required class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer border border-slate-200 rounded-xl bg-slate-50 mb-4">
                            <button type="submit" class="w-full py-3 rounded-xl bg-emerald-600 text-white font-bold hover:bg-emerald-700 shadow-lg">Upload & Proses</button>
                        </form>
                        <div class="mt-4 text-center">
                            <a href="{{ route('cbt.questions.template') }}" class="text-xs font-bold text-blue-600 hover:underline">Download Template Excel</a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. MODAL AMBIL DARI BANK SOAL --}}
            <div x-show="showBankModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="showBankModal = false"></div>
                <div class="flex min-h-screen items-center justify-center p-4">
                    <div class="relative bg-white rounded-[2.5rem] shadow-2xl max-w-lg w-full p-8 border border-slate-100">
                        <h3 class="text-xl font-black text-slate-800 mb-6 flex items-center gap-2"><i class="ph-fill ph-download-simple text-indigo-600"></i> Ambil dari Bank Soal</h3>
                        
                        <form action="{{ route('cbt.import_from_bank', $exam->id) }}" method="POST" onsubmit="event.preventDefault(); confirmSubmit(this, 'Salin dari Bank Soal?', 'Semua soal dari bank terpilih akan diduplikasi ke ujian ini.', '#4f46e5', 'Menyalin Soal...');">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Pilih Bank Soal</label>
                                    @php
                                        $banks = \App\Models\CbtQuestionBank::where('class_level', $exam->class_level)->orWhere('subject_name', 'like', '%'.$exam->subject_name.'%')->get();
                                    @endphp
                                    <select name="bank_id" required class="w-full rounded-xl border-slate-200 font-bold text-slate-700 py-3 px-4">
                                        <option value="" disabled selected>-- Pilih Bank Soal --</option>
                                        @foreach($banks as $b)
                                            <option value="{{ $b->id }}">{{ $b->title }} ({{ $b->questions_count }} Soal)</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="w-full py-3 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700 shadow-lg">Salin Semua Soal</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- 4. MODAL SIMPAN KE BANK SOAL --}}
            <div x-show="showExportBankModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="showExportBankModal = false"></div>
                <div class="flex min-h-screen items-center justify-center p-4">
                    <div class="relative bg-white rounded-[2.5rem] shadow-2xl max-w-lg w-full p-8 border border-slate-100" x-data="{ mode: 'new' }">
                        <h3 class="text-xl font-black text-slate-800 mb-6 flex items-center gap-2"><i class="ph-fill ph-upload-simple text-blue-600"></i> Simpan ke Bank Soal</h3>
                        
                        <form action="{{ route('cbt.export_to_bank', $exam->id) }}" method="POST">
                            @csrf
                            <div class="space-y-4">
                                <div class="flex gap-4 p-1 bg-slate-100 rounded-xl">
                                    <button type="button" @click="mode = 'new'" class="flex-1 py-2 rounded-lg text-xs font-bold transition" :class="mode === 'new' ? 'bg-white shadow text-blue-600' : 'text-slate-500 hover:text-slate-700'">Buat Bank Baru</button>
                                    <button type="button" @click="mode = 'existing'" class="flex-1 py-2 rounded-lg text-xs font-bold transition" :class="mode === 'existing' ? 'bg-white shadow text-blue-600' : 'text-slate-500 hover:text-slate-700'">Gabung ke Lama</button>
                                </div>
                                <input type="hidden" name="mode" x-model="mode">

                                <div x-show="mode === 'new'">
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Judul Bank Baru</label>
                                    <input type="text" name="new_title" class="w-full rounded-xl border-slate-200 font-bold text-slate-700 py-3 px-4" placeholder="Contoh: Bank Soal UTS 2024">
                                </div>

                                <div x-show="mode === 'existing'">
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Pilih Bank Tujuan</label>
                                    <select name="bank_id" class="w-full rounded-xl border-slate-200 font-bold text-slate-700 py-3 px-4">
                                        @foreach($banks as $b)
                                            <option value="{{ $b->id }}">{{ $b->title }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <button type="submit" class="w-full py-3 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-700 shadow-lg">Proses Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- SCRIPT SWEETALERT2 --}}
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                function confirmSubmit(form, title, text, color, loadingText) {
                    Swal.fire({
                        title: title, text: text, icon: 'question',
                        showCancelButton: true, confirmButtonColor: color, cancelButtonColor: '#64748b',
                        confirmButtonText: 'Ya, Lanjutkan!', cancelButtonText: 'Batal', customClass: { popup: 'rounded-[2rem]' }
                    }).then((result) => { 
                        if (result.isConfirmed) {
                            Swal.fire({ title: loadingText, allowOutsideClick: false, didOpen: () => Swal.showLoading(), customClass: { popup: 'rounded-[2rem]' } });
                            form.submit();
                        }
                    });
                }

                document.addEventListener('DOMContentLoaded', function() {
                    const deleteButtons = document.querySelectorAll('.btn-delete');
                    deleteButtons.forEach(button => {
                        button.addEventListener('click', function(e) {
                            e.preventDefault();
                            const form = this.closest('.delete-form');
                            Swal.fire({
                                title: 'Hapus Soal Ini?', text: "Soal yang dihapus tidak dapat dikembalikan!", icon: 'warning',
                                showCancelButton: true, confirmButtonColor: '#e11d48', cancelButtonColor: '#64748b',
                                confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal', customClass: { popup: 'rounded-[2rem]' }
                            }).then((result) => { if (result.isConfirmed) form.submit(); });
                        });
                    });
                    
                    const setupLoading = (formId, text = 'Menyimpan Soal...') => {
                        const form = document.getElementById(formId);
                        if(form) {
                            form.addEventListener('submit', function() {
                                Swal.fire({ title: text, allowOutsideClick: false, didOpen: () => Swal.showLoading(), customClass: { popup: 'rounded-[2rem]' } });
                            });
                        }
                    };
                    setupLoading('createQuestionForm'); setupLoading('editQuestionForm', 'Memperbarui Soal...');
                    
                    @if(session('success'))
                        Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, customClass: { popup: 'rounded-xl' } });
                    @endif
                    @if(session('error'))
                        Swal.fire({ icon: 'error', title: 'Gagal!', text: "{{ session('error') }}", toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, customClass: { popup: 'rounded-xl' } });
                    @endif
                });
                function viewImage(url) {
                    Swal.fire({ imageUrl: url, imageAlt: 'Gambar Soal', showConfirmButton: false, showCloseButton: true, customClass: { popup: 'rounded-[2rem]', image: 'rounded-2xl' }, width: 'auto' });
                }
            </script>
        </div>
    </div>
</x-app-layout>