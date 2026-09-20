<!-- PRESTASI SECTION -->
<div id="prestasi" class="py-24 relative overflow-hidden transition-colors duration-300" x-data="{ activeFilter: 'Terbaru' }">
    <!-- Abstract Ambient -->
    <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-elevate-accent/10 rounded-full filter blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-elevate-primary/10 rounded-full filter blur-[100px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6" data-aos="fade-up">
            <div class="max-w-2xl">
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-elevate-accent/20 text-elevate-accent text-[10px] font-black uppercase tracking-widest mb-4 border border-elevate-accent/30 shadow-sm transition-colors duration-300">
                    <i class="ph-fill ph-trophy text-sm"></i> Hall of Fame
                </span>
                <h2 class="text-3xl md:text-4xl font-black text-white leading-tight transition-colors duration-300">Prestasi Membanggakan</h2>
                <p class="mt-4 text-sm md:text-base text-slate-300 font-medium">Jejak juara siswa dan guru yang mengharumkan nama sekolah.</p>
            </div>
            
            {{-- Filter (Kategori Prestasi) --}}
            <div class="flex overflow-x-auto w-full md:w-auto pb-2 md:pb-0 gap-2 no-scrollbar custom-scrollbar snap-x">
                @foreach(['Terbaru', 'Nasional', 'Provinsi'] as $filter)
                    <button @click="activeFilter = '{{ $filter }}'" 
                            class="snap-start shrink-0 px-5 py-2.5 rounded-full text-xs font-bold transition-all duration-300 border shadow-sm flex items-center gap-2"
                            :class="activeFilter === '{{ $filter }}' 
                                ? 'bg-gradient-to-r from-elevate-accent to-elevate-primary text-white border-elevate-accent/40 shadow-lg shadow-elevate-accent/30 scale-105' 
                                : 'bg-white/5 backdrop-blur-md text-slate-300 border-white/10 hover:bg-white/10 hover:text-white hover:border-elevate-accent/30'">
                        {{ $filter }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($achievements ?? [] as $prestasi)
                <div class="group bg-white/5 backdrop-blur-xl rounded-[2rem] border border-white/10 shadow-[0_8px_32px_rgba(0,0,0,0.3)] hover:shadow-[0_8px_32px_rgba(86,187,241,0.2)] hover:-translate-y-2 transition-all duration-500 relative overflow-hidden h-full flex flex-col hover:border-elevate-accent/40 hover:bg-white/10" 
                     x-show="activeFilter === 'Terbaru' || activeFilter.toLowerCase() === '{{ strtolower($prestasi->level ?? '') }}'"
                     x-transition.duration.500ms
                     data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                    
                    <div class="h-48 w-full bg-white/5 relative overflow-hidden group">
                        @if(!empty($prestasi->photo_path))
                            <img src="{{ asset('storage/' . $prestasi->photo_path) }}" loading="lazy" alt="{{ $prestasi->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="absolute inset-0 flex items-center justify-center bg-white/5 text-elevate-accent" style="display: none;"><i class="ph-duotone ph-trophy text-5xl"></i></div>
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-white/5 text-elevate-accent/30"><i class="ph-duotone ph-trophy text-5xl"></i></div>
                        @endif
                        
                        <div class="absolute top-3 right-3">
                             <span class="px-3 py-1.5 rounded-full bg-[#021124]/85 backdrop-blur border border-elevate-accent/30 text-[9px] font-black uppercase text-elevate-accent tracking-widest shadow-sm">{{ $prestasi->level ?? 'Sekolah' }}</span>
                        </div>
                    </div>
                    
                    <div class="p-6 flex-1 flex flex-col relative z-10">
                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-2 flex items-center gap-1.5"><i class="ph-bold ph-calendar-blank text-elevate-accent"></i> {{ isset($prestasi->date) ? \Carbon\Carbon::parse($prestasi->date)->format('d M Y') : '-' }}</div>
                        <h4 class="text-lg font-black text-white mb-2 leading-tight group-hover:text-elevate-accent transition-colors line-clamp-2">{{ $prestasi->title ?? 'Juara Lomba' }}</h4>
                        
                        <div class="mt-auto pt-4 border-t border-white/10 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-[1rem] bg-white/5 flex items-center justify-center text-elevate-accent text-lg border border-white/10 group-hover:bg-gradient-to-r group-hover:from-elevate-accent group-hover:to-elevate-primary group-hover:text-white transition-colors"><i class="ph-fill ph-user"></i></div>
                            <div>
                                <p class="text-xs font-black text-white line-clamp-1">{{ $prestasi->achiever_name ?? 'Siswa' }}</p>
                                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest">{{ $prestasi->type ?? 'Siswa' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center animate-enter bg-white/5 backdrop-blur-xl rounded-[3rem] border-2 border-dashed border-white/10 transition-colors">
                    <div class="inline-flex p-5 bg-elevate-accent/10 rounded-full mb-4 text-elevate-accent shadow-sm border border-elevate-accent/20"><i class="ph-duotone ph-trophy text-5xl"></i></div>
                    <h3 class="text-xl font-black text-white mb-1">Belum Ada Prestasi</h3>
                    <p class="text-slate-400 text-sm font-medium">Jadilah yang pertama mengukir prestasi gemilang!</p>
                </div>
            @endforelse
         </div>
         
        <div class="mt-12 text-center" data-aos="fade-up">
             <a href="{{ route('public.achievements') }}" class="inline-flex items-center justify-center px-8 py-3.5 text-xs font-bold text-white bg-gradient-to-r from-elevate-accent to-elevate-primary border border-elevate-accent/30 rounded-full hover:shadow-[0_0_20px_rgba(86,187,241,0.4)] transition-all shadow-lg group">
                Lihat Arsip Prestasi 
                <i class="ph-bold ph-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
             </a>
        </div>
    </div>
</div>