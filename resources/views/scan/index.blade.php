{{-- Halaman ini adalah tampilan untuk resources/views/scan/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Scan Aktifitas Siswa') }}
            </h2>
            <div class="text-right">
                {{-- Kita akan gunakan JavaScript untuk update jam ini --}}
                <div id="clock" class="text-2xl font-bold text-gray-900">00:00:00</div>
                <div id="date" class="text-sm text-gray-500">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Tombol Pilihan Mode Pemindaian --}}
                    <div class="mb-6">
                        <div class="flex space-x-4 border-b">
                            <button data-type="Harian" class="scan-type-btn active-tab font-medium text-blue-600 py-4 px-6">
                                Absen Harian
                            </button>
                            <button data-type="Dhuha" class="scan-type-btn inactive-tab font-medium text-gray-500 py-4 px-6">
                                Sholat Dhuha
                            </button>
                            <button data-type="Dhuhur" class="scan-type-btn inactive-tab font-medium text-gray-500 py-4 px-6">
                                Sholat Dhuhur
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Kolom Kiri: Scanner -->
                        <div>
                            {{-- 
                                === DIKEMBALIKAN KE VERSI AWAL ===
                                1. Menggunakan 'aspect-square' lagi
                                2. 'div#qr-reader' tidak lagi 'absolute'
                                3. Bingkai (border-dashed) dari HTML dikembalikan
                            --}}
                            <div id="scanner-container" class="relative w-full aspect-square bg-gray-900 rounded-lg overflow-hidden">
                                {{-- Placeholder untuk QR Scanner --}}
                                <div id="qr-reader" class="w-full"></div>
                                {{-- Frame (Target Scan) --}}
                                <div class="absolute inset-0 flex items-center justify-center p-8 pointer-events-none">
                                    <div class="w-3/4 h-3/4 border-4 border-dashed border-gray-400 rounded-lg"></div>
                                </div>
                            </div>
                            <div id="scan-status" class="mt-4 text-center text-lg font-medium text-gray-700">Arahkan QR Code Siswa ke Kamera</div>
                            
                            {{-- Pesan Sukses/Error dari scan --}}
                            <div id="scan-result" class="mt-2 text-center p-4 rounded-lg hidden"></div>
                        </div>

                        <!-- Kolom Kanan: Log Pindai Terakhir -->
                        <div>
                            <h3 class="text-lg font-medium mb-4">Log Pindai Terakhir</h3>
                            <div class="border rounded-lg max-h-[450px] overflow-y-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <tbody id="scan-log" class="bg-white divide-y divide-gray-200">
                                        @forelse ($recentScans as $scan)
                                            <tr class="log-entry {{ $scan->status == 'Hadir' ? 'bg-green-50' : 'bg-yellow-50' }}">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">{{ $scan->student->name ?? 'Siswa Tidak Dikenal' }}</div>
                                                    <div class="text-sm text-gray-500">{{ $scan->student->student_id ?? 'N/A' }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $scan->time_in }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $scan->status == 'Hadir' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                        {{ $scan->status }} ({{ $scan->type }})
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr id="no-log-entry">
                                                <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                                                    Belum ada data pindai hari ini.
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
        </div>
    </div>

    {{-- CSS untuk Tab --}}
    <style>
        .active-tab {
            border-bottom: 2px solid #2563EB; /* blue-600 */
            color: #2563EB;
        }
        .inactive-tab {
            border-bottom: 2px solid transparent;
        }

        /* CSS TAMBAHAN SAYA (object-fit: cover) DIHAPUS */

    </style>

    {{-- Library untuk QR Scanner --}}
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    {{-- SCRIPT ASLI ANDA (HANYA DIUBAH 1 BARIS) --}}
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            // --- Variabel Global ---
            let selectedType = 'Harian'; // Tipe absen default
            const csrfToken = '{{ csrf_token() }}';
            const scanProcessUrl = '{{ route('scan.process') }}';
            const logTableBody = document.getElementById('scan-log');
            const noLogEntry = document.getElementById('no-log-entry');
            const scanStatus = document.getElementById('scan-status');
            const scanResult = document.getElementById('scan-result');

            // --- Logika Jam Digital ---
            const clockElement = document.getElementById('clock');
            function updateClock() {
                const now = new Date();
                clockElement.textContent = now.toTimeString().split(' ')[0];
            }
            setInterval(updateClock, 1000);
            updateClock();

            // --- Logika Ganti Tipe Absen ---
            const buttons = document.querySelectorAll('.scan-type-btn');
            buttons.forEach(button => {
                button.addEventListener('click', () => {
                    // Update Pilihan
                    selectedType = button.getAttribute('data-type');
                    
                    // Update Tampilan Tombol
                    buttons.forEach(btn => {
                        btn.classList.remove('active-tab', 'text-blue-600');
                        btn.classList.add('inactive-tab', 'text-gray-500');
                    });
                    button.classList.add('active-tab', 'text-blue-600');
                    button.classList.remove('inactive-tab', 'text-gray-500');
                    
                    scanStatus.textContent = `Mode Absen: ${selectedType}. Arahkan QR Code Siswa ke Kamera.`;
                });
            });

            // --- Logika QR Scanner (html5-qrcode) ---
            
            // Inisialisasi object QR Code
            const html5QrCode = new Html5Qrcode("qr-reader");
            
            // Callback jika scan sukses
            const qrCodeSuccessCallback = (decodedText, decodedResult) => {
                // 'decodedText' berisi data dari QR code (kita asumsikan ini adalah student_id)
                console.log(`Scan Sukses: ${decodedText}`);
                
                // Hentikan sejenak scanner agar tidak scan berkali-kali
                html5QrCode.pause();
                
                // Tampilkan status
                scanStatus.textContent = `Memproses ID: ${decodedText}...`;
                scanResult.classList.add('hidden');

                // Kirim data ke server
                processScanData(decodedText, selectedType);
            };

            // Konfigurasi scanner
            const config = { 
                fps: 10, 
                qrbox: { width: 250, height: 250 },
                aspectRatio: 1.0 // <-- SATU-SATUNYA PERUBAHAN JS: Minta video kotak (1:1)
            };

            // Fungsi untuk memulai scanner
            function startScanner(cameraId) {
                html5QrCode.start(
                    cameraId,     // Gunakan ID kamera yang ditemukan
                    config,
                    qrCodeSuccessCallback,
                    (errorMessage) => { 
                        // onScanFailure - biarkan saja
                    }
                ).catch((err) => {
                    console.error("Gagal memulai scanner:", err);
                    scanStatus.textContent = "Gagal memulai kamera. Pastikan izin sudah diberikan.";
                });
            }

            // Minta daftar kamera yang tersedia
            Html5Qrcode.getCameras().then(cameras => {
                if (cameras && cameras.length) {
                    console.log("Kamera ditemukan:", cameras);
                    scanStatus.textContent = "Memulai kamera...";
                    
                    // Ganti baris ini untuk memaksa kamera belakang
                    startScanner({ facingMode: "environment" }); 
                } else {
                    console.error("Tidak ada kamera ditemukan.");
                    scanStatus.textContent = "Tidak ada kamera yang terdeteksi.";
                }
            }).catch(err => {
                console.error("Error saat mencari kamera:", err);
                scanStatus.textContent = "Tidak bisa mengakses kamera. Cek izin browser.";
            });


            // --- Logika Kirim Data ke Server (AJAX/Fetch) ---
            async function processScanData(studentId, scanType) {
                try {
                    const response = await fetch(scanProcessUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            student_id: studentId,
                            type: scanType
                        })
                    });

                    const result = await response.json();

                    if (response.ok) {
                        // Sukses
                        showScanResult('success', result.message);
                        addScanToLog(result.scan);
                    } else {
                        // Gagal (misal: siswa tidak ditemukan, sudah absen)
                        showScanResult('error', result.message || 'Terjadi kesalahan.');
                    }

                } catch (error) {
                    console.error('Error:', error);
                    showScanResult('error', 'Gagal terhubung ke server.');
                } finally {
                    // Nyalakan lagi scanner setelah 2 detik
                    setTimeout(() => {
                        html5QrCode.resume(); // Ganti ini dari html5QrcodeScanner
                        scanStatus.textContent = `Mode Absen: ${selectedType}. Arahkan QR Code Siswa ke Kamera.`;
                    }, 2000);
                }
            }
            
            // --- Fungsi Helper Tampilan ---
            function showScanResult(type, message) {
                scanResult.classList.remove('hidden', 'bg-green-100', 'text-green-800', 'bg-red-100', 'text-red-800');
                if (type === 'success') {
                    scanResult.classList.add('bg-green-100', 'text-green-800');
                } else {
                    scanResult.classList.add('bg-red-100', 'text-red-800');
                }
                scanResult.textContent = message;
            }

            function addScanToLog(scan) {
                // Hapus pesan "Belum ada data" jika ada
                if (noLogEntry) {
                    noLogEntry.remove();
                }

                // Buat baris tabel baru
                const newRow = document.createElement('tr');
                newRow.className = `log-entry ${scan.status == 'Hadir' ? 'bg-green-50' : 'bg-yellow-50'}`;
                newRow.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">${scan.student.name}</div>
                        <div class="text-sm text-gray-500">${scan.student.student_id}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        ${scan.time_in}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${scan.status == 'Hadir' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}">
                            ${scan.status} (${scan.type})
                        </span>
                    </td>
                `;
                
                // Tambahkan baris baru di paling atas log
                logTableBody.prepend(newRow);
            }
        });
    </script>
</x-app-layout>