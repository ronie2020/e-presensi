{{-- LANDING SECTION: KATALOG MODUL PEMBELAJARAN LMS (DICODING / UDEMY STYLE WITH THUMBNAILS) --}}
<section id="katalog-lms" class="py-16 sm:py-24 relative overflow-hidden font-sans">
    
    <!-- Ambient Glow background -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-sky-500/10 rounded-full blur-[180px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 relative z-10">
        
        <!-- SECTION HEADER -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-12">
            <div>
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-sky-500/10 border border-sky-400/30 text-sky-400 text-xs font-bold uppercase tracking-widest mb-3 backdrop-blur-md">
                    <i class="ph-duotone ph-sparkle text-sm text-sky-400"></i>
                    <span>Ruang Belajar Digital • LMS</span>
                </div>
                <h2 class="text-3xl sm:text-4xl font-black text-white tracking-tight leading-tight">
                    Katalog Modul Pembelajaran <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-400 via-blue-400 to-indigo-300">Interaktif &amp; Terstruktur</span>
                </h2>
                <p class="text-slate-300 text-sm sm:text-base font-normal mt-2 max-w-2xl">
                    Jelajahi materi Kurikulum Merdeka, modul bacaan guru, serta latihan soal interaktif. Pengunjung dapat melihat silabus materi sebelum masuk ke ruang belajar.
                </p>
            </div>

            <a href="{{ route('student.login.learning') }}" class="px-5 py-3 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 text-white font-bold text-xs flex items-center gap-2 backdrop-blur-md transition-all group shrink-0">
                <span>Login Ruang Belajar (LMS)</span>
                <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>

        <!-- GRID OF FEATURED MATERIALS -->
        @if(isset($featuredMaterials) && count($featuredMaterials) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @foreach($featuredMaterials as $mat)
                    @php
                        $subjectName = strtolower($mat->subject->name ?? '');
                        
                        // Theme gradients & icons for hybrid fallback
                        $gradientTheme = match(true) {
                            str_contains($subjectName, 'matematika') => ['bg' => 'from-blue-600 via-indigo-700 to-slate-900', 'icon' => 'ph-calculator', 'color' => 'text-blue-300'],
                            str_contains($subjectName, 'ipa') || str_contains($subjectName, 'biologi') || str_contains($subjectName, 'fisika') => ['bg' => 'from-emerald-600 via-teal-700 to-slate-900', 'icon' => 'ph-flask', 'color' => 'text-emerald-300'],
                            str_contains($subjectName, 'inggris') || str_contains($subjectName, 'bahasa') || str_contains($subjectName, 'indonesia') => ['bg' => 'from-purple-600 via-indigo-800 to-slate-900', 'icon' => 'ph-translate', 'color' => 'text-purple-300'],
                            str_contains($subjectName, 'informatika') || str_contains($subjectName, 'tik') => ['bg' => 'from-sky-600 via-blue-800 to-slate-950', 'icon' => 'ph-code', 'color' => 'text-sky-300'],
                            str_contains($subjectName, 'ips') || str_contains($subjectName, 'sejarah') => ['bg' => 'from-amber-600 via-orange-800 to-slate-950', 'icon' => 'ph-compass', 'color' => 'text-amber-300'],
                            default => ['bg' => 'from-sky-600 via-indigo-800 to-[#021124]', 'icon' => 'ph-book-open-text', 'color' => 'text-sky-300']
                        };
                    @endphp

                    <div class="rounded-[2.5rem] bg-[#031d3d]/80 backdrop-blur-xl border border-white/15 shadow-xl hover:border-sky-400/50 hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between group relative overflow-hidden">
                        
                        <!-- CARD THUMBNAIL / COVER IMAGE (UDEMY STYLE) -->
                        <div class="relative h-48 w-full overflow-hidden shrink-0">
                            @if($mat->cover_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($mat->cover_image))
                                <img src="{{ asset('storage/' . $mat->cover_image) }}" alt="{{ $mat->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-[#031d3d] via-transparent to-black/30"></div>
                            @else
                                {{-- Smart Fallback Pattern --}}
                                <div class="w-full h-full bg-gradient-to-br {{ $gradientTheme['bg'] }} p-6 flex flex-col justify-between relative overflow-hidden group-hover:scale-105 transition-transform duration-500">
                                    <div class="absolute -right-6 -bottom-6 text-white/10 text-9xl pointer-events-none">
                                        <i class="ph-duotone {{ $gradientTheme['icon'] }}"></i>
                                    </div>
                                    <div class="flex items-center justify-between relative z-10">
                                        <span class="w-10 h-10 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-xl text-white">
                                            <i class="ph-bold {{ $gradientTheme['icon'] }}"></i>
                                        </span>
                                        <span class="text-[10px] font-black tracking-widest text-white/70 uppercase">MODUL DIGITAL</span>
                                    </div>
                                    <div class="relative z-10">
                                        <p class="text-xs font-black text-white/80 uppercase tracking-widest">{{ $mat->subject->name ?? 'Mata Pelajaran' }}</p>
                                    </div>
                                    <div class="absolute inset-0 bg-gradient-to-t from-[#031d3d] via-transparent to-transparent"></div>
                                </div>
                            @endif

                            <!-- Subject Badge Overlay -->
                            <div class="absolute top-4 left-4 z-10 flex items-center gap-2 flex-wrap">
                                <span class="px-3 py-1 rounded-full bg-[#021124]/80 backdrop-blur-md text-sky-300 border border-white/20 text-[10px] font-black uppercase tracking-wider shadow-md">
                                    {{ $mat->subject->name ?? 'Mata Pelajaran' }}
                                </span>
                                @if($mat->schoolClass)
                                    <span class="px-2.5 py-1 rounded-full bg-black/60 backdrop-blur-md text-slate-300 text-[10px] font-bold border border-white/10">
                                        Kelas {{ $mat->schoolClass->name }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- CARD BODY -->
                        <div class="p-6 flex-1 flex flex-col justify-between">
                            <div>
                                <!-- Title -->
                                <h3 class="text-lg font-black text-white tracking-tight leading-snug mb-2 group-hover:text-sky-300 transition-colors line-clamp-2">
                                    {{ $mat->title }}
                                </h3>

                                <!-- Description Snippet -->
                                <p class="text-slate-300 text-xs line-clamp-2 leading-relaxed mb-4 font-medium">
                                    {{ Str::limit(strip_tags($mat->content ?? $mat->resume ?? 'Modul pembelajaran digital interaktif disiapkan oleh guru pengampu.'), 100) }}
                                </p>
                            </div>

                            <div>
                                <!-- Teacher Info & Meta Stats -->
                                <div class="pt-3 border-t border-white/10 flex items-center justify-between gap-3 mb-4">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-sky-500 to-blue-600 p-[1px] shrink-0">
                                            <div class="w-full h-full bg-[#021124] rounded-[11px] flex items-center justify-center font-black text-sky-400 text-xs">
                                                {{ Str::upper(Str::substr($mat->teacher->name ?? 'G', 0, 1)) }}
                                            </div>
                                        </div>
                                        <span class="text-xs font-bold text-slate-200 truncate">
                                            {{ $mat->teacher->name ?? 'Guru SMPN 3 Lakbok' }}
                                        </span>
                                    </div>

                                    <div class="flex items-center gap-2 shrink-0 text-[11px] text-slate-400 font-bold">
                                        <span class="flex items-center gap-1">
                                            <i class="ph-bold ph-files text-sky-400"></i>
                                            {{ $mat->attachments->count() + ($mat->file_path ? 1 : 0) }} File
                                        </span>
                                    </div>
                                </div>

                                <!-- CTA Button to Public Preview -->
                                <a href="{{ route('lms.public.preview', $mat->id) }}" class="w-full py-3.5 px-4 rounded-xl bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600 hover:from-sky-400 hover:to-indigo-500 text-white font-black text-xs flex items-center justify-center gap-2 shadow-lg shadow-sky-500/25 active:scale-95 transition-all">
                                    <span>Lihat Silabus &amp; Mulai Belajar</span>
                                    <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
                                </a>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        @else
            <!-- EMPTY STATE -->
            <div class="rounded-[2.5rem] bg-[#031d3d]/60 border border-white/15 p-12 text-center backdrop-blur-xl">
                <div class="w-16 h-16 rounded-full bg-sky-500/20 text-sky-300 flex items-center justify-center text-3xl mx-auto mb-4 border border-sky-400/30">
                    <i class="ph-bold ph-books"></i>
                </div>
                <h3 class="text-xl font-black text-white mb-2">Modul Pembelajaran Baru Segera Hadir</h3>
                <p class="text-slate-300 text-xs max-w-md mx-auto">
                    Guru sedang mempersiapkan modul pembelajaran Kurikulum Merdeka interaktif terbaru. Silakan periksa kembali beberapa saat lagi.
                </p>
            </div>
        @endif

    </div>
</section>
