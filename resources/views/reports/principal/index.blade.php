<x-app-layout>
    <div class="py-8 sm:py-10 font-sans text-elevate-dark bg-elevate-surface min-h-screen relative overflow-hidden">
        
        {{-- Efek Latar Belakang Halus --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- HERO SECTION --}}
            <x-hero-section
                badge="Rekapitulasi KBM"
                badgeIcon="ph-books"
                title="Laporan Pembelajaran"
                titleHighlight="Kepala Sekolah"
                description="Pantau dan evaluasi hasil kegiatan belajar mengajar secara real-time. Anda dapat melihat progres materi, kehadiran siswa, dan dokumentasi langsung dari setiap kelas."
                :chips="[
                    ['icon' => 'ph-calendar-blank', 'label' => \Carbon\Carbon::parse($month . '-01')->translatedFormat('F Y')],
                    ['icon' => 'ph-users', 'label' => count($teachers ?? []) . ' Guru Aktif'],
                ]"
                heroIcon="ph-books"
                statusOrb="Data Sinkron"
                statusColor="emerald"
                ctaPrimaryText="Cetak Rekap (PDF)"
                ctaPrimaryHref="#"
                ctaPrimaryIcon="ph-printer"
                ctaSecondaryText="Dashboard Utama"
                ctaSecondaryHref="{{ route('dashboard') }}"
                ctaSecondaryIcon="ph-arrow-left"
            />

            {{-- SUMMARY METRICS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-xl shadow-slate-200/40 relative overflow-hidden flex flex-col justify-center">
                    <div class="absolute right-0 top-0 w-24 h-24 bg-sky-50 rounded-bl-full -mr-4 -mt-4 opacity-50"></div>
                    <div class="flex items-center gap-4 relative z-10">
                        <div class="w-14 h-14 rounded-2xl bg-sky-100 text-[#0d52a1] flex items-center justify-center text-2xl shadow-sm border border-sky-200 shrink-0">
                            <i class="ph-fill ph-chalkboard-teacher"></i>
                        </div>
                        <div>
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Total Sesi KBM</p>
                            <h4 class="text-3xl font-black text-elevate-dark">{{ $totalSessions }} <span class="text-sm font-bold text-slate-400">sesi</span></h4>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-xl shadow-slate-200/40 relative overflow-hidden flex flex-col justify-center">
                    <div class="absolute right-0 top-0 w-24 h-24 bg-emerald-50 rounded-bl-full -mr-4 -mt-4 opacity-50"></div>
                    <div class="flex items-center gap-4 relative z-10">
                        <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-2xl shadow-sm border border-emerald-200 shrink-0">
                            <i class="ph-fill ph-check-circle"></i>
                        </div>
                        <div>
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Materi Selesai</p>
                            <h4 class="text-3xl font-black text-elevate-dark">{{ $completionRate }}%</h4>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-xl shadow-slate-200/40 relative overflow-hidden flex flex-col justify-center group cursor-pointer hover:border-[#0d52a1]/20 transition-colors" onclick="document.getElementById('filter-guru').focus()">
                    <div class="absolute right-0 top-0 w-24 h-24 bg-purple-50 rounded-bl-full -mr-4 -mt-4 opacity-50"></div>
                    <div class="flex items-center gap-4 relative z-10">
                        <div class="w-14 h-14 rounded-2xl bg-purple-100 text-purple-600 flex items-center justify-center text-2xl shadow-sm border border-purple-200 shrink-0 group-hover:scale-110 transition-transform">
                            <i class="ph-fill ph-users"></i>
                        </div>
                        <div>
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Analisis Personal</p>
                            <h4 class="text-sm font-black text-elevate-dark mt-1">Lihat Kinerja Guru &rarr;</h4>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FILTER CARD --}}
            <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] p-6 md:p-8 rounded-[2.5rem] border border-white/10 shadow-2xl backdrop-blur-xl mb-8 relative overflow-hidden text-white">
                <div class="relative z-10">
                    <h3 class="font-black text-white text-lg flex items-center gap-3 mb-6">
                        <span class="bg-[#0d52a1]/20 text-sky-400 border border-sky-400/30 w-10 h-10 rounded-xl flex items-center justify-center"><i class="ph-bold ph-funnel text-xl"></i></span>
                        Filter Rekapitulasi
                    </h3>

                    <form method="GET" action="{{ route('reports.principal') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-5 items-end">
                        <div>
                            <label class="block text-xs font-bold text-sky-300 uppercase tracking-wider mb-2 ml-1">Bulan</label>
                            <input type="month" name="month" value="{{ $month }}" class="w-full rounded-2xl border border-white/10 bg-slate-900/80 font-bold text-white focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 h-14 px-5 transition-colors [color-scheme:dark]">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-sky-300 uppercase tracking-wider mb-2 ml-1">Guru</label>
                            <select name="teacher_id" id="filter-guru" class="w-full rounded-2xl border border-white/10 bg-slate-900/80 text-sm font-bold text-white focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 h-14 px-5 transition-colors [color-scheme:dark]">
                                <option value="" class="bg-slate-900 text-white">Semua Guru</option>
                                @foreach($teachers as $t) <option value="{{ $t->id }}" {{ request('teacher_id') == $t->id ? 'selected' : '' }} class="bg-slate-900 text-white">{{ $t->name }}</option> @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-sky-300 uppercase tracking-wider mb-2 ml-1">Mata Pelajaran</label>
                            <select name="subject_id" class="w-full rounded-2xl border border-white/10 bg-slate-900/80 text-sm font-bold text-white focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 h-14 px-5 transition-colors [color-scheme:dark]">
                                <option value="" class="bg-slate-900 text-white">Semua Mapel</option>
                                @foreach($subjects as $s) <option value="{{ $s->id }}" {{ request('subject_id') == $s->id ? 'selected' : '' }} class="bg-slate-900 text-white">{{ $s->name }}</option> @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-sky-300 uppercase tracking-wider mb-2 ml-1">Kelas</label>
                            <select name="class_id" class="w-full rounded-2xl border border-white/10 bg-slate-900/80 text-sm font-bold text-white focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 h-14 px-5 transition-colors [color-scheme:dark]">
                                <option value="" class="bg-slate-900 text-white">Semua Kelas</option>
                                @foreach($classes as $c) <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }} class="bg-slate-900 text-white">{{ $c->name }}</option> @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-sky-300 uppercase tracking-wider mb-2 ml-1">Status Materi</label>
                            <select name="material_status" class="w-full rounded-2xl border border-white/10 bg-slate-900/80 text-sm font-bold text-white focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 h-14 px-5 transition-colors [color-scheme:dark]">
                                <option value="" class="bg-slate-900 text-white">Semua</option>
                                <option value="completed" {{ request('material_status') == 'completed' ? 'selected' : '' }} class="bg-slate-900 text-white">Sudah Selesai</option>
                                <option value="incomplete" {{ request('material_status') == 'incomplete' ? 'selected' : '' }} class="bg-slate-900 text-white">Belum Selesai (Lanjut)</option>
                            </select>
                        </div>
                        <button type="submit" class="w-full h-14 bg-gradient-to-r from-[#0d52a1] to-sky-600 hover:from-sky-600 hover:to-[#0d52a1] text-white font-bold rounded-2xl transition-colors shadow-lg shadow-sky-950/40 flex items-center justify-center gap-2 group active:scale-95 border border-sky-400/30">
                            <i class="ph-bold ph-magnifying-glass text-lg"></i> Terapkan
                        </button>
                    </form>
                </div>
            </div>

            {{-- === CONTAINER TABEL UTAMA === --}}
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/40 overflow-hidden mb-10">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row items-center justify-between gap-4">
                    <h3 class="font-black text-elevate-dark text-lg flex items-center gap-2">
                        <i class="ph-fill ph-table text-[#0d52a1]"></i> Tabel Rincian Jurnal
                    </h3>
                </div>

                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse min-w-[900px]">
                        <thead class="bg-elevate-soft/50 border-b border-slate-100 text-elevate-primary">
                            <tr>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-wider text-center w-24">Tanggal</th>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-wider">Guru & Mapel</th>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-wider text-center w-24">Kelas</th>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-wider w-1/3">Materi & Aktivitas</th>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-wider text-center">Tugas & Deadline</th>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-wider text-center w-28">Kehadiran</th>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-wider text-center w-20">Bukti</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-sm">
                            @forelse($teachings as $session)
                                @php 
                                    $hadirTotal = ($session->hadir_count ?? 0) + ($session->late_count ?? 0); 
                                    $alphaTotal = $session->alpha_count ?? 0;
                                    $sakitTotal = $session->sick_count ?? 0;
                                    $izinTotal = $session->permit_count ?? 0;
                                @endphp
                                <tr class="hover:bg-elevate-soft/30 transition-colors group">
                                    <td class="px-6 py-5 text-center align-top">
                                        <div class="font-black text-elevate-dark bg-elevate-soft rounded-lg py-1.5 px-3 inline-block">{{ \Carbon\Carbon::parse($session->date)->format('d/m') }}</div>
                                        <div class="text-[10px] text-slate-400 font-mono mt-1.5 font-bold">{{ $session->started_at ? \Carbon\Carbon::parse($session->started_at)->format('H:i') : '-' }}</div>
                                    </td>
                                    <td class="px-6 py-5 align-top">
                                        <div class="font-black text-elevate-dark text-base hover:text-elevate-primary transition-colors">
                                            <a href="{{ route('reports.principal.teacher', $session->teacher_id) }}?month={{ $month }}" title="Lihat Lini Masa Guru">
                                                {{ $session->teacher->name ?? '-' }} <i class="ph-bold ph-arrow-up-right text-[10px]"></i>
                                            </a>
                                        </div>
                                        <div class="text-xs font-bold text-slate-500 mt-1 flex items-center gap-1.5">
                                            <i class="ph-bold ph-book-open text-elevate-primary"></i> {{ $session->timetable->subject->name ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-center align-top">
                                        <span class="inline-block px-3 py-1.5 rounded-lg border border-slate-200 font-bold text-xs bg-white text-slate-600 shadow-sm">
                                            {{ $session->timetable->studentClass->name ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 align-top">
                                        <div class="flex items-start justify-between gap-2 mb-1.5">
                                            <p class="font-black text-elevate-dark">{{ $session->topic ?? 'Tanpa Topik' }}</p>
                                            @if($session->material_status == 'completed')
                                                <span class="shrink-0 inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-600 text-[9px] font-black uppercase border border-emerald-100"><i class="ph-bold ph-check"></i> Tuntas</span>
                                            @elseif($session->material_status == 'incomplete')
                                                <span class="shrink-0 inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-amber-50 text-amber-600 text-[9px] font-black uppercase border border-amber-100"><i class="ph-bold ph-arrow-u-down-right"></i> Lanjut</span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-slate-500 font-semibold text-justify leading-relaxed">{{ $session->activities ?? '-' }}</p>
                                    </td>
                                    <td class="px-6 py-5 align-top">
                                        @if($session->homework_title)
                                            <div class="bg-purple-50 p-2.5 rounded-xl border border-purple-100">
                                                <p class="text-[11px] font-black text-purple-700 mb-1 leading-snug">{{ $session->homework_title }}</p>
                                                <p class="text-[10px] text-purple-600 font-medium mb-1.5 line-clamp-2">{{ $session->homework_description }}</p>
                                                @if($session->homework_deadline)
                                                    <span class="inline-flex items-center gap-1 text-[9px] font-bold text-purple-500"><i class="ph-bold ph-clock"></i> {{ \Carbon\Carbon::parse($session->homework_deadline)->format('d M, H:i') }}</span>
                                                @endif
                                            </div>
                                        @else
                                            <p class="text-xs text-slate-400 font-bold text-center mt-2">- Tidak Ada Tugas -</p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-5 align-top">
                                        <div class="flex flex-col gap-1.5">
                                            <div class="flex items-center justify-between text-[10px] font-black uppercase tracking-wide bg-[#DFF6DD] text-[#107C10] px-2 py-1 rounded border border-[#B7DFB9]"><span>Hadir</span> <span>{{ $hadirTotal }}</span></div>
                                            @if($alphaTotal > 0)<div class="flex items-center justify-between text-[10px] font-black uppercase tracking-wide bg-[#FDE7E9] text-[#D13438] px-2 py-1 rounded border border-[#F4C3C9]"><span>Alpha</span> <span>{{ $alphaTotal }}</span></div>@endif
                                            @if($sakitTotal > 0)<div class="flex items-center justify-between text-[10px] font-black uppercase tracking-wide bg-amber-50 text-amber-600 px-2 py-1 rounded border border-amber-200"><span>Sakit</span> <span>{{ $sakitTotal }}</span></div>@endif
                                            @if($izinTotal > 0)<div class="flex items-center justify-between text-[10px] font-black uppercase tracking-wide bg-blue-50 text-blue-600 px-2 py-1 rounded border border-blue-200"><span>Izin</span> <span>{{ $izinTotal }}</span></div>@endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-center align-top">
                                        <div class="flex flex-col gap-2 items-center">
                                            @if($session->photo_proof)
                                                @php
                                                    $photos = json_decode($session->photo_proof, true) ?? [$session->photo_proof];
                                                @endphp
                                                <a href="{{ asset('storage/' . $photos[0]) }}" target="_blank" class="w-12 h-12 rounded-xl bg-elevate-soft text-elevate-dark flex items-center justify-center hover:bg-elevate-dark hover:text-white transition-colors shadow-sm overflow-hidden group" title="Lihat Foto Dokumentasi">
                                                    <img src="{{ asset('storage/' . $photos[0]) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                                                </a>
                                                @if(count($photos) > 1)
                                                    <span class="text-[9px] font-black text-slate-400">+{{ count($photos)-1 }} foto</span>
                                                @endif
                                            @else
                                                <div class="w-12 h-12 rounded-xl bg-slate-50 text-slate-300 flex items-center justify-center border border-dashed border-slate-200" title="Tidak ada foto">
                                                    <i class="ph-bold ph-image-square text-xl"></i>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="px-6 py-24 text-center text-slate-400 font-bold italic">Tidak ada rekaman pembelajaran pada periode ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t border-slate-100 bg-white rounded-b-[2.5rem]">{{ $teachings->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
