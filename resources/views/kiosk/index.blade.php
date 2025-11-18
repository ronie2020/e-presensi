{{-- DIPERBAIKI: Menggunakan sintaks @extends dan @section --}}
@extends('layouts.kiosk-layout')

@section('content')
<div class="w-full max-w-2xl mx-auto text-center p-8">
    
    <!-- Logo -->
    <div class="mb-6">
        <x-application-logo class="w-24 h-24 mx-auto" />
    </div>

    <!-- Judul -->
    <h1 class="text-4xl font-bold text-white">SMP Negeri 3 Lakbok</h1>
    <p class="text-2xl font-light text-gray-300 mt-2">Stasiun Absensi Scanner</p>

    <!-- Jam & Tanggal Digital -->
    <div class="my-10">
        <div id="kiosk-clock" class="text-7xl font-bold tracking-wider">00:00:00</div>
        <div id="kiosk-date" class="text-xl text-gray-400 mt-2">Sabtu, 15 November 2025</div>
    </div>

    <!-- Status Box -->
    <div id="status-box" class="w-full bg-gray-800 rounded-lg p-12 transition-all duration-300">
        <div class="flex items-center justify-center text-3xl font-medium text-gray-400">
            <x-heroicon-o-qr-code class="w-10 h-10 mr-4"/>
            <span>/</span>
            <x-icon-kiosk-mode class="w-10 h-10 ml-4"/> {{-- (Menggunakan ikon dari login) --}}
        </div>
        <p id="status-message" class="text-3xl font-medium text-gray-400 mt-4">Pindai QR Code atau Tap Kartu RFID</p>
    </div>
    
    <!-- Link Kembali -->
    <div class="mt-12">
        <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 hover:text-gray-300">
            &larr; Kembali ke Dashboard
        </a>
    </div>

    {{-- 
        INPUT TERSEMBUNYI
        Ini adalah 'perangkap' untuk input dari scanner RFID/QR (yang berfungsi seperti keyboard).
        Kita akan membuatnya auto-fokus.
    --}}
    <input type="text" id="scan-input" class="absolute -top-96" autofocus>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const clockElement = document.getElementById('kiosk-clock');
        const dateElement = document.getElementById('kiosk-date');
        const scanInput = document.getElementById('scan-input');
        const statusBox = document.getElementById('status-box');
        const statusMessage = document.getElementById('status-message');
        const csrfToken = '{{ csrf_token() }}';
        const processUrl = '{{ route('kiosk.process') }}';

        // --- 1. Fungsi Jam Digital ---
        function updateTime() {
            const now = new Date();
            
            // Format Waktu: 00:00:00
            const timeString = now.toTimeString().split(' ')[0];
            clockElement.textContent = timeString;

            // Format Tanggal: Sabtu, 15 November 2025
            const dateString = now.toLocaleDateString('id-ID', {
                weekday: 'long', 
                day: 'numeric', 
                month: 'long', 
                year: 'numeric'
            });
            dateElement.textContent = dateString;
        }
        setInterval(updateTime, 1000);
        updateTime();

        // --- 2. Fungsi Listener Input Scanner (RFID/QR Keyboard) ---
        
        // Selalu fokus ke input tersembunyi
        scanInput.focus();
        document.body.addEventListener('click', () => scanInput.focus());
        scanInput.addEventListener('blur', () => scanInput.focus());

        // Saat input di-submit (scanner menekan "Enter")
        scanInput.addEventListener('change', function(e) {
            const scanData = e.target.value;

            if (!scanData) return;

            console.log('Scan data received:', scanData);
            statusMessage.textContent = "Memproses...";
            statusBox.className = "w-full bg-gray-700 rounded-lg p-12 transition-all duration-300";

            // Kirim data ke server
            processScan(scanData);

            // Kosongkan input dan fokus kembali
            e.target.value = '';
            scanInput.focus();
        });

        // --- 3. Fungsi Kirim Data (Fetch API) ---
        async function processScan(data) {
            try {
                const response = await fetch(processUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ scan_data: data })
                });

                const result = await response.json();

                if (response.ok) {
                    // SUKSES
                    showStatus('success', `${result.student_name}`, `${result.message} (${result.time})`);
                } else {
                    // GAGAL (Sudah absen, tidak ditemukan)
                    showStatus('error', `${result.student_name}`, `${result.message}`);
                }

            } catch (error) {
                console.error('Fetch Error:', error);
                showStatus('error', 'Koneksi Gagal', 'Gagal terhubung ke server.');
            }
        }

        // --- 4. Fungsi Menampilkan Status ---
        function showStatus(type, title, message) {
            let titleColor = type === 'success' ? 'text-green-400' : 'text-red-400';
            let bgColor = type === 'success' ? 'bg-green-900' : 'bg-red-900';

            statusBox.className = `w-full ${bgColor} bg-opacity-50 border ${type === 'success' ? 'border-green-700' : 'border-red-700'} rounded-lg p-12 transition-all duration-300`;
            statusMessage.innerHTML = `
                <div class="text-3xl font-bold ${titleColor}">${title}</div>
                <div class="text-2xl font-medium text-gray-200 mt-2">${message}</div>
            `;

            // Kembali ke state awal setelah 3 detik
            setTimeout(() => {
                statusBox.className = "w-full bg-gray-800 rounded-lg p-12 transition-all duration-300";
                statusMessage.innerHTML = `
                    <div class="flex items-center justify-center text-3xl font-medium text-gray-400">
                        <x-heroicon-o-qr-code class="w-10 h-10 mr-4"/> / <x-icon-kiosk-mode class="w-10 h-10 ml-4"/>
                    </div>
                    <p class="text-3xl font-medium text-gray-400 mt-4">Pindai QR Code atau Tap Kartu RFID</p>
                `;
            }, 3000);
        }

    });
</script>
@endsection