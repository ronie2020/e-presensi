<x-app-layout>
    {{-- Load Library Scanner & SweetAlert --}}
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style> 
        .scan-area { transition: border-color 0.3s, box-shadow 0.3s; }
        .scan-area:focus-within { border-color: #4f46e5; box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.5); }
        .scan-success { border-color: #16a34a !important; box-shadow: 0 0 0 2px rgba(22, 163, 74, 0.4) !important; }
        .scan-error { border-color: #dc2626 !important; box-shadow: 0 0 0 2px rgba(220, 38, 38, 0.4) !important; }
    </style>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-black text-gray-800 tracking-tight">Sirkulasi</h1>
                    <p class="text-gray-500 text-sm">Transaksi Peminjaman & Pengembalian Buku.</p>
                </div>
                <a href="{{ route('library.dashboard') }}" class="text-sm font-bold text-gray-500 hover:text-indigo-600">
                    &larr; Kembali ke Dashboard
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <!-- PANEL PEMINJAMAN -->
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-2 bg-indigo-100 rounded-lg text-indigo-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800">Peminjaman</h2>
                    </div>

                    <div class="space-y-6">
                        <!-- 1. Input Anggota -->
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1.5">1. Scan Kartu Anggota (NISN/RFID)</label>
                            <div class="flex gap-2">
                                <div id="member-scan-wrapper" class="flex-1 flex items-center p-2 border-2 border-dashed border-gray-300 rounded-xl scan-area bg-gray-50">
                                    <input type="text" id="memberInput" class="w-full bg-transparent border-none focus:ring-0 text-gray-700 font-mono placeholder-gray-400" placeholder="Scan kartu di sini..." autofocus>
                                </div>
                                <button type="button" onclick="openScanner('memberInput')" class="p-3 bg-indigo-50 hover:bg-indigo-100 rounded-xl text-indigo-600 transition-colors" title="Scan Kamera">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </button>
                            </div>
                        </div>

                        <!-- Info Anggota (Hidden by default) -->
                        <div id="memberInfo" class="hidden p-4 bg-indigo-50 rounded-xl border border-indigo-100"></div>

                        <!-- 2. Input Buku -->
                        <div id="bookInputSection" class="opacity-50 pointer-events-none transition-opacity">
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1.5">2. Scan Barcode Buku</label>
                            <div class="flex gap-2">
                                <div id="book-borrow-scan-wrapper" class="flex-1 flex items-center p-2 border-2 border-dashed border-gray-300 rounded-xl scan-area bg-gray-50">
                                    <input type="text" id="bookBorrowInput" class="w-full bg-transparent border-none focus:ring-0 text-gray-700 font-mono placeholder-gray-400" placeholder="Scan buku di sini...">
                                </div>
                                <button type="button" onclick="openScanner('bookBorrowInput')" class="p-3 bg-indigo-50 hover:bg-indigo-100 rounded-xl text-indigo-600 transition-colors" title="Scan Kamera">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4h2v-4zM6 8v4M6 20v-4M2 20h4M2 4h4M2 12h2m8 0h2M2 8v4M2 16h2M6 16h2M6 12h4m0-8h4m4 0h4M14 8h-2M10 8h2M10 4h2m4 0h2M18 8h2m0 4h2M18 16h2m-2 4h2M2 12v4m0 4v-4m10-4v4m2-4v4m4-4v4M6 4v4m12 0v4"></path></svg>
                                </button>
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="flex gap-3 pt-4 border-t border-gray-100">
                            <button type="button" onclick="resetBorrow()" class="flex-1 py-3 bg-gray-100 text-gray-600 font-bold rounded-xl hover:bg-gray-200 transition-colors">Batal</button>
                            <button type="button" id="btnProcessBorrow" onclick="processBorrow()" disabled class="flex-1 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                Proses Pinjam
                            </button>
                        </div>
                    </div>
                </div>

                <!-- PANEL PENGEMBALIAN -->
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-2 bg-emerald-100 rounded-lg text-emerald-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800">Pengembalian</h2>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1.5">Scan Barcode Buku</label>
                            <div class="flex gap-2">
                                <div id="return-scan-wrapper" class="flex-1 flex items-center p-2 border-2 border-dashed border-gray-300 rounded-xl scan-area bg-gray-50">
                                    <input type="text" id="returnInput" class="w-full bg-transparent border-none focus:ring-0 text-gray-700 font-mono placeholder-gray-400" placeholder="Scan buku yang dikembalikan...">
                                </div>
                                <button type="button" onclick="openScanner('returnInput')" class="p-3 bg-indigo-50 hover:bg-indigo-100 rounded-xl text-indigo-600 transition-colors" title="Scan Kamera">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </button>
                            </div>
                        </div>

                        <!-- Info Pengembalian -->
                        <div id="returnInfo" class="hidden p-4 bg-gray-50 rounded-xl border border-gray-200 text-sm">
                            <p class="text-gray-500 text-center py-4">Menunggu scan buku...</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- MODAL SCANNER (Global untuk halaman ini) --}}
    <div id="scannerModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm" aria-hidden="true" onclick="stopScanner()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-gray-200">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-bold text-gray-900 mb-2 text-center">Scan Barcode/QR</h3>
                            
                            <div class="relative bg-black rounded-xl overflow-hidden aspect-square">
                                <div id="reader" class="w-full h-full"></div>
                                <div class="absolute inset-0 pointer-events-none border-4 border-indigo-500/50 rounded-xl z-10"></div>
                                <p class="absolute bottom-4 left-0 right-0 text-center text-white text-xs bg-black/50 py-1 z-20">Arahkan kamera ke kode</p>
                                
                                {{-- Pesan Error --}}
                                <div id="camera-error" class="hidden absolute inset-0 bg-gray-900 flex flex-col items-center justify-center text-white p-4 text-center z-30">
                                    <svg class="w-12 h-12 text-red-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    <p class="font-bold text-lg">Kamera Bermasalah</p>
                                    <p class="text-sm text-gray-400 mt-1" id="error-text">Mohon izinkan akses kamera.</p>
                                    <button onclick="location.reload()" class="mt-4 px-4 py-2 bg-white text-gray-900 rounded-lg text-sm font-bold">Refresh Halaman</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" class="w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-bold text-gray-700 hover:bg-gray-50 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm" onclick="stopScanner()">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // --- VARIABLE GLOBAL ---
        let currentMember = null;
        let currentBook = null;
        let html5QrcodeScanner = null;
        let isScanning = false;
        let activeInputId = null; // Menyimpan ID input yang sedang aktif

        // --- FUNGSI SCANNER (Updated & Robust) ---
        function openScanner(inputId) {
            activeInputId = inputId;
            const modal = document.getElementById('scannerModal');
            const errorDiv = document.getElementById('camera-error');
            const errorText = document.getElementById('error-text');
            
            errorDiv.classList.add('hidden');
            modal.classList.remove('hidden');

            if (typeof Html5Qrcode === 'undefined') {
                alert('Library Scanner sedang dimuat...');
                return;
            }

            if (html5QrcodeScanner === null) {
                html5QrcodeScanner = new Html5Qrcode("reader");
            }

            html5QrcodeScanner.start(
                { facingMode: "environment" }, 
                { fps: 10, qrbox: { width: 250, height: 250 } },
                (decodedText) => {
                    // Success Callback
                    const targetInput = document.getElementById(activeInputId);
                    if(targetInput) {
                        targetInput.value = decodedText;
                        targetInput.dispatchEvent(new Event('change')); // Trigger logic
                    }
                    stopScanner();
                },
                (errorMessage) => { /* ignore */ }
            ).then(() => {
                isScanning = true;
            }).catch(err => {
                console.error("Error start kamera:", err);
                isScanning = false;
                errorDiv.classList.remove('hidden');
                
                let errorMessage = String(err);
                if (err && err.message) errorMessage = err.message;

                if (errorMessage.includes('NotAllowedError') || errorMessage.includes('Permission denied')) {
                    errorText.innerText = "Izin kamera ditolak. Klik ikon gembok di address bar -> Allow Camera.";
                } else if (errorMessage.includes('NotFoundError')) {
                    errorText.innerText = "Kamera tidak ditemukan.";
                } else if (errorMessage.includes('NotReadableError')) {
                    errorText.innerText = "Kamera sedang digunakan aplikasi lain.";
                } else {
                    errorText.innerText = "Error: " + errorMessage;
                }
            });
        }

        function stopScanner() {
            const modal = document.getElementById('scannerModal');
            if (html5QrcodeScanner && isScanning) {
                html5QrcodeScanner.stop().then(() => {
                    isScanning = false;
                    html5QrcodeScanner.clear();
                    modal.classList.add('hidden');
                }).catch(err => {
                    console.log("Stop failed:", err);
                    isScanning = false;
                    modal.classList.add('hidden');
                });
            } else {
                modal.classList.add('hidden');
            }
        }

        // --- LOGIKA PEMINJAMAN ---
        
        // 1. Scan Anggota
        document.getElementById('memberInput').addEventListener('change', async function(e) {
            const query = e.target.value;
            if(!query) return;

            const wrapper = document.getElementById('member-scan-wrapper');
            const infoBox = document.getElementById('memberInfo');
            
            try {
                const res = await fetch('{{ route("library.circulation.searchStudent") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ q: query })
                });
                const data = await res.json();

                if(data.success) {
                    currentMember = data.student;
                    wrapper.classList.add('scan-success');
                    wrapper.classList.remove('scan-error');
                    
                    infoBox.classList.remove('hidden');
                    infoBox.innerHTML = `
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-bold text-indigo-900 text-lg">${data.student.name}</h3>
                                <p class="text-xs text-indigo-600 font-mono">${data.student.student_id}</p>
                                <p class="text-xs text-gray-500 mt-1">Pinjaman Aktif: <b>${data.active_loans}</b> buku</p>
                            </div>
                            ${data.has_overdue ? '<span class="px-2 py-1 bg-red-100 text-red-600 text-[10px] font-bold rounded uppercase">Ada Tunggakan</span>' : '<span class="px-2 py-1 bg-emerald-100 text-emerald-600 text-[10px] font-bold rounded uppercase">Status Aman</span>'}
                        </div>
                    `;

                    if (!data.has_overdue) {
                        document.getElementById('bookInputSection').classList.remove('opacity-50', 'pointer-events-none');
                        document.getElementById('bookBorrowInput').focus();
                    } else {
                        Swal.fire('Peminjaman Diblokir', 'Siswa ini memiliki buku yang terlambat dikembalikan.', 'error');
                    }
                } else {
                    wrapper.classList.add('scan-error');
                    infoBox.classList.add('hidden');
                    currentMember = null;
                    Swal.fire('Gagal', data.message, 'error');
                }
            } catch (err) { console.error(err); }
        });

        // 2. Scan Buku (Pinjam)
        document.getElementById('bookBorrowInput').addEventListener('change', async function(e) {
            const query = e.target.value;
            if(!query) return;

            const wrapper = document.getElementById('book-borrow-scan-wrapper');
            
            try {
                const res = await fetch('{{ route("library.circulation.searchBook") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ q: query })
                });
                const data = await res.json();

                if(data.success && data.is_available) {
                    currentBook = data.book;
                    wrapper.classList.add('scan-success');
                    document.getElementById('btnProcessBorrow').disabled = false;
                    Swal.fire({
                        toast: true, position: 'top-end', icon: 'success', 
                        title: `Buku "${data.book.title}" siap dipinjam`, showConfirmButton: false, timer: 1500
                    });
                } else {
                    wrapper.classList.add('scan-error');
                    currentBook = null;
                    document.getElementById('btnProcessBorrow').disabled = true;
                    let msg = data.success ? 'Stok buku habis.' : 'Buku tidak ditemukan.';
                    Swal.fire('Gagal', msg, 'error');
                }
            } catch (err) { console.error(err); }
        });

        // 3. Proses Pinjam
        async function processBorrow() {
            if(!currentMember || !currentBook) return;
            try {
                const res = await fetch('{{ route("library.circulation.store") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ student_id: currentMember.id, book_id: currentBook.id })
                });
                const data = await res.json();

                if(data.success) {
                    Swal.fire('Berhasil', 'Buku berhasil dipinjam!', 'success');
                    resetBorrow();
                } else {
                    Swal.fire('Gagal', data.message, 'error');
                }
            } catch (err) {
                Swal.fire('Error', 'Terjadi kesalahan server.', 'error');
            }
        }

        function resetBorrow() {
            currentMember = null;
            currentBook = null;
            document.getElementById('memberInput').value = '';
            document.getElementById('bookBorrowInput').value = '';
            document.getElementById('memberInfo').classList.add('hidden');
            document.getElementById('bookInputSection').classList.add('opacity-50', 'pointer-events-none');
            document.getElementById('btnProcessBorrow').disabled = true;
            document.querySelectorAll('.scan-area').forEach(el => el.classList.remove('scan-success', 'scan-error'));
            document.getElementById('memberInput').focus();
        }

        // --- LOGIKA PENGEMBALIAN ---
        document.getElementById('returnInput').addEventListener('change', async function(e) {
            const query = e.target.value;
            if(!query) return;
            e.target.value = ''; 

            const infoBox = document.getElementById('returnInfo');
            infoBox.classList.remove('hidden');
            infoBox.innerHTML = '<p class="text-center py-4">Mencari data...</p>';

            try {
                const res = await fetch('{{ route("library.circulation.return") }}?check_only=1', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ book_code: query })
                });
                const result = await res.json();

                if(result.success) {
                    const data = result.data;
                    let dendaHtml = data.fine > 0 ? 
                        `<div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg text-center"><p class="text-xs font-bold text-red-500 uppercase">Terlambat ${data.late_days} Hari</p><p class="text-xl font-black text-red-700">Denda: Rp ${new Intl.NumberFormat('id-ID').format(data.fine)}</p></div>` : 
                        `<div class="mt-3 p-3 bg-emerald-50 border border-emerald-200 rounded-lg text-center"><p class="text-sm font-bold text-emerald-600">Tepat Waktu</p></div>`;

                    infoBox.innerHTML = `
                        <div class="text-center">
                            <p class="text-sm text-gray-500">Peminjam:</p>
                            <h3 class="text-lg font-bold text-gray-800">${data.student_name}</h3>
                            <p class="text-xs text-gray-400 mb-2">Tenggat: ${data.due_date}</p>
                            ${dendaHtml}
                            <button onclick="confirmReturn('${query}')" class="mt-4 w-full py-2 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700">Konfirmasi Kembali</button>
                        </div>
                    `;
                } else {
                    infoBox.innerHTML = `<p class="text-center text-rose-500 font-bold py-4">${result.message}</p>`;
                }
            } catch (err) {
                infoBox.innerHTML = '<p class="text-center text-red-500">Error koneksi.</p>';
            }
        });

        async function confirmReturn(bookCode) {
            try {
                const res = await fetch('{{ route("library.circulation.return") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ book_code: bookCode })
                });
                const data = await res.json();
                if(data.success) {
                    Swal.fire('Sukses', 'Buku berhasil dikembalikan.', 'success');
                    document.getElementById('returnInfo').classList.add('hidden');
                }
            } catch(err) {
                Swal.fire('Error', 'Gagal memproses pengembalian.', 'error');
            }
        }
    </script>
</x-app-layout>