<!-- KEGIATAN SEKOLAH -->
<div id="kegiatan" class="py-24 relative overflow-hidden transition-colors duration-300">
    <!-- Ambient Decor -->
    <div class="absolute top-1/2 left-0 w-[500px] h-[500px] bg-elevate-accent/10 rounded-full filter blur-[120px] pointer-events-none -translate-y-1/2 -translate-x-1/2"></div>
    <div class="absolute top-1/3 right-0 w-[500px] h-[500px] bg-elevate-primary/10 rounded-full filter blur-[120px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6" data-aos="fade-up">
            <div class="max-w-2xl">
                <!-- Badge -->
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-elevate-accent/20 text-elevate-accent text-[10px] font-black uppercase tracking-widest mb-4 border border-elevate-accent/30 shadow-sm transition-colors">
                    <i class="ph-fill ph-camera text-sm"></i> Galeri Sekolah
                </span>
                <h2 class="text-3xl md:text-4xl font-black text-white leading-tight transition-colors">Aktivitas & Kegiatan<br>Lingkungan Siswa</h2>
            </div>
            
            <a href="{{ route('public.activities') }}" class="hidden md:inline-flex items-center px-6 py-3 rounded-full text-xs font-bold text-white bg-white/5 backdrop-blur-md border border-white/15 hover:bg-white/15 hover:border-elevate-accent/40 hover:text-elevate-accent transition-all shadow-sm group">
                Lihat Semua Galeri <i class="ph-bold ph-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($activities as $activity)
                {{-- LOGIKA GAMBAR TETAP DIPERTAHANKAN --}}
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

                <!-- Card Elevate -->
                <div class="group bg-white/5 backdrop-blur-xl rounded-[2.5rem] p-2 border border-white/10 hover:border-elevate-accent/40 hover:shadow-[0_8px_32px_rgba(86,187,241,0.2)] hover:bg-white/10 transition-all duration-300 flex flex-col h-full hover:-translate-y-2" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    
                    {{-- Image Container (Rounded atas) --}}
                    <a href="{{ route('public.activities') }}" class="relative h-56 sm:h-64 rounded-[2rem] overflow-hidden bg-white/5 border border-white/5 block">
                        @if($coverImage)
                            <img src="{{ asset('storage/' . $coverImage) }}" loading="lazy" alt="{{ $activity->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="w-full h-full bg-white/5 flex items-center justify-center text-slate-400" style="display: none;"><i class="ph-duotone ph-image-broken text-5xl"></i></div>
                        @else
                            <div class="w-full h-full bg-white/5 flex items-center justify-center transition-colors">
                                <i class="ph-duotone ph-image text-5xl text-elevate-accent/30"></i>
                            </div>
                        @endif

                        {{-- Hover Overlay --}}
                        <div class="absolute inset-0 bg-[#021124]/40 transition-all duration-300 z-10 flex items-center justify-center opacity-0 group-hover:opacity-100">
                            <span class="bg-[#021124]/90 backdrop-blur text-elevate-accent font-bold px-5 py-2.5 rounded-full shadow-lg transform translate-y-4 group-hover:translate-y-0 transition-all duration-300 flex items-center gap-2 text-sm border border-elevate-accent/30">
                                Buka Galeri <i class="ph-bold ph-arrow-square-out"></i>
                            </span>
                        </div>
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-[#021124]/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>

                        {{-- Date Badge --}}
                        <div class="absolute top-4 left-4 z-20 bg-[#021124]/80 backdrop-blur-md px-3 py-1.5 rounded-xl flex items-center gap-1.5 shadow-sm border border-white/10">
                            <i class="ph-bold ph-calendar-blank text-elevate-accent"></i>
                            <span class="text-[10px] font-black uppercase text-elevate-accent tracking-widest">{{ $activity->created_at->format('d M Y') }}</span>
                        </div>
                        
                        {{-- Badges (Foto & Video) --}}
                        <div class="absolute top-4 right-4 z-20 flex flex-col gap-2 items-end">
                            @if($totalImages > 1)
                                <span class="bg-[#021124]/80 backdrop-blur-md px-3 py-1.5 rounded-xl flex items-center gap-1.5 shadow-sm border border-white/10 text-white text-[10px] font-black uppercase tracking-widest">
                                    <i class="ph-bold ph-images text-elevate-accent"></i> +{{ $totalImages - 1 }} Foto
                                </span>
                            @endif
                            @if($activity->video_url)
                                <span class="bg-rose-600/90 backdrop-blur text-white text-[10px] font-black px-3 py-1.5 rounded-xl shadow-sm flex items-center gap-1.5 animate-pulse border border-rose-500/50 uppercase tracking-widest">
                                    <i class="ph-fill ph-play-circle text-sm"></i> VIDEO
                                </span>
                            @endif
                        </div>
                    </a>

                    {{-- Content Container (Floating effect) --}}
                    <div class="bg-[#021124]/80 backdrop-blur-xl rounded-[2rem] p-6 mx-2 -mt-8 relative z-30 shadow-lg border border-white/10 flex-1 flex flex-col group-hover:border-elevate-accent/30 transition-all">
                        <a href="{{ route('public.activities') }}">
                            <h3 class="text-lg font-black text-white mb-2 group-hover:text-elevate-accent transition-colors leading-snug line-clamp-2">
                                {{ $activity->title }}
                            </h3>
                        </a>
                        <p class="text-slate-300 text-xs line-clamp-2 leading-relaxed font-medium mb-4 flex-1 transition-colors">
                            {{ Str::limit(strip_tags($activity->description), 100) }}
                        </p>

                        {{-- Footer Content / Video Link --}}
                        @if($activity->video_url)
                            <div class="pt-4 border-t border-white/10 mt-auto transition-colors">
                                <a href="{{ $activity->video_url }}" target="_blank" class="inline-flex items-center justify-between px-4 py-2.5 bg-rose-500/10 border border-rose-500/20 rounded-xl text-xs font-bold text-rose-400 hover:bg-rose-600 hover:text-white transition-all w-full group/video shadow-sm">
                                    <span class="flex items-center gap-2"><i class="ph-fill ph-youtube-logo text-lg group-hover/video:scale-110 transition-transform"></i> Tonton Video</span>
                                    <i class="ph-bold ph-arrow-right opacity-0 -translate-x-2 group-hover/video:opacity-100 group-hover/video:translate-x-0 transition-all"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center animate-enter bg-white/5 backdrop-blur-xl rounded-[3rem] border-2 border-dashed border-white/10 transition-colors">
                    <div class="inline-flex p-5 bg-elevate-accent/10 rounded-full mb-4 text-elevate-accent shadow-sm border border-elevate-accent/20"><i class="ph-duotone ph-image text-5xl"></i></div>
                    <h3 class="text-xl font-black text-white mb-1">Belum Ada Aktivitas</h3>
                    <p class="text-slate-400 text-sm font-medium">Kegiatan terbaru sekolah akan ditampilkan di sini.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-10 text-center md:hidden" data-aos="fade-up">
            <a href="{{ route('public.activities') }}" class="inline-flex items-center justify-center px-8 py-3.5 text-xs font-bold text-white bg-gradient-to-r from-elevate-accent to-elevate-primary border border-elevate-accent/30 rounded-full hover:shadow-[0_0_20px_rgba(86,187,241,0.4)] transition-all shadow-lg group">
                Lihat Semua Galeri 
                <i class="ph-bold ph-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
    </div>
</div>