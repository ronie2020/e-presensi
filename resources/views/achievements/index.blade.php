<x-app-layout>
    {{-- Header Section --}}
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-2">
                {{-- UPDATED: Warna Icon Header --}}
                <div class="p-2 bg-blue-900 text-white rounded-lg shadow-sm">
                    <i class="ph-fill ph-trophy text-xl"></i>
                </div>
                <span class="text-sm font-bold text-blue-900 uppercase tracking-wider">Modul Kesiswaan</span>
            </div>
            <h1 class="text-3xl md:text-4xl font-black text-slate-800 tracking-tight">
                Prestasi & Penghargaan
            </h1>
            <p class="text-slate-500 mt-2 text-lg">
                Kelola data kejuaraan siswa dan guru di sini.
            </p>
        </div>
        
        {{-- Summary Card --}}
        <div class="flex gap-4">
            <div class="bg-white px-5 py-3 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-900 font-bold border border-blue-100">
                    <i class="ph-bold ph-medal text-xl"></i>
                </div>
                <div>
                    <div class="text-xs text-slate-400 font-bold uppercase">Total Data</div>
                    <div class="text-sm font-bold text-slate-800">{{ $achievements->total() ?? 0 }} Prestasi</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Flash Message --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-between shadow-sm animate-fade-in-down">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-emerald-100 rounded-full text-emerald-600">
                    <i class="ph-bold ph-check"></i>
                </div>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
            <button @click="show = false" class="text-emerald-400 hover:text-emerald-700"><i class="ph-bold ph-x"></i></button>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        {{-- KOLOM KIRI: FORM INPUT --}}
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden sticky top-24">
                
                {{-- Card Header --}}
                {{-- UPDATED: Gradient Biru Tua --}}
                <div class="bg-gradient-to-r from-blue-900 to-blue-700 p-6 text-white relative overflow-hidden">
                    <i class="ph-fill ph-medal absolute -right-4 -bottom-4 text-8xl text-white opacity-10 rotate-12"></i>
                    <h3 class="text-xl font-bold relative z-10">Input Prestasi</h3>
                    <p class="text-blue-100 text-sm relative z-10">Catat momen juara baru.</p>
                </div>

                <div class="p-6">
                    {{-- Form Input --}}
                    <form action="{{ route('achievements.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5" 
                          x-data="{ type: '{{ old('type', 'Siswa') }}', imgPreview: null }">
                        @csrf
                        
                        {{-- Tipe Juara Switcher --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Siapa yang Juara?</label>
                            <div class="grid grid-cols-3 gap-1 p-1 bg-slate-50 rounded-xl border border-slate-200">
                                <button type="button" 
                                    @click="type = 'Siswa'" 
                                    :class="type === 'Siswa' ? 'bg-white text-blue-900 shadow-sm ring-1 ring-black/5' : 'text-slate-400 hover:text-slate-600'"
                                    class="py-2 rounded-lg text-xs font-bold transition-all duration-200">
                                    Siswa
                                </button>
                                <button type="button" 
                                    @click="type = 'Guru'" 
                                    :class="type === 'Guru' ? 'bg-white text-blue-900 shadow-sm ring-1 ring-black/5' : 'text-slate-400 hover:text-slate-600'"
                                    class="py-2 rounded-lg text-xs font-bold transition-all duration-200">
                                    Guru
                                </button>
                                <button type="button" 
                                    @click="type = 'Sekolah'" 
                                    :class="type === 'Sekolah' ? 'bg-white text-blue-900 shadow-sm ring-1 ring-black/5' : 'text-slate-400 hover:text-slate-600'"
                                    class="py-2 rounded-lg text-xs font-bold transition-all duration-200">
                                    Sekolah
                                </button>
                            </div>
                            <input type="hidden" name="type" x-model="type">
                        </div>

                        {{-- Input Nama Siswa --}}
                        <div x-show="type === 'Siswa'" x-transition>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Pilih Siswa</label>
                            <div class="relative">
                                <select name="student_id" 
                                    class="w-full pl-3 pr-10 py-2.5 bg-slate-50 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 transition-all cursor-pointer appearance-none
                                    @error('student_id') border-red-500 bg-red-50 text-red-900 @else border-slate-200 focus:border-blue-600 @enderror">
                                    <option value="">-- Cari Nama Siswa --</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                            {{ $student->name }} ({{ $student->schoolClass->name ?? '-' }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-slate-400">
                                    <i class="ph-bold ph-caret-down text-xs"></i>
                                </div>
                            </div>
                            @error('student_id')
                                <p class="text-xs text-red-500 mt-1 font-bold">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        {{-- Input Manual --}}
                        <div x-show="type !== 'Siswa'" x-transition style="display: none;">
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nama Guru / Tim</label>
                            <input type="text" name="name_manual" 
                                value="{{ old('name_manual') }}"
                                placeholder="Contoh: Tim Robotik Guru" 
                                class="w-full px-3 py-2.5 bg-slate-50 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 transition-all
                                @error('name_manual') border-red-500 bg-red-50 @else border-slate-200 focus:border-blue-600 @enderror">
                            @error('name_manual')
                                <p class="text-xs text-red-500 mt-1 font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Judul & Kategori --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Judul Kejuaraan</label>
                            <input type="text" name="title" required 
                                value="{{ old('title') }}"
                                placeholder="Contoh: Juara 1 Lomba Web Design" 
                                class="w-full px-3 py-2.5 bg-slate-50 border rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-600 transition-all
                                @error('title') border-red-500 bg-red-50 @else border-slate-200 focus:border-blue-600 @enderror">
                            @error('title')
                                <p class="text-xs text-red-500 mt-1 font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tingkat</label>
                                <div class="relative">
                                    <select name="level" class="w-full pl-3 pr-8 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 transition-all appearance-none">
                                        @foreach(['Sekolah', 'Kecamatan', 'Kabupaten', 'Provinsi', 'Nasional', 'Internasional'] as $lvl)
                                            <option value="{{ $lvl }}" {{ old('level') == $lvl ? 'selected' : '' }}>{{ $lvl }}</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-slate-400">
                                        <i class="ph-bold ph-caret-down text-xs"></i>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tanggal</label>
                                <input type="date" name="date" 
                                    value="{{ old('date', date('Y-m-d')) }}"
                                    class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 transition-all text-slate-600">
                            </div>
                        </div>

                        {{-- Image Upload with Preview --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Foto Dokumentasi</label>
                            
                            <div class="relative group">
                                <input type="file" name="photo" accept="image/*" 
                                    @change="imgPreview = URL.createObjectURL($event.target.files[0])"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                
                                <div class="border-2 border-dashed rounded-xl p-4 text-center transition-all group-hover:border-blue-400 group-hover:bg-blue-50
                                     {{ $errors->has('photo') ? 'border-red-400 bg-red-50' : 'border-slate-200' }}"
                                     :class="{'border-blue-400 bg-blue-50': imgPreview}">
                                    
                                    <div x-show="!imgPreview" class="space-y-1">
                                        <i class="ph-duotone ph-cloud-arrow-up text-2xl group-hover:text-blue-600 transition-colors {{ $errors->has('photo') ? 'text-red-400' : 'text-slate-300' }}"></i>
                                        <p class="text-xs text-slate-500">Klik / geser foto ke sini</p>
                                    </div>

                                    <div x-show="imgPreview" class="relative h-32 w-full" style="display: none;">
                                        <img :src="imgPreview" class="h-full w-full object-cover rounded-lg">
                                        <button @click.prevent="imgPreview = null" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 w-6 h-6 flex items-center justify-center text-xs shadow-md z-20 hover:bg-red-600 transition-colors">
                                            <i class="ph-bold ph-x"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @error('photo')
                                <p class="text-xs text-red-500 mt-1 font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Link Video (Opsional)</label>
                            <input type="url" name="video_link" 
                                value="{{ old('video_link') }}"
                                placeholder="https://youtube.com/..." 
                                class="w-full px-3 py-2.5 bg-slate-50 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 transition-all
                                @error('video_link') border-red-500 bg-red-50 @else border-slate-200 focus:border-blue-600 @enderror">
                        </div>

                        {{-- Tombol Submit Biru --}}
                        <button type="submit" class="w-full py-3.5 px-4 bg-blue-900 text-white font-bold rounded-xl hover:bg-blue-800 transition-all shadow-lg shadow-blue-900/30 flex items-center justify-center gap-2 transform active:scale-[0.98]">
                            <i class="ph-bold ph-floppy-disk"></i>
                            <span>Simpan Prestasi</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: TABEL DATA --}}
        <div class="lg:col-span-8 space-y-6">
            
            {{-- Filter Bar --}}
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex flex-col sm:flex-row gap-4 justify-between items-center">
                <form method="GET" class="relative w-full sm:w-64">
                    <i class="ph-bold ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / juara..." class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 transition-all">
                </form>
                
                {{-- Tombol Export (AKTIF) --}}
                <a href="{{ route('achievements.export', request()->all()) }}" target="_blank" class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-blue-50 hover:text-blue-900 hover:border-blue-200 flex items-center gap-2 transition">
                    <i class="ph-bold ph-export"></i> Export Excel
                </a>
            </div>

            {{-- Table Area --}}
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-50 flex items-center gap-2">
                    <i class="ph-fill ph-clock-counter-clockwise text-blue-900 text-xl"></i>
                    <h3 class="font-bold text-slate-800 text-lg">Riwayat Prestasi Sekolah</h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            {{-- UPDATED: Header Table Biru Tua --}}
                            <tr class="bg-blue-900 text-xs font-extrabold text-blue-100 uppercase tracking-wider">
                                <th class="py-4 px-6 rounded-tl-2xl">Info Juara</th>
                                <th class="py-4 px-6">Prestasi</th>
                                <th class="py-4 px-6">Tingkat</th>
                                <th class="py-4 px-6 text-center">Tanggal</th>
                                <th class="py-4 px-6 text-right rounded-tr-2xl">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($achievements as $item)
                                <tr class="group hover:bg-blue-50/30 transition-colors duration-200">
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-3">
                                            {{-- Avatar Logic --}}
                                            <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-xs shrink-0 ring-2 ring-white shadow-sm
                                                {{ $item->type == 'Siswa' ? 'bg-blue-100 text-blue-600' : ($item->type == 'Guru' ? 'bg-emerald-100 text-emerald-600' : 'bg-orange-100 text-orange-600') }}">
                                                @if($item->type == 'Siswa')
                                                    {{ substr($item->achiever_name, 0, 2) }}
                                                @elseif($item->type == 'Guru')
                                                    <i class="ph-bold ph-chalkboard-teacher text-lg"></i>
                                                @else
                                                    <i class="ph-bold ph-buildings text-lg"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="font-bold text-slate-800 text-sm line-clamp-1 group-hover:text-blue-700 transition-colors">{{ $item->achiever_name }}</div>
                                                <div class="text-[10px] font-bold px-1.5 py-0.5 rounded inline-block mt-0.5
                                                    {{ $item->type == 'Siswa' ? 'bg-blue-50 text-blue-600 border border-blue-100' : ($item->type == 'Guru' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-orange-50 text-orange-600 border border-orange-100') }}">
                                                    {{ strtoupper($item->type) }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="font-bold text-slate-700 text-sm mb-1 line-clamp-2">{{ $item->title }}</div>
                                        @if($item->video_link)
                                            <a href="{{ $item->video_link }}" target="_blank" class="flex items-center gap-1 text-xs text-slate-400 hover:text-blue-600 hover:underline transition">
                                                <i class="ph-fill ph-video-camera"></i> Lihat Video
                                            </a>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200 group-hover:bg-white group-hover:border-blue-200 transition-colors">
                                            <i class="ph-fill ph-map-pin text-[10px]"></i> {{ $item->level }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <div class="text-xs font-bold text-slate-500">{{ \Carbon\Carbon::parse($item->date)->format('d M') }}</div>
                                        <div class="text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($item->date)->format('Y') }}</div>
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <div class="flex items-center justify-end gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                            <form action="{{ route('achievements.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data prestasi ini?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center bg-white border border-slate-200 text-slate-400 hover:text-rose-500 hover:border-rose-200 hover:bg-rose-50 transition-all shadow-sm" title="Hapus Data">
                                                    <i class="ph-bold ph-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center">
                                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 mb-4">
                                            <i class="ph-duotone ph-trophy text-3xl text-slate-300"></i>
                                        </div>
                                        <h3 class="text-slate-800 font-bold">Belum ada data prestasi</h3>
                                        <p class="text-slate-500 text-sm">Silakan input prestasi pertama sekolah di formulir sebelah kiri.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{-- Pagination --}}
                <div class="px-6 py-4 border-t border-slate-50">
                    {{ $achievements->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>