<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-elevate-dark leading-tight">
            {{ __('Laporan PPDB') }}
        </h2>
    </x-slot>

    {{-- CUSTOM STYLES ELEVATE --}}
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        @keyframes wiggle { 0%, 100% { transform: rotate(0deg); } 25% { transform: rotate(-10deg); } 75% { transform: rotate(10deg); } }
        .group:hover .animate-wiggle { animation: wiggle 0.5s ease-in-out; }
    </style>

    <div class="py-8 sm:py-10 font-sans text-elevate-dark bg-elevate-surface min-h-screen relative overflow-hidden pb-20">
        
        {{-- Efek Latar Belakang Halus --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            {{-- HEADER ELEVATE --}}
            <div class="animate-enter relative rounded-[2rem] bg-gradient-to-r from-elevate-accent via-elevate-peach-light to-elevate-peach p-8 md:p-10 mb-8 text-elevate-dark shadow-xl shadow-elevate-accent/20 overflow-hidden border border-white/60 group">
                <div class="absolute -top-10 -left-10 w-56 h-56 bg-elevate-primary/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-elevate-peach/40 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-xl"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <a href="{{ route('ppdb.index') }}" class="group/btn bg-white/60 hover:bg-white text-elevate-dark px-5 py-3 rounded-xl font-bold text-sm backdrop-blur-md border border-white/60 transition-all flex items-center gap-2 shadow-sm w-fit mb-4 active:scale-95">
                            <i class="ph-bold ph-arrow-left text-lg group-hover/btn:-translate-x-1 transition-transform"></i>
                            <span>Kembali ke Data</span>
                        </a>
                        <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-2">
                            Laporan & Analitik PPDB
                        </h2>
                        <p class="text-elevate-dark/80 text-sm font-semibold max-w-lg leading-relaxed">
                            Ringkasan statistik pendaftaran siswa baru secara real-time.
                        </p>
                    </div>
                </div>
            </div>

            {{-- STATISTIK UTAMA (GRID 4) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                
                <div class="animate-enter bg-white p-6 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/40 flex flex-col justify-between group hover:-translate-y-1 transition-transform">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-elevate-soft text-elevate-primary flex items-center justify-center text-2xl group-hover:scale-110 transition-transform shadow-sm">
                            <i class="ph-duotone ph-users-three"></i>
                        </div>
                        <span class="px-2 py-1 bg-elevate-soft text-elevate-primary text-[10px] font-bold rounded-lg uppercase">Total</span>
                    </div>
                    <div>
                        <h3 class="text-4xl font-black text-elevate-dark mb-1">{{ $stats['total'] }}</h3>
                        <p class="text-sm font-bold text-slate-500">Pendaftar Masuk</p>
                    </div>
                </div>

                <div class="animate-enter delay-100 bg-white p-6 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/40 flex flex-col justify-between group hover:-translate-y-1 transition-transform">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-[#DFF6DD] text-[#107C10] flex items-center justify-center text-2xl group-hover:scale-110 transition-transform shadow-sm">
                            <i class="ph-duotone ph-check-circle"></i>
                        </div>
                        <span class="px-2 py-1 bg-[#DFF6DD] text-[#107C10] text-[10px] font-bold rounded-lg uppercase">Lolos</span>
                    </div>
                    <div>
                        <h3 class="text-4xl font-black text-[#107C10] mb-1">{{ $stats['diterima'] }}</h3>
                        <p class="text-sm font-bold text-slate-500">Siswa Diterima</p>
                    </div>
                </div>

                <div class="animate-enter delay-200 bg-white p-6 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/40 flex flex-col justify-between group hover:-translate-y-1 transition-transform">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-[#FFEFD6] text-[#D83B01] flex items-center justify-center text-2xl group-hover:scale-110 transition-transform shadow-sm animate-pulse">
                            <i class="ph-duotone ph-clock-countdown"></i>
                        </div>
                        <span class="px-2 py-1 bg-[#FFEFD6] text-[#D83B01] text-[10px] font-bold rounded-lg uppercase">Pending</span>
                    </div>
                    <div>
                        <h3 class="text-4xl font-black text-[#D83B01] mb-1">{{ $stats['menunggu'] }}</h3>
                        <p class="text-sm font-bold text-slate-500">Belum Verifikasi</p>
                    </div>
                </div>

                <div class="animate-enter delay-200 bg-white p-6 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/40 flex flex-col justify-between group hover:-translate-y-1 transition-transform">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-[#FDE7E9] text-[#D13438] flex items-center justify-center text-2xl group-hover:scale-110 transition-transform shadow-sm">
                            <i class="ph-duotone ph-x-circle"></i>
                        </div>
                        <span class="px-2 py-1 bg-[#FDE7E9] text-[#D13438] text-[10px] font-bold rounded-lg uppercase">Gagal</span>
                    </div>
                    <div>
                        <h3 class="text-4xl font-black text-[#D13438] mb-1">{{ $stats['ditolak'] }}</h3>
                        <p class="text-sm font-bold text-slate-500">Siswa Ditolak</p>
                    </div>
                </div>

            </div>

            {{-- CHART & ACTIONS --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- Donut Chart --}}
                <div class="animate-enter delay-200 lg:col-span-2 bg-white rounded-[2.5rem] border border-slate-100 p-8 shadow-xl shadow-slate-200/40 flex flex-col">
                    <h3 class="text-xl font-black text-elevate-dark flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-elevate-soft text-elevate-primary flex items-center justify-center shadow-sm border border-slate-100"><i class="ph-bold ph-chart-pie-slice"></i></div>
                        Sebaran Jalur Pendaftaran
                    </h3>
                    <div class="flex-1 w-full relative min-h-[300px]">
                        <canvas id="trackChart"></canvas>
                    </div>
                </div>

                {{-- Action Export --}}
                <div class="animate-enter delay-200 bg-white rounded-[2.5rem] border border-slate-100 p-8 shadow-xl shadow-slate-200/40 flex flex-col justify-between">
                    <div>
                        <h3 class="text-xl font-black text-elevate-dark flex items-center gap-3 mb-2">
                            <div class="w-10 h-10 rounded-xl bg-elevate-peach-light/40 text-elevate-peach-dark flex items-center justify-center shadow-sm border border-elevate-peach/30"><i class="ph-bold ph-download-simple"></i></div>
                            Export Data
                        </h3>
                        <p class="text-sm text-slate-500 font-semibold mb-6">Unduh data pendaftar dalam format Excel (.xlsx) untuk keperluan arsip atau pencetakan surat massal.</p>
                        
                        <div class="space-y-4">
                            <a href="{{ route('ppdb.export', ['status' => 'Diterima']) }}" class="w-full bg-[#DFF6DD] text-[#107C10] border border-[#B7DFB9] py-3.5 px-4 rounded-2xl flex items-center justify-between font-bold hover:bg-[#107C10] hover:text-white transition-colors shadow-sm active:scale-95">
                                <span class="flex items-center gap-2"><i class="ph-bold ph-check-circle"></i> Data Diterima</span>
                                <i class="ph-bold ph-download"></i>
                            </a>
                            <a href="{{ route('ppdb.export') }}" class="w-full bg-elevate-soft text-elevate-primary border border-slate-200 py-3.5 px-4 rounded-2xl flex items-center justify-between font-bold hover:bg-elevate-primary hover:text-white transition-colors shadow-sm active:scale-95">
                                <span class="flex items-center gap-2"><i class="ph-bold ph-users"></i> Semua Pendaftar</span>
                                <i class="ph-bold ph-download"></i>
                            </a>
                        </div>
                    </div>
                    
                    <div class="mt-8 pt-6 border-t border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Dokumen</p>
                        <a href="#" class="w-full flex justify-center items-center gap-2 py-3.5 bg-white border-2 border-slate-200 text-elevate-dark font-bold rounded-2xl hover:bg-elevate-soft transition-all shadow-sm active:scale-95 text-sm">
                            <i class="ph-bold ph-files"></i> Cetak Surat Massal
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Script Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('trackChart');
            if(ctx) {
                const trackData = {
                    zonasi: {{ $trackStats['zonasi'] ?? 0 }},
                    prestasi: {{ $trackStats['prestasi'] ?? 0 }},
                    afirmasi: {{ $trackStats['afirmasi'] ?? 0 }},
                    pindah: {{ $trackStats['pindah_tugas'] ?? 0 }}
                };

                new Chart(ctx.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Zonasi', 'Prestasi', 'Afirmasi', 'Pindah Tugas'],
                        datasets: [{
                            data: [trackData.zonasi, trackData.prestasi, trackData.afirmasi, trackData.pindah],
                            // Menggunakan Palette Elevate
                            backgroundColor: ['#0d52a1', '#107C10', '#D83B01', '#2c3f61'], 
                            borderWidth: 0, 
                            hoverOffset: 10
                        }]
                    },
                    options: {
                        cutout: '75%',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { 
                            legend: { 
                                position: 'right',
                                labels: {
                                    font: { family: 'Figtree, sans-serif', size: 12, weight: 'bold' },
                                    color: '#2c3f61',
                                    usePointStyle: true,
                                    padding: 20
                                }
                            } 
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>