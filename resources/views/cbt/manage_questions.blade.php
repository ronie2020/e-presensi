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
        
        // State untuk Input Soal Baru
        createType: 'choice',
        createQuestionText: '',
        matchPairs: [{left: '', right: ''}], 

        // State Edit
        editState: {
            id: null,
            url: '', 
            question_type: 'choice', 
            question_text: '', 
            question_image: '', 
            option_A: '', option_B: '', option_C: '', option_D: '',
            correct_answer: 'A', 
            score_weight: 2
        },
        editMatchPairs: [], 
        newImagePreview: null,
        deleteImage: false,
        exportMode: 'new',
        
        // Helper Matching (Create)
        addPair() { this.matchPairs.push({left: '', right: ''}); },
        removePair(index) { if(this.matchPairs.length > 1) this.matchPairs.splice(index, 1); },

        // Helper Matching (Edit)
        addEditPair() { this.editMatchPairs.push({left: '', right: ''}); },
        removeEditPair(index) { if(this.editMatchPairs.length > 1) this.editMatchPairs.splice(index, 1); },

        // Fungsi Buka Modal Edit
        openEdit(data, url) {
            this.newImagePreview = null;
            this.deleteImage = false;
            
            // Populate data dasar
            this.editState = { 
                ...this.editState, 
                ...data, 
                url: url,
                question_type: data.question_type || 'choice'
            };

            // Populate data khusus Matching
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
        },

        updatePreview(elementId) {
            this.$nextTick(() => {
                if(window.MathJax) MathJax.typesetPromise();
            });
        }
    }">
        
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">{{ __('Kelola Soal Ujian') }}</h2>
        </x-slot>

        <div class="py-8 sm:py-10 font-sans text-slate-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
                
                {{-- HEADER INFO --}}
                <div class="relative rounded-[2rem] bg-gray-900 bg-gradient-to-br from-slate-900 via-blue-900 to-blue-800 p-8 text-white shadow-xl shadow-blue-900/30 overflow-hidden border border-white/10">
                    {{-- ... (Header Content sama seperti sebelumnya) ... --}}
                    <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <a href="{{ route('cbt.index') }}" class="text-xs font-bold text-blue-300 hover:text-white transition flex items-center gap-1"><i class="ph-bold ph-arrow-left"></i> Dashboard</a>
                                <span class="text-white/30 text-xs">•</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wide bg-white/10 text-white border border-white/10">{{ $exam->subject_name }}</span>
                            </div>
                            <h1 class="text-3xl font-black tracking-tight leading-none text-white mb-1">{{ $exam->title }}</h1>
                        </div>
                        <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                            @if($exam->questions->count() > 0)
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
                    </div>
                </div>

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
                                            <option value="matching">Menjodohkan (Matching)</option>
                                            <option value="essay">Isian Singkat / Essai</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-500"><i class="ph-bold ph-caret-down"></i></div>
                                    </div>
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
                                    
                                    {{-- Tipe 1: Pilihan Ganda --}}
                                    <template x-if="createType === 'choice'">
                                        <div class="space-y-3">
                                            <label class="block text-xs font-bold text-slate-400 uppercase ml-1">Opsi Jawaban</label>
                                            @foreach(['A','B','C','D'] as $opt)
                                            <div class="flex gap-3 items-center group">
                                                <span class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 flex items-center justify-center font-black text-xs shrink-0">{{ $opt }}</span>
                                                <input type="text" name="option_{{ $opt }}" class="flex-1 rounded-xl border-slate-200 bg-white text-sm py-2 px-3 focus:ring-blue-500" placeholder="Jawaban {{ $opt }}">
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

                                    {{-- Tipe 2: Benar Salah --}}
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

                                    {{-- Tipe 3: Menjodohkan --}}
                                    <template x-if="createType === 'matching'">
                                        <div class="space-y-3">
                                            <label class="block text-xs font-bold text-slate-400 uppercase ml-1">Pasangan Jawaban</label>
                                            <template x-for="(pair, index) in matchPairs" :key="index">
                                                <div class="flex gap-2 items-center bg-white p-2 rounded-xl border border-slate-200 shadow-sm">
                                                    <input type="text" :name="'matches['+index+'][left]'" class="flex-1 min-w-0 rounded-lg border-slate-200 text-xs py-2 px-3" placeholder="Kiri">
                                                    <i class="ph-bold ph-arrow-right text-slate-300 shrink-0"></i>
                                                    <input type="text" :name="'matches['+index+'][right]'" class="flex-1 min-w-0 rounded-lg border-slate-200 text-xs py-2 px-3" placeholder="Kanan">
                                                    <button type="button" @click="removePair(index)" class="text-rose-400 hover:text-rose-600 shrink-0"><i class="ph-bold ph-trash"></i></button>
                                                </div>
                                            </template>
                                            <button type="button" @click="addPair()" class="text-xs font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1 mt-2"><i class="ph-bold ph-plus"></i> Tambah</button>
                                        </div>
                                    </template>

                                    {{-- Tipe 4: Essai --}}
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
                        <div class="flex flex-col sm:flex-row justify-between items-center px-2 gap-4">
                            <h3 class="font-black text-slate-800 text-lg flex items-center gap-2">
                                <i class="ph-fill ph-list-dashes text-blue-500"></i> Daftar Soal
                                <span class="text-xs font-bold text-slate-500 bg-white border border-slate-200 px-3 py-1.5 rounded-lg shadow-sm">{{ $exam->questions->count() }}</span>
                            </h3>
                            <div class="relative w-full sm:w-64">
                                <i class="ph-bold ph-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input type="text" x-model="questionSearch" placeholder="Cari isi pertanyaan..." class="w-full pl-10 pr-4 py-2 text-sm font-bold border-slate-200 rounded-xl focus:ring-blue-500 bg-white shadow-sm transition">
                            </div>
                        </div>
                        
                        @forelse($exam->questions as $index => $q)
                            @php $qType = $q->question_type ?? 'choice'; @endphp
                            <div x-show="questionSearch === '' || '{{ strtolower(addslashes(strip_tags($q->question_text))) }}'.includes(questionSearch.toLowerCase())"
                                 class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm relative group hover:border-blue-200 hover:shadow-lg transition-all duration-300">
                                
                                <div class="absolute top-6 left-6 w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center font-black text-slate-500 text-sm group-hover:bg-blue-600 group-hover:text-white transition-colors shadow-inner">{{ $index + 1 }}</div>
                                
                                <div class="pl-16">
                                    {{-- Badge Tipe Soal --}}
                                    <div class="mb-2">
                                        @if($qType == 'choice') <span class="text-[10px] font-bold bg-blue-50 text-blue-600 px-2 py-0.5 rounded border border-blue-100">PILIHAN GANDA</span>
                                        @elseif($qType == 'true_false') <span class="text-[10px] font-bold bg-purple-50 text-purple-600 px-2 py-0.5 rounded border border-purple-100">BENAR / SALAH</span>
                                        @elseif($qType == 'matching') <span class="text-[10px] font-bold bg-orange-50 text-orange-600 px-2 py-0.5 rounded border border-orange-100">MENJODOHKAN</span>
                                        @elseif($qType == 'essay') <span class="text-[10px] font-bold bg-pink-50 text-pink-600 px-2 py-0.5 rounded border border-pink-100">ESSAI</span>
                                        @endif
                                    </div>

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
                                                @php $val = isset($q->{'option_'.$opt}) ? $q->{'option_'.$opt} : ($q->options[$opt] ?? '-'); @endphp
                                                <div class="flex items-start gap-3 p-2.5 rounded-xl border transition-colors {{ $opt == $q->correct_answer ? 'bg-emerald-50 border-emerald-200' : 'bg-slate-50/50 border-transparent' }}">
                                                    <span class="w-6 h-6 flex items-center justify-center rounded-lg border {{ $opt == $q->correct_answer ? 'border-emerald-500 bg-emerald-500 text-white' : 'border-slate-300 text-slate-400 bg-white' }} text-[10px] font-black shrink-0 mt-0.5">{{ $opt }}</span>
                                                    <span class="leading-relaxed {{ $opt == $q->correct_answer ? 'text-emerald-800 font-bold' : 'text-slate-600 font-medium' }}">{{ $val }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @elseif($qType == 'matching')
                                        <div class="text-xs text-slate-500 bg-slate-50 p-3 rounded-xl border border-slate-200">
                                            <p class="font-bold mb-1">Pasangan:</p>
                                            @php 
                                                $pairs = is_string($q->options) ? json_decode($q->options, true)['pairs'] ?? [] : $q->options['pairs'] ?? [];
                                            @endphp
                                            <ul class="list-disc list-inside">
                                                @foreach($pairs as $p)
                                                    <li>{{ $p['left'] }} <i class="ph-bold ph-arrow-right text-[10px]"></i> {{ $p['right'] }}</li>
                                                @endforeach
                                            </ul>
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
                                            options: {{ json_encode($q->options) }}, 
                                            correct_answer: '{{ $q->correct_answer }}',
                                            score_weight: {{ $q->score_weight }}
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
            </div>

            {{-- === MODAL SECTION (DIPERBAIKI) === --}}

            {{-- 1. MODAL EDIT SOAL (FIX: MENGGUNAKAN X-IF) --}}
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
                                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-500"><i class="ph-bold ph-caret-down"></i></div>
                                    </div>
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

                                {{-- JAWABAN DINAMIS (EDIT - MENGGUNAKAN X-IF AGAR TIDAK BENTROK DATA) --}}
                                <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                                    
                                    {{-- Edit: Pilihan Ganda --}}
                                    <template x-if="editState.question_type === 'choice'">
                                        <div class="space-y-4">
                                            <label class="block text-xs font-bold text-slate-400 uppercase ml-1">Edit Pilihan</label>
                                            @foreach(['A','B','C','D'] as $opt)
                                            <div class="flex gap-3 items-center">
                                                <span class="w-10 h-10 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center font-black text-sm">{{ $opt }}</span>
                                                <input type="text" name="option_{{ $opt }}" x-model="editState.option_{{ $opt }}" class="flex-1 rounded-xl border-slate-200 text-sm py-2.5 px-4 focus:ring-indigo-500 font-medium">
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
                                            <label class="block text-xs font-bold text-slate-400 uppercase ml-1">Pasangan</label>
                                            <template x-for="(pair, index) in editMatchPairs" :key="index">
                                                <div class="flex gap-2 items-center">
                                                    <input type="text" :name="'matches['+index+'][left]'" x-model="pair.left" class="flex-1 min-w-0 rounded-lg border-slate-200 text-xs py-2 px-3">
                                                    <i class="ph-bold ph-arrow-right text-slate-300 shrink-0"></i>
                                                    <input type="text" :name="'matches['+index+'][right]'" x-model="pair.right" class="flex-1 min-w-0 rounded-lg border-slate-200 text-xs py-2 px-3">
                                                    <button type="button" @click="removeEditPair(index)" class="text-rose-400 hover:text-rose-600 shrink-0"><i class="ph-bold ph-trash"></i></button>
                                                </div>
                                            </template>
                                            <button type="button" @click="addEditPair()" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 flex items-center gap-1 mt-2"><i class="ph-bold ph-plus"></i> Tambah</button>
                                        </div>
                                    </template>

                                    {{-- Edit: Essai --}}
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

            {{-- 2. MODAL IMPORT EXCEL & OTHERS... (SAMA SEPERTI SEBELUMNYA) --}}
            {{-- Bagian Modal Import dan Modal Bank sama seperti file sebelumnya, jadi aman --}}
            <div x-show="showImportModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="showImportModal = false"></div>
                <div class="flex min-h-screen items-center justify-center p-4">
                    <div class="relative bg-white rounded-[2.5rem] shadow-2xl max-w-md w-full p-8 border border-slate-100">
                        <div class="text-center mb-6">
                            <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4 text-emerald-600 text-3xl"><i class="ph-fill ph-microsoft-excel-logo"></i></div>
                            <h3 class="text-xl font-black text-slate-800">Import Soal Excel</h3>
                            <p class="text-sm text-slate-500 mt-1">Upload file .xlsx sesuai template.</p>
                        </div>
                        <form action="{{ route('cbt.questions.import', $exam->id) }}" method="POST" enctype="multipart/form-data" id="importQuestionForm">
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
                        
                        <form action="{{ route('cbt.import_from_bank', $exam->id) }}" method="POST">
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
                                    @if($banks->isEmpty())
                                        <p class="text-[10px] text-rose-500 mt-2 font-bold"><i class="ph-bold ph-warning"></i> Tidak ada Bank Soal yang cocok.</p>
                                    @endif
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
                    setupLoading('createQuestionForm'); setupLoading('editQuestionForm', 'Memperbarui Soal...'); setupLoading('importQuestionForm', 'Mengimport Soal...');
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