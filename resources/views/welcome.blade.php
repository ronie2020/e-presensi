<!DOCTYPE html>
<!-- Kunci lebar maksimal tepat pada 100% viewport (layar) -->
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <script>
        // Terapkan tema gelap sebelum halaman digambar, agar tidak "kedip" putih dulu baru gelap
        // (logika ini harus sama persis dengan yang dipakai di navbar/scripts)
        try {
            if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        } catch (e) { /* localStorage diblokir (mode privat/kuki dimatikan): pakai tampilan bawaan */ }
    </script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#021124">
    <link rel="icon" type="image/png" href="{{ asset('images/logo-sekolah.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-sekolah.png') }}">

    <meta name="description" content="Website Resmi SMP Negeri 3 Lakbok. Informasi akademik, kesiswaan, dan prestasi sekolah terkini.">
    <meta property="og:title" content="SMP Negeri 3 Lakbok - Website Resmi Netila Berjaya">
    <meta property="og:description" content="Website Resmi SMP Negeri 3 Lakbok. Informasi akademik, kesiswaan, dan prestasi sekolah terkini.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('images/netila.jpg') }}">
    <meta property="og:site_name" content="SMP Negeri 3 Lakbok">
    <meta property="og:locale" content="id_ID">
    <link rel="canonical" href="{{ url('/') }}">
    {{-- Latar hero dimuat lewat CSS inline sehingga tidak terdeteksi lebih awal; preload mempercepat tampil pertama --}}
    <link rel="preload" as="image" href="{{ asset('images/netila.jpg') }}" fetchpriority="high">
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'School',
            'name' => 'SMP Negeri 3 Lakbok',
            'alternateName' => 'Netila Berjaya',
            'url' => url('/'),
            'logo' => asset('images/logo-sekolah.png'),
            'image' => asset('images/netila.jpg'),
            'address' => ['@type' => 'PostalAddress', 'addressLocality' => 'Lakbok, Ciamis', 'addressRegion' => 'Jawa Barat', 'addressCountry' => 'ID'],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}
    </script>
    <noscript><style>#preloader { display: none !important; }</style></noscript>

    <title>SMP Negeri 3 Lakbok - Website Resmi Netila Berjaya</title>

    @include('landing.styles')

    <style>
        [x-cloak] { display: none !important; }
        /* Preloader: definisi ada di landing/styles.blade.php */

        /* Perbaikan styling list html pada Pop-Up agar rapi saat diloloskan strip_tags */
        .prose ul { list-style-type: disc; padding-left: 1.5rem; margin-top: 0.5rem; margin-bottom: 0.5rem; }
        .prose ol { list-style-type: decimal; padding-left: 1.5rem; margin-top: 0.5rem; margin-bottom: 0.5rem; }
        .prose p, .prose div { margin-bottom: 0.75rem; }
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
            // Konten popup berasal dari editor rich text. strip_tags() saja TIDAK membuang atribut berbahaya
            // (onmouseover, dll.), jadi dibersihkan dulu sebelum dicetak dengan {!! !!}.
            // Tag <h1> sengaja tidak diizinkan (halaman sudah punya <h1> di hero).
            $popupRaw     = (string) $popupAnnouncement->content;
            $popupAllowed = '<div><p><br><strong><b><em><i><u><s><ul><ol><li><h2><h3><h4><h5><h6><blockquote><span>';
            if (class_exists(\Mews\Purifier\Facades\Purifier::class)) {
                // Disarankan: composer require mews/purifier
                $popupMessage = \Mews\Purifier\Facades\Purifier::clean($popupRaw, [
                    'HTML.Allowed' => 'div[class],p[class],br,strong,b,em,i,u,s,ul,ol,li,h2,h3,h4,h5,h6,blockquote,span[class]',
                ]);
            } else {
                // Cadangan tanpa paket: buang SEMUA atribut, sisakan hanya class perataan Quill (ql-align-*).
                $popupMessage = preg_replace_callback('/<([a-z0-9]+)\b[^>]*>/i', function ($m) {
                    $keep = [];
                    if (preg_match('/class\s*=\s*["\']([^"\']*)["\']/i', $m[0], $c)) {
                        $keep = array_filter(preg_split('/\s+/', trim($c[1])), fn ($x) => preg_match('/^ql-align-(left|center|right|justify)$/', $x));
                    }
                    return '<' . strtolower($m[1]) . ($keep ? ' class="' . implode(' ', $keep) . '"' : '') . '>';
                }, strip_tags($popupRaw, $popupAllowed));
            }
        }

        $popupCategory = $hasPopup ? ($popupAnnouncement->category ?? 'Umum') : 'Umum';
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
        activeAnnouncement: null,
        scrolled: window.scrollY > 20,
        showBackToTop: window.scrollY > 500,
        activeSection: 'home',

        safeGet(store, key) { try { return store.getItem(key); } catch (e) { return null; } },
        safeSet(store, key, value) { try { store.setItem(key, value); } catch (e) {} },

        initPopup() {
        @if($hasPopup)
            const id = '{{ $popupId }}';
            if (this.safeGet(localStorage, 'seen_' + id) || this.safeGet(sessionStorage, 'seen_' + id)) return;
            setTimeout(() => { this.infoPopupOpen = true; }, 1500);
        @endif
        },

        closeInfoPopup(dontShowAgain = false) {
            this.infoPopupOpen = false;
        @if($hasPopup)
            // 'Saya mengerti' = tidak muncul lagi selama sesi ini; 'Jangan tampilkan lagi' = permanen di browser ini
            this.safeSet(dontShowAgain ? localStorage : sessionStorage, 'seen_{{ $popupId }}', 'true');
        @endif
        },

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
            // Preloader: hilang begitu Alpine siap (tidak menunggu semua gambar/CDN), dengan batas waktu cadangan
            const hide = () => { const p = document.getElementById('preloader'); if (p) p.classList.add('hide-preloader'); };
            requestAnimationFrame(hide);
            setTimeout(hide, 2500);

            // Popup pengumuman muncul setelah halaman selesai dimuat
            if (document.readyState === 'complete') { this.initPopup(); }
            else { window.addEventListener('load', () => this.initPopup()); }

            // Kunci scroll + fokus ke tombol tutup saat popup terbuka
            this.$watch('infoPopupOpen', open => {
                document.body.style.overflow = open ? 'hidden' : '';
                if (open) this.$nextTick(() => { if (this.$refs.popupClose) this.$refs.popupClose.focus(); });
            });

            // Penanda bagian aktif (hub navigasi + navbar): urutan mengikuti urutan di halaman
            if ('IntersectionObserver' in window) {
                const ids = ['home', 'katalog-lms', 'profil', 'jadwal-ujian', 'kegiatan', 'prestasi', 'perpustakaan', 'kontak'];
                const io = new IntersectionObserver(entries => {
                    entries.forEach(e => { if (e.isIntersecting) this.activeSection = e.target.id; });
                }, { rootMargin: '-40% 0px -55% 0px' });
                ids.forEach(id => { const el = document.getElementById(id); if (el) io.observe(el); });
            }
        }
    }"
    @scroll.window.passive="
        scrolled = window.pageYOffset > 20;
        showBackToTop = window.pageYOffset > 500;
        if (window.innerHeight + window.pageYOffset >= document.documentElement.scrollHeight - 8) activeSection = 'kontak';
    ">

<a href="#konten" class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-[10000] focus:px-4 focus:py-2 focus:rounded-lg focus:bg-white focus:text-[#021124] focus:font-bold">Lewati ke konten utama</a>

<!-- GLOBAL FIXED BACKGROUND -->
<div class="fixed inset-0 z-[-1] w-full h-full pointer-events-none bg-[#021124] overflow-hidden" aria-hidden="true">
    <div class="absolute inset-0 bg-cover bg-center opacity-70" style="background-image: url('{{ asset('images/netila.jpg') }}');"></div>
    <div class="absolute inset-0 bg-gradient-to-b from-[#021124]/75 via-[#021124]/50 to-[#021124]/80"></div>
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_rgba(13,82,161,0.2)_0%,_rgba(2,17,36,0.5)_60%,_#021124_100%)]"></div>

    {{-- Cahaya lembut: radial-gradient statis. Sebelumnya 3 elemen blur-[160px] + animate-pulse terus-menerus,
         yang membuat GPU HP menggambar ulang layar penuh tanpa henti. --}}
    <div class="absolute top-[10%] left-[10%] w-[34rem] h-[34rem] rounded-full" style="background: radial-gradient(closest-side, rgba(86,187,241,0.13), transparent);"></div>
    <div class="absolute bottom-[8%] right-[6%] w-[34rem] h-[34rem] rounded-full" style="background: radial-gradient(closest-side, rgba(13,82,161,0.20), transparent);"></div>
    <div class="hidden md:block absolute top-[60%] left-[30%] w-[28rem] h-[28rem] rounded-full" style="background: radial-gradient(closest-side, rgba(249,162,130,0.08), transparent);"></div>
</div>

<!-- PRELOADER (Elevate Navy) -->
<div id="preloader" role="status" aria-live="polite">
    <div class="flex flex-col items-center gap-4">
        <span class="loader"></span>
        <p class="text-white text-sm font-semibold">Memuat Netila Berjaya&hellip;</p>
    </div>
</div>

<!-- INFO POPUP MODAL (Elevate Style) -->
@if($hasPopup)
<div x-cloak x-show="infoPopupOpen" @keydown.escape.window="if(infoPopupOpen) closeInfoPopup(false)" class="fixed inset-0 z-[100] overflow-y-auto" role="dialog" aria-modal="true" aria-labelledby="modal-title">
    <div x-show="infoPopupOpen" x-transition.opacity class="fixed inset-0 bg-[#021124]/80 backdrop-blur-md transition-opacity" @click="closeInfoPopup(false)"></div>
    <div class="flex min-h-full p-4 sm:p-6">
        <div x-show="infoPopupOpen" x-transition class="m-auto relative transform overflow-hidden rounded-[2.5rem] bg-white/10 backdrop-blur-2xl border border-white/20 text-left shadow-[0_15px_50px_rgba(0,0,0,0.5)] transition-all w-full {{ $hasPopupImage ? 'sm:max-w-2xl' : 'sm:max-w-xl' }}">
            <button x-ref="popupClose" type="button" aria-label="Tutup pengumuman" @click="closeInfoPopup(false)" class="absolute top-4 right-4 z-20 w-10 h-10 bg-white/10 text-white/70 hover:text-rose-400 hover:bg-rose-500/20 transition-colors shadow-sm rounded-full flex items-center justify-center"><i class="ph-bold ph-x text-lg"></i></button>
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
                   <div class="prose prose-sm text-slate-300 mb-6 font-medium leading-relaxed overflow-y-auto max-h-48 pr-2 whitespace-pre-wrap">{!! $popupMessage !!}</div>
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
<main id="konten" class="w-full overflow-x-hidden relative">
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
</main>

   <!-- MODALS -->
    @include('landing.modals')

<!-- VISITOR COUNTER (hanya desktop; untuk HP tampilkan angka yang sama di footer) -->
<div class="fixed bottom-4 left-4 sm:bottom-6 sm:left-6 z-40 bg-[#021124]/80 backdrop-blur-xl border border-white/15 shadow-[0_8px_32px_rgba(0,0,0,0.4)] p-1.5 sm:px-4 sm:py-2 rounded-full hidden lg:flex items-center gap-2 sm:gap-3 hover:-translate-y-1 hover:border-elevate-accent/40 hover:shadow-[0_8px_32px_rgba(86,187,241,0.3)] transition-all duration-300 group cursor-default max-w-[calc(100vw-40px)] overflow-hidden" title="Total pengunjung website">
    <div class="bg-elevate-accent/20 text-elevate-accent border border-elevate-accent/30 p-1.5 sm:p-2 rounded-full shrink-0 group-hover:bg-elevate-accent group-hover:text-elevate-dark transition-colors duration-300">
        <i class="ph-fill ph-users text-sm sm:text-lg"></i>
    </div>
    
    <!-- Teks Detail (Hanya muncul di Desktop/Tablet) -->
    <div class="hidden sm:flex flex-col truncate">
        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-0.5">Pengunjung</span>
        <span class="text-sm font-black text-white leading-none">{{ number_format($visitorCount ?? 0, 0, ',', '.') }}</span>
    </div>
</div>

<!-- FLOATING QUICK HUB NAVIGATOR (Scroll-to-Section Pills) -->
<div x-cloak x-show="showBackToTop" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="opacity-100 translate-y-0 scale-100" x-transition:leave-end="opacity-0 translate-y-4 scale-95" role="navigation" aria-label="Pintasan bagian halaman" class="fixed bottom-4 sm:bottom-6 left-1/2 -translate-x-1/2 z-40 bg-[#021124]/90 backdrop-blur-xl border border-white/20 shadow-[0_8px_32px_rgba(0,0,0,0.5)] p-1.5 rounded-full flex items-center gap-1 max-w-[90vw] overflow-x-auto no-scrollbar">
    @php
        $hub = [
            ['katalog-lms', 'ph-books', 'LMS'],
            ['profil', 'ph-buildings', 'Profil'],
            ['jadwal-ujian', 'ph-desktop', 'CBT'],
            ['kegiatan', 'ph-calendar-check', 'Kegiatan'],
            ['prestasi', 'ph-trophy', 'Prestasi'],
            ['perpustakaan', 'ph-book-open', 'Perpustakaan'],
        ];
    @endphp
    @foreach($hub as [$hubId, $hubIcon, $hubLabel])
    <a href="#{{ $hubId }}" :class="activeSection === '{{ $hubId }}' ? 'bg-elevate-accent text-elevate-dark font-black' : 'text-slate-300 hover:text-white hover:bg-white/10 font-bold'" class="px-3 py-1.5 rounded-full text-xs transition-all flex items-center gap-1 shrink-0">
        <i class="ph-bold {{ $hubIcon }}" aria-hidden="true"></i> {{ $hubLabel }}
    </a>
    @endforeach
</div>

<!-- BACK TO TOP -->
<button x-cloak x-show="showBackToTop" x-transition @click="window.scrollTo({top: 0, behavior: 'smooth'})" aria-label="Kembali ke atas" class="fixed bottom-4 right-4 sm:bottom-6 sm:right-6 z-40 bg-gradient-to-r from-elevate-accent to-elevate-primary text-white w-12 h-12 rounded-2xl shadow-[0_0_20px_rgba(86,187,241,0.3)] flex items-center justify-center hover:shadow-[0_0_30px_rgba(86,187,241,0.5)] hover:-translate-y-1 transition-all duration-300 focus:outline-none border border-elevate-accent/40">
    <i class="ph-bold ph-arrow-up text-lg sm:text-xl"></i>
</button>

{{-- Scripts JS --}}
@include('landing.scripts')

</body>
</html>