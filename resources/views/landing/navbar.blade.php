{{--
    NAVBAR — Versi Terpadu & Teroptimasi
    - Menghilangkan redundansi scroll listener & memanfaatkan scope x-data dari <body>
    - Dropdown Layanan responsif (Katalog, PPDB, CBT, Presensi, SKL)
    - Command Palette pencarian instan klien (Filter keywords + Enter to open)
    - Sinkronisasi status autentikasi Siswa vs Guru/Admin
--}}
@php
    $menu = [
        ['id' => 'home',        'label' => 'Beranda',       'icon' => null],
        ['id' => 'katalog-lms', 'label' => 'Ruang Belajar', 'icon' => 'ph-book-open'],
        ['id' => 'profil',      'label' => 'Profil',        'icon' => null],
        ['id' => 'kegiatan',    'label' => 'Galeri',        'icon' => null],
        ['id' => 'prestasi',    'label' => 'Prestasi',      'icon' => null],
        ['id' => 'kontak',      'label' => 'Kontak',        'icon' => null],
    ];

    // Dipakai dropdown "Layanan" & pencarian cepat
    $services = [
        ['label' => 'Info PPDB',            'icon' => 'ph-student',         'url' => route('ppdb.create'),          'keywords' => 'ppdb pendaftaran siswa baru daftar penerimaan'],
        ['label' => 'Hasil Seleksi PPDB',   'icon' => 'ph-check-circle',    'url' => route('ppdb.check'),           'keywords' => 'kelulusan pengumuman hasil seleksi ppdb daftar ulang'],
        ['label' => 'Ujian Online (CBT)',   'icon' => 'ph-monitor-play',    'url' => route('student.login.cbt'),    'keywords' => 'cbt ujian asesmen tes online soal'],
        ['label' => 'Scan Presensi',        'icon' => 'ph-qr-code',         'url' => route('kiosk.show'),           'keywords' => 'presensi absen kehadiran kiosk qr barcode scan'],
        ['label' => 'Katalog Perpustakaan', 'icon' => 'ph-books',           'url' => route('library.catalogue'),    'keywords' => 'buku perpustakaan katalog pustaka ebook literasi'],
        ['label' => 'Kelulusan Kelas 9 (SKL)', 'icon' => 'ph-certificate',  'url' => route('graduation.index'),     'keywords' => 'kelulusan skl surat keterangan lulus kelas 9'],
    ];

    $searchLinks = array_merge([
        ['label' => 'Ruang Belajar (LMS)', 'icon' => 'ph-book-open',        'url' => route('student.login.learning'), 'keywords' => 'lms materi modul belajar siswa tugas'],
        ['label' => 'Portal Siswa',        'icon' => 'ph-identification-card', 'url' => route('portal.index'),        'keywords' => 'portal siswa biodata kartu pelajar'],
    ], $services);

    $activePill = 'bg-gradient-to-r from-elevate-accent to-elevate-primary text-white shadow-[0_0_15px_rgba(86,187,241,0.35)] border border-elevate-accent/30';
    $idlePill   = 'text-slate-300 hover:bg-white/10 hover:text-white';
@endphp

<!-- NAVBAR SECTION -->
<nav x-data="{
        searchOpen: false,
        layananOpen: false,
        query: '',
        links: @js($searchLinks),
        get results() {
            const q = this.query.trim().toLowerCase();
            return q ? this.links.filter(l => (l.label + ' ' + l.keywords).toLowerCase().includes(q)) : this.links;
        },
        syncScrollLock() {
            document.body.style.overflow = (this.searchOpen || mobileMenuOpen) ? 'hidden' : '';
        }
    }"
    x-init="
        $watch('searchOpen', value => {
            if (value) { query = ''; setTimeout(() => $refs.searchInput.focus(), 100); }
            syncScrollLock();
        });
        $watch(() => mobileMenuOpen, () => syncScrollLock());
    "
    @keydown.escape.window="searchOpen = false; mobileMenuOpen = false; layananOpen = false"
    :class="{ 'py-4': !scrolled, 'py-2': scrolled }"
    class="fixed top-0 w-full z-50 transition-all duration-300"
    aria-label="Navigasi utama">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Floating Pill Header -->
        <div class="relative z-[70] flex justify-between items-center transition-all duration-500"
             :class="{
                 'bg-[#021124]/90 backdrop-blur-2xl border border-elevate-accent/25 shadow-[0_12px_40px_rgba(2,17,36,0.8)] rounded-[2.5rem] px-4 md:px-6 py-2.5': scrolled,
                 'bg-[#021124]/65 backdrop-blur-xl border border-white/15 shadow-[0_8px_32px_rgba(0,0,0,0.35)] rounded-[2.5rem] px-4 md:px-6 py-3': !scrolled
             }">

            <!-- Logo Brand -->
            <a href="{{ url('/') }}" class="flex items-center gap-3 shrink-0 group" aria-label="SMPN 3 Lakbok, ke beranda">
                <div class="relative w-10 h-10 md:w-11 md:h-11 bg-elevate-accent/15 rounded-xl md:rounded-2xl flex items-center justify-center text-elevate-accent shadow-inner border border-elevate-accent/30 group-hover:scale-105 group-hover:border-elevate-accent/60 transition-all overflow-hidden">
                    <img src="{{ asset('images/logo.png') }}" alt="" width="32" height="32" class="w-7 h-7 md:w-8 md:h-8 object-contain z-10" onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
                    <i class="ph-bold ph-buildings text-xl hidden z-10" aria-hidden="true"></i>
                </div>

                <div class="flex flex-col leading-tight">
                    <span class="font-black text-white text-base md:text-lg tracking-tight group-hover:text-elevate-accent transition-colors">SMPN 3 LAKBOK</span>
                    <div class="flex items-center gap-1.5">
                        <span class="font-bold text-elevate-accent uppercase tracking-widest text-[10px]">Berjaya</span>
                        <span class="w-1 h-1 rounded-full bg-white/30 hidden sm:block"></span>
                        <span class="text-[10px] font-bold text-slate-300 tracking-wide hidden sm:block">Unggul &amp; Berkarakter</span>
                    </div>
                </div>
            </a>

            <!-- Desktop Menu -->
            <div class="hidden lg:flex items-center gap-1 bg-[#031d3d]/60 backdrop-blur-md px-1.5 py-1 rounded-full border border-white/10 shadow-inner">
                @foreach($menu as $m)
                    <a href="#{{ $m['id'] }}"
                       class="px-2.5 xl:px-3.5 py-1.5 rounded-full text-xs font-bold transition-all flex items-center gap-1.5"
                       :class="activeSection === '{{ $m['id'] }}' ? '{{ $activePill }}' : '{{ $idlePill }}'"
                       :aria-current="activeSection === '{{ $m['id'] }}' ? 'true' : null">
                        @if($m['icon'])<i class="ph-bold {{ $m['icon'] }} text-sm" aria-hidden="true"></i>@endif
                        {{ $m['label'] }}
                    </a>
                @endforeach

                <!-- Dropdown Layanan -->
                <div class="relative" @click.outside="layananOpen = false">
                    <button type="button" @click="layananOpen = !layananOpen"
                            aria-haspopup="true" :aria-expanded="layananOpen.toString()"
                            class="px-2.5 xl:px-3.5 py-1.5 rounded-full text-xs font-bold transition-all flex items-center gap-1.5 text-slate-300 hover:bg-white/10 hover:text-white">
                        Layanan <i class="ph-bold ph-caret-down text-xs transition-transform" :class="layananOpen ? 'rotate-180' : ''" aria-hidden="true"></i>
                    </button>
                    <div x-show="layananOpen" x-cloak x-transition.opacity.duration.150ms
                         class="absolute right-0 mt-3 w-64 rounded-2xl bg-[#021124]/95 backdrop-blur-2xl border border-white/15 shadow-[0_20px_50px_rgba(0,0,0,0.6)] p-2 z-50">
                        @foreach($services as $s)
                            <a href="{{ $s['url'] }}" @click="layananOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-200 hover:bg-white/10 hover:text-white transition-colors">
                                <i class="ph-bold {{ $s['icon'] }} text-lg text-elevate-accent" aria-hidden="true"></i> {{ $s['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Actions (Desktop) -->
            <div class="hidden lg:flex items-center gap-3">
                @if(Auth::guard('student')->check())
                    <a href="{{ route('students.learning.index') }}" class="px-5 py-2.5 rounded-full bg-gradient-to-r from-elevate-accent to-elevate-primary hover:from-[#67c4f4] hover:to-[#1264c2] text-white text-xs font-bold shadow-[0_0_20px_rgba(86,187,241,0.4)] transition-all flex items-center gap-2 group border border-elevate-accent/30 shrink-0">
                        <span>Dashboard Siswa</span>
                        <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform" aria-hidden="true"></i>
                    </a>
                @elseif(Auth::check())
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-full bg-emerald-600/90 hover:bg-emerald-500 text-white text-xs font-bold transition-all shadow-[0_0_20px_rgba(16,185,129,0.35)] border border-emerald-400/40 flex items-center gap-1.5 shrink-0">
                        <i class="ph-bold ph-chalkboard-teacher" aria-hidden="true"></i> Dashboard Guru
                    </a>
                @else
                    <!-- Tombol Masuk Terpadu (Siswa & Guru) -->
                    <a href="{{ route('login') }}" class="px-4 py-2 rounded-full bg-gradient-to-r from-elevate-accent to-elevate-primary hover:from-[#67c4f4] hover:to-[#1264c2] text-white text-xs font-bold transition-all shadow-[0_0_20px_rgba(86,187,241,0.35)] hover:shadow-[0_0_25px_rgba(86,187,241,0.5)] border border-elevate-accent/40 flex items-center gap-1.5 shrink-0">
                        <i class="ph-bold ph-sign-in" aria-hidden="true"></i> Masuk
                    </a>
                @endif

                <div class="h-5 w-px bg-white/20 mx-0.5" aria-hidden="true"></div>

                <!-- Tombol Cari -->
                <button type="button" @click="searchOpen = true" aria-label="Cari menu dan layanan" title="Cari menu dan layanan"
                        class="w-9 h-9 rounded-full bg-white/5 text-slate-300 hover:text-white flex items-center justify-center border border-white/15 hover:border-elevate-accent/40 hover:bg-white/10 transition-all shrink-0">
                    <i class="ph-bold ph-magnifying-glass text-base" aria-hidden="true"></i>
                </button>
            </div>

            <!-- Mobile Menu Button & Tools -->
            <div class="flex lg:hidden items-center gap-1.5">
                <button type="button" @click="searchOpen = true" aria-label="Cari menu dan layanan"
                        class="w-10 h-10 rounded-xl bg-[#021124]/80 text-slate-300 hover:text-white flex items-center justify-center border border-white/20 hover:bg-white/10">
                    <i class="ph-bold ph-magnifying-glass text-lg" aria-hidden="true"></i>
                </button>
                <button type="button" @click="mobileMenuOpen = !mobileMenuOpen" :aria-expanded="mobileMenuOpen.toString()" :aria-label="mobileMenuOpen ? 'Tutup menu navigasi' : 'Buka menu navigasi'"
                        class="w-10 h-10 rounded-xl bg-gradient-to-r from-elevate-accent to-elevate-primary text-white flex items-center justify-center shadow-[0_0_15px_rgba(86,187,241,0.4)] ml-1">
                    <i class="ph-bold text-xl" :class="mobileMenuOpen ? 'ph-x' : 'ph-list'" aria-hidden="true"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu Overlay -->
    <div x-show="mobileMenuOpen" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-x-full"
         x-transition:enter-end="opacity-100 translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-x-0"
         x-transition:leave-end="opacity-0 translate-x-full"
         class="fixed inset-0 bg-[#021124]/95 backdrop-blur-2xl z-[60] lg:hidden flex flex-col pt-28 px-6 overflow-y-auto pb-10">

        <div class="flex flex-col items-center space-y-5 text-center w-full px-4">
            <a href="{{ route('ppdb.create') }}" class="w-full py-3.5 bg-gradient-to-r from-elevate-accent to-elevate-primary rounded-2xl text-white font-black text-base shadow-xl shadow-elevate-accent/20 flex justify-center items-center border border-elevate-accent/30">
                <i class="ph-bold ph-student mr-2" aria-hidden="true"></i> Info PPDB
            </a>

            <a href="#katalog-lms" @click="mobileMenuOpen = false" class="text-xl font-black text-sky-400 hover:text-sky-300 transition-colors flex items-center justify-center gap-2">
                <i class="ph-bold ph-book-open" aria-hidden="true"></i> Ruang Belajar (LMS)
            </a>
            <a href="#profil" @click="mobileMenuOpen = false" class="text-lg font-bold text-slate-200 hover:text-elevate-accent transition-colors">Profil Sekolah</a>
            <a href="#kegiatan" @click="mobileMenuOpen = false" class="text-lg font-bold text-slate-200 hover:text-elevate-accent transition-colors">Galeri Kegiatan</a>
            <a href="#prestasi" @click="mobileMenuOpen = false" class="text-lg font-bold text-slate-200 hover:text-elevate-accent transition-colors">Prestasi</a>
            <a href="#kontak" @click="mobileMenuOpen = false" class="text-lg font-bold text-slate-200 hover:text-elevate-accent transition-colors">Kontak</a>

            <div class="w-16 h-1 rounded-full bg-white/10 my-2" aria-hidden="true"></div>

            <div class="flex flex-col gap-3 w-full mt-1">
                @if(Auth::guard('student')->check())
                    <a href="{{ route('students.learning.index') }}" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-elevate-accent to-elevate-primary text-white font-black shadow-lg shadow-elevate-primary/30 flex items-center justify-center gap-2">
                        <i class="ph-bold ph-layout" aria-hidden="true"></i> Dashboard Siswa
                    </a>
                @elseif(Auth::check())
                    <a href="{{ route('dashboard') }}" class="w-full py-3.5 rounded-xl bg-emerald-600 text-white font-black shadow-lg shadow-emerald-600/30 flex items-center justify-center gap-2">
                        <i class="ph-bold ph-chalkboard-teacher" aria-hidden="true"></i> Dashboard Guru
                    </a>
                @else
                    {{-- Pintasan Khusus Siswa: Belajar & CBT --}}
                    <div class="grid grid-cols-2 gap-2 mb-1">
                        <a href="{{ route('student.login.learning') }}" class="py-3 px-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm flex items-center justify-center gap-1.5">
                            <i class="ph-bold ph-books text-base" aria-hidden="true"></i> Ruang Belajar
                        </a>
                        <a href="{{ route('student.login.cbt') }}" class="py-3 px-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-sm flex items-center justify-center gap-1.5">
                            <i class="ph-bold ph-monitor-play text-base" aria-hidden="true"></i> Ujian CBT
                        </a>
                    </div>

                    {{-- 1 Tombol Login Terpadu (Siswa & Guru) --}}
                    <a href="{{ route('login') }}" class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-elevate-accent to-elevate-primary text-white font-black flex items-center justify-center gap-2 text-sm border border-elevate-accent/30">
                        <i class="ph-bold ph-sign-in text-lg" aria-hidden="true"></i> Masuk / Login Portal
                    </a>
                @endif

                <div class="grid grid-cols-2 gap-2">
                    <a href="{{ route('ppdb.check') }}" class="py-2.5 rounded-xl bg-white/5 text-slate-200 font-bold text-sm flex items-center justify-center gap-2 border border-white/10 hover:bg-white/10">
                        <i class="ph-bold ph-check-circle text-sky-400" aria-hidden="true"></i> Hasil PPDB
                    </a>
                    <a href="{{ route('kiosk.show') }}" class="py-2.5 rounded-xl bg-white/5 text-slate-200 font-bold text-sm flex items-center justify-center gap-2 border border-white/10 hover:bg-white/10">
                        <i class="ph-bold ph-qr-code text-emerald-400" aria-hidden="true"></i> Scan Presensi
                    </a>
                </div>
                <a href="{{ route('library.catalogue') }}" class="w-full py-2.5 rounded-xl bg-white/5 text-slate-200 font-bold text-sm flex items-center justify-center gap-2 border border-white/10 hover:bg-white/10">
                    <i class="ph-bold ph-books text-amber-400" aria-hidden="true"></i> Katalog Perpustakaan
                </a>
            </div>
        </div>
    </div>

    <!-- PENCARIAN MENU & LAYANAN (COMMAND PALETTE) -->
    <div x-show="searchOpen" x-cloak
         class="fixed inset-0 z-[80] flex items-start justify-center pt-16 sm:pt-24 px-4"
         role="dialog" aria-modal="true" aria-label="Cari menu dan layanan"
         @keydown.escape.window="searchOpen = false">

        <div class="fixed inset-0 bg-[#021124]/80 backdrop-blur-md"
             @click="searchOpen = false"
             x-show="searchOpen"
             x-transition.opacity></div>

        <div x-show="searchOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 -translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 -translate-y-4"
             class="relative bg-[#021124]/95 backdrop-blur-2xl w-full max-w-2xl rounded-[2rem] shadow-[0_20px_60px_rgba(0,0,0,0.6)] overflow-hidden border border-white/20 flex flex-col"
             @click.stop>

            <form @submit.prevent="if (results.length) window.location.href = results[0].url" class="flex items-center px-6 py-4 border-b border-white/10">
                <i class="ph-bold ph-magnifying-glass text-2xl text-elevate-accent" aria-hidden="true"></i>
                <input x-ref="searchInput" x-model="query" type="text" autocomplete="off" aria-label="Kata kunci pencarian"
                       class="w-full bg-transparent border-0 focus:ring-0 text-white px-4 text-lg sm:text-xl font-bold placeholder-slate-400 outline-none"
                       placeholder="Cari menu, misalnya ppdb, cbt, atau modul...">
                <button type="button" @click="searchOpen = false" aria-label="Tutup pencarian"
                        class="rounded-lg text-slate-300 hover:text-white bg-white/10 text-xs font-bold px-3 py-1.5 transition-colors border border-white/15">Esc</button>
            </form>

            <div class="p-4 sm:p-6 bg-white/5 max-h-[60vh] overflow-y-auto">
                <p class="text-xs font-bold text-slate-400 mb-3" x-text="query.trim() ? 'Hasil pencarian' : 'Akses cepat'"></p>
                <div class="flex flex-wrap gap-2.5">
                    <template x-for="l in results" :key="l.url">
                        <a :href="l.url" class="px-3.5 py-2 bg-white/10 rounded-xl text-sm font-semibold text-slate-100 border border-white/15 hover:border-elevate-accent hover:text-white transition flex items-center gap-2">
                            <i class="ph-bold text-elevate-accent" :class="l.icon" aria-hidden="true"></i>
                            <span x-text="l.label"></span>
                        </a>
                    </template>
                </div>
                <p x-show="results.length === 0" x-cloak class="text-sm text-slate-300 py-2">Tidak ada menu yang cocok. Coba kata lain, misalnya "ppdb", "cbt", atau "buku".</p>
            </div>
        </div>
    </div>
</nav>