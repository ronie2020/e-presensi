{{--
    HERO — Revitalisasi Portal Modern Elevate
    Mendukung auto-detect data PPDB, integrasi $schoolStats, dan modal pengumuman.
--}}
@php
    // --- 1. PPDB Status (Prioritaskan controller, fallback ke storage/app/private/ppdb_schedule.json) ---
    $ppdbOpen      = (bool) ($ppdbOpen ?? false);
    $ppdbYearLabel = $ppdbYearLabel ?? ('2026/2027');
    $ppdbCloseDate = $ppdbCloseDate ?? null;

    if (!$ppdbOpen) {
        $ppdbSchedulePath = storage_path('app/private/ppdb_schedule.json');
        if (file_exists($ppdbSchedulePath)) {
            $ppdbData = rescue(fn () => json_decode(file_get_contents($ppdbSchedulePath), true), [], false);
            if (!empty($ppdbData['announcement_date'])) {
                $ppdbCloseDate = $ppdbCloseDate ?? $ppdbData['announcement_date'];
                // Anggap buka jika tanggal pengumuman belum lewat
                $ppdbOpen = \Carbon\Carbon::parse($ppdbData['announcement_date'])->isFuture();
            }
        }
    }

    $ppdbClose    = null;
    $ppdbDaysLeft = null;
    if ($ppdbOpen && !empty($ppdbCloseDate)) {
        $ppdbClose = rescue(fn () => \Carbon\Carbon::parse($ppdbCloseDate), null, false);
        if ($ppdbClose) {
            $ppdbDaysLeft = max(0, (int) now()->startOfDay()->diffInDays($ppdbClose->copy()->startOfDay(), false));
        }
    }

    // --- 2. Persentase Kehadiran Hari Ini ---
    // Di LandingPageController, $stats['hadir'] sudah merupakan gabungan (tepat_waktu + terlambat)
    $hadirTotal = (int) ($stats['hadir'] ?? 0);
    $tidakHadir = (int) ($stats['tidak_hadir'] ?? 0);
    $tercatat   = $hadirTotal + $tidakHadir;
    $hadirPct   = $tercatat > 0 ? (int) round(($hadirTotal / $tercatat) * 100) : null;

    // --- 3. Ringkasan Data Sekolah (Sinkron dengan $schoolStats dari Controller) ---
    $ringkasan = collect([
        [
            'icon'  => 'ph-users-three',
            'value' => $schoolStats['siswa'] ?? $stats['siswa'] ?? null,
            'label' => 'Siswa aktif'
        ],
        [
            'icon'  => 'ph-chalkboard-teacher',
            'value' => $schoolStats['guru'] ?? $stats['guru'] ?? null,
            'label' => 'Guru & pendidik'
        ],
        [
            'icon'  => 'ph-books',
            'value' => $schoolStats['materi'] ?? $stats['modul'] ?? null,
            'label' => 'Modul belajar'
        ],
    ])->filter(fn ($r) => filled($r['value']));

    // --- 4. 3 Pengumuman Teratas ---
    $pengumuman = collect($announcements ?? [])->take(3)->values();

    $btnPrimary   = 'inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-full bg-gradient-to-r from-elevate-accent to-elevate-primary text-white font-black text-xs uppercase tracking-wider shadow-[0_0_25px_rgba(86,187,241,0.35)] hover:shadow-[0_0_35px_rgba(86,187,241,0.55)] hover:-translate-y-0.5 transition-all';
    $btnSecondary = 'inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-full bg-white/10 hover:bg-white/20 border border-white/25 text-white font-bold text-xs uppercase tracking-wider backdrop-blur-md hover:-translate-y-0.5 transition-all';
@endphp

<!-- HERO SECTION -->
<section id="home" class="relative min-h-screen pt-28 pb-20 md:pt-36 md:pb-24 flex flex-col items-center justify-center overflow-hidden w-full max-w-[100vw]">

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full flex-grow flex flex-col justify-center">

        {{-- 0. BANNER PPDB (Hanya muncul saat dibuka) --}}
        @if($ppdbOpen)
            <a href="{{ route('ppdb.create') }}"
               class="mb-6 lg:mb-8 w-full max-w-5xl mx-auto flex flex-wrap items-center gap-x-4 gap-y-2 rounded-2xl bg-emerald-500/15 border border-emerald-400/35 px-4 sm:px-5 py-3 text-emerald-100 hover:bg-emerald-500/25 transition-all duration-300 backdrop-blur-md shadow-[0_4px_20px_rgba(16,185,129,0.15)] group"
               data-aos="fade-down" data-aos-duration="700">
                <div class="w-8 h-8 rounded-xl bg-emerald-400/20 flex items-center justify-center text-emerald-300 shrink-0">
                    <i class="ph-bold ph-megaphone text-lg" aria-hidden="true"></i>
                </div>
                <span class="font-black text-sm sm:text-base text-white">PPDB {{ $ppdbYearLabel }} Dibuka</span>
                @if($ppdbClose)
                    <span class="text-xs sm:text-sm text-emerald-200">
                        Ditutup {{ $ppdbClose->translatedFormat('j F Y') }}@if($ppdbDaysLeft !== null) &middot; <strong class="text-emerald-300 font-bold">{{ $ppdbDaysLeft === 0 ? 'Hari Terakhir!' : 'Sisa '.$ppdbDaysLeft.' hari' }}</strong>@endif
                    </span>
                @endif
                <span class="ml-auto inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-emerald-300 group-hover:text-white underline underline-offset-4 transition-colors">
                    Daftar sekarang <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform" aria-hidden="true"></i>
                </span>
            </a>
        @endif

        <div class="grid lg:grid-cols-12 gap-8 lg:gap-12 items-center">

            {{-- 1. KIRI: HEADLINE + AKSI UTAMA --}}
            <div class="lg:col-span-7 xl:col-span-7 w-full" data-aos="fade-right" data-aos-duration="900">
                <div class="relative w-full rounded-[2rem] sm:rounded-[2.5rem] bg-white/5 backdrop-blur-2xl border border-white/20 p-6 sm:p-10 shadow-[0_8px_32px_rgba(0,0,0,0.35)] overflow-hidden">
                    
                    {{-- Soft glow di dalam kartu --}}
                    <div class="absolute -top-24 -right-24 w-48 h-48 bg-elevate-accent/20 rounded-full blur-3xl pointer-events-none"></div>

                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 border border-white/20 text-white text-[11px] sm:text-xs font-bold mb-6 backdrop-blur-md">
                        <i class="ph-duotone ph-sparkle text-elevate-accent text-sm" aria-hidden="true"></i>
                        <span>E-Learning &bull; SIMADU </span>
                    </div>

                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white tracking-tight mb-5 leading-[1.12]">
                        Membangun.<br>
                        Generasi Cerdas.<br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-elevate-accent via-[#72cbfa] to-white drop-shadow-sm">Berkarakter.</span>
                    </h1>

                    <p class="text-sm sm:text-base text-slate-200 mb-8 leading-relaxed max-w-xl font-medium">
                        Platform Sistem Informasi Manajemen Akademik & Pendidikan terpadu SMPN 3 Lakbok. Dilengkapi modul belajar interaktif, ujian CBT online, dan sistem presensi digital sekolah.
                    </p>

                    {{-- Tombol Aksi Utama --}}
                    <div class="flex flex-wrap items-center gap-3 mb-6">
                        @if($ppdbOpen)
                            <a href="{{ route('ppdb.create') }}" class="{{ $btnPrimary }}">
                                <span>Daftar PPDB</span> <i class="ph-bold ph-arrow-right" aria-hidden="true"></i>
                            </a>
                            <a href="{{ route('student.login.learning') }}" class="{{ $btnSecondary }}">
                                <i class="ph-bold ph-books text-elevate-accent" aria-hidden="true"></i> <span>Ruang Belajar</span>
                            </a>
                        @else
                            <a href="{{ route('student.login.learning') }}" class="{{ $btnPrimary }}">
                                <i class="ph-bold ph-books" aria-hidden="true"></i> <span>Ruang Belajar</span> <i class="ph-bold ph-arrow-right" aria-hidden="true"></i>
                            </a>
                            <a href="{{ route('ppdb.create') }}" class="{{ $btnSecondary }}">
                                <span>Info PPDB</span>
                            </a>
                        @endif
                    </div>

                    {{-- Layanan Cepat (Secondary Links) --}}
                    <div class="flex flex-wrap items-center gap-x-5 gap-y-2 text-xs sm:text-sm font-semibold text-sky-200 mb-8 pb-6 border-b border-white/15">
                        <a href="{{ route('ppdb.check') }}" class="hover:text-white underline-offset-4 hover:underline flex items-center gap-1.5 transition-colors">
                            <i class="ph-bold ph-magnifying-glass text-elevate-accent"></i> Hasil PPDB
                        </a>
                        <a href="{{ route('kiosk.show') }}" class="hover:text-white underline-offset-4 hover:underline flex items-center gap-1.5 transition-colors">
                            <i class="ph-bold ph-qr-code text-emerald-300"></i> Scan Presensi
                        </a>
                        <a href="{{ route('library.catalogue') }}" class="hover:text-white underline-offset-4 hover:underline flex items-center gap-1.5 transition-colors">
                            <i class="ph-bold ph-book-open text-elevate-accent"></i> Perpustakaan
                        </a>
                        <a href="{{ route('graduation.index') }}" class="hover:text-white underline-offset-4 hover:underline flex items-center gap-1.5 transition-colors">
                            <i class="ph-bold ph-certificate text-amber-300"></i> SKL Kelulusan
                        </a>
                    </div>

                    {{-- Masuk Sesuai Peran --}}
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <p class="text-xs font-bold text-slate-300 uppercase tracking-wider mb-2.5">Akses Cepat Pengguna</p>
                            <div class="flex flex-wrap gap-2.5">
                                <a href="{{ route('student.login.learning') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-[#031d3d]/90 hover:bg-[#0d52a1] border border-white/20 text-xs font-bold text-white transition-all shadow-sm">
                                    <i class="ph-bold ph-backpack text-elevate-accent text-sm" aria-hidden="true"></i> Masuk Siswa
                                </a>
                                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-[#031d3d]/90 hover:bg-[#0d52a1] border border-white/20 text-xs font-bold text-white transition-all shadow-sm">
                                    <i class="ph-bold ph-chalkboard-teacher text-emerald-300 text-sm" aria-hidden="true"></i> Portal Guru
                                </a>
                                <a href="{{ route('display.schedules.show') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-[#031d3d]/90 hover:bg-[#0d52a1] border border-white/20 text-xs font-bold text-white transition-all shadow-sm">
                                    <i class="ph-bold ph-broadcast text-amber-300 text-sm" aria-hidden="true"></i> Display Jadwal
                                </a>
                            </div>
                        </div>

                        {{-- Bukti Kepercayaan --}}
                        <div class="flex flex-wrap items-center gap-3 text-xs font-medium text-slate-300 mt-2 sm:mt-0">
                            <span class="inline-flex items-center gap-1 bg-white/5 border border-white/10 px-2.5 py-1 rounded-lg">
                                <i class="ph-duotone ph-shield-check text-emerald-300 text-sm" aria-hidden="true"></i> Akreditasi A
                            </span>
                            <span class="inline-flex items-center gap-1 bg-white/5 border border-white/10 px-2.5 py-1 rounded-lg">
                                <i class="ph-duotone ph-smiley text-elevate-accent text-sm" aria-hidden="true"></i> Ramah Anak
                            </span>
                        </div>
                    </div>

                </div>
            </div>

            {{-- 2. KANAN: FOTO KEGIATAN & PENGUMUMAN --}}
            <div class="lg:col-span-5 xl:col-span-5 w-full flex flex-col items-center gap-6" data-aos="fade-left" data-aos-duration="900" data-aos-delay="120">

                {{-- Lingkaran Foto dengan 4 Pintasan Berlabel --}}
                <div class="relative w-full max-w-[21rem] sm:max-w-[25rem] aspect-square flex items-center justify-center">
                    {{-- Ambient Glow dengan animate-portal-glow ringan GPU --}}
                    <div class="absolute inset-4 rounded-full bg-elevate-accent/25 blur-3xl pointer-events-none animate-portal-glow" aria-hidden="true"></div>

                    {{-- Circle Image Portal --}}
                    <div class="relative w-[72%] aspect-square rounded-full border-[3px] border-elevate-accent shadow-[0_0_40px_rgba(86,187,241,0.45)] ring-8 ring-elevate-accent/15 overflow-hidden bg-[#0d2a52]">
                        <img src="{{ asset('images/digital2.jpg') }}"
                             alt="Siswa SMPN 3 Lakbok belajar interaktif di kelas digital"
                             width="480" height="480" fetchpriority="high" decoding="async"
                             class="w-full h-full object-cover object-center"
                             onerror="this.onerror=null; this.src='{{ asset('images/netila.jpg') }}';">
                    </div>

                    {{-- 4 Floating Badges dengan Link Aktif --}}
                    <a href="#katalog-lms" class="absolute top-[4%] left-0 sm:left-2 inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-[#031d3d]/90 backdrop-blur-md border border-white/25 text-xs font-bold text-white hover:border-elevate-accent shadow-lg animate-float-badge z-20">
                        <i class="ph-bold ph-book-open text-cyan-300" aria-hidden="true"></i> Modul LMS
                    </a>
                    <a href="{{ route('student.login.cbt') }}" class="absolute top-[4%] right-0 sm:right-2 inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-[#031d3d]/90 backdrop-blur-md border border-white/25 text-xs font-bold text-white hover:border-elevate-accent shadow-lg animate-float-badge z-20" style="animation-delay: 1s;">
                        <i class="ph-bold ph-monitor-play text-sky-300" aria-hidden="true"></i> Ujian CBT
                    </a>
                    <a href="{{ route('kiosk.show') }}" class="absolute bottom-[4%] left-0 sm:left-2 inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-[#031d3d]/90 backdrop-blur-md border border-white/25 text-xs font-bold text-white hover:border-elevate-accent shadow-lg animate-float-badge z-20" style="animation-delay: 2s;">
                        <i class="ph-bold ph-map-pin text-emerald-300" aria-hidden="true"></i> Presensi GPS
                    </a>
                    <a href="{{ route('library.catalogue') }}" class="absolute bottom-[4%] right-0 sm:right-2 inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-[#031d3d]/90 backdrop-blur-md border border-white/25 text-xs font-bold text-white hover:border-elevate-accent shadow-lg animate-float-badge z-20" style="animation-delay: 3s;">
                        <i class="ph-bold ph-book-open-text text-purple-300" aria-hidden="true"></i> Perpustakaan
                    </a>
                </div>

                {{-- WIDGET 2-IN-1: STATISTIK KEHADIRAN & PENGUMUMAN --}}
                <div x-data="{ 
                        heroTab: 'chart',
                        switchTab(tab) {
                            this.heroTab = tab;
                            if (tab === 'chart') {
                                this.$nextTick(() => {
                                    window.dispatchEvent(new Event('resize'));
                                });
                            }
                        }
                     }" 
                     class="w-full max-w-md rounded-3xl bg-[#031d3d]/90 backdrop-blur-2xl border border-white/20 p-4 sm:p-5 shadow-[0_12px_40px_rgba(0,0,0,0.5)] flex flex-col">
                    
                    {{-- Header Widget dengan Tab Switcher --}}
                    <div class="flex items-center justify-between pb-3 mb-3 border-b border-white/10 shrink-0 gap-2">
                        <div class="flex items-center gap-1.5 p-1 bg-white/5 rounded-2xl border border-white/10">
                            {{-- Tab 1: Kehadiran --}}
                            <button type="button" 
                                    @click="switchTab('chart')"
                                    :class="heroTab === 'chart' 
                                        ? 'bg-[#0d52a1] text-white shadow-sm border border-elevate-accent/40 font-bold' 
                                        : 'text-slate-300 hover:text-white font-semibold'"
                                    class="px-3 py-1.5 rounded-xl text-xs flex items-center gap-2 transition-all">
                                <i class="ph-fill ph-chart-bar text-sky-400 text-sm" aria-hidden="true"></i>
                                <span>Statistik Kehadiran</span>
                            </button>

                            {{-- Tab 2: Pengumuman --}}
                            <button type="button" 
                                    @click="switchTab('announcements')"
                                    :class="heroTab === 'announcements' 
                                        ? 'bg-[#0d52a1] text-white shadow-sm border border-elevate-accent/40 font-bold' 
                                        : 'text-slate-300 hover:text-white font-semibold'"
                                    class="px-3 py-1.5 rounded-xl text-xs flex items-center gap-1.5 transition-all">
                                <i class="ph-bold ph-megaphone-simple text-amber-300 text-sm" aria-hidden="true"></i>
                                <span>Pengumuman</span>
                                @if(count($pengumuman) > 0)
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                @endif
                            </button>
                        </div>

                        {{-- Right Status Badge --}}
                        <template x-if="heroTab === 'chart'">
                            <span class="text-[10px] font-black uppercase tracking-wider bg-emerald-500/20 text-emerald-300 px-2.5 py-1 rounded-full border border-emerald-400/30 flex items-center gap-1.5 shrink-0 backdrop-blur-sm shadow-sm animate-pulse">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Live
                            </span>
                        </template>
                        <template x-if="heroTab === 'announcements'">
                            <span class="text-[10px] font-black uppercase tracking-wider bg-elevate-accent/20 text-elevate-accent px-2.5 py-1 rounded-full border border-elevate-accent/30 shrink-0">
                                Info Resmi
                            </span>
                        </template>
                    </div>

                    {{-- TAB CONTENT 1: STATISTIK KEHADIRAN (CHART BAR) --}}
                    <div x-show="heroTab === 'chart'" x-transition.opacity.duration.200ms class="w-full flex flex-col">
                        <div class="w-full relative h-[165px]">
                            <canvas id="publicWeeklyChart" class="absolute inset-0 w-full h-full"></canvas>
                        </div>
                    </div>

                    {{-- TAB CONTENT 2: PENGUMUMAN TERBARU --}}
                    <div x-show="heroTab === 'announcements'" x-cloak x-transition.opacity.duration.200ms class="w-full flex flex-col">
                        @forelse($pengumuman as $i => $a)
                            @php
                                $judul  = data_get($a, 'title', 'Pengumuman Sekolah');
                                $tgl    = data_get($a, 'created_at') ?? data_get($a, 'date');
                                $tglFmt = $tgl ? rescue(fn () => \Carbon\Carbon::parse($tgl)->translatedFormat('d M'), '', false) : '-';
                            @endphp
                            <button type="button" 
                                    @click="openAnnouncementByIndex({{ $i }})"
                                    class="w-full text-left py-2 border-b border-white/10 last:border-b-0 flex items-start gap-3 text-xs sm:text-sm text-slate-200 hover:text-white group transition-colors focus:outline-none">
                                <span class="shrink-0 w-12 text-sky-300 font-bold text-xs bg-white/5 py-1 px-1.5 rounded text-center">{{ $tglFmt }}</span>
                                <span class="font-medium group-hover:text-elevate-accent group-hover:underline underline-offset-4 line-clamp-2 leading-snug">{{ $judul }}</span>
                            </button>
                        @empty
                            <p class="text-xs text-slate-300 py-6 text-center">Belum ada pengumuman terbaru saat ini.</p>
                        @endforelse
                    </div>

                </div>
            </div>

        </div>

        {{-- 3. BARIS STATISTIK SEKOLAH & KEHADIRAN --}}
        @if($hadirPct !== null || $ringkasan->isNotEmpty())
            <div class="mt-12 lg:mt-16 w-full" data-aos="fade-up" data-aos-duration="900" data-aos-delay="200">
                <div class="w-full max-w-5xl mx-auto rounded-3xl bg-white/5 backdrop-blur-2xl border border-white/15 p-4 sm:p-6 shadow-[0_8px_32px_rgba(0,0,0,0.3)]">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-0 md:divide-x divide-white/10">
                        
                        {{-- Metrik 1: Persentase Kehadiran Positif --}}
                        @if($hadirPct !== null)
                            <div class="flex items-center gap-3.5 md:px-5">
                                <div class="w-11 h-11 rounded-2xl border border-white/20 bg-white/5 flex items-center justify-center text-emerald-300 shrink-0 shadow-inner">
                                    <i class="ph-duotone ph-user-check text-2xl" aria-hidden="true"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-xl sm:text-2xl font-black text-emerald-300 tracking-tight">{{ $hadirPct }}%</span>
                                    <span class="text-[11px] text-slate-300 font-bold uppercase tracking-wider">Hadir Hari Ini</span>
                                </div>
                            </div>
                        @endif

                        {{-- Metrik 2, 3, 4: Data Profil Sekolah Aktif --}}
                        @foreach($ringkasan as $r)
                            <div class="flex items-center gap-3.5 md:px-5">
                                <div class="w-11 h-11 rounded-2xl border border-white/20 bg-white/5 flex items-center justify-center text-elevate-accent shrink-0 shadow-inner">
                                    <i class="ph-duotone {{ $r['icon'] }} text-2xl" aria-hidden="true"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-xl sm:text-2xl font-black text-white tracking-tight">{{ $r['value'] }}</span>
                                    <span class="text-[11px] text-slate-300 font-bold uppercase tracking-wider">{{ $r['label'] }}</span>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        @endif

    </div>

</section>