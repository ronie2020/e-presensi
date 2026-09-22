<!DOCTYPE html>
<!-- Kunci lebar maksimal tepat pada 100% viewport (layar) -->
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <script>
        // Terapkan tema gelap sebelum halaman digambar, agar tidak "kedip" putih dulu baru gelap
        // (logika ini harus sama persis dengan yang dipakai di navbar.blade.php)
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#021124">
    <link rel="icon" type="image/png" href="{{ asset('images/logo-sekolah.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-sekolah.png') }}">

    <meta name="description" content="Website Resmi SMP Negeri 3 Lakbok. Informasi akademik, kesiswaan, dan prestasi sekolah terkini.">
    <meta property="og:title" content="{{ config('app.name', 'SMP Negeri 3 Lakbok') }}">
    <meta property="og:description" content="Website Resmi SMP Negeri 3 Lakbok. Informasi akademik, kesiswaan, dan prestasi sekolah terkini.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('images/netila.jpg') }}"> 

    <title>{{ config('app.name', 'SMP Negeri 3 Lakbok') }}</title>

    @include('landing.styles')

    <style>
        [x-cloak] { display: none !important; }
        
        /* Style Preloader Glassmorphism */
        #preloader {
            position: fixed; inset: 0; z-index: 9999;
            background: #021124;
            display: flex; align-items: center; justify-content: center;
            transition: opacity 0.5s ease-out, visibility 0.5s ease-out;
        }
        #preloader.hide-preloader { opacity: 0; visibility: hidden; }
        .loader {
            width: 48px; height: 48px;
            border: 4px solid rgba(255, 255, 255, 0.1);
            border-bottom-color: #06b6d4;
            border-radius: 50%;
            display: inline-block;
            box-sizing: border-box;
            animation: rotation 1s linear infinite;
        }
        @keyframes rotation { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

        /* Perbaikan styling list html pada Pop-Up agar rapi saat diloloskan strip_tags */
        .prose ul { list-style-type: disc; padding-left: 1.5rem; margin-top: 0.5rem; margin-bottom: 0.5rem; }
        .prose ol { list-style-type: decimal; padding-left: 1.5rem; margin-top: 0.5rem; margin-bottom: 0.5rem; }
        .prose p { margin-bottom: 0.75rem; }
        /* Dukungan alignment Quill & HTML Rich Text */
        .ql-align-center, [align="center"] { text-align: center !important; }
        .ql-align-right, [align="right"] { text-align: right !important; }
        .ql-align-justify, [align="justify"] { text-align: justify !important; }
        .ql-align-left, [align="left"] { text-align: left !important; }
    </style>

    @php
        // Logika Pop-up Dinamis
        $hasPopup = isset($popupAnnouncement) && !empty($popupAnnouncement);

        if ($hasPopup) {
            $popupId = 'pengumuman_' . $popupAnnouncement->id;
            
            $popupImage = !empty($popupAnnouncement->image) ? asset('storage/' . $popupAnnouncement->image) : null;
            $hasPopupImage = !empty($popupImage);
            $popupTitle = $popupAnnouncement->title;
            // Izinkan tag format Rich Text (bold, miring, alinea, alignment, daftar, judul)
            $popupMessage = strip_tags($popupAnnouncement->content, '<div><p><br><strong><b><em><i><u><s><ul><ol><li><h1><h2><h3><h4><h5><h6><blockquote><span>');
        }

        $popupCategory = $hasPopup ? ($popupAnnouncement->category ?? 'Umum') : 'Umum';

        // Tema warna dinamis sesuai kategori pengumuman
        $colorTheme = match($popupCategory) {
            'Penting' => [
                'badge_bg' => 'bg-rose-500/10', 
                'badge_text' => 'text-rose-600', 
                'badge_ring' => 'ring-rose-500/30', 
                'btn_bg' => 'bg-rose-600', 
                'btn_hover' => 'hover:bg-rose-700', 
                'btn_ring' => 'focus-visible:outline-rose-600'
            ],
            'Akademik' => [
                'badge_bg' => 'bg-blue-500/10', 
                'badge_text' => 'text-blue-600', 
                'badge_ring' => 'ring-blue-500/30', 
                'btn_bg' => 'bg-blue-600', 
                'btn_hover' => 'hover:bg-blue-700', 
                'btn_ring' => 'focus-visible:outline-blue-600'
            ],
            'Kesiswaan' => [
                'badge_bg' => 'bg-emerald-500/10', 
                'badge_text' => 'text-emerald-600', 
                'badge_ring' => 'ring-emerald-500/30', 
                'btn_bg' => 'bg-emerald-600', 
                'btn_hover' => 'hover:bg-emerald-700', 
                'btn_ring' => 'focus-visible:outline-emerald-600'
            ],
            default => [
                'badge_bg' => 'bg-elevate-accent/10', 
                'badge_text' => 'text-elevate-primary', 
                'badge_ring' => 'ring-elevate-accent/30', 
                'btn_bg' => 'bg-elevate-primary', 
                'btn_hover' => 'hover:bg-elevate-dark', 
                'btn_ring' => 'focus-visible:outline-elevate-primary'
            ]
        };
    @endphp

</head>

<!-- TEMA ELEVATE PREMIUM GLASSMORPHISM: Background gelap dengan gambar sekolah fixed -->
<body class="antialiased text-white bg-[#021124] overflow-x-hidden w-full selection:bg-elevate-accent selection:text-elevate-dark" 
    x-data="{ 
        mobileMenuOpen: false,
        modalOpen: false, 
        guestBookModalOpen: false,
        guestListModalOpen: false,
        infoPopupOpen: false,
        
        initPopup() {
        @if($hasPopup)
            const popupId = '{{ $popupId }}';
            const hasSeen = localStorage.getItem('seen_' + popupId);
            
            if (!hasSeen) {
                setTimeout(() => {
                    this.infoPopupOpen = true;
                }, 1500);
            }
        @endif
    },

        closeInfoPopup(dontShowAgain = false) {
            this.infoPopupOpen = false;
            @if($hasPopup)
            if (dontShowAgain) {
                localStorage.setItem('seen_{{ $popupId }}', 'true');
            }
            @endif
        },

    activeAnnouncement: null,
    scrolled: false,
    showBackToTop: false,
    activeSection: 'home',
    
    openAnnouncementByIndex(index) {
        if (window.announcementsData && window.announcementsData[index]) {
            this.activeAnnouncement = window.announcementsData[index];
            this.modalOpen = true;
            document.body.style.overflow = 'hidden';
        }
    },
    
    closeAnnouncement() {
        this.modalOpen = false;
        setTimeout(() => { this.activeAnnouncement = null }, 300);
        document.body.style.overflow = '';
    },

    init() {
        window.addEventListener('load', () => {
            const preloader = document.getElementById('preloader');
            if(preloader) preloader.classList.add('hide-preloader');
            this.initPopup();
        });
    }
}" 
@scroll.window="
    scrolled = (window.pageYOffset > 20) ? true : false;
    showBackToTop = (window.pageYOffset > 500) ? true : false;
    const sections = ['home', 'profil', 'kegiatan', 'prestasi', 'kontak'];
    for (const section of sections) {
        const el = document.getElementById(section);
        if (el && window.scrollY >= (el.offsetTop - 150)) {
            activeSection = section;
        }
    }
">

<!-- GLOBAL FIXED BACKGROUND (Premium Glassmorphism Style) -->
<div class="fixed inset-0 z-[-1] w-full h-full pointer-events-none bg-[#021124] overflow-hidden">
    {{-- Background Image Sekolah (Terlihat jelas dan estetik, tidak terlalu gelap) --}}
    <div class="absolute inset-0 bg-cover bg-center opacity-70 filter brightness-85 contrast-110" style="background-image: url('{{ asset('images/netila.jpg') }}');"></div>
    
    {{-- Soft Navy Gradient & Vignette Overlay (Transparan seimbang agar gedung & suasana sekolah nampak jelas) --}}
    <div class="absolute inset-0 bg-gradient-to-b from-[#021124]/75 via-[#021124]/50 to-[#021124]/80"></div>
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_rgba(13,82,161,0.2)_0%,_rgba(2,17,36,0.5)_60%,_#021124_100%)]"></div>
    
    {{-- Floating Glowing Orbs for subtle ambiance (Elevate brand colors) --}}
    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-elevate-accent/10 rounded-full blur-[160px] pointer-events-none animate-pulse"></div>
    <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-elevate-primary/15 rounded-full blur-[160px] pointer-events-none animate-pulse" style="animation-delay: 2s;"></div>
    <div class="absolute top-2/3 left-1/3 w-80 h-80 bg-elevate-peach/10 rounded-full blur-[150px] pointer-events-none animate-pulse" style="animation-delay: 4s;"></div>
</div>

<!-- PRELOADER (Elevate Navy) -->
<div id="preloader">
    <div class="flex flex-col items-center gap-4">
        <span class="loader"></span>
        <p class="text-white text-xs font-bold tracking-widest uppercase animate-pulse">Memuat Netila Berjaya...</p>
    </div>
</div>

<!-- INFO POPUP MODAL (Elevate Style) -->
@if($hasPopup)
<div x-cloak x-show="infoPopupOpen" @keydown.escape.window="if(infoPopupOpen) closeInfoPopup(false)" class="fixed inset-0 z-[100] overflow-y-auto" role="dialog" aria-modal="true" aria-labelledby="modal-title">
    <div x-show="infoPopupOpen" x-transition.opacity class="fixed inset-0 bg-[#021124]/80 backdrop-blur-md transition-opacity" @click="closeInfoPopup(false)"></div>
    <div class="flex min-h-full p-4 sm:p-6">
        <div x-show="infoPopupOpen" x-transition class="m-auto relative transform overflow-hidden rounded-[2.5rem] bg-white/10 backdrop-blur-2xl border border-white/20 text-left shadow-[0_15px_50px_rgba(0,0,0,0.5)] transition-all w-full {{ $hasPopupImage ? 'sm:max-w-2xl' : 'sm:max-w-xl' }}">
            <button @click="closeInfoPopup(false)" class="absolute top-4 right-4 z-20 w-10 h-10 bg-white/10 text-white/70 hover:text-rose-400 hover:bg-rose-500/20 transition-colors shadow-sm rounded-full flex items-center justify-center"><i class="ph-bold ph-x text-lg"></i></button>
            <div class="flex flex-col {{ $hasPopupImage ? 'md:flex-row' : '' }} w-full">
                @if($hasPopupImage)
                <div class="img-container md:w-5/12 h-56 sm:h-64 md:h-auto shrink-0 relative bg-black/30 p-3 md:p-4 flex items-center justify-center overflow-hidden">
                    <img src="{{ $popupImage }}" alt="{{ $popupTitle }}" class="max-w-full max-h-full object-contain rounded-2xl drop-shadow-lg" onerror="if(this.closest('.img-container')) this.closest('.img-container').style.display='none';">
                </div>
                @endif
                <div class="{{ $hasPopupImage ? 'md:w-7/12' : 'w-full' }} p-6 md:p-8 flex flex-col justify-center bg-transparent relative">
                    <div class="mb-4">
                        <span class="inline-flex items-center rounded-lg bg-elevate-accent/20 px-3 py-1.5 text-[10px] font-black uppercase tracking-widest text-elevate-accent ring-1 ring-inset ring-elevate-accent/30 mb-3"><i class="ph-fill ph-megaphone mr-1.5"></i> {{ $popupCategory }}</span>
                        <h3 id="modal-title" class="text-2xl font-black text-white leading-tight">{{ $popupTitle }}</h3>
                    </div>
                   <div class="prose prose-sm text-slate-300 mb-6 font-medium leading-relaxed overflow-y-auto max-h-48 pr-2">{!! $popupMessage !!}</div>
                    <div class="flex flex-col gap-3 mt-auto">
                        <button @click="closeInfoPopup(false)" class="w-full text-center justify-center items-center rounded-xl bg-gradient-to-r from-elevate-accent to-elevate-primary px-5 py-3.5 text-xs font-black text-white shadow-[0_0_20px_rgba(86,187,241,0.3)] hover:shadow-[0_0_30px_rgba(86,187,241,0.5)] transition-all">SAYA MENGERTI</button>
                        <button @click="closeInfoPopup(true)" class="text-xs font-bold text-slate-400 hover:text-elevate-accent transition-colors text-center py-2">Jangan tampilkan pengumuman ini lagi</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- NAVBAR -->
@include('landing.navbar')

<!-- KONTEN UTAMA -->
<div class="w-full overflow-x-hidden relative">
    @include('landing.hero')
    @include('landing.lms-catalog')
    @include('landing.ppdb')
    @include('landing.character')
    @include('landing.quick-access')
    @include('landing.downloads')
    @include('landing.headmaster')
    @include('landing.profile')
    @include('landing.video')
    @include('landing.teachers')
    @include('landing.exams')
    @include('landing.activities')
    @include('landing.schedule')
    @include('landing.articles')
    @include('landing.achievements')
    @include('landing.extracurricular')
    @include('landing.alumni')
    @include('landing.guestbook')
    @include('landing.library')
    @include('landing.ebooks')
    @include('landing.footer')
</div>

   <!-- MODALS -->
    @include('landing.modals')

<!-- VISITOR COUNTER -->
<div class="fixed bottom-4 left-4 sm:bottom-6 sm:left-6 z-40 bg-[#021124]/80 backdrop-blur-xl border border-white/15 shadow-[0_8px_32px_rgba(0,0,0,0.4)] p-1.5 sm:px-4 sm:py-2 rounded-full flex items-center gap-2 sm:gap-3 hover:-translate-y-1 hover:border-elevate-accent/40 hover:shadow-[0_8px_32px_rgba(86,187,241,0.3)] transition-all duration-300 group cursor-default max-w-[calc(100vw-40px)] overflow-hidden" title="Total pengunjung website">
    <div class="bg-elevate-accent/20 text-elevate-accent border border-elevate-accent/30 p-1.5 sm:p-2 rounded-full shrink-0 group-hover:bg-elevate-accent group-hover:text-elevate-dark transition-colors duration-300">
        <i class="ph-fill ph-users text-sm sm:text-lg"></i>
    </div>
    
    <!-- Teks Detail (Hanya muncul di Desktop/Tablet) -->
    <div class="hidden sm:flex flex-col truncate">
        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-0.5">Pengunjung</span>
        <span class="text-sm font-black text-white leading-none">{{ number_format($visitorCount ?? 0, 0, ',', '.') }}</span>
    </div>
    
    <!-- Angka saja (Muncul di Mobile HP) -->
    <div class="flex sm:hidden pr-2 shrink-0">
        <span class="text-xs font-black text-white leading-none">{{ number_format($visitorCount ?? 0, 0, ',', '.') }}</span>
    </div>
</div>

<!-- BACK TO TOP -->
<button x-cloak x-show="showBackToTop" x-transition @click="window.scrollTo({top: 0, behavior: 'smooth'})" aria-label="Kembali ke atas" class="fixed bottom-4 right-4 sm:bottom-6 sm:right-6 z-40 bg-gradient-to-r from-elevate-accent to-elevate-primary text-white w-12 h-12 rounded-2xl shadow-[0_0_20px_rgba(86,187,241,0.3)] flex items-center justify-center hover:shadow-[0_0_30px_rgba(86,187,241,0.5)] hover:-translate-y-1 transition-all duration-300 focus:outline-none border border-elevate-accent/40">
    <i class="ph-bold ph-arrow-up text-lg sm:text-xl"></i>
</button>

{{-- Scripts JS --}}
@include('landing.scripts')

</body>
</html>