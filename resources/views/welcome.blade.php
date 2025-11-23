<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'SMP Negeri 3 Lakbok') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Load Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50">

    <!-- NAVBAR -->
    <nav class="bg-white shadow-sm sticky top-0 z-50 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <x-application-logo class="block h-10 w-auto fill-current text-blue-600" />
                    <span class="ml-3 text-xl font-bold text-gray-800 tracking-tight hidden md:block">SMP NEGERI 3 LAKBOK</span>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-blue-600">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none transition shadow-sm">
                            Login Guru/Staf
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <div class="relative bg-gradient-to-br from-blue-700 to-blue-900 overflow-hidden text-white">
        <div class="absolute inset-0 opacity-10">
             <svg class="h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0 100 C 20 0 50 0 100 100 Z" fill="white" /></svg>
        </div>
        <div class="relative max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center">
            <div class="md:w-1/2 mb-8 md:mb-0 text-center md:text-left">
                <span class="inline-block py-1 px-3 rounded-full bg-blue-500/30 text-blue-100 text-sm font-semibold mb-4 border border-blue-400/30">Sistem Informasi Terpadu</span>
                <h1 class="text-4xl font-extrabold tracking-tight sm:text-5xl mb-4">Transparansi & <br>Kedisiplinan</h1>
                <p class="text-blue-100 text-lg mb-8">Memantau perkembangan akademik dan aktivitas siswa secara real-time untuk kemajuan bersama.</p>
                
                <!-- Statistik Mini -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white/10 backdrop-blur p-3 rounded-lg border border-white/10">
                        <div class="text-2xl font-bold">{{ $stats['hadir'] ?? 0 }}</div>
                        <div class="text-xs uppercase opacity-70">Siswa Hadir Hari Ini</div>
                    </div>
                     <div class="bg-white/10 backdrop-blur p-3 rounded-lg border border-white/10">
                        <div class="text-2xl font-bold text-yellow-300">{{ $stats['terlambat'] ?? 0 }}</div>
                        <div class="text-xs uppercase opacity-70">Terlambat</div>
                    </div>
                </div>
            </div>

            <!-- GRAFIK PUBLIK -->
            <div class="md:w-1/2 w-full pl-0 md:pl-10">
                <div class="bg-white rounded-xl shadow-2xl p-6 text-gray-800">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-lg text-gray-700">Grafik Kehadiran Minggu Ini</h3>
                        <span class="text-xs font-semibold bg-green-100 text-green-800 px-2 py-1 rounded">Live Data</span>
                    </div>
                    <div class="h-64">
                         <canvas id="publicWeeklyChart"></canvas>
                    </div>
                    <p class="text-center text-xs text-gray-400 mt-4">*Data diperbarui secara otomatis setiap hari.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- MENU AKSES (Portal & Kiosk) -->
    <div class="bg-gray-50 py-12 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative -mt-24 z-10">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- Card 1: Portal Siswa -->
                <a href="{{ route('portal.index') }}" class="bg-white p-6 rounded-xl shadow-lg border border-gray-100 hover:shadow-2xl transition transform hover:-translate-y-1 group flex flex-col items-center text-center">
                    <div class="bg-blue-100 p-4 rounded-full text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition mb-4">
                        {{-- Ikon User (SVG Manual - PERBAIKAN) --}}
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Portal Siswa</h3>
                    <p class="text-sm text-gray-500 mt-2">Cek riwayat kehadiran dan poin pelanggaran siswa.</p>
                </a>

                <!-- Card 2: Mesin Absensi -->
                <a href="{{ route('kiosk.show') }}" class="bg-white p-6 rounded-xl shadow-lg border border-gray-100 hover:shadow-2xl transition transform hover:-translate-y-1 group flex flex-col items-center text-center">
                    <div class="bg-purple-100 p-4 rounded-full text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition mb-4">
                        {{-- Ikon QR Code (SVG Manual - PERBAIKAN) --}}
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h.75v.75h-.75v-.75zM13.5 19.5h.75v.75h-.75v-.75zM19.5 13.5h.75v.75h-.75v-.75zM19.5 19.5h.75v.75h-.75v-.75zM16.5 16.5h.75v.75h-.75v-.75z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Mesin Absensi</h3>
                    <p class="text-sm text-gray-500 mt-2">Mode Kiosk untuk scan kehadiran harian.</p>
                </a>

                <!-- Card 3: Login Guru -->
                <a href="{{ route('login') }}" class="bg-white p-6 rounded-xl shadow-lg border border-gray-100 hover:shadow-2xl transition transform hover:-translate-y-1 group flex flex-col items-center text-center">
                    <div class="bg-green-100 p-4 rounded-full text-green-600 group-hover:bg-green-600 group-hover:text-white transition mb-4">
                        {{-- Ikon Gembok (SVG Manual - PERBAIKAN) --}}
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Login Guru</h3>
                    <p class="text-sm text-gray-500 mt-2">Akses Dashboard Admin dan Manajemen.</p>
                </a>

            </div>
        </div>
    </div>

    <!-- PENGUMUMAN -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <h2 class="text-3xl font-extrabold text-gray-900">Pengumuman Terbaru</h2>
                <p class="mt-2 text-gray-500">Informasi terkini untuk siswa dan wali murid.</p>
            </div>

            <div class="grid gap-6 md:grid-cols-3">
                @forelse ($announcements as $item)
                    <div class="flex flex-col bg-white rounded-lg border border-gray-200 p-6 hover:shadow-lg transition">
                        <div class="text-xs text-blue-600 font-bold uppercase mb-2">{{ $item->created_at->format('d M Y') }}</div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">
                            <a href="#" onclick="openModal('{{ $item->id }}')" class="hover:text-blue-600">{{ $item->title }}</a>
                        </h3>
                        <p class="text-gray-600 text-sm line-clamp-3 mb-4">{{ Str::limit(strip_tags($item->content), 100) }}</p>
                        <button onclick="openModal('{{ $item->id }}')" class="text-blue-600 text-sm font-medium mt-auto self-start hover:underline">Baca Selengkapnya &rarr;</button>
                    </div>

                    <!-- Modal -->
                    <div id="modal-{{ $item->id }}" class="fixed inset-0 z-[60] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                        <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity" onclick="closeModal('{{ $item->id }}')"></div>
                        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
                            <div class="relative bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-2xl w-full">
                                <div class="bg-white px-6 pt-5 pb-4 sm:p-6">
                                    <div class="flex justify-between items-start mb-4">
                                        <h3 class="text-2xl font-bold text-gray-900">{{ $item->title }}</h3>
                                        <button onclick="closeModal('{{ $item->id }}')" class="text-gray-400 hover:text-gray-500"><span class="sr-only">Close</span>✕</button>
                                    </div>
                                    <div class="text-sm text-gray-500 mb-6 pb-4 border-b border-gray-100">Diposting pada {{ $item->created_at->format('d F Y, H:i') }}</div>
                                    <div class="prose max-w-none text-gray-700">{!! nl2br(e($item->content)) !!}</div>
                                </div>
                                <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-end">
                                    <button type="button" class="inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:text-sm" onclick="closeModal('{{ $item->id }}')">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-10 bg-gray-50 rounded-lg border border-dashed border-gray-300 text-gray-500">Belum ada pengumuman.</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="bg-gray-900 text-white border-t border-gray-800">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center">
            <div class="flex items-center mb-4 md:mb-0">
                <x-application-logo class="h-6 w-auto fill-current text-blue-500" />
                <span class="ml-2 font-bold tracking-wider">SMPN 3 LAKBOK</span>
            </div>
            <p class="text-gray-400 text-sm">&copy; {{ date('Y') }} Hak Cipta Dilindungi. @Ri..</p>
        </div>
    </footer>

    {{-- Script --}}
    <script>
        // Modal Functions
        function openModal(id) {
            document.getElementById('modal-' + id).classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        function closeModal(id) {
            document.getElementById('modal-' + id).classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Chart.js Initialization
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('publicWeeklyChart').getContext('2d');
            const chartData = @json($barChartData); // Data dari Controller

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartData.labels,
                    datasets: chartData.datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true, stacked: true, grid: { display: false } },
                        x: { stacked: true, grid: { display: false } }
                    },
                    plugins: {
                        legend: { position: 'bottom', labels: { boxWidth: 10 } }
                    }
                }
            });
        });
    </script>
</body>
</html>