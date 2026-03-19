 <!-- VIDEO PROFIL -->
    <div class="py-24 bg-slate-900 relative overflow-hidden">
        <div class="absolute inset-0 bg-cover bg-center opacity-30" style="background-image: url('<?php echo e(asset('images/netila.jpg')); ?>');"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/80 to-transparent"></div>
        <div class="max-w-4xl mx-auto px-4 relative z-10 text-center" data-aos="zoom-in">
            <span class="inline-block py-1 px-3 rounded-full bg-red-600/20 text-red-400 border border-red-500/30 text-xs font-bold uppercase tracking-wider mb-6 animate-pulse">
                <i class="ph-fill ph-youtube-logo mr-1"></i> Tonton Video
            </span>
            <h2 class="text-3xl md:text-5xl font-extrabold text-white mb-8">Kenali Kami Lebih Dekat</h2>
            
            <div class="relative aspect-video rounded-3xl overflow-hidden shadow-2xl border-4 border-slate-700 group cursor-pointer">
                <?php
                    $rawVideoUrl = 'https://www.youtube.com/watch?v=cx_Q4pyTNVQ'; 
                    $embedUrl = $rawVideoUrl;
                    if(str_contains($rawVideoUrl, 'watch?v=')) {
                        $embedUrl = str_replace('watch?v=', 'embed/', $rawVideoUrl);
                        $embedUrl = explode('&', $embedUrl)[0];
                    } elseif(str_contains($rawVideoUrl, 'youtu.be/')) {
                        $embedUrl = str_replace('youtu.be/', 'www.youtube.com/embed/', $rawVideoUrl);
                    }
                ?>
                 <iframe class="w-full h-full" src="<?php echo e($embedUrl); ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen loading="lazy"></iframe>
            </div>
        </div>
    </div><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/landing/video.blade.php ENDPATH**/ ?>