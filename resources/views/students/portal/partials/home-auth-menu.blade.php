<div class="text-center py-5">
    <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-emerald-500/20 text-emerald-300 font-bold text-xs uppercase mb-3 border border-emerald-500/30 shadow-sm backdrop-blur-sm">
        <i class="ph-fill ph-check-circle text-emerald-400"></i>
        <span>Sesi Siswa Aktif</span>
    </div>
    <h3 class="text-xl sm:text-2xl font-black text-white mb-1">Halo, {{ Auth::guard('student')->user()->name }}</h3>
    
    @php
        $user = Auth::guard('student')->user();
        // LOGIKA PENGECEKAN ALUMNI
        $isAlumni = $user->status === 'graduated' 
                 || !empty($user->graduated_date) 
                 || ($user->graduation && strtoupper($user->graduation->status) === 'LULUS');
    @endphp

    @if($isAlumni)
        <p class="text-amber-300 font-bold text-xs sm:text-sm mb-5 bg-amber-500/15 p-3 rounded-2xl border border-amber-500/30 backdrop-blur-md">
            <i class="ph-bold ph-warning text-amber-400"></i> Akun Anda telah beralih ke status Alumni.
        </p>

        <!-- Menu Khusus Alumni di Halaman Awal -->
        <div class="space-y-3">
            <a href="{{ route('alumni.dashboard') }}" class="w-full py-4 bg-gradient-to-r from-amber-600 to-amber-700 hover:from-amber-500 hover:to-amber-600 text-white rounded-2xl font-black shadow-lg transition-all flex items-center justify-center gap-2 transform active:scale-95">
                <i class="ph-bold ph-graduation-cap text-xl"></i> Buka Dashboard Alumni
            </a>
        </div>
    @else
        <p class="text-slate-300 text-xs sm:text-sm mb-5 font-normal">Silakan pilih layanan untuk melanjutkan aktivitas:</p>

        <!-- Tombol Dinamis Sesuai Mode (Khusus Siswa Aktif) -->
        <div x-show="mode === 'portal'" class="space-y-3">
            {{-- Tombol Buka Profil --}}
            <a href="{{ route('portal.show', $user->id) }}" class="w-full py-4 px-6 bg-gradient-to-r from-elevate-primary via-[#0d52a1] to-elevate-accent hover:brightness-110 text-white rounded-2xl font-black shadow-[0_0_20px_rgba(86,187,241,0.3)] transition-all flex items-center justify-center gap-2.5">
                <i class="ph-bold ph-user-circle text-xl"></i>
                <span>Buka Profil &amp; Rapor Akademik Saya</span>
                <i class="ph-bold ph-arrow-right text-base"></i>
            </a>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 pt-1">
                {{-- Tombol Buku Penghubung & Chat --}}
                <a href="{{ route('student.liaison.index') }}" class="py-3 px-3 bg-white/[0.06] hover:bg-white/10 text-indigo-300 hover:text-white rounded-xl font-bold transition-all flex items-center justify-center gap-2 border border-white/10 text-xs">
                    <i class="ph-bold ph-chat-circle-text text-lg text-indigo-400"></i>
                    <span>Buku Penghubung</span>
                </a>

                {{-- Tombol Layanan Pengaduan --}}
                <a href="{{ route('student.complaints.index') }}" class="py-3 px-3 bg-white/[0.06] hover:bg-white/10 text-rose-300 hover:text-white rounded-xl font-bold transition-all flex items-center justify-center gap-2 border border-white/10 text-xs">
                    <i class="ph-bold ph-warning-circle text-lg text-rose-400"></i>
                    <span>Layanan Pengaduan</span>
                </a>

                {{-- Jurnal Kebiasaan --}}
                <a href="{{ route('student.habits.dashboard') }}" class="py-3 px-3 bg-white/[0.06] hover:bg-white/10 text-emerald-300 hover:text-white rounded-xl font-bold transition-all flex items-center justify-center gap-2 border border-white/10 text-xs">
                    <i class="ph-bold ph-check-square-offset text-lg text-emerald-400"></i>
                    <span>Jurnal Kebiasaan</span>
                </a>
            </div>
        </div>

        <div x-show="mode === 'lms'" x-cloak>
            <a href="{{ route('students.learning.index') }}" class="w-full py-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:brightness-110 text-white rounded-2xl font-black shadow-[0_0_20px_rgba(37,99,235,0.4)] transition-all flex items-center justify-center gap-2">
                <i class="ph-bold ph-books text-xl"></i>
                <span>Masuk ke Ruang Belajar (LMS)</span>
                <i class="ph-bold ph-arrow-right text-base"></i>
            </a>
        </div>

        <div x-show="mode === 'cbt'" x-cloak>
            <a href="{{ route('student.exam.index') }}" class="w-full py-4 bg-gradient-to-r from-rose-600 to-red-600 hover:brightness-110 text-white rounded-2xl font-black shadow-[0_0_20px_rgba(225,29,72,0.4)] transition-all flex items-center justify-center gap-2">
                <i class="ph-bold ph-desktop text-xl"></i>
                <span>Masuk ke Ruang Ujian (CBT)</span>
                <i class="ph-bold ph-arrow-right text-base"></i>
            </a>
        </div>
    @endif

    <!-- Tombol Logout Universal -->
    <div class="mt-5 pt-4 border-t border-white/10">
        <form method="POST" action="{{ route('student.logout') }}">
            @csrf
            <button type="submit" class="text-xs font-bold text-rose-400 hover:text-rose-300 hover:underline flex items-center justify-center gap-1 mx-auto transition-colors">
                <i class="ph-bold ph-sign-out"></i> Bukan Anda? Keluar Akun
            </button>
        </form>
    </div>
</div>