<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Riwayat Mengajar') }}
        </h2>
    </x-slot>

    @push('styles')
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>
    @endpush

    <div class="py-8 sm:py-10 font-sans text-slate-100 bg-[#020b18] min-h-screen relative overflow-hidden pb-20">
        
        {{-- Efek Latar Belakang Halus --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-gradient-to-r from-sky-600/20 via-blue-600/10 to-transparent pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- HERO SECTION --}}
            <x-hero-section
                badge="Arsip Aktivitas Guru"
                badgeIcon="ph-archive"
                title="Riwayat Mengajar"
                titleHighlight="Kelas & Presensi"
                description="Lihat kembali log aktivitas mengajar, materi yang disampaikan, dan catatan kehadiran siswa per sesi secara akurat."
                :chips="[
                    ['icon' => 'ph-clock-counter-clockwise', 'label' => 'Total ' . count($histories) . ' Riwayat'],
                    ['icon' => 'ph-calendar', 'label' => 'Filter: ' . ucfirst($filterType ?? 'Bulanan')],
                    ['icon' => 'ph-check-circle', 'label' => 'Terarsip Otomatis']
                ]"
                heroIcon="ph-clock-counter-clockwise"
                :showcaseNumber="count($histories)"
                showcaseLabel="Total Sesi"
                statusOrb="Terverifikasi"
                statusColor="sky"
            >
                <x-slot:cta>
                    <div class="w-full" x-data="{ filterType: '{{ $filterType ?? 'monthly' }}' }">
                        <form action="{{ route('teaching.history') }}" method="GET" class="flex flex-wrap items-center gap-2">
                            <div class="relative">
                                <select name="filter_type" x-model="filterType" class="bg-slate-900/80 border border-white/10 text-xs font-bold text-white rounded-xl px-3.5 py-2.5 focus:border-[#56bbf1] focus:ring-0 cursor-pointer shadow-sm appearance-none transition-all [color-scheme:dark]">
                                    <option value="daily" class="bg-slate-900 text-white font-bold">Harian</option>
                                    <option value="weekly" class="bg-slate-900 text-white font-bold">Mingguan</option>
                                    <option value="monthly" class="bg-slate-900 text-white font-bold">Bulanan</option>
                                </select>
                            </div>
                            <div class="relative">
                                <input style="display: none;" x-show="filterType === 'daily'" type="date" name="filter_value_daily" value="{{ $filterType == 'daily' ? $filterValue : \Carbon\Carbon::now()->format('Y-m-d') }}" :disabled="filterType !== 'daily'" class="bg-slate-900/80 border border-white/10 text-white text-xs font-bold rounded-xl px-3.5 py-2.5 shadow-sm cursor-pointer [color-scheme:dark]">
                                <input style="display: none;" x-show="filterType === 'weekly'" type="week" name="filter_value_weekly" value="{{ $filterType == 'weekly' ? $filterValue : \Carbon\Carbon::now()->format('Y-\WW') }}" :disabled="filterType !== 'weekly'" class="bg-slate-900/80 border border-white/10 text-white text-xs font-bold rounded-xl px-3.5 py-2.5 shadow-sm cursor-pointer [color-scheme:dark]">
                                <input style="display: none;" x-show="filterType === 'monthly'" type="month" name="filter_value_monthly" value="{{ $filterType == 'monthly' ? $filterValue : \Carbon\Carbon::now()->format('Y-m') }}" :disabled="filterType !== 'monthly'" class="bg-slate-900/80 border border-white/10 text-white text-xs font-bold rounded-xl px-3.5 py-2.5 shadow-sm cursor-pointer [color-scheme:dark]">
                            </div>
                            <button type="submit" class="px-4 py-2.5 bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white rounded-xl font-bold text-xs shadow-md transition-all active:scale-95 flex items-center gap-1.5 border border-white/10" title="Terapkan Filter">
                                <i class="ph-bold ph-magnifying-glass text-sm"></i> Filter
                            </button>
                            <a href="{{ route('dashboard') }}" class="px-4 py-2.5 rounded-xl bg-slate-800/80 hover:bg-slate-700 text-slate-300 hover:text-white font-bold text-xs tracking-wide border border-white/10 backdrop-blur-md transition-all flex items-center gap-1.5">
                                <i class="ph-bold ph-arrow-left text-sm"></i> Dashboard
                            </a>
                        </form>
                    </div>
                </x-slot:cta>
            </x-hero-section>

            {{-- LIST RIWAYAT --}}
            <div class="space-y-6">

                @forelse($histories as $index => $history)
                    <div class="animate-enter bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2rem] border border-white/10 shadow-2xl overflow-hidden flex flex-col md:flex-row group hover:border-sky-500/50 transition-colors backdrop-blur-xl" style="animation-delay: {{ ($index + 1) * 100 }}ms">
                        
                        {{-- KIRI: Tanggal & Waktu --}}
                        <div class="bg-slate-900/80 border-r border-white/10 p-6 md:w-56 flex flex-row md:flex-col items-center justify-between md:justify-center gap-4 shrink-0 transition-colors group-hover:bg-slate-800/80">
                            <div class="text-center">
                                <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ $history->date->translatedFormat('l') }}</div>
                                <div class="text-5xl font-black text-white leading-none">{{ $history->date->format('d') }}</div>
                                <div class="text-sm font-bold text-sky-400 mt-2">{{ $history->date->translatedFormat('M Y') }}</div>
                            </div>
                            
                            <div class="hidden md:block w-10 h-1 rounded-full bg-white/10 my-4"></div>
                            
                            <div class="text-center flex flex-col items-center">
                                <div class="bg-slate-900 border border-white/10 rounded-xl px-4 py-2 shadow-sm">
                                    <div class="text-sm font-black text-white">{{ $history->started_at->format('H:i') }}</div>
                                    <div class="text-[9px] text-slate-400 font-bold uppercase tracking-widest text-center mt-0.5">S/D</div>
                                    <div class="text-sm font-black text-white">{{ $history->ended_at ? $history->ended_at->format('H:i') : '...' }}</div>
                                </div>
                            </div>
                        </div>

                        {{-- KANAN: Detail Mengajar --}}
                        <div class="p-6 md:p-8 flex-1 flex flex-col justify-between text-white">
                            <div>
                                 <div class="flex flex-wrap items-center gap-2 mb-4">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wider bg-slate-800/80 text-slate-300 border border-white/10">
                                        <i class="ph-bold ph-book-open text-sky-400"></i> {{ $history->timetable->subject->name ?? 'Mata Pelajaran' }}
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wider bg-sky-500/20 text-sky-300 border border-sky-500/30">
                                        <i class="ph-bold ph-users-three"></i> Kelas {{ $history->timetable->studentClass->name ?? '-' }}
                                    </span>
                                    @if(isset($history->timetable->timeslot->name))
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wider bg-amber-500/20 text-amber-300 border border-amber-500/30">
                                        <i class="ph-bold ph-calendar-check"></i> {{ $history->timetable->timeslot->name }}
                                    </span>
                                    @endif
                                </div>                                
                                <h3 class="text-xl sm:text-2xl font-black text-white leading-tight mb-2 group-hover:text-sky-400 transition-colors">
                                    {{ $history->topic ?? 'Tidak Ada Topik' }}
                                </h3>
                                
                                <p class="text-sm text-slate-300 mb-6 font-medium leading-relaxed line-clamp-2 md:line-clamp-3">
                                    {{ $history->activities ?? 'Tidak ada catatan aktivitas untuk sesi ini.' }}
                                </p>
                            </div>

                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5 mt-auto pt-5 border-t border-white/10">
                                
                                {{-- Info Kehadiran Semantik Elevate --}}
                                <div class="flex items-center gap-3 flex-wrap">
                                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-emerald-950/60 border border-emerald-500/30 text-emerald-300 shadow-sm" title="Siswa Hadir">
                                        <i class="ph-fill ph-check-circle text-emerald-400 text-base"></i>
                                        <span class="text-sm font-black">{{ $history->total_hadir }}</span>
                                    </div>
                                    @if($history->jml_telat > 0)
                                        <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-amber-950/60 border border-amber-500/30 text-amber-300 shadow-sm" title="Siswa Terlambat">
                                            <i class="ph-fill ph-clock-warning text-amber-400 text-base"></i>
                                            <span class="text-sm font-black">{{ $history->jml_telat }}</span>
                                        </div>
                                    @endif
                                    @if($history->jml_alpha > 0)
                                        <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-rose-950/60 border border-rose-500/30 text-rose-300 shadow-sm" title="Siswa Alpha">
                                            <i class="ph-fill ph-x-circle text-rose-400 text-base"></i>
                                            <span class="text-sm font-black">{{ $history->jml_alpha }}</span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Aksi --}}
                                <a href="{{ route('teaching.show', $history->id) }}" class="group/link w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white px-6 py-3.5 rounded-xl text-sm font-bold shadow-lg shadow-sky-500/20 transition-all active:scale-95 border border-white/10">
                                    <span>Detail Sesi</span> 
                                    <i class="ph-bold ph-arrow-right group-hover/link:translate-x-1 transition-transform"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="animate-enter relative flex flex-col items-center justify-center py-24 text-center z-10 bg-slate-900/60 rounded-[2.5rem] border-2 border-dashed border-white/10 shadow-sm">
                        <div class="w-24 h-24 sm:w-28 sm:h-28 bg-slate-800/80 border border-white/10 rounded-full flex items-center justify-center shadow-sm mb-6 text-sky-400">
                            <i class="ph-duotone ph-notebook text-5xl sm:text-6xl"></i>
                        </div>
                        <h3 class="text-white font-black text-xl sm:text-2xl">Belum Ada Riwayat</h3>
                        <p class="text-slate-400 text-sm mt-3 max-w-sm mx-auto font-medium leading-relaxed">
                            Aktivitas mengajar Anda pada <span class="font-bold text-slate-200">{{ $filterLabel }}</span> belum terekam.
                        </p>
                        <a href="{{ route('teaching.index') }}" class="mt-8 px-8 py-3.5 bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white font-bold rounded-2xl shadow-lg transition-all flex items-center gap-2 active:scale-95 border border-white/10 text-sm">
                            <i class="ph-bold ph-calendar-check"></i> Cek Jadwal Hari Ini
                        </a>
                    </div>
                @endforelse

            </div>

            {{-- Pagination --}}
            <div class="mt-12 flex justify-center animate-enter" style="animation-delay: 300ms">
                {{ $histories->appends(request()->query())->links() }}
            </div>

        </div>
    </div>
</x-app-layout>