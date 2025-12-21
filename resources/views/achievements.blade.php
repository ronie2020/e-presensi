<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Arsip Prestasi - {{ config('app.name', 'SMP Negeri 3 Lakbok') }}</title>
    
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Animasi Custom (Konsisten dengan Dashboard) */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-enter { animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
        
        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
        .animate-float { animation: float 6s ease-in-out infinite; }

        @keyframes shine { 
            0% { transform: translateX(-100%) rotate(45deg); } 
            100% { transform: translateX(200%) rotate(45deg); } 
        }
        .group:hover .animate-shine { animation: shine 1.5s; }
    </style>
</head>
<body class="antialiased text-slate-800 bg-slate-50 flex flex-col min-h-screen font-[Plus_Jakarta_Sans]">

    <!-- NAVBAR -->
    <nav class="bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-10 h-10 object-contain group-hover:rotate-12 transition-transform">
                    <span class="text-lg font-extrabold text-slate-900 tracking-tight hidden sm:block">SMPN 3 LAKBOK</span>
                </a>
                <a href="{{ url('/') }}" class="text-sm font-bold text-slate-600 hover:text-blue-600 transition flex items-center gap-2 bg-slate-50 px-4 py-2 rounded-full hover:bg-blue-50">
                    <i class="ph-bold ph-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </nav>

    <!-- HEADER SECTION (Hall of Fame Style) -->
    <div class="relative overflow-hidden bg-slate-900 py-20 sm:py-24">
        <!-- Background Effects -->
        <div class="absolute inset-0 bg-gradient-to-br from-yellow-600/90 via-amber-600/80 to-slate-900"></div>
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-20"></div>
        
        <!-- Animated Orbs -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-yellow-400/30 rounded-full blur-[100px] animate-float"></div>
        <div class="absolute bottom-0 left-0 w-80 h-80 bg-orange-500/30 rounded-full blur-[80px] animate-float" style="animation-delay: 2s;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <div class="animate-enter inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-yellow-500/20 border border-yellow-400/30 text-yellow-200 text-xs font-bold uppercase tracking-widest mb-6 backdrop-blur-sm shadow-[0_0_15px_rgba(234,179,8,0.3)]">
                <i class="ph-fill ph-trophy"></i> Hall of Fame
            </div>
            <h1 class="animate-enter text-4xl md:text-6xl font-black text-white mb-6 tracking-tight drop-shadow-sm" style="animation-delay: 100ms;">
                Arsip Prestasi Sekolah
            </h1>
            <p class="animate-enter text-yellow-50/90 text-lg max-w-2xl mx-auto font-medium leading-relaxed" style="animation-delay: 200ms;">
                Rekam jejak kebanggaan siswa dan guru SMP Negeri 3 Lakbok yang telah mengharumkan nama sekolah di berbagai tingkat kompetisi.
            </p>
        </div>
    </div>

    <!-- FILTER SECTION (Sticky Glass) -->
    <div class="sticky top-20 z-40 bg-white/90 backdrop-blur-xl border-b border-slate-200/60 shadow-sm transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <form action="{{ route('public.achievements') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-center justify-between">
                <!-- Filter Level Pills -->
                <div class="flex items-center gap-2 w-full md:w-auto overflow-x-auto pb-2 md:pb-0 no-scrollbar mask-gradient">
                    <a href="{{ route('public.achievements') }}" 
                       class="whitespace-nowrap px-5 py-2.5 rounded-full text-sm font-bold border transition-all hover:shadow-md hover:-translate-y-0.5 {{ !request('level') ? 'bg-gradient-to-r from-yellow-500 to-amber-500 text-white border-transparent shadow-lg shadow-yellow-500/30' : 'bg-white text-slate-600 border-slate-200 hover:border-yellow-400' }}">
                       Semua
                    </a>
                    @foreach($levels as $lvl)
                        <a href="{{ route('public.achievements', array_merge(request()->query(), ['level' => $lvl])) }}" 
                           class="whitespace-nowrap px-5 py-2.5 rounded-full text-sm font-bold border transition-all hover:shadow-md hover:-translate-y-0.5 {{ request('level') == $lvl ? 'bg-gradient-to-r from-yellow-500 to-amber-500 text-white border-transparent shadow-lg shadow-yellow-500/30' : 'bg-white text-slate-600 border-slate-200 hover:border-yellow-400' }}">
                           {{ $lvl }}
                        </a>
                    @endforeach
                </div>

                <!-- Search Input -->
                <div class="relative w-full md:w-80 group">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari prestasi..." 
                           class="w-full pl-12 pr-4 py-2.5 rounded-full border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:border-yellow-500 focus:ring-4 focus:ring-yellow-500/10 text-sm font-medium transition-all shadow-inner">
                    <i class="ph-bold ph-magnifying-glass absolute left-4 top-3 text-slate-400 group-focus-within:text-yellow-500 transition-colors"></i>
                    @if(request('level'))
                        <input type="hidden" name="level" value="{{ request('level') }}">
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <main class="flex-grow py-12 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($achievements as $index => $prestasi)
                    <div class="animate-enter group bg-white rounded-[1.5rem] border border-slate-100 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07)] hover:shadow-[0_20px_40px_-15px_rgba(0,0,0,0.1)] hover:-translate-y-2 transition-all duration-500 relative overflow-hidden flex flex-col h-full"
                         style="animation-delay: {{ $index * 100 }}ms;">
                        
                        <!-- Watermark Icon -->
                        <i class="ph-duotone ph-trophy absolute -right-4 -bottom-4 text-9xl text-slate-50 transform rotate-12 group-hover:rotate-0 group-hover:scale-110 transition-transform duration-700 z-0"></i>

                        <!-- Image Area -->
                        <div class="h-56 w-full bg-slate-100 relative overflow-hidden z-10">
                            <!-- Shine Effect Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-tr from-white/0 via-white/30 to-white/0 z-20 w-1/2 h-full -skew-x-12 animate-shine opacity-0 group-hover:opacity-100"></div>

                            @if(!empty($prestasi->photo_path))
                                <img src="{{ asset('storage/' . $prestasi->photo_path) }}" 
                                     alt="{{ $prestasi->title }}" 
                                     loading="lazy"
                                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                >
                                <div class="hidden w-full h-full items-center justify-center bg-gradient-to-br from-yellow-400 to-amber-500 text-white">
                                    <i class="ph-duotone ph-trophy text-5xl animate-bounce"></i>
                                </div>
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-yellow-400 to-amber-500 text-white group-hover:scale-105 transition-transform duration-700">
                                    <i class="ph-duotone ph-trophy text-6xl drop-shadow-md"></i>
                                </div>
                            @endif
                            
                            <!-- Level Badge -->
                            <div class="absolute top-4 right-4 z-20">
                                 <span class="px-3 py-1 rounded-full bg-white/95 backdrop-blur-md border border-white/30 text-[10px] font-black uppercase text-yellow-700 tracking-wide shadow-lg flex items-center gap-1">
                                    <i class="ph-fill ph-star"></i> {{ $prestasi->level ?? 'Sekolah' }}
                                 </span>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6 flex-1 flex flex-col relative z-10">
                             <div class="text-[11px] font-bold text-slate-400 mb-3 flex items-center gap-1.5 uppercase tracking-wide">
                                <i class="ph-duotone ph-calendar-blank text-lg text-yellow-500"></i> 
                                {{ isset($prestasi->date) ? \Carbon\Carbon::parse($prestasi->date)->format('d M Y') : '-' }}
                             </div>
                            <h4 class="text-lg font-black text-slate-900 mb-3 leading-snug group-hover:text-amber-600 transition-colors line-clamp-2">
                                {{ $prestasi->title ?? 'Juara Lomba' }}
                            </h4>
                            <p class="text-sm text-slate-500 line-clamp-2 mb-6 font-medium">{{ $prestasi->description }}</p>

                            <div class="mt-auto pt-4 border-t border-slate-50 flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 text-sm shrink-0">
                                    <i class="ph-bold ph-student"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold text-slate-800 line-clamp-1 group-hover:text-indigo-600 transition-colors" title="{{ $prestasi->achiever_name }}">{{ $prestasi->achiever_name ?? 'Siswa' }}</p>
                                    <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">{{ $prestasi->type ?? 'Siswa' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-24 text-center animate-enter">
                        <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-slate-100 mb-6 text-slate-300 ring-8 ring-slate-50">
                            <i class="ph-duotone ph-trophy-slash text-5xl"></i>
                        </div>
                        <h3 class="text-2xl font-black text-slate-800 mb-2">Belum Ada Prestasi</h3>
                        <p class="text-slate-500 font-medium max-w-md mx-auto">
                            Data prestasi belum tersedia untuk kategori atau pencarian ini.
                        </p>
                        <a href="{{ route('public.achievements') }}" class="inline-flex items-center gap-2 mt-6 px-6 py-2.5 rounded-full bg-slate-800 text-white font-bold text-sm hover:bg-slate-700 hover:-translate-y-1 transition-all">
                            <i class="ph-bold ph-arrow-counter-clockwise"></i> Reset Filter
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-16">
                {{ $achievements->withQueryString()->links() }}
            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="bg-white border-t border-slate-200 py-10 mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center">
             <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-12 w-auto mx-auto mb-4 opacity-80 grayscale hover:grayscale-0 transition-all">
            <p class="text-slate-500 text-sm font-medium">&copy; {{ date('Y') }} SMP Negeri 3 Lakbok. Unggul & Berkarakter.</p>
        </div>
    </footer>

</body>
</html>