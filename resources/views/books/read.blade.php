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

    {{-- 
        MAIN CONTAINER 
        Menggunakan h-[100dvh] (Dynamic Viewport Height) sangat penting untuk mobile 
        agar tidak tertutup address bar browser (Chrome/Safari Mobile).
    --}}
    <div class="fixed inset-0 z-50 bg-slate-900 flex flex-col h-[100dvh] w-full overflow-hidden">
        
        {{-- HEADER / TOOLBAR --}}
        {{-- Menggunakan shrink-0 agar header tidak mengecil/gepeng saat konten utama penuh --}}
        <div class="h-14 sm:h-16 bg-slate-800 border-b border-slate-700 flex items-center justify-between px-3 sm:px-6 shrink-0 z-20 shadow-lg relative">
            
            {{-- KIRI: Navigasi & Judul --}}
            <div class="flex items-center gap-3 sm:gap-4 overflow-hidden w-full mr-2">
                <a href="{{ $backUrl }}" class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-slate-700 hover:bg-slate-600 text-slate-300 hover:text-white flex items-center justify-center transition-colors border border-slate-600 shrink-0">
                    <i class="ph-bold ph-arrow-left text-lg"></i>
                </a>
                
                {{-- Judul dengan Truncate (Potong teks jika kepanjangan) --}}
                <div class="flex flex-col overflow-hidden min-w-0">
                    <h1 class="text-white font-bold text-xs sm:text-sm truncate leading-tight">{{ $book->title }}</h1>
                    <p class="text-slate-400 text-[10px] uppercase font-bold tracking-wider truncate flex items-center gap-1">
                        <span class="hidden sm:inline">Penulis:</span> {{ $book->author ?? 'Umum' }}
                    </p>
                </div>
            </div>

            {{-- KANAN: Kontrol --}}
            <div class="flex items-center gap-2 shrink-0">
                {{-- Tombol Info (Mobile Only) --}}
                <button onclick="toggleSidebar()" class="md:hidden w-9 h-9 rounded-lg text-slate-400 hover:text-white hover:bg-slate-700 transition-colors flex items-center justify-center border border-transparent hover:border-slate-600">
                    <i class="ph-bold ph-info text-xl"></i>
                </button>
                
                {{-- Tombol Fullscreen (Desktop Only) --}}
                <button onclick="toggleFullscreen()" class="w-9 h-9 rounded-lg text-slate-400 hover:text-white hover:bg-slate-700 transition-colors hidden sm:flex items-center justify-center" title="Layar Penuh">
                    <i class="ph-bold ph-corners-out text-xl"></i>
                </button>
            </div>
        </div>

        {{-- AREA KONTEN UTAMA (PDF + SIDEBAR) --}}
        {{-- Menggunakan flex-1 agar mengisi sisa ruang vertical --}}
        <div class="flex-1 flex relative bg-[#525659] overflow-hidden w-full h-full">
            
            {{-- PDF VIEWER --}}
            {{-- Menggunakan absolute inset-0 pada iframe untuk memastikan full width/height di dalam container --}}
            <div class="flex-1 h-full w-full relative">
                <iframe 
                    id="pdf-frame"
                    src="{{ asset('storage/' . $book->ebook_path) }}#toolbar=0&view=FitH" 
                    class="w-full h-full border-none absolute inset-0"
                    allowfullscreen>
                </iframe>
            </div>

            {{-- SIDEBAR INFO (RESPONSIVE OVERLAY) --}}
            {{-- Di Mobile: jadi overlay full screen (z-30). Di Desktop: jadi sidebar kanan tetap (z-auto). --}}
            <div id="book-sidebar" class="hidden md:flex w-full md:w-80 bg-slate-900/95 backdrop-blur-md md:bg-slate-900 border-l border-slate-800 flex-col absolute md:relative inset-0 md:inset-auto z-30 transition-all duration-300">
                
                {{-- Header Sidebar Mobile (Tombol Close) --}}
                <div class="md:hidden h-14 border-b border-slate-800 flex justify-between items-center px-4 bg-slate-900 shrink-0">
                    <span class="text-white font-bold text-sm">Informasi Buku</span>
                    <button onclick="toggleSidebar()" class="text-slate-400 hover:text-white p-2 bg-slate-800 rounded-lg">
                        <i class="ph-bold ph-x text-lg"></i>
                    </button>
                </div>

                {{-- Isi Sidebar --}}
                <div class="p-6 overflow-y-auto custom-scrollbar flex-1 h-full">
                    <div class="flex flex-col h-full">
                        {{-- Cover Image --}}
                        <div class="aspect-[2/3] rounded-xl overflow-hidden shadow-2xl border border-slate-700 bg-slate-800 flex items-center justify-center mb-6 shrink-0 mx-auto w-1/2 md:w-full max-w-[180px]">
                            @if($book->cover_path)
                                <img src="{{ asset('storage/' . $book->cover_path) }}" class="w-full h-full object-cover">
                            @else
                                <div class="flex flex-col items-center gap-2 text-slate-600">
                                    <i class="ph-duotone ph-book-open text-4xl"></i>
                                    <span class="text-[10px] font-bold">No Cover</span>
                                </div>
                            @endif
                        </div>

                        {{-- Metadata --}}
                        <div class="space-y-4">
                            {{-- Judul di Sidebar (hanya muncul di Mobile Overlay) --}}
                            <div class="md:hidden text-center mb-6">
                                <h2 class="text-white font-bold text-lg leading-snug">{{ $book->title }}</h2>
                                <p class="text-slate-400 text-sm mt-1">{{ $book->author }}</p>
                            </div>

                            <div class="bg-slate-800/50 p-3 rounded-xl border border-slate-700/50">
                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider mb-1">Sinopsis</p>
                                <div class="text-slate-300 text-xs leading-relaxed text-justify max-h-60 overflow-y-auto custom-scrollbar pr-1">
                                    {{ $book->description ?? 'Tidak ada sinopsis.' }}
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div class="bg-slate-800/50 p-3 rounded-xl border border-slate-700/50">
                                    <p class="text-[10px] text-slate-500 font-bold uppercase mb-1">Penerbit</p>
                                    <p class="text-white text-xs truncate">{{ $book->publisher ?? '-' }}</p>
                                </div>
                                <div class="bg-slate-800/50 p-3 rounded-xl border border-slate-700/50">
                                    <p class="text-[10px] text-slate-500 font-bold uppercase mb-1">Tahun</p>
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
            // Toggle Visibility
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