<x-app-layout>
    <div class="py-6 sm:py-8">
        
        {{-- Header --}}
        <div class="mb-8 px-4 sm:px-0">
            <h1 class="text-3xl font-black text-gray-800 tracking-tight leading-tight">
                Galeri Kegiatan Sekolah
            </h1>
            <p class="text-gray-500 mt-1">
                Kelola konten aktifitas yang akan tampil di Halaman Depan (Landing Page).
            </p>
        </div>

        {{-- Flash Message --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" class="mb-6 mx-4 sm:mx-0 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button @click="show = false">&times;</button>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 px-4 sm:px-0">
            
            {{-- KOLOM KIRI: FORM INPUT --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl shadow-lg shadow-indigo-100/50 border border-indigo-50 overflow-hidden sticky top-6">
                    <div class="p-6 relative z-10">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center text-2xl shadow-sm border border-indigo-100">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-gray-800">Tambah Kegiatan</h3>
                                <p class="text-xs text-gray-500">Publikasikan aktifitas terbaru</p>
                            </div>
                        </div>

                        <form action="{{ route('school-activities.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                            @csrf
                            
                            {{-- Judul --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1 ml-1">Judul Kegiatan</label>
                                <input type="text" name="title" required placeholder="Contoh: Perkemahan Sabtu Minggu" class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm font-bold text-gray-700 placeholder-gray-300 py-2.5">
                                @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            {{-- Deskripsi --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1 ml-1">Deskripsi Singkat</label>
                                <textarea name="description" required rows="4" placeholder="Jelaskan secara singkat kegiatan ini..." class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm text-gray-700 placeholder-gray-300"></textarea>
                                @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            {{-- Upload Foto --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1 ml-1">Foto Utama (Thumbnail)</label>
                                <div class="relative group">
                                    <input type="file" name="photo" accept="image/*" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-all border border-gray-200 rounded-xl cursor-pointer">
                                </div>
                                <p class="text-[10px] text-gray-400 mt-1 ml-1">*Format: JPG, PNG. Max: 2MB.</p>
                                @error('photo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            {{-- Link Video --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1 ml-1">Link Video (Opsional)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <input type="url" name="video_url" placeholder="https://youtube.com/..." class="w-full pl-9 rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm text-gray-700 placeholder-gray-300 py-2.5">
                                </div>
                                <p class="text-[10px] text-gray-400 mt-1 ml-1">*Jika diisi, ikon Play akan muncul di gambar.</p>
                            </div>

                            <div class="pt-2">
                                <button type="submit" class="w-full py-3.5 px-4 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200 flex items-center justify-center gap-2 group">
                                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    Publikasikan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: PREVIEW LIST (MIRIP LANDING PAGE) --}}
            <div class="lg:col-span-2">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Preview Tampilan ({{ $activities->count() }})</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($activities as $activity)
                        <!-- Card Item -->
                        <div class="group bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-xl border border-gray-100 flex flex-col relative transition-all duration-300">
                            
                            <!-- Tombol Hapus (Overlay) -->
                            <div class="absolute top-3 right-3 z-30 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                <form action="{{ route('school-activities.destroy', $activity->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kegiatan ini?');">
                                    @csrf @method('DELETE')
                                    <button class="bg-white/90 backdrop-blur text-rose-500 p-2 rounded-full shadow-lg hover:bg-rose-500 hover:text-white transition-colors border border-rose-100" title="Hapus Kegiatan">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>

                            <!-- Area Gambar/Video -->
                            <div class="relative h-48 overflow-hidden bg-gray-100">
                                @if($activity->image_path)
                                    <img src="{{ asset('storage/' . $activity->image_path) }}" 
                                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-300">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif

                                <!-- Overlay Gradient -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-60"></div>

                                <!-- Indikator Video -->
                                @if($activity->video_url)
                                    <div class="absolute inset-0 flex items-center justify-center z-20">
                                        <div class="w-10 h-10 bg-white/30 backdrop-blur rounded-full flex items-center justify-center border border-white/50">
                                            <svg class="w-5 h-5 text-white ml-0.5" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z" /></svg>
                                        </div>
                                    </div>
                                    <div class="absolute top-3 left-3 z-20">
                                        <span class="px-2 py-0.5 bg-red-600 text-white text-[10px] font-bold uppercase rounded shadow-sm flex items-center gap-1">
                                            Video
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <!-- Konten Teks -->
                            <div class="p-5 flex-1 flex flex-col">
                                <h4 class="text-lg font-bold text-gray-800 mb-2 line-clamp-2 leading-tight">
                                    {{ $activity->title }}
                                </h4>
                                <p class="text-xs text-gray-500 leading-relaxed line-clamp-3 mb-4 flex-1">
                                    {{ $activity->description }}
                                </p>
                                
                                <div class="pt-3 border-t border-gray-50 flex items-center justify-between text-[10px] text-gray-400 font-bold uppercase tracking-wide">
                                    <span>{{ $activity->created_at->format('d M Y') }}</span>
                                    @if($activity->video_url)
                                        <a href="{{ $activity->video_url }}" target="_blank" class="text-indigo-500 hover:underline">Cek Link &rarr;</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 text-center py-12 bg-white rounded-3xl border-2 border-dashed border-gray-200">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <p class="text-gray-400 text-sm">Belum ada kegiatan yang diunggah.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $activities->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>