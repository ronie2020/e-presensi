<x-app-layout>
    <div class="py-8 sm:py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HEADER --}}
            <div class="flex flex-col md:flex-row justify-between items-end mb-10 gap-4">
                <div>
                    <h1 class="text-3xl font-black text-slate-800 flex items-center gap-3">
                        <span class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center text-2xl">
                            <i class="ph-duotone ph-clock-counter-clockwise"></i>
                        </span>
                        Riwayat Mengajar
                    </h1>
                    <p class="text-slate-500 text-lg mt-2 ml-1">Jejak aktivitas pengajaran Anda.</p>
                </div>

                {{-- Filter Bulan --}}
                <form method="GET" action="{{ route('teaching.history') }}" class="relative group">
                    <i class="ph-bold ph-calendar-blank absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 group-hover:text-blue-500 transition-colors"></i>
                    <input type="month" name="month" value="{{ $month }}" onchange="this.form.submit()" 
                        class="pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-700 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer hover:border-blue-300 transition-colors">
                </form>
            </div>

            {{-- LIST RIWAYAT (TIMELINE) --}}
            <div class="space-y-8 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-200 before:to-transparent">
                
                @forelse($histories as $history)
                    <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                        
                        <!-- Icon Dot di Tengah -->
                        <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-white bg-slate-200 group-hover:bg-blue-500 group-hover:shadow-[0_0_0_4px_rgba(59,130,246,0.2)] text-slate-500 group-hover:text-white shadow-sm shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 transition-all duration-500 z-10">
                            <i class="ph-bold ph-check"></i>
                        </div>
                        
                        <!-- Konten Card -->
                        <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] p-6 bg-white rounded-[2rem] shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group-hover:border-blue-100">
                            
                            {{-- Header Card --}}
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 text-[10px] font-bold uppercase tracking-wider mb-2">
                                        {{ $history->schedule->schoolClass->name ?? '-' }}
                                    </span>
                                    <h3 class="font-bold text-lg text-slate-800 leading-tight">
                                        {{ $history->schedule->subject->name ?? 'Mapel Dihapus' }}
                                    </h3>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-black text-slate-800">{{ \Carbon\Carbon::parse($history->date)->format('d') }}</div>
                                    <div class="text-[10px] font-bold text-slate-400 uppercase">{{ \Carbon\Carbon::parse($history->date)->format('M Y') }}</div>
                                </div>
                            </div>

                            {{-- Info Materi --}}
                            <div class="bg-slate-50 rounded-xl p-3 mb-4 border border-slate-100">
                                <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Topik Bahasan</p>
                                <p class="text-sm font-medium text-slate-700 line-clamp-2">
                                    {{ $history->topic ?? 'Tidak ada judul topik.' }}
                                </p>
                            </div>

                            {{-- Footer Stats --}}
                            <div class="flex items-center justify-between pt-2 border-t border-slate-50">
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center gap-1 text-xs font-bold text-emerald-600" title="Hadir">
                                        <i class="ph-fill ph-check-circle"></i> {{ $history->hadir }}
                                    </div>
                                    <div class="flex items-center gap-1 text-xs font-bold text-rose-500" title="Alpha">
                                        <i class="ph-fill ph-x-circle"></i> {{ $history->alpha }}
                                    </div>
                                </div>
                                
                                <a href="{{ route('teaching.show', $history->id) }}" class="flex items-center gap-1 text-xs font-bold text-blue-600 hover:underline">
                                    Detail <i class="ph-bold ph-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="relative flex flex-col items-center justify-center py-12 text-center z-10">
                        <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center shadow-sm border border-slate-100 mb-4">
                            <i class="ph-duotone ph-notebook text-4xl text-slate-300"></i>
                        </div>
                        <h3 class="text-slate-800 font-bold text-lg">Belum Ada Riwayat</h3>
                        <p class="text-slate-500 text-sm mt-1 max-w-xs">Aktivitas mengajar Anda di bulan ini belum terekam.</p>
                    </div>
                @endforelse

            </div>

            {{-- Pagination --}}
            <div class="mt-10 flex justify-center">
                {{ $histories->links() }}
            </div>

        </div>
    </div>
</x-app-layout>