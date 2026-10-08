<x-app-layout>
    <div class="py-8 sm:py-10 font-sans text-elevate-dark bg-elevate-surface min-h-screen relative overflow-hidden">
        
        {{-- Efek Latar Belakang Halus --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        <div x-data="{ teacherModalOpen: false, teacherSearch: '' }" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            
            {{-- HERO SECTION --}}
            <x-hero-section
                badge="Rekapitulasi KBM"
                badgeIcon="ph-books"
                title="Laporan Pembelajaran"
                titleHighlight="Kepala Sekolah"
                description="Pantau dan evaluasi hasil kegiatan belajar mengajar secara real-time. Anda dapat melihat progres materi, kehadiran siswa, dan dokumentasi langsung dari setiap kelas."
                :chips="[
                    ['icon' => 'ph-calendar-blank', 'label' => \Carbon\Carbon::parse($month . '-01')->translatedFormat('F Y')],
                    ['icon' => 'ph-users', 'label' => count($teachers ?? []) . ' Guru Terdata'],
                ]"
                heroIcon="ph-books"
                statusOrb="Data Sinkron"
                statusColor="emerald"
                ctaPrimaryText="Cetak Rekap (PDF)"
                ctaPrimaryHref="{{ route('reports.principal.print', request()->all()) }}"
                ctaPrimaryTarget="_blank"
                ctaPrimaryIcon="ph-printer"
                ctaSecondaryText="Dashboard Utama"
                ctaSecondaryHref="{{ route('dashboard') }}"
                ctaSecondaryIcon="ph-arrow-left"
            />

            {{-- SUMMARY METRICS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- 1. TOTAL SESI KBM -->
                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-xl shadow-slate-200/40 relative overflow-hidden flex flex-col justify-center">
                    <div class="absolute right-0 top-0 w-24 h-24 bg-sky-50 rounded-bl-full -mr-4 -mt-4 opacity-50" style="background-color: rgba(14, 165, 233, 0.18);"></div>
                    <div class="flex items-center gap-4 relative z-10">
                        <div class="w-14 h-14 rounded-2xl bg-sky-100 text-sky-400 flex items-center justify-center text-2xl shadow-sm border border-sky-200 shrink-0" style="background-color: rgba(14, 165, 233, 0.18); border-color: rgba(14, 165, 233, 0.3); color: #38bdf8;">
                            <i class="ph-fill ph-chalkboard-teacher"></i>
                        </div>
                        <div>
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Total Sesi KBM</p>
                            <h4 class="text-3xl font-black text-elevate-dark">{{ $totalSessions }} <span class="text-sm font-bold text-slate-400">sesi</span></h4>
                        </div>
                    </div>
                </div>

                <!-- 2. MATERI SELESAI -->
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

                <!-- 3. ANALISIS PERSONAL -->
                @php
                    $selectedTeacherId = request('teacher_id');
                    $activeFilteredTeacher = $selectedTeacherId ? $teachers->firstWhere('id', $selectedTeacherId) : null;
                @endphp
                @if($activeFilteredTeacher)
                    <a href="{{ route('reports.principal.teacher', $activeFilteredTeacher->id) }}?month={{ $month }}" 
                       class="bg-white rounded-3xl p-6 border border-slate-100 shadow-xl shadow-slate-200/40 relative overflow-hidden flex flex-col justify-center group cursor-pointer hover:border-[#0d52a1]/20 transition-colors"
                       title="Lihat Lini Masa Kinerja {{ $activeFilteredTeacher->name }}">
                        <div class="absolute right-0 top-0 w-24 h-24 bg-purple-50 rounded-bl-full -mr-4 -mt-4 opacity-50"></div>
                        <div class="flex items-center gap-4 relative z-10">
                            <div class="w-14 h-14 rounded-2xl bg-purple-100 text-purple-600 flex items-center justify-center text-2xl shadow-sm border border-purple-200 shrink-0 group-hover:scale-110 transition-transform">
                                <i class="ph-fill ph-users"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1 truncate">Kinerja Guru</p>
                                <h4 class="text-sm font-black text-elevate-dark mt-1 flex items-center gap-1.5 truncate">
                                    {{ Str::limit($activeFilteredTeacher->name, 16) }} <span class="text-xs text-purple-400 font-bold">&rarr;</span>
                                </h4>
                            </div>
                        </div>
                    </a>
                @else
                    <div @click="teacherModalOpen = true" 
                         class="bg-white rounded-3xl p-6 border border-slate-100 shadow-xl shadow-slate-200/40 relative overflow-hidden flex flex-col justify-center group cursor-pointer hover:border-[#0d52a1]/20 transition-colors"
                         title="Klik untuk memilih guru dan melihat analisis personal">
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
                @endif
            </div>

            {{-- FILTER CARD --}}
            <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] p-6 md:p-8 rounded-[2.5rem] border border-white/10 shadow-2xl backdrop-blur-xl mb-8 relative overflow-hidden text-white">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="font-black text-white text-lg flex items-center gap-3">
                            <span class="bg-[#0d52a1]/20 text-sky-400 border border-sky-400/30 w-10 h-10 rounded-xl flex items-center justify-center"><i class="ph-bold ph-funnel text-xl"></i></span>
                            Filter Rekapitulasi
                        </h3>
                        @if(request()->hasAny(['teacher_id', 'subject_id', 'class_id', 'material_status', 'month']))
                            <a href="{{ route('reports.principal') }}" class="text-xs font-bold text-sky-400 hover:text-white inline-flex items-center gap-1.5 transition-colors">
                                <i class="ph-bold ph-arrow-counter-clockwise"></i> Reset Filter
                            </a>
                        @endif
                    </div>

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
                        <div class="flex items-center gap-2">
                            <button type="submit" class="flex-1 h-14 bg-gradient-to-r from-[#0d52a1] to-sky-600 hover:from-sky-600 hover:to-[#0d52a1] text-white font-bold rounded-2xl transition-all shadow-lg shadow-sky-950/40 flex items-center justify-center gap-2 group active:scale-95 border border-sky-400/30 text-sm cursor-pointer">
                                <i class="ph-bold ph-magnifying-glass text-lg"></i> Terapkan
                            </button>
                            @if(request()->hasAny(['teacher_id', 'subject_id', 'class_id', 'material_status', 'month']))
                                <a href="{{ route('reports.principal') }}" class="h-14 px-4 bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white rounded-2xl border border-white/15 flex items-center justify-center transition-all" title="Reset Filter">
                                    <i class="ph-bold ph-arrow-counter-clockwise text-lg"></i>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            {{-- === CONTAINER TABEL UTAMA === --}}
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/40 overflow-hidden mb-10">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row items-center justify-between gap-4">
                    <div>
                        <h3 class="font-black text-elevate-dark text-lg flex items-center gap-2">
                            <i class="ph-fill ph-table text-[#0d52a1]"></i> Tabel Rincian Jurnal
                        </h3>
                        <p class="text-xs font-bold text-slate-400 mt-0.5">Menampilkan {{ $teachings->total() }} data jurnal pembelajaran</p>
                    </div>

                    <div class="flex items-center gap-3 w-full md:w-auto">
                        <a href="{{ route('reports.principal.print', request()->all()) }}" target="_blank" class="w-full md:w-auto px-5 py-2.5 bg-gradient-to-r from-[#0d52a1] to-sky-600 hover:from-sky-600 hover:to-[#0d52a1] text-white text-xs font-bold rounded-xl shadow-md shadow-sky-900/10 active:scale-95 transition-all inline-flex items-center justify-center gap-2 cursor-pointer">
                            <i class="ph-bold ph-printer text-base"></i> Cetak Rekap (PDF)
                        </a>
                    </div>
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
                                    $hasAttendance = ($hadirTotal + $alphaTotal + $sakitTotal + $izinTotal) > 0;

                                    $subjectName = $session->subject->name ?? ($session->timetable->subject->name ?? ($session->schedule->subject->name ?? '-'));
                                    $className = $session->schoolClass->name ?? ($session->timetable->studentClass->name ?? ($session->schedule->studentClass->name ?? '-'));
                                @endphp
                                <tr class="hover:bg-elevate-soft/30 transition-colors group">
                                    <td class="px-6 py-5 text-center align-top">
                                        <div class="font-black text-elevate-dark bg-elevate-soft rounded-lg py-1.5 px-3 inline-block">{{ $session->date ? \Carbon\Carbon::parse($session->date)->format('d/m') : '-' }}</div>
                                        <div class="text-[10px] text-slate-400 font-mono mt-1.5 font-bold">{{ $session->started_at ? \Carbon\Carbon::parse($session->started_at)->format('H:i') : '-' }}</div>
                                    </td>
                                    <td class="px-6 py-5 align-top">
                                        <div class="font-black text-elevate-dark text-base hover:text-elevate-primary transition-colors">
                                            <a href="{{ route('reports.principal.teacher', $session->teacher_id ?? 0) }}?month={{ $month }}" title="Lihat Lini Masa Guru">
                                                {{ $session->teacher->name ?? '-' }} <i class="ph-bold ph-arrow-up-right text-[10px]"></i>
                                            </a>
                                        </div>
                                        <div class="text-xs font-bold text-slate-500 mt-1 flex items-center gap-1.5">
                                            <i class="ph-bold ph-book-open text-elevate-primary"></i> {{ $subjectName }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-center align-top">
                                        <span class="inline-block px-3 py-1.5 rounded-lg border border-slate-200 font-bold text-xs bg-white text-slate-600 shadow-sm">
                                            {{ $className }}
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
                                        @if($hasAttendance)
                                            <div class="flex flex-col gap-1.5">
                                                <div class="flex items-center justify-between text-[10px] font-black uppercase tracking-wide bg-[#DFF6DD] text-[#107C10] px-2 py-1 rounded border border-[#B7DFB9]"><span>Hadir</span> <span>{{ $hadirTotal }}</span></div>
                                                @if($alphaTotal > 0)<div class="flex items-center justify-between text-[10px] font-black uppercase tracking-wide bg-[#FDE7E9] text-[#D13438] px-2 py-1 rounded border border-[#F4C3C9]"><span>Alpha</span> <span>{{ $alphaTotal }}</span></div>@endif
                                                @if($sakitTotal > 0)<div class="flex items-center justify-between text-[10px] font-black uppercase tracking-wide bg-amber-50 text-amber-600 px-2 py-1 rounded border border-amber-200"><span>Sakit</span> <span>{{ $sakitTotal }}</span></div>@endif
                                                @if($izinTotal > 0)<div class="flex items-center justify-between text-[10px] font-black uppercase tracking-wide bg-blue-50 text-blue-600 px-2 py-1 rounded border border-blue-200"><span>Izin</span> <span>{{ $izinTotal }}</span></div>@endif
                                            </div>
                                        @else
                                            <span class="text-[11px] text-slate-400 font-bold block text-center mt-2 italic">Belum Ada Presensi</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-5 text-center align-top">
                                        <div class="flex flex-col gap-2 items-center">
                                            @if($session->photo_proof)
                                                @php
                                                    $photos = json_decode($session->photo_proof, true) ?? [$session->photo_proof];
                                                    $firstPhoto = $photos[0] ?? null;
                                                    $photoSrc = $firstPhoto ? (str_starts_with($firstPhoto, 'http') ? $firstPhoto : asset('storage/' . $firstPhoto)) : null;
                                                @endphp
                                                @if($photoSrc)
                                                    <a href="{{ $photoSrc }}" target="_blank" class="w-12 h-12 rounded-xl bg-elevate-soft text-elevate-dark flex items-center justify-center hover:bg-elevate-dark hover:text-white transition-colors shadow-sm overflow-hidden group" title="Lihat Foto Dokumentasi">
                                                        <img src="{{ $photoSrc }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                                                    </a>
                                                    @if(count($photos) > 1)
                                                        <span class="text-[9px] font-black text-slate-400">+{{ count($photos)-1 }} foto</span>
                                                    @endif
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

            {{-- MODAL PILIH GURU UNTUK ANALISIS PERSONAL (TELEPORT KE BODY) --}}
            <template x-teleport="body">
                <div x-show="teacherModalOpen" 
                     x-cloak
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     @keydown.escape.window="teacherModalOpen = false"
                     class="fixed inset-0 z-[9999] overflow-y-auto"
                     style="display: none;"
                     role="dialog"
                     aria-modal="true">
                     
                    <!-- Backdrop -->
                    <div class="fixed inset-0 bg-[#021124]/85 backdrop-blur-md transition-opacity" @click="teacherModalOpen = false"></div>

                    <!-- Centering Wrapper with safe scroll margin -->
                    <div class="min-h-full flex items-center justify-center p-4 sm:p-6 lg:p-8 relative pointer-events-none">

                        <!-- Modal Dialog -->
                        <div x-show="teacherModalOpen"
                             x-transition:enter="transition ease-out duration-300 transform"
                             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-200 transform"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                             class="pointer-events-auto relative bg-gradient-to-b from-[#031d3d] via-[#021124] to-[#020b18] rounded-[2.5rem] border border-white/15 shadow-2xl shadow-black/80 w-full max-w-2xl max-h-[85vh] flex flex-col overflow-hidden text-white my-auto">
                         
                            <!-- Modal Header -->
                            <div class="p-6 sm:p-8 border-b border-white/10 flex items-center justify-between gap-4">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-12 h-12 rounded-2xl bg-purple-500/20 text-purple-300 border border-purple-400/30 flex items-center justify-center text-2xl shadow-sm shrink-0">
                                        <i class="ph-bold ph-chalkboard-teacher"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-black text-white tracking-tight">Pilih Guru untuk Analisis Personal</h3>
                                        <p class="text-xs text-slate-400 font-semibold mt-0.5">Lihat lini masa aktivitas pembelajaran dan rincian KBM guru</p>
                                    </div>
                                </div>
                                <button type="button" @click="teacherModalOpen = false" class="w-10 h-10 rounded-xl bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white flex items-center justify-center transition-colors cursor-pointer border border-white/10">
                                    <i class="ph-bold ph-x text-lg"></i>
                                </button>
                            </div>

                            <!-- Search Input -->
                            <div class="p-6 border-b border-white/10 bg-white/[0.02]">
                                <div class="relative">
                                    <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
                                    <input type="text" 
                                           x-model="teacherSearch" 
                                           placeholder="Ketik nama atau NIP guru..." 
                                           class="w-full pl-12 pr-10 py-3.5 rounded-2xl bg-slate-900/80 border border-white/10 text-sm font-bold text-white placeholder-slate-500 focus:border-purple-400 focus:ring-2 focus:ring-purple-400/20 transition-all outline-none">
                                    <button type="button" x-show="teacherSearch.length > 0" @click="teacherSearch = ''" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-white text-sm" style="display: none;">
                                        <i class="ph-bold ph-x-circle text-lg"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Teacher List -->
                            <div class="p-6 overflow-y-auto space-y-3 custom-scrollbar flex-1 min-h-0 max-h-[50vh]">
                                @forelse($teachers as $t)
                                    <a href="{{ route('reports.principal.teacher', $t->id) }}?month={{ $month }}"
                                       x-show="teacherSearch === '' || '{{ strtolower(addslashes($t->name)) }} {{ strtolower(addslashes($t->nip ?? '')) }}'.includes(teacherSearch.toLowerCase())"
                                       class="flex items-center justify-between p-4 rounded-2xl bg-white/[0.03] hover:bg-white/[0.08] border border-white/5 hover:border-purple-400/30 transition-all group cursor-pointer">
                                        <div class="flex items-center gap-3.5 min-w-0">
                                            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-[#0d52a1] to-purple-600 text-white flex items-center justify-center text-sm font-black shrink-0 shadow-sm border border-white/15 overflow-hidden">
                                                @if($t->photo)
                                                    <img src="{{ asset('storage/' . $t->photo) }}" class="w-full h-full object-cover">
                                                @else
                                                    {{ strtoupper(substr($t->name, 0, 2)) }}
                                                @endif
                                            </div>
                                            <div class="min-w-0">
                                                <h4 class="font-black text-sm text-white group-hover:text-purple-300 transition-colors truncate">{{ $t->name }}</h4>
                                                <p class="text-xs text-slate-400 font-semibold mt-0.5 flex items-center gap-2 truncate">
                                                    <span>{{ $t->nip ? 'NIP: ' . $t->nip : ($t->position ?? 'Tenaga Pendidik') }}</span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2 text-xs font-bold text-purple-400 group-hover:translate-x-1 transition-transform shrink-0 ml-3">
                                            <span class="hidden sm:inline">Lini Masa</span>
                                            <i class="ph-bold ph-arrow-right text-sm"></i>
                                        </div>
                                    </a>
                                @empty
                                    <div class="py-12 text-center text-slate-400 text-sm font-bold">
                                        Belum ada guru terdaftar dalam sistem.
                                    </div>
                                @endforelse
                            </div>

                            <!-- Modal Footer -->
                            <div class="p-4 sm:p-5 border-t border-white/10 bg-white/[0.02] flex items-center justify-between text-xs text-slate-400">
                                <span>Total {{ count($teachers) }} Guru Terdaftar</span>
                                <span>Periode: {{ \Carbon\Carbon::parse($month . '-01')->translatedFormat('F Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</x-app-layout>
