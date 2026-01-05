<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Riwayat Mengajar') }}
        </h2>
    </x-slot>

    <div class="py-8 sm:py-10 font-sans text-slate-800">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HERO SECTION & FILTER --}}
            <div class="relative rounded-[2rem] bg-gray-900 bg-gradient-to-br from-slate-900 via-blue-900 to-blue-800 p-8 mb-10 text-white shadow-xl shadow-blue-900/30 overflow-hidden border border-white/10">
                {{-- Dekorasi Latar --}}
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-blue-400/10 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="text-center md:text-left">
                        <h2 class="text-3xl font-extrabold tracking-tight mb-1 flex items-center justify-center md:justify-start gap-3">
                            <span class="text-4xl"></span> Jejak Aktivitas
                        </h2>
                        <p class="text-blue-300 text-sm font-medium">
                            Rekapitulasi kegiatan mengajar Anda per bulan.
                        </p>
                    </div>

                    {{-- Filter Bulan --}}
                    <form method="GET" action="{{ route('teaching.history') }}" class="w-full md:w-auto relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="ph-bold ph-calendar-blank text-blue-300"></i>
                        </div>
                        <input type="month" name="month" value="{{ $month }}" onchange="this.form.submit()" 
                            class="pl-11 pr-5 py-3 w-full md:w-auto bg-white/10 border border-white/20 rounded-2xl text-sm font-bold text-white placeholder-blue-200 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 cursor-pointer hover:bg-white/20 transition-all shadow-lg backdrop-blur-sm">
                    </form>
                </div>
            </div>

            {{-- LIST RIWAYAT (TIMELINE) --}}
            <div class="space-y-8 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-200 before:to-transparent">
                
                @forelse($histories as $history)
                    <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                        
                        <!-- Icon Dot di Tengah -->
                        <div class="flex items-center justify-center w-12 h-12 rounded-full border-[6px] border-slate-50 bg-white shadow-md group-hover:bg-blue-600 group-hover:text-white text-slate-300 shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 transition-all duration-500 z-10 group-hover:scale-110 group-hover:shadow-blue-200">
                            <i class="ph-bold ph-check text-lg"></i>
                        </div>
                        
                        <!-- Konten Card -->
                        <div class="w-[calc(100%-4.5rem)] md:w-[calc(50%-3rem)] bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100 hover:shadow-xl hover:shadow-blue-900/5 hover:border-blue-200 hover:-translate-y-1 transition-all duration-300 group-hover:ring-1 group-hover:ring-blue-100">
                            
                            {{-- Header Card --}}
                            <div class="flex justify-between items-start mb-4 border-b border-slate-50 pb-3">
                                <div class="space-y-1">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 text-[10px] font-black uppercase tracking-wider border border-blue-100">
                                        <i class="ph-bold ph-users-three"></i> {{ $history->schedule->schoolClass->name ?? '-' }}
                                    </span>
                                    <h3 class="font-bold text-lg text-slate-800 leading-tight group-hover:text-blue-700 transition-colors">
                                        {{ $history->schedule->subject->name ?? 'Mapel Dihapus' }}
                                    </h3>
                                </div>
                                <div class="text-right bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-100 min-w-[60px]">
                                    <div class="text-xl font-black text-slate-800 leading-none">{{ \Carbon\Carbon::parse($history->date)->format('d') }}</div>
                                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">{{ \Carbon\Carbon::parse($history->date)->format('M') }}</div>
                                </div>
                            </div>

                            {{-- Info Materi --}}
                            <div class="bg-gradient-to-br from-slate-50 to-white rounded-2xl p-4 mb-4 border border-slate-100 group-hover:border-blue-50 transition-colors">
                                <p class="text-[10px] font-bold text-slate-400 uppercase mb-1 flex items-center gap-1">
                                    <i class="ph-bold ph-notebook text-blue-400"></i> Topik Bahasan
                                </p>
                                <p class="text-sm font-medium text-slate-700 line-clamp-2 leading-relaxed">
                                    {{ $history->topic ?? 'Tidak ada judul topik.' }}
                                </p>
                            </div>

                            {{-- Footer Stats & Action --}}
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3 bg-slate-50 rounded-xl px-3 py-1.5 border border-slate-100">
                                    <div class="flex items-center gap-1 text-xs font-bold text-emerald-600" title="Hadir">
                                        <i class="ph-fill ph-check-circle"></i> {{ $history->hadir }}
                                    </div>
                                    <div class="w-px h-3 bg-slate-200"></div>
                                    <div class="flex items-center gap-1 text-xs font-bold text-rose-500" title="Alpha/Absen">
                                        <i class="ph-fill ph-x-circle"></i> {{ $history->alpha }}
                                    </div>
                                </div>
                                
                                <a href="{{ route('teaching.show', $history->id) }}" class="group/link flex items-center gap-1 text-xs font-bold text-white bg-slate-800 hover:bg-blue-600 px-4 py-2 rounded-xl transition-all shadow-md hover:shadow-lg">
                                    Detail <i class="ph-bold ph-arrow-right group-hover/link:translate-x-1 transition-transform"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="relative flex flex-col items-center justify-center py-20 text-center z-10">
                        <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center shadow-sm border border-slate-100 mb-6 animate-pulse">
                            <i class="ph-duotone ph-notebook text-5xl text-slate-300"></i>
                        </div>
                        <h3 class="text-slate-800 font-bold text-xl">Belum Ada Riwayat</h3>
                        <p class="text-slate-500 text-sm mt-2 max-w-xs mx-auto leading-relaxed">
                            Aktivitas mengajar Anda di bulan <span class="font-bold text-slate-700">{{ \Carbon\Carbon::parse($month)->translatedFormat('F Y') }}</span> belum terekam.
                        </p>
                    </div>
                @endforelse

            </div>

            {{-- Pagination --}}
            <div class="mt-12 flex justify-center">
                {{ $histories->links() }}
            </div>

        </div>
    </div>
</x-app-layout>