<x-student-learning-layout>
    {{-- CDN SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- CUSTOM STYLES --}}
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        
        /* Shimmer Effect untuk Prioritas */
        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }
        .animate-shimmer {
            animation: shimmer 2s infinite linear;
            background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0.4) 50%, rgba(255,255,255,0) 100%);
            background-size: 1000px 100%;
        }
    </style>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        
        {{-- HEADER DASHBOARD MENGGUNAKAN TEMA ELEVATE --}}
        <div class="animate-enter bg-elevate-dark rounded-[2.5rem] p-8 md:p-12 mb-12 relative overflow-hidden shadow-2xl shadow-elevate-dark/20 border border-elevate-primary/20 group">
            {{-- Decoration --}}
            <div class="absolute inset-0 bg-gradient-to-br from-elevate-dark via-elevate-dark to-elevate-primary/40"></div>
            <div class="absolute top-0 right-0 w-80 h-80 bg-elevate-accent/20 rounded-full blur-[100px] -mr-20 -mt-20 pointer-events-none group-hover:bg-elevate-accent/30 transition-all duration-1000"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-elevate-peach/20 rounded-full blur-[80px] -ml-10 -mb-10 pointer-events-none group-hover:bg-elevate-peach/30 transition-all duration-1000"></div>
            <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-10">
                <div class="text-center md:text-left flex-1">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/5 border border-white/10 backdrop-blur-md mb-6 shadow-sm">
                        <span class="relative flex h-2.5 w-2.5">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-elevate-accent opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-elevate-accent"></span>
                        </span>
                        <span class="text-[11px] font-bold text-elevate-soft uppercase tracking-widest">Tahun Ajaran Aktif</span>
                    </div>
                    
                    <h1 class="text-4xl md:text-6xl font-black text-white mb-4 tracking-tight leading-tight">
                        Halo, <span class="text-transparent bg-clip-text bg-gradient-to-r from-elevate-accent via-white to-elevate-peach-light animate-gradient">{{ explode(' ', Auth::guard('student')->user()->name)[0] }}!</span>
                    </h1>
                    
                    <p class="text-elevate-soft font-medium text-base md:text-lg max-w-xl mb-8 leading-relaxed opacity-90">
                        Selamat datang kembali di ruang belajar digitalmu. <br class="hidden md:block"> Jangan lupa cek tugas prioritas sebelum memulai materi baru hari ini.
                    </p>

                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 text-white text-sm font-bold">
                        <div class="flex items-center gap-2 bg-white/10 px-4 py-2 rounded-xl border border-white/10 hover:bg-white/20 transition-colors">
                            <i class="ph-fill ph-student text-elevate-peach-light text-lg"></i>
                            <span>Kelas {{ Auth::guard('student')->user()->schoolClass->name ?? 'Umum' }}</span>
                        </div>
                        <div class="flex items-center gap-2 bg-white/10 px-4 py-2 rounded-xl border border-white/10 hover:bg-white/20 transition-colors">
                            <i class="ph-fill ph-calendar text-elevate-accent text-lg"></i>
                            <span>{{ now()->translatedFormat('l, d F Y') }}</span>
                        </div>
                    </div>
                </div>
                
                {{-- Ilustrasi 3D Sederhana --}}
                <div class="hidden md:block relative transform hover:scale-105 transition-transform duration-700">
                    <div class="relative w-40 h-40">
                        <div class="absolute inset-0 bg-gradient-to-tr from-elevate-primary to-elevate-accent rounded-[2rem] rotate-6 opacity-60 blur-lg"></div>
                        <div class="absolute inset-0 bg-gradient-to-tr from-elevate-dark to-elevate-primary rounded-[2rem] shadow-2xl border border-white/10 flex items-center justify-center z-10">
                            <i class="ph-duotone ph-rocket-launch text-7xl text-white drop-shadow-[0_0_15px_rgba(86,187,241,0.5)]"></i>
                        </div>
                        <div class="absolute -top-4 -right-4 bg-white text-elevate-dark p-3 rounded-xl shadow-lg font-black text-xs z-20 animate-bounce">
                            <i class="ph-fill ph-star text-elevate-peach"></i> Semangat!
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- BAGIAN PRIORITAS (Tugas/Materi Baru) --}}
        @if(isset($prioritySubjects) && $prioritySubjects->count() > 0)
            <div class="animate-enter mb-16" style="animation-delay: 100ms">
                <div class="flex items-end justify-between mb-8 px-2">
                    <div>
                        <h2 class="text-2xl font-black text-elevate-dark flex items-center gap-3">
                            <span class="w-10 h-10 rounded-xl bg-elevate-peach/20 text-elevate-peach-dark flex items-center justify-center text-xl shadow-sm rotate-3">
                                <i class="ph-fill ph-fire"></i>
                            </span>
                            Prioritas Belajar
                        </h2>
                        <p class="text-sm font-bold text-elevate-dark/50 mt-2 ml-14">Selesaikan tugas ini segera.</p>
                    </div>
                </div>

                 <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($prioritySubjects as $index => $subject)
                        <a href="{{ route('students.learning.play', $subject->id) }}" class="group relative flex flex-col bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1.5 border border-slate-100 hover:ring-2 {{ $subject->active_tasks_count > 0 ? 'hover:ring-rose-300/50' : 'hover:ring-sky-300/50' }}">
                            
                            {{-- Cover jika ada di prioritas --}}
                            @if($subject->priority_cover && \Illuminate\Support\Facades\Storage::disk('public')->exists($subject->priority_cover))
                                <div class="relative h-36 w-full overflow-hidden shrink-0 bg-slate-900">
                                    <img src="{{ asset('storage/' . $subject->priority_cover) }}" alt="{{ $subject->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-black/20 pointer-events-none"></div>
                                    <div class="absolute top-3 right-3 z-10">
                                        @if($subject->active_tasks_count > 0)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-rose-600/90 backdrop-blur-md text-white text-[10px] font-black uppercase tracking-wider shadow-md animate-pulse">
                                                <i class="ph-bold ph-clock-countdown"></i> {{ $subject->active_tasks_count }} Tugas
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-emerald-600/90 backdrop-blur-md text-white text-[10px] font-black uppercase tracking-wider shadow-md">
                                                <i class="ph-bold ph-sparkle"></i> Materi Baru
                                            </span>
                                        @endif
                                    </div>
                                    <div class="absolute bottom-2.5 left-3 z-10">
                                        <span class="px-2 py-0.5 rounded-md bg-black/60 backdrop-blur-md text-white/90 border border-white/15 text-[9px] font-black uppercase tracking-wider">
                                            {{ $subject->name }}
                                        </span>
                                    </div>
                                </div>
                            @endif

                            <div class="p-5 flex flex-col flex-1 justify-between relative overflow-hidden">
                                {{-- Top Header jika tanpa cover --}}
                                @if(!$subject->priority_cover || !\Illuminate\Support\Facades\Storage::disk('public')->exists($subject->priority_cover))
                                    <div class="flex justify-between items-start mb-3">
                                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br {{ $subject->active_tasks_count > 0 ? 'from-orange-50 to-rose-100 text-rose-600 border border-rose-200/50' : 'from-sky-50 to-blue-100 text-sky-600 border border-sky-200/50' }} flex items-center justify-center text-2xl shadow-inner group-hover:scale-110 transition-transform">
                                            <i class="ph-duotone {{ $subject->active_tasks_count > 0 ? 'ph-fire' : 'ph-book-open' }}"></i>
                                        </div>
                                        @if($subject->active_tasks_count > 0)
                                            <span class="relative overflow-hidden bg-rose-500 text-white text-[10px] font-black px-3 py-1.5 rounded-lg shadow-md shadow-rose-500/20 uppercase tracking-wider">
                                                <span class="relative z-10">{{ $subject->active_tasks_count }} Tugas Prioritas</span>
                                                <div class="absolute inset-0 -translate-x-full animate-shimmer z-0"></div>
                                            </span>
                                        @elseif($subject->new_materials_count > 0)
                                            <span class="relative overflow-hidden bg-sky-500 text-white text-[10px] font-black px-3 py-1.5 rounded-lg shadow-md shadow-sky-500/20 uppercase tracking-wider">
                                                <span class="relative z-10">Materi Baru</span>
                                                <div class="absolute inset-0 -translate-x-full animate-shimmer z-0"></div>
                                            </span>
                                        @endif
                                    </div>
                                @endif

                                <div>
                                    <h3 class="font-bold text-base text-elevate-dark mb-1 group-hover:text-elevate-primary transition-colors line-clamp-1">
                                        {{ $subject->priority_item_title ?? $subject->name }}
                                    </h3>
                                    <p class="text-xs text-slate-400 font-medium mb-4 line-clamp-2">
                                        {{ $subject->active_tasks_count > 0 ? 'Mata pelajaran ' . $subject->name . ' • Ada tugas yang mendekati batas waktu.' : 'Mata pelajaran ' . $subject->name . ' • Modul baru siap dipelajari.' }}
                                    </p>
                                </div>

                                {{-- Footer Action --}}
                                <div class="mt-auto pt-3 border-t border-slate-100 flex items-center justify-between">
                                    <span class="text-xs font-bold {{ $subject->active_tasks_count > 0 ? 'text-rose-600' : 'text-sky-600' }} group-hover:underline">
                                        {{ $subject->active_tasks_count > 0 ? 'Kerjakan Sekarang' : 'Pelajari Sekarang' }}
                                    </span>
                                    <i class="ph-bold ph-arrow-right {{ $subject->active_tasks_count > 0 ? 'text-rose-500' : 'text-sky-500' }} group-hover:translate-x-1 transition-transform"></i>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- BAGIAN SEMUA MATA PELAJARAN --}}
        <div class="animate-enter" style="animation-delay: 200ms">
            <div class="flex items-end justify-between mb-8 px-2">
                <div>
                    <h2 class="text-2xl font-black text-elevate-dark flex items-center gap-3">
                        <span class="w-10 h-10 rounded-xl bg-elevate-soft text-elevate-primary flex items-center justify-center text-xl shadow-sm -rotate-3">
                            <i class="ph-fill ph-books"></i>
                        </span>
                        Ruang Kelas
                    </h2>
                    <p class="text-sm font-bold text-elevate-dark/50 mt-2 ml-14">Daftar semua mata pelajaranmu.</p>
                </div>
            </div>
            
            @if(!isset($allSubjects) || $allSubjects->isEmpty())
                <div class="bg-elevate-surface rounded-[2.5rem] p-12 text-center border-2 border-dashed border-elevate-soft">
                    <div class="w-24 h-24 bg-elevate-soft/50 rounded-full flex items-center justify-center mx-auto mb-6 text-elevate-dark/30">
                        <i class="ph-duotone ph-books text-5xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-elevate-dark mb-2">Belum Ada Mata Pelajaran</h3>
                    <p class="text-elevate-dark/60 font-medium max-w-md mx-auto">Data mata pelajaran belum ditambahkan oleh admin atau kamu belum terdaftar di kelas manapun.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($allSubjects as $index => $subject)
                        @php
                            $palettes = [
                                'blue'    => ['bg' => 'from-[#0d52a1] to-blue-500', 'light' => 'bg-elevate-soft', 'text' => 'text-elevate-primary', 'border' => 'border-elevate-soft', 'shadow' => 'shadow-elevate-primary/20', 'ring' => 'ring-elevate-accent/20'],
                                'cyan'    => ['bg' => 'from-sky-500 to-cyan-400', 'light' => 'bg-cyan-50', 'text' => 'text-cyan-700', 'border' => 'border-cyan-100', 'shadow' => 'shadow-sky-400/20', 'ring' => 'ring-sky-300/30'],
                                'peach'   => ['bg' => 'from-orange-500 to-rose-400', 'light' => 'bg-orange-50', 'text' => 'text-orange-700', 'border' => 'border-orange-100', 'shadow' => 'shadow-orange-300/20', 'ring' => 'ring-orange-200/40'],
                                'navy'    => ['bg' => 'from-slate-700 to-slate-500', 'light' => 'bg-slate-100', 'text' => 'text-slate-700', 'border' => 'border-slate-200', 'shadow' => 'shadow-slate-300/20', 'ring' => 'ring-slate-300/30'],
                                'emerald' => ['bg' => 'from-emerald-500 to-teal-400', 'light' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-100', 'shadow' => 'shadow-emerald-300/20', 'ring' => 'ring-emerald-200/40'],
                            ];

                            $name = strtolower($subject->name);
                            $selectedIcon = 'ph-book-bookmark'; 
                            $selectedTheme = 'blue'; 

                            if (str_contains($name, 'informatika') || str_contains($name, 'tik') || str_contains($name, 'komputer')) {
                                $selectedIcon = 'ph-desktop'; $selectedTheme = 'cyan';
                            } elseif (str_contains($name, 'seni') || str_contains($name, 'budaya')) {
                                $selectedIcon = 'ph-palette'; $selectedTheme = 'peach';
                            } elseif (str_contains($name, 'matematika')) {
                                $selectedIcon = 'ph-calculator'; $selectedTheme = 'navy';
                            } elseif (str_contains($name, 'ipa') || str_contains($name, 'fisika') || str_contains($name, 'kimia')) {
                                $selectedIcon = 'ph-flask'; $selectedTheme = 'emerald';
                            } elseif (str_contains($name, 'ips') || str_contains($name, 'sejarah')) {
                                $selectedIcon = 'ph-globe-hemisphere-west'; $selectedTheme = 'navy';
                            } elseif (str_contains($name, 'bahasa') || str_contains($name, 'inggris') || str_contains($name, 'indonesia')) {
                                $selectedIcon = 'ph-translate'; $selectedTheme = 'peach';
                            } elseif (str_contains($name, 'agama')) {
                                $selectedIcon = 'ph-hands-praying'; $selectedTheme = 'emerald';
                            } elseif (str_contains($name, 'pjok') || str_contains($name, 'olahraga')) {
                                $selectedIcon = 'ph-soccer-ball'; $selectedTheme = 'cyan';
                            } else {
                                $keys = array_keys($palettes);
                                $selectedTheme = $keys[$index % count($keys)];
                            }

                            $t = $palettes[$selectedTheme];
                            $hasTasks = ($subject->active_tasks_count ?? 0) > 0;
                            $hasNewMat = ($subject->new_materials_count ?? 0) > 0;
                        @endphp

                        {{-- SUBJECT CARD (Pure Gradient & Mapel Icon, Consistent & Elegant) --}}
                        <a href="{{ route('students.learning.play', $subject->id) }}" 
                           class="group relative flex flex-col bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl hover:shadow-elevate-dark/10 transition-all duration-300 hover:-translate-y-1.5 h-full border border-slate-100 hover:ring-2 ring-{{ explode('-', $selectedTheme)[0] }}-200/50">

                            {{-- COVER THUMBNAIL (PURE THEMATIC GRADIENT) --}}
                            <div class="relative h-28 w-full overflow-hidden shrink-0">
                                <div class="w-full h-full bg-gradient-to-br {{ $t['bg'] }} flex flex-col justify-between p-4 group-hover:scale-105 transition-transform duration-500 relative">
                                    <div class="absolute -right-4 -bottom-4 text-white/[0.12] pointer-events-none select-none" style="font-size:6.5rem">
                                        <i class="ph-duotone {{ $selectedIcon }}"></i>
                                    </div>
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/35 via-transparent to-transparent pointer-events-none"></div>

                                    <div class="flex items-start justify-between relative z-10">
                                        <span class="w-8 h-8 rounded-xl bg-white/20 backdrop-blur-md border border-white/30 flex items-center justify-center text-sm text-white shadow-sm">
                                            <i class="ph-bold {{ $selectedIcon }}"></i>
                                        </span>
                                        @if($hasTasks)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-black/50 backdrop-blur-sm border border-white/20 text-amber-300 text-[9px] font-black animate-pulse shadow-sm">
                                                <i class="ph-bold ph-clock-countdown"></i> {{ $subject->active_tasks_count }} Tugas
                                            </span>
                                        @elseif($hasNewMat)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-black/50 backdrop-blur-sm border border-white/20 text-emerald-300 text-[9px] font-black shadow-sm">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Materi Baru
                                            </span>
                                        @endif
                                    </div>
                                    <div class="relative z-10">
                                        <p class="text-[9px] font-black text-white/80 uppercase tracking-widest">Mata Pelajaran</p>
                                    </div>
                                </div>
                            </div>

                            {{-- CARD BODY --}}
                            <div class="p-4 flex flex-col flex-grow justify-between relative overflow-hidden">
                                <div class="absolute -right-8 -top-8 w-24 h-24 rounded-full bg-gradient-to-br {{ $t['bg'] }} opacity-[0.06] group-hover:opacity-[0.12] blur-2xl transition-opacity duration-500 pointer-events-none"></div>

                                <div>
                                    <h4 class="font-bold text-sm text-elevate-dark leading-snug mb-1 relative z-10 line-clamp-2 group-hover:text-elevate-primary transition-colors">
                                        {{ $subject->name }}
                                    </h4>
                                </div>

                                <div class="flex items-center gap-2 mt-3 pt-2.5 border-t border-slate-100 relative z-10">
                                    <div class="w-6 h-6 rounded-full bg-gradient-to-br {{ $t['bg'] }} flex items-center justify-center text-white text-[10px] font-black shadow-sm shrink-0">
                                        <i class="ph-bold ph-user"></i>
                                    </div>
                                    <span class="text-[10px] font-bold text-slate-500 truncate flex-1">Tim Pengajar</span>
                                    <span class="text-[9px] font-black text-white bg-gradient-to-r {{ $t['bg'] }} px-2.5 py-1 rounded-lg shadow-sm shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                                        Masuk →
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- BAGIAN MODUL PEMBELAJARAN TERBARU (UDEMY / LMS-CATALOG STYLE) --}}
        @if(isset($recentMaterials) && $recentMaterials->count() > 0)
            <div class="animate-enter mt-16" style="animation-delay: 300ms">
                <div class="flex items-end justify-between mb-8 px-2">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-sky-500/10 border border-sky-400/30 text-sky-600 text-[11px] font-black uppercase tracking-widest mb-2">
                            <i class="ph-duotone ph-sparkle text-sm"></i>
                            <span>Katalog Modul LMS</span>
                        </div>
                        <h2 class="text-2xl font-black text-elevate-dark flex items-center gap-3">
                            <span class="w-10 h-10 rounded-xl bg-sky-500/20 text-sky-600 flex items-center justify-center text-xl shadow-sm rotate-2">
                                <i class="ph-fill ph-book-open"></i>
                            </span>
                            Modul Pembelajaran Terbaru
                        </h2>
                        <p class="text-sm font-bold text-elevate-dark/50 mt-2 ml-14">Jelajahi modul dan bahan ajar digital dari guru pengampu.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($recentMaterials as $mat)
                        @php
                            $matSubName = strtolower($mat->subject->name ?? '');
                            $gradientTheme = match(true) {
                                str_contains($matSubName, 'matematika') => ['bg' => 'from-blue-600 via-indigo-700 to-slate-900', 'icon' => 'ph-calculator'],
                                str_contains($matSubName, 'ipa') || str_contains($matSubName, 'biologi') || str_contains($matSubName, 'fisika') => ['bg' => 'from-emerald-600 via-teal-700 to-slate-900', 'icon' => 'ph-flask'],
                                str_contains($matSubName, 'inggris') || str_contains($matSubName, 'bahasa') || str_contains($matSubName, 'indonesia') => ['bg' => 'from-purple-600 via-indigo-800 to-slate-900', 'icon' => 'ph-translate'],
                                str_contains($matSubName, 'informatika') || str_contains($matSubName, 'tik') => ['bg' => 'from-sky-600 via-blue-800 to-slate-950', 'icon' => 'ph-code'],
                                str_contains($matSubName, 'ips') || str_contains($matSubName, 'sejarah') => ['bg' => 'from-amber-600 via-orange-800 to-slate-950', 'icon' => 'ph-compass'],
                                default => ['bg' => 'from-sky-600 via-indigo-800 to-[#021124]', 'icon' => 'ph-book-open-text']
                            };
                        @endphp

                        <div class="rounded-2xl bg-white border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-elevate-dark/10 hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between group relative overflow-hidden">
                            {{-- COVER THUMBNAIL --}}
                            <div class="relative h-40 w-full overflow-hidden shrink-0 bg-slate-900">
                                @if($mat->cover_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($mat->cover_image))
                                    <img src="{{ asset('storage/' . $mat->cover_image) }}" alt="{{ $mat->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-black/20 pointer-events-none"></div>
                                @else
                                    <div class="w-full h-full bg-gradient-to-br {{ $gradientTheme['bg'] }} p-4 flex flex-col justify-between relative overflow-hidden group-hover:scale-105 transition-transform duration-500">
                                        <div class="absolute -right-6 -bottom-6 text-white/10 text-8xl pointer-events-none">
                                            <i class="ph-duotone {{ $gradientTheme['icon'] }}"></i>
                                        </div>
                                        <div class="flex items-center justify-between relative z-10">
                                            <span class="w-8 h-8 rounded-xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-sm text-white">
                                                <i class="ph-bold {{ $gradientTheme['icon'] }}"></i>
                                            </span>
                                            <span class="text-[9px] font-black tracking-widest text-white/70 uppercase">MODUL DIGITAL</span>
                                        </div>
                                        <div class="relative z-10">
                                            <p class="text-[10px] font-black text-white/80 uppercase tracking-widest">{{ $mat->subject->name ?? 'Mata Pelajaran' }}</p>
                                        </div>
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent pointer-events-none"></div>
                                    </div>
                                @endif

                                {{-- Badges Overlay --}}
                                <div class="absolute top-3 left-3 right-3 z-10 flex items-center justify-between gap-2 pointer-events-none">
                                    <span class="px-2.5 py-1 rounded-lg bg-[#021124]/85 backdrop-blur-md text-sky-300 border border-white/20 text-[10px] font-black uppercase tracking-wider shadow-md truncate max-w-[170px]">
                                        {{ $mat->subject->name ?? 'Mapel' }}
                                    </span>
                                    @if($mat->schoolClass)
                                        <span class="px-2 py-1 rounded-lg bg-black/60 backdrop-blur-md text-slate-200 text-[10px] font-bold border border-white/15 shadow-md">
                                            Kelas {{ $mat->schoolClass->name }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- CARD BODY --}}
                            <div class="p-4 sm:p-5 flex-1 flex flex-col justify-between">
                                <div>
                                    <h3 class="text-base font-bold text-elevate-dark tracking-tight leading-snug mb-1.5 group-hover:text-elevate-primary transition-colors line-clamp-2">
                                        {{ $mat->title }}
                                    </h3>
                                    <p class="text-slate-500 text-xs line-clamp-2 leading-relaxed mb-3 font-normal">
                                        {{ strip_tags($mat->resume ?? $mat->description ?? 'Modul pembelajaran digital interaktif disiapkan oleh guru pengampu.') }}
                                    </p>
                                </div>

                                <div>
                                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2 mb-3">
                                        <div class="flex items-center gap-2 min-w-0">
                                            <div class="w-6 h-6 rounded-full bg-gradient-to-tr from-sky-500 to-blue-600 text-white font-bold text-[10px] flex items-center justify-center shrink-0">
                                                {{ Str::upper(Str::substr($mat->teacher->name ?? 'G', 0, 1)) }}
                                            </div>
                                            <span class="text-[11px] font-bold text-slate-600 truncate">
                                                {{ $mat->teacher->name ?? 'Guru Pengampu' }}
                                            </span>
                                        </div>
                                        <span class="text-[10px] font-bold text-slate-400 flex items-center gap-1 shrink-0">
                                            <i class="ph-bold ph-files text-sky-500"></i>
                                            {{ $mat->attachments->count() + ($mat->file_path ? 1 : 0) }} File
                                        </span>
                                    </div>

                                    <a href="{{ route('students.learning.play', $mat->subject_id) }}" class="w-full py-2.5 px-3 rounded-xl bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600 hover:from-sky-400 hover:to-indigo-500 text-white font-bold text-xs flex items-center justify-center gap-1.5 shadow-md shadow-sky-500/20 active:scale-95 transition-all">
                                        <span>Buka Modul Belajar</span>
                                        <i class="ph-bold ph-arrow-right text-xs"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: "{{ session('success') }}",
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    customClass: {
                        popup: 'rounded-xl shadow-lg border border-emerald-100 bg-white'
                    }
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "{{ session('error') }}",
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true,
                    customClass: {
                        popup: 'rounded-xl shadow-lg border border-rose-100 bg-white'
                    }
                });
            @endif
        });
    </script>
</x-student-learning-layout>