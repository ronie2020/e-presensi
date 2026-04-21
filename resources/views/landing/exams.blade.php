<!-- JADWAL UJIAN CBT -->
<div id="jadwal-ujian" class="py-24 bg-slate-50 relative overflow-hidden border-t border-slate-100">
    <!-- Ornamen Background -->
    <div class="absolute top-0 right-0 -mr-20 -mt-20 w-72 h-72 bg-cyan-500/10 rounded-full blur-[80px] pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-72 h-72 bg-blue-500/10 rounded-full blur-[80px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
            <span class="text-cyan-600 font-bold tracking-wider text-sm uppercase mb-3 inline-flex items-center gap-2 px-3 py-1 bg-cyan-100/50 rounded-full border border-cyan-200">
                <i class="ph-fill ph-monitor-play text-lg"></i> Info Akademik
            </span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 leading-tight mb-4">Jadwal Ujian Berbasis Komputer (CBT)</h2>
            <p class="text-slate-500 text-lg">Informasi jadwal Penilaian Harian, PTS, dan PAS yang sedang atau akan berlangsung. Silakan login ke Portal Siswa untuk mengikuti ujian.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @if(isset($publicExams) && $publicExams->isNotEmpty())
                @foreach($publicExams as $index => $exam)
                    @php
                        // Menentukan status berdasarkan waktu saat ini
                        $now = \Carbon\Carbon::now();
                        $startTime = \Carbon\Carbon::parse($exam->start_time);
                        $endTime = $exam->end_time ? \Carbon\Carbon::parse($exam->end_time) : null;
                        
                        $isOngoing = $now->greaterThanOrEqualTo($startTime) && ($endTime === null || $now->lessThanOrEqualTo($endTime));
                        
                        $statusLabel = $isOngoing ? 'Sedang Berlangsung' : 'Akan Datang';
                        $statusClass = $isOngoing ? 'bg-emerald-50 text-emerald-600 border-emerald-200 animate-pulse' : 'bg-amber-50 text-amber-600 border-amber-200';
                        $iconClass = $isOngoing ? 'ph-broadcast' : 'ph-clock-countdown';
                    @endphp

                    <!-- Card Ujian -->
                    <div class="bg-white rounded-3xl p-6 md:p-8 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] hover:shadow-xl hover:shadow-cyan-900/10 border border-slate-100 transition-all duration-300 hover:-translate-y-2 group flex flex-col h-full" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        
                        <!-- Header Status -->
                        <div class="flex justify-between items-start mb-6">
                            <div class="w-12 h-12 rounded-2xl bg-slate-50 text-slate-600 flex items-center justify-center border border-slate-100 group-hover:bg-cyan-50 group-hover:text-cyan-600 group-hover:border-cyan-200 transition-colors">
                                <i class="ph-duotone ph-exam text-2xl"></i>
                            </div>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider border shadow-sm {{ $statusClass }}">
                                <i class="ph-fill {{ $iconClass }}"></i> {{ $statusLabel }}
                            </span>
                        </div>

                        <!-- Info Utama -->
                        <div class="flex-1 mb-6">
                            <h3 class="text-xl font-bold text-slate-800 mb-2 line-clamp-2 group-hover:text-cyan-700 transition-colors">
                                {{ $exam->title }}
                            </h3>
                            
                            <!-- BUNGKUSAN BADGE: Mapel & Kelas -->
                            <div class="flex flex-wrap items-center gap-2 mb-4">
                                <div class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100">
                                    <i class="ph-fill ph-book-open text-cyan-500"></i> {{ $exam->subject_name ?? 'Mata Pelajaran' }}
                                </div>
                                <!-- BADGE KELAS (BARU) -->
                                <div class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100">
                                    <i class="ph-fill ph-users-three text-indigo-500"></i> {{ $exam->class_level ?? 'Semua Kelas' }}
                                </div>
                            </div>

                            <div class="space-y-3 mt-2">
                                <div class="flex items-center gap-3 text-sm text-slate-600">
                                    <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center shrink-0">
                                        <i class="ph-bold ph-calendar-blank"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] uppercase font-bold text-slate-400 leading-none mb-0.5">Tanggal</p>
                                        <p class="font-semibold">{{ $startTime->translatedFormat('l, d M Y') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 text-sm text-slate-600">
                                    <div class="w-8 h-8 rounded-full bg-purple-50 text-purple-500 flex items-center justify-center shrink-0">
                                        <i class="ph-bold ph-clock"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] uppercase font-bold text-slate-400 leading-none mb-0.5">Waktu</p>
                                        <p class="font-semibold">{{ $startTime->format('H:i') }} WIB - Selesai</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CTA Button -->
                        <div class="mt-auto pt-5 border-t border-slate-100">
                            <a href="{{ route('student.login') }}" class="w-full inline-flex justify-center items-center gap-2 px-4 py-3 rounded-xl bg-slate-900 text-white text-sm font-bold hover:bg-cyan-600 transition-colors shadow-md hover:shadow-cyan-500/25 group/btn">
                                <i class="ph-bold ph-lock-key"></i> Login untuk Mengerjakan
                                <i class="ph-bold ph-arrow-right ml-1 opacity-0 -translate-x-2 group-hover/btn:opacity-100 group-hover/btn:translate-x-0 transition-all"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            @else
                <!-- State Jika Tidak Ada Ujian -->
                <div class="col-span-full py-16 px-6 bg-white rounded-3xl border border-dashed border-slate-300 text-center" data-aos="fade-up">
                    <div class="w-20 h-20 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                        <i class="ph-duotone ph-coffee text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Belum Ada Jadwal Ujian</h3>
                    <p class="text-slate-500 max-w-md mx-auto">Saat ini tidak ada jadwal Penilaian Harian, PTS, atau PAS yang dijadwalkan dalam waktu dekat.</p>
                </div>
            @endif
        </div>

    </div>
</div>