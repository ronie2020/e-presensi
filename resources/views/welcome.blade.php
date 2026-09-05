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
    <meta name="theme-color" content="#2c3f61">
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
        
        /* Style Preloader Elevate */
        #preloader {
            position: fixed; inset: 0; z-index: 9999;
            background: #2c3f61; /* elevate-dark */
            display: flex; align-items: center; justify-content: center;
            transition: opacity 0.5s ease-out, visibility 0.5s ease-out;
        }
        #preloader.hide-preloader { opacity: 0; visibility: hidden; }
        .loader {
            width: 48px; height: 48px;
            border: 5px solid #e5eff5; /* elevate-soft */
            border-bottom-color: #56bbf1; /* elevate-accent */
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
    </style>

    @php
        // Logika Pop-up Dinamis
        $hasPopup = isset($popupAnnouncement) && !empty($popupAnnouncement);

        if ($hasPopup) {
            $popupId = 'pengumuman_' .$popupAnnouncement->id;
            $popupImage = !empty($popupAnnouncement->image) ? asset('storage/' . $popupAnnouncement->image) : asset('images/logo-sekolah.png');
            $popupTitle =$popupAnnouncement->title;
            // Izinkan tag format dasar saja (bukan <a>, untuk menghindari atribut href berbahaya)
            // agar paragraf/daftar/penekanan teks tetap tampil rapi di dalam .prose, bukan jadi satu baris panjang.
            $popupMessage = strip_tags($popupAnnouncement->content, '<p><br><strong><em><b><i><ul><ol><li>');
        }

        // Tema warna default disesuaikan dengan Microsoft Elevate
        $colorTheme = [
            'badge_bg' => 'bg-elevate-accent/10', 
            'badge_text' => 'text-elevate-primary', 
            'badge_ring' => 'ring-elevate-accent/30', 
            'btn_bg' => 'bg-elevate-primary', 
            'btn_hover' => 'hover:bg-elevate-dark', 
            'btn_ring' => 'focus-visible:outline-elevate-primary'
        ];
    @endphp

</head>

<!-- TEMA ELEVATE: Background terang (slate-50), text navy (elevate-dark), tanpa dark mode class -->
<body class="antialiased text-elevate-dark bg-slate-50 overflow-x-hidden w-full selection:bg-elevate-accent selection:text-white" 
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
                    document.body.style.overflow = 'hidden'; 
                }, 1000);
            }
        @endif
    },

          closeInfoPopup(dontShowAgain) {
        this.infoPopupOpen = false;
        document.body.style.overflow = ''; 
        
        if (dontShowAgain) {
            @if($hasPopup)
                localStorage.setItem('seen_{{ $popupId }}', 'true');
            @endif
        }
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
    <div x-show="infoPopupOpen" x-transition.opacity class="fixed inset-0 bg-elevate-dark/70 backdrop-blur-sm transition-opacity" @click="closeInfoPopup(false)"></div>
    <div class="flex min-h-full p-4 sm:p-6">
        <div x-show="infoPopupOpen" x-transition class="m-auto relative transform overflow-hidden rounded-[2.5rem] bg-white text-left shadow-2xl transition-all w-full sm:max-w-2xl border border-slate-100">
            <button @click="closeInfoPopup(false)" class="absolute top-4 right-4 z-20 w-10 h-10 bg-slate-50 text-slate-400 hover:text-rose-500 hover:bg-rose-50 transition-colors shadow-sm rounded-full flex items-center justify-center"><i class="ph-bold ph-x text-lg"></i></button>
            <div class="flex flex-col md:flex-row w-full">
                <div class="md:w-5/12 h-48 sm:h-56 md:h-auto shrink-0 relative bg-slate-100 p-6 flex items-center justify-center">
                    <img src="{{ $popupImage }}" alt="{{ $popupTitle }}" class="w-full h-full object-contain drop-shadow-lg">
                </div>
                <div class="md:w-7/12 p-6 md:p-8 flex flex-col justify-center bg-white relative">
                    <div class="mb-4">
                        <span class="inline-flex items-center rounded-lg {{ $colorTheme['badge_bg'] }} px-3 py-1.5 text-[10px] font-black uppercase tracking-widest {{ $colorTheme['badge_text'] }} ring-1 ring-inset {{$colorTheme['badge_ring'] }} mb-3"><i class="ph-fill ph-megaphone mr-1.5"></i> Pengumuman</span>
                        <h3 id="modal-title" class="text-2xl font-black text-elevate-dark leading-tight">{{ $popupTitle }}</h3>
                    </div>
                   <div class="prose prose-sm text-slate-500 mb-6 font-medium leading-relaxed overflow-y-auto max-h-48 pr-2">{!! $popupMessage !!}</div>
                    <div class="flex flex-col gap-3 mt-auto">
                        <button @click="closeInfoPopup(false)" class="w-full text-center justify-center items-center rounded-xl {{ $colorTheme['btn_bg'] }} px-5 py-3.5 text-xs font-black text-white shadow-lg shadow-elevate-primary/20 {{$colorTheme['btn_hover'] }} transition-all">SAYA MENGERTI</button>
                        <button @click="closeInfoPopup(true)" class="text-xs font-bold text-slate-400 hover:text-elevate-peach transition-colors text-center py-2">Jangan tampilkan pengumuman ini lagi</button>
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
<div class="fixed bottom-4 left-4 sm:bottom-6 sm:left-6 z-40 bg-white/90 dark:bg-slate-800/90 backdrop-blur-md border border-slate-200 dark:border-slate-700 shadow-xl p-1.5 sm:px-4 sm:py-2 rounded-full flex items-center gap-2 sm:gap-3 hover:-translate-y-1 hover:shadow-2xl transition-all duration-300 group cursor-default max-w-[calc(100vw-40px)] overflow-hidden" title="Total pengunjung website">
    <div class="bg-elevate-accent/10 text-elevate-primary dark:text-elevate-accent p-1.5 sm:p-2 rounded-full shrink-0 group-hover:bg-elevate-primary group-hover:text-white transition-colors duration-300">
        <i class="ph-fill ph-users text-sm sm:text-lg"></i>
    </div>
    
    <!-- Teks Detail (Hanya muncul di Desktop/Tablet) -->
    <div class="hidden sm:flex flex-col truncate">
        <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider leading-none mb-0.5">Pengunjung</span>
        <span class="text-sm font-black text-elevate-dark dark:text-slate-200 leading-none">{{ number_format($visitorCount ?? 0, 0, ',', '.') }}</span>
    </div>
    
    <!-- Angka saja (Muncul di Mobile HP) -->
    <div class="flex sm:hidden pr-2 shrink-0">
        <span class="text-xs font-black text-elevate-dark dark:text-slate-200 leading-none">{{ number_format($visitorCount ?? 0, 0, ',', '.') }}</span>
    </div>
</div>

<!-- BACK TO TOP -->
<button x-cloak x-show="showBackToTop" x-transition @click="window.scrollTo({top: 0, behavior: 'smooth'})" aria-label="Kembali ke atas" class="fixed bottom-4 right-4 sm:bottom-6 sm:right-6 z-40 bg-elevate-dark text-white w-12 h-12 rounded-2xl shadow-xl shadow-elevate-dark/20 flex items-center justify-center hover:bg-elevate-primary hover:-translate-y-1 transition-all duration-300 focus:outline-none border border-elevate-accent/30">
    <i class="ph-bold ph-arrow-up text-lg sm:text-xl"></i>
</button>

{{-- Scripts JS --}}
@include('landing.scripts')

</body>
</html>