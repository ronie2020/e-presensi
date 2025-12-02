<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-black text-slate-800 tracking-tight flex items-center gap-2">
                        <i class="ph-duotone ph-books text-blue-600"></i> Dashboard Pustaka
                    </h1>
                    <p class="text-slate-500 mt-2 text-lg">Statistik dan manajemen perpustakaan sekolah.</p>
                </div>
                <!-- Indikator Hari Ini -->
                <div class="flex items-center gap-3 bg-white px-5 py-3 rounded-2xl border border-slate-200 shadow-sm">
                    <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 text-lg font-bold">
                        <i class="ph-fill ph-users-three"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase">Pengunjung Hari Ini</p>
                        <p class="text-xl font-black text-slate-800">{{ number_format($todayVisits) }} <span class="text-xs font-medium text-slate-400">Siswa</span></p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- KOLOM UTAMA (KIRI) -->
                <div class="lg:col-span-2 space-y-8">

                    <!-- 1. Pintasan Cepat -->
                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
                        <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                            <i class="ph-bold ph-lightning text-yellow-500"></i> Akses Cepat
                        </h2>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <a href="{{ route('library.circulation.index') }}" class="flex flex-col items-center justify-center p-4 bg-slate-50 hover:bg-blue-50 border border-slate-100 hover:border-blue-200 rounded-2xl transition-all group">
                                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm mb-3 group-hover:scale-110 transition-transform">
                                    <i class="ph-duotone ph-arrows-left-right text-2xl text-blue-600"></i>
                                </div>
                                <span class="font-bold text-slate-700 text-sm group-hover:text-blue-700">Sirkulasi</span>
                            </a>
                            <button onclick="searchMemberPopup()" class="flex flex-col items-center justify-center p-4 bg-slate-50 hover:bg-purple-50 border border-slate-100 hover:border-purple-200 rounded-2xl transition-all group">
                                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm mb-3 group-hover:scale-110 transition-transform">
                                    <i class="ph-duotone ph-user-focus text-2xl text-purple-600"></i>
                                </div>
                                <span class="font-bold text-slate-700 text-sm group-hover:text-purple-700">Cari Siswa</span>
                            </button>
                            <a href="{{ route('library.books.create') }}" class="flex flex-col items-center justify-center p-4 bg-slate-50 hover:bg-emerald-50 border border-slate-100 hover:border-emerald-200 rounded-2xl transition-all group">
                                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm mb-3 group-hover:scale-110 transition-transform">
                                    <i class="ph-duotone ph-book-medical text-2xl text-emerald-600"></i>
                                </div>
                                <span class="font-bold text-slate-700 text-sm group-hover:text-emerald-700">Input Buku</span>
                            </a>
                            <a href="{{ route('library.kiosk.index') }}" target="_blank" class="flex flex-col items-center justify-center p-4 bg-slate-50 hover:bg-orange-50 border border-slate-100 hover:border-orange-200 rounded-2xl transition-all group">
                                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm mb-3 group-hover:scale-110 transition-transform">
                                    <i class="ph-duotone ph-desktop text-2xl text-orange-600"></i>
                                </div>
                                <span class="font-bold text-slate-700 text-sm group-hover:text-orange-700">Mode Kiosk</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- 2. Statistik Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 p-6 rounded-3xl shadow-lg shadow-blue-500/20 text-white relative overflow-hidden group">
                            <div class="absolute right-0 top-0 opacity-10 transform translate-x-4 -translate-y-4 group-hover:scale-110 transition-transform duration-500">
                                <i class="ph-fill ph-books text-9xl"></i>
                            </div>
                            <div class="relative z-10">
                                <p class="text-blue-100 text-sm font-medium mb-1">Koleksi Buku</p>
                                <h3 class="text-4xl font-black tracking-tight">{{ number_format($totalBooks) }}</h3>
                                <div class="mt-4 inline-flex items-center gap-1 text-xs font-bold bg-white/20 px-2 py-1 rounded-lg backdrop-blur-sm">
                                    <i class="ph-bold ph-book-open"></i> Judul Terdaftar
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col justify-between relative overflow-hidden group">
                            <div class="absolute right-4 top-4 text-emerald-100 group-hover:text-emerald-50 transition-colors">
                                <i class="ph-duotone ph-users-three text-8xl"></i>
                            </div>
                            <div class="relative z-10">
                                <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 mb-3">
                                    <i class="ph-bold ph-user-check text-xl"></i>
                                </div>
                                <p class="text-slate-500 text-sm font-bold">Anggota Aktif</p>
                                <h3 class="text-3xl font-black text-slate-800">{{ number_format($activeMembers) }}</h3>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Grafik Statistik (Dual Tab) -->
                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                                <i class="ph-bold ph-chart-bar text-indigo-500"></i> Statistik Perpustakaan
                            </h2>
                            <!-- Toggle Chart -->
                            <div class="flex bg-slate-100 p-1 rounded-xl">
                                <button onclick="toggleChart('loans')" id="btn-loans" class="px-3 py-1.5 rounded-lg text-xs font-bold bg-white shadow-sm text-slate-800 transition-all">Peminjaman</button>
                                <button onclick="toggleChart('visits')" id="btn-visits" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-500 hover:text-slate-800 transition-all">Kunjungan</button>
                            </div>
                        </div>
                        <div class="h-64 w-full relative">
                             <!-- Canvas Chart -->
                             <canvas id="mainChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- SIDEBAR KANAN (1/3) -->
                <div class="lg:col-span-1 space-y-8">
                     
                     <!-- 4. Ringkasan Status -->
                     <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 space-y-6">
                        <h3 class="font-bold text-slate-800">Status Sirkulasi</h3>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="p-4 bg-indigo-50 rounded-2xl text-center border border-indigo-100">
                                <i class="ph-duotone ph-hand-holding text-2xl text-indigo-500 mb-1"></i>
                                <p class="text-2xl font-black text-slate-800">{{ $borrowedBooks }}</p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase">Dipinjam</p>
                            </div>
                            <div class="p-4 bg-rose-50 rounded-2xl text-center border border-rose-100">
                                <i class="ph-duotone ph-warning-circle text-2xl text-rose-500 mb-1"></i>
                                <p class="text-2xl font-black text-slate-800">{{ $overdueBooks }}</p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase">Terlambat</p>
                            </div>
                        </div>
                        <div class="pt-4 border-t border-dashed border-slate-200">
                            <div class="flex justify-between items-center text-sm mb-2">
                                <span class="text-slate-500">Anggota Meminjam</span>
                                <span class="font-bold text-slate-800">{{ $membersBorrowingCount }}</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: 45%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- 5. Aktivitas Terkini (SUDAH DIPERBAIKI: Support Kiosk & Pinjam) -->
                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-bold text-slate-800">Log Aktivitas</h2>
                            <a href="#" class="text-xs font-bold text-blue-600 hover:underline">Lihat Semua</a>
                        </div>
                        <div class="space-y-4">
                            @forelse($recentActivities as $activity)
                                <div class="flex gap-3">
                                    <div class="relative mt-1">
                                        @if($activity->type == 'visit')
                                            <!-- Icon Kunjungan/Kiosk -->
                                            <div class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-lg">
                                                <i class="ph-bold ph-door-open"></i>
                                            </div>
                                        @else
                                            <!-- Icon Sirkulasi -->
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-lg {{ $activity->status == 'returned' ? 'bg-emerald-100 text-emerald-600' : 'bg-blue-100 text-blue-600' }}">
                                                <i class="{{ $activity->status == 'returned' ? 'ph-bold ph-arrow-u-down-left' : 'ph-bold ph-arrow-u-right-up' }}"></i>
                                            </div>
                                            @if($activity->status != 'returned')
                                                <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-white rounded-full flex items-center justify-center">
                                                    <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-slate-800 truncate">{{ $activity->student->name }}</p>
                                        
                                        <!-- Logika Tampilan Deskripsi -->
                                        @if($activity->type == 'visit')
                                            <p class="text-xs text-slate-500 truncate flex items-center gap-1">
                                                <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span> Absensi Masuk
                                            </p>
                                        @else
                                            <p class="text-xs text-slate-500 truncate">{{ $activity->book->title }}</p>
                                        @endif
                                    </div>
                                    <span class="text-[10px] font-bold text-slate-400 whitespace-nowrap">
                                        {{ $activity->sort_time->diffForHumans(null, true) }}
                                    </span>
                                </div>
                            @empty
                                <div class="text-center py-6 text-slate-400 text-xs italic">Belum ada aktivitas hari ini.</div>
                            @endforelse
                        </div>
                    </div>

                    <!-- 6. Buku Populer -->
                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
                        <h2 class="text-lg font-bold text-slate-800 mb-4">Buku Terpopuler</h2>
                        <ul class="space-y-3">
                           @forelse($popularBooks as $book)
                                <li class="flex items-center justify-between group cursor-default">
                                    <div class="flex items-center gap-3 overflow-hidden">
                                        <span class="flex-shrink-0 w-6 h-6 bg-slate-100 text-slate-500 text-xs font-black rounded-lg flex items-center justify-center group-hover:bg-yellow-100 group-hover:text-yellow-600 transition-colors">{{ $loop->iteration }}</span>
                                        <p class="text-sm font-medium text-slate-600 truncate group-hover:text-slate-900">{{ $book->title }}</p>
                                    </div>
                                    <span class="text-xs font-bold bg-slate-50 px-2 py-1 rounded text-slate-500">{{ $book->borrowings_count }}x</span>
                                </li>
                           @empty
                                <p class="text-center text-slate-400 text-xs py-2">Data belum tersedia.</p>
                           @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script untuk Chart & SweetAlert --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Data dari Controller
            const loanLabels = @json($chartLabels);
            const loanData = @json($chartData);
            
            const visitLabels = @json($visitChartLabels);
            const visitData = @json($visitChartData);

            const ctx = document.getElementById('mainChart').getContext('2d');
            let mainChart;

            function renderChart(type) {
                if(mainChart) mainChart.destroy();
                
                const isVisit = type === 'visits';
                const labels = isVisit ? visitLabels : loanLabels;
                const data = isVisit ? visitData : loanData;
                const label = isVisit ? 'Jumlah Kunjungan' : 'Jumlah Peminjaman';
                const color = isVisit ? '#f97316' : '#4f46e5'; // Orange vs Indigo

                mainChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: label,
                            data: data,
                            backgroundColor: color,
                            borderRadius: 6,
                            barThickness: isVisit ? 40 : 20
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, grid: { borderDash: [2, 4], drawBorder: false } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }

            // Default render: Loans
            renderChart('loans');

            // Tombol Toggle Logic
            window.toggleChart = function(type) {
                const btnLoans = document.getElementById('btn-loans');
                const btnVisits = document.getElementById('btn-visits');
                
                if(type === 'loans') {
                    btnLoans.className = "px-3 py-1.5 rounded-lg text-xs font-bold bg-white shadow-sm text-slate-800 transition-all";
                    btnVisits.className = "px-3 py-1.5 rounded-lg text-xs font-bold text-slate-500 hover:text-slate-800 transition-all";
                    renderChart('loans');
                } else {
                    btnVisits.className = "px-3 py-1.5 rounded-lg text-xs font-bold bg-white shadow-sm text-slate-800 transition-all";
                    btnLoans.className = "px-3 py-1.5 rounded-lg text-xs font-bold text-slate-500 hover:text-slate-800 transition-all";
                    renderChart('visits');
                }
            };
        });

        // Popup Cari Anggota (Sama seperti sebelumnya)
        async function searchMemberPopup() {
            const { value: query } = await Swal.fire({
                title: 'Cari Data Siswa',
                input: 'text',
                inputPlaceholder: 'Ketik Nama atau NISN...',
                confirmButtonColor: '#2563eb',
                confirmButtonText: 'Cari',
                showCancelButton: true,
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-3xl',
                    confirmButton: 'rounded-xl px-6',
                    cancelButton: 'rounded-xl px-6',
                    input: 'rounded-xl border-slate-300'
                }
            });

            if (query) {
                try {
                    Swal.showLoading();
                    const res = await fetch('{{ route("library.circulation.searchStudent") }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ q: query })
                    });
                    const data = await res.json();

                    if(data.success) {
                        const student = data.student;
                        Swal.fire({
                            html: `
                                <div class="text-center">
                                    <div class="w-20 h-20 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-3xl font-black mx-auto mb-4 border-4 border-white shadow-lg">
                                        ${student.name.charAt(0)}
                                    </div>
                                    <h3 class="text-xl font-black text-slate-800">${student.name}</h3>
                                    <p class="text-slate-500 font-mono text-sm mb-4">${student.student_id}</p>
                                    
                                    <div class="grid grid-cols-2 gap-3 text-left bg-slate-50 p-4 rounded-xl border border-slate-100">
                                        <div>
                                            <p class="text-[10px] font-bold text-slate-400 uppercase">Kelas</p>
                                            <p class="font-bold text-slate-700">${student.school_class ? student.school_class.name : '-'}</p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-bold text-slate-400 uppercase">Status</p>
                                            ${data.has_overdue ? '<span class="text-rose-600 font-bold text-sm">⛔ Terblokir</span>' : '<span class="text-emerald-600 font-bold text-sm">✅ Aktif</span>'}
                                        </div>
                                        <div class="col-span-2 pt-2 border-t border-slate-200 mt-2">
                                            <p class="text-[10px] font-bold text-slate-400 uppercase">Buku Dipinjam</p>
                                            <p class="font-bold text-indigo-600 text-lg">${data.active_loans} Buku</p>
                                        </div>
                                    </div>
                                </div>
                            `,
                            showConfirmButton: false,
                            showCloseButton: true,
                            customClass: { popup: 'rounded-3xl' }
                        });
                    } else {
                        Swal.fire('Tidak Ditemukan', 'Data siswa tidak ada dalam database.', 'error');
                    }
                } catch (err) {
                    Swal.fire('Error', 'Terjadi kesalahan sistem.', 'error');
                }
            }
        }
    </script>
</x-app-layout>