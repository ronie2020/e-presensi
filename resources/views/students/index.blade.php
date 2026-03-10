<x-app-layout>
    {{-- TAMBAHAN WAJIB: Kumpulan CDN Script agar semua fitur UI, Modal, dan Ikon berfungsi --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/airbnb.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>

    <div class="py-8 sm:py-10 font-sans text-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HERO SECTION --}}
            <div class="relative rounded-[2rem] bg-gray-900 bg-gradient-to-br from-blue-900 via-blue-800 to-slate-900 p-8 mb-8 text-white shadow-2xl shadow-blue-900/40 overflow-hidden border border-white/10">
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="text-center md:text-left">
                        <a href="{{ route('dashboard') }}" class="group bg-white/10 hover:bg-white/20 text-white px-5 py-3 rounded-2xl font-bold text-sm backdrop-blur-sm border border-white/10 transition-all flex items-center gap-2 shadow-sm w-fit mb-4 mx-auto xl:mx-0">
                            <i class="ph-bold ph-arrow-left text-lg group-hover:-translate-x-1 transition-transform"></i>
                            <span>Kembali ke Dashboard</span>
                        </a>
                        <h1 class="text-3xl font-black tracking-tight mb-2 flex items-center justify-center md:justify-start gap-3">
                            <span class="text-4xl"></span> Data Induk Siswa
                        </h1>
                        <p class="text-blue-200 text-sm font-medium leading-relaxed max-w-lg">
                            Kelola data siswa, registrasi peserta didik baru, dan cetak kartu identitas dalam satu panel terintegrasi.
                        </p>
                    </div>
                    
                    {{-- Statistik Ringkas --}}
                    <div class="flex gap-3">
                        <div class="bg-blue-950/40 backdrop-blur-md px-5 py-3 rounded-2xl border border-blue-400/20 text-center min-w-[100px] shadow-lg">
                            <span class="block text-2xl font-black text-white">{{ $students->total() }}</span>
                            <span class="text-[10px] uppercase font-bold text-blue-300 tracking-wider">Total Siswa</span>
                        </div>
                        <div class="bg-blue-950/40 backdrop-blur-md px-5 py-3 rounded-2xl border border-blue-400/20 text-center min-w-[100px] shadow-lg">
                            <span class="block text-2xl font-black text-emerald-300">{{ $classes->count() }}</span>
                            <span class="text-[10px] uppercase font-bold text-emerald-200 tracking-wider">Rombel</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

                {{-- KOLOM KIRI: FORM & IMPORT --}}
                <div class="lg:col-span-1">
                    
                    {{-- WRAPPER STICKY --}}
                    <div class="sticky top-24 space-y-6">

                        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative">
                            {{-- Aksen Header Biru Tua --}}
                            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-900 to-blue-700"></div>
                            
                            <div class="p-6 md:p-8">
                                <div class="flex items-center gap-4 mb-6 pb-4 border-b border-slate-100">
                                    <div class="w-12 h-12 bg-blue-900 text-white rounded-2xl flex items-center justify-center text-2xl shadow-lg shadow-blue-900/20">
                                        <i class="ph-duotone ph-user-plus"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-black text-slate-800 leading-none">Registrasi Cepat</h3>
                                        <p class="text-xs text-blue-600 font-bold mt-1 uppercase tracking-wider">Input Siswa Baru</p>
                                    </div>
                                </div>

                                <form id="quick-register-form" action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5" x-data="{ photoPreview: null }">
                                    @csrf
                                    
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">NIS / NISN <span class="text-rose-500">*</span></label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-blue-900/50">
                                                <i class="ph-bold ph-identification-card"></i>
                                            </div>
                                            <input type="text" name="student_id" value="{{ old('student_id') }}" required placeholder="Nomor Induk"
                                                class="w-full pl-11 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm py-3 font-bold text-slate-700 transition-all placeholder:font-normal">
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-blue-900/50">
                                                <i class="ph-bold ph-user"></i>
                                            </div>
                                            <input type="text" name="name" value="{{ old('name') }}" required placeholder="Nama Sesuai Ijazah"
                                                class="w-full pl-11 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm py-3 font-bold text-slate-700 transition-all placeholder:font-normal">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Kelas <span class="text-rose-500">*</span></label>
                                            <div class="relative">
                                                <select name="class_id" required class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm py-3 font-bold text-slate-700 appearance-none px-4">
                                                    <option value="">Pilih</option>
                                                    @foreach ($classes as $class)
                                                        <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                                            {{ $class->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Gender <span class="text-rose-500">*</span></label>
                                            <div class="relative">
                                                <select name="gender" required class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm py-3 font-bold text-slate-700 appearance-none px-4">
                                                    <option value="L">Laki-laki</option>
                                                    <option value="P">Perempuan</option>
                                                </select>
                                                <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- FOTO --}}
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Foto (Opsional)</label>
                                        <div class="flex items-center gap-4 bg-slate-50 p-3 rounded-2xl border border-slate-100">
                                            <div class="shrink-0 w-12 h-12 rounded-xl bg-white border border-slate-200 overflow-hidden flex items-center justify-center shadow-sm">
                                                <template x-if="photoPreview">
                                                    <img :src="photoPreview" class="w-full h-full object-cover">
                                                </template>
                                                <template x-if="!photoPreview">
                                                    <i class="ph-duotone ph-camera text-slate-300 text-2xl"></i>
                                                </template>
                                            </div>
                                            <input type="file" name="photo" accept="image/*" 
                                                @change="const file = $event.target.files[0]; const reader = new FileReader(); reader.onload = (e) => { photoPreview = e.target.result }; reader.readAsDataURL(file)"
                                                class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 cursor-pointer"/>
                                        </div>
                                    </div>

                                    {{-- RFID & WA --}}
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">RFID (Opsional)</label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-scan"></i></div>
                                                <input type="text" name="rfid_id" value="{{ old('rfid_id') }}" placeholder="Scan..."
                                                    class="w-full pl-9 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm py-3 font-mono font-bold text-slate-700">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">WA Ortu</label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-emerald-500"><i class="ph-bold ph-whatsapp-logo"></i></div>
                                                <input type="text" name="parent_wa_number" value="{{ old('parent_wa_number') }}" placeholder="628..."
                                                    class="w-full pl-9 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm py-3 font-mono font-bold text-slate-700">
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="w-full py-3.5 bg-blue-900 text-white font-bold rounded-2xl hover:bg-blue-800 transition-all shadow-lg shadow-blue-900/30 flex items-center justify-center gap-2 transform active:scale-95 mt-4 group">
                                        <i class="ph-bold ph-floppy-disk text-lg group-hover:scale-110 transition-transform"></i>
                                        <span>Simpan Data</span>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-[2.5rem] border border-emerald-100 p-6 relative overflow-hidden group hover:shadow-lg transition-all">
                            <div class="relative z-10">
                                <h3 class="text-sm font-black text-emerald-900 mb-1 flex items-center gap-2">
                                    <i class="ph-bold ph-microsoft-excel-logo text-emerald-600 text-lg"></i> Import Massal
                                </h3>
                                <p class="text-[10px] text-emerald-700/70 mb-4 font-bold">Gunakan file Excel untuk input banyak data sekaligus.</p>
                                
                                <form id="import-form" action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data" class="flex gap-2 items-center">
                                    @csrf
                                    <label class="flex-1 cursor-pointer">
                                        <div class="bg-white border border-dashed border-emerald-300 rounded-xl py-3 px-4 text-center transition-all hover:border-emerald-500 hover:bg-emerald-50/50 truncate">
                                            <span class="text-xs font-bold text-emerald-600 truncate flex items-center justify-center gap-2">
                                                <i class="ph-bold ph-upload-simple"></i> Pilih File...
                                            </span>
                                        </div>
                                        <input type="file" name="file" id="file" required class="hidden" onchange="this.previousElementSibling.querySelector('span').innerHTML = '<i class=\'ph-bold ph-check\'></i> File Dipilih'">
                                    </label>
                                    <button type="submit" class="py-3 px-5 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition-colors text-xs shadow-md shadow-emerald-500/20 flex items-center gap-2">
                                        <span>Upload</span>
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- KOLOM KANAN: DAFTAR SISWA --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden h-full flex flex-col min-h-[800px]">
                        
                        {{-- Toolbar --}}
                        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col xl:flex-row flex-wrap gap-4 justify-between items-center">
                            <h3 class="font-black text-slate-800 text-lg flex items-center gap-2 w-full xl:w-auto shrink-0">
                                <i class="ph-fill ph-users text-blue-900"></i> Daftar Siswa
                            </h3>

                            <div class="flex flex-col sm:flex-row flex-wrap gap-3 w-full xl:w-auto justify-start xl:justify-end items-center">
                                <form action="{{ route('students.index') }}" method="GET" class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                                    <div class="relative flex-1 sm:w-48">
                                        <i class="ph-bold ph-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="text" name="search" placeholder="Cari nama / NISN..." value="{{ request('search') }}"
                                               class="w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 bg-white focus:border-blue-600 focus:ring-blue-600 text-xs font-bold text-slate-700 shadow-sm">
                                    </div>
                                    
                                    <select name="filter_class_id" onchange="this.form.submit()" class="rounded-xl border-slate-200 bg-white focus:border-blue-600 focus:ring-blue-600 text-xs font-bold text-slate-700 py-2.5 px-3 shadow-sm cursor-pointer w-full sm:w-32">
                                        <option value="">Semua Kelas</option>
                                        @foreach ($classes as $class)
                                            <option value="{{ $class->id }}" {{ request('filter_class_id') == $class->id ? 'selected' : '' }}>
                                                {{ $class->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <select name="filter_status" onchange="this.form.submit()" class="rounded-xl border-slate-200 bg-white focus:border-blue-600 focus:ring-blue-600 text-xs font-bold text-slate-700 py-2.5 px-3 shadow-sm cursor-pointer w-full sm:w-36">
                                        <option value="">Semua Status</option>
                                        <option value="lengkap" {{ request('filter_status') == 'lengkap' ? 'selected' : '' }}>Sudah Lengkap</option>
                                        <option value="belum_lengkap" {{ request('filter_status') == 'belum_lengkap' ? 'selected' : '' }}>Belum Lengkap</option>
                                    </select>
                                </form>

                                <div class="flex flex-wrap justify-end gap-2 w-full sm:w-auto mt-2 sm:mt-0">
                                    <a href="{{ route('students.export') }}" class="flex-1 sm:flex-none flex items-center justify-center px-4 py-2.5 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-xl hover:bg-emerald-100 transition-all shadow-sm font-bold text-xs gap-2 whitespace-nowrap">
                                        <i class="ph-bold ph-microsoft-excel-logo text-lg"></i> Export
                                    </a>
                                    
                                    <button type="button" id="btn-delete-selected" onclick="deleteSelected()" class="hidden flex-1 sm:flex-none flex items-center justify-center px-4 py-2.5 bg-rose-50 border border-rose-100 text-rose-700 rounded-xl hover:bg-rose-100 transition-all shadow-sm font-bold text-xs gap-2 whitespace-nowrap">
                                        <i class="ph-bold ph-trash text-lg"></i> Hapus (<span id="delete-selected-count">0</span>)
                                    </button>

                                    <button type="button" id="btn-print-selected" onclick="printSelectedCards()" class="hidden flex-1 sm:flex-none flex items-center justify-center px-4 py-2.5 bg-purple-50 border border-purple-100 text-purple-700 rounded-xl hover:bg-purple-100 transition-all shadow-sm font-bold text-xs gap-2 whitespace-nowrap">
                                        <i class="ph-bold ph-check-square-offset text-lg"></i> Cetak (<span id="print-selected-count">0</span>)
                                    </button>
                                    
                                    <button type="button" onclick="printBatchCards()" class="flex-1 sm:flex-none flex items-center justify-center px-4 py-2.5 bg-blue-50 border border-blue-100 text-blue-700 rounded-xl hover:bg-blue-100 transition-all shadow-sm font-bold text-xs gap-2 whitespace-nowrap">
                                        <i class="ph-bold ph-printer text-lg"></i> Cetak Kelas
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Table --}}
                        <div class="flex-1 overflow-x-auto custom-scrollbar relative pb-12">
                            <table class="w-full text-left border-collapse">
                                <thead class="bg-blue-900 text-blue-100 border-b border-blue-800 sticky top-0 z-20 shadow-sm">
                                    <tr>
                                        <th class="px-6 py-4 text-center w-10">
                                            <input type="checkbox" id="selectAll" class="rounded border-blue-700 bg-blue-800 text-blue-500 focus:ring-blue-500 cursor-pointer">
                                        </th>
                                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider w-1/3">Identitas Siswa</th>
                                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider w-1/6">Kelas</th>
                                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider w-1/6 text-center">Status Data</th>
                                        <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider w-1/6">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @forelse ($students as $student)
                                        <tr class="hover:bg-blue-50/50 transition-colors group">
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <input type="checkbox" value="{{ $student->id }}" class="student-checkbox rounded border-slate-300 text-blue-600 focus:ring-blue-600 cursor-pointer">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center gap-4">
                                                    <div class="relative shrink-0">
                                                        <div class="w-12 h-12 rounded-2xl overflow-hidden shadow-sm border border-slate-100 bg-white flex items-center justify-center group-hover:border-blue-200 transition-colors">
                                                            @if($student->photo_path)
                                                                <img src="{{ asset('storage/' . $student->photo_path) }}" alt="{{ $student->name }}" class="w-full h-full object-cover" loading="lazy">
                                                            @else
                                                                <div class="font-black text-sm {{ $student->gender == 'L' ? 'text-blue-600' : 'text-pink-500' }}">{{ substr($student->name, 0, 2) }}</div>
                                                            @endif
                                                        </div>
                                                        @if($student->rfid_id)
                                                            <div class="absolute -bottom-1 -right-1 bg-emerald-500 text-white rounded-full p-1 border-2 border-white shadow-sm" title="RFID Connected">
                                                                <i class="ph-bold ph-wifi-high text-[10px] block"></i>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <div>
                                                        <div class="font-bold text-slate-800 text-sm group-hover:text-blue-700 transition-colors">{{ $student->name }}</div>
                                                        <div class="flex items-center gap-2 mt-1">
                                                            <span class="text-[10px] text-slate-500 font-mono bg-slate-100 px-1.5 py-0.5 rounded border border-slate-200">{{ $student->student_id }}</span>
                                                            <span class="text-[10px] font-bold {{ $student->gender == 'L' ? 'text-blue-500' : 'text-pink-500' }}">{{ $student->gender }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex px-3 py-1.5 rounded-xl text-xs font-bold bg-slate-50 text-slate-600 border border-slate-100 group-hover:bg-white group-hover:border-blue-100 transition-colors">
                                                    {{ $student->schoolClass->name ?? 'Unassigned' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                @php $isComplete = $student->pob && $student->dob && $student->address && $student->father_name; @endphp
                                                @if($isComplete)
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-600 border border-emerald-100 uppercase tracking-wide">
                                                        <i class="ph-fill ph-check-circle"></i> Lengkap
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-black bg-amber-50 text-amber-600 border border-amber-100 uppercase tracking-wide">
                                                        <i class="ph-fill ph-warning-circle"></i> Incomplete
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <div class="flex items-center justify-end gap-1">
                                                    <a href="{{ route('students.show', $student->id) }}" target="_blank" class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 flex items-center justify-center transition-all shadow-sm" title="Cetak Buku Induk">
                                                        <i class="ph-bold ph-printer text-lg"></i>
                                                    </a>

                                                    <a href="{{ route('students.card', $student->id) }}" target="_blank" class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-purple-600 hover:border-purple-200 hover:bg-purple-50 flex items-center justify-center transition-all shadow-sm" title="Cetak Kartu OSIS">
                                                        <i class="ph-bold ph-identification-card text-lg"></i>
                                                    </a>

                                                    <a href="{{ route('students.edit', array_merge(['student' => $student->id], request()->query())) }}" class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-amber-600 hover:border-amber-200 hover:bg-amber-50 flex items-center justify-center transition-all shadow-sm" title="Edit Data">
                                                        <i class="ph-bold ph-pencil-simple text-lg"></i>
                                                    </a>
                                                    
                                                    {{-- PERBAIKAN: Memindahkan @click.outside ke parent div agar menu tidak cepat tertutup --}}
                                                    <div x-data="{ open: false }" @click.outside="open = false" class="relative">
                                                        <button @click="open = !open" class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-blue-900 hover:border-blue-300 hover:bg-blue-50 flex items-center justify-center transition-all shadow-sm">
                                                            <i class="ph-bold ph-dots-three-vertical text-lg"></i>
                                                        </button>
                                                        
                                                        <div x-show="open" x-transition.origin.top.right class="absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-xl border border-slate-100 z-50 overflow-hidden py-1 ring-1 ring-black/5" style="display: none;">
                                                            
                                                            {{-- Tambahkan @click="open = false" agar menu otomatis nutup saat opsi dipilih --}}
                                                            <button type="button" @click="open = false" class="w-full text-left px-4 py-2.5 text-xs font-bold text-slate-600 hover:bg-slate-50 hover:text-blue-600 flex items-center gap-2 open-absen-modal transition-colors"
                                                                data-student-id="{{ $student->id }}" data-student-name="{{ $student->name }}">
                                                                <i class="ph-bold ph-user-check text-base"></i> Input Absen
                                                            </button>
                                                            
                                                            <button type="button" @click="open = false" class="w-full text-left px-4 py-2.5 text-xs font-bold text-slate-600 hover:bg-slate-50 hover:text-emerald-600 flex items-center gap-2 open-qr-modal transition-colors"
                                                                data-student-id="{{ $student->student_id }}" data-student-name="{{ $student->name }}">
                                                                <i class="ph-bold ph-qr-code text-base"></i> Lihat QR Code
                                                            </button>
                                                            
                                                            <div class="border-t border-slate-100 my-1"></div>
                                                            
                                                            <form action="{{ route('students.destroy', $student->id) }}" method="POST">
                                                                @csrf @method('DELETE')
                                                                <button type="button" class="w-full text-left px-4 py-2.5 text-xs font-bold text-rose-500 hover:bg-rose-50 flex items-center gap-2 transition-colors btn-delete-confirm" data-name="{{ $student->name }}">
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
                                                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                                                    <i class="ph-duotone ph-users-three text-4xl"></i>
                                                </div>
                                                <p class="text-sm font-bold text-slate-500">Belum ada data siswa ditemukan.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="p-6 border-t border-slate-100">
                            {{ $students->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL ABSEN --}}
    <div id="absen-manual-modal" class="fixed inset-0 bg-blue-900/60 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50 transition-opacity">
        <div class="relative top-20 mx-auto p-0 border-0 w-full max-w-md shadow-2xl rounded-[2rem] bg-white overflow-hidden">
            <div class="bg-blue-900 px-6 py-4 flex justify-between items-center">
                <h3 class="font-bold text-white text-lg">Input Absensi Manual</h3>
                <button type="button" id="absen-modal-close" class="text-white/70 hover:text-white text-2xl leading-none">&times;</button>
            </div>
            <form id="absen-manual-form" action="{{ route('reports.storeManual') }}" method="POST" class="p-6 space-y-5">
                @csrf
                <input type="hidden" name="student_id" id="absen-modal-student-id">
                <input type="hidden" name="attendance_type" value="Harian">
                
                <div class="text-center mb-2">
                    <p class="text-xs text-slate-400 uppercase font-bold tracking-wide">Siswa</p>
                    <h4 id="absen-modal-student-name" class="text-xl font-black text-slate-800">Nama Siswa</h4>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5">Tanggal</label>
                    <input type="text" name="date" value="{{ date('Y-m-d') }}" class="datepicker w-full rounded-xl border-slate-200 bg-slate-50 focus:ring-blue-600 focus:border-blue-600 font-bold text-slate-700" placeholder="dd/mm/yyyy">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5">Status Kehadiran</label>
                    <select name="status" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:ring-blue-600 focus:border-blue-600 font-bold text-slate-700">
                        <option value="Hadir">Hadir (Manual)</option>
                        <option value="Sakit">Sakit</option>
                        <option value="Izin">Izin</option>
                        <option value="Alfa">Alfa</option>
                        <option value="Terlambat">Terlambat</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5">Waktu Masuk</label>
                        <input type="time" name="time_in" value="{{ now()->format('H:i') }}" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:ring-blue-600 focus:border-blue-600 text-center font-mono font-bold text-slate-700">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5">Waktu Pulang</label>
                        <input type="time" name="time_out" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:ring-blue-600 focus:border-blue-600 text-center font-mono font-bold text-slate-700">
                    </div>
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5">Keterangan (Opsional)</label>
                    <textarea name="notes" rows="2" placeholder="Contoh: Datang terlambat karena ban bocor..." class="w-full rounded-xl border-slate-200 bg-slate-50 focus:ring-blue-600 focus:border-blue-600 text-sm font-medium"></textarea>
                </div>

                <button type="submit" class="w-full py-3 bg-blue-900 text-white font-bold rounded-xl hover:bg-blue-800 shadow-lg shadow-blue-900/30 transition-transform active:scale-95">Simpan Data</button>
            </form>
        </div>
    </div>

    {{-- MODAL QR CODE LOKAL --}}
    <div id="qr-code-modal" class="fixed inset-0 bg-blue-900/60 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50 transition-opacity">
        <div class="relative top-20 mx-auto p-0 border-0 w-full max-w-sm shadow-2xl rounded-[2rem] bg-white overflow-hidden text-center p-8">
            <h3 class="text-lg font-black text-slate-800 mb-1" id="qr-modal-student-name">QR Code</h3>
            <p class="text-xs text-slate-400 font-bold uppercase mb-6">Identitas Digital Siswa</p>
            <div class="bg-white p-4 border-2 border-dashed border-blue-200 rounded-2xl inline-block mb-6 relative group">
                <div class="absolute inset-0 bg-blue-50/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center rounded-2xl pointer-events-none">
                    <i class="ph-bold ph-download-simple text-blue-600 text-2xl"></i>
                </div>
                <canvas id="qr-modal-canvas" class="mx-auto"></canvas>
            </div>
            <div class="flex gap-3 justify-center">
                <button type="button" id="qr-modal-close" class="px-6 py-2.5 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 text-sm transition-colors">Tutup</button>
                <a id="qr-modal-download" href="#" download="qrcode.png" class="px-6 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-500/30 text-sm transition-transform active:scale-95 flex items-center gap-2">
                    <i class="ph-bold ph-download-simple"></i> Unduh
                </a>
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
            // 0. INISIALISASI FLATPICKR (Dibungkus pengecekan agar tidak error jika internet lambat)
            if (typeof flatpickr !== 'undefined') {
                flatpickr(".datepicker", {
                    altInput: true,
                    altFormat: "d/m/Y",
                    dateFormat: "Y-m-d",
                    locale: "id",
                    disableMobile: "true"
                });
            }

            // 1. FLASH MESSAGES (SUCCESS / ERROR)
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#1e3a8a',
                    timer: 3000,
                    timerProgressBar: true
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#e11d48',
                });
            @endif

            // 2. PREVENT DOUBLE SUBMIT LOADING STATE
            function disableSubmit(formId, btnText) {
                const form = document.getElementById(formId);
                if(form) {
                    form.addEventListener('submit', function() {
                        const btn = this.querySelector('button[type="submit"]');
                        if(btn) {
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
                        showCancelButton: true,
                        confirmButtonColor: '#e11d48',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        reverseButtons: true
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
                            foreground: '#1e3a8a',
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
            
            // Perbaikan menutup modal dengan aman jika di-klik di luar area
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
                    confirmButtonColor: '#1e3a8a',
                    customClass: { popup: 'rounded-3xl' }
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

        
        // FUNGSI JS HAPUS SATUAN (BARU)
        function confirmDelete(button) {
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            
            Swal.fire({
                title: 'Hapus Siswa?',
                text: `Data siswa "${name}" beserta riwayat absen akan dihapus permanen.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Cari form berdasarkan ID dan submit langsung
                    document.getElementById('form-delete-' + id).submit();
                }
            });
        }
        
        // FUNGSI JS HAPUS TERPILIH (MASSAL)
        function deleteSelected() {
            const checkboxes = document.querySelectorAll('.student-checkbox:checked');
            if (checkboxes.length === 0) return;

            const selectedIds = Array.from(checkboxes).map(cb => cb.value).join(',');
            const count = checkboxes.length;

            Swal.fire({
                title: 'Hapus Massal?',
                text: `Anda yakin ingin menghapus ${count} data siswa yang dipilih?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus Semua!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-batch-ids').value = selectedIds;
                    document.getElementById('form-delete-batch').submit();
                }
            });
        }
    </script>
</x-app-layout>