<x-app-layout>
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 10px; }
    </style>

    {{-- Elevate Dark Glass Theme untuk Radar Command Center --}}
    <div class="py-8 sm:py-10 bg-[#020b18] min-h-screen font-sans text-slate-100 relative overflow-hidden"> 
        {{-- Efek Latar Belakang Halus --}}
        <div class="bg-gradient-to-b from-[#0d52a1]/20 via-[#031d3d]/10 to-transparent opacity-30 pointer-events-none -z-10 blur-3xl absolute inset-0"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- Header Radar --}}
            <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-rose-500/20 text-xs font-bold uppercase tracking-widest text-rose-300 border border-rose-500/30 mb-3 shadow-sm backdrop-blur-md">
                        <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse shadow-[0_0_8px_rgba(244,63,94,0.8)]"></span> Live Radar Sistem
                    </div>
                    <h1 class="text-3xl md:text-4xl font-black text-white tracking-tight flex items-center gap-3">
                        <i class="ph-fill ph-radar text-[#56bbf1]"></i> Early Warning System
                    </h1>
                    <p class="text-slate-400 text-sm mt-2 max-w-2xl font-medium">Daftar kasus siswa yang ditangkap otomatis oleh sistem berdasarkan akumulasi absen dan poin kedisiplinan. Segera tindak lanjuti.</p>
                </div>
                <div>
                    <a href="{{ route('admin.bk.index') }}" class="px-5 py-2.5 bg-white/10 hover:bg-white/20 text-slate-200 text-sm font-bold rounded-xl border border-white/15 transition-all flex items-center gap-2 shadow-lg backdrop-blur-md active:scale-95">
                        <i class="ph-bold ph-list-dashes text-[#56bbf1]"></i> Lihat Semua Antrean BK
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                {{-- KIRI: KASUS KRITIS (PELANGGARAN) --}}
                <div class="space-y-4">
                    <h3 class="text-lg md:text-xl font-black text-rose-400 flex items-center justify-between border-b border-rose-500/20 pb-3">
                        <span class="flex items-center gap-2"><i class="ph-fill ph-warning text-2xl"></i> Kasus Kritis (Butuh Tindakan)</span>
                        <span class="bg-rose-500 text-white text-xs px-2.5 py-0.5 rounded-full font-bold shadow-sm">{{ $criticalViolations->count() }}</span>
                    </h3>
                    
                    <div class="space-y-4 max-h-[70vh] overflow-y-auto pr-2 custom-scrollbar">
                        @forelse($criticalViolations as $cv)
                            <div onclick="window.location.href='{{ route('admin.bk.show', $cv->id) }}'" class="cursor-pointer bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2rem] p-6 border border-rose-500/30 hover:border-rose-500/60 transition-all shadow-2xl relative overflow-hidden group backdrop-blur-xl">
                                <div class="absolute top-0 right-0 p-4 opacity-5 pointer-events-none group-hover:opacity-10 transition-opacity">
                                    <i class="ph-fill ph-siren text-8xl text-rose-500"></i>
                                </div>

                                <div class="flex justify-between items-start mb-4 relative z-10">
                                    <div class="flex items-center gap-4">
                                        <div class="w-14 h-14 rounded-full bg-slate-900/80 flex items-center justify-center text-[#56bbf1] font-black text-xl border border-white/15 shadow-inner">
                                            {{ substr($cv->student?->name ?? '?', 0, 1) }}
                                        </div>
                                        <div>
                                            <h4 class="font-black text-white text-lg group-hover:text-[#56bbf1] transition-colors">{{ $cv->student?->name ?? 'Data Siswa Dihapus' }}</h4>
                                            <p class="text-xs text-slate-400 mt-0.5">
                                                <span class="bg-white/10 px-2 py-0.5 rounded text-slate-200 font-bold mr-1">Kelas {{ $cv->student?->schoolClass?->name ?? '-' }}</span> 
                                                NISN: {{ $cv->student?->student_id ?? '-' }}
                                            </p>
                                        </div>
                                    </div>
                                    <span class="bg-rose-500/20 text-rose-300 px-3 py-1.5 rounded-xl text-[10px] font-black border border-rose-500/30 uppercase tracking-widest animate-pulse shadow-sm">
                                        Urgent
                                    </span>
                                </div>
                                
                                <div class="bg-slate-950/80 p-4 rounded-2xl border border-white/10 text-sm text-rose-300 font-mono mb-5 shadow-inner relative z-10 flex items-start gap-3">
                                    <i class="ph-fill ph-robot text-rose-400 text-lg shrink-0 mt-0.5"></i> 
                                    <div class="leading-relaxed">
                                        {!! nl2br(e($cv->initial_message)) !!}
                                    </div>
                                </div>

                                <div class="relative z-10">
                                    <div class="w-full flex items-center justify-center gap-2 py-3.5 bg-rose-600 hover:bg-rose-500 text-white font-bold rounded-xl text-sm transition-all shadow-lg shadow-rose-950/50 active:scale-95 border border-rose-400/30">
                                        <i class="ph-bold ph-shield-check text-lg"></i> Proses Kasus Ini
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] p-10 border border-white/10 text-center border-dashed backdrop-blur-xl">
                                <div class="inline-flex items-center justify-center w-20 h-20 bg-emerald-500/10 rounded-full mb-4 border border-emerald-500/30">
                                    <i class="ph-duotone ph-shield-check text-5xl text-emerald-400"></i>
                                </div>
                                <p class="text-white font-black text-lg">Aman Terkendali</p>
                                <p class="text-sm text-slate-400 mt-1 max-w-xs mx-auto font-medium">Sistem tidak mendeteksi adanya siswa dengan pelanggaran ekstrem saat ini.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- KANAN: APRESIASI PRESTASI --}}
                <div class="space-y-4">
                    <h3 class="text-lg md:text-xl font-black text-emerald-400 flex items-center justify-between border-b border-emerald-500/20 pb-3">
                        <span class="flex items-center gap-2"><i class="ph-fill ph-medal text-2xl"></i> Layak Diapresiasi</span>
                        <span class="bg-emerald-500 text-white text-xs px-2.5 py-0.5 rounded-full font-bold shadow-sm">{{ $meritsToAppreciate->count() }}</span>
                    </h3>
                    
                    <div class="space-y-4 max-h-[70vh] overflow-y-auto pr-2 custom-scrollbar">
                        @forelse($meritsToAppreciate as $ma)
                            <div onclick="window.location.href='{{ route('admin.bk.show', $ma->id) }}'" class="cursor-pointer bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2rem] p-6 border border-emerald-500/30 hover:border-emerald-500/60 transition-all shadow-2xl relative overflow-hidden group backdrop-blur-xl">
                                <div class="absolute top-0 right-0 p-4 opacity-5 pointer-events-none group-hover:opacity-10 transition-opacity">
                                    <i class="ph-fill ph-star text-8xl text-emerald-500"></i>
                                </div>

                                <div class="flex justify-between items-start mb-4 relative z-10">
                                    <div class="flex items-center gap-4">
                                        <div class="w-14 h-14 rounded-full bg-slate-900/80 flex items-center justify-center text-[#56bbf1] font-black text-xl border border-white/15 shadow-inner">
                                            {{ substr($ma->student?->name ?? '?', 0, 1) }}
                                        </div>
                                        <div>
                                            <h4 class="font-black text-white text-lg group-hover:text-emerald-400 transition-colors">{{ $ma->student?->name ?? 'Data Siswa Dihapus' }}</h4>
                                            <p class="text-xs text-slate-400 mt-0.5">
                                                <span class="bg-white/10 px-2 py-0.5 rounded text-slate-200 font-bold mr-1">Kelas {{ $ma->student?->schoolClass?->name ?? '-' }}</span>
                                            </p>
                                        </div>
                                    </div>
                                    <span class="bg-emerald-500/20 text-emerald-300 px-3 py-1.5 rounded-xl text-[10px] font-black border border-emerald-500/30 uppercase tracking-widest shadow-sm">
                                        Reward
                                    </span>
                                </div>
                                
                                <div class="bg-slate-950/80 p-4 rounded-2xl border border-white/10 text-sm text-emerald-300 font-mono mb-5 shadow-inner relative z-10 flex items-start gap-3">
                                    <i class="ph-fill ph-robot text-emerald-400 text-lg shrink-0 mt-0.5"></i> 
                                    <div class="leading-relaxed">
                                        {!! nl2br(e($ma->initial_message)) !!}
                                    </div>
                                </div>

                                <div class="relative z-10">
                                    <div class="w-full flex items-center justify-center gap-2 py-3.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl text-sm transition-all shadow-lg shadow-emerald-950/50 active:scale-95 border border-emerald-400/30">
                                        <i class="ph-bold ph-gift text-lg"></i> Berikan Reward / Selesai
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] p-10 border border-white/10 text-center border-dashed backdrop-blur-xl">
                                <div class="inline-flex items-center justify-center w-20 h-20 bg-white/5 rounded-full mb-4 border border-white/10">
                                    <i class="ph-duotone ph-star text-5xl text-slate-500"></i>
                                </div>
                                <p class="text-white font-black text-lg">Belum Ada Target</p>
                                <p class="text-slate-400 text-sm mt-1 max-w-xs mx-auto font-medium">Belum ada siswa yang mencapai batas poin prestasi (+100) untuk diapresiasi otomatis oleh sistem.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>