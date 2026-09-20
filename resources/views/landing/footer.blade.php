<!-- ANNOUNCEMENTS (Bottom) & FOOTER SECTION -->
<div id="kontak" class="relative overflow-hidden pt-24 pb-12 transition-colors duration-300">
           
    <!-- Aksen garis glowing di atas footer -->
    <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-elevate-accent to-transparent opacity-60"></div>
    
    <!-- Ambient glows -->
    <div class="absolute -right-20 top-20 w-96 h-96 bg-elevate-accent/10 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute -left-20 bottom-20 w-96 h-96 bg-elevate-primary/20 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <!-- PENGUMUMAN -->
        <div class="mb-24">
             <div class="flex justify-between items-end mb-10" data-aos="fade-up">
                <div>
                    <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-elevate-accent/20 text-elevate-accent text-[10px] font-black uppercase tracking-widest mb-3 border border-elevate-accent/30 shadow-sm">
                        <i class="ph-fill ph-bell-ringing text-sm"></i> Informasi Resmi
                    </span>
                    <h2 class="text-3xl font-black text-white mb-2">Papan Pengumuman</h2>
                    <p class="text-slate-300 text-sm font-medium">Informasi terbaru seputar kegiatan sekolah.</p>
                </div>
            </div>
            
            <div class="grid gap-6 md:grid-cols-3">
                @forelse ($announcements as $index => $item)
                    <article class="bg-white/5 backdrop-blur-xl rounded-[2rem] p-6 border border-white/10 hover:border-elevate-accent/40 shadow-[0_8px_32px_rgba(0,0,0,0.3)] hover:shadow-[0_8px_32px_rgba(86,187,241,0.2)] hover:bg-white/10 transition-all duration-300 hover:-translate-y-1 group h-full flex flex-col cursor-pointer" @click="openAnnouncementByIndex({{ $index }})" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <div class="flex justify-between items-start mb-4">
                            @php
                                $badgeStyle = match($item->category ?? 'Umum') {
                                    'Penting' => 'bg-rose-500/20 text-rose-300 border-rose-500/30',
                                    'Akademik' => 'bg-elevate-primary/20 text-elevate-accent border-elevate-accent/30',
                                    'Kesiswaan' => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30',
                                    default => 'bg-elevate-accent/20 text-elevate-accent border-elevate-accent/30',
                                };
                            @endphp
                            <span class="px-2.5 py-1 rounded-md {{ $badgeStyle }} text-[10px] font-black uppercase tracking-wide border shadow-sm">{{ $item->category ?? 'Info' }}</span>
                            <span class="text-xs text-slate-400 font-medium flex items-center gap-1">
                                <i class="ph-fill ph-calendar-blank text-elevate-accent"></i> {{ $item->created_at->format('d M') }}
                            </span>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-3 line-clamp-2 group-hover:text-elevate-accent transition-colors">{{ $item->title }}</h3>
                        <p class="text-slate-300 text-sm line-clamp-3 mb-4 flex-1 leading-relaxed">{{ Str::limit(strip_tags($item->content), 100) }}</p>
                        <div class="flex items-center text-sm text-elevate-accent font-semibold mt-auto gap-1 group-hover:gap-2 transition-all">
                            Baca Selengkapnya <i class="ph-bold ph-arrow-right text-xs"></i>
                        </div>
                    </article>
                @empty
                    <div class="col-span-3 text-center py-12 border border-dashed border-white/10 rounded-2xl bg-white/5">
                        <p class="text-slate-400">Tidak ada pengumuman terbaru saat ini.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- AGENDA KEGIATAN -->
        <div class="bg-white/5 backdrop-blur-xl rounded-[2.5rem] p-8 mb-16 border border-white/10 shadow-[0_8px_32px_rgba(0,0,0,0.3)]">
            <div class="flex items-center gap-3 mb-8">
                <div class="p-2.5 bg-elevate-accent/20 border border-elevate-accent/30 rounded-xl text-elevate-accent shadow-sm">
                    <i class="ph-fill ph-calendar-check text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-white">Agenda Mendatang</h3>
                    <p class="text-slate-300 text-sm mt-0.5 font-medium">Jadwal kegiatan akademik dan non-akademik.</p>
                </div>
            </div>
            
             <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @forelse($agendas as $agenda)
                    <div class="bg-white/5 border border-white/10 border-l-4 border-l-elevate-accent p-4 rounded-xl flex items-start gap-4 hover:bg-white/10 hover:border-elevate-accent/30 transition cursor-default group h-full">
                        <div class="text-center bg-[#021124]/90 border border-elevate-accent/30 p-2 rounded-lg min-w-[60px] shadow-sm transition-colors shrink-0 group-hover:border-elevate-accent">
                            <span class="block text-[10px] text-elevate-accent font-bold uppercase tracking-wider">{{ $agenda->event_date->format('M') }}</span>
                            <span class="block text-xl font-bold text-white">{{ $agenda->event_date->format('d') }}</span>
                        </div>
                        <div class="flex-1 min-w-0 py-0.5">
                            <h4 class="text-white font-bold text-sm line-clamp-2 leading-snug mb-1 group-hover:text-elevate-accent transition-colors" title="{{ $agenda->title }}">{{ $agenda->title }}</h4>
                            <p class="text-slate-400 text-xs flex items-center gap-1.5">
                                <i class="ph-fill ph-map-pin shrink-0 text-elevate-accent"></i> 
                                <span class="truncate">{{ $agenda->location ?? 'Sekolah' }}</span>
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="col-span-4 text-center py-6">
                        <p class="text-slate-400 italic">Belum ada agenda kegiatan mendatang.</p>
                    </div>
                @endforelse
            </div>
        </div>

      
        <!-- FOOTER WIDGETS -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16 border-t border-white/10 pt-16">
            <div class="col-span-1 md:col-span-2 pr-0 md:pr-12">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-lg overflow-hidden bg-white/10 border border-white/20 flex items-center justify-center p-1">
                         <img src="{{ asset('images/logo.png') }}" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';" alt="Logo" class="w-full h-full object-contain">
                         <i class="ph-bold ph-graduation-cap text-xl text-elevate-accent" style="display: none;"></i>
                    </div>
                    <span class="text-xl font-bold text-white tracking-tight">SMPN 3 LAKBOK</span>
                </div>
                <p class="text-slate-300 text-sm leading-relaxed mb-8 font-medium">
                    Visi sekolah adalah Terciptanya generasi pemelajar yang beriman dan bertakwa, tangguh, literat, berkecakapan global, serta berkesadaran budaya dan lingkungan.
                </p>
                <div class="flex space-x-4">
                    <a href="https://www.facebook.com/NetiLakbok" class="w-10 h-10 rounded-full bg-white/5 border border-white/15 flex items-center justify-center text-slate-300 hover:text-white hover:bg-gradient-to-r hover:from-elevate-accent hover:to-elevate-primary hover:border-elevate-accent transition-all duration-300 shadow-sm"><i class="ph-fill ph-facebook-logo text-xl"></i></a>
                    <a href="#" class="w-10 h-10 rounded-full bg-white/5 border border-white/15 flex items-center justify-center text-slate-300 hover:text-white hover:bg-gradient-to-r hover:from-elevate-accent hover:to-elevate-primary hover:border-elevate-accent transition-all duration-300 shadow-sm"><i class="ph-fill ph-instagram-logo text-xl"></i></a>
                    <a href="https://www.youtube.com/@netilachannel" class="w-10 h-10 rounded-full bg-white/5 border border-white/15 flex items-center justify-center text-slate-300 hover:text-white hover:bg-rose-600 hover:border-rose-500 transition-all duration-300 shadow-sm"><i class="ph-fill ph-youtube-logo text-xl"></i></a>
                </div>
            </div>
            <div>
                <h4 class="text-white font-bold mb-6 text-lg">Menu & Layanan</h4>
                <ul class="space-y-3 text-sm text-slate-300">
                    <li><a href="{{ route('student.login.learning') }}" class="hover:text-elevate-accent transition-colors flex items-center gap-2 font-medium"><i class="ph-bold ph-books text-elevate-accent text-sm"></i> Ruang Belajar (LMS)</a></li>
                    <li><a href="{{ route('student.login.cbt') }}" class="hover:text-elevate-accent transition-colors flex items-center gap-2 font-medium"><i class="ph-bold ph-monitor-play text-rose-400 text-sm"></i> Ujian CBT Online</a></li>
                    <li><a href="{{ route('portal.index') }}" class="hover:text-elevate-accent transition-colors flex items-center gap-2"><i class="ph-bold ph-caret-right text-elevate-accent text-xs"></i> Portal Siswa</a></li>
                    <li><a href="#profil" class="hover:text-elevate-accent transition-colors flex items-center gap-2"><i class="ph-bold ph-caret-right text-elevate-accent text-xs"></i> Profil Sekolah</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-elevate-accent transition-colors flex items-center gap-2"><i class="ph-bold ph-caret-right text-elevate-accent text-xs"></i> Masuk / Login</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-bold mb-6 text-lg">Hubungi Kami</h4>
                <ul class="space-y-4 text-sm text-slate-300">
                    <li class="flex items-start gap-3">
                        <i class="ph-fill ph-map-pin mt-1 text-elevate-accent shrink-0"></i>
                        <span class="leading-relaxed">Jl. Mekarjaya No.199 Sidaharja Kec. Lakbok, Kab. Ciamis, Jawa Barat 46385</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="ph-fill ph-phone text-elevate-accent shrink-0"></i>
                        <span>+62 85135961994</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="ph-fill ph-envelope text-elevate-accent shrink-0"></i>
                        <span>admin@smpn3lakbok.sch.id</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- COPYRIGHT -->
        <div class="text-center pt-8 border-t border-white/10">
            <p class="text-slate-400 text-sm">
                &copy; {{ date('Y') }} SMP Negeri 3 Lakbok. All rights reserved.
            </p>
        </div>
    </div>
</div>