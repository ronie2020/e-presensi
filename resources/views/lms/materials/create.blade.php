<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-elevate-dark leading-tight">
            {{ __('Upload Materi Baru') }}
        </h2>
    </x-slot>

    {{-- CUSTOM STYLES --}}
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        [x-cloak] { display: none !important; }
    </style>

    {{-- CSS QUILL --}}
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        .ql-toolbar.ql-snow { border: none !important; border-bottom: 1px solid #e2e8f0 !important; background-color: #f8fafc; font-family: inherit; border-top-left-radius: 1rem; border-top-right-radius: 1rem; }
        .ql-container.ql-snow { border: none !important; font-family: inherit; border-bottom-left-radius: 1rem; border-bottom-right-radius: 1rem; }
        .ql-editor { font-size: 0.95rem; line-height: 1.7; padding: 1.25rem; min-height: 180px; }
        .ql-editor.ql-blank::before { font-style: normal; color: #94a3b8; }
    </style>

    <div class="py-8 sm:py-10 font-sans text-elevate-dark bg-elevate-surface min-h-screen relative overflow-hidden pb-20">

        {{-- Efek Latar Belakang --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            {{-- HERO HEADER --}}
            <div class="relative rounded-[2rem] bg-gradient-to-r from-elevate-accent via-elevate-peach-light to-elevate-peach p-8 md:p-10 mb-8 text-elevate-dark shadow-xl shadow-elevate-accent/20 overflow-hidden border border-white/60 group animate-enter">
                <div class="absolute -top-10 -left-10 w-56 h-56 bg-elevate-primary/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-elevate-peach/40 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-xl"></div>

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <h1 class="text-3xl md:text-4xl font-black mb-2 tracking-tight text-elevate-dark">Upload Materi</h1>
                        <p class="text-elevate-dark/80 text-sm font-semibold max-w-lg leading-relaxed">Bagikan bahan ajar, dokumen, atau video pembelajaran untuk siswa.</p>
                    </div>
                    <a href="{{ route('lms.materials.index') }}" class="w-full md:w-auto inline-flex justify-center items-center gap-2 px-6 py-3.5 bg-white/60 hover:bg-white rounded-xl text-sm font-bold backdrop-blur-md transition-colors text-elevate-dark border border-white/60 shadow-sm active:scale-95 btn-cancel-confirm shrink-0">
                        <i class="ph-bold ph-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>

            {{-- INFO ALUR BELAJAR (PRO-TIP) --}}
            <div class="animate-enter mb-8 bg-blue-50 border border-blue-200 p-5 rounded-[2rem] flex flex-col md:flex-row items-start md:items-center gap-4 shadow-sm" style="animation-delay: 50ms">
                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-2xl shrink-0 flex items-center justify-center text-2xl">
                    <i class="ph-duotone ph-books"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-black text-blue-900 mb-1">Struktur Bab Pembelajaran</h3>
                    <p class="text-xs font-medium text-blue-700 leading-relaxed">
                        Materi ini akan dikelompokkan ke dalam <b>Bab/Pokok Bahasan</b> yang Anda pilih. Siswa akan mempelajari materi secara berurutan di dalam Bab tersebut sebelum mengerjakan tugas terkait.
                    </p>
                </div>
            </div>

            {{-- ERROR BLOCK --}}
            @if ($errors->any())
                <div class="animate-enter mb-8 bg-[#FDE7E9] border border-[#F4C3C9] p-5 rounded-[1.5rem] flex items-start gap-4 shadow-sm">
                    <div class="w-10 h-10 bg-white text-[#D13438] rounded-xl shrink-0 border border-[#F4C3C9] shadow-sm flex items-center justify-center">
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

            {{-- FORM CARD --}}
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden animate-enter" style="animation-delay: 100ms">

                @php
                    $oldAttachments = old('new_attachments', []);
                @endphp

                <form action="{{ route('lms.materials.store') }}" method="POST" enctype="multipart/form-data" id="createMaterialForm"
                      x-data="{
                          selectedSubject: '{{ old('subject_id') }}',
                          topics: [],
                          selectedTopic: '{{ old('topic_id') }}',
                          targetType: '{{ old('target_type', 'class') }}',
                          attachments: {{ json_encode(!empty($oldAttachments) ? array_map(fn($a) => ['id' => uniqid(), 'type' => $a['type'] ?? 'file', 'link' => $a['link'] ?? '', 'name' => $a['name'] ?? ''], $oldAttachments) : [['id' => 'row-1', 'type' => 'file', 'link' => '', 'name' => '']]) }},

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

                        <!-- 1. IDENTITAS MATERI -->
                        <div>
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-12 h-12 rounded-xl bg-elevate-soft text-elevate-primary border border-slate-100 flex items-center justify-center text-2xl shadow-sm"><i class="ph-bold ph-info"></i></div>
                                <h3 class="text-xl font-black text-elevate-dark">Informasi Dasar</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="col-span-1 md:col-span-2">
                                    <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">Judul Materi <span class="text-[#D13438]">*</span></label>
                                    <input type="text" name="title" value="{{ old('title') }}" required
                                           class="w-full rounded-2xl border-slate-200 bg-elevate-soft font-black text-elevate-dark focus:ring-elevate-accent/30 focus:border-elevate-accent focus:bg-white h-14 px-5 transition-colors shadow-sm"
                                           placeholder="Contoh: Modul 1 - Pengenalan Sel">
                                </div>

                                <!-- MATA PELAJARAN -->
                                <div>
                                    <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">Mata Pelajaran <span class="text-[#D13438]">*</span></label>
                                    <div class="relative group">
                                        <select name="subject_id" x-model="selectedSubject" @change="fetchTopics()" required class="w-full rounded-2xl border-slate-200 bg-elevate-soft font-bold text-elevate-dark focus:ring-elevate-accent/30 focus:border-elevate-accent h-14 px-5 appearance-none cursor-pointer focus:bg-white transition-colors shadow-sm">
                                            <option value="">-- Pilih Mapel --</option>
                                            @foreach($subjects as $subject)
                                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-slate-500 group-focus-within:text-elevate-primary transition-colors"><i class="ph-bold ph-caret-down text-lg"></i></div>
                                    </div>
                                </div>

                                <!-- POKOK BAHASAN -->
                                <div>
                                    <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1 flex items-center gap-1.5">
                                        Pokok Bahasan / Bab <span class="text-[#D13438]">*</span>
                                        <span x-show="selectedSubject && topics.length === 0" class="text-xs normal-case text-[#D83B01]"><i class="ph-fill ph-warning-circle"></i> Belum ada bab</span>
                                    </label>
                                    <div class="relative group">
                                        <select name="topic_id" x-model="selectedTopic" :disabled="topics.length === 0" required class="w-full rounded-2xl border-slate-200 bg-elevate-soft font-bold text-elevate-dark focus:bg-white focus:ring-elevate-accent/30 focus:border-elevate-accent h-14 px-5 appearance-none transition-colors cursor-pointer shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                                            <option value="">-- Pilih Bab Terlebih Dahulu --</option>
                                            <template x-for="topic in topics" :key="topic.id">
                                                <option :value="topic.id" x-text="topic.title" :selected="topic.id == selectedTopic"></option>
                                            </template>
                                        </select>
                                        <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-slate-500 group-focus-within:text-elevate-primary"><i class="ph-bold ph-caret-down text-lg"></i></div>
                                    </div>
                                    <p class="text-[10px] font-bold text-slate-400 mt-2 ml-1" x-show="!selectedSubject">Silakan pilih Mata Pelajaran terlebih dahulu.</p>
                                </div>
                            </div>
                        </div>

                        <div class="h-px bg-slate-100"></div>

                        <!-- 2. PENGANTAR / RESUME -->
                        <div>
                            <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">Pengantar & Penjelasan Singkat</label>
                            <input type="hidden" name="resume" id="resume-input" value="{{ old('resume') }}">
                            <div class="rounded-2xl border border-slate-200 overflow-hidden shadow-sm bg-white">
                                <div id="quill-editor" class="text-elevate-dark font-medium">{!! old('resume') !!}</div>
                            </div>
                        </div>

                        <div class="h-px bg-slate-100"></div>

                        <!-- 3. LAMPIRAN -->
                        <div class="bg-elevate-soft/30 rounded-[2rem] border border-slate-100 p-6 md:p-8">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                                <div>
                                    <label class="block text-sm font-black text-elevate-dark flex items-center gap-2">
                                        <i class="ph-fill ph-paperclip text-elevate-primary text-xl"></i> Lampiran Materi
                                    </label>
                                    <p class="text-[11px] font-bold text-slate-400 mt-1">Upload dokumen PDF/Word, atau tautkan link/video YouTube pembelajaran.</p>
                                </div>
                                <button type="button" @click="attachments.push({id: Date.now(), type: 'file', link: '', name: ''})"
                                        class="w-full sm:w-auto text-xs bg-white border border-slate-200 text-elevate-primary px-5 py-3 sm:py-2.5 rounded-xl font-bold hover:bg-elevate-soft hover:border-elevate-accent transition-colors shadow-sm flex items-center justify-center gap-2 active:scale-95">
                                    <i class="ph-bold ph-plus"></i> Tambah Lampiran
                                </button>
                            </div>

                            <div class="space-y-4">
                                <template x-for="(att, index) in attachments" :key="att.id">
                                    <div class="flex flex-col md:flex-row items-start gap-4 p-5 bg-white rounded-2xl border border-slate-200 relative group animate-enter hover:border-elevate-accent/50 transition-colors shadow-sm">
                                        <div class="flex-1 w-full grid grid-cols-1 md:grid-cols-12 gap-4">

                                            <div class="md:col-span-3">
                                                <div class="relative group/sel">
                                                    <select :name="'new_attachments['+index+'][type]'" x-model="att.type" class="w-full text-sm font-bold rounded-xl border-slate-200 bg-elevate-soft focus:ring-elevate-accent/30 focus:border-elevate-accent cursor-pointer h-12 px-4 appearance-none shadow-sm text-elevate-dark focus:bg-white transition-colors">
                                                        <option value="file">Dokumen / File</option>
                                                        <option value="video">Video YouTube</option>
                                                        <option value="link">Link Web Eksternal</option>
                                                    </select>
                                                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400 group-focus-within/sel:text-elevate-primary transition-colors"><i class="ph-bold ph-caret-down"></i></div>
                                                </div>
                                            </div>

                                            <div class="md:col-span-5">
                                                <input x-show="att.type === 'file'" type="file" :name="'new_attachments['+index+'][file]'" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.mp4" :required="att.type === 'file'" class="block w-full text-sm text-slate-500 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-elevate-soft file:text-elevate-primary h-12 border border-slate-200 rounded-xl bg-white cursor-pointer hover:file:bg-elevate-primary/20 shadow-sm transition-colors">
                                                <input x-show="att.type !== 'file'" type="url" :name="'new_attachments['+index+'][link]'" x-model="att.link" :required="att.type !== 'file'" class="w-full text-sm font-medium rounded-xl border-slate-200 h-12 placeholder:text-slate-400 focus:ring-elevate-accent/30 focus:border-elevate-accent text-elevate-dark px-4 shadow-sm bg-elevate-soft focus:bg-white transition-colors" placeholder="https://...">
                                            </div>

                                            <div class="md:col-span-4">
                                                <input type="text" :name="'new_attachments['+index+'][name]'" x-model="att.name" class="w-full text-sm font-medium rounded-xl border-slate-200 h-12 placeholder:text-slate-400 focus:ring-elevate-accent/30 focus:border-elevate-accent text-elevate-dark px-4 shadow-sm bg-elevate-soft focus:bg-white transition-colors" placeholder="Beri Label (Opsional)">
                                            </div>

                                        </div>
                                        <button type="button" @click="attachments = attachments.filter(i => i.id !== att.id)" x-show="attachments.length > 1" class="absolute top-3 right-3 md:static md:mt-1 w-10 h-10 flex items-center justify-center rounded-xl bg-white text-[#D13438] hover:bg-[#FDE7E9] border border-[#F4C3C9] transition-colors shadow-sm shrink-0" title="Hapus Baris">
                                            <i class="ph-bold ph-trash text-lg"></i>
                                        </button>
                                    </div>
                                </template>
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
                        <a href="{{ route('lms.materials.index') }}" class="w-full sm:w-auto px-8 py-4 bg-white border-2 border-slate-200 text-elevate-dark font-bold rounded-2xl hover:bg-elevate-soft transition-colors text-center text-sm shadow-sm btn-cancel-confirm active:scale-95">Batal</a>
                        <button type="submit" class="w-full sm:w-auto px-8 py-4 bg-elevate-dark text-white font-bold rounded-2xl shadow-lg shadow-elevate-dark/30 hover:bg-elevate-primary transition-all flex items-center justify-center gap-2 text-sm border border-transparent active:scale-95">
                            <i class="ph-bold ph-cloud-arrow-up text-lg"></i> <span>Terbitkan Materi</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- SCRIPT SWEETALERT2 & QUILL --}}
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // 1. Inisialisasi Editor Quill
            var quillEditor = null;
            if (document.querySelector('#quill-editor')) {
                quillEditor = new Quill('#quill-editor', {
                    theme: 'snow',
                    placeholder: 'Tuliskan pengantar atau ringkasan materi di sini...',
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

            // 2. Tangani Submit Form (Sync Quill + Loading)
            const form = document.getElementById('createMaterialForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // A. Sync data Quill ke input hidden
                    if (quillEditor) {
                        var html = quillEditor.root.innerHTML;
                        document.getElementById('resume-input').value = html === '<p><br></p>' ? '' : html;
                    }

                    // B. Validasi Bawaan Browser
                    if (!this.checkValidity()) {
                        this.reportValidity();
                        return;
                    }

                    // C. Munculkan Loading & Submit Aktual
                    Swal.fire({
                        title: 'Sedang Mengunggah...',
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
    @endpush
</x-app-layout>