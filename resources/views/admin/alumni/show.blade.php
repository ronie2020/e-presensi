<x-app-layout>
    <div class="py-8 sm:py-10 font-sans text-slate-100 bg-[#020b18] min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HEADER NAVIGATION --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.alumni.index') }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-[#021124]/80 border border-white/15 text-slate-300 hover:text-white hover:border-[#56bbf1]/50 hover:bg-[#031d3d] transition-all shadow-sm group">
                        <i class="ph-bold ph-arrow-left text-xl group-hover:-translate-x-1 transition-transform"></i>
                    </a>
                    <div>
                        <h1 class="text-2xl font-black text-white tracking-tight">Detail Profil Alumni</h1>
                        <p class="text-slate-400 text-sm font-medium mt-1">Informasi lengkap data siswa dan tracer study.</p>
                    </div>
                </div>
                
                <div class="flex gap-2 w-full md:w-auto">
                    <a href="{{ route('admin.alumni.edit', $student->id) }}" class="w-full md:w-auto px-6 py-3 bg-[#56bbf1]/10 text-[#56bbf1] border border-[#56bbf1]/30 rounded-xl font-bold text-sm hover:bg-[#56bbf1] hover:text-white flex items-center justify-center gap-2 transition-all shadow-sm">
                        <i class="ph-bold ph-pencil-simple text-lg"></i> Edit Data
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- KOLOM KIRI: PROFIL UTAMA --}}
                <div class="lg:col-span-1">
                    <div class="bg-gradient-to-br from-[#031d3d]/90 via-[#021124]/95 to-[#031d3d]/90 backdrop-blur-xl rounded-[2.5rem] p-8 shadow-2xl border border-white/10 relative overflow-hidden text-center flex flex-col items-center">
                        {{-- Background Decor --}}
                        <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-br from-[#0d52a1] to-[#56bbf1]/30"></div>
                        
                        <div class="relative z-10 mt-6 mb-5">
                            <div class="w-32 h-32 mx-auto rounded-[2rem] border-4 border-[#021124] shadow-xl bg-[#021124] overflow-hidden flex items-center justify-center text-4xl font-black text-[#56bbf1] shrink-0">
                                @if($student->photo_path)
                                    <img src="{{ asset('storage/' . $student->photo_path) }}" class="w-full h-full object-cover">
                                @else
                                    {{ substr($student->name, 0, 1) }}
                                @endif
                            </div>
                        </div>

                        <h2 class="text-xl font-black text-white mb-1 leading-tight">{{ $student->name }}</h2>
                        <p class="text-sm text-slate-400 font-mono font-bold mb-5">{{ $student->nisn ?? $student->student_id }}</p>

                        <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-white/5 border border-white/10 rounded-full text-xs font-bold text-slate-300 uppercase tracking-widest mb-8 shadow-sm">
                            <i class="ph-fill ph-graduation-cap text-[#56bbf1]"></i>
                            Lulusan {{ $student->graduation_year ?? \Carbon\Carbon::parse($student->graduated_date)->year }}
                        </div>

                        <div class="space-y-3 w-full text-left bg-[#021124]/80 p-5 rounded-2xl border border-white/10">
                            <div class="flex items-center gap-3 text-sm">
                                <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-[#56bbf1] shadow-sm border border-white/10"><i class="ph-bold ph-gender-intersex"></i></div>
                                <span class="font-bold text-slate-200">{{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                            </div>
                            <div class="flex items-center gap-3 text-sm">
                                <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-[#56bbf1] shadow-sm border border-white/10 shrink-0"><i class="ph-bold ph-map-pin"></i></div>
                                <span class="font-bold text-slate-200 line-clamp-2 leading-snug" title="{{ $student->address }}">{{ $student->address ?? 'Alamat tidak tersedia' }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- RIWAYAT AKADEMIK TIMELINE --}}
                    <div class="bg-gradient-to-br from-[#031d3d]/90 via-[#021124]/95 to-[#031d3d]/90 backdrop-blur-xl rounded-[2.5rem] p-8 shadow-2xl border border-white/10 mt-6 relative overflow-hidden">
                        
                        <h3 class="text-lg font-black text-white mb-6 flex items-center gap-2 relative z-10">
                            <i class="ph-fill ph-clock-counter-clockwise text-[#56bbf1]"></i> Riwayat Akademik
                        </h3>

                        @if($student->classHistories && $student->classHistories->count() > 0)
                            <div class="relative border-l-2 border-white/10 ml-3 space-y-6 z-10">
                                @foreach($student->classHistories as $history)
                                    <div class="relative pl-6 group">
                                        {{-- Timeline Dot --}}
                                        <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-[#021124] border-[3px] border-[#56bbf1] shadow-sm"></div>
                                        
                                        {{-- Content --}}
                                        <div class="bg-[#021124]/80 p-4 rounded-2xl border border-white/10 group-hover:border-[#56bbf1]/40 transition-all">
                                            <div class="flex justify-between items-start mb-1.5">
                                                <h4 class="text-sm font-bold text-white group-hover:text-[#56bbf1] transition-colors leading-none">
                                                    {{ $history->schoolClass->name ?? 'Kelas Dihapus' }}
                                                </h4>
                                                <span class="text-[9px] font-black text-slate-300 bg-white/5 px-2 py-0.5 rounded-md shadow-sm border border-white/10 uppercase tracking-wider">
                                                    {{ $history->academic_year }}
                                                </span>
                                            </div>
                                            <p class="text-xs text-slate-400 font-medium">Kenaikan / Mutasi Kelas</p>
                                        </div>
                                    </div>
                                @endforeach
                                
                                {{-- Pintu Masuk / Awal Masuk --}}
                                <div class="relative pl-6 opacity-60">
                                    <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-slate-600 border-[3px] border-[#021124] shadow-sm"></div>
                                    <h4 class="text-sm font-bold text-slate-300">Siswa Masuk / Terdaftar</h4>
                                    <p class="text-xs text-slate-400 font-medium mt-0.5">Awal mula pendataan</p>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-6 relative z-10 border-2 border-dashed border-white/10 rounded-2xl bg-[#021124]/50">
                                <div class="w-12 h-12 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-400 border border-white/10">
                                    <i class="ph-duotone ph-ghost text-2xl"></i>
                                </div>
                                <p class="text-xs text-slate-400 font-bold">Belum ada catatan riwayat mutasi/kenaikan kelas.</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- KOLOM KANAN: TRACER STUDY --}}
                <div class="lg:col-span-2 space-y-6">
                    
                    {{-- Status Tracer --}}
                    @if($student->alumniProfile)
                        <div class="bg-gradient-to-br from-[#031d3d]/90 via-[#021124]/95 to-[#031d3d]/90 backdrop-blur-xl rounded-[2.5rem] p-8 shadow-2xl border border-white/10 relative overflow-hidden">
                            <h3 class="text-lg font-black text-white mb-6 flex items-center gap-2 relative z-10">
                                <i class="ph-fill ph-chart-polar text-[#56bbf1]"></i> Laporan Aktivitas Saat Ini
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 relative z-10">
                                <div class="bg-[#021124]/80 p-5 rounded-2xl border border-white/10">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Jalur Pilihan</p>
                                    <div class="text-xl font-black text-white flex items-center gap-2.5">
                                        @php 
                                            $status = $student->alumniProfile->activity_status; 
                                            if(in_array($status, ['Mencari Kerja', 'Tidak Lanjut'])) {
                                                $status = 'Belum Mengisi';
                                            }
                                        @endphp
                                        <span class="w-3.5 h-3.5 rounded-full shadow-inner
                                            {{ $status == 'SMA' ? 'bg-[#56bbf1]' : '' }}
                                            {{ $status == 'SMK' ? 'bg-orange-400' : '' }}
                                            {{ $status == 'MA' ? 'bg-emerald-400' : '' }}
                                            {{ $status == 'Pesantren' ? 'bg-teal-400' : '' }}
                                            {{ $status == 'Bekerja' ? 'bg-slate-400' : '' }}
                                            {{ in_array($status, ['Lainnya', 'Belum Mengisi']) ? 'bg-rose-400' : '' }}">
                                        </span>
                                        <span class="{{ $status == 'Belum Mengisi' ? 'text-rose-400' : '' }}">{{ $status }}</span>
                                    </div>
                                </div>

                                <div class="bg-[#021124]/80 p-5 rounded-2xl border border-white/10">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Instansi / Tempat</p>
                                    <div class="text-lg font-black text-white truncate" title="{{ $student->alumniProfile->campus_name ?? $student->alumniProfile->company_name }}">
                                        {{ $student->alumniProfile->campus_name ?? $student->alumniProfile->company_name ?? 'Data Belum Diinput' }}
                                    </div>
                                    @if($student->alumniProfile->campus_major || $student->alumniProfile->position)
                                        <div class="text-xs font-bold text-[#56bbf1] mt-1.5 uppercase tracking-wide">
                                            {{ $student->alumniProfile->campus_major ?? $student->alumniProfile->position }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-6 pt-6 border-t border-white/10 grid grid-cols-1 md:grid-cols-2 gap-6 relative z-10">
                                <div class="flex items-center gap-4 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20">
                                    <div class="w-10 h-10 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0"><i class="ph-fill ph-whatsapp-logo text-xl"></i></div>
                                    <div>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Nomor WhatsApp</p>
                                        @if($student->alumniProfile->phone_number)
                                            <a href="https://wa.me/{{ $student->alumniProfile->phone_number }}" target="_blank" class="text-sm font-black text-emerald-400 hover:text-emerald-300 transition-colors">
                                                {{ $student->alumniProfile->phone_number }}
                                            </a>
                                        @else
                                            <p class="text-sm font-bold text-slate-500">-</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 p-4 rounded-xl bg-white/5 border border-white/10">
                                    <div class="w-10 h-10 rounded-full bg-white/5 border border-white/10 text-slate-300 flex items-center justify-center shrink-0 shadow-sm"><i class="ph-bold ph-envelope-simple text-lg"></i></div>
                                    <div class="min-w-0">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Email</p>
                                        <p class="text-sm font-bold text-white truncate" title="{{ $student->alumniProfile->email }}">{{ $student->alumniProfile->email ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Testimoni --}}
                        @if($student->alumniProfile->testimony)
                        <div class="bg-gradient-to-br from-[#031d3d]/90 via-[#021124]/95 to-[#031d3d]/90 backdrop-blur-xl rounded-[2.5rem] p-8 shadow-2xl border border-white/10 relative overflow-hidden">
                            <h3 class="text-lg font-black text-white mb-6 flex items-center gap-2">
                                <i class="ph-fill ph-quotes text-[#56bbf1]"></i> Kesan & Pesan
                            </h3>
                            <div class="bg-[#021124]/90 p-6 rounded-2xl border border-white/10 italic text-slate-300 font-medium leading-relaxed relative">
                                <i class="ph-fill ph-quotes text-4xl text-[#56bbf1]/20 absolute -top-3 -left-2"></i>
                                <span class="relative z-10">"{{ $student->alumniProfile->testimony }}"</span>
                            </div>
                            <div class="mt-5 flex items-center gap-3 px-2">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Rating Sekolah:</span>
                                <div class="flex text-amber-400 text-base drop-shadow-sm">
                                    @for($i=0; $i < ($student->alumniProfile->rating ?? 5); $i++) <i class="ph-fill ph-star"></i> @endfor
                                </div>
                            </div>
                        </div>
                        @endif

                    @else
                        <div class="bg-gradient-to-br from-[#031d3d]/90 via-[#021124]/95 to-[#031d3d]/90 backdrop-blur-xl rounded-[2.5rem] p-12 border border-white/10 shadow-2xl text-center flex flex-col items-center justify-center min-h-[400px]">
                            <div class="w-24 h-24 bg-white/5 border border-white/10 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-400 shadow-sm">
                                <i class="ph-duotone ph-clipboard-text text-5xl"></i>
                            </div>
                            <h3 class="text-xl font-black text-white mb-2">Belum Mengisi Tracer Study</h3>
                            <p class="text-slate-400 text-sm max-w-sm mx-auto mb-8 leading-relaxed">Alumni ini belum memperbarui data kelulusan atau rekam jejak sekolah lanjutan.</p>
                            
                            <a href="{{ route('admin.alumni.edit', $student->id) }}" class="inline-flex items-center gap-2 px-8 py-3.5 bg-gradient-to-r from-[#0d52a1] via-sky-600 to-[#56bbf1] text-white font-bold rounded-2xl hover:brightness-110 transition-all shadow-lg shadow-sky-950/50 transform active:scale-95 text-sm">
                                <i class="ph-bold ph-pencil-simple text-lg"></i> Input Data Manual
                            </a>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</x-app-layout>