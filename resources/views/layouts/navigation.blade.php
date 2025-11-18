{{-- 
    Ini adalah file navigation.blade.php yang sudah diubah total 
    menjadi Sidebar Vertikal.
--}}
{{-- 
    DIPERBARUI: 
    - 'fixed' agar bisa overlay di mobile
    - 'transform -translate-x-full' agar tersembunyi di mobile
    - :class (Alpine.js) untuk memunculkan/menyembunyikan
    - 'md:static' & 'md:translate-x-0' agar terlihat normal di desktop
--}}
<nav 
    class="fixed inset-y-0 left-0 w-64 bg-white border-r border-gray-200 h-screen flex-shrink-0 flex flex-col transform -translate-x-full z-40 transition-transform duration-300 ease-in-out md:static md:translate-x-0"
    :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}">
    
    <!-- Logo -->
    <div class="shrink-0 flex items-center justify-center h-16 border-b">
        <a href="{{ route('dashboard') }}">
            <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
        </a>
    </div>

    {{-- Bagian Menu Utama (Bisa di-scroll jika menunya banyak) --}}
    <div class="flex-1 overflow-y-auto">
        <div class="py-4 px-3">
            <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Menu</h3>
            
            <div class="space-y-1">
                <!-- Navigation Links -->
                <x-nav-link-vertical :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    <x-heroicon-o-home class="w-5 h-5 mr-3"/>
                    {{ __('Dashboard') }}
                </x-nav-link-vertical>

                <x-nav-link-vertical :href="route('scan.show')" :active="request()->routeIs('scan.show')">
                    <x-heroicon-o-qr-code class="w-5 h-5 mr-3"/>
                    {{ __('Scan Aktifitas') }}
                </x-nav-link-vertical>
                
                <x-nav-link-vertical :href="route('reports.daily')" :active="request()->routeIs('reports.daily')">
                    <x-heroicon-o-calendar-days class="w-5 h-5 mr-3"/>
                    {{ __('Rekap Harian') }}
                </x-nav-link-vertical>

                <x-nav-link-vertical :href="route('discipline.index')" :active="request()->routeIs('discipline.index')">
                    <x-heroicon-o-exclamation-triangle class="w-5 h-5 mr-3"/>
                    {{ __('Catatan Disiplin') }}
                </x-nav-link-vertical>
            </div>
        </div>

        <div class="py-4 px-3">
            <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Manajemen</h3>
            
            <div class="space-y-1">
                <x-nav-link-vertical :href="route('schedules.index')" :active="request()->routeIs('schedules.index')">
                    <x-heroicon-o-clock class="w-5 h-5 mr-3"/>
                    {{ __('Manajemen Jadwal') }}
                </x-nav-link-vertical>

                <x-nav-link-vertical :href="route('classes.index')" :active="request()->routeIs('classes.index')">
                    <x-heroicon-o-building-library class="w-5 h-5 mr-3"/>
                    {{ __('Manajemen Kelas') }}
                </x-nav-link-vertical>

                <x-nav-link-vertical :href="route('students.index')" :active="request()->routeIs('students.index')">
                    <x-heroicon-o-users class="w-5 h-5 mr-3"/>
                    {{ __('Manajemen Siswa') }}
                </x-nav-link-vertical>

                <x-nav-link-vertical :href="route('users.index')" :active="request()->routeIs('users.index')">
                    <x-heroicon-o-user-group class="w-5 h-5 mr-3"/>
                    {{ __('Manajemen Pengguna') }}
                </x-nav-link-vertical>
            </div>
        </div>
    </div>
    
    <!-- Bagian User di Bawah (Opsional, seperti di referensi) -->
    <div class="border-t p-4">
        <div class="flex items-center">
            <img class="h-8 w-8 rounded-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=7F9CF5&background=EBF4FF" alt="Avatar">
            <div class="ml-3">
                <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
            </div>
        </div>
    </div>

    {{-- 
        File ini sengaja TIDAK SAYA SERTAKAN dropdown 'Responsive Navigation Menu' (Hamburger)
        karena layout sidebar baru ini tidak membutuhkannya lagi. 
        Dropdown User (Profile & Logout) sudah dipindah ke file app.blade.php.
    --}}
</nav>