<x-app-layout>
    {{-- Load SweetAlert --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- State AlpineJS untuk Modal Edit --}}
    <div class="py-6 sm:py-8 font-sans bg-[#020b18] text-slate-100 min-h-screen relative overflow-hidden"
         x-data="{ 
            editModalOpen: false, 
            editData: { id: '', title: '', description: '', video_url: '' },
            editFormAction: '',
            openEditModal(data, url) {
                this.editData = JSON.parse(JSON.stringify(data));
                this.editFormAction = url;
                this.editModalOpen = true;
                document.body.style.overflow = 'hidden';
            },
            closeEditModal() {
                this.editModalOpen = false;
                document.body.style.overflow = 'auto';
            }
         }">

        {{-- HERO SECTION --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 sm:mb-10 relative z-10">
            <x-hero-section
                badge="DOKUMENTASI SEKOLAH"
                badgeIcon="ph-fill ph-images-square"
                showcaseIcon="ph-duotone ph-camera"
                showcaseTitle="Total Dokumentasi"
                showcaseSubtitle="Arsip Aktif Sekolah">
                <x-slot:title>
                    <span class="block text-slate-100">Galeri & Dokumentasi</span>
                    <span class="block mt-1 sm:mt-1.5 text-transparent bg-clip-text bg-gradient-to-r from-[#56bbf1] via-sky-200 to-[#38bdf8]">
                        Kegiatan Sekolah
                    </span>
                </x-slot:title>
                <x-slot:description>
                    Abadikan dan publikasikan momen terbaik sekolah. Kelola foto dan video kegiatan untuk ditampilkan secara interaktif pada portal.
                </x-slot:description>
                <x-slot:chips>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                        <i class="ph-bold ph-image text-sky-400"></i> Multi Foto
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                        <i class="ph-bold ph-video-camera text-cyan-400"></i> Video Terintegrasi
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                        <i class="ph-bold ph-eye text-emerald-400"></i> Publikasi Online
                    </span>
                </x-slot:chips>
                <x-slot:cta>
                    <a href="{{ url('/activities') }}" target="_blank"
                       class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white font-bold text-sm shadow-lg shadow-sky-500/25 transition-all duration-300 hover:scale-[1.02] active:scale-[0.98]">
                        <i class="ph-bold ph-arrow-square-out text-lg"></i>
                        <span>Lihat Web Publik</span>
                    </a>
                </x-slot:cta>
                <x-slot:showcaseStats>
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-slate-900/80 border border-slate-700/80 backdrop-blur-md shadow-inner">
                        <i class="ph-fill ph-folders text-[#56bbf1] text-base"></i>
                        <span class="text-xs font-bold text-slate-300 tracking-wide">Album:</span>
                        <span class="text-sm font-black text-white font-mono">{{ $activities->total() ?? 0 }}</span>
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
                            icon: '{{ str_contains(session("success"), "Gagal") || str_contains(session("success"), "Error") ? "error" : "success" }}',
                            title: '{{ str_contains(session("success"), "Gagal") || str_contains(session("success"), "Error") ? "Oops..." : "Berhasil" }}',
                            text: "{{ session('success') }}",
                            timer: 4000,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end',
                            background: '#021124',
                            color: '#fff',
                            iconColor: '{{ str_contains(session("success"), "Gagal") || str_contains(session("success"), "Error") ? "#e11d48" : "#56bbf1" }}',
                            customClass: {
                                popup: 'rounded-2xl border border-white/10 shadow-xl bg-[#021124]'
                            }
                        });
                    });
                </script>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                {{-- KOLOM KIRI (1/3): FORM INPUT --}}
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] border border-white/10 overflow-hidden lg:sticky lg:top-24 relative shadow-2xl backdrop-blur-xl">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-[#56bbf1] to-sky-400"></div>
                        
                        <div class="p-6 md:p-8 relative z-10">
                            <div class="flex items-center gap-4 mb-6 pb-4 border-b border-white/10">
                                <div class="w-12 h-12 bg-[#56bbf1]/10 text-[#56bbf1] border border-[#56bbf1]/20 rounded-2xl flex items-center justify-center text-2xl shadow-sm shrink-0">
                                    <i class="ph-duotone ph-aperture"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-black text-white">Upload Kegiatan</h3>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mt-1">Publikasi Galeri</p>
                                </div>
                            </div>

                            <form action="{{ route('school-activities.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" x-data="{ imgPreviews: [] }">
                                @csrf
                                
                                <div>
                                    <label class="block text-[10px] font-black text-[#56bbf1] uppercase tracking-widest mb-2 ml-1">Judul Kegiatan</label>
                                    <div class="relative">
                                        <i class="ph-bold ph-text-t absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="text" name="title" value="{{ old('title') }}" required placeholder="Contoh: Perkemahan Sabtu Minggu" class="w-full pl-11 rounded-2xl border-white/10 bg-slate-900/80 focus:bg-slate-900 focus:border-[#56bbf1] focus:ring-[#56bbf1] font-bold text-white py-3.5 transition-colors placeholder:text-slate-500 placeholder:font-medium">
                                    </div>
                                    @error('title') <span class="text-rose-400 text-xs mt-1 block font-bold ml-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-[10px] font-black text-[#56bbf1] uppercase tracking-widest mb-2 ml-1">Deskripsi Singkat</label>
                                    <textarea name="description" required rows="3" placeholder="Ceritakan sedikit tentang kegiatan ini..." class="w-full rounded-2xl border-white/10 bg-slate-900/80 focus:bg-slate-900 focus:border-[#56bbf1] focus:ring-[#56bbf1] text-sm text-white placeholder:text-slate-500 placeholder:font-medium p-4 font-medium transition-colors">{{ old('description') }}</textarea>
                                    @error('description') <span class="text-rose-400 text-xs mt-1 block font-bold ml-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-[10px] font-black text-[#56bbf1] uppercase tracking-widest mb-2 ml-1">Foto Dokumentasi</label>
                                    <div class="relative group/upload">
                                        <input type="file" name="photos[]" accept="image/*" multiple
                                            @change="imgPreviews = []; Array.from($event.target.files).forEach(file => imgPreviews.push(URL.createObjectURL(file)))"
                                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" required>
                                        
                                        <div class="border-2 border-dashed border-white/10 rounded-2xl p-6 text-center transition-all group-hover/upload:border-[#56bbf1] group-hover/upload:bg-[#56bbf1]/5 bg-slate-900/40"
                                             :class="{'border-[#56bbf1] bg-[#56bbf1]/10': imgPreviews.length > 0}">
                                            
                                            <div x-show="imgPreviews.length === 0" class="space-y-3 py-2">
                                                <div class="mx-auto w-12 h-12 rounded-[1rem] bg-[#56bbf1]/10 flex items-center justify-center text-[#56bbf1] border border-[#56bbf1]/20 transition-colors shadow-sm">
                                                    <i class="ph-fill ph-images text-2xl"></i>
                                                </div>
                                                <div>
                                                    <p class="text-xs font-bold text-white"><span class="text-[#56bbf1] underline">Pilih banyak foto</span> sekaligus</p>
                                                    <p class="text-[10px] text-slate-400 mt-1 font-bold">Bisa blok lebih dari 1 file (Maks 10MB/foto)</p>
                                                </div>
                                            </div>

                                            <div x-show="imgPreviews.length > 0" class="w-full text-left" style="display: none;">
                                                <p class="text-[10px] font-black text-[#56bbf1] uppercase tracking-widest mb-2">Terpilih: <span x-text="imgPreviews.length"></span> Foto</p>
                                                <div class="grid grid-cols-3 gap-2">
                                                    <template x-for="(src, index) in imgPreviews" :key="index">
                                                        <div class="relative h-16 sm:h-20 rounded-lg overflow-hidden shadow-sm border border-white/10">
                                                            <img :src="src" class="h-full w-full object-cover">
                                                        </div>
                                                    </template>
                                                </div>
                                                <p class="text-[10px] text-slate-400 mt-3 text-center font-bold">Klik atau Drop area ini untuk mengganti foto</p>
                                            </div>
                                        </div>
                                    </div>
                                    @error('photos') <span class="text-rose-400 text-xs mt-1 block font-bold ml-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-[10px] font-black text-[#56bbf1] uppercase tracking-widest mb-2 ml-1">Link Video (Opsional)</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-rose-400">
                                            <i class="ph-fill ph-youtube-logo text-lg"></i>
                                        </div>
                                        <input type="url" name="video_url" value="{{ old('video_url') }}" placeholder="https://youtube.com/..." class="w-full pl-11 rounded-2xl border-white/10 bg-slate-900/80 focus:bg-slate-900 focus:border-[#56bbf1] focus:ring-[#56bbf1] text-sm text-white font-bold placeholder:text-slate-500 placeholder:font-medium py-3.5 transition-colors">
                                    </div>
                                </div>

                                <div class="pt-2">
                                    <button type="submit" class="w-full py-3.5 px-4 bg-[#56bbf1] text-slate-950 font-bold rounded-xl hover:bg-sky-400 transition-all shadow-lg shadow-[#56bbf1]/20 flex items-center justify-center gap-2 transform active:scale-95 border border-transparent">
                                        <i class="ph-bold ph-paper-plane-right text-lg"></i>
                                        <span>Publikasikan Album</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN (2/3): PREVIEW LIST --}}
                <div class="lg:col-span-2">
                    <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] border border-white/10 overflow-hidden flex flex-col h-full min-h-[600px] shadow-2xl backdrop-blur-xl">
                        
                        <div class="p-6 md:p-8 border-b border-white/10 bg-white/[0.02] flex flex-col sm:flex-row gap-4 justify-between items-center">
                            <h3 class="text-xl font-black text-white flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-900 text-[#56bbf1] flex items-center justify-center border border-white/10 shadow-sm">
                                    <i class="ph-bold ph-list-dashes text-xl"></i>
                                </div>
                                Daftar Kegiatan
                            </h3>
                            <span class="bg-[#56bbf1]/10 border border-[#56bbf1]/20 text-[10px] font-black px-3 py-1.5 rounded-full text-[#56bbf1] shadow-sm tracking-wider uppercase">
                                Total: {{ $activities->total() ?? 0 }} Post
                            </span>
                        </div>

                        <div class="p-6 md:p-8 flex-1">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @forelse($activities as $activity)
                                    <div class="group bg-slate-900/60 rounded-[1.5rem] overflow-hidden border border-white/10 hover:border-[#56bbf1]/40 transition-all duration-300 flex flex-col relative h-full">
                                        
                                        <div class="relative h-56 w-full bg-slate-950 overflow-hidden">
                                            @php
                                                $rawImage = $activity->image_path;
                                                $images = [];

                                                if (is_array($rawImage)) {
                                                    $images = $rawImage;
                                                } elseif (is_string($rawImage)) {
                                                    $decoded = json_decode($rawImage, true);
                                                    $images = (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) ? $decoded : [$rawImage];
                                                }
                                                
                                                $images = array_filter($images);
                                                $coverImage = !empty($images) ? array_values($images)[0] : null;
                                                $totalImages = count($images);
                                            @endphp

                                            @if($coverImage)
                                                <img src="{{ asset('storage/' . $coverImage) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                            @else
                                                <div class="w-full h-full flex flex-col items-center justify-center text-[#56bbf1] bg-slate-900">
                                                    <i class="ph-duotone ph-image text-5xl mb-2 opacity-50"></i>
                                                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Tidak ada foto</span>
                                                </div>
                                            @endif

                                            <div class="absolute inset-0 bg-gradient-to-t from-[#020b18]/90 via-transparent to-transparent opacity-80"></div>

                                            @if($totalImages > 1)
                                                <div class="absolute top-4 right-4 z-20">
                                                    <span class="bg-slate-900/90 backdrop-blur-md text-[#56bbf1] text-[10px] font-black px-2.5 py-1.5 rounded-lg shadow-sm flex items-center gap-1.5 border border-white/10">
                                                        <i class="ph-fill ph-images"></i> +{{ $totalImages - 1 }} Foto
                                                    </span>
                                                </div>
                                            @endif

                                            @if($activity->video_url)
                                                <div class="absolute top-4 left-4 z-20">
                                                    <a href="{{ $activity->video_url }}" target="_blank" class="px-3 py-1.5 bg-rose-600/90 backdrop-blur-md text-white text-[10px] font-black uppercase rounded-lg shadow-lg flex items-center gap-1.5 hover:bg-rose-500 transition-colors border border-rose-500/50">
                                                        <i class="ph-fill ph-play-circle text-sm"></i> Tonton
                                                    </a>
                                                </div>
                                            @endif

                                            <!-- Tombol Aksi (Edit & Hapus) Stack -->
                                            <div class="absolute top-4 right-4 z-30 flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200 transform translate-x-2 group-hover:translate-x-0 {{ $totalImages > 1 ? 'mt-10' : '' }}">
                                                
                                                <!-- Tombol Edit -->
                                                <button type="button" @click="openEditModal(@js($activity), '{{ route('school-activities.update', $activity->id) }}')" class="bg-slate-900/90 backdrop-blur-md text-[#56bbf1] p-2.5 rounded-xl shadow-sm border border-white/20 hover:bg-[#56bbf1] hover:text-slate-950 transition-all" title="Edit Kegiatan">
                                                    <i class="ph-bold ph-pencil-simple text-lg"></i>
                                                </button>

                                                <!-- Tombol Hapus -->
                                                <form action="{{ route('school-activities.destroy', $activity->id) }}" method="POST" class="delete-form">
                                                    @csrf @method('DELETE')
                                                    <button type="button" class="btn-delete bg-slate-900/90 backdrop-blur-md text-rose-400 p-2.5 rounded-xl shadow-sm border border-white/20 hover:bg-rose-600 hover:text-white transition-all" title="Hapus Kegiatan">
                                                        <i class="ph-bold ph-trash text-lg"></i>
                                                    </button>
                                                </form>
                                            </div>

                                            <div class="absolute bottom-4 left-4 z-20">
                                                <span class="text-[10px] font-black text-white uppercase tracking-widest flex items-center gap-1.5 bg-slate-950/70 backdrop-blur-md px-3 py-1.5 rounded-lg border border-white/10 shadow-sm">
                                                    <i class="ph-bold ph-calendar-blank text-[#56bbf1]"></i>
                                                    {{ $activity->created_at->format('d M Y') }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="p-6 flex-1 flex flex-col border-t border-white/10">
                                            <div class="mb-2">
                                                <h4 class="text-lg font-black text-white mb-2 line-clamp-2 leading-tight group-hover:text-[#56bbf1] transition-colors">
                                                    {{ $activity->title }}
                                                </h4>
                                                <p class="text-xs text-slate-300 leading-relaxed line-clamp-3 font-medium">
                                                    {{ $activity->description }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-span-1 md:col-span-2 py-24 text-center bg-slate-900/40 rounded-[2rem] border-2 border-dashed border-white/10">
                                        <div class="w-20 h-20 bg-slate-900 rounded-[1.5rem] flex items-center justify-center mx-auto mb-4 text-[#56bbf1] border border-white/10 shadow-sm">
                                            <i class="ph-duotone ph-image-broken text-4xl"></i>
                                        </div>
                                        <h4 class="text-white font-black text-lg">Belum ada kegiatan</h4>
                                        <p class="text-sm text-slate-400 font-medium mt-1 max-w-xs mx-auto">Mulai dengan mengupload foto dokumentasi kegiatan sekolah di formulir samping.</p>
                                    </div>
                                @endforelse
                            </div>

                            <div class="mt-8">
                                {{ $activities->links() ?? '' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal Edit Kegiatan --}}
            <div x-show="editModalOpen" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6">
                <!-- Backdrop -->
                <div x-show="editModalOpen" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 backdrop-blur-none" x-transition:enter-end="opacity-100 backdrop-blur-sm"
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 backdrop-blur-sm" x-transition:leave-end="opacity-0 backdrop-blur-none"
                     class="fixed inset-0 bg-slate-950/80 backdrop-blur-md" 
                     @click="closeEditModal()"></div>

                <!-- Modal Panel -->
                <div x-show="editModalOpen" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-8 sm:scale-95"
                     class="relative bg-[#021124] rounded-[2rem] border border-white/10 shadow-2xl w-full max-w-2xl flex flex-col max-h-[90vh] overflow-hidden text-slate-100" 
                     @click.away="closeEditModal()">
                     
                    <!-- Modal Header -->
                    <div class="px-8 py-6 border-b border-white/10 flex items-center justify-between bg-white/[0.02] shrink-0">
                        <h3 class="text-xl font-black text-white flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-slate-900 text-[#56bbf1] flex items-center justify-center border border-white/10 shadow-sm">
                                <i class="ph-bold ph-pencil-simple text-xl"></i>
                            </div>
                            Edit Album Kegiatan
                        </h3>
                        <button @click="closeEditModal()" type="button" class="w-10 h-10 flex items-center justify-center text-slate-400 hover:text-white bg-slate-800 hover:bg-slate-700 rounded-full transition-colors border border-transparent">
                            <i class="ph-bold ph-x text-lg"></i>
                        </button>
                    </div>

                    <!-- Modal Form -->
                    <form :action="editFormAction" method="POST" enctype="multipart/form-data" class="p-8 overflow-y-auto custom-scrollbar flex-1" x-data="{ editImgPreviews: [], isSubmitting: false }" @submit="isSubmitting = true">
                        @csrf
                        @method('PUT')
                        
                        <div class="space-y-6">
                            <div>
                                <label class="block text-[10px] font-black text-[#56bbf1] uppercase tracking-widest mb-2 ml-1">Judul Kegiatan</label>
                                <input type="text" name="title" x-model="editData.title" required class="w-full rounded-2xl border-white/10 bg-slate-900/80 focus:bg-slate-900 focus:border-[#56bbf1] focus:ring-[#56bbf1] font-bold text-white py-3.5 px-4 transition-colors">
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-[#56bbf1] uppercase tracking-widest mb-2 ml-1">Deskripsi Singkat</label>
                                <textarea name="description" x-model="editData.description" required rows="4" class="w-full rounded-2xl border-white/10 bg-slate-900/80 focus:bg-slate-900 focus:border-[#56bbf1] focus:ring-[#56bbf1] text-sm text-white font-medium p-4 transition-colors"></textarea>
                            </div>

                            <div class="p-6 bg-slate-900/60 border border-white/10 rounded-2xl">
                                <label class="block text-[10px] font-black text-[#56bbf1] uppercase tracking-widest mb-2">Ganti Foto / Tambah Foto Baru (Opsional)</label>
                                <p class="text-[10px] text-slate-400 mb-4 font-bold">Biarkan kosong jika tidak ingin mengubah foto. <span class="text-rose-400">Perhatian: Mengupload foto baru akan menghapus seluruh foto lama pada album ini.</span></p>
                                
                                <input type="file" name="photos[]" accept="image/*" multiple
                                    @change="editImgPreviews = []; Array.from($event.target.files).forEach(file => editImgPreviews.push(URL.createObjectURL(file)))"
                                    class="block w-full text-sm text-slate-400 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-800 file:text-[#56bbf1] hover:file:bg-slate-700 transition-all bg-slate-900 border border-white/10 rounded-xl p-1.5 mb-3 shadow-sm">
                                
                                <!-- Preview Foto Baru -->
                                <div x-show="editImgPreviews.length > 0" class="grid grid-cols-4 sm:grid-cols-5 gap-2 mt-4" style="display: none;">
                                    <template x-for="(src, index) in editImgPreviews" :key="index">
                                        <div class="h-16 rounded-xl overflow-hidden border border-white/10 shadow-sm">
                                            <img :src="src" class="w-full h-full object-cover">
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-[#56bbf1] uppercase tracking-widest mb-2 ml-1">Link Video (Opsional)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-rose-400">
                                        <i class="ph-fill ph-youtube-logo text-lg"></i>
                                    </div>
                                    <input type="url" name="video_url" x-model="editData.video_url" placeholder="https://..." class="w-full pl-11 rounded-2xl border-white/10 bg-slate-900/80 focus:bg-slate-900 focus:border-[#56bbf1] focus:ring-[#56bbf1] text-sm text-white font-bold py-3.5 transition-colors">
                                </div>
                            </div>
                        </div>

                        <!-- Footer Modal -->
                        <div class="mt-8 pt-6 border-t border-white/10 flex justify-end gap-3 shrink-0">
                            <button type="button" @click="closeEditModal()" class="px-6 py-3.5 rounded-xl font-bold text-slate-300 bg-slate-800 hover:bg-slate-700 transition-colors text-sm border border-transparent">Batal</button>
                            <button type="submit" :disabled="isSubmitting" :class="{'opacity-70 cursor-wait': isSubmitting}" class="px-8 py-3.5 rounded-xl font-bold text-slate-950 bg-[#56bbf1] hover:bg-sky-400 shadow-lg shadow-[#56bbf1]/20 transition-all flex items-center gap-2 text-sm border border-transparent">
                                <span x-show="!isSubmitting" class="flex items-center gap-2"><i class="ph-bold ph-floppy-disk text-lg"></i> Simpan Perubahan</span>
                                <span x-show="isSubmitting" x-cloak class="flex items-center gap-2"><i class="ph-bold ph-spinner animate-spin text-lg"></i> Menyimpan...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    {{-- Script untuk SweetAlert Delete --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.btn-delete');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    const form = this.closest('.delete-form');
                    Swal.fire({
                        title: 'Hapus Album?',
                        text: "Semua foto dalam album kegiatan ini akan terhapus.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e11d48',
                        cancelButtonColor: '#94a3b8',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        background: '#021124',
                        color: '#fff',
                        customClass: {
                            popup: 'rounded-[2rem] font-sans border border-white/10 shadow-2xl bg-[#021124]',
                            confirmButton: 'bg-rose-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-rose-500 transition-colors mx-2 shadow-lg border border-transparent',
                            cancelButton: 'bg-slate-800 text-slate-300 px-6 py-3 rounded-xl font-bold hover:bg-slate-700 transition-colors mx-2 border border-transparent'
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
</x-app-layout>