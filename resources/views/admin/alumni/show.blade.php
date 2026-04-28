<x-app-layout>
    <div class="py-8 sm:py-10 font-sans text-elevate-text bg-slate-50 min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HEADER NAVIGATION --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.alumni.index') }}" class="p-3 bg-white rounded-xl border border-slate-200 hover:bg-slate-50 hover:text-elevate-primary transition-colors shadow-sm text-slate-500 group">
                        <i class="ph-bold ph-arrow-left text-xl group-hover:-translate-x-1 transition-transform"></i>
                    </a>
                    <div>
                        <h1 class="text-2xl font-black text-elevate-dark tracking-tight">Detail Profil Alumni</h1>
                        <p class="text-slate-500 text-sm font-medium mt-1">Informasi lengkap data siswa dan tracer study.</p>
                    </div>
                </div>
                
                <div class="flex gap-2 w-full md:w-auto">
                    <a href="{{ route('admin.alumni.edit', $student->id) }}" class="w-full md:w-auto px-6 py-3 bg-elevate-accent/10 text-elevate-primary border border-elevate-accent/20 rounded-xl font-bold text-sm hover:bg-elevate-primary hover:text-white flex items-center justify-center gap-2 transition-all shadow-sm">
                        <i class="ph-bold ph-pencil-simple text-lg"></i> Edit Data
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- KOLOM KIRI: PROFIL UTAMA --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 relative overflow-hidden text-center flex flex-col items-center">
                        {{-- Background Decor Microsoft Elevate --}}
                        <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-br from-elevate-dark to-elevate-primary"></div>
                        <div class="absolute top-0 right-0 w-32 h-32 bg-elevate-accent/20 rounded-full blur-2xl pointer-events-none -translate-y-1/2 translate-x-1/2"></div>
                        
                        <div class="relative z-10 mt-6 mb-5">
                            <div class="w-32 h-32 mx-auto rounded-[2rem] border-4 border-white shadow-xl bg-white overflow-hidden flex items-center justify-center text-4xl font-black text-elevate-primary shrink-0">
                                @if($student->photo_path)
                                    <img src="{{ asset('storage/' . $student->photo_path) }}" class="w-full h-full object-cover">
                                @else
                                    {{ substr($student->name, 0, 1) }}
                                @endif
                            </div>
                        </div>

                        <h2 class="text-xl font-black text-elevate-dark mb-1 leading-tight">{{ $student->name }}</h2>
                        <p class="text-sm text-slate-400 font-mono font-bold mb-5">{{ $student->nisn ?? $student->student_id }}</p>

                        <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-slate-50 border border-slate-200 rounded-full text-xs font-bold text-slate-600 uppercase tracking-widest mb-8 shadow-sm">
                            <i class="ph-fill ph-graduation-cap text-elevate-primary"></i>
                            Lulusan {{ $student->graduation_year ?? \Carbon\Carbon::parse($student->graduated_date)->year }}
                        </div>

                        <div class="space-y-3 w-full text-left bg-slate-50/50 p-5 rounded-2xl border border-slate-100">
                            <div class="flex items-center gap-3 text-sm">
                                <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center text-elevate-primary shadow-sm border border-slate-100"><i class="ph-bold ph-gender-intersex"></i></div>
                                <span class="font-bold text-slate-700">{{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                            </div>
                            <div class="flex items-center gap-3 text-sm">
                                <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center text-elevate-primary shadow-sm border border-slate-100 shrink-0"><i class="ph-bold ph-map-pin"></i></div>
                                <span class="font-bold text-slate-700 line-clamp-2 leading-snug" title="{{ $student->address }}">{{ $student->address ?? 'Alamat tidak tersedia' }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- RIWAYAT AKADEMIK TIMELINE --}}
                    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 mt-6 relative overflow-hidden">
                        <div class="absolute -right-4 -top-4 opacity-5 pointer-events-none">
                            <i class="ph-fill ph-clock-counter-clockwise text-9xl text-elevate-dark"></i>
                        </div>
                        
                        <h3 class="text-lg font-black text-elevate-dark mb-6 flex items-center gap-2 relative z-10">
                            <i class="ph-fill ph-clock-counter-clockwise text-elevate-primary"></i> Riwayat Akademik
                        </h3>

                        @if($student->classHistories && $student->classHistories->count() > 0)
                            <div class="relative border-l-2 border-slate-100 ml-3 space-y-6 z-10">
                                @foreach($student->classHistories as $history)
                                    <div class="relative pl-6 group">
                                        {{-- Timeline Dot --}}
                                        <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-white border-[3px] border-elevate-accent/50 group-hover:border-elevate-primary transition-colors shadow-sm"></div>
                                        
                                        {{-- Content --}}
                                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 group-hover:bg-elevate-accent/5 group-hover:border-elevate-accent/20 transition-all">
                                            <div class="flex justify-between items-start mb-1.5">
                                                <h4 class="text-sm font-bold text-elevate-dark group-hover:text-elevate-primary transition-colors leading-none">
                                                    {{ $history->schoolClass->name ?? 'Kelas Dihapus' }}
                                                </h4>
                                                <span class="text-[9px] font-black text-slate-400 bg-white px-2 py-0.5 rounded-md shadow-sm border border-slate-100 uppercase tracking-wider">
                                                    {{ $history->academic_year }}
                                                </span>
                                            </div>
                                            <p class="text-xs text-slate-500 font-medium">Kenaikan / Mutasi Kelas</p>
                                        </div>
                                    </div>
                                @endforeach
                                
                                {{-- Pintu Masuk / Awal Masuk --}}
                                <div class="relative pl-6 opacity-60">
                                    <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-slate-200 border-[3px] border-white shadow-sm"></div>
                                    <h4 class="text-sm font-bold text-slate-600">Siswa Masuk / Terdaftar</h4>
                                    <p class="text-xs text-slate-400 font-medium mt-0.5">Awal mula pendataan</p>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-6 relative z-10 border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50">
                                <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center mx-auto mb-3 text-slate-300 shadow-sm border border-slate-100">
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
                        <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 relative overflow-hidden group hover:border-slate-200 transition-colors">
                            <div class="absolute top-0 right-0 p-6 opacity-[0.03] pointer-events-none">
                                <i class="ph-fill ph-briefcase text-9xl text-elevate-dark"></i>
                            </div>

                            <h3 class="text-lg font-black text-elevate-dark mb-6 flex items-center gap-2 relative z-10">
                                <i class="ph-fill ph-chart-polar text-elevate-primary"></i> Laporan Aktivitas Saat Ini
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 relative z-10">
                                <div class="bg-slate-50/50 p-5 rounded-2xl border border-slate-100">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Jalur Pilihan</p>
                                    <div class="text-xl font-black text-elevate-dark flex items-center gap-2.5">
                                        @php $status = $student->alumniProfile->activity_status; @endphp
                                        <span class="w-3.5 h-3.5 rounded-full shadow-inner
                                            {{ $status == 'SMA' ? 'bg-elevate-primary' : '' }}
                                            {{ $status == 'SMK' ? 'bg-orange-500' : '' }}
                                            {{ $status == 'MA' ? 'bg-emerald-500' : '' }}
                                            {{ $status == 'Pesantren' ? 'bg-teal-500' : '' }}
                                            {{ $status == 'Bekerja' ? 'bg-slate-500' : '' }}
                                            {{ $status == 'Lainnya' ? 'bg-purple-500' : '' }}">
                                        </span>
                                        {{ $status }}
                                    </div>
                                </div>

                                <div class="bg-slate-50/50 p-5 rounded-2xl border border-slate-100">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Instansi / Tempat</p>
                                    <div class="text-lg font-black text-elevate-dark leading-tight line-clamp-1" title="{{ $student->alumniProfile->campus_name ?? $student->alumniProfile->company_name }}">
                                        {{ $student->alumniProfile->campus_name ?? $student->alumniProfile->company_name ?? '-' }}
                                    </div>
                                    @if($student->alumniProfile->campus_major || $student->alumniProfile->position)
                                        <div class="text-xs font-bold text-elevate-primary mt-1.5 uppercase tracking-wide">
                                            {{ $student->alumniProfile->campus_major ?? $student->alumniProfile->position }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-6 pt-6 border-t border-slate-100 grid grid-cols-1 md:grid-cols-2 gap-6 relative z-10">
                                <div class="flex items-center gap-4 p-4 rounded-xl bg-emerald-50/50 border border-emerald-100/50">
                                    <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0"><i class="ph-fill ph-whatsapp-logo text-xl"></i></div>
                                    <div>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Nomor WhatsApp</p>
                                        @if($student->alumniProfile->phone_number)
                                            <a href="https://wa.me/{{ $student->alumniProfile->phone_number }}" target="_blank" class="text-sm font-black text-emerald-700 hover:text-emerald-500 transition-colors">
                                                {{ $student->alumniProfile->phone_number }}
                                            </a>
                                        @else
                                            <p class="text-sm font-bold text-slate-400">-</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 p-4 rounded-xl bg-slate-50/50 border border-slate-100">
                                    <div class="w-10 h-10 rounded-full bg-white border border-slate-200 text-slate-400 flex items-center justify-center shrink-0 shadow-sm"><i class="ph-bold ph-envelope-simple text-lg"></i></div>
                                    <div class="min-w-0">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Email</p>
                                        <p class="text-sm font-bold text-elevate-dark truncate" title="{{ $student->alumniProfile->email }}">{{ $student->alumniProfile->email ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Testimoni --}}
                        @if($student->alumniProfile->testimony)
                        <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 relative overflow-hidden">
                            <h3 class="text-lg font-black text-elevate-dark mb-6 flex items-center gap-2">
                                <i class="ph-fill ph-quotes text-elevate-primary"></i> Kesan & Pesan
                            </h3>
                            <div class="bg-elevate-accent/5 p-6 rounded-2xl border border-elevate-accent/10 italic text-slate-600 font-medium leading-relaxed relative">
                                <i class="ph-fill ph-quotes text-4xl text-elevate-accent/20 absolute -top-3 -left-2"></i>
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
                        {{-- JIKA BELUM MENGISI --}}
                        <div class="bg-white rounded-[2.5rem] p-12 shadow-sm border border-slate-100 text-center flex flex-col items-center justify-center min-h-[400px]">
                            <div class="w-24 h-24 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300 shadow-sm">
                                <i class="ph-duotone ph-clipboard-text text-5xl"></i>
                            </div>
                            <h3 class="text-xl font-black text-elevate-dark mb-2">Belum Mengisi Tracer Study</h3>
                            <p class="text-slate-500 text-sm max-w-sm mx-auto mb-8 leading-relaxed">Alumni ini belum memperbarui data kelulusan atau rekam jejak sekolah lanjutan.</p>
                            
                            <a href="{{ route('admin.alumni.edit', $student->id) }}" class="inline-flex items-center gap-2 px-8 py-3.5 bg-elevate-dark text-white font-bold rounded-2xl hover:bg-elevate-primary transition-all shadow-lg shadow-elevate-dark/20 transform active:scale-95">
                                <i class="ph-bold ph-pencil-simple text-lg"></i> Input Data Manual
                            </a>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</x-app-layout>