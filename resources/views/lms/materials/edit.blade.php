<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Edit Materi') }}
        </h2>
    </x-slot>

    {{-- CUSTOM STYLES --}}
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>
    {{-- CSS QUILL DARK THEME --}}
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        .ql-toolbar.ql-snow { border: 1px solid rgba(255, 255, 255, 0.1) !important; background-color: rgba(15, 23, 42, 0.9); border-top-left-radius: 1rem; border-top-right-radius: 1rem; }
        .ql-container.ql-snow { border: 1px solid rgba(255, 255, 255, 0.1) !important; border-top: none !important; background-color: rgba(15, 23, 42, 0.6); border-bottom-left-radius: 1rem; border-bottom-right-radius: 1rem; color: #f8fafc; }
        .ql-editor { font-size: 0.95rem; line-height: 1.7; padding: 1.25rem; }
        .ql-snow .ql-stroke { stroke: #94a3b8 !important; }
        .ql-snow .ql-fill { fill: #94a3b8 !important; }
        .ql-snow .ql-picker { color: #94a3b8 !important; }
        .ql-snow .ql-picker-options { background-color: #0f172a !important; border-color: rgba(255, 255, 255, 0.1) !important; }
    </style>

    <div class="py-8 sm:py-10 font-sans text-slate-100 bg-[#020b18] min-h-screen relative overflow-hidden pb-20">
        
        {{-- Efek Latar Belakang Halus --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-gradient-to-b from-[#0d52a1]/20 via-[#031d3d]/10 to-transparent opacity-30 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- HERO HEADER --}}
            <x-hero-section
                badge="LMS Pembelajaran"
                badgeIcon="ph-book-open"
                title="Edit Materi"
                titleHighlight="Pembaruan Bahan Ajar"
                description="Perbarui informasi, file modul, atau lampiran materi pembelajaran untuk siswa."
                :chips="[
                    ['icon' => 'ph-note-pencil', 'label' => 'Revisi Materi'],
                    ['icon' => 'ph-folder-notch-open', 'label' => 'Bab Pembelajaran'],
                    ['icon' => 'ph-check-circle', 'label' => 'Auto-Update']
                ]"
                heroIcon="ph-books"
                showcaseValue="Edit"
                showcaseLabel="Revisi Materi"
                showcaseSubtitle="Pembaruan Bahan Ajar"
                statusOrb="Mode Edit"
                statusColor="amber"
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

            {{-- INFO ALUR BELAJAR (PRO-TIP UNTUK GURU) --}}
            <div class="animate-enter mb-8 bg-sky-500/10 border border-sky-400/20 p-5 rounded-[2rem] flex flex-col md:flex-row items-start md:items-center gap-4 shadow-sm backdrop-blur-md" style="animation-delay: 50ms">
                <div class="w-12 h-12 bg-sky-500/20 text-sky-300 rounded-2xl shrink-0 flex items-center justify-center text-2xl border border-sky-400/30">
                    <i class="ph-duotone ph-info"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-black text-sky-200 mb-1">Pro-Tip: Urutan Belajar Siswa</h3>
                    <p class="text-xs font-medium text-slate-300 leading-relaxed">
                        Materi yang sedang Anda ubah ini merupakan bagian dari <b class="text-sky-300">Alur Belajar Siswa (Learning Player)</b>. Pastikan urutan materinya tetap sesuai dengan yang diharapkan siswa.
                    </p>
                </div>
            </div>

            {{-- ERROR BLOCK --}}
            @if ($errors->any())
                <div class="mb-8 bg-rose-500/10 border border-rose-500/30 p-5 rounded-[1.5rem] flex items-start gap-4 shadow-sm backdrop-blur-md animate-enter">
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

            @php
                $oldNewAttachments = old('new_attachments', []);
                foreach($oldNewAttachments as $k => &$att) {
                    if(!isset($att['id'])) $att['id'] = time() + $k;
                }
            @endphp
            
            {{-- FORM CARD --}}
            <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] shadow-2xl border border-white/10 overflow-hidden text-slate-100 backdrop-blur-xl animate-enter" style="animation-delay: 100ms">
                <form action="{{ route('lms.materials.update', $material->id) }}" method="POST" enctype="multipart/form-data" 
                      x-data="{ attachments: {{ json_encode($oldNewAttachments) }} }"
                      id="updateForm">
                    @csrf
                    @method('PUT')

                    <div class="p-6 md:p-10 space-y-8">
                        
                        <!-- BAGIAN 1: INFORMASI UTAMA -->
                        <div>
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-12 h-12 rounded-xl bg-sky-500/20 text-sky-400 border border-sky-400/30 flex items-center justify-center text-2xl shadow-sm"><i class="ph-bold ph-pencil-simple"></i></div>
                                <h3 class="text-xl font-black text-white">Informasi Dasar</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="col-span-1 md:col-span-2">
                                    <label class="block text-[10px] font-bold text-sky-400 uppercase tracking-widest mb-2 ml-1">Judul Materi <span class="text-rose-400">*</span></label>
                                    <input type="text" name="title" value="{{ old('title', $material->title) }}" required 
                                           class="w-full rounded-2xl border-white/10 bg-slate-900/80 font-black text-white focus:ring-sky-400/20 focus:border-sky-400 focus:bg-slate-900 h-14 px-5 transition-colors shadow-sm">
                                </div>
                                
                                <div>
                                    <label class="block text-[10px] font-bold text-sky-400 uppercase tracking-widest mb-2 ml-1">Mata Pelajaran <span class="text-rose-400">*</span></label>
                                    <div class="relative group">
                                        <select name="subject_id" required class="w-full rounded-2xl border-white/10 bg-slate-900/80 font-bold text-white focus:ring-sky-400/20 focus:border-sky-400 h-14 px-5 appearance-none cursor-pointer focus:bg-slate-900 transition-colors shadow-sm [color-scheme:dark]">
                                            @foreach($subjects as $subject)
                                                <option value="{{ $subject->id }}" {{ old('subject_id', $material->subject_id) == $subject->id ? 'selected' : '' }} class="bg-slate-900 text-white">{{ $subject->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-sky-400 transition-colors"><i class="ph-bold ph-caret-down text-lg"></i></div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-sky-400 uppercase tracking-widest mb-2 ml-1">Pokok Bahasan / Bab <span class="text-rose-400">*</span></label>
                                    <div class="relative group">
                                        @php
                                            $topics = \App\Models\Topic::where('subject_id', $material->subject_id)->get();
                                        @endphp
                                        <select name="topic_id" required class="w-full rounded-2xl border-white/10 bg-slate-900/80 font-bold text-white focus:ring-sky-400/20 focus:border-sky-400 h-14 px-5 appearance-none cursor-pointer focus:bg-slate-900 transition-colors shadow-sm [color-scheme:dark]">
                                            @foreach($topics as $topic)
                                                <option value="{{ $topic->id }}" {{ old('topic_id', $material->topic_id) == $topic->id ? 'selected' : '' }} class="bg-slate-900 text-white">{{ $topic->title }}</option>
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-sky-400 transition-colors"><i class="ph-bold ph-caret-down text-lg"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="h-px bg-white/10"></div>

                       <!-- BAGIAN 2: DESKRIPSI -->
                        <div>
                            <label class="block text-[10px] font-bold text-sky-400 uppercase tracking-widest mb-2 ml-1">
                                Pengantar & Penjelasan Singkat
                            </label>
                            
                            <input type="hidden" name="resume" id="resume-input" value="{{ old('resume', $material->resume) }}">
                            
                            <div class="rounded-2xl border border-white/10 overflow-hidden shadow-sm bg-slate-900/80">
                                <div id="quill-editor" class="h-64 sm:h-80 text-slate-100 font-medium text-base">
                                    {!! old('resume', $material->resume) !!}
                                </div>
                            </div>
                        </div>

                        <div class="h-px bg-white/10"></div>

                        <!-- BAGIAN 3: LAMPIRAN (EXISTING & NEW) -->
                        <div class="bg-slate-900/60 rounded-[2rem] border border-white/10 p-6 md:p-8">
                            <div class="flex items-center gap-2 mb-6">
                                <i class="ph-fill ph-paperclip text-sky-400 text-xl"></i>
                                <h3 class="text-lg font-black text-white">Kelola Lampiran</h3>
                            </div>

                            <!-- Lampiran Lama -->
                            @if($material->attachments->count() > 0)
                                <div class="space-y-4 mb-8">
                                    <p class="text-[10px] font-bold text-sky-400 uppercase tracking-widest">Lampiran Tersimpan:</p>
                                    @foreach($material->attachments as $att)
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 bg-slate-900/90 border border-white/10 rounded-2xl shadow-sm gap-4 transition-all hover:border-sky-400/40">
                                            <div class="flex items-center gap-4 overflow-hidden">
                                                <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 bg-sky-500/20 text-sky-300 border border-sky-400/30">
                                                    <i class="ph-bold text-xl {{ $att->file_type == 'file' ? 'ph-file-pdf' : ($att->file_type == 'video' ? 'ph-youtube-logo' : 'ph-link') }}"></i>
                                                </div>
                                                <div class="truncate">
                                                    <p class="text-sm font-black text-white truncate leading-tight">{{ $att->file_name ?? 'File Lampiran' }}</p>
                                                    <a href="{{ $att->file_type == 'file' ? asset('storage/'.$att->file_path) : $att->file_path }}" target="_blank" class="text-[10px] text-sky-400 hover:underline font-bold uppercase tracking-wider mt-1 inline-flex items-center gap-1"><i class="ph-bold ph-arrow-up-right"></i> Lihat {{ $att->file_type == 'file' ? 'File' : 'Link' }}</a>
                                                </div>
                                            </div>
                                            <label class="flex items-center gap-2.5 cursor-pointer bg-slate-800 px-4 py-2.5 rounded-xl border border-white/10 hover:bg-rose-500/20 hover:border-rose-500/30 transition-colors group shrink-0 shadow-sm w-full sm:w-auto justify-center">
                                                <input type="checkbox" name="delete_attachments[]" value="{{ $att->id }}" class="rounded text-rose-500 focus:ring-rose-500 border-white/20 bg-slate-900 w-4 h-4 cursor-pointer">
                                                <span class="text-xs font-bold text-slate-300 group-hover:text-rose-300 transition-colors">Hapus</span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="h-px bg-white/10 mb-6"></div>

                            <!-- Lampiran Baru -->
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-5 gap-4">
                                <div>
                                    <p class="text-[10px] font-bold text-sky-400 uppercase tracking-widest">Tambah Lampiran Baru:</p>
                                    <p class="text-[11px] font-bold text-slate-400 mt-1">Upload PDF/Word baru atau tautkan YouTube.</p>
                                </div>
                                <button type="button" @click="attachments.push({id: Date.now(), type: 'file', link: '', name: ''})" 
                                        class="w-full sm:w-auto text-xs bg-slate-800 border border-white/10 text-sky-300 px-5 py-3 sm:py-2.5 rounded-xl font-bold hover:bg-slate-700 hover:text-white transition-colors shadow-sm flex items-center justify-center gap-2 active:scale-95">
                                    <i class="ph-bold ph-plus"></i> Tambah Baris Baru
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
                                                <input x-show="att.type === 'file'" type="file" :name="'new_attachments['+index+'][file]'" class="block w-full text-sm text-slate-400 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-800 file:text-sky-300 h-12 border border-white/10 rounded-xl bg-slate-900 cursor-pointer hover:file:bg-slate-700 shadow-sm transition-colors" :required="att.type === 'file'" :disabled="att.type !== 'file'">
                                                <input x-show="att.type !== 'file'" type="url" :name="'new_attachments['+index+'][link]'" x-model="att.link" class="w-full text-sm font-medium rounded-xl border-white/10 h-12 placeholder:text-slate-500 focus:ring-sky-400/20 focus:border-sky-400 text-white px-4 shadow-sm bg-slate-800 focus:bg-slate-900 transition-colors" placeholder="https://..." :required="att.type !== 'file'">
                                            </div>
                                            
                                            <div class="md:col-span-4">
                                                <input type="text" :name="'new_attachments['+index+'][name]'" x-model="att.name" class="w-full text-sm font-medium rounded-xl border-white/10 h-12 placeholder:text-slate-500 focus:ring-sky-400/20 focus:border-sky-400 text-white px-4 shadow-sm bg-slate-800 focus:bg-slate-900 transition-colors" placeholder="Beri Label (Opsional)">
                                            </div>
                                            
                                        </div>
                                        <button type="button" @click="attachments = attachments.filter(i => i.id !== att.id)" class="absolute top-3 right-3 md:static md:mt-1 w-10 h-10 flex items-center justify-center rounded-xl bg-rose-500/20 text-rose-300 border border-rose-500/30 hover:bg-rose-500/30 transition-colors shadow-sm" title="Hapus Baris">
                                            <i class="ph-bold ph-trash text-lg"></i>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>

                    </div>

                    <!-- FOOTER ACTIONS -->
                    <div class="bg-slate-900/80 px-6 md:px-10 py-6 border-t border-white/10 flex flex-col md:flex-row justify-end gap-4">
                        <a href="{{ route('lms.materials.index') }}" class="w-full sm:w-auto px-8 py-4 bg-slate-800 border border-white/10 text-slate-300 font-bold rounded-2xl hover:bg-slate-700 hover:text-white transition-colors text-center text-sm btn-cancel-confirm active:scale-95 shadow-sm">Batal</a>
                        
                        <button type="submit" class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-sky-400 to-blue-600 text-slate-950 font-bold rounded-2xl shadow-lg shadow-sky-500/20 hover:from-sky-300 hover:to-blue-500 transition-all flex items-center justify-center gap-2 text-sm active:scale-95">
                            <i class="ph-bold ph-check-circle text-lg"></i>
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            const cancelButtons = document.querySelectorAll('.btn-cancel-confirm');
            cancelButtons.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const href = this.getAttribute('href');

                    Swal.fire({
                        title: 'Batalkan Edit?',
                        text: "Perubahan tidak akan disimpan.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#f43f5e', 
                        cancelButtonColor: '#475569', 
                        confirmButtonText: 'Ya, Batalkan',
                        cancelButtonText: 'Lanjut Mengedit',
                        background: '#021124',
                        color: '#fff',
                        customClass: {
                            popup: 'rounded-[2rem] font-sans border border-white/10 bg-[#021124] text-white shadow-2xl',
                            confirmButton: 'rounded-xl px-5 py-2.5 font-bold shadow-sm',
                            cancelButton: 'rounded-xl px-5 py-2.5 font-bold shadow-sm hover:bg-slate-800 text-slate-300'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = href;
                        }
                    });
                });
            });

            const updateForm = document.getElementById('updateForm');
            if(updateForm) {
                updateForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    if (!this.checkValidity()) {
                        this.reportValidity();
                        return;
                    }
                    Swal.fire({
                        title: 'Menyimpan Perubahan...',
                        html: 'Mohon tunggu sebentar.',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        background: '#021124',
                        color: '#fff',
                        didOpen: () => { Swal.showLoading(); },
                        customClass: { popup: 'rounded-[2rem] font-sans border border-white/10 bg-[#021124] text-white shadow-2xl', title: 'text-xl font-black text-white' }
                    });
                    setTimeout(() => { this.submit(); }, 300);
                });
            }
        });
    </script>

    <!-- Script Quill JS -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (document.getElementById('quill-editor')) {
                var quill = new Quill('#quill-editor', {
                    theme: 'snow',
                    placeholder: 'Ketik materi, penjelasan, atau sisipkan gambar di sini...',
                    modules: {
                        toolbar: [
                            [{ 'header': [1, 2, 3, false] }],
                            ['bold', 'italic', 'underline'],
                            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                            ['link', 'image', 'video'],
                            ['clean']
                        ]
                    }
                });

                var form = document.getElementById('createForm') || document.getElementById('updateForm');
                if(form) {
                    form.addEventListener('submit', function() {
                        var htmlContent = quill.root.innerHTML;
                        if (htmlContent === '<p><br></p>') {
                            htmlContent = '';
                        }
                        document.getElementById('resume-input').value = htmlContent;
                    });
                }
            }
        });
    </script>
    @endpush
</x-app-layout>