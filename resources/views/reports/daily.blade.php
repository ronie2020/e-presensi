{{-- Halaman ini adalah tampilan untuk resources/views/reports/daily.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Rekapitulasi Absensi Harian') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Tampilkan pesan sukses jika ada --}}
            @if (session('success'))
                <div class="p-4 mb-4 bg-green-100 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Tampilkan error (misal: siswa sudah absen) --}}
            @if (session('error'))
                <div class="p-4 mb-4 bg-red-100 text-red-700 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif
            
            {{-- Tampilkan error validasi jika ada --}}
            @if ($errors->any())
                <div class="p-4 mb-4 bg-red-100 text-red-700 rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- 1. Form Input Absensi Manual (Kita pertahankan) -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Input Absensi Manual (Hadir/Sakit/Izin/Alpa)</h3>
                    <form action="{{ route('reports.storeManual') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            {{-- Dropdown Siswa --}}
                            <div>
                                <label for="student_id" class="block text-sm font-medium text-gray-700">Pilih Siswa</label>
                                <select name="student_id" id="student_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">-- Pilih dari Siswa Belum Absen --</option>
                                    {{-- Loop ini hanya berisi siswa yang BELUM ABSEN --}}
                                    @foreach ($belumAbsenList as $student)
                                        <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->schoolClass->name ?? 'N/A' }})</option>
                                    @endforeach
                                    {{-- Opsi tambahan akan di-inject oleh JS jika ada parameter URL --}}
                                </select>
                            </div>

                            {{-- Dropdown Status --}}
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status Kehadiran</label>
                                <select name="status" id="status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    {{-- DIPERBARUI: Menambahkan "Hadir" --}}
                                    <option value="Hadir">Hadir</option>
                                    <option value="Sakit">Sakit</option>
                                    <option value="Izin">Izin</option>
                                    <option value="Alfa">Alfa</option>
                                </select>
                            </div>

                            {{-- Catatan --}}
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700">Catatan (Opsional)</label>
                                <input type="text" name="notes" id="notes" placeholder="Misal: Surat dokter" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                        </div>
                        <button type="submit" class="mt-4 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Simpan Absensi Manual
                        </button>
                    </form>
                </div>
            </div>

            {{-- 
            ==================================================================
             BAGIAN BARU: FILTER DAN TABEL LOG ABSENSI (Menggantikan 4 Kartu)
            ==================================================================
            --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Rekap Kehadiran & Proses Harian</h3>

                    <!-- Tombol Tab (Mingguan & Bulanan belum berfungsi) -->
                    <div class="mb-4 border-b border-gray-200">
                        <nav class="flex space-x-4" aria-label="Tabs">
                            <a href="#" class="bg-blue-600 text-white px-4 py-2 rounded-t-md text-sm font-medium">
                                Harian
                            </a>
                            <a href="#" class="text-gray-500 hover:text-gray-700 px-4 py-2 text-sm font-medium">
                                Mingguan
                            </a>
                            <a href="#" class="text-gray-500 hover:text-gray-700 px-4 py-2 text-sm font-medium">
                                Bulanan
                            </a>
                        </nav>
                    </div>

                    <!-- Baris Filter dan Aksi -->
                    <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
                        
                        <!-- Filter Tanggal -->
                        <form action="{{ route('reports.daily') }}" method="GET" class="flex items-center gap-2">
                            <input type="date" name="date" 
                                   value="{{ $selectedDate_db->format('Y-m-d') }}" 
                                   class="rounded-md border-gray-300 shadow-sm sm:text-sm">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                Tampilkan Rekap
                            </button>
                        </form>

                        <!-- Tombol Aksi (Route perlu dibuat) -->
                        <div class="flex flex-wrap gap-2">
                            <button type="button" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                                Proses Siswa Alpa
                            </button>
                            <button type="button" class="inline-flex items-center px-4 py-2 bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-800">
                                Hapus Rekap Periode Ini
                            </button>
                            <button type="button" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-yellow-600">
                                Ekspor Seluruh Data Periode Ini
                            </button>
                        </div>
                    </div>

                    <!-- Tabel Log Absensi -->
                    <div class="overflow-x-auto border rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($todayAttendances as $attendance)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $attendance->student?->name ?? 'Siswa Dihapus' }}</div>
                                            <div class="text-sm text-gray-500">{{ $attendance->student?->student_id ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $attendance->student?->schoolClass?->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $attendance->attendance_date ? \Carbon\Carbon::parse($attendance->attendance_date)->format('d M Y') : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($attendance->status == 'Hadir') bg-green-100 text-green-800
                                                @elseif($attendance->status == 'Sakit') bg-yellow-100 text-yellow-800
                                                @elseif($attendance->status == 'Izin') bg-blue-100 text-blue-800
                                                @elseif($attendance->status == 'Alfa') bg-red-100 text-red-800
                                                @endif">
                                                {{ $attendance->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $attendance->time_in ? \Carbon\Carbon::parse($attendance->time_in)->format('H:i') : '--' }}
                                            /
                                            {{ $attendance->time_out ? \Carbon\Carbon::parse($attendance->time_out)->format('H:i') : '--' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $attendance->notes }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="#" class="text-yellow-600 hover:text-yellow-900">Edit</a>
                                            <a href="#" class="text-red-600 hover:text-red-900 ml-4">Hapus</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                                            Tidak ada data absensi untuk tanggal ini.
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

    {{-- SCRIPT BARU: Untuk auto-select siswa dari URL --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Baca parameter dari URL
            const urlParams = new URLSearchParams(window.location.search);
            const studentId = urlParams.get('student_id');
            const studentName = urlParams.get('student_name');
            const studentClass = urlParams.get('student_class');

            // 2. Cek jika parameter student_id ada
            if (studentId && studentName) {
                const studentSelect = document.getElementById('student_id');
                
                // 3. Cek apakah siswa sudah ada di dropdown (misal: di list 'Belum Absen')
                let existingOption = studentSelect.querySelector(`option[value="${studentId}"]`);

                if (existingOption) {
                    // 4. Jika sudah ada, langsung pilih
                    existingOption.selected = true;
                } else {
                    // 5. Jika tidak ada (misal: siswa sudah absen 'Sakit' tapi mau diubah jadi 'Hadir'),
                    //    buat option baru, tambahkan ke dropdown, dan pilih.
                    const newOption = document.createElement('option');
                    newOption.value = studentId;
                    newOption.textContent = `${studentName} (${studentClass || 'N/A'}) - (Override)`;
                    newOption.selected = true;
                    
                    // Tambahkan di pilihan kedua (setelah "-- Pilih...")
                    studentSelect.insertBefore(newOption, studentSelect.options[1]);
                }
            }
        });
    </script>
</x-app-layout>