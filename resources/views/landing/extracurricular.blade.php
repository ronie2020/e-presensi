<!-- EKSTRAKURIKULER -->
<div id="ekskul" class="py-24 relative overflow-hidden transition-colors duration-300">
    <!-- Blobs -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-elevate-accent/10 rounded-full blur-[128px] pointer-events-none animate-blob"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-elevate-primary/10 rounded-full blur-[128px] pointer-events-none animate-blob" style="animation-delay: 2s;"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="px-4 py-1.5 bg-elevate-accent/20 text-elevate-accent rounded-full text-xs font-bold uppercase tracking-widest border border-elevate-accent/30 shadow-sm backdrop-blur-sm inline-block">
                Bakat & Minat
            </span>
            <h2 class="text-3xl font-black text-white sm:text-4xl mt-4 tracking-tight">Ekstrakurikuler</h2>
            <p class="mt-4 text-lg text-slate-300 max-w-2xl mx-auto font-medium">
                Wadah pengembangan potensi siswa di luar jam pelajaran akademik.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($extracurriculars as $ekskul)
                <div class="bg-white/5 backdrop-blur-xl border border-white/10 p-6 rounded-[2.5rem] hover:border-elevate-accent/40 shadow-[0_8px_32px_rgba(0,0,0,0.3)] hover:shadow-[0_8px_32px_rgba(86,187,241,0.2)] hover:bg-white/10 transition-all duration-300 group hover:-translate-y-1.5 flex flex-col h-full" data-aos="fade-up">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-14 h-14 bg-white/5 border border-white/10 rounded-2xl flex items-center justify-center text-3xl text-elevate-accent shadow-sm group-hover:bg-gradient-to-br group-hover:from-elevate-accent group-hover:to-elevate-primary group-hover:border-elevate-accent/40 group-hover:text-white transition-all duration-300 overflow-hidden shrink-0">
                            @if(filter_var($ekskul->icon, FILTER_VALIDATE_URL) || preg_match('/\.(jpg|jpeg|png|gif|svg|webp)$/i', $ekskul->icon))
                                <img src="{{ asset($ekskul->icon) }}" loading="lazy" alt="{{ $ekskul->name }}" class="w-full h-full object-cover">
                            @else
                                <i class="{{ $ekskul->icon ?? 'ph-fill ph-star' }}"></i>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white leading-tight line-clamp-2 group-hover:text-elevate-accent transition-colors">{{ $ekskul->name }}</h3>
                            <div class="flex items-center gap-1.5 mt-1">
                                @if($lastActivity = $ekskul->attendances->first())
                                    <span class="relative flex h-2 w-2">
                                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-elevate-accent opacity-75"></span>
                                      <span class="relative inline-flex rounded-full h-2 w-2 bg-elevate-accent"></span>
                                    </span>
                                    <span class="text-[10px] font-bold text-elevate-accent uppercase tracking-wide">Aktif</span>
                                @else
                                    <span class="w-2 h-2 rounded-full bg-slate-600"></span>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Vakum</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="space-y-3 mt-auto">
                        <div class="bg-white/5 rounded-xl p-3 flex items-center gap-3 border border-white/5 group-hover:border-elevate-accent/30 transition-colors">
                            <i class="ph-duotone ph-clock text-elevate-accent text-lg"></i>
                            <div class="flex-1 min-w-0">
                                <p class="text-[10px] text-slate-400 font-bold uppercase">Jadwal</p>
                                <p class="text-xs text-slate-200 font-mono truncate">{{ $ekskul->schedule ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="bg-white/5 rounded-xl p-3 flex items-center gap-3 border border-white/5 group-hover:border-elevate-accent/30 transition-colors">
                            <i class="ph-duotone ph-user-circle text-elevate-peach text-lg"></i>
                            <div class="flex-1 min-w-0">
                                <p class="text-[10px] text-slate-400 font-bold uppercase">Pembina</p>
                                <p class="text-xs text-slate-200 truncate">{{ $ekskul->coach_name ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center text-slate-400 bg-white/5 backdrop-blur-xl rounded-[2.5rem] border-2 border-dashed border-white/10">Belum ada data ekstrakurikuler.</div>
            @endforelse
        </div>
    </div>
</div>