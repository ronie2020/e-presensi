@extends('layouts.public')

@section('title', 'Galeri Kegiatan - ' . config('app.name', 'SMP Negeri 3 Lakbok'))

@push('styles')
    <style>
        [x-cloak] { display: none !important; }

        /* Animasi Custom */
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
        
        .animate-blob { animation: blob 7s infinite; }
        @keyframes blob { 
            0% { transform: translate(0px, 0px) scale(1); } 
            33% { transform: translate(30px, -50px) scale(1.1); } 
            66% { transform: translate(-20px, 20px) scale(0.9); } 
            100% { transform: translate(0px, 0px) scale(1); } 
        }
        
        /* Custom Scrollbar Mini Gallery */
        .mini-scroll::-webkit-scrollbar { height: 4px; }
        .mini-scroll::-webkit-scrollbar-track { background: transparent; }
        .mini-scroll::-webkit-scrollbar-thumb { background: #56bbf1; border-radius: 10px; } /* Menggunakan warna elevate-accent */
    </style>
@endpush

@section('content')
    <!-- HEADER SECTION (Tema Diselaraskan: Elevate Gradient Light) -->
    <div class="pt-32 pb-32 relative overflow-hidden -mt-24 bg-elevate-gradient-main border-b border-white/60 shadow-sm">
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-[0.03] mix-blend-overlay"></div>

        <!-- Animated Blobs Elevate Colors -->
        <div class="absolute top-0 left-0 w-full md:w-[60%] h-full bg-elevate-primary/10 rounded-full blur-[100px] -translate-x-1/4 -translate-y-1/4 pointer-events-none animate-blob"></div>
        <div class="absolute bottom-0 right-0 w-full md:w-[50%] h-[80%] bg-elevate-peach/20 rounded-full blur-[120px] translate-x-1/4 translate-y-1/4 pointer-events-none animate-blob" style="animation-delay: 2s;"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center animate-enter">
            <span class="inline-flex items-center gap-2 py-1.5 px-4 rounded-full bg-white/60 border border-white text-elevate-primary text-xs font-bold uppercase tracking-wider mb-6 backdrop-blur-sm shadow-sm">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-elevate-accent opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-elevate-primary"></span>
                </span>
                <i class="ph-fill ph-image"></i> Dokumentasi Sekolah
            </span>
            <h1 class="text-4xl md:text-6xl font-black text-elevate-dark mb-6 tracking-tight">Galeri Kegiatan</h1>
            <p class="text-elevate-dark/80 text-lg max-w-2xl mx-auto mb-12 leading-relaxed font-medium">
                Kumpulan momen, aktivitas, dan dokumentasi inspiratif dari siswa serta guru SMP Negeri 3 Lakbok.
            </p>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-20 relative z-20 pb-20">
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
            @forelse($activities ?? [] as $index => $activity)
                @php
                    // EKSTRAKSI ARRAY FOTO YANG ROBUST
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

                <!-- Card dengan Alpine.js Data -->
                <div x-data="{ activeImg: '{{ $coverImage ? asset('storage/' . $coverImage) : '' }}' }" 
                     class="group bg-elevate-surface rounded-3xl overflow-hidden shadow-sm hover:shadow-xl hover:shadow-elevate-accent/10 hover:border-elevate-accent/50 hover:-translate-y-2 transition-all duration-300 border border-slate-100 flex flex-col h-full animate-enter" 
                     style="animation-delay: {{ ($index % 6) * 100 }}ms">
                    
                    <!-- Area Gambar Utama -->
                    <div class="relative h-60 overflow-hidden bg-elevate-soft shrink-0">
                        @if($coverImage)
                            <img :src="activeImg" loading="lazy" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="{{ $activity->title }}">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-elevate-primary bg-elevate-soft">
                                <i class="ph-duotone ph-image text-5xl mb-2 opacity-50"></i>
                            </div>
                        @endif

                        <!-- Overlay Gelap di bawah (dipertahankan untuk keterbacaan teks/tombol tambahan jika ada) -->
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/70 via-transparent to-transparent opacity-80 pointer-events-none"></div>

                        <!-- Tanggal -->
                        <div class="absolute top-4 left-4 z-10">
                            <span class="bg-white/95 backdrop-blur text-elevate-dark text-xs font-bold px-3 py-1.5 rounded-full shadow-sm">
                                {{ isset($activity->created_at) ? $activity->created_at->translatedFormat('d M Y') : '-' }}
                            </span>
                        </div>

                        <!-- Label Video -->
                        @if(!empty($activity->video_url))
                            <div class="absolute top-4 right-4 z-10">
                                <span class="bg-elevate-peach-dark text-white text-[10px] font-bold px-2.5 py-1.5 rounded-lg shadow-lg flex items-center gap-1.5 animate-pulse uppercase tracking-wider border border-elevate-peach">
                                    <i class="ph-fill ph-play-circle text-sm"></i> Video
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Area Teks -->
                    <div class="p-6 flex-1 flex flex-col bg-elevate-surface">
                        <h4 class="text-xl font-black text-elevate-dark mb-3 group-hover:text-elevate-primary transition-colors line-clamp-2 leading-tight">
                            {{ $activity->title }}
                        </h4>
                        <p class="text-sm text-elevate-dark/70 leading-relaxed line-clamp-3 mb-4 flex-1 font-medium">
                            {{ $activity->description }}
                        </p>

                        <!-- MINI GALLERY THUMBNAILS (Hanya Muncul Jika Foto > 1) -->
                        @if($totalImages > 1)
                            <div class="mb-4 pt-4 border-t border-slate-100">
                                <p class="text-[10px] font-bold text-elevate-dark/50 uppercase tracking-widest mb-2"><i class="ph-fill ph-images text-elevate-primary"></i> {{ $totalImages }} Foto Tersedia</p>
                                <div class="flex gap-2 overflow-x-auto pb-2 mini-scroll">
                                    @foreach($images as $img)
                                        <button @click="activeImg = '{{ asset('storage/' . $img) }}'" 
                                                class="w-14 h-14 shrink-0 rounded-xl overflow-hidden border-2 transition-all"
                                                :class="activeImg === '{{ asset('storage/' . $img) }}' ? 'border-elevate-primary opacity-100 shadow-md' : 'border-transparent opacity-60 hover:opacity-100 hover:scale-105'">
                                            <img src="{{ asset('storage/' . $img) }}" class="w-full h-full object-cover">
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Area Tombol Aksi -->
                        <div class="mt-auto pt-4 border-t border-slate-100 flex gap-2">
                            <!-- Tombol Lihat Foto Full -->
                            @if($coverImage)
                                <a :href="activeImg" target="_blank" class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 bg-elevate-soft text-elevate-primary hover:bg-elevate-primary hover:text-white rounded-xl text-sm font-bold transition-colors">
                                    <i class="ph-bold ph-arrows-out text-lg"></i>
                                    <span>Zoom Foto</span>
                                </a>
                            @endif

                            <!-- Tombol Lihat Video -->
                            @if(!empty($activity->video_url))
                                <a href="{{ $activity->video_url }}" target="_blank" class="{{ empty($coverImage) ? 'w-full' : 'flex-1' }} flex items-center justify-center gap-2 px-4 py-2.5 bg-elevate-peach/10 text-elevate-peach-dark hover:bg-elevate-peach-dark hover:text-white rounded-xl text-sm font-bold transition-colors">
                                    <i class="ph-bold ph-youtube-logo text-lg"></i>
                                    <span>Tonton Video</span>
                                </a>
                            @endif
                        </div>
                    </div>

                </div>
            @empty
                <!-- Empty State -->
                <div class="col-span-full py-24 text-center animate-enter bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200">
                    <div class="inline-flex bg-elevate-soft p-6 rounded-full mb-6 text-elevate-primary ring-8 ring-elevate-soft/50">
                        <i class="ph-duotone ph-image text-5xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-elevate-dark mb-2">Belum Ada Galeri</h3>
                    <p class="text-elevate-dark/60 text-sm max-w-md mx-auto">Saat ini belum ada dokumentasi kegiatan sekolah yang dipublikasikan.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-16 px-4 animate-enter">
            @if(isset($activities) && method_exists($activities, 'links'))
                {{ $activities->links() }}
            @endif
        </div>
        
    </div>
@endsection