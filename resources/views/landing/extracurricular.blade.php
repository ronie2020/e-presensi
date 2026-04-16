 <!-- EKSTRAKURIKULER -->
    <div id="ekskul" class="py-24 bg-slate-900 text-white relative overflow-hidden border-t border-slate-800">
        <!-- PERBAIKAN: Blobs (bercak) diubah ke warna Cyan dan Blue, badge juga disesuaikan -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-cyan-600 rounded-full mix-blend-overlay filter blur-[128px] opacity-20 animate-blob"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-blue-600 rounded-full mix-blend-overlay filter blur-[128px] opacity-20 animate-blob" style="animation-delay: 2s;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="px-3 py-1.5 bg-cyan-500/10 text-cyan-400 rounded-full text-xs font-bold uppercase tracking-widest border border-cyan-500/20 shadow-sm backdrop-blur-sm">
                    Bakat & Minat
                </span>
                <h2 class="text-3xl font-black text-white sm:text-4xl mt-4 tracking-tight">Ekstrakurikuler</h2>
                <p class="mt-4 text-lg text-slate-400 max-w-2xl mx-auto">
                    Wadah pengembangan potensi siswa di luar jam pelajaran akademik.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($extracurriculars as $ekskul)
                    <!-- PERBAIKAN: Gaya hover diubah ke Cyan dan efek glow ditambahkan -->
                    <div class="bg-slate-800/50 backdrop-blur-md border border-slate-700/50 p-6 rounded-[2rem] hover:border-cyan-500/50 hover:shadow-[0_0_30px_rgba(34,211,238,0.1)] transition-all duration-300 group hover:-translate-y-1.5 flex flex-col h-full" data-aos="fade-up">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-14 h-14 bg-slate-800/80 border border-slate-700 rounded-2xl flex items-center justify-center text-3xl text-cyan-400 shadow-lg group-hover:bg-gradient-to-br group-hover:from-cyan-500 group-hover:to-blue-600 group-hover:border-transparent group-hover:text-white transition-all duration-300 overflow-hidden shrink-0">
                                @if(filter_var($ekskul->icon, FILTER_VALIDATE_URL) || preg_match('/\.(jpg|jpeg|png|gif|svg|webp)$/i', $ekskul->icon))
                                    <img src="{{ asset($ekskul->icon) }}" loading="lazy" alt="{{ $ekskul->name }}" class="w-full h-full object-cover">
                                @else
                                    <i class="{{ $ekskul->icon ?? 'ph-fill ph-star' }}"></i>
                                @endif
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white leading-tight line-clamp-2 group-hover:text-cyan-300 transition-colors">{{ $ekskul->name }}</h3>
                                <div class="flex items-center gap-1.5 mt-1">
                                    @if($lastActivity = $ekskul->attendances->first())
                                        <span class="relative flex h-2 w-2">
                                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                                          <span class="relative inline-flex rounded-full h-2 w-2 bg-cyan-500"></span>
                                        </span>
                                        <span class="text-[10px] font-bold text-cyan-400 uppercase tracking-wide">Aktif</span>
                                    @else
                                        <span class="w-2 h-2 rounded-full bg-slate-600"></span>
                                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wide">Vakum</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="space-y-3 mt-auto">
                            <div class="bg-slate-900/50 rounded-xl p-3 flex items-center gap-3 border border-slate-700/30 group-hover:border-cyan-500/20 transition-colors">
                                <i class="ph-duotone ph-clock text-cyan-400 text-lg"></i>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[10px] text-slate-500 font-bold uppercase">Jadwal</p>
                                    <p class="text-xs text-slate-300 font-mono truncate">{{ $ekskul->schedule ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="bg-slate-900/50 rounded-xl p-3 flex items-center gap-3 border border-slate-700/30 group-hover:border-blue-500/20 transition-colors">
                                <i class="ph-duotone ph-user-circle text-blue-400 text-lg"></i>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[10px] text-slate-500 font-bold uppercase">Pembina</p>
                                    <p class="text-xs text-slate-300 truncate">{{ $ekskul->coach_name ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center text-slate-400">Belum ada data ekstrakurikuler.</div>
                @endforelse
            </div>
        </div>
    </div>