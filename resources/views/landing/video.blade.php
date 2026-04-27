<!-- VIDEO PROFIL -->
    <div class="py-24 bg-slate-900 relative overflow-hidden">
        <!-- PERBAIKAN: Efek glow diubah ke Elevate Accent -->
        <div class="absolute inset-0 bg-cover bg-center opacity-20 mix-blend-luminosity" style="background-image: url('{{ asset('images/netila.jpg') }}');"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/90 to-elevate-dark/60"></div>
        
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-3/4 h-3/4 bg-elevate-accent/20 rounded-full blur-[120px] pointer-events-none"></div>

        <div class="max-w-4xl mx-auto px-4 relative z-10 text-center" data-aos="zoom-in">
            <!-- Badge dengan warna Elevate Glowing -->
            <span class="inline-block py-1.5 px-4 rounded-full bg-elevate-accent/10 text-elevate-accent border border-elevate-accent/20 text-xs font-bold uppercase tracking-wider mb-6 backdrop-blur-sm shadow-lg shadow-elevate-accent/20">
                <i class="ph-fill ph-play-circle mr-1 text-sm"></i> Tonton Video Profil
            </span>
            <h2 class="text-3xl md:text-5xl font-extrabold text-white mb-8 tracking-tight">Kenali Kami Lebih Dekat</h2>
            
            <div class="relative aspect-video rounded-[2rem] overflow-hidden shadow-2xl shadow-elevate-accent/20 border border-white/10 dark:border-slate-700/50 group cursor-pointer bg-slate-800/50 dark:bg-slate-900/80 backdrop-blur-md p-1 sm:p-2 transition-colors duration-300">
                <div class="w-full h-full rounded-2xl overflow-hidden relative">
                    @php   
                        // 1. Set Video Default (Video profil lama Anda)
                        $rawVideoUrl = 'https://www.youtube.com/watch?v=7TMXpAZbE1s&list=PLQwMxsqb0Ozu5sbEFRf5nQNrqyU0XFznH'; 
                        
                        // 2. Cek apakah ada data video dari kegiatan sekolah terbaru
                        if(isset($latestVideoActivity) && !empty($latestVideoActivity->video_url)) {
                            // Jika ada, timpa video default dengan video dari kegiatan terbaru
                            $rawVideoUrl = $latestVideoActivity->video_url;
                        }

                        // 3. Logika untuk mengubah link youtube biasa menjadi link embed
                        $embedUrl = $rawVideoUrl;
                        if(str_contains($rawVideoUrl, 'watch?v=')) {
                            $embedUrl = str_replace('watch?v=', 'embed/', $rawVideoUrl);
                            $embedUrl = explode('&', $embedUrl)[0];
                        } elseif(str_contains($rawVideoUrl, 'youtu.be/')) {
                            $embedUrl = str_replace('youtu.be/', 'www.youtube.com/embed/', $rawVideoUrl);
                        }
                    @endphp
                     <iframe class="w-full h-full bg-slate-900" src="{{ $embedUrl }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen loading="lazy"></iframe>
                </div>
            </div>

            @if(isset($latestVideoActivity) && !empty($latestVideoActivity->video_url))
                <div class="mt-6">
                    <p class="text-elevate-accent font-medium text-sm">Sedang memutar:</p>
                    <p class="text-white font-bold text-lg">{{ $latestVideoActivity->title }}</p>
                </div>
            @endif
        </div>
    </div>