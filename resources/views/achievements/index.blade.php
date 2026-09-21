<x-app-layout>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-8 sm:py-10 font-sans text-slate-100 bg-[#020b18] min-h-screen">
        
        {{-- HERO SECTION --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 sm:mb-10">
            <x-hero-section
                badge="MODUL KESISWAAN"
                badgeIcon="ph-fill ph-trophy"
                showcaseIcon="ph-duotone ph-medal"
                showcaseTitle="Total Prestasi"
                showcaseSubtitle="Arsip Kejuaraan">
                <x-slot:title>
                    <span class="block text-slate-100">Rekam Jejak</span>
                    <span class="block mt-1 sm:mt-1.5 text-transparent bg-clip-text bg-gradient-to-r from-[#56bbf1] via-sky-200 to-[#38bdf8]">
                        Prestasi & Penghargaan
                    </span>
                </x-slot:title>
                <x-slot:description>
                    Rekam jejak kejuaraan siswa dan guru. Kelola data prestasi akademik maupun non-akademik untuk arsip dan verifikasi sekolah.
                </x-slot:description>
                <x-slot:chips>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                        <i class="ph-bold ph-star text-amber-400"></i> Akademik & Seni
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                        <i class="ph-bold ph-shield-check text-emerald-400"></i> Verifikasi Siswa
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                        <i class="ph-bold ph-certificate text-sky-400"></i> E-Sertifikat
                    </span>
                </x-slot:chips>
                <x-slot:showcaseStats>
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-slate-900/80 border border-slate-700/80 backdrop-blur-md shadow-inner">
                        <i class="ph-fill ph-medal text-amber-400 text-base"></i>
                        <span class="text-xs font-bold text-slate-300 tracking-wide">Total:</span>
                        <span class="text-sm font-black text-white font-mono">{{ $achievements->total() }}</span>
                        <span class="text-xs text-slate-400 font-medium">Penghargaan</span>
                    </div>
                </x-slot:showcaseStats>
            </x-hero-section>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition class="mb-8 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 rounded-2xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-emerald-500/20 rounded-full text-emerald-400">
                            <i class="ph-bold ph-check-circle text-xl"></i>
                        </div>
                        <span class="font-bold text-sm">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-400 hover:text-emerald-200 p-1 rounded-md hover:bg-emerald-500/20 transition"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                {{-- KOLOM KIRI (1/3): FORM INPUT --}}
                <div class="lg:col-span-1">
                    <div class="bg-gradient-to-br from-[#031d3d]/90 via-[#021124]/95 to-[#031d3d]/90 backdrop-blur-xl rounded-[2.5rem] shadow-2xl border border-white/10 overflow-hidden sticky top-24 relative group hover:border-[#56bbf1]/30 transition-all duration-300">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-[#0d52a1] via-sky-500 to-[#56bbf1]"></div>
                        
                        <div class="p-6 md:p-8 border-b border-white/10 flex items-center gap-4">
                            <div class="w-12 h-12 bg-[#56bbf1]/10 text-[#56bbf1] rounded-2xl flex items-center justify-center text-2xl shadow-inner border border-[#56bbf1]/20">
                                <i class="ph-fill ph-plus-circle"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-white leading-none">Input Prestasi</h3>
                                <p class="text-xs text-slate-400 font-bold mt-1 uppercase tracking-wider">Catat pencapaian baru</p>
                            </div>
                        </div>

                        <div class="p-6 md:p-8 relative z-10 pt-4">
                            <form action="{{ route('achievements.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" 
                                  x-data="{ type: '{{ old('type', 'Siswa') }}', imgPreview: null }">
                                @csrf
                                
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Pemenang</label>
                                    <div class="grid grid-cols-3 gap-1 p-1 bg-[#021124]/80 rounded-xl border border-white/10">
                                        <button type="button" @click="type = 'Siswa'" :class="type === 'Siswa' ? 'bg-gradient-to-r from-[#0d52a1] to-[#56bbf1] text-white shadow-md' : 'text-slate-400 hover:text-white'" class="py-2.5 rounded-lg text-xs font-bold transition-all duration-200">Siswa</button>
                                        <button type="button" @click="type = 'Guru'" :class="type === 'Guru' ? 'bg-gradient-to-r from-[#0d52a1] to-[#56bbf1] text-white shadow-md' : 'text-slate-400 hover:text-white'" class="py-2.5 rounded-lg text-xs font-bold transition-all duration-200">Guru</button>
                                        <button type="button" @click="type = 'Sekolah'" :class="type === 'Sekolah' ? 'bg-gradient-to-r from-[#0d52a1] to-[#56bbf1] text-white shadow-md' : 'text-slate-400 hover:text-white'" class="py-2.5 rounded-lg text-xs font-bold transition-all duration-200">Sekolah</button>
                                    </div>
                                    <input type="hidden" name="type" x-model="type">
                                </div>

                                <div x-show="type === 'Siswa'" x-transition x-cloak>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Nama Siswa</label>
                                    <div class="relative">
                                        <i class="ph-bold ph-student absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <select name="student_id" class="w-full pl-11 pr-10 py-3 rounded-2xl border-white/15 bg-[#021124]/90 focus:bg-[#031d3d] focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] font-bold text-white transition-colors appearance-none cursor-pointer text-sm">
                                            <option value="" class="bg-[#021124] text-white">-- Pilih Siswa --</option>
                                            @foreach($students as $student)
                                                <option value="{{ $student->id }}" class="bg-[#021124] text-white" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                                    {{ $student->name }} ({{ $student->schoolClass->name ?? '-' }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                    </div>
                                    @error('student_id') <p class="text-xs text-rose-400 mt-1 font-bold">{{ $message }}</p> @enderror
                                </div>
                                
                                <div x-show="type !== 'Siswa'" x-transition x-cloak style="display: none;">
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Nama Pemenang / Tim</label>
                                    <div class="relative">
                                        <i class="ph-bold ph-users absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="text" name="name_manual" value="{{ old('name_manual') }}" placeholder="Contoh: Tim Futsal Guru" class="w-full pl-11 rounded-2xl border-white/15 bg-[#021124]/90 focus:bg-[#031d3d] focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] font-bold text-white py-3 transition-colors text-sm placeholder:text-slate-500">
                                    </div>
                                    @error('name_manual') <p class="text-xs text-rose-400 mt-1 font-bold">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Judul Prestasi</label>
                                    <div class="relative">
                                        <i class="ph-bold ph-medal absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="text" name="title" value="{{ old('title') }}" required placeholder="Juara 1 Lomba..." class="w-full pl-11 rounded-2xl border-white/15 bg-[#021124]/90 focus:bg-[#031d3d] focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] font-bold text-white py-3 transition-colors text-sm placeholder:text-slate-500">
                                    </div>
                                    @error('title') <p class="text-xs text-rose-400 mt-1 font-bold">{{ $message }}</p> @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Tingkat</label>
                                        <div class="relative">
                                            <select name="level" class="w-full pl-3 pr-8 py-3 rounded-2xl border-white/15 bg-[#021124]/90 focus:bg-[#031d3d] focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] font-bold text-white text-xs appearance-none">
                                                @foreach(['Sekolah', 'Kecamatan', 'Kabupaten', 'Provinsi', 'Nasional', 'Internasional'] as $lvl)
                                                    <option value="{{ $lvl }}" class="bg-[#021124] text-white" {{ old('level') == $lvl ? 'selected' : '' }}>{{ $lvl }}</option>
                                                @endforeach
                                            </select>
                                            <i class="ph-bold ph-caret-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Tanggal</label>
                                        <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" class="w-full px-3 py-3 rounded-2xl border-white/15 bg-[#021124]/90 focus:bg-[#031d3d] focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] font-bold text-white text-xs [color-scheme:dark]">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Dokumentasi Foto</label>
                                    <div class="relative group">
                                        <input type="file" name="photo" accept="image/*" @change="if($event.target.files.length > 0) { imgPreview = URL.createObjectURL($event.target.files[0]) } else { imgPreview = null }" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                        <div class="border-2 border-dashed border-white/15 rounded-2xl p-4 text-center transition-all group-hover:border-[#56bbf1] group-hover:bg-[#56bbf1]/5" :class="{'border-[#56bbf1] bg-[#56bbf1]/5': imgPreview}">
                                            <div x-show="!imgPreview" class="space-y-2">
                                                <i class="ph-duotone ph-image text-3xl text-slate-400 group-hover:text-[#56bbf1] transition-colors"></i>
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
                                            <input type="url" name="video_link" value="{{ old('video_link') }}" placeholder="https://..." class="w-full pl-11 rounded-2xl border-white/15 bg-[#021124]/90 focus:bg-[#031d3d] focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] font-bold text-white py-3 transition-colors text-xs placeholder:text-slate-500">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Sertifikat (Opsional)</label>
                                        <div class="relative">
                                            <input type="file" name="certificate" accept=".pdf,image/*" class="w-full text-xs text-slate-400 file:mr-3 file:py-2.5 file:px-3 file:rounded-xl file:border-0 file:font-bold file:bg-[#56bbf1]/10 file:text-[#56bbf1] bg-[#021124]/90 rounded-2xl border border-white/15 hover:file:bg-[#56bbf1]/20 transition-colors cursor-pointer hover:border-[#56bbf1]">
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="w-full py-3.5 px-4 bg-gradient-to-r from-[#0d52a1] via-sky-600 to-[#56bbf1] text-white font-bold rounded-2xl hover:brightness-110 transition-all shadow-lg shadow-sky-950/50 flex items-center justify-center gap-2 transform active:scale-[0.98]">
                                    <i class="ph-bold ph-floppy-disk text-lg"></i>
                                    Simpan Data
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN (2/3): DAFTAR PRESTASI --}}
                <div class="lg:col-span-2">
                    <div class="bg-gradient-to-br from-[#031d3d]/90 via-[#021124]/95 to-[#031d3d]/90 backdrop-blur-xl rounded-[2.5rem] shadow-2xl border border-white/10 overflow-hidden flex flex-col h-full min-h-[600px]">
                        
                        <div class="p-6 md:p-8 border-b border-white/10 bg-white/[0.02] flex flex-col sm:flex-row gap-4 justify-between items-center">
                            <div class="flex items-center gap-3">
                                <h2 class="text-lg font-black text-white flex items-center gap-2">
                                    <i class="ph-fill ph-list-dashes text-[#56bbf1]"></i> Riwayat Prestasi
                                </h2>
                                <span class="bg-[#021124]/80 border border-white/10 text-[10px] font-black px-3 py-1.5 rounded-xl text-sky-300 shadow-sm">
                                    {{ $achievements->count() }} Data
                                </span>
                            </div>

                            <div class="flex gap-3 w-full sm:w-auto">
                                <form method="GET" class="relative flex-1 sm:w-64 group">
                                    <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-[#56bbf1] transition-colors"></i>
                                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari prestasi..." class="w-full pl-11 pr-4 py-2.5 rounded-xl border-white/15 bg-[#021124]/90 focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] text-sm font-bold transition-colors shadow-sm text-white placeholder:text-slate-500">
                                </form>
                                <a href="{{ route('achievements.export', request()->all()) }}" target="_blank" class="px-4 py-2.5 bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500 hover:text-white border border-emerald-500/20 rounded-xl text-xs font-bold transition-all shadow-sm flex items-center gap-2">
                                    <i class="ph-bold ph-microsoft-excel-logo text-lg"></i>
                                    <span class="hidden sm:inline">Export</span>
                                </a>
                            </div>
                        </div>
                        
                        <div class="overflow-x-auto flex-1 custom-scrollbar">
                            <table class="w-full text-left text-sm text-slate-300">
                                <thead class="bg-white/[0.03] text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-white/10">
                                    <tr>
                                        <th class="px-6 py-5">Info Juara</th>
                                        <th class="px-6 py-5">Prestasi</th>
                                        <th class="px-6 py-5">Tingkat</th>
                                        <th class="px-6 py-5 text-center">Status</th>
                                        <th class="px-6 py-5 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5">
                                    @forelse($achievements as $item)
                                        <tr class="group hover:bg-white/[0.03] transition-colors duration-200 {{ $item->status === 'pending' ? 'bg-amber-500/5' : ($item->status === 'rejected' ? 'bg-rose-500/5 opacity-70' : '') }}">
                                            <td class="px-6 py-5">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-xs shrink-0 shadow-sm border border-white/10
                                                        {{ $item->type == 'Siswa' ? 'bg-sky-500/20 text-sky-300 border-sky-500/30' : ($item->type == 'Guru' ? 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30' : 'bg-amber-500/20 text-amber-300 border-amber-500/30') }}">
                                                        @if($item->type == 'Siswa')
                                                            {{ substr($item->achiever_name, 0, 2) }}
                                                        @elseif($item->type == 'Guru')
                                                            <i class="ph-bold ph-chalkboard-teacher text-lg"></i>
                                                        @else
                                                            <i class="ph-bold ph-buildings text-lg"></i>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <div class="font-black text-white text-sm line-clamp-1 group-hover:text-[#56bbf1] transition-colors">{{ $item->achiever_name }}</div>
                                                        <span class="inline-flex mt-0.5 px-2 py-0.5 rounded text-[10px] font-bold border
                                                            {{ $item->type == 'Siswa' ? 'bg-sky-500/10 text-sky-300 border-sky-500/20' : ($item->type == 'Guru' ? 'bg-emerald-500/10 text-emerald-300 border-emerald-500/20' : 'bg-amber-500/10 text-amber-300 border-amber-500/20') }}">
                                                            {{ strtoupper($item->type) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-5">
                                                <div class="font-bold text-white text-sm mb-1 line-clamp-2 leading-snug">{{ $item->title }}</div>
                                                <div class="text-xs font-bold text-slate-400 mb-1.5"><i class="ph-bold ph-calendar-blank text-[#56bbf1]"></i> {{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}</div>
                                                
                                                <div class="flex flex-wrap items-center gap-3 mt-1.5">
                                                    @if($item->photo_path)
                                                        <a href="{{ asset('storage/' . $item->photo_path) }}" target="_blank" class="flex items-center gap-1 text-[10px] font-bold text-amber-400 hover:text-amber-300 hover:underline transition">
                                                            <i class="ph-fill ph-image text-sm"></i> Foto
                                                        </a>
                                                    @endif
                                                    @if($item->video_link)
                                                        <a href="{{ $item->video_link }}" target="_blank" class="flex items-center gap-1 text-[10px] font-bold text-rose-400 hover:text-rose-300 hover:underline transition">
                                                            <i class="ph-fill ph-youtube-logo text-sm"></i> Video
                                                        </a>
                                                    @endif
                                                    @if(!empty($item->certificate_path))
                                                        <a href="{{ asset('storage/' . $item->certificate_path) }}" target="_blank" class="flex items-center gap-1 text-[10px] font-bold text-[#56bbf1] hover:text-sky-300 hover:underline transition">
                                                            <i class="ph-fill ph-certificate text-sm"></i> Sertifikat
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-5">
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[10px] font-black bg-white/5 text-slate-300 border border-white/10 uppercase tracking-wide">
                                                    <i class="ph-fill ph-map-pin text-[#56bbf1]"></i> {{ $item->level }}
                                                </span>
                                            </td>

                                            {{-- KOTAK STATUS --}}
                                            <td class="px-6 py-5 text-center">
                                                @if($item->status === 'pending')
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[10px] font-black bg-amber-500/15 text-amber-300 border border-amber-500/30 uppercase tracking-wide animate-pulse">
                                                        <i class="ph-fill ph-clock text-amber-400"></i> Pending
                                                    </span>
                                                @elseif($item->status === 'rejected')
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[10px] font-black bg-rose-500/15 text-rose-300 border border-rose-500/30 uppercase tracking-wide">
                                                        <i class="ph-fill ph-x-circle text-rose-400"></i> Ditolak
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[10px] font-black bg-emerald-500/15 text-emerald-300 border border-emerald-500/30 uppercase tracking-wide">
                                                        <i class="ph-fill ph-check-circle text-emerald-400"></i> Valid
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
                                                            <button type="submit" class="w-9 h-9 rounded-xl flex items-center justify-center bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 hover:bg-emerald-500 hover:text-white transition-all shadow-sm" title="Terima & Beri Poin Kebaikan">
                                                                <i class="ph-bold ph-check text-lg"></i>
                                                            </button>
                                                        </form>

                                                        <!-- Tombol Tolak -->
                                                        <form action="{{ route('achievements.verify', $item->id) }}" method="POST" onsubmit="return confirm('Tolak laporan prestasi siswa ini?');">
                                                            @csrf @method('PATCH')
                                                            <input type="hidden" name="status" value="rejected">
                                                            <button type="submit" class="w-9 h-9 rounded-xl flex items-center justify-center bg-rose-500/10 border border-rose-500/30 text-rose-400 hover:bg-rose-500 hover:text-white transition-all shadow-sm" title="Tolak Laporan">
                                                                <i class="ph-bold ph-x text-lg"></i>
                                                            </button>
                                                        </form>
                                                    @endif

                                                    {{-- TAMBAHAN: TOMBOL EDIT --}}
                                                    <a href="{{ route('achievements.edit', $item->id) }}" class="w-9 h-9 rounded-xl flex items-center justify-center bg-[#56bbf1]/10 border border-[#56bbf1]/30 text-[#56bbf1] hover:bg-[#56bbf1] hover:text-white transition-all shadow-sm" title="Edit Data">
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
                                                                            popup: 'rounded-[2.5rem] font-sans border border-white/10 bg-[#031d3d] text-white shadow-2xl',
                                                                            confirmButton: 'bg-rose-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-rose-700 transition-colors mx-2 shadow-lg shadow-rose-900/40',
                                                                            cancelButton: 'bg-white/10 text-slate-300 px-6 py-3 rounded-xl font-bold hover:bg-white/20 transition-colors mx-2'
                                                                        },
                                                                        buttonsStyling: false
                                                                    }).then((result) => {
                                                                        if (result.isConfirmed) form.submit();
                                                                    });">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="w-9 h-9 rounded-xl flex items-center justify-center bg-white/5 border border-white/10 text-slate-400 hover:text-rose-400 hover:border-rose-500/30 hover:bg-rose-500/10 transition-all shadow-sm" title="Hapus Data">
                                                            <i class="ph-bold ph-trash text-lg"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="py-20 text-center">
                                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-white/5 mb-4 shadow-sm border border-white/10">
                                                    <i class="ph-duotone ph-trophy text-4xl text-slate-400"></i>
                                                </div>
                                                <h3 class="text-white font-bold text-lg">Belum ada data prestasi</h3>
                                                <p class="text-slate-400 text-sm mt-1">Silakan input prestasi pertama sekolah di formulir sebelah kiri.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="px-6 py-4 border-t border-white/10 bg-white/[0.02]">
                            {{ $achievements->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>