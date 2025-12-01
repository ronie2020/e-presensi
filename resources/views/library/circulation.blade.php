<x-app-layout>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header Navigasi -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <a href="{{ route('library.dashboard') }}" class="flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-blue-600 transition mb-1">
                        <i class="ph-bold ph-arrow-left"></i> Kembali ke Dashboard
                    </a>
                    <h1 class="text-3xl font-black text-slate-800 tracking-tight">Sirkulasi Buku</h1>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <!-- PANEL PEMINJAMAN (KIRI) -->
                <div class="bg-white p-6 md:p-8 rounded-[2rem] shadow-sm border border-slate-100 h-full flex flex-col relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-indigo-500"></div>
                    
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-xl">
                            <i class="ph-fill ph-hand-holding"></i>
                        </div>
                        <h2 class="text-xl font-black text-slate-800">Mode Peminjaman</h2>
                    </div>

                    <div class="space-y-6 flex-1">
                        <!-- Step 1: Anggota -->
                        <div class="relative group">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-2">1. Identitas Peminjam</label>
                            <div class="flex gap-2">
                                <div id="member-scan-wrapper" class="flex-1 flex items-center px-4 py-3 bg-slate-50 border-2 border-dashed border-slate-300 rounded-xl focus-within:border-indigo-500 focus-within:bg-white transition-all">
                                    <i class="ph-bold ph-user text-slate-400 mr-3"></i>
                                    <input type="text" id="memberInput" class="w-full bg-transparent border-none focus:ring-0 text-slate-700 font-bold placeholder-slate-400" placeholder="Scan Kartu / Ketik NISN" autofocus>
                                </div>
                                <button type="button" onclick="openScanner('memberInput')" class="p-3 bg-indigo-100 hover:bg-indigo-600 hover:text-white text-indigo-600 rounded-xl transition-all shadow-sm" title="Buka Kamera">
                                    <i class="ph-bold ph-qr-code text-xl"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Result Card: Anggota -->
                        <div id="memberInfo" class="hidden animate-fade-in-down">
                            <!-- Diisi via JS -->
                        </div>

                        <!-- Step 2: Buku -->
                        <div id="bookInputSection" class="opacity-50 pointer-events-none transition-all duration-300">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-2">2. Data Buku</label>
                            <div class="flex gap-2">
                                <div id="book-borrow-scan-wrapper" class="flex-1 flex items-center px-4 py-3 bg-slate-50 border-2 border-dashed border-slate-300 rounded-xl focus-within:border-indigo-500 focus-within:bg-white transition-all">
                                    <i class="ph-bold ph-book text-slate-400 mr-3"></i>
                                    <input type="text" id="bookBorrowInput" class="w-full bg-transparent border-none focus:ring-0 text-slate-700 font-bold placeholder-slate-400" placeholder="Scan Barcode Buku">
                                </div>
                                <button type="button" onclick="openScanner('bookBorrowInput')" class="p-3 bg-indigo-100 hover:bg-indigo-600 hover:text-white text-indigo-600 rounded-xl transition-all shadow-sm">
                                    <i class="ph-bold ph-barcode text-xl"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="pt-6 mt-auto flex gap-3">
                            <button type="button" onclick="resetBorrow()" class="px-6 py-3 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition">Reset</button>
                            <button type="button" id="btnProcessBorrow" onclick="processBorrow()" disabled class="flex-1 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition transform active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                                Konfirmasi Peminjaman
                            </button>
                        </div>
                    </div>
                </div>

                <!-- PANEL PENGEMBALIAN (KANAN) -->
                <div class="bg-white p-6 md:p-8 rounded-[2rem] shadow-sm border border-slate-100 h-full relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-emerald-500"></div>

                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-xl">
                            <i class="ph-fill ph-arrow-u-down-left"></i>
                        </div>
                        <h2 class="text-xl font-black text-slate-800">Pengembalian Cepat</h2>
                    </div>

                    <div class="bg-slate-50 rounded-2xl p-6 border border-slate-200 text-center mb-6">
                        <p class="text-sm text-slate-500 mb-4">Scan barcode buku untuk memproses pengembalian secara instan.</p>
                        
                        <div class="relative max-w-xs mx-auto">
                            <div id="return-scan-wrapper" class="flex items-center px-4 py-3 bg-white border-2 border-emerald-400 rounded-xl shadow-sm focus-within:ring-4 focus-within:ring-emerald-100 transition-all">
                                <i class="ph-bold ph-barcode text-emerald-500 mr-3 text-lg"></i>
                                <input type="text" id="returnInput" class="w-full bg-transparent border-none focus:ring-0 text-slate-800 font-bold placeholder-slate-300" placeholder="Scan Buku...">
                            </div>
                            <button onclick="openScanner('returnInput')" class="absolute right-2 top-2 p-1.5 text-emerald-600 hover:bg-emerald-50 rounded-lg">
                                <i class="ph-bold ph-camera"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Area Info Pengembalian -->
                    <div id="returnInfo" class="hidden">
                        <!-- Diisi via JS -->
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- MODAL SCANNER --}}
    <div id="scannerModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/90 backdrop-blur-sm transition-opacity" onclick="stopScanner()"></div>
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="bg-white rounded-3xl overflow-hidden shadow-2xl w-full max-w-md relative z-10">
                <div class="p-6">
                    <h3 class="text-lg font-black text-slate-800 text-center mb-4">Pindai Kode</h3>
                    <div class="relative bg-black rounded-2xl overflow-hidden aspect-square border-4 border-slate-100">
                        <div id="reader" class="w-full h-full"></div>
                        <div class="absolute inset-0 pointer-events-none border-[30px] border-black/50 z-10"></div>
                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none z-20">
                            <div class="w-48 h-48 border-2 border-red-500 rounded-xl relative">
                                <div class="absolute top-0 left-0 w-4 h-4 border-t-4 border-l-4 border-red-500 -mt-1 -ml-1"></div>
                                <div class="absolute top-0 right-0 w-4 h-4 border-t-4 border-r-4 border-red-500 -mt-1 -mr-1"></div>
                                <div class="absolute bottom-0 left-0 w-4 h-4 border-b-4 border-l-4 border-red-500 -mb-1 -ml-1"></div>
                                <div class="absolute bottom-0 right-0 w-4 h-4 border-b-4 border-r-4 border-red-500 -mb-1 -mr-1"></div>
                            </div>
                        </div>
                    </div>
                    <button onclick="stopScanner()" class="mt-6 w-full py-3 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition">Batalkan</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Script JavaScript --}}
    <script>
        // Copy logic JS dari kode sebelumnya, hanya sesuaikan class styling pada bagian render HTML (innerHTML).
        // Pastikan styling di dalam `innerHTML` menggunakan Tailwind seperti:
        // class="bg-emerald-50 border border-emerald-200 rounded-xl p-4" dll.
        // ... (Logika JS sama persis, hanya styling string HTML yang diubah) ...
        
        // Contoh update render memberInfo:
        /*
        infoBox.innerHTML = `
            <div class="flex items-start gap-4 p-4 bg-indigo-50 border border-indigo-100 rounded-xl">
                <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-indigo-600 font-black shadow-sm text-lg border border-indigo-100">
                    ${data.student.name.charAt(0)}
                </div>
                <div>
                    <h3 class="font-bold text-slate-800">${data.student.name}</h3>
                    <p class="text-xs text-slate-500 font-mono mb-1">${data.student.student_id}</p>
                    ${data.has_overdue 
                        ? '<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-rose-100 text-rose-700 text-[10px] font-bold uppercase"><i class="ph-bold ph-warning"></i> Bermasalah</span>' 
                        : '<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-700 text-[10px] font-bold uppercase"><i class="ph-bold ph-check"></i> Aktif</span>'}
                </div>
            </div>
        `;
        */
       
       // Sisa logic JS scanner dan fetch API tetap sama.
       // ...
       
        let html5QrcodeScanner = null;
        let activeInputId = null;
        let currentMember = null;
        let currentBook = null;

        function openScanner(inputId) {
            activeInputId = inputId;
            document.getElementById('scannerModal').classList.remove('hidden');
            
            if (!html5QrcodeScanner) {
                html5QrcodeScanner = new Html5Qrcode("reader");
            }
            
            html5QrcodeScanner.start(
                { facingMode: "environment" }, 
                { fps: 10, qrbox: { width: 250, height: 250 } },
                (decodedText) => {
                    document.getElementById(activeInputId).value = decodedText;
                    document.getElementById(activeInputId).dispatchEvent(new Event('change'));
                    stopScanner();
                },
                (errorMessage) => {}
            );
        }

        function stopScanner() {
            if (html5QrcodeScanner) {
                html5QrcodeScanner.stop().then(() => {
                    html5QrcodeScanner.clear();
                    document.getElementById('scannerModal').classList.add('hidden');
                });
            } else {
                document.getElementById('scannerModal').classList.add('hidden');
            }
        }

        // Logic Peminjaman (Member Scan)
        document.getElementById('memberInput').addEventListener('change', async function(e) {
            const query = e.target.value;
            if(!query) return;
            
            try {
                const res = await fetch('{{ route("library.circulation.searchStudent") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ q: query })
                });
                const data = await res.json();

                const infoBox = document.getElementById('memberInfo');
                const wrapper = document.getElementById('member-scan-wrapper');

                if(data.success) {
                    currentMember = data.student;
                    wrapper.classList.add('border-emerald-500', 'bg-emerald-50');
                    wrapper.classList.remove('border-slate-300', 'bg-slate-50', 'border-rose-500', 'bg-rose-50');
                    
                    infoBox.classList.remove('hidden');
                    infoBox.innerHTML = `
                        <div class="flex items-start gap-4 p-4 bg-indigo-50 border border-indigo-100 rounded-xl mt-4">
                            <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-indigo-600 font-black shadow-sm text-lg border border-indigo-100">
                                ${data.student.name.charAt(0)}
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800">${data.student.name}</h3>
                                <p class="text-xs text-slate-500 font-mono mb-2">${data.student.student_id}</p>
                                ${data.has_overdue 
                                    ? '<span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-rose-100 text-rose-700 text-[10px] font-bold uppercase"><i class="ph-bold ph-warning"></i> Ada Tunggakan</span>' 
                                    : '<span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-emerald-100 text-emerald-700 text-[10px] font-bold uppercase"><i class="ph-bold ph-check"></i> Status Aman</span>'}
                            </div>
                        </div>
                    `;

                    if(!data.has_overdue) {
                        const bookSection = document.getElementById('bookInputSection');
                        bookSection.classList.remove('opacity-50', 'pointer-events-none');
                        document.getElementById('bookBorrowInput').focus();
                    } else {
                        Swal.fire('Terblokir', 'Siswa memiliki buku yang belum dikembalikan melewati tenggat.', 'error');
                    }
                } else {
                    wrapper.classList.add('border-rose-500', 'bg-rose-50');
                    Swal.fire('Gagal', 'Siswa tidak ditemukan.', 'error');
                }
            } catch(err) { console.error(err); }
        });

        // Logic Scan Buku (Pinjam)
        document.getElementById('bookBorrowInput').addEventListener('change', async function(e) {
            const query = e.target.value;
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
                    wrapper.classList.add('border-emerald-500', 'bg-emerald-50');
                    document.getElementById('btnProcessBorrow').disabled = false;
                    Swal.fire({
                        toast: true, position: 'top-end', icon: 'success', 
                        title: 'Buku siap dipinjam', showConfirmButton: false, timer: 1500
                    });
                } else {
                    wrapper.classList.add('border-rose-500', 'bg-rose-50');
                    Swal.fire('Gagal', data.success ? 'Stok buku habis' : 'Buku tidak ditemukan', 'error');
                }
            } catch(err) {}
        });

        async function processBorrow() {
            if(!currentMember || !currentBook) return;
            // ... (Kode fetch store borrow sama seperti sebelumnya) ...
             try {
                const res = await fetch('{{ route("library.circulation.store") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ student_id: currentMember.id, book_id: currentBook.id })
                });
                const data = await res.json();
                if(data.success) {
                    Swal.fire('Berhasil', 'Transaksi peminjaman sukses!', 'success');
                    resetBorrow();
                } else {
                    Swal.fire('Gagal', data.message, 'error');
                }
            } catch (err) {}
        }

        function resetBorrow() {
            location.reload(); // Cara paling aman reset state
        }

        // Logic Pengembalian
        document.getElementById('returnInput').addEventListener('change', async function(e) {
            const query = e.target.value;
            e.target.value = '';
            const infoBox = document.getElementById('returnInfo');
            
            infoBox.classList.remove('hidden');
            infoBox.innerHTML = '<div class="text-center py-4"><div class="w-6 h-6 border-2 border-indigo-600 border-t-transparent rounded-full animate-spin mx-auto"></div></div>';

            try {
                const res = await fetch('{{ route("library.circulation.return") }}?check_only=1', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ book_code: query })
                });
                const result = await res.json();

                if(result.success) {
                    const data = result.data;
                    let dendaHtml = data.fine > 0 
                        ? `<div class="p-3 bg-rose-50 border border-rose-200 rounded-xl mb-3"><p class="text-xs font-bold text-rose-600 uppercase">Denda Keterlambatan</p><p class="text-xl font-black text-rose-700">Rp ${new Intl.NumberFormat('id-ID').format(data.fine)}</p></div>`
                        : `<div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl mb-3"><p class="text-sm font-bold text-emerald-600 flex items-center justify-center gap-2"><i class="ph-bold ph-check-circle"></i> Tepat Waktu</p></div>`;

                    infoBox.innerHTML = `
                        <div class="bg-white rounded-2xl border border-slate-200 p-4 text-center mt-4 shadow-sm animate-fade-in-up">
                            <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-500 font-black border border-white shadow-sm text-lg">
                                ${data.student_name.charAt(0)}
                            </div>
                            <h3 class="font-bold text-slate-800 text-lg">${data.student_name}</h3>
                            <p class="text-xs text-slate-400 mb-4">Mengembalikan Buku</p>
                            ${dendaHtml}
                            <button onclick="confirmReturn('${query}')" class="w-full py-3 bg-slate-900 text-white font-bold rounded-xl hover:bg-slate-800 transition shadow-lg">
                                Konfirmasi Pengembalian
                            </button>
                        </div>
                    `;
                } else {
                    infoBox.innerHTML = `<div class="p-4 bg-rose-50 text-rose-600 font-bold text-center rounded-xl border border-rose-100 mt-4">${result.message}</div>`;
                }
            } catch (err) {}
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
                    document.getElementById('returnInfo').innerHTML = '';
                    document.getElementById('returnInfo').classList.add('hidden');
                }
            } catch(err) {}
        }
    </script>
</x-app-layout>