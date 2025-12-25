<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-purple-100 text-purple-600 rounded-lg">
                <i class="ph-duotone ph-printer text-xl"></i>
            </div>
            <div>
                <h2 class="font-black text-2xl text-slate-800 leading-tight">
                    {{ __('Pusat Laporan & Unduhan') }}
                </h2>
                <p class="text-sm text-slate-500">Rekapitulasi data dan cetak dokumen massal.</p>
            </div>
        </div>
    </x-slot>

    {{-- Load Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- 1. STATISTIK VISUAL (REAL TIME) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Grafik Jalur Pendaftaran -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                    <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-chart-pie-slice text-blue-500"></i> Sebaran Jalur Pendaftaran
                    </h3>
                    
                    <div class="flex items-center gap-6">
                        <!-- Area Chart -->
                        <div class="h-48 w-48 relative">
                            <canvas id="trackChart"></canvas>
                        </div>
                        
                        <!-- Legend Manual -->
                        <div class="flex-1 space-y-3 text-sm">
                            <div class="flex justify-between items-center">
                                <span class="flex items-center gap-2 text-slate-600"><span class="w-3 h-3 rounded-full bg-blue-500"></span> Zonasi</span>
                                <span class="font-bold text-slate-800">{{ $trackStats['zonasi'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="flex items-center gap-2 text-slate-600"><span class="w-3 h-3 rounded-full bg-purple-500"></span> Prestasi</span>
                                <span class="font-bold text-slate-800">{{ $trackStats['prestasi'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="flex items-center gap-2 text-slate-600"><span class="w-3 h-3 rounded-full bg-orange-500"></span> Afirmasi</span>
                                <span class="font-bold text-slate-800">{{ $trackStats['afirmasi'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="flex items-center gap-2 text-slate-600"><span class="w-3 h-3 rounded-full bg-slate-500"></span> Pindah Tugas</span>
                                <span class="font-bold text-slate-800">{{ $trackStats['pindah_tugas'] }}</span>
                            </div>
                            <div class="pt-2 border-t border-slate-100 flex justify-between items-center font-bold">
                                <span class="text-slate-800">Total</span>
                                <span class="text-blue-600">{{ $totalRegistrants }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ringkasan Gender & Status -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex flex-col">
                    <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-users-three text-emerald-500"></i> Ringkasan Peserta
                    </h3>
                    <div class="flex-1 grid grid-cols-2 gap-4">
                        <!-- Laki-laki -->
                        <div class="bg-blue-50 rounded-xl p-4 flex flex-col justify-center items-center text-center border border-blue-100 group hover:bg-blue-100 transition">
                            <i class="ph-duotone ph-gender-male text-3xl text-blue-500 mb-2 group-hover:scale-110 transition-transform"></i>
                            <span class="text-3xl font-black text-slate-800">{{ $genderStats['L'] }}</span>
                            <span class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Laki-laki</span>
                        </div>
                        <!-- Perempuan -->
                        <div class="bg-pink-50 rounded-xl p-4 flex flex-col justify-center items-center text-center border border-pink-100 group hover:bg-pink-100 transition">
                            <i class="ph-duotone ph-gender-female text-3xl text-pink-500 mb-2 group-hover:scale-110 transition-transform"></i>
                            <span class="text-3xl font-black text-slate-800">{{ $genderStats['P'] }}</span>
                            <span class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Perempuan</span>
                        </div>
                        <!-- Total Diterima -->
                        <div class="col-span-2 bg-emerald-50 rounded-xl p-4 flex items-center justify-between border border-emerald-100 px-8 hover:bg-emerald-100 transition">
                            <div class="text-left">
                                <span class="block text-xs text-emerald-600 font-bold uppercase tracking-wide">Siswa Diterima</span>
                                <span class="block text-4xl font-black text-emerald-700">{{ $totalAccepted }}</span>
                            </div>
                            <div class="h-14 w-14 rounded-full bg-emerald-200 flex items-center justify-center text-emerald-700 shadow-sm">
                                <i class="ph-fill ph-check-circle text-3xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. MENU CETAK & EXPORT -->
            <div>
                <h3 class="text-lg font-bold text-slate-800 mb-4">Cetak Dokumen & Export Data</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    <!-- Export Excel -->
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition group relative overflow-hidden">
                        <div class="absolute right-0 top-0 p-10 opacity-5 group-hover:opacity-10 transition-opacity">
                            <i class="ph-fill ph-microsoft-excel-logo text-9xl text-green-600"></i>
                        </div>
                        <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center mb-4 relative z-10">
                            <i class="ph-fill ph-microsoft-excel-logo text-2xl"></i>
                        </div>
                        <h4 class="font-bold text-slate-800 text-lg relative z-10">Export Data CSV</h4>
                        <p class="text-sm text-slate-500 mt-1 mb-6 leading-relaxed relative z-10 h-10">Unduh seluruh database pendaftar format CSV (Excel Friendly).</p>
                        <a href="{{ route('admin.ppdb.export.excel') }}" class="block w-full py-3 rounded-xl border border-green-200 text-green-700 font-bold text-sm hover:bg-green-50 transition text-center relative z-10">
                            <i class="ph-bold ph-download-simple mr-2"></i> Download File
                        </a>
                    </div>

                    <!-- Laporan PDF -->
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition group relative overflow-hidden">
                        <div class="absolute right-0 top-0 p-10 opacity-5 group-hover:opacity-10 transition-opacity">
                            <i class="ph-fill ph-file-pdf text-9xl text-red-600"></i>
                        </div>
                        <div class="w-12 h-12 bg-red-100 text-red-600 rounded-xl flex items-center justify-center mb-4 relative z-10">
                            <i class="ph-fill ph-file-pdf text-2xl"></i>
                        </div>
                        <h4 class="font-bold text-slate-800 text-lg relative z-10">Laporan Rekapitulasi</h4>
                        <p class="text-sm text-slate-500 mt-1 mb-6 leading-relaxed relative z-10 h-10">Cetak laporan resmi rekapitulasi siswa baru untuk arsip dinas.</p>
                        <a href="{{ route('admin.ppdb.print.recap') }}" target="_blank" class="block w-full py-3 rounded-xl border border-red-200 text-red-700 font-bold text-sm hover:bg-red-50 transition text-center relative z-10">
                            <i class="ph-bold ph-printer mr-2"></i> Preview & Cetak
                        </a>
                    </div>

                    <!-- Cetak Massal Surat -->
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition group relative overflow-hidden">
                        <div class="absolute right-0 top-0 p-10 opacity-5 group-hover:opacity-10 transition-opacity">
                            <i class="ph-fill ph-files text-9xl text-blue-600"></i>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center mb-4 relative z-10">
                            <i class="ph-fill ph-envelope-open text-2xl"></i>
                        </div>
                        <h4 class="font-bold text-slate-800 text-lg relative z-10">Surat Kelulusan Massal</h4>
                        <p class="text-sm text-slate-500 mt-1 mb-6 leading-relaxed relative z-10 h-10">Cetak SKL otomatis untuk semua siswa berstatus 'Diterima'.</p>
                        <a href="{{ route('admin.ppdb.print.mass_letters') }}" target="_blank" class="block w-full py-3 rounded-xl bg-blue-600 text-white font-bold text-sm hover:bg-blue-700 transition text-center shadow-lg shadow-blue-500/20 relative z-10">
                            <i class="ph-bold ph-files mr-2"></i> Cetak Semua Surat
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- Script Chart.js -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('trackChart').getContext('2d');
            
            // Data dari Controller
            const trackData = {
                zonasi: {{ $trackStats['zonasi'] }},
                prestasi: {{ $trackStats['prestasi'] }},
                afirmasi: {{ $trackStats['afirmasi'] }},
                pindah: {{ $trackStats['pindah_tugas'] }}
            };

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Zonasi', 'Prestasi', 'Afirmasi', 'Pindah Tugas'],
                    datasets: [{
                        data: [trackData.zonasi, trackData.prestasi, trackData.afirmasi, trackData.pindah],
                        backgroundColor: [
                            '#3b82f6', // Blue 500
                            '#a855f7', // Purple 500
                            '#f97316', // Orange 500
                            '#64748b'  // Slate 500
                        ],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    cutout: '70%',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }, // Legend kita buat manual agar rapi
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    if (label) { label += ': '; }
                                    let value = context.raw;
                                    let total = context.chart._metasets[context.datasetIndex].total;
                                    let percentage = Math.round((value / total) * 100) + '%';
                                    return label + value + ' (' + percentage + ')';
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>