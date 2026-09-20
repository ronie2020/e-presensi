<div class="rounded-[2.5rem] bg-[#031d3d]/90 backdrop-blur-2xl border border-white/20 shadow-[0_20px_60px_rgba(0,0,0,0.6)] relative overflow-hidden group p-6 sm:p-10 md:p-12 mb-8 transition-all duration-500">
    
    <!-- Top Glowing Accent Line -->
    <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-transparent via-elevate-accent to-transparent opacity-80"></div>

    <!-- Ambient Glowing Orbs Inside Card -->
    <div class="absolute -top-20 -right-20 w-60 h-60 bg-elevate-accent/15 rounded-full blur-3xl pointer-events-none transition-transform duration-700 group-hover:scale-125"></div>
    <div class="absolute -bottom-20 -left-20 w-60 h-60 bg-elevate-primary/20 rounded-full blur-3xl pointer-events-none"></div>

    <!-- Konten Utama -->
    <div class="relative z-10 w-full max-w-3xl mx-auto flex flex-col items-center text-center">
        
        <!-- LOGO SEKOLAH (3D Floater) -->
        <div class="mb-6 w-20 h-20 sm:w-24 sm:h-24 rounded-3xl bg-gradient-to-tr from-elevate-primary via-elevate-accent to-sky-300 p-[2px] shadow-[0_0_30px_rgba(86,187,241,0.35)] animate-float-portal">
            <div class="w-full h-full bg-[#021124] rounded-[22px] flex items-center justify-center p-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo SMPN 3 Lakbok" class="w-full h-full object-contain" onerror="this.src='/images/logo.png'">
            </div>
        </div>

        <!-- Judul & Deskripsi -->
        <div class="mb-8">
            <!-- Label Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 border border-white/20 text-elevate-accent text-[11px] sm:text-xs font-bold uppercase tracking-widest mb-4 backdrop-blur-md shadow-sm">
                <i class="ph-duotone ph-sparkle text-sm text-elevate-accent"></i>
                <span x-text="mode === 'portal' ? 'Portal Publik • Data Akademik' : (mode === 'lms' ? 'Area Siswa • Ruang Belajar' : 'Area Siswa • Ruang Ujian')"></span>
            </div>

            <!-- Main Title -->
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-white tracking-tight leading-tight mb-4 min-h-[3.5rem]">
                <span x-show="mode === 'portal'" x-transition:enter.duration.400ms>
                    Pusat Informasi &amp; <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-elevate-accent via-[#72cbfa] to-white drop-shadow-md">Rapor Digital Siswa</span>
                </span>
                <span x-show="mode === 'lms'" x-cloak x-transition:enter.duration.400ms>
                    Ruang Belajar <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-400 via-blue-400 to-white drop-shadow-md">Digital Interaktif (LMS)</span>
                </span>
                <span x-show="mode === 'cbt'" x-cloak x-transition:enter.duration.400ms>
                    Sistem Ujian <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-400 via-amber-400 to-white drop-shadow-md">Berbasis Komputer (CBT)</span>
                </span>
            </h1>
            
            <!-- Subtitle -->
            <p class="text-xs sm:text-sm md:text-base text-slate-300 font-normal leading-relaxed max-w-xl mx-auto min-h-[2.5rem]">
                <span x-show="mode === 'portal'" x-transition.opacity>
                    Layanan resmi keterbukaan informasi SMPN 3 Lakbok. Orang Tua dapat memantau presensi GPS pintar, rekap nilai akademik, poin kedisiplinan, dan prestasi secara langsung.
                </span>
                <span x-show="mode === 'lms'" x-cloak x-transition.opacity>
                    Akses materi modul Kurikulum Merdeka, modul digital guru, kuis interaktif, dan pengumpulan tugas kelas secara online.
                </span>
                <span x-show="mode === 'cbt'" x-cloak x-transition.opacity>
                    Ruang ujian terpadu untuk mengikuti Penilaian Tengah Semester (PTS), PAS, Asesmen Sekolah, dan ujian berbasis komputer lainnya.
                </span>
            </p>
        </div>

        <!-- TAB SWITCHER & FORM CARD -->
        <div class="w-full max-w-2xl mx-auto">
            
            <!-- 3 Tab Buttons -->
            @include('students.portal.partials.home-switcher')

            <!-- FORM CONTAINER -->
            <div class="relative bg-white/[0.03] backdrop-blur-xl rounded-2xl p-4 sm:p-6 border border-white/15 shadow-inner">
                
                @if(Auth::guard('student')->check())
                    {{-- STATE 1: SUDAH LOGIN --}}
                    @include('students.portal.partials.home-auth-menu')
                @else
                    {{-- STATE 2: BELUM LOGIN (GUEST) --}}
                    @include('students.portal.partials.home-guest-forms')
                @endif

            </div>
        </div>

        <!-- Error Message -->
        @if(session('error') || $errors->any())
            <div class="mt-6 p-4 bg-rose-500/20 border border-rose-500/30 rounded-2xl text-rose-200 flex items-center justify-center gap-3 backdrop-blur-md shadow-lg max-w-lg mx-auto" role="alert">
                <div class="bg-rose-500/30 rounded-full p-1.5"><i class="ph-bold ph-warning text-rose-300"></i></div>
                <span class="font-bold text-xs sm:text-sm">{{ session('error') ?? $errors->first() }}</span>
            </div>
        @endif

    </div>
</div>

<!-- 3 KARTU LAYANAN TERPADU (PEMISAHAN PERSONA & AKSES CEPAT) -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 w-full mb-10">
    
    <!-- 1. Card Ruang Belajar LMS -->
    <div class="p-6 rounded-[2rem] bg-[#031d3d]/70 backdrop-blur-xl border border-white/15 shadow-lg hover:border-sky-400/40 hover:-translate-y-1 transition-all group flex flex-col justify-between">
        <div>
            <div class="w-12 h-12 rounded-2xl bg-sky-500/20 text-sky-300 border border-sky-500/30 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                <i class="ph-bold ph-books"></i>
            </div>
            <h3 class="text-white font-black text-lg tracking-tight mb-2 flex items-center gap-2">
                <span>Ruang Belajar (LMS)</span>
                <i class="ph-bold ph-arrow-up-right text-xs text-sky-400 opacity-0 group-hover:opacity-100 transition-opacity"></i>
            </h3>
            <p class="text-slate-300 text-xs leading-relaxed mb-5 font-normal">
                Modul Kurikulum Merdeka, materi interaktif guru, pengumpulan tugas, dan bahan ajar digital.
            </p>
        </div>
        <a href="{{ route('student.login.learning') }}" class="w-full py-3 px-4 rounded-xl bg-white/10 hover:bg-sky-500 hover:text-[#021124] text-white text-xs font-black transition-all flex items-center justify-center gap-2 border border-white/15 shadow-sm">
            <span>Buka Ruang Belajar</span>
            <i class="ph-bold ph-arrow-right"></i>
        </a>
    </div>

    <!-- 2. Card Ujian Online CBT -->
    <div class="p-6 rounded-[2rem] bg-[#031d3d]/70 backdrop-blur-xl border border-white/15 shadow-lg hover:border-rose-400/40 hover:-translate-y-1 transition-all group flex flex-col justify-between">
        <div>
            <div class="w-12 h-12 rounded-2xl bg-rose-500/20 text-rose-300 border border-rose-500/30 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                <i class="ph-bold ph-monitor-play"></i>
            </div>
            <h3 class="text-white font-black text-lg tracking-tight mb-2 flex items-center gap-2">
                <span>Ujian Online (CBT)</span>
                <i class="ph-bold ph-arrow-up-right text-xs text-rose-400 opacity-0 group-hover:opacity-100 transition-opacity"></i>
            </h3>
            <p class="text-slate-300 text-xs leading-relaxed mb-5 font-normal">
                Sesi ujian online, asesmen tengah semester (PTS), PAS, serta kuis terjadwal berbasis komputer.
            </p>
        </div>
        <a href="{{ route('student.login.cbt') }}" class="w-full py-3 px-4 rounded-xl bg-white/10 hover:bg-rose-500 hover:text-white text-white text-xs font-black transition-all flex items-center justify-center gap-2 border border-white/15 shadow-sm">
            <span>Masuk Ruang Ujian</span>
            <i class="ph-bold ph-arrow-right"></i>
        </a>
    </div>

    <!-- 3. Card Login Guru & Staff -->
    <div class="p-6 rounded-[2rem] bg-[#031d3d]/70 backdrop-blur-xl border border-white/15 shadow-lg hover:border-elevate-accent/40 hover:-translate-y-1 transition-all group flex flex-col justify-between">
        <div>
            <div class="w-12 h-12 rounded-2xl bg-elevate-accent/20 text-elevate-accent border border-elevate-accent/30 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                <i class="ph-bold ph-user-gear"></i>
            </div>
            <h3 class="text-white font-black text-lg tracking-tight mb-2 flex items-center gap-2">
                <span>Portal Guru &amp; Staff</span>
                <i class="ph-bold ph-arrow-up-right text-xs text-elevate-accent opacity-0 group-hover:opacity-100 transition-opacity"></i>
            </h3>
            <p class="text-slate-300 text-xs leading-relaxed mb-5 font-normal">
                Presensi GPS pintar guru, e-jurnal KBM, penginputan nilai rapor, dan manajemen administrasi.
            </p>
        </div>
        <a href="{{ route('login') }}" class="w-full py-3 px-4 rounded-xl bg-white/10 hover:bg-elevate-accent hover:text-elevate-dark text-white text-xs font-black transition-all flex items-center justify-center gap-2 border border-white/15 shadow-sm">
            <span>Login Staff Resmi</span>
            <i class="ph-bold ph-arrow-right"></i>
        </a>
    </div>

</div>