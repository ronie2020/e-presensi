<x-app-layout>
    <div class="py-8 sm:py-10 font-sans text-elevate-dark bg-elevate-surface min-h-screen relative overflow-hidden">
        
        {{-- Efek Latar Belakang Halus --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- HEADER: IDENTITAS GURU --}}
            <div class="mb-10 animate-enter">
                <a href="{{ route('reports.principal') }}?month={{ $month }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-elevate-primary transition-colors mb-6">
                    <i class="ph-bold ph-arrow-left"></i> Kembali ke Rekap Global
                </a>
                
                <div class="flex flex-col md:flex-row items-center gap-6 bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/40 relative overflow-hidden">
                    <div class="absolute right-0 top-0 w-48 h-48 bg-elevate-primary rounded-bl-full -mr-10 -mt-10 opacity-5 pointer-events-none"></div>
                    
                    <div class="w-28 h-28 rounded-full border-4 border-white shadow-xl overflow-hidden bg-slate-100 shrink-0 relative">
                        @if($teacher->photo)
                            <img src="{{ asset('storage/' . $teacher->photo) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-elevate-primary text-white flex items-center justify-center text-3xl font-black">
                                {{ substr($teacher->name, 0, 2) }}
                            </div>
                        @endif
                    </div>
                    
                    <div class="flex-grow text-center md:text-left">
                        <h2 class="text-3xl font-black text-elevate-dark tracking-tight">{{ $teacher->name }}</h2>
                        <p class="text-slate-500 font-bold mt-1 uppercase tracking-widest text-sm flex items-center justify-center md:justify-start gap-2">
                            <i class="ph-bold ph-chalkboard-teacher text-elevate-primary"></i> {{ $teacher->nip ?? 'NIP/NUPTK: -' }}
                        </p>
                    </div>
                    
                    <div class="flex gap-4 w-full md:w-auto">
                        <div class="flex-1 md:w-32 bg-slate-50 rounded-2xl p-4 border border-slate-100 text-center">
                            <h4 class="text-2xl font-black text-[#0d52a1]">{{ $totalSessions }}</h4>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Sesi KBM</p>
                        </div>
                        <div class="flex-1 md:w-32 bg-emerald-50 rounded-2xl p-4 border border-emerald-100 text-center">
                            <h4 class="text-2xl font-black text-emerald-600">{{ $completionRate }}%</h4>
                            <p class="text-[10px] font-black text-emerald-500/70 uppercase tracking-widest mt-1">Materi Selesai</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TIMELINE KINERJA --}}
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/40 p-6 md:p-10 relative overflow-hidden mb-10">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 border-b border-slate-100 pb-6">
                    <h3 class="font-black text-elevate-dark text-xl flex items-center gap-2">
                        <i class="ph-fill ph-git-commit text-elevate-primary"></i> Lini Masa Kinerja
                    </h3>
                    <div class="flex items-center gap-3">
                        <div class="px-4 py-2 bg-slate-50 rounded-xl border border-slate-200 text-xs font-bold text-slate-600">
                            Periode: {{ \Carbon\Carbon::parse($month . '-01')->translatedFormat('F Y') }}
                        </div>
                        <a href="{{ route('reports.principal.teacher.print', ['id' => $teacher->id, 'month' => $month]) }}" target="_blank" class="px-4 py-2 bg-gradient-to-r from-[#0d52a1] to-sky-600 hover:from-sky-600 hover:to-[#0d52a1] text-white text-xs font-bold rounded-xl shadow-md shadow-sky-900/10 active:scale-95 transition-all inline-flex items-center gap-2 cursor-pointer">
                            <i class="ph-bold ph-printer text-base"></i> Cetak PDF
                        </a>
                    </div>
                </div>

                @if($groupedTeachings->isEmpty())
                    <div class="text-center py-20">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                            <i class="ph-duotone ph-calendar-blank text-4xl"></i>
                        </div>
                        <h4 class="text-lg font-black text-slate-400">Belum ada KBM</h4>
                        <p class="text-sm font-medium text-slate-400 mt-1">Guru ini belum mencatat jurnal mengajar di bulan ini.</p>
                    </div>
                @else
                    <div class="relative max-w-4xl mx-auto">
                        <!-- Garis vertikal timeline -->
                        <div class="absolute left-4 md:left-1/2 top-0 bottom-0 w-1 bg-slate-100 -translate-x-1/2 rounded-full"></div>

                        @foreach($groupedTeachings as $date => $sessions)
                            <!-- Penanda Tanggal -->
                            <div class="flex justify-start md:justify-center w-full mb-8 relative z-10">
                                <div class="bg-elevate-dark text-white px-5 py-2 rounded-xl text-xs font-black shadow-lg shadow-elevate-dark/20 border border-white/10 flex items-center gap-2 ml-10 md:ml-0">
                                    <i class="ph-bold ph-calendar-check text-elevate-accent"></i> {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}
                                </div>
                                <div class="absolute left-4 md:left-1/2 top-1/2 w-3 h-3 bg-elevate-primary rounded-full -translate-x-1/2 -translate-y-1/2 ring-4 ring-white"></div>
                            </div>

                            @foreach($sessions as $index => $session)
                                @php 
                                    $isEven = $index % 2 == 0;
                                    $hadirTotal = ($session->hadir_count ?? 0) + ($session->late_count ?? 0); 
                                    $alphaTotal = $session->alpha_count ?? 0;
                                @endphp
                                <div class="flex flex-col md:flex-row w-full mb-10 relative z-10 {{ $isEven ? 'md:flex-row-reverse' : '' }}">
                                    <!-- Ruang kosong di sisi berlawanan untuk desktop -->
                                    <div class="hidden md:block w-1/2"></div>
                                    
                                    <!-- Titik Timeline -->
                                    <div class="absolute left-4 md:left-1/2 top-6 w-5 h-5 bg-white border-4 {{ $session->material_status == 'completed' ? 'border-emerald-500' : 'border-amber-500' }} rounded-full -translate-x-1/2 ring-4 ring-slate-50"></div>
                                    
                                    <!-- Konten Kartu -->
                                    <div class="w-full md:w-1/2 pl-12 pr-0 md:px-10">
                                        <div class="bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/50 border border-slate-100 hover:border-elevate-primary/30 transition-all hover:-translate-y-1 relative overflow-hidden group">
                                            
                                            <div class="flex justify-between items-start mb-4">
                                                <div class="bg-elevate-soft text-elevate-primary px-3 py-1.5 rounded-lg text-[10px] font-black tracking-widest uppercase flex items-center gap-1.5">
                                                    <i class="ph-bold ph-clock"></i> {{ $session->started_at ? \Carbon\Carbon::parse($session->started_at)->format('H:i') : '-' }} s/d {{ $session->ended_at ? \Carbon\Carbon::parse($session->ended_at)->format('H:i') : '-' }}
                                                </div>
                                                <span class="inline-block px-3 py-1.5 rounded-lg border border-slate-200 font-black text-[10px] bg-slate-50 text-slate-600 shadow-sm uppercase">
                                                    {{ $session->schoolClass->name ?? ($session->timetable->studentClass->name ?? ($session->schedule->studentClass->name ?? '-')) }}
                                                </span>
                                            </div>

                                            <h4 class="font-black text-elevate-dark text-lg leading-tight mb-2 group-hover:text-elevate-primary transition-colors">
                                                {{ $session->subject->name ?? ($session->timetable->subject->name ?? ($session->schedule->subject->name ?? '-')) }}
                                            </h4>
                                            
                                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 mb-4">
                                                <div class="flex items-center gap-2 mb-2">
                                                    <span class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Materi:</span>
                                                    @if($session->material_status == 'completed')
                                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-emerald-600 bg-emerald-100 text-[9px] font-black uppercase"><i class="ph-bold ph-check"></i> Tuntas</span>
                                                    @elseif($session->material_status == 'incomplete')
                                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-amber-600 bg-amber-100 text-[9px] font-black uppercase"><i class="ph-bold ph-arrow-u-down-right"></i> Belum Selesai</span>
                                                    @endif
                                                </div>
                                                <p class="text-sm font-bold text-slate-700">{{ $session->topic ?? 'Tanpa Topik' }}</p>
                                                <p class="text-xs text-slate-500 font-medium mt-1 line-clamp-3">{{ $session->activities ?? '-' }}</p>
                                            </div>

                                            <div class="flex flex-wrap items-center gap-4 justify-between border-t border-slate-100 pt-4 mt-auto">
                                                <div class="flex items-center gap-3">
                                                    <div class="text-[10px] font-black uppercase tracking-wide bg-[#DFF6DD] text-[#107C10] px-2.5 py-1 rounded-lg border border-[#B7DFB9]">
                                                        Hadir: {{ $hadirTotal }}
                                                    </div>
                                                    @if($alphaTotal > 0)
                                                    <div class="text-[10px] font-black uppercase tracking-wide bg-[#FDE7E9] text-[#D13438] px-2.5 py-1 rounded-lg border border-[#F4C3C9]">
                                                        Alpha: {{ $alphaTotal }}
                                                    </div>
                                                    @endif
                                                </div>
                                                
                                                @if($session->photo_proof)
                                                    @php
                                                        $photos = json_decode($session->photo_proof, true) ?? [$session->photo_proof];
                                                    @endphp
                                                    <a href="{{ asset('storage/' . $photos[0]) }}" target="_blank" class="w-10 h-10 rounded-xl bg-elevate-primary/10 text-elevate-primary flex items-center justify-center hover:bg-elevate-primary hover:text-white transition-colors shadow-sm" title="Lihat Foto Dokumentasi">
                                                        <i class="ph-bold ph-camera text-lg"></i>
                                                    </a>
                                                @endif
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
