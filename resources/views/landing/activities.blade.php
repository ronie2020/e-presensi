<!-- KEGIATAN SEKOLAH -->
<div id="kegiatan" class="py-24 bg-white dark:bg-slate-950 relative overflow-hidden border-t border-slate-100 dark:border-slate-900 transition-colors duration-300">
    <!-- Ambient Decor -->
    <div class="absolute top-1/2 left-0 w-[500px] h-[500px] bg-slate-50 dark:bg-slate-900 rounded-full filter blur-[100px] pointer-events-none -translate-y-1/2 -translate-x-1/2 transition-colors duration-300"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6" data-aos="fade-up">
            <div class="max-w-2xl">
                <!-- Badge Elevate -->
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-elevate-soft dark:bg-elevate-primary/20 text-elevate-primary dark:text-elevate-accent text-[10px] font-black uppercase tracking-widest mb-4 border border-elevate-accent/20 dark:border-elevate-accent/30 shadow-sm transition-colors">
                    <i class="ph-fill ph-camera text-sm"></i> Galeri Sekolah
                </span>
                <h2 class="text-3xl md:text-4xl font-black text-elevate-dark dark:text-white leading-tight transition-colors">Aktivitas & Kegiatan<br>Lingkungan Siswa</h2>
            </div>
            
            <a href="{{ route('public.activities') }}" class="hidden md:inline-flex items-center px-6 py-3 rounded-full text-xs font-bold text-elevate-dark dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-elevate-primary dark:hover:text-elevate-accent transition-all shadow-sm group">
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
                <div class="group bg-slate-50 dark:bg-slate-900 rounded-[2.5rem] p-2 border border-slate-100 dark:border-slate-800 hover:border-elevate-accent/30 dark:hover:border-elevate-primary/50 hover:shadow-xl hover:shadow-elevate-dark/5 dark:hover:shadow-elevate-accent/5 transition-all duration-300 flex flex-col h-full hover:-translate-y-2" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    
                    {{-- Image Container (Rounded atas) --}}
                    <a href="{{ route('public.activities') }}" class="relative h-56 sm:h-64 rounded-[2rem] overflow-hidden bg-slate-200 dark:bg-slate-800 block">
                        @if($coverImage)
                            <img src="{{ asset('storage/' . $coverImage) }}" loading="lazy" alt="{{ $activity->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="w-full h-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-slate-300 dark:text-slate-500" style="display: none;"><i class="ph-duotone ph-image-broken text-5xl"></i></div>
                        @else
                            <div class="w-full h-full bg-elevate-soft dark:bg-slate-800 flex items-center justify-center transition-colors">
                                <i class="ph-duotone ph-image text-5xl text-elevate-primary/30 dark:text-slate-600"></i>
                            </div>
                        @endif

                        {{-- Hover Overlay --}}
                        <div class="absolute inset-0 bg-elevate-dark/0 group-hover:bg-elevate-dark/30 dark:group-hover:bg-slate-900/50 transition-all duration-300 z-10 flex items-center justify-center opacity-0 group-hover:opacity-100">
                            <span class="bg-white/95 dark:bg-slate-900/90 backdrop-blur text-elevate-primary dark:text-elevate-accent font-bold px-5 py-2.5 rounded-full shadow-lg transform translate-y-4 group-hover:translate-y-0 transition-all duration-300 flex items-center gap-2 text-sm border border-white/20 dark:border-slate-700">
                                Buka Galeri <i class="ph-bold ph-arrow-square-out"></i>
                            </span>
                        </div>
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>

                        {{-- Date Badge --}}
                        <div class="absolute top-4 left-4 z-20 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md px-3 py-1.5 rounded-xl flex items-center gap-1.5 shadow-sm border border-white/50 dark:border-slate-700/50">
                            <i class="ph-bold ph-calendar-blank text-elevate-accent"></i>
                            <span class="text-[10px] font-black uppercase text-elevate-dark dark:text-slate-200 tracking-widest">{{ $activity->created_at->format('d M Y') }}</span>
                        </div>
                        
                        {{-- Badges (Foto & Video) --}}
                        <div class="absolute top-4 right-4 z-20 flex flex-col gap-2 items-end">
                            @if($totalImages > 1)
                                <span class="bg-elevate-dark/80 dark:bg-elevate-primary/80 backdrop-blur-md px-3 py-1.5 rounded-xl flex items-center gap-1.5 shadow-sm border border-white/10 dark:border-white/20 text-white text-[10px] font-black uppercase tracking-widest">
                                    <i class="ph-bold ph-images"></i> +{{ $totalImages - 1 }} Foto
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
                    <div class="bg-white dark:bg-slate-800 rounded-[2rem] p-6 mx-2 -mt-8 relative z-30 shadow-sm border border-slate-100 dark:border-slate-700/50 flex-1 flex flex-col group-hover:shadow-md transition-shadow">
                        <a href="{{ route('public.activities') }}">
                            <h3 class="text-lg font-black text-elevate-dark dark:text-slate-100 mb-2 group-hover:text-elevate-primary dark:group-hover:text-elevate-accent transition-colors leading-snug line-clamp-2">
                                {{ $activity->title }}
                            </h3>
                        </a>
                        <p class="text-slate-500 dark:text-slate-400 text-xs line-clamp-2 leading-relaxed font-medium mb-4 flex-1 transition-colors">
                            {{ Str::limit(strip_tags($activity->description), 100) }}
                        </p>

                        {{-- Footer Content / Video Link --}}
                        @if($activity->video_url)
                            <div class="pt-4 border-t border-slate-50 dark:border-slate-700/50 mt-auto transition-colors">
                                <a href="{{ $activity->video_url }}" target="_blank" class="inline-flex items-center justify-between px-4 py-2.5 bg-rose-50 dark:bg-rose-500/10 rounded-xl text-xs font-bold text-rose-600 dark:text-rose-400 hover:bg-rose-600 hover:text-white dark:hover:bg-rose-500 dark:hover:text-white transition-all w-full group/video shadow-sm">
                                    <span class="flex items-center gap-2"><i class="ph-fill ph-youtube-logo text-lg group-hover/video:scale-110 transition-transform"></i> Tonton Video</span>
                                    <i class="ph-bold ph-arrow-right opacity-0 -translate-x-2 group-hover/video:opacity-100 group-hover/video:translate-x-0 transition-all"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center animate-enter bg-white dark:bg-slate-800/50 rounded-[3rem] border-2 border-dashed border-slate-200 dark:border-slate-700 transition-colors">
                    <div class="inline-flex p-5 bg-elevate-soft dark:bg-slate-800 rounded-full mb-4 text-elevate-primary dark:text-slate-500 shadow-sm border border-elevate-accent/20 dark:border-slate-700"><i class="ph-duotone ph-image text-5xl"></i></div>
                    <h3 class="text-xl font-black text-elevate-dark dark:text-slate-200 mb-1">Belum Ada Aktivitas</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Kegiatan terbaru sekolah akan ditampilkan di sini.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-10 text-center md:hidden" data-aos="fade-up">
            <a href="{{ route('public.activities') }}" class="inline-flex items-center justify-center px-8 py-3.5 text-xs font-bold text-elevate-primary dark:text-white bg-elevate-soft dark:bg-elevate-primary border border-elevate-accent/30 dark:border-transparent rounded-full hover:bg-white dark:hover:bg-elevate-dark transition-all shadow-sm group">
                Lihat Semua Galeri 
                <i class="ph-bold ph-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
    </div>
</div>