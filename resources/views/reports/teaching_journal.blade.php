<x-app-layout>
    <div class="py-8 sm:py-10 font-sans text-elevate-dark bg-elevate-surface min-h-screen relative overflow-hidden">
        
        {{-- Efek Latar Belakang Halus --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- HERO SECTION --}}
            <x-hero-section
                badge="Rekapitulasi KBM"
                badgeIcon="ph-chart-bar"
                title="Monitoring Jurnal"
                titleHighlight="KBM Guru"
                description="Rekapitulasi aktivitas belajar mengajar (KBM) guru beserta kehadiran siswa secara terperinci dan real-time."
                :chips="[
                    ['icon' => 'ph-calendar-blank', 'label' => 'Periode Terpilih'],
                    ['icon' => 'ph-users', 'label' => count($teachers ?? []) . ' Tenaga Pendidik'],
                    ['icon' => 'ph-file-pdf', 'label' => 'Siap Cetak PDF']
                ]"
                heroIcon="ph-chart-bar"
                statusOrb="Data Valid"
                statusColor="emerald"
                ctaPrimaryText="Cetak Laporan / PDF"
                ctaPrimaryHref="{{ route('reports.teaching_journal', array_merge(request()->all(), ['print' => 'true'])) }}"
                ctaPrimaryIcon="ph-printer"
                ctaSecondaryText="Dashboard Utama"
                ctaSecondaryHref="{{ route('dashboard') }}"
                ctaSecondaryIcon="ph-arrow-left"
            />

            {{-- FILTER CARD --}}
            <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] p-6 md:p-8 rounded-[2rem] border border-white/10 shadow-2xl backdrop-blur-xl mb-8 relative overflow-hidden text-white">
                <div class="relative z-10">
                    <h3 class="font-black text-white text-lg flex items-center gap-3 mb-6">
                        <span class="bg-[#0d52a1]/20 text-sky-400 border border-sky-400/30 w-10 h-10 rounded-xl flex items-center justify-center"><i class="ph-bold ph-faders text-xl"></i></span>
                        Filter Data
                    </h3>

                    <form method="GET" action="{{ route('reports.teaching_journal') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-5 items-end">
                        <div>
                            <label class="block text-xs font-bold text-sky-300 uppercase tracking-wider mb-2 ml-1">Dari Tanggal</label>
                            <input type="date" name="start_date" value="{{ $startDate ?? '' }}" class="w-full rounded-2xl border border-white/10 bg-slate-900/80 font-bold text-white focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 h-14 px-5 transition-colors [color-scheme:dark]">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-sky-300 uppercase tracking-wider mb-2 ml-1">Sampai Tanggal</label>
                            <input type="date" name="end_date" value="{{ $endDate ?? '' }}" class="w-full rounded-2xl border border-white/10 bg-slate-900/80 font-bold text-white focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 h-14 px-5 transition-colors [color-scheme:dark]">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-sky-300 uppercase tracking-wider mb-2 ml-1">Guru</label>
                            <select name="teacher_id" class="w-full rounded-2xl border border-white/10 bg-slate-900/80 text-sm font-bold text-white focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 h-14 px-5 transition-colors [color-scheme:dark]">
                                <option value="" class="bg-slate-900 text-white">Semua Guru</option>
                                @foreach($teachers as $t) <option value="{{ $t->id }}" {{ $teacherId == $t->id ? 'selected' : '' }} class="bg-slate-900 text-white">{{ $t->name }}</option> @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-sky-300 uppercase tracking-wider mb-2 ml-1">Kelas</label>
                            <select name="class_id" class="w-full rounded-2xl border border-white/10 bg-slate-900/80 text-sm font-bold text-white focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 h-14 px-5 transition-colors [color-scheme:dark]">
                                <option value="" class="bg-slate-900 text-white">Semua Kelas</option>
                                @foreach($classes as $c) <option value="{{ $c->id }}" {{ $classId == $c->id ? 'selected' : '' }} class="bg-slate-900 text-white">{{ $c->name }}</option> @endforeach
                            </select>
                        </div>
                        <button type="submit" class="w-full h-14 bg-gradient-to-r from-[#0d52a1] to-sky-600 hover:from-sky-600 hover:to-[#0d52a1] text-white font-bold rounded-2xl transition-colors shadow-lg shadow-sky-950/40 flex items-center justify-center gap-2 group active:scale-95 border border-sky-400/30">
                            <i class="ph-bold ph-magnifying-glass text-lg"></i> Terapkan
                        </button>
                    </form>
                </div>
            </div>

            {{-- === CONTAINER TABEL UTAMA === --}}
            {{-- Tidak perlu lagi class "print-container" atau class "no-print" karena cetaknya di file terpisah --}}
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/40 overflow-hidden">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-elevate-soft/50 border-b border-slate-100 text-elevate-primary">
                            <tr>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-wider text-center w-24">Tanggal</th>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-wider">Guru & Mapel</th>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-wider text-center w-24">Kelas</th>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-wider w-1/3">Materi & Aktivitas</th>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-wider text-center">Kehadiran</th>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-wider text-center w-20">Bukti</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-sm">
                            @forelse($sessions as $session)
                                @php $hadirTotal = ($session->hadir_count ?? 0) + ($session->late_count ?? 0); $alphaTotal = $session->alpha_count ?? 0; @endphp
                                <tr class="hover:bg-elevate-soft/30 transition-colors group">
                                    <td class="px-6 py-5 text-center align-top">
                                        <div class="font-black text-elevate-dark bg-elevate-soft rounded-lg py-1.5 px-3 inline-block">{{ \Carbon\Carbon::parse($session->date)->format('d/m') }}</div>
                                        <div class="text-[10px] text-slate-400 font-mono mt-1.5 font-bold">{{ $session->started_at ? \Carbon\Carbon::parse($session->started_at)->format('H:i') : '-' }}</div>
                                    </td>
                                    <td class="px-6 py-5 align-top">
                                        <div class="font-black text-elevate-dark text-base">{{ $session->teacher->name ?? '-' }}</div>
                                        <div class="text-xs font-bold text-elevate-primary mt-1 flex items-center gap-1.5">
                                            <i class="ph-bold ph-book-open"></i> {{ $session->timetable->subject->name ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-center align-top">
                                        <span class="inline-block px-3 py-1.5 rounded-lg border border-slate-200 font-bold text-xs bg-white text-slate-600 shadow-sm">
                                            {{ $session->timetable->studentClass->name ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 align-top">
                                        <p class="font-black text-elevate-dark mb-1.5">{{ $session->topic ?? 'Tanpa Topik' }}</p>
                                        <p class="text-xs text-slate-500 font-semibold text-justify leading-relaxed">{{ $session->activities ?? '-' }}</p>
                                    </td>
                                    <td class="px-6 py-5 text-center align-top">
                                        <div class="flex flex-col items-center gap-2">
                                            <span class="inline-flex items-center gap-1.5 text-[10px] font-black uppercase tracking-wide bg-[#DFF6DD] text-[#107C10] px-2.5 py-1 rounded border border-[#B7DFB9]">{{ $hadirTotal }} Hadir</span>
                                            @if($alphaTotal > 0)<span class="inline-flex items-center gap-1.5 text-[10px] font-black uppercase tracking-wide bg-[#FDE7E9] text-[#D13438] px-2.5 py-1 rounded border border-[#F4C3C9]">{{ $alphaTotal }} Alpha</span>@endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-center align-top">
                                        <div class="flex gap-2 justify-center">
                                            @if($session->photo_proof)<a href="{{ asset('storage/' . $session->photo_proof) }}" target="_blank" class="w-10 h-10 rounded-xl bg-elevate-soft text-elevate-dark flex items-center justify-center hover:bg-elevate-dark hover:text-white transition-colors shadow-sm"><i class="ph-bold ph-image text-lg"></i></a>@endif
                                            @if($session->reference_link || $session->video_link)<a href="{{ $session->reference_link ?? $session->video_link }}" target="_blank" class="w-10 h-10 rounded-xl bg-white text-elevate-primary flex items-center justify-center hover:bg-elevate-primary hover:text-white transition-colors shadow-sm border border-slate-200"><i class="ph-bold ph-link text-lg"></i></a>@endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-6 py-24 text-center text-slate-400 font-bold italic">Tidak ada data jurnal ditemukan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t border-slate-100 bg-white rounded-b-[2.5rem]">{{ $sessions->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>