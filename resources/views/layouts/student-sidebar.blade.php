<aside class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-slate-200 transition-transform duration-300 lg:translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="-translate-x-full"
    x-transition:enter-end="translate-x-0"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="translate-x-0"
    x-transition:leave-end="-translate-x-full">
    
    <!-- HEADER SIDEBAR -->
    <div class="h-20 flex items-center px-8 border-b border-slate-100 bg-slate-50/50">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-600/20">
                <i class="ph-bold ph-student text-xl"></i>
            </div>
            <div>
                <h1 class="font-bold text-slate-800 text-lg tracking-tight leading-none">Portal Siswa</h1>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">SMPN 3 Lakbok</p>
            </div>
        </div>
    </div>

    <!-- MENU LIST -->
    <div class="p-4 space-y-1 overflow-y-auto h-[calc(100vh-5rem)] custom-scrollbar">
        
        <!-- GROUP: UTAMA -->
        <p class="px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 mt-4">Menu Utama</p>
        
        {{-- Dashboard / Portal Index --}}
        <a href="{{ route('portal.index') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group
           {{ request()->routeIs('portal.index') 
                ? 'bg-blue-50 text-blue-600 font-bold shadow-sm ring-1 ring-blue-100' 
                : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 font-medium' }}">
            <i class="text-xl {{ request()->routeIs('portal.index') ? 'ph-fill ph-squares-four' : 'ph-bold ph-squares-four' }}"></i>
            <span>Dashboard</span>
        </a>

        {{-- Profil Saya --}}
        <a href="{{ route('portal.show', Auth::guard('student')->id()) }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group
           {{ request()->routeIs('portal.show') 
                ? 'bg-blue-50 text-blue-600 font-bold shadow-sm ring-1 ring-blue-100' 
                : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 font-medium' }}">
            <i class="text-xl {{ request()->routeIs('portal.show') ? 'ph-fill ph-user-circle' : 'ph-bold ph-user-circle' }}"></i>
            <span>Profil Saya</span>
        </a>

        <!-- GROUP: AKADEMIK -->
        <p class="px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 mt-6">Akademik</p>

        {{-- Ruang Belajar / LMS --}}
        <a href="{{ route('students.learning.index') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group
           {{ request()->routeIs('students.learning.*') 
                ? 'bg-blue-50 text-blue-600 font-bold shadow-sm ring-1 ring-blue-100' 
                : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 font-medium' }}">
            <i class="text-xl {{ request()->routeIs('students.learning.*') ? 'ph-fill ph-books' : 'ph-bold ph-books' }}"></i>
            <span>Ruang Belajar</span>
        </a>

        {{-- Jadwal Pelajaran --}}
        <a href="{{ route('student.schedule.index') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group
           {{ request()->routeIs('student.schedule.*') 
                ? 'bg-blue-50 text-blue-600 font-bold shadow-sm ring-1 ring-blue-100' 
                : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 font-medium' }}">
            <i class="text-xl {{ request()->routeIs('student.schedule.*') ? 'ph-fill ph-calendar-blank' : 'ph-bold ph-calendar-blank' }}"></i>
            <span>Jadwal Pelajaran</span>
        </a>

        {{-- Ujian Online --}}
        <a href="{{ route('student.exam.index') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group
           {{ request()->routeIs('student.exam.*') 
                ? 'bg-blue-50 text-blue-600 font-bold shadow-sm ring-1 ring-blue-100' 
                : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900 font-medium' }}">
            <i class="text-xl {{ request()->routeIs('student.exam.*') ? 'ph-fill ph-desktop' : 'ph-bold ph-desktop' }}"></i>
            <span>Ujian Online</span>
        </a>

        <!-- GROUP: KOMUNIKASI & LAYANAN -->
        <p class="px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 mt-6">Layanan</p>

        {{-- Buku Penghubung --}}
        <a href="{{ route('student.liaison.index') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group
           {{ request()->routeIs('student.liaison.*') 
                ? 'bg-indigo-50 text-indigo-600 font-bold shadow-sm ring-1 ring-indigo-100' 
                : 'text-slate-500 hover:bg-indigo-50 hover:text-indigo-600 font-medium' }}">
            <i class="text-xl {{ request()->routeIs('student.liaison.*') ? 'ph-fill ph-chat-circle-text' : 'ph-bold ph-chat-circle-text' }}"></i>
            <span>Buku Penghubung</span>
        </a>

        {{-- Layanan Pengaduan --}}
        <a href="{{ route('student.complaints.index') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group
           {{ request()->routeIs('student.complaints.*') 
                ? 'bg-rose-50 text-rose-600 font-bold shadow-sm ring-1 ring-rose-100' 
                : 'text-slate-500 hover:bg-rose-50 hover:text-rose-600 font-medium' }}">
            <i class="text-xl {{ request()->routeIs('student.complaints.*') ? 'ph-fill ph-megaphone' : 'ph-bold ph-megaphone' }}"></i>
            <span>Pengaduan & Lapor</span>
            
            @if(request()->routeIs('student.complaints.*'))
                <span class="ml-auto w-1.5 h-1.5 bg-rose-500 rounded-full animate-pulse"></span>
            @endif
        </a>
        <a href="{{ route('student.habits.index') }}" 
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group
            {{ request()->routeIs('student.habits.*') 
                    ? 'bg-emerald-50 text-emerald-600 font-bold shadow-sm ring-1 ring-emerald-100' 
                    : 'text-slate-500 hover:bg-emerald-50 hover:text-emerald-600 font-medium' }}">
                <i class="text-xl {{ request()->routeIs('student.habits.*') ? 'ph-fill ph-check-square-offset' : 'ph-bold ph-check-square-offset' }}"></i>
                <span>Jurnal Kebiasaan</span>
        </a>

        <!-- BOTTOM ACTIONS -->
        <div class="mt-8 pt-6 border-t border-slate-100 pb-10">
            <form method="POST" action="{{ route('student.logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-rose-50 hover:text-rose-600 font-bold transition-all group">
                    <i class="ph-bold ph-sign-out text-xl group-hover:-translate-x-1 transition-transform"></i>
                    Keluar Sesi
                </button>
            </form>
        </div>
    </div>
</aside>