<x-app-layout>
    {{-- Scripts penunjang flatpickr & qrious --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
    <script src="{{ asset('js/face-api.min.js') }}"></script>

    <div class="py-8 sm:py-10 font-sans text-slate-100 bg-[#020b18] min-h-screen relative overflow-hidden">
        {{-- Efek Latar Belakang Halus --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-gradient-to-b from-[#0d52a1]/20 via-[#031d3d]/10 to-transparent opacity-30 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- HERO SECTION (UNIFIED ELEVATE DARK GLASS - AQUALIFE & E-LEARNING) --}}
            <div class="animate-enter relative rounded-[2.5rem] bg-gradient-to-br from-[#0d52a1]/85 via-[#031d3d]/90 to-[#021124]/95 p-8 md:p-10 mb-8 text-white shadow-2xl shadow-[#0d52a1]/25 border border-white/20 backdrop-blur-2xl overflow-hidden group">
                {{-- Specular Top Rim Light --}}
                <div class="absolute inset-0 rounded-[2.5rem] pointer-events-none border-t border-l border-white/30"></div>
                <div class="absolute inset-0 opacity-[0.03] bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none mix-blend-overlay"></div>

                {{-- Ambient Radiant Glow Orbs --}}
                <div class="absolute -top-16 -right-16 w-80 h-80 bg-[#56bbf1]/20 rounded-full blur-[100px] pointer-events-none"></div>
                <div class="absolute -bottom-16 -left-16 w-72 h-72 bg-[#0d52a1]/30 rounded-full blur-[90px] pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-8">
                    
                    {{-- KIRI: Badge, Headline, Subtitle, Chips, Action Buttons --}}
                    <div class="space-y-4 max-w-2xl">
                        <div class="flex flex-wrap items-center gap-2">
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white border border-white/15 text-xs font-bold transition-all shadow-sm">
                                <i class="ph-bold ph-arrow-left text-sky-400"></i>
                                <span>Dashboard</span>
                            </a>
                            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 border border-white/20 text-sky-300 text-xs font-bold uppercase tracking-wider backdrop-blur-md shadow-sm">
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-sky-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-sky-400"></span>
                                </span>
                                Master Data Kesiswaan
                            </div>
                        </div>

                        <h1 class="text-3xl sm:text-4xl md:text-5xl font-black tracking-tight leading-snug sm:leading-snug md:leading-normal text-white">
                            <span class="block text-slate-100">Daftar Induk &</span>
                            <span class="block mt-1 sm:mt-1.5 text-transparent bg-clip-text bg-gradient-to-r from-[#56bbf1] via-sky-200 to-[#38bdf8]">Profil Siswa Terpadu</span>
                        </h1>
                        <p class="text-slate-300 text-sm sm:text-base font-medium leading-relaxed">
                            Kelola data pokok siswa, sinkronisasi tag RFID & kartu pelajar, penetapan rombel, serta pencetakan buku induk secara komprehensif.
                        </p>

                        {{-- Feature Highlight Chips --}}
                        <div class="flex flex-wrap items-center gap-2.5 pt-1">
                            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-white/5 border border-white/10 text-slate-200 text-xs font-semibold backdrop-blur-sm shadow-sm">
                                <i class="ph-bold ph-check text-sky-400"></i> Basis Data Terpusat
                            </div>
                            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-white/5 border border-white/10 text-slate-200 text-xs font-semibold backdrop-blur-sm shadow-sm">
                                <i class="ph-bold ph-check text-sky-400"></i> Integrasi RFID & Kartu OSIS
                            </div>
                            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-white/5 border border-white/10 text-slate-200 text-xs font-semibold backdrop-blur-sm shadow-sm">
                                <i class="ph-bold ph-check text-sky-400"></i> Validasi Identitas Otomatis
                            </div>
                        </div>

                        {{-- ACTION BUTTONS --}}
                        <div class="flex flex-wrap items-center gap-3 pt-2">
                            <button onclick="document.getElementById('input_student_id').focus(); document.getElementById('input_student_id').scrollIntoView({behavior: 'smooth', block: 'center'});" class="group bg-gradient-to-r from-[#0d52a1] via-sky-600 to-[#56bbf1] hover:from-sky-600 hover:to-sky-400 text-white shadow-lg shadow-sky-600/25 border border-white/20 px-6 py-3.5 rounded-2xl font-bold text-sm transition-all flex items-center justify-center gap-2 active:scale-95">
                                <i class="ph-bold ph-user-plus text-lg"></i>
                                <span>Registrasi Siswa</span>
                                <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
                            </button>
                            <a href="{{ route('face-recognition.index') }}" class="bg-sky-500/10 hover:bg-sky-500/20 border border-sky-400/30 hover:border-sky-400/50 text-sky-200 hover:text-white px-5 py-3.5 rounded-2xl font-bold text-sm transition-all flex items-center justify-center gap-2 shadow-sm backdrop-blur-md active:scale-95">
                                <i class="ph-bold ph-user-focus text-lg text-sky-400"></i>
                                <span>Studio Wajah AI</span>
                            </a>
                            <a href="{{ route('students.export') }}" class="bg-white/10 hover:bg-white/20 border border-white/15 hover:border-sky-400/40 text-white px-5 py-3.5 rounded-2xl font-bold text-sm transition-all flex items-center justify-center gap-2 shadow-sm backdrop-blur-md active:scale-95">
                                <i class="ph-bold ph-microsoft-excel-logo text-lg text-emerald-400"></i>
                                <span>Export Excel</span>
                            </a>
                        </div>
                    </div>

                    {{-- KANAN: Luminous Circular Showcase --}}
                    <div class="relative flex items-center justify-center shrink-0 w-full lg:w-auto mt-4 lg:mt-0">
                        {{-- Outer Pulse Ring --}}
                        <div class="absolute w-60 h-60 rounded-full border border-[#56bbf1]/30 animate-pulse pointer-events-none"></div>
                        <div class="absolute w-72 h-72 rounded-full border border-sky-400/15 pointer-events-none"></div>
                        
                        {{-- Core Floating Card inside luminous ring --}}
                        <div class="relative z-10 w-52 h-52 rounded-full bg-gradient-to-br from-[#0d52a1]/80 via-[#031d3d]/90 to-[#021124] p-1 border-2 border-[#56bbf1]/50 shadow-2xl shadow-sky-500/30 backdrop-blur-xl flex flex-col items-center justify-center text-center">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-sky-400 to-[#0d52a1] flex items-center justify-center text-white shadow-lg shadow-sky-400/40 mb-2 border border-white/20">
                                <i class="ph-bold ph-users-three text-2xl"></i>
                            </div>
                            <span class="text-3xl font-black tracking-tight text-white leading-none">
                                {{ number_format($students->total() ?? 0) }}
                            </span>
                            <span class="text-[11px] font-bold text-sky-300 uppercase tracking-widest mt-1">Total Siswa</span>
                            <div class="mt-2 inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 text-[10px] font-bold">
                                <i class="ph-fill ph-check-circle"></i> Terdaftar
                            </div>
                        </div>

                        {{-- Floating Status Orbs / Bubbles --}}
                        <div class="absolute -top-3 -right-3 z-20 flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-[#031d3d]/90 border border-white/20 text-white text-[11px] font-bold shadow-xl backdrop-blur-md">
                            <span class="w-2 h-2 rounded-full bg-sky-400 animate-ping"></span>
                            <i class="ph-bold ph-identification-card text-sky-400"></i>
                            <span>RFID Sync</span>
                        </div>

                        <div class="absolute -bottom-3 -left-3 z-20 flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-[#031d3d]/90 border border-white/20 text-white text-[11px] font-bold shadow-xl backdrop-blur-md">
                            <i class="ph-bold ph-chalkboard-teacher text-emerald-400"></i>
                            <span>{{ $classes->count() }} Rombel</span>
                        </div>

                        <div class="absolute top-1/2 -left-6 -translate-y-1/2 z-20 hidden sm:flex items-center justify-center w-9 h-9 rounded-full bg-[#0d52a1]/80 border border-white/30 text-white shadow-lg backdrop-blur-md" title="Master Data Terintegrasi">
                            <i class="ph-bold ph-database text-sm text-sky-300"></i>
                        </div>
                    </div>

                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

                {{-- KOLOM KIRI: FORM & IMPORT --}}
                <div class="lg:col-span-1">
                    
                    {{-- WRAPPER STICKY --}}
                    <div class="sticky top-24 space-y-6">

                        <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] shadow-2xl border border-white/10 backdrop-blur-xl overflow-hidden relative group transition-colors">
                            {{-- Aksen Header Elevate --}}
                            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-[#0d52a1] to-[#56bbf1]"></div>
                            
                            <div class="p-6 md:p-8">
                                <div class="flex items-center gap-4 mb-6 pb-4 border-b border-white/10">
                                    <div class="w-12 h-12 bg-sky-500/20 text-sky-400 rounded-2xl flex items-center justify-center text-2xl shadow-sm border border-sky-400/30">
                                        <i class="ph-duotone ph-user-plus"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-black text-white leading-none">Registrasi Cepat</h3>
                                        <p class="text-xs text-slate-400 font-bold mt-1 uppercase tracking-wider">Input Siswa Baru</p>
                                    </div>
                                </div>

                                {{-- PENTING: PENANGKAP ERROR VALIDASI DARI SERVER --}}
                                @if ($errors->any())
                                    <div class="mb-6 p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 flex items-start gap-3 shadow-xl backdrop-blur-xl">
                                        <i class="ph-fill ph-warning-circle text-rose-400 text-xl shrink-0 mt-0.5"></i>
                                        <div>
                                            <h4 class="text-sm font-bold text-rose-200 mb-1">Gagal memproses data!</h4>
                                            <ul class="text-xs text-rose-300 font-medium space-y-1 list-disc list-inside">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endif

                                <form id="quick-register-form" action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5" x-data="{ photoPreview: null }">
                                    @csrf
                                    
                                    <div>
                                        <label for="input_student_id" class="block text-xs font-bold text-sky-400 uppercase tracking-wider mb-2 ml-1">NIS / NISN <span class="text-rose-400">*</span></label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                                <i class="ph-bold ph-identification-card"></i>
                                            </div>
                                            <input type="text" id="input_student_id" name="student_id" value="{{ old('student_id') }}" required placeholder="Nomor Induk"
                                                class="w-full pl-11 rounded-2xl border border-white/10 bg-slate-900/80 focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 text-sm py-3 font-bold text-white transition-all placeholder:text-slate-500 placeholder:font-normal [color-scheme:dark]">
                                        </div>
                                    </div>

                                    <div>
                                        <label for="input_name" class="block text-xs font-bold text-sky-400 uppercase tracking-wider mb-2 ml-1">Nama Lengkap <span class="text-rose-400">*</span></label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                                <i class="ph-bold ph-user"></i>
                                            </div>
                                            <input type="text" id="input_name" name="name" value="{{ old('name') }}" required placeholder="Nama Sesuai Ijazah"
                                                class="w-full pl-11 rounded-2xl border border-white/10 bg-slate-900/80 focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 text-sm py-3 font-bold text-white transition-all placeholder:text-slate-500 placeholder:font-normal [color-scheme:dark]">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label for="input_class_id" class="block text-xs font-bold text-sky-400 uppercase tracking-wider mb-2 ml-1">Kelas <span class="text-rose-400">*</span></label>
                                            <div class="relative">
                                                <select id="input_class_id" name="class_id" required class="w-full rounded-2xl border border-white/10 bg-slate-900/80 focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 text-sm py-3 font-bold text-white appearance-none px-4 [color-scheme:dark]">
                                                    <option value="" class="bg-slate-900 text-white">Pilih</option>
                                                    @foreach ($classes as $class)
                                                        <option value="{{ $class->id }}" class="bg-slate-900 text-white" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                                            {{ $class->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                                            </div>
                                        </div>
                                        <div>
                                            <label for="input_gender" class="block text-xs font-bold text-sky-400 uppercase tracking-wider mb-2 ml-1">Gender <span class="text-rose-400">*</span></label>
                                            <div class="relative">
                                                <select id="input_gender" name="gender" required class="w-full rounded-2xl border border-white/10 bg-slate-900/80 focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 text-sm py-3 font-bold text-white appearance-none px-4 [color-scheme:dark]">
                                                    <option value="L" class="bg-slate-900 text-white" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                                    <option value="P" class="bg-slate-900 text-white" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                                </select>
                                                <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- FOTO --}}
                                    <div>
                                        <label for="input_photo" class="block text-xs font-bold text-sky-400 uppercase tracking-wider mb-2 ml-1">Foto (Opsional)</label>
                                        <div class="flex items-center gap-4 bg-slate-900/50 p-3 rounded-2xl border border-white/10">
                                            <div class="shrink-0 w-12 h-12 rounded-xl bg-slate-900 border border-white/10 overflow-hidden flex items-center justify-center shadow-sm">
                                                <template x-if="photoPreview">
                                                    <img :src="photoPreview" class="w-full h-full object-cover">
                                                </template>
                                                <template x-if="!photoPreview">
                                                    <i class="ph-duotone ph-camera text-slate-400 text-2xl"></i>
                                                </template>
                                            </div>
                                            <input type="file" id="input_photo" name="photo" accept="image/*" 
                                                @change="const file = $event.target.files[0]; const reader = new FileReader(); reader.onload = (e) => { photoPreview = e.target.result }; reader.readAsDataURL(file)"
                                                class="block w-full text-xs text-slate-300 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-sky-500/20 file:text-sky-300 hover:file:bg-sky-500/30 cursor-pointer"/>
                                        </div>
                                    </div>

                                    {{-- RFID & WA --}}
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label for="input_rfid" class="block text-xs font-bold text-sky-400 uppercase tracking-wider mb-2 ml-1">RFID (Opsional)</label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-scan"></i></div>
                                                <input type="text" id="input_rfid" name="rfid_id" value="{{ old('rfid_id') }}" placeholder="Scan..."
                                                    class="w-full pl-9 rounded-2xl border border-white/10 bg-slate-900/80 focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 text-sm py-3 font-mono font-bold text-white placeholder:text-slate-500 [color-scheme:dark]">
                                            </div>
                                        </div>
                                        <div>
                                            <label for="input_parent_wa" class="block text-xs font-bold text-sky-400 uppercase tracking-wider mb-2 ml-1">WA Ortu</label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-emerald-400"><i class="ph-bold ph-whatsapp-logo"></i></div>
                                                <input type="text" id="input_parent_wa" name="parent_wa_number" value="{{ old('parent_wa_number') }}" placeholder="628..."
                                                    class="w-full pl-9 rounded-2xl border border-white/10 bg-slate-900/80 focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 text-sm py-3 font-mono font-bold text-white placeholder:text-slate-500 [color-scheme:dark]">
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-[#0d52a1] to-[#56bbf1] hover:from-sky-600 hover:to-sky-400 text-white font-bold rounded-2xl transition-all shadow-lg shadow-sky-600/25 flex items-center justify-center gap-2 transform active:scale-95 mt-4 border border-white/20 group">
                                        <i class="ph-bold ph-floppy-disk text-lg group-hover:scale-110 transition-transform"></i>
                                        <span>Simpan Data</span>
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- IMPORT EXCEL --}}
                        <div class="bg-emerald-500/10 rounded-[2.5rem] border border-emerald-500/30 p-6 relative overflow-hidden group hover:shadow-xl transition-all backdrop-blur-xl">
                            <div class="relative z-10">
                                <h3 class="text-sm font-black text-emerald-300 mb-1 flex items-center gap-2">
                                    <i class="ph-bold ph-microsoft-excel-logo text-emerald-400 text-lg"></i> Import Massal
                                </h3>
                                <p class="text-[10px] text-slate-300 mb-4 font-bold">Gunakan file Excel untuk input banyak data sekaligus.</p>
                                
                                <form id="import-form" action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data" class="flex gap-2 items-center">
                                    @csrf
                                    <label for="file_upload" class="flex-1 cursor-pointer">
                                        <div class="bg-slate-900/80 border border-dashed border-emerald-400/40 rounded-xl py-3 px-4 text-center transition-all hover:border-emerald-400 hover:bg-emerald-500/20 truncate">
                                            <span class="text-xs font-bold text-emerald-300 truncate flex items-center justify-center gap-2">
                                                <i class="ph-bold ph-upload-simple"></i> Pilih File...
                                            </span>
                                        </div>
                                        <input type="file" name="file" id="file_upload" required class="hidden" onchange="this.previousElementSibling.querySelector('span').innerHTML = '<i class=\'ph-bold ph-check\'></i> File Dipilih'">
                                    </label>
                                    <button type="submit" class="py-3 px-5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl transition-colors text-xs shadow-md shadow-emerald-500/20 flex items-center gap-2 border border-emerald-400/30">
                                        <span>Upload</span>
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- IMPORT MASSAL PAS FOTO RESMI (ZIP) --}}
                        <div class="bg-gradient-to-br from-sky-500/10 via-indigo-500/10 to-blue-500/10 rounded-[2.5rem] border border-sky-500/30 p-6 relative overflow-hidden group hover:shadow-xl transition-all backdrop-blur-xl">
                            <div class="relative z-10">
                                <h3 class="text-sm font-black text-sky-300 mb-1 flex items-center gap-2">
                                    <i class="ph-bold ph-file-zip text-sky-400 text-lg"></i> Import Pas Foto (ZIP)
                                </h3>
                                <p class="text-[10px] text-slate-300 mb-4 font-bold leading-relaxed">
                                    Upload file <strong class="text-white">.ZIP</strong> pas foto. Nama file foto harus sesuai <strong class="text-sky-300">NISN</strong> atau <strong class="text-sky-300">NIS</strong> siswa (contoh: <code class="bg-black/50 px-1 py-0.5 rounded text-sky-200">0101782738.jpg</code>).
                                </p>
                                
                                <form id="import-photo-form" action="{{ route('students.importPhotos') }}" method="POST" enctype="multipart/form-data" class="flex gap-2 items-center">
                                    @csrf
                                    <label for="zip_file_upload" class="flex-1 cursor-pointer">
                                        <div class="bg-slate-900/80 border border-dashed border-sky-400/40 rounded-xl py-3 px-4 text-center transition-all hover:border-sky-400 hover:bg-sky-500/20 truncate">
                                            <span class="text-xs font-bold text-sky-300 truncate flex items-center justify-center gap-2">
                                                <i class="ph-bold ph-archive"></i> Pilih File ZIP...
                                            </span>
                                        </div>
                                        <input type="file" name="zip_file" id="zip_file_upload" accept=".zip" required class="hidden" onchange="this.previousElementSibling.querySelector('span').innerHTML = '<i class=\'ph-bold ph-check\'></i> ' + (this.files[0] ? this.files[0].name.substring(0, 14) + '...' : 'ZIP Dipilih')">
                                    </label>
                                    <button type="submit" class="py-3 px-5 bg-sky-600 hover:bg-sky-500 text-white font-bold rounded-xl transition-colors text-xs shadow-md shadow-sky-500/20 flex items-center gap-2 border border-sky-400/30 shrink-0">
                                        <span>Upload</span>
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- KOLOM KANAN: DAFTAR SISWA --}}
                <div class="lg:col-span-2">
                    <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] shadow-2xl border border-white/10 backdrop-blur-xl overflow-hidden h-full flex flex-col min-h-[800px]">
                        
                        {{-- Toolbar --}}
                        <div class="p-6 border-b border-white/10 bg-white/5 flex flex-col xl:flex-row flex-wrap gap-4 justify-between items-center">
                            <h3 class="font-black text-white text-lg flex items-center gap-2 w-full xl:w-auto shrink-0">
                                <i class="ph-fill ph-users text-sky-400"></i> Daftar Siswa
                            </h3>

                            <div class="flex flex-col sm:flex-row flex-wrap gap-3 w-full xl:w-auto justify-start xl:justify-end items-center">
                                <form action="{{ route('students.index') }}" method="GET" class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                                    <div class="relative flex-1 sm:w-48">
                                        <i class="ph-bold ph-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="text" id="search_input" aria-label="Cari nama atau NISN" name="search" placeholder="Cari nama / NISN..." value="{{ request('search') }}"
                                               class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-white/10 bg-slate-900/80 focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 text-xs font-bold text-white shadow-sm placeholder:text-slate-500 [color-scheme:dark]">
                                    </div>
                                    
                                    <select name="filter_class_id" id="filter_class_id" aria-label="Filter Kelas" onchange="this.form.submit()" class="rounded-xl border border-white/10 bg-slate-900/80 focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 text-xs font-bold text-white py-2.5 px-3 shadow-sm cursor-pointer w-full sm:w-32 [color-scheme:dark]">
                                        <option value="" class="bg-slate-900 text-white">Semua Kelas</option>
                                        @foreach ($classes as $class)
                                            <option value="{{ $class->id }}" class="bg-slate-900 text-white" {{ request('filter_class_id') == $class->id ? 'selected' : '' }}>
                                                {{ $class->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <select name="filter_status" id="filter_status" aria-label="Filter Status" onchange="this.form.submit()" class="rounded-xl border border-white/10 bg-slate-900/80 focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 text-xs font-bold text-white py-2.5 px-3 shadow-sm cursor-pointer w-full sm:w-44 [color-scheme:dark]">
                                        <option value="" class="bg-slate-900 text-white">Semua Status</option>
                                        <option value="lengkap" class="bg-slate-900 text-white" {{ request('filter_status') == 'lengkap' ? 'selected' : '' }}>Biodata Lengkap</option>
                                        <option value="belum_lengkap" class="bg-slate-900 text-white" {{ request('filter_status') == 'belum_lengkap' ? 'selected' : '' }}>Biodata Belum Lengkap</option>
                                        <option value="face_pending" class="bg-slate-900 text-amber-300" {{ request('filter_status') == 'face_pending' ? 'selected' : '' }}>Face ID: Menunggu Verifikasi</option>
                                        <option value="face_verified" class="bg-slate-900 text-emerald-300" {{ request('filter_status') == 'face_verified' ? 'selected' : '' }}>Face ID: Terverifikasi</option>
                                        <option value="face_unregistered" class="bg-slate-900 text-slate-300" {{ request('filter_status') == 'face_unregistered' ? 'selected' : '' }}>Face ID: Belum Rekam</option>
                                    </select>
                                </form>

                                <div class="flex flex-wrap justify-end gap-2 w-full sm:w-auto mt-2 sm:mt-0">
                                    <a href="{{ route('students.export') }}" class="flex-1 sm:flex-none flex items-center justify-center px-4 py-2.5 bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 rounded-xl hover:bg-emerald-500/20 transition-all shadow-sm font-bold text-xs gap-2 whitespace-nowrap">
                                        <i class="ph-bold ph-microsoft-excel-logo text-lg"></i> Master Export
                                    </a>

                                    <button type="button" onclick="exportAttendanceSheet()" class="flex-1 sm:flex-none flex items-center justify-center px-4 py-2.5 bg-sky-500/10 border border-sky-400/30 text-sky-300 rounded-xl hover:bg-sky-500/20 transition-all shadow-sm font-bold text-xs gap-2 whitespace-nowrap" title="Lihat Laporan Absen Harian">
                                        <i class="ph-bold ph-calendar-check text-lg"></i> Rekap Absen
                                    </button>
                                    
                                    <button type="button" id="btn-delete-selected" onclick="deleteSelected()" class="hidden flex-1 sm:flex-none flex items-center justify-center px-4 py-2.5 bg-rose-500/10 border border-rose-500/30 text-rose-300 rounded-xl hover:bg-rose-500/20 transition-all shadow-sm font-bold text-xs gap-2 whitespace-nowrap">
                                        <i class="ph-bold ph-trash text-lg"></i> Hapus (<span id="delete-selected-count">0</span>)
                                    </button>

                                    <button type="button" id="btn-print-selected" onclick="printSelectedCards()" class="hidden flex-1 sm:flex-none flex items-center justify-center px-4 py-2.5 bg-sky-500/10 border border-sky-400/30 text-sky-300 rounded-xl hover:bg-sky-500/20 transition-all shadow-sm font-bold text-xs gap-2 whitespace-nowrap">
                                        <i class="ph-bold ph-check-square-offset text-lg"></i> Cetak (<span id="print-selected-count">0</span>)
                                    </button>

                                    <button type="button" onclick="printClassDistribution()" class="flex-1 sm:flex-none flex items-center justify-center px-4 py-2.5 bg-indigo-500/10 border border-indigo-500/30 text-indigo-300 rounded-xl hover:bg-indigo-500/20 transition-all shadow-sm font-bold text-xs gap-2 whitespace-nowrap" title="Cetak Lembar Pembagian Kelas">
                                        <i class="ph-bold ph-users-three text-lg"></i> Cetak Pembagian Kelas
                                    </button>
                                    
                                    <button type="button" onclick="printBatchCards()" class="flex-1 sm:flex-none flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-[#0d52a1] to-[#56bbf1] border border-white/20 text-white rounded-xl hover:from-sky-600 hover:to-sky-400 transition-all shadow-sm font-bold text-xs gap-2 whitespace-nowrap">
                                        <i class="ph-bold ph-printer text-lg"></i> Cetak Kelas
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Banner Antrean Verifikasi Face ID --}}
                        @if(($pendingFaceCount ?? 0) > 0)
                            <div class="mx-6 mt-4 p-4 rounded-2xl bg-amber-500/10 border border-amber-500/30 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-amber-500/20 border border-amber-400/30 flex items-center justify-center text-amber-300 shrink-0">
                                        <i class="ph-fill ph-face-mask text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-black text-amber-300 uppercase tracking-wider">
                                            {{ $pendingFaceCount }} Rekam Face ID Siswa Menunggu Verifikasi
                                        </h4>
                                        <p class="text-[11px] text-slate-300 mt-0.5">
                                            Face ID siswa tetap <strong class="text-white">aktif digunakan absen</strong> di Kiosk/Scan. Silakan periksa kesesuaian foto atau setujui sekaligus.
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 shrink-0 w-full sm:w-auto">
                                    <a href="{{ route('students.index', array_merge(request()->query(), ['filter_status' => 'face_pending'])) }}"
                                       class="flex-1 sm:flex-none text-center px-3.5 py-2 rounded-xl bg-white/10 hover:bg-white/15 text-amber-200 border border-amber-400/30 text-xs font-bold transition-all">
                                        Lihat Antrean
                                    </a>
                                    <form action="{{ route('students.face.bulk_verify') }}" method="POST" class="flex-1 sm:flex-none">
                                        @csrf
                                        <input type="hidden" name="class_id" value="{{ request('filter_class_id') }}">
                                        <button type="submit" class="w-full px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-black transition-all flex items-center justify-center gap-1.5 shadow-md">
                                            <i class="ph-bold ph-checks"></i> Verifikasi Semua
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                        
                        {{-- Table --}}
                        <div class="flex-1 overflow-x-auto custom-scrollbar relative pb-12">
                            <table class="w-full text-left border-collapse">
                                <thead class="bg-slate-900/80 border-b border-white/10 sticky top-0 z-20">
                                    <tr>
                                        <th class="px-6 py-4 text-center w-10">
                                            <input type="checkbox" id="selectAll" aria-label="Pilih semua siswa" class="rounded border-white/20 bg-slate-900 text-sky-500 focus:ring-sky-400/30 cursor-pointer">
                                        </th>
                                        <th class="px-6 py-4 text-xs font-bold text-sky-400 uppercase tracking-wider w-1/3">Identitas Siswa</th>
                                        <th class="px-6 py-4 text-xs font-bold text-sky-400 uppercase tracking-wider w-1/6">Kelas</th>
                                        <th class="px-6 py-4 text-xs font-bold text-sky-400 uppercase tracking-wider w-1/6 text-center">Status Data</th>
                                        <th class="px-6 py-4 text-right text-xs font-bold text-sky-400 uppercase tracking-wider w-1/6">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5">
                                    @forelse ($students as $student)
                                        <tr class="hover:bg-white/5 transition-colors group">
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <input type="checkbox" id="checkbox_{{ $student->id }}" aria-label="Pilih siswa {{ $student->name }}" value="{{ $student->id }}" class="student-checkbox rounded border-white/20 bg-slate-900 text-sky-500 focus:ring-sky-400/30 cursor-pointer">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center gap-4">
                                                    <div class="relative shrink-0">
                                                        <div class="w-12 h-12 rounded-2xl overflow-hidden shadow-sm border border-white/10 bg-slate-900 flex items-center justify-center group-hover:border-sky-400/40 transition-colors">
                                                            @if($student->photo_path)
                                                                <img src="{{ asset('storage/' . $student->photo_path) }}" alt="{{ $student->name }}" class="w-full h-full object-cover" loading="lazy">
                                                            @else
                                                                <div class="font-black text-sm {{ $student->gender == 'L' ? 'text-sky-400' : 'text-pink-400' }}">{{ substr($student->name, 0, 2) }}</div>
                                                            @endif
                                                        </div>
                                                        @if($student->rfid_id)
                                                            <div class="absolute -bottom-1 -right-1 bg-emerald-500 text-white rounded-full p-1 border-2 border-[#021124] shadow-sm" title="RFID Connected">
                                                                <i class="ph-bold ph-wifi-high text-[10px] block"></i>
                                                            </div>
                                                        @endif
                                                        @if($student->face_descriptor || $student->face_embedding)
                                                            <div class="absolute -top-1 -right-1 bg-cyan-500 text-white rounded-full p-1 border-2 border-[#021124] shadow-sm" title="Wajah Terdaftar (Face Recognition Ready)">
                                                                <i class="ph-bold ph-scan text-[10px] block"></i>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <div>
                                                        <div class="font-bold text-white text-sm group-hover:text-sky-300 transition-colors">{{ $student->name }}</div>
                                                        <div class="flex items-center gap-2 mt-1">
                                                            <span class="text-[10px] text-slate-400 font-mono bg-slate-900/80 px-1.5 py-0.5 rounded border border-white/10">{{ $student->student_id }}</span>
                                                            <span class="text-[10px] font-bold {{ $student->gender == 'L' ? 'text-sky-400' : 'text-pink-400' }}">{{ $student->gender }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex px-3 py-1.5 rounded-xl text-xs font-bold bg-white/5 text-slate-300 border border-white/10 group-hover:border-sky-400/30 transition-colors">
                                                    {{ $student->schoolClass->name ?? 'Unassigned' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <div class="flex flex-col items-center gap-1.5">
                                                    @php
                                                        $isComplete = $student->pob && $student->dob && $student->address && $student->father_name;
                                                        $faceStatus = $student->face_status ?? 'unregistered';
                                                        $hasFace = !empty($student->face_descriptor);
                                                        $facePhotoUrl = $student->face_photo_path ? asset('storage/' . $student->face_photo_path) : ($student->photo_path ? asset('storage/' . $student->photo_path) : '');
                                                    @endphp
                                                    @if($isComplete)
                                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 uppercase tracking-wide">
                                                            <i class="ph-fill ph-check-circle"></i> Lengkap
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black bg-amber-500/20 text-amber-300 border border-amber-500/30 uppercase tracking-wide">
                                                            <i class="ph-fill ph-warning-circle"></i> Incomplete
                                                        </span>
                                                    @endif

                                                    {{-- Badge Status Face ID (Klik untuk Verifikasi / Reset / Rekam) --}}
                                                    <button type="button"
                                                        class="open-face-modal inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black border transition-all cursor-pointer
                                                        {{ ($hasFace && $faceStatus === 'verified') ? 'bg-sky-500/20 text-sky-300 border-sky-400/30 hover:bg-sky-500/30' : (($hasFace && $faceStatus === 'pending') ? 'bg-amber-500/20 text-amber-300 border-amber-400/40 hover:bg-amber-500/30 animate-pulse' : ($faceStatus === 'rejected' ? 'bg-rose-500/20 text-rose-300 border-rose-400/30 hover:bg-rose-500/30' : 'bg-white/5 text-slate-400 border-white/10 hover:bg-white/10 hover:text-white')) }}"
                                                        data-student-id="{{ $student->id }}"
                                                        data-student-nisn="{{ $student->student_id }}"
                                                        data-student-name="{{ $student->name }}"
                                                        data-student-class="{{ $student->schoolClass->name ?? '-' }}"
                                                        data-face-status="{{ $faceStatus }}"
                                                        data-has-face="{{ $hasFace ? '1' : '0' }}"
                                                        data-photo-url="{{ $facePhotoUrl }}"
                                                        data-rejection-note="{{ $student->face_rejection_note ?? '' }}"
                                                        title="Kelola Face ID & Foto Siswa">
                                                        <i class="ph-fill ph-face-mask"></i>
                                                        @if($hasFace && $faceStatus === 'verified')
                                                            Face Terverifikasi
                                                        @elseif($hasFace && $faceStatus === 'pending')
                                                            Verifikasi Face ID
                                                        @elseif($faceStatus === 'rejected')
                                                            Foto Ditolak
                                                        @else
                                                            Belum Face ID
                                                        @endif
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <div class="flex items-center justify-end gap-1 opacity-80 group-hover:opacity-100 transition-opacity">
                                                    <a href="{{ route('students.show', $student->id) }}" target="_blank" class="w-9 h-9 rounded-xl bg-white/5 border border-white/10 text-slate-300 hover:text-white hover:border-sky-400/40 hover:bg-sky-500/20 flex items-center justify-center transition-all shadow-sm" title="Cetak Buku Induk">
                                                        <i class="ph-bold ph-printer text-lg"></i>
                                                    </a>

                                                    <a href="{{ route('students.card', $student->id) }}" target="_blank" class="w-9 h-9 rounded-xl bg-white/5 border border-white/10 text-slate-300 hover:text-white hover:border-sky-400/40 hover:bg-sky-500/20 flex items-center justify-center transition-all shadow-sm" title="Cetak Kartu OSIS">
                                                        <i class="ph-bold ph-identification-card text-lg"></i>
                                                    </a>

                                                    <a href="{{ route('students.edit', array_merge(['student' => $student->id], request()->query())) }}" class="w-9 h-9 rounded-xl bg-white/5 border border-white/10 text-slate-300 hover:text-amber-300 hover:border-amber-500/40 hover:bg-amber-500/20 flex items-center justify-center transition-all shadow-sm" title="Edit Data">
                                                        <i class="ph-bold ph-pencil-simple text-lg"></i>
                                                    </a>

                                                    <a href="{{ route('face-recognition.index', ['search' => $student->student_id ?: $student->nisn ?: $student->name]) }}" class="w-9 h-9 rounded-xl {{ $student->face_descriptor ? 'bg-emerald-500/10 border-emerald-500/30 text-emerald-400 hover:bg-emerald-500/20' : 'bg-white/5 border-white/10 text-slate-400 hover:text-sky-300 hover:border-sky-400/40 hover:bg-sky-500/20' }} border flex items-center justify-center transition-all shadow-sm" title="{{ $student->face_descriptor ? 'Wajah Terdaftar (Kelola)' : 'Rekam Wajah AI' }}">
                                                        <i class="ph-bold {{ $student->face_descriptor ? 'ph-user-check' : 'ph-user-focus' }} text-lg"></i>
                                                    </a>
                                                    
                                                    <div x-data="{ open: false }" class="relative">
                                                        <button @click="open = !open" @click.outside="open = false" class="w-9 h-9 rounded-xl bg-white/5 border border-white/10 text-slate-300 hover:text-white hover:border-white/20 hover:bg-white/10 flex items-center justify-center transition-all shadow-sm">
                                                            <i class="ph-bold ph-dots-three-vertical text-lg"></i>
                                                        </button>
                                                        
                                                        <div x-show="open" x-transition.origin.top.right class="absolute right-0 mt-2 w-48 bg-[#021124] rounded-2xl shadow-2xl border border-white/10 z-50 overflow-hidden py-1" style="display: none;">
                                                            
                                                            <button type="button" @click="open = false" class="w-full text-left px-4 py-2.5 text-xs font-bold text-slate-300 hover:bg-white/5 hover:text-sky-300 flex items-center gap-2 open-absen-modal transition-colors"
                                                                data-student-id="{{ $student->id }}" data-student-name="{{ $student->name }}">
                                                                <i class="ph-bold ph-user-check text-base"></i> Input Absen
                                                            </button>
                                                            
                                                            <button type="button" @click="open = false" class="w-full text-left px-4 py-2.5 text-xs font-bold text-slate-300 hover:bg-white/5 hover:text-emerald-300 flex items-center gap-2 open-qr-modal transition-colors"
                                                                data-student-id="{{ $student->student_id }}" data-student-name="{{ $student->name }}">
                                                                <i class="ph-bold ph-qr-code text-base"></i> Lihat QR Code
                                                            </button>

                                                            <button type="button" @click="open = false" class="w-full text-left px-4 py-2.5 text-xs font-bold text-slate-300 hover:bg-white/5 hover:text-sky-300 flex items-center gap-2 open-face-modal transition-colors"
                                                                data-student-id="{{ $student->id }}"
                                                                data-student-nisn="{{ $student->student_id }}"
                                                                data-student-name="{{ $student->name }}"
                                                                data-student-class="{{ $student->schoolClass->name ?? '-' }}"
                                                                data-face-status="{{ $student->face_status ?? 'unregistered' }}"
                                                                data-has-face="{{ !empty($student->face_descriptor) ? '1' : '0' }}"
                                                                data-photo-url="{{ $student->face_photo_path ? asset('storage/' . $student->face_photo_path) : ($student->photo_path ? asset('storage/' . $student->photo_path) : '') }}"
                                                                data-rejection-note="{{ $student->face_rejection_note ?? '' }}">
                                                                <i class="ph-bold ph-face-mask text-base"></i> Kelola Face ID & Foto
                                                            </button>
                                                            
                                                            <div class="border-t border-white/10 my-1"></div>
                                                            
                                                            <form action="{{ route('students.destroy', $student->id) }}" method="POST">
                                                                @csrf @method('DELETE')
                                                                <button type="button" class="w-full text-left px-4 py-2.5 text-xs font-bold text-rose-400 hover:bg-rose-500/20 flex items-center gap-2 transition-colors btn-delete-confirm" data-name="{{ $student->name }}">
                                                                    <i class="ph-bold ph-trash text-base"></i> Hapus Siswa
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-20 text-center">
                                                <div class="w-20 h-20 bg-sky-500/10 rounded-full flex items-center justify-center mx-auto mb-4 text-sky-400 border border-sky-400/20">
                                                    <i class="ph-duotone ph-users-three text-4xl"></i>
                                                </div>
                                                <p class="text-sm font-bold text-white">Belum ada data siswa ditemukan.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="p-6 border-t border-white/10 bg-slate-900/50">
                            {{ $students->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL ABSEN --}}
    <div id="absen-manual-modal" class="fixed inset-0 bg-[#020b18]/80 backdrop-blur-md overflow-y-auto h-full w-full hidden z-50 transition-opacity">
        <div class="relative top-20 mx-auto p-0 border border-white/10 w-full max-w-md shadow-2xl rounded-[2rem] bg-[#021124] overflow-hidden text-white">
            <div class="bg-gradient-to-r from-[#0d52a1] to-[#56bbf1] px-6 py-4 flex justify-between items-center text-white border-b border-white/20">
                <h3 class="font-bold text-white text-lg">Input Absensi Manual</h3>
                <button type="button" id="absen-modal-close" class="text-white/70 hover:text-white text-2xl leading-none">&times;</button>
            </div>
            <form id="absen-manual-form" action="{{ route('reports.storeManual') }}" method="POST" class="p-6 space-y-5">
                @csrf
                <input type="hidden" name="student_id" id="absen-modal-student-id">
                <input type="hidden" name="attendance_type" value="Harian">
                
                <div class="text-center mb-2">
                    <p class="text-xs text-slate-400 uppercase font-bold tracking-wide">Siswa</p>
                    <h4 id="absen-modal-student-name" class="text-xl font-black text-white">Nama Siswa</h4>
                </div>

                <div>
                    <label for="absen_date" class="block text-xs font-bold text-sky-400 uppercase mb-1.5">Tanggal</label>
                    <input type="text" id="absen_date" name="date" value="{{ date('Y-m-d') }}" class="datepicker w-full rounded-xl border border-white/10 bg-slate-900/80 focus:ring-sky-400 focus:border-sky-400 font-bold text-white [color-scheme:dark]" placeholder="dd/mm/yyyy">
                </div>

                <div>
                    <label for="absen_status" class="block text-xs font-bold text-sky-400 uppercase mb-1.5">Status Kehadiran</label>
                    <select id="absen_status" name="status" class="w-full rounded-xl border border-white/10 bg-slate-900/80 focus:ring-sky-400 focus:border-sky-400 font-bold text-white [color-scheme:dark]">
                        <option value="Hadir" class="bg-slate-900 text-white">Hadir (Manual)</option>
                        <option value="Sakit" class="bg-slate-900 text-white">Sakit</option>
                        <option value="Izin" class="bg-slate-900 text-white">Izin</option>
                        <option value="Alfa" class="bg-slate-900 text-white">Alfa</option>
                        <option value="Terlambat" class="bg-slate-900 text-white">Terlambat</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="absen_time_in" class="block text-xs font-bold text-sky-400 uppercase mb-1.5">Waktu Masuk</label>
                        <input type="time" id="absen_time_in" name="time_in" value="{{ now()->format('H:i') }}" class="w-full rounded-xl border border-white/10 bg-slate-900/80 focus:ring-sky-400 focus:border-sky-400 text-center font-mono font-bold text-white [color-scheme:dark]">
                    </div>
                    <div>
                        <label for="absen_time_out" class="block text-xs font-bold text-sky-400 uppercase mb-1.5">Waktu Pulang</label>
                        <input type="time" id="absen_time_out" name="time_out" class="w-full rounded-xl border border-white/10 bg-slate-900/80 focus:ring-sky-400 focus:border-sky-400 text-center font-mono font-bold text-white [color-scheme:dark]">
                    </div>
                </div>
                
                <div>
                    <label for="absen_notes" class="block text-xs font-bold text-sky-400 uppercase mb-1.5">Keterangan (Opsional)</label>
                    <textarea id="absen_notes" name="notes" rows="2" placeholder="Contoh: Datang terlambat karena ban bocor..." class="w-full rounded-xl border border-white/10 bg-slate-900/80 focus:ring-sky-400 focus:border-sky-400 text-sm font-medium text-white placeholder:text-slate-500 [color-scheme:dark]"></textarea>
                </div>

                <button type="submit" class="w-full py-3 bg-gradient-to-r from-[#0d52a1] to-[#56bbf1] hover:from-sky-600 hover:to-sky-400 text-white font-bold rounded-xl shadow-lg shadow-sky-600/25 transition-transform active:scale-95 border border-white/20">Simpan Data</button>
            </form>
        </div>
    </div>

    {{-- MODAL QR CODE LOKAL --}}
    <div id="qr-code-modal" class="fixed inset-0 bg-[#020b18]/80 backdrop-blur-md overflow-y-auto h-full w-full hidden z-50 transition-opacity">
        <div class="relative top-20 mx-auto border border-white/10 w-full max-w-sm shadow-2xl rounded-[2rem] bg-[#021124] overflow-hidden text-center p-8 text-white">
            <h3 class="text-lg font-black text-white mb-1" id="qr-modal-student-name">QR Code</h3>
            <p class="text-xs text-sky-400 font-bold uppercase mb-6">Identitas Digital Siswa</p>
            <div class="bg-white p-4 border-2 border-dashed border-sky-400/50 rounded-2xl inline-block mb-6 relative group">
                <div class="absolute inset-0 bg-sky-500/10 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center rounded-2xl pointer-events-none">
                    <i class="ph-bold ph-download-simple text-sky-600 text-2xl"></i>
                </div>
                <canvas id="qr-modal-canvas" class="mx-auto"></canvas>
            </div>
            <div class="flex gap-3 justify-center">
                <button type="button" id="qr-modal-close" class="px-6 py-2.5 bg-white/5 border border-white/10 text-slate-300 font-bold rounded-xl hover:bg-white/10 hover:text-white text-sm transition-colors">Tutup</button>
                <a id="qr-modal-download" href="#" download="qrcode.png" class="px-6 py-2.5 bg-gradient-to-r from-[#0d52a1] to-[#56bbf1] hover:from-sky-600 hover:to-sky-400 text-white font-bold rounded-xl shadow-lg shadow-sky-600/25 text-sm transition-transform active:scale-95 flex items-center gap-2 border border-white/20">
                    <i class="ph-bold ph-download-simple"></i> Unduh
                </a>
            </div>
        </div>
    </div>

    {{-- MODAL VERIFIKASI, HAPUS FOTO & REKAM FACE ID SISWA --}}
    <div id="face-recognition-modal" class="fixed inset-0 bg-[#020b18]/85 backdrop-blur-md overflow-y-auto h-full w-full hidden z-50 transition-opacity p-4">
        <div class="relative top-6 sm:top-10 mx-auto border border-white/15 w-full max-w-2xl shadow-2xl rounded-[2.5rem] bg-[#021124] overflow-hidden text-white mb-12">
            {{-- Header --}}
            <div class="bg-gradient-to-r from-[#0d52a1] to-[#031d3d] px-6 sm:px-8 py-5 flex justify-between items-center border-b border-white/15">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-sky-500/20 border border-sky-400/30 flex items-center justify-center text-sky-300">
                        <i class="ph-fill ph-face-mask text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-white text-base sm:text-lg leading-tight" id="face-modal-student-name">Kelola Face ID & Foto Siswa</h3>
                        <p class="text-xs text-sky-300 font-mono font-bold mt-0.5" id="face-modal-student-meta">NISN: -</p>
                    </div>
                </div>
                <button type="button" onclick="closeFaceModal()" class="w-9 h-9 rounded-xl bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors">
                    <i class="ph-bold ph-x text-lg"></i>
                </button>
            </div>

            <div class="p-6 sm:p-8 space-y-6">
                {{-- Bagian 1: Preview Foto Saat Ini & Status Verifikasi --}}
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-6 items-center bg-slate-900/70 p-5 rounded-2xl border border-white/10">
                    <div class="sm:col-span-4 flex flex-col items-center">
                        <div class="w-32 h-36 rounded-2xl overflow-hidden border-2 border-white/15 bg-slate-950 flex items-center justify-center shadow-lg relative">
                            <img id="face-modal-current-photo" src="" alt="Foto Siswa" class="w-full h-full object-cover hidden">
                            <div id="face-modal-no-photo" class="flex flex-col items-center justify-center text-slate-500 p-4 text-center">
                                <i class="ph-duotone ph-user-circle text-5xl mb-1"></i>
                                <span class="text-[10px] font-bold uppercase">Belum Ada Foto</span>
                            </div>
                        </div>
                    </div>

                    <div class="sm:col-span-8 space-y-3">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Status Face ID:</span>
                            <span id="face-modal-status-badge" class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider">-</span>
                        </div>

                        <p id="face-modal-status-desc" class="text-xs text-slate-300 leading-relaxed">
                            Memuat informasi Face ID siswa...
                        </p>

                        {{-- Tombol Verifikasi Cepat (Muncul jika ada Face ID & status pending) --}}
                        <div id="face-modal-verify-box" class="hidden pt-2">
                            <form id="face-verify-form" method="POST">
                                @csrf
                                <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-black text-xs uppercase tracking-wider shadow-lg shadow-emerald-600/25 transition-all flex items-center justify-center gap-2">
                                    <i class="ph-bold ph-seal-check text-base"></i> Verifikasi & Setujui Face ID Siswa Ini
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Bagian 2: Hapus Foto Tidak Sesuai / Reset Kunci Face ID --}}
                <div class="bg-rose-500/10 border border-rose-500/25 rounded-2xl p-5 space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl bg-rose-500/20 border border-rose-400/30 flex items-center justify-center text-rose-300 shrink-0 mt-0.5">
                            <i class="ph-fill ph-trash text-lg"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-black text-rose-300 uppercase tracking-wider">Hapus Foto Tidak Sesuai / Buka Kunci Rekam Ulang</h4>
                            <p class="text-[11px] text-slate-300 mt-0.5">
                                Gunakan fitur ini jika siswa salah memasukkan foto, foto tidak sesuai ketentuan, atau agar siswa dapat merekam ulang wajahnya di Portal Siswa.
                            </p>
                        </div>
                    </div>

                    <form id="face-reset-form" method="POST" class="space-y-3">
                        @csrf
                        @method('DELETE')
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-300 uppercase tracking-wider mb-1">Tindakan Hapus / Reset</label>
                                <select name="reset_type" class="w-full rounded-xl border border-white/15 bg-slate-900/90 text-xs font-bold text-white py-2.5 px-3 [color-scheme:dark]">
                                    <option value="both">Hapus Foto + Reset Face ID (Buka Kunci)</option>
                                    <option value="face_only">Reset Face ID Saja (Pertahankan Foto)</option>
                                    <option value="photo_only">Hapus File Foto Saja</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-300 uppercase tracking-wider mb-1">Catatan untuk Siswa (Opsional)</label>
                                <input type="text" name="rejection_note" id="face-modal-rejection-note" placeholder="Cth: Foto gelap / bukan foto wajah asli"
                                       class="w-full rounded-xl border border-white/15 bg-slate-900/90 text-xs font-bold text-white py-2.5 px-3 placeholder:text-slate-500">
                            </div>
                        </div>
                        <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-rose-600/90 hover:bg-rose-500 text-white font-black text-xs uppercase tracking-wider transition-all flex items-center justify-center gap-2 shadow-md">
                            <i class="ph-bold ph-trash"></i> Hapus Foto & Buka Kunci Rekam Ulang Siswa
                        </button>
                    </form>
                </div>

                {{-- Bagian 3: Rekam / Update Face ID Langsung oleh Admin / Guru --}}
                <div class="bg-slate-900/60 border border-white/10 rounded-2xl p-5 space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <i class="ph-fill ph-camera text-sky-400 text-lg"></i>
                            <h4 class="text-xs font-black text-white uppercase tracking-wider">Rekam / Perbarui Face ID oleh Admin/Guru</h4>
                        </div>
                        <button type="button" id="btn-toggle-admin-enroll" onclick="toggleAdminFaceEnroll()" class="px-3 py-1.5 rounded-xl bg-sky-500/20 hover:bg-sky-500/30 text-sky-300 border border-sky-400/30 text-xs font-bold transition-all">
                            Buka Perekam Wajah
                        </button>
                    </div>

                    <div id="admin-face-enroll-panel" class="hidden space-y-4 pt-2 border-t border-white/10">
                        <div class="grid grid-cols-2 gap-2">
                            <button type="button" onclick="startAdminFaceCamera()" class="py-2.5 px-3 rounded-xl bg-sky-600 hover:bg-sky-500 text-white text-xs font-bold flex items-center justify-center gap-2">
                                <i class="ph-bold ph-video-camera"></i> Gunakan Kamera
                            </button>
                            <button type="button" onclick="document.getElementById('admin-face-file').click()" class="py-2.5 px-3 rounded-xl bg-white/10 hover:bg-white/15 text-slate-200 border border-white/15 text-xs font-bold flex items-center justify-center gap-2">
                                <i class="ph-bold ph-upload-simple"></i> Pilih File Foto
                            </button>
                            <input type="file" id="admin-face-file" accept="image/*" class="hidden" onchange="handleAdminFaceFile(event)">
                        </div>

                        <div class="relative rounded-2xl overflow-hidden bg-slate-950 border border-white/10 aspect-[4/3] max-h-64 mx-auto flex items-center justify-center">
                            <video id="admin-face-video" autoplay muted playsinline class="w-full h-full object-cover hidden transform -scale-x-100"></video>
                            <img id="admin-face-img-preview" class="w-full h-full object-contain hidden" alt="Preview">
                            <div id="admin-face-placeholder" class="text-center p-4 text-slate-500 text-xs font-bold">
                                Pilih kamera atau unggah foto wajah siswa untuk mengekstrak Face ID.
                            </div>
                        </div>

                        <div id="admin-face-status" class="text-center text-xs font-bold text-sky-300"></div>

                        <button type="button" id="btn-admin-save-face" onclick="captureAndSaveAdminFace()" class="w-full py-3 rounded-xl bg-gradient-to-r from-[#0d52a1] to-[#56bbf1] hover:from-sky-600 hover:to-sky-400 text-white font-black text-xs uppercase tracking-wider shadow-lg transition-all flex items-center justify-center gap-2">
                            <i class="ph-bold ph-floppy-disk text-base"></i> Deteksi Wajah & Simpan (Otomatis Terverifikasi)
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FORM HIDDEN UNTUK HAPUS MASSAL --}}
    <form id="form-delete-batch" action="{{ route('students.destroyBatch') ?? '#' }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
        <input type="hidden" name="ids" id="delete-batch-ids">
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 0. INISIALISASI FLATPICKR
            if (typeof flatpickr !== 'undefined') {
                flatpickr(".datepicker", {
                    altInput: true,
                    altFormat: "d/m/Y",
                    dateFormat: "Y-m-d",
                    locale: "id",
                    disableMobile: "true"
                });
            }

            // 1. FLASH MESSAGES (SUCCESS / ERROR GLOBAL)
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    background: '#021124',
                    color: '#fff',
                    confirmButtonColor: '#0d52a1',
                    timer: 3000,
                    timerProgressBar: true,
                    customClass: { popup: 'rounded-[2rem] border border-white/10 bg-[#021124] text-white shadow-2xl font-sans' }
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ session('error') }}',
                    background: '#021124',
                    color: '#fff',
                    confirmButtonColor: '#e11d48',
                    customClass: { popup: 'rounded-[2rem] border border-white/10 bg-[#021124] text-white shadow-2xl font-sans' }
                });
            @endif

            // 2. PREVENT DOUBLE SUBMIT LOADING STATE
            function disableSubmit(formId, btnText) {
                const form = document.getElementById(formId);
                if(form) {
                    form.addEventListener('submit', function() {
                        const btn = this.querySelector('button[type="submit"]');
                        if(btn) {
                            setTimeout(() => {
                                btn.disabled = true;
                                const icon = btn.querySelector('i');
                                if(icon) {
                                    icon.className = 'ph-bold ph-spinner animate-spin text-lg';
                                }
                                const span = btn.querySelector('span');
                                if(span) {
                                    span.innerText = btnText;
                                }
                                btn.classList.add('opacity-75', 'cursor-not-allowed');
                            }, 50);
                        }
                    });
                }
            }
            disableSubmit('quick-register-form', 'Menyimpan...');
            disableSubmit('import-form', 'Mengupload...');

            // 3. KONFIRMASI HAPUS SISWA SATUAN
            document.body.addEventListener('click', function(e) {
                if(e.target.closest('.btn-delete-confirm')) {
                    e.preventDefault();
                    const button = e.target.closest('.btn-delete-confirm');
                    const form = button.closest('form');
                    const studentName = button.getAttribute('data-name');

                    Swal.fire({
                        title: 'Hapus Siswa?',
                        text: `Data siswa "${studentName}" beserta riwayat absen akan dihapus permanen.`,
                        icon: 'warning',
                        background: '#021124',
                        color: '#fff',
                        showCancelButton: true,
                        confirmButtonColor: '#e11d48',
                        cancelButtonColor: '#1e293b',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                        customClass: {
                            popup: 'rounded-[2.5rem] font-sans border border-white/10 bg-[#021124] text-white shadow-2xl',
                            confirmButton: 'bg-rose-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-rose-700 transition-colors mx-2 shadow-lg',
                            cancelButton: 'bg-slate-800 text-slate-300 px-6 py-3 rounded-xl font-bold hover:bg-slate-700 transition-colors mx-2 border border-white/10'
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                }
            });

            // 4. LOGIKA MODAL (ABSEN & QR CODE LOKAL)
            const absenModal = document.getElementById('absen-manual-modal');
            const qrModal = document.getElementById('qr-code-modal');

            document.addEventListener('click', function(e) {
                // Handle Open Absen
                if (e.target.closest('.open-absen-modal')) {
                    const btn = e.target.closest('.open-absen-modal');
                    document.getElementById('absen-modal-student-name').innerText = btn.dataset.studentName;
                    document.getElementById('absen-modal-student-id').value = btn.dataset.studentId;
                    absenModal.classList.remove('hidden');
                }
                
                // Handle Open QR
                if (e.target.closest('.open-qr-modal')) {
                    const btn = e.target.closest('.open-qr-modal');
                    const id = btn.dataset.studentId;
                    const name = btn.dataset.studentName;
                    
                    document.getElementById('qr-modal-student-name').innerText = name;
                    
                    // Generate QR Code secara lokal langsung di browser
                    if (typeof QRious !== 'undefined') {
                        const qr = new QRious({
                            element: document.getElementById('qr-modal-canvas'),
                            value: id,
                            size: 200,
                            background: 'white',
                            foreground: '#032b5b',
                            level: 'H'
                        });

                        const downloadBtn = document.getElementById('qr-modal-download');
                        downloadBtn.href = qr.toDataURL('image/png');
                        downloadBtn.download = `QRCode_${name.replace(/\s+/g, '_')}.png`;
                    }

                    qrModal.classList.remove('hidden');
                }
            });

            document.getElementById('absen-modal-close').onclick = () => absenModal.classList.add('hidden');
            document.getElementById('qr-modal-close').onclick = () => qrModal.classList.add('hidden');
            
            window.addEventListener('click', function(event) {
                if (event.target == absenModal) absenModal.classList.add('hidden');
                if (event.target == qrModal) qrModal.classList.add('hidden');
            });

            // 5. LOGIKA CHECKBOX UNTUK CETAK & HAPUS MASSAL
            const masterCheckbox = document.getElementById('selectAll');
            if (masterCheckbox) {
                masterCheckbox.addEventListener('change', function() {
                    const checkboxes = document.querySelectorAll('.student-checkbox');
                    checkboxes.forEach(cb => cb.checked = this.checked);
                    toggleBatchButtons();
                });
            }

            document.querySelectorAll('.student-checkbox').forEach(cb => {
                cb.addEventListener('change', toggleBatchButtons);
            });

            function toggleBatchButtons() {
                const checkedCount = document.querySelectorAll('.student-checkbox:checked').length;
                const btnPrint = document.getElementById('btn-print-selected');
                const badgePrint = document.getElementById('print-selected-count');
                const btnDelete = document.getElementById('btn-delete-selected');
                const badgeDelete = document.getElementById('delete-selected-count');
                
                if (checkedCount > 0) {
                    btnPrint.classList.remove('hidden');
                    btnDelete.classList.remove('hidden');
                    badgePrint.innerText = checkedCount;
                    badgeDelete.innerText = checkedCount;
                } else {
                    btnPrint.classList.add('hidden');
                    btnDelete.classList.add('hidden');
                    if (masterCheckbox) masterCheckbox.checked = false; 
                }
            }
        });

        // FUNGSI JS CETAK KARTU MASSAL BERDASARKAN KELAS
        function printBatchCards() {
            const classSelect = document.querySelector('select[name="filter_class_id"]');
            const classId = classSelect ? classSelect.value : '';
            
            if (!classId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pilih Kelas Dulu!',
                    text: 'Silakan filter/pilih kelas di kotak pencarian sebelum mencetak kartu massal.',
                    background: '#021124',
                    color: '#fff',
                    confirmButtonColor: '#0d52a1',
                    customClass: { popup: 'rounded-[2rem] border border-white/10 bg-[#021124] text-white shadow-2xl font-sans' }
                });
                return;
            }
            window.open(`/students/print-batch?class_id=${classId}`, '_blank');
        }

        // FUNGSI JS CETAK KARTU TERPILIH
        function printSelectedCards() {
            const checkboxes = document.querySelectorAll('.student-checkbox:checked');
            if (checkboxes.length === 0) return;
            const selectedIds = Array.from(checkboxes).map(cb => cb.value).join(',');
            window.open(`/students/print-batch?ids=${selectedIds}`, '_blank');
        }
        
        // FUNGSI JS DOWNLOAD FORMAT EXCEL KOSONG UNTUK MANUAL
        function exportAttendanceSheet() {
            const classSelect = document.querySelector('select[name="filter_class_id"]');
            const classId = classSelect ? classSelect.value : '';
            
            if (!classId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pilih Kelas Terlebih Dahulu!',
                    text: 'Silakan filter/pilih kelas di kotak pencarian sebelum mengunduh format daftar hadir excel.',
                    background: '#021124',
                    color: '#fff',
                    confirmButtonColor: '#0d52a1',
                    customClass: { popup: 'rounded-[2rem] border border-white/10 bg-[#021124] text-white shadow-2xl font-sans' }
                });
                return;
            }
            
            window.location.href = `/students/export-attendance?class_id=${classId}`;
        }

        // FUNGSI JS CETAK PEMBAGIAN KELAS
        function printClassDistribution() {
            const classSelect = document.querySelector('select[name="filter_class_id"]');
            const classId = classSelect ? classSelect.value : '';
            window.open(`/students/print-class-distribution?class_id=${classId}`, '_blank');
        }
        
        // FUNGSI JS HAPUS TERPILIH (MASSAL)
        function deleteSelected() {
            const checkboxes = document.querySelectorAll('.student-checkbox:checked');
            if (checkboxes.length === 0) return;

            const selectedIds = Array.from(checkboxes).map(cb => cb.value).join(',');
            const count = checkboxes.length;

            Swal.fire({
                title: 'Hapus Siswa Terpilih?',
                text: `Anda yakin ingin menghapus ${count} data siswa yang dipilih?`,
                icon: 'warning',
                background: '#021124',
                color: '#fff',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#1e293b',
                confirmButtonText: 'Ya, Hapus Semua!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-[2.5rem] font-sans border border-white/10 bg-[#021124] text-white shadow-2xl',
                    confirmButton: 'bg-rose-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-rose-700 transition-colors mx-2 shadow-lg',
                    cancelButton: 'bg-slate-800 text-slate-300 px-6 py-3 rounded-xl font-bold hover:bg-slate-700 transition-colors mx-2 border border-white/10'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-batch-ids').value = selectedIds;
                    document.getElementById('form-delete-batch').submit();
                }
            });
        }

        document.addEventListener("DOMContentLoaded", function() {
            let scrollPosition = sessionStorage.getItem('scrollPosition');
            if (scrollPosition) {
                window.scrollTo({
                    top: parseInt(scrollPosition),
                    behavior: 'auto'
                });
                sessionStorage.removeItem('scrollPosition');
            }
        });

        window.addEventListener("beforeunload", function() {
            sessionStorage.setItem('scrollPosition', window.scrollY);
        });

        // =========================================================================
        // LOGIKA MODAL VERIFIKASI, HAPUS FOTO & REKAM FACE ID SISWA
        // =========================================================================
        let currentFaceStudentId = null;
        let adminFaceModelsLoaded = false;
        let adminFaceStream = null;
        let adminFaceMode = null; // 'camera' | 'upload'

        async function ensureAdminFaceModels() {
            if (adminFaceModelsLoaded) return true;
            const statusEl = document.getElementById('admin-face-status');
            try {
                statusEl.textContent = 'Memuat model AI pengenalan wajah...';
                const MODEL_URL = "{{ asset('models') }}";
                await Promise.all([
                    faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
                    faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
                    faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL)
                ]);
                adminFaceModelsLoaded = true;
                statusEl.textContent = 'Model AI siap digunakan.';
                return true;
            } catch (err) {
                console.error(err);
                statusEl.textContent = 'Gagal memuat model AI. Periksa koneksi internet.';
                return false;
            }
        }

        function stopAdminFaceStream() {
            if (adminFaceStream) {
                adminFaceStream.getTracks().forEach(t => t.stop());
                adminFaceStream = null;
            }
        }

        function closeFaceModal() {
            stopAdminFaceStream();
            document.getElementById('face-recognition-modal').classList.add('hidden');
            document.getElementById('admin-face-enroll-panel').classList.add('hidden');
        }

        function toggleAdminFaceEnroll() {
            const panel = document.getElementById('admin-face-enroll-panel');
            panel.classList.toggle('hidden');
            if (!panel.classList.contains('hidden')) {
                ensureAdminFaceModels();
            } else {
                stopAdminFaceStream();
            }
        }

        async function startAdminFaceCamera() {
            await ensureAdminFaceModels();
            stopAdminFaceStream();
            const video = document.getElementById('admin-face-video');
            const img = document.getElementById('admin-face-img-preview');
            const placeholder = document.getElementById('admin-face-placeholder');
            const statusEl = document.getElementById('admin-face-status');

            try {
                adminFaceStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user', width: 640, height: 480 } });
                video.srcObject = adminFaceStream;
                video.classList.remove('hidden');
                img.classList.add('hidden');
                placeholder.classList.add('hidden');
                adminFaceMode = 'camera';
                statusEl.textContent = 'Kamera aktif. Arahkan wajah siswa lalu klik Deteksi Wajah & Simpan.';
            } catch (err) {
                statusEl.textContent = 'Kamera tidak dapat diakses. Gunakan opsi Pilih File Foto.';
            }
        }

        function handleAdminFaceFile(event) {
            const file = event.target.files[0];
            if (!file) return;
            stopAdminFaceStream();
            ensureAdminFaceModels();

            const video = document.getElementById('admin-face-video');
            const img = document.getElementById('admin-face-img-preview');
            const placeholder = document.getElementById('admin-face-placeholder');
            const statusEl = document.getElementById('admin-face-status');

            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                img.classList.remove('hidden');
                video.classList.add('hidden');
                placeholder.classList.add('hidden');
                adminFaceMode = 'upload';
                statusEl.textContent = 'Foto dipilih. Klik Deteksi Wajah & Simpan untuk memproses.';
            };
            reader.readAsDataURL(file);
        }

        async function captureAndSaveAdminFace() {
            if (!currentFaceStudentId) return;
            const ready = await ensureAdminFaceModels();
            if (!ready) return;

            const statusEl = document.getElementById('admin-face-status');
            const btnSave = document.getElementById('btn-admin-save-face');
            const video = document.getElementById('admin-face-video');
            const img = document.getElementById('admin-face-img-preview');

            let sourceEl = null;
            if (adminFaceMode === 'camera' && !video.classList.contains('hidden')) {
                sourceEl = video;
            } else if (adminFaceMode === 'upload' && !img.classList.contains('hidden')) {
                sourceEl = img;
            } else {
                statusEl.textContent = 'Aktifkan kamera atau pilih file foto terlebih dahulu!';
                return;
            }

            btnSave.disabled = true;
            statusEl.textContent = 'Mendeteksi titik biometrik wajah...';

            try {
                const detection = await faceapi
                    .detectSingleFace(sourceEl, new faceapi.TinyFaceDetectorOptions({ inputSize: 416, scoreThreshold: 0.45 }))
                    .withFaceLandmarks()
                    .withFaceDescriptor();

                if (!detection) {
                    statusEl.textContent = 'Wajah tidak terdeteksi jelas! Pastikan hanya 1 wajah menghadap depan dengan terang.';
                    btnSave.disabled = false;
                    return;
                }

                // Ambil snapshot base64
                const canvas = document.createElement('canvas');
                canvas.width = 480;
                canvas.height = 360;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(sourceEl, 0, 0, canvas.width, canvas.height);
                const photoBase64 = canvas.toDataURL('image/jpeg', 0.85);
                const descriptorArray = Array.from(detection.descriptor);

                statusEl.textContent = 'Menyimpan Face ID ke server...';

                const res = await fetch(`/students/${currentFaceStudentId}/face/store`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        descriptor: JSON.stringify(descriptorArray),
                        face_descriptor: JSON.stringify(descriptorArray),
                        photo: photoBase64,
                        face_photo_base64: photoBase64
                    })
                });

                const data = await res.json();
                if (res.ok && (data.success || data.status === 'success')) {
                    closeFaceModal();
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        background: '#021124',
                        color: '#fff'
                    }).then(() => window.location.reload());
                } else {
                    statusEl.textContent = data.message || 'Gagal menyimpan Face ID.';
                }
            } catch (e) {
                console.error(e);
                statusEl.textContent = 'Terjadi kesalahan saat memproses wajah.';
            } finally {
                btnSave.disabled = false;
            }
        }

        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.open-face-modal');
            if (!btn) return;

            currentFaceStudentId = btn.dataset.studentId;
            const name = btn.dataset.studentName;
            const nisn = btn.dataset.studentNisn;
            const className = btn.dataset.studentClass;
            const faceStatus = btn.dataset.faceStatus;
            const hasFace = btn.dataset.hasFace === '1';
            const photoUrl = btn.dataset.photoUrl;
            const rejectionNote = btn.dataset.rejectionNote || '';

            document.getElementById('face-modal-student-name').textContent = name;
            document.getElementById('face-modal-student-meta').textContent = `NISN: ${nisn} • Kelas: ${className}`;
            document.getElementById('face-modal-rejection-note').value = rejectionNote;

            // Set form actions
            document.getElementById('face-verify-form').action = `/students/${currentFaceStudentId}/face/verify`;
            document.getElementById('face-reset-form').action = `/students/${currentFaceStudentId}/face/reset`;

            // Photo preview
            const imgEl = document.getElementById('face-modal-current-photo');
            const noPhotoEl = document.getElementById('face-modal-no-photo');
            if (photoUrl) {
                imgEl.src = photoUrl;
                imgEl.classList.remove('hidden');
                noPhotoEl.classList.add('hidden');
            } else {
                imgEl.src = '';
                imgEl.classList.add('hidden');
                noPhotoEl.classList.remove('hidden');
            }

            // Status Badge & Verify Box
            const badgeEl = document.getElementById('face-modal-status-badge');
            const descEl = document.getElementById('face-modal-status-desc');
            const verifyBox = document.getElementById('face-modal-verify-box');

            if (hasFace && faceStatus === 'verified') {
                badgeEl.className = 'px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-emerald-500/20 text-emerald-300 border border-emerald-500/30';
                badgeEl.textContent = 'Terverifikasi & Aktif';
                descEl.textContent = 'Face ID siswa ini sudah terverifikasi oleh Wali Kelas/Admin dan aktif digunakan untuk absensi di Kiosk & Scan. Rekam mandiri di Portal Siswa terkunci.';
                verifyBox.classList.add('hidden');
            } else if (hasFace && faceStatus === 'pending') {
                badgeEl.className = 'px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-amber-500/20 text-amber-300 border border-amber-500/30';
                badgeEl.textContent = 'Menunggu Verifikasi (Tetap Aktif)';
                descEl.textContent = 'Siswa telah merekam Face ID mandiri di Portal Siswa. Saat ini Face ID SUDAH AKTIF digunakan untuk absen, namun menunggu verifikasi kesesuaian foto dari Wali Kelas/Admin.';
                verifyBox.classList.remove('hidden');
            } else if (faceStatus === 'rejected') {
                badgeEl.className = 'px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-rose-500/20 text-rose-300 border border-rose-500/30';
                badgeEl.textContent = 'Ditolak / Direset (Kunci Terbuka)';
                descEl.textContent = 'Foto/Face ID sebelumnya telah dihapus oleh Admin/Guru. Kunci perekaman di Portal Siswa saat ini TERBUKA agar siswa dapat merekam ulang wajah yang benar.';
                verifyBox.classList.add('hidden');
            } else if (hasFace) {
                badgeEl.className = 'px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-emerald-500/20 text-emerald-300 border border-emerald-500/30';
                badgeEl.textContent = 'Terdaftar & Aktif';
                descEl.textContent = 'Data Face ID biometrik siswa sudah terdaftar dan aktif digunakan untuk absensi di Kiosk & Scan.';
                verifyBox.classList.add('hidden');
            } else {
                badgeEl.className = 'px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-white/10 text-slate-300 border border-white/15';
                badgeEl.textContent = 'Belum Rekam Face ID';
                descEl.textContent = 'Siswa belum merekam data Face ID. Siswa dapat merekam mandiri melalui Portal Siswa atau direkam langsung oleh Admin/Guru di bawah ini.';
                verifyBox.classList.add('hidden');
            }

            document.getElementById('face-recognition-modal').classList.remove('hidden');
        });
    </script>
</x-app-layout>