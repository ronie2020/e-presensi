<x-app-layout>
    <div class="py-4 sm:py-6 font-sans text-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- HERO SECTION --}}
            <x-hero-section
                badge="LMS Monitoring"
                badgeIcon="ph-eye"
                title="Riwayat Pembaca"
                titleHighlight="{{ $material->title }}"
                description="Pantau aktivitas siswa yang mengakses dan mempelajari materi digital {{ $material->subject->name }} secara real-time."
                :chips="[
                    ['icon' => 'ph-book-open-text', 'label' => $material->subject->name],
                    ['icon' => 'ph-clock', 'label' => 'Time-on-Task Tracking'],
                    ['icon' => 'ph-users', 'label' => $logs->count() . ' Siswa Telah Membaca']
                ]"
                heroIcon="ph-eye"
                :showcaseNumber="$logs->count()"
                showcaseLabel="Total Pembaca"
                showcaseSubtitle="Siswa Terdata"
                statusOrb="Tracking Aktif"
                statusColor="emerald"
            >
                <x-slot:cta>
                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ route('lms.materials.index') }}" class="px-5 py-2.5 bg-gradient-to-r from-sky-400 to-[#0d52a1] hover:from-sky-300 hover:to-sky-700 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-lg shadow-sky-500/25 hover:shadow-sky-500/40 hover:scale-[1.02] transition-all flex items-center gap-2 border border-white/20 active:scale-95">
                            <i class="ph-bold ph-arrow-left text-base"></i> Kembali ke Materi
                        </a>
                        <a href="{{ route('dashboard') }}" class="px-4 py-2.5 bg-white/10 hover:bg-white/20 text-slate-200 hover:text-white font-bold text-xs rounded-xl border border-white/15 backdrop-blur-md hover:scale-[1.02] transition-all flex items-center gap-1.5">
                            <i class="ph-bold ph-squares-four text-sm text-sky-400"></i> Dashboard
                        </a>
                    </div>
                </x-slot:cta>
            </x-hero-section>

            {{-- TABLE CONTAINER CARD --}}
            <div class="rounded-[2.5rem] bg-[#031d3d]/85 backdrop-blur-2xl border border-white/15 p-6 sm:p-8 shadow-2xl shadow-black/50 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-sky-400 via-blue-500 to-indigo-500"></div>

                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                    <div>
                        <h3 class="text-lg sm:text-xl font-black text-white flex items-center gap-2.5">
                            <i class="ph-duotone ph-chart-line-up text-sky-400 text-2xl"></i>
                            <span>Daftar Waktu Belajar Siswa</span>
                        </h3>
                        <p class="text-xs text-slate-400 mt-1">Rekam jejak durasi keterlibatan (time-on-task) siswa pada materi ini.</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/15 border border-emerald-400/30 text-emerald-300 text-xs font-bold">
                            <i class="ph-bold ph-check-circle"></i> {{ $logs->count() }} Terdata
                        </span>
                    </div>
                </div>

                @if($logs->isEmpty())
                    <div class="text-center py-16 rounded-2xl border-2 border-dashed border-white/10 bg-[#021124]/40">
                        <div class="w-16 h-16 rounded-2xl bg-white/5 flex items-center justify-center text-3xl text-slate-400 mx-auto mb-3 border border-white/10">
                            <i class="ph-duotone ph-clock"></i>
                        </div>
                        <h4 class="text-base font-bold text-slate-200">Belum Ada Pembaca</h4>
                        <p class="text-slate-400 text-xs mt-1">Belum ada siswa yang membuka dan mempelajari materi ini.</p>
                    </div>
                @else
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left text-sm min-w-[700px] border-separate border-spacing-y-2">
                            <thead>
                                <tr>
                                    <th class="p-3.5 bg-[#021124]/90 text-sky-300 font-extrabold text-xs uppercase tracking-wider rounded-xl border border-white/10">Siswa</th>
                                    <th class="p-3.5 bg-[#021124]/90 text-sky-300 font-extrabold text-xs uppercase tracking-wider rounded-xl border border-white/10">Kelas</th>
                                    <th class="p-3.5 bg-[#021124]/90 text-sky-300 font-extrabold text-xs uppercase tracking-wider rounded-xl border border-white/10">Durasi Belajar (Time-on-Task)</th>
                                    <th class="p-3.5 bg-[#021124]/90 text-sky-300 font-extrabold text-xs uppercase tracking-wider rounded-xl border border-white/10">Terakhir Akses</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($logs as $log)
                                    @php
                                        $minutes = floor($log->time_spent_seconds / 60);
                                        $seconds = $log->time_spent_seconds % 60;
                                        
                                        $color = 'text-emerald-300 bg-emerald-500/15 border-emerald-400/30';
                                        $icon = 'ph-check-circle';
                                        if($log->time_spent_seconds < 60) {
                                            $color = 'text-rose-300 bg-rose-500/15 border-rose-400/30';
                                            $icon = 'ph-warning-circle';
                                        } elseif($log->time_spent_seconds < 180) {
                                            $color = 'text-amber-300 bg-amber-500/15 border-amber-400/30';
                                            $icon = 'ph-clock';
                                        }
                                    @endphp
                                    <tr class="hover:bg-white/[0.02] transition-colors">
                                        <td class="p-3 bg-[#021124]/50 border border-white/10 rounded-xl">
                                            <div class="font-bold text-white text-xs">{{ $log->student->name ?? 'Siswa (Telah Lulus/Dihapus)' }}</div>
                                            <div class="text-[10px] text-slate-400 font-mono mt-0.5">NIS: {{ $log->student->student_id ?? '-' }}</div>
                                        </td>
                                        <td class="p-3 bg-[#021124]/50 border border-white/10 rounded-xl text-xs font-bold text-slate-300">
                                            @if(isset($log->student) && $log->student->schoolClass)
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-sky-500/15 text-sky-300 border border-sky-400/30 text-xs font-bold">
                                                    {{ $log->student->schoolClass->name }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold bg-white/5 text-slate-400 border border-white/10 uppercase">
                                                    <i class="ph-bold ph-graduation-cap"></i> Alumni / Lulus
                                                </span>
                                            @endif
                                        </td>
                                        <td class="p-3 bg-[#021124]/50 border border-white/10 rounded-xl">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold border {{ $color }} shadow-sm">
                                                <i class="ph-fill {{ $icon }} text-sm"></i>
                                                {{ $minutes }} Menit {{ $seconds }} Detik
                                            </span>
                                        </td>
                                        <td class="p-3 bg-[#021124]/50 border border-white/10 rounded-xl text-xs font-mono text-slate-300">
                                            {{ $log->updated_at->translatedFormat('d M Y, H:i') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>