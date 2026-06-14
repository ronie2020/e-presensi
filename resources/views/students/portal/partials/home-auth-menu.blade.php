<div class="text-center py-6">
    <div class="inline-block p-2 px-4 rounded-full bg-emerald-100 text-emerald-700 font-bold text-xs uppercase mb-4 animate-pulse border border-emerald-200 shadow-sm">
        <i class="ph-fill ph-check-circle"></i> Anda Sedang Login
    </div>
    <h3 class="text-xl font-bold text-slate-800 mb-1">Halo, {{ Auth::guard('student')->user()->name }}</h3>
    
    @php
        $user = Auth::guard('student')->user();
        // LOGIKA PENGECEKAN ALUMNI (Mencegah Alumni mengakses menu siswa aktif)
        $isAlumni = $user->status === 'graduated' 
                 || !empty($user->graduated_date) 
                 || ($user->graduation && strtoupper($user->graduation->status) === 'LULUS');
    @endphp

    @if($isAlumni)
        <p class="text-amber-600 font-bold text-sm mb-6 bg-amber-50 p-2 rounded-lg border border-amber-200">
            <i class="ph-bold ph-warning"></i> Akun Anda telah beralih ke status Alumni.
        </p>

        <!-- Menu Khusus Alumni di Halaman Awal -->
        <div class="space-y-3">
            <a href="{{ route('alumni.dashboard') }}" class="w-full block py-4 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-bold shadow-lg transition-all flex items-center justify-center gap-2 transform active:scale-95">
                <i class="ph-bold ph-graduation-cap text-xl"></i> Buka Dashboard Alumni
            </a>
        </div>
    @else
        <p class="text-slate-500 text-sm mb-6">Silakan pilih layanan untuk melanjutkan:</p>

        <!-- Tombol Dinamis Sesuai Mode (Khusus Siswa Aktif) -->
        <div x-show="mode === 'portal'" class="space-y-3">
            {{-- Tombol Buka Profil --}}
            <a href="{{ route('portal.show', $user->id) }}" class="w-full block py-4 bg-slate-800 hover:bg-slate-900 text-white rounded-xl font-bold shadow-lg transition-all flex items-center justify-center gap-2">
                <i class="ph-bold ph-user-circle text-xl"></i> Buka Profil Akademik Saya
            </a>

            {{-- Tombol Lihat Jadwal Pelajaran --}}
            <a href="{{ route('student.schedule.index') }}" class="w-full block py-4 bg-white border-2 border-slate-100 hover:border-blue-200 text-slate-700 hover:text-blue-600 rounded-xl font-bold transition-all flex items-center justify-center gap-2">
                <i class="ph-bold ph-calendar-blank text-xl"></i> Lihat Jadwal Pelajaran
            </a>

            {{-- Tombol Buku Penghubung & Chat --}}
            <a href="{{ route('student.liaison.index') }}" class="w-full block py-4 bg-white border-2 border-indigo-100 hover:border-indigo-300 text-indigo-700 hover:text-indigo-800 rounded-xl font-bold transition-all flex items-center justify-center gap-2 shadow-sm hover:shadow-md">
                <i class="ph-bold ph-chat-circle-text text-xl"></i> Buku Penghubung & Chat
            </a>

            {{-- Tombol Layanan Pengaduan --}}
            <a href="{{ route('student.complaints.index') }}" class="w-full block py-4 bg-white border-2 border-rose-100 hover:border-rose-300 text-rose-700 hover:text-rose-800 rounded-xl font-bold transition-all flex items-center justify-center gap-2 shadow-sm hover:shadow-md">
                <i class="ph-bold ph-warning-circle text-xl"></i> Layanan Pengaduan
            </a>

            {{-- Jurnal Kebiasaan --}}
            <a href="{{ route('student.habits.dashboard') }}" class="w-full block py-4 bg-white border-2 border-emerald-100 hover:border-emerald-300 text-emerald-700 hover:text-emerald-800 rounded-xl font-bold transition-all flex items-center justify-center gap-2 shadow-sm hover:shadow-md">
                <i class="ph-bold ph-check-square-offset text-xl"></i> Jurnal Kebiasaan
            </a>
        </div>

        <div x-show="mode === 'lms'" x-cloak>
            <a href="{{ route('students.learning.index') }}" class="w-full block py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-lg shadow-blue-600/20 transition-all flex items-center justify-center gap-2">
                <i class="ph-bold ph-books text-xl"></i> Masuk Ruang Belajar
            </a>
        </div>

        <div x-show="mode === 'cbt'" x-cloak>
            <a href="{{ route('student.exam.index') }}" class="w-full block py-4 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold shadow-lg shadow-rose-600/20 transition-all flex items-center justify-center gap-2">
                <i class="ph-bold ph-desktop text-xl"></i> Masuk Ruang Ujian
            </a>
        </div>
    @endif

    <!-- Tombol Logout Universal -->
    <div class="mt-6 pt-5 border-t border-slate-100">
        <form method="POST" action="{{ route('student.logout') }}">
            @csrf
            <button type="submit" class="text-xs font-bold text-rose-500 hover:text-rose-700 hover:underline flex items-center justify-center gap-1 mx-auto">
                <i class="ph-bold ph-sign-out"></i> Bukan Anda? Keluar Akun
            </button>
        </form>
    </div>
</div>