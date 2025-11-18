<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Icon (Phosphor Icons) -->
        <script src="https://unpkg.com/@phosphor-icons/web@2.0.3"></script>

        <style>
            /* Simple styling for the sidebar navigation */
            .sidebar-link {
                display: flex;
                align-items: center;
                padding: 0.75rem 1.5rem; /* 12px 24px */
                border-radius: 0.5rem; /* rounded-lg */
                font-size: 0.875rem; /* text-sm */
                font-weight: 500; /* medium */
                color: #dbeafe; /* text-blue-200 */
                transition: background-color 0.2s;
            }
            .sidebar-link:hover {
                background-color: #1d4ed8; /* hover:bg-blue-700 */
                color: white;
            }
            .sidebar-link.active {
                background-color: #2563eb; /* bg-blue-600 */
                color: white;
            }
            .sidebar-link i {
                margin-right: 0.75rem; /* mr-3 */
                font-size: 1.125rem; /* text-lg */
            }
        </style>
        
        <!-- Stack untuk script khusus per halaman -->
        @stack('styles')
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <!-- 
          PERBAIKAN RESPONSIF: 
          'flex-col' (tumpuk di HP) 
          'md:flex-row' (sampingan di Desktop)
        -->
        <div class="min-h-screen flex flex-col md:flex-row bg-gray-100">
            
            <!-- 
              Kolom Sidebar Biru (Responsif) 
              PERBAIKAN RESPONSIF: 
              'w-full' (lebar penuh di HP) 
              'md:w-64' (lebar normal di Desktop)
              'md:flex-shrink-0' (agar tidak menciut)
            -->
            <aside class="w-full md:w-64 md:flex-shrink-0 bg-blue-800 text-white p-6 shadow-lg" style="background-color: #1e40af;">
                <div class="flex items-center gap-3 mb-8">
                    <!-- Ganti 'placehold.co' dengan logo sekolah Anda -->
                    <img src="{{ asset('images/logo_smp3.png') }}" alt="Logo Sekolah" class="h-10 w-10 bg-white rounded-full p-1">
                    <div>
                        <h2 class="text-sm font-semibold text-white">SMP NEGERI 3 LAKBOK</h2>
                        <p class="text-xs text-blue-200">Sistem Manajemen Kehadiran</p>
                    </div>
                </div>

                <nav class="space-y-2">
                    <span class="px-6 text-xs font-semibold uppercase text-blue-300">Akses Publik Tersedia</span>
                    
                    <a href="{{ route('portal.index') }}" 
                       class="sidebar-link {{ request()->routeIs('portal.*') ? 'active' : '' }}">
                        <i class="ph-fill ph-student"></i>
                        Portal Siswa
                    </a>
                    
                    <a href="{{ route('kiosk.show') }}" 
                       class="sidebar-link {{ request()->routeIs('kiosk.show') ? 'active' : '' }}">
                        <i class="ph-fill ph-identification-card"></i>
                        Mode Kiosk
                    </a>

                    <a href="{{ route('login') }}" 
                       class="sidebar-link {{ request()->routeIs('login') ? 'active' : '' }}">
                        <i class="ph-fill ph-house"></i>
                        Halaman Utama
                    </a>
                </nav>
            </aside>

            <!-- Kolom Konten Putih -->
            <main class="flex-1 p-6 lg:p-10 overflow-y-auto">
                <!-- 
                  PERBAIKAN ERROR '$student':
                  Kotak "Selamat Datang" sudah dihapus dari sini 
                  dan dipindahkan ke 'portal.show.blade.php'.
                -->
                @yield('content')
            </main>
        </div>

        <!-- Stack untuk script khusus per halaman -->
        @stack('scripts')
    </body>
</html>