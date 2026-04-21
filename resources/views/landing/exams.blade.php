<!-- JADWAL UJIAN CBT -->
<div id="jadwal-ujian" class="py-24 bg-slate-50 relative overflow-hidden border-t border-slate-100">
    <!-- Ornamen Background -->
    <div class="absolute top-0 right-0 -mr-20 -mt-20 w-72 h-72 bg-cyan-500/10 rounded-full blur-[80px] pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-72 h-72 bg-blue-500/10 rounded-full blur-[80px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <div class="text-center max-w-3xl mx-auto mb-12" data-aos="fade-up">
            <span class="text-cyan-600 font-bold tracking-wider text-xs uppercase mb-3 inline-flex items-center gap-1.5 px-3 py-1 bg-cyan-100/50 rounded-full border border-cyan-200">
                <i class="ph-fill ph-monitor-play text-sm"></i> Info Akademik
            </span>
            <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900 leading-tight mb-3">Jadwal Ujian Berbasis Komputer (CBT)</h2>
            <p class="text-slate-500 text-sm md:text-base max-w-2xl mx-auto">Informasi jadwal Penilaian Harian, PTS, dan PAS yang sedang atau akan berlangsung. Silakan login ke Portal Siswa untuk mengikuti ujian.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @if(isset($publicExams) && $publicExams->isNotEmpty())
                @foreach($publicExams as $index => $exam)
                    @php
                        // Menentukan status berdasarkan waktu saat ini
                        $now = \Carbon\Carbon::now();
                        $startTime = \Carbon\Carbon::parse($exam->start_time);
                        $endTime = $exam->end_time ? \Carbon\Carbon::parse($exam->end_time) : null;
                        
                        $isOngoing = $now->greaterThanOrEqualTo($startTime) && ($endTime === null || $now->lessThanOrEqualTo($endTime));
                        
                        $statusLabel = $isOngoing ? 'Sedang Berlangsung' : 'Akan Datang';
                        $statusClass = $isOngoing ? 'bg-emerald-50/50 text-emerald-500 border-emerald-200 animate-pulse' : 'bg-amber-50/50 text-amber-500 border-amber-200';
                        $iconClass = $isOngoing ? 'ph-broadcast' : 'ph-clock-countdown';
                    @endphp

                    <!-- Card Ujian -->
                    <div class="bg-white rounded-[1.25rem] p-5 shadow-sm hover:shadow-xl border border-slate-100 transition-all duration-300 hover:-translate-y-1 group flex flex-col h-full" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        
                        <!-- Header Status & Icon Kanan -->
                        <div class="flex justify-between items-center mb-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[9px] font-black uppercase tracking-wider border {{ $statusClass }}">
                                <i class="ph-fill {{ $iconClass }} text-sm"></i> {{ $statusLabel }}
                            </span>
                            <div class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 flex items-center justify-center border border-slate-100 group-hover:bg-blue-50 group-hover:text-blue-600 transition-colors">
                                <i class="ph-duotone ph-exam text-lg"></i>
                            </div>
                        </div>

                        <!-- Info Utama -->
                        <div class="flex-1 mb-5">
                            <!-- BUNGKUSAN BADGE: Mapel & Kelas -->
                            <div class="flex flex-wrap items-center gap-1.5 mb-2">
                                <div class="inline-flex items-center text-[9px] font-black uppercase tracking-wider text-indigo-600 bg-indigo-50/80 px-2 py-1 rounded border border-indigo-100/50">
                                    {{ $exam->subject_name ?? 'Mata Pelajaran' }}
                                </div>
                                <!-- BADGE KELAS -->
                                <div class="inline-flex items-center gap-1 text-[9px] font-black uppercase tracking-wider text-indigo-600 bg-indigo-50/80 px-2 py-1 rounded border border-indigo-100/50">
                                    <i class="ph-fill ph-users-three text-xs"></i> {{ $exam->class_level ?? 'Semua Kelas' }}
                                </div>
                            </div>

                            <h3 class="text-lg md:text-xl font-bold text-blue-600 mb-3 line-clamp-2 transition-colors">
                                {{ $exam->title }}
                            </h3>
                            
                            <!-- Box Tanggal & Waktu (Lebih Compact) -->
                            <div class="bg-slate-50/80 rounded-xl p-3 border border-slate-100">
                                <!-- Baris Mulai -->
                                <div class="flex items-center gap-2.5 text-xs mb-2">
                                    <i class="ph-fill ph-play-circle text-emerald-500 text-lg shrink-0"></i>
                                    <div class="flex items-center gap-1.5">
                                        <span class="font-bold text-slate-400 w-10">Mulai</span>
                                        <span class="font-bold text-slate-700">: {{ $startTime->translatedFormat('d M Y, H:i') }}</span>
                                    </div>
                                </div>
                                <!-- Baris Akhir -->
                                <div class="flex items-center gap-2.5 text-xs">
                                    <i class="ph-fill ph-stop-circle text-rose-500 text-lg shrink-0"></i>
                                    <div class="flex items-center gap-1.5">
                                        <span class="font-bold text-slate-400 w-10">Akhir</span>
                                        <span class="font-bold text-slate-700">: {{ $endTime ? $endTime->translatedFormat('d M Y, H:i') : 'Tidak dibatasi' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CTA Button -->
                        <div class="mt-auto">
                            <a href="{{ route('student.login') }}" class="w-full inline-flex justify-center items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-cyan-600 transition-colors shadow-md hover:shadow-cyan-500/25 group/btn">
                                <i class="ph-bold ph-lock-key text-sm"></i> Login untuk Mengerjakan
                            </a>
                        </div>
                    </div>
                @endforeach
            @else
                <!-- State Jika Tidak Ada Ujian -->
                <div class="col-span-full py-12 px-6 bg-white rounded-[1.25rem] border border-dashed border-slate-300 text-center" data-aos="fade-up">
                    <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-3 border border-slate-100">
                        <i class="ph-duotone ph-coffee text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-1">Belum Ada Jadwal Ujian</h3>
                    <p class="text-slate-500 text-sm max-w-md mx-auto">Saat ini tidak ada jadwal Penilaian Harian, PTS, atau PAS yang dijadwalkan dalam waktu dekat.</p>
                </div>
            @endif
        </div>

    </div>
</div>