@extends('layouts.public')

@section('title', 'Portofolio ' . ($teacher->name ?? 'Guru') . ' - ' . config('app.name', 'SMP Negeri 3 Lakbok'))

@push('styles')
    <style>
        /* Mencegah tab berkedip saat halaman baru dimuat (Wajib untuk AlpineJS) */
        [x-cloak] { display: none !important; }
        
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
        
        .animate-blob { animation: blob 7s infinite; }
        @keyframes blob { 
            0% { transform: translate(0px, 0px) scale(1); } 
            33% { transform: translate(30px, -50px) scale(1.1); } 
            66% { transform: translate(-20px, 20px) scale(0.9); } 
            100% { transform: translate(0px, 0px) scale(1); } 
        }
        
        /* Custom Scrollbar */
        .custom-scroll::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 10px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #56bbf1; border-radius: 10px; } /* Warna elevate-accent */
    </style>
@endpush

@section('content')
    <!-- HEADER PROFIL (Hero Section - Dark Glassmorphism) -->
    <div class="pt-32 pb-24 relative overflow-hidden -mt-24 border-b border-white/10">
        <!-- Animated Blobs / Glows -->
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-elevate-accent/15 rounded-full blur-[120px] pointer-events-none"></div>
        <div class="absolute bottom-0 right-10 w-72 h-72 bg-blue-500/10 rounded-full blur-[100px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 animate-enter">
            <div class="flex flex-col md:flex-row items-center md:items-start gap-8">
                <!-- Foto -->
                <div class="w-40 h-40 md:w-48 md:h-48 rounded-full p-2 bg-white/10 backdrop-blur-md border-2 border-elevate-accent/40 shadow-2xl shadow-elevate-accent/10 shrink-0">
                    <img src="{{ !empty($teacher->photo_path) ? asset('storage/' . $teacher->photo_path) : 'https://ui-avatars.com/api/?name='.urlencode($teacher->name ?? 'Guru').'&background=021124&color=56bbf1' }}" 
                         alt="{{ $teacher->name ?? 'Foto Guru' }}" 
                         class="w-full h-full rounded-full object-cover bg-white/5">
                </div>

                <!-- Info Utama -->
                <div class="text-center md:text-left flex-1 mt-4 md:mt-0">
                    @php
                        // Logika decode role
                        $displayRole = $teacher->position ?? null;
                        if (empty($displayRole) && isset($teacher->role)) {
                            $decodedRoles = is_string($teacher->role) ? json_decode($teacher->role, true) : $teacher->role;
                            $displayRole = is_array($decodedRoles) ? implode(', ', $decodedRoles) : $teacher->role;
                        }
                    @endphp
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-elevate-accent/20 border border-elevate-accent/30 text-elevate-accent text-xs font-bold uppercase tracking-wider mb-4 shadow-sm backdrop-blur-sm">
                        <i class="ph-fill ph-briefcase"></i> {{ $displayRole ?: 'Tenaga Pendidik' }}
                    </div>
                    <h1 class="text-3xl md:text-5xl font-black text-white mb-2 tracking-tight">{{ $teacher->name ?? 'Nama Tidak Diketahui' }}</h1>
                    <p class="text-slate-300 font-mono text-sm mb-6 font-bold">{{ !empty($teacher->nip) ? 'NIP. ' . $teacher->nip : 'Non-NIP' }}</p>
                    
                    <p class="text-slate-200 max-w-2xl leading-relaxed text-sm md:text-base italic mb-6">
                        "{{ $teacher->bio ?? 'Terus belajar dan menginspirasi generasi bangsa.' }}"
                    </p>

                   <!-- Sosial Media -->
                    <div class="flex items-center justify-center md:justify-start gap-4">
                        @if(!empty($teacher->instagram))
                        <a href="{{ $teacher->instagram }}" target="_blank" class="w-11 h-11 rounded-xl bg-white/10 border border-white/15 text-slate-300 hover:bg-elevate-accent hover:text-elevate-dark hover:border-elevate-accent flex items-center justify-center transition-all duration-300 shadow-sm hover:shadow-lg hover:-translate-y-1 backdrop-blur-sm">
                            <i class="ph ph-instagram-logo text-2xl"></i>
                        </a>
                        @endif

                        @if(!empty($teacher->facebook))
                        <a href="{{ $teacher->facebook }}" target="_blank" class="w-11 h-11 rounded-xl bg-white/10 border border-white/15 text-slate-300 hover:bg-elevate-accent hover:text-elevate-dark hover:border-elevate-accent flex items-center justify-center transition-all duration-300 shadow-sm hover:shadow-lg hover:-translate-y-1 backdrop-blur-sm">
                            <i class="ph ph-facebook-logo text-2xl"></i>
                        </a>
                        @endif

                        @if(!empty($teacher->tiktok))
                        <a href="{{ $teacher->tiktok }}" target="_blank" class="w-11 h-11 rounded-xl bg-white/10 border border-white/15 text-slate-300 hover:bg-elevate-accent hover:text-elevate-dark hover:border-elevate-accent flex items-center justify-center transition-all duration-300 shadow-sm hover:shadow-lg hover:-translate-y-1 backdrop-blur-sm">
                            <i class="ph ph-tiktok-logo text-2xl"></i>
                        </a>
                        @endif

                        @if(!empty($teacher->phone))
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $teacher->phone) }}" target="_blank" class="w-11 h-11 rounded-xl bg-white/10 border border-white/15 text-slate-300 hover:bg-emerald-500 hover:text-white hover:border-emerald-500 flex items-center justify-center transition-all duration-300 shadow-sm hover:shadow-lg hover:-translate-y-1 backdrop-blur-sm">
                            <i class="ph ph-whatsapp-logo text-2xl"></i>
                        </a>
                        @endif
                    </div>

                    <!-- Keahlian & Hobi -->
                    @if(!empty($teacher->keahlian) || !empty($teacher->hobi))
                        <div class="mt-8 flex flex-col md:flex-row gap-6 border-t border-white/10 pt-6 text-left">
                            @if(!empty($teacher->keahlian))
                            <div class="flex-1">
                                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 flex items-center justify-center md:justify-start gap-2">
                                    <i class="ph-bold ph-star text-amber-400"></i> Keahlian Utama
                                </h3>
                                <div class="flex flex-wrap justify-center md:justify-start gap-2">
                                    @foreach(array_map('trim', explode(',', $teacher->keahlian ?? '')) as $keahlian)
                                        @if(!empty($keahlian))
                                        <span class="px-3 py-1 bg-amber-400/15 text-amber-300 border border-amber-400/30 rounded-lg text-xs font-bold shadow-sm backdrop-blur-sm">{{ $keahlian }}</span>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            @if(!empty($teacher->hobi))
                            <div class="flex-1">
                                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 flex items-center justify-center md:justify-start gap-2">
                                    <i class="ph-bold ph-heart text-rose-400"></i> Minat & Hobi
                                </h3>
                                <div class="flex flex-wrap justify-center md:justify-start gap-2">
                                    @foreach(array_map('trim', explode(',', $teacher->hobi ?? '')) as $hobi)
                                        @if(!empty($hobi))
                                        <span class="px-3 py-1 bg-rose-400/15 text-rose-300 border border-rose-400/30 rounded-lg text-xs font-bold shadow-sm backdrop-blur-sm">{{ $hobi }}</span>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT DENGAN TABS (ALPINE.JS) -->
    <div x-data="{ activeTab: 'pendidikan' }" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-20 pb-20">
        
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- SIDEBAR NAVIGASI TABS -->
            <div class="lg:w-1/4">
                <div class="bg-white/10 backdrop-blur-2xl rounded-3xl p-4 shadow-2xl sticky top-24 border border-white/15 animate-enter" style="animation-delay: 100ms;">
                    <nav class="flex flex-row lg:flex-col gap-2 overflow-x-auto custom-scroll pb-2 lg:pb-0">
                        <button @click="activeTab = 'pendidikan'" :class="activeTab === 'pendidikan' ? 'bg-elevate-accent text-elevate-dark font-black shadow-lg shadow-elevate-accent/20 border-transparent' : 'text-slate-300 hover:bg-white/10 hover:text-white border-transparent'" class="w-full flex items-center gap-3 px-4 py-3.5 rounded-2xl border transition-all text-sm whitespace-nowrap lg:whitespace-normal text-left font-bold">
                            <i class="ph-duotone ph-graduation-cap text-xl"></i> Pendidikan Formal
                        </button>
                        <button @click="activeTab = 'pengalaman'" :class="activeTab === 'pengalaman' ? 'bg-elevate-accent text-elevate-dark font-black shadow-lg shadow-elevate-accent/20 border-transparent' : 'text-slate-300 hover:bg-white/10 hover:text-white border-transparent'" class="w-full flex items-center gap-3 px-4 py-3.5 rounded-2xl border transition-all text-sm whitespace-nowrap lg:whitespace-normal text-left font-bold">
                            <i class="ph-duotone ph-student text-xl"></i> Pengalaman & Pelatihan
                        </button>
                        <button @click="activeTab = 'materi'" :class="activeTab === 'materi' ? 'bg-elevate-accent text-elevate-dark font-black shadow-lg shadow-elevate-accent/20 border-transparent' : 'text-slate-300 hover:bg-white/10 hover:text-white border-transparent'" class="w-full flex items-center gap-3 px-4 py-3.5 rounded-2xl border transition-all text-sm whitespace-nowrap lg:whitespace-normal text-left font-bold">
                            <i class="ph-duotone ph-book-open-text text-xl"></i> Materi & Media
                        </button>
                        <button @click="activeTab = 'portofolio'" :class="activeTab === 'portofolio' ? 'bg-elevate-accent text-elevate-dark font-black shadow-lg shadow-elevate-accent/20 border-transparent' : 'text-slate-300 hover:bg-white/10 hover:text-white border-transparent'" class="w-full flex items-center gap-3 px-4 py-3.5 rounded-2xl border transition-all text-sm whitespace-nowrap lg:whitespace-normal text-left font-bold">
                            <i class="ph-duotone ph-medal text-xl"></i> Portofolio Guru
                        </button>
                        <button @click="activeTab = 'artikel'" :class="activeTab === 'artikel' ? 'bg-elevate-accent text-elevate-dark font-black shadow-lg shadow-elevate-accent/20 border-transparent' : 'text-slate-300 hover:bg-white/10 hover:text-white border-transparent'" class="w-full flex items-center gap-3 px-4 py-3.5 rounded-2xl border transition-all text-sm whitespace-nowrap lg:whitespace-normal text-left font-bold">
                            <i class="ph-duotone ph-article text-xl"></i> Artikel Terpublikasi
                        </button>
                    </nav>

                    <hr class="my-4 border-white/10 hidden lg:block">
                    
                    <a href="{{ route('teachers.index') }}" class="hidden lg:flex items-center justify-center gap-2 w-full py-3 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-sm transition-colors border border-white/15">
                        <i class="ph-bold ph-arrow-left"></i> Kembali ke Direktori
                    </a>
                </div>
            </div>

            <!-- AREA KONTEN -->
            <div class="lg:w-3/4 animate-enter" style="animation-delay: 200ms;">
                
                <!-- TAB PENDIDIKAN -->
                <div x-show="activeTab === 'pendidikan'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="bg-white/10 backdrop-blur-2xl rounded-3xl p-6 md:p-8 shadow-2xl border border-white/15 min-h-[400px] text-white">
                    <h2 class="text-2xl font-black text-white mb-6 flex items-center gap-3">
                        <span class="p-2 bg-elevate-accent/20 text-elevate-accent rounded-lg"><i class="ph-bold ph-graduation-cap"></i></span> Riwayat Pendidikan Formal
                    </h2>
                    
                    <div class="relative border-l-2 border-white/15 ml-4 space-y-8 pb-4">
                        @forelse($teacher->educations ?? [] as $edu)
                            <div class="relative pl-6">
                                <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-elevate-accent border-4 border-[#021124] shadow"></div>
                                <span class="text-xs font-bold text-elevate-accent mb-1 block">{{ $edu->start_year ?? '-' }} - {{ $edu->end_year ?? 'Sekarang' }}</span>
                                <h3 class="text-lg font-bold text-white">{{ $edu->institution ?? 'Institusi Tidak Diketahui' }}</h3>
                                <p class="text-slate-300 text-sm mt-1">{{ $edu->degree ?? '-' }}</p>
                            </div>
                        @empty
                            <div class="py-10 text-center text-slate-400">
                                <i class="ph-duotone ph-graduation-cap text-5xl mb-3 opacity-50"></i>
                                <p>Belum ada data pendidikan yang ditambahkan.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- TAB 1: PENGALAMAN & PELATIHAN -->
               <div x-cloak x-show="activeTab === 'pengalaman'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="bg-white/10 backdrop-blur-2xl rounded-3xl p-6 md:p-8 shadow-2xl border border-white/15 min-h-[400px] text-white">
                    <h2 class="text-2xl font-black text-white mb-6 flex items-center gap-3">
                        <span class="p-2 bg-elevate-accent/20 text-elevate-accent rounded-lg"><i class="ph-bold ph-student"></i></span> Riwayat Pelatihan & Sertifikasi
                    </h2>
                    
                    <div class="relative border-l-2 border-white/15 ml-4 space-y-8 pb-4">
                        @forelse($experiences ?? [] as $exp)
                            <div class="relative pl-6">
                                <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-elevate-accent border-4 border-[#021124] shadow"></div>
                                <span class="text-xs font-bold text-elevate-accent mb-1 block">Tahun {{ $exp->year ?? 'N/A' }}</span>
                                <h3 class="text-lg font-bold text-white">{{ $exp->title ?? 'Tanpa Judul' }}</h3>
                                <p class="text-slate-300 text-sm mt-1 mb-2">{{ $exp->organizer ?? '-' }}</p>
                                
                                @if(!empty($exp->certificate_path))
                                    <a href="{{ asset('storage/' . $exp->certificate_path) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-elevate-accent/20 text-elevate-accent hover:bg-elevate-accent hover:text-elevate-dark rounded-lg text-xs font-bold transition-colors border border-elevate-accent/30">
                                        <i class="ph-bold ph-certificate"></i> Lihat Sertifikat
                                    </a>
                                @endif
                            </div>
                        @empty
                            <div class="py-10 text-center text-slate-400">
                                <i class="ph-duotone ph-folder-open text-5xl mb-3 opacity-50"></i>
                                <p>Belum ada data pelatihan yang ditambahkan.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- TAB 2: MATERI & MEDIA -->
                <div x-cloak x-show="activeTab === 'materi'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="bg-white/10 backdrop-blur-2xl rounded-3xl p-6 md:p-8 shadow-2xl border border-white/15 min-h-[400px] text-white">
                    <h2 class="text-2xl font-black text-white mb-6 flex items-center gap-3">
                        <span class="p-2 bg-elevate-accent/20 text-elevate-accent rounded-lg"><i class="ph-bold ph-presentation-chart"></i></span> Materi & Media Pembelajaran
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @forelse($materials ?? [] as $material)
                            <a href="{{ !empty($material->file_url) ? $material->file_url : '#' }}" target="_blank" class="group block p-4 border border-white/15 rounded-2xl hover:border-elevate-accent hover:bg-white/10 transition-all bg-white/5">
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 shrink-0 rounded-xl bg-elevate-accent/20 text-elevate-accent flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                                        <i class="ph-fill {{ $material->icon ?? 'ph-file-text' }}"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-white group-hover:text-elevate-accent transition-colors line-clamp-2">{{ $material->title ?? 'Dokumen' }}</h4>
                                        <p class="text-xs text-slate-300 mt-1">{{ $material->type ?? 'Materi' }}</p>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="col-span-1 md:col-span-2 py-10 text-center text-slate-400">
                                <i class="ph-duotone ph-folder-open text-5xl mb-3 opacity-50"></i>
                                <p>Belum ada data materi yang dibagikan.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- TAB 3: PORTOFOLIO -->
                <div x-cloak x-show="activeTab === 'portofolio'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="bg-white/10 backdrop-blur-2xl rounded-3xl p-6 md:p-8 shadow-2xl border border-white/15 min-h-[400px] text-white">
                    <h2 class="text-2xl font-black text-white mb-6 flex items-center gap-3">
                        <span class="p-2 bg-elevate-accent/20 text-elevate-accent rounded-lg"><i class="ph-bold ph-medal"></i></span> Portofolio & Pencapaian
                    </h2>

                    {{-- Wrapper AlpineJS untuk Galeri Interaktif --}}
                    <div x-data="{ galleryOpen: false, galleryImages: [], currentIdx: 0, galleryTitle: '' }">
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @forelse($portfolios ?? [] as $portfolio)
                                @php
                                    // Parsing Array Foto
                                    $images = is_string($portfolio->image_path) ? json_decode($portfolio->image_path, true) : $portfolio->image_path;
                                    if (!is_array($images)) {
                                        $images = $portfolio->image_path ? [$portfolio->image_path] : [];
                                    }

                                    // Buat array Full URL khusus untuk dikirim ke Javascript
                                    $imageUrls = [];
                                    foreach($images as $img) {
                                        $imageUrls[] = asset('storage/' . $img);
                                    }
                                @endphp
                                
                                {{-- Kartu Portofolio (Bisa Diklik) --}}
                                <div @click="if({{ count($imageUrls) }} > 0) { galleryOpen = true; galleryImages = @js($imageUrls); currentIdx = 0; galleryTitle = '{{ addslashes($portfolio->title ?? 'Portofolio') }}' }" 
                                     class="group rounded-2xl overflow-hidden border border-white/15 relative cursor-pointer hover:shadow-xl hover:-translate-y-1 hover:border-elevate-accent transition-all duration-300 bg-white/5 backdrop-blur-xl">
                                    
                                    <div class="aspect-video bg-white/5 flex items-center justify-center relative">
                                        @if(count($images) > 0)
                                            <img src="{{ asset('storage/' . $images[0]) }}" alt="Portofolio" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                            
                                            @if(count($images) > 1)
                                                <div class="absolute top-3 right-3 bg-black/60 backdrop-blur-md text-white text-[10px] font-black px-2.5 py-1 rounded-lg flex items-center gap-1 shadow-sm">
                                                    <i class="ph-bold ph-images"></i> +{{ count($images) - 1 }} Foto
                                                </div>
                                            @endif

                                            {{-- Overlay "Lihat Galeri" --}}
                                            <div class="absolute inset-0 bg-[#021124]/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center backdrop-blur-[2px]">
                                                <span class="bg-elevate-accent text-elevate-dark px-4 py-2 rounded-xl text-sm font-black flex items-center gap-2 shadow-lg transform translate-y-4 group-hover:translate-y-0 transition-all duration-300">
                                                    <i class="ph-bold ph-magnifying-glass-plus text-lg"></i> Lihat Galeri
                                                </span>
                                            </div>
                                        @else
                                            <i class="ph-duotone ph-image text-slate-500 text-3xl"></i>
                                        @endif
                                    </div>
                                    
                                    <div class="p-4 relative z-10 border-t border-white/10">
                                        <h4 class="font-bold text-white mb-1 group-hover:text-elevate-accent transition-colors line-clamp-1">{{ $portfolio->title ?? 'Portofolio' }}</h4>
                                        <p class="text-xs font-medium text-slate-400">Tahun {{ $portfolio->year ?? '-' }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-1 sm:col-span-2 lg:col-span-3 py-10 text-center text-slate-400 border border-dashed border-white/15 rounded-3xl bg-white/5">
                                    <i class="ph-duotone ph-folder-open text-5xl mb-3 opacity-50"></i>
                                    <p>Belum ada portofolio / galeri yang ditambahkan.</p>
                                </div>
                            @endforelse
                        </div>

                        {{-- MODAL GALLERY FULLSCREEN (Pop-up Lightbox) --}}
                        <div x-show="galleryOpen" x-cloak 
                             class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6 bg-slate-900/95 backdrop-blur-md"
                             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                            
                            {{-- Tombol Close --}}
                            <button @click="galleryOpen = false" class="absolute top-4 right-4 text-white hover:text-rose-400 bg-white/10 hover:bg-white/20 p-3 rounded-full transition-colors z-[110]">
                                <i class="ph-bold ph-x text-xl"></i>
                            </button>

                            <div @click.away="galleryOpen = false" class="relative w-full max-w-5xl flex flex-col items-center justify-center h-full">
                                <!-- Judul Galeri -->
                                <div class="text-center mb-6 mt-12 md:mt-0 px-4">
                                    <h3 class="text-xl md:text-2xl font-black text-white drop-shadow-md" x-text="galleryTitle"></h3>
                                    <p class="text-xs font-bold text-elevate-accent mt-2 bg-white/10 border border-white/20 px-3 py-1 rounded-full inline-block">
                                        Foto <span x-text="currentIdx + 1"></span> dari <span x-text="galleryImages.length"></span>
                                    </p>
                                </div>

                                <!-- Area Foto Utama -->
                                <div class="relative w-full h-[50vh] md:h-[65vh] flex items-center justify-center mb-6 rounded-3xl overflow-hidden shadow-2xl bg-black/50 border border-white/10 group">
                                    <template x-if="galleryImages.length > 0">
                                        <img :src="galleryImages[currentIdx]" class="max-w-full max-h-full object-contain transition-all duration-300" 
                                             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                                    </template>
                                    
                                    <!-- Navigasi -->
                                    <button x-show="galleryImages.length > 1" @click.stop="currentIdx = currentIdx === 0 ? galleryImages.length - 1 : currentIdx - 1" class="absolute left-2 md:left-6 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/10 border border-white/20 hover:bg-elevate-accent hover:text-elevate-dark text-white rounded-full flex items-center justify-center transition-all shadow-lg backdrop-blur-md opacity-80 hover:opacity-100 md:opacity-0 md:group-hover:opacity-100"><i class="ph-bold ph-caret-left text-2xl"></i></button>
                                    <button x-show="galleryImages.length > 1" @click.stop="currentIdx = currentIdx === galleryImages.length - 1 ? 0 : currentIdx + 1" class="absolute right-2 md:right-6 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/10 border border-white/20 hover:bg-elevate-accent hover:text-elevate-dark text-white rounded-full flex items-center justify-center transition-all shadow-lg backdrop-blur-md opacity-80 hover:opacity-100 md:opacity-0 md:group-hover:opacity-100"><i class="ph-bold ph-caret-right text-2xl"></i></button>
                                </div>

                                <!-- Thumbnail -->
                                <div class="flex gap-3 overflow-x-auto max-w-full custom-scroll py-2 px-2" x-show="galleryImages.length > 1">
                                    <template x-for="(img, index) in galleryImages">
                                        <button @click.stop="currentIdx = index" class="shrink-0 w-16 h-16 md:w-20 md:h-20 rounded-xl overflow-hidden border-2 transition-all duration-300 ease-out" :class="currentIdx === index ? 'border-elevate-accent opacity-100 scale-110 shadow-lg shadow-elevate-accent/40' : 'border-white/10 opacity-40 hover:opacity-100 hover:border-white/40'">
                                            <img :src="img" class="w-full h-full object-cover">
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>

                <!-- TAB 4: ARTIKEL -->
                <div x-cloak x-show="activeTab === 'artikel'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="bg-white/10 backdrop-blur-2xl rounded-3xl p-6 md:p-8 shadow-2xl border border-white/15 min-h-[400px] text-white">
                    <h2 class="text-2xl font-black text-white mb-6 flex items-center gap-3">
                        <span class="p-2 bg-elevate-accent/20 text-elevate-accent rounded-lg"><i class="ph-bold ph-article"></i></span> Artikel & Opini
                    </h2>

                    <div class="space-y-4">
                        @forelse($articles ?? [] as $article)
                            <a href="{{ !empty($article->url) ? $article->url : '#' }}" class="flex flex-col sm:flex-row gap-4 p-4 rounded-2xl border border-white/15 hover:bg-white/10 hover:border-elevate-accent transition-all group bg-white/5">
                                @if(!empty($article->image_path))
                                <div class="sm:w-32 aspect-video sm:aspect-square rounded-xl bg-white/10 overflow-hidden shrink-0">
                                    <img src="{{ asset('storage/' . $article->image_path) }}" alt="Thumbnail" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                </div>
                                @endif
                                <div class="flex flex-col justify-center">
                                    <span class="text-xs font-bold text-elevate-accent mb-1">{{ $article->category ?? 'Pendidikan' }}</span>
                                    <h4 class="text-lg font-bold text-white group-hover:text-elevate-accent transition-colors">{{ $article->title ?? 'Tanpa Judul' }}</h4>
                                    <p class="text-sm text-slate-300 mt-2 line-clamp-2">{{ $article->excerpt ?? '' }}</p>
                                    <span class="text-xs text-slate-400 mt-3">
                                        <i class="ph-regular ph-calendar-blank"></i> 
                                        {{ !empty($article->published_at) ? \Carbon\Carbon::parse($article->published_at)->translatedFormat('d F Y') : 'Belum dipublikasi' }}
                                    </span>
                                </div>
                            </a>
                        @empty
                            <div class="py-10 text-center text-slate-400">
                                <i class="ph-duotone ph-folder-open text-5xl mb-3 opacity-50"></i>
                                <p>Belum ada artikel yang dipublikasikan.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection