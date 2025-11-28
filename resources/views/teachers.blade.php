<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Direktori Pengajar - {{ config('app.name', 'SMP Negeri 3 Lakbok') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body class="antialiased text-slate-800 bg-slate-50 font-[Plus_Jakarta_Sans]">

    <!-- NAVBAR (Sederhana) -->
    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="{{ route('landing') }}" class="flex items-center gap-2 group">
                    <div class="bg-blue-50 p-1.5 rounded-lg group-hover:bg-blue-100 transition">
                        <i class="ph-bold ph-arrow-left text-blue-600 text-xl"></i>
                    </div>
                    <span class="font-bold text-slate-600 group-hover:text-blue-600 transition">Kembali ke Beranda</span>
                </a>
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" class="h-8 w-auto" alt="Logo">
                    <span class="font-extrabold text-slate-800 hidden sm:inline">SMPN 3 LAKBOK</span>
                </div>
            </div>
        </div>
    </nav>

    <!-- HEADER SECTION -->
    <div class="bg-slate-900 pt-16 pb-20 relative overflow-hidden">
        <div class="absolute inset-0 bg-blue-600/10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-20"></div>
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-blue-500 rounded-full mix-blend-overlay filter blur-3xl opacity-20"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h1 class="text-3xl md:text-5xl font-extrabold text-white mb-4">Direktori Tenaga Pendidik</h1>
            <p class="text-slate-400 text-lg max-w-2xl mx-auto mb-8">
                Mengenal lebih dekat para guru dan staf profesional yang berdedikasi mendidik putra-putri bangsa di SMP Negeri 3 Lakbok.
            </p>

            <!-- FORM PENCARIAN -->
            <form action="{{ route('teachers.index') }}" method="GET" class="max-w-md mx-auto relative">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama guru atau mata pelajaran..." 
                       class="w-full pl-12 pr-4 py-3 rounded-full border-0 focus:ring-4 focus:ring-blue-500/30 shadow-xl text-sm font-medium">
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                    <i class="ph-bold ph-magnifying-glass text-lg"></i>
                </div>
                @if(request('q'))
                    <a href="{{ route('teachers.index') }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-red-500" title="Hapus Pencarian">
                        <i class="ph-bold ph-x"></i>
                    </a>
                @endif
            </form>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-20 pb-20">
        
        <!-- GRID GURU -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($teachers as $teacher)
                <div class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-slate-100 flex flex-col">
                    
                    <!-- Foto -->
                    <div class="aspect-[4/4] bg-slate-100 relative overflow-hidden">
                        @if($teacher->photo_path)
                            <img src="{{ asset('storage/' . $teacher->photo_path) }}" alt="{{ $teacher->name }}" 
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-50 text-blue-200">
                                <i class="ph-duotone ph-user text-8xl"></i>
                            </div>
                        @endif
                        
                        <!-- Overlay Kontak -->
                        <div class="absolute inset-0 bg-blue-900/80 backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center gap-3">
                            @if($teacher->phone)
                                <a href="https://wa.me/{{ $teacher->phone }}" target="_blank" class="w-10 h-10 rounded-full bg-white text-green-600 flex items-center justify-center hover:scale-110 transition shadow-lg" title="WhatsApp">
                                    <i class="ph-fill ph-whatsapp-logo text-xl"></i>
                                </a>
                            @endif
                            @if($teacher->instagram)
                                <a href="https://instagram.com/{{ $teacher->instagram }}" target="_blank" class="w-10 h-10 rounded-full bg-white text-pink-600 flex items-center justify-center hover:scale-110 transition shadow-lg" title="Instagram">
                                    <i class="ph-fill ph-instagram-logo text-xl"></i>
                                </a>
                            @endif
                            @if($teacher->email)
                                <a href="mailto:{{ $teacher->email }}" class="w-10 h-10 rounded-full bg-white text-blue-600 flex items-center justify-center hover:scale-110 transition shadow-lg" title="Email">
                                    <i class="ph-fill ph-envelope-simple text-xl"></i>
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Info -->
                    <div class="p-5 text-center flex-1 flex flex-col">
                        <div class="mb-3">
                            <span class="inline-block px-2 py-1 bg-blue-50 text-blue-700 text-[10px] font-bold uppercase tracking-wider rounded border border-blue-100">
                                {{ $teacher->position ?? $teacher->role }}
                            </span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 leading-tight mb-1">{{ $teacher->name }}</h3>
                        @if($teacher->nip)
                            <p class="text-xs text-slate-400 font-mono mb-3">NIP. {{ $teacher->nip }}</p>
                        @endif
                        
                        @if($teacher->bio)
                            <p class="text-xs text-slate-500 italic mt-auto border-t border-slate-50 pt-3 line-clamp-2">
                                "{{ $teacher->bio }}"
                            </p>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-4 py-20 text-center">
                    <div class="inline-flex bg-slate-100 p-4 rounded-full mb-4 text-slate-400">
                        <i class="ph-duotone ph-magnifying-glass text-4xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-700">Data Tidak Ditemukan</h3>
                    <p class="text-slate-500 text-sm">Coba kata kunci lain atau belum ada data guru.</p>
                    @if(request('q'))
                        <a href="{{ route('teachers.index') }}" class="inline-block mt-4 text-blue-600 font-bold text-sm hover:underline">Reset Pencarian</a>
                    @endif
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-12">
            {{ $teachers->withQueryString()->links() }}
        </div>

    </div>

    <!-- FOOTER -->
    <footer class="bg-white border-t border-slate-200 py-8 mt-auto text-center">
        <p class="text-slate-500 text-sm">&copy; {{ date('Y') }} SMP Negeri 3 Lakbok. Semua Hak Dilindungi.</p>
    </footer>

</body>
</html>