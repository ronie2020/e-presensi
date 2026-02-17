<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Website Resmi SMP Negeri 3 Lakbok. Informasi akademik, kesiswaan, dan prestasi sekolah terkini.">
    <title>{{ config('app.name', 'SMP Negeri 3 Lakbok') }}</title>
    
    {{-- CSS & Meta Tags --}}
    @include('landing.styles')

    {{-- 
        === KONFIGURASI POP-UP INFO SEKOLAH === 
        Ubah bagian ini sesuai kebutuhan (PPDB, Ramadhan, Ujian, dll)
    --}}
    @php
        $infoPopup = [
            'active' => true, // Set false untuk mematikan popup
            'id' => 'promo_ppdb_2026', // Ganti ID ini setiap ganti materi baru (agar muncul lagi di user yang sudah close)
            
            // --- CARA GANTI GAMBAR ---
            // Opsi 1 (Link Luar):
            //'image' => 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=1000&auto=format&fit=crop',
            
            // Opsi 2 (File Lokal di folder public/img):
            'image' => asset('images/netila.jpg'),
            
            'title' => 'Penerimaan Peserta Didik Baru (PPDB) 2026',
            'message' => 'Gelombang 1 telah dibuka! Segera daftarkan putra-putri Anda dan jadilah bagian dari keluarga besar SMP Negeri 3 Lakbok yang berprestasi dan berkarakter.',
            'cta_text' => 'Daftar Sekarang',
            'cta_link' => 'https://e-presensi.smpn3lakbok.sch.id/ppdb/register', // Bisa link ke section atau URL luar (google form)
            'color' => 'blue' // Pilihan warna: blue, emerald, amber, rose
        ];
    @endphp
</head>
<body class="antialiased text-slate-800 bg-slate-50 overflow-x-hidden selection:bg-blue-500 selection:text-white" 
    x-data="{ 
        mobileMenuOpen: false,
        modalOpen: false, 
        guestBookModalOpen: false,
        guestListModalOpen: false,
        
        // --- STATE POPUP INFO ---
        infoPopupOpen: false,
        
        // --- LOGIC POPUP ---
        initPopup() {
            // Cek apakah fitur aktif
            const isActive = {{ $infoPopup['active'] ? 'true' : 'false' }};
            const popupId = '{{ $infoPopup['id'] }}';
            
            if (isActive) {
                // Cek apakah user sudah pernah menutup popup ini sebelumnya
                const hasSeen = localStorage.getItem('seen_' + popupId);
                
                if (!hasSeen) {
                    // Tampilkan popup setelah 2 detik (agar loading selesai dulu)
                    setTimeout(() => {
                        this.infoPopupOpen = true;
                        document.body.style.overflow = 'hidden'; // Matikan scroll
                    }, 2000);
                }
            }
        },

        closeInfoPopup(dontShowAgain) {
            this.infoPopupOpen = false;
            document.body.style.overflow = 'auto'; // Hidupkan scroll
            
            if (dontShowAgain) {
                // Simpan di browser user agar tidak muncul lagi
                localStorage.setItem('seen_{{ $infoPopup['id'] }}', 'true');
            }
        },
        // -------------------------

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
                    const preloader = document.getElementById('preloader');
                    if(preloader) preloader.classList.add('hide-preloader');
                }, 800);
                
                // Jalankan Logic Popup
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

    <!-- PRELOADER -->
    <div id="preloader">
        <div class="flex flex-col items-center gap-4">
            <span class="loader"></span>
            <p class="text-white text-xs font-bold tracking-widest uppercase animate-pulse">Memuat Netila Berjaya...</p>
        </div>
    </div>

    <!-- 
        === INFO POPUP MODAL (DYNAMIC) === 
        Muncul sesuai konfigurasi PHP di atas
    -->
    <div x-show="infoPopupOpen" 
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 px-4 py-6 sm:px-0" 
         style="display: none;"
         role="dialog" aria-modal="true">
        
        {{-- Backdrop --}}
        <div x-show="infoPopupOpen" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm transition-opacity" 
             @click="closeInfoPopup(false)"></div>

        {{-- Modal Panel --}}
        <div x-show="infoPopupOpen" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-white/20">
            
            <div class="flex flex-col md:flex-row">
                {{-- Image Side (Mobile: Top, Desktop: Left) --}}
                <div class="md:w-5/12 h-48 md:h-auto relative bg-slate-200">
                    <img src="{{ $infoPopup['image'] }}" alt="Info Sekolah" class="absolute inset-0 w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent md:bg-gradient-to-r md:from-transparent md:to-black/10"></div>
                </div>

                {{-- Content Side --}}
                <div class="md:w-7/12 p-6 md:p-8 flex flex-col justify-center bg-white relative">
                    <!-- Close Button -->
                    <button @click="closeInfoPopup(false)" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 transition-colors bg-slate-100 rounded-full p-1">
                        <i class="ph-bold ph-x text-xl"></i>
                    </button>

                    <div class="mb-4">
                        <span class="inline-flex items-center rounded-md bg-{{ $infoPopup['color'] }}-50 px-2 py-1 text-xs font-medium text-{{ $infoPopup['color'] }}-700 ring-1 ring-inset ring-{{ $infoPopup['color'] }}-600/20 mb-3">
                            Informasi Terbaru
                        </span>
                        <h3 class="text-xl font-black text-slate-900 leading-tight">
                            {{ $infoPopup['title'] }}
                        </h3>
                    </div>
                    
                    <div class="prose prose-sm text-slate-500 mb-6 leading-relaxed">
                        <p>{{ $infoPopup['message'] }}</p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 items-center">
                        <a href="{{ $infoPopup['cta_link'] }}" @click="closeInfoPopup(false)" class="w-full sm:w-auto text-center inline-flex justify-center items-center gap-2 rounded-xl bg-{{ $infoPopup['color'] }}-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-{{ $infoPopup['color'] }}-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-{{ $infoPopup['color'] }}-600 transition-all">
                            {{ $infoPopup['cta_text'] }}
                            <i class="ph-bold ph-arrow-right"></i>
                        </a>
                        
                        <button @click="closeInfoPopup(true)" class="text-xs font-semibold text-slate-400 hover:text-slate-600 underline decoration-slate-300 underline-offset-4 transition-colors">
                            Jangan tampilkan lagi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- NAVBAR -->
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

    {{-- Scripts JS --}}
    @include('landing.scripts')

</body>
</html>