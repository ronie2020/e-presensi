<!-- JADWAL UJIAN CBT -->
<div id="jadwal-ujian" class="py-24 relative overflow-hidden transition-colors duration-300">
    
    <!-- Ambient Ornaments -->
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-elevate-accent/10 rounded-full blur-[120px] pointer-events-none -translate-y-1/3 translate-x-1/3"></div>
    <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-elevate-primary/20 rounded-full blur-[120px] pointer-events-none translate-y-1/3 -translate-x-1/3"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-elevate-accent/20 text-elevate-accent text-[10px] font-black uppercase tracking-widest mb-4 border border-elevate-accent/30 shadow-sm transition-colors duration-300">
                <i class="ph-fill ph-monitor-play text-sm"></i> Info Akademik
            </span>
            <h2 class="text-3xl md:text-5xl font-black text-white leading-tight mb-4 transition-colors duration-300">Jadwal Ujian Komputer (CBT)</h2>
            <p class="text-slate-300 text-sm md:text-base max-w-2xl mx-auto font-medium transition-colors duration-300">Informasi jadwal Penilaian Harian, PTS, dan PAS yang sedang atau akan berlangsung. Silakan login ke Portal Siswa untuk mengikuti ujian.</p>
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
                        $statusClass = $isOngoing 
                            ? 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30' 
                            : 'bg-amber-500/20 text-amber-300 border-amber-500/30';
                        $iconClass = $isOngoing ? 'ph-broadcast animate-pulse' : 'ph-clock-countdown';
                    @endphp

                    <!-- Card Ujian -->
                    <div class="bg-white/5 backdrop-blur-xl rounded-[2.5rem] p-6 shadow-[0_8px_32px_rgba(0,0,0,0.3)] hover:shadow-[0_8px_32px_rgba(86,187,241,0.2)] border border-white/10 hover:border-elevate-accent/40 hover:bg-white/10 transition-all duration-300 hover:-translate-y-2 group flex flex-col h-full relative overflow-hidden" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        
                        <!-- Latar belakang card dekoratif -->
                        <div class="absolute -right-10 -top-10 w-32 h-32 bg-elevate-accent/10 rounded-full blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>

                        <!-- Header Status & Icon Kanan -->
                        <div class="flex justify-between items-center mb-6 relative z-10">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest border shadow-sm transition-colors {{ $statusClass }}">
                                <i class="ph-bold {{ $iconClass }} text-sm"></i> {{ $statusLabel }}
                            </span>
                            <div class="w-12 h-12 rounded-[1rem] bg-white/10 text-elevate-accent flex items-center justify-center border border-white/15 group-hover:bg-gradient-to-r group-hover:from-elevate-accent group-hover:to-elevate-primary group-hover:text-white transition-colors shadow-sm">
                                <i class="ph-duotone ph-desktop text-2xl"></i>
                            </div>
                        </div>

                        <!-- Info Utama -->
                        <div class="flex-1 flex flex-col mb-2 relative z-10">
                            <!-- BUNGKUSAN BADGE: Mapel & Kelas -->
                            <div class="flex flex-wrap items-center gap-2 mb-4">
                                <div class="inline-flex items-center text-[9px] font-black uppercase tracking-widest text-elevate-accent bg-elevate-accent/10 px-2.5 py-1 rounded-lg border border-elevate-accent/20 transition-colors">
                                    {{ $exam->subject_name ?? 'Mata Pelajaran' }}
                                </div>
                                <!-- BADGE KELAS -->
                                <div class="inline-flex items-center gap-1 text-[9px] font-black uppercase tracking-widest text-elevate-accent bg-elevate-accent/10 px-2.5 py-1 rounded-lg border border-elevate-accent/20 transition-colors">
                                    <i class="ph-bold ph-users-three text-xs"></i> {{ $exam->class_level ?? 'Semua Kelas' }}
                                </div>
                            </div>

                            <h3 class="text-xl font-black text-white mb-6 line-clamp-2 transition-colors group-hover:text-elevate-accent leading-snug">
                                {{ $exam->title }}
                            </h3>
                            
                            <!-- Box Tanggal & Waktu -->
                            <div class="bg-white/5 rounded-[1.5rem] p-4 border border-white/10 mb-6 flex-1 transition-colors">
                                <!-- Baris Mulai -->
                                <div class="flex items-center gap-3 text-xs mb-4">
                                    <div class="w-8 h-8 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-500/30">
                                        <i class="ph-bold ph-play"></i>
                                    </div>
                                    <div class="flex flex-col gap-0.5">
                                        <span class="font-bold text-slate-400 text-[9px] uppercase tracking-widest">Mulai Ujian</span>
                                        <span class="font-black text-white text-xs">{{ $startTime->translatedFormat('d M Y, H:i') }}</span>
                                    </div>
                                </div>
                                <!-- Baris Akhir -->
                                <div class="flex items-center gap-3 text-xs">
                                    <div class="w-8 h-8 rounded-full bg-rose-500/20 text-rose-400 flex items-center justify-center shrink-0 border border-rose-500/30">
                                        <i class="ph-bold ph-stop"></i>
                                    </div>
                                    <div class="flex flex-col gap-0.5">
                                        <span class="font-bold text-slate-400 text-[9px] uppercase tracking-widest">Berakhir Ujian</span>
                                        <span class="font-black text-white text-xs">{{ $endTime ? $endTime->translatedFormat('d M Y, H:i') : 'Tidak dibatasi' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CTA Button -->
                        <div class="mt-auto relative z-10">
                            <a href="{{ route('student.login') }}" class="w-full inline-flex justify-center items-center gap-2 px-4 py-3.5 rounded-xl bg-gradient-to-r from-elevate-accent to-elevate-primary text-white text-xs font-black hover:shadow-[0_0_20px_rgba(86,187,241,0.4)] hover:scale-[1.02] transition-all border border-elevate-accent/30 group/btn">
                                <i class="ph-bold ph-lock-key text-sm"></i> Login untuk Mengerjakan
                            </a>
                        </div>
                    </div>
                @endforeach
            @else
                <!-- State Jika Tidak Ada Ujian -->
                <div class="col-span-1 md:col-span-3 py-16 px-6 bg-white/5 backdrop-blur-xl rounded-[3rem] border-2 border-dashed border-white/10 text-center transition-colors" data-aos="fade-up">
                    <div class="w-24 h-24 bg-elevate-accent/10 text-elevate-accent rounded-[2rem] flex items-center justify-center mx-auto mb-6 border border-elevate-accent/20 transition-colors shadow-sm">
                        <i class="ph-duotone ph-coffee text-5xl"></i>
                    </div>
                    <h3 class="text-xl font-black text-white mb-2 transition-colors">Belum Ada Jadwal Ujian</h3>
                    <p class="text-slate-300 text-sm max-w-md mx-auto font-medium transition-colors">Saat ini tidak ada jadwal Penilaian Harian, PTS, atau PAS yang dijadwalkan dalam waktu dekat.</p>
                </div>
            @endif
        </div>

    </div>
</div>