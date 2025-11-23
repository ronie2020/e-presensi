<nav 
    class="fixed inset-y-0 left-0 w-72 h-screen flex-shrink-0 flex flex-col transition-transform duration-300 ease-in-out z-50 md:static md:translate-x-0 bg-blue-700"
    :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}">
    
    <!-- Header Logo: Lebih Besar & Modern -->
    <div class="shrink-0 flex items-center justify-center h-28 px-6">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-4 group w-full p-3 rounded-2xl hover:bg-white/5 transition-colors">
            <div class="bg-white p-2.5 rounded-xl shadow-md group-hover:scale-110 transition-transform duration-300">
                <x-application-logo class="block h-9 w-auto fill-current text-blue-700" />
            </div>
            <div class="flex flex-col">
                <span class="text-white font-extrabold text-lg leading-none tracking-wide">SMPN 3</span>
                <span class="text-blue-200 font-bold text-sm leading-tight tracking-widest">LAKBOK</span>
            </div>
        </a>
    </div>

    {{-- Menu Scrollable --}}
    <div class="flex-1 overflow-y-auto py-4 px-5 space-y-8 no-scrollbar">
        
         {{-- GRUP 1: MENU UTAMA --}}
        <div>
            <h3 class="px-4 text-[11px] font-extrabold text-blue-200/60 uppercase tracking-widest mb-4 ml-1">Menu Utama</h3>
            
            <div class="space-y-1">
                <x-nav-link-vertical :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    {{ __('Dashboard') }}
                </x-nav-link-vertical>

                <x-nav-link-vertical :href="route('scan.show')" :active="request()->routeIs('scan.show')">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4h2v-4zM6 8v4M6 20v-4M2 20h4M2 4h4M2 12h2m8 0h2M2 8v4M2 16h2M6 16h2M6 12h4m0-8h4m4 0h4M14 8h-2M10 8h2M10 4h2m4 0h2M18 8h2m0 4h2M18 16h2m-2 4h2M2 12v4m0 4v-4m10-4v4m2-4v4m4-4v4M6 4v4m12 0v4"></path></svg>
                    {{ __('Scan Aktifitas') }}
                </x-nav-link-vertical>
                
                <x-nav-link-vertical :href="route('reports.daily')" :active="request()->routeIs('reports.daily')">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    {{ __('Rekap Harian') }}
                </x-nav-link-vertical>
                
                <x-nav-link-vertical :href="route('reports.religious')" :active="request()->routeIs('reports.religious')">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    {{ __('Rekap Keagamaan') }}
                </x-nav-link-vertical>

                <x-nav-link-vertical :href="route('discipline.index')" :active="request()->routeIs('discipline.index')">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ __('Catatan Disiplin') }}
                </x-nav-link-vertical>
                
                <x-nav-link-vertical :href="route('announcements.index')" :active="request()->routeIs('announcements.*')">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                    {{ __('Pusat Informasi') }}
                </x-nav-link-vertical>
            </div>
        </div>

        {{-- GRUP 2: MANAJEMEN DATA --}}
        <div>
            <h3 class="px-4 text-[11px] font-extrabold text-blue-200/60 uppercase tracking-widest mb-4 ml-1">Administrasi</h3>
            
            <div class="space-y-1">
                <x-nav-link-vertical :href="route('schedules.index')" :active="request()->routeIs('schedules.index')">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    {{ __('Atur Jadwal') }}
                </x-nav-link-vertical>

                <x-nav-link-vertical :href="route('classes.index')" :active="request()->routeIs('classes.index')">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    {{ __('Data Kelas') }}
                </x-nav-link-vertical>

                <x-nav-link-vertical :href="route('students.index')" :active="request()->routeIs('students.index')">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    {{ __('Data Siswa') }}
                </x-nav-link-vertical>

                <x-nav-link-vertical :href="route('users.index')" :active="request()->routeIs('users.index')">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ __('Data Pengguna') }}
                </x-nav-link-vertical>
                
                <x-nav-link-vertical :href="route('discipline-types.index')" :active="request()->routeIs('discipline-types.*')">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                    {{ __('Jenis Pelanggaran') }}
                </x-nav-link-vertical>
            </div>
        </div>
        
        <!-- Spacer agar tombol logout tidak ketutup di mobile -->
        <div class="h-12"></div>
    </div>
</nav>