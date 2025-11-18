{{-- Halaman ini adalah tampilan untuk resources/views/students/index.blade.php --}}
{{-- Kita menggunakan layout 'app-layout' yang sudah disediakan oleh Breeze --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Data Siswa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- Tampilkan pesan sukses jika ada --}}
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Tampilkan error (TERMASUK ERROR IMPOR) --}}
                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- Tampilkan error validasi jika ada --}}
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Kontainer Grid: 1/3 untuk Form, 2/3 untuk Tabel --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        {{-- Kolom 1: Form Tambah Siswa Baru --}}
                        <div class="md:col-span-1">
                            <h3 class="text-lg font-medium mb-4">Tambah Siswa Baru</h3>
                            {{-- Form akan dikirim ke route 'students.store' (method POST) --}}
                            <form action="{{ route('students.store') }}" method="POST">
                                @csrf {{-- Token Keamanan Laravel --}}
                                
                                {{-- ID Siswa (NISN) --}}
                                <div class="mb-4">
                                    <label for="student_id" class="block text-sm font-medium text-gray-700">ID Siswa (Unik, misal: NISN)</label>
                                    <input type="text" name="student_id" id="student_id" value="{{ old('student_id') }}" required 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>

                                {{-- Nama Lengkap Siswa --}}
                                <div class="mb-4">
                                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap Siswa</label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" required 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>

                                {{-- ID RFID (Opsional) --}}
                                <div class="mb-4">
                                    <label for="rfid_id" class="block text-sm font-medium text-gray-700">ID RFID (Opsional)</label>
                                    <input type="text" name="rfid_id" id="rfid_id" value="{{ old('rfid_id') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>

                                {{-- Kelas --}}
                                <div class="mb-4">
                                    <label for="class_id" class="block text-sm font-medium text-gray-700">Kelas</label>
                                    <select name="class_id" id="class_id" required 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <option value="">-- Pilih Kelas --</option>
                                        {{-- Kita akan mengisi dropdown ini dari controller --}}
                                        @foreach ($classes as $class)
                                            <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                                {{ $class->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Nomor WA Orang Tua (Opsional) --}}
                                <div class="mb-4">
                                    <label for="parent_wa_number" class="block text-sm font-medium text-gray-700">Nomor WA Orang Tua (Format: 62...)</label>
                                    <input type="text" name="parent_wa_number" id="parent_wa_number" value="{{ old('parent_wa_number') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>

                                <button type="submit" 
                                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Tambah Siswa
                                </button>
                            </form>

                            {{-- 1. TAMBAHKAN FORM IMPOR BARU DI SINI --}}
                            <div class="mt-8 pt-6 border-t">
                                <h3 class="text-lg font-medium mb-4">Impor Data Siswa (CSV/Excel)</h3>
                                <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="file" class="block text-sm font-medium text-gray-700">Pilih File</label>
                                        <input type="file" name="file" id="file" required 
                                               class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                                        <p class="mt-1 text-xs text-gray-500">
                                            File CSV/XLSX. Pastikan kolom header: <strong>studentid, nama, kelas, nomorwa, rfidid</strong>.
                                        </p>
                                        <p class="mt-1 text-xs text-gray-500">
                                            (Kolom 'kelas' harus berisi Nama Kelas yang sudah ada, misal: "7A").
                                        </p>
                                    </div>
                                    <button type="submit" 
                                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                        Impor
                                    </button>
                                </form>
                            </div>

                        </div>

                        {{-- Kolom 2: Daftar Siswa --}}
                        <div class="md:col-span-2">
                              {{-- 1. TAMBAHKAN TOMBOL DOWNLOAD DAN SEARCH/FILTER DI SINI --}}
                            <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-4 gap-4">
                                {{-- Tombol Download --}}
                                <a href="{{ route('students.export') }}" 
                                   class="inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Download Data Siswa (Excel)
                                </a>

                                {{-- Form Search (Belum berfungsi) --}}
                                <form action="{{ route('students.index') }}" method="GET" class="flex gap-2">
                                    <input type="text" name="search" placeholder="Cari nama siswa..." 
                                           value="{{ request('search') }}" {{-- Buat input 'sticky' --}}
                                           class="block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                                    
                                    <select name="filter_class_id" class="block rounded-md border-gray-300 shadow-sm sm:text-sm">
                                        <option value="">Semua Kelas</option>
                                        @foreach ($classes as $class)
                                            {{-- Buat dropdown 'sticky' --}}
                                            <option value="{{ $class->id }}" {{ request('filter_class_id') == $class->id ? 'selected' : '' }}>
                                                {{ $class->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                        Cari
                                    </button>
                                </form>
                            </div>
                                                      
                            {{-- (Fitur Search & Filter bisa ditambahkan di sini nanti) --}}

                            <div class="overflow-x-auto border rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse ($students as $student)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">{{ $student->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $student->student_id }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    {{-- Kita gunakan relasi 'schoolClass' yang sudah kita buat --}}
                                                    <div class="text-sm text-gray-900">{{ $student->schoolClass->name ?? 'N/A' }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    {{-- Tombol Edit --}}
                                                    <a href="{{ route('students.edit', $student->id) }}" class="text-yellow-600 hover:text-yellow-900">Edit</a>
                                                    
                                                    {{-- Tombol Hapus --}}
                                                    <form action="{{ route('students.destroy', $student->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus siswa ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900 ml-4">Hapus</button>
                                                    </form>
                                                    
                                                    {{-- === TOMBOL BARU (Diubah menjadi Button pemicu Modal) === --}}
                                                    
                                                    {{-- 1. Tombol Absen Manual --}}
                                                    <button type="button"
                                                       data-student-id="{{ $student->id }}"
                                                       data-student-name="{{ $student->name }}"
                                                       class="text-blue-600 hover:text-blue-900 ml-4 open-absen-modal">
                                                        Absen Manual
                                                    </button>
                                                    
                                                    {{-- 2. Tombol QR Code --}}
                                                    <button type="button"
                                                       data-student-id="{{ $student->student_id }}" {{-- QR Code biasanya berisi student_id (NISN) --}}
                                                       data-student-name="{{ $student->name }}"
                                                       class="text-green-600 hover:text-green-900 ml-4 open-qr-modal">
                                                        QR Code
                                                    </button>
                                                    {{-- === AKHIR TOMBOL BARU === --}}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                                                    Belum ada data siswa.
                                                </td>
                                            </tr>
                                        @endforelse {{-- <-- DIPERBAIKI: 'F' diubah menjadi 'f' --}}
                                    </tbody>
                                </table>
                            </div>
                            {{-- Pagination Links --}}
                            <div class="mt-4">
                                {{-- 2. TAMBAHKAN .appends() AGAR FILTER TETAP BERJALAN SAAT PINDAH HALAMAN --}}
                                {{ $students->appends(request()->query())->links() }}
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- =================================================================== --}}
    {{-- MODAL UNTUK ABSENSI MANUAL (Sesuai image_30cd41.png)                 --}}
    {{-- =================================================================== --}}
    <div id="absen-manual-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <!-- Header Modal -->
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Absensi Manual / Keterangan</h3>
                        <p class="text-sm text-gray-500" id="absen-modal-student-name">Nama Siswa</p>
                    </div>
                    <button type="button" id="absen-modal-close" class="text-gray-400 hover:text-gray-600">&times;</button>
                </div>

                <!-- Form Modal -->
                <form id="absen-manual-form" action="{{ route('reports.storeManual') }}" method="POST">
                    @csrf
                    {{-- Input tersembunyi untuk ID siswa --}}
                    <input type="hidden" name="student_id" id="absen-modal-student-id">

                    <div class="space-y-4">
                        <div>
                            <label for="absen-tanggal" class="block text-sm font-medium text-gray-700">Tanggal</label>
                            <input type="date" name="tanggal" id="absen-tanggal" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" readonly>
                            <small class="text-gray-500">Tanggal di-set ke hari ini.</small>
                        </div>
                        
                        <div>
                            <label for="absen-status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="absen-status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="Hadir">Hadir (Manual)</option>
                                <option value="Sakit">Sakit</option>
                                <option value="Izin">Izin</option>
                                <option value="Alfa">Alfa</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="absen-time-in" class="block text-sm font-medium text-gray-700">Waktu Masuk</label>
                                <input type="time" name="time_in" id="absen-time-in" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div>
                                <label for="absen-time-out" class="block text-sm font-medium text-gray-700">Waktu Pulang</label>
                                <input type="time" name="time_out" id="absen-time-out" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                        </div>

                        <div>
                            <label for="absen-notes" class="block text-sm font-medium text-gray-700">Keterangan Tambahan</label>
                            <textarea name="notes" id="absen-notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                        </div>
                    </div>

                    <!-- Footer Modal (Tombol) -->
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" id="absen-modal-cancel" class="bg-gray-200 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-300">
                            Batal
                        </button>
                        <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- =================================================================== --}}
    {{-- MODAL UNTUK QR CODE (Sesuai image_30cd69.png)                      --}}
    {{-- =================================================================== --}}
    <div id="qr-code-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-sm shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="qr-modal-student-name">QR Code untuk...</h3>
                
                <div class="mt-4 mb-6">
                    {{-- Gambar QR Code akan dimuat di sini --}}
                    <img id="qr-modal-image" src="https://placehold.co/250x250?text=Loading..." alt="QR Code" class="mx-auto border">
                    <p class="text-xs text-gray-500 mt-2">Ini adalah QR Code unik siswa.</p>
                </div>
                
                <div class="flex justify-center space-x-3">
                    <button type="button" id="qr-modal-close" class="bg-gray-200 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-300">
                        Tutup
                    </button>
                    <a id="qr-modal-download" href="#" download="qrcode.png" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">
                        Unduh
                    </a>
                </div>
            </div>
        </div>
    </div>


    {{-- =================================================================== --}}
    {{-- JAVASCRIPT UNTUK MODAL                                            --}}
    {{-- =================================================================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // === Logika Modal Absen Manual ===
            const absenModal = document.getElementById('absen-manual-modal');
            const absenModalName = document.getElementById('absen-modal-student-name');
            const absenModalInputId = document.getElementById('absen-modal-student-id');
            const absenModalTimeIn = document.getElementById('absen-time-in');
            const btnOpenAbsen = document.querySelectorAll('.open-absen-modal');
            const btnCloseAbsen = document.getElementById('absen-modal-close');
            const btnCancelAbsen = document.getElementById('absen-modal-cancel');

            btnOpenAbsen.forEach(button => {
                button.addEventListener('click', function() {
                    const studentId = this.dataset.studentId;
                    const studentName = this.dataset.studentName;

                    // Isi data siswa ke modal
                    absenModalName.textContent = studentName;
                    absenModalInputId.value = studentId;

                    // Set Waktu Masuk default ke jam sekarang
                    const now = new Date();
                    const hours = String(now.getHours()).padStart(2, '0');
                    const minutes = String(now.getMinutes()).padStart(2, '0');
                    absenModalTimeIn.value = `${hours}:${minutes}`;

                    // Tampilkan modal
                    absenModal.classList.remove('hidden');
                });
            });

            // Logika menutup modal absen
            const closeAbsenModal = () => absenModal.classList.add('hidden');
            btnCloseAbsen.addEventListener('click', closeAbsenModal);
            btnCancelAbsen.addEventListener('click', closeAbsenModal);

            // === Logika Modal QR Code ===
            const qrModal = document.getElementById('qr-code-modal');
            const qrModalName = document.getElementById('qr-modal-student-name');
            const qrModalImage = document.getElementById('qr-modal-image');
            const qrModalDownload = document.getElementById('qr-modal-download');
            const btnOpenQr = document.querySelectorAll('.open-qr-modal');
            const btnCloseQr = document.getElementById('qr-modal-close');

            btnOpenQr.forEach(button => {
                button.addEventListener('click', function() {
                    const studentId = this.dataset.studentId; // Ini adalah NISN
                    const studentName = this.dataset.studentName;

                    // Buat URL QR Code menggunakan API eksternal
                    const qrApiUrl = `https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=${encodeURIComponent(studentId)}`;

                    // Isi data siswa ke modal
                    qrModalName.textContent = `QR Code untuk ${studentName}`;
                    qrModalImage.src = qrApiUrl;
                    qrModalDownload.href = qrApiUrl; // Atur link download
                    qrModalDownload.download = `qrcode_${studentName.replace(/\s+/g, '_')}.png`;

                    // Tampilkan modal
                    qrModal.classList.remove('hidden');
                });
            });

            // Logika menutup modal QR
            btnCloseQr.addEventListener('click', () => qrModal.classList.add('hidden'));
        });
    </script>

</x-app-layout>