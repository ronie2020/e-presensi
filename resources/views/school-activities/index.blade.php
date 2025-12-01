<x-app-layout>
    <div class="py-6 sm:py-8">
        
        {{-- Header Page --}}
        <div class="mb-8 px-4 sm:px-0 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight leading-tight flex items-center gap-3">
                    <i class="ph-duotone ph-images-square text-indigo-600"></i> Galeri Kegiatan
                </h1>
                <p class="text-slate-500 mt-2 text-lg">
                    Kelola dokumentasi aktivitas sekolah untuk Halaman Depan.
                </p>
            </div>
            
            <div class="hidden md:block">
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-700 text-xs font-bold uppercase tracking-wide">
                    <i class="ph-bold ph-eye"></i> Live Preview
                </span>
            </div>
        </div>

        {{-- Flash Message --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 mx-4 sm:mx-0 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-emerald-100 rounded-full text-emerald-600">
                        <i class="ph-bold ph-check-circle text-xl"></i>
                    </div>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 p-1 rounded-md hover:bg-emerald-100 transition"><i class="ph-bold ph-x"></i></button>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 px-4 sm:px-0 items-start">
            
            {{-- KOLOM KIRI (1/3): FORM INPUT --}}
            <div class="lg:col-span-1 space-y-6">
                
                <div class="bg-white rounded-3xl shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] border border-slate-100 overflow-hidden sticky top-24">
                    <!-- Header Card -->
                    <div class="bg-gradient-to-r from-indigo-600 to-violet-600 p-6 text-white relative overflow-hidden">
                        <i class="ph-duotone ph-aperture absolute -right-4 -bottom-4 text-8xl text-white opacity-10 rotate-12"></i>
                        <h3 class="text-xl font-bold relative z-10">Upload Kegiatan</h3>
                        <p class="text-indigo-100 text-sm relative z-10">Bagikan momen terbaik sekolah.</p>
                    </div>

                    <div class="p-6">
                        <!-- Form dengan Alpine Data untuk Preview Gambar -->
                        <form action="{{ route('school-activities.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5" x-data="{ imgPreview: null }">
                            @csrf
                            
                            {{-- Judul --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Judul Kegiatan</label>
                                <input type="text" name="title" required placeholder="Contoh: Perkemahan Sabtu Minggu" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 font-bold text-slate-800 py-3 transition-colors placeholder:font-normal">
                                @error('title') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            {{-- Deskripsi --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Deskripsi Singkat</label>
                                <textarea name="description" required rows="3" placeholder="Ceritakan sedikit tentang kegiatan ini..." class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 text-sm text-slate-700 placeholder:font-normal p-3"></textarea>
                                @error('description') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            {{-- Upload Foto (Dengan Preview) --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Foto Utama</label>
                                
                                <div class="relative group">
                                    <input type="file" name="photo" accept="image/*" 
                                        @change="imgPreview = URL.createObjectURL($event.target.files[0])"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">
                                    
                                    <div class="border-2 border-dashed border-slate-200 rounded-xl p-4 text-center transition-all group-hover:border-indigo-400 group-hover:bg-indigo-50"
                                         :class="{'border-indigo-400 bg-indigo-50': imgPreview}">
                                        
                                        <!-- State: Belum ada gambar -->
                                        <div x-show="!imgPreview" class="space-y-2 py-2">
                                            <div class="mx-auto w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-white group-hover:text-indigo-500 transition-colors">
                                                <i class="ph-bold ph-camera text-xl"></i>
                                            </div>
                                            <p class="text-xs text-slate-500"><span class="font-bold text-indigo-600">Klik untuk upload</span> foto</p>
                                            <p class="text-[10px] text-slate-400">JPG/PNG, Max 2MB</p>
                                        </div>

                                        <!-- State: Sudah ada gambar (Preview) -->
                                        <div x-show="imgPreview" class="relative h-40 w-full rounded-lg overflow-hidden shadow-sm" style="display: none;">
                                            <img :src="imgPreview" class="h-full w-full object-cover">
                                            <div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-colors"></div>
                                            <div class="absolute bottom-2 right-2 bg-black/50 text-white text-[10px] px-2 py-1 rounded backdrop-blur-sm">
                                                Ganti Foto
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @error('photo') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            {{-- Link Video --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Link Video (Opsional)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                        <i class="ph-bold ph-youtube-logo"></i>
                                    </div>
                                    <input type="url" name="video_url" placeholder="https://youtube.com/..." class="w-full pl-10 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 text-sm text-slate-700 placeholder:font-normal py-3">
                                </div>
                            </div>

                            <button type="submit" class="w-full py-3.5 px-4 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/30 flex items-center justify-center gap-2 transform active:scale-[0.98]">
                                <i class="ph-bold ph-paper-plane-right"></i>
                                <span>Publikasikan</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN (2/3): PREVIEW LIST --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-6 border-b border-slate-50 flex items-center justify-between">
                        <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                            <i class="ph-duotone ph-list-dashes text-indigo-500"></i> Daftar Kegiatan
                        </h3>
                        <span class="bg-slate-100 text-[10px] font-bold px-2.5 py-1 rounded-full text-slate-500 border border-slate-200">
                            Total: {{ $activities->total() ?? 0 }}
                        </span>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @forelse($activities as $activity)
                                <!-- Card Item -->
                                <div class="group bg-white rounded-2xl overflow-hidden border border-slate-200 hover:border-indigo-300 hover:shadow-lg hover:shadow-indigo-500/5 transition-all duration-300 flex flex-col relative">
                                    
                                    <!-- Area Gambar/Video -->
                                    <div class="relative h-48 w-full bg-slate-100 overflow-hidden">
                                        @if($activity->image_path)
                                            <img src="{{ asset('storage/' . $activity->image_path) }}" 
                                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                        @else
                                            <div class="w-full h-full flex flex-col items-center justify-center text-slate-300 bg-slate-50">
                                                <i class="ph-duotone ph-image text-4xl mb-2"></i>
                                                <span class="text-xs font-medium">Tidak ada foto</span>
                                            </div>
                                        @endif

                                        <!-- Overlay Gradient -->
                                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent opacity-60"></div>

                                        <!-- Badge Video -->
                                        @if($activity->video_url)
                                            <div class="absolute top-3 left-3 z-20">
                                                <span class="px-2 py-1 bg-red-600/90 backdrop-blur text-white text-[10px] font-bold uppercase rounded-lg shadow-sm flex items-center gap-1">
                                                    <i class="ph-fill ph-play-circle"></i> Video
                                                </span>
                                            </div>
                                        @endif

                                        <!-- Tombol Hapus (Muncul saat Hover) -->
                                        <div class="absolute top-3 right-3 z-30 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                            <form action="{{ route('school-activities.destroy', $activity->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kegiatan ini?');">
                                                @csrf @method('DELETE')
                                                <button class="bg-white/90 backdrop-blur text-slate-400 p-2 rounded-lg shadow-sm hover:bg-rose-50 hover:text-rose-600 transition-colors border border-white/50" title="Hapus Kegiatan">
                                                    <i class="ph-bold ph-trash text-lg"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Konten Teks -->
                                    <div class="p-5 flex-1 flex flex-col">
                                        <div class="mb-3">
                                            <h4 class="text-lg font-bold text-slate-800 mb-2 line-clamp-2 leading-tight group-hover:text-indigo-600 transition-colors">
                                                {{ $activity->title }}
                                            </h4>
                                            <p class="text-xs text-slate-500 leading-relaxed line-clamp-3">
                                                {{ $activity->description }}
                                            </p>
                                        </div>
                                        
                                        <div class="mt-auto pt-4 border-t border-slate-50 flex items-center justify-between">
                                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wide flex items-center gap-1.5">
                                                <i class="ph-fill ph-calendar-blank"></i>
                                                {{ $activity->created_at->format('d M Y') }}
                                            </span>
                                            
                                            @if($activity->video_url)
                                                <a href="{{ $activity->video_url }}" target="_blank" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1 hover:underline">
                                                    Tonton <i class="ph-bold ph-arrow-square-out"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-1 md:col-span-2 py-16 text-center bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200">
                                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-3 text-slate-300 shadow-sm">
                                        <i class="ph-duotone ph-image-broken text-3xl"></i>
                                    </div>
                                    <h4 class="text-slate-600 font-bold">Belum ada kegiatan</h4>
                                    <p class="text-xs text-slate-400 mt-1">Mulai dengan mengupload foto kegiatan sekolah.</p>
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
</x-app-layout>