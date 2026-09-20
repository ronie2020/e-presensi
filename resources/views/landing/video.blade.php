<!-- VIDEO PROFIL -->
<div class="py-24 relative overflow-hidden">
    <div class="absolute inset-0 bg-cover bg-center opacity-10 mix-blend-luminosity" style="background-image: url('{{ asset('images/netila.jpg') }}');"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-transparent via-[#021124]/40 to-transparent"></div>
    
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-3/4 h-3/4 bg-elevate-accent/15 rounded-full blur-[140px] pointer-events-none"></div>

    <div class="max-w-4xl mx-auto px-4 relative z-10 text-center" data-aos="zoom-in">
        <!-- Badge -->
        <span class="inline-block py-1.5 px-4 rounded-full bg-elevate-accent/20 text-elevate-accent border border-elevate-accent/30 text-xs font-bold uppercase tracking-wider mb-6 backdrop-blur-sm shadow-[0_0_15px_rgba(86,187,241,0.3)]">
            <i class="ph-fill ph-play-circle mr-1 text-sm"></i> Tonton Video Profil
        </span>
        <h2 class="text-3xl md:text-5xl font-extrabold text-white mb-8 tracking-tight">Kenali Kami Lebih Dekat</h2>
        
        <div class="relative aspect-video rounded-[2.5rem] overflow-hidden shadow-[0_16px_50px_rgba(0,0,0,0.5)] border border-elevate-accent/30 group bg-white/5 backdrop-blur-xl p-2 sm:p-3 transition-all duration-300">
            <div class="w-full h-full rounded-[2rem] overflow-hidden relative border border-white/10">
                @php   
                    // 1. Set Video Default
                    $rawVideoUrl = 'https://www.youtube.com/watch?v=7TMXpAZbE1s&list=PLQwMxsqb0Ozu5sbEFRf5nQNrqyU0XFznH'; 
                    
                    // 2. Cek apakah ada data video dari kegiatan sekolah terbaru
                    if(isset($latestVideoActivity) && !empty($latestVideoActivity->video_url)) {
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
                <iframe class="w-full h-full bg-[#021124]" src="{{ $embedUrl }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen loading="lazy"></iframe>
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