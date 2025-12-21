<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Galeri Kegiatan - {{ config('app.name', 'SMP Negeri 3 Lakbok') }}</title>
    
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased text-slate-800 bg-slate-50 flex flex-col min-h-screen">

    <!-- NAVBAR (Simplified) -->
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
    <div class="bg-slate-900 py-16 relative overflow-hidden">
        <div class="absolute inset-0 bg-blue-600/10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-20"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h1 class="text-3xl md:text-5xl font-extrabold text-white mb-4">Galeri Kegiatan Sekolah</h1>
            <p class="text-slate-400 text-lg max-w-2xl mx-auto">Dokumentasi aktifitas siswa dan guru dalam kegiatan akademik maupun non-akademik.</p>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <main class="flex-grow py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($activities as $activity)
                    <div class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-slate-100 flex flex-col h-full">
                        <!-- Image Wrapper -->
                        <div class="relative h-56 overflow-hidden bg-slate-100">
                            @if($activity->image_path)
                                <img src="{{ asset('storage/' . $activity->image_path) }}" 
                                     alt="{{ $activity->title }}" 
                                     loading="lazy"
                                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                >
                                <div class="w-full h-full flex items-center justify-center text-slate-400 hidden">
                                    <i class="ph-duotone ph-image-broken text-4xl"></i>
                                </div>
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-400">
                                    <i class="ph-duotone ph-image text-4xl"></i>
                                </div>
                            @endif

                            <!-- Date Badge -->
                            <div class="absolute top-4 left-4">
                                <span class="bg-white/95 backdrop-blur text-slate-800 text-xs font-bold px-3 py-1 rounded-full shadow-sm flex items-center gap-1">
                                    <i class="ph-fill ph-calendar-blank text-blue-500"></i> {{ $activity->created_at->format('d M Y') }}
                                </span>
                            </div>

                            <!-- Video Indicator -->
                            @if($activity->video_url)
                                <div class="absolute inset-0 flex items-center justify-center bg-black/20 group-hover:bg-black/40 transition-colors pointer-events-none">
                                    <div class="w-12 h-12 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-white border border-white/50 group-hover:scale-110 transition-transform">
                                        <i class="ph-fill ph-play text-2xl ml-1"></i>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="p-6 flex-1 flex flex-col">
                            <h3 class="text-lg font-bold text-slate-900 mb-3 group-hover:text-blue-600 transition-colors line-clamp-2">
                                {{ $activity->title }}
                            </h3>
                            <p class="text-sm text-slate-500 leading-relaxed line-clamp-3 mb-4 flex-1">
                                {{ $activity->description }}
                            </p>

                            @if($activity->video_url)
                                <div class="mt-auto pt-4 border-t border-slate-50">
                                    <a href="{{ $activity->video_url }}" target="_blank" class="inline-flex items-center justify-center w-full px-4 py-2 bg-red-50 text-red-600 rounded-lg text-sm font-bold hover:bg-red-600 hover:text-white transition-all group-video">
                                        <i class="ph-fill ph-youtube-logo text-lg mr-2"></i> Tonton Video
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-slate-100 mb-4 text-slate-400">
                            <i class="ph-duotone ph-image-square text-4xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-700">Belum ada kegiatan</h3>
                        <p class="text-slate-500">Dokumentasi kegiatan sekolah belum tersedia saat ini.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $activities->links() }}
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