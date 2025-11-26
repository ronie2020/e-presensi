<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {{-- Tambahkan SweetAlert untuk popup pencarian --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-black text-gray-800 tracking-tight">Dashboard Petugas</h1>
                    <p class="text-gray-500 text-sm">Perpustakaan SMP Negeri 3 Lakbok</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- KOLOM UTAMA (KIRI) -->
                <div class="lg:col-span-2 space-y-8">

                    <!-- 1. Pintasan Cepat -->
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <h2 class="text-lg font-bold text-gray-800 mb-4">Pintasan Cepat</h2>
                        {{-- UPDATE: Grid diubah jadi 4 kolom agar muat tombol Cari --}}
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            
                            <!-- Tombol Kiosk -->
                            <a href="{{ route('library.kiosk.index') }}" target="_blank" class="flex flex-col items-center justify-center text-center p-4 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors cursor-pointer group">
                                <div class="p-3 bg-indigo-200 rounded-full mb-2 group-hover:bg-indigo-300 transition-colors">
                                    <svg class="w-6 h-6 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75" /></svg>
                                </div>
                                <span class="font-semibold text-indigo-800 text-xs">Kiosk Tamu</span>
                            </a>

                            <!-- Tombol Cari Anggota (BARU) -->
                            <button onclick="searchMemberPopup()" class="flex flex-col items-center justify-center text-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors cursor-pointer group">
                                <div class="p-3 bg-purple-200 rounded-full mb-2 group-hover:bg-purple-300 transition-colors">
                                    <svg class="w-6 h-6 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <span class="font-semibold text-purple-800 text-xs">Cari Anggota</span>
                            </button>
                            
                            <!-- Tombol Tambah Buku -->
                            <a href="{{ route('library.books.create') }}" class="flex flex-col items-center justify-center text-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors cursor-pointer group">
                                <div class="p-3 bg-blue-200 rounded-full mb-2 group-hover:bg-blue-300 transition-colors">
                                     <svg class="w-6 h-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18-3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6-2.292m0 0v14.25" /></svg>
                                </div>
                                <span class="font-semibold text-blue-800 text-xs">Tambah Buku</span>
                            </a>

                            <!-- Tombol Tambah Anggota -->
                            <a href="{{ route('students.create') }}" class="flex flex-col items-center justify-center text-center p-4 bg-yellow-50 hover:bg-yellow-100 rounded-lg transition-colors cursor-pointer group">
                                <div class="p-3 bg-yellow-200 rounded-full mb-2 group-hover:bg-yellow-300 transition-colors">
                                    <svg class="w-6 h-6 text-yellow-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m-7.5-2.962a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM12 15a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" /></svg>
                                </div>
                                <span class="font-semibold text-yellow-800 text-xs">Input Siswa</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- 2. Kartu Statistik Besar -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Total Judul Buku</p>
                                <p class="text-3xl font-bold text-gray-800">{{ number_format($totalBooks) }}</p>
                            </div>
                            <div class="p-3 bg-blue-100 rounded-full"><svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18-3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6-2.292m0 0v14.25" /></svg></div>
                        </div>
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Anggota Aktif</p>
                                <p class="text-3xl font-bold text-gray-800">{{ number_format($activeMembers) }}</p>
                            </div>
                            <div class="p-3 bg-yellow-100 rounded-full"><svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-4.663M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
                        </div>
                    </div>

                    <!-- 3. Aktivitas Terkini -->
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Aktivitas Terkini</h2>
                        <div class="space-y-4">
                            @forelse($recentActivities as $activity)
                                <div class="flex items-start space-x-3 pb-3 border-b border-gray-50 last:border-0">
                                    <div class="flex-shrink-0 mt-1 p-2 rounded-full {{ $activity->status == 'returned' ? 'bg-blue-100 text-blue-600' : 'bg-green-100 text-green-600' }}">
                                        @if($activity->status == 'returned')
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M15.707 15.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L12.414 11H17a1 1 0 110 2h-4.586l3.293 3.293a1 1 0 010 1.414z" clip-rule="evenodd"></path><path fill-rule="evenodd" d="M6 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 01-1 1z" clip-rule="evenodd"></path></svg>
                                        @else
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L7.586 9H3a1 1 0 110-2h4.586L4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path><path fill-rule="evenodd" d="M14 17a1 1 0 01-1-1V4a1 1 0 112 0v12a1 1 0 01-1 1z" clip-rule="evenodd"></path></svg>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-800">
                                            <b>{{ $activity->student->name }}</b> 
                                            {{ $activity->status == 'returned' ? 'mengembalikan' : 'meminjam' }} buku
                                        </p>
                                        <p class="text-xs text-gray-500 italic">{{ $activity->book->title }} - {{ $activity->updated_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center text-gray-500 py-4">Belum ada aktivitas.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- 4. Grafik Peminjaman -->
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Peminjaman Berdasarkan Kelas</h2>
                        <div class="h-64">
                             <canvas id="loanChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- SIDEBAR KANAN -->
                <div class="lg:col-span-1 space-y-8">
                     
                     <!-- 5. Status Buku Ringkas -->
                     <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 space-y-4">
                        {{-- Statistik Anggota (Dipindah ke sini agar rapi) --}}
                        <div class="grid grid-cols-2 gap-4 mb-2">
                            <div class="p-3 bg-gray-50 rounded-lg text-center border border-gray-200">
                                <p class="text-[10px] font-bold text-gray-400 uppercase">Meminjam</p>
                                <p class="text-xl font-black text-indigo-600">{{ $membersBorrowingCount }}</p>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-lg text-center border border-gray-200">
                                <p class="text-[10px] font-bold text-gray-400 uppercase">Diblokir</p>
                                <p class="text-xl font-black text-rose-600">{{ $blockedMembersCount }}</p>
                            </div>
                        </div>

                         <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Buku Dipinjam</p>
                                <p class="text-3xl font-bold text-gray-800">{{ $borrowedBooks }}</p>
                            </div>
                            <div class="p-3 bg-green-100 rounded-full"><svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3v1.5M3 21v-6m0 0l2.77-.693a9 9 0 016.208.682l.108.054a9 9 0 006.086.71l3.114-.732a48.524 48.524 0 01-.005-10.499l-3.11.732a9 9 0 01-6.085-.711l-.108-.054a9 9 0 00-6.208-.682L3 4.5M3 15V4.5" /></svg></div>
                        </div>
                         <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Terlambat Kembali</p>
                                <p class="text-3xl font-bold text-red-600">{{ $overdueBooks }}</p>
                            </div>
                            <div class="p-3 bg-red-100 rounded-full"><svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg></div>
                        </div>
                    </div>

                    <!-- 6. List Terlambat -->
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Daftar Terlambat</h2>
                        <div class="space-y-3 overflow-y-auto max-h-80 pr-2 custom-scrollbar">
                            @forelse($overdueList as $overdue)
                                <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg border border-red-100">
                                    <div>
                                        <p class="font-bold text-sm text-gray-800 line-clamp-1">{{ $overdue->book->title }}</p>
                                        <p class="text-xs text-gray-500">{{ $overdue->student->name }}</p>
                                    </div>
                                    <span class="text-xs font-bold text-red-600 whitespace-nowrap ml-2">
                                        {{ $overdue->overdue_days }} Hari
                                    </span>
                                </div>
                            @empty
                                <p class="text-center text-gray-500 py-4">Tidak ada buku terlambat.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- 7. Buku Populer -->
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Buku Populer</h2>
                        <div class="space-y-3">
                           @forelse($popularBooks as $book)
                                <div class="flex items-center justify-between text-sm">
                                    <div class="flex items-center gap-2 overflow-hidden">
                                        <span class="flex-shrink-0 w-6 h-6 bg-gray-100 text-gray-600 text-xs font-bold rounded-full flex items-center justify-center">{{ $loop->iteration }}</span>
                                        <p class="truncate text-gray-700">{{ $book->title }}</p>
                                    </div>
                                    <span class="font-bold text-gray-800 bg-gray-50 px-2 py-0.5 rounded">{{ $book->borrowings_count }}x</span>
                                </div>
                           @empty
                                <p class="text-center text-gray-500 py-4">Belum ada data statistik.</p>
                           @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // --- CHART JS ---
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('loanChart');
            if (ctx) {
                const chartLabels = @json($chartLabels);
                const chartData = @json($chartData);

                new Chart(ctx.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: chartLabels,
                        datasets: [{
                            label: 'Jumlah Peminjaman',
                            data: chartData,
                            backgroundColor: 'rgba(79, 70, 229, 0.8)',
                            borderColor: 'rgba(79, 70, 229, 1)',
                            borderWidth: 1,
                            borderRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: { beginAtZero: true, ticks: { stepSize: 1 } },
                            x: { grid: { display: false } }
                        },
                        plugins: { legend: { display: false } }
                    }
                });
            }
        });

        // --- LOGIKA POPUP PENCARIAN ANGGOTA ---
        async function searchMemberPopup() {
            const { value: query } = await Swal.fire({
                title: 'Cari Anggota',
                input: 'text',
                inputLabel: 'Masukkan Nama atau NISN',
                inputPlaceholder: 'Contoh: 12345...',
                showCancelButton: true,
                confirmButtonText: 'Cari',
                confirmButtonColor: '#4f46e5',
                cancelButtonText: 'Batal',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Anda harus menuliskan sesuatu!'
                    }
                }
            });

            if (query) {
                // Panggil API Search Student (yang sudah kita buat di Sirkulasi)
                try {
                    Swal.showLoading();
                    const res = await fetch('{{ route("library.circulation.searchStudent") }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ q: query })
                    });
                    const data = await res.json();

                    if(data.success) {
                        // Tampilkan Detail Anggota
                        const student = data.student;
                        Swal.fire({
                            title: student.name,
                            html: `
                                <div class="text-left bg-gray-50 p-4 rounded-lg border border-gray-200">
                                    <p><strong>NISN:</strong> ${student.student_id}</p>
                                    <p><strong>Kelas:</strong> ${student.school_class ? student.school_class.name : '-'}</p>
                                    <hr class="my-2">
                                    <p><strong>Buku Dipinjam:</strong> ${data.active_loans} Buku</p>
                                    <p><strong>Status:</strong> ${data.has_overdue ? '<span class="text-red-600 font-bold">TERBLOKIR (Ada Tunggakan)</span>' : '<span class="text-green-600 font-bold">AMAN</span>'}</p>
                                </div>
                                <div class="mt-4 flex gap-2 justify-center">
                                    <a href="/portal/${student.id}" target="_blank" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">Lihat Profil Lengkap</a>
                                </div>
                            `,
                            icon: data.has_overdue ? 'warning' : 'info',
                            showConfirmButton: false,
                            showCloseButton: true
                        });
                    } else {
                        Swal.fire('Tidak Ditemukan', 'Anggota tidak ditemukan.', 'error');
                    }
                } catch (err) {
                    Swal.fire('Error', 'Terjadi kesalahan server.', 'error');
                }
            }
        }
    </script>
</x-app-layout>