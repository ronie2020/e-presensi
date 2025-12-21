<x-app-layout>
    <div class="py-8 sm:py-10 font-sans text-slate-800">
        
        {{-- HERO SECTION --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10">
            <div class="relative rounded-[2.5rem] bg-gray-900 bg-gradient-to-br from-blue-900 via-blue-800 to-slate-900 p-8 sm:p-10 text-white shadow-2xl shadow-blue-900/40 overflow-hidden border border-white/10 group">
                
                {{-- Background Decorations --}}
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-80 h-80 bg-blue-500/20 rounded-full blur-3xl pointer-events-none group-hover:bg-blue-500/30 transition-all duration-700"></div>
                <div class="absolute bottom-0 right-20 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                    
                    {{-- Text Content --}}
                    <div class="max-w-2xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-200 text-[10px] font-bold uppercase tracking-widest mb-4 backdrop-blur-sm">
                            <i class="ph-fill ph-image"></i> Dokumentasi Sekolah
                        </div>
                        <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-3 flex items-center gap-3 text-white leading-tight">
                            Galeri Kegiatan
                        </h1>
                        <p class="text-blue-100/80 text-sm md:text-base font-medium leading-relaxed max-w-lg">
                            Abadikan dan publikasikan momen terbaik sekolah. Kelola foto dan video kegiatan untuk ditampilkan di halaman depan.
                        </p>
                    </div>
                    
                    {{-- Stats Cards --}}
                    <div class="flex flex-row md:flex-col lg:flex-row gap-4 w-full md:w-auto">
                        {{-- Stat 1: Total Galeri --}}
                        <div class="bg-white/10 backdrop-blur-md px-6 py-5 rounded-2xl border border-white/10 flex-1 md:flex-none min-w-[140px] text-center md:text-left hover:bg-white/15 transition-colors">
                            <div class="flex items-center justify-center md:justify-start gap-2 mb-1 text-blue-300">
                                <i class="ph-duotone ph-images-square text-lg"></i>
                                <span class="text-[10px] font-bold uppercase tracking-wider">Total Album</span>
                            </div>
                            <span class="block text-3xl font-black text-white tracking-tight">{{ $activities->total() }}</span>
                        </div>

                        {{-- Stat 2: Live Preview Link (Button Style) --}}
                        <a href="/" target="_blank" class="bg-indigo-500/20 backdrop-blur-md px-6 py-5 rounded-2xl border border-indigo-400/20 flex-1 md:flex-none min-w-[140px] text-center md:text-left hover:bg-indigo-500/30 transition-colors group/link">
                            <div class="flex items-center justify-center md:justify-start gap-2 mb-1 text-indigo-300">
                                <i class="ph-duotone ph-eye text-lg"></i>
                                <span class="text-[10px] font-bold uppercase tracking-wider">Halaman Depan</span>
                            </div>
                            <div class="flex items-center justify-center md:justify-start gap-1 text-white font-bold text-sm mt-2 group-hover/link:text-indigo-200">
                                <span>Lihat Web</span>
                                <i class="ph-bold ph-arrow-right"></i>
                            </div>
                        </a>
                    </div>

                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Flash Message --}}
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
                <div class="lg:col-span-1 space-y-6">
                    
                    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden sticky top-24 relative group hover:shadow-2xl hover:shadow-blue-900/10 transition-all duration-300">
                        
                        {{-- Card Header --}}
                        <div class="bg-gradient-to-r from-blue-900 to-blue-800 p-8 text-white relative overflow-hidden">
                            <div class="absolute -right-6 -bottom-6 text-white/5 text-9xl pointer-events-none transform rotate-12">
                                <i class="ph-fill ph-aperture"></i>
                            </div>
                            <h3 class="text-xl font-black relative z-10">Upload Kegiatan</h3>
                            <p class="text-blue-200 text-sm font-medium relative z-10 mt-1">Bagikan momen terbaik sekolah.</p>
                        </div>

                        <div class="p-8 relative z-10">
                            <!-- Form dengan Alpine Data untuk Preview Gambar -->
                            <form action="{{ route('school-activities.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" x-data="{ imgPreview: null }">
                                @csrf
                                
                                {{-- Judul --}}
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Judul Kegiatan</label>
                                    <input type="text" name="title" required placeholder="Contoh: Perkemahan Sabtu Minggu" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 font-bold text-slate-800 py-3 px-4 transition-colors placeholder:font-normal">
                                    @error('title') <span class="text-red-500 text-xs mt-1 block font-bold ml-1">{{ $message }}</span> @enderror
                                </div>

                                {{-- Deskripsi --}}
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Deskripsi Singkat</label>
                                    <textarea name="description" required rows="3" placeholder="Ceritakan sedikit tentang kegiatan ini..." class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm text-slate-700 placeholder:font-normal p-4 font-medium"></textarea>
                                    @error('description') <span class="text-red-500 text-xs mt-1 block font-bold ml-1">{{ $message }}</span> @enderror
                                </div>

                                {{-- Upload Foto (Dengan Preview) --}}
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Foto Utama</label>
                                    
                                    <div class="relative group">
                                        <input type="file" name="photo" accept="image/*" 
                                            @change="imgPreview = URL.createObjectURL($event.target.files[0])"
                                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">
                                        
                                        <div class="border-2 border-dashed border-slate-300 rounded-2xl p-6 text-center transition-all group-hover:border-blue-500 group-hover:bg-blue-50"
                                             :class="{'border-blue-500 bg-blue-50': imgPreview}">
                                            
                                            <!-- State: Belum ada gambar -->
                                            <div x-show="!imgPreview" class="space-y-3 py-2">
                                                <div class="mx-auto w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-white group-hover:text-blue-600 transition-colors shadow-sm">
                                                    <i class="ph-fill ph-camera text-2xl"></i>
                                                </div>
                                                <div>
                                                    <p class="text-xs font-bold text-slate-600"><span class="text-blue-600 underline">Upload</span> foto kegiatan</p>
                                                    <p class="text-[10px] text-slate-400 mt-1">JPG/PNG, Max 2MB</p>
                                                </div>
                                            </div>

                                            <!-- State: Sudah ada gambar (Preview) -->
                                            <div x-show="imgPreview" class="relative h-48 w-full rounded-xl overflow-hidden shadow-sm" style="display: none;">
                                                <img :src="imgPreview" class="h-full w-full object-cover">
                                                <div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-colors"></div>
                                                <div class="absolute bottom-3 right-3 bg-black/60 text-white text-[10px] font-bold px-3 py-1.5 rounded-lg backdrop-blur-sm shadow-lg flex items-center gap-1">
                                                    <i class="ph-bold ph-pencil-simple"></i> Ganti
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @error('photo') <span class="text-red-500 text-xs mt-1 block font-bold ml-1">{{ $message }}</span> @enderror
                                </div>

                                {{-- Link Video --}}
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Link Video (Opsional)</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-red-500">
                                            <i class="ph-fill ph-youtube-logo text-lg"></i>
                                        </div>
                                        <input type="url" name="video_url" placeholder="https://youtube.com/..." class="w-full pl-11 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm text-slate-700 font-bold placeholder:font-normal py-3 transition-colors">
                                    </div>
                                </div>

                                <button type="submit" class="w-full py-3.5 px-4 bg-blue-900 text-white font-bold rounded-xl hover:bg-blue-800 transition-all shadow-lg shadow-blue-900/30 flex items-center justify-center gap-2 transform active:scale-[0.98]">
                                    <i class="ph-bold ph-paper-plane-right text-lg"></i>
                                    <span>Publikasikan</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN (2/3): PREVIEW LIST --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                        
                        {{-- Toolbar --}}
                        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row gap-4 justify-between items-center">
                            <h3 class="font-black text-slate-800 text-lg flex items-center gap-2">
                                <i class="ph-fill ph-list-dashes text-blue-900"></i> Daftar Kegiatan
                            </h3>
                            <span class="bg-white border border-slate-200 text-[10px] font-black px-3 py-1.5 rounded-xl text-slate-500 shadow-sm">
                                Total: {{ $activities->total() ?? 0 }} Post
                            </span>
                        </div>

                        <div class="p-6 bg-slate-50/30 min-h-[500px]">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @forelse($activities as $activity)
                                    <!-- Card Item -->
                                    <div class="group bg-white rounded-[1.5rem] overflow-hidden border border-slate-100 hover:border-blue-200 hover:shadow-xl hover:shadow-blue-900/5 transition-all duration-300 flex flex-col relative h-full">
                                        
                                        <!-- Area Gambar/Video -->
                                        <div class="relative h-56 w-full bg-slate-100 overflow-hidden">
                                            @if($activity->image_path)
                                                <img src="{{ asset('storage/' . $activity->image_path) }}" 
                                                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                            @else
                                                <div class="w-full h-full flex flex-col items-center justify-center text-slate-300 bg-slate-50">
                                                    <i class="ph-duotone ph-image text-5xl mb-2 opacity-50"></i>
                                                    <span class="text-xs font-bold uppercase tracking-wide">Tidak ada foto</span>
                                                </div>
                                            @endif

                                            <!-- Overlay Gradient -->
                                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent opacity-80"></div>

                                            <!-- Badge Video -->
                                            @if($activity->video_url)
                                                <div class="absolute top-4 left-4 z-20">
                                                    <a href="{{ $activity->video_url }}" target="_blank" class="px-3 py-1.5 bg-red-600/90 backdrop-blur text-white text-[10px] font-bold uppercase rounded-lg shadow-lg flex items-center gap-1.5 hover:bg-red-500 transition-colors">
                                                        <i class="ph-fill ph-play-circle text-sm"></i> Tonton
                                                    </a>
                                                </div>
                                            @endif

                                            <!-- Tombol Hapus (Muncul saat Hover) -->
                                            <div class="absolute top-4 right-4 z-30 opacity-0 group-hover:opacity-100 transition-opacity duration-200 transform translate-x-2 group-hover:translate-x-0">
                                                <form action="{{ route('school-activities.destroy', $activity->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kegiatan ini?');">
                                                    @csrf @method('DELETE')
                                                    <button class="bg-white/90 backdrop-blur text-rose-500 p-2.5 rounded-xl shadow-lg hover:bg-rose-500 hover:text-white transition-all" title="Hapus Kegiatan">
                                                        <i class="ph-bold ph-trash text-lg"></i>
                                                    </button>
                                                </form>
                                            </div>

                                            <!-- Tanggal di atas gambar -->
                                            <div class="absolute bottom-4 left-4 z-20">
                                                <span class="text-[10px] font-black text-white uppercase tracking-widest flex items-center gap-1.5 bg-black/30 backdrop-blur-md px-3 py-1 rounded-lg border border-white/10">
                                                    <i class="ph-bold ph-calendar-blank"></i>
                                                    {{ $activity->created_at->format('d M Y') }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Konten Teks -->
                                        <div class="p-6 flex-1 flex flex-col">
                                            <div class="mb-4">
                                                <h4 class="text-lg font-black text-slate-800 mb-2 line-clamp-2 leading-tight group-hover:text-blue-700 transition-colors">
                                                    {{ $activity->title }}
                                                </h4>
                                                <p class="text-xs text-slate-500 leading-relaxed line-clamp-3 font-medium">
                                                    {{ $activity->description }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-span-1 md:col-span-2 py-20 text-center bg-white rounded-[2rem] border-2 border-dashed border-slate-200">
                                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300 shadow-sm">
                                            <i class="ph-duotone ph-image-broken text-4xl"></i>
                                        </div>
                                        <h4 class="text-slate-700 font-bold text-lg">Belum ada kegiatan</h4>
                                        <p class="text-sm text-slate-400 mt-1 max-w-xs mx-auto">Mulai dengan mengupload foto dokumentasi kegiatan sekolah di formulir samping.</p>
                                    </div>
                                @endforelse
                            </div>

                            {{-- Pagination --}}
                            <div class="mt-8">
                                {{ $activities->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>