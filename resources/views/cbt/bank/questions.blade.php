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

    {{-- CONFIG MATHJAX --}}
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
        questionSearch: '', 
        editState: {
            url: '', question_text: '', question_image: '', 
            option_A: '', option_B: '', option_C: '', option_D: '',
            correct_answer: 'A', score_weight: 2
        },
        newImagePreview: null,
        deleteImage: false,
        createQuestionText: '',
        
        init() {
            this.$watch('questionSearch', () => {
                this.$nextTick(() => { if (window.renderMath) window.renderMath(); });
            });
        },

        openEdit(data, url) {
            this.editState = { ...data, url: url };
            this.newImagePreview = null;
            this.deleteImage = false;
            this.showEditModal = true;
            this.$nextTick(() => {
                const trix = document.getElementById('edit-trix-editor');
                if(trix) trix.editor.loadHTML(this.editState.question_text);
                if (window.renderMath) window.renderMath();
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
                const previewEl = document.getElementById(elementId);
                if (window.MathJax && previewEl) MathJax.typesetPromise([previewEl]);
            });
        }
    }">
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">{{ __('Kelola Bank Soal') }}</h2>
        </x-slot>

        <div class="py-8 sm:py-10 font-sans text-slate-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
                
                {{-- HERO INFO --}}
                <div class="relative rounded-[2rem] bg-gray-900 bg-gradient-to-br from-slate-900 via-indigo-900 to-indigo-800 p-8 text-white shadow-xl shadow-indigo-900/30 overflow-hidden border border-white/10">
                    <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-2 mb-2">
                            <a href="{{ route('bank.index') }}" class="text-xs font-bold text-indigo-300 hover:text-white transition flex items-center gap-1"><i class="ph-bold ph-arrow-left"></i> Kembali ke Bank Soal</a>
                            <span class="text-white/30 text-xs">•</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wide bg-white/10 text-white border border-white/10">{{ $bank->subject_name }}</span>
                        </div>
                        <h1 class="text-3xl font-black tracking-tight leading-none text-white mb-1">{{ $bank->title }}</h1>
                        <p class="text-indigo-200 text-sm font-medium">Total: {{ $bank->questions->count() }} Soal</p>
                    </div>
                </div>

                <div class="flex flex-col lg:flex-row gap-8 items-start">
                    
                    {{-- INPUT MANUAL (RICH TEXT) --}}
                    <div class="w-full lg:w-2/5 order-2 lg:order-1">
                        <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 lg:sticky lg:top-8">
                            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-50">
                                <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg shadow-sm border border-indigo-100"><i class="ph-fill ph-plus-circle"></i></div>
                                <div>
                                    <h3 class="font-black text-slate-800 text-lg leading-none">Tambah Soal</h3>
                                    <p class="text-xs text-slate-400 font-bold mt-1 uppercase tracking-wider">Ke Bank Soal</p>
                                </div>
                            </div>

                            <form action="{{ route('bank.questions.store', $bank->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5" id="createQuestionForm">
                                @csrf
                                
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Pertanyaan</label>
                                    <input id="q_input_create" type="hidden" name="question_text">
                                    <trix-editor input="q_input_create" @trix-change="createQuestionText = $event.target.value; updatePreview('create-preview')" placeholder="Tulis soal di sini..." class="prose prose-sm max-w-none"></trix-editor>
                                    <div x-show="createQuestionText.length > 0" class="mt-2 bg-slate-50 border border-slate-200 rounded-xl p-3">
                                        <p class="text-[10px] text-slate-400 uppercase font-bold mb-1">Preview:</p>
                                        <div id="create-preview" class="text-sm text-slate-800 trix-content" x-html="createQuestionText"></div>
                                    </div>
                                </div>

                                <div x-data="{ createPreviewImage: null }">
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Gambar (Opsional)</label>
                                    <template x-if="createPreviewImage">
                                        <div class="mb-3 relative w-full group">
                                            <img :src="createPreviewImage" class="w-full h-40 object-cover rounded-2xl border border-slate-200">
                                            <button type="button" @click="createPreviewImage = null; $refs.fileInput.value = ''" class="absolute top-2 right-2 bg-rose-500 text-white p-1.5 rounded-xl shadow-lg hover:bg-rose-600 transition"><i class="ph-bold ph-trash"></i></button>
                                        </div>
                                    </template>
                                    <label class="flex flex-col items-center justify-center w-full h-20 border-2 border-dashed border-slate-200 rounded-2xl cursor-pointer hover:bg-indigo-50 hover:border-indigo-300 transition-all bg-slate-50">
                                        <div class="flex items-center gap-2"><i class="ph-duotone ph-image text-xl text-slate-400"></i><span class="text-xs font-bold text-slate-500">Upload Gambar</span></div>
                                        <input type="file" x-ref="fileInput" name="question_image" @change="createPreviewImage = URL.createObjectURL($event.target.files[0])" accept="image/*" class="hidden">
                                    </label>
                                </div>

                                <div class="space-y-3 pt-2">
                                    <label class="block text-xs font-bold text-slate-400 uppercase ml-1">Pilihan Jawaban</label>
                                    @foreach(['A','B','C','D'] as $opt)
                                    <div class="flex gap-3 items-center group">
                                        <span class="w-10 h-10 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center font-black text-sm">{{ $opt }}</span>
                                        <input type="text" name="option_{{ $opt }}" required class="flex-1 rounded-xl border-slate-200 bg-white text-sm py-2.5 px-4 focus:ring-indigo-500 focus:border-indigo-500 font-medium" placeholder="Jawaban {{ $opt }}">
                                    </div>
                                    @endforeach
                                </div>

                                <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-100">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Kunci</label>
                                        <div class="relative">
                                            <select name="correct_answer" required class="w-full rounded-xl border-slate-200 text-sm font-bold text-slate-700 bg-slate-50 h-11 px-4 appearance-none focus:ring-indigo-500"><option value="A">A</option><option value="B">B</option><option value="C">C</option><option value="D">D</option></select>
                                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-500"><i class="ph-bold ph-caret-down"></i></div>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Bobot</label>
                                        <input type="number" name="score_weight" value="2" required class="w-full rounded-xl border-slate-200 bg-slate-50 text-sm font-bold text-center h-11 focus:ring-indigo-500">
                                    </div>
                                </div>

                                <button type="submit" class="w-full py-3.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-500/20 flex items-center justify-center gap-2 transform active:scale-95">
                                    <i class="ph-bold ph-floppy-disk text-lg"></i> Simpan ke Bank
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- LIST SOAL --}}
                    <div class="w-full lg:w-3/5 order-1 lg:order-2 space-y-6">
                        <div class="flex flex-col sm:flex-row justify-between items-center px-2 gap-4">
                            <h3 class="font-black text-slate-800 text-lg flex items-center gap-2">
                                <i class="ph-fill ph-list-dashes text-indigo-500"></i> Isi Bank Soal
                            </h3>
                            <div class="relative w-full sm:w-64">
                                <i class="ph-bold ph-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input type="text" x-model="questionSearch" placeholder="Cari isi pertanyaan..." class="w-full pl-10 pr-4 py-2 text-sm font-bold border-slate-200 rounded-xl focus:ring-indigo-500 bg-white shadow-sm transition">
                            </div>
                        </div>
                        
                        @forelse($bank->questions as $index => $q)
                            <div x-show="questionSearch === '' || '{{ strtolower(addslashes(strip_tags($q->question_text))) }}'.includes(questionSearch.toLowerCase())"
                                 class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm relative group hover:border-indigo-200 hover:shadow-lg transition-all duration-300">
                                
                                <div class="absolute top-6 left-6 w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center font-black text-slate-500 text-sm group-hover:bg-indigo-600 group-hover:text-white transition-colors shadow-inner">{{ $index + 1 }}</div>
                                
                                <div class="pl-16">
                                    @if($q->question_image)
                                        <div class="mb-4 group/img relative w-fit">
                                            <img src="{{ asset('storage/' . $q->question_image) }}" class="max-h-48 rounded-2xl border border-slate-100 shadow-sm object-cover">
                                        </div>
                                    @endif
                                    
                                    <div class="text-slate-800 font-medium text-lg mb-5 leading-relaxed trix-content prose prose-sm max-w-none">{!! $q->question_text !!}</div>
                                    
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                                        @foreach(['A','B','C','D'] as $opt)
                                            @php $val = isset($q->{'option_'.$opt}) ? $q->{'option_'.$opt} : ($q->options[$opt] ?? '-'); @endphp
                                            <div class="flex items-start gap-3 p-2.5 rounded-xl border transition-colors {{ $opt == $q->correct_answer ? 'bg-emerald-50 border-emerald-200' : 'bg-slate-50/50 border-transparent' }}">
                                                <span class="w-6 h-6 flex items-center justify-center rounded-lg border {{ $opt == $q->correct_answer ? 'border-emerald-500 bg-emerald-500 text-white' : 'border-slate-300 text-slate-400 bg-white' }} text-[10px] font-black shrink-0 mt-0.5">{{ $opt }}</span>
                                                <span class="leading-relaxed {{ $opt == $q->correct_answer ? 'text-emerald-800 font-bold' : 'text-slate-600 font-medium' }}">{{ $val }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- ACTION BUTTONS (Reuse route cbt.questions karena logic update/delete sama) --}}
                                <div class="absolute top-6 right-6 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    <button type="button" @click="openEdit({
                                            question_text: {{ json_encode($q->question_text) }},
                                            question_image: {{ json_encode($q->question_image) }}, 
                                            option_A: {{ json_encode($q->option_A ?? '') }},
                                            option_B: {{ json_encode($q->option_B ?? '') }},
                                            option_C: {{ json_encode($q->option_C ?? '') }},
                                            option_D: {{ json_encode($q->option_D ?? '') }},
                                            correct_answer: '{{ $q->correct_answer }}',
                                            score_weight: {{ $q->score_weight }}
                                        }, '{{ route('cbt.questions.update', $q->id) }}')"
                                        class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-indigo-600 hover:border-indigo-200 shadow-sm flex items-center justify-center transition-all hover:scale-105">
                                        <i class="ph-bold ph-pencil-simple text-lg"></i>
                                    </button>
                                    <form action="{{ route('cbt.questions.destroy', $q->id) }}" method="POST" class="delete-form">
                                        @csrf @method('DELETE')
                                        <button type="button" class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-rose-600 hover:border-rose-200 shadow-sm flex items-center justify-center transition-all hover:scale-105 btn-delete">
                                            <i class="ph-bold ph-trash text-lg"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-16 bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200">
                                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300 animate-pulse"><i class="ph-duotone ph-folder-dashed text-4xl"></i></div>
                                <h3 class="text-slate-800 font-black text-xl mb-1">Bank Soal Kosong</h3>
                                <p class="text-slate-500 text-sm">Isi bank soal ini untuk digunakan di ujian nanti.</p>
                            </div>
                        @endforelse
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
                            <h3 class="text-xl font-black text-slate-800 flex items-center gap-2"><i class="ph-fill ph-pencil-simple text-indigo-600"></i> Edit Soal</h3>
                            <button type="button" @click="showEditModal = false" class="text-slate-400 hover:text-rose-500 transition bg-slate-50 w-10 h-10 rounded-xl flex items-center justify-center"><i class="ph-bold ph-x text-lg"></i></button>
                        </div>

                        <div class="p-8 space-y-6 max-h-[70vh] overflow-y-auto custom-scrollbar bg-slate-50/30">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Pertanyaan</label>
                                <input id="q_input_edit" type="hidden" name="question_text" x-model="editState.question_text">
                                <trix-editor id="edit-trix-editor" input="q_input_edit" @trix-change="editState.question_text = $event.target.value; updatePreview('edit-preview')" class="prose prose-sm max-w-none bg-white"></trix-editor>
                                <div class="mt-2 bg-slate-50 border border-slate-200 rounded-xl p-3"><p class="text-[10px] text-slate-400 uppercase font-bold mb-1">Preview:</p><div id="edit-preview" class="text-sm text-slate-800 trix-content" x-html="editState.question_text"></div></div>
                            </div>

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
                                    <template x-if="deleteImage && !newImagePreview"><div class="text-xs text-rose-600 font-bold bg-rose-50 p-3 rounded-xl border border-rose-100 flex items-center gap-2"><i class="ph-bold ph-trash"></i> Gambar akan dihapus. <button type="button" @click="deleteImage = false" class="text-blue-600 underline ml-auto">Batalkan</button></div></template>
                                    <div class="flex-1">
                                        <input type="file" x-ref="editFileInput" name="question_image" @change="handleEditImage" accept="image/*" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-white file:text-blue-600 hover:file:bg-blue-50 cursor-pointer border border-slate-200 rounded-xl bg-slate-50">
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <label class="block text-xs font-bold text-slate-400 uppercase ml-1">Edit Pilihan</label>
                                <div class="grid grid-cols-1 gap-3">
                                    @foreach(['A','B','C','D'] as $opt)
                                    <div class="flex gap-3 items-center">
                                        <span class="w-10 h-10 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center font-black text-sm">{{ $opt }}</span>
                                        <input type="text" name="option_{{ $opt }}" x-model="editState.option_{{ $opt }}" required class="flex-1 rounded-xl border-slate-200 text-sm py-2.5 px-4 focus:ring-indigo-500 font-medium">
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Kunci</label>
                                    <div class="relative"><select name="correct_answer" x-model="editState.correct_answer" required class="w-full rounded-xl border-slate-200 text-sm font-bold text-slate-700 bg-white h-11 appearance-none px-4"><option value="A">A</option><option value="B">B</option><option value="C">C</option><option value="D">D</option></select><div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-500"><i class="ph-bold ph-caret-down"></i></div></div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Bobot</label>
                                    <input type="number" name="score_weight" x-model="editState.score_weight" required class="w-full rounded-xl border-slate-200 text-sm font-bold text-center h-11">
                                </div>
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
            @if(session('success'))
                Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, customClass: { popup: 'rounded-xl' } });
            @endif
        });
    </script>
</x-app-layout>