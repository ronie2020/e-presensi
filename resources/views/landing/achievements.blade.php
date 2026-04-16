<!-- PRESTASI SECTION -->
    <div id="prestasi" class="py-24 bg-gradient-to-b from-cyan-50/50 to-white relative overflow-hidden border-t border-slate-100" x-data="{ activeFilter: 'Terbaru' }">
        <div class="absolute top-0 right-0 w-96 h-96 bg-cyan-200 rounded-full mix-blend-multiply filter blur-[128px] opacity-20 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-blue-200 rounded-full mix-blend-multiply filter blur-[128px] opacity-20 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-6" data-aos="fade-up">
                <div class="max-w-2xl">
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-100 text-cyan-700 text-xs font-bold uppercase tracking-wider mb-4 border border-cyan-200"><i class="ph-fill ph-trophy"></i> Hall of Fame</span>
                    <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 leading-tight">Prestasi Membanggakan</h2>
                    <p class="mt-4 text-lg text-slate-600">Jejak juara siswa dan guru yang mengharumkan nama sekolah.</p>
                </div>
                <div class="flex gap-2">
                    <button @click="activeFilter = 'Terbaru'" :class="activeFilter === 'Terbaru' ? 'bg-cyan-500 text-white shadow-lg shadow-cyan-500/30' : 'bg-white text-slate-500 border border-slate-200 hover:bg-slate-50'" class="px-4 py-2 rounded-full text-sm font-bold transition">Terbaru</button>
                    <button @click="activeFilter = 'Nasional'" :class="activeFilter === 'Nasional' ? 'bg-cyan-500 text-white shadow-lg shadow-cyan-500/30' : 'bg-white text-slate-500 border border-slate-200 hover:bg-slate-50'" class="px-4 py-2 rounded-full text-sm font-bold transition">Nasional</button>
                    <button @click="activeFilter = 'Provinsi'" :class="activeFilter === 'Provinsi' ? 'bg-cyan-500 text-white shadow-lg shadow-cyan-500/30' : 'bg-white text-slate-500 border border-slate-200 hover:bg-slate-50'" class="px-4 py-2 rounded-full text-sm font-bold transition">Provinsi</button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($achievements ?? [] as $prestasi)
                    <div class="group bg-white rounded-2xl border border-cyan-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] hover:shadow-xl hover:shadow-cyan-500/10 hover:-translate-y-1 transition-all duration-300 relative overflow-hidden h-full flex flex-col" 
                         x-show="activeFilter === 'Terbaru' || activeFilter.toLowerCase() === '{{ strtolower($prestasi->level ?? '') }}'"
                         x-transition.duration.500ms
                         data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                        <div class="h-48 w-full bg-slate-100 relative overflow-hidden group">
                            @if(!empty($prestasi->photo_path))
                                <img src="{{ asset('storage/' . $prestasi->photo_path) }}" loading="lazy" alt="{{ $prestasi->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-cyan-400 to-blue-500 text-white" style="display: none;"><i class="ph-bold ph-trophy text-4xl"></i></div>
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-cyan-400 to-blue-500 text-white"><i class="ph-bold ph-trophy text-4xl"></i></div>
                            @endif
                            <div class="absolute top-3 right-3">
                                 <span class="px-2.5 py-1 rounded-full bg-white/90 backdrop-blur border border-white/20 text-[10px] font-bold uppercase text-cyan-700 tracking-wide shadow-sm">{{ $prestasi->level ?? 'Sekolah' }}</span>
                            </div>
                        </div>
                        <div class="p-5 flex-1 flex flex-col relative z-10">
                             <div class="text-xs text-slate-400 font-medium mb-2 flex items-center gap-1"><i class="ph-fill ph-calendar-blank"></i> {{ isset($prestasi->date) ? \Carbon\Carbon::parse($prestasi->date)->format('d M Y') : '-' }}</div>
                            <h4 class="text-lg font-bold text-slate-900 mb-2 leading-tight group-hover:text-cyan-600 transition-colors line-clamp-2">{{ $prestasi->title ?? 'Juara Lomba' }}</h4>
                            <div class="mt-auto pt-4 border-t border-slate-50 flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 text-sm"><i class="ph-bold ph-user"></i></div>
                                <div>
                                    <p class="text-xs font-bold text-slate-700 line-clamp-1">{{ $prestasi->achiever_name ?? 'Siswa' }}</p>
                                    <p class="text-xs text-slate-400 uppercase font-bold">{{ $prestasi->type ?? 'Siswa' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-4 text-slate-400 text-sm italic">Belum ada data prestasi.</div>
                @endforelse
            </div>
            <div class="mt-12 text-center" data-aos="fade-up">
                 <a href="{{ route('public.achievements') }}" class="inline-flex items-center justify-center px-6 py-3 text-sm font-bold text-cyan-700 bg-cyan-50 border border-cyan-200 rounded-full hover:bg-cyan-100 hover:text-cyan-800 transition-all shadow-sm">Lihat Arsip Prestasi <i class="ph-bold ph-arrow-right ml-2"></i></a>
            </div>
        </div>
    </div>