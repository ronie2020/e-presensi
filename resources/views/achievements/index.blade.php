<x-app-layout>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-8 sm:py-10 font-sans text-elevate-text bg-slate-50 min-h-screen">
        
        {{-- HERO SECTION MICROSOFT ELEVATE THEME --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10">
            <div class="relative rounded-[2rem] bg-elevate-gradient-main p-8 sm:p-10 text-elevate-dark shadow-xl shadow-elevate-accent/10 overflow-hidden border border-white/60 group">
                <div class="absolute -top-10 -left-10 w-48 h-48 bg-elevate-primary/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-3xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-elevate-peach/20 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-2xl"></div>
                <div class="absolute top-10 right-32 w-24 h-24 bg-white/40 rounded-2xl rotate-45 pointer-events-none shadow-sm"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                    <div class="max-w-2xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/50 border border-white/60 text-elevate-dark text-[10px] font-bold uppercase tracking-widest mb-4 backdrop-blur-sm shadow-sm">
                            <i class="ph-fill ph-trophy"></i> Modul Kesiswaan
                        </div>
                        <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-3 flex items-center gap-3 text-elevate-dark leading-tight">
                            Prestasi & Penghargaan
                        </h1>
                        <p class="text-elevate-dark/80 text-sm md:text-base font-medium leading-relaxed max-w-lg">
                            Rekam jejak kejuaraan siswa dan guru. Kelola data prestasi akademik maupun non-akademik untuk arsip sekolah. Laporan dari siswa akan muncul di sini untuk diverifikasi.
                        </p>
                    </div>
                    <div class="flex flex-row md:flex-col lg:flex-row gap-4 w-full md:w-auto">
                        <div class="bg-white/60 backdrop-blur-md px-6 py-5 rounded-[2rem] border border-white flex-1 md:flex-none min-w-[140px] text-center md:text-left shadow-sm">
                            <div class="flex items-center justify-center md:justify-start gap-2 mb-1 text-elevate-primary">
                                <i class="ph-duotone ph-medal text-lg"></i>
                                <span class="text-[10px] font-bold uppercase tracking-wider">Total Prestasi</span>
                            </div>
                            <span class="block text-3xl font-black text-elevate-dark tracking-tight">{{ $achievements->total() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition class="mb-8 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-emerald-100 rounded-full text-emerald-600">
                            <i class="ph-bold ph-check-circle text-xl"></i>
                        </div>
                        <span class="font-bold text-sm">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 p-1 rounded-md hover:bg-emerald-100 transition"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                {{-- KOLOM KIRI (1/3): FORM INPUT --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden sticky top-24 relative group hover:border-elevate-accent/30 transition-all duration-300">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-elevate-accent to-elevate-primary"></div>
                        
                        <div class="p-6 md:p-8 border-b border-slate-50 flex items-center gap-4">
                            <div class="w-12 h-12 bg-elevate-accent/10 text-elevate-primary rounded-2xl flex items-center justify-center text-2xl shadow-sm border border-elevate-accent/20">
                                <i class="ph-fill ph-plus-circle"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-elevate-dark leading-none">Input Prestasi</h3>
                                <p class="text-xs text-slate-400 font-bold mt-1 uppercase tracking-wider">Catat pencapaian baru</p>
                            </div>
                        </div>

                        <div class="p-6 md:p-8 relative z-10 pt-4">
                            <form action="{{ route('achievements.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" 
                                  x-data="{ type: '{{ old('type', 'Siswa') }}', imgPreview: null }">
                                @csrf
                                
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Pemenang</label>
                                    <div class="grid grid-cols-3 gap-1 p-1 bg-slate-50 rounded-xl border border-slate-200">
                                        <button type="button" @click="type = 'Siswa'" :class="type === 'Siswa' ? 'bg-white text-elevate-dark shadow-sm ring-1 ring-black/5' : 'text-slate-400 hover:text-elevate-dark'" class="py-2.5 rounded-lg text-xs font-bold transition-all duration-200">Siswa</button>
                                        <button type="button" @click="type = 'Guru'" :class="type === 'Guru' ? 'bg-white text-elevate-dark shadow-sm ring-1 ring-black/5' : 'text-slate-400 hover:text-elevate-dark'" class="py-2.5 rounded-lg text-xs font-bold transition-all duration-200">Guru</button>
                                        <button type="button" @click="type = 'Sekolah'" :class="type === 'Sekolah' ? 'bg-white text-elevate-dark shadow-sm ring-1 ring-black/5' : 'text-slate-400 hover:text-elevate-dark'" class="py-2.5 rounded-lg text-xs font-bold transition-all duration-200">Sekolah</button>
                                    </div>
                                    <input type="hidden" name="type" x-model="type">
                                </div>

                                <div x-show="type === 'Siswa'" x-transition x-cloak>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Nama Siswa</label>
                                    <div class="relative">
                                        <i class="ph-bold ph-student absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <select name="student_id" class="w-full pl-11 pr-10 py-3 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-elevate-primary focus:ring-elevate-primary font-bold text-elevate-dark transition-colors appearance-none cursor-pointer text-sm">
                                            <option value="">-- Pilih Siswa --</option>
                                            @foreach($students as $student)
                                                <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                                    {{ $student->name }} ({{ $student->schoolClass->name ?? '-' }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                    </div>
                                    @error('student_id') <p class="text-xs text-rose-500 mt-1 font-bold">{{ $message }}</p> @enderror
                                </div>
                                
                                <div x-show="type !== 'Siswa'" x-transition x-cloak style="display: none;">
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Nama Pemenang / Tim</label>
                                    <div class="relative">
                                        <i class="ph-bold ph-users absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="text" name="name_manual" value="{{ old('name_manual') }}" placeholder="Contoh: Tim Futsal Guru" class="w-full pl-11 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-elevate-primary focus:ring-elevate-primary font-bold text-elevate-dark py-3 transition-colors text-sm">
                                    </div>
                                    @error('name_manual') <p class="text-xs text-rose-500 mt-1 font-bold">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Judul Prestasi</label>
                                    <div class="relative">
                                        <i class="ph-bold ph-medal absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="text" name="title" value="{{ old('title') }}" required placeholder="Juara 1 Lomba..." class="w-full pl-11 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-elevate-primary focus:ring-elevate-primary font-bold text-elevate-dark py-3 transition-colors text-sm">
                                    </div>
                                    @error('title') <p class="text-xs text-rose-500 mt-1 font-bold">{{ $message }}</p> @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Tingkat</label>
                                        <div class="relative">
                                            <select name="level" class="w-full pl-3 pr-8 py-3 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-elevate-primary focus:ring-elevate-primary font-bold text-elevate-dark text-xs appearance-none">
                                                @foreach(['Sekolah', 'Kecamatan', 'Kabupaten', 'Provinsi', 'Nasional', 'Internasional'] as $lvl)
                                                    <option value="{{ $lvl }}" {{ old('level') == $lvl ? 'selected' : '' }}>{{ $lvl }}</option>
                                                @endforeach
                                            </select>
                                            <i class="ph-bold ph-caret-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Tanggal</label>
                                        <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" class="w-full px-3 py-3 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-elevate-primary focus:ring-elevate-primary font-bold text-elevate-dark text-xs">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Dokumentasi Foto</label>
                                    <div class="relative group">
                                        <input type="file" name="photo" accept="image/*" @change="if($event.target.files.length > 0) { imgPreview = URL.createObjectURL($event.target.files[0]) } else { imgPreview = null }" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                        <div class="border-2 border-dashed border-slate-300 rounded-2xl p-4 text-center transition-all group-hover:border-elevate-primary group-hover:bg-elevate-accent/5" :class="{'border-elevate-primary bg-elevate-accent/5': imgPreview}">
                                            <div x-show="!imgPreview" class="space-y-2">
                                                <i class="ph-duotone ph-image text-3xl text-slate-300 group-hover:text-elevate-primary transition-colors"></i>
                                                <p class="text-[10px] text-slate-400 font-bold">Upload Foto</p>
                                            </div>
                                            <div x-show="imgPreview" style="display: none;">
                                                <img :src="imgPreview" class="h-24 w-full object-cover rounded-xl mx-auto">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Link Video (Opsional)</label>
                                        <div class="relative">
                                            <i class="ph-bold ph-youtube-logo absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                            <input type="url" name="video_link" value="{{ old('video_link') }}" placeholder="https://..." class="w-full pl-11 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-elevate-primary focus:ring-elevate-primary font-bold text-elevate-dark py-3 transition-colors text-xs">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Sertifikat (Opsional)</label>
                                        <div class="relative">
                                            <input type="file" name="certificate" accept=".pdf,image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-2.5 file:px-3 file:rounded-xl file:border-0 file:font-bold file:bg-elevate-accent/10 file:text-elevate-primary bg-slate-50 rounded-2xl border border-slate-200 hover:file:bg-elevate-accent/20 transition-colors cursor-pointer hover:border-elevate-primary focus:bg-white">
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="w-full py-3.5 px-4 bg-elevate-dark text-white font-bold rounded-2xl hover:bg-elevate-primary transition-all shadow-lg shadow-elevate-dark/30 flex items-center justify-center gap-2 transform active:scale-[0.98]">
                                    <i class="ph-bold ph-floppy-disk text-lg"></i>
                                    Simpan Data
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN (2/3): DAFTAR PRESTASI --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden flex flex-col h-full min-h-[600px]">
                        
                        <div class="p-8 border-b border-slate-50 bg-slate-50/30 flex flex-col sm:flex-row gap-4 justify-between items-center">
                            <div class="flex items-center gap-3">
                                <h2 class="text-lg font-black text-elevate-dark flex items-center gap-2">
                                    <i class="ph-fill ph-list-dashes text-elevate-primary"></i> Riwayat Prestasi
                                </h2>
                                <span class="bg-white border border-slate-200 text-[10px] font-black px-3 py-1.5 rounded-xl text-slate-500 shadow-sm">
                                    {{ $achievements->count() }} Data
                                </span>
                            </div>

                            <div class="flex gap-3 w-full sm:w-auto">
                                <form method="GET" class="relative flex-1 sm:w-64 group">
                                    <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-elevate-primary transition-colors"></i>
                                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari prestasi..." class="w-full pl-11 pr-4 py-2.5 rounded-xl border-slate-200 bg-white focus:bg-white focus:border-elevate-primary focus:ring-elevate-primary text-sm font-bold transition-colors shadow-sm text-elevate-dark">
                                </form>
                                <a href="{{ route('achievements.export', request()->all()) }}" target="_blank" class="px-4 py-2.5 bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white border border-emerald-200 rounded-xl text-xs font-bold transition-all shadow-sm flex items-center gap-2">
                                    <i class="ph-bold ph-microsoft-excel-logo text-lg"></i>
                                    <span class="hidden sm:inline">Export</span>
                                </a>
                            </div>
                        </div>
                        
                        <div class="overflow-x-auto flex-1 custom-scrollbar">
                            <table class="w-full text-left text-sm text-slate-600">
                                <thead class="bg-slate-50 text-xs font-bold text-slate-400 uppercase tracking-wider">
                                    <tr>
                                        <th class="px-6 py-5">Info Juara</th>
                                        <th class="px-6 py-5">Prestasi</th>
                                        <th class="px-6 py-5">Tingkat</th>
                                        <th class="px-6 py-5 text-center">Status</th>
                                        <th class="px-6 py-5 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @forelse($achievements as $item)
                                        <tr class="group hover:bg-elevate-accent/5 transition-colors duration-200 {{ $item->status === 'pending' ? 'bg-amber-50/20' : ($item->status === 'rejected' ? 'bg-rose-50/20 opacity-70' : '') }}">
                                            <td class="px-6 py-5">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-xs shrink-0 shadow-sm border border-white
                                                        {{ $item->type == 'Siswa' ? 'bg-elevate-accent/20 text-elevate-primary' : ($item->type == 'Guru' ? 'bg-emerald-100 text-emerald-600' : 'bg-orange-100 text-orange-600') }}">
                                                        @if($item->type == 'Siswa')
                                                            {{ substr($item->achiever_name, 0, 2) }}
                                                        @elseif($item->type == 'Guru')
                                                            <i class="ph-bold ph-chalkboard-teacher text-lg"></i>
                                                        @else
                                                            <i class="ph-bold ph-buildings text-lg"></i>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <div class="font-black text-elevate-dark text-sm line-clamp-1 group-hover:text-elevate-primary transition-colors">{{ $item->achiever_name }}</div>
                                                        <span class="inline-flex mt-0.5 px-2 py-0.5 rounded text-[10px] font-bold border
                                                            {{ $item->type == 'Siswa' ? 'bg-elevate-accent/10 text-elevate-primary border-elevate-accent/20' : ($item->type == 'Guru' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-orange-50 text-orange-600 border-orange-100') }}">
                                                            {{ strtoupper($item->type) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-5">
                                                <div class="font-bold text-elevate-dark text-sm mb-1 line-clamp-2 leading-snug">{{ $item->title }}</div>
                                                <div class="text-xs font-bold text-slate-400 mb-1.5"><i class="ph-bold ph-calendar-blank"></i> {{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}</div>
                                                
                                                <div class="flex flex-wrap items-center gap-3 mt-1.5">
                                                    @if($item->photo_path)
                                                        <a href="{{ asset('storage/' . $item->photo_path) }}" target="_blank" class="flex items-center gap-1 text-[10px] font-bold text-amber-500 hover:text-amber-700 hover:underline transition">
                                                            <i class="ph-fill ph-image text-sm"></i> Foto
                                                        </a>
                                                    @endif
                                                    @if($item->video_link)
                                                        <a href="{{ $item->video_link }}" target="_blank" class="flex items-center gap-1 text-[10px] font-bold text-rose-500 hover:text-rose-700 hover:underline transition">
                                                            <i class="ph-fill ph-youtube-logo text-sm"></i> Video
                                                        </a>
                                                    @endif
                                                    @if(!empty($item->certificate_path))
                                                        <a href="{{ asset('storage/' . $item->certificate_path) }}" target="_blank" class="flex items-center gap-1 text-[10px] font-bold text-elevate-primary hover:text-elevate-dark hover:underline transition">
                                                            <i class="ph-fill ph-certificate text-sm"></i> Sertifikat
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-5">
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[10px] font-black bg-slate-100 text-slate-600 border border-slate-200 uppercase tracking-wide">
                                                    <i class="ph-fill ph-map-pin text-slate-400"></i> {{ $item->level }}
                                                </span>
                                            </td>

                                            {{-- KOTAK STATUS --}}
                                            <td class="px-6 py-5 text-center">
                                                @if($item->status === 'pending')
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[10px] font-black bg-amber-50 text-amber-600 border border-amber-200 uppercase tracking-wide animate-pulse">
                                                        <i class="ph-fill ph-clock text-amber-500"></i> Pending
                                                    </span>
                                                @elseif($item->status === 'rejected')
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[10px] font-black bg-rose-50 text-rose-600 border border-rose-200 uppercase tracking-wide">
                                                        <i class="ph-fill ph-x-circle text-rose-500"></i> Ditolak
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[10px] font-black bg-emerald-50 text-emerald-600 border border-emerald-200 uppercase tracking-wide">
                                                        <i class="ph-fill ph-check-circle text-emerald-500"></i> Valid
                                                    </span>
                                                @endif
                                            </td>

                                            <td class="px-6 py-5 text-right">
                                                {{-- KOTAK AKSI (VERIFIKASI & HAPUS) --}}
                                                <div class="flex justify-end gap-2 {{ $item->status === 'pending' ? 'opacity-100' : 'opacity-0 group-hover:opacity-100' }} transition-opacity">
                                                    
                                                    {{-- TOMBOL VERIFIKASI (MUNCUL JIKA STATUS PENDING) --}}
                                                    @if($item->status === 'pending')
                                                        <!-- Tombol Setuju -->
                                                        <form action="{{ route('achievements.verify', $item->id) }}" method="POST">
                                                            @csrf @method('PATCH')
                                                            <input type="hidden" name="status" value="approved">
                                                            <button type="submit" class="w-9 h-9 rounded-xl flex items-center justify-center bg-emerald-50 border border-emerald-200 text-emerald-600 hover:bg-emerald-600 hover:text-white transition-all shadow-sm" title="Terima & Beri Poin Kebaikan">
                                                                <i class="ph-bold ph-check text-lg"></i>
                                                            </button>
                                                        </form>

                                                        <!-- Tombol Tolak -->
                                                        <form action="{{ route('achievements.verify', $item->id) }}" method="POST" onsubmit="return confirm('Tolak laporan prestasi siswa ini?');">
                                                            @csrf @method('PATCH')
                                                            <input type="hidden" name="status" value="rejected">
                                                            <button type="submit" class="w-9 h-9 rounded-xl flex items-center justify-center bg-rose-50 border border-rose-200 text-rose-600 hover:bg-rose-600 hover:text-white transition-all shadow-sm" title="Tolak Laporan">
                                                                <i class="ph-bold ph-x text-lg"></i>
                                                            </button>
                                                        </form>
                                                    @endif

                                                    {{-- TAMBAHAN: TOMBOL EDIT --}}
                                                    <a href="{{ route('achievements.edit', $item->id) }}" class="w-9 h-9 rounded-xl flex items-center justify-center bg-elevate-accent/10 border border-elevate-accent/20 text-elevate-primary hover:bg-elevate-primary hover:text-white transition-all shadow-sm" title="Edit Data">
                                                        <i class="ph-bold ph-pencil-simple text-lg"></i>
                                                    </a>

                                                    <!-- Tombol Hapus Biasa -->
                                                    <form action="{{ route('achievements.destroy', $item->id) }}" method="POST" 
                                                          onsubmit="event.preventDefault(); 
                                                                    const form = this;
                                                                    Swal.fire({
                                                                        title: 'Hapus Prestasi?',
                                                                        text: 'Yakin ingin menghapus data prestasi ini?',
                                                                        icon: 'warning',
                                                                        showCancelButton: true,
                                                                        confirmButtonColor: '#e11d48',
                                                                        cancelButtonColor: '#94a3b8',
                                                                        confirmButtonText: 'Ya, Hapus!',
                                                                        cancelButtonText: 'Batal',
                                                                        reverseButtons: true,
                                                                        customClass: {
                                                                            popup: 'rounded-[2.5rem] font-sans border-0 shadow-2xl',
                                                                            confirmButton: 'bg-rose-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-rose-700 transition-colors mx-2 shadow-lg shadow-rose-900/20',
                                                                            cancelButton: 'bg-slate-100 text-slate-600 px-6 py-3 rounded-xl font-bold hover:bg-slate-200 transition-colors mx-2'
                                                                        },
                                                                        buttonsStyling: false
                                                                    }).then((result) => {
                                                                        if (result.isConfirmed) form.submit();
                                                                    });">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="w-9 h-9 rounded-xl flex items-center justify-center bg-white border border-slate-200 text-slate-400 hover:text-rose-500 hover:border-rose-200 hover:bg-rose-50 transition-all shadow-sm" title="Hapus Data">
                                                            <i class="ph-bold ph-trash text-lg"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="py-20 text-center">
                                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 mb-4 shadow-sm border border-slate-100">
                                                    <i class="ph-duotone ph-trophy text-4xl text-slate-300"></i>
                                                </div>
                                                <h3 class="text-elevate-dark font-bold text-lg">Belum ada data prestasi</h3>
                                                <p class="text-slate-500 text-sm mt-1">Silakan input prestasi pertama sekolah di formulir sebelah kiri.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="px-6 py-4 border-t border-slate-50 bg-slate-50/30">
                            {{ $achievements->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>