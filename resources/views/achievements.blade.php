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
</head>
<body class="antialiased text-slate-800 bg-slate-50 flex flex-col min-h-screen">

    <!-- NAVBAR -->
    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <a href="{{ url('/') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-10 h-10 object-contain">
                    <span class="text-lg font-extrabold text-slate-900 tracking-tight hidden sm:block">SMPN 3 LAKBOK</span>
                </a>
                <a href="{{ url('/') }}" class="text-sm font-bold text-slate-600 hover:text-blue-600 transition flex items-center gap-2">
                    <i class="ph-bold ph-arrow-left"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </nav>

    <!-- HEADER SECTION -->
    <div class="bg-gradient-to-r from-yellow-500 to-amber-600 py-16 relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/20 border border-white/30 text-white text-xs font-bold uppercase tracking-wider mb-4">
                <i class="ph-fill ph-trophy"></i> Hall of Fame
            </div>
            <h1 class="text-3xl md:text-5xl font-extrabold text-white mb-4">Arsip Prestasi Sekolah</h1>
            <p class="text-yellow-50 text-lg max-w-2xl mx-auto">Rekam jejak kebanggaan siswa dan guru SMP Negeri 3 Lakbok di berbagai tingkat kompetisi.</p>
        </div>
    </div>

    <!-- FILTER SECTION -->
    <div class="bg-white border-b border-slate-200 sticky top-20 z-40 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <form action="{{ route('public.achievements') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-center justify-between">
                <!-- Filter Level -->
                <div class="flex items-center gap-2 w-full md:w-auto overflow-x-auto pb-2 md:pb-0 no-scrollbar">
                    <a href="{{ route('public.achievements') }}" 
                       class="whitespace-nowrap px-4 py-2 rounded-full text-sm font-bold border transition {{ !request('level') ? 'bg-yellow-500 text-white border-yellow-600' : 'bg-white text-slate-600 border-slate-200 hover:border-yellow-400' }}">
                       Semua
                    </a>
                    @foreach($levels as $lvl)
                        <a href="{{ route('public.achievements', array_merge(request()->query(), ['level' => $lvl])) }}" 
                           class="whitespace-nowrap px-4 py-2 rounded-full text-sm font-bold border transition {{ request('level') == $lvl ? 'bg-yellow-500 text-white border-yellow-600' : 'bg-white text-slate-600 border-slate-200 hover:border-yellow-400' }}">
                           {{ $lvl }}
                        </a>
                    @endforeach
                </div>

                <!-- Search -->
                <div class="relative w-full md:w-72">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari prestasi..." class="w-full pl-10 pr-4 py-2 rounded-full border border-slate-300 focus:outline-none focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500 text-sm">
                    <i class="ph-bold ph-magnifying-glass absolute left-3 top-2.5 text-slate-400"></i>
                    @if(request('level'))
                        <input type="hidden" name="level" value="{{ request('level') }}">
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <main class="flex-grow py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($achievements as $prestasi)
                    <div class="group bg-white rounded-2xl border border-yellow-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] hover:shadow-xl hover:shadow-yellow-500/10 hover:-translate-y-1 transition-all duration-300 relative overflow-hidden h-full flex flex-col">
                        <!-- Image Area -->
                        <div class="h-48 w-full bg-slate-100 relative overflow-hidden group">
                            @if(!empty($prestasi->photo_path))
                                <img src="{{ asset('storage/' . $prestasi->photo_path) }}" 
                                     alt="{{ $prestasi->title }}" 
                                     loading="lazy"
                                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                >
                                <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-yellow-400 to-amber-500 text-white" style="display: none;">
                                    <i class="ph-bold ph-trophy text-4xl"></i>
                                </div>
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-yellow-400 to-amber-500 text-white">
                                    <i class="ph-bold ph-trophy text-4xl"></i>
                                </div>
                            @endif
                            
                            <!-- Level Badge -->
                            <div class="absolute top-3 right-3">
                                 <span class="px-2.5 py-1 rounded-full bg-white/90 backdrop-blur border border-white/20 text-[10px] font-bold uppercase text-yellow-700 tracking-wide shadow-sm">{{ $prestasi->level ?? 'Sekolah' }}</span>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-5 flex-1 flex flex-col relative z-10">
                             <div class="text-xs text-slate-400 font-medium mb-2 flex items-center gap-1">
                                <i class="ph-fill ph-calendar-blank"></i> {{ isset($prestasi->date) ? \Carbon\Carbon::parse($prestasi->date)->format('d M Y') : '-' }}
                             </div>
                            <h4 class="text-lg font-bold text-slate-900 mb-2 leading-tight group-hover:text-yellow-600 transition-colors line-clamp-2">
                                {{ $prestasi->title ?? 'Juara Lomba' }}
                            </h4>
                            <p class="text-sm text-slate-500 line-clamp-2 mb-4">{{ $prestasi->description }}</p>

                            <div class="mt-auto pt-4 border-t border-slate-50 flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 text-sm shrink-0">
                                    <i class="ph-bold ph-user"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold text-slate-700 line-clamp-1" title="{{ $prestasi->achiever_name }}">{{ $prestasi->achiever_name ?? 'Siswa' }}</p>
                                    <p class="text-xs text-slate-400 uppercase font-bold">{{ $prestasi->type ?? 'Siswa' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-slate-100 mb-4 text-slate-400">
                            <i class="ph-duotone ph-trophy-slash text-4xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-700">Data Tidak Ditemukan</h3>
                        <p class="text-slate-500">Belum ada data prestasi yang sesuai dengan pencarian Anda.</p>
                        <a href="{{ route('public.achievements') }}" class="inline-block mt-4 text-sm font-bold text-blue-600 hover:underline">Reset Filter</a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $achievements->withQueryString()->links() }}
            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="bg-white border-t border-slate-200 py-8 mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-slate-500 text-sm">&copy; {{ date('Y') }} SMP Negeri 3 Lakbok. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>