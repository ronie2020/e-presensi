<nav 
    x-cloak
    class="fixed inset-y-0 left-0 w-72 h-screen flex-shrink-0 flex flex-col transition-transform duration-300 ease-in-out z-50 md:static md:translate-x-0 bg-gray-900 text-white shadow-2xl overflow-hidden"
    :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}">
    
    <!-- BACKGROUND DECORATION -->
    <div class="absolute inset-0 z-0 pointer-events-none">
        <div class="absolute inset-0 bg-gradient-to-b from-blue-900 via-blue-800 to-slate-900"></div>
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-b from-blue-400/10 to-transparent"></div>
    </div>

    <!-- HEADER LOGO -->
    <div class="relative z-10 shrink-0 flex items-center justify-center h-28 px-6 border-b border-white/10 bg-blue-900/20 backdrop-blur-sm">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-4 group w-full p-2 rounded-2xl transition-all duration-300">
            <div class="bg-white p-2.5 rounded-xl shadow-[0_0_15px_rgba(37,99,235,0.3)] group-hover:shadow-[0_0_20px_rgba(37,99,235,0.6)] group-hover:scale-105 transition-all duration-300">
                <x-application-logo class="block h-9 w-auto fill-current text-blue-800" />
            </div>
            <div class="flex flex-col">
                <span class="text-white font-extrabold text-lg leading-none tracking-wide group-hover:text-blue-200 transition-colors">SMPN 3</span>
                <span class="text-blue-300 font-bold text-xs leading-tight tracking-[0.2em] mt-1">LAKBOK</span>
            </div>
        </a>
    </div>

    {{-- SCROLLABLE MENU --}}
    <div class="relative z-10 flex-1 overflow-y-auto py-6 px-4 space-y-8 custom-scrollbar">
        
         {{-- GRUP 1: MENU UTAMA --}}
        <div>
            <h3 class="px-4 text-[10px] font-black text-blue-300/50 uppercase tracking-widest mb-3 ml-1">Menu Utama</h3>
            
            <div class="space-y-1.5">
                {{-- Dashboard --}}
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50 ring-1 ring-blue-400/30' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                    @if(request()->routeIs('dashboard'))
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-yellow-400"></div>
                    @endif
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-blue-300 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    <span class="text-sm font-semibold tracking-wide">Dashboard</span>
                </a>

                {{-- Scan Aktifitas --}}
                <a href="{{ route('scan.show') }}" 
                   class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden {{ request()->routeIs('scan.show') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50 ring-1 ring-blue-400/30' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                   @if(request()->routeIs('scan.show'))
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-yellow-400"></div>
                   @endif
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('scan.show') ? 'text-white' : 'text-blue-300 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4h2v-4zM6 8v4M6 20v-4M2 20h4M2 4h4M2 12h2m8 0h2M2 8v4M2 16h2M6 16h2M6 12h4m0-8h4m4 0h4M14 8h-2M10 8h2M10 4h2m4 0h2M18 8h2m0 4h2M18 16h2m-2 4h2M2 12v4m0 4v-4m10-4v4m2-4v4m4-4v4M6 4v4m12 0v4"></path></svg>
                    <span class="text-sm font-semibold tracking-wide">Scan Aktifitas</span>
                </a>
                
                {{-- Rekap Harian --}}
                <a href="{{ route('reports.daily') }}" 
                   class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden {{ request()->routeIs('reports.daily') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50 ring-1 ring-blue-400/30' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                   @if(request()->routeIs('reports.daily'))
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-yellow-400"></div>
                   @endif
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('reports.daily') ? 'text-white' : 'text-blue-300 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    <span class="text-sm font-semibold tracking-wide">Rekap Harian</span>
                </a>
                
                {{-- Rekap Keagamaan --}}
                <a href="{{ route('reports.religious') }}" 
                   class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden {{ request()->routeIs('reports.religious') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50 ring-1 ring-blue-400/30' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                   @if(request()->routeIs('reports.religious'))
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-yellow-400"></div>
                   @endif
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('reports.religious') ? 'text-white' : 'text-blue-300 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    <span class="text-sm font-semibold tracking-wide">Rekap Keagamaan</span>
                </a>

                {{-- Catatan Disiplin --}}
                <a href="{{ route('discipline.index') }}" 
                   class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden {{ request()->routeIs('discipline.index') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50 ring-1 ring-blue-400/30' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                   @if(request()->routeIs('discipline.index'))
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-yellow-400"></div>
                   @endif
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('discipline.index') ? 'text-white' : 'text-blue-300 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="text-sm font-semibold tracking-wide">Catatan Disiplin</span>
                </a>
                
                {{-- Pusat Informasi --}}
                <a href="{{ route('announcements.index') }}" 
                   class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden {{ request()->routeIs('announcements.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50 ring-1 ring-blue-400/30' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                   @if(request()->routeIs('announcements.*'))
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-yellow-400"></div>
                   @endif
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('announcements.*') ? 'text-white' : 'text-blue-300 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                    <span class="text-sm font-semibold tracking-wide">Pusat Informasi</span>
                </a>
            </div>
        </div>

        {{-- SEPARATOR --}}
        <div class="border-t border-white/10 mx-4"></div>

        {{-- GRUP 2: AKADEMIK --}}
        <div>
            <h3 class="px-4 text-[10px] font-black text-blue-300/50 uppercase tracking-widest mb-3 ml-1">Akademik</h3>
            <div class="space-y-1.5">
                {{-- Input Nilai & E-Rapor --}}
                <a href="{{ route('grades.index') }}" 
                   class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden {{ request()->routeIs('grades.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50 ring-1 ring-blue-400/30' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                   @if(request()->routeIs('grades.*'))
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-yellow-400"></div>
                   @endif
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('grades.*') ? 'text-white' : 'text-blue-300 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span class="text-sm font-semibold tracking-wide">Input Nilai & Rapor</span>
                </a>
            </div>
        </div>

        {{-- SEPARATOR --}}
        <div class="border-t border-white/10 mx-4"></div>

        {{-- GRUP 3: PERPUSTAKAAN --}}
        <div>
            <h3 class="px-4 text-[10px] font-black text-blue-300/50 uppercase tracking-widest mb-3 ml-1">Perpustakaan</h3>
            <div class="space-y-1.5">
                
                {{-- Dashboard Perpus --}}
                <a href="{{ route('library.dashboard') }}" 
                   class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden {{ request()->routeIs('library.dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50 ring-1 ring-blue-400/30' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                   @if(request()->routeIs('library.dashboard'))
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-yellow-400"></div>
                   @endif
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('library.dashboard') ? 'text-white' : 'text-blue-300 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    <span class="text-sm font-semibold tracking-wide">Dashboard Perpus</span>
                </a>

                {{-- Sirkulasi (BARU) --}}
                <a href="{{ route('library.circulation.index') }}" 
                   class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden {{ request()->routeIs('library.circulation.index') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50 ring-1 ring-blue-400/30' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                   @if(request()->routeIs('library.circulation.index'))
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-yellow-400"></div>
                   @endif
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('library.circulation.index') ? 'text-white' : 'text-blue-300 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                    <span class="text-sm font-semibold tracking-wide">Sirkulasi</span>
                </a>

                {{-- Data Buku --}}
                <a href="{{ route('library.books.index') }}" 
                   class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden {{ request()->routeIs('library.books.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50 ring-1 ring-blue-400/30' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                   @if(request()->routeIs('library.books.*'))
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-yellow-400"></div>
                   @endif
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('library.books.*') ? 'text-white' : 'text-blue-300 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    <span class="text-sm font-semibold tracking-wide">Data Buku</span>
                </a>
            </div>
        </div>

        {{-- SEPARATOR --}}
        <div class="border-t border-white/10 mx-4"></div>

        {{-- GRUP 4: ADMINISTRASI --}}
        <div>
            <h3 class="px-4 text-[10px] font-black text-blue-300/50 uppercase tracking-widest mb-3 ml-1">Administrasi</h3>
            
            <div class="space-y-1.5">

                {{-- Pengaturan Akademik --}}
                <a href="{{ route('settings.academic.index') }}" 
                class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden {{ request()->routeIs('settings.academic.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50 ring-1 ring-blue-400/30' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                
                @if(request()->routeIs('settings.academic.*'))
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-yellow-400"></div>
                @endif

                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('settings.academic.*') ? 'text-white' : 'text-blue-300 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span class="text-sm font-semibold tracking-wide">Tahun Ajaran</span>
                </a>

                {{-- Data Mata Pelajaran --}}
                <a href="{{ route('subjects.index') }}" 
                    class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden {{ request()->routeIs('subjects.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50 ring-1 ring-blue-400/30' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                     @if(request()->routeIs('subjects.*'))
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-yellow-400"></div>
                    @endif
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('subjects.*') ? 'text-white' : 'text-blue-300 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        <span class="text-sm font-semibold tracking-wide">Mata Pelajaran</span>
                </a>

                {{-- Atur Jadwal --}}
                <a href="{{ route('schedules.index') }}" 
                   class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden {{ request()->routeIs('schedules.index') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50 ring-1 ring-blue-400/30' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                   @if(request()->routeIs('schedules.index'))
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-yellow-400"></div>
                   @endif
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('schedules.index') ? 'text-white' : 'text-blue-300 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span class="text-sm font-semibold tracking-wide">Atur Jadwal</span>
                </a>

                {{-- Data Kelas --}}
                <a href="{{ route('classes.index') }}" 
                   class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden {{ request()->routeIs('classes.index') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50 ring-1 ring-blue-400/30' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                   @if(request()->routeIs('classes.index'))
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-yellow-400"></div>
                   @endif
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('classes.index') ? 'text-white' : 'text-blue-300 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    <span class="text-sm font-semibold tracking-wide">Data Kelas</span>
                </a>

                {{-- Data Siswa --}}
                <a href="{{ route('students.index') }}" 
                   class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden {{ request()->routeIs('students.index') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50 ring-1 ring-blue-400/30' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                   @if(request()->routeIs('students.index'))
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-yellow-400"></div>
                   @endif
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('students.index') ? 'text-white' : 'text-blue-300 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span class="text-sm font-semibold tracking-wide">Data Siswa</span>
                </a>

                {{-- Data Pengguna --}}
                <a href="{{ route('users.index') }}" 
                   class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden {{ request()->routeIs('users.index') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50 ring-1 ring-blue-400/30' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                   @if(request()->routeIs('users.index'))
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-yellow-400"></div>
                   @endif
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('users.index') ? 'text-white' : 'text-blue-300 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="text-sm font-semibold tracking-wide">Data Pengguna</span>
                </a>
                
                {{-- Jenis Pelanggaran --}}
                <a href="{{ route('discipline-types.index') }}" 
                   class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden {{ request()->routeIs('discipline-types.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50 ring-1 ring-blue-400/30' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                   @if(request()->routeIs('discipline-types.*'))
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-yellow-400"></div>
                   @endif
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('discipline-types.*') ? 'text-white' : 'text-blue-300 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                    <span class="text-sm font-semibold tracking-wide">Jenis Pelanggaran</span>
                </a>
            </div>
        </div>
        
        <!-- Footer Info -->
        <div class="mt-8 px-4 text-center">
             <div class="bg-blue-800/30 rounded-lg p-3 border border-blue-700/30">
                 <p class="text-[10px] text-blue-300 uppercase tracking-widest font-bold">Ri..Versi Sistem</p>
                 <p class="text-xs text-white font-mono mt-1">v2.4.0 Beta</p>
             </div>
        </div>

        <div class="h-12"></div>
    </div>
</nav>