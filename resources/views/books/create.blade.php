<x-app-layout>
    {{-- 
        FIX: Script Library dipindah ke sini (keluar dari @push) 
        agar pasti terbaca oleh browser meskipun Layout tidak punya @stack('scripts') 
    --}}
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <style>
        #reader video {
            object-fit: cover;
            width: 100% !important;
            height: 100% !important;
            border-radius: 0.75rem;
        }
    </style>

    <div class="py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header Navigasi --}}
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-black text-gray-800 tracking-tight">Tambah Buku Baru</h1>
                    <p class="text-sm text-gray-500 mt-1">Masukkan detail buku secara lengkap untuk inventaris perpustakaan.</p>
                </div>
                <a href="{{ route('library.books.index') }}" class="text-sm font-bold text-gray-500 hover:text-indigo-600 flex items-center gap-1 transition-colors">
                    Kembali
                </a>
            </div>

            {{-- Form Card --}}
            <div class="bg-white rounded-3xl shadow-xl shadow-indigo-50/50 border border-indigo-50 overflow-hidden">
                <div class="p-8">
                    
                    <form action="{{ route('library.books.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            
                            {{-- KOLOM KIRI: IDENTITAS UTAMA --}}
                            <div class="space-y-6">
                                <div class="bg-indigo-50/50 p-4 rounded-2xl border border-indigo-100">
                                    <h3 class="text-xs font-bold text-indigo-500 uppercase tracking-wider mb-4">Identitas Buku</h3>
                                    
                                    <div class="space-y-5">
                                        {{-- Kode Buku (DENGAN TOMBOL SCAN) --}}
                                        <div>
                                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1.5">Kode Buku / Barcode *</label>
                                            <div class="flex gap-2">
                                                <div class="relative flex-1">
                                                    <input type="text" name="book_code" id="book_code" value="{{ $autoCode }}" required 
                                                        class="w-full rounded-xl border-indigo-200 bg-white focus:border-indigo-500 focus:ring-indigo-500 font-mono font-bold text-indigo-700 pl-4 pr-10">
                                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                        <svg class="w-5 h-5 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                                    </div>
                                                </div>
                                                {{-- Tombol Trigger Scanner --}}
                                                <button type="button" onclick="startScanner()" class="shrink-0 p-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl transition-colors shadow-md shadow-indigo-200" title="Scan Barcode dengan Kamera">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                                </button>
                                            </div>
                                            <p class="text-[10px] text-indigo-400 mt-1 ml-1">Gunakan scanner USB atau klik ikon kamera untuk scan.</p>
                                        </div>

                                        {{-- Judul Buku --}}
                                        <div>
                                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1.5">Judul Buku *</label>
                                            <input type="text" name="title" placeholder="Masukkan judul lengkap..." required 
                                                class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>

                                        {{-- Kategori --}}
                                        <div>
                                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1.5">Kategori</label>
                                            <select name="category_id" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500">
                                                <option value="">-- Pilih Kategori --</option>
                                                @foreach($categories as $cat)
                                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Stok & Tahun (Grid Kecil) --}}
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1.5">Stok *</label>
                                                <input type="number" name="stock" value="1" min="0" required 
                                                    class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1.5">Tahun Terbit</label>
                                                <input type="number" name="year" placeholder="YYYY" min="1900" max="{{ date('Y') + 1 }}"
                                                    class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- KOLOM KANAN: DATA PUSTAKA --}}
                            <div class="space-y-6">
                                <div class="bg-gray-50/50 p-4 rounded-2xl border border-gray-100">
                                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Data Pustaka</h3>
                                    
                                    <div class="space-y-5">
                                        {{-- Pengarang --}}
                                        <div>
                                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1.5">Pengarang</label>
                                            <input type="text" name="author" placeholder="Nama Penulis" 
                                                class="w-full rounded-xl border-gray-200 bg-white focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>

                                        {{-- Penerbit --}}
                                        <div>
                                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1.5">Penerbit</label>
                                            <input type="text" name="publisher" placeholder="Nama Penerbit" 
                                                class="w-full rounded-xl border-gray-200 bg-white focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>

                                        {{-- Lokasi Rak --}}
                                        <div>
                                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1.5">Lokasi Rak</label>
                                            <div class="relative">
                                                <input type="text" name="shelf_location" placeholder="Contoh: R-01" 
                                                    class="w-full rounded-xl border-gray-200 bg-white focus:border-indigo-500 focus:ring-indigo-500 pl-10">
                                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                    <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Cover Buku --}}
                                        <div>
                                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1.5">Cover Buku (Opsional)</label>
                                            <input type="file" name="cover" accept="image/*" class="block w-full text-sm text-gray-500
                                                file:mr-4 file:py-2.5 file:px-4
                                                file:rounded-xl file:border-0
                                                file:text-xs file:font-bold
                                                file:bg-indigo-600 file:text-white
                                                hover:file:bg-indigo-700
                                                cursor-pointer border border-gray-200 rounded-xl bg-white
                                            "/>
                                            <p class="text-[10px] text-gray-400 mt-1">Format: JPG/PNG, Maks: 2MB</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- SINOPSIS (FULL WIDTH) --}}
                        <div class="border-t border-gray-100 pt-6">
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1.5">Sinopsis / Deskripsi</label>
                            <textarea name="description" rows="3" placeholder="Ringkasan cerita atau isi buku..."
                                class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        </div>

                        {{-- TOMBOL SIMPAN --}}
                        <div class="pt-4 flex justify-end">
                            <button type="submit" 
                                class="px-8 py-3.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Simpan Buku
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

  {{-- MODAL SCANNER --}}
    <div id="scannerModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm" aria-hidden="true" onclick="stopScanner()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-gray-200">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-bold text-gray-900 mb-2 text-center" id="modal-title">
                                Scan Barcode Buku
                            </h3>
                            
                            <!-- Area Kamera -->
                            <div class="relative bg-black rounded-xl overflow-hidden aspect-square">
                                <div id="reader" class="w-full h-full"></div>
                                <!-- Overlay Kotak Fokus -->
                                <div class="absolute inset-0 pointer-events-none border-4 border-indigo-500/50 rounded-xl z-10"></div>
                                <p class="absolute bottom-4 left-0 right-0 text-center text-white text-xs bg-black/50 py-1 z-20">Arahkan kamera ke barcode belakang buku</p>
                                
                                <!-- Pesan Error di Dalam Kamera -->
                                <div id="camera-error" class="hidden absolute inset-0 bg-gray-900 flex flex-col items-center justify-center text-white p-4 text-center z-30">
                                    <svg class="w-12 h-12 text-red-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    <p class="font-bold text-lg">Kamera Tidak Dapat Diakses</p>
                                    <p class="text-sm text-gray-400 mt-1" id="error-text">Pastikan Anda mengizinkan akses kamera di browser.</p>
                                    <button onclick="location.reload()" class="mt-4 px-4 py-2 bg-white text-gray-900 rounded-lg text-sm font-bold">Coba Refresh Halaman</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-white text-base font-bold text-gray-700 hover:bg-gray-100 focus:outline-none border-gray-300 sm:ml-3 sm:w-auto sm:text-sm" onclick="stopScanner()">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT SCANNER (VERSI FINAL & ROBUST) --}}
    <script>
        let html5QrcodeScanner = null;
        let isScanning = false;

        function startScanner() {
            const modal = document.getElementById('scannerModal');
            const errorDiv = document.getElementById('camera-error');
            const errorText = document.getElementById('error-text');
            
            // Reset tampilan
            errorDiv.classList.add('hidden');
            modal.classList.remove('hidden');

            // Cek apakah library sudah dimuat
            if (typeof Html5Qrcode === 'undefined') {
                alert('Library Scanner sedang dimuat, mohon tunggu sebentar...');
                return;
            }

            // Inisialisasi scanner jika belum ada
            if (html5QrcodeScanner === null) {
                html5QrcodeScanner = new Html5Qrcode("reader");
            }

            // Config Scanner
            const config = { 
                fps: 10, 
                qrbox: { width: 250, height: 150 },
                aspectRatio: 1.0 
            };

            // Mulai Scanner
            html5QrcodeScanner.start(
                { facingMode: "environment" }, // Pakai kamera belakang (HP)
                config,
                (decodedText, decodedResult) => {
                    // Callback Sukses
                    document.getElementById('book_code').value = decodedText;
                    stopScanner();
                },
                (errorMessage) => {
                    // Callback Error Scanning (biasanya diabaikan karena terjadi tiap frame)
                }
            ).then(() => {
                isScanning = true;
                console.log("Kamera berhasil dimulai.");
            }).catch(err => {
                // Callback Gagal Start Kamera
                console.error("Error start kamera:", err);
                isScanning = false;
                
                // Tampilkan pesan error cantik di dalam modal
                errorDiv.classList.remove('hidden');
                
                // --- PERBAIKAN DI SINI: Deteksi Error lebih canggih ---
                // Ubah error menjadi string agar bisa dicek isinya
                let errorMessage = String(err);
                if (err && err.message) {
                    errorMessage = err.message;
                }

                // Cek kata kunci error untuk memberikan solusi yang tepat
                if (errorMessage.includes('NotAllowedError') || errorMessage.includes('Permission denied') || errorMessage.includes('PermissionDeniedError')) {
                    errorText.innerText = "Izin kamera ditolak. Mohon klik ikon gembok/kamera di address bar (sebelah URL) browser Anda, pilih 'Allow' (Izinkan), lalu Refresh halaman.";
                } else if (errorMessage.includes('NotFoundError') || errorMessage.includes('DevicesNotFoundError')) {
                    errorText.innerText = "Kamera tidak ditemukan pada perangkat ini.";
                } else if (errorMessage.includes('NotReadableError') || errorMessage.includes('in use')) {
                    errorText.innerText = "Kamera mungkin sedang digunakan oleh aplikasi lain (seperti Zoom/Meet). Silakan tutup aplikasi tersebut lalu coba lagi.";
                } else {
                    // Tampilkan pesan asli jika error tidak dikenali
                    errorText.innerText = "Gagal: " + errorMessage;
                }
            });
        }

        function stopScanner() {
            const modal = document.getElementById('scannerModal');
            
            // Hanya stop jika scanner ada DAN statusnya sedang scanning
            if (html5QrcodeScanner && isScanning) {
                html5QrcodeScanner.stop().then(() => {
                    isScanning = false;
                    html5QrcodeScanner.clear();
                    modal.classList.add('hidden');
                }).catch(err => {
                    console.log("Gagal stop scanner:", err);
                    isScanning = false;
                    modal.classList.add('hidden'); // Paksa tutup modal
                });
            } else {
                // Jika tidak scanning atau error di awal, langsung tutup modal
                modal.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>