<!-- ARTIKEL & OPINI GURU -->
<section id="artikel" class="py-24 relative overflow-hidden transition-colors duration-300">
    
    <!-- Ambient Backgrounds -->
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-elevate-accent/10 rounded-full blur-[120px] pointer-events-none -translate-y-1/2 translate-x-1/4"></div>
    <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-elevate-primary/10 rounded-full blur-[120px] pointer-events-none translate-y-1/3 -translate-x-1/4"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-elevate-accent/20 text-elevate-accent text-[10px] font-black uppercase tracking-widest mb-4 border border-elevate-accent/30 shadow-sm transition-colors duration-300">
                <i class="ph-fill ph-pen-nib text-sm"></i> Pojok Literasi
            </span>
            <h2 class="text-3xl md:text-5xl font-black text-white mb-4 tracking-tight transition-colors duration-300">Artikel & Opini Guru</h2>
            <p class="text-slate-300 max-w-2xl mx-auto text-sm md:text-base font-medium transition-colors duration-300">Kumpulan tulisan, gagasan, dan opini inspiratif dari tenaga pendidik SMP Negeri 3 Lakbok.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($latestArticles ?? [] as $article)
                <!-- Elevate Card -->
                <article class="bg-white/5 backdrop-blur-xl rounded-[2.5rem] p-2 border border-white/10 shadow-[0_8px_32px_rgba(0,0,0,0.3)] hover:shadow-[0_8px_32px_rgba(86,187,241,0.2)] overflow-hidden group hover:-translate-y-2 hover:border-elevate-accent/40 hover:bg-white/10 transition-all duration-300 flex flex-col" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                    
                    {{-- Header Image --}}
                    <div class="relative h-56 sm:h-64 rounded-[2rem] bg-white/5 overflow-hidden shrink-0 border border-white/5">
                        @if($article->image_path)
                            <img src="{{ asset('storage/' . $article->image_path) }}" onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($article->title) }}&background=021124&color=56bbf1&size=500';" alt="{{ $article->title }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-in-out">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-white/5 transition-colors"><i class="ph-duotone ph-article text-6xl text-elevate-accent/30"></i></div>
                        @endif
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-[#021124]/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                        
                        {{-- Kategori Badge --}}
                        <div class="absolute top-4 left-4 z-20">
                            <span class="px-3 py-1.5 bg-[#021124]/80 backdrop-blur-md text-elevate-accent text-[9px] font-black uppercase tracking-widest rounded-xl shadow-sm border border-elevate-accent/30 transition-colors">{{ $article->category ?? 'Pendidikan' }}</span>
                        </div>
                    </div>

                    {{-- Konten Artikel --}}
                    <div class="p-6 flex flex-col flex-1 relative z-10 bg-transparent rounded-b-[2rem] transition-colors">
                        
                        {{-- Author & Meta Info --}}
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-[1rem] bg-white/5 overflow-hidden border border-white/10 shrink-0 shadow-sm transition-colors">
                                    <img src="{{ optional($article->user)->photo_path ? asset('storage/' . $article->user->photo_path) : 'https://ui-avatars.com/api/?name='.urlencode(optional($article->user)->name ?? 'A').'&background=021124&color=56bbf1' }}" alt="Penulis" loading="lazy" class="w-full h-full object-cover">
                                </div>
                                <span class="text-xs font-bold text-white line-clamp-1 transition-colors">{{ optional($article->user)->name ?? 'Anonim' }}</span>
                            </div>
                            
                            <div class="flex items-center gap-3 shrink-0">
                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest flex items-center gap-1 transition-colors" title="Estimasi Waktu Baca">
                                    <i class="ph-bold ph-clock text-elevate-accent"></i> 3 Min
                                </span>
                            </div>
                        </div>

                        {{-- Judul --}}
                        <a href="{{ $article->url ?? '#' }}" target="{{ $article->url ? '_blank' : '_self' }}" class="block group-hover:text-elevate-accent transition-colors">
                            <h3 class="text-lg font-black text-white mb-3 leading-snug line-clamp-2 transition-colors">{{ $article->title }}</h3>
                        </a>
                        
                        {{-- Excerpt --}}
                        <p class="text-sm text-slate-300 line-clamp-3 mb-6 flex-1 font-medium leading-relaxed transition-colors">{{ Str::limit(strip_tags($article->excerpt), 150) }}</p>

                        {{-- Footer Card --}}
                        <div class="mt-auto pt-4 border-t border-white/10 flex items-center justify-between transition-colors">
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest flex items-center gap-1.5 transition-colors">
                                <i class="ph-bold ph-calendar-blank text-elevate-accent"></i> 
                                {{ $article->published_at ? \Carbon\Carbon::parse($article->published_at)->translatedFormat('d M Y') : '-' }}
                            </span>
                            
                            <a href="{{ $article->url ?? route('teachers.show', $article->user_id) }}" target="{{ $article->url ? '_blank' : '_self' }}" aria-label="Baca selengkapnya tentang {{ $article->title }}" class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-white/10 text-elevate-accent hover:bg-gradient-to-r hover:from-elevate-accent hover:to-elevate-primary hover:text-white transition-all shadow-sm border border-white/15 group/link">
                                @if($article->url)
                                    <i class="ph-bold ph-arrow-up-right text-lg group-hover/link:scale-110 transition-transform"></i>
                                @else
                                    <i class="ph-bold ph-arrow-right text-lg group-hover/link:translate-x-0.5 transition-transform"></i>
                                @endif
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-1 md:col-span-3 text-center py-16 px-4 bg-white/5 backdrop-blur-xl rounded-[3rem] border-2 border-dashed border-white/10 shadow-sm transition-colors" data-aos="fade-up">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-[2rem] bg-elevate-accent/10 text-elevate-accent mb-6 border border-elevate-accent/20 transition-colors"><i class="ph-duotone ph-pen-nib text-5xl"></i></div>
                    <h3 class="text-xl font-black text-white mb-2 transition-colors">Belum Ada Artikel</h3>
                    <p class="text-sm font-medium text-slate-400 transition-colors">Guru-guru kami sedang menyiapkan tulisan-tulisan inspiratif untuk Anda.</p>
                </div>
            @endforelse
        </div>

        @if(isset($latestArticles) && count($latestArticles) > 0)
        <div class="text-center mt-12" data-aos="fade-up">
            <a href="{{ route('articles.index') }}" class="inline-flex items-center justify-center px-8 py-3.5 text-xs font-bold text-white bg-gradient-to-r from-elevate-accent to-elevate-primary border border-elevate-accent/30 rounded-full hover:shadow-[0_0_20px_rgba(86,187,241,0.4)] transition-all shadow-lg group">
                Jelajahi Semua Tulisan
                <i class="ph-bold ph-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
        @endif
    </div>
</section>