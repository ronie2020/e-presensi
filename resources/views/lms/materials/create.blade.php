<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Upload Materi Baru') }}
        </h2>
    </x-slot>

    {{-- CUSTOM STYLES --}}
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        [x-cloak] { display: none !important; }
    </style>

    {{-- CSS QUILL DARK THEME --}}
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        .ql-toolbar.ql-snow { border: 1px solid rgba(255, 255, 255, 0.1) !important; background-color: rgba(15, 23, 42, 0.9); border-top-left-radius: 1rem; border-top-right-radius: 1rem; }
        .ql-container.ql-snow { border: 1px solid rgba(255, 255, 255, 0.1) !important; border-top: none !important; background-color: rgba(15, 23, 42, 0.6); border-bottom-left-radius: 1rem; border-bottom-right-radius: 1rem; color: #f8fafc; }
        .ql-editor { font-size: 0.95rem; line-height: 1.7; padding: 1.25rem; min-height: 180px; }
        .ql-editor.ql-blank::before { font-style: normal; color: #64748b; }
        .ql-snow .ql-stroke { stroke: #94a3b8 !important; }
        .ql-snow .ql-fill { fill: #94a3b8 !important; }
        .ql-snow .ql-picker { color: #94a3b8 !important; }
        .ql-snow .ql-picker-options { background-color: #0f172a !important; border-color: rgba(255, 255, 255, 0.1) !important; }
    </style>

    <div class="py-8 sm:py-10 font-sans text-slate-100 bg-[#020b18] min-h-screen relative overflow-hidden pb-20">

        {{-- Efek Latar Belakang --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-gradient-to-b from-[#0d52a1]/20 via-[#031d3d]/10 to-transparent opacity-30 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            {{-- HERO HEADER --}}
            <x-hero-section
                badge="LMS Pembelajaran"
                badgeIcon="ph-book-open"
                title="Upload Materi"
                titleHighlight="Bahan Ajar Baru"
                description="Bagikan bahan ajar, dokumen, modul, atau video pembelajaran interaktif untuk siswa."
                :chips="[
                    ['icon' => 'ph-file-arrow-up', 'label' => 'Materi Digital'],
                    ['icon' => 'ph-folder-notch-open', 'label' => 'Terintegrasi Bab'],
                    ['icon' => 'ph-sparkle', 'label' => 'Akses Siswa']
                ]"
                heroIcon="ph-books"
                showcaseValue="Baru"
                showcaseLabel="Upload Modul"
                showcaseSubtitle="Bahan Ajar Siswa"
                statusOrb="Form Input"
                statusColor="sky"
            >
                <x-slot:cta>
                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ route('lms.materials.index') }}" class="px-5 py-2.5 bg-white/10 hover:bg-white/20 text-slate-200 hover:text-white font-bold text-xs rounded-xl border border-white/15 backdrop-blur-md hover:scale-[1.02] transition-all flex items-center gap-2">
                            <i class="ph-bold ph-arrow-left text-base text-sky-400"></i> Kembali ke Materi
                        </a>
                        <a href="{{ route('dashboard') }}" class="px-4 py-2.5 bg-white/5 hover:bg-white/10 text-slate-300 hover:text-white font-bold text-xs rounded-xl border border-white/10 transition-all flex items-center gap-1.5">
                            <i class="ph-bold ph-squares-four text-sm text-sky-400"></i> Dashboard
                        </a>
                    </div>
                </x-slot:cta>
            </x-hero-section>

            {{-- INFO ALUR BELAJAR (PRO-TIP) --}}
            <div class="animate-enter mb-8 bg-sky-500/10 border border-sky-400/20 p-5 rounded-[2rem] flex flex-col md:flex-row items-start md:items-center gap-4 shadow-sm backdrop-blur-md" style="animation-delay: 50ms">
                <div class="w-12 h-12 bg-sky-500/20 text-sky-300 rounded-2xl shrink-0 flex items-center justify-center text-2xl border border-sky-400/30">
                    <i class="ph-duotone ph-books"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-black text-sky-200 mb-1">Struktur Bab Pembelajaran</h3>
                    <p class="text-xs font-medium text-slate-300 leading-relaxed">
                        Materi ini akan dikelompokkan ke dalam <b class="text-sky-300">Bab/Pokok Bahasan</b> yang Anda pilih. Siswa akan mempelajari materi secara berurutan di dalam Bab tersebut sebelum mengerjakan tugas terkait.
                    </p>
                </div>
            </div>

            {{-- ERROR BLOCK --}}
            @if ($errors->any())
                <div class="animate-enter mb-8 bg-rose-500/10 border border-rose-500/30 p-5 rounded-[1.5rem] flex items-start gap-4 shadow-sm backdrop-blur-md">
                    <div class="w-10 h-10 bg-rose-500/20 text-rose-400 rounded-xl shrink-0 border border-rose-500/30 shadow-sm flex items-center justify-center">
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

            {{-- FORM CARD --}}
            <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] shadow-2xl border border-white/10 overflow-hidden text-slate-100 backdrop-blur-xl animate-enter" style="animation-delay: 100ms">

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
                                <div class="w-12 h-12 rounded-xl bg-sky-500/20 text-sky-400 border border-sky-400/30 flex items-center justify-center text-2xl shadow-sm"><i class="ph-bold ph-info"></i></div>
                                <h3 class="text-xl font-black text-white">Informasi Dasar</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="col-span-1 md:col-span-2">
                                    <label class="block text-[10px] font-bold text-sky-400 uppercase tracking-widest mb-2 ml-1">Judul Materi <span class="text-rose-400">*</span></label>
                                    <input type="text" name="title" value="{{ old('title') }}" required
                                           class="w-full rounded-2xl border-white/10 bg-slate-900/80 font-black text-white focus:ring-sky-400/20 focus:border-sky-400 focus:bg-slate-900 h-14 px-5 transition-colors shadow-sm placeholder:text-slate-500"
                                           placeholder="Contoh: Modul 1 - Pengenalan Sel">
                                </div>

                                <!-- MATA PELAJARAN -->
                                <div>
                                    <label class="block text-[10px] font-bold text-sky-400 uppercase tracking-widest mb-2 ml-1">Mata Pelajaran <span class="text-rose-400">*</span></label>
                                    <div class="relative group">
                                        <select name="subject_id" x-model="selectedSubject" @change="fetchTopics()" required class="w-full rounded-2xl border-white/10 bg-slate-900/80 font-bold text-white focus:ring-sky-400/20 focus:border-sky-400 h-14 px-5 appearance-none cursor-pointer focus:bg-slate-900 transition-colors shadow-sm [color-scheme:dark]">
                                            <option value="" class="bg-slate-900 text-white">-- Pilih Mapel --</option>
                                            @foreach($subjects as $subject)
                                                <option value="{{ $subject->id }}" class="bg-slate-900 text-white">{{ $subject->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-sky-400 transition-colors"><i class="ph-bold ph-caret-down text-lg"></i></div>
                                    </div>
                                </div>

                                <!-- POKOK BAHASAN -->
                                <div>
                                    <label class="block text-[10px] font-bold text-sky-400 uppercase tracking-widest mb-2 ml-1 flex items-center gap-1.5">
                                        Pokok Bahasan / Bab <span class="text-rose-400">*</span>
                                        <span x-show="selectedSubject && topics.length === 0" class="text-xs normal-case text-amber-400"><i class="ph-fill ph-warning-circle"></i> Belum ada bab</span>
                                    </label>
                                    <div class="relative group">
                                        <select name="topic_id" x-model="selectedTopic" :disabled="topics.length === 0" required class="w-full rounded-2xl border-white/10 bg-slate-900/80 font-bold text-white focus:bg-slate-900 focus:ring-sky-400/20 focus:border-sky-400 h-14 px-5 appearance-none transition-colors cursor-pointer shadow-sm disabled:opacity-50 disabled:cursor-not-allowed [color-scheme:dark]">
                                            <option value="" class="bg-slate-900 text-white">-- Pilih Bab Terlebih Dahulu --</option>
                                            <template x-for="topic in topics" :key="topic.id">
                                                <option :value="topic.id" x-text="topic.title" :selected="topic.id == selectedTopic" class="bg-slate-900 text-white"></option>
                                            </template>
                                        </select>
                                        <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-sky-400"><i class="ph-bold ph-caret-down text-lg"></i></div>
                                    </div>
                                    <p class="text-[10px] font-bold text-slate-400 mt-2 ml-1" x-show="!selectedSubject">Silakan pilih Mata Pelajaran terlebih dahulu.</p>
                                </div>
                            </div>
                        </div>

                        <div class="h-px bg-white/10"></div>

                        <!-- 2. PENGANTAR / RESUME -->
                        <div>
                            <label class="block text-[10px] font-bold text-sky-400 uppercase tracking-widest mb-2 ml-1">Pengantar & Penjelasan Singkat</label>
                            <input type="hidden" name="resume" id="resume-input" value="{{ old('resume') }}">
                            <div class="rounded-2xl border border-white/10 overflow-hidden shadow-sm bg-slate-900/80">
                                <div id="quill-editor" class="text-slate-100 font-medium">{!! old('resume') !!}</div>
                            </div>
                        </div>

                        <div class="h-px bg-white/10"></div>

                        <!-- 3. LAMPIRAN -->
                        <div class="bg-slate-900/60 rounded-[2rem] border border-white/10 p-6 md:p-8">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                                <div>
                                    <label class="block text-sm font-black text-white flex items-center gap-2">
                                        <i class="ph-fill ph-paperclip text-sky-400 text-xl"></i> Lampiran Materi
                                    </label>
                                    <p class="text-[11px] font-bold text-slate-400 mt-1">Upload dokumen PDF/Word, atau tautkan link/video YouTube pembelajaran.</p>
                                </div>
                                <button type="button" @click="attachments.push({id: Date.now(), type: 'file', link: '', name: ''})"
                                        class="w-full sm:w-auto text-xs bg-slate-800 border border-white/10 text-sky-300 px-5 py-3 sm:py-2.5 rounded-xl font-bold hover:bg-slate-700 hover:text-white transition-colors shadow-sm flex items-center justify-center gap-2 active:scale-95">
                                    <i class="ph-bold ph-plus"></i> Tambah Lampiran
                                </button>
                            </div>

                            <div class="space-y-4">
                                <template x-for="(att, index) in attachments" :key="att.id">
                                    <div class="flex flex-col md:flex-row items-start gap-4 p-5 bg-slate-900/90 rounded-2xl border border-white/10 relative group animate-enter hover:border-sky-400/50 transition-colors shadow-sm">
                                        <div class="flex-1 w-full grid grid-cols-1 md:grid-cols-12 gap-4">

                                            <div class="md:col-span-3">
                                                <div class="relative group/sel">
                                                    <select :name="'new_attachments['+index+'][type]'" x-model="att.type" class="w-full text-sm font-bold rounded-xl border-white/10 bg-slate-800 focus:ring-sky-400/20 focus:border-sky-400 cursor-pointer h-12 px-4 appearance-none shadow-sm text-white focus:bg-slate-900 transition-colors [color-scheme:dark]">
                                                        <option value="file" class="bg-slate-900 text-white">Dokumen / File</option>
                                                        <option value="video" class="bg-slate-900 text-white">Video YouTube</option>
                                                        <option value="link" class="bg-slate-900 text-white">Link Web Eksternal</option>
                                                    </select>
                                                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400 group-focus-within/sel:text-sky-400 transition-colors"><i class="ph-bold ph-caret-down"></i></div>
                                                </div>
                                            </div>

                                            <div class="md:col-span-5">
                                                <input x-show="att.type === 'file'" type="file" :name="'new_attachments['+index+'][file]'" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.mp4" :required="att.type === 'file'" class="block w-full text-sm text-slate-400 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-800 file:text-sky-300 h-12 border border-white/10 rounded-xl bg-slate-900 cursor-pointer hover:file:bg-slate-700 shadow-sm transition-colors">
                                                <input x-show="att.type !== 'file'" type="url" :name="'new_attachments['+index+'][link]'" x-model="att.link" :required="att.type !== 'file'" class="w-full text-sm font-medium rounded-xl border-white/10 h-12 placeholder:text-slate-500 focus:ring-sky-400/20 focus:border-sky-400 text-white px-4 shadow-sm bg-slate-800 focus:bg-slate-900 transition-colors" placeholder="https://...">
                                            </div>

                                            <div class="md:col-span-4">
                                                <input type="text" :name="'new_attachments['+index+'][name]'" x-model="att.name" class="w-full text-sm font-medium rounded-xl border-white/10 h-12 placeholder:text-slate-500 focus:ring-sky-400/20 focus:border-sky-400 text-white px-4 shadow-sm bg-slate-800 focus:bg-slate-900 transition-colors" placeholder="Beri Label (Opsional)">
                                            </div>

                                        </div>
                                        <button type="button" @click="attachments = attachments.filter(i => i.id !== att.id)" x-show="attachments.length > 1" class="absolute top-3 right-3 md:static md:mt-1 w-10 h-10 flex items-center justify-center rounded-xl bg-rose-500/20 text-rose-300 border border-rose-500/30 hover:bg-rose-500/30 transition-colors shadow-sm shrink-0" title="Hapus Baris">
                                            <i class="ph-bold ph-trash text-lg"></i>
                                        </button>
                                    </div>
                                </template>
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
                        <a href="{{ route('lms.materials.index') }}" class="w-full sm:w-auto px-8 py-4 bg-slate-800 border border-white/10 text-slate-300 font-bold rounded-2xl hover:bg-slate-700 hover:text-white transition-colors text-center text-sm shadow-sm active:scale-95">Batal</a>
                        <button type="submit" class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-sky-400 to-blue-600 text-slate-950 font-bold rounded-2xl shadow-lg shadow-sky-500/20 hover:from-sky-300 hover:to-blue-500 transition-all flex items-center justify-center gap-2 text-sm active:scale-95">
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
    @endpush
</x-app-layout>