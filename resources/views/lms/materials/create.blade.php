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
                          targetType: '{{ old('target_type', 'multiple') }}',
                          selectedClasses: {{ json_encode(array_map('strval', (array)old('class_ids', old('class_id') ? [old('class_id')] : []))) }},
                          availableClasses: {{ $classes->map(fn($c) => ['id' => (string)$c->id, 'name' => $c->name])->toJson() }},
                          allSchoolClasses: {{ ($allClasses ?? $classes)->map(fn($c) => ['id' => (string)$c->id, 'name' => $c->name])->toJson() }},
                          showAllSchoolClasses: false,
                          targetGrade: '{{ old('target_grade', '7') }}',
                          attachments: {{ json_encode(!empty($oldAttachments) ? array_map(fn($a) => ['id' => uniqid(), 'type' => $a['type'] ?? 'file', 'link' => $a['link'] ?? '', 'name' => $a['name'] ?? ''], $oldAttachments) : [['id' => 'row-1', 'type' => 'file', 'link' => '', 'name' => '']]) }},

                          get displayClasses() {
                              return this.showAllSchoolClasses ? this.allSchoolClasses : this.availableClasses;
                          },

                          toggleClass(id) {
                              id = String(id);
                              if (this.selectedClasses.includes(id)) {
                                  this.selectedClasses = this.selectedClasses.filter(x => x !== id);
                              } else {
                                  this.selectedClasses.push(id);
                              }
                          },

                          isClassSelected(id) {
                              return this.selectedClasses.includes(String(id));
                          },

                          selectAll() {
                              this.selectedClasses = this.displayClasses.map(c => String(c.id));
                          },

                          selectGrade(grade) {
                              const gradeIds = this.displayClasses
                                  .filter(c => c.name.startsWith(grade))
                                  .map(c => String(c.id));
                              this.selectedClasses = [...new Set([...this.selectedClasses, ...gradeIds])];
                          },

                          clearAll() {
                              this.selectedClasses = [];
                          },

                          getSelectedNames() {
                              return this.allSchoolClasses
                                  .filter(c => this.selectedClasses.includes(String(c.id)))
                                  .map(c => c.name)
                                  .join(', ');
                          },

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

                                {{-- FOTO COVER MATERI (OPSIONAL) --}}
                                <div class="col-span-1 md:col-span-2">
                                    <label class="block text-[10px] font-bold text-sky-400 uppercase tracking-widest mb-2 ml-1">Foto Sampul / Cover Course (Opsional)</label>
                                    <div class="relative flex items-center justify-center w-full">
                                        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-white/20 rounded-2xl cursor-pointer bg-slate-900/60 hover:bg-slate-900/90 hover:border-sky-400/50 transition-all group">
                                            <div class="flex flex-col items-center justify-center pt-4 pb-4">
                                                <i class="ph-duotone ph-image text-3xl text-sky-400 group-hover:scale-110 transition-transform mb-1.5"></i>
                                                <p class="text-xs text-slate-300 font-bold mb-1">Unggah Foto Sampul Materi (JPG, PNG, WEBP)</p>
                                                <p class="text-[10px] text-slate-400">Rekomendasi rasio 16:9 (Maksimal 5MB). Jika dikosongkan, gambar tematik otomatis akan digunakan.</p>
                                            </div>
                                            <input type="file" name="cover_image" accept="image/*" class="hidden" />
                                        </label>
                                    </div>
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
                                                        <option value="file" class="bg-slate-900 text-white">Dokumen / File / Web App (HTML, PDF, ZIP)</option>
                                                        <option value="video" class="bg-slate-900 text-white">Video YouTube</option>
                                                        <option value="link" class="bg-slate-900 text-white">Link Web Eksternal</option>
                                                    </select>
                                                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400 group-focus-within/sel:text-sky-400 transition-colors"><i class="ph-bold ph-caret-down"></i></div>
                                                </div>
                                            </div>

                                            <div class="md:col-span-5">
                                                <input x-show="att.type === 'file'" type="file" :name="'new_attachments['+index+'][file]'" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.mp4,.html,.htm,.zip,.rar,.7z" :required="att.type === 'file'" class="block w-full text-sm text-slate-400 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-800 file:text-sky-300 h-12 border border-white/10 rounded-xl bg-slate-900 cursor-pointer hover:file:bg-slate-700 shadow-sm transition-colors">
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
                                <p class="text-xs text-slate-400 mt-3 ml-1 flex items-center gap-1.5">
                                    <i class="ph-fill ph-info text-sky-400"></i> Mendukung berkas <b>PDF</b>, Word, Excel, PPT, Gambar, serta <b>Aplikasi Web Interaktif (HTML / index.html / ZIP)</b> hingga 30MB.
                                </p>
                            </div>
                        </div>

                        <!-- 4. TARGET PENERIMA -->
                        <div class="bg-slate-900/60 p-6 md:p-8 rounded-[2rem] border border-white/10 space-y-6">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                <label class="block text-xs font-black text-sky-400 uppercase tracking-widest flex items-center gap-2">
                                    <i class="ph-fill ph-users-three text-lg"></i> Target Penerima Materi <span class="text-rose-400">*</span>
                                </label>
                                <span class="text-xs text-slate-400 font-medium">Tentukan kelas mana saja yang dapat mengakses materi ini</span>
                            </div>

                            {{-- PILIHAN MODE: MULTI KELAS, SATU KELAS, PER JENJANG --}}
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                                {{-- OPSI 1: MULTI KELAS --}}
                                <label class="relative flex flex-col p-4 rounded-2xl border cursor-pointer transition-all duration-200 select-none group"
                                       :class="targetType === 'multiple' ? 'bg-sky-500/15 border-sky-400 shadow-lg shadow-sky-500/10' : 'bg-slate-900/80 border-white/10 hover:border-white/20'">
                                    <input type="radio" name="target_type" value="multiple" x-model="targetType" class="sr-only">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="w-9 h-9 rounded-xl flex items-center justify-center transition-colors"
                                             :class="targetType === 'multiple' ? 'bg-sky-400 text-slate-950 font-bold' : 'bg-slate-800 text-slate-400 group-hover:text-white'">
                                            <i class="ph-bold ph-squares-four text-lg"></i>
                                        </div>
                                        <span class="text-[10px] font-extrabold uppercase tracking-wider px-2 py-0.5 rounded-full"
                                              :class="targetType === 'multiple' ? 'bg-sky-400/20 text-sky-300 border border-sky-400/30' : 'bg-slate-800 text-slate-400'">
                                            Fleksibel
                                        </span>
                                    </div>
                                    <span class="text-sm font-black text-white">Pilih Multi Kelas</span>
                                    <span class="text-[11px] text-slate-400 mt-0.5 leading-snug">Pilih beberapa kelas sekaligus (misal: 7A, 7B, 7C)</span>
                                </label>

                                {{-- OPSI 2: SATU KELAS --}}
                                <label class="relative flex flex-col p-4 rounded-2xl border cursor-pointer transition-all duration-200 select-none group"
                                       :class="targetType === 'class' ? 'bg-sky-500/15 border-sky-400 shadow-lg shadow-sky-500/10' : 'bg-slate-900/80 border-white/10 hover:border-white/20'">
                                    <input type="radio" name="target_type" value="class" x-model="targetType" class="sr-only">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="w-9 h-9 rounded-xl flex items-center justify-center transition-colors"
                                             :class="targetType === 'class' ? 'bg-sky-400 text-slate-950 font-bold' : 'bg-slate-800 text-slate-400 group-hover:text-white'">
                                            <i class="ph-bold ph-chalkboard-teacher text-lg"></i>
                                        </div>
                                        <span class="text-[10px] font-extrabold uppercase tracking-wider px-2 py-0.5 rounded-full"
                                              :class="targetType === 'class' ? 'bg-sky-400/20 text-sky-300 border border-sky-400/30' : 'bg-slate-800 text-slate-400'">
                                            Tunggal
                                        </span>
                                    </div>
                                    <span class="text-sm font-black text-white">Satu Kelas</span>
                                    <span class="text-[11px] text-slate-400 mt-0.5 leading-snug">Khusus hanya untuk 1 kelas tertentu</span>
                                </label>

                                {{-- OPSI 3: PER JENJANG --}}
                                <label class="relative flex flex-col p-4 rounded-2xl border cursor-pointer transition-all duration-200 select-none group"
                                       :class="targetType === 'grade' ? 'bg-sky-500/15 border-sky-400 shadow-lg shadow-sky-500/10' : 'bg-slate-900/80 border-white/10 hover:border-white/20'">
                                    <input type="radio" name="target_type" value="grade" x-model="targetType" class="sr-only">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="w-9 h-9 rounded-xl flex items-center justify-center transition-colors"
                                             :class="targetType === 'grade' ? 'bg-sky-400 text-slate-950 font-bold' : 'bg-slate-800 text-slate-400 group-hover:text-white'">
                                            <i class="ph-bold ph-graduation-cap text-lg"></i>
                                        </div>
                                        <span class="text-[10px] font-extrabold uppercase tracking-wider px-2 py-0.5 rounded-full"
                                              :class="targetType === 'grade' ? 'bg-sky-400/20 text-sky-300 border border-sky-400/30' : 'bg-slate-800 text-slate-400'">
                                            1 Angkatan
                                        </span>
                                    </div>
                                    <span class="text-sm font-black text-white">Pilih Per Jenjang</span>
                                    <span class="text-[11px] text-slate-400 mt-0.5 leading-snug">Otomatis seluruh kelas di jenjang terpilih</span>
                                </label>
                            </div>

                            {{-- KONTEN BERDASARKAN MODE YANG DIPILIH --}}

                            {{-- 1. KONTEN MULTI KELAS --}}
                            <div x-show="targetType === 'multiple'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-4 pt-2">
                                <div class="flex flex-wrap items-center justify-between gap-3 bg-slate-900/90 p-4 rounded-2xl border border-white/10">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="text-xs font-bold text-slate-400 mr-1 flex items-center gap-1">
                                            <i class="ph-bold ph-lightning text-amber-400"></i> Pilih Cepat:
                                        </span>
                                        <button type="button" @click="selectAll()" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 hover:text-white text-xs font-bold transition-all border border-white/10 active:scale-95">
                                            Pilih Semua
                                        </button>
                                        <button type="button" @click="selectGrade('7')" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-sky-500/20 text-slate-200 hover:text-sky-300 text-xs font-bold transition-all border border-white/10 active:scale-95">
                                            + Kelas 7
                                        </button>
                                        <button type="button" @click="selectGrade('8')" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-sky-500/20 text-slate-200 hover:text-sky-300 text-xs font-bold transition-all border border-white/10 active:scale-95">
                                            + Kelas 8
                                        </button>
                                        <button type="button" @click="selectGrade('9')" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-sky-500/20 text-slate-200 hover:text-sky-300 text-xs font-bold transition-all border border-white/10 active:scale-95">
                                            + Kelas 9
                                        </button>
                                        <button type="button" @click="clearAll()" x-show="selectedClasses.length > 0" class="px-3 py-1.5 rounded-xl bg-rose-500/10 hover:bg-rose-500/20 text-rose-300 text-xs font-bold transition-all border border-rose-500/20 active:scale-95">
                                            Reset
                                        </button>
                                    </div>

                                    @if(isset($allClasses) && $classes->count() < $allClasses->count())
                                        <button type="button" @click="showAllSchoolClasses = !showAllSchoolClasses" class="text-xs font-bold text-sky-400 hover:text-sky-300 underline flex items-center gap-1">
                                            <span x-text="showAllSchoolClasses ? 'Tampilkan Hanya Kelas Saya' : 'Tampilkan Seluruh Kelas Sekolah'"></span>
                                        </button>
                                    @endif
                                </div>

                                {{-- GRID CHECKBOX KELAS --}}
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
                                    <template x-for="c in displayClasses" :key="c.id">
                                        <div @click="toggleClass(c.id)"
                                             class="flex items-center justify-between p-3.5 rounded-2xl border cursor-pointer transition-all duration-150 select-none group"
                                             :class="isClassSelected(c.id)
                                                ? 'bg-sky-500/20 border-sky-400 text-white shadow-md shadow-sky-500/10 font-black'
                                                : 'bg-slate-900/80 border-white/10 text-slate-300 hover:border-white/20 font-bold'">
                                            <input type="checkbox" name="class_ids[]" :value="c.id" :checked="isClassSelected(c.id)" class="sr-only">
                                            <div class="flex items-center gap-2.5 truncate">
                                                <i class="ph-fill text-lg transition-colors"
                                                   :class="isClassSelected(c.id) ? 'ph-chalkboard text-sky-400' : 'ph-chalkboard-simple text-slate-500 group-hover:text-slate-300'"></i>
                                                <span class="text-sm truncate" x-text="c.name"></span>
                                            </div>
                                            <div class="w-5 h-5 rounded-lg flex items-center justify-center text-xs transition-colors"
                                                 :class="isClassSelected(c.id) ? 'bg-sky-400 text-slate-950 font-black' : 'border border-slate-600 group-hover:border-slate-400'">
                                                <i class="ph-bold ph-check" x-show="isClassSelected(c.id)"></i>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                {{-- SUMMARY STATUS BAR --}}
                                <div class="p-4 rounded-2xl border transition-all"
                                     :class="selectedClasses.length > 0 ? 'bg-sky-500/10 border-sky-400/30 text-sky-200' : 'bg-rose-500/10 border-rose-500/20 text-rose-300'">
                                    <div class="flex items-center justify-between flex-wrap gap-2 text-xs">
                                        <div class="flex items-center gap-2">
                                            <i class="ph-bold text-base" :class="selectedClasses.length > 0 ? 'ph-check-circle text-sky-400' : 'ph-warning-circle text-rose-400'"></i>
                                            <span class="font-bold" x-show="selectedClasses.length > 0">
                                                <b class="text-white" x-text="selectedClasses.length"></b> Kelas Dipilih:
                                                <span class="text-sky-300 font-mono" x-text="getSelectedNames()"></span>
                                            </span>
                                            <span class="font-bold" x-show="selectedClasses.length === 0">
                                                Belum ada kelas yang dipilih. Silakan centang minimal 1 kelas di atas.
                                            </span>
                                        </div>
                                        <span class="text-[11px] text-slate-400 font-medium" x-show="selectedClasses.length > 0">
                                            Materi otomatis terbit di semua kelas ini tanpa perlu input ulang.
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- 2. KONTEN SATU KELAS --}}
                            <div x-show="targetType === 'class'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="pt-2">
                                <label class="block text-[10px] font-bold text-sky-400 uppercase tracking-widest mb-2 ml-1">Pilih Kelas Tujuan <span class="text-rose-400">*</span></label>
                                <div class="relative group">
                                    <select name="class_id" :required="targetType === 'class'" :disabled="targetType !== 'class'" class="w-full text-sm font-bold rounded-2xl border-white/10 bg-slate-900/80 focus:ring-sky-400/20 focus:border-sky-400 h-14 px-5 shadow-sm text-white transition-colors [color-scheme:dark] appearance-none cursor-pointer">
                                        <option value="" class="bg-slate-900 text-white">-- Pilih Satu Kelas --</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }} class="bg-slate-900 text-white">{{ $class->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-sky-400"><i class="ph-bold ph-caret-down text-lg"></i></div>
                                </div>
                            </div>

                            {{-- 3. KONTEN PER JENJANG --}}
                            <div x-show="targetType === 'grade'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-4 pt-2">
                                <div>
                                    <label class="block text-[10px] font-bold text-sky-400 uppercase tracking-widest mb-2 ml-1">Pilih Tingkat / Jenjang <span class="text-rose-400">*</span></label>
                                    <div class="relative group">
                                        <select name="target_grade" x-model="targetGrade" :required="targetType === 'grade'" :disabled="targetType !== 'grade'" class="w-full text-sm font-bold rounded-2xl border-white/10 bg-slate-900/80 focus:ring-sky-400/20 focus:border-sky-400 h-14 px-5 shadow-sm text-white transition-colors [color-scheme:dark] appearance-none cursor-pointer">
                                            <option value="7" class="bg-slate-900 text-white">Kelas 7 (Semua Kelas)</option>
                                            <option value="8" class="bg-slate-900 text-white">Kelas 8 (Semua Kelas)</option>
                                            <option value="9" class="bg-slate-900 text-white">Kelas 9 (Semua Kelas)</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-sky-400"><i class="ph-bold ph-caret-down text-lg"></i></div>
                                    </div>
                                </div>

                                <div class="p-4 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-200 flex items-start gap-3">
                                    <i class="ph-bold ph-info text-xl text-amber-400 shrink-0 mt-0.5"></i>
                                    <div class="text-xs space-y-1">
                                        <p class="font-bold text-white">Catatan Pilihan Per Jenjang:</p>
                                        <p class="text-slate-300 leading-relaxed">
                                            Materi ini otomatis akan diterbitkan ke <b class="text-amber-300">SELURUH KELAS</b> pada jenjang yang Anda pilih (misal jika memilih Kelas 7, seluruh kelas 7A, 7B, 7C, 7D, dst. akan menerima materi ini).
                                        </p>
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

                    // B2. Validasi Multi Kelas
                    const alpineData = window.Alpine ? Alpine.$data(this) : null;
                    if (alpineData && alpineData.targetType === 'multiple' && alpineData.selectedClasses.length === 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Pilih Minimal 1 Kelas',
                            text: 'Silakan pilih atau centang minimal satu kelas penerima materi.',
                            background: '#021124',
                            color: '#fff',
                            customClass: { popup: 'rounded-[2rem] font-sans border border-white/10 bg-[#021124] text-white shadow-2xl' }
                        });
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