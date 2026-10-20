<aside class="hidden lg:flex lg:flex-col lg:w-72 lg:fixed lg:inset-y-0 z-50 bg-[#031d3d] border-r border-white/10 text-slate-100 p-6">
    <div class="flex items-center gap-3 pb-6 border-b border-white/10">
        <div class="w-10 h-10 rounded-xl bg-[#021124] border border-white/10 flex items-center justify-center p-1.5">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-full h-full object-contain" onerror="this.src='/images/logo.png'">
        </div>
        <div class="leading-tight">
            <span class="text-sm font-black text-white block">SMPN 3 LAKBOK</span>
            <span class="text-[9px] font-bold text-sky-400 uppercase tracking-widest">Portal Siswa</span>
        </div>
    </div>

    <nav class="mt-6 space-y-1.5 flex-1">
        <a href="{{ route('portal.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-slate-200 hover:bg-white/5 hover:text-white transition-colors">
            <i class="ph-bold ph-identification-card text-lg text-sky-400"></i> Ringkasan Portal
        </a>
        <a href="{{ route('students.learning.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-slate-200 hover:bg-white/5 hover:text-white transition-colors">
            <i class="ph-bold ph-books text-lg text-indigo-400"></i> Pembelajaran LMS
        </a>
        <a href="{{ route('student.exam.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-slate-200 hover:bg-white/5 hover:text-white transition-colors">
            <i class="ph-bold ph-desktop text-lg text-rose-400"></i> Ruang Ujian CBT
        </a>
        <a href="{{ route('library.catalogue') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-slate-200 hover:bg-white/5 hover:text-white transition-colors">
            <i class="ph-bold ph-book-open text-lg text-emerald-400"></i> Perpustakaan
        </a>
    </nav>

    <div class="pt-6 border-t border-white/10">
        @if(Auth::guard('student')->check())
            <div class="mb-4 bg-white/5 rounded-2xl p-3 border border-white/5">
                <p class="text-xs font-bold text-white truncate">{{ Auth::guard('student')->user()->name }}</p>
                <p class="text-[10px] text-slate-400 font-mono">NISN: {{ Auth::guard('student')->user()->student_id }}</p>
            </div>
        @endif
        <form method="POST" action="{{ route('student.logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center gap-2 py-2.5 px-4 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 rounded-xl text-xs font-bold border border-rose-500/20 transition-all">
                <i class="ph-bold ph-sign-out"></i> Keluar Akun
            </button>
        </form>
    </div>
</aside>
