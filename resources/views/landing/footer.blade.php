<!-- ANNOUNCEMENTS (Bottom) & FOOTER SECTION -->
<div class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-white pt-24 pb-12 relative overflow-hidden transition-colors duration-300">
           
     <!-- Aksen garis elevate di atas footer -->
    <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-elevate-accent to-transparent"></div>
    
    <!-- Blob aksen di kanan atas -->
    <div class="absolute -right-20 top-20 w-96 h-96 bg-elevate-accent/50 dark:bg-elevate-primary/30 rounded-full mix-blend-multiply dark:mix-blend-overlay filter blur-[100px] opacity-50 dark:opacity-20"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <!-- PENGUMUMAN -->
        <div class="mb-24">
             <div class="flex justify-between items-end mb-10">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">Papan Pengumuman</h2>
                    <p class="text-slate-500 dark:text-slate-400 text-sm">Informasi terbaru seputar kegiatan sekolah.</p>
                </div>
            </div>
            
            <div class="grid gap-6 md:grid-cols-3">
                @forelse ($announcements as $index => $item)
                    <!-- Hover disesuaikan ke Elevate Theme -->
                    <article class="bg-white dark:bg-slate-900/50 backdrop-blur-md rounded-2xl p-6 border border-slate-200 dark:border-slate-800 hover:border-elevate-accent dark:hover:border-elevate-accent/50 shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1 group h-full flex flex-col cursor-pointer" @click="openAnnouncementByIndex({{ $index }})" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <div class="flex justify-between items-start mb-4">
                            <span class="px-2 py-1 rounded bg-elevate-accent/10 dark:bg-elevate-accent/20 text-elevate-primary dark:text-elevate-accent text-[10px] font-bold uppercase tracking-wide border border-elevate-accent/20">Info</span>
                            <span class="text-xs text-slate-400 dark:text-slate-500 font-medium flex items-center gap-1">
                                <i class="ph-fill ph-calendar-blank"></i> {{ $item->created_at->format('d M') }}
                            </span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-3 line-clamp-2 group-hover:text-elevate-primary dark:group-hover:text-elevate-accent transition-colors">{{ $item->title }}</h3>
                        <p class="text-slate-500 dark:text-slate-400 text-sm line-clamp-3 mb-4 flex-1 leading-relaxed">{{ Str::limit(strip_tags($item->content), 100) }}</p>
                        <div class="flex items-center text-sm text-elevate-primary dark:text-elevate-accent font-semibold mt-auto gap-1 group-hover:gap-2 transition-all">
                            Baca Selengkapnya <i class="ph-bold ph-arrow-right text-xs"></i>
                        </div>
                    </article>
                @empty
                    <div class="col-span-3 text-center py-12 border border-dashed border-slate-300 dark:border-slate-800 rounded-xl bg-slate-100/50 dark:bg-slate-900/30">
                        <p class="text-slate-500">Tidak ada pengumuman terbaru saat ini.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- AGENDA KEGIATAN -->
        <div class="bg-white dark:bg-slate-900/50 rounded-3xl p-8 mb-16 border border-slate-200 dark:border-slate-800 backdrop-blur-md shadow-sm">
            <div class="flex items-center gap-3 mb-8">
                <div class="p-2 bg-elevate-accent/10 dark:bg-elevate-accent/20 rounded-lg text-elevate-primary dark:text-elevate-accent">
                    <i class="ph-fill ph-calendar-check text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">Agenda Mendatang</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm mt-0.5">Jadwal kegiatan akademik dan non-akademik.</p>
                </div>
            </div>
            
             <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @forelse($agendas as $agenda)
                    <!-- Dibuat seragam menggunakan warna tema agar terlihat lebih rapi/korporat -->
                    <div class="bg-slate-50 dark:bg-slate-800/50 p-4 rounded-xl border-l-4 border-elevate-accent flex items-start gap-4 hover:bg-slate-100 dark:hover:bg-slate-800 transition cursor-default group h-full">
                        <div class="text-center bg-white dark:bg-slate-900 border border-slate-200 dark:border-none p-2 rounded-lg min-w-[60px] shadow-sm dark:shadow-lg transition-colors shrink-0 group-hover:border-elevate-accent/50">
                            <span class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider">{{ $agenda->event_date->format('M') }}</span>
                            <span class="block text-xl font-bold text-elevate-dark dark:text-white">{{ $agenda->event_date->format('d') }}</span>
                        </div>
                        <div class="flex-1 min-w-0 py-0.5">
                            <h4 class="text-slate-800 dark:text-white font-bold text-sm line-clamp-2 leading-snug mb-1" title="{{ $agenda->title }}">{{ $agenda->title }}</h4>
                            <p class="text-slate-500 dark:text-slate-400 text-xs flex items-center gap-1.5">
                                <i class="ph-fill ph-map-pin shrink-0 text-elevate-accent"></i> 
                                <span class="truncate">{{ $agenda->location ?? 'Sekolah' }}</span>
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="col-span-4 text-center py-6">
                        <p class="text-slate-500 italic">Belum ada agenda kegiatan mendatang.</p>
                    </div>
                @endforelse
            </div>
        </div>

      
        <!-- FOOTER WIDGETS -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16 border-t border-slate-200 dark:border-slate-800 pt-16">
            <div class="col-span-1 md:col-span-2 pr-0 md:pr-12">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-lg overflow-hidden bg-white border border-slate-200 dark:border-none flex items-center justify-center p-1">
                         <img src="{{ asset('images/logo.png') }}" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';" alt="Logo" class="w-full h-full object-contain">
                         <i class="ph-bold ph-graduation-cap text-xl text-elevate-dark" style="display: none;"></i>
                    </div>
                    <span class="text-xl font-bold text-slate-800 dark:text-white tracking-tight">SMPN 3 LAKBOK</span>
                </div>
                <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed mb-8">
                    Visi sekolah adalah Terciptanya generasi pemelajar yang beriman dan bertakwa, tangguh, literat, berkecakapan global, serta berkesadaran budaya dan lingkungan.
                </p>
                <div class="flex space-x-4">
                    <a href="https://www.facebook.com/NetiLakbok" class="w-10 h-10 rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-none flex items-center justify-center text-slate-500 dark:text-slate-400 hover:text-white dark:hover:text-white hover:bg-elevate-primary dark:hover:bg-elevate-primary hover:border-transparent transition-all duration-300 shadow-sm"><i class="ph-fill ph-facebook-logo text-xl"></i></a>
                    <a href="#" class="w-10 h-10 rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-none flex items-center justify-center text-slate-500 dark:text-slate-400 hover:text-white dark:hover:text-white hover:bg-elevate-primary dark:hover:bg-elevate-primary hover:border-transparent transition-all duration-300 shadow-sm"><i class="ph-fill ph-instagram-logo text-xl"></i></a>
                    <a href="https://www.youtube.com/@netilachannel" class="w-10 h-10 rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-none flex items-center justify-center text-slate-500 dark:text-slate-400 hover:text-white dark:hover:text-white hover:bg-rose-600 dark:hover:bg-rose-600 hover:border-transparent transition-all duration-300 shadow-sm"><i class="ph-fill ph-youtube-logo text-xl"></i></a>
                </div>
            </div>
            <div>
                <h4 class="text-slate-800 dark:text-white font-bold mb-6 text-lg">Menu Utama</h4>
                <ul class="space-y-3 text-sm text-slate-500 dark:text-slate-400">
                    <li><a href="#profil" class="hover:text-elevate-primary dark:hover:text-elevate-accent transition-colors flex items-center gap-2"><i class="ph-bold ph-caret-right text-xs"></i> Profil Sekolah</a></li>
                    <li><a href="#guru" class="hover:text-elevate-primary dark:hover:text-elevate-accent transition-colors flex items-center gap-2"><i class="ph-bold ph-caret-right text-xs"></i> Tenaga Pendidik</a></li>
                    <li><a href="#kegiatan" class="hover:text-elevate-primary dark:hover:text-elevate-accent transition-colors flex items-center gap-2"><i class="ph-bold ph-caret-right text-xs"></i> Galeri Kegiatan</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-elevate-primary dark:hover:text-elevate-accent transition-colors flex items-center gap-2"><i class="ph-bold ph-caret-right text-xs"></i> Login Staff</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-slate-800 dark:text-white font-bold mb-6 text-lg">Hubungi Kami</h4>
                <ul class="space-y-4 text-sm text-slate-500 dark:text-slate-400">
                    <li class="flex items-start gap-3">
                        <i class="ph-fill ph-map-pin mt-1 text-elevate-primary dark:text-elevate-accent shrink-0"></i>
                        <span class="leading-relaxed">Jl. Mekarjaya No.199 Sidaharja Kec. Lakbok, Kab. Ciamis, Jawa Barat 46385</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="ph-fill ph-phone text-elevate-primary dark:text-elevate-accent shrink-0"></i>
                        <span>+62 85135961994</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="ph-fill ph-envelope text-elevate-primary dark:text-elevate-accent shrink-0"></i>
                        <span>admin@smpn3lakbok.sch.id</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- COPYRIGHT -->
        <div class="text-center pt-8 border-t border-slate-200 dark:border-slate-800/80">
            <p class="text-slate-500 text-sm">
                &copy; {{ date('Y') }} SMP Negeri 3 Lakbok. Ri.. All rights reserved.
            </p>
        </div>
    </div>
</div>