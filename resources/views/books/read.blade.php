<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    {{-- Viewport Optimization: Mencegah zoom paksa di mobile dan memastikan skala 1 --}}
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"> 
    <title>Baca: {{ $book->title }}</title>
    
    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <style>
        /* Reset & Base Layout */
        html, body { 
            height: 100%; 
            margin: 0; 
            padding: 0;
            overflow: hidden; /* Mencegah scroll pada body utama */
            background-color: #0f172a; 
        }
        
        /* Custom Scrollbar yang Minimalis */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: rgba(255,255,255,0.05); }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }
    </style>
</head>
<body class="font-sans antialiased text-slate-200">

    {{-- LOGIKA TOMBOL KEMBALI (SMART BACK BUTTON) --}}
    @php
        $backUrl = route('library.catalogue');
        if(request('origin') == 'portal' && Auth::guard('student')->check()) {
            $backUrl = route('portal.show', ['student_id' => Auth::guard('student')->id()]) . '?tab=perpustakaan';
        }
    @endphp

    {{-- MAIN CONTAINER --}}
    <div class="fixed inset-0 z-50 bg-elevate-dark flex flex-col h-[100dvh] w-full overflow-hidden">
        
        {{-- HEADER / TOOLBAR --}}
        <div class="h-14 sm:h-16 bg-elevate-dark border-b border-white/10 flex items-center justify-between px-3 sm:px-6 shrink-0 z-20 shadow-lg relative">
            
            {{-- KIRI: Navigasi & Judul --}}
            <div class="flex items-center gap-3 sm:gap-4 overflow-hidden w-full mr-2">
                <a href="{{ $backUrl }}" class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors border border-white/10 shrink-0">
                    <i class="ph-bold ph-arrow-left text-lg"></i>
                </a>
                
                {{-- Judul dengan Truncate --}}
                <div class="flex flex-col overflow-hidden min-w-0">
                    <h1 class="text-white font-bold text-xs sm:text-sm truncate leading-tight">{{ $book->title }}</h1>
                    <p class="text-white/50 text-[10px] uppercase font-bold tracking-wider truncate flex items-center gap-1">
                        <span class="hidden sm:inline">Penulis:</span> {{ $book->author ?? 'Umum' }}
                    </p>
                </div>
            </div>

            {{-- KANAN: Kontrol --}}
            <div class="flex items-center gap-2 shrink-0">
                {{-- Tombol Info (Mobile Only) --}}
                <button onclick="toggleSidebar()" class="md:hidden w-9 h-9 rounded-lg text-white/50 hover:text-white hover:bg-white/10 transition-colors flex items-center justify-center border border-transparent hover:border-white/20">
                    <i class="ph-bold ph-info text-xl"></i>
                </button>
                
                {{-- Tombol Fullscreen (Desktop Only) --}}
                <button onclick="toggleFullscreen()" class="w-9 h-9 rounded-lg text-white/50 hover:text-white hover:bg-white/10 transition-colors hidden sm:flex items-center justify-center" title="Layar Penuh">
                    <i class="ph-bold ph-corners-out text-xl"></i>
                </button>
            </div>
        </div>

        {{-- AREA KONTEN UTAMA (PDF + SIDEBAR) --}}
        <div class="flex-1 flex relative bg-[#525659] overflow-hidden w-full h-full">
            
            {{-- PDF VIEWER --}}
            <div class="flex-1 h-full w-full relative">
                <iframe 
                    id="pdf-frame"
                    src="{{ asset('storage/' . $book->ebook_path) }}#toolbar=0&view=FitH" 
                    class="w-full h-full border-none absolute inset-0"
                    allowfullscreen>
                </iframe>
            </div>

            {{-- SIDEBAR INFO (RESPONSIVE OVERLAY) --}}
            <div id="book-sidebar" class="hidden md:flex w-full md:w-80 bg-elevate-dark/95 backdrop-blur-md md:bg-elevate-dark border-l border-white/10 flex-col absolute md:relative inset-0 md:inset-auto z-30 transition-all duration-300">
                
                {{-- Header Sidebar Mobile --}}
                <div class="md:hidden h-14 border-b border-white/10 flex justify-between items-center px-4 shrink-0">
                    <span class="text-white font-bold text-sm">Informasi Buku</span>
                    <button onclick="toggleSidebar()" class="text-white/50 hover:text-white p-2 bg-white/10 rounded-lg">
                        <i class="ph-bold ph-x text-lg"></i>
                    </button>
                </div>

                {{-- Isi Sidebar --}}
                <div class="p-6 overflow-y-auto custom-scrollbar flex-1 h-full">
                    <div class="flex flex-col h-full">
                        {{-- Cover Image --}}
                        <div class="aspect-[2/3] rounded-xl overflow-hidden shadow-2xl border border-white/10 bg-white/5 flex items-center justify-center mb-6 shrink-0 mx-auto w-1/2 md:w-full max-w-[180px]">
                            @if($book->cover_path)
                                <img src="{{ asset('storage/' . $book->cover_path) }}" class="w-full h-full object-cover">
                            @else
                                <div class="flex flex-col items-center gap-2 text-white/30">
                                    <i class="ph-duotone ph-book-open text-4xl"></i>
                                    <span class="text-[10px] font-bold uppercase tracking-wider">No Cover</span>
                                </div>
                            @endif
                        </div>

                        {{-- Metadata --}}
                        <div class="space-y-4">
                            {{-- Judul di Sidebar (Mobile Overlay) --}}
                            <div class="md:hidden text-center mb-6">
                                <h2 class="text-white font-bold text-lg leading-snug">{{ $book->title }}</h2>
                                <p class="text-elevate-accent text-sm mt-1 font-bold">{{ $book->author }}</p>
                            </div>

                            <div class="bg-white/5 p-3 rounded-xl border border-white/10">
                                <p class="text-[10px] text-white/50 font-bold uppercase tracking-wider mb-1">Sinopsis</p>
                                <div class="text-white/80 text-xs leading-relaxed text-justify max-h-60 overflow-y-auto custom-scrollbar pr-1">
                                    {{ $book->description ?? 'Tidak ada sinopsis.' }}
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div class="bg-white/5 p-3 rounded-xl border border-white/10">
                                    <p class="text-[10px] text-white/50 font-bold uppercase mb-1">Penerbit</p>
                                    <p class="text-white text-xs truncate">{{ $book->publisher ?? '-' }}</p>
                                </div>
                                <div class="bg-white/5 p-3 rounded-xl border border-white/10">
                                    <p class="text-[10px] text-white/50 font-bold uppercase mb-1">Tahun</p>
                                    <p class="text-white text-xs">{{ $book->year ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(e => console.log(e));
            } else {
                if (document.exitFullscreen) document.exitFullscreen();
            }
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('book-sidebar');
            if (sidebar.classList.contains('hidden')) {
                sidebar.classList.remove('hidden');
                sidebar.classList.add('flex');
            } else {
                sidebar.classList.add('hidden');
                sidebar.classList.remove('flex');
            }
        }
    </script>
</body>
</html>