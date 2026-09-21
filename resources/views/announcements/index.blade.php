<x-app-layout>
    {{-- Load SweetAlert, Animate.css & Quill.js Rich Text Editor --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

    <style>
        .ql-toolbar.ql-snow { border-color: rgba(255,255,255,0.1) !important; background: rgba(15, 23, 42, 0.8); border-top-left-radius: 1rem; border-top-right-radius: 1rem; }
        .ql-container.ql-snow { border-color: rgba(255,255,255,0.1) !important; background: rgba(15, 23, 42, 0.9); color: #fff !important; border-bottom-left-radius: 1rem; border-bottom-right-radius: 1rem; }
        .ql-snow .ql-stroke { stroke: #94a3b8 !important; }
        .ql-snow .ql-fill { fill: #94a3b8 !important; }
        .ql-snow .ql-picker { color: #94a3b8 !important; }
        .ql-snow .ql-picker-options { background-color: #0f172a !important; border-color: rgba(255,255,255,0.1) !important; color: #fff !important; }
        .ql-editor.ql-blank::before { color: #64748b !important; font-style: italic; }
    </style>

    <div class="py-6 sm:py-8 font-sans bg-[#020b18] text-slate-100 relative overflow-hidden min-h-screen"
         x-data="{
             editModalOpen: false,
             editData: {},
             announcementsMap: {{ \Illuminate\Support\Js::from($announcements->keyBy('id')) }},
             openEditModal(announce) {
                 this.editData = Object.assign({}, announce);
                 this.editData.expired_at_date = announce.expired_at ? announce.expired_at.substring(0, 10) : '';
                 this.editModalOpen = true;
                 this.$nextTick(() => {
                     if (!window.quillEdit && document.getElementById('quill-edit-editor')) {
                         window.quillEdit = new Quill('#quill-edit-editor', {
                             theme: 'snow',
                             modules: {
                                 toolbar: [
                                     [{ 'header': [1, 2, 3, false] }],
                                     ['bold', 'italic', 'underline', 'strike'],
                                     [{ 'align': [] }],
                                     [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                                     [{ 'color': [] }, { 'background': [] }],
                                     ['clean']
                                 ]
                             }
                         });
                     }
                     if (window.quillEdit) {
                         window.quillEdit.root.innerHTML = announce.content || '';
                     }
                 });
             }
         }">
        
        {{-- HERO SECTION --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 sm:mb-10 relative z-10">
            <x-hero-section
                badge="PORTAL KOMUNIKASI"
                badgeIcon="ph-fill ph-broadcast"
                showcaseIcon="ph-duotone ph-newspaper-clipping"
                showcaseTitle="Pusat Informasi"
                showcaseSubtitle="Gateway & Pengumuman">
                <x-slot:title>
                    <span class="block text-slate-100">Pusat Informasi</span>
                    <span class="block mt-1 sm:mt-1.5 text-transparent bg-clip-text bg-gradient-to-r from-[#56bbf1] via-sky-200 to-[#38bdf8]">
                        Agenda & Pengumuman
                    </span>
                </x-slot:title>
                <x-slot:description>
                    Kelola pengumuman website, jadwalkan agenda kegiatan, dan kirim notifikasi WhatsApp massal dalam satu dashboard terintegrasi.
                </x-slot:description>
                <x-slot:chips>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                        <i class="ph-bold ph-newspaper text-sky-400"></i> Broadcast Web
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                        <i class="ph-bold ph-whatsapp-logo text-emerald-400"></i> WA Blast
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                        <i class="ph-bold ph-calendar-check text-cyan-400"></i> Agenda Sekolah
                    </span>
                </x-slot:chips>
                <x-slot:showcaseStats>
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-2xl bg-slate-900/80 border border-slate-700/80 backdrop-blur-md">
                        <i class="ph-fill ph-newspaper-clipping text-[#56bbf1] text-sm"></i>
                        <span class="text-xs font-bold text-slate-300">Postingan:</span>
                        <span class="text-sm font-black text-white font-mono">{{ $announcements->count() }}</span>
                    </div>
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-2xl bg-slate-900/80 border border-emerald-500/30 backdrop-blur-md">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                        </span>
                        <span class="text-xs font-black text-emerald-400">WA Gateway Ready</span>
                    </div>
                </x-slot:showcaseStats>
            </x-hero-section>
        </div>

        {{-- Main Content --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- Flash Messages (SweetAlert Style Toast) --}}
            @if(session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: "{{ session('success') }}",
                            timer: 3000,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end',
                            background: '#021124',
                            color: '#fff',
                            iconColor: '#56bbf1',
                            customClass: { popup: 'rounded-2xl border border-white/10 shadow-xl bg-[#021124]' }
                        });
                    });
                </script>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                {{-- KOLOM KIRI (2/3): FORM INPUT --}}
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- CARD 1: PENGUMUMAN WEBSITE -->
                    <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] shadow-2xl border border-white/10 overflow-hidden relative group hover:border-[#56bbf1]/30 transition-all duration-300 backdrop-blur-xl">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-[#56bbf1] via-sky-400 to-blue-600"></div>
                        
                        <div class="p-6 sm:p-8 relative z-10">
                             <div class="flex items-center gap-4 mb-6 pb-4 border-b border-white/10">
                                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-[#56bbf1]/10 text-[#56bbf1] rounded-2xl flex items-center justify-center text-2xl sm:text-3xl shadow-sm border border-[#56bbf1]/20 shrink-0">
                                    <i class="ph-duotone ph-megaphone"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg sm:text-xl font-black text-white">Pengumuman Website</h3>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mt-1">Publikasi Informasi Sekolah</p>
                                </div>
                            </div>

                            <form action="{{ route('announcements.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                                @csrf
                                <div>
                                    <label class="block text-xs font-bold text-[#56bbf1] uppercase tracking-wider mb-2 ml-1">Judul Pengumuman</label>
                                    <div class="relative">
                                        <i class="ph-bold ph-text-t absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="text" name="title" class="w-full pl-11 rounded-2xl border-white/10 bg-slate-900/80 focus:bg-slate-900 focus:border-[#56bbf1] focus:ring-[#56bbf1] font-bold text-white py-3.5 transition-colors placeholder:text-slate-500 placeholder:font-medium" placeholder="Contoh: Jadwal Libur Awal Ramadhan" required>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-[#56bbf1] uppercase tracking-wider mb-2 ml-1">Isi Konten Pengumuman</label>
                                    <div class="bg-slate-900/80 rounded-2xl border border-white/10 overflow-hidden focus-within:border-[#56bbf1] transition-all shadow-sm">
                                        <div id="quill-editor" class="min-h-[180px] text-sm text-white font-medium"></div>
                                    </div>
                                    <input type="hidden" name="content" id="content-input" required>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-[#56bbf1] uppercase tracking-wider mb-2 ml-1">Gambar Banner / Pop-up (Opsional)</label>
                                    <input type="file" name="image" accept="image/*" class="w-full text-sm text-slate-400 file:mr-4 file:py-3 file:px-5 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-slate-800 file:text-[#56bbf1] hover:file:bg-slate-700 transition-all border border-white/10 rounded-2xl bg-slate-900/80 p-2">
                                     <p class="text-[10px] text-slate-400 font-bold mt-2 ml-1">Format: JPG/PNG/WEBP (Maksimal 10MB). Jika diisi, gambar ini akan tampil pada Pop-up & Papan Pengumuman.</p>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-[#56bbf1] uppercase tracking-wider mb-2 ml-1">Kategori / Urgensi</label>
                                        <div class="relative">
                                            <select name="category" class="w-full rounded-2xl border-white/10 bg-slate-900/80 focus:bg-slate-900 focus:border-[#56bbf1] focus:ring-[#56bbf1] font-bold text-white py-3.5 px-4 text-sm transition-colors cursor-pointer appearance-none">
                                                <option value="Umum" class="bg-slate-900 text-white">Umum (Biasa)</option>
                                                <option value="Akademik" class="bg-slate-900 text-white">Akademik</option>
                                                <option value="Kesiswaan" class="bg-slate-900 text-white">Kesiswaan</option>
                                                <option value="Penting" class="bg-slate-900 text-white">Penting / Urgent</option>
                                            </select>
                                            <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-[#56bbf1] uppercase tracking-wider mb-2 ml-1">Tanggal Kadaluarsa (Opsional)</label>
                                        <input type="date" name="expired_at" class="w-full rounded-2xl border-white/10 bg-slate-900/80 focus:bg-slate-900 focus:border-[#56bbf1] focus:ring-[#56bbf1] font-bold text-white py-3.5 transition-colors [color-scheme:dark]">
                                    </div>
                                </div>

                                <div class="flex items-center gap-3 p-4 rounded-2xl bg-slate-900/60 border border-white/10">
                                    <input type="checkbox" name="is_popup" value="1" id="is_popup" checked class="w-5 h-5 rounded-lg text-[#56bbf1] border-slate-700 bg-slate-900 focus:ring-[#56bbf1] cursor-pointer">
                                    <label for="is_popup" class="text-xs font-bold text-white cursor-pointer select-none">
                                        Tampilkan sebagai Pop-Up Modal saat pengunjung membuka website
                                        <span class="block text-[10px] text-slate-400 font-medium">Jika di centang, pengumuman akan muncul sebagai jendela pop-up di layar beranda.</span>
                                    </label>
                                </div>

                                <div class="flex justify-end pt-2">
                                    <button type="submit" class="w-full sm:w-auto py-3.5 px-8 bg-[#56bbf1] text-slate-950 font-bold rounded-xl hover:bg-sky-400 transition-all shadow-lg shadow-[#56bbf1]/20 text-sm flex items-center justify-center gap-2 transform active:scale-95">
                                        <i class="ph-bold ph-paper-plane-right text-lg"></i>
                                        Publikasikan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- CARD 2: AGENDA KEGIATAN -->
                    <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] shadow-2xl border border-white/10 overflow-hidden relative group hover:border-[#56bbf1]/30 transition-all duration-300 backdrop-blur-xl">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-sky-400 to-[#56bbf1]"></div>
                        
                        <div class="p-6 sm:p-8 relative z-10">
                            <div class="flex items-center gap-4 mb-6 pb-4 border-b border-white/10">
                                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-[#56bbf1]/10 text-[#56bbf1] rounded-2xl flex items-center justify-center text-2xl sm:text-3xl shadow-sm border border-[#56bbf1]/20 shrink-0">
                                    <i class="ph-duotone ph-calendar-plus"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg sm:text-xl font-black text-white">Agenda Kegiatan</h3>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mt-1">Jadwal Kalender Akademik</p>
                                </div>
                            </div>

                            <form action="{{ route('agendas.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-12 gap-5">
                                @csrf
                                <div class="md:col-span-5">
                                    <label class="block text-xs font-bold text-[#56bbf1] uppercase tracking-wider mb-2 ml-1">Nama Kegiatan</label>
                                    <input type="text" name="title" class="w-full rounded-2xl border-white/10 bg-slate-900/80 focus:bg-slate-900 focus:border-[#56bbf1] focus:ring-[#56bbf1] font-bold text-white py-3.5 placeholder:text-slate-500 placeholder:font-medium transition-all" placeholder="Contoh: UTS Semester Ganjil" required>
                                </div>
                                <div class="md:col-span-3">
                                    <label class="block text-xs font-bold text-[#56bbf1] uppercase tracking-wider mb-2 ml-1">Tanggal</label>
                                    <input type="date" name="event_date" class="w-full rounded-2xl border-white/10 bg-slate-900/80 focus:bg-slate-900 focus:border-[#56bbf1] focus:ring-[#56bbf1] font-bold text-white py-3.5 transition-all [color-scheme:dark]" required>
                                </div>
                                <div class="md:col-span-4">
                                    <label class="block text-xs font-bold text-[#56bbf1] uppercase tracking-wider mb-2 ml-1">Lokasi</label>
                                    <div class="flex gap-2">
                                        <input type="text" name="location" class="w-full rounded-2xl border-white/10 bg-slate-900/80 focus:bg-slate-900 focus:border-[#56bbf1] focus:ring-[#56bbf1] text-sm py-3.5 font-bold text-white placeholder:text-slate-500 transition-all" placeholder="Aula Sekolah">
                                        <button type="submit" class="p-3.5 bg-[#56bbf1] text-slate-950 rounded-2xl hover:bg-sky-400 transition shadow-lg shadow-[#56bbf1]/20 active:scale-95 flex-shrink-0 w-14 flex items-center justify-center font-bold">
                                            <i class="ph-bold ph-plus text-xl"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <!-- LIST AGENDA AKTIF -->
                            <div class="mt-8 pt-6 border-t border-white/10">
                                <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                    <i class="ph-fill ph-list-dashes text-[#56bbf1]"></i> Agenda Mendatang
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @forelse($agendas as $agenda)
                                        <div class="flex items-center justify-between p-4 rounded-2xl border border-white/10 bg-slate-900/60 hover:border-white/20 transition-all group relative overflow-hidden">
                                            <div class="flex items-center gap-4 relative z-10">
                                                <div class="text-center bg-[#56bbf1]/10 p-2.5 rounded-xl border border-[#56bbf1]/20 min-w-[60px] group-hover:bg-[#56bbf1] group-hover:border-[#56bbf1] transition-colors">
                                                    <span class="block text-[10px] text-[#56bbf1] font-black uppercase group-hover:text-slate-950">{{ $agenda->event_date->format('M') }}</span>
                                                    <span class="block text-2xl font-black text-white leading-none mt-0.5 group-hover:text-slate-950">{{ $agenda->event_date->format('d') }}</span>
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="text-sm font-bold text-white line-clamp-1 group-hover:text-[#56bbf1] transition-colors" title="{{ $agenda->title }}">{{ $agenda->title }}</p>
                                                    <div class="flex items-center gap-1.5 text-[11px] text-slate-400 mt-1 font-medium">
                                                        <i class="ph-fill ph-map-pin text-[#56bbf1]"></i>
                                                        <span class="truncate">{{ $agenda->location ?? 'Sekolah' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <form action="{{ route('agendas.destroy', $agenda->id) }}" method="POST" class="delete-agenda-form relative z-10">
                                                @csrf @method('DELETE')
                                                <button type="button" class="btn-delete-agenda w-9 h-9 rounded-xl flex items-center justify-center text-slate-400 hover:text-white hover:bg-rose-600 transition-all bg-slate-800 border border-white/10" title="Hapus Agenda">
                                                    <i class="ph-bold ph-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @empty
                                        <div class="col-span-full text-center py-8 border-2 border-dashed border-white/10 rounded-2xl bg-slate-900/40">
                                            <p class="text-xs text-slate-400 font-bold">Belum ada agenda mendatang.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CARD 3: BROADCAST WA -->
                    <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] shadow-2xl border border-white/10 overflow-hidden relative group hover:border-emerald-500/30 transition-all duration-300 backdrop-blur-xl">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-emerald-500 to-emerald-400"></div>

                        <div class="p-6 sm:p-8 relative z-10" x-data="{ target: 'class', message: '' }">
                            <div class="flex items-center gap-4 mb-6 pb-4 border-b border-white/10">
                                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-emerald-500/10 text-emerald-400 rounded-2xl flex items-center justify-center text-2xl sm:text-3xl shadow-sm border border-emerald-500/20 shrink-0">
                                    <i class="ph-duotone ph-whatsapp-logo"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg sm:text-xl font-black text-white">Broadcast WhatsApp</h3>
                                    <p class="text-xs font-bold text-emerald-400 uppercase tracking-wide mt-1">Kirim Pesan Massal</p>
                                </div>
                            </div>

                            <form action="{{ route('announcements.send') }}" method="POST" class="space-y-6" id="broadcastForm">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-xs font-bold text-emerald-400 uppercase tracking-wider mb-2 ml-1">Target Penerima</label>
                                        <div class="relative">
                                            <select name="target_type" x-model="target" class="w-full rounded-2xl border-white/10 bg-slate-900/80 focus:bg-slate-900 focus:border-emerald-500 focus:ring-emerald-500/30 text-sm py-3.5 px-4 font-bold text-white transition-colors cursor-pointer appearance-none">
                                                <option value="class" class="bg-slate-900 text-white">Per Kelas (Spesifik)</option>
                                                <option value="student" class="bg-slate-900 text-white">Per Siswa (Personal)</option>
                                                <option value="all" class="bg-slate-900 text-white">Semua Siswa (Broadcast Akbar)</option>
                                            </select>
                                            <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                        </div>
                                    </div>
                                    
                                    <div x-show="target === 'class'" x-transition>
                                        <label class="block text-xs font-bold text-emerald-400 uppercase tracking-wider mb-2 ml-1">Pilih Kelas</label>
                                        <div class="relative">
                                            <select name="class_id" class="w-full rounded-2xl border-white/10 bg-slate-900/80 focus:bg-slate-900 focus:border-emerald-500 focus:ring-emerald-500/30 text-sm py-3.5 px-4 font-bold text-white transition-colors appearance-none">
                                                @foreach($classes as $class)
                                                    <option value="{{ $class->id }}" class="bg-slate-900 text-white">{{ $class->name }}</option>
                                                @endforeach
                                            </select>
                                            <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                        </div>
                                    </div>

                                    <div x-show="target === 'student'" style="display: none;" x-transition>
                                        <label class="block text-xs font-bold text-emerald-400 uppercase tracking-wider mb-2 ml-1">Pilih Siswa</label>
                                        <div class="relative">
                                            <select name="student_id" class="w-full rounded-2xl border-white/10 bg-slate-900/80 focus:bg-slate-900 focus:border-emerald-500 focus:ring-emerald-500/30 text-sm py-3.5 px-4 font-bold text-white transition-colors appearance-none">
                                                <option value="" class="bg-slate-900 text-slate-400">-- Cari Nama Siswa --</option>
                                                @if(isset($students))
                                                    @foreach($students as $student)
                                                        <option value="{{ $student->id }}" class="bg-slate-900 text-white">{{ $student->name }} ({{ $student->schoolClass->name ?? '-' }})</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-emerald-400 uppercase tracking-wider mb-2 ml-1">Template Cepat</label>
                                    <div class="relative">
                                        <select @change="message = $event.target.value" class="w-full rounded-2xl border-white/10 bg-slate-900/80 focus:bg-slate-900 focus:border-emerald-500 focus:ring-emerald-500/30 text-sm py-3.5 px-4 font-bold text-white transition-colors cursor-pointer appearance-none">
                                            <option value="" class="bg-slate-900 text-slate-400">-- Pilih Template Pesan --</option>
                                            @foreach($templates as $key => $val)
                                                <option value="{{ $val }}" class="bg-slate-900 text-white">{{ $key }}</option>
                                            @endforeach
                                        </select>
                                        <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-emerald-400 uppercase tracking-wider mb-2 ml-1">Isi Pesan</label>
                                    <textarea name="message" x-model="message" rows="4" class="w-full rounded-2xl border-white/10 bg-slate-900/80 focus:bg-slate-900 focus:border-emerald-500 focus:ring-emerald-500/30 text-sm p-4 text-white placeholder-slate-500 font-medium transition-all" placeholder="Halo Bapak/Ibu, kami menginformasikan bahwa..." required></textarea>
                                </div>

                                <div class="flex justify-end pt-2">
                                    <button type="button" id="btn-broadcast" class="w-full sm:w-auto py-3.5 px-8 bg-emerald-500 text-slate-950 font-bold rounded-xl hover:bg-emerald-400 transition-all shadow-lg shadow-emerald-500/20 text-sm flex items-center justify-center gap-2 transform active:scale-95">
                                        <i class="ph-bold ph-paper-plane-tilt text-lg"></i>
                                        Kirim Pesan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>

                {{-- KOLOM KANAN (1/3): FEED PENGUMUMAN --}}
                <div class="lg:col-span-1">
                    <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] shadow-2xl border border-white/10 overflow-hidden flex flex-col lg:sticky lg:top-24 h-auto lg:h-[calc(100vh-6rem)] backdrop-blur-xl">
                        <div class="p-6 border-b border-white/10 flex items-center justify-between bg-white/[0.02] z-10">
                            <h3 class="text-lg font-black text-white flex items-center gap-2">
                                <i class="ph-duotone ph-newspaper-clipping text-[#56bbf1]"></i> Feed Publik
                            </h3>
                            <span class="bg-[#56bbf1]/10 text-[10px] font-black px-3 py-1.5 rounded-full text-[#56bbf1] border border-[#56bbf1]/20 uppercase tracking-wide shadow-sm">{{ $announcements->count() }} Post</span>
                        </div>
                        
                        <div class="p-0 overflow-y-auto flex-1 custom-scrollbar">
                            <div class="divide-y divide-white/5">
                                @forelse($announcements as $announce)
                                    <div class="p-6 hover:bg-white/[0.02] transition-all group relative">

                                        <!-- Header Post -->
                                        <div class="flex items-center justify-between mb-3">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-full bg-[#56bbf1]/20 text-[#56bbf1] flex items-center justify-center text-[11px] font-bold border border-[#56bbf1]/30">
                                                    {{ substr($announce->author->name ?? 'A', 0, 1) }}
                                                </div>
                                                <div>
                                                    <span class="block text-[11px] font-black text-white uppercase tracking-wide">{{ $announce->author->name ?? 'Admin' }}</span>
                                                    <span class="block text-[10px] text-slate-400 font-medium">{{ $announce->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Content -->
                                        <div class="pl-12">
                                             <h4 class="font-bold text-slate-100 mb-2 text-sm leading-snug group-hover:text-[#56bbf1] transition-colors">
                                                {{ $announce->title }}
                                                @if(!empty($announce->image))
                                                    <span class="inline-block ml-1 align-middle text-[9px] bg-[#56bbf1]/10 text-[#56bbf1] px-1.5 py-0.5 rounded uppercase font-black border border-[#56bbf1]/20"><i class="ph-bold ph-image"></i> Berfoto</span>
                                                @endif
                                            </h4>
                                             @if(!empty($announce->image))
                                                 <div class="mt-2 mb-3 rounded-xl overflow-hidden border border-white/10 max-h-40">
                                                     <img src="{{ asset('storage/' . $announce->image) }}" alt="Banner" class="w-full h-36 object-cover" onerror="this.parentElement.style.display='none'">
                                                 </div>
                                             @endif
                                             <p class="text-xs text-slate-300 line-clamp-3 leading-relaxed mb-4 font-medium">
                                                 {{ Str::limit(strip_tags($announce->content), 120) }}
                                             </p>
                                            
                                             <!-- Actions -->
                                             <div class="flex flex-wrap items-center justify-between gap-2">
                                                 <div class="flex flex-wrap items-center gap-2">
                                                     @php
                                                         $badgeStyle = match($announce->category ?? 'Umum') {
                                                             'Penting' => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
                                                             'Akademik' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                                                             'Kesiswaan' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                                             default => 'bg-sky-500/10 text-[#56bbf1] border-sky-500/20',
                                                         };
                                                     @endphp
                                                     <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded-md border {{ $badgeStyle }}">
                                                         {{ $announce->category ?? 'Umum' }}
                                                     </span>

                                                     @if($announce->is_popup)
                                                         <span class="text-[10px] font-bold text-purple-400 bg-purple-500/10 px-2 py-0.5 rounded-md border border-purple-500/20 flex items-center gap-1" title="Tampil Pop-Up Modal">
                                                             <i class="ph-bold ph-lightning"></i> Pop-Up
                                                         </span>
                                                     @endif

                                                     @if($announce->expired_at)
                                                         <span class="text-[10px] font-bold text-amber-400 bg-amber-500/10 px-2 py-0.5 rounded-md border border-amber-500/20 flex items-center gap-1" title="Kadaluarsa">
                                                             <i class="ph-bold ph-clock"></i> s/d {{ $announce->expired_at->format('d M Y') }}
                                                         </span>
                                                     @endif
                                                 </div>
                                                 
                                                 <div class="flex items-center gap-1">
                                                     <button type="button" @click="openEditModal(announcementsMap[{{ $announce->id }}])" class="w-8 h-8 flex items-center justify-center rounded-xl text-slate-400 hover:text-[#56bbf1] hover:bg-slate-800 transition-all opacity-100 lg:opacity-0 group-hover:opacity-100 bg-slate-900/60 border border-white/10" title="Edit Post">
                                                         <i class="ph-bold ph-pencil-simple text-sm"></i>
                                                     </button>
                                                     <form action="{{ route('announcements.destroy', $announce->id) }}" method="POST" class="delete-announce-form">
                                                         @csrf @method('DELETE')
                                                         <button type="button" class="btn-delete-announce w-8 h-8 flex items-center justify-center rounded-xl text-slate-400 hover:text-rose-400 hover:bg-slate-800 transition-all opacity-100 lg:opacity-0 group-hover:opacity-100 bg-slate-900/60 border border-white/10" title="Hapus Post">
                                                             <i class="ph-bold ph-trash"></i>
                                                         </button>
                                                     </form>
                                                 </div>
                                             </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-20 px-6">
                                        <div class="w-20 h-20 bg-slate-900/80 rounded-2xl flex items-center justify-center mx-auto mb-4 text-[#56bbf1] border border-white/10">
                                            <i class="ph-duotone ph-article text-4xl"></i>
                                        </div>
                                        <p class="text-sm font-bold text-white">Belum ada pengumuman.</p>
                                        <p class="text-xs text-slate-400 mt-1 font-medium">Buat pengumuman pertama Anda di form sebelah kiri.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <!-- EDIT ANNOUNCEMENT MODAL -->
        <div x-cloak x-show="editModalOpen" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" role="dialog" aria-modal="true">
            <div x-show="editModalOpen" x-transition.opacity class="fixed inset-0 bg-slate-950/80 backdrop-blur-md transition-opacity" @click="editModalOpen = false"></div>
            <div class="flex min-h-screen items-center justify-center p-4">
                <div x-show="editModalOpen" x-transition class="relative transform overflow-hidden rounded-[2.5rem] bg-[#021124] text-left shadow-2xl transition-all w-full max-w-2xl border border-white/10 p-6 sm:p-8 text-slate-100">
                    <div class="flex justify-between items-center mb-6 pb-4 border-b border-white/10">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-[#56bbf1]/10 text-[#56bbf1] rounded-xl flex items-center justify-center text-xl border border-[#56bbf1]/20">
                                <i class="ph-bold ph-pencil-simple"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-white">Edit Pengumuman Website</h3>
                                <p class="text-xs text-slate-400 font-medium">Perbarui konten dan pengaturan pengumuman</p>
                            </div>
                        </div>
                        <button @click="editModalOpen = false" class="w-9 h-9 rounded-full bg-slate-800 text-slate-400 hover:text-white hover:bg-slate-700 transition flex items-center justify-center"><i class="ph-bold ph-x text-lg"></i></button>
                    </div>

                    <form :action="'{{ url('/announcements') }}/' + editData.id" method="POST" enctype="multipart/form-data" class="space-y-5" id="editAnnouncementForm">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-xs font-bold text-[#56bbf1] uppercase tracking-wider mb-2 ml-1">Judul Pengumuman</label>
                            <input type="text" name="title" x-model="editData.title" class="w-full rounded-2xl border-white/10 bg-slate-900/80 focus:bg-slate-900 focus:border-[#56bbf1] focus:ring-[#56bbf1] font-bold text-white py-3.5 px-4 text-sm" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#56bbf1] uppercase tracking-wider mb-2 ml-1">Isi Konten Pengumuman</label>
                            <div class="bg-slate-900/80 rounded-2xl border border-white/10 overflow-hidden focus-within:border-[#56bbf1] transition-all shadow-sm">
                                <div id="quill-edit-editor" class="min-h-[160px] text-sm text-white font-medium"></div>
                            </div>
                            <input type="hidden" name="content" id="edit-content-input" required>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-[#56bbf1] uppercase tracking-wider mb-2 ml-1">Kategori / Urgensi</label>
                                <select name="category" x-model="editData.category" class="w-full rounded-2xl border-white/10 bg-slate-900/80 focus:bg-slate-900 focus:border-[#56bbf1] focus:ring-[#56bbf1] font-bold text-white py-3.5 px-4 text-sm">
                                    <option value="Umum" class="bg-slate-900 text-white">Umum (Biasa)</option>
                                    <option value="Akademik" class="bg-slate-900 text-white">Akademik</option>
                                    <option value="Kesiswaan" class="bg-slate-900 text-white">Kesiswaan</option>
                                    <option value="Penting" class="bg-slate-900 text-white">Penting / Urgent</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-[#56bbf1] uppercase tracking-wider mb-2 ml-1">Tanggal Kadaluarsa (Opsional)</label>
                                <input type="date" name="expired_at" x-model="editData.expired_at_date" class="w-full rounded-2xl border-white/10 bg-slate-900/80 focus:bg-slate-900 focus:border-[#56bbf1] focus:ring-[#56bbf1] font-bold text-white py-3.5 px-4 text-sm [color-scheme:dark]">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#56bbf1] uppercase tracking-wider mb-2 ml-1">Ganti Gambar Banner (Opsional)</label>
                            <template x-if="editData.image">
                                <div class="mb-3 flex items-center gap-3 p-2.5 bg-slate-900/60 rounded-xl border border-white/10">
                                    <img :src="'{{ asset('storage') }}/' + editData.image" class="w-12 h-12 rounded-lg object-cover">
                                    <span class="text-xs text-slate-400 font-medium">Gambar saat ini sudah terpasang. Pilih file baru jika ingin mengganti.</span>
                                </div>
                            </template>
                            <input type="file" name="image" accept="image/*" class="w-full text-sm text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-slate-800 file:text-[#56bbf1] hover:file:bg-slate-700 transition-all border border-white/10 rounded-2xl bg-slate-900/80 p-2">
                        </div>

                        <div class="flex items-center gap-3 p-4 rounded-2xl bg-slate-900/60 border border-white/10">
                            <input type="checkbox" name="is_popup" value="1" id="edit_is_popup" :checked="editData.is_popup" class="w-5 h-5 rounded-lg text-[#56bbf1] border-slate-700 bg-slate-900 focus:ring-[#56bbf1] cursor-pointer">
                            <label for="edit_is_popup" class="text-xs font-bold text-white cursor-pointer select-none">
                                Tampilkan sebagai Pop-Up Modal saat pengunjung membuka website
                            </label>
                        </div>

                        <div class="flex justify-end gap-3 pt-2">
                            <button type="button" @click="editModalOpen = false" class="py-3 px-6 bg-slate-800 text-slate-300 font-bold rounded-xl hover:bg-slate-700 hover:text-white text-sm">Batal</button>
                            <button type="submit" class="py-3 px-8 bg-[#56bbf1] text-slate-950 font-bold rounded-xl hover:bg-sky-400 transition-all shadow-lg shadow-[#56bbf1]/20 text-sm">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPTS UNTUK SWEETALERT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // 1. Handle Delete Agenda
            const deleteAgendaButtons = document.querySelectorAll('.btn-delete-agenda');
            deleteAgendaButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    const form = this.closest('.delete-agenda-form');
                    Swal.fire({
                        title: 'Hapus Agenda?',
                        text: "Jadwal kegiatan ini akan dihapus permanen.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e11d48',
                        cancelButtonColor: '#94a3b8',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        background: '#021124',
                        color: '#fff',
                        customClass: {
                            popup: 'rounded-[2rem] border border-white/10 shadow-2xl bg-[#021124]',
                            confirmButton: 'bg-rose-600 text-white rounded-xl px-6 py-2.5 font-bold mx-2 hover:bg-rose-500',
                            cancelButton: 'bg-slate-800 text-slate-300 rounded-xl px-6 py-2.5 font-bold mx-2 hover:bg-slate-700'
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        if (result.isConfirmed) form.submit();
                    });
                });
            });

            // 2. Handle Delete Pengumuman
            const deleteAnnounceButtons = document.querySelectorAll('.btn-delete-announce');
            deleteAnnounceButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    const form = this.closest('.delete-announce-form');
                    Swal.fire({
                        title: 'Hapus Postingan?',
                        text: "Pengumuman ini akan dihapus dari feed publik.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e11d48',
                        cancelButtonColor: '#94a3b8',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        background: '#021124',
                        color: '#fff',
                        customClass: {
                            popup: 'rounded-[2rem] border border-white/10 shadow-2xl bg-[#021124]',
                            confirmButton: 'bg-rose-600 text-white rounded-xl px-6 py-2.5 font-bold mx-2 hover:bg-rose-500',
                            cancelButton: 'bg-slate-800 text-slate-300 rounded-xl px-6 py-2.5 font-bold mx-2 hover:bg-slate-700'
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        if (result.isConfirmed) form.submit();
                    });
                });
            });

            // 3. Handle Broadcast Confirm
            const btnBroadcast = document.getElementById('btn-broadcast');
            if(btnBroadcast){
                btnBroadcast.addEventListener('click', function() {
                    const form = document.getElementById('broadcastForm');
                    Swal.fire({
                        title: 'Kirim Broadcast?',
                        text: "Pesan WhatsApp akan dikirim ke antrian gateway.",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Kirim Sekarang',
                        cancelButtonText: 'Batal',
                        background: '#021124',
                        color: '#fff',
                        customClass: {
                            popup: 'rounded-[2rem] border border-white/10 shadow-2xl bg-[#021124]',
                            confirmButton: 'bg-emerald-500 text-slate-950 rounded-xl px-6 py-2.5 font-bold mx-2 hover:bg-emerald-400',
                            cancelButton: 'bg-slate-800 text-slate-300 rounded-xl px-6 py-2.5 font-bold mx-2 hover:bg-slate-700'
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        if (result.isConfirmed) form.submit();
                    });
                });
            }

            // --- INITIALIZE QUILL RICH TEXT EDITOR ---
            if (document.getElementById('quill-editor')) {
                var quill = new Quill('#quill-editor', {
                    theme: 'snow',
                    placeholder: 'Tulis detail pengumuman di sini (Anda dapat mengatur Bold, Miring, Garis Bawah, Rata Kiri/Tengah/Kanan/Kiri-Kanan, Penomoran, Judul, dll)...',
                    modules: {
                        toolbar: [
                            [{ 'header': [1, 2, 3, false] }],
                            ['bold', 'italic', 'underline', 'strike'],
                            [{ 'align': [] }],
                            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                            [{ 'color': [] }, { 'background': [] }],
                            ['clean']
                        ]
                    }
                });

                var announceForm = document.querySelector('form[action="{{ route('announcements.store') }}"]');
                if (announceForm) {
                    announceForm.addEventListener('submit', function() {
                        var html = quill.root.innerHTML;
                        if (quill.getText().trim().length === 0 && !html.includes('<img')) {
                            document.getElementById('content-input').value = '';
                        } else {
                            document.getElementById('content-input').value = html;
                        }
                    });
                }
            }

            var editForm = document.getElementById('editAnnouncementForm');
            if (editForm) {
                editForm.addEventListener('submit', function() {
                    if (window.quillEdit) {
                        document.getElementById('edit-content-input').value = window.quillEdit.root.innerHTML;
                    }
                });
            }
        });
    </script>
</x-app-layout>