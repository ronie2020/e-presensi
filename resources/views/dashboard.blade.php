{{-- Halaman ini adalah tampilan untuk resources/views/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Bagian Filter (Diperbarui) --}}
            <div class="p-6 bg-white shadow-sm sm:rounded-lg">
                <form action="{{ route('dashboard') }}" method="GET">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        
                        <!-- Filter Periode -->
                        <div>
                            <span class="font-medium text-gray-700">Periode Rekap:</span>
                            <span class="isolate inline-flex rounded-md shadow-sm ms-4">
                                <button type="submit" name="periode" value="Harian" class="relative inline-flex items-center px-3 py-2 text-sm font-semibold {{ $periode == 'Harian' ? 'bg-blue-600 text-white' : 'bg-white text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50' }} rounded-l-md">Harian</button>
                                <button type="submit" name="periode" value="Mingguan" class="relative -ms-px inline-flex items-center px-3 py-2 text-sm font-semibold {{ $periode == 'Mingguan' ? 'bg-blue-600 text-white' : 'bg-white text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50' }}">Mingguan</button>
                                <button type="submit" name="periode" value="Bulanan" class="relative -ms-px inline-flex items-center px-3 py-2 text-sm font-semibold {{ $periode == 'Bulanan' ? 'bg-blue-600 text-white' : 'bg-white text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50' }}">Bulanan</button>
                                <button type="submit" name="periode" value="Tahunan" class="relative -ms-px inline-flex items-center px-3 py-2 text-sm font-semibold {{ $periode == 'Tahunan' ? 'bg-blue-600 text-white' : 'bg-white text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50' }} rounded-r-md">Tahunan</button>
                            </span>
                        </div>
                        
                        <!-- Filter Tanggal -->
                        <div class="flex items-center space-x-2">
                            <label for="date" class="text-sm font-medium text-gray-700">Tanggal:</label>
                            <input type="date" name="date" id="date" value="{{ $selectedDate->format('Y-m-d') }}" class="rounded-md border-gray-300 shadow-sm sm:text-sm">
                        </div>

                        <!-- Filter Kelas -->
                        <div class="flex items-center space-x-2">
                            <label for="class_id" class="text-sm font-medium text-gray-700">Filter Kelas:</label>
                            <select name="class_id" id="class_id" class="rounded-md border-gray-300 shadow-sm sm:text-sm" onchange="this.form.submit()">
                                <option value="">Semua Kelas</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}" {{ $selectedClassId == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Bagian Kartu Statistik (Diperbarui 6 Kartu) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
                <!-- Kartu Total Siswa -->
                <div class="bg-white p-5 shadow-sm sm:rounded-lg border-t-4 border-gray-300">
                    <p class="text-sm font-medium text-gray-500 uppercase">Total Siswa</p>
                    <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $totalSiswa }}</p>
                </div>
                
                <!-- Kartu Total Hadir -->
                <div class="bg-white p-5 shadow-sm sm:rounded-lg border-t-4 border-green-500">
                    <p class="text-sm font-medium text-gray-500 uppercase">Total Hadir</p>
                    <p class="mt-1 text-3xl font-semibold text-green-600">{{ $totalHadir }}</p>
                </div>

                <!-- Kartu Belum Hadir -->
                <div class="bg-white p-5 shadow-sm sm:rounded-lg border-t-4 border-red-500">
                    <p class="text-sm font-medium text-gray-500 uppercase">Belum Hadir</p>
                    <p class="mt-1 text-3xl font-semibold text-red-600">{{ $totalBelumHadir }}</p>
                </div>

                <!-- Kartu Terlambat -->
                <div class="bg-white p-5 shadow-sm sm:rounded-lg border-t-4 border-orange-500">
                    <p class="text-sm font-medium text-gray-500 uppercase">Terlambat</p>
                    <p class="mt-1 text-3xl font-semibold text-orange-600">{{ $totalTerlambat }}</p>
                </div>

                <!-- Kartu Pulang Awal (BARU) -->
                <div class="bg-white p-5 shadow-sm sm:rounded-lg border-t-4 border-yellow-500">
                    <p class="text-sm font-medium text-gray-500 uppercase">Pulang Awal</p>
                    <p class="mt-1 text-3xl font-semibold text-yellow-600">{{ $totalPulangAwal }}</p>
                </div>

                <!-- Kartu Sakit/Izin/Alpa -->
                <div class="bg-white p-5 shadow-sm sm:rounded-lg border-t-4 border-blue-500">
                    <p class="text-sm font-medium text-gray-500 uppercase">Sakit/Izin/Alpa</p>
                    <p class="mt-1 text-3xl font-semibold text-blue-600">{{ $totalSakitIzinAlpa }}</p>
                </div>
            </div>

            {{-- Bagian Grafik (Diisi dengan Chart.js) --}}
            <div class="mt-6 grid grid-cols-1 md:grid-cols-5 gap-6">
                <!-- Bar Chart -->
                <div class="md:col-span-3 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium mb-4">Progres Kehadiran Mingguan</h3>
                        {{-- DIBERIKAN max-h-96 untuk membatasi tinggi agar tidak terlalu panjang --}}
                        <canvas id="barChart" class="h-64 max-h-96"></canvas> 
                    </div>
                </div>
                <!-- Donut Chart -->
                <div class="md:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium mb-4">Presentasi Kehadiran ({{ $periode }})</h3>
                         {{-- PERBAIKAN: Mengganti id="barChart" menjadi id="donutChart" --}}
                         <canvas id="donutChart" class="h-64 max-h-96"></canvas> 
                    </div>
                </div>
            </div>

            {{-- Bagian Tabel Siswa Perlu Perhatian (BARU) --}}
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium mb-4">Siswa Perlu Perhatian (Tahun Ini)</h3>
                    <p class="text-sm text-gray-600 mb-4">Daftar siswa dengan tingkat absensi (S/I/A), keterlambatan, atau pulang awal tertinggi.</p>
                    
                    <div class="overflow-x-auto border rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Absen (S/I/A)</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Terlambat</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pulang Awal</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($studentsPerluPerhatian as $student)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $student->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $student->student_id }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $student->schoolClass->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-red-600">{{ $student->total_alfa }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-orange-600">{{ $student->total_terlambat }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-yellow-600">{{ $student->total_pulang_awal }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                            Tidak ada siswa yang perlu perhatian khusus saat ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Script untuk Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Data dari Controller
            const donutData = @json($donutChartData);
            const barData = @json($barChartData);

            // 1. Inisialisasi Donut Chart
            // Menggunakan ID yang BENAR: donutChart
            const donutCtx = document.getElementById('donutChart').getContext('2d');
            if (donutCtx) {
                new Chart(donutCtx, {
                    type: 'doughnut',
                    data: {
                        labels: donutData.labels,
                        datasets: [{
                            data: donutData.data,
                            backgroundColor: [
                                'rgba(34, 197, 94, 1)',  // Tepat Waktu (green-500)
                                'rgba(234, 179, 8, 1)',  // Terlambat (yellow-500)
                                'rgba(239, 68, 68, 1)',  // Tdk Hadir (red-500)
                                'rgba(245, 158, 11, 1)', // Pulang Awal (amber-500)
                                'rgba(59, 130, 246, 1)', // SIA (blue-500)
                            ],
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                            }
                        }
                    }
                });
            }


            // 2. Inisialisasi Bar Chart
            // Menggunakan ID yang BENAR: barChart
            const barCtx = document.getElementById('barChart').getContext('2d');
            if (barCtx) {
                new Chart(barCtx, {
                    type: 'bar',
                    data: {
                        labels: barData.labels,
                        datasets: barData.datasets
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                stacked: true,
                            },
                            y: {
                                stacked: true,
                                beginAtZero: true
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'bottom',
                            }
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>