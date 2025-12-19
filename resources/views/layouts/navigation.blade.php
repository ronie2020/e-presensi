<nav 
    x-cloak
    class="fixed inset-y-0 left-0 w-72 h-screen flex-shrink-0 flex flex-col transition-transform duration-300 ease-in-out z-50 md:static md:translate-x-0 bg-gray-900 text-white shadow-2xl overflow-hidden"
    :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}">
    
    <!-- BACKGROUND DECORATION -->
    <div class="absolute inset-0 z-0 pointer-events-none">
        <div class="absolute inset-0 bg-gradient-to-b from-blue-900 via-blue-800 to-slate-900"></div>
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
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
        
        @php
            // DEFINISI MENU ARRAY
            $menus = [
                'Menu Utama' => [
                    [
                        'name' => 'Dashboard',
                        'route' => 'dashboard',
                        'icon' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2zz'
                    ],
                    [
                        'name' => 'Scan Aktifitas',
                        'route' => 'scan.show',
                        'icon' => 'M12 4v1m6 11h2m-6 0h-2v4h2v-4zM6 8v4M6 20v-4M2 20h4M2 4h4M2 12h2m8 0h2M2 8v4M2 16h2M6 16h2M6 12h4m0-8h4m4 0h4M14 8h-2M10 8h2M10 4h2m4 0h2M18 8h2m0 4h2M18 16h2m-2 4h2M2 12v4m0 4v-4m10-4v4m2-4v4m4-4v4M6 4v4m12 0v4'
                    ],
                    [
                        'name' => 'Rekap Harian',
                        'route' => 'reports.daily',
                        'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'
                    ],
                ],
                'E-Learning (LMS)' => [
                    [
                        'name' => 'Materi Pelajaran',
                        'route' => 'lms.materials.index',
                        'active_check' => 'lms.materials.*',
                        'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'
                    ],
                    [
                        'name' => 'Tugas & PR',
                        'route' => 'lms.assignments.index',
                        'active_check' => 'lms.assignments.*',
                        'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'
                    ],
                    [
                        'name' => 'Rekap Nilai', 
                        'route' => 'lms.grades.index',
                        'active_check' => 'lms.grades.*',
                        'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'
                    ],
                ],
                'Akademik' => [
                    [
                        'name' => 'Manajemen Kelulusan',
                        'route' => 'admin.graduation.index',
                        'active_check' => 'admin.graduation.*',
                        'icon' => 'M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z'
                    ],
                    [
                        'name' => 'Jurnal Mengajar',
                        'route' => 'teaching.index',
                        'active_check' => ['teaching.index', 'teaching.show', 'teaching.start'], 
                        'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'
                    ],
                     [
                        'name' => 'Riwayat Mengajar',
                        'route' => 'teaching.history',
                        'active_check' => 'teaching.history',
                        'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'
                    ],
                    [
                        'name' => 'Monitoring Jurnal',
                        'route' => 'reports.teaching_journal',
                        'active_check' => 'reports.teaching_journal',
                        'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'
                    ],
                    [
                        'name' => 'Input Nilai & Rapor',
                        'route' => 'grades.index',
                        'active_check' => 'grades.*',
                        'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'
                    ],
                    [
                        'name' => 'CBT / Ujian Online',
                        'route' => 'cbt.index',
                        'active_check' => 'cbt.*',
                        'icon' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'
                    ]
                ],
                'Kesiswaan' => [
                     [
                        'name' => 'Catatan Disiplin',
                        'route' => 'discipline.index',
                        'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'
                    ],
                    [
                        'name' => 'Data Siswa',
                        'route' => 'students.index',
                        'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'
                    ],
                     [
                        'name' => 'Prestasi',
                        'route' => 'achievements.index',
                        'active_check' => 'achievements.*',
                        'icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z'
                    ],
                ],
                'Ekstrakurikuler' => [
                    [
                        'name' => 'Data & Jadwal',
                        'route' => 'extracurriculars.index',
                        'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'
                    ],
                    [
                        'name' => 'Peserta Ekskul',
                        'route' => 'extracurriculars.members',
                        'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'
                    ],
                ],
                'Persuratan & Dinas' => [
                    [
                        'name' => 'Surat Masuk',
                        'route' => 'letters.incoming.index',
                        'active_check' => 'letters.incoming.*',
                        'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'
                    ],
                    [
                        'name' => 'Surat Tugas (SPT)',
                        'route' => 'letters.spt.index',
                        'active_check' => 'letters.spt.*',
                        'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9 2 2 4-4'
                    ],
                    [
                        'name' => 'Input SPPD',
                        'route' => 'sppd.index',
                        'active_check' => 'sppd.*',
                        'icon' => 'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0121 18.382V7.618a1 1 0 01-1.447-.894L15 7m0 13V7m0 0L9.5 3.5'
                    ],
                ],
                'Informasi Sekolah' => [
                    [
                        'name' => 'Papan Pengumuman',
                        'route' => 'announcements.index',
                        'active_check' => 'announcements.*',
                        'icon' => 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z'
                    ],
                    [
                        'name' => 'Agenda Kegiatan',
                        'route' => 'agendas.index',
                        'active_check' => 'agendas.*',
                        'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'
                    ],
                    [
                        'name' => 'Galeri Aktifitas',
                        'route' => 'activities.index',
                        'active_check' => 'activities.*',
                        'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'
                    ],
                ],
                'Perpustakaan' => [
                    [
                        'name' => 'Dashboard Pustaka',
                        'route' => 'library.dashboard',
                        'icon' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z'
                    ],
                    [
                        'name' => 'Sirkulasi',
                        'route' => 'library.circulation.index',
                        'icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4'
                    ],
                    [
                        'name' => 'Data Buku',
                        'route' => 'library.books.index',
                        'active_check' => 'library.books.*',
                        'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'
                    ]
                ],
                'Administrasi' => [
                    [
                        'name' => 'Tahun Ajaran',
                        'route' => 'settings.academic.index',
                        'active_check' => 'settings.academic.*',
                        'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'
                    ],
                    [
                        'name' => 'Mata Pelajaran',
                        'route' => 'subjects.index',
                        'active_check' => 'subjects.*',
                        'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'
                    ],
                    [
                        'name' => 'Atur Jadwal',
                        'route' => 'schedules.index',
                        'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'
                    ],
                    [
                        'name' => 'Data Kelas',
                        'route' => 'classes.index',
                        'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'
                    ],
                    [
                        'name' => 'Data Pengguna',
                        'route' => 'users.index',
                        'icon' => 'M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z'
                    ],
                    [
                        'name' => 'Jenis Pelanggaran',
                        'route' => 'discipline-types.index',
                        'active_check' => 'discipline-types.*',
                        'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'
                    ]
                ]
            ];
        @endphp

        {{-- LOOPING MENU OTOMATIS --}}
        @foreach($menus as $groupTitle => $items)
            <div>
                <h3 class="px-4 text-[10px] font-black text-blue-300/50 uppercase tracking-widest mb-3 ml-1">
                    {{ $groupTitle }}
                </h3>
                <div class="space-y-1.5">
                    @foreach($items as $item)
                        @php
                            // Cek apakah route aktif (mendukung wildcard *)
                            $checkRoute = $item['active_check'] ?? $item['route'];
                            if (is_array($checkRoute)) {
                                $isActive = false;
                                foreach ($checkRoute as $route) {
                                    if (request()->routeIs($route)) {
                                        $isActive = true;
                                        break;
                                    }
                                }
                            } else {
                                $isActive = request()->routeIs($checkRoute);
                            }
                        @endphp

                        <a href="{{ route($item['route']) }}" 
                           class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden {{ $isActive ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50 ring-1 ring-blue-400/30' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                            
                            @if($isActive)
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-yellow-400"></div>
                            @endif
                            
                            <svg class="w-5 h-5 mr-3 {{ $isActive ? 'text-white' : 'text-blue-300 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"></path>
                            </svg>
                            <span class="text-sm font-semibold tracking-wide">{{ $item['name'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
            
            {{-- Separator antar grup --}}
            @if(!$loop->last)
                <div class="border-t border-white/10 mx-4 my-4"></div>
            @endif
        @endforeach
        
        <!-- Footer Info -->
        <div class="mt-8 px-4 text-center">
             <div class="bg-blue-800/30 rounded-lg p-3 border border-blue-700/30">
                 <p class="text-[10px] text-blue-300 uppercase tracking-widest font-bold">Ri..Versi Sistem</p>
                 <p class="text-xs text-white font-mono mt-1">v4.9.0 Beta</p>
             </div>
        </div>

        <div class="h-12"></div>
    </div>
</nav>