<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- SEO & Meta Tags -->
    <meta name="description" content="Daftar lengkap tenaga pendidik dan guru profesional di SMP Negeri 3 Lakbok.">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Direktori Pengajar - {{ config('app.name', 'SMP Negeri 3 Lakbok') }}">
    <meta property="og:description" content="Mengenal lebih dekat para guru dan staf profesional di SMP Negeri 3 Lakbok.">
    <meta property="og:image" content="{{ asset('images/logo.png') }}">

    <title>Direktori Pengajar - {{ config('app.name', 'SMP Negeri 3 Lakbok') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
    
    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <!-- Animation Library (AOS) - Agar senada dengan Welcome Page -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <style>
        /* Menambahkan animasi Blob agar sama dengan Welcome Page */
        .animate-blob { animation: blob 7s infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
    </style>
</head>
<body class="antialiased text-slate-800 bg-slate-50 font-[Plus_Jakarta_Sans] flex flex-col min-h-screen">

    <!-- NAVBAR (Konsisten dengan Dashboard/Inner Page) -->
    <nav class="bg-white/90 backdrop-blur-md border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center"> <!-- Tinggi disamakan h-20 -->
                <a href="{{ route('landing') }}" class="flex items-center gap-2 group">
                    <div class="bg-blue-50 p-2 rounded-xl group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
                        <i class="ph-bold ph-arrow-left text-blue-600 group-hover:text-white text-xl"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-bold text-slate-700 group-hover:text-blue-600 transition text-sm">Kembali</span>
                        <span class="text-[10px] text-slate-400 font-medium hidden sm:block">Halaman Utama</span>
                    </div>
                </a>
                
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" class="h-10 w-auto" alt="Logo">
                    <div class="flex flex-col text-right hidden sm:flex">
                        <span class="font-extrabold text-slate-900 text-sm leading-none">SMPN 3 LAKBOK</span>
                        <span class="text-[10px] text-blue-600 font-bold tracking-wider">UNGGUL & BERKARAKTER</span>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- HEADER SECTION (Diperbarui dengan Animasi Blob) -->
    <div class="bg-slate-900 pt-16 pb-28 relative overflow-hidden">
        <!-- Background Pattern & Gradient -->
        <div class="absolute inset-0 bg-blue-600/10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-20"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-slate-900 via-slate-900/95 to-slate-900"></div>

        <!-- Animated Blobs (Elemen kunci dari Welcome Page) -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-blue-600 rounded-full mix-blend-overlay filter blur-[80px] opacity-30 animate-blob"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-purple-600 rounded-full mix-blend-overlay filter blur-[80px] opacity-30 animate-blob animation-delay-2000"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center" data-aos="fade-down" data-aos-duration="1000">
            <span class="inline-block py-1 px-3 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-300 text-xs font-bold uppercase tracking-wider mb-4 backdrop-blur-sm">
                SDM Berkualitas
            </span>
            <h1 class="text-3xl md:text-5xl font-extrabold text-white mb-4 tracking-tight">Direktori Tenaga Pendidik</h1>
            <p class="text-slate-400 text-base md:text-lg max-w-2xl mx-auto mb-10 leading-relaxed">
                Mengenal lebih dekat profil profesional guru dan staf yang berdedikasi membangun generasi masa depan di SMP Negeri 3 Lakbok.
            </p>

            <!-- FORM PENCARIAN (Modern Style) -->
            <form action="{{ route('teachers.index') }}" method="GET" class="max-w-lg mx-auto relative group">
                <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200"></div>
                <div class="relative">
                    <input type="text" 
                           name="q" 
                           value="{{ request('q') }}" 
                           {{ request('q') ? 'autofocus' : '' }}
                           placeholder="Cari nama guru atau mata pelajaran..." 
                           class="w-full pl-12 pr-12 py-4 rounded-full border-0 focus:ring-0 shadow-xl text-sm font-medium placeholder-slate-400 bg-white/95 backdrop-blur-xl text-slate-800">
                    
                    <div class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400">
                        <i class="ph-bold ph-magnifying-glass text-lg"></i>
                    </div>
                    
                    @if(request('q'))
                        <a href="{{ route('teachers.index') }}" class="absolute right-4 top-1/2 -translate-y-1/2 w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-red-100 hover:text-red-600 transition-colors" title="Hapus Pencarian">
                            <i class="ph-bold ph-x"></i>
                        </a>
                    @else
                        <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 p-2 bg-blue-600 rounded-full text-white hover:bg-blue-700 transition shadow-lg shadow-blue-500/30">
                            <i class="ph-bold ph-arrow-right"></i>
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-20 relative z-20 pb-20">
        
        <!-- GRID GURU -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 sm:gap-6">
            @forelse($teachers as $index => $teacher)
                <!-- Tambahkan data-aos untuk animasi masuk -->
                <div class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 border border-slate-100 flex flex-col h-full"
                     data-aos="fade-up" 
                     data-aos-delay="{{ ($index % 4) * 100 }}"> <!-- Delay bertingkat -->
                    
                    <!-- Foto -->
                    <div class="aspect-[4/5] sm:aspect-square bg-slate-100 relative overflow-hidden">
                        @if($teacher->photo_path)
                            <img src="{{ asset('storage/' . $teacher->photo_path) }}" 
                                 alt="{{ $teacher->name }}" 
                                 loading="lazy"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            
                            <!-- Fallback Image -->
                            <div class="hidden w-full h-full flex-col items-center justify-center bg-gradient-to-br from-blue-50 to-slate-200 text-slate-400 absolute inset-0">
                                <span class="text-4xl font-bold opacity-30">{{ substr($teacher->name, 0, 2) }}</span>
                            </div>
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-blue-50 to-slate-200 text-blue-300">
                                <span class="text-6xl sm:text-7xl font-black opacity-20 select-none uppercase group-hover:scale-110 transition-transform duration-500">
                                    {{ substr($teacher->name, 0, 1) }}
                                </span>
                            </div>
                        @endif
                        
                        <!-- Overlay Kontak (Gradient diperbaiki) -->
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/40 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-end justify-center pb-6 gap-3 translate-y-4 group-hover:translate-y-0">
                            @if($teacher->phone)
                                @php
                                    $phoneRaw = preg_replace('/[^0-9]/', '', $teacher->phone);
                                    $waLink = Str::startsWith($phoneRaw, '0') ? '62' . substr($phoneRaw, 1) : $phoneRaw;
                                @endphp
                                <a href="https://wa.me/{{ $waLink }}" target="_blank" class="w-10 h-10 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white flex items-center justify-center hover:bg-green-500 hover:border-green-500 hover:scale-110 transition shadow-lg" title="WhatsApp">
                                    <i class="ph-fill ph-whatsapp-logo text-xl"></i>
                                </a>
                            @endif
                            
                            @if($teacher->email)
                                <a href="mailto:{{ $teacher->email }}" class="w-10 h-10 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white flex items-center justify-center hover:bg-blue-500 hover:border-blue-500 hover:scale-110 transition shadow-lg" title="Email">
                                    <i class="ph-fill ph-envelope-simple text-xl"></i>
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Info -->
                    <div class="p-4 sm:p-5 text-center flex-1 flex flex-col relative bg-white">
                        <!-- Badge Posisi (Floating Style) -->
                        <div class="absolute -top-3.5 left-0 right-0 flex justify-center">
                            <span class="inline-block px-3 py-1 bg-blue-600 text-white text-[10px] font-bold uppercase tracking-wider rounded-full shadow-md border-2 border-white transform group-hover:scale-105 transition-transform">
                                {{ $teacher->position ?? $teacher->role }}
                            </span>
                        </div>

                        <div class="mt-3 mb-1">
                            <h3 class="text-sm sm:text-lg font-bold text-slate-800 leading-tight group-hover:text-blue-600 transition-colors line-clamp-1">
                                {{ $teacher->name }}
                            </h3>
                            @if($teacher->nip)
                                <p class="text-[10px] sm:text-xs text-slate-400 font-mono mt-1">
                                    NIP. {{ $teacher->nip }}
                                </p>
                            @endif
                        </div>
                        
                        @if($teacher->bio)
                            <div class="mt-auto pt-3 border-t border-slate-50">
                                <p class="text-[11px] sm:text-xs text-slate-500 italic line-clamp-2 leading-relaxed">
                                    "{{ $teacher->bio }}"
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <!-- Empty State -->
                <div class="col-span-2 lg:col-span-4 py-24 text-center" data-aos="fade-up">
                    <div class="inline-flex bg-slate-100 p-6 rounded-full mb-6 text-slate-300 ring-8 ring-slate-50">
                        <i class="ph-duotone ph-magnifying-glass text-5xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Data Tidak Ditemukan</h3>
                    <p class="text-slate-500 text-sm max-w-md mx-auto mb-6">
                        Maaf, kami tidak dapat menemukan data guru dengan kata kunci tersebut. Silakan coba kata kunci lain.
                    </p>
                    @if(request('q'))
                        <a href="{{ route('teachers.index') }}" class="inline-flex items-center justify-center px-6 py-2.5 bg-blue-600 text-white text-sm font-bold rounded-full hover:bg-blue-700 transition shadow-lg shadow-blue-500/30 gap-2">
                            <i class="ph-bold ph-arrow-counter-clockwise"></i> Reset Pencarian
                        </a>
                    @endif
                </div>
            @endforelse
        </div>

        <!-- Pagination (Styled) -->
        <div class="mt-16 px-4" data-aos="fade-up">
            {{ $teachers->withQueryString()->links() }}
        </div>

    </div>

    <!-- FOOTER (Disamakan dengan Welcome Blade agar Konsisten) -->
    <div class="bg-slate-900 text-white pt-16 pb-8 border-t border-slate-800 relative overflow-hidden mt-auto">
        <!-- Decoration same as Landing -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-blue-900/20 rounded-full blur-3xl -mr-20 -mt-20 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div class="col-span-1 md:col-span-2 pr-0 md:pr-12">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center p-1">
                             <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-full h-full object-contain">
                        </div>
                        <span class="text-xl font-bold text-white tracking-tight">SMPN 3 LAKBOK</span>
                    </div>
                    <p class="text-slate-400 text-sm leading-relaxed mb-8">
                        Lembaga pendidikan yang berdedikasi untuk mencetak generasi berprestasi, berkarakter mulia, dan peduli lingkungan.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 hover:text-white hover:bg-blue-600 transition-all duration-300"><i class="ph-fill ph-facebook-logo text-xl"></i></a>
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 hover:text-white hover:bg-pink-600 transition-all duration-300"><i class="ph-fill ph-instagram-logo text-xl"></i></a>
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 hover:text-white hover:bg-red-600 transition-all duration-300"><i class="ph-fill ph-youtube-logo text-xl"></i></a>
                    </div>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-6 text-lg">Menu Utama</h4>
                    <ul class="space-y-3 text-sm text-slate-400">
                        <li><a href="{{ route('landing') }}#profil" class="hover:text-blue-400 transition-colors flex items-center gap-2"><i class="ph-bold ph-caret-right text-xs"></i> Profil Sekolah</a></li>
                        <li><a href="{{ route('teachers.index') }}" class="text-blue-400 font-bold flex items-center gap-2"><i class="ph-bold ph-caret-right text-xs"></i> Tenaga Pendidik</a></li>
                        <li><a href="{{ route('landing') }}#kegiatan" class="hover:text-blue-400 transition-colors flex items-center gap-2"><i class="ph-bold ph-caret-right text-xs"></i> Galeri Kegiatan</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-blue-400 transition-colors flex items-center gap-2"><i class="ph-bold ph-caret-right text-xs"></i> Login Staff</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-6 text-lg">Hubungi Kami</h4>
                    <ul class="space-y-4 text-sm text-slate-400">
                        <li class="flex items-start gap-3">
                            <i class="ph-fill ph-map-pin mt-1 text-blue-500 shrink-0"></i>
                            <span class="leading-relaxed">Jl. Mekarjaya No.199 Sidaharja Kec. Lakbok, Kab. Ciamis</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="ph-fill ph-phone text-blue-500 shrink-0"></i>
                            <span>(0265) 1234567</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- COPYRIGHT -->
            <div class="text-center pt-8 border-t border-slate-800">
                <p class="text-slate-500 text-sm">
                    &copy; {{ date('Y') }} SMP Negeri 3 Lakbok. All rights reserved.
                </p>
            </div>
        </div>
    </div>

    <!-- Script Init AOS -->
    <script>
        AOS.init({
            once: true,
            offset: 50,
            duration: 800,
            easing: 'ease-out-cubic',
        });
    </script>

</body>
</html>