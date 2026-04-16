 <!-- VIDEO PROFIL -->
    <div class="py-24 bg-slate-900 relative overflow-hidden">
        <!-- PERBAIKAN: Latar belakang digelapkan, dan ditambahkan efek glow Cyan -->
        <div class="absolute inset-0 bg-cover bg-center opacity-20 mix-blend-luminosity" style="background-image: url('<?php echo e(asset('images/netila.jpg')); ?>');"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/90 to-blue-900/40"></div>
        
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-3/4 h-3/4 bg-cyan-500/20 rounded-full blur-[120px] pointer-events-none"></div>

        <div class="max-w-4xl mx-auto px-4 relative z-10 text-center" data-aos="zoom-in">
            <!-- PERBAIKAN: Badge yang dulunya merah diubah ke Cyan Glowing -->
            <span class="inline-block py-1.5 px-4 rounded-full bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 text-xs font-bold uppercase tracking-wider mb-6 backdrop-blur-sm shadow-[0_0_15px_rgba(34,211,238,0.2)]">
                <i class="ph-fill ph-play-circle mr-1 text-sm"></i> Tonton Video Profil
            </span>
            <h2 class="text-3xl md:text-5xl font-extrabold text-white mb-8 tracking-tight">Kenali Kami Lebih Dekat</h2>
            
            <!-- PERBAIKAN: Bingkai iframe diberi gaya kaca (Glassmorphism) -->
            <div class="relative aspect-video rounded-[2rem] overflow-hidden shadow-[0_0_40px_rgba(34,211,238,0.15)] border border-white/10 group cursor-pointer bg-slate-800/50 backdrop-blur-md p-1 sm:p-2">
                <div class="w-full h-full rounded-2xl overflow-hidden relative">
                    <?php
                        $rawVideoUrl = 'https://www.youtube.com/watch?v=Ryc_cLLWef4'; 
                        $embedUrl = $rawVideoUrl;
                        if(str_contains($rawVideoUrl, 'watch?v=')) {
                            $embedUrl = str_replace('watch?v=', 'embed/', $rawVideoUrl);
                            $embedUrl = explode('&', $embedUrl)[0];
                        } elseif(str_contains($rawVideoUrl, 'youtu.be/')) {
                            $embedUrl = str_replace('youtu.be/', 'www.youtube.com/embed/', $rawVideoUrl);
                        }
                    ?>
                     <iframe class="w-full h-full bg-slate-900" src="<?php echo e($embedUrl); ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </div><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/landing/video.blade.php ENDPATH**/ ?>