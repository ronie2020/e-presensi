<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Galeri Kegiatan - {{ config('app.name', 'SMP Negeri 3 Lakbok') }}</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Shared Animations */
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
        
        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-15px); } }
        .animate-float { animation: float 6s ease-in-out infinite; }
        
        @keyframes pulse-ring { 0% { transform: scale(0.8); box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.7); } 70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(255, 255, 255, 0); } 100% { transform: scale(0.8); box-shadow: 0 0 0 0 rgba(255, 255, 255, 0); } }
        .animate-pulse-ring { animation: pulse-ring 2s infinite; }
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

    <!-- HEADER SECTION -->
    <div class="relative overflow-hidden bg-slate-900 py-20 sm:py-24 group">
        <!-- Background -->
        <div class="absolute inset-0 bg-gradient-to-br from-blue-900 via-indigo-900 to-slate-900"></div>
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-20"></div>
        
        <!-- Blobs -->
        <div class="absolute -top-20 -right-20 w-96 h-96 bg-blue-500/20 rounded-full blur-[80px] animate-float"></div>
        <div class="absolute bottom-0 left-20 w-64 h-64 bg-purple-500/20 rounded-full blur-[60px] animate-float" style="animation-delay: 1.5s;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <div class="animate-enter inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-200 text-xs font-bold uppercase tracking-widest mb-6 backdrop-blur-sm">
                <i class="ph-fill ph-camera"></i> Dokumentasi Sekolah
            </div>
            <h1 class="animate-enter text-4xl md:text-6xl font-black text-white mb-6 tracking-tight" style="animation-delay: 100ms;">
                Galeri Kegiatan
            </h1>
            <p class="animate-enter text-blue-100/80 text-lg max-w-2xl mx-auto font-medium leading-relaxed" style="animation-delay: 200ms;">
                Kumpulan momen berharga, aktivitas akademik, dan keceriaan siswa dalam lingkungan belajar SMP Negeri 3 Lakbok.
            </p>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <main class="flex-grow py-16 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($activities as $index => $activity)
                    <div class="animate-enter group bg-white rounded-[2rem] overflow-hidden border border-slate-100 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07)] hover:shadow-2xl hover:shadow-blue-500/10 hover:-translate-y-2 transition-all duration-500 flex flex-col h-full"
                         style="animation-delay: {{ $index * 100 }}ms;">
                        
                        <!-- Image Wrapper -->
                        <div class="relative h-64 overflow-hidden bg-slate-200">
                            @if($activity->image_path)
                                <img src="{{ asset('storage/' . $activity->image_path) }}" 
                                     alt="{{ $activity->title }}" 
                                     loading="lazy"
                                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                >
                                <div class="w-full h-full flex items-center justify-center text-slate-400 hidden bg-slate-100">
                                    <i class="ph-duotone ph-image-broken text-4xl"></i>
                                </div>
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-300 bg-slate-100">
                                    <i class="ph-duotone ph-image text-5xl"></i>
                                </div>
                            @endif

                            <!-- Overlay Gradient -->
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent opacity-60 group-hover:opacity-80 transition-opacity"></div>

                            <!-- Date Badge (Top Left) -->
                            <div class="absolute top-5 left-5">
                                <span class="bg-white/95 backdrop-blur-md text-slate-800 text-[10px] font-black uppercase tracking-wider px-3 py-1.5 rounded-full shadow-lg flex items-center gap-1.5">
                                    <i class="ph-bold ph-calendar-blank text-blue-600"></i> 
                                    {{ $activity->created_at->format('d M Y') }}
                                </span>
                            </div>

                            <!-- Video Play Button (Center) -->
                            @if($activity->video_url)
                                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                    <div class="w-16 h-16 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center text-white border border-white/50 group-hover:scale-110 transition-transform animate-pulse-ring">
                                        <i class="ph-fill ph-play text-2xl ml-1"></i>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="p-8 flex-1 flex flex-col relative">
                            <!-- Category/Tag (Optional Placeholder) -->
                            <div class="mb-3">
                                <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded-md uppercase tracking-wider">
                                    {{ $activity->video_url ? 'Video Dokumentasi' : 'Foto Galeri' }}
                                </span>
                            </div>

                            <h3 class="text-xl font-black text-slate-900 mb-3 group-hover:text-blue-600 transition-colors leading-tight line-clamp-2">
                                {{ $activity->title }}
                            </h3>
                            <p class="text-sm text-slate-500 leading-relaxed line-clamp-3 mb-6 font-medium">
                                {{ $activity->description }}
                            </p>

                            @if($activity->video_url)
                                <div class="mt-auto pt-5 border-t border-slate-50">
                                    <a href="{{ $activity->video_url }}" target="_blank" class="flex items-center justify-center w-full px-5 py-3 bg-red-50 text-red-600 rounded-xl text-sm font-bold hover:bg-red-600 hover:text-white transition-all duration-300 group/btn shadow-sm hover:shadow-red-500/20">
                                        <i class="ph-fill ph-youtube-logo text-xl mr-2 group-hover/btn:scale-110 transition-transform"></i> 
                                        Tonton Video
                                    </a>
                                </div>
                            @else
                                <div class="mt-auto pt-5 border-t border-slate-50 flex items-center text-slate-400 text-xs font-bold gap-1 group-hover:text-blue-600 transition-colors">
                                    <i class="ph-bold ph-images"></i> Lihat Detail Foto
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-24 text-center animate-enter">
                        <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-slate-100 mb-6 text-slate-300 ring-8 ring-slate-50">
                            <i class="ph-duotone ph-aperture text-5xl"></i>
                        </div>
                        <h3 class="text-2xl font-black text-slate-800 mb-2">Belum Ada Dokumentasi</h3>
                        <p class="text-slate-500 font-medium max-w-md mx-auto">
                            Galeri kegiatan sekolah belum tersedia saat ini. Kunjungi lagi nanti!
                        </p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-16">
                {{ $activities->links() }}
            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="bg-white border-t border-slate-200 py-10 mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-slate-500 text-sm font-medium">&copy; {{ date('Y') }} SMP Negeri 3 Lakbok. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>