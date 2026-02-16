<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Website Resmi SMP Negeri 3 Lakbok. Informasi akademik, kesiswaan, dan prestasi sekolah terkini.">
    <title>{{ config('app.name', 'SMP Negeri 3 Lakbok') }}</title>
    
    {{-- Memanggil Partial Styles --}}
    @include('landing.styles')
</head>
<body class="antialiased text-slate-800 bg-slate-50 overflow-x-hidden selection:bg-blue-500 selection:text-white" 
    x-data="{ 
        mobileMenuOpen: false,
        modalOpen: false, 
        guestBookModalOpen: false,
        guestListModalOpen: false,
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
            document.body.style.overflow = 'auto';
        },

        init() {
            window.addEventListener('load', () => {
                setTimeout(() => {
                    document.getElementById('preloader').classList.add('hide-preloader');
                }, 800);
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

    <!-- PRELOADER -->
    <div id="preloader">
        <div class="flex flex-col items-center gap-4">
            <span class="loader"></span>
            <p class="text-white text-xs font-bold tracking-widest uppercase animate-pulse">Memuat Netila Berjaya...</p>
        </div>
    </div>

    <!-- NAVBAR (Fixed Z-Index Mobile) -->
    @include('landing.navbar')

    <!-- HERO SECTION -->
    @include('landing.hero')

    <!-- PPDB TRACKS -->
    @include('landing.ppdb')

    <!-- 7 HABITS (KARAKTER) -->
    @include('landing.character')

    <!-- QUICK ACCESS MENU -->
    @include('landing.quick-access')

    <!-- DOWNLOAD AREA -->
    @include('landing.downloads')

    <!-- KEPALA SEKOLAH -->
    @include('landing.headmaster')

    <!-- PROFIL SEKOLAH -->
    @include('landing.profile')

    <!-- VIDEO PROFIL -->
    @include('landing.video')

    <!-- GURU & STAFF -->
    @include('landing.teachers')

    <!-- KEGIATAN -->
    @include('landing.activities')

    <!-- PRESTASI -->
    @include('landing.achievements')

    <!-- EKSTRAKURIKULER -->
    @include('landing.extracurricular')

    <!-- ALUMNI -->
    @include('landing.alumni')

    <!-- KATA MEREKA / GUESTBOOK -->
    @include('landing.guestbook')

    <!-- LIBRARY -->
    @include('landing.library')

    <!-- E-BOOKS -->
    @include('landing.ebooks')

    <!-- ANNOUNCEMENTS, AGENDA & FOOTER -->
    @include('landing.footer')

    <!-- MODALS -->
    @include('landing.modals')

    <!-- BACK TO TOP -->
    <button 
        x-show="showBackToTop" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-10"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-10"
        @click="window.scrollTo({top: 0, behavior: 'smooth'})"
        class="fixed bottom-6 right-6 z-40 bg-blue-600 text-white p-3 rounded-full shadow-lg hover:bg-blue-700 hover:-translate-y-1 transition-all duration-300 focus:outline-none"
    >
        <i class="ph-bold ph-arrow-up text-xl"></i>
    </button>

    {{-- Memanggil Partial Scripts --}}
    @include('landing.scripts')

</body>
</html>