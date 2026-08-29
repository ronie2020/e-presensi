<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-elevate-dark leading-tight">
            {{ __('Edit Tugas') }}
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
                        <h1 class="text-3xl md:text-4xl font-black mb-2 tracking-tight">Edit Tugas</h1>
                        <p class="text-elevate-dark/80 text-sm font-semibold">Perbarui detail, deadline, atau instruksi tugas yang sudah diterbitkan.</p>
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

            {{-- INFO TUGAS PAKET / BULK --}}
            @if($isBulk ?? false)
                <div class="animate-enter mb-8 bg-amber-50 border border-amber-200 p-5 rounded-[2rem] flex flex-col md:flex-row items-start md:items-center gap-4 shadow-sm">
                    <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-2xl shrink-0 flex items-center justify-center text-2xl">
                        <i class="ph-duotone ph-users-three"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm font-black text-amber-900 mb-1">Tugas Ini Diterbitkan ke Beberapa Kelas</h3>
                        <p class="text-xs font-medium text-amber-700 leading-relaxed">
                            Perubahan yang Anda simpan di sini akan berlaku untuk <b>semua kelas</b> yang menerima tugas dengan judul dan waktu terbit yang sama.
                        </p>
                    </div>
                </div>
            @endif

            {{-- INFO ALUR BELAJAR --}}
            <div class="animate-enter mb-8 bg-blue-50 border border-blue-200 p-5 rounded-[2rem] flex flex-col md:flex-row items-start md:items-center gap-4 shadow-sm">
                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-2xl shrink-0 flex items-center justify-center text-2xl">
                    <i class="ph-duotone ph-book-open"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-black text-blue-900 mb-1">Struktur Bab Pembelajaran</h3>
                    <p class="text-xs font-medium text-blue-700 leading-relaxed">
                        Tugas ini terletak di dalam <b>Bab/Pokok Bahasan</b> yang Anda pilih. Siswa hanya akan melihat tugas ini ketika mereka sedang mempelajari Bab tersebut di Learning Player.
                    </p>
                </div>
            </div>

            {{-- FORM CARD --}}
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden">
                <form action="{{ route('lms.assignments.update', $assignment) }}" method="POST" id="editAssignmentForm"
                      x-data="{
                          selectedSubject: '{{ old('subject_id', $assignment->subject_id) }}',
                          topics: [],
                          selectedTopic: '{{ old('topic_id', $assignment->topic_id) }}',

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
                      x-init="fetchTopics()">
                    @csrf
                    @method('PUT')

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
                                    <input type="text" name="title" value="{{ old('title', $assignment->title) }}" required class="w-full rounded-2xl border-slate-200 bg-elevate-soft font-black text-elevate-dark focus:bg-white focus:ring-elevate-accent/30 focus:border-elevate-accent h-14 px-5 placeholder:font-bold placeholder:text-slate-400 transition-colors shadow-sm" placeholder="Contoh: Ulangan Harian Bab 1">
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
                                                <option :value="topic.id" x-text="topic.title" :selected="topic.id == selectedTopic"></option>
                                            </template>
                                        </select>
                                        <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-slate-500 group-focus-within:text-elevate-primary"><i class="ph-bold ph-caret-down text-lg"></i></div>
                                    </div>
                                    <p class="text-[10px] font-bold text-slate-400 mt-2 ml-1" x-show="!selectedSubject">Silakan pilih Mata Pelajaran terlebih dahulu.</p>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">Deadline <span class="text-[#D13438]">*</span></label>
                                    <input type="datetime-local" name="deadline" value="{{ old('deadline', \Carbon\Carbon::parse($assignment->deadline)->format('Y-m-d\TH:i')) }}" required class="w-full rounded-2xl border-slate-200 bg-elevate-soft font-bold text-elevate-dark focus:bg-white focus:ring-elevate-accent/30 focus:border-elevate-accent h-14 px-5 transition-colors shadow-sm">
                                </div>

                                <div class="col-span-2 md:col-span-1 flex items-center mt-4">
                                    <label class="inline-flex items-center cursor-pointer group">
                                        <div class="relative">
                                            <input type="checkbox" name="allow_late_submission" class="sr-only peer" {{ old('allow_late_submission', $assignment->allow_late_submission) ? 'checked' : '' }}>
                                            <div class="w-12 h-7 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-elevate-primary shadow-inner"></div>
                                        </div>
                                        <span class="ml-3 text-sm font-bold text-slate-500 group-hover:text-elevate-primary transition-colors">Izinkan pengumpulan terlambat</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="h-px bg-slate-100"></div>

                        <!-- 2. JENIS TUGAS (READ-ONLY, TIDAK BISA DIUBAH) -->
                        <div>
                            <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-4 ml-1">Jenis Penugasan</label>

                            <div class="inline-flex items-center gap-4 p-5 rounded-2xl border-2 border-slate-100 bg-elevate-soft/50">
                                <div class="w-14 h-14 rounded-2xl bg-elevate-primary text-white flex items-center justify-center text-2xl shadow-sm">
                                    @switch($assignment->assignment_type)
                                        @case('quiz')
                                            <i class="ph-duotone ph-brain"></i>
                                            @break
                                        @case('link')
                                            <i class="ph-duotone ph-link"></i>
                                            @break
                                        @case('interactive_video')
                                            <i class="ph-duotone ph-youtube-logo"></i>
                                            @break
                                        @default
                                            <i class="ph-duotone ph-upload-simple"></i>
                                    @endswitch
                                </div>
                                <div>
                                    <span class="block font-black text-elevate-dark text-sm sm:text-base">
                                        @switch($assignment->assignment_type)
                                            @case('quiz') Kuis Online @break
                                            @case('link') Link Luar @break
                                            @case('interactive_video') Video Interaktif @break
                                            @default Upload File
                                        @endswitch
                                    </span>
                                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Tidak dapat diubah setelah tugas dibuat</span>
                                </div>
                            </div>
                        </div>

                        <!-- 3. KONTEN -->
                        <div class="bg-elevate-soft/30 rounded-[2rem] p-6 md:p-8 border border-slate-100 space-y-6">

                            <div>
                                <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">Instruksi / Deskripsi Tugas <span class="text-[#D13438]">*</span></label>
                                <input type="hidden" name="description" id="desc-input" value="{{ old('description', $assignment->description) }}">
                                <div class="rounded-2xl border border-slate-200 overflow-hidden shadow-sm bg-white">
                                    <div id="quill-edit" class="text-elevate-dark font-medium">{!! old('description', $assignment->description) !!}</div>
                                </div>
                            </div>

                            @if($assignment->assignment_type === 'link')
                                <div>
                                    <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">URL Link Tugas <span class="text-[#D13438]">*</span></label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-elevate-primary"><i class="ph-bold ph-link text-lg"></i></div>
                                        <input type="url" name="link_url" value="{{ old('link_url', $assignment->link_url) }}" required
                                               class="w-full rounded-2xl border-slate-200 bg-white pl-12 font-bold text-elevate-primary focus:ring-elevate-accent/30 focus:border-elevate-accent h-14 transition-colors shadow-sm"
                                               placeholder="https://...">
                                    </div>
                                </div>
                            @endif

                            @if($assignment->assignment_type === 'interactive_video')
                                <div>
                                    <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">URL YouTube Video <span class="text-[#D13438]">*</span></label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-red-500"><i class="ph-bold ph-youtube-logo text-xl"></i></div>
                                        <input type="url" name="link_url" value="{{ old('link_url', $assignment->link_url) }}" required
                                               class="w-full rounded-2xl border-slate-200 bg-white pl-12 font-bold text-elevate-dark focus:ring-red-500/30 focus:border-red-500 h-14 transition-colors shadow-sm"
                                               placeholder="Contoh: https://www.youtube.com/watch?v=xxxxxxx">
                                    </div>
                                    <p class="text-xs font-semibold text-slate-500 mt-2 ml-1">Untuk mengubah titik-titik kuis di dalam video, fitur edit soal belum tersedia di form ini — hubungi admin/developer bila diperlukan.</p>
                                </div>
                            @endif

                            @if($assignment->assignment_type === 'quiz')
                                <div class="max-w-xs">
                                    <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">Durasi (Menit) <span class="text-[#D13438]">*</span></label>
                                    <div class="relative group">
                                        <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $assignment->duration_minutes) }}" required min="1"
                                               class="w-full rounded-2xl border-slate-200 bg-white font-black text-elevate-dark focus:ring-purple-500/30 focus:border-purple-500 h-14 pl-5 pr-12 transition-colors shadow-sm">
                                        <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-slate-400 text-[10px] font-black tracking-widest">MIN</div>
                                    </div>
                                    <p class="text-xs font-semibold text-slate-500 mt-2 ml-1">Untuk mengubah soal kuis, fitur edit soal belum tersedia di form ini.</p>
                                </div>
                            @endif
                        </div>

                        <!-- 4. TARGET PENERIMA (READ-ONLY) -->
                        <div class="bg-elevate-soft/50 p-6 md:p-8 rounded-[2rem] border border-slate-100">
                            <label class="block text-xs font-black text-elevate-primary uppercase tracking-widest mb-3 flex items-center gap-2">
                                <i class="ph-fill ph-users-three text-lg"></i> Target Penerima
                            </label>
                            <p class="text-sm font-bold text-elevate-dark">
                                @if($isBulk ?? false)
                                    Beberapa kelas (satu paket penerbitan)
                                @else
                                    {{ $assignment->schoolClass->name ?? '-' }}
                                @endif
                            </p>
                            <p class="text-xs font-semibold text-slate-400 mt-1">Target kelas tidak dapat diubah dari form edit. Hapus dan buat ulang tugas jika ingin mengganti target.</p>
                        </div>
                    </div>

                    <!-- FOOTER ACTIONS -->
                    <div class="bg-elevate-soft/30 px-6 py-6 md:px-10 md:py-8 flex flex-col sm:flex-row justify-end gap-4 border-t border-slate-100">
                        <a href="{{ route('lms.assignments.index') }}" class="w-full sm:w-auto px-8 py-4 bg-white border-2 border-slate-200 text-elevate-dark font-bold rounded-2xl hover:bg-elevate-soft transition-colors text-center text-sm shadow-sm btn-cancel-confirm active:scale-95">Batal</a>
                        <button type="submit" class="w-full sm:w-auto px-8 py-4 bg-elevate-dark text-white font-bold rounded-2xl shadow-lg shadow-elevate-dark/30 hover:bg-elevate-primary transition-all flex items-center justify-center gap-2 text-sm border border-transparent active:scale-95">
                            <i class="ph-bold ph-floppy-disk text-lg"></i> <span>Simpan Perubahan</span>
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
            var quillEdit = null;
            if (document.querySelector('#quill-edit')) {
                quillEdit = new Quill('#quill-edit', {
                    theme: 'snow',
                    placeholder: 'Edit instruksi tugas...',
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
            const form = document.getElementById('editAssignmentForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // A. Sync data Quill ke input hidden
                    if (quillEdit) {
                        var html = quillEdit.root.innerHTML;
                        document.getElementById('desc-input').value = html === '<p><br></p>' ? '' : html;
                    }

                    // B. Validasi Bawaan Browser
                    if (!this.checkValidity()) {
                        this.reportValidity();
                        return;
                    }

                    // C. Munculkan Loading & Submit Aktual
                    Swal.fire({
                        title: 'Menyimpan Perubahan...',
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